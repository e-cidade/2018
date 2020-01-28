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
$clppadotacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o54_anousu");
$clrotulo->label("o56_codele");
$clrotulo->label("o56_codele");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o54_anousu");
$clrotulo->label("o54_anousu");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o56_codele");
$clrotulo->label("o56_codele");
$clrotulo->label("o11_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To08_sequencial?>">
       <?=@$Lo08_sequencial?>
    </td>
    <td> 
<?
db_input('o08_sequencial',10,$Io08_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_ano?>">
       <?
       db_ancora(@$Lo08_ano,"js_pesquisao08_ano(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_ano',4,$Io08_ano,true,'text',$db_opcao," onchange='js_pesquisao08_ano(false);'")
?>
       <?
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o56_codele',6,$Io56_codele,true,'text',3,'')
db_input('o56_codele',6,$Io56_codele,true,'text',3,'')
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_orgao?>">
       <?
       db_ancora(@$Lo08_orgao,"js_pesquisao08_orgao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_orgao',10,$Io08_orgao,true,'text',$db_opcao," onchange='js_pesquisao08_orgao(false);'")
?>
       <?
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o40_descr',50,$Io40_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_unidade?>">
       <?
       db_ancora(@$Lo08_unidade,"js_pesquisao08_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_unidade',10,$Io08_unidade,true,'text',$db_opcao," onchange='js_pesquisao08_unidade(false);'")
?>
       <?
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
db_input('o41_descr',50,$Io41_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_funcao?>">
       <?
       db_ancora(@$Lo08_funcao,"js_pesquisao08_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_funcao',10,$Io08_funcao,true,'text',$db_opcao," onchange='js_pesquisao08_funcao(false);'")
?>
       <?
db_input('o52_descr',40,$Io52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_subfuncao?>">
       <?
       db_ancora(@$Lo08_subfuncao,"js_pesquisao08_subfuncao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_subfuncao',10,$Io08_subfuncao,true,'text',$db_opcao," onchange='js_pesquisao08_subfuncao(false);'")
?>
       <?
db_input('o53_descr',40,$Io53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_programa?>">
       <?
       db_ancora(@$Lo08_programa,"js_pesquisao08_programa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_programa',10,$Io08_programa,true,'text',$db_opcao," onchange='js_pesquisao08_programa(false);'")
?>
       <?
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
db_input('o54_anousu',4,$Io54_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_projativ?>">
       <?
       db_ancora(@$Lo08_projativ,"js_pesquisao08_projativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_projativ',10,$Io08_projativ,true,'text',$db_opcao," onchange='js_pesquisao08_projativ(false);'")
?>
       <?
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
db_input('o55_descr',40,$Io55_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_elemento?>">
       <?
       db_ancora(@$Lo08_elemento,"js_pesquisao08_elemento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_elemento',10,$Io08_elemento,true,'text',$db_opcao," onchange='js_pesquisao08_elemento(false);'")
?>
       <?
db_input('o56_codele',6,$Io56_codele,true,'text',3,'')
db_input('o56_codele',6,$Io56_codele,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_recurso?>">
       <?=@$Lo08_recurso?>
    </td>
    <td> 
<?
db_input('o08_recurso',10,$Io08_recurso,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_instit?>">
       <?=@$Lo08_instit?>
    </td>
    <td> 
<?
db_input('o08_instit',10,$Io08_instit,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_localizadorgastos?>">
       <?
       db_ancora(@$Lo08_localizadorgastos,"js_pesquisao08_localizadorgastos(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o08_localizadorgastos',10,$Io08_localizadorgastos,true,'text',$db_opcao," onchange='js_pesquisao08_localizadorgastos(false);'")
?>
       <?
db_input('o11_codigo',10,$Io11_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_anousu|o56_codele','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_codele.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_codele.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o56_codele.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_codele','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_codele.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_codele.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o56_codele.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_ano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_ano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_ano.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_ano.focus(); 
    document.form1.o08_ano.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_ano.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_anousu|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_orgao.focus(); 
    document.form1.o08_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o08_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostraorcorgao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_orgao.focus(); 
    document.form1.o08_orgao.value = ''; 
  }
}
function js_mostraorcorgao1(chave1,chave2){
  document.form1.o08_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orcorgao.hide();
}
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_orgao.focus(); 
    document.form1.o08_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_orgao.focus(); 
    document.form1.o08_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_orgao.focus(); 
    document.form1.o08_orgao.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_orgao.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_anousu|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_unidade.focus(); 
    document.form1.o08_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_unidade.focus(); 
    document.form1.o08_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
     }else{
       document.form1.o41_descr.value = ''; 
     }
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o41_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_unidade.focus(); 
    document.form1.o08_unidade.value = ''; 
  }
}
function js_mostraorcunidade1(chave1,chave2){
  document.form1.o08_unidade.value = chave1;
  document.form1.o41_descr.value = chave2;
  db_iframe_orcunidade.hide();
}
function js_pesquisao08_funcao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfuncao','func_orcfuncao.php?funcao_js=parent.js_mostraorcfuncao1|o52_funcao|o52_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_funcao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcfuncao','func_orcfuncao.php?pesquisa_chave='+document.form1.o08_funcao.value+'&funcao_js=parent.js_mostraorcfuncao','Pesquisa',false);
     }else{
       document.form1.o52_descr.value = ''; 
     }
  }
}
function js_mostraorcfuncao(chave,erro){
  document.form1.o52_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_funcao.focus(); 
    document.form1.o08_funcao.value = ''; 
  }
}
function js_mostraorcfuncao1(chave1,chave2){
  document.form1.o08_funcao.value = chave1;
  document.form1.o52_descr.value = chave2;
  db_iframe_orcfuncao.hide();
}
function js_pesquisao08_subfuncao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcsubfuncao','func_orcsubfuncao.php?funcao_js=parent.js_mostraorcsubfuncao1|o53_subfuncao|o53_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_subfuncao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcsubfuncao','func_orcsubfuncao.php?pesquisa_chave='+document.form1.o08_subfuncao.value+'&funcao_js=parent.js_mostraorcsubfuncao','Pesquisa',false);
     }else{
       document.form1.o53_descr.value = ''; 
     }
  }
}
function js_mostraorcsubfuncao(chave,erro){
  document.form1.o53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_subfuncao.focus(); 
    document.form1.o08_subfuncao.value = ''; 
  }
}
function js_mostraorcsubfuncao1(chave1,chave2){
  document.form1.o08_subfuncao.value = chave1;
  document.form1.o53_descr.value = chave2;
  db_iframe_orcsubfuncao.hide();
}
function js_pesquisao08_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_anousu|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o08_programa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o08_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o08_programa.focus(); 
    document.form1.o08_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_programa.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao08_programa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_anousu','Pesquisa',true);
  }else{
     if(document.form1.o08_programa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o08_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_anousu.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_anousu.value = chave; 
  if(erro==true){ 
    document.form1.o08_programa.focus(); 
    document.form1.o08_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_programa.value = chave1;
  document.form1.o54_anousu.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao08_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_projativ.focus(); 
    document.form1.o08_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o08_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao08_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
     }else{
       document.form1.o55_descr.value = ''; 
     }
  }
}
function js_mostraorcprojativ(chave,erro){
  document.form1.o55_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_projativ.focus(); 
    document.form1.o08_projativ.value = ''; 
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  document.form1.o08_projativ.value = chave1;
  document.form1.o55_descr.value = chave2;
  db_iframe_orcprojativ.hide();
}
function js_pesquisao08_elemento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_anousu|o56_codele','Pesquisa',true);
  }else{
     if(document.form1.o08_elemento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_elemento.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_codele.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_codele.value = chave; 
  if(erro==true){ 
    document.form1.o08_elemento.focus(); 
    document.form1.o08_elemento.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_elemento.value = chave1;
  document.form1.o56_codele.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_elemento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_codele','Pesquisa',true);
  }else{
     if(document.form1.o08_elemento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_elemento.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_codele.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_codele.value = chave; 
  if(erro==true){ 
    document.form1.o08_elemento.focus(); 
    document.form1.o08_elemento.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_elemento.value = chave1;
  document.form1.o56_codele.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_localizadorgastos(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?funcao_js=parent.js_mostrappasubtitulolocalizadorgasto1|o11_sequencial|o11_codigo','Pesquisa',true);
  }else{
     if(document.form1.o08_localizadorgastos.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?pesquisa_chave='+document.form1.o08_localizadorgastos.value+'&funcao_js=parent.js_mostrappasubtitulolocalizadorgasto','Pesquisa',false);
     }else{
       document.form1.o11_codigo.value = ''; 
     }
  }
}
function js_mostrappasubtitulolocalizadorgasto(chave,erro){
  document.form1.o11_codigo.value = chave; 
  if(erro==true){ 
    document.form1.o08_localizadorgastos.focus(); 
    document.form1.o08_localizadorgastos.value = ''; 
  }
}
function js_mostrappasubtitulolocalizadorgasto1(chave1,chave2){
  document.form1.o08_localizadorgastos.value = chave1;
  document.form1.o11_codigo.value = chave2;
  db_iframe_ppasubtitulolocalizadorgasto.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ppadotacao','func_ppadotacao.php?funcao_js=parent.js_preenchepesquisa|o08_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppadotacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>