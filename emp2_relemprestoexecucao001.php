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
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$clcriaabas = new cl_criaabas;
$clcriaabas->scrolling="yes";

$abas    = array();
$titulos = array();
$fontes  = array();
$sizecp  = array();

$anousu  = db_getsession("DB_anousu");
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
     <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?
       if ($anousu <= 2007){
         $clcriaabas->identifica = array("g1"=>"Principal","g2"=>"Credor","filtro"=>"Filtro");
         $clcriaabas->title      = array("g1"=>"Principal","g2"=>"Selecionar credores","filtro"=>"Filtro");
         $clcriaabas->src        = array("g1"=>"emp2_relemprestoexecucao011.php","g2"=>"emp2_relemprestocredor001.php","filtro"=>"func_selorcdotacao_aba.php?desdobramento=true");
         $clcriaabas->funcao_js  = array("g1"=>"","g2"=>"","filtro"=>"js_atualizar_instit();");
         $clcriaabas->sizecampo  = array("g1"=>"20","g2"=>"20","filtro"=>"20");
       } else {
         $clcriaabas->identifica = array("g1"=>"Principal","g2"=>"Credor","filtro"=>"Filtro");
         $clcriaabas->title      = array("g1"=>"Principal","g2"=>"Selecionar credores","filtro"=>"Filtro");
         $clcriaabas->src        = array("g1"=>"emp2_relemprestoexecucao011.php","g2"=>"emp2_relempcredor001.php","filtro"=>"func_selorcdotacao_aba.php?desdobramento=true");
         $clcriaabas->funcao_js  = array("g1"=>"","g2"=>"","filtro"=>"js_atualizar_instit();");
         $clcriaabas->sizecampo  = array("g1"=>"20","g2"=>"20","filtro"=>"20");
       }
       $clcriaabas->cria_abas();
     ?> 
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
  function js_atualizar_instit(){
    iframe_filtro.js_atualizar_instit();
  }

/*
  document.formaba.g1.size     = 20; 
  document.formaba.g2.size     = 20; 
  document.formaba.g3.size     = 20; 
  document.formaba.g4.size     = 20; 
  document.formaba.g5.size     = 20; 
  if (document.formaba.g6){
    document.formaba.g6.size   = 30;
  }
  document.formaba.filtro.size = 20; 
*/  
</script>
</html>