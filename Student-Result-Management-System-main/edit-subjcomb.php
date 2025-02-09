<?php
session_start();
$showAlert = false;
$showError = false;
$subjcombDeleted = false; // Flag to indicate if subject combo has been deleted
include "includes/connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

$combid = intval($_GET['combid']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = $_POST['branch'];
    $sem_id = $_POST['semester'];
    $subj_id = $_POST['subject'];
    $stat = $_POST['status'];

    if (isset($_POST['update'])) {
        $addsql = "UPDATE `subject_comb` SET branch_id = '$branch_id', sem_id = '$sem_id', subj_id = '$subj_id', status = '$stat' WHERE comb_id = '$combid'";
        $result = mysqli_query($conn, $addsql);

        if ($result) {
            $showAlert = true;
        } else {
            $showError = true;
        }
    } elseif (isset($_POST['delete'])) {
        $deletesql = "DELETE FROM `subject_comb` WHERE comb_id = '$combid'";
        $result = mysqli_query($conn, $deletesql);

        if ($result) {
            $subjcombDeleted = true;
        } else {
            $showError = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/form.css"> -->

    <title>Edit Subject Combination</title>
</head>

<body style="background-color: alicewhite; margin: 0">
    <?php include "nav.php"; ?>
    <?php
    if ($showAlert) {
        echo '<script>alert("Operation Successful!")</script>';
    }
    if ($showError) {
        echo '<script>alert("Error! Try Again.")</script>';
    }
    ?>
    <div style="width: 67%; margin: auto auto; height: 500px; border: 2px solid rgb(200, 200, 200); margin-top: 80px;background-color: rgb(236, 236, 236)">
        <form method="post">
            <h2 style="text-align:center; font-size: 30px">Edit Subject Combination</h2>
            <div style="width: 75%; margin: 60px auto; font-size: 20px">
                <?php
                $sql = "SELECT subject_comb.comb_id, subject_comb.branch_id, subject_comb.sem_id, subject_comb.subj_id, subject_comb.status, branch.branch, semester.semester, subjects.subj_name FROM subject_comb 
                        JOIN branch ON subject_comb.branch_id = branch.branch_id 
                        JOIN semester ON subject_comb.sem_id = semester.sem_id 
                        JOIN subjects ON subject_comb.subj_id = subjects.subj_id 
                        WHERE comb_id = '$combid'";
                $result = mysqli_query($conn, $sql);

                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div style="margin-left : 50px; margin-bottom:50px">
                            <label for="branch">Branch &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; </label>
                            <select name="branch" id="branch" style="padding: 5px; background-color: alicewhite; width:80%; font-size:17px">
                                <?php
                                $branchSql = "SELECT * FROM `branch`";
                                $branchResult = mysqli_query($conn, $branchSql);

                                while ($branchRow = mysqli_fetch_assoc($branchResult)) {
                                    $selected = ($branchRow['branch_id'] == $row['branch_id']) ? 'selected' : '';
                                    echo '<option value="' . $branchRow['branch_id'] . '" style="font-size:17px" ' . $selected . '>' . $branchRow['branch'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-left : 50px;margin-bottom:50px">
                            <label for="semester">Semester &nbsp;&nbsp; :&nbsp;&nbsp; </label>
                            <select name="semester" id="semester" style="padding: 5px; background-color: alicewhite; width:80%; font-size:17px">
                                <?php
                                $semesterSql = "SELECT * FROM `semester`";
                                $semesterResult = mysqli_query($conn, $semesterSql);

                                while ($semesterRow = mysqli_fetch_assoc($semesterResult)) {
                                    $selected = ($semesterRow['sem_id'] == $row['sem_id']) ? 'selected' : '';
                                    echo '<option value="' . $semesterRow['sem_id'] . '" style="font-size:17px" ' . $selected . '>' . $semesterRow['semester'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-left : 50px; margin-bottom:50px">
                            <label for="subject">Subject &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; </label>
                            <select name="subject" id="subject" style="padding: 5px; background-color: alicewhite; width:80%; font-size:17px">
                                <?php
                                $subjectSql = "SELECT * FROM `subjects`";
                                $subjectResult = mysqli_query($conn, $subjectSql);

                                while ($subjectRow = mysqli_fetch_assoc($subjectResult)) {
                                    $selected = ($subjectRow['subj_id'] == $row['subj_id']) ? 'selected' : '';
                                    echo '<option value="' . $subjectRow['subj_id'] . '" style="font-size:17px" ' . $selected . '>' . $subjectRow['subj_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-left : 50px; margin-bottom:50px">
                            Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;
                            <?php
                            $s = $row['status'];
                            if ($s == 1) {
                            ?>
                                <label><input type="radio" name="status" value="1" required="required" checked /> Active</label>
                                <label><input type="radio" name="status" value="0" required /> Block</label>
                            <?php
                            }
                            ?>
                            <?php
                            if ($s == 0) {
                            ?>
                                <label><input type="radio" name="status" value="1" required="required" /> Active</label>
                                <label><input type="radio" name="status" value="0" required="required" checked /> Block</label>
                            <?php
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <div style="float: right; margin-right: 80px">
                <button type="submit" name="update" style="width: 80px; padding: 7px;font-size: 17px; background-color: rgba(42, 42, 120, 0.909);color:white;">Update</button>
                <button type="submit" name="delete" style="width: 80px; padding: 7px;font-size: 17px; background-color: red;color:white;">Delete</button>
            </div>
        </form>
        <?php
        if ($subjcombDeleted) {
            echo '<script>
                    var confirmation = confirm("Subject Combination Deleted Successfully!");
                    if (confirmation) {
                        window.location.href = "manage-subjcomb.php";
                    }
                  </script>';
        }
        ?>
    </div>
</body>

</html>
