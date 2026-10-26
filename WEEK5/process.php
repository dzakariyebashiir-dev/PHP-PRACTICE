<?php
require_once "functions.php";

// Escape output before showing user-provided values in HTML.
function safeOutput($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "Please submit the registration form first. <a href='index.html'>Back to form</a>";
    exit;
}

// Read form fields sent using POST.
$employeeName = trim($_POST["employee_name"] ?? "");
$employeeId = trim($_POST["employee_id"] ?? "");
$gender = $_POST["gender"] ?? "";
$department = $_POST["department"] ?? "";
$employmentType = $_POST["employment_type"] ?? "";
$basicInput = $_POST["basic_salary"] ?? "";
$allowanceInput = $_POST["allowance"] ?? "";
$deductionInput = $_POST["deduction"] ?? "";
$yearsInput = $_POST["years_service"] ?? "";

$errorMessage = "";
$errorCount = 0;

// Validate required text and choice fields.
if (empty($employeeName)) {
    $errorMessage .= "<li>Employee full name is required.</li>";
    $errorCount++;
}
if (empty($employeeId)) {
    $errorMessage .= "<li>Employee ID is required.</li>";
    $errorCount++;
}
if (empty($gender)) {
    $errorMessage .= "<li>Please select gender.</li>";
    $errorCount++;
} elseif ($gender != "Male" && $gender != "Female") {
    $errorMessage .= "<li>Please select a valid gender.</li>";
    $errorCount++;
}

if (empty($department)) {
    $errorMessage .= "<li>Please select a department.</li>";
    $errorCount++;
} elseif ($department != "Human Resources" && $department != "Finance"
    && $department != "Information Technology" && $department != "Administration") {
    $errorMessage .= "<li>Please select a valid department.</li>";
    $errorCount++;
}

if (empty($employmentType)) {
    $errorMessage .= "<li>Please select an employment type.</li>";
    $errorCount++;
} elseif ($employmentType != "Full-Time" && $employmentType != "Part-Time") {
    $errorMessage .= "<li>Please select a valid employment type.</li>";
    $errorCount++;
}

// Validate numeric fields. String "0" is allowed for the optional amounts and years.
if ($basicInput === "" || !is_numeric($basicInput) || (float)$basicInput <= 0) {
    $errorMessage .= "<li>Basic salary must be a number greater than zero.</li>";
    $errorCount++;
}
if ($allowanceInput === "" || !is_numeric($allowanceInput) || (float)$allowanceInput < 0) {
    $errorMessage .= "<li>Allowance must be zero or a positive number.</li>";
    $errorCount++;
}
if ($deductionInput === "" || !is_numeric($deductionInput) || (float)$deductionInput < 0) {
    $errorMessage .= "<li>Deduction must be zero or a positive number.</li>";
    $errorCount++;
}
if ($yearsInput === "" || !is_numeric($yearsInput) || (float)$yearsInput < 0
    || floor((float)$yearsInput) != (float)$yearsInput) {
    $errorMessage .= "<li>Years of service must be a whole number that is zero or more.</li>";
    $errorCount++;
}

// Only calculate salaries when the numeric values are valid.
$numbersAreValid = ($basicInput !== "" && is_numeric($basicInput) && (float)$basicInput > 0
    && $allowanceInput !== "" && is_numeric($allowanceInput) && (float)$allowanceInput >= 0
    && $deductionInput !== "" && is_numeric($deductionInput) && (float)$deductionInput >= 0
    && $yearsInput !== "" && is_numeric($yearsInput) && (float)$yearsInput >= 0
    && floor((float)$yearsInput) == (float)$yearsInput);

if ($numbersAreValid && ($employmentType == "Full-Time" || $employmentType == "Part-Time")) {
    $basicSalary = (float)$basicInput;
    $allowance = (float)$allowanceInput;
    $deduction = (float)$deductionInput;
    $yearsOfService = (int)$yearsInput;

    $grossSalary = calculateGrossSalary($basicSalary, $allowance);
    $bonus = calculateBonus($basicSalary);
    $serviceBenefit = calculateServiceBenefit($basicSalary, $yearsOfService);
    $employmentAllowance = calculateEmploymentAllowance($basicSalary, $employmentType);
    $totalBenefits = calculateTotalBenefits($bonus, $serviceBenefit, $employmentAllowance);

    if ($deduction > ($grossSalary + $totalBenefits)) {
        $errorMessage .= "<li>Deduction cannot exceed gross salary plus total benefits.</li>";
    $errorCount++;
    }

    $netSalary = calculateNetSalary($grossSalary, $totalBenefits, $deduction);
    $salaryStatus = checkSalaryStatus($netSalary);
    $annualSalary = calculateAnnualSalary($netSalary);
}

// Validate uploaded document.
$fileReady = isset($_FILES["employee_document"])
    && $_FILES["employee_document"]["error"] == UPLOAD_ERR_OK;

if (!isset($_FILES["employee_document"]) || $_FILES["employee_document"]["error"] == UPLOAD_ERR_NO_FILE) {
    $errorMessage .= "<li>Please select an employee document.</li>";
    $errorCount++;
} elseif ($_FILES["employee_document"]["error"] != UPLOAD_ERR_OK) {
    $errorMessage .= "<li>The document could not be uploaded. Please try again.</li>";
    $errorCount++;
} elseif ($_FILES["employee_document"]["size"] > 2 * 1024 * 1024) {
    $errorMessage .= "<li>The document must not be larger than 2 MB.</li>";
    $errorCount++;
}

// Upload only if form validation has no errors.
$uploadName = "";
$uploadType = "";
$uploadSize = 0;
$uploadStatus = "Not uploaded";

if ($errorCount == 0 && $fileReady) {
    $uploadName = basename($_FILES["employee_document"]["name"]);
    $uploadType = $_FILES["employee_document"]["type"];
    $uploadSize = $_FILES["employee_document"]["size"];
    $temporaryLocation = $_FILES["employee_document"]["tmp_name"];
    $uploadFolder = __DIR__ . "/uploads/";

    // Make sure the uploads folder exists.
    if (!is_dir($uploadFolder)) {
        if (!mkdir($uploadFolder, 0755, true)) {
            $errorMessage .= "<li>Could not create the uploads folder.</li>";
    $errorCount++;
        }
    }

    // Do not overwrite an existing document with the same name.
    $destination = $uploadFolder . $uploadName;
    if ($errorCount == 0 && file_exists($destination)) {
        $errorMessage .= "<li>A file with this name already exists. Rename your document and try again.</li>";
    $errorCount++;
    }

    if ($errorCount == 0) {
        if (move_uploaded_file($temporaryLocation, $destination)) {
            $uploadStatus = "Upload successful";
        } else {
            $errorMessage .= "<li>The document could not be saved.</li>";
    $errorCount++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration Result</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f5f9; padding: 25px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        h1 { color: #203a5f; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #cbd3df; padding: 10px; text-align: left; }
        th { background: #eaf0f8; width: 38%; }
        .error { background: #fff0f0; border: 1px solid #e4aaaa; padding: 12px; color: #8b1c1c; }
        .success { color: #176b35; }
        a { display: inline-block; margin-top: 20px; color: #2457a7; }
    </style>
</head>
<body>
<div class="container">
    <?php if ($errorCount > 0) { ?>
        <h1>Registration Errors</h1>
        <div class="error">
            <p>Please correct the following issue(s):</p>
            <ul>
                <?php
                // The error messages are stored in one string, without a custom array or loop.
                echo $errorMessage;
                ?>
            </ul>
        </div>
        <a href="index.html">Back to Registration Form</a>
    <?php } else { ?>
        <h1>Employee Registration Successful</h1>
        <p class="success">The employee information has been validated and the document was uploaded.</p>
        <table>
            <tr><th>Employee Name</th><td><?php echo safeOutput($employeeName); ?></td></tr>
            <tr><th>Employee ID</th><td><?php echo safeOutput($employeeId); ?></td></tr>
            <tr><th>Gender</th><td><?php echo safeOutput($gender); ?></td></tr>
            <tr><th>Department</th><td><?php echo safeOutput($department); ?></td></tr>
            <tr><th>Employment Type</th><td><?php echo safeOutput($employmentType); ?></td></tr>
            <tr><th>Years of Service</th><td><?php echo $yearsOfService; ?></td></tr>
            <tr><th>Basic Salary</th><td>$<?php echo number_format($basicSalary, 2); ?></td></tr>
            <tr><th>Allowance</th><td>$<?php echo number_format($allowance, 2); ?></td></tr>
            <tr><th>Gross Salary</th><td>$<?php echo number_format($grossSalary, 2); ?></td></tr>
            <tr><th>Bonus</th><td>$<?php echo number_format($bonus, 2); ?></td></tr>
            <tr><th>Service Benefit</th><td>$<?php echo number_format($serviceBenefit, 2); ?></td></tr>
            <tr><th>Employment Allowance</th><td>$<?php echo number_format($employmentAllowance, 2); ?></td></tr>
            <tr><th>Total Benefits</th><td>$<?php echo number_format($totalBenefits, 2); ?></td></tr>
            <tr><th>Deduction</th><td>$<?php echo number_format($deduction, 2); ?></td></tr>
            <tr><th>Net Salary</th><td>$<?php echo number_format($netSalary, 2); ?></td></tr>
            <tr><th>Salary Status</th><td><?php echo safeOutput($salaryStatus); ?></td></tr>
            <tr><th>Annual Salary</th><td>$<?php echo number_format($annualSalary, 2); ?></td></tr>
            <tr><th>Uploaded Document Name</th><td><?php echo safeOutput($uploadName); ?></td></tr>
            <tr><th>Document Type</th><td><?php echo safeOutput($uploadType); ?></td></tr>
            <tr><th>Document Size</th><td><?php echo number_format($uploadSize / 1024, 2); ?> KB</td></tr>
            <tr><th>Upload Status</th><td><?php echo safeOutput($uploadStatus); ?></td></tr>
        </table>
        <a href="index.html">Back to Registration Form</a>
    <?php } ?>
</div>
</body>
</html>
