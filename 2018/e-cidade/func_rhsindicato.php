<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhsindicato_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$clrhsindicato = new cl_rhsindicato;
$clrhsindicato->rotulo->label("rh116_sequencial");
$clrhsindicato->rotulo->label("rh116_codigo");
$clrhsindicato->rotulo->label("rh116_cnpj");
$clrhsindicato->rotulo->label("rh116_descricao");
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
            <td width="4%" align="right" nowrap title="<?php echo $Trh116_sequencial; ?>">
              <?php echo $Lrh116_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap> 
							<?php db_input("rh116_sequencial", 10, $Irh116_sequencial, true, "text", 4, "", "chave_rh116_sequencial"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh116_codigo; ?>">
              <?php echo $Lrh116_codigo; ?>
            </td>
            <td width="96%" align="left" nowrap> 
							<?php db_input("rh116_codigo", 10, $Irh116_codigo, true, "text", 4, "", "chave_rh116_codigo"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh116_cnpj; ?>">
              <?php echo $Lrh116_cnpj; ?>
            </td>
            <td width="96%" align="left" nowrap> 
							<?php db_input("rh116_cnpj", 10, $Irh116_cnpj, true, "text", 4, "", "chave_rh116_cnpj"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh116_descricao; ?>">
              <?php echo $Lrh116_descricao; ?>
            </td>
            <td width="96%" align="left" nowrap> 
							<?php db_input("rh116_descricao", 10, $Irh116_descricao, true, "text", 4, "", "chave_rh116_descricao"); ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhsindicato.hide();">
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
           if(file_exists("funcoes/db_func_rhsindicato.php")==true){
             include("funcoes/db_func_rhsindicato.php");
           }else{
           $campos = "rhsindicato.*";
           }
        }

				$sWhere = null;
				$aWhere = array();

				if ( !empty($oPost->chave_rh116_codigo) ) {
					$aWhere[] = "rh116_codigo ilike '%{$oPost->chave_rh116_codigo}%'";
				}

				if ( !empty($oPost->chave_rh116_cnpj) ) {
					$aWhere[] = "rh116_cnpj ilike '%{$oPost->chave_rh116_cnpj}%'";
				}
				
				if ( !empty($oPost->chave_rh116_descricao) ) {
					$aWhere[] = "rh116_descricao ilike '%{$oPost->chave_rh116_descricao}%'";
				}

				$sWhere = implode(' and ', $aWhere);

        if(isset($chave_rh116_sequencial) && (trim($chave_rh116_sequencial)!="") ){
	         $sql = $clrhsindicato->sql_query($chave_rh116_sequencial, $campos, "rh116_sequencial");
        } else if( !empty($sWhere) ){
	         $sql = $clrhsindicato->sql_query("", $campos, "rh116_sequencial", $sWhere);
        } else {
           $sql = $clrhsindicato->sql_query("",$campos, "rh116_sequencial", "");
        }

        $repassa = array();
        if(isset($chave_rh116_sequencial)){
          $repassa = array("chave_rh116_sequencial"=>$chave_rh116_sequencial,"chave_rh116_sequencial"=>$chave_rh116_sequencial);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhsindicato->sql_record($clrhsindicato->sql_query($pesquisa_chave));
          if($clrhsindicato->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh116_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_rh116_sequencial",true,1,"chave_rh116_sequencial",true);
</script>