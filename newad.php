<?php 
    ob_start();
    session_start();
    $pageTitle = 'Create New Item';
    include 'init.php';

    
    if(isset($_SESSION['user'])){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErros = array();

            $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 4){
                $formErros[] = 'Item Name Must Be At Least 4 Charachters!';
            }
            if(strlen($desc) < 10){
                $formErros[] = 'Description must be at least 10 charachters';
            }
            if(strlen($name) < 3){
                $formErros[] = 'Country must be at least 3 charachters';
            }
            if(empty($price)){
                $formErros[] = 'Price can\'t be empty!';
            }
            if(empty($status)){
                $formErros[] = 'Status can\'t be empty!';
            }
            if(empty($category)){
                $formErros[] = 'Category can\'t be empty!';
            }

            // Check If There's No Error Proceed The Update Operation

            if (empty($formErrors)) {

                // Insert Userinfo In Database

                $stmt = $con->prepare("INSERT INTO 
                    items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                $stmt->execute(array(

                    'zname' 	=> $name,
                    'zdesc' 	=> $desc,
                    'zprice' 	=> $price,
                    'zcountry' 	=> $country,
                    'zstatus' 	=> $status,
                    'zcat'		=> $category,
                    'zmember'	=> $_SESSION['uid'],
                    'ztags'     => $tags
                    

                ));

                    // Echo Success Message

                    if($stmt){

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Item Added Successfully!</div>';

                    redirectHome($theMsg, 'back');
                    }

                
            }

        }

?>

<h1 class="text-center"> <?php echo $pageTitle ?> </h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Create Item</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                            <!-- Start Name Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="name" class="form-control live" autocomplete="off"  required="required" placeholder="Enter Item Name" data-class=".live-name"/>
                                </div>
                            </div>
                            <!-- End Name Field -->
                            <!-- Start Description Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="description" class="form-control live" autocomplete="off"  required="required" placeholder="Enter Item Description" data-class=".live-desc"/>
                                </div>
                            </div>
                            <!-- End Description Field -->
                            <!-- Start Price Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="price" class="form-control live" autocomplete="off"  required="required" placeholder="Item Price" data-class=".live-price"/>
                                </div>
                            </div>
                            <!-- End Price Field -->
                            <!-- Start Country of origin Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country </label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="country" class="form-control" autocomplete="off"  required="required" placeholder="Country of origin"/>
                                </div>
                            </div>
                            <!-- End Country of origin Field -->
                            <!-- Start Status Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="status" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->
                            <!-- Start Categories Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category" required>
                                    <option value="">...</option>
                                        <?php
                                            $cats = getAllFrom('*', 'categories', '', '', 'ID');
                                            foreach ($cats as $cat){
                                                echo "<option value='". $cat['ID'] ."'>" .$cat['Name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Categories Field -->
                            <!-- Start Tags Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="tags" class="form-control" autocomplete="off" placeholder="Seperate Tags with a ' , '"/>
                                </div>
                            </div>
                            <!-- End Tags Field -->
                            <!-- Start Submit Field -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-3 col-sm-10 col-md-9">
                                    <input type="submit" value="Add Item" class="add-item-btn btn btn-primary btn-md"/>
                                </div>
                            </div>
                            <!-- End Submit Field -->
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" src="godofwar.jpg" alt=""/>
                            <div class="caption">
                                <h3 class="live-name"></h3>
                                <p class="live-desc"> Bla bla bla</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start looping thru errors -->
                <?php

                    if(!empty($formErros)){
                        foreach($formErros as $error){
                            echo '<div class="alert-error-item-form">' . $error . '</div>';
                        }
                    }
                ?>
                <!-- End looping thru errors -->
            </div>
        </div>
    </div>
</div>

<?php

    }else{

        header('Location: login.php');
        exit();
    }

    include $tpl . 'footer.php'; 
    ob_end_flush();
?>