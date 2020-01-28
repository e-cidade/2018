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
include("classes/db_db_tipo_classe.php");

db_postmemory($HTTP_POST_VARS);
$cldb_tipo = new cl_db_tipo;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_limpaCampos() {
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == 'text' || F.elements[i].type == 'select-one')
	  F.elements[i].value = '';
  }
  F.email.focus();
}
function js_submeter() {
  var F = document.form1;
  var verf = 0;
  
  if(F.data_dia.value != '' || F.data_mes.value != '' || F.data_ano.value != '') {
    if(F.data_dia.value == '' || F.data_mes.value == '' || F.data_ano.value == '') {
	  alert("Esta data é inválida");
	  js_limpaCampos();
	  F.data_dia.focus();
	} else
	  verf = 1;	
  } else
    verf = 2;
	
  if(verf > 0)
    F.submit();
}
function js_emite(chave){
  qry  = 'idOuvidoria='+chave;
  jan  = window.open('pre2_ouvidoria002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
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

<br>
<br>
<center>
<form name="form1" action="" method="post">
  <center>
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" colspan="2"><u><em><strong>Consulta de Solicita&ccedil;&atilde;o 
          j&aacute; Revisadas da Ouvidoria</strong></em></u></td>
      </tr>
      <tr> 
        <td width="23%" height="20" nowrap><strong>Por Email:</strong></td>
        <td width="77%"><input name="email" type="text" id="email" value="<?=@$email?>" size="50" maxlength="50"></td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por Categoria:</strong></td>
        <td> 
            <select name="po01_tipo" id="po01_tipo">
              <option value="">-</option>
            <?
              $results = pg_fetch_all($cldb_tipo->sql_record($cldb_tipo->sql_query("","*","","")));
              foreach ($results as $result) {
            ?>
              <option value="<?=@$result['w03_codtipo']?>" <?=@($result['w03_codtipo']==$po01_tipo?"selected='selected'":"")?>><?=@$result['w03_tipo']?></option>
            <?}?>
            </select>
          </td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por Mensagem:</strong></td>
        <td><input name="comentario" type="text" id="comentario" value="<?=@$comentario?>" size="50"></td>
      </tr>
      <tr>
        <td height="20" nowrap><strong>Por Resposta:</strong></td>
        <td><input name="texto" type="text" id="texto" value="<?=@$texto?>" size="50"></td>
      </tr>
      <tr> 
        <td height="20" nowrap><strong>Por Matr&iacute;cula:</strong></td>
        <td><input name="usr_login" type="text" id="usr_login" value="<?=@$usr_login?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td height="20" nowrap><strong>Por Data da Resposta:</strong></td>
        <td nowrap> <input name="data_dia" type="text" id="Idata_dia" value="<?=@$data_dia?>" size="2" maxlength="2">
          <strong>/ 
          <input name="data_mes" type="text" id="Idata_mes" value="<?=@$data_mes?>" size="2" maxlength="2">
          / 
          <input name="data_ano" type="text" id="Idata_ano" value="<?=@$data_ano?>" size="4" maxlength="4">
          </strong>
        </td>
      </tr>
      <tr> 
        <td height="20" nowrap>&nbsp;</td>
        <td height="30"> 
          <input type="button" name="Submit" value="Consultar" onClick="js_submeter()"> 
		<input type="button" name="Submit2" value="Limpa Campos" onClick="js_limpaCampos()">
        </td>
      </tr>
    </table>
  </center>
</form>
</center>
<?
if(isset($Submit) || count($HTTP_POST_VARS)>0){
	$query = "SELECT po01_sequencial as sequencial, po01_email as email, 
									 w03_tipo as dl_categoria, 
									 to_char(po01_data,'DD-MM-YYYY')::varchar as dl_data_da_Solicitação, 
                   to_char(po01_revisado,'DD-MM-YYYY')::varchar as dl_data_da_Resposta,
                   po01_id_usuario::varchar as dl_Respondido_por
            FROM db_ouvidoria 
            	INNER JOIN db_tipo ON db_tipo.w03_codtipo = db_ouvidoria.po01_tipo
            WHERE po01_revisado IS NOT NULL";

  if(empty($email) && empty($po01_tipo) && empty($data_dia) && empty($data_mes) && empty($data_ano) && empty(
     $comentario) && empty($texto) && empty($usr_login))
    $query .= " ";
  else {
  	if(!empty($email))
      $query .= " AND upper(po01_email) like upper('$email%')";
    if(!empty($po01_tipo))
      $query .= " AND po01_tipo = $po01_tipo";
    if((!empty($data_dia) && !empty($data_mes) && !empty($data_ano)) || !empty($data_nula))
      $query .= " AND po01_revisado = '$data_ano-$data_mes-$data_dia'";
    if(!empty($comentario))
      $query .= " AND upper(po01_mensagem) like upper('$comentario%')";
    if(!empty($texto))
      $query .= " AND upper(po01_texto) like upper('$texto%')";
    if(!empty($usr_login))
      $query .= " AND po01_rhpessoal = '$usr_login'";
  }
  $query .= " ORDER BY po01_revisado DESC, po01_data DESC";
}db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<center>
<? db_lovrot($query,10,"()","","js_emite|sequencial",null,"NoMe");?>
</center>

</body>
</html>