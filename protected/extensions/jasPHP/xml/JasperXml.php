<?php

/**
 * JasperXml contains functions to help manage xml tags
 *
 * @author Eric Maicon <eric@ericmaicon.com.br>
 * @version $Id: JasperXml.php 1 2012-02-24 09:03:02 ericmaicon $
 * @package 
 * @since 1.0
 */
class JasperXml {

    public $jasper;
    public $dbResult;
    public $config;
    public $parameters;
    private $variables;
    public $directory;

    /**
     * Print Jasper
     */
    public function printJasper($left = 0, $top = 0, $subreport = false) {
        $return = new XmlReturn();

        if (!$subreport) {
            $return->content = XmlContent::headerReportContent($this->jasper);
        } else {
            $return->content = XmlContent::headerSubReportContent($this->jasper);
        }

        $topTemp = 0;

        if ($this->dbResult) {

            //gettingVars
            $this->variables = $this->getVariables();

            $return->top += $top;

            //getting the itens from columnHeader`s band:
            if ($this->jasper->columnHeader) {
                $band = $this->getItensFromBand($this->jasper->columnHeader->band, $this->dbResult[0], $left, $return->top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->columnHeader->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->columnHeader->band->height;
                }
            }

            //getting the itens from title`s band:
            if ($this->jasper->title) {
                $band = $this->getItensFromBand($this->jasper->title->band, $this->dbResult[0], $left, $return->top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->title->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->title->band->height;
                }
            }

            $topTemp = $return->top;

            //getting the itens from detail`s band:
            if ($this->jasper->detail) {

                foreach ($this->dbResult as $row) {
                    $band = $this->getItensFromBand($this->jasper->detail->band, $row, $left, $topTemp);
                    $return->content .= $band->content;

                    if ($band->top > $this->jasper->detail->band->height) {
                        $topTemp += $band->top;
                    } else {
                        $topTemp += $this->jasper->detail->band->height;
                    }
                }
            }

            //getting the itens from columnFooter`s band:
            if ($this->jasper->columnFooter) {
                $band = $this->getItensFromBand($this->jasper->columnFooter->band, $this->dbResult[0], $left, $topTemp);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->columnFooter->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->columnFooter->band->height;
                }
            }

            //getting the itens from background's band:
            if ($this->jasper->background) {
                $band = $this->getItensFromBand($this->jasper->background->band, $this->dbResult[0], $left, $top, $topTemp - $top);
                $return->content .= $band->content;
            }
        } else {
            //getting the itens from noData`s band:
            if ($this->jasper->noData) {
                $band = $this->getItensFromBand($this->jasper->noData->band, null, $left, $top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->noData->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->noData->band->height;
                }
            }

            if ($this->jasper->columnHeader) {
                $band = $this->getItensFromBand($this->jasper->columnHeader->band, null, $left, $return->top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->columnHeader->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->columnHeader->band->height;
                }
            }

            //getting the itens from title`s band:
            if ($this->jasper->title) {
                $band = $this->getItensFromBand($this->jasper->title->band, null, $left, $return->top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->title->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->title->band->height;
                }
            }

            $topTemp = $return->top;

            //getting the itens from detail`s band:
            if ($this->jasper->detail) {
                $band = $this->getItensFromBand($this->jasper->detail->band, null, $left, $return->top);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->detail->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->detail->band->height;
                }
            }

            //getting the itens from columnFooter`s band:
            if ($this->jasper->columnFooter) {
                $band = $this->getItensFromBand($this->jasper->columnFooter->band, null, $left, $topTemp);
                $return->content .= $band->content;

                if ($band->top > $this->jasper->columnFooter->band->height) {
                    $return->top += $band->top;
                } else {
                    $return->top += $this->jasper->columnFooter->band->height;
                }
            }

            //getting the itens from background's band:
            if ($this->jasper->background) {
                $band = $this->getItensFromBand($this->jasper->background->band, null, $left, $top, $topTemp - $top);
                $return->content .= $band->content;
            }
        }

        if (!$subreport) {
            $return->content .= XmlContent::footerReportContent($this->jasper);
        } else {
            $return->content .= XmlContent::footerSubReportContent($this->jasper);
        }


        if (!$subreport) {
            $this->generatePdf($return->content);
        } else {
            $return->top = $topTemp - $top;
            $return->variables = $this->variables;
            return $return;
        }
    }

    private function getVariables() {
        $return = array();

        if (is_array($this->jasper->variable)) {
            foreach ($this->jasper->variable as $var) {
                if ($var->variableExpression) {
                    $return[$var->name] = $this->getVariableValue($var->calculation, $var->variableExpression->content);
                } else {
                    $return[$var->name] = null;
                }
            }
        } else {
            $var = $this->jasper->variable;

            if ($var) {
                if ($var->variableExpression) {
                    $return[$var->name] = $this->getVariableValue($var->calculation, $var->variableExpression->content);
                } else {
                    $return[$var->name] = null;
                }
            }
        }

        return $return;
    }

    private function getVariableValue($type, $content) {
        $value = 0;

        foreach ($this->dbResult as $row) {
            $textUtils = new TextUtils($row, $this->parameters, $this->variables);
            $valueTemp = $textUtils->changeFieldValue($content);


            //TODO FAzer os outros 
            //TODO Juntar com PdfUtils::getReturnValues
            if ($type == 'Sum') {
                $value += $valueTemp;
            }
        }

        return $value;
    }

    /**
     * print the fields
     */
    private function getItensFromBand($band, $row = null, $left = 0, $top = 0, $height = 0) {
        $return = new XmlReturn();

        $attributes = JasperReflection::getVars($band);

        $attributeValue = null;
        foreach ($attributes as $attributeName => $attributeValue) {
            switch ($attributeName) {

                case 'image':
                    $returnTemp = $this->getComponent($band->image, 'printImage', array($left, $top, $this->getConfig('IMAGE_DIR'), $row, $this->parameters, $this->variables));
                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    break;

                case 'rectangle':
                    $returnTemp = $this->getComponent($band->rectangle, 'printRectangle', array($left, $top, $height));
//                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    break;

                case 'line':
                    $returnTemp = $this->getComponent($band->line, 'printLine', array($left, $top));
                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    break;

                case 'textField':
                    $returnTemp = $this->getComponent($band->textField, 'printTextField', array($left, $top, $row, $this->parameters, $this->variables));
                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    break;

                case 'staticText':
                    $returnTemp = $this->getComponent($band->staticText, 'printStaticText', array($left, $top));
                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    break;

                case 'subreport':
                    //TODO why 4?
                    $returnTemp = $this->getComponent($band->subreport, 'printSubreport', array($left, $top + 1, $this->directory, $row, $this->parameters, $this->variables, $this->config));
                    $return->top += $returnTemp->top;
                    $return->content .= $returnTemp->content;
                    $return->variables = $returnTemp->variables;
                    break;

                case 'height':
                case 'splitType':
                    break;

                default:
                    throw new \br\com\ericmaicon\exception\JasperReaderException("There`s some component without constructor here: " . $attributeName);
                    break;
            }
        }

//        $return->top += $top;

        return $return;
    }

    private function getComponent($components, $function, $parameters) {
        $return = new XmlReturn();

        if ($components) {

            if (is_array($components)) {
                $tempTop = 0;

                foreach ($components as $component) {

                    $parametersTemp = $parameters;
                    array_push($parametersTemp, $component);

                    $returnTemp = PdfUtils::$function($parametersTemp);
                    $return->content .= $returnTemp->content;
                    if (count($returnTemp->variables) > 0) {
                        $this->sendSubReportVariablesToReportVariables($returnTemp->variables);
                    }
                    if ($returnTemp->top > $tempTop) {
                        $tempTop = $returnTemp->top;
                    }
                }
                $return->top += $tempTop;
            } else {
                array_push($parameters, $components);

                $returnTemp = PdfUtils::$function($parameters);
                $return->content .= $returnTemp->content;
                if (count($returnTemp->variables) > 0) {
                    $this->sendSubReportVariablesToReportVariables($returnTemp->variables);
                }
                $return->top += $returnTemp->top;
            }
        }

        return $return;
    }

    private function getConfig($configName) {

        if ($this->config) {
            if (array_key_exists($configName, $this->config)) {
                foreach ($this->config as $attributeName => $attributeValue) {
                    if ($attributeName == $configName) {
                        return $attributeValue;
                    }
                }
            } else {
                $attributes = JasperReflection::getVars("Constant");

                foreach ($attributes as $attributeName => $attributeValue) {
                    if ($attributeName == $configName) {
                        return $attributeValue;
                    }
                }
            }
        }

        return null;
    }

    private function sendSubReportVariablesToReportVariables($variables) {
        foreach ($variables as $varName => $varValue) {
            $this->variables[$varName] = $varValue;
        }
    }

    private function generatePdf($content) {
        $obj = new Xml2Pdf($content);
        $pdf = $obj->render();
        $pdf->Output();
    }

}