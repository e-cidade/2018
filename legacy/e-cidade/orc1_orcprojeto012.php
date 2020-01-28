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
include("classes/db_orcprojeto_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clorcprojeto = new cl_orcprojeto;
$clorcprojeto->rotulo->label();

if (isset($gravar)){
   db_inicio_transacao();
   $erro = false;
   $mensagem = "";
   // update no o39_texto do projeto
   $clorcprojeto->o39_texto = $o39_texto;
   $clorcprojeto->o39_compllei = $o39_compllei;
   $clorcprojeto->o39_codproj = $o39_codproj;
   $clorcprojeto->alterar($o39_codproj);
   if ($clorcprojeto->erro_status == "0" ){    
     $mensagem =$clorcprojeto->erro_msg;
     $erro = true;
   }  
   db_fim_transacao($erro);  

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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    
    <form name="form1" method="post" action="">
    <input type=hidden name=o39_codproj value="<?=$o39_codproj ?>" >

    <table border="0" style="border:1px solid #999999">
    <tr>
       <td colspan=2> &nbsp; </td>
    </tr>

    <tr>
       <td nowrap title="<?=@$To39_texto?>"><?=@$Lo39_texto?></td>
       <td> 
        <? 
	   if (isset($o39_codproj) && $o39_codproj!=""){
              $ro = $clorcprojeto->sql_record($clorcprojeto->sql_query_file($o39_codproj,"o39_texto"));	   
   	      //db_criatabela($ro);
	      if (@pg_numrows($ro)>0){
                 @db_fieldsmemory($ro,0);
   	      }  
  	      if ($o39_texto ==""){
                $o39_texto = "Art 2. -  Para cobertura do Crédito aberto de acordo com o Art 1.,"; 
                $o39_texto.= " será usado como recurso as seguintes reduções orçamentárias:   ";
	      }  
	      db_textarea('o39_texto',7,80,$Io39_texto,true,'text',1); 
	   }   

       ?>	  
       </td>
    </tr>
    <tr>
       <td nowrap title="<?=@$To39_compllei?>"><?=@$Lo39_compllei?></td>
       <td> 
        <? 
	   if (isset($o39_codproj) && $o39_codproj!=""){
              $ro = $clorcprojeto->sql_record($clorcprojeto->sql_query_file($o39_codproj,"o39_compllei"));	   
   	      //db_criatabela($ro);
	      if (@pg_numrows($ro)>0){
                 @db_fieldsmemory($ro,0);
   	      }  
	      db_textarea('o39_compllei',7,80,$Io39_compllei,true,'text',1); 
	   }   

       ?>	  
       </td>
    </tr>

    <tr>
       <td colspan=2 align=center><input type="submit" name=gravar value="Gravar"></td>
    </tr>
    </table>
    </form>
 </td>
</tr>

</table>
</body>
</html>