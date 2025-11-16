<?php
session_start();
include 'conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // zjištění zda je zadaný email a heslo
    if (!empty($email) && !empty($password)) {
      
        // připravení a provedení SQL statementu aby našel uživatele se zadaným emailem
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        // Uživatel se zadaným emailem existuje, result je fetchnutý do proměné user
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Ověření hesla
            if (password_verify($password, $user['heslo'])) {
                // Nastavení session proměných
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['loggedin'] = true;
                
                // přesměrování na index
                echo '<script>
                        alert("Jste úspěšně přihlášený/á");
                        window.location.href = "profile.php";
                      </script>';
                exit;
            } else {
                echo '<script>
                        alert("špatný E-mailem nebo heslo");
                        window.location.href = "login.html";
                      </script>';
            }
        } else {
            // Uživatel se zadaným emailem neexistuje
            echo '<script>
                    alert("žádný uživatel s tímto E-mailem nenalezený");
                    window.location.href = "login.html";
                  </script>';
        }
    } else {
        // email nebo heslo nebylo zadané
        echo '<script>
                alert("Prosím zadejte E-mail i heslo");
                window.location.href = "login.html";
              </script>';
    }
}
?>
