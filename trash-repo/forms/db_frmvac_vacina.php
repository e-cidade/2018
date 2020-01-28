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
$oDaoVacVacina->rotulo->label();
$oDaoVacVacinaMaterial->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("m60_descr");
$oRotulo->label("vc04_c_descr");
?>
<fieldset style='width: 600;'> 
  <legend><b>Vacina:</b></legend>
  <form name="form1" method="post" action="">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tvc06_i_codigo?>">
            <?=@$Lvc06_i_codigo?>
          </td>
          <td> 
            <?
            db_input('vc06_i_codigo', 10, $Ivc06_i_codigo, true, 'text', 3, "");
            db_input('vc06_i_orden', 10, $Ivc06_i_orden, true, 'hidden', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc06_c_descr?>">
            <b>Material:</b>
          </td>
          <td> 
            <?
            db_input('vc06_c_descr', 52, $Ivc06_c_descr, true, 'text', $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc06_c_codpni?>">
            <?=@$Lvc06_c_codpni?>
          </td>
          <td>
            <?
            db_input('vc06_c_codpni', 10, $Ivc06_c_codpni, true, 'text', $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc06_i_tipovacina?>">
            <?
            db_ancora(@$Lvc06_i_tipovacina, "js_pesquisavc06_i_tipovacina(true);", $db_opcao);
            ?>
          </td>
          <td> 
            <?
            db_input('vc06_i_tipovacina', 10, $Ivc06_i_tipovacina, true, 'text', $db_opcao,
                     " onchange='js_pesquisavc06_i_tipovacina(false);'");
            db_input('vc04_c_descr', 40, $Ivc04_c_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc06_i_basico?>">
            <?=@$Lvc06_i_basico?>
          </td>
          <td> 
            <?
              $x = array('1'=>'SIM', '2'=>'NÃO');
              db_select('vc06_i_basico', $x, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc06_i_situacao?>">
            <?=@$Lvc06_i_situacao?>
          </td>
          <td> 
            <?
            $x = array('1'=>'ATIVA', '2'=>'INATIVA');
            db_select('vc06_i_situacao',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </center>
  <?
  if ($db_opcao == 2) {
  ?>
  <fieldset style="width: 600; margin-bottom: 6px;">
    <legend><b>Itens:</b></legend>
    <table>
      <tr>
        <td nowrap title="<?=@$Tvc29_i_vacina?>">
          <?
          db_ancora(@$Lvc29_i_vacina, "js_pesquisavc29_i_material(true);", $db_opcao);
          ?>
        </td>
        <td> 
          <?
          db_input('vc29_i_codigo', 10, $Ivc29_i_codigo, true, 'hidden', 3, '');
          db_input('vc29_i_material', 10, $Ivc29_i_material, true, 'text', $db_opcao,
                   " onchange='js_pesquisavc29_i_material(false);'");
          db_input('m60_descr', 40, $Im60_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tvc29_i_dose?>">
          <b>Doses:</b>
        </td>
        <td> 
          <?
          db_input('vc29_i_dose', 10, $Ivc29_i_dose, true, 'text', $db_opcao, '');
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" style="margin: 6px;">
          <input type="button" name="incluir_material" id="incluir_material" value="Lançar" 
                 onclick="js_Material(1)">     
        </td>
      </tr>
    </table>
    <div id="GridVacinas" id="GridVacinas">
    </div>
  </fieldset>
  <? 
  } 
  ?>
  <input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type  = "submit" id = "db_opcao"
         value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?
  if (($db_opcao == 2) || ($db_opcao==22)) {
  ?>
  <input name = "ordenar" type = "button" id = "ordenar" value = "Ordenar" onclick = "js_ordenar();" >
  <?
  }
  ?>
</form>

<script>
var sRPC         = 'vac4_vacinas.RPC.php';
var oGridVacinas = new DBGrid('GridVacinas');
var F = document.form1;
<?
if ($db_opcao == 2) {
?>
  js_init();
<?
}
?>
function js_init() {

  oGridVacinas.setCellWidth(new Array('15%', '60%', '15%','10%'));
  oGridVacinas.nameInstance = 'oGridVacinas';
  var arrHeader             = new Array ("Codigo",  
                                         "Descrição", 
                                         "Doses",
                                         "Opções"
                                        );
  oGridVacinas.setHeader(arrHeader);
  oGridVacinas.setHeight(80);
  oGridVacinas.show($('GridVacinas')); 
  js_load();
  
}

function js_load() {
	
  if ($F('vc06_i_codigo') != '') {
    
    var oParam     = new Object();
    oParam.exec    = 'getVacinaMaterial';
    oParam.iVacina = $F('vc06_i_codigo');
    js_ajax(oParam, 'js_retornoLoad');
    
  }
  
}

function js_retornoLoad(objAjax) {
	
  oAjax = eval("("+objAjax.responseText+")");
  if (oAjax.iStatus == 1) {

	oGridVacinas.clearAll(true);
    var iTam = oAjax.aItens.length;
    for (iI = 0; iI < iTam; iI++) {
        
      alinha      = new Array();
      alinha[0]   = oAjax.aItens[iI][0];
      alinha[1]   = oAjax.aItens[iI][1];
      alinha[2]   = oAjax.aItens[iI][2];
      sParametro  = oAjax.aItens[iI][0]+',\''+oAjax.aItens[iI][1]+'\'';
      alinha[3]   = '&nbsp;<input type="button" onclick="js_Material(3,'+sParametro+');" value="E">';
      oGridVacinas.addRow(alinha);
      
    }
    oGridVacinas.renderRows();
    
  }
  
}

function js_loadMaterial(iCodigo, sDescr, iDose, iMaterial) {
  
  $('vc29_i_codigo').value             = iCodigo; 
  $('vc29_i_dose').value               = iDose;
  $('vc29_i_material').value           = iMaterial;
  $('m60_descr').value                 = sDescr;
  $('incluir_material').style.display  = 'none';
  
}

function js_cancelarMaterial() {
  
  js_limparMaterial();
  $('incluir_material').style.display  = '';
  
}

function js_Material(iOp, iCodigo, sDescr){
  
  if (iOp == 3) {

    if (!confirm("Tem certeza que deseja excluir o material "+sDescr+"?")) {
      return false;
    } else {
      $('vc29_i_codigo').value = iCodigo;
    }
    
  }  
  var oParam       = new Object();
  oParam.exec      = 'VacinaMaterial';
  oParam.iOp       = iOp;
  oParam.iCodigo   = $F('vc29_i_codigo');
  oParam.iVacina   = $F('vc06_i_codigo');
  oParam.iMaterial = $F('vc29_i_material');
  oParam.iDose     = $F('vc29_i_dose');
  js_ajax( oParam, 'js_retornoMaterial' );
  
}

function js_retornoMaterial(objAjax) {
	
  oAjax = eval("("+objAjax.responseText+")");
  if (oAjax.iStatus == 1) {
	  
    js_cancelarMaterial();
    js_load();
    
  } else {
    alert(oAjax.sMessage);
  }
  
}

function js_limparMaterial() {

  $('vc29_i_codigo').value            = ''; 
  $('vc29_i_dose').value              = '';
  $('vc29_i_material').value          = '';
  $('m60_descr').value                = '';

}
function js_ajax(objParam, jsRetorno) {
	
  var objAjax = new Ajax.Request(
                                 sRPC, 
                                 {
                                 method    : 'post', 
                                 parameters: 'json='+Object.toJSON(objParam),
                                 onComplete: function(objAjax) { 
                                               var evlJS = jsRetorno+'( objAjax );';
                                               eval( evlJS );
                                             }
                                 }
                                );
  
}

function  js_ordenar(iCgs, iVacinaDoseCodigo, iUnidade, iVacina, sVacinaDose) {

  top     = (screen.availHeight - 600) / 2;
  left    = (screen.availWidth - 600) / 2;   
  js_OpenJanelaIframe("", 
		              "db_iframe_ordenar", 
		              "vac1_vac_ordenavacina.iframe.php",
		              "Ordenar Vacinas",
		              true,
		              top, 
		              left, 
		              500, 
		              300
		             );

}

function js_pesquisavc29_i_material(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_matmater',
                        'func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form1.vc29_i_material.value != '') { 

       js_OpenJanelaIframe('',
                           'db_iframe_matmater',
                           'func_matmater.php?pesquisa_chave='+document.form1.vc29_i_material.value+
                           '&funcao_js=parent.js_mostramatmater',
                           'Pesquisa',
                           false
                          );

     } else {
       document.form1.m60_descr.value = ''; 
     }
     
  }
  
}

function js_mostramatmater(sChave, lErro) {

  document.form1.m60_descr.value = sChave; 
  if (lErro == true) { 

    document.form1.vc29_i_material.focus(); 
    document.form1.vc29_i_material.value = ''; 

  }
  
}

function js_mostramatmater1(iChave1, sChave2) {

  document.form1.vc29_i_material.value = iChave1;
  document.form1.m60_descr.value       = sChave2;
  db_iframe_matmater.hide();

}

function js_pesquisavc06_i_tipovacina(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_vac_tipovacina',
                        'func_vac_tipovacina.php?funcao_js=parent.js_mostravac_tipovacina1|vc04_i_codigo|vc04_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {
	  
     if (document.form1.vc06_i_tipovacina.value != '') { 

       js_OpenJanelaIframe('',
                           'db_iframe_vac_tipovacina',
                           'func_vac_tipovacina.php?pesquisa_chave='+document.form1.vc06_i_tipovacina.value+
                           '&funcao_js=parent.js_mostravac_tipovacina',
                           'Pesquisa',
                           false
                          );

     } else {
       document.form1.vc04_c_descr.value = ''; 
     }
     
  }
  
}

function js_mostravac_tipovacina(sChave,erro) {

  document.form1.vc04_c_descr.value = sChave; 
  if (lErro == true) {  

    document.form1.vc06_i_tipovacina.focus(); 
    document.form1.vc06_i_tipovacina.value = ''; 

  }
  
}

function js_mostravac_tipovacina1(iChave1, sChave2) {

  document.form1.vc06_i_tipovacina.value = iChave1;
  document.form1.vc04_c_descr.value      = sChave2;
  db_iframe_vac_tipovacina.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'db_iframe_vac_vacina',
                      'func_vac_vacina.php?funcao_js=parent.js_preenchepesquisa|vc06_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(iChave) {

  db_iframe_vac_vacina.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+iChave";
  }
  ?>
  
}

function js_fechaOrdenar() {

  db_iframe_ordenar.hide();
  location.href = 'vac1_vac_vacina005.php?chavepesquisa='+document.form1.vc06_i_codigo.value;

}
</script>