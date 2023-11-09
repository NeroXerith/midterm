  <?php
  include('config.php');

  if (isset($_POST['studentGrade'])) {
    $totalStudents = count($_POST['studentName']);

    for ($i = 0; $i < $totalStudents; $i++) {
        $studentName = $_POST['studentName'][$i];
        $studentSection = $_POST['studentSection'][$i];
        $studentSubject = $_POST['studentSubject'][$i];
        $studentGrade = $_POST['studentGrade'][$i];
        $sessionCount = ($_POST['sSession'] === "0" || $_POST['sSession'] === null) ? 1 : $_POST['sSession'];
    $sql="INSERT INTO students SET studentName='$studentName', studentSubject='$studentSubject', studentSection='$studentSection', studentGrade='$studentGrade', studentSession='$sessionCount'";
    if($conn->query($sql)){
      header("location:index.php");// redirect in php
    }
    else{
      echo $conn->error;
    }
  }
  }
  ?>
