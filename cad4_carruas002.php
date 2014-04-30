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
include("classes/db_cargrup_classe.php");
include("classes/db_ruas_classe.php");
include("dbforms/db_funcoes.php");
$clcargrup = new cl_cargrup;
$clruas = new cl_ruas;
db_postmemory($HTTP_POST_VARS);

$result33=$clruas->sql_record($clruas->sql_query_file($j14_codigo,"j14_nome"));
db_fieldsmemory($result33,0);

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("j14_codigo");
$clrotulo->label("j32_grupo");
$clrotulo->label("j32_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
   <td align="center"> 
     <table border="1">
       <tr>   
         <td title="<?=$Tj14_nome?>" >
           <?=$Lj14_nome?>
         </td>    
         <td title="<?=$Tj14_nome?>" colspan="4">
           <?
            db_input('j14_codigo',5,$Ij14_codigo,true,'text',3);
            db_input('j14_nome',50,$Ij14_nome,true,'text',3);
           ?>
          </td>
       </tr>
       <tr>
          <td title="<?=$Tj32_grupo?>">
           <?=$Lj32_grupo?>
          </td>  
          <td title="<?=$Tj32_grupo?>">
           <?
             $result05=$clcargrup->sql_record($clcargrup->sql_query_file("","j32_descr","","j32_grupo=$j32_grupo"));
             db_fieldsmemory($result05,0);
             db_input('j32_grupo',5,$Ij32_grupo,true,'text',3);
             db_input('j32_descr',50,$Ij32_descr,true,'text',3);
           ?> 
          </td>  
       </tr>  
     </table>
   </td>
 </tr>
 <tr>
   <td>
      <fieldset><Legend align="center"><b>Características da faces </b></legend>
         <center>
	     <input name="selecionar" value="Selecionar" type="button" onclick="caracter.js_seleciona();">
             <input name="atualizar" value="Atualizar" type="button" onclick="caracter.js_atualizar();" style="visibility:hidden">
             <input name="fechar" value="Fechar" type="button" onclick="parent.js_fechar();" >
         </center>
       <iframe id="caracter"  frameborder="0" name="caracter" src="cad4_carruas003.php?j14_codigo=<?=$j14_codigo?>&j32_grupo=<?=$j32_grupo?>" height="250" width="680" scrolling="auto" >
       </iframe>
      </fieldset>
   </td>
 </tr>
</table> 
</form>
</body>
</html>