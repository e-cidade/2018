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

//MODULO: cadastro
$claverbatipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j105_descr");

$opcaoAverba = $db_opcao;
if($db_opcao!= 1 and isset($j93_codigo)){
  //verifica se ja tem averbação para este tipo 
  $sqlAverba = "select * from averbacao where j75_tipo = $j93_codigo limit 2";
  $rsAverba = db_query($sqlAverba);
  $linhasAverba = pg_num_rows($rsAverba);
  if($linhasAverba > 0){
    // não pode alterar nem excluir esse tipo 
    $opcaoAverba = 3; 
  }
	   
}   
	   
?>
<form name="form1" method="post" action="">
  <fieldset style="width: 500px;">
  <legend class="bold"><?=($db_opcao==1?"Inclusão":($db_opcao==2||$db_opcao==22?"Alteração":"Exclusão"))?> de Tipo de Averbação</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tj93_codigo?>">
           <?=@$Lj93_codigo?>
        </td>
        <td> 
          <?
            db_input('j93_codigo',6,$Ij93_codigo,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj93_descr?>">
           <?=@$Lj93_descr?>
        </td>
        <td> 
          <?
            db_input('j93_descr',20,$Ij93_descr,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj93_regra?>">
           <?=@$Lj93_regra?>
        </td>
        <td> 
          <?
            $x = array('1'=>'Proprietario','2'=>'Promitente');
            db_select('j93_regra',$x,true,$opcaoAverba,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj93_datalimite?>">
           <?=@$Lj93_datalimite?>
        </td>
        <td> 
          <?
            db_inputdata('j93_datalimite',@$j93_datalimite_dia,@$j93_datalimite_mes,@$j93_datalimite_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj93_averbagrupo?>">
           <?
    	       db_ancora(@$Lj93_averbagrupo,"js_pesquisaj93_averbagrupo(true);",$opcaoAverba);
           ?>
        </td>
        <td> 
          <?
            db_input('j93_averbagrupo',10,$Ij93_averbagrupo,true,'text',$opcaoAverba," onchange='js_pesquisaj93_averbagrupo(false);'")
          ?>
           <?
            db_input('j105_descr',40,$Ij105_descr,true,'text',3,'')
           ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj93_averbagrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_averbagrupo','func_averbagrupo.php?funcao_js=parent.js_mostraaverbagrupo1|j105_sequencial|j105_descr','Pesquisa',true);
  }else{
     if(document.form1.j93_averbagrupo.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_averbagrupo','func_averbagrupo.php?pesquisa_chave='+document.form1.j93_averbagrupo.value+'&funcao_js=parent.js_mostraaverbagrupo','Pesquisa',false);
     }else{
       document.form1.j105_descr.value = ''; 
     }
  }
}
function js_mostraaverbagrupo(chave,erro){
  document.form1.j105_descr.value = chave; 
  if(erro==true){ 
    document.form1.j93_averbagrupo.focus(); 
    document.form1.j93_averbagrupo.value = ''; 
  }
}
function js_mostraaverbagrupo1(chave1,chave2){
  document.form1.j93_averbagrupo.value = chave1;
  document.form1.j105_descr.value = chave2;
  db_iframe_averbagrupo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_averbatipo','func_averbatipo.php?funcao_js=parent.js_preenchepesquisa|j93_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_averbatipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>