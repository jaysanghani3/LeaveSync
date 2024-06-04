<?php
// Start the session and suppress error reporting
session_start();
error_reporting(0);

// Include database connection and configuration file
include('includes/config.php');

// Check if user is logged in, otherwise redirect to login page
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    // Check if 'id' parameter is provided in the URL
    if (isset($_GET['id'])) {
        // Retrieve the employee ID from the query string
        $id = $_GET['id'];

        // Generate the PDF content based on the provided employee ID
        $pdf_content = generatePDFContent($id, $dbh);

        // If PDF content is generated successfully
        if ($pdf_content !== false) {
            // Set the appropriate headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="salary_slip.pdf"');
            header('Content-Length: ' . strlen($pdf_content));

            // Output the PDF content
            echo $pdf_content;

            // Ensure no further output is sent
            exit();
        } else {
            // Handle the case when PDF generation fails
            error_log("Error: Failed to generate PDF for ID: $id");
            echo "Error: Failed to generate PDF for ID: $id";
            exit();
        }
    } else {
        // Handle the case when 'id' parameter is not provided
        echo "Error: 'id' parameter is missing.";
        exit();
    }
}

// Function to generate PDF content based on the provided employee ID
function generatePDFContent($id, $dbh)
{
    // Fetch employee details based on the provided ID
    $sql = "SELECT * FROM tblemployees WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // If employee details are found, generate PDF content
    if ($result) {
        // Start output buffering to capture HTML content
        ob_start();
?>
        <div>
            <!-- Employee Details -->
            <h2>Salary Slip</h2>
            <h4>Employee Details</h4>
            <p><strong>First Name:</strong> <?php echo $result['FirstName']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $result['LastName']; ?></p>
            <p><strong>Email:</strong> <?php echo $result['EmailId']; ?></p>
            <p><strong>Department:</strong> <?php echo $result['Department']; ?></p>
            <!-- Salary Details -->
            <?php
            // Calculate salary, PF, and income tax
            $lpa = $result['salary'];
            $salary = $lpa / 12;
            $pf = $salary * 0.12; // 12% of the salary
            $incomeTax = $salary * 0.10; // 10% of the salary
            ?>
            <h5 class="fw-bold mb-3 text-center">Salary Details for <?php echo date('F Y'); ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered border border-2 border-dark">
                    <tbody>
                        <tr>
                            <th class="border border-2 border-dark">Description</th>
                            <th class="border border-2 border-dark">Amount (₹)</th>
                        </tr>
                        <tr>
                            <td class="border border-2 border-dark fw-bold">Monthly Salary</td>
                            <td class="border border-2 border-dark"><?php echo number_format($salary, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-2 border-dark fw-bold">PF Deduction</td>
                            <td class="border border-2 border-dark"><?php echo number_format($pf, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-2 border-dark fw-bold">Income Tax</td>
                            <td class="border border-2 border-dark"><?php echo number_format($incomeTax, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-2 border-dark fw-bold">Total Deduction</td>
                            <td class="border border-2 border-dark"><?php echo number_format($pf + $incomeTax, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-2 border-dark fw-bold">Net Salary</td>
                            <td class="border border-2 border-dark"><?php echo number_format($salary - $pf - $incomeTax, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php
        // Get the buffered HTML content
        $pdf_content = ob_get_clean();

        return $pdf_content;
    } else {
        // If employee details are not found, return false
        return false;
    }
}
?>
