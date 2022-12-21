<?php
function badUnifyRPS($abc)
{
    switch ($abc) {
        case 'A':
        case 'X':
            $returner = 1;
            break;
        case 'B':
        case 'Y':
            $returner = 2;
            break;
        case 'C':
        case 'Z':
            $returner = 3;
            break;
        default:
            $returner = 0;
            break;
    }
    // echo "$returner\r\n";
    return $returner;
}

function XYZToScore($abc)
{
    switch ($abc) {
        case 'X':
            $returner = 1;
            break;
        case 'Y':
            $returner = 2;
            break;
        case 'Z':
            $returner = 3;
            break;
        default:
            $returner = 0;
            break;
    }
    // echo "$returner\r\n";
    return $returner;
}

function RPStoEnglish($rps)
{
    switch ($rps) {
        case 1:
            $returner = "Rock";
            break;
        case 2:
            $returner = "Paper";
            break;
        case 3:
            $returner = "Scissors";
            break;
        default:
            $returner = "";
            break;
    }
    return $returner;
}

function scoreRound($theirs, $mine)
{
    if ($theirs == $mine) {
        return 3;
    }
    $adder = 0;
    switch ($mine) {
            //rock
        case 1:
            $multiplier = 6;
            break;
            // paper
        case 2:
            $multiplier = -3;
            break;
            // scissors
        case 3:
            $adder = 3;
            $multiplier = 6;
            break;
        default:
            $multiplier = 0;
            break;
    }
    return ($theirs - $mine - 1 + $adder) * $multiplier;
}

function getMine($theirs, $score)
{
    if ($score) {
        return $theirs;
    }
}

$cheatSheet = file("input.txt") or die("Unable to open the cheats!");
$tournament = ["Good Score" => 0,"Bad Score" => 0, "Rounds" => []];

foreach ($cheatSheet as $round) {
    $pattern = '{(?<theirs>[ABC]) (?<mine>[XYZ])}';
    preg_match($pattern, $round, $choice);
    // print_r($choice);
    $theirChoice = badUnifyRPS($choice["theirs"]);
    $badMyChoice = badUnifyRPS($choice["mine"]);
    $badRoundScore =  scoreRound($theirChoice, $badMyChoice);
    $badTotalScore = $badRoundScore + $badMyChoice;
    $tournament["Rounds"][] = [
        "Theirs" => RPStoEnglish($theirChoice),
        "Bad" => [
            "Mine" => RPStoEnglish($badMyChoice),
            "Choice Score" => $badMyChoice,
            "Round Score" => $badRoundScore,
            "Total Score" => $badTotalScore
        ]
    ];
    $tournament["Bad Score"] += $badTotalScore;

    $tournamentJson = json_encode($tournament, JSON_PRETTY_PRINT);
    $output = "output.json";
    $myfile = fopen($output, "w") or die("Unable to open file!");
    fwrite($myfile, $tournamentJson);
    fclose($myfile);
}
