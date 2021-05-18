<?php

 function check_agent($ag_email, $ag_password){
   GLOBAL $papa_dbh;
   $q = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_email=?");
   $q->execute([$ag_email]);
   if($q->rowCount() == 1){
     $qr = $papa_dbh->prepare("SELECT agent_password, agent_pepper FROM agents WHERE agent_email=?");
     $qr->execute([$ag_email]);
     $r = $qr->fetchAll(PDO::FETCH_ASSOC)[0];
     // $password = $r['password'];
     $ag_pepper = $r['agent_pepper'];
     $pwd_peppered = hash_hmac("sha256", $ag_password, $ag_pepper);
     $pwd_hashed = $r['agent_password'];

     if(password_verify($pwd_peppered, $pwd_hashed)){
       $msg = "success";
       return $msg;
     }else{
       $error = "Invalid Password";
       $msg = $error;
       return $msg;
     }

   }else{
     $error = "Invalid Email";
     $msg = $error;
     return $msg;
   }
 }

 function confirm_agent($ag_uid, $ag_email){
   GLOBAL $papa_dbh;
   $q = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_email=?");
   $q->execute([$ag_email]);
   if($q->rowCount() == 1){
     $qr = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_unique_id=? && agent_email=?");
     $qr->execute([$ag_uid, $ag_email]);
     if($qr->rowCount() > 0){
       $error = "success";
       $msg = $error;
       return $msg;
     }else{
       $error = "Error occured during processing";
       $msg = $error;
       return $msg;
     }
   }else{
     $error = "Error occured during processing";
     $msg = $error;
     return $msg;
   }
 }

 function get_agent_uid($ag_email, $ag_password){
   GLOBAL $papa_dbh;
   $q = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_email=?");
   $q->execute([$ag_email]);
   if($q->rowCount() == 1){
     $qr = $papa_dbh->prepare("SELECT agent_password, agent_pepper FROM agents WHERE agent_email=?");
     $qr->execute([$ag_email]);
     $r = $qr->fetchAll(PDO::FETCH_ASSOC)[0];
     // $password = $r['password'];
     $ag_pepper = $r['agent_pepper'];
     $pwd_peppered = hash_hmac("sha256", $ag_password, $ag_pepper);
     $pwd_hashed = $r['agent_password'];

     if(password_verify($pwd_peppered, $pwd_hashed)){
       $q_uid = $papa_dbh->prepare("SELECT agent_unique_id FROM agents WHERE agent_email=?");
       $q_uid->execute([$ag_email]);
       if($q_uid->rowCount() > 0){
         $r_uid = $q_uid->fetchAll(PDO::FETCH_ASSOC)[0];
         $agent_uid = $r_uid['agent_unique_id'];
         if(strlen($agent_uid) > 0){
           return $agent_uid;
         }else{
           $error = "failed";
           return $error;
         }
       }else{
         $error = "failed";
         return $errorr;
       }
     }else{
       $error = "failed";
       $msg = $error;
       return $msg;
     }

   }else{
     $error = "failed";
     $msg = $error;
     return $msg;
   }
 }
 // function  get_agent_uid($ag_email){
 //   GLOBAL $papa_dbh;
 //   $q_uid = $papa_dbh->prepare("SELECT agent_unique_id FROM agents WHERE agent_email=?")
 //   $q_uid->execute([$ag_email]);
 //   if($q_uid->rowCount() > 0){
 //     $r_uid = $qp->fetchAll(PDO::FETCH_ASSOC)[0];
 //     $agent_uid = $r_uid['agent_unique_id'];
 //     if(strlen($agent_uid) > 0){
 //       return $agent_uid;
 //     }else{
 //       $error = "failed";
 //       return $error;
 //     }
 //   }else{
 //     $error = "failed";
 //     return $errorr;
 //   }
 // }



function get_agent_pepper($ag_email){
  GLOBAL $papa_dbh;
  $qp = $papa_dbh->prepare("SELECT agent_pepper FROM agents WHERE agent_email=?");
  $qp->execute([$ag_email]);
  if($qp->rowCount() > 0){
    $r_p = $qp->fetchAll(PDO::FETCH_ASSOC)[0];
    $r_pepper = $r_p['agent_pepper'];
    if(strlen($r_pepper) > 0){
      return $r_pepper;
    }else{
      $error = "failed";
      return $error;
    }
  }else{
    $error = "failed";
    return $errorr;
  }
}

function get_agent_password($ag_email){
  GLOBAL $papa_dbh;
    $qe = $papa_dbh->prepare ("SELECT agent_password FROM agents WHERE agent_email=?");
    $qe->execute([$ag_email]);
    if($qe->rowCount() == 1){
      $r_pwd = $qe->fetchAll(PDO::FETCH_ASSOC)[0];
      $pwd = $r_pwd['agent_password'];
      return $pwd;
    }else{
      $error = "failed";
      $msg = $error;
      return $msg;
    }
}
function checkEmail($ag_email){
  GLOBAL $papa_dbh;
    $qe = $papa_dbh->prepare ("SELECT id FROM agents WHERE agent_email=?");
    $qe->execute([$ag_email]);
    if($qe->rowCount() == 1){
      $msg = "Email already exist";
      return $msg;
    }else{
      $error = "success";
      $msg = $error;
      return $msg;
    }
}

function checkPhone($ag_phone){
  GLOBAL $papa_dbh;
    $qee = $papa_dbh->prepare ("SELECT id FROM agents WHERE agent_phone=?");
    $qee->execute([$ag_phone]);
    if($qee->rowCount() == 1){
      $msgg = "Phone already exist";
      return $msgg;
    }else{
      $errorr = "success";
      $msgg = $errorr;
      return $msgg;
    }
}

 function hashPwd($ag_password, $ag_fname, $ag_phone, $company_name){
   GLOBAL $papa_dbh;
   $date = time();
   $cac = "fyufug4498342grb";
   $pepper_value = $ag_fname.$cac.$date.$ag_phone.$company_name;
   $pepper = str_shuffle($pepper_value);
   $pwd = $ag_password;
   $pwd_p = hash_hmac("sha256", $pwd, $pepper);
   $password = password_hash($pwd_p, PASSWORD_DEFAULT);
   $p_array = ["pepper"=>$pepper, "password"=>$password];
   return $p_array;
 }

 function generateUid(){
   GLOBAL $papa_dbh;
     $date = time();
     $Alpha22=range("A","Z");
     $Alpha12=range("A","Z");
     $alpha22=range("a","z");
     $alpha12=range("a","z");
     $num22=range(1000,9999);
     $num12=range(1000,9999);
     $numU22=range(99999,10000);
     $numU12=range(99999,10000);
     $AlphaB22=array_rand($Alpha22);
     $AlphaB12=array_rand($Alpha12);
     $alphaS22=array_rand($alpha22);
     $alphaS12=array_rand($alpha12);
     $Num22=array_rand($num22);
     $NumU22=array_rand($numU22);
     $Num12=array_rand($num12);
     $NumU12=array_rand($numU12);
     $res22=$Alpha22[$AlphaB22].$num22[$Num22].$Alpha12[$AlphaB12].$numU22[$NumU22].$alpha22[$alphaS22].$num12[$Num12].$date;
     $retj=str_shuffle($res22);
     $alaye = "$/$/";
     $rak = str_shuffle($retj.$alaye);
     $retjy = str_shuffle($rak);
     $text22=$rak.$retjy.$res22;
     // $_SESSION['randnum']= $text22;

     $qryt = $papa_dbh->prepare("SELECT id FROM agents WHERE agent_unique_id=?");
     $qryt->execute([$text22]);
     if($qryt->rowCount() == 0){
         return $text22;
     }else{
         return generateUid();
     }
 }

 ?>
