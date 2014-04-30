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

//MODULO: arrecadacao
$clregracompensacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k154_sequencial");
$clrotulo->label("k154_descricao");
$clrotulo->label("k00_descr");
$clrotulo->label("nomeinst");
?>
<style type="text/css">
form fieldset table tr td:first-child {
  width: 230px;
}
form fieldset table {
  width: 700px;
}

form fieldset select {
  width: 92px;
}
#k155_descricao {
  width: 439px;
}
  
</style>
<form name="form1" method="post" action="">
<fieldset>
  <legend><strong>Dados da Regra</strong></legend>
  <table>
  <tr>
    <td title="<?=@$Tk155_sequencial?>">
      <?=@$Lk155_sequencial?>
    </td>
    <td> 
    <?
      db_input('k155_sequencial',10,$Ik155_sequencial,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tk155_tiporegracompensacao?>">
    <?
      db_ancora(@$Lk155_tiporegracompensacao,"js_pesquisak155_tiporegracompensacao(true);",$db_opcao);
    ?>
    </td>
    <td> 
    <?
      db_input('k155_tiporegracompensacao',10,$Ik155_tiporegracompensacao,true,'text',$db_opcao," onchange='js_pesquisak155_tiporegracompensacao(false);'")
    ?> 
    <?
      db_input('k154_descricao',46,$Ik154_descricao,true,'text',3,'')
    ?>
    </td>
  </tr>
  
  <tr>
		<td nowrap title="<?=@$Tk155_descricao?>">
		  <?=@$Lk155_descricao?>
		</td>
		<td>
		<?
      db_input('k155_descricao',60,$Ik155_descricao,true,'text',$db_opcao,"")
		?>
		</td>
	</tr>
  <tr>
    <td nowrap title="<?=@$Tk155_percmaxuso?>">
       <?=@$Lk155_percmaxuso?>
       <strong>(%)</strong>
    </td>
    <td> 
    <?
      db_input('k155_percmaxuso',10,$Ik155_percmaxuso,true,'text',$db_opcao,"onblur='js_verificaPercentual(this)'")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk155_tempovalidade?>">
       <?=@$Lk155_tempovalidade?>
    </td>
    <td> 
    <?
      db_input('k155_tempovalidade',10,$Ik155_tempovalidade,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk155_automatica?>">
       <?=@$Lk155_automatica?>
    </td>
    <td> 
    <?
      $x = array("f"=>"NÃO","t"=>"SIM");
      db_select('k155_automatica',$x,true,$db_opcao,"");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk155_permitetransferencia?>">
       <?=@$Lk155_permitetransferencia?>
    </td>
    <td> 
    <?
      $x = array("f"=>"NÃO","t"=>"SIM");
      db_select('k155_permitetransferencia',$x,true,$db_opcao,"");
    ?>
    </td>
  </tr>
  </table>
</fieldset>

<fieldset>
  <legend><b>Origem do Débito</b></legend>
  <table>
		<tr>
			<td nowrap title="<?=@$Tk155_arretipoorigem?>">
			<?
			  db_ancora(@$Lk155_arretipoorigem,"js_pesquisak155_arretipoorigem(true);",$db_opcao);
			?>
			</td>
			<td>
			<?
			  db_input('k155_arretipoorigem',10,$Ik155_arretipoorigem,true,'text',$db_opcao," onchange='js_pesquisak155_arretipoorigem(false);'")
			?> 
			<?
			  db_input('k00_descr',46,$Ik00_descr,true,'text',3,'','k00_descricaoorigem')
			?>
			</td>
		</tr>

  </table>
</fieldset>

<fieldset>
  <legend><b>Destino do Crédito</b></legend>
  <table>
		<tr>
    <td nowrap title="<?=@$Tk155_arretipodestino?>">
       <?
         db_ancora(@$Lk155_arretipodestino, "js_pesquisak155_arretipodestino(true);", $db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('k155_arretipodestino',10,$Ik155_arretipodestino,true,'text',$db_opcao,"onchange='js_pesquisak155_arretipodestino(false)'");
       
        db_input('k00_descr',46,$Ik00_descr,true,'text',3,'','k00_descricaodestino');
      ?>
    </td>
  </tr>

  </table>
</fieldset>

<br>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_verificaPercentual(oInput) {
  
  if(oInput.value > 100) {
    alert('Valor percentual de uso não pode ultrapassar 100%.');
    oInput.value = '';
    return false;
  }
  
}
      
function js_pesquisak155_tiporegracompensacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiporegracompensacao','func_tiporegracompensacao.php?funcao_js=parent.js_mostratiporegracompensacao|k154_sequencial|k154_descricao','Pesquisa',true);
  }else{
     if(document.form1.k155_tiporegracompensacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiporegracompensacao','func_tiporegracompensacao.php?pesquisa_chave='+document.form1.k155_tiporegracompensacao.value+'&funcao_js=parent.js_mostratiporegracompensacaohide','Pesquisa',false);
     }else{
       document.form1.k154_sequencial.value = ''; 
     }
  }
}

function js_mostratiporegracompensacaohide(chave,erro){
  document.form1.k154_descricao.value = chave; 
  if(erro==true){ 
    document.form1.k155_tiporegracompensacao.focus(); 
    document.form1.k155_tiporegracompensacao.value = ''; 
  }
}

function js_mostratiporegracompensacao(chave1,chave2){
  document.form1.k155_tiporegracompensacao.value = chave1;
  document.form1.k154_descricao.value = chave2;
  db_iframe_tiporegracompensacao.hide();
}

function js_pesquisak155_arretipoorigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipoorigem|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.k155_arretipoorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k155_arretipoorigem.value+'&funcao_js=parent.js_mostraarretipoorigemhide','Pesquisa',false);
     }else{
       document.form1.k00_descricaoorigem.value = ''; 
     }
  }
}

function js_mostraarretipoorigemhide(chave,erro){
  document.form1.k00_descricaoorigem.value = chave; 
  if(erro==true){ 
    document.form1.k155_arretipoorigem.focus(); 
    document.form1.k155_arretipoorigem.value = ''; 
  }
}

function js_mostraarretipoorigem(chave1,chave2){
  document.form1.k155_arretipoorigem.value = chave1;
  document.form1.k00_descricaoorigem.value = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_regracompensacao','func_regracompensacao.php?funcao_js=parent.js_preenchepesquisa|k155_sequencial','Pesquisa',true);
}

function js_pesquisak155_arretipodestino(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipodestino|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.k155_arretipodestino.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k155_arretipodestino.value+'&funcao_js=parent.js_mostraarretipodestinohide','Pesquisa',false);
     }else{
       document.form1.k00_descricaodestino.value = ''; 
     }
  }
}

function js_mostraarretipodestinohide(chave,erro){
  document.form1.k00_descricaodestino.value = chave; 
  if(erro==true){ 
    document.form1.k155_arretipodestino.focus(); 
    document.form1.k155_arretipodestino.value = ''; 
  }
}

function js_mostraarretipodestino(chave1,chave2){

  document.form1.k155_arretipodestino.value = chave1;
  document.form1.k00_descricaodestino.value = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_regracompensacao','func_regracompensacao.php?funcao_js=parent.js_preenchepesquisa|k155_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_regracompensacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>