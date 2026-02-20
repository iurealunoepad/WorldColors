<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="pt_PT" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>WorldColors Quiz</title>
</head>
<body>

<?php

$levels = [
    1 => "iniciante",
    2 => "normal",
    3 => "normal",
    4 => "dificil",
    5 => "dificil"
];

$paises = ["Portugal", "Venezuela", "Sudão do Sul", "Bangladesh"];

// Perguntas (2 por país/dificuldade)
$questions = [
    // INICIANTE
    ["id"=>1,"country"=>"Portugal","difficulty"=>"iniciante","question"=>"Qual é a capital de Portugal?"],
    ["id"=>2,"country"=>"Portugal","difficulty"=>"iniciante","question"=>"Qual é a moeda de Portugal?"],

    ["id"=>3,"country"=>"Venezuela","difficulty"=>"iniciante","question"=>"Qual é a capital da Venezuela?"],
    ["id"=>4,"country"=>"Venezuela","difficulty"=>"iniciante","question"=>"Qual é a moeda da Venezuela?"],

    ["id"=>5,"country"=>"Sudão do Sul","difficulty"=>"iniciante","question"=>"Qual é a capital do Sudão do Sul?"],
    ["id"=>6,"country"=>"Sudão do Sul","difficulty"=>"iniciante","question"=>"Em que continente fica o Sudão do Sul?"],

    ["id"=>7,"country"=>"Bangladesh","difficulty"=>"iniciante","question"=>"Qual é a capital do Bangladesh?"],
    ["id"=>8,"country"=>"Bangladesh","difficulty"=>"iniciante","question"=>"Qual é o principal rio do Bangladesh?"],

    // NORMAL
    ["id"=>9,"country"=>"Portugal","difficulty"=>"normal","question"=>"Portugal pertence à União Europeia?"],
    ["id"=>10,"country"=>"Portugal","difficulty"=>"normal","question"=>"Qual é o maior rio de Portugal?"],

    ["id"=>11,"country"=>"Venezuela","difficulty"=>"normal","question"=>"Quem foi Simón Bolívar?"],
    ["id"=>12,"country"=>"Venezuela","difficulty"=>"normal","question"=>"Qual é o principal recurso natural da Venezuela?"],

    ["id"=>13,"country"=>"Sudão do Sul","difficulty"=>"normal","question"=>"Em que ano se tornou independente?"],
    ["id"=>14,"country"=>"Sudão do Sul","difficulty"=>"normal","question"=>"Qual é a principal atividade económica?"],

    ["id"=>15,"country"=>"Bangladesh","difficulty"=>"normal","question"=>"Qual é o principal rio do Bangladesh?"],
    ["id"=>16,"country"=>"Bangladesh","difficulty"=>"normal","question"=>"Qual é a principal atividade económica do Bangladesh?"],

    // DIFÍCIL
    ["id"=>17,"country"=>"Portugal","difficulty"=>"dificil","question"=>"Em que ano foi restaurada a independência de Portugal?"],
    ["id"=>18,"country"=>"Portugal","difficulty"=>"dificil","question"=>"Quem foi o primeiro rei de Portugal?"],

    ["id"=>19,"country"=>"Venezuela","difficulty"=>"dificil","question"=>"Em que ano começou a crise económica venezuelana?"],
    ["id"=>20,"country"=>"Venezuela","difficulty"=>"dificil","question"=>"Qual é o nome da moeda atual da Venezuela?"],

    ["id"=>21,"country"=>"Sudão do Sul","difficulty"=>"dificil","question"=>"Que conflito levou à sua independência?"],
    ["id"=>22,"country"=>"Sudão do Sul","difficulty"=>"dificil","question"=>"Qual é a principal etnia do país?"],

    ["id"=>23,"country"=>"Bangladesh","difficulty"=>"dificil","question"=>"Em que ano ocorreu a independência do Bangladesh?"],
    ["id"=>24,"country"=>"Bangladesh","difficulty"=>"dificil","question"=>"Que desastre natural afeta frequentemente o país?"]
];

/* ==========================================================
   FUNÇÕES DO QUIZ
========================================================== */

function startQuiz() {
    $_SESSION["level"] = 1;
    $_SESSION["questionNumber"] = 1;
    $_SESSION["usedCountries"] = [];
    $_SESSION["currentCountry"] = null;
    $_SESSION["questionsLevel"] = [];
}

function getRandomCountry($paises) {
    $available = array_diff($paises, $_SESSION["usedCountries"]);
    if (empty($available)) return null;
    $available = array_values($available);
    $country = $available[array_rand($available)];
    $_SESSION["usedCountries"][] = $country;
    return $country;
}

function getTwoQuestions($questions, $difficulty, $country) {
    $filtered = array_filter($questions, function($q) use ($difficulty, $country) {
        return $q["difficulty"] === $difficulty && $q["country"] === $country;
    });
    if (count($filtered) < 2) return null;
    $filtered = array_values($filtered);
    shuffle($filtered);
    return array_slice($filtered, 0, 2);
}

function nextStep() {
    if ($_SESSION["questionNumber"] == 2) {
        $_SESSION["level"]++;
        $_SESSION["questionNumber"] = 1;
        $_SESSION["currentCountry"] = null;
        $_SESSION["questionsLevel"] = [];
    } else {
        $_SESSION["questionNumber"]++;
    }
}

/* ==========================================================
   INICIALIZAÇÃO
========================================================== */

if (!isset($_SESSION["level"])) {
    startQuiz();
}

/* ==========================================================
   EXECUÇÃO PRINCIPAL
========================================================== */

if ($_SESSION["level"] <= 5) {

    $level = $_SESSION["level"];
    $difficulty = $levels[$level];

    if ($_SESSION["currentCountry"] == null) {
        $country = getRandomCountry($paises);
        $_SESSION["currentCountry"] = $country;

        $twoQuestions = getTwoQuestions($questions, $difficulty, $country);
        if (!$twoQuestions) {
            echo "<p>Erro: perguntas insuficientes para este país/dificuldade.</p>";
            exit;
        }
        $_SESSION["questionsLevel"] = $twoQuestions;
    }

    $country = $_SESSION["currentCountry"];
    $index = $_SESSION["questionNumber"] - 1;
    $question = $_SESSION["questionsLevel"][$index];

    echo "<h2>Nível $level - " . ucfirst($difficulty) . "</h2>";
    echo "<p><strong>País:</strong> $country</p>";
    echo "<p><strong>Pergunta " . $_SESSION["questionNumber"] . ":</strong> " . $question['question'] . "</p>";

    nextStep();

} else {

    echo "<h2>FIM DO QUIZ 🎉</h2>";

    // Destrói sessão ao terminar
    session_destroy();
}

?>

</body>
</html>
