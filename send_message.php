<?php
// 1. الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "portfolio_messages"); // بدلي info إذا لزم

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// 2. التحقق من أن الطلب جا من الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. التقاط البيانات وتصفية المدخلات
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // 4. تحضير وإرسال البيانات لقاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // ✅ 5. إرسال نسخة من الرسالة إلى بريدك الإلكتروني
        $to      = "rokaya.choua2@gmail.com"; // 👈 بدليها بالإيميل ديالك
        $subject = "Nouveau message du formulaire";
        $body    = "Nom: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        mail($to, $subject, $body, $headers);

        echo "<p style='color:green;'>✅ Message enregistré et envoyé par email avec succès.</p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur lors de l'enregistrement du message.</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color:red;'>Méthode non autorisée.</p>";
}

$conn->close();
?>
