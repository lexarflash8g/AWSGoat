<?php

include_once '../config.inc';
session_start();

if ((!isset($_SESSION['username']))) {
    header("Location: ../login.php");
    exit;
}

if($_SESSION['isadmin'] == 1 || $_SESSION['isadmin'] == 2){
    header("Location: ../logout.php");  
}

$sql = "SELECT * from users_info where id =(SELECT id from users where username = '{$_SESSION['username']}');";
$result = mysqli_prepare($conn, $sql);
$userid = $_SESSION['id'];
$resultname = mysqli_fetch_assoc($result);
$firstname = $resultname['first_name'];

if (isset($_POST['submit'])) {
    $fname = $_REQUEST['inputfirstname'];
    $lname = $_REQUEST['inputlastname'];
    $phone = $_REQUEST['inputphone'];
    $email = $_REQUEST['inputEmail'];
    $address = $_REQUEST['inputAddress'];
    $ssn = $_REQUEST['inputssn'];
    $bank = $_REQUEST['inputbank'];
    $npass = $_REQUEST['inputnewPassword'];
    $cpass = $_REQUEST['inputcnfPassword'];
    $uid = $_REQUEST['uid'];

    if ((!empty($fname)) && (!empty($lname)) && (!empty($email)) && (!empty($address)) && (!empty($ssn))) {
        $upq = "UPDATE `users_info` SET `first_name` = '$fname', `last_name` = '$lname' , `phone` = '$phone', `email` = '$email', `address` = '$address', `ssn` = '$ssn', `bank_account` = '$bank' WHERE id = $uid;";
        $upq2 = "UPDATE `users` SET `email` = '$email' where id =$uid; ";
        $upload1 = mysqli_prepare($conn, $upq);
        $upload2 = mysqli_prepare($conn, $upq2);

        if ((!empty($npass)) && (!empty($cpass))) {
            if (($npass == $cpass)) {
                $pass = md5($cpass);
                $upq3 = "UPDATE `users` SET `password` = '$pass' where id =$uid; ";
                $upload3 = mysqli_prepare($conn, $upq3);
                header('Location: ../logout.php');
                exit;
            }
            else{
                echo "<script>alert('Confirm Password is Wrong!')</script>";
            }
        }

        header('Location: index.php');
        exit;
    } else {
        $_SESSION['errorMsg'] = "Only Phone & Bank details can be blank!";
        header('Location:index.php');
        exit;
    }
} else if (isset($_REQUEST['apply'])) {
    $leavetype = $_REQUEST['leavetype'];
    $fromdate = $_REQUEST['fromdate'];
    $todate = $_REQUEST['todate'];
    $inputreason = $_REQUEST['inputreason'];

    if ((!empty($leavetype)) && (!empty($fromdate))) {
        $queryleaveinsert = "INSERT INTO `leave_applications`(`first_name`, `id`,`leave_type`,`from_date`,`to_date`,`reason`) VALUES('$firstname','$userid','$leavetype','$fromdate','$todate','$inputreason')";
        $upload4 = mysqli_prepare($conn, $queryleaveinsert);
    } else {

        header('Location:leave-application.php');
        exit;
    }


    header('Location: leave-application.php');
    exit;
} else if (isset($_REQUEST['request'])) {

    if( $_FILES['file']['name'] != "" ) {
        $currentDirectory = getcwd();
        $uploadDirectory = "../uploads/";
        $fileName = $_FILES['file']['name'];
        $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName);
        move_uploaded_file( $_FILES['file']['tmp_name'],$uploadPath) or die( "Could not copy file!");
    }
    else {
        die("No file specified!");
    }
    $remtype = $_REQUEST['remtype'];
    $filedon = $_REQUEST['filedon'];
    $amount = $_REQUEST['amount'];


    if ((!empty($remtype)) && (!empty($filedon)) && (!empty($amount))) {
        $queryreminsert = "INSERT INTO `reimbursments` (`id`,`first_name`,`type`,`filed_on`,`amount`) VALUES('$userid','$firstname','$remtype','$filedon','$amount')";
        $upload5 = mysqli_prepare($conn, $queryreminsert);
    } else {

        header('Location: reimbursment.php');
        exit;
    }


    header('Location: reimbursment.php');
    exit;
}




?>





<!DOCTYPE html>
<html lang="en">

<head>
    <title>AWS GOAT V2 - Payslips <?$_SESSION['username']?>!</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/AWScloud.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    
    <link rel="stylesheet" href="../CSS/styles.css">
</head>

<body>

    <!-- Navbar & Menus -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-main-head">
            <div class="container-fluid navbar-bundle">
                <div class="nav-flex-container">
                    <a class="navbar-brand" href="index.php"><img src="../images/AWScloud.png" height="40" width="60"> &nbsp; <img src="../images/logo.png" height="25" width="120"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">                      
                        <li class="nav-item">
                            <div class="dropdown profile">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img id="profileimg" src="../images/pic.png" height="30" width="30">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-user"></i> Profile</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#settingsModal"><i class="fa fa-gear"></i> Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../logout.php"><i class="fa fa-arrow-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">
            <div class="navlinks" style="width: 260px;">
                <ul>
                    <li><a href="./index.php" type="button" data-page="homepage" class="navlink"><b></b><b></b><i class="fa fa-home fa-xl"></i><span class="title">Home</span></a></li>
                    <li><a href="#" type="button" data-page="payslippage" class="navlink active"><b></b><b></b><i class="fa fa-file-invoice-dollar fa-xl"></i><span class="title">Payslips</span></a></li>
                    <li><a href="./leave-application.php" type="button" data-page="leaveapplicationpage" class="navlink"><b></b><b></b><i class="fa fa-calendar fa-xl"></i></i><span class="title">Leave Applications</span></a></li>
                    <li><a href="./reimbursment.php" type="button" data-page="reimbursmentpage" class="navlink"><b></b><b></b><i class="fa fa-money-check-dollar fa-xl"></i><span class="title">Reimbursements</span></a></li>
                    <li><a href="./complaints.php" type="button" data-page="complaintspage" class="navlink"><b></b><b></b><i class="fa fa-ticket-simple fa-xl"></i><span class="title">Complaints</span></a></li>
                </ul>
            </div>
        </nav>

        <footer>
            <div class="waves">
                <div class="wave" id="wave1"></div>
                <div class="wave" id="wave2"></div>
                <div class="wave" id="wave3"></div>
                <div class="wave" id="wave4"></div>
            </div>
        </footer>

    </header>


    <div class="profilewrapper">
        <?php
        $sql = "SELECT * from users_info where id =(SELECT id from users where username = '{$_SESSION['username']}');";
        $result = mysqli_prepare($conn, $sql);
        ?>
        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="profile_info_text">Profile</h1>
                    </div>
                    <div class="modal-body">
                        <?php if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result); ?>
                            <div class="card">

                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-6">Name</dt>
                                        <dd class="col-6"><?php echo $row['first_name'] . " " . $row['last_name']; ?></dd>
                                        <dt class="col-6">Email</dt>
                                        <dd class="col-6"><?php echo $row['email']; ?></dd>
                                        <dt class="col-6">Address</dt>
                                        <dd class="col-6"><?php echo $row['address']; ?></dd>
                                        <dt class="col-6">Social Security Number</dt>
                                        <dd class="col-6"><?php echo $row['ssn']; ?></dd>
                                        <dt class="col-6">Phone</dt>
                                        <dd class="col-6"><?php echo $row['phone']; ?></dd>
                                        <dt class="col-6">Bank Account Number</dt>
                                        <dd class="col-6"><?php echo $row['bank_account']; ?></dd>
                                    </dl>
                                </div>
                            </div>
                        <?php } else { ?>
                            User not found.
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn " data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="settingswrapper">
        <?php
        $sql = "SELECT * from users_info where id =(SELECT id from users where username = '{$_SESSION['username']}');";
        $result = mysqli_prepare($conn, $sql);

        ?>
        <div class="modal fade" id="settingsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="profile_info_text">Settings</h1>
                    </div>
                    <div class="modal-body">

                        <?php if ($result->num_rows > 0) {
                            $row = mysqli_fetch_assoc($result); ?>
                            <form method="POST" action="#">
                                <input type='hidden' name='uid' value="<?php echo $_SESSION['id'] ?>">
                                <div class="form-group row">
                                    <label for="inputfirstname" class="col-sm-4 col-form-label">First Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $row['first_name'] ?>" id="inputfirstname" name="inputfirstname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputlastname" class="col-sm-4 col-form-label">Last Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $row['last_name'] ?>" id="inputlastname" name="inputlastname" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputphone" class="col-sm-4 col-form-label">Phone</label>
                                    <div class="col-sm-8">
                                        <input type="tel" class="form-control" value="<?php echo $row['phone'] ?>" id="inputphone" name="inputphone" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" value="<?php echo $row['email'] ?>" id="inputEmail" name="inputEmail" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputAddress" class="col-sm-4 col-form-label">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $row['address'] ?>" id="inputAddress" name="inputAddress" placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputssn" class="col-sm-4 col-form-label">SSN</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $row['ssn'] ?>" id="inputssn" name="inputssn" placeholder="SSN">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputbank" class="col-sm-4 col-form-label">Account Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $row['bank_account'] ?>" id="inputbank" name="inputbank" placeholder="SSN">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputnewPassword" class="col-sm-4 col-form-label">New Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="inputnewPassword" name="inputnewPassword" placeholder="New Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputcnfPassword" class="col-sm-4 col-form-label">Confirm New Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="inputcnfPassword" name="inputcnfPassword" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <div class="col-sm-12 ">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </div>
                            </form>
                        <?php } else { ?>
                            User not found.
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn " data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pay Slips -->
    <section id="payslippage" style="margin-left:10px;">
        <div class="payslipswrapper" id="payslips">
            <h4 class="titletext"> Your Pay slips</h4>
            <div class="container" style="margin-left:-10px;">
                <div class="card row " id="payslipcolumns">

                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-2">Date</dt>
                            <dt class="col-4">Payslip ID</dt>
                            <dt class="col-6">File</dt>
                        </dl>
                    </div>

                </div>
                <?php
                    $queryrem = "select * from `payslips` where id = (SELECT id from users where username = '{$_SESSION['username']}') ORDER BY date DESC LIMIT 4;";
                    $remresult = mysqli_prepare($conn, $queryrem);

                    if (!$remresult) {
                        die("Invalid Query: ");
                    }
                    
                    while ($remrow = $remresult->fetch_assoc()) {
                        $date1=date_create($remrow["date"]);
                        echo "<div class='card row '>

                            <div class='card-body'>
                                <dl class='row'>
                                    <dt class='col-2'>" . date_format($date1,"Y F") . "</dt>
                                    <dd class='col-4'>" . $remrow['payslip_id'] . "</dd>
                                    <dd class='col-6'><a href=" . $remrow["file"] . " target='_blank'>
                                    <button class='btn btn-primary' type='button'>View File</button></a></dd>
                                </dl>
                            </div> 
                            
                        </div> <br>";
                        
                    }
                ?>
            </div>

        </div>

    </section>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/ums/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>
