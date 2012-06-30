<?php
/**
 * JasperBand
 *
 * @author Eric Maicon <eric@ericmaicon.com.br>
 * @version $Id: JasperBand.php 1 2012-02-24 09:03:02 ericmaicon $
 * @package 
 * @since 1.0
 */
/** @TagAnnotation(tagName="band") */
class JasperBand extends AbstractJasper {
    
    /** @BeanAnnotation(className="JasperImage") */
    public $image = null;
    
    /** @BeanAnnotation(className="JasperRectangle") */
    public $rectangle = null;
    
    /** @BeanAnnotation(className="JasperLine") */
    public $line = null;
    
    /** @BeanAnnotation(className="JasperTextField") */
    public $textField = null;
    
    /** @BeanAnnotation(className="JasperStaticText") */
    public $staticText = null;
    
    /** @BeanAnnotation(className="JasperSubreport") */
    public $subreport = null;
    
    public $height = null;
    public $splitType = null;
}