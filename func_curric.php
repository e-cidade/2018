<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_curric_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clcurric = new cl_curric;
$clcurric->rotulo->label("h03_seq");
$clcurric->rotulo->label("h03_numcgm");
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
            <td width="4%" align="right" nowrap title="<?=$Th03_seq?>">
              <?=$Lh03_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h03_seq",4,$Ih03_seq,true,"text",4,"","chave_h03_seq");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th03_numcgm?>">
              <?=$Lh03_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h03_numcgm",6,$Ih03_numcgm,true,"text",4,"","chave_h03_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_curric.hide();">
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
        $campos = "h03_seq, c.z01_nome, h01_descr, h03_data, h02_descr, e.z01_nome as h01_cgmentid, h03_cargahoraria ";
        if(isset($chave_h03_seq) && (trim($chave_h03_seq)!="") ){
	         $sql = $clcurric->sql_query_curric($chave_h03_seq,$campos,"h03_seq");
        }else if(isset($chave_h03_numcgm) && (trim($chave_h03_numcgm)!="") ){
	         $sql = $clcurric->sql_query_curric("",$campos,"h03_numcgm"," h03_numcgm like '$chave_h03_numcgm%' ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clcurric->sql_query_curric("",$campos,"c.z01_nome"," c.z01_nome like '$chave_z01_nome%' ");
        }else{
           $sql = $clcurric->sql_query_curric("",$campos,"h03_seq","");
        }
        $repassa = array();
        if(isset($chave_h03_numcgm)){
          $repassa = array("chave_h03_seq"=>$chave_h03_seq,"chave_h03_numcgm"=>$chave_h03_numcgm);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcurric->sql_record($clcurric->sql_query_curric($pesquisa_chave));
          if($clcurric->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h03_numcgm',false);</script>";
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
js_tabulacaoforms("form2","chave_h03_numcgm",true,1,"chave_h03_numcgm",true);
</script>