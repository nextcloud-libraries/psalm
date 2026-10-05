<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Fixtures;

final class CodeStyle {
	public static int $counter = 0; // expect: ImpureStaticProperty

	public function check(int $a, int $b, \DateTimeImmutable $c, \DateTimeImmutable $d): bool {
		static $cache = null; // expect: ImpureStaticVariable
		$cache = $a;

		$values = [$a, $b];
		$found = in_array($cache, $values); // expect: UnrecognizedExpression
		$strict = in_array($cache, $values, true);
		$named = in_array($cache, $values, strict: true);

		$loose = $a == $b; // expect: UnrecognizedExpression
		$sameDate = $c == $d;

		return ($found and $strict) || $named || $loose || $sameDate; // expect: UnrecognizedExpression
	}
}
