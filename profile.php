<?php
session_start();
include 'conn.php';

// Kontrola přihlášení - MUSÍ BÝT HNED NA ZAČÁTKU
if (!isset($_SESSION['user_id'])) {
    // Použijeme PHP header pro čisté přesměrování
    header("Location: login.html"); 
    exit;
}

$user_id = $_SESSION['user_id'];
$user = null; // Inicializace proměnné

// Získání dat uživatele
$stmt = $conn->prepare("SELECT jmeno, prijmeni, telefon, email, datum_narozeni FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
}

// Pokud uživatel nebyl nalezen (i když je přihlášen, což by nemělo), odhlásit
if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit;
}

// Místo vypisování alertu, který blokuje, přesměrujeme čistě
// Odstranili jsme: alert("Pro zobrazení profilu se musíte přihlásit.");

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Můj Profil</title>
    <script>
      fetch("header.php")
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
      
      <section class="profile-details">
        <h2>Osobní údaje</h2>
        <ul>
          <li>Jméno: <span id="user-first-name"><?php echo htmlspecialchars($user['jmeno'] ?? 'N/A'); ?></span></li>
          <li>Příjmení: <span id="user-last-name"><?php echo htmlspecialchars($user['prijmeni'] ?? 'N/A'); ?></span></li>
          <li>E-mail: <span id="user-email"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span></li>
          <li>Telefon: <span id="user-phone"><?php echo htmlspecialchars($user['telefon'] ?? 'N/A'); ?></span></li>
          <li>Datum narození: <span id="user-birthdate"><?php echo htmlspecialchars($user['datum_narozeni'] ?? 'N/A'); ?></span></li>
        </ul>
      </section>

      <section class="profile-photo-upload">
        <h2>Nahrát profilovou fotku</h2>
        <form id="photo-upload-form" method="post" enctype="multipart/form-data">
          <input type="file" id="profile-photo" name="profile-photo" accept="image/*" required>
          <button type="submit">Nahrát</button>
        </form>
      </section>

      <section class="profile-reservations">
        <h2>Moje rezervace</h2>
        <ul id="reservations-list">
          <li>Žádné rezervace k zobrazení.</li>
        </ul>
      </section>
    </main>
  </section>
    <footer>
        <p>&copy; 2023 Vilémův strejda. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>