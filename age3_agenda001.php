<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
?>

<html>
<head>
<title>Pesquisa de Contatos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.dblov {
    font-family: Arial, Helvetica, sans-serif;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
.fonte {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;
}
.bordas {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #666666;
}
.botao {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #60BEE4;
	border: 1px solid #FFFFFF;
	cursor: hand;	
}
table {
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body bgcolor=#CCCCCC bgcolor="#E7E7E7" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
<table width="770" height="100%" border="0" cellspacing="0" cellpadding="0">
<!--  <tr>
      <td bgcolor="#656B6F"><img src="imagens/topo_agenda_o.gif" width="423" height="92" border="0"></td>
  </tr>-->
  <tr height="40">
      <td align="center" valign="middle" > 
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="fonte">
          <tr> 
            <td colspan="3" align="center" width="36%" align="right" nowrap><strong>Pesquisar:&nbsp;&nbsp;</strong> 
              <input  name="texto_pesquisa" type="text" id="texto_pesquisa3"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </td>
          </tr>
        </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle" bgcolor="#E7E7E7">
	<?
	$CorFundoCabec = "#438BC5";
	$CorResult = "#D4D609";
	$CorResto = "white";
	$db_corcabec = "#FFFFFF";
	$cor1 = "#B2B3B7";
    $cor2 = "#E7E7E7";	
	
	if(isset($HTTP_POST_VARS["pesquisar_x"]) && isset($HTTP_POST_VARS["filtro"])) {
	  $queryAux = base64_decode($HTTP_POST_VARS["filtro"]);
	  $prim = substr($queryAux,0,strpos($queryAux,"like"));
	  $seg = substr($queryAux,strpos($queryAux,"ORDER"));
	  $HTTP_POST_VARS["filtro"] = base64_encode("$prim like upper('".$HTTP_POST_VARS["texto_pesquisa"]."%') $seg");
      $HTTP_POST_VARS["offsetNoMe"] = "0";
      $HTTP_POST_VARS["totregNoMe"] = "";
	}
    if(isset($HTTP_POST_VARS["filtro"])) {
	  db_lov(base64_decode($HTTP_POST_VARS["filtro"]),20,"",$HTTP_POST_VARS["filtro"],$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe");	  	
	} else if(isset($HTTP_POST_VARS["pesquisar_x"])) {
      $query = "SELECT g01_id as Código, ";
      reset($HTTP_POST_VARS);
      for($i = 0;$i < sizeof($HTTP_POST_VARS);$i++) {
        if(substr(key($HTTP_POST_VARS),0,2) == "sc")
	      if(pos($HTTP_POST_VARS) != "")
	        $query .= pos($HTTP_POST_VARS).",";
	    next($HTTP_POST_VARS);
      }
      $query[strlen($query) - 1] = " ";
      $query .= " FROM db_contatos WHERE upper(".$HTTP_POST_VARS["PesquisarPor"].") like upper('%".$HTTP_POST_VARS["texto_pesquisa"]."%')";
      $query .= " ORDER BY ".$HTTP_POST_VARS["ordenar"]." ".$HTTP_POST_VARS["ascdesc"];
	  db_lov($query,20,"",base64_encode($query),$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe");
	} else {
	?>
        <table width="77%" border="0" cellspacing="0" cellpadding="5">         
          <tr bgcolor="#A9A9CF" class="fonte"> 
            <td width="27%" height="20" nowrap bgcolor="#438BC5"><em><strong>Pesquisar por:</strong></em></td>
            <td width="34%" height="20" nowrap bgcolor="#5097CF"><em><strong>Selecionar campos:</strong></em></td>
            <td width="39%" height="20" nowrap bgcolor="#3BADE3"><table width="67%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="35%" nowrap bgcolor="#3BADE3" class="fonte"><em><strong>Ordenar por: </strong></em></td>
                  <td width="65%" nowrap bgcolor="#3BADE3" class="fonte">
				    <em><strong> 
                    <input id="ascdesc1" name="ascdesc" type="radio" value="asc" checked>
                    </strong></em><label for="ascdesc1">ascendente</label> <br> 
					<input id="ascdesc2" type="radio" name="ascdesc" value="desc">
                    <label for="ascdesc2">descendente </label>
				  </td>
                </tr>
              </table></td>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor1"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
			 <input type="radio" id="PesquisarPor1" name="PesquisarPor" value="g01_id"> 
             <strong>C&oacute;digo</strong> 
			</td></label>
            <label for="sc_organizacao"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_organizacao" type="checkbox" id="sc_organizacao" value="g01_organizacao as Organiza&ccedil;&atilde;o" checked> 
              <strong>Organiza&ccedil;&atilde;o</strong>
			</td></label>
            <label for="ordenar1"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">			
              <input type="radio" name="ordenar" value="g01_id" id="ordenar1">
              <strong> C&oacute;digo</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor2"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
			  <input name="PesquisarPor" id="PesquisarPor2" type="radio" value="g01_organizacao" checked> 
              <strong>Organiza&ccedil;&atilde;o</strong>
			</td></label>
            <label for="sc_nome"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_nome" type="checkbox" id="sc_nome" value="g01_nome" checked> 
              <strong>Nome</strong>
			</td></label>
              <label for="ordenar2"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input name="ordenar" type="radio" id="ordenar2" value="g01_organizacao" checked>
              <strong> Organiza&ccedil;&atilde;o</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor3"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" id="PesquisarPor3" name="PesquisarPor" value="g01_nome"> 
              <strong>Nome</strong> 
			</td></label>
            <label for="sc_endereco"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_endereco" type="checkbox" id="sc_endereco" value="g01_rua as Endere&ccedil;o" checked> 
              <strong>Endere&ccedil;o</strong>
			</td></label>
            <label for="ordenar3"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
              <input type="radio" id="ordenar3" name="ordenar" value="g01_nome">
              <strong>Nome</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor4"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" id="PesquisarPor4" name="PesquisarPor" value="g01_rua"> 
              <strong>Endere&ccedil;o</strong> 
			</td></label>
            <label for="sc_bairro"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_bairro" type="checkbox" id="sc_bairro" value="g01_bairro"> 
              <strong>Bairro</strong>
			</td></label>
            <label for="ordenar4"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" id="ordenar4" value="g01_rua">
              <strong> Endere&ccedil;o</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor5"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" id="PesquisarPor5" value="g01_bairro"> 
              <strong>Bairro</strong>
			</td></label>
            <label for="sc_cidade"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_cidade" type="checkbox" id="sc_cidade" value="g01_cidade" checked> 
              <strong>Cidade</strong>
			</td></label>
            <label for="ordenar6"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" id="ordenar6" value="g01_bairro">
              <strong> Bairro</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor6"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" id="PesquisarPor6" value="g01_cidade"> 
              <strong>Cidade</strong> 
			</td></label>
            <label for="sc_uf"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_uf" type="checkbox" id="sc_uf" value="g01_uf"> 
              <strong>UF</strong>
			</td></label>
            <label for="ordenar7"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_cidade" id="ordenar7">
              <strong> Cidade</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor7"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_uf" id="PesquisarPor7"> 
              <strong>UF</strong> 
			</td></label>
            <label for="sc_cep"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_cep" type="checkbox" id="sc_cep" value="g01_cep"> 
              <strong>CEP</strong>
			</td></label>
            <label for="ordenar8"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_uf" id="ordenar8">
             <strong> UF</strong>
			</td></label> 
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor8"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_cep" id="PesquisarPor8"> 
              <strong>CEP</strong>
			</td> </label>
            <label for="sc_telefone"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_telefone" type="checkbox" id="sc_telefone" value="g01_telef" checked> 
              <strong>Telefone</strong>
			</td></label>
            <label for="ordenar9"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_cep" id="ordenar9">
              <strong>CEP</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor9"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_telef" id="PesquisarPor9"> 
              <strong>Telefone</strong> 
			</td></label>
            <label for="sc_fax"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_fax" type="checkbox" id="sc_fax" value="g01_fax"> 
              <strong>Fax</strong>
			</td></label>
            <label for="ordenar10"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" id="ordenar10" value="g01_telef">
              <strong>Telefone</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor10"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_fax" id="PesquisarPor10"> 
              <strong>Fax</strong> 
			</td></label>
            <label for="sc_celular"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_celular" type="checkbox" id="sc_celular" value="g01_celular" checked> 
              <strong>Celular</strong>
			</td></label>
            <label for="ordenar11"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_fax" id="ordenar11">
              <strong>Fax</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor11"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_celular" id="PesquisarPor11"> 
              <strong>Celular</strong> 
			</td></label>
            <label for="sc_obs"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_obs" type="checkbox" id="sc_obs" value="g01_obs as Observa&ccedil;&otilde;es"> 
              <strong>Observa&ccedil;&otilde;es</strong>
			</td></label>
            <label for="ordenar12"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
              <input type="radio" name="ordenar" value="g01_celular" id="ordenar12">
              <strong>Celular</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor12"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_obs" id="PesquisarPor12"> 
              <strong>Observa&ccedil;&otilde;es</strong> 
			</td></label>
            <label for="sc_email"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_email" type="checkbox" id="sc_email" value="g01_email"> 
              <strong>Email</strong>
			</td></label>
            <label for="ordenar13"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_obs" id="ordenar13">
              <strong>Observa&ccedil;&otilde;es</strong>
  	    </td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor13"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_email" id="PesquisarPor13"> 
              <strong>Email</strong> 
			</td></label>
            <label for="sc_pagina"><td nowrap bgcolor="#AEAFB3" class="bordas"> 
			  <input name="sc_pagina" type="checkbox" id="sc_pagina" value="g01_site as P&aacute;gina"> 
              <strong>P&aacute;gina</strong>
			</td></label>
            <label for="ordenar14"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
              <input type="radio" name="ordenar" value="g01_email" id="ordenar14">
              <strong> Email</strong>
			</td></label>
          </tr>
          <tr bgcolor="#CFCCD9"> 
            <label for="PesquisarPor14"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas"> 
			  <input type="radio" name="PesquisarPor" value="g01_site" id="PesquisarPor14"> 
              <strong>P&aacute;gina</strong> 
			</td></label>
            <td height="20" nowrap bgcolor="#AEAFB3" class="bordas">&nbsp;</td>
             <label for="ordenar15"><td height="20" nowrap bgcolor="#CDCDCF" class="bordas">
               <input type="radio" name="ordenar" value="g01_site" id="ordenar15">
               <strong> P&aacute;gina</strong>
 	     </label>
          </tr>
        </table>
		<?
		}
		?>
       </td>
  </tr>
</table>
<table>
<tr>
  <td align="center">
    <input name="pesquisar_x"  type="submit" value="Pesquisar"  width="83" height="36" border="0">&nbsp;&nbsp;&nbsp;
    <input name="novoregistro" type="button" value="Nova Pesquisa" width="83" height="36" border="0" onClick="location.href='age3_agenda001.php';return false">
  </td>
</tr>
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>