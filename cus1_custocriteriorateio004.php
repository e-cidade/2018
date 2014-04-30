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
include("classes/db_custocriteriorateio_classe.php");
include("classes/db_custoplanoanaliticacriteriorateio_classe.php");
include("classes/db_custocriteriopcmater_classe.php");
include("classes/db_custocriteriorateiobens_classe.php");
$clcustocriteriorateio = new cl_custocriteriorateio;
  /*
$clcustoplanoanaliticacriteriorateio = new cl_custoplanoanaliticacriteriorateio;
$clcustocriteriopcmater = new cl_custocriteriopcmater;
$clcustocriteriorateiobens = new cl_custocriteriorateiobens;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  
  $sqlerro=false;
  db_inicio_transacao();
  $clcustocriteriorateio->cc08_instit     = db_getsession("DB_instit");
  $clcustocriteriorateio->cc08_coddepto   = db_getsession("DB_coddepto");
  $clcustocriteriorateio->cc08_automatico = "false";
  $clcustocriteriorateio->incluir($cc08_sequencial);
  if($clcustocriteriorateio->erro_status == 0){
    $sqlerro=true;
  }
   
  $erro_msg = $clcustocriteriorateio->erro_msg; 
  db_fim_transacao($sqlerro);
  $cc08_sequencial= $clcustocriteriorateio->cc08_sequencial;
  $db_opcao = 1;
  $db_botao = true;
   
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
 <center>
	<?
	include("forms/db_frmcustocriteriorateio.php");
	?>
 </center>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clcustocriteriorateio->erro_campo!=""){
      echo "<script> document.form1.".$clcustocriteriorateio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcustocriteriorateio->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("cus1_custocriteriorateio005.php?liberaaba=true&chavepesquisa=$cc08_sequencial");
  }
}
?>