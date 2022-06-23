<?php 
    //Include constants.php for SITEURL
    include('../config/constants.php');
    //1. Hủy phiên
    session_destroy(); //Unsets $_SESSION['user']

    //2.Sửa lại trang đăng nhập
    header('location:'.SITEURL.'admin/login.php');

?>