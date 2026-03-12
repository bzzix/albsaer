---
description: إنشاء وحدة جديدة في لوحة التحكم (Routes, Controller, Blade, Livewire)
---

# Workflow: إنشاء وحدة جديدة في لوحة التحكم

هذا الـ Workflow يوضح الخطوات القياسية لإنشاء أي وحدة جديدة في لوحة التحكم، مثل (المنتجات، الموردين، العملاء، إلخ).

## الخطوات بالترتيب:

### 1️⃣ إنشاء Routes في `routes/web.php`
- إضافة المسارات داخل مجموعة `dashboard`
- استخدام `prefix` و `name` مناسبين
- إضافة middleware للصلاحيات

**مثال:**
```php
// داخل مجموعة dashboard
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', 'productsIndex')->name('index')->middleware('can:products_view');
    Route::get('/create', 'productsCreate')->name('create')->middleware('can:products_add');
});
```

---

### 2️⃣ إنشاء Methods في `DashboardController`
- إضافة الدوال في `app/Http/Controllers/Dashboard/DashboardController.php`
- كل دالة ترجع view مع البيانات المطلوبة
- Controllers خفيفة (Thin Controllers)

**مثال:**
```php
public function productsIndex()
{
    return view('dashboard.products.index');
}

public function productsCreate()
{
    return view('dashboard.products.create');
}
```

---

### 3️⃣ إنشاء مجلد Views
- إنشاء مجلد في `resources/views/dashboard/[module-name]/`
- إنشاء ملف `index.blade.php` على الأقل

**مثال:**
```
resources/views/dashboard/products/
├── index.blade.php
├── create.blade.php (اختياري)
└── edit.blade.php (اختياري)
```

---

### 4️⃣ إنشاء Livewire Component
- إنشاء Component في `app/Livewire/Dashboard/[ModuleName]/`
- إنشاء View في `resources/views/livewire/dashboard/[module-name]/`

**الأمر:**
```bash
php artisan make:livewire Dashboard/Products/ProductsTable
```

**سيُنشئ:**
- `app/Livewire/Dashboard/Products/ProductsTable.php`
- `resources/views/livewire/dashboard/products/products-table.blade.php`

---

### 5️⃣ إنشاء DataTable (إذا لزم)
- إنشاء Livewire DataTable باستخدام `rappasoft/laravel-livewire-tables`
- في نفس مجلد Livewire

**مثال:**
```php
namespace App\Livewire\Dashboard\Products;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use App\Models\Product;

class ProductsDataTable extends DataTableComponent
{
    // ...
}
```

---

### 6️⃣ ربط Livewire مع Blade
- في ملف `index.blade.php`، استدعاء مكون Livewire

**مثال:**
```blade
@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>إدارة المنتجات</h1>
    
    @livewire('dashboard.products.products-table')
</div>
@endsection
```

---

### 7️⃣ إضافة الرابط في Sidebar
- تعديل ملف `resources/views/dashboard/layouts/sidebar.blade.php`
- إضافة رابط القائمة الجديدة

**مثال:**
```blade
<li>
    <a href="{{ route('dashboard.products.index') }}">
        <i class="icon"></i>
        <span>المنتجات</span>
    </a>
</li>
```

---

### 8️⃣ إنشاء الصلاحيات (إذا لزم)
- إضافة الصلاحيات في `database/seeders/RolesSeeder.php`
- تشغيل Seeder

**مثال:**
```php
$products_view = Permission::create([
    'name' => 'products_view',
    'display_name' => 'عرض المنتجات',
]);
```

---

## 📋 Checklist سريع:

- [ ] إنشاء Routes في `web.php`
- [ ] إنشاء Methods في `DashboardController`
- [ ] إنشاء مجلد Views
- [ ] إنشاء ملف `index.blade.php`
- [ ] إنشاء Livewire Component
- [ ] إنشاء DataTable (اختياري)
- [ ] ربط Livewire مع Blade
- [ ] إضافة رابط في Sidebar
- [ ] إنشاء الصلاحيات (اختياري)
- [ ] اختبار الوحدة

---

## 🎯 مثال كامل: وحدة المنتجات

### 1. Routes
```php
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', 'productsIndex')->name('index')->middleware('can:products_view');
});
```

### 2. Controller Method
```php
public function productsIndex()
{
    return view('dashboard.products.index');
}
```

### 3. Blade View
```blade
@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>إدارة المنتجات</h1>
            @livewire('dashboard.products.products-table')
        </div>
    </div>
</div>
@endsection
```

### 4. Livewire Component
```php
namespace App\Livewire\Dashboard\Products;

use Livewire\Component;

class ProductsTable extends Component
{
    public function render()
    {
        return view('livewire.dashboard.products.products-table');
    }
}
```

---

## ⚠️ ملاحظات مهمة:

1. **التسمية**: استخدم نفس الاسم في كل مكان (products مثلاً)
2. **الصلاحيات**: تأكد من إنشاء الصلاحيات قبل استخدام middleware
3. **Livewire**: استخدم Livewire لجميع التفاعلات (لا Ajax يدوي)
4. **iziToast**: استخدمه للإشعارات
5. **Service Classes**: للمنطق الحسابي المعقد

---

**آخر تحديث**: 2026-02-05
