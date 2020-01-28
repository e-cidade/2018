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
$clissvarnotas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q05_numpre");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq06_codigo?>">
       <?
       db_ancora(@$Lq06_codigo,"js_pesquisaq06_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q06_codigo',0,$Iq06_codigo,true,'text',$db_opcao," onchange='js_pesquisaq06_codigo(false);'")
?>
       <?
db_input('q05_numpre',4,$Iq05_numpre,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq06_seq?>">
       <?=@$Lq06_seq?>
    </td>
    <td> 
<?
db_input('q06_seq',5,$Iq06_seq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq06_nota?>">
       <?=@$Lq06_nota?>
    </td>
    <td> 
<?
db_input('q06_nota',100,$Iq06_nota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq06_valor?>">
       <?=@$Lq06_valor?>
    </td>
    <td> 
<?
db_input('q06_valor',15,$Iq06_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq06_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issvar','func_issvar.php?funcao_js=parent.js_mostraissvar1|q05_codigo|q05_numpre','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_issvar','func_issvar.php?pesquisa_chave='+document.form1.q06_codigo.value+'&funcao_js=parent.js_mostraissvar','Pesquisa',false);
  }
}
function js_mostraissvar(chave,erro){
  document.form1.q05_numpre.value = chave; 
  if(erro==true){ 
    document.form1.q06_codigo.focus(); 
    document.form1.q06_codigo.value = ''; 
  }
}
function js_mostraissvar1(chave1,chave2){
  document.form1.q06_codigo.value = chave1;
  document.form1.q05_numpre.value = chave2;
  db_iframe_issvar.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issvarnotas','func_issvarnotas.php?funcao_js=parent.js_preenchepesquisa|q06_codigo|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_issvarnotas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>