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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_keyword = $_POST['search_keyword'] ?? '';

        if (!empty($search_keyword)) {
            $search_keyword = "%$search_keyword%";
            $sql_filtered_users = "SELECT * FROM users WHERE kullanici_adi LIKE :keyword OR ad_soyad LIKE :keyword";
            $stmt_filtered_users = $pdo->prepare($sql_filtered_users);
            $stmt_filtered_users->bindParam(':keyword', $search_keyword, PDO::PARAM_STR);
            $stmt_filtered_users->execute();
            $filteredUsersData = $stmt_filtered_users->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    $sql_all_users = "SELECT * FROM users";
    $stmt_all_users = $pdo->query($sql_all_users);
    $allUsersData = $stmt_all_users->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kullanıcılar</title>
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
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <form action="kullanicilar.php" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Kullanıcı adı veya Ad Soyad arayın" name="search_keyword">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">Ara</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <?php
        $usersData = isset($filteredUsersData) ? $filteredUsersData : $allUsersData;
        foreach ($usersData as $user) : ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <div style="width: 100px; height: 100px; background-color: #007bff; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 36px; border-radius: 50%; margin: 0 auto;">
                            <?php echo strtoupper(substr($user['kullanici_adi'], 0, 1)); ?>
                        </div>
                        <h6 class="card-subtitle mb-2 mt-2"><?php echo $user['kullanici_adi']; ?></h6>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $user['ad_soyad']; ?></h6>
                        <a href="kullanici.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">Detaylar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
    <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
