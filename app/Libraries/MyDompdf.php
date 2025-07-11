<?php

namespace App\Libraries;

require_once APPPATH . 'Libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class MyDompdf
{
    protected $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    public function generate($html, $filename = 'file.pdf')
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A6', 'portrait');
        $this->dompdf->render();
        $this->dompdf->stream($filename, ["Attachment" => true]);
    }
}
