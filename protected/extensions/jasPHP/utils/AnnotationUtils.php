<?php

//https://github.com/TXGruppi/FW/blob/master/fw.php
class AnnotationUtils {

    public static function hasAnnotation($class, $annotation) {
//        if(!file_exists(str_replace('\\','/',$class) . '.php')) {
//            throw new JasperReaderException("Class doesn`t exists (" . $class . ")");
//        }
        
        $reflectionClass = new \ReflectionClass($class);
        $comments = $reflectionClass->getDocComment();

        if (empty($comments))
            return false;

        $comments = explode("\n", $comments);
        foreach ($comments as $comment) {
            $comment = trim($comment);

            $matches = array();
            if (preg_match('/(?<=@)[a-zA-Z0-9]+/', $comment, $matches)) {
                if (is_numeric(array_search($annotation, $matches))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        return false;
    }

    public static function getAnnotation($class, $annotation) {

        $className = Constant::$ANNOTATION_NAMESPACE . $annotation;
        $return = new $className;

        $attributes = JasperReflection::getVars($return);

        $reflectionClass = new \ReflectionClass($class);
        $comments = $reflectionClass->getDocComment();

        if (empty($comments))
            return false;

        $comments = explode("\n", $comments);
        foreach ($comments as $comment) {
            $comment = trim($comment);

            $matches = array();
            if (preg_match('/(?<=@)[a-zA-Z0-9]+/', $comment, $matches)) {

                if (is_numeric(array_search($annotation, $matches))) {

                    if (preg_match_all('/(?<=[(\()|(,\s)])[a-zA-Z0-9]+/', $comment, $tagMatches)) {

                        $attributeValue = null;
                        foreach ($attributes as $attributeName => $attributeValue) {
                            $index = array_search($attributeName, $tagMatches[0]);

                            //if the attribute class exists in comments
                            if (is_numeric($index)) {
                                if (preg_match('/(?<=' . $attributeName . '=")[\w\\\]+/', $comment, $valuesMatches)) {
                                    $return->{$attributeName} = $valuesMatches[0];
                                }
                            }
                        }
                    }
                } else {
                    throw new JasperReaderException("This annotation doesn't exists");
                }
            } else {
                throw new JasperReaderException("This class doesn't have annotations");
            }
        }

        return $return;
    }

    public static function hasPropertyAnnotation($class, $annotation, $property) {
        $reflectionClass = new \ReflectionProperty($class, $property);
        $comments = $reflectionClass->getDocComment();
        
        if (empty($comments))
            return false;

        $comments = explode("\n", $comments);
        foreach ($comments as $comment) {
            $comment = trim($comment);

            $matches = array();
            if (preg_match('/(?<=@)[a-zA-Z0-9]+/', $comment, $matches)) {
                if (is_numeric(array_search($annotation, $matches))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        return false;
    }

    public static function getPropertyAnnotation($class, $annotation, $property) {

        $className = Constant::$ANNOTATION_NAMESPACE . $annotation;
        $return = new $className;

        $attributes = JasperReflection::getVars($return);

        $reflectionClass = new \ReflectionProperty($class, $property);
        $comments = $reflectionClass->getDocComment();

        if (empty($comments))
            return false;

        $comments = explode("\n", $comments);
        foreach ($comments as $comment) {
            $comment = trim($comment);

            $matches = array();
            if (preg_match('/(?<=@)[a-zA-Z0-9]+/', $comment, $matches)) {

                if (is_numeric(array_search($annotation, $matches))) {

                    if (preg_match_all('/(?<=[(\()|(,\s)])[a-zA-Z0-9]+/', $comment, $tagMatches)) {

                        $attributeValue = null;
                        foreach ($attributes as $attributeName => $attributeValue) {
                            $index = array_search($attributeName, $tagMatches[0]);

                            //if the attribute class exists in comments
                            if (is_numeric($index)) {
                                if (preg_match('/(?<=' . $attributeName . '=")[\w\\\]+/', $comment, $valuesMatches)) {
                                    $return->{$attributeName} = $valuesMatches[0];
                                }
                            }
                        }
                    }
                } else {
                    throw new JasperReaderException("This annotation doesn't exists");
                }
            } else {
                throw new JasperReaderException("This class doesn't have annotations");
            }
        }

        return $return;
    }

}