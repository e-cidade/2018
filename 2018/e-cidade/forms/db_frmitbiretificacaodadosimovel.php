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

//MODULO: itbi
$clitbidadosimovel->rotulo->label();
$clitbidadosimovelsetorloc->rotulo->label();
$clitbi->rotulo->label();
$clitburbano->rotulo->label();
$clitbirural->rotulo->label();
$clitbiruralcaract->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("it04_descr");
$clrotulo->label("it07_descr");
$clrotulo->label("j05_descr");
$clrotulo->label("j31_codigo");

$tipo = $oGet->tipo; 

if ( $oGet->tipo == "urbano") {
  $sPrefix     = "do ";
  $sTerraLabel = "Terreno";
  $sMedida	   = "m²";	
} else {
  $sPrefix     = "da ";	
  $sTerraLabel = "Terra";
  $sMedida	   = "ha"; 	
}
?>
<center>
  <form name="form1" method="post" action="">
    <table width="750px;">
      <tr align="center">
        <td>
          <b>I.T.B.I. <?=strtoupper($oGet->tipo)?></b>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Dados ITBI</b>
            </legend>
            <table width="100%">
			  <tr>
			    <td width="16%">
			      <b>Código da ITBI:</b>
			    </td>
			    <td align="left"> 
				   <?
					 db_input('it01_guia'      ,20,$Iit01_guia,true,'text',3);
					 							
//					 db_input('j01_matric'     ,10,"",true,'hidden',3);
					 db_input('it22_sequencial',10,"",true,'hidden',3);
					 db_input('listaFormas'    ,10,"",true,'hidden',3);
					 db_input('tipo'	         ,10,"",true,'hidden',3);
			       ?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit01_mail?>">
			      <?=@$Lit01_mail?>
			    </td>
			    <td> 
				  <?
					db_input('it01_mail',50,$Iit01_mail,true,'text',$db_opcao,"");
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
              <b>Dados do Imóvel - <?=$sTerraLabel?></b>
            </legend>
            <table width="100%">
              <tr>
                <td>
                  <fieldset>
                    <legend>
                      <b>Localização</b>
                    <legend>
                    <table width="100%">
                      <tr>
                        <td>
                          <b>Setor/Bairro :</b>
                        </td>
                        <td colspan="3">
                          <?
							db_input('it22_setor',20,$Iit22_setor,true,'text',$db_opcao);
						  ?>	
                        </td>
                      </tr>
				      <tr>
				        <td nowrap title="<?=@$Tit22_descrlograd?>" width="15%">
 				           <?=@$Lit22_descrlograd?>
				        </td>
				        <td colspan="3"> 
					      <?
 					        db_input('it22_descrlograd',112,$Iit22_descrlograd,true,'text',$db_opcao,"");
 				          ?>
				        </td>
				      </tr>
					  <tr>
					    <td nowrap title="<?=@$Tit22_numero?>">
					      <?=@$Lit22_numero?>
					    </td>
					    <td> 
						  <?
							db_input('it22_numero',20,$Iit22_numero,true,'text',$db_opcao,"");
						  ?>
					    </td>
					    <td nowrap title="<?=@$Tit22_compl?>" align="right">
					      <?=@$Lit22_compl?>
					    </td>
					    <td align="right"> 
					  	  <?
							db_input('it22_compl',20,$Iit22_compl,true,'text',$db_opcao,"");
						  ?>
					    </td>
				  	  </tr>
					  <tr>
					    <td nowrap title="<?=@$Tit22_quadra?>">
					      <?=@$Lit22_quadra?>
					    </td>
					    <td> 
						  <?
							db_input('it22_quadra',20,$Iit22_quadra,true,'text',$db_opcao,"");
						  ?>
					    </td>
					    <td nowrap title="<?=@$Tit22_lote?>" align="right">
					      <?=@$Lit22_lote?>
					    </td>
					    <td align="right"> 
						  <?
							db_input('it22_lote',20,$Iit22_lote,true,'text',$db_opcao,"");
						  ?>
					    </td>
					  </tr>
					  
					  <? if ( $oGet->tipo == "urbano" ) {?>
					  	
					  <tr>
					    <td nowrap title="<?=@$Tit05_itbisituacao?>">
					      <?
					        db_ancora(@$Lit05_itbisituacao,"js_pesquisait05_itbisituacao(true);",$db_opcao);
					      ?>
					    </td>
					    <td colspan="3"> 
						  <?
							db_input('it05_itbisituacao',20,$Iit05_itbisituacao,true,'text',$db_opcao," onchange='js_pesquisait05_itbisituacao(false);'");
							db_input('it07_descr',87,$Iit07_descr,true,'text',3,'');
					      ?>
					    </td>
					  </tr>
					  
					  <? } else { ?>
					  <tr>
              <td>
                <?=@$Lit18_coordenadas?>
              </td>
              <td colspan="3"> 
                <?
                  db_input('it18_coordenadas',112,$Iit18_coordenadas,true,'text',$db_opcao);
                ?>
              </td>					  
					  </tr>
					  <tr>
					    <td>
					      <b>Localização:</b>
					    </td>
					    <td colspan="3"> 
						  <?
							db_input('it18_localimovel',112,$Iit18_localimovel,true,'text',$db_opcao);
					      ?>
					    </td>
					  </tr>
					  <tr>
					    <td>
					      <b>Distância da Cidade:</b>
					    </td>
					    <td colspan="3"> 
						  <?
							db_input('it18_distcidade',20,$Iit18_distcidade,true,'text',$db_opcao);
					      ?>
					      <b>Km</b>
					    </td>
					  </tr>
					  <tr>
					    <td colspan="4">
					      <b>Imóvel faz frente para logradouro ?</b>
					       <input type="radio" name="lFrenteLogradouro" value="s" onChange="js_frenteLogradouro(this.value);"<?=((isset($it18_nomelograd) && trim($it18_nomelograd)!="")?"checked":"")?>>Sim</input>
					       <input type="radio" name="lFrenteLogradouro" value="n" onChange="js_frenteLogradouro(this.value);"<?=(!isset($it18_nomelograd)||(isset($it18_nomelograd) && trim($it18_nomelograd)=="")?"checked":"")?>>Não</input>
					    </td>
					  </tr>
					  <tr id="frenteLogradouro" <?=($db_opcao!=1||(isset($it18_nomelograd) && trim($it18_nomelograd)!="")?"":"style='display:none'")?>>
					    <td>
					      <b>Nome Logradouro:</b>
					    </td>
					    <td colspan="3"> 
						  <?
							db_input('it18_nomelograd',112,$Iit18_nomelograd,true,'text',$db_opcao);
					      ?>
					    </td>
					  </tr>					  							  					  					  
					  <tr>
					    <td colspan="4">
					       <?
					         db_ancora("<b>Característica do Imóvel</b>","js_caract('imovel');",$db_opcao);
					         db_input('valorCaracImovel',20,"",true,'hidden',$db_opcao,"");
					       ?>
					    </td>
					  </tr>
					  <tr>
					    <td colspan="4">
					       <?
					         db_ancora("<b>Característica de Utilização do Imóvel</b>","js_caract('util');",$db_opcao);
					         db_input('valorCaracUtil',20,"",true,'hidden',$db_opcao,"");
					       ?>
					    </td>
					  </tr>					  
					  					  
					  <? } ?>
					  
                    </table>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td>
                  <fieldset>
                    <legend>
                      <b>Medidas</b>
                    <legend>
                  <table width="100%">
                    <tr>
                      <td width="15%">
                        <b>Área Total:</b>
                      </td>
                      <td>
                        <?
                          db_input('it01_areaterreno',20,$Iit01_areaterreno,true,'text',$db_opcao,"");
                        ?>
                        <b><?=$sMedida?></b>
                      </td>
                      <td  align="right">
                        <b>Área Transmitida:</b>
                      </td>
                      <td  align="right">
                        <?
                          db_input('it01_areatrans',20,$Iit01_areatrans,true,'text',$db_opcao,"");                          
                        ?>
                        <b><?=$sMedida?></b>
                      </td>                      
                    </tr>
                    
                    <? if ( $oGet->tipo == "urbano") {?> 
                    
                    <tr>
                      <td>
                        <b>Frente:</b>
                      </td>
                      <td>
                        <?
                          db_input('it05_frente',20,$Iit05_frente,true,'text',$db_opcao,"");                          
                        ?>
                        <b><?=$sMedida?></b>
                      </td>
                      <td align="right">
                        <b>Fundos:</b>
                      </td>
                      <td align="right">
                        <?
                          db_input('it05_fundos',20,$Iit05_fundos,true,'text',$db_opcao,"");                          
                        ?>
                        <b><?=$sMedida?></b>
                      </td>                      
                    </tr>                    
                    <tr>
                      <td>
                        <b>Lado Direito:</b>
                      </td>
                      <td>
                        <?
                          db_input('it05_direito',20,$Iit05_direito,true,'text',$db_opcao,"");                          
                        ?>
                        <b><?=$sMedida?></b>
                      </td>
                      <td align="right">
                        <b>Lado Esquerdo:</b>
                      </td>
                      <td align="right">
                        <?
                          db_input('it05_esquerdo',20,$Iit05_esquerdo,true,'text',$db_opcao,"");                          
                        ?>
                        <b><?=$sMedida?></b>
                      </td>                      
                    </tr>  
                    
                    <? } else { ?>
                   	<tr>
					  <td nowrap title="<?=@$Tit18_frente?>">
					    <?=@$Lit18_frente?>
					  </td>
					  <td> 
					    <?
					  	  db_input('it18_frente',20,$Iit18_frente,true,'text',$db_opcao,"");
						  db_input('it18_guia',10,$Iit18_guia,true,'hidden',$db_opcao,"");
						?>
						<b><?=$sMedida?></b>						
					  </td>
					  <td nowrap title="<?=@$Tit18_fundos?>" align="right">
					    <?=@$Lit18_fundos?>
					  </td>
					  <td align="right"> 
					    <?
					  	  db_input('it18_fundos',20,$Iit18_fundos,true,'text',$db_opcao,"")
						?>
						<b><?=$sMedida?></b>
					  </td>
					</tr>
					<tr>
					  <td nowrap title="<?=@$Tit18_prof?>">
					    <?=@$Lit18_prof?>
					  </td>
					  <td colspan="2"> 
					    <?
					  	  db_input('it18_prof',20,$Iit18_prof,true,'text',$db_opcao,"")
						?>
						<b><?=$sMedida?></b>						
					  </td>
					</tr>
					<? } ?>                                      
                  </table>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td>
                  <fieldset>
                    <legend>
                      <b>Dados Registro de Imóvel</b>
                    <legend>
                  <table width="100%">
                    <tr>
                      <td width="100px;">
					    <?
					      db_ancora("<b>Setor:</b>","js_pesquisait29_setorloc(true);",$db_opcao);
					    ?>                      
                      </td>
                      <td colspan="3">
                        <?
                          db_input('it29_setorloc',20,$Iit29_setorloc,true,'text',$db_opcao,"onChange='js_pesquisait29_setorloc(false);'");                        
    					  db_input('j05_descr',87,$Ij05_descr,true,'text',3);                          
                        ?>
                      </td>                      
                    </tr>
                    <tr>  
                      <td>
                      	<b>Quadra:</b>
                      </td>
                      <td>
                        <?
                          db_input('it22_quadrari',20,$Iit22_quadrari,true,'text',$db_opcao,"");                        
                        ?>
                      </td>
                      <td align="right">
                      	<b>Lote:</b>
                      </td>
                      <td align="right">
                        <?
                          db_input('it22_loteri',20,$Iit22_loteri,true,'text',$db_opcao,"");                        
                        ?>
                      </td>                                            
                    </tr>
                    <tr>
                      <td width="100px;">
                        <b>Matrícula</b>
                      </td>
                      <td colspan="3">
                        <?
                          db_input('it22_matricri',20,$Iit22_matricri,true,'text',$db_opcao);                        
                        ?>
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
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Dados da Transação</b>
            </legend>
            <table width="100%">
			  <tr>
			    <td title="<?=@$Tit01_tipotransacao?>" width="16%">
			      <?
			        db_ancora(@$Lit01_tipotransacao,"js_pesquisait01_tipotransacao(true);",$db_opcao);
			      ?>
			    </td>
			    <td colspan="5"> 
				   <?
					 db_input('it01_tipotransacao',20,$Iit01_tipotransacao,true,'text',$db_opcao," onBlur='js_pesquisait01_tipotransacao(false);'");
					 db_input('it04_descr',90,$Iit04_descr,true,'text',3,'');
			       ?>
			    </td>
			  </tr>
			  <tr>
			    <td>
			      <b>Valor  <?=$sPrefix.$sTerraLabel?>:</b>
			    </td>
			    <td> 
				   <?
					 db_input('it01_valorterreno',20,$Iit01_valorterreno,true,'text',$db_opcao,"onBlur='js_validaValores(this)'");
			       ?>
			    </td>
			    <td>
			      <b>Valor das Benfeitorias:</b>
			    </td>
			    <td> 
				   <?
					 db_input('it01_valorconstr',20,$Iit01_valorconstr,true,'text',$db_opcao,"onBlur='js_validaValores(this)'");
			       ?>
			    </td>
			    <td>
			      <b>Valor Total:</b>
			    </td>
			    <td> 
				   <?
					 db_input('it01_valortransacao',20,$Iit01_valortransacao,true,'text',$db_opcao,"onBlur='js_validaValores(this)'");
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
              <b>Dados de Pagamento</b>
            </legend>
			<div id="listaFormasPgto"></div>
          </fieldset>
        </td>
      </tr>      
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Observações</b>
            </legend>
            <table width="100%">
			  <tr>
			    <td>
 			      <?
					db_textarea('it01_obs',3,134,$Iit01_obs,true,'text',$db_opcao,"");			    
			      ?>
			    </td>			    			    
			  </tr> 			              
            </table>
          </fieldset>
        </td>
      </tr>       
    </table>
  <input name="incluir"   type="submit" id="db_opcao" value="Incluir" <?=($db_botao==false?"disabled":"")?>  
         onClick=" return js_validaCampos();">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</center>

<script>
function js_liberaguia(guia) {
  var iCodGuia = guia;
  parent.location.href='itb1_itbiavalia001.php?chavepesquisa='+iCodGuia;
}

function js_visualizar(guia) {
  var iGuia  = guia;
  var sParam = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+
                (screen.height-100)+",width="+(screen.width-100);
  window.open('reciboitbi.php?itbi='+iGuia,"",sParam);
}

function js_frenteLogradouro(sValor){
  
  if ( sValor == "s") {
    document.getElementById('frenteLogradouro').style.display = "";
  } else {
    document.getElementById('frenteLogradouro').style.display = "none";
  }

}

function js_validaCampos() {

  var doc = document.form1;

  if ( doc.tipo.value == "urbano"  ) {
  	
    if ( doc.it05_itbisituacao.value == "" ) {
      alert("Campo situação não informado!");
      return false;
    }
    if ( doc.it05_frente.value == "" ) {
      alert("Campo Frente não informado!");
      return false;
    }
    if ( doc.it05_direito.value == "" ) {
      alert("Campo Lado Direito não informado!");
      return false;
    }
    if ( doc.it05_esquerdo.value == "" ) {
      alert("Campo Lado Esquerdo não informado!");
      return false;
    }    
    if ( doc.it05_fundos.value == "" ) {
      alert("Campo Fundos não informado!");
      return false;
    }    
    
    
  } else {

    if ( doc.it18_localimovel.value == "" ) {
      alert("Localização do imóvel não informada!");
      return false;
    }
    if ( doc.it18_distcidade.value == "" ) {
      alert("Distância da cidade não informada!");
      return false;
    } 
    if ( doc.it18_frente.value == "" ) {
      alert("Campo Frente não informado!");
      return false;
    }
    if ( doc.it18_prof.value == "" ) {
      alert("Campo Profundidade não informado!");
      return false;
    }
    if ( doc.it18_fundos.value == "" ) {
      alert("Campo Fundos não informado!");
      return false;
    }
    
    if (doc.it01_areaterreno.value > 50) {
    
      if ( doc.it18_coordenadas.value == "" ) {
      
        alert("Campo Longitude/Latitude não informado!");
        return false;      
      }    
    }
  
  }

  if ( doc.it01_areaterreno.value == "" ) {
    alert("Área Total não informada!");
    return false;
  }
  
  if ( doc.it01_areatrans.value == "" ) {
    alert("Área Transmitida não informada!");
    return false;
  }
  
  if ( doc.it01_tipotransacao.value == "" ) {
    alert("Tipo de transação não informado!");
    return false;
  }  
  
  
  if ( doc.it01_valortransacao.value == "" ) {
    alert("Valor total não informado!");
    return false;
  }

 
  var aObjFormasPgto = js_getElementbyClass(document.all,'formasPgto');
  var sQuery 		 = "";
  
  if (aObjFormasPgto.length == 0) {
  
    alert('Nenhuma forma de pagamento informada!')
    return false;
    
  } else {
    
    var sPrefix = "";
    for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
      sQuery += sPrefix+aObjFormasPgto[iInd].id+"X"+aObjFormasPgto[iInd].value;
      sPrefix = "|";
    }

    document.form1.listaFormas.value = sQuery;
    
  }
  
}

function js_controlaValoresFormaPgto(obj){
  
  var doc 	          = document.form1;
  var aObjFormasPgto  = js_getElementbyClass(document.all,'formasPgto');
  var nValorTotal	  = new Number(doc.it01_valortransacao.value);
      obj.value 	  = new String(obj.value).replace(",",".");
      obj.value			= new Number(obj.value).toFixed(2);
  var nValorAlterado  = new Number(obj.value); 
  var nValorResto	  = new Number();
  
  
  for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
    if ( aObjFormasPgto[iInd].name != "primeiro" ) {
     var nValLinha = new Number(aObjFormasPgto[iInd].value);
	 nValorResto  += nValLinha;
	}   	        
  }
  
  var nValorAvista = new Number( nValorTotal - nValorResto );  
  
  if ( nValorAvista < 0 ) {
    
    nValorAvista = nValorTotal - ( nValorResto - new Number(obj.value));
    alert("A soma dos valores das formas de pagamento não conferem com o valor total do imóvel!");
    obj.value         = 0;
    
  }
  		
  doc.primeiro.value = new Number(nValorAvista).toFixed(2);
  
}


function js_validaValores(obj){
  
  var sNomeCampo		= obj.name;
      obj.value			= new String(obj.value).replace(",",".");
      obj.value			= new Number(obj.value).toFixed(2);			
  var doc				= document.form1;
      doc.it01_valortransacao.value = new String(doc.it01_valortransacao.value).replace(",",".");
  var nValorTotal 	    = new Number(doc.it01_valortransacao.value);
  var nValorTerreno 	= new Number(doc.it01_valorterreno.value);
  var nValorBenfeitoria = new Number(doc.it01_valorconstr.value);
  
  
  if ( nValorTerreno != 0 || nValorBenfeitoria != 0 ) {
	doc.it01_valortransacao.disabled = true;
    doc.it01_valortransacao.value    = new Number(nValorTerreno + nValorBenfeitoria).toFixed(2);
  } else if ( nValorTerreno == 0 && nValorBenfeitoria == 0 && sNomeCampo == "it01_valortransacao" && nValorTotal != 0) {
    doc.it01_valorterreno.disabled   = true;
    doc.it01_valorconstr.disabled    = true;
  } else if ( nValorTerreno == 0 && nValorBenfeitoria == 0 && sNomeCampo != "it01_valortransacao") {
    doc.it01_valortransacao.value    = 0;
    doc.it01_valortransacao.disabled = false;
  } else {
    doc.it01_valorterreno.disabled   = false;
    doc.it01_valorconstr.disabled    = false;
    doc.it01_valortransacao.disabled = false;  
  }
  
  
  if ( doc.primeiro != undefined) {
    js_limpaValorFormaPgto(); 
    doc.primeiro.value = new Number(doc.it01_valortransacao.value).toFixed(2);
  } 
  

}

function js_limpaValorFormaPgto(){

  var aObjFormasPgto = js_getElementbyClass(document.all,'formasPgto');
  for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
     aObjFormasPgto[iInd].value = 0;
  }

}

function js_criaGrid() {

  gridFormasPgto              = new DBGrid("listaFormasPgto");
  gridFormasPgto.nameInstance = "gridFormasPgto";
  
  gridFormasPgto.setCellAlign( new Array("left","center","right") );
  gridFormasPgto.setHeader   ( new Array("Descrição","Alíquota %","Valor"));
  gridFormasPgto.setCellWidth( new Array("60%","20%","20%"));
  gridFormasPgto.setHeight(80);
  gridFormasPgto.show(document.getElementById('listaFormasPgto'));

  
  closeOnSave    = false;
  
}

function js_consultaFormaPgto(iCodTransacao){

  js_divCarregando('Aguarde...','msgBox',false);
	  
  var url          = "itb4_consultaformaPagamentoRPC.php";
  var sQuery	   = "codtransacao="+iCodTransacao;
      sQuery	  += "&tipoPesquisa=formasDisponiveis";  
      sQuery	  += "&tipoITBI="+document.form1.tipo.value;
  var oAjax        = new Ajax.Request( url, {
                                              method: 'post', 
                                              parameters: sQuery, 
                                              onComplete: js_retornoFormaPgto
                                            }
                                      );

}

function js_consultaFormaPgtoCadastrada(iGuia){

  js_divCarregando('Aguarde...','msgBox',false);
	  
  var url          = "itb4_consultaformaPagamentoRPC.php";
  var sQuery	   = "codguia="+iGuia;
      sQuery	  += "&tipoPesquisa=formasCadastradas";
  var oAjax        = new Ajax.Request( url, {
                                              method: 'post', 
                                              parameters: sQuery, 
                                              onComplete: js_retornoFormaPgtoCadastrada
                                            }
                                      );

}



function js_retornoFormaPgto(oAjax){

  var objListaForma = eval("("+oAjax.responseText+")");
  var nValor		= 0;
  
  gridFormasPgto.clearAll(true);
	  
  if ( objListaForma.iStatus && objListaForma.iStatus == 2){
   	js_removeObj("msgBox");
   	alert(objListaForma.sMensagem.urlDecode());
   	return false ;
  }
 
  for ( var iInd = 0; iInd < objListaForma.length; iInd++ ) {
  
    with (objListaForma[iInd]) {
      
      if ( iInd == 0 ) {
        nValor         = document.form1.it01_valortransacao.value;      
        var sDisabled  = "disabled";
        var sNomeCampo = "name='primeiro'";
      } else {
        nValor         = 0;
        var sDisabled  = "";
        var sNomeCampo = "";
      }
      
      var sInputValor  = "<input type='text' id='"+it25_sequencial.urlDecode()+"' class='formasPgto' value='"+nValor+"'";
    	  sInputValor += "style='width:100%;text-align:right;height:100%;border:1px inset' "+sDisabled+" "+sNomeCampo+"";
    	  sInputValor += " onChange='js_controlaValoresFormaPgto(this);'>";
   
      var aLinha	= new Array();
   	      aLinha[0] = it27_descricao.urlDecode();
    	  aLinha[1] = js_formatar(it27_aliquota.urlDecode(),'f');
    	  aLinha[2] = sInputValor;
    
      gridFormasPgto.addRow(aLinha);
      gridFormasPgto.renderRows();
      
    }
  }
  
  js_removeObj("msgBox");
  document.form1.it01_valortransacao.focus();
}

function js_retornoFormaPgtoCadastrada(oAjax){

  var objListaForma = eval("("+oAjax.responseText+")");
  var nValor		= 0;
  
  gridFormasPgto.clearAll(true);
	  
  if ( objListaForma.iStatus && objListaForma.iStatus == 2){
   	js_removeObj("msgBox");
   	alert(objListaForma.sMensagem.urlDecode());
   	return false ;
  }
 
  for ( var iInd = 0; iInd < objListaForma.length; iInd++ ) {
  
    with (objListaForma[iInd]) {

      if ( iInd == 0 ) {
        var sDisabled  = "disabled";
        var sNomeCampo = "name='primeiro'";
      } else {
        var sDisabled  = "";
        var sNomeCampo = "";
      }
      
      var sInputValor  = "<input type='text' id='"+it25_sequencial.urlDecode()+"' class='formasPgto' value='"+it26_valor.urlDecode()+"'";
    	  sInputValor += "style='width:100%;text-align:right;height:100%;border:1px inset' "+sDisabled+" "+sNomeCampo+"";
    	  sInputValor += " onChange='js_controlaValoresFormaPgto(this);'>";
   
      var aLinha	= new Array();
   	      aLinha[0] = it27_descricao.urlDecode();
    	  aLinha[1] = js_formatar(it27_aliquota.urlDecode(),'f');
    	  aLinha[2] = sInputValor;
    
      gridFormasPgto.addRow(aLinha);
      gridFormasPgto.renderRows();
      
    }
  }
  
  js_removeObj("msgBox");
  document.form1.it01_valortransacao.focus();
}

function js_caract(sTipo){

  var sQuery  = "?guia="+document.form1.it01_guia.value;
      sQuery += "&tipo="+sTipo;
  
  js_OpenJanelaIframe('','db_iframe_caract','itb1_itbiruralcaract002.php'+sQuery,'Pesquisa',true,0);
  
}

function js_fecha(){
  db_iframe_caract.hide();
}

function js_pesquisait22_itbi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it22_itbi.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it22_itbi.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}

function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it22_itbi.focus(); 
    document.form1.it22_itbi.value = ''; 
  }
}

function js_mostraitbi1(chave1,chave2){
  document.form1.it22_itbi.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}

function js_pesquisait01_tipotransacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_itbitransacao','func_itbitransacao.php?validadata=true&funcao_js=parent.js_mostraitbitransacao1|it04_codigo|it04_descr','Pesquisa',true);
  }else{
     if(document.form1.it01_tipotransacao.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_itbitransacao','func_itbitransacao.php?validadata=true&pesquisa_chave='+document.form1.it01_tipotransacao.value+'&funcao_js=parent.js_mostraitbitransacao','Pesquisa',false);
     }else{
       document.form1.it04_descr.value = ''; 
     }
  }
}

function js_mostraitbitransacao(chave,erro){

  document.form1.it04_descr.value = chave;
   
  if(erro==true){ 
    document.form1.it01_tipotransacao.focus(); 
    document.form1.it01_tipotransacao.value = ''; 
  } else {
    js_consultaFormaPgto(document.form1.it01_tipotransacao.value);
  }  
  
}

function js_mostraitbitransacao1(chave1,chave2){

  document.form1.it01_tipotransacao.value = chave1;
  document.form1.it04_descr.value 		  = chave2;
  db_iframe_itbitransacao.hide();
  
  js_consultaFormaPgto(chave1);
  
}


function js_pesquisait05_itbisituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_itbisituacao','func_itbisituacao.php?funcao_js=parent.js_mostraitbisituacao1|it07_codigo|it07_descr','Pesquisa',true);
  }else{
     if(document.form1.it05_itbisituacao.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_itbisituacao','func_itbisituacao.php?pesquisa_chave='+document.form1.it05_itbisituacao.value+'&funcao_js=parent.js_mostraitbisituacao','Pesquisa',false);
     }else{
       document.form1.it07_descr.value = ''; 
     }
  }
}

function js_mostraitbisituacao(chave,erro){
  document.form1.it07_descr.value = chave;
  if(erro==true){ 
    document.form1.it05_itbisituacao.focus(); 
    document.form1.it05_itbisituacao.value = ''; 
  }
}

function js_mostraitbisituacao1(chave1,chave2){
  document.form1.it05_itbisituacao.value = chave1;
  document.form1.it07_descr.value = chave2;
  db_iframe_itbisituacao.hide();
}


function js_pesquisait29_setorloc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_setorregimovel','func_setorregimovel.php?funcao_js=parent.js_mostrasetorregimovel1|j69_sequencial|j69_descr','Pesquisa',true);
  }else{
     if(document.form1.it29_setorloc.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_setorregimovel','func_setorregimovel.php?pesquisa_chave='+document.form1.it29_setorloc.value+'&funcao_js=parent.js_mostrasetorregimovel','Pesquisa',false);
     }else{
       document.form1.j05_descr.value = ''; 
     }
  }
}

function js_mostrasetorregimovel(chave,erro){
  document.form1.j05_descr.value = chave;
  if(erro==true){ 
    document.form1.it29_setorloc.focus(); 
    document.form1.it29_setorloc.value = ''; 
  }
}

function js_mostrasetorregimovel1(chave1,chave2){
  document.form1.it29_setorloc.value = chave1;
  document.form1.j05_descr.value = chave2;
  db_iframe_setorregimovel.hide();
}

js_criaGrid();

function js_pesquisa(){
  js_OpenJanelaIframe('',
                      'db_iframe_itbi',
                      'func_itbilib.php?funcao_js=parent.js_preenchepesquisa|it01_guia','Pesquisa',true,0);
}

function js_preenchepesquisa(chave){
  var sTipo = '<?=$oGet->tipo?>';
  db_iframe_itbi.hide();
  location.href = 'itb1_itbiretificacaodadosimovel001.php?chavepesquisa='+chave+'&tipo='+sTipo;
}

<?
  if ( isset($oGet->chavepesquisa) ) {
	  echo "js_validaValores(document.form1.it01_valortransacao);";
	  echo "js_consultaFormaPgtoCadastrada(".$oGet->chavepesquisa.");";
  }
?>
</script>