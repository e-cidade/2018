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
$clissarqsimplesregdisbanco->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q23_issarqsimples");
$clrotulo->label("k00_numbco");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq44_sequencial?>">
       <?=@$Lq44_sequencial?>
    </td>
    <td> 
<?
db_input('q44_sequencial',8,$Iq44_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq44_issarqsimplesreg?>">
       <?
       db_ancora(@$Lq44_issarqsimplesreg,"js_pesquisaq44_issarqsimplesreg(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q44_issarqsimplesreg',8,$Iq44_issarqsimplesreg,true,'text',$db_opcao," onchange='js_pesquisaq44_issarqsimplesreg(false);'")
?>
       <?
db_input('q23_issarqsimples',8,$Iq23_issarqsimples,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq44_disbanco?>">
       <?
       db_ancora(@$Lq44_disbanco,"js_pesquisaq44_disbanco(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q44_disbanco',6,$Iq44_disbanco,true,'text',$db_opcao," onchange='js_pesquisaq44_disbanco(false);'")
?>
       <?
db_input('k00_numbco',15,$Ik00_numbco,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq44_issarqsimplesreg(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesreg','func_issarqsimplesreg.php?funcao_js=parent.js_mostraissarqsimplesreg1|q23_sequencial|q23_issarqsimples','Pesquisa',true);
  }else{
     if(document.form1.q44_issarqsimplesreg.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesreg','func_issarqsimplesreg.php?pesquisa_chave='+document.form1.q44_issarqsimplesreg.value+'&funcao_js=parent.js_mostraissarqsimplesreg','Pesquisa',false);
     }else{
       document.form1.q23_issarqsimples.value = ''; 
     }
  }
}
function js_mostraissarqsimplesreg(chave,erro){
  document.form1.q23_issarqsimples.value = chave; 
  if(erro==true){ 
    document.form1.q44_issarqsimplesreg.focus(); 
    document.form1.q44_issarqsimplesreg.value = ''; 
  }
}
function js_mostraissarqsimplesreg1(chave1,chave2){
  document.form1.q44_issarqsimplesreg.value = chave1;
  document.form1.q23_issarqsimples.value = chave2;
  db_iframe_issarqsimplesreg.hide();
}
function js_pesquisaq44_disbanco(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disbanco','func_disbanco.php?funcao_js=parent.js_mostradisbanco1|idret|k00_numbco','Pesquisa',true);
  }else{
     if(document.form1.q44_disbanco.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disbanco','func_disbanco.php?pesquisa_chave='+document.form1.q44_disbanco.value+'&funcao_js=parent.js_mostradisbanco','Pesquisa',false);
     }else{
       document.form1.k00_numbco.value = ''; 
     }
  }
}
function js_mostradisbanco(chave,erro){
  document.form1.k00_numbco.value = chave; 
  if(erro==true){ 
    document.form1.q44_disbanco.focus(); 
    document.form1.q44_disbanco.value = ''; 
  }
}
function js_mostradisbanco1(chave1,chave2){
  document.form1.q44_disbanco.value = chave1;
  document.form1.k00_numbco.value = chave2;
  db_iframe_disbanco.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimplesregdisbanco','func_issarqsimplesregdisbanco.php?funcao_js=parent.js_preenchepesquisa|q44_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimplesregdisbanco.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>