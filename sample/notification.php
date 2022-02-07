<?php
    $host = "localhost";
    $user = "u964751242_sample";
    $dbname = "u964751242_sample";
    $pass = "Feutech123";
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    
    /**if($conn) {
        echo 'Database is connected';
    } else {
        echo 'Database is not connected';
    }**/
    
    $query = mysqli_query($conn, "SELECT * FROM `notifications` WHERE `account_id` = '".$_GET['id']."'");
    $query2 = mysqli_query($conn, "SELECT * FROM `notifications` WHERE `account_id` = '".$_GET['id']."' AND `seen` = 0");
    $howManyNotification = mysqli_num_rows($query2);

    echo '<h4>Notification ('.$howManyNotification.')</h4>';
    echo '<table border="1">';
    echo '<tbody>';
    $count = 1;
    while($row = mysqli_fetch_array($query)) {
        echo '<tr>';
            echo '<td>'.$count.'</td>';
            echo '<td>'.$row['text'].'</td>';
            echo '<td>'.date("M d Y h:iA", $row['timestamp']).'</td>';
            if($row['seen'] == 0) {
                echo '<td style="background:red;">&nbsp;&nbsp;&nbsp;</td>';
            } else {
                echo '<td style="background:green;">&nbsp;&nbsp;&nbsp;</td>';
            }
        echo '</tr>';
        $count++;
    }
    echo '</tbody>';
    echo '</table>';
    echo '<br/>';
    echo '<b>Legends:</b><br/>';
    echo '<span style="color:red;">Red</span> means \'first time to see that notification\'<br/>';
    echo '<span style="color:green;">Green</span> means \'many times to see that notification\'<br/>';

    $query = mysqli_query($conn, "UPDATE `notifications` SET `seen` = 1 WHERE `account_id` = '".$_GET['id']."'");
?>