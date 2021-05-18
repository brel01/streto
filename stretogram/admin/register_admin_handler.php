<?php
include('../shamaya.php');
include("functions.php");

if(isset($_POST)){
  $json = file_get_contents("php://input");
  $return = json_decode($json, true);

  $ad_fname = $return['ad_fname'];
  $ad_email = str_replace(" ", "", $return['ad_email']);
  $ad_phone = str_replace(" ", "", $return['ad_phone']);
  $ad_country = $return['ad_country'];
  $ad_state = $return['ad_state'];
  $ad_password = $return['ad_password'];

  if(strlen($ad_fname) > 0){
    if(strlen($ad_email) > 0){
      if(filter_var($ad_email, FILTER_VALIDATE_EMAIL)){
        $checkEmail = checkEmail($ad_email);
        if($checkEmail == "success"){
          if(strlen($ad_phone) > 0){
            if(strlen($ad_country) > 0){
              if(strlen($ad_state) > 0){
                $ad_uid = generateUid();
                $pep = $ad_fname.$ad_email.$ad_phone.$ad_country;
                $pepper = str_shuffle($pep);
                $reg_date = time();
                $pwd = hash_hmac("sha256", $ad_password, $pepper);
                $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);
                $ad_profile_pic = "loading";
                $query = $papa_dbh->prepare("INSERT INTO admin(admin_unique_id, admin_fname, admin_email, admin_phone, admin_country, admin_state, admin_profile_pic, admin_password, admin_reg_date, admin_pepper) VALUE (?,?,?,?,?,?,?,?,?,?)");
                $query->execute([$ad_uid, $ad_fname, $ad_email, $ad_phone, $ad_country, $ad_state, $ad_profile_pic, $pwd_hashed, $reg_date, $pepper]);
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
                $error = "State is invalid";
                $msg = json_encode($error);
                echo $msg;
              }
            }else{
              $error = "Country is invalid";
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
      }else{
        $error = "Email is invalid";
        $msg = json_encode($error);
        echo $msg;
      }
    }else{
      $error = "Email cannot  be empty";
      $msg = json_encode($error);
      echo $msg;
    }
  }else{
    $error = "Fullname cannot be empty";
    $msg = json_encode($error);
    echo $msg;
  }
}
?>
