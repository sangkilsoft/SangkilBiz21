<?php
/**
 * JasperReader display a jrxml file in PDF file
 *
 * @author Eric Maicon <eric@ericmaicon.com.br>
 * @version $Id: JasperReader.php 1 2012-02-24 09:03:02 ericmaicon $
 * @package 
 * @since 1.0
 */
Yii::import("ext.jasPHP.annotation.BeanAnnotation");
Yii::import("ext.jasPHP.annotation.TagAnnotation");

Yii::import("ext.jasPHP.bean.AbstractJasper");
Yii::import("ext.jasPHP.bean.Jasper");
Yii::import("ext.jasPHP.bean.JasperBackground");
Yii::import("ext.jasPHP.bean.JasperBand");
Yii::import("ext.jasPHP.bean.JasperColumnFooter");
Yii::import("ext.jasPHP.bean.JasperColumnHeader");
Yii::import("ext.jasPHP.bean.JasperConnectExpression");
Yii::import("ext.jasPHP.bean.JasperDefaultValueExpression");
Yii::import("ext.jasPHP.bean.JasperDetail");
Yii::import("ext.jasPHP.bean.JasperField");
Yii::import("ext.jasPHP.bean.JasperFont");
Yii::import("ext.jasPHP.bean.JasperImage");
Yii::import("ext.jasPHP.bean.JasperImageExpression");
Yii::import("ext.jasPHP.bean.JasperLine");
Yii::import("ext.jasPHP.bean.JasperNoData");
Yii::import("ext.jasPHP.bean.JasperParameter");
Yii::import("ext.jasPHP.bean.JasperProperty");
Yii::import("ext.jasPHP.bean.JasperQueryString");
Yii::import("ext.jasPHP.bean.JasperRectangle");
Yii::import("ext.jasPHP.bean.JasperReportElement");
Yii::import("ext.jasPHP.bean.JasperReturnValue");
Yii::import("ext.jasPHP.bean.JasperStaticText");
Yii::import("ext.jasPHP.bean.JasperSubreport");
Yii::import("ext.jasPHP.bean.JasperSubreportExpression");
Yii::import("ext.jasPHP.bean.JasperSubreportParameter");
Yii::import("ext.jasPHP.bean.JasperSubreportParameterExpression");
Yii::import("ext.jasPHP.bean.JasperText");
Yii::import("ext.jasPHP.bean.JasperTextElement");
Yii::import("ext.jasPHP.bean.JasperTextField");
Yii::import("ext.jasPHP.bean.JasperTextFieldExpression");
Yii::import("ext.jasPHP.bean.JasperTitle");
Yii::import("ext.jasPHP.bean.JasperVariable");
Yii::import("ext.jasPHP.bean.JasperVariableExpression");

Yii::import("ext.jasPHP.constants.Constant");

Yii::import("ext.jasPHP.db.DbConnection");

Yii::import("ext.jasPHP.exception.JasperReaderException");

Yii::import("ext.jasPHP.utils.AnnotationUtils");
Yii::import("ext.jasPHP.utils.BeanUtils");
Yii::import("ext.jasPHP.utils.FileUtils");
Yii::import("ext.jasPHP.utils.PdfUtils");
Yii::import("ext.jasPHP.utils.PixelUtils");
Yii::import("ext.jasPHP.utils.TextUtils");

Yii::import("ext.jasPHP.reflection.JasperReflection");

Yii::import("ext.jasPHP.xml.JasperXml");
Yii::import("ext.jasPHP.xml.XmlContent");
Yii::import("ext.jasPHP.xml.XmlReturn");

Yii::import("ext.jasPHP.libs.xml2pdf.Xml2Pdf");

class JasperReader {

    public function dbConnection($url, $username, $password) {
        DbConnection::getInstance()->connect($url, $username, $password);
    }
    
    /**
     * Call all the necessary methods
     */
    public function read($directory, $fileName, $parameters, $config = array(), $left=0, $top=0, $subreport=false) {

        //geting the bean
        $xmlContent = FileUtils::readFile($directory . trim($fileName));
        
        //Turn the jrxml to bean
        $jasper = BeanUtils::xmlToBean($xmlContent, 'Jasper');
        
        //Get the db stuff
        $dbResult = null;
        if($jasper->queryString) {
            $dbResult = DbConnection::getInstance()->listAll($jasper->queryString->content, $parameters);
        }

        $left += $jasper->leftMargin;
        $top += $jasper->topMargin;
        
        $jasperXml = new JasperXml();
        $jasperXml->dbResult = $dbResult;
        $jasperXml->config = $config;
        $jasperXml->jasper = $jasper;
        $jasperXml->parameters = $parameters;
        $jasperXml->directory = $directory;
        return $jasperXml->printJasper($left, $top, $subreport);
    }

}