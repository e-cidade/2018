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
require_once("classes/db_conhistdoc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconhistdoc = new cl_conhistdoc;
$clconhistdoc->rotulo->label("c53_coddoc");
$clconhistdoc->rotulo->label("c53_descr");

$sWhereTipoDocumento = "";
if (isset($iCodigoTipoDocumento) && !empty($iCodigoTipoDocumento)) {
  $sWhereTipoDocumento = " and c53_tipo = {$iCodigoTipoDocumento}";
}

// where adiciona para filtra tipos de documento reconhecimento contabil.
if (isset($lTipoReconhecimentoContabil)) {
	$sWhereTipoDocumento .= " and c53_tipo in (3001, 3002) ";
}

if(!USE_PCASP and false) {
  
  $aDocumentosValidos   = array(100, 33, 3, 23, 5, 37, 35, 1, 101, 4, 24, 34, 6, 36, 38, 2, 31, 32, 58, 61); 
  $sDocumentosValidos   = implode(",", $aDocumentosValidos);
  $sWhereTipoDocumento .= " and  c53_coddoc in ({$sDocumentosValidos})";
}

/**
 * Filtro para não exibir documentos onde exite processamento na rotina nova (con4_processalancamentos002.php) 
 */
if ( isset($lDocumentosProcessadosOutraRotina) ) {
  
  $aDocumentos = array(80, 81, 900, 901, 903, 904, 414, 415, 700, 701, 703, 508, 509, 510, 511, 513, 514);  
  $sDocumentos = implode(',', $aDocumentos);
  $sWhereTipoDocumento .= " and c53_coddoc not in($sDocumentos)";
} 
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
            <td width="4%" align="right" nowrap title="<?php echo $Tc53_coddoc;?>">
              <?php echo $Lc53_coddoc;?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
    		        db_input("c53_coddoc",4,$Ic53_coddoc,true,"text",4,"","chave_c53_coddoc");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Tc53_descr;?>">
              <?php echo $Lc53_descr;?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("c53_descr",50,$Ic53_descr,true,"text",4,"","chave_c53_descr");
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
        if(isset($campos) == false) {
           if(file_exists("funcoes/db_func_conhistdoc.php") == true) {
             include("funcoes/db_func_conhistdoc.php");
           } else {
             $campos = "conhistdoc.*";
           }
        }

        if(isset($chave_c53_coddoc) && (trim($chave_c53_coddoc) != "") ) {

           $sWhereSequencial = "c53_coddoc = {$chave_c53_coddoc} {$sWhereTipoDocumento} ";
           $sql = $clconhistdoc->sql_query(null,$campos,"c53_coddoc",$sWhereSequencial);

        } else if(isset($chave_c53_descr) && (trim($chave_c53_descr) != "") ) {
	         $sql = $clconhistdoc->sql_query("",$campos,"c53_descr"," c53_descr like '$chave_c53_descr%' $sWhereTipoDocumento ");
        } else {
           $sql = $clconhistdoc->sql_query("",$campos,"c53_coddoc","1=1 {$sWhereTipoDocumento}");
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      } else {
        if($pesquisa_chave != null && $pesquisa_chave != "") {
          
          $sWhereCodigoSequencial = "c53_coddoc = {$pesquisa_chave} {$sWhereTipoDocumento}";
          $result = $clconhistdoc->sql_record($clconhistdoc->sql_query(null, "*", null, $sWhereCodigoSequencial));
          if($clconhistdoc->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c53_descr',false);</script>";

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
<?php
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?php
}
?>