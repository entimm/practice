<?php
/**********
 * 发送邮件 *
 **********/
function SendMail($address,$name,$title,$message)
{
    vendor('PHPMailer.class#PHPMailer');

    $mail=new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet='UTF-8';
    $mail->Encoding = "base64";
    $mail->AddAddress($address,$name);
    $mail->Body=$message;
    $mail->From=C('MAIL_ADDRESS');
    $mail->FromName=C('MAIL_FROMNAME');
    $mail->Subject=$title;
    $mail->Host=C('MAIL_SMTP');
    $mail->Port = 25;
    $mail->SMTPAuth=true;

    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');

    return($mail->Send());
}

function array_sort($arr,$keys,$type='desc'){
    $keysvalue = $new_array = array();
    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$keys];
    }
    if($type == 'desc'){
        arsort($keysvalue);
    }else{
        asort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k=>$v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}
?>