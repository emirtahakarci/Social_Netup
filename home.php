<!DOCTYPE html>
<html>

<head>
    <title>Sosyal Medya Anasayfası</title>
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
            background-color: #dedede;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .comment-box {
            background-color: #dedede;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .comment-icon {
            font-size: 24px;
            color: #007bff;
            margin-right: 10px;
        }

        .translate-btn {
            position: absolute;
            bottom: 5px;
            right: 10px;
            font-size: 14px;
            cursor: pointer;
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
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }

        .add-comment-btn:hover {
            background-color: #0056b3;
        }

        .translate-btn {
            /* ... Diğer stiller ... */
            font-size: 20px;
            /* İkon boyutunu ayarlayabilirsiniz */
        }

        /* Arka plan ve metin rengi */
        body {
            background-color: #333333;
            /* Arka plan rengi */
            color: #625d5d;
            /* Metin rengi */
        }

        /* Navbar arka planı */
        .navbar {
            background-color: #dedede;
            /* Navbar arka plan rengi */
        }

        /* Navbar yazı rengi */
        .navbar-brand {
            color: #f0f0f0;
            /* Navbar yazı rengi */
        }

        /* ... Daha fazla stil tanımlaması ... */

        /* Ekleme butonu rengi */
        .add-comment-btn {
            background-color: #007bff;
            /* Ekleme butonu arka plan rengi */
            color: #ffffff;
            /* Ekleme butonu yazı rengi */
        }

        /* Ekleme butonu hover rengi */
        .add-comment-btn:hover {
            background-color: #0056b3;
            /* Ekleme butonu hover arka plan rengi */
        }

        /* Çeviri butonu rengi */
        .translate-btn {
            font-size: 20px;
            color: #007bff;
            /* Çeviri butonu yazı rengi */
        }

        /* Çeviri butonu hover rengi */
        .translate-btn:hover {
            color: #0056b3;
            /* Çeviri butonu hover yazı rengi */
        }
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
                    <h2>Anasayfa</h2>
                    <p>
                        Hoş geldiniz! Sosyal medya platformumuzda neler oluyor?
                    </p>
                    <p>
                        Bu alanda paylaşımları görebilir ve diğer kullanıcılarla etkileşimde bulunabilirsiniz.
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

                        $comments_per_page = 5;
                        $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $offset = ($current_page - 1) * $comments_per_page;

                        $sql = "SELECT kullanici_adi, mesaj, resim FROM comment ORDER BY id DESC LIMIT :limit OFFSET :offset";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':limit', $comments_per_page, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $stmt->execute();
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($comments) {
                            foreach ($comments as $comment) {
                                echo '<div class="comment-box">';
                                echo '<div class="comment-icon"><i class="fas fa-comment"></i></div>';
                                echo '<div class="comment-content">';
                                echo '<h5>' . $comment['kullanici_adi'] . '</h5>';
                                echo '<p>' . $comment['mesaj'] . '</p>';
                                if (!empty($comment['resim'])) {
                                    echo '<img src="' . $comment['resim'] . '" alt="Yorum Resmi" style="max-width: 100%; height: auto;">';
                                }
                                echo '</div>';
                                echo '<button class="translate-btn" data-toggle="modal" data-target="#translation-modal" data-comment="' . $comment['mesaj'] . '"><i class="fas fa-language"></i></button>';
                                echo '</div>';
                            }

                            $sql_count = "SELECT COUNT(*) as count FROM comment";
                            $stmt_count = $pdo->prepare($sql_count);
                            $stmt_count->execute();
                            $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
                            $total_comments = $row_count['count'];
                            $total_pages = ceil($total_comments / $comments_per_page);

                                              echo  '<nav aria-label="Sayfalar">';
                           echo ' <ul class="pagination justify-content-center">';
                                 for ($i = 1; $i <= $total_pages; $i++) {
                                    $active_class = $i === $current_page ? 'active' : '';
                                    echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                } 
                            echo '</ul>';
                       echo '</nav>';
                            
                        } else {
                            echo '<p>Henüz hiç yorum yapılmamış.</p>';
                        }
                    } catch (PDOException $e) {
                        echo "Veritabanı hatası: " . $e->getMessage();
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Çeviri Modal -->
    <div class="modal fade" id="translation-modal" tabindex="-1" role="dialog" aria-labelledby="translation-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="translation-modal-label">Çeviri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="translated-comment"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Yorum yapma butonu -->
    <a href="yorum.php" class="add-comment-btn">
        <i class="fas fa-plus"></i>
    </a>
    <footer style="background-color: #222; color: #fff; padding: 20px; text-align: center;">
        <p>&copy; 2023 Emir Taha Karcı. Tüm hakları saklıdır.</p>
    </footer>

    <!-- Font Awesome JS ve Bootstrap JavaScript ve jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="custom.js"></script>
</body>

</html>