<?php
session_start();
// Zkontroluje, zda je uživatel přihlášen na základě existence 'user_id' v session
$is_logged_in = isset($_SESSION['user_id']);
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
                <a href="profil.php">Profil</a>
                <a href="logout.php">Odhlásit se</a>
              <?php else: ?>
                <a href="login.html">Přihlásit se</a> <a href="registration.html">Registrovat</a> <?php endif; ?>
            </div>
          </div>
          <div class="user-photo-dropdown">
            <img src="profile-picture.jpg" alt="Profilová fotka" class="user-photo" onclick="toggleDropdown()">
            <div class="dropdown-content">
              <?php if ($is_logged_in): ?>
                <a href="profil.php">Profil</a>
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