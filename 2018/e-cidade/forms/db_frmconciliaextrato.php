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
$clconciliaextrato->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k83_hora");
$clrotulo->label("k86_extrato");
$clrotulo->label("k96_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk87_sequencial?>">
       <?=@$Lk87_sequencial?>
    </td>
    <td> 
<?
db_input('k87_sequencial',10,$Ik87_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk87_conciliaitem?>">
       <?
       db_ancora(@$Lk87_conciliaitem,"js_pesquisak87_conciliaitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k87_conciliaitem',10,$Ik87_conciliaitem,true,'text',$db_opcao," onchange='js_pesquisak87_conciliaitem(false);'")
?>
       <?
db_input('k83_hora',5,$Ik83_hora,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk87_extratolinha?>">
       <?
       db_ancora(@$Lk87_extratolinha,"js_pesquisak87_extratolinha(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k87_extratolinha',10,$Ik87_extratolinha,true,'text',$db_opcao," onchange='js_pesquisak87_extratolinha(false);'")
?>
       <?
db_input('k86_extrato',10,$Ik86_extrato,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk87_conciliaorigem?>">
       <?
       db_ancora(@$Lk87_conciliaorigem,"js_pesquisak87_conciliaorigem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k87_conciliaorigem',8,$Ik87_conciliaorigem,true,'text',$db_opcao," onchange='js_pesquisak87_conciliaorigem(false);'")
?>
       <?
db_input('k96_descr',40,$Ik96_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak87_conciliaitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliaitem','func_conciliaitem.php?funcao_js=parent.js_mostraconciliaitem1|k83_sequencial|k83_hora','Pesquisa',true);
  }else{
     if(document.form1.k87_conciliaitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliaitem','func_conciliaitem.php?pesquisa_chave='+document.form1.k87_conciliaitem.value+'&funcao_js=parent.js_mostraconciliaitem','Pesquisa',false);
     }else{
       document.form1.k83_hora.value = ''; 
     }
  }
}
function js_mostraconciliaitem(chave,erro){
  document.form1.k83_hora.value = chave; 
  if(erro==true){ 
    document.form1.k87_conciliaitem.focus(); 
    document.form1.k87_conciliaitem.value = ''; 
  }
}
function js_mostraconciliaitem1(chave1,chave2){
  document.form1.k87_conciliaitem.value = chave1;
  document.form1.k83_hora.value = chave2;
  db_iframe_conciliaitem.hide();
}
function js_pesquisak87_extratolinha(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_extratolinha','func_extratolinha.php?funcao_js=parent.js_mostraextratolinha1|k86_sequencial|k86_extrato','Pesquisa',true);
  }else{
     if(document.form1.k87_extratolinha.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_extratolinha','func_extratolinha.php?pesquisa_chave='+document.form1.k87_extratolinha.value+'&funcao_js=parent.js_mostraextratolinha','Pesquisa',false);
     }else{
       document.form1.k86_extrato.value = ''; 
     }
  }
}
function js_mostraextratolinha(chave,erro){
  document.form1.k86_extrato.value = chave; 
  if(erro==true){ 
    document.form1.k87_extratolinha.focus(); 
    document.form1.k87_extratolinha.value = ''; 
  }
}
function js_mostraextratolinha1(chave1,chave2){
  document.form1.k87_extratolinha.value = chave1;
  document.form1.k86_extrato.value = chave2;
  db_iframe_extratolinha.hide();
}
function js_pesquisak87_conciliaorigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?funcao_js=parent.js_mostraconciliaorigem1|k96_sequencial|k96_descr','Pesquisa',true);
  }else{
     if(document.form1.k87_conciliaorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?pesquisa_chave='+document.form1.k87_conciliaorigem.value+'&funcao_js=parent.js_mostraconciliaorigem','Pesquisa',false);
     }else{
       document.form1.k96_descr.value = ''; 
     }
  }
}
function js_mostraconciliaorigem(chave,erro){
  document.form1.k96_descr.value = chave; 
  if(erro==true){ 
    document.form1.k87_conciliaorigem.focus(); 
    document.form1.k87_conciliaorigem.value = ''; 
  }
}
function js_mostraconciliaorigem1(chave1,chave2){
  document.form1.k87_conciliaorigem.value = chave1;
  document.form1.k96_descr.value = chave2;
  db_iframe_conciliaorigem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conciliaextrato','func_conciliaextrato.php?funcao_js=parent.js_preenchepesquisa|k87_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conciliaextrato.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>