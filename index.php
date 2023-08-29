<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 // Veritabanı bağlantı bilgilerini alın
 $servername = "localhost"; // Veritabanı sunucu adı
 $db_username = "root"; // Veritabanı kullanıcı adı
 $db_password = ""; // Veritabanı şifre
 $dbname = "social_netup"; // Veritabanı adı
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($username) || empty($password)) {
        header('Location: index.php?error=empty');
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        

        $sql = "SELECT * FROM users WHERE kullanici_adi = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
         $salt = '2wFkd#$*!f9Fk'; 
         $hashed_password = sha1($password . $salt);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("burda");

        if ($hashed_password == $row['kullanici_sifre']) {
            error_log("şifre burda");
            $_SESSION['username'] = $row['kullanici_adi'];
            $_SESSION['ad_soyad'] = $row['ad_soyad'];
            header('Location: home.php');
            exit;
        } else {
            header('Location: index.php?error=invalid');
            exit;
        }
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Giriş Ekranı</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #121212; /* Dark background color */
            color: #FFFFFF; /* Light text color */
        }

        .card {
            background-color: #1E1E1E; /* Dark card background color */
            border: 1px solid #333; /* Dark border color */
        }

        .card-header {
            background-color: #333; /* Dark card header background color */
            color: #FFFFFF; /* Light text color */
        }

        .form-control {
            background-color: #333; /* Dark input background color */
            color: #FFFFFF; /* Light text color */
            border: 1px solid #555; /* Dark border color */
        }

        .form-check-input:checked {
            background-color: #333; /* Dark checked checkbox background color */
        }

        .btn-primary {
            background-color: #007BFF; /* Blue primary button color */
            border-color: #007BFF; /* Blue primary button border color */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }

        .btn-outline-secondary {
            color: #FFF; /* Light text color for button */
            border-color: #555; /* Dark border color for button */
        }

        .btn-outline-secondary:hover {
            background-color: #333; /* Darker background on hover */
            border-color: #333;
        }

        .alert-danger {
            background-color: #721c24; /* Dark red alert background color */
            color: #FFF; /* Light text color for alert */
            border-color: #721c24; /* Dark red alert border color */
        }
    </style>
</head>

<body>
     <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Giriş Yap</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['error'])) {
                            $error = $_GET['error'];
                            if ($error == "invalid") {
                                echo '<div class="alert alert-danger">Kullanıcı adı veya şifre yanlış. Lütfen tekrar deneyin.</div>';
                            } elseif ($error == "empty") {
                                echo '<div class="alert alert-danger">Kullanıcı adı ve şifre alanlarını doldurun.</div>';
                            }
                        }
                        ?>
                        <form action="index.php" method="post">
                            <div class="form-group">
                                <label for="username">Kullanıcı Adı:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Şifre:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePasswordVisibility()">Şifreyi Göster</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Beni Hatırla</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Giriş Yap</button>
                            <a href="forgot_password.php" class="btn btn-link">Şifremi Unuttum</a>
                        </form>
                        <p class="mt-3">Kayıtlı değil misiniz? <a href="signup.php">Kayıt Ol</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>

</html>
