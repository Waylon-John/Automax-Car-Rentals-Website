<?php
    //PAGE WHERE CUSTOMERS CAN BOOK A CAR FOR RENT.
    session_start();
    require("database.php");
    function book(){
        $db = $GLOBALS['db'];
            if(!isset($_SESSION['signeduser'])){
                header("location:account.php");
            } else if($_SESSION['userlevel'] != "Customer") {
                     header("location:account.php");   
                }


            else {
            $AccountID = $_SESSION['signedid'];
            $id = $_GET['id'];

        require("messagenotif.php");

        $notify = $sign;

            $stmt = $db->prepare('SELECT Model,Type,PricePerDay,Units,image FROM cars WHERE id = ? LIMIT 1');
            $stmt->bind_param('s',$id);
            $stmt->execute();
            $stmt->bind_result($model,$type,$rentprice,$units,$image);
            while($stmt->fetch()) {
                echo "
                <div class = 'bookform' id = 'product'>
                    <img class='bookcarpic' src='images/{$image}'/><br>
                    <br>
                    <div>Car Model: $model</div>
                    <div>Car Type: $type</div>
                    <div>Rent Price: $rentprice</div>
                    <div>Units: $units</div>
                </div>";

                echo "
                    <div class = 'bookform' id = 'book'>
                        <form action='book.php?id={$id}' method='post'>
                            <label class = 'inputlabels' >Date of Rent: </label><br>
                            <input type='date'
                            placeholder='Date of Rent' class='carinputs' name='rentdate' required/>
                            <br><br>
                            <label class = 'inputlabels'>Date of Return: </span><br>
                            <input type='date'
                            placeholder='Date of Return' class='carinputs' name='returndate' required/><br>
                            <input type='text' value='NOT FINALIZED' name='bookstatus' class='statusinput' readonly/><br>
                            <input type='submit' value='Book' name='bookcar' class='bookbutton'/>
                        </form>";

                }

            if(isset($_POST['rentdate']) && isset($_POST['returndate'])) {
                 $rentt = new DateTime($_POST['rentdate']);
                $today = new DateTime();


                $rent = $_POST['rentdate'];
                $return = $_POST['returndate'];


               if($rent >= $return) {
                  echo "<div class ='errortext'>ERROR</div>";
                  return;

                }
                if($rentt <= $today ){
                    echo "<div class ='errortext'>ERROR</div>";
                    return;
                }


                    echo "</div>";

                $sql = "SELECT Car_ID FROM reservations";
                $result = $db->query($sql);
                $stmt=$db->prepare("SELECT currentunits FROM cars WHERE id = ?");
                $stmt->bind_param('i',$id);
                $stmt->execute();
                $stmt->bind_result($currentunits);
                while($stmt->fetch()) {
                }
                if($currentunits <=0) {
                if ($result->num_rows) {
                // output data of each row
                    while($row = $result->fetch_assoc()) {
                        foreach ($row as $samerow){ 
                            if ($id == $samerow) {
                                $stmt=$db->prepare("SELECT DateRent,DateReturn,Status FROM reservations WHERE Car_ID = ?");
                                $stmt->bind_param('i',$samerow);
                                $stmt->execute();    
                                $stmt->bind_result($rent,$return,$stats);
                                while($stmt->fetch()) {
                                    if($stats == 'NOT FINALIZED') {
                                        $begin = new DateTime($rent);
                                        $end = new DateTime($return);
                                        $end = $end->modify( '+0 day' ); 
                                        $interval = new DateInterval('P1D');
                                        $daterange = new DatePeriod($begin, $interval ,$end);

                                        $begin1 = new DateTime($_POST['rentdate']);
                                        $end1 = new DateTime($_POST['returndate']);
                                        $end1 = $end1->modify( '+0 day' ); 
                                        $interval1 = new DateInterval('P1D');
                                        $daterange1 = new DatePeriod($begin1, $interval1 ,$end1);

                                        foreach($daterange as $date){
                                           foreach($daterange1 as $date1){


                                                if($date == $date1) {
                                                header("location: errordates.php?id={$id}");
                                                return;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
                $stmt=$db->prepare('SELECT currentunits FROM cars WHERE id = ? LIMIT 1');
                $stmt->bind_param('i',$id);
                $stmt->execute();
                $stmt->bind_result($currentunits);
                $stmt->store_result();
                while($stmt->fetch()) {

                    $currentunits = $currentunits  - 1;
                    $stmt = $db->prepare('UPDATE cars SET currentunits = ? WHERE id = ? LIMIT 1');
                    $stmt->bind_param('ii', $currentunits,$id);
                    $stmt->execute();
                }



                PriceComputation($AccountID,$type,$rentprice,$_POST['rentdate'],$_POST['returndate'],$id,$_POST['bookstatus']);
            }
            } $stmt->close(); $db->close();



        }

    function PriceComputation($userID,$cartype,$pricerent,$rent,$return,$bid,$bookstatus) {

            $totalprice = 0;
            $numdays = 0;
            $begin = new DateTime($rent);
            $end = new DateTime($return);
            $end = $end->modify( '+0 day' ); 
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);
            foreach($daterange as $date){
            $numdays++;
            //echo $date->format("Ymd") . "<br>";
            }
            //echo $numdays;
            $totalprice = $numdays * $pricerent;

        doBooking ($userID,$rent,$return,$bid,$bookstatus,$totalprice);
    }

    function doBooking($userID2,$rent2,$return2,$bid2,$bookstatus2,$price) {
        $db = $GLOBALS['db'];
        $stmt = $db->prepare('INSERT INTO reservations(Account_ID,Car_ID,DateRent,DateReturn,RentPrice,Status) VALUES(?,?,?,?,?,?)');
        $stmt->bind_param("ssssss",$userID2,$bid2,$rent2,$return2,$price,$bookstatus2);
        $stmt->execute();
        header("location:reservations.php");


    }

     function navmenu() {

            if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
                echo '
                    <div class = "navbarmenu"><a href="index.php">Home</a></div>
                    <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                    <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                    <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                    <div class = "navbarmenu"><a href="#foot">Contact Us</a></div>
                    <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div><br>
                    <div class = "navbarmenu"><a href = "account.php?logout">Logout</a></div>

                ';
            } else if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Admin"){

                echo "
                     <div class = 'navbarmenu'><a href='adminindex.php'>Home</a></div>
                    <div class = 'navbarmenu'><a href='account.php'>Profile</a></div>
                    <div class = 'navbarmenu'><a href='customerlist.php'>Customers</a></div>
                    <div class = 'navbarmenu'><a href='inbox.php'>Inbox{$notif}</a></div>
                    <div class = 'navbarmenu'><a href='about-us.php'>About Us</a></div>
                    <div class = 'navbarmenu'><a href='#foot'>Contact Us</a></div>
                    <div class = 'navbarmenu'><a href = 'account.php?logout'>Logout</a></div>
                ";

                }

            else {
                echo '
                    <div class = "navbarmenu"><a href="index.php">Home</a></div>
                    <div class = "navbarmenu"><a href="account.php">Login</a></div>
                    <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>

                    <div class = "navbarmenu"><a href="#foot">Contact Us</a></div>
                    <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div>
                ';
            }

        }


        if(isset($_GET['logout'])) {
                unset($_SESSION['user']);
                unset($_SESSION['signeduser']);
                unset($_SESSION['signedid']);
                unset($_SESSION['userlevel']);
            }
    
?> 


<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='cars.css'/>
         <link rel='stylesheet' href='book.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'bookbody'>
        <!--HEADER-->
         <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
           </div>
        </div>
        <hr>
        
        <!--SIDENAV-->
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        <!--SIDENAV-->
        
       
           
          
                     <div class="bookingcontainer">
                      <?php
                         book();
                  
                          
                         ?>
                         
                    </div>
               
          
       
        
        <script>
            /* Set the width of the side navigation to 250px */
            function openNav() {
                document.getElementById("sidenav").style.width = "15%";
                document.getElementById("sidenav").style.minWidth="200px";
                document.getElementById("sidenav").style.visibility = "visible"; document.getElementById("sidenav").style.padding = "20,20,20,20"; document.getElementById("buttonlogin").style.width = "25%";
            }

            /* Set the width of the side na vigation to 0 */
            function closeNav() {
                
                document.getElementById("sidenav").style.width = "0";
                document.getElementById("sidenav").style.minWidth="0";
                document.getElementById("sidenav").style.visibility = "hidden";
                document.getElementsById("buttonlogin").style.width = "0";
                document.getElementById("sidenav").style.padding = "0,0,0,0";
                
            }
          
       
            
        </script>
    </body>
</html>