<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Récupérer les spécialités disponibles
$stmt_specialties = $conn->prepare("SELECT DISTINCT specialty FROM doctors");
$stmt_specialties->execute();
$specialties = $stmt_specialties->fetchAll();

if ($user_type === 'patient') {
    // Récupérer les détails du patient
    $stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Prendre un Rendez-vous</title>
  <style>
    /* rdv.css */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }
    body {
        background-color: #f9f9f9;
        color: #333;
    }
    .navbar {
        background-color: #4CAF50;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar .logo {
        color: #fff;
        font-size: 24px;
        font-weight: bold;
    }
    .navbar nav ul {
        display: flex;
        list-style: none;
    }
    .navbar nav ul li {
        margin-left: 20px;
    }
    .navbar nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
    }
    .navbar nav ul li a:hover,
    .navbar nav ul li a.active {
        text-decoration: underline;
    }
    .hero {
        background-color: #4CAF50;
        color: white;
        text-align: center;
        padding: 50px 0;
    }
    .animated-text {
        font-size: 36px;
        font-weight: bold;
        animation: fadeInText 4s ease-out;
    }
    @keyframes fadeInText {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        background-color: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    select, input[type="date"], input[type="time"], input[type="text"], textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #45a049;
    }
    .urgent-btn {
        width: 100%;
        padding: 12px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        margin-top: 10px;
    }
    .urgent-btn:hover {
        background-color: #d9534f;
    }
    .urgent-container textarea {
        height: 100px;
    }
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        h2 {
            font-size: 24px;
        }
        .navbar nav ul {
            flex-direction: column;
            align-items: center;
        }
        .navbar nav ul li {
            margin: 10px 0;
        }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo">DocRDV</div>
    <nav>
      <ul>
        <li><a href="index.html">Accueil</a></li>
        <li><a href="rdv.php" class="active">Prendre un Rendez-vous</a></li>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="logout.php">Déconnexion</a></li>
      </ul>
    </nav>
  </header>

  <section class="hero">
    <div class="animated-text">
      Nous sommes là pour vous, votre santé est notre priorité !
    </div>
  </section>

  <div class="form-container">
    <h2>Prendre un rendez-vous</h2>

    <form action="rdv_action.php" method="POST">
      <label for="specialty">Choisir un spécialité</label>
      <select name="specialty" id="specialty" required>
        <option value="">Sélectionner un spécialité</option>
        <?php foreach ($specialties as $specialty): ?>
          <option value="<?= htmlspecialchars($specialty['specialty']) ?>"><?= htmlspecialchars($specialty['specialty']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="doctor">Choisir un médecin</label>
      <select name="doctor" id="doctor" required>
        <option value="">Sélectionner un médecin</option>
      </select>

      <label for="appointment_date">Date du rendez-vous</label>
      <input type="date" name="appointment_date" id="appointment_date" required>

      <label for="appointment_time">Heure du rendez-vous</label>
      <input type="time" name="appointment_time" id="appointment_time" required>

      <div class="urgent-container">
        <label for="urgent_message">Message urgent</label>
        <textarea name="urgent_message" id="urgent_message" placeholder="Envoyez un message direct au médecin si c'est urgent"></textarea>
      </div>

      <button type="submit">Prendre le Rendez-vous</button>
      <button type="button" class="urgent-btn" onclick="window.location.href='mailto:docteur@example.com'">Urgent !</button>
    </form>
  </div>

  <script>
    document.getElementById('specialty').addEventListener('change', function() {
        var specialty = this.value;

        if (specialty) {
            fetch('get_doctors.php?specialty=' + specialty)
                .then(response => response.json())
                .then(data => {
                    var doctorSelect = document.getElementById('doctor');
                    doctorSelect.innerHTML = '<option value="">Sélectionner un médecin</option>';

                    data.forEach(function(doctor) {
                        var option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = doctor.nom + " - " + doctor.specialty;
                        doctorSelect.appendChild(option);
                    });
                });
        } else {
            document.getElementById('doctor').innerHTML = '<option value="">Sélectionner un médecin</option>';
        }
    });
  </script>

</body>
</html>
