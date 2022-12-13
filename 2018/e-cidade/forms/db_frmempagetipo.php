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

//MODULO: empenho
$clempagetipo->rotulo->label();
$sDisplayCompromisso= "none";
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Cadastro de Conta Pagadora</b></legend>
<table border="0" width="690">
  <tr>
    <td nowrap title="<?=@$Te83_codtipo?>">
      <?=@$Le83_codtipo?>
    </td>
    <td> 
			<?
			  db_input('e83_codtipo',10,$Ie83_codtipo,true,'text',3);
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te83_descr?>">
       <?=@$Le83_descr?>
    </td>
    <td> 
			<?
			 if (isset($e83_conta) && $e83_conta != '') {
			
			   $sSqlConta = $clempagetipo->sql_query_conplanoconta(null,
			                                                  "c63_banco||'-'||c63_agencia||'-'||c63_conta as e83_descricao,c63_banco", 
			                                                  "",
			                                                  " c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=$e83_conta ");
			   $result = $clempagetipo->sql_record($sSqlConta);
			   if($clempagetipo->numrows> 0 ) {
			     db_fieldsmemory($result,0);
			     if ($e83_descr == '') {
			        
			       $e83_descr = $e83_descricao;
			     }
			     if (trim($c63_banco) == 104) {
			       $sDisplayCompromisso = "normal"; 
			     }
			   }
			 }
			 
			db_input('e83_descr',60,$Ie83_descr,true,'text',$db_opcao,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te83_conta?>">
       <?
         db_ancora(@$Le83_conta,"js_saltes(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('e83_conta',10,$Ie83_conta,true,'text',$db_opcao,"onchange='js_saltes(false);'")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te83_codmod?>">
       <?
         db_ancora(@$Le83_codmod,"js_mod(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('e83_codmod',10,$Ie83_codmod,true,'text',$db_opcao,"onchange='js_mod(false);'")
			?>
    </td>
  </tr>
  <tr style='display: <?=$sDisplayCompromisso?>'>
    <td nowrap title="<?=@$Te83_codigocompromisso?>">
       <?=@$Le83_codigocompromisso?>
    </td>
    <td> 
		  <?
		    db_input('e83_codigocompromisso',4,$Ie83_codigocompromisso,true,'text',$db_opcao,"")
		  ?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Te83_convenio?>">
       <?=@$Le83_convenio?>
    </td>
    <td> 
			<?
			  db_input('e83_convenio',10,$Ie83_convenio,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te83_sequencia?>">
      <b>Seq. Cheque:</b>
    </td>
    <td> 
			<?
			  db_input('e83_sequencia',10,$Ie83_sequencia,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  </table>
  </fieldset>
<br>
<center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>
function js_mod(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_empagemod','func_empagemod.php?funcao_js=parent.js_mostramod1|e84_codmod','Pesquisa',true);
  }else{
     if(document.form1.e83_codmod.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_empagemod','func_empagemod.php?pesquisa_chave='+document.form1.e83_codmod.value+'&funcao_js=parent.js_mostramod','Pesquisa',false);
     }else{
       document.form1.e83_codmod.value = ''; 
     }
  }
}
function js_mostramod(chave,erro){
  if(erro==true){ 
    document.form1.e83_codmod.focus(); 
    document.form1.e83_codmod.value = ''; 
  }
}
function js_mostramod1(chave1){
  document.form1.e83_codmod.value = chave1;
  db_iframe_empagemod.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empagetipo','func_empagetipo.php?funcao_js=parent.js_preenchepesquisa|e83_codtipo','Pesquisa',true);
}
function js_saltes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_saltes','func_saltes.php?lFiltroContaPagadora=true&funcao_js=parent.js_mostrasaltes1|k13_conta','Pesquisa',true);
  }else{
     if(document.form1.e83_conta.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.e83_conta.value+'&lFiltroContaPagadora=true&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.e83_conta.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  if(erro==true){ 
    document.form1.e83_conta.focus(); 
    document.form1.e83_conta.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostrasaltes1(chave1){
  document.form1.e83_conta.value = chave1;
  document.form1.submit();
  db_iframe_saltes.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empagetipo','func_empagetipo.php?funcao_js=parent.js_preenchepesquisa|e83_codtipo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empagetipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>