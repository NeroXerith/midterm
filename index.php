<?php
include('config.php');
$disp = '';
$fetchSession = "SELECT MAX(studentSession) AS max FROM students";
$result = $conn->query($fetchSession);
$row = $result->fetch_assoc();
$maxSession = $row['max'];
$sql = "SELECT id, studentName, studentSubject, studentSection, studentGrade, studentSession FROM students";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sNumberOfStudents'])) {
        $numberOfStudents = intval($_POST['sNumberOfStudents']); 

        // Check if the number of students is a positive integer
        if ($numberOfStudents > 0) {
            // Get the number of sections from sSection
            $numberOfSections = isset($_POST['sSection']) ? intval($_POST['sSection']) : 1;
            $inputSubject = $_POST['sSubject'];
            // Calculate the number of students per section
            $studentsPerSection = ceil($numberOfStudents / $numberOfSections);

            // Generate input fields for each section
            $inputFields = '';
                for ($section = 1; $section <= $numberOfSections; $section++) {
                    $inputFields .= '<div class="section">';
                    $inputFields .= '<h3>Section ' . $section . '</h3>';

                    // Generate input fields for this section
                    for ($i = 1; $i <= $numberOfStudents; $i++) {
                        if ($i == 1) {
                            $studentNumber = 1;
                        }

                        $inputFields .= '<div class="student-input">';
                        $inputFields .= '<input type="text" name="tests" value="'.$numberOfStudents.'"  hidden>';
                        $inputFields .= '<input type="text" name="studentName[]" value="SEC' . $section . '_STUD' . $studentNumber . '" readonly>';
                        $inputFields .= '<input type="text" name="studentSection[]" value="Section '.$section.'"  hidden>';
                        $inputFields .= '<input type="text" name="studentSubject[]" value="'.$inputSubject.'" readonly>';
                        $inputFields .= '<input type="text" name="studentGrade[]" placeholder="Grade" required>';                        
                        $inputFields .= '</div>';
                        $studentNumber++; 
                }
                $inputFields .= '</div>';
            }
        } else {
            $error = 'Please enter a valid number of students.';
        }
    }
}


// execute query
if ($rs = $conn->query($sql)) {
    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            $disp .= '<tr>';
            $disp .= '<td>' . $row['id'] . '</td>';
            $disp .= '<td>' . $row['studentName'] . '</td>';
            $disp .= '<td>' . $row['studentSection'] . '</td>';
            $disp .= '<td>' . $row['studentSubject'] . '</td>';
            $disp .= '<td>' . $row['studentGrade'] . '</td>';
            $disp .= '<td>' . $row['studentSession'] . '</td>';
            $disp .= '</tr>';
        }
    } else {
        $disp = "No record(s) Found!";
    }
} else {
    echo $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>MIDTERM EXAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>

<body>
    <center>
        <div>
            <form name="generateInputs" action="index.php" method="post">
                <input type="text" name="sSubject" placeholder="Subject" value="" required>
                <input type="text" name="sSection" placeholder="Section" value="" required>
                <input type="number" name="sNumberOfStudents" placeholder="No. of Students" value="" required>
                <input class="btn btn-primary" type="submit" name="btnSubmit" value="GENERATE">
            </form>
        </div>
        <?php if (isset($error)) { ?>
    <p><?php echo $error; ?></p>
<?php } ?>

<?php if (isset($inputFields)) { ?>
    <form name="studentData" action="save.php" method="post">
    <input type="text" name="sSession" value="<?php echo $maxSession+1; ?>" hidden >
        <?php echo $inputFields; ?>
        <input class="btn btn-primary" type="submit" name="btnSubmit" value="Save">
    </form>
<?php } ?>
        <div>
            <form action="filter.php" method="post">
                <a>Session: </a>
                <select name="sessionList">
                    <?php
                    $sql = "SELECT DISTINCT(studentSession) FROM students";
                    if ($rs = $conn->query($sql)) {
                        while ($row = $rs->fetch_assoc()) {
                            echo '<option value="' . $row['studentSession'] . '">' . $row['studentSession'] . '</option>';
                        }
                    } else {
                        echo $conn->error;
                    }
                    ?>
                </select>
                <input class="btn btn-primary" type="submit" name="btnSubmit" value="FILTER">
            </form>
        </div>
        <table class="table table-bordered table-hover" id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Subject</th>

                    <th>Grade</th>
                    <th>Session</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo $disp;
                ?>
            </tbody>
        </table>
    </center>
</body>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>

</html>