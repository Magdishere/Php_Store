<?php

/* Manage Members: Add, Delete, Edit members from here */

session_start();

    $pageTitle = 'Members';

    if(isset($_SESSION['Username'])){

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){ //Manage Page 

            $query = '';

            if(isset($_GET['page']) && ($_GET['page'] = 'Pending')){

                $query = 'AND RegStatus = 0';
            }
        
        //Select all members except Admins
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();

        //Assign to Variable
        $rows = $stmt->fetchAll();
        
        if(!empty($rows)){
        ?>

            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center manage-members table table-bordered">
                        <tr>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                        
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>";
                                        if(empty($row['avatar'])){
                                            echo "No Image";
                                        }else{
                                            echo "<img src ='uploads/avatars/" . $row['avatar'] . "' alt ='' />";
                                        }
                                    
                                    echo "</td>";
                                    echo "<td>" . $row['Username'] . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>";
                                    echo "<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . $row['Date'] . "</td>";
                                    echo "<td>
                                        <a href='members.php?do=Edit&userid=" . $row['UserID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-edit'></i> Delete</a>";

                                        if($row['RegStatus'] == 0){
                                            echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] ."' class='btn btn-info activate'><i class='fa-solid fa-circle-check'></i> Activate</a>";
                                        }
                                        echo "</td>";
                                echo "</tr>";
                            }
                        
                        ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="manage-btn btn btn-primary"><i class="add-btn fa fa-plus"></i> Add New Member</a>
            </div>
            <?php 
                }else{
                    echo '<div class="container">';
                        echo '<div class="nice-message">There are no Members to show</div>';
                        echo '<a href="members.php?do=Add" class="manage-btn btn btn-primary"><i class="add-btn fa fa-plus"></i> Add New Member</a>';
                    echo '</div>';
            } ?>
            

       <?php }elseif($do == 'Add'){ //Add Members Page ?>
            
            <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Enter Your Username"/>
                            </div>
                        </div>
                        <!-- End Username Field -->
                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Enter Password" required="required"/>
                                <i class="show-pass fa fa-eye fa-2px"></i>
                            </div>
                        </div>
                        <!-- End Password Field -->
                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" name="email" class="form-control" required="required" placeholder="Enter Your Email"/>
                            </div>
                        </div>
                        <!-- End Email Field -->
                        <!-- Start Full Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="full" class="form-control" required="required" placeholder="Enter Full Name"/>
                            </div>
                        </div>
                        <!-- End Full Name Field -->
                        <!-- Start Avatar Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">User Picture</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" name="avatar" class="form-control" required="required"/>
                            </div>
                        </div>
                        <!-- End Avatar Field -->
                        <!-- Start Sumbit Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10 col-md-6">
                                <input type="submit" value="Add Member" class="add-member-btn btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- End Submit Field -->
                    </form>
                </div>
            
            

        <?php 
            } elseif($do == 'Insert'){ //Insert Member Page

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                echo "<h1 class='text-center'>Insert Members</h1>";
                echo "<div class='container'>";

                //Upload Variables

                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];

                //List of allowed file types to upload
                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

                //Get Avatar Extension
                $avtemp = explode('.', $avatarName);
                $avatarExtension = strtolower(end($avtemp));


                //Get data from the Form
                $user   = $_POST['username'];
                $pass   = $_POST['password'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                $hashPass = sha1($_POST['password']);

                //Validate Form
                $formErrors= array();

                if(empty($user)){
                    $formErrors[] = 'Username cant be empty';
                }
                if(empty($pass)){
                    $formErrors[] = 'Password cant be empty';
                }
                if(empty($name)){
                    $formErrors[] = 'Full Name cant be empty';
                }
                if(empty($email)){
                    $formErrors[] = 'Email cant be empty';
                }
                if(!empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)){
                    $formErrors[] = 'This Extension is not allowed!';
                }
                if(empty($avatarName)){
                    $formErrors[] = 'User Image is Required!';
                }
                if($avatarSize > 4194304){
                    $formErrors[] = 'User Image Cannot be larger than 4 Mb';
                }

                //Loop in the Error Array and echo it
                foreach($formErrors as $error){

                    echo '<div class="alert alert-danger">' . $error . '</div>';

                }

                //Check if there is no Errors to proceed the Update

            
				if (empty($formErrors)) {

                    $avatar = rand(0, 1000000) . "_" . $avatarName;
                    move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);


					// Check If User Exist in Database
					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

						redirectHome($theMsg, 'back');

					} else {

						// Insert Userinfo In Database

						$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, RegStatus, Date, avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)");
						$stmt->execute(array(

							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name,
                            'zavatar'   => $avatar
                        ));

						// Echo Success Message

						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

						redirectHome($theMsg, 'back');

					}

				}


			}else{

                $theMsg = '<div class="alert alert-danger">Sorry you cant browse this page directly<div>';
                redirectHome($theMsg, 'back');

            }

            echo "</div>";
            }

            elseif($do == 'Edit'){ //Edit Page 

            //check if Get Request userid is numeric and get the integer value of it
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
            //select all data depend on this ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

            //Execute query
            $stmt->execute(array($userid));
            //Fetch the data
            $row = $stmt->fetch();
            //Row Count
            $count = $stmt->rowCount();

            //If there is such id, show form

            if($count > 0){ ?>

                <h1 class="text-center">Edit Members</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required"/>
                            </div>
                        </div>
                        <!-- End Username Field -->
                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>"/>
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Enter New Password"/>
                            </div>
                        </div>
                        <!-- End Password Field -->
                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required"/>
                            </div>
                        </div>
                        <!-- End Email Field -->
                        <!-- Start Full Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required"/>
                            </div>
                        </div>
                        <!-- End Full Name Field -->
                        <!-- Start Avatar Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">User Picture</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" name="avatar" class="form-control" required="required"/>
                            </div>
                        </div>
                        <!-- End Avatar Field -->
                        <!-- Start Sumbit Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10 col-md-6">
                                <input type="submit" value="Save" class="save-member-btn btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- End Submit Field -->
                    </form>
                </div>
            
        <?php 
            //else show error message if there is no such ID
            }else{

                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">There is no such ID</div>';
                redirectHome($theMsg);
                echo "</div>";
            }
        } elseif($do == 'Update'){ //Update Page

            echo "<h1 class='text-center'>Update Members</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                //Get data from the Form
                $id     = $_POST['userid'];
                $user   = $_POST['username'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];
                $avatar   = $_POST['avatar'];

                //Password Trick
                $pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

                //Validate Form
                $formErrors= array();

                if(empty($user)){
                    $formErrors[] = 'Username cant be empty';
                }
                if(empty($name)){
                    $formErrors[] = 'Full Name cant be empty';
                }
                if(empty($email)){
                    $formErrors[] = 'Email cant be empty';
                }
                if(empty($avatar)){
                    $formErrors[] = 'Image cant be empty';
                }

                //Loop in the Error Array and echo it
                foreach($formErrors as $error){

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                //Check if there is no Errors to proceed the Update

            if(empty($formErrors)){

                $stmt2 = $con->prepare("SELECT *
                                        FROM    users
                                        WHERE   Username = ?
                                        AND     UserID != ?
                                        ");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                
                if($count == 1){

                    echo '<div class="container">';
                        echo $theMsg = '<div class="nice-message">Username Already Exists</div>';
                    echo '</div>';
                    redirectHome($theMsg, 'back');

                }else{

                    //Update Database with this info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, avatar = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $avatar, $id));

                    //Echo Success Message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');

                }

            }

            }else{

                $theMsg = '<div class="alert alert-danger">Sorry you cant browse this page directly</div>';
                redirectHome($theMsg);
            }

            echo "</div>";

        }elseif($do == 'Delete'){

            echo "<h1 class='text-center'>Delete Members</h1>";
            echo "<div class='container'>";

                //check if Get Request userid is numeric and get the integer value of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                //select all data depend on this ID
                $check = checkItem('userid', 'users', $userid);
                

                //If there is such id, show form
                
                if($check > 0){

                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                    $stmt->bindParam(":zuser", $userid);
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' User Deleted</div>';
                    redirectHome($theMsg, 'back');


                }else{

                    $theMsg = '<div class="alert alert-danger">ID does not exist</div>';
                    redirectHome($theMsg);
                }
                
            echo '</div>';

        } elseif($do == 'Activate'){

            echo "<h1 class='text-center'>Activate Members</h1>";
            echo "<div class='container'>";

                //check if Get Request userid is numeric and get the integer value of it
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                //select all data depend on this ID
                $check = checkItem('userid', 'users', $userid);
                

                //If there is such id, show form
                
                if($check > 0){

                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                    $stmt->execute(array($userid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' User Activated</div>';
                    redirectHome($theMsg);


                }else{

                    $theMsg = '<div class="alert alert-danger">ID does not exist</div>';
                    redirectHome($theMsg);
                }
                
            echo '</div>';

        }
        
        include $tpl . 'footer.php';

    }else{

        header('Location: index.php');
        exit();

    }