## Parser cli script

The script you need in the command line is `parse-class.php`. Use `-h` for help.

```php
./parse-class.php -h

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

```

## Generator for multiple classes

To generate multiple markdown doc files per - one class file - there is a generic approach for GNU Shell tools (Linux ... or you install GNU tools on Mac OS or MS Windows).

### Preparations

* In your project create a subdir "scripts".
* Into this subdir 
  * copy the file `scripts/generate_classdoc.sh` --> `<your-project>>/scripts/generate_classdoc.sh`
  * copy `scripts/config.sh.dist` as `<your-project>>/scripts/config.sh`.

### Update configuration

Edit `<your-project>>/scripts/config.sh` and adapt it to your needs.

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

Start the script `<your-project>>/scripts/generate_classdoc.sh` tu generate/ update the markdown files.
