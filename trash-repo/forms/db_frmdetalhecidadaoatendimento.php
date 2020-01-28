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

//MODULO: ouvidori a
include ("classes/db_tiporetorno_classe.php");
include ("classes/db_telefonetipo_classe.php");

$cltelefonetipo 		= new cl_telefonetipo();
$cltiporetorno 			= new cl_tiporetorno();
$clcidadao 					= new cl_cidadao;
$clcidadaoemail 		= new cl_cidadaoemail;
$clcidadaotelefone 	= new cl_cidadaotelefone;

$clcidadaoemail->rotulo->label();
$clcidadaotelefone->rotulo->label();
$clcidadao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ov16_sequencial");
$clrotulo->label("ov03_numcgm");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table style="margin-top: 20px;" width="790">
	<tr>
	  <td>
	    <fieldset>
				<table>
					<tr>
					  <td>
				      <fieldset>
				        <legend>
				          <b>Detalhes <?=$oGet->sTipo?></b>
				        </legend>
        				<table>
									<tr>
										<td nowrap title="<?=@$Tov02_sequencial?>"><?=@$Lov02_sequencial;?></td>
										<td>
										  <?
										    db_input('ov02_sequencial',10,$Iov02_sequencial,true,'text',3,"");
  							        db_input('ov02_seq'       ,10,'',true,'hidden',3,"");
										  ?>
										</td>
										<td>
											<b>Processado:</b>
											<? db_input('ov03_numcgm',10,$Iov03_numcgm,true,'text',3,"");?>
											<? db_input('z01_nome',30,$Iz01_nome,true,'text',3,"");?>
										</td>
									</tr>
									<tr>
										<td nowrap title="<?=@$Tov02_nome?>"><?=@$Lov02_nome?></td>
										<td colspan="2"> 
											<?db_input('ov02_nome',80,$Iov02_nome,true,'text',$db_opcao,"");?>
										</td>
									</tr>
									<tr>
										<td nowrap title="<?=@$Tov02_ident?>"><?=@$Lov02_ident?></td>
										<td>
											<?db_input('ov02_ident',20,$Iov02_ident,true,'text',$db_opcao,"");?>
										</td>
										<td nowrap title="<?=@$Tov02_cnpjcpf?>" align="left"><?=@$Lov02_cnpjcpf?>
											<?
											db_input('ov02_cnpjcpf',14,$Iov02_cnpjcpf,true,'text',$db_opcao,"")
											?>
									  </td>
									</tr>
									<tr>
										<td><b>Tipo Retorno:</b></td>
										<td colspan="2" id="tiporetorno">
											<?
												$rsTipoRetorno = $cltiporetorno->sql_record($cltiporetorno->sql_query_file());
												$iNumRowsTipoRetorno = pg_num_rows($rsTipoRetorno);
												if($iNumRowsTipoRetorno > 0){
													for($i=0; $i < $iNumRowsTipoRetorno; $i++){
														db_fieldsmemory($rsTipoRetorno,$i);
														$y = $i + 1;
														echo "<input type=\"checkbox\" name=\"tiporetorno$y\" id=\"tiporetorno$y\" value=\"$ov22_sequencial\" disabled >";
														echo "<b>".$ov22_descricao."</b>&nbsp;";
													}
												}
											?>
										</td>
									</tr>
				        </table>
				      </fieldset>
			      </td>
			    </tr>
			    <tr>
			      <td>
				      <fieldset>
				        <legend>
				          <b>Retorno por Carta/Pessoalmente</b>
				        </legend>
				        <table>
									<tr>
										<td nowrap title="<?=@$Tov02_endereco?>">
											<?
											db_ancora(@$Lov02_endereco,"js_pesquisaov02_endereco(true);",$db_opcao,'','ancora_endereco');
											?>
											
										<td>
											<?
											db_input('ov02_endereco',50,$Iov02_endereco,true,'text',$db_opcao,"")
											?>
										</td>
										<td nowrap title="<?=@$Tov02_numero?>" align="right">
											<?=@$Lov02_numero?>
										</td>
										<td>
											<?
											db_input('ov02_numero',20,$Iov02_numero,true,'text',$db_opcao,"")
											?>
										</td>
									</tr>
									<tr>
										<td nowrap title="<?=@$Tov02_bairro?>">
										<? 
										db_ancora(@$Lov02_bairro,"js_pesquisaov02_bairro(true);",$db_opcao,'','ancora_bairro');
					       		?>
					       		</td>
										<td> 
											<?
											db_input('ov02_bairro',50,$Iov02_bairro,true,'text',$db_opcao,"")
											?>
										</td>
										<td nowrap title="<?=@$Tov02_compl?>">
											<?=@$Lov02_compl?>
										</td>
										<td>
											<?
											db_input('ov02_compl',20,$Iov02_compl,true,'text',$db_opcao,"")
											?>
										</td>  					
									</tr>
									<tr>
										<td nowrap title="<?=@$Tov02_munic?>"><?=@$Lov02_munic?></td>
										<td> 
											<?
											db_input('ov02_munic',30,$Iov02_munic,true,'text',$db_opcao,"")
											?>
											<?=@$Lov02_uf?>
											<?
											db_input('ov02_uf',2,$Iov02_uf,true,'text',$db_opcao,"")
											?>
										</td>
										<td nowrap title="<?=@$Tov02_cep?>" align="right"><?=@$Lov02_cep?></td>
										<td> 
											<?db_input('ov02_cep',20,$Iov02_cep,true,'text',$db_opcao,"")?>
										</td>
									</tr>
				        </table>
				      </fieldset>
			      </td>
			    </tr>
			    <tr>
			      <td>
							<fieldset>
							  <legend>
							    <b>Retorno por Email</b>
							  </legend>
								<table width="100%">
					  			<tr>
					  				<td>
					  					<fieldset>
					  					  <legend>
					  					    <b>Lista Emails</b>
					  					  </legend>
						  					<div id="listaemails">
						  					</div>
					  					</fieldset>
					  				</td>
					  			</tr>
								</table>
							</fieldset>
						</td>
				  </tr>
					<tr>
					  <td>
						  <fieldset>
						    <legend>
						      <b>Retorno por Telefone/Fax</b>
						    </legend>
						    <table width="100%">
		  			      <tr>
		  			        <td>
		  				        <fieldset>
			   			          <legend>
						              <b>Lista Telefones</b>
						            </legend>
						            <div id="listatelefones">
		            			  </div>
						          </fieldset>
						        </td>
						      </tr>
						    </table>
						  </fieldset>
					  </td>
					</tr>
		    </table>
	    </fieldset>
    </td>
  </tr>
  <tr align="center">
    <td>
      <input type="button" name="voltar"  value="Voltar"           onClick="parent.db_iframe_detalhes.hide();"/>
      <input type="button" name="alterar" value="Alterar Cadastro" onClick="parent.js_alteraCadastro(<?=$oGet->iCodigo?>,'<?=$oGet->sTipo?>',document.form1.ov02_seq.value);"/>
      <input type="button" name="confirm" value="Confirmar"        onClick="parent.js_confirmaSelecao(<?=$oGet->iCodigo?>,'<?=$oGet->sTipo?>',document.form1.ov02_seq.value,document.form1.ov02_nome.value);"/>
    </td>
  </tr>
</table>
</center>
</form>
<script type="text/javascript">

	function js_frmListaEmails(){
		oDBGridListaEmails = new DBGrid('emails');
		oDBGridListaEmails.nameInstance = 'oDBGridListaEmails';
		oDBGridListaEmails.setHeader(new Array('Descrição','Principal'));
		oDBGridListaEmails.setHeight(80);
		oDBGridListaEmails.setCellAlign(new Array('left','center'));
		oDBGridListaEmails.setCellWidth(new Array(260,5));
		oDBGridListaEmails.show($('listaemails'));
	}
	
	function js_frmListaTelefones(){
  	oDBGridListaTelefones = new DBGrid('telefones');
		oDBGridListaTelefones.nameInstance = 'oDBGridListaTelefones';
		oDBGridListaTelefones.setHeader(new Array('Descrição','DDD','Número','Ramal','Principal'));
		oDBGridListaTelefones.setHeight(80);
		oDBGridListaTelefones.setCellAlign(new Array('left','center','left','center','center'));
		oDBGridListaTelefones.show($('listatelefones'));
	}
	
		
	function js_RenderGridEmail(aEmail){
	
			oDBGridListaEmails.clearAll(true);
			
			var iNumRows = aEmail.length;
			
			if(iNumRows > 0){
				aEmail.each(
					function (oEmail,iInd){
						aRow 												= new Array();
						aRow[0] 										= oEmail.ov08_email.urlDecode();
						aRow[1] 										= oEmail.descricao.urlDecode();
	 					oDBGridListaEmails.addRow(aRow);
		 			}	
					
				);
			}
			oDBGridListaEmails.renderRows();
				
		}
	
	
	function js_pesquisaCidadao(chavepesquisa){
		
		oPesquisar = new Object();
		oPesquisar.chave 	= chavepesquisa;
		oPesquisar.acao		= 'pesquisar';
		
		var sDados = Object.toJSON(oPesquisar);
			
			js_divCarregando('Aguarde Carregando dados do Cidadão...','msgBox');
		
			sUrl = 'ouv1_cidadao.RPC.php';
			var sQuery = 'dados='+sDados;
			var oAjax   = new Ajax.Request( sUrl, {
	                                            method: 'post', 
	                                            parameters: sQuery, 
	                                            onComplete: js_retornoPesquisaCidadao
	                                          }
	                                  );			
		
	}
	
	function js_retornoPesquisaCidadao(oAjax){
			
		js_removeObj("msgBox");
	  
	  var aRetorno = eval("("+oAjax.responseText+")");
	  
	  var sExpReg  = new RegExp('\\\\n','g');
	  
	  if ( aRetorno.status == 1) {
	    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
	    return false;
	  }else{
	  	
	  	js_PreenchePesquisaCidadao(aRetorno.cidadao);
	  	js_PreenchePesquisaTipoRetorno(aRetorno.tiporetorno);
			js_PreenchePesquisaCidadaoEmails(aRetorno.cidadaoemails);
			js_PreenchePesquisaCidadaoTelefones(aRetorno.cidadaotelefones);
	  	
	  } 
		
	}
	
	function js_PreenchePesquisaCidadao(aCidadao){
		
		with (aCidadao[0])  {
		  $('ov02_sequencial').value 	= ov02_sequencial;
		  $('ov02_seq').value         = ov02_seq;
		  $('ov03_numcgm').value 			= ov03_numcgm
		  $('ov02_nome').value 				= ov02_nome.urlDecode();
		  $('ov02_ident').value				= ov02_ident;
		  $('ov02_cnpjcpf').value 		= ov02_cnpjcpf;
		  $('ov02_endereco').value		=	ov02_endereco.urlDecode(); 
			$('ov02_numero').value			=	ov02_numero; 
			$('ov02_bairro').value			=	ov02_bairro.urlDecode(); 
			$('ov02_munic').value				=	ov02_munic.urlDecode(); 
			$('ov02_uf').value					=	ov02_uf.urlDecode(); 
			$('ov02_cep').value					=	ov02_cep; 
			$('ov02_compl').value				=	ov02_compl.urlDecode(); 
			$('z01_nome').value					=	z01_nome.urlDecode(); 
		}
		
	}
	
	function js_PreenchePesquisaTipoRetorno(aTipoRetorno){
		
		var iNumRows = aTipoRetorno.length;
		
		for (var i=0; i < iNumRows; i++){
			with (aTipoRetorno[i])  {
				var sCheckBox = 'tiporetorno'+aTipoRetorno[i].ov04_tiporetorno;
				$(sCheckBox).checked = true	; 
			}
		}
		
	}
	
	function js_PreenchePesquisaCidadaoTelefones(aCidadaoTelefones){
		js_RenderGridTelefone(aCidadaoTelefones);
	}
	
	function js_PreenchePesquisaCidadaoEmails(aCidadaoEmails){
		js_RenderGridEmail(aCidadaoEmails);
	}


  function js_RenderGridTelefone(aTelefone){
  
		oDBGridListaTelefones.clearAll(true);
		
		var iNumRows = aTelefone.length;
		
		if(iNumRows > 0){
			aTelefone.each(
				function (oTelefone,iInd){
					
					var aRow = new Array();
					
					aRow[0]  = oTelefone.descricao.urlDecode();
					aRow[1]  = oTelefone.ov07_ddd;
					aRow[2]  = oTelefone.ov07_numero; 		
	 				aRow[3]  = oTelefone.ov07_ramal; 	
	 				aRow[4]  = oTelefone.descrprincipal.urlDecode(); 	
 					
 					oDBGridListaTelefones.addRow(aRow);
 					
	 			}	
			);
		}
		oDBGridListaTelefones.renderRows();	
	}
 

	function js_pesquisaCGM(iCgm){
	  
	  var oPesquisar = new Object();
			  oPesquisar.numcgm = iCgm;
			  oPesquisar.acao   = 'pesquisaCGM';
	  
	  var sDados = Object.toJSON(oPesquisar);
	    
	    js_divCarregando('Aguarde Carregando dados do CGM...','msgBox');
	  
	    sUrl = 'ouv1_cidadao.RPC.php';
	    var sQuery = 'dados='+sDados;
	    var oAjax   = new Ajax.Request( sUrl, {
	                                            method: 'post', 
	                                            parameters: sQuery, 
	                                            onComplete: js_retornoPesquisaCGM
	                                          }
	                                  );      
	  
	}
	
	function js_retornoPesquisaCGM(oAjax){
	    
	  js_removeObj("msgBox");
	  
	  var aRetorno = eval("("+oAjax.responseText+")");
	  var sExpReg  = new RegExp('\\\\n','g');
	  
	  if ( aRetorno.status == 1) {
	    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
	    return false;
	  }else{
	    
	    js_PreenchePesquisaCidadao(aRetorno.cidadao);
	    js_PreenchePesquisaTipoRetorno(aRetorno.tiporetorno);
	    js_PreenchePesquisaCidadaoEmails(aRetorno.cidadaoemails);
	    js_PreenchePesquisaCidadaoTelefones(aRetorno.cidadaotelefones);
	    
	  } 
	}
	
	$('ov02_nome').focus();
	js_frmListaEmails();
	js_frmListaTelefones();

	
	 
</script>