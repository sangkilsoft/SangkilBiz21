<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InvComp extends CComponent {

    public function createGI($hdr = array(), $dtl = array()) {
        //check hdr ...
        $retval = array();
        if ($hdr['dscrp'] == '')
            $retval = array('type' => 'E', 'message' => 'Error GI: Deskripsi GI tidak boleh kosong ..!');

        if (!count($dtl) > 1)
            $retval = array('type' => 'E', 'message' => 'Error GI: Detail GI tidak boleh kosong ..!');

        if (count($retval) > 0)
            return $retval;

        try {
            $hdrmodel = new InvgiHdr;
            $hdrmodel->gi_num = SysComp::getNumberDoc('GI01', '10', $hdr['cdunit']);
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->cdwhse = $hdr['cdwhse'];
            $hdrmodel->dscrp = $hdr['dscrp'];

            $hdrmodel->date_gi = new CDbExpression("to_date('" . $hdr['date_gi'] . "','dd-mm-yyyy')");
            $hdrmodel->id_periode = FiComp::getActivePeriode();
            $hdrmodel->refnum = $hdr['refnum'];

            if (!$hdrmodel->save())
                return array('type' => 'E', 'message' => $hdrmodel->getErrors());

            $i = 1;
            foreach ($dtl as $rows) {
                $dtlmodel = new InvgiDtl;
                $dtlmodel->gi_num = $hdrmodel->gi_num;
                $dtlmodel->lnum = $i;
                $dtlmodel->cditem = $rows['cditem'];
                $dtlmodel->lnitem = $rows['lnitem'];
                $dtlmodel->cduom = $rows['uom'];
                $dtlmodel->qty = $rows['qtyitem'];
                $dtlmodel->uomprice = (float) str_replace(',', '', $rows['sprise']);

                if (isset($rows['pprise']))
                    $dtlmodel->uomcost = (float) str_replace(',', '', $rows['pprise']);
                else
                    $dtlmodel->uomcost = 0;

                if (isset($rows['markup']))
                    $dtlmodel->markup = (float) str_replace(',', '', $rows['markup']);
                else
                    $dtlmodel->markup = 0;

                if (!$dtlmodel->save())
                    return array('type' => 'E', 'message' => $dtlmodel->getErrors());

                $addstock = InvStock::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cditem=:cditem AND lnitem=:lnitem AND cduom=:cduom AND cdwhse=:cdwhse';
                $criteria->params = array(':cditem' => $rows['cditem'], ':lnitem' => $rows['lnitem'], ':cduom' => $rows['uom'], ':cdwhse' => $hdr['cdwhse']);

                $exist = $addstock->exists($criteria);
                $addstock = new InvStock;
                $addstock->cditem = $rows['cditem'];
                $addstock->lnitem = $rows['lnitem'];
                $addstock->id_periode = FiComp::getActivePeriode();
                $addstock->cduom = $rows['uom'];
                $addstock->cdwhse = $hdr['cdwhse'];
                $addstock->date_mv = 'now()';
                $addstock->qtymv = $rows['qtyitem'] * -1;
                $addstock->refnum = $dtlmodel->gi_num . "/" . $dtlmodel->lnum;

                if (!$exist)
                    $addstock->qtynow = $addstock->qtymv;
                else {
                    $sql = "select qtynow 
                        from minidb.invmv_stock 
                        where trim(cditem)='" . $rows['cditem'] . "' AND lnitem=" . $rows['lnitem'] . " AND trim(cduom)='" . $rows['uom'] . "' AND trim(cdwhse)='" . $hdr['cdwhse'] . "'
                        AND mvstock_id = (select max(mvstock_id) from minidb.invmv_stock where trim(cditem)='" . $rows['cditem'] . "' AND lnitem=" . $rows['lnitem'] . " AND trim(cduom)='" . $rows['uom'] . "' AND trim(cdwhse)='" . $hdr['cdwhse'] . "')";

                    $command = Yii::app()->db->createCommand($sql);
                    $oldqty = $command->queryScalar();

                    $addstock->qtynow = $oldqty + $addstock->qtymv;
                }

                if (!$addstock->save())
                    return array('type' => 'E', 'message' => $addstock->getErrors());

                $i++;
            }

            return array('type' => 'S', 'message' => 'GI Created, GI Num:' . $hdrmodel->gi_num, 'val' => $hdrmodel->gi_num);
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    public function createGR($hdr = array(), $dtl = array()) {
        //check hdr ...
        $grreturn = array();
        if ($hdr['dscrp'] == '')
            $grreturn = array('type' => 'E', 'message' => 'Error GR: Deskripsi GR tidak boleh kosong ..!');

        if (!count($dtl) > 1)
            $grreturn = array('type' => 'E', 'message' => 'Error GR: Detail GR tidak boleh kosong ..!');

        if (count($grreturn) > 0)
            return $grreturn;

        try {
            $hdrmodel = new InvgrHdr;
            $hdrmodel->gr_num = SysComp::getNumberDoc('GR01', '10', $hdr['cdunit']);
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->cdwhse = $hdr['cdwhse'];
            $hdrmodel->dscrp = $hdr['dscrp'];
            $hdrmodel->date_gr = new CDbExpression("to_date('" . $hdr['date_gr'] . "','dd-mm-yyyy')");
            $hdrmodel->id_periode = FiComp::getActivePeriode();
            $hdrmodel->refnum = $hdr['refnum'];

            $simpan = $hdrmodel->save();
            if (!$simpan) {
                $grreturn = array('type' => 'E', 'message' => $hdrmodel->getErrors());
                return $grreturn;
            }

            $i = 1;
            foreach ($dtl as $rows) {
                $dtlmodel = new InvgrDtl;
                $dtlmodel->gr_num = $hdrmodel->gr_num;
                $dtlmodel->lnum = $i;
                $dtlmodel->cditem = $rows['cditem'];
                $dtlmodel->lnitem = $rows['lnitem'];
                $dtlmodel->cduom = $rows['uom'];
                $dtlmodel->qty = $rows['qtyitem'];
                $dtlmodel->uomprice = (float) str_replace(',', '', $rows['sprise']);

                if (isset($rows['pprise']))
                    $dtlmodel->uomcost = (float) str_replace(',', '', $rows['pprise']);
                else
                    $dtlmodel->uomcost = 0;

                if (isset($rows['markup']))
                    $dtlmodel->markup = (float) str_replace(',', '', $rows['markup']);
                else
                    $dtlmodel->markup = 0;


                if (!$dtlmodel->save()) {
                    $grreturn = array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    return $grreturn;
                }

                $addstock = InvStock::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cditem=:cditem AND lnitem=:lnitem AND cduom=:cduom AND cdwhse=:cdwhse';
                $criteria->params = array(':cditem' => $rows['cditem'], ':lnitem' => $rows['lnitem'], ':cduom' => $rows['uom'], ':cdwhse' => $hdr['cdwhse']);

                $exist = $addstock->exists($criteria);
                if (!$exist) {
                    $addstock = new InvStock;
                    $addstock->id_periode = FiComp::getActivePeriode();
                    $addstock->cditem = $rows['cditem'];
                    $addstock->lnitem = $rows['lnitem'];
                    $addstock->cduom = $rows['uom'];
                    $addstock->cdwhse = $hdr['cdwhse'];
                    $addstock->date_mv = 'now()';
                    $addstock->qtymv = $rows['qtyitem'];
                    $addstock->qtynow = $addstock->qtymv;
                    $addstock->refnum = $dtlmodel->gr_num . "/" . $dtlmodel->lnum;
                    if (!$addstock->save()) {
                        $grreturn = array('type' => 'E', 'message' => $addstock->getErrors());
                        return $grreturn;
                    }
                } else {
                    $sql = "select qtynow 
                        from minidb.invmv_stock 
                        where trim(cditem)='" . $rows['cditem'] . "' AND lnitem=" . $rows['lnitem'] . " AND trim(cduom)='" . $rows['uom'] . "' AND trim(cdwhse)='" . $hdr['cdwhse'] . "'
                        AND mvstock_id = (select max(mvstock_id) from minidb.invmv_stock where trim(cditem)='" . $rows['cditem'] . "' AND lnitem=" . $rows['lnitem'] . " AND trim(cduom)='" . $rows['uom'] . "' AND trim(cdwhse)='" . $hdr['cdwhse'] . "')";

                    $command = Yii::app()->db->createCommand($sql);
                    $oldqty = $command->queryScalar();

                    $addstock = new InvStock;
                    $addstock->id_periode = FiComp::getActivePeriode();
                    $addstock->cditem = $rows['cditem'];
                    $addstock->lnitem = $rows['lnitem'];
                    $addstock->cduom = $rows['uom'];
                    $addstock->cdwhse = $hdr['cdwhse'];
                    $addstock->date_mv = 'now()';
                    $addstock->qtymv = $rows['qtyitem'];
                    $addstock->qtynow = $oldqty + $addstock->qtymv;
                    $addstock->refnum = $dtlmodel->gr_num . "/" . $dtlmodel->lnum;

                    if (!$addstock->save()) {
                        $grreturn = array('type' => 'E', 'message' => $addstock->getErrors());
                        return $grreturn;
                    }
                }
                $i++;
            }
            return array('type' => 'S', 'message' => 'GR Created, GR Num:' . $hdrmodel->gr_num, 'val' => $hdrmodel->gr_num);
        } catch (ErrorException $e) {
            $grreturn = array('type' => 'E', 'message' => $e->getMessage());
            return $grreturn;
        }
    }

    public static function createSto($hdr = array(), $dtl = array()) {
        /*
          Skenario :
          1. Validasi data
          2. Insert Header
          3. Insert Detail
          4. Create GI (Status Issued, Receipt)
          5. Create GR (Status Receipt)
         */

        try {
            $hdrmodel = new InvtrfHdr;
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->cdwhse = $hdr['cdwhse'];
            $hdrmodel->cdwhse2 = $hdr['cdwhse2'];
            $hdrmodel->dscrp = $hdr['dscrp'];
            $hdrmodel->status = $hdr['status'];

            $hdrmodel->trf_num = SysComp::getNumberDoc('TR01', '10', $hdr['cdunit']);
            $hdrmodel->gi_num = '-1';
            $hdrmodel->gr_num = '-1';
            $hdrmodel->date_trf = new CDbExpression("to_date('" . $hdr['date_trf'] . "','dd-mm-yyyy')");

            if (!$hdrmodel->save())
                return array('type' => 'E', 'message' => $hdrmodel->getErrors());

            $i = 1;
            $rsgi = array();
            foreach ($dtl as $rows) {
                $dtlmodel = new InvtrfDtl;
                $dtlmodel->trf_num = $hdrmodel->trf_num;
                $dtlmodel->lnum = $i;
                $dtlmodel->cditem = $rows['cditem'];
                $dtlmodel->lnitem = $rows['lnitem'];
                $dtlmodel->qtytrf = $rows['qtyitem'];
                $dtlmodel->cduom = $rows['uom'];

                $dtlmodel->uomcost = (float) str_replace(',', '', 0);
                $dtlmodel->uomprice = (float) str_replace(',', '', $rows['sprise']);

                if (!$dtlmodel->save())
                    return array('type' => 'E', 'message' => $dtlmodel->getErrors());

                $rsgi[$i] = $rows;
                $rsgi[$i]['pprise'] = '0';
                $rsgi[$i]['markup'] = '0';
                $i++;
            }

            if ($hdr['status'] > 0) {
                try {
                    $hdr['refnum'] = $hdrmodel->trf_num;
                    $hdr['date_gi'] = date('d-m-Y');
                    $gi = InvComp::createGI($hdr, $dtl);
                    if ($gi['type'] == 'E')
                        return $gi;
                } catch (Exception $e) {
                    return array('type' => 'E', 'message' => $e->getMessage());
                }
            }

            if ($hdr['status'] > 1) {
                try {
                    $hdr['refnum'] = $hdrmodel->trf_num;
                    $hdr['date_gr'] = date('d-m-Y');
                    $hdr['cdwhse'] = $hdrmodel->cdwhse2;
                    $forunit = InvWarehouse::model()->find('cdwhse =:cdwhse', array(':cdwhse' => $hdrmodel->cdwhse2));
                    $hdr['cdunit'] = $forunit['cdunit'];
                    $hdr['dscrp'] = 'by sto creation';

                    $gr = InvComp::createGR($hdr, $dtl);

                    if ($gr['type'] == 'E')
                        return $gr;
                } catch (Exception $e) {
                    return array('type' => 'E', 'message' => $e->getMessage());
                }
            }

            return array('type' => 'S', 'message' => 'Transfer Created, STO Num:' . $hdrmodel->trf_num, 'val' => $hdrmodel->trf_num);
        } catch (ErrorException $e) {
            $trns->rollback();
            print_r($e->getMessage());
            return false;
        }
    }

    public function createPO($hdr = array(), $dtl = array()) {
        /*
          Skenario :
          1. Validasi data
          2. Insert Header
          3. Insert Detail
          4. Create GR (Status Receipt, Posted)
          5. Create Invoice (Status Posted)
         */

        //Validasi data hdr ...
        $retval = array();
        if ($hdr['dscrp'] == '')
            $retval = array('type' => 'E', 'message' => 'Error PO: Deskripsi tidak boleh kosong ..!');

        if (!count($dtl) > 1)
            $retval = array('type' => 'E', 'message' => 'Error PO: Detail tidak boleh kosong ..!');

        if (count($retval) > 0)
            return $retval;

        try {
            $hdrmodel = new InvpurchHdr;
            $hdrmodel->purch_num = SysComp::getNumberDoc('PO01', '11', $hdr['cdunit']);
            $hdrmodel->id_periode = FiComp::getActivePeriode();
            $hdrmodel->dscrp = $hdr['dscrp'];
            $hdrmodel->refnum = $hdr['refnum'];
            $hdrmodel->status = $hdr['status'];
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->cdwhse = $hdr['cdwhse'];
            $hdrmodel->cdvend = $hdr['cdvend'];

            $hdrmodel->gr_num = '-1';
            $hdrmodel->bill_num = '-1';
            $hdrmodel->termofpayment = 'credit 30 hari';

            if (!$hdrmodel->save())
                return array('type' => 'E', 'message' => $hdrmodel->getErrors());

            $i = 1;
            $totcost = 0;
            foreach ($dtl as $rows) {
                $dtlmodel = new InvpurchDtl;
                $dtlmodel->purch_num = $hdrmodel->purch_num;
                $dtlmodel->lnum = $i;
                $dtlmodel->cditem = $rows['cditem'];
                $dtlmodel->lnitem = $rows['lnitem'];
                $dtlmodel->cduom = $rows['uom'];
                $dtlmodel->qtypurch = $rows['qtyitem'];

                $dtlmodel->markup = $rows['markup'];
                $dtlmodel->uomcost = (float) str_replace(',', '', $rows['pprise']);
                $dtlmodel->uomprice = (float) str_replace(',', '', $rows['sprise']);
                $totcost += ($dtlmodel->uomcost * $dtlmodel->qtypurch);


                if (!$dtlmodel->save()) {
                    $retval = array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    return $retval;
                }
                $i++;
            }

            //status receipt
            if ($hdrmodel->status >= 1) {
                $hdr['refnum'] = $hdrmodel->purch_num;
                $hdr['date_gr'] = date('d-m-Y');
                $hdr['dscrp'] = 'by po creation';

                $gr = InvComp::createGR($hdr, $dtl);
                if ($gr !== false) {
                    if ($gr['type'] == 'E')
                        return $gr;
                }else {
                    $retval = array('type' => 'E', 'message' => 'GR Failed');
                    return $retval;
                }

                //status posted
                if ($hdrmodel->status >= 2) {
                    $hdr['dscrp'] = "Pembelian Barang";
                    $hdr['gl_date'] = date('d-m-Y');
                    $hdr['refnum'] = $hdrmodel->purch_num;

                    $dtl[0]['cdacc'] = '1005'; //akun code for persediaan
                    $dtl[0]['cdfigroup'] = '1000';
                    $dtl[0]['debit'] = $totcost;
                    $dtl[0]['kredit'] = '0';

                    $dtl[1]['cdacc'] = '2001'; //akun code for hutang
                    $dtl[1]['cdfigroup'] = '2000';
                    $dtl[1]['debit'] = '0';
                    $dtl[1]['kredit'] = $totcost;

                    $bill = FiComp::createGL($hdr, $dtl);
                    if ($bill !== false) {
                        if ($bill['type'] == 'E')
                            return $bill;
                    }else {
                        $retval = array('type' => 'E', 'message' => 'Billing Failed');
                        return $retval;
                    }

                    //for catatan hutang
                    try {
                        $dthutang = array('purch_num' => $hdrmodel->purch_num, 'cdvend' => $hdrmodel->cdvend, 'total_hutang' => $totcost, 'status' => 0);
                        $hutang = FiComp::createHutang($dthutang);
                        if ($hutang['type'] == 'E')
                            return $hutang;
                    } catch (ErrorException $e) {
                        return array('type' => 'E', 'message' => 'Hutang Failed');
                    }
                    
                    //UPDATE ITEM PRICE
                }
            }

            $retval = array('type' => 'S', 'message' => 'PO Created, PO Num:' . $hdrmodel->purch_num, 'val' => $hdrmodel->purch_num);
            //$trns->commit();
            return $retval;
        } catch (ErrorException $e) {
            print_r($e->getMessage());
            return;
        }
    }

    public function updtePO($frstatus, $tostatus, $datahdr = array(), $datadtl = array()) {
        try {
            // <editor-fold defaultstate="collapsed" desc="Update header and detail for all">
            $model = InvpurchHdr::model();
            $criteria = new CDbCriteria;
            $criteria->condition = 'purch_num=:purch_num AND id_periode =:id_periode ';
            $criteria->params = array(':purch_num' => $datahdr['purch_num'], 'id_periode' => FiComp::getActivePeriode());
            $hdr = $model->find($criteria);

            if (count($hdr) > 0) {
                $hdr->dscrp = $datahdr['dscrp'];
                $hdr->status = $datahdr['status'];
                if (!$hdr->save())
                    return array('type' => 'E', 'message' => 'Save header failed');
            }else
                return array('type' => 'E', 'message' => 'Update failed');

            //set data detail --------------------------------------------------
            $creating = array();
            $updating = array();
            $forcompare = array();

            if (isset($datadtl[0]['cditem']))
                if (count($datadtl) > 0) {
                    foreach ($datadtl as $rows) {
                        if (!isset($rows['lnum']))
                            $creating[] = $rows;
                        else {
                            $updating[] = $rows;
                            $forcompare[] = $datahdr['purch_num'] . '' . $rows['lnum'];
                        }
                    }
                }

            $modeldtl = InvpurchDtl::model();
            $criteria = new CDbCriteria;
            $criteria->condition = 'purch_num=:purch_num ';
            $criteria->params = array(':purch_num' => $datahdr['purch_num']);
            $dtldata = $modeldtl->findAll($criteria);

            $deleting = array();
            $totcost = 0;
            foreach ($dtldata as $rows) {
                $tempdtl[] = $rows['lnum'];
                if (!in_array($rows['purch_num'] . $rows['lnum'], $forcompare))
                    $deleting[] = $rows;
                $totcost += ($rows['uomcost'] * $rows['qtypurch']);
            }

            $maxline = max($tempdtl) + 1;
            if ($tostatus !== '-1') {
                $rsgi = array();
                $rsgr = array();
                $j = 0;
                $k = 0;
                //creating record
                if (count($creating) > 0)
                    for ($i = 0; $i < count($creating); $i++) {
                        $dtlmodel = new InvpurchDtl;
                        $dtlmodel->lnum = $maxline;
                        $dtlmodel->purch_num = $datahdr['purch_num'];
                        $dtlmodel->cditem = $creating[$i]['cditem'];
                        $dtlmodel->lnitem = $creating[$i]['lnitem'];
                        $dtlmodel->cduom = $creating[$i]['uom'];
                        $dtlmodel->qtypurch = $creating[$i]['qtyitem'];
                        $dtlmodel->markup = $creating[$i]['markup'];
                        $dtlmodel->uomcost = (float) str_replace(',', '', $creating[$i]['pprise']);
                        $dtlmodel->uomprice = (float) str_replace(',', '', $creating[$i]['sprise']);
                        $maxline++;

                        $rsgr[$k] = $creating[$i];
                        $k++;

                        if (!$dtlmodel->save())
                            return array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    }

                //updating record
                if (count($updating) > 0)
                    for ($i = 0; $i < count($updating); $i++) {
                        $dtlmodel = InvpurchDtl::model();
                        $criteria = new CDbCriteria;
                        $criteria->condition = 'purch_num=:purch_num AND lnum=:lnum';
                        $criteria->params = array(':purch_num' => $datahdr['purch_num'], ':lnum' => $updating[$i]['lnum']);
                        $toupdate = $dtlmodel->find($criteria);

                        $selisih = $updating[$i]['qtyitem'] - $toupdate->qtypurch;

                        $toupdate->purch_num = $datahdr['purch_num'];
                        $toupdate->lnum = $updating[$i]['lnum'];
                        $toupdate->cditem = $updating[$i]['cditem'];
                        $toupdate->lnitem = $updating[$i]['lnitem'];
                        $toupdate->cduom = $updating[$i]['uom'];
                        $toupdate->qtypurch = $updating[$i]['qtyitem'];
                        $toupdate->markup = $updating[$i]['markup'];
                        $toupdate->uomcost = (float) str_replace(',', '', $updating[$i]['pprise']);
                        $toupdate->uomprice = (float) str_replace(',', '', $updating[$i]['sprise']);

                        if ($selisih < 0) {
                            $rsgi[$j] = $updating[$i];
                            $rsgi[$j]['qtyitem'] = abs($selisih);
                            $j++;
                        } elseif ($selisih > 0) {
                            $rsgr[$k] = $updating[$i];
                            $rsgr[$k]['qtyitem'] = abs($selisih);
                            $k++;
                        }

                        if (!$toupdate->save())
                            return array('type' => 'E', 'message' => $toupdate->getErrors());
                    }

                //deleting record
                if (count($deleting) > 0)
                    for ($i = 0; $i < count($deleting); $i++) {
                        $dtlmodel = InvpurchDtl::model();
                        $criteria = new CDbCriteria;
                        $criteria->condition = 'purch_num=:purch_num AND lnum=:lnum';
                        $criteria->params = array(':purch_num' => $datahdr['purch_num'], ':lnum' => $deleting[$i]['lnum']);
                        $todelete = $dtlmodel->find($criteria);

                        $rsgi[$j] = array('lnum' => $todelete['lnum'], "cditem" => $todelete['cditem'], "lnitem" => $todelete['lnitem'], "qtyitem" => $todelete['qtypurch'], "uom" => $todelete['cduom'], "pprise" => $todelete['uomcost'], "markup" => $todelete['markup'], "sprise" => $todelete['uomprice']);
                        $j++;

                        if (!$todelete->delete())
                            return array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    }
            } // </editor-fold> 
            //update data record -----------------------------------------------
            switch ($frstatus) {
                case 0:// <editor-fold defaultstate="collapsed" desc="from zero">
                    switch ($tostatus) {
                        case 0: //From Draft to Draft
                            return array('type' => 'S', 'message' => 'Success update ' . $datahdr['purch_num']);
                            break;
                        case 1://From Draft to Receipt
                            try {
                                $datahdr['refnum'] = $datahdr['purch_num'];
                                $datahdr['date_gr'] = date('d-m-Y');
                                $datahdr['dscrp'] = 'by po update';
                                $gr = InvComp::createGR($datahdr, $datadtl);
                                if ($gr['type'] == 'E')
                                    return $gr;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            $msg = 'Success updating ' . $datahdr['purch_num'];
                            $msg .= '</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            $msg .= $gr['message'];

                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case 2://From Draft to Posted
                            try {
                                $datahdr['refnum'] = $datahdr['purch_num'];
                                $datahdr['date_gr'] = date('d-m-Y');
                                $datahdr['dscrp'] = 'by po update';
                                $gr = InvComp::createGR($datahdr, $datadtl);
                                if ($gr['type'] == 'E')
                                    return $gr;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            try {
                                //entri jurnal ---------------------------------
                                $datahdr['dscrp'] = "Create by purcashing";
                                $datahdr['tglgl'] = date('d-m-Y');
                                $datahdr['refnum'] = $datahdr['purch_num'];

                                $dtl[0]['cdacc'] = '1005'; //akun code for persediaan
                                $dtl[0]['cdfigroup'] = '1000';
                                $dtl[0]['debit'] = $totcost;
                                $dtl[0]['kredit'] = '0';

                                $dtl[1]['cdacc'] = '2001'; //akun code for hutang
                                $dtl[1]['cdfigroup'] = '2000';
                                $dtl[1]['debit'] = '0';
                                $dtl[1]['kredit'] = $totcost;

                                $bill = FiComp::createGL($datahdr, $datadtl);
                                if ($bill['type'] == 'E')
                                    return $bill;

                                //catatan hutang vendor
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            //for catatan hutang
                            try {
                                $dthutang = array('purch_num' => $hdr->purch_num, 'cdvend' => $hdr->cdvend, 'total_hutang' => $totcost, 'status' => 0);
                                $hutang = FiComp::createHutang($dthutang);
                                if ($hutang['type'] == 'E')
                                    return $hutang;
                            } catch (ErrorException $e) {
                                return array('type' => 'E', 'message' => 'Hutang Failed');
                            }

                            $msg = 'Success updating ' . $datahdr['purch_num'];
                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case -1 ://From Draft to Canceled
                            return array('type' => 'S', 'message' => 'Cancel purchasing document ' . $datahdr['purch_num']);
                            break;
                    }
                    break; // </editor-fold>                
                case 1:// <editor-fold defaultstate="collapsed" desc="from receipt">
                    switch ($tostatus) {
                        case 1:
                            $msg = 'Success updating ' . $datahdr['purch_num'];
                            try {
                                //gr->by_fer updplus po line, gr->by_fer new po line                            
                                if (count($rsgr) > 0) {
                                    $datahdr['refnum'] = $datahdr['purch_num'];
                                    $datahdr['date_gr'] = date('d-m-Y');
                                    $datahdr['dscrp'] = 'by po update';
                                    $gr = InvComp::createGR($datahdr, $rsgr);
                                    if ($gr['type'] == 'E')
                                        return $gr;
                                }

                                //gi->by_ref delete po line, gi->by_fer updplus po line
                                if (count($rsgi) > 0) {
                                    $datahdr['refnum'] = $datahdr['purch_num'];
                                    $datahdr['date_gi'] = date('d-m-Y');
                                    $gi = InvComp::createGI($datahdr, $rsgi);
                                    if ($gi['type'] == 'E')
                                        return $gi;
                                }
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case 2:
                            try {
                                //gr->by_ref updplus po line, gr->by_ref new po line                            
                                if (count($rsgr) > 0) {
                                    $datahdr['refnum'] = $datahdr['purch_num'];
                                    $datahdr['date_gr'] = date('d-m-Y');
                                    $datahdr['dscrp'] = 'by po update';
                                    $gr = InvComp::createGR($datahdr, $rsgr);
                                    if ($gr['type'] == 'E')
                                        return $gr;
                                }

                                //gi->by_ref delete po line, gi->by_fer updplus po line
                                if (count($rsgi) > 0) {
                                    $datahdr['refnum'] = $datahdr['purch_num'];
                                    $datahdr['date_gi'] = date('d-m-Y');
                                    $gi = InvComp::createGI($datahdr, $rsgi);
                                    if ($gi['type'] == 'E')
                                        return $gi;
                                }
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            try {
                                $datahdr['dscrp'] = "Create by purcashing";
                                $datahdr['tglgl'] = date('d-m-Y');
                                $datahdr['refnum'] = $datahdr['purch_num'];

                                $datadtl[0]['cdacc'] = '2001'; //akun code for hutang
                                $datadtl[0]['cdfigroup'] = '2000';
                                $datadtl[0]['debit'] = $totcost;
                                $datadtl[0]['kredit'] = '0';

                                $datadtl[1]['cdacc'] = '1005'; //akun code for persediaan
                                $datadtl[1]['cdfigroup'] = '1000';
                                $datadtl[1]['debit'] = '0';
                                $datadtl[1]['kredit'] = $totcost;

                                $bill = FiComp::createGL($datahdr, $datadtl);
                                if ($bill['type'] == 'E')
                                    return $bill;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            //for catatan hutang
                            try {
                                $dthutang = array('purch_num' => $datahdr['purch_num'], 'cdvend' => $datahdr['cdvend'], 'total_hutang' => $totcost, 'status' => 0);
                                $hutang = FiComp::createHutang($dthutang);
                                if ($hutang['type'] == 'E')
                                    return $hutang;
                            } catch (ErrorException $e) {
                                return array('type' => 'E', 'message' => 'Hutang Failed');
                            }

                            $msg = 'Success updating ' . $datahdr['purch_num'];
                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case -1:
                            if (count($dtldata) > 0) {
                                $r = 0;
                                foreach ($dtldata as $rows) {
                                    $rsgi[$r] = array('lnum' => $rows['lnum'],
                                        "cditem" => $rows['cditem'],
                                        "lnitem" => $rows['lnitem'],
                                        "qtyitem" => $rows['qtypurch'],
                                        "uom" => $rows['cduom'],
                                        "pprise" => $rows['uomcost'],
                                        "markup" => $rows['markup'],
                                        "sprise" => $rows['uomprice']);
                                    $r++;
                                }

                                $datahdr['refnum'] = $datahdr['purch_num'];
                                $datahdr['date_gi'] = date('d-m-Y');

                                $msg = 'Success canceled ' . $datahdr['purch_num'];
                                $gi = InvComp::createGI($datahdr, $rsgi);
                                if ($gi['type'] == 'E')
                                    return $gi;
                            }
                            return array('type' => 'S', 'message' => $msg);
                            break;
                    }
                    break; // </editor-fold>
                case 2:// <editor-fold defaultstate="collapsed" desc="from post">
                    switch ($tostatus) {
                        case 2:
                            $retval = array('type' => 'E', 'message' => $datahdr['purch_num'] . ' has been posted, can\'t be updated..!');
                            break;
                        case -1:
                            if (count($dtldata) > 0) {
                                $r = 0;
                                foreach ($dtldata as $rows) {
                                    $rsgi[$r] = array('lnum' => $rows['lnum'],
                                        "cditem" => $rows['cditem'],
                                        "lnitem" => $rows['lnitem'],
                                        "qtyitem" => $rows['qtypurch'],
                                        "uom" => $rows['cduom'],
                                        "pprise" => $rows['uomcost'],
                                        "markup" => $rows['markup'],
                                        "sprise" => $rows['uomprice']);
                                    $r++;
                                }

                                $datahdr['refnum'] = $datahdr['purch_num'];
                                $datahdr['date_gi'] = date('d-m-Y');

                                $gi = InvComp::createGI($datahdr, $rsgi);
                                if ($gi['type'] == 'E')
                                    return $gi;
                            }

                            //for cancel journal
                            try {
                                $datahdr['dscrp'] = "Cancel purcashing";
                                $datahdr['gl_date'] = date('d-m-Y');
                                $datahdr['refnum'] = $datahdr['purch_num'];

                                $datadtl[0]['cdacc'] = '2001'; //akun code for hutang
                                $datadtl[0]['cdfigroup'] = '2000';
                                $datadtl[0]['debit'] = $totcost;
                                $datadtl[0]['kredit'] = '0';

                                $datadtl[1]['cdacc'] = '1005'; //akun code for persediaan
                                $datadtl[1]['cdfigroup'] = '1000';
                                $datadtl[1]['debit'] = '0';
                                $datadtl[1]['kredit'] = $totcost;

                                $bill = FiComp::createGL($datahdr, $datadtl);
                                if ($bill['type'] == 'E')
                                    return $bill;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            //for catatan hutang
                            try {
                                $dthutang = array('purch_num' => $datahdr['purch_num'], 'cdvend' => $datahdr['cdvend'], 'total_hutang' => $totcost, 'status' => -1);
                                $hutang = FiComp::cancelHutang($dthutang);

                                if ($hutang['type'] == 'E')
                                    return $hutang;
                            } catch (ErrorException $e) {
                                return array('type' => 'E', 'message' => 'Cancel hutang failed');
                            }

                            $msg = 'Success canceled ' . $datahdr['purch_num'];
                            return array('type' => 'S', 'message' => $msg);
                            break;
                    }
                    break; // </editor-fold>
            }
            return $retval;
        } catch (Exception $exc) {
            $retval = $exc->getMessage();
        }
    }

    public function updteSto($frstatus, $tostatus, $datahdr = array(), $datadtl = array()) {
        try {
            // <editor-fold defaultstate="collapsed" desc="Update header and detail for all selected sto">
            $model = InvtrfHdr::model();
            $criteria = new CDbCriteria;
            $criteria->compare('trf_num', $datahdr['trf_num']);
            $hdr = $model->find($criteria);

            if (count($hdr) > 0) {
                $hdr->dscrp = $datahdr['dscrp'];
                $hdr->status = $datahdr['status'];
                if (!$hdr->save())
                    return array('type' => 'E', 'message' => 'Save header failed');
                else
                    $temp = "sukses";
            }else
                return array('type' => 'E', 'message' => 'No data found');

            //set data detail --------------------------------------------------
            $creating = array();
            $updating = array();
            $forcompare = array();

            if (isset($datadtl[0]['cditem']))
                if (count($datadtl) > 0) {
                    foreach ($datadtl as $rows) {
                        if (!isset($rows['lnum']))
                            $creating[] = $rows;
                        else {
                            $updating[] = $rows;
                            $forcompare[] = $datahdr['trf_num'] . '' . $rows['lnum'];
                        }
                    }
                }

            $modeldtl = InvtrfDtl::model();
            $criteria = new CDbCriteria;
            $criteria->compare('trf_num', $datahdr['trf_num']);
            $criteria->order = ('lnum ASC');
            $dtldata = $modeldtl->findAll($criteria);

            $tempdtl = array();
            $deleting = array();
            $totcost = 0;
            foreach ($dtldata as $rows) {
                $tempdtl[] = $rows['lnum'];
                if (!in_array($rows['trf_num'] . $rows['lnum'], $forcompare))
                    $deleting[] = $rows;
            }

            $maxline = max($tempdtl) + 1;
            if ($tostatus !== '-1') {
                $forPlus = array();
                $forMins = array();
                $j = 0;
                $k = 0;

                //creating record
                if (count($creating) > 0)
                    for ($i = 0; $i < count($creating); $i++) {
                        $dtlmodel = new InvtrfDtl();
                        $dtlmodel->lnum = $maxline;
                        $dtlmodel->trf_num = $datahdr['trf_num'];

                        $dtlmodel->cditem = $creating[$i]['cditem'];
                        $dtlmodel->lnitem = $creating[$i]['lnitem'];
                        $dtlmodel->cduom = $creating[$i]['uom'];
                        $dtlmodel->qtytrf = $creating[$i]['qtyitem'];
                        $dtlmodel->uomprice = (float) str_replace(',', '', $creating[$i]['sprise']);
                        $maxline++;

                        $forPlus[$k] = $creating[$i];
                        $k++;

                        if (!$dtlmodel->save())
                            return array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    }

                //updating record
                if (count($updating) > 0)
                    for ($i = 0; $i < count($updating); $i++) {
                        $dtlmodel = InvtrfDtl::model();
                        $criteria = new CDbCriteria;
                        $criteria->compare('trf_num', $datahdr['trf_num']);
                        $criteria->compare('lnum', $updating[$i]['lnum']);
                        $toupdate = $dtlmodel->find($criteria);

                        $selisih = $updating[$i]['qtyitem'] - $toupdate['qtytrf'];

                        $toupdate->cditem = $updating[$i]['cditem'];
                        $toupdate->lnitem = $updating[$i]['lnitem'];
                        $toupdate->cduom = $updating[$i]['uom'];
                        $toupdate->qtytrf = $updating[$i]['qtyitem'];
                        $toupdate->uomprice = (float) str_replace(',', '', $updating[$i]['sprise']);

                        if ($selisih < 0) {
                            $forMins[$j] = $updating[$i];
                            $forMins[$j]['qtyitem'] = abs($selisih);
                            $j++;
                        } elseif ($selisih > 0) {
                            $forPlus[$k] = $updating[$i];
                            $forPlus[$k]['qtyitem'] = abs($selisih);
                            $k++;
                        }

                        if (!$toupdate->save())
                            return array('type' => 'E', 'message' => $toupdate->getErrors());
                    }


                //deleting record
                if (count($deleting) > 0)
                    for ($i = 0; $i < count($deleting); $i++) {
                        $dtlmodel = InvtrfDtl::model();
                        $criteria = new CDbCriteria;
                        $criteria->compare('trf_num', $datahdr['trf_num']);
                        $criteria->compare('lnum', $deleting[$i]['lnum']);
                        $todelete = $dtlmodel->find($criteria);

                        if (!$todelete->delete())
                            return array('type' => 'E', 'message' => $todelete);
                        else {

                            $forMins[$j] = $deleting[$i];
                            $j++;
                        }
                    }
            } // </editor-fold> 
            //update data record -----------------------------------------------
            switch ($frstatus) {
                case 0:// <editor-fold defaultstate="collapsed" desc="from zero">
                    switch ($tostatus) {
                        case 0: //From Draft to Draft
                            return array('type' => 'S', 'message' => 'Success update ' . $datahdr['trf_num']);
                            break;
                        case 1://From Draft to Issued
                            try {
                                $datahdr['refnum'] = $datahdr['trf_num'];
                                $datahdr['date_gi'] = date('d-m-Y');

                                $gi = InvComp::createGI($datahdr, $datadtl);
                                if ($gi['type'] == 'E')
                                    return $gi;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }
                            $msg = 'Success updating ' . $datahdr['trf_num'] . ' to issued';
                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case 2://From Draft to Posted
                            try {
                                $datahdr['refnum'] = $datahdr['trf_num'];
                                $datahdr['date_gi'] = date('d-m-Y');
                                $datahdr['dscrp'] = 'by sto update';

                                $gi = InvComp::createGI($datahdr, $datadtl);
                                if ($gi['type'] == 'E')
                                    return $gi;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            try {
                                $datahdr['refnum'] = $datahdr['trf_num'];
                                $datahdr['date_gr'] = date('d-m-Y');
                                $datahdr['cdwhse'] = $datahdr['cdwhse2'];
                                $datahdr['dscrp'] = 'by sto update';

                                $forunit = InvWarehouse::model()->find('cdwhse =:cdwhse', array(':cdwhse' => $datahdr['cdwhse2']));
                                $datahdr['cdunit'] = $forunit['cdunit'];

                                $gr = InvComp::createGR($datahdr, $datadtl);

                                if ($gr['type'] == 'E')
                                    return $gr;
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            $msg = 'Success updating ' . $datahdr['trf_num'] . ' to receipt';
                            return array('type' => 'S', 'message' => $msg);
                            break;
                        case -1 ://From Draft to Canceled
                            return array('type' => 'S', 'message' => 'Cancel purchasing document ' . $datahdr['trf_num']);
                            break;
                    }
                    break; // </editor-fold>
                case 1:// <editor-fold defaultstate="collapsed" desc="from issued">
                    switch ($tostatus) {
                        case 1: // <editor-fold defaultstate="collapsed" desc="from issued to issued">
                            try {
                                //receipt deleted qty and minus diff
                                if (count($forPlus) > 0) {
                                    $datahdr['refnum'] = $datahdr['trf_num'];
                                    $datahdr['date_gi'] = date('d-m-Y');

                                    $gi = InvComp::createGI($datahdr, $forPlus);
                                    if ($gi['type'] == 'E')
                                        return $gi;
                                }

                                //issued additional and plus diff
                                if (count($forMins) > 0) {
                                    $datahdr['refnum'] = $datahdr['trf_num'];
                                    $datahdr['date_gr'] = date('d-m-Y');

                                    $gr = InvComp::createGR($datahdr, $forMins);
                                    if ($gr['type'] == 'E')
                                        return $gr;
                                }
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            return array('type' => 'S', 'message' => 'Update transfer document ' . $datahdr['trf_num']);
                            break;
                        // </editor-fold>                            
                        case 2: // <editor-fold defaultstate="collapsed" desc="from issued to receipt">
                            try {
                                //receipt deleted qty and minus diff
                                if (count($forPlus) > 0) {
                                    $datahdr['refnum'] = $datahdr['trf_num'];
                                    $datahdr['date_gi'] = date('d-m-Y');

                                    $gi = InvComp::createGI($datahdr, $forPlus);
                                    if ($gi['type'] == 'E')
                                        return $gi;
                                }

                                //issued additional and plus diff
                                if (count($forMins) > 0) {
                                    $datahdr['refnum'] = $datahdr['trf_num'];
                                    $datahdr['date_gr'] = date('d-m-Y');
                                    $datahdr['dscrp'] = 'by sto update';

                                    $gr = InvComp::createGR($datahdr, $forMins);
                                    if ($gr['type'] == 'E')
                                        return $gr;
                                }

                                //issued additional and plus diff
                                if (count($datadtl) > 0) {
                                    $datahdr['refnum'] = $datahdr['trf_num'];
                                    $datahdr['date_gr'] = date('d-m-Y');
                                    $datahdr['cdwhse'] = $datahdr['cdwhse2'];
                                    $datahdr['dscrp'] = 'by sto creation';

                                    $forunit = InvWarehouse::model()->find('cdwhse =:cdwhse', array(':cdwhse' => $datahdr['cdwhse2']));
                                    $datahdr['cdunit'] = $forunit['cdunit'];

                                    $gr = InvComp::createGR($datahdr, $datadtl);

                                    if ($gr['type'] == 'E')
                                        return $gr;
                                }
                            } catch (Exception $e) {
                                return array('type' => 'E', 'message' => $e->getMessage());
                            }

                            return array('type' => 'S', 'message' => 'Update transfer document ' . $datahdr['trf_num']);
                            break;
                        // </editor-fold>   
                        case -1:// <editor-fold defaultstate="collapsed" desc="from issued to canceled">
                            $forcancel = InvtrfDtl::model()->findAll('trf_num =:trf_num', array(':trf_num' => $datahdr['trf_num']));
                            //issued additional and plus diff
                            $r = 0;
                            foreach ($forcancel as $rows) {
                                $rscancel[$r] = array('lnum' => $rows['lnum'],
                                    "cditem" => $rows['cditem'],
                                    "lnitem" => $rows['lnitem'],
                                    "qtyitem" => $rows['qtytrf'],
                                    "uom" => $rows['cduom'],
                                    "pprise" => '0',
                                    "markup" => '0',
                                    "sprise" => $rows['uomprice']);
                                $r++;
                            }

                            $datahdr['refnum'] = $datahdr['trf_num'];
                            $datahdr['date_gr'] = date('d-m-Y');
                            $datahdr['dscrp'] = 'by sto update';

                            $gr = InvComp::createGR($datahdr, $rscancel);
                            if ($gr['type'] == 'E')
                                return $gr;

                            return array('type' => 'S', 'message' => 'Success canceled ' . $datahdr['trf_num']);
                            break;
                    }
                    // </editor-fold>   
                    break; // </editor-fold>
                case 2:// <editor-fold defaultstate="collapsed" desc="from post">
                    switch ($tostatus) {
                        case 2:
                            return array('type' => 'E', 'message' => $datahdr['trf_num'] . ' has been receipt, can\'t be updated..!');
                            break;
                        case -1:
                            $forcancel = InvtrfDtl::model()->findAll('trf_num =:trf_num', array(':trf_num' => $datahdr['trf_num']));
                            //issued additional and plus diff
                            $r = 0;
                            foreach ($forcancel as $rows) {
                                $rscancel[$r] = array('lnum' => $rows['lnum'],
                                    "cditem" => $rows['cditem'],
                                    "lnitem" => $rows['lnitem'],
                                    "qtyitem" => $rows['qtytrf'],
                                    "uom" => $rows['cduom'],
                                    "sprise" => $rows['uomprice']);
                                $r++;
                            }

                            $datahdr['refnum'] = $datahdr['trf_num'];
                            $datahdr['date_gr'] = date('d-m-Y');
                            $datahdr['dscrp'] = 'by sto update';

                            $gr = InvComp::createGR($datahdr, $rscancel);
                            if ($gr['type'] == 'E')
                                return $gr;

                            $datahdr['cdwhse'] = $datahdr['cdwhse2'];
                            $forunit = InvWarehouse::model()->find('cdwhse =:cdwhse', array(':cdwhse' => $datahdr['cdwhse2']));
                            $datahdr['cdunit'] = $forunit['cdunit'];
                            $datahdr['date_gi'] = date('d-m-Y');


                            $gi = InvComp::createGI($datahdr, $rscancel);
                            if ($gi['type'] == 'E')
                                return $gi;

                            return array('type' => 'S', 'message' => 'Success canceled ' . $datahdr['trf_num']);
                            break;
                    }
                    break; // </editor-fold>
            }
            return array('type' => 'E', 'message' => 'something error..!');
        } catch (Exception $exc) {
            return array('type' => 'E', 'message' => $exc->getMessage());
//            return array('type' => 'E', 'message' => 'error sangajo');
        }
    }

}

?>
