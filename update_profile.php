<?php
session_start();
include 'conn.php';

// 1. Zkontroluj, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); 
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Získání a ošetření dat z formuláře
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];
    $datum_narozeni = $_POST['datum_narozeni'];

    // 3. Základní validace
    if (empty($jmeno) || empty($prijmeni) || empty($telefon) || empty($email) || empty($datum_narozeni)) {
        echo '<script>
                alert("Všechna pole musí být vyplněna!");
                window.location.href = "profile.php";
              </script>';
        exit;
    }
    
    // 4. Aktualizace databáze pomocí Prepared Statement pro bezpečnost
    $stmt = $conn->prepare("UPDATE users SET jmeno = ?, prijmeni = ?, telefon = ?, email = ?, datum_narozeni = ? WHERE id = ?");
    
    // 'sssssi' = 5x string, 1x integer (jmeno, prijmeni, telefon, email, datum_narozeni, user_id)
    $stmt->bind_param("sssssi", $jmeno, $prijmeni, $telefon, $email, $datum_narozeni, $user_id); 
    
    if ($stmt->execute()) {
        // Změna v databázi byla úspěšná
        $_SESSION['email'] = $email; 
        
        echo '<script>
                alert("Profilové údaje byly úspěšně aktualizovány!");
                window.location.href = "profile.php"; // Přesměrování zpět na profil
              </script>';
        exit;
    } else {
        // Chyba při provádění dotazu (např. pokud je email již obsazen a je nastaven jako UNIQUE)
        echo '<script>
                alert("Chyba při aktualizaci dat: Zkuste to prosím znovu.");
                window.location.href = "profile.php";
              </script>';
        exit;
    }
} else {
    // Pokud se někdo pokusí přejít na soubor přímo bez POST metody
    header("Location: profile.php");
    exit;
}
?>