<?php

namespace App\Services\Accounting;

use App\Exceptions\Accounting\JournalWriteNotAllowedException;
use Closure;

/**
 * Enforces the golden rule: only PostingService writes journal_entries / journal_lines.
 * The models call assertAllowed() on save; PostingService opens the gate around its writes.
 */
final class JournalWriteGuard
{
    private static int $depth = 0;

    /**
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    public static function allow(Closure $callback): mixed
    {
        self::$depth++;

        try {
            return $callback();
        } finally {
            self::$depth--;
        }
    }

    public static function assertAllowed(): void
    {
        if (self::$depth === 0) {
            throw new JournalWriteNotAllowedException;
        }
    }
}
