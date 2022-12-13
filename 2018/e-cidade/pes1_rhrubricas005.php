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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhrubricas_classe.php"));
include(modification("classes/db_rhrubelemento_classe.php"));
include(modification("classes/db_rhrubretencao_classe.php"));

$clrhrubricas    = new cl_rhrubricas();
$clrhrubelemento = new cl_rhrubelemento();
$clrhrubretencao = new cl_rhrubretencao();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($novasrubricas)){
  db_inicio_transacao();
  $calc1 = "";
  $calc2 = "";
  $calc3 = "";
  $sqlerro = false;

  $clrhrubricas->rh27_form  = str_replace("\\","",$rh27_form);
  $clrhrubricas->rh27_form2 = str_replace("\\","",$rh27_form2);
  $clrhrubricas->rh27_form3 = str_replace("\\","",$rh27_form3);
  $clrhrubricas->rh27_formq = str_replace("\\","",$rh27_formq);
//$clrhrubricas->rh27_cond2 = str_replace("\\","",$rh27_cond2);
//$clrhrubricas->rh27_cond3 = str_replace("\\","",$rh27_cond3);
  $clrhrubricas->rh27_cond2 = stripslashes($rh27_cond2);
  $clrhrubricas->rh27_cond3 = stripslashes($rh27_cond3);
  $clrhrubricas->rh27_cond2 = addslashes($rh27_cond2);
  $clrhrubricas->rh27_cond3 = addslashes($rh27_cond3);
  $clrhrubricas->rh27_obs   = str_replace("\\","",$rh27_obs);

  if(!isset($novasrubricas)){
    if($rh27_calc1 > 0){
      $rubricateste = $rh27_rubric + 2000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc1 = $rubricateste;
      }
    }

    if($rh27_calc2 > 0){
      $rubricateste = $rh27_rubric + 4000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc2 = $rubricateste;
      }
    }

    if($rh27_calc3 == 't'){
      $rubricateste = $rh27_rubric + 6000;
      // die($clrhrubricas->sql_query_file($rubricateste));
      $result_teste = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubricateste,db_getsession("DB_instit")));
      if($clrhrubricas->numrows == 0){
        $calc3 = $rubricateste;
      }
    }
  }else{
    $arr_codigos = split(",",$novasrubricas);
    for($i=0; $i<count($arr_codigos); $i++){
      $rubricainclui = $arr_codigos[$i];
      if($i == 0){
        $varsubstr = $rh27_descr." S/ FÉRIAS";
        $descricinclui = substr($varsubstr,0,30);
      }else if($i == 1){
        $varsubstr = $rh27_descr." S/ 13o SALÁRIO";
        $descricinclui = substr($varsubstr,0,30);
      }else if($i == 2){
        $varsubstr = $rh27_descr." S/ RESCISÃO";
        $descricinclui = substr($varsubstr,0,30);
      }

      if($rubricainclui != 0){

        if($sqlerro == false){
          $clrhrubricas->rh27_tipo  = "2";
          $clrhrubricas->rh27_calc1 = "0";
          $clrhrubricas->rh27_calc2 = "0";
          $clrhrubricas->rh27_calc3 = "false";
          $clrhrubricas->rh27_descr = $descricinclui;
    		  $clrhrubricas->rh27_instit = db_getsession("DB_instit");
          $clrhrubricas->incluir($rubricainclui,db_getsession("DB_instit"));
          $erro_msg = $clrhrubricas->erro_msg;
          if($clrhrubricas->erro_status==0){
            $sqlerro = true;
            break;
          }
        }

        if ( !$sqlerro ) {
          $clrhrubelemento->excluir($rubricainclui,db_getsession("DB_instit"));
          $erro_msg = $clrhrubelemento->erro_msg;
          if($clrhrubelemento->erro_status == 0 ){
            $sqlerro=true;
          }
        }
        if ( !$sqlerro ) {
        	$sWhereExcluiRetencao  = "     rh75_rubric = '{$rh27_rubric}' ";
        	$sWhereExcluiRetencao .= " and rh75_instit = ".db_getsession('DB_instit');
          $clrhrubretencao->excluir(null,$sWhereExcluiRetencao);
          if($clrhrubretencao->erro_status == 0 ){
            $erro_msg = $clrhrubretencao->erro_msg;
            $sqlerro=true;
          }
        }
      }
    }
  }

  if ( !empty($rh27_rhfundamentacaolegal) ) {
    $oDaoFundamentacaoLegal = new cl_rhfundamentacaolegal();
    $sSqlFundamentacaoLegal = $oDaoFundamentacaoLegal->sql_query_file($rh27_rhfundamentacaolegal);
    $rsFundamentacao        = db_query($sSqlFundamentacaoLegal);

    if ( !$rsFundamentacao ) {
      $sqlerro = true;
      $erro_msg = 'Não foi possível consultar a fundamentação legal informada.';
    }

    if ( pg_num_rows($rsFundamentacao) == 0 ) {
      $sqlerro = false;
      $clrhrubricas->rh27_rhfundamentacaolegal = null;
      $GLOBALS["HTTP_POST_VARS"]["rh27_rhfundamentacaolegal"] = null;
    }
  }

  if($calc1 == "" && $calc2 == "" && $calc3 == "" && $sqlerro == false){
    if($rh27_calc3 == 't'){
      $inccalc3 = 'true';
    }else{
      $inccalc3 = 'false';
    }
    $clrhrubricas->rh27_rubric= $rh27_rubric;
    $clrhrubricas->rh27_tipo  = $rh27_tipo;
    $clrhrubricas->rh27_calc1 = $rh27_calc1;
    $clrhrubricas->rh27_calc2 = $rh27_calc2;
    $clrhrubricas->rh27_calc3 = $inccalc3;
    $clrhrubricas->rh27_descr = $rh27_descr;
    $clrhrubricas->rh27_periodolancamento = $rh27_periodolancamento == 't' ? 'true' : 'false';
	  $clrhrubricas->rh27_instit = db_getsession("DB_instit");
    $clrhrubricas->alterar($rh27_rubric,db_getsession("DB_instit"));
    $erro_msg = $clrhrubricas->erro_msg;
    $rh27_rubric = $clrhrubricas->rh27_rubric;
    if($clrhrubricas->erro_status==0){
      $sqlerro = true;
      $rh27_cond2 = stripslashes($rh27_cond2);
      $rh27_cond3 = stripslashes($rh27_cond3);
    }
    if( !$sqlerro ){
      // <!-- ContratosPADRS: tipo de rubrica alterar -->
      $clrhrubelemento->excluir($rh27_rubric,db_getsession("DB_instit"));
      if($clrhrubelemento->erro_status == 0 ){
      	$erro_msg = $clrhrubelemento->erro_msg;
        $sqlerro=true;
      }
    }

    if ( !$sqlerro ) {
      $sWhereExcluiRetencao  = "     rh75_rubric = '{$rh27_rubric}' ";
      $sWhereExcluiRetencao .= " and rh75_instit = ".db_getsession('DB_instit');
      $clrhrubretencao->excluir(null,$sWhereExcluiRetencao);
      if($clrhrubretencao->erro_status == 0 ){
        $erro_msg = $clrhrubretencao->erro_msg;
        $sqlerro=true;
      }
    }


    if ( isset($tipo) ) {
    	if ( $tipo == 'e') {
		    if( !$sqlerro && isset($rh23_codele) && trim($rh23_codele) != ""){
		      $clrhrubelemento->incluir($rh27_rubric,db_getsession("DB_instit"));
		      if($clrhrubelemento->erro_status == 0 ){
		      	$erro_msg = $clrhrubelemento->erro_msg;
		        $sqlerro=true;
		      }
		    }
    	} else if ( $tipo == 'c' || $tipo == 'p' || $tipo == 'd') {
        if( isset($rh75_retencaotiporec) && trim($rh75_retencaotiporec) != ""){
          $clrhrubretencao->rh75_retencaotiporec = $rh75_retencaotiporec;
          $clrhrubretencao->rh75_instit          = db_getsession('DB_instit');
          $clrhrubretencao->rh75_rubric          = $rh27_rubric;
          $clrhrubretencao->incluir(null);
          if($clrhrubretencao->erro_status == 0 ){
            $erro_msg = $clrhrubretencao->erro_msg;
            $sqlerro=true;
          }
        }
    	}
    }
  //sqlerro = true;
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;
  if($sqlerro == false){
    $chavepesquisa = $rh27_rubric;
  }
}
// }else if(isset($chavepesquisa)){
// Separei este "IF" para que não fique aparecendo contra-barras nos campos text e textarea
} else if(isset($chavepesquisa)) {
  $db_opcao = 2;
  $db_botao = true;
  $sCampos = "rhrubricas.*,
              db_config.*,
              rhtipomedia.rh29_descr,
              b.rh29_descr as rh29_descr2,
              rhfundamentacaolegal.rh137_numero||' - '||rhfundamentacaolegal.rh137_descricao as rh137_descricao,
              previdencia_complementar.z01_nome ";

  $result = $clrhrubricas->sql_record($clrhrubricas->sql_query($chavepesquisa,db_getsession("DB_instit"),$sCampos));
  db_fieldsmemory($result,0);

  $rh27_cond2 = stripslashes($rh27_cond2);
  $rh27_cond3 = stripslashes($rh27_cond3);

  $sWhereRhrubelemento    = "      rhrubelemento.rh23_rubric = '{$chavepesquisa}'";
  $sWhereRhrubelemento   .= " and rhrubelemento.rh23_instit = ". db_getsession("DB_instit");
  $sWhereRhrubelemento   .= " and o56_anousu = ". db_getsession("DB_anousu");
  $result = $clrhrubelemento->sql_record($clrhrubelemento->sql_query($chavepesquisa,db_getsession("DB_instit"), "*", " o56_anousu desc", $sWhereRhrubelemento));
  if($clrhrubelemento->numrows > 0){
    db_fieldsmemory($result, 0);
  } else {
    $rh23_codele = '';
    $o56_descr   = '';
  }

  $sWhereRetencao   = "     rh75_rubric = '{$chavepesquisa}'";
  $sWhereRetencao  .= " and rh75_instit = ".db_getsession('DB_instit');

  $sCamposRetencao  = " rh75_retencaotiporec,";
  $sCamposRetencao .= " e21_descricao,";
  $sCamposRetencao .= " e21_retencaotiporecgrupo";


  $rsRetencao = $clrhrubretencao->sql_record($clrhrubretencao->sql_query(null,$sCamposRetencao,null,$sWhereRetencao));

  if ( $clrhrubretencao->numrows > 0 ) {
    db_fieldsmemory($rsRetencao,0);
  } else {
  	$rh75_retencaotiporec = '';
  	$e21_descricao        = '';
  }

  $oPost  = db_utils::postMemory($_POST);

  if (isset($oPost->rh27_calc1) && $oPost->rh27_calc1 != 0) {
    $rh27_calc1 = $oPost->rh27_calc1;
  }
  if (isset($oPost->rh27_calc2) && $oPost->rh27_calc2 != 0) {
    $rh27_calc2 = $oPost->rh27_calc2;
  }
  if (isset($oPost->rh27_quant) && $oPost->rh27_quant != 0) {
    $rh27_quant = $oPost->rh27_quant;
  }
  if (isset($oPost->rh27_obs) && $oPost->rh27_obs != '') {
    $rh27_obs = $oPost->rh27_obs;
  }
  if (isset($oPost->rh23_codele) && $oPost->rh23_codele != '') {
    $rh23_codele = $oPost->rh23_codele;
  }
  if (isset($oPost->rh23_codele) && $oPost->rh23_codele != '') {
    $rh23_codele = $oPost->rh23_codele;
  }
  if (isset($oPost->rh27_form) && $oPost->rh27_form != '') {
    $rh27_form = $oPost->rh27_form;
  }
  if (isset($oPost->rh27_form2) && $oPost->rh27_form2 != '') {
    $rh27_form2 = $oPost->rh27_form2;
  }
  if (isset($oPost->rh27_form3) && $oPost->rh27_form3 != '') {
    $rh27_form3 = $oPost->rh27_form3;
  }
  if (isset($oPost->rh27_cond2) && $oPost->rh27_cond2 != '') {
    $rh27_cond2 = $oPost->rh27_cond2;
  }
  if (isset($oPost->rh27_cond3) && $oPost->rh27_cond3 != '') {
    $rh27_cond3 = $oPost->rh27_cond3;
  }
  if (isset($oPost->rh27_limdat) && $oPost->rh27_limdat != '') {
    $rh27_limdat = $oPost->rh27_limdat;
  }
  if (isset($oPost->rh27_tipo) && $oPost->rh27_tipo != '') {
    $rh27_tipo = $oPost->rh27_tipo;
  }
  if (isset($oPost->rh27_pd) && $oPost->rh27_pd != '') {
    $rh27_pd = $oPost->rh27_pd;
  }
  if (isset($oPost->rh27_ativo) && $oPost->rh27_ativo != '') {
    $rh27_ativo = $oPost->rh27_ativo;
  }
  if (isset($oPost->rh27_calc3) && $oPost->rh27_calc3 != '') {
    $rh27_calc3 = $oPost->rh27_calc3;
  }
  if (isset($oPost->rh27_presta) && $oPost->rh27_presta != '') {
    $rh27_presta = $oPost->rh27_presta;
  }
  if (isset($oPost->rh27_propi) && $oPost->rh27_propi != '') {
    $rh27_propi = $oPost->rh27_propi;
  }
  if (isset($oPost->rh27_calcp) && $oPost->rh27_calcp != '') {
    $rh27_calcp = $oPost->rh27_calcp;
  }
  if (isset($oPost->rh27_propq) && $oPost->rh27_propq != '') {
    $rh27_propq = $oPost->rh27_propq;
  }
  if (isset($oPost->rh27_periodolancamento) && $oPost->rh27_periodolancamento != '') {
    $rh27_periodolancamento = $oPost->rh27_periodolancamento;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <? include(modification("forms/db_frmrhrubricas.php")); ?>
</body>
</html>
<?
if(isset($alterar) || isset($novasrubricas)){
  if($calc1 == "" && $calc2 == "" && $calc3 == ""){
    if($sqlerro==true){
      db_msgbox($erro_msg);
      if($clrhrubricas->erro_campo!=""){
        echo "<script> document.form1.".$clrhrubricas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhrubricas->erro_campo.".focus();</script>";
      };
    }else{
      $clrhrubricas->erro(true,true);
    }
  }else{
    $ccalculos = "";
    $dcalculos = "";
    $cvirgulas = "";
    $dvirgulas = "";
    if($calc1 != ""){
      $ccalculos.= $cvirgulas.$calc1;
      $dcalculos.= $dvirgulas.$calc1." - (".$rh27_descr." S/ FÉRIAS)";
      $cvirgulas = ",";
      if($calc2 != "" && $calc3 != ""){
        $dvirgulas = ", ";
      }else{
        $dvirgulas = " e ";
      }
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    if($calc2 != ""){
      $ccalculos.= $cvirgulas.$calc2;
      $dcalculos.= $dvirgulas.$calc2." - (".$rh27_descr." S/ 13o SALÁRIO)";
      $cvirgulas = ",";
      $dvirgulas = " e ";
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    if($calc3 != ""){
      $ccalculos.= $cvirgulas.$calc3;
      $dcalculos.= $dvirgulas.$calc3." - (".$rh27_descr." S/ RESCISÃO)";
    }else{
      $ccalculos.= $cvirgulas."0";
      $cvirgulas = ",";
    }
    echo "
          <script>
            alert('Será(ão) gerada(s) a(s) rubrica(s) ".$dcalculos.", não esqueça de configurar suas bases.');
            obj=document.createElement('input');
            obj.setAttribute('name','novasrubricas');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','".$ccalculos."');
            document.form1.appendChild(obj);
            document.form1.submit();
          </script>
         ";
  }
}
if(isset($chavepesquisa)){
  echo "
        <script>
          function js_db_libera(){
            /*
            parent.document.formaba.rhrubelemento.disabled=false;
            (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhrubelemento.location.href='pes1_rhrubelemento001.php?rh23_rubric=".@$rh27_rubric."';
            */
            parent.document.formaba.rhbases.disabled=false;
            (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhbases.location.href='pes1_rhbases004.php?r09_rubric=".@$rh27_rubric."';
       ";
  if(isset($liberaaba)){
    echo "  parent.mo_camada('rhbases');";
  }
  echo"   }\n
          js_db_libera();
        </script>\n
      ";
}
if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
