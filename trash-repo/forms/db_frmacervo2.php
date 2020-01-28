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

//MODULO: Biblioteca
$clacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi09_nome");
$clrotulo->label("bi05_nome");
$clrotulo->label("bi02_nome");
$clrotulo->label("bi03_classificacao");
$clrotulo->label("bi04_forma");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbi06_tipoitem?>">
       <?
       db_ancora(@$Lbi06_tipoitem,"js_pesquisabi06_tipoitem(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('bi06_tipoitem',10,$Ibi06_tipoitem,true,'text',$db_opcao," onchange='js_pesquisabi06_tipoitem(false);' onKeyPress='tab(event,4)'")
?>
       <?
db_input('bi05_nome',50,$Ibi05_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi06_editora?>">
       <?
       db_ancora(@$Lbi06_editora,"js_pesquisabi06_editora(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('bi06_editora',10,$Ibi06_editora,true,'text',$db_opcao," onchange='js_pesquisabi06_editora(false);' onKeyPress='tab(event,6)'")
?>
       <?
db_input('bi02_nome',50,$Ibi02_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi06_classiliteraria?>">
       <?
       db_ancora(@$Lbi06_classiliteraria,"js_pesquisabi06_classiliteraria(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('bi06_classiliteraria',10,$Ibi06_classiliteraria,true,'text',$db_opcao," onchange='js_pesquisabi06_classiliteraria(false);' onKeyPress='tab(event,8)'")
?>
       <?
db_input('bi03_classificacao',50,$Ibi03_classificacao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi06_aquisicao?>">
       <?
       db_ancora(@$Lbi06_aquisicao,"js_pesquisabi06_aquisicao(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('bi06_aquisicao',10,$Ibi06_aquisicao,true,'text',$db_opcao," onchange='js_pesquisabi06_aquisicao(false);' onKeyPress='tab(event,9)'")
?>
       <?
db_input('bi04_forma',50,$Ibi04_forma,true,'text',3,"onKeyPress='tab(event,10)'")
       ?>
    </td>
  </tr>
  </table>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" id="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="excluir" type="submit" id="excluir" value="Excluir" <?=$db_opcao==1?"disabled":""?>>
  <?if($db_opcao!=1){?>
  <input name="autores" type="button" id="autores" value="Alterar Autores" <?=$db_opcao==1?"disabled":""?> onclick="js_autores('<?=$bi06_seq?>','<?=$bi06_titulo?>')">
  <input name="assuntos" type="button" id="assuntos" value="Alterar Assuntos" <?=$db_opcao==1?"disabled":""?> onclick="js_assuntos('<?=$bi06_seq?>','<?=$bi06_titulo?>')">
  <?}?>
</form>
<script>
function js_pesquisabi06_localizacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_localizacao','func_localizacao.php?funcao_js=parent.js_mostralocalizacao1|bi09_codigo|bi09_nome','Pesquisa',true);
  }else{
     if(document.form1.bi06_localizacao.value != ''){
        js_OpenJanelaIframe('','db_iframe_localizacao','func_localizacao.php?pesquisa_chave='+document.form1.bi06_localizacao.value+'&funcao_js=parent.js_mostralocalizacao','Pesquisa',false);
     }else{
       document.form1.bi09_nome.value = '';
     }
  }
}
function js_mostralocalizacao(chave,erro){
  document.form1.bi09_nome.value = chave; 
  if(erro==true){ 
    document.form1.bi06_localizacao.focus(); 
    document.form1.bi06_localizacao.value = ''; 
  }
}
function js_mostralocalizacao1(chave1,chave2){
  document.form1.bi06_localizacao.value = chave1;
  document.form1.bi09_nome.value = chave2;
  db_iframe_localizacao.hide();
}
function js_pesquisabi06_tipoitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipoitem','func_tipoitem.php?funcao_js=parent.js_mostratipoitem1|bi05_codigo|bi05_nome','Pesquisa',true);
  }else{
     if(document.form1.bi06_tipoitem.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tipoitem','func_tipoitem.php?pesquisa_chave='+document.form1.bi06_tipoitem.value+'&funcao_js=parent.js_mostratipoitem','Pesquisa',false);
     }else{
       document.form1.bi05_nome.value = ''; 
     }
  }
}
function js_mostratipoitem(chave,erro){
  document.form1.bi05_nome.value = chave; 
  if(erro==true){ 
    document.form1.bi06_tipoitem.focus(); 
    document.form1.bi06_tipoitem.value = ''; 
  }
}
function js_mostratipoitem1(chave1,chave2){
  document.form1.bi06_tipoitem.value = chave1;
  document.form1.bi05_nome.value = chave2;
  db_iframe_tipoitem.hide();
}
function js_pesquisabi06_editora(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_editora','func_editora.php?funcao_js=parent.js_mostraeditora1|bi02_codigo|bi02_nome','Pesquisa',true);
  }else{
     if(document.form1.bi06_editora.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_editora','func_editora.php?pesquisa_chave='+document.form1.bi06_editora.value+'&funcao_js=parent.js_mostraeditora','Pesquisa',false);
     }else{
       document.form1.bi02_nome.value = ''; 
     }
  }
}
function js_mostraeditora(chave,erro){
  document.form1.bi02_nome.value = chave; 
  if(erro==true){ 
    document.form1.bi06_editora.focus(); 
    document.form1.bi06_editora.value = ''; 
  }
}
function js_mostraeditora1(chave1,chave2){
  document.form1.bi06_editora.value = chave1;
  document.form1.bi02_nome.value = chave2;
  db_iframe_editora.hide();
}
function js_pesquisabi06_classiliteraria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_classiliteraria','func_classiliteraria.php?funcao_js=parent.js_mostraclassiliteraria1|bi03_codigo|bi03_classificacao','Pesquisa',true);
  }else{
     if(document.form1.bi06_classiliteraria.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_classiliteraria','func_classiliteraria.php?pesquisa_chave='+document.form1.bi06_classiliteraria.value+'&funcao_js=parent.js_mostraclassiliteraria','Pesquisa',false);
     }else{
       document.form1.bi03_classificacao.value = ''; 
     }
  }
}
function js_mostraclassiliteraria(chave,erro){
  document.form1.bi03_classificacao.value = chave; 
  if(erro==true){ 
    document.form1.bi06_classiliteraria.focus(); 
    document.form1.bi06_classiliteraria.value = ''; 
  }
}
function js_mostraclassiliteraria1(chave1,chave2){
  document.form1.bi06_classiliteraria.value = chave1;
  document.form1.bi03_classificacao.value = chave2;
  db_iframe_classiliteraria.hide();
}
function js_pesquisabi06_aquisicao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_aquisicao','func_aquisicao.php?funcao_js=parent.js_mostraaquisicao1|bi04_codigo|bi04_forma','Pesquisa',true);
  }else{
     if(document.form1.bi06_aquisicao.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_aquisicao','func_aquisicao.php?pesquisa_chave='+document.form1.bi06_aquisicao.value+'&funcao_js=parent.js_mostraaquisicao','Pesquisa',false);
     }else{
       document.form1.bi04_forma.value = ''; 
     }
  }
}
function js_mostraaquisicao(chave,erro){
  document.form1.bi04_forma.value = chave; 
  if(erro==true){ 
    document.form1.bi06_aquisicao.focus(); 
    document.form1.bi06_aquisicao.value = ''; 
  }
}
function js_mostraaquisicao1(chave1,chave2){
  document.form1.bi06_aquisicao.value = chave1;
  document.form1.bi04_forma.value = chave2;
  db_iframe_aquisicao.hide();
}
function js_pesquisabi06_coddepto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.bi06_coddepto.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.bi06_coddepto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.bi06_coddepto.focus(); 
    document.form1.bi06_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.bi06_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_acervo','func_acervo.php?funcao_js=parent.js_preenchepesquisa|bi06_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_acervo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_autores(acervo,titulo){
 parent.iframe_acervo3.location="bib1_acervo003.php?acervo="+acervo+'&titulo='+titulo;
 parent.mo_camada('acervo3');
 parent.document.formaba.acervo3.disabled = false;
}
function js_assuntos(acervo,titulo){
 parent.iframe_acervo4.location="bib1_assunto001.php?acervo="+acervo+'&titulo='+titulo;
 parent.mo_camada('acervo4');
 parent.document.formaba.acervo4.disabled = false;
}
</script>