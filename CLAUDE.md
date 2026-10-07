# منظومة إدارة معرض سيارات — قواعد المشروع

نظام داخلي لمعرض سيارات (5–20 مستخدمًا، 50–300 سيارة). يُبنى على مراحل (المواصفة الكاملة لدى صاحب المشروع). **مرحلة واحدة في كل مرة**؛ في نهاية كل مرحلة: اختبارات ثم ملخص ثم انتظار الموافقة.

## البيئة والأوامر

- **Laravel 12 / PHP 8.2 / MariaDB 10.4 (XAMPP)** — قرار صاحب المشروع بدل Laravel 13 / PHP 8.3 / MySQL 8 الواردة في المواصفة. لا تستخدم ميزات PHP 8.3+ ولا ميزات MySQL 8 فقط (CTE متقدمة مسموحة في MariaDB 10.4، لكن لا JSON_TABLE ولا window functions خاصة بـ MySQL).
- Livewire 3 + Volt (صفحات Breeze للمصادقة فقط) + Alpine + Tailwind 3. واجهة عربية RTL، خط IBM Plex Sans Arabic مستضاف محليًا (`@fontsource`).
- قاعدة التطوير `cars`، والاختبارات `cars_test` (MySQL حقيقي وليس SQLite؛ `TestCase` يرفض العمل على غيرها).
- تشغيل MariaDB: `C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini --standalone`
- `composer test` (Pest) · `composer analyse` (Larastan مستوى 6) · `composer lint` (Pint) · `npm run build`
- المهام المجدولة: `php artisan schedule:list` (reservations:expire، alerts:daily، backup:*). التثبيت والتشغيل: `docs/DEPLOYMENT.md`.
- `php artisan migrate:fresh --seed` — كل Seeders متكررة الأمان (idempotent). المدير الأول: اسم المستخدم `admin` / قيمة `ADMIN_PASSWORD` (افتراضيًا `password`).

## قواعد صارمة

1. **لا تعدّل migration بعد اعتماد مرحلتها**؛ أي تغيير = migration جديدة. (المرحلة 1 لم تُعتمد بعد عند كتابة هذا.)
2. **commit بعد كل وحدة مكتملة** برسالة واضحة.
3. لا تخترع متطلبات؛ عند التعارض أو النقص اسأل.

## المحاسبة (القسم 5)

- **القاعدة الذهبية:** لا يكتب في `journal_entries` / `journal_lines` إلا `App\Services\Accounting\PostingService`. مفروضة وقت التشغيل: الموديلان يرميان `JournalWriteNotAllowedException` عند أي حفظ خارج `JournalWriteGuard::allow()`، والحذف ممنوع دائمًا، وسطور القيد لا تُعدَّل أبدًا. لا تنشئ Factory لهذين الموديلين.
- بناء القيد: `JournalBuilder::make($date, $desc)->source($doc)->debit(...)->credit(...)` ثم `PostingService::post()`. العملة الافتراضية الدينار بسعر 1؛ العملة الأجنبية بلا سعر صريح تأخذ سعر يوم القيد أو آخر سعر قبله من `ExchangeRateService`، وإن لم يوجد يُرمى `MissingExchangeRateException` (لا تخمين).
- `PostingService` يرفض: أقل من سطرين، مبلغ ≤ 0، سعر عملة أساسية ≠ 1، عدم توازن `debit_base`/`credit_base` (مقارنة دقيقة بثلاث خانات)، حساب تجميعي أو معطّل، تاريخ بلا فترة، فترة مقفلة. يأخذ قفلًا مشتركًا على صف الفترة، و`CloseFiscalPeriod` يأخذ قفلًا حصريًا.
- إذا لم تتوازن السطور الأجنبية بعد التحويل بسبب التقريب فمسؤولية المستدعي إضافة سطر فروقات عملة (حساب الدور `fx_differences`).
- **الإلغاء = قيد عكسي كامل** عبر `ReversalService::reverse()`: يعكس كل سطر بنفس العملة والسعر والأبعاد، تاريخه اليوم افتراضيًا، يربط `reversed_by_id` و`reverses_id`، ويجعل الأصلي `cancelled`. لا يُعكس قيد مرتين ولا يُعكس قيد عكسي.
- **المال:** `App\Support\Money` فقط (brick/math). لا `float` أبدًا: `Money` يرمي استثناء إذا وصله float. أعمدة المبالغ `DECIMAL(15,3)` وأسعار الصرف `DECIMAL(12,6)`، وتصل من Eloquent كنصوص (`decimal:N`). سعر الصرف = دينار لكل وحدة واحدة من العملة. التقريب HALF_UP.
- **الحسابات الافتراضية** تُقرأ من `settings` بمفاتيح `account.{AccountRole}` عبر `AccountResolver::for(AccountRole::X)`. رموز الحسابات لا تُكتب في الكود (الاستثناء الوحيد: `SettingsSeeder` و`ChartOfAccountsSeeder`). الحساب الأب للخزائن: `cashbox.parent.cash` / `cashbox.parent.bank`.
- العملاء والموردون بلا حسابات فرعية: حساب مراقبة واحد + `party_id` في سطر القيد. `journal_lines.party_id/vehicle_id` بلا مفاتيح أجنبية حتى المرحلة 2 (تُضاف بـ migration جديدة).
- **الترقيم:** `SequenceService::next(SequenceType, $date)` داخل نفس transaction المستند حصرًا (يرمي خارجه) → بلا فجوات ولا تكرار. ترقيم على مستوى الشركة لكل (نوع، سنة) بصيغة `PREFIX-YYYY-000001`. أضف كل نوع مستند جديد كـ case في `SequenceType`.
- **سلة المحذوفات** (طلب صاحب المشروع، شاشة `system.trash` للمبرمج فقط ضمن `developer_only`): كل حذف يقوم به مستخدم يمر بـ `RecycleBin::keep($root, fn)`، وكل موديل يُحذف داخله (عبر حدث `eloquent.deleting`) يُنسخ صفه إلى `trash_items` كدفعة واحدة، والاستعادة تعيد الصفوف بنفس المعرّفات (الآباء أولًا) أو `restore()` للحذف الناعم، وتُرفض عند التعارض (مثلًا شاصي أُضيف بعد الحذف). **احذف بموديلات لا باستعلام جماعي** (`->get()->each->delete()`) داخل `keep()` وإلا لن تُحفظ. الحذف خارج `keep()` (إعادة بناء سطور مسودة عند الحفظ، تنظيف النظام) لا يُحفظ. صلاحيات الدور تُحمَّل قبل حذفه، وملفات المرفقات تُنسخ إلى `storage/app/private/trash`.
- الأرصدة تُحسب من القيود ولا تُخزَّن. مفاتيح أجنبية `RESTRICT` على كل ما يخص المال. لا SoftDelete على المستندات المالية؛ SoftDelete للمراجع فقط (فروع، عملات، ماركات، موديلات، ألوان، مواقع).
- كل عملية مالية داخل `DB::transaction()` مع `lockForUpdate()` على الصفوف المتأثرة (السيارة، التسلسل).

## الصلاحيات (القسم 7)

- كتالوج الصلاحيات ومصفوفة الأدوار (`developer`, `admin`, `accountant`, `cashier`, `sales`, `purchasing`) في `config/permissions.php`، والتسميات في `lang/{ar,en}/permissions.php`. الـ Seeder يملأ صلاحيات الدور عند إنشائه فقط (لا يدهس تعديلات الواجهة).
- **دور المبرمج `developer`** (طلب صاحب المشروع) فوق المدير: يملك كل الصلاحيات دائمًا ومنها `system.locks` و`system.features` (قائمة `developer_only` لا تُعطى لأي دور آخر). شاشة `system.locks` يقفل منها أي صلاحية عن الجميع بمن فيهم المدير (`App\Support\PermissionLocks`، مخزنة في الإعداد `system.locked_permissions`). الفرض في `User::hasPermissionTo()` (كل `can`/`@can`/`can:` يمر منه؛ `Gate::before` لا يكفي لأن Spatie يسجّل before قبلنا). حسابات المبرمج ودوره مخفية عن غيره ولا يعدّلها/يعطّلها/يمنحها إلا المبرمج. المستخدم `developer` يُنشأ بكلمة `DEVELOPER_PASSWORD`.
- دور `admin` يملك كل شيء عدا `developer_only` وما يقفله المبرمج، ولا يُعدَّل من شاشة الأدوار.
- صلاحية جديدة: أضفها في `config/permissions.php` + تسميتها في اللغتين (اختبار `TranslationParityTest` يتحقق) + أعد تشغيل `RolesAndPermissionsSeeder`.
- الفرض في الخادم دائمًا: middleware على المسار (`can:` أو `can_any:`) **و** `$this->authorize()` في كل method من Livewire (الإجراءات طلبات مستقلة). إخفاء الزر ليس حماية.
- أمين الخزينة يرى خزائنه فقط: جدول `cashbox_user` + `Cashbox::scopeVisibleTo()` + `CashboxPolicy::view` (من يملك `cashboxes.view_all` يرى الكل). موظف المبيعات يرى فواتيره فقط (`sales.view` مقابل `sales.view_all`، يُطبَّق في المرحلة 3).
- إخفاء التكلفة (`vehicles.view_cost`) يُطبَّق في الشاشات والتقارير والبحث والتصدير.
- المستخدم يُعطَّل ولا يُحذف (`UserPolicy::delete` = false، ولا يعطّل نفسه). المستخدم المعطَّل يُمنع من الدخول ويُطرد من جلسته (`EnsureUserIsActive`).
- سجل التدقيق: trait `Auditable` (spatie activitylog) على كل موديل مهم + `LogAuthenticationEvents` للدخول والخروج والمحاولات الفاشلة (log name `auth`).

## المستندات (من المرحلة 2)

- دورة كل مستند مالي: `draft → posted → cancelled` عبر trait `IsDocument` و`ManagesDocumentLifecycle`. **الرقم يُعطى عند الاعتماد** (المسودة بلا رقم وتُحذف دون فجوات). المعتمد لا يُحذف أبدًا؛ الإلغاء = `ReversalService` + `markCancelled` مع سبب.
- إعداد `documents.require_approval`: مفعّل = الحفظ مسودة والاعتماد خطوة منفصلة بصلاحية `*.approve` (يجوز لنفس المستخدم إن ملك الصلاحية). معطّل = الحفظ يعتمد فورًا لمن يملك صلاحية الاعتماد.
- الشاشات تستدعي Actions عبر `HandlesBusinessErrors::attempt()`؛ الرفض التجاري يُرمى كـ `BusinessRuleException` برسالة مترجمة.
- **سياسات المستندات** ترث `Policies\Concerns\DocumentPolicy` (view/create/update/delete للمسودة، approve للمسودة، cancel للمعتمد).
- أمين الخزينة: قوائم السندات والمصروفات مقيدة بخزائنه (`Cashbox::visibleTo`) والتحقق في الخادم بـ `Rule::in` على الخزائن المرئية.
- `Relation::morphMap` للمستندات (`purchase_invoice`, `expense`, `voucher`, `return`, `vehicle`...). أضف كل مستند جديد إليها.

## السيارات والمشتريات والمصروفات (المرحلة 2)

- حالات إضافية غير مذكورة في المواصفة: `pending` (السيارة موجودة فقط على مسودة شراء) و`returned_to_supplier` (خرجت بمرتجع/إلغاء شراء). الانتقالات في `VehicleStatus::allowedTargets()`؛ اليدوي فقط عبر `manualTargets()` (قبل available، و`returned → available`). **لا تغيّر `vehicles.status` إلا عبر `VehicleStateMachine`.**
- تكلفة السيارة على حساب 15 (في الطريق/الجمارك) ما دامت `in_transit/in_customs`، وعند الخروج من الجمارك يُرحَّل قيد نقل تلقائي 15 ← 14 بكامل `total_cost`. الدور المحاسبي: `VehicleStatus::stockRole()`.
- رقم الشاصي فريد: نفس السيارة تعود للمخزون بنفس الصف (إعادة شراء بعد بيع أو مرتجع)؛ الأعمدة `purchase_cost/extra_cost/total_cost` تصف الدورة الحالية، و`vehicle_costs` سجل إلحاقي (صفوف سالبة عند الإلغاء).
- **فاتورة الشراء** (مثل البيع النقدي): القيد دائمًا مدين المخزون / دائن ذمم المورّد لكل سيارة (`net` بعملة الفاتورة، `cost_base` بالدينار، الخصم يوزَّع بـ `Money::allocate`)، والمدفوع عند الاعتماد = سند صرف تلقائي معتمد يُسدَّد بسعر الفاتورة.
- إلغاء فاتورة الشراء متاح فقط إن لم تتغير سياراتها (في المخزون، غير محجوزة، بلا مصاريف، بلا مرتجع، ونفس حساب المخزون)، ويُلغي معها سندات الدفع المرتبطة بها. غير ذلك = **مرتجع لسيارة** (`ReturnPurchaseItem`) يتطلب ألا تحمل مصاريف.
- المصروف على سيارة في المخزون يُرسمل على حسابها (14/15)، وعلى سيارة مباعة يُرحَّل لتكلفة المبيعات دون تغيير `cost_snapshot`. إلغاؤه ممكن فقط ما دامت التكلفة في نفس الحساب. المصروف الدوري: `recurs_every_months` و`next_due_date` مع زر "تكرار".
- **السندات**: العملة دائمًا عملة الخزينة. "الغرض" يحدد الحساب المقابل (`Vouchers\Index::PURPOSES`). على حسابات المراقبة بعملة أجنبية تُستخدم `SettlementLines`: الرصيد المفتوح يُسدَّد بسعر الفاتورة المرجعية إن وُجدت وإلا بمتوسط سعر الرصيد (`PartyBalanceService::carryingRate`)، والفائض بسعر السند، والفرق إلى حساب فروقات العملة. التحويل بين خزائن بعملتين مختلفتين غير مدعوم.
- التقارير Query objects في `app/Reports` (`TrialBalance`, `PartyStatement`).
- اختبار `TranslationKeysTest` يمسح الكود ويفشل لأي مفتاح ترجمة ناقص في ar أو en.

## المبيعات والحجز والتقسيط (المرحلة 3)

- **المسودة = عرض السعر** (تُطبع "عرض سعر"). لا جدول عروض أسعار منفصل.
- `SalesTerms` يحسب ويتحقق من المبالغ في الحفظ والاعتماد معًا: `total = subtotal − discount` (الإيراد)، `due = total − الاستبدال − العربون المعتمد`. نقدي/تحويل: المدفوع = المستحق؛ آجل/مختلط: المدفوع ≤ المستحق؛ تقسيط: المدفوع = المقدم والباقي يُجدول (كفيل إلزامي).
- قيد البيع الواحد: إيراد (مدين الذمم / دائن المبيعات بعملة الفاتورة) + تكلفة (`total_cost` يُجمَّد في `cost_snapshot`) + تحويل العربون + الاستبدال (مدين المخزون / دائن الذمم) + العمولة. المدفوعات عند البيع = سندات قبض معتمدة منفصلة مرجعها الفاتورة.
- منع البيع المزدوج: `lockForUpdate` على السيارات عند الاعتماد والتحقق أنها `available` أو محجوزة بحجز نفس الفاتورة.
- قيود الأسعار تُفحص عند حفظ المسودة على المستخدم الحافظ: الخصم بالدينار ≤ `users.max_discount` إلا بـ `sales.override_discount`، وصافي سعر كل سيارة ≥ `min_price` إلا بـ `sales.override_min_price`.
- العمولة لكل سيارة (`CommissionCalculator`: نسبة من صافي البيع بالدينار أو مبلغ ثابت) في جدول `commissions` وفي `sales_invoice_items.commission`. الربح = `net_base − cost_snapshot − commission` (`SalesInvoiceItem::profit()`). صرفها بـ `PayCommissions` (سند صرف واحد من خزينة بالدينار).
- **الحجز والعربون (مرن بطلب صاحب المشروع)**: السيارة تُحجز فورًا والعربون سند قبض على "عرابين العملاء" مرجعه الحجز (`DepositVouchers`: يُعتمد فورًا إلا إن كان الفصل مفعّلًا والمستخدم لا يملك `vouchers.approve`).
  - أثناء الحجز (`ManageReservation`, صلاحية `reservations.create`): إضافة عربون، تمديد، نقل الحجز وعربونه لسيارة أخرى.
  - عند الإلغاء أو في أي وقت بعده (`SettleDeposit`, صلاحية `reservations.cancel`): رد جزء/كل (سند صرف) و/أو مصادرة جزء/كل (قيد: مدين عرابين / دائن حساب الدور `forfeited_deposits`، افتراضيًا 42 إيرادات أخرى)، والباقي يبقى رصيدًا للعميل. الحد = أقل من (متبقي الحجز، رصيد عرابين العميل).
  - الانتهاء التلقائي (`reservations:expire` يوميًا) يتبع إعداد `reservations.expiry_action` = `credit` (يبقى رصيدًا) أو `forfeit` (يُصادر كاملًا).
  - الأرقام تُحسب ولا تُخزَّن (`DepositService`): المستلم = سندات القبض المعتمدة، المردود = سندات الصرف غير الملغاة، والمصادر في `reservations.forfeited_amount` فقط.
  - في البيع: `deposit_applied` يختاره المستخدم، أي جزء من **رصيد عرابين العميل المعتمد بعملة الفاتورة** مهما كان مصدره (الحجز الحالي أو حجز قديم ملغى). يُعاد التحقق عند الاعتماد، ويُنقل بسعر حمل الرصيد.
- **التقسيط بلا فوائد إطلاقًا (قاعدة ثابتة من صاحب المشروع — الربا محرّم):** لا نسبة ولا رسوم تأخير ولا أي زيادة على المبلغ المقسط؛ الأقساط تقسيم للمتبقي فقط. لا تضف أي إعداد أو حقل لفائدة. أقساط متساوية والأخير يمتص فرق التقريب. التحصيل = سند قبض **مرجعه `installment_plan`** يوزَّع بـ `InstallmentAllocator` على الأقدم أولًا (`installment_payments`)، ويُفك التوزيع عند إلغاء السند. المبلغ الزائد عن الأقساط مرفوض.
- **إلغاء البيع** (`sales.cancel`) فقط قبل أي تحصيل أقساط/مرتجع/صرف عمولة وما دامت سيارة الاستبدال لم تتغير: يعكس القيد وسندات القبض، السيارة `sold → returned → available`، سيارة الاستبدال `returned_to_supplier` (معادة للبائع)، والعربون يعود رصيدًا.
- **مرتجع البيع** (`sales.approve`) لسيارة: يعكس إيرادها وتكلفتها وعمولتها غير المصروفة، يلغي الأقساط المفتوحة للفاتورة، والسيارة `returned` ثم تُتاح يدويًا. ما للعميل يُرد بسند صرف.
- العملة: عربون الحجز ومدفوعات البيع والتحصيل بعملة الفاتورة. سند يرجع لفاتورة بيع/خطة أقساط يُسدَّد بسعر الفاتورة.
- موظف المبيعات يرى فواتيره فقط (`SalesInvoice::scopeVisibleTo` + `SalesInvoicePolicy::view`) وعمولاته فقط. خزينة الدفعات في شاشتي البيع والحجز تُختار من كل الخزائن النشطة بعملة الفاتورة (البائع يحدد أين ذهب المال، والترحيل عند الاعتماد).
- **الطباعة**: `PrintController` + `App\Support\Pdf` (mPDF، خط XB Riyaz، RTL) وقوالب `resources/views/print/*` بترويسة من الإعدادات. بنود عقد البيع نص حر في الإعدادات (`print.contract_terms`) ولا تُكتب بنود قانونية في الكود. التفقيط `App\Support\Tafqeet` (دينار/درهم 1000، دولار/سنت 100) مع اختبارات المطابقة النحوية.

## المحاسبة والتقارير (المرحلة 4)

- **القيود اليدوية**: مستند `ManualJournal` (morph `manual_journal`، تسلسل `MJ`) بدورة المستندات المعتادة وصلاحيات `journal.{view,create,approve,cancel}`. `SaveManualJournal` يتحقق: طرف واحد لكل سطر، مبلغ موجب، حساب ترحيلي، الطرف إلزامي على حسابات المراقبة، سطران على الأقل، توازن بالدينار. `PostManualJournal` يرحّل عبر `PostingService` ويلغي عبر `ReversalService`.
- **محرك التقارير**: كل تقرير صنف يرث `App\Reports\Report` (key, group, permissions, filters, columns, rows, notes, allows) ويُسجَّل في `ReportRegistry`. شاشة عامة واحدة `Livewire\Reports\Viewer` (الفلاتر في الرابط `?f[...]`) و`ReportController` للتصدير PDF/Excel، والجدول المشترك `resources/views/reports/table.blade.php`. صفوف التقرير قد تحمل `_style` (heading/subtotal/total) و`_indent`. تقرير جديد = صنف + سطر في السجل + ترجمات.
- صلاحيات التقارير: `reports.financial` (القوائم المالية والأستاذ والذمم)، `reports.sales`، `reports.inventory`. تقارير التكلفة والربح تتطلب `vehicles.view_cost` أيضًا (`allows()`)، وموظف المبيعات يرى مبيعاته فقط بلا تكلفة، وأمين الخزينة يرى حركة خزائنه فقط.
- **الميزانية**: صافي ربح الفترة غير المقفلة يظهر في حقوق الملكية كسطر "أرباح الفترة الحالية" (لا قيد إقفال سنوي). أعمار الديون FIFO (الدفعات تُطفئ الأقدم أولًا).
- **لوحة التحكم** `App\Livewire\Dashboard` + `DashboardMetrics`: كل عنصر يرجع null إن لم يملك المستخدم صلاحيته.
- **`DemoMonthSeeder`** (`php artisan db:seed --class=DemoMonthSeeder`): شهر كامل من النشاط (الشهر السابق) بكل أنواع المستندات؛ هو أساس اختبار بوابة المرحلة (`MonthGateTest`: الأصول = الخصوم + حقوق الملكية).

## الترحيل والتشغيل (المرحلة 5)

- **الاستيراد من Excel** (شاشة `imports.index`، صلاحية `imports.run` للمدير والمحاسب): `SheetReader` يقرأ الصف الأول عناوين ويطابقها بتسميات `imports.columns.*` بالعربية أو الإنجليزية أو بالمفتاح (أي ترتيب)، و`CellParser` يحوّل الخلايا (أرقام Excel العائمة → نصوص عشرية، أرقام عربية، تواريخ Excel أو نصية، قيم Enums بالتسمية). كل مستورد يرث `Actions\Imports\Importer`: `analyse()` بلا كتابة، و`import()` يعيد نفس الفحص ثم يكتب الكل في transaction أو لا شيء. القوالب من `Exports\ImportTemplate`.
  - الأطراف: الموجود (نفس الرقم الوطني، أو نفس الاسم والهاتف) يُتخطى → الاستيراد متكرر الأمان.
  - السيارات الحالية: مستند `OpeningStock` (morph `opening_stock`، تسلسل `OS`، سياسة بصلاحيات `journal.*`): مسودة بسيارات `pending` ثم الاعتماد: مدين حساب `stockRole()` / دائن حساب الدور `opening_balances` (34). الماركات والموديلات الناقصة تُنشأ. الإلغاء مثل إلغاء فاتورة الشراء (لم تتغير السيارة، وكل تغيّر حالة بعده يدوي).
  - الأرصدة الافتتاحية: مسودة `ManualJournal`، الفرق إلى 34. ممنوع فيها حسابا المخزون (14/15) وحساب العرابين (22، لأن رصيد العربون يُحسب من السندات).
  - بعد الاستيراد يُقفل 34 في رأس المال بقيد يدوي.
- **التنبيهات**: جدول `notifications` (database channel). `alerts:daily` الساعة 07:00 (`SendDailyAlerts` + `AlertDigest`): أقساط متأخرة ومستحقة خلال 7 أيام (`vouchers.view`/`sales.view_all`/`reports.financial`)، حجوزات تنتهي خلال 3 أيام (`reservations.view`)، سيارات راكدة فوق حدّي الإعدادات (`reports.inventory`/`vehicles.update`). الملخص غير المقروء يُستبدل ولا يتراكم. النص يُبنى عند العرض (`Notification::lines()`) بلغة القارئ. الجرس `Livewire\Notifications\Bell` في الشريط العلوي.
- **النسخ الاحتياطي** (spatie/laravel-backup): قاعدة البيانات + `storage/app/{public,private}` إلى قرص `backups` (`BACKUP_PATH`)، الجدولة: clean 01:30، run 02:00، monitor 08:00. `DB_DUMP_PATH` لمسار mysqldump (XAMPP: `C:/xampp/mysql/bin`). الفشل يصل لمن يملك `backups.manage` عبر `NotifyBackupProblems` (البريد فقط إن ضُبط `BACKUP_MAIL_TO`). شاشة `backups.index` للقائمة والتنزيل والنسخ الفوري، ومجلد نسخة إضافية يختاره المدير (`App\Support\BackupDestinations`، الإعداد `backup.extra_path`، يُقرأ من الجدول مباشرة) يُضاف كقرص `backups_extra` قبل بناء إعداد Spatie (`beforeResolving(Config::class)`) فيعمل من الجدولة والسطر والشاشة. ممنوع داخل `public`.
- المنطقة الزمنية من `APP_TIMEZONE` (`Africa/Tripoli`).
- **`DemoSeeder`** (`php artisan db:seed --class=DemoSeeder`، مرة واحدة على قاعدة فارغة): مستخدم لكل دور (`accountant`, `cashier`, `sales1`, `sales2`, `purchasing` بكلمة `ADMIN_PASSWORD`)، بدء تشغيل قبل شهرين عبر المستوردات نفسها، ثم شهر بدء التشغيل، ثم `DemoMonthSeeder`، ثم الشهر الحالي حتى اليوم. لا يُشغَّل على الإنتاج.
- دليل التثبيت والنشر والاستعادة: `docs/DEPLOYMENT.md`.

## سيارات الأمانة والشراكة (بعد المرحلة 5، بطلب صاحب المشروع)

- **الملكية** `VehicleOwnership` (دورة واحدة للسيارة في المعرض) + `VehicleOwnershipOwner` (طرف + حصة ٪ بأربع خانات + `contribution` للشريك) + `vehicles.ownership_id` (الملكية الحالية؛ null = سيارة المعرض). النوع `consignment` (أمانة، حصة المعرض 0) أو `partnership` (شراكة، حصة المعرض = 100 − حصص الشركاء > 0). الحالة active/sold/returned/closed. الملاك أطراف عادية.
- **الحسابات:** 24 "مستحقات ملاك وشركاء السيارات" (دور `owners_payable`، حساب مراقبة بطرف في كل سطر، بالدينار فقط) و43 "عمولات سيارات الأمانة" (دور `consignment_revenue`).
- **الاستلام أمانةً** (`ReceiveConsignment`، تسلسل `CI`، صلاحية `consignments.manage`): بلا قيد، تكلفة السيارة 0، إيصال مطبوع. الاتفاق لكل سيارة: `earning_mode` = net_price (للملاك مبلغ صافٍ والزيادة للمعرض) / percent / fixed / none (بلا عمولة، الثمن كله للملاك)، و`payout` = on_sale / on_collection. الإعادة للمالك (`ReturnToOwner`) لسيارة في المخزون غير محجوزة؛ ما رسمله المعرض عليها يُقفل في تكلفة المبيعات.
- **الشراكة في الشراء**: سطر الشراء فيه `partners` + `partner_payout`. عند الاعتماد لكل شريك: مدين 24 (الشريك) / دائن حساب المخزون بحصته من التكلفة، فتحمل السيارة حصة المعرض فقط. الإلغاء والمرتجع يعيدان الحصص ويغلقان الملكية.
- **البيع** (`OwnershipSales` + `OwnershipSplit`): إيراد السيارة بالدينار يُقسم: نصيب المعرض دائن 43 (أمانة) أو 41 (شراكة)، ونصيب كل مالك دائن 24 بطرفه، ويُحفظ في `vehicle_owner_dues` و`sales_invoice_items.showroom_revenue`. **العمولة تُختار في البيع** لكل سيارة أمانة (طلب صاحب المشروع): `sales_invoice_items.showroom_commission` = null حسب الاتفاق / 0 بدون عمولة (الثمن كله للملاك) / مبلغ آخر بالدينار (≤ صافي سعرها)، والاتفاق عند الاستلام قيمة افتراضية فقط. البيع تحت المتفق عليه **تنبيه فقط** (`NetPriceCheck`) والمعرض يتحمل الفرق (نصيب سالب = مدين 43). الإلغاء والمرتجع يعكسان النصيب ويرفضان إن صُرف للمالك أكثر مما سيبقى له. الربح في التقارير = `showroom_revenue` (أو `net_base` للسيارة الخاصة) − التكلفة − العمولة.
- **المصروف على سيارة لها ملاك**: `expenses.borne_by` = showroom (كسيارة المعرض) أو owners (أمانة: مدين 24 لكل مالك بحصته بلا رسملة؛ شراكة: حصص الشركاء على 24 وحصة المعرض تُرسمل). `vehicle_costs.amount` = المرسمل، و`owners_amount` = على الملاك.
- **السندات**: غرضا "صرف لمالك أو شريك" و"قبض من شريك أو مالك" على 24 بخزينة دينار. الصرف لا يتجاوز `OwnerPayouts::available()` = الرصيد − نصيب مبيعات on_collection غير المحصَّل (المحصَّل = الاستبدال + العربون + سندات القبض المعتمدة على الفاتورة/خطة الأقساط − المردود). **لا سلف للملاك.**
- موظف المبيعات يرى شارة "أمانة/شراكة" فقط؛ الملاك والاتفاق والمستحقات لمن يملك `vehicles.view_cost`. تقرير `owner_dues`، وكشف حساب المالك هو كشف الطرف العادي.
- ميزة قابلة للإيقاف `consignment` (لا تُوقف مع ملكيات نشطة أو أرصدة ملاك).

## هيكلة الكود

- `app/Actions/<Module>/` منطق العمليات · `app/Services/{Accounting,Numbering,Currency}` · `app/Livewire/` شاشات رفيعة تستدعي Actions ولا تحتوي منطقًا ماليًا · `app/Policies` · `app/Support/{Money,Settings,Navigation,Labels}` · `app/Enums`.
- `SettingsSeeder` يفرّغ كاش الإعدادات أولًا: قيم مخبأة قبل `migrate:fresh` في نفس العملية كانت تُخفي الإعدادات الناقصة.
- **الإضافة السريعة**: مودال واحد `App\Livewire\QuickCreate` في الـ layout (طرف، ماركة، موديل، لون، موقع، بند مصروف؛ الصلاحيات في `QuickCreate::TYPES`). زر `<x-ui.quick-add type="color" target="form.color_id" />` بجانب الحقل، والمكوّن المضيف يستخدم trait `AcceptsQuickCreate` ليُختار السجل الجديد في الحقل. قائمة "إضافة سريعة" في الشريط العلوي، وشاشات السندات والمصروفات والحجوزات تفتح نموذجها مع `?new=1`.
- **البحث عن سيارة**: trait `SearchesVehicles` + `pickers/partials/vehicle-search` (فلاتر ماركة/موديل/لون/سنة/حالة/سعر، وتفاصيل السيارة؛ التكلفة فقط مع `vehicles.view_cost`). يستخدمه `VehiclePicker` (زر "بحث" بجانب الحقل، والاختيار مقيد بـ `statuses`) و`VehicleFinder` في الشريط العلوي (يفتح بطاقة السيارة). لا تسمِّ بيانات العرض بأسماء خصائص عامة للمكوّن (`$search` مثلًا): الخاصية تطغى عليها.
- **بحث الأطراف**: `PartyPicker` فيه زر "بحث" ومودال (نص/نوع، تفاصيل مع رابط كشف الحساب)، والاختيار مقيد بنوع المنتقي والأطراف النشطة.
- **قوائم select قابلة للبحث تلقائيًا**: `resources/js/searchable-select.js` يفتح لوحة بحث عائمة لأي select فيه 8 خيارات فأكثر (أو `data-searchable="on"`، والإلغاء بـ `off`) دون تعديل DOM الـ select (Livewire يعيد رسمه)، ويختار بإطلاق input/change فيعمل `wire:model` كما هو. بحث عربي يوحّد الهمزات والتاء المربوطة والياء.
- **هوية المعرض**: `App\Support\Branding` (الاسم، الشعار `company.logo`، صورة المعرض `company.cover`) بروابط نسبية `/storage/...`. المكوّن `<x-brand-logo>` يعرض الشعار أو شارة بأول حرف من الاسم. صفحة الدخول (`layouts/guest`) بعمودين على الشاشات الكبيرة مع صورة المعرض، وبانر ترحيب في لوحة التحكم. لا شعار Laravel في أي مكان.
- **PWA**: `PwaController` (manifest ديناميكي من الإعدادات، أيقونات 192/512 any/maskable مولّدة بـ GD من الشعار، صفحة `/offline`) + `public/sw.js` + `partials/pwa-head` في الـ layoutين + `<x-pwa-install>`. عامل الخدمة يخبّئ `/build/*` و`/offline` فقط؛ الصفحات وLivewire وكل ما ليس GET إلى الشبكة دائمًا (لا بيانات مالية مخبّأة). غيّر `VERSION` في `sw.js` لتنظيف الكاش. التثبيت يحتاج HTTPS. `<x-pwa-hint>` يعرض تلميح التثبيت مرة واحدة لكل جهاز (خطوات المشاركة ← إضافة إلى الشاشة الرئيسية على iPhone/iPad، وزر تثبيت على Chrome/Edge)، ويُحفظ الإغلاق في localStorage بالمفتاح `pwa-install-hint-v1` (غيّره لإظهاره مجددًا للجميع).
- **ميزات قابلة للإيقاف** (`App\Support\Features`، شاشة `settings.features` للمبرمج فقط بصلاحية `system.features` ضمن `developer_only`): الحجوزات والعرابين، التقسيط، الاستبدال، العمولات، الاستيراد. الإعداد `features.{name}` (افتراضيًا مفعّل). الموقوفة تختفي من القائمة (`feature` في `Navigation`) والنماذج (`@feature`) ولوحة التحكم والتنبيهات والتقارير (`Report::feature()`)، والمسار يرجع 404 (middleware `feature:x`)، والخادم يرفضها (`Features::ensure()` في الحفظ، والعمولة صفر). لا تُوقف ميزة لها أعمال مفتوحة (`blocker()`: حجوزات نشطة أو أرصدة عرابين، أقساط غير مسددة، عمولات مستحقة). ميزة جديدة = ثابت في `Features` + تسميتها في `lang/*/features.php` + الحراسة في الأماكن السابقة.
- **القائمة الجانبية**: عنصر بـ `children` في `Navigation` يصبح قائمة منسدلة (عرض / جديد) تُفلتر بالصلاحية؛ روابط `?new=` تفتح النموذج مباشرة (السندات: `receipt|payment|transfer`).
- الـ select في RTL: سهم `@tailwindcss/forms` منقول لليسار في `resources/css/app.css`.
- شاشات الإعداد البسيطة ترث `App\Livewire\Concerns\CrudComponent`. رسائل النجاح عبر trait `Notifies`، ومكوّنات الواجهة في `resources/views/components/ui/*`.
- عنصر قائمة جانبية جديد: `App\Support\Navigation` مع صلاحيته.
- Enums بدل النصوص الحرة، مع `label()` من `lang/*/enums.php`.
- `Model::preventLazyLoading()` و`preventSilentlyDiscardingAttributes()` مفعّلان خارج الإنتاج: استخدم eager loading و`$fillable` صحيحًا.
- كل نص واجهة عبر `__()`؛ مفاتيح `lang/ar` و`lang/en` متطابقة. رسائل التحقق عربية مع أسماء الحقول في `validation.attributes`: يكفي اسم الحقل نفسه (`account_id`)، و`App\Validation\Validator` يستخدمه لأي مفتاح متداخل (`form.account_id`، `items.*.price`، `roles.0`). حقل جديد = سطر واحد هناك.
- Factory لكل Model (عدا قيود اليومية). الاختبارات Pest على MySQL؛ البذور الأساسية تُحمَّل مرة لكل تشغيل (`TestCase::$seed`). مساعدات الاختبار في `tests/Pest.php` (`account('14')`, `cashbox(...)`, `posting()`, `baseBalance()`, `ledgerIsBalanced()`).

## قرارات متخذة

- دليل الحسابات: 5 و7 حسابات تجميعية مع أبناء (51 تكلفة السيارات المباعة؛ 71 فروقات العملة، 72 الخصم المسموح به). 6 فيها 61 رواتب، 62 إيجار، 63 كهرباء، 64 دعاية، 65 عمولات المبيعات، 66 مصروفات متنوعة. 11 و12 تجميعيان؛ 1101/1102/1201 تُنشأ مع الخزائن.
- الفترات المالية شهرية؛ تُنشأ سنة كاملة دفعة واحدة. لا يوجد "إعادة فتح" فترة (غير مذكور في المواصفة).
- الخزينة الجديدة تُنشئ حسابها في الدليل تلقائيًا تحت الأب المحدد في الإعدادات؛ نوعها وعملتها ثابتان بعد الإنشاء.
- صلاحيات غير محددة في جدول المواصفة: `exchange_rates.manage` للمدير والمحاسب؛ `references.manage` للمدير والمشتريات؛ `parties.manage` للمدير والمحاسب والمبيعات والمشتريات؛ اعتماد السندات والمصروفات للمدير والمحاسب (أمين الخزينة ينشئ فقط).
- **الدخول باسم مستخدم لا ببريد** (قرار صاحب المشروع؛ migration `replace_email_with_username_on_users` حذف `email`): `users.username` فريد، حروف لاتينية وأرقام و`._-`، يُخزَّن بحروف صغيرة والدخول لا يفرّق بين الحالتين. لا استعادة كلمة مرور بالبريد: المدير يعيّنها من شاشة المستخدمين.
- المستخدم يغيّر اسمه وكلمة مروره فقط؛ اسم المستخدم يغيّره المدير. لا تسجيل ذاتي.
- **حد الائتمان تنبيه فقط ولا يمنع البيع** (قرار صاحب المشروع): `Services\Sales\CreditLimitCheck` في شاشة البيع وصفحة المسودة؛ الحد صفر = بلا حد.
