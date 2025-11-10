<?php
namespace ValidCert\Certificate;

class CertificateHash
{
    protected $params = [];
    protected $data = [];

    public function __construct($params)
    {
        $this->params = $params;
        $this->data = $this->params['data'];
    }

    /**
     * Compute certificate hash
     * @return string
     */
    public function computeCertificateHash()
    {
        //compute certifcate metadata hash
        $jsonMetadata = json_encode(
            $this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        //hash the JSON
        $certificateHash = hash('sha256', $jsonMetadata . $_ENV['CERTIFCATE_HASH_SALT']);

        return $certificateHash;
    }

    /**
     * Verify certificate hash
     * @return bool
     */
    public function verifyCertificateHash()
    {
        //recompute hash and verify with the passed one
        $computedHash = $this->computeCertificateHash();

        return hash_equals($this->params['certificateHash'], $computedHash);
    }
}