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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_ouvidoriacadlocal_classe.php");
require_once("classes/db_ouvidoriacadlocalgeral_classe.php");
require_once("classes/db_ouvidoriacadlocalender_classe.php");
require_once("classes/db_ouvidoriacadlocaldepart_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clouvidoriacadlocal = new cl_ouvidoriacadlocal;
$clouvidoriacadlocal->rotulo->label("ov25_sequencial");
$clouvidoriacadlocal->rotulo->label("ov25_descricao");


$oDaoOuvGeral  = new cl_ouvidoriacadlocalgeral();
$oDaoOuvEnder  = new cl_ouvidoriacadlocalender();
$oDaoOuvDepart = new cl_ouvidoriacadlocaldepart();

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
            <td width="4%" align="right" nowrap title="<?=$Tov25_sequencial?>">
              <?=$Lov25_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ov25_sequencial",10,$Iov25_sequencial,true,"text",4,"","chave_ov25_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tov25_descricao?>">
              <?=$Lov25_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ov25_descricao",100,$Iov25_descricao,true,"text",4,"","chave_ov25_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ouvidoriacadlocal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_ouvidoriacadlocal.php")==true){
             include("funcoes/db_func_ouvidoriacadlocal.php");
           }else{
           $campos = "ouvidoriacadlocal.*";
           }
        }
        if(isset($chave_ov25_sequencial) && (trim($chave_ov25_sequencial)!="") ){
	         $sql = $clouvidoriacadlocal->sql_query($chave_ov25_sequencial,$campos,"ov25_sequencial");
        }else if(isset($chave_ov25_descricao) && (trim($chave_ov25_descricao)!="") ){
	         $sql = $clouvidoriacadlocal->sql_query("",$campos,"ov25_descricao"," ov25_descricao like '$chave_ov25_descricao%' ");
        }else{
           $sql = $clouvidoriacadlocal->sql_query("",$campos,"ov25_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ov25_descricao)){
          $repassa = array("chave_ov25_sequencial"=>$chave_ov25_sequencial,"chave_ov25_descricao"=>$chave_ov25_descricao);
        }
        
        if (isset($sTipoLocal) && $sTipoLocal != "t" || trim($sTipoLocal) != "") {
          
          switch ($sTipoLocal) {
            
            /**
             * Busca na tabela ouvidoriacadlocalgeral
             */
            case "g":
              
              if (isset($chave_ov25_sequencial) && (trim($chave_ov25_sequencial) != "")) {
                $sSqlLocalGeral = $oDaoOuvGeral->sql_query($chave_ov25_sequencial, "ouvidoriacadlocal.*", "ov25_sequencial");
              } else if (isset($chave_ov25_descricao) && (trim($chave_ov25_descricao) != "")) {
                
                $sWhereOuvidoriaGeral = " ov25_descricao like '%{$chave_ov25_descricao}%' ";
                $sSqlLocalGeral = $oDaoOuvGeral->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", $sWhereOuvidoriaGeral);
              } else {
                $sSqlLocalGeral = $oDaoOuvGeral->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", "");
              }
              
              $sql = $sSqlLocalGeral;
              break;
            
            /**
             * Busca na tabela ouvidoriacadlocalender
             */
            case "e":
              
              if (isset($chave_ov25_sequencial) && (trim($chave_ov25_sequencial) != "")) {
                $sSqlLocalEnder = $oDaoOuvEnder->sql_query($chave_ov25_sequencial, "ouvidoriacadlocal.*", "ov25_sequencial");
              } else if (isset($chave_ov25_descricao) && (trim($chave_ov25_descricao) != "")) {
                
                $sWhereOuvidoriaEnder = " ov25_descricao like '%{$chave_ov25_descricao}%' ";
                $sSqlLocalEnder = $oDaoOuvEnder->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", $sWhereOuvidoriaEnder);
              } else {
                $sSqlLocalEnder = $oDaoOuvEnder->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", "");
              }
              
              $sql = $sSqlLocalEnder;
              break;
            
            /**
             * Busca na tabela ouvidoriacadlocaldepart
             */
            case "d":
              
              if (isset($chave_ov25_sequencial) && (trim($chave_ov25_sequencial) != "")) {
                $sSqlLocalDepart = $oDaoOuvDepart->sql_query($chave_ov25_sequencial, "ouvidoriacadlocal.*", "ov25_sequencial");
              } else if (isset($chave_ov25_descricao) && (trim($chave_ov25_descricao) != "")) {
                
                $sWhereOuvidoriaDepart = " ov25_descricao like '%{$chave_ov25_descricao}%' ";
                $sSqlLocalDepart = $oDaoOuvDepart->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", $sWhereOuvidoriaDepart);
              } else {
                $sSqlLocalDepart = $oDaoOuvDepart->sql_query(null, "ouvidoriacadlocal.*", "ov25_sequencial", "");
              }
              
              $sql = $sSqlLocalDepart;
              break;
          }
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clouvidoriacadlocal->sql_record($clouvidoriacadlocal->sql_query_tipo($pesquisa_chave));
          if($clouvidoriacadlocal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ov25_descricao',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
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
js_tabulacaoforms("form2","chave_ov25_descricao",true,1,"chave_ov25_descricao",true);
</script>