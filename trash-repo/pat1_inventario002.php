<?PHP
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_inventario_classe.php");
require_once("classes/db_inventarioanulado_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clinventario        = new cl_inventario;
$clinventarioanulado = new cl_inventarioanulado;
$clrotulo            = new rotulocampo;
$db_botao            = true;

$clinventario->rotulo->label();
$clinventarioanulado->rotulo->label();
$clrotulo->label("p58_codproc");
$clrotulo->label("ac08_descricao");

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
db_app::load("estilos.css");


$db_opcao = 3;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Cancelamento de abertura de inventário</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?php echo $Tt75_sequencial?>">
          <?php echo $Lt75_sequencial ?>
        </td>
        <td> 
          <?php
            db_input('t75_sequencial',10,$It75_sequencial,true,'text', 3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tt75_dataabertura ?>">
          <?php echo $Lt75_dataabertura ?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_dataabertura',
                          @$t75_dataabertura_dia,
                          @$t75_dataabertura_mes,
                          @$t75_dataabertura_ano,
                          true,
                          'text', 
                          3,
                          "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tt75_periodoinicial?>">
          <?php echo $Lt75_periodoinicial?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodoinicial',@$t75_periodoinicial_dia,@$t75_periodoinicial_mes,@$t75_periodoinicial_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt75_periodofinal?>">
          <?=@$Lt75_periodofinal?>
        </td>
        <td> 
          <?
            db_inputdata('t75_periodofinal',@$t75_periodofinal_dia,@$t75_periodofinal_mes,@$t75_periodofinal_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt75_exercicio?>">
          <?=@$Lt75_exercicio?>
        </td>
        <td> 
          <?
            //$t75_exercicio = db_getsession("DB_anousu");
            db_input('t75_exercicio',10,$It75_exercicio,true,'text', 3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt75_processo?>">
          <?
            db_ancora(@$Lt75_processo,"js_pesquisat75_processo(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t75_processo',10,$It75_processo,true,'hidden',$db_opcao," onchange='js_pesquisat75_processo(false);'");
            db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt75_acordocomissao?>">
          <?
            db_ancora(@$Lt75_acordocomissao,"js_pesquisat75_acordocomissao(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t75_acordocomissao',10,$It75_acordocomissao,true,'text',$db_opcao," onchange='js_pesquisat75_acordocomissao(false);'");
            db_input('ac08_descricao',39,$Iac08_descricao,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt75_observacao?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Lt75_observacao?></legend>
            <?
              db_textarea('t75_observacao',2,50,$It75_observacao,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt76_motivo?>" colspan="2">
          <fieldset class="separator">
            <legend>Motivo do Cancelamento:</legend>
            <?
              db_textarea('t76_motivo',2,50,$It76_motivo,true,'text',1,"")
            ?>
          </fieldset>
        </td>
      </tr>  
    </table>
  </fieldset>
  <input onclick='js_anular();' name="anular" type="button" id="db_opcao" value="Anular"  >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","t75_dataabertura",true,1,"t75_dataabertura",true);
</script>
<script>

js_pesquisa();

var sUrlRPC      = 'pat4_inventario.RPC.php';  
var oParametros  = new Object();


    
/**
 * funcao para incluir inventarios
 */
function js_anular() {

  var iInventario = $F("t75_sequencial");
  var sMotivo     = encodeURIComponent(tagString($F("t76_motivo"))); 

  if (iInventario == "") {

    alert(_M('patrimonial.patrimonio.pat1_inventario002.selecione_inventario'));
    return false;
  }
  
  if (sMotivo == '') {
    
    alert(_M('patrimonial.patrimonio.pat1_inventario002.digite_motivo_cancelamento'))
    return false;
  }
  
  oParametros.exec            = 'anular';  
  oParametros.iInventario     = iInventario;
  oParametros.sMotivo         = sMotivo;
  js_divCarregando(_M('patrimonial.patrimonio.pat1_inventario002.cancelando_inventario'),'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoAnular
                                             });   
}

function js_retornoAnular(oAjax) {


  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.sMessage.urlDecode());
  
  if (oRetorno.iStatus == 1) {

    $("t75_sequencial")    .value = "";
    $("t75_dataabertura")  .value = "";
    $("t75_periodoinicial").value = "";
    $("t75_periodofinal")  .value = "";
    $("t75_exercicio")     .value = "";
    $("p58_codproc")       .value = "";
    $("t75_processo")      .value = "";
    $("t75_acordocomissao").value = "";
    $("ac08_descricao")    .value = "";
    $("t75_observacao")    .value = "";
    $("t76_motivo")        .value = "";
    
  }


}


function js_pesquisat75_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.t75_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.t75_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.t75_processo.focus(); 
    document.form1.t75_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.t75_processo.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisat75_acordocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_acordocomissao','func_acordocomissao.php?funcao_js=parent.js_mostraacordocomissao1|ac08_sequencial|ac08_descricao','Pesquisa',true);
  }else{
     if(document.form1.t75_acordocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_acordocomissao','func_acordocomissao.php?pesquisa_chave='+$F('t75_acordocomissao')+'&funcao_js=parent.js_mostraacordocomissao','Pesquisa',false);
     }else{
       document.form1.ac08_descricao.value = ''; 
     }
  }
}
function js_mostraacordocomissao(chave,erro){
  document.form1.ac08_descricao.value = chave; 
  if(erro==true){ 
    document.form1.t75_acordocomissao.focus(); 
    document.form1.t75_acordocomissao.value = ''; 
  }
}
function js_mostraacordocomissao1(chave1,chave2){
  document.form1.t75_acordocomissao.value = chave1;
  document.form1.ac08_descricao.value = chave2;
  db_iframe_acordocomissao.hide();
}
function js_pesquisa(){

  var sQuery  = "func_inventario.php?";
      sQuery += "lAnulados=0&funcao_js=parent.js_preenchepesquisa";
      sQuery += "|t75_sequencial"    ;
      sQuery += "|t75_dataabertura"  ;
      sQuery += "|t75_periodoinicial";  
      sQuery += "|t75_periodofinal"  ;
      sQuery += "|t75_processo"      ;
      sQuery += "|t75_exercicio"     ;
      sQuery += "|t75_acordocomissao";
      sQuery += "|ac08_descricao"    ;
      sQuery += "|t75_observacao"    ;
      
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_inventario',
                      sQuery,
                      'Pesquisa',
                      true);
}
function js_preenchepesquisa(iSequencial, 
                             dDataAbertura, 
                             dPeriodoInicial, 
                             dPeriodoFinal, 
                             iProcesso, 
                             iExercicio,
                             iComissao,
                             sComissao,
                             sObservacao
                            ){

  //alert(sComissao);
  dDataAbertura   = js_formatar(dDataAbertura  ,'d','');
  dPeriodoInicial = js_formatar(dPeriodoInicial,'d','');
  dPeriodoFinal   = js_formatar(dPeriodoFinal  ,'d','');
  
  $("t75_sequencial")    .value = iSequencial;
  $("t75_dataabertura")  .value = dDataAbertura
  $("t75_periodoinicial").value = dPeriodoInicial;
  $("t75_periodofinal")  .value = dPeriodoFinal;
  $("p58_codproc")       .value = iProcesso;
  $("t75_exercicio")     .value = iExercicio;
  $("t75_acordocomissao").value = iComissao;
  $("ac08_descricao")    .value = sComissao;
  $("t75_observacao")    .value = sObservacao;
  
  db_iframe_inventario.hide();
}
</script>
<script>

$("t75_sequencial").addClassName("field-size2");
$("t75_dataabertura").addClassName("field-size2");
$("t75_periodoinicial").addClassName("field-size2");
$("t75_periodofinal").addClassName("field-size2");
$("t75_exercicio").addClassName("field-size2");
$("p58_codproc").addClassName("field-size2");
$("t75_acordocomissao").addClassName("field-size2");
$("ac08_descricao").addClassName("field-size7");

</script>