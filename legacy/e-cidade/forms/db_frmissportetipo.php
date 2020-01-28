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
$clissportetipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q40_codporte");
$clrotulo->label("q12_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq41_codporte?>">
       <?
       db_ancora(@$Lq41_codporte,"js_pesquisaq41_codporte(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q41_codporte',10,$Iq41_codporte,true,'text',$db_opcao," onchange='js_pesquisaq41_codporte(false);'")
?>
       <?
db_input('q40_codporte',10,$Iq40_codporte,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq41_codclasse?>">
       <?
       db_ancora(@$Lq41_codclasse,"js_pesquisaq41_codclasse(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q41_codclasse',4,$Iq41_codclasse,true,'text',$db_opcao," onchange='js_pesquisaq41_codclasse(false);'")
?>
       <?
db_input('q12_descr',40,$Iq12_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq41_codtipcalc?>">
       <?=@$Lq41_codtipcalc?>
    </td>
    <td> 
<?
db_input('q41_codtipcalc',4,$Iq41_codtipcalc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq41_codporte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issporte','func_issporte.php?funcao_js=parent.js_mostraissporte1|q40_codporte|q40_codporte','Pesquisa',true);
  }else{
     if(document.form1.q41_codporte.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issporte','func_issporte.php?pesquisa_chave='+document.form1.q41_codporte.value+'&funcao_js=parent.js_mostraissporte','Pesquisa',false);
     }else{
       document.form1.q40_codporte.value = ''; 
     }
  }
}
function js_mostraissporte(chave,erro){
  document.form1.q40_codporte.value = chave; 
  if(erro==true){ 
    document.form1.q41_codporte.focus(); 
    document.form1.q41_codporte.value = ''; 
  }
}
function js_mostraissporte1(chave1,chave2){
  document.form1.q41_codporte.value = chave1;
  document.form1.q40_codporte.value = chave2;
  db_iframe_issporte.hide();
}
function js_pesquisaq41_codclasse(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_classe','func_classe.php?funcao_js=parent.js_mostraclasse1|q12_classe|q12_descr','Pesquisa',true);
  }else{
     if(document.form1.q41_codclasse.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_classe','func_classe.php?pesquisa_chave='+document.form1.q41_codclasse.value+'&funcao_js=parent.js_mostraclasse','Pesquisa',false);
     }else{
       document.form1.q12_descr.value = ''; 
     }
  }
}
function js_mostraclasse(chave,erro){
  document.form1.q12_descr.value = chave; 
  if(erro==true){ 
    document.form1.q41_codclasse.focus(); 
    document.form1.q41_codclasse.value = ''; 
  }
}
function js_mostraclasse1(chave1,chave2){
  document.form1.q41_codclasse.value = chave1;
  document.form1.q12_descr.value = chave2;
  db_iframe_classe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issportetipo','func_issportetipo.php?funcao_js=parent.js_preenchepesquisa|q41_codporte|q41_codclasse|q41_codtipcalc','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_issportetipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>