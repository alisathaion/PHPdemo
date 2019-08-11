<?php
$username = '';
$id = '';

//Session 
session_start();
if(isset($_SESSION['loggedin']))
{
    $username =  $_SESSION['username'];
    $id = $_GET['id'];
}
else{
    header('Location:index.php');
    exit();
}

if(file_exists('posts.txt'))
{
    //local variable initiation
    $postArray = array();
    $postFile = array();
    $deletekey = '';

    $postFile = file('posts.txt');
    
    foreach($postFile as $key => $line)
    {
        $postArray = preg_split('/\|/', $line);
        if(trim($postArray[0]) == $id && trim($postArray[1]) == $username)
        {
            $deletekey = $key;
        }
        elseif(trim($postArray[0]) == $id && $username == "admin")
        {
            $deletekey = $key;
        }

    }
    
    unset($postFile[$deletekey]);
    file_put_contents('posts.txt', $postFile);
    header('Location:posts.php');
    exit();
   
}
?>

