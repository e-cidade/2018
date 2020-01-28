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
$clpardiv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k01_descr");
$clrotulo->label("k15_numcgm");
$clrotulo->label("i01_descr");
$clrotulo->label("k15_numcgm");
$clrotulo->label("db03_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("k00_descr");
$clrotulo->label("k00_descr");

?>

	<fieldset>
	  <legend>
	    <b>Parâmetros da Dívida</b>
	  </legend>
			<table border="0">
				<tr>
					<td nowrap title="<?=@$Tv04_docum?>">
						<b>
							<?
								db_ancora("Documento","js_pesquisav04_docum(true);",$db_opcao);
                db_input('v04_instit',10,$Iv04_instit,true,'hidden',3,"");
							?>
						</b>
					</td>
					<td>
						<?
							db_input('v04_docum',10,$Iv04_docum,true,'text',$db_opcao," onchange='js_pesquisav04_docum(false);'");
						?>
						<?
							db_input('db03_descr',40,$Idb03_descr,true,'text',3,'');
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_histjuros?>">
						<?=@$Lv04_histjuros?>
					</td>
					<td>
						<?
							db_input('v04_histjuros',10,$Iv04_histjuros,true,'text',$db_opcao,"");
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
							db_input('k00_hist',10,$Ik00_hist,true,'text',$db_opcao," onchange='js_pesquisak00_hist(false);'");
						?>
						<?
							db_input('k01_descr',40,$Ik01_descr,true,'text',3,'');
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_tipoinicial?>">
						<b>
						  <?
							  db_ancora("Tipo Inicial","js_pesquisav04_tipoinicial(true);",$db_opcao);
						  ?>
						</b>
					</td>
					<td>
						<?
							db_input('v04_tipoinicial',10,$Iv04_tipoinicial,true,'text',$db_opcao," onchange='js_pesquisav04_tipoinicial(false);'");
						?>
						<?
							db_input('descrtipoinicial',40,$descrtipoinicial,true,'text',3,'');
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_tipocertidao?>">
						<b>
							<?
								db_ancora("Tipo Certidão","js_pesquisav04_tipocertidao(true);",$db_opcao);
							?>
						</b>
					</td>
					<td>
						<?
							db_input('v04_tipocertidao',10,$Iv04_tipocertidao,true,'text',$db_opcao," onchange='js_pesquisav04_tipocertidao(false);'");
						?>
						<?
							db_input('descrtipocertidao',40,$descrtipocertidao,true,'text',3,'');
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_peticaoinicial?>">
						<?=@$Lv04_peticaoinicial?>
					</td>
					<td>
						<?
							$x = array('1'=>'Modelo original','2'=>'Modelo com margem esquerda menor e espaçamentos gerais menores e espaço para juiz despachar em cima');
							db_select('v04_peticaoinicial',$x,true,$db_opcao,"style='width:170'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_envolcdaiptu?>">
						<?=@$Lv04_envolcdaiptu?>
					</td>
					<td>
						<?
							$x = array('0'=>'Todos','1'=>'Somente Proprietários','2'=>'Somente Promitentes');
							db_select('v04_envolcdaiptu',$x,true,$db_opcao,"style='width:170'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_envolcdaiss?>">
						<?=@$Lv04_envolcdaiss?>
					</td>
					<td>
						<?
							$x = array('0'=>'Não Vincular Sócios','1'=>'Vincular Sócios');
							db_select('v04_envolcdaiss',$x,true,$db_opcao,"style='width:170'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_ordemendcda?>">
						<?=@$Lv04_ordemendcda?>
					</td>
					<td>
						<?
							$aOrdemCda = array('1'=>'Origem','2'=>'CGM');
							db_select('v04_ordemendcda',$aOrdemCda,true,$db_opcao,"style='width:80px;'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_envolprinciptu?>">
						<?=@$Lv04_envolprinciptu?>
					</td>
					<td>
						<?
							$aEnvolPrincIptu = array("f"=>"NAO","t"=>"SIM");
							db_select('v04_envolprinciptu',$aEnvolPrincIptu,true,$db_opcao,"style='width:80px;'");
						?>
					</td>
				</tr>
				<tr>
					<td nowrap title="<?=@$Tv04_imphistcda?>">
						<?=@$Lv04_imphistcda?>
					</td>
					<td>
						<?
							$aImpHistCda = array("f"=>"NAO","t"=>"SIM");
							db_select('v04_imphistcda',$aImpHistCda,true,$db_opcao,"style='width:80px;'");
						?>
					</td>
				</tr>
				<tr>
          <td nowrap title="<?=@$Tv04_implivrofolha?>">
            <?=@$Lv04_implivrofolha?>
          </td>
          <td>
            <?
              $aImplivrofolha = array("f"=>"NAO","t"=>"SIM");
              db_select('v04_implivrofolha',$aImplivrofolha,true,$db_opcao,"style='width:80px;'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tv04_confexpfalec?>">
            <?=@$Lv04_confexpfalec?>
          </td>
          <td>
            <?
              $aConfExpFalec = array("1"=>"Somente CDA",
                                     "2"=>"Somente Inicial",
                                     "3"=>"Em Ambas");
              db_select('v04_confexpfalec',$aConfExpFalec,true,$db_opcao,"style='width:170px;'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tv04_expfalecimentocda?>">
            <?=@$Lv04_expfalecimentocda?>
          </td>
          <td>
            <?
              db_input('v04_expfalecimentocda',50,$Iv04_expfalecimentocda,true,'text',$db_opcao,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tv04_formgeracda?>">
            <?=@$Lv04_formgeracda?>
          </td>
          <td>
            <?
              $x = array('1'=>'Normal',
                         '2'=>'Individualizar por Origem/ Ano/ Procedência');
              db_select('v04_formgeracda',$x,true,$db_opcao,"style='width:370'");
            ?>
          </td>
        </tr>
			</table>
    <fieldset class="separator">
      <legend>Cobrança Extrajudicial</legend>
        <table>
          <tr>
            <td nowrap title="<?php echo $Tv04_cobrarjurosmultacda; ?>" style="width: 262px;">
              <label for="v04_cobrarjurosmultacda">
                <?php echo $Lv04_cobrarjurosmultacda; ?>
              </label>
            </td>
            <td>
              <?php
                $aCobrarJurosMultaCda = array('f'=>'Não', 't'=>'Sim');
                db_select('v04_cobrarjurosmultacda', $aCobrarJurosMultaCda, true, $db_opcao, "style='width:80px;'");
              ?>
            </td>
          </tr>
        </table>
    </fieldset>
  </fieldset>
  <table align="center">
    <tr>
      <td>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
               type="submit" id="db_opcao"
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
               <?=($db_botao==false?"disabled":"")?> >
      </td>
    </tr>
  </table>
<script>
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

function js_pesquisav04_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.v04_docum.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.v04_docum.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_descr.value = '';
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave;
  if(erro==true){
    document.form1.v04_docum.focus();
    document.form1.v04_docum.value = '';
  }
}
function js_mostradb_documento1(chave1,chave2){
  document.form1.v04_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}

function js_pesquisav04_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.v04_instit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.v04_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = '';
     }
  }
}

function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave;
  if(erro==true){
    document.form1.v04_instit.focus();
    document.form1.v04_instit.value = '';
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.v04_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}

function js_pesquisav04_tipoinicial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.v04_tipoinicial.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.v04_tipoinicial.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = '';
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.descrtipoinicial.value = chave;
  if(erro==true){
    document.form1.v04_tipoinicial.focus();
    document.form1.v04_tipoinicial.value = '';
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.v04_tipoinicial.value = chave1;
  document.form1.descrtipoinicial.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisav04_tipocertidao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostracertidao1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.v04_tipocertidao.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.v04_tipocertidao.value+'&funcao_js=parent.js_mostracertidao','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = '';
     }
  }
}
function js_mostracertidao(chave,erro){
  document.form1.descrtipocertidao.value = chave;
  if(erro==true){
    document.form1.v04_tipocertidao.focus();
    document.form1.v04_tipocertidao.value = '';
  }
}
function js_mostracertidao1(chave1,chave2){
  document.form1.v04_tipocertidao.value = chave1;
  document.form1.descrtipocertidao.value = chave2;
  db_iframe_arretipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pardiv','func_pardiv.php?funcao_js=parent.js_preenchepesquisa|v04_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pardiv.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>