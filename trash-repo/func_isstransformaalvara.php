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
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issmovalvara_classe.php");

$oPost = db_utils::postmemory($_POST);
$oGet  = db_utils::postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clIssMovAlvara = new cl_issmovalvara();
$clIssMovAlvara->rotulo->label("q120_sequencial");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
       <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="Inscrição">
              <b>Inscrição : </b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("q123_inscr",10,"Inscrição",true,"text",1,"","chave_q123_inscr");
              ?>
            </td>
          </tr>
          
          <tr> 
            <td width="4%" align="right" nowrap title="Nome">
              <b>Nome : </b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("z01_nome",45,"Nome",true,"text",1,"","chave_z01_nome");
              ?>
            </td>
          </tr>          
          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_isstrasnf.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($oGet->pesquisa_chave)) {

        if(isset($chave_q123_inscr) && (trim($chave_q123_inscr) != "") ) {
        	
           $sSql = $clIssMovAlvara->sql_queryAlvarasTransformacao("q120_sequencial, q120_issalvara, z01_nome, q123_inscr, issgrupotipoalvara.q97_sequencial, issgrupotipoalvara.q97_descricao, isstipoalvara.q98_sequencial, isstipoalvara.q98_descricao", "q123_inscr = {$chave_q123_inscr} AND q120_isstipomovalvara <> 2");
           
        }else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")){
        
        	 $sSql = $clIssMovAlvara->sql_queryAlvarasTransformacao("q120_sequencial, q120_issalvara, z01_nome, q123_inscr, issgrupotipoalvara.q97_sequencial, issgrupotipoalvara.q97_descricao, isstipoalvara.q98_sequencial, isstipoalvara.q98_descricao", " z01_nome ilike '$chave_z01_nome%' AND q120_isstipomovalvara <> 2");
        } else {
        	
           $sSql = $clIssMovAlvara->sql_queryAlvarasTransformacao("q120_sequencial, q120_issalvara, z01_nome, q123_inscr, issgrupotipoalvara.q97_sequencial, issgrupotipoalvara.q97_descricao, isstipoalvara.q98_sequencial, isstipoalvara.q98_descricao", "q120_isstipomovalvara <> 2");
        }
        
        $repassa = array();
        
        if (isset($chave_q120_sequencial)) {
          $repassa = array("chave_q120_sequencial"=>$chave_q120_sequencial);
        }
        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {
      	
        if (isset($oGet->pesquisa_chave) != null && isset($oGet->pesquisa_chave)) {
          $sSql        = $clIssMovAlvara->sql_queryAlvarasTransformacao("q120_sequencial, q120_issalvara, z01_nome, q123_inscr, issgrupotipoalvara.q97_sequencial, issgrupotipoalvara.q97_descricao, isstipoalvara.q98_sequencial, isstipoalvara.q98_descricao","q123_inscr = {$pesquisa_chave} AND q120_isstipomovalvara <> 2");
          $rsMovAlvara = $clIssMovAlvara->sql_record($sSql);
          if ($clIssMovAlvara->numrows != 0) {
            
            db_fieldsmemory($rsMovAlvara, 0);
            
            echo "<script>".$funcao_js."($q123_inscr,$q120_issalvara, $q97_sequencial,'$q97_descricao',$q98_sequencial,'$q98_descricao',$q120_sequencial,'$z01_nome',false);</script>";
          } else {
           echo "<script>".$funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
         echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_q120_sequencial",true,1,"chave_q120_sequencial",true);
</script>