<?php

namespace Shop\lookbook\Model; 

class Uploadedfileform {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {						// C:\wamp\tmp\php7AA9.tmp
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name']; //top-with-dainty-straps-caramel-gv613331-s6-produit-110x145.jpg
    }
    function getSize() {
        return (int)$_FILES['qqfile']['size']; //ex: 4477
    }
}


