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
include("classes/db_extrato_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clextrato = new cl_extrato;
$clextrato->rotulo->label("k85_sequencial");
$clextrato->rotulo->label("k85_nomearq");
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
            <td width="4%" align="right" nowrap title="<?=$Tk85_sequencial?>">
              <?=$Lk85_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k85_sequencial",10,$Ik85_sequencial,true,"text",4,"","chave_k85_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk85_nomearq?>">
              <?=$Lk85_nomearq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k85_nomearq",50,$Ik85_nomearq,true,"text",4,"","chave_k85_nomearq");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_extrato.hide();">
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
           if(file_exists("funcoes/db_func_extrato.php")==true){
             include("funcoes/db_func_extrato.php");
           }else{
           $campos = "extrato.*";
           }
        }
        if(isset($chave_k85_sequencial) && (trim($chave_k85_sequencial)!="") ){
	         $sql = $clextrato->sql_query($chave_k85_sequencial,$campos,"k85_sequencial");
        }else if(isset($chave_k85_nomearq) && (trim($chave_k85_nomearq)!="") ){
	         $sql = $clextrato->sql_query("",$campos,"k85_nomearq"," k85_nomearq like '$chave_k85_nomearq%' and k85_tipoinclusao = 2 ");
        }else{
           $sql = $clextrato->sql_query("",$campos,"k85_sequencial"," k85_tipoinclusao = 2 ");
        }
        $repassa = array();
        if(isset($chave_k85_nomearq)){
          $repassa = array("chave_k85_sequencial"=>$chave_k85_sequencial,"chave_k85_nomearq"=>$chave_k85_nomearq);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clextrato->sql_record($clextrato->sql_query($pesquisa_chave));
          if($clextrato->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k85_nomearq',false);</script>";
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
js_tabulacaoforms("form2","chave_k85_nomearq",true,1,"chave_k85_nomearq",true);
</script>