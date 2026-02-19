<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="pt_PT" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WorldColors</title>
</head>

<body>

<?php

$sql_pais = "SELECT * FROM categorias";
$stmt_pais = $conexao->prepare($sql_pais);

if (!$stmt_pais) {
    die("Erro ao preparar a consulta da base de dados.");
}

$stmt_pais->execute();
$pais = $stmt_pais->get_result();

if (!$pais) { die("") }

$levels = [
    1 => "facil",
    2 => "medio",
    3 => "medio",
    4 => "dificil",
    5 => "dificil"
];

$questions = [

    // ------------------- FÁCIL -------------------
    ["id"=>1,"country"=>"Portugal","difficulty"=>"facil","question"=>"Qual é a capital de Portugal?"],
    ["id"=>2,"country"=>"Irão","difficulty"=>"facil","question"=>"Qual é a capital do Irão?"],

    // ------------------- MÉDIO -------------------
    ["id"=>3,"country"=>"Brasil","difficulty"=>"medio","question"=>"Qual é a capital do Brasil?"],
    ["id"=>4,"country"=>"Japão","difficulty"=>"medio","question"=>"Qual é a capital do Japão?"],
    ["id"=>5,"country"=>"Canadá","difficulty"=>"medio","question"=>"Qual é a capital do Canadá?"],
    ["id"=>6,"country"=>"Portugal","difficulty"=>"medio","question"=>"Portugal pertence a que continente?"],

    // ------------------- DIFÍCIL -------------------
    ["id"=>7,"country"=>"Irão","difficulty"=>"dificil","question"=>"Em que ano ocorreu a Revolução Iraniana?"],
    ["id"=>8,"country"=>"Brasil","difficulty"=>"dificil","question"=>"Em que ano foi proclamada a República no Brasil?"],
    ["id"=>9,"country"=>"Japão","difficulty"=>"dificil","question"=>"Qual era o nome do período de isolamento japonês?"],
    ["id"=>10,"country"=>"Canadá","difficulty"=>"dificil","question"=>"Qual província canadense tem maioria francófona?"]
];


/* ==========================================================
   FUNÇÃO: INICIAR QUIZ
   Define valores iniciais do jogo
========================================================== */
function startQuiz() {
    $_SESSION["currentLevel"] = 1;       // começa no nível 1
    $_SESSION["questionInLevel"] = 1;    // primeira pergunta do nível
    $_SESSION["usedQuestions"] = [];     // array vazio para guardar perguntas já usadas
}


/* ==========================================================
   Se for a primeira vez que o utilizador entra,
   inicializa o jogo
========================================================== */
if (!isset($_SESSION["currentLevel"])) {
    startQuiz();
}


/* ==========================================================
   FUNÇÃO: BUSCAR PERGUNTA RANDOM SEM REPETIR
   - Filtra por dificuldade
   - Remove perguntas já usadas
   - Escolhe uma aleatória
========================================================== */
function getRandomQuestion($questions, $difficulty) {

    $used = $_SESSION["usedQuestions"];

    // Filtrar perguntas pela dificuldade atual
    $filtered = array_filter($questions, function($q) use ($difficulty, $used) {
        return $q["difficulty"] === $difficulty
               && !in_array($q["id"], $used);
    });

    if (empty($filtered)) {
        return null;
    }

    // Reorganiza o array
    $filtered = array_values($filtered);

    // Escolhe pergunta aleatória
    $randomQuestion = $filtered[array_rand($filtered)];

    // Guarda como usada para não repetir
    $_SESSION["usedQuestions"][] = $randomQuestion["id"];

    return $randomQuestion;
}


/* ==========================================================
   FUNÇÃO: AVANÇAR NÍVEL
   - Cada nível tem 2 perguntas
   - Após 2 perguntas, sobe o nível
========================================================== */
function nextStep() {

    if ($_SESSION["questionInLevel"] == 2) {

        $_SESSION["currentLevel"]++;
        $_SESSION["questionInLevel"] = 1;

    } else {

        $_SESSION["questionInLevel"]++;
    }

    if ($_SESSION["currentLevel"] > 5) {
        return "fim";
    }

    return "continuar";
}


/* ==========================================================
   EXECUÇÃO PRINCIPAL DO QUIZ
========================================================== */

if ($_SESSION["currentLevel"] <= 5) {

    global $levels;

    // Obtém a dificuldade do nível atual
    $difficulty = $levels[$_SESSION["currentLevel"]];

    // Busca pergunta random dessa dificuldade
    $question = getRandomQuestion($questions, $difficulty);

    if ($question) {

        echo "<pre>";
        echo "Nível Atual: " . $_SESSION["currentLevel"] . "\n";
        echo "Pergunta nº no nível: " . $_SESSION["questionInLevel"] . "\n";
        echo "Dificuldade: " . $difficulty . "\n";
        echo "País (Random): " . $question["country"] . "\n";
        echo "Pergunta: " . $question["question"] . "\n";
        echo "</pre>";

        // Avança para próxima pergunta ou nível
        nextStep();

    } else {
        echo "Não existem perguntas disponíveis.";
    }

} else {

    echo "<h2>FIM DO QUIZ</h2>";

    // Destroi sessão ao terminar
    session_destroy();
}
?>

</body>
</html>
