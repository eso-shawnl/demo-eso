<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 2/05/2015
 * Time: 10:28 PM
 **/


	class mailservice
    {
        public function __construct() {


        }

        public function send_mail($body)
        {
            require_once('class.phpmailer.php');
            require_once('class.pop3.php');
            require_once('class.smtp.php');

            $root=realpath($_SERVER["DOCUMENT_ROOT"]);

            $result=array();
            $orderNumber='';
            //hard coded $body for testing
/*          $mail_date=array();
            $mail_date['customer']['fname']='Steven';
            $mail_date['customer']['lname']='Wang';
            $mail_date['customer']['gender']='';
            $mail_date['customer']['salutation']='';

            $mail_date['mail']['language_id']='en';
            $mail_date['mail']['type']='reg';
            $mail_date['mail']['send_time']='';
            $mail_date['mail']['time']= 1;
            $mail_date['mail']['email_address']='stevenwang0410@gmail.com';

            $mail_date['data']['link']='http://www.sina.com';
            $mail_date['data']['items']='';
            $mail_date['data']['delivery_type']['id']='';
            $mail_date['data']['delivery_type']['value']='';*/

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
            try{
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();

            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //$mail->Debugoutput = 'html';
            $mail->Host = 'smtp.zoho.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "info@eso.nz";
            $mail->Password = "teamEso2015";
            $mail->setFrom('info@eso.nz', 'ESO Team');
            $mail->addReplyTo('info@eso.nz', 'ESO Team');
            $mail->addAddress($toAddress, '');


            if ($emailType == 'reg') {
                if ($languageId == 'cn') {
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = '购票确认 -- ESO 团队';
                    include_once('regconfirm_cn.html') ;
                    $emailContent = file_get_contents('regconfirm_cn.html',true);
                } else {
                    include_once('regconfirm_en.html');
                    $mail->Subject = 'Registration Confirmation';
                    $emailContent = file_get_contents('regconfirm_en.html',true);
                }
                $emailContent = str_replace("@URL", $link, $emailContent);
                $emailContent = str_replace("@FIRSTNAME", $firstName, $emailContent);
            } elseif ($emailType == 'checkout') {
                 if ($languageId == 'cn') {
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = '购票确认 -- ESO 团队';
                    include_once('checkoutconfirm_cn.html') ;
                    $emailContent = file_get_contents('checkoutconfirm_cn.html',true);
                } else {
                     $mail->Subject = 'Ticket Purchase Confirmation - ESO Team';
                     include_once('checkoutconfirm_en.html');
                     $emailContent = file_get_contents('checkoutconfirm_en.html',true);
                 }

                 $itemDescription='';
                 if (is_array($items) && !empty($items)) {
                     foreach ($items as $k => $v) {
                         foreach ($v as $k1 => $v1) {
                             if ($k1 == 'name') {
                                 $itemDescription .= 'Item Name:' . $v1 . '<br/>';
                             }
                             if ($k1 == 'price') {
                                 $itemDescription .= 'Item Price:' . $v1 . '<br/>';
                             }
                             if ($k1 == 'zone') {
                                 $itemDescription .= 'zone:' . $v1 . '<br/>';
                             }
                             if ($k1 == 'quantity') {
                                 $itemDescription .= 'Quantity:' . $v1 . '<br/>';
                             }
                         }
                     }
                 }
                //hard-coded delivery instructions
                $delivery = 'We will deliver your tickets to the following address';
                $pickup = 'Please come to XXX at anytime from 9am. to 5pm. from Monday to Friday';
                if($delivery_id=='1'){
                    $emailContent=str_replace('@DELIVERYINS',$delivery,$emailContent);
                }
                if($delivery_id=='2'){
                    $emailContent=str_replace('@DELIVERYINS',$pickup,$emailContent);
                }

                $emailContent = str_replace('@FIRSTNAME', $firstName, $emailContent);
                $emailContent=str_replace('@ORDERID',$orderNumber,$emailContent);
                $emailContent=str_replace('@ITEMS',$itemDescription,$emailContent);
            }

            $mail->msgHTML($emailContent, dirname(__FILE__));

            $mail->send();
                $result[1]='';
        }
            catch(phpmailerException $e)
            {
                $result[0]=$e->errorMessage();
            }
            catch (Exception $e){
                $result[0]=$e->errorMessage();//Boring error messages from anything else
            }

            return $result;
        }
    }
