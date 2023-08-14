<?php

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

//If the page is the main page

if($do == 'Manage'){

    echo 'Welcome, You Are in Manage Category Page';
    echo '<a href="page.php?do=Insert">Add New Category +</a>';

    }elseif($do == 'Add'){

        echo 'Welcome, You Are in Add Category Page';
    
    }elseif($do == 'Insert'){

        echo 'Welcome, You Are in Insert Category Page';

    }else{

        echo 'Error';

}