<?php 
 
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Retrieve session data 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
// Get status message from session 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
} 
 
// Get member data 
$studentData = $userData = array(); 
if(!empty($_GET['id'])){ 
    // Include database configuration file 
    require_once 'dbConfig.php'; 
     
    // Fetch data from SQL server by row ID 
    $sql = "SELECT * FROM Students WHERE StudentID = ".$_GET['id']; 
    $query = $conn->prepare($sql); 
    $query->execute(); 
    $studentData = $query->fetch(PDO::FETCH_ASSOC); 
} 
$userData = !empty($sessData['userData'])?$sessData['userData']:$studentData; 
unset($_SESSION['sessData']['userData']); 
 
$actionLabel = !empty($_GET['id'])?'Edit':'Add'; 
 
?>

<!-- Display status message -->
<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
<div class="col-xs-12">
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
</div>
<?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
<div class="col-xs-12">
    <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>
<div class="row">
    <div class="col-md-12">
        <h2><?php echo $actionLabel; ?> Student Information</h2>
    </div>
    <div class="col-md-6">
         <form method="post" action="userAction.php">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="FirstName" placeholder="Enter your first name" value="<?php echo !empty($userData['FirstName'])?$userData['FirstName']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="LastName" placeholder="Enter your last name" value="<?php echo !empty($userData['LastName'])?$userData['LastName']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="Email" placeholder="Enter your email" value="<?php echo !empty($userData['Email'])?$userData['Email']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>Section</label>
                <input type="text" class="form-control" name="Section" placeholder="Enter section name" value="<?php echo !empty($userData['Section'])?$userData['Section']:''; ?>" required="">
            </div>
            
            <a href="index.php" class="btn btn-secondary">Back</a>
            <input type="hidden" name="StudentID" value="<?php echo !empty($userData['StudentID'])?$userData['StudentID']:''; ?>">
            <input type="submit" name="userSubmit" class="btn btn-success" value="Submit">
        </form>
    </div>
</div>
</body>
</html>
