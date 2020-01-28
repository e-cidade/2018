<?php

use Classes\PostgresMigration;

class M9704IptuTaquari extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into iptucadlogcalc values (115, 'MATRICULA COM CARACTERISTICA DO TIPO APARTAMENTO NAO PODE TER MAIS DE UMA CONSTRUCAO. VERIFIQUE.', true);");

        $this->execute("insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 185 ,'fc_calculoiptu_taquari_2018' ,'calculoiptu_taquari_2018.sql' ,'Cálculo de IPTU para 2018' ,'.' ,'0' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 992 ,185 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'' ,'MATRICULA' );");
        $this->execute("update db_sysfuncoesparam set db42_sysfuncoesparam = 992 , db42_funcao = 185 , db42_ordem = 1 , db42_nome = 'iMatricula' , db42_tipo = 'int4' , db42_tamanho = 0 , db42_precisao = 0 , db42_valor_default = '0' , db42_descricao = 'MATRICULA' where db42_sysfuncoesparam = 992;");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 993 ,185 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'0' ,'ANO DE CALCULO' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 994 ,185 ,3 ,'lGerafinanc' ,'bool' ,0 ,0 ,'0' ,'SE GERA FINANCEIRO' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 995 ,185 ,4 ,'lAtualizap' ,'bool' ,0 ,0 ,'0' ,'ATUALIZA PARCELAS' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 996 ,185 ,5 ,'lNovonumpre ' ,'bool' ,0 ,0 ,'0' ,'NOVO NUMPRE' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 997 ,185 ,6 ,'lCalculogeral' ,'bool' ,0 ,0 ,'0' ,'SE CALCULO GERAL' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 998 ,185 ,7 ,'lDemo' ,'bool' ,0 ,0 ,'0' ,'SE E DEMOSNTRATIVO' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 999 ,185 ,8 ,'iParcelaini' ,'int4' ,0 ,0 ,'0' ,'PARCELA INICIAL' );");
        $this->execute("insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1000 ,185 ,9 ,'iParcelafim' ,'int4' ,0 ,0 ,'0' ,'PARCELA FIM' );");

    }

    public function down()
    {
        $this->execute("DELETE FROM iptucadlogcalc where j62_codigo = 115;");
        $this->execute("DELETE FROM db_sysfuncoesparam where db42_funcao = 185;");
        $this->execute("DELETE FROM db_sysfuncoes where codfuncao = 185;");
    }
}
