<?php

return [
    'number' => 'الرقم',
    'entry' => 'القيد',
    'amount' => 'المبلغ',
    'rate' => 'سعر الصرف',
    'rate_hint' => 'دينار لكل وحدة. اتركه فارغًا لاستخدام سعر اليوم.',
    'cashbox' => 'الخزينة',
    'subtotal' => 'الإجمالي قبل الخصم',
    'discount' => 'الخصم',
    'total' => 'الإجمالي',
    'paid' => 'المدفوع',
    'created_by' => 'أنشأه',
    'approved_by' => 'اعتمده',
    'save_draft' => 'حفظ',
    'approve' => 'اعتماد',
    'confirm_approve' => 'اعتماد المستند؟ سيُرحَّل قيده ولن يمكن تعديله بعد ذلك.',
    'posted_ok' => 'تم الاعتماد والترحيل.',
    'cancel_document' => 'إلغاء',
    'cancel_reason' => 'السبب',
    'confirm_cancel' => 'تأكيد الإلغاء بقيد عكسي',
    'cancelled_ok' => 'تم الإلغاء بقيد عكسي.',
    'confirm_delete_draft' => 'حذف هذه المسودة نهائيًا؟',
    'cancellation_of' => 'إلغاء :number',
    'cancelled_info' => 'ملغى بواسطة :user بتاريخ :date — السبب: :reason',

    'errors' => [
        'wrong_status' => 'لا يمكن تنفيذ العملية: المستند في حالة ":status".',
        'not_draft' => 'لا يمكن تعديل مستند غير مسودة.',
        'not_posted' => 'المستند غير معتمد.',
        'cashbox_currency' => 'عملة الخزينة يجب أن تطابق عملة المستند.',
    ],
];
