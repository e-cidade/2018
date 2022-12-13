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

//MODULO: Vacinas
$oDaoVacAplicaanula->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('vc07_i_codigo');
$oRotulo->label('z01_v_nome');
$oRotulo->label('z01_v_mae');
$oRotulo->label('z01_d_nasc');
$oRotulo->label('z01_v_sexo');

$oRotulo->label('vc18_i_aplica');
$oRotulo->label('m61_descr');
$oRotulo->label('vc15_i_lote');
$oRotulo->label('m77_dtvalidade');
$oRotulo->label('nome');
$oRotulo->label('login');
$oRotulo->label('vc01_c_nome');
$oRotulo->label('vc17_i_sala');
$oRotulo->label('vc16_i_cgs');
$oRotulo->label('vc16_d_dataaplicada');
$oRotulo->label('vc16_n_quant');
$oRotulo->label('vc16_i_usuario');
$oRotulo->label('vc16_d_data');
$oRotulo->label('vc16_c_hora');
?>
<form name="form1" method="post" action="">

<center>
<fieldset style='width: 96%;'> <legend><b>Paciente</b></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tvc16_i_cgs?>">
        <?
        db_ancora(@$Lvc16_i_cgs,"js_pesquisavc16_i_cgs(true);",$db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('vc16_i_cgs',10,$Ivc16_i_cgs,true,'text',$db_opcao," onchange='js_pesquisavc16_i_cgs(false);'");
        db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_d_nasc?>">
        <?=$Lz01_d_nasc?>
      </td>
      <td>
        <?
        db_input('z01_d_nasc',10,$Iz01_d_nasc,true,'text',3,"");
        echo"<b>Idade:</b>";
        db_input('iIdade',23,"",true,'text',3,"");
        echo"<b>Sexo:</b>";
        db_input('z01_v_sexo',1,$Iz01_v_sexo,true,'text',3,"");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_v_mae?>">
        <strong><b>Nome da Mãe:</b></strong>
      </td>
      <td>
        <?
        db_input('z01_v_mae', 50, $Iz01_v_mae, true, 'text' ,3, '');
        ?>
      </td>
    </tr>
  </table>
</fieldset>
</center>

<center>
<fieldset style='width: 96%;'> <legend><b>Vacina</b></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=$Tvc18_i_aplica?>">
        <?
        db_ancora($Lvc18_i_aplica, 'js_pesquisavc18_i_aplica(true);', $db_opcao);
        ?>
      </td>
      <td nowrap> 
        <?
        db_input('vc18_i_aplica', 10, $Ivc18_i_aplica, true, 'text', $db_opcao,
                 " onchange='js_pesquisavc18_i_aplica(false);'");
        db_input('vc07_c_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc16_d_dataaplicada?>">
        <?=$Lvc16_d_dataaplicada?>
      </td>
      <td nowrap>
        <?
        db_input('vc16_d_dataaplicada', 10, $Ivc16_d_dataaplicada, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc16_n_quant?>">
        <?=$Lvc16_n_quant?>
      </td>
      <td nowrap>
        <?
        db_input('vc16_n_quant', 10, $Ivc16_n_quant, true, 'text', 3, '');
        echo '<b>Unidade de Saída:</b> ';
        db_input('m61_descr', 10, $Im61_descr, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc15_i_lote?>">
        <?=$Lvc15_i_lote?>
      </td>
      <td nowrap>
        <?
        db_input('vc15_i_lote', 10, $Ivc15_i_lote, true, 'text', 3, '');
        echo $Lm77_dtvalidade;
        db_input('m77_dtvalidade', 10, $Im77_dtvalidade, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc17_i_sala?>">
        <?=$Lvc17_i_sala?>
      </td>
      <td nowrap>
        <?
        db_input('vc01_c_nome', 50, $Ivc01_c_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc16_i_usuario?>">
        <?=$Lvc16_i_usuario?>
      </td>
      <td nowrap>
        <?
        db_input('login', 10, $Ilogin, true, 'text', 3, '');
        db_input('nome', 50, $Inome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tvc16_d_data?>">
        <b>Data Lançamento:</b>
      </td>
      <td nowrap>
        <?
        db_input('vc16_d_data', 10, $Ivc16_d_data, true, 'text', 3, '');
        db_input('vc16_c_hora', 10, $Ivc16_c_hora, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  
  </table>
</fieldset>
</center>
<table>
  <tr>
    <td><?=$Lvc18_t_obs?></td>
    <td><?db_textarea('vc18_t_obs',2,40,$Ivc18_t_obs,true,'text',$db_opcao,"")?></td>
  </tr>
</table>
<input name="anular" type="submit" id="anular" value="Anular Aplicação" onClick="return js_validaEnvio();">
<input type="button" value="Cancelar" onclick="window.location.href = 'vac4_vac_aplicaanula001.php';">
</form>
<script>

sUrl = 'vac4_vacinas.RPC.php';

function js_ajax(oParam, jsRetorno) {

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

  if($F('vc16_i_cgs') == '') {

    alert('Informe um CGS.');
    return false;

  }
  if($F('vc18_i_aplica') == '') {

    alert('Informe a aplicação que deseja anular.');
    return false;

  }

  return true;

}

function js_pesquisavc16_i_cgs(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                        'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae','Pesquisa',true
                       );

  } else {

    if (document.form1.vc16_i_cgs.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostra_cgs|z01_i_cgsund|'+
                          'z01_v_nome|z01_v_sexo|z01_d_nasc|z01_v_mae&chave_z01_i_cgsund='+
                           document.form1.vc16_i_cgs.value+'&nao_mostra=true', 'Pesquisa', false
                         );

    } else {

      document.form1.z01_v_nome.value = ''; 
      document.form1.iIdade.value     = '';
      document.form1.z01_d_nasc.value = '';
      document.form1.z01_v_sexo.value = '';
      document.form1.z01_v_mae.value  = '';

    }

  }

}

function js_mostra_cgs(chave1, chave2, sexo, nasc, mae) {

  /* Limpo as informações da vacina */
  $('vc18_i_aplica').value       = '';
  $('vc07_c_nome').value         = '';
  $('vc16_d_dataaplicada').value = '';
  $('vc16_n_quant').value        = '';
  $('m61_descr').value           = '';
  $('vc15_i_lote').value         = '';
  $('m77_dtvalidade').value      = '';
  $('vc01_c_nome').value         = '';
  $('login').value               = '';
  $('nome').value                = '';
  $('vc16_d_data').value         = '';
  $('vc16_c_hora').value         = '';


  if (chave1 == '') {

    sexo                        = '';
    nasc                        = '';
    mae                         = '';
    document.form1.iIdade.value = '';

  }

  document.form1.vc16_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  document.form1.z01_d_nasc.value = js_formataData(nasc);
  document.form1.z01_v_sexo.value = sexo;
  document.form1.z01_v_mae.value  = mae;
  
  db_iframe_cgs_und.hide();

  if (chave1 != '') {

    oParam            = new Object();
    oParam.exec       = 'getIdadeDiaMesAno';
    oParam.z01_d_nasc = nasc;
    oParam.iCgs       = chave1;
    js_ajax(oParam, 'js_retornoIdade');

  }

}

function js_retornoIdade(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
 
  if (oRetorno.iStatus == 1) {
    $('iIdade').value = oRetorno.iAnos+' anos, '+oRetorno.iMeses+' meses e '+oRetorno.iDias+' dias.';
  } else {

    alert(oRetorno.sMessage.urlDecode());
    document.form1.vc16_i_cgs.value = '';
    document.form1.z01_v_nome.value = '';
    document.form1.z01_d_nasc.value = '';
    document.form1.z01_v_sexo.value = '';
    document.form1.z01_v_mae.value  = '';
    document.form1.iIdade.value     = '';

  }

}

function js_pesquisavc18_i_aplica(mostra) {
  
  if ($F('vc16_i_cgs') == '') {

    alert('Informe um CGS.');
    $('vc18_i_aplica').value = '';
    return false;

  } else {

    sChave = '&chave_cgs='+$F('vc16_i_cgs');

  }

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_vac_aplica', 'func_vac_aplica.php?funcao_js=parent.js_mostra_aplica|'+
                        'vc16_i_codigo|vc07_c_nome|vc16_d_dataaplicada|vc16_n_quant|m61_descr|m77_lote|'+
                        'm77_dtvalidade|vc01_c_nome|login|nome|vc16_d_data|vc16_c_hora'+sChave,
                        'Pesquisa', true
                       );

  } else {

    if ($F('vc18_i_aplica').value != '') {

      js_OpenJanelaIframe('', 'db_iframe_vac_aplica', 'func_vac_aplica.php?funcao_js=parent.js_mostra_aplica|'+
                          'vc16_i_codigo|vc07_c_nome|vc16_d_dataaplicada|vc16_n_quant|m61_descr|m77_lote|'+
                          'm77_dtvalidade|vc01_c_nome|db_login|db_nome|db_vc16_d_data|db_vc16_c_hora&nao_mostra=true'+
                          sChave+'&chave_vc16_i_codigo='+$F('vc18_i_aplica'),
                          'Pesquisa', false
                         );

    } else {
     
      $('vc18_i_aplica').value       = '';
      $('vc07_c_nome').value         = '';
      $('vc16_d_dataaplicada').value = '';
      $('vc16_n_quant').value        = '';
      $('m61_descr').value           = '';
      $('vc15_i_lote').value         = '';
      $('m77_dtvalidade').value      = '';
      $('vc01_c_nome').value         = '';
      $('login').value               = '';
      $('nome').value                = '';
      $('vc16_d_data').value         = '';
      $('vc16_c_hora').value         = '';

    }

  }

}

function js_mostra_aplica(iCod, sNome, dAplic, iQuant, sUnid, sLote, dValidade, sSala, sLogin, 
                          sNomeUsu, dDataSys, sHoraSys) {

  if (iCod == '') {
    dAplic = iQuant = sUnid = sLote = dValidade = sSala = sLogin = sNomeUsu = dDataSys = sHoraSys = '';
  }

  $('vc18_i_aplica').value       = iCod;
  $('vc07_c_nome').value         = sNome;
  $('vc16_d_dataaplicada').value = js_formataData(dAplic);
  $('vc16_n_quant').value        = iQuant;
  $('m61_descr').value           = sUnid;
  $('vc15_i_lote').value         = sLote;
  $('m77_dtvalidade').value      = js_formataData(dValidade);
  $('vc01_c_nome').value         = sSala;
  $('login').value               = sLogin;
  $('nome').value                = sNomeUsu;
  $('vc16_d_data').value         = js_formataData(dDataSys);
  $('vc16_c_hora').value         = sHoraSys;

  db_iframe_vac_aplica.hide();

}

function js_formataData(dData) {
  
  if(dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

</script>