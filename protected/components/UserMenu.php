<?php

Yii::import('zii.widgets.CMenu');

class UserMenu extends CMenu {

    public $title = 'User Menu';
    public $_menu = array();

    public function init() {
        $this->title = $this->title;        
        $this->_menu = array(
            'master'=>array(
                array('val' => 'Organization', 'url' => 'sysapp/Organization', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Unit Kerja', 'url' => 'sysapp/unit', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Warehouse', 'url' => 'sysapp/wrhouse', 'img'=>'/images/ico/edit-list.png'),
                //array('val' => 'Locator', 'url' => 'sysapp/lctor'),
                array('val' => 'Item Uom', 'url' => 'mditem/itemUom', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Item Group', 'url' => 'mditem/itemGroup', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Item Category', 'url' => 'mditem/itemCat', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Item Master', 'url' => 'mditem/items', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Vendor Category', 'url' => 'mditem/vendorCat', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Vendor List', 'url' => 'mditem/vendorList', 'img'=>'/images/ico/edit-list.png'),
            ),
            'purc'=>array(
                array('val' => 'Pembelian Barang', 'url' => 'inv/purch', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Pembayaran Hutang', 'url' => 'purc/bayar', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Detail Hutang ', 'url' => 'purc/hutang', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Rekap Hutang ', 'url' => 'purc/rkphutang', 'img'=>'/images/ico/document-attribute-r.png'),
            ),
            'inv'=>array(
                array('val' => 'Stock Transfer', 'url' => 'inv/sto', 'img'=>'/images/ico/tick.png'),
                //array('val' => 'Pengeluaran Barang', 'url' => 'inv/gi', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Penerimaan Barang', 'url' => 'inv/gr', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Stock Opname', 'url' => 'inv/sto', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Barcode Label', 'url' => 'inv/gr', 'img'=>'/images/ico/barcode.png'),
                array('val' => 'Daftar Transfer', 'url' => 'inv/storeport', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Penelusuran Stock', 'url' => 'inv/trc', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Daftar Stock', 'url' => 'inv/sto', 'img'=>'/images/ico/document-attribute-r.png'),
            ),
            'sales'=>array(
                array('val' => 'Price Category', 'url' => 'sales/priceCat', 'img'=>'/images/ico/wrench-screwdriver.png'),
                array('val' => 'Price Detail', 'url' => 'sales/priceItem', 'img'=>'/images/ico/wrench-screwdriver.png'),
                array('val' => 'Penjualan Retail', 'url' => 'retail/retail', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Penjualan Grosir', 'url' => 'sales/grosir', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Retur Penjualan', 'url' => 'sales/retur', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Penerimaan Piutang', 'url' => 'sysapp/unit', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Laporan Penjualan', 'url' => 'sales/salesDtl', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Laporan Piutang', 'url' => 'sysapp/unit', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Rekap Penjualan', 'url' => 'sysapp/unit', 'img'=>'/images/ico/document-attribute-r.png'),
            ),
            'site'=>array(
            ),
            'fico'=>array(
                array('val' => 'Chart of Account', 'url' => 'fico/coangroup', 'img'=>'/images/ico/edit-list.png'),
                array('val' => 'Setup Periode', 'url' => 'fico/periode', 'img'=>'/images/ico/tick.png'),
//                array('val' => 'Cash In', 'url' => 'fico/glentri', 'img'=>'/images/ico/tick.png'),
//                array('val' => 'Cash Out', 'url' => 'fico/glentri', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Entri Journal', 'url' => 'fico/glentri', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Closing Periode', 'url' => 'fico/closing', 'img'=>'/images/ico/tick.png'),
                array('val' => 'Laporan Journal Detail', 'url' => 'fico/rptgl', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Laporan Buku Besar', 'url' => 'fico/rptbesar', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Laporan Laba/Rugi', 'url' => 'fico/rptlr', 'img'=>'/images/ico/document-attribute-r.png'),
                array('val' => 'Laporan Neraca', 'url' => 'fico/rptbalance', 'img'=>'/images/ico/document-attribute-r.png'),
            ),
            'adm'=>array(
                array('val' => 'Users', 'url' => 'sysadmin/users', 'img'=>'/images/ico/users.png'),
                array('val' => 'User to unit', 'url' => 'sysadmin/userunit', 'img'=>'/images/ico/users.png'),
                array('val' => 'Auth & Autorization', 'url' => 'fico/periode', 'img'=>'/images/ico/user-worker-boss.png'),
                array('val' => 'Number Generator', 'url' => 'sysadmin/numgen', 'img'=>'/images/ico/sort-number.png'),
                array('val' => 'LookUp Management', 'url' => 'sysadmin/vlookup', 'img'=>'/images/ico/wrench-screwdriver.png'),
                
                
            ),
        );        
        parent::init();
    }

    protected function renderMenu() {
        echo "<li><h2>$this->title</h2>";
        $this->render('userMenu');
    }

}

?>