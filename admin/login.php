<?php include('../config/constants.php'); ?>

<html>
    <head>
        <title>Login - Food Order System</title>
        <link rel="stylesheet" href="../css/admin.css">
    </head>

    <body >
        
        <div class="login">
            <h1 class="text-center">Login</h1>
            <br><br>

            <?php 
                if(isset($_SESSION['login']))
                {
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }

                if(isset($_SESSION['no-login-message']))
                {
                    echo $_SESSION['no-login-message'];
                    unset($_SESSION['no-login-message']);
                }
            ?>
            <br><br>

            <!-- Login Form Starts HEre -->
            <form action="" method="POST" class="text-center">
            Username: <br>
            <input type="text" name="username" placeholder="Enter Username"><br><br>

            Password: <br>
            <input type="password" name="password" placeholder="Enter Password"><br><br>

            <input type="submit" name="submit" value="Login" class="btn-primary " style="border: none;">
            <br><br>
            </form>
            <!-- Login Form Ends HEre -->

            <p class="text-center">Created By - <a href="www.vijaythapa.com">CSE485</a></p>
        </div>

    </body>
</html>

<?php 

    //Kiểm tra xem nút Gửi được Nhấp hay KHÔNG
    if(isset($_POST['submit']))
    {
        //Quy trình đăng nhập
        //1. Lấy dữ liệu từ biểu mẫu Đăng nhập
       
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        
        $raw_password = md5(md5($_POST['password']));
        $password = mysqli_real_escape_string($conn, $raw_password);

        //2. SQL để kiểm tra xem người dùng có tên người dùng và mật khẩu có tồn tại hay không
        $sql = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'";

        //3. Thực thi truy vấn
        $res = mysqli_query($conn, $sql);

        //4. đếm các hàng để kiểm tra xem người dùng có tồn tại hay không
        $count = mysqli_num_rows($res);

        if($count==1)
        {
            //Người đã có và đăng nhập thành công
            $_SESSION['login'] = "<div class='success'>Login Successful.</div>";
            $_SESSION['user'] = $username; //ĐỂ kiểm tra xem người dùng đã đăng nhập hay chưa và đăng xuất sẽ không đặt nó

            // Sửa lại Trang / Bảng điều khiển HOme
            header('location:'.SITEURL.'admin/');
        }
        else
        {
            //Người dùng không có sẵn và đăng nhập không có kết quả
            $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
            // Sửa lại Trang / Bảng điều khiển HOme
            header('location:'.SITEURL.'admin/login.php');
        }


    }

?>