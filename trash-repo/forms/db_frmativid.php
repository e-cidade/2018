<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: issqn

$clativid->rotulo->label();
$cl_classe->rotulo->label();
$clcnaeanalitica ->rotulo->label();
$clcnae ->rotulo->label();
$clrhcbo ->rotulo->label();
?>

<script>

function js_valida(db_opcao) {

   if (db_opcao != 3) {
   
  	 classe = document.getElementById('q12_classe');
  	 if (document.form1.q03_descr.value.length == "") {
       alert('Campo Descrição é obrigatório!');
       document.form1.q03_descr.focus();
       return false;
     } 
  	 
  	 if (classe.value == '') {
  
  		   alert('Campo Código da Classe deve ser preenchido!');
  			 classe.focus();
  			 return false;
  	 }
  	 
  	 if (document.form1.q03_descr.value.length > 200) {
     
         alert('Campo Descrição ultrapassou o tamanho máximo de caracteres permitido.\nTamanho Máximo 200 caracteres!');
         document.form1.q03_descr.focus();
         return false;
     }
     
     
   }
   js_processaDados(db_opcao);

	 
}
function js_calcula(){
  if(document.form1.q03_ativ.value==""){
    alert("Preencha o Código da atividade!");
    return false;
  }
    return true;
    
}

function js_verifica(){

 if ($F('q03_ativ') == ''){

  $('db_opcao').disabled = true;
 }

}
function js_getCodigo(){
 
     
     iAtiv = $F('q03_ativ');  
     $('calcular').disabled = true;  
     $('db_opcao').disabled = true;  
		 if (iAtiv ==  ''){
       iAtiv = 1;
		 }
     url   = 'iss1_ativid004.php';
     oAjax = new Ajax.Request(
				                       url, 
		   		                    {
					                     method: 'get', 
				   	                   parameters: 'q03_ativ='+iAtiv, 
				   	                   onComplete: js_setCodigo
			                   	    }
												     );
}

function js_setCodigo(oAjax){

    iCodigo = eval("("+oAjax.responseText+")");
		$('q03_ativ').value    = iCodigo.q03_ativ;
    $('calcular').disabled = false;  
    $('db_opcao').disabled = false;  

}
</script>

<form name="form1" method="post" action="">
<center>
<fieldset style="width: 500px;"><legend><b><?=$sAcao;?> Cadastro de Atividades&nbsp;</b></legend>
<table border="0"  align="center">
  <tr>
    <td nowrap title="<?=@$Tq03_ativ?>"  width="150">
       <b>Código da Atividade:</b>
    </td>
    <td> 
      <?
      	db_input('q03_ativ',10,$Iq03_ativ,true,'text',$db_opcaoc,"'onblur='js_verifica();js_getCodigo()'");
        if ($db_opcaoc!=3) {
          echo "<input name=\"calcular\" type=\"button\" id=\"calcular\" value=\"Calcular\" onclick='js_getCodigo();'>";
        }
      ?>
    </td>
  </tr>
  <tr>
    <td>
       <b>Pessoa:</b>
    </td>
    <td>
      <?
        $arrayvalores = array("S" => "Selecione","F" => "Física","J"=>"Juridica");
        db_select("pessoa",$arrayvalores,1,$db_opcaoselect,"onchange='js_limpaCampos()'");
      ?>
    </td>
  </tr>

  <tr id = "oculta" style="display: ;">
    <td colspan="2">
	    <div id ='PJ' style='visibility:<?=$hiddenPJ?>; position:<?=$positionPJ?>;'>
  	    <table>
  		    <tr>
  			    <td width="149"><?db_ancora(@$Lq71_estrutural,"js_pesquisa_cnae(true);",$db_opcaoselect);?></td>
  			    <td nowrap="nowrap">
  			    <?
  			    db_input("q71_estrutural",10,$Iq71_estrutural,true,"text",$db_opcaoselect,"onchange='js_pesquisa_cnae(false);'");
  			    db_input("q71_descr",61,$Iq71_descr,true,"text",3);  
  			    db_input("q72_sequencial",5,$Iq72_sequencial,true,"hidden",3); 
  			    ?>
  			    </td>
  		    </tr>
  	    </table>
      </div>
      <div id ='PF' style="visibility:<?=$hiddenPF?>; position:<?=$positionPF?>;">
        <table>
  		    <tr>
  			    <td width="149">
  			      <?db_ancora(@$Lrh70_estrutural,"js_pesquisa_cbo(true);",$db_opcaoselect);?>
  			    </td>
  			    <td nowrap="nowrap">
  			    <?
    			    db_input("rh70_estrutural",10,$Irh70_estrutural,true,"text",$db_opcaoselect,"onchange='js_pesquisa_cbo(false);'");
    			    db_input("rh70_descr",61,$Irh70_sequencial,true,"text",3);  
    			    db_input("rh70_sequencial",5,$Irh70_sequencial,true,"hidden",3); 
  			    ?>
  			    </td>
  		    </tr>
  	    </table>
      </div>
    </td> 
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq03_descr?>">
       <b>Descrição:</b>
    </td>
    <td> 
       <?
         db_input('q03_descr',75,$Iq03_descr,true,'text',$db_opcao,"")
       ?>
    </td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tq12_classe?>">
      <b><?db_ancora("Código da Classe:","js_pesquisaq12_classe(true);",$db_opcao);?></b>
    </td>
    <td align="left" nowrap>
      <?
        db_input("q12_classe",10,$Iq12_classe,true,"text",3,"onchange='js_pesquisaq12_classe(false);'");
        db_input("q12_descr",61,$Iq03_descr,true,"text",3);  
      ?>
    </td>
  </tr>  
  <tr id='servico' style="display: <?=$sServicoStyle ?> ">
    <td  align="left" nowrap title="Códido do Serviço">
      <b><?db_ancora("Código do Serviço:","js_pesquisa_servico(true);",$db_opcao);?></b>
    </td>
    <td align="left" nowrap>
      <?db_input("q127_sequencial",10,"text",true,"text",$db_opcao,"onchange='js_pesquisa_servico(false);'");
        db_input("db121_descricao",61,"text",true,"text",3);  
      ?>
    </td>
  </tr>
  <!--<tr> 
    <td  align="left" nowrap title="<?=$Tq80_tipcal?>"><?db_ancora(@$Lq80_tipcal,"js_pesquisa_tipcalc(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("q80_tipcal",10,$Iq80_tipcal,true,"text",$db_opcao,"onchange='js_pesquisa_tipcalc(false);'");
         db_input("q81_descr",60,"$Iq81_descr",true,"text",3);  
        ?></td>
  </tr>-->
  <tr>
    <td nowrap title="<?=@$Tq03_atmemo?>">
       <b>Observação:</b>
    </td>
    <td> 
      <?
        db_textarea('q03_atmemo',0,73,$Iq03_atmemo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq03_limite?>">
       <?=@$Lq03_limite?> 
    </td>
    <td>
       <?
        db_inputdata('q03_limite',@$q03_limite_dia,@$q03_limite_mes,@$q03_limite_ano,true,'text',$db_opcao,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq03_horaini?>">
      <?=@$Lq03_horaini?>
    </td>
    <td colspan="2">
      <?
        db_input('q03_horaini',10,$Iq03_horaini,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq03_horafim?>">
       <?=@$Lq03_horafim?>
    </td>
    <td colspan="2">
      <?
        db_input('q03_horafim',10,$Iq03_horafim,true,'text',$db_opcao,"onchange='js_verifica_hora(this.value,this.name)';");
      ?>
    </td>
  </tr>

</table>
<fieldset style="margin-top: 10px;">
  <legend><b>Lista de Documentos  </b></legend>
  <div id="gridDocumentos">
  
  </div>
</fieldset>
</fieldset>
<input name="db_opcao" onclick='return js_valida(<?=$db_opcao?>)' type="button" id="db_opcao" <?=($db_botao==false?"disabled":"")?>
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"  >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?db_input("alteracbocnae",5,'',true,"hidden",3);?> 
</center>
</form>
<script>

oGrid = new DBGrid("gridDoc");
js_pesquisaDocumentos();
var lCalCiss;
var db_opcao = <?=$db_opcao?>;

if (db_opcao == 22 || db_opcao == 2 ){
  lCalCiss = '<?echo isset($q12_calciss);?>';
} else {
  lCalCiss = false;
}



/**
 * Cria a Grid com os Documentos
 */
function js_pesquisaDocumentos() {

  oGrid.nameInstance = 'oGrid';
  oGrid.setCellAlign(new Array('center','left'));
  oGrid.setCheckbox(0);
  oGrid.setHeader(new Array('Código', 'Tipo'));
  oGrid.show($('gridDocumentos'));
  oGrid.clearAll(true);
  
  var url                = 'iss1_ativid.RPC.php';
  var oObject            = new Object();
  oObject.exec           = "buscaDocs";
  oObject.iCodAtividade  = $F("q03_ativ");
  oObject.db_opcao       = db_opcao;
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoBusca
                                        }
                                   );
  
}
  
function js_retornoBusca(oJson) {
  
    var oRetorno = eval("("+oJson.responseText+")");

    if (oRetorno.status == 2) {
      alert('Sem Documentos Retornados.'); 
    } else {
    
      for (var i = 0; i < oRetorno.aDocs.length; i++) {
        
        var bCheked = false;
        var aLinha  = new Array();
        aLinha[0]   = oRetorno.aDocs[i].db44_sequencial;
        aLinha[1]   = oRetorno.aDocs[i].db44_descricao.urlDecode();
        
        if (oRetorno.aDocs[i].checked == 1) {
          bCheked = true;
        }
        oGrid.addRow(aLinha, false, false, bCheked);
      }
      oGrid.renderRows();
    }
}  


/**
 * Realiza processo de dos Dados do Formulário
 */ 
function js_processaDados(db_opcao) {

  var sMsg = "Carregando...";               
  js_divCarregando(sMsg, 'msgBox');  
  var url                = 'iss1_ativid.RPC.php';
  var aDocs              = new Array();
  var oObject            = new Object();
  
  oObject.iCodAtividade  = $F("q03_ativ")
  oObject.sTipoPessoa    = $F("pessoa");
  oObject.sDescricao     = encodeURIComponent(tagString($F("q03_descr")));
  oObject.iClasse        = $F("q12_classe");
  oObject.sObservacao    = encodeURIComponent(tagString($F("q03_atmemo")));
  oObject.dtDataLimit    = $F("q03_limite");
  oObject.sHoraIni       = $F("q03_horaini");
  oObject.sHoraFim       = $F("q03_horafim");
  oObject.lCalCiss       = lCalCiss;
  oObject.db_opcao       = db_opcao;
  
  if (lCalCiss) {
    oObject.sServico = $F("q127_sequencial");
  } 

  if (oObject.db_opcao != 3) {
    oObject.exec           = "processaDados";  
  } else {
    oObject.exec           = "processaExclusao";
  }
  
  // Tipo de Pessoa Não é Obrigatório
  if (oObject.sTipoPessoa == "F") {
    oObject.iCBO  = $F("rh70_sequencial");
  } else if (oObject.sTipoPessoa == "J") {
    oObject.iCNAE = $F("q72_sequencial");
  }
  
  
  aDocsGrid          = oGrid.getSelection();
  
  for (var i=0; i < aDocsGrid.length; i++) {
    var oDocs          = new Object();
    oDocs.db44_sequencial  = aDocsGrid[i][1];
    oDocs.db44_descricao   = aDocsGrid[i][2]
    aDocs[i]               = oDocs;
  }
  
  oObject.docs       = aDocs; 

  var objAjax   = new Ajax.Request (url, {
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete: js_retornoProcessaDados
                                        }
                                   );
}

function js_retornoProcessaDados(objAjax) {

  js_removeObj('msgBox');
  var oRetorno     = eval("("+objAjax.responseText+")");
  oRetorno.message = oRetorno.message.urlDecode().replace(/\\n/g, "\n");
  
  if (oRetorno.status == 2) {
    alert(oRetorno.message); 
  } else {
    alert(oRetorno.message);
    if (oRetorno.altera == 1) {
      window.location = "iss1_ativid002.php";
    } else {
      window.location = "iss1_ativid003.php";
    }
    
  }
}
  

function js_verifica_hora(valor,campo) {
  erro= 0;
  ms  = "";
  hs  = "";
  
  tam = "";
  pos = "";
  tam = valor.length;
  pos = valor.indexOf(":");  
  if (pos!=-1) {
    if (pos==0 || pos>2) {
      erro++;
    } else {
      if (pos==1) {
        hs = "0"+valor.substr(0,1);
        ms = valor.substr(pos+1,2);
      } else if(pos==2) {
        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);
      }
      if (ms=="") { 
         ms = "00";
      }
    }
  } else {
    if (tam>=4) {
      hs = valor.substr(0,2);
      ms = valor.substr(2,2);
    } else if(tam==3) {
      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);
    } else if(tam==2) {
      hs = valor;
      ms = "00";
    } else if(tam==1) {
      hs = "0"+valor;
      ms = "00";
    }
  }
  if (ms!="" && hs!="") {
  
    if (hs>24 || hs<0 || ms>60 || ms<0) {
      erro++
    } else {
      if (ms==60) {
        ms = "59";
      }
      if (hs==24) {
        hs = "00";
      }
      hora = hs;
      minu = ms;
    }    
  }

  if (document.form1.q03_horafim.value != "" && erro == 0) {
  
     var botao   = document.getElementById("db_opcao");
     var val_ini = document.form1.q03_horaini.value;
     var pos_ini = val_ini.indexOf(":");
     var hs_ini  = "";

     if (pos_ini == 1) {
        hs_ini = "0" + val_ini.substr(0,1);
     } else if (pos_ini == 2) {
          hs_ini = val_ini.substr(0,2);
     }

     if (valor!="") {    
          eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
     }

     var val_fin = document.form1.q03_horafim.value;
     var pos_fin = val_fin.indexOf(":");
     var ms_fin  = "";

     if (pos_fin == 1) {
        hs_fin = "0" + val_fin.substr(0,1);
     } else if (pos_fin == 2) {
        hs_fin = val_fin.substr(0,2);
     }

  }     
  if (erro>0) {
    if (erro < 99) { 
       alert("Informe uma hora válida.");
    }
  }
  if (valor!="") {    
    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
  }
}


function js_pesquisa() {

  <?
     if (($db_opcao == 2 )  or ($db_opcao == 22) or ($db_opcao == 3) or ($db_opcao == 33)) 
     {  
  ?>  
      js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_atividalt.php?funcao_js=parent.js_preenchepesquisa|q03_ativ','Pesquisa',true);
  <? 
     } else {  
  ?>
      window.location = "iss1_ativid002.php";
  <?
     }
  ?>    
}

function js_preenchepesquisa(chave) {
  db_iframe_ativid.hide();
  <?
  if($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}

function js_pesquisa_cbo(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_cbo','func_rhcboalt.php?funcao_js=parent.js_mostracbo|rh70_estrutural|rh70_descr|rh70_sequencial|tipo','Pesquisa',true);
  } else {
     if (document.form1.rh70_estrutural.value != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_cbo','func_rhcboalt.php?pesquisa_chave='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostracbo2','Pesquisa',false);
     } else {     
       document.form1.rh70_estrutural.value = '';
       document.form1.rh70_sequencial.value = '';
       document.form1.rh70_descr.value      = '';  
     }
  }
}

function js_mostracbo(estrutural,descr,sequencial,tipo) {

	if (tipo=='Sintetico') {
		alert('Selecione uma atividade do tipo "Analitico".');
	} else {
	
    db_iframe_cbo.hide();
		document.form1.rh70_estrutural.value=estrutural;
		document.form1.rh70_descr.value=descr;
		<?
       if ($db_opcao == 1) {
		?>
     	  document.form1.q03_descr.value=descr;
		<?
      }
		?>
		document.form1.rh70_sequencial.value=sequencial;
  }
}

function js_mostracbo2(estrutural,descr,sequencial,tipo,erro) {

	if (tipo=='Sintetico') {
	
		alert('Selecione uma atividade do tipo "Analitico".');
		document.form1.rh70_estrutural.value = '';
    document.form1.rh70_sequencial.value='';
    document.form1.rh70_descr.value='';
	} else {
	
  document.form1.rh70_estrutural.value=estrutural;
	document.form1.rh70_descr.value=descr;
		<?
       if ($db_opcao == 1) {
		?>
     	  document.form1.q03_descr.value=descr;
		<?
      }
		?>
	document.form1.rh70_sequencial.value=sequencial;
 }
 if (erro== true) {
   document.form1.rh70_estrutural.value = '';
   document.form1.rh70_sequencial.value='';
  }
  //document.form1.submit();
}

function js_pesquisa_cnae(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_cnae','func_cnae.php?funcao_js=parent.js_mostracnae|q71_estrutural|q71_descr|q72_sequencial','Pesquisa',true);
  } else {
  
     if (document.form1.q71_estrutural.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_cnae','func_cnae.php?pesquisa_chave='+document.form1.q71_estrutural.value+'&funcao_js=parent.js_mostracnae2','Pesquisa',false);
     } else {
       document.form1.q71_estrutural.value = ''; 
       document.form1.q72_sequencial.value = '';
       document.form1.q71_descr.value      = '';
     }
  }
}

function js_mostracnae(estrutural,descr,sequencial) {
  if (sequencial == '') {
  	  alert('Selecione uma atividade do tipo "Analitico".');
  } else {
  
	  db_iframe_cnae.hide();
	  document.form1.q71_estrutural.value=estrutural;
	  document.form1.q71_descr.value=descr;
		<?
       if ($db_opcao == 1){
		?>
     	  document.form1.q03_descr.value=descr;
		<?
      }
		?>
	  document.form1.q72_sequencial.value=sequencial;
  }
}

function js_mostracnae2(estrutural,descr,sequencial,erro) {

  if(erro == false) {
 
    if (sequencial == '') {
      alert('Selecione uma atividade do tipo "Analitico".');
    } else {
    
  	  document.form1.q71_estrutural.value=estrutural;
  	  document.form1.q71_descr.value=descr;
  		<?
         if ($db_opcao == 1){
  		?>
       	  document.form1.q03_descr.value=descr;
  		<?
        }
  		?>
  	  document.form1.q72_sequencial.value=sequencial;
    }
  } else {
    document.form1.q71_estrutural.value='';
	  document.form1.q71_descr.value = descr;
	  document.form1.q03_descr.value=descr;
	  document.form1.q72_sequencial.value='';
  }
}

function js_pesquisaq12_classe(mostra) {

	tipo_pessoa  = document.getElementById('pessoa');
	filtrapessoa = '';
	
	if (tipo_pessoa.value == 'J') {
		filtrapessoa = 'filtrapessoa=J&'
	} else if (tipo_pessoa.value == 'F') {
		filtrapessoa = 'filtrapessoa=F&'
	}

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_classealt.php?'+filtrapessoa+'funcao_js=parent.js_mostraclasse|q12_classe|q12_descr|q12_fisica|q12_calciss','Pesquisa',true);
  } else {
     if(document.form1.q12_classe.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_classealt.php?'+filtrapessoa+'pesquisa_chave='+document.form1.q12_classe.value+'&funcao_js=parent.js_mostraclasse2','Pesquisa',false);
     } else {
       document.form1.q12_classe.value = ''; 
     }
  }
}

function js_mostraclasse(chave_classe,chave1_classe,juridica, calciss) {

   document.form1.q12_classe.value = chave_classe ;
   document.form1.q12_descr.value = chave1_classe ;
   db_iframe_ativid.hide();
   lCalCiss = calciss; 
   
   if (calciss == 't') { 
     $("servico").style.display = "";
   } else {
     $("servico").style.display = "none";
     $("q127_sequencial").value = "";
     $("db121_descricao").value = "";
   }
   
}
function js_mostraclasse2(chave_classe,erro_classe,juridica){
 document.form1.q12_descr.value = chave_classe ;
 if(erro_classe == true) 
  {
   document.form1.q12_classe.value = '';
  }
  //document.form1.submit();
  if(juridica=='t'){
 	document.form1.pessoa.value = 'J';
 }else if(juridica=='f'){
  document.form1.pessoa.value = 'F';
 }
}
function js_pesquisa_tipcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalciss.php?funcao_js=parent.js_mostraportetipo1|q81_codigo|q81_descr','Pesquisa',true);
  }else{
     if(document.form1.q80_tipcal.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_tipcalciss.php?pesquisa_chave='+document.form1.q80_tipcal.value+'&funcao_js=parent.js_mostraportetipo','Pesquisa',false);
     }else{
       document.form1.q81_descr.value = ''; 
     }
  }
}
function js_mostraportetipo(chave,erro){
  document.form1.q81_descr.value = chave; 
  if(erro==true){ 
    document.form1.q80_tipcal.focus(); 
    document.form1.q80_tipcal.value = ''; 
  }
 
}
function js_mostraportetipo1(chave1,chave2){
  document.form1.q80_tipcal.value = chave1;
  document.form1.q81_descr.value = chave2;
  db_iframe_issportetipo.hide();
}


/**
 * Pesquisa Serviço
 *    
 */
function js_pesquisa_servico(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_servico','func_issgruposervico.php?funcao_js=parent.js_mostraServico1|q126_sequencial|db121_descricao','Pesquisa',true);
  } else {
  
     if (document.form1.q127_sequencial.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_servico','func_issgruposervico.php?pesquisa_chave='+document.form1.q127_sequencial.value+'&funcao_js=parent.js_mostraServico','Pesquisa',false);
     } else {
       document.form1.db121_descricao.value = '';
     }
  }
}

function js_mostraServico(chave,erro) {

  document.form1.db121_descricao.value = chave; 
  if (erro==true) {
   
    document.form1.q127_sequencial.focus(); 
    document.form1.q127_sequencial.value = ''; 
  }
 
}
function js_mostraServico1(chave1,chave2) {

  document.form1.q127_sequencial.value = chave1;
  document.form1.db121_descricao.value = chave2;
  db_iframe_servico.hide();
}




function js_pessoa() {

  var tr     = $("oculta");
  var pessoa = $F("pessoa");
  
  if (pessoa == 'J') {
    
    tr.style.display ="";
    document.getElementById('PJ').style.visibility = 'visible';
    document.getElementById('PJ').style.position   = 'relative';
    document.getElementById('PF').style.visibility = 'hidden';
    document.getElementById('PF').style.position   = 'absolute';
    document.form1.rh70_estrutural.value           = '';
    document.form1.rh70_descr.value                = '';
    document.form1.rh70_sequencial.value           = '';
  } else if(pessoa == 'F') {
  
    tr.style.display ="";
    document.getElementById('PF').style.visibility = 'visible';
    document.getElementById('PF').style.position   = 'relative';
    document.getElementById('PJ').style.visibility = 'hidden';
    document.getElementById('PJ').style.position   = 'absolute';
    document.form1.q71_estrutural.value            = '';
    document.form1.q71_descr.value                 = '';
    document.form1.q72_sequencial.value            = '';
  } else {
  
    tr.style.display = "none";
    document.getElementById('PF').style.visibility = 'hidden';
    document.getElementById('PJ').style.visibility = 'hidden';
    document.getElementById('PF').style.position   = 'absolute';
    document.getElementById('PJ').style.position   = 'absolute';
    document.form1.rh70_estrutural.value           = '';
    document.form1.rh70_descr.value                = '';
    document.form1.rh70_sequencial.value           = '';
    document.form1.q71_estrutural.value            = '';
    document.form1.q71_descr.value                 = '';
    document.form1.q72_sequencial.value            = '';
  }
}

/**
 * Limpa os campos dependendo do tipo de pessoa selecionado
 */
function js_limpaCampos() {

  var tr     = $("oculta");
  var pessoa = $F("pessoa");
  
  if (pessoa == 'J') {
    
    tr.style.display ="";
    document.getElementById('PJ').style.visibility = 'visible';
    document.getElementById('PJ').style.position   = 'relative';
    document.getElementById('PF').style.visibility = 'hidden';
    document.getElementById('PF').style.position   = 'absolute';
    document.form1.rh70_estrutural.value           = '';
    document.form1.rh70_descr.value                = '';
    document.form1.rh70_sequencial.value           = '';
    document.form1.q12_classe.value                = '';
    document.form1.q12_descr.value                 = '';
    document.form1.q127_sequencial.value           = '';
    document.form1.db121_descricao.value           = '';
  } else if(pessoa == 'F') {
  
    tr.style.display ="";
    document.getElementById('PF').style.visibility = 'visible';
    document.getElementById('PF').style.position   = 'relative';
    document.getElementById('PJ').style.visibility = 'hidden';
    document.getElementById('PJ').style.position   = 'absolute';
    document.form1.q71_estrutural.value            = '';
    document.form1.q71_descr.value                 = '';
    document.form1.q72_sequencial.value            = '';
    document.form1.q12_classe.value                = '';
    document.form1.q12_descr.value                 = '';
    document.form1.q127_sequencial.value           = '';
    document.form1.db121_descricao.value           = '';
  } else {
  
    tr.style.display = "none";
    document.getElementById('PF').style.visibility = 'hidden';
    document.getElementById('PJ').style.visibility = 'hidden';
    document.getElementById('PF').style.position   = 'absolute';
    document.getElementById('PJ').style.position   = 'absolute';
    document.form1.rh70_estrutural.value           = '';
    document.form1.rh70_descr.value                = '';
    document.form1.rh70_sequencial.value           = '';
    document.form1.q71_estrutural.value            = '';
    document.form1.q71_descr.value                 = '';
    document.form1.q72_sequencial.value            = '';
    document.form1.q12_classe.value                = '';
    document.form1.q12_descr.value                 = '';
    document.form1.q127_sequencial.value           = '';
    document.form1.db121_descricao.value           = '';
    $("servico").style.display = "none";
  }
}

if (db_opcao == 1) {
  $("servico").style.display = "none";
}

</script>