<?php
namespace ValidCert\Certificate;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Exception;

class CertificateQr
{
    protected $certificateHash;

    public function __construct($certificateHash)
    {
        $this->certificateHash = $certificateHash;
    }
    
    /**
     * Generate certificate QR
     * @throws \Exception
     */
    public function generateCertificateQr()
    {
        if ($this->certificateHash != '')
        {
            $certificateHash = $this->certificateHash;
            $verificationUrl = DEF_FULL_ROOT_PATH."/verify?hash={$certificateHash}";

            //configure QR options
            $options = new QROptions([
                'version' => 5,
                'outputType' => QROutputInterface::GDIMAGE_PNG,
                'eccLevel' => EccLevel::L, //error correction level
                'scale' => 6,
            ]);

            //generate the QR code
            //header('Content-Type: image/png');
            return (new QRCode($options))->render($verificationUrl);
        }

        //if execution gets here, the hash is not retrieved
        throw new Exception('No hash found for this record!');
    }
}