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

  $clouvidoriacadlocal->rotulo->label();
  $clouvidoriacadlocalender->rotulo->label();
  $clouvidoriacadlocaldepart->rotulo->label();
  $clouvidoriacadlocalgeral->rotulo->label();
?>
<form name="form1" method="post" action="">
  <table>
    <tr>
      <td>
			  <fieldset>
			    <legend>
			      <b>Cadastro de Locais</b>
			    </legend>
				  <table border="0">
				    <tr>
				      <td nowrap title="<?=@$Tov25_sequencial?>" width="85px;">
				        <?=@$Lov25_sequencial?>
				      </td>
				      <td> 
								<?
								  db_input('ov25_sequencial',10,$Iov25_sequencial,true,'text',3,"");
								?>
				      </td>
				    </tr>
					  <tr>
					    <td nowrap title="<?=@$Tov25_descricao?>">
					       <?=@$Lov25_descricao?>
					    </td>
					    <td> 
								<?
								  db_input('ov25_descricao',63,$Iov25_descricao,true,'text',$db_opcao,"");
								?>
					    </td>
					  </tr>
            <tr>
              <td nowrap title="<?=@$Tov25_validade?>">
                 <?=@$Lov25_validade?>
              </td>
              <td> 
                <?
                  db_inputdata('ov25_validade',@$ov25_validade_dia,@$ov25_validade_mes,@$ov25_validade_ano,true,'text',$db_opcao);
                ?>
              </td>
            </tr>					  
			      <tr>
			        <td nowrap">
			          <b>Tipo de Local:</b>
			        </td>
			        <td> 
			          <?
			            $aTipoLocal = array("g"=>"Geral",
			                                "e"=>"Endereço",
			                                "d"=>"Departamento");
			            
			            db_select('tipoLocal',$aTipoLocal,true,$db_opcao,"onChange='js_validaTipo();'");
			          ?>
			        </td>
			      </tr>
				  </table>
				</fieldset>
			</td>
		</tr>
		<tr id="idGeral">
		  <td>		
				<fieldset>
				  <legend>
				    <b>Dados Gerais</b>
				  </legend>
				  <table>
			      <tr>
			        <td nowrap title="<?=@$Tov28_descricao?>" width="85px;">
			           <?=@$Lov28_descricao?>
			        </td>
			        <td> 
			          <?
			            db_input('ov28_descricao',63,$Iov28_descricao,true,'text',$db_opcao,"");
			          ?>
			        </td>
			      </tr>
				  </table>
				</fieldset>
      </td>
    </tr>
    <tr id="idEndereco" style="display:none">
      <td>			
			  <fieldset>
			    <legend>
			      <b>Dados Endereço</b>
			    </legend>
			    <table>
			      <tr>
			        <td nowrap title="<?=@$Tov26_ruas?>" width="85px;">
			           <?
			             db_ancora($Lov26_ruas,'js_pesquisaRuas(true);',$db_opcao,'');
			           ?>
			        </td>
			        <td colspan="3">  
			          <?
			            db_input('ov26_ruas',10,$Iov26_ruas,true,'text',$db_opcao,"onChange='js_pesquisaRuas(false);'");
			            db_input('j14_nome',50,'',true,'text',3,"");
			          ?>
			        </td>
			      </tr>
			      <tr>
			        <td nowrap title="<?=@$Tov26_numero?>">
			           <?=@$Lov26_numero?>
			        </td>
			        <td> 
			          <?
			            db_input('ov26_numero',10,$Iov26_numero,true,'text',$db_opcao,"");
			          ?>
			        </td>
			        <td nowrap title="<?=@$Tov26_complemento?>" align="right">
			           <?=@$Lov26_complemento?>
			        </td>
			        <td  align="right"> 
			          <?
			            db_input('ov26_complemento',30,$Iov26_complemento,true,'text',$db_opcao,"");
			          ?>
			        </td>        
			      </tr>
            <tr>
              <td><b>Bairro:</b></td>
              <td colspan='3'>
                <?
                  db_input('sBairro', 63, '', 3, 'text', 3);
                ?>
              </td>
            </tr>
			      <tr>
			        <td colspan='4'>
			          <fieldset>
			          <legend><b>Observações</b></legend>
			          <?
                  db_textarea("ov26_observacao", 4, 70, '', true, 'text', $db_opcao);
			          ?>
			          </fieldset>
			        </td>
			      </tr>      
			    </table>
			  </fieldset>
      </td>
    </tr>
    <tr id="idDepartamento" style="display:none">
      <td>      
        <fieldset>
          <legend>
            <b>Dados Departamento</b>
          </legend>
          <table>
            <tr>
              <td nowrap title="<?=@$Tov27_depart?>" width="85px;">
                 <?
                   db_ancora($Lov27_depart,'js_pesquisaDepart(true);',$db_opcao,'');
                 ?>
              </td>
              <td>  
                <?
                  db_input('ov27_depart',10,$Iov27_depart,true,'text',$db_opcao,"onChange='js_pesquisaDepart(false);'");
                  db_input('descrdepto',50,'',true,'text',3,"");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>    
  </table> 
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onClick="js_acaoLocal(this.name);">
<? if ( $db_opcao != 1 ) { ?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<? }
   if ( isset($lAtendimento) ) { ?>
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_local.hide();" >
<? } ?>

</form>
<script>

var sUrl = 'ouv1_localouvidoria.RPC.php';

function js_acaoLocal( sAcao ){
  
  js_divCarregando('Aguarde...','msgBox');
  
  var sQuery = '';
  
  if ( sAcao == 'incluir' ) { 
    sQuery += 'sMethod=incluirLocal';
  } else if ( sAcao == 'alterar' ) {
    sQuery += 'sMethod=alterarLocal';
  } else {
    sQuery += 'sMethod=excluirLocal';
  }
  
  sQuery += '&oDadosLocal='+Object.toJSON(js_getObjDadosTela());

  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: sQuery, 
                                          onComplete: js_retornoAcaoLocal
                                        }
                                );      
 
}


function js_retornoAcaoLocal(oAjax){

  js_removeObj("msgBox");
   
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
  
  alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
  
  if ( aRetorno.lErro ) {
    return false;
  } else {
    js_recarregaTela();
  }  
    
}



function js_getObjDadosTela(){

  var oDados = new Object();

  oDados.ov25_sequencial = $F('ov25_sequencial');
  oDados.ov25_descricao  = $F('ov25_descricao');
  oDados.ov25_validade   = $F('ov25_validade');
  oDados.sTipoLocal      = $F('tipoLocal');
  
  if ( $F('tipoLocal') == 'g' ) {
  
    oDados.ov28_descricao = $F('ov28_descricao');
  
  } else if ( $F('tipoLocal') == 'e' ) {
  
    oDados.ov26_ruas        = $F('ov26_ruas');
    oDados.ov26_numero      = $F('ov26_numero');
    oDados.ov26_complemento = $F('ov26_complemento');
    oDados.ov26_observacao  = $F('ov26_observacao');
  
  } else if ( $F('tipoLocal') == 'd' ){
  
    oDados.ov27_depart = $F('ov27_depart');
    
  }  
  return oDados;
}

function js_validaTipo(){
  
  $('idDepartamento').style.display = 'none';
  $('idEndereco').style.display     = 'none';
  $('idGeral').style.display        = 'none';
  
  if ( $F('tipoLocal') == 'g' ) {
    $('idGeral').style.display        = '';
  } else if ( $F('tipoLocal') == 'e' ) {
    $('idEndereco').style.display     = '';
  } else if ( $F('tipoLocal') == 'd' ){
    $('idDepartamento').style.display = '';
  }

}

function js_pesquisaRuas(lMostra){
  
  if(lMostra){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome|j13_descr','Pesquisa Ruas',true);
  } else {
    if( $F('ov26_ruas') != '' ){ 
      js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+$F('ov26_ruas')+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
    } else {
      document.form1.j14_nome.value = ''; 
    }
  }

}
function js_mostraruas(chave,sBairro,erro){
  document.form1.j14_nome.value = chave;
  document.form1.sBairro.value  = sBairro;
  
  if (sBairro.trim() == "") {
    js_msgBairroNaoCadastrado();
  }
  
  if(erro){ 
    document.form1.ov26_ruas.focus(); 
    document.form1.ov26_ruas.value = ''; 
  }
}

function js_mostraruas1(chave1,chave2, sBairro){

  document.form1.ov26_ruas.value = chave1;
  document.form1.j14_nome.value  = chave2;
  document.form1.sBairro.value   = sBairro;

  db_iframe_ruas.hide();
  if (sBairro.trim() == "") {
    js_msgBairroNaoCadastrado();
  }
}

function js_msgBairroNaoCadastrado() {
  alert("O logradouro selecionado não possui bairro cadastrado.");
}

function js_pesquisaDepart(lMostra){
  
  if(lMostra){
    js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa Departamento',true);
  } else {
    if( $F('ov27_depart') != '' ){ 
      js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?pesquisa_chave='+$F('ov27_depart')+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
    } else {
      document.form1.descrdepto.value = ''; 
    }
  }

}
function js_mostradepart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro){ 
    document.form1.ov27_depart.focus(); 
    document.form1.ov27_depart.value = ''; 
  }
}

function js_mostradepart1(chave1,chave2){
  document.form1.ov27_depart.value = chave1;
  document.form1.descrdepto.value  = chave2;
  db_iframe_depart.hide();
}


function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ouvidoriacadlocal','func_ouvidoriacadlocal.php?funcao_js=parent.js_preenchepesquisa|ov25_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ouvidoriacadlocal.hide();
  <?
	  if($db_opcao!=1){
	    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
	  }
  ?>
}

function js_recarregaTela(){
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>
}

if ($F('ov26_ruas') != "") {
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+$F('ov26_ruas')+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
}

 js_validaTipo();

</script>