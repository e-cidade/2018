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

//MODULO: dividaativa
$clproced->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k01_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("v06_arretipo");
$clrotulo->label("v24_procedagrupa");
$clrotulo->label("v03_tributaria");
$clrotulo->label("receita");
?>
<form name="form1" method="post" action="">
	<center>
		<fieldset>
		<legend><b>Cadastro de Procedência</b></legend>
		<table border="0">
			<tr>
				<td nowrap title="<?=@$Tv03_codigo?>">
					<?=@$Lv03_codigo?>
				</td>
				<td>
					<?
						db_input('v03_codigo',6,$Iv03_codigo,true,'text',3,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tv03_descr?>">
					<?=@$Lv03_descr?>
				</td>
				<td>
					<?
						db_input('v03_descr',40,$Iv03_descr,true,'text',$db_opcao,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tv03_dcomp?>">
					<?=@$Lv03_dcomp?>
				</td>
				<td>
					<?
						db_input('v03_dcomp',40,$Iv03_dcomp,true,'text',$db_opcao,"")
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tv03_receit?>">
					<?
						db_ancora(@$Lv03_receit,"js_pesquisav03_receit(true);",$db_opcao);
					?>
				</td>
				<td>
					<?
						db_input('v03_receit',6,$Iv03_receit,true,'text',$db_opcao," onchange='js_pesquisav03_receit(false);'");
						db_input('k02_descr',30,$Ik02_descr,true,'text',3,'');
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tk00_hist?>">
					<?
						db_ancora(@$Lk00_hist,"js_pesquisak00_hist(true);",$db_opcao);
					?>
				</td>
				<td>
					<?
						db_input('k00_hist',6,$Ik00_hist,true,'text',$db_opcao," onchange='js_pesquisak00_hist(false);'");
						db_input('k01_descr',30,$Ik01_descr,true,'text',3,'');
					?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tv03_tributaria?>">
					<?=@$Lv03_tributaria?>
				</td>
				<td>
					<?
            $rs = $cltipoproced->sql_record($cltipoproced->sql_query(null,"*","v07_sequencial",null));
            db_selectrecord('v03_tributaria',$rs,true,$db_opcao,"","","","0-Nenhum","",2);
					?>
				</td>
			</tr>





      <tr>
        <td nowrap title="<?=@$Tar36_receita?>"><b>
           <?
           db_ancora("Grupo de Procedência","js_pesquisaprocedtipo(true);",$db_opcao);
           ?></b>
        </td>
        <td>
    				<?
    				  db_input('v03_procedtipo',8,"",true,'text',$db_opcao," onchange='js_pesquisaprocedtipo(false);'")
    				?>
           <?
              db_input('v28_descricao',30,"",true,'text',3,'')
           ?>
        </td>
      </tr>






			<tr>
				<td nowrap title="<?=@$Treceita?>">
					<?
						db_ancora(@$Lreceita,"js_pesquisareceita(true);",$db_opcao);
					?>
				</td>
				<td>
					<?
						db_input('receita',6,$Ireceita,true,'text',$db_opcao," onchange='js_pesquisareceita(false);'")
					?>
					<?
						db_input('descr_2',30,"",true,'text',3,'');
					?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Tipo de Débito Padrão:</b>
				</td>
				<td>
					<?
						$rsArretipo = $clarretipo->sql_record($clarretipo->sql_query_file(null,"k00_tipo, k00_descr","k00_descr","k03_tipo = 5")) ;
						db_selectrecord('v06_arretipo',$rsArretipo,true,$db_opcao,"","","","0-Nenhum","");
					?>
				</td>
			</tr>
		  <tr>
        <td nowrap title="<?=@$Tv24_procedagrupa?>">
          <?
            db_ancora(@$Lv24_procedagrupa,"js_pesquisav24_procedagrupa(true);",$db_opcaoagrupa);
          ?>
        </td>
        <td>
          <?
            db_input('v24_procedagrupa',6,$Ireceita,true,'text', $db_opcaoagrupa," onchange='js_pesquisav24_procedagrupa(false);'")
          ?>
          <?
            db_input('v24_procedagrupadescr',30,"",true,'text',3,'');
          ?>
        </td>
      </tr>


			<!--
			<tr>
				<td nowrap title="<?=@$Treceita?>">
					<?
						db_ancora("<b>Receita procdiver</b>","js_pesquisareceitad(true);",$db_opcao);
					?>
				</td>
				<td>
					<?
						db_input('receitad',10,$Ireceita,true,'text',$db_opcao," onchange='js_pesquisareceitad(false);'")
					?>
					<?
						db_input('k02_descrd',15,$Ik02_descr,true,'text',3,'')
					?>
				</td>
			</tr>
			-->


		</table>
		</fieldset>
	</center>
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
      type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
      <?=($db_botao==false?"disabled":"")?> onclick="return js_validar();" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

// lookup tipo procedencia

function js_pesquisaprocedtipo(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe_receita','func_procedtipo.php?funcao_js=parent.js_mostraprocedtipo1|v28_sequencial|v28_descricao','Pesquisa',true);
	  }else{
	     if(document.form1.v03_procedtipo.value != ''){
	        js_OpenJanelaIframe('','db_iframe_receita','func_procedtipo.php?pesquisa_chave='+document.form1.v03_procedtipo.value+'&funcao_js=parent.js_mostraprocedtipo','Pesquisa',false);
	     }else{
	       document.form1.v28_descricao.value = '';
	     }
	  }
	}
	function js_mostraprocedtipo(chave,erro){
	  document.form1.v28_descricao.value = chave;
	  if(erro==true){
	    document.form1.v03_procedtipo.focus();
	    document.form1.v03_procedtipo.value = '';
	  }
	}
	function js_mostraprocedtipo1(chave1,chave2){
	//	alert("chave1 - " + chave1 + "\nchave2 - "+chave2 );
	  document.form1.v03_procedtipo.value = chave1;
	  document.form1.v28_descricao.value = chave2;
	  db_iframe_receita.hide();
	}





function js_validar(){
  var sCampo = document.getElementById('v03_tributariadescr').value;
  if (sCampo == 0) {
    alert("Nenhum Tipo de Procedência Escolhido");
    return false;
  }
}

function js_pesquisak00_hist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostrahistcalc1|k01_codigo|k01_descr','Pesquisa',true);
  }else{
     if(document.form1.k00_hist.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave='+document.form1.k00_hist.value+'&funcao_js=parent.js_mostrahistcalc','Pesquisa',false);
     }else{
       document.form1.k01_descr.value = '';
     }
  }
}

function js_mostrahistcalc(chave,erro){
  document.form1.k01_descr.value = chave;
  if(erro==true){
    document.form1.k00_hist.focus();
    document.form1.k00_hist.value = '';
  }
}

function js_mostrahistcalc1(chave1,chave2){
  document.form1.k00_hist.value = chave1;
  document.form1.k01_descr.value = chave2;
  db_iframe_histcalc.hide();
}

function js_pesquisav03_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr<?php echo isset($somenteTipoReceitaPrincipal) ? '&k02_tabrectipo=1' : ''?>','Pesquisa',true);
  }else{
     if(document.form1.v03_receit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?pesquisa_chave='+document.form1.v03_receit.value+'&funcao_js=parent.js_mostratabrec<?php echo isset($somenteTipoReceitaPrincipal) ? '&k02_tabrectipo=1' : ''?>','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = '';
     }
  }
}

function js_mostratabrec(chave,erro){

  document.form1.k02_descr.value = chave;
  if (erro==true) {

    document.form1.v03_receit.focus();
    document.form1.v03_receit.value = '';

  }
}

function js_mostratabrec1(chave1,chave2) {

  document.form1.v03_receit.value = chave1;
    document.form1.k02_descr.value = chave2;
    document.form1.k02_tabrectipo.value = 1;

  db_iframe_tabrec.hide();

}

function js_pesquisareceita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec2|k02_codigo|k02_descr<?php echo isset($somenteTipoReceitaPrincipal) ? '&k02_tabrectipo=1' : ''?>','Pesquisa',true);
  }else{
     if(document.form1.receita.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?pesquisa_chave='+document.form1.receita.value+'&funcao_js=parent.js_mostratabrec22<?php echo isset($somenteTipoReceitaPrincipal) ? '&k02_tabrectipo=1' : ''?>','Pesquisa',false);
     }else{
       document.form1.descr_2.value = '';
     }
  }
}

function js_mostratabrec22(chave,erro){
  document.form1.descr_2.value = chave;
  if(erro==true){
    document.form1.receita.focus();
    document.form1.receita.value = '';
  }
}

function js_mostratabrec2(chave1,chave2){
  document.form1.receita.value = chave1;
  document.form1.descr_2.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisav24_procedagrupa(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_procedagrupa',
                        'func_proced.php?funcao_js=parent.js_mostraprocedagrupa2|v03_codigo|v03_descr',
                        'Pesquisar Procedências',
                        true
                        );
  }else{

     if (document.form1.v24_procedagrupa.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_procedagrupa',
                            'func_proced?pesquisa_chave='+document.form1.v24_procedagrupa.value+
                            '&funcao_js=parent.js_mostraprocedagrupa1',
                            'Pesquisar Procedências',
                            false);
     }else{
       document.form1.v24_procedagrupadescr.value = '';
     }

  }
}

function js_mostraprocedagrupa1(chave,erro) {

  document.form1.v24_procedagrupadescr.value = chave;
  if(erro==true){
    document.form1.v24_procedagrupa.focus();
    document.form1.v24_procedagrupa.value = '';
  }
}

function js_mostraprocedagrupa2(chave1,chave2) {

  document.form1.v24_procedagrupa.value = chave1;
  document.form1.v24_procedagrupadescr.value = chave2;
  db_iframe_procedagrupa.hide();

}

function js_pesquisareceitad(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec2d|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.receita.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tabrec','func_tabrec_todas.php?pesquisa_chave='+document.form1.receita.value+'&funcao_js=parent.js_mostratabrec22d','Pesquisa',false);
     }else{
       document.form1.k02_descrd.value = '';
     }
  }
}

function js_mostratabrec22d(chave,erro){
  document.form1.k02_descrd.value = chave;
  if(erro==true){
    document.form1.receitad.focus();
    document.form1.receitad.value = '';
  }
}

function js_mostratabrec2d(chave1,chave2){
  document.form1.receitad.value = chave1;
  document.form1.k02_descrd.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_proced','func_proced.php?funcao_js=parent.js_preenchepesquisa|v03_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_proced.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

document.getElementById('v03_codigo').style.width             = 63;
document.getElementById('v03_descr').style.width              = 299;
document.getElementById('v03_dcomp').style.width              = 299;
document.getElementById('v03_receit').style.width             = 63;
document.getElementById('k02_descr').style.width              = 232;
document.getElementById('k00_hist').style.width               = 63;
document.getElementById('k01_descr').style.width              = 232;
document.getElementById('v03_tributaria').style.width         = 63;
document.getElementById('v03_tributariadescr').style.width    = 232;
document.getElementById('receita').style.width                = 63;
document.getElementById('descr_2').style.width                = 232;
document.getElementById('v06_arretipo').style.width           = 63;
document.getElementById('v06_arretipodescr').style.width      = 232;
document.getElementById('v24_procedagrupa').style.width       = 63;
document.getElementById('v24_procedagrupadescr').style.width  = 232;
document.getElementById('v28_descricao').style.width  = 232;
document.getElementById('v03_procedtipo').style.width           = 63;
</script>
