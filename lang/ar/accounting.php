<?php

return [
    'reversal_of' => 'قيد عكسي للقيد :number',

    'errors' => [
        'unbalanced' => 'القيد غير متوازن: المدين :debit ≠ الدائن :credit.',
        'closed_period' => 'الفترة المالية :period مقفلة ولا تقبل الترحيل.',
        'no_period' => 'لا توجد فترة مالية تشمل التاريخ :date.',
        'not_postable' => 'الحساب :account تجميعي أو معطّل ولا يقبل الترحيل.',
        'invalid_line' => 'سطر قيد غير صالح: :reason',
        'already_reversed' => 'القيد :number ملغى مسبقًا.',
        'missing_mapping' => 'لم يُحدَّد حساب ":role" في الإعدادات.',
        'missing_rate' => 'لا يوجد سعر صرف للعملة :currency في تاريخ :date أو قبله.',
        'min_lines' => 'القيد يحتاج سطرين على الأقل.',
        'non_positive_amount' => 'مبلغ السطر يجب أن يكون أكبر من صفر.',
        'base_rate_must_be_one' => 'سعر صرف العملة الأساسية يجب أن يكون 1.',
        'non_positive_rate' => 'سعر الصرف يجب أن يكون أكبر من صفر.',
        'cannot_reverse_reversal' => 'لا يمكن عكس قيد عكسي.',
    ],

    'validation' => [
        'parent_required' => 'يجب اختيار الحساب الأب.',
        'parent_must_be_group' => 'الحساب الأب يجب أن يكون حسابًا تجميعيًا.',
        'code_prefix' => 'رمز الحساب يجب أن يبدأ برمز الحساب الأب (:prefix) ويكون أطول منه.',
        'system_account_locked' => 'لا يمكن نقل حساب نظام إلى أب آخر.',
        'has_postings' => 'لا يمكن تحويل حساب عليه قيود إلى تجميعي.',
        'has_children' => 'لا يمكن تحويل حساب له حسابات فرعية إلى حساب حركة.',
        'code_locked' => 'لا يمكن تغيير رمز حساب نظام أو حساب له فروع.',
        'account_in_use' => 'الحساب مستخدم (قيود أو فروع أو خزينة أو إعدادات)؛ عطّله بدل حذفه.',
        'cashbox_parent_missing' => 'لم يُحدَّد الحساب الأب لهذا النوع من الخزائن في الإعدادات.',
        'period_already_closed' => 'الفترة مقفلة مسبقًا.',
    ],
];
