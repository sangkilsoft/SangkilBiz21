<?php

class BeanUtils {

    /**
     * Put all the attributes into a bean
     */
    public static function xmlToBean($xmlContent, $class, $elements = null) {
        $returnArray = array();

        if (AnnotationUtils::hasAnnotation($class, 'TagAnnotation')) {

            $tagName = AnnotationUtils::getAnnotation($class, 'TagAnnotation')->tagName;

            //important thing: when has more than one tag with same name, we return an array:
            if ($elements == null) {
                $elements = $xmlContent->getElementsByTagName($tagName);
            } else {
                $elements = $elements->getElementsByTagName($tagName);
            }

            foreach ($elements as $element) {
                $instance = new $class;

                //getting the reflection`s field
                $attributes = JasperReflection::getVars($instance);
                $attritubeValue = null;
                foreach ($attributes as $attritubeName => $attritubeValue) {

                    //Getting the annotation`s field
                    if (AnnotationUtils::hasPropertyAnnotation($instance, 'BeanAnnotation', $attritubeName)) {
                        $className = AnnotationUtils::getPropertyAnnotation($instance, 'BeanAnnotation', $attritubeName)->className;

                        $instance->{$attritubeName} = self::xmlToBean($xmlContent, $className, $element);
                    } else {
                        //if the annotation field doesn`t exists, we get the normal XML and put into the bean
                        $val = self::nodeToBean($attritubeName, $element);

                        if (is_numeric($val) && $attritubeName != 'size') {
                            $instance->{$attritubeName} = PixelUtils::pixeltoMm($val);
                        } else {
                            $instance->{$attritubeName} = $val;
                        }
                    }
                }

                array_push($returnArray, $instance);
            }
        } else {
            throw new JasperReaderException("There isnt`t tag specified in the class: " . $class);
        }

        if (count($returnArray) > 1) {
            return $returnArray;
        } else {
            if (count($returnArray) == 0) {
                return null;
            }

            return $returnArray[0];
        }
    }

    /**
     * Return an xml attribute
     */
    private static function nodeToBean($property, $element) {
        //if is an attribute we`ll return the value...or the value in the tags!!
        if ($property == 'content') {
            return $element->nodeValue;
        } else {
            return $element->getAttribute($property);
        }
    }

}