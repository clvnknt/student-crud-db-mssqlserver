<?php 
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Include database configuration file 
require_once 'dbConfig.php'; 
 
// Set default redirect url 
$redirectURL = 'index.php'; 
 
if(isset($_POST['userSubmit'])){ 
    // Get form fields value 
    $StudentID = $_POST['StudentID']; 
    $FirstName = trim(strip_tags($_POST['FirstName'])); 
    $LastName = trim(strip_tags($_POST['LastName'])); 
    $Email = trim(strip_tags($_POST['Email'])); 
    $Section = trim(strip_tags($_POST['Section'])); 
     
    $id_str = ''; 
    if(!empty($id)){ 
        $id_str = '?id='.$StudentID; 
    } 
     
    // Fields validation 
    $errorMsg = ''; 
    if(empty($FirstName)){ 
        $errorMsg .= '<p>Please enter your first name.</p>'; 
    } 
    if(empty($LastName)){ 
        $errorMsg .= '<p>Please enter your last name.</p>'; 
    } 
    if(empty($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)){ 
        $errorMsg .= '<p>Please enter a valid email.</p>'; 
    } 
    if(empty($Section)){ 
        $errorMsg .= '<p>Please enter section name.</p>'; 
    } 
     
    // Submitted form data 
    $userData = array( 
        'FirstName' => $FirstName, 
        'LastName' => $LastName, 
        'Email' => $Email, 
        'Section' => $Section 
    ); 
     
    // Store the submitted field values in the session 
    $sessData['userData'] = $userData; 
     
    // Process the form data 
    if(empty($errorMsg)){ 
        if(!empty($StudentID)){ 
            // Update data in SQL server 
            $sql = "UPDATE Students SET FirstName = ?, LastName = ?, Email = ?, Section = ? WHERE StudentID = ?";   
            $query = $conn->prepare($sql);   
            $update = $query->execute(array($FirstName, $LastName, $Email, $Section, $StudentID)); 
             
            if($update){ 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Student data has been updated successfully.'; 
                 
                // Remove submitted field values from session 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                // Set redirect url 
                $redirectURL = 'addEdit.php'.$id_str; 
            } 
        }else{ 
            // Insert data in SQL server 
            $sql = "INSERT INTO Students (FirstName, LastName, Email, Section, Created) VALUES (?,?,?,?,?)";   
            $params = array( 
                &$FirstName, 
                &$LastName, 
                &$Email, 
                &$Section, 
                date("Y-m-d H:i:s") 
            );   
            $query = $conn->prepare($sql); 
            $insert = $query->execute($params);   
             
            if($insert){ 
                //$StudentID = $conn->lastInsertId(); 
                 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Student data has been added successfully.'; 
                 
                // Remove submitted field values from session 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                // Set redirect url 
                $redirectURL = 'addEdit.php'.$id_str; 
            } 
        } 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = '<p>Please fill all the mandatory fields.</p>'.$errorMsg; 
         
        // Set redirect url 
        $redirectURL = 'addEdit.php'.$id_str; 
    } 
     
    // Store status into the session 
    $_SESSION['sessData'] = $sessData; 
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){ 
    $StudentID = $_GET['id']; 
     
    // Delete data from SQL server 
    $sql = "DELETE FROM Students WHERE StudentID = ?"; 
    $query = $conn->prepare($sql); 
    $delete = $query->execute(array($StudentID)); 
     
    if($delete){ 
        $sessData['status']['type'] = 'success'; 
        $sessData['status']['msg'] = 'Student data has been deleted successfully.'; 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
    } 
     
    // Store status into the session 
    $_SESSION['sessData'] = $sessData; 
} 
 
// Redirect to the respective page 
header("Location:".$redirectURL); 
exit(); 
?>