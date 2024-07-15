#!/bin/bash

cd "$( dirname $0)/.."

./parse-class.php --debug --out md src/phpclass-parser.class.php > docs/70_Class.md
