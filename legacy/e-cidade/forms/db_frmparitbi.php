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

//MODULO: itbi
$clparitbi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j32_descr");
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
?>
<fieldset>
  <legend>
    <strong>Parâmetros de ITBI</strong>
  </legend>
  <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
    <tr>
      <td colspan="2">
        <table border="0" width="100%">
          <tr>
            <td align="left" title="<?=@$Tit24_anousu?>" width="45%"><?=@$Lit24_anousu?></td>
            <td>
			        <?
			          db_input('it24_anousu',10,$Iit24_anousu,true,'text',3," style=' width: 210px; background-color: rgb(222, 184, 135);'")
			        ?>
            </td>
          </tr>
          <tr>
            <td align="left" title="<?=@$Tit24_diasvctoitbi?>" width="45%"><?=@$Lit24_diasvctoitbi?></td>
            <td>
			        <?
			          db_input('it24_diasvctoitbi',10,$Iit24_diasvctoitbi,true,'text',$db_opcao," style=' width: 210px;'");
			        ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_impsituacaodeb?>"><?=@$Lit24_impsituacaodeb?></td>
            <td width="96%" align="left">
             <?
               $aImpSituacaoDeb = array("t"=>"Sim",
                                        "f"=>"Não");
               db_select('it24_impsituacaodeb',$aImpSituacaoDeb,true,1," style=' width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
            <td align="left" title="<?=@$Tit24_alteraguialib?>"><?=@$Lit24_alteraguialib?></td>
            <td width="96%" align="left">
             <?
               $aAlteraGuiaLib = array("1"=>"Somente Datas",
                                       "2"=>"Somente Dados Cadastrais",
                                       "3"=>"Ambos");
               db_select('it24_alteraguialib',$aAlteraGuiaLib,true,1," style=' width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="<?=@$Tit24_cgmobrigatorio?>"><?=@$Lit24_cgmobrigatorio?></td>
            <td width="96%" align="left">
             <?
               $aCgmObrigatorio = array("t"=>"Sim",
                                         "f"=>"Não");
               db_select('it24_cgmobrigatorio',$aCgmObrigatorio,true,1," style='width: 210px;'");
             ?>
            </td>
          </tr>
          <tr>
          	<td align="left" title="<?php echo $Tit24_taxabancaria; ?>">
          		<?php echo $Lit24_taxabancaria; ?>
          	</td>
          	<td>
          	   <?php db_input('it24_taxabancaria', 10, $Iit24_taxabancaria, true, 'text', $db_opcao); ?>
          	</td>
          </tr>
          <tr>
            <td align="left" title="<?php echo $Tit24_grupopadraoconstrutivobenurbana; ?>" nowrap>
              <?php
                db_ancora($Lit24_grupopadraoconstrutivobenurbana, "js_pesquisait24_grupopadraoconstrutivobenurbana(true);", $db_opcao);
              ?>
            </td>
            <td colspan="2" nowrap>
              <?php
                db_input('it24_grupopadraoconstrutivobenurbana', 10, $Iit24_grupopadraoconstrutivobenurbana, true, 'text', $db_opcao, " onchange='js_pesquisait24_grupopadraoconstrutivobenurbana(false);'");
                db_input('nomegrupopadraoconstrutivourbana', 35, $Ij32_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Filtros</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td><strong>Hist. Calc. :</strong></td>
              <td colspan="2">
                  <input type="text" name="hist_calc" id="hist_calc" style=' width:80px; background-color: rgb(222, 184, 135);'
                   value='707' readonly="readonly" title='Histórico do Calculo Fixo ITBI' >
              </td>
            </tr>
            <tr>
              <td title="Código da Receita" width="28%">
                 <strong><? db_ancora("Codigo da Receita",'js_pesquisa(true);',1); ?></strong>
              </td>
              <td>
                <? db_input('k02_codigo',10,"",true,'text',1,'onchange="js_pesquisa(false);"');  ?>
              </td>
              <td>
                <? db_input('k02_descr' ,40,"",true,'text',3);  ?>
              </td>
            </tr>
              <tr>
                <td nowrap title="<?=@$Tz01_numcgm?>" align='left'>
                  <strong>
                    <?
                      db_ancora("CGM :","js_pesquisaz01_numcgm(true);",1);
                    ?>
                  </strong>
                </td>
                <td>
                  <?
                    db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
                  ?>
                </td>
                <td>
                  <?
                    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
                  ?>
                </td>
         </table>
        </fieldset>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Urbana</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td title="<?=@$Tit24_grupoespbenfurbana?>" width="28%">
                <?
                  db_ancora(@$Lit24_grupoespbenfurbana,"js_pesquisait24_grupoespbenfurbana(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupoespbenfurbana',10,$Iit24_grupoespbenfurbana,true,'text',$db_opcao," onchange='js_pesquisait24_grupoespbenfurbana(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nomeespbenfurbana',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupotipobenfurbana?>">
                <?
                  db_ancora(@$Lit24_grupotipobenfurbana,"js_pesquisait24_grupotipobenfurbana(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupotipobenfurbana',10,$Iit24_grupotipobenfurbana,true,'text',$db_opcao," onchange='js_pesquisait24_grupotipobenfurbana(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nometipobenfurbana',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
         </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset>
          <legend>
            <strong>Rural</strong>
          </legend>
          <table border="0" width="100%">
            <tr>
              <td title="<?=@$Tit24_grupoespbenfrural?>" width="28%">
                <?
                  db_ancora(@$Lit24_grupoespbenfrural,"js_pesquisait24_grupoespbenfrural(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupoespbenfrural',10,$Iit24_grupoespbenfrural,true,'text',$db_opcao," onchange='js_pesquisait24_grupoespbenfrural(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nomeespbenfrural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupotipobenfrural?>">
                <?
                  db_ancora(@$Lit24_grupotipobenfrural,"js_pesquisait24_grupotipobenfrural(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupotipobenfrural',10,$Iit24_grupotipobenfrural,true,'text',$db_opcao," onchange='js_pesquisait24_grupotipobenfrural(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nometipobenfrural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupoutilterrarural?>">
                <?
                  db_ancora(@$Lit24_grupoutilterrarural,"js_pesquisait24_grupoutilterrarural(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupoutilterrarural',10,$Iit24_grupoutilterrarural,true,'text',$db_opcao," onchange='js_pesquisait24_grupoutilterrarural(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nomeutilterrarural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?=@$Tit24_grupodistrterrarural?>">
                <?
                  db_ancora(@$Lit24_grupodistrterrarural,"js_pesquisait24_grupodistrterrarural(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('it24_grupodistrterrarural',10,$Iit24_grupodistrterrarural,true,'text',$db_opcao," onchange='js_pesquisait24_grupodistrterrarural(false);'");
                ?>
              </td>
              <td>
                <?
                  db_input('nomedistrterrarural',40,$Ij32_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</fieldset>

<input name="<?=($db_opcao==1?"incluir":"alterar")?>" onclick="return js_valida();"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>"/>

<script type="text/javascript">
function js_pesquisait24_grupoespbenfurbana(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupespbenfurbana1|j32_grupo|j32_descr','Pesquisa',true);
  }else{
    if(document.form1.it24_grupoespbenfurbana.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoespbenfurbana.value+'&funcao_js=parent.js_mostracargrupespbenfurbana','Pesquisa',false);
    }else{
      document.form1.nomeespbenfurbana.value = '';
    }
  }
}
function js_mostracargrupespbenfurbana(chave,erro){

  document.form1.nomeespbenfurbana.value = chave;
  if(erro==true){

    document.form1.it24_grupoespbenfurbana.focus();
    document.form1.it24_grupoespbenfurbana.value = '';
  }
}
function js_mostracargrupespbenfurbana1(chave1,chave2){

  document.form1.it24_grupoespbenfurbana.value = chave1;
  document.form1.nomeespbenfurbana.value	     = chave2;
  db_iframe_cargrup.hide();
}

function js_pesquisait24_grupotipobenfurbana(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruptipobenfurbana1|j32_grupo|j32_descr','Pesquisa',true);
  }else{

     if(document.form1.it24_grupotipobenfurbana.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupotipobenfurbana.value+'&funcao_js=parent.js_mostracargruptipobenfurbana','Pesquisa',false);
     }else{
       document.form1.nometipobenfurbana.value = '';
     }
  }
}

function js_mostracargruptipobenfurbana(chave,erro){

  document.form1.nometipobenfurbana.value = chave;
  if(erro==true){

    document.form1.it24_grupotipobenfurbana.focus();
    document.form1.it24_grupotipobenfurbana.value = '';
  }
}
function js_mostracargruptipobenfurbana1(chave1,chave2){

  document.form1.it24_grupotipobenfurbana.value = chave1;
  document.form1.nometipobenfurbana.value       = chave2;
  db_iframe_cargrup.hide();
}


function js_pesquisait24_grupoespbenfrural(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupespbenfrural1|j32_grupo|j32_descr','Pesquisa',true);
  }else{

    if(document.form1.it24_grupoespbenfrural.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoespbenfrural.value+'&funcao_js=parent.js_mostracargrupespbenfrural','Pesquisa',false);
    }else{
      document.form1.nomeespbenfrural.value = '';
    }
  }
}

function js_mostracargrupespbenfrural(chave,erro){

  document.form1.nomeespbenfrural.value = chave;
  if(erro==true){

    document.form1.it24_grupoespbenfrural.focus();
    document.form1.it24_grupoespbenfrural.value = '';
  }
}

function js_mostracargrupespbenfrural1(chave1,chave2){

  document.form1.it24_grupoespbenfrural.value = chave1;
  document.form1.nomeespbenfrural.value       = chave2;
  db_iframe_cargrup.hide();
}


function js_pesquisait24_grupotipobenfrural(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruptipobenfrural1|j32_grupo|j32_descr','Pesquisa',true);
  }else{

     if(document.form1.it24_grupotipobenfrural.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupotipobenfrural.value+'&funcao_js=parent.js_mostracargruptipobenfrural','Pesquisa',false);
     }else{
       document.form1.nometipobenfrural.value = '';
     }
  }
}
function js_mostracargruptipobenfrural(chave,erro){

  document.form1.nometipobenfrural.value = chave;
  if(erro==true){

    document.form1.it24_grupotipobenfrural.focus();
    document.form1.it24_grupotipobenfrural.value = '';
  }
}
function js_mostracargruptipobenfrural1(chave1,chave2){

  document.form1.it24_grupotipobenfrural.value = chave1;
  document.form1.nometipobenfrural.value       = chave2;
  db_iframe_cargrup.hide();
}


function js_pesquisait24_grupoutilterrarural(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargruputilterrarural1|j32_grupo|j32_descr','Pesquisa',true);
  }else{

     if(document.form1.it24_grupoutilterrarural.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupoutilterrarural.value+'&funcao_js=parent.js_mostracargruputilterrarural','Pesquisa',false);
     }else{
       document.form1.nomeutilterrarural.value = '';
     }
  }
}

function js_mostracargruputilterrarural(chave,erro){

  document.form1.nomeutilterrarural.value = chave;
  if(erro==true){

    document.form1.it24_grupoutilterrarural.focus();
    document.form1.it24_grupoutilterrarural.value = '';
  }
}

function js_mostracargruputilterrarural1(chave1,chave2){

  document.form1.it24_grupoutilterrarural.value = chave1;
  document.form1.nomeutilterrarural.value       = chave2;
  db_iframe_cargrup.hide();
}


function js_pesquisait24_grupodistrterrarural(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?funcao_js=parent.js_mostracargrupdistrterrarural1|j32_grupo|j32_descr','Pesquisa',true);
  }else{

     if(document.form1.it24_grupodistrterrarural.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup.php?pesquisa_chave='+document.form1.it24_grupodistrterrarural.value+'&funcao_js=parent.js_mostracargrupdistrterrarural','Pesquisa',false);
     }else{
       document.form1.nomedistrterrarural.value = '';
     }
  }
}
function js_mostracargrupdistrterrarural(chave,erro){

  document.form1.nomedistrterrarural.value = chave;
  if(erro==true){
    document.form1.it24_grupodistrterrarural.focus();
    document.form1.it24_grupodistrterrarural.value = '';
  }
}
function js_mostracargrupdistrterrarural1(chave1,chave2){

  document.form1.it24_grupodistrterrarural.value = chave1;
  document.form1.nomedistrterrarural.value 	 = chave2;
  db_iframe_cargrup.hide();
}

/*
 * Pesquisa para CGM
*/
function js_pesquisaz01_numcgm(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{

     if(document.form1.z01_numcgm.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.kz01_numcgm.value = '';
     }
  }
}

function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){

  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_nome.hide();
}

/**
 * Grupo de Padrão Construtivo
 */
 function js_pesquisait24_grupopadraoconstrutivobenurbana(mostra){

	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup_rel.php?grupo=I&funcao_js=parent.js_mostracargruptipopadraocontrutivo1|j32_grupo|j32_descr','Pesquisa',true);
	  }else{

	     if(document.form1.it24_grupopadraoconstrutivobenurbana.value != ''){
	        js_OpenJanelaIframe('top.corpo','db_iframe_cargrup','func_cargrup_rel.php?grupo=I&pesquisa_chave='+document.form1.it24_grupopadraoconstrutivobenurbana.value+'&funcao_js=parent.js_mostracargruptipopadraocontrutivo','Pesquisa',false);
	     }else{
	       document.form1.nomegrupopadraoconstrutivourbana.value = '';
	     }
	  }
	}

 	function js_mostracargruptipopadraocontrutivo(chave, erro) {

 		document.form1.nomegrupopadraoconstrutivourbana.value = chave;
 		if ( erro == true ) {
 		  document.form1.it24_grupopadraoconstrutivobenurbana.focus();
 		  document.form1.it24_grupopadraoconstrutivobenurbana.value = '';
 		}
	}

	function js_mostracargruptipopadraocontrutivo1(chave1, chave2) {

		document.form1.it24_grupopadraoconstrutivobenurbana.value = chave1;
		document.form1.nomegrupopadraoconstrutivourbana.value     = chave2;
		db_iframe_cargrup.hide();
	}

/*
 * PESQUISA PARA RECEITA
*/
function js_pesquisa(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancamrec','func_tabrec.php?funcao_js=parent.js_preenchepesquisa|k02_codigo|k02_descr','Pesquisa',true);
  }else{

     if(document.form1.k02_codigo != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_conlancamrec','func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostrarec','Pesquisa', false);
     }else{
       document.form1.k02_codigo.value = '';
     }
  }
}

function js_preenchepesquisa(chave,chave2){

  db_iframe_conlancamrec.hide();
  document.form1.k02_codigo.value=chave;
  document.form1.k02_descr.value=chave2;
}
function js_mostrarec(chave2, erro, chave3) {

  document.form1.k02_descr.value=chave2;
  if(erro==true){

    document.form1.k02_codigo.focus();
    document.form1.k02_codigo.value = '';
  }
}

function js_valida(){

  if( $F('k02_codigo') == '' ) {

    alert('Campo Código da Receita é de preenchimento obrigatório.');
    $('k02_codigo').focus();
    return false;
  }

  if( $F('z01_numcgm') == '' ) {

    alert('Campo CGM é de preenchimento obrigatório.');
    $('z01_numcgm').focus();
    return false;
  }

  return true;
}
</script>