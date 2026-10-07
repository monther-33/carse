# دليل التثبيت والتشغيل — منظومة إدارة معرض السيارات

هذا الدليل لمن يثبّت المنظومة ويشغّلها ويصونها. يغطي: التثبيت على خادم Linux (موصى به)، أو على جهاز Windows بـ XAMPP، ثم بدء التشغيل الفعلي، والمهام المجدولة، والنسخ الاحتياطي والاستعادة، والتحديث.

---

## 1. المتطلبات

| المكوّن | الإصدار |
|---|---|
| PHP | 8.2 (أو أحدث من فرع 8.x) |
| امتدادات PHP | `pdo_mysql`، `mbstring`، `gd`، `zip`، `intl`، `bcmath`، `fileinfo`، `exif` |
| قاعدة البيانات | MariaDB 10.4 أو أحدث، أو MySQL 8 |
| Composer | 2.x |
| Node.js | 20 أو أحدث (لبناء الواجهة فقط) |
| خادم الويب | Nginx + PHP-FPM (Linux)، أو Apache الخاص بـ XAMPP (Windows) |
| أدوات | `mysqldump` (للنسخ الاحتياطي)، `unzip` |

الحجم المتوقع صغير (5–20 مستخدمًا، 50–300 سيارة): خادم بمعالجين و2 جيجابايت ذاكرة يكفي.

---

## 2. التثبيت على خادم Linux (Ubuntu 24.04)

### 2.1 الحزم

```bash
sudo apt update
sudo apt install -y nginx mariadb-server unzip git \
  php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-gd php8.2-zip php8.2-intl \
  php8.2-bcmath php8.2-xml php8.2-curl
# Composer و Node.js 20 حسب طريقتك المعتادة
```

> إن لم يتوفر `php8.2` في مستودعات التوزيعة، أضف مستودع `ppa:ondrej/php`.

### 2.2 قاعدة البيانات

```sql
CREATE DATABASE cars CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cars'@'localhost' IDENTIFIED BY 'كلمة-مرور-قوية';
GRANT ALL PRIVILEGES ON cars.* TO 'cars'@'localhost';
FLUSH PRIVILEGES;
```

### 2.3 الشيفرة والإعداد

```bash
sudo mkdir -p /var/www/cars && sudo chown $USER:www-data /var/www/cars
git clone <رابط-المستودع> /var/www/cars
cd /var/www/cars

composer install --no-dev --optimize-autoloader
npm ci && npm run build

cp .env.example .env
php artisan key:generate
```

عدّل `.env`:

```dotenv
APP_NAME="معرض السيارات"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cars.example.ly
APP_TIMEZONE=Africa/Tripoli
APP_LOCALE=ar

DB_DATABASE=cars
DB_USERNAME=cars
DB_PASSWORD=كلمة-مرور-قوية

ADMIN_PASSWORD=كلمة-مرور-المدير-الأولى   # تُستخدم مرة واحدة عند البذر ثم تُغيَّر من الواجهة
DEVELOPER_PASSWORD=كلمة-مرور-المبرمج     # حساب developer (فوق المدير، يقفل الصلاحيات)

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

# النسخ الاحتياطي
DB_DUMP_PATH=                 # فارغ = mysqldump من PATH
BACKUP_NAME=cars
BACKUP_PATH=/var/backups/cars # مكان الحفظ (يفضّل قرصًا آخر)
BACKUP_MAIL_TO=               # بريد لتنبيهات الفشل (اختياري، يتطلب إعداد MAIL_*)
```

ثم:

```bash
php artisan migrate --force
php artisan db:seed --force          # الأدوار والصلاحيات، الدليل المحاسبي، العملات، الخزائن، الإعدادات، المدير
php artisan storage:link

sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
sudo mkdir -p /var/backups/cars && sudo chown www-data:www-data /var/backups/cars

php artisan optimize                  # config + routes + views cache
```

> كل الـ Seeders آمنة للتكرار: إعادة تشغيلها لا تدهس الإعدادات ولا صلاحيات الأدوار المعدّلة من الواجهة.

### 2.4 Nginx

`/etc/nginx/sites-available/cars`:

```nginx
server {
    listen 80;
    server_name cars.example.ly;
    root /var/www/cars/public;
    index index.php;

    client_max_body_size 20M;   # صور السيارات وملفات Excel

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_read_timeout 300;   # التقارير الكبيرة والنسخ الاحتياطي اليدوي
    }

    location ~ /\.(?!well-known) { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/cars /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo apt install -y certbot python3-certbot-nginx && sudo certbot --nginx -d cars.example.ly   # HTTPS
```

في `/etc/php/8.2/fpm/php.ini`:

```ini
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 300
date.timezone = Africa/Tripoli
```

ثم `sudo systemctl restart php8.2-fpm`.

### 2.5 المهام المجدولة (Cron)

سطر واحد يشغّل كل المهام في أوقاتها:

```bash
sudo crontab -u www-data -e
```

```cron
* * * * * cd /var/www/cars && php artisan schedule:run >> /dev/null 2>&1
```

المهام المسجلة (بتوقيت `APP_TIMEZONE`):

| الوقت | الأمر | الغرض |
|---|---|---|
| 00:10 | `reservations:expire` | إنهاء الحجوزات المنتهية وتحرير سياراتها (حسب إعداد مصير العربون) |
| 01:30 | `backup:clean` | حذف النسخ القديمة حسب مدة الاحتفاظ |
| 02:00 | `backup:run` | نسخة كاملة: قاعدة البيانات + الصور والمرفقات |
| 07:00 | `alerts:daily` | تنبيهات الأقساط المستحقة والمتأخرة، الحجوزات القريبة الانتهاء، السيارات الراكدة |
| 08:00 | `backup:monitor` | التحقق من وجود نسخة حديثة؛ يُنبّه المدير إن لم توجد |

للتحقق: `php artisan schedule:list`.

### 2.6 عامل الطوابير (Queue)

حاليًا لا تعتمد أي عملية على الطوابير (التنبيهات وصور السيارات المصغّرة تُنفَّذ مباشرة)، لكن `QUEUE_CONNECTION=database` مضبوط ويُستحسن تشغيل العامل حتى لا تتراكم أي مهمة تُضاف مستقبلًا.

`/etc/systemd/system/cars-queue.service`:

```ini
[Unit]
Description=Cars showroom queue worker
After=network.target mariadb.service

[Service]
User=www-data
Group=www-data
Restart=always
WorkingDirectory=/var/www/cars
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload && sudo systemctl enable --now cars-queue
```

---

## 3. التثبيت على Windows بـ XAMPP

مناسب لجهاز واحد داخل المعرض.

1. ثبّت XAMPP بـ PHP 8.2، وفعّل في `C:\xampp\php\php.ini` الامتدادات: `gd`، `zip`، `intl`، `mbstring`، `exif`، `fileinfo`، `pdo_mysql`، واضبط `upload_max_filesize = 20M` و`post_max_size = 25M` و`date.timezone = Africa/Tripoli`.
2. ضع المشروع في مجلد مثل `C:\cars`، ونفّذ خطوات 2.3 نفسها من سطر الأوامر (`composer install --no-dev -o`، `npm ci && npm run build`، `.env`، `migrate`، `db:seed`، `storage:link`).
3. في `.env` أضف مسار `mysqldump`:
   ```dotenv
   DB_DUMP_PATH=C:/xampp/mysql/bin
   BACKUP_PATH=D:/backups/cars
   ```
4. اجعل Apache يخدم مجلد `public`: في `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName cars.local
       DocumentRoot "C:/cars/public"
       <Directory "C:/cars/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   وأضف `127.0.0.1 cars.local` إلى `C:\Windows\System32\drivers\etc\hosts` (وعلى أجهزة الموظفين: عنوان IP لهذا الجهاز).
5. شغّل Apache وMySQL كخدمات من لوحة XAMPP (زر Svc) حتى تعمل بعد إعادة التشغيل.
6. **المهام المجدولة**: من موجّه أوامر بصلاحية مدير:
   ```bat
   schtasks /Create /TN "CarsScheduler" /SC MINUTE /MO 1 /RU SYSTEM ^
     /TR "C:\xampp\php\php.exe C:\cars\artisan schedule:run"
   ```
   الجهاز يجب أن يبقى يعمل في أوقات المهام (02:00 للنسخ الاحتياطي)، أو غيّر الأوقات في `routes/console.php`.

---

## 4. بدء التشغيل الفعلي

بالترتيب، بحساب المدير (اسم المستخدم `admin`) وكلمة `ADMIN_PASSWORD`:

1. **غيّر كلمتي مرور المدير والمبرمج** (`admin` و`developer`) من صفحة الملف الشخصي لكل منهما. حساب المبرمج يقفل من شاشة "قفل الصلاحيات" ما لا يجوز للمدير أو غيره استخدامه. (لا استعادة لكلمة المرور بالبريد: إن نسي مستخدم كلمته يعيّنها المدير من شاشة المستخدمين.)
2. **الإعدادات**: اسم المعرض وهاتفه وعنوانه وشعاره (تظهر في الطباعة)، بنود عقد البيع، العمولة، حدود الركود، فصل الاعتماد، مصير العربون عند انتهاء الحجز.
3. **الفروع والخزائن**: أنشئ الخزائن الفعلية (تُنشئ حساباتها تلقائيًا) وأسند أمناء الخزائن.
4. **الفترات المالية**: السنة الحالية تُنشأ عند البذر؛ أنشئ السنة السابقة إن كان تاريخ الأرصدة الافتتاحية فيها.
5. **أسعار الصرف**: أدخل سعر الدولار بتاريخ الأرصدة الافتتاحية.
6. **المستخدمون**: أنشئ الموظفين بأدوارهم، وحدّد حد الخصم لموظفي المبيعات.
7. **استيراد البيانات** (شاشة "استيراد البيانات"، بهذا الترتيب):
   1. **العملاء والموردون** — نزّل القالب، املأه، افحصه، ثم استورد.
   2. **السيارات الحالية** — بتكلفة كل سيارة بالدينار وتاريخ استلامها الحقيقي (يحدد أعمار المخزون). تُنشأ مسودة "بضاعة أول المدة" ثم تُعتمد.
   3. **الأرصدة الافتتاحية** — الخزائن والمصارف، ما على العملاء، ما للموردين (برمز الحساب، والطرف بالرقم الوطني أو الاسم). تُنشأ مسودة قيد يدوي تُعتمد من شاشة القيود اليدوية.
   4. **إقفال حساب الأرصدة الافتتاحية (34)**: بعد الاعتماد، قيد يدوي: مدين 34 / دائن رأس المال (31) بكامل رصيد 34، حتى يصبح صفرًا.
   - العرابين القائمة لا تُستورد كأرصدة: سجّلها كحجوزات بعربونها من شاشة الحجوزات.
   - التاريخ المقترح للأرصدة: اليوم السابق لبدء التشغيل.
8. راجع **ميزان المراجعة** و**الميزانية العمومية** من التقارير قبل بدء العمل.

> **بيانات العرض**: الأمر `php artisan db:seed --class=DemoSeeder` يملأ قاعدة بيانات فارغة بمعرض تجريبي كامل (مستخدم لكل دور بكلمة `ADMIN_PASSWORD`). يرفض العمل على خادم الإنتاج (`APP_ENV=production`).

## 4.2 تصفير النظام

لإرجاع النظام كما بعد التثبيت (مثلًا بعد فترة تجربة على الخادم قبل البدء الفعلي):

```bash
php artisan db:seed --class=ResetSystemSeeder --force
```

- يطلب كتابة **اسم قاعدة البيانات** للتأكيد، ثم يعرض أخذ **نسخة احتياطية كاملة** أولًا (موصى به).
- يحذف كل الجداول ويعيد بناءها فارغة، ثم يشغّل البذور الأساسية: الأدوار والصلاحيات، الدليل المحاسبي، العملات، الخزائن، الإعدادات الافتراضية، السنة المالية، والمستخدمين `admin` و`developer` بكلمتي `ADMIN_PASSWORD` و`DEVELOPER_PASSWORD`.
- يحذف الصور والمرفقات المرفوعة (صور السيارات، الهويات، الإيصالات، الشعار، نسخ سلة المحذوفات). **النسخ الاحتياطية لا تُحذف.**
- لا يعمل دون تفاعل (`--no-interaction` = إلغاء)، فلا يمكن تشغيله بالخطأ من الجدولة أو سكربت.
- بعده: نفّذ `php artisan optimize` ثم ابدأ من الخطوة 1 في القسم 4.

## 4.3 قائمة ما قبل الرفع

- `.env` على الخادم: `APP_ENV=production`، `APP_DEBUG=false`، `APP_URL` بعنوان HTTPS، `APP_KEY` مولّد (`php artisan key:generate`)، كلمتا `ADMIN_PASSWORD` و`DEVELOPER_PASSWORD` قويتان، `BACKUP_PATH` على قرص آخر، و`LOG_LEVEL=warning`.
- لا ترفع ملف `.env` الخاص بجهاز التطوير، ولا مجلدات `vendor` و`node_modules` و`storage/app/public` و`storage/app/private` (بيانات التجربة)؛ تُبنى على الخادم بـ `composer install --no-dev -o` و`npm ci && npm run build`.
- قاعدة بيانات جديدة فارغة ثم `php artisan migrate --force` و`php artisan db:seed --force` (البذور الأساسية فقط، **لا** `DemoSeeder`).
- `php artisan storage:link` و`php artisan optimize`، ثم Cron/Task Scheduler (القسم 2.5 أو 3).
- ادخل بـ `admin` و`developer` وغيّر كلمتي المرور فورًا، ثم أكمل القسم 4.

---

## 4.1 التطبيق المثبَّت (PWA)

المنظومة تُثبَّت كتطبيق على الهاتف والتابلت والكمبيوتر (أيقونة المعرض واسمه، نافذة مستقلة بلا شريط متصفح، واختصارات: فاتورة بيع، السيارات، سند).

- **شرط أساسي: HTTPS.** المتصفحات لا تسمح بالتثبيت إلا على `https://` (أو `localhost` على نفس الجهاز). على خادم Linux يكفي certbot (القسم 2.4). على شبكة المعرض الداخلية بـ XAMPP يلزم شهادة محلية، مثلًا بأداة `mkcert` لاسم مثل `cars.local`، مع تثبيت شهادتها الجذرية على أجهزة الموظفين.
- **التثبيت:** Chrome / Edge / Android: زر "تثبيت التطبيق" في قائمة المستخدم (أو في صفحة الدخول) أو أيقونة التثبيت في شريط العنوان. iPhone / iPad: من Safari ← مشاركة ← "إضافة إلى الشاشة الرئيسية".
- **الأيقونة** تُولَّد من شعار المعرض في الإعدادات (أو سيارة بلون النظام إن لم يُرفع شعار)، وتتحدث عند تغيير الشعار.
- **بلا بيانات مخبّأة:** عامل الخدمة (`public/sw.js`) يخبّئ ملفات التصميم فقط (CSS/JS/الخطوط). الصفحات والبيانات والعمليات تذهب دائمًا للخادم، وعند انقطاع الشبكة تظهر صفحة "لا يوجد اتصال" بدل بيانات قديمة. النظام لا يعمل دون اتصال بالخادم (مقصود: الأرصدة والمخزون يجب أن تكون لحظية).
- في Nginx أضف لعامل الخدمة منع التخزين المؤقت حتى تصل تحديثاته فورًا:
  ```nginx
  location = /sw.js { add_header Cache-Control "no-cache"; try_files $uri =404; }
  ```

---

## 5. النسخ الاحتياطي والاستعادة

- كل ليلة الساعة 02:00 تُنشأ نسخة ZIP في `BACKUP_PATH/BACKUP_NAME/` تحتوي:
  - `db-dumps/mysql-cars.sql` — قاعدة البيانات كاملة.
  - `public/` و`private/` — صور السيارات وصور الهويات والإيصالات والمرفقات.
- الاحتفاظ: كل النسخ لـ 7 أيام، ثم يومية لـ 16 يومًا، أسبوعية لـ 8 أسابيع، شهرية لـ 4 أشهر، سنوية لسنتين.
- شاشة "النسخ الاحتياطي" (للمدير): آخر نسخة، القائمة، التنزيل، و"نسخة احتياطية الآن".
- فشل النسخ أو عدم وجود نسخة حديثة يظهر للمدير في جرس التنبيهات (وبالبريد إن ضُبط `BACKUP_MAIL_TO`).
- **نسخة إضافية في مكان آخر**: من شاشة "النسخ الاحتياطي" اكتب مجلدًا على قرص آخر أو قرص خارجي أو مجلد مزامنة سحابية (OneDrive / Google Drive)، مثل `D:\Backups`. كل نسخة تُحفظ في المكانين بنفس مدة الاحتفاظ، وإن لم يكن المجلد متاحًا (قرص مفصول) يصل تنبيه للمدير وتبقى النسخة الأساسية. النسخة على نفس القرص وحدها لا تحمي من تلفه.

### الاستعادة

```bash
php artisan down
unzip /var/backups/cars/cars/2026-10-05-02-00-00.zip -d /tmp/restore

# قاعدة البيانات (تستبدل البيانات الحالية بالكامل)
mysql -u cars -p cars < /tmp/restore/db-dumps/mysql-cars.sql

# الملفات
rsync -a /tmp/restore/public/  /var/www/cars/storage/app/public/
rsync -a /tmp/restore/private/ /var/www/cars/storage/app/private/

php artisan optimize:clear && php artisan up
```

على Windows: فك الضغط بالمستكشف، واستورد الملف بـ
`C:\xampp\mysql\bin\mysql.exe -u root cars < db-dumps\mysql-cars.sql`، وانسخ المجلدين إلى `storage\app\`.

> جرّب الاستعادة على قاعدة اختبار مرة كل بضعة أشهر للتأكد من صلاحية النسخ.

---

## 6. التحديث

```bash
cd /var/www/cars
php artisan down
php artisan backup:run                 # نسخة قبل التحديث
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan db:seed --force            # يضيف الصلاحيات والإعدادات الجديدة فقط
php artisan optimize
php artisan queue:restart
php artisan up
```

---

## 7. مشكلات شائعة

| العرض | السبب والحل |
|---|---|
| فشل النسخ: `mysqldump: not found` | اضبط `DB_DUMP_PATH` على مجلد `mysqldump` (في XAMPP: `C:/xampp/mysql/bin`). |
| التنبيهات أو النسخ لا تعمل | Cron / Task Scheduler غير مضبوط؛ تحقق بـ `php artisan schedule:list` ثم شغّل `php artisan schedule:run` يدويًا. |
| مبيعات منتصف الليل تظهر في اليوم السابق | `APP_TIMEZONE` غير مضبوط على `Africa/Tripoli`؛ بعد تعديله نفّذ `php artisan optimize`. |
| الطباعة PDF فارغة أو خطأ صلاحيات | المجلد `storage/app/mpdf` يجب أن يكون قابلًا للكتابة لمستخدم خادم الويب. |
| الصور لا تظهر | نفّذ `php artisan storage:link` وتأكد من `APP_URL`. |
| رفض رفع صورة أو ملف Excel كبير | ارفع `upload_max_filesize` و`post_max_size` في PHP و`client_max_body_size` في Nginx. |
| "لا يوجد سعر صرف" عند الاستيراد أو الترحيل | أدخل سعر العملة بتاريخ المستند أو قبله من شاشة العملات. |
| تغيير لا يظهر بعد تعديل `.env` | `php artisan optimize` (الإعدادات مخزّنة مؤقتًا في الإنتاج). |
