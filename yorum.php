<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$kullanici_adi = $_SESSION['username'];

 // Veritabanı bağlantı bilgilerini alın
 $servername = "localhost"; // Veritabanı sunucu adı
 $db_username = "root"; // Veritabanı kullanıcı adı
 $db_password = ""; // Veritabanı şifre
 $dbname = "social_netup"; // Veritabanı adı

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mesaj = $_POST['mesaj'] ?? '';
        $resim = $_POST['resim'] ?? '';

        $sql = "INSERT INTO comment (kullanici_adi, mesaj, resim) VALUES (:kullanici_adi, :mesaj, :resim)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $stmt->bindParam(':mesaj', $mesaj);
        $stmt->bindParam(':resim', $resim);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Yorum başarıyla eklendi.</div>';
        } else {
            echo '<div class="alert alert-danger">Yorum eklenirken hata oluştu: ' . $stmt->errorInfo()[2] . '</div>';
        }
    }

    $sql_comments = "SELECT * FROM comment WHERE kullanici_adi = :kullanici_adi ORDER BY id DESC";
    $stmt_comments = $pdo->prepare($sql_comments);
    $stmt_comments->bindParam(':kullanici_adi', $kullanici_adi);
    $stmt_comments->execute();

    $commentsData = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yorum Yap</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    /* Arka plan ve metin rengi */
    body {
        background-color: #f0f0f0;
        color: #333333;
    }

    /* Navbar arka planı */
    .navbar {
        background-color: #f8f9fa;
    }

    /* Navbar yazı rengi */
    .navbar-brand {
        color: #333333;
    }

    /* Kart arka planı */
    .card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    /* Kart başlık rengi */
    .card-header {
        background-color: #007bff;
        color: #ffffff;
    }

    /* Buton rengi */
    .btn-dark {
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Buton hover rengi */
    .btn-dark:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Form arka planı */
    .form-control {
        background-color: #ffffff;
        color: #333333;
        border-color: #ced4da;
    }

    /* ... Daha fazla stil tanımlaması ... */
</style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">Sosyal Medya</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="etkinlikler.php">Etkinlikler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kullanicilar.php">Kullanıcılar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Çıkış Yap</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Yorum Yap</h4>
                    </div>
                    <div class="card-body">
                        <form action="yorum.php" method="post">
                            <div class="form-group">
                                <label for="mesaj">Yorumunuz:</label>
                                <textarea class="form-control" id="mesaj" name="mesaj" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="resim">Resim (isteğe bağlı):</label>
                                <input type="file" class="form-control" id="resim" name="resim">
                            </div>
                            <button type="submit" class="btn btn-dark bg-dark">Yorum Yap</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php if (count($commentsData) > 0) : ?>
            <div class="row mt-5">
                <div class="col-md-6">
                    <h4>Kullanıcının Yorumları</h4>
                    <?php foreach ($commentsData as $comment) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $comment['kullanici_adi']; ?></h5>
                                <p class="card-text"><?php echo $comment['mesaj']; ?></p>
                                <?php if (!empty($comment['resim'])) : ?>
                                    <img src="<?php echo $comment['resim']; ?>" class="card-img-top" alt="Yorum Resmi" style="max-width: 100%; height: auto;">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
    <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
