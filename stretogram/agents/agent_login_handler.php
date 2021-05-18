<?php
include("../shamaya.php");
include("functions.php");

if(isset($_POST)){
  $json = file_get_contents('php://input');
  $_return_data = json_decode($json,true);

  $ag_email = $_return_data['email'];
  $ag_email = str_replace(' ', '', $ag_email);
  if(strlen($ag_email) > 0){
    if(filter_var($ag_email, FILTER_VALIDATE_EMAIL)){
      $checkEmail = checkEmail($ag_email);
      if($checkEmail == "success"){
        $ag_password = $_return_data['ag_password'];
        if(strlen($ag_password) > 5){
          $check_agent =  check_agent($ag_email, $ag_password);
          if($check_agent == "success"){
            $agent_uid = get_agent_uid($ag_email, $ag_password);
            if($agent_uid !== "failed"){
              // agent_uid is the fake one
              $error = [ "msg"=>"success", "agent_uid"=>"ueriergiuuiog9439ru429ti4htiht23rf2v", "_ag_" => $agent_uid];
              $msg =  json_encode($error);
              echo $msg;
            }else{
              $error = ["msg"=>"Error occued during process"];
              $msg = json_encode($error);
              echo $msg;
            }
          }else{
            $error = ["msg" => $check_agent];
            $msg = json_encode($error);
            echo $msg;
          }
        }else{
          $error = ["msg"=>"Invalid Password"];
          $msg = json_encode($error);
          echo $msg;
        }
      }else{
        $error = ["msg"=>"Invalid Email"];
        $msg = json_encode($error);
        echo $msg;
      }
    }else{
      $error = ["msg"=>"Email is Invalid"];
      $msg = $error;
      echo $error;
    }
  }else{
    $error = ["msg"=>"Email cannot be empty"];
    $msg = $error;
    echo $msg;
  }
}else {
  $error = ["msg"=>"Error occured during process"];
  $msg = json_encode($error);
  echo $msg;
}
?>
