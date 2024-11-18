<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>

<body>


    <h1>Daftar Users</h1>

    <a href="<?= route('users.create'); ?>">Tambah User</a>

    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Nama</td>
                <td>Umur</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->nama ?></td>
                    <td><?= $user->umur ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</body>

</html>