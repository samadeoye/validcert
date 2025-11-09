<?php
namespace ValidCert\Issuer\Certificate;

use Exception;
use ValidCert\Crud\Crud;
use ValidCert\EducationLevel\EducationLevelFunctions;
use ValidCert\History\History;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ValidCert\Certificate\CertificateHash;

class Certificate
{
    private $table = DEF_TBL_CERTIFICATES;
    protected $historyCategory = 'certificates';
    protected $requestData = [];
    protected $action;
    public $dataJson = [];

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->action = $this->requestData['action'];
    }

    public function processAction()
    {
        switch ($this->action)
        {
            case 'add':
                $this->addNewCertificate();
            break;

            case 'update':
                $this->updateCertificate();
            break;

            case 'delete':
                $this->deleteCertificate();
            break;

            case 'import':
                $this->importCertificates();
            break;

            case 'confirmimport':
                $this->confirmCertificatesImport();
            break;
        }
    }

    /**
     * Validate certificate data entered by user
     * @param array $requestData
     * @throws \Exception
     * @return array{certificateId: string, holderFirstName: string, holderLastName: string, issueDate: string, issuerId: mixed, program: string}
     */
    private function validateCertificateData($requestData)
    {
        $certificateId = trim($requestData['certificateId']);
        $holderFirstName = stringToUpper(trim($requestData['holderFirstName']));
        $holderLastName = stringToUpper(trim($requestData['holderLastName']));
        $program = stringToUpper(trim($requestData['program']));
        $issueDate = trim($requestData['issueDate']);
        $levelId = trim($requestData['levelId']);
        $issuerId = $requestData['userId'];

        //convert issue date to appropriate PHP date format due to excel formatting
        if (!empty($issueDate))
        {
            $issueDate = date('Y-m-d', strtotime($issueDate));
        }
        
        if (empty($holderFirstName) || strlen($holderFirstName) < 3 || strlen($holderFirstName) > 100)
        {
            throw new Exception('Please enter a valid first name!');
        }
        elseif (empty($holderLastName) || strlen($holderLastName) < 3 || strlen($holderLastName) > 100)
        {
            throw new Exception('Please enter a valid first name!');
        }
        elseif (empty($program) || strlen($program) < 3)
        {
            throw new Exception('Please enter a valid program!');
        }
        elseif (empty($issueDate) || strlen($issueDate) != 10)
        {
            throw new Exception('Please enter a valid issue date!');
        }
        elseif (strtotime($issueDate) > strtotime(date('Y-m-d')))
        {
            throw new Exception('Issue date cannot be after the current date!');
        }
        elseif (empty($levelId) || strlen($levelId) != 36)
        {
            throw new Exception('Please enter a valid level!');
        }
        else
        {
            //check for duplicate
            $rs = Crud::getRecordInfoWithCondition(
                $this->table
                , ['id']
                , [
                    'certificateId' => $certificateId
                    , 'holderFirstName' => $holderFirstName
                    , 'holderLastName' => $holderLastName
                    , 'program' => $program
                    , 'issueDate' => $issueDate
                    , 'levelId' => $levelId
                ]
            );
            if ($rs)
            {
                $id = isset($this->requestData['id']) ? trim($this->requestData['id']) : '';
                if ($id != $rs['id'])
                {
                    $certId = '';
                    if (!empty($certificateId))
                    {
                        $certId = $certificateId;
                    }
                    else
                    {
                        $certId = "for {$holderFirstName} {$holderLastName}";
                    }
                    throw new Exception("Duplicate entry found for the certificate - {$certId}");
                }
            }
        }

        return [
            'certificateId' => $certificateId
            , 'issuerId' => $issuerId
            , 'holderFirstName' => $holderFirstName
            , 'holderLastName' => $holderLastName
            , 'program' => $program
            , 'issueDate' => $issueDate
        ];
    }

    private function computeCertificateHash($data)
    {
        //compute certifcate metadata hash
        $obj = new CertificateHash([
            'data' => $data
        ]);
        return $obj->computeCertificateHash();
    }

    /**
     * Add a new certificate
     * @return void
     */
    private function addNewCertificate()
    {
        //validate certificate data
        $data = $this->validateCertificateData($this->requestData);

        $id = getNewId();

        //update logs
        History::updateHistoryLogs(
            $this->table
            , $id
            , $data
            , $this->historyCategory
            , 'new'
        );

        $data['certificateHash'] = $this->computeCertificateHash($data);
        $data['levelId'] = $this->requestData['levelId'];

        $data['id'] = $id;
        $data['holderFullName'] = "{$data['holderFirstName']} {$data['holderLastName']}";
        $data['action'] = 'manual';
        $data['cdate'] = getCurrentDate();

        Crud::insert(
            $this->table
            , $data
        );

        $this->dataJson['msg'] = 'Record added successfully';
    }
    
    /**
     * Update a certificate
     * @throws \Exception
     * @return void
     */
    private function updateCertificate()
    {
        //validate certificate data
        $data = $this->validateCertificateData($this->requestData);

        $id = trim($this->requestData['id']);

        //check if there are changes and update logs
        History::updateHistoryLogs(
            $this->table
            , $id
            , $data
            , $this->historyCategory
        );
        if (History::$updateCount > 0)
        {
            $data['certificateHash'] = $this->computeCertificateHash($data);
            $data['levelId'] = $this->requestData['levelId'];
            $data['holderFullName'] = "{$data['holderFirstName']} {$data['holderLastName']}";
            $data['mdate'] = getCurrentDate();
            
            Crud::update(
                $this->table
                , $data
                , ['id' => $id]
            );

            $this->dataJson['msg'] = 'Record updated successfully';
        }
        else
        {
            throw new Exception('No changes found!');
        }
    }
    
    /**
     * Delete a certificate
     * @return void
     */
    private function deleteCertificate()
    {
        $id = trim($this->requestData['id']);

        //update logs
        History::updateHistoryLogs(
            $this->table
            , $id
            , []
            , $this->historyCategory
            , 'delete'
        );

        Crud::delete(
            $this->table
            , ['id' => $id]
        );

        $this->dataJson['msg'] = 'Record deleted successfully';
    }

    /**
     * Import certificates (multiple)
     * @throws \Exception
     * @return void
     */
    private function importCertificates()
    {
        $fileName = $this->requestData['arFile']['certificatesFile']['name'];
        $fileTmp = $this->requestData['arFile']['certificatesFile']['tmp_name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array($fileExt, ['csv', 'xls', 'xlsx']))
        {
            throw new Exception('Invalid file type. Only CSV, XLS, or XLSX allowed.');
        }

        $data = [];
        if ($fileExt == 'csv')
        {
            //CSV
            if (($handle = fopen($fileTmp, "r")) !== FALSE)
            {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE)
                {
                    $data[] = $row;
                }
                fclose($handle);
            }
        }
        else
        {
            //excel
            $spreadsheet = IOFactory::load($fileTmp);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
        }

        //Validate headers
        $expectedHeaders = ['Certificate ID', 'Holder\'s First Name', 'Holder\'s Last Name', 'Program', 'Issue Date', 'Level'];
        $headers = array_map('trim', $data[0]);

        if ($headers !== $expectedHeaders)
        {
            throw new Exception('Invalid file format. Expected header columns: ' . implode(', ', $expectedHeaders));
        }

        $tableBody = '';
        $numRows = count($data);
        if ($numRows < 2)
        {
            /*
            Only header row is present in the imported file.
            The file has to contain at least two rows: the header and a min. of one data row
            */
            throw new Exception('No data imported!');
        }

        $importedData = []; //data to import without headers.
        for ($i=1; $i<$numRows; $i++)
        {
            $row = $data[$i];
            $importedData[] = $row;

            $tableBody .= <<<EOQ
            <tr>
EOQ;
            foreach ($row as $cell)
            {
                $cell = htmlspecialchars($cell);

                $tableBody .= <<<EOQ
                <td>{$cell}</td>
EOQ;
            }
            $tableBody .= <<<EOQ
            </tr>
EOQ;
        }
        //store imported data in session
        $importId = getNewId();
        $_SESSION[$importId] = $importedData;

        $this->dataJson['imported'] = <<<EOQ
        <h6 class="mt-4"> Imported Data Preview</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Certificate ID</th>
                        <th>Holder's First Name</th>
                        <th>Holder's Last Name</th>
                        <th>Program</th>
                        <th>Issue Date</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    {$tableBody}
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-success" onclick="confirmImport('{$importId}');"><i class="fas fa-check-double"></i> Confirm Import</button>
EOQ;

    }

    /**
     * Confirm import of certificates (push data to table)
     * @throws \Exception
     * @return void
     */
    private function confirmCertificatesImport()
    {
        $importId = isset($this->requestData['importId']) ? $this->requestData['importId'] : '';

        if (empty($importId) || strlen($importId) != 36)
        {
            throw new Exception('Invalid process!');
        }
        elseif (!isset($_SESSION[$importId]))
        {
            throw new Exception('Invalid process!');
        }
        else
        {
            //process import
            $data = $_SESSION[$importId];
            if (count($data) > 0)
            {
                foreach ($data as $row)
                {
                    /*
                    Import Order:
                    certificateId, holderFirstName, holderLastName, program, issueDate, levelAbbr
                    */
                    $certificateId = isset($row[0]) ? $row[0] : ''; //optional
                    $holderFirstName = isset($row[1]) ? $row[1] : ''; //compulsory
                    $holderLastName = isset($row[2]) ? $row[2] : ''; //compulsory
                    $program = isset($row[3]) ? $row[3] : ''; //compulsory
                    $issueDate = isset($row[4]) ? $row[4] : ''; //compulsory
                    $levelAbbr = isset($row[5]) ? $row[5] : ''; //compulsory

                    //get level id by abbr.
                    $levelId = EducationLevelFunctions::getEducationLevelIdByAbbr($levelAbbr);
                    if (empty($levelId) || strlen($levelId) != 36)
                    {
                        throw new Exception('Invalid level type. Please check the levels guide.');
                    }

                    $dataInsert = $this->validateCertificateData([
                        'certificateId' => $certificateId
                        , 'issuerId' => $this->requestData['userId']
                        , 'holderFirstName' => $holderFirstName
                        , 'holderLastName' => $holderLastName
                        , 'program' => $program
                        , 'issueDate' => $issueDate
                        , 'levelId' => $levelId //will not be returned in the data
                        , 'userId' => $this->requestData['userId'] //will not be returned in the data
                    ]);

                    $id = getNewId();
                    //update logs
                    History::updateHistoryLogs(
                        $this->table
                        , $id
                        , $dataInsert
                        , $this->historyCategory
                        , 'new'
                    );

                    $dataInsert['certificateHash'] = $this->computeCertificateHash($dataInsert);

                    $dataInsert['levelId'] = $levelId;
                    $dataInsert['id'] = $id;
                    $dataInsert['holderFullName'] = "{$dataInsert['holderFirstName']} {$dataInsert['holderLastName']}";
                    $dataInsert['action'] = 'import';
                    $dataInsert['cdate'] = getCurrentDate();

                    Crud::insert(
                        $this->table
                        , $dataInsert
                    );

                    $this->dataJson['msg'] = 'Records imported successfully';
                }
            }
            else
            {
                throw new Exception('No imported data found!');
            }
        }
    }
}