<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="genolines.js"></script>


<body>

<h3>class Setters And Getters</h3>

<!--
class new
private $title;
protected $options = []; self_returning:true
-->

<textarea class="insert" style="width:500px;height:200px;">
SelfReturning:true

class new
private $title;
private $age;
protected $options = []; self_returning:false
</textarea>

<div>
    <button>
        Generate
    </button>
</div>

<textarea class="result" style="width:500px;height:500px;">

</textarea>

</body>


<script>

    $(function () {

        $('.result').val(executeGenerator($('.insert').val().split('\n')).join("\n"));

        $(document).keypress(function (e) {
            if (e.which === 13) {
                $('.result').val(executeGenerator($('.insert').val().split('\n')).join("\n"));
            }
        });

        $('button').click(function () {

            $('.result').val(executeGenerator($('.insert').val().split('\n')).join("\n"));
        });
    });

</script>

<script>

    function executeGenerator(input) {

        var genolines = new Genolines();

        // Settings
        var resultTemplate = function () {
            var result = [
                'public function get{{firstToUpperMethodName}}(){',
                'return $this->' + '{{firstToLowerMethodName}};',
                '}',
                ''
            ];

            var selfReturningGlobal = genolines.getGlobalVariable('selfReturning');
            var selfReturningTmp = genolines.getTmpVariable('selfReturning');



            if (selfReturningGlobal === 'true') {

                if ( selfReturningTmp === 'true' || selfReturningTmp === undefined) {
                    result.push(
                        'public function set{{firstToUpperMethodName}}($value): {{global.firstToUpperClassName}}{'
                    );
                }

                console.log(selfReturningTmp);

                if (selfReturningTmp === 'false' || selfReturningTmp !== 'true' ) {
                    if(selfReturningTmp !== undefined){
                        result.push('public function set{{firstToUpperMethodName}}($value){');
                    }

                }

            } else {

                if (selfReturningTmp === 'true') {
                    result.push(
                        'public function set{{firstToUpperMethodName}}($value): {{global.firstToUpperClassName}}{'
                    );
                }

                if (selfReturningTmp === 'false' || selfReturningTmp !== 'true' || selfReturningTmp === undefined) {
                    result.push('public function set{{firstToUpperMethodName}}($value){');
                }
            }

            result.push('$this->' + '{{firstToLowerMethodName}}' + ' = $value;');

            if (selfReturningGlobal === 'true') {

                if (selfReturningTmp === undefined || selfReturningTmp === 'true') {
                    result.push('return $this;');
                }

            } else {

                if (selfReturningTmp === 'true') {
                    result.push('return $this;');
                }

            }

            result = result.concat([
                '}',
                ''
            ]);

            return result;
        };


        var needleOne = 'protected $';
        var needleTwo = 'private $';

        var options = {};

        options[needleOne] = {
            variables: {
                firstToUpperMethodName: {
                    after: needleOne,
                    before: [' ', ';'],
                    modifiers: ['firstToUpper']
                },
                firstToLowerMethodName: {
                    after: needleOne,
                    before: [' ', ';'],
                    modifiers: []
                },
                selfReturning: {
                    after: 'self_returning:',
                    before: [' ', ';'],
                    modifiers: []
                }
            },
            resultTemplate: resultTemplate
        };

        options[needleTwo] = {
            variables: {
                firstToUpperMethodName: {
                    after: needleTwo,
                    before: [' ', ';'],
                    modifiers: ['firstToUpper']
                },
                firstToLowerMethodName: {
                    after: needleTwo,
                    before: [' ', ';'],
                    modifiers: []
                },
                selfReturning: {
                    after: ['self_returning:', 'self_returning'],
                    before: [' ', ';'],
                    modifiers: []
                }
            },
            resultTemplate: resultTemplate
        };

        // Execution
        genolines
            .setInputLines(input)
            .setGlobalVariable('firstToUpperClassName', 'class', {
                after: 'class ',
                before: [' ']
            }, ['firstToUpper'])
            .setGlobalVariable('selfReturning', 'SelfReturning', {
                after: 'SelfReturning:',
                before: ''
            }, [])
            .addLines([
                "<" + '?php',
                ''
            ])
            .handleInputLines(options);

        return genolines.resultLines;
    }

</script>