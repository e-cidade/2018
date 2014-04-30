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
$clrotulo = new rotulocampo;
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
 
?>

<form name="form1" action="" method="POST" enctype="multipart/form-data" >
<center>
<fieldset style='width: 60%;'> <legend><b>Importar Resultados</b></legend>
<table border="0" width="100%">
  <tr>
    <td nowrap title="<?=@$Tla22_i_codigo?>">
       <?db_ancora ('<b>Requisição</b>', "js_pesquisala22_i_codigo(true);", "");?>
    </td>
    <td> 
      <?db_input ('la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text',"", 
                   " onchange='js_pesquisala22_i_codigo(false);'")?>
      <?db_input ('z01_v_nome', 30, @$Iz01_v_nome, true, 'text', 3, '')?>
    </td>
  </tr> 
</table>
</fieldset>
<br>
<input name="pesquisar" value="Pesquisar" type="button" onClick="js_CarregaItens()">
<input name="limpar" value="Limpar" type="button" onClick="js_limpar()" >
<br><br>
<fieldset style='width: 75%;'> <legend><b>Exames</b></legend>
  <div id="GridExames" name="GridExames"></div>
  <input name="sDados" id="sDados" value="" type="hidden" >
  <input name="sCod"   id="sCod"   value="" type="hidden" >
</fieldset>
<br>
<input name="confirma" id="confirma" type  = "submit"  value = "confirma" onclick="return js_valida();" disabled  >
</center>
</form>
<script>
oGridExames = new DBGrid('GridExames');
aDados = new Array();
aCod   = new Array();
js_init();

function js_valida(){
  for (iX=0; iX < aDados.length; iX++) {
    if (document.getElementById('proc'+aDados[iX]).value == '') {

      alert(' Selecione um procedimento procedimento! ');
      return false;
    
    }
	}
	$('sDados').value = aDados.join(',');    
	$('sCod').value   = aCod.join(',');
  return true;

}

//GridExames
function js_init() {

  var arrHeader = new Array (" Código ",  
                             " Exame ",
                             " Arquivo ",
                             " Procedimento ",
                             " Valor ",
                             " Usuário ",
                             " Data ",
                             " Hora ");
  oGridExames.nameInstance = 'GridExames';
  oGridExames.setHeader( arrHeader );
  oGridExames.setHeight(80);
  
  oGridExames.show($('GridExames')); 

}

function js_CarregaItens() {
  
  if ($F('la22_i_codigo') == '') {

    alert('Seleciona uma Requisição!');
    oGridExames.clearAll(true);
    return false;

  }
  var oParam    = new Object();
  oParam.exec   = 'getGridExames';
  oParam.iRequi = $F('la22_i_codigo');
  js_ajax( oParam, 'js_RetornoCarregaItens' );

}

function js_RetornoCarregaItens(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    oGridExames.clearAll(true);
    iTam = oRetorno.aItens.length;
    
    for (iX = 0; iX < iTam; iX++) {

    	 if (oRetorno.aItens[iX].conferencia != '' && 
    	     oRetorno.aItens[iX].conferencia != null &&
    	     oRetorno.aItens[iX].arqui != '' && 
           oRetorno.aItens[iX].arqui != null
    	     ) {
        	 
    	   aConf   = oRetorno.aItens[iX].conferencia.split('__');
         sProc   = aConf[0];
         aValor  = aConf[1].split('.');
         if (aValor.length == 2) {
        	 if (aValor[1] < 10) {
        	   sValor  = aConf[1]+'0';
        	 } else {
        		 sValor  = aConf[1];
           }
         } else {
        	 sValor  = aConf[1]+'.00';  
         }
         sLogin  = aConf[2];
         aData   = aConf[3].split('-');
         sData   = aData[2]+'/'+aData[1]+'/'+aData[0];
         sHora   = aConf[4];
         sUsuCod = aConf[5];
         sProCod = aConf[6];
         sArqui  = oRetorno.aItens[iX].arqui; 
    	   
    	 } else {

    		 sProc   = '';
    		 sProCod = ''; 
    		 sValor  = '';
         sLogin  = '';
         sData   = '';
         sHora   = '';
         sArqui  = '';
         sUsuCod = ''; 
           
  	   }
       alinha= new Array();
       alinha[0] = oRetorno.aItens[iX].codigo;
       alinha[1] = oRetorno.aItens[iX].descr.urlDecode();
       sDisabled = ' readonly style="background-color: rgb(222, 184, 135);" ';
       alinha[2] = '<input type="file" id="file'+iX+'" name="file'+iX+'" ';
       if (sUsuCod != '' && sUsuCod != <?=$iUsuario?>) {
    	   alinha[2] += sDisabled+' disabled ';  
       }
       iNumproc  = sProCod;
       if (iNumproc == '') {
    	   iNumproc = '0';
       }
       alinha[2]+= '  onchange="js_addIten('+iX+','+oRetorno.aItens[iX].codigo+','+iNumproc+',this.value)">';
       alinha[3] = '<input type="text" id="proc'+iX+'" name="proc'+iX+'" value="'+sProc+'" size="15" ';
       if (sUsuCod != '' && sUsuCod != <?=$iUsuario?>) {
           alinha[3] += sDisabled;  
       }
       alinha[3]+= ' onchange="js_buscaProc(this.value,'+iX+')" > ';
       alinha[3]+= '<input type="hidden" id="iproc'+iX+'" name="iproc'+iX+'" value="'+sProCod+'" size="15">';
       alinha[4] = '<input type="text" id="valor'+iX+'" name="valor'+iX+'" value="R$ '+sValor+'" size="5" '+sDisabled+' >';
       alinha[5] = sLogin;
       alinha[6] = sData;
       alinha[7] = sHora;
       oGridExames.addRow(alinha);

    }
    oGridExames.renderRows();

  } else {

    oGridExames.clearAll(true);
    alert(oRetorno.message.urlDecode());

  }

}

function js_addIten(iIndex,iCod,proc,valor) {
  if (valor != '') {
	  
    iLarg = aCod.indexOf(iCod);
    if (iLarg == -1) {
        
      aCod[aCod.length] = iCod;  
    	iLarg             = aCod.length;
    	
    }
	  aDados[iLarg-1] = iIndex;
	  
  } else {
	  
	  iLarg = aCod.indexOf(iCod);
	  aCod.splice(iLarg,1);
	  aDados.splice(iLarg,1);
	  
  }

  if (aDados.length > 0) {
    $('confirma').disabled = false;
  } else {
    $('confirma').disabled = true;
  }
}

function js_ajax( objParam,jsRetorno ) {
    var objAjax = new Ajax.Request(
                           'lab4_agendar.RPC.php', 
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

function js_limpar() {

  $('la22_i_codigo').value = '';
  $('z01_v_nome').value    = '';
  oGridExames.clearAll(true);

}

function js_pesquisala22_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_requisicao','func_lab_requisicao.php?autoriza=2&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome','Pesquisa',true);
  }else{
     if(document.form1.la22_i_codigo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_requisicao','func_lab_requisicao.php?autoriza=2&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostrarequisicao','Pesquisa',false);
     }else{
    	 js_limpar(); 
     }
  }
}
function js_mostrarequisicao(chave,erro){
  document.form1.z01_v_nome.value = chave; 
  if (erro == true) { 
    document.form1.la22_i_codigo.focus(); 
    js_limpar(); 
  }
}
function js_mostrarequisicao1(chave1,chave2){
  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_lab_requisicao.hide();    
}

//lookup procedimentos
function js_buscaProc (sProcedimento,iIndex) {
	if (sProcedimento != '') {
		
    var objParam                 = new Object();
    objParam.exec                = "getProcedimento";
    objParam.rh70_sequencial     = <?=$iResponsavelTecnico?>;
    objParam.sd63_c_procedimento = sProcedimento;
    objParam.iIndex              = iIndex;
    js_ajax( objParam, 'js_retornoProcedimento' );
    
	} else {

		document.getElementById('iproc'+iIndex).value = '';
		document.getElementById('valor'+iIndex).value = '';

  }
	
}
function js_retornoProcedimento (oAjax) {
	
	oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
	  
	  document.getElementById('iproc'+oRetorno.iIndex).value = oRetorno.itens[0].sd63_i_codigo;
	  sValor = parseFloat(oRetorno.itens[0].sd63_f_sa)+parseFloat(oRetorno.itens[0].sd63_f_sp);
	  sValor = sValor.toString();
	  aValor = sValor.split('.');
 	  if (aValor.length == 2) {
 	    if (aValor[1] < 10) {
        sValor  = sValor+'0';
      } else {
        sValor  = sValor;
      }
    } else {
      sValor  = sValor+'.00';  
    }
	  document.getElementById('valor'+oRetorno.iIndex).value = 'R$ '+sValor;
	  
  } else {
	  
	  document.getElementById('iproc'+oRetorno.iIndex).value = '';
	  document.getElementById('proc'+oRetorno.iIndex).value  = '';
	  document.getElementById('valor'+oRetorno.iIndex).value = '';
	  alert(oRetorno.message.urlDecode());
	  
  }
  
}
</script>