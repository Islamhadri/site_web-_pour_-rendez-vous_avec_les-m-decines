<?php
session_start();
include("db.php");

$erreurs = [];
$type_utilisateur = $_POST['type_utilisateur'] ?? ''; // Définir la variable type_utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $mot_de_passe = $_POST["mot_de_passe"];
    $confirmer_mot_de_passe = $_POST["confirmer_mot_de_passe"];

    if ($type_utilisateur === "medecin") {
        $wilaya = $_POST["wilaya"];
        $specialite = $_POST["specialite"];
        $numero_identification = $_POST["numero_identification"];

        if (!preg_match('/^\d{8,15}$/', $numero_identification)) {
            $erreurs[] = "Le numéro d'identification doit contenir entre 8 et 15 chiffres.";
        }
    } elseif ($type_utilisateur === "patient") {
        $wilaya = $_POST["wilaya_patient"];
        $maladie = $_POST["maladie"];
        $carte_chronique = $_POST["carte_chronique"];

        if (!preg_match('/^\d{6}$/', $carte_chronique)) {
            $erreurs[] = "Le numéro de carte chronique doit contenir exactement 6 chiffres.";
        }
    } else {
        $erreurs[] = "Veuillez choisir un type d'utilisateur.";
    }

    if (empty($nom)) $erreurs[] = "Le nom est requis.";
    if (empty($prenom)) $erreurs[] = "Le prénom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Adresse e-mail invalide.";
    if (strlen($mot_de_passe) < 6) $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    if ($mot_de_passe !== $confirmer_mot_de_passe) $erreurs[] = "Les mots de passe ne correspondent pas.";

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) $erreurs[] = "Cette adresse e-mail est déjà utilisée.";

    if (empty($erreurs)) {
        // Hacher le mot de passe
        $password = password_hash($mot_de_passe, PASSWORD_DEFAULT); // Utiliser $mot_de_passe ici

        // Insertion dans le tableau users
        $stmt = $conn->prepare("INSERT INTO users (email, password, user_type, nom, prenom) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $password, $type_utilisateur, $nom, $prenom]); // Utiliser $type_utilisateur
        $user_id = $conn->lastInsertId();  // Récupérer l'ID de l'utilisateur inséré

        // Insertion spécifique au type d'utilisateur
        if ($type_utilisateur === "medecin") {
            $stmt = $conn->prepare("INSERT INTO doctors (user_id, wilaya, specialty, doctor_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $wilaya, $specialite, $numero_identification]);
        } elseif ($type_utilisateur === "patient") {
            $stmt = $conn->prepare("INSERT INTO patients (user_id, wilaya, maladie, social_security_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $wilaya, $maladie, $carte_chronique]);
        }

        $_SESSION['user'] = $user_id;
        $_SESSION['role'] = $type_utilisateur;
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription | DocRéservation</title>
<style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f7f7f7;
    }
    .container {
      width: 90%;
      max-width: 600px;
      margin: 3rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #0b8a60;
    }
    .errors {
      background: #ffe6e6;
      border: 1px solid #ff3333;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 5px;
      color: #b30000;
    }
    label {
      display: block;
      margin: 0.5rem 0 0.2rem;
      font-weight: 600;
    }
    input, select {
      width: 100%;
      padding: 0.6rem;
      margin-bottom: 1rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .radio-group {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-bottom: 1rem;
    }
    .btn {
      background: #0b8a60;
      color: white;
      padding: 0.8rem;
      border: none;
      width: 100%;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
    }
    .btn:hover {
      background: #08704f;
    }
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--primary);
  padding: 1rem 2rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  animation: slideDown 0.8s ease-out;
}
.navbar .logo {
  font-size: 1.8rem;
  font-weight: 700;
  color: #fff;
}
.navbar nav ul {
  list-style: none;
  display: flex;
  gap: 1.5rem;
}
.navbar nav ul li a {
  color: green;
  font-weight: 500;
  position: relative;
}
.navbar nav ul li a::after {
  content: '';
  position: absolute;
  left: 0; bottom: -4px;
  width: 0; height: 2px;
  background: var(--bg-light);
  transition: width var(--transition);
}
.navbar nav ul li a:hover::after {
  width: 100%;
}
.auth-buttons a {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  border: 2px solid #fff;
  border-radius: 6px;
  color: #fff;
  font-weight: 500;
}
.auth-buttons a:hover {
  background: #fff;
  color: var(--primary);
}

  </style>
</head>
<body>
<!-- Navbar -->
  <header class="navbar">
    <div class="logo">DocteurDZ</div>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="services.php">Nos Services</a></li>
        <li><a href="about.php">À propos</a></li>
        <li><a href="specialites.php">Spécialités</a></li>
        <li><a href="appointment.php">Prendre un rendez-vous</a></li>
      </ul>
    </nav>
</header>
<div class="container">
  <h2>Créer un compte</h2>

  <?php if (!empty($erreurs)): ?>
    <div class="errors">
      <ul>
        <?php foreach ($erreurs as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="register.php">
    <div class="radio-group">
      <label><input type="radio" name="type_utilisateur" value="medecin" required> Médecin</label>
      <label><input type="radio" name="type_utilisateur" value="patient"> Patient</label>
    </div>

    <label>Nom</label>
    <input type="text" name="nom" required>

    <label>Prénom</label>
    <input type="text" name="prenom" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="mot_de_passe" required>

    <label>Confirmer mot de passe</label>
    <input type="password" name="confirmer_mot_de_passe" required>

    <!-- Médecin -->
    <div id="medecin-fields" style="display:none;">
      <label>Wilaya</label>
      <input type="text" name="wilaya">

      <label>Spécialité</label>
      <input type="text" name="specialite">

      <label>Numéro d'identification</label>
      <input type="text" name="numero_identification">
    </div>

    <!-- Patient -->
    <div id="patient-fields" style="display:none;">
      <label>Wilaya</label>
      <input type="text" name="wilaya_patient">

      <label>Maladie</label>
      <input type="text" name="maladie">

      <label>Carte maladie chronique</label>
      <input type="text" name="carte_chronique">
    </div>

    <button type="submit" class="btn" name="submit">S'inscrire</button>
  </form>
</div>

<script>
  const medecinFields = document.getElementById('medecin-fields');
  const patientFields = document.getElementById('patient-fields');
  const radios = document.querySelectorAll('input[name="type_utilisateur"]');

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (radio.value === 'medecin') {
        medecinFields.style.display = 'block';
        patientFields.style.display = 'none';
      } else {
        medecinFields.style.display = 'none';
        patientFields.style.display = 'block';
      }
    });
  });
</script>

</body>
</html>
