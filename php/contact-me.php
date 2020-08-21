<?php
if($_POST) {

    $to_Email = 'danisluz@gmail.com'; // Write your email here to receive the form submissions
    $subject = 'Nova mensagem do Site Daniel'; // Write the subject you'll see in your inbox

    $name = $_POST["userName"];
    $email = $_POST["userEmail"];
    $reason = $_POST["userSubject"];
    $message = $_POST["userMessage"];
   
    // Use PHP To Detect An Ajax Request
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
   
        // Exit script for the JSON data
        $output = json_encode(
        array(
            'type'=> 'error',
            'text' => 'Request must come from Ajax'
        ));
       
        die($output);
    }
   
    // Checking if the $_POST vars well provided, Exit if there is one missing
    if(!isset($_POST["userChecking"]) || !isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userSubject"]) || !isset($_POST["userMessage"])) {
        
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Os campos de entrada estão vazios!'));
        die($output);
    }

    // Anti-spam field, if the field is not empty, submission will be not proceeded. Let the spammers think that they got their message sent with a Thanks ;-)
    if(!empty($_POST["userChecking"])) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-checkmark-round"></i> Obrigado por se inscrever'));
        die($output);
    }
   
    // PHP validation for the fields required
    if(empty($_POST["userName"])) {
        $output = json_encode(array('type'=>'error', 'text' => '<span><i class="icon ion-close-round"></i></span>Erro no 1º campo:<br>Nome muito curto ou não especificado'));
        die($output);
    }
    
    if(!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
        $output = json_encode(array('type'=>'error', 'text' => '<span><i class="icon ion-close-round"></i></span>Erro no 2º campo:<br>Por favor insira um endereço de e-mail válido.'));
        die($output);
    }

    if(empty($_POST["userSubject"])) {
        $output = json_encode(array('type'=>'error', 'text' => '<span><i class="icon ion-close-round"></i></span>Erro no 3º campo:<br>Selecione o motivo da sua mensagem.'));
        die($output);
    }

    // Avoid too small message by changing the value of the minimum characters required. Here it's <20
    if(strlen($_POST["userMessage"])<20) {
        $output = json_encode(array('type'=>'error', 'text' => '<span><i class="icon ion-close-round"></i></span>Erro no 4º campo:<br>Mensagem muito curta! Tome seu tempo e escreva algumas palavras.'));
        die($output);
    }
   
    // Proceed with PHP email
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
    $headers .= 'De: Site Daniel Luz <contato@danieluz.com>' . "\r\n";
    $headers .= 'Responder a: '.$_POST["userEmail"]."\r\n";
    
    'X-Mailer: PHP/' . phpversion();
    
    // Body of the Email received in your Inbox
    $emailcontent = "
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
    </head>
    <body style='font-family:Verdana;background:#f2f2f2;color:#606060;'>

        <style>
            h3 {
                font-weight: normal;
                color: #999999;
                margin-bottom: 0;
                font-size: 14px;
            }
            a , h2 {
                color: #6534ff;
            }
            p {
                margin-top: 5px;
                line-height:1.5;
                font-size: 14px;
            }
        </style>

        <table cellpadding='0' width='100%' cellspacing='0' border='0'>
            <tr>
                <td>
                    <table cellpadding='0' cellspacing='0' border='0' align='center' width='100%' style='border-collapse:collapse;'>
                        <tr>
                            <td>

                                <div>
                                    <table cellpadding='0' cellspacing='0' border='0' align='center'  style='width: 100%;max-width:600px;background:#FFFFFF;margin:0 auto;border-radius:5px;padding:50px 30px'>
                                        <tr>
                                            <td width='100%' colspan='3' align='left' style='padding-bottom:0;'>
                                                <div>
                                                    <h2>Nova mensagem</h2>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width='100%' align='left' style='padding-bottom:30px;'>
                                                <div>
                                                    <p>Olá, acaba de receber uma nova mensagem através do formulário de contato do seu site.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width='100%' align='left' style='padding-bottom:20px;'>
                                                <div>
                                                    <h3>De</h3>
                                                    <p>$name</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width='100%' align='left' style='padding-bottom:20px;'>
                                                <div>
                                                    <h3>Email</h3>
                                                    <p>$email</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width='100%' align='left' style='padding-bottom:20px;'>
                                                <div>
                                                    <h3>Assunto</h3>
                                                    <p>$reason</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width='100%' align='left' style='padding-bottom:20px;'>
                                                <div>
                                                    <h3>Mensagem</h3>
                                                    <p>$message</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div style='margin-top:30px;text-align:center;color:#b3b3b3'>
                                    <p style='font-size:12px;'>1980-2020 Daniel Luz®, All Rights Reserved.</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>";
    
    $Mailsending = @mail($to_Email, $subject, $emailcontent, $headers);
   
    if(!$Mailsending) {
        
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => '<span><i class="icon ion-close-round"></i></span>Oops! Parece que algo deu errado <br> Verifique sua configuração de e-mail do PHP.'));
        die($output);
        
    } else {
        $output = json_encode(array('type'=>'message', 'text' => '<span><i class="icon ion-checkmark-round"></i></span><strong>Olá '.$_POST["userName"] .'!</strong><br>Sua mensagem foi enviada, entraremos em contato com você o mais rápido possível!'));
        die($output);
    }
}
?>