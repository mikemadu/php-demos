
<?php include('dbpike.php');
  $srch_day='';//Initialize these two variables
  $srch_month='';
          if( isset($_POST['name'])){// We are writing a new record
              /*Get posted data*/
             $name = $_POST["name"];
             $year = $_POST["year"];
             $month = $_POST["month"];
             $day = $_POST["day"];
             if($name == '' || $year == '' || $day == '' || $month ==''){
                 echo ("Invalid Inputs");die;
             }
            $birthday = $year . "-" . $month . "-" . $day; //format the date before we insert into database
                                
             //insert to database
                $sql ="INSERT INTO users (name, dob)
                VALUES ('$name', '$birthday')";
                $result = $con->query($sql);  

            } else if(isset($_POST['search'])){// We are searching. This is a hidden field in the form
             $srch_day = $_POST["srch_day"];
             $srch_month = $_POST["srch_month"];
            }
              
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Test Multiselect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <style>
     .lbl{
         font-style:italic;
         font-size:.9em;
     }
     .search{
         display:grid;grid-template-columns:1fr 2fr; 
         grid-row-gap:4px;
         padding:10px;margin:5px;border:1px solid #999;border-radius:5px;
     }
     </style>   
</head>

<body>
<div>
<h1 style='text-align:center'>Date of Birth Filter</h1>
  <form  action="dateofbirth.php" method="POST">
  <div  style='display:grid; grid-template-columns:1fr 1fr 1fr;width:50%;margin:10px auto;'>
  <div style='grid-column:1/4'><label>Name:</label><br>
   <input type="text" id="name" name="name" required placeholder='-- name' >
   </div>
   <div style='grid-column:1/4; margin:10px 0 0 0'><b>Date of Birth:</b></div>
  <div>
  <label class='lbl'>Month:</label><br>
  <input type="text" id="month" name="month" required  placeholder='-- month (numbers only)' >
  </div>
  <div>
  <label class='lbl'>Day:</label><br>
  <input type="text" id="day" name="day" required  placeholder='-- day' >
  </div>
  <div>
  <label class='lbl'>Year:</label><br>
  <input type="text" id="year" name="year" required placeholder='-- year' >
  </div>
  <div style ='margin-top:20px;'>
  <input type='submit' value='Add To List' />&nbsp;<button type='reset'>Reset</button>
  </div>
  </div>
   </form>
   <hr>
   <div style='display:grid; grid-template-columns:50% 50%;width:50%;margin:20px auto;'>
        <div style='padding:5px'>
        <h4>Unfiltered List</h4>
           <?php  // select all users in the database
                    $sql ="SELECT name, dob FROM users";
                        $result = $con->query($sql);
                            while($row = $result->fetch_assoc()) {
                                $name = $row['name'];
                                  $dob = $row['dob'];
                                  if($dob != NULL){//check for NULL
                                    $date = date_create($dob); //convert from MySQL date format to PHP date  
                                     //so we can display it in a nice formated way in the next statement.
                                       echo  $name ."  ---- " .  date_format($date, 'd M, Y') . "<br>";
                                  }
                               
                            } 
                ?>
         </div>    
          <div style='padding:5px'>
        <h4>Filter By Birth Date</h4>
        <form method='POST' action='dateofbirth.php'>
        <div class='search'>
        <label>Month:</label><input type='text' name='srch_month' required />
        <label>Day:</label><input type='text' name='srch_day' required/>
        <input type='hidden' name='search'/>
        <input style='grid-column:2' type='submit' value ='Search' />
        </div>
        
        </form>
           <?php  // Select users who match incoming search criteria
           if($srch_day != '' && $srch_month !=''){//make sure the search criteria is valid
               //In the next sql query we use the MySQL statements MONTH() and DAY() to do the filtering.
               //We can include Year in the form POST but we will end up ignoring it anyway.
                    $sql ="SELECT name, dob FROM users WHERE MONTH(dob) = $srch_month && DAY(dob) = $srch_day ";
                        $result = $con->query($sql);
                            while($row = $result->fetch_assoc()) {
                                $name = $row['name'];
                                $dob = $row['dob'];
                            if($dob != NULL){//check for NULL
                                    $date = date_create($dob); //convert from MySQL date format to PHP date 
                                    //so we can display it in a nice formated way in the next statement.
                                       echo  $name ."  ---- " .  date_format($date, 'd M, Y') . "<br>";
                                  }
                                }
           }
                ?>
         </div>    
   </div>     
</div>
</body>
</html>