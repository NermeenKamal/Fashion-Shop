# دليل التشغيل السريع - متجر الملابس

## 🚀 التشغيل السريع (5 دقائق)

### 1. تشغيل XAMPP
```bash
# شغل XAMPP Control Panel
# اضغط Start على Apache و MySQL
```

### 2. إنشاء قاعدة البيانات
```bash
# افتح http://localhost/phpmyadmin
# أنشئ قاعدة بيانات: fashion_store
# الترميز: utf8mb4_unicode_ci
```

### 3. إعداد المشروع
```bash
# في مجلد المشروع
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 4. تعديل ملف البيئة
```env
DB_DATABASE=fashion_store
DB_USERNAME=root
DB_PASSWORD=
```

### 5. إنشاء المدير
```bash
php artisan tinker
App\Models\User::create([
    'name' => 'المدير',
    'email' => 'admin@test.com',
    'password' => bcrypt('123456'),
    'role' => 'admin'
]);
```

### 6. الوصول للموقع
```
http://localhost/fashion-store/public
```

## 👤 بيانات الدخول

### المدير
- **البريد**: admin@test.com
- **كلمة المرور**: 123456

### المستخدم العادي
- **البريد**: user@test.com  
- **كلمة المرور**: 123456

## 📱 الوصول للوحات

### لوحة الإدارة
```
http://localhost/fashion-store/public/admin
```

### لوحة المستخدم
```
http://localhost/fashion-store/public/profile
```

## 🔧 الأوامر المفيدة

```bash
# مسح التخزين المؤقت
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# إعادة تشغيل الخادم
php artisan serve

# تشغيل الـ watcher للتطوير
npm run dev

# تجميع الأصول للإنتاج
npm run build
```

## 🐛 استكشاف الأخطاء

### مشكلة في قاعدة البيانات
```bash
# تأكد من تشغيل MySQL
# تحقق من إعدادات .env
# شغل: php artisan migrate:fresh --seed
```

### مشكلة في الصلاحيات
```bash
# تأكد من صلاحيات مجلد storage
# شغل: php artisan storage:link
```

### مشكلة في الصفحة البيضاء
```bash
# تأكد من تشغيل Apache
# تحقق من ملف .htaccess
# شغل: php artisan serve
```

## 📞 الدعم السريع

- **مشكلة في XAMPP**: تأكد من تشغيل Apache و MySQL
- **مشكلة في قاعدة البيانات**: تحقق من إعدادات .env
- **مشكلة في الصلاحيات**: تأكد من صلاحيات مجلد storage
- **مشكلة في التصميم**: شغل `npm run dev`

---

**ملاحظة**: هذا دليل سريع للتشغيل. للتفاصيل الكاملة راجع `README.md` 