<?php
// Database connection
$host = 'localhost'; // Replace with your host
$dbname = 'resl'; // Replace with your database name
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch granted loan reports with necessary details from both HR and branch tables
$hr_sql = "
    SELECT 
        hr.branch_id,
        hr.district_name,
        hr.branch_name,
        hr.number_of_employees,
        hr.total_amount_granted,
        e.employee_id,
        e.name AS employee_name,
        e.salary AS employee_salary,
        e.working_unit AS employee_working_unit,
        e.district AS employee_district,
        e.age AS employee_age,
        e.is_permanent AS employee_is_permanent,
        e.existing_loans AS employee_existing_loans,
        e.national_id_upload AS employee_national_id,
        e.date_of_employment AS employee_date_of_employment,
        e.working_period AS employee_working_period,
        e.max_loan_amount AS employee_max_loan_amount,
        e.guarantor_count,
        b.guarantee_id,
        b.guarantee_name,
        g.salary,
        g.age,
        g.working_unit,
        g.district,
        g.existing_loan
    FROM hr_table hr
    LEFT JOIN branch_table b ON hr.branch_id = b.branch_id
    LEFT JOIN branch_employees e ON b.employee_id = e.employee_id
    LEFT JOIN general_employee g ON b.employee_id = g.id
    WHERE b.loan_status = 'granted'
    ORDER BY hr.district_name, hr.branch_name
";

$result = $conn->query($hr_sql);

// Organize the data by district
$districts = [];
while ($row = $result->fetch_assoc()) {
    $districts[$row['district_name']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .district-section {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 15px;
        }

        .branch-table {
            width: 100%;
            border-collapse: collapse;
        }

        .branch-table th,
        .branch-table td {
            padding: 8px;
            text-align: left;
        }

        .branch-table th {
            background-color: #007bff;
            color: white;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 15px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-4">
        <h1 class="text-center  mb-4" style="color: rgb(71, 17, 17);">HR Dashboard</h1><a style="float: right; text-decoration:none;color:rgb(71, 17, 17)" class="logouts" href="logout.php">Logout</a>
        <h2 class="text-center mb-4" style="color: rgb(71, 17, 17);">Loan Granting Report - Granted Loans</h2>

        <?php if (empty($districts)): ?>
            <p class="text-center text-danger">No granted loans found.</p>
        <?php else: ?>
            <?php foreach ($districts as $district_name => $branches): ?>
                <div class="district-section">
                    <h3 class="text-center text-info" style="color: rgb(71, 17, 17);" >District: <?php echo htmlspecialchars($district_name); ?></h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th>Number of Employees</th>
                                <th>Total Amount Granted</th>
                                <th>Employee Name</th>
                                <th>Employee Salary</th>
                                <th>Employee Working Unit</th>
                                <th>Employee District</th>
                                <th>Employee Age</th>
                                <th>Employee Permanent Status</th>
                                <th>Employee Existing Loans</th>
                                <th>Guarantee Name</th>
                                <th>Guarantee Salary</th>
                                <th>Guarantee Age</th>
                                <th>Guarantee Working Unit</th>
                                <th>Guarantee District</th>
                                <th>Guarantee Existing Loans</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($branches as $branch): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($branch['branch_name']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['number_of_employees']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['total_amount_granted']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_name']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_salary']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_working_unit']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_district']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_age']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_is_permanent'] ? 'Yes' : 'No'); ?></td>
                                    <td><?php echo htmlspecialchars($branch['employee_existing_loans']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_name']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_salary']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_age']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_working_unit']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_district']); ?></td>
                                    <td><?php echo htmlspecialchars($branch['guarantee_existing_loans']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
