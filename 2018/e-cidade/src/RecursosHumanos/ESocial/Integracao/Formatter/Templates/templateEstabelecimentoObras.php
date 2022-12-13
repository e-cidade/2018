<?php
return array(
    'ideEstab' => array(
        'nome_api' => 'ideEstab',
        'properties' => array(
            'tpInsc' => array(
                'nome_api'=> 'tpInsc',
                'type' => 'int'
            ),
            'nrInsc' => array(
                'nome_api'=> 'nrInsc4',
                'type' => 'int'
            ),
            'iniValid' => 'iniValid',
            'fimValid' => 'fimValid'
        )
    ),
    'dadosEstab' => array(
        'nome_api' => 'dadosEstab',
        'properties' => array(
            'cnaePrep' => 'cnaePrep'
        ),
        'groups' => array (
            'aliqGilrat' => array(
                'nome_api' => 'aliqGilrat',
                'properties' => array(
                    'aliqRat' => array(
                        'nome_api'=>'aliqRat',
                        'type' => 'integer'
                    ),
                    'fap' => array(
                        'nome_api'=>'fap',
                        'type' => 'float'
                    ),
                    'aliqRatAjust' => array(
                        'nome_api'=>'aliqRatAjust',
                        'type' => 'float'
                    ),
                ),
                'groups' =>array (
                    'procAdmJudRat' => array (
                        'properties' => array(
                            'tpProc' => array(
                                'nome_api'=> 'tpProc',
                                'type' => 'int'
                            ),
                            'nrProc' => 'nrProc',
                            'codSusp' => 'codSuspo'
                        )
                    ),
                    'procAdmJudFap' => array (
                        'properties' => array(
                            'tpProc' => array(
                                'nome_api'=> 'tpProc',
                                'type' => 'int'
                            ),
                            'nrProc' => 'nrProc',
                            'codSusp' => 'codSuspo'
                        )
                    )
                )
            ),
            'infoCaepf' => array (
                'nome_api' => 'infoCaepf',
                'properties' => array(
                    'tpCaepf' => array(
                        'nome_api'=> 'tpCaepf',
                        'type' => 'int'
                    ),
                )
            ),
            'infoObra' => array (
                'nome_api' => 'infoObra',
                'properties' => array(
                    'indSubstPatrObra' => array(
                        'nome_api'=>  'indSubstPatrObra',
                        'type' => 'int'
                    )
                )
            ),
            'infoTrab' => array (
                'nome_api' => 'infoTrab',
                'properties' => array(
                    'regPt' => array(
                        'nome_api'=>  'regPt',
                        'type' => 'int'
                    )
                ),
                'groups' => array(
                    'infoApr' => array (
                        'nome_api' => 'infoApr',
                        'properties' => array(
                            'contApr' => array(
                                'nome_api'=>  'contApr',
                                'type' => 'int'
                            ),
                            'nrProcJud',
                            'contEntEd',
                        ),
                        'groups' => array(
                            'infoEntEduc' => array (
                                'type' => 'array',
                                'nome_api' => 'infoEntEduc',
                                'items' => array(
                                    'properties' => array(
                                        'nrInsc' => array(
                                            'nome_api'=>  'nrInsc',
                                            'type' => 'int'

                                        ),
                                    )
                                )
                            ),
                        )
                    ),
                    'infoPCD' => array (
                        'nome_api' => 'infoPCD',
                        'properties' => array(
                            'contPCD' => array(
                                'nome_api'=>  'contPCD',
                                'type' => 'int'
                            ),
                            'nrProcJud' => 'nrProcJud'
                        )
                    )
                )
            ),
        )
    ),
);
