# ----------------------------------------------------------------------
# CONFIG
# includet by generate_classdoc.sh
# standing in its directory
# ----------------------------------------------------------------------

# go to approot to reference local input and ouput files
cd ..
APPDIR="$( pwd )"
OUTDIR="$APPDIR/docs/70_Classes"

FILELIST="
    src/phpclass-parser.class.php
"

# web url to watch sources
# The relative filename to approot will be added + "#L" + line number
# (which works for Github and Gitlab for sure)
SOURCEURL="https://github.com/axelhahn/php-classdoc/blob/main"

# relative or absolute path of local php doc parser
PARSERDIR="."

# ----------------------------------------------------------------------
