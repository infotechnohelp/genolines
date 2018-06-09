var Genolines = function () {

    var $this = this;

    this.inputLines = [];

    this.globalVariables = {};

    this.tmpLine = null;

    this.tmpVariables = {};

    this.resultLines = [];

    this.modifiers = {
        firstToUpper: function (input) {
            return input.charAt(0).toUpperCase() + input.slice(1);
        },
        firstToLower: function (input) {
            return input.charAt(0).toLowerCase() + input.slice(1);
        },
        singularize: function (input) {
            return pluralize.singular(input);
        },
        pluralize: function (input) {
            return pluralize(input);
        }
    };

    this.finders = {
        after: function (needle) {

            $this.tmpLine = $this.tmpLine.split(needle)[1];

            return $this;
        },
        before: function (needleArray) {

            // @todo $.each() does not work
            for (i = 0; i < needleArray.length; i++) {

                if ($this.tmpLine !== undefined) {
                    $this.tmpLine = $this.tmpLine.trim().split(needleArray[i])[0];
                }

            }

            return $this;
        }
    };
};

Genolines.prototype.addLines = function (lines) {
    this.resultLines = this.resultLines.concat(lines);

    return this;
};

Genolines.prototype.setInputLines = function (lines) {
    this.inputLines = lines;

    return this;
};

Genolines.prototype.setTmpLine = function (line) {
    this.tmpLine = line;

    return this;
};

Genolines.prototype.pushLines = function (lines) {

    var $this = this;

    $.each(lines, function (key, value) {
        $this.resultLines.push(value);
    });
};

Genolines.prototype.setGlobalVariable = function (variableTitle, lineNeedle, options, modifiers) {

    var $this = this;

    $.each($this.inputLines, function (index, line) {
        if ($this.contains(lineNeedle, line)) {

            var after = options.after;
            var before = options.before;

            var result = $this.setTmpLine(line).finders.after(after).finders.before(before).tmpLine;

            $.each(modifiers, function (index, modifier) {
                result = $this.modifiers[modifier](result);
            });

            $this.globalVariables[variableTitle] = result;
        }
    });

    return this;
};

Genolines.prototype.contains = function (needle, haystack) {
    return haystack.indexOf(needle, 0) !== -1
};

Genolines.prototype.getGlobalVariable = function (title) {
    return this.globalVariables[title];
};

Genolines.prototype.getTmpVariable = function (title) {
    return this.tmpVariables[title];
};

Genolines.prototype.handleInputLines = function (options) {

    var $this = this;

    $.each($this.inputLines, function (index, inputLine) {

        $.each(options, function (lineNeedle, lineHandlingOptions) {

            if ($this.contains(lineNeedle, inputLine)) {

                var tmpVariables = {};
                $this.tmpVariables = tmpVariables;


                $.each(lineHandlingOptions.variables, function (title, settingOptions) {

                    $this.setTmpLine(inputLine);

                    $this.finders.after(settingOptions.after).finders.before(settingOptions.before);

                    var tmp = $this.tmpLine;

                    $.each(settingOptions.modifiers, function (index, modifier) {
                        tmp = $this.modifiers[modifier](tmp);
                    });

                    tmpVariables[title] = tmp;
                });


                $this.tmpVariables = tmpVariables;

                var result = [];

                var resultTemplateLines = lineHandlingOptions.resultTemplate();

                $.each(resultTemplateLines, function (lineIndex, lineValue) {

                    var resultLine = lineValue;

                    $.each(tmpVariables, function (tmpVariableTitle, tmpVariableValue) {
                        var find = '{{' + tmpVariableTitle + '}}';
                        var regex = new RegExp(find, 'g');

                        resultLine = resultLine.replace(regex, tmpVariableValue);
                    });

                    $.each($this.globalVariables, function (globalVariableTitle, globalVariableValue) {
                        var find = '{{global.' + globalVariableTitle + '}}';
                        var regex = new RegExp(find, 'g');

                        resultLine = resultLine.replace(regex, globalVariableValue);
                    });

                    result.push(resultLine);
                });

                $this.pushLines(result);
            }

        });
    });
};
