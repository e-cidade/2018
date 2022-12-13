<?php

use Classes\PostgresMigration;

class M5811 extends PostgresMigration
{
    public function up(){
        $sql = <<<'SQL'

            select fc_executa_ddl('
            insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,a
            insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3835 ,21801 ,7 ,0 );
            insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,a
            insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3835 ,21802 ,8 ,0 );
             
            delete from db_syscadind where codind = 4229;
            insert into db_syscadind values(4229,21280,1);
            insert into db_syscadind values(4229,21283,4);
            insert into db_syscadind values(4229,21284,5);
             
             
            alter table agendaassentamento add column h82_formulafim integer;
            alter table agendaassentamento add column h82_formulafaltasperiodo integer;
             
            update agendaassentamento set h82_formulafim = (select db148_sequencial from db_formulas where db148_nome = ''FIN
             
            alter table agendaassentamento alter column h82_formulafim set not null;
            alter table agendaassentamento alter column h82_formulafaltasperiodo set not null;
             
             
             
            ALTER TABLE agendaassentamento
            ADD CONSTRAINT agendaassentamento_formulafim_fk FOREIGN KEY (h82_formulafim)
            REFERENCES db_formulas;
             
            ALTER TABLE agendaassentamento
            ADD CONSTRAINT agendaassentamento_formulafaltasperiodo_fk FOREIGN KEY (h82_formulafaltasperiodo)
            REFERENCES db_formulas;
             
            DROP INDEX agendaassentamento_un_in;
            CREATE UNIQUE INDEX agendaassentamento_un_in ON agendaassentamento(h82_tipoassentamento, h82_selecao, h82_instit)         
            ');    

SQL;
  
    $this->execute($sql);
  }

public function down(){}
}
