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

//MODULO: orcamento
$clorccenarioeconomicoparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o02_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Cenário Macroeconômicos</legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$To03_sequencial?>">
       <?=@$Lo03_sequencial?>
    </td>
    <td> 
<?
db_input('o03_sequencial',10,$Io03_sequencial,true,'text', 3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_orccenarioeconomico?>">
       <?
       db_ancora("<b>Indicador Macroeconômico:</b>","js_pesquisao03_orccenarioeconomico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o03_orccenarioeconomico',10,$Io03_orccenarioeconomico,true,'text',$db_opcao," onchange='js_pesquisao03_orccenarioeconomico(false);'")
?>
       <?
db_input('o02_descricao',40,$Io02_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_anoorcamento?>">
       <?=@$Lo03_anoorcamento?>
    </td>
    <td> 
<?
if (!isset($o03_anoorcamento)) {
  $o03_anoorcamento = db_getsession("DB_anousu") + 1;
}
db_input('o03_anoorcamento',4,$Io03_anoorcamento,true,'text', 3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_anoreferencia?>">
     <?
     if ($db_opcao == 1) { 
      echo "<b>Ano Final:</b>";
     } else {
       echo @$Lo03_anoreferencia;
     }
     ?>  
    </td>
    <td> 
<?
db_input('o03_anoreferencia',4,$Io03_anoreferencia,true,'text',$db_opcao,"");
if ($db_opcao == 1) {
  echo "<b>*</b>Será Incluindo os parâmetros ate o ano final informado";
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_descricao?>">
       <?=@$Lo03_descricao?>
    </td>
    <td> 
<?
db_input('o03_descricao',40,$Io03_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_tipovalor?>">
       <?=@$Lo03_tipovalor?>
    </td>
    <td> 
<?
$x = array('1'=>'Percentual','2'=>'Quantidade');
db_select('o03_tipovalor',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_valorparam?>">
       <?=@$Lo03_valorparam?>
    </td>
    <td> 
<?
db_input('o03_valorparam',10,$Io03_valorparam,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To03_fonte?>">
       <?=@$Lo03_fonte?>
    </td>
    <td> 
<?
db_textarea('o03_fonte', 5, 40, $Io03_fonte,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisao03_orccenarioeconomico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orccenarioeconomico','func_orccenarioeconomico.php?funcao_js=parent.js_mostraorccenarioeconomico1|o02_sequencial|o02_descricao','Pesquisa',true);
  }else{
     if(document.form1.o03_orccenarioeconomico.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orccenarioeconomico','func_orccenarioeconomico.php?pesquisa_chave='+document.form1.o03_orccenarioeconomico.value+'&funcao_js=parent.js_mostraorccenarioeconomico','Pesquisa',false);
     }else{
       document.form1.o02_descricao.value = ''; 
     }
  }
}
function js_mostraorccenarioeconomico(chave,erro){
  document.form1.o02_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o03_orccenarioeconomico.focus(); 
    document.form1.o03_orccenarioeconomico.value = ''; 
  }
}
function js_mostraorccenarioeconomico1(chave1,chave2){
  document.form1.o03_orccenarioeconomico.value = chave1;
  document.form1.o02_descricao.value = chave2;
  db_iframe_orccenarioeconomico.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orccenarioeconomicoparam','func_orccenarioeconomicoparam.php?funcao_js=parent.js_preenchepesquisa|o03_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orccenarioeconomicoparam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>