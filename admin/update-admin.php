<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Admin</h1>

        <br><br>

        <?php 
            //1. Lấy ID của quản trị viên đã chọn
            $id=$_GET['id'];

            //2. Tạo truy vấn SQL để lấy thông tin chi tiết
            $sql="SELECT * FROM tbl_admin WHERE id=$id";

            //Thực thi truy vấn
            $res=mysqli_query($conn, $sql);

            //Kiểm tra xem truy vấn có được thực thi hay không
            if($res==true)
            {
                // Kiểm tra xem dữ liệu có sẵn hay không
                $count = mysqli_num_rows($res);
                //Kiểm tra xem  có dữ liệu quản trị viên hay không
                if($count==1)
                {
                    // Nhận thông tin chi tiết
                    //echo "Admin Available";
                    $row=mysqli_fetch_assoc($res);

                    $full_name = $row['full_name'];
                    $username = $row['username'];
                }
                else
                {
                    //Chuyển hướng sang Quản lý PAge quản trị viên
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }
        
        ?>


        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" value="<?php echo $full_name; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>
    </div>
</div>

<?php 

    //Kiểm tra xem nút Gửi có được nhấp vào hay không
    if(isset($_POST['submit']))
    {
        //echo "Button CLicked";
        //Nhận tất cả các giá trị từ biểu mẫu để cập nhật
        $id = $_POST['id'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];

        //Tạo truy vấn SQL để cập nhật quản trị viên
        $sql = "UPDATE tbl_admin SET
        full_name = '$full_name',
        username = '$username' 
        WHERE id='$id'
        ";

        //Thực thi truy vấn
        $res = mysqli_query($conn, $sql);

        //Kiểm tra xem truy vấn có được thực thi thành công hay không
        if($res==true)
        {
            //Truy vấn được thực thi và được quản trị viên cập nhật
            $_SESSION['update'] = "<div class='success'>Admin Updated Successfully.</div>";
            //Chuyển hướng đến Quản lý Trang quản trị
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
        else
        {
            //Không cập nhật được quản trị viên
            $_SESSION['update'] = "<div class='error'>Failed to Delete Admin.</div>";
            //Chuyển hướng đến Quản lý Trang quản trị
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }

?>


<?php include('partials/footer.php'); ?>