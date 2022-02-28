<?php
date_default_timezone_set("Asia/Manila");
error_reporting(0);
$host = "localhost";
$user = "u964751242_whealth";
$dbname = "u964751242_whealth";
$pass = "6!xl+msLx";
$conn = mysqli_connect($host, $user, $pass, $dbname);

echo '<h4>Doctor:</h4>';
$getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl`");
while($getDoctorRow = mysqli_fetch_array($getDoctorQuery)) {
    echo $getDoctorRow['doctor_name'] . '<br/>Web: 
    '.(($getDoctorRow['doctor_sms_code'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getDoctorRow['doctor_sms_code'] != "@@@@@@@@EXIT@@@@@@@@") ? $getDoctorRow['doctor_sms_code'] : ""). '<br/>Mobile: '.(($getDoctorRow['doctor_sms_code_mobile'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getDoctorRow['doctor_sms_code_mobile'] != "@@@@@@@@EXIT@@@@@@@@") ? $getDoctorRow['doctor_sms_code_mobile'] : "").'<br/><br/>';
}

echo '<h4>Administrator:</h4>';
$getAdminQuery = mysqli_query($conn, "SELECT * FROM `admins_tbl`");
while($getAdminRow = mysqli_fetch_array($getAdminQuery)) {
    echo $getAdminRow['admin_name'] . '<br/>Web: '.(($getAdminRow['admin_sms_code'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getAdminRow['admin_sms_code'] != "@@@@@@@@EXIT@@@@@@@@") ? $getAdminRow['admin_sms_code'] : ""). '<br/>Mobile: '.(($getAdminRow['admin_sms_code_mobile'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getAdminRow['admin_sms_code_mobile'] != "@@@@@@@@EXIT@@@@@@@@") ? $getAdminRow['admin_sms_code_mobile'] : "").'<br/><br/>';
}

echo '<h4>Receptionist:</h4>';
$getRecptQuery = mysqli_query($conn, "SELECT * FROM `receptionists_tbl`");
while($getRecptRow = mysqli_fetch_array($getRecptQuery)) {
    echo $getRecptRow['receptionist_name'] . '<br/>Web: '.(($getRecptRow['receptionist_sms_code'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getRecptRow['receptionist_sms_code'] != "@@@@@@@@EXIT@@@@@@@@") ? $getRecptRow['receptionist_sms_code'] : "") . '<br/>Mobile: '.(($getRecptRow['receptionist_sms_code_mobile'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getRecptRow['receptionist_sms_code_mobile'] != "@@@@@@@@EXIT@@@@@@@@") ? $getRecptRow['receptionist_sms_code_mobile'] : "").'<br/><br/>';
}

echo '<h4>Parent:</h4>';
$getParentQuery = mysqli_query($conn, "SELECT * FROM `parents_tbl`");
while($getParentRow = mysqli_fetch_array($getParentQuery)) {
    echo $getParentRow['parent_name'] . '<br/>Web: '.(($getParentRow['parent_sms_code'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getParentRow['parent_sms_code'] != "@@@@@@@@EXIT@@@@@@@@") ? $getParentRow['parent_sms_code'] : "") . '<br/>Mobile: '.(($getParentRow['parent_sms_code_mobile'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getParentRow['parent_sms_code_mobile'] != "@@@@@@@@EXIT@@@@@@@@") ? $getParentRow['parent_sms_code_mobile'] : "").'<br/>Forgot: '.(($getParentRow['parent_sms_code_change'] != "@@@@@@@@SUCCESS@@@@@@@@" && $getParentRow['parent_sms_code_change'] != "@@@@@@@@EXIT@@@@@@@@") ? $getParentRow['parent_sms_code_change'] : "").'<br/><br/>';
}
?>