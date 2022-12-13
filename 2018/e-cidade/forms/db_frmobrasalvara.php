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

//MODULO: projetos
$clobrasalvara->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ob01_nomeobra");
?>
<form name="form1" method="post" action="">
<fieldset><legend><strong>Alvar&aacute;s</strong></legend>
	<table>
		<tr>
			<td nowrap title="<?=@$Tob04_codobra?>">
			<?
				db_ancora(@$Lob04_codobra,"js_pesquisaob04_codobra(true);",($db_opcao == 2?3:$db_opcao));
			?>
			</td>
			<td>
			<? 
				db_input('ob04_codobra',10,$Iob04_codobra,true,'text',($db_opcao == 2?3:$db_opcao)," onchange='js_pesquisaob04_codobra(false);'"); 
			  db_input('ob01_nomeobra',40,$Iob01_nomeobra,true,'text',3,'');      
			?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="<?=@$Tob04_alvara?>">
				<?=@$Lob04_alvara?>
			</td>
			<td nowrap>
				<? 
					db_input('ob04_alvara',10,$Iob04_alvara,true,'text',1,"") ;
					if ($db_opcao==1){
						echo "(se não preencher, codigo será gerado automaticamente)";
					}
				?>
			</td>
		</tr>
		
		<tr>
			<td nowrap title="<?=@$Tob04_data?>">
				<?=@$Lob04_data?>
			</td>
			<td>
				<?
				if($db_opcao == 1){
					$ob04_data_dia = date("d",db_getsession("DB_datausu"));
					$ob04_data_mes = date("m",db_getsession("DB_datausu"));
					$ob04_data_ano = date("Y",db_getsession("DB_datausu"));
				}
				db_inputdata('ob04_data',@$ob04_data_dia,@$ob04_data_mes,@$ob04_data_ano,true,'text',$db_opcao,"")
				?>
			</td>
		</tr>
	</table>
	
	<div id="botoes">
	  <input name="consulta" id="consulta" type="button" value="Consulta Obras" onclick="js_pesquisaConstrucoes()" />
		<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>> 
		<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
	</div>
</fieldset>
</form>

<script>
sUrl = 'pro1_obrasalvara.RPC.php';
function js_pesquisaConstrucoes() {

	var iCodigoObra = $F('ob04_codobra');
	var oParam      = new Object();

	if(iCodigoObra == '') {
		alert('Código da obra não informado.');
		return false;
	}
	
	oParam.sExec       = 'getConstrucoes';
	oParam.iCodigoObra = iCodigoObra;

	
	js_divCarregando('Pesquisando construções vinculadas a obra, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
        											 method    : 'POST',
                               parameters: 'json=' + Object.toJSON(oParam), 
                               onComplete: js_retornaConstrucoes
                              });
	
}

function js_retornaConstrucoes(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno        = eval("("+oAjax.responseText+")");

	if (oRetorno.iStatus == 1) {

		with (oRetorno.aConstrucao[0]) {

			var sContent = '';
			
			sContent += "<div style='margin: 10px auto; text-align: center;'>                                                          ";
			sContent += "  <div id='msgtopo'></div>                                                                                    ";
			sContent += "  <div style='width:550px; margin:10px auto;'>                                                                ";
			sContent += "    <fieldset>                                                                                                ";
			sContent += "      <table id='window' width='100%'>                                                                        ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "					 <td class='descricao'>Obra</td>                                                           					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codobra+"</td>                                                          ";                 
			sContent += "			   </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "				   <td class='descricao'>Construção</td>                                                     					 ";
			sContent += "					 <td class='conteudo'>"+ob08_codconstr+"</td>                                                        ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "				 <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Descrição Obra</td>                                                           ";
			sContent += "          <td class='conteudo'>"+ob01_nomeobra.urlDecode()+"</td>                                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Area</td>                                                                     ";
			sContent += "          <td class='conteudo'>"+ob08_area+"m2</td>                                                           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Ocupação</td>                                                                 ";
			sContent += "          <td class='conteudo'>"+ob08_ocupacao + " - " + ob08_descrocupacao.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo de Construção</td>                                                       ";
			sContent += "          <td class='conteudo'>"+ob08_tipoconstr + " - " + ob08_descrtipoconstr.urlDecode()+"</td>            ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Tipo Lançamento</td>                                                          ";
			sContent += "          <td class='conteudo'>"+ob08_tipolanc + " - " + ob08_descrtipolanc.urlDecode()+"</td>                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Logradouro</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_lograd + " - " + j14_nome.urlDecode()+", "+ob07_numero+"</td>           ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Complemento</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_compl.urlDecode()+"</td>                                                ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Bairro</td>                                                                   ";
			sContent += "          <td class='conteudo'>"+ob07_bairro +" - "+ j13_descr.urlDecode()+"</td>                             ";     
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Detalhes</td>                                                                 ";
			sContent += "          <td class='conteudo'>                                                                               ";     						 
			sContent += "            Área - " + ob07_areaatual + " | Unidades - " +ob07_unidades+" | Pavimentos - "+ob07_pavimentos+"  ";
			sContent += "          </td>                                                                                               ";
			sContent += "        </tr>                                                                                                 ";
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Início</td>                                                              ";
			sContent += "          <td class='conteudo'>"+ob07_inicio+"</td>                                                           ";                 
			sContent += "        </tr>                                                                                                 "; 
			sContent += "        <tr>                                                                                                  ";
			sContent += "          <td class='descricao'>Data Final</td>                                                               ";
			sContent += "          <td class='conteudo'>"+ob07_fim+"</td>                                                              ";                 
			sContent += "        </tr>                                                                                                 ";
			sContent += "      </table>                                                                                                ";
			sContent += "    </fieldset>                                                                                               ";
			sContent += "    <div style='margin: 10px auto;'>                                                                          ";
			sContent += "      <input type='button' name='cancelar' value='Cancelar' onclick='js_fechar_janela()'/>                    ";
			sContent += "    </div>																																																		 ";		
			sContent += "  </div>                                                                                                      ";
			sContent += "</div>                                                                                                        ";
			
			js_montaGradeConstrucao(sContent);
		}
		
	} else {
		
		alert(oRetorno.sMessage);
		
	}

}

function js_montaGradeConstrucao(sHtml) {

	windowConstr = new windowAux('wndConstr', 'Construção da obra', 570, 440);
	windowConstr.setContent(sHtml);

  var w = ((screen.width - 550) / 2);
  var h = ((screen.height / 2) - 380);

  windowConstr.show(h, w);
	
	$('window'+windowConstr.idWindow+'_btnclose').observe("click",js_fechar_janela);

	oMessage  = new DBMessageBoard('msgboard', 
  										           'Detalhes da Construção',
  										           'Detalhes da Construção',
            										 $('msgtopo'));
	oMessage.show();

	
}

function js_fechar_janela(){
	
	windowConstr.destroy();
	  
} 

function js_numero(){
  if(document.form1.ob04_codobra.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_numero','pro1_obrasconstr001.php?func_alvara=1&ob08_codobra='+document.form1.ob04_codobra.value,'Pesquisa',true);
  }
  return false;
}
function js_pesquisaob04_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob04_codobra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob04_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{
       document.form1.ob01_nomeobra.value = ''; 
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave; 
  if(erro==true){ 
    document.form1.ob04_codobra.focus(); 
    document.form1.ob04_codobra.value = ''; 
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob04_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrasalvara','func_obrasalvara.php?funcao_js=parent.js_preenchepesquisa|ob04_codobra','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrasalvara.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>