#!/bin/env php
<?php

declare(strict_types=1);

namespace StuartMcGill\SumoApiTester;

require __DIR__ . '/../vendor/autoload.php';

use StuartMcGill\SumoApiPhp\Model\Rikishi;
use StuartMcGill\SumoApiPhp\Model\RikishiMatch;
use StuartMcGill\SumoApiPhp\Service\BashoService;
use StuartMcGill\SumoApiPhp\Service\RikishiService;

$bashoService = BashoService::factory();
$rikishiService = RikishiService::factory();

// Fetch rikishis from a particular basho
$rikishisFromThePast = $bashoService->fetchRikishiIdsByBasho(2019, 3, 'Makuuchi');
echo 'Rikishi IDs from March 2019 are ' . implode(',', $rikishisFromThePast) . "\n\n";

// Fetch a single rikishi
$rikishi = $rikishiService->fetch(1);
echo $rikishi->shikonaJp . "\n";

// Fetch all rikishis
$rikishis = $rikishiService->fetchAll();
$totalMass = array_reduce(
    array: $rikishis,
    callback: static fn (float $total, Rikishi $rikishi) => $total + $rikishi->weight,
    initial:0,
);
echo "The total mass of all the wrestlers is $totalMass kg\n";

// Fetch all of a rikishi's matches
$matches = $rikishiService->fetchMatches(1);
$oshidashiWins = array_filter(
    array: $matches,
    callback: static fn (RikishiMatch $match) =>
            $match->winnerId === 1 && $match->kimarite === 'oshidashi',
);
echo 'Takakeisho has won by Oshidashi ' . count($oshidashiWins) . " times\n";

// Fetch some rikishi (by IDs)
$someRikishi = $rikishiService->fetchSome([1, 2]);
echo 'Fetched details for ' . count($someRikishi) . " wrestlers\n";

// Fetch rikishi and filter by division
$someRikishi = $rikishiService->fetchDivision('Makuuchi');
echo 'Fetched details for ' . count($someRikishi) . " Makuuchi wrestlers\n";

// Fetch rikishi matchups (head-to-heads)
$matchupSummary = $rikishiService->fetchMatchups(1, [2]);
echo 'Takakeisho has fought Asanoyama ' . $matchupSummary->matchups[0]->total() . ' times';
