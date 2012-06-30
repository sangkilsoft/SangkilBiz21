<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SalComp extends CComponent {

    public static function createSales($hdr = array(), $dtl = array()) {
        /*
          Skenario :
          1. Validasi data
          2. Insert Header
          3. Insert Detail
          4. Create GI
          5. Create Invoice
         */
        try {
            $hdrmodel = new SalesHdr;
            $hdrmodel->sal_num = SysComp::getNumberDoc('SL01', '10', $hdr['cdunit']);
            $hdrmodel->date_sales = new CDbExpression("to_date('" . $hdr['date_sales'] . "','dd-mm-yyyy')");
            $hdrmodel->cdvend = '1003';
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->cdwhse = $hdr['cdwhse'];
            $hdrmodel->dscrp = $hdr['dscrp'];
            $hdrmodel->sal_type = $hdr['type'];
            $hdrmodel->id_periode = FiComp::getActivePeriode();

            $simpan = $hdrmodel->save();
            if (!$simpan)
                return array('type' => 'E', 'message' => $hdrmodel->getErrors());

            $i = 1;
            $totjual = 0;
            $tothpp = 0;
            $totbayar = 0;
            foreach ($dtl as $rows) {
                $dtlmodel = new SalesDtl;
                $dtlmodel->sal_num = $hdrmodel->sal_num;
                $dtlmodel->lnum = $i;
                $dtlmodel->cditem = $rows['cditem'];
                $dtlmodel->lnitem = $rows['lnitem'];
                $dtlmodel->qty = $rows['qtyitem'];
                $dtlmodel->uomdiskon = $rows['disk'];
                $dtlmodel->uomprice = (float) str_replace(',', '', $rows['sprise']);
                $hpp = SalComp::getHPP($rows['cditem'], $rows['lnitem']);

                $tothpp = $tothpp + ($hpp * $rows['qtyitem']);
                $totjual = $totjual + ($dtlmodel->uomprice * $dtlmodel->qty);
                $totbayar = $totbayar + (float) str_replace(',', '', $rows['stotal']);

                if (!$dtlmodel->save())
                    return array('type' => 'E', 'message' => $dtlmodel->getErrors());

//                $rsgi[$i] = $rows;
//                $rsgi[$i]['pprise'] = '0';
//                $rsgi[$i]['markup'] = '0';

                $i++;
            }

            try {
                $hdr['refnum'] = $hdrmodel->sal_num;
                $hdr['date_gi'] = date('d-m-Y');
                $gi = InvComp::createGI($hdr, $dtl);
                if ($gi['type'] == 'E')
                    return $gi;
            } catch (Exception $e) {
                return array('type' => 'E', 'message' => $e->getMessage());
            }

            try {
                //entri hdr jurnal ---------------------------------
                $billhdr['dscrp'] = $hdr['dscrp'].' '.$hdrmodel->sal_num;
                $billhdr['gl_date'] = date('d-m-Y');
                $billhdr['refnum'] = $hdrmodel->sal_num;
                $billhdr['cdunit'] = $hdrmodel->cdunit;

                //debit kas
                $j = 0;
                $billdtl[$j] = array('cdacc' => '1003', 'cdfigroup' => '1000', 'debit' => $totbayar, 'kredit' => '0');

                //diskon penjualan                
                if ($totjual != $totbayar) {
                    $j++;
                    $totdisk = $totjual - $totbayar;
                    $billdtl[$j] = array('cdacc' => '4003', 'cdfigroup' => '4000', 'debit' => $totdisk, 'kredit' => '0');
                }

                //kredit penjualan
                $j++;
                $billdtl[$j] = array('cdacc' => '4001', 'cdfigroup' => '4000', 'debit' => '0', 'kredit' => $totjual);

                //debit HPP                
                $j++;
                $billdtl[$j] = array('cdacc' => '5001', 'cdfigroup' => '5000', 'debit' => $tothpp, 'kredit' => '0');
                
                //kredit persediaan
                $j++;
                $billdtl[$j] = array('cdacc' => '1005', 'cdfigroup' => '1000', 'debit' => '0', 'kredit' => $tothpp);

                $bill = FiComp::createGL($billhdr, $billdtl);
                if ($bill['type'] == 'E')
                    return $bill;
            } catch (Exception $e) {
                return array('type' => 'E', 'message' => $e->getMessage());
            }

            return array('type' => 'S', 'message' => 'Sales Created, ' . $hdrmodel->sal_num, 'val' => $hdrmodel->sal_num);
        } catch (ErrorException $e) {
            return array('type' => 'E', 'message' => $e->getMessage());
        }
    }

    public static function getHPP($itemnum, $itemln) {
        $hpp = new MditemPrice;
        $hppitem = $hpp->model()->find('cditem=:cditem AND lnitem=:lnitem', array(':cditem' => $itemnum, ':lnitem' => $itemln));
        if (count($hppitem) > 0)
            return $hppitem->getAttribute('val_cost');
        else
            return 0;
    }

}

?>
