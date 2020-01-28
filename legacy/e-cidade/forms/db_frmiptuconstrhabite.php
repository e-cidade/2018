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

$oRotulo = new rotulocampo();
$oRotulo->label("j131_obs");

?>
<form name="form1" method="post" action="">
<?php db_input('j131_sequencial',10,$Ij131_sequencial,true,'hidden',$db_opcao,""); ?>
<?php db_input('j131_usuario'   ,10,$Ij131_usuario   ,true,'hidden',$db_opcao,""); ?>
<?php db_input('j131_data'      ,10,$Ij131_data      ,true,'hidden',$db_opcao,""); ?>
<?php db_input('j131_hora'      ,10,$Ij131_hora      ,true,'hidden',$db_opcao,""); ?>

<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj131_matric?>">
      <?=@$Lj131_matric?>
    </td>
    <td>
      <?php
      db_input('j131_matric',10,$Ij131_matric,true,'text',3,"");
      db_input('z01_nome',45,0,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj131_idcons?>">
      <?=@$Lj131_idcons?>
    </td>
    <td>
      <? db_input('j131_idcons',10,$Ij131_idcons,true,'text',3,"") ?>
    </td>
  </tr>
  <tr>
    <td title="Origem do Processo"> <b>Processo do Sistema:</b> </td>
    <td >
     <?
      $x = array("S"=>"Sim", "N"=>"Não");
      db_select("lProcesso", $x, true, $db_opcao, "onChange=js_montaCampoProcesso()");
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj131_codprot?>">
      <div id="ProcessoLabel1" style='display: none;'>
         <?db_ancora(@$Lj131_codprot, "js_pesquisaj131_codprot(true)", $db_opcao)?>
      </div>
      <div id="ProcessoLabel2" style='display: none;'>
         <?=@$Lj131_codprot?>
      </div>
    </td>
    <td>
      <div id="ProcessoCod1" style='display:none;'>
         <input id        = 'j131_codprot1'
                type      = "text"
                value     = "<?=@$j131_codprot?>"
                size      = '10'
                maxlength = '20'
                onchange  = "js_pesquisaj131_codprot(false);"
                onblur    = "js_ValidaMaiusculo(this,'f',event);"
             	  onkeyup   = "js_ValidaCampos(this,4,'Processo do Protocolo','f','f',event);"
                <?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?> >
         <input type="text" name="p58_requer" value="<?=@$p58_requer?>" size=45 readonly style="background: #DEB887;">
      </div>
      <div id="ProcessoCod2" style='display: none;'>
        <input id         = 'j131_codprot2'
               type       = "text"
               value      = "<?=@$j131_codprot?>"
               size       = '10'
               maxlength  = '20'
               onblur     = "js_ValidaMaiusculo(this,'f',event);"
            	 onkeyup    = "js_ValidaCampos(this,4,'Processo do Protocolo','f','f',event);"
               <?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?> >
      </div>
    </td>
  </tr>
  <tr>
    <td nowrap title='<?=$Tj131_dtprot?>'>
      <?=$Lj131_dtprot?>
    </td>
    <td>
      <div id="divDtProt1" style='display: none;'>
        <input name         = "j131_dtprot_1"
               type         = "text"
               id           = "j131_dtprot_1"
               value        = "<?=@$j131_dtprot?>"
               size         = "10"
               maxlength    = "20"
               style        = "background-color:#DEB887;"
               autocomplete = ''
               readonly>
      </div>
      <div id="divDtProt2" style='display: none;'>

        <input name="j131_dtprot_2"     type="text" id="j131_dtprot_2"  value="<?=@$j131_dtprot?>" size="10" maxlength="10" autocomplete="off" onBlur='js_validaDbData(this);' onKeyUp="return js_mascaraData(this,event)"  onFocus="js_validaEntrada(this);"  >
        <input name="j131_dtprot_2_dia" type="hidden" title="" id="j131_dtprot_2_dia" value="" size="2"  maxlength="2" >
        <input name="j131_dtprot_2_mes" type="hidden" title="" id="j131_dtprot_2_mes" value="" size="2"  maxlength="2" >
        <input name="j131_dtprot_2_ano" type="hidden" title="" id="j131_dtprot_2_ano" value="" size="4"  maxlength="4" >
        <script>
          var PosMouseY, PosMoudeX;

          function js_comparaDatasj131_dtprot(dia,mes,ano){
	          var objData        = document.getElementById('j131_dtprot');
	          objData.value      = dia+"/"+mes+'/'+ano;
          }

        </script>

        <input value="D" type="button" name="dtjs_j131_dtprot" onclick="pegaPosMouse(event);show_calendar('j131_dtprot','none')" />

      </div>
    </td>
  </tr>

  <tr>
    <td title="Habite-se do Sistema?"> <b>Habite-se do Sistema:</b> </td>
    <td >
     <?
      $x = array("S"=>"Sim", "N"=>"Não");
      db_select("lHabite", $x, true, $db_opcao, "onChange=js_montaCampoHabite()");
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj131_cadhab?>">
      <div id="HabiteLabel1" style='display: none;'>
         <?db_ancora(@$Lj131_cadhab, "js_pesquisaj131_cadhab(true)", $db_opcao)?>
      </div>
      <div id="HabiteLabel2" style='display: none;'>
         <?=@$Lj131_cadhab?>
      </div>
    </td>
    <td>
      <div id="HabiteCod1" style='display:none;'>
         <input id        = 'j131_cadhab1'
                type      = "text"
                value     = "<?=@$j131_cadhab?>"
                size      = '10'
                maxlength = '20'
                onchange  = "js_pesquisaj131_cadhab(false);"
                onblur    = "js_ValidaMaiusculo(this,'f',event);"
             	  onkeyup   = "js_ValidaCampos(this,4,'Habite-se','f','f',event);"
                <?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?> >
         <input type="text" name="ob09_habite" value="<?=@$ob09_habite?>" size=45 readonly style="background: #DEB887;">
      </div>
      <div id="HabiteCod2" style='display: none;'>
        <input id         = 'j131_cadhab2'
               type       = "text"
               value      = "<?=@$j131_cadhab?>"
               size       = '10'
               maxlength  = '20'
               onblur     = "js_ValidaMaiusculo(this,'f',event);"
            	 onkeyup    = "js_ValidaCampos(this,3,'Habite-se','f','f',event);"
               <?=($db_opcao==3)?" readonly style='background: #DEB887;' ":""?> >
      </div>
    </td>
  </tr>

  <tr>
    <td nowrap title='<?=$Tj131_dthabite?>'>
      <?=$Lj131_dthabite?>
    </td>
    <td>
      <div id="divDtHabite1" style='display: none;'>
      <input title     = ""
             name      = "j131_dthabite_1"
             type      = "text"
             id        = "j131_dthabite_1"
             value     = "<?=@$j131_dthabite ?>"
             size      = "10"
             maxlength = ""
             style     = "background-color:#DEB887;"
             readonly
             autocomplete=''>
      </div>
      <div id="divDtHabite2" style='display: none;'>

      <input name         = "j131_dthabite_2"
             type         = "text"
             id           = "j131_dthabite_2"
             value        = "<?=@$j131_dthabite ?>"
             size         = "10"
             maxlength    = "10"
             onBlur       = 'js_validaDbData(this);'
             onKeyUp      = "return js_mascaraData(this,event)"
             onFocus      = "js_validaEntrada(this);"  >
      <input name="j131_dthabite_2_dia"   type="hidden" title="" id="j131_dthabite_2_dia" value="" size="2"  maxlength="2" >
      <input name="j131_dthabite_2_mes"   type="hidden" title="" id="j131_dthabite_2_mes" value="" size="2"  maxlength="2" >
      <input name="j131_dthabite_2_ano"   type="hidden" title="" id="j131_dthabite_2_ano" value="" size="4"  maxlength="4" >
      <script>
        var PosMouseY, PosMoudeX;
        function js_comparaDatasj131_dthabite(dia,mes,ano){
          var objData        = document.getElementById('j131_dthabite');
          objData.value      = dia+"/"+mes+'/'+ano;
        }
      </script>

      <input value="D" type="button" name="dtjs_j131_dthabite" onclick="pegaPosMouse(event);show_calendar('j131_dthabite','none')"  >

      </div>
    </td>
  </tr>


  <tr>
  	<td nowrap title='<?=$Tj131_obs?>'>
  		<?=$Lj131_obs?>
  	</td>
  	<td>
  		<div id="labelDtHabite1">
  			<?
  				db_textarea('j131_obs', 5, 50, $Ij131_obs, true, 'text', 1);
  			?>
  		</div>
  	</td>
  </tr>

  <tr>
   <td colspan="2" align="center">

     <input name    = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
            type    = "submit"
            id      = "db_opcao"
            value   = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onClick="return js_valida()">

     <input name    = "novo"
            type    = "button"
            id      = "cancelar"
            value   = "Novo"
            onclick = "js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>

  <tr>
   <td colspan="2">
     <?
     if(isset($j131_idcons)){
         $chavepri= array("j131_idcons"=>$j131_idcons,"j131_matric"=>$j131_matric,"j131_sequencial"=>@$j131_sequencial);
         $cliframe_alterar_excluir->chavepri= $chavepri;
         $cliframe_alterar_excluir->sql     = $cliptuconstrhabite->sql_query(null,"*",null, "j131_matric = {$j131_matric} and j131_idcons = {$j131_idcons}");
         $cliframe_alterar_excluir->campos  ="j131_sequencial,j131_matric,j131_idcons,j131_cadhab,j131_codprot,j131_usuario,j131_data,j131_hora";
         $cliframe_alterar_excluir->legenda ="Habite-se cadastrados para a construção";
         $cliframe_alterar_excluir->iframe_width   ="720";
         $cliframe_alterar_excluir->iframe_height   ="180";
         $cliframe_alterar_excluir->msg_vazio ="<small>Não foi encontrado nenhum registro.</small>";
         $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     }
     ?>
   </td>
  </tr>
</table>
</center>
</form>

<script>
function js_valida() {

  if (document.form1.j131_cadhab.value == "") {
	  alert("Informe o Código do Habite-se");
	  document.form1.j131_cadhab.focus();
	  return false;
  } else {
    return true;
  }
}
function js_cancelar(){
  location.href='cad1_iptuconstrhabite001.php?j131_matric='+$F('j131_matric')+'&z01_nome='+encodeURIComponent($F('z01_nome').urlEncode())+'&j131_idcons=<?=$j131_idcons?>';
}

function js_montaCampoProcesso() {

	var lProcesso = document.form1.lProcesso.value;


	if (lProcesso == "S") {

		document.getElementById("ProcessoLabel1").style.display  = '';
		document.getElementById("ProcessoCod1").style.display    = '';
		document.getElementById("j131_codprot1").setAttribute('name','j131_codprot');

		document.getElementById("ProcessoLabel2").style.display = 'none';
	  document.getElementById("ProcessoCod2").style.display   = 'none';
	  document.getElementById("j131_codprot2").setAttribute('name','');


	}	else {

		document.getElementById("ProcessoLabel2").style.display  = '';
		document.getElementById("ProcessoCod2").style.display    = '';
		document.getElementById("j131_codprot2").setAttribute('name','j131_codprot');

		document.getElementById("ProcessoLabel1").style.display = 'none';
	  document.getElementById("ProcessoCod1").style.display   = 'none';
	  document.getElementById("j131_codprot1").setAttribute('name','');


	}
	//data processo

  if (lProcesso == "S") {

    document.getElementById('divDtProt1').style.display = '';
    document.getElementById('divDtProt2').style.display = 'none';

    if (document.getElementById("j131_dtprot_2") == null) {

      document.getElementById("j131_dtprot")    .setAttribute('name','j131_dtprot_2');
      document.getElementById("j131_dtprot_dia").setAttribute('name','j131_dtprot_2_dia');
      document.getElementById("j131_dtprot_mes").setAttribute('name','j131_dtprot_2_mes');
      document.getElementById("j131_dtprot_ano").setAttribute('name','j131_dtprot_2_ano');

      document.getElementById("j131_dtprot")    .setAttribute('id','j131_dtprot_2');
      document.getElementById("j131_dtprot_dia").setAttribute('id','j131_dtprot_2_dia');
      document.getElementById("j131_dtprot_mes").setAttribute('id','j131_dtprot_2_mes');
      document.getElementById("j131_dtprot_ano").setAttribute('id','j131_dtprot_2_ano');

    }

    document.getElementById("j131_dtprot_1")    .setAttribute('name','j131_dtprot');
    document.getElementById("j131_dtprot_1")    .setAttribute('id'  ,'j131_dtprot');

  } else {

    var iCodProt = document.form1.j131_codprot1.value;
    document.getElementById('divDtProt1').style.display = 'none';
    document.getElementById('divDtProt2').style.display = '';

    if(document.getElementById("j131_dtprot_1") == null) {
      document.getElementById("j131_dtprot").setAttribute('name','j131_dtprot_1');
      document.getElementById("j131_dtprot").setAttribute('id'  ,'j131_dtprot_1');
    }

    document.getElementById("j131_dtprot_2")    .setAttribute('name','j131_dtprot');
    document.getElementById("j131_dtprot_2_dia").setAttribute('name','j131_dtprot_dia');
    document.getElementById("j131_dtprot_2_mes").setAttribute('name','j131_dtprot_mes');
    document.getElementById("j131_dtprot_2_ano").setAttribute('name','j131_dtprot_ano');
    document.getElementById("j131_dtprot_2")    .setAttribute('id'  ,'j131_dtprot');
    document.getElementById("j131_dtprot_2_dia").setAttribute('id'  ,'j131_dtprot_dia');
    document.getElementById("j131_dtprot_2_mes").setAttribute('id'  ,'j131_dtprot_mes');
    document.getElementById("j131_dtprot_2_ano").setAttribute('id'  ,'j131_dtprot_ano');

  }
}

function js_pesquisaj131_codprot(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome|p58_dtproc','Pesquisa',true);
	  }else{
	    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?chave_unica='+document.form1.j131_codprot.value+'&funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome|p58_dtproc','Pesquisa',true);
	  }
	}

function js_mostraprotprocesso(chave,chave1,erro) {
	document.form1.p58_requer.value = chave1;
  if(erro==true){
    document.form1.j131_codprot.focus();
    document.form1.j131_codprot.value = '';
  }
}

function js_mostraprotprocesso1(chave1,chave2, chave3) {

  var aDtProt = chave3.split('-');
  document.form1.j131_codprot.value = chave1;
  document.form1.p58_requer.value   = chave2;
  document.form1.j131_dtprot.value  = aDtProt[2] +'/'+
																	  	aDtProt[1] +'/'+
																			aDtProt[0];

  db_iframe_proc.hide();
}

function js_montaCampoHabite() {

	var lHabite = document.form1.lHabite.value;


	if (lHabite == "S") {

		document.getElementById("HabiteLabel1").style.display  = '';
		document.getElementById("HabiteCod1").style.display    = '';
		document.getElementById("j131_cadhab1").setAttribute('name','j131_cadhab');

		document.getElementById("HabiteLabel2").style.display = 'none';
	  document.getElementById("HabiteCod2").style.display   = 'none';
	  document.getElementById("j131_cadhab2").setAttribute('name','');

	}	else {

		document.getElementById("HabiteLabel1").style.display  = 'none';
		document.getElementById("HabiteCod1").style.display    = 'none';
		document.getElementById("j131_cadhab1").setAttribute('name','');

		document.getElementById("HabiteLabel2").style.display  = '';
		document.getElementById("HabiteCod2").style.display    = '';
		document.getElementById("j131_cadhab2").setAttribute('name','j131_cadhab');

	}


	//data habite-se
  if (lHabite == "S") {

    document.getElementById('divDtHabite1').style.display = '';
    document.getElementById('divDtHabite2').style.display = 'none';

    if (document.getElementById("j131_dthabite_2") == null) {

      document.getElementById("j131_dthabite")    .setAttribute('name','j131_dthabite_2');
      document.getElementById("j131_dthabite_dia").setAttribute('name','j131_dthabite_2_dia');
      document.getElementById("j131_dthabite_mes").setAttribute('name','j131_dthabite_2_mes');
      document.getElementById("j131_dthabite_ano").setAttribute('name','j131_dthabite_2_ano');

      document.getElementById("j131_dthabite")    .setAttribute('id','j131_dthabite_2');
      document.getElementById("j131_dthabite_dia").setAttribute('id','j131_dthabite_2_dia');
      document.getElementById("j131_dthabite_mes").setAttribute('id','j131_dthabite_2_mes');
      document.getElementById("j131_dthabite_ano").setAttribute('id','j131_dthabite_2_ano');

    }

    document.getElementById("j131_dthabite_1")    .setAttribute('name','j131_dthabite');
    document.getElementById("j131_dthabite_1")    .setAttribute('id'  ,'j131_dthabite');

  } else {

    document.getElementById('divDtHabite1').style.display = 'none';
    document.getElementById('divDtHabite2').style.display = '';

    if(document.getElementById("j131_dthabite_1") == null) {
	    document.getElementById("j131_dthabite").setAttribute('name','j131_dthabite_1');
	    document.getElementById("j131_dthabite").setAttribute('id'  ,'j131_dthabite_1');
    }

    document.getElementById("j131_dthabite_2")    .setAttribute('name','j131_dthabite');
    document.getElementById("j131_dthabite_2_dia").setAttribute('name','j131_dthabite_dia');
    document.getElementById("j131_dthabite_2_mes").setAttribute('name','j131_dthabite_mes');
    document.getElementById("j131_dthabite_2_ano").setAttribute('name','j131_dthabite_ano');
    document.getElementById("j131_dthabite_2")    .setAttribute('id'  ,'j131_dthabite');
    document.getElementById("j131_dthabite_2_dia").setAttribute('id'  ,'j131_dthabite_dia');
    document.getElementById("j131_dthabite_2_mes").setAttribute('id'  ,'j131_dthabite_mes');
    document.getElementById("j131_dthabite_2_ano")  .setAttribute('id'  ,'j131_dthabite_ano');

  }
}

function js_pesquisaj131_cadhab(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('','db_iframe_obrashabite','func_obrashabite.php?funcao_js=parent.js_mostracadhab1|ob09_codhab|ob09_habite|ob09_data','Pesquisa',true);
	  }else{
			js_OpenJanelaIframe('','db_iframe_obrashabite','func_obrashabite.php?chave_unica='+document.form1.j131_cadhab1.value+'&funcao_js=parent.js_mostracadhab1|ob09_codhab|ob09_habite|ob09_data','Pesquisa',true);
	  }
	}

function js_mostracadhab(chave,chave1,erro) {
	document.form1.ob09_habite.value = chave;
  if (erro==true) {
    document.form1.j131_cadhab.focus();
    document.form1.j131_cadhab.value = '';
  }
}

function js_mostracadhab1(chave1,chave2, chave3) {

  var aDataHabite = chave3.split('-');

	document.form1.j131_cadhab.value   = chave1;
  document.form1.ob09_habite.value   = chave2;
  document.form1.j131_dthabite.value = aDataHabite[2] + '/' +
                                       aDataHabite[1] + '/' +
                                       aDataHabite[0];

  db_iframe_obrashabite.hide();

}
</script>