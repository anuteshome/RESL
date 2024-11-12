<?php
session_start();
require "db.php"; // Include database connection

$msg = "";
$pendingRequests = [];
$grantedRequests = [];
$branch_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['branch_id'])) {
    $branch_id = (int)$_POST['branch_id']; // Get the branch ID from the form submission

    try {
        // Fetch all pending loan requests for the specified branch
        $stmtPending = $conn->prepare("SELECT * FROM branch_table WHERE branch_id = ? AND loan_status = 'pending'");
        $stmtPending->bind_param("i", $branch_id);
        $stmtPending->execute();
        $resultPending = $stmtPending->get_result();

        while ($row = $resultPending->fetch_assoc()) {
            $pendingRequests[] = $row;
        }

        // Fetch all granted loan requests for the specified branch
        $stmtGranted = $conn->prepare("SELECT * FROM branch_table WHERE branch_id = ? AND loan_status = 'granted'");
        $stmtGranted->bind_param("i", $branch_id);
        $stmtGranted->execute();
        $resultGranted = $stmtGranted->get_result();

        while ($row = $resultGranted->fetch_assoc()) {
            $grantedRequests[] = $row;
        }

        if (empty($pendingRequests) && empty($grantedRequests)) {
            $msg = "No loan requests found for branch ID: " . htmlspecialchars($branch_id);
        }
    } catch (Exception $e) {
        $msg = "Failed to fetch loan request data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager Loan Requests</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 15px;
        }
        /* Change the blue to rgb(71, 17, 17) */
        .table th {
            background-color: rgb(71, 17, 17); /* Deep red color */
            color: white;
        }
        .btn-primary {
            background-color: rgb(71, 17, 17); /* Deep red for the submit button */
            border-color: rgb(71, 17, 17); /* Matching border color */
        }
        .btn-primary:hover {
            background-color: rgb(91, 27, 27); /* Slightly darker shade for hover effect */
            border-color: rgb(91, 27, 27);
        }
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center  my-4">Branch Manager </h1>        <a style="float: right; text-decoration:none;color:rgb(71, 17, 17)" class="logouts" href="logout.php">Logout</a>


        <!-- Form to manually input branch ID -->
        <form action="" method="POST" class="mb-4">
            <div class="form-group">
                <label for="branch_id">Enter Branch ID:</label>
                <input type="number" class="form-control" id="branch_id" name="branch_id" required>
            </div>
            <button type="submit" class="btn btn-primary">View Loan Requests</button>
        </form>

        <?php if ($msg): ?>
            <div class="alert alert-danger"><?php echo $msg; ?></div>
        <?php endif; ?>

        <!-- Display Pending Loan Requests -->
        <?php if ($pendingRequests): ?>
            <h2 class="text-center text-warning">Pending Loan Requests</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Loan Amount</th>
                            <th>Guarantee ID</th>
                            <th>Guarantee Name</th>
                            <th>Requester File</th>
                            <th>Guarantee File</th>
                            <th>Repayment Period</th>
                            <!-- <th>Loan Status</th> -->
                            <th>Branch ID</th>
                            <th>Accept</th>
                            <th>Decline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingRequests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['employee_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['loan_amount']); ?></td>
                                <td><?php echo htmlspecialchars($request['guarantee_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['guarantee_name']); ?></td>
                                <td><a href="uploads/<?php echo htmlspecialchars($request['requester_uploaded_file']); ?>" target="_blank">View File</a></td>
                                <td><a href="uploads/<?php echo htmlspecialchars($request['guarantee_uploaded_file']); ?>" target="_blank">View File</a></td>
                                <td><?php echo htmlspecialchars($request['repayment_period']); ?></td>
                                <!-- <td> -->
                                    <?php 
                                        // $loan_status = htmlspecialchars($request['loan_status']);
                                        // if ($loan_status == 'Pending') {
                                        //     echo '<span class="badge badge-warning badge-status">Pending</span>';
                                        // } elseif ($loan_status == 'Approved') {
                                        //     echo '<span class="badge badge-success badge-status">Approved</span>';
                                        // } elseif ($loan_status == 'Rejected') {
                                        //     echo '<span class="badge badge-danger badge-status">Rejected</span>';
                                        // } else {
                                        //     echo '<span class="badge badge-secondary badge-status">Unknown</span>';
                                        // }
                                    ?>
                                <!-- </td> -->
                                <td><?php echo htmlspecialchars($request['branch_id']); ?></td>
                                <td><a href=""><Button style="border: none;">Accept</Button></a></td>
                                <td><a href=""><Button style="border: none;">Decline</Button></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No pending loan requests found for branch ID: <?php echo htmlspecialchars($branch_id); ?>.</p>
        <?php endif; ?>

        <!-- Display Granted Loan Requests -->
        <?php if ($grantedRequests): ?>
            <h2 class="text-center text-success">Granted Loan Requests</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Loan Amount</th>
                            <th>Guarantee ID</th>
                            <th>Guarantee Name</th>
                            <th>Requester File</th>
                            <th>Guarantee File</th>
                            <th>Repayment Period</th>
                            <!-- <th>Loan Status</th> -->
                            <th>Branch ID</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grantedRequests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['employee_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['loan_amount']); ?></td>
                                <td><?php echo htmlspecialchars($request['guarantee_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['guarantee_name']); ?></td>
                                <td><a href="uploads/<?php echo htmlspecialchars($request['requester_uploaded_file']); ?>" target="_blank">View File</a></td>
                                <td><a href="uploads/<?php echo htmlspecialchars($request['guarantee_uploaded_file']); ?>" target="_blank">View File</a></td>
                                <td><?php echo htmlspecialchars($request['repayment_period']); ?></td>
                                <!-- <td> -->
                                    <?
                                // php echo htmlspecialchars($request['loan_status']);
                                 ?>
                                 <!-- </td> -->
                                <td><?php echo htmlspecialchars($request['branch_id']); ?></td>
                               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No granted loan requests found for branch ID: <?php echo htmlspecialchars($branch_id); ?>.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
