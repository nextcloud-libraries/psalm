<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Fixtures;

use OCP\ICache;
use OCP\IMemcache;

final class InMemoryCache implements ICache {
	#[\Override]
	public function clear(string $prefix = ''): bool {
		return $prefix !== '';
	}

	#[\Override]
	public function remove(string $key): bool {
		return $key !== '';
	}
}

final class ExpensiveMethod {
	public function viaInterface(ICache $cache): void {
		$cache->clear('prefix'); // expect: ExpensiveMethodCall
		$cache->remove('key');
	}

	public function viaChildInterface(IMemcache $cache): void {
		$cache->clear(); // expect: ExpensiveMethodCall
	}

	public function viaNullsafe(?ICache $cache): void {
		$cache?->clear(); // expect: ExpensiveMethodCall
	}

	public function suppressed(ICache $cache): void {
		/** @psalm-suppress ExpensiveMethodCall */
		$cache->clear();
	}

	public function inMemory(InMemoryCache $cache): void {
		$cache->clear();
	}
}
