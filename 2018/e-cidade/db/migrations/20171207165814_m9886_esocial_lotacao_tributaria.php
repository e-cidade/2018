<?php

use Classes\PostgresMigration;

class M9886EsocialLotacaoTributaria extends PostgresMigration
{
    public function up()
    {
        $this->adicionaFormulario();
        $this->adicionaDicionario();
        $this->criaTabela();
    }

    public function down()
    {
        $id  = 3000014;
        $sql = <<<SQL
            delete from esocialversaoformulario where rh211_avaliacao = $id;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id));
            delete from avaliacaogrupopergunta where db102_avaliacao in ($id);
            delete from esocialversaoformulario where rh211_avaliacao in ($id);
            delete from avaliacao where db101_sequencial in ($id);

            delete from db_menu where id_item_filho = 10479 AND modulo = 10216;
            delete from db_itensmenu where id_item = 10479;
            delete from esocialformulariotipo where rh209_sequencial = 4;

            delete from esocialversaoformulario where  rh211_versao =  '2.4' and rh211_avaliacao = 3000013;

            -- Remove dicionario da tabela avaliacaogruporespostalotacao
            delete from db_sysarqcamp   where codarq       = 1010246;
            delete from db_syssequencia where codsequencia = 1000706;
            delete from db_sysforkey    where codarq       = 1010246;
            delete from db_sysprikey    where codarq       = 1010246;
            delete from db_sysarqmod    where codarq       = 1010246;
            delete from db_syscampo     where codcam in (1009555, 1009556, 1009557);
            delete from db_sysarquivo   where codarq       = 1010246;
SQL;
        $this->execute($sql);
        $this->removeTabela();
    }

    private function adicionaDicionario()
    {
        $sql = <<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10479 ,'Lota��o Tribut�ria' ,'Lota��es Tribut�rias' ,'eso01_preenchimentolotacaotributaria.php' ,'1' ,'1' ,'Lota��es Tribut�rias' ,'true' );
            delete from db_menu where id_item_filho = 10479 AND modulo = 10216;
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10479 ,4 ,10216 );

            insert into esocialformulariotipo values(4, 'Lotacao Tributaria');
            insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000014, 4);
            -- Para o Formulario do servidor nao parar de funcionar
            insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000013, 3);

            -- Dicionario da tabela avaliacaogruporespostalotacao
            insert into db_sysarquivo values (1010246, 'avaliacaogruporespostalotacao', 'Grupo de respostas da Lota��o Tribut�ria do eSocial', 'eso04', '2017-12-09', 'Respostas da Lota��o', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod  values (81,1010246);
            insert into db_syscampo   values(1009555,'eso04_sequencial','int4','C�digo Sequencial da tabela avaliacaogruporespostalotacao','0', 'C�digo Sequencial',19,'f','f','f',1,'text','C�digo Sequencial');
            insert into db_syscampo   values(1009556,'eso04_avaliacaogruporesposta','int4','Campo de vinculo da resposta com o cgm','0', 'C�digo do Grupo de Respostas',19,'f','f','f',1,'text','C�digo do Grupo de Respostas');
            insert into db_syscampo   values(1009557,'eso04_cgm','int4','C�digo de vinculo do cgm com o grupo de respostas','0', 'C�digo do Cgm',19,'f','f','f',1,'text','C�digo do Cgm');

            delete from db_sysarqcamp where codarq = 1010246;
            insert into db_sysarqcamp values(1010246,1009555,1,1000706);
            insert into db_sysarqcamp values(1010246,1009556,2,0);
            insert into db_sysarqcamp values(1010246,1009557,3,0);

            delete from db_sysprikey where codarq = 1010246;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010246,1009555,1,1009555);
            delete from db_sysforkey where codarq = 1010246 and referen = 0;
            insert into db_sysforkey  values(1010246,1009556,1,2987,0);
            delete from db_sysforkey where codarq = 1010246 and referen = 0;
            insert into db_sysforkey    values(1010246,1009557,1,42,0);
            insert into db_syssequencia values(1000706, 'avaliacaogruporespostalotacao_eso04_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000706 where codarq = 1010246 and codcam = 1009555;
SQL;
        $this->execute($sql);
    }

    private function adicionaFormulario()
    {
        $sql = <<<SQL
        insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000014 ,5 ,'Formul�rio S1020 v2.4.01_Beta' ,'s1020s_v.2.4.01_Beta_Migra' ,'Formul�rio' ,'true' ,'' ,'true' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000191 ,3000014 ,'Informa��es de identifica��o da lota��o e validade das informa��es que est�o sendo inclu�das' ,'informacoes-de-identificacao-da-lotacao-e-validade' ,'ideLotacao' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000860 ,2 ,3000191 ,'Informar o c�digo atribu�do pela empresa para a lota��o tribut�ria. ' ,'informar-o-codigo-atribuido-pela-empresa-para-a-lo' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'codLotacao' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003555 ,3000860 ,'' ,'' ,'true' ,0 ,'' ,'codLotacao' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000861 ,2 ,3000191 ,'Preencher com o m�s e ano de in�cio da validade das informa��es prestadas no evento, no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-inicio-d5a2ab3853b732' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'iniValid' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003556 ,3000861 ,'' ,'5a2ab3853c80f' ,'true' ,0 ,'' ,'iniValid' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000862 ,2 ,3000191 ,'Preencher com o m�s e ano de t�rmino da validade das informa��es, se houver, no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-termino-5a2ab3853d177' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'fimValid' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003557 ,3000862 ,'' ,'5a2ab3854473f' ,'true' ,0 ,'' ,'fimValid' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000192 ,3000014 ,'Detalhamento das informa��es da lota��o que est� sendo inclu�da' ,'detalhamento-das-informacoes-da-lotacao-que-esta-s' ,'dadosLotacao' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000863 ,1 ,3000192 ,'Preencher com o c�digo correspondente ao tipo de lota��o:' ,'preencher-com-o-codigo-correspondente-ao-tipo-de-l' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpLotacao' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003558 ,3000863 ,'Classifica��o da atividade econ�mica exercida pela Pessoa Jur�dica para fins de atribui��o de c�digo FPAS' ,'classificacao-da-atividade-economica-exercida-pela' ,'false' ,0 ,'1' ,'tpLotacao_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003559 ,3000863 ,'Obra de Constru��o Civil - Empreitada Parcial ou Subempreitada' ,'obra-de-construcao-civil-empreitada-parcial-ou-sub' ,'false' ,0 ,'2' ,'tpLotacao_2' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003560 ,3000863 ,'Pessoa F�sica Tomadora de Servi�os prestados mediante cess�o de m�o de obra, exceto contratante de cooperativa' ,'pessoa-fisica-tomadora-de-servicos-prestados-media' ,'false' ,0 ,'3' ,'tpLotacao_3' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003561 ,3000863 ,'Pessoa Jur�dica Tomadora de Servi�os prestados mediante cess�o de m�o de obra, exceto contratante de cooperativa' ,'pessoa-juridica-tomadora-de-servicos-prestados-med' ,'false' ,0 ,'4' ,'tpLotacao_4' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003562 ,3000863 ,'Pessoa Jur�dica Tomadora de Servi�os prestados por cooperados por interm�dio de cooperativa de trabalho' ,'pessoa-juridica-tomadora-de-servicos-prestados-por' ,'false' ,0 ,'5' ,'tpLotacao_5' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003563 ,3000863 ,'Entidade beneficente/isenta Tomadora de Servi�os prestados por CNPJ do Estabelecimento Contratante cooperados por interm�dio de cooperativa de trabalho' ,'entidade-beneficenteisenta-tomadora-de-servicos-pr' ,'false' ,0 ,'6' ,'tpLotacao_6' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003564 ,3000863 ,'Pessoa F�sica tomadora de Servi�os prestados por Cooperados por interm�dio de Cooperativa de Trabalho' ,'pessoa-fisica-tomadora-de-servicos-prestados-por-c' ,'false' ,0 ,'7' ,'tpLotacao_7' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003565 ,3000863 ,'Operador Portu�rio tomador de servi�os de trabalhadores avulsos' ,'operador-portuario-tomador-de-servicos-de-trabalha' ,'false' ,0 ,'8' ,'tpLotacao_8' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003566 ,3000863 ,'Contratante de trabalhadores avulsos n�o portu�rios por interm�dio de Sindicato' ,'contratante-de-trabalhadores-avulsos-nao-portuario' ,'false' ,0 ,'9' ,'tpLotacao_9' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003567 ,3000863 ,'Embarca��o inscrita no Registro Especial Brasileiro - REB' ,'embarcacao-inscrita-no-registro-especial-brasileir' ,'false' ,0 ,'10' ,'tpLotacao_10' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003568 ,3000863 ,'Classifica��o da atividade econ�mica ou obra pr�pria de constru��o civil da Pessoa F�sica' ,'classificacao-da-atividade-economica-ou-obra-propr' ,'false' ,0 ,'21' ,'tpLotacao_21' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003569 ,3000863 ,'Empregador Dom�stico' ,'empregador-domestico' ,'false' ,0 ,'24' ,'tpLotacao_24' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003570 ,3000863 ,'Atividades desenvolvidas no exterior por trabalhador vinculado ao Regime Geral de Previd�ncia Social (expatriados)' ,'atividades-desenvolvidas-no-exterior-por-trabalhad' ,'false' ,0 ,'90' ,'tpLotacao_90' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003571 ,3000863 ,'Atividades desenvolvidas por trabalhador estrangeiro vinculado a Regime de Previd�ncia Social Estrangeiro' ,'atividades-desenvolvidas-por-trabalhador-estrangei' ,'false' ,0 ,'91' ,'tpLotacao_91' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000864 ,1 ,3000192 ,'C�digo correspondente ao tipo de inscri��o:' ,'codigo-correspondente-ao-tipo-de-inscricao' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpInsc' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003572 ,3000864 ,'CNPJ' ,'cnpj' ,'false' ,0 ,'1' ,'tpInsc_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003573 ,3000864 ,'CPF' ,'cpf5a2ab38555d9a' ,'false' ,0 ,'2' ,'tpInsc_2' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003574 ,3000864 ,'CNO (Cadastro Nacional de Obra)' ,'cno-cadastro-nacional-de-obra' ,'false' ,0 ,'4' ,'tpInsc_4' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000865 ,2 ,3000192 ,'Preencher com o n�mero de Inscri��o (CNPJ, CPF, CNO).' ,'preencher-com-o-numero-de-inscricao-cnpj-cpf-cno' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrInsc' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003575 ,3000865 ,'' ,'5a2ab38557bef' ,'true' ,0 ,'' ,'nrInsc' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000193 ,3000014 ,'Informa��es de FPAS e Terceiros relativas � lota��o tribut�ria' ,'informacoes-de-fpas-e-terceiros-relativas-a-lotaca' ,'fpasLotacao' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000866 ,1 ,3000193 ,'Preencher com o c�digo relativo ao FPAS' ,'preencher-com-o-codigo-relativo-ao-fpas' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'fpas' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003576 ,3000866 ,'Ind�stria, Escrit�rio e Dep�sito de Empresa Industrial, Ind�stria de carnes e derivados entre outros' ,'industria-escritorio-e-deposito-de-empresa-industr' ,'false' ,0 ,'507' ,'fpas_507' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003577 ,3000866 ,'Com�rcio atacadista, Varejista, Estabelecimento de servi�o de sa�de, Com�rcio transportador entre outros' ,'comercio-atacadista-varejista-estabelecimento-de-s' ,'false' ,0 ,'515' ,'fpas_515' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003578 ,3000866 ,'Sindicado e associa��o, trabalhador avulso ou empregador' ,'sindicado-e-associacao-trabalhador-avulso-ou-empre' ,'false' ,0 ,'523' ,'fpas_523' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003579 ,3000866 ,'Ind�stria de cana-de-a��car e latic�nios, extra��o de madeira, matadouro e abatedouro entre outros' ,'industria-de-canadeacucar-e-laticinios-extracao-de' ,'false' ,0 ,'531' ,'fpas_531' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003580 ,3000866 ,'Empresa de navega��o mar�tima, fluvial e lacustre, Empresa de administra��o e explora��o de portos entre outros' ,'empresa-de-navegacao-maritima-fluvial-e-lacustre-e' ,'false' ,0 ,'540' ,'fpas_540' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003581 ,3000866 ,'Empresa aerovi�ria' ,'empresa-aeroviaria' ,'false' ,0 ,'558' ,'fpas_558' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003582 ,3000866 ,'Empresa de comunica��o, publicidade, josrnalista.' ,'empresa-de-comunicacao-publicidade-josrnalista' ,'false' ,0 ,'566' ,'fpas_566' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003583 ,3000866 ,'Estabelecimento de ensino - Sociedade cooperativa ' ,'estabelecimento-de-ensino-sociedade-cooperativa-' ,'false' ,0 ,'574' ,'fpas_574' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003584 ,3000866 ,'�rg�o de poder p�blico, ' ,'orgao-de-poder-publico-' ,'false' ,0 ,'582' ,'fpas_582' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003585 ,3000866 ,'Cart�rio e tabelionato' ,'cartorio-e-tabelionato' ,'false' ,0 ,'590' ,'fpas_590' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003586 ,3000866 ,'Produtor Rural pessoa f�sica, jur�dica, cons�rcio simplificado de produtores rurais, agroind�stria' ,'produtor-rural-pessoa-fisica-juridica-consorcio-si' ,'false' ,0 ,'604' ,'fpas_604' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003587 ,3000866 ,'Empresa optante pelo simples nacional, transporte rodovi�rio, transporte simples entre outros' ,'empresa-optante-pelo-simples-nacional-transporte-r' ,'false' ,0 ,'612' ,'fpas_612' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003588 ,3000866 ,'Tomador de servi�o de transportador rodovi�rio aut�nomo' ,'tomador-de-servico-de-transportador-rodoviario-aut' ,'false' ,0 ,'620' ,'fpas_620' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003589 ,3000866 ,'Sociedade beneficente de assist�ncia social' ,'sociedade-beneficente-de-assistencia-social' ,'false' ,0 ,'639' ,'fpas_639' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003590 ,3000866 ,'Associa��o desportiva que mant�m equipe de futebol profissional ' ,'associacao-desportiva-que-mantem-equipe-de-futebol' ,'false' ,0 ,'647' ,'fpas_647' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003591 ,3000866 ,'Empresa de trabalho tempor�rio' ,'empresa-de-trabalho-temporario' ,'false' ,0 ,'655' ,'fpas_655' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003592 ,3000866 ,'�rg�o gestor de m�o-de-obra' ,'orgao-gestor-de-maodeobra' ,'false' ,0 ,'680' ,'fpas_680' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003593 ,3000866 ,'Banco comercial e de investimento, Banco de desenvolvimento - caixa eletr�nico entre outros' ,'banco-comercial-e-de-investimento-banco-de-desenvo' ,'false' ,0 ,'736' ,'fpas_736' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003594 ,3000866 ,'Empresa adquirente, consumidora, consignat�ria ou cooperativa, produtor rural de pessoa f�sica e jur�dica' ,'empresa-adquirente-consumidora-consignataria-ou-co' ,'false' ,0 ,'744' ,'fpas_744' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003595 ,3000866 ,'Associa��o desportiva que mant�m equipe de futebol profissional' ,'associacao-desportiva-que-mantem-equi5a2ab38570caf' ,'false' ,0 ,'779' ,'fpas_779' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003596 ,3000866 ,'Sindicato federa��o e confedera��o patronal rural, Atividade cooperativista rural entre outros' ,'sindicato-federacao-e-confederacao-patronal-rural-' ,'false' ,0 ,'787' ,'fpas_787' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003597 ,3000866 ,'Estabelecimento Rural e industrial de sociedade cooperativa' ,'estabelecimento-rural-e-industrial-de-sociedade-co' ,'false' ,0 ,'795' ,'fpas_795' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003598 ,3000866 ,'Tomador de servi�o de trabalhador avulso' ,'tomador-de-servico-de-trabalhador-avulso' ,'false' ,0 ,'825' ,'fpas_825' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003599 ,3000866 ,'Setor indutrial de agroind�stria e tomador de servi�o trabalhador avulso' ,'setor-indutrial-de-agroindustria-e-tomador-de-serv' ,'false' ,0 ,'833' ,'fpas_833' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003600 ,3000866 ,'Empregador Dom�stico' ,'empregador-domestico5a2ab38572dc5' ,'false' ,0 ,'868' ,'fpas_868' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003601 ,3000866 ,'Miss�es diplom�ticas e outros organismos a elas equiparados' ,'missoes-diplomaticas-e-outros-organismos-a-elas-eq' ,'false' ,0 ,'876' ,'fpas_876' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000867 ,2 ,3000193 ,'Preencher com o c�digo de Terceiros conforme tabela 4.' ,'preencher-com-o-codigo-de-terceiros-conforme-tabel' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codTercs' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003602 ,3000867 ,'' ,'5a2ab385745b5' ,'true' ,0 ,'' ,'codTercs' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000868 ,2 ,3000193 ,'Informar o c�digo combinado dos Terceiros para os quais o recolhimento est� suspenso em virtude de processos Judiciais. ' ,'informar-o-codigo-combinado-dos-terceiros-para-os-' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codTercsSusp' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003603 ,3000868 ,'' ,'5a2ab38575880' ,'true' ,0 ,'' ,'codTercsSusp' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000194 ,3000014 ,'Identifica��o do Processo Judicial' ,'identificacao-do-processo-judicial' ,'procJudTerceiro' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000869 ,2 ,3000194 ,'Informar o C�digo de Terceiro comforme tabela 4.' ,'informar-o-codigo-de-terceiro-comforme-tabela-4' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'codTerc' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003604 ,3000869 ,'' ,'5a2ab38576ff0' ,'true' ,0 ,'' ,'codTerc' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000870 ,2 ,3000194 ,'Informar um n�mero de processo judicial cadastrado atrav�s do evento S- 1070.' ,'informar-um-numero-de-processo-judicial-cadastrado' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrProcJud' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003605 ,3000870 ,'' ,'5a2ab385781d1' ,'true' ,0 ,'' ,'nrProcJud' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000871 ,2 ,3000194 ,'C�digo do Indicativo da Suspens�o, atribu�do pelo empregador em S-1070.' ,'codigo-do-indicativo-da-suspensao-at5a2ab385789e1' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codSusp' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003606 ,3000871 ,'' ,'5a2ab38579544' ,'true' ,0 ,'' ,'codSusp' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000195 ,3000014 ,'Informa��o complementar que apresenta identifica��o do contratante e do propriet�rio de obra' ,'informacao-complementar-que-apresenta-identificaca' ,'infoEmprParcial' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000872 ,1 ,3000195 ,'Tipo de Inscri��o do contratante:' ,'tipo-de-inscricao-do-contratante' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpInscContrat' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003607 ,3000872 ,'CNPJ' ,'cnpj5a2ab3857ac42' ,'false' ,0 ,'1' ,'tpInscContrat_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003608 ,3000872 ,'CPF' ,'cpf5a2ab3857b377' ,'false' ,0 ,'2' ,'tpInscContrat_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000873 ,2 ,3000195 ,'N�mero de Inscri��o (CNPJ/CPF) do Contrante.' ,'numero-de-inscricao-cnpjcpf-do-contrante' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrInscContrat' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003609 ,3000873 ,'' ,'5a2ab3857c60d' ,'true' ,0 ,'' ,'nrInscContrat' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000874 ,1 ,3000195 ,'Tipo de Inscri��o do propriet�rio do CNO:' ,'tipo-de-inscricao-do-proprietario-do-cno' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'tpInscProp' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003610 ,3000874 ,'CNPJ' ,'cnpj5a2ab3857d8c3' ,'false' ,0 ,'1' ,'tpInscProp_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003611 ,3000874 ,'CPF' ,'cpf5a2ab3857dfed' ,'false' ,0 ,'2' ,'tpInscProp_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000875 ,2 ,3000195 ,'Preencher com o n�mero de inscri��o (CNPJ/CPF) do propriet�rio do CNO.' ,'preencher-com-o-numero-de-inscricao-cnpjcpf-do-pro' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'nrInscProp' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003612 ,3000875 ,'' ,'5a2ab3857f0cc' ,'true' ,0 ,'' ,'nrInscProp' );
SQL;
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $sql = <<<SQL
            CREATE SEQUENCE esocial.avaliacaogruporespostalotacao_eso04_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE esocial.avaliacaogruporespostalotacao(
                eso04_sequencial             int4 NOT NULL default 0,
                eso04_avaliacaogruporesposta int4 NOT NULL default 0,
                eso04_cgm                    int4 NOT NULL default 0,
                CONSTRAINT avaliacaogruporespostalotacao_sequ_pk PRIMARY KEY (eso04_sequencial)
            );

            ALTER TABLE esocial.avaliacaogruporespostalotacao
                ALTER COLUMN eso04_sequencial
                SET DEFAULT nextval('esocial.avaliacaogruporespostalotacao_eso04_sequencial_seq');

            ALTER TABLE esocial.avaliacaogruporespostalotacao
                ADD CONSTRAINT avaliacaogruporespostalotacao_avaliacaogruporesposta_fk FOREIGN KEY (eso04_avaliacaogruporesposta)
                REFERENCES avaliacaogruporesposta;

            ALTER TABLE esocial.avaliacaogruporespostalotacao
                ADD CONSTRAINT avaliacaogruporespostalotacao_cgm_fk FOREIGN KEY (eso04_cgm)
                REFERENCES cgm;

SQL;
        $this->execute($sql);
    }

    private function removeTabela()
    {
        $sql = <<<SQL
            DROP TABLE esocial.avaliacaogruporespostalotacao;
            DROP SEQUENCE esocial.avaliacaogruporespostalotacao_eso04_sequencial_seq;

SQL;
        $this->execute($sql);
    }
}
