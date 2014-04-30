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

//MODULO: saude
$oDaoCgsUnd->rotulo->label();
?>
<form name="form1" method="post" action="">
	<center>
	<fieldset><legend><b>Fatores de Risco</b></legend>
	<table border="0">
		<tr>
			<td>
				<b>Não Lançados:</b><br>
				<select name="fatorderisco" id="fatorderisco" size="10" onclick="js_desabinc()" 
          style="font-size:9px;width:330px;height:120px" multiple>
				<?
        $sSql           = $oDaoSauFatorDeRisco->sql_query(null, '*', 's105_v_descricao', 
                                                          's105_i_codigo not in '.
                                                          '(select s106_i_fatorderisco from cgsfatorderisco '.
                                                          "where s106_i_cgs = $chavepesquisacgs) "
                                                         );
				$rsFatorDeRisco = $oDaoSauFatorDeRisco->sql_record($sSql);
				if ($oDaoSauFatorDeRisco->numrows > 0) {

					for ($i = 0; $i < $oDaoSauFatorDeRisco->numrows; $i++) {

						$oFatorDeRisco = db_utils::fieldsMemory($rsFatorDeRisco,$i);
						echo "<option value={$oFatorDeRisco->s105_i_codigo}>{$oFatorDeRisco->s105_v_descricao}</option>";

					}

				}
				?>
				</select>
			</td>
			<td valign="middle">
				<p><p>
				<input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" 
          class="estiloBotaoSelect" disabled><p>
				<input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
          class="estiloBotaoSelect" disabled><p>
				<input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
          class="estiloBotaoSelect" disabled><p>
				<input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
          class="estiloBotaoSelect" disabled><p>
				<input name="codigosfatores" type="hidden">
				
			</td>
			<td>
				<b>Lançados:</b><br>
				<select name="cgsfatorderisco" id="cgsfatorderisco" size="10" onclick="js_desabexc()" 
          style="font-size:9px;width:330px;height:120px" multiple>
				<?
        $sSql              = $oDaoCgsFatorDeRisco->sql_query(null, '*', 's105_v_descricao', 
                                                             "s106_i_cgs = $chavepesquisacgs"
                                                            );
				$rsCgsFatorDeRisco = $oDaoCgsFatorDeRisco->sql_record($sSql);
				if ($oDaoCgsFatorDeRisco->numrows > 0) {

					for ($i = 0; $i < $oDaoCgsFatorDeRisco->numrows; $i++) {

						$oCgsFatorDeRisco = db_utils::fieldsMemory($rsCgsFatorDeRisco,$i);
						echo "<option value={$oCgsFatorDeRisco->s105_i_codigo}>{$oCgsFatorDeRisco->s105_v_descricao}</option>";

					}

				}
				?>
				</select>			
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<?
				$z01_t_obs = !isset($z01_t_obs)?' ':$z01_t_obs;
				db_textarea('z01_t_obs', 3, 95, @$z01_t_obs, true, 'text', $db_opcao, "disabled");
        db_input('chavepesquisacgs', 10, '', true, 'hidden', 3, '');
				?>
			</td>
		</tr>
	</table>
	</fieldset>	
</center>
<p>
<input name="editar" type="button" id="editar" value="Editar" onclick="js_editar();">
<input name="botao_ok" type="submit" id="botao_ok" value="Fechar" onclick="js_botao_ok();">
</form>

<script>
function js_editar() {

	obj = document.form1;
	if ( obj.editar.value == "Editar" ) {

		obj.z01_t_obs.disabled = false;
		//obj.incluirum.disabled = false;
		obj.incluirtodos.disabled = false;
		//obj.excluirum.disabled = false;
		//obj.excluirtodos.disabled = false;
		obj.z01_t_obs.focus();
		obj.editar.value = "Cancelar";
		obj.botao_ok.value = "Ok";
		
	} else {

		obj.z01_t_obs.disabled = true;
		obj.incluirum.disabled = true;
		obj.incluirtodos.disabled = true;
		obj.excluirum.disabled = true;
		obj.excluirtodos.disabled = true;
		
		obj.editar.value = "Editar";
		obj.reset();
		obj.botao_ok.value = "Fechar";

	}

}
function js_botao_ok() {

	obj = document.form1;
	if ( obj.botao_ok.value == "Fechar" ) {

		parent.db_iframe_fatoresderisco.hide();
		if ( parent.document.getElementById('framefatorderisco') != undefined ) {
			parent.document.getElementById('framefatorderisco').contentDocument.location.reload(true);
		}

	} else {

    var Tam     = obj.cgsfatorderisco.length;
    var codigos = '';
    var sep     = '';
    for (x = 0; x < Tam; x++) {

    	codigos += sep+obj.cgsfatorderisco.options[x].value;
    	sep      = ',';

    }	
		obj.codigosfatores.value = codigos;
		obj.submit();

	}

}

function js_incluir() {

	var Tam = document.form1.fatorderisco.length;
	var F   = document.form1;
	for (x = 0; x < Tam; x++) {

		if (F.fatorderisco.options[x].selected == true) {

			F.elements['cgsfatorderisco'].options[F.elements['cgsfatorderisco'].options.length] = new Option(
                                                                           F.fatorderisco.options[x].text,
                                                                           F.fatorderisco.options[x].value);
			F.fatorderisco.options[x] = null;
			Tam--;
			x--;

		}

	}

	if (document.form1.fatorderisco.length>0) {
		document.form1.fatorderisco.options[0].selected = true;
	} else {

		document.form1.incluirum.disabled    = true;
		document.form1.incluirtodos.disabled = true;

	}
	document.form1.excluirtodos.disabled = false;
	document.form1.fatorderisco.focus();

}

function js_incluirtodos() {

	var Tam = document.form1.fatorderisco.length;
	var F   = document.form1;
	for (i = 0; i < Tam; i++) {

		F.elements['cgsfatorderisco'].options[F.elements['cgsfatorderisco'].options.length] = new Option(
                                                                         F.fatorderisco.options[0].text,
                                                                         F.fatorderisco.options[0].value);
		F.fatorderisco.options[0] = null;

	}
	document.form1.incluirum.disabled    = true;
	document.form1.incluirtodos.disabled = true;
	document.form1.excluirtodos.disabled = false;
	document.form1.cgsfatorderisco.focus();

}

function js_excluir() {

	var F   = document.getElementById("cgsfatorderisco");
	var Tam = F.length;
	for (x = 0; x < Tam; x++) {

		if (F.options[x].selected == true) {

			document.form1.fatorderisco.options[document.form1.fatorderisco.length] = new Option(F.options[x].text,
                                                                                           F.options[x].value
                                                                                          );
			F.options[x] = null;
			Tam--;
			x--;

		}

	}

	if (document.form1.cgsfatorderisco.length>0) {
		document.form1.cgsfatorderisco.options[0].selected = true;
	} else {

		document.form1.excluirum.disabled    = true;
		document.form1.excluirtodos.disabled = true;
		document.form1.incluirtodos.disabled = false;

	}
	document.form1.cgsfatorderisco.focus();

}


function js_excluirtodos() {

	var Tam = document.form1.cgsfatorderisco.length;
	var F   = document.getElementById("cgsfatorderisco");
	for (i = 0; i < Tam; i++) {

		document.form1.fatorderisco.options[document.form1.fatorderisco.length] = new Option(F.options[0].text,
                                                                                         F.options[0].value
                                                                                        );
		F.options[0] = null;

	}
	if (F.length == 0) {

		document.form1.excluirum.disabled    = true;
		document.form1.excluirtodos.disabled = true;
		document.form1.incluirtodos.disabled = false;

	}
	document.form1.fatorderisco.focus();

}

function js_desabinc() {

	for (i = 0; i < document.form1.fatorderisco.length; i++) {

		if (document.form1.fatorderisco.length > 0 && document.form1.fatorderisco.options[i].selected) {

			if (document.form1.cgsfatorderisco.length>0) {
				document.form1.cgsfatorderisco.options[0].selected = false;
			}
			if (document.form1.botao_ok.value == "Ok") {

				document.form1.incluirum.disabled = false;
				document.form1.excluirum.disabled = true;

			}

		}

	}
  
}
function js_desabexc() {

	for (i = 0; i < document.form1.cgsfatorderisco.length; i++) {

		if (document.form1.cgsfatorderisco.length > 0 && document.form1.cgsfatorderisco.options[i].selected) {
			if (document.form1.fatorderisco.length > 0) {
				document.form1.fatorderisco.options[0].selected = false;
			}
			if (document.form1.botao_ok.value == "Ok") {

				document.form1.incluirum.disabled = true;
				document.form1.excluirum.disabled = false;

			}

		}

	}

}

</script>