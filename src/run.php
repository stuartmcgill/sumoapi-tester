#!/bin/env php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiTester;

require __DIR__ . '/../vendor/autoload.php';

use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;
use StuartMcGill\SumoApiPhp\Service\BashoService;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

$rikishiService = RikishiService::factory();
$bashoService = BashoService::factory();

$rikishi = $rikishiService->fetch(1);
echo $rikishi->shikonaJp . "\n";

$rikishis = $rikishiService->fetchAll();
$totalMass = array_reduce(
    array: $rikishis,
    callback: static fn (float $total, Rikishi $rikishi) => $total + $rikishi->weight,
    initial:0,
);
echo "The total mass of all the wrestlers is $totalMass kg\n";

$matches = $rikishiService->fetchMatches(1);
$oshidashiWins = array_filter(
    array: $matches,
    callback: static fn (RikishiMatch $match) =>
            $match->winnerId === 1 && $match->kimarite === 'oshidashi',
);
echo 'Takakeisho has won by Oshidashi ' . count($oshidashiWins) . " times\n";

$someRikishi = $rikishiService->fetchSome([1, 2]);
echo 'Fetched details for ' . count($someRikishi) . " wrestlers\n";

$someRikishi = $rikishiService->fetchDivision('Makuuchi');
echo 'Fetched details for ' . count($someRikishi) . " Makuuchi wrestlers\n";

$rikishisFromThePast = $bashoService->fetchRikishiIdsByBasho(2019, 3, 'Makuuchi');
echo 'Rikishi IDs from March 2019 are ' . implode(',', $rikishisFromThePast) . "\n";

$matchupSummary = $rikishiService->fetchMatchups(1, [2]);
echo 'Takakeisho has fought Asanoyama ' . $matchupSummary->matchups[0]->total() . ' times';
