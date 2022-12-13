<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$clppadotacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o54_anousu");
$clrotulo->label("o56_codele");
$clrotulo->label("o55_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o54_anousu");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o56_codele");
$clrotulo->label("o11_codigo");
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_anoinicio");
$clrotulo->label("o01_anofinal");
$clrotulo->label("o01_descricao");
$clrotulo->label("o05_ppaversao"); 
$clrotulo->label("o05_valor"); 
?>
<center>
<form name="form1" method="post" action="">
<table>
<tr>
<td>
<fieldset>
<table border="0">
  <tr>
   
   <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <?
                db_ancora(@$Lo05_ppaversao,"js_pesquisao05_ppalei(true);",3);
                ?>
              </td>
              <td> 
                <?
                db_input('o05_ppaversao',10,$Io05_ppaversao,true,'text',3," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'')
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
      db_input('o08_orgao',10,$Io08_orgao,true,'text',$db_opcao," onchange='js_pesquisao08_orgao(false);'");
      db_input('o40_descr',40,$Io40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
	<?
	?>
  <tr>
    <td nowrap title="<?=@$To08_unidade?>">
       <?
       db_ancora(@$Lo08_unidade,"js_pesquisao08_unidade(true);",1);
       ?>
    </td>
    <td> 
      <?
      db_input('o08_unidade',10,$Io08_unidade,true,'text',1," onchange='js_pesquisao08_unidade(false);'");
      db_input('o41_descr',40,$Io41_descr,true,'text',3,'')
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
db_input('o54_descr',40,$Io54_anousu,true,'text',3);
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
       ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
<input name="alterar"   type="button" id="db_opcao"  value="Alterar"   onclick="js_alterarDotacoes()" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
</body>
</html>

<script>
function js_pesquisao08_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcorgao','func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_orgao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcorgao','func_orcorgao.php?pesquisa_chave='+document.form1.o08_orgao.value+'&funcao_js=parent.js_mostraorcorgao','Pesquisa',false);
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

function js_pesquisao08_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcunidade','func_orcunidade.php?pesquisa_chave='+document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade','Pesquisa',false);
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
function js_pesquisao08_unidade(mostra) {
  
  if ($F('o08_orgao') == '') {
    
    alert('Antes de escolher uma Unidade, informe um orgão!');
    return false;
    
  } 
  var sFiltro = 'orgao='+$F('o08_orgao');
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                        'db_iframe_orcunidade',
                        'func_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_unidade|o41_descr&'+sFiltro,
                        'Unidades',
                        true
                       );
  }else{
  
     if(document.form1.o08_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                            'db_iframe_orcunidade',
                            'func_orcunidade.php?pesquisa_chave='+
                             document.form1.o08_unidade.value+'&funcao_js=parent.js_mostraorcunidade&'+sFiltro,
                            'Pesquisa',
                            false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcfuncao','func_orcfuncao.php?funcao_js=parent.js_mostraorcfuncao1|o52_funcao|o52_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_funcao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcfuncao','func_orcfuncao.php?pesquisa_chave='+document.form1.o08_funcao.value+'&funcao_js=parent.js_mostraorcfuncao','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcsubfuncao','func_orcsubfuncao.php?funcao_js=parent.js_mostraorcsubfuncao1|o53_subfuncao|o53_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_subfuncao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcsubfuncao','func_orcsubfuncao.php?pesquisa_chave='+document.form1.o08_subfuncao.value+'&funcao_js=parent.js_mostraorcsubfuncao','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprograma','func_orcprograma.php?funcao_js=parent.js_mostraorcprograma1|o54_programa|o54_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_programa.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprograma','func_orcprograma.php?pesquisa_chave='+document.form1.o08_programa.value+'&funcao_js=parent.js_mostraorcprograma','Pesquisa',false);
     }else{
       document.form1.o54_descr.value = ''; 
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_descr.value = chave; 
  if(erro==true){ 
    document.form1.o08_programa.focus(); 
    document.form1.o08_programa.value = ''; 
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_programa.value = chave1;
  document.form1.o54_descr.value = chave2;
  db_iframe_orcprograma.hide();
}
function js_pesquisao08_projativ(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_anousu|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprojativ','func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_projativ.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcprojativ','func_orcprojativ.php?pesquisa_chave='+document.form1.o08_projativ.value+'&funcao_js=parent.js_mostraorcprojativ','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_descr','Pesquisa',true);
  }else{
     if(document.form1.o08_elemento.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                            'db_iframe_orcelemento',
                            'func_orcelemento.php?&tipo_pesquisa=1&pesquisa_chave='
                            +document.form1.o08_elemento.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.o08_elemento.focus(); 
    document.form1.o08_elemento.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_elemento.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_localizadorgastos(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?funcao_js=parent.js_mostrappasubtitulolocalizadorgasto1|o11_sequencial|o11_descricao','Pesquisa',true);
  }else{
     if(document.form1.o08_localizadorgastos.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?pesquisa_chave='+document.form1.o08_localizadorgastos.value+'&funcao_js=parent.js_mostrappasubtitulolocalizadorgasto','Pesquisa',false);
     }else{
       document.form1.o11_descricao.value = ''; 
     }
  }
}
function js_mostrappasubtitulolocalizadorgasto(chave,erro){
  document.form1.o11_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o08_localizadorgastos.focus(); 
    document.form1.o08_localizadorgastos.value = ''; 
  }
}
function js_mostrappasubtitulolocalizadorgasto1(chave1,chave2){
  document.form1.o08_localizadorgastos.value = chave1;
  document.form1.o11_descricao.value = chave2;
  db_iframe_ppasubtitulolocalizadorgasto.hide();
}
function js_pesquisa() {
  
  var sParam = 'o40_orgao|o41_unidade|o52_funcao|o53_subfuncao|o54_programa|o55_projativ|db_o08_ppaversao';  
  js_OpenJanelaIframe('top.corpo.iframe_ppadotacao', 
                      'db_iframe_ppadotacao',
                      'func_ppadotacaoaltera.php?lEstimativas=true&'+
                      'funcao_js=parent.js_preenchepesquisa|'+sParam,
                      'Pesquisa de Dotações',
                      true
                      ,0);
}
function js_preenchepesquisa(o40_orgao, o41_unidade, o52_funcao, o53_subfuncao, o54_programa, o55_projativ,o08_ppaversao){
  
  if (db_iframe_ppadotacao) {
    db_iframe_ppadotacao.hide();
  }
  oGrupoOriginal = new Object();
  js_divCarregando('Aguarde, Carregando estimativas','msgbox');
  oGrupoOriginal.exec          = "getElementosFromAcao";
  oGrupoOriginal.o40_orgao     = o40_orgao;
  oGrupoOriginal.o41_unidade   = o41_unidade;
  oGrupoOriginal.o52_funcao    = o52_funcao;
  oGrupoOriginal.o53_subfuncao = o53_subfuncao;
  oGrupoOriginal.o54_programa  = o54_programa;
  oGrupoOriginal.o55_projativ  = o55_projativ;
  oGrupoOriginal.o08_ppaversao = o08_ppaversao;
  var oRequest         = new Ajax.Request(
                                          'orc4_ppadotacaoalteracaoRPC.php',
                                          {
                                           method    : 'post', 
                                           parameters: 'json='+Object.toJSON(oGrupoOriginal), 
                                           onComplete: js_retornoPesquisa 
                                          }
                                         );  
  
}

function js_retornoPesquisa(oRequest) {
  
  js_removeObj('msgbox');
  $('db_opcao').disabled = false;
  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {
    
    var a = $$('input[type=text],select');
    a.each(function(input,id) {
       
       var valor   = eval("oRetorno."+input.id);
       if (valor    != null ) {
         input.value = valor.urlDecode();
       }
       
     });
     top.corpo.iframe_ppadotacaoele.iAnoIni = oRetorno.o01_anoinicio; 
     top.corpo.iframe_ppadotacaoele.iAnoFim = oRetorno.o01_anofinal;
     top.corpo.iframe_ppadotacaoele.$('o05_anoreferencia').onfocus = function(event) {
     if (this.value == "") {
       this.value = oRetorno.o01_anoinicio;
      }
     }
     
     if (!oRetorno.leivalida) {
      $('db_opcao').disabled = true;
    }  
  }
  top.corpo.iframe_ppadotacaoele.addValoresGrid(oRetorno.itens); 
}

function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                        'db_iframe_ppalei',
                        'func_ppaversao.php?funcao_js=parent.js_mostrappalei1|o119_sequencial|o01_descricao',
                        'Pesquisa de Versões para o PPA',
                        true);
  }else{
     if(document.form1.o05_ppaversao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                            'db_iframe_ppalei',
                            'func_ppaversao.php?pesquisa_chave='
                            +document.form1.o05_ppaversao.value+'&funcao_js=parent.js_mostrappalei',
                            'Leis PPA',
                            false);
     }else{
       document.form1.o01_descricao.value = ''; 
     }
  }
}
function js_mostrappalei(chave, erro) {

  document.form1.o01_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o05_ppaversao.focus(); 
    document.form1.o05_ppaversao.value = ''; 
  }
  
}
function js_mostrappalei1(chave1,chave2){
  
  document.form1.o05_ppaversao.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
    
} 
function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                           'db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Recursos',true);
   }else{
       if(document.form1.o15_codigo.value != ''){ 
           js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                               'db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='
                                +document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec',
                                'Pesquisa',false);
       }else{
           document.form1.o15_descr.value = ''; 
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave; 
   if(erro==true){ 
      document.form1.o15_codigo.focus(); 
      document.form1.o15_codigo.value = ''; 
   } 
}

function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
}
if ($('o08_orgao').type=='select') {
  
  $('o08_orgao').style.width="95px";
  $('o08_orgaodescr').style.width="300px";
  $('o08_unidade').style.width="95px";
  $('o08_unidadedescr').style.width="300px";
  
}

function js_reload(){
 document.form1.o40_orgao.value = document.form1.o08_orgao.value;
 document.form1.submit();	
}

function js_alterarDotacoes() {
  
  var iTotalDotacoes  = top.corpo.iframe_ppadotacaoele.oGridPPA.getElementsByClass('dotacaonormal').length;
  if (iTotalDotacoes == 0) {
  
    alert('Não há dotações que podem ser modificadas. todas as dotações foram estimadas automaticamente!');
    return false;
    
  }
  var sMsg  = 'As modificações realizadas refletiram em '+iTotalDotacoes+' dotações.\n';
      sMsg += 'Confirmar as modificações?';
      
  if (!confirm(sMsg)) {
    return false;   
  }   
  
  oParam = new Object();
  js_divCarregando('Aguarde, Carregando estimativas','msgbox');
  oParam.exec               = "alterarAcoesGrupo";
  oParam.o40_orgao          = oGrupoOriginal.o40_orgao;
  oParam.o41_unidade        = oGrupoOriginal.o41_unidade;
  oParam.o52_funcao         = oGrupoOriginal.o52_funcao;
  oParam.o53_subfuncao      = oGrupoOriginal.o53_subfuncao;
  oParam.o54_programa       = oGrupoOriginal.o54_programa;
  oParam.o55_projativ       = oGrupoOriginal.o55_projativ;
  oParam.o08_ppaversao      = oGrupoOriginal.o08_ppaversao;
  //oParam.o08_concarpeculiar = oGrupoOriginal.o08_concarpeculiar;
  
  oParam.oAlterar                     = new Object();
  oParam.oAlterar.o08_orgao           = $F('o08_orgao');
  oParam.oAlterar.o08_unidade         = $F('o08_unidade');
  oParam.oAlterar.o08_funcao          = $F('o08_funcao');
  oParam.oAlterar.o08_subfuncao       = $F('o08_subfuncao');
  oParam.oAlterar.o08_programa        = $F('o08_programa');
  oParam.oAlterar.o08_projativ        = $F('o08_projativ');
  //oParam.oAlterar.o08_concarpeculiar  = $F('o08_concarpeculiar');
  
  var oRequest         = new Ajax.Request(
                                          'orc4_ppadotacaoalteracaoRPC.php',
                                          {
                                           method    : 'post', 
                                           parameters: 'json='+Object.toJSON(oParam), 
                                           onComplete: js_retornoAlterarDotacoes 
                                          }
                                         );  
 
    
}
function js_retornoAlterarDotacoes(oRequest) {
  
  js_removeObj('msgbox');
  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {
  
    alert('Alteração efetuada com sucesso!');
    js_preenchepesquisa(
                        oParam.oAlterar.o08_orgao,
                        oParam.oAlterar.o08_unidade,
                        oParam.oAlterar.o08_funcao,
                        oParam.oAlterar.o08_subfuncao,
                        oParam.oAlterar.o08_programa,
                        oParam.oAlterar.o08_projativ,
                        $F('o05_ppaversao') 
                       );
     
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

js_pesquisa();
</script>