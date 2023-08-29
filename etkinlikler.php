<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Etkinlikler</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .banner {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .logo {
            display: block;
            margin: auto;
            width: 150px;
            padding: 10px;
        }

        .navbar {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .comment-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .comment-icon {
            font-size: 24px;
            color: #007bff;
            margin-right: 10px;
        }

        .add-comment-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            line-height: 60px;
            cursor: pointer;
        }
    </style>
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
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Buton hover rengi */
        .btn-primary:hover {
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
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="content">
                    <h2>Etkinlikler</h2>
                    <p>
                        Bu alanda etkinlikleri görebilir ve diğer kullanıcıların etkinliklerine katılabilirsiniz.
                    </p>

                    <?php
                    // Veritabanı bağlantı bilgilerini alın
                    $servername = "localhost"; // Veritabanı sunucu adı
                    $db_username = "root"; // Veritabanı kullanıcı adı
                    $db_password = ""; // Veritabanı şifre
                    $dbname = "social_netup"; // Veritabanı adı
                    
                    try {
                        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "SELECT * FROM etkinlikler";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $etkinlikler = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($etkinlikler) {
                            foreach ($etkinlikler as $row) {
                                echo '<div class="comment-box">';
                                echo '<div class="comment-icon"><i class="fas fa-calendar-alt"></i></div>';
                                echo '<div class="comment-content">';
                                echo '<h5>' . $row['kullanici'] . '</h5>';
                                echo '<h4>' . $row['etkinlik_ismi'] . '</h4>';
                                echo '<p>Tarih: ' . $row['etkinlik_tarihi'] . '</p>';
                                echo '<p>' . $row['icerik'] . '</p>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Henüz hiç etkinlik eklenmemiş.</p>';
                        }
                    } catch (PDOException $e) {
                        echo "Veritabanı hatası: " . $e->getMessage();
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Yorum yapma butonu -->
    <a href="etkinlikekle.php" class="add-comment-btn">
        <i class="fas fa-plus"></i>
    </a>
    <footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
        <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
    </footer>

    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>