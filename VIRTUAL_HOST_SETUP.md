# 🔧 حل مشكلة "Not Found" - إعداد Virtual Host

## المشكلة
عند الوصول لـ `http://localhost/fashion-store/public` تظهر رسالة "Not Found"

## الحل: إعداد Virtual Host

### الخطوة 1: تعديل ملف httpd-vhosts.conf

1. **افتح الملف**: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. **أضف هذا الكود في نهاية الملف**:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/fashion-store/public"
    ServerName fashion-store.local
    ServerAlias www.fashion-store.local
    <Directory "C:/xampp/htdocs/fashion-store/public">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/fashion-store-error.log"
    CustomLog "logs/fashion-store-access.log" combined
</VirtualHost>
```

### الخطوة 2: تعديل ملف hosts

1. **افتح الملف**: `C:\Windows\System32\drivers\etc\hosts`
2. **أضف هذا السطر**:
```
127.0.0.1 fashion-store.local
127.0.0.1 www.fashion-store.local
```

### الخطوة 3: إعادة تشغيل Apache

1. **في XAMPP Control Panel**
2. **اضغط Stop على Apache**
3. **انتظر قليلاً**
4. **اضغط Start على Apache**

### الخطوة 4: الوصول للموقع

```
http://fashion-store.local
```

## الحل البديل: تعديل DocumentRoot

إذا لم يعمل Virtual Host:

### الخطوة 1: تعديل httpd.conf

1. **افتح الملف**: `C:\xampp\apache\conf\httpd.conf`
2. **ابحث عن DocumentRoot**
3. **غيّر السطر إلى**:
```apache
DocumentRoot "C:/xampp/htdocs/fashion-store/public"
```

### الخطوة 2: تعديل Directory

ابحث عن `<Directory "C:/xampp/htdocs">` وغيّره إلى:
```apache
<Directory "C:/xampp/htdocs/fashion-store/public">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

### الخطوة 3: إعادة تشغيل Apache

### الخطوة 4: الوصول للموقع

```
http://localhost
```

## الحل السريع: استخدام Laravel Serve

إذا لم تعمل الحلول السابقة:

```bash
# في مجلد المشروع
cd C:\xampp\htdocs\fashion-store

# تشغيل خادم Laravel
php artisan serve
```

ثم اذهب إلى:
```
http://127.0.0.1:8000
```

## التحقق من الإعداد

### 1. تأكد من وجود الملفات
```
C:\xampp\htdocs\fashion-store\
├── public\
│   ├── index.php
│   └── .htaccess
├── app\
├── resources\
└── ...
```

### 2. تأكد من صلاحيات الملفات
- تأكد من أن مجلد `storage` قابل للكتابة
- تأكد من وجود ملف `.htaccess` في مجلد `public`

### 3. تحقق من سجلات الأخطاء
- **Apache Error Log**: `C:\xampp\apache\logs\error.log`
- **Laravel Log**: `C:\xampp\htdocs\fashion-store\storage\logs\laravel.log`

## استكشاف الأخطاء

### مشكلة: "mod_rewrite not enabled"
```apache
# في httpd.conf، تأكد من تفعيل:
LoadModule rewrite_module modules/mod_rewrite.so
```

### مشكلة: "Permission denied"
```bash
# تأكد من صلاحيات المجلدات
php artisan storage:link
```

### مشكلة: "Database connection failed"
```bash
# تأكد من تشغيل MySQL
# تحقق من إعدادات .env
```

## الأوامر المفيدة

```bash
# مسح التخزين المؤقت
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# إعادة تشغيل الخادم
php artisan serve --host=0.0.0.0 --port=8000
```

## الدعم

إذا استمرت المشكلة:
1. تحقق من سجلات الأخطاء
2. تأكد من إعدادات XAMPP
3. جرب الحل البديل باستخدام `php artisan serve` 