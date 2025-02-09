<?php
session_start();
$showAlert = false;
$showError = false;
$subjectDeleted = false; // Flag to indicate if subject has been deleted
include "includes/connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

$subid = intval($_GET['subid']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subj_name = $_POST['subj_name'];
    $subj_code = $_POST['subj_code'];
    $stat = $_POST['status'];

    if (isset($_POST['update'])) {
        $addsql = "UPDATE `subjects` SET subj_name = '$subj_name', subj_code = '$subj_code', status = '$stat' WHERE subj_id = '$subid'";
        $result = mysqli_query($conn, $addsql);

        if ($result) {
            $showAlert = true;
        } else {
            $showError = true;
        }
    } elseif (isset($_POST['delete'])) {
        $deletesql = "DELETE FROM `subjects` WHERE subj_id = '$subid'";
        $result = mysqli_query($conn, $deletesql);

        if ($result) {
            $subjectDeleted = true;
        } else {
            $showError = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... (unchanged) ... -->
    <title>Edit Subject</title>
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
            <h2 style="text-align:center; font-size: 30px">Edit Subject</h2>
            <div style="width: 75%; margin: 60px auto; font-size: 20px">
                <?php
                $sql = "SELECT subjects.subj_id,subjects.subj_name, subjects.subj_code, subjects.status FROM subjects WHERE subjects.subj_id = $subid";
                $result = mysqli_query($conn, $sql);

                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <p>
                            <label for="subj_name">Subject Name &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
                                <input name="subj_name" value="<?php echo $row['subj_name']; ?>" style="width: 50%;padding: 5px;font-size:17px" />
                            </label>
                        </p>
                        <p>
                            <label for="subj_code">Subject Code &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
                                <input name="subj_code" value="<?php echo $row['subj_code']; ?>" style="width: 50%;padding: 5px;font-size:17px" />
                            </label>
                        </p>
                        <p>
                            Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;
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
                        </p>
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
        if ($subjectDeleted) {
            echo '<script>
                    var confirmation = confirm("Subject Deleted Successfully!");
                    if (confirmation) {
                        window.location.href = "manage-subjects.php";
                    }
                  </script>';
        }
        ?>
    </div>
</body>

</html>
