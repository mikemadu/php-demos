<?php

/**
Assume our database table is called 'customer_order'
 */

//Initialize database
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
//Connect to database
if ($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']'); // If error, quit.
}

//incoming data are contained in the following variables
$customerID = $_POST['customerid'];
$productID = $_POST['productid'];
$qty = $_POST['quantity'];

// check if this customer already exists
$qry = " SELECT * FROM customer_order WHERE customerID = $customerID AND productID = $productID";
$result = $db->query($qry);

if ($result->num_rows < 1) {
    // Not found,  so create it
    $insertQry = "INSERT INTO customer_order (productID, quantity) VALUES ($productID, $quantity)";
    if ($db->query($insertQry)) {
        //No error. It was successfully inserted
       // return a message
        echo("Data inserted");
    }
    if ($db->errno) {
        //there was an error inserting, so announce it
        echo ('Error inserting data');
    }
    $db->close(); // close the connection

} else { // Found something ...
    //Get the rows, just in case they are more than one.
    //Loop over the existing rows to add up total
    $totalQty = 0;
    while ($row = $result->fetch_assoc()) {
        $totalQty = $totalQty + $row['quantity'];
    }
    //add incoming quantity to the total quantity already in the database
    $totalQty = $totalQty + $qty;
    //update the record in the database
    $updateQry = "UPDATE customer_order SET quantity = $totalQty WHERE customerID = $customerID AND productID = $productID";
   if ($db->query($updateQry)){
   echo("Record updated");
   }
    if ($db->errno) {
        //there was an error updating, so announce it
        echo ('Error updating data');
    }
    $db->close(); //close the connection

}
