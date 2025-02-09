<?php
session_start();
$showAlert = false;
$showError = false;
include "includes/connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

if (isset($_GET['acid'])) {
    $acid = intval($_GET['acid']);
    $status = 1;
    $sql = "UPDATE subject_comb SET status = $status WHERE comb_id = $acid";
    $result = mysqli_query($conn, $sql);
    $showAlert = true;
}

if (isset($_GET['did'])) {
    $did = intval($_GET['did']);
    $status = 0;
    $sql = "UPDATE subject_comb SET status = $status WHERE comb_id = $did";
    $result = mysqli_query($conn, $sql);
    $showAlert = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subject Combinations</title>
    <link rel="stylesheet" type="text/css" href="css/fp1.css?version=51">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php
    if ($showAlert) {
        echo '<script>alert("Subject Activated/Deactivated successfully!")</script>';
    }
    if ($showError) {
        echo '<script>alert("Error! Try Again.")</script>';
    }
    ?>
    <div class="m2">
        <h1 style="text-align:center;">Manage Subject Combinations</h1>
        <h3 style="margin: 20px; margin-bottom:50px">* View Subject Combinations</h3>
        <table id="tableID" class="display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Branch</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Branch</th>
                    <th>Semester</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $sql = "SELECT branch.branch, semester.semester, subjects.subj_name, subject_comb.comb_id as scid, subject_comb.status FROM subject_comb JOIN branch ON subject_comb.branch_id = branch.branch_id JOIN semester ON subject_comb.sem_id = semester.sem_id JOIN subjects ON subjects.subj_id = subject_comb.subj_id ORDER BY semester";
                $result = mysqli_query($conn, $sql);
                $c = 1;
                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><?php echo $c; ?></td>
                            <td><?php echo $row['branch']; ?></td>
                            <td><?php echo $row['semester']; ?></td>
                            <td><?php echo $row['subj_name']; ?></td>
                            <td><?php echo ($row['status'] == 1) ? "Active" : "Inactive"; ?></td>
                            <td>
                                <a href="edit-subjcomb.php?combid=<?php echo $row['scid']; ?>"><i class="fa fa-edit" title="Edit Record"></i></a>
                            </td>
                        </tr>
                <?php
                        $c++;
                    }
                }
                ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('#tableID').DataTable({});
            });
        </script>
    </div>
</body>

</html>
