<?php

namespace App\Enums;

/**
 * Every numbered document type. Later phases add cases here (invoices, vouchers...).
 */
enum SequenceType: string
{
    case JournalEntry = 'journal_entry';

    public function defaultPrefix(): string
    {
        return match ($this) {
            self::JournalEntry => 'JE',
        };
    }
}
