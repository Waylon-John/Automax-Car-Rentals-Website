<?php
session_start();
 require("database.php");
 require("messagenotif.php");
   
    $notify = $sign;
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


if(isset($_GET['logout'])) {
        unset($_SESSION['user']);
        unset($_SESSION['signeduser']);
        unset($_SESSION['signedid']);
        unset($_SESSION['userlevel']);
    }

 
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='index.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'aboutusbody'>
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
        
        <div class ="bodycontainer">
            <div class ="bannercontainer">
                     <div class="about">
                         
                      <h2 class = 'h22'>ABOUT US</h2><br>
                         <p>Automax Car Rentals is a specialty automotive rental company offering rental cars and vans. We offer a variety of options that can enhance your experience, always according to your necessities, and help you get the best out of your holidays or your business trip we provide high-quality customer service and dependable car rentals. Locally owned and operated, we take pride in providing the best in both automotive rentals and used auto sales</p><br>
                         <h4>Automax Car Rentals Offers</h4><br>
                         <li>Fully inclusive rates.</li>
                         <li>Wide choice of vehicles.</li>
                         <li>Price Promise.</li>
                         <li>Dedicated Staff, who are not commission based, but customer service focused.</li>
                         <br>
                         <p>With us, you'll find a host of services designed to get you to your destination safely with excellent rates and dependable, safe vehicles.</p>
                    </div>
             
                
            </div>
        </div>
           <footer id = 'abtfoot'>
               <div class = 'footdiv'>
         
            <div id ='footercontent'><label class= 'footh'>Address</label>
                <br><br>B17 L15 Royal South Village Talon V / L15 B17 Royal South St. Royal South Townhomes Talon V, Las Pinas, Metro Manila<br>
                Centennial Road, Kawit, Cavite
            </div>

            <div id = 'footercontent'>
                <label class = 'footh'>Social Media</label><br><br>
                <label id = 'email'>automaxrentals@gmail.com</label>
                <label id = 'email'>www.facebook.com/pages/automaxrentals</label>
            </div>
            <div id = 'footercontent'>
                <label class = 'footh'>Contact</label><br><br>
                
                <label>+63 2 330 0479</label><br>
            </div>
            
      
            </div>
           
        </footer>
         
          
        
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