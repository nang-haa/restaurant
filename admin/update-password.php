<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php 
            if(isset($_GET['id']))
            {
                $id=$_GET['id'];
            }
        ?>

        <form action="" method="POST">
        
            <table class="tbl-30">
                <tr>
                    <td>Current Password: </td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password: </td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

    </div>
</div>

<?php 

            //Kiểm tra xem nút Gửi có được Nhấp vào Không
            if(isset($_POST['submit']))
            {
                //echo "CLicked";

                //1. Nhận DAta từ Biểu mẫu
                $id=$_POST['id'];
                $current_password = md5($_POST['current_password']);
                $new_password = md5($_POST['new_password']);
                $confirm_password = md5($_POST['confirm_password']);


                //2. Kiểm tra xem người dùng có ID hiện tại và mật khẩu hiện tại còn tồn tại hay không
                $sql = "SELECT * FROM tbl_admin WHERE id=$id AND password='$current_password'";

                //Thực thi truy vấn
                $res = mysqli_query($conn, $sql);

                if($res==true)
                {
                    //Kiểm tra xem dữ liệu có sẵn hay không
                    $count=mysqli_num_rows($res);

                    if($count==1)
                    {
                        //Người dùng tồn tại và mật khẩu có thể được thay đổi
                        //echo "User FOund";

                        //Kiểm tra xem mật khẩu mới và xác nhận có khớp hay không
                        if($new_password==$confirm_password)
                        {
                            //Update the Password
                            $sql2 = "UPDATE tbl_admin SET 
                                password='$new_password' 
                                WHERE id=$id
                            ";

                            //Execute the Query
                            $res2 = mysqli_query($conn, $sql2);

                            //Kiểm tra xem truy vấn có được miễn trừ hay không
                            if($res2==true)
                            {
                                //Display Succes Message
                                //Sửa lại để Quản lý Trang Quản trị với Thông báo Thành công
                                $_SESSION['change-pwd'] = "<div class='success'>Password Changed Successfully. </div>";
                                //Chuyển hướng người dùng
                                header('location:'.SITEURL.'admin/manage-admin.php');
                            }
                            else
                            {
                                //Hiển thị thông báo lỗi
                                //Sửa lại để quản lý trang quản trị có thông báo lỗi
                                $_SESSION['change-pwd'] = "<div class='error'>Failed to Change Password. </div>";
                                //Chuyển hướng sử dụngr
                                header('location:'.SITEURL.'admin/manage-admin.php');
                            }
                        }
                        else
                        {
                            //Sửa lại để quản lý trang quản trị có thông báo lỗi
                            $_SESSION['pwd-not-match'] = "<div class='error'>Password Did not Patch. </div>";
                            //Redirect the User
                            header('location:'.SITEURL.'admin/manage-admin.php');

                        }
                    }
                    else
                    {
                        //Người dùng không tồn tại set tin nhắn và sửa lại
                        $_SESSION['user-not-found'] = "<div class='error'>User Not Found. </div>";
                        //Redirect the User
                        header('location:'.SITEURL.'admin/manage-admin.php');
                    }
                }

                //3. Kiểm tra xem mật khẩu mới và xác nhận mật khẩu có khớp hay không

               
            }

?>


<?php include('partials/footer.php'); ?>