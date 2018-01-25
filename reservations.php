<?php
    session_start();

    if(!isset($_SESSION['signeduser'])){
        header("location:index.php");
    }

     require("database.php");
     require("messagenotif.php");

    $notify = $sign;

    if(isset($_POST['updateaction'])){
            $stmt=$db->prepare('SELECT RentPrice FROM reservations WHERE id = ? LIMIT 1');
            $stmt->bind_param('i',$_POST['reservationid']);
            $stmt->execute();
            $stmt->bind_result($rentprice);
            $stmt->store_result();
            while($stmt ->fetch()) {
                $rentprice = $rentprice - 400;
                $stmt = $db->prepare('UPDATE reservations SET Status = "FINALIZED",RentPrice = ?  WHERE id = ? LIMIT 1');  
                $stmt->bind_param('ii', $rentprice,$_POST['reservationid']);
                $stmt->execute();

            }
    }

        else if(isset($_POST['revokeaction'])){
            $stmt=$db->prepare('SELECT RentPrice FROM reservations WHERE id = ? LIMIT 1');
            $stmt->bind_param('i',$_POST['reservationid']);
            $stmt->execute();
            $stmt->bind_result($rentprice);
            $stmt->store_result();
            while($stmt ->fetch()) {
                $rentprice = $rentprice + 400;
                $stmt = $db->prepare('UPDATE reservations SET Status = "NOT FINALIZED",RentPrice = ? WHERE id = ? LIMIT 1');
                $stmt->bind_param('ii', $rentprice,$_POST['reservationid']);
                $stmt->execute();
            }
        }
        else if(isset($_POST['deleteaction'])) {
            $db = $GLOBALS['db'];
            $stmt=$db->prepare('SELECT Status FROM reservations WHERE id = ? LIMIT 1');
            $stmt->bind_param('i',$_POST['reservationid']);
            $stmt->execute();
            $stmt->bind_result($transactionstatus);
            $stmt->store_result();
            $stmt->fetch();

            if($transactionstatus == "NOT FINALIZED"){

                $stmt=$db->prepare('SELECT Car_ID FROM reservations WHERE id = ? LIMIT 1');
                $stmt->bind_param('i',$_POST['reservationid']);
                $stmt->execute();
                $stmt->bind_result($carid);
                $stmt->store_result();
                while($stmt->fetch()) {

                        $stmt=$db->prepare('SELECT currentunits FROM cars WHERE id = ? LIMIT 1');
                        $stmt->bind_param('i',$carid);
                        $stmt->execute();
                        $stmt->bind_result($currentunits);
                        $stmt->store_result();

                        while($stmt->fetch()) {
                            $stmt=$db->prepare('SELECT Units FROM cars WHERE id = ? LIMIT 1');
                            $stmt->bind_param('i',$carid);
                            $stmt->execute();
                            $stmt->bind_result($units);
                            $stmt->store_result();
                            while($stmt->fetch()) {
                                if($currentunits != $units){
                                $currentunits = $currentunits+1;
                                     var_dump($currentunits);
                                $stmt = $db->prepare('UPDATE cars SET currentunits = ? WHERE id = ? LIMIT 1');
                                $stmt->bind_param('ii', $currentunits,$carid);
                                $stmt->execute();
                                    $stmt2 = $db->prepare('DELETE FROM reservations WHERE id = ? LIMIT 1');
                                    $stmt2->bind_param('i',$_POST['reservationid']);
                                    $stmt2->execute();
                                }

                            }
                        }
                }

            }

                $stmt = $db->prepare('DELETE FROM reservations WHERE id = ? LIMIT 1');
                $stmt->bind_param('i',$_POST['reservationid']);
                $stmt->execute();  
        }

    function homecontent() {
    //Admin
        if($_SESSION['userlevel'] == "Admin"){
            $db = $GLOBALS['db'];
            $stmt2 = $db->query('SELECT * FROM reservations WHERE Status = "FINALIZED" ');
            while ($reservations=$stmt2->fetch_assoc()) {
                $stmt  = $db->prepare('SELECT Model,Type FROM cars WHERE id = ?');
                $stmt->bind_param('i',$reservations['Car_ID']);
                $stmt->execute();
                $stmt->bind_result($model,$typecar);
                $stmt->store_result();
                while ($stmt->fetch()){ 

                    if($reservations['Status'] == 'FINALIZED'){
                        echo "
                            <div class = 'reservef'>
                                <table class='reservetable'>
                                        <tr>
                                            <td colspan='2'>
                                                Account ID: {$reservations['Account_ID']}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2'>
                                                Transaction ID: {$reservations['id']}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'>
                                                {$model}<br><br> 
                                                {$typecar}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Date of Rent
                                            </td>
                                            <td>
                                                Date of Return
                                            </td>
                                        <tr>
                                            <td>
                                                {$reservations['DateRent']}<br>
                                            </td>
                                            <td>
                                                {$reservations['DateReturn']}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'>
                                                Balance Due <br><br> {$reservations['RentPrice']} PHP
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'>
                                                <br>DOWNPAYMENT PAID<br><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'>
                                                {$reservations['Status']}
                                                <form action='reservations.php' method='post'>
                                                    <br>
                                                    <input type='hidden' value='{$reservations['id']}'  name='reservationid'>
                                                    <input class='tablebutton' type='submit' name='revokeaction' value='Unconfirm'/> 
                                                    <input class='tablebutton' type='submit' name='deleteaction' value='Remove'/>
                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </div>
                        ";
                    }
                }
            }
            $stmt3 = $db->query('SELECT * FROM reservations WHERE Status = "NOT FINALIZED" ');
            
            while ($reservations=$stmt3->fetch_assoc()) {
                $stmt  = $db->prepare('SELECT Model,Type FROM cars WHERE id = ?');
                $stmt->bind_param('i',$reservations['Car_ID']);
                $stmt->execute();
                $stmt->bind_result($model,$typecar);
                $stmt->store_result();
                while ($stmt->fetch()){ 
                    if($reservations['Status'] == 'NOT FINALIZED'){
                        $dateapplied = new DateTime($reservations['DateApplied']);
                        $tomorrow = $dateapplied->modify('+1 day');
                        echo "
                        <div id = 'anf'>
                            <div class = 'reserve' id = 'rec'>
                                <table class='reservetable'>
                                    <tr>
                                        <td colspan='2'>
                                            Account ID: {$reservations['Account_ID']}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            Transaction ID: {$reservations['id']}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            {$model}<br><br> 
                                            {$typecar}<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Date of Rent
                                        </td>
                                        <td>
                                            Date of Return
                                        </td>
                                    <tr>
                                        <td>
                                            {$reservations['DateRent']}<br>
                                        </td>
                                        <td>
                                            {$reservations['DateReturn']}<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            Price of Rent <br><br> {$reservations['RentPrice']} PHP
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            DOWNPAYMENT DEADLINE: <br><br>
                                            {$tomorrow->format('Y-m-d h:i:s A')}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            {$reservations['Status']}
                                            <form action='reservations.php' method='post'>
                                                <br>
                                                <input type='hidden' value='{$reservations['id']}'  name='reservationid'>
                                                <input class='tablebutton' type='submit' name='updateaction' value='Confirm'/> 
                                                <input class='tablebutton' type='submit' name='deleteaction' value='Remove'/>
                                        </td>
                                    </tr>
                                    </form>

                                </table>
                            </div>
                        </div>
                        ";
                            $stmt  = $db->prepare('SELECT image FROM receipt WHERE transaction_id = ? AND user_id = ?');
                            $stmt->bind_param('ii',$reservations['id'],$reservations['Account_ID']);
                            $stmt->execute();
                            $stmt->bind_result($image);
                            $stmt->store_result();
                            while($stmt->fetch()){
                                if($image!=null){

                                    echo "<div class = 'reserve' id = 'rec'><div class = 'receipttext'>RECEIPT OF TRANSACTION ID {$reservations['id']}</div>
                                     <img class='receipt' id = 'rec' alt='IMAGE OF RECEIPT' src=/images/".$image." />

                                     </div><br>
                                ";
                                }
                        }    
                    }

                }
            }

        } else {

            //Customer 
            $db = $GLOBALS['db'];
            $stmt2 = $db->prepare("SELECT id,Account_ID,Car_ID,DateApplied,DateRent,DateReturn,RentPrice,Status FROM reservations WHERE Account_ID = ?");
            $stmt2->bind_param('i',$_SESSION['signedid']);
            $stmt2->execute();
            $stmt2->bind_result($tid,$aid,$cid,$applied,$rent,$return,$rp,$tstatus);
            $stmt2->store_result();
            while ($stmt2->fetch()){
                $stmt=$db->prepare("SELECT Model,Type FROM cars WHERE id = ?");
                $stmt->bind_param('s',$cid);
                $stmt->execute();
                $stmt->bind_result($model,$typecar);
                $stmt->store_result();
                while ($stmt->fetch()){
                    $dateapplied = new DateTime($applied);
                    $tomorrow = $dateapplied->modify('+1 day');

                     if($tstatus=="FINALIZED"){
                         echo "
                            <div class = 'cusreserve' id = 'cusf'>
                                <table class='cusreservetable'>
                                    <tr>
                                        <td colspan = '2'>
                                            Transaction ID: {$tid}
                                        </td>
                                    </tr>
                                        <td colspan='2'>
                                            {$model}<br>
                                            <br>{$typecar}<br>
                                        </td>
                                    <tr>
                                        <td>Date of Rent</td>
                                        <td>Date of Return</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {$rent}<br>
                                        </td>
                                        <td>
                                            {$return}<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>Balance Due<br><br>
                                            {$rp}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>
                                            {$tstatus}
                                        </td>
                                    </tr>
                                </table>
                            <br><br>
                            </div>

                            ";
                    }

                }
            }

            $db = $GLOBALS['db'];
            $stmt2 = $db->prepare("SELECT id,Account_ID,Car_ID,DateApplied,DateRent,DateReturn,RentPrice,Status FROM reservations WHERE Account_ID = ?");
            $stmt2->bind_param('i',$_SESSION['signedid']);
            $stmt2->execute();
            $stmt2->bind_result($tid,$aid,$cid,$applied,$rent,$return,$rp,$tstatus);
            $stmt2->store_result();
            while ($stmt2->fetch()){
                $stmt=$db->prepare("SELECT Model,Type FROM cars WHERE id = ?");
                $stmt->bind_param('s',$cid);
                $stmt->execute();
                $stmt->bind_result($model,$typecar);
                $stmt->store_result();
                while ($stmt->fetch()){
                    $dateapplied = new DateTime($applied);
                    $tomorrow = $dateapplied->modify('+1 day');
                    if($tstatus=="NOT FINALIZED"){
                        echo "
                        <div class = 'cusreserve' id = 'cusnf'>
                            <table class='cusreservetable'>
                                <tr>
                                    <td colspan = '2'>
                                        Transaction ID: {$tid}
                                    </td>
                                </tr>
                                    <td colspan='2'>
                                        {$model}<br><br>{$typecar}<br>
                                    </td>
                                <tr>
                                    <td>Date of Rent</td>
                                    <td>Date of Return</td>
                                </tr>
                                <tr>
                                    <td>
                                        {$rent}
                                    </td>
                                    <td>
                                        {$return}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Price of Rent<br><br>
                                        {$rp} PHP
                                    </td>
                                    <td>
                                        DOWNPAYMENT DEADLINE:<br><br>
                                        {$tomorrow->format('Y-m-d h:i:s A')}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='2'>{$tstatus}
                                        <br><br>
                                        <form action='reservations.php' method='post'>
                                            <input type='hidden' value='{$tid}'  name='reservationid'>
                                            <input class='tablebutton' type='submit' name='deleteaction' value='Cancel'/>
                                            <br>
                                            <a href='customerupdate.php?cid={$cid}&tid={$tid}' id = 'tablea' ><div class = 'tablebutton'>Edit</div>
                                            </a>
                                    </td>
                                </tr>
                                </form>
                            </table>

                        ";

                        echo " 
                                <form action='reservations.php' method='post' enctype='multipart/form-data'>
                                    <input type='hidden' name='size' value='2000000'/><br>
                                    <input  class = 'uploadrec' id = 'upl'type='file' name='image' value = 'Upload Receipt'/><br>
                                    <input class = 'uploadrec' type='submit' value='Upload Image of Receipt' name='receiptupload{$tid}'/><br>
                                </form><br>
                                <a class='viewreceiptbutton' href='viewreceipt.php?id={$tid}'>View Receipt</a>
                                <br><br>
                        </div>

                            ";

                        if(isset($_POST["receiptupload{$tid}"])){
                            $targetreceipt = "images/".basename($_FILES['image']['name']);
                            if(isset($targetreceipt)){
                                $image = $_FILES['image']['name'];
                                if(move_uploaded_file($_FILES['image']['tmp_name'],$targetreceipt)){
                                    $sql = "INSERT INTO receipt(user_id,transaction_id,image) VALUES('$aid','$tid','$image')";
                                    mysqli_query($db,$sql);
                                    echo "<div class = 'error'>SUCCESSFUL UPLOAD OF RECEIPT</div>";    
                                } else {
                                    echo "<div class = 'error' >ERROR IN UPLOAD RECEIPT FOR TRANSACTION NO. {$tid}</div>";
                                    return;
                                }
                            } else {
                                echo "-ERROR-";
                                return;
                            }

                        }
                    }

                }
            }
        }   
    }

    function action() {
            if($_SESSION['userlevel'] == "Admin") {
                echo "<div class = 'reservationheader'>Customer Reservations</div>";
            } else if($_SESSION['userlevel'] == "Customer") {
                echo "<div class = 'reservationheader'>My Reservations</div>";
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
?>

<html>
    <head>
        <link rel='stylesheet' href='automax.css'/>
        <link rel='stylesheet' href='cars.css'/>
        <meta name='viewport' content='width-device-width, initial-scale=1.0'>
    </head>
    <body class = 'reservebody'>
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
        
        
                    <div class="custablecontainer">
                        <?php
                            action();
                        ?>
                        <!--<table class='reservetable' style='width:100%'>
                            <tr>                    
                                <th>Account ID</th>
                                <th>Car Model</th> 
                                <th>Car Type</th>
                                <th>Plate Number</th>
                                <th>Date of Rent</th>
                                <th>Return Date</th>
                                <th>Price of Rent</th>
                                <th colspan='2'>Booking Status</th>
                            </tr>
                        </table> -->
                        <center>
                      <?php
                            homecontent();
                            //echo $date. "<br>" .
                            //$timezone;
                        ?>
                        </center>
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