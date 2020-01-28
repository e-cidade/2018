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


include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_conecta.php");
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
			  if(@$nome!="") {
			    $opcao = "CNPJ:";
			    $funcao = "onKeyDown='FormataCNPJ(this,event)'";
			    $max = "18";
				$size = "18";
			  }elseif(@$matric!="") {
			    $opcao = @$matric.":";
                            $opcao2 = "matric";
				$max = "10";
				$size = "10";
			  }elseif(@$inscr!=""){
			    $opcao = @$inscr.":";
			    $opcao2 = "inscr";
				$max = "6";
				$size = "8";
			  }else{
			    $opcao = @$verifica.":";
			  }
postmemory($HTTP_POST_VARS);





?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_restaurafundo(obj) {
  document.getElementById(obj.id).style.backgroundColor = '#00436E';
}
function js_trocafundo(obj){
  document.getElementById(obj.id).style.backgroundColor = '#0065A8';
}
function js_link(arq) {
  location.href = arq;
}
function testa() {
alert(document.form1.<?=@$opcao2?>.value);
  var numero = new Number(document.form1.<?=@$opcao2?>.value);
  if(isNaN(numero)){
    alert ("este campo deve ser preenchido somente com números");
    document.form1.<?=$opcao2?>.focus();
  }else{	
    alert ("denis");
  }
}
</script>
<style type="text/css">
.bordas {
	border: 1px solid white;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #666666;
	background-color: #00436E;
	cursor: hand;	
}
.linksmenu {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
.links {
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
a.links:hover {
	font-size: 12px;
	font-weight: bold;
	color: #CCCCCC;
	text-decoration: underline;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;	
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
	background-color: #FFFFFF;
	height: 16px;
	border: 1px solid #00436E;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#0F6BAA" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="certidao2.php">

<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="#0F6BAA">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="33%" align="left" valign="top"><img src="imagens/topo1_O.gif" width="256" height="81"></td>
          <td width="21%" align="left" valign="top"><img src="imagens/topo2_O.gif" width="163" height="81"></td>
            <td width="46%" align="left" valign="top"><img src="imagens/topo3.jpg" width="347" height="81"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
      <td class="bordas" nowrap>         
	   &nbsp;<a href="index.php" class="links">Principal &gt;</a> 
		&nbsp;<font class="links">Contribuinte &gt;</font>
      </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	  <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
      <tr>
            <td width="90" align="left" valign="top"> 
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
                <td id="coluna<?=$i?>" align="center" height="25" class="bordas" onClick="js_link('<?=$arquivo?>')" onMouseOut="js_restaurafundo(this)" onMouseOver="js_trocafundo(this)">
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

        <table width="100%" border="0">
          <tr> 
            <td width="42%" align="right"> 
          <?
		  echo $opcao;
		  ?>
            </td>
            <td width="58%" align="left"><input name="<?=@$opcao2?>" type="text" value=""  size="<?=$size?>" maxlength="<?=$max?>" onBlur="testa()" <?=@$funcao?>></td>
          </tr>
          <tr>
            <td align="right">
              <? 
			  if(@$nome!="") {
			    $opcao1 = "CPF:";
			    echo $opcao1."
			    </td>
				  <td align=\"left\"><input name=\"<?=$opcao1?>\" type=\"text\" value=\"\" onKeyDown=\"FormataCPF(this,event)\" size=\"14\" maxlength=\"14\"> 
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