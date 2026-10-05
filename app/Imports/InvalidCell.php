<?php

namespace App\Imports;

use RuntimeException;

/**
 * A cell that cannot be read; the message is already translated and names the column.
 */
class InvalidCell extends RuntimeException {}
