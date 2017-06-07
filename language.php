<?php

class language {

   public $data;

   function __construct($language) {
      $data = file_get_contents($language . ".json");
      $this->data = json_decode($data);
   }

   function translate() {
        return $this->data;
   }
}

?>
