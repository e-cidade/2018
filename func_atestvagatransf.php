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

//MODULO: educação
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_atestvaga_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatestvaga = new cl_atestvaga;
$clrotulo    = new rotulocampo;
$clatestvaga->rotulo->label("ed102_i_codigo");
$clrotulo->label("ed47_v_nome");
$escola = db_getsession("DB_coddepto");
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
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted102_i_codigo?>">
      <?=$Led102_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed102_i_codigo",10,$Ied102_i_codigo,true,"text",4,"","chave_ed102_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted47_v_nome?>">
      <?=$Led47_v_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_v_nome",40,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atestvaga.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
    $iEscola   = db_getsession("DB_coddepto");
    $sCampos   = " distinct ";
    $sCampos  .= "           atestvaga.ed102_i_codigo, ";
    $sCampos  .= "           aluno.ed47_i_codigo, ";
    $sCampos  .= "           aluno.ed47_v_nome, ";
    $sCampos  .= "           escola.ed18_c_nome, ";
    $sCampos  .= "           base.ed31_c_descr, ";
    $sCampos  .= "           calendario.ed52_c_descr, ";
    $sCampos  .= "           serie.ed11_c_descr, ";
    $sCampos  .= "           turno.ed15_c_nome, ";
    $sCampos  .= "           atestvaga.ed102_d_data, ";
    $sCampos  .= "           atestvaga.ed102_i_base, ";
    $sCampos  .= "           atestvaga.ed102_i_calendario, ";
    $sCampos  .= "           escola.ed18_i_codigo, ";
    $sCampos  .= "           serie.ed11_i_codigo, ";
    $sCampos  .= "           turno.ed15_i_codigo ";
    
    $sWhere    = " AND exists(select * from matricula inner join turma on ed57_i_codigo = ed60_i_turma ";
    $sWhere   .= "          where ed60_i_aluno = aluno.ed47_i_codigo ";
    $sWhere   .= "         and ed57_i_escola = $iEscola  ";
    $sWhere   .= "         AND ed60_d_datamatricula = (select m.ed60_d_datamatricula from matricula as m "; 
    $sWhere   .= "                                             where m.ed60_i_aluno = ed47_i_codigo  ";
    $sWhere   .= "                                            order by m.ed60_d_datamatricula desc limit 1) ";
    $sWhere   .= " AND not exists(select * from transfescolarede ";
    $sWhere   .= "             where ed103_i_atestvaga = ed102_i_codigo ";
    $sWhere   .= "                ) ";
    $sWhere   .= "           ) ";
    if (isset($chave_ed102_i_codigo) && (trim($chave_ed102_i_codigo) != "")) {
      $sql = $clatestvaga->sql_query("",$sCampos,"ed102_d_data desc",
                                     " ed102_i_escola != $iEscola AND ed102_i_codigo = $chave_ed102_i_codigo ".$sWhere);
    } else if (isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome) != "")) {
      $sql = $clatestvaga->sql_query("",$sCampos,"ed102_d_data desc"," ed102_i_escola != $iEscola 
                                     AND ed47_v_nome like '$chave_ed47_v_nome%' ".$sWhere);
    } else {
       $sql = $clatestvaga->sql_query("",$sCampos,"ed102_d_data desc"," ed102_i_escola != $iEscola ".$sWhere);
    }
    $repassa = array();
    if (isset($chave_ed102_i_codigo)) {
      $repassa = array("chave_ed102_i_codigo"=>$chave_ed102_i_codigo,"chave_ed102_i_codigo"=>$chave_ed102_i_codigo);
    }
    if (!isset($pesquisa_chave)) {
      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
    } else {
      if ($pesquisa_chave != null && $pesquisa_chave != "") { 
        $result = $clatestvaga->sql_record($clatestvaga->sql_query("",$sCampos,"ed102_d_data desc",
                                                                   " ed102_i_escola != $iEscola 
                                                                     AND ed102_i_codigo = $pesquisa_chave ".$sWhere));
        if ($clatestvaga->numrows != 0) {
          db_fieldsmemory($result,0);
          echo "<script>parent.js_getDadosLookup($ed47_i_codigo,$ed102_i_base,$ed102_i_calendario,'$ed102_d_data',
                                                 $ed18_i_codigo,'$ed18_c_nome',$ed102_i_codigo,$ed15_i_codigo,
                                                 '$ed15_c_nome',$ed11_i_codigo,'$ed11_c_descr','$ed47_v_nome');</script>";
        } else {
          echo "<script>parent.js_getDadosLookup(null);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed102_i_codigo",true,1,"chave_ed102_i_codigo",true);
</script>