<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/**
 * Runs Psalm with the plugin on tests/fixtures and compares the reported issues
 * with the "// expect: IssueType" markers in the fixtures.
 */

$expected = [];
foreach (glob(__DIR__ . '/fixtures/*.php') as $file) {
	foreach (file($file) as $index => $line) {
		if (preg_match('/\/\/ expect: (\w+)/', $line, $match)) {
			$expected[] = basename($file) . ':' . ($index + 1) . ' ' . $match[1];
		}
	}
}

$command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../vendor/bin/psalm')
	. ' --config=' . escapeshellarg(__DIR__ . '/psalm.xml')
	. ' --no-cache --no-progress --threads=1 --output-format=json';
exec($command, $output);

$actual = [];
foreach (json_decode(implode("\n", $output), true, flags: JSON_THROW_ON_ERROR) as $issue) {
	$actual[] = basename($issue['file_name']) . ':' . $issue['line_from'] . ' ' . $issue['type'];
}

sort($expected);
sort($actual);
$missing = array_diff($expected, $actual);
$unexpected = array_diff($actual, $expected);

foreach ($missing as $entry) {
	echo 'Missing:    ' . $entry . PHP_EOL;
}
foreach ($unexpected as $entry) {
	echo 'Unexpected: ' . $entry . PHP_EOL;
}

if ($missing !== [] || $unexpected !== []) {
	exit(1);
}
echo 'OK (' . count($expected) . ' expected issues reported)' . PHP_EOL;
