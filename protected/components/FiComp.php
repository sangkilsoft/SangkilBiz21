<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FiComp extends CComponent {

    public static function getActivePeriode() {
        $ap = FicoPeriode::model()->findAll('is_active=:is_active', array(':is_active' => '1'));
        if (count($ap) > 0) {
            foreach ($ap as $rows) {
                $rap = $rows['id_periode'];
            }
            return $rap;
        }else
            return array();
    }

    public function createGL($hdr = array(), $dtl = array()) {
        //check hdr ...
        $retval = array();
        if ($hdr['dscrp'] == '')
            $retval = array('type' => 'E', 'message' => 'Deskripsi journal tidak boleh kosong ..!');

        if (!count($dtl) > 1)
            $retval = array('type' => 'E', 'message' => 'Detail journal tidak boleh kosong ..!');

        $tdebit = 0;
        $tcredit = 0;
        foreach ($dtl as $rows) {
            $tdebit += (float) str_replace(',', '', $rows['debit']);
            $tcredit += (float) str_replace(',', '', $rows['kredit']);
        }

        if ($tdebit != $tcredit)
            $retval = array('type' => 'E', 'message' => 'journal tidak balance ..!');

        if (count($retval) > 0)
            return $retval;

        try {
            $hdrmodel = new FicoGl;
            $hdrmodel->cdfigl = SysComp::getNumberDoc('GL01', '8', $hdr['cdunit']);
            $hdrmodel->cdunit = $hdr['cdunit'];
            $hdrmodel->dscrp = $hdr['dscrp'];

            $hdrmodel->gl_date = new CDbExpression("to_date('" . $hdr['gl_date'] . "','dd-mm-yyyy')");


            $hdrmodel->id_periode = FiComp::getActivePeriode();
            $hdrmodel->refnum = $hdr['refnum'];

            if (!$hdrmodel->save()) {
                $retval = array('type' => 'E', 'message' => $hdrmodel->getErrors());
                return $retval;
            }

            foreach ($dtl as $rows) {
                $dtlmodel = new FicoGldtl();
                $dtlmodel->cdfigl = $hdrmodel->cdfigl;

                //get account id --------------------------------------------
                $acc = FicoNcoa::model();
                $criteria = new CDbCriteria;
                $criteria->select = array('id_coa');
                $criteria->condition = 'cdfiacc=:cdfiacc';
                $criteria->params = array(':cdfiacc' => $rows['cdacc']);
                $acc = $acc->find($criteria);
                //end get account group ----------------------------------------

                $dtlmodel->id_coa = $acc->id_coa;
                $dtlmodel->debit = (float) str_replace(',', '', $rows['debit']);
                $dtlmodel->kredit = (float) str_replace(',', '', $rows['kredit']);
                if (!$dtlmodel->save()) {
                    $retval = array('type' => 'E', 'message' => $dtlmodel->getErrors());
                    return $retval;
                }
            }

            $retval = array('type' => 'S', 'message' => 'GL Created :' . $dtlmodel->cdfigl, 'val' => $dtlmodel->cdfigl);
            return $retval;
        } catch (ErrorException $e) {
            print_r($e->getMessage());
            $retval = array('type' => 'S', 'message' => $e->getMessage());
            return $retval;
        }
    }

    public function createHutang($dthutang = array()) {
        try {
            $hdrmodel = new FicoHutang;
            $hdrmodel->purch_num = $dthutang['purch_num'];
            $hdrmodel->cdvend = $dthutang['cdvend'];
            $hdrmodel->total_hutang = $dthutang['total_hutang'];
            $hdrmodel->date_post = new CDbExpression('NOW()');

            if (!$hdrmodel->save())
                return array('type' => 'E', 'message' => $hdrmodel->getErrors());

            return array('type' => 'S', 'message' => 'Catatan hutang created');
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    public static function bayarHutang($dbayar = array()) {

        //check hdr ...
        $retval = array();
        try {

            $criteria = new CDbCriteria;
            $criteria->condition = 'purch_num=:purch_num';
            $criteria->params = array(':purch_num' => $dbayar['purch_num']);

            $exists = FicoBayar::model()->exists($criteria);
            if (!$exists) {
                $bayar = new FicoBayar;
                $bayar->cdfigl = $dbayar['cdfigl'];
                $bayar->purch_num = $dbayar['purch_num'];
                $bayar->cdvend = $dbayar['cdvend'];
                $bayar->lnum = '1';
                $bayar->jml_bayar = $dbayar['jml_bayar'];
                if (!$bayar->save())
                    return array('type' => 'E', 'message' => $bayar->getErrors());
            } else {
                $criteria = new CDbCriteria;
                $criteria->select = 'lnum';
                $criteria->condition = 'purch_num=:purch_num';
                $criteria->condition .= ' AND lnum = (SELECT MAX(lnum) FROM minidb.fico_bayar WHERE purch_num=:purch_num)';
                $criteria->params = array(':purch_num' => $dbayar['purch_num']);
                $criteria->order = 'lnum DESC';
                $lnumplus = FicoBayar::model()->find($criteria);
                $lnumplus = $lnumplus->lnum + 1;

                $bayar = new FicoBayar;
                $bayar->cdfigl = $dbayar['cdfigl'];
                $bayar->purch_num = $dbayar['purch_num'];
                $bayar->cdvend = $dbayar['cdvend'];
                $bayar->lnum = $lnumplus;
                $bayar->jml_bayar = $dbayar['jml_bayar'];
                if (!$bayar->save())
                    return array('type' => 'E', 'message' => $bayar->getErrors());
            }
            return array('type' => 'S', 'message' => 'Pembayaran sukses dilakukan');
        } catch (ErrorException $e) {
            print_r($e->getMessage());
            $retval = array('type' => 'E', 'message' => $e->getMessage());
            return $retval;
        }
    }

    public function cancelHutang($dthutang = array()) {
        try {
            $hdrmodel = new FicoHutang;
            $criteria = new CDbCriteria;
            $criteria->condition = 'purch_num=:purch_num AND cdvend=:cdvend';
            $criteria->params = array(':purch_num' => $dthutang['purch_num'], ':cdvend' => $dthutang['cdvend']);

            $hutang = $hdrmodel->find($criteria);
            $hutang->status = -1;

            if (!$hutang->save())
                return array('type' => 'E', 'message' => $hutang->getErrors());
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

}

?>
