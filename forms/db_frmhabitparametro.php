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

//MODULO: Habitacao
$clhabitparametro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db101_descricao");
$clrotulo->label("k02_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend>
    <b>Parâmetros da Habitação</b>
  </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tht16_anousu?>">
	      <?=@$Lht16_anousu?>
	    </td>
	    <td> 
				<?
					$ht16_anousu = db_getsession('DB_anousu');
					db_input('ht16_anousu',10,$Iht16_anousu,true,'text',3,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tht16_avaliacao?>">
	      <?
	        db_ancora(@$Lht16_avaliacao,"js_pesquisaht16_avaliacao(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
				<?
				  db_input('ht16_avaliacao',10,$Iht16_avaliacao,true,'text',$db_opcao," onchange='js_pesquisaht16_avaliacao(false);'");
	        db_input('db101_descricao',40,$Idb101_descricao,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tht16_receitapadrao?>">
	      <?
	        db_ancora(@$Lht16_receitapadrao,"js_pesquisaht16_receitapadrao(true);",$db_opcao);
	      ?>
	    </td>
	    <td> 
				<?
				  db_input('ht16_receitapadrao',10,$Iht16_receitapadrao,true,'text',$db_opcao," onchange='js_pesquisaht16_receitapadrao(false);'");
	        db_input('k02_descr',40,$Ik02_descr,true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tht16_qtdparcelaspagamento?>">
	      <?=@$Lht16_qtdparcelaspagamento?>
	    </td>
	    <td> 
				<?
				  db_input('ht16_qtdparcelaspagamento',10,$Iht16_qtdparcelaspagamento,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tht16_diaspadraopagamento?>">
	      <?=@$Lht16_diaspadraopagamento?>
	    </td>
	    <td> 
				<?
				  db_input('ht16_diaspadraopagamento',10,$Iht16_diaspadraopagamento,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tht16_mesescarencia?>">
	      <?=@$Lht16_mesescarencia?>
	    </td>
	    <td> 
				<?
				  db_input('ht16_mesescarencia',10,$Iht16_mesescarencia,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
  </table>
</fieldset>
 <br>
<center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>">
</center>
</form>
<script>
function js_pesquisaht16_avaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_avaliacao','func_avaliacao.php?funcao_js=parent.js_mostraavaliacao1|db101_sequencial|db101_descricao','Pesquisa',true);
  }else{
     if(document.form1.ht16_avaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_avaliacao','func_avaliacao.php?pesquisa_chave='+document.form1.ht16_avaliacao.value+'&funcao_js=parent.js_mostraavaliacao','Pesquisa',false);
     }else{
       document.form1.db101_descricao.value = ''; 
     }
  }
}
function js_mostraavaliacao(chave,erro){
  document.form1.db101_descricao.value = chave; 
  if(erro==true){ 
    document.form1.ht16_avaliacao.focus(); 
    document.form1.ht16_avaliacao.value = ''; 
  }
}
function js_mostraavaliacao1(chave1,chave2){
  document.form1.ht16_avaliacao.value = chave1;
  document.form1.db101_descricao.value = chave2;
  db_iframe_avaliacao.hide();
}
function js_pesquisaht16_receitapadrao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.ht16_receitapadrao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.ht16_receitapadrao.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.ht16_receitapadrao.focus(); 
    document.form1.ht16_receitapadrao.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.ht16_receitapadrao.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_habitparametro','func_habitparametro.php?funcao_js=parent.js_preenchepesquisa|ht16_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_habitparametro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>