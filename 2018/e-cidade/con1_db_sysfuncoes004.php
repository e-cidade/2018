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

include("classes/db_db_sysfuncoes_classe.php");
include("classes/db_db_sysfuncoesparam_classe.php");
include("classes/db_db_sysfuncoescliente_classe.php");

$cldb_sysfuncoes = new cl_db_sysfuncoes;
$cldb_sysfuncoescliente = new cl_db_sysfuncoescliente;
/* $cldb_sysfuncoesparam = new cl_db_sysfuncoesparam;  */
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
//  $cldb_sysfuncoes->corpofuncao = 
  $cldb_sysfuncoes->incluir($codfuncao);
  $erro_msg = $cldb_sysfuncoes->erro_msg; 
  if($cldb_sysfuncoes->erro_status==0){
    $sqlerro = true;
    $erro_msg = "db_sysfuncoes : ".$cldb_sysfuncoes->erro_msg; 
  } 
  if(isset($db41_cliente) && $db41_cliente != "" && $sqlerro == false){
    $cldb_sysfuncoescliente->db41_funcao  = $cldb_sysfuncoes->codfuncao;
    $cldb_sysfuncoescliente->db41_cliente = $db41_cliente;
    $cldb_sysfuncoescliente->incluir(null);
    if($cldb_sysfuncoescliente->erro_status==0){
      $sqlerro = true;
      $erro_msg = "db_sysfuncoescliente : ".$cldb_sysfuncoescliente->erro_msg; 
    } 
  }

  db_fim_transacao($sqlerro);
  $codfuncao= $cldb_sysfuncoes->codfuncao;
  $db_opcao = 1;
  $db_botao = true;
}

if(isset($carregar) && $arquivo != ""){
   $erro_msg = "";
   $linha    = "";    
   $nomearquivo =  $_FILES["arquivo"]["name"];
   // Nome do arquivo temporário gerado no /tmp
   $nometmp = $_FILES["arquivo"]["tmp_name"];
   // Faz um upload do arquivo para o local especificado
   move_uploaded_file($nometmp,$nomearquivo) or $erro_msg = "ERRO: Contate o suporte.";
   // Abre o arquivo
   $ponteiro = fopen("$nomearquivo","r") or $erro_msg = "ERRO: Arquivo não abre.";
   while(!feof($ponteiro)){
      $corpofuncao .= fgets($ponteiro,4096);
   }
   if ($erro_msg != "") {
     db_msgbox($erro_msg);
   }
   fclose($ponteiro);
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
	include("forms/db_frmdb_sysfuncoes.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_sysfuncoes->erro_campo!=""){
      echo "<script> document.form1.".$cldb_sysfuncoes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_sysfuncoes->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("con1_db_sysfuncoes005.php?liberaaba=true&chavepesquisa=$codfuncao");
  }
}
?>