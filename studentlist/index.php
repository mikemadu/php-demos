
<?php

ini_set('display_errors', '1'); //comment this out for Production
require_once 'db_config.php'; // contains database connection string
$con = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($con->connect_errno > 0) {
    die('Unable to connect to database [' . $con->connect_error . ']');
}
/** We will query the database twice. First to get the unique iDs of the students
 * The WHERE clause was removed from the query below for simplicity.
 * The WHERE clause must be the same for the two queries below
 */
$sql_unique_ids = 'SELECT DISTINCT user_id FROM results';
/** Second query will get the whole fields */
$sql_details = "SELECT user_id, name, subject, assess, exam, total, obtainable, subjectRank, grade, gpa, remark, subAvg  FROM results  ORDER BY name ";

//Query for IDs of the students that you want to filter out of the database.
$result_unique_id = $con->query($sql_unique_ids);
//Array to hold the unique ids
$iDs = array();
while ($row = $result_unique_id->fetch_assoc()) {
//push each row into the array; in this case each ID
    array_push($iDs, $row['user_id']); // $iDs will contain [179, 180, 181] after the WHILE loop
}

//Next query for whole students details
$desired_result = $con->query($sql_details);
//We will need to get the results into an array
$result_array = array();
while ($row = $desired_result->fetch_assoc()) {
    //make a new row array
    $one_row                = array(); // each of this will contain a row of the results
    $one_row['user_id']     = $row['user_id'];
    $one_row['subjectRank'] = $row["subjectRank"];
    $one_row['name']        = $row["name"];
    $one_row['subject']     = $row["subject"];
    $one_row['assess']      = $row["assess"];
    $one_row['exam']        = $row["exam"];
    $one_row['total']       = $row["total"];
    $one_row['subAvg']      = $row["subAvg"];
    $one_row['obtainable']  = $row["obtainable"];
    $one_row['gpa']         = $row["gpa"];
    $one_row['remark']      = $row["remark"];
    $one_row['grade']       = $row["grade"];

    //push the $one_row variable into our final results array.
    //This will be done as many times as there are rows in $desired_result
    array_push($result_array, $one_row);
}
/* By now the $result_array has all our students. Next we use the unique iDs to loop over
the results array and produce our final HTML that will be displayed*/
foreach ($iDs as $oneID) {
    ?>

<div class="col-md-12 mt-2" style='margin-bottom:40px;'>
  <div class="table-responsive">
  <table class="table table-bordered table-sm">
      <thead style="background-color:#f0f0f0">
        <tr>
        <th style="width:60%">
        <div style="padding-top:70px">Name</div>
        </th>
        <th style="width:60%">
        <div style="padding-top:70px">Subjects</div>
        </th>
        <th>
        <div class="vertical">CAT(40%)</div>
        </th>
        <th>
        <div class="vertical">Exam(60%)</div>
        </th>
        <th>
        <div class="vertical">Total(100%)</div>
        </th>
        <th>
        <div class="vertical">Subject Class Avg</div>
        </th>
        <th>
        <div class="vertical">Mrks Obtainable</div>
        </th>
        <th>
        <div class="vertical">Subject Position</div>
        </th>
        <th>
        <div class="vertical">Grade</div>
        </th>
        <th>
        <div class="vertical">G.P.A</div>
        </th>
        <th>
        <div style="padding-top:70px">Remarks</div>
        </th>
        </tr>
      </thead>

         <?php
//We loop over the result array and display only the records that the IDs match the ID ($oneID) in the main outer loop
    foreach ($result_array as $val) {
        // $val is each row in the result array
        if ($val['user_id'] == $oneID) { //compare the iD
            $rank = $val["subjectRank"];
            ?>
     <tbody>
    <tr style='background-color:#f2f1f1'>
    <td><?php echo $val['name']; ?></td>
  <td><?php echo $val['subject']; ?></td>
  <td><?php echo $val['assess']; ?></td>
  <td><?php echo $val['exam']; ?></td>
  <td><?php if ($val['total'] <= 39) {echo "<span class='text-danger'>" . $val['total'] . "</span>";} else {echo "<span style='color:#12b512'><b>" . $val['total'] . "</b></span>";} ?></td>
  <td><?php echo round($val['subAvg']); ?></td>
  <td><?php echo $val['obtainable']; ?></td>
  <td><?php echo $rank; ?><?php if ("1" == $rank) {echo "st";} elseif ("2" == $rank) {echo "nd";} elseif ("3" == $rank) {echo "rd";} elseif ("" == $rank) {echo "";} else {echo "th";} ?></td>
  <td><?php echo $val['grade']; ?></td>
  <td><?php echo $val['gpa']; ?></td>
  <td><?php echo $val['remark']; ?></td>
  </tr>
  </tbody>
 <?php
} // comparison
    } // end inner loop ?>
  </table>
</div>
</div>
<?php

} //end of outer loop