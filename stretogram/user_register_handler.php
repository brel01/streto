<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($fname, $uemail){
  // global $papa_dbh;
  // $fname = $details['fullname'];
  // $uname = $details['username'];
  // $uemail = $detaisl['useremail'];

    $message = "
      <center><img src='https://spiritanssounds.com/logo3.jpeg' /></center>
      <center><h2>HoodFulx</h2>
      <p>".$fname."</p>
      <p>Your Registration Is Successfull</p>
      <p>Thank You For Registering With Us</p>
      <a href='https://webmit.org'><p style='font-size:small'>@webmit</p></a>
      </center>
    ";
    //Load phpmailer
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'spiritanssounds.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@spiritanssounds.com';
        $mail->Password = '@spiritanssounds.com';
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('info@spiritanssounds.com');

        //Recipients
        $mail->addAddress($uemail);
        $mail->addReplyTo("info@stretogram.com");

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body    = $message;

        $mail->send();


    }
    catch (Exception $e) {
     //   $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        $message = 'Message could not be sent. Mailer Error: ';
     $error = json_encode($message);
     echo $error;
   };
}
    if(isset($_POST)){
       $json = file_get_contents('php://input');
       $resp = json_decode($json,true);
       $email = $resp['email'];
       if(isset($resp['fname'])){
            if(strlen($resp['fname']) > 2){
                // if(strlen($resp['uname']) > 0){
                    if(strlen($resp['email'])){
                        if(filter_var($resp['email'],FILTER_VALIDATE_EMAIL)){
                            if(strlen($resp['contact']) > 0){
                                $qy = $papa_dbh->prepare("SELECT id FROM users WHERE useremail=?");
                                $qy->execute([$resp['email']]);
                                if($qy->rowCount() == 0){
                                    // $qyy = $papa_dbh->prepare("SELECT id FROM users WHERE username=?");
                                    // $qyy->execute([$resp['uname']]);
                                    // if($qyy->rowCount() == 0){
                                        $qyyy = $papa_dbh->prepare("SELECT id FROM users WHERE contact=?");
                                        $qyyy->execute([$resp['contact']]);
                                        if($qyyy->rowCount() == 0){
                                            if(strlen($resp['password']) > 5){
                                                function generateUid(){
                                                  global $papa_dbh;
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
                                                    $text22=$retjy.$rak.$res22;
                                                    // $_SESSION['randnum']= $text22;

                                                    $qryt = $papa_dbh->prepare("SELECT id FROM users WHERE unique_id=?");
                                                    $qryt->execute([$text22]);
                                                    if($qryt->rowCount() == 0){
                                                        return $text22;
                                                    }else{
                                                        return generateUid();
                                                    }
                                                }

                                                // $p_arra
                                                $fullname = $resp['fname'];
                                                $useremail = $resp['email'];
                                                $contact = $resp['contact'];
                                                $country = $resp['country'];
                                                $state = $resp['state'];
                                                $reg_date = time();
                                                $date =  time();
                                                $unique_id = generateUid();
                                                $cac = "fyufug4498342grb";
                                                $pepper_value = $fullname.$cac.$date.$contact;
                                                $pepper = str_shuffle($pepper_value);
                                                $pwd = $resp['password'];
                                                $pwd_p = hash_hmac("sha256", $pwd, $pepper);
                                                $password = password_hash($pwd_p, PASSWORD_DEFAULT);
                                                $query = $papa_dbh->prepare("INSERT INTO users (user_id, fullname, useremail, contact, country, state, password, reg_date, pepper) VALUE (?,?,?,?,?,?,?,?,?) ");
                                                $query->execute([$unique_id, $fullname, $useremail, $contact, $country, $state, $password, $reg_date, $pepper]);
                                                if($query){
                                                    // $d_array = ["fullname" => $fullname, "username"=>$username, "useremail"=>$useremail];
                                                    $send = sendMail( $fullname, $useremail );
                                                    $error = "success";
                                                    $msg = json_encode($error);
                                                    echo $msg;
                                                }else{
                                                    $error = "Error occured during process";
                                                    $msg = json_encode($error);
                                                    echo $msg;
                                                }
                                            }else{
                                                $error = "Least Characters for Password is 6";
                                                $msg = json_encode($error);
                                                echo $msg;
                                            }
                                        }else{
                                            $error = "Phone number already used";
                                            $msg = json_encode($error);
                                            echo $msg;
                                        }
                                    // }else{
                                    //     $error = "Username is not available";
                                    //     $msg = json_encode($error);
                                    //     echo $msg;
                                    // }
                                }else{
                                    $error = "Email already exist";
                                    $msg = json_encode($error);
                                    echo $msg;
                                }
                            }else{
                                $error = "Invalid Phone Number";
                                $msg = json_encode($error);
                                echo $msg;
                            }
                        }else{
                            $error = "Invalid Email";
                            $msg = json_encode($error);
                            echo $msg;
                        }
                    }else{
                        $error = "Invalid Useremail";
                        $msg = json_encode($error);
                        echo $msg;
                    }
                // }else{
                //     $error = "Invalid Username";
                //     $msg = json_encode($error);
                //     echo $msg;
                // }
            }else{
                $error = "Invalid fulname";
                $msg = json_encode($error);
                echo $msg;
            }
       }else{
           $error = ['error'=>'Fullname cannot be empty'];
           $msg = json_encode($error);
           echo $msg;
       }
    }else{
        $error = "error occured during process";
        $msg = json_encode($error);
        echo $msg;
    }
?>
