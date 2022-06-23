<?php include('partials/menu.php'); ?>

<?php 
    //Kiểm tra xem id đã được đặt hay chưa
    if(isset($_GET['id']))
    {
        //Nhận tất cả các chi tiết
        $id = $_GET['id'];

        //Truy vấn SQL để lấy thực phẩm đã chọn
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
        //thực hiện truy vấn
        $res2 = mysqli_query($conn, $sql2);

        //Nhận giá trị dựa trên truy vấn được thực thi
        $row2 = mysqli_fetch_assoc($res2);

        //Nhận các giá trị riêng của thực phẩm đã chọn
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];

    }
    else
    {
        // Chuyển hướng đến Quản lý thực phẩm
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>


<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
        
        <table class="tbl-30">

            <tr>
                <td>Title: </td>
                <td>
                    <input type="text" name="title" value="<?php echo $title; ?>">
                </td>
            </tr>

            <tr>
                <td>Description: </td>
                <td>
                    <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Price: </td>
                <td>
                    <input type="number" name="price" value="<?php echo $price; ?>">
                </td>
            </tr>

            <tr>
                <td>Current Image: </td>
                <td>
                    <?php 
                        if($current_image == "")
                        {
                            //Image not Available 
                            echo "<div class='error'>Image not Available.</div>";
                        }
                        else
                        {
                            //Image Available
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                            <?php
                        }
                    ?>
                </td>
            </tr>

            <tr>
                <td>Select New Image: </td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>

            <tr>
                <td>Category: </td>
                <td>
                    <select name="category">

                        <?php 
                            // Truy vấn để lấy danh mục ACtive
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                           // Thực thi truy vấn
                            $res = mysqli_query($conn, $sql);
                            //Đếm hàng
                            $count = mysqli_num_rows($res);

                            //Kiểm tra xem danh mục có sẵn hay không
                            if($count>0)
                            {
                                //Danh mục có sẵn
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];
                                    
                                    //echo "<option value='$category_id'>$category_title</option>";
                                    ?>
                                    <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                    <?php
                                }
                            }
                            else
                            {
                                //Danh mục không khả dụng
                                echo "<option value='0'>Category Not Available.</option>";
                            }

                        ?>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Featured: </td>
                <td>
                    <input <?php if($featured=="Yes") {echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes 
                    <input <?php if($featured=="No") {echo "checked";} ?> type="radio" name="featured" value="No"> No 
                </td>
            </tr>

            <tr>
                <td>Active: </td>
                <td>
                    <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Yes 
                    <input <?php if($active=="No") {echo "checked";} ?> type="radio" name="active" value="No"> No 
                </td>
            </tr>

            <tr>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                    <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                </td>
            </tr>
        
        </table>
        
        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //echo "Button Clicked";

                //1. Nhận tất cả các chi tiết từ form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];

                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //2. Tải lên hình ảnh nếu được chọn

                //Kiểm tra xem nút tải lên có được nhấp hay không
                if(isset($_FILES['image']['name']))
                {
                    //Upload BUtton Clicked
                    $image_name = $_FILES['image']['name']; //New Image NAme

                    //Kiểm tra xem tệp thứ có sẵn hay không
                    if($image_name!="")
                    {
                        //IMage is Available
                        //A. Tải lên hình ảnh mới

                        //Đổi tên hình ảnh
                        $ext = end(explode('.', $image_name)); //Nhận phần mở rộng của hình ảnh

                        $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; //Nó sẽ được đổi tên hình ảnh

                        //Get the Source Path and DEstination PAth
                        $src_path = $_FILES['image']['tmp_name']; //Source Path
                        $dest_path = "../images/food/".$image_name; //DEstination Path

                        //Upload the image
                        $upload = move_uploaded_file($src_path, $dest_path);

                        /// Kiểm tra xem hình ảnh có được tải lên hay không
                        if($upload==false)
                        {
                            //FAiled to Upload
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload new Image.</div>";
                            //REdirect để quản lý thực phẩm
                            header('location:'.SITEURL.'admin/manage-food.php');
                            //Stop the Process
                            die();
                        }
                        //3.Xóa hình ảnh nếu hình ảnh mới được tải lên và hình ảnh hiện tại tồn tại
                        //B. Xóa hình ảnh hiện tại nếu có
                        if($current_image!="")
                        {
                            //Hình ảnh hiện tại có sẵn
                            //REmove the image
                            $remove_path = "../images/food/".$current_image;

                            $remove = unlink($remove_path);

                            //Kiểm tra xem hình ảnh có bị xóa hay không
                            if($remove==false)
                            {
                                //không thể xóa hình ảnh hiện tại
                                $_SESSION['remove-failed'] = "<div class='error'>Faile to remove current image.</div>";
                                //chuyển hướng để quản lý thực phẩm
                                header('location:'.SITEURL.'admin/manage-food.php');
                                //stop the process
                                die();
                            }
                        }
                    }
                    else
                    {
                        $image_name = $current_image; //Hình ảnh mặc định khi Hình ảnh không được chọn
                    }
                }
                else
                {
                    $image_name = $current_image; //Hình ảnh mặc định khi nút không được nhấp
                }

                

                //4.Cập nhật thực phẩm trong cơ sở dữ liệu
                $sql3 = "UPDATE tbl_food SET 
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = '$category',
                    featured = '$featured',
                    active = '$active'
                    WHERE id=$id
                ";

                //Thực thi truy vấn SQL
                $res3 = mysqli_query($conn, $sql3);

                //Kiểm tra xem truy vấn có được thực thi hay không
                if($res3==true)
                {
                    //Truy vấn được khám phá và cập nhật thực phẩm
                    $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //Failed to Update Food
                    $_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                
            }
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>