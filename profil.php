<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
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

    $username = $_SESSION['username'];
    $sql_user = "SELECT * FROM users WHERE kullanici_adi = :username";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_user->execute();

    $userData = null;
    if ($stmt_user->rowCount() === 1) {
        $userData = $stmt_user->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ad_soyad = $_POST['ad_soyad'] ?? '';
        $email = $_POST['email'] ?? '';
        $country = $_POST['country'] ?? '';
        $new_username = $_POST['new_username'] ?? '';

        $check_username_sql = "SELECT * FROM users WHERE kullanici_adi = :new_username";
        $stmt_check_username = $pdo->prepare($check_username_sql);
        $stmt_check_username->bindParam(':new_username', $new_username, PDO::PARAM_STR);
        $stmt_check_username->execute();

        if ($stmt_check_username->rowCount() > 0) {
            echo '<div class="alert alert-danger">Bu kullanıcı adı zaten mevcut.</div>';
        } else {
            $pdo->beginTransaction();

            $update_comment_username_sql = "UPDATE comment SET kullanici_adi = :new_username WHERE kullanici_adi = :username";
            $stmt_update_comment_username = $pdo->prepare($update_comment_username_sql);
            $stmt_update_comment_username->bindParam(':new_username', $new_username, PDO::PARAM_STR);
            $stmt_update_comment_username->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt_update_comment_username->execute();

            $update_sql = "UPDATE users SET ad_soyad = :ad_soyad, email = :email, country = :country, kullanici_adi = :new_username WHERE kullanici_adi = :username";
            $stmt_update = $pdo->prepare($update_sql);
            $stmt_update->bindParam(':ad_soyad', $ad_soyad, PDO::PARAM_STR);
            $stmt_update->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_update->bindParam(':country', $country, PDO::PARAM_STR);
            $stmt_update->bindParam(':new_username', $new_username, PDO::PARAM_STR);
            $stmt_update->bindParam(':username', $username, PDO::PARAM_STR);

            if ($stmt_update->execute()) {
                $_SESSION['username'] = $new_username;
                $username = $new_username;
                $pdo->commit();
            } else {
                $pdo->rollBack();
                echo '<div class="alert alert-danger">Profil güncellenirken hata oluştu.</div>';
            }
        }
    }

    $sql_comments = "SELECT * FROM comment WHERE kullanici_adi = :username ORDER BY id DESC";
    $stmt_comments = $pdo->prepare($sql_comments);
    $stmt_comments->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_comments->execute();

    $commentsData = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Sayfası</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        .profile-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .profile-card h3 {
            margin-bottom: 10px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="profile-card text-center">
                    <div class="profile-img mb-3" style="background-color: #007bff; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 60px;">
                        <span><?php echo strtoupper(substr($userData['kullanici_adi'], 0, 1)); ?></span>
                    </div>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="ad_soyad">Ad Soyad:</label>
                            <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" value="<?php echo $userData['ad_soyad']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="country">Yaşadığı Ülke:</label>
                            <input type="text" class="form-control" id="country" name="country" value="<?php echo $userData['country']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="new_username">Yeni Kullanıcı Adı:</label>
                            <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $userData['kullanici_adi']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bilgileri Güncelle</button>
                    </form>
                </div>

                <?php if (count($commentsData) > 0) : ?>
                    <h4>Yorumlar</h4>
                    <?php foreach ($commentsData as $comment) : ?>
                        <div class="comment-box">
                            <div class="comment-icon"><i class="fas fa-comment"></i></div>
                            <div class="comment-content">
                                <h5><?php echo $userData['kullanici_adi']; ?></h5>
                                <p><?php echo $comment['mesaj']; ?></p>
                                <?php if (!empty($comment['resim'])) : ?>
                                    <img src="<?php echo $comment['resim']; ?>" alt="Yorum Resmi" style="max-width: 100%; height: auto;">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Henüz hiç yorum yapmamış.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
    <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
