<?php
session_start();
//including the connection page
include  "dbConnection.php";




    
    
    // define variables to empty values  
$info1 = "";
 $passErr1 = $repeatErr1 = $emailerr1 = $cpassErr1 = $avatarErr1 = "";
 $myass1 = $myemail1 = $img1 = "";
$isvalidate1 = true;
$_SESSION['mypasserror1']=$_SESSION['mycpasserror1']=$_SESSION['myemailerror1']=$_SESSION['myavatarerror1']=$_SESSION['mycerror1']=$_SESSION['msg_error1']="";
$_SESSION['error_stat1']="0";

$accepted_format = array("image/png", "image/jpg", "image/jpeg");
$php_file_error = [];
$php_file_error[0] = 'There is no error, the file uploaded with success';
$php_file_error[1] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
$php_file_error[2] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
$php_file_error[3] = 'The uploaded file was only partially uploaded';
$php_file_error[4] = 'No file was uploaded';
$php_file_error[6] = 'Missing a temporary folder';
$php_file_error[7] = 'Failed to write file to disk.';
$php_file_error[8] = 'A PHP extension stopped the file upload.';




//Input fields validation  
if (isset($_POST['updateuser']) && !empty($_POST['updateuser'])) {
    unset($_POST['updateuser']);
    
    
    
    if ( !empty($_POST['password1']) && !empty($_POST['cpassword1']) && !empty($_POST['email1'])  ) {
        
        //validate password
        if (!empty($_POST["password1"])) {
            // password strength validation

            $password1 = trim($_POST["password1"]);

            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password1);
            $lowercase = preg_match('@[a-z]@', $password1);
            $number    = preg_match('@[0-9]@', $password1);
            $specialChars = preg_match('@[^\w]@', $password1);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password1) < 6) {

                $passErr1 = 'invalid Password .';
                $_SESSION['mypasserror1'] = $passErr1;


                $isvalidate1 = false;
            }
        } else {
            $passErr1 = 'enter Password .';
            $_SESSION['mypasserror1'] = $passErr1;


            $isvalidate1 = false;
        }
        
        //cpassword validation
        if (!empty($_POST["cpassword1"])) {
            $cpassword1 = trim($_POST["cpassword1"]);

            // Validate cpassword strength
            $uppercase = preg_match('@[A-Z]@', $cpassword1);
            $lowercase = preg_match('@[a-z]@', $cpassword1);
            $number    = preg_match('@[0-9]@', $cpassword1);
            $specialChars = preg_match('@[^\w]@', $cpassword1);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($cpassword1) < 6) {

                $cpassErr1 = 'Invalid confirm password.';

                $_SESSION['mycpasserror1'] = $cpassErr1;
                $isvalidate1 = false;
            }
        } else {
            $cpassErr1 = 'enter Password confirmation .';
            $_SESSION['mycpasserror1'] = $cpassErr1;


            $isvalidate1 = false;
        }
        
        //email validation

        if (!empty($_POST["email1"])) {
            //check the format of the email
            $email1 = trim($_POST['email1']);
            if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
                $emailerr1 = 'enter valid email address';
                $_SESSION['myemailerror1'] = $emailerr1;

                $isvalidate1 = false;
            }
        } else {
            $emailerr1 = 'enter your email address';
            $_SESSION['myemailerror1'] = $emailerr1;

            $isvalidate1 = false;
        }

        //   check if confirm pass and pass are the same
        if ($_POST['password1'] != $_POST['cpassword1']) {
            $repeatErr1 = 'your password should be the same as your confirm.';

            $isvalidate1 = false;

            $_SESSION['mycerror1'] = $repeatErr1;
        }
    
     


        // on submit if there is no error
        if ($isvalidate1 === true && $repeatErr1 == "" && $emailerr1 == "" && $cpassErr1 == "" && $passErr1 == ""  ) {

            //create a file based on each username for users
            
            $mypass1 = trim($_POST["password1"]);
            $myemail1 = trim($_POST["email1"]);

            // if no new image is selected
            if (($_FILES["avatar1"])=="") {
               
                
                //update  user based on the old image
                $image_address = $_SESSION['this_user_avatar_new'];
                $query1 = $db->prepare("UPDATE users SET password=?,email=?,avatar=? WHERE username=? ;") or die("Query failed: " . $query1());
                $query1->execute(array($mypass1, $myemail1, $image_address, $_SESSION['this_user_username_new']));
                //change log in status
                $_SESSION['this_user_login_new'] = "0";
                echo "<script>alert('your info updated,login back.');</script>";
               echo  "<script>window.location.href='index.php';</script>";







            } else if(($_FILES["avatar1"])!="") {

                // //make the path of the avatar and check other character of image to be correct
                $image_name = $_FILES['avatar1']['name'];
                $image_type = $_FILES['avatar1']['type'];
                $image_size = $_FILES['avatar1']['size'];
                $image_temp_name = $_FILES['avatar1']['tmp_name'];
                $array_name = explode(".", $image_name);
                $image_format = end($array_name); //get last index of array
                //make an address for saving avatar

                $image_address = "user_image/" .  trim($_SESSION['this_user_username_new']) . "." . $image_format;

                //check for general errors
                if (!$_FILES['avatar1']['error']) {
                    // check the size of the image
                    if ($image_size < 1024000) {
                        //check for type of the image
                        if (in_array($image_type, $accepted_format)) {

                            // first remove the old img and save the new one
                            if (file_exists($_SESSION['this_user_avatar_new'])) {
                                unlink($_SESSION['this_user_avatar_new']);
                            } else {
                                echo "img File does not exists to edit.";
                            }

                            // save the new img
                            move_uploaded_file($image_temp_name, $image_address);
                            
                            
                            



                            //update  user 
                            $query1 = $db->prepare("UPDATE users SET password=?,email=?,avatar=? WHERE username=? ;") or die("Query failed: " . $query1());
                            $query1->execute(array($mypass1, $myemail1, $image_address, $_SESSION['this_user_username_new']));

                            //update image address in post table
                            $query2 = $db->prepare("UPDATE posts SET avatar=? WHERE username=? ;") or die("Query failed: " . $query2());
                            $query2->execute(array($image_address, $image_address));
                            
                            $_SESSION['this_user_avatar_new']=$image_address;

                            //change log in status
                            $_SESSION['this_user_login_new'] = "0";

                            echo "<script>alert('your info updated,login back.');</script>";
                            echo "<script>window.location.href='index.php';</script>";
                        } else {
                            echo "<script>alert('invalid image format.Choose the new one.');</script>";
                            echo "<script>window.location.href='index.php';</script>";
                        }
                    } else {
                        echo "<script>alert('the size of the image is big.Choose the new one.');</script>";
                        echo "<script>window.location.href='index.php';</script>";
                    }
                } else {
                    echo "<script>alert('there is an error for image, choose another one!');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                }
            }
        }else{
            $_SESSION['msg_error1']="fill out fields correctly based on errors!";
            $_SESSION['error_stat1']="1";
            echo "<script>window.location.href='index.php';</script>";

        }
        
     } else {
        $_SESSION['msg_error1']="fill out All fields correctly!";
        $_SESSION['error_stat1']="1";
       echo "<script>window.location.href='index.php';</script>";
     }
    
    
    
    
    
    
    }