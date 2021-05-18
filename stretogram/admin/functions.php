<?php
function checkAdmin($ad_uid, $ad_email){
  GLOBAL $papa_dbh;
  $q_c = $papa_dbh->prepare("SELECT id FROM admin WHERE admin_unique_id=? && admin_email=?");
  $q_c->execute([$ad_uid, $ad_email]);
  if($q_c->rowCount() == 1){
    $msg = "success";
    return $msg;
  }else{
    $error = "Failed";
    return $error;
  }
}

function generateUid(){
  GLOBAL $papa_dbh;
  $chac = "1234567890qwert/&yuiopasdfghjklzxcvbnm/&";
  $chac_c = str_shuffle($chac);
  $cah_v = substr($chac_c, 0, 19);
  $date = time();
  $ad_uid = $cah_v.$date;

  $q_c_u = $papa_dbh->prepare("SELECT id FROM admin WHERE admin_unique_id=? ");
  $q_c_u->execute([$ad_uid]);
  if($q_c_u->rowCount() == 0){
    $msg = $ad_uid;
    return $msg;
  }else{
    $error = generateUid();
  }
}

function checkEmail($ad_email){
  GLOBAL $papa_dbh;
  $q_c_e = $papa_dbh->prepare("SELECT id FROM admin WHERE admin_email=?");
  $q_c_e->execute([$ad_email]);
  if($q_c_e->rowCount() == 0){
    $msg = "success";
    return $msg;
  }else{
    $msg = "Email already exist";
    return $msg;
  }
}

function checkCountry($country){
  GLOBAL $papa_dbh;
  $q_c_c = $papa_dbh->prepare("SELECT id FROM countries WHERE country=?");
  $q_c_c->execute([$country]);
  if($q_c_c->rowCount() == 0){
    $msg = "success";
    return $msg;
  }else{
    $msg = "Country already exist";
    return $msg;
  }
}

function checkCountryCode($country_code){
  GLOBAL $papa_dbh;
  $q_c_cc = $papa_dbh->prepare("SELECT id FROM countries WHERE country_code=?");
  $q_c_cc->execute([$country_code]);
  if($q_c_cc->rowCount() == 0){
    $msg = "success";
    return $msg;
  }else{
    $msg = "Country code already exist";
    return $msg;
  }
}

?>
