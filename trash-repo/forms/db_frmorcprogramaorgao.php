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
$clorcprogramaorgao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o54_anousu");
$clrotulo->label("o40_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o54_anousu");
$clrotulo->label("o54_anousu");
$clrotulo->label("o40_descr");
$clrotulo->label("o40_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To12_sequencial?>">
       <?=@$Lo12_sequencial?>
    </td>
    <td> 
<?
db_input('o12_sequencial',10,$Io12_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To12_anousu?>">
       <?
       db_ancora(@$Lo12_anousu,"js_pesquisao12_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o12_anousu = db_getsession('DB_anousu');
db_input('o12_anousu',4,$Io12_anousu,true,'text',3," onchange='js_pesquisao12_anousu(false);'")
?>
       <?
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To12_orcprograma?>">
       <?
       db_ancora(@$Lo12_orcprograma,"js_pesquisao12_orcprograma(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o12_orcprograma',10,$Io12_orcprograma,true,'text',$db_opcao," onchange='js_pesquisao12_orcprograma(false);'")
?>
       <?
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To12_orcorgao?>">
       <?
       db_ancora(@$Lo12_orcorgao,"js_pesquisao12_orcorgao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o12_orcorgao',10,$Io12_orcorgao,true,'text',$db_opcao," onchange='js_pesquisao12_orcorgao(false);'")
?>
       <?
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao12_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o12_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o12_anousu.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o12_anousu.focus(); 
    document.form1.o12_anousu.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o12_anousu.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao12_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o12_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o12_anousu.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o12_anousu.focus(); 
    document.form1.o12_anousu.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o12_anousu.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao12_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o12_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o12_anousu.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o12_anousu.focus(); 
    document.form1.o12_anousu.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o12_anousu.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao12_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o12_anousu.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o12_anousu.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o12_anousu.focus(); 
    document.form1.o12_anousu.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o12_anousu.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao12_orcprograma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o12_orcprograma.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o12_orcprograma.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o12_orcprograma.focus(); 
    document.form1.o12_orcprograma.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o12_orcprograma.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao12_orcprograma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o12_orcprograma.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o12_orcprograma.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o12_orcprograma.focus(); 
    document.form1.o12_orcprograma.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o12_orcprograma.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao12_orcorgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o12_orcorgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o12_orcorgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o12_orcorgao.focus(); 
    document.form1.o12_orcorgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o12_orcorgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao12_orcorgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o12_orcorgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o12_orcorgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o12_orcorgao.focus(); 
    document.form1.o12_orcorgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o12_orcorgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcprogramaorgao','func_orcprogramaorgao.php?funcao_js=parent.js_preenchepesquisa|o12_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_orcprogramaorgao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>