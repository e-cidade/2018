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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_acordocomissaomembro_classe.php");
include("classes/db_acordocomissao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clacordocomissaomembro = new cl_acordocomissaomembro;
$clacordocomissao = new cl_acordocomissao;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clacordocomissaomembro->ac07_sequencial = $ac07_sequencial;
$clacordocomissaomembro->ac07_acordocomissao = $ac07_acordocomissao;
$clacordocomissaomembro->ac07_numcgm = $ac07_numcgm;
$clacordocomissaomembro->ac07_tipomembro = $ac07_tipomembro;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clacordocomissaomembro->incluir($ac07_sequencial);
    $erro_msg = $clacordocomissaomembro->erro_msg;
    if($clacordocomissaomembro->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clacordocomissaomembro->alterar($ac07_sequencial);
    $erro_msg = $clacordocomissaomembro->erro_msg;
    if($clacordocomissaomembro->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clacordocomissaomembro->excluir($ac07_sequencial);
    $erro_msg = $clacordocomissaomembro->erro_msg;
    if($clacordocomissaomembro->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clacordocomissaomembro->sql_record($clacordocomissaomembro->sql_query($ac07_sequencial));
   if($result!=false && $clacordocomissaomembro->numrows>0){
     db_fieldsmemory($result,0);
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<?
db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC >
	<?
	include("forms/db_frmacordocomissaomembro.php");
	?>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clacordocomissaomembro->erro_campo!=""){
        echo "<script> document.form1.".$clacordocomissaomembro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clacordocomissaomembro->erro_campo.".focus();</script>";
    }
}
?>