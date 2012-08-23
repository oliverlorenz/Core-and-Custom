<?php

class stable_ol_Development {
    public static $bolDevelopmentMode = true;
    
    public static function isValidFile($strPath) {
        $bolReturn = false;
        if(file_exists($strPath)) {
            $strFileContent = implode('\n', file($strPath));
            $bolReturn = self::fileIsEmpty($strFileContent);
        }
        
        return $bolReturn;
    }
    
    public static function fileIsEmpty($strFileContent) {
        
        $strReturnPattern       = '\n';
        $strReturnReplacement   = 'return';
        
        // Decorator Pattern:
        $strFileContent = trim($strFileContent);
        
        // delete starting php-Tag
        $strFileContent = preg_replace(
                                        '/' . preg_quote('<?php') . '/', 
                                        '', 
                                        $strFileContent
                                      );
        
        // delete ending php-Tag
        $strFileContent = preg_replace(
                                        '/' . preg_quote('?>') . '/', 
                                        '', 
                                        $strFileContent
                                      );
        
        // replace line-breaks
        $strFileContent = preg_replace(
                                        '/' . preg_quote($strReturnPattern) . '/', 
                                        $strReturnReplacement, 
                                        $strFileContent
                                      );
        
        // delete one-line comments
        $strFileContent = preg_replace("/(\/\/.*)/", "", $strFileContent);
        
        // delete multi-line comments
        $strFileContent = preg_replace("/(\/\*.*\*\/)/sU", "", $strFileContent);
        
        // re-replace line-breaks
        $strFileContent = preg_replace(
                                        '/' . preg_quote($strReturnReplacement) . '/', 
                                        $strReturnPattern, 
                                        $strFileContent
                                      );
        
    }
    
    public static function generateClassHead($arrClassParts) {
        
    }
}

?>
