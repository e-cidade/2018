<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

$clconplanoconta->rotulo->label();
$clconplano->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c52_descr");
$clrotulo->label("c61_reduz");
$clrotulo->label("c51_descr");
$clrotulo->label("codigo");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c90_estrutsistema");
$clrotulo->label("c64_descr");

$clrotulo->label("db83_sequencial");
$clrotulo->label("db89_db_bancos");
$clrotulo->label("db89_codagencia");
$clrotulo->label("db89_digito");
$clrotulo->label("db83_conta");
$clrotulo->label("db83_dvconta");
$clrotulo->label("db83_identificador");
$clrotulo->label("db83_codigooperacao");
$clrotulo->label("db83_tipoconta");

?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" cellspacing="0" cellpadding="0">
		  <tr>
			  <td>
			    <fieldset>
				    <table  cellspacing="0" cellpadding="0" >
					    <tr>
					      <td nowrap title="<?=@$Tc60_codcon?>"><?=@$Lc60_codcon?></td>
					      <td>
					        <? 
					          db_input('c60_codcon',6,$Ic60_codcon,true,'text',3); 
					        ?>
					      </td>
					    </tr>
					    <?
					
					      if($db_opcao!=1){ 
						      $clestrutura_sistema->db_opcao = 3;
						    }
						    	 
						    $clestrutura_sistema->autocompletar = true;
						    $clestrutura_sistema->size          = 30;
						    $clestrutura_sistema->botao         = true;
						    $clestrutura_sistema->reload        = true ;
						    $clestrutura_sistema->estrutura_sistema('c90_estrutcontabil');
						       
					    ?>	 
							<tr>
							  <td nowrap title="<?=@$Tc60_descr?>"><?=@$Lc60_descr?></td>
							  <td>
							    <? 
							      db_input('c60_descr',52,$Ic60_descr,true,'text',$db_opcao,"onFocus='js_configsistema();'");
							    ?>
							  </td>
							</tr>
							<tr>
							  <td nowrap title="<?=@$Tc60_finali?>" valign='top'><?=@$Lc60_finali?></td>
							  <td>
							    <? 
							      db_textarea('c60_finali',0,50,$Ic60_finali,true,'text',$db_opcao,"") 
							    ?>
							  </td>
							</tr>
							<tr>
							  <td nowrap title="<?=@$Tc60_codsis?>"><? db_ancora(@$Lc60_codsis,"js_pesquisac60_codsis(true);",$db_opcao);?></td>
							  <td>
							    <? 
						        db_input('c60_codsis',4,$Ic60_codsis,true,'text',$db_opcao," onchange='js_pesquisac60_codsis(false);'");  
						        db_input('c52_descr',46,@$Ic52_descr,true,'text',3,'');   
						      ?>
							  </td>
							</tr>
							<tr>
							  <td nowrap title="<?=@$Tc60_codcla?>">
						      <?
							      db_ancora(@$Lc60_codcla,"js_pesquisac60_codcla(true);",$db_opcao);
							    ?>
							  </td>
							  <td> 
						      <?
						        db_input('c60_codcla',4,$Ic60_codcla,true,'text',$db_opcao," onchange='js_pesquisac60_codcla(false);'");
						        db_input('c51_descr',46,@$Ic51_descr,true,'text',3,'');
							    ?>
							  </td>
							</tr>
							<tr>
							  <td nowrap title="Tipo de conta: Analitica ou Sintética">
							    <b>Tipo de conta:</b>
						  	</td>
							  <td>
							    <? 
						        if (!isset($tipo)){
							        $tipo ="sintetica";
						        }
						        
						        $mtr = array( "analitica"=>"Analitica",
						                      "sintetica"=>"Sintética");
							      
						        if (isset($bloqueada) && $bloqueada=='true'){
							        db_select("tipo",$mtr,true,3);
							      } else {	
							        db_select("tipo",$mtr,false,$db_opcao);
							      }
							         
						      ?>
							  </td> 
						  </tr>  
					  </table>	
			    </fieldset>
			  </td>	
		  </tr>
		<? 
		  if (isset($c60_codsis) && @$c60_codsis == 6){
		    $mostrar = "visible";
		  } else {
		    $mostrar = "hidden";
		  }
		  
		  if ( $lContaBancaria ) {
		  	
		?>    
		  <tr>   
		    <td  align="center">
		      <div id="dados_banco" style="visibility:<?=$mostrar?>;">
			      <fieldset>
			        <legend align='left'>
			          <b>Dados da Conta Bancária </b>
			        </legend>
			        <table>



			          <tr>
                  <td>
                    <b>Sequencial:</b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input('db83_sequencial',10,$Idb83_sequencial,true,'text',3,'');
                    ?>
                  </td>			            
			          </tr>
			          <tr>







			          <tr>
                  <td>
                    <b>Banco:</b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input('db89_db_bancos',10,$Idb89_db_bancos,true,'text',3,'');
                      db_input('db90_descr',40,'',true,'text',3,'');
                    ?>
                  </td>			            
			          </tr>
			          <tr>
			            <td>
                    <?
                      db_ancora('<b>Código Agência:</b>',"js_pesquisaAgencia(true);",$db_opcao,"");                     
                    ?>
			            </td>
                  <td>
                    <?
                      db_input('db89_codagencia',10,$Idb89_codagencia,true,'text',$db_opcao,"onChange='js_pesquisaAgencia(false);'");
                      db_input('db83_bancoagencia',10,'',true,'hidden',3);
                    ?>
                  </td>			            
                  <td>
                    <b>DV Agência:</b>
                  </td>
                  <td>
                    <?
                      db_input('db89_digito',5,$Idb89_digito,true,'text',3,'');
                    ?>
                  </td>                  
			          </tr>
                <tr>
                  <td>
                    <?
                      db_ancora('<b>Conta Bancária:</b>','js_pesquisaContaBancaria(true)',$db_opcao,"");                     
                    ?>
                  </td>
                  <td>
                    <?
                      db_input('db83_conta',10,$Idb83_conta,true,'text',$db_opcao,"onChange='js_pesquisaContaBancaria(false)'");
                      db_input('c56_contabancaria',10,'',true,'hidden',1);
                    ?>
                  </td>                 
                  <td>
                    <b>DV Conta:</b>
                  </td>
                  <td>
                    <?
                      db_input('db83_dvconta',5,$Idb83_dvconta,true,'text',3,'');
                    ?>
                  </td>                  
                </tr>			          
                <tr>
                  <td>
                    <b>Identificador (CNPJ)</b>
                  </td>
                  <td colspan="4">
                    <?
                      db_input('db83_identificador',54,$Idb83_identificador,true,'text',3,'');
                    ?>
                  </td>                 
                </tr>
                <tr>
                  <td>
                    <b>Código da Operação:</b>                     
                  </td>
                  <td>
                    <?
                      db_input('db83_codigooperacao',10,$Idb83_codigooperacao,true,'text',3,'');
                    ?>
                  </td>                 
                  <td>
                    <b>Tipo da Conta:</b>
                  </td>
                  <td>
                    <?
                    
                      if ( isset($db83_tipoconta) && trim($db83_tipoconta) != '' ) {
                      	if ( $db83_tipoconta == 1 ) {
                      		$db83_tipocontadescr = 'Conta Corrente'; 
                      	} else if ( $db83_tipoconta == 2 ) {
                      		$db83_tipocontadescr = 'Conta Poupança';
                      	}
                      }
                      
                      db_input('db83_tipoconta',10,'',true,'hidden',3,'');
                      db_input('db83_tipocontadescr',28,'',true,'text',3,'');
                      
                    ?>
                  </td>                  
                </tr>
			        </table>
		        </fieldset>
		      </div>
		    </td>	
		  </tr>
		  
		  <?
      
		  } else {
		  	
	  	?>
      <tr>   
        <td  align="center">
          <div id="dados_banco" style="visibility:<?=$mostrar?>;">
            <fieldset>
              <legend align='left'>
                <b>Dados da Conta Bancária </b>
              </legend>
              <table>
                <tr>
                  <td>
                    <b>Banco:</b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input('c63_banco',10,$Ic63_banco,true,'text',1,'');
                    ?>
                  </td>                 
                </tr>
                <tr>
                  <td>
                    <b>Código Agência:</b>                     
                  </td>
                  <td>
                    <?
                      db_input('c63_agencia',10,$Ic63_agencia,true,'text',1);
                    ?>
                  </td>                 
                  <td>
                    <b>DV Agência:</b>
                  </td>
                  <td>
                    <?
                      db_input('c63_dvagencia',5,$Ic63_dvagencia,true,'text',1);
                    ?>
                  </td>                  
                </tr>
                <tr>
                  <td>
                    <b>Conta Bancária:</b>                     
                  </td>
                  <td>
                    <?
                      db_input('c63_conta',10,$Ic63_conta,true,'text',1);
                    ?>
                  </td>                 
                  <td>
                    <b>DV Conta:</b>
                  </td>
                  <td>
                    <?
                      db_input('c63_dvconta',5,$Ic63_dvconta,true,'text',1);
                    ?>
                  </td>                  
                </tr>               
                <tr>
                  <td>
                    <b>Identificador (CNPJ)</b>
                  </td>
                  <td colspan="4">
                    <?
                      db_input('c63_identificador',54,$Ic63_identificador,true,'text',1);
                    ?>
                  </td>                 
                </tr>
                <tr>
                  <td>
                    <b>Código da Operação:</b>                     
                  </td>
                  <td>
                    <?
                      db_input('c63_codigooperacao',10,$Ic63_codigooperacao,true,'text',1);
                    ?>
                  </td>                 
                  <td>
                    <b>Tipo da Conta:</b>
                  </td>
                  <td>
                    <?
                      $aTipoConta = array( 1 => 'Conta Corrente',
                                           2 => 'Conta Poupança' );
                      db_select('c63_tipoconta',$aTipoConta,true,1);
                    ?>
                  </td>                  
                </tr>
              </table>
            </fieldset>
          </div>
        </td> 
      </tr>
	  	<?
		  	
		  }
		  
		  ?>	
		  <tr>
		    <td align='center'> 
		      <input 
		          name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
		          type="submit" id="db_opcao" 
		          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
		          <?=($db_botao==false?"disabled":"")?> >
		      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
		    </td>
		  </tr>	
		</table>
  </center>
</form>
<script>

oAutoComplete = new dbAutoComplete(document.form1.db83_conta,'con4_pesquisaconta.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('db83_conta'));
oAutoComplete.show();

oAutoComplete.setQueryStringFunction(
  function () {
    
    var sQuery  = 'string='+$F('db83_conta');
    
	  if ( document.form1.db83_bancoagencia.value != '' ) {
	    sQuery += '&bancoagencia='+document.form1.db83_bancoagencia.value; 
	  }    

    return sQuery;
    
  }
);


var lLimpaConta = true;

function js_configsistema(){
  var estrut = new String(document.form1.c90_estrutcontabil.value);
 
  if (estrut[0] == "3" || estrut[0] == "4"){
    document.form1.c60_codsis.value = "1";
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistema','func_consistema.php?pesquisa_chave='+document.form1.c60_codsis.value+'&funcao_js=parent.js_mostraconsistema','Pesquisa',false);
  } else {
    document.form1.c60_codsis.value = "";
    document.form1.c52_descr.value  = ""; 
  }
}

function js_divbanco(codsis){
  if (codsis == 6){
    document.getElementById("dados_banco").style.visibility = "visible";
  } else {
    document.getElementById("dados_banco").style.visibility = "hidden";
    document.form1.c63_banco.value         = "";
    document.form1.c63_agencia.value       = "";
    document.form1.c63_conta.value         = "";
    document.form1.c63_dvconta.value       = "";
    document.form1.c63_dvagencia.value     = "";
    document.form1.c63_identificador.value = "";   
  }
}

function js_pesquisac60_codsis(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistema','func_consistema.php?funcao_js=parent.js_mostraconsistema1|c52_codsis|c52_descr','Pesquisa',true,'0');
  } else {
    if(document.form1.c60_codsis.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistema','func_consistema.php?pesquisa_chave='+document.form1.c60_codsis.value+'&funcao_js=parent.js_mostraconsistema','Pesquisa',false);
    } else {
      document.form1.c52_descr.value = ''; 
    }
  }
}

function js_mostraconsistema(chave,erro){
  document.form1.c52_descr.value = chave; 
  if(erro==true){ 
    document.form1.c60_codsis.focus(); 
    document.form1.c60_codsis.value = ''; 
  }

  js_divbanco(document.form1.c60_codsis.value);
}

function js_mostraconsistema1(chave1,chave2){
  document.form1.c60_codsis.value = chave1;
  document.form1.c52_descr.value = chave2;
  db_iframe_consistema.hide();

  js_divbanco(document.form1.c60_codsis.value);
}

function js_pesquisac60_codcla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conclass','func_conclass.php?funcao_js=parent.js_mostraconclass1|c51_codcla|c51_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.c60_codcla.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conclass','func_conclass.php?pesquisa_chave='+document.form1.c60_codcla.value+'&funcao_js=parent.js_mostraconclass','Pesquisa',false);
     }else{
       document.form1.c51_descr.value = ''; 
     }
  }
}

function js_mostraconclass(chave,erro){
  document.form1.c51_descr.value = chave; 
  if(erro==true){ 
    document.form1.c60_codcla.focus(); 
    document.form1.c60_codcla.value = ''; 
  }
}

function js_mostraconclass1(chave1,chave2){
  document.form1.c60_codcla.value = chave1;
  document.form1.c51_descr.value = chave2;
  db_iframe_conclass.hide();
}


function js_pesquisaAgencia(mostra){

  lLimpaConta = true;
  
  if ( mostra ) {
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_agencia','func_bancoagenciaconta.php?funcao_js=parent.js_mostraAgencia1|db90_codban|db90_descr|db89_codagencia|db89_digito|db89_sequencial','Pesquisa Agência',true,'0');
  } else {
  
    if(document.form1.db89_codagencia.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_agencia','func_bancoagenciaconta.php?pesquisa_chave='+document.form1.db89_codagencia.value+'&funcao_js=parent.js_mostraAgencia','Pesquisa Agência',false);
    } else {
		  document.form1.db89_db_bancos.value    = '';
		  document.form1.db90_descr.value        = '';
		  document.form1.db89_codagencia.value   = '';
		  document.form1.db89_digito.value       = '';
		  document.form1.db83_bancoagencia.value = '';    
    }
    
  }
  
}

function js_consultaBancoAgencia(iBancoAgencia){
  lLimpaConta = false;
  js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_agencia','func_bancoagenciaconta.php?pesquisaSeq=true&pesquisa_chave='+iBancoAgencia+'&funcao_js=parent.js_mostraAgencia','Pesquisa Agência',false);
}


function js_mostraAgencia(lErro,iCodBanco,sDescrBanco,iCodAgencia,iDigAgencia,iSequencial){
  
  if ( lLimpaConta ) {
	  js_limpaContaBancaria();
  }
  
  document.form1.db89_db_bancos.value    = iCodBanco;
  document.form1.db90_descr.value        = sDescrBanco;
  document.form1.db89_codagencia.value   = iCodAgencia;
  document.form1.db89_digito.value       = iDigAgencia;
  document.form1.db83_bancoagencia.value = iSequencial;
  
  
}

function js_mostraAgencia1(iCodBanco,sDescrBanco,iCodAgencia,iDigAgencia,iSequencial){
  
  document.form1.db89_db_bancos.value    = iCodBanco;
  document.form1.db90_descr.value        = sDescrBanco;
  document.form1.db89_codagencia.value   = iCodAgencia;
  document.form1.db89_digito.value       = iDigAgencia;
  document.form1.db83_bancoagencia.value = iSequencial;

  js_limpaContaBancaria();
  db_iframe_agencia.hide();
  
}

function js_pesquisaContaBancaria(mostra){
  
  if ( document.form1.db83_bancoagencia.value != '' ) {
    var sQuery = 'bancoagencia='+document.form1.db83_bancoagencia.value+'&'; 
  } else {
    var sQuery = '';
  }
  
  if ( mostra ) {
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_contabancaria','func_contabancaria.php?'+sQuery+'funcao_js=parent.js_mostraContaBancaria1|db83_conta|db83_dvconta|db83_identificador|db83_codigooperacao|db83_tipoconta|db83_bancoagencia|db83_sequencial','Pesquisa Conta Bancária',true,'0');
  } else {
    if(document.form1.db83_conta.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_contabancaria','func_contabancaria.php?'+sQuery+'pesquisa_chave='+document.form1.db83_conta.value+'&funcao_js=parent.js_mostraContaBancaria','Pesquisa Conta Bancária',false);
    } else {
      js_limpaContaBancaria();
    }
  }
  
}

function js_mostraContaBancaria(lErro,sConta,sDigConta,sIdentificador,sCodOperacao,iTipoConta,iBancoAgencia,iSequencial){
  
  document.form1.db83_conta.value          = sConta;
  document.form1.db83_dvconta.value        = sDigConta;
  document.form1.db83_identificador.value  = sIdentificador;
  document.form1.db83_codigooperacao.value = sCodOperacao;
  document.form1.db83_tipoconta.value      = js_getDescrTipoConta(iTipoConta);
  document.form1.c56_contabancaria.value   = iSequencial;
  document.form1.db83_sequencial.value     = iSequencial;
    
  if ( iBancoAgencia != '' ) {
    js_consultaBancoAgencia(iBancoAgencia);
  }
  
}

function js_mostraContaBancaria1(sConta,sDigConta,sIdentificador,sCodOperacao,iTipoConta,iBancoAgencia,iSequencial){

	document.form1.db83_conta.value          = sConta;
	document.form1.db83_dvconta.value        = sDigConta;
	document.form1.db83_identificador.value  = sIdentificador;
	document.form1.db83_codigooperacao.value = sCodOperacao;
	document.form1.db83_tipoconta.value      = js_getDescrTipoConta(iTipoConta);
	document.form1.c56_contabancaria.value   = iSequencial;
	document.form1.db83_sequencial.value     = iSequencial;
	
  db_iframe_contabancaria.hide();
  
  if ( document.form1.db83_bancoagencia.value == ''  ) {
	  js_consultaBancoAgencia(iBancoAgencia);
  }
  
}

function js_limpaContaBancaria(){
  
  document.form1.db83_conta.value          = '';
  document.form1.db83_dvconta.value        = '';
  document.form1.db83_identificador.value  = '';
  document.form1.db83_codigooperacao.value = '';
  document.form1.db83_tipoconta.value      = '';    
  document.form1.c56_contabancaria.value   = '';
  
}


function js_getDescrTipoConta(iTipo) {

  var sDescrTipo = '';
 
  if ( iTipo == 1 ) {
    sDescrTipo = 'Conta Corrente'; 
  } else if ( iTipo == 2 ) {
    sDescrTipo = 'Conta Poupança';
  }
  
  return sDescrTipo;

}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conplano','func_conplanogeral.php?funcao_js=parent.js_preenchepesquisa|c60_codcon','Pesquisa',true,'0');
}

function js_preenchepesquisa(chave){
  db_iframe_conplano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}

<?
 if(isset($atualizar) || isset($atualizar02)){
   if(isset($erro_msg)){
     echo "
      \n alert('$erro_msg'); \n
      \n document.form1.c90_estrutcontabil.focus();\n
      \n document.form1.c90_estrutcontabil.style.backgroundColor='#99A9AE';\n
     ";
  }else{
      echo "\n document.form1.c60_descr.focus();\n";
  } 
 } 
 if(isset($focar)){
  echo "\n document.form1.".$focar.".style.backgroundColor='#99A9AE'; \n";
  echo "\n document.form1.$focar.focus();  \n";
 }    
 if(isset($chavepesquisa)){
   if($c90_estrutcontabil!=""){
     echo "\n  js_mascara03_c90_estrutcontabil('$c90_estrutcontabil'); \n";
   }
   if (isset($c90_estrutsistema)){
      if($tipo=="analitica" && $c90_estrutsistema!=''){
         echo "\n  js_mascara03_c90_estrutsistema('$c90_estrutsistema'); \n";
      }  
   }
 }  
?>

function js_validaBanco() {

  if (document.getElementById('c63_banco').value != '104') {

    document.getElementById('c63_codigooperacao').value    = '';
    document.getElementById('c63_codigooperacao').disabled = true;
    document.getElementById('c63_codigooperacao').style.backgroundColor='';
    document.getElementById('c63_tipoconta').disabled      = true;
    document.getElementById('c63_tipoconta').value         = 1;
    
  } else {
    
    document.getElementById('c63_codigooperacao').disabled = false;
    document.getElementById('c63_codigooperacao').style.backgroundColor='#E6E4F1';
    document.getElementById('c63_tipoconta').disabled      = false;
    
  }
  
  
}

js_validaBanco();
$('c63_banco').observe('change', js_validaBanco);
</script>