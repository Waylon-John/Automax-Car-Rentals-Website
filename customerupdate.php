<?php
    //PAGE WHERE USERS/CUSTOMERS CAN UPDATE/EDIT THEIR CURRENT RESERVATIONS
    session_start();
    require("database.php");
    function update(){
        if(!isset($_SESSION['signeduser'])){
        header("location:account.php");
    } else {
        $db = $GLOBALS['db'];
        $AccountID = $_SESSION['signedid'];
        $tid = $_GET['tid'];
        $cid = $_GET['cid'];
        require("messagenotif.php");
        $stmt = $db->prepare('SELECT Model,Type,PricePerDay,image FROM cars WHERE id = ? LIMIT 1');
        $stmt->bind_param('s',$cid);
        $stmt->execute();
        $stmt->bind_result($model,$type,$rentprice,$image);
        while($stmt->fetch()) {
           echo "
           <div class = 'bookform' id = 'product'>
                <img class='bookcarpic' src='images/{$image}'/><br>
                <br>
                <div>Car Model: $model</div>
                <div>Car Type: $type</div>
                <div>Rent Price: $rentprice</div>
            </div>";
            
            echo "
            <div class = 'bookform' id = 'book'>
                <form action='customerupdate.php?cid={$cid}&tid={$tid}' method='post'>
                <label class = 'inputlabels' >Date of Rent: </label><br>
                <input type='date'
                placeholder='Date of Rent' class='carinputs' name='rentdate' required/>
                <br><br>
                <label class = 'inputlabels'>Date of Return: </span><br>
                <input type='date'
                placeholder='Date of Return' class='carinputs' name='returndate' required/>

                 <br>
                <input type='text' value='NOT FINALIZED' name='bookstatus' class='statusinput' readonly/><br>
                <input type='submit' value='UPDATE' name='bookcar' class='bookbutton'/>
                </form>
            </div>";
            }
          
            if(isset($_POST['rentdate']) && isset($_POST['returndate'])) {
                $rentt = new DateTime($_POST['rentdate']);
                $today = new DateTime();

                $rent = $_POST['rentdate']; 
                $return = $_POST['returndate'];

                if ($rent >= $return) {
                  echo "<br><label class ='errortext'>ERROR</label>";
                  return;
                }
                if($rentt <= $today ){
                    echo "<div class ='errortext'>ERROR</div>";
                    return;
                }

                $sql = "SELECT Car_ID FROM reservations";
                $result = $db->query($sql);
                $stmt=$db->prepare("SELECT currentunits FROM cars WHERE id = ?");
                $stmt->bind_param('s',$cid);

                $stmt->execute();
                $stmt->bind_result($currentunits);
                while($stmt->fetch()) {
                }
                    if($currentunits <=0) {
                        if ($result->num_rows) {
                            // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    foreach ($row as $samerow){ 
                                            if ($cid == $samerow) {
                                                $stmt=$db->prepare("SELECT DateRent,DateReturn,Status FROM reservations WHERE Car_ID = ?");
                                                $stmt->bind_param('i',$samerow);
                                                $stmt->execute();    
                                                $stmt->bind_result($rent,$return,$stats);
                                                while($stmt->fetch()) {
                                                    if($stats == 'FINALIZED') {
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
                                                                header("location: errordates.php?id={$cid}");
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
                PriceComputation($AccountID,$type,$rentprice,$_POST['rentdate'],$_POST['returndate'],$cid,$tid,$_POST['bookstatus']);

            }
        } $stmt->close(); $db->close();
    }

    function PriceComputation($userID,$cartype,$pricerent,$rent,$return,$cid3,$tid3,$bookstatus) {
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
            doUpdate ($userID,$rent,$return,$cid3,$tid3,$bookstatus,$totalprice);
    }

    function doUpdate($userID2,$rent2,$return2,$cid2,$tid2,$bookstatus2,$price) {
        $db = $GLOBALS['db'];
        $stmt = $db->prepare("UPDATE reservations SET DateRent = ?, DateReturn = ?, RentPrice = ? WHERE id = ?  ");
        $stmt->bind_param("ssss",$rent2,$return2,$price,$tid2);
        $stmt->execute();
        header("location:reservations.php");
        //var_dump($rent2);
        //var_dump($return2);
       
    }


    
function navmenu() {
      
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="#foot">Contact Us</a></div>
                <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div>
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
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'updatebody'>
        <!--HEADER-->
       <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
               
           </div>
        </div>
        <hr>
        <!--BODY-->
        <!--SIDENAV-->
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        <!--SIDENAV-->
        <div class ="bookbodycontainer">
            <div class ="bookbannercontainer">
                <div class ='updatetext'>UPDATE RESERVATION</div>
                 <div class="updatecontainer">
                    <?php
                        update();
                    ?>
                </div><br>
            </div>
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