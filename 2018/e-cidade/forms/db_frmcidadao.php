<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("classes/db_tiporetorno_classe.php"));
require_once(modification("classes/db_telefonetipo_classe.php"));

$cltelefonetipo 		= new cl_telefonetipo();
$cltiporetorno 			= new cl_tiporetorno();
$clcidadao 					= new cl_cidadao;
$clcidadaoemail 		= new cl_cidadaoemail;
$clcidadaotelefone 	= new cl_cidadaotelefone;

$clcidadaoemail->rotulo->label();
$clcidadaotelefone->rotulo->label();
$clcidadao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ov16_sequencial");
$clrotulo->label("ov03_numcgm");
$clrotulo->label("z01_nome");

$clrotulo->label("as04_sequencial");
$clrotulo->label("as04_codigofamiliarcadastrounico");
$clrotulo->label("as15_codigofamiliarcadastrounico");
$clrotulo->label("as03_sequencial");
$clrotulo->label("as17_sequencial");
$iCidadaoFamilia = 0;

$lOrigemLeitor = isset($oGet->lOrigemLeitor) && $oGet->lOrigemLeitor == true ? true : 'false';
$lTelaSocial   = isset($oGet->lTelaSocial) && $oGet->lTelaSocial     == 'true' ? 'true' : 'false';
?>
<form id='frmCidadao' name="form1" method="post" action="">

<div id="cadastroCidadao">
<center>
<table style="margin-top: 20px;" width="790">
	<tr>
	  <td>
	    <fieldset style="<?php echo $sStylelTelaSocial; ?>">
        <legend><strong>Consulta Família</strong></legend>
        <table>
          <tr>
            <td>
              <strong>Responsável Pela Família: </strong>
            </td>
            <td>
              <?php
                $aResponsavelFamilia = array(0=>'Selecione', 1=>'Sim',2=>'Não');
                db_select('sResponsavelFamilia', $aResponsavelFamilia, true, $db_opcao, 'onchange="js_selecionaResponsavelFamilia(this.value)"');
              ?>
            </td>
          </tr>
          <tr id="trConsultaFamilia" style="display:none">
            <td style="font-weight: bold;">
              <?php
                db_ancora("<strong>Responsável da Família: </strong>", "js_pesquisaCidadaoFamilia(true);", 1);
              ?>
            </td>
            <td>
            	<?php
                db_input("as04_sequencial", 10, $Ias04_sequencial, true,
                         "text", 1, "onchange='js_pesquisaCidadaoFamilia(false);'");
                db_input("sNomeResponsavelFamilia", 40, $Iov02_nome, true, "text", 3);
  				    ?>
            </td>
          </tr>
          <tr id="trTipoFamiliar" style="display:none">
            <td><strong>Tipo Familiar: </strong></td>
            <td id="ctnTipoFamiliar"></td>
          </tr>
        </table>
      </fieldset>
	<fieldset>
		<table>
			<tr><td>
				<fieldset id="fieldset_cidadao"><legend><strong>Cadastro Cidadão</strong></legend>
				<table>
				<tr>
					<td nowrap title="<?=@$Tov02_sequencial?>"><?=@$Lov02_sequencial;?></td>
					<td>
					  <?
					    db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,"");
					    db_input('cadatendimento' ,10,'',true,'hidden',3,"");
					  ?>
					</td>
					<td style="<?=$sStyleProcessado?>">
						<strong>Processado:</strong>
						<? db_input('ov03_numcgm',10,$Iov03_numcgm,true,'text',3,"");?>
						<? db_input('z01_nome',30,$Iz01_nome,true,'text',3,"");?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tov02_nome?>"><?=@$Lov02_nome?></td>
					<td colspan="2">
						<?db_input('ov02_nome',80,$Iov02_nome,true,'text',$db_opcao,"");?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tov02_ident?>"><?=@$Lov02_ident?></td>
					<td>
						<?db_input('ov02_ident',20,$Iov02_ident,true,'text',$db_opcao,"");?>
					</td>
					<td nowrap title="<?=@$Tov02_cnpjcpf?>" align="left"><?=@$Lov02_cnpjcpf?>
						<?
						db_input('ov02_cnpjcpf',14,$Iov02_cnpjcpf,true,'text',$db_opcao,"")
						?>
				  </td>
				</tr>
				<tr>
  			  <td nowrap title="<?=@$Tov02_datanascimento?>"><?=@$Lov02_datanascimento?></td>
  			  <td>
  			    <?php db_inputdata("ov02_datanascimento", null, null, null, true, 'text', $db_opcao) ?>
  			  </td>
  			  <td nowrap title="<?=@$Tov02_sexo?>"><?=@$Lov02_sexo?>
  			    <?php
  			      $aSexo = array('M' => 'Masculino', 'F' => 'Feminino');
  			      db_select("ov02_sexo", $aSexo, true, $db_opcao);
  			    ?>
  			  </td>
        </tr>
				<tr style="<?=$sStyleProcessado?>">
					<td><strong>Tipo Retorno:</strong></td>
					<td colspan="2" id="tiporetorno">
						<?
							$rsTipoRetorno = $cltiporetorno->sql_record($cltiporetorno->sql_query_file());
							$iNumRowsTipoRetorno = pg_num_rows($rsTipoRetorno);
							if($iNumRowsTipoRetorno > 0){
								for($i=0; $i < $iNumRowsTipoRetorno; $i++){
									db_fieldsmemory($rsTipoRetorno,$i);
									$y = $i + 1;
									echo "<input type=\"checkbox\" name=\"tiporetorno$y\" id=\"tiporetorno$y\" value=\"$ov22_sequencial\">";
									echo "<strong>".$ov22_descricao."</strong>&nbsp;";
								}
							}
						?>
					</td>
				</tr>
				</table>
				</fieldset>
			</td></tr>
			<tr><td>
				<fieldset id="fieldset_carta"><legend><strong>Retorno por Carta/Pessoalmente</strong></legend>
				<table>
				<tr>
					<td nowrap title="<?=@$Tov02_endereco?>">
						<?
						db_ancora(@$Lov02_endereco,"js_pesquisaov02_endereco(true);",$db_opcao,'','ancora_endereco');
						?>
					<td>
						<?
						db_input('ov02_endereco',50,$Iov02_endereco,true,'text',$db_opcao,"")
						?>
					</td>
					<td nowrap title="<?=@$Tov02_numero?>" align="right">
						<?=@$Lov02_numero?>
					</td>
					<td>
						<?
						db_input('ov02_numero',20,$Iov02_numero,true,'text',$db_opcao,"")
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tov02_bairro?>">
					<?
					db_ancora(@$Lov02_bairro,"js_pesquisaov02_bairro(true);",$db_opcao,'','ancora_bairro');
       		?>
       		</td>
					<td>
						<?
						db_input('ov02_bairro',50,$Iov02_bairro,true,'text',$db_opcao,"")
						?>
					</td>
					<td nowrap title="<?=@$Tov02_compl?>">
						<?=@$Lov02_compl?>
					</td>
					<td>
						<?
						db_input('ov02_compl',20,$Iov02_compl,true,'text',$db_opcao,"")
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tov02_munic?>"><?=@$Lov02_munic?></td>
					<td>
						<?
						db_input('ov02_munic',30,$Iov02_munic,true,'text',$db_opcao,"")
						?>
						<?=@$Lov02_uf?>
						<?
						db_input('ov02_uf',2,$Iov02_uf,true,'text',$db_opcao,"")
						?>
					</td>
					<td nowrap title="<?=@$Tov02_cep?>" align="right"><?=@$Lov02_cep?></td>
					<td>
						<?db_input('ov02_cep',20,$Iov02_cep,true,'text',$db_opcao,"")?>
					</td>
				</tr>
				</table>
				</fieldset>
			</td></tr>
			<tr><td>
				<fieldset id="fieldset_email"><legend><strong>Retorno por Email</strong></legend>
				<table>
				<tr>
    			<td nowrap title="<?=@$Tov08_email?>"><?=@$Lov08_email?></td>
    			<td>
    				<input type="hidden" id="alteraEmail" value="" name="alteraEmail">
						<?
						db_input('ov08_email',60,$Iov08_email,true,'text',$db_opcao,"onkeyUp='js_email_lower(this.value);'");

						echo "&nbsp;".@$Lov08_principal;
						$xx = array('t'=>'Sim','f'=>'Não');
						db_select('ov08_principal',$xx,true,$db_opcao);
						?>
						<span id="btnEmail">
						<input name="incluiEmail" type="button" id="incluiEmail" value="Incluir" onclick="js_incluirEmail();" <?echo  $db_opcao==33 ? 'disabled': ''; ?> >
						<input name="novoEmail" type="button" style="display: none;" id="novoEmail" value="Novo" onclick="js_NovoEmail();"  <?echo  $db_opcao==33 ? 'disabled': ''; ?>>
						</span>
			    </td>
  			</tr>
  			<tr>
  				<td colspan="2">
  					<fieldset><legend><strong>Lista Emails</strong></legend>
  					<div id="listaemails">
  					</div>
  					</fieldset>
  				</td>
  			</tr>
				</table>
				</fieldset>
			</td></tr>
			<tr><td>
				<fieldset id="fieldset_fax"><legend><strong>Retorno por Telefone/Fax</strong></legend>
				<table width="90%" border="0">
				<tr>
    			<td nowrap title="<?=@$Tov07_tipotelefone?>"><?=@$Lov07_tipotelefone?></td>
    			<input type="hidden" id="alteraTelefone" value="" name="alteraTelefone">
    			<td>
						<?php
						$rsTipoTelefone = $cltelefonetipo->sql_record($cltelefonetipo->sql_query_file());

            $x = array();
						if($cltelefonetipo->numrows > 0){
							for($i=0; $i < $cltelefonetipo->numrows; $i++){
								db_fieldsmemory($rsTipoTelefone,$i);
								$x[$ov23_sequencial] = $ov23_descricao;
							}
						}
						//$x = array('1'=>'Residencial','2'=>'Comercial','3'=>'Celular');
						db_select('ov07_tipotelefone',$x,true,$db_opcao);
						?>
			    </td>
			    <td nowrap title="<?=@$Tov07_principal?>" align="right"><?=@$Lov07_principal?></td>
			    <td>
			    <?
			    	$xxx = array('t'=>'Sim','f'=>'Não');
						db_select('ov07_principal',$xxx,true,$db_opcao);
					?>
			    </td>
			   <td></td>
			   <td></td>
			  </tr>
  			<tr>
			    <td nowrap title="<?=@$Tov07_ddd?>"><?=@$Lov07_ddd?></td>
    			<td>
						<?
						db_input('ov07_ddd',10,$Iov07_ddd,true,'text',$db_opcao,"")
						?>
			    </td>
    			<td nowrap title="<?=@$Tov07_numero?>" align="right"><?=@$Lov07_numero?></td>
    			<td>
					<?
					db_input('ov07_numero',10,$Iov07_numero,true,'text',$db_opcao,"")
					?>
			    </td>
			    <td nowrap title="<?=@$Tov07_ramal?>" align="right"><?=@$Lov07_ramal?></td>
    			<td align="left">
					<?
					db_input('ov07_ramal',10,$Iov07_ramal,true,'text',$db_opcao,"")
					?>
			    </td>
  			</tr>
  			<tr>
  				<td nowrap title="<?=@$Tov07_obs?>"><?=@$Lov07_obs?></td>
    			<td colspan="5">
					<?
					db_textarea('ov07_obs',3,80,$Iov07_obs,true,'text',$db_opcao,"")
					?>
			    </td>
  			</tr>
  			<tr>
					<td align="right" colspan="3"><input name="incluiTelefone" type="button" id="incluiTelefone" value="Incluir" onclick="js_incluirTelefone();" <?echo  $db_opcao==33 ? 'disabled': ''; ?>></td>
			    <td colspan="3"><input name="novoTelefone" type="button" style="display: none;" id="novoTelefone" value="Novo" onclick="js_NovoTelefone();" <?echo  $db_opcao==33 ? 'disabled': ''; ?>></td>
  			</tr>
  			<tr align="center"><td colspan="6">
				<fieldset><legend><strong>Lista Telefones</strong></legend>
				<div id="listatelefones">
  			</div>
				</fieldset>
				</td></tr>
				</table>
				</fieldset>
			</td></tr>
		</table>
	</fieldset></td></tr>
</table>
<table>
  <tr>
    <td id="idBotoes">
			<input 	name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
							type="button" id="db_opcao"
							value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>
							onclick="js_incluir(<?=$db_opcao;?>);" >
			<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?>>
	  </td>
	</tr>
</table>
</center>
</div>
<div id="cadastroAvaliacaoSocioEconomicaCidadao" style="<?php echo $sStylelTelaSocial; ?>">
  <center>
    <fieldset style="width:700px;height:500px">
      <legend><strong>Avaliação Sócio Econômica - Cidadão</strong></legend>
       <div id='gridContainerCidadao'></div>
    </fieldset>
  </center>
</div>
<div id="cadastroAvaliacaoSocioEconomicaFamilia" style="<?php echo $sStylelTelaSocial; ?>">
  <center>
    <fieldset style="width:700px;height:500px">
      <legend><strong>Avaliação Sócio Econômica - Família</strong></legend>
       <div id='gridContainerFamilia'></div>
    </fieldset>
  </center>
</div>
<?php
if ($lTelaSocial) {

  db_input('as03_sequencial',20,$Ias03_sequencial,true,'hidden',$db_opcao,"");
  db_input('as17_sequencial',20,$Ias17_sequencial,true,'hidden',$db_opcao,"");
  db_input('iCidadaoFamilia',20,null,true,'hidden',$db_opcao,"");
  db_input('iLocalatendimentosocial',20,null,true,'hidden',$db_opcao);
}

?>
</form>
<script type="text/javascript">
var lOrigemLeitor     = <?=$lOrigemLeitor?>;
var lTelaSocial       = <?=$lTelaSocial?>;

function js_incluir(acao) {

	if (acao != 3) {

			//Valida cidadao nome,identidade e cpf
			if ($F('ov02_nome') == '') {

				alert('Usuário:\n\nCampo Nome / Razão Social não informado !\n\n');
				$('ov02_nome').focus();
				return false;
			}

			if ($F('ov02_ident') == '' && !lOrigemLeitor) {

				alert('Usuário:\n\nCampo Identidade não informado !\n\n');
				$('ov02_ident').focus();
				return false;
			}
			if ($F('ov02_cnpjcpf') == '' && !lOrigemLeitor) {

				alert('Usuário:\n\nCampo CPF não informado !\n\n');
				$('ov02_cnpjcpf').focus();
				return false;
			}

			if ($F('ov02_datanascimento') == '' && lTelaSocial) {

				alert('Data de Nascimento não informada.');
				$('ov02_datanascimento').focus();
				return false;
			}

			var validaCPF = js_verificaCGCCPF($('ov02_cnpjcpf'));

			if (!validaCPF && !lOrigemLeitor) {
				return false;
		  }

			if (!$('tiporetorno1').checked && !$('tiporetorno2').checked && !$('tiporetorno3').checked && !$('tiporetorno4').checked
					&& lOrigemLeitor == 'true') {

				alert('\n\nUsuário:\n\n  Deve ser preenchido pelos um tipo de retorno para o cidadão!\n\n\Inclusão Abortada');
				return false;
			}

			//Aqui valida se o tipo de retorno escolhido foi Pessoalmente ou Carta
			if ($('tiporetorno1').checked || $('tiporetorno2').checked) {

				if (!lOrigemLeitor) {

  				if ($F('ov02_endereco') == "") {

  					alert('Usuário:\n\nCampo Endereço não informado !\n\n');
  					$('ov02_endereco').focus();
  					return false;
  				}

  				if ($F('ov02_numero') == "") {

  					alert('Usuário:\n\nCampo Número não informado !\n\n');
  					$('ov02_numero').focus();
  					return false;
  				}

  				if ($F('ov02_bairro') == "") {

  					alert('Usuário:\n\nCampo Bairro não informado !\n\n');
  					$('ov02_bairro').focus();
  					return false;
  				}

  				if ($F('ov02_munic') == "") {

  					alert('Usuário:\n\nCampo Município não informado !\n\n');
  					$('ov02_munic').focus();
  					return false;
  				}

  				if ($F('ov02_uf') == "") {

  					alert('Usuário:\n\nCampo UF não informado !\n\n');
  					$('ov02_uf').focus();
  					return false;
  				}

  				if ($F('ov02_cep') == "") {

  					alert('Usuário:\n\nCampo CEP não informado !\n\n');
  					$('ov02_cep').focus();
  					return false;
  				}
				}
			}

			//Aqui valida se o tipo de retorno escolhido foi Email
			if ($('tiporetorno3').checked && oDBGridListaEmails.getNumRows() == 0) {

				alert('Usuário:\n\nNenhum Email informado para retorno!\n\n');
				$('ov08_email').focus();
				return false;
			}

			//Aqui valida se o tipo de retorno escolhido foi Telefone / Fax
			if ($('tiporetorno4').checked && oDBGridListaTelefones.getNumRows() == 0) {

				alert('Usuário:\n\nNenhum Telefone informado para retorno!\n\n');
				$('ov07_numero').focus();
				return false;
			}

			if (lTelaSocial) {
			  if ($F('sResponsavelFamilia') == 2 && $F('as04_sequencial') == '') {
				  alert('Escolha o Responsável da Família');
				  $('as04_sequencial').focus();
				  return false;
			  }
			}

			//Monto o objeto para incluir os dados
			var oIncluir = new Object();
			//Dados do cidadão
			oIncluir.cidadao = new Object();

			oIncluir.cidadao.ov02_sequencial	   = $F('ov02_sequencial');
			oIncluir.cidadao.ov03_numcgm	 		   = $F('ov03_numcgm');
			oIncluir.cidadao.ov02_nome 				   = encodeURIComponent(tagString($F('ov02_nome'))) ;;
			oIncluir.cidadao.ov02_ident 			   = $F('ov02_ident');
			oIncluir.cidadao.ov02_cnpjcpf			   = $F('ov02_cnpjcpf');
			oIncluir.cidadao.ov02_endereco 		   = $F('ov02_endereco');
			oIncluir.cidadao.ov02_numero 			   = $F('ov02_numero');
			oIncluir.cidadao.ov02_bairro 			   = $F('ov02_bairro');
			oIncluir.cidadao.ov02_munic 			   = $F('ov02_munic');
			oIncluir.cidadao.ov02_uf 					   = $F('ov02_uf');
			oIncluir.cidadao.ov02_cep 				   = $F('ov02_cep');
			oIncluir.cidadao.ov02_compl 			   = $F('ov02_compl');
			oIncluir.cidadao.ov02_datanascimento = $F('ov02_datanascimento');
			oIncluir.cidadao.ov02_sexo 			     = $F('ov02_sexo');
			oIncluir.cidadao.lOrigemLeitor       = lOrigemLeitor;
			oIncluir.cidadao.lTelaSocial         = lTelaSocial;

			if (lTelaSocial) {
			  oIncluir.cidadao.iResponsavelFamilia     = $F('as04_sequencial');
			  oIncluir.cidadao.iTipoFamiliar           = $F('CboTipoFamiliar');
			  oIncluir.cidadao.iLocalatendimentosocial = <?php echo $iLocalatendimentosocial; ?>;
				oIncluir.cidadao.lTelaSocial             = true;
				oIncluir.cidadao.as03_sequencial         = $F('as03_sequencial');
				oIncluir.cidadao.as17_sequencial         = $F('as17_sequencial');
				oIncluir.cidadao.iCidadaoFamilia         = $F('iCidadaoFamilia');
			}

			//Tipos de Retorno selecionados
			oIncluir.tiporetorno = new Array();
			if ($('tiporetorno1').checked) {

				var iInd = oIncluir.tiporetorno.length;
				oIncluir.tiporetorno[iInd] = new Object();
				oIncluir.tiporetorno[iInd].ov04_tiporetorno = $F('tiporetorno1');
			}

			if ($('tiporetorno2').checked) {

				var iInd = oIncluir.tiporetorno.length;
				oIncluir.tiporetorno[iInd] = new Object();
				oIncluir.tiporetorno[iInd].ov04_tiporetorno = $F('tiporetorno2');
			}

			if ($('tiporetorno3').checked) {

				var iInd = oIncluir.tiporetorno.length;
				oIncluir.tiporetorno[iInd] = new Object();
				oIncluir.tiporetorno[iInd].ov04_tiporetorno = $F('tiporetorno3');
			}

			if ($('tiporetorno4').checked) {

				var iInd = oIncluir.tiporetorno.length;
				oIncluir.tiporetorno[iInd] = new Object();
				oIncluir.tiporetorno[iInd].ov04_tiporetorno = $F('tiporetorno4');
			}
			//Dados do tipo de retorno carta/pessoalmente

			//Lendo os emails incluidos para cadastrar
			if (oDBGridListaEmails.getNumRows() > 0) {

				oIncluir.emails = new Array();
				var iNumRows = oDBGridListaEmails.aRows.length;

				for (var iInd = 0; iInd < iNumRows; iInd++) {

					oIncluir.emails[iInd] = new Object();
					oIncluir.emails[iInd].ov08_email 			= oDBGridListaEmails.aRows[iInd].aCells[0].getValue();
					oIncluir.emails[iInd].ov08_principal	= oDBGridListaEmails.aRows[iInd].aCells[3].getValue();
				}
			} else {
				oIncluir.emails = false;
			}
			//Lendo os Telefones incluidos para enviar
			if (oDBGridListaTelefones.getNumRows() > 0) {

				var temPrincipal = js_readGridTelefonePrincipal();

				if (!temPrincipal) {

					alert('Usuário:\n\nNenhum Telefone Informado definido como principal!\n\nInclusão Abortada\n\n');
					return false;
				}

				oIncluir.telefones = new Array();
				var iNumRows = oDBGridListaTelefones.aRows.length;

				for (var iInd = 0; iInd < iNumRows; iInd++) {

					oIncluir.telefones[iInd] = new Object();

					oIncluir.telefones[iInd].ov07_ddd 					= oDBGridListaTelefones.aRows[iInd].aCells[1].getValue().trim();
					oIncluir.telefones[iInd].ov07_numero				= oDBGridListaTelefones.aRows[iInd].aCells[2].getValue();
					oIncluir.telefones[iInd].ov07_ramal					= oDBGridListaTelefones.aRows[iInd].aCells[3].getValue().trim();
					oIncluir.telefones[iInd].ov07_obs						= oDBGridListaTelefones.aRows[iInd].aCells[6].getValue().trim();
					oIncluir.telefones[iInd].ov07_tipotelefone	= oDBGridListaTelefones.aRows[iInd].aCells[7].getValue();
					oIncluir.telefones[iInd].ov07_principal			= oDBGridListaTelefones.aRows[iInd].aCells[8].getValue();


				}
			} else {
				oIncluir.telefones = false;
			}
	} else {

		var oIncluir     = new Object();
		oIncluir.cidadao = new Object();
		oIncluir.cidadao.ov02_sequencial	= $F('ov02_sequencial');
	}

  if ( $F('cadatendimento') != '' ) {
    oIncluir.lAtendimento = true;
  } else {
    oIncluir.lAtendimento = false;
  }

	if (acao == 1) {

		oIncluir.acao = 'incluir';
		var msgDiv    = 'Incluindo Cadastro do Cidadão...';
	} else if (acao == 2) {

		oIncluir.acao = 'alterar';
		var msgDiv    = 'Alterando Cadastro do Cidadão...';
	} else if(acao == 3) {

		oIncluir.acao = 'excluir';
		var msgDiv    = 'Excluindo Cadastro do Cidadão...';
	}

	var sDados = Object.toJSON(oIncluir);

	js_divCarregando(msgDiv,'msgBox');

	sUrl       = 'ouv1_cidadao.RPC.php';
	var sQuery = 'dados='+sDados;
	var oAjax  = new Ajax.Request( sUrl, {
                                         method: 'post',
                                         parameters: sQuery,
                                         onComplete: js_retornoIncluirDados
                                       }
                                  );

}

function js_retornoIncluirDados(oAjax){

	//alert(oAjax.responseText);

	js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");

  var sExpReg  = new RegExp('\\\\n','g');

  alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));

  if ( aRetorno.status == 1) {
    return false;
  } else if ( aRetorno.status == 0) {

    if (lTelaSocial) {

      oAbaAvaliacaoCidadao.lBloqueada = false;
      oAbaAvaliacaoFamilia.lBloqueada = false;
      oAbaCidadao.setVisibilidade(false);
      oAbaAvaliacaoCidadao.setVisibilidade(true);
      oAbaAvaliacaoFamilia.setVisibilidade(false);
      js_carregaDados(aRetorno.iCodCidadao);

      if (aRetorno.iTipoFamiliar == 0) {

        js_carregaDadosFamilia(aRetorno.iCodCidadao);
      }
    } else {

      if (lOrigemLeitor) {

        parent.location.href = "bib1_leitor001.php?codigo="+aRetorno.iCodCidadao
                                                +"&seq="+aRetorno.iSeqCidadao
                                                +"&nome="+aRetorno.sNome.urlDecode()
                                                +"&tipo=CIDADAO";
      } else {

        location.href = 'ouv1_cidadao001.php';
      }
    }
  } else if ( aRetorno.status == 2){

  	$('ov02_sequencial').value = aRetorno.cidadao;

  	if (lTelaSocial) {
  	  location.href = 'soc1_cidadao002.php';
  	  return false;
  	}

  	if (lOrigemLeitor) {
    	parent.location.href = 'bib1_leitor002.php';
  	} else {
  	  location.href        = 'ouv1_cidadao002.php';
  	}
  } else if ( aRetorno.status == 3){
  	location.href = 'ouv1_cidadao003.php';
  } else if ( aRetorno.status == 4){
    parent.js_confirmaCadastro(aRetorno.iCodCidadao,aRetorno.iSeqCidadao,$F('ov02_nome'));
  }
}


function js_frmListaEmails(){
		oDBGridListaEmails = new DBGrid('emails');
		oDBGridListaEmails.nameInstance = 'oDBGridListaEmails';
		oDBGridListaEmails.setHeader(new Array('Descrição','Principal','Ações','tipoprincipal'));
		oDBGridListaEmails.setHeight(38);
		oDBGridListaEmails.setCellAlign(new Array('left','center','right','center'));
		oDBGridListaEmails.setCellWidth(new Array(260,5,100,5));
		oDBGridListaEmails.aHeaders[3].lDisplayed = false;
		oDBGridListaEmails.show($('listaemails'));

		//js_RenderGridEmails();
}

function js_frmListaTelefones(){
		oDBGridListaTelefones = new DBGrid('telefones');
		oDBGridListaTelefones.nameInstance = 'oDBGridListaTelefones';
		oDBGridListaTelefones.setHeader(new Array('Descrição','DDD','Número','Ramal','principal','Ações','obs','tipotelefone','tipoprincipal'));
		oDBGridListaTelefones.setHeight(38);
		oDBGridListaTelefones.aHeaders[6].lDisplayed = false;
		oDBGridListaTelefones.aHeaders[7].lDisplayed = false;
		oDBGridListaTelefones.aHeaders[8].lDisplayed = false;
		oDBGridListaTelefones.setCellAlign(new Array('left','right','right','right','center','right','right','right','center'));

		oDBGridListaTelefones.show($('listatelefones'));

}

function js_pesquisaov02_situacaocidadao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_situacaocidadao','func_situacaocidadao.php?funcao_js=parent.js_mostrasituacaocidadao1|ov16_sequencial|ov16_sequencial','Pesquisa',true);
  }else{
     if(document.form1.ov02_situacaocidadao.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_situacaocidadao','func_situacaocidadao.php?pesquisa_chave='+document.form1.ov02_situacaocidadao.value+'&funcao_js=parent.js_mostrasituacaocidadao','Pesquisa',false);
     }else{
       document.form1.ov16_sequencial.value = '';
     }
  }
}

function js_mostrasituacaocidadao(chave,erro){
  document.form1.ov16_sequencial.value = chave;
  if(erro==true){
    document.form1.ov02_situacaocidadao.focus();
    document.form1.ov02_situacaocidadao.value = '';
  }
}

function js_mostrasituacaocidadao1(chave1,chave2){
  document.form1.ov02_situacaocidadao.value = chave1;
  document.form1.ov16_sequencial.value = chave2;
  db_iframe_situacaocidadao.hide();
}

function js_pesquisa(){

  var sParametros  = 'funcao_js=parent.js_preenchepesquisa|ov02_sequencial|ov02_seq';
  var sNomeLookup = 'func_cidadao.php';
  if (lTelaSocial) {

    sNomeLookup  = 'func_cidadaofamiliacompleto.php';
    sParametros += '&lSomenteCidadaoDepartamento&lSomenteFamiliaVinculada';
  }

  js_OpenJanelaIframe(
                       'CurrentWindow.corpo',
                       'db_iframe_cidadao',
                       sNomeLookup+'?'+sParametros,
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa(chave,chave1){
  db_iframe_cidadao.hide();
  <?
  if($db_opcao!=1){
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}

function js_email_lower(email){
	$('ov08_email').value = email.toLowerCase();
}

function js_validaEmail(email){
	var email = email;
	var expReg0 = new RegExp("[A-Za-z0-9_.-]+@([A-Za-z0-9_]+\.)+[A-Za-z]{2,4}");
	var expReg1 = new RegExp("[!#$%*<>,:;?°ºª~/|]");

	if(email.match(expReg1)!= null || email.indexOf('\\') != -1 || email.indexOf(' ') != -1){
		alert('Usuário:\n\nEmail informado não é válido ou esta vazio!\n\n exemplo de email: xxx@xx.xx\n\n Email pode conter: \n  letras, números, hifen(-), sublinhado _\n\n Email não pode conter:\n  caracteres especiais, virgula(,), ponto e virgula (;), dois pontos (:) \n\nAdministrador:\n\n') ;
		return false;
	}

	if(email.match(expReg0)==null){
		alert('Usuário:\n\nEmail informado não é válido ou esta vazio!\n\n exemplo de email: xxx@xx.xx\n\n Email pode conter: \n  letras, números, hifen(-), sublinhado _\n\n Email não pode conter:\n  caracteres especiais, virgula(,), ponto e virgula (;), dois pontos (:) \n\nAdministrador:\n\n') ;
		return false;
	}
	return true;

}


function js_incluirEmail(){

	var email = $F('ov08_email');

	if(js_validaEmail(email)==false){
		return false;
	}

//	if($F('ov08_email')== ""){
//
//	 	alert('Usuário:\n\nEmail não Informado!\n\nInclusão Abortada\n\n');
//	 	$('ov08_email').focus();
//	 	return false;
//
//	 }
//
	 var temPrincipal = js_readGridEmailPrincipal();

	 if(temPrincipal  && $F('ov08_principal') == 't'){

	 		if(!confirm('Usuário:\n\nJá existe um email Informado como principal!\n\nDeseja Incluir este email como secundário?\n\n')){
	 			$('ov08_email').focus();
	 			return false;
	 		}else{
	 			$('ov08_principal').value = 'f';
	 		}

	 }


 	 var oEmail = new Object();

   oEmail.ov08_email 			= $F('ov08_email');
   oEmail.ov08_principal	= $F('ov08_principal');
   oEmail.descricao				= $('ov08_principal').options[$('ov08_principal').selectedIndex].innerHTML;

   js_RenderGridEmail(new Array(oEmail),false,0,false);

   delete oEmail;

   $('ov08_email').value = '';
   $('ov08_principal').value = 'f';
   $('ov08_email').focus();

   $('incluiEmail').value = 'Incluir';
	 $('novoEmail').style.display = 'none';

	}

	function js_excluirEmail( posicao ){

		js_RenderGridEmail(new Array(),true,posicao,false);

 	}

	function js_alterarEmail( posicao ){

		oEmailAltera = new Object();

		oEmailAltera.ov08_email 		= oDBGridListaEmails.aRows[posicao].aCells[0].getValue().trim();
		oEmailAltera.ov08_principal = oDBGridListaEmails.aRows[posicao].aCells[3].getValue().trim();
		oEmailAltera.descricao			= oDBGridListaEmails.aRows[posicao].aCells[1].getValue().trim();

	 	$('ov08_email').value 		= oEmailAltera.ov08_email;
	 	$('ov08_principal').value = oEmailAltera.ov08_principal;

	 	$('alteraEmail').value = Object.toJSON(oEmailAltera);
	 	delete (oEmailAltera);

	 	$('ov08_email').focus();

	 	$('incluiEmail').value = 'Alterar';
		$('novoEmail').style.display = '';


	 	js_RenderGridEmail(new Array(),true,posicao,true);
	}

	function js_RenderGridEmail(aEmail,acao,indice,acaoBotao){
		var acaoBotao = acaoBotao;
		var iIndice 	= 0;
		var acao  		= acao;
		var aTemp			= oDBGridListaEmails.aRows;

		oDBGridListaEmails.clearAll(true);

		var iNumRows = aTemp.length;
		if(iNumRows > 0){
			aTemp.each(
				function (oEmail,iInd){

					if(acao == true && indice == iInd){

					}else{
		   			var aRow										= new Array();
						aRow[0] 										= oEmail.aCells[0].getContent();
						aRow[1] 										= oEmail.aCells[1].getContent();
						//aRow[2] 										= oEmail.sAcoes;
						aRow[2] 										= js_btnAcoesGridEmail(acaoBotao,iIndice);
						aRow[3] 										= oEmail.aCells[3].getContent();

	 					oDBGridListaEmails.addRow(aRow);
	 					iIndice++;
	 				}
				}
			);
		}

		var iNumRows = aEmail.length;
		if(iNumRows > 0){
			var acaoBotao = false;
			aEmail.each(
				function (oEmail,iInd){

						aRow 												= new Array();
						aRow[0] 										= oEmail.ov08_email.urlDecode();
						aRow[1] 										= oEmail.descricao.urlDecode();
						//aRow[2] 										= oEmail.sAcoes;
						aRow[2] 										= js_btnAcoesGridEmail(acaoBotao,iIndice);
						aRow[3] 										= oEmail.ov08_principal.urlDecode();

	 					oDBGridListaEmails.addRow(aRow);
	 					iIndice++;
	 			}

			);
		}
		oDBGridListaEmails.renderRows();
	}

 /*
	acao true desabilita os botes
			 false habilita os botoes
	*/
 function js_btnAcoesGridEmail(acaoBotao,iIndice){
 	var iIndice 	= iIndice;
 	var strBotoes = '';
 	if(acaoBotao){
 		strBotoes += '<input type="button" value="Alterar" onclick="js_alterarEmail('+iIndice+')" disabled>';
 		strBotoes += '<input type="button" value="Excluir" onclick="js_excluirEmail('+iIndice+')" disabled>';
 	}else{
 		strBotoes += '<input type="button" value="Alterar" onclick="js_alterarEmail('+iIndice+')" >';
 		strBotoes += '<input type="button" value="Excluir" onclick="js_excluirEmail('+iIndice+')" >';
 	}
 	return strBotoes;
 }

 function js_readGridEmailPrincipal(){

 	var iNumRows = oDBGridListaEmails.getNumRows();
		if(iNumRows > 0){
			for (var iInd=0; iInd < iNumRows; iInd++){
				if(oDBGridListaEmails.aRows[iInd].aCells[3].getValue().trim() == 't') return true;
			}
		}
	return false;
 }

 function js_NovoEmail(){

	oEmail = eval('('+$('alteraEmail').value+')');
	js_RenderGridEmail(new Array(oEmail),false,0,false);

  delete oEmail;

  $('ov08_email').value = '';
  $('ov08_principal').value = 'f';
  $('ov08_email').focus();

  $('incluiEmail').value = 'Incluir';
	$('novoEmail').style.display = 'none';

}


	function js_incluirTelefone(){

   if($F('ov07_numero') == ""){
   	alert('Usuário:\n\nNúmero de telefone não Informado!\n\n');
   	$('ov07_numero').focus();
   		return false;
	 }

   var temPrincipal = js_readGridTelefonePrincipal();

	 if(temPrincipal  && $F('ov07_principal') == 't'){

	 		if(!confirm('Usuário:\n\nJá existe um Telefone Informado como principal!\n\nDeseja Incluir este telefone como secundário?\n\n')){
	 			$('ov07_ddd').focus();
	 			return false;
	 		}else{
	 			$('ov07_principal').value = 'f';
	 		}

	 }

 	 var oTelefone = new Object();

   oTelefone.ov07_numero 				= $F('ov07_numero');
   oTelefone.ov07_ddd 					= $F('ov07_ddd');
   oTelefone.ov07_ramal 				= $F('ov07_ramal');
   oTelefone.ov07_obs 					= $F('ov07_obs');
   oTelefone.ov07_tipotelefone 	= $F('ov07_tipotelefone');
   oTelefone.ov07_principal		 	= $F('ov07_principal');
   oTelefone.descricao					= $('ov07_tipotelefone').options[$('ov07_tipotelefone').selectedIndex].innerHTML;
   oTelefone.descrprincipal			= $('ov07_principal').options[$('ov07_principal').selectedIndex].innerHTML;

	 js_RenderGridTelefone(new Array(oTelefone),false,0,false);

   delete oTelefone;

   $('ov07_numero').value 	= '';
   $('ov07_ddd').value		 	= '';
   $('ov07_ramal').value 		= '';
   $('ov07_obs').value 			= '';
   $('ov07_tipotelefone').value = 1;
   $('ov07_principal').value = 'f';
   $('ov07_numero').focus();
   $('incluiTelefone').value = 'Incluir';
	 $('novoTelefone').style.display = 'none';

 }

 function js_excluirTelefone( posicao ){

 	js_RenderGridTelefone(new Array(),true,posicao,false);

 }

 function js_alterarTelefone( posicao ){

 	oTelefoneAltera = new Object();

 	oTelefoneAltera.ov07_ddd 					= oDBGridListaTelefones.aRows[posicao].aCells[1].getValue().trim();
	oTelefoneAltera.ov07_numero				= oDBGridListaTelefones.aRows[posicao].aCells[2].getValue().trim();
	oTelefoneAltera.ov07_ramal				= oDBGridListaTelefones.aRows[posicao].aCells[3].getValue().trim();
	oTelefoneAltera.ov07_obs					= oDBGridListaTelefones.aRows[posicao].aCells[6].getValue().trim();
	oTelefoneAltera.ov07_tipotelefone	= oDBGridListaTelefones.aRows[posicao].aCells[7].getValue().trim();
	oTelefoneAltera.ov07_principal		= oDBGridListaTelefones.aRows[posicao].aCells[8].getValue().trim();
	oTelefoneAltera.descricao					= oDBGridListaTelefones.aRows[posicao].aCells[0].getValue().trim();
	oTelefoneAltera.descrprincipal		= oDBGridListaTelefones.aRows[posicao].aCells[4].getValue().trim();

 	$('ov07_ddd').value						= oTelefoneAltera.ov07_ddd;
	$('ov07_numero').value				= oTelefoneAltera.ov07_numero;
	$('ov07_ramal').value					= oTelefoneAltera.ov07_ramal;
	$('ov07_obs').value						= oTelefoneAltera.ov07_obs;
	$('ov07_tipotelefone').value	= oTelefoneAltera.ov07_tipotelefone;
	$('ov07_principal').value			= oTelefoneAltera.ov07_principal;


	$('alteraTelefone').value = Object.toJSON(oTelefoneAltera);
	delete (oTelefoneAltera);

	$('ov07_ddd').focus();

	$('incluiTelefone').value = 'Alterar';
	$('novoTelefone').style.display = '';

	js_RenderGridTelefone(new Array(),true,posicao,true);

 }

 function js_pesquisaov02_endereco(mostra){

	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
	  }

	}

function js_mostraruas1(chave1,chave2){
  $('ov02_endereco').value = chave2;
  db_iframe.hide();
}

function js_pesquisaov02_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }
}
function js_mostrabairro1(chave1,chave2){
  $('ov02_bairro').value = chave2;
  db_iframe_bairro.hide();
}

function js_pesquisaCidadao(chavepesquisa){

	oPesquisar = new Object();
	oPesquisar.chave 	= chavepesquisa;
	oPesquisar.acao		= 'pesquisar';

	if (lTelaSocial) {

	  oPesquisar.lTelaSocial = true;
	}

	var sDados = Object.toJSON(oPesquisar);

		js_divCarregando('Aguarde Carregando dados do Cidadão...','msgBox');

		sUrl = 'ouv1_cidadao.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoPesquisaCidadao
                                          }
                                  );

}

function js_retornoPesquisaCidadao(oAjax){

	js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");

  var sExpReg  = new RegExp('\\\\n','g');

  if ( aRetorno.status == 1) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{

    if (lTelaSocial) {

      if (!aRetorno.cidadao[0].lFamliliaDepartamento) {

  		  alert('Este cidadão não pode ser alterado pois a família não esta em nenhum departamento CRAS ou CREAS');

  		  if ($('db_opcao').name == 'alterar') {

  		    $('db_opcao').disabled            = 'disabled';
  		    $('sResponsavelFamilia').disabled = 'disabled';
  		    js_ativaFieldsetCadastro(false);
  		    return false;
  		  }
  		} else {

  		  js_PreenchePesquisaCidadao(aRetorno.cidadao);
  	  	js_PreenchePesquisaTipoRetorno(aRetorno.tiporetorno);
  			js_PreenchePesquisaCidadaoEmails(aRetorno.cidadaoemails);
  			js_PreenchePesquisaCidadaoTelefones(aRetorno.cidadaotelefones);

  			js_ativaFieldsetCadastro(true);
  			oAbaAvaliacaoCidadao.lBloqueada = false;
  			oAbaAvaliacaoFamilia.lBloqueada = true;

  		  if (aRetorno.cidadao[0].iTipoFamiliar == 0) {

  		    oAbaAvaliacaoFamilia.lBloqueada = false;
  		    js_carregaDadosFamilia(aRetorno.cidadao[0].ov02_sequencial);
  		  }

  			js_carregaDados(aRetorno.cidadao[0].ov02_sequencial);
  		}
    } else {

    	js_PreenchePesquisaCidadao(aRetorno.cidadao);
    	js_PreenchePesquisaTipoRetorno(aRetorno.tiporetorno);
  		js_PreenchePesquisaCidadaoEmails(aRetorno.cidadaoemails);
  		js_PreenchePesquisaCidadaoTelefones(aRetorno.cidadaotelefones);
    }
  }
}

function js_PreenchePesquisaCidadao(aCidadao){

	with (aCidadao[0])  {
	  $('ov02_sequencial').value 	   = ov02_sequencial;
	  $('ov03_numcgm').value 			   = ov03_numcgm
	  $('ov02_nome').value 				   = ov02_nome.urlDecode();
	  $('ov02_ident').value				   = ov02_ident;
	  $('ov02_cnpjcpf').value 		   = ov02_cnpjcpf;
	  $('ov02_endereco').value		   =	ov02_endereco.urlDecode();
		$('ov02_numero').value			   =	ov02_numero;
		$('ov02_bairro').value			   =	ov02_bairro.urlDecode();
		$('ov02_munic').value				   =	ov02_munic.urlDecode();
		$('ov02_uf').value					   =	ov02_uf.urlDecode();
		$('ov02_cep').value					   =	ov02_cep;
		$('ov02_compl').value				   =	ov02_compl.urlDecode();
		$('z01_nome').value					   =	z01_nome.urlDecode();
		$('ov02_datanascimento').value = js_formatar(ov02_datanascimento,'d');
		$('ov02_sexo').value					 = ov02_sexo;


		if (lTelaSocial) {

		  $('sResponsavelFamilia').value     = $F('as04_sequencial');
		  $('iLocalatendimentosocial').value = <?php echo $iLocalatendimentosocial; ?>;
			$('as03_sequencial').value         = as03_sequencial;
			$('as17_sequencial').value         = as17_sequencial;
			$('iCidadaoFamilia').value         = iCidadaoFamilia;

		  if (iTipoFamiliar == 0) {

			  $('sResponsavelFamilia').value = 1;
			  js_selecionaResponsavelFamilia(1);
			} else if (iTipoFamiliar == '') {

			  $('sResponsavelFamilia').value = 0;
			  js_selecionaResponsavelFamilia(0);
			} else if (iTipoFamiliar > 0) {

			  $('sResponsavelFamilia').value = 2;
			  $('as04_sequencial').value = parseInt(iCidadaoFamilia);
			  js_selecionaResponsavelFamilia(2);
			  oCboTipoFamiliar.setValue(iTipoFamiliar);
			  js_pesquisaCidadaoFamilia(false);
			}
		}
	}
}

function js_PreenchePesquisaTipoRetorno(aTipoRetorno){

	var iNumRows = aTipoRetorno.length;

	for (var i=0; i < iNumRows; i++){

		with (aTipoRetorno[i])  {
			var sCheckBox = 'tiporetorno'+aTipoRetorno[i].ov04_tiporetorno;
			$(sCheckBox).checked = true	;
		}
	}
}

function js_PreenchePesquisaCidadaoTelefones(aCidadaoTelefones){
	js_RenderGridTelefone(aCidadaoTelefones,false,0,false);
}

function js_PreenchePesquisaCidadaoEmails(aCidadaoEmails){
	js_RenderGridEmail(aCidadaoEmails,false,0,false);
}

function js_NovoTelefone(){

	oTelefone = eval('('+$('alteraTelefone').value+')');

	js_RenderGridTelefone(new Array(oTelefone),false,0,false);

  delete oTelefone;

  $('ov07_ddd').value = '';
  $('ov07_numero').value = '';
  $('ov07_ramal').value = '';
  $('ov07_obs').value = '';
  $('ov07_principal').value = 'f';
  $('ov07_ddd').focus();
  $('incluiTelefone').value = 'Incluir';
	$('novoTelefone').style.display = 'none';

}


</script>
<script type="text/javascript"><!--

$('ov02_nome').focus();
js_frmListaEmails();
js_frmListaTelefones();
/*
$('ov02_nome').tabIndex 			= 0;
$('ov02_ident').tabIndex 			= 1;
$('ov02_cnpjcpf').tabIndex 		= 2;
$('tiporetorno1').tabIndex 		= 3;
$('tiporetorno2').tabIndex 		= 4;
$('tiporetorno3').tabIndex 		= 5;
$('tiporetorno4').tabIndex 		= 6;
$('ancora_endereco').tabIndex	= 7;
$('ov02_endereco').tabIndex		= 8;
$('ov02_numero').tabIndex 		= 9;
$('ov02_bairro').tabIndex 		= 10;
$('ancora_bairro').tabIndex 	= 11;
$('ov02_compl').tabIndex 			= 12;
$('ov02_munic').tabIndex 			= 13;
$('ov02_uf').tabIndex 				= 14;
$('ov02_cep').tabIndex 				= 15;
$('ov08_email').tabIndex			= 16;
$('ov08_principal').tabIndex  = 17;
$('incluiEmail').tabIndex			= 18;
$('novoEmail').tabIndex				= 19;
$('ov07_tipotelefone').tabIndex	= 20;
$('ov07_principal').tabIndex	= 21;
$('ov07_ddd').tabIndex				= 22;
$('ov07_numero').tabIndex		= 23;
$('ov07_ramal').tabIndex			= 24;
$('ov07_obs').tabIndex				= 25;
$('incluiTelefone').tabIndex	= 26;
$('novoTelefone').tabIndex		= 27;
$('gridemails').tabIndex			= 200;
$('gridtelefones').tabIndex		= 201;
$('fieldset_cidadao').tabIndex = 201;
$('fieldset_carta').tabIndex	= 202;
$('fieldset_email').tabIndex	= 203;
$('fieldset_fax').tabIndex		= 204;
$('emailsbody').tabIndex		= 205;

alert($$("*[tabIndex==''"));
*/
//$('fieldset_cidadao').tabIndex		= 201;


//-->
function js_RenderGridTelefone(aTelefone,acao,indice,acaoBotao){
		var acaoBotao = acaoBotao;
		var iIndice 	= 0;
		var acao  		= acao;
		var aTemp			= oDBGridListaTelefones.aRows;

		oDBGridListaTelefones.clearAll(true);

		var iNumRows = aTemp.length;
		if(iNumRows > 0){
			aTemp.each(
				function (oTelefone,iInd){

					if(acao == true && indice == iInd){

					}else{
		   			var aRow										= new Array();
		   			aRow[0] = oTelefone.aCells[0].getContent();
						aRow[1] = oTelefone.aCells[1].getContent();
						aRow[2] = oTelefone.aCells[2].getContent();
		 				aRow[3] = oTelefone.aCells[3].getContent();
		 				aRow[4] = oTelefone.aCells[4].getContent();
		 				aRow[5] = js_btnAcoesGridTelefones(acaoBotao,iIndice);
		 				aRow[6] = oTelefone.aCells[6].getContent();
		 				aRow[7] = oTelefone.aCells[7].getContent();
		 				aRow[8] = oTelefone.aCells[8].getContent();

	 					oDBGridListaTelefones.addRow(aRow);
	 					iIndice++;
	 				}
				}
			);
		}

		var iNumRows = aTelefone.length;
		if(iNumRows > 0){
			var acaoBotao = false;
			aTelefone.each(
				function (oTelefone,iInd){

						var aRow		= new Array();

						aRow[0] = oTelefone.descricao.urlDecode();
						aRow[1] = oTelefone.ov07_ddd;
						aRow[2] = oTelefone.ov07_numero;
		 				aRow[3] = oTelefone.ov07_ramal;
		 				aRow[4] = oTelefone.descrprincipal.urlDecode();
		 				aRow[5] = js_btnAcoesGridTelefones(acaoBotao,iIndice);
		 				aRow[6] = oTelefone.ov07_obs.urlDecode();
		 				aRow[7] = oTelefone.ov07_tipotelefone;
		 				aRow[8] = oTelefone.ov07_principal;

	 					oDBGridListaTelefones.addRow(aRow);
	 					iIndice++;
	 			}

			);
		}
		oDBGridListaTelefones.renderRows();
	}

 /*
	acao true desabilita os botes
			 false habilita os botoes
	*/
 function js_btnAcoesGridTelefones(acaoBotao,iIndice){
 	var iIndice 	= iIndice;
 	var strBotoes = '';
 	if(acaoBotao){
 		strBotoes += '<input type="button" value="Alterar" onclick="js_alterarTelefone('+iIndice+')" disabled>';
 		strBotoes += '<input type="button" value="Excluir" onclick="js_excluirTelefone('+iIndice+')" disabled>';
 	}else{
 		strBotoes += '<input type="button" value="Alterar" onclick="js_alterarTelefone('+iIndice+')" >';
 		strBotoes += '<input type="button" value="Excluir" onclick="js_excluirTelefone('+iIndice+')" >';
 	}
 	return strBotoes;
 }

 function js_readGridTelefonePrincipal(){

 	var iNumRows = oDBGridListaTelefones.getNumRows();
		if(iNumRows > 0){
			for (var iInd=0; iInd < iNumRows; iInd++){
				if(oDBGridListaTelefones.aRows[iInd].aCells[8].getValue().trim() == 't') return true;
			}
		}
	return false;
 }

	function js_pesquisaCGM(iCgm){

	  var oPesquisar = new Object();
			  oPesquisar.numcgm  = iCgm;
			  oPesquisar.acao    = 'pesquisaCGM';

	  var sDados = Object.toJSON(oPesquisar);

	    js_divCarregando('Aguarde Carregando dados do CGM...','msgBox');

	    sUrl = 'ouv1_cidadao.RPC.php';
	    var sQuery = 'dados='+sDados;
	    var oAjax   = new Ajax.Request( sUrl, {
	                                            method: 'post',
	                                            parameters: sQuery,
	                                            onComplete: js_retornoPesquisaCGM
	                                          }
	                                  );

	}

	function js_retornoPesquisaCGM(oAjax){

	  js_removeObj("msgBox");

	  var aRetorno = eval("("+oAjax.responseText+")");
	  var sExpReg  = new RegExp('\\\\n','g');

	  if ( aRetorno.status == 1) {
	    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
	    return false;
	  }else{

	    js_PreenchePesquisaCidadao(aRetorno.cidadao);
	    js_PreenchePesquisaTipoRetorno(aRetorno.tiporetorno);
	    js_PreenchePesquisaCidadaoEmails(aRetorno.cidadaoemails);
	    js_PreenchePesquisaCidadaoTelefones(aRetorno.cidadaotelefones);
	  }
	}

-->

var oCboTipoFamiliar = new DBComboBox("CboTipoFamiliar", "oCboTipoFamiliar", null, "350px", 1);
oCboTipoFamiliar.setMultiple(false);
oCboTipoFamiliar.show($('ctnTipoFamiliar'));

function js_pesquisaTipoFamiliar() {

  var oParametro    = new Object();
  oParametro.exec   = 'buscaTipoFamiliar';
  js_divCarregando('Aguarde, pesquisando...', 'msgBox')
  var oAjax = new Ajax.Request('soc4_relatoriossociais.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoTipoFamiliar,
                               }
                              );
}

function js_retornoTipoFamiliar(oAjax) {

  oCboTipoFamiliar.clearItens();
	js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  if(oRetorno.status == 2 ) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

	oRetorno.tipoFamiliar.each(function(oTipoFamiliar, iContador) {

		if (oTipoFamiliar.iCodigo != 0) {

		  oCboTipoFamiliar.addItem(oTipoFamiliar.iCodigo, oTipoFamiliar.sDescricao.urlDecode());
		}
	});
}
js_pesquisaTipoFamiliar();

function js_ativaFieldsetCadastro(lAtiva) {

  if (lAtiva) {

    $('sResponsavelFamilia').disabled   = '';
    $('fieldset_cidadao').style.display = '';
    $('fieldset_carta').style.display   = '';
    $('fieldset_email').style.display   = '';
    $('fieldset_fax').style.display     = '';
    $('db_opcao').disabled              = '';
  } else {

    $('fieldset_cidadao').style.display = 'none';
    $('fieldset_carta').style.display   = 'none';
    $('fieldset_email').style.display   = 'none';
    $('fieldset_fax').style.display     = 'none';
    $('db_opcao').disabled              = 'disabled';
    oAbaAvaliacaoCidadao.lBloqueada     = true;
    oAbaAvaliacaoFamilia.lBloqueada     = true;
  }
}

function js_carregaDados(iCidadao) {

  var url                   = 'soc3_consultacidadao.RPC.php';
  var oObject               = new Object();
  oObject.exec              = "buscaAvaliacao";
  oObject.iCidadao          = iCidadao;
  js_divCarregando('Buscando ...','msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoCarregaDados
                                        }
                                   );
}

function js_retornoCarregaDados(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2) {
    alert(oRetorno.mesage.urlDecode());
  } else {

    oAvaliacaoEscola  = new dbViewAvaliacao(oRetorno.iCodigoAvaliacao, oRetorno.iCodigoGrupoRespostas, $('gridContainerCidadao'));
    oAvaliacaoEscola.show();

    $('btnSalvarPerguntas'+oRetorno.iCodigoAvaliacao).style.display = 'none';
    $('btnSalvarAvaliacao'+oRetorno.iCodigoAvaliacao).value         = 'Salvar';
  }
}

function js_carregaDadosFamilia(iCidadao) {

  var url                   = 'soc3_consultacidadao.RPC.php';
  var oObject               = new Object();
  oObject.exec              = "buscaAvaliacao";
  oObject.iCidadao          = iCidadao;
  oObject.lAvaliacaoFamilia = true;
  js_divCarregando('Buscando ...','msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoCarregaDadosFamilia
                                        }
                                   );
}

function js_retornoCarregaDadosFamilia(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2) {
    alert(oRetorno.mesage.urlDecode());
  } else {

    oAvaliacaoFamilia  = new dbViewAvaliacao(oRetorno.iCodigoAvaliacao, oRetorno.iCodigoGrupoRespostas, $('gridContainerFamilia'));
    oAvaliacaoFamilia.show();

    $('btnSalvarPerguntas'+oRetorno.iCodigoAvaliacao).style.display = 'none';
    $('btnSalvarAvaliacao'+oRetorno.iCodigoAvaliacao).value         = 'Salvar';
  }
}

//Arrays utilizados na grids de lista de emails e telefones

aLinhasEmails    = new Array();
aLinhasTelefones = new Array();

function js_selecionaResponsavelFamilia(iEscolha) {

  if (iEscolha == 0) {

    $('trConsultaFamilia').style.display = 'none';
    $('trTipoFamiliar').style.display    = 'none';
    $('as04_sequencial').value           = '';
    $('as04_sequencial').value           = '';
    $('sNomeResponsavelFamilia').value   = '';
    oCboTipoFamiliar.setValue(1);
    $('db_opcao').disabled              = 'disabled';
    oAbaAvaliacaoCidadao.lBloqueada     = true;
    oAbaAvaliacaoFamilia.lBloqueada     = true;
  } else if (iEscolha == 1) {

    $('trConsultaFamilia').style.display = 'none';
    $('trTipoFamiliar').style.display    = 'none';
    $('as04_sequencial').value           = '';
    $('sNomeResponsavelFamilia').value   = '';
    $('db_opcao').disabled               = false;
    oAbaAvaliacaoCidadao.lBloqueada      = false;
    oAbaAvaliacaoFamilia.lBloqueada      = false;
  } else if (iEscolha == 2) {

    $('db_opcao').disabled               = false;
    $('trConsultaFamilia').style.display = 'table-row';
    $('trTipoFamiliar').style.display    = 'table-row';
    oAbaAvaliacaoFamilia.lBloqueada      = true;
  }
}

function js_pesquisaCidadaoFamilia(lMostra) {

  var sUrl = 'func_cidadaofamiliacompleto.php?lSomenteResponsavel&lSomenteFamiliaVinculada&lFamilia';

  if (lMostra == true) {

    sUrl += '&funcao_js=parent.js_mostracidadaofamilia1|as04_sequencial|ov02_nome';
  	js_OpenJanelaIframe('', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', true);
  } else {

   sUrl += '&funcao_js=parent.js_mostracidadaofamilia&pesquisa_chave='+$F('as04_sequencial');
  	js_OpenJanelaIframe('', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', false);
  }
}

function js_mostracidadaofamilia() {

  if (arguments[0] === true) {

    $('as04_sequencial').value         = '';
    $('sNomeResponsavelFamilia').value = arguments[1];
    return false;
  }

	$('sNomeResponsavelFamilia').value = arguments[2];

	db_iframe_cidadaofamilia.hide();

  if ($('db_opcao').name != 'incluir') {
    $('sResponsavelFamilia').disabled = true;
  }

}

function js_mostracidadaofamilia1(iSequencial, sNome, iCodigoFamilia, iNis) {

	$('as04_sequencial').value         = iSequencial;
	$('sNomeResponsavelFamilia').value = sNome;

	db_iframe_cidadaofamilia.hide();
}

/**
 * Valida se eh um departamento CRAS ou CREAS, para bloqueio do formulario
 */
if (!lDepartamentoCrasCreas && lTelaSocial) {

  setFormReadOnly($('frmCidadao'), true);
  $('ancora_endereco').setAttribute("onclick", "");
  $('ancora_endereco').setAttribute("style", "text-decoration: none; color: #000;");
  $('ancora_bairro').setAttribute("onclick", "");
  $('ancora_bairro').setAttribute("style", "text-decoration: none; color: #000;");
}
</script>
