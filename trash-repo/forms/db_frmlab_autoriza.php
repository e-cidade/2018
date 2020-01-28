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

//MODULO: Laboratório
$cllab_autoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la22_i_cgs");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("la09_i_exame");
$clrotulo->label("la24_i_laboratorio");
$clrotulo->label("la21_d_data");
$clrotulo->label("la21_c_hora");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
  <center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tla48_i_codigo?>">
        <?=@$Lla48_i_codigo?>
      </td>
      <td> 
        <?db_input('la48_i_codigo',10,$Ila48_i_codigo,true,'text',3,"")?>
      </td>
      <td>
        <table>
          <tr>
            <td>
              <b>Data:</b>
            </td>
            <td> 
              <?db_inputdata('la48_d_data',@$la48_d_data_dia,@$la48_d_data_mes,@$la48_d_data_ano,true,'text',3,"")?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
	  	<td nowrap title="<?=@$Tla22_i_codigo?>">
        <?db_ancora ( '<b>Requisição</b>', "js_pesquisala22_i_codigo(true);", "" );?>
      </td>
		  <td> 
        <?db_input ( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisala22_i_codigo(false);'" )?>
      </td>
      <td>
        <table>
          <tr>
            <td>
              <b>Data:</b>
            </td>
            <td> 
              <?db_inputdata('la22_d_data',@$la22_d_data_dia,@$la22_d_data_mes,@$la22_d_data_ano,true,'text',3,"")?>
            </td>
            <td>
              <b>Login:</b>
            </td>
            <td> 
              <?db_input ( 'nome', 10, @$Inome, true, 'text',3, "" )?>
            </td>
          </tr>
        </table>
      </td>
	  </tr>
	  <tr>
	    <td>
	      <?=@$Lla22_i_cgs?>
	    </td>
	    <td>
	      <?db_input ( 'la22_i_cgs', 10, @$Ila22_i_cgs, true, 'text',3, "" )?>
	    </td>
	    <td>
	      <?db_input ( 'z01_v_nome', 60, @$Iz01_v_nome, true, 'text',3, "" )?>
      </td>
	  </tr>
	  <tr>
	    <td colspan="3">
	      <fieldset><legend>Exames:</legend>
	        <div id="GridExames" id="GridExames"></div>
          <select name="exames" style="display:none"></select>
        </fieldset>
	    </td>
	  </tr>	
  </table>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Autorizar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="limpar" type="button" id="limpar" value="Limpar" onclick="document.location = 'lab4_reqexameaut001.php';" >
</form>
<script>
sRPC = 'lab4_agendar.RPC.php';
objGridExames = new DBGrid(' GridExames ');
F = document.form1;
js_init();

function js_pesquisala22_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&autoriza=1&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome','Pesquisa',true);
  }else{
    if(document.form1.la22_i_codigo.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&autoriza=1&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostrarequisicao','Pesquisa',false);
    }else{
      document.form1.z01_v_nome.value = ''; 
    }
  }
}
function js_mostrarequisicao(chave,erro){
  document.form1.z01_v_nome.value = chave; 
	if (erro == true) { 
	  document.form1.la22_i_codigo.focus(); 
	  document.form1.la22_i_codigo.value = ''; 
	} else {
		js_carregaexames(chave);
	}
}
function js_mostrarequisicao1(chave1,chave2){
  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  js_carregaexames(chave1);
	db_iframe_requisicao.hide();	  
}

/* Funções do Grid */
//grid exames
function js_init() {

     var arrHeader = new Array ( " Cod. ",  
                                 "    <?=substr(@$Lla24_i_laboratorio,8,-10)?>   ", 
                                 "     <?=substr(@$Lla09_i_exame,8,-10)?>     ",
                                 " <?=substr(@$Lla21_d_data,8,-10)?>  ",
                                 " <?=substr(@$Lla21_c_hora,8,-10)?> ",
                                 " Entrega ",
                                 " urgente");

    objGridExames.nameInstance = 'oGridExames';
    objGridExames.setHeader( arrHeader );
    objGridExames.setHeight(80);
    objGridExames.show($('GridExames')); 

}
function js_AtualizaGrid(){

    objGridExames.clearAll(true);
    tam=F.exames.length; 
    for(x=0;x<tam;x++){

       sText     = F.exames.options[x].text;
       avet      = sText.split('#');
       alinha    = new Array();
       alinha[0] = avet[0]; //codigo Setor/Exame
       alinha[1] = avet[1]; //descr  laboratorio
       alinha[2] = avet[2]; //descr  exame
       alinha[3] = avet[3]; //data coleta
       alinha[4] = avet[4]; //hora coleta
       alinha[5] = avet[8]; //data entrega
       scheck    = (avet[6]==1)?' checked ':'';
       alinha[6] = '<input type="checkbox" id="urgente'+x+'" '+scheck+' >';
       objGridExames.addRow(alinha);

    }
    objGridExames.renderRows();

  }
function js_mudadata(data){

  F.la32_d_entrega.value=data;
  
}
function js_carregaexames(requisicao){
  var oParam                 = new Object();
  oParam.exec                = 'CarregaGridRequi';
  oParam.requisicao          = requisicao;
  oParam.iLaboratorioLogado  = <?=$iLaboratorioLogado?>;
  js_ajax( oParam, 'js_retornocarregaexames' );
}
function js_retornocarregaexames(objAjax){
  oAjax=eval("("+objAjax.responseText+")");
  while(F.exames.length>0){
    F.exames.remove(0);
  }
  if (oAjax.status == 1) {
	  F.la22_d_data.value = oAjax.dDataRequi;
	  F.nome.value        = oAjax.sLogin;
	  F.la22_i_cgs.value  = oAjax.iCgs;
	  if (oAjax.alinhasgrid.length > 0) {
      for(x=0;x<oAjax.alinhasgrid.length;x++){
        F.exames.add(new Option(oAjax.alinhasgrid[x],F.exames.length),null);   
      }
      js_AtualizaGrid();
    }else{
      objGridExames.clearAll(true);
    }
  }
}
function js_ajax( objParam,jsRetorno ){
    var objAjax = new Ajax.Request(
                           sRPC, 
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
</script>