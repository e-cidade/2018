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
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
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
    <legend>Relatório de Inventários</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?php echo $Tt75_periodoinicial?>">
           <?php echo $Lt75_periodoinicial?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodoinicial',@$t75_periodoinicial_dia,@$t75_periodoinicial_mes,@$t75_periodoinicial_ano,true,'text', 1 ,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tt75_periodofinal?>">
           <?php echo $Lt75_periodofinal?>
        </td>
        <td> 
          <?php
            db_inputdata('t75_periodofinal',@$t75_periodofinal_dia,@$t75_periodofinal_mes,@$t75_periodofinal_ano,true,'text', 1,"")
          ?>
        </td>
      </tr>  
      <tr>
        <td nowrap title="Intervalos de Inventário">
           <strong>
           <?php 
             db_ancora("Inventário Inicial:","js_pesquisaInicial();", 1);
           ?>  
           </strong>
        </td>
        <td> 
          <?
            db_input('inicial',10,$It75_exercicio,true,'text', 3,"")
          ?>    
        </td>
      </tr>
      <tr>
        <td nowrap title="Intervalos de Inventário">
           <strong>
             <?php 
               db_ancora("Inventário Final:","js_pesquisaFinal();", 1);
             ?> 
           </strong>
        </td>
        <td> 
          <?
            db_input('final',10,$It75_exercicio,true,'text', 3,"")
          ?>    
        </td>
      </tr>
      <tr>
        <td colspan="2">
        <fieldset class="separator">
          <legend><strong>Situação do Inventário</strong></legend>
            <div id='ctnSituacao'>
              <input type="checkbox" id='aberto'     value='1' /> Ativo
              <input type="checkbox" id='anulado'    value='2' /> Anulado
              <input type="checkbox" id='processado' value='3' /> Processado
            </div>
        </fieldset> 
        </td>
      </tr>   
    </table>
  </fieldset>
  <input onclick='js_emite();' name="emitir" type="button" id="db_opcao" value="Emitir"  >
  <input name="limpar" type="reset" id="limpar" value="Limpar"  >
</form>

<?PHP  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script>
        
function js_emite(){
  
  var dtDataInicial      = js_formatar($F("t75_periodoinicial"), "d");
  var dtDataFinal        = js_formatar($F("t75_periodofinal")  , "d");
  var iInventarioInicial = $F("inicial");
  var iInventarioFinal   = $F("final");
  var iAberto            = $F("aberto");
  var iAnulado           = $F("anulado");
  var iProcessado        = $F("processado");
  var sFonte             = "pat2_inventarios002.php";

  sQuery  = "?dtDataInicial="       + dtDataInicial;
  sQuery += "&dtDataFinal="         + dtDataFinal;
  sQuery += "&iInventarioInicial="  + iInventarioInicial;
  sQuery += "&iInventarioFinal="    + iInventarioFinal;
  sQuery += "&lAberto="             + iAberto;
  sQuery += "&lAnulado="            + iAnulado;
  sQuery += "&lProcessado="         + iProcessado;  

  if (!js_ComparaDatas(dtDataInicial, dtDataFinal) ){
    return false;
  }
  if (iInventarioInicial > iInventarioFinal) {

    alert(_M("patrimonial.patrimonio.pat2_inventarios001.inventario_final_maior_inicial"));
    return false;
  }

  if (dtDataInicial == "" && dtDataFinal == "" &&iInventarioInicial == "" && iInventarioFinal == "") {

    if ( confirm(_M("patrimonial.patrimonio.pat2_inventarios001.nenhum_filtro_selecionado"))) {
      
      jan = window.open(sFonte+sQuery, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      
    } else {
      return false;
    } 

  }
  jan = window.open(sFonte+sQuery, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

/*
 * funcao para validar datas maiores e menores
 */
function js_ComparaDatas(dInicial, dFinal) {
  
  var data1 = $F('t75_periodoinicial');
  var data2 = $F('t75_periodofinal');

  if (data1 != "" && data2 != ""){
    
    var nova_data1 = parseInt(data1.split("/")[2].toString() + data1.split("/")[1].toString() + data1.split("/")[0].toString());
    var nova_data2 = parseInt(data2.split("/")[2].toString() + data2.split("/")[1].toString() + data2.split("/")[0].toString());
    
    if (nova_data2 > nova_data1) {
      return true;
    }else if (nova_data1 == nova_data2) {
    
      alert(_M("patrimonial.patrimonio.pat2_inventarios001.data_inicial_final_diferentes"));
      return false;
    }else {
    
      alert(_M("patrimonial.patrimonio.pat2_inventarios001.intervalo_inicial_menor_intervalo_final"));
      return false;
  }

  } else {
    return true;
  }    
 
}

function js_pesquisaInicial(){

  var sQuery  = "func_inventario.php?";
      sQuery += "funcao_js=parent.js_preenchepesquisaInicial";
      sQuery += "|t75_sequencial"    ;
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_inventario',
                      sQuery,
                      'Pesquisa',
                      true);
}
function js_preenchepesquisaInicial(iSequencial){

  $("inicial")    .value = iSequencial;
  db_iframe_inventario.hide();
}

function js_pesquisaFinal(){

  var sQuery  = "func_inventario.php?";
      sQuery += "funcao_js=parent.js_preenchepesquisaFinal";
      sQuery += "|t75_sequencial"    ;
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_inventario',
                      sQuery,
                      'Pesquisa',
                      true);
}
function js_preenchepesquisaFinal(iSequencial){

  $("final")    .value = iSequencial;
  db_iframe_inventario.hide();
}

</script>
<script>

$("t75_periodoinicial").addClassName("field-size2");
$("t75_periodofinal").addClassName("field-size2");
$("inicial").addClassName("field-size2");
$("final").addClassName("field-size2");

</script>