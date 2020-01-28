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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conhistdoctipo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconhistdoctipo = new cl_conhistdoctipo;
$clconhistdoctipo->rotulo->label("c57_sequencial");
$clconhistdoctipo->rotulo->label("c57_descricao"); 

$sWhereCodigoSequencial = "";

//Tipos de documento permitidos
$sWhereTipos = " and c57_sequencial in(100,10,20,30,11,21,31,101,200,201)";
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
            <td width="4%" align="right" nowrap title="<?php echo $Tc57_sequencial; ?>">
              <?php echo $Lc57_sequencial;?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("c57_sequencial",4,$Ic57_sequencial,true,"text",4,"","chave_c57_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Tc57_descricao; ?>">
              <?php echo $Lc57_descricao;?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("c57_descricao",50,$Ic57_descricao,true,"text",4,"","chave_c57_descricao");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conhistdoc.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
    <?php

      if(!isset($pesquisa_chave)) {
        if(isset($campos)==false) {
           
           $campos = "conhistdoctipo.*";
        }

        if(isset($chave_c57_sequencial) && (trim($chave_c57_sequencial)!="") ) {

           $sWhereSequencial = "c57_sequencial = {$chave_c57_sequencial} ";
           $sql = $clconhistdoctipo->sql_query(null,$campos,"c57_sequencial",$sWhereSequencial . $sWhereTipos);

        } else if(isset($chave_c57_descricao) && (trim($chave_c57_descricao)!="") ) {
          
           $sql = $clconhistdoctipo->sql_query("",$campos,"c57_descricao"," c57_descricao ilike '%$chave_c57_descricao%' {$sWhereTipos} ");
        } else {
           $sql = $clconhistdoctipo->sql_query("",$campos,"c57_sequencial","1=1 {$sWhereTipos}");
        }

        db_lovrot($sql,15,"()","",$funcao_js);

      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!="") {
          
          $sWhereCodigoSequencial = "c57_sequencial = {$pesquisa_chave} {$sWhereTipos}";
          $result = $clconhistdoctipo->sql_record($clconhistdoctipo->sql_query(null, "*", null, $sWhereCodigoSequencial . $sWhereTipos));
          if($clconhistdoctipo->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c57_descricao',false);</script>";
          } else {

	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
<?php if(!isset($pesquisa_chave)){ ?>
  <script>
  </script>
<?php }?>