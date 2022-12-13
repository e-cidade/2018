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
$clvarfixnotifica->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q33_codigo");
$clrotulo->label("y30_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq37_sequence?>">
       <?=@$Lq37_sequence?>
    </td>
    <td> 
<?
db_input('q37_sequence',10,$Iq37_sequence,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq37_varfix?>">
       <?
       db_ancora(@$Lq37_varfix,"js_pesquisaq37_varfix(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q37_varfix',8,$Iq37_varfix,true,'text',$db_opcao," onchange='js_pesquisaq37_varfix(false);'")
?>
       <?
db_input('q33_codigo',8,$Iq33_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq37_notifica?>">
       <?
       db_ancora(@$Lq37_notifica,"js_pesquisaq37_notifica(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q37_notifica',20,$Iq37_notifica,true,'text',$db_opcao," onchange='js_pesquisaq37_notifica(false);'")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq37_varfix(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?funcao_js=parent.js_mostravarfix1|q33_codigo|q33_codigo','Pesquisa',true);
  }else{
     if(document.form1.q37_varfix.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_varfix','func_varfix.php?pesquisa_chave='+document.form1.q37_varfix.value+'&funcao_js=parent.js_mostravarfix','Pesquisa',false);
     }else{
       document.form1.q33_codigo.value = ''; 
     }
  }
}
function js_mostravarfix(chave,erro){
  document.form1.q33_codigo.value = chave; 
  if(erro==true){ 
    document.form1.q37_varfix.focus(); 
    document.form1.q37_varfix.value = ''; 
  }
}
function js_mostravarfix1(chave1,chave2){
  document.form1.q37_varfix.value = chave1;
  document.form1.q33_codigo.value = chave2;
  db_iframe_varfix.hide();
}
function js_pesquisaq37_notifica(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
     if(document.form1.q37_notifica.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.q37_notifica.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
     }else{
       document.form1.y30_data.value = ''; 
     }
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.q37_notifica.focus(); 
    document.form1.q37_notifica.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.q37_notifica.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_varfixnotifica','func_varfixnotifica.php?funcao_js=parent.js_preenchepesquisa|q37_sequence','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_varfixnotifica.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>