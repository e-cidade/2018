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

include("libs/db_sql.php");
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
session_start();
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'certidao.php'
                   ORDER BY m_descricao
                  ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  /*
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
  */
}
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

db_mensagem("certidaomatric_cab","certidaomatric_rod");
mens_help();
$dblink="certidao.php";      
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("certidao.php");
function js_erromatric(matric){
  alert('Matrícula '+matric+' inválida');
}

function testa() {
  var numero = new Number(document.form1.<?=@$opcao2?>.value);
  if(isNaN(numero)){
    alert ("Este campo deve ser preenchido somente com números");
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
db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<!--<form name="form1" method="post" onSubmit="window.open('certidao2.php','cert','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height=500,width=700')">-->
<?mens_div();?>
<center>
<br><br><br>
        <form name="form1" method="post" action="certidao2.php" <?=@$retorna?>>
        <table width="100%" border="0" class="texto">
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
                            $name = "cpf";
                            $opcao1 = "CPF:";
                            echo $opcao1."
                            </td>
                                  <td align=\"left\"><input name=\"$name\" type=\"text\" value=\"\" onKeyDown=\"FormataCPF(this,event)\" size=\"14\" maxlength=\"14\"> 
                </td>";
                          ?>
</tr>
<tr>
<td align="right">
              <? 
                            $name = "cnpj";
                            $op = "CNPJ:";
                            echo $op."
                            </td>
                            <td align=\"left\"><input name=\"$name\" type=\"text\" value=\"\" onKeyDown=\"FormataCNPJ(this,event)\" size=\"18\" maxlength=\"18\">
                </td>";
                          ?>
            </tr>
          <tr> 
            <td align="center">&nbsp; </td>
            <td align="left"><input class="botao" type="submit" name="Submit" value="Emitir certid&atilde;o"></td>
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
</center>
<!--</form>-->
</body>
</html>