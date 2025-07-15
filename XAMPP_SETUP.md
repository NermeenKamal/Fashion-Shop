# دليل إعداد XAMPP لمتجر الملابس

## الخطوة 1: تثبيت XAMPP

1. قم بتحميل XAMPP من [الموقع الرسمي](https://www.apachefriends.org/)
2. ثبت XAMPP على جهازك
3. شغل XAMPP Control Panel

## الخطوة 2: تشغيل الخدمات

1. اضغط على زر **Start** بجانب **Apache**
2. اضغط على زر **Start** بجانب **MySQL**
3. تأكد من أن الحالة تظهر **Running** باللون الأخضر

## الخطوة 3: إنشاء قاعدة البيانات

1. افتح المتصفح واذهب إلى: `http://localhost/phpmyadmin`
2. اضغط على **New** أو **جديد** من القائمة اليسرى
3. أدخل اسم قاعدة البيانات: `fashion_store`
4. اختر الترميز: `utf8mb4_unicode_ci`
5. اضغط **Create** أو **إنشاء**

## الخطوة 4: إعداد المشروع

1. انسخ مجلد المشروع إلى: `C:\xampp\htdocs\fashion-store`
2. افتح Terminal في مجلد المشروع
3. شغل الأوامر التالية:

```bash
# تثبيت التبعيات
composer install

# نسخ ملف البيئة
copy .env.example .env

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل الـ migrations
php artisan migrate

# تشغيل الـ seeders
php artisan db:seed

# إنشاء رابط رمزي
php artisan storage:link
```

## الخطوة 5: تعديل ملف البيئة

افتح ملف `.env` وعدّل إعدادات قاعدة البيانات:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fashion_store
DB_USERNAME=root
DB_PASSWORD=
```

## الخطوة 6: الوصول للموقع

1. افتح المتصفح
2. اذهب إلى: `http://localhost/fashion-store/public`
3. أو أنشئ Virtual Host (اختياري)

## إنشاء Virtual Host (اختياري)

1. افتح ملف: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. أضف السطور التالية:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/fashion-store/public"
    ServerName fashion-store.local
    <Directory "C:/xampp/htdocs/fashion-store/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. افتح ملف: `C:\Windows\System32\drivers\etc\hosts`
4. أضف السطر: `127.0.0.1 fashion-store.local`
5. أعد تشغيل Apache

## إنشاء حساب المدير

```bash
# افتح Terminal في مجلد المشروع
php artisan tinker

# أنشئ مدير جديد
App\Models\User::create([
    'name' => 'المدير',
    'email' => 'admin@fashion-store.com',
    'password' => bcrypt('123456'),
    'role' => 'admin',
    'status' => 'active'
]);
```

## بيانات الدخول الافتراضية

- **البريد الإلكتروني**: admin@fashion-store.com
- **كلمة المرور**: 123456

## استكشاف الأخطاء

### مشكلة في الاتصال بقاعدة البيانات
- تأكد من تشغيل MySQL في XAMPP
- تحقق من إعدادات قاعدة البيانات في ملف `.env`
- تأكد من إنشاء قاعدة البيانات `fashion_store`

### مشكلة في الصلاحيات
- تأكد من صلاحيات الكتابة في مجلد `storage`
- شغل الأمر: `php artisan storage:link`

### مشكلة في الصفحة البيضاء
- تأكد من تشغيل Apache في XAMPP
- تحقق من ملف `.htaccess` في مجلد `public`
- تأكد من تفعيل mod_rewrite في Apache

### مشكلة في التحميل البطيء
- تأكد من إعدادات PHP في XAMPP
- زد قيمة `memory_limit` في `php.ini`
- تأكد من تفعيل OPcache

## إعدادات PHP الموصى بها

افتح ملف `C:\xampp\php\php.ini` وعدّل:

```ini
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 64M
post_max_size = 64M
```

## النسخ الاحتياطي

```bash
# نسخ احتياطي لقاعدة البيانات
php artisan db:backup

# استعادة قاعدة البيانات
php artisan db:restore
```

## التحديثات

```bash
# تحديث التبعيات
composer update

# تشغيل الـ migrations الجديدة
php artisan migrate

# مسح التخزين المؤقت
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## الدعم

إذا واجهت أي مشاكل:
1. تحقق من سجلات الأخطاء في `storage/logs`
2. تأكد من إعدادات XAMPP
3. راجع الوثائق الرسمية
4. اطلب المساعدة من المجتمع

---

**ملاحظة مهمة**: تأكد من تحديث XAMPP إلى أحدث إصدار للحصول على أفضل أداء وأمان. 