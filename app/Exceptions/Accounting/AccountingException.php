<?php

namespace App\Exceptions\Accounting;

use RuntimeException;

/**
 * Base class for business-rule violations in the ledger. Messages are user-facing (translated).
 */
abstract class AccountingException extends RuntimeException {}
