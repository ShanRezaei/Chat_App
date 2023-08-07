<?php
include "../head.inc.php";

//to see what is exactly  HTTPS 500 error add following 2 lines
ini_set("display_errors","1");
error_reporting(E_ALL);

// define our variables for registration
$info = "";
$usernameErr = $passErr = $repeatErr = $emailerr = $cpassErr = $avatarErr = "";
$myusername = $myass = $myemail = $img = "";
$isvalidate = true;
$_SESSION['myusererror'] = $_SESSION['mypasserror'] = $_SESSION['mycpasserror'] = $_SESSION['myemailerror'] = $_SESSION['myavatarerror'] = $_SESSION['mycerror'] = $_SESSION['msg_error'] = "";
$_SESSION['error_stat'] = "0";

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



// create object of dbmanager to access functions

$DbMgr = new DbManager();
$action = $_REQUEST['action'];


switch ($action) {
    case 'adduser':
        // do validation
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['cpassword']) && !empty($_POST['email']) && !empty($_FILES['avatar'])) {
            // validate the length of username
            if (!empty($_POST["username"])) {
                if (strlen(trim($_POST["username"])) >= 5) {
                    $isvalidate = true;
                } else {
                    $usernameErr = 'your user name could not be less than 5 character';

                    $_SESSION['myusererror'] = $usernameErr;
                    $isvalidate = false;
                }
            } else {
                $usernameErr = 'your username is empty.';

                $_SESSION['myusererror'] = $usernameErr;
                $isvalidate = false;
            }



            //validate password
            if (!empty($_POST["password"])) {
                // password strength validation

                $password = trim($_POST["password"]);

                // Validate password strength
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number    = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {

                    $passErr = 'invalid Password .';
                    $_SESSION['mypasserror'] = $passErr;


                    $isvalidate = false;
                }
            } else {
                $passErr = 'enter Password .';
                $_SESSION['mypasserror'] = $passErr;


                $isvalidate = false;
            }


            //cpassword validation
            if (!empty($_POST["cpassword"])) {
                $cpassword = trim($_POST["cpassword"]);

                // Validate cpassword strength
                $uppercase = preg_match('@[A-Z]@', $cpassword);
                $lowercase = preg_match('@[a-z]@', $cpassword);
                $number    = preg_match('@[0-9]@', $cpassword);
                $specialChars = preg_match('@[^\w]@', $cpassword);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($cpassword) < 6) {

                    $cpassErr = 'Invalid confirm password.';

                    $_SESSION['mycpasserror'] = $cpassErr;
                    $isvalidate = false;
                }
            } else {
                $cpassErr = 'enter Password confirmation .';
                $_SESSION['mycpasserror'] = $cpassErr;


                $isvalidate = false;
            }


            //email validation

            if (!empty($_POST["email"])) {
                //check the format of the email
                $email = trim($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailerr = 'enter valid email address';
                    $_SESSION['myemailerror'] = $emailerr;

                    $isvalidate = false;
                }
            } else {
                $emailerr = 'enter your email address';
                $_SESSION['myemailerror'] = $emailerr;

                $isvalidate = false;
            }

            //   check if confirm pass and pass are the same
            if ($_POST['password'] != $_POST['cpassword']) {
                $repeatErr = 'your password should be the same as your confirm.';

                $isvalidate = false;

                $_SESSION['mycerror'] = $repeatErr;
            }

            //validate avatar
            if (empty($_FILES["avatar"])) {
                $isvalidate = false;
                $avatarErr = "choose your avatar.";
                $_SESSION['myavatarerror'] =  $avatarErr;
            }

            // on submit if there is no error
            if ($isvalidate === true && $repeatErr == "" && $emailerr == "" && $cpassErr == "" && $passErr == "" && $usernameErr == "" &&  $avatarErr == "") {

                //create a file based on each username for users
                $myusername = trim($_POST["username"]);
                $mypass = trim($_POST["password"]);
                $myemail = trim($_POST["email"]);

                // //make the path of the avatar and check other character of image to be correct
                $image_name = $_FILES['avatar']['name'];
                $image_type = $_FILES['avatar']['type'];
                $image_size = $_FILES['avatar']['size'];
                $image_temp_name = $_FILES['avatar']['tmp_name'];
                $array_name = explode(".", $image_name);
                $image_format = end($array_name); //get last index of array
                //make an address for saving avatar
                $image_address = "user_image/" . $myusername . "." . $image_format;

                //check for general errors
                if (!$_FILES['avatar']['error']) {
                    // check the size of the image
                    if ($image_size < 1024000) {
                        //check for type of the image
                        if (in_array($image_type, $accepted_format)) {

                            /////////////////check for unique username////////////////
                            // here we used the object of dbmanager to get the function
                            $rowcount = $DbMgr->getUserByUsername($myusername);

                            if (isset($rowcount)) {
                                echo "<script>alert('this user name already registered.Try with different username!');</script>";
                                echo "<script>window.location.href='../index.php';</script>";
                            } else {
                                // check for email uniqueness
                                $emailrowcount = $DbMgr->getUserByEmail($myemail);
                                if ($emailrowcount > 0) {
                                    echo "<script>alert('this email already registered.Try with different email!');</script>";
                                    echo "<script>window.location.href='../index.php';</script>";
                                } else {
                                    //uploade img in the folder in project root
                                    move_uploaded_file($image_temp_name, $image_address);

                                    // write inside the database
                                    //  hash the password
                                   $hashedpassword = password_hash(($mypass), PASSWORD_DEFAULT);

                                    //create user object
                                    $user = new Users($id, $myusername, $hashedpassword, $myemail, $image_address, $stat);

                                    $adduser = $DbMgr->addUser($user);


                                    if (isset($adduser)) {

                                        $actual_link = "https://nkhodapanah.herzingmontreal.ca/lab4-m2/" . "activate.php?username=" .($myusername) . "&email=" . ($myemail);
                                        $header = 'From:nkhodapanah@nkhodapanah.herzingmontreal.ca' . "\r\n";
                                        $email = $myemail;
                                        $subject = "User Registration Activation Email";
                                        $content = "Click this link to activate your account. <a href='" . $actual_link . "'>" . $actual_link . "</a>";
                                        $sendemail = $DbMgr->sendEmail($email, $subject, $content, $header);
                                        if ($sendemail == 1) {
                                            echo "<script>alert('You Registered successfully,check your email and activate your account.');</script>";
                                            echo "<script>window.location.href='../index.php';</script>";
                                        } else {
                                            echo "<script>alert('something went wrong.try again.');</script>";
                                            echo "<script>window.location.href='../index.php';</script>";
                                        }
                                    } else {
                                        echo "<script>alert('problem in registration.try again.');</script>";
                                        echo "<script>window.location.href='../index.php';</script>";
                                    }
                                }
                            }
                        } else {
                            echo "<script>alert('invalid image format.Choose the new one.');</script>";
                            echo "<script>window.location.href='../index.php';</script>";
                        }
                    } else {
                        echo "<script>alert('the size of the image is big.Choose the new one.');</script>";
                        echo "<script>window.location.href='../index.php';</script>";
                    }
                } else {
                    echo "<script>alert('there is an error for image, choose another one!');</script>";
                    echo "<script>window.location.href='../index.php';</script>";
                }
            } else {
                $_SESSION['msg_error'] = "fill out fields correctly based on errors!";
                $_SESSION['error_stat'] = "1";
                echo "<script>window.location.href='../index.php';</script>";
            }
        } else {
            $_SESSION['msg_error'] = "fill out All fields correctly!";
            $_SESSION['error_stat'] = "1";
            echo "<script>window.location.href='../index.php';</script>";
        }


        break;
    case 'updateuser':
        // update user info






        break;
    case 'loginuser':
        $_SESSION['this_user_username_new'] = "";
        $_SESSION['this_user_email_new'] = "";
        $_SESSION['this_user_avatar_new'] = "";
        $_SESSION['this_user_login_new'] = "0";
        if (!empty($_POST['username1']) && !empty($_POST['password1'])) {

            //do the query in db
            $resultsearch = $DbMgr->getUserByUsername(trim($_POST['username1']));
           
            if (isset($resultsearch)) {
                var_dump($resultsearch);
               
                // $resultsearch=($DbMgr->getUserByUsername(trim($_POST['username1'])));
                //get the info of user
                //foreach ($DbMgr->getUserByUsername(trim($_POST['username1'])) as $value){}

                //  $myuser=new Users();

                   $_SESSION['this_user_id_new'] = $resultsearch->getId() ;
                   $_SESSION['this_user_username_new'] = $resultsearch->getUsername();
                   $_SESSION['this_user_password_new'] = $resultsearch->getPassword();
                    $status = $resultsearch->getStat();
                   $_SESSION['this_user_email_new'] = $resultsearch->getEmail();
                     $_SESSION['this_user_avatar_new'] = $resultsearch->getAvatar();
                 
                 $verifiedpass = password_verify(trim($_POST['password1']),  $_SESSION['this_user_password_new']);
                // var_dump($verifiedpass );
                if (  $verifiedpass) {
                    if ($status == 1) {
                        //change log in status
                        $_SESSION['this_user_login_new'] = "1";
                        echo "<script>alert('Welcome to our Mini-cool chat App');</script>";
                         echo "<script>window.location.href='../index.php';</script>";
                    } else {
                        $_SESSION['this_user_login_new'] = "0";
                        echo "<script>alert('Please check your Email and activate your account');</script>";
                         echo "<script>window.location.href='../index.php';</script>";
                    }
                } else {
                    $_SESSION['msg_error'] = " your password  is wrong,try again!";
                    $_SESSION['error_stat'] = "1";
                    echo "<script>window.location.href='../index.php';</script>";
                }
            } else {
                $_SESSION['msg_error'] = " your username  is wrong,try again!";
                $_SESSION['error_stat'] = "1";

                echo "<script>window.location.href='../index.php';</script>";
            }
        } else {
            $_SESSION['msg_error'] = "Enter your username and password!";
            $_SESSION['error_stat'] = "1";
            echo "<script>window.location.href='../index.php';</script>";
        }


        break;
    case 'logout':
       
        //change the display of the card 1
        $_SESSION['this_user_logout_new'] = "1";
        echo "<script>window.location.href='../index.php';</script>";

        break;

    case 'writePost':
        if (!empty($_POST['description']) && strlen(($_POST["description"])) >= 4){
             //write username,avatar,post inside the posts DB
        // write inside the database
        
        $post = new Posts($id1, $_SESSION['this_user_username_new'], $_SESSION['this_user_avatar_new'],$_POST['description']);
        $postresult=$DbMgr->sendPost($post);

        if(isset($postresult)){
            $_SESSION['this_user_login_new']="1";

        // echo "<script>alert('your comment sent successfully.');</script>";
       echo "<script>window.location.href='../index.php';</script>";



        }else{
            echo "<script>alert('something went wrong ,try again!');</script>";
        echo "<script>window.location.href='../index.php';</script>";

        }



        }else{
        echo "<script>alert('Write your comment correctly!');</script>";
        echo "<script>window.location.href='../index.php';</script>";
    }





        break;
}
?>
