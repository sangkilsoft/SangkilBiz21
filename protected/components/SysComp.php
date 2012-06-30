<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SysComp extends CComponent {

    public static function getNumberDoc($cdnum, $ln=13, $uk='') {

        $mdl_num = SysNumgen::model()->findByPk($cdnum);

        if (!$mdl_num)
            throw new CHttpException(404, "Error on get model", 0);

        $pattr = strtolower($mdl_num->pattern);
        $year = date("y");
        $month = date("m");
        $dd = date("d");

        $pattr = str_replace("yy", $year, $pattr);
        $pattr = str_replace("mm", $month, $pattr);
        $pattr = str_replace("dd", $dd, $pattr);
        $last = "";
        if ($mdl_num->last_value == "") {
            $last = $mdl_num->startnum;
            if ($last == "")
                $last = "1";
        } else {
            $last_int = (int) $mdl_num->last_value;
            $last_int = $last_int + 1;
            $last = "" . $last_int;
        }

        $val = "";
        $jmlchar = strlen(strval((string) $last));
        $jmlchar = $jmlchar + strlen(strval((string) $pattr)) - 3;
        for ($i = 0; $i < ($ln - $jmlchar); $i++) {
            $val .= "0";
        }
        $val .= strval($last) . "";
        $last = $val;
        $nlast = $uk . $last;

        $pattr = str_replace("#num#", $nlast, $pattr);
        $mdl_num->last_value = $last;
        $mdl_num->Save();
        return $mdl_num->prefix . $pattr;
    }

    public static function getActiveUnit($uid) {
        $cdunit = "";
        $usereunit = Userunit::model()->FindAll('id=:id', array(':id' => $uid));
        $jml = count($usereunit);
        if (!$jml > 0)
            return array();
        
        $j = 0;
        foreach ($usereunit as $rows) {
            $cdunit .= "'" . $rows['cdunit'] . "'";
            if ($j <= $jml - 2)
                $cdunit .= ",";
            $j++;
        }
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdunit IN(' . $cdunit . ')';
        $criteria->order = 'cdunit ASC';
                
        $dtunit = SysUnit::model()->FindAll($criteria);
        $listnyo = CHtml::listData($dtunit, 'cdunit', 'dscrp');
        return $listnyo;
    }

    public static function getActiveWhse($uid) {
        $cdunit = "";
        $usereunit = TblUserunit::model()->FindAll('id=:id', array(':id' => $uid));
        $jml = count($usereunit);
        $j = 0;
        foreach ($usereunit as $rows) {
            $cdunit .= "'" . $rows['cdunit'] . "'";
            if ($j <= $jml - 2)
                $cdunit .= ",";
            $j++;
        }

        $dtunit = InvWarehouse::model()->FindAll('cdunit IN(' . $cdunit . ')');
        $listnyo = CHtml::listData($dtunit, 'cdwhse', 'dscrp');
        return $listnyo;
    }

}

?>
