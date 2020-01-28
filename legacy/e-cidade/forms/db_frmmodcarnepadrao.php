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

  //MODULO: caixa
  $clmodcarnepadrao->rotulo->label();
  $clmodcarnepadraolayouttxt->rotulo->label();
  $clmodcarnepadraocadmodcarne->rotulo->label();
  
  $clrotulo = new rotulocampo;
  $clrotulo->label("nomeinst");
  $clrotulo->label("ar11_nome");
  $clrotulo->label("k47_descr");
  $clrotulo->label("db50_descr");  
  $clrotulo->label("k46_descr");

  if($db_opcao==1){
    $db_action="cai1_modcarnepadrao004.php";
  }else if($db_opcao==2||$db_opcao==22){
    $db_action="cai1_modcarnepadrao005.php";
  }else if($db_opcao==3||$db_opcao==33){
    $db_action="cai1_modcarnepadrao006.php";
  }

$sValida = "";
if ($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22) {
  $sValida = "onsubmit='return js_valida();'";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>" <?=$sValida ?>>
  <center>
<fieldset style="margin-top: 20px;"> 
<legend><b>Cadastro Modelo Padrão</b></legend> 
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tk48_sequencial?>">
          <?=@$Lk48_sequencial?>
        </td>
    	<td> 
		  <?
			db_input('k48_sequencial',10,$Ik48_sequencial,true,'text',3,"");
		  ?>
        </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tk48_instit?>">
	       <?=$Lk48_instit?>
	    </td>
	    <td> 
		  <?
			db_input('k48_instit',10,$Ik48_instit,true,'text',3,"");
			db_input('nomeinst',50,$Inomeinst,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tk48_cadconvenio?>">
	      <?
	        db_ancora(@$Lk48_cadconvenio,"js_pesquisak48_cadconvenio(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
	 	  <?
			db_input('k48_cadconvenio',10,$Ik48_cadconvenio,true,'text',$db_opcao," onchange='js_pesquisak48_cadconvenio(false);'");
			db_input('ar11_nome',50,$Ik47_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tk48_cadtipomod?>">
	      <?
	        db_ancora(@$Lk48_cadtipomod,"js_pesquisak48_cadtipomod(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
		  <?
		    db_input('k48_cadtipomod',10,$Ik48_cadtipomod,true,'text',$db_opcao," onchange='js_pesquisak48_cadtipomod(false);'");
			db_input('k46_descr',50,$Ik46_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td>
	      <b>Tipo Modelo</b>
	    </td>
	    <td>
	      <?
			$aPdfTxt = array("pdf"=>"PDF","txt"=>"TXT");
	        db_select("selPdfTxt",$aPdfTxt,true,$db_opcao,"style='width:80px;' onChange='js_alteraTelaModelo(this.value)'");
  		  ?> 
	    </td>
	  </tr>
	  <tr id="formPdf">
	    <td>
	      <?
	        db_ancora(@$Lm01_cadmodcarne,"js_pesquisam01_cadmodcarne(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
	 	  <?
			db_input('m01_cadmodcarne',10,$Im01_cadmodcarne,true,'text',$db_opcao," onchange='js_pesquisam01_cadmodcarne(false);'");
			db_input('k47_descr',50,$Ik47_descr,true,'text',3,'');
	      ?>
	    </td>	  
	  </tr>
	  <tr id="formTxt" style="display:none">
	    <td>
	      <?
	        db_ancora(@$Lm02_db_layouttxt,"js_pesquisam02_db_layouttxt(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
	 	  <?
			db_input('m02_db_layouttxt',10,$Im02_db_layouttxt,true,'text',$db_opcao," onchange='js_pesquisam02_db_layouttxt(false);'");
			db_input('db50_descr',50,$Idb50_descr,true,'text',3,'');
	      ?>
	    </td>	  
	  </tr>	  
	  <tr>
	    <td nowrap title="<?=@$Tk48_dataini?>">
	      <?=@$Lk48_dataini?>
	    </td>
	    <td> 
	 	  <?
	 	  
	 	    if ( !isset($k48_dataini) || trim($k48_dataini) == ""  ) {
	 	      $k48_dataini_dia = "01";
	 	      $k48_dataini_mes = "01";
	 	      $k48_dataini_ano = "1900";	 	      
	 	    }
	 	    
			db_inputdata('k48_dataini',@$k48_dataini_dia,@$k48_dataini_mes,@$k48_dataini_ano,true,'text',$db_opcao,"");
			
		  ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tk48_datafim?>">
	      <?=@$Lk48_datafim?>
	    </td>
	    <td> 
		  <?
		  
	 	    if ( !isset($k48_datafim) || trim($k48_datafim) == "" ) {
	 	      $k48_datafim_dia = "31";
	 	      $k48_datafim_mes = "12";
	 	      $k48_datafim_ano = "2099";
	 	    }
		  
			db_inputdata('k48_datafim',@$k48_datafim_dia,@$k48_datafim_mes,@$k48_datafim_ano,true,'text',$db_opcao,"");
			
		  ?>
	    </td>
	  </tr>
	  
     <tr>
       <td nowrap >
         <b>Parcela Inicial</b>
       </td>
       <td> 
         <?
           db_input('k48_parcini',10,'' ,true,'text',$db_opcao," ");
         ?>
       </td>  
     </tr>
     
     <tr>
       <td nowrap >
         <b>Parcela Final</b>
       </td>
       <td> 
         <?
           db_input('k48_parcfim',10, '' ,true,'text',$db_opcao," ");
         ?>
       </td>  
     </tr> 	  
	  
    </table>
  </center>
</fieldset>  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"<?=($db_opcao==1?"disabled":"")?>>
</form>
<script>

js_alteraTelaModelo(document.form1.selPdfTxt.value);

function js_valida() {
  var fParcIni = document.getElementById('k48_parcini').value;
  var fParcFim = document.getElementById('k48_parcfim').value;
 //alert(fParcFim);
  if (fParcIni == null || fParcIni == '') {
    alert('Preencha um Valor na Parcela Inicial');
    document.getElementById('k48_parcini').focus();
    return false;
  }
  if (fParcFim == null || fParcFim == '') {
    alert('Preencha um Valor na Parcela Final');
    document.getElementById('k48_parcfim').focus();
    return false;
  }  

}


function js_alteraTelaModelo(sValor){

  if (sValor == 'pdf') {
    $('formPdf').style.display = ""; 
    $('formTxt').style.display = "none";
  } else {
    $('formTxt').style.display = ""; 
    $('formPdf').style.display = "none"; 
  }

}

function js_pesquisam01_cadmodcarne(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadmodcarne','func_cadmodcarne.php?funcao_js=parent.js_mostracadmodcarne1|k47_sequencial|k47_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.m01_cadmodcarne.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadmodcarne','func_cadmodcarne.php?pesquisa_chave='+document.form1.m01_cadmodcarne.value+'&funcao_js=parent.js_mostracadmodcarne','Pesquisa',false);
     }else{
       document.form1.k47_descr.value = ''; 
     }
  }
}
function js_mostracadmodcarne(chave,erro){
  document.form1.k47_descr.value = chave; 
  if(erro==true){ 
    document.form1.m01_cadmodcarne.focus(); 
    document.form1.m01_cadmodcarne.value = ''; 
  }
}

function js_mostracadmodcarne1(chave1,chave2){
  document.form1.m01_cadmodcarne.value = chave1;
  document.form1.k47_descr.value = chave2;
  db_iframe_cadmodcarne.hide();
}


function js_pesquisam02_db_layouttxt(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_db_layouttxt','func_db_layouttxt.php?funcao_js=parent.js_mostralayouttxt1|db50_codigo|db50_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.m02_db_layouttxt.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_db_layouttxt','func_db_layouttxt.php?pesquisa_chave='+document.form1.m02_db_layouttxt.value+'&funcao_js=parent.js_mostralayouttxt','Pesquisa',false);
     }else{
       document.form1.db50_descr.value = ''; 
     }
  }
}
function js_mostralayouttxt(chave,erro){
  document.form1.db50_descr.value = chave; 
  if(erro==true){ 
    document.form1.m02_db_layouttxt.focus(); 
    document.form1.m02_db_layouttxt.value = ''; 
  }
}

function js_mostralayouttxt1(chave1,chave2){
  document.form1.m02_db_layouttxt.value = chave1;
  document.form1.db50_descr.value       = chave2;
  db_iframe_db_layouttxt.hide();
}




function js_pesquisak48_cadtipomod(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadtipomod','func_cadtipomod.php?funcao_js=parent.js_mostracadtipomod1|k46_sequencial|k46_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k48_cadtipomod.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadtipomod','func_cadtipomod.php?pesquisa_chave='+document.form1.k48_cadtipomod.value+'&funcao_js=parent.js_mostracadtipomod','Pesquisa',false,'0');
     }else{
       document.form1.k46_descr.value = ''; 
     }
  }
}
function js_mostracadtipomod(chave,erro){
  document.form1.k46_descr.value = chave; 
  if(erro==true){ 
    document.form1.k48_cadtipomod.focus(); 
    document.form1.k48_cadtipomod.value = ''; 
  }
}
function js_mostracadtipomod1(chave1,chave2){
  document.form1.k48_cadtipomod.value = chave1;
  document.form1.k46_descr.value = chave2;
  db_iframe_cadtipomod.hide();
}

function js_pesquisak48_cadconvenio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadconvenio','func_cadconvenio.php?funcao_js=parent.js_mostracadconvenio1|ar11_sequencial|ar11_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.k48_cadconvenio.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_cadconvenio','func_cadconvenio.php?pesquisa_chave='+document.form1.k48_cadconvenio.value+'&funcao_js=parent.js_mostracadconvenio','Pesquisa',false);
     }else{
       document.form1.ar11_nome.value = ''; 
     }
  }
}
function js_mostracadconvenio(chave,erro){
  document.form1.ar11_nome.value = chave; 
  if(erro==true){ 
    document.form1.k48_cadconvenio.focus(); 
    document.form1.k48_cadconvenio.value = ''; 
  }
}
function js_mostracadconvenio1(chave1,chave2){
  document.form1.k48_cadconvenio.value = chave1;
  document.form1.ar11_nome.value 	   = chave2;
  db_iframe_cadconvenio.hide();
}



function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_modcarnepadrao','db_iframe_modcarnepadrao','func_modcarnepadrao.php?funcao_js=parent.js_preenchepesquisa|k48_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_modcarnepadrao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>