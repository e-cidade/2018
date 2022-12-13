<?php

use Classes\PostgresMigration;

class M6535 extends PostgresMigration
{

public function up(){
    $sql = <<<'SQL'

    select fc_executa_ddl('insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21801 ,''h82_formulafim'' ,''int4'' ,''Fórmula que informa a data de fim do assentamento que será lançado.'' ,''0'' ,''Fórmula de Fim'' ,19 ,''false'' ,''false'' ,''false'' ,1 ,''text'' ,''Fórmula de Fim'' );');
select fc_executa_ddl('insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3835 ,21801 ,7 ,0 );');
select fc_executa_ddl('insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21802 ,''h82_formulafaltasperiodo'' ,''int4'' ,''Fórmula que informa a as faltas por período do assentamento que será lançado.'' ,''0'' ,''Fórmula de Faltas por Período'' ,19 ,''false'' ,''false'' ,''false'' ,1 ,''text'' ,''Fórmula de Faltas por Período'' );');
select fc_executa_ddl('insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3835 ,21802 ,8 ,0 );');
select fc_executa_ddl('delete from db_syscadind where codind = 4229;');
select fc_executa_ddl('insert into db_syscadind values(4229,21280,1);');
select fc_executa_ddl('insert into db_syscadind values(4229,21283,4);');
select fc_executa_ddl('insert into db_syscadind values(4229,21284,5);');

select fc_executa_ddl('update db_syscampo set nulo = true where codcam = 21801');
select fc_executa_ddl('update db_syscampo set nulo = true where codcam = 21802;');
select fc_executa_ddl('insert into db_syscampo values (21954,\'h82_formulaprorrogafim\',\'int4\',\'Formula que calcula data final do afastamento, contando a prorrogação da data fina por faltas, licenças, etc\',\'0\', \'Fórmula de Prorrogação do Fim\',10,\'t\',\'f\',\'f\',1,\'text\',\'Fórmula de Prorrogação do Fim\');');
select fc_executa_ddl('insert into db_sysarqcamp values(3835,21954,9,0)');

select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10250 ,\'Concessão de Direitos\' ,\'Concessão de Direitos\' ,\'\' ,\'1\' ,\'1\' ,\'Concessão de Direitos\' ,\'true\' );');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10250 AND modulo = 2323;');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10250 ,470 ,2323 );');
select fc_executa_ddl('update db_itensmenu set id_item = 10114 , descricao = \'Processamento\', help = \'Processamento\' where id_item = 10114;');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10114 AND modulo = 2323;');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,10114 ,1 ,2323 );');
select fc_executa_ddl('update db_itensmenu set id_item = 10113 , descricao = \'Parâmetros\' , help = \'Parâmetros\' where id_item = 10113;');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10113 AND modulo = 2323;');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,10113 ,2 ,2323 );');

select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10251 ,\'Previsão de Direitos\' ,\'Previsão de Direitos\' ,\'rec2_previsaodedireitos001.php\' ,\'1\' ,\'1\' ,\'Previsão de Direitos\' ,\'true\' );');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10251 AND modulo = 2323;');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,10251 ,452 ,2323 );');
update db_syscampo set conteudo = 'float4' where codcam = 20927;

--ddl
select fc_executa_ddl('alter table agendaassentamento add column h82_formulafim integer;');
select fc_executa_ddl('alter table agendaassentamento add column h82_formulafaltasperiodo integer;');
select fc_executa_ddl('update agendaassentamento set h82_formulafim = (select db148_sequencial from db_formulas where db148_nome = ''FINAL_GTS''), h82_formulafaltasperiodo = (select db148_sequencial from db_formulas where db148_nome = ''FALTAS_PERIODO'');');
select fc_executa_ddl('alter table agendaassentamento alter column h82_formulafim set not null;');
select fc_executa_ddl('alter table agendaassentamento alter column h82_formulafaltasperiodo set not null;');
select fc_executa_ddl('
    ALTER TABLE agendaassentamento
        ADD CONSTRAINT agendaassentamento_formulafim_fk FOREIGN KEY (h82_formulafim)
        REFERENCES db_formulas;
');
select fc_executa_ddl('
    ALTER TABLE agendaassentamento
        ADD CONSTRAINT agendaassentamento_formulafaltasperiodo_fk FOREIGN KEY (h82_formulafaltasperiodo)
        REFERENCES db_formulas;
');
select fc_executa_ddl('DROP INDEX agendaassentamento_un_in;');
select fc_executa_ddl('CREATE UNIQUE INDEX agendaassentamento_un_in ON agendaassentamento(h82_tipoassentamento, h82_selecao, h82_instit);');

select fc_executa_ddl('alter table agendaassentamento alter h82_formulafim drop not null;');
select fc_executa_ddl('alter table agendaassentamento alter h82_formulafaltasperiodo drop not null;');
select fc_executa_ddl('alter table agendaassentamento add h82_formulaprorrogafim integer;');
select fc_executa_ddl('alter table agendaassentamento add constraint agendaassentamento_h82_formulaprorrogafim_fk foreign key (h82_formulaprorrogafim) references db_formulas(db148_sequencial);');
select fc_executa_ddl('alter table rhpreponto alter rh149_quantidade type numeric;');
alter table db_formulas alter db148_nome type varchar(100);    

SQL;
  
    $this->execute($sql);
  }

public function down(){}

}
