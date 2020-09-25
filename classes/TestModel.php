<?php


class TestModel extends BaseModel {
   
    public function sampleFun() {
        
        $company_master = $this->dbr->query('SELECT * FROM company_master')->fetchAll();
        foreach ($company_master as $account) {
            echo $account['name'] . '<br>';
        }
       
    }

    public function sampleFunCount($args) {
        try{
            $company_master = $this->dbr->query('SELECT * FROM company_master1');
            $count = ['cnt'=>$company_master->numRows(),'args'=>$args];
            $this->responseOk($count);
        }
        catch (Exception $e) {
            $this->responseErr($e->getMessage());
        }
    }

    public function appDetails() {
        $this->responseOk(['app'=>'slate','v'=>0.01]);
    } 
}