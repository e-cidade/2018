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
$clconciliaitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k65_descricao");
$clrotulo->label("k68_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk83_sequencial?>">
       <?=@$Lk83_sequencial?>
    </td>
    <td> 
<?
db_input('k83_sequencial',10,$Ik83_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk83_conciliatipo?>">
       <?
       db_ancora(@$Lk83_conciliatipo,"js_pesquisak83_conciliatipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k83_conciliatipo',10,$Ik83_conciliatipo,true,'text',$db_opcao," onchange='js_pesquisak83_conciliatipo(false);'")
?>
       <?
db_input('k65_descricao',50,$Ik65_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk83_concilia?>">
       <?
       db_ancora(@$Lk83_concilia,"js_pesquisak83_concilia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k83_concilia',10,$Ik83_concilia,true,'text',$db_opcao," onchange='js_pesquisak83_concilia(false);'")
?>
       <?
db_input('k68_data',10,$Ik68_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk83_hora?>">
       <?=@$Lk83_hora?>
    </td>
    <td> 
<?
db_input('k83_hora',5,$Ik83_hora,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk83_usuario?>">
       <?=@$Lk83_usuario?>
    </td>
    <td> 
<?
db_input('k83_usuario',10,$Ik83_usuario,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak83_conciliatipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conciliatipo','func_conciliatipo.php?funcao_js=parent.js_mostraconciliatipo1|k65_sequencial|k65_descricao','Pesquisa',true);
  }else{
     if(document.form1.k83_conciliatipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conciliatipo','func_conciliatipo.php?pesquisa_chave='+document.form1.k83_conciliatipo.value+'&funcao_js=parent.js_mostraconciliatipo','Pesquisa',false);
     }else{
       document.form1.k65_descricao.value = ''; 
     }
  }
}
function js_mostraconciliatipo(chave,erro){
  document.form1.k65_descricao.value = chave; 
  if(erro==true){ 
    document.form1.k83_conciliatipo.focus(); 
    document.form1.k83_conciliatipo.value = ''; 
  }
}
function js_mostraconciliatipo1(chave1,chave2){
  document.form1.k83_conciliatipo.value = chave1;
  document.form1.k65_descricao.value = chave2;
  db_iframe_conciliatipo.hide();
}
function js_pesquisak83_concilia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?funcao_js=parent.js_mostraconcilia1|k68_sequencial|k68_data','Pesquisa',true);
  }else{
     if(document.form1.k83_concilia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?pesquisa_chave='+document.form1.k83_concilia.value+'&funcao_js=parent.js_mostraconcilia','Pesquisa',false);
     }else{
       document.form1.k68_data.value = ''; 
     }
  }
}
function js_mostraconcilia(chave,erro){
  document.form1.k68_data.value = chave; 
  if(erro==true){ 
    document.form1.k83_concilia.focus(); 
    document.form1.k83_concilia.value = ''; 
  }
}
function js_mostraconcilia1(chave1,chave2){
  document.form1.k83_concilia.value = chave1;
  document.form1.k68_data.value = chave2;
  db_iframe_concilia.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conciliaitem','func_conciliaitem.php?funcao_js=parent.js_preenchepesquisa|k83_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conciliaitem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>