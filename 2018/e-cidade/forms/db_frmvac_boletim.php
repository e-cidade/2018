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
$clvac_boletim->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("vc06_c_descr");
?>
<form name="form1" method="post" action="">
<fieldset style='width: 75%;'> 
  <legend>
    <b>Faixa etária Boletim</b>
   </legend>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc13_i_codigo?>">
       <?=@$Lvc13_i_codigo?>
    </td>
    <td> 
     <?db_input('vc13_i_codigo',10,$Ivc13_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc13_i_vacina?>">
     <? db_ancora(@$Lvc13_i_vacina,"js_pesquisavc13_i_vacina(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('vc13_i_vacina',10,$Ivc13_i_vacina,true,'text',$db_opcao,
                " onchange='js_pesquisavc13_i_vacina(false);'")?>
     <?db_input('vc06_c_descr',20,$Ivc06_c_descr,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc13_c_descr?>">
       <?=@$Lvc13_c_descr?>
    </td>
    <td> 
     <?db_input('vc13_c_descr',30,$Ivc13_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 80%;'> <legend><b>Faixa etária</b></legend>
      <table>
       <tr>
        <td>
      <table>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_diaini?>">
            <?=@$Lvc13_i_diaini?>
          </td>
          <td> 
            <?
            db_input('vc13_i_diaini',2,$Ivc13_i_diaini,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_mesini?>">
            <?=@$Lvc13_i_mesini?>
          </td>
          <td> 
            <?
            db_input('vc13_i_mesini',2,$Ivc13_i_mesini,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_anoini?>">
            <?=@$Lvc13_i_anoini?>
          </td>
          <td> 
            <?
            db_input('vc13_i_anoini',3,$Ivc13_i_anoini,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        </table>
        </td>
        <td>
        <table>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_diafim?>">
            <?=@$Lvc13_i_diafim?>
          </td>
          <td> 
            <?
            db_input('vc13_i_diafim',2,$Ivc13_i_diafim,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_mesfim?>">
            <?=@$Lvc13_i_mesfim?>
          </td>
          <td> 
            <?
            db_input('vc13_i_mesfim',2,$Ivc13_i_mesfim,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tvc13_i_anofim?>">
            <?=@$Lvc13_i_anofim?>
          </td>
          <td>
            <?
            db_input('vc13_i_anofim',3,$Ivc13_i_anofim,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      </table>
        </td>
       </tr>
      </table>
      </fieldset>
    </td
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc13_i_situacao?>">
       <?=@$Lvc13_i_situacao?>
    </td>
    <td> 
      <?
        $aTipos= Array("1"=>"ATIVO","2"=>"INATIVO");
        db_select("vc13_i_situacao",$aTipos,$Ivc13_i_situacao,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>
  </center>

</fieldset>
<br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> onclick="return js_valida()">
<input name = "cancelar" type = "button" id = "cancelar" value = "Cancelar" 
       onclick = "location.href='vac1_vac_boletim001.php';" <?=($db_botao1==false?"disabled":"")?> >
<br><br>
<div id="GridBoletim" name="GridBoletim"></div>
</form>
<script>
oGridBoletim = new DBGrid('GridBoletim');
js_init();
<?
  if(isset($vc13_i_vacina)){
    echo "js_pesquisavc13_i_vacina(false)";
  }
?>


//GridExames
function js_init() {

  oGridBoletim.setCellWidth(new Array('25%','10%','10%','10%','10%','10%','10%','15%'));
  var aAligns   = new Array('center','center','center','center','center','center','center','center');
  var arrHeader = new Array (" Descrição ",  
                             " Dia inicial ",
                             " Mês inicial ",
                             " Ano inicial ",
                             " Dia final ",
                             " Mês final ",
                             " Ano final ",
                             " Opção ");
  oGridBoletim.nameInstance = 'GridBoletim';
  oGridBoletim.setCellAlign(aAligns);
  oGridBoletim.setHeader( arrHeader );
  oGridBoletim.setHeight(80);
  
  oGridBoletim.show($('GridBoletim')); 

}

function js_CarregaBoletim() {

  if (document.form1.vc13_i_vacina.value != '') {
    iCodVacina = document.form1.vc13_i_vacina.value;
  } else {
    iCodVacina = 0;
  }
  var oParam        = new Object();
  oParam.exec       = 'getGridBoletim';
  oParam.iCodVacina = iCodVacina;
  js_ajax( oParam, 'js_RetornoCarregaBoletim' );

}

function js_RetornoCarregaBoletim(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {

	  oGridBoletim.clearAll(true);
    iTam = oRetorno.aItens.length;
    for (iX = 0; iX < iTam; iX++) {

       alinha= new Array();
       alinha[0] = oRetorno.aItens[iX][1];
       alinha[1] = oRetorno.aItens[iX][2];
       alinha[2] = oRetorno.aItens[iX][3];
       alinha[3] = oRetorno.aItens[iX][4];
       alinha[4] = oRetorno.aItens[iX][5];
       alinha[5] = oRetorno.aItens[iX][6];
       alinha[6] = oRetorno.aItens[iX][7];
       alinha[7] = '<input name="alterar'+iX+'" type="button" '; 
       alinha[7]+= ' value="Alterar" onClick="js_location(2,'+oRetorno.aItens[iX][0]+')">';
       alinha[7]+= '<input name="alterar'+iX+'" type="button" ';
       alinha[7]+= ' value="Excluir" onClick="js_location(3,'+oRetorno.aItens[iX][0]+')">';
       oGridBoletim.addRow(alinha);

    }
    oGridBoletim.renderRows();

  }else{
    message_ajax(oRetorno.message); 
  }

}

function js_location(iOp,iChave) {
	
  if (iOp == 2) {
    sOp = 'alterar&db_opcao=2';
  } else {
    sOp = 'excluir&db_opcao=3';
  }
  location.href = 'vac1_vac_boletim001.php?opcao='+sOp+'&chavepesquisa='+iChave;
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

function js_valida() {
  oF = document.form1;
  sErro = '';
  if ((oF.vc13_i_diaini.value != "")
     &&(oF.vc13_i_mesini.value != "")
     &&(oF.vc13_i_anoini.value != "")
     &&(oF.vc13_i_diafim.value != "")
     &&(oF.vc13_i_mesfim.value != "")
     &&(oF.vc13_i_anofim.value != "")) {

     if ((parseInt(oF.vc13_i_diaini.value,10) < 0)||(parseInt(oF.vc13_i_diaini.value,10) > 31)) {
       sErro = 'Dia inicial incorreto!';
     }
     
     if ((parseInt(oF.vc13_i_mesini.value,10) < 0)||(parseInt(oF.vc13_i_mesini.value,10) > 12)) {
       sErro = 'mes inicial incorreto!';
     }
     
     if ((parseInt(oF.vc13_i_anoini.value,10) < 0)||(parseInt(oF.vc13_i_anoini.value,10) > 120)) {
       sErro = 'ano inicial incorreto!';
     }
     
     if ((parseInt(oF.vc13_i_diafim.value,10) < 0)||(parseInt(oF.vc13_i_diafim.value,10) > 31)) {
       sErro = 'dia final incorreto!';
     }
     
     if ((parseInt(oF.vc13_i_mesfim.value,10) < 0)||(parseInt(oF.vc13_i_mesfim.value,10) > 12)) {
       sErro = 'mes final incorreto!';
     }
     
     if ((parseInt(oF.vc13_i_anofim.value,10) < 0)||(parseInt(oF.vc13_i_anofim.value,10) > 120)) {
       sErro = 'ano final incorreto!';
     }
     
     if (parseInt(oF.vc13_i_anofim.value,10) < parseInt(oF.vc13_i_anoini.value,10)) {
       sErro = 'ano final menor que inicial!';
     }

  } else {
    sErro = "Preencha a Faixa etaria";
  }
  if (sErro == '') {
    return true;
  } else {
    alert(sErro);
    return false

  }
}

function js_pesquisavc13_i_vacina(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_vac_vacina',
                        'func_vac_vacina.php?funcao_js=parent.js_mostravac_vacina1|vc06_i_codigo|vc06_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

    if (document.form1.vc13_i_vacina.value != '') {  

      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_vac_vacina',
                          'func_vac_vacina.php?pesquisa_chave='+document.form1.vc13_i_vacina.value+
                          '&funcao_js=parent.js_mostravac_vacina',
                          'Pesquisa',
                          false
                         );

    } else {
    	oGridBoletim.clearAll(true);
      document.form1.vc06_c_descr.value = ''; 
    }
  }
}

function js_mostravac_vacina(chave,erro) {

  document.form1.vc06_c_descr.value = chave; 
  if (erro == true) {  

    document.form1.vc13_i_vacina.focus(); 
    document.form1.vc13_i_vacina.value = ''; 
    oGridBoletim.clearAll(true);

  }
  js_CarregaBoletim();
}

function js_mostravac_vacina1(chave1,chave2) {

  document.form1.vc13_i_vacina.value = chave1;
  document.form1.vc06_c_descr.value = chave2;
  db_iframe_vac_vacina.hide();
  js_CarregaBoletim();

}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_vac_boletim',
                      'func_vac_boletim.php?funcao_js=parent.js_preenchepesquisa|vc13_i_codigo',
                      'Pesquisa',
                      true
                     );
}

function js_preenchepesquisa(chave) {

  db_iframe_vac_boletim.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>