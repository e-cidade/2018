<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matrequi_classe.php");

$cldb_almox      = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
$cl_matrequi     = new cl_matrequi;
db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;
$erro_msg = null;
$sqlerro  = null;
$sqlerro=false;
if (isset($excluir)) {

  // retorna true se existem movimentação no deposito, retorna false se não houver
  $sSql  = " select exists(                                    ";
  $sSql .= "  select 1                                         ";
  $sSql .= "  from (select m91_depto as coddepto               ";
  $sSql .= "          from db_almox                            ";
  $sSql .= "          where m91_codigo = $m91_codigo           ";
  $sSql .= "  union                                            ";  
  $sSql .= "        select m92_depto as coddepto               ";
  $sSql .= "          from db_almoxdepto                       "; 
  $sSql .= "          where m92_codalmox = $m91_codigo         ";
  $sSql .= "        ) as depto                                 ";
  $sSql .= "  inner join matestoque on m70_coddepto = coddepto ";
  $sSql .= "             ) as movimentacao                     ";

  // die($sSql);
  
  $rsConsulta    = db_query($sSql);
  $oMovimentacao = db_utils::fieldsMemory($rsConsulta, 0);

  if ($oMovimentacao->movimentacao == "t") {
    $sqlerro = true;
    $erro_msg = "Depósito com movimentação! Exclusão abortada!";
  }	else {
  	
  	db_inicio_transacao();
    $cldb_almoxdepto->m92_codalmox=$m91_codigo;
    $cldb_almoxdepto->excluir($m91_codigo);

    if ($cldb_almoxdepto->erro_status == 0) {
      $sqlerro = true;
    } 
    
    $erro_msg = $cldb_almoxdepto->erro_msg; 
    $cldb_almox->excluir($m91_codigo);
    
    if ($cldb_almox->erro_status == 0) {
      $sqlerro = true;
    } 
    
    $erro_msg = $cldb_almox->erro_msg; 
    db_fim_transacao($sqlerro);
    $db_opcao = 3;
    $db_botao = true;
  }
  
} else if (isset($chavepesquisa)) {
   $db_opcao = 3;
   $db_botao = true;
   $result = $cldb_almox->sql_record( $cldb_almox->sql_query($chavepesquisa) ); 
   db_fieldsmemory($result,0);
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
<center>
<table border="0" style="padding-top:15px" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" bgcolor="#CCCCCC"> 
	  <?
	    include("forms/db_frmdb_almox.php");
	  ?>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
    if($cldb_almox->erro_campo!=""){
      echo "<script> document.form1.".$cldb_almox->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_almox->erro_campo.".focus();</script>";
    }
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='mat1_db_almox003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.db_almoxdepto.disabled=false;
         top.corpo.iframe_db_almoxdepto.location.href='mat1_matdb_almoxdepto001.php?db_opcao=33&codalmox=".@$m91_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_almoxdepto');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>