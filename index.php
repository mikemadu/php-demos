<?php include('dbpike.php');
/*For the sake of this demo, lets make query string variable 
names different from POST variables*/ 
 $qs_class =  $qs_subject =  $qs_term =  $qs_session = '';
            if(isset($_GET['q_class']) && isset($_GET['q_subject']) && isset($_GET['q_term']) && isset($_GET['q_session'])){
                $qs_class = $_GET["q_class"];
                $qs_subject = $_GET["q_subject"];
                $qs_term = $_GET["q_term"];
                $qs_session = $_GET["q_session"];
            }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Test Multiselect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        
</head>

<body>
    <div class="card-body">
         <form  action="index.php" method="POST">
        <div class="col-md-12">
            <table class="table table-sm ">
                <tbody>
                    <tr>
                        <td>Class</td>
                        <td></td>
                        <td>
                            <input type="text" id="class" name="class" class="form-control" value='<?= $qs_class ?>'>
                        </td>
                    </tr>
                    <tr>
                        <td>Subject</td>
                        <td></td>
                        <td>
                            <input type="text" id="class" name="subject" class="form-control" value='<?= $qs_subject ?>'></td>
                    </tr>
                    <tr>
                        <td>Term</td>
                        <td></td>
                        <td>
                            <input type="text" id="class" name="term" class="form-control" value='<?= $qs_term?>'></td>
                    </tr>
                    <tr>
                        <td>Session</td>
                        <td></td>
                        <td><input type="text" id="class" name="session" class="form-control"  value='<?= $qs_session ?>'></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group  col-md-12">
            <label for="">Add Students to this Subject <span class="h6" style="color:red"><?php // echo $subject;?></span> <br>
                <span class="small">Hold down the "shift" key to multi-select.</span>
            </label>

            <select id="studentName" name="studentName[]" multiple="multiple" class="form-control">

                <?php 
                   // select all users already in the database and populate the multiselect box
                    $sql ="SELECT name FROM users";
                        $result = $con->query($sql);
                            while($row = $result->fetch_assoc()) {
                                $student = $row['name'];
                                echo "<option value = '". $student ."' >" .  $student. "</option>";
                            } 
                ?>
            </select>

         <?php
          if( isset($_POST['studentName'])){
              /*Create new variables to hold the form submissions. In a production app make sure inputs are 
              sanitized to prevent SQL-injection attacks. Especially public-facing pages*/
             $class = $_POST["class"];
             $term = $_POST["term"];
             $subject = $_POST["subject"];
             $session = $_POST["session"];
             $studentName = $_POST["studentName"]; //this post is already an array of student names   
                                
             //insert to database
             foreach ($studentName as $student){ /*loop over each item in $studentName. $student contains 
                the student name*/
                $sql ="INSERT INTO tbl_finalresult (studentName, class, term, session, subject)
                VALUES ('$student', '$class', '$term', '$session', '$subject')";
                $result = $con->query($sql); 
                if($result){
                     echo  ("<div class='alert alert-success' id='success-alert'>
                     <button type='button' class='close' data-dismiss='alert'>x</button>"
                     . $student . " added successfully!</div>");
                }

              } 
            } 
          ?>
        </div>
        <div class="col-md-12">
            <button type="submit" name="submit" class="btn btn-primary">Save Students</button>
        </div>
        <!-- Below is a link to supply query strings to this page for initial population of the text boxes-->
        <a href='http://localhost:8080/multiselect/index.php?q_class=Pearl&q_subject=Biology&q_term=Second&q_session=First'>Test Link</a>
         </form>
    </div>
   
</body>

</html>