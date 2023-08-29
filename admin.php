<?php
session_start();

// Eğer zaten giriş yapılmışsa admin_panel.php'ye yönlendir
if (isset($_SESSION['admin_username'])) {
    header('Location: admin_panel.php');
    exit;
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kullanıcı adı ve şifreyi al
    $admin_username = $_POST['admin_username'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';

    // Kullanıcı adı ve şifreyi kontrol et (örnekte "admin" kullanıcı adı ve "12345" şifresi varsayıldı)
    if ($admin_username === 'admin' && $admin_password === '12345') {
        // Giriş başarılıysa oturum başlat ve admin_panel.php'ye yönlendir
        $_SESSION['admin_username'] = $admin_username;
        header('Location: admin_panel.php');
        exit;
    } else {
        // Hatalı giriş durumunda mesaj göster
        $error_message = "Hatalı kullanıcı adı veya şifre.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Girişi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Admin Girişi</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)) : ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form action="admin.php" method="post">
                        <div class="form-group">
                            <label for="admin_username">Kullanıcı Adı:</label>
                            <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                        </div>
                        <div class="form-group">
                            <label for="admin_password">Şifre:</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Giriş Yap</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
