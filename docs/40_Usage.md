## Parser cli script

The script you need in the command line is `parse-class.php`. Use `-h` for help.

```txt
./parse-class.php -h

Axels docpage generator for php class files.
_______________________________________________________________________________

AXELs docpage generator for php class files.
_______________________________________________________________________________

This tool creates a documentation of a given php class file with all methods,
and its parameters.

👤 Author: Axel Hahn \
📄 Source: https://github.com/axelhahn/php-classdoc
📜 License: GNU GPL 3.0
📗 Docs: https://www.axel-hahn.de/docs/php-classdoc/


✨ USAGE: parse-class.php [OPTIONS] <classfile.php> [<classname>]


🔸 OPTIONS:
    -h, --help            Show this help

    -d, --debug           Enable debug output (written on STDERR)
    -o, --out <type>      Set output type: 'md' (default).
                          It is a subdir below folder './config/'
    -s, --source <url>    Set url of source file in main branch; default: none
                          This url is used to create a link to the source file
                          and line number of a method. 
                          Examples:
                          https://github.com/<user>/<project>/blob/main
                          https://gitlab.<domain>/<group>/<project>/-/tree/main


🔹 PARAMETERS:
    <classfile.php>       path to class file
    <classname>           optional: if classname is not detected you can set 
                          it manually

```

## Generator for multiple classes

To generate multiple markdown doc files per - one class file - there is a generic approach for GNU Shell tools (Linux ... or you install GNU tools on Mac OS or MS Windows).

### Preparations

* In your project create a subdir "scripts".
* Into this subdir 
  * copy the file `scripts/generate_classdoc.sh` --> `<your-project>/scripts/generate_classdoc.sh`
  * copy `scripts/config.sh.dist` as `<your-project>/scripts/config.sh`.

### Update configuration

Edit `<your-project>>/scripts/config.sh` (that you can create from the .dist file in the same folder) and adapt it to your needs.

```shell
# ----------------------------------------------------------------------
# CONFIG
# includet by generate_classdoc.sh
# standing in its directory
# ----------------------------------------------------------------------

# go to approot to reference local input and ouput files
cd ..
APPDIR="$( pwd )"
OUTDIR="$APPDIR/docs/<subdir>"

FILELIST="
    src/<file1>.class.php
    src/<file2>.class.php
"

# web url to watch sources
# The relative filename to approot will be added + "#L" + line number
# (which works for Github and Gitlab for sure)
SOURCEURL="https://github.com/<user>/<repo>/blob/main"

# relative or absolute path of local php doc parser
PARSERDIR="$( dirname $0)/../../php-classdoc"

# ----------------------------------------------------------------------
```

### Generate

Start the script `<your-project>/scripts/generate_classdoc.sh` to generate/ update the markdown files.
