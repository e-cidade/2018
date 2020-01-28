<?php
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clzonas = new cl_zonas;
$clzonas->rotulo->label("j50_zona");
$clzonas->rotulo->label("j50_descr");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lj50_zona?></label></td>
          <td><? db_input("j50_zona",10,$Ij50_zona,true,"text",4,"","chave_j50_zona"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lj50_descr?></label></td>
          <td><? db_input("j50_descr",10,$Ij50_descr,true,"text",4,"","chave_j50_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_zonas.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_zonas.php")==true){
             include("funcoes/db_func_zonas.php");
           }else{
           $campos = "zonas.*";
           }
        }
        if(isset($chave_j50_zona) && (trim($chave_j50_zona)!="") ){
	         $sql = $clzonas->sql_query($chave_j50_zona,$campos,"j50_zona");
        }else if(isset($chave_j50_descr) && (trim($chave_j50_descr)!="") ){
	         $sql = $clzonas->sql_query("",$campos,"j50_descr"," j50_descr like '$chave_j50_descr%' ");
        }else{
           $sql = $clzonas->sql_query("",$campos,"j50_zona","");
        }
        $repassa = array();
        if(isset($chave_j50_descr)){
          $repassa = array("chave_j50_zona"=>$chave_j50_zona,"chave_j50_descr"=>$chave_j50_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clzonas->sql_record($clzonas->sql_query($pesquisa_chave));
          if($clzonas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j50_descr',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
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
js_tabulacaoforms("form2","chave_j50_descr",true,1,"chave_j50_descr",true);
</script>
