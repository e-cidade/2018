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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_cfiptu_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_documentotemplate_classe.php"));

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cldb_documentotemplate = new cl_db_documentotemplate;
$clcfiptu               = new cl_cfiptu;
$oDaoReceita            = new cl_tabrec;
$oDaoArretipo           = new cl_arretipo();
$db_botao               = false;
$db_opcao               = 2;

try {

 /**
  * Valida Certidao Existencia
  */
if(isset($j18_templatecertidaoexitencia) && $j18_templatecertidaoexitencia != ""){

  $rsExistencia = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query_file(null,'*',null,"db82_sequencial = {$j18_templatecertidaoexitencia} and db82_templatetipo = 18"));

  if ($cldb_documentotemplate->numrows == 0) {
    throw new Exception("Campo Documento Template Existência Inexistente. Alteração abortada.");
  }
}

 /**
  * Valida Certidao Isenção
  */

if(isset($j18_templatecertidaoisencao) && $j18_templatecertidaoisencao != ""){

  $rsIsencao = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query_file(null,'*',null,"db82_sequencial = {$j18_templatecertidaoisencao} and db82_templatetipo = 44"));

  if ($cldb_documentotemplate->numrows == 0) {
    throw new Exception("Campo Documento Template Isenção Inexistente. Alteração abortada.");
  }
}

/**
 * Verifica se receita Predial esta vencida
 */
if( !empty($j18_rpredi) ){

  $rsReceitaPredial = $oDaoReceita->sql_record($oDaoReceita->sql_query_validaReceitaVencida($j18_anousu, $j18_rpredi));
  if ($oDaoReceita->numrows != 0) {
    throw new Exception("Receita Predial possui data limite informada inválida. Verifique cadastro da receita.");
  }
}

/**
 * Verifica se receita Predial esta vencida
 */
if( !empty($j18_rterri) ){

  $rsReceitaTerritorial = $oDaoReceita->sql_record($oDaoReceita->sql_query_validaReceitaVencida($j18_anousu, $j18_rterri));
  if ($oDaoReceita->numrows != 0) {
    throw new Exception("Receita Territorial possui data limite informada inválida. Verifique cadastro da receita.");
  }
}

if (!empty($j18_receitacreditorecalculo)) {

  $j18_receitacreditorecalculo = preg_replace('/[^0-9]/', '', $j18_receitacreditorecalculo);

  $sSqlValidacaoRecalculo = $oDaoArretipo->sql_query_file(null, "*", null, "k00_receitacredito = {$j18_receitacreditorecalculo}");
  $rsValidacaoRecalculo   = $oDaoArretipo->sql_record($sSqlValidacaoRecalculo);

  if ($oDaoArretipo->numrows != 0) {
    throw new Exception("Receita de crédito do recálculo não pode estar configurada em nenhum tipo de débito.");
  }
}

if(isset( $alterar )){

  db_inicio_transacao();
    $clcfiptu->alterar($j18_anousu);
  db_fim_transacao();

} else if ( isset($incluir) ) {

  db_inicio_transacao();
    $clcfiptu->incluir($j18_anousu);
  db_fim_transacao();

} else {

	$db_botao = true;
	$result   = $clcfiptu->sql_record($clcfiptu->sql_query_param(db_getsession('DB_anousu')));
	if($result!=false && $clcfiptu->numrows>0){

	  db_fieldsmemory($result,0);
	  $utilizadocpadrao = ($j18_templatecertidaoisencao==""?"0":"1");
	} else {
		$db_opcao = 1;
	}
}

if (isset($importar)) {

	$iAnoAnt  = ($j18_anousu-1);
  $rsCfiptu = $clcfiptu->sql_record($clcfiptu->sql_query_param($iAnoAnt));

  if ($rsCfiptu != false && $clcfiptu->numrows > 0) {
    db_fieldsmemory($rsCfiptu, 0);
  }
}

/**
 * Busca Descrições das receitas
 */
if(isset($j18_rpredi)){

  $sSqlReceitaPredial = $oDaoReceita->sql_query_file( $j18_rpredi );
  $rsReceitaPredial   = $oDaoReceita->sql_record( $sSqlReceitaPredial );

  if( $rsReceitaPredial ){
    $k02_descrPredial  = db_utils::fieldsMemory( $rsReceitaPredial, 0 )->k02_descr;
  }
}

if(isset($j18_rterri)){

  $sSqlReceitaTerritorial = $oDaoReceita->sql_query_file( $j18_rterri );
  $rsReceitaTerritorial   = $oDaoReceita->sql_record( $sSqlReceitaTerritorial );

  if( $rsReceitaTerritorial ){
    $k02_descrTerritorial  = db_utils::fieldsMemory( $rsReceitaTerritorial, 0 )->k02_descr;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="a=1; js_template(<?=$utilizadocpadrao?>);" >
  <div class="container">
    <?php
      require_once(modification("forms/db_frmcfiptu.php"));
    ?>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?php

if(isset($alterar) || isset($incluir)){
  if($clcfiptu->erro_status=="0"){
    $clcfiptu->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfiptu->erro_campo!=""){
      echo "<script> document.form1.".$clcfiptu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfiptu->erro_campo.".focus();</script>";
    }
  }else{
    $clcfiptu->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}

}catch(Exception $oErro) {

  db_msgbox($oErro->getMessage());
  db_redireciona('cad1_cfiptu002.php');
  db_fim_transacao(true);

}
?>
