<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_matricula_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatricula = new cl_matricula;
$clmatricula->rotulo->label("ed60_matricula");
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_v_nome");
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
     <td width="4%" align="right" nowrap title="<?=$Ted60_matricula?>">
      <?=$Led60_matricula?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed60_matricula",10,$Ied60_matricula,true,"text",4,"","chave_ed60_matricula");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted47_v_nome?>">
      <?=$Led47_v_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed47_v_nome",50,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aluno.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   if(isset($excluir) && $excluir == "no") {
     
     $where  = " AND ed60_c_situacao != 'TRANSFERIDO REDE' AND ed60_c_situacao != 'TRANSFERIDO FORA' ";
     $where .= " AND ed60_c_situacao != 'TROCA DE TURMA'";
   }elseif(isset($excluir) && $excluir=="yes"){
    $where = " AND ed60_c_situacao = 'MATRICULADO'";
   }else{
    $where = "";
   }
   
    $campos  = "  ed60_matricula, ";
    $campos .= "  ed60_i_aluno, ";
    $campos .= "  aluno.ed47_v_nome, ";
    $campos .= "  ed60_i_turma, ";
    
    if ( isset($lSituacaoLabel) && !empty($lSituacaoLabel) ) {
      $campos .= "  ed60_c_situacao as dl_Situação, ";
    } else {
      $campos .= "  ed60_c_situacao , ";
    }
    
    $campos .= "  ed60_d_datamatricula, ";
    $campos .= "  ed60_d_datasaida, ";
    $campos .= "  ed60_c_concluida ";
    if (!isset($pesquisa_chave)) {
     
      if (isset($chave_ed60_matricula) && (trim($chave_ed60_matricula)!="") ) {
        $sql = $clmatricula->sql_query("",$campos,"ed60_i_numaluno,to_ascii(ed47_v_nome)"," ed60_matricula = $chave_ed60_matricula AND ed60_i_turma = $turma AND ed60_c_concluida = 'N' ".$where);
      }else if(isset($chave_ed47_v_nome) && (trim($chave_ed47_v_nome)!="") ){
        $sql = $clmatricula->sql_query("",$campos,"ed60_i_numaluno,to_ascii(ed47_v_nome)"," ed47_v_nome like '$chave_ed47_v_nome%' AND ed60_i_turma = $turma AND ed60_c_concluida = 'N' ".$where);
      }else{
        $sql = $clmatricula->sql_query("",$campos,"ed60_i_numaluno,to_ascii(ed47_v_nome)"," ed60_i_turma = $turma AND ed60_c_concluida = 'N' ".$where);
      }
      $repassa = array();

      if(isset($chave_ed60_matricula)) {
        $repassa = array("chave_ed60_matricula"=>$chave_ed60_matricula,"chave_ed47_v_nome"=>$chave_ed47_v_nome);
      }
      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    } else {
      
      if ($pesquisa_chave!=null && $pesquisa_chave!="") {
        
       $result = $clmatricula->sql_record($clmatricula->sql_query("","*",""," ed60_i_aluno = $pesquisa_chave AND ed60_i_turma = $turma AND ed60_c_concluida = 'N' ".$where));
       if($clmatricula->numrows!=0){
        db_fieldsmemory($result,0);
        echo "<script>".$funcao_js."('$ed47_v_nome','$ed60_matricula','$ed60_c_situacao','$ed60_d_datamatricula','$ed60_d_datasaida',false);</script>";
       }else{
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','',true);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed60_matricula",true,1,"chave_ed60_matricula",true);
</script>