<?php
session_start();
require "db.php"; // Include the database connection
require "test1.php";
require "newtest2.php";

// if (!isset($_SESSION['db_id'])) {
//     $_SESSION['db_id'] =; /* appropriate value here, e.g., fetched from the database */;
// }


$eligible = false;
$sql = "SELECT * FROM general_employee WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['db_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Store employee data in session
    $_SESSION['db_name'] = $row['name'];
    $_SESSION['db_salary'] = $row['salary'];
    $_SESSION['db_wu'] = $row['working_unit'];
    $_SESSION['db_district'] = $row['district'];
    $_SESSION['db_age'] = $row['age'];
    $_SESSION['db_permanent'] = $row['is_permanent'];
    // $_SESSION['db_existing_loan'] = $row['existing_loans'];
    // $_SESSION['db_national_id'] = $row['national_id_upload'];
    $_SESSION['db_date_emp'] = $row['date_of_employment'];
    $_SESSION['db_created_at'] = $row['created_at'];

    $year_of_employment = (int)date('Y', strtotime($_SESSION['db_date_emp']));
    $Emp = 2024 - $year_of_employment;
    $new_emp = $Emp * 12;

    // Determine eligibility
    if ($_SESSION['db_permanent'] == 1 && $_SESSION['db_age'] < 55 && $Emp >= 1) {
        $_SESSION['eligibility'] = 'you are eligible <i class="fa fa-check" aria-hidden="true"></i>';
        $eligible = true;
    } else {
        $_SESSION['eligibility'] = 'you are not eligible <i class="fa fa-times" aria-hidden="true"></i>';
    }
}
if (isset($array['existing_loans'])) {
    $existingLoans = $array['existing_loans'];
} else {
    // Handle the case where the key doesn't exist
    $existingLoans = 0; // or some default value
}

$msg = $success = $mssg = $mssg1 ="";
$db1_salary = $db1_permanent = $db1_age = null;
$db2_salary = $db2_permanent = $db2_age = null;
$db3_salary = $db3_permanent = $db3_age = null;
$third_guarantee_required = false;  // New variable to determine if the third guarantee is needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // header("location:loan.php");
    $id1 = isset($_POST['id1']) ? (int)$_POST['id1'] : null;
    $id2 = isset($_POST['id2']) ? (int)$_POST['id2'] : null;
    $id3 = isset($_POST['id3']) ? (int)$_POST['id3'] : null;

    // File uploads
    $id1_pic = isset($_FILES['id1_pic']) ? $_FILES['id1_pic'] : null;
    $id2_pic = isset($_FILES['id2_pic']) ? $_FILES['id2_pic'] : null;
    $id3_pic = isset($_FILES['id3_pic']) ? $_FILES['id3_pic'] : null;

    // Ensure the uploads directory exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Move uploaded files to the uploads directory
    $id1_pic_path = $id2_pic_path = $id3_pic_path = null;

    if ($id1_pic) {
        $id1_pic_path = 'uploads/' . basename($id1_pic['name']);
        move_uploaded_file($id1_pic['tmp_name'], $id1_pic_path);
    }

    if ($id2_pic) {
        $id2_pic_path = 'uploads/' . basename($id2_pic['name']);
        move_uploaded_file($id2_pic['tmp_name'], $id2_pic_path);
    }

    if ($id3_pic) {
        $id3_pic_path = 'uploads/' . basename($id3_pic['name']);
        move_uploaded_file($id3_pic['tmp_name'], $id3_pic_path);
    }

    // Store image paths in the database if IDs are provided
    if ($id1 && $id1_pic_path) {
        $stmt = $conn->prepare("UPDATE general_employee SET image_path = ? WHERE id = ?");
        $stmt->bind_param("si", $id1_pic_path, $id1);
        $stmt->execute();
    }

    if ($id2 && $id2_pic_path) {
        $stmt->bind_param("si", $id2_pic_path, $id2);
        $stmt->execute();
    }

    if ($id3 && $id3_pic_path) {
        $stmt->bind_param("si", $id3_pic_path, $id3);
        $stmt->execute();
    }

    // Fetch and validate employee data
    $stmt = $conn->prepare("SELECT * FROM general_employee WHERE id = ?");
    if ($id1) {
        $stmt->bind_param("i", $id1);
        $stmt->execute();
        $result1 = $stmt->get_result();
        if ($result1 && $result1->num_rows > 0) {
            $res = $result1->fetch_assoc();
            $db1_salary = $res['salary'];
            $db1_permanent = $res['is_permanent'];
            $db1_age = $res['age'];
        }
    }

    if ($id2) {
        $stmt->bind_param("i", $id2);
        $stmt->execute();
        $result2 = $stmt->get_result();
        if ($result2 && $result2->num_rows > 0) {
            $res = $result2->fetch_assoc();
            $db2_salary = $res['salary'];
            $db2_permanent = $res['is_permanent'];
            $db2_age = $res['age'];
        }
    }

    if ($id3) {
        $stmt->bind_param("i", $id3);
        $stmt->execute();
        $result3 = $stmt->get_result();
        if ($result3 && $result3->num_rows > 0) {
            $res = $result3->fetch_assoc();
            $db3_salary = $res['salary'];
            $db3_permanent = $res['is_permanent'];
            $db3_age = $res['age'];
        }
    }
 

if($id1 !== $_SESSION['db_id']){


    $mssg="!You are not enter your ID,Please enter your ID!";



}else{

    // $message = "Do you want to be a guarantees?";
    // $loan_taker_id = $_SESSION['db_id']; // Assuming you have a guarantor_id in session
    // $sql = "INSERT INTO confirmation (loan_taker_id, message10,guaranter1,guaranter2) VALUES (?,?,?,?)";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("isii", $loan_taker_id, $message,$id2,$id3);
    // $stmt->execute();
    // $stmt->close();
   
        // Validation logic here...
        $sum = $db2_salary + $db3_salary;
    if ($db1_salary !== null && $db2_salary !== null) {
        if ($db1_salary < $db2_salary && $db2_permanent && $db2_age < 55) {
            // $mssg = "You can be guaranteed";
            $success1 = "Your Request is successfully sent!";
            $success = "Your Request is successfully sent!";
        } else {
            $third_guarantee_required = true;  // Set the flag to true if first guarantee is insufficient
            if ($db1_salary < $sum && $db2_permanent && $db3_permanent && $db2_age < 55 && $db3_age < 55) {
                // $mssg = "You can be guaranteed with both second and third employees.";
                $success = "Your Request is successfully sent!";
            } else {
                $mssg = "Please add a 2nd guarantee or another guarantee.";
            
            }
        }
    }

  
}
$loan_taker_id = $_SESSION['db_id']; // The session ID for loan_taker
$message1 = "Do you want to be a first guarantor?"; // Message for the first guarantor
$message2 = "Do you want to be a second guarantor?"; // Message for the second guarantor
$guarantor1_id = $id2;  // The actual ID of the first guarantor
$guarantor2_id = $id3;  // The actual ID of the second guarantor
// Function to insert the first guarantor
// function insertFirstGuarantor($loan_taker_id, $message1, $guarantor1_id, $conn) {
//     // Prepare the SQL query for the first guarantor
//     $sql = "INSERT INTO confirmation (loan_taker_id, message10, guaranter1) 
//             VALUES (?, ?, ?) 
//             ON DUPLICATE KEY UPDATE message10 = VALUES(message10), guaranter1 = VALUES(guaranter1)";
    
//     if ($stmt = $conn->prepare($sql)) {
//         $stmt->bind_param("isi", $loan_taker_id, $message1, $guarantor1_id);
        
//         if ($stmt->execute()) {
//             $stmt->close();
//             return true;
//         } else {
//             $stmt->close();
//             return false;
//         }
//     } else {
//         return false;
//     }
// }

// Function to insert the second guarantor
// Function to insert the second guarantor
function insertSecondGuarantor($loan_taker_id, $message1, $guarantor1_id, $message2, $guarantor2_id, $conn) {
    // Insert a new record for the second guarantor if it doesn't already exist
    $sql = "INSERT INTO confirmation (loan_taker_id, message10, guaranter1, message11, guaranter2) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE message10 = VALUES(message10), guaranter1 = VALUES(guaranter1), message11 = VALUES(message11), guaranter2 = VALUES(guaranter2)";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the statement
        $stmt->bind_param("issii", $loan_taker_id, $message1, $guarantor1_id, $message2, $guarantor2_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;  // Success
        } else {
            $stmt->close();
            return false;  // Error
        }
    } else {
        return false;  // Error with preparing the statement
    }
}



// Check if the form was submitted
// if (isset($_POST['notify1'])) {
//     // Insert first guarantor
//     if (insertFirstGuarantor($loan_taker_id, $message1, $guarantor1_id, $conn)) {
//         echo "First guarantor data inserted successfully!";
//     } else {
//         echo "Error inserting first guarantor data.";
//     }
// }

if (isset($_POST['notify2'])) {
    // Insert second guarantor
    if (insertSecondGuarantor($loan_taker_id, $message1, $guarantor1_id, $message2, $guarantor2_id, $conn)) {
        // echo "Second guarantor data inserted successfully!";
    } else {
        echo "Error inserting second guarantor data.";
    }
}
if($id1=== $id2){
    $mssg1="you can't be both";
}


}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Info</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Css/loan.css">
    <!-- <script>
        function validateForm() {
            var thirdGuaranteeRequired = 
            <?php echo json_encode($third_guarantee_required); ?>;
            if (thirdGuaranteeRequired) {
                var id3 = document.forms["employeeForm"]["id3"].value;
                var id3_pic = document.forms["employeeForm"]["id3_pic"].value;
                if (id3 == "" || id3_pic == "") {
                    alert("Third guarantor's ID and picture are required!");
                    return false;
                }
            }
        }

        var eligible = <?php echo json_encode($eligible); ?>;
        if (!eligible) {
            document.getElementById("form").style.display = "none";
        }
    </script> -->
</head>
<body class="bg-light">
<button class="renew" style="padding: 5px 20px;
margin-top:-8rem;
margin-left:2rem;
 background-color:rgb(71, 17, 17);
border-radius:10px;
border:none;
"><a style=" text-decoration:aqua; color:antiquewhite;
" href="revolve.php">Renew</a> </button> <div class="container mt-5">
        <div class="error_handel">
        <h5 class="text-center text-success " id="success"  ><?php echo $success; ?></h5>
        <h3 class="text-center text-danger"id="mssg" style="font-size: 1.2rem; color:red;margin-top:-3rem;"><?php echo $mssg; ?></h3>
       <h3 class="text-center text-danger"  id="mssg1" style="font-size: 1.2rem; color:red"><?php echo $mssg1; ?></h3>

        </div>
   
        <?php if ($eligible): ?>
        <form id="form" class="form" name="employeeForm" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
            <h1 class="text-center" style="margin-right:8rem">Loan Form</h1><br>
            <div class="form-group">
                <label for="id1">Your ID:</label>
                <input type="text" class="form-control" name="id1" required />
                <input type="file" class="form-control-file mt-2 choose" name="id1_pic" accept="image/*" required />
            </div>
            <div class="form-group">
                <!-- <form action="" method="post"> -->
                <label for="id2">First Guarantor ID:</label>
                <input type="text" class="form-control" name="id2" required />
                <input type="file" class="form-control-file mt-2 choose" name="id2_pic" accept="image/*" required /><br>
                <!-- <button type="submit" class="btn btn-primary noti1" name="notify1">Notify First Guarantor</button> -->
                <!-- <button type="submit" name="notify1" class="btn btn-primary noti1">Notify</button> -->
                <!-- </form> -->
            </div><br>
            <?php
      if($mssg){
?>
<div class="form-group sec" >
        <!-- <form action="" method="post"> -->
        <label for="id3">Second Guarantor ID:</label>
                <input type="text" class="form-control" name="id3" id="id3" />
                <input type="file" class="form-control-file mt-2 choose" name="id3_pic" id="id3_pic" accept="image/*"  /><br>
                <!-- <button type="submit" name="notify2" class="btn btn-primary noti2">Notify</button> -->
                <!-- </form> -->
            </div><br>
            

            <?php }else{?>


          
         <?php   }
            
            
            
            ?>
            <div class="text-center">
            <button type="submit"  class="btn btn-primary noti2" name="notify2">Notify the Guarantee(s)</button>   

            </div>
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-success  submit">Submit</button>
            </div>
            </form>
        <?php else: ?>
            <h2 class="text-center text-danger" style="font-size: 2rem;">You can't see any Form Because you are not eligible for a loan.</h2>
        <?php endif; ?>
        <!-- <h5 class="text-center"><?php 
        // echo $msg; 
        ?></h5>
        <h5 class="text-center"><?php 
        // echo $mssg; 
        ?></h5> -->




    </div>
     <script>
    // Set the time limit in milliseconds (5000ms = 5 seconds)
    // setTimeout(function() {
    //     // Find the element with the id 'welcome-message' and hide it
    //     document.getElementById('form').style.marginTop='-7rem';
    // }, 5000); // 5 seconds
    
   
    // Set the time limit in milliseconds (5000ms = 5 seconds)
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('mssg').style.display='none';
    }, 5000); // 5 seconds
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('mssg1').style.display='none';
    }, 5000); // 5 seconds
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('mssg').style.display='none';
    }, 5000); // 5 seconds
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('success').style.display='none';
    }, 5000); // 5 seconds
    
</script> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
   
   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
