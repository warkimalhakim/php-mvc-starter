# php-mvc-starter

**Project starter dengan konsep MVC untuk bahasa pemrograman PHP**. MVC adalah singkatan dari Model-View-Controller. Ini adalah sebuah pola arsitektur yang populer digunakan dalam pengembangan aplikasi web, termasuk PHP.
Konsep MVC ini diinisialisasi sesuai dengan versi Warkim, digunakan pribadi untuk memulai project (kosong) dengan konsep modern _Object Orientation Programming (OOP)_ dan _tanpa menggunakan framework_.
Jika kamu menemukan repositori ini dan ingin menggunakannya, dipersilahkan untuk digunakan secara public namun tidak boleh merusak atribut.
Jika kamu berminat mengembangkan source ini juga dengan senang hati dipersilahkan.

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

Route berada pada folder routes atau "routes/web.php", contoh penggunaannya seperti berikut:

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
    echo  'Nama Aplikasi: '  .  **config('APP_NAME')**;
    ?>
    <h2>Halaman Kontak</h2>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus, sed. A laborum quidem porro consectetur vel veritatis mollitia deleniti ipsam unde sunt praesentium, nisi qui quasi dolore laboriosam exercitationem iusto quod.</p>

## CEK URL DAN URL QUERY PARAMETER (ACTIVE ROUTE)

- Jika dibutuhkan untuk mengecek route yang saat ini bisa menggunakan **`Route::is()`**, mengembalikan tipe data **_Array_**
- Jika ingin mengecek/membandingkan route saat ini dengan yang dikehendaki, maka bisa menggunakan **`Route::is('/nama-route-yang-dikehendaki')`**, mengambalikan tipe data **_Boolean_**
- Jika ingin mengecek query parameter apa saja yang sedang dibuka atau mengambil nilai query tersebut bisa menggunakan **`Route::query()`** mengembalikan **_Array_**, **`Route::query('nama-key')`** mengembalikan **_String_** atau **_NULL_**

## MENGGUNAKAN ROUTE **`route()`** UNTUK ACTION FORMULIR

Daripada menggunakan action url static, sekarang sudah ditambahkan method route() dengan meberikan 1 argumen string. Contoh saya mempunyai Route di **`routes/web.php`**:

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
