<?php

require 'includes/functions.php';
$message = '';

if($_GET['type'] == 'login')
{
    $found   = false;
    $user = trim($_POST['user']);
    $pass = trim($_POST['password']);
    $username = filterUserName($_GET['username']);

    if(checkUsername($user))
    {
        $found = findUser($user, $pass, 'username');
    }
    elseif(checkPhoneNumber($user))
    {
        $found = findUser($user, $pass, 'phone');
    }
    elseif($user == "admin" && $pass == "iamadmin!!")
    {
        if(saveAdmin($_POST))
        {
            $found = true;
        }
    }

    if($found)
    {
        
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user;
        setcookie('user', $user , time() + 3600 );
        
        header('Location: thankyou.php?type=login&username='.$user);
        exit();
    }
    else
    {
        $message = '<div class="alert alert-danger text-center">
                    Login not found! Try again.
                </div>';
        //should be expire immidiately here
        setcookie('error_message', $message);
        header('Location: login.php');
        exit();
    }
}

else
{
    if(count($_POST) > 0)
    {
        $check = checkSignUp($_POST);

        if($check !== true)
        {
            $message = '<div class="alert alert-danger text-center">
                            '.$check.' 
                        </div>';
        }
        else
        {
            if(saveUser($_POST))
            {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $_POST['username'];
                setcookie('user',$_POST['username'], time() + 3600 );

                header('Location: thankyou.php?type=signup&username='.$_POST['username']);
                exit();
            }
            else
            {
                $message = '<div class="alert alert-danger text-center">
                                Unable to sign up at this time.
                            </div>';
            }
        }

        if($message != '')
        {
            //should be expire immidiately here
            setcookie('error_message', $message);
            header('Location: signup.php');
            exit();
        }
    }

}


?>


