<?php
session_start();
include 'conn.php';

// Zkontroluj, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    // Pokud není přihlášen, přesměruj ho
    header("Location: login.html"); 
    exit;
}

$user_id = $_SESSION['user_id'];

// Cesta ke složce, kam se budou ukládat profilovky
$upload_dir = 'uploads/profile_photos/';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile-photo"])) {
    $file = $_FILES["profile-photo"];
    
    // Zpracování nahrávaného souboru
    $file_name = $file["name"];
    $file_tmp_name = $file["tmp_name"];
    $file_error = $file["error"];
    $file_size = $file["size"];
    
    // zízká příponu
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Povolené typy souborů
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_ext, $allowed)) {
        if ($file_error === 0) {
            // Omez velikost (např. max 5MB = 5 * 1024 * 1024 B)
            if ($file_size < 5000000) { 
                
                // Vytvoření unikátního názvu souboru (proti přepsání a bezpečnost)
                // Např.: 123_456789.jpg (user_id_unikátníčas.ext)
                $new_file_name = $user_id . '_' . uniqid('', true) . '.' . $file_ext;
                $file_destination = $upload_dir . $new_file_name;
                
                // Přesun souboru z dočasného umístění na server
                if (move_uploaded_file($file_tmp_name, $file_destination)) {
                    
                    // Uložit novou cestu do databáze
                    $stmt = $conn->prepare("UPDATE users SET profilovka_cesta = ? WHERE id = ?");
                    // Ukládáme jen relativní cestu, např. uploads/profile_photos/123_...
                    $stmt->bind_param("si", $file_destination, $user_id); 
                    
                    if ($stmt->execute()) {
                        echo '<script>
                                alert("Profilová fotka byla úspěšně nahrána.");
                                window.location.href = "profile.php"; // Přesměrování zpět na profil
                              </script>';
                        exit;
                    } else {
                        // Pokud selže DB, smaž nahraný soubor
                        unlink($file_destination);
                        $error_message = "Chyba při ukládání do databáze.";
                    }
                } else {
                    $error_message = "Chyba při přesunu souboru na server.";
                }
            } else {
                $error_message = "Soubor je příliš velký (max 5MB).";
            }
        } else {
            $error_message = "Chyba při nahrávání souboru.";
        }
    } else {
        $error_message = "Nepodporovaný typ souboru. Zvolte JPG, PNG nebo GIF.";
    }
    
    // Pokud nastala chyba, vypíšeme ji a přesměrujeme
    echo '<script>
            alert("Nahrání se nezdařilo: ' . addslashes($error_message) . '");
            window.location.href = "profile.php"; 
          </script>';
    exit;
} else {
    // Pokud se někdo pokusí přejít na soubor přímo
    header("Location: profile.php");
    exit;
}
?>