# php-mvc-starter

**Project starter dengan konsep MVC untuk bahasa pemrograman PHP**. MVC adalah singkatan dari Model-View-Controller. Ini adalah sebuah pola arsitektur yang populer digunakan dalam pengembangan aplikasi web, termasuk PHP.
Konsep MVC ini diinisialisasi sesuai dengan versi Warkim, digunakan pribadi untuk memulai project (kosong) dengan konsep modern _Object Orientation Programming (OOP)_ dan _tanpa menggunakan framework_.
Jika kamu menemukan repositori ini dan ingin menggunakannya, dipersilahkan untuk digunakan secara public namun tidak boleh merusak atribut.
Jika kamu berminat mengembangkan source ini juga dengan senang hati dipersilahkan.

## FITUR

- [ ] **RINGAN & MUDAH DIGUNAKAN**
- [ ] **ROUTER YANG FLEKSIBEL**
- [ ] **BLADE TEMPLATE ENGINE**
- [ ] **OPEN SOURCE**

## CARA PENGGUNAAN

- Download repo ini dan unzip/extract
- Masuk ke direktori/folder projectnya
- Buka (projectnya) dengan IDE/text editor
- Buka terminal dan pastikan sudah mengarah ke direktori projectnya
- Install dependensi melalui composer `composer update`
- Jalankan local development server (bebas, xampp, laragon, ..etc)
- Jalankan service Apache/Nginx atau dengan command `php -S localhost:3000` (port bebas)

## ENVIRONMENT

- File .env atau environment adalah file yang memuat informasi yang bisa ditambahkan sesuai dengan kebutuhan pengembangan website
- Default: berada di dalam folder `_env`
- **UBAH NAMA FOLDER** `_env` DENGAN NAMA FOLDER UNIK ATAU YANG TIDAK MUDAH DITEBAK
- Misal diubah menjadi folder `!!_enpironmen` atau apapun bebas, tapi perhatikan poin dibawah ini
- SESUAIKAN LOKASINYA, sesuaikan path-nya didalam "app/config/app.php" pada bagian `'env' => realpath(__DIR__ . '/../../_env/.env'),`

## ROUTE

Route berada pada folder routes atau `routes/web.php`, contoh penggunaannya seperti berikut:

        <?php
        use Warkim\controllers\Contact;
        use Warkim\core\Route;
        use Warkim\models\User;

       Route::get('/', function () {
            echo "Selamat datang di Homepage";
        });

       Route::get('/users/index', function () {
            $users = User::all();
            return view('users.index', ['users' => $users]);
        });

       Route::get('/contact', [Contact::class, 'index']);

### Penulisan Route Callback Function

    // Model Pertama
    Route::get('/', function () {
        return view('home', [
            'title' => 'Halaman Utama'
        ]);
    });

    // Model Kedua
    Route::get('/home/{title}', fn(Request $request, $title) => view('home',['title' =>$title]));

### Penulisan Route Class & Method

    use Warkim\core\Route;
    use Warkim\controllers\UserController;

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users/store', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

## MENGAKSES .ENV DARI (METHOD) CONFIG

Untuk dapat menggunakan atau mengambil informasi dari file **.env** kamu dapat menggunakan method **`config()`**. Contoh penggunaan di **Controller**:

    <?php
    namespace Warkim\controllers;
    use Warkim\models\User;

    class  Contact
    {

        public  function  index()
        {
    	    $app_name  =  config('APP_NAME');
    	    $users  =  User::all();

    	    return  view('contact.contact',  [
    		    'app_name'  =>  $app_name,
    		    'users'  =>  $users
    	    ]);
        }

    }

Contoh penggunaan di **View** (Warkim\views) file dan juga pada **Route** (routes\web.php):

    <?php
    echo  'Nama Aplikasi: '  .  config('APP_NAME');
    ?>
    <h2>Halaman Kontak</h2>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus, sed. A laborum quidem porro consectetur vel veritatis mollitia deleniti ipsam unde sunt praesentium, nisi qui quasi dolore laboriosam exercitationem iusto quod.</p>

## CEK URL DAN URL QUERY PARAMETER (ACTIVE ROUTE)

- Jika dibutuhkan untuk mengecek route yang saat ini bisa menggunakan **`Route::is()`**, mengembalikan tipe data **_Array_**
- Jika ingin mengecek/membandingkan route saat ini dengan yang dikehendaki, maka bisa menggunakan **`Route::is('/nama-route-yang-dikehendaki')`**, mengambalikan tipe data **_Boolean_**
- Jika ingin mengecek query parameter apa saja yang sedang dibuka atau mengambil nilai query tersebut bisa menggunakan **`Route::query()`** mengembalikan **_Array_**, **`Route::query('nama-key')`** mengembalikan **_String_** atau **_NULL_**

## MENGGUNAKAN ROUTE **`route()`** UNTUK ACTION FORMULIR

Daripada menggunakan action url static, sekarang sudah ditambahkan method route() dengan memberikan 1 argumen string. Contoh saya mempunyai Route di **`routes/web.php`**:

### Store Action

    <?php
    use Warkim\core\Route;
    use Warkim\controllers\UserController;
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users/store', [UserController::class, 'store']);

Digunkan di Views **`Warkim\views\users\create.php`** , masukkan action formulir dengan **`route('users.store')`** seperti dibawah. Gunakan titik (.) untuk menggantikan slash (/) atau tetap bisa menggunakan slash:

    <h2>Tambahkan User</h2>

    <form action="<?= route('users.store'); ?>" method="post">
        <div>
            <label for="nama">Name</label>
            <input type="text" name="nama" id="nama">
        </div>
        <div>
            <label for="umur">Umur</label>
            <input type="number" name="umur" id="umur">
        </div>
        <button type="submit">Submit</button>
    </form>

### Edit Action

Jika menggunakan route action edit, kamu disarankan menulisankan url-nya sesuai dengan route dikarenakan belum tersedia Route Model Binding 🙏. Contoh `routes/web.php`

    use Warkim\core\Route;
    use Warkim\controllers\UserController;

    Route::get('/users/{id}/edit', [UserController::class, 'edit']); // Edit
    Route::post('/users/{id}', [UserController::class, 'update']); // Update

Maka di tombol action yang mengarahkan ke halaman edit perlu menyesuaikan url atau me-replace pattern ({id}) seperti berikut `route('/users/'.$user->id.'edit')`:

    <a href="{{ route('/users/'.$user->id.'edit') }}"  target="_self"  class="btn btn-primary">
    Edit
    </a>

**Action Update**, Penyesuaian formulir pada action yang mengarahkan ke update, harus mereplace pattern {id} seperti di url seperti berikut `route('users',  $user->id)`:

    <form  action="<?=  route('users',  $user->id);  ?>"  method="post">
        <div>
    	    <label  for="nama">Name</label>
    	    <input  type="text"  name="nama"  id="nama">
        </div>
    	  <div>
    		  <label  for="umur">Umur</label>
    		  <input  type="number"  name="umur"  id="umur">
        </div>
        <button  type="submit">Submit</button>
    </form>

## MODELS

Model merupakan **bagian yang bertugas untuk mengatur, menyiapkan, memanipulasi, dan mengorganisir data (biasanya dari basis data)**. Direktori Model berada pada app\models, kamu bisa menyimpannya disana. Setiap model yang kamu buat harus extends ke **`Warkim\core\Model`**. Berikut contoh penggunaan model user.

    <?php
    namespace Warkim\models;
    use Warkim\core\Model;

    class  User  extends  Model
    {

    protected  $table  =  "users";
    protected  $primaryKey  =  "id";
    protected  $columns  = ["nama", "umur"];

    }

### REKOMENDASI ATRIBUT DI MODEL

Agar koneksi ke tabel di database berjalan dengan baik, kami merekomendasikan untuk membuat beberapa definisi dengan atribut (attributes) di dalam model kelas yang dibuat. Tambahkan definisi atribut ini:

- **protected $table**, isi dengan string nama tabel (table name)
- **protected $primaryKey**, isi dengan string nama kolom PRIMARY KEY
- **protected $columns**, diisi dengan nama kolom-kolom yang ada pada tabel (_terdefinisi diatas_) dan dimasukkan kedalam Array

## INHERITANCE DARI MODELS

Jika model yang kamu definisikan sudah extends ke _core Model_ maka akan mewarisi methods berikut yang dapat digunakan di _Controller_:
| Methods | Tipe | Penjelasan |
|--|--|--|
| **YourModel::all()** | object | mengambil/menampilkan semua data records |
| **YourModel::where('column_name', 'operand', 'value')->get()** | object | mencari record berdasarkan nama kolom (column_name), operand (null/empty, =, !=, LIKE) dan nilai (value : string, integer, boolean) |
| **YourModel::where('column_name', 'value')->orderBy('column_name', 'order')->get()** | object | mencari record berdasarkan nama kolom (column_name), operand (null/empty, =, !=, LIKE) dan nilai (value : string, integer, boolean) dan melakukan order (ASCENDING atau DESCENDING) |
| **YourModel::where('column_name', 'value')->orderBy('column_name', 'order')->first()** | object | mencari record berdasarkan nama kolom (column_name), operand (null/empty, =, !=, LIKE) dan nilai (value : string, integer, boolean) dan **dapat disertakan/tidak dengan order** (ASCENDING atau DESCENDING) dan mengambil hanya 1 record sesuai order |
| **YourModel::where('column_name', 'value')->latest()** | object | mencari record berdasarkan nama kolom (column_name), operand (null/empty, =, !=, LIKE) dan nilai (value : string, integer, boolean) dan mengambil semua record mulai dari Z-A/Descending |
| **YourModel::create()** | object | menyimpan data. isi create() dengan Array seperti berikut: `User::create(['nama' => 'Warkim', 'umur' => 30]);` |
| **YourModel::update()** | boolean | update data. terpadat 2 argumen pada update(), argumen pertama adalah array assosiatif data yang akan diupdate, sedangkan argumen kedua adalah string/integer ID record. Contoh: `User::update(['nama' => 'Warkim Alhakim', 'umur' => 31], 1);` |
| **YourModel::delete()** | boolean | hapus data. hanya 1 argumen dapat diisi dengan string/integer ID record yang akan dihapus. Contoh: `User::delete(1);` |

## BLADE TEMPLATE

Blade template sepenuhnya siap digunakan, aktifkan melalui file **`.env`** dengan merubah nilai `BLADE_TEMPLATE=`**`true`** setelah itu kamu bisa menambahkan file view dengan ekstensi .blade.php contoh home.blade.php
Contoh method create yang akan menggunakan view (path: `app/views/users/create.blade.php`):

    public  function  create()
    {
        return  view('users.create', [
    	    'user' => User::all(),
        ]);
    }

Penggunaan Blade melalui callback view di route `routes/web.php`:

    use Warkim\core\Route;

    Route::get('/home', fn($nama) => view('home'));

Namun jika kamu **tidak ingin menggunakan Blade**, kamu tidak perlu merubah **BLADE_TEMPLATE** dan biarkan isinya **false**.

## SESSION

Kamu dapat menggunakan Session untuk menyimpan data sementara atau untuk flash message. Gunakan Session dengan cara:
| Class Method | Helper | Response | Penjelasan |
|--|--|--|--|
| Session::all() | session()->all() | array | Menampilkan semua session `sesi`|
| Session::get($key) | session()->get($key) | string | Cek dan Menampilkan session value dari key $key. $key type string |
| - | session($key) | boolean| Cek session key |
| Session::put($key, $value) | session()->put($key, $value) | boolean | Menyimpan session |
| Session::forget($key) | session()->forget($key) | null | Menghapus session dengan key tersebut |
| Session::flush() | session()->clear() | null | Menghapus semua session |

## SESSION FLASH

Kadang kita membutuhkan pesan konfirmasi baik success maupun error dari action yang kita terapkan, oleh karena itu kamu bisa menggunakan flash() untuk menangani hal tersebut. Hal ini juga akan menghapus key yang tersimpan didalam Session karena setelah flash dijalankan maka akan menghapus key value dari Session (sekali pakai). Penggunaan:

    flash("success'" "Berhasil Menyimpan Data");

**Penggunaan di Controller, contoh konfirmasi simpan data.**

    $simpan = User::create(['nama' => 'Warkim', 'umur' => 23]);
    ($simpan) ? flash('success', 'User berhasil disimpan') : flash('error', 'User gagal disimpan');
    return redirect()->back();

**Cek di View (dengan Blade Directive)**
Contoh dibawah menggunakan Blade directive, selain itu kamu juga bisa menggunakan PHP native (jika tidak mengaktifkan BLADE_TEMPLATE) untuk pengecekan session.

    @if(session('success'))
    <div  class="alert alert-success">
    {{ flash('success') }}
    </div>
    @elseif(session('error'))
    <div  class="alert alert-danger">
    {{ flash('error') }}
    </div>
    @endif

## LAYOUTING DENGAN BLADE

Aktifkan Blade untuk tugas ini! [Lihat](https://github.com/warkimalhakim/php-mvc-starter?tab=readme-ov-file#blade-template)
Untuk membuat layout kamu perlu mengaktifkan Blade terlebih dahulu, anggap saja sudah diaktifkan. Buat layout dengan membuat folder bernama **layouts** didalam folder **views** (`app/views/layouts`). Tambahkan satu atau beberapa file dengan nama apa saja, contoh nama: _guest, dashboard, public_ diakhiri dengan ekstensi file .blade.php sehingga menjadi `app/views/layouts/guest.blade.php`.
Selanjutnya isi file tersebut dengan layout yang sudah dibuat custom atau saya contohkan dengan menggunakan _HTML_ dan _Bootstrap_ seperti berikut:

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title', 'Guest Layout')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            crossorigin="anonymous">

        @stack('css')
    </head>

    <body>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('/') }}">PHP MVC Starter</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            @component('components.Button', ['url' => route('/'), 'class'
                            => 'nav-link'])
                            Home
                            @endcomponent
                        </li>
                        <li class="nav-item">
                            @component('components.Button', ['url' => route('users'), 'class'
                            => 'nav-link'])
                            Users
                            @endcomponent
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        {{-- Content --}}
        <div class="container">
            <div class="row">
                <div class="col pt-3">
                    @yield('content')
                </div>
            </div>
        </div>



        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
        </script>

        {{-- Stack Javascript --}}
        @stack('script')
    </body>

    </html>

Pada layout diatas dapat ditemukan Blade directive sebagai berikut:
| Directive | Penjelasan |
|--|--|
| **@yield**('title', 'Guest Layout') | untuk Judul dengan isi default 'Guest Layout' |
| **@yield**('content') | untuk menampung/render content |
| **@stack**('css') | untuk menampung/render css |
| **@stack**('script') | untuk menampung/render javascript |
Contoh penggunaan **@yield**('content') di file View seperti berikut:

    @extends('layouts.guest') // Menggunakan layout guest
    @section('title', 'Judul Halaman') // Mengisi judul (title bar)
    @section('content') // Mengisi main content
        <h2>Hello World</h2>

    @push('script') // Mengisi script atau Javascript lainnya
    <script>
    	console.log('HALO SAYA JAVASCRIPT')
    </script>
    @endpush

    @endsection
