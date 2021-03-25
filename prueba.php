<?php


require_once (dirname(dirname(dirname(__FILE__)))."/config.php"); //search for config.php
require_once ($CFG->dirroot."/local/rsocial/forms.php"); //search the file where we have our forms 



echo "
    <Form action='prueba.php' method = post>
        Boton = <button type = submit ;class= 'btn btn-primary'; value = 'esta funcionando'; name = hola > WENA CABROS</button><br><br>
        </form>" ;

echo  $_POST["hola"];