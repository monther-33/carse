# منظومة إدارة معرض سيارات — قواعد المشروع

نظام داخلي لمعرض سيارات (5–20 مستخدمًا، 50–300 سيارة). يُبنى على مراحل (المواصفة الكاملة لدى صاحب المشروع). **مرحلة واحدة في كل مرة**؛ في نهاية كل مرحلة: اختبارات ثم ملخص ثم انتظار الموافقة.

## البيئة والأوامر

- **Laravel 12 / PHP 8.2 / MariaDB 10.4 (XAMPP)** — قرار صاحب المشروع بدل Laravel 13 / PHP 8.3 / MySQL 8 الواردة في المواصفة. لا تستخدم ميزات PHP 8.3+ ولا ميزات MySQL 8 فقط (CTE متقدمة مسموحة في MariaDB 10.4، لكن لا JSON_TABLE ولا window functions خاصة بـ MySQL).
- Livewire 3 + Volt (صفحات Breeze للمصادقة فقط) + Alpine + Tailwind 3. واجهة عربية RTL، خط IBM Plex Sans Arabic مستضاف محليًا (`@fontsource`).
- قاعدة التطوير `cars`، والاختبارات `cars_test` (MySQL حقيقي وليس SQLite؛ `TestCase` يرفض العمل على غيرها).
- تشغيل MariaDB: `C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini --standalone`
- `composer test` (Pest) · `composer analyse` (Larastan مستوى 6) · `composer lint` (Pint) · `npm run build`
- `php artisan migrate:fresh --seed` — كل Seeders متكررة الأمان (idempotent). المدير الأول: `admin@cars.local` / قيمة `ADMIN_PASSWORD` (افتراضيًا `password`).

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
- الأرصدة تُحسب من القيود ولا تُخزَّن. مفاتيح أجنبية `RESTRICT` على كل ما يخص المال. لا SoftDelete على المستندات المالية؛ SoftDelete للمراجع فقط (فروع، عملات، ماركات، موديلات، ألوان، مواقع).
- كل عملية مالية داخل `DB::transaction()` مع `lockForUpdate()` على الصفوف المتأثرة (السيارة، التسلسل).

## الصلاحيات (القسم 7)

- كتالوج الصلاحيات ومصفوفة الأدوار الخمسة (`admin`, `accountant`, `cashier`, `sales`, `purchasing`) في `config/permissions.php`، والتسميات في `lang/{ar,en}/permissions.php`. الـ Seeder يملأ صلاحيات الدور عند إنشائه فقط (لا يدهس تعديلات الواجهة)، ودور `admin` يملك كل شيء دائمًا ولا يُعدَّل من الواجهة.
- صلاحية جديدة: أضفها في `config/permissions.php` + تسميتها في اللغتين (اختبار `TranslationParityTest` يتحقق) + أعد تشغيل `RolesAndPermissionsSeeder`.
- الفرض في الخادم دائمًا: middleware على المسار (`can:` أو `can_any:`) **و** `$this->authorize()` في كل method من Livewire (الإجراءات طلبات مستقلة). إخفاء الزر ليس حماية.
- أمين الخزينة يرى خزائنه فقط: جدول `cashbox_user` + `Cashbox::scopeVisibleTo()` + `CashboxPolicy::view` (من يملك `cashboxes.view_all` يرى الكل). موظف المبيعات يرى فواتيره فقط (`sales.view` مقابل `sales.view_all`، يُطبَّق في المرحلة 3).
- إخفاء التكلفة (`vehicles.view_cost`) يُطبَّق في الشاشات والتقارير والبحث والتصدير.
- المستخدم يُعطَّل ولا يُحذف (`UserPolicy::delete` = false، ولا يعطّل نفسه). المستخدم المعطَّل يُمنع من الدخول ويُطرد من جلسته (`EnsureUserIsActive`).
- سجل التدقيق: trait `Auditable` (spatie activitylog) على كل موديل مهم + `LogAuthenticationEvents` للدخول والخروج والمحاولات الفاشلة (log name `auth`).

## هيكلة الكود

- `app/Actions/<Module>/` منطق العمليات · `app/Services/{Accounting,Numbering,Currency}` · `app/Livewire/` شاشات رفيعة تستدعي Actions ولا تحتوي منطقًا ماليًا · `app/Policies` · `app/Support/{Money,Settings,Navigation,Labels}` · `app/Enums`.
- شاشات الإعداد البسيطة ترث `App\Livewire\Concerns\CrudComponent`. رسائل النجاح عبر trait `Notifies`، ومكوّنات الواجهة في `resources/views/components/ui/*`.
- عنصر قائمة جانبية جديد: `App\Support\Navigation` مع صلاحيته.
- Enums بدل النصوص الحرة، مع `label()` من `lang/*/enums.php`.
- `Model::preventLazyLoading()` و`preventSilentlyDiscardingAttributes()` مفعّلان خارج الإنتاج: استخدم eager loading و`$fillable` صحيحًا.
- كل نص واجهة عبر `__()`؛ مفاتيح `lang/ar` و`lang/en` متطابقة. رسائل التحقق عربية مع أسماء الحقول في `validation.attributes`.
- Factory لكل Model (عدا قيود اليومية). الاختبارات Pest على MySQL؛ البذور الأساسية تُحمَّل مرة لكل تشغيل (`TestCase::$seed`). مساعدات الاختبار في `tests/Pest.php` (`account('14')`, `cashbox(...)`, `posting()`, `baseBalance()`, `ledgerIsBalanced()`).

## قرارات متخذة

- دليل الحسابات: 5 و7 حسابات تجميعية مع أبناء (51 تكلفة السيارات المباعة؛ 71 فروقات العملة، 72 الخصم المسموح به). 6 فيها 61 رواتب، 62 إيجار، 63 كهرباء، 64 دعاية، 65 عمولات المبيعات، 66 مصروفات متنوعة. 11 و12 تجميعيان؛ 1101/1102/1201 تُنشأ مع الخزائن.
- الفترات المالية شهرية؛ تُنشأ سنة كاملة دفعة واحدة. لا يوجد "إعادة فتح" فترة (غير مذكور في المواصفة).
- الخزينة الجديدة تُنشئ حسابها في الدليل تلقائيًا تحت الأب المحدد في الإعدادات؛ نوعها وعملتها ثابتان بعد الإنشاء.
- صلاحيات غير محددة في جدول المواصفة: `exchange_rates.manage` للمدير والمحاسب؛ `references.manage` للمدير والمشتريات؛ `parties.manage` للمدير والمحاسب والمبيعات والمشتريات؛ اعتماد السندات والمصروفات للمدير والمحاسب (أمين الخزينة ينشئ فقط).
- المستخدم يغيّر اسمه وكلمة مروره فقط؛ البريد يغيّره المدير. لا تسجيل ذاتي ولا تحقق بريد.
