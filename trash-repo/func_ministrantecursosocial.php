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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

db_postmemory($_POST);
db_postmemory($_GET);
$oGet = db_utils::postMemory($_GET);

$oDaoCursoSocial = new cl_cursosocial;
$oRotuloCampo    = new rotulocampo();
$oRotuloCampo->label("as19_ministrante");
$oRotuloCampo->label("z01_nome");
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
       <table width="35%" align="center" >
	     <form name="form2" method="post" action="" >
          <tr title="Pesquise um cidadão">  
            <td align="right" nowrap="nowrap" class="bold" >Cidadão:</td>
            <td nowrap="nowrap"> 
              <?
		            db_input("as19_ministrante", 10, $Ias19_ministrante, true, "text", 4, "", "chave_as19_ministrante");
		          ?>
            </td>
            <td nowrap="nowrap"> 
              <?
		            db_input("z01_nome", 30, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="3" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
              <input name="Fechar"    type="button" id="fechar"     value="Fechar" 
                     onClick="parent.db_iframe_ministrantecursosocial.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
    <?
      $campos = "distinct cursosocial.as19_ministrante, cgm.z01_nome";
      $sOrder = "z01_nome";
      if (!isset($pesquisa_chave)) {
        
        $aWhere = array();
        /**
         * Adiciona os filtros informados em um array
         */
        if (isset($chave_as19_ministrante) && !empty($chave_as19_ministrante)) {
          $aWhere[] = "as19_ministrante = {$chave_as19_ministrante}";
        } 
        if (isset($chave_z01_nome) && !empty($chave_z01_nome)) {
          $aWhere[] = " trim(z01_nome) ilike trim('{$chave_z01_nome}%')";
        } 
        
        $sWhere  = implode(" and ", $aWhere);
        $sSql    = $oDaoCursoSocial->sql_query(null, $campos, $sOrder, $sWhere);
        $repassa = array();

        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere = " as19_ministrante = {$pesquisa_chave}";  
          $sSql   = $oDaoCursoSocial->sql_query(null, $campos, $sOrder, $sWhere);
          $result = $oDaoCursoSocial->sql_record($sSql);
          
          if ($oDaoCursoSocial->numrows > 0) {
          	
            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."(false, '$z01_nome');</script>";
          } else {
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
          }
        } else {
	        echo "<script>".$funcao_js."(true, '');</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>