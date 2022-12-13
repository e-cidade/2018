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
$clconciliacor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k83_hora");
$clrotulo->label("k96_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk84_sequencial?>">
       <?=@$Lk84_sequencial?>
    </td>
    <td> 
<?
db_input('k84_sequencial',10,$Ik84_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk84_conciliaitem?>">
       <?
       db_ancora(@$Lk84_conciliaitem,"js_pesquisak84_conciliaitem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k84_conciliaitem',10,$Ik84_conciliaitem,true,'text',$db_opcao," onchange='js_pesquisak84_conciliaitem(false);'")
?>
       <?
db_input('k83_hora',5,$Ik83_hora,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk84_id?>">
       <?=@$Lk84_id?>
    </td>
    <td> 
<?
db_input('k84_id',5,$Ik84_id,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk84_data?>">
       <?=@$Lk84_data?>
    </td>
    <td> 
<?
db_inputdata('k84_data',@$k84_data_dia,@$k84_data_mes,@$k84_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk84_autent?>">
       <?=@$Lk84_autent?>
    </td>
    <td> 
<?
db_input('k84_autent',5,$Ik84_autent,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk84_conciliaorigem?>">
       <?
       db_ancora(@$Lk84_conciliaorigem,"js_pesquisak84_conciliaorigem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k84_conciliaorigem',8,$Ik84_conciliaorigem,true,'text',$db_opcao," onchange='js_pesquisak84_conciliaorigem(false);'")
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
function js_pesquisak84_conciliaitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliaitem','func_conciliaitem.php?funcao_js=parent.js_mostraconciliaitem1|k83_sequencial|k83_hora','Pesquisa',true);
  }else{
     if(document.form1.k84_conciliaitem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliaitem','func_conciliaitem.php?pesquisa_chave='+document.form1.k84_conciliaitem.value+'&funcao_js=parent.js_mostraconciliaitem','Pesquisa',false);
     }else{
       document.form1.k83_hora.value = ''; 
     }
  }
}
function js_mostraconciliaitem(chave,erro){
  document.form1.k83_hora.value = chave; 
  if(erro==true){ 
    document.form1.k84_conciliaitem.focus(); 
    document.form1.k84_conciliaitem.value = ''; 
  }
}
function js_mostraconciliaitem1(chave1,chave2){
  document.form1.k84_conciliaitem.value = chave1;
  document.form1.k83_hora.value = chave2;
  db_iframe_conciliaitem.hide();
}
function js_pesquisak84_conciliaorigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?funcao_js=parent.js_mostraconciliaorigem1|k96_sequencial|k96_descr','Pesquisa',true);
  }else{
     if(document.form1.k84_conciliaorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?pesquisa_chave='+document.form1.k84_conciliaorigem.value+'&funcao_js=parent.js_mostraconciliaorigem','Pesquisa',false);
     }else{
       document.form1.k96_descr.value = ''; 
     }
  }
}
function js_mostraconciliaorigem(chave,erro){
  document.form1.k96_descr.value = chave; 
  if(erro==true){ 
    document.form1.k84_conciliaorigem.focus(); 
    document.form1.k84_conciliaorigem.value = ''; 
  }
}
function js_mostraconciliaorigem1(chave1,chave2){
  document.form1.k84_conciliaorigem.value = chave1;
  document.form1.k96_descr.value = chave2;
  db_iframe_conciliaorigem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conciliacor','func_conciliacor.php?funcao_js=parent.js_preenchepesquisa|k84_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conciliacor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>