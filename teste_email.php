<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once ('/home/trailservicos/public_html/painel_trail/inc/class.phpmailer.php');
                include_once('/home/trailservicos/public_html/painel_trail/inc/simple_html_dom.php');
                
                
                $img = "<a href=\"#\"><img src=\"/home/trailservicos/public_html/painel_trail/email/logo_empresa.png\" width=\"147\" height=\"46\" alt=\"painel_trail\" /></a>";
                
                $email = 'contato@trailservicos.com.br';
                $name = 'ADM - REALIZA SAUDE';
                $nome = 'rafael';
                $cpf = '055.';
                $message = file_get_contents('/home/trailservicos/public_html/painel_trail/email/email_ativar_cliente.html');
                $message = str_replace('%imagem%', $img, $message);
                $message = str_replace('%nome%', $nome, $message);
                $message = str_replace('%cpf%', $cpf, $message);
                
                
                $mail = new PHPMailer(true);
                $mail->CharSet = "UTF-8";
                $mail->SMTPSecure = "ssl";
                $mail->IsSMTP();
                $mail->Host = "smtp.zoho.com";
                $mail->Port = "465";
                $mail->SMTPAuth = true;
                $mail->Username = "contato@trailservicos.com.br";
                $mail->Password = "senhatrailservicos";
                $mail->IsHTML(true);
                
                $mail->AddAddress($email, $name);
                $mail->AddAddress('rafaelnogueira@trailservicos.com.br', 'Rafael Nogueira');
                
                $mail->AddReplyTo('contato@trailservicos.com.br', 'ADM - REALIZA SAUDE');
                
                $mail->SetFrom = 'contato@trailservicos.com.br'; 
                $mail->FromName = 'REALIZA SAUDE'; 
                $mail->From = 'contato@trailservicos.com.br';
                $mail->Subject = $cpf.' - '.$nome.' (REALIZA SAUDE)';
                
                
                $mail->MsgHTML($message);
                                
                $mail->AltBody = strip_tags($message);

                if($mail->Send()) 
                {
                    
                }
                

?>