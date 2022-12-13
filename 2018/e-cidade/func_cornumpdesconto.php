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
require_once("classes/db_cornumpdesconto_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcornumpdesconto = new cl_cornumpdesconto;
$clcornumpdesconto->rotulo->label("k12_id");
$clcornumpdesconto->rotulo->label("k12_data");
$clcornumpdesconto->rotulo->label("k12_autent");
$clcornumpdesconto->rotulo->label("k12_numpre");
$clcornumpdesconto->rotulo->label("k12_numpar");
$clcornumpdesconto->rotulo->label("k12_receit");
$clcornumpdesconto->rotulo->label("k12_id");
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
            <td width="4%" align="right" nowrap title="<?=$Tk12_id?>">
              <?=$Lk12_id?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_id",5,$Ik12_id,true,"text",4,"","chave_k12_id");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_data?>">
              <?=$Lk12_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_data",8,$Ik12_data,true,"text",4,"","chave_k12_data");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_autent?>">
              <?=$Lk12_autent?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_autent",5,$Ik12_autent,true,"text",4,"","chave_k12_autent");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_numpre?>">
              <?=$Lk12_numpre?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_numpre",8,$Ik12_numpre,true,"text",4,"","chave_k12_numpre");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_numpar?>">
              <?=$Lk12_numpar?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_numpar",3,$Ik12_numpar,true,"text",4,"","chave_k12_numpar");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_receit?>">
              <?=$Lk12_receit?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_receit",10,$Ik12_receit,true,"text",4,"","chave_k12_receit");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tk12_id?>">
              <?=$Lk12_id?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("k12_id",5,$Ik12_id,true,"text",4,"","chave_k12_id");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cornumpdesconto.hide();">
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
           if(file_exists("funcoes/db_func_cornumpdesconto.php")==true){
             include("funcoes/db_func_cornumpdesconto.php");
           }else{
           $campos = "cornumpdesconto.*";
           }
        }
        if(isset($chave_k12_id) && (trim($chave_k12_id)!="") ){
	         $sql = $clcornumpdesconto->sql_query($chave_k12_id,$chave_k12_data,$chave_k12_autent,$chave_k12_numpre,$chave_k12_numpar,$chave_k12_receit,$campos,"k12_id");
        }else if(isset($chave_k12_id) && (trim($chave_k12_id)!="") ){
	         $sql = $clcornumpdesconto->sql_query("","","","","","",$campos,"k12_id"," k12_id like '$chave_k12_id%' ");
        }else{
           $sql = $clcornumpdesconto->sql_query("","","","","","",$campos,"k12_id#k12_data#k12_autent#k12_numpre#k12_numpar#k12_receit","");
        }
        $repassa = array();
        if(isset($chave_k12_id)){
          $repassa = array("chave_k12_id"=>$chave_k12_id,"chave_k12_id"=>$chave_k12_id);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcornumpdesconto->sql_record($clcornumpdesconto->sql_query($pesquisa_chave));
          if($clcornumpdesconto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k12_id',false);</script>";
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
js_tabulacaoforms("form2","chave_k12_id",true,1,"chave_k12_id",true);
</script>