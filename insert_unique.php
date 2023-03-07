<!--//////////////////////INSERT INTO RESULT///////////////////////////////-->
<?php
include "../lorenadb.php";
$term = $session = $class = $name = $subject = $fresult_id = $sessionName = " ";
$select = "--Select--";
// process form submission
if (isset($_POST['resultList'])) {
    if (empty($_POST['resultList']) || empty($_POST['subjectName'])) {
        echo "<script>sweetAlert('Error!', 'Select Subjects and Students!', 'error');</script>";
    }
    if (isset($_POST['resultList']) && isset($_POST['subjectName'])) {

        //get the submitted array
        $subjectName = $_POST['subjectName'];
        $listOfStudents = $_POST['resultList'];

        foreach ($listOfStudents as $oneStudent) {
            $id = $oneStudent['user_id'];
            $name = $oneStudent['name'];
            $class = $oneStudent['class'];
            $term = $_POST["term"];
            $session = $_POST["sessionName"];

            foreach ($subjectName as $subject) { /*loop over each item in $studentName.*/

                // test for duplicate by calling the function below with a name and subject and react accordingly
                if (check_for_duplicates($name, $subject) == false) {
                    // we are here because the 'check_for_duplicate' function returned false
                    $sql = "INSERT INTO results (user_id, name, class, term, sessionName, subject)
                            VALUES ('$id', '$name', '$class', '$term', '$session', '$subject')";
                    $result = $con->query($sql);
                    if ($result) {
                        header("location:subject_review_details.php?success_subject&class=$class");
                        //header("location:success_subject.php?update");
                    } else { $fail = '<div class="alert alert-danger" style="margin-top:5px"  id="success-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button><strong> ERROR! </strong> Subjects NOT updated!</div>';
                    }
                }
            }
        }
    }
}

function check_for_duplicates($student_name, $subject)
{
    $sql = "SELECT name, subject FROM results WHERE name = '" . $student_name . "' AND subject = '" . $subject . "'";
    // Execute the query where student name and subject already exists, there will be a result if it exists
    $result = $con->query($sql);

    if (mysql_num_rows($result)) {
        return true; // return TRUE to the caller of this function
    } else {
        return false; // no duplicate exists so return FALSE
    }
}

?>