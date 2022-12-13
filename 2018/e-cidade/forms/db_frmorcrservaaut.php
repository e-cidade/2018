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
$clorcrservaaut->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o80_descr");
$clrotulo->label("e04_destin");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To83_codres?>">
       <?
       db_ancora(@$Lo83_codres,"js_pesquisao83_codres(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o83_codres',8,$Io83_codres,true,'text',$db_opcao," onchange='js_pesquisao83_codres(false);'")
?>
       <?
db_input('o80_descr',1,$Io80_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To83_autori?>">
       <?
       db_ancora(@$Lo83_autori,"js_pesquisao83_autori(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o83_autori',6,$Io83_autori,true,'text',$db_opcao," onchange='js_pesquisao83_autori(false);'")
?>
       <?
db_input('e04_destin',40,$Ie04_destin,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao83_codres(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?funcao_js=parent.js_mostraorcreserva1|o80_codres|o80_descr','Pesquisa',true);
  }else{
     if(document.form1.o83_codres.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcreserva','func_orcreserva.php?pesquisa_chave='+document.form1.o83_codres.value+'&funcao_js=parent.js_mostraorcreserva','Pesquisa',false);
     }else{
       document.form1.o80_descr.value = ''; 
     }
  }
}
function js_mostraorcreserva(chave,erro){
  document.form1.o80_descr.value = chave; 
  if(erro==true){ 
    document.form1.o83_codres.focus(); 
    document.form1.o83_codres.value = ''; 
  }
}
function js_mostraorcreserva1(chave1,chave2){
  document.form1.o83_codres.value = chave1;
  document.form1.o80_descr.value = chave2;
  db_iframe_orcreserva.hide();
}
function js_pesquisao83_autori(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_autoriza','func_autoriza.php?funcao_js=parent.js_mostraautoriza1|e04_autori|e04_destin','Pesquisa',true);
  }else{
     if(document.form1.o83_autori.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_autoriza','func_autoriza.php?pesquisa_chave='+document.form1.o83_autori.value+'&funcao_js=parent.js_mostraautoriza','Pesquisa',false);
     }else{
       document.form1.e04_destin.value = ''; 
     }
  }
}
function js_mostraautoriza(chave,erro){
  document.form1.e04_destin.value = chave; 
  if(erro==true){ 
    document.form1.o83_autori.focus(); 
    document.form1.o83_autori.value = ''; 
  }
}
function js_mostraautoriza1(chave1,chave2){
  document.form1.o83_autori.value = chave1;
  document.form1.e04_destin.value = chave2;
  db_iframe_autoriza.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcrservaaut','func_orcrservaaut.php?funcao_js=parent.js_preenchepesquisa|o83_codres','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcrservaaut.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>