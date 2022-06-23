<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>


        <?php 
        
            //Kiểm tra xem id đã được đặt hay chưa
            if(isset($_GET['id']))
            {
                //Nhận ID và tất cả các chi tiết khác
                //echo "Getting the Data";
                $id = $_GET['id'];
                //Tạo Truy vấn SQL để nhận tất cả các chi tiết khác
                $sql = "SELECT * FROM tbl_category WHERE id=$id";

                //Thực thi truy vấn
                $res = mysqli_query($conn, $sql);

                //Đếm các hàng để kiểm tra xem id có hợp lệ hay không
                $count = mysqli_num_rows($res);

                if($count==1)
                {
                    //Get all the data
                    $row = mysqli_fetch_assoc($res);
                    $title = $row['title'];
                    $current_image = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
                }
                else
                {
                    // chuyển hướng để quản lý danh mục với thông báo phiên
                    $_SESSION['no-category-found'] = "<div class='error'>Category not Found.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }

            }
            else
            {
                // chuyển hướng đến Quản lý danh mục
                header('location:'.SITEURL.'admin/manage-category.php');
            }
        
        ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                            if($current_image != "")
                            {
                                //Display the Image
                                ?>
                                <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width="150px">
                                <?php
                            }
                            else
                            {
                                // Hiển thị thông báo
                                echo "<div class='error'>Image Not Added.</div>";
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes 

                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No"> No 
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes 

                        <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No"> No 
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //echo "Clicked";
                // 1. Nhận tất cả các giá trị từ biểu mẫu của chúng tôi
                $id = $_POST['id'];
                $title = $_POST['title'];
                $current_image = $_POST['current_image'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //2. Cập nhật hình ảnh mới nếu được chọn
                //Kiểm tra xem hình ảnh đã được chọn hay chưa
                if(isset($_FILES['image']['name']))
                {
                    //Nhận chi tiết hình ảnh
                    $image_name = $_FILES['image']['name'];

                    //Kiểm tra xem hình ảnh có sẵn hay không
                    if($image_name != "")
                    {
                        //Hình ảnh có sẵn

                        //A. Tải lên hình ảnh mới

                        //Tự động đổi tên hình ảnh của chúng tôi
                        //Lấy phần mở rộng của hình ảnh của chúng tôi (jpg, png, gif, v.v.), ví dụ: "specialfood1.jpg"
                        $ext = end(explode('.', $image_name));

                        //Đổi tên hình ảnh
                        $image_name = "Food_Category_".rand(000, 999).'.'.$ext; // e.g. Food_Category_834.jpg
                        

                        $source_path = $_FILES['image']['tmp_name'];

                        $destination_path = "../images/category/".$image_name;

                        //Cuối cùng tải lên hình ảnh
                        $upload = move_uploaded_file($source_path, $destination_path);

                        //Kiểm tra xem hình ảnh có được tải lên hay không
                        //Và nếu hình ảnh không được tải lên thì chúng tôi sẽ dừng quá trình và chuyển hướng với thông báo lỗi
                        if($upload==false)
                        {
                            //SEt message
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
                            //Chuyển hướng đến Thêm Trang Danh mục
                            header('location:'.SITEURL.'admin/manage-category.php');
                            //STop the Process
                            die();
                        }

                        //B. Xóa hình ảnh hiện tại nếu có
                        if($current_image!="")
                        {
                            $remove_path = "../images/category/".$current_image;

                            $remove = unlink($remove_path);

                            //Kiểm tra xem hình ảnh có bị xóa hay không
                            //Nếu không xóa được thì hiển thị thông báo và dừng quá trình
                            if($remove==false)
                            {
                                //Không xóa được hình ảnh
                                $_SESSION['failed-remove'] = "<div class='error'>Failed to remove current Image.</div>";
                                header('location:'.SITEURL.'admin/manage-category.php');
                                die();//Stop the Process
                            }
                        }
                        

                    }
                    else
                    {
                        $image_name = $current_image;
                    }
                }
                else
                {
                    $image_name = $current_image;
                }

                //3. Cập nhật cơ sở dữ liệu
                $sql2 = "UPDATE tbl_category SET 
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active' 
                    WHERE id=$id
                ";

                //Thực thi truy vấn
                $res2 = mysqli_query($conn, $sql2);

                //4. Sửa lại để quản lý danh mục với MEssage
                //Kiểm tra xem có được thực thi hay không
                if($res2==true)
                {
                    //Đã cập nhật danh mục
                    $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
                else
                {
                    //không cập nhật được danh mục
                    $_SESSION['update'] = "<div class='error'>Failed to Update Category.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }

            }
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>