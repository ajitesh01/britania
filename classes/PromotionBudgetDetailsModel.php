<?php


class PromotionBudgetDetailsModel extends BaseModel {

    public function testMail() {
        try{
            $to = ['sajal@xelpmoc.in', 'ajitesh.mandal@xelpmoc.in'];
            $this->sendSmtpMail("Test subject", "Test Body from <b>sajal</b>", $to);
            $this->responseOk(['mail'=>'send','to'=> $to]);
        }
        catch (Exception $e) {
            $this->responseErr($e->getMessage());
        }
    }  
   
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


                    $SaleUOMHash=["PKT"=>1,"AMT"=>2,"KG"=>3,"Lines"=>4,"CBB"=>5,"ECO"=>6,"Points"=>7];
                    $SaleUOMUpdate=$SaleUOMHash[$SaleUOM];
                    $objectiveValue=$Limit;
                    $SchemeType='E';
                    $SalesAccount="Trade Load";
                    $PromotionStatus='O';
                    $CreatedBy='1010101';
                    $customerValueSql="select DISTINCT ASM_code from viw_Customermaster where ASM_Name  LIKE '%".$TSIID."%' ";
                    $customerValue=$this->dbw->query($customerValueSql);
                    $customerValueResult=$customerValue->resultArrayList;
                    $CustomerValue=$customerValueResult[0]['ASM_code'];
                    $OnSaleOffType='4';
                    $Createddate=date('Y-m-d H:i:s');
                    $UpdatedDate=date('Y-m-d H:i:s');
                    $ObjectiveType=300901;
                    
                    $brandQuery="select Brand from VIW_CCProductMaster where Brand_Name like '%".$Brand."%' ";
                    //$brandQuery="select Brand from VIW_CCProductMaster where Brand_Name like '%Bourbon%'"
                    $brandQueryExec=$this->dbw->query($brandQuery);
                    $brandResult=$brandQueryExec->resultArrayList;

                    $Brand_Names="";
                    foreach($brandResult as $key=>$value){
                        $Brand_Names.=($value['Brand'].",");
                    }

                    $OnSaleOffValue=$Brand_Names;

                    # insert into PromotionBudgetDetails
                    $params1 = array(
                        $Pmonth, 
                        $Pyear, 
                        $OnSaleOffValue, 
                        $Limit, 
                        $ValidityFrom, 
                        $ValidityTo, 
                        $SaleUOMUpdate, 
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
                        $KAT,
                        $objectiveValue,
                        $SchemeType,
                        $SalesAccount,
                        $PromotionStatus,
                        $CreatedBy,
                        $CustomerValue,
                        $OnSaleOffType,
                        $Createddate,
                        $UpdatedDate ,
                        $ObjectiveType
                    );

                $tsql1 = "INSERT PromotionBudgetDetails (Pmonth, Pyear, OnSaleOffValue, Limit, ValidityFrom,ValidityTo,SaleUOM,Onsaleoff,Remarks, TSIID, ProfitCenter,CustomerGroup,BudgetType,Description,Brand,FutureChilds,AllChannel,QPS,KAT,ObjectiveValue,SchemeType,SalesAccount,PromotionStatus,CreatedBy,CustomerValue,OnSaleOffType,Createddate,UpdatedDate,ObjectiveType) OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $promotions = $this->dbw->query($tsql1, $params1);
                $budgetId = $promotions->resultArrayList;
                $promotionBudgetTableRowId=$budgetId[0]['RowID'];
                $promotionCode='CC'.$promotionBudgetTableRowId;
                $paramsPromo=[$promotionCode,$promotionBudgetTableRowId];
                $updatePromoCodeQuery="UPDATE PromotionBudgetDetails SET PromotionCode=? WHERE RowID=?";
                $updatePromotCodeExec=$this->dbw->query($updatePromoCodeQuery, $paramsPromo);
                $GT_Array=[118,119,120,121,122,123,124,125,126,127];
                $channelIds=[];
                $tsql2 = "INSERT PromotionChannels (BudgetPromotionId, channelname,channelcode,CreatedDate,UpdatedDate,CreatedBy,ChannelAttributes,promocode) OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?)";
                if($channelname=="GT"){
                    $ChannelAttributes=10055;
                    foreach($GT_Array as $value){
                        $params2 = array($budgetId[0]['RowID'], $channelname,$value,$Createddate,$UpdatedDate,$CreatedBy,$ChannelAttributes,$promotionCode);
                        $promotionsChannel = $this->dbw->query($tsql2, $params2);
                        $channelId = $promotionsChannel->resultArrayList;
                        $channelIds[]=$channelId[0]['RowID'];
                    }
                }
                else if($channelname=="Wholesale"){
                    $ChannelAttributes=10055;
                    $params2 = array($budgetId[0]['RowID'], $channelname,128,$Createddate,$UpdatedDate,$CreatedBy,$ChannelAttributes,$promotionCode);
                    $promotionsChannel = $this->dbw->query($tsql2, $params2);
                    $channelId = $promotionsChannel->resultArrayList;
                    $channelIds[]=$channelId[0]['RowID'];
                }
                else{
                    $ChannelAttributes=NULL;
                    $params2 = array($budgetId[0]['RowID'], $channelname,10085,$Createddate,$UpdatedDate,$CreatedBy,$ChannelAttributes,$promotionCode);
                    $promotionsChannel = $this->dbw->query($tsql2, $params2);
                    $channelId = $promotionsChannel->resultArrayList;
                    $channelIds[]=$channelId[0]['RowID'];
                }


                
                    //ObjectiveType
                $tsql2 = "INSERT PromotionChannels (BudgetPromotionId, channelname) OUTPUT INSERTED.RowID VALUES (?,?)";
                $promotionsChannel = $this->dbw->query($tsql2, $params2);
                $channelId = $promotionsChannel->resultArrayList;


         
               
                //$SlabQtyFrom=$PromotionDetail->SalePkt;

                    #insert into PROMOTIONDETAIL
                    $applawCodes="";
                    $detailsIds = [];
                    foreach($applawcodes as $applawcode){
                        $applawCodes.=($applawcode->applawcode.",");
                    }
                    
                    foreach($PromotionDetails as $key=>$PromotionDetail){
                        $PromotionTypeHash=['DISCOUNT %'=>1,'Discount Amount'=>2,'Free SKU'=>3,'Free Article'=>4,'Free SKU Group'=>5,'Scratch Card'=>6];
                        $PromotionTypeText=isset($PromotionDetail->PromotionType)?$PromotionDetail->PromotionType:null;
			$PromotionType=$PromotionTypeHash[$PromotionTypeText];
			$slabQtyTo=isset($PromotionDetails[($key+1)])?($PromotionDetails[($key+1)])->SlabQtyTo:999999999;
			
                        $params3 = array(
				$budgetId[0]['RowID'],
				$slabQtyTo,
                                $PromotionType,
                                isset($PromotionDetail->SalePkt)?$PromotionDetail->SalePkt:null,
                                isset($PromotionDetail->FreePkt)?$PromotionDetail->FreePkt:null,
                                isset($PromotionDetail->FreeSku)?$PromotionDetail->FreeSku:null,
                                isset($PromotionDetail->Slab)?$PromotionDetail->Slab:null,
                                isset($PromotionDetail->FreeUom)?$PromotionDetail->FreeUom:null,
                                isset($PromotionDetail->ReimbPrice)?$PromotionDetail->ReimbPrice:null,
                                isset($PromotionDetail->SalePkt)?$PromotionDetail->SalePkt:null,
                                $applawCodes
                            );
                        $tsql3 = "INSERT PromotionDetail (BudgetPromotionId, SlabQtyTo, PromotionType, SalePkt, FreePkt, FreeSku, Slab, FreeUom, ReimbPrice,SlabQtyFrom,Customerid)  OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                        $res3 = $this->dbw->query($tsql3, $params3);
                        $detailsId = $res3->resultArrayList;
                        $detailsIds[] = $detailsId[0]['RowID'];
                    }
                    $ASMCodeSQL=" select DISTINCT ASM_code from viw_Customermaster where ASM_Name  LIKE '%".$TSIID."%' ";
                    $TisCodeSqlQuery="select distinct TSI_code from Viw_customermaster where ASM_code in ($ASMCodeSQL)";
                    $TisCodeQuery=$this->dbw->query($TisCodeSqlQuery);
                    $TisCodeResultList=$TisCodeQuery->resultArrayList;
                    $TISRESULT="";
                    foreach($TisCodeResultList as $key=>$value){
                        $TISRESULT.=($value['TSI_code'].",");
                    }

                    $SomQuery="select distinct  SOM_Code from Viw_customermaster where ASM_code in ($ASMCodeSQL)";
                    $SomQuery=$this->dbw->query($SomQuery);
                    $SomQueryList=$SomQuery->resultArrayList;
                    $SomResult="";
                    foreach($SomQueryList as $key=>$value){
                        $SomResult.=($value['SOM_Code'].",");
                    }

                $RSM_Query="select distinct  Region_code  from Viw_customermaster where ASM_code in ($ASMCodeSQL)";
                $RSM_Query=$this->dbw->query($RSM_Query);
                $RSM_Query_List=$RSM_Query->resultArrayList;
                $RSM_Code="";
                foreach($RSM_Query_List as $key=>$value){
                  $RSM_Code.=($value['Region_code'].",");
                }

             $ASM_Code_Query="select distinct  ASM_code  from Viw_customermaster where ASM_code in ($ASMCodeSQL)";
             $AsmCodeExec=$this->dbw->query($ASM_Code_Query);
             $Asm_Code_Result=$AsmCodeExec->resultArrayList;
             $Asm_Code=$Asm_Code_Result[0]['ASM_code'];


                  #insert into Promotionaws
                    $applawcodeIds = [];
                    foreach($applawcodes as $applawcode){
                        $params4 = array(
                            $budgetId[0]['RowID'],
                            isset($applawcode->applawcode)?$applawcode->applawcode:null,
                            isset($applawcode->Amt)?$applawcode->Amt:null,
                            $CustomerValue,
                            $Createddate,
                            $UpdatedDate,
                            $CreatedBy,
                            $TISRESULT,
                            $SomResult,
                            $RSM_Code,
                            $Asm_Code,
                            $promotionCode
                        );
                        $tsql4 = "INSERT Promotionaws (BudgetPromotionId, applawcode, Amt,AwTypevalue,CreatedDate,UpdatedDate,CreatedBy,TSICode,SOM_CODE,RSM_CODE,ASM_CODE,promocode) OUTPUT INSERTED.RowID VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
                        $aws = $this->dbw->query($tsql4, $params4);
                        $awslId = $aws->resultArrayList;
                        $applawcodeIds[] = $awslId[0]['RowID'];
                    }
                    $params5=[$OnSaleOffValue];
                    $tsql5="INSERT PromotionsaleProduct(saleprodlevel) OUTPUT INSERTED.RowID VALUES(?)";
                    $promotionSaleProductExec=$this->dbw->query($tsql5,$params5);
                    $promotionSaleProductResult=$promotionSaleProductExec->resultArrayList;
                    $promotionSaleProduct=$promotionSaleProductResult[0]['RowID'];

                    $res = ['data'=>['budget'=>$budgetId[0]['RowID'], 'channel'=>$channelIds, 'details'=>$detailsIds, 'awslId' => $applawcodeIds,'promotionSaleProduct'=>$promotionSaleProduct]];
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