<?php
/**
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

?>
<!-- UPS -->
<tr>
	<td nowrap title="<?=@$Tsd24_i_unidade?>">
		<?=@$Lsd24_i_unidade?>
	</td>
	<td colspan=2>
		<?
		db_input('campoFocado',10,@$campoFocado,true,'hidden',3,"tabIndex='0' ");

		$strWhere = $objSau_config->s103_i_departamentos == 1 ? "":"db_depusu.id_usuario = ".db_getsession("DB_id_usuario");
		$result = $clunidades->sql_record( $clunidades->sql_query_ext("","distinct unidades.sd02_i_codigo, db_depart.descrdepto", "descrdepto",$strWhere) );
		//se gerou codigo do lote bloqueia unidade
		$db_opcaounidade = isset($sd58_i_codigo)&&(int)$sd58_i_codigo>0?3:$db_opcao;
		db_selectrecord('sd24_i_unidade',$result,true,$db_opcaounidade,"onFocus='js_foco(this, \"sd24_i_codigo\");'",'','','',"js_limpadados();");
		?>
	</td>
</tr>

<tr>
	<td colspan="3">
		<div id="divFAA">
		<table>

			<!-- FAA -->
			<tr>
				<td nowrap title="<?=@$Tsd24_i_codigo?>" width="80">
					<?
					db_ancora(@$Lsd24_i_codigo,"js_pesquisasd24_i_codigo(true);",$db_opcao);
					?>
				</td>
				<td colspan=3>
					<?
					db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',($db_opcao==1?1:3),"onchange='js_pesquisasd24_i_codigo(false)'; onFocus='js_foco(this, \"z01_v_nome\");' ")
					?>
				</td>
			</tr>

			<!--  CGS / Nome -->
			<tr>
				<td nowrap title="<?=@$Tz01_i_cgsund?>">
					<?
					$db_opcaocgs = isset($sd24_i_codigo)&&(int)$sd24_i_codigo>0?3:$db_opcao;
					db_ancora(@$Lz01_i_cgsund,"js_pesquisaz01_i_cgsund(true);",1, "", "ancCGS");
					?>
				</td>
				<td colspan=3>
					<?
					db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',3);
					//colocado onblur, pois versão 2 do firefox não estava respeitando o onchange
					db_input('z01_v_nome',49,$Iz01_v_nome,true,'text',$db_opcao,"onchange='js_pesquisaz01_v_nome(false);' onblur='js_pesquisaz01_v_nome(false);' onFocus='js_foco(this, \"sd24_t_diagnostico\");' ");
					?>
				</td>
			</tr>

			<!--  Nascimento / Sexo -->
			<tr>
				<td nowrap title="<?=@$Tz01_d_nasc?>">
					<?=@$Lz01_d_nasc?>
				</td>
				<td colspan=3>
					<?
					  db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,"text\"  onFocus='js_foco(this, \"sd70_c_cid\");' ",3, "onchange='js_atualizarIdade();'", "", "", "parent.js_atualizarIdade();");
					  $iIdade = "";
					  db_input('iIdade', 44, @$IiIdade, true, 'text', 3);
					?>
				</td>
			</tr>

			<!-- CID
			<tr>
				<td nowrap title="<?=@$Tsd70_c_cid?>" valign="top" align="top">
					<?
					db_ancora(@$Lsd70_c_cid,"js_pesquisasd70_c_cid(true); \" onFocus='js_foco(this, \"sd24_t_diagnostico\");' ",$db_opcao);
					?>
				</td>
				<td valign="top" align="top" colspan=3>
					<?
					db_input('sd55_i_cid',10,$Isd55_i_cid,true,'hidden',$db_opcao);
					db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);' onFocus='js_foco(this, \"sd24_t_diagnostico\");' ");
					db_input('sd70_c_nome',48,$Isd70_c_nome,true,'text',3,"tabIndex='0' ");
					?>
				</td>
			</tr>
			-->

			<!-- DIAGNOSTICO -->
			<tr>
				<td valign="top" nowrap title="<?=@$Tsd24_t_diagnostico?>">
					<?=@$Lsd24_t_diagnostico?>
				</td>
				<td colspan="3">
					<?
					$botao = ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"));
					db_textarea('sd24_t_diagnostico',1,58,@$sd24_t_diagnostico,true,'text',$db_opcao," onFocus='js_foco(this, \"db_opcao\");' ");
					?>
				</td>
			</tr>
		</table>
		</div>
	</td>
</tr>
<script>

  if (document.form1.z01_d_nasc.value != '' && document.form1.z01_d_nasc.value != undefined) {
    js_atualizarIdade();
  }

  function js_atualizarIdade() {

    var dNascimento  = new wsDate($('z01_d_nasc').value);
	  if (dNascimento != 'undefined') {

		  var dDataAtual   = new Date().getDate() + "/" + (parseInt(new Date().getMonth(), 10) + 1);
		  dDataAtual      += "/" + new Date().getFullYear();

		  if (!dNascimento.thisHigher(dDataAtual)) {

        $('iIdade').value = dNascimento.getAge();

	    } else {

	      alert("Data de Nascimento inválida!");
	      $('iIdade').value     = "";
	      $('z01_d_nasc').value = "";
	      $('z01_d_nasc').focus();

	    }

	  }

  }

</script>