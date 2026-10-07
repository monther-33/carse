<?php

return [
    'new_receipt' => 'سند قبض',
    'new_payment' => 'سند صرف',
    'new_transfer' => 'تحويل بين خزائن',
    'purpose' => 'الغرض',
    'party' => 'الطرف',
    'party_or_account' => 'الطرف / الحساب',
    'from_cashbox' => 'من خزينة',
    'to_cashbox' => 'إلى خزينة',
    'for_invoice' => 'عن فاتورة',
    'for_invoice_hint' => 'يُسدَّد بسعر صرف الفاتورة، والفرق يذهب لحساب فروقات العملة.',

    'purposes' => [
        'customer' => 'تحصيل من عميل',
        'deposit' => 'عربون من عميل',
        'supplier_refund' => 'استرداد من مورّد',
        'partner' => 'قبض من شريك أو مالك سيارة',
        'supplier' => 'دفع لمورّد',
        'customer_refund' => 'رد مبلغ لعميل',
        'deposit_refund' => 'رد عربون',
        'commissions' => 'صرف عمولات',
        'owner' => 'صرف لمالك أو شريك سيارة',
        'other' => 'أخرى (اختيار حساب)',
    ],

    'errors' => [
        'amount' => 'المبلغ يجب أن يكون أكبر من صفر.',
        'same_cashbox' => 'خزينة التحويل يجب أن تختلف عن خزينة المصدر.',
        'transfer_currency' => 'التحويل بين خزينتين بعملتين مختلفتين غير مدعوم.',
        'account' => 'اختر حسابًا يقبل الترحيل.',
        'party_required' => 'هذا الحساب يتطلب تحديد الطرف.',
        'type' => 'نوع السند غير مدعوم هنا.',
        'cashbox_not_allowed' => 'لا تملك صلاحية استخدام هذه الخزينة.',
    ],
];
