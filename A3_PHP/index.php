<?php

require_once 'dbconfig.php';
	
// Create connection
$conn = new mysqli($servername, $username, $password, $myDB);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Code involved in filtering by product line
$pLines = $conn->query("SELECT DISTINCT productLine FROM products");

/* Method for initializing as empty, and then assigning drop-down selection value to variables idea from stackoverflow post by user Johnny1998 https://stackoverflow.com/questions/51105392/dynamic-php-table-filter-and-sort
*/
$pLine_selected = "";
$qty_sorted = "";
$qty_max = "";
if (isset($_GET['submit'])) {
	$pLine_selected = $_GET['pLines_menu'];
	$qty_sorted = $_GET['qty_sort'];
	$qty_max = $_GET['qty_max'];
	}

// Modifying the central query depending on user's filtering choices

// First create the segments of each type of query with the fetched POST value
$all = "SELECT * FROM products";
$pLineFilt_query = " WHERE productLine='" . $pLine_selected . "'";
$sorted_query = " ORDER BY quantityInStock " . $qty_sorted;
$max_query = " WHERE quantityInStock<=" . $qty_max;


// the following is a series of conditional statements that result in the appropriate query construction based on user preferences, using the above variables to construct the query

// if nothing has been selected (default page load)
if (empty($pLine_selected) && empty($qty_sorted) && empty($qty_max)) {
	$sql = $all;
} else{
	// if ONLY productLine filter has been used
	if (!empty($pLine_selected) && $qty_sorted == "unspecified" && empty($qty_max)) {
		if ($pLine_selected != "unspecified"){
		$sql = $all . $pLineFilt_query;
		} else {
			$sql = $all;
		}
	// if ONLY sorting by ascending/descending QIS order
	} elseif ($pLine_selected == "unspecified" && $qty_sorted != "unspecified" && empty($qty_max)) {
		// this will call the sorted_query query in ASC or DESC order depending on what has been selected
		$sql = $all . $sorted_query;
	// if ONLY filtering by a max value for quantityInStock
	} elseif ($pLine_selected == "unspecified" && $qty_sorted == "unspecified" && !empty($qty_max)) {
		$sql = $all . $max_query;
	// if ONLY filter by productLine and sort by ASC/DESC are selected
	} elseif ($pLine_selected != "unspecified" && $qty_sorted != "unspecified" && empty($qty_max)) {
		$sql = $all . $pLineFilt_query . $sorted_query;
	// if ONLY filter by productLine and max value for QIS are selected
	} elseif ($pLine_selected != "unspecified" && $qty_sorted == "unspecified" && !empty($qty_max)) {
		$sql = $all . $pLineFilt_query . " AND quantityInStock<=" . $qty_max;
	// if ONLY sort by ASC/DESC and max value for QIS are selected
	} elseif ($pLine_selected == "unspecified" && $qty_sorted != "unspecified" && !empty($qty_max)) {
		$sql = $all . $max_query . $sorted_query;
	// if ALL fields have been specified
	} elseif ($pLine_selected != "unspecified" && $qty_sorted != "unspecified" && !empty($qty_max)) {
		$sql = $all . $pLineFilt_query . " AND quantityInStock<=" . $qty_max . $sorted_query;
	}
}

$result = $conn->query($sql);

$conn->close();

?>


<html>
<head>
	
	<link rel="stylesheet" style="text/css" href="classic.css">
	
</head>
<body>
	<div class="container">
		
	<?php include 'header.html'; ?>
	
	<div class="form" id="refine">
		
		<form method="get">	
			<label>Filter by product line:</label>
			<select name='pLines_menu'>
			<option value="unspecified">All</option>
			
			<!--use PHP to create a dropdown menu from the product lines found in the database - this way the product lines will update should the database be changed-->
				
			<?php 
			// method to populate drop-down found at https://www.youtube.com/watch?v=TNPxG2yrPlM
			while($rows = $pLines->fetch_assoc()) {
				$pLine_name = $rows['productLine'];
				echo "<option value='$pLine_name'>$pLine_name</option>";
			} ?>
			</select>
			
			<!--create sorting dropdown menu-->
			<label>Filter by by quantity in stock:</label>
			<select name="qty_sort">
			<option value="unspecified">Sort</option>
			<option value="ASC">Ascending</option>
			<option value="DESC">Descending</option>
			</select>
			
			<!--create input field for price specification-->
			<input type="text" name="qty_max" placeholder="max quantity in stock">
			<input type="submit" name="submit" value="find products">
		</form>	
		
	</div>
	<div class="tableContainer">
		
		<!--Results of the table query applied here-->
		<?php
		
		if($result->num_rows > 0) {
			
			echo "<table class='table'><tr><th>Name</th><th>Code</th><th>Description</th><th>Quantity in stock</th><th>MSRP</th></tr>";
			
			while ($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["productName"] . "</td><td>" . $row["productCode"] . "</td><td>" . $row["productDescription"] . "</td><td>" . $row["quantityInStock"] . "</td><td>" . $row["MSRP"] . "</td></tr>";
			}
			
			echo "</table>";
		} else {
			echo "Error displaying product info." . $conn->error;
		}
		?>

	</div>
	</div>
	<?php include 'footer.html'; ?>
	
</body>
</html>