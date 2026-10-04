<?php

namespace App\Exceptions\Accounting;

use LogicException;

/**
 * Thrown when code other than PostingService tries to write or delete journal rows.
 */
class JournalWriteNotAllowedException extends LogicException
{
    public function __construct()
    {
        parent::__construct('Journal entries and lines can only be written by PostingService.');
    }
}
