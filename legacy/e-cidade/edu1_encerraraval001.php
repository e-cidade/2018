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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("std/DBDate.php");
require_once("classes/db_cursoedu_classe.php");

db_postmemory($_POST);

$oDaoProcedimento        = new cl_procedimento();
$oDaoDiarioFinal         = new cl_diariofinal();
$oDaoDiario              = new cl_diario();
$oDaoRegencia            = new cl_regencia();
$oDaoRegenciaPeriodo     = new cl_regenciaperiodo();
$oDaoTurma               = new cl_turma();
$oDaoTurmaSerieRegimeMat = new cl_turmaserieregimemat();
$oDaoSerieRegimeMat      = new cl_serieregimemat();
$oDaoMatricula           = new cl_matricula();
$oDaoMatriculaMov        = new cl_matriculamov();
$oDaoHistorico           = new cl_historico();
$oDaoHistoricoMpd        = new cl_historicompd();
$oDaoHistoricoMps        = new cl_historicomps();
$oDaoHistMpsDisc         = new cl_histmpsdisc();
$oDaoAlunoPossib         = new cl_alunopossib();
$oDaoBaseMps             = new cl_basemps();
$oDaoEscolaBase          = new cl_escolabase();
$oDaoSerie               = new cl_serie();
$clcurso                 = new cl_curso;

$db_botao   = true;
$escola     = db_getsession("DB_coddepto");
$resultedu  = eduparametros(db_getsession("DB_coddepto"));

$sCamposRegencia   = " ed59_i_turma,ed57_c_descr,ed52_c_descr,ed57_i_calendario as calend, ";
$sCamposRegencia  .= " fc_codetapaturma(ed59_i_turma) as serie1";
$sSqlRegencia      = $oDaoRegencia->sql_query("",$sCamposRegencia,"","ed59_i_turma = $turma");
$rsResultRegencia  = $oDaoRegencia->sql_record($sSqlRegencia);
db_fieldsmemory($rsResultRegencia,0);

$sSqlCurso = $clcurso->sql_query("","*", "", "ed29_i_codigo = $curso");
$rsCurso   = $clcurso->sql_record($sSqlCurso);

db_fieldsmemory($rsCurso,0);
$iCodigoEnsino               = $ed29_i_ensino;
$oParametroProgressaoParcial = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($escola);
$iQuantidadeDisciplinas      = 0;
$sLabelAprovado              = 'APROVADO';
$sLabelReprovado             = 'REPROVADO';
$sLabelAprovadoParcial       = 'APROVADO PARCIAL';

$oEtapa             = EtapaRepository::getEtapaByCodigo( $codserieregencia );
$oTurma             = TurmaRepository::getTurmaByCodigo( $turma );
$iAnoTurma          = $oTurma->getCalendario()->getAnoExecucao();
$oProcedimento      = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
$lCargaHorariaTotal = false;

if( $oProcedimento->getFormaCalculoFrequencia() == 2 ) {
  $lCargaHorariaTotal = true;
}

if ($oParametroProgressaoParcial->getQuantidadeDisciplina() != null) {
  $iQuantidadeDisciplinas = $oParametroProgressaoParcial->getQuantidadeDisciplina();
}
$aTermosAprovado       = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A', $iAnoTurma);
if (count($aTermosAprovado) > 0) {
  $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
}

$aTermosReprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R', $iAnoTurma);
if (count($aTermosReprovado) > 0) {
  $sLabelReprovado = $aTermosReprovado[0]->sDescricao;
}

$aTermosAprovadoParcial = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'P', $iAnoTurma);
if (count($aTermosAprovadoParcial) > 0) {
  $sLabelAprovadoParcial = $aTermosAprovadoParcial[0]->sDescricao;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;

}
.alunopq{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
 padding-top: 0px;
 padding-bottom: 0px;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
$sCamposRegPer       = "ed78_i_regencia,ed78_i_procavaliacao,ed78_i_aulasdadas,ed09_c_descr,ed232_c_descr,ed59_i_ordenacao";
$sWhereRegPer        = " ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $codserieregencia AND ed59_c_freqglob!='A' ";
$sWhereRegPer       .= " AND ed09_c_somach = 'S' AND ed59_c_condicao = 'OB'";
$sSqlRegenciaPeriodo = $oDaoRegenciaPeriodo->sql_query("",$sCamposRegPer,"ed59_i_ordenacao",$sWhereRegPer);
$rsResultRegPeriodo  = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);
$embranco            = "";
$mensagem            = "";
$sep                 = "";
$faltaaprov          = false;
$mudaregencia        = "";

for ($x = 0; $x < $oDaoRegenciaPeriodo->numrows; $x++) {

  db_fieldsmemory($rsResultRegPeriodo,$x);
  if ($mudaregencia != $ed78_i_regencia) {

    $mensagem    .= "<hr>";
    $mudaregencia = $ed78_i_regencia;

  }

  if ($ed78_i_aulasdadas == "") {

    $embranco .= "S";
    $mensagem .= $sep." * Falta informar aulas dadas no período $ed09_c_descr para disciplina $ed232_c_descr";
    $sep       = "|";

  }

}

if (strstr($embranco,"S")) {

  $mensagens = explode("|",$mensagem);
  ?>
  <table border='0' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
   <tr>
    <td class='titulo'>
      Não foi possível encerrar as avaliações da turma <?=$ed57_c_descr?>
    </td>
   </tr>
   <?
   for ($x = 0; $x < count($mensagens); $x++) {

     ?>
     <tr>
      <td class='aluno'>
       <?=$mensagens[$x]?>
      </td>
     </tr>
   <?

   }
 ?></table><?

} else {

  $sSqlMatricula     = " SELECT DISTINCT ed60_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome,ed60_i_numaluno ";
  $sSqlMatricula    .= "       FROM matricula ";
  $sSqlMatricula    .= "            inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sSqlMatricula    .= "            inner join aluno on ed47_i_codigo = ed60_i_aluno ";
  $sSqlMatricula    .= "            inner join diario on ed95_i_aluno = ed47_i_codigo ";
  $sSqlMatricula    .= "            inner join regencia on ed59_i_codigo = ed95_i_regencia ";
  $sSqlMatricula    .= "            inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
  $sSqlMatricula    .= "            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
  $sSqlMatricula    .= "            inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
  $sSqlMatricula    .= "       WHERE ed60_i_turma = $ed59_i_turma ";
  $sSqlMatricula    .= "             AND ed221_i_serie = $codserieregencia ";
  $sSqlMatricula    .= "             AND ed60_c_situacao = 'MATRICULADO' ";
  $sSqlMatricula    .= "             AND ed60_c_concluida = 'N' ";
  $sSqlMatricula    .= "             AND ed95_c_encerrado = 'N' ";
  $sSqlMatricula    .= "             AND ed59_c_condicao = 'OB' ";
  $sSqlMatricula    .= "             AND ed221_c_origem = 'S' ";
  $sSqlMatricula    .= "             AND not exists (select 1 from alunonecessidade where ed214_i_aluno = ed47_i_codigo)";
  $sSqlMatricula    .= "       ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome) ";

  $rsResultMatricula = db_query($sSqlMatricula);
  $iLinhasMatricula  = pg_num_rows($rsResultMatricula);
  $naopode           = 0;
  $sep               = "";
  if ($iLinhasMatricula > 0) {

    db_inicio_transacao();
    $aPendenciasGeral  = array();
    for ($x = 0; $x < $iLinhasMatricula; $x++) {

      $oDadosAluno = db_utils::fieldsMemory($rsResultMatricula, $x);
      $oMatricula  = new Matricula($oDadosAluno->ed60_i_codigo);
      $oDiario     = $oMatricula->getDiarioDeClasse();
      $aPendencias = $oDiario->getPendenciasEncerramento();
      if (count($aPendencias) > 0) {

        $oPendencia            = new stdClass();
        $oPendencia->aluno     = $oDadosAluno->ed47_v_nome;
        $oPendencia->matricula = $oDadosAluno->ed60_i_codigo;
        $oPendencia->ordem     = $oDadosAluno->ed60_i_numaluno;
        $oPendencia->detalhe   = implode("<br>", $aPendencias);
        $aPendenciasGeral[]    = $oPendencia;
      }
      unset($oMatricula);
    }
    db_inicio_transacao(true);
    $faltaaprov = true;
    if (count($aPendenciasGeral) > 0) {
      ?>

      <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
       <tr>
        <td class='titulo' colspan="3">
         Não foi possível encerrar as avaliações dos seguintes alunos:
        </td>
       </tr>
       <tr>
        <td class='cabec1'>N°</td>
        <td class='cabec1'>Aluno</td>
        <td class='cabec1'>Detalhes</td>
       </tr>
       <?
       $cor1 = "#f3f3f3";
       $cor2 = "#DBDBDB";
       $cor  = "";
       foreach ($aPendenciasGeral as $oPendencia) {

         $naopode .= $sep.$oPendencia->matricula;
         $sep      = ",";

         if ($cor == $cor1) {
           $cor = $cor2;
         } else {
           $cor = $cor1;
         }
         ?>
         <tr bgcolor="<?=$cor?>">
          <td class='aluno'>
           <?=$oPendencia->matricula==""||$oPendencia->ordem==null?"&nbsp;":$oPendencia->ordem?>
          </td>
          <td class='aluno'>
           <?=$oPendencia->aluno?>
          </td>
          <td class='aluno'>
          <?=$oPendencia->detalhe?>
      </td>
     </tr>
     <?
    }
    ?></table><br><?
    }
 }
 $sSqlMatri     = " SELECT DISTINCT ";
 $sSqlMatri    .= "        ed60_i_codigo,  ";
 $sSqlMatri    .= "        ed60_i_numaluno, ";
 $sSqlMatri    .= "        ed60_i_aluno, ";
 $sSqlMatri    .= "        ed60_c_situacao, ";
 $sSqlMatri    .= "        to_ascii(ed47_v_nome) as ed47_v_nome, ";
 $sSqlMatri    .= "        ed221_i_serie as etapaorigem ";
 $sSqlMatri    .= "        FROM matricula ";
 $sSqlMatri    .= "             inner join aluno on ed47_i_codigo = ed60_i_aluno ";
 $sSqlMatri    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
 $sSqlMatri    .= "             inner join diario on ed95_i_aluno = ed47_i_codigo ";
 $sSqlMatri    .= "             inner join regencia on ed59_i_codigo = ed95_i_regencia ";
 $sSqlMatri    .= "             inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
 $sSqlMatri    .= "             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
 $sSqlMatri    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
 $sSqlMatri    .= "        WHERE ed60_i_turma = $ed59_i_turma ";
 $sSqlMatri    .= "              AND ed59_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed221_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed60_i_codigo not in ($naopode) ";
 $sSqlMatri    .= "              AND ed60_c_situacao = 'MATRICULADO' ";
 $sSqlMatri    .= "              AND ed60_c_ativa = 'S' ";
 $sSqlMatri    .= "              AND ed60_c_concluida = 'N' ";
 $sSqlMatri    .= "              AND ed95_c_encerrado = 'N' ";
 $sSqlMatri    .= "              AND ed59_c_condicao = 'OB' ";
 $sSqlMatri    .= "              AND ed221_c_origem = 'S' ";
 $sSqlMatri    .= "              AND ed74_c_resultadofreq != '' ";
 $sSqlMatri    .= "              AND ed74_c_resultadoaprov != ''";
 $sSqlMatri    .= " UNION ";
 $sSqlMatri    .= " SELECT DISTINCT ";
 $sSqlMatri    .= "        ed60_i_codigo, ";
 $sSqlMatri    .= "        ed60_i_numaluno, ";
 $sSqlMatri    .= "        ed60_i_aluno, ";
 $sSqlMatri    .= "        ed60_c_situacao, ";
 $sSqlMatri    .= "        to_ascii(ed47_v_nome) as ed47_v_nome, ";
 $sSqlMatri    .= "        ed221_i_serie as etapaorigem ";
 $sSqlMatri    .= "        FROM matricula ";
 $sSqlMatri    .= "             inner join aluno on ed47_i_codigo = ed60_i_aluno ";
 $sSqlMatri    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
 $sSqlMatri    .= "             inner join turma on ed57_i_codigo = ed60_i_turma ";
 $sSqlMatri    .= "             inner join regencia on ed59_i_turma = ed57_i_codigo ";
 $sSqlMatri    .= "             inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
 $sSqlMatri    .= "             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
 $sSqlMatri    .= "        WHERE ed60_i_turma = $ed59_i_turma ";
 $sSqlMatri    .= "              AND ed59_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed221_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'MATRICULADO' ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'AVANÇADO' ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'CLASSIFICADO' ";
 $sSqlMatri    .= "              AND ed60_c_ativa = 'S' ";
 $sSqlMatri    .= "              AND ed60_c_concluida = 'N' ";
 $sSqlMatri    .= "              AND ed59_c_condicao = 'OB' ";
 $sSqlMatri    .= "              AND ed221_c_origem = 'S' ";
 $sSqlMatri    .= "              ORDER BY ed60_i_numaluno,ed47_v_nome ";

 $rsResultMatri = db_query($sSqlMatri);
 $iLinhasMatri  = pg_num_rows($rsResultMatri);

  if ($iLinhasMatri > 0) {

  ?>
  <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
  <tr>
   <td class='titulo' colspan="3">
    O sistema vai encerrar as matrículas dos seguintes alunos desta turma:
   </td>
  </tr>
  <tr>
   <td class='cabec1'>N°</td>
   <td class='cabec1'>Aluno</td>
   <td class='cabec1'>Resultado Final</td>
  </tr>
  <?
  $cor1   = "#f3f3f3";
  $cor2   = "#DBDBDB";
  $cor    = "";
  $alunos = "";
  $sep    = "";
  for ($x = 0; $x < $iLinhasMatri; $x++) {

    db_fieldsmemory($rsResultMatri,$x);
    $alunos .= $sep.$ed60_i_codigo;
    $sep     = ",";

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }
   ?>
   <tr bgcolor="<?=$cor?>">
    <td class='aluno'><?=$ed60_i_numaluno==""||$ed60_i_numaluno==null?"&nbsp;":$ed60_i_numaluno?></td>
    <td class='aluno'><?=$ed60_i_aluno?> - <?=$ed47_v_nome?></td>
    <td class='aluno'>
     <?
     if ($ed60_c_situacao != "MATRICULADO") {
       echo trim($ed60_c_situacao);
     } else {

       $sSqlDia     = " SELECT ed95_i_codigo ";
       $sSqlDia    .= "        FROM diario  ";
       $sSqlDia    .= "             inner join aluno on ed47_i_codigo = ed95_i_aluno ";
       $sSqlDia    .= "             inner join matricula on ed60_i_aluno = ed47_i_codigo ";
       $sSqlDia    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
       $sSqlDia    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
       $sSqlDia    .= "             inner join regencia on ed59_i_codigo = ed95_i_regencia ";
       $sSqlDia    .= "        WHERE ed95_i_aluno = $ed60_i_aluno ";
       $sSqlDia    .= "              AND ed95_i_calendario = $calend ";
       $sSqlDia    .= "              AND ed95_i_serie = $etapaorigem ";
       $sSqlDia    .= "              AND ed59_i_serie = $codserieregencia ";
       $sSqlDia    .= "              AND ed221_i_serie = $codserieregencia ";
       $sSqlDia    .= "              AND ed60_i_codigo = $ed60_i_codigo ";
       $sSqlDia    .= "              AND ed59_c_condicao = 'OB' ";
       $sSqlDia    .= "              AND ed221_c_origem = 'S' ";
       $sSqlDia    .= "              AND ed60_i_turma = ed59_i_turma ";
       $sSqlDia    .= "              AND (case when ed59_c_freqglob <> 'F' then ed74_c_resultadofinal != 'A' ";
       $sSqlDia    .= "                        else ed74_c_resultadofreq <> 'A' end)";
       $sSqlDia    .= "              ORDER BY to_ascii(ed47_v_nome) ";
       $rsResultDia = db_query($sSqlDia);
       $iLinhasDia  = pg_num_rows($rsResultDia);

       if ($iLinhasDia == 0) {

         $sSqlCursoEdu =  $clcurso->sql_query("","*","","ed29_i_codigo = $curso");
         $rsCursoEdu   = $clcurso->sql_record($sSqlCursoEdu);
         db_fieldsmemory($rsCursoEdu,0);

         if ($ed29_i_avalparcial == 2) {


  	       $sWhereHistoricoMps  = "ed11_i_codigo = $codserieregencia and ed47_i_codigo = $ed60_i_aluno ";
           $sWhereHistoricoMps .= " and ed29_i_codigo = $curso and ed62_c_resultadofinal='P'";
           $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("", "*", "", $sWhereHistoricoMps);
           $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
           $iLinhasHistoricoMps = $oDaoHistoricoMps->numrows;


  	       if ($iLinhasHistoricoMps > 0) {
  	         $sResultadoFinal = $sLabelAprovado;
  	       } else {
  	       	 $sResultadoFinal = "$sLabelAprovadoParcial";
  	       }

 	     } else {
 	       $sResultadoFinal = "$sLabelAprovado";
 	     }

       } else  {

       	/*if ($iLinhasHistoricoMps > 0) {
  	         echo "APROVADO ";
  	       } else {
  	       	 echo "APROVADO PARCIAL";
  	       }*/
         $sResultadoFinal = "$sLabelReprovado";
       }

       /**
        * Verificamos o total de disciplinas em que o Aluno foi reprovado
        */
       if ($oParametroProgressaoParcial->isHabilitada()) {

         db_inicio_transacao();
         $oMatricula    = new Matricula($ed60_i_codigo);
         $oDiarioClasse = $oMatricula->getDiarioDeClasse();
         db_fim_transacao(false);
         if ($oDiarioClasse->aprovadoComProgressaoParcial()) {
           $sResultadoFinal = " {$sLabelAprovado} (Progressão Parcial / Dependência)";
         }
       }
       echo $sResultadoFinal;
     }
     ?>
    </td>
   </tr>
   <?
  }
  ?>
  <tr bgcolor="#f3f3f3">
   <td align="center" class='aluno' colspan="3">
    <form name="form1" method="post" action="">
    <input id='btnConfirmar' type="button" name="confirmar" value="Confirmar">
    <input id="btnFechar" name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_encerrar<?=$turma?>.hide();">
    <input type="hidden" name="alunos" value="<?=$alunos?>">
    <input type="hidden" name="turma" value="<?=$turma?>">
    <input type="hidden" name="ed57_c_descr" value="<?=$ed57_c_descr?>">
    <input type="hidden" name="codserieregencia" value="<?=$codserieregencia?>">
    </form>
   </td>
  </tr>
  </table><?
 } else if ($iLinhasMatri == 0 && $faltaaprov == false) {
  ?>
  <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
  <tr>
   <td class='titulo'>
    Todos os alunos já possuem avaliações encerradas.
   </td>
  </tr>
  <tr>
   <td align="center">
    <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_encerrar<?=$turma?>.hide();">
   </td>
  </tr>
  <?
 }
}
?>
</body>
</html>
<script>
var oGet    = js_urlToObject();
var aTurmas = new Array();

$('btnConfirmar').onclick = function() {
  encerrarAvaliacoes();
}

/**
 * Encerra as avaliações da turma utilizando o mesmo RPC do encerramento novo
 */
function encerrarAvaliacoes() {

  var oTurma      = {};
  var oParametros = {};
  var oRequisicao = {};

  oTurma.iTurma = oGet.turma;
  oTurma.iEtapa = oGet.codserieregencia;

  aTurmas.push( oTurma );

  oParametros.exec    = 'encerrarAvaliacoes';
  oParametros.aTurmas = aTurmas;

  oRequisicao.method     = "post";
  oRequisicao.parameters = "json=" + Object.toJSON( oParametros );
  oRequisicao.onComplete = retornoEncerrarAvaliacoes;

  js_divCarregando( "Aguarde, encerrando avaliações...", "msgBox" );
  new Ajax.Request( "edu4_encerramentoavaliacao.RPC.php", oRequisicao );
}

/**
 * Verifica se as avaliações foram encerradas, fecha o frame e recarrega o frame das disciplinas da turma
 * @param  {Object} oResponse
 */
function retornoEncerrarAvaliacoes( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  alert( oRetorno.message.urlDecode() );

  if ( oRetorno.status == 1 ) {

    $('btnFechar').click();
    top.corpo.dados.location.reload();
  }
}
</script>