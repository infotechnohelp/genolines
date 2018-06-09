<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="pluralize.js"></script>
<script src="Generator.js"></script>


<body>

<h3>CakePHP Table class</h3>

<textarea class="insert" style="width:500px;height:200px;">
class agreements
timestamp
assoc:clients type:hasOne
assoc:agreements type:belongsTo
}
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

        $('button').click(function () {

            var generator = new Genolines();

            // Execution
            generator
                .setInputLines($('.insert').val().split('\n'))
                .handleInputLines({
                    'class': {
                        variables: {
                            firstToUpperClassName: {
                                after: 'class ',
                                before: [' '],
                                modifiers: ['firstToUpper']
                            },
                            firstToLowerClassName: {
                                after: 'class ',
                                before: [' '],
                                modifiers: ['firstToLower']
                            }
                        },
                        resultTemplate: [
                            'class {{firstToUpperClassName}}Table extends Table{',
                            '',
                            'public function initialize(array $config){',
                            'parent::initialize($config);',
                            '',
                            "$this->setTable('{{firstToLowerClassName}}');",
                            "$this->setDisplayField('id');",
                            "$this->setPrimaryKey('id');",
                            ''
                        ]
                    },

                    'timestamp': {
                        variables: {},
                        resultTemplate: [
                            "$this->addBehavior('Timestamp');",
                            ''
                        ]
                    },
                    'hasOne': {
                        variables: {
                            associatedTable: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToUpper']
                            },
                            associatedEntityTitle: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToLower', 'singularize']
                            },
                            associationType: {
                                after: 'type:',
                                before: [],
                                modifiers: []
                            }
                        },
                        resultTemplate: [
                            "$this->{{associationType}}('{{associatedTable}}', [",
                            "'foreignKey' => '{{associatedEntityTitle}}_id',",
                            ']);',
                            ''
                        ]
                    },
                    'belongsTo': {
                        variables: {
                            associatedTable: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToUpper']
                            },
                            associatedEntityTitle: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToLower', 'singularize']
                            },
                            associationType: {
                                after: 'type:',
                                before: [],
                                modifiers: []
                            }
                        },

                        resultTemplate: [
                            "$this->{{associationType}}('{{associatedTable}}', [",
                            "'foreignKey' => '{{associatedEntityTitle}}_id',",
                            "'joinType'   => 'INNER',",
                            ']);',
                            ''
                        ]
                    },
                    'belongsToMany': {
                        variables: {
                            associatedTable: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToUpper']
                            },
                            associatedEntityTitle: {
                                after: 'assoc:',
                                before: [' '],
                                modifiers: ['firstToLower', 'singularize']
                            },
                            associationType: {
                                after: 'type:',
                                before: [],
                                modifiers: []
                            }
                        },
/*
 'targetForeignKey' => 'contact_id',
 'joinTable'        => 'addresses_contacts',
 */
                        resultTemplate: [
                            "$this->{{associationType}}('{{associatedTable}}', [",
                            "'foreignKey' => '{{associatedEntityTitle}}_id',",
                            "'joinType'   => 'INNER',",
                            ']);',
                            ''
                        ]
                    },
                    '}': {
                        variables: {},
                        resultTemplate: [
                            '}'
                        ]
                    }
                });

            $('.result').val(generator.resultLines.join("\n"));
        });
    });

</script>