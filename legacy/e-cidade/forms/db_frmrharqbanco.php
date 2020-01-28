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

//MODULO: pessoal
$clrharqbanco->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db90_descr");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <form name="form1" method="post" action="">
     <fieldset style="width: 800px;">
      <legend>Arquivo Bancario</legend>
      <table border=0 width="100%">
        <tr>
          <td nowrap title="<?=@$Trh34_codarq?>" width="35%">
            <?=@$Lrh34_codarq?>
          </td>
          <td width="65%"> 
            <? db_input('rh34_codarq',10,$Irh34_codarq,true,'text',3,"") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh34_descr?>">
            <?=@$Lrh34_descr?>
          </td>
          <td> 
            <? db_input('rh34_descr',50,$Irh34_descr,true,'text',$db_opcao,"style='width:370px'") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh34_where?>">
            <?=@$Lrh34_where?>
          </td>
          <td>
            <? db_input('rh34_where',50,$Irh34_where,true,'text',$db_opcao,"style='width:370px;background-color:#E6E4F1;'") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh34_codban?>">
            <? db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",$db_opcao); ?>
          </td>
          <td> 
            <?
              db_input('rh34_codban',10,$Irh34_codban,true,'text',$db_opcao," onchange='js_pesquisarh34_codban(false);'");
              db_input('db90_descr',36,$Idb90_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh34_sequencial?>">
            <?=@$Lrh34_sequencial?>
          </td>
          <td> 
            <? db_input('rh34_sequencial',10,$Irh34_sequencial,true,'text',$db_opcao,"") ?>
          </td>
        </tr>
        <tr>  
          <td nowrap title="<?=@$Trh34_ativo?>">
            <?=@$Lrh34_ativo?>
          </td>
          <td> 
            <?
              if (!isset($rh34_ativo)) {
      	        $rh34_ativo = "t";
              }
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('rh34_ativo',$x,true,$db_opcao,"style='width: 80px;'");
            ?>
          </td>
        </tr>    
        <tr>
          <td colspan=2>
            <fieldset>
              <legend> Dados Bancários </legend>
              <table width="100%">
                <tr>
                  <td nowrap title="<?=@$Trh34_agencia?>" width="35%">
                    <?=@$Lrh34_agencia?>
                  </td>
                  <td width="25%"> 
                    <? db_input('rh34_agencia',8,$Irh34_agencia,true,'text',$db_opcao,"") ?>
                  </td>
                  <td nowrap title="<?=@$Trh34_dvagencia?>" align="right" width="16%">
                    <?=@$Lrh34_dvagencia?>
                  </td>
                  <td width="24%"> 
                    <? db_input('rh34_dvagencia',4,$Irh34_dvagencia,true,'text',$db_opcao,"") ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Trh34_conta?>">
                    <?=@$Lrh34_conta?>
                  </td>
                  <td> 
                    <? db_input('rh34_conta',20,$Irh34_conta,true,'text',$db_opcao,"") ?>
                  </td>
                  <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
                    <?=@$Lrh34_dvconta?>
                  </td>
                  <td> 
                    <? db_input('rh34_dvconta',4,$Irh34_dvconta,true,'text',$db_opcao,"") ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Trh34_convenio?>">
                    <?=@$Lrh34_convenio?>
                  </td>
                  <td colspan=2> 
                    <? db_input('rh34_convenio',20,$Irh34_convenio,true,'text',$db_opcao,"") ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr id="dadosCEF" style="display: none;">
         <td colspan=2>
           <fieldset>
             <legend> Dados para geração de Arquivos</legend>
             <table width="100%">
              <tr>
                <td nowrap title="<?=@$Trh34_parametrotransmissaoheader?>" width="35%">
                  <?=@$Lrh34_parametrotransmissaoheader?>
                </td>
                <td width="65%">
                  <? db_input('rh34_parametrotransmissaoheader',4,$Irh34_parametrotransmissaoheader,true,'text',$db_opcao,"") ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh34_parametrotransmissaolote?>">
                  <?=@$Lrh34_parametrotransmissaolote?>
                </td>
                <td>
                  <? db_input('rh34_parametrotransmissaolote',4,$Irh34_parametrotransmissaolote,true,'text',$db_opcao,"") ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh34_codigocompromisso?>">
                  <?=@$Lrh34_codigocompromisso?>
                </td>
                <td>
                  <? db_input('rh34_codigocompromisso',8,$Irh34_codigocompromisso,true,'text',$db_opcao,"") ?>
                </td>
              </tr>                    
             </table>
           </fieldset>
         </td>
        </tr> 
      </table>
     </fieldset>
     <br>
     
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
            type="submit" 
            id="db_opcao" 
            value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
            <?=($db_botao==false?"disabled":"")?> 
            onclick=" return js_submit();">
            
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </form>
  </td>
 </tr>
</table>
    

<script>
function js_pesquisarh34_codban(mostra){
	
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  } else {
	   
    if (document.form1.rh34_codban.value != '') { 
      js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    } else {
      document.form1.db90_descr.value = ''; 
      js_mostraDadosCEF();
    }
    
  }
  
}

function js_mostradb_bancos(chave,erro) {
  document.form1.db90_descr.value = chave;
   
  if(erro==true){ 
    document.form1.rh34_codban.focus(); 
    document.form1.rh34_codban.value = ''; 
  }

  js_mostraDadosCEF();
  
}

function js_mostradb_bancos1(chave1,chave2) {
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  js_mostraDadosCEF();
  db_iframe_db_bancos.hide();
}

function js_mostraDadosCEF() {
	if ($F('rh34_codban') == '104') {
		$('dadosCEF').style.display = '';
	} else {
		$('dadosCEF').style.display                = 'none';
    $('rh34_parametrotransmissaoheader').value = '';
    $('rh34_parametrotransmissaolote').value   = '';
    $('rh34_codigocompromisso').value          = '';
	}		
}

function js_submit() {

	var MENSAGEM    = 'recursoshumanos/pessoal/db_frmrharqbanco.';
	var oRegex      = /^[0-9]+$/;
	
	/*
	 * Validações
	 */

	 if ( $F('rh34_sequencial') != "" && !oRegex.test( $F('rh34_sequencial') ) ) {

	   alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_sequencial?>'}) );
	   $('rh34_sequencial').value = '';
	   $('rh34_sequencial').focus();
	   return false;
	 }
	    
	 
	 if ( $F('rh34_agencia')!= "" && !oRegex.test( $F('rh34_agencia') ) ) {
		 alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_agencia?>'}) );
		 $('rh34_agencia').value = '';
		 $('rh34_agencia').focus();
		 return false;
	 }
		 
	 if ( $F('rh34_dvagencia') != "" && !oRegex.test( $F('rh34_dvagencia') ) ) {
		 alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_dvagencia?>'}) );
		 $('rh34_dvagencia').value = '';
		 $('rh34_dvagencia').focus();
		 return false;
	 }
	 
	 if ( $F('rh34_conta') != "" && !oRegex.test( $F('rh34_conta') ) ) {
		 alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_conta?>'}) );
		 $('rh34_conta').value = '';
	   return false;
	 }
	 
	 if ( $F('rh34_dvconta') != "" && !oRegex.test( $F('rh34_dvconta') ) ) {
		 alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_dvconta?>'}) );
		 $('rh34_dvconta').value = '';
		 $('rh34_dvconta').focus();
		 return false;
	 }
	 
	 if ( $F('rh34_convenio') != "" && !oRegex.test( $F('rh34_convenio') ) ) {
		 alert( _M( MENSAGEM + 'somente_numeros', {sCampo: '<?=@$LSrh34_convenio?>'}) );
		 $('rh34_convenio').value = '';
		 $('rh34_convenio').focus();
		 return false;
	 }

	 return true;
	  
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?funcao_js=parent.js_preenchepesquisa|rh34_codarq','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_rharqbanco.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>