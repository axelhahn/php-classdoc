## Change output

The rendering output is based on the templates in `./config/md/`.

To change the output copy the folder "md" inside the config directory, eg "md_custom". In your copy you can edit all templates.
To use your template use the parameter `--out md_custom` when calling the parser.

## Template files

* **index.tpl**<br>main template for a doc page

There you see placeholders like `{{<name>}}` for values and `{{<file.tpl>}}` to include another template (which is quite static).

Other (required) templates are:

* **properties.tpl**<br>Renders properties
* **methods.tpl**<br>Renders methods ... and includes
  * **parameters.tpl**<br>Render section for parameters (if parameter count of a metho d is > 0) ... and
    * **parameter.tpl**<br>To shoe a single parameter of a method.

Remark: there is no template engine. All these 5 files must exist if you create your own output format.

## Values in template files

TODO