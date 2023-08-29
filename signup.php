<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Kayıt Ol</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $username = $_POST['username'] ?? '';
                            $password = $_POST['password'] ?? '';
                            $confirm_password = $_POST['confirm_password'] ?? '';
                            $ad_soyad = $_POST['ad_soyad'] ?? '';
                            $email = $_POST['email'] ?? '';
                            $country = $_POST['country'] ?? '';
                            $phone = $_POST['phone'] ?? '';
                            $gender = $_POST['gender'] ?? '';
                            $age = $_POST['age'] ?? '';

                            $errors = array();

                            if (empty($username)) {
                                $errors[] = 'Kullanıcı adı alanı boş bırakılamaz.';
                            }
                            if (empty($password)) {
                                $errors[] = 'Şifre alanı boş bırakılamaz.';
                            }
                            if (empty($confirm_password)) {
                                $errors[] = 'Şifre doğrulama alanı boş bırakılamaz.';
                            }
                            if ($password !== $confirm_password) {
                                $errors[] = 'Şifre ve şifre doğrulama alanları eşleşmiyor.';
                            }
                            if (empty($ad_soyad)) {
                                $errors[] = 'Ad Soyad alanı boş bırakılamaz.';
                            }
                            if (empty($email)) {
                                $errors[] = 'Email alanı boş bırakılamaz.';
                            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = 'Geçerli bir email adresi girin.';
                            }
                            if (empty($country)) {
                                $errors[] = 'Yaşadığı Ülke seçimi yapmalısınız.';
                            }
                            if (empty($phone)) {
                                $errors[] = 'Telefon Numarası alanı boş bırakılamaz.';
                            }
                            if (empty($gender)) {
                                $errors[] = 'Cinsiyet seçimi yapmalısınız.';
                            }
                            if (empty($age)) {
                                $errors[] = 'Yaş alanı boş bırakılamaz.';
                            } elseif ($age < 18) {
                                $errors[] = 'Kayıt olmak için yaşınız 18 yaşından büyük olmalıdır.';
                            }

                            if (empty($errors)) {
                                // Veritabanı bağlantı bilgilerini alın
    $servername = "localhost"; // Veritabanı sunucu adı
    $db_username = "root"; // Veritabanı kullanıcı adı
    $db_password = ""; // Veritabanı şifre
    $dbname = "social_netup"; // Veritabanı adı

                                try {
                                    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $salt = '2wFkd#$*!f9Fk';
                                    $hashed_password = sha1($password . $salt);

                                    $sql = "INSERT INTO users (kullanici_adi, Kullanici_sifre, ad_soyad, email, country, phone, gender, age) 
                                            VALUES (:username, :hashed_password, :ad_soyad, :email, :country, :phone, :gender, :age)";

                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':username', $username);
                                    $stmt->bindParam(':hashed_password', $hashed_password);
                                    $stmt->bindParam(':ad_soyad', $ad_soyad);
                                    $stmt->bindParam(':email', $email);
                                    $stmt->bindParam(':country', $country);
                                    $stmt->bindParam(':phone', $phone);
                                    $stmt->bindParam(':gender', $gender);
                                    $stmt->bindParam(':age', $age);

                                    if ($stmt->execute()) {
                                        echo '<div class="alert alert-success">Kayıt başarıyla tamamlandı.</div>';
                                        header('Location: index.php'); 
                                    } else {
                                        echo '<div class="alert alert-danger">Kayıt sırasında hata oluştu.</div>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<div class="alert alert-danger">Veritabanı hatası: ' . $e->getMessage() . '</div>';
                                }
                            } else {
                                foreach ($errors as $error) {
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                }

                            }
                        }
                        ?>
                        <form action="signup.php" method="post">
                            <div class="form-group">
                                <label for="username">Kullanıcı Adı:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Şifre:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Şifre Doğrulama:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="form-group">
                                <label for="ad_soyad">Ad Soyad:</label>
                                <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="country">Yaşadığı Ülke:</label>
                                <select class="form-control" id="country" name="country" required>
                                    <option value="" disabled selected>Lütfen bir ülke seçin</option>
                                    <option value="Turkey">Türkiye</option>
                                    <option value="United States">Amerika Birleşik Devletleri</option>
                                    <option value="United Kingdom">Birleşik Krallık</option>
                                    <option value="Germany">Almanya</option>
                                    <option value="France">Fransa</option>
                                    <!-- Diğer ülkeleri ekleyebilirsiniz -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="phone">Telefon Numarası:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Cinsiyet:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                    <label class="form-check-label" for="male">Erkek</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                                    <label class="form-check-label" for="female">Kadın</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="age">Yaş:</label>
                                <input type="number" class="form-control" id="age" name="age" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
