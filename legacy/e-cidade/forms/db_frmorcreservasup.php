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

//MODULO: orcamento
$clorcreservasup->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o80_descr");
$clrotulo->label("o46_codlei");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To81_codres?>">
       <?
       db_ancora(@$Lo81_codres,"js_pesquisao81_codres(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o81_codres',8,$Io81_codres,true,'text',$db_opcao," onchange='js_pesquisao81_codres(false);'")
?>
       <?
db_input('o80_descr',1,$Io80_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To81_codsup?>">
       <?
       db_ancora(@$Lo81_codsup,"js_pesquisao81_codsup(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o81_codsup',4,$Io81_codsup,true,'text',$db_opcao," onchange='js_pesquisao81_codsup(false);'")
?>
       <?
db_input('o46_codlei',4,$Io46_codlei,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao81_codres(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?funcao_js=parent.js_mostraorcreserva1|o80_codres|o80_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?pesquisa_chave='+document.form1.o81_codres.value+'&funcao_js=parent.js_mostraorcreserva','Pesquisa',false);
  }
}
function js_mostraorcreserva(chave,erro){
  document.form1.o80_descr.value = chave; 
  if(erro==true){ 
    document.form1.o81_codres.focus(); 
    document.form1.o81_codres.value = ''; 
  }
}
function js_mostraorcreserva1(chave1,chave2){
  document.form1.o81_codres.value = chave1;
  document.form1.o80_descr.value = chave2;
  db_iframe_orcreserva.hide();
}
function js_pesquisao81_codsup(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplem','func_orcsuplem.php?funcao_js=parent.js_mostraorcsuplem1|o46_codsup|o46_codlei','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsuplem','func_orcsuplem.php?pesquisa_chave='+document.form1.o81_codsup.value+'&funcao_js=parent.js_mostraorcsuplem','Pesquisa',false);
  }
}
function js_mostraorcsuplem(chave,erro){
  document.form1.o46_codlei.value = chave; 
  if(erro==true){ 
    document.form1.o81_codsup.focus(); 
    document.form1.o81_codsup.value = ''; 
  }
}
function js_mostraorcsuplem1(chave1,chave2){
  document.form1.o81_codsup.value = chave1;
  document.form1.o46_codlei.value = chave2;
  db_iframe_orcsuplem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcreservasup','func_orcreservasup.php?funcao_js=parent.js_preenchepesquisa|o81_codres','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcreservasup.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>