<?php


class PromotionBudgetDetailsModel extends BaseModel {
   
    public function doInsert() {
        try{
            $request = $this->post();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ValidityFrom                   = isset($request->ValidityFrom)?$request->ValidityFrom:null;
                $ValidityFromParts              = explode('-', $ValidityFrom);
                $Pmonth                         = $ValidityFromParts[1];
                $Pyear                          = $ValidityFromParts[0];
                $ProfitCenter                   = isset($request->ProfitCenter)?$request->ProfitCenter:null;
                $CustomerGroup                  = 'GT';
                $BudgetType                     = 0;//'Generic';
                $OnSaleOffValue                 = isset($request->OnSaleOffValue)?$request->OnSaleOffValue:null;
                $Description                    = isset($request->Description)?$request->Description:null;

                $Limit                          = isset($request->Limit)?$request->Limit:null;
                
                $ValidityTo                     = isset($request->ValidityTo)?$request->ValidityTo:null;
                $SaleUOM                        = isset($request->SaleUOM)?$request->SaleUOM:null;
                $OnSaleOff                      = isset($request->OnSaleOff)?$request->OnSaleOff:null;
                $Brand                          = isset($request->Brand)?$request->Brand:null;
                $Remarks                        = isset($request->Remarks)?$request->Remarks:null;
                $FutureChilds                   = 1;

                $TSIID                          = isset($request->TSIID)?$request->TSIID:null;
                $AllChannel         = 0;
                $QPS                = 0;
                $KAT                = 0;

                $channelname                    = isset($request->channelname)?$request->channelname:null;
                $PromotionDetails               = isset($request->PromotionDetail)?$request->PromotionDetail:null;
                $applawcodes                    = isset($request->Promotionaws)?$request->Promotionaws:null;


                if(IsNullOrEmptyString($Pmonth) || 
                    IsNullOrEmptyString($Pyear) || 
                    IsNullOrEmptyString($OnSaleOffValue) || 
                    IsNullOrEmptyString($Limit) || 
                    IsNullOrEmptyString($ValidityFrom) || 
                    IsNullOrEmptyString($ValidityTo) || 
                    IsNullOrEmptyString($SaleUOM) || 
                    IsNullOrEmptyString($OnSaleOff) || 
                    IsNullOrEmptyString($Remarks) ||
                    IsNullOrEmptyString($TSIID) ||
                    IsNullOrEmptyString($channelname) 
                ){
                    $res = "OnSaleOffValue, Limit, ValidityFrom, ValidityTo, SaleUOM, OnSaleOff, Remarks,TSIID, channelname, PromotionDetail, applawcode fields are required";
                    $this->responseMis($res);
                }else{

                    # insert into PromotionBudgetDetails
                    $params1 = array(
                        $Pmonth, 
                        $Pyear, 
                        $OnSaleOffValue, 
                        $Limit, 
                        $ValidityFrom, 
                        $ValidityTo, 
                        $SaleUOM, 
                        $OnSaleOff, 
                        $Remarks, 
                        $TSIID,

                        $ProfitCenter,
                        $CustomerGroup,
                        $BudgetType,
                        $Description,
                        $Brand,
                        $FutureChilds,
                        $AllChannel,
                        $QPS,
                        $KAT
                    );
                    $tsql1 = "INSERT PromotionBudgetDetails (Pmonth, Pyear, OnSaleOffValue, Limit, ValidityFrom,ValidityTo,SaleUOM,Onsaleoff,Remarks, TSIID, ProfitCenter,CustomerGroup,BudgetType,Description,Brand,FutureChilds,AllChannel,QPS,KAT) OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $promotions = $this->dbw->query($tsql1, $params1);
                    $budgetId = $promotions->resultArrayList;

                    # insert into PromotionChannels
                    $params2 = array($budgetId[0]['RowID'], $channelname);
                    $tsql2 = "INSERT PromotionChannels (BudgetPromotionId, channelname) OUTPUT INSERTED.RowID VALUES (?,?)";
                    $promotionsChannel = $this->dbw->query($tsql2, $params2);
                    $channelId = $promotionsChannel->resultArrayList;

                    #insert into PROMOTIONDETAIL
                    $detailsIds = [];
                    foreach($PromotionDetails as $PromotionDetail){
                        $params3 = array(
                                $budgetId[0]['RowID'],
                                isset($PromotionDetail->SlabQtyTo)?$PromotionDetail->SlabQtyTo:null,
                                isset($PromotionDetail->PromotionType)?$PromotionDetail->PromotionType:null,
                                isset($PromotionDetail->SalePkt)?$PromotionDetail->SalePkt:null,
                                isset($PromotionDetail->FreePkt)?$PromotionDetail->FreePkt:null,
                                isset($PromotionDetail->FreeSku)?$PromotionDetail->FreeSku:null,
                                isset($PromotionDetail->Slab)?$PromotionDetail->Slab:null,
                                isset($PromotionDetail->FreeUom)?$PromotionDetail->FreeUom:null,
                                isset($PromotionDetail->ReimbPrice)?$PromotionDetail->ReimbPrice:null
                            );
                        $tsql3 = "INSERT PromotionDetail (BudgetPromotionId, SlabQtyTo, PromotionType, SalePkt, FreePkt, FreeSku, Slab, FreeUom, ReimbPrice)  OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?,?)";
                        $res3 = $this->dbw->query($tsql3, $params3);
                        $detailsId = $res3->resultArrayList;
                        $detailsIds[] = $detailsId[0]['RowID'];
                    }
                    #insert into Promotionaws
                    $applawcodeIds = [];
                    foreach($applawcodes as $applawcode){
                        $params4 = array(
                            $budgetId[0]['RowID'],
                            isset($applawcode->applawcode)?$applawcode->applawcode:null,
                            isset($applawcode->Amt)?$applawcode->Amt:null
                        );
                        $tsql4 = "INSERT Promotionaws (BudgetPromotionId, applawcode, Amt) OUTPUT INSERTED.RowID VALUES (?,?,?)";
                        $aws = $this->dbw->query($tsql4, $params4);
                        $awslId = $aws->resultArrayList;
                        $applawcodeIds[] = $awslId[0]['RowID'];
                    }

                    $res = ['data'=>['budget'=>$budgetId[0]['RowID'], 'channel'=>$channelId[0]['RowID'], 'details'=>$detailsIds, 'awslId' => $applawcodeIds ]];
                    $this->responseOk($res);
                }
                
                
            }
        }
        catch (Exception $e) {
            $this->responseErr($e->getMessage());
        }
    }

    public function readAll($args) {
        try{
            $OFFSET     =  intval(IsNullOrEmptyString($args[0]) ? 0:$args[0]);
            $ROWS       =  intval(IsNullOrEmptyString($args[1])?100:$args[1]);
            $TSIID      =  urldecode(IsNullOrEmptyString(isset($args[2])?$args[2] : null)?'%':$args[2]);
            $CURMONTH   =  intval(IsNullOrEmptyString($args[3]) ? null:$args[3]);
            #get PromotionBudgetDetails
            $where = " and TSIID LIKE '".$TSIID."' ";
            if($CURMONTH != null && $CURMONTH == 1){
                $where .= " and PMonth = MONTH(getdate()) and PYear = YEAR(getdate()) ";
            }else if($CURMONTH != null && $CURMONTH == 2){
                $where .= " and PMonth != MONTH(getdate()) and PYear != YEAR(getdate()) ";
            }
            
            $sql = "select RowID, PMonth, PYear, OnSaleOffValue, Limit, ValidityFrom, ValidityTo, SaleUOM, OnSaleOff, Remarks, TSIID, ProfitCenter,CustomerGroup,BudgetType,Description,Brand,FutureChilds,AllChannel,QPS,KAT from PromotionBudgetDetails where 1=1 $where order by ValidityFrom DESC OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";

            $promotionArrayList = $this->dbr->query($sql,[$OFFSET, $ROWS]);
            $promotion = $promotionArrayList->resultArrayList;
            #get PROMOTIONDETAIL
            foreach($promotion as &$obj){
                $rowID = $obj['RowID'];
                $promotionDetailsArrayList = $this->dbr->query('select BudgetPromotionId, SlabQtyTo, PromotionType, SalePkt, FreePkt, FreeSku, Slab, FreeUom, ReimbPrice from PromotionDetail where BudgetPromotionId = ?',[$rowID]);
                $PromotionDetail = $promotionDetailsArrayList->resultArrayList;
                $obj['PromotionDetail'] = $PromotionDetail;

                #get Promotionaws
                $applawcodesArrayList = $this->dbr->query('select BudgetPromotionId, applawcode from Promotionaws where BudgetPromotionId = ?',[$rowID]);
                $Promotionaws = $applawcodesArrayList->resultArrayList;
                $obj['Promotionaws'] = $Promotionaws;

                # get PromotionChannels
                $PromotionChannelArrayList = $this->dbr->query('select BudgetPromotionId, channelname from PromotionChannels where BudgetPromotionId = ?',[$rowID]);
                $PromotionChannel = $PromotionChannelArrayList->resultArrayList;
                $channel = null;
                if(isset($PromotionChannel[0]['channelname'])){
                    $channel = $PromotionChannel[0]['channelname'];
                }
                $obj['channelname'] = $channel;
            }

            $res = ['data'=>$promotion];
            $this->responseOk($res);
        }
        catch (Exception $e) {
            $this->responseErr($e->getMessage());
        }
    }

    public function readById($args) {
        try{
            $rowID =  sanitize($args[0]);
            #get PromotionBudgetDetails
            $promotionArrayList = $this->dbr->query('select PMonth, PYear, OnSaleOffValue, Limit, ValidityFrom, ValidityTo, SaleUOM, OnSaleOff, Remarks, TSIID, ProfitCenter,CustomerGroup,BudgetType,Description,Brand,FutureChilds,AllChannel,QPS,KAT from PromotionBudgetDetails where RowID = ?',[$rowID]);
            $promotion = $promotionArrayList->resultArrayList;
            #get PROMOTIONDETAIL
            foreach($promotion as &$obj){
                $promotionDetailsArrayList = $this->dbr->query('select BudgetPromotionId, SlabQtyTo, PromotionType, SalePkt, FreePkt, FreeSku, Slab, FreeUom, ReimbPrice from PromotionDetail where BudgetPromotionId = ?',[$rowID]);
                $PromotionDetail = $promotionDetailsArrayList->resultArrayList;
                $obj['PromotionDetail'] = $PromotionDetail;
            
                #get Promotionaws
            
                $applawcodesArrayList = $this->dbr->query('select BudgetPromotionId, applawcode from Promotionaws where BudgetPromotionId = ?',[$rowID]);
                $Promotionaws = $applawcodesArrayList->resultArrayList;
                $obj['Promotionaws'] = $Promotionaws;

                # get PromotionChannels
                $PromotionChannelArrayList = $this->dbr->query('select BudgetPromotionId, channelname from PromotionChannels where BudgetPromotionId = ?',[$rowID]);
                $PromotionChannel = $PromotionChannelArrayList->resultArrayList;
                $channel = null;
                if(isset($PromotionChannel[0]['channelname'])){
                    $channel = $PromotionChannel[0]['channelname'];
                }
                $obj['channelname'] = $channel;
            }

            $res = ['data'=>$promotion, 'args'=>$args];
            $this->responseOk($res);
        }
        catch (Exception $e) {
            $this->responseErr($e->getMessage());
        }
    }

    public function appDetails() {
        $this->responseOk(['app'=>'britannia','v'=>0.01]);
    } 
}