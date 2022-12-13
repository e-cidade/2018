<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


class bvmovant {
  var $arq=null;

  function bvmovant($header){
    umask(74);
    $this->arq = fopen("tmp/BVMOVANT.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");

  }


  function db_planocontassaldo_matriz_mes($anousu,$dataini,$datafim,$retsql=false,$where='',$estrut_inicial='',$acumula_reduzido='true',$encerramento='false'){
    if ($anousu == null ){
      echo "Problemas de estrutura no arquivo";
      exit;
    }
    if ($dataini == null ){
      echo "Problemas de estrutura no arquivo";
      exit;
    }
    if ($datafim == null ){
      echo "Problemas de estrutura no arquivo";
      exit;
    }
    if ($where != '') {
      $condicao = " and ".$where;
    }else{
      $condicao = "";
    }
    $pesq_estrut = "";
    if ($estrut_inicial !=""){
      // oberve a concatenação da variável
      $condicao .= "  and p.c60_estrut like '$estrut_inicial%' ";
    }
    $sql = "
    select
    estrut_mae,
    estrut,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    c60_descr,
    c60_finali,
    c61_instit,
     round(substr(p1bin,3, 15)::float8,2)::float8 as saldo_anterior,
       round(substr(p1bin,18, 15)::float8,2)::float8 as debito_1bin,
       round(substr(p1bin,33, 15)::float8,2)::float8 as credito_1bin,
       round(substr(p2bin,18, 15)::float8,2)::float8 as debito_2bin,
       round(substr(p2bin,33, 15)::float8,2)::float8 as credito_2bin,
       round(substr(p3bin,18, 15)::float8,2)::float8 as debito_3bin,
       round(substr(p3bin,33, 15)::float8,2)::float8 as credito_3bin,
       round(substr(p4bin,18, 15)::float8,2)::float8 as debito_4bin,
       round(substr(p4bin,33, 15)::float8,2)::float8 as credito_4bin,
       round(substr(p5bin,18, 15)::float8,2)::float8 as debito_5bin,
       round(substr(p5bin,33, 15)::float8,2)::float8 as credito_5bin,
       round(substr(p6bin,18, 15)::float8,2)::float8 as debito_6bin,
       round(substr(p6bin,33, 15)::float8,2)::float8 as credito_6bin,
       round(substr(p6bin,48, 15)::float,2)::float8 as saldo_final,
       substr(p1bin,63,1)::varchar(1) as sinal_anterior,
       substr(p6bin,64,1)::varchar(1) as sinal_final
    from

    (select p.c60_estrut as estrut_mae,
    p.c60_estrut as estrut,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    p.c60_descr,
    p.c60_finali,
    r.c61_instit,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-01-01'::date,('$anousu-02-'||fc_ultimodiames($anousu,2))::date,$encerramento) as p1bin,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-03-01'::date,'$anousu-04-30'::date,$encerramento) as p2bin,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-05-01'::date,'$anousu-06-30'::date,$encerramento) as p3bin,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-07-01'::date,'$anousu-08-31'::date,$encerramento) as p4bin,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-09-01'::date,'$anousu-10-31'::date,$encerramento) as p5bin,
    fc_planosaldosigned($anousu,c61_reduz,'$anousu-11-01'::date,'$anousu-12-31'::date,$encerramento) as p6bin

    from conplanoexe e
    inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
    inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
    left outer join consistema on c60_codsis = c52_codsis
    $pesq_estrut
    where c62_anousu = $anousu $condicao) as x
    ";

    db_query("create temporary table work_pl (
    estrut_mae varchar(15),
    estrut varchar(15),
    c61_reduz integer,
    c61_codcon integer,
    c61_codigo integer,
    c60_descr varchar(50),
    c60_finali text,
    c61_instit integer,
    saldo_anterior float8,
    debito_1bin float8,
    credito_1bin float8,

    debito_2bin float8,
    credito_2bin float8,

    debito_3bin float8,
    credito_3bin float8,

    debito_4bin float8,
    credito_4bin float8,

    debito_5bin float8,
    credito_5bin float8,

    debito_6bin float8,
    credito_6bin float8,

    saldo_final float8,
    sinal_anterior varchar(1),
    sinal_final varchar(1)) ");

    //   db_query("create temporary table work_plano as $sql");
    db_query("create index work_pl_estrut on work_pl(estrut)");
    db_query("create index work_pl_estrutmae on work_pl(estrut_mae)");

    $result = db_query($sql);
    //db_criatabela($result);exit;
    $tot_anterior        = 0;
    $tot_debito_1bin = 0;
    $tot_debito_2bin = 0;
    $tot_debito_3bin = 0;
    $tot_debito_4bin = 0;
    $tot_debito_5bin = 0;
    $tot_debito_6bin = 0;

    $tot_credito_1bin = 0;
    $tot_credito_2bin = 0;
    $tot_credito_3bin = 0;
    $tot_credito_4bin = 0;
    $tot_credito_5bin = 0;
    $tot_credito_6bin = 0;

    $tot_saldo_final     = 0;
    GLOBAL $seq;
    GLOBAL $estrut_mae;
    GLOBAL $estrut;
    GLOBAL $c61_reduz;
    GLOBAL $c61_codcon;
    GLOBAL $c61_codigo;
    GLOBAL $c60_codcon;
    GLOBAL $c60_descr;
    GLOBAL $c60_finali;
    GLOBAL $c61_instit;
    GLOBAL $saldo_anterior;
    GLOBAL $debito_1bin,$debito_2bin,$debito_3bin,$debito_4bin,$debito_5bin,$debito_6bin;
    GLOBAL $credito_1bin,$credito_2bin,$credito_3bin,$credito_4bin,$credito_5bin,$credito_6bin;
    GLOBAL $saldo_final;
    GLOBAL $result_estrut;
    GLOBAL $sinal_anterior;
    GLOBAL $sinal_final;

    $work_planomae    = array();
    $work_planoestrut = array();
    $work_plano = array();
    $seq = 0;

    for($i = 0;$i < pg_numrows($result);$i++){
      //  for($i = 0;$i < 20;$i++)
      db_fieldsmemory($result,$i);
      if($sinal_anterior == "C")
        $saldo_anterior *= -1;
      if($sinal_final == "C")
        $saldo_final *= -1;
      $tot_anterior         = $saldo_anterior;

      $tot_debito_1bin  = $debito_1bin;
      $tot_debito_2bin  = $debito_2bin;
      $tot_debito_3bin  = $debito_3bin;
      $tot_debito_4bin  = $debito_4bin;
      $tot_debito_5bin  = $debito_5bin;
      $tot_debito_6bin  = $debito_6bin;
      $tot_credito_1bin = $credito_1bin;
      $tot_credito_2bin = $credito_2bin;
      $tot_credito_3bin = $credito_3bin;
      $tot_credito_4bin = $credito_4bin;
      $tot_credito_5bin = $credito_5bin;
      $tot_credito_6bin = $credito_6bin;

      $tot_saldo_final      = $saldo_final;


      if($acumula_reduzido==true){
        $key = array_search("$estrut_mae",$work_planomae);
      }else{
        $key = false;
      }
      if ($key === false ) {  // não achou
        $work_planomae[$seq]= $estrut_mae;
        $work_planoestrut[$seq]= $estrut;
        $work_plano[$seq] =  array(0=>"$c61_reduz",
                                   1=>"$c61_codcon",
                                   2=>"$c61_codigo",
                                   3=>"$c60_descr",
                                   4=>"$c60_finali",
                                   5=>"$c61_instit",
                                   6=>"$saldo_anterior",
                                   7=>"$debito_1bin",
                                   8=>"$credito_1bin",
                                   9=>"$debito_2bin",
                                   10=>"$credito_2bin",
                                   11=>"$debito_3bin",
                                   12=>"$credito_3bin",
                                   13=>"$debito_4bin",
                                   14=>"$credito_4bin",
                                   15=>"$debito_5bin",
                                   16=>"$credito_5bin",
                                   17=>"$debito_6bin",
                                   18=>"$credito_6bin",
                                   19=>"$saldo_final",
                                   20=>"$sinal_anterior",
                                   21=>"$sinal_final");
        $seq = $seq+1;
      }else{
        $work_plano[$key][6] += $tot_anterior;
        $work_plano[$key][7] += $tot_debito_1bin;
        $work_plano[$key][8] += $tot_credito_1bin;
        $work_plano[$key][9] += $tot_debito_2bin;
        $work_plano[$key][10] += $tot_credito_2bin;
        $work_plano[$key][11] += $tot_debito_3bin;
        $work_plano[$key][12] += $tot_credito_3bin;
        $work_plano[$key][13] += $tot_debito_4bin;
        $work_plano[$key][14] += $tot_credito_4bin;
        $work_plano[$key][15] += $tot_debito_5bin;
        $work_plano[$key][16] += $tot_credito_5bin;
        $work_plano[$key][17] += $tot_debito_6bin;
        $work_plano[$key][18] += $tot_credito_6bin;
        $work_plano[$key][19] += $tot_saldo_final;
      }
      $estrutural = $estrut;
      for($ii = 1;$ii < 10;$ii++){
        $estrutural = db_le_mae_conplano($estrutural);
        $nivel = db_le_mae_conplano($estrutural,true);

        $key = array_search("$estrutural",$work_planomae);
        if ($key === false ) {  // não achou
          // busca no banco e inclui
          $res = db_query("select c60_descr,c60_finali,c60_codcon from conplano where c60_anousu=".$anousu." and c60_estrut = '$estrutural'");
          if($res == false || pg_numrows($res) == 0){
            db_redireciona("db_erros.php?fechar=true&db_erro=Está faltando cadastrar esse estrutural na contabilidade. Nível : $nivel  Estrutural : $estrutural");
            exit;
          }
          db_fieldsmemory($res,0);

          $work_planomae[$seq]= $estrutural;
          $work_planoestrut[$seq]= '';
          $work_plano[$seq] =(array(0=> 0,
                                    1=> 0,
                                    2=>$c60_codcon,
                                    3=>$c60_descr,
                                    4=>$c60_finali,
                                    5=> 0 ,
                                    6=>$saldo_anterior,
                                    7=>$debito_1bin,
                                    8=>$credito_1bin,
                                    9=>$debito_2bin,
                                    10=>$credito_2bin,
                                    11=>$debito_3bin,
                                    12=>$credito_3bin,
                                    13=>$debito_4bin,
                                    14=>$credito_4bin,
                                    15=>$debito_5bin,
                                    16=>$credito_5bin,
                                    17=>$debito_6bin,
                                    18=>$credito_6bin,
                                    19=>$saldo_final,
                                    20=>$sinal_anterior,
                                    21=>$sinal_final));



          $seq ++;
        }else{

          $work_plano[$key][6] += $tot_anterior;
          $work_plano[$key][7] += $tot_debito_1bin;
          $work_plano[$key][8] += $tot_credito_1bin;
          $work_plano[$key][9] += $tot_debito_2bin;
          $work_plano[$key][10] += $tot_credito_2bin;
          $work_plano[$key][11] += $tot_debito_3bin;
          $work_plano[$key][12] += $tot_credito_3bin;
          $work_plano[$key][13] += $tot_debito_4bin;
          $work_plano[$key][14] += $tot_credito_4bin;
          $work_plano[$key][15] += $tot_debito_5bin;
          $work_plano[$key][16] += $tot_credito_5bin;
          $work_plano[$key][17] += $tot_debito_6bin;
          $work_plano[$key][18] += $tot_credito_6bin;
          $work_plano[$key][19] += $tot_saldo_final;

        }
        if($nivel == 1)	break;
      }
    }
    for ($i=0;$i<sizeof($work_planomae);$i++){
      $mae        = $work_planomae[$i];
      $estrut     = $work_planoestrut[$i];
      $c61_reduz  = $work_plano[$i][0];
      $c61_codcon = $work_plano[$i][1];
      $c61_codigo = $work_plano[$i][2];
      $c60_descr  = $work_plano[$i][3];
      $c60_finali = $work_plano[$i][4];
      $c61_instit     = $work_plano[$i][5];
      $saldo_anterior = $work_plano[$i][6];

      $debito_1bin  = $work_plano[$i][7];
      $credito_1bin = $work_plano[$i][8];

      $debito_2bin  = $work_plano[$i][9];
      $credito_2bin = $work_plano[$i][10];

      $debito_3bin  = $work_plano[$i][11];
      $credito_3bin = $work_plano[$i][12];

      $debito_4bin  = $work_plano[$i][13];
      $credito_4bin = $work_plano[$i][14];

      $debito_5bin  = $work_plano[$i][15];
      $credito_5bin = $work_plano[$i][16];

      $debito_6bin  = $work_plano[$i][17];
      $credito_6bin = $work_plano[$i][18];

      $saldo_final     = $work_plano[$i][19];
      $sinal_anterior  = $work_plano[$i][20];
      $sinal_final     = $work_plano[$i][21];

      $sql = "insert into work_pl
      values ('$mae',
      '$estrut',
      $c61_reduz,
      $c61_codcon,
      $c61_codigo,
      '".addslashes($c60_descr)."',
      '".addslashes($c60_finali)."',
      $c61_instit,
      $saldo_anterior,
      $debito_1bin,
      $credito_1bin,
      $debito_2bin,
      $credito_2bin,
      $debito_3bin,
      $credito_3bin,
      $debito_4bin,
      $credito_4bin,
      $debito_5bin,
      $credito_5bin,
      $debito_6bin,
      $credito_6bin,
      $saldo_final,
      '$sinal_anterior',
      '$sinal_final')";

      db_query($sql);

    }

    $sql = "select
    case when c61_reduz = 0 then
    estrut_mae
    else
    estrut
    end as estrutural,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    c60_descr,
    c60_finali,
    c61_instit,
    saldo_anterior as saldo_anterior,
    debito_1bin as debito_1bin,
    credito_1bin as credito_1bin,
    debito_2bin as debito_2bin,
    credito_2bin as credito_2bin,
    debito_3bin as debito_3bin,
    credito_3bin as credito_3bin,
    debito_4bin as debito_4bin,
    credito_4bin as credito_4bin,
    debito_5bin as debito_5bin,
    credito_5bin as credito_5bin,
    debito_6bin as debito_6bin,
    credito_6bin as credito_6bin,
    saldo_final as saldo_final,
    case when saldo_anterior < 0 then  'C'
    when saldo_anterior > 0 then 'D'
    else ' '
    end as  sinal_anterior,
    case when saldo_final < 0 then 'C'
    when saldo_final > 0 then 'D'
    else ' '
    end as  sinal_final
    from work_pl
    order by estrut_mae,estrut";

    if($retsql == false){
      $result_final = db_query($sql);
      // db_criatabela($result_final); exit;
      return $result_final;
    }else{
      return $sql;
    }
  }


  function processa($instit=1,$data_ini="",$data_fim="",$tribinst,$subelemento="") {
    global $instituicoes,$contador,$nomeinst,$sinal_anterior,$sinal_final;
    global $debito_1bin,$debito_2bin,$debito_3bin,$debito_4bin,$debito_5bin,$debito_6bin;
    global $credito_1bin,$credito_2bin,$credito_3bin,$credito_4bin,$credito_5bin,$credito_6bin;

    $teste_debito =0;
    $teste_credito=0;

    $where = " c61_instit in ($instit)";

    $anousu   = (db_getsession("DB_anousu")-1);
    $data_ini = $anousu.'-01-01';
    $data_fim = $anousu.'-12-31';
    $nomeArq = 'BVMOVANT.TXT';

    /*
     * verifica se ja existe arquivo no banco
     */
    $oDaoArquivosPad  = db_utils::getDao("conarquivospad");
    $rsDaoArquivosPad = $oDaoArquivosPad->sql_record($oDaoArquivosPad->sql_query(null,
                                                                                 "c54_codarq, c54_anousu, c54_nomearq, c54_arquivo",
                                                                                 "",
                                                                                 "c54_anousu      =  {$anousu}
                                                                                     and c54_nomearq = '{$nomeArq}'"
    ));

    if ($oDaoArquivosPad->numrows > 0 ){

      $oArquivo  = db_utils::fieldsMemory($rsDaoArquivosPad,0);
      $sArquivo   =  $oArquivo->c54_arquivo;

      fputs($this->arq, str_replace("\n\r", "", $sArquivo));
      fputs($this->arq,"\r\n");

      $contador = count(explode("\n",$sArquivo));

    } else {


      $result = $this->db_planocontassaldo_matriz_mes($anousu,$data_ini,$data_fim,false,$where,'',false,'true');

      $contador=0;

      $array_teste = array();
      for($x = 0; $x < pg_numrows($result);$x++){
        global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr;
        db_fieldsmemory($result,$x);

        if ($x == 3496) {
          //db_fieldsmemory($result,$x,true,true);exit;
        }

        $line  = formatar($estrutural,20,'n');
        if($c61_instit == 0 || empty($c61_instit))
          $line .= "0000";
        else
          $line .= $instituicoes[$c61_instit];    // aqui é o codtrib, da tabela db_config

        if ($debito_1bin >=0 ) {
          if ($debito_1bin == 7600000) {
            $line .= formatar(7600000,13,'v');
          } else {
            $line .= formatar($debito_1bin,13,'v');
          }
        } else {
          $line .= "-" .formatar(abs($debito_1bin),12,'v');
        }

        if ($credito_1bin >= 0) {
          if ($credito_1bin == 96100000) {
            $line .= formatar(96100000,13,'v');
          } else {
            if ($credito_1bin == 7600000) {
              $line .= formatar(7600000,13,'v');
            } else {
              $line .= formatar($credito_1bin,13,'v');
            }
          }
        } else	   {
          $line .= "-".formatar(abs($credito_1bin),12,'v');
        }


        if ($debito_2bin >= 0)
          $line .= formatar($debito_2bin,13,'v');
        else
          $line .= "-".formatar(abs($debito_2bin),12,'v');


        if ($credito_2bin>=0)
          $line .= formatar($credito_2bin,13,'v');
        else
          $line .= "-".formatar(abs($credito_2bin),12,'v');

        if ($debito_3bin >= 0)
          $line .= formatar($debito_3bin,13,'v');
        else
          $line .= "-".formatar(abs($debito_3bin),12,'v');


        if ($credito_3bin>=0)
          $line .= formatar($credito_3bin,13,'v');
        else
          $line .= "-".formatar(abs($credito_3bin),12,'v');

        if ($debito_4bin>=0)
          $line .= formatar($debito_4bin,13,'v');
        else
          $line .= "-".formatar(abs($debito_4bin),12,'v');

        if ($credito_4bin>=0)
          $line .= formatar($credito_4bin,13,'v');
        else
          $line .= "-".formatar(abs($credito_4bin),12,'v');

        if ($debito_5bin>=0)
          $line .= formatar($debito_5bin,13,'v');
        else
          $line .= "-".formatar(abs($debito_5bin),12,'v');

        if ($credito_5bin>=0)
          $line .= formatar($credito_5bin,13,'v');
        else
          $line .= "-".formatar(abs($credito_5bin),12,'v');

        if ($debito_6bin>=0) {
          if ($debito_6bin == 96100000) {
            $line .= formatar(96100000,13,'v');
          } else {
            if ($debito_6bin == 7600000) {
              $line .= formatar(7600000,13,'v');
            } else {
              $line .= formatar($debito_6bin,13,'v');
            }
          }
        } else {
          $line .= "-".formatar(abs($debito_6bin),12,'v');
        }

        if ($credito_6bin>=0) {
          if ($credito_6bin == 7600000) {
            $line .= formatar(7600000,13,'v');
          } else {
            $line .= formatar($credito_6bin,13,'v');
          }
        } else {
          $line .= "-".formatar(abs($credito_6bin),12,'v');
        }

        $contador ++;

        fputs($this->arq,$line);
        fputs($this->arq,"\r\n");

      }

    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";

    @db_query("drop table work_pl");

    return $teste;
  }
}