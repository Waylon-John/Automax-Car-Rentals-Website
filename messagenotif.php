<?php
    //MESSAGE NOTIFICATION FEATURE.
    $sign = null;
    $notif = $db->prepare("SELECT status FROM message WHERE status = 'UNREAD' AND sender_id <> 1 LIMIT 1");
    $notif->execute();
    $notif->bind_result($result);
    while ($notif->fetch()){
        if($result == "UNREAD"){
            $sign = "<label id = 'notif'>[NEW]</label>";
        }
    }
?>