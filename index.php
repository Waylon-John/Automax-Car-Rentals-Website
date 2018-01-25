<?php
    session_start();
    require("database.php");
    require("messagenotif.php");
    $notify = $sign;

    function action() {
        if(!isset($_SESSION['signeduser'])) {
                  echo "
            <div class='topbanner'>
                <div class ='topbannertext'>
                    <h2 id ='hh2'>WE GOT YOU COVERED.</h2>
                    <h3 class = 'hh3'>DON'T HAVE A CAR? TRAVELING SOMEWHERE? WANNA GO TRAVELLING?</h3>
                    <h3 class = 'hh3'>RESERVE THE CAR THAT YOU LIKE</h3>
                </div>  
                <div class='topbanner2'>
                    <a href='account.php' id = 'myreserv'>LOGIN NOW</a>
                </div>
            </div>   
            ";
        } else if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") {
            echo "
            <div class='topbanner'>
                <div class ='topbannertext'>
                    <h2 id ='hh2'>WE GOT YOU COVERED.</h2>
                    <h3 class = 'hh3'>DON'T HAVE A CAR? TRAVELING SOMEWHERE? WANNA GO TRAVELLING?</h3>
                    <h3 class = 'hh3'>RESERVE THE CAR THAT YOU LIKE</h3>
                </div>              
                <div class='topbanner2'>
                    <a href='reservations.php' id = 'myreserv'>MY RESERVATIONS</a>
                </div>           
            </div>
            ";
        }
    }
    function homecontent() {
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel']=="Admin"){
            echo ' <div class ="adminhome">ADMIN OPTIONS</div>
                <div class = "admincontent">
                    <h3><a href = "cars.php" class=adminbutton>Cars for rent</a></h3>
                    <br><br>
                    <h3><a href = "reservations.php" class=adminbutton>Customer Reservations</a></h3>
                    <br><br>
                   <h4><a href = "account.php" class=adminbutton>Back</a></h4>
                </div>
            ';
            
        } else {
            $db = $GLOBALS['db'];
            $stmt = $db->prepare("SELECT Model,image,id FROM cars");
            $stmt->execute();   
            $stmt->bind_result($model,$image,$id);
            while ($stmt->fetch()){    
            echo "
                <div class = 'cars2'>
                    <span class='pic'>
                        <img class='carspic' src='images/{$image}' />
                    </span>
                    <div class = 'indexcardetails'>
                        <label class = 'carmodel'>$model</label>
                        <br>
                        <a class='indexbookbutton' href=book.php?id={$id}>
                            <div class ='bookingbutton'>BOOK</div>
                        </a>
                        <a class='indexviewreservebutton' href=car-reservations.php?id={$id}>
                            <div class ='viewreservationsbutton'>VIEW CURRENT RESERVATIONS</div>
                        </a>
                    </div>
                </div>
            ";
            }
        }
    }

      function navmenu() {
      $notif = $GLOBALS['notify'];
        if(isset($_SESSION['signeduser']) && $_SESSION['userlevel'] == "Customer") 
        {
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

<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='index.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = "indexbody">
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
        <div id ="bodycontainer">
            <div class ="bannercontainer">
                <?php 
                    action();
                ?>
                 <div class="carslogin">
                         <?php 
                            if(isset($_SESSION['signeduser']) && $_SESSION['userlevel']) {
                                echo "<div id = 'carstext'>CARS FOR RENT</div>";
                            } else if(!isset($_SESSION['signeduser'])) {
                                echo "<div id = 'carstext'>CARS FOR RENT</div>";
                            }
                         ?>
                      <div class = 'cars2container'>
                        <?php
                            homecontent();
                        ?>
                     </div>
                </div>
            </div>
        </div>
        <br>
        <footer id = 'foot'>
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