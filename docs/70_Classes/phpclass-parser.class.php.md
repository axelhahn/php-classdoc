## 📦 Class axelhahn\phpclassparser

```txt

 PHP CLASS PARSER

 @author axelhahn
 @license GNU GPL 3.0

 @source <https://github.com/axelhahn/php-classdoc>

 2024-07-15  v0.1  axelhahn  initial version

```

## 🔶 Properties

(none)

## 🔷 Methods

### 🔹 public __construct()

Constructs a new instance of the class.

Line [38](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L38) (5 lines)

**Return**: `void`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sClassname | `string` | optional: The name of the class. Default is an empty string.

### 🔹 public getClassInfos()

Get metainformation for the class

Line [154](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L154) (15 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public getMethods()

Get a hash of methods with its type, parameters, phpdoc infos

Line [175](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L175) (116 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bPublicOnly | `flag: *` | flag: public only methods or all; default: true (=only public methods)

### 🔹 public getProperties()

Get a hash of properties with its type, phpdoc infos, default value, attributes, etc.

Line [299](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L299) (66 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bPublicOnly | `flag: *` | flag: public only properties or all; default: true (=only public properties)

### 🔹 public setClassFile()

Sets the class file to be analyzed.
 It will detect namespace and class name to initialize the class.

Line [51](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L51) (74 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $file | `string` | The path to the class file.

### 🔹 public setClassname()

Set a classname.
 You can use that method directly if the class file was loaded before.
 Or use setClassFile() to load the class file and detect the classname.
 @see setClassFile()

Line [134](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L134) (10 lines)

**Return**: `void`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sClassname | `string` | classname to access for doc generation

### 🔹 public setSourceUrl()



Line [145](https://github.com/axelhahn/php-classdoc//blob/main/src/phpclass-parser.class.php#L145) (4 lines)

**Return**: `void`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sSourceUrl | `string` | 

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
