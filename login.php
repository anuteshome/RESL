<?php
session_start(); 
require "db.php"; 

if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    // Prepare SQL statement to fetch user data
    $sql = "SELECT * FROM general_employee WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $run = $stmt->get_result();
    
    if ($run->num_rows > 0) {
        $rows = $run->fetch_assoc();
        $db_password = $rows['password'];

        // Verify password
        if ($db_password == $pass) {
            // Set session variables
            $_SESSION['db_id'] = $rows['id'];
            $_SESSION['db_name'] = $rows['name'];
            // Fetch and store additional necessary session variables
            $_SESSION['db_salary'] = $rows['salary'];
            $_SESSION['db_wu'] = $rows['working_unit'];
            $_SESSION['db_district'] = $rows['district'];
            $_SESSION['db_age'] = $rows['age'];
            $_SESSION['db_permanent'] = $rows['is_permanent'];
            // $_SESSION['db_existing_loan'] = $rows['existing_loans'];
            // $_SESSION['db_national_id'] = $rows['national_id_upload'];
            $_SESSION['db_date_emp'] = $rows['date_of_employment'];
            $_SESSION['db_created_at'] = $rows['created_at'];
            $_SESSION['role']=$rows['role'];
            if ($_SESSION['role'] == 'user') { header("location:loan.php"); // Redirect to the next page 
                exit();
             } elseif ($_SESSION['role'] == 'hr') { 
                header("location:hrtable.php");
                 exit(); 
                } elseif ($_SESSION['role'] == 'admin') { 
                    header("location:branchManager.php");
                     exit(); 
                    } 
                else {
                     $err = "Invalid user role."; 
                    } 
                } else {
                     $err = "Can't login: Incorrect password"; 
                    } 
                } else {
                     $err = "Invalid username."; 
                    }
                 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Css/login.css">
</head>
<body>
    <div>
       <a class="home" href="home.php">Home</a> <h1>Login Form</h1><br><br>
<form action="" method="post">
    <label>Username</label><br><br><br>
    <input type="text" name="user" required /><br><br>
    <label>Password</label><br><br><br>
    <input type="password" name="pass" required /><br><br><br>
    <button type="submit" class="btn4" name="login">Login</button>
</form>
    </div>


<?php if (isset($err)) echo "<p class='err' style='color:red;'>$err</p>"; // Display error if exists ?>
</body>
</html>
