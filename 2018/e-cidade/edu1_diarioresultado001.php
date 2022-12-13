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

require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_utils.php');
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("model/educacao/DBEducacaoTermo.model.php");
db_postmemory($HTTP_POST_VARS);

if (!isset($iTrocaTurma)) {
	$iTrocaTurma = null;
}
/**
 * Função que retorna o mínimo para aprovação
 * @param $iCodProcAvalResult código da tabela procavaliacao ou procresultado, dependendo de $sTipo.
 * @param $iCodDiario código da tabela diario.
 * @param $sTipo se 'A', então $iCodProcAvalResult é o código da tabela procavaliação, senão, é da tabela procresultado
 * @return string mínimo para aprovação, ou null, se a consulta falhar.
 */

function VerAprovAvalAnt($iCodProcAvalResult, $iDiario, $sTipo) {

  $oDao           = $sTipo == 'A' ? db_utils::getdao('diarioavaliacao') : db_utils::getdao('diarioresultado');
  $sCampoAprovMin = $sTipo == 'A' ? 'ed72_c_aprovmin' : 'ed73_c_aprovmin';
  $sCampoProc     = $sTipo == 'A' ? 'ed72_i_procavaliacao' : 'ed73_i_procresultado';
  $sWhere         = $sTipo == 'A' ? 'ed72_i_diario' : 'ed73_i_diario';
  $sWhere        .= " = $iDiario and $sCampoProc = $iCodProcAvalResult ";
  $sSql           = $oDao->sql_query_file(null, $sCampoAprovMin, '', $sWhere);
  $rs             = $oDao->sql_record($sSql);
  if ($oDao->numrows > 0) {
    return db_utils::fieldsmemory($rs, 0)->$sCampoAprovMin;
  }

  return null;

}

function getComboBoxConceito($sDisabled, $sCorDisabled, $lDiarioEncerrado, $iFormaAvaliacao,
		$sValorConceito, $lAmparado, $iCodDiarioResultado = null, $iCont = null) {

	$oDaoConceito = db_utils::getDao("conceito");
	$sCombo  = '<select name="ed73_c_valorconceito'.$iCont.'" style="background:'.$sCorDisabled;
	$sCombo .= ';width:50px;height:15px;font-size:10px;text-align:center;padding:0px;" ';

	if ($lAmparado || $lDiarioEncerrado) {
		$sCombo .= 'onclick="alert(\'Aluno já possui avaliações encerradas para esta disciplina!\')" ';
	} else {
		$sCombo .= 'onchange="js_conceito(this.value,'.$iCodDiarioResultado.",'NIVEL');\"";
	}
	$sCombo .= '>';
	$sCombo .= ($lDiarioEncerrado ? 'disabled' : $sDisabled);
	$sCombo .= '<option value=""></option> ';

	/* Busco os conceitos de acordo com a forma de avaliação */
	$sSql = $oDaoConceito->sql_query(null, 'ed39_c_conceito', 'ed39_i_sequencia', "ed39_i_formaavaliacao = $iFormaAvaliacao" );
  $rs   = $oDaoConceito->sql_record($sSql);
  for ($iCont = 0; $iCont < $oDaoConceito->numrows; $iCont++) {

    $sConceito = trim(db_utils::fieldsmemory($rs, $iCont)->ed39_c_conceito);
    $sSelected = ($sConceito == trim($sValorConceito) ? 'selected' : '');
    $sCombo   .= '<option value="'.$sConceito.'" '.$sSelected.' >'.$sConceito.'</option>';

  }
  $sCombo .= '</select>';

  return $sCombo;

}


function getHtmlParecerAluno($sDisabled, $sCorDisabled, $oDadosMat, $iTurma, $lAmparado,
                             $sOnchange = '', $iCont = null) {

  global $iCodigoEnsino;
  $sPar   = empty($oDadosMat->ed73_t_parecer) ? '' : substr($oDadosMat->ed73_t_parecer, 0, 20).'...';
  $sHtml  = '<input name="ed73_t_parecer'.$iCont.'" value="'.mb_strtoupper($sPar).'" type="text" size="20" maxlength="20" ';
  $sHtml .= 'style="background:'.$sCorDisabled.';height:14px;text-align:left;border: 1px solid #000000;';
  $sHtml .= 'font-size:11px;padding:0px;" onclick="js_parecer(this, '.$oDadosMat->ed73_i_codigo.',';
  $sHtml .= $oDadosMat->ed73_i_procresultado.", '".$oDadosMat->ed42_c_descr."', '".str_replace("'", "", $oDadosMat->ed47_v_nome);
  $sHtml .= "' , '".$oDadosMat->ed95_c_encerrado."', $iTurma, ".$oDadosMat->ed47_i_codigo.');" ';
  $sHtml .= (trim($oDadosMat->ed95_c_encerrado) == 'S' ? 'readonly' : $sDisabled).'>';
  $sHtml .= '<select name="ed73_c_aprovmin" style="background:'.$sCorDisabled.';width:95px;height:17px;';
  $sHtml .= '                                      font-size:10px;padding:0px;" ';

  if ($lAmparado || (isset($lEncerrado) && $lEncerrado)) {

    $sHtml .= 'onclick="alert(\'Aluno já possui avaliações encerradas para esta disciplina!\')" ';
    $sHtml .= (trim($oDadosMat->ed95_c_encerrado) == 'S' ? 'disabled' : $sDisabled);

  } else {
    $sHtml .= $sOnchange;
  }
  $sHtml .= '>';
  $sLabelAprovado   = 'APROVADO';
  $sLabelReprovado  = 'REPROVADO';

  $oTurma    = TurmaRepository::getTurmaByCodigo( $iTurma );
  $iAnoTurma = $oTurma->getCalendario()->getAnoExecucao();

  $aTermosAprovado  = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A', $iAnoTurma);
  if (count($aTermosAprovado) > 0) {
    $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
  }
  $aTermosReprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R', $iAnoTurma);
  if (count($aTermosReprovado) > 0) {
    $sLabelReprovado = $aTermosReprovado[0]->sDescricao;
  }

  if (isset($oDadosMat->lAprovadoDefault) && $oDadosMat->lAprovadoDefault == true) {
    $oDadosMat->ed73_c_aprovmin = "S";
  }

  if ((isset($lAprovAuto) && $lAprovAuto)) {
    $sHtml .= "<option value='S' ".($oDadosMat->ed73_c_aprovmin == 'S' ? 'selected' : '').">{$sLabelAprovado}</option>";
  } else {

    $lAprovado = false;
    if ( ($oDadosMat->ed73_c_aprovmin == 'S' || $oDadosMat->ed73_c_aprovmin == '') && $oDadosMat->lAlunoNecessidade) {
      $lAprovado = true;
    }


    $oResultado          = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosMat->ed73_i_procresultado);
    $oAvalicaoDependente = AvaliacaoPeriodicaRepository::getAvaliacaoDependente($oResultado);
    $sHtml .= "<option value='' ".($oDadosMat->ed73_c_aprovmin == '' ? 'selected' : '').'></option>';
    $sHtml .= "<option value='S' ".($lAprovado ? 'selected' : '').">{$sLabelAprovado}</option>";
    $sHtml .= "<option value='N' ".($oDadosMat->ed73_c_aprovmin == 'N' ? 'selected' : '').">{$sLabelReprovado}</option>";

    if (!empty($oAvalicaoDependente)) {
      $sHtml .= "<option value='R' ".(!empty($oDadosMat->tem_recuperacao) ? " selected ":'').">EM RECUPERAÇÃO</option>";
    }
  }

  $sHtml .= '</select>';

  return $sHtml;

}

function getSemDecimal($fDados) {
  return floor($fDados);
}


$oDaoDiarioResultado  = db_utils::getdao('diarioresultado');
$oDaoDiarioAvaliacao  = db_utils::getdao('diarioavaliacao');
$oDaoDiarioFinal      = db_utils::getdao('diariofinal');
$oDaoRegencia         = db_utils::getdao('regencia');
$oDaoConceito         = db_utils::getdao('conceito');
$oDaoProcResultado    = db_utils::getdao('procresultado');
$oDaoAvalCompoeRes    = db_utils::getdao('avalcompoeres');
$oDaoResCompoeRes     = db_utils::getdao('rescompoeres');
$oDaoMatricula        = db_utils::getdao('matricula');
$db_botao             = true;
$sCorSim              = "#BBFFBB";
$sCorNao              = "#FF9B9B";
$lCasasDecimais       = eduparametros(db_getsession('DB_coddepto')) == 'S' ? true : false;
$lPermiteNotaEmBranco = VerParametroNota(db_getsession('DB_coddepto')) == 'S' ? true : false;

/* Busco dados referentes ao resultado (definição e parâmetros do resultado) */
$sCampos                   = 'ed43_c_minimoaprov as minimoaprov, trim(ed43_c_obtencao) as obtencao, ';
$sCampos                  .= 'ed43_c_arredmedia as arredmedia, trim(ed37_c_tipo) as tipoaval ';
$sSql                      = $oDaoProcResultado->sql_query("", $sCampos, "", "ed43_i_codigo = $ed43_i_codigo");
$rs                        = $oDaoProcResultado->sql_record($sSql);
$oDadosResultado           = db_utils::fieldsmemory($rs, 0);

/* Obtenho os dados da regência e relacionados */
$sSql           = $oDaoRegencia->sql_query(null, '*', '', "ed59_i_codigo = $regencia");
$rs             = $oDaoRegencia->sql_record($sSql);
$oDadosRegencia = db_utils::fieldsmemory($rs, 0);
$iAno           = $oDadosRegencia->ed52_i_ano;
if ($oDadosResultado->obtencao != 'AT') {

  /* Obtenho os períodos de avaliação que compõem o resultado */
  $sSql                  = $oDaoAvalCompoeRes->sql_query_file(null, 'ed44_i_procavaliacao as componente', '',
                                                              "ed44_i_procresultado = $ed43_i_codigo"
                                                             );
  $rs                    = $oDaoAvalCompoeRes->sql_record($sSql);
  $iNumPeriodos          = $oDaoAvalCompoeRes->numrows;
  $sProcAvalCompoeResult = "";
  $sVir                  = "";
  for ($iCont = 0; $iCont < $oDaoAvalCompoeRes->numrows; $iCont++) {

    $sProcAvalCompoeResult .= $sVir.db_utils::fieldsmemory($rs, $iCont)->componente;
    $sVir                   = ',';

  }
  $sProcAvalCompoeResult = empty($sProcAvalCompoeResult) ? 0 : $sProcAvalCompoeResult;

  /* Obtenho os resultado de avaliações que compõem o resultado (sim, um resultado pode influenciar em outro) */
  $sSql                    = $oDaoResCompoeRes->sql_query_file(null, 'ed68_i_procresultcomp as componente',
                                                               '', "ed68_i_procresultado = $ed43_i_codigo"
                                                              );
  $rs                      = $oDaoResCompoeRes->sql_record($sSql);
  $sProcResultCompoeResult = "";
  $sVir                    = "";
  for ($iCont = 0; $iCont < $oDaoResCompoeRes->numrows; $iCont++) {

    $sProcResultCompoeResult .= $sVir.db_utils::fieldsmemory($rs, $iCont)->componente;
    $sVir                     = ',';

  }
  $sProcResultCompoeResult = $sProcResultCompoeResult == "" ? 0 : $sProcResultCompoeResult;

  /* Obtenho o código da ultimo componente do resultado, pois é necessário quando:
     a) Cálculo obtencao = ULTIMA NOTA
     b) Cálculo obtencao = ULTIMA NÍVEL
  */
  $sCampos                 = "codigo as codigoultimocomponente, tipo as tipoultimo";
  $sSql                    = $oDaoProcResultado->sql_query_procavalres(null, $sCampos, 'sequencia desc',
                                                                       "ed41_i_codigo in ($sProcAvalCompoeResult)",
                                                                       "ed43_i_codigo in ($sProcResultCompoeResult)"
                                                                      );
  $rs                      = $oDaoProcResultado->sql_record($sSql);
  $iCodigoUltimoComponente = -1;
  $iTipoUltimoComponente   = 0;
  if ($oDaoProcResultado->numrows > 0) {

    $iCodigoUltimoComponente = db_utils::fieldsmemory($rs, 0)->codigoultimocomponente;
    $iTipoUltimoComponente   = db_utils::fieldsmemory($rs, 0)->tipoultimo;
  }
}

if (isset($alterar)) {

  db_inicio_transacao();
  /* Obtenho alguns dados da avaliação */
  $sSql            = $oDaoDiarioResultado->sql_query(null, ' ed43_i_formaavaliacao, '.
                                                     'ed43_c_minimoaprov as ed37_c_minimoaprov', '',
                                                     " ed73_i_codigo = $codigo"
                                                    );
  $rs              = $oDaoDiarioResultado->sql_record($sSql);
  $oDadosAvaliacao = db_utils::fieldsmemory($rs, 0);

  /* Inicializo algumas variável em branco, pois nem todas serão setadas com algum valor */
  $sConceito = '';
  $sNota     = "";
  $sMinimo   = '';
  if ($tipo == 'NIVEL') {

    $sConceito = $valor;
    if (!empty($sConceito)) {

      /* Busco a ordem do conceito sendo alterado */
      $sSql         = $oDaoConceito->sql_query(null, 'ed39_i_sequencia', '',
                                               "ed39_i_formaavaliacao = $oDadosAvaliacao->ed43_i_formaavaliacao ".
                                               "and ed39_c_conceito = '$sConceito'"
                                              );
      $rs           = $oDaoConceito->sql_record($sSql);
      $iSeqConceito = db_utils::fieldsmemory($rs, 0)->ed39_i_sequencia;

      /* Busco a ordem do conceito mínimo para aprovação */
      $sSql            = $oDaoConceito->sql_query(null, 'ed39_i_sequencia', '',
                                                  "ed39_i_formaavaliacao = $oDadosAvaliacao->ed43_i_formaavaliacao ".
                                                  "and ed39_c_conceito = '$oDadosAvaliacao->ed37_c_minimoaprov'"
                                                 );
      $rs              = $oDaoConceito->sql_record($sSql);
      $iSeqConceitoMin = db_utils::fieldsmemory($rs, 0)->ed39_i_sequencia;

      /* Verifico se o conceito informado é maior ou igual ao mínimo exigido */
      if ($iSeqConceito >= $iSeqConceitoMin) {
        $sMinimo = 'S';
      } else {
        $sMinimo = 'N';
      }

    } else { // Conceito não informado (em branco)
      $sMinimo = 'N';
    }

  } elseif ($tipo == 'NOTA') {

    $sNota = (string)$valor;
    if (!empty($sNota)) {

      if ($sNota >= $oDadosAvaliacao->ed37_c_minimoaprov) {
        $sMinimo = 'S';
      } else {
        $sMinimo = 'N';
      }

    } else { // Nota não informada (em branco)
      $sMinimo = 'N';
    }
    $nNota = ArredondamentoNota::arredondar($sNota, $iAno);
  } elseif ($tipo == 'PARECER') {

    $sMinimo = 'S';
  }

  $oDaoDiarioResultado->ed73_c_aprovmin      = $sMinimo;
  $oDaoDiarioResultado->ed73_c_valorconceito = $sConceito;
  $oDaoDiarioResultado->ed73_i_valornota     = "{$sNota}";
  $oDaoDiarioResultado->ed73_i_codigo        = $codigo;
  $oDaoDiarioResultado->alterar($codigo);

  if ($oDaoDiarioResultado->erro_status == '0') {
    db_msgbox(str_replace("'", "\'", $oDaoDiarioResultado->erro_msg));
  } else {

    /* Atualizo a data de ultima atualização da regência */
    $dDataAtualiz                     = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoRegencia->ed59_d_dataatualiz = $dDataAtualiz;
    $oDaoRegencia->ed59_i_codigo      = $regencia;
    $oDaoRegencia->alterar($regencia);
    if ($oDaoRegencia->erro_status == '0') {
      db_msgbox(str_replace("'", "\'", $oDaoRegencia->erro_msg));
    }
  }

  db_fim_transacao();

}
if (isset($aprovminimo)) {

  db_inicio_transacao();

  $oDaoDiarioResultado->ed73_c_aprovmin = $valor;
  $oDaoDiarioResultado->ed73_i_codigo   = $codigo;
  $oDaoDiarioResultado->alterar($codigo);

  $sSql           = $oDaoDiarioResultado->sql_query(null, 'ed95_i_codigo', '', " ed73_i_codigo = $codigo");
  $rs             = $oDaoDiarioResultado->sql_record($sSql);
  $iCodDiario     = db_utils::fieldsmemory($rs, 0)->ed95_i_codigo;
  $sValorAprov    = '';
  $sValorDescrito = '';

  $oDaoResultadoRecuperacao = new cl_diarioresultadorecuperacao();
  $oDaoResultadoRecuperacao->excluir(null, "ed116_diarioresultado = {$codigo}");
  if ($valor == 'S') {

    $sValorAprov    = 'A';
    $sValorDescrito = 'Parecer';

  } elseif ($valor == 'N') {

    $sValorAprov    = 'R';
    $sValorDescrito = 'Parecer';

  } else if ($valor == 'R') {

    $sValorAprov    = '';
    $sValorDescrito = '';

    $oDaoResultadoRecuperacao->ed116_diarioresultado = $codigo;
    $oDaoResultadoRecuperacao->incluir(null);

  }

  $sSql              = $oDaoDiarioFinal->sql_query(null, 'ed74_c_resultadofreq, ed74_i_procresultadofreq, '.
                                                   'ed74_i_percfreq, ed74_i_codigo', '',
                                                   " ed74_i_diario = $iCodDiario"
                                                  );

  $rs                = $oDaoDiarioFinal->sql_record($sSql);
  $oDadosDiarioFinal = db_utils::fieldsmemory($rs, 0);
  if ($oDadosDiarioFinal->ed74_c_resultadofreq == 'A' && $sValorAprov == 'A') {
    $sResFinal = 'A';
  } elseif ($oDadosDiarioFinal->ed74_c_resultadofreq == '' || $sValorAprov == '') {
    $sResFinal = '';
  } else {
    $sResFinal = 'R';
  }

  $oDadosDiarioFinal->ed74_i_procresultadofreq = $oDadosDiarioFinal->ed74_i_procresultadofreq == '' ?
                                                   'null' : $oDadosDiarioFinal->ed74_i_procresultadofreq;
  $oDadosDiarioFinal->ed74_i_percfreq          = $oDadosDiarioFinal->ed74_i_percfreq == '' ?
                                                   'null' : $oDadosDiarioFinal->ed74_i_percfreq;


  $oDaoDiarioFinal->ed74_i_procresultadoaprov  = $ed43_i_codigo;
  $oDaoDiarioFinal->ed74_c_resultadoaprov      = $sValorAprov;
  $oDaoDiarioFinal->ed74_c_valoraprov          = $sValorDescrito;
  $oDaoDiarioFinal->ed74_i_procresultadofreq   = $oDadosDiarioFinal->ed74_i_procresultadofreq;
  $oDaoDiarioFinal->ed74_c_resultadofreq       = $oDadosDiarioFinal->ed74_c_resultadofreq;
  $oDaoDiarioFinal->ed74_i_percfreq            = $oDadosDiarioFinal->ed74_i_percfreq;
  $oDaoDiarioFinal->ed74_c_resultadofinal      = $sResFinal;
  $oDaoDiarioFinal->ed74_i_codigo              = $oDadosDiarioFinal->ed74_i_codigo;
  $oDaoDiarioFinal->alterar($oDadosDiarioFinal->ed74_i_codigo);

  if ($oDaoDiarioFinal->erro_status == '0') {
    db_msgbox(str_replace("'", "\'", $oDaoDiarioFinal->erro_msg));
  } else {

    /* Atualizo a data de ultima atualização da regência */
    $dDataAtualiz                     = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoRegencia->ed59_d_dataatualiz = $dDataAtualiz;
    $oDaoRegencia->ed59_i_codigo      = $regencia;
    $oDaoRegencia->alterar($regencia);
    if ($oDaoRegencia->erro_status == '0') {
      db_msgbox(str_replace("'", "\'", $oDaoRegencia->erro_msg));
    }
  }
  unset($GLOBALS['HTTP_POST_VARS']['ed73_c_aprovmin']);
  unset($GLOBALS['HTTP_GET_VARS']['ed73_c_aprovmin']);
  db_fim_transacao();

?>
  <script>
   // parent.iframe_RF.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>"+"&iTrocaTurma=<?=$iTrocaTurma?>";
  </script>
<?
  $valoralterado = $valor;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo {

  font-size: 11;
  color: #DEB887;
  background-color:#444444;
  font-weight: bold;

}
.cabec1 {

  font-size: 11;
  color: #000000;
  background-color:#999999;
  font-weight: bold;

}
.aluno {

  color: #000000;
  font-family : Tahoma;
  font-size: 9;

}
.alunopq {

  color: #000000;
  font-family : Tahoma;
  font-size: 9;
  padding-top: 0px;
  padding-bottom: 0px;

}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<input name="ed43_i_codigo" type="hidden" value="<?=$ed43_i_codigo?>">
<input name="regencia" type="hidden" value="<?=$regencia?>">
<?
/* Busco informações dos alunos matriculados na turma para exibir no formulário */

$sCampos  = 'diariofinal.*, matricula.ed60_c_situacao, matricula.ed60_c_concluida, matricula.ed60_i_codigo, matricula.ed60_matricula,';
$sCampos .= 'matricula.ed60_c_ativa, matricula.ed60_d_datamatricula, ';
$sCampos .= 'matricula.ed60_i_numaluno, matricula.ed60_c_parecer, ';
$sCampos .= 'aluno.ed47_v_nome, aluno.ed47_i_codigo,';
$sCampos .= 'resultado.ed42_c_descr, resultado.ed42_c_abrev, ';
$sCampos .= 'formaavaliacao.ed37_c_tipo, formaavaliacao.ed37_i_menorvalor, ';
$sCampos .= 'formaavaliacao.ed37_i_maiorvalor, formaavaliacao.ed37_i_variacao, ';
$sCampos .= 'procresultado.ed43_i_formaavaliacao, ';
$sCampos .= 'amparo.ed81_c_todoperiodo, amparo.ed81_i_justificativa, amparo.ed81_i_convencaoamp, ';
$sCampos .= 'diario.ed95_c_encerrado, ed116_sequencial as tem_recuperacao,';
$sCampos .= 'convencaoamp.*, diarioresultado.*, ';
$sCampos .= "to_char(matricula.ed60_d_datasaida,'DD/MM/YYYY') as datasaida ";

$sWhere   = "    diario.ed95_i_regencia = $regencia ";
$sWhere  .= "AND diarioresultado.ed73_i_procresultado = $ed43_i_codigo ";
$sWhere  .= "AND matricula.ed60_i_turma = ".$oDadosRegencia->ed59_i_turma." ";
$sWhere  .= "AND matriculaserie.ed221_c_origem = 'S' ";

if ($iTrocaTurma == 1) {
  $sWhere .= "AND ed60_c_situacao <> 'TROCA DE TURMA'";
}

$sOrderBy = " matricula.ed60_i_numaluno, to_ascii(aluno.ed47_v_nome), matricula.ed60_c_ativa ";

$sSql     = $oDaoMatricula->sql_query_matricula_resultado(null, $sCampos, $sOrderBy, $sWhere);
$rsMat    = $oDaoMatricula->sql_record($sSql);

/* Obtenho os dados do primeiro registro, pois algumas informações são comuns, como a forma de avaliação,
   que é necessária antes de começar a processar os dados de cada aluno / matrícula individualmente.
*/
$oDadosMat              = db_utils::fieldsmemory($rsMat, 0);
$oDadosMat->ed37_c_tipo = trim($oDadosMat->ed37_c_tipo);

$iCodigoEnsino = $oDadosRegencia->ed12_i_ensino;
/* Títulos */
$sTitulo1  = '&nbsp;'.$oDadosRegencia->ed232_c_descr.' - '.$oDadosMat->ed42_c_descr.' <br> Turma ';
$sTitulo1 .= $oDadosRegencia->ed57_c_descr.' - '.$oDadosRegencia->ed11_c_descr;
$sTitulo1 .= ' - Calendário '.$oDadosRegencia->ed52_c_descr;

/* Determino o nome da forma de obetenção da nota */
if ($oDadosMat->ed37_c_tipo == 'NOTA') {

  if ($oDadosResultado->obtencao == 'AT') {
    $sFormaObtencao = 'ATRIBUÍDO';
  } elseif ($oDadosResultado->obtencao == 'ME') {
    $sFormaObtencao = 'MÉDIA ARITMÉTICA';
  } elseif ($oDadosResultado->obtencao == 'SO') {
    $sFormaObtencao = 'SOMA';
  } elseif ($oDadosResultado->obtencao == 'MN') {
    $sFormaObtencao = 'MAIOR NOTA';
  } elseif ($oDadosResultado->obtencao == 'UN') {
    $sFormaObtencao = 'ÚLTIMA NOTA';
  } elseif ($oDadosResultado->obtencao == 'MP') {
    $sFormaObtencao = 'MÉDIA PONDERADA';
  }

} elseif ($oDadosMat->ed37_c_tipo == 'NIVEL') {

  if ($oDadosResultado->obtencao == 'AT') {
    $sFormaObtencao = 'ATRIBUÍDO';
  } elseif ($oDadosResultado->obtencao == 'MC') {
    $sFormaObtencao = 'MAIOR NIVEL';
  } elseif ($oDadosResultado->obtencao == 'UC') {
    $sFormaObtencao = 'ÚLTIMO NIVEL';
  }

} else {
  $sFormaObtencao = 'ATRIBUÍDO';
}

$sTitulo2 = "Forma de Obtenção:<br> $sFormaObtencao";

if ($lCasasDecimais) {

  $sMinimo = $oDadosResultado->tipoaval == 'NOTA' ?
               number_format($oDadosResultado->minimoaprov, 2, '.', '.') :
               ($oDadosResultado->tipoaval == 'NIVEL' ? $oDadosResultado->minimoaprov : '----');

} else {

  $sMinimo = $oDadosResultado->tipoaval == 'NOTA' ?
               number_format(str_replace('.00', '', $oDadosResultado->minimoaprov), 0) :
               ($oDadosResultado->tipoaval == 'NIVEL' ? $oDadosResultado->minimoaprov : '----');

}

?>
<table border='0' width="96%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0"> <!--Tabela dos títulos -->
 <tr>
  <td class='titulo'>
    <?=$sTitulo1?>
  </td>
  <td class='titulo' align="center">
    <?=$sTitulo2?>
  </td>
  <td class='titulo' width="25%" align="right">
   <table border='0px' style="" cellspacing="0px" cellpading="0px">
    <tr>
     <td class='titulo' align="center">
      Mínimo para Aprovação:
      <table border="0" cellspacing="0px" cellpading="0px">
       <tr>
        <td width="40" bgcolor="#f3f3f3" width="40" align="center">
         <font face="tahoma" color="#008000" size="1">
           <b><?=$sMinimo?></b>
         </font>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td colspan="3">
   <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px"> <!-- Tabela com os cabeçalhos e informações dos alunos -->
    <tr>
     <td colspan="6" width="55%" align="center" class='cabec1'>Alunos</td>
     <td align="center" class='cabec1'><?=$oDadosMat->ed42_c_descr?></td>
    </tr>
    <tr align="center">
     <td class="cabec1">N°</td>
     <td class="cabec1">Nome</td>
     <td class="cabec1">Situação</td>
     <td class="cabec1">Dt. Matrícula</td>
     <td class="cabec1">Dt. Saída</td>
     <td class="cabec1">Código</td>
     <td class="cabec1"><?=$oDadosMat->ed37_c_tipo?></td>
    </tr>
    <?
    if ($oDaoMatricula->numrows > 0) { // Matrículas encontrada

     for ($iCont = 0; $iCont < $oDaoMatricula->numrows; $iCont++) {

       /* Cor de cada linha */
       if ($iCont % 2 == 1) {
         $sCor = '#DBDBDB';
       } else {
         $sCor = '#F3F3F3';
       }

       $oDadosMat = db_utils::fieldsmemory($rsMat, $iCont);

       /**
        * Verifica se o aluno tem necessidade especial.
        */
       $iCodigoAluno = $oDadosMat->ed47_i_codigo;

       $oDaoAlunoNecessidade = new cl_alunonecessidade();
       $sSqlAlunoNecessidade = $oDaoAlunoNecessidade->sql_query(null, '*', 'ed214_i_codigo', "ed214_i_aluno = {$iCodigoAluno}");
       $rsAlunoNecessidade   = $oDaoAlunoNecessidade->sql_record($sSqlAlunoNecessidade);

       $oDadosMat->lAlunoNecessidade = false;
       if ($oDaoAlunoNecessidade->numrows > 0) {
        $oDadosMat->lAlunoNecessidade = true;
       }



       /* Verifico se o aluno possui parecer e nota (caso em que a avaliação é por nota,
          mas o aluno possui necessidades especiais e, então, é avaliado por parecer) */
       $lNotaParecer = $oDadosMat->ed60_c_parecer == 'S' ? true : false;

       if ($lNotaParecer) {

          $sObtencaoAnt              = $oDadosResultado->obtencao;
          $oDadosMat->ed37_c_tipo    = 'PARECER';
          $oDadosResultado->obtencao = 'AT';
          $lNotaParecer              = true;

          /* Faço isso para limpar os campos possivelmente já setados na classe */
          $oDaoDiarioResultado = db_utils::getdao('diarioresultado');

          /* Gambiarra para poder utilizar a classe DAO com um campo com valor vazio */
          $GLOBALS['HTTP_POST_VARS']['ed73_c_valorconceito'] = '';
          $oDaoDiarioResultado->ed73_c_valorconceito         = '';
          $oDaoDiarioResultado->ed73_i_valornota             = "null";
          $oDaoDiarioResultado->ed73_i_codigo                = $oDadosMat->ed73_i_codigo;
          $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);
          unset($GLOBALS['HTTP_POST_VARS']['ed73_c_valorconceito']);

          $oDadosMat->lAprovadoDefault    = false;

          /**
           *  Verifica se não existem status para resultados
           *  Se não existir status para algum dos resultados, deverá seta-los
           */
          if (  (empty($oDadosMat->ed74_c_resultadofreq)
              || empty($oDadosMat->ed74_c_resultadofinal)
              || empty($oDadosMat->ed74_c_resultadoaprov))
              && $oDadosMat->ed73_c_aprovmin != 'S') {

            //Se for nota por parecer, será mostrado 'aprovado' por padrão
            $oDadosMat->lAprovadoDefault  = true;
            $oDAODiarioFinal = db_utils::getDao("diariofinal");
            $oDAODiarioFinal->ed74_i_codigo             = $oDadosMat->ed74_i_codigo;
            $oDAODiarioFinal->ed74_i_diario             = $oDadosMat->ed74_i_diario;
            $oDAODiarioFinal->ed74_i_procresultadoaprov = $oDadosMat->ed74_i_procresultadoaprov;
            $oDAODiarioFinal->ed74_c_valoraprov         = "Parecer";
            $oDAODiarioFinal->ed74_i_procresultadofreq  = $oDadosMat->ed74_i_procresultadofreq;
            $oDAODiarioFinal->ed74_i_percfreq           = "100";
            $oDAODiarioFinal->ed74_c_resultadofreq      = "A";
            $oDAODiarioFinal->ed74_c_resultadofinal     = "A";
            $oDAODiarioFinal->ed74_c_resultadoaprov     = "A";
            $oDAODiarioFinal->ed74_i_calcfreq           = $oDadosMat->ed74_i_calcfreq;
            $oDAODiarioFinal->ed74_t_obs                = $oDadosMat->ed74_t_obs;
            $oDAODiarioFinal->alterar($oDadosMat->ed74_i_codigo);
          }

          /**
           * Seta os valores corretos pra o caso descrito acima
           */
          if ($oDadosMat->lAprovadoDefault ) {

            $oDAODiarioResultado                   = db_utils::getdao('diarioresultado');
            $oDAODiarioResultado->ed73_c_aprovmin  = 'S';
            $oDAODiarioResultado->ed73_i_codigo    = $oDadosMat->ed73_i_codigo;
            $oDAODiarioResultado->alterar($oDadosMat->ed73_i_codigo);

            if($oDAODiarioResultado->erro_status == '0') {
              throw new Exception ($oDAODiarioResultado->erro_msg);
            }
            $oDadosMat->ed73_c_aprovmin  = "S";
          }
       }

       /* Se o amparo não for de todo o período, então o amparo não garante a aprovação do aluno, então,
          no diarioresultado, seta para não o amparo, o que significa que o amparo não vai aprovar o aluno
        */
       if (trim($oDadosMat->ed81_c_todoperiodo) != 'S' && trim($oDadosMat->ed60_c_concluida) == 'N' && $oDadosMat->ed60_c_parecer != 'S') {

         /* Faço isso para limpar os campos possivelmente já setados na classe */
         $oDaoDiarioResultado                = db_utils::getdao('diarioresultado');
         $oDaoDiarioResultado->ed73_c_amparo = 'N';
         $oDaoDiarioResultado->ed73_i_codigo = $oDadosMat->ed73_i_codigo;
         $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);
         $oDadosMat->ed73_c_amparo = 'N';

       }

       $sTdAmparo = '';
       if (trim($oDadosMat->ed81_c_todoperiodo) == 'S') {

         /* Se a matrícula ainda está ativa, exibe a justificativa legal do amparo. */
         if ($oDadosMat->ed60_c_ativa == 'S') {

           if ($oDadosMat->ed81_i_justificativa != '') {
             $sTdAmparo = '<td align="center" class="aluno">Amparo - Justificativa Legal n° '.$oDadosMat->ed81_i_justificativa;
           } elseif (!empty($oDadosMat->ed81_i_convencaoamp)) {
             $sTdAmparo = '<td align="center" class="aluno">'.$oDadosMat->ed250_c_abrev.'</td>';
           }

         } else {
           $sTdAmparo = '<td align="center" class="aluno">&nbsp;</td>';
         }

         /* Se ainda não foi concluída a etapa / matrícula, então seto para sim o status amparado e
            limpo os valores lançados (notas, conceitos, pareceres), pois o aluno foi amparado na
            disciplina, não necessitando constar valores. */
         if (trim($oDadosMat->ed60_c_concluida) == 'N' && $oDadosMat->ed60_c_parecer != 'S') {

           /* Faço isso para limpar os campos possivelmente já setados na classe */
           $oDaoDiarioResultado = db_utils::getdao('diarioresultado');

           /* Gambiarra para poder utilizar a classe DAO com um campo com valor vazio */
           $GLOBALS['HTTP_POST_VARS']['ed73_c_valorconceito'] = '';
           $GLOBALS['HTTP_POST_VARS']['ed73_t_parecer']       = '';

           $oDaoDiarioResultado->ed73_c_amparo                = 'S';
           $oDaoDiarioResultado->ed73_c_valorconceito         = '';
           $oDaoDiarioResultado->ed73_i_valornota             = "null"; // Outra gambi
           $oDaoDiarioResultado->ed73_t_parecer               = '';
           $oDaoDiarioResultado->ed73_i_codigo                = $oDadosMat->ed73_i_codigo;
           $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);

           /* Neutralizando possíveis problemas ocasionados pela gambiarra */
           unset($GLOBALS['HTTP_POST_VARS']['ed73_c_valorconceito']);
           unset($GLOBALS['HTTP_POST_VARS']['ed73_t_parecer']);

         }

       } // Fim if ed81_c_todoperiodo

       /* Faço verificações da situação da matrícula para setar a cor de exibição e valores do diarioresultado,
          caso devam ser alterados.
       */
       $sDisabled    = '';
       $sCorDisabled = '#FFD5AA';
       if (trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO' || trim($oDadosMat->ed73_c_amparo) == 'S') {

         if (trim($oDadosMat->ed60_c_situacao) == 'TRANSFERIDO FORA' && $oDadosMat->ed60_c_ativa == 'S') {
           $oDadosMat->ed95_c_encerrado = 'N';
         } elseif (trim($oDadosMat->ed60_c_situacao) == 'TRANSFERIDO FORA' && $oDadosMat->ed60_c_ativa == 'N') {

           $sDisabled                       = 'disabled';
           $oDadosMat->ed73_i_valornota     = "null";
           $oDadosMat->ed73_c_valorconceito = '';
           $oDadosMat->ed73_t_parecer       = '';

         } else {
           $sDisabled     = 'disabled';
         }

       } else {
         $sCorDisabled = '#FFFFFF';
       }

       $sAlturaLinha = trim($oDadosMat->ed60_c_concluida) == 'S' ? '': "height='33'";
       $sParecer     = $oDadosMat->ed60_c_parecer == 'S' ? '<b>&nbsp;&nbsp;&nbsp;(NEE - Parecer)</b>' : '';

       /* Obtenho a descrição da situação do aluno */
       $sSitAluno    = '';
       if (trim($oDadosMat->ed81_c_todoperiodo) == 'S' && $oDadosMat->ed60_c_ativa == 'S') {

         if ($oDadosMat->ed81_i_justificativa != '') {
           $sSitAluno = 'AMPARADO';
         } else {
           $sSitAluno = $oDadosMat->ed250_c_abrev;
         }

       } else {
         $sSitAluno = Situacao($oDadosMat->ed60_c_situacao, $oDadosMat->ed60_i_codigo);
       }

    ?>
      <tr bgcolor="<?=$sCor?>" <?=$sAlturaLinha?>>
       <td align="right" class='aluno'><?=$oDadosMat->ed60_i_numaluno?></td>
       <td class='aluno'>
        <a class="aluno" href="javascript:js_movimentos(<?=$oDadosMat->ed60_i_codigo?>)"><?=$oDadosMat->ed47_v_nome?></a>
        <?=$sParecer?>
       </td>
       <td align="center" class='aluno'>
        <?=$sSitAluno?>
       </td>
       <td align="center" class='aluno'><?=db_formatar($oDadosMat->ed60_d_datamatricula, 'd')?></td>
       <td align="center" class='aluno'><?=empty($datasaida) ? '&nbsp;': $datasaida?></td>
       <td align="right" class='aluno'><b><?=$oDadosMat->ed47_i_codigo?></b></td>
       <?
       echo (empty($sTdAmparo) ? '<td class="aluno" align="center">' : $sTdAmparo);

       if (trim($oDadosMat->ed81_c_todoperiodo) != 'S') { // Não tem amparo completo (todo o período)

         if (trim($oDadosMat->ed60_c_concluida) == 'S') {

           if (trim($oDadosMat->ed37_c_tipo) == 'NIVEL') {

             echo $sFormaObtencao.': <br>'.getComboBoxConceito($sDisabled,
                                                               $sCorDisabled,
                                                               trim($oDadosMat->ed95_c_encerrado) == 'S',
                                                               $oDadosMat->ed43_i_formaavaliacao,
                                                               $oDadosMat->ed73_c_valorconceito,
                                                               true
                                                              );

           } elseif (trim($oDadosMat->ed37_c_tipo) == 'PARECER') {
             echo getHtmlParecerAluno($sDisabled, $sCorDisabled, $oDadosMat, $oDadosRegencia->ed59_i_turma, true, $iCodigoEnsino);
           } elseif (trim($oDadosMat->ed37_c_tipo) == 'NOTA') {

             $sHtml = $sFormaObtencao.':<br><input name="ed73_i_valornota" value="';
             $sHtml .= ($oDadosMat->ed73_i_valornota == "" ?
                       '' :ArredondamentoNota::formatar($oDadosMat->ed73_i_valornota, $iAno));
             $sHtml .= '" type="text" size="6" maxlength="6" style="background:'.$sCorDisabled;
             $sHtml .= ';width:45px;height:14px;border: 1px solid #000000;';
             $sHtml .= 'font-size:11px;text-align:right;padding:0px;" ';
             $sHtml .= 'onclick="alert(\'Aluno já possui avaliações encerradas para esta disciplina!\')"';
             $sHtml .= (trim($oDadosMat->ed95_c_encerrado) == 'S' ? 'readonly' : $sDisabled).'>';

             echo $sHtml;

           }

         } else { // Matrícula ainda não está concluída

           if (trim($oDadosResultado->obtencao) == 'AT') {

             if (trim($oDadosMat->ed37_c_tipo) == 'NIVEL') {

               if (isset($conc) && $conc == '' && $oDadosMat->ed60_c_situacao == 'MATRICULADO') {

                 echo $oDadosMat->ed42_c_abrev.' ainda não foi concluído.';

               } else {

                 echo getComboBoxConceito($sDisabled,
                                          $sCorDisabled,
                                          trim($oDadosMat->ed95_c_encerrado) == 'S',
                                          $oDadosMat->ed43_i_formaavaliacao,
                                          $oDadosMat->ed73_c_valorconceito,
                                          false,
                                          $oDadosMat->ed73_i_codigo,
                                          $iCont
                                         );
               }

             } elseif (trim($oDadosMat->ed37_c_tipo) == 'PARECER') {

               if (isset($parec) && $parec == '') {

                 echo $oDadosMat->ed42_c_abrev." ainda não foi concluído.";

               } else {

                 $sOnchange = '';
                 if (isset($oDadosRegencia->ed220_c_aprovauto) && $oDadosRegencia->ed220_c_aprovauto == 'S') {

                   $oDadosMat->ed73_c_aprovmin = 'S';
                   /* Faço isso para limpar os campos possivelmente já setados na classe */
                   $oDaoDiarioResultado                  = db_utils::getdao('diarioresultado');

                   $oDaoDiarioResultado->ed73_c_aprovmin = 'S';
                   $oDaoDiarioResultado->ed73_i_codigo   = $oDadosMat->ed73_i_codigo;
                   $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);

                 } else {

                   $sNotaParecer = $lNotaParecer ? 'S' : 'N';
                   $sOnchange    = 'onchange="js_aprovmin(this, '.$oDadosMat->ed73_i_codigo.", '$sNotaParecer')\"";

                 }

                 echo getHtmlParecerAluno($sDisabled, $sCorDisabled, $oDadosMat, $oDadosRegencia->ed59_i_turma,
                                          false, $sOnchange, $iCont, $iCodigoEnsino);

               }

             } elseif (trim($oDadosMat->ed37_c_tipo) == 'NOTA') {

               $sHtml = '<input name="ed73_i_valornota'.$iCont.'" value="';
               $sHtml .= ($oDadosMat->ed73_i_valornota == "" ?
                             '' : number_format($oDadosMat->ed73_i_valornota, ArredondamentoNota::getNumeroCasasDecimais($iAno), '.', '.'));
               $sHtml .= '" type="text" size="6" maxlength="6" style="background:'.$sCorDisabled;
               $sHtml .= ';width:45px;height:14px;border: 1px solid #000000;';
               $sHtml .= 'font-size:11px;text-align:right;padding:0px;" ';

               if (trim($oDadosMat->ed95_c_encerrado) == 'S' || $oDadosMat->ed60_c_situacao != 'MATRICULADO') {

                 $sHtml .= "onclick=\"alert('Aluno já possui avaliações encerradas para esta disciplina!')\"";
                 $sHtml .= 'readonly';

               } else {
                 if (!isset($sDisableda)) {
                   $sDisableda = '';
                 }
                 $sHtml .= 'onChange="js_formatavalor(this, ';
                 $sHtml .= $oDadosMat->ed37_i_variacao.', '.$oDadosMat->ed37_i_menorvalor.', ';
                 $sHtml .= $oDadosMat->ed37_i_maiorvalor.', '.$oDadosMat->ed73_i_codigo.",'NOTA');\"";
                 $sHtml .= $sDisableda;

               }

               echo $sHtml;

             } // fim if tipo == 'NOTA'

           } else { //  obtenção != 'AT' (Não é atribuída)

             $sDisabled    = 'disabled'; // Se não é atribuída, então o usuário não pode alterar

             /* Obtenho todas as avaliações / resultados que compõem o resultado para exibir */
             $sCampos      = 'abrev, trim(amparo) as amparo, avalvinc, resultvinc, peso, conceito, nota, tipo ';
             $sWhereAval   = "diarioavaliacao.ed72_i_procavaliacao in ($sProcAvalCompoeResult) ";
             $sWhereAval  .= ' and diarioavaliacao.ed72_i_diario = '.$oDadosMat->ed73_i_diario;
             $sWhereAval  .= ' and avalcompoeres.ed44_i_procresultado = '.$ed43_i_codigo;
             $sWhereRes    = "diarioresultado.ed73_i_procresultado in ($sProcResultCompoeResult) ";
             $sWhereRes   .= ' and diarioresultado.ed73_i_diario = '.$oDadosMat->ed73_i_diario;
             $sWhereRes   .= ' and rescompoeres.ed68_i_procresultado = '.$ed43_i_codigo;
             $sSql         = $oDaoDiarioResultado->sql_query_diarioavalres(null, $sCampos, 'sequencia',
                                                                           $sWhereAval, $sWhereRes
                                                                          );
             $rsResAval    = $oDaoDiarioResultado->sql_record($sSql);

             $sCampoRes    = trim($oDadosMat->ed37_c_tipo) == 'NIVEL' ? 'conceito': 'nota';

             $iNumLinhas   = $oDaoDiarioResultado->numrows;
             $iNumPeriodos = $oDaoDiarioResultado->numrows;

             /* Tabela com os componentes de avaliação e resultado final */
             echo '<table border="0" width="100%" cellspacing="1" cellpading="0"><tr>';

             $sEmBranco    = '';
             $iQtdeAmparos = 0;
             $sHtml        = '';
             $sPesoBranco  = '';
             /* Exibo todas as avaliações / resultados */
             for ($iCont2 = 0; $iCont2 < $iNumLinhas; $iCont2++) {

               $oDadosResAval = db_utils::fieldsmemory($rsResAval, $iCont2);
               if ($oDadosResAval->amparo == 'S') {

                 $valor      = 'AMPARADO';
                 $sBgColor   = $sCorNao;
                 $sEmBranco .= "N";
                 $iNumPeriodos--; // Se o período de avaliação / resultado foi amparado, não deve contar nos cálculos
                 $iQtdeAmparos++;

               } else {

                 /* Se o valor da nota / conceito for vazio */
                 if ($oDadosResAval->$sCampoRes == '') {

                   /* Se houver uma avaliação ou um resultado vinculado como avaliação / resultado anterior,
                      que siginifica que só interessa este resultado / avaliação, se no resultado / avaliação
                      anterior vinculado o aluno não atingiu o mínimo para aprovação */
                   if ($oDadosResAval->avalvinc != 0 || $oDadosResAval->resultvinc != 0) {

                     /* Se houver uma avaliação vinculada */
                     if ($oDadosResAval->avalvinc != 0) {


                       /* Obtenho o mínimo para aprovação da avaliação vinculada (anterior) */
                       $sMinAprovacao = VerAprovAvalAnt($oDadosResAval->avalvinc, $oDadosMat->ed73_i_diario, 'A');

                       if (trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO') {

                         $valor      = trim(Situacao($oDadosMat->ed60_c_situacao, $oDadosMat->ed60_i_codigo));
                         $sBgColor   = $sCorNao;
                         $sEmBranco .= 'S';

                       /* Se o mínimo para aprovação for == 'N', significa que a nota ainda não foi lançada, mas
                          o aluno deverá se submeter a esta avaliação / resultado, pois na avaliação / resultado
                          anterior, o mesmo não atingiu o mínimo para aprovação.
                       */
                       } elseif ($sMinAprovacao == 'N') {

                         $valor      = 'EM BRANCO';
                         $sBgColor   = $sCorNao;
                         $sEmBranco .= 'S';

                       /* Se o mínimo para aprovação for == 'S', significa que o aluno atingiu o mínimo para aprovação
                          no resultado / avaliação anterior, o que o dispensa desta avaliação / resultado.
                       */
                       } elseif ($sMinAprovacao == 'S') {

                         $valor      = 'DISPENSADO';
                         $sBgColor   = $sCorSim;
                         $sEmBranco .= 'N';

                       }

                     } elseif ($oDadosResAval->resultvinc != 0) { // Se houver um resultado vinculado

                       /* Obtenho o mínimo para aprovação do resultado vinculado (anterior) */
                       $sMinAprovacao = VerAprovAvalAnt($oDadosResAval->resultvinc, $oDadosMat->ed73_i_diario, 'R');

                       $sEmBranco    .= $sMinAprovacao == 'N' ? 'S' : 'N';

                       if (trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO') {

                         $valor    = trim(Situacao($oDadosMat->ed60_c_situacao, $oDadosMat->ed60_i_codigo));
                         $sBgColor = $sCorNao;

                       /* Se o mínimo para aprovação for == 'N', significa que a nota ainda não foi lançada, mas
                          o aluno deverá se submeter a esta avaliação / resultado, pois na avaliação / resultado
                          anterior, o mesmo não atingiu o mínimo para aprovação.
                       */
                       } elseif ($sMinAprovacao == 'N') {

                         $valor    = 'EM BRANCO';
                         $sBgColor = $sCorNao;

                       /* Se o mínimo para aprovação for == 'S', significa que o aluno atingiu o mínimo para aprovação
                          no resultado / avaliação anterior, o que o dispensa desta avaliação / resultado.
                       */
                       } elseif ($sMinAprovacao == 'S') {

                         $valor    = 'DISPENSADO';
                         $sBgColor = $sCorSim;

                       }

                     }

                   } else { // Não tem um resultado / avaliação anterior que decida se o aluno deveria realizar este

                     $valor      = trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO' ?
                                     trim(Situacao($oDadosMat->ed60_c_situacao, $oDadosMat->ed60_i_codigo)) : 'EM BRANCO';
                     $sEmBranco .= 'S'; // Nota vazia
                     $sBgColor   = $oDadosResAval->$sCampoRes == '' || trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO' ?
                                     $sCorNao : $sCorSim;

                   }

                   // Se a nota está em branco, este período de avaliação / resultado não deve contar para os cálculos
                   $iNumPeriodos--;

                 /* Se houver nota e o aluno estiver matriculado */
                 } elseif (trim($oDadosMat->ed37_c_tipo) == 'NOTA'
                           && trim($oDadosMat->ed60_c_situacao) == 'MATRICULADO') {

                   $valor      = number_format($oDadosResAval->$sCampoRes, ArredondamentoNota::getNumeroCasasDecimais($iAno), ".", "");
                   $sEmBranco .= 'N';
                   $sBgColor   = $sCorSim;

                 /* Se houver nível e o aluno estiver matriculado */
                 } elseif (trim($oDadosMat->ed37_c_tipo) == 'NIVEL'
                           && trim($oDadosMat->ed60_c_situacao) == 'MATRICULADO') {

                   $valor      = $oDadosResAval->$sCampoRes;
                   $sEmBranco .= 'N';
                   $sBgColor   = $sCorSim;

                 } else { // Ou o aluno não está matriculado ou a avaliação é por parecer

                   $valor      = trim(Situacao($oDadosMat->ed60_c_situacao, $oDadosMat->ed60_i_codigo));
                   $sEmBranco .= 'N';
                   $sBgColor   = trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO' ? $sCorNao : $sCorSim;

                 }

               }

               /* Se a forma de obtenção for por média ponderada e o peso estiver como zero,
                  significa que o peso não foi informado
               */
               if ($oDadosResultado->obtencao == 'MP' && $oDadosResAval->peso == 0) {

                 $sHtml       .= '<td align="center" width="75" class="alunopq" bgcolor="$sCorNao" ';
                 $sHtml       .= 'style="border:1px solid #444444">'.$oDadosResAval->abrev;
                 $sHtml       .= ' - Peso: '.$oDadosResAval->peso.'<br><b>Peso Não Informado</b></td>';
                 $sPesoBranco .= 'S';

               } else {

                 $sHtml .= '<td align="center" width="75" class="alunopq" bgcolor="'.$sBgColor.'" ';
                 $sHtml .= 'style="border:1px solid #444444">'.$oDadosResAval->abrev;
                 $sHtml .= ($oDadosResultado->obtencao == 'MP' ? ' - Peso: '.$oDadosResAval->peso : '');
                 $sHtml .= '<br><b>'.ArredondamentoNota::formatar($valor, $iAno).'</b></td>';

               }

               $valor = '';

             } // Final do for que exibe todos os períodos de avaliação / resultado que compõem o resultado em questão


             /* Se todos os períodos de avaliação / resultado foram amparados */
             if ($iQtdeAmparos == $iNumLinhas) {

               if ($oDadosMat->ed60_c_ativa == 'S') {

                 if ($oDadosMat->ed81_i_justificativa != '') {

                   echo '<td class="aluno" align="center">Amparo - Justificativa Legal n° '.
                        $oDadosMat->ed81_i_justificativa.'</td></tr></table></td>';

                 } else {
                   echo '<td class="aluno" align="center">'.$oDadosMat->ed250_c_abrev.'</td></tr></table></td>';
                 }

               } else { // Matrícula não está mais ativa
                 echo '<td class="aluno" align="center">&nbsp;</td></tr></table></td>';
               }

               $oDaoDiarioResultado->ed73_c_amparo        = 'S';
               $oDaoDiarioResultado->ed73_c_aprovmin      = 'S';
               $oDaoDiarioResultado->ed73_c_valorconceito = '0';
               $oDaoDiarioResultado->ed73_i_valornota     = "null";
               $oDaoDiarioResultado->ed73_i_codigo        = $oDadosMat->ed73_i_codigo;
               $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);
               continue;

             } else { // Nenhum amparo ou parcialmente amparado

               $oDaoDiarioResultado->ed73_c_amparo = 'N';
               echo $sHtml;

             }

             /* Se houve alguma nota em branco */
             if (strstr($sEmBranco, 'S')) {

               if (!$lPermiteNotaEmBranco) {

                 $sEmBranco    = '';
                 $sDisabled    = 'disabled';
                 $sCorDisabled = '#FFD5AA';
                 echo '<td class="aluno" align="center"> '.$sFormaObtencao.': <br>';
                 echo '<input name="nulo" value="" type="text" size="6" maxlength="6" ';
                 echo 'style="background:<?=$sCorDisabled?>;width:45px;height:14px;';
                 echo 'border: 1px solid #000000;padding:0px;" '.$sDisabled.'></td>';

                 $oDaoDiarioResultado->ed73_c_amparo        = 'N';
                 $oDaoDiarioResultado->ed73_c_aprovmin      = 'N';
                 $oDaoDiarioResultado->ed73_c_valorconceito = "";
                 $oDaoDiarioResultado->ed73_i_valornota     = "null";
                 $oDaoDiarioResultado->ed73_i_codigo        = $oDadosMat->ed73_i_codigo;
                 $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);

               }

             } else {
               $sEmBranco = 'S';
             }

             $sResFinal = ''; // Variável que irá armazenar o resultado final

             if (($lPermiteNotaEmBranco || strstr($sEmBranco, 'S')) && !strstr($sPesoBranco, 'S')) {

               /* Avaliação por nível */
               if (trim($oDadosMat->ed37_c_tipo) == 'NIVEL') {

                 $sSql             = $oDaoConceito->sql_query(null, 'ed39_i_sequencia', 'ed39_i_sequencia',
                                                              " ed39_c_conceito = '".
                                                              $oDadosResultado->minimoaprov.
                                                              "' and ed39_i_formaavaliacao = ".
                                                              $oDadosMat->ed43_i_formaavaliacao
                                                             );
                 $rs                = $oDaoConceito->sql_record($sSql);
                 $iMinAprovConceito = db_utils::fieldsmemory($rs, 0)->ed39_i_sequencia;

                 $iMaxConcei        = -1;
                 /* Avaliação por nível, obtenção por maior nível */
                 if (trim($oDadosResultado->obtencao) == 'MC') {

                   /* Obtenho a ordem do maior conceito */
                   $sCampos      = 'max((select ed39_i_sequencia ';
                   $sCampos     .= '      from conceito ';
                   $sCampos     .= '        where conceito.ed39_i_formaavaliacao = ed37_i_codigo ';
                   $sCampos     .= '          and conceito.ed39_c_conceito = conceito)) as maiorconceito ';
                   $sWhereAval   = "diarioavaliacao.ed72_i_procavaliacao in ($sProcAvalCompoeResult) ";
                   $sWhereAval  .= ' and diarioavaliacao.ed72_i_diario = '.$oDadosMat->ed73_i_diario;
                   $sWhereAval  .= " and diarioavaliacao.ed72_c_amparo = 'N' ";
                   $sWhereAval  .= ' and avalcompoeres.ed44_i_procresultado = '.$ed43_i_codigo;
                   $sWhereRes    = "diarioresultado.ed73_i_procresultado in ($sProcResultCompoeResult) ";
                   $sWhereRes   .= ' and diarioresultado.ed73_i_diario = '.$oDadosMat->ed73_i_diario;
                   $sWhereRes   .= " and diarioresultado.ed73_c_amparo = 'N' ";
                   $sWhereRes   .= ' and rescompoeres.ed68_i_procresultado = '.$ed43_i_codigo;
                   $sSql         = $oDaoDiarioResultado->sql_query_diarioavalres(null,
                                                                                 $sCampos,
                                                                                 '',
                                                                                 $sWhereAval,
                                                                                 $sWhereRes
                                                                                );
                   $rs           = $oDaoDiarioResultado->sql_record($sSql);
                   $iMaiorConcei = db_utils::fieldsmemory($rs, 0)->maiorconceito;

                   if ($iMaiorConcei != '') {

                     /* Obtenho o maior conceito atingido pelo aluno */
                     $sSql      = $oDaoConceito->sql_query_file(null, 'ed39_c_conceito as maiorconceito', '',
                                                                " ed39_i_sequencia = $iMaiorConcei ".
                                                                ' and ed39_i_formaavaliacao = '.
                                                                $oDadosMat->ed43_i_formaavaliacao
                                                               );
                     $rs         = $oDaoConceito->sql_record($sSql);
                     $sResFinal  = db_utils::fieldsmemory($rs, 0)->maiorconceito;
                     $iMaxConcei = $iMaiorConcei;

                   } else {
                     $sResFinal = '';
                   }

                 /* Avaliação por nível, obtenção por último nível (conceito) */
                 } elseif (trim($oDadosResultado->obtencao) == 'UC') {

                   /* Obtenho o conceito do último componente (período ou resultado de avaliação) */
                   $sCampos      = 'max((select ed39_i_sequencia ';
                   $sCampos     .= '      from conceito ';
                   $sCampos     .= '        where conceito.ed39_i_formaavaliacao = ed37_i_codigo ';
                   $sCampos     .= '          and conceito.ed39_c_conceito = conceito)) as ultimoconceito ';
                   $sWhereAval   = "diarioavaliacao.ed72_i_procavaliacao in ($iCodigoUltimoComponente) ";
                   $sWhereAval  .= ' and diarioavaliacao.ed72_i_diario = '.$oDadosMat->ed73_i_diario;
                   $sWhereAval  .= " and diarioavaliacao.ed72_c_amparo = 'N' ";
                   $sWhereAval  .= ' and avalcompoeres.ed44_i_procresultado = '.$ed43_i_codigo;
                   $sWhereRes    = "diarioresultado.ed73_i_procresultado in ($iCodigoUltimoComponente) ";
                   $sWhereRes   .= ' and diarioresultado.ed73_i_diario = '.$oDadosMat->ed73_i_diario;
                   $sWhereRes   .= " and diarioresultado.ed73_c_amparo = 'N' ";
                   $sWhereRes   .= ' and rescompoeres.ed68_i_procresultado = '.$ed43_i_codigo;
                   $sWhere       = ' tipo = '.$iTipoUltimoComponente;
                   $sSql         = $oDaoDiarioResultado->sql_query_diarioavalres(null,
                                                                                 $sCampos,
                                                                                 '',
                                                                                 $sWhereAval,
                                                                                 $sWhereRes,
                                                                                 $sWhere
                                                                                );
                   $rs           = $oDaoDiarioResultado->sql_record($sSql);
                   $sResFinal    = '';
                   if ($oDaoDiarioResultado->numrows > 0) {

                     $iUltConcei = db_utils::fieldsmemory($rs, 0)->ultimoconceito;
                     if ($iUltConcei != '') {

                       /* Obtenho o ultimo conceito atingido pelo aluno */
                       $sSql      = $oDaoConceito->sql_query_file(null, 'ed39_c_conceito as ultimoconceito', '',
                                                                  " ed39_i_sequencia = $iUltConcei ".
                                                                  ' and ed39_i_formaavaliacao = '.
                                                                  $oDadosMat->ed43_i_formaavaliacao
                                                                 );
                       $rs         = $oDaoConceito->sql_record($sSql);
                       $sResFinal  = db_utils::fieldsmemory($rs, 0)->ultimoconceito;
                       $iMaxConcei = $iUltConcei;
                     }

                   }

                 }

                 $sMinimo                                   = $iMaxConcei >= $iMinAprovConceito ? 'S': 'N';
                 $oDaoDiarioResultado->ed73_c_aprovmin      = $sMinimo;
                 $oDaoDiarioResultado->ed73_c_valorconceito = $sResFinal;
                 $oDaoDiarioResultado->ed73_i_valornota     = "null";
                 $oDaoDiarioResultado->ed73_i_codigo        = $oDadosMat->ed73_i_codigo;
                 $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);

                 echo '<td class="aluno" align="center"> '.$sFormaObtencao.': <br><input ';
                 echo 'name="ed73_i_valorconceito" value="'.$sResFinal.'" type="text" ';
                 echo 'size="6" maxlength="6" style="background:'.$sCorDisabled.';width:45px;';
                 echo 'height:14px;border: 1px solid #000000;font-size:11px;text-align:center;';
                 echo 'padding:0px;" readonly></td>';

               /* Avaliação por nota */
               } elseif (trim($oDadosMat->ed37_c_tipo) ==  'NOTA') {

                 $iNumPeriodos = $iNumPeriodos == 0 ? 1 : $iNumPeriodos; // Impeço divisão por 0

                 $sWhereAval  = " diarioavaliacao.ed72_i_procavaliacao in ($sProcAvalCompoeResult) ";
                 $sWhereAval .= ' and avalcompoeres.ed44_i_procresultado = '.$ed43_i_codigo;
                 $sWhereRes   = " diarioresultado.ed73_i_procresultado in ($sProcResultCompoeResult) ";
                 $sWhereRes  .= ' and rescompoeres.ed68_i_procresultado = '.$ed43_i_codigo;
                 $sWhere      = ' diario = '.$oDadosMat->ed73_i_diario;
                 $sWhere     .= " and amparo = 'N' and nota is not null ";
                 $sOrderBy    = '';

                 $sCampos     = 'nota, peso, tipo';
                 /* Avaliação por nota, forma de obtenção última nota */
                 if (trim($oDadosResultado->obtencao) == 'UN') {

                   $sOrderBy    = 'sequencia desc limit 1';
                   $sWhere      = ' diario = '.$oDadosMat->ed73_i_diario;
                   $sWhere     .= " and nota is not null ";
                 }


                 $sSql    = $oDaoDiarioResultado->sql_query_diarioavalres(null,
                                                                          $sCampos,
                                                                          $sOrderBy,
                                                                          $sWhereAval,
                                                                          $sWhereRes,
                                                                          $sWhere);

                 $rsMedia = $oDaoDiarioResultado->sql_record($sSql);
                 $sResFinal = '';
                 if ($oDaoDiarioResultado->numrows > 0) {

                   $iTotalRegistros = $oDaoDiarioResultado->numrows;

                   $nNotaFinal = 0;
                   $nPesoFinal = null;
                   $nPeso      = null;
                   $nNotaTemp  = null;

                   for ($iNotas = 0; $iNotas < $iTotalRegistros + 1; $iNotas++) {

                     $nNota = db_utils::fieldsmemory($rsMedia, $iNotas)->nota;
                     if (db_utils::fieldsmemory($rsMedia, $iNotas)->tipo == 2) {
                       $nNota = ArredondamentoNota::formatar($nNota, $iAno);
                     }
                     /* Avaliação por nota, forma de obtenção média aritmética */
                     if (trim($oDadosResultado->obtencao) == 'ME') {

                       if (!empty($nNota) && $iNotas < $iTotalRegistros + 1) {

                         $nNotaFinal = ($nNotaFinal + $nNota);
                       } elseif ($iNotas == $iTotalRegistros) {

                         $nNotaFinal = $nNotaFinal / $iNumPeriodos;
                       }
                     /* Avaliação por nota, forma de obtenção média ponderada */
                     } elseif (trim($oDadosResultado->obtencao) == 'MP') {

                       if (!empty($nNota) && $iNotas < $iTotalRegistros + 1) {

                         $nPeso      = db_utils::fieldsmemory($rsMedia, $iNotas)->peso;
                         $nNotaFinal = ($nNotaFinal + ($nNota * $nPeso)) ;
                         $nPesoFinal = ($nPesoFinal + $nPeso);
                       } elseif ($iNotas == $iTotalRegistros) {

                         $nNotaFinal = ($nNotaFinal) / $nPesoFinal;
                       }
                     /* Avaliação por nota, forma de obtenção soma */
                     } elseif (trim($oDadosResultado->obtencao) == 'SO') {

                       if (!empty($nNota)) {

                         $nNotaFinal = ($nNotaFinal + $nNota);
                       }

                     /* Avaliação por nota, forma de obtenção maior nota */
                     } elseif (trim($oDadosResultado->obtencao) == 'MN') {

                       if (!empty($nNota)) {

                         if ($nNotaFinal < $nNota) {
                           $nNotaFinal = $nNota;
                         }
                       }

                     /* Avaliação por nota, forma de obtenção última nota */
                     } elseif (trim($oDadosResultado->obtencao) == 'UN') {

                       if ( !empty($nNota) ) {
                         $nNotaFinal = $nNota;
                       }
                     }
                   }

                   $sResFinal = "$nNotaFinal";
                 }


                 $sMinimo = ArredondamentoNota::arredondar($sResFinal, $iAno) >= $oDadosResultado->minimoaprov ? 'S' : 'N';

                 /* oDadosResAval do último componente, então a última nota foi amparada, logo,
                    se a última nota é a que vale, é como se todo o período tivesse sido amparado.
                 */
                 if (trim($oDadosResultado->obtencao) == 'UN' && $oDadosResAval->amparo == 'S') {

                   $sMinimo      = 'S';
                   $sResFinal    = null;
                   $sCorDisabled = '#DEB887';
                   $oDaoAmparo   = db_utils::getdao('amparo');
                   $sSql         = $oDaoAmparo->sql_query_file(null, 'ed81_i_codigo', '',
                                                               'ed81_i_diario = '.$oDadosMat->ed73_i_diario
                                                              );
                   $rs           = $oDaoAmparo->sql_record($sSql);
                   $iLinhasAmp   = $oDaoAmparo->numrows;
                   for ($iCont3; $iCont3 < $iLinhasAmp; $iCont3++) {

                     $oDaoAmparo->ed81_i_codigo      = db_utils::fieldsmemory($rs, $iCont3)->ed81_i_codigo;
                     $oDaoAmparo->ed81_c_todoperiodo = 'S';
                     $oDaoAmparo->alterar($oDaoAmparo->ed81_i_codigo);

                   }

                 }

                 $sResFinal = trim($oDadosMat->ed60_c_situacao) != 'MATRICULADO'? '' : $sResFinal;
                 //$sResFinal = $sResFinal == 0 ? '0' : $sResFinal;
                 $sResFinal = $sResFinal == '' ? 'null' : $sResFinal;
                 /* Atualizo o diário resultado do aluno */
                 $oDaoDiarioResultado->ed73_c_amparo        = 'N';
                 $oDaoDiarioResultado->ed73_c_aprovmin      = $sMinimo;
                 $oDaoDiarioResultado->ed73_c_valorconceito = null;
                 $iValorNota                                = ArredondamentoNota::arredondar($sResFinal, $iAno);
                 $oDaoDiarioResultado->ed73_i_valornota     = "{$iValorNota}";
                 $oDaoDiarioResultado->ed73_i_codigo        = $oDadosMat->ed73_i_codigo;
                 $oDaoDiarioResultado->alterar($oDadosMat->ed73_i_codigo);
                 $sResFinalOriginal = $sResFinal;
                 $sResFinal = $sResFinal == 'null' ? '' : ArredondamentoNota::arredondar($sResFinal, $iAno);
                 
                 /* TD com o resultado final */
                 echo '<td class="aluno" align="center"> '.$sFormaObtencao.': <br>';
                 echo '<input name="ed73_i_valornota" value="'.$sResFinal.'" type="text" ';
                 echo 'size="6" maxlength="6" style="background:'.$sCorDisabled.';width:45px;';
                 echo 'height:14px;border: 1px solid #000000;font-size:11px;text-align:right;';
                 echo 'padding:0px;" readonly></td>';

               } // Fim if forma avaliação = 'NOTA'

             } // Fim if ($lPermiteNotaEmBranco || strstr($sEmBranco, 'S')) && !strstr($sPesoBranco, 'S')

             $oDaoResultadoRecuperacao = new cl_diarioresultadorecuperacao();
             $oDaoResultadoRecuperacao->excluir(null, "ed116_diarioresultado = {$oDadosMat->ed73_i_codigo}");
            
             if ($sMinimo == 'N') {

               $oResultado          = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosMat->ed73_i_procresultado);
               $oAvalicaoDependente = AvaliacaoPeriodicaRepository::getAvaliacaoDependente($oResultado);
               
               if (!empty($oAvalicaoDependente)) {

                  $lGeraRecuparacao  = false;  // Se deve gerar recuperação validando configuração
                  $lJaFezRecuperacao = false; // se aluno tem nota no provão desta disciplina
                  
                  /**
                   * Verificamos em quantas disciplinas aluno ficou em abaixo da média
                   */
                  $sSqlDisciplinasAbaixoMedia  = " select count(*) as abaixo_media";
                  $sSqlDisciplinasAbaixoMedia .= "   from diarioresultado ";
                  $sSqlDisciplinasAbaixoMedia .= "  inner join diario on diario.ed95_i_codigo = diarioresultado.ed73_i_diario ";
                  $sSqlDisciplinasAbaixoMedia .= "  where ed95_i_aluno         = {$oDadosMat->ed47_i_codigo} ";
                  $sSqlDisciplinasAbaixoMedia .= "    and ed73_i_procresultado = {$oDadosMat->ed73_i_procresultado} ";
                  $sSqlDisciplinasAbaixoMedia .= "    and ed73_c_aprovmin      = 'N' ";
                  $sSqlDisciplinasAbaixoMedia .= "    and ed73_i_valornota     is not null ";
                  $sSqlDisciplinasAbaixoMedia .= "    and ed95_i_codigo        = {$oDadosMat->ed74_i_diario}";

                  $iDisciplinasAbaixoMedia  = 0;
                  $rsDisciplinasAbaixoMedia = db_query($sSqlDisciplinasAbaixoMedia);
                  if ( $rsDisciplinasAbaixoMedia ) {
                    $iDisciplinasAbaixoMedia = db_utils::fieldsMemory($rsDisciplinasAbaixoMedia, 0)->abaixo_media;
                  }
                  // Busca como esta configurado                  
                  $iQuantidadeMaximaDisciplinasParaRecuperacao = $oAvalicaoDependente->quantidadeMaximaDisciplinasParaRecuperacao();
                  
                  /**
                   * Validamos se o aluno esta apto a cursar a recuperação. 
                   * - Quando $iQuantidadeMaximaDisciplinasParaRecuperacao == 0 significa que @todo complementar
                   * - O número de disciplinas reprovadas não pode ser maior do que o configurado na Avaliação para o 
                   *   Aluno ter direito a cursar a recuperação.
                   */
                  if (!empty($iQuantidadeMaximaDisciplinasParaRecuperacao)
                       && $iQuantidadeMaximaDisciplinasParaRecuperacao >= $iDisciplinasAbaixoMedia) {
                  	$lGeraRecuparacao = true;
                  }
                   
                  /**
                   * Veridicamos se aluno já tem resultado no provão.
                   * Não devemos incluir em diarioresultadorecuperacao se aluno já tivér realizado o provão
                   */
                  $sSqlAvaliacaoRecuperacao  = " select ed72_i_valornota,            ";
                  $sSqlAvaliacaoRecuperacao .= "        ed72_c_valorconceito, ";
                  $sSqlAvaliacaoRecuperacao .= "        ed72_c_amparo          ";
                  $sSqlAvaliacaoRecuperacao .= "   from diarioavaliacao             ";
                  $sSqlAvaliacaoRecuperacao .= "  where ed72_i_procavaliacao = {$oAvalicaoDependente->getCodigo()}";
                  $sSqlAvaliacaoRecuperacao .= "    and ed72_i_diario = {$oDadosMat->ed73_i_diario} ";
                  
                  $rsAvaliacaoRecuperacao = db_query($sSqlAvaliacaoRecuperacao);
                  if ( $rsAvaliacaoRecuperacao ) {

                    $oDadosAvaliacaoRecuperacao = db_utils::fieldsMemory($rsAvaliacaoRecuperacao, 0);

                    /**
                     * Contamos como avaliacao lancada o aluno ter conceito ou nota lancada
                     * Alunos Amparados no periodo, nao devem ter recuperacao lancada
                     */
                    $lJaFezRecuperacao = $oDadosAvaliacaoRecuperacao->ed72_i_valornota != ''  ||
                                        trim($oDadosAvaliacaoRecuperacao->ed72_c_valorconceito) != '';

                    if ($oDadosAvaliacaoRecuperacao->ed72_c_amparo == 'S') {
                      $lJaFezRecuperacao = true;
                    }

                  }

                  /**
                   * Só incluimos na recuperação se o aluno ainda não lançou a avaliação do provão e quando ele atende 
                   * os requisitos configurados na Avaliação de recuperação.
                   */
                  if ( !$lJaFezRecuperacao && $lGeraRecuparacao && $iDisciplinasAbaixoMedia > 0 ) {

                    $oDaoResultadoRecuperacao->ed116_diarioresultado = $oDadosMat->ed73_i_codigo;
                    $oDaoResultadoRecuperacao->incluir(null);
                  }
               }
             }
             $sCorDisabled = "#FFFFFF";

             /* Fechamento da tabela com os componentes de avaliação e resultado final */
             echo '</tr></table></td>';

           } // Fim else (Obtenção não atribuída)

         } // Fim else (Matrícula ainda não está concluída)

       } // Fim if não tem amparo completo (todo o período)

       echo '</td></tr>'; // Fecho a TD das avaliações / amparo do aluno

       if ($oDadosMat->ed60_c_parecer == 'S') {
         $oDadosResultado->obtencao = $sObtencaoAnt;
       }

     } // Fim for matrículas

   } else { // Nenhuma matrícula encontrada
     echo '<td colspan="3" class="aluno" align="center">NENHUM ALUNO MATRICULADO NESTA TURMA.</td>';
   }
   ?>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?
if (isset($aprovminimo) && !isset($lNotaParecer)) {
 ?>
 <script>
  js_OpenJanelaIframe('','db_iframe_outrasdisc','func_outrasdisc.php?regencia=<?=$regencia?>&ed43_i_codigo=<?=$ed43_i_codigo?>&codigo=<?=$codigo?>&valor=<?=$valoralterado?>','Informar este resultado para outras disciplinas',true);
 </script>
 <?
}

?>
<script>
parent.iframe_RF.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>"+"&iTrocaTurma=<?=$iTrocaTurma?>";
function js_formatavalor(campo,variacao,menor,maior,codigo,tipo) {
 if (campo.value!="") {
  valor = campo.value.replace(",",".");
  var expre = new RegExp("[^0-9\.]+");
  if (!valor.match(expre)) {
   if (valor<menor || valor>maior) {
    alert("Nota deve ser entre "+menor+" e "+maior+"!");
    campo.value = "";
    campo.focus();
   } else {
    variacaoant = variacao;
    valorant = valor;
    if (variacao<1) {
     partevariacao = variacao.toString();
     partevariacao = partevariacao.split(".");
     if (partevariacao[1].length==1) {
      variacao = partevariacao[1]+"0";
     } else {
      variacao = partevariacao[1];
     }
     partevalor = valor.toString();
     partevalor = partevalor.split(".");
     if (partevalor[1]!=undefined) {
      if (partevalor[1].length==1) {
       valor = partevalor[1]+"0";
      } else {
       valor = partevalor[1];
      }
     } else {
      valor = "00";
     }
     valor = parseInt(valor);
     variacao = parseInt(variacao);
    }
    if ((valor % variacao)==0) {
     var expr = new RegExp("[^0-9]+");
     valor = valorant.toString();
     if (valor.match(expr)) {
      campo.value = valor;
      adiante = valor;
     } else {
      campo.value = js_cent(valor);
      adiante = js_cent(valor);
     }
     location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>"
                                                +"&ed43_i_codigo=<?=$ed43_i_codigo?>"
                                                +"&tipo="+tipo
                                                +"&codigo="+codigo
                                                +"&valor="+adiante
                                                +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                                +"&alterar";
    } else {
     alert("Intervalos da Nota devem ser de "+js_cent(variacao)+"");
     campo.value = "";
     campo.focus();
    }
   }
  } else {
   alert("Nota deve ser um número!");
   campo.value = "";
   campo.focus();
  }
 } else {
  location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>"
                                            +"&ed43_i_codigo=<?=$ed43_i_codigo?>"
                                            +"&tipo="+tipo
                                            +"&codigo="+codigo
                                            +"&valor="+campo.value
                                            +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                            +"&alterar";
 }
}
function js_conceito(valor,codigo,tipo) {
 location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>"
                                           +"&ed43_i_codigo=<?=$ed43_i_codigo?>"
                                           +"&tipo="+tipo
                                           +"&codigo="+codigo
                                           +"&valor="+valor
                                           +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                           +"&alterar";
}
function js_aprovmin(campo,codigo,neeparecer) {
 location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>"
                                           +"&ed43_i_codigo=<?=$ed43_i_codigo?>"
                                           +"&codigo="+codigo
                                           +"&valor="+campo.value
                                           +"&neeparecer="+neeparecer
                                           +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                           +"&aprovminimo";
}
function js_parecer(campo,codigo,resultado,periodo,aluno,encerrado,turma,codaluno) {
 js_OpenJanelaIframe('','db_iframe_parecer','edu1_parecerresult001.php?regencia=<?=$regencia?>'
                                                                    +'&ed43_i_codigo='+resultado
                                                                    +'&ed63_i_diarioresultado='+codigo
                                                                    +'&campo='+campo.name
                                                                    +'&periodo='+periodo
                                                                    +'&aluno='+aluno
                                                                    +'&encerrado='+encerrado
                                                                    +'&turma='+turma
                                                                    +"&iTrocaTurma=<?=$iTrocaTurma?>"
                                                                    +'&codaluno='+codaluno,
                                                                    'Parecer',
                                                                    true,0,0,screen.availWidth-50,screen.availHeight);
}
function js_movimentos(matricula) {
 js_OpenJanelaIframe('','db_iframe_movimentos','edu1_matricula005.php?matricula='+matricula,'Movimentação da Matrícula',true,0,0,screen.availWidth-50,screen.availHeight);
}
function js_cent(amount) {
 //retorna o valor com 2 casas decimais
 <?if ($lCasasDecimais) {?>
  return(amount == Math.floor(amount)) ? amount + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
 <?} else {?>
  return(amount == Math.floor(amount)) ? Math.floor(amount) : ( (amount*10 == Math.floor(amount*10)) ? Math.floor(amount) : Math.floor(amount));
 <?}?>
}
function js_dec(cantidad, decimales) {
 //arredonda o valor
 var cantidad = parseFloat(cantidad);
 var decimales = parseFloat(decimales);
 decimales = (!decimales ? 2 : decimales);
 return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}
</script>