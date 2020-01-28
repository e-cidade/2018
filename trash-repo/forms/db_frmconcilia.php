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

//MODULO: caixa
$clconcilia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k13_descr");
$clrotulo->label("k95_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk68_sequencial?>">
       <?=@$Lk68_sequencial?>
    </td>
    <td> 
<?
db_input('k68_sequencial',10,$Ik68_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk68_data?>">
       <?=@$Lk68_data?>
    </td>
    <td> 
<?
db_inputdata('k68_data',@$k68_data_dia,@$k68_data_mes,@$k68_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk68_saltes?>">
       <?
       db_ancora(@$Lk68_saltes,"js_pesquisak68_saltes(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k68_saltes',5,$Ik68_saltes,true,'text',$db_opcao," onchange='js_pesquisak68_saltes(false);'")
?>
       <?
db_input('k13_descr',40,$Ik13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk68_saldoextrato?>">
       <?=@$Lk68_saldoextrato?>
    </td>
    <td> 
<?
db_input('k68_saldoextrato',10,$Ik68_saldoextrato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk68_saldocorrente?>">
       <?=@$Lk68_saldocorrente?>
    </td>
    <td> 
<?
db_input('k68_saldocorrente',10,$Ik68_saldocorrente,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk68_conciliastatus?>">
       <?
       db_ancora(@$Lk68_conciliastatus,"js_pesquisak68_conciliastatus(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k68_conciliastatus',10,$Ik68_conciliastatus,true,'text',$db_opcao," onchange='js_pesquisak68_conciliastatus(false);'")
?>
       <?
db_input('k95_descr',40,$Ik95_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak68_saltes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.k68_saltes.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k68_saltes.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k68_saltes.focus(); 
    document.form1.k68_saltes.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k68_saltes.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}
function js_pesquisak68_conciliastatus(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliastatus','func_conciliastatus.php?funcao_js=parent.js_mostraconciliastatus1|k95_sequencial|k95_descr','Pesquisa',true);
  }else{
     if(document.form1.k68_conciliastatus.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliastatus','func_conciliastatus.php?pesquisa_chave='+document.form1.k68_conciliastatus.value+'&funcao_js=parent.js_mostraconciliastatus','Pesquisa',false);
     }else{
       document.form1.k95_descr.value = ''; 
     }
  }
}
function js_mostraconciliastatus(chave,erro){
  document.form1.k95_descr.value = chave; 
  if(erro==true){ 
    document.form1.k68_conciliastatus.focus(); 
    document.form1.k68_conciliastatus.value = ''; 
  }
}
function js_mostraconciliastatus1(chave1,chave2){
  document.form1.k68_conciliastatus.value = chave1;
  document.form1.k95_descr.value = chave2;
  db_iframe_conciliastatus.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?funcao_js=parent.js_preenchepesquisa|k68_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_concilia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>