<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("classes/db_arrebanco_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clarrebanco = new cl_arrebanco;
$clarrebanco->rotulo->label();
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
            <td width="4%" align="right" nowrap title="<?=$Tk00_numpre?>">
              <?=$Lk00_numpre?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k00_numpre",10,$Ik00_numpre,true,"text",4,"","chave_k00_numpre");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk00_numpar?>">
              <?=$Lk00_numpar?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k00_numpar",10,$Ik00_numpar,true,"text",4,"","chave_k00_numpar");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk00_numbco?>">
              <?=$Lk00_numbco?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k00_numbco",15,$Ik00_numbco,true,"text",4,"","chave_k00_numbco");
		       ?>
            </td>
          </tr>          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_arrebanco.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       if (isset($pesquisar)) { 
         
         $sCampos = "k00_numbco, k00_numpre, k00_numpar";
         $sWhere  = "";
         $sAnd    = "";
         
         if ( isset($chave_k00_numpre) && trim($chave_k00_numpre) != "" ) {
           $sWhere .= "k00_numpre = {$chave_k00_numpre}";
           $sAnd    = " and "; 
         } 
         
         if( isset($chave_k00_numpar) && trim($chave_k00_numpar) != "" ) {
           $sWhere .= $sAnd."k00_numpar = {$chave_k00_numpar}";
           $sAnd    = " and ";
         }
         
         if( isset($chave_k00_numbanco) && trim($chave_k00_numbanco) != "" ) {
           $sWhere .= $sAnd."k00_numbco = {$chave_k00_numbanco}";
           $sAnd    = " and ";
         }         

         if( isset($codbco) && $codbco != "" ) {
           $sWhere .= $sAnd." trim(k00_codbco) = '{$codbco}'";
           $sAnd    = " and ";
         }
         
         if( isset($codage) && $codage != "" ) {
           $sWhere .= $sAnd." trim(k00_codage) = '{$codage}'";
           $sAnd    = " and ";
         }         
         
         if ( $sWhere != "") {
           
           $rsArrebanco = $clarrebanco->sql_record($clarrebanco->sql_query(null,null,null,null,null,$sCampos,$sCampos,$sWhere));
           if ($clarrebanco->numrows != 0) {
             db_fieldsmemory($rsArrebanco,0);
             echo "<script>".$funcao_js."('$k00_numbco',false);</script>";
           } else {
	           echo "<script>".$funcao_js."('Numero Banco não encontrado',true);</script>";
           }
           
         }
         
       }
      ?>
     </td>
   </tr>
</table>
</body>
</html>