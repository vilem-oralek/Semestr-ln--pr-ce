<?php
  session_start();
  include 'conn.php';

  // Kontrola přihlášení
  if (!isset($_SESSION['user_id'])) {
      echo "Uživatel není přihlášen.";
      exit;
  }

  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("SELECT jmeno, prijmeni FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user) {
    echo "Uživatel nenalezen.";
  }
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
    <script>
      fetch("header.html")
        .then(response => response.text())
        .then(data => {
          document.getElementById("header-placeholder").innerHTML = data;
        });
    </script>
</head>
<body>
  <div id="header-placeholder"></div>
  <section class="profile-hero">
    <div class="background-image"></div>
    <main id="profile-container"> 
      <h1 class="profile-title">Můj Profil</h1>
      
      <!-- Sekce osobních údajů -->
      <section class="profile-details">
        <h2>Osobní údaje</h2>
        <ul>
          <li>Jméno: <span id="user-first-name"><?php echo htmlspecialchars($user['jmeno']) ?></span></li>
          <li>Příjmení: <span id="user-last-name"><?php echo htmlspecialchars($user['prijmeni']); ?></span></li>
          <li>E-mail: <span id="user-email">jan.novak@example.com</span></li>
          <li>Telefon: <span id="user-phone">+420 123 456 789</span></li>
        </ul>
      </section>

      <!-- Sekce pro nahrání profilové fotky -->
      <section class="profile-photo-upload">
        <h2>Nahrát profilovou fotku</h2>
        <form id="photo-upload-form" method="post" enctype="multipart/form-data">
          <input type="file" id="profile-photo" name="profile-photo" accept="image/*" required>
          <button type="submit">Nahrát</button>
        </form>
      </section>

      <!-- Sekce výpisu rezervací -->
      <section class="profile-reservations">
        <h2>Moje rezervace</h2>
        <ul id="reservations-list">
          <li>Rezervace 1: <span>Datum: 2023-10-01, Čas: 14:00</span></li>
          <li>Rezervace 2: <span>Datum: 2023-10-15, Čas: 10:00</span></li>
          <!-- Další rezervace budou načteny z databáze -->
        </ul>
      </section>
    </main>
  </section>
  <footer>
    <p>&copy; 2023 Modrý Jelen Lipno. Všechna práva vyhrazena.</p>
  </footer>
</body>
</html>