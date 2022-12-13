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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("std/DBDate.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clturma               = new cl_turma;
$clmatricula           = new cl_matricula;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$escola                = db_getsession("DB_coddepto");

$sCamposSerieMatricula = "ed221_i_serie, ed11_c_descr, ed11_i_sequencia, ed10_i_tipoensino, ed10_i_codigo";
$sWhereSerieMatricula  = " ed60_i_aluno = {$codaluno} AND ed60_i_turma = {$turma} AND ed60_c_ativa = 'S'";
$sSqlSerieMatricula    = $clmatricula->sql_query( "", $sCamposSerieMatricula, "", $sWhereSerieMatricula );
$result                = $clmatricula->sql_record($sSqlSerieMatricula);
db_fieldsmemory($result, 0);

$sCamposTurma = "ed57_i_tipoturma, ed57_c_descr, ed52_c_descr, ed52_i_ano";
$sSqlTurma    = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$turma}" );
$result       = $clturma->sql_record( $sSqlTurma );

db_fieldsmemory($result, 0);

$oTurma        = TurmaRepository::getTurmaByCodigo($turma);
$oEtapa        = EtapaRepository::getEtapaByCodigo($ed221_i_serie);
$oProximaEtapa = EtapaRepository::getProximaEtapa($oTurma, $oEtapa);
if (!empty($oProximaEtapa) && $ed10_i_codigo != $oProximaEtapa->getEnsino()->getCodigo()) {

  $ed11_i_sequencia = 0;
  $ed10_i_codigo    = $oProximaEtapa->getEnsino()->getCodigo();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <br>
   <b>
    Aluno: <?=$aluno?><br>
    Turma Atual: <?=$ed57_c_descr?> - Etapa: <?=$ed11_c_descr?> - Calendário: <?=$ed52_c_descr?><br><br>
    Turmas disponíveis para o avanço do aluno:
   </b>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   /**
    * Na Classificacao, os Alunos pode ser matriculados qualquer etapa posterior a da matrícula atual.
    * no avanço, os alunos apenas podem ser colocados na próxima Etapa do ensino.
    */
   $sTipoComparacao = " >= ";
   if (isset($_GET["sTipoValidacaoEtapa"]) && $_GET["sTipoValidacaoEtapa"] == 'avanco') {
     $sTipoComparacao = " = ";
   }
   if (!isset($pesquisa_chave) && !empty($oProximaEtapa)) {

    $campos = "turma.ed57_i_codigo,
               turma.ed57_c_descr,
               ed11_i_codigo,
               ed11_c_descr,
               calendario.ed52_c_descr as ed57_i_calendario,
               cursoedu.ed29_c_descr as ed31_i_curso,
               turno.ed15_c_nome as ed57_i_turno,
               sala.ed16_c_descr as ed57_i_sala,
               formaavaliacao.ed37_c_descr as dl_Avaliação
              ";
    $sWhere = " ed57_i_escola = $escola AND ed52_i_ano = $ed52_i_ano";
    if (!$ed57_i_tipoturma == 3) {
     $sWhere = " AND ed57_i_codigo not in($turma)";
    }

    /**
     * Buscamos todas as turmas que possuem etapa(s) posterior(es) a turma atual do aluno.
     * retorna também as etapas equivalentes (dentro do ensino em que o aluno se encontra)
     *
     */
    $sWhere .= " and ed10_i_tipoensino = {$ed10_i_tipoensino} ";
    $sWhere .= " and ((ed11_i_sequencia {$sTipoComparacao} ".($ed11_i_sequencia + 1);
    $sWhere .= "        and  ed10_i_codigo = {$ed10_i_codigo}) ";

    /**
     * Caso exista proxima etapa apos a etapa do aluno,
     * procuramos as equivalencias da mesma
     */
    if ($oProximaEtapa != null) {

      $sWhere .= "     or ed11_i_codigo in (select ed234_i_serieequiv from ";
      $sWhere .= "                     serieequiv ";
      $sWhere .= "                     inner join serie e       on e.ed11_i_codigo       = ed234_i_serie";
      $sWhere .= "                     inner join serie equiva  on equiva.ed11_i_codigo  = ed234_i_serieequiv";
      $sWhere .= "                     inner join ensino ens on e.ed11_i_ensino = ens.ed10_i_codigo";
      $sWhere .= "               where e.ed11_i_codigo = {$oProximaEtapa->getCodigo()}";
    }

    $sWhere .= "   )";
    $sWhere .= ")";
    $sql = $clturma->sql_query_turmaserie( "", " DISTINCT ".$campos, "ed57_c_descr", $sWhere );
    db_lovrot(@$sql,15,"()","",$funcao_js);
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>