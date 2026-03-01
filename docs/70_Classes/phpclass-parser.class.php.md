---
title: axelhahn\phpclassparser
generator: Axels php-classdoc; https://github.com/axelhahn/php-classdoc
---

## 📦 Class axelhahn\phpclassparser

```txt

 PHP CLASS PARSER

 @author axelhahn
 @license GNU GPL 3.0

 @source <https://github.com/axelhahn/php-classdoc>

 2024-07-15  v0.1  axelhahn  initial version
 2026-03-01  v0._  axelhahn  last changes

```

## 🔶 Properties

(none)

## 🔷 Methods

### 🔹 public __construct()

Constructs a new instance of the class.

Line [39](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L39) (5 lines)

**Return**: `void`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sClassname | `string` | optional: The name of the class. Default is an empty string.

### 🔹 public setClassFile()

Sets the class file to be analyzed.
 It will detect namespace and class name to initialize the class.

Line [52](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L52) (74 lines)

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

Line [135](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L135) (10 lines)

**Return**: `void`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sClassname | `string` | classname to access for doc generation

### 🔹 public setSourceUrl()



Line [146](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L146) (4 lines)

**Return**: `void`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sSourceUrl | `string` | 

### 🔹 public getClassInfos()

Get metainformation for the class

Line [155](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L155) (15 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public getMethods()

Get a list of all methods of a class

Line [176](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L176) (15 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bPublicOnly | ` *` | 

### 🔹 public getMethod()

Get a hash of methods with its type, parameters, phpdoc infos

Line [197](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L197) (105 lines)

**Return**: `array`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sMethodname | `string` | mame of the method

### 🔹 public getProperties()

Get a hash of properties with its type, phpdoc infos, default value, attributes, etc.

Line [310](https://github.com/axelhahn/php-classdoc/blob/main/src/phpclass-parser.class.php#L310) (66 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bPublicOnly | `flag: *` | flag: public only properties or all; default: true (=only public properties)

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
