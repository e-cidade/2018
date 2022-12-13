<?php

use Classes\PostgresMigration;

class PontoEletronicoAfastmentosM8673 extends PostgresMigration
{
    public function up()
    {
        $this->upDDL();
        $this->upDicionarioDados();
    }
    
    public function down()
    {
        $this->downDDL();
        $this->downDicionarioDados();
    }

    public function upDDL()
    {
        $this->execute("
        
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_trabalhadas type varchar;      
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_atraso type varchar;           
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_falta type varchar;            
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_50_d type varchar;      
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_75_d type varchar;      
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_100_d type varchar;     
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_adicinal_noturno type varchar;                          
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_50_n type varchar;      
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_75_n type varchar;      
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata alter rh197_horas_extras_100_n type varchar;     
        ALTER TABLE recursoshumanos.pontoeletronicoarquivodata add   rh197_afastamento integer;
     ");
        
      $this->execute("alter table recursoshumanos.pontoeletronicoarquivodata add CONSTRAINT pontoeletronicoarquivodata_matricula_fk FOREIGN KEY (rh197_matricula) REFERENCES pessoal.rhpessoal (rh01_regist)");  
      $this->execute("alter table recursoshumanos.pontoeletronicoarquivodata add CONSTRAINT pontoeletronicoarquivodata_afastamento_fk FOREIGN KEY (rh197_afastamento) REFERENCES recursoshumanos.assenta (h16_codigo)");  
      $this->execute("create index pontoeletronicoarquivodata_data_in                   on recursoshumanos.pontoeletronicoarquivodata(rh197_data);");  
      $this->execute("create index pontoeletronicoarquivodata_matricula_in              on recursoshumanos.pontoeletronicoarquivodata(rh197_matricula);");  
      $this->execute("create index pontoeletronicoarquivodata_pontoeletronicoarquivo_in on recursoshumanos.pontoeletronicoarquivodata(rh197_pontoeletronicoarquivo);");  
    }

    public function upDicionarioDados()
    {
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009336 ,'rh197_afastamento' ,'int4' ,'Assentamento de Afastamento do servidor na data' ,'null' ,'Afastamento' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Afastamento' );
                        delete from db_syscampodef where codcam = 1009336;
                        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 4014 ,1009336 ,15 ,0 );");
        $this->execute("        
        insert into db_sysforkey values(4014,22266,1,1153,0);
        insert into db_sysforkey values(4014,1009336,1,528,0);
        insert into db_sysindices values(1008202,'pontoeletronicoarquivodata_data_in',4014,'0');
        insert into db_syscadind values(1008202,22265,1);
        insert into db_sysindices values(1008203,'pontoeletronicoarquivodata_matricula_in',4014,'0');
        insert into db_syscadind values(1008203,22266,1);
        insert into db_sysindices values(1008204,'pontoeletronicoarquivodata_pontoeletronicoarquivo_in',4014,'0');
        insert into db_syscadind values(1008204,22264,1);
        ");
        
    }

    public function downDDL()
    {
      $this->execute("alter table recursoshumanos.pontoeletronicoarquivodata drop constraint pontoeletronicoarquivodata_matricula_fk;");
      $this->execute("alter table recursoshumanos.pontoeletronicoarquivodata drop constraint pontoeletronicoarquivodata_afastamento_fk;");
      $this->execute("drop index pontoeletronicoarquivodata_data_in;");
      $this->execute("drop index pontoeletronicoarquivodata_matricula_in;");
      $this->execute("drop index pontoeletronicoarquivodata_pontoeletronicoarquivo_in;");
      $this->execute("ALTER TABLE recursoshumanos.pontoeletronicoarquivodata drop rh197_afastamento;");
    }

    public function downDicionarioDados()
    {
        $this->execute("            
            delete from db_sysforkey where codcam  in(22266, 1009336);
            delete from db_sysindices where codind in(1008202, 1008203, 1008204);
            delete from db_syscadind where codind in(1008202, 1008203, 1008204);
            DELETE FROM db_sysarqcamp WHERE codcam = 1009336;
            DELETE FROM db_syscampo WHERE codcam = 1009336;
        ");
    }
}
