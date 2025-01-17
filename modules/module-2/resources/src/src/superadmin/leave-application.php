<?php

include_once '../config.inc';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: superadmin-index.php");
}
if($_SESSION['isadmin'] == 0 || $_SESSION['isadmin'] == 1){
    header("Location: ../logout.php");  
}

$sql = "SELECT * from users_info where id =(SELECT id from users where username = '{$_SESSION['username']}');";
$result = mysqli_prepare($conn, $sql);
$userid = $_SESSION['id'];



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

        header('Location: superadmin-index.php');
        exit;
    } else {
        $_SESSION['errorMsg'] = "Only Phone & Bank details can be blank!";
        header('Location: superadmin-index.php');
        exit;
    }
} 




?>





<!DOCTYPE html>
<html lang="en">

<head>
    <title>AWS GOAT V2 Leaves</title>
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
                    <a class="navbar-brand" href="#"><img src="../images/AWScloud.png" height="40" width="60"> &nbsp; <img src="../images/logo.png" height="25" width="120"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <div class="dropdown notify">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-repeat fa-xl"></i></a>
                                <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdown">
                                    <h6 class="dropdown-header">Organizations</h6>
                                    <?php
                                    $sql = "SELECT * from organizations where organization_id != 0;";
                                    $organizationresult = mysqli_prepare($conn, $sql);

                                    while ($organizationrow = $organizationresult->fetch_assoc()) {
                                        echo "<a class='dropdown-item' href='http://" . $_SERVER['HTTP_HOST'] . "/login.php?organization=" . $organizationrow["organization"] . "'>" . $organizationrow["organization"] . "</a>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>

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
                    <li><a href="./superadmin-index.php" type="button" data-page="homepage" class="navlink"><b></b><b></b><i class="fa fa-home fa-xl"></i><span class="title">Home</span></a></li>
                    <li><a href="./payslips.php" type="button" data-page="payslippage" class="navlink"><b></b><b></b><i class="fa fa-file-invoice-dollar fa-xl"></i><span class="title">Payslips</span></a></li>
                    <li><a href="#" type="button" data-page="leaveapplicationpage" class="navlink active"><b></b><b></b><i class="fa fa-calendar fa-xl"></i></i><span class="title">Leave Applications</span></a></li>
                    <li><a href="reimbursment.php" type="button" data-page="reimbursmentpage" class="navlink"><b></b><b></b><i class="fa fa-money-check-dollar fa-xl"></i><span class="title">Reimbursements</span></a></li>
                    <li><a href="user-settings.php" type="button" data-page="usersettingspage" class="navlink"><b></b><b></b><i class="fa-solid fa-user-gear"></i><span class="title">User Settings</span></a></li>
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
                                        <input type="submit" name="submit" class="btn btn-success" value="Update">
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

    <!-- Leave Applications -->
    <section id="leaveapplicationpage" style="margin-left:10px;">
        <div class="leaveapplicationwrapper">
            <h4 class="titletext">Leave Applications</h4>

            <div class="container leaves" style="margin-left:-10px;">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#appliedleaves">Applied Leaves</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#applyleave">Apply Leave</a></li> -->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="appliedleaves">
                        <div class="row border g-0  shadow-sm">
                            <div class="col p-4">
                                <div class="table-responsive">
                                    <table class="table bg-white">
                                        <thead class="text-light">
                                            <tr id="tableheading">
                                                <th>Name</th>
                                                <th>Leave ID</th>
                                                <th>Leave Type</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Status</th>
                                                <th>Review</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tablebodyrows">
                                            <?php

                                            $queryleaves = "SELECT * from `leave_applications` where id IN (select id from `users` where organization_id = '{$_SESSION['organization_id']}') and isadmin = 1 ORDER BY from_date DESC LIMIT 7;";
                                            $leaveresult = mysqli_prepare($conn, $queryleaves);

                                            if (!$leaveresult) {
                                                die("Invalid Query: ");
                                            }

                                            while ($leaverow = $leaveresult->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>" . $leaverow["first_name"] . "</td>
                                                    <td>" . $leaverow["leave_id"] . "</td>
                                                    <td>" . $leaverow["leave_type"] . "</td>
                                                    <td>" . $leaverow["from_date"] . "</td>
                                                    <td>" . $leaverow["to_date"] . "</td>
                                                    <td>" . $leaverow["status"] . "</td>";
                                                echo "<td>
                                                <form action='leave-approve-super.php' method='post'>
                                                    <input type='radio' name='review' value='Approved'>Approve</br>
                                                    <input type='radio' name='review' value='Rejected'>Reject</br>
                                                    <input type='hidden' name='leave_id' value=" . $leaverow['leave_id'] . ">
                                                    <button class='btn' type='submit' name='save_leave_status'>Review</button>
                                                    <button class='btn' type='delete' name='delete_leave_status'>Delete</button>
                                                </form>
                                                " . "</td> 
                                                </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="tab-pane" id="applyleave">
                        <div class="row border g-0  shadow-sm">
                            <div class="col p-4">

                                <form method="POST" action="#">
                                    <div class="myform">
                                        <div class="form-row align-items-center">
                                            <div class="col-auto my-1">
                                                <label for="leavetype" class="mr-sm-2">Leave Type</label>
                                                <select class="custom-select mr-sm-4" id="leavetype" name="leavetype">
                                                    <option selected>Choose</option>
                                                    <option value="Medical">Medical</option>
                                                    <option value="Emergency">Emergency</option>
                                                    <option value="Paid time off">Paid time off</option>
                                                    <option value="UnPaid time off">UnPaid time off</option>
                                                </select>
                                            </div>
                                            <div class="col-auto my-1">
                                                <label for="fromdate" class="mr-sm-4">From</label>
                                                <input type="date" class="form-control mr-sm-4" name="fromdate" id="fromdate" placeholder="Date">
                                            </div>
                                            <div class="col-auto my-1">
                                                <label for="todate" class="mr-sm-4">To</label>
                                                <input type="date" class="form-control mr-sm-4" name="todate" id="todate" placeholder="Date">
                                            </div>
                                            <div class="col-auto ml-sm-8">
                                                <label for="inputreason" class="mr-sm-8">Reason</label>
                                                <textarea class="form-control ml-sm-10" rows="2" name="inputreason" id="inputreason" placeholder="Reason"></textarea>
                                            </div>

                                        </div>
                                        <div class="form-row align-items-center">
                                            <div class="form-group col-6">
                                                <div class="mr-sm-8">
                                                    <input type="submit" name="apply" value="Apply" class="btn btn-success">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </form>

                            </div>
                        </div>
                    </div> -->
                </div>
            </div>





        </div>
    </section>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/ums/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>
