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

//MODULO: protocolo
$clproctransfer->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
?>
<style>
.ordenador {
  cursor: pointer;
}
</style>
<form name="form1" id="cria" method="post" action="">
<center>
<fieldset style="width: 633px;">
<legend><strong>Trâmite</strong></legend>
<table border="0">
  <tr>
    <td nowrap title="Usuário">
      <b>Usuário:</b> 
    </td>
    <td> 
     <?
       $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
       echo pg_result(db_query($sql),0,"nome");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Usuário">
      <b>Departamento:</b> 
    </td>
    <td> 
     <?
       $sql = "select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto");
       echo pg_result(db_query($sql),0,"descrdepto");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_codtran?>">
      <?=@$Lp62_codtran?>
    </td>
    <td> 
			<?
			  db_input('p62_codtran',10,$Ip62_codtran,true,'text',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_dttran?>">
      <?=@$Lp62_dttran?>
    </td>
    <td> 
			<?
			  db_inputdata('p62_dttran',@$p62_dttran_dia,@$p62_dttran_mes,@$p62_dttran_ano,true,'text',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_coddeptorec?>" >
      <?
        db_ancora(@$Lp62_coddeptorec,"js_pesquisap62_coddeptorec(true);",$db_opcao);
      ?>
    </td>
    <td nowrap>
      <?
        db_input('p62_coddeptorec',10,$Ip62_coddeptorec,true,'text',$db_opcao," onchange='js_pesquisap62_coddeptorec(false);'");
        db_input('descrdepto',60,$Idescrdepto,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td  nowrap title="<?=@$Tp62_id_usorec?>">
       <?=@$Lp62_id_usorec; ?>
    </td>
    <td nowrap>
       <? 
         $aUsuarios = array("0" => "Selecione o Usuário");
         db_select("p62_id_usorec",$aUsuarios,true,$db_opcao);
       ?>
    </td>
  </tr>
</table>
</fieldset>
<fieldset style="width:800; margin-bottom:5px;">
<legend><strong>Processos Existentes</strong></legend>
<div id="ctnProcessos"></div>
<?

if(!isset($ordem) || $ordem == ''){
  $ordem = " p58_codproc";
}

$iInstituicao    = db_getsession('DB_instit');
	    
$sqlParametro    = "select p90_traminic from protparam where p90_instit = {$iInstituicao}";
$rsParametro     = db_query($sqlParametro);
$linhasParametro = pg_num_rows($rsParametro);
	    
db_fieldsmemory($rsParametro,0);

db_input('ordem'       ,5,"",true,'hidden',3,"");
db_input('usuario'     ,5,"",true,'hidden',3,"");
db_input('p90_traminic',5,"",true,'hidden',3,"");
db_input('depart'      ,5,"",true,'hidden',3,"");

?> 

    </fieldset>
  </center>
  <input name="id_usuario" type="hidden" id="id_usuario" value="<?=db_getsession('DB_id_usuario')?> ">
  <input name="grupo"      type="hidden" id="grupo"      value="<?=$grupo?> ">
  <input name="db_opcao"   type="button" id="db_opcao"   value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>"  onClick="js_validaProcessos();">
  <input name="pesquisar"  type="button" id="pesquisar"  value="Pesquisar" onClick="js_pesquisa();" >
</form>
<script>

var sUrl   = window.location.search;
var oUrl   = null;

if (sUrl) {
  oUrl = js_urlToObject(sUrl);
}

var processa = true;
var sUrlRPC = "pro4_proctransf.RPC.php";

oDBGridProcessosDif = new DBGrid('difprocessos');
oDBGridProcessosDif.nameInstance = 'oDBGridProcessosDif';
oDBGridProcessosDif.setHeader(new Array('Processo','Requerente','Número Dias','Seguir no Andamento','obj'));
oDBGridProcessosDif.setHeight(290);
oDBGridProcessosDif.setCellWidth(new Array(60,180,100,110,10));
oDBGridProcessosDif.setCellAlign(new Array('center','left','center','center','center'));
oDBGridProcessosDif.aHeaders[4].lDisplayed = false;

js_carregaGridProcessos();

/**
 * Função que monta a grid de processos.
 */
function js_carregaGridProcessos() {

	oGridProcessos = new DBGrid('ctnProcessos');
	oGridProcessos.nameInstance = 'oGridProcessos';
	oGridProcessos.setCheckbox(0);
	oGridProcessos.setCellWidth(new Array("15%", "15%", "15%", "15%", "30%", "20%", "20%"));

	/**
	 * Alterado a visibilidade da Grid de acordo com o Módulo que acessa o fonte, sendo que é utilizado uma variável
	 * para controle passada via GET (grupo).
	 * grupo = 1 Procotolo
	 * grupo = 2 Ouvidoria
	 * O número de Processo do Procolo é controlado pelos campos p58_numero e p58_ano enquanto que na Ouvidoria é
	 * utilizado o campo p58_codproc 
	 */
	oGridProcessos.setHeader(new Array("N. Controle", 
	  	                               "Processo", 
	  	                               "Processo", 
	  	                               "Atendimento", 
	  	                               "Requerente", 
	  	                               "Tipo Processo", 
	  	                               "Depto. Padrão",
	  	                               "Cod. Depart.",
	  	                               "Limite")
                                    );
	oGridProcessos.setCellAlign(new Array('center', 'center', 'center', 'center', 'left', 'left', 'left'));

	oGridProcessos.aHeaders[1].lDisplayed = false;
	oGridProcessos.aHeaders[2].lDisplayed = false;
	oGridProcessos.aHeaders[8].lDisplayed = false;
	oGridProcessos.aHeaders[9].lDisplayed = false;
	if (oUrl.grupo == 1 ) {

	  oGridProcessos.aHeaders[1].lDisplayed = true;
		oGridProcessos.aHeaders[2].lDisplayed = true;
		oGridProcessos.aHeaders[3].lDisplayed = false;
		oGridProcessos.aHeaders[4].lDisplayed = false;
	}
	
	oGridProcessos.show($('ctnProcessos'));
  /**
   * Percorremos o cabeçalho da grid criada para adicionar a função js_reOrdenaGridProcessos
   * ao evento onclick nas células de cabeçalho.
   */
  $$(".table_header.cell").each(function (object, id) {
	  
	  object.setAttribute('onclick', "js_reOrdenaGridProcessos('"+object.id+"');");
	  object.addClassName("ordenador");
  });

	js_populaGridProcessos();
}

/**
 * Função que popula a grid de processos.
 */
function js_populaGridProcessos(sOrdenador) {
  $F('id_usuario');

	oGridProcessos.clearAll(true);
	sQuery = 'sMethod=buscaProcessos&iGrupo='+oUrl.grupo;
	if (sOrdenador !== undefined) {
		sQuery += '&sOrdem='+sOrdenador;
	}
	var oAjax = new Ajax.Request(sUrlRPC, 
                               {method: 'post',
                                parameters: sQuery, 
                                onComplete: js_retornoPopulaGridProcesos});
	js_divCarregando('Aguarde, carregando processos...','msgBox'); // exibimos uma notificação de processamento.
}

/**
 * Sobreescrevemos a função da datagrid para poder inserir uma nova função no onclick do checkbox
 */
oGridProcessos.selectSingle = function (oCheckbox, sRow, oRow) {
  
  if (oCheckbox.checked) {

    js_enviaSetor(oRow.aCells[8].getValue(), oRow.aCells[7].getValue(), oRow.aCells[9].getValue(), oCheckbox, false);
    $(sRow).className = 'marcado';
    oRow.isSelected   = true;
    
  } else {

    $(sRow).className = oRow.getClassName();
    oRow.isSelected   = false;
   
  }
  return true;
}

/**
 * Função que processa o retorno da js_populaGridProcessos.
 */
function js_retornoPopulaGridProcesos(oAjax) {

  var teste ='';
	js_removeObj("msgBox"); // removemos a notificação de processamento.
	var oRetorno = eval("("+oAjax.responseText+")");
	oRetorno.aProcessosEncontrados.each(function(oProcesso, iIndiceProcessos) {

    var aLinha = new Array();

    		
        aLinha[0] = oProcesso.p58_codproc;
        aLinha[1] = oProcesso.processoProtocolo;
        aLinha[2] = oProcesso.p58_codproc  + '/' + oProcesso.p58_ano;
        aLinha[3] = oProcesso.ov01_numero + '/' + oProcesso.ov01_anousu;
        aLinha[4] = oProcesso.z01_nome.urlDecode();
        aLinha[5] = oProcesso.p51_descr.urlDecode();
        aLinha[6] = oProcesso.coddepto + ' - ' + oProcesso.descrdepto.urlDecode();
        aLinha[7] = oProcesso.coddepto;
        aLinha[8] = oProcesso.limiteBloqueado;
        
      	sClass = "normal";
				if (parseInt($F('id_usuario')) == oProcesso.p58_id_usuario) {
          sClass = "destacado";
    		}
    		
    	  oGridProcessos.addRow(aLinha);
    	  oGridProcessos.aRows[iIndiceProcessos].setClassName(sClass);

	});
  oGridProcessos.renderRows();
}



/**
 * Função que dispara o comando para ordenação dos resultados da grid.
 * É disparado a partir do click no cabeçalho da grid.
 */
function js_reOrdenaGridProcessos(sOrdenador) {

	/**
	 * Efetuamos um switch para ser decidido qual o campo que vai ordenar a nova pesquisa.
	 */
	switch(sOrdenador) {

	  case 'col2':
      var sCampo = 'p58_codproc';
		break;

	  case 'col3':
	    var sCampo = 'p58_numero';
		break;

	  case 'col4':
      var sCampo = 'p58_codproc';
		break;

	  case 'col5':
	    var sCampo = 'ov01_numero';
		break;

	  case 'col6':
		  var sCampo = 'z01_nome';
		break;

	  case 'col7':
		  var sCampo = 'p51_descr';
		break;
		
	  case 'col8':
		  var sCampo = 'descrdepto';
		break;
	}
	/**
	 * Chamamos a pesquisa que popula a grid que já está preparada para receber na assinatura um campo
	 * ordenador para o resultado.
	 */
	js_populaGridProcessos(sCampo);
}

function js_telaDiferencas(aListaDiferenca) {

	var sContent  = "<div id='msg' style='border-bottom: 2px groove white;padding:5px;background-color:white;vertical-align:bottom;font-weight:bold;width:98%;height:50px;text-align:left'> ";
	    sContent += "Departamento escolhido difere do andamento padrão, favor digite o número de dias referente ao departamento escolhido.</div>                                              ";
	    sContent += "<table width='100%' style='padding-top:20px;'> ";
	    sContent += "  <tr>                             ";
      sContent += "    <td>                           "; 
      sContent += "	     <fieldset>                   ";
      sContent += "	       <div id='listaDifProcesso'>";
      sContent += "	       </div>                     ";
      sContent += "	     </fieldset>                  ";
      sContent += "    </td>                          ";
      sContent += "  </tr>                            ";
      sContent += "  <tr align='center'>              ";
      sContent += "    <td>                           ";
      sContent += "      <input type='button' id='btnIncluir' value='Incluir'/>";
      sContent += "      <input type='button' id='btnFechar'  value='Fechar'/> ";
      sContent += "    </td>                          ";
      sContent += "  </tr>                            ";
      sContent += "</table>                           ";
      
	
	windowAuxiliarDias  = new windowAux('wnddias', 'Informe a quantidade de dias', 650, 500);
	windowAuxiliarDias.setContent(sContent);
	windowAuxiliarDias.show(100,300);

	$('btnFechar').observe("click",js_fecharJanela);
	$('btnIncluir').observe("click",js_validaCamposDif);
	$('window'+windowAuxiliarDias.idWindow+'_btnclose').observe("click",js_fecharJanela);
 
	oDBGridProcessosDif.show($('listaDifProcesso'));
	oDBGridProcessosDif.clearAll(true);

  aListaDiferenca.each(
    function (oProcesso,iInd){
    
      if ( oProcesso.lTemDepto ) {
        var sDisabled = '';
      } else {
        var sDisabled = 'disabled';
      }
		  var sSelect  = "<select style='width:100%' id='segue"+oProcesso.p58_codproc+"' "+sDisabled+"> ";
		      sSelect += "  <option value='false' >Não</option>                                         "; 
		      sSelect += "  <option value='true'  >Sim</option>                                         ";
		      sSelect += "</select>                                                                     ";
		      
      aRow = new Array();
      aRow[0] = oProcesso.p58_codproc;
      aRow[1] = oProcesso.p58_requer;
      aRow[2] = "<input style='width:100%' type='text' id='dia"+oProcesso.p58_codproc+"' value=''/>";
      aRow[3] = sSelect;
      aRow[4] = Object.toJSON(oProcesso);
      oDBGridProcessosDif.addRow(aRow);
      
    }
  );

  oDBGridProcessosDif.renderRows();
    
}


function js_fecharJanela(){
  windowAuxiliarDias.destroy();
}


function js_validaCamposDif() {
  
  var aCamposText  = $$("#listaDifProcesso input[type='text']");
  var lRetorno     = true;
  var aProcessoDif = new Array(); 
  
  if ( $F('p62_coddeptorec') == '' ) {
    alert('Departamento de recebimento não informado!');
    return false;
  }  
  
  aCamposText.each(
    function ( eCampo, iInd ) {
      if ( eCampo.value.trim() == '') {
        alert('Número de Dias não informado!');
        lRetorno = false;
      } else if ( eCampo.value.trim() == '0' ) {
        alert('Número de Dias deve ser maior que zero!');
        lRetorno = false;
      }                  
    }
  );
  
  if ( !lRetorno ) {
    return false;
  }

 var oProcessoDif = new Array();
  
  oDBGridProcessosDif.aRows.each(
    function ( eRow, iInd ){
      
      var iCodProcesso = eRow.aCells[0].getValue();
      var iDias        = $('dia'+iCodProcesso).value; 
      var lSegue       = false; 
       
      var iNumOpt      = $('segue'+iCodProcesso).options.length;
      for ( var iIndOpt=0; iIndOpt < iNumOpt; iIndOpt++ ) {
        if ( $('segue'+iCodProcesso).options[iIndOpt].selected ) {
          lSegue = eval($('segue'+iCodProcesso).options[iIndOpt].value);
        }
      }
      
      oProcessoDif = new js_objProcesso(iCodProcesso,iDias,lSegue);
      aProcessoDif.push(oProcessoDif);
       
    }
  );
  
  var aListaChk    = js_getChkProcessos();
  var aProcessoSel = new Array();
  
  aListaChk.each(
    function ( eChk, iInd ) {
      if ( eChk.checked ) {
        var oProcesso = new js_objProcesso(eChk.value,0,false);
        aProcessoSel.push(oProcesso);    
      }     
    }
  );
  
  aProcessoSel.each(
    function ( oProcessoSel, iIndSel ){
		  aProcessoDif.each(
		    function ( oProcessoDif, iIndDif ){
		      if ( oProcessoSel.iCodProc == oProcessoDif.iCodProc ) {
   		      oProcessoSel.iDias  = oProcessoDif.iDias; 
   		      oProcessoSel.lSegue = oProcessoDif.lSegue; 
		      } 
		    }
		  );
    }
  );
  
  js_fecharJanela();
  js_incluirTramiteInicial(aProcessoSel);

}


function js_objProcesso(iCodProc,iDias,lSegue){
  
  this.iCodProc = iCodProc;
  this.iDias    = iDias;
  this.lSegue   = lSegue;

}

function js_incluirTramiteInicial(aObjProcesso) {
  
  js_divCarregando('Aguarde...','msgBox');

  var iNumOpt       = $('p62_id_usorec').options.length;
  var iIdUsuarioRec = ''; 
  
  if ( !$('p62_id_usorec').disabled ) {
    for ( var iIndOpt=0; iIndOpt < iNumOpt; iIndOpt++ ) {
      if ( $('p62_id_usorec').options[iIndOpt].selected ) {
        iIdUsuarioRec = $('p62_id_usorec').options[iIndOpt].value;
      }
    }  
  }     
     
  var sQuery  = 'sMethod=incluirTramite';
      sQuery += '&aObjProcesso='+Object.toJSON(aObjProcesso);
      sQuery += '&iCodDeptoRec='+$F('p62_coddeptorec');
      sQuery += '&iIdUsuarioRec='+iIdUsuarioRec;
      sQuery += '&iGrupo='+$F('grupo');
  
  var oAjax   = new Ajax.Request( sUrlRPC, {
	                                           method: 'post', 
	                                           parameters: sQuery, 
	                                           onComplete: js_retornoIncluirTramite
                                           }
                                );
}

function js_retornoIncluirTramite(oAjax){

  js_removeObj("msgBox");
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
  
  alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
  
  if ( aRetorno.lErro ) {
    return false;
  } else {
    url = "pro4_termorecebimento.php?codtran="+aRetorno.iCodTran;
    window.open(url,'','location=0');
    document.form1.submit();
  }


}



if (document.form1.p62_coddeptorec.value == 0) {
  document.form1.p62_id_usorec.disabled = true;
} else {
  document.form1.p62_id_usorec.disabled = false;
}

function js_ajaxRequest(iCoddepto) {

 if (iCoddepto!="") {
  var objUsuarios    = document.form1.p62_id_usorec;
  objUsuarios.disabled = true;

  js_divCarregando('Buscando usuarios','div_processando');

  var objUsuarios = document.form1.p62_id_usorec;
  var url         = 'pro4_consusuariodeptoRPC.php';
  var parametro   = "json={icoddepto:"+iCoddepto+"}";
  var objAjax     = new Ajax.Request (url,{ 
                                           method:'post',
                                           parameters:parametro, 
                                           onComplete:carregaDadosSelect
                                         }
                                    );
 } else {

   $('p62_id_usorec').length   = 1;
   $('p62_id_usorec').disabled = true;

 }
}

function carregaDadosSelect(oResposta) {
  eval('var aUsuarios = '+oResposta.responseText);
  var objUsuarios    = document.form1.p62_id_usorec;
  objUsuarios.length = 0;
  objUsuarios.disabled = false;

    
  for (var i = 0; i < aUsuarios.length; i++) {

    objUsuarios.options[i]       = new Option();
    objUsuarios.options[i].value = aUsuarios[i].id_usuario.urlDecode();
    objUsuarios.options[i].text  = aUsuarios[i].nome.urlDecode();
    
  }
  if(document.form1.usuario.value!=0 || document.form1.usuario.value!=""){
     document.form1.p62_id_usorec.value = document.form1.usuario.value;
  }
   
  objUsuarios.disabled = false;
  js_removeObj('div_processando');

}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_proctransfer.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true); 
}
function js_preenchepesquisa(chave){
  db_iframe_tran.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}


function js_pesquisap62_coddeptorec(mostra){
  
  var processa = true;
  var form = document.form1;
  var itens = form.elements.length;
  a = 0;
  for (i = 0; i < itens ;i++){
    if (form.elements[i].type=="checkbox"){
      if (form.elements[i].checked == true){
        a = a + 1;
      }
    }
  }

  if ( (a >= 1) && (document.form1.depart.value !="") && (document.form1.depart.value != document.form1.p62_coddeptorec.value)) {
    
     if ($F('p90_traminic') == 2 || $F('p90_traminic') == '2' ){
       
       alert('Departamento selecionado diferente do departamento padrão.');
       document.form1.p62_coddeptorec.value = document.form1.depart.value;
       processa = false;
     }
     if ($F('p90_traminic') == 3 || $F('p90_traminic') == '3'){
       alert('Aviso...Departamento selecionado diferente do departamento padrão.');
       processa = true;
     }
  }else{
     processa = true;
  }
  
  if(processa == true){
    
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart_transferencias.php?funcao_js=parent.js_mostradb_depart1|0|1&todasinstit=1','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart_transferencias.php?pesquisa_chave='+document.form1.p62_coddeptorec.value+'&funcao_js=parent.js_mostradb_depart&todasinstit=1&instituicao=0','Pesquisa',false);
    }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.p62_coddeptorec.focus(); 
    document.form1.p62_coddeptorec.value = ''; 
  }
  //
  // funcao que processa uma requisicao ajax 
  // para pesquisar os usuarios do departamento
  //
  js_ajaxRequest(document.form1.p62_coddeptorec.value);

}
function js_mostradb_depart1(chave1,chave2){

  
 
  //
  // funcao que processa uma requisicao ajax 
  // para pesquisar os usuarios do departamento
  //
  js_ajaxRequest(chave1);

  if (chave1 != $F('depart')) {
    
    if ($F('p90_traminic') == 2 || $F('p90_traminic') == '2' ){
      
      alert('Departamento selecionado diferente do departamento padrão.');
      document.form1.p62_coddeptorec.value = document.form1.depart.value;
      processa = false;
      db_iframe_tran.hide();
      return false;
    }
    if ($F('p90_traminic') == 3 || $F('p90_traminic') == '3'){
      alert('Aviso...Departamento selecionado diferente do departamento padrão.');
      processa = true;
    }

  } 


  document.form1.p62_coddeptorec.value = chave1;
  document.form1.descrdepto.value = chave2; 
  
  db_iframe_tran.hide();
}


function js_ordena(ord){
  document.form1.ordem.value = ord;
  document.form1.usuario.value = document.form1.p62_id_usorec.value;
  document.form1.submit();
  return false;
}

     
function js_chamaajax(){  
  if(document.form1.p62_coddeptorec.value!=0){
    js_ajaxRequest(document.form1.p62_coddeptorec.value);
  }
}


function js_enviaSetor(iCodDepto,sDescrDepto,lDesativado,oCheck,lLink) {
  
  if ( lDesativado === 'true'){
    alert("Departamento "+iCodDepto+" desativado! Verifique.");
  } else {
  
    var lCopiaDepart = false;
    var aListaChk    = js_getChkProcessos();
    
    if ( aListaChk.length > 0 ) {
      var lTemProcesso = true;          
    } else {
      var lTemProcesso = false;
    }
      
    if ( $F('p90_traminic') == 2 ) {
     
      if( lTemProcesso && $F('p62_coddeptorec') != '' && $F('p62_coddeptorec') != iCodDepto ){

        alert('Departamento padrão diferente do departamento selecionado!');
        oCheck.checked = false;
        
      } else if( !lTemProcesso ) {
       
        $('p62_coddeptorec').value  = '';
        $('descrdepto').value       = '';
        $('depart').value           = '';
        $('p62_id_usorec').disabled = true;
        
        if( lLink ){
          lCopiaDepart = true;
        }
      
      } else {
        lCopiaDepart = true;
      } 
     
    } else if( $F('p90_traminic') == 3 ) {
      
     if ( !lTemProcesso ) {
  
       $('p62_coddeptorec').value  = '';
       $('descrdepto').value       = '';
       $('depart').value           = '';
       
       $('p62_id_usorec').disabled = true;
       
       lCopiaDepart = false;

       
     } else if ( lTemProcesso && $F('p62_coddeptorec') != '' && $F('p62_coddeptorec') != iCodDepto ){
  
       if( oCheck.checked ){
          alert('Aviso...Departamento padrão diferente do departamento selecionado.');
          lCopiaDepart = true;
       }
       
     } else {
       lCopiaDepart = true;
     }
     
   } else {
     lCopiaDepart = true;
   }

    if( lCopiaDepart ){
      if ( iCodDepto != '' && ( oCheck.checked || lLink )){
        
        $('p62_coddeptorec').value = iCodDepto;
        $('descrdepto').value      = sDescrDepto;
        $('depart').value          = iCodDepto;
        
        js_ajaxRequest(iCodDepto);
        
        $('p62_id_usorec').disabled = true;
        oCheck.checked              = true;
      }
    }
  }
  
}

function js_validaProcessos(){
 
  var aListaChk     = js_getChkProcessos();
  var aListaProc    = new Array();

  if ( (processa == 'false' || processa == false) && ( $F('p62_coddeptorec') != $F('depart') ) ) {
    return false;
  }
  
  if ( $F('p62_coddeptorec') == '' ) {
    alert('Departamento de recebimento não informado!');
    return false;
  }
  
  aListaChk.each(
    function ( eChk,iInd ) {
      if ( eChk.checked ) {
        aListaProc.push(eChk.value);      
      }
    } 
  );    

  if ( aListaProc.length == 0 ) {
    alert('Nenhum processo selecionado!');
    return false;
  }
  
  if ( $F('grupo') ==  2 ) {  
  
	  js_divCarregando('Aguarde...','msgBox');
	   
	  var sQuery  = 'sMethod=validaDeptoInicial';
	      sQuery += '&aListaProcesso='+Object.toJSON(aListaProc);
	      sQuery += '&iCodDeptoRec='+$F('p62_coddeptorec');
	      
	   document.form1.db_opcao.disabled = true;
	   
	  var oAjax   = new Ajax.Request( sUrlRPC, {
	                                          method: 'post', 
	                                          parameters: sQuery, 
	                                          onComplete: js_retornoValidaProcesso
	                                        }
	                                );      
  } else {
  
    var aListaChk = js_getChkProcessos();
    var aProcesso = new Array();
    
    aListaChk.each(
      function ( eChk, iInd ) {
        if ( eChk.checked ) {
          var oProcesso = new js_objProcesso(eChk.value,0,false);
          aProcesso.push(oProcesso);    
        }     
      }
    );
    
    document.form1.db_opcao.disabled = true;
    js_incluirTramiteInicial(aProcesso);  
  
  }
 
}

function js_retornoValidaProcesso(oAjax){

  js_removeObj("msgBox");
   
  var aRetorno = eval("("+oAjax.responseText+")");
  
  if ( aRetorno.aListaDiferenca.length > 0 ) {
    
    js_telaDiferencas(aRetorno.aListaDiferenca);
    
  } else {
  
    var aListaChk = js_getChkProcessos();
	  var aProcesso = new Array();
	  
	  aListaChk.each(
	    function ( eChk, iInd ) {
	      if ( eChk.checked ) {
	        var oProcesso = new js_objProcesso(eChk.value,0,false);
	        aProcesso.push(oProcesso);    
	      }     
	    }
	  );
	  document.form1.db_opcao.disabled = true;
    js_incluirTramiteInicial(aProcesso);
  }
    
}


function js_getChkProcessos(){
  return $$('#ctnProcessosbody input.checkboxctnProcessos[type="checkbox"]');
}
</script>