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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_usuarios_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_usuarios = new cl_db_usuarios;
$cldb_usuarios->rotulo->label("id_usuario");
$cldb_usuarios->rotulo->label("nome");
$sGnome = "t";
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
            <td width="4%" align="left" nowrap title="<?=$Tid_usuario?>">
              <?php echo $Lid_usuario; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_input("id_usuario",10,$Iid_usuario,true,"text",4,"","chave_id_usuario"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="left" nowrap title="<?=$Tnome?>">
              <?php echo $Lnome; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_input("nome",60,$Inome,true,"text",4,"","chave_nome"); ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_usuarios.hide();">
             </td>
          </tr>

        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php
      if(!isset($pesquisa_chave)){

        if(isset($pesquisaInstit)){

          if(isset($campos)==false){
             if(file_exists("funcoes/db_func_db_usuarios.php")==true){
               include(modification("funcoes/db_func_db_usuarios.php"));
             }else{
             $campos = "db_usuarios.*";
             }
          }
          if (isset($chave_id_usuario) && (trim($chave_id_usuario) !="" )) {
             $sql = $cldb_usuarios->sql_query_instit($chave_id_usuario,$campos,"nome"," usuarioativo = '1' and usuext = 0 and id_usuario = {$chave_id_usuario} and db_depusu.coddepto = {$coddepto}");
          } else if(isset($chave_nome) && (trim($chave_nome)!="")) {
             $sql = $cldb_usuarios->sql_query_instit("",$campos,"nome"," usuarioativo = '1' and usuext = 0 and nome like '$chave_nome%' and db_depusu.coddepto = {$coddepto}");
          }else{
            if(!empty($sDeptos)){
              $campos = str_replace("db_usuarios.id_usuario,", "", $campos);
              $campos = " distinct db_usuarios.id_usuario, " . $campos;
              $sql = $cldb_usuarios->sql_query_instit("",$campos,"nome"," usuarioativo = '1' and usuext = 0 and db_depusu.coddepto in ({$sDeptos})");
            } else {

              $sql = $cldb_usuarios->sql_query_instit("",$campos,"nome"," usuarioativo = '1' and usuext = 0 and db_depusu.coddepto = {$coddepto}");
            }
          }
          $repassa = array();
          if(isset($chave_nome)){
            $repassa = array("chave_id_usuario"=>$chave_id_usuario,"chave_nome"=>$chave_nome);
          }
          db_lovrot($sql,50,"()","",$funcao_js,"","NoMe",$repassa);
        } else{

          if(isset($campos)==false){
             if(file_exists("funcoes/db_func_db_usuarios.php")==true){
               include(modification("funcoes/db_func_db_usuarios.php"));
             }else{
             $campos = "db_usuarios.*";
             }
          }
          if (isset($chave_id_usuario) && (trim($chave_id_usuario) !="" )) {
  	         $sql = $cldb_usuarios->sql_query($chave_id_usuario,$campos,"nome"," usuarioativo = '1' and usuext = 0 and id_usuario = {$chave_id_usuario}");
          } else if(isset($chave_nome) && (trim($chave_nome)!="")) {
  	         $sql = $cldb_usuarios->sql_query("",$campos,"nome"," usuarioativo = '1' and usuext = 0 and nome like '$chave_nome%' ");
          }else{
             $sql = $cldb_usuarios->sql_query("",$campos,"nome"," usuarioativo = '1' and usuext = 0");
          }
          $repassa = array();
          if(isset($chave_nome)){
            $repassa = array("chave_id_usuario"=>$chave_id_usuario,"chave_nome"=>$chave_nome);
          }
          db_lovrot($sql,50,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($pesquisa_chave." and usuarioativo = '1' and usuext = 0"));

          if($result && $cldb_usuarios->numrows!=0){
							db_fieldsmemory($result,0);
					    if(isset($login)){
				              echo "<script>".$funcao_js."('$nome','$login',false);</script>";
					    }else{
				              echo "<script>".$funcao_js."('$nome',false);</script>";
					    }
          }else{
					    if(isset($login)){
					      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',false,false);</script>";
	            }else{
					      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',false);</script>";
					    }
          }
        }else{
					  if(isset($login)){
					     echo "<script>".$funcao_js."('',false,false);</script>";
					  }else{
					     echo "<script>".$funcao_js."('',false);</script>";
					  }
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script type="text/javascript">
js_tabulacaoforms("form2","chave_nome",true,1,"chave_nome",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
