<?php

class FileUtils {
    
    public static function createDom($name) {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = FALSE;
        $doc->load($name);
        
        return $doc;
    }
    
    public static function readFile($name) {
        if (!file_exists($name)) {
            throw new JasperReaderException("This file doesn`t exists: " . $name);
        }

        return self::createDom($name);
    }
}