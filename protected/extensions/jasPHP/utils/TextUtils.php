<?php

class TextUtils {

    private $replacement = array("+", "new BigDecimal", "new MathContext", "new String");
    private $row;
    private $parameters;
    private $variables;

    public function __construct($row, $parameters, $variables) {
        $this->row = $row;
        $this->parameters = $parameters;
        $this->variables = $variables;
    }

    public function changeFieldValue($finalVar) {
        if ($finalVar) {
            $finalVar = $this->FTreatment($finalVar);
            $finalVar = $this->PTreatment($finalVar);
            $finalVar = $this->VTreatment($finalVar);

            $finalVar = $this->gerenalTreatment($finalVar);

            return $finalVar;
        } else {
            return "";
        }
    }

    private function FTreatment($finalVar) {
        $matches = null;
        preg_match_all('/\$[F]\{[\w]*}/', $finalVar, $matches);

        if ($matches[0]) {

            foreach ($matches[0] as $match) {

                //tratando F = do banco
                $fieldName = str_replace(array("\$F{", "}"), "", $match);

                $value = $this->row[$fieldName];
                if (!$value) {
                    $value = '0';
                }

                if (is_numeric($value)) {
                    $finalVar = preg_replace('/\$F\{' . $fieldName . '\}/', $value, $finalVar);
                } else {
                    $finalVar = preg_replace('/\$F\{' . $fieldName . '\}/', '"' . $value . '"', $finalVar);
                }
            }
        }

        return $finalVar;
    }

    private function PTreatment($finalVar) {
        $matches = null;
        preg_match_all('/\$[P]\{[\w]*}/', $finalVar, $matches);

        if ($matches[0]) {

            foreach ($matches[0] as $match) {
                //tratando P = parametro
                if ($this->parameters) {
                    foreach ($this->parameters as $paramName => $paramValue) {
                        if ("\$P{" . $paramName . "}" == $match) {
                            $finalVar = preg_replace('/\$P\{' . $paramName . '\}/', $paramValue, $finalVar);
                            break;
                        }
                    }
                }
            }
        }

        return $finalVar;
    }

    private function VTreatment($finalVar) {
        $matches = null;
        preg_match_all('/\$[V]\{[\w]*}/', $finalVar, $matches);

        if ($matches[0]) {

            foreach ($matches[0] as $match) {
                //tratando V = variaveis
                if ($this->variables) {
                    foreach ($this->variables as $paramName => $paramValue) {
                        if (!$paramValue) {
                            $paramValue = 0;
                        }

                        if ("\$V{" . $paramName . "}" == $match) {
                            $finalVar = preg_replace('/\$V\{' . $paramName . '\}/', $paramValue, $finalVar);
                            break;
                        }
                    }
                }
            }
        }

        return $finalVar;
    }

    private function gerenalTreatment($finalVar) {
        //Tratando os valores irrelevantes:
        $finalVar = str_replace($this->replacement, "", $finalVar);

        //sum
        $finalVar = str_replace(".add", " + ", $finalVar);

        //divide
        $divideMatch = null;
        if (preg_match('/\.divide\([\w\$\{\}\(\)]+[\s,]+[\w\$\{\}\(\)]+\)/', $finalVar, $divideMatch)) {
            $finalVar = str_replace(".divide", "/", $finalVar);
            $finalVar = preg_replace("/(,\s)+\([0-9]+\)/", "", $finalVar);
            $finalVar = preg_replace("/(,)+\([0-9]+\)/", "", $finalVar);
        }

        //subtract
        $finalVar = str_replace(".subtract", " - ", $finalVar);

        //equals
        $finalVar = str_replace(".equals", " == ", $finalVar);

        $ternarioMatch = null;
        if (preg_match_all('/.*[!|=]=.*\?.*:.*/', $finalVar, $ternarioMatch)) {
            $evalVar = null;
            eval('$evalVar = ' . $finalVar . ";");
            return $evalVar;
        }

        //date
        if ($finalVar == 'new Date()') {
            return date('d/m/Y h:i');
        }

        //aspas
        $finalVar = str_replace("\"", "", $finalVar);

        return $finalVar;
    }

}