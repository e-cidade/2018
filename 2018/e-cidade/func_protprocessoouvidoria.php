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
include("classes/db_protprocesso_classe.php");

db_postmemory($_POST);
db_postmemory($_GET);

$clprotprocesso = new cl_protprocesso;
$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");
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
            <td width="4%" align="right" nowrap title="<?=$Tp58_codproc?>">
              <?=$Lp58_codproc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p58_codproc",10,$Ip58_codproc,true,"text",4,"","chave_p58_codproc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp58_requer?>">
              <?=$Lp58_requer?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p58_requer",50,$Ip58_requer,true,"text",4,"","chave_p58_requer");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_proc.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " tipoproc.p51_instit = ".db_getsession("DB_instit");
      
      if(isset($grupo) && trim($grupo) != '' ){
      	$where .= " and tipoproc.p51_tipoprocgrupo = $grupo";
      }       
      if ( isset($tipo) && trim($tipo) != '' ) {
        $where .= " and p58_codigo = {$tipo} ";         
      }
      
      if ( isset($arq) && trim($arq) != '' ) {
      	if ( $arq == 'true') {
	        $where .= " and p68_codarquiv is not null ";         
      	} else {
      		$where .= " and p68_codarquiv is null     ";
      	}
      }      
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "p58_codproc,z01_numcgm as DB_p58_numcgm,z01_nome,p58_dtproc,p51_descr,p58_obs,p58_requer as DB_p58_requer";
        }
        if ( isset($chave_p58_numcgm) || isset($chave_p58_codproc) || isset($chave_p58_requer) ) {
	        if(isset($chave_p58_numcgm) && (trim($chave_p58_numcgm)!="") ){
		        $sql = $clprotprocesso->sql_query_ouvidoria(null,$campos,"p58_codproc desc","p58_numcgm = $chave_p58_numcgm  and $where");
	        } else if(isset($chave_p58_codproc) && (trim($chave_p58_codproc)!="") ){
		        $sql = $clprotprocesso->sql_query_ouvidoria(null,$campos,"p58_codproc desc","p58_codproc = ".$chave_p58_codproc." and ".$where);
	        } else if(isset($chave_p58_requer) && (trim($chave_p58_requer)!="") ){
		        $sql = $clprotprocesso->sql_query_ouvidoria("",$campos,"p58_codproc desc"," p58_requer like '$chave_p58_requer%'  and $where");
	        } else{
	          $sql = $clprotprocesso->sql_query_ouvidoria("",$campos,"p58_dtproc desc",$where);
	        }
        } else {
        	$sql = '';
        }
        
      	$repassa = array();
        if(isset($chave_p58_codproc)){
        	$repassa = array("chave_p58_codproc"=>$chave_p58_codproc);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){        	
          //die($clprotprocesso->sql_query("","*","","p58_codproc = $pesquisa_chave and $where"));
          $result = $clprotprocesso->sql_record($clprotprocesso->sql_query_ouvidoria("","*","","p58_codproc = $pesquisa_chave and $where"));
          if($clprotprocesso->numrows!=0){
          
            db_fieldsmemory($result,0);
	    			if(isset($retobs)){
           
	    				echo "<script>".$funcao_js."('$p58_numcgm','$p58_obs',false);</script>";
               
	    			}else{
	    		
               	echo "<script>".$funcao_js."('$p58_codproc','$z01_nome',false);</script>";
	    			}   
          }else{
          
	         echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
        	
	       echo "<script>".$funcao_js."('','',false);</script>";
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
//document.form2.chave_p58_codproc.focus();
//document.form2.chave_p58_codproc.select();
  </script>
  <?
}
?>