<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['add'])) //Kiểm tra xem SEssion có phải là Set of Not không
            {
                echo $_SESSION['add']; //Hiển thị Thông báo SEssion nếu SEt
                unset($_SESSION['add']); //Xóa thông báo phiên
            }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter Your Name">
                    </td>
                </tr>

                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>


    </div>
</div>

<?php include('partials/footer.php'); ?>


<?php 
    //Xử lý giá trị từ biểu mẫu và lưu nó trong cơ sở dữ liệu

    //Kiểm tra xem nút gửi có được nhấp hay không

    if(isset($_POST['submit']))
    {
        // Button Clicked
        //echo "Button Clicked";

        //1. Lấy dữ liệu từ form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = md5($_POST['password']); //Mã hóa mật khẩu với MD5

        //2. Truy vấn SQL để lưu dữ liệu vào cơ sở dữ liệu
        $sql = "INSERT INTO tbl_admin SET 
            full_name='$full_name',
            username='$username',
            password='$password'
        ";
 
        //3. Thực thi truy vấn và lưu dữ liệu vào Datbase
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

        //4. Kiểm tra xem dữ liệu (Truy vấn được thực thi) đã được chèn hay chưa và hiển thị thông báo thích hợp
        if($res==TRUE)
        {
            //Data Inserted
            //echo "Data Inserted";
            //Tạo một biến phiên để hiển thị thông báo
            $_SESSION['add'] = "<div class='success'>Admin Added Successfully.</div>";
            //Chuyển hướng Trang đến Quản lý Quản trị viên
            header("location:".SITEURL.'admin/manage-admin.php');
        }
        else
        {
            //FAiled to Insert DAta
            //echo "Faile to Insert Data";
            //Tạo một biến phiên để hiển thị thông báo
            $_SESSION['add'] = "<div class='error'>Failed to Add Admin.</div>";
            //Chuyển hướng trang để thêm quản trị viên
            header("location:".SITEURL.'admin/add-admin.php');
        }

    }
    
?>