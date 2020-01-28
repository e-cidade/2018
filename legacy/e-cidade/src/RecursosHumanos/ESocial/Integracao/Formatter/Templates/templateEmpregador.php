<?php
return array(
    'infoCadastro' => array(
        'properties' => array(
            'nmRazao',
            'classTrib',
            'natJurid',
            'indCoop' => array(
                'type' => 'int'
            ),
            'indConstr' => array(
                'type' => 'int'
            ),
            'indDesFolha' => array(
                'type' => 'int'
            ),
            'indOptRegEletron' => array(
                'type' => 'int'
            ),
            'indEntEd',
            'indEtt',
            'nrRegEtt',
        )
    ),

    'dadosIsencao' => array(
        'properties' => array(
            'ideMinLei' => 'ideMinLei',
            'nrCertif' => 'nrCertif',
            'dtEmisCertif' => 'dtEmisCertif',
            'dtVencCertif' => 'dtVencCertif',
            'nrProtRenov' => 'nrProtRenov',
            'dtProtRenov' => 'dtProtRenov',
            'dtDou' => 'dtDou',
            'pagDou' => array(
                'nome_api'=> 'pagDou',
                'type' => 'int'
            )
        )
    ),
    'contato' => array(
        'properties' => array(
            'nmCtt' => 'nmCtt',
            'cpfCtt' => 'cpfCtt',
            'foneFixo' => 'foneFixo',
            'foneCel' => 'foneCel',
            'email' => 'email'
        )
    ),
    'infoOP' => array(
        'properties' => array(
            'nrSiafi' => 'nrSiafi'
        )
    ),

    'infoEFR' => array(
        'properties' => array(
            'ideEFR' => 'ideEFR',
            'cnpjEFR' => 'cnpjEFR'
        )
    ),

    'infoEnte' => array(
        'properties' => array(
            'nmEnte' => 'nmEnte',
            'uf' => 'uf',
            'codMunic' => array(
                'type' => 'int'
            ),
            'indRPPS' => 'indRPPS',
            'subteto' => array(
                'nome_api'=> 'subteto',
                'type' => 'int'
            ),
            'vrSubteto' => array(
                'nome_api'=> 'vrSubteto',
                'type' => 'float'
            )
        )
    ),
    'infoOrgInternacional' => array(
        'properties' => array(
            'indAcordoIsenMulta' => array(
                'nome_api'=> 'indAcordoIsenMulta',
                'type' => 'int'
            )
        )
    ),
    'situacaoPJ' => array(
        'properties' => array(
            'indSitPJ' => array(
                'nome_api'=> 'indSitPJ',
                'type' => 'int'
            )
        )
    ),

    'situacaoPF' => array(
        'properties' => array(
            'indSitPF' => array(
                'nome_api'=> 'indSitPF',
                'type' => 'int'
            )
        )
    )
);
