<?php
session_start();
include("db.php"); // deja kayna 

$erreur = "";

// Vérifier la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Vérification rapide (compte test manuel)
    if ($email === "adem" && $password === "123") {
        $_SESSION['user'] = $email;
        $_SESSION['user_type'] = 'medecin';
        header("Location: dashboard.php");
        exit;
    }

    // Vérifier si l'email existe dans la base
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion | DocRéservation</title>
  <style>
/* ===== Imports ===== */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

/* ===== Variables ===== */
:root {
  --primary: #0b8a60;
  --primary-light: #36a378;
  --bg-light: #f2fef6;
  --text-dark: #333;
  --transition: 0.4s ease;
}

/* ===== Global ===== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: 'Montserrat', sans-serif;
  background: url('https://images.unsplash.com/photo-1580281657521-0754ebb1f483?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') center/cover fixed;
  color: var(--text-dark);
  overflow-x: hidden;
  animation: fadeIn 1s;
}
a { text-decoration: none; transition: color var(--transition); }
a:hover { color: var(--primary-light); }

/* ===== Navbar ===== */
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
  color: #fff;
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

/* ===== Login Container ===== */
.login-container {
  max-width: 380px;
  margin: 5% auto;
  background: rgba(255,255,255,0.95);
  padding: 2.5rem;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  position: relative;
  overflow: hidden;
  animation: zoomIn 0.8s ease-out;
}
.login-container::before {
  content: '';
  position: absolute;
  top: -40%; right: -40%;
  width: 200px; height: 200px;
  background: var(--primary-light);
  border-radius: 50%;
  opacity: 0.2;
  animation: rotate 10s linear infinite;
}
.login-container h2 {
  text-align: center;
  color: var(--primary);
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
}

/* ===== Form Elements ===== */
.login-container label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}
.login-container input[type="email"],
.login-container input[type="password"] {
  width: 100%;
  padding: 0.8rem;
  margin-bottom: 1.2rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color var(--transition), box-shadow var(--transition);
}
.login-container input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 8px rgba(11,138,96,0.3);
  outline: none;
}
.login-container .role-group {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
.login-container .role-group label {
  font-weight: 400;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}
.login-container .role-group input {
  accent-color: var(--primary);
}

/* ===== Submit Button ===== */
.login-container button {
  width: 100%;
  padding: 0.9rem;
  background-color: var(--primary);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color var(--transition), transform var(--transition);
}
.login-container button:hover {
  background-color: var(--primary-light);
  transform: translateY(-2px);
}

/* ===== Footer ===== */
footer {
  background-color: var(--primary);
  color: #fff;
  text-align: center;
  padding: 1.5rem 0;
  margin-top: 3rem;
  animation: slideUp 0.8s ease-out;
}
footer .social-icons {
  margin-bottom: 0.5rem;
}
footer .social-icons a {
  color: #fff;
  margin: 0 0.5rem;
  font-size: 1.2rem;
  transition: color var(--transition);
}
footer .social-icons a:hover {
  color: var(--accent);
}
footer p {
  margin-top: 0.3rem;
  font-size: 0.9rem;
}

/* ===== Keyframes ===== */
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideDown { from { transform: translateY(-100%); } to { transform: translateY(0); } }
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
@keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
@keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* ===== Responsive ===== */
@media (max-width: 480px) {
  .navbar { flex-direction: column; text-align: center; gap: 0.5rem; }
  .login-container { margin: 10% auto; width: 90%; padding: 2rem; }
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
    <div class="auth-buttons">
      <a href="login.php" class="btn">Connexion</a>
      <a href="register.php" class="btn">Inscription</a>
    </div>
  </header>

  <!-- Page de connexion -->
  <main class="login-container">
    <h2>Connexion</h2>

    <?php if (!empty($erreur)): ?>
      <p class="error"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Se connecter</button>
    </form>
  </main>

  <footer>
    <div class="social-icons">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <p>&copy; 2025 DocRéservation - Tous droits réservés</p>
  </footer>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body> 
</html>
