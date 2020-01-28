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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_turno_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clperiodoescola = new cl_periodoescola;
$clturno = new cl_turno;
$clperiodoescola->rotulo->label("ed17_i_codigo");
$clperiodoescola->rotulo->label("ed17_i_turno");
$clturno->rotulo->label("ed15_c_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Ted17_i_codigo?>">
              <?=$Led17_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("ed17_i_codigo",4,$Ied17_i_codigo,true,"text",4,"","chave_ed17_i_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ted15_c_nome?>">
              <?=$Led17_i_turno?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("ed15_c_nome",20,$Ied15_c_nome,true,"text",4,"","chave_ed15_c_nome");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_periodoescola.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $codescola = db_getsession("DB_coddepto");
      $where = " ed17_i_escola = $codescola ";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_periodoescola.php")==true){
             include("funcoes/db_func_periodoescola.php");
           }else{
           $campos = "periodoescola.*";
           }
        }
        if(isset($chave_ed17_i_codigo) && (trim($chave_ed17_i_codigo)!="") ){
                 $sql = $clperiodoescola->sql_query("",$campos,"ed17_i_turno asc,ed17_i_periodoaula asc",$where);
        }else if(isset($chave_ed15_c_nome) && (trim($chave_ed15_c_nome)!="") ){
                 $sql = $clperiodoescola->sql_query("",$campos,"ed17_i_turno asc,ed17_i_periodoaula asc",$where." and ed15_c_nome like '$chave_ed15_c_nome%' ");
        }else{
           $sql = $clperiodoescola->sql_query("",$campos,"ed17_i_turno asc,ed17_i_periodoaula asc",$where);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clperiodoescola->sql_record($clperiodoescola->sql_query("","*","",$where));
          if($clperiodoescola->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed17_i_codigo',false);</script>";
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