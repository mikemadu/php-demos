<?php  include('dbpike.php');?>
<!-- Below is a link to supply query strings to this page for initial population of the text boxes-->
        <a href='http://localhost:8080/multiselect/formarray.php?term=Second&subjectName=Biology&sessionName=First'>Test Link (Click to start)</a>
<?php       
    /* BEGINNING OF QUERY STRING REQUESTS
    Assuming that at page load we are accepting some query strings and using them to query 'tbl_finalresult'
    */                            
    if (isset($_GET['term']) && isset($_GET['sessionName']) && isset($_GET['subjectName'])) {
    //Get the query strings into variables. These will be used as search criteria
    $term = $_GET['term'];
    $sessionName = $_GET['sessionName'];
    $subjectName = $_GET['subjectName'];
 ?>  
    <form action='formarray.php' method='post'>
     <table>
        <thead>
         <tr>
           <th></th>
           <th>Class</th>
           <th>Name</th>
           <th>Subject</th>
           <th>Quiz (40%)</th>
           <th>Exam (60%)</th>
         </tr>
        </thead>
        <tbody>
<?php
    $sql = "SELECT * FROM tbl_finalresult 
    WHERE term = '$term' AND session = '$sessionName' 
    AND subject = '$subjectName'  
    ORDER BY fresult_id, class, subject, studentName";
       $result = $con->query($sql);                                     
           if($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {
?>
            <tr>
            <!-- While writing out the HTML form, we make an array called 'resultList' for each row read from the database -->
              <td> <input  type="hidden" name = "resultList[<?php echo $row['fresult_id']?>][fresult_id]" readonly value="<?php echo $row['fresult_id'] ?>" ></td>
              <td> <input type="text" name = "resultList[<?php echo $row['fresult_id'] ?>][class]" readonly value="<?php echo  $row['class'] ?>" ></td>
              <td> <input  type="text" name = "resultList[<?php echo $row['fresult_id'] ?>][name]" readonly value="<?php echo  $row['studentName'] ?>"></td>
              <td> <input  type="text" name = "resultList[<?php echo $row['fresult_id'] ?>][subjectName]" readonly value="<?php echo  $row['subject']?>"></td>
              <td> <input  type="text" name = "resultList[<?php echo $row['fresult_id'] ?>][assess]" placeholder="Assessment"  value="<?php echo  $row['assess']?>"></td>
              <td> <input  type="text" name = "resultList[<?php echo $row['fresult_id'] ?>][exam]" placeholder="Exam" value="<?php echo  $row['exam']?>" ></td>
            </tr>
<?php
            } //End: while .... 
?>
            <tr><td colspan='5'></td>
            <td><input type ='submit' value='UPDATE MARKS'/></td>
            </tr>
            </tbody>
     </table>
     </form>
<?php
        } //End: if($result->num_rows) ...etc
        else //no data found from the SQL query above
        { 
?>
          <tr><td colspan='6'>NO DATA FOUND</td></tr>
        </tbody>
     </table>
     </form>
<?php  } //End: no data found

} // End:  if(isset($_GET['term'])) ...etc

else{ /*BEGINNING OF FORM SUBMISSION ============================================================================================
    We are posting back to this page for processing.
     If we are posting to another page instead of this one, we will have the code starting from this place onwards in that form*/  
if(is_array($_POST) && isset( $_POST['resultList'])){
    $id=$assessment=$exam_result="";
    $listOfStudents;
    //get the submitted array
    $listOfStudents = $_POST['resultList'];
    $successMessage="";
    //loop thru each ...
    foreach ($listOfStudents as $oneStudent) {
        $id = $oneStudent['fresult_id'];

       $assessment = $oneStudent['assess'];
       $assessment =  floatval($assessment);//make sure it is a number coming in. Evaluate to floating point number

       $exam_result = $oneStudent['exam'];
       $exam_result = floatval($exam_result);//make sure it is a number coming in

        //Notice that we don't even need to read the 'name', 'class', 'subjectName' fields since we are not updating them.
        if($assessment != 0 && $exam_result != 0){//only do updates if $assessment and $exam_result contain valid inputs
            $total = $assessment + $exam_result;
            
            $grade = computeGrade($assessment, $exam_result);//Using the function below, we get the grade
            $remark = computeRemark($total, $grade); //Using the function below , get the remark
            //Prepare a SQL statement for updating the database 
            $sql ="UPDATE tbl_finalresult SET total = $total, assess = $assessment, exam = $exam_result, grade = '$grade',remark = '$remark' 
            WHERE fresult_id = $id";
            $result = $con->query($sql);
            if($result){
                $successMessage = $successMessage . $oneStudent['name'] . " successfully updated <br>";
           }
        }//End: if($assessment ....)   

    }//End: foreach
   // echo $successMessage;//<------- uncomment to see message


}//End: if(isset())

}//End: form submission 
//Display a list of students
?>

 <h4>List of Students</h4>
 <style>
 .result{
     display:grid;grid-gap:2px;
     grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr 1fr
 }
 .bx{
     padding:3px;height:auto;border-radius:2px;border:1px solid #999;*/
 }
 </style>
    <ul style='display:block;width :60%;list-style:none'>
    <li style="">
    <div class='result'>                     
              <div class='bx'><b>Class</b></div>
              <div class='bx'><b>Name</b></div>
              <div class='bx'><b>Subject</b></div>
              <div class='bx'><b>Assessment</b></div>
              <div class='bx'><b>Exam</b></div>
              <div class='bx'><b>Grade</b></div>
              <div class='bx'><b>Remark</b></div>         
            </div>
    </li>
<?php   
      $sql = "SELECT * FROM tbl_finalresult ORDER BY fresult_id, class, subject,studentName";
       $result = $con->query($sql);                                     
           if($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {
?>
        <li>
            <div class='result'>                     
              <div class='bx'> <?php echo $row['class'] ?> </div>
              <div class='bx'> <?php echo $row['studentName'] ?></div>
              <div class='bx'> <?php echo $row['subject']?></div>
              <div class='bx'> <?php echo $row['assess'] ?></div>
              <div class='bx'> <?php echo $row['exam'] ?></div>
              <div class='bx'> <?php echo $row['grade'] ?></div>
              <div class='bx'> <?php echo $row['remark'] ?></div>         
            </div>
        </li>
<?php
               }//End: while
            }//End: if($result->num_rows > 0)
?>            
</ul>
<?php

/*
The functions below will help keep our code cleaner
//==================================================
*/
    //This function takes in a total and grade to return a remark
    function computeRemark($total, $grade){
            //check for remarks   
        if ($total >=100 || $grade >= 90){ 
        $remark = "Excellent";
        }else if ($total >=89 || $grade >= 75){ 
        $remark = "Good";
        }else if ($total >=74 || $grade >= 60){ 
        $remark = "Satisfactory";
        }else if ($total >=59 || $grade >= 50){ 
        $remark = "Unsatisfactory";
        }else if ($total >=49 || $grade >= 0){ 
        $remark = "Poor";
        }
    return $remark;
    }

    //==============================================
    //This function takes two parameters, assess and exam to return grade
    function computeGrade($assess, $exam){
        $grade="";
        if(is_numeric($assess) && is_numeric($exam)){
                if ($assess + $exam >=95 && $assess + $exam <=100){
                    $grade = 'A+';  
                }else if($assess + $exam  >=90 && $assess + $exam<=94){
                    $grade = 'A';  
                }else if($assess + $exam >=85 && $assess + $exam <=89){
                    $grade = 'A-';  
                }else if($assess + $exam >=80 && $assess + $exam <=84){
                    $grade = 'B+';  
                }else if($assess + $exam  >=75 && $assess + $exam <=79){
                    $grade = 'B';  
                }else if($assess + $exam >=70 && $assess + $exam <=74){
                    $grade = 'B-';  
                }else if($assess + $exam >=65 && $assess + $exam <=69){
                    $grade = 'C+';  
                }else if($assess + $exam >=60 && $assess + $exam  <=64){
                    $grade = 'C';  
                }else if($assess + $exam >=55 && $assess + $exam  <=59){
                    $grade = 'C-';  
                }else if($assess + $exam >=50 && $assess + $exam <=54){
                    $grade = 'D';  
                }else if($assess + $exam >=40 && $assess + $exam  <=49){
                    $grade = 'E';  
                }else if($assess + $exam >=30 && $assess + $exam  <=39){
                    $grade = 'F';  
                }else if($assess + $exam >=0 && $assess + $exam  <=29){
                    $grade = 'F';}               
        } 
         return $grade;
    }    

?>
