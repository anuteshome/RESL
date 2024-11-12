<?php
// session_start();
require "db.php"; // Include the database connection

if (!isset($_SESSION['db_id'])) {
    header("location:login.php"); // Redirect to login if not logged in
    exit();
}
$R_S="";
$upto="";
$Amount = 0;
if (isset($_POST['loan'])) {
    $Amount = $_POST['amount'];
}

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

    // Calculate loan amount
    if ($new_emp == 3) {
        $R_S = $_SESSION['db_salary'] * 3;
    } elseif ($new_emp == 6) {
        $R_S = $_SESSION['db_salary'] * 6;
    } elseif ($new_emp == 12) {
        $R_S = $_SESSION['db_salary'] * 12;
    } elseif ($new_emp == 24) {
        $R_S = $_SESSION['db_salary'] * 24;
    } else {
        $R_S = "Invalid Amount";
    }

    if ($R_S >= $Amount) {
        $loan = "True";
    } else {
        $loan = "False";
    }

    $welcome_message = "Welcome " . $_SESSION['db_name'] . ", " . $_SESSION['eligibility'];
} else {
    echo "Fetching data is not working.";
    exit();
}
if($eligible){
$upto=" You can Loan up to ". $R_S ;
}else{

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Info</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Css/loan.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="welcome-message text-center" id="back">
        <h1 class="well" id="welcome-message"><?php echo $welcome_message . $upto; ?></h1>
    </div>
    
    <!-- Optional logout link -->
    <!-- <a class="logouts" href="logout.php">Logout</a> -->
    <br>
</div>

<!-- <script>
    // Set the time limit in milliseconds (5000ms = 5 seconds)
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('welcome-message').style.display = 'none';
    }, 5000); // 5 seconds
</script>

<script>
    // Set the time limit in milliseconds (5000ms = 5 seconds)
    setTimeout(function() {
        // Find the element with the id 'welcome-message' and hide it
        document.getElementById('back').style.display = 'none';
    }, 5000); // 5 seconds
    
</script> -->


        <?php if ($eligible): ?>
        <!-- <form style="margin-top:6rem; padding-right:-3rem" class="loanForm" id="loanForm" action="" method="post">
            <div class="form-group">
                <label for="amount" class="font-weight-bold">Enter your Loan Amount</label>
                <input style="padding:--4rem" type="number" class="form-control" id="amount" placeholder="Enter your loan amount" name="amount" required />
            </div>
            <div class="text-center">
                <button type="submit" name="loan" class="btn btn-danger">Apply</button>
            </div> -->
        </form>
        <?php else: ?>
            <!-- <h3 class="text-center text-danger">You are not eligible to choose the amount of loan.</h3> -->
        <?php endif; ?>
    </div>

    <script>
        var eligible = <?php echo json_encode($eligible); ?>;
        if (!eligible) {
            document.getElementById("loanForm").style.display = "none";
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

