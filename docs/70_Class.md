## Class axelhahn\phpclassparser


PHP CLASS PARSER
@author axelhahn
@license GNU GPL 3.0

@source <https://github.com/axelhahn/TODO>



## Properties

(none)

## Methods

### public __construct


Constructs a new instance of the class.



**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
\<optional\> string $sClassname = '' | string | optional: The name of the class. Default is an empty string.



**Return**: 

### public getClassInfos



**Parameters**: **0** (required: 0)



**Return**: array

### public getMethods


Get a hash of methods with its type, parameters, phpdoc infos


**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
\<optional\> $bPublicOnly = true | string * | flag: public only methods or all; default: true (=only public methods)



**Return**: array

### public getProperties


Get a hash of properties with its type, phpdoc infos, default value, attributes, etc.



**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
\<optional\> $bPublicOnly = true | bool * | flag: public only properties or all; default: true (=only public properties)



**Return**: array

### public setClassFile


Sets the class file to be analyzed.
It will detect namespace and class name to initialize the class.



**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
\<required\> string $file | string | The path to the class file.



**Return**: string|bool

### public setClassname


Set a classname. 
You can use that method directly if the class file was loaded before. 
Or use setClassFile() to load the class file and detect the classname.
@see setClassFile()


**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
\<required\> string $sClassname | string | classname to access for doc generation



**Return**: void



---
Generated with Axels PHP class doc parser.