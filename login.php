<?php
session_start();
include 'conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
      
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['heslo'])) {
                // Nastavení session proměnných
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role']; // DŮLEŽITÉ: Ukládáme roli (admin/user)
                $_SESSION['loggedin'] = true;
                
                // Rozhodnutí o přesměrování podle role
                if ($user['role'] === 'admin') {
                     echo '<script>
                        alert("Vítejte v administraci!");
                        window.location.href = "admin.php";
                      </script>';
                } else {
                     echo '<script>
                        alert("Jste úspěšně přihlášený/á");
                        window.location.href = "profile.php";
                      </script>';
                }
                exit; 
            } else {
                echo '<script>
                        alert("Špatný E-mail nebo heslo");
                        window.location.href = "login.html";
                      </script>';
            }
        } else {
            echo '<script>
                    alert("Žádný uživatel s tímto E-mailem nenalezený");
                    window.location.href = "login.html";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Prosím zadejte E-mail i heslo");
                window.location.href = "login.html";
              </script>';
    }
}
?>