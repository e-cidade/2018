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
$cllab_conferencia->rotulo->label();
$clrotulo = new rotulocampo ( );

//procedimentos
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
$clrotulo->label ( "sd70_c_cid" );
$clrotulo->label ( "sd70_c_nome" );

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla47_i_codigo?>">
       <?=@$Lla47_i_codigo?>
    </td>
    <td> 
<?
db_input('la47_i_codigo',10,$Ila47_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
      <tr>
		<td nowrap title="<?=@$Tla22_i_codigo?>">
         <? db_ancora ( '<b>Requisição</b>', "js_pesquisala22_i_codigo(true);", "" );?>
        </td>
		<td> 
         <? db_input ( 'la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisala22_i_codigo(false);'" )?>
         <? db_input ( 'z01_v_nome2', 50, @$Iz01_v_nome, true, 'text', 3, '' )?>
        </td>
	  </tr>	
      <tr>
		<td nowrap title="requiitem">
         <? db_ancora ( '<b>Exame</b>', "js_pesquisala21_i_codigo(true);", "" );?>
        </td>
		<td> 
		 <? db_input ( 'la08_i_codigo', 10, @$Ila08_i_codigo, true, 'text',"", " onchange='js_pesquisala21_i_codigo(false);'" )?>
         <? db_input ( 'la47_i_requiitem', 10, @$Ila21_i_codigo, true, 'hidden',"", " onchange='js_pesquisala21_i_codigo(false);'" )?>
         <?db_input ( 'la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' )?>
        </td>
	  </tr>	
	  <tr>
		<td nowrap title="<?=@$Tla22_i_codigo?>">
          <? db_ancora ($Lla47_i_procedimento,"js_pesquisala47_i_procedimento(true);", "" );?>
        </td>
		<td> 
		  <? db_input ( 'sd63_c_procedimento', 10, @$Isd63_c_procedimento, true, 'text',"", " onchange='js_pesquisala47_i_procedimento(false);'" )?>
          <? db_input ( 'la47_i_procedimento', 10, @$Ila47_i_procedimento, true, 'hidden',"", " onchange='js_pesquisala47_i_procedimento(false);'" )?>
          <?db_input ( 'sd63_c_nome', 50, @$Isd63_c_nome, true, 'text', 3, '' )?>
        </td>
	  </tr>	
      <tr>
			<td nowrap title="<?=@$Tla47_i_cid?>" valign="top" align="top">
			<?
			db_ancora(@$Lla47_i_cid,"js_pesquisasd70_c_cid(true); ",$db_opcao);
			?>
			</td>
			<td valign="top" align="top" colspan=3>
			<?
		    db_input('la47_i_cid',10,$Ila47_i_cid,true,'hidden',$db_opcao);
			db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);' onFocus='js_foco(this, \"sd29_d_data\");' onblur='js_validacid(this);'");
			db_input('sd70_c_nome',49,$Isd70_c_nome,true,'text',3,"tabIndex='0' ");
			?>
			</td>
	 </tr>
  <tr>
     <td colspan="2">
        <iframe src="" name="lista" id="lista" width="700px" height="240px" ></iframe>
     </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla47_i_resultado?>">
       <?=@$Lla47_i_resultado?>
    </td>
    <td> 
 <?$aX = array('1'=>'SIM', '2'=>'NÃO');
   db_select('la47_i_resultado', $aX, true, $db_opcao,' onchange="js_resultado();" ');?>
    </td>
  </tr>
  <tr id="textLinha" style="display: none;" >
    <td nowrap title="<?=@$Tla47_t_observacao?>">
       <?=@$Lla47_t_observacao?>
    </td>
    <td> 
<?
db_textarea('la47_t_observacao',0,60,@$Ila47_t_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Confirmar Resultado":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="novo" type="button" id="novo" value="Novo" onclick="js_novoresultado();">
</form>
<script>
F=document.form1;
strURL         = 'sau1_sau_individualprocedRPC.php';
booValidaCID   = false;


//iframe
function js_loadIframe(iRequiitem){
	        x  = "lab4_confresult002.php";
	        x += "?iRequiitem="+iRequiitem;
	        this.lista.location.href=x;
}
function js_resultado(){
   if(F.la47_i_resultado.value==1){
	   valor='none';
   }else{
       valor='';
   }   
   document.getElementById('textLinha').style.display=valor;
}
function js_pesquisala22_i_codigo(mostra){
	  if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome','Pesquisa',true);
	  }else{
		if(document.form1.la22_i_codigo.value != ''){ 
		  js_OpenJanelaIframe('','db_iframe_requisicao','func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostrarequisicao','Pesquisa',false);
		}else{
		  document.form1.z01_v_nome2.value = ''; 
	    }
	  }
	}

	function js_mostrarequisicao(chave,erro){

	  document.form1.z01_v_nome2.value = chave; 
	  if(erro==true){ 
		document.form1.la22_i_codigo.focus(); 
		document.form1.la22_i_codigo.value = ''; 
	  }
	  js_limpaCamposTrocaReq();
	}

	function js_mostrarequisicao1(chave1,chave2){

		document.form1.la22_i_codigo.value = chave1;
		document.form1.z01_v_nome2.value = chave2;
		db_iframe_requisicao.hide();
		js_limpaCamposTrocaReq();

	}
			
	function js_pesquisala21_i_codigo(mostra){
	  if(document.form1.la22_i_codigo.value == '') {

	    alert('Escolha uma requisição primeiro.');
		js_limpaCamposTrocaReq();
		return false;

	  }
	  sPesq = 'la21_i_requisicao='+document.form1.la22_i_codigo.value+'&iLaboratorioLogado=<?=$iLaboratorioLogado?>&sSituacao=2 - Lancado&';
	  if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_requiitem','func_lab_requiitem.php?'+sPesq+'funcao_js=parent.js_mostrarequiitem1|la08_i_codigo|la08_c_descr|la21_i_codigo','Pesquisa',true);
	  }else{
		 if(document.form1.la08_i_codigo.value != ''){
		    js_OpenJanelaIframe('','db_iframe_requiitem','func_lab_requiitem.php?'+sPesq+'pesquisa_chave='+document.form1.la08_i_codigo.value+'&funcao_js=parent.js_mostrarequiitem','Pesquisa',false);
		 }else{
		    document.form1.la08_c_descr.value = ''; 
		 }
	  }
	}

	function js_mostrarequiitem(chave,erro,requiitem){
	  document.form1.la08_c_descr.value = chave; 
	  if(erro==true){ 
		document.form1.la08_i_codigo.focus(); 
		document.form1.la08_i_codigo.value = ''; 
	  }else{
		  document.form1.la47_i_requiitem.value=requiitem;
		  js_loadIframe(requiitem);
	  }
	}

	function js_mostrarequiitem1(chave1,chave2,requiitem){

	  document.form1.la08_i_codigo.value = chave1;	
	  document.form1.la47_i_requiitem.value = requiitem;
	  document.form1.la08_c_descr.value = chave2;
	  db_iframe_requiitem.hide();
	  js_loadIframe(requiitem);	  

	}

	function js_limpaCamposTrocaReq() {

		  document.form1.la47_i_requiitem.value = '';
		  document.form1.la08_c_descr.value = '';

	}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_conferencia','func_lab_conferencia.php?funcao_js=parent.js_preenchepesquisa|la47_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_conferencia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_novoresultado(){
  location.href="lab4_confresult001.php";
}
/**
 * Pesquisa Procedimento
 */
function js_pesquisala47_i_procedimento(mostra){

	var strParam = '';
	strParam += 'func_sau_proccbo.php';
	strParam += '?chave_rh70_sequencial=<?=$iResponsavelTecnico?>';
	strParam += '?lNaoFiltrar=true';
	strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
	strParam += '&campoFoco=sd63_c_procedimento';

	if(mostra==true){
		js_OpenJanelaIframe('','db_iframe_sau_proccbo',strParam,'Pesquisa Procedimentos',true);
	}else{

		if(document.form1.sd63_c_procedimento.value != ''){
			var objParam                 = new Object();
			objParam.exec                = "getProcedimento";
			objParam.rh70_sequencial     = <?=$iResponsavelTecnico?>;
			objParam.rh70_descr          = <?=$iResponsavelTecnico?>;
			objParam.sd63_c_procedimento = $F('sd63_c_procedimento');

			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoProcedimento' );
		}else{     
			document.form1.sd63_c_nome.value = ''; 
		}
	}
	document.form1.sd63_c_procedimento.focus(); 
}
function js_mostraprocedimentos1(sd96_i_procedimento,sd63_c_procedimento,sd63_c_nome){
   
	F.la47_i_procedimento.value=sd96_i_procedimento;
	F.sd63_c_procedimento.value=sd63_c_procedimento;
	F.sd63_c_nome.value=sd63_c_nome;
	db_iframe_sau_proccbo.hide();
    
}
/**
 * Retorno Pesquisa Procedimento
 */
function js_retornoProcedimento( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");

	if (objRetorno.status == 1) {
		if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objProcedimento, iIteracao) {
     			//Prenche Procedimento
				$('la47_i_procedimento').value = objProcedimento.sd96_i_procedimento;
				$('sd63_c_procedimento').value = objProcedimento.sd63_c_procedimento.urlDecode();
				$('sd63_c_nome').value         = objProcedimento.sd63_c_nome.urlDecode();
			});
 		}
	} else {
    	alert(objRetorno.message.urlDecode());
		$('sd63_c_procedimento').focus();			
		$('sd63_c_procedimento').select();			
	}
}

/**
 * Pesquisa CID
 */
function js_pesquisasd70_c_cid(mostra){
    if(F.la47_i_procedimento.value==''){
        alert('Escolha um procedimento!');
    	F.sd70_c_cid.value='';
        return false;
    }
	if(mostra==true){
		var strParam = ( booValidaCID == true )?'func_sau_proccid2.php':'func_sau_cid.php';
		strParam += '?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome';
		strParam += '&chave_sd72_i_procedimento='+$F('la47_i_procedimento');
		strParam += '&campoFoco=sd70_c_cid';
		js_OpenJanelaIframe('','db_iframe_sau_cid',strParam,'Pesquisa CID',true);
	}else{
		if(document.form1.sd70_c_cid.value != ''){
			var objParam            = new Object();
			objParam.exec           = "getCID";
			objParam.sd70_c_cid     = $F('sd70_c_cid');
			objParam.sd29_i_procedimento = $F('la47_i_procedimento');
			objParam.booValidaCID   = booValidaCID; 
	
			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoCID' );
		}else{
			$('sd70_i_codigo').value = '';
			$('sd70_c_cid').value    = '';
			$('sd70_c_nome').value   = '';
		}
	}
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
	$('la47_i_cid').value    = chave1;
	$('sd70_c_cid').value    = chave2;
	$('sd70_c_nome').value   = chave3;
	
	db_iframe_sau_cid.hide();
		
}

/**
 * retorno CID
 */
function js_retornoCID( objAjax ){
  	var objRetorno = eval("("+objAjax.responseText+")");
  	var objForm    = document.form1;

	$('la47_i_cid').value = '';
	$('sd70_c_cid').value    = '';
	$('sd70_c_nome').value   = '';
	  	  	
  	if (objRetorno.status == 1) {
     	if (objRetorno.itens.length > 0) {
     		objRetorno.itens.each(function (objCID, iIteracao) {
     			$('la47_i_cid').value = objCID.sd70_i_codigo;
     			$('sd70_c_cid').value    = objCID.sd70_c_cid.urlDecode();
     			$('sd70_c_nome').value   = objCID.sd70_c_nome.urlDecode();
         	});
     	}
  	} else {
    	alert(objRetorno.message.urlDecode());
    	$('sd70_c_cid').focus();
  	}

}

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno ){ 
	var objAjax = new Ajax.Request(
                         strURL, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                          				js_divCarregando( strCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
}

</script>