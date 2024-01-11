OC.L10N.register(
    "cookbook",
    {
    "No image with the matching MIME type was found on the server." : "Nenhuma imagem com o tipo MIME correspondente foi encontrada no servidor.",
    "Recipe with ID %d was not found in database." : "A receita com ID %d  não foi encontrada no banco de dados.",
    "Downloading of a file failed returned the following error message: %s" : "O download de um arquivo falhou retornou a seguinte mensagem de erro:%s",
    "No content encoding was detected in the content." : "Nenhuma codificação de conteúdo foi detectada no conteúdo.",
    "The given image for the recipe %s cannot be parsed. Aborting and skipping it." : "A imagem fornecida para a receita %s não pode ser analisada. Abortando e pulando.",
    "No valid recipe was left after heuristics of recipe %s." : "Nenhuma receita válida foi deixada após a heurística da receita %s.",
    "Heuristics failed for image extraction of recipe %s." : "A heurística falhou na extração de imagem da receita %s.",
    "Could not guess image URL as no recipe URL was found." : "Não foi possível adivinhar o URL da imagem porque nenhum URL de receita foi encontrado.",
    "Could not guess image URL scheme from recipe URL %s" : "Não foi possível adivinhar o esquema de URL da imagem do URL da receita %s",
    "Could not parse recipe ingredients. It is no array." : "Não foi possível analisar os ingredientes da receita. Não é nenhuma matriz.",
    "Could not parse recipe instructions as they are no array." : "Não foi possível analisar as instruções da receita, pois elas não são matrizes.",
    "Cannot parse recipe: Unknown object found during flattening of instructions." : "Não é possível analisar a receita: Objeto desconhecido encontrado durante a análise das instruções.",
    "Did not find any p or li entries in the raw string of the instructions." : "Não foi encontrada nenhuma entrada p ou li na string bruta das instruções.",
    "Could not parse the keywords for recipe {recipe}." : "Não foi possível analisar as palavras-chave da receita {recipe}.",
    "Could not parse the nutrition information successfully for recipe {name}." : "Não foi possível analisar as informações nutricionais com sucesso para a receita {name}.",
    "Using heuristics to parse the \"recipeYield\" field representing the number of servings of recipe {name}." : "Usando heurística para analisar o campo \"rendimento da receita\" que representa o número de porções da receita {name}.",
    "_Only a single number was found in the \"recipeYield\" field. Using it as number of servings._::_There are %n numbers found in the \"recipeYield\" field. Using the highest number found as number of servings._" : ["Apenas um único %n número foi encontrado no campo \"rendimento da receita\". Usando-o como número de porções. ","Apenas um único %n número foi encontrado no campo \"rendimento da receita\". Usando-o como número de porções. ","Apenas um único %n número foi encontrado no campo \"rendimento da receita\". Usando-o como número de porções. "],
    "Could not parse \"recipeYield\" field. Falling back to 1 serving." : "Não foi possível analisar o campo \"rendimento da receita\". Caindo de volta para 1 porção.",
    "Could not parse recipe tools. Expected array or string." : "Não foi possível analisar as ferramentas de receita. Matriz ou string esperada.",
    "Could not find recipe in HTML code." : "Não foi possível encontrar a receita no código HTML.",
    "JSON cannot be decoded." : "JSON não pode ser decodificado.",
    "No recipe was found." : "Nenhuma receita foi encontrada.",
    "Parsing of HTML failed." : "Falha na análise de HTML.",
    "Unsupported error level during parsing of XML output." : "Nível de erro não suportado durante a análise da saída XML.",
    "_Warning %u occurred while parsing %s._::_Warning %u occurred %n times while parsing %s._" : ["Aviso 1%u ocorreu 2%n vezes durante a análise 3%s.","Aviso %u ocorreu %n vezes durante a análise %s.","Aviso %u ocorreu %n vezes durante a análise %s."],
    "_Error %u occurred while parsing %s._::_Error %u occurred %n times while parsing %s._" : ["Ocorreu 1%u um erro 2%n vezes durante a análise 3%s.","Ocorreu %u um erro %n vezes durante a análise %s.","Ocorreu %u um erro %n vezes durante a análise %s."],
    "_Fatal error %u occurred while parsing %s._::_Fatal error %u occurred %n times while parsing %s._" : ["Ocorreu um erro 1%u fatal 2%n vezes durante a análise 3%s.","Ocorreu um erro %u fatal %n vezes durante a análise %s.","Ocorreu um erro %u fatal %n vezes durante a análise %s."],
    "First time it occurred in line %u and column %u" : "Primeira vez que ocorreu em linha %u e coluna %u",
    "Could not parse duration {duration}" : "Não foi possível analisar a duração {duration}",
    "The recipe has already an image file. Cannot create a new one." : "A receita já tem um arquivo de imagem. Não é possível criar um novo.",
    "There is no primary image for the recipe present." : "Não há imagem primária para a receita presente.",
    "Cannot parse non-POST multipart encoding. This is a bug." : "Não é possível analisar a codificação non-POST multipart. Isso é um bug.",
    "Cannot detect type of transmitted data. This is a bug, please report it." : "Não é possível detectar o tipo dos dados transmitidos. Isso é um erro, por favor, informe-o.",
    "Invalid URL-encoded string found. Please report a bug." : "String codificada por URL inválida encontrada. Por favor, comunique o erro.",
    "Could not parse timestamp {timestamp}" : "Não foi possível analisar o carimbo de data/hora {timestamp}",
    "The user is not logged in. No user configuration can be obtained." : "O usuário não está conectado. Nenhuma configuração de usuário pode ser obtida.",
    "Recipes" : "Receitas",
    "The user folder cannot be created due to missing permissions." : "A pasta do usuário não pode ser criada devido a permissões ausentes.",
    "The configured user folder is a file." : "A pasta de usuário configurada é um arquivo.",
    "User cannot create recipe folder" : "O usuário não pode criar uma pasta de receitas",
    "in %s" : "em %s",
    "The JSON file in the folder with ID %d does not have a valid name." : "O arquivo JSON na pasta com ID %d não tem um nome válido.",
    "Could not parse URL" : "Não foi possível analisar o URL",
    "Exception while downloading recipe from %s." : "Exceção ao baixar a receita de %s.",
    "Download from %s failed as HTTP status code %d is not in expected range." : "Baixar de %s falhou porque o código de status HTTP %d não está no intervalo esperado.",
    "Could not find a valid encoding when parsing %s." : "Não foi possível encontrar uma codificação válida ao analisar %s.",
    "No parser found for the given import." : "Nenhum analisador encontrado para a importação fornecida.",
    "No recipe name was given. A unique name is required to store the recipe." : "Nenhum nome de receita foi dado. Um nome exclusivo é necessário para armazenar a receita.",
    "Another recipe with that name already exists" : "Já existe outra receita com esse nome ",
    "No recipe data found. This is a bug" : "Nenhum dado de receita encontrado. Isso é um bug",
    "Recipe with ID %d not found." : "Receita com ID %d não encontrada.",
    "Image size \"%s\" is not recognized." : "Tamanho da imagem \"%s\" não é reconhecido.",
    "The full-sized image is not a thumbnail." : "A imagem em tamanho real não é uma miniatura.",
    "The thumbnail type %d is not known." : "O tipo de miniatura  %d não é conhecido.",
    "Cookbook" : "Livro de Receitas",
    "An integrated cookbook using schema.org JSON files as recipes" : "Um livro de receitas integrado usando arquivos JSON do schema.org como receitas",
    "A library for all your recipes. It uses JSON files following the schema.org recipe format. To add a recipe to the collection, you can paste in the URL of the recipe, and the provided web page will be parsed and downloaded to whichever folder you specify in the app settings." : "Uma biblioteca para todas as suas receitas. Ela usa arquivos JSON seguindo o formato da receita do schema.org. Para adicionar uma receita à coleção, você pode colar a URL da receita. A página da web será analisada e baixada para a pasta especificada nas configurações do aplicativo.",
    "Editing recipe" : "Editando receita",
    "Viewing recipe" : "Visualizando receita",
    "All recipes" : "Todas as receitas",
    "None" : "Nenhum",
    "Loading app" : "Carregando aplicativo",
    "Loading recipe" : "Carregando receita",
    "Recipe not found" : "Receita não encontrada",
    "Page not found" : "Página não encontrada",
    "Creating new recipe" : "Criando nova receita",
    "Edit" : "Editar",
    "Save" : "Salvar",
    "Search" : "Pesquisar",
    "Filter" : "Filtro",
    "Reload recipe" : "Recarregar receita",
    "Abort editing" : "Abortar edição",
    "Print recipe" : "Imprimir receita",
    "Clone recipe" : "Receita de clonagem",
    "Delete recipe" : "Excluir receita",
    "Category" : "Categoria",
    "Recipe name" : "Nome da receita",
    "Tags" : "Etiquetas",
    "Search for recipes" : "Pesquisar por receitas",
    "Are you sure you want to delete this recipe?" : "Quer realmente excluir esta receita?",
    "Delete failed" : "Erro na exclusão",
    "Cannot access recipe folder." : "Não é possível acessar a pasta de receitas.",
    "Select recipe folder" : "Selecione a pasta de receitas",
    "Path to your recipe collection" : "Caminho para sua coleção de receitas",
    "You are logged in with a guest account. Therefore, you are not allowed to generate arbitrary files and folders on this Nextcloud instance. To be able to use the Cookbook app as a guest, you need to specify a folder where all recipes are stored. You will need write permission to this folder." : "Você está autenticado com uma conta de convidado. Portanto, você não está autorizado a gerar arquivos e pastas arbitrários nesta instância Nextcloud. Para poder usar o aplicativo Cookbook como convidado, você precisa especificar uma pasta onde todas as receitas estão armazenadas. Será necessário permissão de gravação para esta pasta.",
    "Create recipe" : "Criar receita",
    "Download recipe from URL" : "Baixar a receita da URL",
    "Uncategorized recipes" : "Receitas sem categoria",
    "Categories" : "Categorias ",
    "Rename" : "Renomear",
    "Enter new category name" : "Insira o novo nome da categoria ",
    "Cookbook settings" : "Configurações do livro de receitas",
    "Failed to load category {category} recipes" : "Não foi possível carregar as receitas da categoria {category}",
    "Failed to update name of category \"{category}\"" : "Falha ao atualizar o nome da categoria \"{category}\" ",
    "The server reported an error. Please check." : "O servidor relatou um erro. Por favor, verifique.",
    "Could not query the server. This might be a network problem." : "Não foi possível consultar o servidor. Isso pode ser um problema de rede.",
    "Loading category recipes …" : "Carregando receitas da categoria...",
    "Failed to fetch categories" : "Erro ao obter categorias",
    "Enter URL or select from your Nextcloud instance on the right" : "Coloque a URL ou selecione a sua instância Nextcloud à direita",
    "Pick a local image" : "Escolha uma imagem local",
    "Path to your recipe image" : "Caminho para imagens das receitas",
    "Move entry up" : "Mover entrada para cima",
    "Move entry down" : "Mova entrada para baixo",
    "Insert entry above" : "Inserir entrada acima",
    "Delete entry" : "Excluir entrada",
    "Add" : "Adicionar",
    "Select option" : "Selecione a opção",
    "No recipes created or imported." : "Nenhuma receita criada ou importada.",
    "To get started, you may use the text box in the left navigation bar to import a new recipe. Click below to create a recipe from scratch." : "Para começar, você pode usar a caixa de texto na barra de navegação esquerda para importar uma nova receita. Clique abaixo para criar uma receita do zero.",
    "No recipes" : "Sem receitas",
    "Select order" : "Selecione o pedido",
    "Name" : "Nome",
    "Creation date" : "Data de criação",
    "Modification date" : "Modificação de data",
    "Toggle keyword" : "Alternar palavra-chave",
    "Keyword not contained in visible recipes" : "Palavra-chave não encontrada nas receitas visíveis",
    "Toggle keyword area size" : "Alternar o tamanho da área da senha",
    "Order keywords by number of recipes" : "Ordenar senhas pelo número de recipientes",
    "Order keywords alphabetically" : "Ordenar as senhas alfabeticamente",
    "Recipe folder" : "Pasta da receita",
    "Rescan library" : "Verificar biblioteca novamente",
    "Please pick a folder" : "Escolha uma pasta",
    "Update interval in minutes" : "Intervalo de atualização em minutos",
    "Recipe display settings" : "Configurações de exibição da receita",
    "Print image with recipe" : "Imprimir imagem com receita",
    "Show keyword cloud in recipe lists" : "Mostrar nuvem de senhas nas listas de recipientes",
    "Info blocks" : "Info blocks",
    "Control which blocks of information are shown in the recipe view. If you do not use some features and find them distracting, you may hide them." : "Control which blocks of information are shown in the recipe view. If you do not use some features and find them distracting, you may hide them.",
    "Preparation time" : "Tempo de preparação",
    "Cooking time" : "Tempo de Cozimento",
    "Total time" : "Tempo total",
    "Nutrition information" : "Nutrition information",
    "Tools" : "Ferramentas",
    "Frontend debug settings" : "Configurações de depuração do front-end",
    "This allows to temporarily enable logging in the browser console in case of problems. You will not need these settings by default." : "Isso permite ativar temporariamente o login no console do navegador em caso de problemas. Você não precisará dessas configurações por padrão.",
    "Enable debugging" : "Habilitar depuração",
    "Could not set preference for image printing" : "Não foi possível definir a preferência para impressão de imagem",
    "Could not set recipe update interval to {interval}" : "Não foi possível definir o intervalo de atualização da receita para {interval}",
    "Could not save visible info blocks" : "Não foi possível salvar os blocos de informações visíveis",
    "Could not set recipe folder to {path}" : "Não foi possível definir a pasta de receitas para {path}",
    "Dismiss" : "Dispensar",
    "Cancel" : "Cancelar",
    "OK" : "OK",
    "The page was not found" : "A página não foi encontrada",
    "Description" : "Descrição",
    "URL" : "URL",
    "Image" : "Imagem",
    "Preparation time (hours:minutes)" : "Tempo de preparação (horas:minutos)",
    "Cooking time (hours:minutes)" : "Tempo de cozimento (horas:minutos)",
    "Total time (hours:minutes)" : "Tempo total (horas:minutos)",
    "Choose category" : "Escolher categoria",
    "Keywords" : "Palavras-chave",
    "Choose keywords" : "Escolher palavras-chave",
    "Servings" : "Porções",
    "Toggle if the number of servings is present" : "Alternar se o número de porções estiver presente",
    "Nutrition Information" : "Informação nutricional",
    "Pick option" : "Escolha uma opção",
    "Ingredients" : "Ingredientes",
    "Instructions" : "Instruções",
    "You have unsaved changes! Do you still want to leave?" : "Você tem alterações não salvas! Você ainda quer sair?",
    "Calories" : "Calorias",
    "E.g.: 450 kcal (amount & unit)" : "Ex: 450 kcal (quantidade e unidade)",
    "Carbohydrate content" : "Quantidade de carboidratos",
    "E.g.: 2 g (amount & unit)" : "Ex: 2 g (quantidade e unidade)",
    "Cholesterol content" : "Quantidade de colesterol",
    "Fat content" : "Quantidade de gorduras",
    "Fiber content" : "Quantidade de fibras alimentares",
    "Protein content" : "Quantidade de proteínas",
    "Saturated-fat content" : "Quantidade de gorduras saturadas",
    "Serving size" : "Tamanho da porção",
    "Enter serving size (volume or mass)" : "Informe o tamanho da porção (volume ou massa)",
    "Sodium content" : "Quantidade de sódio",
    "Sugar content" : "Quantidade de açúcar",
    "Trans-fat content" : "Quantidade de gorduras trans",
    "Unsaturated-fat content" : "Quantidade de gorduras não-saturadas",
    "Failed to fetch keywords" : "Falha na busca de palavras-chave",
    "Unknown answer returned from server. See logs." : "Resposta desconhecida retornada pelo servidor. Veja os registros. ",
    "No answer for request was received." : "Nenhuma resposta para o pedido foi recebida. ",
    "Could not start request to save recipe." : "Não foi possível iniciar a solicitação para salvar a receita. ",
    "Clone of {name}" : "Clone de {name}",
    "Loading recipe failed" : "Erro ao carregar receita",
    "Recipe image" : "Imagem da receita",
    "Cooking time is up!" : "O tempo de cozimento acabou!",
    "Search recipes with this keyword" : "Procurar receitas com esta palavra-chave",
    "Date created" : "Data de criação",
    "Last modified" : "Última modificação",
    "Source" : "Fonte",
    "Preparation time (H:MM)" : "Tempo de preparo (H:MM)",
    "Cooking time (H:MM)" : "Tempo de cozimento (H:MM)",
    "Total time (H:MM)" : "Tempo total (H:MM)",
    "Copy ingredients" : "Copiar ingredientes",
    "Serving Size" : "Tamanho da Porção",
    "Energy" : "Energia",
    "Sugar" : "Açúcar",
    "Carbohydrate" : "Carboidratos",
    "Cholesterol" : "Colesterol",
    "Fiber" : "Fibra",
    "Protein" : "Proteína",
    "Sodium" : "Sódio",
    "Fat total" : "Total de gordura",
    "Saturated Fat" : "Gordura Saturada",
    "Unsaturated Fat" : "Gordura Insaturada",
    "Trans Fat" : "Gordura Trans",
    "Loading…" : "Carregando...",
    "The ingredient cannot be recalculated due to incorrect syntax. Please change it to this syntax: amount unit ingredient. Examples: 200 g carrots or 1 pinch of salt" : "O ingrediente não pode ser recalculado devido à sintaxe incorreta. Por favor, altere para esta sintaxe: quantidade unidade ingrediente. Exemplos: 200 g de cenoura ou 1 pitada de sal",
    "Failed to load recipes with keywords: {tags}" : "Falhou em carregar receitas com palavras-chave: {tags}",
    "Failed to load search results" : "Falha ao carregar os resultados da pesquisa",
    "Filter current recipes" : "Filtrar receitas atuais",
    "Search recipes" : "Pesquisar receitas",
    "Delete nutrition item" : "Excluir item nutricional",
    "Please select option first." : "Selecione a opção primeiro.",
    "Order" : "Pedido",
    "Search term" : "Termo de busca",
    "Clear" : "Limpar",
    "Apply" : "Aplicar",
    "Copy ingredients to the clipboard" : "Copie os ingredientes para a área de transferência"
},
"nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;");
