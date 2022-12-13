<?
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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_pensao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sWhere   = "";
$sAnd     = "";

$clpensao = new cl_pensao;
$clpensao->rotulo->label("r52_anousu");
$clpensao->rotulo->label("r52_mesusu");
$clpensao->rotulo->label("r52_regist");
$clpensao->rotulo->label("r52_numcgm");
$clpensao->rotulo->label("z01_nome");

if (!isset($chave_r52_mesusu)) {
  $chave_r52_mesusu = db_mesfolha();
}

if (!isset($chave_r52_anousu)) {
  $chave_r52_anousu = db_anofolha();
}

if (isset($valor_testa_rescisao)) {
	
  $chave_r52_regist = $valor_testa_rescisao;
  $retorno          = db_alerta_dados_func($testarescisao,$valor_testa_rescisao,db_anofolha(), db_mesfolha());
  if ($retorno != "") {
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    function js_recebe_click(value){
      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','funcao_js');
      obj.setAttribute('id','funcao_js');
      obj.setAttribute('value','<?=$funcao_js?>');
      document.form2.appendChild(obj);

      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','valor_testa_rescisao');
      obj.setAttribute('id','valor_testa_rescisao');
      obj.setAttribute('value',value);
      document.form2.appendChild(obj);

      document.form2.submit();
    }
  </script>
  <?
}
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td align="right" nowrap title="Digite o Ano / Mes de competência" >
              <strong>Ano / Mês:</strong>
            </td>
            <td nowrap>
              <?
              db_input('r52_anousu',4,$Ir52_anousu,true,'text',4,'',"chave_r52_anousu");
              ?>
              &nbsp;/&nbsp;
              <?
              db_input('r52_mesusu',2,$Ir52_mesusu,true,'text',4,'',"chave_r52_mesusu");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr52_regist?>">
              <?=$Lr52_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r52_regist",10,$Ir52_regist,true,"text",4,"","chave_r52_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr52_numcgm?>">
              <?=$Lr52_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("r52_numcgm",10,$Ir52_numcgm,true,"text",4,"","chave_r52_numcgm");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pensao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos)==false) {
        	
           if (file_exists("funcoes/db_func_pensao.php") == true) {
             include("funcoes/db_func_pensao.php");
           } else {
             $campos = "pensao.*";
           }
        }
        
        if (isset($chave_r52_anousu) && !empty($chave_r52_anousu)) {
        	
          $sWhere .= " {$sAnd} pensao.r52_anousu = {$chave_r52_anousu}";
          $sAnd    = " and ";
        }
        
        if (isset($chave_r52_mesusu) && !empty($chave_r52_mesusu)) {
        	
          $sWhere .= " {$sAnd} pensao.r52_mesusu = {$chave_r52_mesusu}";
          $sAnd    = " and ";
        }
        
        if (isset($chave_r52_regist) && !empty($chave_r52_regist)) {
          
          $sWhere .= " {$sAnd} pensao.r52_regist = {$chave_r52_regist}";
          $sAnd    = " and ";
        }
        
        if (isset($chave_r52_numcgm) && !empty($chave_r52_numcgm)) {
          
          $sWhere .= " {$sAnd} pensao.r52_numcgm = {$chave_r52_numcgm}";
          $sAnd    = " and ";
        }
        
        if (isset($instit) && !empty($instit)) {

          $sWhere .= "{$sAnd} rhpessoalmov.rh02_instit = {$instit}";
          $sAnd    = " and ";
        }
        
        if (isset($chave_r52_regist) && (trim($chave_r52_regist) != "") ) {
	        $sSqlPensao = $clpensao->sql_query_dados(null, null, null, null, $campos, "r52_mesusu", $sWhere);
        } else if (isset($chave_r52_numcgm) && (trim($chave_r52_numcgm) != "") ) {
        	
        	$sWhere     = " r52_numcgm like '{$chave_r52_numcgm}%' {$sAnd} {$sWhere} ";
	        $sSqlPensao = $clpensao->sql_query_dados(null, null, null, null, $campos, "r52_numcgm", $sWhere);
        } else {
        	
        	$sOrderBy   = "r52_anousu#r52_mesusu#r52_regist#r52_numcgm";
          $sSqlPensao = $clpensao->sql_query_dados(null, null, null, null, $campos, $sOrderBy, $sWhere);
        }
        
        db_lovrot($sSqlPensao,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|r52_regist" : $funcao_js));
      } else {
        
      	if ($pesquisa_chave != null && $pesquisa_chave != "") {

      		$sSqlPensao  = $clpensao->sql_query_dados($chave_r52_anousu, $chave_r52_mesusu, $pesquisa_chave);
      		$rsSqlPensao = $clpensao->sql_record($sSqlPensao);
          if ($clpensao->numrows != 0) {
            
          	db_fieldsmemory($rsSqlPensao, 0);  
            if (isset($testarescisao)) {
            	
              $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
              if ($retorno != "") {
                db_msgbox($retorno);
              }
	          }
            
	          echo "<script>".$funcao_js."('$r52_numcgm',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>