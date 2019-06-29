<?php

class Product {
    var $id = null;
    var $list = '';
    var $name = '';
    var $quantity = 0;



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
