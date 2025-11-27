<?php
session_start();
include 'conn.php';

// 1. ZABEZPEČENÍ: Pouze admin má přístup
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

// 2. ZPRACOVÁNÍ AKCÍ (Mazání, Změna role)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action_id = intval($_GET['id']);
    
    // Ochrana: Admin nemůže smazat sám sebe
    if ($action_id == $_SESSION['user_id']) {
        echo "<script>alert('Nemůžete smazat nebo změnit vlastní účet!'); window.location.href='admin.php';</script>";
        exit;
    }

    if ($_GET['action'] == 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        header("Location: admin.php?msg=deleted");
        exit;
    } 
    elseif ($_GET['action'] == 'toggle_role') {
        // Zjistíme aktuální roli
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $curr_user = $res->fetch_assoc();
            $new_role = ($curr_user['role'] == 'admin') ? 'user' : 'admin';
            
            $update = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
            $update->bind_param("si", $new_role, $action_id);
            $update->execute();
        }
        header("Location: admin.php?msg=role_changed");
        exit;
    }
}

// 3. STRÁNKOVÁNÍ (Pagination)
$limit = 10; // Počet uživatelů na stránku
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Získání celkového počtu uživatelů
$count_result = $conn->query("SELECT COUNT(*) as total FROM users");
$count_row = $count_result->fetch_assoc();
$total_users = $count_row['total'];
$total_pages = ceil($total_users / $limit);

// Získání uživatelů pro aktuální stránku
$sql = "SELECT id, jmeno, prijmeni, email, telefon, role, created_at FROM users ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$users_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
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

    <section class="admin-hero">
        <div class="background-image"></div>
        
        <main id="admin-container">
            <h1 class="admin-title">Admin Panel</h1>

            <div class="admin-tabs">
                <button class="tab-button active" onclick="openTab('users-tab')">Správa Uživatelů</button>
                <button class="tab-button" onclick="openTab('reservations-tab')">Správa Rezervací</button>
            </div>

            <div id="users-tab" class="tab-content" style="display: block;">
                <h2>Seznam uživatelů</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Jméno</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['jmeno'] . ' ' . $row['prijmeni']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['role'] == 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                        <?php echo $row['role']; ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Upravit</a>
                                    
                                    <a href="admin.php?action=toggle_role&id=<?php echo $row['id']; ?>" class="btn-action btn-role">
                                        <?php echo $row['role'] == 'admin' ? '⬇ User' : '⬆ Admin'; ?>
                                    </a>

                                    <a href="admin.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Opravdu smazat tohoto uživatele?');">Smazat</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <?php if($page > 1): ?>
                        <a href="admin.php?page=<?php echo $page-1; ?>" class="pagination-button">Předchozí</a>
                    <?php endif; ?>

                    <?php for($i=1; $i<=$total_pages; $i++): ?>
                        <a href="admin.php?page=<?php echo $i; ?>" class="pagination-button <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if($page < $total_pages): ?>
                        <a href="admin.php?page=<?php echo $page+1; ?>" class="pagination-button">Další</a>
                    <?php endif; ?>
                </div>
            </div>

            <div id="reservations-tab" class="tab-content" style="display: none;">
                <h2>Seznam rezervací</h2>
                <p>Zde bude brzy seznam všech rezervací.</p>
            </div>

        </main>
    </section>

    <footer>
        <p>&copy; 2023 Vilémův strejda. Admin Sekce.</p>
    </footer>

    <script>
        function openTab(tabName) {
            // Skrýt všechny taby
            var contents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < contents.length; i++) {
                contents[i].style.display = "none";
            }
            // Zrušit active u tlačítek
            var buttons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove("active");
            }
            // Zobrazit vybraný
            document.getElementById(tabName).style.display = "block";
            // Přidat active na kliknuté tlačítko
            event.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>