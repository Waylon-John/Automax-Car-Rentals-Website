<?php
    //PAGE WHERE ADMIN AND USER/CUSTOMER CAN CONTACT EACH OTHER. 
    session_start();
    if(!isset($_SESSION['signeduser'])){
        header("location:index.php");
    }
    //CODES FOR CHAT
     require("database.php");
     require("messagenotif.php");

        $notify = $sign;

    if(isset($_POST['refresh'])) {
        header("refresh:0");
    }

    if($_SESSION['userlevel']=="Admin"){
        $id = $_GET['id'];

        $update = $db->prepare("UPDATE message SET status = 'VIEWED' where sender_id = ?");
        $update->bind_param('i',$id);
        $update->execute();

        $stmt = $db->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->bind_result($cid);
        $stmt->store_result();
        $stmt->fetch();
        $customerid=$cid;


        function displayMessage(){
            $customerid = $GLOBALS['customerid'];
            $db = $GLOBALS['db'];
            $stmt2 = $db->prepare("SELECT sender_id,content,message_time FROM message WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY message_time ASC");
            $stmt2->bind_param('iiii',$_SESSION['signedid'],$customerid,$customerid,$_SESSION['signedid']);
            $stmt2->execute();
            $stmt2->bind_result($sid,$messagecontent,$messagetime);
            $stmt2->store_result();
            while ($stmt2->fetch()){
                $stmt3 = $db->prepare("SELECT username FROM users WHERE id = ? LIMIT 1");
                $stmt3->bind_param('i',$sid);
                $stmt3->execute();
                $stmt3->bind_result($sender);
                $stmt3->store_result();
                $stmt3->fetch();

                  echo "
                        <div class = 'messagecontents'>
                       <div class = 'usertimemsg' id = 'user'>$sender </div>
                        <div class = 'usertimemsg' id = 'time'>$messagetime</div>
                        <div class = 'usertimemsg' id = 'chatmsg'>$messagecontent</div>
                        </div>
                ";
            }
        }

        function insertMessage($content,$status){
            $customerid = $GLOBALS['customerid'];
            $db = $GLOBALS['db'];
            $stmt3 = $db->prepare("INSERT INTO message(sender_id,content,recipient_id,status) VALUES(?,?,?,?)");
            $stmt3->bind_param('isis',$_SESSION['signedid'],$content,$customerid,$status);
            $stmt3->execute();
        }

        if(isset($_POST['sendmessage'])){
            insertMessage($_POST['message'],$_POST['messagestatus']);
        }

        function Message() {
            $cid = $GLOBALS['cid'];
             echo "
                <form action='message.php?id={$cid}' method='post' id='messageform'>
                <textarea placeholder='Enter Message here...' rows='6' cols='50' name='message' form='messageform' id = 'messageinput' class = 'chatbutton'></textarea><br>
                    <input type='submit' name='sendmessage' value='Send' id = 'chatbutton'/>
                    <input type='submit' name-'refresh' value='Refresh' id = 'chatbutton'/>
                    <input type='hidden' value='UNREAD' name='messagestatus'/>
                </form>
            ";
        }

    }

    if($_SESSION['userlevel']=="Customer") {
        $stmt = $db->prepare('SELECT id FROM users WHERE UserLevel = "Admin" LIMIT 1');
        $stmt->execute();
        $stmt->bind_result($aid);
        $stmt->store_result();
        $stmt->fetch();
        $admin_id = $aid;

        function displayMessage(){
            $aid = $GLOBALS['admin_id'];
            $db = $GLOBALS['db'];
            $stmt2 = $db->prepare("SELECT sender_id,content,message_time FROM message WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY message_time ASC");
            $stmt2->bind_param('iiii',$_SESSION['signedid'],$aid,$aid,$_SESSION['signedid']);
            $stmt2->execute();
            $stmt2->bind_result($sid,$messagecontent,$messagetime);
            $stmt2->store_result();
            while ($stmt2->fetch()){
                $stmt3 = $db->prepare("SELECT username FROM users WHERE id = ? LIMIT 1");
                $stmt3->bind_param('i',$sid);
                $stmt3->execute();
                $stmt3->bind_result($sender);
                $stmt3->store_result();
                $stmt3->fetch();


                echo "
                        <div class = 'messagecontents'>
                       <div class = 'usertimemsg' id = 'user'>$sender </div>
                        <div class = 'usertimemsg' id = 'time'>$messagetime</div>
                        <div class = 'usertimemsg' id = 'chatmsg'>$messagecontent</div>
                        </div>
                ";
            }
        }

        function insertMessage($content,$status){
            $aid = $GLOBALS['admin_id'];
            $db = $GLOBALS['db'];
            $stmt3 = $db->prepare("INSERT INTO message(sender_id,content,recipient_id,status) VALUES(?,?,?,?)");
            $stmt3->bind_param('isis',$_SESSION['signedid'],$content,$aid,$status);
            $stmt3->execute();
        }

        if(isset($_POST['sendmessage'])&& isset($_POST['messagestatus'])){
            insertMessage($_POST['message'],$_POST['messagestatus']);
        }

        function Message() {
            echo "
                <form action='message.php' method='post' id='messageform'>
                <textarea placeholder='Enter Message here...' rows='6' cols='50' name='message' form='messageform' id = 'messageinput' class = 'chatbutton'></textarea><br>
                    <input type='submit' name='sendmessage' value='Send' id = 'chatbutton'/>
                    <input type='submit' name-'refresh' value='Refresh' id = 'chatbutton'/>
                    <input type='hidden' value='UNREAD' name='messagestatus'/>
                </form>
            ";
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
<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
         <link rel='stylesheet' href='message.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'msgbody'>
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
        <div class ="msgbodycontainer">      
            <div class = 'messageheader'></div>
            <div class ="chathead">MESSAGE</div>
               
                      <div class = "messagecontainer">
                            <?php
                                displayMessage();
                               
                            ?>
            </div>
             <?php          
            Message();
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