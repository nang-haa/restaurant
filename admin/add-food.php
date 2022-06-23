<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
        
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Title of the Food">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Food."></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">

                            <?php 
                                //Create PHP Code to display categories from Database
                                //1. CReate SQL to get all active categories from database
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                
                                //Executing qUery
                                $res = mysqli_query($conn, $sql);

                                //Count Rows to check whether we have categories or not
                                $count = mysqli_num_rows($res);

                                //IF count is greater than zero, we have categories else we donot have categories
                                if($count>0)
                                {
                                    //WE have categories
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        //get the details of categories
                                        $id = $row['id'];
                                        $title = $row['title'];

                                        ?>

                                        <option value="<?php echo $id; ?>"><?php echo $title; ?></option>

                                        <?php
                                    }
                                }
                                else
                                {
                                    //WE do not have category
                                    ?>
                                    <option value="0">No Category Found</option>
                                    <?php
                                }
                            

                                //2. Display on Drpopdown
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes 
                        <input type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes 
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        
        <?php 

            //Kiểm tra xem nút có được nhấp hay không
            if(isset($_POST['submit']))
            {
                //Thêm thực phẩm vào cơ sở dữ liệu
                //echo "Clicked";
                
                //1. Nhận DAta từ Biểu mẫu
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                //Kiểm tra xem nút radion cho tính năng và hoạt động có được chọn hay không
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }
                else
                {
                    $featured = "No"; //Ghi giá trị mặc định
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No"; //Đặt giá trị mặc định
                }

                //2. Tải lên hình ảnh nếu được chọn
                //Kiểm tra xem hình ảnh đã chọn có được nhấp vào hay không và chỉ tải hình ảnh lên nếu hình ảnh được chọn
                if(isset($_FILES['image']['name']))
                {
                    //Nhận thông tin chi tiết của hình ảnh đã chọn
                    $image_name = $_FILES['image']['name'];

                    //Kiểm tra xem hình ảnh có được chọn hay không và chỉ tải lên hình ảnh nếu được chọn
                    if($image_name!="")
                    {
                        // Hình ảnh được chọn lọc
                        //A. Đổi tên hình ảnh
                        //Nhận phần mở rộng của hình ảnh đã chọn (jpg, png, gif, v.v.) "vijay-thapa.jpg" vijay-thapa jpg
                        $ext = end(explode('.', $image_name));

                        // Tạo tên mới cho hình ảnh
                        $image_name = "Food-Name-".rand(0000,9999).".".$ext; //New Image Name May Be "Food-Name-657.jpg"

                        //B. Tải lên hình ảnh
                        //Nhận đường dẫn Src và đường dẫn DEstinaton

                        // Đường dẫn nguồn là vị trí hiện tại của hình ảnh
                        $src = $_FILES['image']['tmp_name'];

                        //Đường dẫn đích cho hình ảnh được tải lên
                        $dst = "../images/food/".$image_name;

                        // Uppload the food image
                        $upload = move_uploaded_file($src, $dst);

                        //kiểm tra xem hình ảnh có được tải lên không
                        if($upload==false)
                        {
                            //Không tải lên được hình ảnh
                            //Sửa lại để Thêm Trang Thực phẩm có Thông báo Lỗi
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                            header('location:'.SITEURL.'admin/add-food.php');
                            //STop the process
                            die();
                        }

                    }

                }
                else
                {
                    $image_name = ""; //GỬI Giá trị mặc định là trống
                }

                //3. Chèn vào cơ sở dữ liệu

                //Tạo truy vấn SQL để lưu hoặc thêm thức ăn
                // Đối với Numerical, chúng ta không cần phải chuyển giá trị vào bên trong dấu ngoặc kép '' Nhưng đối với giá trị chuỗi thì bắt buộc phải thêm dấu ngoặc kép ''
                $sql2 = "INSERT INTO tbl_food SET 
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = $category,
                    featured = '$featured',
                    active = '$active'
                ";

                //Thực thi truy vấn
                $res2 = mysqli_query($conn, $sql2);

                //Kiểm tra xem dữ liệu đã được chèn hay chưa
                //4. Chuyển hướng với MEssage đến trang Quản lý thực phẩm
                if($res2 == true)
                {
                    //Đã chèn dữ liệu thành công
                    $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //FAiled để chèn dữ liệu
                    $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                
            }

        ?>


    </div>
</div>

<?php include('partials/footer.php'); ?>