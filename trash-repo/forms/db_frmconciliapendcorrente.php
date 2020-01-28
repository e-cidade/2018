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
$clconciliapendcorrente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k68_data");
$clrotulo->label("k96_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk89_sequencial?>">
       <?=@$Lk89_sequencial?>
    </td>
    <td> 
<?
db_input('k89_sequencial',10,$Ik89_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk89_concilia?>">
       <?
       db_ancora(@$Lk89_concilia,"js_pesquisak89_concilia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k89_concilia',10,$Ik89_concilia,true,'text',$db_opcao," onchange='js_pesquisak89_concilia(false);'")
?>
       <?
db_input('k68_data',10,$Ik68_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk89_id?>">
       <?=@$Lk89_id?>
    </td>
    <td> 
<?
db_input('k89_id',5,$Ik89_id,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk89_data?>">
       <?=@$Lk89_data?>
    </td>
    <td> 
<?
db_inputdata('k89_data',@$k89_data_dia,@$k89_data_mes,@$k89_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk89_autent?>">
       <?=@$Lk89_autent?>
    </td>
    <td> 
<?
db_input('k89_autent',5,$Ik89_autent,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk89_conciliaorigem?>">
       <?
       db_ancora(@$Lk89_conciliaorigem,"js_pesquisak89_conciliaorigem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k89_conciliaorigem',8,$Ik89_conciliaorigem,true,'text',$db_opcao," onchange='js_pesquisak89_conciliaorigem(false);'")
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
function js_pesquisak89_concilia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?funcao_js=parent.js_mostraconcilia1|k68_sequencial|k68_data','Pesquisa',true);
  }else{
     if(document.form1.k89_concilia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?pesquisa_chave='+document.form1.k89_concilia.value+'&funcao_js=parent.js_mostraconcilia','Pesquisa',false);
     }else{
       document.form1.k68_data.value = ''; 
     }
  }
}
function js_mostraconcilia(chave,erro){
  document.form1.k68_data.value = chave; 
  if(erro==true){ 
    document.form1.k89_concilia.focus(); 
    document.form1.k89_concilia.value = ''; 
  }
}
function js_mostraconcilia1(chave1,chave2){
  document.form1.k89_concilia.value = chave1;
  document.form1.k68_data.value = chave2;
  db_iframe_concilia.hide();
}
function js_pesquisak89_conciliaorigem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?funcao_js=parent.js_mostraconciliaorigem1|k96_sequencial|k96_descr','Pesquisa',true);
  }else{
     if(document.form1.k89_conciliaorigem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliaorigem','func_conciliaorigem.php?pesquisa_chave='+document.form1.k89_conciliaorigem.value+'&funcao_js=parent.js_mostraconciliaorigem','Pesquisa',false);
     }else{
       document.form1.k96_descr.value = ''; 
     }
  }
}
function js_mostraconciliaorigem(chave,erro){
  document.form1.k96_descr.value = chave; 
  if(erro==true){ 
    document.form1.k89_conciliaorigem.focus(); 
    document.form1.k89_conciliaorigem.value = ''; 
  }
}
function js_mostraconciliaorigem1(chave1,chave2){
  document.form1.k89_conciliaorigem.value = chave1;
  document.form1.k96_descr.value = chave2;
  db_iframe_conciliaorigem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conciliapendcorrente','func_conciliapendcorrente.php?funcao_js=parent.js_preenchepesquisa|k89_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conciliapendcorrente.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>