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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clinventario = new cl_inventario;
//$db_opcao     = 1;
$db_botao     = true;

$clinventario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("ac08_descricao");

$t75_dataabertura_dia = date("d",db_getsession("DB_datausu"));
$t75_dataabertura_mes = date("m",db_getsession("DB_datausu"));
$t75_dataabertura_ano = date("Y",db_getsession("DB_datausu"));
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
db_app::load("estilos.css");

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
    <legend>Inclusão de abertura de inventário</legend>
    <table class="form-container">
      <tr>
        <td title="<?php echo $Tt75_sequencial?>">
          <?php echo $Lt75_sequencial ?>
        </td>
        <td> 
          <?php
            db_input('t75_sequencial',10,$It75_sequencial,true,'text', 3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?php echo $Tt75_dataabertura ?>">
          <?php echo $Lt75_dataabertura ?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_dataabertura',
                          $t75_dataabertura_dia,
                          $t75_dataabertura_mes,
                          $t75_dataabertura_ano,
                          true,
                          'text', 
                          3,
                          "")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?php echo $Tt75_periodoinicial?>">
          <?php echo $Lt75_periodoinicial?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodoinicial',@$t75_periodoinicial_dia,@$t75_periodoinicial_mes,@$t75_periodoinicial_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt75_periodofinal?>">
          <?=@$Lt75_periodofinal?>
        </td>
        <td> 
          <?
            db_inputdata('t75_periodofinal',@$t75_periodofinal_dia,@$t75_periodofinal_mes,@$t75_periodofinal_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt75_exercicio?>">
          <?=@$Lt75_exercicio?>
        </td>
        <td> 
          <?
            $t75_exercicio = db_getsession("DB_anousu");
            db_input('t75_exercicio',10,$It75_exercicio,true,'text', 3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt75_processo?>">
          <?
            db_ancora(@$Lt75_processo,"js_pesquisat75_processo(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t75_processo',10,$It75_processo,true,'text',$db_opcao," onchange='js_pesquisat75_processo(false);'");
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tt75_acordocomissao?>">
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
        <td title="<?=@$Tt75_observacao?>" colspan="2">
          <fieldset class="separator">
            <legend>Observação</legend>
            <?
              db_textarea('t75_observacao',5,70,$It75_observacao,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input onclick='js_incluir();' name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="limpar" type="reset" id="limpar" value="Limpar"  >
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

var sUrlRPC      = 'pat4_inventario.RPC.php';  
var oParametros  = new Object();

/**
 * funcao para incluir inventarios
 */
function js_incluir() {

  var iSequencial     = $F("t75_sequencial"); 
  var dAbertura       = $F("t75_dataabertura");
  var dPeriodoInicial = $F("t75_periodoinicial");
  var dPeriodoFinal   = $F("t75_periodofinal");
  var iExercicio      = $F("t75_exercicio");
  var iProcesso       = $F("t75_processo");
  var iComissao       = $F("t75_acordocomissao");
  var sObservacao     = encodeURIComponent(tagString($F("t75_observacao")));


  if (iSequencial != "") {

		  
    alert(_M('patrimonial.patrimonio.pat1_inventario001.impossivel_incluir',{codigo: iSequencial}));
    return false;
  }
  
  if (dPeriodoInicial == '') {
    
    alert(_M('patrimonial.patrimonio.pat1_inventario001.selecione_periodo_inicial'))
    return false;
  }
  
  if (dPeriodoFinal == '') {
    
    alert(_M('patrimonial.patrimonio.pat1_inventario001.selecione_periodo_final'))
    return false;
  }  

  if (!js_ComparaDatas(dPeriodoInicial, dPeriodoFinal) ){
    return false;
  }
  
  if (iComissao == '') {
    
    alert(_M('patrimonial.patrimonio.pat1_inventario001.selecione_comissao'))
    return false;
  }
  
  oParametros.exec            = 'incluir';  
  oParametros.dAbertura       = dAbertura      ;
  oParametros.dPeriodoInicial = dPeriodoInicial;
  oParametros.dPeriodoFinal   = dPeriodoFinal  ;
  oParametros.iExercicio      = iExercicio     ;
  oParametros.iProcesso       = iProcesso      ;
  oParametros.iComissao       = iComissao      ;
  oParametros.sObservacao     = sObservacao    ;

  js_divCarregando(_M('patrimonial.patrimonio.pat1_inventario001.incluindo'),'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoIncluir
                                             });   
  

}
function js_retornoIncluir(oAjax) {


  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  
  if (oRetorno.iStatus == 1) {

    $("t75_sequencial").value = oRetorno.iSequencial;
  }


}
////////////////////
/*
 * funcao para validar datas maiores e menores
 */
function js_ComparaDatas(dInicial, dFinal) {
  
  var data1 = $F('t75_periodoinicial');
  var data2 = $F('t75_periodofinal');
  
  var nova_data1 = parseInt(data1.split("/")[2].toString() + data1.split("/")[1].toString() + data1.split("/")[0].toString());
  var nova_data2 = parseInt(data2.split("/")[2].toString() + data2.split("/")[1].toString() + data2.split("/")[0].toString());
  
  if (nova_data2 > nova_data1) {
    return true;
  }else if (nova_data1 == nova_data2) {
  
    alert(_M('patrimonial.patrimonio.pat1_inventario001.data_inicial_diferente_data_final'));
    return false;
  }else {
  
    alert(_M('patrimonial.patrimonio.pat1_inventario001.data_inicial_menor_data_final'));
    return false;
  }
 
}

    
function js_pesquisat75_processo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.t75_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.t75_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.t75_processo.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.t75_processo.value = chave; 
  if(erro==true){ 
    document.form1.t75_processo.focus(); 
    document.form1.t75_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.t75_processo.value = chave1;
  //document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisat75_acordocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_acordocomissao','func_acordocomissao.php?funcao_js=parent.js_mostraacordocomissao1|ac08_sequencial|ac08_descricao','Pesquisa',true);
  }else{
     if(document.form1.t75_acordocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_acordocomissao','func_acordocomissao.php?pesquisa_chave='+document.form1.t75_acordocomissao.value+'&funcao_js=parent.js_mostraacordocomissao','Pesquisa',false);
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
  js_OpenJanelaIframe('top.corpo','db_iframe_inventario','func_inventario.php?funcao_js=parent.js_preenchepesquisa|t75_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_inventario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t75_sequencial").addClassName("field-size2");
$("t75_dataabertura").addClassName("field-size2");
$("t75_periodoinicial").addClassName("field-size2");
$("t75_periodofinal").addClassName("field-size2");
$("t75_exercicio").addClassName("field-size2");
$("t75_processo").addClassName("field-size2");
$("t75_acordocomissao").addClassName("field-size2");
$("ac08_descricao").addClassName("field-size7");

</script>