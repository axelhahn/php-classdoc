<?php

namespace axelhahn;

/**
 * PHP CLASS PARSER
 * 
 * @author axelhahn
 * @license GNU GPL 3.0
 * 
 * @source <https://github.com/axelhahn/php-classdoc>
 * 
 * 2024-07-15  v0.1  axelhahn  initial version
 */

class phpclassparser
{

    /**
     * Class name to analyze
     * @var string
     */
    protected string $sClassname = '';

    /**
     * url of class file in main branch
     * @var string
     */
    protected string $sSourceUrl = '';

    protected object $oRefClass;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $sClassname optional: The name of the class. Default is an empty string.
     */
    public function __construct(string $sClassname = '')
    {
        if ($sClassname)
            $this->setClassname($sClassname);
    }

    /**
     * Sets the class file to be analyzed.
     * It will detect namespace and class name to initialize the class.
     *
     * @param string $file The path to the class file.
     * @return bool Returns true if the class name is successfully extracted from the file, false otherwise.
     */
    public function setClassFile(string $file): bool|string
    {

        // $sFiledata=substr(file_get_contents($file), 0 ,2000);
        $sFiledata=file_get_contents($file);

        $sContent = '';

        // remove all php comments
        $commentTokens = [T_COMMENT];

        if (defined('T_DOC_COMMENT')) {
            $commentTokens[] = T_DOC_COMMENT; // PHP 5
        }
        if (defined('T_ML_COMMENT')) {
            $commentTokens[] = T_ML_COMMENT;  // PHP 4
        }
        $tokens = token_get_all($sFiledata);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens)) {
                    continue;
                }
                $token = $token[1];
            }
            $sContent .= $token;
        }

        // detect "namespace" and "class"
        preg_match('@namespace\s+(\w+)@', $sContent, $aMatchNS);
        preg_match('@class\s+(\w+)@', $sContent, $aMatchClass);

        // echo "$sContent";
        // print_r($aMatchNS);
        // print_r($aMatchClass);
        // die();

        if (!isset($aMatchClass[1])) {
            return false;
        }
        $class = ($aMatchNS[1] ?? '' ) . '\\' . $aMatchClass[1];

        // https://stackoverflow.com/questions/7153000/get-class-name-from-file
        /*
        $fp = fopen($file, 'r');
        $class = $buffer = '';
        $namespace = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;
        
            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);
        
            if (strpos($buffer, '{') === false) continue;
        
            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }
        */
        if ($class) {
            require_once $file;
            $this->setClassname($class);
            return $class;
        }
        return false;
    }

    /**
     * Set a classname. 
     * You can use that method directly if the class file was loaded before. 
     * Or use setClassFile() to load the class file and detect the classname.
     * @see setClassFile()
     * @param string $sClassname  classname to access for doc generation
     * @return void
     */
    public function setClassname(string $sClassname): void
    {
        unset($this->oRefClass);
        $this->sClassname = $sClassname;
        try {
            $this->oRefClass = new \ReflectionClass($this->sClassname);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function setSourceUrl(string $sSourceUrl): void
    {
        $this->sSourceUrl = $sSourceUrl;
    }

    /**
     * Get metainformation for the class
     * @return array
     */
    public function getClassInfos(): array
    {
        $aReturn = [];
        if (!$this->oRefClass) {
            return $aReturn;
        }
        $aReturn['classname'] = $this->sClassname;
        $aReturn['namespace'] = $this->oRefClass->getNamespaceName();
        $aReturn['phpdoc'] = $this->parsePhpdocBlock($this->oRefClass->getDocComment());
        // $aReturn['comment'] = $aReturn['phpdoc']['filtered'];
        $aReturn['comment'] = $aReturn['phpdoc']['raw'];
        $aReturn['comment'] = $aReturn['phpdoc']['filtered'];

        return $aReturn;
    }

    /**
     * Get a hash of methods with its type, parameters, phpdoc infos
     * @param string $bPublicOnly  flag: public only methods or all; default: true (=only public methods)
     * @return array
     */
    public function getMethods($bPublicOnly = true): array
    {
        $aReturn = [];
        if (!$this->oRefClass) {
            return $aReturn;
        }

        foreach ($this->oRefClass->getMethods() as $o) {
            $aMethod = [];
            $sMethodname = $o->name;
            if (!$bPublicOnly == false && !$o->isPublic()) {
                continue;
            }
            $sType = '';
            if ($o->isPublic())
                $sType .= 'public ';
            if ($o->isPrivate())
                $sType .= 'private ';
            if ($o->isProtected())
                $sType .= 'protected ';
            if ($o->isStatic())
                $sType .= 'static ';
            if ($o->isAbstract())
                $sType .= 'abstract ';
            if ($o->isFinal())
                $sType .= 'final ';

            $oMethod = $this->oRefClass->getMethod($sMethodname);


            $iCount = 0;
            $iRequired = $oMethod->getNumberOfRequiredParameters();

            $aPhpDoc = $this->parsePhpdocBlock($oMethod->getDocComment());
            $aParams = [];
            foreach ($oMethod->getParameters() as $oParam) {
                $iCount++;

                $sPhpDoc4Param = isset($aPhpDoc['tags']['param'][$iCount - 1]) && $aPhpDoc['tags']['param'][$iCount - 1]
                    ? trim($aPhpDoc['tags']['param'][$iCount - 1])
                    : ''
                ;

                $sStringValue=($iCount <= $iRequired ? '\<required\>' : '\<optional\>')
                    . ' $'.$oParam->getName()
                    ;
                // see https://www.php.net/manual/en/class.reflectionparameter.php
                $aParam = [
                    'name' => $oParam->getName(),
                    'type' => $oParam->getType(),
                    'position' => $oParam->getPosition(),
                    'required' => $iCount <= $iRequired,
                    'default' => $iCount <= $iRequired ? NULL : $oParam->getDefaultValue(),
                    'raw' => $oParam->__toString(),
                    /*
                    'string' => str_replace(
                        ['<', '>'],
                        ['\<', '\>'],
                        preg_replace('@Parameter\ \#.*\[\ (.*)\ \]@', '$1', $oParam->__toString())
                    ),
                    */
                    'string' => $sStringValue,
                ];
                $aParam['phpdoc'] = $sPhpDoc4Param;
                $aParam['phpdoc_line'] = $sPhpDoc4Param;
                $aParam['phpdoc_type'] = $sPhpDoc4Param
                    ? preg_replace('/ .*/', '', $sPhpDoc4Param)
                    : ''
                ;
                // $aParam['phpdoc_descr'] = ($sPhpDoc4Param
                //     ? preg_replace('/^[a-z]* [\$A-Za-z0-9]* /', '', $sPhpDoc4Param)
                //     : ''
                // )
                // ;
                $aParam['phpdoc_descr'] = str_replace(chr(13), '<br>', $sPhpDoc4Param);

                $aParam['type'] = $aParam['type']
                    ? $aParam['type']
                    : $aParam['phpdoc_type'] . ' *'
                ;

                $aParams[] = $aParam;
            }

            // https://www.php.net/manual/en/reflectionfunctionabstract.getparameters.php
            $aMethod = [
                'type' => $sType,
                'name' => $sMethodname,
                'linefrom' => $o->getStartLine(),
                'lineto' => $o->getEndLine(),
                'lines' => $o->getEndLine() - $o->getStartLine() + 1,
                'sourceurl' => $this->sSourceUrl ? $this->sSourceUrl."#L".$o->getStartLine() : "",
                'comment' => $aPhpDoc['comment'] ?? '',
                'raw' => $aPhpDoc['raw'] ?? '',
                'parameters_count' => $oMethod->getNumberOfParameters(),
                'parameters_required' => $oMethod->getNumberOfRequiredParameters(),
                'parameters' => $aParams,
                'returntype' => $oMethod->getReturnType(),
                'attributes' => $oMethod->getAttributes(),
                'phpdoc' => $aPhpDoc,
            ];
            $aMethod['returntype'] = ($aMethod['returntype']
                ? $aMethod['returntype']
                : (isset($aPhpDoc['tags']['return'][0]) && $aPhpDoc['tags']['return'][0] > " "
                    ? preg_replace('/ .*/', '', trim($aPhpDoc['tags']['return'][0])) . ' *'
                    : ''
                )
            )
            ;
            $aMethod['returntype'] = $aMethod['returntype']?:'void';

            $aReturn[$sMethodname] = $aMethod;
        }
        ksort($aReturn);
        return $aReturn;
    }


    /**
     * Get a hash of properties with its type, phpdoc infos, default value, attributes, etc.
     *
     * @param bool $bPublicOnly flag: public only properties or all; default: true (=only public properties)
     * @return array
     */
    public function getProperties($bPublicOnly = true): array
    {
        $aReturn = [];
        if (!$this->oRefClass) {
            return $aReturn;
        }

        $oDefaulProps = $this->oRefClass->getDefaultProperties();

        // foreach($this->oRefClass->getProperties(ReflectionProperty::IS_PROTECTED) as $o){
        foreach ($this->oRefClass->getProperties() as $o) {
            if (!$bPublicOnly == false && !$o->isPublic()) {
                continue;
            }
            if ($o->getName() == 'oRefClass') {
                continue;
            }

            $sType = '';

            // https://www.php.net/manual/en/class.reflectionproperty.php
            if ($o->isPublic())
                $sType .= 'public ';
            if ($o->isPrivate())
                $sType .= 'private ';
            if ($o->isProtected())
                $sType .= 'protected ';
            if ($o->isStatic())
                $sType .= 'static ';
            /*
            public isPrivate(): bool
            public isPromoted(): bool
            public isProtected(): bool
            public isPublic(): bool
            public isReadOnly(): bool
            public isStatic(): bool
            */

            $aPhpDoc = $this->parsePhpdocBlock($o->getDocComment());
            $sValue = ($oDefaulProps[$o->getName()]) ? $oDefaulProps[$o->getName()] : 'false';
            if (is_array($sValue)) {
                $sValue = '<pre>' . print_r($sValue, 1) . '</pre>';
            }
            $aProperty = [
                'type' => $sType,
                'name' => $o->getName(),
                'comment' => $aPhpDoc['comment'] ?? '',
                'vartype' => $o->getType() ? $o->getType()->getName() : '??',
                'defaultvalue' => $o->hasDefaultValue() ? $o->getDefaultValue() : NULL,
                'attributes' => $o->getAttributes(),
                'phpdoc' => $aPhpDoc,
            ];
            $aProperty['vartype'] = ($aProperty['vartype']
                ? $aProperty['vartype']
                : (isset($aPhpDoc['tags']['var'][0]) && $aPhpDoc['tags']['var'][0] > " "
                    ? preg_replace('/ .*/', '', trim($aPhpDoc['tags']['var'][0])) . ' *'
                    : ''
                )
            )
            ;

            $aReturn[$o->getName()] = $aProperty;
        }

        return $aReturn;
    }


    /**
     * Parses a PHPDoc block and extracts relevant information.
     *
     * @param string $sPhpDoc The PHPDoc block to parse.
     * @return array An array containing the filtered PHPDoc block, the comment without @param, @return, and @var tags, and tags with their corresponding values.
     */
    private function parsePhpdocBlock($sPhpDoc)
    {
        $aReturn = [];

        /*
        $tokens = token_get_all('<?php '.$sPhpDoc);

        foreach ($tokens as $token) {
            if (is_array($token)) {
                echo "Line {$token[2]}: ", token_name($token[0]), " ('{$token[1]}')", PHP_EOL;
            }
        }
        */

        $sFiltered = $sPhpDoc;

        $sFiltered = preg_replace('@^\/\*\*@', '', $sFiltered); // remove first comment line
        $sFiltered = preg_replace('@.*\*\/@', '', $sFiltered);  // remove last comment line

        $sFiltered = preg_replace('@[\ \t]*\*@', '', $sFiltered); // remove " * " 
        // $sFiltered = preg_replace('@\n @', '', $sFiltered);       // remove leading spaces


        // TODO: put filter to output specific configuration
        $sFiltered = str_replace(['<code>', '</code>'], ["\n\n```txt ", "```\n\n"], $sFiltered);
        $sFiltered = preg_replace('@ *\n@', "\n", $sFiltered);    // remove

        $iStart = strpos($sFiltered, '@param');
        if (!$iStart) $iStart =  strpos($sFiltered, '@return');
        $sComment = $iStart ? substr($sFiltered, 0, $iStart) : $sFiltered;

        $sParams = substr($sFiltered, $iStart);
        $aReturn = [
            'raw' => $sPhpDoc,
            'filtered' => $sFiltered,
            // 'comment' => preg_replace('/@(param|return|var).*\n/', '', $sFiltered),
            'comment' => trim($sComment),
            'params' => $sParams,
            'tags' => $this->_parsePhpdocTags($sFiltered),
        ];


        // print_r($aReturn['tags']);

        return $aReturn;
    }

    /**
     * Summary of _parsePhpdocTags
     * @param string $sFiltered  filtered doc block
     * @return array
     */
    protected function _parsePhpdocTags($sFiltered): array
    {
        $aReturn = [];
        // all @-Tags
        $aTags = [
            "abstract",
            "access",
            "author",
            "category",
            "copyright",
            "deprecated",
            "example",
            "final",
            "filesource",
            "global",
            "ignore",
            "internal",
            "license",
            "link",
            "method",
            "name",
            "package",
            "param",
            "property",
            "return",
            "see",
            "since",
            "static",
            "staticvar",
            "subpackage",
            "todo",
            "tutorial",
            "uses",
            "var",
            "version"
        ];


        // echo "$sFiltered\n\n";

        $sMyTag='_comment';
        $sTagData='';
        $bParseAsFirstLine=false;
        foreach(explode('@', $sFiltered) as $sLine){
            $sFirstword=explode(' ', $sLine)[0];
            if(array_search($sFirstword, $aTags)!==false){
                // store collected lines
                $aReturn[$sMyTag][]=$sTagData;
                $sTagData='';

                // start new tag
                $sMyTag=$sFirstword;
                if ($sMyTag=="param"){
                    $bParseAsFirstLine=true;
                }
            }
            $sLine=preg_replace("/^$sMyTag */", '', $sLine);

            // parse "string $sAppid      id of an app"
            if($bParseAsFirstLine){
                $bParseAsFirstLine=false;
                $sTagData.=preg_replace("/^[a-z\|]*[\t ]*\\$[a-zA-Z0-9_]*[\t ]*/", '', $sLine)."\n";
                //                         ^       ^      ^              ^
                //                    type(s)  space     dollar+varname  spaces
            } else {
                $sTagData.=$sLine;
            }
            // echo "$sMyTag > $sLine\n";
        }

        // write last data
        $aReturn[$sMyTag][]=$sTagData;
        

        if(strstr($sFiltered, '__Get a flat array with all application ids')){
            echo "```txt ".print_r($aReturn, 1). "```\n";
            die();
        }
        return $aReturn;
    }

}

