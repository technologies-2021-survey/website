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
    
    if(isset($_GET['login'])) {
        $id = $_GET['id'];
    }

    if(isset($_POST['insertNotification'])) {
        $id = $_POST['id'];
        $text = $_POST['text'];
        $timestamp = time();
        mysqli_query($conn, "INSERT INTO `notifications` (`account_id`, `text`, `timestamp`) VALUES ('".$id."','".$text."','".$timestamp."')");
        header("location: ?success");
    }
    
?>

<?php
    // hindi pa nakalogin
    if($_GET['id'] == "") {
?>
<h4>Login</h4>
<form action="" method="GET">
    <input type="text" name="id" placeholder="Account ID">
    <input type="submit" name="login" placeholder="Log In">
</form>
<hr/>
<h4>Insert notification</h4>
<form action="" method="POST">
    <input type="text" name="id" placeholder="Account ID">
    <input type="text" name="text" placeholder="Text">
    <input type="submit" name="insertNotification" placeholder="Insert notification">
</form>
<?php
    } else {
        // Login
        $query = mysqli_query($conn, "SELECT * FROM `notifications` WHERE `account_id` = '".$_GET['id']."' AND `seen` = '0'");
        $howManyNotification = mysqli_num_rows($query);
?>
    <a href="./notification.php?id=<?=$_GET['id'];?>">You have <?=$howManyNotification;?> notifications!</a>
<?php
    }
?>