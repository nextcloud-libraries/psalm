<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Nextcloud\Psalm\Checker;

use Nextcloud\Psalm\Issue\ExpensiveMethodCall;
use Psalm\CodeLocation;
use Psalm\Internal\MethodIdentifier;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterMethodCallAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterMethodCallAnalysisEvent;

/**
 * Complains about calls to methods marked with #[\OCP\AppFramework\Attribute\Expensive]
 *
 * Only the declaring method is checked, so implementations that do not repeat
 * the attribute (e.g. in-memory caches implementing ICache) are not reported.
 */
class ExpensiveMethodChecker implements AfterMethodCallAnalysisInterface {
	private const ATTRIBUTE = 'ocp\appframework\attribute\expensive';

	public static function afterMethodCallAnalysis(AfterMethodCallAnalysisEvent $event): void {
		$methodId = MethodIdentifier::wrap($event->getDeclaringMethodId());
		$storage = $event->getCodebase()->methods->getStorage($methodId);

		foreach ($storage->getAttributeStorages() as $attribute) {
			if (strtolower($attribute->fq_class_name) === self::ATTRIBUTE) {
				IssueBuffer::maybeAdd(
					new ExpensiveMethodCall(
						$storage->defining_fqcln . '::' . $storage->cased_name . '() is marked #[Expensive], avoid calling it on hot paths',
						new CodeLocation($event->getStatementsSource(), $event->getExpr()),
					),
					$event->getStatementsSource()->getSuppressedIssues(),
				);
				return;
			}
		}
	}
}
