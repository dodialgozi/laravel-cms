## Requirements & Stack
1. PHP 8.1
2. Laravel 10
3. PHP Imagick
4. PHP FileInfo
## How to install
1. Jalankan `composer install`
2. Kemudian copy `.env.example` menjadi `.env`
3. Sesuaikan isi database di `.env`
4. Generate key untuk laravel dengan cara command `php artisan key:generate`
5. Jalankan migrate `php artisan migrate:fresh --seed`

## Cara membuat fitur baru
1. Buat Controller dengan isi seperti dibawah ini

```
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    protected $title = 'Pengguna';
    protected $modelClass = User::class;
    protected $alias = 'p';
    protected $descKey = 'user_name';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.user';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $urlRedirect = 'user';
    protected $searchColumn = ['nama' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'p.user_name'];

    protected $selectFind = ['user_name'];
    protected $selectColumn = ['id', 'user_name As nama'];

    protected $permissionName = "user";
}

```
Ada beberapa field yang bisa diisi, Lihat penjelasannya di BaseController.php

2. Buat View index.php dan form.php di backend/admin/partials dengan nama folder sesuai dengan nama $view
3. Untuk index.php berisi:

```
@extends('backend.layouts.panel')

@section('title', $title)

@php
$tableData = [
	[
		'label' => 'Nama',
		'field' => 'user_name',
		'sort' => 'nama',
		'search' => true,
	],
];

$action = function($item) use($mainURL, $primaryKey, $title, $permission, $descKey) {
	$encId = encodeId($item->{$primaryKey});
	
	return actionButtons([
		[
			userCan("{$permission}.ubah"),
			'Ubah',
			'fas fa-pencil-alt text-warning',
			urlWithQueryParams("{$mainURL}/{$encId}/edit"),
		],
		[
			userCan("{$permission}.hapus"),
			'Hapus',
			'fas fa-trash-alt text-danger',
			'onClick' => [
				'hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? '',
			],
		],
	]);
};
@endphp

@section('panel_content')
<x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
@if(userCan("{$permission}.tambah"))
<a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}" ><i class="label-icon fas fa-plus"></i> Tambah</a>
@endif
@endsection
```

4. Untuk form.php berisi
```
<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-input label="Role" name="{{ $input }}[name]" value="{{ $result->name ?? '' }}" required/>
    </div>
</div>
```

Sesuaikan index dan form

5. Jika ingin menggunakan form validasi, buat validasi dengan perintah `php artisan make:request NamaRequest`
6. Kemudian sesuaikan isinya dengan request (tiru di RoleRequest.php)
7. Kemudian di controller tambahkan property

`protected $formRequest = NamaRequest::class;`

8. Harap diingat `$this->input` pada Request itu harus sesuai dengan `$input` yang ada di Controller, (biasanya isinya pasti sama)

9. Buat model baru (jika belum ada) dengan perintah `php artisan make:model NamaModel`
10. Defaultnya isi model seperti ini:
```
protected $table = 'roles';
protected $primaryKey = 'id';
protected $perPage = 20;
protected $guarded = [];

use ModelFunction;
```
10. Tambahkan method relasi di moddel (jika model tsb memiliki relasi dengan model lain)

11. Tambahkan permission di `optional/permission.json`
12. Untuk fitur yang hanya CRUD buat seperti ini (`"default": true`):
```
	{
		"group": "Roles",
		"default": true,
		"key": "roles"
	},
```
13. Jika fitur yang dikembangkan memiliki rincian/detail, buat seperti ini (tambahkan permission `"rincian"`):
```
{
	"group": "User",
	"default": true,
	"key": "user",
	"permissions": [
		"rincian"
	]
},
```
14. Jika fitur yang dikembangkan memiliki permission lainnya, tambahkan permission baru seperti ini:
```
{
	"group": "Post",
	"default": true,
	"key": "post",
	"permissions": [
		"rincian",
		{"key": "schedule", "name": "Menjadwalkan Postingan"}
	]
},
```
15. Jika fitur yang dikembangkan tidak memiliki CRUD, tambahkan permission baru saja (`"default": false`):
```
{
	"group": "Pengaturan",
	"default": false,
	"key": "pengaturan",
	"permissions": [
		{"key": "umum", "name": "Lihat Pengaturan Umum"},
		{"key": "menu", "name": "Lihat Pengaturan Menu"}
	]
}
```

16. Tambah menu di sidebar di `optional/menu.json`.
```
{
	"menu_name": "Pengaturan", 
	"menu_link": "",
	"menu_class": "",
	"menu_icon": "bx bxs-cog",
	"permission": "",
	"menu_children": [
		{
			"menu_name": "Pengaturan Umum", 
			"menu_link": "{[BASEURL]}/pengaturan/umum",
			"menu_class": "",
			"permission": "pengaturan.umum"
		},
		{
			"menu_name": "Pengaturan Menu", 
			"menu_link": "{[BASEURL]}/pengaturan/menu",
			"menu_class": "",
			"permission": "pengaturan.menu"
		}
	]
}
```
sesuaikan value itemnya, jika tidak memiliki children, cukup hapus item `menu_children`.

17. Tambahkan route di `routes/web/...`
Default route nya sebagai berikut:
```
$pref = 'user';
$ctrl = 'UserController';
Route::resource($pref, "{$ctrl}");
```

Jika ada tambahan method di controller:
```
$pref = 'page';
$ctrl = 'PageController';
Route::prefix($pref)->group(function () use ($ctrl) {
	Route::post('upload', "{$ctrl}@upload");
});
Route::resource($pref, "{$ctrl}");
```
