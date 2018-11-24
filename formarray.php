<?php  include('dbpike.php');
        
    /* Assuming that at page load we are accepting some query strings and using them to query 'tbl_finalresult'
    */                            
    if (isset($_GET['term']) && isset($_GET['sessionName']) && isset($_GET['subjectName'])) {
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
    WHERE term = '$term' && sessionName = '$sessionName' && subject = '$subjectName'";
       $result = $con->query($sql);                                     
           if($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {?>
            <tr>
              <td> <input  type="hidden" name="id" readonly value="<? $row['fresult_id'] ?>" ></td>
              <td><input type="text" name="class" readonly value="<? $row['class'] ?>" ></td>
              <td> <input  type="text" name="name" readonly value="<? $row['name'] ?>"></td>
              <td> <input  type="text" name="subjectName" readonly value="<? $row['subject']?>"></td>
              <td> <input  type="text" name="assess" placeholder="Assessment"></td>
              <td> <input  type="text" name="exam" placeholder="Exam"></td>
            </tr>
            <?php
            }
        }else{ //no data found ?>
                    <tr><td colspan='6'>NO DATA FOUND</td>
        </tbody>
     </table>
     </form>
<?php  } //else

} // ---> if(isset($_GET['term'])) ...etc
else{ /*  */  

} 

/*
The functions below will help keep our code cleaner
//==================================================
*/
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
        }else {$remark = 'Poor';}
    return $remark;
    }

    //==============================================
    function computeGrade($assess, $exam){
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
                    $grade = 'F';
                }else{
                    $grade = 'F';
                }
        } 
        return $grade;
    }    

?>