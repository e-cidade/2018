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
include("dbforms/db_funcoes.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("codtrib");
$rotulocampo->label("tribinst");
$rotulocampo->label("nomeinstabrev");

$db_opcao = 1;

if (isset($alterar)) {
  $db_opcao = 2;
}

if (isset($excluir)) {
  $db_opcao = 3;
}

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if (isset($retorno)) {
  $sql = "select *,to_char(dtcont,'DD') as dtcont_dia,to_char(dtcont,'MM') as dtcont_mes,to_char(dtcont,'YYYY') as dtcont_ano from db_config where codigo = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}

//////////INCLUIR/////////////
if (isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $dtcont = "'".$dtcont_ano."-".$dtcont_mes."-".$dtcont_dia."'";
  if (!checkdate($dtcont_mes,$dtcont_dia,$dtcont_ano)) {
    db_erro("Campo 'Data da Contabilidade' está inválido: $dtcont");
  }
  $tx_banc = $tx_banc == ""?"null":$tx_banc;
  $ident = $ident == ""?"null":$ident;
  
  db_getfile("db_figura",$dbh_figura);
  db_getfile("db_logo",$dbh_logo);
  $result = pg_exec("select max(codigo) + 1 from db_config");
  $codigo = pg_result($result,0,0);
  $codigo = $codigo==""?"1":$codigo;
	$insert = "insert into db_config
	                (codigo,
									 nomeinst,
									 nomeinstabrev,
									 ender,
									 munic,
									 uf,
									 telef,
									 email,
									 ident,
									 tx_banc,
									 numbanco,
									 url,
									 logo,
									 figura,
									 dtcont,
									 codtrib,
									 diario,
									 tribinst,
									 pref,
									 vicepref,
									 numero,
									 fax,
									 cgc,
									 cep,
									 bairro,
									 numcgm.
									 db21_codigomunicipoestado
									 )
									values
							(
					$codigo,
					'$nomeinst',
					'$nomeinstabrev',
					'$ender',
					'$munic',
					'$uf',
					'$telef',
					'$email',
					$ident,
					$tx_banc,
					'$numbanco',
					'$url',
					'$dbh_logo',
					'$dbh_figura',
					$dtcont,
					'$codtrib',
					$tribinst,
					$diario,
					'$pref',
					'$vicepref'
					'$fax',
					'$cgc',
					'$cep',
					'$bairro',
					'$numcgm',
					'$numero',
					'$db21_codigomunicipoestado'
					)";
					pg_exec($insert) or die("Erro(23) inserindo em db_config: ".pg_errormessage());
					db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
				exit;
				////////////////ALTERAR////////////////
} else if (isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  
  $dtcont = "'".$dtcont_ano."-".$dtcont_mes."-".$dtcont_dia."'";
  if (!checkdate($dtcont_mes,$dtcont_dia,$dtcont_ano)) {
    db_erro("Campo 'Data da Contabilidade' está inválido: $dtcont");
  }
  $tx_banc = $tx_banc == ""?"null":$tx_banc;
  $ident = $ident == ""?"null":$ident;
  
  $result = pg_exec("select figura,logo from db_config where codigo = $codigo ");
  pg_exec("update db_config set
nomeinst = '$nomeinst',
nomeinstabrev = '$nomeinstabrev',
ender = '$ender',
munic = '$munic',
uf = '$uf',
telef = '$telef',
email = '$email',
ident = $ident,
tx_banc = $tx_banc,
numbanco = '$numbanco',
url = '$url',
dtcont = $dtcont,
codtrib = '$codtrib',
tribinst = $tribinst,
diario = $diario,
pref = '$pref',
vicepref = '$vicepref',
numero = '$numero',
fax = '$fax',
cgc = '$cgc',
cep = '$cep',
db21_codigomunicipoestado = '$db21_codigomunicipoestado',
bairro = '$bairro',
numcgm = $numcgm,
logo = '".db_getfile("db_logo",$dbh_logo,pg_result($result,0,1))."',
figura = '".db_getfile("db_figura",$dbh_figura,pg_result($result,0,0))."'
where codigo = $codigo") or die("Erro(38) alterando db_config: ".pg_errormessage());
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;
  ////////////////EXCLUIR//////////////
} else if (isset($HTTP_POST_VARS["excluir"])) {
  $result = pg_exec("select figura,logo from db_config where codigo = ".$HTTP_POST_VARS["codigo"]);
  pg_exec("delete from db_config where codigo = ".$HTTP_POST_VARS["codigo"]) or die("Erro(43) excluindo db_usuarios: ".pg_errormessage());
  system("rm -f $DB_FILES/".pg_result($result,0,0));
  system("rm -f $DB_FILES/".pg_result($result,0,1));
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<script>
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao != 'procurar') {  
  	if(obj.nomeinst.value == "") {
      alert("Campo nome é obrigatório");
	  obj.nomeinst.focus();
	  return false;
    }

    if (obj.nomeinstabrev.value == ""){
         alert("Campo nomeinstabrev é obrigatório");
         obj.nomeinstabrev.focus();
         return false;
    }

	if(obj.ender.value == "") {
      alert("Campo endereço é obrigatório");
	  obj.ender.focus();
	  return false;
    }
	if(obj.munic.value == "") {
      alert("Campo município é obrigatório");
	  obj.munic.focus();
	  return false;
    }
	if(obj.uf.value == "") {
      alert("Campo UF é obrigatório");
	  obj.uf.focus();
	  return false;
    }	
    if(obj.dtcont_dia.value == "" || obj.dtcont_mes.value == "" || obj.dtcont_ano.value == "") {
      alert("Data inválida, campo obrigatório");
	  obj.dtcont_dia.focus();
	  return false;
    }
    if (obj.tribinst.value == "") {
         alert("Tributação inválida");
	 obj.tribinst.value = 0;
	 obj.tribinst.focus();
	 obj.tribinst.select();
	 return false;
    }
	if(obj.diario.value == "") {
      alert("Campo Diario é obrigatório");
	  obj.diario.focus();
	  return false;
    }		
	if(obj.pref.value == "") {
      alert("Campo 'Nome do Prefeito' é obrigatório");
	  obj.pref.focus();
	  return false;
    }	
	if(obj.vicepref.value == "") {
    
      alert("Campo 'Nome do Vice - prefeito' é obrigatório");
	  obj.vicepref.focus();
	  return false;
    }
    if (obj.db21_codigomunicipoestado.value == "") {
  
     alert("Campo Codigo do municipio é obrigatorio");
     obj.db21_codigomunicipoestado.focus();
     return false
    }	
  }
  
  return true;
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.nomeinst.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
        <?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
        $sql = "SELECT  codigo as db_codigo,nomeinst,ender as endereço,telef as telefone,munic as município,uf,figura,
                        db21_codigomunicipoestado
                FROM db_config
		  	    WHERE upper(nomeinst) like upper('".$HTTP_POST_VARS["nomeinst"]."%')
			    ORDER BY nomeinst";
        db_lov($sql,15,"con1_cadinst.php"); 
      } else {
    ?>
        <form name="form1" method="post" enctype="multipart/form-data" onSubmit="return js_submeter(this)">
          <input type="hidden" name="codigo" value="<?=@$codigo?>">
          <table width="487" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="107" nowrap><strong>Nome:</strong></td>
              <td width="293" nowrap><input name="nomeinst" onBlur="js_ValidaCamposText(this,3)" type="text" id="nomeinst" value="<?=@$nomeinst?>" size="50" maxlength="80"></td>
            </tr>
            <tr> 
              <td nowrap><?=@$Lnomeinstabrev?></td>
              <td nowrap><input name="nomeinstabrev" onBlur="js_ValidaCamposText(this,3)" type="text" id="nomeinstabrev" value="<?=@$nomeinstabrev?>" size="30" maxlength="20">
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>CNPJ:</strong></td>
              <td nowrap><input name="cgc" type="text" id="cgc" value="<?=@$cgc?>" size="20" maxlength="14">
              </td>
            </tr>
           <tr> 
              <td nowrap><strong>Numcgm:</strong></td>
              <td nowrap><input name="numcgm" type="text" id="numcgm" value="<?=@$numcgm?>" size="10" maxlength="10"></td>
            </tr>

            <tr> 
              <td nowrap><strong>Endere&ccedil;o:</strong></td>
              <td nowrap><input name="ender" type="text" id="ender" value="<?=@$ender?>" size="50" maxlength="80">
              <input name="numero" type="text" id="numero" value="<?=@$numero?>" size="10" maxlength="10">
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>Munic&iacute;pio:</strong></td>
              <td nowrap><input name="munic" type="text" id="munic" value="<?=@$munic?>" size="40" maxlength="40">
                UF: 
                <input name="uf" onBlur="js_ValidaCamposText(this,2)" type="text" id="uf" value="<?=@$uf?>" size="2" maxlength="2">
                CEP: 
                <input name="cep" type="text" id="cep" value="<?=@$cep?>" size="12" maxlength="8"></td>
            </tr>
           <tr> 
              <td nowrap><strong>Bairro:</strong></td>
              <td nowrap><input name="bairro" type="text" id="bairro" value="<?=@$bairro?>" size="20" maxlength="17"></td>
            </tr>
 
           <tr> 
              <td nowrap><strong>Telefone:</strong></td>
              <td nowrap><input name="telef" type="text" id="telef" value="<?=@$telef?>" size="50" maxlength="11"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Fax:</strong></td>
              <td nowrap><input name="fax" type="text" id="fax" value="<?=@$fax?>" size="50" maxlength="11"></td>
            </tr>
            <tr> 
              <td nowrap><strong>E-mail:</strong></td>
              <td nowrap><input name="email" type="text" id="email" value="<?=@$email?>" size="50" maxlength="200"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Site:</strong></td>
              <td nowrap><input name="url" type="text" id="url" value="<?=@$url?>" size="50" maxlength="200"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Num. Banco:</strong></td>
              <td nowrap><input name="numbanco" onBlur="js_ValidaCamposText(this,1)" type="text" id="numbanco" value="<?=@$numbanco?>" size="10" maxlength="10"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Taxa Banc&aacute;ria:</strong></td>
              <td nowrap><input name="tx_banc" type="text" id="tx_banc" onBlur="js_ValidaCamposText(this,4)" value="<?=@$tx_banc?>" size="5" maxlength="5"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Logo:</strong></td>
              <td nowrap> 
                <?=db_file("logo",50,100,@$logo)?>
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>Tipo:</strong></td>
              <td nowrap><input name="ident" type="text" id="ident" onBlur="js_ValidaCamposText(this,1)" value="<?=@$ident?>" size="2" maxlength="2"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Figura:</strong></td>
              <td nowrap> 
                <?=db_file("figura",50,100,@$figura)?>
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>Data da Contabilidade:</strong></td>
              <td nowrap> 
                <? db_data("dtcont",@$dtcont_dia,@$dtcont_mes,@$dtcont_ano) ?>
              </td>
            </tr>
            <tr> 
              <td nowrap title="<?=$Tcodtrib?>"><?=$Lcodtrib?></td>
              <td nowrap> 
                <? db_input("codtrib",4,$Icodtrib,"true","text",$db_opcao) ?>
              </td>
              <td nowrap title="<?=$Ttribinst?>"><?=$Ltribinst?></td>
              <td nowrap> 
                <? db_input("tribinst",10,$Itribinst,"true","text",$db_opcao) ?>
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>Di&aacute;rio:</strong></td>
              <td nowrap><input name="diario" type="text" id="diario" onBlur="js_ValidaCamposText(this,1)" value="<?=@$diario?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Nome do Prefeito:</strong></td>
              <td nowrap><input name="pref" type="text" id="pref" value="<?=@$pref?>" size="40" maxlength="40"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Nome do Vice - Prefeito:</strong></td>
              <td nowrap><input name="vicepref" type="text" id="vicepref" value="<?=@$vicepref?>" size="40" maxlength="40"></td>
            </tr>
            <tr> 
              <td nowrap><strong>Código do Municipio:</strong></td>
              <td nowrap><input name="db21_codigomunicipoestado" type="text" id="db21_codigomunicipoestado"
                  value="<?=@$db21_codigomunicipoestado?>" size="40" maxlength="40"></td>
            </tr>
            <tr> 
              <td nowrap>&nbsp;</td>
              <td nowrap> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
                &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar" value="Procurar"></td>
            </tr>
          </table>
        </form>
        <?
		}
		?>
      </center>
    </td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>