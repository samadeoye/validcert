<?php
namespace ValidCert\Admin\EducationLevel;

use Exception;
use ValidCert\Crud\Crud;

class EducationLevel
{
    private $table = DEF_TBL_EDUCATION_LEVELS;
    protected $requestData = [];
    protected $action;
    public $dataJson = [];

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->action = $this->requestData['action'];
    }

    /**
     * Process action
     * @return void
     */
    public function processAction()
    {
        switch ($this->action)
        {
            case 'add':
                $this->addNewEducationLevel();
            break;

            case 'update':
                $this->updateEducationLevel();
            break;

            case 'delete':
                $this->deleteEducationLevel();
            break;
        }
    }

    /**
     * Validate user entry data
     * @param array $requestData
     * @throws \Exception
     * @return array{abbr: string, title: string}
     */
    private function validateEducationLevelData($requestData)
    {
        $abbr = stringToUpper(trim($requestData['abbr']));
        $title = stringToUpper(trim($requestData['title']));
        
        if (empty($abbr) || strlen($abbr) < 2)
        {
            throw new Exception('Please enter a valid abbreviation!');
        }
        elseif (empty($title) || strlen($title) < 3)
        {
            throw new Exception('Please enter a valid title!');
        }

        return [
            'abbr' => $abbr,
            'title' => $title
        ];
    }

    /**
     * Add a new education level
     * @return void
     */
    private function addNewEducationLevel()
    {
        //validate request data
        $data = $this->validateEducationLevelData($this->requestData);

        $data['id'] = getNewId();
        $data['cdate'] = getCurrentDate();

        Crud::insert(
            $this->table
            , $data
        );

        $this->dataJson['msg'] = 'Record added successfully';
    }
    
    /**
     * Update a certificate level
     * @return void
     */
    private function updateEducationLevel()
    {
        //validate request data
        $data = $this->validateEducationLevelData($this->requestData);

        $id = trim($this->requestData['id']);

        $data['mdate'] = getCurrentDate();
        
        Crud::update(
            $this->table
            , $data
            , ['id' => $id]
        );

        $this->dataJson['msg'] = 'Record updated successfully';
    }
    
    /**
     * Delete an education level
     * @return void
     */
    private function deleteEducationLevel()
    {
        $id = trim($this->requestData['id']);

        Crud::delete(
            $this->table
            , ['id' => $id]
        );

        $this->dataJson['msg'] = 'Record deleted successfully';
    }
}