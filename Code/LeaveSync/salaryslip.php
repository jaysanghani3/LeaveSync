<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="" />
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
    <link href="assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h4 {
        color: #333;
    }

    .card {
        margin-top: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 20px;
    }

    .employee-details h5 {
        margin-top: 0;
        color: #555;
    }

    .employee-details p {
        margin: 5px 0;
        color: #777;
    }

    .download-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .download-btn:hover {
        background-color: #45a049;
    }

    @media print {
        .navbar,
        .sidebar,
        .footer {
            display: none !important;
        }
    }
   
    #slide-out, .footer {
        display: none;
    }
    @media print {
        .hide-on-print {
            display: none;
        }
    }
    @media print {
        .hidethis {
            display: none;
        }
    }
    .content-font {
        font-size: 14px; /* Adjust the font size as needed */
    }

    @media print {
        .container {
            border: none !important;
        }
        /* Override font size for content when printing */
        .content-font {
            font-size: px !important; /* Adjust the font size as needed */
        }
    }
   
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <main class="container py-2">
        <div class="row">
            <div class="col">
                <div class="text-center">
                    <h2 class="fw-bold">Salary Slip</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card border border-2 border-dark">
                    <div class="card-body">
                        <?php
                        // Fetch employee details based on the ID from the URL
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $sql = "SELECT * FROM tblemployees WHERE id = :id"; // Change here to use 'EmpId'
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':id', $id, PDO::PARAM_STR); // Correct parameter binding
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_ASSOC);

                            // Display employee details
                            if ($result) {
                        ?>
                        <div style="max-width: 600px; margin: 0 auto;">
                            <h5 style="font-weight: bold; margin-bottom: 15px; text-align: center;">Employee
                                Details</h5>
                            <div class="table-responsive">
                                <table style="width: 100%; border-collapse: collapse; border: 2px solid #000;">
                                    <tbody>
                                        <tr>
                                            <th style="border: 2px solid #000; background-color: #f0f0f0; padding: 10px;">
                                                Field</th>
                                            <th style="border: 2px solid #000; background-color: #f0f0f0; padding: 10px;">
                                                Value</th>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px; font-weight: bold;">First
                                                Name</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo $result['FirstName']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px; font-weight: bold;">Last
                                                Name</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo $result['LastName']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px; font-weight: bold;">Email</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo $result['EmailId']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px; font-weight: bold;">Department</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo $result['Department']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php
                        // Calculate salary, PF, and incentive
                        $lpa = $result['salary'];
                        $salary = $lpa / 12;
                        $pf = $salary * 0.12; // 12% of the salary
                        $incomeTax = $salary * 0.10; // 10% of the salary

                        // Current month and year
                        $currentMonth = date('F');
                        $currentYear = date('Y');

                        // Display monthly salary, PF, and incentive
                        ?>
                        <div style="max-width: 600px; margin: 0 auto;">
                            <h5 class="fw-bold mb-3 text-center">Salary Details for <?php echo $currentMonth; ?>
                                <?php echo $currentYear; ?></h5>
                            <div class="table-responsive">
                                <table style="width: 100%; border-collapse: collapse; border: 2px solid #000;">
                                    <tbody>
                                        <tr>
                                            <th style="border: 2px solid #000; background-color: #f0f0f0; padding: 10px;">
                                                Description</th>
                                            <th style="border: 2px solid #000; background-color: #f0f0f0; padding: 10px;">
                                                Amount (₹)</th>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px;" class="fw-bold">Monthly
                                                Salary</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo number_format($salary, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px;" class="fw-bold">PF
                                                Deduction</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo number_format($pf, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px;" class="fw-bold">Income
                                                Tax</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo number_format($incomeTax, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px;" class="fw-bold">Total
                                                Deduction</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo number_format($pf + $incomeTax, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 2px solid #000; padding: 10px;" class="fw-bold">Net
                                                Salary</td>
                                            <td style="border: 2px solid #000; padding: 10px;"><?php echo number_format($salary - $pf - $incomeTax, 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php
                        } else {
                            echo "<p class='text-center'>No employee details found.</p>";
                        }
                    } else {
                        echo "<p class='text-center'>Invalid employee ID.</p>";
                    }
                    ?>
                  <div class="text-center hide-on-print">
    <a class="btn btn-primary" download onclick="showDownloadMessage(); printDocument();  hidePageTitleOnPrint();">
        Download PDF
    </a>
</div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/plugins/chart.js/chart.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script>
    // Function to show download success message
    function showDownloadMessage() {
        alert("Download successful!");
    }

    // Function to print the document
    function printDocument() {
        // Hide the header
        document.querySelector('.mn-header').style.display = 'none';

        // Print the document
        window.print();

        // Show the header again after printing
        document.querySelector('.mn-header').style.display = 'block';
    }

    // Function to hide the page title on print
    function hidePageTitleOnPrint() {
        // Hide the element with class 'hidethis'
        document.querySelector('.hidethis').style.display = 'none';

        // Print the document
        window.print();

        // Show the element with class 'hidethis' again after printing
        document.querySelector('.hidethis').style.display = 'block';
    }
    </script>

</body>

</html>
<?php } ?>
