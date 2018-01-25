<?php
session_start();
function navmenu() {
        if(isset($_SESSION['signeduser'])) {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="contact-us.php">Contact Us</a></div>
                <div class = "navbarmenu"><a href="index.php?logout">Logout</a></div>
            ';
        } else {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Login</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="contact-us.php">Contact Us</a></div>
            ';
        }

    }
  

    /*function doAdding($model,$type,$pltnum){
        $db = new mysqli('localhost','root','','automax');
        if($db->connect_error){
            die("Error in Connecting to database" . $db->connect_error);
        }
        
        $stmt = $db->prepare('INSERT INTO cars(Model,Type,PlateNumber) VALUES(?,?,?)');
        $stmt->bind_param('sss',$model,$type,$pltnum);
        $stmt->execute();
        $stmt->close(); $db->close();
    }*/ 


/*if(isset($_POST['addmodel'])){
    doAdding($_POST['addmodel'],$_POST['addtype'],$_POST['addnumber']);
}*/

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
    <body>
        <!--HEADER-->

                <div class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</div>
       
       <div class = "headercontainer">
           
           <div class = "header">
            AUTOMAX
           </div>
           CAR RENTALS
        </div>
        
        
        <!--BODY-->
        
        <!--SIDENAV-->
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        <!--SIDENAV-->
        
        <div class ="bodycontainer">
            <div class ="bannercontainer">
                     <div class="carslogin">
                      <h1>Contact Us</h1>
                    </div>
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