------
-- [FINANCEIRO - INICIO]
------

-- 97342
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
     values ( 10024 ,'Delib. 200/96 - TCE RJ' ,'Deliberação 200/69 - TCE RJ' ,'' ,'1' ,'1' ,'Deliberação 200/69 - TCE RJ' ,'true' ),
            ( 10025 ,'Modelo 2' ,'Demonstrativo de Adiantamentos Recebidos' ,'emp2_deliberacao20096adiantamentos001.php?modelo=2' ,'1' ,'1' ,'Demonstrativo de Adiantamentos Recebidos' ,'true' ),
            ( 10026 ,'Modelo 3' ,'Demonstrativo de Subvenções e Auxílios' ,'emp2_deliberacao20096adiantamentos001.php?modelo=3' ,'1' ,'1' ,'Demonstrativo de Subvenções e Auxílios' ,'true' );

insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo )
     values ( 30 ,10024 ,441 ,398 ),
            ( 10024 ,10025 ,1 ,398 ),
            ( 10024 ,10026 ,2 ,398 );

insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
     values ( 20881 ,'e44_naturezaevento' ,'int4' ,'Natureza do Evento' ,'1' ,'Natureza do Evento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Natureza do Evento' );
insert into db_syscampodef ( codcam ,defcampo ,defdescr )
     values ( 20881 ,'1' ,'Não se Aplica' ),
            ( 20881 ,'2' ,'Adiantamentos Concedidos' ),
            ( 20881 ,'3' ,'Subvenções e Auxílios' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
     values ( 1038 ,20881 ,4 ,0 );

insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
     values ( 20884 ,'e45_processoadministrativo' ,'varchar(20)' ,'Processo Administrativo' ,'' ,'Processo Administrativo' ,20 ,'true' ,'true' ,'false' ,0 ,'text' ,'Processo Administrativo' ),
            ( 20889 ,'e45_datalimiteaplicacao' ,'date' ,'Data Limite para Aplicação' ,'' ,'Data Limite para Aplicação' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Data Limite para Aplicação' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
     values ( 1036 ,20884 ,9 ,0 ),
            ( 1036 ,20889 ,10 ,0 );

-- Assinatura
insert into db_tipodoc( db08_codigo ,db08_descr )
     values ( 5016 ,'RELATÓRIO ADIANTAMENTOS SUBVENÇÕES' );

insert into db_documentopadrao( db60_coddoc ,db60_descr ,db60_tipodoc ,db60_instit )
     select nextval('db_documentopadrao_db60_coddoc_seq') ,'RELATORIO ADIANTAMENTOS SUBVENÇÕES' ,5016 , codigo from db_config;

drop table if exists db_paragrafopadrao_97342;
create temp table db_paragrafopadrao_97342 as select nextval('db_paragrafopadrao_db61_codparag_seq') as sequencial, codigo from db_config;

insert into db_paragrafopadrao( db61_codparag ,db61_descr ,db61_texto ,db61_alinha ,db61_inicia ,db61_espaco ,db61_alinhamento ,db61_altura ,db61_largura ,db61_tipo )
     select sequencial, 'PARAGRAFO 1' ,'$nWidth = $oPdf->getAvailWidth(); $oPdf->setBold(true); $oPdf->cell($nWidth*0.33, 4, \"Elaborado Por\", 1, 0, \'C\'); $oPdf->cell($nWidth*0.33, 4, \"Conferido Por\", 1, 0, \'C\'); $oPdf->cell($nWidth*0.24, 4, \"Visto\", 1, 0, \'C\'); $oPdf->cell($nWidth*0.10, 4, \"Data\", 1, 1, \'C\'); $oPdf->setBold(false); $oPdf->cell($nWidth*0.33, 4, \"Nome\", \'L:R\'); $oPdf->cell($nWidth*0.33, 4, \'\', \'L:R\'); $oPdf->cell($nWidth*0.24, 4, \'\', \'L:R\'); $oPdf->cell($nWidth*0.10, 4, \'\', \'L:R\', 1); $oPdf->cell($nWidth*0.33, 4, \"Matrícula\", \'L:R\'); $oPdf->cell($nWidth*0.33, 4, \'\', \'L:R\'); $oPdf->cell($nWidth*0.24, 4, \'\', \'L:R\'); $oPdf->cell($nWidth*0.10, 4, \'\', \'L:R\', 1); $oPdf->cell($nWidth*0.33, 4, \"Assinatura\", \'L:R:B\'); $oPdf->cell($nWidth*0.33, 4, \'\', \'L:R:B\'); $oPdf->cell($nWidth*0.24, 4, \'\', \'L:R:B\'); $oPdf->cell($nWidth*0.10, 4, \'\', \'L:R:B\', 1);' ,0 ,0 ,1 ,'J' ,0 ,0 ,3
       from db_paragrafopadrao_97342;

insert into db_docparagpadrao( db62_coddoc ,db62_codparag ,db62_ordem )
     select db60_coddoc, db_paragrafopadrao_97342.sequencial, db60_instit
       from db_documentopadrao inner join db_paragrafopadrao_97342 on db_paragrafopadrao_97342.codigo = db60_instit where db60_tipodoc = 5016;

------
-- [FINANCEIRO - FIM]
------

------
-- [FOLHA - INICIO]
------

insert into db_sysarquivo values (3757, 'rhreajusteparidade', 'Guarda informação do tipo de reajuste, ligada com a tabela rhpessoal.', 'rh148', '2014-12-01', 'Reajuste', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3757);
insert into db_syscampo values(20882,'rh148_sequencial','int4','Sequencial da tabela rhreajusteparidade','0', 'Sequencial',20,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20883,'rh148_descricao','varchar(50)','Descrição do reajuste.','', 'Descrição',50,'f','f','f',3,'text','Descrição');
delete from db_sysarqcamp where codarq = 3757;
insert into db_sysarqcamp values(3757,20882,1,0);
insert into db_sysarqcamp values(3757,20883,2,0);
delete from db_sysprikey where codarq = 3757;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3757,20882,1,20882);
update db_syscampo set nomecam = 'rh01_reajusteparidade', conteudo = 'int4', descricao = 'Campo que traz a informação do tipo de reajuste do servidor, fazendo ligação com a rhreajusteparidade', valorinicial = '0', rotulo = 'Tipo de Reajuste', nulo = 'f', tamanho = 20, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Reajuste' where codcam = 20685;
delete from db_syscampodep where codcam = 20685;
delete from db_syscampodef where codcam = 20685;
delete from db_sysforkey where codarq = 1153 and referen = 0;
insert into db_sysforkey values(1153,20685,1,3757,0);

-- Relatório por Tipo de Reajuste
update db_relatorio
set db63_xmlestruturarel = '<?xml version="1.0" encoding="ISO-8859-1"?>
<Relatorio>
 <Versao>1.0</Versao>
 <Propriedades versao="1.0" nome="Relatório de Servidores por Tipo de Reajuste" layout="dbseller" formato="A4" orientacao="portrait" margemsup="0" margeminf="0" margemesq="20" margemdir="20" tiposaida="pdf"/>
 <Cabecalho></Cabecalho>
 <Rodape></Rodape>
 <Variaveis>
  <Variavel nome="$sPadrao" label="" tipodado="varchar" valor=""/>
  <Variavel nome="$iReajuste" label="" tipodado="int4" valor="0"/>
  <Variavel nome="$iCargo" label="" tipodado="int4" valor="0"/>
  <Variavel nome="$iRegime" label="" tipodado="int4" valor="0"/>
  <Variavel nome="$iLotacao" label="" tipodado="int4" valor="0"/>
 </Variaveis>
 <Campos>
  <Campo id="7024" nome="rh02_regist" alias="Matrícula" largura="18" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="217" nome="z01_nome" alias="Nome" largura="90" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="15613" nome="rh88_descricao" alias="Tipo Aposentadoria" largura="55" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="15614" nome="rh01_descricaoreajusteparidade" alias="Tipo de Reajuste" largura="30" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
 </Campos>
 <Consultas>
  <Consulta tipo="Principal">
   <Select>
    <Campo id="7024"/>
    <Campo id="217"/>
    <Campo id="15613"/>
    <Campo id="15614"/>
   </Select>
   <From>SELECT DISTINCT ON (rh02_regist)
                rh02_regist,
                z01_nome,
                rh88_descricao,
                rh148_descricao AS rh01_descricaoreajusteparidade
FROM rhpessoal
     INNER JOIN rhpessoalmov ON rh01_regist = rh02_regist
                            AND rh01_instit = rh02_instit
     INNER JOIN cgm ON rh01_numcgm = z01_numcgm
     INNER JOIN rhreajusteparidade ON rh01_reajusteparidade = rh148_sequencial
     LEFT  JOIN rhpespadrao ON rh02_seqpes = rh03_seqpes
     LEFT  JOIN rhtipoapos ON rh02_rhtipoapos = rh88_sequencial
WHERE rh01_instit                               = fc_getsession(''DB_instit'')::integer
  AND rh01_reajusteparidade                     = $iReajuste
  AND CASE WHEN $iCargo   &gt;  0 THEN rh02_funcao   = $iCargo   ELSE true END
  AND CASE WHEN $iRegime  &gt;  0 THEN rh02_codreg = $iRegime  ELSE true END
  AND CASE WHEN $iLotacao &gt;  0 THEN rh02_lota = $iLotacao ELSE true END
  AND CASE WHEN $sPadrao &lt;&gt; '''' THEN rh03_padrao = $sPadrao  ELSE true END
  GROUP BY z01_nome, rh02_regist, rh88_descricao, rh148_descricao, rh02_anousu, rh02_mesusu
  ORDER BY rh02_regist, rh02_anousu DESC, rh02_mesusu DESC</From>
   <Where/>
   <Group></Group>
   <Order>
    <Ordem id="217" nome="z01_nome" ascdesc="asc" alias="Nome"/>
   </Order>
  </Consulta>
 </Consultas>
</Relatorio>
'
where db63_sequencial = 28;

insert into db_syscampo values(20897,'rh03_padraoprev','varchar(10)','Padrão para desconto da previdência, caso o desconto da previdência não corresponda ao padrão normal.','', 'Padrão de Previdência',10,'t','f','f',3,'text','Padrão de Previdência');
delete from db_sysarqcamp where codarq = 1159;
insert into db_sysarqcamp values(1159,7039,1,0);
insert into db_sysarqcamp values(1159,7643,2,0);
insert into db_sysarqcamp values(1159,7644,3,0);
insert into db_sysarqcamp values(1159,7040,4,0);
insert into db_sysarqcamp values(1159,20897,5,0);
insert into db_sysarqcamp values(1159,7642,6,0);

-- Comparativo de Férias
insert into db_syscampo
values(20899,'r11_compararferias','bool','Vai dizer para o cálculo se deve efetuar o comparativo de férias. Para obedecer o estatuto no qual estipula que o 1/3 de férias seja calculado à partir da comparação entre os pontos de salário e de férias no mês de pagamento, pegando o maior e dividindo por 3.','f', 'Efetuar Comparativo',1,'f','f','f',5,'text','Efetuar Comparativo'),
      (20900,'r11_baseferias','varchar(4)','Base de férias que será utilizada no comparativo de férias no momento da execução do cálculo.','', 'Base de Férias',4,'t','t','f',3,'text','Base de Férias'),
      (20901,'r11_basesalario','varchar(4)','Base de salário que será utilizada no comparativo de férias no momento da execução do cálculo.','', 'Base de Salário',4,'t','t','f',3,'text','Base de Salário');

insert into db_sysarqcamp
values(536,20899,84,0),
      (536,20900,85,0),
      (536,20901,86,0);

insert into db_syscampodef values(20899,'Sim','1');
insert into db_syscampodef values(20899,'Não','0');

update db_syscampo
set descricao    = 'Valor default no formulário de inclusão de férias que informa se é pagamento de 1/3 de férias.',
    rotulo       = 'Pagar 1/3 Férias',
    rotulorel    = 'Pagar 1/3 Férias'
    where codcam = 8930;

update db_syscampo
set descricao    = 'Paga dias de férias como férias ou como salário.',
    rotulo       = 'Pagar Como',
    rotulorel    = 'Pagar Como'
    where codcam = 3805;

update db_syscampo
set descricao    = 'Valor default ao entrar no formulário de inclusão de férias que informa se o usuário deseja pagar férias complementar ou salário',
    rotulo       = 'Pagar Férias',
    rotulorel    = 'Pagar Férias'
    where codcam = 8929;

update db_syscampo
set descricao    = 'Pagar abono de férias',
    rotulo       = 'Pagar Abono de Férias',
    rotulorel    = 'Pagar Abono de Férias'
    where codcam = 3804;

update db_syscampo
set descricao    = 'Proporcionaliza para estatutário',
    rotulo       = 'Proporcionalizar Estatutário',
    rotulorel    = 'Proporcionaliza'
    where codcam = 4582;

update db_syscampo
set descricao    = 'Proporcionaliza para celetista',
    rotulo       = 'Proporcionalizar Celetista',
    rotulorel    = 'Proporcionaliza'
    where codcam = 4583;

update db_syscampo
set descricao    = 'Informa se deve ser realizado o recalculo de 1/3 de férias',
    rotulo       = 'Recalcular 1/3 Férias Mês Gozo',
    rotulorel    = 'Recalcular 1/3 Férias Mês Gozo'
    where codcam = 3803;

-- Campo para vinculação de instituição à fundamentação legal
insert into db_syscampo values(20902,'rh137_instituicao','int4','Instituição que a fundamentação legal foi criada.','','Instituição',10,'f','f','f',1,'text','Instituição');
insert into db_sysarqcamp  values(3697,20902,7,0);
delete from db_sysforkey where codarq = 3697 and referen = 0;
insert into db_sysforkey values(3697,20902,1,83,0);
delete from db_sysprikey where codarq = 3697;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3697,20533,1,20535);

-- Alteração do valor default do campo de fundamentação legal na rhrubricas
update db_syscampo set valorinicial = null where codcam = 20539;

-- Layout Integração Bancária TCE-RO
INSERT INTO db_layouttxt(db50_codigo, db50_layouttxtgrupo, db50_descr, db50_quantlinhas, db50_obs)
VALUES (221, 1, 'LAYOUT INTEGRAÇÃO BANCÁRIA TCE-RO', 0, 'O layout é único, tanto para o arquivo de envio como para o arquivo de retorno. No arquivo de envio/remessa constarão as informações enviadas pelo BANCO para a consignação em folha de pagamento da Entidade Pública. No arquivo de retorno constarão as informações consignadas ou não em folha de pagamento da Entidade Pública, para o BANCO;');

-- Linhas
INSERT INTO db_layoutlinha(db51_codigo, db51_layouttxt, db51_descr, db51_tipolinha, db51_tamlinha, db51_linhasantes, db51_linhasdepois, db51_obs, db51_separador, db51_compacta)
VALUES (724, 221, 'HEADER',   1, 200, 0, 0, 'Cabeçalho',                '', '0'),
       (725, 221, 'DETALHE',  3, 200, 0, 0, 'Registro de Funcionários', '', '0'),
       (726, 221, 'TRAILLER', 5, 200, 0, 0, 'Rodapé',                   '', '0');

-- Header
INSERT INTO db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
VALUES (11851, 724, 'tipo_registro',           'TIPO DE REGISTRO',       1, 1,  '', 6,   'f', 't', 'd', 'O campo recebe valores alfanuméricos e deverá ser preenchido com \"BCEC00\" para envio e \"BCER00\" para retorno;',                          0),
       (11852, 724, 'codigo_convenio',         'CÓDIGO DO CONVÊNIO',     2, 7,  '', 8,   'f', 't', 'e', 'Código do convênio no BANCO, informação enviada pelo BANCO;',                                                                                0),
       (11853, 724, 'nome_convenio',           'NOME DO CONVÊNIO',       1, 15, '', 50,  'f', 't', 'd', 'Nome da Entidade Pública;',                                                                                                                  0),
       (11854, 724, 'competencia_consignacao', 'ANO MÊS DA CONSIGNAÇÃO', 9, 65, '', 6,   'f', 't', 'd', 'Ano e mês da folha de pagamento que está sendo consignada; O campo deverá ser preenchido com o ano contendo 4 dígitos e mês com 2 dígitos;', 0),
       (11855, 724, 'filler',                  'FILLER',                 1, 71, '', 130, 'f', 't', 'd', 'São espaços, sem informações no final de cada linha até completar o total de 200 colunas;',                                                  0);

-- Detalhe
INSERT INTO db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
VALUES (11856, 725, 'tipo_registro',              'TIPO DE REGISTRO',                       1, 1,   '', 6,  'f', 't', 'd', 'O campo recebe valores alfanuméricos e deverá ser preenchido com \"BCEC10\" para envio e \"BCER10\" para retorno;',                                                                                                                                                                                                                                                       0),
       (11857, 725, 'oa',                         'OA',                                     2, 7,   '', 6,  'f', 't', 'e', 'Código do órgão/entidade que será fornecido pelo BANCO para cada uma das Entidades;',                                                                                                                                                                                                                                                                                     0),
       (11858, 725, 'matricula_funcionario',      'MATRÍCULA DO FUNCIONÁRIO',               2, 13,  '', 15, 'f', 't', 'e', 'Campo obrigatório; Código da matrícula do servidor na Entidade;',                                                                                                                                                                                                                                                                                                         0),
       (11859, 725, 'cpf',                        'CPF DO FUNCIONÁRIO',                     7, 28,  '', 11, 'f', 't', 'e', 'Número do CPF do servidor;',                                                                                                                                                                                                                                                                                                                                              0),
       (11860, 725, 'nome',                       'NOME DO FUNCIONÁRIO',                    1, 39,  '', 35, 'f', 't', 'd', 'Nome do servidor;',                                                                                                                                                                                                                                                                                                                                                       0),
       (11861, 725, 'codigo_canal',               'CÓDIGO DO CANAL',                        2, 74,  '', 5,  'f', 't', 'e', 'Código que identifica o empréstimo no contracheque do funcionário, definido como código do evento pela Entidade e repassado ao BANCO; Rubrica;',                                                                                                                                                                                                                          0),
       (11862, 725, 'numero_contrato',            'NÚMERO DO CONTRATO',                     2, 79,  '', 20, 'f', 't', 'e', 'Código da operação, fornecido pelo BANCO;',                                                                                                                                                                                                                                                                                                                               0),
       (11863, 725, 'data_da_contratacao',        'DATA DA CONTRATAÇÃO',                    4, 99,  '', 8,  'f', 't', 'e', 'Data da assinatura do contrato;',                                                                                                                                                                                                                                                                                                                                         0),
       (11864, 725, 'data_vencimento_parcela',    'DATA DE VENCIMENTO DA PARCELA',          4, 107, '', 8,  'f', 't', 'e', 'Data de vencimento da parcela;',                                                                                                                                                                                                                                                                                                                                          0),
       (11865, 725, 'data_vencimento_emprestimo', 'DATA DE VENCIMENTO FINAL DO EMPRÉSTIMO', 4, 115, '', 8,  'f', 't', 'e', 'Data de vencimento final do empréstimo;',                                                                                                                                                                                                                                                                                                                                 0),
       (11866, 725, 'prestacao',                  'PRESTAÇÃO',                              1, 123, '', 7,  'f', 't', 'd', 'AAA/BBB; AAA: Número da parcela a consignar; BBB: Quantidade de parcelas do contrato;',                                                                                                                                                                                                                                                                                   0),
       (11867, 725, 'valor_consignar',            'VALOR A CONSIGNAR',                      3, 130, '', 15, 'f', 't', 'e', '13 inteiros e 2 decimais; Valor enviado pelo BANCO a ser consignado em Folha de Pagamento;',                                                                                                                                                                                                                                                                              0),
       (11868, 725, 'valor_consignado',           'VALOR CONSIGNADO',                       3, 145, '', 15, 'f', 't', 'e', '13 inteiros e 2 decimais; Deverá vir preenchido com o valor consignado na folha de pagamento do servidor que deverá ser exatamente igual ao valor enviado pelo BANCO para consignação e, no caso de não haver saldo para consignação em folha de pagamento do servidor o valor será rejeitado pela entidade e a informação deste campo deverá ser preenchida com zeros;', 0),
       (11869, 725, 'codigo_motivo_rejeicao',     'CÓDIGO DO MOTIVO DA REJEIÇÃO',           1, 160, '', 2,  'f', 't', 'd', 'Ocorrências: BI: Falecimento do Servidor; HM: Servidor não identificado; HN: Tipo de contrato não permite empréstimo; HW: Margem consignável excedida para o servidor; H3: Não descontado - outros motivos; H8: Servidor desligado da entidade; H9: Servidor afastado por licença;',                                                                                      0),
       (11870, 725, 'filler',                     'FILLER',                                 1, 162, '', 39, 'f', 't', 'd', 'São espaços, sem informações no final de cada linha até completar o total de 200 colunas;',                                                                                                                                                                                                                                                                               0);

-- Trailler
INSERT INTO db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos)
VALUES (11871, 726, 'tipo_registro',        'TIPO DE REGISTRO',        1, 1,  '', 6,   'f', 't', 'd', 'O campo recebe valores alfanúmericos e deverá ser preechido com \"BCEC99\" para envio e \"BCER99\" para retorno;', 0),
       (11872, 726, 'quantidade_registros', 'QUANTIDADE DE REGISTROS', 2, 7,  '', 15,  'f', 't', 'e', 'Quantidade de registros mais o HEADER e o TRAILLER;',                                                              0),
       (11873, 726, 'valor_total',          'VALOR TOTAL',             3, 22, '', 15,  'f', 't', 'e', '13 inteiros e 2 decimais;',                                                                                        0),
       (11874, 726, 'filler',               'FILLER',                  1, 37, '', 164, 'f', 't', 'd', 'São espaços, sem informações no final de cada linha até completar o total de 200 colunas;',                        0);

-- Menu para processamento dos dados no Ponto.
insert into db_itensmenu ( id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente ) values ( 10032 ,'Processamento Dados do Ponto' ,'Processamento Dados do Ponto' ,'pes4_processamentodadosponto001.php' ,'1' ,'1' ,'Rotina responsavél pelo lançamento dos dados nas tabelas do ponto.' ,'true' );
delete from db_menu where id_item_filho = 10032 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,10032 ,105 ,952 );


/**
 * Criação da Tabela rhpreponto
 */
insert into db_sysarquivo values (3766, 'rhpreponto', 'Tabela reponsável pelo pre processamento dos dados para as tabelas de ponto', 'rh149', '2015-01-05', 'rhpreponto', 0, 'f', 't', 'f', 't' );
insert into db_sysarqmod  values (28,3766);
insert into db_syscampo   values(20923,'rh149_instit','int4','Campo contento o códiigo da intituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
insert into db_syscampo   values(20924,'rh149_regist','int4','Matrícula do servidor','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
insert into db_syscampo   values(20925,'rh149_rubric','varchar(20)','Código da Rubrica','', 'Rubrica',20,'f','t','f',0,'text','Rubrica');
insert into db_syscampo   values(20926,'rh149_valor','float4','Valor da Rubrica','0', 'Valor',10,'f','f','f',4,'text','Valor');
insert into db_syscampo   values(20927,'rh149_quantidade','int4','Quantidade informada para a Rubrica','0', 'Quantidade',10,'f','f','f',1,'text','Quantidade');
insert into db_syscampo   values(20928,'rh149_tipofolha','int4','Informa qual o tipo de folha (Salário, Férias, etc) que os dados correspontem','0', 'Tipo de Folha',10,'f','f','f',1,'text','Tipo de Folha');
delete from db_sysarqcamp where codarq = 3766;
insert into db_sysarqcamp values(3766,20923,1,0);
insert into db_sysarqcamp values(3766,20924,2,0);
insert into db_sysarqcamp values(3766,20925,3,0);
insert into db_sysarqcamp values(3766,20926,4,0);
insert into db_sysarqcamp values(3766,20927,5,0);
insert into db_sysarqcamp values(3766,20928,6,0);
delete from db_sysforkey  where codarq = 3766 and referen = 0;
insert into db_sysforkey  values(3766,20928,1,3728,0);
delete from db_sysforkey  where codarq = 3766 and referen = 0;
insert into db_sysforkey  values(3766,20923,1,83,0);
insert into db_sysforkey  values(3766,20924,1,1153,0);


------
-- [FOLHA - FIM]
------

------
-- [TRIBUTARIO - INICIO]
------
update db_syscampo
   set conteudo = 'varchar(100)', tamanho = 100
 where codcam = 5110;

update db_syscampo
   set conteudo = 'varchar(100)', tamanho = 100
 where codcam = 5106;

insert into db_itensmenu values( 10029, 'Relatório de receitas por bairro', 'Relatório de receitas por bairro.', 'agu2_relreceitabairro001.php', '1', '1', 'Relatório de receitas por bairro.', '1'	);
insert into db_itensfilho (id_item, codfilho) values(10029,1918);
insert into db_menu values(3331,10029,46,4555);

update db_syscampo set nomecam = 'j46_arealo', conteudo = 'float8', descricao = 'Área do lote em metros quadrados', valorinicial = '0', rotulo = 'Área do lote M2', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Área do lote M2' where codcam = 5908;

update db_syscampo set descricao = 'Código para identificar uma isenção atribuída a uma matrícula',
                       rotulo    = 'Código Isenção',                   rotulorel = 'Código Isenção'                                  where codcam = 187;
update db_syscampo set descricao = 'Código da matrícula de um imóvel', rotulo = 'Matrícula',         rotulorel = 'Matrícula'         where codcam = 188;
update db_syscampo set descricao = 'Código do tipo de isenção',        rotulo = 'Tipo Isenção',      rotulorel = 'Tipo Isenção'      where codcam = 189;
update db_syscampo set descricao = 'Data de início da isenção',        rotulo = 'Data Início',       rotulorel = 'Data Início'       where codcam = 190;
update db_syscampo set descricao = 'Data Final da Isenção',            rotulo = 'Data Final',        rotulorel = 'Data Final'        where codcam = 191;
update db_syscampo set descricao = 'Percentual de isenção',            rotulo = 'Percentual',        rotulorel = 'Percentual'        where codcam = 192;
update db_syscampo set descricao = 'Data da inclusão da isenção',      rotulo = 'Data inclusão',     rotulorel = 'Data inclusão'     where codcam = 193;
update db_syscampo set descricao = 'Código do login do usuário',       rotulo = 'Código do Usuário', rotulorel = 'Código do Usuário' where codcam = 194;
update db_syscampo set descricao = 'Histórico da Isenção',             rotulo = 'Histórico',         rotulorel = 'Histórico'         where codcam = 195;

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10030 ,'Vencimento de Licenças' ,'Relatórios de Vencimento de Licenças' ,'amb2_vencimentodelicencas001.php' ,'1' ,'1' ,'Relatórios que contém Licenças e suas respectivas datas de vencimento.' ,'true' );
delete from db_menu where id_item_filho = 10030 AND modulo = 7808;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10030 ,442 ,7808 );

delete from db_menu where id_item_filho = 9227 AND modulo = 7808;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,9227 ,178 ,7808 );

insert into db_syscampo values(20922,'am05_areatotal','float8','Área Total do Empreendimento',0, 'Área Total',15,'t','f','f',4,'text','Área Total');
insert into db_sysarqcamp values(3741,20922,12,0);



------
-- [TRIBUTARIO - FIM]
------

------
-- [TIME C - INICIO]
------

update db_syscampo set nomecam = 's152_i_pressaosistolica', conteudo = 'int4', descricao = 'Pressão arterial sistólica medida no paciente.', valorinicial = 'null', rotulo = 'Sistólica', nulo = 't', tamanho = 3, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sistólica' where codcam = 17216;
update db_syscampo set nomecam = 's152_i_pressaodiastolica', conteudo = 'int4', descricao = 'Pressão arterial diastólica medida no paciente.', valorinicial = 'null', rotulo = 'Diastólica', nulo = 't', tamanho = 3, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Diastólica' where codcam = 17217;
update db_syscampo set nomecam = 's152_i_cintura', conteudo = 'int4', descricao = 'Cintura do paciente em centímetros.', valorinicial = 'null', rotulo = 'Cintura', nulo = 't', tamanho = 3, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Cintura' where codcam = 17218;
update db_syscampo set nomecam = 's152_n_peso', conteudo = 'float4', descricao = 'Peso do paciente.', valorinicial = 'null', rotulo = 'Peso', nulo = 't', tamanho = 7, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Peso' where codcam = 17219;
update db_syscampo set nomecam = 's152_i_altura', conteudo = 'int4', descricao = 'Altura do paciente medida em centímetros.', valorinicial = 'null', rotulo = 'Altura', nulo = 't', tamanho = 3, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Altura' where codcam = 17220;

-- TAREFA 104640

insert into db_sysarquivo values (3761, 'motivoalta', 'Motivos de alta do sus ', 'sd01', '2014-12-19', 'Motivos de Alta', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3761);
insert into db_syscampo values(20903,'sd01_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(20904,'sd01_codigosus','int4','Código SUS','0', 'Código SUS',10,'f','f','f',1,'text','Código SUS');
insert into db_syscampo values(20905,'sd01_descricao','varchar(80)','Descrição do motivo da alta','', 'Descrição',80,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(20906,'sd01_finalizaatendimento','bool','Define se a alta deve finalizar o atendimento.','false', 'Finaliza Atendimento',1,'f','f','f',5,'text','Finaliza Atendimento');
insert into db_sysarqcamp values(3761,20903,1,0);
insert into db_sysarqcamp values(3761,20904,2,0);
insert into db_sysarqcamp values(3761,20905,3,0);
insert into db_sysarqcamp values(3761,20906,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3761,20903,1,20905);
insert into db_syssequencia values(1000424, 'motivoalta_sd01_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000424 where codarq = 3761 and codcam = 20903;
insert into db_sysarquivo values (3762, 'prontuariosmotivoalta', 'Motivos de alta nos prontuarios', 'sd25', '2014-12-19', 'Alta de prontuarios', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3762);
insert into db_syscampo values(20907,'sd25_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(20908,'sd25_motivoalta','int4','Motivo da alta do prontuário ','0', 'Motivo de alta',10,'f','f','f',1,'text','Motivo de alta');
insert into db_syscampo values(20909,'sd25_prontuarios','int4','Prontuário de atendimento','0', 'Prontuarios',10,'f','f','f',1,'text','Prontuarios');
insert into db_syscampo values(20910,'sd25_data','date','Data que foi efetuada a alta do paciente','null', 'Data',10,'f','f','f',1,'text','Data');
insert into db_syscampo values(20912,'sd25_hora','varchar(5)','Hora que foi efetuada a alta do paciente','', 'Hora',5,'f','t','f',0,'text','Hora');
insert into db_syscampo values(20913,'sd25_db_usuarios','int4','Usuário que realizou a alta','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
insert into db_sysarqcamp values(3762,20907,1,0);
insert into db_sysarqcamp values(3762,20908,2,0);
insert into db_sysarqcamp values(3762,20909,3,0);
insert into db_sysarqcamp values(3762,20910,4,0);
insert into db_sysarqcamp values(3762,20912,5,0);
insert into db_sysarqcamp values(3762,20913,6,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3762,20907,1,20907);
insert into db_sysforkey values(3762,20908,1,3761,0);
insert into db_sysforkey values(3762,20909,1,1010134,0);
insert into db_sysforkey values(3762,20913,1,109,0);
insert into db_syssequencia values(1000425, 'prontuariosmotivoalta_sd25_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000425 where codarq = 3762 and codcam = 20907;
insert into db_sysindices values(4143,'prontuariosmotivoalta_motivoalta_in',3762,'0');
insert into db_syscadind values(4143,20908,1);
insert into db_sysindices values(4144,'prontuariosmotivoalta_prontuarios_in',3762,'0');
insert into db_syscadind values(4144,20909,1);
insert into db_sysindices values(4145,'prontuariosmotivoalta_db_usuarios_in',3762,'0');
insert into db_syscadind values(4145,20913,1);

update db_syscampo set nomecam = 'sd24_d_cadastro', conteudo = 'date', descricao = 'Data Atendimento', valorinicial = 'now()', rotulo = 'Data Atendimento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data Atendimento' where codcam = 1008910;
update db_syscampo set nomecam = 'sd24_c_cadastro', conteudo = 'varchar(20)', descricao = 'Hora Atendimento', valorinicial = '\'||current_time||\'', rotulo = 'Hora Atendimento', nulo = 'f', tamanho = 20, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Hora Atendimento' where codcam = 1008911;


insert into db_sysarquivo values (3763, 'classificacaorisco', 'Classificação de risco da saúde', 'sd78', '2014-12-23', 'Classificação de Risco', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod  values (1000004,3763);
insert into db_syscampo   values(20914,'sd78_codigo','int4','Chave primária','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo   values(20915,'sd78_descricao','varchar(40)','Descrição da prioridade','', 'Decrição',40,'f','t','f',0,'text','Decrição');
insert into db_syscampo   values(20916,'sd78_labelcor','varchar(10)','Descrição da cor a ser utilizada','', 'Descrição da cor',10,'f','t','f',0,'text','Descrição da cor');
insert into db_syscampo   values(20917,'sd78_peso','int4','Classifica a prioridade','0', 'Peso',10,'f','f','f',1,'text','Peso');
insert into db_syscampo   values(20918,'sd78_cor','varchar(7)','Cor em hexadecimal','', 'Cor',7,'f','t','f',0,'text','Cor');
insert into db_sysarqcamp values(3763,20914,1,0);
insert into db_sysarqcamp values(3763,20915,2,0);
insert into db_sysarqcamp values(3763,20917,3,0);
insert into db_sysarqcamp values(3763,20916,4,0);
insert into db_sysarqcamp values(3763,20918,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3763,20914,1,20915);
insert into db_syssequencia values(1000426, 'classificacaorisco_sd78_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000426 where codarq = 3763 and codcam = 20914;
insert into db_sysarquivo values (3764, 'prontuariosclassificacaorisco', 'Prontuários com classificação de risco', 'sd101', '2014-12-23', 'Prontuarios com classificação de risco', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod  values (1000004,3764);
insert into db_syscampo   values(20919,'sd101_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo   values(20920,'sd101_prontuarios','int4','Prontuário','0', 'Prontuário',10,'f','f','f',1,'text','Prontuário');
insert into db_syscampo   values(20921,'sd101_classificacaorisco','int4','Classificação de risco','0', 'Classificação de Risco',10,'f','f','f',1,'text','Classificação de Risco');
insert into db_sysarqcamp values(3764,20919,1,0);
insert into db_sysarqcamp values(3764,20920,2,0);
insert into db_sysarqcamp values(3764,20921,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3764,20919,1,20919);
insert into db_sysforkey  values(3764,20920,1,1010134,0);
insert into db_sysforkey  values(3764,20921,1,3763,0);
insert into db_sysindices values(4147,'prontuariosclassificacaorisco_prontuarios_classificacaorisco_in',3764,'1');
insert into db_syscadind  values(4147,20920,1);
insert into db_syscadind  values(4147,20921,2);
insert into db_syssequencia values(1000427, 'prontuariosclassificacaorisco_sd101_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000427 where codarq = 3764 and codcam = 20919;

------
-- [TIME C - FIM]
------
