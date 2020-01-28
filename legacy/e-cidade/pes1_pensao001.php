<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("classes/db_pensao_classe.php"));
include(modification("classes/db_pensaoretencao_classe.php"));
include(modification("classes/db_pensaocontabancaria_classe.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

/*
 * seta o id a instituição selecionada 
 */
$instit                = db_getsession('DB_instit');
$clpensao              = new cl_pensao();
$clpensaoretencao      = new cl_pensaoretencao();
$clpensaocontabancaria = new cl_pensaocontabancaria();
$clrhpessoal           = new cl_rhpessoal();

$db_opcao      = 1;
$db_botao      = true;
$limpar_campos = false;
$lErro         = false;

if(isset($incluir)){
	
  $clpensao->r52_valfer = "0";
  $clpensao->r52_pagfer = 'f';
  
  db_inicio_transacao();

  if ($r52_pag13 == 'f') {
  	
  	$r52_adiantamento13 = 'f';
  	$clpensao->r52_adiantamento13 = 'false';
  }
  
  if ($r52_adiantamento13 == 'f') {
    $clpensao->r52_percadiantamento13 = "0";
  }


  if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    global $r52_pagasuplementar;
    $r52_pagasuplementar                              = 'f';
    $GLOBALS["HTTP_POST_VARS"]["r52_pagasuplementar"] = 'f';
    $clpensao->r52_pagasuplementar                    = 'f';
  }


  $clpensao->r52_sequencial = null; 
  $clpensao->r52_codbco     = $inputCodigoBanco; 
  $clpensao->r52_codage     = $inputNumeroAgencia; 
  $clpensao->r52_relacaodependencia  = $r52_relacaodependencia;
  $clpensao->r52_conta      = $inputNumeroConta;
  $clpensao->r52_dvagencia  = $inputDvAgencia; 
  $clpensao->r52_dvconta    = $inputDvConta; 
  $clpensao->db83_tipoconta = $cboTipoConta; 
  $clpensao->incluir($r52_anousu,$r52_mesusu,$r52_regist,$r52_numcgm);

  
  $sWhere = "rh139_regist = $r52_regist 
         and rh139_numcgm = $r52_numcgm 
         and rh139_anousu = $r52_anousu 
         and rh139_mesusu = $r52_mesusu";

  $sSqlPensaoContaBancaria = $clpensaocontabancaria->sql_query_file(null, "rh139_contabancaria", null, $sWhere);


  $rsContabancaria = db_query($sSqlPensaoContaBancaria);

  if (pg_num_rows($rsContabancaria) > 0){

    $iContaBancaria = db_utils::fieldsMemory($rsContabancaria, 0)->rh139_contabancaria;
    $oDaoContaBancaria = new cl_contabancaria();
    $oDaoContaBancaria->db83_sequencial     = $iContaBancaria;
    $oDaoContaBancaria->db83_tipoconta      = $cboTipoConta;
    $oDaoContaBancaria->db83_codigooperacao = $inputOperacao;
    $oDaoContaBancaria->alterar($iContaBancaria);
  }

  
  if ($clpensao->erro_status=="0") {
    $lErro = true;
  }

  
  if ( !$lErro ) {
	  if ( isset($rh77_retencaotiporec) && trim($rh77_retencaotiporec) != '' ) {
		  $clpensaoretencao->rh77_anousu = $r52_anousu;
		  $clpensaoretencao->rh77_mesusu = $r52_mesusu;
		  $clpensaoretencao->rh77_numcgm = $r52_numcgm;
		  $clpensaoretencao->rh77_regist = $r52_regist;	  	
		  $clpensaoretencao->rh77_retencaotiporec = $rh77_retencaotiporec;
	  	$clpensaoretencao->incluir(null);
	  	if ( $clpensaoretencao->erro_status == "0") {
	  		$lErro = true;
	  	}
	  }
  }
  
  if ( !$lErro ) {
    $limpar_campos = true;
    if(isset($db_opcaoal) && $db_opcaoal==22){
      $clicar = "clicar";
    }     
  }  
  
  db_fim_transacao($lErro);
  
}elseif(isset($alterar)){
	
  db_inicio_transacao();
  
  if($r52_pagres == 'f'){
    $clpensao->r52_valres = "0" ;
  }  
  if($r52_pag13 == 'f'){
    $clpensao->r52_val13 = "0" ;
  }  
  if($r52_pagfer == 'f'){
    $clpensao->r52_valfer = "0" ;
  }  
  if($r52_pagcom == 'f'){
    $clpensao->r52_valcom = "0" ;
  }
  
  if ($r52_pag13 == 'f') {
    
    $r52_adiantamento13 = 'f';
    $clpensao->r52_adiantamento13 = 'false';
  }
  
  if ($r52_adiantamento13 == 'f') {
  	$clpensao->r52_percadiantamento13 = "0";
  }

  $clpensao->r52_codbco     = $inputCodigoBanco; 
  $clpensao->r52_codage     = $inputNumeroAgencia; 
  $clpensao->r52_conta      = $inputNumeroConta; 
  $clpensao->r52_dvagencia  = $inputDvAgencia; 
  $clpensao->r52_dvconta    = $inputDvConta;
  $clpensao->db83_tipoconta = $cboTipoConta;
  $clpensao->r52_relacaodependencia  = $r52_relacaodependencia;
  $clpensao->alterar($r52_anousu,$r52_mesusu,$r52_regist,$r52_numcgm);

  $sWhere = "rh139_regist = $r52_regist 
         and rh139_numcgm = $r52_numcgm 
         and rh139_anousu = $r52_anousu 
         and rh139_mesusu = $r52_mesusu";

  $sSqlPensaoContaBancaria = $clpensaocontabancaria->sql_query_file(null, "rh139_contabancaria", null, $sWhere);


  $rsContabancaria = db_query($sSqlPensaoContaBancaria);

  if (pg_num_rows($rsContabancaria) > 0){

    $iContaBancaria = db_utils::fieldsMemory($rsContabancaria, 0)->rh139_contabancaria;
    $oDaoContaBancaria = new cl_contabancaria();
    $oDaoContaBancaria->db83_sequencial     = $iContaBancaria;
    $oDaoContaBancaria->db83_tipoconta      = $cboTipoConta;
    $oDaoContaBancaria->db83_codigooperacao = $inputOperacao;
    $oDaoContaBancaria->alterar($iContaBancaria);
  }
  
  if ( $clpensao->erro_status == "0") {
    $lErro = true;	
  }

  if ( !$lErro ) {
  	
    $clpensaoretencao->rh77_anousu = $r52_anousu;
    $clpensaoretencao->rh77_mesusu = $r52_mesusu;
    $clpensaoretencao->rh77_numcgm = $r52_numcgm;
    $clpensaoretencao->rh77_regist = $r52_regist;       	
  	
	  $sWhereRetencao  = "    rh77_numcgm = {$r52_numcgm} ";
	  $sWhereRetencao .= "and rh77_regist = {$r52_regist} ";
	  $sWhereRetencao .= "and rh77_anousu = {$r52_anousu} ";
	  $sWhereRetencao .= "and rh77_mesusu = {$r52_mesusu} ";
	  $sWhereRetencao .= "and rh77_regist = {$r52_regist} ";
	    
	  $sCamposRetencao  = "rh77_sequencial,     ";
	  $sCamposRetencao .= "rh77_retencaotiporec ";

	  $rsRetencao = $clpensaoretencao->sql_record($clpensaoretencao->sql_query_file(null,$sCamposRetencao,null,$sWhereRetencao));
	  
	  if ( $clpensaoretencao->numrows > 0 ) {
	    $oRetencao = db_utils::fieldsMemory($rsRetencao,0);
	    if ( trim($rh77_retencaotiporec) != '') {
	      if ( $oRetencao->rh77_retencaotiporec != $rh77_retencaotiporec ) {
	        $clpensaoretencao->rh77_retencaotiporec = $rh77_retencaotiporec;
          $clpensaoretencao->rh77_sequencial      = $oRetencao->rh77_sequencial;
	        $clpensaoretencao->alterar($oRetencao->rh77_sequencial);
	        if ( $clpensaoretencao->erro_status == "0") {
         	  $lErro = true;
	        }
	      }
	    } else {
	      $clpensaoretencao->excluir($oRetencao->rh77_sequencial);
	      if ( $clpensaoretencao->erro_status == "0") {
          $lErro = true;
        }
	    }
	  } else {
	    if ( trim($rh77_retencaotiporec) != '') {
	      $clpensaoretencao->rh77_retencaotiporec = $rh77_retencaotiporec;
	      $clpensaoretencao->incluir(null);
        if ( $clpensaoretencao->erro_status == "0") {
        	die($clpensaoretencao->erro_msg);
          $lErro = true;
        }	      
	    }     
	  }
  }
      
  if( $lErro ){
    $db_opcao = "2";
  }else{
    if(isset($db_opcaoal) && $db_opcaoal==22){
      $clicar = "clicar";
    }
    $limpar_campos = true;
  }
  
  db_fim_transacao($lErro);
  
}elseif(isset($excluir)){
	
  db_inicio_transacao();
  
  $numcgm = null;
  
  if(isset($db_opcaoal) && $db_opcaoal!=33){
  	$numcgm = $r52_numcgm;
  }
  
  $sWhereRetencao  = "    rh77_regist = {$r52_regist} ";
  $sWhereRetencao .= "and rh77_anousu = {$r52_anousu} ";
  $sWhereRetencao .= "and rh77_mesusu = {$r52_mesusu} ";

  if ( trim($numcgm) != '') {  
    $sWhereRetencao .= "and rh77_numcgm = {$numcgm}   ";
  }
  
  $clpensaoretencao->excluir(null,$sWhereRetencao);
  
  if ( $clpensaoretencao->erro_status == "0" ) {
  	$lErro = true;  
  }

  $oDaoPensao    = new cl_pensao();
  $iSequencial   = $oDaoPensao->getSequencial($r52_regist, $r52_numcgm, $r52_anousu, $r52_mesusu );

  $oDAOHistorico = new cl_rhhistoricopensao();
  $oDAOHistorico->excluir(null, "rh145_pensao = $iSequencial");

  if ( $oDAOHistorico->erro_status == "0" ) {
    $lErro = true;  
  }

  $iSequencial   = $oDaoPensao->getSequencial($r52_regist, $r52_numcgm, $r52_anousu, $r52_mesusu );

  if ( !$lErro ) {
    $clpensao->excluir($r52_anousu,$r52_mesusu,$r52_regist,$numcgm);
    if($clpensao->erro_status=="0"){
      $lErro = true;
    }
  }
  
  if( $lErro ){
  	$db_opcao = "3";
  }else{
  	if(isset($db_opcaoal) && $db_opcaoal==33){
  	  unset($r52_regist, $z01_nome, $clicar);
  	}else if(isset($db_opcaoal) && $db_opcaoal==22){
  	  $clicar = "clicar";
  	}
    $limpar_campos = true;
  }
  
  db_fim_transacao($lErro);
  
}else if(isset($opcao)){
	
  $sql = $clpensao->sql_query_dados(
                                    $r52_anousu,
                                    $r52_mesusu,
                                    $r52_regist,
                                    $r52_numcgm,
                                    "
                                     r52_anousu,
                                     r52_mesusu,
                                     r52_regist,
                                     cgm.z01_nome as z01_nome02,
                                     a.z01_nome,
                                     r52_formul,
                                     r52_perc,
                                     r52_numcgm,
                                     r52_codbco,
                                     db90_descr,
                                     r52_codage,
                                     r52_conta,
                                     r52_vlrpen,
                                     r52_dtincl,
                                     r52_pag13,
                                     r52_pagres,
                                     r52_pagfer,
                                     r52_pagcom,
                                     r52_pagasuplementar,
                                     r52_valor,
                                     r52_valcom,
                                     r52_val13,
                                     r52_valres,
                                     r52_limite,
                                     r52_dvagencia,
                                     r52_dvconta,
                                     r52_valfer,
                                     r52_adiantamento13,
                                     r52_percadiantamento13,
                                     r52_pagasuplementar,
                                     rh77_retencaotiporec,
                                     e21_descricao,
                                     rh139_contabancaria,
                                     r52_relacaodependencia
                                    ",
                                    "
                                     z01_nome
                                    "
                                    );
  
  $result_dados_pensao = $clpensao->sql_record($sql);

  if($clpensao->numrows > 0){
  	db_fieldsmemory($result_dados_pensao,0);
  }

  if((isset($opcao) && $opcao == "excluir") || (isset($db_opcaoal) && $db_opcaoal==33)){
  	$db_opcao = "3";
  }else if((isset($opcao) && $opcao == "alterar") || (isset($db_opcaoal) && ($db_opcaoal==22 || $db_opcaoal==11))){
  	$db_opcao = "2";
  }
}else if((isset($r52_regist) && trim($r52_regist)!="") || isset($chavepesquisa)){
  if(isset($chavepesquisa)){
    $r52_regist = $chavepesquisa2;
  }
  $result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r52_regist,"z01_nome"));
  if($clrhpessoal->numrows > 0){
  	db_fieldsmemory($result_registro,0);
  }
}
if($limpar_campos == true){
  unset($z01_nome02,$r52_formul,$r52_perc,$r52_numcgm,$r52_codbco,$db90_descr,$r52_codage,$r52_conta,$r52_vlrpen,$r52_dtincl,$r52_pagres,$r52_pag13,$r52_pagfer,$r52_pagcom,$r52_valor,$r52_valcom,$r52_val13,$r52_valres,$r52_limite,$r52_dvagencia,$r52_dvconta,$rh77_retencaotiporec,$e21_descricao, $r52_relacaodependencia);
  unset($r52_valfer,$r52_adiantamento13,$r52_percadiantamento13);
}

if (isset($r52_percadiantamento13)) {
	
	if (empty($r52_percadiantamento13)) {
	  $r52_percadiantamento13 = 0;	
	}
} else {
	$r52_percadiantamento13 = 0;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewContaBancariaServidor.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=((isset($r52_regist) && trim($r52_regist)!="")?((isset($r52_numcgm) && trim($r52_numcgm)!="")?"document.form1.r52_formul.select();":"document.form1.r52_numcgm.focus();"):"document.form1.r52_regist.focus();")?>" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
-->
	<?
	include(modification("forms/db_frmpensao.php"));
	?>
<!--
    </center>
	</td>
  </tr>
</table>
-->
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clpensao->erro_status=="0" && !isset($sqlerro)){
    $clpensao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clpensao->erro_campo!=""){
      echo "<script> document.form1.".$clpensao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpensao->erro_campo.".focus();</script>";
    };
  }else if(isset($sqlerro)){
  	db_msgbox($erro_msg);
  };
};
if(isset($db_opcaoal) && ($db_opcaoal==22 || $db_opcaoal==33) && !isset($clicar) && (!isset($sqlerro) || (isset($sqlerro) && $sqlerro==false))){
  echo "<script> document.form1.pesquisar.click();</script>";
}
?>
