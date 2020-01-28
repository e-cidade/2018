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
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_reemiteguia(){
  window.open("reciboitbi.php?itbi=" + document.form1.id_itbi.value + "&itbinumpre=","","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<? if(!isset($HTTP_POST_VARS["consultar"])) { ?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
  <form name="form1" method="post" target="consulta">
    <table width="44%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center" valign="middle"> 
        <td height="40" colspan="2"><u><em><strong>Consulta Guia de ITBI liberadas:</strong></em></u></td>
      </tr>
      <tr> 
        <td nowrap><strong>N&uacute;mero da Guia:</strong></td>
        <td nowrap><input name="id_itbi" type="text" id="id_itbi" value="<?=@$id_itbi?>" size="5" maxlength="5"></td>
      </tr>
      <tr> 
        <td width="30%" nowrap><strong>Nome do Comprador:</strong></td>
        <td width="70%" nowrap><input name="nomecomprador" type="text" id="nomecomprador" value="<?=@$nomecomprador?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td nowrap><strong>CgcCpf do Comprador:</strong></td>
        <td nowrap><input name="cgccpfcomprador" type="text" id="cgccpfcomprador" value="<?=@$cgccpfcomprador?>" size="14" maxlength="14"></td>
      </tr>
      <tr> 
        <td nowrap><strong>Endere&ccedil;o do Comprador:</strong></td>
        <td nowrap><input name="enderecocomprador" type="text" id="enderecocomprador" value="<?=@$enderecocomprador?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td nowrap><strong>Munic&iacute;pio do Comprador:</strong></td>
        <td nowrap><input name="municipiocomprador" type="text" id="municipiocomprador" value="<?=@$municipiocomprador?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td nowrap><strong>Data da Solicita&ccedil;&atilde;o:</strong></td>
        <td nowrap>
		  <?
		    include("dbforms/db_funcoes.php");
			db_data("datasolicitacao");
		  ?>
		<!--input name="datasolicitacao_dia" type="text" id="datasolicitacao_dia" value="<?=@$datasolicitacao_dia?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="datasolicitacao_mes" type="text" id="datasolicitacao_mes" value="<?=@$datasolicitacao_mes?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="datasolicitacao_ano" type="text" id="datasolicitacao_ano" value="<?=@$datasolicitacao_ano?>" size="4" maxlength="4"--> 
        </td>
      </tr>
      <tr> 
        <td nowrap><strong>Data da Libera&ccedil;&atilde;o:</strong></td>
        <td nowrap>
		  <?
		  db_data("dataliber");
		  ?>
		  <!--input name="dataliber_dia" type="text" id="dataliber_dia" value="<?=@$dataliber_dia?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="dataliber_mes" type="text" id="dataliber_mes" value="<?=@$dataliber_mes?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="dataliber_ano" type="text" id="dataliber_ano" value="<?=@$dataliber_ano?>" size="4" maxlength="4"-->
		</td>
      </tr>
      <tr> 
        <td nowrap><strong>Login:</strong></td>
        <td nowrap><input name="loginn" type="text" id="loginn" value="<?=@$loginn?>" size="10" maxlength="10"></td>
      </tr>
      <tr>
        <td height="25" nowrap><input name="emiteguia" type="button" id="emiteguia" value="Reemite Guia" onclick="js_reemiteguia()"></td>
        <td height="25" nowrap><input type="submit" name="consultar" value="Procurar"></td>
      </tr>
    </table>
  </form>
   <iframe name="consulta" width="750" height="180"></iframe>
</center>

	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
<? } else { ?>
<?
db_postmemory($HTTP_POST_VARS);

$query = "SELECT loginn as login,id_itbi as \"Número da Guia\",nomecomprador as \"Nome do Comprador\",
                 cgccpfcomprador as \"CgcCpf do Comprador\",enderecocomprador as \"Endereço do Comprador\",
				 municipiocomprador as \"Município do Comprador\",ufcomprador as \"UF do Comprador\",
				 bairrocomprador as \"Bairro do Comprador\", cepcomprador as \"Cep do Comprador\",				 
				 to_char(datasolicitacao,'DD-MM-YYYY') as \"Data da Solicitação\",
				 dataliber as \"Data da Liberação\",				  
                 matricula,
                 areaterreno as \"Área do Terrano\",
                 areaedificada as \"Área Edificada\",
                 tipotransacao as \"Tipo de Transação\",
                 valortransacao as \"Valor da Transação\",
                 caracteristicas,
                 mfrente as \"Medida Frente\",
                 mladodireito as \"Medida Lado Direito\",
                 mladoesquerdo as \"Medida Lado Esquerdo\",
                 mfundos as \"Medida Fundos\",
                 email,
                 obs,
                 liberado,
                 to_char(datavencimento,'DD-MM-YYYY') as \"Data de Vencimento\",
                 aliquota,
                 valoravaliacao as \"Valor da Avaliação\",
                 valorpagamento as \"Valor do Pagamento\",
                 obsliber,
                 numpre				 
          FROM db_itbi
		  WHERE 2 > 1";				  
if(!empty($nomecomprador))
  $query .= " AND upper(nomecomprador) LIKE upper('$nomecomprador%')";
if(!empty($cgccpfcomprador))
  $query .= " AND upper(cgccpfcomprador) LIKE upper('$cgccpfcomprador%')";
if(!empty($enderecocomprador))
  $query .= " AND upper(enderecocomprador) LIKE upper('$enderecocomprador%')";
if(!empty($municipiocomprador))
  $query .= " AND upper(municipiocomprador) LIKE upper('$municipiocomprador%')";
if(!empty($datasolicitacao_dia) && !empty($datasolicitacao_mes) && !empty($datasolicitacao_ano))
  $query .= " AND datasolicitacao > '$datasolicitacao_ano-$datasolicitacao_mes-$datasolicitacao_dia'";
if(!empty($dataliber_dia) && !empty($dataliber_mes) && !empty($dataliber_ano))
  $query .= " AND dataliber > '$dataliber_ano-$dataliber_mes-$dataliber_ano'";
if(!empty($loginn))
  $query .= " AND upper(loginn) LIKE upper('$loginn%')";

/*
if (!isset($offset))
  db_browse($query,'',2,0,"1&nomecomprador=".@$nomecomprador."&cgccpfcomprador=".@$cgccpfcomprador."&enderecocomprador=".@$enderecocomprador."&municipiocomprador=".@$municipiocomprador."&datasolicitacao_dia=".@$datasolicitacao_dia."&datasolicitacao_mes=".@$datasolicitacao_mes."&datasolicitacao_ano=".@$datasolicitacao_ano."&dataliber_dia=".@$dataliber_dia."&dataliber_mes=".@$dataliber_mes."&dataliber_ano=".@$dataliber_ano."&loginn=".@$loginn);
else
  db_browse($query,'',2,$offset,"1&nomecomprador=".@$nomecomprador."&cgccpfcomprador=".@$cgccpfcomprador."&enderecocomprador=".@$enderecocomprador."&municipiocomprador=".@$municipiocomprador."&datasolicitacao_dia=".@$datasolicitacao_dia."&datasolicitacao_mes=".@$datasolicitacao_mes."&datasolicitacao_ano=".@$datasolicitacao_ano."&dataliber_dia=".@$dataliber_dia."&dataliber_mes=".@$dataliber_mes."&dataliber_ano=".@$dataliber_ano."&loginn=".@$loginn);
*/

$filtro = "nomecomprador=".@$nomecomprador."&cgccpfcomprador=".@$cgccpfcomprador."&enderecocomprador=".@$enderecocomprador."&municipiocomprador=".@$municipiocomprador."&datasolicitacao_dia=".@$datasolicitacao_dia."&datasolicitacao_mes=".@$datasolicitacao_mes."&datasolicitacao_ano=".@$datasolicitacao_ano."&dataliber_dia=".@$dataliber_dia."&dataliber_mes=".@$dataliber_mes."&dataliber_ano=".@$dataliber_ano."&loginn=".@$loginn;
db_lov($query,200,"",$filtro);
?>

<? } ?>
</body>
</html>