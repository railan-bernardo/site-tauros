<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\View;
use Source\Support\Email;
/**
 * Class Service
 * @package Source\Models
 */
class Contacts extends Model
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct("contact", ["id"], ["first_name", "email",  "phone", "subject", "msg"]);
    }

    /**
     * @param string $firstName
     * @param string $email
     * @param string $phone
     * @param string $subject
     * @param string|null $msg
     * @return Contacts
     */
    public function bootstrap(
        string $firstName,
        string $email,
        string $phone,
        string $subject,
        string $msg = null
    ): Contacts {
        $this->first_name = $firstName;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->msg = $msg;
        return $this;
    }


 /**
     * @param Contacts $contacts
     * @return bool
     */
    public function register(Contacts $contacts): bool
    {
        

        $view = new View(__DIR__ . "/../../shared/views/email/");
        $message = $view->render("mail", [
            "first_name" => $contacts->first_name,
            "email" => $contacts->email,
             "subject" => $contacts->subject,
              "phone" => $contacts->phone,
               "msg" => $contacts->msg
        ]);

        (new Email())->bootstrap(
            "Cadastro ao Cliente " . CONF_SITE_NAME,
            $message,
            $contacts->email,
            "{$contacts->first_name} "
        )->send();

        return true;
    }


    /**
     * @return bool
     */
    public function save(): bool
    {

 return parent::save();
    }
}