<?php
session_start();
// Zkontroluje, zda je uživatel přihlášen na základě existence 'user_id' v session
$is_logged_in = isset($_SESSION['user_id']);

$profile_image_path = 'profile-picture-default.jpg'; // Defaultní placeholder

if ($is_logged_in) {
    include 'conn.php'; // Připojení k DB jen pokud je uživatel přihlášen
    $user_id = $_SESSION['user_id'];
    
    // Načtení cesty k profilovce
    $stmt = $conn->prepare("SELECT profilovka_cesta FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    if ($user_data && !empty($user_data['profilovka_cesta'])) {
        $profile_image_path = htmlspecialchars($user_data['profilovka_cesta']);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body id = "headerBody">
      <header class="navbar" id="index-nav">
        <div class="logo">
          <a href="index.html">Chata</a>
        </div>
    
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>
    
        <nav class="nav-menu" id="navMenu">
          <ul>
            <li><a href="galerie.html">Galerie</a></li>
            <li><a href="rezervace.html">Rezervace</a></li>
            <li><a href="kontakty.html">Kontakt</a></li>
          </ul>
        </nav>
    
        <div class="user-controls">
          <div class="user-dropdown">
            <div class="username">Profil</div>
            <div class="dropdown-content">
              <?php if ($is_logged_in): ?>
                <a href="profile.php">Profil</a>
                <a href="logout.php">Odhlásit se</a>
              <?php else: ?>
                <a href="login.html">Přihlásit se</a> <a href="registration.html">Registrovat</a> <?php endif; ?>
            </div>
          </div>
          <div class="user-photo-dropdown">
            <img src="<?php echo $profile_image_path; ?>" alt="Profilová fotka" class="user-photo" onclick="toggleDropdown()">
            <div class="dropdown-content">
              <?php if ($is_logged_in): ?>
                <a href="profile.php">Profil</a>
                <a href="logout.php">Odhlásit se</a>
              <?php else: ?>
                <a href="login.html">Přihlásit se</a> <a href="registration.html">Registrovat</a> <?php endif; ?>
            </div>
          </div>
        </div>
      </header>
      
          <script>
            function toggleMenu() {
              document.getElementById("navMenu").classList.toggle("active");
            }
            function toggleDropdown() {
              const dropdowns = document.querySelectorAll('.dropdown-content');
              dropdowns.forEach(menu => menu.classList.toggle('active'));
            }
          </script>
    </body>
</html>