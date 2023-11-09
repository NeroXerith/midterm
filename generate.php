<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numberOfStudents = isset($_POST['sNumberOfStudents']) ? intval($_POST['sNumberOfStudents']) : 0;

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
                    $inputFields .= '<input type="text" name="studentSection" value="Section '.$section.'" disabled hidden>';
                    $inputFields .= '<input type="text" name="sessionCount" value="'.$section.'" disabled hidden>';
                    $inputFields .= '<input type="text" name="studentName" value="SEC' . $section . '_STUD' . $studentNumber . '" disabled required>';
                    $inputFields .= '<input type="text" name="studentSubject" value="'.$inputSubject.'" disabled required>';
                    $inputFields .= '<input type="text" name="studentGrade" placeholder="Grade" required>';
                    $inputFields .= '</div>';
                    $studentNumber++; 
            }
            $inputFields .= '</div>';
        }
    } else {
        $error = 'Please enter a valid number of students.';
    }

    if (isset($error)) {
        echo $error;
    } elseif (isset($inputFields)) {
        echo '<form name="studentData" action="save.php" method="post">';
        echo $inputFields;
        echo '<input class="btn btn-primary" type="submit" name="btnSubmit" value="Save">';
        echo '</form>';
    }
}
?>