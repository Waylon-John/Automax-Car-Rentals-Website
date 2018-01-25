<?php
    //PAGE WHERE USERS/CUSTOMERS CAN VIEW RESERVATIONS OF OTHER USERS/CUSTOMERS ON THE PARTICULAR CAR.
    session_start();
    
    require("database.php");
    require("messagenotif.php");
   
    $notify = $sign;

    function carstable(){
    
        $id = $_GET['id'];
        $db = $GLOBALS['db'];
        $stmt = $db->prepare('SELECT id,DateRent, DateReturn, Status FROM reservations WHERE Car_ID = ?');
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $stmt->bind_result($reserveid,$rent,$return,$status);
        $stmt->store_result();
        
        while ($stmt->fetch()){
           
            if($status == "FINALIZED") {
            echo "
                 <div class = 'creserve'>
                    <table class = 'creservetable'>

                        <tr>
                            <td colspan='2'>
                                RESERVATION NO. {$reserveid}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Rent Day
                            </td>
                            <td>
                                $rent
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Return Day
                            </td>
                            <td>
                                $return
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Booking Status
                            </td>
                            <td>
                                $status
                            </td>
                        </tr>
                    </table>
                    </div>
            ";
            } else { 
                echo "
                    <div class = 'creserve'>
                    <table class = 'creservetable'>

                        <tr>
                            <td colspan='2'>
                                RESERVATION NO. {$reserveid}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Rent Day
                            </td>
                            <td>
                                $rent
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Return Day
                            </td>
                            <td>
                                $return
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Booking Status
                            </td>
                            <td>
                                $status
                            </td>
                        </tr>

                    </table>
                 </div>
            ";
            } 
        }                             
    }

 function navmenu() {
      
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="index.php#foot">Contact Us</a></div>
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

?>

<!DOCTYPE html>
<html class = 'carreservebody'>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='cars.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body>
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
      
                <?php
                    $idd = $_GET['id'];
                    $stmt22 = $db->prepare('SELECT Model FROM cars WHERE id = ?');
                    $stmt22->bind_param('s',$idd);
                    $stmt22->execute();
                    $stmt22->bind_result($model);
                    $stmt22->store_result();
                    while($stmt22->fetch()) {
                        echo "<div id = 'rtext'>$model RESERVATIONS</div>";
                    }
                ?>
                <div class = 'container'>
                     <div class="rtablecontainer">
                        <?php
                            carstable();
                        ?>
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