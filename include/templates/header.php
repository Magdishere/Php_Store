<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title><?php getTitle() ?></title>
            <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.css">
            <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css">
            <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
            <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
            <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
</head>
        <body>
            <div class="upper-bar">
                <div class="container text-right">

                <?php 

                    if(isset($_SESSION['user'])){ ?>

                        <img class="my-image img-thumbnail img-circle" src="img.png" alt=""/>
                        <div class="btn-group my-info pull-right">
                            <span class=" btn dropdown-toggle" data-toggle="dropdown">
                                <?php echo $sessionUser ?>
                                <span class="caret"></span>
                            </span>
                            <ul class="dropdown-menu">
                                <li><a href="Profile.php">My Profile</li>
                                <li><a href="profile.php#my-ads">My Items</li>
                                <li><a href="newad.php">New Item</li>
                                <li><a href="logout.php">Log Out</li>
                            </ul>
                        </div>
                <?php
                    }else{
                ?>
                    <a href="login.php">
                        <span class="pull-right links ">Login/SignUp</span>
                    </a>
                    <?php } ?>
                </div>
            </div>
        <nav class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-header">
                        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="app-nav" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">Home</a>
                    </div>
                    <div class="collapse navbar-collapse" id="app-nav">
                        <ul class="nav navbar-nav navbar-right">
                        <?php
                            $allCats = getAllFrom('*', 'categories', 'where parent =0', '', 'ID' , 'ASC');
                            foreach($allCats as $cat){
                                echo '
                                <li>
                                <a href="categories.php?pageid=' . $cat['ID'] . '">'
                                    . $cat['Name'] . '
                                </a>
                                </li>';
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </nav>
            