<?php

/* Manage Comments: Approve, Delete, Edit members from here */
    ob_start();
    session_start();

    $pageTitle = 'Comments';

    if(isset($_SESSION['Username'])){

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Manage Members Page

			// Select All Users Except Admin 

			$stmt = $con->prepare("SELECT 
										comments.*, items.Name AS Item_Name, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										items 
									ON 
										items.Item_ID = comments.item_id
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									ORDER BY 
										c_id DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$comments = $stmt->fetchAll();

			if (! empty($comments)) {

			?>

			<h1 class="text-center">Manage Comments</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>Comment</td>
							<td>Item Name</td>
							<td>User Name</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($comments as $comment) {
								echo "<tr>";
									echo "<td>" . $comment['c_id'] . "</td>";
									echo "<td>" . $comment['comment'] . "</td>";
									echo "<td>" . $comment['Item_Name'] . "</td>";
									echo "<td>" . $comment['Member'] . "</td>";
									echo "<td>" . $comment['comment_date'] ."</td>";
									echo "<td>
										<a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($comment['status'] == 0) {
											echo "<a href='comments.php?do=Approve&comid="
													. $comment['c_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Comments To Show</div>';
				echo '</div>';

			} ?>

       <?php } elseif($do == 'Edit'){ //Edit Page 

            //check if Get Request comid is numeric and get the integer value of it
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            
            //select all data depend on this ID
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");

            //Execute query
            $stmt->execute(array($comid));
            //Fetch the data
            $row = $stmt->fetch();
            //Row Count
            $count = $stmt->rowCount();

            //If there is such id, show form

            if($count > 0){ ?>

                <h1 class="text-center">Edit Comments</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                        <!-- Start Comment Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                            </div>
                        </div>
                        <!-- End Commment Field -->
                        <!-- Start Sumbit Field -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10 col-md-6">
                                <input type="submit" value="Save" class="add-member-btn btn btn-primary btn-lg"/>
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

            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                //Get data from the Form
                $comid     = $_POST['comid'];
                $comment   = $_POST['comment'];

                //Check if there is no Errors to proceed the Update

            if(empty($formErrors)){

                //Update Database with this info
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));

                //Echo Success Message

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Updated</div>';
                redirectHome($theMsg, 'back');
            }

            }else{

                $theMsg = '<div class="alert alert-danger">Sorry you cant browse this page directly</div>';
                redirectHome($theMsg);
            }

            echo "</div>";

        }elseif($do == 'Delete'){

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                //check if Get Request comid is numeric and get the integer value of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                //select all data depend on this ID
                $check = checkItem('c_id', 'comments', $comid);
                

                //If there is such id, show form
                
                if($check > 0){

                    $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
                    $stmt->bindParam(":zid", $comid);
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Deleted</div>';
                    redirectHome($theMsg, 'back');


                }else{

                    $theMsg = '<div class="alert alert-danger">ID does not exist</div>';
                    redirectHome($theMsg);
                }
                
            echo '</div>';

        } elseif($do == 'Approve'){

            echo "<h1 class='text-center'>Approve Comments</h1>";
            echo "<div class='container'>";

                //check if Get Request userid is numeric and get the integer value of it
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
                
                //select all data depend on this ID
                $check = checkItem('c_id', 'comments', $comid);
                

                //If there is such id, show form
                
                if($check > 0){

                    $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                    $stmt->execute(array($comid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Approved</div>';
                    redirectHome($theMsg, 'back');


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
    ob_end_flush();