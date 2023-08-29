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

    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        $sql_user = "SELECT * FROM users WHERE id = :user_id";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_user->execute();

        $user_info = $stmt_user->fetch(PDO::FETCH_ASSOC);

        if (!$user_info) {
            echo "Kullanıcı bulunamadı.";
            exit;
        }

        $sql_comments = "SELECT * FROM comment WHERE kullanici_adi = :kullanici_adi ORDER BY id DESC";
        $stmt_comments = $pdo->prepare($sql_comments);
        $stmt_comments->bindParam(':kullanici_adi', $user_info['kullanici_adi'], PDO::PARAM_STR);
        $stmt_comments->execute();

        $commentsData = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

        $sql_events = "SELECT * FROM etkinlikler WHERE kullanici = :kullanici_adi ORDER BY id DESC";
        $stmt_events = $pdo->prepare($sql_events);
        $stmt_events->bindParam(':kullanici_adi', $user_info['kullanici_adi'], PDO::PARAM_STR);
        $stmt_events->execute();

        $eventsData = $stmt_events->fetchAll(PDO::FETCH_ASSOC);

    } else {
        echo "Kullanıcı ID'si belirtilmedi.";
        exit;
    }
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user_info['kullanici_adi']; ?> Profili</title>
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
<nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <a class="nav-link" href="yorum.php">Yorum Yap</a>
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
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div style="width: 100px; height: 100px; background-color: #007bff; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 36px; border-radius: 50%; margin: 0 auto;">
                        <?php echo strtoupper(substr($user_info['kullanici_adi'], 0, 1)); ?>
                    </div>
                    <h6 class="card-subtitle mb-2 mt-2"><?php echo $user_info['kullanici_adi']; ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $user_info['ad_soyad']; ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex mb-4">
                <button class="btn btn-primary mr-2" onclick="showComments()">Kullanıcı Yorumları</button>
                <button class="btn btn-primary" onclick="showEvents()">Kullanıcı Etkinlikleri</button>
            </div>
            <div id="commentsSection">
                <?php if (count($commentsData) > 0) : ?>
                    <h4>Kullanıcının Yorumları</h4>
                    <?php
                    $commentChunks = array_chunk($commentsData, 5);
                    $commentPage = $_GET['comment_page'] ?? 1;
                    $commentPageData = isset($commentChunks[$commentPage - 1]) ? $commentChunks[$commentPage - 1] : [];
                    foreach ($commentPageData as $comment) : ?>
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

                    <!-- Sayfalama -->
                    <nav aria-label="Yorum Sayfaları">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= count($commentChunks); $i++) : ?>
                                <li class="page-item <?php echo ($i == $commentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?id=<?php echo $user_id; ?>&comment_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php else : ?>
                    <p>Kullanıcının henüz yorumu yok.</p>
                <?php endif; ?>
            </div>
            <div id="eventsSection" style="display: none;">
                <?php if (count($eventsData) > 0) : ?>
                    <h4>Kullanıcının Etkinlikleri</h4>
                    <?php
                    $eventChunks = array_chunk($eventsData, 5);
                    $eventPage = $_GET['event_page'] ?? 1;
                    $eventPageData = isset($eventChunks[$eventPage - 1]) ? $eventChunks[$eventPage - 1] : [];
                    foreach ($eventPageData as $event) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $event['etkinlik_ismi']; ?></h5>
                                <p class="card-text"><?php echo $event['icerik']; ?></p>
                                <?php if (!empty($event['resim'])) : ?>
                                    <img src="<?php echo $event['resim']; ?>" class="card-img-top" alt="Etkinlik Resmi" style="max-width: 100%; height: auto;">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Sayfalama -->
                    <nav aria-label="Etkinlik Sayfaları">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= count($eventChunks); $i++) : ?>
                                <li class="page-item <?php echo ($i == $eventPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?id=<?php echo $user_id; ?>&event_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php else : ?>
                    <p>Kullanıcının henüz etkinliği yok.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
    <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
</footer>

<!-- Bootstrap JavaScript ve jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function showComments() {
        document.getElementById("commentsSection").style.display = "block";
        document.getElementById("eventsSection").style.display = "none";
    }

    function showEvents() {
        document.getElementById("eventsSection").style.display = "block";
        document.getElementById("commentsSection").style.display = "none";
    }
</script>

</body>
</html>

