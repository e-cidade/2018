----- TRIBUTARIO ------
insert into db_syscampo values(20929,' j38_datalancamento','date','Date de Lançamento','null', 'Date de Lançamento',10,'t','f','f',1,'text','Date de Lançamento');
insert into db_sysarqcamp values(18,20929,3,0);

insert into db_sysarquivo values (3770, 'agrupamentocaracterisca', 'Características', 'j139', '2015-01-12', 'Características', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (69,3770);
insert into db_sysarquivo values (3771, 'agrupamentocaracteristicavalor', 'Agrupamento de Características', 'j140', '2015-01-12', 'Agrupamento Características', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (69,3771);
insert into db_syscampo values(20933,'j139_sequencial','int4','Característica','0', 'Característica',10,'f','f','f',1,'text','Característica');
insert into db_syscampo values(20934,'j139_anousu','int4','Exercício da Característica','0', 'Exercício',4,'f','f','f',1,'text','Exercício');
insert into db_syscampo values(20935,'j139_agrupamentocaracteristicavalor','int4','Agrupamento','0', 'Agrupamento',10,'t','f','f',1,'text','Agrupamento');
insert into db_syscampo values(20936,'j139_caracter','int4','Característica','0', 'Característica',10,'f','f','f',1,'text','Característica');
insert into db_syscampo values(20937,'j140_sequencial','int4','Agrupamento Valor','0', 'Agrupamento Valor',10,'f','f','f',1,'text','Agrupamento Valor');
insert into db_syscampo values(20938,'j140_valor','float8','Valor das Caracteristicas','0', 'Valor',15,'f','f','f',4,'text','Valor');
update db_sysarquivo set nomearq = 'agrupamentocaracteristicavalor', descricao = 'Agrupamento de Características', sigla = 'j140', dataincl = '2015-01-12', rotulo = 'Agrupamento Características', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 3771;
update db_sysarquivo set nomearq = 'agrupamentocaracterisca', descricao = 'Características', sigla = 'j139', dataincl = '2015-01-12', rotulo = 'Características', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 3770;
insert into db_sysarqcamp values(3771,20937,1,0);
insert into db_sysarqcamp values(3771,20938,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3771,20937,1,20938);
update db_sysarquivo set nomearq = 'agrupamentocaracteristica', descricao = 'Características', sigla = 'j139', dataincl = '2015-01-12', rotulo = 'Características', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 3770;
insert into db_syssequencia values(1000428, 'agrupamentocaracteristicavalor_j140_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000428 where codarq = 3771 and codcam = 20937;
insert into db_sysarqcamp values(3770,20933,1,0);
insert into db_sysarqcamp values(3770,20934,2,0);
insert into db_sysarqcamp values(3770,20935,3,0);
insert into db_sysarqcamp values(3770,20936,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3770,20933,1,20936);
insert into db_sysforkey values(3770,20935,1,3771,0);
insert into db_sysforkey values(3770,20936,1,13,0);
insert into db_syssequencia values(1000429, 'agrupamentocaracteristica_j139_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000429 where codarq = 3770 and codcam = 20933;
update db_itensmenu set descricao = 'Gerar Arquivo', help = 'Gerar arquivo', funcao = 'edu4_exportarsituacaoalunonovo001.php', itemativo = '1', desctec = 'gerar arquivo de exportação da situação do aluno', libcliente = '1' where id_item = 8043;

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10044 ,'Meio Ambiente' ,'Parametros do modulo Meio Ambiente' ,'' ,'1' ,'1' ,'Mensagens para o módulo Meio Ambiente' ,'false' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9945 ,10044 ,2 ,1 );
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10045 ,'Licenças a Vencer' ,'Mensagem para Licenças que irão vencer' ,'amb4_licencasvencer001.php' ,'1' ,'1' ,'Configuração de mensagem para Licenças que irão vencer' ,'false' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10044 ,10045 ,1 ,1 );

insert into db_sysarquivo values (3778, 'mensagerialicenca', 'Mensagem', 'am14', '2015-02-04', 'Mensagem', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (64,3778);
insert into db_syscampo values(20974,'am14_sequencial','int4','Código da Mensagem','0', 'Código da Mensagem',10,'f','f','f',1,'text','Código da Mensagem');
insert into db_syscampo values(20975,'am14_assunto','varchar(100)','Assunto','', 'Assunto',100,'f','f','f',0,'text','Assunto');
insert into db_syscampo values(20976,'am14_mensagem','text','Mensagem','', 'Mensagem',500,'f','f','f',0,'text','Mensagem');
insert into db_sysarqcamp values(3778,20974,1,0);
insert into db_sysarqcamp values(3778,20975,2,0);
insert into db_sysarqcamp values(3778,20976,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3778,20974,1,20976);
insert into db_sysindices values(4163,'mensagerialicenca_sequencial_in',3778,'1');
insert into db_syscadind values(4163,20974,1);
insert into db_syssequencia values(1000436, 'mensagerialicenca_am14_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000436 where codarq = 3778 and codcam = 20974;
insert into db_sysarquivo values (3779, 'mensagerialicencaprocessado', 'Licenças Notificadas', 'am15', '2015-02-04', 'Licenças Notificadas', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (64,3779);
insert into db_syscampo values(20977,'am15_sequencial','int4','Licenças Notificadas','0', 'Licenças Notificadas',10,'f','f','f',1,'text','Licenças Notificadas');
insert into db_syscampo values(20978,'am15_mensagerialicencadb_usuarios','int4','Usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(20979,'am15_licencaempreendimento','int4','Licença','0', 'Licença',10,'f','f','f',1,'text','Licença');
insert into db_sysarqcamp values(3779,20977,1,0);
insert into db_sysarqcamp values(3779,20978,2,0);
insert into db_sysarqcamp values(3779,20979,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3779,20977,1,20979);
insert into db_sysindices values(4164,'mensagerialicencaprocessado_sequencial_in',3779,'1');
insert into db_syscadind values(4164,20977,1);
insert into db_sysforkey values(3779,20979,1,3754,0);
insert into db_sysarquivo values (3780, 'mensagerialicenca_db_usuarios', 'Usuário', 'am16', '2015-02-04', 'Usuário', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (64,3780);
insert into db_syscampo values(20980,'am16_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20981,'am16_usuario','int4','Usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(20982,'am16_dias','int4','Dias','0', 'Dias',10,'f','f','f',1,'text','Dias');
insert into db_sysarqcamp values(3780,20980,1,0);
insert into db_sysarqcamp values(3780,20981,2,0);
insert into db_sysarqcamp values(3780,20982,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3780,20980,1,20981);
insert into db_sysforkey values(3780,20981,1,109,0);
insert into db_syssequencia values(1000437, 'mensagerialicenca_db_usuarios_am16_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000437 where codarq = 3780 and codcam = 20980;
insert into db_sysforkey values(3779,20978,1,3780,0);
insert into db_syssequencia values(1000438, 'mensagerialicencaprocessado_am15_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000438 where codarq = 3779 and codcam = 20977;


----- FIM TRIBUTARIO ------


---------------------------
------- TIME C ------------
---------------------------
insert into db_sysarquivo values (3772, 'setorambulatorial', 'cadastro dos setores do ambulatorio', 'sd91', '2015-01-12', 'Setor ambulatorial', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3772);
insert into db_sysarquivo values (3773, 'movimentacaoprontuario', 'Movimentação da ficha de atendimento ambulatorial', 'sd102', '2015-01-12', 'Movimentação da FAA', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3773);
insert into db_syscampo values(20939,'sd91_codigo','int4','Código do setor','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(20940,'sd91_unidades','int4','Unidade a qual pertence o setor','0', 'Unidade',10,'f','f','f',1,'text','Unidade');
insert into db_syscampo values(20941,'sd91_descricao','varchar(60)','Nome do setor','', 'Setor',60,'f','t','f',0,'text','Setor');
insert into db_syscampo values(20942,'sd91_local','int4','Local do setor','0', 'Local',10,'f','f','f',1,'text','Local');
insert into db_syscampodef values(20942,'1','RECEPÇÃO');
insert into db_syscampodef values(20942,'2','TRIAGEM');
insert into db_syscampodef values(20942,'3','CONSULTA MÉDICA');
insert into db_syscampodef values(20942,'4','EXTERNO');
insert into db_syscampo values(20943,'sd24_setorambulatorial','int4','Setor ambulatorial','0', 'Setor ambulatorial',10,'f','f','f',1,'text','Setor ambulatorial');
insert into db_syscampo values(20944,'sd102_codigo','int4','Código da movimentação','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(20945,'sd102_prontuarios','int4','Ficha de atendimento ambulatorial','0', 'Ficha de atendimento',10,'f','f','f',1,'text','Ficha de atendimento');
insert into db_syscampo values(20946,'sd102_db_usuarios','int4','Usuário que estava logado ao gerar a movimentação','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(20947,'sd102_setorambulatorial','int4','Setor ambulatorial','0', 'Setor ambulatorial',10,'f','f','f',1,'text','Setor ambulatorial');
insert into db_syscampo values(20948,'sd102_data','date','Data da movimentação','null', 'Data',10,'f','f','f',1,'text','Data');
insert into db_syscampo values(20949,'sd102_hora','varchar(5)','Hora da movimentação','', 'Hora',5,'f','t','f',0,'text','Hora');
insert into db_syscampo values(20950,'sd102_observacao','text','Observação','', 'Observação',1,'t','t','f',0,'text','Observação');
insert into db_syscampo values(20951,'sd102_situacao','int4','Situação','', 'Situação',10,'f','t','f',0,'text','Situação');
insert into db_syscampodef values(20951,'1','ENTRADA');
insert into db_syscampodef values(20951,'2','ENCAMINHADA');
insert into db_syscampodef values(20951,'3','FINALIZADA');
insert into db_sysarqcamp values(3773,20944,1,0);
insert into db_sysarqcamp values(3773,20945,2,0);
insert into db_sysarqcamp values(3773,20946,3,0);
insert into db_sysarqcamp values(3773,20947,4,0);
insert into db_sysarqcamp values(3773,20948,5,0);
insert into db_sysarqcamp values(3773,20949,6,0);
insert into db_sysarqcamp values(3773,20951,7,0);
insert into db_sysarqcamp values(3773,20950,8,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3773,20944,1,20944);
insert into db_sysarqcamp values(3772,20939,1,0);
insert into db_sysarqcamp values(3772,20940,2,0);
insert into db_sysarqcamp values(3772,20941,3,0);
insert into db_sysarqcamp values(3772,20942,4,0);
insert into db_sysforkey values(3772,20940,1,100011,0);
insert into db_sysindices values(4148,'setorambulatorial_unidades_in',3772,'0');
insert into db_syscadind values(4148,20940,1);
insert into db_sysarqcamp values(1010134,20943,21,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3772,20939,1,20941);
insert into db_sysforkey values(1010134,20943,1,3772,0);
insert into db_sysindices values(4149,'prontuarios_setorambulatorial_in',1010134,'0');
insert into db_syscadind values(4149,20943,1);
insert into db_sysforkey values(3773,20945,1,1010134,0);
insert into db_sysforkey values(3773,20946,1,109,0);
insert into db_sysforkey values(3773,20947,1,3772,0);
insert into db_sysindices values(4150,'movimentacaoprontuario_prontuarios_in',3773,'0');
insert into db_syscadind values(4150,20945,1);
insert into db_sysindices values(4151,'movimentacaoprontuario_db_usuarios_in',3773,'0');
insert into db_syscadind values(4151,20946,1);
insert into db_sysindices values(4152,'movimentacaoprontuario_setorambulatorial_in',3773,'0');
insert into db_syscadind values(4152,20947,1);
insert into db_syssequencia values(1000430, 'movimentacaoprontuario_sd102_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000430 where codarq = 3773 and codcam = 20944;
insert into db_syssequencia values(1000431, 'setorambulatorial_sd91_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000431 where codarq = 3772 and codcam = 20939;

insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente )
  values (10035, 'Setor Ambulatorial', 'Cadastro de Setor Ambulatorial', '', '1', 1, 'Cadastro de Setor Ambulatorial', '1');
insert into db_processa (codarq, id_item) values (3772, 10035);
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
  values (10036, 'Inclusão', 'Inclusão de Setor Ambulatorial', 'amb1_setorambulatorial001.php', '1', 1, 'Inclusão de Setor Ambulatorial', '1');
insert into db_arquivos values(5836, 'amb1_setorambulatorial001.php', 'Inclusão: cadastro dos setores do ambulatorio');
insert into db_itensfilho values(10036, 5836);
insert into db_processa (codarq, id_item) values (3772, 10036);
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
 values (10037, 'Alteração', 'Alteração de Setor Ambulatorial', 'amb1_setorambulatorial002.php', '1', 1, 'Alteração de Setor Ambulatorial', '1');
insert into db_arquivos values(5837, 'amb1_setorambulatorial002.php', 'Inclusão: cadastro dos setores do ambulatorio');
insert into db_itensfilho values(10037, 5837);
insert into db_processa (codarq, id_item) values (3772, 10037);
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
  values (10038, 'Exclusão', 'Exclusão de Setor Ambulatorial', 'amb1_setorambulatorial003.php', '1', 1, 'Exclusão de Setor Ambulatorial', '1');
insert into db_arquivos values(5838, 'amb1_setorambulatorial003.php', 'Inclusão: cadastro dos setores do ambulatorio');
insert into db_itensfilho values(10038, 5838);
insert into db_processa (codarq, id_item) values (3772, 10038);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values(3470, 10035, 1, 1000004);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values(10035, 10036, 1, 1000004);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values(10035, 10037, 1, 1000004);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values(10035, 10038, 1, 1000004);
insert into db_arquivos values(5839, 'db_func_setorambulatorial.php', 'Arquivo com os campos para a função da tabela : Setor Ambulatorial');
insert into db_itensfilho values(10036, 5839);
insert into db_itensfilho values(10037, 5839);
insert into db_itensfilho values(10038, 5839);
insert into db_arquivos values(5840, 'func_setorambulatorial.php', 'Função de consulta aos dados da tabela : Setor Ambulatorial');
insert into db_itensfilho values(10036, 5840);
insert into db_itensfilho values(10037, 5840);
insert into db_itensfilho values(10038, 5840);
insert into db_arquivos values(5841, 'db_frmsetorambulatorial.php', 'Formulario utilizado para a tabela : Setor Ambulatorial');
insert into db_itensfilho values(10036, 5841);
insert into db_itensfilho values(10037, 5841);
insert into db_itensfilho values(10038, 5841);

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10039 ,'Ficha de Atendimento Ambulatorial' ,'Ficha de Atendimento Ambulatorial' ,'sau3_consultafaa.php' ,'1' ,'1' ,'Consulta para ficha de atendimento do paciente' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,10039 ,179 ,1000004 );

-- Requisição de exames ---
insert into db_sysarquivo values (3775, 'requisicaoexameprontuario', 'Requisição de exame do prontuario', 'sd103', '2015-01-16', 'Requisição de exame do prontuario', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3775);
insert into db_syscampo values(20960,'sd103_codigo','int4','Requisição de exame do prontuario','0', 'Requisição',10,'f','f','f',1,'text','Requisição de exame do prontuario');
insert into db_syscampo values(20961,'sd103_prontuarios','int4','Prontuários ','0', 'Prontuarios',10,'f','f','f',1,'text','Prontuarios');
insert into db_syscampo values(20962,'sd103_medicos','int4','Médicos','0', 'Médicos',10,'f','f','f',1,'text','Médicos');
insert into db_syscampo values(20963,'sd103_observacao','text','Observação','', 'Observação',1,'t','t','f',0,'text','Observação');
insert into db_syscampo values(20964,'sd103_data','date','Data da requisição','null', 'Data',10,'f','f','f',1,'text','Data da requisição');
insert into db_syscampo values(20965,'sd103_hora','varchar(5)','Hora da requisição','', 'Hora',5,'f','t','f',0,'text','Hora da requisição');
insert into db_sysarqcamp values(3775,20960,1,0);
insert into db_sysarqcamp values(3775,20961,2,0);
insert into db_sysarqcamp values(3775,20962,3,0);
insert into db_sysarqcamp values(3775,20964,4,0);
insert into db_sysarqcamp values(3775,20965,5,0);
insert into db_sysarqcamp values(3775,20963,6,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3775,20960,1,20960);
insert into db_sysforkey values(3775,20961,1,1010134,0);
insert into db_sysforkey values(3775,20962,1,100012,0);
insert into db_sysindices values(4158,'requisicaoexameprontuario_prontuarios_in',3775,'0');
insert into db_syscadind values(4158,20961,1);
insert into db_sysindices values(4159,'requisicaoexameprontuario_medicos_in',3775,'0');
insert into db_syscadind values(4159,20962,1);
insert into db_syssequencia values(1000433, 'requisicaoexameprontuario_sd103_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000433 where codarq = 3775 and codcam = 20960;
insert into db_sysarquivo values (3776, 'examerequisicaoexame', 'Exame da requisição de exames', 'sd104', '2015-01-16', 'Exame da requisição de exames', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1000004,3776);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20966 ,'sd104_codigo' ,'int4' ,'Codigo' ,'' ,'Codigo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Codigo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3776 ,20966 ,1 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20967 ,'sd104_requisicaoexameprontuario' ,'int4' ,'Requisição' ,'' ,'Requisição' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Requisição' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3776 ,20967 ,2 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20968 ,'sd104_lab_exame' ,'int4' ,'Exame' ,'' ,'Exame' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Exame' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3776 ,20968 ,3 ,0 );
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3776,20966,1,20966);
insert into db_sysforkey values(3776,20967,1,3775,0);
insert into db_sysforkey values(3776,20968,1,2758,0);
insert into db_sysindices values(4160,'examerequisicaoexame_requisicaoexameprontuario_in',3776,'0');
insert into db_syscadind values(4160,20967,1);
insert into db_sysindices values(4161,'examerequisicaoexame_lab_exame_in',3776,'0');
insert into db_syscadind values(4161,20968,1);
insert into db_syssequencia values(1000434, 'examerequisicaoexame_sd104_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000434 where codarq = 3776 and codcam = 20966;

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10041 ,'Requisição de Exame' ,'Requisição de Exame' ,'sau2_requisicaoexame001.php' ,'1' ,'1' ,'Emissão da requisição de exames' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10041 ,443 ,1000004 );

insert into db_syscampo values(20969,'sd29_sigilosa','bool','Ao marcado como true, informa que a informação lançada em sd29_t_tratamento é sigilosa.','f', 'Sigiloso',1,'f','f','f',5,'text','Sigiloso');
insert into db_sysarqcamp values(1006042,20969,12,0);

insert into db_syscampo (codcam, nomecam, conteudo, descricao, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
     values (20973, 's152_evolucao', 'text', 'Evolução do paciente', 'Evolução', 1, 't', 't', 'f', 0, 'text', 'Evolução');
insert into db_sysarqcamp values(3043, 20973, 17, 0);
delete from db_sysindices where codind = 2847;
delete from db_syscadind where codind = 2847  and codcam = 17213;

insert into db_sysarquivo values (3781, 'regracalculocargahoraria', 'Regra Calculo Carga Horaria', 'ed127', '2015-02-04', 'Regra Calculo Carga Horaria', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1008004,3781);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20983 ,'ed127_codigo' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20984 ,'ed127_ano' ,'int4' ,'Ano de vigência ' ,'' ,'Ano' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ano' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20985 ,'ed127_calculaduracaoperiodo' ,'bool' ,'Como deve proceder o calculo da carga horária.nse true aplica o seguinte calculo:n(Somatório das Aulas Dadas * duração dos periodos ) / 60 "1 hora em min"nnse false o calculo é o Somatório das Aulas Dadas' ,'false' ,'Calculo da Carga Horária ' ,1 ,'false' ,'false' ,'false' ,5 ,'text' ,'Calculo da Carga Horária ' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20986 ,'ed127_escola' ,'int4' ,'Escola' ,'' ,'Escola' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Escola' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3781 ,20983 ,1 ,0 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3781 ,20984 ,2 ,0 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3781 ,20985 ,3 ,0 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3781 ,20986 ,4 ,0 );
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3781,20983,1,20983);
insert into db_sysforkey values(3781,20986,1,1010031,0);
insert into db_sysindices values(4165,'regracalculocargahoraria_escola_ano_in',3781,'1');
insert into db_syscadind values(4165,20986,1);
insert into db_syscadind values(4165,20984,2);
insert into db_syssequencia values(1000439, 'regracalculocargahoraria_ed127_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000439 where codarq = 3781 and codcam = 20983;

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10046 ,'Cálculo da Carga Horária' ,'Cálculo da Carga Horária' ,'edu4_calculocargahoraria001.php' ,'1' ,'1' ,'Configuração do parâmetro do cálculo da carga horária.' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9307 ,10046 ,4 ,1100747 );

update db_syscampo set descricao = 'Data da Consulta /Exame na Prestadora.', rotulo = 'Data Cons./Exame', rotulorel = 'Data Cons./Exame' where codcam = 16398;
---------------------------
------- FIM TIME C --------
---------------------------


----- TRIBUTARIO DAEB ------

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 10034 ,'Alteração de Vencimentos para os débitos' ,'Alteração de Vencimentos para os débitos' ,
'arr4_alteravencimento001.php' ,'1' ,'1' ,'Responsável por alterar o vencimento dos débitos selecionados.' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10034 ,452 ,1985522 );

----- FIM TRIBUTARIO DAEB------

---------------------------
--------  TIME NFSE -------
---------------------------

insert into db_sysarquivo values (3774, 'confvencissqnvariavel', 'Configurações de ISSQN Variável', 'q144', '2015-01-14', 'Configurações de ISSQN Variável', 1, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (3,3774);
insert into db_syscampo values(20952,'q144_sequencial','int4','Campo Sequencial','0', 'Campo Sequencial',10,'f','f','f',1,'text','Campo Sequencial');

update db_syscampo set nomecam = 'q144_sequencial', conteudo = 'int4', descricao = 'Código Sequencial', valorinicial = '0', rotulo = 'Código Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código Sequencial' where codcam = 20952;

insert into db_syscampo values(20953,'q144_ano','int4','Ano Competência','0', 'Ano Competência',10,'f','f','f',1,'text','Ano Competência');
insert into db_syscampo values(20954,'q144_codvenc','int4','Código do Vencimento','0', 'Código do Vencimento',10,'f','f','f',1,'text','Código do Vencimento');
insert into db_syscampo values(20955,'q144_receita','int4','Código da Receita','0', 'Código da Receita',10,'f','f','f',1,'text','Código da Receita');
insert into db_syscampo values(20956,'q144_diavenc','int4','Dia do Vencimento','1', 'Dia do Vencimento',2,'f','f','f',1,'text','Dia do Vencimento');

update db_syscampo set nomecam = 'q144_codvenc', conteudo = 'int4', descricao = 'Código do Vencimento', valorinicial = '0', rotulo = 'Código do Vencimento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código do Vencimento' where codcam = 20954;

insert into db_syscampodep values(20954,'259');

update db_syscampo set nomecam = 'q144_receita', conteudo = 'int4', descricao = 'Código da Receita', valorinicial = '0', rotulo = 'Código da Receita', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código da Receita' where codcam = 20955;

insert into db_syscampodep values(20955,'382');
insert into db_sysarqcamp values(3774,20952,1,0);
insert into db_sysarqcamp values(3774,20953,2,0);
insert into db_sysarqcamp values(3774,20954,3,0);
insert into db_sysarqcamp values(3774,20955,4,0);
insert into db_sysarqcamp values(3774,20956,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3774,20952,1,20952);
insert into db_sysforkey values(3774,20954,1,54,0);
insert into db_sysforkey values(3774,20955,1,75,0);
insert into db_sysindices values(4153,'confvencissqnvariavel_cadvencdesc_in',3774,'0');
insert into db_syscadind values(4153,20954,1);
insert into db_sysindices values(4154,'confvencissqnvariavel_tabrec_in',3774,'0');
insert into db_syscadind values(4154,20955,1);
insert into db_syssequencia values(1000432, 'confvencissqnvariavel_q144_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

update db_sysarqcamp set codsequencia = 1000432 where codarq = 3774 and codcam = 20952;

insert into db_syscampo values(20957,'q144_hist','int4','Codigo do historico de calculo para identificar o débito','1019', 'Campo Histórico',4,'f','f','f',0,'text','Campo Histórico');
insert into db_syscampodep values(20957,'369');
insert into db_syscampo values(20958,'q144_tipo','int4','Tipo de débito, para possibilitar e facilitar saber o tipo buscando na tabela arretipo onde estao as descricões','33', 'Tipo de Débito',4,'f','f','f',0,'text','Tipo de Débito');
insert into db_syscampodep values(20958,'380');
insert into db_syscampo values(20959,'q144_valor','float4','Campo Valor','0', 'Campo Valor',4,'t','f','f',4,'text','Campo Valor');

update db_syscampo set nomecam = 'q144_valor', conteudo = 'float4', descricao = 'Valor mínimo do recibo a ser gerado.', valorinicial = '0', rotulo = 'Campo Valor Mínimo', nulo = 't', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Campo Valor Mínimo' where codcam = 20959;

insert into db_sysarqcamp values(3774,20957,6,0);
insert into db_sysarqcamp values(3774,20958,7,0);
insert into db_sysarqcamp values(3774,20959,8,0);
insert into db_sysforkey values(3774,20958,1,82,0);
insert into db_sysforkey values(3774,20957,1,73,0);
insert into db_sysindices values(4155,'confvencissqnvariavel_arretipo_in',3774,'0');
insert into db_syscadind values(4155,20958,1);
insert into db_sysindices values(4156,'confvencissqnvariavel_histcalc_in',3774,'0');
insert into db_syscadind values(4156,20957,1);
insert into db_sysindices values(4157,'confvencissqnvariavel_cadvenccompetencia_in',3774,'1');
insert into db_syscadind values(4157,20953,1);
insert into db_syscadind values(4157,20954,2);

update db_syscampo set nomecam = 'q144_codvenc', conteudo = 'int4', descricao = 'Código do Vencimento', valorinicial = '0', rotulo = 'Vencimento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Vencimento' where codcam = 20954;
update db_syscampo set nomecam = 'q144_hist', conteudo = 'int4', descricao = 'Codigo do historico de calculo para identificar o débito', valorinicial = '1019', rotulo = 'Histórico', nulo = 'f', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Histórico' where codcam = 20957;
update db_syscampo set nomecam = 'q144_receita', conteudo = 'int4', descricao = 'Código da Receita', valorinicial = '0', rotulo = 'Receita', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Receita' where codcam = 20955;
update db_syscampo set nomecam = 'q144_valor', conteudo = 'float4', descricao = 'Valor mínimo do recibo a ser gerado.', valorinicial = '0', rotulo = 'Valor Mínimo', nulo = 't', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor Mínimo' where codcam = 20959;

-- Alterado menus
update db_itensmenu set id_item = 3889, descricao = 'Inclusão', help = 'Inclusão de Db_confplan', funcao = 'pre1_db_confplan001.php', itemativo = '1', manutencao = '1', desctec = 'Inclusão de Db_confplan', libcliente = 'false' where id_item = 3889;
update db_itensmenu set id_item = 3890, descricao = 'Alteração', help = 'Alteração de Db_confplan', funcao = 'pre1_db_confplan002.php', itemativo = '1', manutencao = '1', desctec = 'Alteração de Db_confplan', libcliente = 'false' where id_item = 3890;
update db_itensmenu set id_item = 3891, descricao = 'Exclusão', help = 'Exclusão de Db_confplan', funcao = 'pre1_db_confplan003.php', itemativo = '1', manutencao = '1', desctec = 'Exclusão de Db_confplan', libcliente = 'false' where id_item = 3891;
update db_itensmenu set id_item = 3888, descricao = 'Configuração da Planilha', help = 'Configuração da Planilha', funcao = 'pre1_db_confplan.php', itemativo = '1', manutencao = '1', desctec = 'Altera os dados das tabelas db_confplan e confvencissqnvariavel.', libcliente = 'true' where id_item = 3888;
update db_itensmenu set id_item = 1925, descricao = 'Manutenção de Planilhas', help = 'Manutenção de Planilhas', funcao = 'pre1_db_confplanalt002.php', itemativo = '1', manutencao = '1', desctec = 'Alteração da tabela db_confplan que possui somente um registro', libcliente = 'true' where id_item = 1925;

---------------------------
------ FIM TIME NFSE ------
---------------------------


-------------------------------
------ INÍCIO TIME FOLHA ------
-------------------------------
-- Alterando menus
UPDATE db_itensmenu
   SET descricao = 'Processamento de Dados do Ponto',
       help      = 'Processamento de Dados do Ponto'
 WHERE id_item   = 10032;

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 10042 ,'Suplementar' ,'Suplementar' ,'pes1_gerffx001.php?gerf=supl' ,'1' ,'1' ,'Implantação do Ponto Suplementar.' ,true );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5205 ,10042 ,5 ,952 );

-- Tabela "tipodeficiencia"
SELECT fc_executa_ddl('
  insert into db_sysarquivo   values (3777 , ''tipodeficiencia'', ''Tabela responsável por armazenar os tipos de deficiência do servidor.'', ''rh150'', ''2015-01-27'', ''Tipo de Deficiência'', 0, ''f'', ''f'', ''f'', ''f'' );
');
SELECT fc_executa_ddl('
  insert into db_sysarqmod    values (28 ,3777 );
');
SELECT fc_executa_ddl('
  insert into db_syssequencia values (1000435, ''tipodeficiencia_rh150_sequencial_seq'', 1, 1, 9223372036854775807, 1, 1 );
');
SELECT fc_executa_ddl('
  insert into db_syscampo     values (20970 ,''rh150_sequencial'' ,''int4''        ,''Representa a primary key da tabela.''           ,'''' ,''Sequencial'' ,10 ,''false'' ,''false'' ,''false'' ,1 ,''text'' ,''Sequencial'' );
');
SELECT fc_executa_ddl('
  insert into db_syscampo     values (20971 ,''rh150_descricao''  ,''varchar(50)'' ,''Representa o tipo de deficiência do servidor.'' ,'''' ,''Descrição''  ,50 ,''false'' ,''true''  ,''false'' ,0 ,''text'' ,''Descrição'' );
');
SELECT fc_executa_ddl('
  insert into db_sysarqcamp   values (3777 ,20970 ,1 , 1000435);
');
SELECT fc_executa_ddl('
  insert into db_sysarqcamp   values (3777 ,20971 ,2 ,0 );
');
SELECT fc_executa_ddl('
  insert into db_sysprikey    values (3777 ,20970 ,1 ,20970 );
');
SELECT fc_executa_ddl('
  insert into db_sysindices   values (4162 ,''tipodeficiencia_sequencial_in'', 3777, ''0'' );
');
SELECT fc_executa_ddl('
  insert into db_syscadind    values (4162, 20970, 1);
');

-- Tabela "rhpessoalmov"
SELECT fc_executa_ddl('
  insert into db_syscampo     values (20972 ,''rh02_tipodeficiencia'' ,''int4'' ,''Representa a foreign key da tabela "tipodeficiencia".'' ,''0'' ,''Tipo de Deficiência'' ,10 ,''true'' ,''false'' ,''false'' ,1 ,''text'' ,''Tipo de Deficiência'' );
');
SELECT fc_executa_ddl('
  insert into db_sysarqcamp   values (1158 ,20972 ,25 ,0 );
');
SELECT fc_executa_ddl('
  insert into db_sysforkey    values (1158 ,20972 ,1  ,3777, 0);
');

-- Menu
delete from db_menu      where id_item_filho = 10043;
delete from db_itensmenu where id_item = 10043;

insert into db_itensmenu values (10043, 'Caged Mensal', 'Geração de arquivo para Caged Mensal', 'pes1_caged_mensal001.php', '1', '1', 'Geração de arquivo para Caged Mensal.', 'true');
insert into db_menu      values (5106 , 10043, 20, 952);

update db_menu set menusequencia = 1  where id_item = 5106 and modulo = 952 and id_item_filho = 5098;
update db_menu set menusequencia = 2  where id_item = 5106 and modulo = 952 and id_item_filho = 5000;
update db_menu set menusequencia = 3  where id_item = 5106 and modulo = 952 and id_item_filho = 5107;
update db_menu set menusequencia = 4  where id_item = 5106 and modulo = 952 and id_item_filho = 10043;
update db_menu set menusequencia = 5  where id_item = 5106 and modulo = 952 and id_item_filho = 5733;
update db_menu set menusequencia = 6  where id_item = 5106 and modulo = 952 and id_item_filho = 48643;
update db_menu set menusequencia = 7  where id_item = 5106 and modulo = 952 and id_item_filho = 228040;
update db_menu set menusequencia = 8  where id_item = 5106 and modulo = 952 and id_item_filho = 268991;
update db_menu set menusequencia = 9  where id_item = 5106 and modulo = 952 and id_item_filho = 608584;
update db_menu set menusequencia = 10 where id_item = 5106 and modulo = 952 and id_item_filho = 7761;
update db_menu set menusequencia = 11 where id_item = 5106 and modulo = 952 and id_item_filho = 8747;
update db_menu set menusequencia = 12 where id_item = 5106 and modulo = 952 and id_item_filho = 8756;
update db_menu set menusequencia = 13 where id_item = 5106 and modulo = 952 and id_item_filho = 8779;
update db_menu set menusequencia = 14 where id_item = 5106 and modulo = 952 and id_item_filho = 9847;
update db_menu set menusequencia = 15 where id_item = 5106 and modulo = 952 and id_item_filho = 9866;
update db_menu set menusequencia = 16 where id_item = 5106 and modulo = 952 and id_item_filho = 9935;

----------------------------
------ FIM TIME FOLHA ------
----------------------------
