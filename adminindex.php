<?php
session_start();
    require("database.php");
    require("messagenotif.php");
   
    $notify = $sign;

function login() {
        
        echo '<form action=account.php method="post" class = "loginform">
                      
               <input type="text" placeholder="Enter Username"
                name="loginusername" class = "username" required>
                <input type="password" placeholder="Enter Password" name="loginpassword" class="password" required>
                
                <input type="submit" value="LOGIN" id="buttonlogin">
                
                <a href="register.php" name="sign" id ="buttonregister"><div id ="buttonnregister">SIGN UP</div></a>
             </form>';
    }



function doLogin($user,$pass) {
        $db = $GLOBALS['db'];
        $stmt = $db->prepare('SELECT firstname,lastname,contactnumber, password,id,userlevel FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s',$user);
        $stmt->execute();
        $stmt->bind_result($fname,$lname,$contact,$password,$id,$userlevel);
        while($stmt->fetch()) {
            if($pass == $password){
                $_SESSION['signeduser'] = $user;
                $_SESSION['signedid'] = $id;
                $_SESSION['userlevel'] = $userlevel;
            }
            if($userlevel == 'Customer') {
                header('location:index.php');
            }
        } $stmt->close();
        if(!isset($_SESSION['signeduser'])){
            $_SESSION['error'] = 1;
        }
        
        
    }

   
     function navmenu() {
      $notif = $GLOBALS['notify'];
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Profile</a></div>
                <div class = "navbarmenu"><a href="message.php">Contact Admin</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                <div class = "navbarmenu"><a href="#abtfoot">Contact Us</a></div>
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
                    <div class = 'navbarmenu'><a href='#abtfoot'>Contact Us</a></div>
                    <div class = 'navbarmenu'><a href = 'account.php?logout'>Logout</a></div>
                ";
                    
            }
            
        else {
            echo '
                <div class = "navbarmenu"><a href="index.php">Home</a></div>
                <div class = "navbarmenu"><a href="account.php">Login</a></div>
                <div class = "navbarmenu"><a href="about-us.php">About Us</a></div>
                
                <div class = "navbarmenu"><a href="#abtfoot">Contact Us</a></div>
                <div class = "navbarmenu"><a href = "termsandconditions.php">Terms and Conditions</a></div>
            ';
        }

    }

    if(isset($_POST['loginusername'])){
        doLogin($_POST['loginusername'], $_POST['loginpassword']);
    }
    if(isset($_GET['logout'])) {
        unset($_SESSION['user']);
        unset($_SESSION['signeduser']);
        unset($_SESSION['signedid']);
        unset($_SESSION['userlevel']);
    }
    if(isset($_SESSION['error'])){
        header("location:errorlogin.php");
    }
    if(!isset($_SESSION['signedid']) && ($_SESSION['userlevel'] != "Admin")) {
        header("location:account.php");
    }
    if(isset($_SESSION['signedid']) && ($_SESSION['userlevel'] == "Customer")) {
        header("location:account.php");
    }

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='account.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = "registerbody">
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
        <!--BODY-->
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
        
                
        </div>
        <div class = "logincontainer">
           
            <?php
            
            
                        echo "<div class ='logintext'>ADMIN OPTIONS</div>
                        ";
            echo ' <div class = "admincontent">
                  
                    <a href = "cars.php" class="adminoptionsbutton"><div class ="adminbuttond">Cars for rent</div></a>
                    
                   
                   <a href = "reservations.php" class="adminoptionsbutton"><div class = "adminbuttond">Customer Reservations</div></a>
                   <br>
                   <a href = "account.php" class=adminbackbutton>Back</a>
                </div>';
                    
                  
             
            ?>
         
        </div>
        
         <script>
            /* Set the width of the side navigation to 250px */
                  function openNav() {
                document.getElementById("sidenav").style.width = "200px";
               
                document.getElementById("sidenav").style.visibility = "visible"; document.getElementById("sidenav").style.padding = "20,20,20,20";
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