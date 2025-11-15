<?php
include 'conn.php';

// Je použita metoda POST?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['jmeno'];
  $email = $_POST['email'];
  $telefon = $_POST['telefon'];
  $password = $_POST['password'];

  // Zahashování hesla do proměné 
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL command který vkládá uživatele do databáze
  $sql = "INSERT INTO Uzivatel (jmeno, email, tel_cislo, heslo) VALUES ('$name', '$email', '$telefon', '$hashed_password')";

  // Provedení SQL commandu
  if ($conn->query($sql) === TRUE) {
    // Přesměrování na index a alert
      echo '<script>
              window.location.href = "index.php";
              alert("Jste úspěšně zaregistrovaný/á");
            </script>';
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
} else {
  // form nebyl submitnutý metodou POST
  echo "Nastal ERROR, prosím zkuste znovu později!";
}
?>