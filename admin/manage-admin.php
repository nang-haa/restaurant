<?php include('partials/menu.php'); ?>


        <!-- Phần nội dung chính bắt đầu -->
        <div class="main-content">
            <div class="wrapper">
                <h1>Manage Users</h1>

                <br />

                <?php 
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add']; //Hiển thị thông báo phiên
                        unset($_SESSION['add']); //Điều chỉnh lại thông báo phiên
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }
                    
                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }

                    if(isset($_SESSION['user-not-found']))
                    {
                        echo $_SESSION['user-not-found'];
                        unset($_SESSION['user-not-found']);
                    }

                    if(isset($_SESSION['pwd-not-match']))
                    {
                        echo $_SESSION['pwd-not-match'];
                        unset($_SESSION['pwd-not-match']);
                    }

                    if(isset($_SESSION['change-pwd']))
                    {
                        echo $_SESSION['change-pwd'];
                        unset($_SESSION['change-pwd']);
                    }

                ?>
                <br><br><br>

                <!-- Nút để thêm quản trị viên -->
                <a href="add-admin.php" class="btn-primary">Add New User</a>

                <br /><br /><br />

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>

                    
                    <?php 
                        //Truy vấn để nhận tất cả quản trị viên
                        $sql = "SELECT * FROM tbl_admin";
                        //Thực thi truy vấn
                        $res = mysqli_query($conn, $sql);

                        //Kiểm tra xem truy vấn có được thực thi hay không
                        if($res==TRUE)
                        {
                            // Đếm hàng để kiểm tra xem chúng ta có dữ liệu trong cơ sở dữ liệu hay không
                            $count = mysqli_num_rows($res); //Hàm lấy tất cả các hàng trong cơ sở dữ liệu

                            $sn=1; //Tạo một biến và chỉ định giá trị

                            //Kiểm tra số lượng hàng
                            if($count>0)
                            {
                               
                                while($rows=mysqli_fetch_assoc($res))
                                {
                                    //Sử dụng vòng lặp While để lấy tất cả dữ liệu từ cơ sở dữ liệu.
                                    //Và vòng lặp while sẽ chạy miễn là chúng ta có dữ liệu trong cơ sở dữ liệu

                                    //Nhận DAta cá nhân
                                    $id=$rows['id'];
                                    $full_name=$rows['full_name'];
                                    $username=$rows['username'];

                                    //Hiển thị các Giá trị trong Bảng 
                                    ?>
                                    
                                    <tr>
                                        <td><?php echo $sn++; ?>. </td>
                                        <td><?php echo $full_name; ?></td>
                                        <td><?php echo $username; ?></td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>admin/update-password.php?id=<?php echo $id; ?>" class="btn-primary">Change Password</a>
                                            <a href="<?php echo SITEURL; ?>admin/update-admin.php?id=<?php echo $id; ?>" class="btn-secondary">Update Admin</a>
                                            <a href="<?php echo SITEURL; ?>admin/delete-admin.php?id=<?php echo $id; ?>" class="btn-danger">Delete Admin</a>
                                        </td>
                                    </tr>

                                    <?php

                                }
                            }
                            else
                            {
                                // không có dữ liệu trong cơ sở dữ liệu
                            }
                        }

                    ?>


                    
                </table>

            </div>
        </div>
        <!-- Main Content Setion Ends -->

<?php include('partials/footer.php'); ?>