<?php
session_start();
require 'db.php';

// تأكد أن المستخدم مسجل الدخول ومريض
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit;
}

// استرجاع التخصصات المميزة من جدول الأطباء
$specialties_stmt = $pdo->query("SELECT DISTINCT specialty FROM doctors");
$specialties = $specialties_stmt->fetchAll(PDO::FETCH_COLUMN);

// استرجاع جميع الأطباء
$doctors_stmt = $pdo->query("SELECT d.id, d.name, d.specialty, d.hospital_name FROM doctors d");
$doctors = $doctors_stmt->fetchAll(PDO::FETCH_ASSOC);

// عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $specialty = $_POST['specialite'] ?? '';
    $doctor_id = $_POST['medecin'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['heure'] ?? '';

    // تحقق من صحة البيانات
    if (!empty($specialty) && !empty($doctor_id) && !empty($date) && !empty($time)) {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $doctor_id, $date, $time]);
        $success = "Votre rendez-vous a été enregistré avec succès.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Prendre un rendez-vous</title>
  <link rel="stylesheet" href="login.css" />
  <link rel="stylesheet" href="rdv.css" />
  <script>
    // JS pour filtrer les médecins selon la spécialité
    function filterDoctors() {
      const specialty = document.getElementById("specialite").value;
      const medecinSelect = document.getElementById("medecin");
      const allOptions = medecinSelect.querySelectorAll("option[data-specialty]");

      medecinSelect.innerHTML = '<option value="">-- Choisir un médecin --</option>';
      allOptions.forEach(option => {
        if (option.dataset.specialty === specialty) {
          medecinSelect.appendChild(option.cloneNode(true));
        }
      });
    }
  </script>
</head>
<body>
  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">DocRDV</div>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Déconnexion</a></li>
      </ul>
    </nav>
  </header>

  <!-- Page de rendez-vous -->
  <section class="rdv-container">
    <h2>Prendre un rendez-vous</h2>

    <?php if (isset($success)): ?>
      <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php elseif (isset($error)): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" id="rdv-form">
      <label for="specialite">Spécialité :</label>
      <select id="specialite" name="specialite" onchange="filterDoctors()" required>
        <option value="">-- Choisir une spécialité --</option>
        <?php foreach ($specialties as $spec): ?>
          <option value="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="medecin">Médecin :</label>
      <select id="medecin" name="medecin" required>
        <option value="">-- Choisir un médecin --</option>
        <?php foreach ($doctors as $doctor): ?>
          <option 
            value="<?= $doctor['id'] ?>" 
            data-specialty="<?= htmlspecialchars($doctor['specialty']) ?>"
          >
            <?= htmlspecialchars($doctor['name']) ?> — <?= htmlspecialchars($doctor['hospital_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="date">Date :</label>
      <input type="date" id="date" name="date" required />

      <label for="heure">Heure :</label>
      <input type="time" id="heure" name="heure" required />

      <button type="submit">Envoyer la demande</button>
      <button type="button" onclick="window.print()">Imprimer</button>
    </form>
  </section>
</body>
</html>
