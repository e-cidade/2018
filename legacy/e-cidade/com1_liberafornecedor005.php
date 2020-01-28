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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_liberafornecedor_classe.php");
//include("classes/db_liberafornecedorsol_classe.php");
//include("classes/db_liberafornecedorpcproc_classe.php");
$clliberafornecedor = new cl_liberafornecedor;
  /*
$clliberafornecedorsol = new cl_liberafornecedorsol;
$clliberafornecedorpcproc = new cl_liberafornecedorpcproc;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clliberafornecedor->pc82_id_usuario  = db_getsession("DB_id_usuario");  
  $clliberafornecedor->pc82_ativo       = "true";
  $clliberafornecedor->pc82_data        = date("Y-m-d",db_getsession("DB_datausu"));
  //Verifica quais checkbox estão setados
  isset($pc82_liberasol)  ? $clliberafornecedor->pc82_liberasol  = "true" : $clliberafornecedor->pc82_liberasol  = "false"; 
  isset($pc82_liberaaut)  ? $clliberafornecedor->pc82_liberaaut  = "true" : $clliberafornecedor->pc82_liberaaut  = "false"; 
  isset($pc82_liberaproc) ? $clliberafornecedor->pc82_liberaproc = "true" : $clliberafornecedor->pc82_liberaproc = "false";
  
  if($pc82_dataini != "" && $pc82_datafim != ""){
	  $dtIni = implode("-",array_reverse(explode("/",$pc82_dataini)));
	  $dtFim = implode("-",array_reverse(explode("/",$pc82_datafim)));
	  
	  $sWhere = "pc82_dataini = '$dtIni' and pc82_datafim = '$dtFim' and pc82_numcgm = $pc82_numcgm";
	  //die($clliberafornecedor->sql_query_file(null,"*",null,$sWhere)); 
	  $rsFornecedor =  $clliberafornecedor->sql_record($clliberafornecedor->sql_query_file(null,"*",null,$sWhere));
	
	  if(pg_num_rows($rsFornecedor) > 0){
	    $sqlerro = true;
	    
	    $clliberafornecedor->erro_msg   = "\\n\\nusuário: \\n\\n Já existe uma liberação cadastrada para o fornecedor no período informado !\\n\\n Alteração não efetuada!!!";
	    $clliberafornecedor->erro_campo = "";
	     
	  }
  }
  if(!$sqlerro){  
	  $clliberafornecedor->alterar($pc82_sequencial);
	  if($clliberafornecedor->erro_status==0){
	    $sqlerro=true;
	  }
  } 
  $erro_msg = $clliberafornecedor->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   $result = $clliberafornecedor->sql_record($clliberafornecedor->sql_query($chavepesquisa)); 
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmliberafornecedor.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clliberafornecedor->erro_campo!=""){
      echo "<script> document.form1.".$clliberafornecedor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clliberafornecedor->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
//echo 
$liberaabaSol = true;
if(isset($pc82_liberasol) && trim($pc82_liberasol) != ''){
  $liberaabaSol   = $pc82_liberasol   == 't' ? "false" : "true";	
}
$liberaabaProc = true;
if(isset($pc82_liberaproc) && trim($pc82_liberaproc) != ''){
  $liberaabaProc  = $pc82_liberaproc  == 't' ? "false" : "true";	 
}
 

if(isset($chavepesquisa) || (isset($alterar)&&$sqlerro==false)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.liberafornecedorsol.disabled=$liberaabaSol;
         top.corpo.iframe_liberafornecedorsol.location.href='com1_liberafornecedorsol001.php?pc82_sequencial=".@$pc82_sequencial."';
         parent.document.formaba.liberafornecedorpcproc.disabled=$liberaabaProc;
         top.corpo.iframe_liberafornecedorpcproc.location.href='com1_liberafornecedorpcproc001.php?pc82_sequencial=".@$pc82_sequencial."';
     ";
         if(isset($liberaaba)){
           //echo "  parent.mo_camada('liberafornecedorsol');";
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