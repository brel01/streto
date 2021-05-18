<?php
 function checkUser($useremail, $password){
   GLOBAL $papa_dbh;
   $q = $papa_dbh->prepare("SELECT id FROM users WHERE useremail=?");
   $q->execute([$useremail]);
   if($q->rowCount() == 1){
     $qr = $papa_dbh->prepare("SELECT password, pepper FROM users WHERE useremail=?");
     $qr->execute([$useremail]);
     $r = $qr->fetchAll(PDO::FETCH_ASSOC)[0];
     // $password = $r['password'];
     $pepper = $r['pepper'];
     $pwd_peppered = hash_hmac("sha256", $password, $pepper);
     $pwd_hashed = $r['password'];

     if(password_verify($pwd_peppered, $pwd_hashed)){
       $query  = $papa_dbh->prepare("SELECT user_id, fullname, username, useremail, verified, type FROM users WHERE useremail=?");
       $query->execute([$useremail]);
       if($query->rowCount() == 1){
         $row_s = $query->fetchAll(PDO::FETCH_ASSOC)[0];
         // fake u_id
         $u_id = "neri12ugbuibu2fbguer&jjb3v?jdfjbfeugbeur";
         // end of fake u_id
         if($row_s['useremail'] == $useremail){
           $cruise = $row_s['user_id'];
           $fullname = $row_s['fullname'];
           $username = $row_s['username'];
           $useremail = $row_s['useremail'];
           $verified = $row_s['verified'];
           $type = $row_s['type'];
           // verified is thesame thing as cleared
           $ret = ["msg" => "success", "user_id"=>$u_id, "cruise"=>$cruise, 'f_name'=>$fullname, 'u_name'=>$username, 'u_email'=>$useremail, 'cleared'=>$verified, 'type'=> $type];
           $msg = $ret;
           return $msg;
         }else{
           $error = ["msg" => "1error occured during processing"];
           $msg = $error;
           return $msg;
         }
       }else{
         $error = ["msg" => "error occured during processing"];
         $msg = $error;
         return $msg;
       }
     }else{
       $error = ["msg" => "Invalid Password"];
       $msg = $error;
       return $msg;
     }

   }else{
     $error = "Invalid Email";
     $msg = $error;
     return $msg;
   }
 }
 ?>
