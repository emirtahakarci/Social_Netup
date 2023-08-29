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
        $etkinlik_ismi = $_POST['etkinlik_ismi'] ?? '';
        $etkinlik_tarihi = $_POST['etkinlik_tarihi'] ?? '';
        $icerik = $_POST['icerik'] ?? '';

        $insert_sql = "INSERT INTO etkinlikler (kullanici, etkinlik_ismi, etkinlik_tarihi, icerik) VALUES (:kullanici, :etkinlik_ismi, :etkinlik_tarihi, :icerik)";

        $stmt = $pdo->prepare($insert_sql);
        $stmt->bindParam(':kullanici', $kullanici_adi);
        $stmt->bindParam(':etkinlik_ismi', $etkinlik_ismi);
        $stmt->bindParam(':etkinlik_tarihi', $etkinlik_tarihi);
        $stmt->bindParam(':icerik', $icerik);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Etkinlik başarıyla eklendi.</div>';
        } else {
            if ($stmt->errorCode() === '23000') {
                echo '<div class="alert alert-danger">Bu etkinlik zaten mevcut.</div>';
            } else {
                echo '<div class="alert alert-danger">Etkinlik eklenirken hata oluştu.</div>';
            }
        }
    }
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Etkinlik Ekle</title>
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
                        <h4 class="mb-0">Etkinlik Ekle</h4>
                    </div>
                    <div class="card-body">
                        <form action="etkinlikekle.php" method="post">
                            <div class="form-group">
                                <label for="etkinlik_ismi">Etkinlik İsmi:</label>
                                <input type="text" class="form-control" id="etkinlik_ismi" name="etkinlik_ismi" required>
                            </div>
                            <div class="form-group">
                                <label for="etkinlik_tarihi">Etkinlik Tarihi:</label>
                                <input type="date" class="form-control" id="etkinlik_tarihi" name="etkinlik_tarihi" required>
                            </div>
                            <div class="form-group">
                                <label for="icerik">İçerik:</label>
                                <textarea class="form-control" id="icerik" name="icerik" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark bg-dark">Etkinlik Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
    <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
