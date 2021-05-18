<?php
include("functions.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    if(isset($_POST)){
       $json = file_get_contents('php://input');
       $resp = json_decode($json,true);
       $ag_fname =  $resp['ag_fname'];
       if(strlen($ag_fname) > 2){
         $company_name = $resp['company_name'];
         if(strlen($company_name) > 0){
           $ag_email = str_replace(' ', '', $resp['email']);
           if(strlen($ag_email) > 0){
             if(filter_var($ag_email,FILTER_VALIDATE_EMAIL)){
               $email_con = checkEmail($ag_email);
               if($email_con == "success"){
                 $ag_phone = str_replace(' ', '', $resp['ag_phone']);
                 if(strlen($ag_phone) > 0){
                   $checkPhone = checkPhone($ag_phone);
                   if($checkPhone == "success"){
                     $ag_country = $resp['ag_country'];
                     if(strlen($ag_country) > 0){
                       $ag_state = $resp['ag_state'];
                       if(strlen($ag_state) > 0){
                         $ag_password = $resp['ag_password'];
                         if(strlen($ag_password) > 5){
                           $pwd_array = hashPwd($ag_password, $ag_fname, $ag_phone, $company_name);
                           $pwd_hashed = $pwd_array['password'];
                           $ag_pepper = $pwd_array['pepper'];
                           $ag_u_id = generateUid();
                           $date = time();
                           $query = $papa_dbh->prepare("INSERT INTO agents(agent_unique_id, agent_fullname, company_name, agent_email, agent_phone, agent_country, agent_state, agent_password, agent_reg_date, agent_pepper) VALUE (?,?,?,?,?,?,?,?,?,?)");
                           $query->execute([$ag_u_id, $ag_fname, $company_name, $ag_email, $ag_phone, $ag_country, $ag_state, $pwd_hashed, $date, $ag_pepper]);
                           if($query){
                             $error = "success";
                             $msg = json_encode($error);
                             echo $msg;
                           }else{
                             $error = "Error occured during process";
                             $msg = json_encode($error);
                             echo $msg;
                           }
                         }else{
                           $error = "Password: 6 is the least number of characters";
                           $msg = json_encode($error);
                           echo $msg;
                         }
                       }else{
                         $error = "State Is Invalid";
                         $msg = json_encode($error);
                         echo $msg;
                       }
                     }else{
                       $error = "Country Is Invalid";
                       $msg = json_encode($error);
                       echo $msg;
                     }
                   }else{
                     $error = "Phone Already Exist";
                     $msg = json_encode($error);
                     echo $msg;
                   }
                 }else{
                   $error = "Phone cannot be empty";
                   $msg = json_encode($error);
                   echo $msg;
                 }
               }else{
                 $error = "Email already exist";
                 $msg = json_encode($error);
                 echo $msg;
               }
             }else {
               $error = "Email is Invalid";
               $msg = json_encode($error);
               echo $msg;
             }
           }else{
             $error = "Email cannot be empty";
             $msg = json_encode($error);
             echo $msg;
           }
         }else{
           $error = "Company name cannot be empty";
           $msg = json_encode($error);
           echo $msg;
         }
       }else{
         $error = "Fullname: Number of Characters too low";
         $msg = json_encode($error);
         echo $msg;
       }
    }else{
        $error = "error occured during process";
        $msg = json_encode($error);
        echo $msg;
    }
?>
