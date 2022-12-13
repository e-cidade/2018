<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_contabancaria_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clcontabancaria = new cl_contabancaria;
$clcontabancaria->rotulo->label("db83_sequencial");
$clcontabancaria->rotulo->label("db83_descricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb83_sequencial?>">
              <?=$Ldb83_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db83_sequencial",10,$Idb83_sequencial,true,"text",4,"","chave_db83_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb83_descricao?>">
              <?=$Ldb83_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db83_descricao",100,$Idb83_descricao,true,"text",4,"","chave_db83_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_contabancaria.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $sWhere = '1=1';
      
      if ( isset($bancoagencia) && trim($bancoagencia) != '' )  {
      	$sWhere .= " and db83_bancoagencia = {$bancoagencia} ";
      }

      $sWhere .= ' and c61_instit = ' . db_getsession('DB_instit');
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_contabancaria.php")==true){
             include("funcoes/db_func_contabancaria.php");
           }else{
           $campos = "contabancaria.*";
           }
        }
        if(isset($chave_db83_sequencial) && (trim($chave_db83_sequencial)!="") ){
	         $sql = $clcontabancaria->sql_query_concilia(null,$campos,"db83_sequencial",$sWhere." and db83_sequencial = ".$chave_db83_sequencial);
        }else if(isset($chave_db83_descricao) && (trim($chave_db83_descricao)!="") ){
	         $sql = $clcontabancaria->sql_query_concilia("",$campos,"db83_descricao",$sWhere." and db83_descricao like '$chave_db83_descricao%' ");
        }else{
           $sql = $clcontabancaria->sql_query_concilia("",$campos,"db83_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_db83_descricao)){
          $repassa = array("chave_db83_sequencial"=>$chave_db83_sequencial,"chave_db83_descricao"=>$chave_db83_descricao);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	if(isset($tp) && $tp == 1){
        		$sWhere .= " and db83_sequencial = '".$pesquisa_chave."'";
        	} else {
        	  $sWhere .= " and db83_conta = '".$pesquisa_chave."'";
        	}
          $result = $clcontabancaria->sql_record($clcontabancaria->sql_query_concilia(null,"*",null,$sWhere));
          if($clcontabancaria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."(false,'$db83_conta','$db83_dvconta','$db83_identificador','$db83_codigooperacao','$db83_tipoconta','$db83_bancoagencia','$db83_sequencial');</script>";
          }else{
	         echo "<script>".$funcao_js."(true,'','','','','','','');</script>";
          }
        }else{
	       echo "<script>".$funcao_js."(false,'','','','','','','');</script>";
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
js_tabulacaoforms("form2","chave_db83_descricao",true,1,"chave_db83_descricao",true);
</script>
