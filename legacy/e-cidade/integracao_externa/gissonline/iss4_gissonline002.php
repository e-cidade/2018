<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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


if (! session_id()) {
  session_start();
}

/*
 * Chamamos os arquivos com os métodos necessários para execução do script
*/
require_once ("lib/dbportal.constants.php");
require_once ("lib/db_conecta.php");
require_once ("../../model/configuracao/TraceLog.model.php");
require_once (DB_LIBS . "std/label/rotulo.php");
require_once (DB_LIBS . "std/label/RotuloDB.php");
require_once (DB_LIBS . "libs/db_stdlib.php");
require_once (DB_LIBS . "libs/db_utils.php");

$dtDataHoje         = date("Y-m-d");
$iAnoUsu            = date("Y");
$iMomentoIntegracao = time();

/**
 * Argumento que define se devem ser processados todos ou apenas os alterados.
 * Caso $argv[1] nao tenha sido setado, recebe 1 por padrao (processar apenas os alterados)
 */
if (!isset($argv[1])) {
  $argv[1] = 1;
}

if (isset($mostrahtml) and $mostrahtml == true) {

  if ($botao == 1) {
    //processar os alteradas.
    $processartodos = false;
  } else {
    //processar todos.
    $processartodos = true;
  }
} else {

  //
  //  Parametro informando se processa todos ou nao
  //
  // argv[1] == 1 -- nao processa todos registros
  // argv[1] == 2 -- processa todos registros
  //
  if ($argv [1] == '1') {
    //processar os alteradas.
    $processartodos = false;
  } else {
    //processar todos
    $processartodos = true;
  }
}

//################## log de inicio ####################\

//
// Desabilita tempo maximo de execucao
//
set_time_limit(0);

// Hora de Inicio do Script
$sHoraInicio = date("H:i:s");
// Timestamp para data/Hora
$sTimeStampInicio = date("Ymd_His");
// Verifica se nao foi setado o nome do script
if (! isset($sNomeScript)) {
  $sNomeScript = basename(__FILE__);
}

// Seta nome do arquivo de Log, caso ja nao exista
if (! defined("DB_ARQUIVO_LOG")) {
  if ($processartodos == true) {
    $sArquivoLog = DB_LOGDIR . "tmp/PROCESSAR_GISSONLINE_" . $sTimeStampInicio . ".log";
  } else {
    $sArquivoLog = DB_LOGDIR . "tmp/ATUALIZACAO_GISSONLINE_" . $sTimeStampInicio . ".log";
  }

  define("DB_ARQUIVO_LOG", $sArquivoLog);
}

// Logs...
db_log("", $sArquivoLog, 2);
db_log("*** INICIO Script " . $sNomeScript . " ***", $sArquivoLog, 2);
db_log("", $sArquivoLog, 2);
db_log("Arquivo de Log: $sArquivoLog", $sArquivoLog, 2);
db_log("", $sArquivoLog, 2);

include (DB_DBFORMS . "dbforms/db_funcoes.php");

if (isset($mostrahtml) and $mostrahtml == true) {
  require ("libs/db_conecta.php");
  include ("libs/db_sessoes.php");
} else {
  //
  //  Variaveis de conexao com base de dados guaiba
  //
  $sqlSessao  = "SELECT fc_startsession()";
  $rsSessao   = db_query($conn, $sqlSessao) or die("Problema com a sessão");
  $sqlinstit  = "select codigo from db_config where prefeitura is true";
  $rsInstit   = db_query($conn, $sqlinstit);
  $instit     = pg_result($rsInstit, 0, 0);
  $sqlPut     = "SELECT fc_putsession('DB_instit','" . $instit ."')";
  $rsPut      = db_query($conn, $sqlPut) or die("Problema com a sessão");
  $sql        = "select nextval('db_logsacessa_codsequen_seq')";
  $result     = db_query($sql) or die($sql);
  $codsequen  = pg_result($result, 0, 0);
}

db_putsession("DB_instit",     $instit);
db_putsession("DB_datausu",    date("Y-m-d"));
db_putsession("DB_id_usuario", 1);
db_putsession("DB_anousu",     date("Y"));
db_putsession("DB_acessado",   $codsequen);

// Validações de parâmetros
if (!isset($ConfigINI["ClienteGiss"]) or empty($ConfigINI["ClienteGiss"])) {
  db_log("Erro: Parâmetro do código do cliente da Giss não setado no arquivo db_config.ini", $sArquivoLog, 0, true, true);

  db_log("Final.: " . date("H:i:s"), $sArquivoLog);
  db_log("\n *** FINAL Script " . $sNomeScript . " *** \n\n", $sArquivoLog);
  exit(1);
}

// Caso nao esteja configurado limite de numpre setado para 8000000 como padrão
if (!isset($ConfigINI["LimiteNumpre"]) or empty($ConfigINI["LimiteNumpre"])) {
  $ConfigINI["LimiteNumpre"] = 8000000;
  db_log("Aviso: Parâmetro do limite do numpre não definido, atribuindo valor padrão de 8000000", $sArquivoLog, 0, true, true);
}

$DB_CLIENTE_GISSONLINE = $ConfigINI["ClienteGiss"];

if (isset($mostrahtml) and $mostrahtml == true) {

  ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php
  echo "<br><center><b>PROCESSAMENTO GERAL</b></center><br>";
  db_criatermometro('termometro2', 'Concluido...', 'blue', 1);
  echo "<br><br><br>";
  echo "<b><center><span id='titulo'></span></center></b><br>";
  db_criatermometro('termometro', 'Concluido...', 'blue', 1);
  db_atutermometro(0, 8, 'termometro2');
}

$vir                  = "";
$whereinscr           = "";
$sWhereTipoSocio      = "";
$inscricoes           = "";
$cancelaProcessamento = false;

/*
 * Se é a opção de processar todos os registros, busca os dados atualizados.
 * Caso NAO encontre algum dado atualizado, altera o valor da variável $cancelaProcessamento para "true" do contrário a
 * variável continua com seu valor padrão "false".
 */

if ($processartodos == false) {

  $datahoje = date("Y-m-d");

  $sqlinscricao = " select q02_inscr
                      from issbaseintegracaoexterna
                           inner join issbase on issbase.q02_inscr = issbaseintegracaoexterna.q135_inscr";

  $resultinscricao = db_query($conn, $sqlinscricao);
  $linhasinscricao = pg_num_rows($resultinscricao);
  if ($linhasinscricao > 0) {

    for($procalt = 0; $procalt < $linhasinscricao; $procalt ++) {

      db_fieldsmemory($resultinscricao, $procalt);
      $inscricoes .= $vir . $q02_inscr;
      $vir = ",";
    }
    $whereinscr = "where issbase.q02_inscr in($inscricoes)";
  } else {

    if (isset($mostrahtml) and $mostrahtml == true) {

      db_msgbox("Nenhum registro incluido ou alterado para esta data.");
      db_log("Nenhum registro incluido ou alterado para esta data. ", $sArquivoLog, 2);
      db_log("", $sArquivoLog, 2);
      db_log("Final.: " . date("H:i:s"), $sArquivoLog, 2);
      db_log("", $sArquivoLog, 2);
      db_log("*** FINAL Script " . $sNomeScript . " *** \n\n", $sArquivoLog, 2);
      echo "<script>parent.db_iframe_relatorio.hide(); </script>";
      $cancelaProcessamento = true; //exit;
    } else {

      db_log("Nenhum registro incluido ou alterado para esta data. ", $sArquivoLog);
      db_log("", $sArquivoLog);
      db_log("Final.: " . date("H:i:s"), $sArquivoLog);
      db_log("", $sArquivoLog);
      db_log("*** FINAL Script " . $sNomeScript . " *** \n\n", $sArquivoLog);
      $cancelaProcessamento = true; //exit;
    }
  }
}

db_query($conn, 'BEGIN');
db_query($conn2, 'BEGIN');

if ($cancelaProcessamento == false) {

  $inscricaodupla = "";
  $vir            = "";

  // busca dados
  $sqlissbase = "select distinct on(issbase.q02_inscr)
                        case when db_cgmcgc.z01_cgc is not null
                             then 'J' else
                             case when db_cgmcpf.z01_cpf is not null
                                  then 'F' else
                                  null
                             end
                        end as tipo_empresa,
                        case when db_cgmcgc.z01_cgc is not null
                             then z01_cgc else
                             case when db_cgmcpf.z01_cpf is not null
                                   then z01_cpf else
                                   null
                             end
                        end as cpf_cnpj,
                        case when issbase.q02_dtbaix is null
                             then 'A'
                             else 'E'
                        end as status_empresa,
                        issbase.q02_inscr  as num_cadastro,
                        issbase.q02_inscmu as inscricao,
                        issbase.q02_dtinic as data_abertura,
                        issbase.q02_dtbaix as data_encerramento,
                        cgm.z01_numcgm,
                        cgm.z01_telef      as telefone,
                        cgm.z01_incest     as inscricao_estadual,
                        cgm.z01_nome       as nome_empresa,
                        cgm.z01_fax        as fax,
                        cgm.z01_email      as email,
                        cgm.z01_nomefanta  as nome_fantasia,
                        q14_proces        as num_processo,
                        j88_sigla          as tipo_logradouro,
                        case when issruas.q02_inscr is null then cgm.z01_ender    else ruas.j14_nome      end as logradouro,
                        case when issruas.q02_inscr is null then cgm.z01_numero   else issruas.q02_numero end as num_imovel,
                        case when issruas.q02_inscr is null then cgm.z01_compl    else issruas.q02_compl  end  as complemento,
                        case when issbairro.q13_inscr is null then cgm.z01_bairro else bairro.j13_descr   end as bairro,
                        case when issruas.q02_inscr is null then cgm.z01_cep      else issruas.z01_cep    end as cep,
                        case when issruas.q02_inscr is null then cgm.z01_munic else ( select munic from db_config where prefeitura is true limit 1 ) end as cidade,
                        case when issruas.q02_inscr is null then cgm.z01_uf    else ( select uf from db_config where prefeitura is true limit 1 ) end as estado,
                        q30_area           as area
                   from issbase
                        inner join cgm         on issbase.q02_numcgm    = cgm.z01_numcgm
                        left  join issprocesso on issprocesso.q14_inscr = issbase.q02_inscr
                        left  join db_cgmcgc   on db_cgmcgc.z01_numcgm  = cgm.z01_numcgm
                        left  join db_cgmcpf   on db_cgmcpf.z01_numcgm  = cgm.z01_numcgm
                        left  join issruas     on issruas.q02_inscr     = issbase.q02_inscr
                        left  join ruas        on issruas.j14_codigo    = ruas.j14_codigo
                        left  join ruastipo    on ruastipo.j88_codigo   = ruas.j14_codigo
                        left  join issbairro   on issbairro.q13_inscr   = issbase.q02_inscr
                        left  join bairro      on bairro.j13_codi       = issbairro.q13_bairro
                        left  join issquant    on issquant.q30_inscr    = issbase.q02_inscr
                  $whereinscr ";

  $resultissbase = db_query($conn, $sqlissbase);
  $linhasissbase = pg_num_rows($resultissbase);
  if (isset($mostrahtml) and $mostrahtml == true) {

    db_atutermometro(1, 8, 'termometro2');
    echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO EMPRESAS'; </script>";
    db_log("PROCESSANDO EMPRESAS  total = $linhasissbase ", $sArquivoLog, 2);
  } else {

    db_log("PROCESSANDO EMPRESAS  total = $linhasissbase ", $sArquivoLog);
    db_log("", $sArquivoLog);
  }

  if ($linhasissbase > 0) {

    for($is = 0; $is < $linhasissbase; $is ++) {

      if (isset($mostrahtml) and $mostrahtml == true) {
        db_atutermometro($is, $linhasissbase, 'termometro');
      } else {

        $nPercentual = round((($is + 1) / $linhasissbase) * 100, 2);
        db_log(" ".($is+1)." de {$linhasissbase} .....  \rProcessando {$nPercentual}%...", null, 1, false, false);
      }
      db_fieldsmemory($resultissbase, $is);

      //verifica se ja processou hoje...
      $sqlverifica    = " select * from tb_inter_empresas where cod_cliente = $DB_CLIENTE_GISSONLINE and num_cadastro=$num_cadastro and timestamp = to_timestamp({$iMomentoIntegracao})";
      $resultverifica = db_query($conn2, $sqlverifica);
      $linhasverifica = pg_num_rows($resultverifica);
      if ($linhasverifica > 0) {
        continue;
      }

      /**
       *  Regime da Empresa
       *
       * F - Caso seja ISSQN FIXO
       * A - Caso seja ISSQN VARIAVEL
       * T - Caso Seja ISSQN VARIAVEL FIXADO
       * N - Caso não se aplique a nenhum dos casos anteriores ou Seja optante pelo simples nacional
       *
       */
      $sRegimeEmpresa = "N";

      $sSqlRegime  = " select q01_cadcal, 													                             ";
      $sSqlRegime .= "        q38_categoria  as isscadsimples, 								                   ";
      $sSqlRegime .= "        q39_sequencial as isscadsimplesbaixa, 							               ";
      $sSqlRegime .= "        q33_codigo     as varfix, 										                     ";
      $sSqlRegime .= "        q34_codigo     as varfixval,                                       ";
      $sSqlRegime .= "        q34_ano        as ano_valorfixado                                  ";
      $sSqlRegime .= "   from isscalc  														                               ";
      $sSqlRegime .= "        left join isscadsimples      on q38_inscr         = q01_inscr      ";
      $sSqlRegime .= "        left join isscadsimplesbaixa on q39_isscadsimples = q38_sequencial ";
      $sSqlRegime .= "        left join varfix             on q33_inscr         = q01_inscr 	   ";
      $sSqlRegime .= "        left join varfixval          on q33_codigo        = q34_codigo     ";
      $sSqlRegime .= "  where q01_anousu = extract(year from current_date)  					           ";
      $sSqlRegime .= "    and q01_inscr = {$num_cadastro}						                             ";

      $rsRegimeEmpresa = db_query($conn, $sSqlRegime);

      if ( pg_num_rows($rsRegimeEmpresa) > 0) {

      	$oRegimeEmpresa = db_utils::fieldsMemory($rsRegimeEmpresa, 0);

      	if ( $oRegimeEmpresa->q01_cadcal == 2 ) {

      		$sRegimeEmpresa = 'F';

      	} else if ( $oRegimeEmpresa->q01_cadcal == 3 ) {

      		$sRegimeEmpresa = 'A';

      		if ( $iAnoUsu ==  $oRegimeEmpresa->ano_valorfixado)  {
      			$sRegimeEmpresa = 'T';
      		}

      	}

      }

      $sSqlTipcalc  = " select count(*) as quant ";
      $sSqlTipcalc .= " from tabativ ";
      $sSqlTipcalc .= " inner join ativid on ativid.q03_ativ = tabativ.q07_ativ ";
      $sSqlTipcalc .= " inner join ativtipo on ativtipo.q80_ativ = ativid.q03_ativ ";
      $sSqlTipcalc .= " inner join tipcalc on tipcalc.q81_codigo = ativtipo.q80_tipcal and tipcalc.q81_tipo = 1 ";
      $sSqlTipcalc .= " inner join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc and cadcalc.q85_var is true ";
      $sSqlTipcalc .= " where (     tabativ.q07_databx is null ";
      $sSqlTipcalc .= "         and (  q07_datafi is null or q07_datafi >= '{$dtDataHoje}' ) ";
      $sSqlTipcalc .= "       ) and tabativ.q07_inscr = {$num_cadastro} ";
      $rsTipcalc   = db_query($conn, $sSqlTipcalc) or die($sSqlTipcalc);
      $oTipCalc    = db_utils::fieldsMemory($rsTipcalc, 0);

      if ( $oTipCalc->quant > 0 ) {
      $sRegimeEmpresa = 'A';
      }


      if ($inscricao == "" || $inscricao == 0) {
        $inscricao = $num_cadastro;
      }


      if ( $linhasverifica == 0 ) {

        $nome_empresa    = pg_escape_string($nome_empresa   );
        $nome_fantasia   = pg_escape_string($nome_fantasia  );
        $num_processo    = pg_escape_string($num_processo   );
        $tipo_empresa    = pg_escape_string($tipo_empresa   );
        $tipo_logradouro = pg_escape_string($tipo_logradouro);
        $logradouro      = pg_escape_string($logradouro     );
        $num_imovel      = pg_escape_string($num_imovel     );
        $complemento     = pg_escape_string($complemento    );
        $bairro          = pg_escape_string($bairro         );
        $cidade          = pg_escape_string($cidade         );
        $estado          = pg_escape_string($estado         );
        $telefone        = pg_escape_string($telefone       );
        $email           = pg_escape_string($email          );
        $regime_empresa  = pg_escape_string($sRegimeEmpresa );
        $status_empresa  = pg_escape_string($status_empresa );

        $inscricao_estadual = dbValidaNumero($inscricao_estadual);
        $fax                = dbValidaNumero($fax);


        $incluiempresa = "
          INSERT INTO tb_inter_empresas (
              cod_cliente ,
              num_cadastro ,
              timestamp  ,
              inscricao ,
              inscricao_estadual ,
              nome_empresa ,
              nome_fantasia ,
              num_processo ,
              tipo_empresa ,
              cpf_cnpj,
              data_abertura ,
              data_encerramento ,
              tipo_logradouro ,
              titulo_logradouro ,
              logradouro,
              num_imovel ,
              complemento ,
              bairro ,
              cep ,
              cidade ,
              estado ,
              ddd ,
              telefone  ,
              ramal ,
              fax,
              email ,
              regime_empresa  ,
              classificacao  ,
              area_total    ,
              area_ocupada    ,
              status_empresa  ,
              controle )
                VALUES
                ( $DB_CLIENTE_GISSONLINE  ,
                  $num_cadastro ,
                  to_timestamp($iMomentoIntegracao)  ,
                  " . dbValida($inscricao, 'int') . ",
                  " . dbValida($inscricao_estadual, 'int') . ",
                  " . dbValida($nome_empresa, 'string') . ",
                  " . dbValida($nome_fantasia, 'string') . ",
                  " . dbValida($num_processo, 'string') . ",
                  " . dbValida($tipo_empresa, 'string') . ",
                  " . dbValida($cpf_cnpj, 'int') . ",
                  " . dbValida($data_abertura, 'date') . ",
                  " . dbValida($data_encerramento, 'date') . ",
                  " . dbValida($tipo_logradouro, 'string') . ",
                  null,
                  " . dbValida($logradouro, 'string') . ",
                  " . dbValida($num_imovel, 'string') . ",
                  " . dbValida(substr($complemento,0,30), 'string') . ",
                  " . dbValida($bairro, 'string') . ",
                  " . dbValida($cep, 'int') . ",
                  " . dbValida($cidade, 'string') . ",
                  " . dbValida($estado, 'string') . ",
                  null,
                  " . dbValida($telefone, 'string') . ",
                  null ,
                  " . dbValida($fax, 'int') . ",
                  " . dbValida($email, 'string') . ",
                  " . dbValida($regime_empresa, 'string') . ",
                  null  ,
                  " . dbValida($area, 'int') . ",
                  " . dbValida($area, 'int') . ",
                  " . dbValida($status_empresa, 'string') . ",
                  null)";
        // db_log("inset 1 = $incluiempresa", null, 1, true,true);
        //die($incluiempresa);
        $resultinclui = @db_query($conn2, $incluiempresa);
        if ($resultinclui == false) {
          db_log("Erro: \n sql = $incluiempresa", null, 1);
          db_query($conn, 'rollback');
          db_query($conn2, 'rollback');
          exit();
        } else {
          if ($processartodos == false) {
            db_log("Atualizada inscrição : {$num_cadastro}", $sArquivoLog, 2);
          } else {
            db_log("Incluida inscrição : {$num_cadastro}", $sArquivoLog, 2);
          }

        }
      }

      $sqlativ = "
        select q07_ativ as cod_atividade,
               q07_inscr,
               q88_inscr,
               q07_seq,
               q88_seq ,
               q07_datain,
               q07_databx,
               q07_datafi,
               case when q07_inscr = q88_inscr and q07_seq = q88_seq then '1'
               else 'null'
                 end as ativ_principal,
                     case when q07_datain is null then q02_dtinic
               else q07_datain
                 end as data_inicio,
                     case when q07_databx is null then q07_datafi
               else q07_databx
                 end as data_fim,
                     case when q07_datain is null then extract(year from q02_dtinic)
               else extract(year from q07_datain)
                 end as exercicio,
                     (select count(*) from tabativ t2 where t2.q07_inscr = tabativ.q07_inscr and t2.q07_ativ = tabativ.q07_ativ) as quant_ativ
                       from issbase
                       inner join tabativ  on q07_inscr = q02_inscr
                       left join ativprinc on q88_inscr = q07_inscr
                       and q88_seq   = q07_seq
                       where q02_inscr = $num_cadastro
                       order by q07_inscr,cod_atividade,ativ_principal;";

      $resultativ = db_query($conn, $sqlativ);
      $linhasativ = pg_num_rows($resultativ);
      $aux_atividade = "";
      if ($linhasativ > 0) {

        for($at = 0; $at < $linhasativ; $at ++) {

          db_fieldsmemory($resultativ, $at);
          $deveincluir = false;
          if ($quant_ativ > 1) {

            if ($aux_atividade != $cod_atividade) {

              //inclui
              $aux_atividade = $cod_atividade;
              $deveincluir = true;
            } else {
              $inscricaodupla .= $vir . $q07_inscr;
              $vir = ",";
            }
          } else {
            //inclui
            $deveincluir = true;
          }

          if ($deveincluir == true) {

            $incluiativ = "
              INSERT INTO tb_inter_atividades (
                  cod_atividade ,
                  cod_cliente ,
                  num_cadastro ,
                  timestamp ,
                  ativ_principal ,
                  data_inicio ,
                  data_fim ,
                  exercicio ,
                  controle )
              VALUES
              ($cod_atividade,
               $DB_CLIENTE_GISSONLINE ,
               $q07_inscr,
               to_timestamp($iMomentoIntegracao),
               $ativ_principal,
               '$data_inicio',
               " . dbValida($data_fim, 'date') . ",
               " . dbValida($exercicio, 'int') . ",
               null
              )";

            $rsativ = @db_query($conn2, $incluiativ);
            if ($rsativ == false) {
              db_log("Erro: \n sql = $incluiativ", null, 1, true, true);
              db_query($conn, 'rollback');
              db_query($conn2, 'rollback');
              exit();
            }
          }
        }
      }

    }
  }

  if ($inscricaodupla != "") {

    db_log("", $sArquivoLog, 0);
    db_log("Inscrições com atividades duplas = $inscricaodupla", $sArquivoLog, 0);
    db_log("", $sArquivoLog, 0);
  }
  db_log("", $sArquivoLog, 2);

  if ($processartodos == true) {
    //
    // divida
    //
    $sqldivida  = "  select distinct q02_inscr, divida.*, certid.v13_certid                    ";
    $sqldivida .= "    from divida                                                             ";
    $sqldivida .= "         inner join arreinscr on arreinscr.k00_numpre = v01_numpre          ";
    $sqldivida .= "         inner join issbase   on issbase.q02_inscr    = arreinscr.k00_inscr ";
    $sqldivida .= "         inner join certdiv   on certdiv.v14_coddiv   = divida.v01_coddiv   ";
    $sqldivida .= "         inner join certid    on certid.v13_certid    = certdiv.v14_certid  ";

    $resultdivida = db_query($conn, $sqldivida);
    $linhasdivida = pg_num_rows($resultdivida);

    if ($linhasdivida > 0) {

      if (isset($mostrahtml) and $mostrahtml == true) {
        db_atutermometro(3, 8, 'termometro2');
        echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO DIVIDA'; </script>";
      } else {
        db_log("PROCESSANDO DIVIDA total = $linhasdivida ", $sArquivoLog, 0);
        db_log("", $sArquivoLog);
      }

      for($dv = 0; $dv < $linhasdivida; $dv ++) {

        db_fieldsmemory($resultdivida, $dv);
        if (isset($mostrahtml) and $mostrahtml == true) {
          db_atutermometro($dv, $linhasdivida, 'termometro');
        } else {

          $nPercentual = round((($dv + 1) / $linhasdivida) * 100, 2);
          db_log(($dv+1)." de {$linhasdivida} \rProcessando {$nPercentual}%...", null, 1, false, false);
        }

        $sqlfund  = " select v03_codigo,                                            ";
        $sqlfund .= "        db02_descr,                                            ";
        $sqlfund .= "        db02_texto                                             ";
        $sqlfund .= "   from proced                                                 ";
        $sqlfund .= "        inner join procedparag on v80_proced = v03_codigo      ";
        $sqlfund .= "        inner join db_documento on db03_docum = v80_docum      ";
        $sqlfund .= "        inner join db_docparag on db04_docum = db03_docum      ";
        $sqlfund .= "        inner join db_paragrafo on db02_idparag = db04_idparag ";
        $sqlfund .= "  where v03_codigo =  {$v01_proced}                            ";
        $sqlfund .= "  order by db04_ordem                                          ";

        $resultfund = db_query($conn, $sqlfund);
        $linhasfund = pg_num_rows($resultfund);

        if ($linhasfund > 0) {
          db_fieldsmemory($resultfund, 0);
        }
        $v13_certid = str_pad($v13_certid, 8, "0", STR_PAD_LEFT);
        $v01_coddiv = str_pad($v01_coddiv, 8, "0", STR_PAD_LEFT);
        $num_documento = $v13_certid . $v01_coddiv;

        //
        // deletar e inserir denovo se controle estiver null
        //
        $sSqlVerificaCDA  = " select * from tb_inter_boletos_cdas               ";
        $sSqlVerificaCDA .= "  where cod_cliente     = {$DB_CLIENTE_GISSONLINE} ";
        $sSqlVerificaCDA .= "    and num_cadastro    = {$q02_inscr}             ";
        $sSqlVerificaCDA .= "    and num_documento   = {$num_documento}         ";
        $sSqlVerificaCDA .= "    and mes_competencia = {$v01_numpar}            ";
        $sSqlVerificaCDA .= "    and ano_competencia = {$v01_exerc}             ";
        $rsVerificaCDA    = db_query($conn2,$sSqlVerificaCDA) or die($sSqlVerificaCDA);

        $iNumRowsVerificaCDA = pg_num_rows($rsVerificaCDA);
        if ( $iNumRowsVerificaCDA > 0 ) {
        	$oVerificaCDA = db_utils::fieldsMemory($rsVerificaCDA,0);
        }else{
        	unset($oVerificaCDA);
        }

        if ( $iNumRowsVerificaCDA > 0  && $oVerificaCDA->controle == '') {

	        $sSqlDeleteCDA  = " delete from tb_inter_boletos_cdas ";
	        $sSqlDeleteCDA .= "  where cod_cliente     = {$DB_CLIENTE_GISSONLINE} ";
	        $sSqlDeleteCDA .= "    and num_cadastro    = {$q02_inscr} ";
	        $sSqlDeleteCDA .= "    and num_documento   = {$num_documento}";
	        $sSqlDeleteCDA .= "    and mes_competencia = {$v01_numpar}";
	        $sSqlDeleteCDA .= "    and ano_competencia = {$v01_exerc}";
	        $rsVerificaCDA  = db_query($conn2,$sSqlDeleteCDA) or die($sSqlDeleteCDA) ;
          if ( ! $rsVerificaCDA ) {

            db_log("Erro: \n sql = $sSqlDeleteCDA", null, 1, true, true);
            db_query($conn, 'rollback');
            db_query($conn2, 'rollback');
            exit();
          }
        }

        if ($iNumRowsVerificaCDA == 0 || $oVerificaCDA->controle == '') {

          $db02_texto = pg_escape_string($db02_texto);

	        $incluiboletos_cdas = "
	          INSERT INTO tb_inter_boletos_cdas (
	              cod_cliente ,
	              num_cadastro ,
	              num_documento ,
	              mes_competencia,
	              ano_competencia ,
	              ins_divida ,
	              livro ,
	              folha ,
	              volume ,
	              fundamento ,
	              data_inscricao,
	              controle ,
	              timestamp
	              )
	          VALUES ($DB_CLIENTE_GISSONLINE ,
	              $q02_inscr,
	              $num_documento,
	              $v01_numpar,
	              $v01_exerc,
	              " . dbValida($v01_coddiv, 'int') . ",
	              " . dbValida($v01_livro, 'int') . ",
	              " . dbValida($v01_folha, 'int') . ",
	              0,
	              " . dbValida(@$db02_texto, 'string') . ",
	              " . dbValida($v01_dtinsc, 'date') . ",
	              null,
	              to_timestamp($iMomentoIntegracao)
	              )";
	        $resultboletos_cdas = @db_query($conn2, $incluiboletos_cdas);
	        if ($resultboletos_cdas == false) {
	          db_log("Erro: \n sql = $incluiboletos_cdas", null, 1, true, true);
	          db_query($conn, 'rollback');
	          db_query($conn2, 'rollback');
	          exit();
	        }

        }

      }

    }
  } // do  processar todos

  //COMPONENTE
  $sqlcomp  = "  select q02_inscr,                                  ";
  $sqlcomp .= "         q07_ativ,                                   ";
  $sqlcomp .= "         sum(q07_quant) as soma,                     ";
  $sqlcomp .= "         case                                        ";
  $sqlcomp .= "           when min(tabativ.q07_datain) is null      ";
  $sqlcomp .= "             then q02_dtinic                         ";
  $sqlcomp .= "           else  min(tabativ.q07_datain)             ";
  $sqlcomp .= "         end as dt_inicio,                           ";
  $sqlcomp .= "         case                                        ";
  $sqlcomp .= "           when max(tabativ.q07_databx) is null      ";
  $sqlcomp .= "             then max(tabativ.q07_datafi)            ";
  $sqlcomp .= "           else max(tabativ.q07_databx)              ";
  $sqlcomp .= "         end as dt_fim                               ";
  $sqlcomp .= "    from issbase                                     ";
  $sqlcomp .= "         inner join tabativ on q07_inscr = q02_inscr ";
  $sqlcomp .= "         $whereinscr                                 ";
  $sqlcomp .= "   group by q02_inscr,                               ";
  $sqlcomp .= "            q07_ativ,                                ";
  $sqlcomp .= "            q02_dtinic                               ";

  $resultcomp = db_query($conn, $sqlcomp);
  $linhascomp = pg_num_rows($resultcomp);
  if (isset($mostrahtml) and $mostrahtml == true) {
    db_atutermometro(5, 8, 'termometro2');
    echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO COMPONENTES'; </script>";
  } else {
    db_log("PROCESSANDO COMPONENTES total = $linhascomp ", $sArquivoLog, 0);
    db_log("", $sArquivoLog);
  }

  if ($linhascomp > 0) {
    //echo "<br><br> COMPONENNTES <br>";
    for($c = 0; $c < $linhascomp; $c ++) {

      db_fieldsmemory($resultcomp, $c);

      if (isset($mostrahtml) and $mostrahtml == true) {
        db_atutermometro($c, $linhascomp, 'termometro');
      } else {
        $nPercentual = round((($c + 1) / $linhascomp) * 100, 2);
        db_log(($c+1)." de {$linhascomp} \rProcessando {$nPercentual}%...", null, 1, false, false);
      }

      $sSqlVerificarComponentes  = "select * from tb_inter_qtd_componentes                      ";
      $sSqlVerificarComponentes .= " where cod_cliente     = $DB_CLIENTE_GISSONLINE             ";
      $sSqlVerificarComponentes .= "   and num_cadastro    = $q02_inscr                         ";
      $sSqlVerificarComponentes .= "   and comp_quantidade = $soma                              ";
      $sSqlVerificarComponentes .= "   and cod_atividade   = '$q07_ativ'                        ";
      $sSqlVerificarComponentes .= "   and timestamp       = to_timestamp($iMomentoIntegracao)  ";
      $rsVerificaComp            = db_query($conn2,$sSqlVerificarComponentes);
      if (pg_num_rows($rsVerificaComp) > 0) {
      	// continue;
      	$sSqlDeleteComponentes = " delete from tb_inter_qtd_componentes                          ";
      	$sSqlDeleteComponentes .= "  where cod_cliente     = $DB_CLIENTE_GISSONLINE              ";
	      $sSqlDeleteComponentes .= "    and num_cadastro    = $q02_inscr                          ";
	      $sSqlDeleteComponentes .= "     and comp_quantidade = $soma                              ";
	      $sSqlDeleteComponentes .= "    and cod_atividade   = '$q07_ativ'                         ";
	      $sSqlDeleteComponentes .= "    and timestamp       = to_timestamp($iMomentoIntegracao)   ";
	      $rsDeleteComp           = db_query($conn2,$sSqlDeleteComponentes) or die("\n $sSqlDeleteComponentes \n");
      }

      $incluicompo  = " INSERT INTO tb_inter_qtd_componentes (";
      $incluicompo .= "             cod_cliente  ,";
      $incluicompo .= "             num_cadastro ,";
      $incluicompo .= "             comp_quantidade ,";
      $incluicompo .= "             cod_atividade ,";
      $incluicompo .= "             timestamp ,";
			$incluicompo .= "             dt_inicio ,";
			$incluicompo .= "             dt_fim ,";
			$incluicompo .= "             controle )";
			$incluicompo .= "     VALUES ($DB_CLIENTE_GISSONLINE,";
			$incluicompo .= "             $q02_inscr,";
			$incluicompo .= "             $soma,";
			$incluicompo .= "             $q07_ativ,";
			$incluicompo .= "             to_timestamp($iMomentoIntegracao),";
			$incluicompo .= "             ".dbValida($dt_inicio, 'date').",";
			$incluicompo .= "             ".dbValida($dt_fim, 'date').",";
			$incluicompo .= "             null ) ";

      $resultcompo = @db_query($conn2, $incluicompo);
      if ($resultcompo == false) {
        db_log("Erro: \n sql = $incluicompo", null, 1, true, true);
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit();
      }
    }

  }

  //ESTIMATIVA
  $sqlestim = "
    select q02_inscr,
           extract(year from q34_dtval)||'-'||lpad(q34_mes,2,0)||'-'||extract(day from q34_dtval) as q34_dtval,
           q34_valor as vlr_estimativa,
           extract(year from q34_dtval)||'-'||lpad(q34_mes,2,0)||'-'||extract(day from q34_dtval) as dt_fim
    from issbase
    inner join varfix             on q33_inscr         = q02_inscr
    inner join varfixval          on q33_codigo        = q34_codigo
    $whereinscr ";
  $resultestim = db_query($conn, $sqlestim);
  $linhasestim = pg_num_rows($resultestim);
  if (isset($mostrahtml) and $mostrahtml == true) {
    db_atutermometro(6, 8, 'termometro2');
    echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO ESTIMATIVA'; </script>";
  } else {
    db_log("PROCESSANDO ESTIMATIVA total = $linhasestim ", $sArquivoLog, 0);
    db_log("", $sArquivoLog);
  }
  if ($linhasestim > 0) {
    //echo "<br><br> ESTIMATIVA <br>";
    //db_criatermometro('termometro5', 'Concluido...', 'blue', 1);
    for($e = 0; $e < $linhasestim; $e ++) {
      db_fieldsmemory($resultestim, $e);
      if (isset($mostrahtml) and $mostrahtml == true) {
        db_atutermometro($e, $linhasestim, 'termometro');
      } else {
        $nPercentual = round((($e + 1) / $linhasestim) * 100, 2);
        db_log(($e+1)." de {$linhasestim} \rProcessando {$nPercentual}%...", null, 1, false, false);
      }

      /**
       * Verifica se já existe estimativa cadastrada para o mesmo periodo e estimativa.
       */
      $sSqlVerificaEstimativa     = "select *  ";
      $sSqlVerificaEstimativa    .= "  from tb_inter_estimativa  ";
      $sSqlVerificaEstimativa    .= " where cod_cliente = {$DB_CLIENTE_GISSONLINE}  ";
      $sSqlVerificaEstimativa    .= "   and num_cadastro = {$q02_inscr}  ";
      $sSqlVerificaEstimativa    .= "   and dt_inicio = '{$q34_dtval}'  ";
      $sSqlVerificaEstimativa    .= "   and vlr_estimativa = ".dbValida($vlr_estimativa, 'int');
      $sSqlVerificaEstimativa    .= "   and timestamp = to_timestamp($iMomentoIntegracao) ";
      $rsSqlVerificaEstimativa    = db_query($conn2, $sSqlVerificaEstimativa);
      $iNumRowsVerificaEstimativa = pg_num_rows($rsSqlVerificaEstimativa);
      if ($rsSqlVerificaEstimativa == false) {

        db_log("Erro: \n sql = $sSqlVerificaEstimativa", null, 1, true, true);
        echo pg_last_error();
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit;
      }

      if ($iNumRowsVerificaEstimativa > 0) {

      	/**
      	 * Deleta o registro encontado se já existe estimativa cadastrada para o mesmo periodo e estimativa.
      	 */
	      $sSqlExcluirEstimativa    = "delete  ";
	      $sSqlExcluirEstimativa   .= "  from tb_inter_estimativa  ";
	      $sSqlExcluirEstimativa   .= " where cod_cliente = {$DB_CLIENTE_GISSONLINE}  ";
	      $sSqlExcluirEstimativa   .= "   and num_cadastro = {$q02_inscr}  ";
	      $sSqlExcluirEstimativa   .= "   and dt_inicio = '{$q34_dtval}'  ";
	      $sSqlExcluirEstimativa   .= "   and vlr_estimativa = ".dbValida($vlr_estimativa, 'int');
	      $sSqlExcluirEstimativa   .= "   and timestamp = to_timestamp($iMomentoIntegracao) ";
	      $rsSqlExcluirEstimativa   = db_query($conn2, $sSqlExcluirEstimativa);
	      if ($rsSqlExcluirEstimativa == false) {

	        db_log("Erro: \n sql = $sSqlExcluirEstimativa", null, 1, true, true);
	        echo pg_last_error();
	        db_query($conn, 'rollback');
	        db_query($conn2, 'rollback');
	        exit;
	      }

      }

      $incluirestim = "INSERT INTO tb_inter_estimativa ( cod_cliente ,
                                                         num_cadastro ,
                                                         dt_inicio ,
                                                         vlr_estimativa ,
                                                         dt_fim ,
                                                         timestamp ,
                                                         controle )
                                                VALUES ( $DB_CLIENTE_GISSONLINE,
                                                         $q02_inscr,
                                                         '$q34_dtval',
                                                         " . dbValida($vlr_estimativa, 'int') . ",
                                                         " . dbValida($dt_fim, 'date') . ",
                                                         to_timestamp($iMomentoIntegracao),
                                                         null
                                                       )";

      $resultest = db_query($conn2, $incluirestim);
      if ($resultest == false) {

        db_log("Erro: \n sql = $incluirestim", null, 1, true, true);
        echo pg_last_error();
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit;
      }
    }
  }

  //SOCIOS
  if (trim($whereinscr) == '') {
  	$sWhereTipoSocio = " where q95_tipo = 1 ";
  } else {
    $sWhereTipoSocio = "   and q95_tipo = 1 ";
  }
  $sqlsocios = "
    select distinct * from(
        select q02_inscr,q02_inscmu, cgm.z01_numcgm ,z01_nome,z01_cpf,z01_ender,z01_numero,z01_compl,z01_bairro,z01_cep,z01_munic,z01_uf,z01_telef,z01_fax,z01_email
        from issbaseintegracaoexterna
        inner join issbase   on issbase.q02_inscr = issbaseintegracaoexterna.q135_inscr
        inner join socios    on q02_numcgm = q95_cgmpri
        inner join cgm       on z01_numcgm = q95_numcgm
        inner join db_cgmcpf on cgm.z01_numcgm =db_cgmcpf.z01_numcgm
        $whereinscr $sWhereTipoSocio
        ) as x

    ";
  $resultsocios = db_query($conn, $sqlsocios);
  $linhassocios = pg_num_rows($resultsocios);
  if (isset($mostrahtml) and $mostrahtml == true) {
    db_atutermometro(7, 8, 'termometro2');
    echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO SOCIOS'; </script>";
  } else {
    db_log("PROCESSANDO SOCIOS total = $linhassocios ", $sArquivoLog, 0);
    db_log("", $sArquivoLog);
  }


  if ($linhassocios > 0) {

    for($s = 0; $s < $linhassocios; $s ++) {

      db_fieldsmemory($resultsocios, $s);

      db_query($conn2, "delete from tb_inter_socios where num_cadastro = $q02_inscr");

    }

    for($s = 0; $s < $linhassocios; $s ++) {
      db_fieldsmemory($resultsocios, $s);
      if (isset($mostrahtml) and $mostrahtml == true) {
        db_atutermometro($s, $linhassocios, 'termometro');
      } else {
        $nPercentual = round((($s + 1) / $linhassocios) * 100, 2);
        db_log(($s+1)." de {$linhassocios} \rProcessando {$nPercentual}%...", null, 1, false, false);
      }

      $z01_nome   = pg_escape_string($z01_nome   );
      $z01_ender  = pg_escape_string($z01_ender  );
      $z01_numero = pg_escape_string($z01_numero );
      $z01_compl  = pg_escape_string($z01_compl  );
      $z01_bairro = pg_escape_string($z01_bairro );
      $z01_munic  = pg_escape_string($z01_munic  );
      $z01_uf     = pg_escape_string($z01_uf     );
      $z01_telef  = pg_escape_string($z01_telef  );
      $z01_email  = pg_escape_string($z01_email  );

      $z01_fax = dbValidaNumero($z01_fax);

      $incluisocios = "INSERT INTO tb_inter_socios (cod_cliente,
                                                    num_cadastro,
                                                    timestamp ,
                                                    cpf ,
                                                    inscricao ,
                                                    nome_socio ,
                                                    data_inicio ,
                                                    data_fim ,
                                                    logradouro,
                                                    tipo_logradouro,
                                                    titulo_logradouro ,
                                                    num_imovel ,
                                                    complemento ,
                                                    bairro ,
                                                    cep ,
                                                    cidade ,
                                                    estado ,
                                                    ddd,
                                                    telefone ,
                                                    ramal ,
                                                    fax,
                                                    email,
                                                    controle )
                                            VALUES ($DB_CLIENTE_GISSONLINE,
                                                    $q02_inscr,
                                                    to_timestamp($iMomentoIntegracao),
                                                    $z01_cpf,
                                                    " . dbValida($q02_inscr, 'int') . ",
                                                    " . dbValida($z01_nome, 'string') . ",
                                                    null,
                                                    null,
                                                    " . dbValida($z01_ender, 'string') . ",
                                                    null,
                                                    null,
                                                    " . dbValida($z01_numero, 'string') . ",
                                                    " . dbValida(substr($z01_compl,0,30), 'string') . ",
                                                    " . dbValida($z01_bairro, 'string') . ",
                                                    " . dbValida($z01_cep, 'int') . ",
                                                    " . dbValida($z01_munic, 'string') . ",
                                                    " . dbValida($z01_uf, 'string') . ",
                                                    null,
                                                    " . dbValida($z01_telef, 'string') . ",
                                                    0,
                                                    " . dbValida($z01_fax, 'int') . ",
                                                    " . dbValida($z01_email, 'string') . ",
                                                    null)";

      $resultsoc = @db_query($conn2, $incluisocios) or die("\n $incluisocios \n ".pg_last_error()."\n");
      if ($resultsoc == false) {
        db_log("Erro: \n sql = $incluisocios", null, 1, true, true);
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit();
      }

    }

  }

} // fecha a parte 1

// #########################  PARTE 2  ################################ //
//
// COMEÇA A PARTE DE GERAR ISSVAR COMPLEMENTAR DADOS DA INTER_BOLETOS_GISS
//

include (DB_CLASSES . "classes/db_issvar_classe.php");
include (DB_CLASSES . "classes/db_issvarnotas_classe.php");
include (DB_CLASSES . "classes/db_arreinscr_classe.php");
include (DB_CLASSES . "classes/db_arrecad_classe.php");

$clissvar = new cl_issvar();
$vir = "";
$sqlerro = false;
$inscricaoSemIssbase = "";

/*
 *
 * Busca os registros da tabela tb_inter_boletos_giss da base de dados do GissOnline
 *
 */
$sqlBuscaBoleto  = " select *                                                                                                ";
$sqlBuscaBoleto .= "   from tb_inter_boletos_giss                                                                            ";
$sqlBuscaBoleto .= "  where controle is null                                                                                 ";
$sqlBuscaBoleto .= "    and (     exists ( select 1                                                                          ";
$sqlBuscaBoleto .= "                         from tb_inter_empresas_giss                                                     ";
$sqlBuscaBoleto .= "                        where tb_inter_empresas_giss.num_cadastro = tb_inter_boletos_giss.num_cadastro ) ";
$sqlBuscaBoleto .= "          or  exists ( select 1                                                                          ";
$sqlBuscaBoleto .= "                         from tb_inter_empresas                                                          ";
$sqlBuscaBoleto .= "                        where tb_inter_empresas.num_cadastro = tb_inter_boletos_giss.num_cadastro ) )    ";
$sqlBuscaBoleto .= " order by cod_cliente, num_cadastro, num_documento                                                       ";

$rsBuscaBoleto = db_query($conn2, $sqlBuscaBoleto);
$linhasBuscaBoleto = pg_num_rows($rsBuscaBoleto);
if (isset($mostrahtml) and $mostrahtml == true) {

  db_atutermometro(8, 9, 'termometro2');
  echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO ... gerar issvar apartir de boletos'; </script>";
} else {

  db_log("PROCESSANDO ... gerar issvar apartir de boletos - total = $linhasBuscaBoleto ", $sArquivoLog, 0, true, true);
  db_log("", $sArquivoLog);
}

if ($linhasBuscaBoleto > 0) {

  // For nos Boletos do GISS
  $num_cadastro_ant = null;
  // Controla Processamento Inscricao caso nao exista na ISSBASE
  $lProcessaInscricao = true;

  for($bb = 0; $bb < $linhasBuscaBoleto; $bb ++) {

    db_fieldsmemory($rsBuscaBoleto, $bb);

    if (isset($mostrahtml) and $mostrahtml == true) {
      db_atutermometro($bb, $linhasBuscaBoleto, 'termometro');
    } else {
      $nPercentual = round((($bb + 1) / $linhasBuscaBoleto) * 100, 2);
      db_log(($bb+1)." de {$linhasBuscaBoleto} \rProcessando {$nPercentual}%...", null, 1, false, false);
    }

    if ($num_cadastro != $num_cadastro_ant) {

      // comeca outro processamento
      // Log de Inicio do Processamento da Inscricao
      db_log("", $sArquivoLog, 2, true, true);
      db_log("Processando Inscricao $num_cadastro", $sArquivoLog, 2, true, true);
      db_log("", $sArquivoLog, 2, true, true);
      $lProcessaInscricao = true;
      $num_cadastro_ant = $num_cadastro;
    }

    if ($lProcessaInscricao == false) {
      continue;
    }

    $lEncontrouIssbase = true;
    //validar se a inscrição esta na issbase
    $sqlValidaIssbase = "select q02_inscr from issbase where q02_inscr = $num_cadastro ";
    $rsValidaIssbase = db_query($conn, $sqlValidaIssbase);
    $linhasValidaIssbase = pg_num_rows($rsValidaIssbase);

    if ($linhasValidaIssbase == 0) {

      $lEncontrouIssbase = false;
      // $lProcessaInscricao = false;
      //
      // se nao encontrou na issbase a inscricao e o num_cadastro for maior que 1000000
      //
      //   1 -- procurar o cnpj da empresa no cgm
      //       se encontrar lanca um complementar para o cgm encontrado
      //       senao inclui um cgm e lanca o complementar, no campo z01_incest deve ser gravado o num_cadastro
      //
      $sSqlPesquisaCgmCnpjGiss = " select * ";
      $sSqlPesquisaCgmCnpjGiss .= "   from tb_inter_empresas_giss ";
      $sSqlPesquisaCgmCnpjGiss .= "  where num_cadastro = $num_cadastro ";
      $rsPesquisaCgmCnpjGiss = db_query($conn2, $sSqlPesquisaCgmCnpjGiss) or die($sSqlPesquisaCgmCnpjGiss);
      $iNumRowsGiss = pg_numrows($rsPesquisaCgmCnpjGiss);

      if ($iNumRowsGiss > 0) {

        db_log("Encontrou EMPRESA no giss (tb_inter_empresas_giss) ", $sArquivoLog, 2, true, true);
        $oPesquisaCgmCnpjGiss = db_utils::fieldsMemory($rsPesquisaCgmCnpjGiss, 0);
        $sSqlPesquisaCgmCnpj = " select * ";
        $sSqlPesquisaCgmCnpj .= "   from cgm ";
        $sSqlPesquisaCgmCnpj .= "  where z01_cgccpf = '{$oPesquisaCgmCnpjGiss->cpf_cnpj}'";
        $rsPesquisaCgmCnpj = db_query($conn, $sSqlPesquisaCgmCnpj) or die($sSqlPesquisaCgmCnpj);
        $iNumRows = pg_numrows($rsPesquisaCgmCnpj);

        if ($iNumRows > 0) {
          $oPesquisaCgmCnpj = db_utils::fieldsMemory($rsPesquisaCgmCnpj, 0);
          db_log("Encontrou CGM : {$oPesquisaCgmCnpj->z01_numcgm} para inscrição : {$num_cadastro}, procurando pelo CNPJ.", $sArquivoLog, 2, true, true);
          db_log("Incluindo ISS complementar automaticamente para o CGM : {$oPesquisaCgmCnpj->z01_numcgm}", $sArquivoLog, 2, true, true);
        } else {
          db_log("Inscrição não encontrada : {$num_cadastr} no CGM.", $sArquivoLog, 2, true, true);
          //
          // Incluindo cgm automaticamente
          //
          db_log("Incluindo CGM automaticamente para CNPJ/CPF : {$oPesquisaCgmCnpjGiss->cpf_cnpj}", $sArquivoLog, 2, true, true);
          $sCamposInsertCGM = "z01_numcgm,z01_nome,z01_ender,z01_compl,z01_bairro,z01_uf,z01_cep,z01_telef,z01_incest,z01_email,z01_cgccpf,z01_nomefanta";
          $sSqlInsertCGM  = " insert into cgm ({$sCamposInsertCGM}) ";
          $sSqlInsertCGM .= "          values (nextval('cgm_z01_numcgm_seq'), ";
          $sSqlInsertCGM .= "                  ".dbValida(substr($oPesquisaCgmCnpjGiss->nome_empresa,0,40), 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(substr($oPesquisaCgmCnpjGiss->logradouro,0,40)  , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->complemento       , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(substr($oPesquisaCgmCnpjGiss->bairro,0,40)      , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->estado            , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->cep               , 'int')   .", ";
          $sSqlInsertCGM .= "                  ".dbValida(substr($oPesquisaCgmCnpjGiss->telefone,0,12)    , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->num_cadastro      , 'int')   .", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->email             , 'string').", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->cpf_cnpj          , 'int')   .", ";
          $sSqlInsertCGM .= "                  ".dbValida(       $oPesquisaCgmCnpjGiss->nome_fantasia     , 'int')   ." ) ";

          $rsCGM = db_query($conn,$sSqlInsertCGM);
          if (pg_affected_rows($rsCGM) <> 0) {
          	 db_log("CGM incluido com sucesso", $sArquivoLog, 2, true, true);

          	 $sSqlPesquisaCgmCnpj  = " select * ";
             $sSqlPesquisaCgmCnpj .= "   from cgm ";
             $sSqlPesquisaCgmCnpj .= "  where z01_cgccpf = '{$oPesquisaCgmCnpjGiss->cpf_cnpj}'";
             $rsPesquisaCgmCnpj = db_query($conn, $sSqlPesquisaCgmCnpj) or die($sSqlPesquisaCgmCnpj);
             $iNumRows = pg_numrows($rsPesquisaCgmCnpj);
             if ($iNumRows > 0) {
               $oPesquisaCgmCnpj = db_utils::fieldsMemory($rsPesquisaCgmCnpj, 0);
             }

          }else{
          	 db_log("Erro: Não foi possivel incluir CGM automaticamente para a empresa. [num_cadastro = {$num_cadastr}] Erro no na operação : ".pg_last_error(), $sArquivoLog, 2, true, true);
          }

        }

      } else {
        db_log("Erro: Não é possivel processar o boleto (Inscrição [{$num_cadastro}] não encontrada na base GISSONLINE)", $sArquivoLog, 2, true, true);
      }

    }

    if ($linhasValidaIssbase > 0 || true) {

      //
      //  1 -- testar o tb_inter_boletos_giss.tipo_boleto
      //       se for upper(T) lanca complementar para o cgm da inscricao(q02_numcgm)
      //       no campo historico do issvar identificar que o complementar foi incluido pela integracao com o gissonline
      //
      if (strtoupper(trim($tipo_boleto)) == strtoupper('T')) {
      	$lEncontrouIssbase      = false;
      	$lProcessaBoletoSimples = true;
      	$sSqlCGMempresa = "select q02_numcgm as z01_numcgm from issbase where q02_inscr = {$num_cadastro}";
      	$rsCGMEmpresa   = db_query($conn,$sSqlCGMempresa);
      	if (pg_num_rows($rsCGMEmpresa) > 0) {

      	  $oCGMEmpresa = db_utils::fieldsMemory($rsCGMEmpresa,0);
      	  $oPesquisaCgmCnpj->z01_numcgm = $oCGMEmpresa->z01_numcgm ;
      	}
      }

      if ($num_documento < $ConfigINI["LimiteNumpre"]) {
        db_log("Erro: Boleto não processada, num_documento deve ser maior que {$ConfigINI["LimiteNumpre"]} (num_documento = $num_documento)", $sArquivoLog, 2, true, true);
      } else {

        $lProcessaBoletoSimples = true;

        /*
         * Busca as inscrições que estão em aberto e que não são um levantamento e não estão em divida
         * Caso exista um pagamento nessa mesma competencia, exclui o registro em aberto
         *
         */
          $sqlBuscarAbertos  = " select distinct ";
          $sqlBuscarAbertos .= "        q05_codigo as cod_issvar, ";
          $sqlBuscarAbertos .= "        arrecad.k00_numpre,              ";
          $sqlBuscarAbertos .= "        k00_numpar               ";
          $sqlBuscarAbertos .= "   from issvar                   ";
          $sqlBuscarAbertos .= "        inner join arrecad  on q05_numpre  = arrecad.k00_numpre   ";
          $sqlBuscarAbertos .= "                           and q05_numpar  = arrecad.k00_numpar   ";
          $sqlBuscarAbertos .= "        inner join arreinscr on q05_numpre = arreinscr.k00_numpre ";
          $sqlBuscarAbertos .= "        left  join issvarlev on q18_codigo = q05_codigo           ";
          $sqlBuscarAbertos .= "        left  join issvardiv on q19_issvar = q05_codigo           ";
          $sqlBuscarAbertos .= "  where q18_codigo is null           ";
          $sqlBuscarAbertos .= "    and q19_issvar is null           ";
          $sqlBuscarAbertos .= "	  and k00_inscr = $num_cadastro    ";
          $sqlBuscarAbertos .= "	  and q05_ano   = $ano_competencia ";
          $sqlBuscarAbertos .= "	  and q05_mes   = $mes_competencia ";
          $sqlBuscarAbertos .= "    and coalesce(q05_valor,0)  = 0";
          $sqlBuscarAbertos .= "    and coalesce(q05_vlrinf,0) = 0";

          $rsBuscarAbertos = db_query($conn, $sqlBuscarAbertos);
          $linhasBuscarAbertos = pg_num_rows($rsBuscarAbertos);
          if ($linhasBuscarAbertos > 0) {

            //Exclui os issvar encontrados em aberto
            for($ev = 0; $ev < $linhasBuscarAbertos; $ev ++) {
              db_fieldsmemory($rsBuscarAbertos, $ev);
              // validar se o issvar não est ano simples

              $sqlSimples  = "select q17_nomearq,k15_codbco,k15_codage,q23_codbco,q23_codage,q17_nroremessa ";
              $sqlSimples .= "  from issarqsimplesregissvar ";
              $sqlSimples .= "       inner join issarqsimplesreg         on q68_issarqsimplesreg = q23_sequencial ";
              $sqlSimples .= "       inner join issarqsimples            on q23_issarqsimples    = q17_sequencial ";
              $sqlSimples .= "       left  join issarqsimplesregdisbanco on q44_issarqsimplesreg = q23_sequencial ";
              $sqlSimples .= "       left  join disbanco                 on disbanco.idret = q44_disbanco ";
              $sqlSimples .= " where q68_issvar = $cod_issvar ";
              $rsSimples = db_query($sqlSimples);
              $linhasSimples = pg_num_rows($rsSimples);
              if ($linhasSimples > 0) {
                db_fieldsmemory($rsSimples, 0);
                db_log("Erro: ISS não processado ... Boleto giss documento = $num_documento importado para simples (issvar = $cod_issvar, Remessa = $q17_nroremessa, Arquivo = $q17_nomearq).", $sArquivoLog, 2, true, true);
                // $naoProcessaBoletoSimples = true;
                $lProcessaBoletoSimples = false;
              } else {
                // $naoProcessaBoletoSimples = false;
                $lProcessaBoletoSimples = true;
                db_log("Aviso: Gerando novo ISSVAR (issvar = $cod_issvar  documento giss = $num_documento) para competencia $mes_competencia/$ano_competencia  Inscricao: $num_cadastro", $sArquivoLog, 2, true, true);

                db_log("Anulando ISS em aberto (issvar = $cod_issvar).", $sArquivoLog, 2, true, true);
                $clissvar->excluir_issvar($cod_issvar, "0");

              }

              db_log("Excluindo financeiro (issvar = $cod_issvar numpre = $k00_numpre numpar = $k00_numpar).", $sArquivoLog, 2, true, true);
              $sSqlDeletaVariavelArrecad  = " delete from arrecad  ";
              $sSqlDeletaVariavelArrecad .= "  using arreinscr     ";
              $sSqlDeletaVariavelArrecad .= " where arreinscr.k00_inscr = {$num_cadastro} ";
              $sSqlDeletaVariavelArrecad .= "   and arrecad.k00_numpre  = {$k00_numpre}   ";
              $sSqlDeletaVariavelArrecad .= "   and arrecad.k00_numpar  = {$k00_numpar}   ";

              $rsDeletaVariavelArrecad    = db_query($sSqlDeletaVariavelArrecad) or die("Erro excluindo ARRECAD. Numpre: ".$k00_numpre." Numpar:".$k00_numpar. "MENSAGEM: ".pg_last_error());
              if (pg_affected_rows($rsDeletaVariavelArrecad) > 0 ) {
                db_log("Encontrado e excluido financeiro (issvar = $cod_issvar numpre = $k00_numpre numpar = $k00_numpar).", $sArquivoLog, 2, true, true);
              }
            }
          }
        // }
        // gera issvar complementar para todos
        // setar todas as  variaveis..
        if ($lProcessaBoletoSimples) {

          db_log("Processando Boleto giss. Documento = $num_documento, Data emissao= $data_emissao, Data Vencimento = $data_vencimento, Mês/Ano = $mes_competencia/$ano_competencia, Valor = $valor_imposto", $sArquivoLog, 2, true, true);

          /*
           * Incluimos os registros do GissOnline no Issvar como complementar
           * Caso seja tomador incluimos para o cgm do tomador, senão para o contribuinte
           * Após geramos um recibo para o registro do issvar e alteramos a informação do boleto no Giss para lido
           *
           */
          $vt = array ();
          $clissvar->q05_numpre = $num_documento;
          $clissvar->q05_numpar = $mes_competencia;
          $clissvar->q05_valor  = $valor_imposto;
          $clissvar->q05_ano    = $ano_competencia;
          $clissvar->q05_mes    = $mes_competencia;
          $clissvar->q05_histor = "Importado do GissOnline - tipo de boleto: $tipo_boleto";
          $clissvar->q05_aliq   = '0';
          $clissvar->q05_bruto  = '0';
          $clissvar->q05_vlrinf = "null";

          if ( ! $lEncontrouIssbase ) {

          	if (isset($oPesquisaCgmCnpj->z01_numcgm)) {

	          	if ($oPesquisaCgmCnpj->z01_numcgm != '') {
	          		db_log("Incluindo ISSQN complementar para CGM : {$oPesquisaCgmCnpj->z01_numcgm} . ", $sArquivoLog, 2, true, true);
	              $lIncluiComplementar = $clissvar->incluir_issvar_complementar($vt, '',$oPesquisaCgmCnpj->z01_numcgm);

                /* Trata erro ao incluir issvar complementar */
                if ($lIncluiComplementar == false) {

                  $sqlerro = true;
                  db_log("Erro: ISSQN complementar não incluido ( {$clissvar->erro_msg} )", $sArquivoLog, 2, true, true);
                  db_query($conn, 'rollback');
                  db_query($conn2, 'rollback');
                  exit();
                }

	          	} else {

                $sqlerro = true;
                db_log("Erro: ISSQN complementar não incluido ( sem CGM {$oPesquisaCgmCnpj->z01_numcgm}). ", $sArquivoLog, 2, true, true);
                db_query($conn, 'rollback');
                db_query($conn2, 'rollback');
                exit();
	          	}
          	}
          } else {

          	db_log("Incluindo ISSQN complementar. ", $sArquivoLog, 2, true, true);
            $lIncluiComplementar = $clissvar->incluir_issvar_complementar($vt, $num_cadastro);

            /* Trata erro ao incluir issvar complementar */
            if ($lIncluiComplementar == false) {
              $sqlerro = true;
              db_log("Erro: ISSQN complementar não incluido ( {$clissvar->erro_msg} )", $sArquivoLog, 2, true, true);
              db_query($conn, 'rollback');
              db_query($conn2, 'rollback');
              exit();
            }

          }

          if ($clissvar->erro_status == "0") {
            $sqlerro = true;
            db_log("Erro: Processamento do boleto (Gerando isscomplementar), {$clissvar->erro_msg}", $sArquivoLog, 0, true, true);
            db_query($conn, 'rollback');
            db_query($conn2, 'rollback');
            exit();
          }

          // gerar db_reciboweb
          if ($sqlerro == false) {
            $sqlIncluiRecibo = "insert into db_reciboweb (k99_numpre,k99_numpar,k99_numpre_n,k99_codbco,k99_codage,k99_numbco,k99_desconto,k99_tipo,k99_origem) ";
            $sqlIncluiRecibo .= "     values ($num_documento,$mes_competencia,$num_documento,0,'','',0,9,3) ";
            $rsIncluiRecibo = db_query($sqlIncluiRecibo);
            if ($rsIncluiRecibo == false) {
              $sqlerro = true;
              db_log("Erro: \n sql = $sqlIncluiRecibo", null, 0, true, true);
              db_query($conn, 'rollback');
              db_query($conn2, 'rollback');
              exit();
            }

            $sqlIncluiRecibocodbar = "insert into recibocodbar (k00_numpre,k00_codbar,k00_linhadigitavel) values($num_documento,'" . $cod_barras . "','" . $cod_barras . "')";
            $rsIncluiRecibocodbar = db_query($sqlIncluiRecibocodbar);
            if ($rsIncluiRecibocodbar == false) {
              $sqlerro = true;
              db_log("Erro: \n sql = $sqlIncluiRecibocodbar", null, 1, true, true);
              db_query($conn, 'rollback');
              db_query($conn2, 'rollback');
              exit();
            }
          }

          $sqlAlteraControle = "update tb_inter_boletos_giss set controle = '1' where num_documento = {$num_documento} ";
          $rsAlteraControle = db_query($conn2, $sqlAlteraControle);
		      if ($rsAlteraControle == false) {
		        db_log("Erro: \n sql = $sqlAlteraControle", null, 1, true, true);
		        db_query($conn, 'rollback');
		        db_query($conn2, 'rollback');
		        exit();
		      }

          if ($sqlerro == false) {
            db_log("Boleto processado com sucesso!...(issvar = " . $clissvar->q05_codigo . ", numpre = $num_documento, parcela = $mes_competencia)", $sArquivoLog, 2, true, true);
          }
        }
      }
    }
  } // do for

}

// ############################ PARTE 3 #####################

db_log("INICIO DO PROCESSAMENTO DE BAIXA ", $sArquivoLog, 0, true, true);

//verificar se tem arreidret... se não tiver ai vai pela corrente.

$sqlArreidret  = "select disbanco.k15_codbco,";
$sqlArreidret .= "       arreidret.idret,";
$sqlArreidret .= "       disbanco.dtarq,";
$sqlArreidret .= "       disbanco.dtpago,";
$sqlArreidret .= "       arrepaga.k00_numpre, ";
$sqlArreidret .= "       arrepaga.k00_numpar,";
$sqlArreidret .= "       (select sum(arrecant.k00_valor)";
$sqlArreidret .= "          from arrecant";
$sqlArreidret .= "         where arrecant.k00_numpre = arrepaga.k00_numpre";
$sqlArreidret .= "           and arrecant.k00_numpar = arrepaga.k00_numpar) as valor_titulo,";
$sqlArreidret .= "       sum(arrepaga.k00_valor) as valor,";
$sqlArreidret .= "       arrepaga.k00_dtpaga,";
$sqlArreidret .= "       disarq.arqret,";
$sqlArreidret .= "       disarq.dtarquivo";
$sqlArreidret .= "  from issvar";
$sqlArreidret .= "       inner join arrepaga  on arrepaga.k00_numpre  = q05_numpre";
$sqlArreidret .= "                           and arrepaga.k00_numpar  = q05_numpar";
$sqlArreidret .= "       inner join arreidret on arreidret.k00_numpre = q05_numpre";
$sqlArreidret .= "                           and arreidret.k00_numpar = q05_numpar";
$sqlArreidret .= "       inner join disbanco  on disbanco.idret       = arreidret.idret";
$sqlArreidret .= "       inner join disarq    on disarq.codret        = disbanco.codret";
$sqlArreidret .= " group by disbanco.k15_codbco,";
$sqlArreidret .= "          arreidret.idret,";
$sqlArreidret .= "          disbanco.dtarq,";
$sqlArreidret .= "          disbanco.dtpago,";
$sqlArreidret .= "          arrepaga.k00_numpre, ";
$sqlArreidret .= "          arrepaga.k00_numpar,";
$sqlArreidret .= "          arrepaga.k00_dtpaga,";
$sqlArreidret .= "          disarq.arqret,";
$sqlArreidret .= "          disarq.dtarquivo ";
$rsArreidret   = db_query($conn, $sqlArreidret);
$linhasArreidret = pg_num_rows($rsArreidret);
if (isset($mostrahtml) and $mostrahtml == true) {
  db_atutermometro(2, 8, 'termometro2');
  echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO BAIXA POR DISBANCO'; </script>";
} else {
  db_log("PROCESSANDO BAIXA POR DISBANCO  total = {$linhasArreidret}", $sArquivoLog, 0);
  db_log("", $sArquivoLog);
}

if ($linhasArreidret > 0) {
  $codBancDis = "";
  $idRetDis = "";
  for($di = 0; $di < $linhasArreidret; $di ++) {
    db_fieldsmemory($rsArreidret, $di);

    if (isset($mostrahtml) and $mostrahtml == true) {
      db_atutermometro($di, $linhasArreidret, 'termometro');
    } else {
      $nPercentual = round((($di + 1) / $linhasArreidret) * 100, 2);
      db_log(" ".($di+1)." da {$linhasArreidret} \rProcessando {$nPercentual}%...", null, 1, false, false);
    }
    // validar se ja esta no giss .. na tb_inter_baixa

    if (($codBancDis != $k15_codbco) or ($idRetDis != $idret)) {
      // inclui baixa
      $codBancDis = $k15_codbco;
      $idRetDis = $idret;

      $sqlVerBaixa = "select * from tb_inter_baixa
        where  cod_cliente  =  $DB_CLIENTE_GISSONLINE
        and cod_banco    = $k15_codbco
        and num_sequencia =$idret ";
      $rsVerBaixa = db_query($conn2, $sqlVerBaixa);
      $linhasVerBaixa = pg_num_rows($rsVerBaixa);
      if ($linhasVerBaixa == 0) {

        $incluibaixa = " INSERT INTO tb_inter_baixa (
                      cod_cliente ,
                      cod_banco ,
                      num_sequencia ,
                      data_geracao ,
                      nome_arquivo ,
                      data_movimento ,
                      timestamp ,
                      controle )
                        VALUES ($DB_CLIENTE_GISSONLINE ,
                            $k15_codbco,
                            $idret,
                            " . dbValida($dtarquivo, 'date') . ",
                            " . dbValida($arqret, 'string') . ",
                            " . dbValida($dtarq, 'date') . ",
                            to_timestamp($iMomentoIntegracao),
                            null
                            )";
        $resultbaixa = @db_query($conn2, $incluibaixa);
        if ($resultbaixa == false) {
          db_log("Erro: \n sql = $incluibaixa", null, 1, true, true);
          db_query($conn, 'rollback');
          db_query($conn2, 'rollback');
          exit();
        } else {
          db_log("Processado tb_inter_baixa: cod_cliente=$DB_CLIENTE_GISSONLINE, cod_banco=$k15_codbco, num_sequencia=$idret", $sArquivoLog, 2, true, true);

        }
      }
    }
    // aqui eu gravaria varios detalhes para esta baixa

    $sqlVerBaixaDet = "select * from tb_inter_baixa_detalhe
      where  cod_cliente   =  $DB_CLIENTE_GISSONLINE
      and cod_banco     = $k15_codbco
      and num_sequencia = $idret
      and num_documento = $k00_numpre
      and linha         = $k00_numpar
      ";
    $rsVerBaixaDet = db_query($conn2, $sqlVerBaixaDet);
    $linhasVerBaixaDet = pg_num_rows($rsVerBaixaDet);
    if ($linhasVerBaixaDet == 0) {

      $incluirbaixadetalhe = "
        INSERT INTO  tb_inter_baixa_detalhe (
            num_sequencia ,
            cod_banco ,
            cod_cliente,
            num_documento,
            linha ,
            valor_titulo ,
            valor_pago ,
            data_pagamento ,
            valor_encargos ,
            descricao_linha_t ,
            descricao_linha_u ,
            controle ,
            timestamp
            )
        VALUES (                $idret,
            $k15_codbco,
            $DB_CLIENTE_GISSONLINE ,
            $k00_numpre,
            $k00_numpar,
            " . dbValida($valor_titulo, 'string') . ",
            " . dbValida($valor, 'string') . ",
            " . dbValida($dtpago, 'date') . ",
            0,
            null,
            null,
            null,
            to_timestamp($iMomentoIntegracao)
            )";
      $resultbaixadetalhe = @db_query($conn2, $incluirbaixadetalhe);
      if ($resultbaixadetalhe == false) {
        db_log("Erro: \n sql = $incluibaixadetalhe", null, 1, true, true);
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit();
      } else {
        db_log("Processado tb_inter_baixa_detalhe: cod_cliente=$DB_CLIENTE_GISSONLINE, cod_banco=$k15_codbco, num_sequencia=$idret, num_documento= $k00_numpre, linha= $k00_numpar", $sArquivoLog, 2, true, true);
      }
    }

  }
}

//  ############## verifica se esta na cornump #################
$sqlCornump = "select correnteid.k56_sequencial,
  arrepaga.k00_numpre,
  arrepaga.k00_numpar,
  (select sum(arrecant.k00_valor)
   from arrecant
   where arrecant.k00_numpre = arrepaga.k00_numpre
   and arrecant.k00_numpar = arrepaga.k00_numpar) as valor_titulo_cor,
  sum(arrepaga.k00_valor) as valor_cor,
  arrepaga.k00_dtpaga,
  corrente.k12_data
  from issvar
  inner join arrepaga    on arrepaga.k00_numpre   = q05_numpre
  and arrepaga.k00_numpar   = q05_numpar
  inner join cornump     on cornump.k12_numpre    = q05_numpre
  and cornump.k12_numpar    = q05_numpar
  inner join corrente    on corrente.k12_id       = cornump.k12_id
  and corrente.k12_data     = cornump.k12_data
  and corrente.k12_autent   = cornump.k12_autent
  inner join correnteid  on corrente.k12_id       = correnteid.k56_id
  and corrente.k12_data     = correnteid.k56_data
  and corrente.k12_autent   = correnteid.k56_autent
  group by	correnteid.k56_sequencial,
  arrepaga.k00_numpre,
  arrepaga.k00_numpar,
  arrepaga.k00_dtpaga,
  corrente.k12_data
  ";
$rsCornump = db_query($conn, $sqlCornump);
$linhasCornump = pg_num_rows($rsCornump);
if (isset($mostrahtml) and $mostrahtml == true) {
  db_atutermometro(2, 8, 'termometro2');
  echo "<script>document.getElementById('titulo').innerHTML='PROCESSANDO BAIXA POR CORNUMP'; </script>";
} else {
  db_log("", $sArquivoLog);
  db_log("PROCESSANDO BAIXA POR CORNUMP  total = {$linhasCornump}", $sArquivoLog, 0);
  db_log("", $sArquivoLog);
}

if ($linhasCornump > 0) {

  $idRetCor = "";
  for($co = 0; $co < $linhasCornump; $co ++) {
    db_fieldsmemory($rsCornump, $co);

    if (isset($mostrahtml) and $mostrahtml == true) {
      db_atutermometro($co, $linhasCornump, 'termometro');
    } else {
      $nPercentual = round((($co + 1) / $linhasCornump) * 100, 2);
      db_log(" ".($co+1)." da {$linhasCornump} \rProcessando {$nPercentual}%...", null, 1, false, false);
    }

    if ($idRetCor != $k56_sequencial) {
      // inclui baixa
      $idRetCor = $k56_sequencial;
      $sqlVerBaixaCor = "select * from tb_inter_baixa
        where  cod_cliente   =  $DB_CLIENTE_GISSONLINE
        and cod_banco     = 0
        and num_sequencia = $k56_sequencial ";
      $rsVerBaixaCor = db_query($conn2, $sqlVerBaixaCor);
      $linhasVerBaixaCor = pg_num_rows($rsVerBaixaCor);
      if ($linhasVerBaixaCor == 0) {

        $incluibaixaCor = " INSERT INTO tb_inter_baixa (
          cod_cliente ,
                      cod_banco ,
                      num_sequencia ,
                      data_geracao ,
                      nome_arquivo ,
                      data_movimento ,
                      timestamp ,
                      controle )
                        VALUES ($DB_CLIENTE_GISSONLINE ,
                            0,
                            $k56_sequencial,
                            " . dbValida($k12_data, 'date') . ",
                            'ARRECADACAO TESOURARIA',
                            " . dbValida($dtarquivo, 'date') . ",
                            to_timestamp($iMomentoIntegracao),
                            null
                            )";
        $resultbaixaCor = db_query($conn2, $incluibaixaCor) or die($incluibaixaCor);
        if ($resultbaixaCor == false) {
          db_log("Erro: \n sql = $incluibaixaCor", null, 1, true, true);
          db_query($conn, 'rollback');
          db_query($conn2, 'rollback');
          exit();
        } else {
          db_log("Processado tb_inter_baixa: cod_cliente=$DB_CLIENTE_GISSONLINE, cod_banco=0, num_sequencia=$k56_sequencial", $sArquivoLog, 2, true, true);
        }
      }
    }
    //grava detalhe

    $sqlVerBaixaDetCor = "select * from tb_inter_baixa_detalhe
      where  cod_cliente   =  $DB_CLIENTE_GISSONLINE
      and cod_banco     = 0
      and num_sequencia = $k56_sequencial
      and num_documento = $k00_numpre
      and linha         = $k00_numpar ";

    $rsVerBaixaDetCor = db_query($conn2, $sqlVerBaixaDetCor);
    $linhasVerBaixaDetCor = pg_num_rows($rsVerBaixaDetCor);
    if ($linhasVerBaixaDetCor == 0) {

      $incluirbaixadetalhecor = "
        INSERT INTO  tb_inter_baixa_detalhe (
            num_sequencia ,
            cod_banco ,
            cod_cliente,
            num_documento,
            linha ,
            valor_titulo ,
            valor_pago ,
            data_pagamento ,
            valor_encargos ,
            descricao_linha_t ,
            descricao_linha_u ,
            controle ,
            timestamp)
        VALUES ($k56_sequencial,
            0,
            $DB_CLIENTE_GISSONLINE ,
            $k00_numpre,
            $k00_numpar,
            " . dbValida($valor_titulo_cor, 'string') . ",
            " . dbValida($valor_cor, 'string') . ",
            " . dbValida($k00_dtpaga, 'date') . ",
            0,
            null,
            null,
            null,
            to_timestamp($iMomentoIntegracao) )";

      $resultbaixadetalhecor = @db_query($conn2, $incluirbaixadetalhecor) or die($incluirbaixadetalhecor);
      if ($resultbaixadetalhecor == false) {
        db_log("Erro: \n sql = $incluibaixadetalhecor", null, 1, true, true);
        db_query($conn, 'rollback');
        db_query($conn2, 'rollback');
        exit();
      } else {
        db_log("Processado tb_inter_baixa_detalhe: cod_cliente=$DB_CLIENTE_GISSONLINE, cod_banco=0, num_sequencia=$k56_sequencial, num_documento= $k00_numpre, linha= $k00_numpar", $sArquivoLog, 2, true, true);
      }
    }
  }
}

// FIM PARTE 3

db_query($conn, 'truncate issbaseintegracaoexterna');
db_query($conn,'commit');
db_query($conn2,'commit');

db_log("Final.: " . date("H:i:s"), $sArquivoLog);
db_log("\n *** FINAL Script " . $sNomeScript . " *** \n\n", $sArquivoLog);

function dbValida($valor, $tipo) {

  $aValorDefault = array ( 'int' => "0", 'date' => "null", 'string' => "null" );
  if ($valor != '') {
    if ($tipo == 'int') {
      return $valor;
    } else {
      $valor = "'" . addslashes($valor) . "'";
      return $valor;
    }

  } else {
    return $aValorDefault [$tipo];
  }
}

function dbValidaNumero($string) {

  $tamanho = strlen($string);
  $string2 = "";
  for($tm = 0; $tm < $tamanho; $tm ++) {

    $letra = $string {$tm};
    if ($letra == '0' or $letra == '1' or $letra == '2' or $letra == '3' or $letra == '4' or $letra == '5' or $letra == '6' or $letra == '7' or $letra == '8' or $letra == '9') {
      $string2 .= $letra;
    }
  }
  return $string2;
}

function db_log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

  $aDataHora = getdate();

  $sQuebraAntes = $lQuebraAntes ? "\n" : "";

  if ($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes, $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }

  // Se habilitado saida na tela...
  if ($iTipo == 0 or $iTipo == 1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo...
  if ($iTipo == 0 or $iTipo == 2) {
    if (! empty($sArquivo)) {
      $fd = fopen($sArquivo, "a+");
      if ($fd) {
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
    }
  }

  return $aDataHora;
}