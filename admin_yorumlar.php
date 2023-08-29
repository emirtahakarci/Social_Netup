<?php
session_start();

// Oturum kontrolü yapalım, eğer admin girişi yapılmamışsa admin.php'ye yönlendir
if (!isset($_SESSION['admin_username'])) {
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

    // Silme işlemi
    if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Yorumu sil
        $sql_delete = "DELETE FROM comment WHERE id = :delete_id";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
        if ($stmt_delete->execute()) {
            header('Location: admin_yorumlar.php');
            exit;
        } else {
            echo "Yorum silinirken hata oluştu: " . $stmt_delete->errorInfo()[2];
        }
    }

    // Pagination için ayarlar
    $items_per_page = 10;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // SQL sorgusunu hazırlayın ve yorumları sorgulayın
    $sql = "SELECT * FROM comment ORDER BY id DESC LIMIT :offset, :items_per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();

    // Yorumları çekin
    $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Yorumlar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color: #222; color: #fff;">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Paneli</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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
                    <a class="nav-link" href="admin.php?logout=1">Çıkış Yap</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h2 class="mb-4">Admin Yorumlar</h2>
            <?php if (count($commentsData) > 0) : ?>
                <table class="table table-dark table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı Adı</th>
                            <th>Yorum</th>
                            <th>Resim</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commentsData as $comment) : ?>
                            <tr>
                                <td><?php echo $comment['id']; ?></td>
                                <td><?php echo $comment['kullanici_adi']; ?></td>
                                <td><?php echo $comment['mesaj']; ?></td>
                                <td>
                                    <?php if (!empty($comment['resim'])) : ?>
                                        <img src="<?php echo $comment['resim']; ?>" alt="Yorum Resmi" style="max-width: 100px; height: auto;">
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="admin_yorumlar.php?delete_id=<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Henüz yorum bulunmuyor.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
