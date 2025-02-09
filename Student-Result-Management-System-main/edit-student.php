<?php
session_start();
$showAlert = false;
$showError = false;
include "includes/connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

$stid = intval($_GET['stid']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $rollno = $_POST['rollno'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['birthDate'];
    $status = $_POST['status'];

    // Update record
    $addsql = "UPDATE `student` SET student.Name = '$fullname', student.Roll_No = '$rollno', student.Email = '$email', student.Gender='$gender', student.DOB = '$dob', student.status='$status' WHERE student.reg_id = '$stid'";
    $result1 = mysqli_query($conn, $addsql);

    if ($result1) {
        $showAlert = true;
    } else {
        $showError = true;
    }

    // Check if the delete button is clicked
    if (isset($_POST['delete'])) {
        $deletesql = "DELETE FROM `student` WHERE reg_id = '$stid'";
        $deleteResult = mysqli_query($conn, $deletesql);

        if ($deleteResult) {
            header("location: manage-students.php"); // Redirect to manage-students.php after deletion
            exit;
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
    <title>Edit Student's Info</title>
</head>

<body style="margin: 0">
    <?php include "nav.php"; ?>
    <?php
    if ($showAlert) {
        echo '<script>alert("Record Updated Successfully!")</script>';
    }
    if ($showError) {
        echo '<script>alert("Error! Try Again.")</script>';
    }
    ?>
    <div style="width: 70%; margin: auto auto; height: 900px; border: 2px solid rgb(200, 200, 200); margin-top: 15px;background-color: rgb(236, 236, 236)">
        <h2 style="text-align:center; font-size: 30px">Student Admission Details</h2>

        <form method="post">
            <?php
            $sql = "SELECT student.Name, student.Roll_No, student.reg_id, student.status, student.Email, student.Gender, student.Reg_date, student.DOB, branch.branch, semester.semester FROM student JOIN branch ON student.branch_id = branch.branch_id JOIN semester ON student.sem_id = semester.sem_id WHERE student.reg_id = $stid";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);

            if ($num > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div style="width: 75%; margin:auto auto; font-size: 20px">
                        <p>
                            <label for="fullname">Full name &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
                                <input name="fullname" value="<?php echo $row['Name']; ?>" style="width: 50%;padding: 5px;font-size:17px" />
                            </label>
                        </p>
                        <p style="margin-top : 50px">
                            <label for="rollno">Roll No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;
                                <input name="rollno" value="<?php echo $row['Roll_No']; ?>" style="width : 50%;padding : 5px;font-size:17px" />
                            </label>
                        </p>
                        <p style="margin-top : 50px">
                            <label for="email">Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;
                                <input type="email" name="email" value="<?php echo $row['Email']; ?>" style="width : 50%;padding : 5px;font-size:17px" />
                            </label>
                        </p>
                        <p style="margin-top : 50px">
                            Gender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;
                            <label><input type="radio" name="gender" value="Male" <?php echo ($row['Gender'] == 'Male') ? 'checked' : ''; ?> /> Male</label>
                            <label><input type="radio" name="gender" value="Female" <?php echo ($row['Gender'] == 'Female') ? 'checked' : ''; ?> /> Female</label>
                            <label><input type="radio" name="gender" value="Other" <?php echo ($row['Gender'] == 'Other') ? 'checked' : ''; ?> /> Other</label>
                        </p>
                        <p style="margin-top : 50px">
                            <label for="birthDate">DOB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;
                                <input type="date" name="birthDate" value="<?php echo $row['DOB']; ?>" style="padding : 5px;font-size:17px; width : 180px" />
                            </label>
                        </p>
                        <div style="margin-top : 50px;margin-bottom: 20px; display: flex; align-items: center;">
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
                        <div style="margin-top : 50px; margin-bottom: 20px; display: flex; align-items: center;">
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
                        <p style="margin-top : 50px">
                            Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;
                            <label><input type="radio" name="status" value="1" required="required" <?php echo ($row['status'] == 1) ? 'checked' : ''; ?> /> Active</label>
                            <label><input type="radio" name="status" value="0" required <?php echo ($row['status'] == 0) ? 'checked' : ''; ?> /> Block</label>
                        </p>
                        <div style="margin-top : 50px;float:right; margin-right: 80px">
                            <button type="submit" name="update" style="width: 90px; padding: 7px; font-size: 17px; background-color: rgba(42, 42, 120, 0.909); color:white;">Update</button>
                            <button type="submit" name="delete" style="width: 90px; padding: 7px; margin-left: 10px; font-size: 17px; background-color: rgba(255, 0, 0, 0.909); color:white;" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </form>
    </div>
</body>

</html>
