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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_lotecemit_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$cllotecemit = new cl_lotecemit;
$db_opcao = 1;
$db_botao = true;

/**
 * Validamos se a inserção e a alteração não gerarão lotes com números duplicados na mesma quadra
 */
$lOperacaoValida = true;

if ( isset($incluir) || isset($alterar) ) {

  $sWhere        = " cm23_i_quadracemit   = {$cm23_i_quadracemit} ";
  $sWhere       .= " and cm23_i_lotecemit = '{$cm23_i_lotecemit}'  ";
  $sSqlLoteCemit = $cllotecemit->sql_query_file(null, "*", null, $sWhere);

  $rsLoteCemit   = db_query($sSqlLoteCemit);

  if ( empty($rsLoteCemit) ) {

    echo "<script>alert('Erro ao verificar se há lote cadastrado para esta quadra com o mesmo número.')</script>";
    $lOperacaoValida = false;
  }

  if (pg_num_rows($rsLoteCemit) > 0) {

    echo "<script>alert('Já há um lote para esta quadra com o número desejado.')</script>";
    $lOperacaoValida = false;
  }

}

if(isset($incluir) && $lOperacaoValida){

 db_inicio_transacao();
 $cllotecemit->cm23_i_quadracemit = $cm23_i_quadracemit;
 $cllotecemit->incluir(null);
 db_fim_transacao();
}

if(isset($alterar) && $lOperacaoValida){
 db_inicio_transacao();
 $cllotecemit->alterar($cm23_i_codigo);
 db_fim_transacao();
}

if(isset($excluir)){

 db_inicio_transacao();

 $sSql = "select *
            from sepulturas
           where cm05_i_lotecemit = {$cm23_i_codigo}";
 $sSqlValida = $cllotecemit->sql_record($sSql);
 if ($cllotecemit->numrows > 0) {
   $cllotecemit->erro_status="0";
   $cllotecemit->erro_msg = "Lote possui sepulturas cadastradas!\\nExclusão Não Permitida!";
 } else {
   $cllotecemit->excluir($cm23_i_codigo);
 }

 db_fim_transacao();

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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <!--
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
 -->
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:90%"><legend><b>Cadastro de Lotes: </legend>
    <? include(modification("forms/db_frmlotecemit.php")); ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
if(isset($incluir)){
 if($cllotecemit->erro_status=="0"){
  $cllotecemit->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllotecemit->erro_campo!=""){
   echo "<script> document.form1.".$cllotecemit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllotecemit->erro_campo.".focus();</script>";
  };
 }else{
  $cllotecemit->erro(true,false);
 };
}
if(isset($alterar)){
 if($cllotecemit->erro_status=="0"){
  $cllotecemit->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllotecemit->erro_campo!=""){
   echo "<script> document.form1.".$cllotecemit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$cllotecemit->erro_campo.".focus();</script>";
  };
 }else{
  $cllotecemit->erro(true,false);
 };

}
if(isset($excluir)){
 if($cllotecemit->erro_status=="0"){
  $cllotecemit->erro(true,false);
 }else{
  $cllotecemit->erro(true,false);
 };
}
if(isset($cancelar) or isset($alterar) or isset($excluir) or isset($incluir) ){
 echo "<script>location.href='".$cllotecemit->pagina_retorno."?cm23_i_quadracemit=$cm23_i_quadracemit'</script>";
}
?>