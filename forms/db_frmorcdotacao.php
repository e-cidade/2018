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
$clorcdotacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o55_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o61_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To58_anousu?>">
       <?
       db_ancora(@$Lo58_anousu,"js_pesquisao58_anousu(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$o58_anousu = db_getsession('DB_anousu');
db_input('o58_anousu',4,$Io58_anousu,true,'text',$db_opcao," onchange='js_pesquisao58_anousu(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_coddot?>">
       <?=@$Lo58_coddot?>
    </td>
    <td> 
<?
db_input('o58_coddot',6,$Io58_coddot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_orgao?>">
       <?
       db_ancora(@$Lo58_orgao,"js_pesquisao58_orgao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_orgao',2,$Io58_orgao,true,'text',$db_opcao," onchange='js_pesquisao58_orgao(false);'")
?>
       <?
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_unidade?>">
       <?
       db_ancora(@$Lo58_unidade,"js_pesquisao58_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_unidade',2,$Io58_unidade,true,'text',$db_opcao," onchange='js_pesquisao58_unidade(false);'")
?>
       <?
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_funcao?>">
       <?
       db_ancora(@$Lo58_funcao,"js_pesquisao58_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_funcao',2,$Io58_funcao,true,'text',$db_opcao," onchange='js_pesquisao58_funcao(false);'")
?>
       <?
db_input('o52_descr',40,$Io52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_subfuncao?>">
       <?
       db_ancora(@$Lo58_subfuncao,"js_pesquisao58_subfuncao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_subfuncao',3,$Io58_subfuncao,true,'text',$db_opcao," onchange='js_pesquisao58_subfuncao(false);'")
?>
       <?
db_input('o53_descr',40,$Io53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_programa?>">
       <?
       db_ancora(@$Lo58_programa,"js_pesquisao58_programa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_programa',4,$Io58_programa,true,'text',$db_opcao," onchange='js_pesquisao58_programa(false);'")
?>
       <?
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_projativ?>">
       <?
       db_ancora(@$Lo58_projativ,"js_pesquisao58_projativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_projativ',4,$Io58_projativ,true,'text',$db_opcao," onchange='js_pesquisao58_projativ(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_codele?>">
       <?
       db_ancora(@$Lo58_codele,"js_pesquisao58_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_codele',6,$Io58_codele,true,'text',$db_opcao," onchange='js_pesquisao58_codele(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To58_codigo?>">
       <?
       db_ancora(@$Lo58_codigo,"js_pesquisao58_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_codigo',4,$Io58_codigo,true,'text',$db_opcao," onchange='js_pesquisao58_codigo(false);'")
?>
       <?
db_input('o15_descr',30,$Io15_descr,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$To61_codigo?>">
       <?
       db_ancora(@$Lo61_codigo,"js_pesquisao61_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o61_codigo',4,$Io61_codigo,true,'text',$db_opcao," onchange='js_pesquisao61_codigo(false);'")
?>
       <?
db_input('o15_descr',30,$Io15_descr,true,'text',3,'','o15_contra_recurso')
       ?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$To58_valor?>">
       <?
       db_ancora(@$Lo58_valor,"js_pesquisao58_valor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o58_valor',15,$Io58_valor,true,'text',$db_opcao," onchange='js_pesquisao58_valor(false);'")
?>
       <?
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>



  
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_anousu.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_anousu.focus(); 
    document.form1.o58_anousu.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_anousu.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o58_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_orgao.focus(); 
    document.form1.o58_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o58_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao58_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o58_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_orgao.focus(); 
    document.form1.o58_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o58_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao58_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_unidade.focus(); 
    document.form1.o58_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_unidade.focus(); 
    document.form1.o58_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o58_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_unidade.focus(); 
    document.form1.o58_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o58_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao58_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfuncao','func_orcfuncao.php?funcao_js=parent.js_mostraorcfuncao1|o52_funcao|o52_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfuncao','func_orcfuncao.php?pesquisa_chave='+document.form1.o58_funcao.value+'&funcao_js=parent.js_mostraorcfuncao','Pesquisa',false);
  }
}
function js_mostraorcfuncao(chave,erro){
  document.form1.o52_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_funcao.focus(); 
    document.form1.o58_funcao.value = ''; 
  }
}
function js_mostraorcfuncao1(chave1,chave2){
  document.form1.o58_funcao.value = chave1;
  document.form1.o52_descr.value = chave2;
  db_iframe_orcfuncao.hide();
}
function js_pesquisao58_subfuncao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsubfuncao','func_orcsubfuncao.php?funcao_js=parent.js_mostraorcsubfuncao1|o53_subfuncao|o53_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsubfuncao','func_orcsubfuncao.php?pesquisa_chave='+document.form1.o58_subfuncao.value+'&funcao_js=parent.js_mostraorcsubfuncao','Pesquisa',false);
  }
}
function js_mostraorcsubfuncao(chave,erro){
  document.form1.o53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_subfuncao.focus(); 
    document.form1.o58_subfuncao.value = ''; 
  }
}
function js_mostraorcsubfuncao1(chave1,chave2){
  document.form1.o58_subfuncao.value = chave1;
  document.form1.o53_descr.value = chave2;
  db_iframe_orcsubfuncao.hide();
}
function js_pesquisao58_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o58_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o58_programa.focus(); 
    document.form1.o58_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o58_programa.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao58_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o58_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o58_programa.focus(); 
    document.form1.o58_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o58_programa.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao58_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o58_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_projativ.focus(); 
    document.form1.o58_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o58_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao58_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o58_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_projativ.focus(); 
    document.form1.o58_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o58_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao58_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o58_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.o58_codele.focus(); 
    document.form1.o58_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o58_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao58_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o58_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_codigo.focus(); 
    document.form1.o58_codigo.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.o58_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}

function js_pesquisao61_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec2|o15_codigo|o15_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o58_codigo.value+'&funcao_js=parent.js_mostraorctiporec3','Pesquisa',false);
  }
}
function js_mostraorctiporec3(chave,erro){
  document.form1.o15_contra_recurso.value = chave; 
  if(erro==true){ 
    document.form1.o61_codigo.focus(); 
    document.form1.o61_codigo.value = ''; 
  }
}
function js_mostraorctiporec2(chave1,chave2){
  document.form1.o61_codigo.value = chave1;
  document.form1.o15_contra_recurso.value = chave2;
  db_iframe_orctiporec.hide();
}



function js_pesquisao58_valor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o58_valor.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_valor.focus(); 
    document.form1.o58_valor.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o58_valor.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao58_valor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o58_valor.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_valor.focus(); 
    document.form1.o58_valor.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o58_valor.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_preenchepesquisa|o58_anousu|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcdotacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>