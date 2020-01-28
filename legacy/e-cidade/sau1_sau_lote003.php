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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsau_lote         = db_utils::getdao('sau_lote');
$clsau_lotepront    = db_utils::getdao('sau_lotepront_ext');
$oDaoProntuarios    = db_utils::getdao('prontuarios');
$oDaoProntproced    = db_utils::getdao('prontproced');
$oDaoProntprocedcid = db_utils::getdao('prontprocedcid');
$oDaoProntcid       = db_utils::getdao('prontcid');
$oDaoFechapront     = db_utils::getdao('sau_fechapront');
$clunidades         = db_utils::getdao('unidades_ext');
$oDaoCgs_und        = db_utils::getdao('cgs_und');
$oDaoSau_config     = db_utils::getdao('sau_config_ext');
$oDaoProntagend     = db_utils::getdao('prontagendamento');
$oDaoProntprofatend = db_utils::getdao('prontprofatend');

$db_botao           = false;
$db_opcao           = 33;

/* CONFIGURAÇÕES SAÚDE */
$resSau_config      = $oDaoSau_config->sql_record($oDaoSau_config->sql_query_ext());
$objSau_config      = db_utils::fieldsMemory($resSau_config, 0);

/*
 * ==================================================================
 *   FUNÇÃO QUE APAGA TODOS OS REGISTROS DE UMA LISTA DE FAA'S
 *  ATRAVÉS DE UMA LISTA DE CÓDIGOS DE PRONTUÁRIOS(sd24_i_codigo)
 *
 *  *O RETORNO É UM OBJETO 'ERRO' COM SEUS ATRIBUTOS (sMsg e lError)
 * ==================================================================
 */
function excluirProntuariosLote($aLista) {

  global $oDaoFechapront;
  global $oDaoProntprocedcid;
  global $oDaoProntproced;
  global $oDaoProntcid;
  global $oDaoProntuarios;
  global $oDaoProntagend;
  global $oDaoProntprofatend;
  global $clsau_lotepront;
  $oErro->lError  = false;
  $oErro->sMsg    = "";
  $sWhere         = "";


  $sWhere  = " sd98_i_prontproced in (select sd29_i_codigo from prontproced where sd29_i_prontuario in (";
  $sWhere .= implode(",", $aLista)."))";
  $lExec   = existeRegistro($oDaoFechapront, $sWhere);
  if ($lExec) {
    $oDaoFechapront->excluir(null, $sWhere);
  }
  if ($oDaoFechapront->erro_status == "0" && $lExec) {

    $oErro->sMsg   = $oDaoFechapront->erro_msg;
    $oErro->lError = true;

  }

  if($oErro->lError == false){

    $sWhere  = "sd59_i_prontuario  in (".implode(",",$aLista).")";
    $clsau_lotepront->excluir(null, $sWhere);
    if ($clsau_lotepront->erro_status == "0") {

      $oErro->sMsg   = $clsau_lotepront->erro_msg;
      $oErro->lError = true;

    }

  }

  if($oErro->lError == false){

    $sWhere  = "s135_i_prontproced in (select sd29_i_codigo from prontproced where sd29_i_prontuario in (";
    $sWhere .= implode(",", $aLista)."))";
    $oDaoProntprocedcid->excluir(null, $sWhere);
    if ($oDaoProntprocedcid->erro_status == "0") {

      $oErro->sMsg   = $oDaoProntprocedcid->erro_msg;
      $oErro->lError = true;

    }

  }

  if ($oErro->lError == false) {

    $sWhere = " s104_i_prontuario in (".implode(",",$aLista).")";
    $lExec  = existeRegistro($oDaoProntprofatend, $sWhere);
    if ($lExec) {
      $oDaoProntprofatend->excluir(null, $sWhere);
    }
    if ($oDaoProntprofatend->erro_status == "0" && $lExec) {

      $oErro->sMsg   = $oDaoProntprofatend->erro_msg;
      $oErro->lError = true;

    }

  }

  if ($oErro->lError == false) {

    $sWhere = "sd29_i_prontuario in (".implode(",",$aLista).")";
    $oDaoProntproced->excluir(null, $sWhere);
    if ($oDaoProntproced->erro_status == "0") {

      $oErro->sMsg   = $oDaoProntproced->erro_msg;
      $oErro->lError = true;

    }

  }

  if ($oErro->lError == false) {

    $sWhere = "sd55_i_prontuario in (".implode(",",$aLista).") ";
    $lExec  = existeRegistro($oDaoProntcid, $sWhere);
    if ($lExec) {
      $oDaoProntcid->excluir(null, $sWhere);
    }
    if ($oDaoProntcid->erro_status == "0" && $lExec) {

      $oErro->sMsg   = $oDaoProntcid->erro_msg;
      $oErro->lError = true;

    }

  }

  if ($oErro->lError == false) {

    $sWhere = " s102_i_prontuario in (".implode(",",$aLista).") ";
    $lExec  = existeRegistro($oDaoProntagend, $sWhere);
    if ($lExec) {
      $oDaoProntagend->excluir(null, $sWhere);
    }
    if ($oDaoProntagend->erro_status == "0" && $lExec) {

      $$oErro->sMsg  = $oDaoProntagend->erro_msg;
      $oErro->lError = true;

    }

  }

  if ($oErro->lError == false) {

    $sWhere = "sd24_i_codigo in (".implode(",",$aLista).") ";
    $oDaoProntuarios->excluir(null, $sWhere);
    if ($oDaoProntuarios->erro_status == "0") {

      $oErro->sMsg   = $oDaoProntuarios->erro_msg;
      $oErro->lError = true;

    }

  }

  return $oErro;

}

function existeRegistro($oDao, $sWhere) {

  $sSql     = $oDao->sql_query_file(null, "*", null, $sWhere);
  $rsResult = $oDao->sql_record($sSql);
  if ($oDao->numrows > 0) {
    return true;
  }
  return false;

}

if(isset($excluir)){

  db_inicio_transacao();
  $lErro    = false;

  $sSql     = $clsau_lotepront->sql_query_file(null,"sd59_i_prontuario as cod","","sd59_i_lote = $sd58_i_codigo");
  $rsResult = $clsau_lotepront->sql_record($sSql);
  /* CASO POSSUA APENAS 1 PRONTUÁRIO NO LOTE, DEVE APAGAR O LOTE JUNTAMENTE. */
  if ($clsau_lotepront->numrows <= 1) {

    unset($excluir);
    $excluirlote = 'excluirlote';

  } else {

    $db_opcao = 3;
    /* PARA NÃO ACESSAR A ROTINA QUE RECARREGA A FAA */
    unset($chavepesquisalotepront);
    $aLista[] = $sd24_i_codigo;
    if ($lErro == false) {

      $oErro = excluirProntuariosLote($aLista);
      if ($oErro->lError == true) {

        $clsau_lotepront->erro_msg = $oErro->sMsg;

      }

    }

    if ($lErro == true) {
    	$clsau_lotepront->erro_status = "0";
    }  else {
      db_msgbox($clsau_lotepront->erro_msg);
    }

  }
  db_fim_transacao($lErro);

}

if(isset($excluirlote)){

  db_inicio_transacao();
  $db_opcao = 3;
  $lErro    = false;
  $sSql     = $clsau_lotepront->sql_query_file(null,"sd59_i_prontuario as cod", "", "sd59_i_lote = $sd58_i_codigo");
  $rsResult = $clsau_lotepront->sql_record($sSql);
  if ($clsau_lotepront->numrows > 0) {

    $aLista = array();
  	for ($iX = 0; $iX < $clsau_lotepront->numrows; $iX++) {

    	$oPront   = db_utils::fieldsmemory($rsResult, $iX);
    	$aLista[] = $oPront->cod;
  	}

  } else {

  	$clsau_lotepront->erro_msg  = "Lote[$sd58_i_codigo] sem prontuarios. ";
    $lErro                      = true;
  }

  if ($lErro == false) {

    $oErro = excluirProntuariosLote($aLista);
    if ($oErro->lError == true) {

      $clsau_lotepront->erro_msg = $oErro->sMsg;
    }
  }

  if ($lErro == false) {

    $clsau_lote->excluir($sd58_i_codigo);
    if ($clsau_lote->erro_status == "0") {
      $lErro                     = true;
    }
    $clsau_lotepront->erro_msg = $clsau_lote->erro_msg;
  }

  if ($lErro == true) {
    $clsau_lotepront->erro_status = "0";
  } else {
    db_msgbox($clsau_lotepront->erro_msg);
  }
  db_fim_transacao($lErro);

} else if(isset($chavepesquisacgs)&&(int)$chavepesquisacgs != 0){

   $db_opcao = 3;
   $result = $oDaoCgs_und->sql_record($oDaoCgs_und->sql_query($chavepesquisacgs));
   db_fieldsmemory($result,0);
   $db_botao = true;

}else if(isset($chavepesquisalote)){

  $result = $clsau_lotepront->sql_record($clsau_lotepront->sql_query_ext(null, "sau_lote.*, db_usuarios.*, sd24_i_unidade", "", "sd59_i_lote = $chavepesquisalote"));
	db_fieldsmemory($result,0);
	$db_opcao = 3;
	$db_botao = true;

}else if(isset($chavepesquisalotepront)&&(int)$chavepesquisalotepront != 0){

  $result = $clsau_lotepront->sql_record( $clsau_lotepront->sql_query_ext($chavepesquisalotepront) );
   db_fieldsmemory($result,0);
  $db_opcao = 3;
	$db_botao = true;

}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    try{
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("strings.js");
      db_app::load("webseller.js");
      db_app::load("grid.style.css");
      db_app::load("estilos.css");
    }catch (Exception $eException){
      die( $eException->getMessage() );
    }
  ?>
  <script type="text/javascript" language="JavaScript" src="scripts/AjaxRequest.js"></script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <?
          require_once("forms/db_frmsau_lote.php");
          ?>
        </center>
      </td>
    </tr>
  </table>
</body>
</html>
<?
if (isset($excluir) || isset($excluirlote)) {

  if ($clsau_lotepront->erro_status == "0") {
		$clsau_lotepront->erro(true,false);
	}
	db_redireciona("sau1_sau_lote003.php?idarq=3");

}
if ($db_opcao == 33) {
  echo "<script>js_pesquisalote();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>