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
$clitbiformapagamento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it28_avista");
?>

<form name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend align="center">
        <b>Cadastro Forma de Pagamento</b>
      </legend>
      <table border="0">
        <tr>
    	  <td nowrap title="<?=@$Tit27_itbitipoformapag?>">
            <?
       		  db_ancora(@$Lit27_itbitipoformapag,"js_pesquisait27_itbitipoformapag(true);",$db_opcao);
       	    ?> 
    	  </td>
    	  <td> 
		    <?
  		      db_input('it27_sequencial',10,$Iit27_sequencial,true,'hidden',$db_opcao,"");
			  db_input('it27_itbitipoformapag',10,$Iit27_itbitipoformapag,true,'text',$db_opcao," onchange='js_pesquisait27_itbitipoformapag(false);'");
			  db_input('it28_descricao',40,$Iit28_avista,true,'text',3,'');
       	    ?>
    	  </td>
  	    </tr>
        <tr>
          <td nowrap title="<?=@$Tit27_descricao?>">
            <?=@$Lit27_descricao?>
          </td>
          <td> 
		    <?
			  db_input('it27_descricao',55,$Iit27_descricao,true,'text',$db_opcao,"");
		    ?>
    	  </td>
  	    </tr>
  	    <tr>
       	  <td nowrap title="<?=@$Tit27_tipo?>">
       	    <?=@$Lit27_tipo?>
    	  </td>
    	  <td> 
		    <?
		    
			  $aTipo = array('1'=>'ITBI Urbano',
			   		  		 '2'=>'ITBI Rural',
			   				 '3'=>'Todos');
			  
			  db_select('it27_tipo',$aTipo,true,$db_opcao,"");
		    ?>
          </td>
        </tr>
        <tr>
    	  <td nowrap title="<?=@$Tit27_aliquota?>">
       	    <?=@$Lit27_aliquota?>
    	  </td>
    	  <td> 
 		    <?
			  db_input('it27_aliquota',10,$Iit27_aliquota,true,'text',$db_opcao,"");
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
function js_pesquisait27_itbitipoformapag(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbitipoformapag','func_itbitipoformapag.php?funcao_js=parent.js_mostraitbitipoformapag1|it28_sequencial|it28_descricao','Pesquisa',true);
  }else{
     if(document.form1.it27_itbitipoformapag.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbitipoformapag','func_itbitipoformapag.php?pesquisa_chave='+document.form1.it27_itbitipoformapag.value+'&funcao_js=parent.js_mostraitbitipoformapag','Pesquisa',false);
     }else{
       document.form1.it28_descricao.value = ''; 
     }
  }
}
function js_mostraitbitipoformapag(chave,erro){
  document.form1.it28_descricao.value = chave; 
  if(erro==true){ 
    document.form1.it27_itbitipoformapag.focus(); 
    document.form1.it27_itbitipoformapag.value = ''; 
  }
}
function js_mostraitbitipoformapag1(chave1,chave2){
  document.form1.it27_itbitipoformapag.value = chave1;
  document.form1.it28_descricao.value = chave2;
  db_iframe_itbitipoformapag.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbiformapagamento','func_itbiformapagamento.php?funcao_js=parent.js_preenchepesquisa|it27_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbiformapagamento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>