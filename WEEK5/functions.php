<?php
// Calculate gross salary by adding basic salary and allowance.
function calculateGrossSalary($basicSalary, $allowance) {
    return $basicSalary + $allowance;
}

// Calculate bonus using the basic salary.
function calculateBonus($basicSalary) {
    if ($basicSalary >= 1000) {
        return $basicSalary * 0.10;
    } elseif ($basicSalary >= 500) {
        return $basicSalary * 0.05;
    } else {
        return 0;
    }
}

// Calculate service benefit using years of service.
function calculateServiceBenefit($basicSalary, $yearsOfService) {
    if ($yearsOfService >= 5) {
        return $basicSalary * 0.08;
    } elseif ($yearsOfService >= 3) {
        return $basicSalary * 0.05;
    } else {
        return 0;
    }
}

// Calculate allowance based on employment type.
function calculateEmploymentAllowance($basicSalary, $employmentType) {
    if ($employmentType == "Full-Time") {
        return $basicSalary * 0.06;
    } elseif ($employmentType == "Part-Time") {
        return $basicSalary * 0.03;
    } else {
        return 0;
    }
}

// Add bonus, service benefit, and employment allowance.
function calculateTotalBenefits($bonus, $serviceBenefit, $employmentAllowance) {
    return $bonus + $serviceBenefit + $employmentAllowance;
}

// Calculate net salary after deduction.
function calculateNetSalary($grossSalary, $totalBenefits, $deduction) {
    return $grossSalary + $totalBenefits - $deduction;
}

// Classify salary using net salary.
function checkSalaryStatus($netSalary) {
    if ($netSalary >= 1500) {
        return "High Salary";
    } elseif ($netSalary >= 800) {
        return "Medium Salary";
    } else {
        return "Low Salary";
    }
}

// The default parameter means 12 months is used if no second argument is given.
function calculateAnnualSalary($netSalary, $months = 12) {
    return $netSalary * $months;
}
?>
