<?php

use Classes\PostgresMigration;

class M8711IptuCriaTempTable extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL

create or replace function fc_iptu_criatemptable(boolean) returns boolean as
$$
declare

     lRaise alias for $1;

     rbErro boolean default false;
     nome   name;

begin

   /**
    * FUNCAO PARA CRIAR AS TABELAS TEMPORARIAS
    */
  perform fc_debug('', lRaise);
  perform fc_debug(' <iptu_criatemptable> INICIANDO CRIACAO DE ESTRUTURAS TEMPORARIAS...', lRaise);

  begin

    /*
     * NAO REMOVER CAMPOS DESSAS TABELAS, ESSA ALTERACAO PODE CAUSAR PROBLEMAS EM TODOS OS CALCULOS
     * QUANDO USAR AS TABELAS TEMPORARIAS NAO USE SELECT * INTO VAI1, VAR2,VAR3 FROM XXX.
     * USE: SELECT CAMPO1,CAMPO2,CAMPO3 INTO  VAR1, VAR2,VAR3 FROM XXXX.
     */

    /**
     * Tabela que guarda as receitas e valores das mesmas, para gerar o financeiro(arrecad)
     */
    create temporary table tmprecval( "receita" integer,"valor" numeric,"hist" integer,"taxa" boolean );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPRECVAL CRIADA', lRaise);

    /**
     * Tabela que guarda os dados referente ao comportamento do calculo durante o processamento das sub-funcoes
     */
    create temporary table tmpdadosiptu( "aliq"      numeric,
                                         "vvc"       numeric,
                                         "vvt"       numeric,
                                         "viptu"     numeric,
                                         "fracao"    numeric,
                                         "areat"     numeric,
                                         "predial"   boolean,
                                         "codvenc"   integer,
                                         "tipoisen"  integer,
                                         "vm2t"      numeric,
                                         "testada"   numeric,
                                         "matric"    integer,
                                         "isentaxas" boolean );
    insert into tmpdadosiptu values (0,0,0,0,0,0,false,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSIPTU CRIADA', lRaise);

    /**
     * Tabela que guarda os dados das contrucoes calculadas, alimentada pela fc_iptu_calculavvc
     */
    create temporary table tmpiptucale( "anousu"         integer,
                                        "matric"         integer,
                                        "idcons"         integer,
                                        "areaed"         numeric,
                                        "vm2"            numeric,
                                        "pontos"         integer,
                                        "valor"          numeric,
                                        "edificacao"     boolean,
                                        "caracteristica" integer,
                                        "aliquota"       numeric );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTUCALE CRIADA', lRaise);

    /**
     * Tabela que guarda os valores para calcular as taxas
     */
    create temporary table tmpdadostaxa( "anousu"  integer,
                                         "matric"  integer,
                                         "zona"    integer,
                                         "idbql"   integer,
                                         "nparc"   integer,
                                         "valiptu" numeric,
                                         "valref"  numeric,
                                         "vvt"     numeric,
                                         "totareaconst" numeric );
    insert into tmpdadostaxa values (0,0,0,0,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSTAXA CRIADA', lRaise);

    /**
     * Tabela com os parametros para o comportamento da fase do calculo que gera o financeiro
     */
    create temporary table tmpfinanceiro("anousu" integer,"matric" integer,"idbql" integer,"valiptu" numeric,"valref" numeric,"vvt" numeric);
    insert into tmpfinanceiro values (0,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPFINANCEIRO CRIADA', lRaise);

    /**
     * Tabela que guarda as receitas e percentual de isencao das taxas
     */
    create temporary table tmptaxapercisen("rectaxaisen" integer,"percisen" numeric, "histcalcisen" integer,"valsemisen" numeric);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

    /**
     * Tabela que guarda os valores para "outras" taxas (taxa bombeiro, limpeza)
     */
    create temporary table tmpoutrosvalores("valor" numeric,"descricao" varchar);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

    /**
     * Tabela que guarda os valores de vencimentos
     */
    create temporary table tmp_cadvenc as
      select q92_codigo,
             q92_tipo,
             q92_hist,
             q92_vlrminimo,
             q82_parc,
             q82_venc,
             q82_perc,
             q82_hist
        from cadvencdesc
             inner join cadvenc on q92_codigo = q82_codigo
       limit 0;
    perform fc_debug(' <iptu_criatemptable> TABELA TMP_CADVENC CRIADA', lRaise);

    /**
     * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
     */
    create temporary table tmpipturecalculonump (
        matricula integer,
        anousu    integer,
        numpre    integer
    );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULONUMP CRIADA', lRaise);

    /**
     * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
     */
    create temporary table tmpipturecalculocreditonump (
        matricula integer,
        anousu    integer,
        numpre    integer
    );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULOCREDITONUMP CRIADA', lRaise);

  exception
       when duplicate_table then
            truncate tmprecval;
            truncate tmpdadosiptu;
            truncate tmpiptucale;
            truncate tmpdadostaxa;
            truncate tmpfinanceiro;
            truncate tmptaxapercisen;
            truncate tmpoutrosvalores;
            truncate tmp_cadvenc;
            truncate tmpipturecalculonump;
            truncate tmpipturecalculocreditonump;
            insert into tmpdadosiptu  values (0,0,0,0,0,0,false,0,0,0,0,0,false);
            insert into tmpdadostaxa  values (0,0,0,0,0,0,0,0,0);
            insert into tmpfinanceiro values (0,0,0,0,0,0);
  end;

  perform fc_debug(' <iptu_criatemptable> FIM CRIACAO DE ESTRUTURAS TEMPORARIAS', lRaise);
  perform fc_debug('', lRaise);

  return rbErro;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL

create or replace function fc_iptu_criatemptable(boolean) returns boolean as
$$
declare

     lRaise alias for $1;

     rbErro boolean default false;
     nome   name;

begin

   /**
    * FUNCAO PARA CRIAR AS TABELAS TEMPORARIAS
    */
  perform fc_debug('', lRaise);
  perform fc_debug(' <iptu_criatemptable> INICIANDO CRIACAO DE ESTRUTURAS TEMPORARIAS...', lRaise);

  begin

    /*
     * NAO REMOVER CAMPOS DESSAS TABELAS, ESSA ALTERACAO PODE CAUSAR PROBLEMAS EM TODOS OS CALCULOS
     * QUANDO USAR AS TABELAS TEMPORARIAS NAO USE SELECT * INTO VAI1, VAR2,VAR3 FROM XXX.
     * USE: SELECT CAMPO1,CAMPO2,CAMPO3 INTO  VAR1, VAR2,VAR3 FROM XXXX.
     */

    /**
     * Tabela que guarda as receitas e valores das mesmas, para gerar o financeiro(arrecad)
     */
    create temporary table tmprecval( "receita" integer,"valor" numeric,"hist" integer,"taxa" boolean );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPRECVAL CRIADA', lRaise);

    /**
     * Tabela que guarda os dados referente ao comportamento do calculo durante o processamento das sub-funcoes
     */
    create temporary table tmpdadosiptu( "aliq"      numeric,
                                         "vvc"       numeric,
                                         "vvt"       numeric,
                                         "viptu"     numeric,
                                         "fracao"    numeric,
                                         "areat"     numeric,
                                         "predial"   boolean,
                                         "codvenc"   integer,
                                         "tipoisen"  integer,
                                         "vm2t"      numeric,
                                         "testada"   numeric,
                                         "matric"    integer,
                                         "isentaxas" boolean );
    insert into tmpdadosiptu values (0,0,0,0,0,0,false,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSIPTU CRIADA', lRaise);

    /**
     * Tabela que guarda os dados das contrucoes calculadas, alimentada pela fc_iptu_calculavvc
     */
    create temporary table tmpiptucale( "anousu"         integer,
                                        "matric"         integer,
                                        "idcons"         integer,
                                        "areaed"         numeric,
                                        "vm2"            numeric,
                                        "pontos"         integer,
                                        "valor"          numeric,
                                        "edificacao"     boolean,
                                        "caracteristica" integer,
                                        "aliquota"       numeric );
    perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTUCALE CRIADA', lRaise);

    /**
     * Tabela que guarda os valores para calcular as taxas
     */
    create temporary table tmpdadostaxa( "anousu"  integer,
                                         "matric"  integer,
                                         "zona"    integer,
                                         "idbql"   integer,
                                         "nparc"   integer,
                                         "valiptu" numeric,
                                         "valref"  numeric,
                                         "vvt"     numeric,
                                         "totareaconst" numeric );
    insert into tmpdadostaxa values (0,0,0,0,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSTAXA CRIADA', lRaise);

    /**
     * Tabela com os parametros para o comportamento da fase do calculo que gera o financeiro
     */
    create temporary table tmpfinanceiro("anousu" integer,"matric" integer,"idbql" integer,"valiptu" numeric,"valref" numeric,"vvt" numeric);
    insert into tmpfinanceiro values (0,0,0,0,0,0);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPFINANCEIRO CRIADA', lRaise);

    /**
     * Tabela que guarda as receitas e percentual de isencao das taxas
     */
    create temporary table tmptaxapercisen("rectaxaisen" integer,"percisen" numeric, "histcalcisen" integer,"valsemisen" numeric);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

    /**
     * Tabela que guarda os valores para "outras" taxas (taxa bombeiro, limpeza)
     */
        create temporary table tmpoutrosvalores("valor" numeric,"descricao" varchar);
    perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

    /**
     * Tabela que guarda os valores de vencimentos
     */
    create temporary table tmp_cadvenc as
      select q92_codigo,
             q92_tipo,
             q92_hist,
             q92_vlrminimo,
             q82_parc,
             q82_venc,
             q82_perc,
             q82_hist
        from cadvencdesc
             inner join cadvenc on q92_codigo = q82_codigo
       limit 0;
    perform fc_debug(' <iptu_criatemptable> TABELA TMP_CADVENC CRIADA', lRaise);

  exception
       when duplicate_table then
            truncate tmprecval;
            truncate tmpdadosiptu;
            truncate tmpiptucale;
            truncate tmpdadostaxa;
            truncate tmpfinanceiro;
            truncate tmptaxapercisen;
            truncate tmpoutrosvalores;
            truncate tmp_cadvenc;
            insert into tmpdadosiptu  values (0,0,0,0,0,0,false,0,0,0,0,0,false);
            insert into tmpdadostaxa  values (0,0,0,0,0,0,0,0,0);
            insert into tmpfinanceiro values (0,0,0,0,0,0);
  end;

  perform fc_debug(' <iptu_criatemptable> FIM CRIACAO DE ESTRUTURAS TEMPORARIAS', lRaise);
  perform fc_debug('', lRaise);

  return rbErro;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

}
