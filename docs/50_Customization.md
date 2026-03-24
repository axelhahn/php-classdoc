## Change output

The rendering output is based on the templates in `./config/md/`.

To change the output copy the folder "md" inside the config directory, eg "md_custom". In your copy you can edit all templates.
To use your template use the parameter `--out md_custom` when calling the parser.

## Overview of template files

* **index.tpl**<br>main template for a doc page

There you see placeholders like `{{<name>}}` for values and `{{<file.tpl>}}` to include another template (which is quite static).

Other (required) templates are:

* **properties.tpl**<br>Renders properties
* **methods.tpl**<br>Renders methods ... and includes
  * **parameters.tpl**<br>Render section for parameters (if parameter count of a method is > 0) ... and
    * **parameter.tpl**<br>To show a single parameter of a method.

Remark: there is no template engine. All these 5 files must exist if you create your own output format.

## Values in template files


### index.tpl

🔁 Placeholders:

* {{classname}} - name of the php class
* {{comment}} - comment from phpdoc

🧾 Include files:

* {{properties.tpl}}
* {{methods.tpl}}

### properties.tpl

🔁 Placeholders:

* {{type}} - type of the property, eg. public|private|protected|...
* {{name}} - variable name of the property
* {{comment}} - comment from phpdoc
* {{vartype}} - type of the variable, eg. array|bool|int|string|...
* {{defaultvalue}} - default value of the variable

### methods.tpl

🔁 Placeholders:

* {{type}} - type of the method, eg. public|private|protected|...
* {{name}} - method name
* {{comment}} - comment from phpdoc
* {{linefrom}} - line number of the method
* {{sourceurl}} - source url of the method with hash `#L<number>` to jump to the right position
* {{lines}} - number of lines of the method
* {{returntype}} - return type of the method
* {{parameters_count}} - number of parameters for this method
* {{parameters_required}} - number of required parameters

🧾 Include files:

* {{parameters.tpl}} - template to render parameters of the method

### parameters.tpl

This is a wrapper to show all parameters of a method. It uses the `{{parameter.tpl}}` template to render each parameter.

🧾 Include files:

* {{parameter.tpl}}

### parameter.tpl

This template is included from `parameters.tpl`.
It renders a single parameter of a method by reading a single line with `@param ...` in the phpdoc for it.

🔁 Placeholders:

* {{string}} - name of the variable with prefix '\<required\>' or '\<optional\>'
* {{type}} - type of the variable, eg. array|bool|int|string|...
* {{phpdoc_descr}} - comment for this parameter from phpdoc
