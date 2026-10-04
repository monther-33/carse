<?php

namespace App\Enums;

/**
 * Every numbered document type. Numbers are assigned when a document is posted,
 * so deleted drafts never leave gaps.
 */
enum SequenceType: string
{
    case JournalEntry = 'journal_entry';
    case PurchaseInvoice = 'purchase_invoice';
    case PurchaseReturn = 'purchase_return';
    case Expense = 'expense';
    case ReceiptVoucher = 'receipt_voucher';
    case PaymentVoucher = 'payment_voucher';
    case TransferVoucher = 'transfer_voucher';
    case Reservation = 'reservation';
    case SalesInvoice = 'sales_invoice';
    case SalesReturn = 'sales_return';

    public function defaultPrefix(): string
    {
        return match ($this) {
            self::JournalEntry => 'JE',
            self::PurchaseInvoice => 'PI',
            self::PurchaseReturn => 'PR',
            self::Expense => 'EX',
            self::ReceiptVoucher => 'RV',
            self::PaymentVoucher => 'PV',
            self::TransferVoucher => 'TV',
            self::Reservation => 'RS',
            self::SalesInvoice => 'SI',
            self::SalesReturn => 'SR',
        };
    }

    public static function forVoucher(VoucherType $type): self
    {
        return match ($type) {
            VoucherType::Receipt => self::ReceiptVoucher,
            VoucherType::Payment => self::PaymentVoucher,
            VoucherType::Transfer => self::TransferVoucher,
            VoucherType::Journal => self::JournalEntry,
        };
    }
}
