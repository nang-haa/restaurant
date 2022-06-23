<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Food</h1>

        <br /><br />

                <!-- Nút để thêm quản trị viên -->
                <a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add Food</a>

                <br /><br /><br />

                <?php 
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add'];
                        unset($_SESSION['add']);
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];
                        unset($_SESSION['delete']);
                    }

                    if(isset($_SESSION['upload']))
                    {
                        echo $_SESSION['upload'];
                        unset($_SESSION['upload']);
                    }

                    if(isset($_SESSION['unauthorize']))
                    {
                        echo $_SESSION['unauthorize'];
                        unset($_SESSION['unauthorize']);
                    }

                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                
                ?>

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Featured</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>

                    <?php 
                        //Tạo một truy vấn SQL để nhận tất cả Food
                        $sql = "SELECT * FROM tbl_food";

                        //Thực thi truy vấn
                        $res = mysqli_query($conn, $sql);

                        // Đếm hàng để kiểm tra xem  có food hay không
                        $count = mysqli_num_rows($res);

                        //Tạo số sê-ri VAriable và đặt VAlue mặc định là 1
                        $sn=1;

                        if($count>0)
                        {
                            //có foood trong Cơ sở dữ liệu
                            //Lấy Thực phẩm từ Cơ sở dữ liệu và Hiển thị
                            while($row=mysqli_fetch_assoc($res))
                            {
                                //lấy các giá trị từ các cột riêng lẻ
                                $id = $row['id'];
                                $title = $row['title'];
                                $price = $row['price'];
                                $image_name = $row['image_name'];
                                $featured = $row['featured'];
                                $active = $row['active'];
                                ?>

                                <tr>
                                    <td><?php echo $sn++; ?>. </td>
                                    <td><?php echo $title; ?></td>
                                    <td>$<?php echo $price; ?></td>
                                    <td>
                                        <?php  
                                            //Kiểm tra xem chúng tôi có hình ảnh hay không
                                            if($image_name=="")
                                            {
                                                //CHÚNG TÔI không có hình ảnh, Thông báo lỗi DIslpay
                                                echo "<div class='error'>Image not Added.</div>";
                                            }
                                            else
                                            {
                                                //có img và hiển thị img
                                                ?>
                                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" width="100px">
                                                <?php
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo $featured; ?></td>
                                    <td><?php echo $active; ?></td>
                                    <td>
                                        <a href="<?php echo SITEURL; ?>admin/update-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update Food</a>
                                        <a href="<?php echo SITEURL; ?>admin/delete-food.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-danger">Delete Food</a>
                                    </td>
                                </tr>

                                <?php
                            }
                        }
                        else
                        {
                            //food không được thêm vào cơ sở dữ liệu
                            echo "<tr> <td colspan='7' class='error'> Food not Added Yet. </td> </tr>";
                        }

                    ?>

                    
                </table>
    </div>
    
</div>

<?php include('partials/footer.php'); ?>