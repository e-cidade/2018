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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_censocursoprofiss_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcensocursoprofiss = new cl_censocursoprofiss;
$clcensocursoprofiss->rotulo->label("ed247_i_codigo");
$clcensocursoprofiss->rotulo->label("ed247_c_descr");
$clcensocursoprofiss->rotulo->label("ed247_i_tipo");
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
     <td width="4%" align="right" nowrap title="<?=$Ted247_i_codigo?>">
      <?=$Led247_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed247_i_codigo",20,$Ied247_i_codigo,true,"text",4,"","chave_ed247_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted247_c_descr?>">
      <?=$Led247_c_descr?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed247_c_descr",50,$Ied247_c_descr,true,"text",4,"","chave_ed247_c_descr");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted247_i_tipo?>">
      <?=$Led247_i_tipo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?
      $x = array( ''=>''
                 ,'1'=>'AGROPECUÁRIA'
                 ,'2'=>'RECURSOS PESQUEIROS'
                 ,'3'=>'INDÚSTRIA'
                 ,'4'=>'MINERAÇÃO'
                 ,'5'=>'QUÍMICA'
                 ,'6'=>'CONSTRUÇÃO CIVIL'
                 ,'7'=>'GEOMÁTICA'
                 ,'8'=>'COMÉRCIO'
                 ,'9'=>'TURISMO E HOSPITALIDADE'
                 ,'10'=>'TRANSPORTES'
                 ,'11'=>'TELECOMUNICAÇÕES'
                 ,'12'=>'INFORMÁTICA'
                 ,'13'=>'GESTÃO'
                 ,'14'=>'SAÚDE'
                 ,'15'=>'COMUNICAÇÃO'
                 ,'16'=>'ARTES'
                 ,'17'=>'DESENVOLVIMENTO SOCIAL E LAZER'
                 ,'18'=>'IMAGEM PESSOAL'
                 ,'19'=>'MEIO AMBIENTE'
                 ,'20'=>'DESIGN'
                 ,'21'=>'SERVIÇOS DE APOIO ESCOLAR'
                );
      db_select('chave_ed247_i_tipo',$x,true,@$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_censocursoprofiss.hide();">
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
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_censocursoprofiss.php")==true){
      include(modification("funcoes/db_func_censocursoprofiss.php"));
     }else{
      $campos = "censocursoprofiss.*";
     }
    }
    if(isset($chave_ed247_i_codigo) && (trim($chave_ed247_i_codigo)!="") ){
     $sql = $clcensocursoprofiss->sql_query($chave_ed247_i_codigo,$campos,"ed247_c_descr");
    }else if(isset($chave_ed247_c_descr) && (trim($chave_ed247_c_descr)!="") ){
     $sql = $clcensocursoprofiss->sql_query("",$campos,"ed247_c_descr"," ed247_c_descr like '$chave_ed247_c_descr%' ");
    }else if(isset($chave_ed247_i_tipo) && (trim($chave_ed247_i_tipo)!="") ){
     $sql = $clcensocursoprofiss->sql_query("",$campos,"ed247_c_descr"," ed247_i_tipo = $chave_ed247_i_tipo ");
    }else{
     $sql = $clcensocursoprofiss->sql_query("",$campos,"ed247_c_descr","");
    }
    $repassa = array();
    if(isset($chave_ed247_i_codigo)){
     $repassa = array("chave_ed247_i_codigo"=>$chave_ed247_i_codigo,"chave_ed247_c_descr"=>$chave_ed247_c_descr,"chave_ed247_i_tipo"=>$chave_ed247_i_tipo);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = $clcensocursoprofiss->sql_record($clcensocursoprofiss->sql_query($pesquisa_chave));
     if($clcensocursoprofiss->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed247_i_codigo','$ed247_c_descr',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_ed247_i_codigo",true,1,"chave_ed247_i_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
