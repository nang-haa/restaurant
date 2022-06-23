<?php 

    //Ủy quyền - Truy cập COntrol
    //Kiểm tra xem người dùng đã đăng nhập hay chưa
    if(!isset($_SESSION['user'])) 
    {
        //Người dùng chưa đăng nhập
        //Sửa lại trang đăng nhập bằng tin nhắn
        $_SESSION['no-login-message'] = "<div class='error text-center'>Please login to access Admin Panel.</div>";
        //Sửa lại trang đăng nhập
        header('location:'.SITEURL.'admin/login.php');
    }

?>