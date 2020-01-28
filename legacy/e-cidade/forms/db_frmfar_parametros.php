<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: Farmácia
$oDaoFarParametros->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("db77_descr");
$oRotulo->label("fa12_c_descricao");

//verifica se a opção da numeração pelo material esta Disponivel para seleção
$sSql = $oDaoFarMaterSaude->sql_query_file(null, ' count(*) as total ');
$rs   = $oDaoFarMaterSaude->sql_record($sSql);
if ($oDaoFarMaterSaude->numrows == 1) {
  $oDados = db_utils::fieldsmemory($rs, 0);
} else {

  echo "<script>alert('Erro Técnico - Verifique a tabela far_matersaude');</script>";
  $iLibera = 3;

}
if ($oDados->total == 0) {
  $iLibera = 1;
} else {
  $iLibera = 3;
}
?>
<form name="form1" method="post" action="">
<center>
<div style="display: table;">
<fieldset><legend><b>Inclusão de Parâmetros</b></legend>
<div id='divScroll' style="width: 100%; height: 100%; overflow: scroll; overflow-x: hidden">
<table>
	<tr>
		<td>
		<fieldset class='fieldsetSeparator'><legend><b>Parâmetros Gerais</legend>
		<table border="0">
			<tr>
				<td nowrap title="<?=@$Tfa02_i_codigo?>">
                  <?=@$Lfa02_i_codigo?>
                </td>
				<td>
                  <?
                  db_input('fa02_i_codigo', 5, $Ifa02_i_codigo, true, 'text', 3, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_dbestrutura?>">
                  <?
                  db_ancora(@$Lfa02_i_dbestrutura, "js_pesquisafa02_i_dbestrutura(true);", $db_opcao1);
                  ?>
                </td>
				<td>
                  <?
                  db_input('fa02_i_dbestrutura', 5, $Ifa02_i_dbestrutura, true, 'text', $db_opcao1,
                           " onchange='js_pesquisafa02_i_dbestrutura(false);'"
                          );
                  db_input('db77_descr', 40, $Idb77_descr, true, 'text', 3, '');
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_c_descr?>">
                  <?=@$Lfa02_c_descr?>
                </td>
				<td>
                  <?
                  db_input('fa02_c_descr', 49, $Ifa02_c_descr, true, 'text', $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_c_digitacao?>"><b>Digitação
				Medicamentos</b></td>
				<td>
                  <?
                  $aX = array("S"=>"SIM", "N"=>"NÃO");
                  db_select('fa02_c_digitacao', $aX, true, $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_b_numestoque?>">
                  <?=@$Lfa02_b_numestoque?>
                </td>
				<td>
                  <?
                  $aX = array("f"=>"NÃO", "t"=>"SIM");
                  db_select('fa02_b_numestoque', $aX, true, $iLibera, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_b_novaretirada?>">
                  <?=@$Lfa02_b_novaretirada?>
                </td>
				<td>
                  <?
                  $aX = array("f"=>"NÃO", "t"=>"SIM");
                  db_select('fa02_b_novaretirada', $aX, true, $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_origemreceita?>">
                  <?=@$Lfa02_i_origemreceita?>
                </td>
				<td>
                  <?
                  $aX = array('1'=>'SIM', '2'=>'NÃO');
                  db_select('fa02_i_origemreceita', $aX, true, $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_acumularsaldocontinuado?>">
                  <?=@$Lfa02_i_acumularsaldocontinuado?>
                </td>
				<td>
                  <?
                  $aX = array('1' => 'SIM', '2' => 'N&Atilde;O');
                  db_select('fa02_i_acumularsaldocontinuado', $aX, true, 1);
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_cursor?>">
                  <?=@$Lfa02_i_cursor?>
                </td>
				<td>
                  <?
                  $aX = array('1' => 'CGS', '2' => 'Cartão SUS', '3' => 'Nome');
                  db_select('fa02_i_cursor', $aX, true, 1);
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_validalote?>">
                  <?=@$Lfa02_i_validalote?>
                </td>
				<td>
                  <?
                  $aX = array('2' => 'NÃO', '1' => 'SIM');
                  db_select('fa02_i_validalote', $aX, true, 1);
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_validavencimento?>">
                  <?=@$Lfa02_i_validavencimento?>
                </td>
				<td>
                  <?
                  $aX = array('1' => 'Não validar', '2' => 'Somente alertar', '3' => 'Alertar e Bloquear');
                  db_select('fa02_i_validavencimento', $aX, true, 1);
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_acaoprog?>">
                  <?=db_ancora(@$Lfa02_i_acaoprog, "js_pesquisafa02_i_acaoprog(true);", $db_opcao);?>
                </td>
				<td>
                  <?
                  db_input('fa02_i_acaoprog', 10, '', true, 'text', $db_opcao,
                           " onchange='js_pesquisafa02_i_acaoprog(false);' "
                          );
                  db_input('fa12_c_descricao', 50, $Ifa12_c_descricao, true, 'text', 3, '');
                  ?>
                </td>
			</tr>
			<tr>


			<tr>
				<td nowrap title="<?=@$Tfa02_i_avisoretirada?>">
                  <?=@$Lfa02_i_avisoretirada?>
                </td>
				<td>
                  <?
                  if (!isset($fa02_i_avisoretirada)) {
                    $fa02_i_avisoretirada  = 0;
                  }
                  db_input('fa02_i_avisoretirada', 5, $Ifa02_i_avisoretirada, true, 'text', $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_verificapacientehiperdia?>">
                  <?=@$Lfa02_i_verificapacientehiperdia?>
                </td>
				<td>
                  <?
                  $aX = array('2' => 'NÃO', '1' => 'SIM');
                  db_select('fa02_i_verificapacientehiperdia', $aX, true, 1);
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_i_numdiasmedcontinativo?>">
                  <?=@$Lfa02_i_numdiasmedcontinativo?>
                </td>
				<td>
                  <?
                  if (!isset($fa02_i_numdiasmedcontinativo)) {
                    $fa02_i_numdiasmedcontinativo  = 0;
                  }
                  db_input('fa02_i_numdiasmedcontinativo', 5, $Ifa02_i_numdiasmedcontinativo, true, 'text', $db_opcao, "");
                  ?>
                </td>
			</tr>
		</table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td>
		<fieldset class='fieldsetSeparator'><legend><b>Comprovante de Entrega</b></legend>
		<table>
			<tr>
				<td nowrap title="<?=@$Tfa02_b_comprovante?>">
                  <?=@$Lfa02_b_comprovante?>
                </td>
				<td>
                  <?
                  $aX = array("f"=>"NÃO", "t"=>"SIM");
                  db_select('fa02_b_comprovante', $aX, true, $db_opcao, "");
                  ?>
                </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfa02_utilizaimpressoratermica?>">
                 <?=@$Lfa02_utilizaimpressoratermica?>
                </td>
				<td>
                 <?
                 if (isset($fa02_utilizaimpressoratermica)) {
                   $fa02_utilizaimpressoratermica = $fa02_utilizaimpressoratermica == 't' ? 'true' : 'false';
                 }
                 $aUtilizaImpressora = array('false' => 'NÃO', 'true' => 'SIM');
                 db_select('fa02_utilizaimpressoratermica', $aUtilizaImpressora, true, 1);
                 ?>
                </td>
			</tr>
		</table>
		</fieldset>
		</td>
	<tr>
		<td nowrap colspan='2'>
		<fieldset class='fieldsetSeparator'><legend><b>Pr&oacute;xima Retirada</b></legend>
		<table border="0">
			<tr>
				<td nowrap>
                  <?
                  $sC1 = $sC2 = '';
                  if (@$fa02_i_tipoperiodocontinuado == 1) {
                    $sC1 = 'checked';
                  } else if (@$fa02_i_tipoperiodocontinuado == 2) {
                    $sC2 = 'checked';
                  }
                  ?>
                  <input type='radio'
					name='fa02_i_tipoperiodocontinuado' <?=$sC1?> value='1'> &Agrave;
				partir da validade inicial no cadastro de continuado <input
					type='radio' name='fa02_i_tipoperiodocontinuado' <?=$sC2?>
					value='2'> &Agrave; partir da data da &uacute;ltima retirada</td>
			</tr>
		</table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td height="18">&nbsp;</td>
		<td height="18">&nbsp;</td>
	</tr>
</table>
</div>
</fieldset>
  <center>
    <input
  	name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
  	type="submit" id="db_opcao"
  	value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
  	<?=($db_botao == false ? "disabled" : "")?>>
  </center>
</div>
</form>

<script>

function js_pesquisafa02_i_acaoprog(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_far_programa', 'func_far_programa.php?funcao_js='+
                        'parent.js_mostrafar_programa1|fa12_i_codigo|fa12_c_descricao',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.fa02_i_acaoprog.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_far_programa', 'func_far_programa.php?pesquisa_chave='+
                          document.form1.fa02_i_acaoprog.value+'&funcao_js=parent.js_mostrafar_programa',
                          'Pesquisa', false
                         );

    } else {
      document.form1.fa12_c_descricao.value = '';
    }

  }

}
function js_mostrafar_programa(chave, erro) {

  document.form1.fa12_c_descricao.value = chave;
  if (erro == true) {

    document.form1.fa02_i_acaoprog.focus();
    document.form1.fa02_i_acaoprog.value = '';

  }

}
function js_mostrafar_programa1(chave1, chave2) {

  document.form1.fa02_i_acaoprog.value  = chave1;
  document.form1.fa12_c_descricao.value = chave2;
  db_iframe_far_programa.hide();

}
function js_pesquisafa02_i_dbestrutura(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_db_estrutura', 'func_db_estrutura.php?funcao_js='+
                        'parent.js_mostradb_estrutura1|db77_codestrut|db77_descr', 'Pesquisa', true
                       );

  } else {

     if (document.form1.fa02_i_dbestrutura.value != '') {

        js_OpenJanelaIframe('', 'db_iframe_db_estrutura', 'func_db_estrutura.php?pesquisa_chave='+
                            document.form1.fa02_i_dbestrutura.value+'&funcao_js=parent.js_mostradb_estrutura',
                            'Pesquisa', false
                           );

     } else {
       document.form1.db77_descr.value = '';
     }

  }

}
function js_mostradb_estrutura(chave, erro) {

  document.form1.db77_descr.value = chave;
  if (erro == true) {

    document.form1.fa02_i_dbestrutura.focus();
    document.form1.fa02_i_dbestrutura.value = '';

  }

}
function js_mostradb_estrutura1(chave1, chave2) {

  document.form1.fa02_i_dbestrutura.value = chave1;
  document.form1.db77_descr.value         = chave2;
  db_iframe_db_estrutura.hide();

}
$('divScroll').style.height = document.viewport.getDimensions().height - 80;
</script>