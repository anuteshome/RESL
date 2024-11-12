<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guarantor Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        height:600px;
        }
        .notification-item{
            background-color:rgb(71,17,17);
            color:wheat;
            width:100%;
            /* margin-right:5rem; */
border-radius:10px;
text-align:center;


        }
        .confirmationMessage{
color:wheat;
        }
        .message{
            color:wheat;
        }
        .confirmationModal{
            margin-left:70rem;
        }
        .notification {
            position: relative;
            display: inline-block;
            margin-left:60rem;
            /* margin-top:-4rem; */
        }
        .dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 30px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  position: relative;
  top:-50px;
  left:50px;
}

.dropbtn i {
  margin-right: 8px;
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
        #confirmationModal{
            background-color:rgb(71,17,17);
            color:wheat;
            width:20%;
            height:80px;
            text-align:center;
            border-radius:10px;
            padding-top:5px;
            margin-left:40%;
        }
    </style>
</head>
<body>
    <?php
             
                
     $id2 = null;
     $id3 = null;
    require "db.php";  // Ensure this file sets up your $conn
    // session_start();  // Ensure session is started

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // header("location:newtest2.php");
    //     if (isset($_POST['read'])) {
    //         $notification_id = $_POST['notification_id'];
    //         $sql = "UPDATE confirmation SET is_read = TRUE WHERE id = ?";
    //         $stmt = $conn->prepare($sql);
    //         $stmt->bind_param("i", $notification_id);
    //         $stmt->execute();
    //         $stmt->close();
    //     } elseif (isset($_POST['accept'])) {
    //         $notification_id = $_POST['notification_id'];
    //         $loan_taker_id = $_SESSION['db_id'];
    //         $message2 = "Your Request is Accepted by Guarantee";
    //         $message = "Do you want to be a guarantees?";
    // $loan_taker_id = $_SESSION['db_id'];
    // // $idd2 = $_SESSION['guarantor_id'];
    // // $idd3 = $_SESSION['guarantee_id2']; // Assuming you have a guarantor_id in session
    // $sql = "INSERT INTO notifications (loan_taker_id, guaranter) VALUES (?, ?,?,?,?)";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("issii", $loan_taker_id, $message2,$message,$id2,$id3);
    // $stmt->execute();
    // $stmt->close();
            // Fetch loan_taker_id from notifications table
            $current_user_id = $_SESSION['db_id'];
            $sql = "SELECT * FROM confirmation WHERE guaranter1 || guaranter2  = ? AND is_read = FALSE";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $current_user_id);
            $stmt->execute();
            $result_notifications = $stmt->get_result();

   

            // $sql = "SELECT loan_taker_id FROM notifications WHERE id = ?";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param("i", $notification_id);
            // $stmt->execute();
            // $stmt->bind_result($loan_taker_id);
            // $stmt->fetch();
            // $stmt->close();

            // Check if loan_taker_id is not null
            // if ($loan_taker_id) {
            //     $message = "Are you sure you want to accept?";
            //     $sql = "INSERT INTO confirmation (loan_taker_id, guarantor_id, message) VALUES (?, ?, ?)";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param("iis", $loan_taker_id, $guarantor_id, $message);
            //     $stmt->execute();
            //     $stmt->close();
            // } else {
            //     echo "Error: loan_taker_id cannot be null.";
            // }
        }
    // }

    // Fetch unread notifications
    $current_user_id = $_SESSION['db_id'];
    $sql = "SELECT * FROM confirmation WHERE guaranter1 || guaranter2  = ? AND is_read = FALSE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result_notifications = $stmt->get_result();
    ?>
    <a href="logout.php"><button class="logout">Logout</button></a>
    <div class="notification">
    <button class="dropbtn" onclick="toggleDropdown()">
  <i class="fa fa-bell" aria-hidden="true"></i> 
</button>

        <div id="dropdownContent" class="dropdown-content">
            <?php while ($row = $result_notifications->fetch_assoc()) { ?>
                <div class="notification-item">
                    <p class="message"><?php echo $row['message10']; ?></p>
                    <!-- <p><?php echo $row['message']; ?></p> -->
                    <form method="post" action="">
                        <input type="hidden" name="notification_id" value="">
                        <button type="submit" name="read">Mark as Read</button>
                        <button type="button" onclick="showConfirmationModal(

                        '<?php echo $row['message11']; ?>')">Accept</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="confirmation">
        <!-- <h3>Confirmation Records</h3> -->
        <div id="confirmationContent"></div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" style="display:none" >
        <p id="confirmationMessage" style="color:wheat">Are you sure</p>
        <form method="post" action="">
            <input type="hidden" name="notification_id" id="confirmationNotificationId">
            <button type="submit" name="accept">Yes</button>
            <button type="button" onclick="hideConfirmationModal()">No</button>
        </form>
    </div>

    <?php
    $stmt->close();
    // $conn->close();
    ?>

    <script>
        let message2="Are you sure  "
        function toggleDropdown() {
            document.getElementById("dropdownContent").classList.toggle("show");
        }

        function showConfirmationModal(notificationId, message2) {
            document.getElementById("confirmationModal").style.display = "block";
            document.getElementById("confirmationMessage").textContent = message;
            document.getElementById("confirmationNotificationId").value = notificationId;
        }

        function hideConfirmationModal() {
            document.getElementById("confirmationModal").style.display = "none";
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
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
