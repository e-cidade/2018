<?php

use Classes\PostgresMigration;

class M9392MatriculaOnline extends PostgresMigration
{
    public function up()
    {
        $this->execute("delete from db_itensmenu where id_item = 10101");
        $this->execute("delete from db_itensmenu where id_item = 10102");
        $this->execute("delete from db_itensmenu where id_item = 10103");
        $this->execute("delete from db_itensmenu where id_item = 10100");
        $this->execute("delete from db_itensmenu where id_item = 10105");
        $this->execute("delete from db_itensmenu where id_item = 10106");
        $this->execute("delete from db_itensmenu where id_item = 10107");
        $this->execute("delete from db_itensmenu where id_item = 10104");
        $this->execute("delete from db_itensmenu where id_item = 10095");
        $this->execute("delete from db_itensmenu where id_item = 10143");
        $this->execute("delete from db_itensmenu where id_item = 10145");
        $this->execute("delete from db_itensmenu where id_item = 10150");
        $this->execute("delete from db_itensmenu where id_item = 10151");
        $this->execute("delete from atendcadareamod where at26_id_item = 10094");
        $this->execute("delete from db_modulos where id_item = 10094");
        $this->execute("delete from db_itensmenu where id_item = 10094");
    }

    public function down()
    {
        $this->execute("insert into db_itensmenu values (10094, 'Matr�cula On-line', 'Matr�cula On-line', '', 2, 1, 'Matr�cula On-line', 't')");
        $this->execute("insert into db_modulos values (10094, 'Matr�cula On-line', 'Matr�cula On-line', '', 'f', '')");
        $this->execute("insert into atendcadareamod values (75, 8, 10094)");
        $this->execute("insert into db_itensmenu values (10095, 'Vagas'                          , 'Vagas'                          , 'mol1_vagas001.php'                     , 1, 1, 'Cadastro de vagas que existem para as etapas de uma determinada fase.', 't')");
        $this->execute("insert into db_itensmenu values (10100, 'Ciclos'                         , 'Ciclos'                         , ''                                      , 1, 1, 'Menu para manuten��o dos ciclos da matr�cula online.'                 , 't')");
        $this->execute("insert into db_itensmenu values (10101, 'Inclus�o'                       , 'Inclus�o'                       , 'mol1_ciclos001.php'                    , 1, 1, 'Inclus�o de novos ciclos.'                                            , 't')");
        $this->execute("insert into db_itensmenu values (10102, 'Altera��o'                      , 'Altera��o'                      , 'mol1_ciclos002.php'                    , 1, 1, 'Altera��o das informa��es de um ciclo.'                               , 't')");
        $this->execute("insert into db_itensmenu values (10103, 'Exclus�o'                       , 'Exclus�o'                       , 'mol1_ciclos003.php'                    , 1, 1, 'Exclus�o de ciclos referentes a matr�cula online.'                    , 't')");
        $this->execute("insert into db_itensmenu values (10104, 'Fases'                          , 'Fases'                          , ''                                      , 1, 1, 'Fases'                                                                , 't')");
        $this->execute("insert into db_itensmenu values (10105, 'Inclus�o'                       , 'Inclus�o'                       , 'mol1_fase001.php'                      , 1, 1, 'Inclus�o de uma Fase.'                                                , 't')");
        $this->execute("insert into db_itensmenu values (10106, 'Altera��o'                      , 'Altera��o'                      , 'mol1_fase002.php'                      , 1, 1, 'Altera��o de uma Fase.'                                               , 't')");
        $this->execute("insert into db_itensmenu values (10107, 'Exclus�o'                       , 'Exclus�o'                       , 'mol1_fase003.php'                      , 1, 1, 'Exclus�o de uma Fase.'                                                , 't')");
        $this->execute("insert into db_itensmenu values (10143, 'Idade por Etapa'                , 'Idade por Etapa'                , 'mol1_idadeetapa001.php'                , 1, 1, 'Idade por Etapa'                                                      , 't')");
        $this->execute("insert into db_itensmenu values (10145, 'Escola por Bairro'              , 'Escola por Bairro'              , 'mol1_escolaporbairro001.php'           , 1, 1, 'Manuten��o dos v�nculos de escolas com bairros'                       , 't')");
        $this->execute("insert into db_itensmenu values (10150, 'Ordenar Crit�rios de Designa��o', 'Ordenar Crit�rios de Designa��o', 'mol3_ordenarcriteriosdesignacao001.php', 1, 1, 'Ordenar Crit�rios de Designa��o no ensino selecionado'                , 't')");
        $this->execute("insert into db_itensmenu values (10151, 'Processar Designa��o'           , 'Processar Designa��o'           , 'mol3_processardesignacao001.php'       , 1, 1, 'Processa a designa��o dos alunos de uma fase'                         , 't')");
    }
}
