<?php
include("shamaya.php");
    if(isset($_POST)){
       $json = file_get_contents('php://input');
       $resp = json_decode($json,true);

       $fbi = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_email=?");
       $fbi->execute([$resp['email']]);

       $fbi_2 = $papa_dbh->prepare("SELECT id FROM users WHERE useremail=?");
       $fbi_2->execute([$resp['email']]);

       if($fbi->rowCount() == 0 && $fbi_2->rowCount() == 0){
         // if(){
           if($resp['type'] == "agent"){
             include("agents/agent_register_handler.php");
           }elseif($resp['type'] == "user"){
             include("user_register_handler.php");
           }else{
             $error = "error occured during process";
             $msg = json_encode($error);
             echo $msg;
           }
         // }else{
         //   $error = "Email already exist";
         //   $msg = json_encode($error);
         //   echo $msg;
         // }
       }else{
         $error = "Email already exist";
         $msg = json_encode($error);
         echo $msg;
       }
    }else{
        $error = "error occured during process";
        $msg = json_encode($error);
        echo $msg;
    }
?>
