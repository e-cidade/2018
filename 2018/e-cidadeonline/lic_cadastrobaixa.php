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
include("classes/db_licbaixa_classe.php");
postmemory($HTTP_POST_VARS);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

?>
<html>
<head>
	<title>Licitações</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
	<script language="JavaScript" src="scripts/db_script.js"></script>
	<script>
	</script>
	<style type="text/css">
		<?
    db_estilosite()
    ?>
	</style>
</head>
<body >
<table width="80%" border="0" align= "center"  Cellspacing="10" class="texto">
	<form name="form1" method="post" action="">

		<input type=hidden name=edital value="<?=@$edital?>" >
		<tr><td>&nbsp;</td></tr>
		<tr><td >&nbsp;</td></tr>
		<tr>
			<td colspan ="2" align= "center" > <b>Efetue cadastro para receber informações referentes ao edital solicitado</b>
			</td>
		</tr>
		<tr>
			<td width="20%">Nome/Razão Social
			</td>
			<td><input name="nome" type= "text" size ="70" >
			</td>
		</tr>
		<tr>
			<td width="20%">CPF/CNPJ
			</td>
			<td><input name="cnpj" type= "text" size ="70"
								 onChange='js_teclas(event);'
								 onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'>
			</td>
		</tr>
		<tr>
			<td width="20%">Email
			</td>
			<td><input name="email2" type= "text" size ="70">
			</td>
		</tr>
		<tr>
			<td width="20%">Telefone
			</td>
			<td><input name="fone" type= "text" size ="30" >
			</td>
		</tr>
		<tr>
			<td width="20%">Cidade
			</td>
			<td><input name="cidade" type= "text" size ="30">
			</td>
		</tr>
		<tr>
			<td width="20%">Endereço
			</td>
			<td><input name="endereco" type= "text" size ="70">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input name="incluir" type= "submit" value="Incluir">
			</td>
		</tr>
		<tr>
			<td colspan = "2"> mensagem
			</td>
		</tr>

		<?
		//echo "$edital";
		$data= date("Y-m-d");
		$hora = date("H:i");
		$ip= $_SERVER["REMOTE_ADDR"];
		$cl_licbaixa= new cl_licbaixa;

		//echo "seq= $edital";
		if (isset($incluir)){

			db_logs("","",0,"Baixa do Edital {$edital}");

			$sqlerro = false;
			$cl_licbaixa->l28_nome     = $nome;
			$cl_licbaixa->l28_email    = $email2;
			$cl_licbaixa->l28_cnpj     = ereg_replace("[./-]","",$cnpj);
			$cl_licbaixa->l28_endereco = $endereco;
			$cl_licbaixa->l28_cidade   = $cidade;
			$cl_licbaixa->l28_fone     = $fone;
			$cl_licbaixa->l28_data     = date("Y-m-d");
			$cl_licbaixa->l28_hora     = date("H:i");
			$cl_licbaixa->l28_ip       = $_SERVER["REMOTE_ADDR"];
			$cl_licbaixa->l28_liclicita= $edital;
			$cl_licbaixa->incluir(null);

			if ($cl_licbaixa->erro_status == 0) {
				$sqlerro = true;
				//echo"entrei no erro do cl_licbaixa.....";
				//die($cl_licbaixa->erro_sql);
				$erro_sql = $cl_licbaixa->erro_sql;
				db_msgbox($erro_sql);
			}
			if ($sqlerro==false){
				echo"<script>location.href='lic_baixaedital.php?lic=$edital'</script>";
			}

		}
		?>
	</form>
</table>
</html>