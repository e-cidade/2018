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
$clvac_vacinalote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m77_lote");
$clrotulo->label("m71_quant");
$clrotulo->label("m61_descr");
$clrotulo->label("m77_dtvalidade");
$clrotulo->label("vc06_c_descr");
$clrotulo->label("vc06_i_vacina");
?>
<fieldset style='width: 75%;'> <legend><b>Vacina Lote</b></legend>
<form name="form2" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc15_i_codigo?>">
       <?=@$Lvc15_i_codigo?>
    </td>
    <td> 
     <?db_input('vc15_i_codigo',10,$Ivc15_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc15_i_vacina?>">
      <? db_ancora(@$Lvc15_i_vacina,"js_pesquisavc15_i_vacina(true);",$db_opcao);?>
    </td>
    <td> 
    <?
      db_input('vc15_i_vacina',10,$Ivc15_i_vacina,true,'text',$db_opcao,
                " onchange='js_pesquisavc15_i_vacina(false);'");
      db_input('vc06_c_descr',40,$Ivc06_c_descr,true,'text',3,'');
      db_input('vc06_i_vacina',10,$Ivc06_i_vacina,true,'hidden',3,'');
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc15_i_lote?>">
     <?db_ancora(@$Lvc15_i_lote,"js_pesquisavc15_i_lote(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('vc15_i_lote',10,$Ivc15_i_lote,true,'text',$db_opcao," onchange='js_pesquisavc15_i_lote(false);'")?>
     <?db_input('m77_lote',40,$Im77_lote,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm61_descr?>">
      <?=@$Lm61_descr?>
    </td>
    <td>
    <?db_input('m61_descr',10,$Im61_descr,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm77_dtvalidade?>">
      <?=@$Lm77_dtvalidade?>
    </td>
    <td>
    <?db_inputdata('m77_dtvalidade',@$m77_dtvalidade_dia,@$m77_dtvalidade_mes,@$m77_dtvalidade_ano,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm71_quant?>">
      <strong><b>Quantidade do Lote:</b></strong> 
    </td>
    <td>
    <?db_input('m71_quant',10,$Im71_quant,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc15_n_quant?>">
       <?=@$Lvc15_n_quant?>
    </td>
    <td> 
    <?db_input('vc15_n_quant',10,$Ivc15_n_quant,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type  = "submit" 
       id    = "db_opcao" value = "<?=($db_opcao==1?"Gravar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name = "cancelar" type = "button" id = "cancelar" value   = "Cancelar" 
       onclick = "location.href='vac4_liberar001.php';" <?=($db_botao1==false?"disabled":"")?> >
</form>
</fieldset>
<br><br>
<div id="GridLotes" name="Gridlotes"></div>
<script>
oGridLotes = new DBGrid('GridLotes');
js_init();

//GridExames
function js_init() {

	oGridLotes.setCellWidth(new Array('10%','45%','35%','10%'));
  var arrHeader = new Array (" Codigo ",  
                             " Vacina ",
                             " Lote ",
                             " Opções ");
  oGridLotes.nameInstance = 'GridLotes';
  oGridLotes.setHeader( arrHeader );
  oGridLotes.setHeight(80);
  
  oGridLotes.show($('GridLotes')); 

  js_CarregaLotes();

}

function js_CarregaLotes() {

  if (document.form2.vc15_i_vacina.value != '') {
    iCodVacina = document.form2.vc15_i_vacina.value;
  } else {
    iCodVacina = 0;
  }
  var oParam        = new Object();
  oParam.exec       = 'getGridLotes';
  oParam.iCodVacina = iCodVacina;
  js_ajax( oParam, 'js_RetornoCarregaLotes' );

}

function js_RetornoCarregaLotes(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {

    oGridLotes.clearAll(true);
    iTam = oRetorno.aItens.length;
    for (iX = 0; iX < iTam; iX++) {

       alinha= new Array();
       alinha[0] = oRetorno.aItens[iX][0];
       alinha[1] = oRetorno.aItens[iX][1];
       alinha[2] = oRetorno.aItens[iX][2];
       alinha[3] = '<input name="alterar'+iX+'" type="button" '; 
       alinha[3]+= ' value="Alterar" onClick="js_location(2,'+oRetorno.aItens[iX][0]+')">';
       alinha[3]+= '<input name="alterar'+iX+'" type="button" ';
       alinha[3]+= ' value="Excluir" onClick="js_location(3,'+oRetorno.aItens[iX][0]+')>';
       oGridLotes.addRow(alinha);

    }
    oGridLotes.renderRows();

  } else {
    message_ajax(oRetorno.message); 
  }

}

function js_location(iOp,iChave) {
	
  if (iOp == 2) {
    sOp = 'alterar';
  } else {
    sOp = 'excluir';
  }
  location.href = 'vac4_liberar001.php?opcao='+sOp+'&vc15_i_codigo='+iChave;

}

function js_ajax( objParam,jsRetorno ) {
	
	  var objAjax = new Ajax.Request(
	                         'vac4_vacinas.RPC.php', 
	                         {
	                          method    : 'post', 
	                          parameters: 'json='+Object.toJSON(objParam),
	                          onComplete: function(objAjax){
	                                  var evlJS = jsRetorno+'( objAjax );';
	                                  eval( evlJS );
	                                }
	                         }
	                        );
	}

function js_pesquisavc15_i_lote(mostra) {

  if (document.form2.vc06_i_vacina.value != '') {

    if (mostra == true) {

      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_matestoqueitemlote',
                          'func_vac_matestoqueitemlote.php?iVacina='+document.form2.vc06_i_vacina.value+
                          '&funcao_js=parent.js_mostramatestoqueitemlote1|m77_sequencial|m77_lote|m77_dtvalidade|m71_quant',
                          'Pesquisa',
                          true
                         );

    } else {

       if (document.form2.vc15_i_lote.value != '') {  

          js_OpenJanelaIframe('top.corpo',
                              'db_iframe_matestoqueitemlote',
                              'func_vac_matestoqueitemlote.php?iVacina='+document.form2.vc06_i_vacina.value+
                              '&pesquisa_chave='+document.form2.vc15_i_lote.value+
                              '&funcao_js=parent.js_mostramatestoqueitemlote',
                              'Pesquisa',
                              false
                             );

       } else {
         document.form2.m77_lote.value = ''; 
       }
    }
  } else {
    
    alert('Selecione uma vacina!');
    document.form2.vc15_i_lote.value = ''

  }
}

function js_mostramatestoqueitemlote(chave, erro, sValidade, sQuantidade) {

  document.form2.m77_lote.value = chave; 
  if (erro == true) { 

    document.form2.vc15_i_lote.focus(); 
    document.form2.vc15_i_lote.value = ''; 

  } else {
	  
    aVet                                = sValidade.split('-');
    sValidade                           = aVet[2]+'/'+aVet[1]+'/'+aVet[0];
    document.form2.m77_dtvalidade.value = sValidade;
    document.form2.m71_quant.value      = sQuantidade;
    
  }
}

function js_mostramatestoqueitemlote1(chave1, chave2, sValidade, sQuantidade) {

  document.form2.vc15_i_lote.value    = chave1;
  document.form2.m77_lote.value       = chave2;
  aVet                                = sValidade.split('-');
  sValidade                           = aVet[2]+'/'+aVet[1]+'/'+aVet[0];
  document.form2.m77_dtvalidade.value = sValidade;
  document.form2.m71_quant.value      = sQuantidade;
  db_iframe_matestoqueitemlote.hide();

}

function js_pesquisavc15_i_vacina(mostra) {
	
  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_vac_vacina',
                        'func_vac_vacina.php?funcao_js=parent.js_mostravac_vacina1|'+
                        'vc06_i_codigo|vc06_c_descr|vc06_i_vacina|m61_descr',
                        'Pesquisa',
                        true
                       );
  } else {

     if (document.form2.vc15_i_vacina.value != '') {  

        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_vac_vacina',
                            'func_vac_vacina.php?pesquisa_chave='+document.form2.vc15_i_vacina.value+
                            '&funcao_js=parent.js_mostravac_vacina',
                            'Pesquisa',
                             false
                           );
     } else {
       document.form2.vc06_c_descr.value = '';
       js_CarregaLotes(); 
     }
  }
}

function js_mostravac_vacina(chave,erro,vacina,unidade) {

  document.form2.vc06_c_descr.value = chave; 
  if (erro == true) {  

    document.form2.vc15_i_vacina.focus(); 
    document.form2.vc15_i_vacina.value = ''; 

  } else {

    document.form2.vc06_i_vacina.value = vacina;
    document.form2.m61_descr.value     = unidade;

  }
  js_CarregaLotes();
}

function js_mostravac_vacina1(chave1,chave2,vacina,unidade) {

  document.form2.vc15_i_vacina.value = chave1;
  document.form2.vc06_c_descr.value  = chave2;
  document.form2.vc06_i_vacina.value = vacina;
  document.form2.m61_descr.value     = unidade;
  db_iframe_vac_vacina.hide();
  js_CarregaLotes();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_vac_vacinalote',
                      'func_vac_vacinalote.php?funcao_js=parent.js_preenchepesquisa|vc15_i_codigo',
                      'Pesquisa',
                       true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_vacinalote.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>