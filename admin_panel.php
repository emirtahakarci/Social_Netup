<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}
 // Veritabanı bağlantı bilgilerini alın
 $servername = "localhost"; // Veritabanı sunucu adı
 $db_username = "root"; // Veritabanı kullanıcı adı
 $db_password = ""; // Veritabanı şifre
 $dbname = "social_netup"; // Veritabanı adı

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        $sql_delete = "DELETE FROM users WHERE id = :delete_id";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
        if ($stmt_delete->execute()) {
            header('Location: admin_panel.php');
            exit;
        } else {
            echo "Kullanıcı silinirken hata oluştu: " . $stmt_delete->errorInfo()[2];
        }
    }

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $sql_users = "SELECT * FROM users LIMIT :offset, :perPage";
    $stmt_users = $pdo->prepare($sql_users);
    $stmt_users->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt_users->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt_users->execute();

    $usersData = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #343a40; /* Karanlık arkaplan */
            color: #fff;
        }
        /* Diğer stiller... */
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Kişiler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_yorumlar.php">Yorumlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_etkinlikler.php">Etkinlikler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php?logout=true">Çıkış</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Kişiler</h2>
    <!-- Kullanıcı verilerini göster -->
    <?php if (count($usersData) > 0) : ?>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Ad Soyad</th>
                    <th>Email</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usersData as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['kullanici_adi']; ?></td>
                        <td><?php echo $user['ad_soyad']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><a href="admin_panel.php?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Sil</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Kullanıcı bulunamadı.</p>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>