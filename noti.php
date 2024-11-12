<?php
session_start();
require "db.php"; 
require "test1.php";  

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_taker_id = $_SESSION['db_id'];
    
    // Fetch details of the guarantors
    $id1 = isset($_POST['id1']) ? $_POST['id1'] : null;
    $id2 = isset($_POST['id2']) ? $_POST['id2'] : null;
    $id3 = isset($_POST['id3']) ? $_POST['id3'] : null;

    // Send notification when the loan request is created
    if ($id2 && $id3) {
        $message1 = "You have been requested to be a guarantor for a loan.";
        $message2 = "You have been requested to be a second guarantor for a loan.";
        
        // Notify the first and second guarantors
        sendLoanNotification($loan_taker_id, $message1, $id2);
        sendLoanNotification($loan_taker_id, $message2, $id3);
    }

    // If the loan is accepted, notify the loan taker and guarantors
    if (isset($_POST['accept'])) {
        $message = "Your loan request has been accepted!";
        sendLoanApprovalNotification($loan_taker_id, $message, $id2, $id3);
    }

    // Mark notification as read when the user acknowledges it
    if (isset($_POST['read'])) {
        markNotificationAsRead($_POST['notification_id']);
    }
}

// Function to send loan notification
function sendLoanNotification($loan_taker_id, $message, $guarantor_id) {
    global $conn;
    $sql = "INSERT INTO confirmation (loan_taker_id, message10, guaranter1) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $loan_taker_id, $message, $guarantor_id);
    $stmt->execute();
    $stmt->close();
}

// Function to send loan approval notification
function sendLoanApprovalNotification($loan_taker_id, $message1, $guarantor1_id, $guarantor2_id) {
    global $conn;
    $sql = "INSERT INTO confirmation (loan_taker_id, message10, guaranter1, guaranter2, is_read) 
            VALUES (?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $loan_taker_id, $message1, $guarantor1_id, $guarantor2_id);
    $stmt->execute();
    $stmt->close();
}

// Function to mark a notification as read
function markNotificationAsRead($notification_id) {
    global $conn;
    $sql = "UPDATE confirmation SET is_read = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch notifications for the current user
$current_user_id = $_SESSION['db_id'];
$sql = "SELECT * FROM confirmation WHERE (guaranter1 = ? OR guaranter2 = ?) AND is_read = FALSE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_user_id, $current_user_id);
$stmt->execute();
$result_notifications = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guarantor Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .notification-item {
            background-color: rgb(71,17,17);
            color: wheat;
            width: 100%;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        .notification .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .notification .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .notification .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .notification .show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Notifications</h2>
        <div class="notification">
            <button class="btn btn-primary" onclick="toggleDropdown()">Notifications</button>
            <div id="dropdownContent" class="dropdown-content">
                <?php while ($row = $result_notifications->fetch_assoc()) { ?>
                    <div class="notification-item">
                        <p><?php echo $row['message10']; ?></p>
                        <form method="post" action="">
                            <input type="hidden" name="notification_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="read" class="btn btn-secondary">Mark as Read</button>
                            <button type="submit" name="accept" class="btn btn-success">Accept</button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdownContent").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.btn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
