<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Nextcloud\Psalm;

use Nextcloud\Psalm\Checker\AppFrameworkTainter;
use Nextcloud\Psalm\Checker\AttributeNamedParameters;
use Nextcloud\Psalm\Checker\InArrayStrictChecker;
use Nextcloud\Psalm\Checker\LogicalOperatorChecker;
use Nextcloud\Psalm\Checker\StaticVarsChecker;
use Nextcloud\Psalm\Checker\UnstrictComparisonChecker;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

class Plugin implements PluginEntryPointInterface {
	private const CHECKERS = [
		AppFrameworkTainter::class,
		AttributeNamedParameters::class,
		InArrayStrictChecker::class,
		LogicalOperatorChecker::class,
		StaticVarsChecker::class,
		UnstrictComparisonChecker::class,
	];

	public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void {
		foreach (self::CHECKERS as $checker) {
			// Psalm only registers hooks of classes that are already loaded
			class_exists($checker);
			$registration->registerHooksFromClass($checker);
		}
	}
}
