<?php

$message = '';
if(isset($_COOKIE['error_message']))
{
    $message = $_COOKIE['error_message'];
    setcookie('error_message',null,time()-3600);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Demo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?php echo $message; ?>
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create Account</h3>
                    </div>
                    <div class="panel-body">
                        <form name="signup" role="form" action="redirect.php?type=signup" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                           value=""
                                           name="username"
                                           placeholder="Username"
                                           type="text"
                                           autofocus
                                    />
                                    <p class="text-danger" style="font-size: 70%;" >must be at least 8 characters, start with letter, end with number, no special characters</P>
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           value=""
                                           name="phoneNumber"
                                           placeholder="Phone Number"
                                           type="text"
                                           autofocus
                                    />
                                    <p class="text-danger" style="font-size: 70%;">must be 7 or 10 digits only, Ex xxxxxxxxxx or xxx-xxx-xxxx or xxx xxx xxxx or (xxx)xxxxxxx</p>
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           placeholder="Password"
                                           type="password"
                                    />
                                    <p class="text-danger" style="font-size: 70%;">must be at least 8 characters, one lower case letter, one upper case letter, one number, one special character (, / ? * !)</p>
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="verify_password"
                                           placeholder="Verify Password"
                                           type="password"
                                    />
                                </div>
                                <input type="submit" class="btn btn-lg btn-info btn-block" value="Sign Up!"/>
                            </fieldset><br />
                            <a class="btn btn-sm btn-default" href="login.php">Login</a>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
