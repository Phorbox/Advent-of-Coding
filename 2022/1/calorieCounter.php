<?php
$input = "input.txt";
$elfFile = file($input) or die("Unable to open inventory!");

// make the expedition
$elf = [
    "total"     => 0,
    "inventory" => []
];
$elves = [];
foreach ($elfFile as $line) {
    if (!(is_numeric($line))) {
        foreach ($elf["inventory"] as $item) {
            $elf["total"] += $item;
        }
        $elves[] = $elf;
        $elf = [
            "total"     => 0,
            "inventory" => []
        ];
    } else {
        $elf["inventory"][] = intval($line);
    }
}

// make the a team
$aTeam =  [-1, -1, -1];
foreach ($elves as $member => $stuff) {
    foreach ($aTeam as $index => $val) {
        if ($val == -1) {
            $aTeam[$index] = $member;
            break;
        } else if ($stuff["total"] > $elves[$val]["total"]){ 
            $aTeam[$index] = $member;
            $member = $val;
        }
    }
}

$expedition = [
    "A Team" => ["total" => 0, "squad" => []],
    "B Team" => ["total" => 0, "squad" => []]
];

foreach ($aTeam as $index => $val) {
    $expedition["A Team"]["squad"][] = $elves[$val];
    $expedition["A Team"]["total"] += $elves[$val]["total"];
}
foreach ($aTeam as $index => $val) {
    unset($elves[$val]);
}
foreach ($elves as $key => $value) {
    $expedition["B Team"]["squad"][] = $value;
    $expedition["B Team"]["total"] += $value["total"];
}


$squadJson = json_encode($expedition, JSON_PRETTY_PRINT);
$output = "output.json";
$myfile = fopen($output, "w") or die("Unable to open file!");
fwrite($myfile, $squadJson);
fclose($myfile);
