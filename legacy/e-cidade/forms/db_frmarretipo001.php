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

//MODULO: caixa
$clarretipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k03_tipo");
?>
<form name="form1" method="post" action="">
<div id="container">

  <div id="parametros">
  <fieldset class="fieldsetPrincipal">
    <legend>
      Cadastro Tipo de Débito
    </legend>

		<table width="100%">
		  <tr>
		    <td title="<?=@$Tk00_tipo?>">
		      <?=@$Lk00_tipo?>
		    </td>
		    <td>
				  <?
				    db_input('k00_tipo',10,$Ik00_tipo,true,'text',3,"")
				  ?>
		    </td>
		    <td title="<?=@$Tk00_marcado?>">
					<?=@$Lk00_marcado?>
		    </td>
		    <td>
					<?
					  $x = array('t'=>'Marcado','f'=>'Desmarcado');
					  db_select('k00_marcado',$x,true,$db_opcao,"");
					?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_codbco?>">
		       <?=@$Lk00_codbco?>
		    </td>
		    <td>
				  <?
				    db_input('k00_codbco',10,$Ik00_codbco,true,'text',$db_opcao,"")
				  ?>
		    </td>
		    <td title="<?=@$Tk00_codage?>">
		       <?=@$Lk00_codage?>
		    </td>
		    <td>
				  <?
				    db_input('k00_codage',10,$Ik00_codage,true,'text',$db_opcao,"")
				  ?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_emrec?>">
		       <?=@$Lk00_emrec?>
		    </td>
		    <td>
				  <?
				    $x = array("f"=>"NAO","t"=>"SIM");
				    db_select('k00_emrec',$x,true,$db_opcao,"");
				  ?>
		    </td>
		    <td title="<?=@$Tk00_agnum?>">
		       <?=@$Lk00_agnum?>
		    </td>
		    <td >
				  <?
				    $x = array("f"=>"NAO","t"=>"SIM");
				    db_select('k00_agnum',$x,true,$db_opcao,"");
				  ?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_agpar?>">
		       <?=@$Lk00_agpar?>
		    </td>
		    <td >
				  <?
				    $x = array("f"=>"NAO","t"=>"SIM");
				    db_select('k00_agpar',$x,true,$db_opcao,"");
				  ?>
		    </td>
		    <td title="<?=@$Tk00_txban?>">
		       <?=@$Lk00_txban?>
		    </td>
		    <td >
					<?
					  db_input('k00_txban',10,$Ik00_txban,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_rectx?>">
		       <?=@$Lk00_rectx?>
		    </td>
		    <td >
					<?
					  db_input('k00_rectx',10,$Ik00_rectx,true,'text',$db_opcao,"")
					?>
		    </td>
		        <td title="<?=@$Tk00_vlrmin?>">
		           <?=@$Lk00_vlrmin?>
		        </td>
		        <td >
		    			<?
		    			  db_input('k00_vlrmin',10,$Ik00_vlrmin,true,'text',$db_opcao,"")
		    			?>
		        </td>		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_impval?>">
		       <?=@$Lk00_impval?>
		    </td>
		    <td>
					<?
						$x = array("f"=>"NAO","t"=>"SIM");
						db_select('k00_impval',$x,true,$db_opcao,"");
					?>
		    </td>
  	    <input name="codmodelo" type="hidden" value="0"></input>

		  </tr>

		  <tr>
		    <td>
		       <b>Libera emissão de recibo DBpref</b>
		    </td>
		    <td colspan="3">
			  <?
					$lib = array("1"=>"Emissão liberada",
					             "2"=>"Mostrar débito e não emitir recibo",
			                 "3"=>"Não mostrar débito e não emitir recibo");
					db_select('k00_recibodbpref',$lib,true,$db_opcao, "style='width: 400px'");
				?>
		    </td>
		  </tr>

		  <tr>
		    <td>
		       <b>Tipo de agrupamento </b>
		    </td>
		    <td colspan="3">
				  <?
						$arrayTipoAgrup = array("1"=>"Nenhum","2"=>"Parcial","3"=>"Total");
						db_select('k00_tipoagrup',$arrayTipoAgrup,true,$db_opcao, "style='width: 400px'");
					?>
		    </td>
		  </tr>

		  <tr>
		    <td>
		      <?=@$Lk00_formemissao?>
		    </td>
		    <td colspan="3">
		    <?
		      $aFormEmissao = array("2" => "Com valores atualizados",
		                            "1" => "Com valores originais",
		                            "3" => "Ambos");
		      db_select('k00_formemissao', $aFormEmissao, true, $db_opcao, "style='width: 400px'");
		    ?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk03_tipo?>">
		       <?
		       db_ancora(@$Lk03_tipo,"js_pesquisak03_tipo(true);",$db_opcao);
		       ?>
		    </td>
		    <td colspan="3">
			  	<?
			  		db_input('k03_tipo',10,$Ik03_tipo,true,'text',$db_opcao," onchange='js_pesquisak03_tipo(false);'");
			  		db_input('tipodescr',40,$Ik03_tipo,true,'text',3,'');
					?>
		    </td>
		  </tr>

      <tr>
        <td title="<?=@$Tk00_receitacredito?>">
           <?
           db_ancora(@$Lk00_receitacredito,"js_pesquisak00_receitacredito(true);",$db_opcao);
           ?>
        </td>
        <td colspan="3">
          <?
            db_input('k00_receitacredito' ,10,$Ik00_receitacredito,true,'text',$db_opcao," onchange='js_pesquisak00_receitacredito(false);'");
            db_input('receitacreditodescr',40,$Ik00_receitacredito,true,'text',3,'');
          ?>
        </td>
      </tr>

		  <tr>
		    <td title="<?=@$Tk00_descr?>" >
		       <?=@$Lk00_descr?>
		    </td>
		    <td colspan="3">
			  <?
			  db_input('k00_descr',40,$Ik00_descr,true,'text',$db_opcao,"")
			  ?>
		    </td>
		  </tr>

			<tr>
				<td title="<?=@$Tk00_tercdigcarneunica?>">
				  <?=@$Lk00_tercdigcarneunica?>
				</td>
				<td colspan="3">
					<?
						db_input('k00_tercdigcarneunica',10,$Ik00_tercdigcarneunica,true,'text',$db_opcao,"onkeyup='js_controladig3(this.name);'")
					?>
				</td>
			</tr>

			<tr>
		    <td title="<?=@$Tk00_tercdigcarnenormal?>">
		      <?=@$Lk00_tercdigcarnenormal?>
		    </td>
		    <td colspan="3">
					<?
						db_input('k00_tercdigcarnenormal',10,$Ik00_tercdigcarnenormal,true,'text',$db_opcao,"onkeyup='js_controladig3(this.name);'")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_tercdigrecunica?>">
					<?=@$Lk00_tercdigrecunica?>
		    </td>
		    <td colspan="3">
					<?
						db_input('k00_tercdigrecunica',10,$Ik00_tercdigrecunica,true,'text',$db_opcao,"onkeyup='js_controladig3(this.name);'")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_tercdigrecnormal?>">
		       <?=@$Lk00_tercdigrecnormal?>
		    </td>
		    <td colspan="3">
				<?
					db_input('k00_tercdigrecnormal',10,$Ik00_tercdigrecnormal,true,'text',$db_opcao,"onkeyup='js_controladig3(this.name);'")
				?>
		    </td>
		  </tr>

		  <tr>
		    <td title="<?=@$Tk00_exercicioscarne?>">
		       <?=@$Lk00_exercicioscarne?>
		    </td>
		    <td colspan="3">
					<?php
						db_input('k00_exercicioscarne',10, $Ik00_exercicioscarne,true, 'text', $db_opcao)
					?>
		    </td>
		  </tr>

		</table>
	</fieldset>
  </div>

  <div id="mensagens">
  <fieldset class="fieldsetPrincipal">
  	<legend>Mensagens</legend>

  		<fieldset class="fieldsetSecundario">
				<legend>Mensagens Cota Única</legend>
				<table width="100%">

					<tr>
						<td title="<?=@$Tk00_msguni?>">
							<strong>Guia Caixa/Prefeitura: </strong>
			    	</td>
			    	<td>
				  	<?
				  		db_textarea('k00_msguni',0,50,$Ik00_msguni,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"u\");'")
				  	?>
						<div id='u'></div>
			    	</td>
					</tr>

					<tr>
	          <td title="<?=@$Tk00_msguni2?>">
							<strong>Guia Contribuinte: </strong>
	          </td>
			    	<td>
						  <?
						  	db_textarea('k00_msguni2',0,50,$Ik00_msguni2,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"u2\");'")
						  ?>
							<div id='u2'></div>
			    	</td>
			  	</tr>
			  </table>

			</fieldset>

		  <fieldset class="fieldsetSecundario">
			  <legend>
				  Mensagens parcelas
			  </legend>
				<table width="100%">

					<tr>
						<td title="<?=@$Tk00_msgparc?>">
						  <strong>Guia Contribuinte: </strong>
						</td>
						<td>
							<?
								db_textarea('k00_msgparc',0,50,$Ik00_msgparc,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"p\");'")
							?>
							<div id='p'> </div>
						</td>
					</tr>

					<tr>
						<td title="<?=@$Tk00_msgparc2?>">
							<strong>Guia Caixa/Prefeitura: </strong>
						</td>
						<td>
							<?
								db_textarea('k00_msgparc2',0,50,$Ik00_msgparc2,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"p2\");'")
							?>
							<div id='p2'>  </div>
						</td>
					</tr>

				</table>

		</fieldset>

		<fieldset class="fieldsetSecundario">
			<legend>Mensagens parcelas vencidas</legend>
			<table width="100%">
				<tr>
					<td title="<?=@$Tk00_msgparcvenc?>">
			    	    <b>Guia Contribuinte: </b>
					</td>
					<td>
						<?
						db_textarea('k00_msgparcvenc',0,50,$Ik00_msgparcvenc,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"pv\");'")
						?>
						<div id='pv'>  </div>
					</td>
				</tr>
				<tr>
					<td title="<?=@$Tk00_msgparcvenc2?>">
			    	    <b>Guia Caixa/Prefeitura:  </b>
					</td>
					<td>
						<?
						db_textarea('k00_msgparcvenc',0,50,$Ik00_msgparcvenc2,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"pv2\");'",'k00_msgparcvenc2')
						?>
						<div id='pv2'>  </div>
					</td>
				</tr>

			</table>

		</fieldset>

		<fieldset class="fieldsetSecundario">
			<legend>Mensagem Recibo</legend>
		  <table width=100%>

		  <tr>
		    <td title="<?=@$Tk00_msgrecibo?>">
		       <?=@$Lk00_msgrecibo?>&nbsp;
		    </td>
		    <td>
			  <?
			  	db_textarea('k00_msgrecibo',0,50,$Ik00_msgrecibo,true,'text',$db_opcao,"onkeyup='js_controlatextarea(this.name,150,\"r\");'")
			  ?>
				<div id='r'></div>
		    </td>
		  </tr>

		  </table>

		</fieldset>

	</fieldset>
	</div>
</div>

	<center>
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
		<?php
		  if ($db_opcao != 1) {
		    echo '<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />';
		  }
		?>
	</center>

</form>


<script>

var oAbas = new DBAbas($("container"));
oAbas.adicionarAba('Detalhes', $("parametros"));
oAbas.adicionarAba('Mensagens', $("mensagens"));


function js_controlatextarea(objt,max,dv) {

  obj = eval('document.form1.'+objt);
  atu = max-obj.value.length;
  document.getElementById(eval('dv')).innerHTML='Caracteres disponiveis : '+atu+' de '+max ;
  if (obj.value.length > max) {

    alert('A mensagem não pode ter no máximo 150 caracteres !');
	  obj.value = obj.value.substr(0,150);
    document.getElementById(eval('dv')).innerHTML='Caracteres disponiveis : 0 de '+max ;
	  obj.select();
	  obj.focus();
  }

  if (obj.value.length == 0) {
    document.getElementById(eval('dv')).innerHTML='';
  }
}

function js_controladig3(dobj) {

  digobj = eval('document.form1.'+dobj);
  if (digobj.value != 6 && digobj.value != 7) {

	  alert('O terceiro digito so pode ser 6 ou 7');
	  digobj.value = '';
	  digobj.select();
    digobj.focus();
  }
}

function js_pesquisak03_tipo(mostra) {

  if(mostra == true) {

    var sUrl = 'func_cadtipo.php?funcao_js=parent.js_mostracadtipo1|k03_tipo|k03_descr';
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadtipo',sUrl,'Pesquisa',true);
  } else {

    if (document.form1.k03_tipo.value != '') {

      var sUrl = 'func_cadtipo.php?pesquisa_chave='+document.form1.k03_tipo.value+'&funcao_js=parent.js_mostracadtipo';
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadtipo',sUrl,'Pesquisa',false);
    } else {
      document.form1.k03_tipo.value = '';
    }
  }
}

function js_mostracadtipo(chave,erro) {

  document.form1.tipodescr.value = chave;
  if (erro == true) {

    document.form1.k03_tipo.focus();
    document.form1.k03_tipo.value = '';
  }
}

function js_mostracadtipo1(chave1,chave2) {

  document.form1.k03_tipo.value  = chave1;
  document.form1.tipodescr.value = chave2;
  db_iframe_cadtipo.hide();
}


function js_pesquisak00_receitacredito(mostra) {

  if (mostra) {

    var sUrl = 'func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr';
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec',sUrl,'Pesquisa Receitas',true);

  } else {

    if (document.form1.k00_receitacredito.value != '') {
      var sUrl = 'func_tabrec.php?pesquisa_chave='+document.form1.k00_receitacredito.value+'&funcao_js=parent.js_mostratabrec';
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec',sUrl,'Pesquisa Receitas',false);
    } else {
      document.form1.k00_receitacredito.value = '';
    }
  }
}

function js_mostratabrec(chave,erro) {

  document.form1.receitacreditodescr.value = chave;

  if (erro) {
    document.form1.k00_receitacredito.focus();
    document.form1.k00_receitacredito.value = '';
  }
}

function js_mostratabrec1(chave1,chave2) {

  document.form1.k00_receitacredito.value  = chave1;
  document.form1.receitacreditodescr.value = chave2;
  db_iframe_tabrec.hide();
}



function js_pesquisa() {

  var sUrl = 'func_arretipo.php?funcao_js=parent.js_preenchepesquisa|k00_tipo';
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo',sUrl,'Pesquisa',true);
}

function js_preenchepesquisa(chave) {

  db_iframe_arretipo.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>