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

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_dataNula() {
  var F = document.form1;
  
  if(F.data_nula.checked == true) {
    F.data_dia.disabled = true;
        F.data_mes.disabled = true;
        F.data_ano.disabled = true;
        F.data_dia.style.backgroundColor = '#CCCCCC';
        F.data_mes.style.backgroundColor = '#CCCCCC';
        F.data_ano.style.backgroundColor = '#CCCCCC';
  } else {
    F.data_dia.disabled = false;
        F.data_mes.disabled = false;
        F.data_ano.disabled = false;
        F.data_dia.style.backgroundColor = 'white';
        F.data_mes.style.backgroundColor = 'white';
        F.data_ano.style.backgroundColor = 'white';
  }
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
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
<br>
  <form name="form1" method="post" target="consulta">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr align="center" valign="middle"> 
        <td height="40" colspan="2"><u><em><strong>Consulta de Solicita&ccedil;&atilde;o 
          de Endere&ccedil;o j&aacute; Revisadas</strong></em></u></td>
      </tr>
      <tr> 
        <td width="23%" height="20" nowrap><strong>Por Nome:</strong></td>
        <td width="77%"><input name="usr_nome" type="text" id="usr_nome" value="<?=@$usr_nome?>" size="40" maxlength="40"></td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por CgcCpf:</strong></td>
        <td><input name="cgccpf" type="text" id="cgccpf" value="<?=@$cgccpf?>" size="14" maxlength="14"></td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por NumCgm:</strong></td>
        <td><input name="numcgm" type="text" id="numcgm" value="<?=@$numcgm?>" size="10"></td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por Endere&ccedil;o:</strong></td>
        <td><input name="endereco" type="text" id="endereco" value="<?=@$endereco?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td height="20" nowrap><strong>Por Login:</strong></td>
        <td><input name="usr_login" type="text" id="usr_login" value="<?=@$usr_login?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
              <td height="20" nowrap><strong>Por Data da Revis&atilde;o:&nbsp;&nbsp;</strong></td>
        <td nowrap> <input name="data_dia" type="text" id="Idata_dia" onkeyUp="js_digitadata(this.name)" value="<?=@$data_dia?>" size="2" maxlength="2">
          <strong>/ 
          <input name="data_mes" type="text" id="Idata_mes" onkeyUp="js_digitadata(this.name)" value="<?=@$data_mes?>" size="2" maxlength="2">
          / 
          <input name="data_ano" type="text" id="Idata_ano" value="<?=@$data_ano?>" size="4" maxlength="4">
          <input name="data_nula" type="checkbox" id="data_nula" value="1" onClick="js_dataNula()" <? echo @$data_nula == "1"?"checked":"" ?>>
          datas nulas.</strong></td>
      </tr>
      <tr> 
        <td height="20" nowrap>&nbsp;</td>
        <td height="30"> 
          <input type="submit" name="consultar" value="Consultar"> 
                <input type="button" name="Submit2" value="Limpa Campos" onClick="js_limpaCampos()">
        </td>
      </tr>
    </table>
  </form>
  <iframe name="consulta" src="pre3_solicitantigas002.php" width="770" height="200"></iframe>
  </center>
        <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </td>
</tr>
</table>        
        </td>
  </tr>
</table>
<? } else { ?>
<?
db_postmemory($HTTP_POST_VARS);

$query = "SELECT 
w11_revisado as \"Revisado\",
w11_login as \"Login do Usuário\",
w11_nome,
w11_cgccpf,
w11_numcgm,
w11_ender as endereço,
w11_munic as municipio,
w11_uf,
w11_cep,
to_char(w11_cadast,'DD-MM-YYYY') as \"Data do Cadastramento\",
w11_telef as telefone,
w11_ident as \"Carteira de Identidade\",
w11_bairro,
w11_incest as \"Inscrição Estadual\",
w11_telcel as \"Telefone Celular\",
w11_email,
w11_endcon as \"Endereço pra Contato\",
w11_muncon as \"Municipio pra Contato\",
w11_baicon as \"Bairro pra Contato\",
w11_ufcon as \"UF pra Contato\",
w11_cepcon as \"CEP pra Contato\",
w11_telcon as \"Telefone pra Contato\",
w11_celcon as \"Telefone Celular pra Contato\",
w11_emailc as \"Email pra Contato\"
FROM db_cgmatualiza WHERE 2 > 1";

if(empty($usr_nome) && empty($usr_login) && empty($data_dia) && empty($data_mes) && empty($data_ano) && empty($cgccpf) && empty($numcgm) && empty($endereco) && empty($data_nula))
  $query .= " ";
//  $query .= " AND revisado >= (CURRENT_DATE - 10)";
else {
  if(!empty($usr_nome))
    $query .= " AND upper(w11_nome) like upper('$usr_nome%')";
  if(!empty($usr_login))
    $query .= " AND upper(w11_login) like upper('$usr_login%')";
//  if((!empty($data_dia) && !empty($data_mes) && !empty($data_ano)) || !empty($data_nula))
//    $query .= " AND revisado ".($data_nula != "1"?" >= '$data_ano-$data_mes-$data_dia'":" is null");
  if(!empty($cgccpf))
    $query .= " AND upper(w11_cgccpf) like upper('$cgccpf%')";
  if(!empty($numcgm))
    $query .= " AND upper(w11_numcgm) like upper('$numcgm%')";
  if(!empty($endereco))
    $query .= " AND upper(w11_ender) like upper('$endereco%')";
}

db_lov($query,100);
/*
$db_corcabec  = "cyan";
$db_corlinha1 = "#66CCFF" ;
$db_corlinha2 = "#03BCCB" ;
if (!isset($offset))
  db_browse($query,'',10,0,"1&usr_nome=".@$usr_nome."&usr_login=".@$usr_login."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&cgccpf=".@$cgccpf."&numcgm=".@$numcgm."&endereco=".@$endereco);
else
  db_browse($query,'',10,$offset,"1&usr_nome=".@$usr_nome."&usr_login=".@$usr_login."&data_dia=".@$data_dia."&data_mes=".@$data_mes."&data_ano=".@$data_ano."&cgccpf=".@$cgccpf."&numcgm=".@$numcgm."&endereco=".@$endereco);
*/
?>
<? } ?>
</body>
</html>