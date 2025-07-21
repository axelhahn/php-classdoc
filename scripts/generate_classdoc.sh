#!/bin/bash
# ====================================================================== 
#
# Generator for markdown files from php class files
#
# ----------------------------------------------------------------------
# 2025-07-21  v1.0  axelhahn  initial version
# ====================================================================== 

cd "$( dirname $0)/.."

files="
    src/phpclass-parser.class.php
"
outdir="docs/70_Classes"
sourceurl="https://github.com/axelhahn/php-classdoc//blob/main"

# ----------------------------------------------------------------------
# MAIN
# ----------------------------------------------------------------------

for myfile in $files
do
    outfile="$outdir/$(basename "$myfile").md"

    ./parse-class.php \
        --debug \
        --source "$sourceurl/$myfile" \
        --out md \
        "$myfile" > "$outfile"
done

# ----------------------------------------------------------------------
