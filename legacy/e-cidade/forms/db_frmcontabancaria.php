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

//MODULO: Configuracoes
$clcontabancaria->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db89_codagencia");
?>
<script>

  function js_functionVerificaIdentificador(){

    var iIdentificador = document.getElementById('db83_identificador').value;
    
    if (iIdentificador.length < 11 ) {

      alert("Campo identificador(CNPJ) inválido.");
      return false; 
    }

    return true;
  }

</script>
<form name="form1" method="post" action="" onsubmit="return js_functionVerificaIdentificador();">
<center>
<fieldset>
  <legend>
    <b>Cadastro de Conta Bancária</b>
  </legend>
	<table border="0">
	  <tr>
	    <td nowrap title="<?=@$Tdb83_descricao?>">
	      <?=@$Ldb83_descricao?>
	    </td>
	    <td>
				<?
				  db_input('db83_sequencial',10,$Idb83_sequencial,true,'text',3,"");
				  db_input('db83_descricao',50,$Idb83_descricao,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb83_bancoagencia?>">
	      <?
	        db_ancora(@$Ldb83_bancoagencia,"js_pesquisadb83_bancoagencia(true);",$db_opcao);
	      ?>
	    </td>
	    <td>
				<?
				  db_input('db83_bancoagencia',10,$Idb83_bancoagencia,true,'text',$db_opcao," onchange='js_pesquisadb83_bancoagencia(false);'");
	  			db_input('db89_codagencia'  ,10,$Idb89_codagencia,true,'text',3,'');
	  			db_input('db89_digito'      ,1 ,'',true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb83_conta?>">
	      <?=@$Ldb83_conta?>
	    </td>
	    <td>
				<?
				  db_input('db83_conta',15,$Idb83_conta,true,'text',$db_opcao,"");
				  db_input('db83_dvconta',1,$Idb83_dvconta,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb83_identificador?>">
	      <?=@$Ldb83_identificador?>
	    </td>
	    <td>
				<?
				  db_input('db83_identificador',15, 1,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb83_codigooperacao?>">
	      <?=@$Ldb83_codigooperacao?>
	    </td>
	    <td>
				<?
				  db_input('db83_codigooperacao',4,$Idb83_codigooperacao,true,'text',$db_opcao,"");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tdb83_tipoconta?>">
	      <?=@$Ldb83_tipoconta?>
	    </td>
	    <td>
				<?
				  $x = array('1'=>'Conta Corrente','2'=>'Conta Poupanca', '3'=>'Conta Aplicacao');
				  db_select('db83_tipoconta',$x,true,$db_opcao,"style='width: 150px;'");
				?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?php echo $Tdb83_contaplano?>">
	      <?php echo $Ldb83_contaplano?>
	    </td>
	    <td>
				<?php
				  $aContaPlano = array('t' => 'Sim', 'f' => 'Não');
				  db_select('db83_contaplano', $aContaPlano, true, $db_opcao,"style='width: 150px;'");
				?>
	    </td>
	  </tr>
  </table>
</fieldset>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>


function js_pesquisadb83_bancoagencia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bancoagencia','func_bancoagencia.php?digito=true&funcao_js=parent.js_mostrabancoagencia1|db89_sequencial|db89_codagencia|db89_digito','Pesquisa',true);
  }else{
     if(document.form1.db83_bancoagencia.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_bancoagencia','func_bancoagencia.php?digito=true&pesquisa_chave='+document.form1.db83_bancoagencia.value+'&funcao_js=parent.js_mostrabancoagencia','Pesquisa',false);
     }else{
       document.form1.db89_codagencia.value = '';
     }
  }
}

function js_mostrabancoagencia(chave,chave1,erro){

  document.form1.db89_codagencia.value   = chave;
  document.form1.db89_digito.value       = chave1;
  document.form1.db83_bancoagencia.value = '';

}

function js_mostrabancoagencia1(chave1,chave2,chave3){

  document.form1.db83_bancoagencia.value = chave1;
  document.form1.db89_codagencia.value   = chave2;
  document.form1.db89_digito.value       = chave3;

  db_iframe_bancoagencia.hide();

}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancariacadastro.php?funcao_js=parent.js_preenchepesquisa|db83_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_contabancaria.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>