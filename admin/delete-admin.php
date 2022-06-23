<?php 

    //Include constants.php file here
    include('../config/constants.php');

    // 1. xóa ID của Admin
    $id = $_GET['id'];

    //2. Tạo truy vấn SQL để xóa quản trị viên
    $sql = "DELETE FROM tbl_admin WHERE id=$id";

    //Thực thi truy vấn
    $res = mysqli_query($conn, $sql);

    // Kiểm tra xem truy vấn có được thực thi thành công hay không
    if($res==true)
    {
        //Truy vấn được thực thi thành công và quản trị viên bị xóa
        //echo "Admin Deleted";
        //Tạo biến SEssion để hiển thị thông báo
        $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully.</div>";
        //Chuyển hướng đến Quản lý Trang quản trị
        header('location:'.SITEURL.'admin/manage-admin.php');
    }
    else
    {
        //Không xóa được quản trị viên
        //echo "Failed to Delete Admin";

        $_SESSION['delete'] = "<div class='error'>Failed to Delete Admin. Try Again Later.</div>";
        header('location:'.SITEURL.'admin/manage-admin.php');  //3. Chuyển hướng đến trang Quản lý quản trị với thông báo (thành công / lỗi)
    }

  

?>