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

//MODULO: educa��o
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_disciplina_classe.php");
include("classes/db_caddisciplina_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldisciplina = new cl_disciplina;
$clcaddisciplina = new cl_caddisciplina;
$cldisciplina->rotulo->label("ed12_i_codigo");
$clcaddisciplina->rotulo->label("ed232_c_descr");

$curso    = isset($curso) && !empty($curso) ? $curso : "";
$disc_cad = isset($disc_cad) && !empty($disc_cad) ? $disc_cad : "";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="55%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted12_i_codigo?>">
      <?=$Led12_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed12_i_codigo",10,$Ied12_i_codigo,true,"text",4,"","chave_ed12_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted232_c_descr?>">
       <?=$Led232_c_descr?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed232_c_descr",30,$Ied232_c_descr,true,"text",4,"","chave_ed232_c_descr");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="curso" type="hidden" value="<?=$curso?>">
      <input name="disciplinas" type="hidden" value="<?=$disc_cad?>">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_disciplina.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
    <?
   
    $lSetarCampos = false;
    $sOrder       = "ed10_c_descr, ed232_c_descr";
    
    if (!isset($disciplinas) || $disciplinas=="") {
      $disciplinas = 0;
    }
    
    $where = "";
    if(isset($curso) && $curso!=""){
     $where .= " AND ed29_i_codigo = $curso";
    }
    
    if (isset($ensino) && $ensino != "") {
    
      $where        .= " AND ensino.ed10_i_codigo = $ensino";
      $lSetarCampos  = true;
    }
    
    if (!isset($pesquisa_chave)) {
    
      if (isset($campos)==false) {
    
        if (file_exists("funcoes/db_func_disciplina.php")==true) {
          include("funcoes/db_func_disciplina.php");
        }else{
          $campos = "disciplina.*";
        }
      }
      
      if ($lSetarCampos) {
        
        $campos = "distinct ed12_i_codigo, ed232_c_descr, ed10_c_descr as ed12_i_ensino, ed232_c_abrev";
        $sOrder = "ed12_i_codigo";
      }
      
      if (isset($chave_ed12_i_codigo) && (trim($chave_ed12_i_codigo)!="") ) {
        $sql = $cldisciplina->sql_query("", $campos, $sOrder," ed12_i_codigo = $chave_ed12_i_codigo AND ed12_i_codigo not in ($disciplinas)".$where);
      } else if(isset($chave_ed232_c_descr) && (trim($chave_ed232_c_descr)!="") ){
        $sql = $cldisciplina->sql_query("", $campos, $sOrder, " ed232_c_descr like '$chave_ed232_c_descr%' AND ed12_i_codigo not in ($disciplinas)".$where);
      } else {
        $sql = $cldisciplina->sql_query("", $campos, $sOrder, " ed12_i_codigo not in ($disciplinas)".$where);
      }
      db_lovrot($sql,15,"()","",$funcao_js);
    } else {
    
     if ($pesquisa_chave!=null && $pesquisa_chave!="") {
    
       $sql    = $cldisciplina->sql_query("","*","ed232_c_descr"," ed12_i_codigo = $pesquisa_chave AND ed12_i_codigo not in ($disciplinas)".$where);
       $result = $cldisciplina->sql_record($sql);
       if ($cldisciplina->numrows != 0) {
    
         db_fieldsmemory($result,0);
         echo "<script>".$funcao_js."('$ed232_c_descr',false);</script>";
       } else {
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
       }
     } else {
       echo "<script>".$funcao_js."('',false);</script>";
     }
    }
    ?>
  </td>
 </tr>
</table>
</body>
</html>