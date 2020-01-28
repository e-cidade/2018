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
include("classes/db_rhestagioagendadata_classe.php");
include("classes/db_rhestagioagenda_classe.php");
include("classes/db_rhestagioresultado_classe.php");
include("classes/estagioAvaliacoes.classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrhestagioagendadata = new cl_rhestagioagendadata;
$clrhestagioagenda     = new cl_rhestagioagenda;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clrhestagioagendadata->h64_sequencial = $h64_sequencial;
$clrhestagioagendadata->h64_estagioagenda = $h64_estagioagenda;
$clrhestagioagendadata->h64_data = $h64_data;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clrhestagioagendadata->incluir($h64_sequencial);
    $erro_msg = $clrhestagioagendadata->erro_msg;
    if($clrhestagioagendadata->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clrhestagioagendadata->alterar($h64_sequencial);
    $erro_msg = $clrhestagioagendadata->erro_msg;
    if($clrhestagioagendadata->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if ($sqlerro==false){

     $clrhestagioresultado = new cl_rhestagioresultado();
     db_inicio_transacao();
     $rsResultado = $clrhestagioresultado->sql_record($clrhestagioresultado->sql_query(null,"h65_sequencial"
                                                      ,null,"h65_rhestagioagenda = {$h64_estagioagenda}"));
     if ($clrhestagioresultado->numrows > 0){
         
         $sqlerro  = true;
         $erro_msg = "Já existe um resultado final para esse estágio.\\nNão podera ser excluído.";

     }
     
     if (!$sqlerro){

        $estagioAvaliacao = new estagioAvaliacao($h64_sequencial,false);
        $estagioAvaliacao->excluirAvaliacao();
        if ($estagioAvaliacao->lSqlErro){
            
           $sqlerro  = true;
           $erro_msg = $estagioAvaliacao->sErroMsg;

        }
     }
     if (!$sqlerro){
   
        $clrhestagioagendadata->excluir($h64_sequencial);
        $erro_msg = $clrhestagioagendadata->erro_msg;
        if ($clrhestagioagendadata->erro_status==0){
          $sqlerro=true;
       }
     }
     db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clrhestagioagendadata->sql_record($clrhestagioagendadata->sql_query($h64_sequencial));
   if($result!=false && $clrhestagioagendadata->numrows>0){
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhestagioagendadata.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clrhestagioagendadata->erro_campo!=""){
        echo "<script> document.form1.".$clrhestagioagendadata->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhestagioagendadata->erro_campo.".focus();</script>";
    }
}
?>