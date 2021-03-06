<?php
/**
 * Created by PhpStorm.
 * User: micheal
 * Date: 11/26/13
 * Time: 8:18 AM
 */
session_start();

 require_once 'Backend\addClient.php';
 require_once 'Backend\Login.php';
 require_once 'Backend\TrackingSystem.php';
 require_once 'Backend\Redirect.php';
 require_once 'Backend\upload.php';



$Redirect = new \Backend\Redirect();
$login = new \Backend\Login();
$addClient = new \Backend\addClient();
$tracking = new \Backend\TrackingSystem();
$upload = new \Backend\upload();

//$companyAdd->add('comp1', 'company1', 'Company1');

if(isset($_POST['username']) && isset($_POST['password']))
{
    if($_POST['username'] === 'BWAdmin' && $_POST['password'] === 'B3stwayTransfer1')
    {
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['loginType'] = 'employee';

        $Redirect->employeeRedirect();
    }
    else
    {
        if($login->checkLogin($_POST['username'], $_POST['password']) == TRUE)
        {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['loginType'] = 'company';
            $Redirect->customerRedirect();
        }
        else
        {
            $Redirect->loginRedirect();
        }
    }
}

if(isset($_POST['submitfrom']))
{
    if($_POST['submitfrom'] == 'standardLogin')
    {
        if($login->checkLogin($_POST['username'], $_POST['password']) == TRUE)
        {
            $_SESSION['loginType'] == 'company';
        }
    }
    elseif($_POST['submitfrom'] == 'add_shipment')
    {
        $tracking->addShipmentInfo($_POST['pronumber'], $_POST['status'],$_POST['pickupLocation'], $_POST['deliveryLocation'], $_POST['service'], $_POST['equipment'], $_POST['companyname'], $_POST['currentlocationcity'], $_POST['currentlocationstate']);
        if($_SESSION['loginType'] == 'employee')
        {
            $Redirect->employeeRedirect();
        }
    }
    elseif($_POST['submitfrom'] == 'updateRecord')
    {
        $tracking->updateShipmentInfo($_POST['recordNumber'], $_POST['pronumber'], $_POST['status'],$_POST['pickupLocation'], $_POST['deliveryLocation'], $_POST['service'], $_POST['equipment'], $_POST['companyname'], $_POST['currentlocationcity'], $_POST['currentlocationstate']);
        if($_SESSION['loginType'] == 'employee')
        {
            $Redirect->updateRecord($_POST['pronumber']);
        }
    }
    elseif($_POST['submitfrom'] == 'upload')
    {
        if($upload->file_upload() != TRUE)
        {
            echo 'UH OH! there was a problem!';
        }
        else{
            //$location = getcwd()."\\".$upload->uploadName;
            $tracking->UploadtoDB($_POST['pronumber'], $_POST['docType'], $upload->uploadName);
            $Redirect->uploadRedirect();
        }
    }
    elseif($_POST['submitfrom'] == 'addClient')
    {
        $addClient->getInfo($_POST['username'], $_POST['password'], $_POST['email']);
    }
}
?>