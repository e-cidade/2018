<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Vacinas
$oDaoVacDescarte->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("m77_lote");
$oRotulo->label("vc01_c_nome");
$oRotulo->label("vc06_c_descr");
$oRotulo->label("vc06_i_vacina");
$oRotulo->label("m77_dtvalidade");
?>
<fieldset style='width: 50%;'> <legend><b>Descartar Doses</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc19_i_codigo?>">
       <?=@$Lvc19_i_codigo?>
    </td>
    <td> 
     <?db_input('vc19_i_codigo',10,$Ivc19_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc19_i_sala?>">
      <? db_ancora(@$Lvc19_i_sala,"js_pesquisavc19_i_sala(true);",$db_opcao);?>
    </td>
    <td> 
      <? db_input('vc19_i_sala',10,$Ivc19_i_sala,true,'text',$db_opcao,
                  " onchange='js_pesquisavc19_i_sala(false);'");
         db_input('vc01_c_nome',40,$Ivc01_c_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc19_i_vacina?>">
      <? db_ancora(@$Lvc19_i_vacina,"js_pesquisavc19_i_vacina(true);",$db_opcao);?>
    </td>
    <td> 
    <?
      db_input('vc19_i_vacina',10,$Ivc19_i_vacina,true,'text',$db_opcao,
                " onchange='js_pesquisavc19_i_vacina(false);'");
      db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,'');
      db_input('vc06_i_vacina',10,$Ivc06_i_vacina,true,'hidden',3,'');
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc19_i_matetoqueitemlote?>">
     <?db_ancora(@$Lvc19_i_matetoqueitemlote,"js_pesquisavc19_i_vacinalote(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('vc19_i_matetoqueitemlote',10,$Ivc19_i_matetoqueitemlote,true,'text', 3, ' style="display: none;"')?>
     <?db_input('m77_lote', 10,$Im77_lote,true,'text', $db_opcao, 'onchange="js_pesquisavc19_i_vacinalote(false);"')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tm77_dtvalidade?>">
      <b>Validade:</b>
    </td>
    <td nowrap> 
     <?db_input('m77_dtvalidade', 10, $Im77_dtvalidade, true, 'text', 3, '')?>
      <b>Unidade de Saída:</b>
     <?db_input('m61_descr', 20, '', true, 'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="2">
      <fieldset> <legend><b>Quantidade de Doses Disponível</b></legend>
        <b>Quantidade: </b>
        <?
        db_input('iQuantidade', 4, '', true, 'text', 3, '');
        ?>
        <b> - Aplicadas: </b>
        <?
        db_input('iAplicadas', 4, '', true, 'text', 3, '');
        ?>
        <b> - Descartadas: </b>
        <?
        db_input('iDescartadas', 4, '', true, 'text', 3, '');
        ?>
        <b> = Total: </b>
        <?
        db_input('iTotal', 4, '', true, 'text', 3, '');
        ?>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc19_n_quant?>">
       <?=@$Lvc19_n_quant?>
    </td>
    <td> 
      <?
      db_input('vc19_n_quant',10,$Ivc19_n_quant,true,'text',$db_opcao,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tvc19_t_obs?>">
       <?=$Lvc19_t_obs?>
    </td>
    <td> 
      <?
      db_textarea('vc19_t_obs', 2, 50,$Ivc19_t_obs, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type  = "submit" 
       id    = "db_opcao" value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> <?=($db_opcao != 3 ? "onclick=\"return js_validaEnvio();\"" : "")?> >
<input name  = "cancelar" type  = "button" id = "cancelar" value = "Cancelar" 
       onclick="location.href='vac4_descartar001.php';" <?=(!isset($opcao)?"disabled":"")?>>
</form>
</fieldset>
<script>

if (<?=isset($opcao) ? 'true' : 'false'?>) {
  js_pesquisavc19_i_vacinalote(false);
}

function js_ajax(oParam, jsRetorno) {
  
  sUrl = 'vac4_vacinas.RPC.php';
	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method: 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: 
                                     function(objAjax) {
                          				   
                                       var evlJS = jsRetorno+'(objAjax);';
                                       return eval(evlJS);

                          			     }
                         }
                        );

}

function js_validaEnvio() {

  if ($F('vc19_i_vacina') == '') {

    alert('Informe a vacina.');
    return false;

  }
  
  if ($F('vc19_i_vacinalote') == '' || $F('m77_lote') == '') {

    alert('Informe o lote.');
    return false;

  }
  
  if ($F('vc19_n_quant') == '') {

    alert('Informe a quantidade a ser descartada.');
    return false;

  }
  
  if (parseFloat($F('vc19_n_quant')) <= 0.0) {

    alert('A quantidade a ser descartada tem que ser um valor válido.');
    return false;

  }
  
  if (parseFloat($F('vc19_n_quant')) > parseFloat($F('iTotal'))) {

    alert('Quantidade informada deve ser menor que total de doses disponível.');
    $('vc19_n_quant').value = '';
    return false;

  }
  
  if ($F('vc19_t_obs') == '') {

    alert('Informe o motivo do descarte (observação).');
    return false;

  }

  return true;

}

function js_pesquisavc19_i_vacinalote(mostra) {

  if ($F('vc19_i_vacina') != '') {

    sChave = '&chave_vacina='+$F('vc19_i_vacina');

    if (mostra == true) {

      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_vac_vacinalote',
                          'func_vac_vacinalote.php?funcao_js=parent.js_mostravacinalote|m77_sequencial|'+
                          'm77_lote|m77_dtvalidade|m61_descr|vc15_i_lote|dl_total'+
                          sChave,
                          'Pesquisa',
                          true
                         );

    } else {

       if ($F('m77_lote') != '') {  

          js_OpenJanelaIframe('top.corpo',
                              'db_iframe_vac_vacinalote',
                              'func_vac_vacinalote.php?&funcao_js=parent.js_mostravacinalote|m77_sequencial|m77_lote|'+
                              'm77_dtvalidade|db_m61_descr|vc15_i_lote|dl_total&nao_mostra=true'+
                              '&chave_m77_lote='+$F('m77_lote')+sChave, 'Pesquisa', 
                              false
                             );

       } else {
         js_limpaLote();
       }

    }

  } else {
    
    alert('Selecione uma vacina!');
    $('vc19_i_vacinalote').value = $('m77_lote').value = '';

  }

}

function js_mostravacinalote(iCod, sLote, dValidade, sUnidade, iLote, iQuant) {

  if (iCod == '') {
    dValidade = sUnidade = iLote = iQuant = '';
  }

  $('vc19_i_matetoqueitemlote').value = iCod; 
  $('m77_lote').value                 = sLote; 
  $('m77_dtvalidade').value           = js_formataData(dValidade); 
  $('m61_descr').value                = sUnidade; 
  $('iQuantidade').value              = iQuant; 

  if (iCod != '') {

    oParam             = new Object();
    oParam.exec        = 'getDosesUsadasLote';
    oParam.iVacinaLote = iCod;
    js_ajax(oParam, 'js_retornoDosesUsadasLote');

  } else {

    alert(sLote);
    js_limpaLote();

  }

  db_iframe_vac_vacinalote.hide();

}

function js_retornoDosesUsadasLote(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
 
  if (oRetorno.iStatus == 1) {

   $('iAplicadas').value   = oRetorno.iAplicadas;
   $('iDescartadas').value = oRetorno.iDescartadas;
   $('iTotal').value       = $F('iQuantidade') - oRetorno.iAplicadas - oRetorno.iDescartadas;

  } else {

    alert(oRetorno.sMessage.urlDecode());
    js_limpaLote();

  }

}

function js_limpaLote() {

  $('vc19_i_matetoqueitemlote').value = ''; 
  $('m77_lote').value                 = ''; 
  $('m77_dtvalidade').value           = ''; 
  $('m61_descr').value                = ''; 
  $('iQuantidade').value              = '';
  $('iAplicadas').value               = '';
  $('iDescartadas').value             = '';
  $('iTotal').value                   = '';


}

function js_pesquisavc19_i_vacina(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_vac_vacina',
                        'func_vac_vacina.php?funcao_js=parent.js_mostravac_vacina1|'+
                        'vc06_i_codigo|vc06_c_descr|vc06_i_vacina',
                        'Pesquisa',
                        true
                       );
  } else {

     if ($F('vc19_i_vacina') != '') {  

        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_vac_vacina',
                            'func_vac_vacina.php?pesquisa_chave='+$F('vc19_i_vacina')+
                            '&funcao_js=parent.js_mostravac_vacina',
                            'Pesquisa',
                             false
                           );

     } else {

       $('vc06_c_descr').value = '';
       js_limpaLote();

     }

  }

}

function js_mostravac_vacina(chave,erro,vacina) {
  
  js_limpaLote();
  $('vc06_c_descr').value = chave; 
  if (erro==true) {  

    $('vc19_i_vacina').focus(); 
    $('vc19_i_vacina').value = ''; 

  } else {
	  
    $('vc06_i_vacina').value = vacina;
    js_loadIframe();
    
  }
}

function js_loadIframe() {
  
  sSrc = 'vac4_descartar.iframe.php?vc19_i_vacina='+$('vc19_i_vacina').value;
  this.frame_lotes.location.href = sSrc;
  
}

function js_mostravac_vacina1(chave1,chave2,vacina) {

  js_limpaLote();
  $('vc19_i_vacina').value = chave1;
  $('vc06_c_descr').value  = chave2;
  $('vc06_i_vacina').value = vacina;
  document.getElementById('frame_lotes').src = 'vac4_descartar.iframe.php?vc19_i_vacina='+$('vc19_i_vacina').value;
  js_loadIframe();
  db_iframe_vac_vacina.hide();

}

function js_pesquisavc19_i_sala(mostra) {

	  if (mostra==true) {

	    js_OpenJanelaIframe('top.corpo',
	                        'db_iframe_vac_sala',
	                        'func_vac_sala.php?funcao_js=parent.js_mostravac_sala1|'+
	                        'vc01_i_codigo|vc01_c_nome',
	                        'Pesquisa',
	                        true
	                       );
	  } else {

	     if ($F('vc19_i_sala') != '') {  

	        js_OpenJanelaIframe('top.corpo',
	                            'db_iframe_vac_vacina',
	                            'func_vac_sala.php?pesquisa_chave='+$F('vc19_i_sala')+
	                            '&funcao_js=parent.js_mostravac_sala',
	                            'Pesquisa',
	                             false
	                           );

	     } else {
	       $('vc06_c_descr').value = '';
	     }

	  }

	}

	function js_mostravac_sala(chave,erro) {
	  
	  $('vc01_c_nome').value = chave; 
	  if (erro==true) {  

	    $('vc19_i_sala').focus(); 
	    $('vc19_i_sala').value = ''; 

	  }
	}
	function js_mostravac_sala1(chave1,chave2) {

	  js_limpaLote();
	  $('vc19_i_sala').value = chave1;
	  $('vc01_c_nome').value  = chave2;
	  db_iframe_vac_sala.hide();

	}

function js_formataData(dData) {
  
  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}
</script>

<br><br>
<iframe src="vac4_descartar.iframe.php" name="frame_lotes" id="frame_lotes" width="790" height="180">