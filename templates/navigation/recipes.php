<?php foreach($_['recipes'] as $recipe) { ?>
    <li>
        <a href="#<?php echo $recipe['recipe_id']; ?>">
            <img src="<?php echo $recipe['imageURL']; ?>">
            <?php echo $recipe['name']; ?>
        </a>
    </li>
<?php } ?>
