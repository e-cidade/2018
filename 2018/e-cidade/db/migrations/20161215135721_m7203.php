<?php

use Classes\PostgresMigration;

class M7203 extends PostgresMigration
{

  public function up()
  {

    $codeAtribute = $this->fetchRow("select db118_sequencial from configuracoes.db_cadattdinamico where db118_descricao = 'Atributos da licitação'");
    $codeAtribute = $codeAtribute['db118_sequencial'];

    $data = array(
      (object)array('descricao' => 'CNPJ do Órgão Gerenciador',          'propriedade' => 'cnpjorgaogerenciador', 'tipo' => 1),
      (object)array('descricao' => 'Nome do Órgão Gerenciador',          'propriedade' => 'nomeorgaogerenciador', 'tipo' => 1),
      (object)array('descricao' => 'Número da Licitação Original',       'propriedade' => 'numerolicitacao',      'tipo' => 1),
      (object)array('descricao' => 'Ano da Licitação Original',          'propriedade' => 'anolicitacao',         'tipo' => 2),
      (object)array('descricao' => 'Número da Ata de Registro de Preço', 'propriedade' => 'numeroataregistropreco', 'tipo' => 1),
      (object)array('descricao' => 'Data da Ata de Adesão',              'propriedade' => 'dataata',                'tipo' => 3),
      (object)array('descricao' => 'Data de Autorização da Adesão',      'propriedade' => 'dataautorizacao',        'tipo' => 3),
      (object)array('descricao' => 'Tipo de Atuação',                    'propriedade' => 'tipoatuacao',            'tipo' => 1)
    );


    $dataColumns = array(
      'db109_sequencial',
      'db109_db_cadattdinamico',
      'db109_codcam',
      'db109_descricao',
      'db109_valordefault',
      'db109_tipo',
      'db109_nome'
    );

    $lastSequence = null;
    $table = $this->table('db_cadattdinamicoatributos', array('schema' => 'configuracoes'));
    $dataRow = array();
    foreach ($data as $row) {

      $lastSequence = $this->fetchRow("select nextval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq') as nextval");
      $lastSequence = $lastSequence['nextval'];

      $dataRow[] = array(
        $lastSequence,
        $codeAtribute,
        null,
        $row->descricao,
        null,
        $row->tipo,
        $row->propriedade
      );
    }

    $table->insert($dataColumns, $dataRow);
    $table->saveData();
    unset($table, $dataColumns, $dataRow);

    $data = array(
      (object)array('valor' => 'P', 'descricao' => 'Participante'),
      (object)array('valor' => 'A', 'descricao' => 'Não Participante / Aderente')
    );

    $dataColumns = array(
      'db18_sequencial',
      'db18_cadattdinamicoatributos',
      'db18_opcao',
      'db18_valor'
    );

    $dataRows = array();
    $table = $this->table('db_cadattdinamicoatributosopcoes', array('schema' => 'configuracoes'));
    foreach ($data as $row) {

      $sequence = $this->fetchRow("select nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq') as nextval");

      $dataRows[] = array(
        $sequence['nextval'],
        $lastSequence,
        $row->valor,
        $row->descricao
      );
    }

    $table->insert($dataColumns, $dataRows);
    $table->saveData();
  }


  public function down()
  {

    $nameAtributes = array(
      'cnpjorgaogerenciador',
      'nomeorgaogerenciador',
      'numerolicitacao',
      'anolicitacao',
      'propriedade',
      'dataata',
      'dataautorizacao',
      'tipoatuacao',
      'numeroataregistropreco'
    );

    $deleteOptions = "delete from db_cadattdinamicoatributosopcoes
                            using db_cadattdinamicoatributos
                            where db_cadattdinamicoatributosopcoes.db18_cadattdinamicoatributos = db_cadattdinamicoatributos.db109_sequencial
                              and db_cadattdinamicoatributos.db109_nome = 'tipoatuacao'
                              and db109_db_cadattdinamico = 2";

    $deleteAtributes = "delete from db_cadattdinamicoatributos
                              where db_cadattdinamicoatributos.db109_db_cadattdinamico = 2
                                and db_cadattdinamicoatributos.db109_nome in ('".implode("','", $nameAtributes)."')";

    $this->execute($deleteOptions);
    $this->execute($deleteAtributes);
  }
}
