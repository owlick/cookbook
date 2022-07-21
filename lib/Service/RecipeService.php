<?php

namespace OCA\Cookbook\Service;

use Exception;
use OCP\Files\NotFoundException;
use OCP\Image;
use OCP\IL10N;
use OCP\Files\IRootFolder;
use OCP\Files\File;
use OCP\Files\Folder;
use OCA\Cookbook\Db\RecipeDb;
use OCA\Cookbook\Exception\NoRecipeNameGivenException;
use OCP\PreConditionNotMetException;
use Psr\Log\LoggerInterface;
use OCA\Cookbook\Exception\UserFolderNotWritableException;
use OCA\Cookbook\Exception\RecipeExistsException;
use OCA\Cookbook\Helper\ImageService\ImageSize;
use OCA\Cookbook\Helper\UserConfigHelper;
use OCA\Cookbook\Helper\UserFolderHelper;
use OCA\Cookbook\Exception\HtmlParsingException;
use OCA\Cookbook\Exception\ImportException;
use OCA\Cookbook\Helper\Filter\JSONFilter;
use OCA\Cookbook\Helper\TextCleanupHelper;

/**
 * Main service class for the cookbook app.
 *
 * @package OCA\Cookbook\Service
 */
class RecipeService {
	private $root;
	private $user_id;
	private $db;
	private $il10n;
	/**
	 * @var UserFolderHelper
	 */
	private $userFolder;
	private $logger;

	/**
	 * @var HtmlDownloadService
	 */
	private $htmlDownloadService;

	/**
	 * @var RecipeExtractionService
	 */
	private $recipeExtractionService;

	/**
	 * @var UserConfigHelper
	 */
	private $userConfigHelper;

	/** @var TextCleanupHelper */
	private $textCleanupHelper;
	/**
	 * @var ImageService
	 */
	private $imageService;

	/** @var JSONFilter */
	private $jsonFilter;

	public function __construct(
		?string $UserId,
		IRootFolder $root,
		RecipeDb $db,
		UserConfigHelper $userConfigHelper,
		UserFolderHelper $userFolder,
		ImageService $imageService,
		IL10N $il10n,
		LoggerInterface $logger,
		HtmlDownloadService $downloadService,
		RecipeExtractionService $extractionService,
		TextCleanupHelper $textCleanupHelper,
		JSONFilter $jsonFilter
	) {
		$this->user_id = $UserId;
		$this->root = $root;
		$this->db = $db;
		$this->il10n = $il10n;
		$this->userFolder = $userFolder;
		$this->logger = $logger;
		$this->userConfigHelper = $userConfigHelper;
		$this->imageService = $imageService;
		$this->htmlDownloadService = $downloadService;
		$this->recipeExtractionService = $extractionService;
		$this->textCleanupHelper = $textCleanupHelper;
		$this->jsonFilter = $jsonFilter;
	}

	/**
	 * Get a recipe by its folder id.
	 *
	 * @param int $id
	 *
	 * @return array|null
	 */
	public function getRecipeById(int $id) {
		$file = $this->getRecipeFileByFolderId($id);

		if (!$file) {
			return null;
		}

		return $this->parseRecipeFile($file);
	}

	/**
	 * Get a recipe's modification time by its folder id.
	 *
	 * @param int $id
	 *
	 * @return int
	 */
	public function getRecipeMTime(int $id) {
		$file = $this->getRecipeFileByFolderId($id);

		if (!$file) {
			return null;
		}

		return $file->getMTime();
	}

	/**
	 * Returns a recipe file by folder id
	 *
	 * @param int $id
	 *
	 * @return File|null
	 */
	public function getRecipeFileByFolderId(int $id) {
		$user_folder = $this->userFolder->getFolder();
		$recipe_folder = $user_folder->getById($id);

		if (count($recipe_folder) <= 0) {
			return null;
		}

		$recipe_folder = $recipe_folder[0];

		if ($recipe_folder instanceof Folder === false) {
			return null;
		}

		foreach ($recipe_folder->getDirectoryListing() as $file) {
			if ($this->isRecipeFile($file)) {
				return $file;
			}
		}

		return null;
	}

	/**
	 * Checks the fields of a recipe and standardises the format
	 *
	 * @param array $json
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function checkRecipe(array $json): array {
		if (!$json) {
			throw new Exception('Recipe array was null');
		}

		if (empty($json['name'])) {
			throw new Exception('Field "name" is required');
		}

		return $this->jsonFilter->apply($json);
	}

	/**
	 * @param string $html
	 * @param mixed $url
	 *
	 * @return array
	 * @deprecated
	 */
	private function parseRecipeHtml($url, $html) {
		if (!$html) {
			return null;
		}

		// Make sure we don't have any encoded entities in the HTML string
		$html = html_entity_decode($html);

		// Start document parser
		$document = new \DOMDocument();

		$libxml_previous_state = libxml_use_internal_errors(true);

		try {
			if (!$document->loadHTML($html)) {
				throw new \Exception('Malformed HTML');
			}
			$errors = libxml_get_errors();
			$this->display_libxml_errors($url, $errors);
			libxml_clear_errors();
		} finally {
			libxml_use_internal_errors($libxml_previous_state);
		}

		$xpath = new \DOMXPath($document);

		$json_ld_elements = $xpath->query("//*[@type='application/ld+json']");

		foreach ($json_ld_elements as $json_ld_element) {
			if (!$json_ld_element || !$json_ld_element->nodeValue) {
				continue;
			}

			$string = $json_ld_element->nodeValue;

			// Some recipes have newlines inside quotes, which is invalid JSON. Fix this before continuing.
			$string = preg_replace('/\s+/', ' ', $string);

			$json = json_decode($string, true);

			// Look through @graph field for recipe
			if ($json && isset($json['@graph']) && is_array($json['@graph'])) {
				foreach ($json['@graph'] as $graph_item) {
					if (!isset($graph_item['@type']) || $graph_item['@type'] !== 'Recipe') {
						continue;
					}

					$json = $graph_item;
					break;
				}
			}

			// Check if json is an array for some reason
			if ($json && isset($json[0])) {
				foreach ($json as $element) {
					if (!$element || !isset($element['@type']) || $element['@type'] !== 'Recipe') {
						continue;
					}
					return $this->checkRecipe($element);
				}
			}

			if (!$json || !isset($json['@type']) || $json['@type'] !== 'Recipe') {
				continue;
			}

			return $this->checkRecipe($json);
		}

		// Parse HTML if JSON couldn't be found
		$json = [];

		$recipes = $xpath->query("//*[@itemtype='http://schema.org/Recipe']");

		if (!isset($recipes[0])) {
			throw new \Exception('Could not find recipe element');
		}

		$props = [
			'name',
			'image', 'images', 'thumbnail',
			'recipeYield',
			'keywords',
			'recipeIngredient', 'ingredients',
			'recipeInstructions', 'instructions', 'steps', 'guide',
		];

		foreach ($props as $prop) {
			$prop_elements = $xpath->query("//*[@itemprop='" . $prop . "']");

			foreach ($prop_elements as $prop_element) {
				switch ($prop) {
					case 'image':
					case 'images':
					case 'thumbnail':
						$prop = 'image';

						if (!isset($json[$prop]) || !is_array($json[$prop])) {
							$json[$prop] = [];
						}

						if (!empty($prop_element->getAttribute('src'))) {
							array_push($json[$prop], $prop_element->getAttribute('src'));
						} elseif (
							null !== $prop_element->getAttributeNode('content') &&
							!empty($prop_element->getAttributeNode('content')->value)
						) {
							array_push($json[$prop], $prop_element->getAttributeNode('content')->value);
						}

						break;

					case 'recipeIngredient':
					case 'ingredients':
						$prop = 'recipeIngredient';

						if (!isset($json[$prop]) || !is_array($json[$prop])) {
							$json[$prop] = [];
						}

						if (
							null !== $prop_element->getAttributeNode('content') &&
							!empty($prop_element->getAttributeNode('content')->value)
						) {
							array_push($json[$prop], $prop_element->getAttributeNode('content')->value);
						} else {
							array_push($json[$prop], $prop_element->nodeValue);
						}

						break;

					case 'recipeInstructions':
					case 'instructions':
					case 'steps':
					case 'guide':
						$prop = 'recipeInstructions';

						if (!isset($json[$prop]) || !is_array($json[$prop])) {
							$json[$prop] = [];
						}

						if (
							null !== $prop_element->getAttributeNode('content') &&
							!empty($prop_element->getAttributeNode('content')->value)
						) {
							array_push($json[$prop], $prop_element->getAttributeNode('content')->value);
						} else {
							array_push($json[$prop], $prop_element->nodeValue);
						}
						break;

					default:
						if (isset($json[$prop]) && $json[$prop]) {
							break;
						}

						if (
							null !== $prop_element->getAttributeNode('content') &&
							!empty($prop_element->getAttributeNode('content')->value)
						) {
							$json[$prop] = $prop_element->getAttributeNode('content')->value;
						} else {
							$json[$prop] = $prop_element->nodeValue;
						}
						break;
				}
			}
		}

		// Make one final desparate attempt at getting the instructions
		if (!isset($json['recipeInstructions']) || !$json['recipeInstructions'] || sizeof($json['recipeInstructions']) < 1) {
			$json['recipeInstructions'] = [];

			$step_elements = $recipes[0]->getElementsByTagName('p');

			foreach ($step_elements as $step_element) {
				if (!$step_element || !$step_element->nodeValue) {
					continue;
				}

				array_push($json['recipeInstructions'], $step_element->nodeValue);
			}
		}

		return $this->checkRecipe($json);
	}

	private function display_libxml_errors($url, $errors) {
		$error_counter = [];
		$by_error_code = [];

		foreach ($errors as $error) {
			$count = array_key_exists($error->code, $error_counter) ? $error_counter[$error->code] : 0;
			$error_counter[$error->code] = $count + 1;
			$by_error_code[$error->code] = $error;
		}

		foreach ($error_counter as $code => $count) {
			$error = $by_error_code[$code];

			switch ($error->level) {
				case LIBXML_ERR_WARNING:
					$error_message = "libxml: Warning $error->code ";
					break;
				case LIBXML_ERR_ERROR:
					$error_message = "libxml: Error $error->code ";
					break;
				case LIBXML_ERR_FATAL:
					$error_message = "libxml: Fatal Error $error->code ";
					break;
				default:
					$error_message = "Unknown Error ";
			}

			$error_message .= "occurred " . $count . " times while parsing " . $url . ". Last time in line $error->line" .
				" and column $error->column: " . $error->message;

			$this->logger->warning($error_message);
		}
	}

	/**
	 * @param int $id
	 */
	public function deleteRecipe(int $id) {
		$user_folder = $this->userFolder->getFolder();
		$recipe_folder = $user_folder->getById($id);

		if ($recipe_folder && count($recipe_folder) > 0) {
			$recipe_folder[0]->delete();
		}

		$this->db->deleteRecipeById($id);
	}

	/**
	 * @param array $json
	 *
	 * @return File
	 */
	public function addRecipe($json) {
		if (!$json || !isset($json['name']) || !$json['name']) {
			throw new NoRecipeNameGivenException($this->il10n->t('No recipe name was given. A unique name is required to store the recipe.'));
		}

		$now = date(DATE_ISO8601);

		// Sanity check
		$json = $this->checkRecipe($json);

		// Update modification date
		$json['dateModified'] = $now;

		// Create/move recipe folder
		$user_folder = $this->userFolder->getFolder();
		$recipe_folder = null;

		// Recipe already has an id, update it
		if (isset($json['id']) && $json['id']) {
			$recipe_folder = $user_folder->getById($json['id'])[0];

			$old_path = $recipe_folder->getPath();
			$new_path = dirname($old_path) . '/' . $json['name'];

			// The recipe is being renamed, move the folder
			if ($old_path !== $new_path) {
				if ($user_folder->nodeExists($json['name'])) {
					throw new RecipeExistsException($this->il10n->t('Another recipe with that name already exists'));
				}

				$recipe_folder->move($new_path);
			}

		// This is a new recipe, create it
		} else {
			$json['dateCreated'] = $now;

			if ($user_folder->nodeExists($json['name'])) {
				throw new RecipeExistsException($this->il10n->t('Another recipe with that name already exists'));
			}

			$recipe_folder = $user_folder->newFolder($json['name']);
		}

		// Write JSON file to disk
		$recipe_file = $this->getRecipeFileByFolderId($recipe_folder->getId());

		if (!$recipe_file) {
			$recipe_file = $recipe_folder->newFile('recipe.json');
		}

		// Rename .json file if it's not "recipe.json"
		if ($recipe_file->getName() !== 'recipe.json') {
			$recipe_file->move(str_replace($recipe_file->getName(), 'recipe.json', $recipe_file->getPath()));
		}

		$recipe_file->putContent(json_encode($json));

		// Download image and generate thumbnail
		$full_image_data = null;

		if (isset($json['image']) && $json['image']) {
			// The image is a URL
			if (strpos($json['image'], 'http') === 0) {
				$json['image'] = str_replace(' ', '%20', $json['image']);
				$full_image_data = file_get_contents($json['image']);

			// The image is a local path
			} else {
				try {
					$full_image_file = $this->root->get('/' . $this->user_id . '/files' . $json['image']);
					$full_image_data = $full_image_file->getContent();
				} catch (NotFoundException $e) {
					$full_image_data = null;
				}
			}

		// The image field was empty, remove images in the recipe folder
		} else {
			$this->imageService->dropImage($recipe_folder);
		}

		// If image data was fetched, write it to disk
		if ($full_image_data) {
			$this->imageService->setImageData($recipe_folder, $full_image_data);
		}

		// Write .nomedia file to avoid gallery indexing
		if (!$recipe_folder->nodeExists('.nomedia')) {
			$recipe_folder->newFile('.nomedia');
		}

		// Make sure the directory has been marked as changed
		$recipe_folder->touch();

		return $recipe_file;
	}

	/**
	 * Download a recipe from a url and store it in the files
	 *
	 * @param string $url The recipe URL
	 * @throws Exception
	 * @return File
	 */
	public function downloadRecipe(string $url): File {
		$this->htmlDownloadService->downloadRecipe($url);

		try {
			$json = $this->recipeExtractionService->parse($this->htmlDownloadService->getDom(), $url);
		} catch (HtmlParsingException $ex) {
			throw new ImportException($ex->getMessage(), null, $ex);
		}

		$json = $this->checkRecipe($json);

		if (!$json) {
			$this->logger->error('Importing parsers resulted in null recipe.' .
				'This is most probably a bug. Please report.');
			throw new ImportException($this->il10n->t('No recipe data found. This is a bug'));
		}

		$json['url'] = $url;

		return $this->addRecipe($json);
	}

	/**
	 * @return array
	 */
	public function getRecipeFiles() {
		$user_folder = $this->userFolder->getFolder();
		$recipe_folders = $user_folder->getDirectoryListing();
		$recipe_files = [];

		foreach ($recipe_folders as $recipe_folder) {
			$recipe_file = $this->getRecipeFileByFolderId($recipe_folder->getId());

			if (!$recipe_file) {
				continue;
			}

			$recipe_files[] = $recipe_file;
		}

		return $recipe_files;
	}

	/**
	 * Updates the search index (no more) and migrate file structure
	 * @deprecated
	 */
	public function updateSearchIndex() {
		try {
			$this->migrateFolderStructure();
		} catch (UserFolderNotWritableException $ex) {
			// Ignore migration if not permitted.
			$this->logger->warning("Cannot migrate cookbook file structure as not permitted.");
			throw $ex;
		}
	}

	private function migrateFolderStructure() {
		// Remove old cache folder if needed
		$legacy_cache_path = '/cookbook/cache';

		if ($this->root->nodeExists($legacy_cache_path)) {
			$this->root->get($legacy_cache_path)->delete();
		}

		// Restructure files if needed
		$user_folder = $this->userFolder->getFolder();

		foreach ($user_folder->getDirectoryListing() as $node) {
			// Move JSON files from the user directory into its own folder
			if ($this->isRecipeFile($node)) {
				$recipe_name = str_replace('.json', '', $node->getName());

				$node->move($node->getPath() . '_tmp');

				$recipe_folder = $user_folder->newFolder($recipe_name);

				$node->move($recipe_folder->getPath() . '/recipe.json');

			// Rename folders with .json extensions (this was likely caused by a migration bug)
			} elseif ($node instanceof Folder && strpos($node->getName(), '.json')) {
				$node->move(str_replace('.json', '', $node->getPath()));
			}
		}
	}

	/**
	 * Gets all keywords from the index
	 *
	 * @return array
	 */
	public function getAllKeywordsInSearchIndex() {
		return $this->db->findAllKeywords($this->user_id);
	}

	/**
	 * Gets all categories from the index
	 *
	 * @return array
	 */
	public function getAllCategoriesInSearchIndex() {
		return $this->db->findAllCategories($this->user_id);
	}



	/** Adds modification and creation date to each recipe in the list
	 *
	 * @param array $recipes
	 */
	private function addDatesToRecipes(array &$recipes) {
		foreach ($recipes as $i => $recipe) {
			if (! array_key_exists('dateCreated', $recipe) || ! array_key_exists('dateModified', $recipe)) {
				$r = $this->getRecipeById($recipe['recipe_id']);
				$recipes[$i]['dateCreated'] = $r['dateCreated'];
				$recipes[$i]['dateModified'] = $r['dateModified'];
			}
		}
	}

	/**
	 * Gets all recipes from the index
	 *
	 * @return array
	 */
	public function getAllRecipesInSearchIndex(): array {
		$recipes = $this->db->findAllRecipes($this->user_id);
		$this->addDatesToRecipes($recipes);
		return $recipes;
	}

	/**
	 * Get all recipes of a certain category
	 *
	 * @param string $category
	 *
	 * @return array
	 */
	public function getRecipesByCategory($category): array {
		$recipes = $this->db->getRecipesByCategory($category, $this->user_id);
		$this->addDatesToRecipes($recipes);
		return $recipes;
	}

	/**
	 * Get all recipes containing all of the keywords.
	 *
	 * @param string $keywords Keywords/tags as a comma-separated string.
	 *
	 * @return array
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 */
	public function getRecipesByKeywords($keywords): array {
		$recipes = $this->db->getRecipesByKeywords($keywords, $this->user_id);
		$this->addDatesToRecipes($recipes);
		return $recipes;
	}

	/**
	 * Search for recipes by keywords
	 *
	 * @param $keywords_string
	 * @return array
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 */
	public function findRecipesInSearchIndex($keywords_string): array {
		$keywords_string = strtolower($keywords_string);
		$keywords_array = [];
		preg_match_all('/[^ ,]+/', $keywords_string, $keywords_array);

		if (sizeof($keywords_array) > 0) {
			$keywords_array = $keywords_array[0];
		}

		$recipes = $this->db->findRecipes($keywords_array, $this->user_id);
		$this->addDatesToRecipes($recipes);
		return $recipes;
	}

	/**
	 * @param int $interval
	 * @throws PreConditionNotMetException
	 */
	public function setSearchIndexUpdateInterval(int $interval) {
		$this->userConfigHelper->setUpdateInterval($interval);
	}

	/**
	 * @param bool $printImage
	 * @throws PreConditionNotMetException
	 */
	public function setPrintImage(bool $printImage) {
		$this->userConfigHelper->setPrintImage($printImage);
	}

	/**
	 * Should image be printed with the recipe
	 * @return bool
	 */
	public function getPrintImage() {
		return $this->userConfigHelper->getPrintImage();
	}

	/**
	 * Get recipe file contents as an array
	 *
	 * @param File $file
	 *
	 * @return array
	 */
	public function parseRecipeFile($file) {
		if (!$file) {
			return null;
		}

		$json = json_decode($file->getContent(), true);

		if (!$json) {
			return null;
		}

		$json['id'] = $file->getParent()->getId();


		if (!array_key_exists('dateCreated', $json) && method_exists($file, 'getCreationTime')) {
			$json['dateCreated'] = $file->getCreationTime();
		}
		if (!array_key_exists('dateModified', $json)) {
			$json['dateModified'] = $file->getMTime();
		}

		return $this->checkRecipe($json);
	}

	/**
	 * Gets the image file for a recipe
	 *
	 * @param int $id
	 * @param string $size
	 *
	 * @return File
	 */
	public function getRecipeImageFileByFolderId($id, $size = 'thumb'): File {
		$recipe_folders = $this->root->getById($id);
		if (count($recipe_folders) < 1) {
			throw new Exception($this->il10n->t('Recipe with ID %d not found.', [$id]));
		}
		$recipe_folder = $recipe_folders[0];

		// TODO: Check that file is really an image
		switch ($size) {
			case 'full':
				return $this->imageService->getImageAsFile($recipe_folder);
			case 'thumb':
				return $this->imageService->getThumbnailAsFile($recipe_folder, ImageSize::THUMBNAIL);
			case 'thumb16':
				return $this->imageService->getThumbnailAsFile($recipe_folder, ImageSize::MINI_THUMBNAIL);
			default:
				throw new Exception($this->il10n->t('Image size "%s" is not recognized.', [$size]));
		}
	}

	/**
	 * Test if file is an image
	 *
	 * @param File $file
	 *
	 * @return bool
	 */
	private function isImage($file) {
		$allowedExtensions = ['jpg', 'jpeg', 'png'];
		if ($file->getType() !== 'file') {
			return false;
		}
		$ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
		$iext = strtolower($ext);
		if (!in_array($iext, $allowedExtensions)) {
			return false;
		}
		return true;
	}

	/**
	 * Test if file is a recipe
	 *
	 * @param File $file
	 *
	 * @return bool
	 */
	private function isRecipeFile($file) {
		$allowedExtensions = ['json'];

		if ($file->getType() !== 'file') {
			return false;
		}

		$ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
		$iext = strtolower($ext);

		if (!in_array($iext, $allowedExtensions)) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $str
	 * @param mixed $preserve_newlines
	 * @param mixed $remove_slashes
	 *
	 * @return string
	 */
	private function cleanUpString($str, $preserve_newlines = false, $remove_slashes = false) {
		return $this->textCleanupHelper->cleanUp($str, !$preserve_newlines, $remove_slashes);
	}
}
