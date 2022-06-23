<?php 
    //Include COnstants Page
    include('../config/constants.php');

    //echo "Delete Food Page";

    if(isset($_GET['id']) && isset($_GET['image_name'])) //Either use '&&' or 'AND'
    {
        //Xử lý để xóa
        //echo "Process to Delete";

        //1. Nhận ID và NAme Hình ảnh
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //2.Xóa hình ảnh nếu có
        //Kiểm tra xem hình ảnh có sẵn hay không và chỉ Xóa nếu có
        if($image_name != "")
        {
            // Nếu có hình ảnh và cần xóa khỏi thư mục
            //Lấy đường dẫn hình ảnh
            $path = "../images/food/".$image_name;

            //Di chuyển tệp hình ảnh từ thư mục
            $remove = unlink($path);

            //Kiểm tra xem hình ảnh có bị xóa hay không
            if($remove==false)
            {
                //Không xóa được hình ảnh
                $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
                //REdirect to Manage Food
                header('location:'.SITEURL.'admin/manage-food.php');
                //Stop the Process of Deleting Food
                die();
            }

        }

        //3.Xóa thực phẩm khỏi cơ sở dữ liệu
        $sql = "DELETE FROM tbl_food WHERE id=$id";
        //Thực thi truy vấn
        $res = mysqli_query($conn, $sql);

        //Kiểm tra xem truy vấn có được thực thi hay không và đặt thông báo phiên tương ứng
        //4. Chuyển hướng sang Quản lý Thực phẩm với Thông báo Phiên
        if($res==true)
        {
            //Food Deleted
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";\
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //Failed to Delete Food
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";\
            header('location:'.SITEURL.'admin/manage-food.php');
        }

        

    }
    else
    {
        //Chuyển hướng đến Quản lý Trang Thực phẩm
        //echo "REdirect";
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>