<?php
class jasPHP extends CApplicationComponent {

    public function create($reportDir, $reportName, $parameters, $config = null) {
        $xml = new JasperReader();
        $xml->read($reportDir, $reportName, $parameters, $config);
    }

}
