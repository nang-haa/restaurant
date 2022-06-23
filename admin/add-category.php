<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>

        <br><br>

        <?php 
        
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }

            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        
        ?>

        <br><br>

        <!-- Add CAtegory Form Starts -->
        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image">
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
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>
        <!-- Add CAtegory Form Ends -->

        <?php 
        
            //Kiểm tra xem nút gửi có được nhấp hay không
            if(isset($_POST['submit']))
            {
                //echo "Clicked";

                //1. lấu Giá trị từ form 
                $title = $_POST['title'];

                //Đối với đầu vào Radio, chúng ta cần kiểm tra xem nút đã được chọn hay chưa
                if(isset($_POST['featured']))
                {
                    //Lấy dữ liệu từ form
                    $featured = $_POST['featured'];
                }
                else
                {
                    //SEt the VAlue mặc định
                    $featured = "No";
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No";
                }

                //Kiểm tra xem hình ảnh có được chọn hay không và đặt giá trị cho tên hình ảnh
                //print_r($_FILES['image']);

                //die();//Break the Code Here

                if(isset($_FILES['image']['name']))
                {
                    //Tải lên hình ảnh
                    //Để tải lên hình ảnh, chúng ta cần tên hình ảnh, đường dẫn nguồn và đường dẫn đích
                    $image_name = $_FILES['image']['name'];
                    
                    // Tải lên hình ảnh chỉ khi hình ảnh được chọn
                    if($image_name != "")
                    {

                        //Tự động đổi tên hình ảnh của chúng tôi
                        //Lấy phần mở rộng của hình ảnh của chúng tôi (jpg, png, gif, v.v.), ví dụ: "specialfood1.jpg"
                        $ext = end(explode('.', $image_name));

                        //Đổi tên hình ảnh
                        $image_name = "Food_Category_".rand(000, 999).'.'.$ext; // e.g. Food_Category_834.jpg
                        

                        $source_path = $_FILES['image']['tmp_name'];

                        $destination_path = "../images/category/".$image_name;

                        // tải  hình ảnh 
                        $upload = move_uploaded_file($source_path, $destination_path);

                        //Kiểm tra xem hình ảnh có được tải lên hay không
                        //Và nếu hình ảnh không được tải lên thì  sẽ dừng quá trình và chuyển hướng với thông báo lỗi
                        if($upload==false)
                        {
                           
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
                            //Chuyển hướng đến Thêm Trang Danh mục
                            header('location:'.SITEURL.'admin/add-category.php');
                            
                            die();
                        }

                    }
                }
                else
                {
                    //Không tải lên hình ảnh và đặt giá trị image_name thành trống
                    $image_name="";
                }

                //2. Tạo truy vấn SQL để chèn CAtegory vào cơ sở dữ liệu
                $sql = "INSERT INTO tbl_category SET 
                    title='$title',
                    image_name='$image_name',
                    featured='$featured',
                    active='$active'
                ";

                //3. Thực thi Truy vấn và Lưu trong Cơ sở dữ liệu
                $res = mysqli_query($conn, $sql);

                //4. Kiểm tra xem truy vấn có được thực thi hay không và dữ liệu được thêm vào hay không
                if($res==true)
                {
                    //Truy vấn được thực thi và danh mục được thêm vào
                    $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
                    //Chuyển hướng đến Quản lý Trang Danh mục
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
                else
                {
                    //Không thể thêm CAtegory
                    $_SESSION['add'] = "<div class='error'>Failed to Add Category.</div>";
                    //Chuyển hướng đến Quản lý Trang Danh mục
                    header('location:'.SITEURL.'admin/add-category.php');
                }
            }
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>