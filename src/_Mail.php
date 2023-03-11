<?php

namespace Junyang7\PhpCommon;

use PHPMailer\PHPMailer\PHPMailer;

class _Mail
{

    private $handler;

    public $host = "";
    public $port = "";
    public $encryption = "";
    public $username = "";
    public $password = "";
    public $from = ["address" => "", "name" => "",];
    public $to = [];
    public $cc = [];
    public $attachment = [];
    public $subject = "";
    public $body = "";

    public function __construct()
    {

        $this->handler = new PHPMailer(true);

    }

    /**
     * 发送邮件
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send()
    {

        $this->handler->CharSet = PHPMailer::CHARSET_UTF8;
        $this->handler->isSMTP();
        $this->handler->isHTML();
        $this->handler->SMTPAuth = true;
        $this->handler->Host = $this->host;
        $this->handler->Username = $this->username;
        $this->handler->Password = $this->password;
        $this->handler->SMTPSecure = $this->encryption;
        $this->handler->Port = $this->port;
        $this->handler->setFrom($this->from["address"], $this->from["name"]);

        foreach ($this->to as $to) {
            $this->handler->addAddress($to["address"], $to["name"]);
        }

        foreach ($this->cc as $cc) {
            $this->handler->addCc($cc["address"], $cc["name"]);
        }

        foreach ($this->attachment as $attachment_path => $attachment_name) {
            $this->handler->addAttachment($attachment_path, $attachment_name);
        }

        $this->handler->Subject = $this->subject;
        $this->handler->Body = $this->body;

        if (false === $this->handler->send()) {
            throw new Exception($this->handler->ErrorInfo);
        }

    }

}
