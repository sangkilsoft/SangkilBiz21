<?php

class PdfUtils {

    public static function printImage($array) {

        $left = $array[0];
        $top = $array[1];
        $directory = $array[2];
        $row = $array[3];
        $parameters = $array[4];
        $variables = $array[5];
        $image = $array[6];

        $return = new XmlReturn();

        if ($image) {
            $reportElement = $image->reportElement;
            $imageExpression = $image->imageExpression;

            $y = $reportElement->y + $top;
            $x = $reportElement->x + $left;

            //tratando o nome
            $textUtils = new TextUtils($row, $parameters, $variables);
            $name = $textUtils->changeFieldValue($imageExpression->content);
            $name = $directory . $name;
            $return->content = "<image position='absolute' file='" . $name . "' top='" . $y . "' left='" . $x . "' height='" . $reportElement->height . "' width='" . $reportElement->width . "' />";
            $return->top = $reportElement->height;
        }

        return $return;
    }

    public static function printRectangle($array) {

        $left = $array[0];
        $top = $array[1];
        $height = $array[2];
        $rectangle = $array[3];

        $return = new XmlReturn();

        if ($rectangle) {

            $reportElement = $rectangle->reportElement;

            $y = $reportElement->y + $top;
            $x = $reportElement->x + $left;

            $fill = "";
            if ($reportElement->backcolor) {
                $fill = "fill='1' fillcolor='$reportElement->backcolor'";
            }

            if ($height == 0) {
                $height = $reportElement->height;
            }

            $return->content = "<table position='absolute' lineheight='" . $height . "' width='" . $reportElement->width . "' left='" . $x . "' top='" . $y . "' $fill>
                <th>
                    <td></td>
                </th>
            </table>";
            $return->top = $height;
        }

        return $return;
    }

    public static function printLine($array) {

        $left = $array[0];
        $top = $array[1];
        $line = $array[2];

        $return = new XmlReturn();

        if ($line) {

            $reportElement = $line->reportElement;

            $y = $reportElement->y + $top;
            $x = $reportElement->x + $left;

            $return->content = "<table position='absolute' lineheight='0' width='" . $reportElement->width . "' left='" . $x . "' top='" . $y . "' fill='1' fillcolor='#000000'>
                <th>
                    <td></td>
                </th>
            </table>";
        }

        return $return;
    }

    public static function printTextField($array) {

        $left = $array[0];
        $top = $array[1];
        $row = $array[2];
        $parameters = $array[3];
        $variables = $array[4];
        $textField = $array[5];

        $return = new XmlReturn();

        if ($textField) {
            $reportElement = $textField->reportElement;
            $textElement = $textField->textElement;
            $textFieldExpression = $textField->textFieldExpression;

            $y = $reportElement->y + $top;
            $x = $reportElement->x + $left;

            $textUtils = new TextUtils($row, $parameters, $variables);
            $value = $textUtils->changeFieldValue($textFieldExpression->content);


            //TODO melhorar
            if ($textField->pattern) {
                $pattern = str_replace('¤','R$',$textField->pattern);
                
                $value = Yii::app()->getLocale()->getNumberFormatter()->format($pattern, $value);
            }

            $style = "";
            if ($textElement->font && $textElement->font->isBold) {
                $style = " fontStyle='B' ";
            }

            $font = " fontSize='" . Constant::$DEFAULT_FONT_SIZE . "' ";
            $tempFont = Constant::$DEFAULT_FONT_SIZE;
            if ($textElement->font && $textElement->font->size) {
                $tempFont = $textElement->font->size;
                $font = " fontSize='" . $textElement->font->size . "' ";
            }

            //tamanho do texto escrito, para ver se vai cair para a segunda linha
            $fontWidth = strlen($value) * 2;
            if ($fontWidth > $reportElement->width) {
                $return->top = $tempFont + 3;
            }

            $align = "";
            if ($textElement->textAlignment) {
                $align = " textalign='" . substr($textElement->textAlignment, 0, 1) . "' ";
            }

            $return->content = "<paragraph font='helvetica' width='" . $reportElement->width . "' top='" . $y . "' left='" . $x . "' position='absolute' " . $align . " " . $font . " " . $style . ">" . $value . "</paragraph>";
        }

        return $return;
    }

    public static function printStaticText($array) {

        $left = $array[0];
        $top = $array[1];
        $static = $array[2];

        $return = new XmlReturn();

        if ($static) {
            $reportElement = $static->reportElement;
            $textElement = $static->textElement;

            $y = $reportElement->y + $top;
            $x = $reportElement->x + $left;

            $value = $static->text->content;

            $style = "";
            if ($textElement->font && $textElement->font->isBold) {
                $style = " fontStyle='B' ";
            }

            $font = " fontSize='" . Constant::$DEFAULT_FONT_SIZE . "' ";
            if ($textElement->font && $textElement->font->size) {
                $font = " fontSize='" . $textElement->font->size . "' ";
            }

            $align = "";
            if ($textElement->textAlignment) {
                $align = " textalign='" . substr($textElement->textAlignment, 0, 1) . "' ";
            }

            $return->content = "<paragraph font='helvetica' width='" . $reportElement->width . "' top='" . $y . "' left='" . $x . "' position='absolute' " . $align . " " . $font . " " . $style . ">" . $value . "</paragraph>";
        }

        return $return;
    }

    public static function printSubreport($array) {

        $left = $array[0];
        $top = $array[1];
        $directory = $array[2];
        $row = $array[3];
        $parameters = $array[4];
        $variables = $array[5];
        $config = $array[6];
        $subreport = $array[7];

        $return = new XmlReturn();

        if ($subreport) {

            $reportElement = $subreport->reportElement;
            $subreportExpression = $subreport->subreportExpression;
            $subreportParameter = $subreport->subreportParameter;
            $returnValue = $subreport->returnValue;

            $textUtils = new TextUtils($row, $parameters, $variables);
            $reportName = $textUtils->changeFieldValue($subreportExpression->content);
            $reportName = str_replace('.jasper', '.jrxml', $reportName);

            $subParameters = self::parametersToSend($subreportParameter, $row, $parameters, $variables);

            $left += $reportElement->x;
            $top += $reportElement->y;

            $jasperReader = new JasperReader();
            $return = $jasperReader->read($directory, $reportName, $subParameters, $config, $left, $top, true);

            //getting the return and sending to the main report
            $return->variables = self::getReturnValues($returnValue, $variables, $return->variables);
        }

        return $return;
    }

    private static function parametersToSend($subreportParameter, $row, $parameters, $variables) {
        $return = array();
        $textUtils = new TextUtils($row, $parameters, $variables);

        //sendding necessary parameters
        if (is_array($subreportParameter)) {
            foreach ($subreportParameter as $param) {
                $return[$param->name] = $textUtils->changeFieldValue($param->subreportParameterExpression->content);
            }
        } else {
            $return[$subreportParameter->name] = $textUtils->changeFieldValue($subreportParameter->subreportParameterExpression->content);
        }

        //sendding the master parameters
        foreach ($parameters as $parameterName => $parameterValue) {
            if (!array_key_exists($parameterName, $return)) {
                $return[$parameterName] = $parameterValue;
            }
        }

        return $return;
    }

    private static function getReturnValues($returnValue, $variables, $subReportVariable) {
        $returnTemp = array();

        foreach ($variables as $variableName => $variableValue) {
            if (is_array($returnValue)) {
                foreach ($returnValue as $return) {
                    if ($variableName == $return->toVariable && $subReportVariable) {
                        $returnTemp[$return->toVariable] = $variableValue;

                        //TODO Fazer os outros 
                        if ($return->calculation == 'Sum') {
                            if (!$returnTemp[$return->toVariable]) {
                                $returnTemp[$return->toVariable] = 0;
                            }

                            if (!array_key_exists($return->subreportVariable, $subReportVariable)) {
                                throw new \br\com\ericmaicon\exception\JasperReaderException("There isn`t the " . $return->subreportVariable . " in subreport!");
                            }

                            $returnTemp[$return->toVariable] += $subReportVariable[$return->subreportVariable];
                        }
                    }
                }
            } else {
                if ($returnValue) {
                    if ($variableName == $returnValue->toVariable && $subReportVariable) {
                        $returnTemp[$returnValue->toVariable] = $variableValue;

                        //TODO Fazer os outros
                        if ($returnValue->calculation == 'Sum') {
                            if (!$returnTemp[$returnValue->toVariable]) {
                                $returnTemp[$returnValue->toVariable] = 0;
                            }

                            $returnTemp[$returnValue->toVariable] += $subReportVariable[$returnValue->subreportVariable];
                        }
                    }
                }
            }
        }

        return $returnTemp;
    }

}