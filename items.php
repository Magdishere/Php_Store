<?php 
    ob_start();
    session_start();
    $pageTitle = 'Show Items';
    include 'init.php';

    //check if Get Request itemid is numeric and get the integer value of it
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
    //select all data depend on this ID
    $stmt = $con->prepare("SELECT 
                                items.*, 
                                categories.Name AS category_name, 
                                users.Username 
                            FROM 
                                items
                            INNER JOIN 
                                categories 
                            ON 
                                categories.ID = items.Cat_ID 
                            INNER JOIN 
                                users 
                            ON 
                                users.UserID = items.Member_ID 
                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1");

    //Execute query
    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    if($count > 0){

            //Fetch the data
    $item = $stmt->fetch();
    
    ?>  
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="godofwar.jpg" alt=""/>
            </div>
            <div class="col-md-6 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description']  ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-calendar-check fa-fw"></i>
                        <span>Added Date</span>:  <?php echo $item['Add_Date']?>
                    </li>
                    <li>
                        <i class="fa fa-dollar fa-fw"></i>
                        <span>Price</span>: <?php echo $item['Price']?>
                    </li>
                    <li>
                        <i class="fa-solid fa-earth-americas fa-fw"></i>
                        <span>Made In</span>: <?php echo $item['Country_Made']?>
                    </li>
                    <li>
                        <i class="fas fa-tags fa-fw"></i>
                        <span>Category</span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"> <?php echo $item['category_name']?></a>
                    </li>
                    <li>
                        <i class="fas fa-user-alt fa-fw"></i> 
                        <span>Added By</span>: <a href="profile.php?itemid=<?php echo $item['Member_ID'] ?>"> <?php echo $item['Username']?></a>
                    </li>
                    <li>
                        <i class="fa-solid fa-hashtag"></i> 
                        <span>Tags</span>:
                        <?php
                            $allTags = explode(",", $item['tags']);
                            foreach($allTags as $tag){
                                $tag = str_replace(' ', '', $tag);
                                $lowertag = strtolower($tag);
                                if(!empty($tag)){
                                    echo "<a class='tag-items' href='tags.php?name={$lowertag}'>" . '#'.$tag . '</a> ';
                                }
                                
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if(isset($_SESSION['user'])) { ?>
        <!-- Start Add Comment -->
        <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Leave a comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                        <textarea name="comment" class="form-control" required></textarea>
                        <input class="btn btn-primary comment-area" type="submit" value="Add Comment">
                    </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD']== 'POST'){

                            $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $itemid 	= $item['Item_ID'];
                            $userid 	= $_SESSION['uid'];

                            if (! empty($comment)) {

                                $stmt = $con->prepare("INSERT INTO 
                                    comments(comment, status, comment_date, item_id, user_id)
                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
    
                                $stmt->execute(array(
    
                                    'zcomment' => $comment,
                                    'zitemid' => $itemid,
                                    'zuserid' => $userid
    
                                ));

                                if ($stmt) {

                                    echo '<div class="alert alert-success">Comment Added</div>';
    
                                }
    
                            } else {
    
                                echo '<div class="alert alert-danger">You Must Add Comment</div>';
    
                            }

                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- End Add Comment -->
        <?php } else{
            echo 'You Must <a href="login.php">Login</a> or <a href="login.php">Register</a> To Add A Comment';
        } 
        
        ?>
        <hr class="custom-hr">
        <?php
                        // Select All Users Except Admin 

                    $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username AS Member  
                                        FROM 
                                            comments
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.UserID = comments.user_id
                                        WHERE
                                            item_id = ?
                                        AND
                                            status = 1
                                        ORDER BY 
                                            c_id DESC");

                                        // Execute The Statement

                    $stmt->execute(array($item['Item_ID']));

                    // Assign To Variable 

                    $comments = $stmt->fetchAll();
                ?>

            <?php foreach($comments as $comment) { ?>
                <div class="comment-box">
                    <div class="row">
                            <div class="col-sm-2 text-center"> 
                                <img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt=""/>
                                <?php echo $comment['Member'] ?> 
                            </div>
                            <div class="col-md-10"> 
                                <p class="lead"><?php echo $comment['comment'] ?></p>
                            </div>
                        </div>
                </div>
                <hr class="custom-hr">
            <?php } ?>
        </div>
    </div>
<?php

    }else{
        $Msg = '<div class="item-error-msg"> There\'s no such ID or Item is Waiting Approval </div>';
        redirectHome($Msg, 'back');
    }
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>