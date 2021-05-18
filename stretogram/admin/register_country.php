<?php
include('../shamaya.php');
include("functions.php");

if(isset($_POST)){
  $json = file_get_contents("php://input");
  $return = json_decode($json, true);

  $ad_email = $return['ad_email'];
  $ad_uid = $return['ad_uid'];
  $country = $return['country'];

  if(strlen($ad_email) > 0){
    if(strlen($ad_uid) > 0){
      if(strlen($country) > 0){
        $checkAdmin = checkAdmin($ad_uid, $ad_email);
        if($checkAdmin == "success"){
          $country_code = $return['country_code'];
          $country = $return['country'];
          // $state = $return['state'];
          // $state = explode(",",$st);
          // $state = str();
          $permit = $return['permit'];
          if(strlen($country_code) > 0){
            if(strlen($country) > 0){
              // if(count($state) > 0){
                if(strlen($permit) == 1){
                  $checkCountry = checkCountry($country);
                  $checkCountryCode = checkCountryCode($country_code);
                  if($checkCountry == "success"){
                    if($checkCountryCode == "success"){
                      $query = $papa_dbh->prepare("INSERT INTO countries(country_code, country, permit) VALUE(?,?,?)");
                      $query->execute([$country_code, $country, $permit]);
                      if($query){
                        $error = "success";
                        $msg  =json_encode($error);
                        echo $msg;
                      }else{
                        $error = "Error occured during processing";
                        $msg  =json_encode($error);
                        echo $msg;
                      }
                    }else{
                      $error = "Country code already exist";
                      $msg  =json_encode($error);
                      echo $msg;
                    }
                  }else{
                    $error = "Country already exist";
                    $msg  =json_encode($error);
                    echo $msg;
                  }
                }else{
                  $error = "Error occured during processing";
                  $msg  =json_encode($error);
                  echo $msg;
                }
              }else{
                $error = "State is invalid";
                $msg  =json_encode($error);
                echo $msg;
              }
            // }else{
            //   $error = "Country is invalid";
            //   $msg  =json_encode($error);
            //   echo $msg;
            // }
          }else{
            $error = "Invalid country code";
            $msg  =json_encode($error);
            echo $msg;
          }
        }else{
          $error = "Error occured during processing";
          $msg  =json_encode($error);
          echo $msg;
        }
      }else{
        $error = "Country is invalid";
        $msg  =json_encode($error);
        echo $msg;
      }
    }else{
      $error = "Error occured during processing";
      $msg  =json_encode($error);
      echo $msg;
    }
  }else{
    $error = "Error occured during processing";
    $msg  =json_encode($error);
    echo $msg;
  }
}else{
  $error = "Error occured during processing";
  $msg = json_encode($error);
  echo $msg;
}
?>
