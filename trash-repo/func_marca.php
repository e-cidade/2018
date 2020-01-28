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
include("classes/db_marca_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmarca = new cl_marca;
$clcgm = new cl_cgm;
$clmarca->rotulo->label("ma01_i_codigo");
$clcgm->rotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Tma01_i_codigo?>">
              <?=$Lma01_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
               db_input("ma01_i_codigo",10,$Ima01_i_codigo,true,"text",4,"","chave_ma01_i_codigo");
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
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_marca.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($campos)==false){
       if(file_exists("funcoes/db_func_marca.php")==true){
        include("funcoes/db_func_marca.php");
       }else{
        $campos = "marca.*";
       }
      }
      if(isset($todas)){
       $where = "";
      }else{
       $where = " ma01_c_ativo = 'S' and ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($chave_ma01_i_codigo) && (trim($chave_ma01_i_codigo)!="") ){
          $sql = $clmarca->sql_query("",$campos,"ma01_i_codigo",$where." ma01_i_codigo = $chave_ma01_i_codigo ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
          $sql = $clmarca->sql_query("",$campos,"z01_nome",$where." z01_nome like '$chave_z01_nome%' ");
        }
        db_lovrot(@$sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sql = $clmarca->sql_query("",$campos,"ma01_i_codigo",$where." ma01_i_codigo = $pesquisa_chave ");
          $result = $clmarca->sql_record($sql);
          if($clmarca->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ma01_i_codigo','$ma01_i_cgm','$z01_nome',false);</script>";
          }else{
            echo "<script>".$funcao_js."('','','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
          echo "<script>".$funcao_js."('','','',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>