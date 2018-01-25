<?php
    session_start();

    if(isset($_SESSION['signeduser'])){
        header("location: index.php");
    }
    function register($first,$last,$user,$pass,$contact,$level) {
        require("database.php");
        require("messagenotif.php");
   
        $notify = $sign;
       

        $whatToSelect = 'username';
        $whereToSelect = 'users';
        $username = $user;
        
        $stmt=$db->prepare("SELECT username,password FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $stmt->bind_result($user1,$pass1);
        while($stmt->fetch()) { 
            if($username == $user1) {
                 $_SESSION['usererror']=1;
                return;
            }
        }
        $stmt = $db->prepare ("INSERT INTO users (FirstName,LastName,username,password,ContactNumber,UserLevel) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssis",$_POST['ufirstname'],$_POST['ulastname'],$_POST['uusername'],$_POST['upassword'],$_POST['ucontact'],$_POST['level']);
        $stmt->execute();
        $_SESSION['register'] = 1;
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
   if(isset($_POST['ufirstname'])&&isset($_POST['ulastname'])&&isset($_POST['uusername'])&&isset($_POST['upassword'])&&isset($_POST['ucontact'])&&isset($_POST['level'])){
       register($_POST['ufirstname'],$_POST['ulastname'],$_POST['uusername'],$_POST['upassword'],$_POST['ucontact'],$_POST['level']);
   }

    
  
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='account.css'/>
        <link rel='stylesheet' href='cars.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'registerbody'>
       <!--HEADER-->
            <div class = "headercontainer">
           <div class = "header">
            <label class ='automax'>AUTOMAX</label> <label class ='rentals'>CAR RENTALS  <label class="menu" style="font-size:20px;cursor:pointer;" onclick="openNav()">&#9776;</label></label>
               
           </div>
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
       <div id="sidenav" class ="navbar">
                <a href="javascript:void(0)"  class="closebtn" onclick="closeNav()">&times;</a>
                <?php
                    navmenu();
                ?>
        </div>
        <!--SIDENAV-->
            <div class = "registercontent">
                
                <?php if(!isset($_SESSION['register'])) {
     if(isset($_SESSION['usererror'])) {
                    echo "<div class ='usertaken'>USERNAME ALREADY TAKEN</div>";
                    unset($_SESSION['usererror']);
                   
                }
                    
               echo " 
               <div class = 'registertext'>REGISTER</div>
                    <div class = 'registerform'>
                    <form action='register.php' method='post'>
                        <div class = 'registerinputs'>
                            <label class = 'label'>First Name</label><br>
                            <input type = 'text' name = 'ufirstname' class = 'inputs' required>
                        </div>
                        
                         <div class = 'registerinputs'>
                            <label class = 'label'>Last Name</label><br>
                            <input type = 'text' name = 'ulastname' class = 'inputs' required>
                        </div>
                         <div class = 'registerinputs'>
                            <label class = 'label'>Username</label><br>
                            <input type = 'text' name = 'uusername' class = 'inputs' required>
                        </div>
                         <div class = 'registerinputs'>
                            <label class = 'label'>Password</label><br>
                            <input type = 'password' name = 'upassword' class = 'inputs' required>
                        </div>
                         <div class = 'registerinputs'>
                            <label class = 'label'>Contact</label><br>
                            <input type = 'text' name = 'ucontact' class = 'inputs' required>
                        </div>
                         <div class = 'registerinputs'>
                         
                            <input type = 'hidden' name = 'level' value = 'Customer'>
                        </div><br>
                        <div><input type = 'submit' name = 'reg' class = 'regbutton' value= 'REGISTER'></div>
                     
                    </form>
                </div>
            </div>
               ";
                    
          
            } 
               
                        
        ?>
              
        <?php
        if(isset($_SESSION['register'])) {
                echo "<div class = 'regsuccess'>REGISTRATION SUCCESSFUL!<a href = 'account.php' class = 'backtologin'><div class ='loginnowbutton'>LOGIN NOW</div></a></div>";
                unset($_SESSION['register']);
            }?>
        
    <script>
            /* Set the width of the side navigation to 250px */
            function openNav() {
                document.getElementById("sidenav").style.width = "18%";
                document.getElementById("sidenav").style.minWidth="250px";
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