<?php
namespace Controllers\Admin;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

class Mailer
{
    private PHPMailer $mail;
    private string $user_name;
    private string $user_email;
    private string $subject;
    private string $message;

    function __construct(string $name, string $email, string $subject, string $message)
    {
        $this->mail = new PHPMailer(true);
        $this->user_name = $name;
        $this->user_email = $email;
        $this->subject = $subject;
        $this->message = $message;

    }

    function sendMail(): bool
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = "smtp.gmail.com";
            $this->mail->SMTPAuth = true;
            $this->mail->Username = "youmschoco@gmail.com";
            $this->mail->Password = "maib mgri ujqd rktr";
            $this->mail->SMTPSecure = "tls";
            $this->mail->Port = 587;
            $this->mail->CharSet = "utf-8";

            $this->mail->setFrom("youmschoco@gmail.com", "GEST_LOCATION");
            $this->mail->addAddress($this->user_email, $this->user_name);
            $this->mail->isHTML(true);

            $this->mail->Subject = $this->subject;
            $this->mail->Body = $this->message;

            $this->mail->SMTPDebug = 0;

            $this->mail->send();

            return true;

        } catch (Exception $e) {
            echo "Erreur : {$e}";
            echo "Le message n'a pas pu etre envoyé {$this->mail->ErrorInfo}";
            return false;
        }

    }
}

header("../Views/user/index.php");