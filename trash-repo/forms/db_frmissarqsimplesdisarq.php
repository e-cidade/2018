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

//MODULO: issqn
$clissarqsimplesdisarq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q17_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq43_sequencial?>">
       <?=@$Lq43_sequencial?>
    </td>
    <td> 
<?
db_input('q43_sequencial',8,$Iq43_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq43_issarqsimples?>">
       <?
       db_ancora(@$Lq43_issarqsimples,"js_pesquisaq43_issarqsimples(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q43_issarqsimples',8,$Iq43_issarqsimples,true,'text',$db_opcao," onchange='js_pesquisaq43_issarqsimples(false);'")
?>
       <?
db_input('q17_data',10,$Iq17_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq43_disarq?>">
       <?=@$Lq43_disarq?>
    </td>
    <td> 
<?
db_input('q43_disarq',6,$Iq43_disarq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq43_issarqsimples(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_data','Pesquisa',true);
  }else{
     if(document.form1.q43_issarqsimples.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?pesquisa_chave='+document.form1.q43_issarqsimples.value+'&funcao_js=parent.js_mostraissarqsimples','Pesquisa',false);
     }else{
       document.form1.q17_data.value = ''; 
     }
  }
}
function js_mostraissarqsimples(chave,erro){
  document.form1.q17_data.value = chave; 
  if(erro==true){ 
    document.form1.q43_issarqsimples.focus(); 
    document.form1.q43_issarqsimples.value = ''; 
  }
}
function js_mostraissarqsimples1(chave1,chave2){
  document.form1.q43_issarqsimples.value = chave1;
  document.form1.q17_data.value = chave2;
  db_iframe_issarqsimples.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesdisarq','func_issarqsimplesdisarq.php?funcao_js=parent.js_preenchepesquisa|q43_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimplesdisarq.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>