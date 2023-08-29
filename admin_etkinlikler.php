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

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Veritabanı bağlantı bilgilerini alın
    $servername = "localhost"; // Veritabanı sunucu adı
    $db_username = "root"; // Veritabanı kullanıcı adı
    $db_password = ""; // Veritabanı şifre
    $dbname = "social_netup"; // Veritabanı adı

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Etkinliği silme SQL sorgusu
        $sql_delete = "DELETE FROM etkinlikler WHERE id = :delete_id";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
        if ($stmt_delete->execute()) {
            header('Location: admin_etkinlikler.php');
            exit;
        } else {
            echo "Etkinlik silinirken hata oluştu: " . $stmt_delete->errorInfo()[2];
        }
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}

try {
    // Veritabanı bağlantı bilgilerini alın
  $servername = "sql307.infinityfree.com"; // Veritabanı sunucu adı
    $db_username = "if0_34866917"; // Veritabanı kullanıcı adı
    $db_password = "d3KpwSG6JY0A3hE"; // Veritabanı şifre
    $dbname = "if0_34866917_social_netup"; // Veritabanı adı

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Etkinlik verilerini veritabanından alalım
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $sql_etkinlikler = "SELECT * FROM etkinlikler LIMIT :offset, :perPage";
    $stmt_etkinlikler = $pdo->prepare($sql_etkinlikler);
    $stmt_etkinlikler->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt_etkinlikler->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt_etkinlikler->execute();

    $etkinliklerData = $stmt_etkinlikler->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Etkinlikler</title>
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
    <h2>Etkinlikler</h2>
    <!-- Etkinlik verilerini göster -->
    <?php if (count($etkinliklerData) > 0) : ?>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullancı</th>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etkinliklerData as $etkinlik) : ?>
                    <tr>
                        <td><?php echo $etkinlik['id']; ?></td>
                        <td><?php echo $etkinlik['kullanici']; ?></td>
                        <td><?php echo $etkinlik['etkinlik_ismi']; ?></td>
                        <td><?php echo $etkinlik['etkinlik_tarihi']; ?></td>
                        <td><a href="admin_etkinlikler.php?delete_id=<?php echo $etkinlik['id']; ?>" class="btn btn-danger btn-sm">Sil</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Etkinlik bulunamadı.</p>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
