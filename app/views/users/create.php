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