<?php 
    ob_start();
    session_start();
    $pageTitle = 'Profile';
    include 'init.php';

    
    if(isset($_SESSION['user'])){

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];

?>

<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-lock"></i>  <span>Username</span>: <?php  echo  $info['Username'] ?> </li>
                    <li><i class="fas fa-envelope"></i>  <span>Email</span>: <?php  echo $info['Email'] ?> </li>
                    <li><i class="fas fa-user-alt"></i>  <span>Full Name</span>: <?php  echo $info['FullName'] ?> </li>
                    <li><i class="fas fa-calendar-check"></i>  <span>Registered Date</span>: <?php  echo $info['Date'] ?> </li>
                    <li><i class="fas fa-tags"></i>  <span>Favorite Category</span>: <?php  echo $info['UserID'] ?> </li>
                <ul>
                <a href="" class="edit-info add-item-btn btn btn-primary btn-md">Edit Info</a>
            </div>
        </div>
    </div>
</div>
<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Ads</div>
            <div class="panel-body">
                    <?php
                        $myItems = getAllFrom('*', 'items', "where Member_ID = $userid", '', "Item_ID");
                        if(! empty($myItems)){
                            echo '<div class="row">';
                            foreach($myItems as $item){
                                echo '<div class="col-sm-6 col-md-3">';
                                    echo '<div class="thumbnail item-box">';
                                        if($item['Approve'] == 0){ echo '<span class="approve-status">Waiting Approval</span>'; }
                                        echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                        echo '<img class="img-responsive" src="godofwar.jpg" alt=""/>';
                                        echo '<div class="caption">';
                                            echo '<h4><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h4>';
                                            echo '<p>' . $item['Description'] . '</p>';
                                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }else{
                            echo 'There are no Ads to show! Create A <a href="newad.php"> New Ad</a>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php
                    $myComments = getAllFrom('comment', 'comments', "where user_id = $userid", '', "c_id");

                    if(!empty($myComments)){
                        foreach($myComments as $comment){

                            echo '<p>' . $comment['comment'] . '</p>';
                        }
                    }else{
                        echo 'There are no comments to show!';
                    }
                ?>
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