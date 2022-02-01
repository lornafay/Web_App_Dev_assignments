<?php

include 'header.html'; 
require_once "dbconfig.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $myDB);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$allReps = "SELECT e.firstName, e.lastName, e.email, e2.firstName AS mgrFirst, e2.lastName AS mgrLast, o.* FROM employees e, employees e2, offices o WHERE e.officeCode = o.officeCode AND e.reportsTo=e2.employeeNumber AND e.jobTitle='Sales Rep' AND e2.jobTitle LIKE 'Sales Manager%'";
$result = $conn->query($allReps);

// function call from geeksforgeeks tutorial https://www.geeksforgeeks.org/how-to-call-php-function-on-the-click-of-a-button/ 
$buttonVal = $_GET["repButton"];
if (isset($buttonVal)) {
	showCustomers($buttonVal);
}

//function that fetches customer data for a given employee
function showCustomers($value) {
	global $conn;
	
	$customerArray = []; 
	$customerValueAll = [];
	$repValue = 0;
	
	$sql_getEmp = "SELECT c.customerName, c.customerNumber, c.addressLine1, c.city, c.country, c.postalCode, c.creditLimit, SUM(p.amount) AS totalReceived FROM employees e, customers c, payments p WHERE CONCAT(e.firstName, ' ', e.lastName)='{$value}' AND e.employeeNumber=c.salesRepEmployeeNumber AND c.customerNumber=p.customerNumber GROUP BY c.customerNumber;";
	
	$getCus = $conn->query($sql_getEmp);
	if($getCus->num_rows > 0) {
		echo "<div class='tableContainer'><table class='table' id='cusTable'><tr><th>{$value}'s Customers</th></tr>";
		while ($row = $getCus->fetch_assoc()) {
			$cusNum = $row["customerNumber"];
			$customerArray[] = $cusNum;
			$cusName = $row["customerName"];
			$cusAddress = $row["addressLine1"]."<br>".$row["city"]."<br>".$row["country"]."<br>".$row["postalCode"];
			$cusCredit = $row["creditLimit"];
			$cusPayments = $row["totalReceived"];
			echo "<tr><td><span class='attribute'>Name:</span> " .$cusName ."<br>";
			echo "<span class='attribute'>Address:</span> " .$cusAddress ."<br>";
			echo "<span class='attribute'>Credit limit:</span> " .$cusCredit ."<br>";
			echo "<span class='attribute'>Total payments received:</span> " .$cusPayments. "<br>";
			
			$sql_orders = "SELECT customerNumber, GROUP_CONCAT(orderNumber) as orderNums FROM orders WHERE customerNumber={$cusNum}";
			$getOrders = $conn->query($sql_orders);
			$cusOrders = "";
			if($getOrders->num_rows > 0) {
				while ($ordRow = $getOrders->fetch_assoc()) {
					$cusOrders = $ordRow["orderNums"];
					echo "Order history: " .$cusOrders . "<br></td></tr>";
				}
			
			} else {
			echo "Error fetching order numbers. " . $conn->error;
		}
			
		}
		echo "</table></div>";
	} else {
			echo "Error displaying customer data. " . $conn->error;
		}
	
	for ($i=0; $i<count($customerArray); $i++){
		$cus_sql = "SELECT o.orderNumber, o.customerNumber, SUM(od.quantityOrdered*od.priceEach) AS orderValue FROM orders o, orderdetails od WHERE o.orderNumber=od.orderNumber AND o.customerNumber={$customerArray[$i]}";
		
		$cus_result = $conn->query($cus_sql);
		if($cus_result->num_rows > 0) {
			$cusVal = 0;
			while ($cusRow = $cus_result->fetch_assoc()) {
				$cusVal += $cusRow["orderValue"];
			}
			$customerValueAll[] = $cusVal;
		} else {
			echo "Error fetching customer sales. " . $conn->error;
		}
	}
	
	for($j=0; $j<count($customerValueAll); $j++){
		$repValue += $customerValueAll[$j];
	}
	echo "<div id='repVal'><p id='repValue'> Total sales value of {$value}: <span id='important'>€{$repValue}</span></p></div>";
}



$conn->close();

?>
<html>
<head>
	
	<link rel="stylesheet" style="text/css" href="classic.css">
	
	<style>
		
		#repVal {
			display: flex;
			justify-content: center;
			margin: 40px 0px;
		}
		#repValue {
			color: #000000;
			font-size: 20px;
		}
	
	</style>
	
</head>
	
<body>
	<div class="tableContainer" id="repsTable">
		
		<?php
		
		//Re-using code from index.php

		if($result->num_rows > 0) {
			echo "<table class='table'><tr><th>Name</th><th>Email</th><th>Office address</th><th>Reporting sales manager</th></tr>";
			while ($row = $result->fetch_assoc()) {
				echo "<tr><td><form method='get'><input type='submit' name='repButton' value='" . $row["firstName"] . " " . $row["lastName"] . "'></form></td><td>" . $row["email"] . "</td><td>" . $row["addressLine1"] . "<br>" . $row["addressLine2"] . "<br>" . $row["city"] . "<br>" . $row["country"] . "<br>" . $row["postalCode"] . "</td><td>" . $row["mgrFirst"] . " " . $row["mgrLast"] . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "Error displaying sales reps data. " . $conn->error;
		}
		?>

	</div>
	
	
	<?php include 'footer.html'; ?>
	
</body>
</html>