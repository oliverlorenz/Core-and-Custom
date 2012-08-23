<?php

// simple autoload
function __autoload($strClassName) {
    $objAutoloader = new Autoloader($strClassName);
    $strPath = $objAutoloader->load();
    require_once $strPath;
}

// autoloader class
class Autoloader
{
    protected $strClassName;
    protected $arrClassPath;
    protected $arrLevels;
    
    public function __construct($strClassName, $arrOptions = array()) {
        
        // take classname
        if(!empty($strClassName)) {
            $this->strClassName = $strClassName;
        }
        
        // define levels
        if(!empty($arrOptions['levels'])) {
            $this->arrLevels = $arrOptions['levels'];
        } else {
            $this->setStandardLevels();
        }
    }
    
    protected function setStandardLevels() {
        $this->arrLevels = array(
            'custom',
            'core'
        );
    }
    
    public function load() {
        $strPath = '';
        $strRealClassName = '';

        if($this->isPrefixedClassName()) {
            $strPath = $this->getClassPath();
            $strRealClassName = $this->strClassName;
        } else {
            $arrClassPathInfos = $this->getExistingClassInformations();
            $strRealClassName = implode('_', $arrClassPathInfos['classpathparts']);
            $strPath = $arrClassPathInfos['classpath'];
        }
        if(!empty($strPath)) {
            if($this->strClassName != $strRealClassName) {
                $strRequireClass = 'class ' . $this->strClassName . ' extends ' . $strRealClassName . '{}';
                eval($strRequireClass);
            }
        }
        return $strPath;
    }
    
    protected function validateClasses() {
        $arrReturn = array();
        foreach($this->arrLevels as $strLevel) {
            $arrClassPathInfos = $this->buildClassInformations($strLevel);
            if(!empty($arrClassPathInfos['classpath'])
            ) {
                
                $arrReturn = $arrClassPathInfos;
            }
        }
        if(empty($arrReturn)) {
            die($this->strClassName . ' not found!');
        }
        return $arrReturn;
    }
    
    protected function getExistingClassInformations() {
        $arrReturn = array();
        foreach($this->arrLevels as $strLevel) {
            $arrClassPathInfos = $this->buildClassInformations($strLevel);
            if(!empty($arrClassPathInfos['classpath']) &&
            file_exists($arrClassPathInfos['classpath'])
            ) {
                if($this->bolDevelopmentMode) {
                    stable_ol_Development::isValidFile($arrClassPathInfos['classpath']);
                }
                $arrReturn = $arrClassPathInfos;
                break;
            }
        }
        if(empty($arrReturn)) {
            die($this->strClassName . ' not found!');
        }
        return $arrReturn;
    }
    
    protected function buildClassInformations($strLevel) {
        $arrReturn = array(
            'classpath' => '',
            'classpathparts' => ''
        );
        if($this->isValidLevel($strLevel)) {
            $arrClassPath = array();
            $arrClassPath = $this->getClassPathParts();
            array_unshift($arrClassPath, $strLevel);
            $arrReturn['classpath'] = $this->getPathByClassPathParts($arrClassPath);
            $arrReturn['classpathparts'] = $arrClassPath;
        }
        return  $arrReturn;
    }
    
    protected function getClassPath() {
        $arrClassPath = $this->getClassPathParts();
        return $this->getPathByClassPathParts($arrClassPath);
    }
    
    protected function getPathByClassPathParts($arrClassPath) {
        return implode(DIRECTORY_SEPARATOR, $arrClassPath) . '.php';
    }
    
    protected function getClassPathParts() {
        if(empty($this->arrClassPath)) {
            $this->arrClassPath = explode('_', $this->strClassName);
        }
        return $this->arrClassPath;
    }
    
    protected function isValidLevel($strLevel) {
        if($this->isValidLevelName($strLevel)) {
            return in_array($strLevel, $this->arrLevels);
        } else {
            die($strLevel . ' is not a valid Levelname');
        }
    }
    
    protected function isValidLevelName($strLevel) {
        return !preg_match('/[_]/', $strLevel);
    }
    
    protected function isPrefixedClassName() {
        $arrClassPath = $this->getClassPathParts();
        return in_array($arrClassPath[0], $this->arrLevels);
    }
}

?>
