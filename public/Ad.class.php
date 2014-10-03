<?php
 
class Ad {
 
    public $title = '';
    public $body = '';
    public $contactName = '';
    public $contactEmail = '';
    public $createdAt = '';
 
    public function __construct($title, $body, $contactName, $contactEmail, $createdAt)
    {
        $this->title = $title;
        $this->body = $body;
        $this->contactName = $contactName;
        $this->contactEmail = $contactEmail;
        $this->createdAt = $createdAt;
    }
 
    public function toArray()
    {
        return [$this->title, $this->body, $this->contactName, $this->contactEmail, $this->createdAt];
    }
}
