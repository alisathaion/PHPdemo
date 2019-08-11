<?php
//import function
require 'includes/functions.php';

//Global variable initiation
$message = '';
$username = "";
$valid = false;
$lists = array();


//Session 
session_start();
if(isset($_SESSION['loggedin']))
{
    $username =  $_SESSION['username'];
}
else{
    header('Location:index.php');
    exit();
}

//write file
if(count($_POST) > 0)
{
    $check = checkPost($_POST);
    if($check !== true)
    {
        $message = '<div class="alert alert-danger text-center">
                        '.$check.' 
                    </div>';
    }
    else
    {
        savePost($_POST);
    }
}

//read file
if(file_exists('posts.txt'))
{
    
    //local variable initiation
    $postArray = array();
    $postFile = array();
    $divclass = '';

    $postFile = file('posts.txt');

    if(trim(filesize('posts.txt')) != 0)
    {
        $valid = true;

        foreach($postFile as $line)
        {
            $postArray = preg_split('/\|/', $line);
            if(trim($postArray[4]) == '1')
            {
                $divclass = 'panel panel-danger';
                $postArray[5] = $divclass;
            }
            else if(trim($postArray[4]) == '2')
            {
                $divclass = 'panel panel-warning';
                $postArray[5] = $divclass;
            }
            else
            {
                $divclass= 'panel panel-info';
                $postArray[5] = $divclass;
            }
            array_push($lists, $postArray);
        
        }

        function cmp($a, $b)
        {
            return strcmp($a[4], $b[4]);
        }

        usort($lists,"cmp");
    }
 
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
                <h1 class="login-panel text-center text-muted">
                PHP Demo
                </h1>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?php 
                if($username != "admin"){
                    echo '<button class="btn btn-default" data-toggle="modal" data-target="#newPost" id="newpost"><i class="fa fa-comment"></i> New Post</button>';
                }
                ?>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>

        <?php 
        if($message != "")
        {
            echo '<div class="row">
                    <div class="col-md-6 col-md-offset-3">' . $message . '</div>
                  </div>'; 
        }
        ?>

        <?php
        if($valid)
        {
            foreach($lists as $list)
            {
                echo '  <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                        <div class="'.$list[5].'">
                        <div class="panel-heading">
                        <span>
                            '. $list[2] .'
                        </span>';
        
                if($username == $list[1] || $username == "admin")
                {
                    echo '<span class="pull-right text-muted">
                            <a class="" href="delete.php?id='.$list[0].'">
                            <i class="fa fa-trash"></i>Delete
                            </a>
                          </span>';
                          
                }
                if($username == "admin")
                {
                    echo '<span class="pull-right text-muted">
                    <a class="modal-dialog" data-toggle="modal" onclick=myFunction("'.$list[0].'")>
                    <i class="fa fa-edit"></i>Edit
                    </a>&nbsp;
                    </span>';
                }

                          
                echo '  </div>
                        <div class="panel-body">
                            <p class="text-muted">
                            </p>
                            <p>
                                ' . $list[3] . '
                            </p>
                        </div>
                        <div class="panel-footer">
                            <p>
                                By ' . $list[1]  . '
                            </p>
                        </div>
                        </div>
                        </div>
                        </div>';
            }  
        }
        ?>
        <script>
        
        function myFunction(ID){
            var author ="";
            var title = "";
            var comment ="";
            var priority ="";
                
            $.get('posts.txt', function(data) {    
                var lines = data.split("\n");

                lines.forEach(function(element) {
                    var item = element.split("|");
                    if(item[0] == ID)
                    {
                        author = item[1].trim();
                        title = item[2].trim();
                        comment = item[3].trim();
                        priority = item[4].trim();
                    }
                });

                $("#username").val(author);
                $("#title").val(title);
                $("#comment").val(comment);
                $("#priority").val(priority);
                $("#Id").val(ID);        
            });
            $('#newPost').modal('show');
        }

        </script>
    </div>
</div>


<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">

    <form role="form" method="post" action="posts.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Post</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <input class="form-control" placeholder="Username" name="username" value="<?php echo $username;?>" id="username" readonly>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" placeholder="" name="title" id="title">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" rows="3" name="comment" id="comment"></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select class="form-control" name="priority" id="priority">
                        <option value="1">Important</option>
                        <option value="2">High</option>
                        <option value="3">Normal</option>
                    </select>
                </div>
                <input type="hidden" id="Id" name="Id" value="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Post!"/>
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
