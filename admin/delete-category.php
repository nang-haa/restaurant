<?php 
    //Include Constants File
    include('../config/constants.php');

    //echo "Delete Page";
    //Kiểm tra xem giá trị id và image_name đã được đặt hay chưa
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        //Nhận giá trị và xóa
        //echo "Nhận giá trị và xóa";
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //Xóa tệp hình ảnh  có sẵn
        if($image_name != "")
        {
            //Hình ảnh có sẵn. Vì vậy, loại bỏ nó
            $path = "../images/category/".$image_name;
            //Di chuyển lại hình ảnh
            $remove = unlink($path);

            //NẾU không xóa được hình ảnh thì hãy thêm thông báo lỗi và dừng quá trình
            if($remove==false)
            {
                //Đặt thông điệp SEssion
                $_SESSION['remove'] = "<div class='error'>Failed to Remove Category Image.</div>";
                //Sửa lại để quản lý trang Danh mục
                header('location:'.SITEURL.'admin/manage-category.php');
                //Stop the Process
                die();
            }
        }

        //Xóa dữ liệu khỏi cơ sở dữ liệu
        //Truy vấn SQL để xóa dữ liệu khỏi cơ sở dữ liệu
        $sql = "DELETE FROM tbl_category WHERE id=$id";

        // Thực thi truy vấn
        $res = mysqli_query($conn, $sql);

        // Kiểm tra xem dữ liệu có bị xóa khỏi cơ sở dữ liệu hay không
        if($res==true)
        {
            
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
            //Chuyển hướng sang Quản lý danh mục
            header('location:'.SITEURL.'admin/manage-category.php');
        }
        else
        {
           
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Category.</div>";
            //Chuyển hướng sang Quản lý danh mục
            header('location:'.SITEURL.'admin/manage-category.php');
        }

 

    }
    else
    {
        //chuyển hướng đến Quản lý Trang Danh mục
        header('location:'.SITEURL.'admin/manage-category.php');
    }
?>