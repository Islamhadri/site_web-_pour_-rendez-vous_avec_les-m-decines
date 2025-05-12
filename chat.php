<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['appointment_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$appointment_id = $_GET['appointment_id'];

// جلب بيانات المستخدم
if ($user_type === 'doctor') {
    $stmt = $conn->prepare("SELECT nom FROM doctors WHERE user_id = ?");
} else {
    $stmt = $conn->prepare("SELECT nom FROM patients WHERE user_id = ?");
}
$stmt->execute([$user_id]);
$current_user_name = $stmt->fetchColumn();

// جلب الرسائل الخاصة بالموعد
$stmt = $conn->prepare("
    SELECT m.*, 
           IF(m.sender_type = 'doctor', d.nom, p.nom) AS sender_name 
    FROM messages m
    LEFT JOIN doctors d ON (m.sender_type = 'doctor' AND d.id = (SELECT doctor_id FROM appointments WHERE id = m.appointment_id))
    LEFT JOIN patients p ON (m.sender_type = 'patient' AND p.id = (SELECT patient_id FROM appointments WHERE id = m.appointment_id))
    WHERE m.appointment_id = ?
    ORDER BY m.timestamp ASC
");
$stmt->execute([$appointment_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Discussion</title>
  <link rel="stylesheet" href="chat.css">
</head>
<body>
  <div class="chat-container">
    <h2>Discussion avec <?= htmlspecialchars($user_type === 'doctor' ? 'le patient' : 'le médecin') ?></h2>

    <div class="messages">
      <?php foreach ($messages as $msg): ?>
        <div class="message <?= $msg['sender_name'] === $current_user_name ? 'from-me' : 'from-other' ?>">
          <span class="sender"><?= htmlspecialchars($msg['sender_name']) ?>:</span>
          <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <form action="send_message_action.php" method="POST" class="send-form">
      <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
      <textarea name="message" required placeholder="Écrire un message..."></textarea>
      <button type="submit">Envoyer</button>
    </form>
  </div>
</body>
</html>
