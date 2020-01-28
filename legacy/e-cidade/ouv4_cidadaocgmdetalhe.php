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
include("classes/db_cidadao_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_GET_VARS);
$clcidadao = new cl_cidadao();

$sQueryCidadaoCgm  = " select ov02_nome,			";
$sQueryCidadaoCgm .= " 				ov02_endereco,	";
$sQueryCidadaoCgm .= " 				ov02_numero,		";
$sQueryCidadaoCgm .= " 				ov02_munic,			";
$sQueryCidadaoCgm .= "        ov02_ident,     ";
$sQueryCidadaoCgm .= " 				ov02_bairro,		";
$sQueryCidadaoCgm .= " 				ov02_cep,				";
$sQueryCidadaoCgm .= " 				ov02_cnpjcpf,		";
$sQueryCidadaoCgm .= " 				ov02_compl,			";
$sQueryCidadaoCgm .= " 				ov02_uf,				";
$sQueryCidadaoCgm .= " 				ov07_ddd,				";
$sQueryCidadaoCgm .= " 				ov07_numero,		";
$sQueryCidadaoCgm .= " 				ov07_ramal,			";
$sQueryCidadaoCgm .= "        ov08_email,      ";
$sQueryCidadaoCgm .= "        ov02_seq        ";
//$sQueryCidadaoCgm .= " 				z01_numcgm,			";
//$sQueryCidadaoCgm .= " 				z01_nome,				";
//$sQueryCidadaoCgm .= " 				z01_ender,			";
//$sQueryCidadaoCgm .= " 				z01_numero,			";
//$sQueryCidadaoCgm .= " 				z01_munic,			";
//$sQueryCidadaoCgm .= " 				z01_bairro,			";
//$sQueryCidadaoCgm .= " 				z01_cep,				";
//$sQueryCidadaoCgm .= " 				z01_cgccpf,			";
//$sQueryCidadaoCgm .= " 				z01_telef,			";
//$sQueryCidadaoCgm .= " 				z01_email,			";
//$sQueryCidadaoCgm .= " 				z01_compl,			";
//$sQueryCidadaoCgm .= " 				z01_uf					";
//$sQueryCidadaoCgm .= " 				ov03_numcgm			";
$sQueryCidadaoCgm .= " 			from cidadao as c	";
$sQueryCidadaoCgm .= " 			     left join  cidadaotelefone as ct on c.ov02_sequencial = ct.ov07_cidadao and c.ov02_seq = ct.ov07_seq	and ov07_principal is true";
$sQueryCidadaoCgm .= "      		 left join  cidadaoemail as cm on c.ov02_sequencial = cm.ov08_cidadao and c.ov02_seq = cm.ov08_seq	and ov08_principal is true";
$sQueryCidadaoCgm .= "	 	   	   left join  cidadaocgm as ccgm on c.ov02_sequencial = ccgm.ov03_cidadao and c.ov02_seq = ccgm.ov03_seq";
//$sQueryCidadaoCgm .= "			     left join cgm on ccgm.ov03_numcgm = cgm.z01_numcgm";
$sQueryCidadaoCgm .= "	 	 where c.ov02_sequencial = $ov02_sequencial and ov02_ativo is true";

//echo $sQueryCidadaoCgm;

$rsQueryCidadaoCgm = pg_query($sQueryCidadaoCgm);

$cidadao_false = true;;
if(pg_num_rows($rsQueryCidadaoCgm)>0){
	db_fieldsmemory($rsQueryCidadaoCgm,0);
	$cidadao_false = false;
}

$z01_numcgm = "";

if (isset($ov03_numcgm) && trim($ov03_numcgm) != "" && $z01_numcgm == ""){

	$sQueryCgm  = " 		select							";
	$sQueryCgm .= " 				z01_nome,				";
	$sQueryCgm .= " 				z01_ender,			";
	$sQueryCgm .= " 				z01_numero,			";
	$sQueryCgm .= " 				z01_munic,			";
	$sQueryCgm .= " 				z01_bairro,			";
	$sQueryCgm .= " 				z01_cep,				";
	$sQueryCgm .= " 				z01_cgccpf,			";
	$sQueryCgm .= "         z01_ident,      ";
	$sQueryCgm .= " 				z01_telef,			";
	$sQueryCgm .= " 				z01_email,			";
	$sQueryCgm .= " 				z01_compl,			";
	$sQueryCgm .= " 				z01_uf					";
	$sQueryCgm .= " 		from cgm						";
	$sQueryCgm .= " 			where z01_numcgm = $ov03_numcgm	";
	
	$rsQueryCgm = pg_query($sQueryCgm);
	if(pg_num_rows($rsQueryCgm) > 0){

		db_fieldsmemory($rsQueryCgm,0);
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
	db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
	db_app::load('estilos.css,grid.style.css');
?>
</head>
<script>
function js_marcar(){
	if ($F('btnMarcar') == 'M'){
		var lMarcar = true;
		$('btnMarcar').value = "D";
	}else{
		var lMarcar = false;
		$('btnMarcar').value = "M";
	}
	
	var aCheck = document.getElementsByTagName('input');
	var iNumElementos =  aCheck.length;
	for(var iInd=0; iInd < iNumElementos; iInd++){
		if(aCheck[iInd].type == 'checkbox' && lMarcar == true){
			aCheck[iInd].checked = 'checked';
		}else{
			aCheck[iInd].checked = '';
		}
	}
}

function js_importaDadosSelecionados(){
	var aCheck = document.getElementsByTagName('input');
	var iNumElementos =  aCheck.length;
	var iNumSel = 0;
	for(var iInd=0; iInd < iNumElementos; iInd++){
		if(aCheck[iInd].checked){ iNumSel++; }
	}
	if(iNumSel == 0){
		alert("Usuário:\n\n Nenhum dado selecionado para ser importado!\n\n\Administrador:\n\n");
	}
	
	frmCgm = parent.document.form1
	
	$('chkNome').checked 			? frmCgm.z01_nome.value   = $F('chkNome') : '';
	$('chkEndereco').checked 	? frmCgm.z01_ender.value  = $F('chkEndereco') : '';
	$('chkNumero').checked 		? frmCgm.z01_numero.value = $F('chkNumero') : '';
	$('chkCompl').checked 		? frmCgm.z01_compl.value  = $F('chkCompl') : '';
	$('chkTelefone').checked 	? frmCgm.z01_telef.value  = $F('chkTelefone') : '';
	$('chkEmail').checked 		? frmCgm.z01_email.value  = $F('chkEmail') : '';
	$('chkCNPJCPF').checked 	? frmCgm.z01_cpf.value    = $F('chkCNPJCPF') : '';
	$('chkIdent').checked     ? frmCgm.z01_ident.value  = $F('chkIdent') : '';
	$('chkMunic').checked 		? frmCgm.z01_munic.value  = $F('chkMunic') : '';
	$('chkBairro').checked 		? frmCgm.z01_bairro.value = $F('chkBairro') : '';
	$('chkCep').checked 			? frmCgm.z01_cep.value    = $F('chkCep') : '';
	$('chkUF').checked 				? frmCgm.z01_cep.value    = $F('chkUF') : '';
	
	frmCgm.ov02_sequencial.value	= $F('ov02_sequencial');
	frmCgm.ov02_seq.value 				= $F('ov02_seq');

	parent.db_iframe.hide();
	
}

</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<form method="post" action="" name="form1">
<input type="hidden" name="datausu" id="datausu" value="<?=date('Y-m-d',db_getsession('DB_datausu')); ?>">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
<? 
	if (!$cidadao_false){
?>
  <tr align="center"> 
    <td valign="top" bgcolor="#CCCCCC">
    <center>
    <fieldset>
    	<legend><b>Compara Cidadão / CGM:</b></legend>
    	
	    <table cellspacing = 1 align="left" width="790" style="border: 2px inset white;">
	    	<tr bgcolor="#EEEFF2" align="center">
	    		<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="button" onclick="js_marcar();" value="M" id="btnMarcar" name="btnMarcar" style="font-weight: bold;"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left" width="80" height="25">&nbsp;</td>
	    		<td align="center" width="355"><b>Cidadão</b></td>
	    		<td align="center"><b>CGM</b></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    		<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center" height="25"><input type="checkbox" id="chkNome"  name="chkNome" class="chkBox" value="<?=trim($ov02_nome);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Nome:</b></td>
	    		<? 
	    			$background = (trim($ov02_nome) != trim($z01_nome)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_nome?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_nome?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center" height="25"><input type="checkbox" id="chkEndereco" name="chkEndereco" class="chkBox" value="<?=trim($ov02_endereco);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Endereço:</b></td>
	    		<? 
	    			$background = (trim($ov02_endereco." nº".$ov02_numero) != trim($z01_ender." nº".$z01_numero)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<?echo $ov02_endereco;?>
	    				</td>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<?echo $z01_ender;
	    				?>
	    		</td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkNumero" name="chkNumero" class="chkBox" value="<?=trim($ov02_numero);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Número:</b></td>
	    		<? 
	    			$background = (trim($ov02_numero) != trim($z01_numero)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<? echo $ov02_endereco != "" ? "$ov02_numero" : ''?></td>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<? echo $z01_ender != "" ? "$z01_numero" : '' ?>
	    		</td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center" height="25"><input type="checkbox" id="chkCompl" name="chkCompl" class="chkBox" value="<?=trim($ov02_compl);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Complemento:</b></td>
	    		<? 
	    			$background = (trim($ov02_compl) != trim($z01_compl)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<? echo $ov02_compl ?></td>
	    		<td align="left" bgcolor="<?=$background?>">
	    			<? echo $z01_compl ?>
	    		</td>
	    	</tr>
	    <tr bgcolor="#FFFFFF">
	    		<? 
	    			if(isset($importa)&& $importa=='true'){
	    				if($ov07_ddd == 0){
	    					$telefone = $ov07_numero;
	    				}else{
	    					$telefone = $ov07_ddd." ".$ov07_numero;
	    				}
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkTelefone" name="chkTelefone" class="chkBox" value="<?=$telefone;?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Telefone:</b></td>
	    		<? 
	    			$background = (str_replace(" ","",$ov07_ddd.$ov07_numero) != str_replace(" ","",$z01_telef)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    			if($ov07_ddd == 0){
	    					$telefone = $ov07_numero;
	    				}else{
	    					$telefone = $ov07_ddd." ".$ov07_numero;
	    				}
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$telefone ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_telef ?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkEmail" name="chkEmail" class="chkBox" value="<?=trim($ov08_email);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>E-Mail:</b></td>
	    		<? 
	    			$background = (trim($ov08_email) != trim($z01_email)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov08_email ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_email ?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkCNPJCPF" name="chkCNPJCPF" class="chkBox" value="<?=trim($ov02_cnpjcpf);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>CNPJ/CPF:</b></td>
	    		<? 
	    			$background = (trim($ov02_cnpjcpf) != trim($z01_cgccpf)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_cnpjcpf ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_cgccpf ?></td>
	    	</tr>
        <tr bgcolor="#FFFFFF">
        <? 
            if(isset($importa)&& $importa=='true'){
              ?>
              <td align="center"  height="25"><input type="checkbox" id="chkIdent" name="chkIdent" class="chkBox" value="<?=trim($ov02_ident);?>"></td>
              <?
            }
          ?>
          <td align="left"><b>Identidade:</b></td>
          <? 
            $background = (trim($ov02_ident) != trim($z01_ident)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
          ?>
          <td align="left" bgcolor="<?=$background?>"><?=$ov02_ident ?></td>
          <td align="left" bgcolor="<?=$background?>"><?=$z01_ident ?></td>
        </tr>	    	
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkMunic" name="chkMunic" class="chkBox" value="<?=trim($ov02_munic);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Município:</b></td>
	    		<? 
	    			$background = (trim($ov02_munic) != trim($z01_munic)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_munic ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_munic ?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkUF" name="chkUF" class="chkBox" value="<?=trim($ov02_uf);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>UF:</b></td>
	    		<? 
	    			$background = (trim($ov02_uf) != trim($z01_uf)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_uf ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_uf ?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkBairro" name="chkBairro" class="chkBox" value="<?=trim($ov02_bairro);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Bairro:</b></td>
	    		<? 
	    			$background = (trim($ov02_bairro) != trim($z01_bairro)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_bairro ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_bairro ?></td>
	    	</tr>
	    	<tr bgcolor="#FFFFFF">
	    	<? 
	    			if(isset($importa)&& $importa=='true'){
	    				?>
	    				<td align="center"  height="25"><input type="checkbox" id="chkCep" name="chkCep" class="chkBox" value="<?=trim($ov02_cep);?>"></td>
	    				<?
	    			}
	    		?>
	    		<td align="left"><b>Cep:</b></td>
	    		<? 
	    			$background = (trim($ov02_cep) != trim($z01_cep)) && $ov03_numcgm != ""  ? '#FFFF66' : '';
	    		?>
	    		<td align="left" bgcolor="<?=$background?>"><?=$ov02_cep ?></td>
	    		<td align="left" bgcolor="<?=$background?>"><?=$z01_cep ?></td>
	    	</tr>
			</table>
	   
	   </fieldset>
	   </center> 
   </td>
  </tr> 
  <tr align="center">
  	<td height="40" valign="middle">
  		<? 
  		if(isset($importa) && $importa == 'true') {
  		?>
	  		<input type="hidden" name="ov02_sequencial" id="ov02_sequencial" value="<?=$ov02_sequencial ?>">
				<input type="hidden" name="ov02_seq" id="ov02_seq" value="<?=$ov02_seq ?>">
	  		<input type="button" name="importar" value="Importar dados selecionados" onclick="js_importaDadosSelecionados();">
  		<?
  		}
}else {
  		?>
  	<tr align="center">
  		<td>Nenhum cadastro ativo para importar dados do cidadão!</td>
  	</tr>	
  	<tr align="center">
		<td>
<?
}
?>
  		<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe.hide()">
  	</td>
  </tr>
</table>
</form>
</body>
</html>