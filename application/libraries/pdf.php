<?php if(! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once dirname(__FILE__) . '/TCPDF-main/tcpdf_import.php';
require_once dirname(__FILE__) . '/TCPDF-main/examples/lang/urd.php';
// require_once('TCPDF-main/tcpdf_import.php');

class pdf extends TCPDF
{

    function __construct()
    {
        parent :: __construct();
        
    }
}







?>