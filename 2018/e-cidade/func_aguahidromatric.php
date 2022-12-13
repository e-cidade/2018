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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_aguahidromatric_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oAguaHidroMatric = new cl_aguahidromatric;
$oAguaHidroMatric->rotulo->label("x04_codhidrometro");
$oAguaHidroMatric->rotulo->label("x04_matric");
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
            <td width="4%" align="right" nowrap title="<?=$Tx04_codhidrometro?>">
              <label for="chave_x04_codhidrometro"><?=$Lx04_codhidrometro?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("x04_codhidrometro",6,$Ix04_codhidrometro,true,"text",4,"","chave_x04_codhidrometro");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tx04_matric?>">
              <?=$Lx04_matric?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php db_input("x04_matric",10,$Ix04_matric,true,"text",4,"","chave_x04_matric"); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguahidromatric.hide();">
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

        if(isset($campos)==false){

          if(file_exists("funcoes/db_func_aguahidromatric.php")==true){
            include(modification("funcoes/db_func_aguahidromatric.php"));
          }else{
            $campos = "aguahidromatric.*";
          }
        }

        if(isset($chave_x04_codhidrometro) && (trim($chave_x04_codhidrometro)!="") ){
	        $sql = $oAguaHidroMatric->sql_query_hidrometro($chave_x04_codhidrometro,$campos,"x04_codhidrometro");
        }else if(isset($chave_x04_matric) && (trim($chave_x04_matric)!="") ){
	        $sql = $oAguaHidroMatric->sql_query_hidrometro("",$campos,"x04_matric"," x04_matric like '$chave_x04_matric%' ");
        }else{
          $sql = $oAguaHidroMatric->sql_query_hidrometro("",$campos,"x04_codhidrometro","");
        }

        db_lovrot($sql,15,"()","",$funcao_js);

      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $oAguaHidroMatric->sql_record($oAguaHidroMatric->sql_query_hidrometro($pesquisa_chave));
          if($oAguaHidroMatric->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$x04_nrohidro',false);</script>";
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
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
