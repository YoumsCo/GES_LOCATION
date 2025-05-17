<?php
namespace Controllers\Admin;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

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
        $dotenv = Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();
        
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV["SMTP_HOST"];
            $this->mail->SMTPAuth = $_ENV["SMTP_SMTP_AUTH"];
            $this->mail->Username = $_ENV["SMTP_USER_NAME"];
            $this->mail->Password = $_ENV["SMTP_PASSWORD"];
            $this->mail->SMTPSecure = $_ENV["SMTP_SMTP_SECURE"];
            $this->mail->Port = $_ENV["SMTP_PORT"];
            $this->mail->CharSet = $_ENV["SMTP_CHARSET"];

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