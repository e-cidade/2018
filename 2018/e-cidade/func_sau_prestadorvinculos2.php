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
include("classes/db_sau_prestadorvinculos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_prestadorvinculos = new cl_sau_prestadorvinculos;
$clsau_prestadorvinculos->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("z01_nome");
$clrotulo->label("s111_procedimento");
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
	     <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts111_i_codigo?>">
              <?=$Ls111_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("s111_i_codigo",10,$Is111_i_codigo,true,"text",4,"","chave_s111_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts111_procedimento?>">
              <?=$Ls111_procedimento?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("s111_procedimento",10,$Is111_procedimento,true,"text",4,"","chave_s111_procedimento");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts111_i_prestador?>">
              <?=$Ls111_i_prestador?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("s111_i_prestador",10,$Is111_i_prestador,true,"text",4,"","chave_s111_i_prestador");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",49,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_prestadorvinculos.hide();">
             </td>
          </tr>
        </table>
        </form>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = " s111_c_situacao = 'A' ";
      if( isset($chave_s111_procedimento) && (int)$chave_s111_procedimento != 0 ){
         $where .= "and s111_procedimento = $chave_s111_procedimento";  
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           //if( isset($chave_s111_i_prestador) ){
           	   $campos = "s111_i_prestador, z01_nome, s111_i_codigo";	 
           //}else{
           //	   $campos = " distinct s111_i_prestador, z01_nome";	 
           //}
        }
        $where .= " and exists (select * 
        						  from sau_prestadorhorarios 
        						  where sau_prestadorhorarios.s112_i_prestadorvinc = sau_prestadorvinculos.s111_i_codigo 
        						) ";
        if(isset($chave_s111_i_codigo) && (trim($chave_s111_i_codigo)!="") ){
	         $sql = $clsau_prestadorvinculos->sql_query($chave_s111_i_codigo." and ".$where,$campos,"s111_i_codigo");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clsau_prestadorvinculos->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' and $where ");
        }else if(isset($chave_s111_i_prestador) && (trim($chave_s111_i_prestador)!="") ){
	         $sql = $clsau_prestadorvinculos->sql_query("",$campos,"z01_nome"," s111_i_prestador = $chave_s111_i_prestador and $where ");
        }else{
           $sql = $clsau_prestadorvinculos->sql_query("",$campos,"z01_nome","$where");
        }
        $repassa = array();
        if(isset($chave_s111_i_codigo)){
          $repassa = array("chave_s111_i_codigo"=>$chave_s111_i_codigo,"chave_s111_i_codigo"=>$chave_s111_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsau_prestadorvinculos->sql_record($clsau_prestadorvinculos->sql_query($pesquisa_chave));
          if($clsau_prestadorvinculos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$s111_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_s111_i_codigo",true,1,"chave_s111_i_codigo",true);
</script>