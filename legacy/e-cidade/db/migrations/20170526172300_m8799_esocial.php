<?php

use Classes\PostgresMigration;

class M8799Esocial extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
  public function up()
  {

    $this->addDicionarioDados();
    $this->criarTabelas();
    $this->dadosEsocial();
  }

  public function down() {

    $this->removerDicionarioDados();
    $this->droparDML();
    $this->deleteDadosEsocial();
  }

  public function addDicionarioDados()
  {

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
      array(1009305,'db103_perguntaidentificadora','bool','Define se a perguntaé uma chave primaria dos metadados','f', 'Pergunta Identificadora',1,'t','f','f',5,'text','Pergunta Identificadora'),
      array(1009304,'db103_camposql','varchar(40)','Campo que salva o SQL para carga das avaliações','', 'Campo Carga',40,'t','f','f',0,'text','Campo Carga'),
      array(1009306,'db101_cargadados','text','Campo com a query que será utilizada para a carga de dados','', ' Query Dados',1,'t','t','f',0,'text',' Query Dados'),
      array(1009307,'db103_dblayoutcampo','int4','Coluna que vincula a coluna de um layout com a avaliação','0', 'Coluna Layout',10,'t','f','f',1,'text','Coluna Layout'),
      array(1009308,'db104_valorresposta','varchar(50)','Campo para valor de resposta para opções ','', 'Valor Resposta',50,'t','t','f',0,'text','Valor Resposta'),
      array(1009309,'db101_permiteedicao','bool','Determina se é permitido ou não a edição dos dados do formulário na rotina de manutenção.','f', 'Permite Edição',1,'t','f','f',5,'text','Permite Edição')
    );

    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
      array(2983,1009305,6,0),
      array(2983,1009304,7,0),
      array(2983,1009307,5,0),
      array(2980,1009306,7,0),
      array(2985,1009308,7,0),
      array(2980,1009309,8,0)
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_itensmenu set id_item = 8528 , descricao = 'Cadastro de Formulários' , help = 'Cadastro de Formulários' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro de formulários onde são cadastrados a estrutura genérica para os mesmos.' , libcliente = 'true' where id_item = 8528;");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,8528 ,277 ,1 )");
    $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10423 ,'Manutenção Formulários' ,'Manutenção Formulários' ,'con4_manutencaoformularios.php' ,'1' ,'1' ,'Rotina para manutneção dos formulários do e-cidade' ,'true' )");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10423 ,482 ,1 )");

    $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10425 ,'Carga de Dados' ,'Carga de Dados para o esocial' ,'con4_cargaformularios001.php?tipo_formulario=5' ,'1' ,'1' ,'Menu para realização da carga de dados para o esocial.' ,'true' )");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10425 ,483 ,10216 )");
    $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10426 ,'Manutenção S1010 - Tabela de Rubricas' ,'Manutenção S1010 - Tabela de Rubricas' ,'con4_manutencaoformulario001.php?formulario=3000010' ,'1' ,'1' ,'Realiza a manutençãod e formulários para o cadastro de Rubricas' ,'true' )");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10426 ,484 ,10216 )");
    $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10427 ,'Conferência' ,'Conferência' ,'eso4_conferenciadados001.php' ,'1' ,'1' ,'Conferência' ,'true')");    
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10220 ,10427 ,1 ,10216 );");
    $this->execute("update db_itensmenu set descricao = 'Manutenção S2100 - Dados do Servidor' , help = 'Manutenção S2100 - Dados do Servidor', desctec = 'Manutenção S2100 - Dados do Servidor' , libcliente = 'true' where id_item = 10220;");
    $this->execute("update db_itensmenu set id_item = 10219 , descricao = 'Preenchimento' , help = 'Preenchimento' , funcao = 'eso4_preenchimento001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Preenche o formulário do e-social.' , libcliente = 'true' where id_item = 10219;");
    $this->execute("delete from db_menu where id_item_filho = 10219 AND modulo = 10216;");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10220 ,10219 ,2 ,10216 );");
    
  }

  public function criarTabelas() {


    $avaliacao = $this->table('avaliacao',  array('schema'=>'habitacao'));
    $avaliacao->addColumn('db101_cargadados', 'text', array('null' => true))
              ->addColumn('db101_permiteedicao', 'boolean', array('null' => false, 'default' => false))
              ->save();

    $avaliacaoPergunta = $this->table('avaliacaopergunta',  array('schema'=>'habitacao'));
    $avaliacaoPergunta->addColumn('db103_dblayoutcampo', 'integer', array('null' => true))
                      ->addColumn('db103_perguntaidentificadora', 'boolean', array('null' => true, 'default' => false))
                      ->addColumn('db103_camposql', 'string', array('null' => true))
                      ->save();

    $avaliacaoPergunta = $this->table('avaliacaoperguntaopcao',  array('schema'=>'habitacao'));
    $avaliacaoPergunta->addColumn('db104_valorresposta', 'string', array('null' => true))
                      ->save();
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009305,1009304,1009306,1009307, 1009308, 1009309)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009305,1009304,1009306,1009307, 1009308, 1009309)');
  }

  private function droparDML() {

    $this->execute('alter table habitacao.avaliacaopergunta drop COLUMN IF EXISTS db103_dblayoutcampo');
    $this->execute('alter table habitacao.avaliacaopergunta drop COLUMN IF EXISTS db103_perguntaidentificadora');
    $this->execute('alter table habitacao.avaliacaopergunta drop COLUMN IF EXISTS db103_camposql ');
    $this->execute('alter table habitacao.avaliacao drop COLUMN IF EXISTS db101_permiteedicao ');
    $this->execute('alter table habitacao.avaliacao drop COLUMN IF EXISTS db101_cargadados');
    $this->execute('alter table habitacao.avaliacaoperguntaopcao drop COLUMN IF EXISTS db104_valorresposta');
    $this->execute("update db_itensmenu set id_item = 8528 , descricao = 'Cadastro de Avaliacao' , help = 'Cadastro de Avaliacao' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro de formulários onde são cadastrados a estrutura genérica para os mesmos.' , libcliente = 'true' where id_item = 8528;");
    $this->execute("delete from db_menu where id_item = 29 and id_item_filho = 8528");
    $this->execute("delete from db_menu where id_item = 32 and id_item_filho = 10423");
    $this->execute("delete from db_menu where id_item = 32 and id_item_filho = 10425");
    $this->execute("delete from db_menu where id_item = 32 and id_item_filho = 10426");       
    $this->execute("delete from db_menu where id_item_filho = 10427");   
    $this->execute("delete from db_itensmenu where id_item in (10423, 10425, 10426, 10427)");
  }

  private function deleteDadosEsocial() {

    $this->execute("delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in ( select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000010))");
    $this->execute("delete from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000010)");
    $this->execute("delete from avaliacaogrupopergunta where db102_avaliacao = 3000010");
    $this->execute("delete from habitacao.avaliacao where db101_sequencial = 3000010");
  }


  private function dadosEsocial() {

    $this->execute(
<<<STRING
  INSERT INTO avaliacao VALUES (3000010, 5, 'S1010 - TABELA DE RUBRICAS', 'Registros do evento S-1010 - Tabela de Rubricas', true, 'S1010', 'select rh27_rubric as codigorubrica,  rh27_descr as descricaorubrica from rhrubricas where rh27_instit = fc_getsession(\'DB_instit\')::int', true);


--
-- Data for Name: avaliacaogrupopergunta; Type: TABLE DATA; Schema: public; Owner: ecidade
--

INSERT INTO avaliacaogrupopergunta VALUES (3000091, 3000010, 'Dados da Rubrica', 'TabelaRubricas');
INSERT INTO avaliacaogrupopergunta VALUES (3000093, 3000010, 'Esta rubrica possui processo determinando a não incidência de contribuição previdenciária?', 'DadosProcessosRubricas');
INSERT INTO avaliacaogrupopergunta VALUES (3000096, 3000010, 'Esta rubrica possui processo determinando a não incidência de contrinbuição sindical?', 'ideProcessoSIND');
INSERT INTO avaliacaogrupopergunta VALUES (3000094, 3000010, 'Esta rubrica possui processo determinando a não incidência de imposto de renda?');
INSERT INTO avaliacaogrupopergunta VALUES (3000095, 3000010, 'Esta rubrica possui processo determinando a não incidência de FGTS?', 'ideProcessoFGTS');


--
-- Data for Name: avaliacaopergunta; Type: TABLE DATA; Schema: public; Owner: ecidade
--

INSERT INTO avaliacaopergunta VALUES (3000436, 2, 3000091, 'Observação:',                                                                      false, true, 30, 'observacao', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000420, 2, 3000091, 'Código rubrica:',                                                                  true, true, 14, 'codRubr', 1, '', 0, true, 'codigorubrica');
INSERT INTO avaliacaopergunta VALUES (3000421, 2, 3000091, 'Identificador rubricas:',                                                          false, true, 15, 'ideTabRubr', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000422, 2, 3000091, 'Início da validade:',                                                              true, true, 16, 'iniValid', 1, '9999-99', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000423, 2, 3000091, 'Término da validade:',                                                             false, true, 17, 'fimValid', 1, '9999-99', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000424, 2, 3000091, 'Descrição da rubrica:',                                                            true, true, 19, 'dscRubr', 1, '', 0, false, 'descricaorubrica');
INSERT INTO avaliacaopergunta VALUES (3000425, 2, 3000091, 'Código de classificação da rubrica de acordo com a tabela 3: <a href="">link</a>', false, true, 20, 'natRubr', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000426, 1, 3000091, 'Tipo de rubrica:',                                                                 true, true, 21, 'tpRubr', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000427, 1, 3000091, 'Código de incidência tributária da rubrica para a previdência social:',            true, true, 22, 'codIncCP', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000428, 1, 3000091, 'Código de incidência tributária da rubrica para o irrf:',                          true, true, 23, 'codIncIRRF', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000429, 1, 3000091, 'Código de incidência da rubrica para o fgts:',                                     true, true, 24, 'codIncFGTS', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000430, 1, 3000091, 'Código de incidência tributária da rubrica para a contribuição sindical laboral:', true, true, 25, 'codIncSIND', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000431, 1, 3000091, 'Rubrica repercute no cálculo do descanso semanal remunerado:',                     true, true, 26, 'repDSR', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000432, 1, 3000091, 'Rubrica repercute no cálculo do 13º salário:',                                     true, true, 27, 'rep13', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000433, 1, 3000091, 'Rubrica repercute no cálculo das férias:',                                         true, true, 28, 'repFerias', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000435, 1, 3000091, 'Rubrica repercute no cálculo do aviso prévio:',                                    true, true, 29, 'repAviso', 1, '', 0, false, '');

INSERT INTO avaliacaopergunta VALUES (3000437, 1, 3000093, 'Tipo do processo:',                                                                false, true, 32, 'ideProcessoCP_tpProc'    , 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000438, 2, 3000093, 'Número do processo:',                                                              false, true, 33, 'ideprocessocp_nrProc'    , 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000439, 1, 3000093, 'Extensão da decisão/sentença:',                                                    false, true, 34, 'ideProcessoCP_extDecisao', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000440, 2, 3000093, 'Código do indicativo da suspensão:',                                               false, true, 35, 'codSusp'                 , 1, '', 0, false, '');

INSERT INTO avaliacaopergunta VALUES (3000445, 2, 3000096, 'Número do processo judicial:',                                                     false, true, 43, 'ideProcessoSIND_nrProc', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000446, 2, 3000096, 'Código do indicativo da suspensão:',                                               false, true, 44, 'ideProcessoSIND_codSusp', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000441, 2, 3000094, 'Número do processo judicial:',                                                     false, true, 37, 'ideProcessoIRRF_nrProc', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000442, 2, 3000094, 'Código do indicativo da suspensão:',                                               false, true, 38, 'ideProcessoIRRF_codSusp', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000443, 2, 3000095, 'Número do processo judicial:',                                                     false, true, 40, 'ideProcessoFGTS_nrProc', 1, '', 0, false, '');
INSERT INTO avaliacaopergunta VALUES (3000444, 2, 3000095, 'Código do indicativo da suspensão:',                                               false, true, 41, 'ideProcessoFGTS_codSusp', 1, '', 0, false, '');


--
-- Data for Name: avaliacaoperguntaopcao; Type: TABLE DATA; Schema: public; Owner: ecidade
--

INSERT INTO avaliacaoperguntaopcao VALUES (3001470, 3000441, '', true, 'ideProcessoIRRF_nrProc_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001374, 3000423, '', true, 'fimValid_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001445, 3000430, '91 - Incidência suspensa em decorrência de decisão judicial', false, 'incidencia_suspensa_em_decorrencia_de_decisao_judi', 0, '91');
INSERT INTO avaliacaoperguntaopcao VALUES (3001441, 3000430, '31 - Valor da contribuição sindical laboral descontada', false, 'valor_da_contribuicao_sindical_laboral_descont', 0, '31');
INSERT INTO avaliacaoperguntaopcao VALUES (3001440, 3000430, '11 - Base de cálculo;', false, 'codIncSIND_base_calculo', 0, '11');
INSERT INTO avaliacaoperguntaopcao VALUES (3001436, 3000430, '00 - Não é base de cálculo', false, 'nao_e_base_de_calculo_sind', 0, '00');
INSERT INTO avaliacaoperguntaopcao VALUES (3001471, 3000442, '', true, 'ideProcessoIRRF_codSusp_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001473, 3000444, '', true, 'ideProcessoFGTS_codSusp_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001371, 3000420, '', true, 'codRubr_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001373, 3000422, '', true, 'iniValid_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001375, 3000424, '', true, 'dscRubr_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001376, 3000425, '', true, 'natRubr_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001461, 3000433, 'Não', false, 'repFerias_nao', 0, 'N');
INSERT INTO avaliacaoperguntaopcao VALUES (3001460, 3000433, 'Sim', false, 'repFerias_sim', 0, 'S');
INSERT INTO avaliacaoperguntaopcao VALUES (3001475, 3000446, '', true, 'ideProcessoSIND_codSusp_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001463, 3000435, 'Não', false, 'repAviso_nao', 0, 'N');
INSERT INTO avaliacaoperguntaopcao VALUES (3001462, 3000435, 'Sim', false, 'repAviso_sim', 0, 'S');
INSERT INTO avaliacaoperguntaopcao VALUES (3001380, 3000426, 'Informativa dedutora', false, 'informativa_dedutora', 4, '4');
INSERT INTO avaliacaoperguntaopcao VALUES (3001379, 3000426, 'Informativa', false, 'informativa', 3, '3');
INSERT INTO avaliacaoperguntaopcao VALUES (3001378, 3000426, 'Desconto', false, 'desconto', 2, '2');
INSERT INTO avaliacaoperguntaopcao VALUES (3001377, 3000426, 'Vencimento, provento ou pensão', false, 'vencimento_provento_ou_pensao', 1, '1');
INSERT INTO avaliacaoperguntaopcao VALUES (3001456, 3000428, 'Deduções IRRF - Compensação judicial de anos anteriores', false, 'deducoes_irrf__compensacao_judicial_de_anos_anteri', 83, '83');
INSERT INTO avaliacaoperguntaopcao VALUES (3001454, 3000428, 'Deduções IRRF - Compensação judicial do ano calendário ', false, 'deducoes_irrf__compensacao_judicial_do_ano_calenda', 82, '82');
INSERT INTO avaliacaoperguntaopcao VALUES (3001453, 3000428, 'Deduções IRRF - Depósito judicial ', false, 'deducoes_irrf__deposito_judicial_', 81, '81');
INSERT INTO avaliacaoperguntaopcao VALUES (3001452, 3000428, 'Deduções IRRF - Outras isenções (o nome da rubrica deve ser claro para identificação da natureza dos valores) Demandas Judiciais', false, 'deducoes_irrf__outras_isencoes_o_nome_da_rubrica_d', 79, '79');
INSERT INTO avaliacaoperguntaopcao VALUES (3001451, 3000428, 'Deduções IRRF - Valores pagos a titular ou sócio de microempresa ou empresa de pequeno porte, exceto pró-labore e alugueis ', false, 'deducoes_irrf__valores_pagos_a_titular_ou_socio_pr', 78, '78');
INSERT INTO avaliacaoperguntaopcao VALUES (3001450, 3000428, 'Deduções IRRF - Pensão, aposentadoria ou reforma por moléstia grave ou acidente em serviço - 13° salário', false, 'deducoes_irrf__pensao_aposentadori13', 77, '77');
INSERT INTO avaliacaoperguntaopcao VALUES (3001448, 3000428, 'Deduções IRRF - Pensão, aposentadoria ou reforma por moléstia grave ou acidente em serviço - Remuneração Mensal ', false, 'deducoes_irrf__pensao_aposentadoria_ou_reforma_por', 76, '76');
INSERT INTO avaliacaoperguntaopcao VALUES (3001447, 3000428, 'Deduções IRRF - Abono pecuniário', false, 'deducoes_irrf__abono_pecuniario', 75, '75');
INSERT INTO avaliacaoperguntaopcao VALUES (3001446, 3000428, 'Deduções IRRF - Indenização e rescisão de contrato, inclusive a título de PDV e acidentes de trabalho ', false, 'deducoes_irrf__indenizacao_e_rescisao_de_contrato_', 74, '74');
INSERT INTO avaliacaoperguntaopcao VALUES (3001444, 3000428, 'Deduções IRRF - Ajuda de custo', false, 'deducoes_irrf__ajuda_de_custo', 73, '73');
INSERT INTO avaliacaoperguntaopcao VALUES (3001443, 3000428, 'Deduções IRRF - Diárias ', false, 'deducoes_irrf__diarias_', 72, '72');
INSERT INTO avaliacaoperguntaopcao VALUES (3001442, 3000428, 'Deduções IRRF - Parcela Isenta 65 anos - 13° salário ', false, 'deducoes_irrf__parcela_isenta_65_anos__13_salario_', 71, '71');
INSERT INTO avaliacaoperguntaopcao VALUES (3001439, 3000428, 'Deduções IRRF - Parcela Isenta 65 anos - Remuneração mensal ', false, 'parcela_isenta_65_anos__remuneracao_mensal_', 70, '70');
INSERT INTO avaliacaoperguntaopcao VALUES (3001438, 3000428, 'Deduções IRRF - Fundação de Previdência Complementar do Servidor Público Federal - Funpresp - 13° salário  Isenções do IRRF', false, 'fundacao_de_previdencia_complementar_do_13', 64, '64');
INSERT INTO avaliacaoperguntaopcao VALUES (3001435, 3000428, 'Deduções IRRF - Fundação de Previdência Complementar do Servidor Público Federal - Funpresp - Remuneração mensal ', false, 'fundacao_de_previdencia_complementar_do_servidor_p', 63, '63');
INSERT INTO avaliacaoperguntaopcao VALUES (3001433, 3000428, 'Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - 13° salário ', false, 'fundo_de_aposentadoria_programada_individual__fapi', 62, '62');
INSERT INTO avaliacaoperguntaopcao VALUES (3001432, 3000428, 'Deduções IRRF - Fundo de Aposentadoria Programada Individual - FAPI - Remuneração Mensal', false, 'deducoes_irrf__fundo_de_aposentadoria_programada_i', 61, '61');
INSERT INTO avaliacaoperguntaopcao VALUES (3001431, 3000428, 'Deduções IRRF - Pensão Alimentícia - RRA', false, 'deducoes_irrf__pensao_alimenticia__rra', 55, '55');
INSERT INTO avaliacaoperguntaopcao VALUES (3001430, 3000428, 'Deduções IRRF - Pensão Alimentícia - PLR', false, 'deducoes_irrf__pensao_alimenticia__plr', 54, '54');
INSERT INTO avaliacaoperguntaopcao VALUES (3001429, 3000428, 'Deduções IRRF - Pensão Alimentícia - Férias', false, 'deducoes_irrf__pensao_alimenticia__ferias', 53, '53');
INSERT INTO avaliacaoperguntaopcao VALUES (3001428, 3000428, 'Deduções IRRF - Pensão Alimentícia - 13° salário', false, 'deducoes_irrf__pensao_alimenticia__13_salario', 52, '52');
INSERT INTO avaliacaoperguntaopcao VALUES (3001427, 3000428, 'Deduções IRRF - Pensão Alimentícia - Remuneração mensal', false, 'deducoes_irrf__pensao_alimenticia__remuneracao_men', 51, '51');
INSERT INTO avaliacaoperguntaopcao VALUES (3001426, 3000428, 'Deduções IRRF - Previdência Privada - 13° salário ', false, 'deducoes_irrf__previdencia_privada__13_salario_', 47, '47');
INSERT INTO avaliacaoperguntaopcao VALUES (3001424, 3000428, 'Deduções IRRF - Previdência Privada - salário mensal', false, 'deducoes_irrf__previdencia_privada__salario_mensal', 46, '46');
INSERT INTO avaliacaoperguntaopcao VALUES (3001423, 3000428, 'Deduções IRRF - PSO - RRA', false, 'deducoes_irrf__pso__rra', 44, '44');
INSERT INTO avaliacaoperguntaopcao VALUES (3001421, 3000428, 'Deduções IRRF - PSO - Férias ', false, 'deducoes_irrf__pso__ferias_', 43, '43');
INSERT INTO avaliacaoperguntaopcao VALUES (3001420, 3000428, 'Deduções IRRF - PSO - 13° salário', false, 'deducoes_irrf__pso__13_salario', 42, '42');
INSERT INTO avaliacaoperguntaopcao VALUES (3001418, 3000428, 'eduções IRRF - Previdência Social Oficial - PSO - Remuner. mensal ', false, 'educoes_irrf__previdencia_social_oficial__pso__rem', 41, '41');
INSERT INTO avaliacaoperguntaopcao VALUES (3001415, 3000428, 'Retenções do IRRF efetuadas sobre - RRA', false, 'retencoes_do_irrf_efetuadas_sobre__rra', 35, '35');
INSERT INTO avaliacaoperguntaopcao VALUES (3001413, 3000428, 'Retenções do IRRF efetuadas sobre - PLR', false, 'retencoes_do_irrf_efetuadas_sobre__plr', 34, '34');
INSERT INTO avaliacaoperguntaopcao VALUES (3001412, 3000428, 'Retenções do IRRF efetuadas sobre - Férias', false, 'retencoes_do_irrf_efetuadas_sobre__ferias', 33, '33');
INSERT INTO avaliacaoperguntaopcao VALUES (3001411, 3000428, 'Retenções do IRRF efetuadas sobre - 13o Salário', false, 'retencoes_do_irrf_efetuadas_sobre__13o_salario', 32, '32');
INSERT INTO avaliacaoperguntaopcao VALUES (3001410, 3000428, 'Retenções do IRRF efetuadas sobre - Remuneração mensal ', false, 'retencoes_do_irrf_efetuadas_sobre__remuneracao_men', 31, '31');
INSERT INTO avaliacaoperguntaopcao VALUES (3001409, 3000428, 'Rendimentos tributáveis base de IRRF - RRA', false, 'rendimentos_tributaveis_base_de_irrf__rra', 15, '15');
INSERT INTO avaliacaoperguntaopcao VALUES (3001408, 3000428, 'Rendimentos tributáveis base de IRRF - PLR', false, 'rendimentos_tributaveis_base_de_irrf__plr', 14, '14');
INSERT INTO avaliacaoperguntaopcao VALUES (3001407, 3000428, 'Rendimentos tributáveis base de IRRF - Férias ', false, 'rendimentos_tributaveis_base_de_irrf__ferias', 13, '13');
INSERT INTO avaliacaoperguntaopcao VALUES (3001406, 3000428, 'Rendimentos tributáveis base de IRRF - 13o Salário', false, 'rendimentos_tributaveis_base_de_irrf__13o_salario', 12, '12');
INSERT INTO avaliacaoperguntaopcao VALUES (3001405, 3000428, 'Rendimentos tributáveis base de IRRF - Remuneração mensal ', false, 'rendimentos_tributaveis_base_de_irrf__remuneracao', 11, '11');
INSERT INTO avaliacaoperguntaopcao VALUES (3001404, 3000428, 'Rendimento não tributável em função de acordos internacionais de bitributação', false, 'rendimento_nao_tributavel_em_funcao_de_acordos_int', 1, '01');
INSERT INTO avaliacaoperguntaopcao VALUES (3001403, 3000428, 'Rendimento não tributável', false, 'rendimento_nao_tributavel', 0, '00');
INSERT INTO avaliacaoperguntaopcao VALUES (3001425, 3000429, '91 - Incidência suspensa em decorrência de decisão judicial', false, 'incidencia_suspensa_em_decorrencia_de_decisao_jud', 0, '91');
INSERT INTO avaliacaoperguntaopcao VALUES (3001422, 3000429, '21 - Base de Cálculo do FGTS Rescisório (aviso prévio)', false, 'base_de_calculo_do_fgts_rescisorio_aviso_previ', 0, '21');
INSERT INTO avaliacaoperguntaopcao VALUES (3001419, 3000429, '12 - Base de Cálculo do FGTS 13° salário', false, 'base_de_calculo_do_fgts_13_salario', 0, '12');
INSERT INTO avaliacaoperguntaopcao VALUES (3001417, 3000429, '11 - Base de Cálculo do FGTS', false, 'base_de_calculo_do_fgts', 0, '11');
INSERT INTO avaliacaoperguntaopcao VALUES (3001416, 3000429, '00 - Não é Base de Cálculo do FGTS', false, 'nao_e_base_de_calculo_do_fgts', 0, '00');
INSERT INTO avaliacaoperguntaopcao VALUES (3001466, 3000437, '2 - Judicial', false, 'tipo_processo_judicial', 0, '2');
INSERT INTO avaliacaoperguntaopcao VALUES (3001465, 3000437, '1 - Administrativo', false, 'tipo_processo_administrativo', 0, '1');
INSERT INTO avaliacaoperguntaopcao VALUES (3001472, 3000443, '', true, 'ideProcessoFGTS_nrProc_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001402, 3000427, 'Suspensão de incidência sobre Salário por decisão judicial - Salário maternidade 13o salário', false, 'suspensao_de_incidencia_decisao_maternidade_13', 94, '94');
INSERT INTO avaliacaoperguntaopcao VALUES (3001401, 3000427, 'Suspensão de incidência sobre Salário por decisão judicial - Salário maternidade', false, 'suspensao_de_incidencia_sobre_maternidade', 93, '93');
INSERT INTO avaliacaoperguntaopcao VALUES (3001400, 3000427, 'Suspensão de incidência sobre Salário por decisão judicial - 13o Salário', false, 'suspensao_de_incidencia_sobre_13_salario', 92, '92');
INSERT INTO avaliacaoperguntaopcao VALUES (3001399, 3000427, 'Suspensão de incidência sobre Salário por decisão judicial - Mensal', false, 'suspensao_de_incidencia_sobre_salario_por_decisao_', 91, '91');
INSERT INTO avaliacaoperguntaopcao VALUES (3001398, 3000427, 'Outros - Complemento de salário-mínimo - Regime próprio de previdência social', false, 'outros__complemento_de_salariominimo__regime_propr', 61, '61');
INSERT INTO avaliacaoperguntaopcao VALUES (3001397, 3000427, 'Outros - Salário-família', false, 'outros__salariofamilia', 51, '51');
INSERT INTO avaliacaoperguntaopcao VALUES (3001396, 3000427, 'Contribuição descontada sobre salário - SENAT', false, 'contribuicao_descontada_sobre_salario__senat', 35, '35');
INSERT INTO avaliacaoperguntaopcao VALUES (3001395, 3000427, 'Contribuição descontada sobre salário - SEST', false, 'contribuicao_descontada_sobre_salario__sest', 34, '34');
INSERT INTO avaliacaoperguntaopcao VALUES (3001394, 3000427, 'Contribuição descontada sobre salário - 13o Salário', false, 'contribuicao_descontada_sobre_salario__13o_salario', 32, '32');
INSERT INTO avaliacaoperguntaopcao VALUES (3001393, 3000427, 'Contribuição descontada sobre salário - Mensal', false, 'contribuicao_descontada_sobre_salario__mensal', 31, '31');
INSERT INTO avaliacaoperguntaopcao VALUES (3001392, 3000427, 'ase de cálculo das contribuições sociais - Salário maternidade - 13° salário, pago pelo INSS;', false, 'ase_de_calculo_das_contribuicoes_sociais_inss', 26, '26');
INSERT INTO avaliacaoperguntaopcao VALUES (3001391, 3000427, 'Base de cálculo das contribuições sociais - Salário maternidade mensal pago pelo INSS;', false, 'base_de_calculo_das_contribuicoes_sociais__mensal', 25, '25');
INSERT INTO avaliacaoperguntaopcao VALUES (3001390, 3000427, 'Base de cálculo das contribuições sociais - Auxilio doença 13o salário doença - Regime próprio de previdência social;', false, 'base_de_calculo__auxilio_regime', 24, '24');
INSERT INTO avaliacaoperguntaopcao VALUES (3001388, 3000427, 'Base de cálculo das contribuições sociais - Auxilio doença mensal - Regime Próprio de Previdência Social;', false, 'base_de_calculo_das_contribuicoes_sociais__auxilio', 23, '23');
INSERT INTO avaliacaoperguntaopcao VALUES (3001387, 3000427, 'Base de cálculo das contribuições sociais - Salário maternidade - 13o Salário, pago pela empresa', false, 'base_de_calculo_das___decimosalario', 22, '22');
INSERT INTO avaliacaoperguntaopcao VALUES (3001385, 3000427, 'Base de cálculo das contribuições sociais - Salário maternidade mensal pago pela empresa;', false, 'base_de_calculo_das_contribuicoes_sociais__salario', 21, '21');
INSERT INTO avaliacaoperguntaopcao VALUES (3001384, 3000427, 'Base de cálculo das contribuições sociais - 13o Salário', false, 'base_de_calculo_das_contribuicoes_sociais__13o_sal', 12, '12');
INSERT INTO avaliacaoperguntaopcao VALUES (3001383, 3000427, 'Não é base de cálculo em função de acordos internacionais de previdência social;', false, 'nao_e_base_de_calculo_em_funcao_de_acordos_interna', 1, '01');
INSERT INTO avaliacaoperguntaopcao VALUES (3001382, 3000427, 'Não é base de cálculo', false, 'nao_e_base_de_calculo', 0, '00');
INSERT INTO avaliacaoperguntaopcao VALUES (3001381, 3000427, 'Base de cálculo das contribuições sociais - Mensal', false, 'base_de_calculo_das_contribuicoes_sociais_salario', 11, '11');
INSERT INTO avaliacaoperguntaopcao VALUES (3001457, 3000431, 'Não', false, 'repDSR_nao', 0, 'N');
INSERT INTO avaliacaoperguntaopcao VALUES (3001455, 3000431, 'Sim', false, 'repDSR_sim', 0, 'S');
INSERT INTO avaliacaoperguntaopcao VALUES (3001474, 3000445, '', true, 'ideProcessoSIND_nrProc_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001372, 3000421, '', true, 'ideTabRubr_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001469, 3000440, '', true, 'codSusp_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001464, 3000436, '', true, 'observacao_2', 0, '');
INSERT INTO avaliacaoperguntaopcao VALUES (3001459, 3000432, 'Não', false, 'rep13_nao', 0, 'N');
INSERT INTO avaliacaoperguntaopcao VALUES (3001458, 3000432, 'Sim', false, 'rep13_sim', 0, 'S');
INSERT INTO avaliacaoperguntaopcao VALUES (3001468, 3000439, '2 - Contribuição Previdenciária Patronal + Descontada dos Segurados', false, 'extcontribuicao_previ_patronal_descontad', 0, '2');
INSERT INTO avaliacaoperguntaopcao VALUES (3001467, 3000439, '1 - Contribuição Previdenciária Patronal', false, 'extDecisao_contribuicao_previdenciaria_patronal', 0, '1');
INSERT INTO avaliacaoperguntaopcao VALUES (3001476, 3000438, '', true, 'respideprocessocp_nrProc ', 0, '');
STRING
      );
  }

}
