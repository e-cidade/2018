<?
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

//MODULO: educação
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cursoedu_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$iEscola = db_getsession("DB_coddepto");
$lEsc    = false;

$oDaoTurma        = new cl_turma();
$oDaoTurno        = new cl_turno();
$oDaoCalendario   = new cl_calendario();
$oDaoProcedimento = new cl_procedimento();
$oDaoCurso        = new cl_curso;

/**
 * Alterado forma de buscar as etapas que podem ter progressão parcial definidas.
 * Agora buscamos as etapas de alunos que já estão em progressão na escola, para depois buscar as turmas contemplam 
 * as etapas retornadas.
 * Só são vistas as progressões que ainda não estão concluída. 
 */
$sCamposEtapaProgressao = " array_to_string(array_accum(distinct ed114_serie), ',') as lista_etapas";
$sWhereEtapaProgressao  = "     ed114_situacaoeducacao  = " . ProgressaoParcialAluno::ATIVA;
$sWhereEtapaProgressao .= " and ed114_escola = {$iEscola} ";
$oDaoProgressaoParcial  = new cl_progressaoparcialaluno();

$sSqlEtapas             = $oDaoProgressaoParcial->sql_query_file(null, $sCamposEtapaProgressao, null, $sWhereEtapaProgressao);
$rsEtapasEmProgressao   = db_query( $sSqlEtapas );

if ( !$rsEtapasEmProgressao ) {
  
  $sMsgErro  = "Erro ao buscar etapas de alunos em progressão. ";
  $sMsgErro .= pg_last_error($rsEtapasEmProgressao);
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
}

$sDiscParam = 0;
if ( pg_num_rows( $rsEtapasEmProgressao ) > 0 ) {
  
  $lEsc         = true;
  $sListaEtapas = db_utils::fieldsMemory($rsEtapasEmProgressao, 0)->lista_etapas;
  
  if ( !empty($sListaEtapas) ) {
    $sDiscParam = $sListaEtapas; 
  }
}

/**
 * Busca as etapas equivalentes das etapas que possuem aluno em progressão, para incrementar a busca
 */
$sSqlEquivalentes  = "SELECT ARRAY(SELECT ed234_i_serieequiv "; 
$sSqlEquivalentes .= "               FROM serieequiv  ";
$sSqlEquivalentes .= "              WHERE ed234_i_serie in ({$sDiscParam})) as seriesequivalentes";
$rsEquivalentes    = db_query( $sSqlEquivalentes );
 
db_fieldsmemory( $rsEquivalentes, 0 );
 
$seriesequivalentes = str_replace( "{", "", $seriesequivalentes );
$seriesequivalentes = str_replace( "}", "", $seriesequivalentes );
 
if ( $seriesequivalentes != "" ) {
  $sDiscParam .= ", {$seriesequivalentes}";
}

$clrotulo  = new rotulocampo;
$oDaoTurma->rotulo->label("ed57_i_codigo");
$oDaoTurma->rotulo->label("ed57_c_descr");
$oDaoTurma->rotulo->label("ed57_i_calendario");
$oDaoTurma->rotulo->label("ed57_i_turno");
$oDaoTurma->rotulo->label("ed57_i_sala");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed220_i_procedimento");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td height="63" align="center" valign="top">
          <table width="35%" border="0" align="center" cellspacing="0">
            <form name="form1" method="post" action="" >
              <tr>
                <td width="4%" nowrap title="<?=$Ted57_i_codigo?>">
                  <?=$Led57_i_codigo?>
                  <?db_input("ed57_i_codigo",10,$Ied57_i_codigo,true,"text",4,"","chave_ed57_i_codigo");?>
                </td>
                <td width="4%" nowrap title="<?=$Ted57_c_descr?>">
                  <?=$Led57_c_descr?>
                  <?db_input("ed57_c_descr",10,$Ied57_c_descr,true,"text",4,"","chave_ed57_c_descr");?>
                </td>
                <td width="4%" nowrap title="<?=$Ted31_i_curso?>">
                  <?=$Led31_i_curso?>
                  <?
                    $sCamposCurso = " ed29_i_codigo,ed29_c_descr ";
                    $sSqlCurso    = $oDaoCurso->sql_query_file("", $sCamposCurso, "ed29_c_descr");
                    $rsCurso      = $oDaoCurso->sql_record($sSqlCurso);
                    db_selectrecord("ed31_i_curso", $rsCurso, "", "", "", "chave_ed31_i_curso", "", "  ", "", 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td width="4%" nowrap title="<?=$Ted57_i_turno?>">
                  <?=$Led57_i_turno?>
                  <?
                    $sCamposTurno = " ed15_i_codigo,ed15_c_nome,ed15_i_sequencia ";
                    $sWhereTurno  = " periodoescola.ed17_i_escola = ".$iEscola;
                    $sSqlTurno    = " SELECT ".$sCamposTurno;
                    $sSqlTurno   .= "    FROM turno ";
                    $sSqlTurno   .= "      INNER JOIN periodoescola ON periodoescola.ed17_i_turno = turno.ed15_i_codigo ";
                    $sSqlTurno   .= "    WHERE ".$sWhereTurno;
                    $sSqlTurno   .= "    GROUP BY ed15_i_codigo,ed15_c_nome,ed15_i_sequencia ";
                    $sSqlTurno   .= "    ORDER BY ed15_i_sequencia ";
                    $rsTurno      = $oDaoTurno->sql_record($sSqlTurno);

                    if ($oDaoTurno->numrows == 0) {

                      $aTurno = array(
                                      '' => 'NENHUM REGISTRO'
                                     );
                      db_select('ed57_i_turno', $aTurno, true, 1, "");

                    } else {
                      db_selectrecord("ed57_i_turno", $rsTurno, "", "", "", "chave_ed57_i_turno", "", "  ", "", 1);
                    }
                  ?>
                </td>
                <td width="4%" nowrap title="<?=$Ted57_i_calendario?>">
                  <?=$Led57_i_calendario?>
                  <?
                    $sCamposCalendario = " ed52_i_codigo,ed52_c_descr ";
                    $sWhereCalendario  = " ed52_c_passivo = 'N' AND ed38_i_escola = ".$iEscola;
                    $sSqlCalendario    = $oDaoCalendario->sql_query_calescola("", $sCamposCalendario, "ed52_i_ano DESC", $sWhereCalendario);
                    $rsCalendario      = $oDaoCalendario->sql_record($sSqlCalendario);

                    if ($oDaoCalendario->numrows == 0) {

                      $aCalendario = array(
                                           '' => 'NENHUM REGISTRO'
                                          );
                      db_select('ed57_i_calendario', $aCalendario, true, 1, "");

                    } else {
                      db_selectrecord("ed57_i_calendario", $rsCalendario, "", "", "", "chave_ed57_i_calendario", "", "  ", "", 1);
                    }
                  ?>
                </td>
                <td width="4%" nowrap title="<?=$Ted220_i_procedimento?>">
                  <?=$Led220_i_procedimento?>
                  <?
                    $sCamposProcedimento = " ed40_i_codigo,ed40_c_descr ";
                    $sWhereProcedimento  = " ed86_i_escola = ".$iEscola." GROUP BY ed40_i_codigo,ed40_c_descr ";
                    $sSqlProcedimento    = $oDaoProcedimento->sql_query_procturma("",
                                                                                  $sCamposProcedimento,
                                                                                  "ed40_c_descr",
                                                                                  $sWhereProcedimento
                                                                                 );
                    $rsProcedimento      = $oDaoProcedimento->sql_record($sSqlProcedimento);

                    if ($oDaoProcedimento->numrows == 0) {

                      $aProcedimento = array(
                                             '' => 'NENHUM REGISTRO'
                                            );
                      db_select('ed57_i_procedimento', $aProcedimento, true, 1, "");

                    } else {
                      db_selectrecord("ed220_i_procedimento", $rsProcedimento, "", "", "", "chave_ed220_i_procedimento", "", "  ", "", 1);
                    }
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="4" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_turma.hide();">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?
            if (!isset($pesquisa_chave)) {

              $sCampos  = " distinct turma.ed57_i_codigo, ";
              $sCampos .= " turma.ed57_i_codigoinep, ";
              $sCampos .= " turma.ed57_c_descr, ";
              $sCampos .= " ed11_i_codigo, ";
              $sCampos .= " ed11_c_descr as ed57_i_serie, ";
              $sCampos .= " calendario.ed52_c_descr AS ed57_i_calendario, ";
              $sCampos .= " cursoedu.ed29_c_descr AS ed31_i_curso, ";
              $sCampos .= " turno.ed15_c_nome AS ed57_i_turno, ";
              $sCampos .= " sala.ed16_c_descr AS ed57_i_sala ";
              $sWhere = "";

              if (isset($chave_ed57_i_codigo) && (trim($chave_ed57_i_codigo) != "")) {

              	$sWhere .= " AND ed57_i_codigo = ".$chave_ed57_i_codigo;
                $lEsc    = true;

              }

              if (isset($chave_ed57_c_descr) && (trim($chave_ed57_c_descr) != "")) {

              	$sWhere .= " AND ed57_c_descr like '$chave_ed57_c_descr%'";
                $lEsc    = true;

              }

              if (isset($chave_ed57_i_calendario) && (trim($chave_ed57_i_calendario) != "")){

              	$sWhere .= " AND ed57_i_calendario = $chave_ed57_i_calendario";
                $lEsc    = true;

              }

              if (isset($chave_ed57_i_turno) && (trim($chave_ed57_i_turno) != "")) {

                $sWhere .= " AND ed57_i_turno =$chave_ed57_i_turno";
                $lEsc    = true;

              }

              if (isset($chave_ed31_i_curso) && (trim($chave_ed31_i_curso) != "")) {

              	$sWhere .= " AND ed31_i_curso = $chave_ed31_i_curso";
                $lEsc    = true;

              }

              if (isset($chave_ed220_i_procedimento) && (trim($chave_ed220_i_procedimento) != "")) {

              	$sWhere .= " AND exists(SELECT * FROM turmaserieregimemat WHERE ed220_i_turma = ed57_i_codigo ";
                $sWhere .= "               AND ed220_i_procedimento = $chave_ed220_i_procedimento) ";
                $lEsc    = true;

              }

              if (isset($chave_ed57_i_sala) && (trim($chave_ed57_i_sala) != "")) {

              	$sWhere .= " AND ed57_i_sala = $chave_ed57_i_sala";
                $lEsc    = true;

              }

              if ($lEsc) {

                $sWhereSql  = " ed52_c_passivo = 'N' AND ed57_i_escola = ".$iEscola." ".$sWhere;
                $sWhereSql .= " AND turma.ed57_i_tipoturma = 4 AND ed11_i_codigo IN (".$sDiscParam.") ";
                $sSql       = $oDaoTurma->sql_query_turmaserie("", $sCampos, "ed57_c_descr", $sWhereSql);
                $rsTurma    = $oDaoTurma->sql_record($sSql);

                if ($oDaoTurma->numrows == 0) {

                  $sWhere   .= " AND ed11_i_codigo IN (".$sDiscParam.") ";
                  $sWhereSql = " ed52_c_passivo = 'N' AND ed57_i_escola = ".$iEscola." ".$sWhere;
                  $sSql      = $oDaoTurma->sql_query_turmaserie("", $sCampos, "ed57_c_descr", $sWhereSql);

                }

              }

              db_lovrot($sSql,12,"()","",$funcao_js);
            } else {

              if ($pesquisa_chave != null && $pesquisa_chave != "") {

                $sOrderByTurma = " ed57_c_descr,ed11_i_ensino,ed11_i_sequencia ";
                $sWhereTurma   = " ed52_c_passivo = 'N' AND ed57_i_codigo = ".$pesquisa_chave;
                $sWhereTurma  .= " AND ed57_i_escola = ".$iEscola." AND turma.ed57_i_tipoturma = 4 ";
                $sWhereTurma  .= " AND ed57_i_codigo IN (".$sDiscParam.") ";
                $sSqlTurma     = $oDaoTurma->sql_query("", "*", $sOrderByTurma, $sWhereTurma);
                $rsTurma       = $oDaoTurma->sql_record($sSqlTurma);

                if ($oDaoTurma->numrows != 0) {

                  db_fieldsmemory($rsTurma, 0);
                  echo "<script>".$funcao_js."('$ed57_c_descr','$ed52_c_descr','$ed29_c_descr','$ed11_c_descr','$ed15_c_nome',false);</script>";

                } else {
                  echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','','','','',true);</script>";
                }

              } else {
                echo "<script>".$funcao_js."('',false);</script>";
              }
            }
          ?>
        </td>
      </tr>
    </table>
  </body>
</html>