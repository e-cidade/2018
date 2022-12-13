<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


include("classes/db_confsite_classe.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_conecta.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
			  if(@$nome!="") {
			    $opcao = "CNPJ:";
                            $retorna = "onSubmit='return js_verificaCGCCPF(this.cnpj,this.cpf)'";		 
                            $opcao2 = "cnpj";
                            $funcao = "onKeyDown='FormataCNPJ(this,event)'";
			    $max = "18";
		            $size = "18";
			  }elseif(@$matric!="") {
			    $opcao = @$matric.":";
                            $opcao2 = "matric";
			    $retorna = "onSubmit='return js_verificamatricula()'"; 
                            $max = "10";
			    $size = "10";
			 
                          }elseif(@$inscr!=""){
			    $opcao = @$inscr.":";
			    $opcao2 = "inscr";
			    $retorna = "onSubmit='return js_verificamatricula()'"; 
                            $max = "10";
			    $max = "6";
			    $size = "8";
			  }elseif(@$verifica!=""){
			    $opcao = @$verifica.":";
			    $max = "50";
			    $size = "50";
			    $opcao2 = "verifica";
			  }

db_mensagem("certidao1_cab","certidao1_rod");
mens_help();
$dblink="certidao.php";      
postmemory($HTTP_POST_VARS);
$clconfsite = new cl_confsite;
$result = $clconfsite->sql_record($clconfsite->sql_query("1","*","",""));
db_fieldsmemory($result,0);
?>
<html><!-- InstanceBegin template="/Templates/principal.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title><?=$w01_titulo?></title>
<!-- InstanceEndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceParam name="onload" type="text" value="" --> 
<script language="JavaScript" src="scripts/db_script.js">
function testa() {
  var numero = new Number(document.form1.<?=@$opcao2?>.value);
  if(isNaN(numero)){
    alert ("este campo deve ser preenchido somente com números");
    document.form1.<?=$opcao2?>.focus();
  }
}
function js_verificamatricula() {
  if (document.form1.<?=$opcao2?>.value == "" || isNaN(document.form1.<?=$opcao2?>.value)){
    alert("Codigo de <?=$opcao?> Inválido.");
    document.form1.<?=$opcao2?>.focus();
    document.form1.<?=$opcao2?>.select();
    return false;
  }	
}
</script>
<style type="text/css">
<?
echo"
.bordas {
	border: $w01_bordamenu $w01_estilomenu;
	border-color: $w01_corbordamenu;
	background-color: $w01_corfundomenu;
	cursor: hand;	
}
.linksmenu {
	font-size: $w01_tamfontemenu;
	font-family: $w01_fontemenu;
	font-weight: $w01_wfontemenu;
	color: $w01_corfontemenu;
	text-decoration: none;
}
.links {
	font-size: $w01_tamfontesite;
	font-family: $w01_fontesite;
	font-weight: $w01_wfontesite;
	color: $w01_corfontesite;
	text-decoration: none;
}
a.links:hover {
	font-size: $w01_tamfonteativo;
	font-family: $w01_fonteativo;
	font-weight: $w01_wfonteativo;
	color: $w01_corfonteativo;
	text-decoration: underline;
}
a.links:visited {
	font-size: $w01_tamfontesite;
	font-family: $w01_fontesite;
	font-weight: $w01_wfontesite;
	color: $w01_corfontesite;
	text-decoration: none;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;	
}
input {
	font-family: $w01_fonteinput;
	font-size: $w01_tamfonteinput;
	color: $w01_corfonteinput;
	background-color: $w01_corfundoinput;
	border: $w01_bordainput $w01_estiloinput $w01_corbordainput;
}";
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" align="left" valign="top"><img src="imagens/cabecalho.jpg"></td>
</tr>
      </table></td>
  </tr>
  <tr>
      <td class="bordas" nowrap>         
	   &nbsp;<a href="index.php" class="links">Principal &gt;</a> 
       <!-- InstanceBeginEditable name="caminho" -->	           
		
	   <!-- InstanceEndEditable --> 
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	  <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
      <tr>
            <td width="90" align="left" valign="top"> 
              <!-- MENU -->
              <img src="imagens/linha.gif" width="90" height="1" border="0"> 
              <table width="97%" cellpadding="0" cellspacing="0" border="0">
          <?            
		  	$result_dtw = db_query("SELECT * FROM db_menupref WHERE m_ativo = '1'");
	        $numrows_dtw = pg_numrows($result_dtw);
            for($i = 0;$i < $numrows_dtw;$i++) {
              $arquivo = pg_result($result_dtw,$i,"m_arquivo");
	          $nome = substr($arquivo,0,strlen($arquivo) - 4);
	          $imgs = split(";",pg_result($result_dtw,$i,"m_imgs"));
	          $descricao = pg_result($result_dtw,$i,"m_descricao");
              ?>
              <tr> 			    
                <td id="coluna<?=$i?>" align="center" height="25" class="bordas" onClick="js_link('<?=$arquivo?>')" onMouseOut="js_restaurafundo(this,'<?=$w01_corfundomenu?>')" onMouseOver="js_trocafundo(this,'<?=$w01_corfundomenuativo?>')">
				  <a class="linksmenu" href="<?=$arquivo?>" >
				    <?=$descricao?>
				  </a>
				  </td>				
              </tr>
              <?
		    }
          ?>
          </table>
		</td>
            <td align="left" valign="top"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="60" align="<?=$DB_align1?>">
                    <?=$DB_mens1?>
                  </td>
                </tr>
                <tr align="center">
                 <td>

	<form name="form1" method="post" action="certidao2.php" <?=@$retorna?>>
        <table width="100%" border="0">
          <tr> 
            <td width="42%" align="right"> 
          <?
		  echo $opcao;
		  ?>
            </td>
            <td width="58%" align="left"><input name="<?=@$opcao2?>" type="text" value=""  size="<?=$size?>" maxlength="<?=$max?>" <?=@$funcao?>></td>
          </tr>
          <tr>
            <td align="right">
              <? 
			  if(@$nome!="") {
			    $name = "cpf";
                            $opcao1 = "CPF:";
			    echo $opcao1."
			    </td>
				  <td align=\"left\"><input name=\"$name\" type=\"text\" value=\"\" onKeyDown=\"FormataCPF(this,event)\" size=\"14\" maxlength=\"14\"> 
                </td>";
			  }else{
			    echo "</td>";              
			  }
			  ?>
            </tr>
          <tr> 
            <td align="center">&nbsp; </td>
            <td align="left"><input type="submit" name="Submit" value="Emitir certid&atilde;o"></td>
          </tr>
        </table>
      </form>
                  </td>      
                </tr>   
                <tr> 
                  <td height="60" align="<?=$DB_align2?>">
                    <?=$DB_mens2?>
                  </td>
                </tr>
              </table>
           </td>
         </tr>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>