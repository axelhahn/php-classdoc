The script you need in the command line is `parse-class.php`. Use `-h` for help.

```php
./parse-class.php -h

Axels docpage generator for php class files.
It generates markdown from a class file.
You can customize the output or create output in another wanted format.
Redirect its output to create/ update a doc file.

USAGE: parse-class.php [OPTIONS] <classfile.php> [<classname>] [> <outputfile>]

OPTIONS:
    -h, --help            show this help

    -d, --debug           enable debug output (written on STDERR)
    -o, --out <type>      set output type: 'md' (default) or 
                          any subdir below ./config/
    -s, --source <url>    set url of source file in main branch; default: none

PARAMETERS:
    <classfile.php>       path to class file
    <classname>           optional: if classname is not detected you can set 
                          it manually
```
