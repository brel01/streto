<?php

include("shamaya.php");
include("functions.php");
if(isset($_POST)){
  $json = file_get_contents('php://input');
  $r_data = json_decode($json,true);
  // user check
  $q_c = $papa_dbh->prepare("SELECT id FROM users WHERE useremail=?");
  $q_c->execute([$r_data['email']]);

  // agent check
  $q_ac = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_email=?");
  $q_ac->execute([$r_data['email']]);

  if($q_c->rowCount() == 1){
    $useremail = $r_data['email'];
    $password = $r_data['password'];
    if(filter_var($useremail,FILTER_VALIDATE_EMAIL)){
      if(strlen($password) > 5){
        $response = checkUser($useremail, $password);
        $msg = json_encode($response);
        echo $msg;
      }else{
        $error = "Password Incorrect";
        $msg = json_encode($error);
        echo $msg;
      }
    }else{
      $error = "Invalid Email";
      $msg = $error;
      echo $msg;
    }
  }elseif($q_ac->rowCount() == 1){
    include("agents/agent_login_handler.php");
  }else{
    $error = "Invalid details";
    $msg = $error;
    echo $msg;
  }

}
?>
