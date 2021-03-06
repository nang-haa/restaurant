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

            //Ki???m tra xem n??t c?? ???????c nh???p hay kh??ng
            if(isset($_POST['submit']))
            {
                //Th??m th???c ph???m v??o c?? s??? d??? li???u
                //echo "Clicked";
                
                //1. Nh???n DAta t??? Bi???u m???u
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                //Ki???m tra xem n??t radion cho t??nh n??ng v?? ho???t ?????ng c?? ???????c ch???n hay kh??ng
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }
                else
                {
                    $featured = "No"; //Ghi gi?? tr??? m???c ?????nh
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No"; //?????t gi?? tr??? m???c ?????nh
                }

                //2. T???i l??n h??nh ???nh n???u ???????c ch???n
                //Ki???m tra xem h??nh ???nh ???? ch???n c?? ???????c nh???p v??o hay kh??ng v?? ch??? t???i h??nh ???nh l??n n???u h??nh ???nh ???????c ch???n
                if(isset($_FILES['image']['name']))
                {
                    //Nh???n th??ng tin chi ti???t c???a h??nh ???nh ???? ch???n
                    $image_name = $_FILES['image']['name'];

                    //Ki???m tra xem h??nh ???nh c?? ???????c ch???n hay kh??ng v?? ch??? t???i l??n h??nh ???nh n???u ???????c ch???n
                    if($image_name!="")
                    {
                        // H??nh ???nh ???????c ch???n l???c
                        //A. ?????i t??n h??nh ???nh
                        //Nh???n ph???n m??? r???ng c???a h??nh ???nh ???? ch???n (jpg, png, gif, v.v.) "vijay-thapa.jpg" vijay-thapa jpg
                        $ext = end(explode('.', $image_name));

                        // T???o t??n m???i cho h??nh ???nh
                        $image_name = "Food-Name-".rand(0000,9999).".".$ext; //New Image Name May Be "Food-Name-657.jpg"

                        //B. T???i l??n h??nh ???nh
                        //Nh???n ???????ng d???n Src v?? ???????ng d???n DEstinaton

                        // ???????ng d???n ngu???n l?? v??? tr?? hi???n t???i c???a h??nh ???nh
                        $src = $_FILES['image']['tmp_name'];

                        //???????ng d???n ????ch cho h??nh ???nh ???????c t???i l??n
                        $dst = "../images/food/".$image_name;

                        // Uppload the food image
                        $upload = move_uploaded_file($src, $dst);

                        //ki???m tra xem h??nh ???nh c?? ???????c t???i l??n kh??ng
                        if($upload==false)
                        {
                            //Kh??ng t???i l??n ???????c h??nh ???nh
                            //S???a l???i ????? Th??m Trang Th???c ph???m c?? Th??ng b??o L???i
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                            header('location:'.SITEURL.'admin/add-food.php');
                            //STop the process
                            die();
                        }

                    }

                }
                else
                {
                    $image_name = ""; //G???I Gi?? tr??? m???c ?????nh l?? tr???ng
                }

                //3. Ch??n v??o c?? s??? d??? li???u

                //T???o truy v???n SQL ????? l??u ho???c th??m th???c ??n
                // ?????i v???i Numerical, ch??ng ta kh??ng c???n ph???i chuy???n gi?? tr??? v??o b??n trong d???u ngo???c k??p '' Nh??ng ?????i v???i gi?? tr??? chu???i th?? b???t bu???c ph???i th??m d???u ngo???c k??p ''
                $sql2 = "INSERT INTO tbl_food SET 
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = $category,
                    featured = '$featured',
                    active = '$active'
                ";

                //Th???c thi truy v???n
                $res2 = mysqli_query($conn, $sql2);

                //Ki???m tra xem d??? li???u ???? ???????c ch??n hay ch??a
                //4. Chuy???n h?????ng v???i MEssage ?????n trang Qu???n l?? th???c ph???m
                if($res2 == true)
                {
                    //???? ch??n d??? li???u th??nh c??ng
                    $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //FAiled ????? ch??n d??? li???u
                    $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                
            }

        ?>


    </div>
</div>

<?php include('partials/footer.php'); ?>