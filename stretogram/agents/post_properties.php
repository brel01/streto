<?php
include ("../shamaya.php");
include("functions.php");

if(isset($_POST)){
  $json = file_get_contents("php://input");
  $data = json_decode($josn, true);

  $ag_email = $data['ag_email'];
  if(filter_var($ag_email, FILTER_VALIDATE_EMAIL)){
  $ag_uid = $data['ag_uid'];
    if(strlen($ag_uid) > 0){
      $confirm_agent = confirm_agent($ad_uid, $ag_email);
      if($confirm_agent == "success"){
        $pro_type = $data['property_type'];
        if(strlen($pro_type) > 0){
          $pro_price = $data['property_price'];
          if($pro_price > 0){
            if(is_numeric($pro_price) == true){
              $pro_address = $data['property_address'];
              if(strlen($pro_address) > 0){
                  $pro_comment = $data['property_comment'];
              }else{
                $error = "Address cannot be empty";
              }
            }else {
              $error = "Invalid Price";
            }
          }else{
            $error = "Invalid Amount";
          }
        }else{
          $error = "Type not selected";
        }
      }else{
        $error = "Error occued during processing";
      }
    }else{
      $error = "Error occured during processing";
    }
  }else{
    $error = "Error occured during processing";
    $msg = json_encode($error);
    echo $msg;
  }
}
?>
