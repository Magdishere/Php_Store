<?php
    ob_start();
    session_start();


    $pageTitle = 'Login';

    if(isset($_SESSION['user'])){

        header('Location: index.php');
    }

    include 'init.php';

     //Check if user is coming from HTTP POST request

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['login'])){

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

        //Check if user exists in Database

            $stmt = $con->prepare("SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ?");

            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();

        //If Count > 0 => The Database contain record about this Username

            if($count > 0){

                $_SESSION['user'] = $user; //Register Session Name
                $_SESSION['uid'] = $get['UserID']; //Register User ID

                header('Location: index.php'); //Redirect to dashboard page
                exit();
            }
        }else{

            $formErrors = array();

            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email    = $_POST['email'];

            if(isset($username)){

                $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
                
                if(strlen($filterdUser) < 4){

                    $formErrors[] = 'Username should be at least 4 charachters long';
                }
            }

            if(isset($password) && isset($password2) ){

                if(empty($password)){

                    $formErrors[] = 'Passowrd Can\'t be empty!';
                }

                if(sha1($password) !== sha1($password2)){

                    $formErrors[] = 'Password Doesn\'t match';
                }
            }

            if(isset($email)){

                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){

                    $formErrors[] = 'Email is not valid!';
                }
            }

            if (empty($formErrors)) {

                // Check If User Exist in Database
                $check = checkItem("Username", "users", $username);

                if ($check == 1) {

                    $formErrors[] = 'Username Is Not Available';

                } else {

                    // Insert Userinfo In Database

                    $stmt = $con->prepare("INSERT INTO 
                                                users(Username, Password, Email, RegStatus, Date)
                                            VALUES(:zuser, :zpass, :zmail, 0, now())");
                    $stmt->execute(array(

                        'zuser' 	=> $username,
                        'zpass' 	=> sha1($password),
                        'zmail' 	=> $email

                    ));

                    // Echo Success Message

                    $successMsg = 'Thank You For Signing Up!';

                

                }

            }
        }

    }

?>

    <div class="container login-page">
        
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | 
            <span data-class="signup">Sign Up</span>
        </h1>
        <!-- Start Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your Username" required />
            </div>
            <div class="input-container">
                <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Your Password" />
            </div>
            <div class="input-container">
                <input class="btn btn-primary btn-block submit" name="login" type="submit" value="Login" />
            </div>
        </form>
        <!-- End Login Form -->
        <!-- Start Signup Form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <div class="input-container">
                <input pattern=".{5,}" title="Username should be at least 4 charachters long" class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Username" required/>
            </div>
            <div class="input-container">
                <input minlength="8" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Password" required/>
            </div>
            <div class="input-container">
                <input minlength="8" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Confirm Password" required/>
            </div>
            <div class="input-container">
                <input class="form-control" type="email" name="email" placeholder="user@example.com" required/>
            </div>
            <input class="btn btn-primary btn-block submit" name="signup" type="submit" value="Sign Up" />
        </form>
        <!-- End Signup Form -->
        <div class="the-errors text-center">
            <?php

                if(!empty($formErrors)){
                    foreach($formErrors as $error){
                        echo '<div class="msg error">' . $error . '</div>';
                    }
                }

                if(isset($successMsg)){

                    echo '<div class="msg success">' . $successMsg . '</div>';
                }
            ?>
        </div>
    </div>





<?php 
    include $tpl . 'footer.php';
    ob_end_flush();
?>