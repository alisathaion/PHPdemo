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
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form name="login" role="form" action="redirect.php?type=login" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                           value=""
                                           name="user"
                                           placeholder="Username or Phone Number"
                                           type="text"
                                           autofocus
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           placeholder="Password"
                                           type="password"
                                    />
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="btn btn-sm btn-default" href="signup.php">Sign Up</a>
            </div>
        </div>

    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
