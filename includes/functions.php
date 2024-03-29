<?php

define('SALT', 'a_very_random_salt_for_this_app');

/**
 * Look up the user & password pair from the text file.
 *
 * User can be the username or the phone number.
 * Passwords are simple md5 hashed.
 *
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $user string The username or phone number to look up
 * @param $password string The password to look up
 * @param $field string user|phone
 * @return bool true if found, false if not
 */
function findUser($user, $password, $field)
{
    $found = false;

    $lines = file('users.txt');

    foreach($lines as $line)
    {
        $pieces = preg_split("/\|/", $line); // | is a special character, so escape it

        if($field == 'username' && $pieces[0] == $user && trim($pieces[2]) == md5($password . SALT))
        {
            $found = true;
        }
        elseif($field == 'phone' && $pieces[1] == $user && trim($pieces[2]) == md5($password . SALT))
        {
            $found = true;
        }
    }

    return $found;
}

/**
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $data
 * @return bool returns false if fopen() or fwrite() fails
 */
function saveUser($data)
{
    $success = false;

    $fp = fopen('users.txt', 'a+');

    if($fp != false)
    {
        $username       = trim($data['username']);
        $phoneNumber    = trim(preg_replace("/[^0-9]/", '', $data['phoneNumber']));
        $password       = trim($data['password']);
        $passwordHash   = md5($password . SALT);

        $results = fwrite($fp, $username.'|'.$phoneNumber.'|'.$passwordHash. PHP_EOL);

        fclose($fp);

        if($results)
        {
            $success = true;
        }
    }

    return $success;
}

function saveAdmin($data)
{
    $success = false;

    $fp = fopen('admin.ini', 'a+');

    if($fp != false)
    {
        $user = trim($data['user']);
        $pwd = trim($data['password']);

        $results = fwrite($fp, $user . ','. $pwd . PHP_EOL);

        fclose($fp);

        if($results)
        {
            $success = true;
        }
    }

    return $success;
}

function checkUsername($username)
{
    return preg_match('/^[a-z]([a-z]|[0-9]){6}([a-z]|[0-9])*[0-9]+$/i', $username);
}

function checkPhoneNumber($phoneNumber)
{
    // assuming phone numbers can start with a 0
    return preg_match("/^[0-9]{7}$|^[0-9]{10}$/", $phoneNumber);
}

/**
 * @param $data
 * @return bool|string
 */
function checkSignUp($data)
{
    $valid = true;

    // if any of the fields are missing, return an error
    if( trim($data['username'])        == '' ||
        trim($data['phoneNumber'])     == '' ||
        trim($data['password'])        == '' ||
        trim($data['verify_password']) == '')
    {
        $valid = "All inputs are required.";
    }
    elseif(!preg_match('/^[a-z]([a-z]|[0-9]){6}([a-z]|[0-9])*[0-9]+$/i', trim($data['username'])))
    {
        $valid = "Invalid username!";
    }
    elseif(!preg_match("/^((\([0-9]{3}\))|([0-9]{3}))?( |-)?[0-9]{3}( |-)?[0-9]{4}$/", trim($data['phoneNumber'])))
    {
        $valid = "Invalid phone number!";
    }
    else if(!preg_match('/((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[,.\/\?\*!])){8}/', trim($data['password'])))
    {
        $valid = "Invalid password!";
    }
    elseif($data['password'] != $data['verify_password'])
    {
        $valid = 'Passwords do not match!';
    }

    return $valid;
}

function filterUserName($name)
{
    // if it's not alphanumeric, replace it with an empty string
    return preg_replace("/[^a-z0-9]/i", '', $name);
}

/**
 * @param $data
 * @return bool|string
 */
function checkPost($data)
{
    $valid = true;

    // if any of the fields are missing, return an error
    if( trim($data['username'])        == '' ||
        trim($data['title'])     == '' ||
        trim($data['comment'])        == '' ||
        trim($data['priority']) == '')
    {
        $valid = "POST ERROR!! All inputs are required.";
    }

    elseif(!preg_match('/^[a-z]([a-z]|[0-9]){6}([a-z]|[0-9])*[0-9]+$/i', trim($data['username']))  &&  trim($data['username']) != 'admin' )
    {
        $valid = "POST ERROR!! Invalid username.";
    }
    elseif(!preg_match('/^[a-zA-Z\s]+$/', trim($data['title'])))
    {
        $valid = "POST ERROR!! Invalid title.";
    }
    else if(!preg_match('/^[a-zA-Z\s0-9\,.?!]+$/', trim($data['comment'])))
    {
        $valid = "POST ERROR!! Invalid comment.";
    }
    elseif(!preg_match('/^[0-9]{1}/', trim($data['priority'])))
    {
        $valid = 'POST ERROR!! Invalid priority.';
    }

    return $valid;
}
function savePost($data)
{
    
    $success = false;
    $fp = fopen('posts.txt', 'a+');
    if($fp != false)
    {
        $id = trim($_POST['Id']);
        $user = trim($_POST['username']);
        $title = trim($_POST['title']);
        $comment = trim($_POST['comment']);
        $priority = trim($_POST['priority']);
        if(trim($_POST['Id'] == ""))
        {
            $id = uniqid();
        }
        else
        {
            $postArray = array();
            $postFile = array();
            $deletekey = '';

            $postFile = file('posts.txt');
            foreach($postFile as $key => $line)
            {
                $postArray = preg_split('/\|/', $line);
                if(trim($postArray[0]) == $id)
                {
                    $deletekey = $key;
                }
            }
            unset($postFile[$deletekey]);
            file_put_contents('posts.txt', $postFile);

        }
      
        $results = fwrite($fp, $id . '|' .$user . '|' . $title . '|' . $comment . '|' . $priority . PHP_EOL);
        fclose($fp);

        if($results)
        {
            $success = true;
        }
    }

    return $success;
}