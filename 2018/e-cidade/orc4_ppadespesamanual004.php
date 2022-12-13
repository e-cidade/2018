<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_ppadotacao_classe.php");
require_once("dbforms/db_funcoes.php");
$clppadotacao = new cl_ppadotacao();
$oPost        = db_utils::postMemory($_POST);
$db_opcao     = 1;
$db_botao = true;
if (!isset($oPost->incluir) && !isset($oPost->alterar)){

   if (isset($_SESSION["dotacaoestimativa"])) {
     unset($_SESSION["dotacaoestimativa"]);
   }

} else {

  $db_opcao = 2;
  $_SESSION["dotacaoestimativa"] = $oPost;
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.ppadotacaoele.disabled=false;
         top.corpo.iframe_ppadotacaoele.location.href='orc4_ppadotacaoelementos004.php';
     ";
      echo "  parent.mo_camada('ppadotacaoele');";
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<?
//MODULO: orcamento
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
$clrotulo->label("o119_sequencial");
$clrotulo->label("o119_versao");
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_descricao");
?>
<form name="form1" method="post" action="">
<table>
<tr>
<td>
<fieldset>
<legend><b>Inclusão da Ação</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To08_sequencial?>">
       <?=@$Lo08_sequencial?>
    </td>
    <td>
<?
db_input('o08_sequencial',10,$Io08_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_ano?>">
       <?
       echo @$Lo08_ano;
       ?>
    </td>
    <td>
<?
db_input('o08_ano',10,$Io08_ano,true,'text',3," onchange='js_pesquisao08_ano(false);'")
?>
       <?
       ?>
    </td>
  </tr>
   <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'');
                db_input('codrel',40,'',true,'hidden',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Perspectiva:</b>
              </td>
              <td id='verppa'>

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
db_input('o54_descricao',40,$Io54_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To08_projativ?>">
       <?
       db_ancora("<b>Ação:</b>","js_pesquisao08_projativ(true);",$db_opcao);
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
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
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
       document.form1.o54_descricao.value = '';
     }
  }
}
function js_mostraorcprograma(chave,erro){
  document.form1.o54_descricao.value = chave;
  if(erro==true){
    document.form1.o08_programa.focus();
    document.form1.o08_programa.value = '';
  }
}
function js_mostraorcprograma1(chave1,chave2){
  document.form1.o08_programa.value = chave1;
  document.form1.o54_descricao.value = chave2;
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_anousu|o56_elemento','Pesquisa',true);
  }else{
     if(document.form1.o08_elemento.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_elemento.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_codele','Pesquisa',true);
  }else{
     if(document.form1.o08_elemento.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o08_elemento.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?funcao_js=parent.js_mostrappasubtitulolocalizadorgasto1|o11_sequencial|o11_codigo','Pesquisa',true);
  }else{
     if(document.form1.o08_localizadorgastos.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?pesquisa_chave='+document.form1.o08_localizadorgastos.value+'&funcao_js=parent.js_mostrappasubtitulolocalizadorgasto','Pesquisa',false);
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
  js_OpenJanelaIframe('top.corpo.iframe_ppadotacao','db_iframe_ppadotacao','func_ppadotacao.php?funcao_js=parent.js_preenchepesquisa|o08_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppadotacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisao05_ppalei(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                          'db_iframe_ppalei',
                          'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao&verificaano=1',
                          'Pesquisa de Leis para o PPA',
                          true);
    }else{
       if(document.form1.o05_ppalei.value != ''){
          js_OpenJanelaIframe('top.corpo.iframe_ppadotacao',
                              'db_iframe_ppalei',
                              'func_ppalei.php?pesquisa_chave='
                              +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei&verificaano=1',
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
      document.form1.o05_ppalei.focus();
      document.form1.o05_ppalei.value = '';
      js_limpaComboBoxPerspectivaPPA();
      } else {
        js_getVersoesPPA($F('o05_ppalei'), 2);
      }
  }

  function js_mostrappalei1(chave1,chave2){

    document.form1.o05_ppalei.value = chave1;
    document.form1.o01_descricao.value = chave2;
      js_getVersoesPPA(chave1, 2);
    db_iframe_ppalei.hide();

  }

js_drawSelectVersaoPPA($('verppa'));
<?
if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {
  echo "js_getVersoesPPA({$oPost->o05_ppalei}, 2);\n";

}
?>
</script>

</body>
</html>