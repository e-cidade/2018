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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_aidof_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claidof = new cl_aidof;
$claidof->rotulo->label("y08_codigo");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Ty08_codigo?>">
              <?=$Ly08_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y08_codigo",6,$Iy08_codigo,true,"text",4,"","chave_y08_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",60,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>         
                   
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aidof.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php
      if (isset($canc)&&$canc==true){
      	$where = " y08_cancel = 't' ";
      }else{
      	$where = " y08_cancel = 'f' ";
      }

      /**
       * Não exibir notas do grupo eletronica
       */
      $where .= " and q139_sequencial != 2";

      if(!isset($pesquisa_chave)){
      	$campos = "aidof.y08_codigo,aidof.y08_nota,aidof.y08_inscr,cgm.z01_nome,aidof.y08_dtlanc,aidof.y08_notain,aidof.y08_notafi,aidof.y08_obs,aidof.y08_quantsol,aidof.y08_quantlib,aidof.y08_cancel";        
        if(isset($chave_y08_codigo) && (trim($chave_y08_codigo)!="") ){
	         $sql = $claidof->sql_query($chave_y08_codigo,$campos,"y08_codigo","y08_codigo = $chave_y08_codigo and $where ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $claidof->sql_query(null,$campos,"y08_codigo"," cgm.z01_numcgm like '$chave_z01_numcgm%' and $where ");        
        }else{
           $sql = $claidof->sql_query("",$campos,"y08_codigo","$where");
        }
        $repassa = array();
        if(isset($chave_y08_codigo)){
          $repassa = array("chave_y08_codigo"=>$chave_y08_codigo,"chave_y08_codigo"=>$chave_y08_codigo);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $claidof->sql_record($claidof->sql_query($pesquisa_chave,"*",null," y08_codigo=$pesquisa_chave and $where"));
          if($claidof->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
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
js_tabulacaoforms("form2","chave_y08_codigo",true,1,"chave_y08_codigo",true);
</script>