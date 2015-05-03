<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2/05/2015
 * Time: 10:28 PM
 **/
	require_once("Rest.inc.php");
	require_once('./class.phpmailer.php');
	require_once('./class.pop3.php');
	require_once('./class.smtp.php');

	class mailsvc
    {
        public function __construct() {

        }
        public function mailsvc($body)
        {
            //parse the data passed to this method
            foreach ($body as $key => $value) {
                if (is_array($value) && !empty($value)) {
                    if ($key == 'customer') {
                        foreach ($value as $k => $v) {
                            if (isset($k) && !empty($v)) {
                                if ($k == 'fname') {
                                    $firstName = $v;
                                }
                                if ($k == 'lname') {
                                    $lastName = $v;
                                }
                                if ($k == 'gender') {
                                    $gender = $v;
                                }
                                if ($k == 'salutation') {
                                    $salutation = $v;
                                }
                            }
                        }
                    }
                    if ($key == 'mail') {
                        foreach ($value as $k => $v) {
                            if (isset($k) && !empty($v)) {
                                if ($k == 'language_id') {
                                    $languageId = $v;
                                }
                                if ($k == 'type') {
                                    $emailType = $v;
                                }
                                if ($k == 'send_time') {
                                    $sendTime = $v;
                                }
                                if ($k == 'time') {
                                    $time = $v;
                                }
                                if ($k == 'email_address') {
                                    $toAddress = $v;
                                }
                            }
                        }
                    }
                    if ($key == 'data') {
                        foreach ($value as $k => $v) {
                            if (isset($k) && !empty($v)) {
                                if ($k == 'link') {
                                    $link = $v;
                                }
                                if ($k == 'items') {
                                    if (is_array($v) && !empty($v)) {
                                        $items=$v;
                                    }
                                }
                                if($k=='order_number'){
                                    $orderNumber=$v;
                                }
                                if ($k == 'delivery_type') {
                                    foreach ($v as $k1 => $v1) {
                                        if ($k1 == 'id') {
                                            $delivery_id = $v1;
                                        }
                                        if ($k1 == 'value') {
                                            $delivery_data = $v1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $emailContent = '';

            //SMTP needs accurate times, and the PHP time zone MUST be set
            //This should be done in your php.ini, but this is how to do it if you don't have access to that
            date_default_timezone_set('Etc/UTC');

            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();

            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 2;

            //Ask for HTML-friendly debug output

            $mail->Debugoutput = 'html';

            //Set the hostname of the mail server
            $mail->Host = 'smtp.zoho.com';

            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission

            $mail->Port = 587;

            //Set the encryption system to use - ssl (deprecated) or tls

            $mail->SMTPSecure = 'tls';

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication - use full email address for gmail

            $mail->Username = "info@eso.nz";

            //Password to use for SMTP authentication
            $mail->Password = "teamEso2015";
            //Set who the message is to be sent from
            $mail->setFrom('info@eso.nz', 'ESO Team');
            //Set an alternative reply-to address
            $mail->addReplyTo('info@eso.nz', 'ESO Team');
            //Set who the message is to be sent to
            $mail->addAddress($toAddress, '');
            //Set the subject line
            if ($emailType == 'reg') {
                if ($languageId == 'cn') {
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = '邮件确认 -- ESO 团队';
                    $emailcontent = file_get_contents('regconfirm_cn.html');
                } else {
                    $mail->Subject = 'Registration Confirmation';
                    $emailcontent = file_get_contents('regconfirm_en.html');
                }
                $emailContent = str_replace("@URL", $link, $emailContent);
                $emailContent = str_replace("@FIRSTNAME", $firstName, $emailContent);
            } elseif ($emailType == 'checkout') {
                if ($languageId == 'cn') {
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = '购票确认 -- ESO 团队';
                    $emailContent = file_get_contents('checkoutconfirm_cn.html');
                } else {
                    $mail->Subject = 'Checkout Confirmation';
                    $emailContent = file_get_contents('checkoutconfirm_cn.html');
                }
                $itemDescription='';
                if (is_array($items) && !empty($items)) {
                    foreach ($items as $k => $v) {
                        foreach($v as $k1=>$v1){
                            if($k1=='name'){
                                $itemDescription+='Item Name:' . $v1 . '<br/>';
                            }
                            if($k1=='price'){
                                $itemDescription+='Item Price:' .$v1 . '<br/>';
                            }
                            if($k1=='Zone'){
                                $itemDescription+='Zone:' . $v1 . '<br/>';
                            }
                            if($k1=='quantity'){
                                $itemDescription+='Quantity:' . $v1 . '<br/>';
                            }
                        }
                    }
                }
                $emailContent=str_replace('@FIRSTNAME',$firstName,$emailContent);
                $emailContent=str_replace('@ORDERNUM',$orderNumber,$emailContent);
                $emailContent=str_replace('@ITEMS',$items,$emailContent);

                //hard-coded delivery instructions
                $delivery='We will deliver your tickets to the following address';
                $pickup='Please come to XXX at anytime from 9am. to 5pm. from Monday to Friday';

                if($delivery_id=='1'){
                    $emailContent=str_replace('@DELIVERYINS',$delivery,$emailContent);
                }
                if($delivery_id=='2'){
                    $emailContent=str_replace('@DELIVERYINS',$pickup,$emailContent);
                }
            }
            $mail->msgHTML($emailContent, dirname(__FILE__));
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                $this->response('', 200);
            } else {
                $this->response('', 406);;
            }
        }
    }
