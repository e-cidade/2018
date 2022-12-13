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
$clconcedente->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o37_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To108_sequencial?>">
       <?=@$Lo108_sequencial?>
    </td>
    <td> 
<?
db_input('o108_sequencial',10,$Io108_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To108_numcgm?>">
       <?
       db_ancora(@$Lo108_numcgm,"js_pesquisao108_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o108_numcgm',10,$Io108_numcgm,true,'text',$db_opcao," onchange='js_pesquisao108_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To108_tipoconcedente?>">
       <?
       db_ancora(@$Lo108_tipoconcedente,"js_pesquisao108_tipoconcedente(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o108_tipoconcedente',10,$Io108_tipoconcedente,true,'text',$db_opcao," onchange='js_pesquisao108_tipoconcedente(false);'")
?>
       <?
db_input('o37_descricao',50,$Io37_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao108_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.o108_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.o108_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.o108_numcgm.focus(); 
    document.form1.o108_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.o108_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisao108_tipoconcedente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoconcedente','func_tipoconcedente.php?funcao_js=parent.js_mostratipoconcedente1|o37_sequencial|o37_descricao','Pesquisa',true);
  }else{
     if(document.form1.o108_tipoconcedente.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoconcedente','func_tipoconcedente.php?pesquisa_chave='+document.form1.o108_tipoconcedente.value+'&funcao_js=parent.js_mostratipoconcedente','Pesquisa',false);
     }else{
       document.form1.o37_descricao.value = ''; 
     }
  }
}
function js_mostratipoconcedente(chave,erro){
  document.form1.o37_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o108_tipoconcedente.focus(); 
    document.form1.o108_tipoconcedente.value = ''; 
  }
}
function js_mostratipoconcedente1(chave1,chave2){
  document.form1.o108_tipoconcedente.value = chave1;
  document.form1.o37_descricao.value = chave2;
  db_iframe_tipoconcedente.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_concedente','func_concedente.php?funcao_js=parent.js_preenchepesquisa|o108_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_concedente.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>