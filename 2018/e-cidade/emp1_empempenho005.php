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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_emppresta_classe.php"));
require_once(modification("classes/db_empprestatip_classe.php"));
require_once(modification("classes/db_cflicita_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_emptipo_classe.php"));
require_once(modification("classes/db_empemphist_classe.php"));
require_once(modification("classes/db_emphist_classe.php"));
require_once(modification("classes/db_concarpeculiar_classe.php"));
require_once(modification("classes/db_empempenhonl_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_empempaut_classe.php"));
require_once(modification("classes/db_empautidot_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_empempitem_classe.php"));
require_once(modification("std/Modification.php"));

$clempempaut			= new cl_empempaut;
$clempempenho	  	= new cl_empempenho;
$clempautoriza  	= new cl_empautoriza;
$clemppresta	  	= new cl_emppresta;
$clempprestatip 	= new cl_empprestatip;
$clcflicita	    	= new cl_cflicita;
$clpctipocompra 	= new cl_pctipocompra;
$clemptipo	    	= new cl_emptipo;
$clemphist	    	= new cl_emphist;
$clempparametro 	= new cl_empparametro;

$clempemphist	  	= new cl_empemphist;
$clconcarpeculiar = new cl_concarpeculiar;
$oDaoEmpenhoNl  	= new cl_empempenhonl;
$clempautidot	  	= new cl_empautidot;
$clpagordem				= new cl_pagordem;
$clorcdotacao	  	= new cl_orcdotacao;
$clorcelemento    = new cl_orcelemento;
$clempautitem	  	= new cl_empautitem;
$clempelemento	  = new cl_empelemento;
$clempempitem			= new cl_empempitem;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$db_opcao =  22;
$db_botao = false;
if(isset($alterar)){

  $sqlerro=false;
  $db_botao = true;
  db_inicio_transacao();
  /*rotina de incluir  na tabela empempenho*/
  if($sqlerro==false){

    $db_opcao = 2;
    $clempempenho->alterar($e60_numemp);
    if($clempempenho->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clempempenho->erro_msg;

      // <!-- ContratosPADRS: despesa funcionario alterar -->
  }

  /**
   * Manutenção da tabela emppresta
   */
  if (isset($e44_tipo) && $e44_tipo != "") {

    $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo,"e44_obriga"));
    $opera = true;

    db_fieldsmemory($result,0);
    $clemppresta->e45_tipo = $e44_tipo;

    $sSqlEmppresta = $clemppresta->sql_query_file(null, 'e45_sequencial', null, "e45_numemp = $e60_numemp");
    $rsEmppresta =  $clemppresta->sql_record($sSqlEmppresta);

    if ( $clemppresta->numrows > 0 ) {

      $e45_sequencial = db_utils::fieldsMemory($rsEmppresta, 0)->e45_sequencial;
      $clemppresta->e45_sequencial = $e45_sequencial;
    }

    if ( !empty($e45_sequencial) && $e44_obriga != 0 ) {

      $clemppresta->e45_numemp = $e60_numemp;
      $clemppresta->alterar($e45_sequencial);

    } else if (!empty($e45_sequencial) && $e44_obriga == 0) {

      $clemppresta->e45_numemp = $e60_numemp;
      $clemppresta->excluir($e45_sequencial);

    } else if ($e44_obriga != 0) {

      $clemppresta->e45_data   = date("Y-m-d",db_getsession("DB_datausu"));
      $clemppresta->e45_numemp = $e60_numemp;
      $clemppresta->incluir(null);

    } else {
      $opera = false;
    }

    if ($opera == true) {

      $erro_msg = $clemppresta->erro_msg;
      if ($clemppresta->erro_status == '0') {

        $sqlerro  = true;
      }
    }

  }

  /**
   * rotina que inclui na tabela empemphist
   */
  if($sqlerro == false){

    $clempemphist->sql_record($clempemphist->sql_query_file($e60_numemp));

    if($clempemphist->numrows>0){

      $clempemphist->e63_numemp  = $e60_numemp ;
      $clempemphist->excluir($e60_numemp);
      $erro_msg=$clempemphist->erro_msg;

      if($clempemphist->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  if($sqlerro==false && $e63_codhist!="Nenhum"){

    $clempemphist->e63_numemp  = $e60_numemp ;
    $clempemphist->e63_codhist = $e63_codhist ;
    $clempemphist->incluir($e60_numemp);
    $erro_msg=$clempemphist->erro_msg;

    if($clempemphist->erro_status==0){
      $sqlerro=true;
    }
  }

  if (isset($e68_numemp) && $e68_numemp == "s") {

    $oDaoEmpenhoNl->e68_numemp = $e60_numemp;
    $oDaoEmpenhoNl->e68_data   = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoEmpenhoNl->incluir(null);
    if ($oDaoEmpenhoNl->erro_status == 0) {
      $sqlerro=true;
    }
  }

  if(!$sqlerro && isset($e64_codele)){

    $clempelemento->e64_codele = $e56_codele;
    $clempelemento->e64_numemp = $e60_numemp;
    $clempelemento->alterar($e60_numemp);
    if($clempelemento->erro_status=="0"){
      $sqlerro=true;
      $erro_msg = $clempelemento->erro_msg;
    }
  }

  if(!$sqlerro && isset($e64_codele)){

    $result = $clempempitem->sql_record($clempempitem->sql_query_file($e60_numemp,null,"e62_numemp,e62_sequen,e62_item"));

    $iNumRows = pg_num_rows($result);

    for ($i = 0; $i < $iNumRows; $i++) {

      $oRow = db_utils::fieldsMemory($result,$i);
      $clempempitem->e62_codele  = $e56_codele;
      $clempempitem->e62_numemp  = $oRow->e62_numemp;
      $clempempitem->e62_sequen  = $oRow->e62_sequen;
      $clempempitem->alterar($oRow->e62_numemp,$oRow->e62_sequen);

      if ($clempempitem->erro_status=="0") {

        $sqlerro=true;
        $erro_msg = $clempempitem->erro_msg;
        break;

      }
    }

  }

  if(!$sqlerro){

    $sSql = "SELECT c75_codlan, c67_codele from conlancamele inner join conlancamemp on c75_codlan = c67_codlan
                                     inner join conlancamdoc on c71_codlan = c75_codlan
              where c71_coddoc = 1 and c75_numemp = $e60_numemp ";
    $rsSql = db_query($sSql);

    $iNumRows = pg_num_rows($rsSql);
    if(isset($e56_codele) && $e56_codele != ""){
      for($i = 0; $i < $iNumRows; $i++){
        $oRow = db_utils::fieldsMemory($rsSql,$i);
        $sSqlUpdate = "update conlancamele set c67_codele = $e56_codele where c67_codlan = $oRow->c75_codlan ";

        if(db_query($sSqlUpdate)===false){
          $sqlerro = true;
          $erro_msg = "Usuário: \\n\\n Itens conlancamele nao Alterado. Alteracao Abortada \\n\\n";
          break;
        }

      }
    }

  }

  if (!$sqlerro) {

    try {

      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($e60_numemp);
      $iRecursoDotacao    = $oEmpenhoFinanceiro->getDotacao()->getRecurso();

      if ($iRecursoDotacao == ParametroCaixa::getCodigoRecursoFUNDEB(db_getsession("DB_instit"))) {

        $oEmpenhoFinanceiro->setFinalidadePagamentoFundeb(FinalidadePagamentoFundeb::getInstanciaPorCodigo($e151_codigo));
        $oEmpenhoFinanceiro->salvarFinalidadePagamentoFundeb();
      }

    } catch (Exception $eErro) {

      $sqlerro  = true;
      $erro_msg = $eErro->getMessage();
    }
  }

  $result = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp,"e60_anousu,e60_vlrliq"));

  if ( $clempempenho->erro_status == '0' ) {

    $sqlerro = true;
    $erro_msg = $clempempenho->erro_msg;
  } else {
    db_fieldsmemory($result,0);
  }
  /**[Extensao Ordenador Despesa] inclusao_ordenador*/

  db_fim_transacao($sqlerro);

} else if(isset($chavepesquisa)) {

  $rsPar = $clempparametro->sql_record($clempparametro->sql_query_file(DB_getsession("DB_anousu")));
  if ( $clempparametro->numrows > 0) {
    db_fieldsmemory($rsPar, 0);
  }
  $db_opcao = 1;
  $result = $clempempenho->sql_record($clempempenho->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);

  $result=$clempemphist->sql_record($clempemphist->sql_query_file($e60_numemp));
  if($clempemphist->numrows>0){
    db_fieldsmemory($result,0);
  }


  $result=$clemppresta->sql_record($clemppresta->sql_query_file(null,"e45_tipo as e44_tipo", null, "e45_numemp = $e60_numemp"));
  if($clemppresta->numrows > 0){
    db_fieldsmemory($result,0);
  }
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/empenho/ViewCotasMensais.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC; margin-top:35px;" >

<center>
  <fieldset style="width: 800px;">
    <legend><b>Alteração de Empenho</b></legend>
  	<?php require_once(modification(modification::getFile("forms/db_frmempempenhoaltera.php"))); ?>
  </fieldset>
</center>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<?php
if(isset($alterar)){
  if($sqlerro == true){
   db_msgbox($erro_msg);
   if($clempempenho->erro_campo!=""){
      echo "<script> document.form1.".$clempempenho->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempempenho->erro_campo.".focus();</script>";
    }
  }else{
   db_msgbox($erro_msg);
   db_redireciona('emp1_empempenho005.php');
  }
}


if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}

if(isset($mensagem)){
	$msg = "Usuário:\\n\\n".$mensagem."\\n\\n";
	db_msgbox($msg);
}
?>
