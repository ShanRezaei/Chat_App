<?php
// to start our session and include all classes
include "head.inc.php";
$DbMngIndex = new DbManager();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap link *****************but in real production it is better to download the file and have it in our machine to reference it***-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Document</title>
</head>

<body>

    <!-- header -->
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarExample01" aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

            </div>
        </nav>
        <!-- Navbar -->

        <!-- Background image -->
        <div class="p-5 text-center bg-image" id="header">
            <div class="mask" id="welcome">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-white">
                        <h1 class="mb-3">Cool Mini-chat Application</h1>
                        <h4 class="mb-3">Connect to the Word and share your comments with your friends.</h4>

                    </div>
                </div>
            </div>
        </div>

    </header>



    <!-- main part with our cards -->

    <div class="container">
        <!---------------- log out action --------------->
        <?php

        if (isset($_SESSION['this_user_logout_new']) && $_SESSION['this_user_logout_new'] == "1") {
            unset($_SESSION['this_user_logout_new']);



            //change the display of the card 1
            $_SESSION['this_user_login_new'] = "0";
            $showinfo = "none";
            $showlogin = "block";
        }


        ?>

        <!-- in log in we will  change the display of the content of card1 -->
        <?php
        if (isset($_SESSION['this_user_login_new']) && $_SESSION['this_user_login_new'] == "1") {
            // unset($_SESSION['this_user_login_new']);
            $showinfo = "block";
            $showlogin = "none";
        }
        ?>
        <!-- in log out change the display and also change the status of the user in database to 0 -->




        <!-- to show error -->
        <?php
        if (isset($_SESSION['error_stat']) && $_SESSION['error_stat'] == "1") {
            unset($_SESSION['error_stat']);
            $showe = "block";
        }
        ?>

        <!-- to show update error -->
        <?php
        if (isset($_SESSION['error_stat1']) && $_SESSION['error_stat1'] == "1") {
            unset($_SESSION['error_stat1']);
            $showe1 = "block";
        }
        ?>

        <!-- card for registration or log in -->
        <div class="card cards card-2 text-center" id="card1">



            <div class="card-body" style="display:<?php echo isset($showlogin) ? $showlogin : "block"; ?>">
                <!-- show there is error -->
                <div class="alert alert-danger" role="alert" style="display:<?php echo isset($showe) ? $showe : "none"; ?>">
                    <?php echo isset($_SESSION['msg_error']) ? $_SESSION['msg_error'] : ""; ?>
                </div>
                <h5 class="card-title">Welcome to our App</h5>
                <p class="card-text">To send post, Log in or Register.</p>
                <br>
                <br>
                <!-- add modal by two tags -->
                <a href="#" class="btn btn-success" id="login" data-bs-toggle="modal" data-bs-target="#addModal1">Log-in</a>
                <!-- add modal by two tags -->
                <a href="#" class="btn btn-primary" id="login" data-bs-toggle="modal" data-bs-target="#addModal">Register</a>
            </div>


            <!-- after log in we can see this part -->
            <div class="card-body" style="display:<?php echo isset($showinfo) ? $showinfo : "none"; ?>">
                <!-- show there is error -->
                <div class="alert alert-danger" role="alert" style="display:<?php echo isset($showe1) ? $showe1 : "none"; ?>">
                    <?php echo isset($_SESSION['msg_error1']) ? $_SESSION['msg_error1'] : ""; ?>
                </div>


                <h5 class="card-title">Welcome <?php echo (isset($_SESSION['this_user_username_new']) && !empty($_SESSION['this_user_username_new'])) ?  ($_SESSION['this_user_username_new']) : "" ?></h5>
                <p class="card-text">Your Information:</p>
                <br>
                <br>
                <img src="<?php echo (isset($_SESSION['this_user_avatar_new']) && !empty($_SESSION['this_user_avatar_new'])) ? $_SESSION['this_user_avatar_new'] : "" ?>" alt="my_img" width="100px" height="95px">
                <p style="font-weight: 600;">Username: <span><?php echo (isset($_SESSION['this_user_username_new']) && !empty($_SESSION['this_user_username_new'])) ? $_SESSION['this_user_username_new'] : "" ?></span> </p>
                <p style="font-weight: 600;">Email: <span><?php echo (isset($_SESSION['this_user_email_new']) && !empty($_SESSION['this_user_email_new'])) ? $_SESSION['this_user_email_new'] : "" ?></span> </p>

                <!-- log out process -->
                <form action="controller/control.php" method="post" style="display:inline-block;width: 24%;">
                    <input type="hidden" name="action" value="logout">

                    <input type="submit" name="logout" value="Log out" class="btn btn-warning" style="font-weight: 600;">
                </form>
                <!--add a tag and assign modal to it for update info-->

                <a href="#" id="update" class="btn btn-primary" style="font-weight: 600; margin-top:1%;width: 24%;display:inline-block" data-bs-toggle="modal" data-bs-target="#addModal2" data-id='<?= $_SESSION['this_user_id_new'] ?>" data-username="<?= $_SESSION['this_user_username_new'] ?>" data-password="<?= $_SESSION['this_user_password_new'] ?>" data-cpassword="<?= $_SESSION['this_user_password_new'] ?>" data-email="<?= $_SESSION['this_user_email_new'] ?>" '>Update Info</a>



            </div>

        </div>


        <div class=" container">
            <!-- card for writing post -->
            <div class="row ">
                <div class="col-6">
                    <div class="card cards card-2 text-center" id="card2">
                        <div class="card-body" style="display:<?php echo isset($showinfo) ? $showinfo : "none"; ?>">


                            <!------------------------------------ writing post part------------------------------- -->

                            <div class="container" style="display:<?php echo isset($show4) ? $show4 : "block"; ?>; text-align:center">
                                <h5 class="card-title">Write your Post</h5>
                                <p class="card-text">With your comment, your friends will know you.</p>
                                <!-- writing post part -->

                                <form method="POST" action="controller/control.php" enctype="multipart/form-data" id="form_post">
                                    <input type="hidden" name="action" value="writePost">

                                    <!-- error text to show -->
                                    <span style="display: block;color:chocolate"></span>
                                    <div class="cleaner_h10"></div>

                                    <textarea id="description" name="description" rows="8" cols="50" style="display: inline-block;"></textarea>


                                    <!-- error text to show -->
                                    <span style="display: block;color:chocolate"></span>
                                    <div class="cleaner_h10"></div>
                                    <input type="submit" name="submit_post" id="submit_post" value="Shout out" class=" btn btn-warning send" />
                                </form>

                                <div class="cleaner"></div>
                            </div> <!-- end of content -->








                        </div>
                        <div class="alert alert-warning" role="alert" style="display:<?php echo isset($showlogin) ? $showlogin : "display"; ?>">
                            Log in to Write your comment!
                        </div>




                    </div>



                </div>
                <!-- card to see users comments -->

                <div class="col-6">
                    <div class="card cards card-2 text-center" id="card2">
                        <div class="card-body">
                            <h5 class="card-title">Our User's Comments</h5>
                            <p class="card-text">They are our honored users with their comments.</p>
                            <!-- --here we need to populate table with posts table's data -->
                            <!-- to add scrolbar to the table add the following style "style="overflow-y:scroll;height:300px;width:450px;"" -->
                            <div class="container" class="divScroll" style="overflow-y:scroll;height:300px;width:450px;">

                                <!-- table -->
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">image</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($DbMngIndex->getPost()  as $comments) : ?>

                                            <tr>
                                                <td><img src="<?= $comments->getAvatar() ?>" alt="my_img" width="50px" height="35px"></td>
                                                <td><?= $comments->getUsername() ?></td>
                                                <td><?= $comments->getComment() ?></td>

                                        <?php endforeach; ?>

                                            </tr>
                                    </tbody>

                                </table>









                            </div>
                        </div>


                    </div>



                </div>



            </div>




        </div>
        <!-- footer -->
        <footer>
            <!-- Copyright -->
            <div class="text-center p-4" id="footer">

                © 2023 Copyright Shan Rezaei:
                <a class="text-reset fw-bold" href="#">Mini_chat.ca</a>
            </div>
        </footer>



        <!-- -------------------------------------The Modal for registration-------------------------------------->
        <div class="modal" id="addModal">
            <div class="modal-dialog">
                <div class="modal-content">



                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add New user</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <!-- Modal body -->
                    <div class="modal-body malign">

                        <!-- //////////////////////////////////do not forget to put enctype="multipart/form-data" to get the image  -->
                        <form action="controller/control.php" method="POST" id="form_register" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="adduser">
                            <div class="form-group mb-3">
                                <!-- <label for="username">Username:</label> -->
                                <input type="text" name="username" class="form-control" placeholder="Enter username" id="username">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myusererror']) ? $_SESSION['myusererror'] : ""; ?></span>
                            </div>



                            <div class="form-group mb-3">
                                <!-- <label for="password" class="form-label">Password:</label> -->
                                <input type="password" name="password" class="form-control" placeholder="Enter Password" id="password">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mypasserror']) ? $_SESSION['mypasserror'] : ""; ?></span>
                            </div>

                            <div class="form-group mb-3">
                                <!-- <label for="cpassword" class="form-label">Confirm(Password):</label> -->
                                <input type="password" name="cpassword" class="form-control" placeholder="Enter Password confirmation" id="cpassword">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mycpasserror']) ? $_SESSION['mycpasserror'] : ""; ?></span>
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mycerror']) ? $_SESSION['mycerror'] : ""; ?></span>
                            </div>



                            <div class="form-group mb-3">
                                <!-- <label for="email" class="form-label">Email:</label> -->
                                <input type="text" name="email" class="form-control" placeholder="Enter Email" id="email">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myemailerror']) ? $_SESSION['myemailerror'] : ""; ?></span>
                            </div>

                            <!-- choose avatar -->
                            <div class="mb-3">
                                <!-- <label for="avatar" class="form-label mystyle">Avatar:</label> -->
                                <input type="file" class="form-control myinput" id="avatar" name="avatar" placeholder="Choose your Avatar" />
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myavatarerror']) ? $_SESSION['myavatarerror'] : ""; ?></span>
                            </div>





                            <div class="form-group mb-3">
                                <input type="submit" name="adduser" value="Add User" class="form-control btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <!------------------------------- The Modal for login------------------------->
        <div class="modal" id="addModal1">
            <div class="modal-dialog">
                <div class="modal-content">



                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Log in</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <!-- Modal body -->
                    <div class="modal-body malign">

                        <form action="controller/control.php" method="post" id="form_login">
                            <!-- hidden input -->
                            <input type="hidden" name="action" value="loginuser">
                            <div class="form-group mb-3">
                                <label for="username1">Username:</label>
                                <input type="text" name="username1" class="form-control" placeholder="Enter username" id="username1">
                                <!-- error text to show -->
                                <span style="color:chocolate"></span>
                            </div>



                            <div class="form-group mb-3">
                                <label for="password1" class="form-label">Password:</label>
                                <input type="password" name="password1" class="form-control" placeholder="Enter Password" id="password1">
                                <!-- error text to show -->
                                <span style="color:chocolate"></span>
                            </div>





                            <div class="form-group mb-3">
                                <input type="submit" name="login" value="Log in" class="form-control btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!------------------------------------------ The Modal for update info-------------------------------->
        <div class="modal" id="addModal2">
            <div class="modal-dialog">
                <div class="modal-content">



                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Update User's Info</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <!-- Modal body -->
                    <div class="modal-body malign">

                        <!-- //////////////////////////////////do not forget to put enctype="multipart/form-data" to get the image  -->

                        <form action="controller/control.php" method="POST" id="form_update" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="updateuser">
                            <div class="form-group mb-3">
                                <!--hidden input-->
                                <input type="hidden" name="id">

                                <label for="username1">Username:</label>
                                <input type="text" name="username1" class="form-control" id="username1" disabled>
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myusererror1']) ? $_SESSION['myusererror1'] : ""; ?></span>
                            </div>



                            <div class="form-group mb-3">
                                <label for="password1" class="form-label">Password:</label>
                                <input type="password" name="password1" class="form-control" id="password1">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mypasserror1']) ? $_SESSION['mypasserror1'] : ""; ?></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="cpassword1" class="form-label">Confirm(Password):</label>
                                <input type="password" name="cpassword1" class="form-control" id="cpassword1">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mycpasserror1']) ? $_SESSION['mycpasserror1'] : ""; ?></span>
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['mycerror1']) ? $_SESSION['mycerror1'] : ""; ?></span>
                            </div>



                            <div class="form-group mb-3">
                                <label for="email1" class="form-label">Email:</label>
                                <input type="text" name="email1" class="form-control" id="email1">
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myemailerror1']) ? $_SESSION['myemailerror1'] : ""; ?></span>
                            </div>

                            <!-- choose avatar -->
                            <div class="mb-3">
                                <label for="avatar1" class="form-label mystyle" style="display:inline-block;">Avatar:</label>
                                <input type="file" class="form-control myinput" id="avatar1" name="avatar1" style="display:inline-block;width:50%;
                                 margin-right: 2%;" /><span><?php echo $_SESSION['this_user_avatar_new'] ?></span>
                                <!-- show js error -->
                                <span></span>
                                <!-- error text to show -->
                                <span style="color:chocolate;font-size:90%;text-align:left"><?php echo isset($_SESSION['myavatarerror1']) ? $_SESSION['myavatarerror1'] : ""; ?></span>
                            </div>





                            <div class="form-group mb-3">
                                <input type="submit" name="updateuser" value="Update Info" class="form-control btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>










        <!-- with this line of code , it can prevent of resubmiting of info inside the form from cashe.
         add it to your code. -->
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
        <!--------------------------------------------- javascript for bootstrap --------------------------------------------------->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <!-- jquery link -->
        <script src="js-folder/jquery.js"></script>
        <!-- my js file -->
        <script type="text/javascript" src="js-folder/app.js"></script>
</body>

</html>