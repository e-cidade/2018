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
$clisstipoalvara->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q97_descricao");
$clrotulo->label("db82_descricao");
$q98_instit = db_getsession('DB_instit');

?>
<form name="form1" method="post" action="" onsubmit="return js_validacao();">
<center>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Tipos de Alvarás</b></legend>
<table border="0">
  <tr>
    <td width="25%" nowrap title="<?=@$Tq98_sequencial?>">&nbsp;
       <?=@$Lq98_sequencial?>
    </td>
    <td> 
			<?
				db_input('q98_sequencial',5,$Iq98_sequencial,true,'text',3,"")
			?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tq98_descricao?>">&nbsp;
       <?=@$Lq98_descricao?>
    </td>
    <td> 
			<?
				db_input('q98_descricao',77,$Iq98_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>  
  
  
  <tr>
    <td nowrap title="<?=@$Tq98_documento?>">&nbsp;
       <?
       db_ancora(@$Lq98_documento,"js_pesquisaq98_documento(true);",$db_opcao);
       ?>
    </td>
    <td> 
				<?
					db_input('q98_documento',5,$Iq98_documento,true,'text',$db_opcao," onchange='js_pesquisaq98_documento(false);'")
				?>
       <?
       		db_input('db82_descricao',70,$Idb82_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq98_issgrupotipoalvara?>">&nbsp;
       <?
       	db_ancora(@$Lq98_issgrupotipoalvara,"js_pesquisaq98_issgrupotipoalvara(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
				db_input('q98_issgrupotipoalvara',5,$Iq98_issgrupotipoalvara,true,'text',$db_opcao," onchange='js_pesquisaq98_issgrupotipoalvara(false);'")
			?>
       <?
       	db_input('q97_descricao',70,$Iq97_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="Tipo de Validade">
       &nbsp;<b>Tipo de Validade</b>
    </td>
    <td> 
			<?
				$x = array("" => "SELECIONE...",
				           "1" => "FIXO",
				           "2" => "VARIÁVEL", 
				           "3" => "INDETERMINADO");
				db_select('q98_tipovalidade',$x,true,$db_opcao,"onchange = 'js_mostraQuantvalidade(); js_mostraRenovacao();js_zerarenovacao(true);js_indeterminado();'");
			?>
    </td>
  </tr>   
  
  
  
  <tr>
   	<td colspan="2" align="left">
   	  <div id='quantvalidade' style="display: none;">
   	    <table align="left" width="100%" border="0">
				  <tr >
				    <td width="25%" align="left" nowrap title="<?=@$Tq98_quantvalidade?>" >
				       <?=@$Lq98_quantvalidade?>
				    </td>
				    <td align="left"> 
							<?
								db_input('q98_quantvalidade',10,$Iq98_quantvalidade,true,'text',$db_opcao,"")
							?>
				    </td>
				  </tr>
			  </table>
		  </div>
	  <td>
	</tr>  
	
  <tr>
   	<td colspan="2" align="left">
   	  <div id='renovacao' style="display: none;">
   	    <table align="left" width="100%" border="0">
				  <tr>
				    <td  align="left" width="25%" nowrap title="<?=@$Tq98_permiterenovacao?>">
				       <?=@$Lq98_permiterenovacao?>
				    </td>
				    <td align="left"> 
							<?
								$x = array(""  => "SELECIONE...",
								           "f" => "NAO",
								           "t" => "SIM");
								db_select('q98_permiterenovacao',$x,true,$db_opcao,"onchange='js_mostraQuantRenovacao();'");
							?>
				    </td>
				  </tr>
			  </table>
		  </div>
	  <td>
	</tr> 
	
	
  <tr>
   	<td colspan="2" align="left">
   	  <div id='quantrenovacao' style="display: none;">
   	    <table align="left" width="100%" border="0">
				  <tr>
				    <td align="left" width="25%" nowrap title="<?=@$Tq98_quantrenovacao?>">
				       <?=@$Lq98_quantrenovacao?>
				    </td>
				    <td align="left"> 
							<?
								db_input('q98_quantrenovacao',10,$Iq98_quantrenovacao,true,'text',$db_opcao,"")
							?>
				    </td>
				  </tr>
			  </table>
		  </div>
	  <td>
	</tr> 	
	 	
  
  <tr>
    <td nowrap title="<?=@$Tq98_permitetransformacao?>">&nbsp;
       <?=@$Lq98_permitetransformacao?>
    </td>
    <td> 
			<?
					$x = array(""  => "SELECIONE...",
					           "f" => "NAO",
					           "t" => "SIM");
					db_select('q98_permitetransformacao',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq98_permiteimpressao?>">&nbsp;
       <?=@$Lq98_permiteimpressao?>
    </td>
    <td> 
			<?
				$x = array(""  => "SELECIONE...",
				           "f" => "NAO",
				           "t" => "SIM");
				db_select('q98_permiteimpressao',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tq98_gerataxa?>">&nbsp;
       <?=@$Lq98_gerataxa?>
    </td>
    <td> 
			<?
				$x = array(""  => "SELECIONE...","f"=>"NAO","t"=>"SIM");
				db_select('q98_gerataxa',$x,true,$db_opcao,"");
			?>
    </td>
  </tr>
  <tr style="display: none;">
    <td nowrap title="<?=@$Tq98_instit?>">&nbsp;
       <?=@$Lq98_instit?>
    </td>
    <td> 
			<?
				db_input('q98_instit',10,$Iq98_instit,true,'hidden',$db_opcao,"")
			?>
    </td>
  </tr>


  </table>
  </center>
  </fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_validacao(){

  var iTipoValidade     = $F('q98_tipovalidade');
  var iQtdValidade      = $F('q98_quantvalidade');
  var iPermiteRenovacao = $F('q98_permiterenovacao');
  var iQtdRenovacao     = $F('q98_quantrenovacao');
  
  if (iTipoValidade == 1 || iTipoValidade == 2) {

   	  
    if (iPermiteRenovacao == null || iPermiteRenovacao == "" ){

      alert("Selecione se permitira renovação");
      return false;
    }
	}

  if (iTipoValidade == null) {

		alert("Selecione o tipo de Validade");
		return false;
	}

	if ((iTipoValidade == 1) && (iQtdValidade <= 0 || iQtdValidade == null  )) {


	     alert('Preencha Quantidade de Validade ');
       return false;
  }

	if ((iTipoValidade == 1 || iTipoValidade == 2) && (iPermiteRenovacao == null ) ) {

		  alert ('Selecione Permição de Renovação');  
      return false;
  }  
  
  if (iPermiteRenovacao == 't' && (iQtdRenovacao <= 0 || iQtdRenovacao == null )){

			alert('Preencha a Quantidade de Renovação');
			return false;
	}
  
  if (iPermiteRenovacao == 't'){
	  
	  if (iQtdRenovacao == null || iQtdRenovacao == 0 ) {
	
	     alert('Selecione Quantidade de Renovação ');
	     return false;
		}
  }
  
}


function js_indeterminado(){

	 var iMostra = $F('q98_tipovalidade');
	  if(iMostra == 3){
		  
		  $("q98_permiterenovacao").options.length = 0;
		  $("q98_permiterenovacao").options[0]     = new Option('NAO','f');   
		}

	
}


function js_zerarenovacao(lZera){

  var iMostra = $F('q98_tipovalidade');

  if (lZera == true) {
	  
	  $("q98_permiterenovacao").options.length = 0;
	  $("q98_permiterenovacao").options[0]     = new Option('SELECIONE...','');
	  $("q98_permiterenovacao").options[1]     = new Option("NAO", 'f');
	  $("q98_permiterenovacao").options[2]     = new Option('SIM', 't');
	  
	  $('q98_quantrenovacao').value = '';
  }  
	  js_mostraQuantRenovacao();
  
}


function js_mostraQuantRenovacao(){

	var iMostra = $F('q98_permiterenovacao');

	if(iMostra == 't'){
		$('quantrenovacao').style.display = 'inLine';
	} else {
		$('quantrenovacao').style.display = 'none';
	}	
	
}

function js_mostraRenovacao(){

	var iMostra = $F('q98_tipovalidade'); 
	
	if (iMostra == 1 || iMostra == 2 ){
		
		$('renovacao').style.display = "inLine";
  
  } else {
	  
	  $('renovacao').style.display = "none";
	}
	js_zerarenovacao(false);
	
}

function js_mostraQuantvalidade(){

	var iMostra = $F('q98_tipovalidade'); 
	
	if (iMostra == 1 ){
		
		$('quantvalidade').style.display = "inLine";
  
  } else {
	  
	  $('quantvalidade').style.display = "none";
	}
	js_zerarenovacao(false);
}

function js_pesquisaq98_issgrupotipoalvara(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_issgrupotipoalvara','func_issgrupotipoalvara.php?funcao_js=parent.js_mostraissgrupotipoalvara1|q97_sequencial|q97_descricao','Pesquisa',true);
  }else{
     if(document.form1.q98_issgrupotipoalvara.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_issgrupotipoalvara','func_issgrupotipoalvara.php?pesquisa_chave='+document.form1.q98_issgrupotipoalvara.value+'&funcao_js=parent.js_mostraissgrupotipoalvara','Pesquisa',false);
     }else{
       document.form1.q97_descricao.value = ''; 
     }
  }
}
function js_mostraissgrupotipoalvara(chave,erro){
  document.form1.q97_descricao.value = chave; 
  if(erro==true){ 
    document.form1.q98_issgrupotipoalvara.focus(); 
    document.form1.q98_issgrupotipoalvara.value = ''; 
  }
}
function js_mostraissgrupotipoalvara1(chave1,chave2){
  document.form1.q98_issgrupotipoalvara.value = chave1;
  document.form1.q97_descricao.value = chave2;
  db_iframe_issgrupotipoalvara.hide();
}
function js_pesquisaq98_documento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_documentotemplate','func_db_documentotemplate.php?funcao_js=parent.js_mostradb_documentotemplate1|db82_sequencial|db82_descricao','Pesquisa',true);
  }else{
     if(document.form1.q98_documento.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_documentotemplate','func_db_documentotemplate.php?pesquisa_chave='+document.form1.q98_documento.value+'&funcao_js=parent.js_mostradb_documentotemplate','Pesquisa',false);
     }else{
       document.form1.db82_descricao.value = ''; 
     }
  }
}
function js_mostradb_documentotemplate(chave,erro){
  document.form1.db82_descricao.value = chave; 
  if(erro==true){ 
    document.form1.q98_documento.focus(); 
    document.form1.q98_documento.value = ''; 
  }
}
function js_mostradb_documentotemplate1(chave1,chave2){
  document.form1.q98_documento.value = chave1;
  document.form1.db82_descricao.value = chave2;
  db_iframe_db_documentotemplate.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_isstipoalvara','func_isstipoalvara.php?cadastro=1&funcao_js=parent.js_preenchepesquisa|q98_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isstipoalvara.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

js_mostraQuantvalidade();
js_mostraRenovacao();
js_mostraQuantRenovacao();

</script>