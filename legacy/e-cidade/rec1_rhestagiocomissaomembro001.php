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
include("classes/db_rhestagiocomissaomembro_classe.php");
include("classes/db_rhestagiocomissao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrhestagiocomissaomembro = new cl_rhestagiocomissaomembro;
$clrhestagiocomissao = new cl_rhestagiocomissao;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clrhestagiocomissaomembro->h60_sequencial = $h60_sequencial;
$clrhestagiocomissaomembro->h60_regist = $h60_regist;
$clrhestagiocomissaomembro->h60_rhestagiocomissao = $h60_rhestagiocomissao;
$clrhestagiocomissaomembro->h60_tipo = $h60_tipo;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    if ($h60_tipo == 1){
       $clrhestagiocomissaomembro->sql_record($clrhestagiocomissaomembro->sql_query(null,"*",null
                                            ," h60_rhestagiocomissao = $h60_rhestagiocomissao 
                                               and  h60_tipo = 1 "));
     if ($clrhestagiocomissaomembro->numrows > 0){

         $sqlerro  = true;
         $erro_msg = "Já existe um presidente de comissão cadastastrado";
     
       }
    }

    if (!$sqlerro){  
       $clrhestagiocomissaomembro->incluir($h60_sequencial);
       $erro_msg = $clrhestagiocomissaomembro->erro_msg;
       if($clrhestagiocomissaomembro->erro_status==0){
        $sqlerro=true;
       }
    }
    db_fim_transacao($sqlerro);
 }

  
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    if ($h60_tipo == 1){
       $clrhestagiocomissaomembro->sql_record($clrhestagiocomissaomembro->sql_query(null,"*",null
                                            ," h60_rhestagiocomissao = $h60_rhestagiocomissao 
                                               and  h60_tipo = 1 
                                               and  h60_regist <> $h60_regist"));
     if ($clrhestagiocomissaomembro->numrows > 0){

         $sqlerro  = true;
         $erro_msg = "Já existe um presidente de comissão cadastastrado";
     
       }
    }
    if (!$sqlerro){ 
      $clrhestagiocomissaomembro->alterar($h60_sequencial);
      $erro_msg = $clrhestagiocomissaomembro->erro_msg;
      if($clrhestagiocomissaomembro->erro_status==0){
        $sqlerro=true;
      }
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clrhestagiocomissaomembro->excluir($h60_sequencial);
    $erro_msg = $clrhestagiocomissaomembro->erro_msg;
    if($clrhestagiocomissaomembro->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clrhestagiocomissaomembro->sql_record($clrhestagiocomissaomembro->sql_query($h60_sequencial));
   if($result!=false && $clrhestagiocomissaomembro->numrows>0){
     db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?
	include("forms/db_frmrhestagiocomissaomembro.php");
	?>
    </center>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clrhestagiocomissaomembro->erro_campo!=""){
        echo "<script> document.form1.".$clrhestagiocomissaomembro->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhestagiocomissaomembro->erro_campo.".focus();</script>";
    }
}
?>