<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCP\AppFramework\Attribute {
	#[\Attribute(\Attribute::TARGET_METHOD)]
	class Expensive {
	}
}

namespace OCP {
	use OCP\AppFramework\Attribute\Expensive;

	interface ICache {
		#[Expensive]
		public function clear(string $prefix = ''): bool;

		public function remove(string $key): bool;
	}

	interface IMemcache extends ICache {
	}
}
