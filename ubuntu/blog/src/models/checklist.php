<?php

class Checklist {
    var $id = null;
    var $name = '';
    var $created = '';



    /** Get All Data */

    function getData(){
       return $this;
    }

    /** Getter and Setters */

    function set($key, $value) {
        $this->$key = $value;
    }

    function get($key) {
        return $this->$key;
    }
}

?>
