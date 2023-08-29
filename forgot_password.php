<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // E-posta adresini alın
    $email = $_POST["email"];

    // E-posta adresini veritabanında kontrol edin
    // Bu kısmı kendi veritabanı yapınıza göre uyarlamalısınız

    // Eğer kullanıcı bulunursa
    if ($user) {
        // Rastgele bir şifre oluşturun
        $newPassword = generateRandomPassword();

        // Şifreyi hashleyin ve güncelleyin
        // Bu kısmı kendi veritabanı yapınıza göre uyarlamalısınız

        // Şifre sıfırlama e-postasını gönderin
        $to = $email;
        $subject = "Şifre Sıfırlama Talebi";
        $message = "Merhaba, yeni şifreniz: $newPassword";
        $headers = "From: info@example.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.";
        } else {
            echo "E-posta gönderilirken bir hata oluştu.";
        }
    } else {
        echo "E-posta adresi bulunamadı.";
    }
}

// Rastgele şifre oluşturucu
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Şifre Sıfırlama</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container {
            margin-top: 100px;
        }

        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .reset-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .reset-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="form-container">
                <h2>Şifre Sıfırlama</h2>
                <p>Lütfen hesabınıza bağlı olan e-posta adresinizi girin. Yeni bir şifre sıfırlama bağlantısı alacaksınız.</p>
                <form method="post">
                    <div class="form-group">
                        <label for="email">E-posta Adresi:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="reset-btn">Şifre Sıfırlama E-postası Gönder</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
