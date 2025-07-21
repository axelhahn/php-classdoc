#!/usr/bin/php
<?php 
/**
 * PARSE CLASS AND GENERATE DOC PAGE
 * 
 * @author axelhahn
 * @license GNU GPL 3.0
 * 
 * @source <https://github.com/axelhahn/php-classdoc>
 * 
 * 2024-07-15  v0.1  axelhahn  initial version
 * 2025-07-21        axelhahn
 */

// ------------------------------------------------------------
// CONFIG
// ------------------------------------------------------------

global $_bDebug; $_bDebug=false;

$outtype='md';
// $sourceurl='';

// ------------------------------------------------------------
// FNUCTTIONS
// ------------------------------------------------------------

/**
 * Write given debug text to STDERR
 * @param string $s
 * @return void
 */
function _wd(string $s): void
{
    global $_bDebug;
    if ($_bDebug) {
        // echo "DEBUG: $s".PHP_EOL;
        file_put_contents('php://stderr', "DEBUG: 🔹 $s".PHP_EOL,FILE_APPEND);
    }
}

/**
 * Show help
 * @return void
 */
function showHelp(){
    echo "
Axels docpage generator for php class files.

USAGE: parse-class.php [OPTIONS] <classfile.php> [<classname>]

OPTIONS:
    -h, --help            show this help

    -d, --debug           enable debug output (written on STDERR)
    -o, --out <type>      set output type: 'md' (default) or 'html'
    -s, --source <url>    set url of source file in main branch; default: none

PARAMETERS:
    <classfile.php>       path to class file
    <classname>           optional: if classname is not detected you can set 
                          it manually

";
}

// ------------------------------------------------------------
// MAIN
// ------------------------------------------------------------

if (!isset($argv[1])){
    echo "ERROR: no class file given".PHP_EOL;
    showHelp();
    exit(1);
}

array_shift($argv);
foreach ($argv as $argument) {
    _wd("Processing param: $argument");
    if(preg_match('/^\-/', $argument)){
        switch ($argument) {
            case '-h':
            case '--help':
                showHelp();
                exit(0);
                break;

            case '-d':
            case '--debug':
                $_bDebug=true;
                _wd("Enable debugging");
                array_shift($argv);
                break;

            case '-o':
            case '--out':
                _wd("Set output type to '".$argv[1]."'");
                $outtype=$argv[1];
                array_shift($argv);
                array_shift($argv);
                break;

            case '-s':
            case '--source':
                _wd("Set source url to '".$argv[1]."'");
                $sourceurl=$argv[1];
                array_shift($argv);
                array_shift($argv);
                break;
        }
    }
}



$sClassfile=$argv[0] ?? "";
$sClass=$argv[1] ?? "";
_wd("Info: class file is $sClassfile");

_wd("Check existence of file [$sClassfile]");
if(!file_exists($sClassfile)){
    echo "ERROR: file not found: $sClassfile".PHP_EOL;
    exit(2);
}

_wd("Loading src/phpclass-parser.class.php");
require "src/phpclass-parser.class.php";


// ---------- load class and parse it

$oParser=new axelhahn\phpclassparser();
if(!$sClass){
    _wd("Detect classname and init it: $sClassfile");
    if(!$sClass=$oParser->setClassFile($sClassfile)){
        echo "ERROR: class not detected in file: $sClassfile".PHP_EOL;
        echo "Use a 2nd parameter with the classname".PHP_EOL;
        exit(1); 
    }
} else {
    _wd("Load file $sClassfile");
    require_once $sClassfile;

    _wd("Init parser with param 2: $sClass");
    $oParser->setClassname($sClass);
}
if($sourceurl){
    $oParser->setSourceUrl($sourceurl);
}
// ---------- generate output

$sOut="";

// print_r($oParser->getProperties()); die();

// ----- properties

_wd("Processing properties");
$sOutProperties='';
$aPReplace=[];
foreach($oParser->getProperties() as $aProperty){
    foreach($aProperty as $sKey => $value){
        if(!is_array($value)){
            $aPReplace['{{'.$sKey.'}}']=$value;
        }        
    }
    // print_r($aPReplace);
    $sOutProperties.=str_replace(
        array_keys($aPReplace), 
        array_values($aPReplace), 
        file_get_contents("config/$outtype/properties.tpl")
    );
}

// ----- methods

_wd("Processing methods");

$sOutMethods="";
foreach($oParser->getMethods() as $sMethodname => $aMethod){
    _wd("Method: $sMethodname");
    $aMReplace=[];
    
    foreach($aMethod as $sKey => $value){
        
        if(!is_array($value)){
            $aMReplace['{{'.$sKey.'}}']=$value;
        }
    }

    $aPReplace=[];
    $sOutParams="";
    foreach($aMethod['parameters'] as $aParam){
        foreach ($aParam as $sKeyP => $valueP) {
            if(!is_array($valueP)){
                $aPReplace['{{'.$sKeyP.'}}']=$valueP;
            }       
        }
        $sOutParams.=str_replace(
            array_keys($aPReplace), 
            array_values($aPReplace), 
            file_get_contents("config/$outtype/parameter.tpl")
        );
        // _wd($sOutParams);
    }
    if($sOutParams){
        $sOutParams=str_replace(
            '{{parameter.tpl}}',
            $sOutParams,
            file_get_contents("config/$outtype/parameters.tpl")
        );
    }
    $aMReplace['{{parameters.tpl}}']=$sOutParams;
    $sOutMethods.=str_replace(
        array_keys($aMReplace), 
        array_values($aMReplace), 
        file_get_contents("config/$outtype/methods.tpl")
    );

    // echo $sOut; print_r($aMReplace); die();


}

// ----- docpage



_wd("Merging docpage for $sClass...");
$aPageReplace=[];
foreach($oParser->getClassInfos() as $sKey => $value){
        
    if(!is_array($value)){
        $aPageReplace['{{'.$sKey.'}}']=$value;
    }
}

$aPageReplace['{{name}}']=$sClass;
$aPageReplace['{{properties.tpl}}']=$sOutProperties ? $sOutProperties : '(none)';
$aPageReplace['{{methods.tpl}}']=$sOutMethods ? $sOutMethods : '(none)';

$sOut=str_replace(
    array_keys($aPageReplace),
    array_values($aPageReplace),
    file_get_contents("config/$outtype/index.tpl")
);


echo $sOut;
