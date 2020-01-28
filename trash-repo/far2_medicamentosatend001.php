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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo; 
$clrotulo->label("fa04_i_unidadess");
$clrotulo->label("fa06_i_matersaude");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <?
    db_app::load("strings.js");
    db_app::load("/widgets/dbautocomplete.widget.js");
    db_app::load("prototype.js");
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body  bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <form name="form1" method="post" action="">
      <br>
      <br>
      <center> 
        <fieldset style="width:50%">
          <legend>
              <b>
                Filtros:
              </b>
            </a>

          </legend>
            
             <table>
             <tr>
              <td align="left">
                <b>Data Inicial:</b>
                <?
                  db_inputdata('data_inicio', '', '', '', true, 'text', 1, "");
                ?>
              </td>
              <td>
                <?
                 echo' <b>Data Final:</b> ';
                  db_inputdata('data_fim', '', '', '', true, 'text', 1, "");
                 ?>
              </td>
            </tr>
            <tr>
              <td align="left">
                <b>Idade Inicial:</b>
                <?
                  db_input('idade_inicio', 3, "", true, 'text', 1, "", "", "", "", 3);
                ?>
              </td>
              <td>
                 <b>Idade Final:</b>
                <?
                  db_input('idade_fim', 3, "", true, 'text', 1, "", "", "", "", 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <b>Tipo Retirada:</b>
                <?
                 $aRetirada = array("1"=>"NORMAL", "2"=>"NÃO PADRONIZADA"); 
                 db_select('aRetirada', $aRetirada, true, "");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset style="width:50%">
          <legend>
            <a  id='esconderAlmoxarifado' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeAlmoxarifado('');">
              <b>
                Almoxarifado: 
              </b>
              <img src='imagens/seta.gif' id='toggleAlmoxarifado' border='0'>
            </a>

          </legend>
          <table width="100%" border="0"  id="tabAlmoxarifado" style="display:none">
            <tr>
              <td>
                <?
                  db_ancora("<b>Almoxarifado</b>", "js_pesquisafa04_i_unidades(true);", "");
                ?>
              
              
                <?
                  db_input('fa04_i_unidades',
                           10,
                           @$Ifa04_i_unidadess,
                           false,
                           'text',
                           1,
                           " onchange='js_pesquisafa04_i_unidades(false);'"
                          );
                  db_input('descrdepto', 50, @$Idescrdepto, false, 'text', 3, "");?>
                  </td>
                  <td>
                  <input type='button'value='Lan&ccedil;ar'name='lancar_unidade' id='lancar_unidade' 
                  onclick="js_incluir_item_unidade();">
                  </td>
                
                  </tr>
                  <tr>
           <td colspan='2'> 
                <select multiple size='6'
                        name='select_unidade' 
                        id='select_unidade' 
                        style="width: 100%;" 
                        onDblClick="js_excluir_item_unidade();">
                </select>
           </td> 
            </tr>
          </table>
        </fieldset>
        <fieldset style="width:50%">
          <legend>
             <a  id='escondermedicamentos'  
                 style="-moz-user-select: none;cursor: pointer" onClick="js_escondeMedicamentos('');">
              <b>
                Medicamentos:
              </b>
              <img src='imagens/seta.gif' id='toggleMedicamentos' border='0'>
            </a>
          </legend>
            <table width="100%" id='tabMedicamentos' style="display:none" >
             <tr>
              <td>             
                  <?
                    db_ancora(@$Lfa06_i_matersaude, "js_pesquisafa01_i_medicamento(true);", "");
                  ?>
              
               
                  <?
                    db_input('fa01_i_codigo',
                             10,
                             @$Ifa01_i_codigo,
                             true,
                             'text',
                             1,
                             " onchange='js_pesquisafa01_i_medicamento(false);'");
                             db_input('m60_descr', 50, @$Im60_descr, false, 'text', 3, ''
                            );
                  ?>
                  </td>
                  <td>
                  <input type='button' 
                         value='Lan&ccedil;ar' 
                         name='lancar_medicamento' 
                         id='lancar_medicamento'
                         onclick="js_incluir_item_medicamento();">
                  </td>
              </tr>
              <tr>
              <td colspan= '2'>
                  <select multiple 
                          size='6'
                          name='select_medicamento[]'
                          id='select_medicamento'
                          style="width: 100%;"
                          onDblClick="js_excluir_item_medicamento();">
                  </select> 
                  </td>
              </tr>
            </table>
        </fieldset>  
      </center>  
    </form>
    <center>
      <table>
        <tr>
          <td>
            <input  name="emite2" id="emite2" type="button" value="Gerar Relatório" onclick="js_mandadados();" >
          </td>
          <td>
            <input name="limpar" id="limpar" type ="button" value="Limpar" onclick = "js_Limpa();">
          </td>
        </tr>
      </table>
    </center>
   
    <?
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
      db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
//autocomplite dos medicamentos
oAutoComplete2 = new dbAutoComplete(document.form1.m60_descr,'far4_retirada_autonomeRPC.php?tipo=1');
oAutoComplete2.setTxtFieldId(document.getElementById('fa01_i_codigo'));
oAutoComplete2.setHeightList(180);
oAutoComplete2.show();

//autocomplite dos Almoxarifados/departamentos
oAutoComplete1 = new dbAutoComplete(document.form1.descrdepto,'far4_retirada_autonomeRPC.php?tipo=7');
oAutoComplete1.setTxtFieldId(document.getElementById('fa04_i_unidades'));
oAutoComplete1.setHeightList(180);
oAutoComplete1.show();

/* 
*Function tem por objetivo apagar todos os campos do relatorio, 
*deixando todos aqueles que foram preenchidos em branco.
*/
function js_Limpa() {
 
  var iElem     = document.form1.select_medicamento.options.length;
  var iElem2    = document.form1.select_unidade.options.length;
  var sInput    = document.getElementsByTagName('input')
  var sSelect   = document.getElementsByTagName('select')
  var sTextarea = document.getElementsByTagName('textarea')
   
  //for percorre a quantidade de elementos do select de medicamentos e exclui todos.
  for (var iConta = 0; iConta < iElem; iConta++) { 
    document.form1.select_medicamento.remove (select_medicamento.length-1); 
  }  //fim do for que percorre os medicamentos selecionados

  // for que percorre a quantidade de elementos do select unidades e exclui todos.
  for(var iConta = 0; iConta <iElem2; iConta++) { 
  document.form1.select_unidade.remove (select_unidade.lenght-1);
  }//fim do for que percorre as unidades selecionadas

  //percorre os campos selects do formulario resetandos as informaçoes aleteradas
  for(var iConta=0; iConta<sSelect.length; iConta++) {  
    sSelect[iConta].selectedIndex = 0;
  }// fim do for que percorre os selects

//percorre todos os text areas do formulamrio apagando os seus valores
  for(var iConta=0; iConta<sTextarea.length; iConta++) { 
      sTextarea[iConta].value = '';
  }// fim do for que percorre todos os text areas do formulario
  
  //percorre todos os inputs do forumlario apagando os valores contidos
  for(var iConta=0; iConta<sInput.length; iConta++) { 
  
    switch (sInput[iConta].type){
      case 'text'     :   sInput[iConta].value      = ''; break;
      case 'radio'    :   sInput[iConta].checked    = ''; break;
      case 'checkbox' :   sInput[iConta].checked    = ''; break;
  
    }

  }// fim do for que percorre os inputs

}

function js_incluir_item_medicamento(){

  var texto=document.form1.m60_descr.value;
  var valor=document.form1.fa01_i_codigo.value;
  
  if (texto != "" && valor != "") {
    F = document.getElementById("select_medicamento");
    var tam = F.length;
    var testa = false;
  
    for (x=0; x < F.length; x++) {

      if (F.options[x].value == valor){

        testa = true;
        break;

      }

    }

    if (testa == false) {

      F.options[tam] = new Option(texto,valor);
      
      for (i=0; i < F.length; i++){
        F.options[i].selected = false;
      }

      F.options[tam].selected = true;
    }

  }

  document.form1.m60_descr.value            = '';
  document.form1.fa01_i_codigo.value        = '';

}

function js_excluir_item_medicamento(){

  F = document.getElementById("select_medicamento");
  
  if (F.length == 1) {
    F.options[0].selected = true;
  }

  var SI = F.selectedIndex;
  
  if (F.selectedIndex != -1 && F.length > 0) {

    F.options[SI] = null;
   
    if (SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}

function js_incluir_item_unidade(){

  texto = document.form1.descrdepto.value;
  valor = document.form1.fa04_i_unidades.value;
  
  if (texto != "" && valor != "") {

    F     = document.getElementById("select_unidade");
    tam   = F.length;
    testa = false;
  
    for (x=0; x < F.length; x++){

      if (F.options[x].value == valor) {

        testa = true;
        break;

      }

    }

    if (testa == false) {

      F.options[tam] = new Option(texto,valor);
      
      for (i=0; i < F.length; i++) {
        F.options[i].selected = false;
      }

      F.options[tam].selected = true;

    }
    
  }

  document.form1.descrdepto.value      = "";
  document.form1.fa04_i_unidades.value = "";

}

function js_excluir_item_unidade(){

  F = document.getElementById("select_unidade");

  if (F.length == 1) {
    F.options[0].selected = true;
  }
  var SI = F.selectedIndex;
  
  if (F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
    
    if (SI <= (F.length - 1)) {
      F.options[SI].selected = true;
    }

  }

}

function js_pesquisafa01_i_medicamento(mostra){
  
  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_far_matersaude',
                        'func_far_matersaude.php'+
                        '?funcao_js=parent.js_mostramatersaude1|fa01_i_codigo|m60_descr',
                        'Pesquisa',
                        true,
                        3);

  } else {

    if (document.form1.fa01_i_codigo.value != '') { 
  
      js_OpenJanelaIframe('',
                        'db_iframe_far_matersaude',
                        'func_far_matersaude.php'
                        +'?pesquisa_chave='+document.form1.fa01_i_codigo.value+'&funcao_js=parent.js_mostramatersaude',
                         'Pesquisa',false);
    } else {
      document.form1.m60_descr.value = "";
    }

  }

}

function js_mostramatersaude(chave, erro) {

  document.form1.m60_descr.value = chave; 
  
  if (erro == true) {

    document.form1.fa01_i_codigo.focus(); 
    document.form1.fa01_i_codigo.value = '';

  } else {
    document.form1.m60_descr.value=chave;
  }

}

function js_mostramatersaude1(chave1, chave2) {

  document.form1.fa01_i_codigo.value = chave1;
  document.form1.m60_descr.value     = chave2;
  db_iframe_far_matersaude.hide();
  
}

function js_pesquisafa04_i_unidades(mostra){
  if (mostra == true) {
  
    js_OpenJanelaIframe('',
                        'db_iframe_unidades',
                        'func_unidades.php?funcao_js=parent.js_mostraunidade1|sd02_i_codigo|descrdepto',
                        'Pesquisa',
                        true);
  } else {

    if(document.form1.fa04_i_unidades.value != '') {

       js_OpenJanelaIframe('',
                           'db_iframe_unidades',
                           'func_unidades.php?pesquisa_chave='+document.form1.fa04_i_unidades.value+
                           '&funcao_js=parent.js_mostraunidade',
                           'Pesquisa',
                       false);

     } else {
       document.descrdepto.value = '';
     }

   }

}

function js_mostraunidade(chave, erro) {

  document.form1.descrdepto.value = chave; 

  if (erro == true) {

    document.form1.fa04_i_unidades.focus(); 
    document.form1.fa04_i_unidades.value = '';

  }

}

function js_mostraunidade1(chave1, chave2) {

  document.form1.fa04_i_unidades.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();

}

function js_validadata(){

  if (document.form1.data_inicio.value == "" || document.form1.data_fim.value == "") {

    alert('ERRO: os campos data de inicio e de fim devem sem preenchidos.');
    document.form1.data_inicio.focus();
    return false;

  }

  inicio = new Date(document.form1.data_inicio.value.substring(6,10),
           document.form1.data_inicio.value.substring(3,5),
           document.form1.data_inicio.value.substring(0,2));
  fim    = new Date(document.form1.data_fim.value.substring(6,10),
           document.form1.data_fim.value.substring(3,5),
           document.form1.data_fim.value.substring(0,2));

  if (inicio > fim) {

    alert('ERRO: A data de Inicio esta maior que a data de Fim.');
    document.form1.data_inicio.value = '';
    document.form1.data_fim.value = '';
    document.form1.data_inicio.focus();
    return false;

  }

  iVal = 0;

  if (document.form1.idade_inicio.value != '' && document.form1.idade_inicio.value >= 0) {
    iVal++;
  }

  if (document.form1.idade_fim.value != '' && document.form1.idade_fim.value > 0) {
    iVal++;
  }

  if(iVal == 2){
    if(document.form1.idade_fim.value < document.form1.idade_inicio.value){
      iVal = 1;
    }

  }

  if(iVal == 1) {

    alert('Idade Invalida!');
    return false;

  }

  return true;

}

function js_validaenvio(){
 
/*if(document.form1.select_unidade.length <= 0){ não é obrigatorio informar o Almoxarifado

    alert('Selecione ao menos uma Unidade.');
    return false;

  }*/
  if (!js_validadata()) {
    return false;
  }
  return true;
}

function js_mandadados(){

  if (js_validaenvio()) {

    datas        = 'dIni='+document.form1.data_inicio.value+'&dFim='+document.form1.data_fim.value;
    unidades     = '&sDepartamentos=';
    vir          = '';
    for (x=0; x < document.form1.select_unidade.length; x++) {

      unidades += vir + document.form1.select_unidade.options[x].value;
      vir = ',';

    }

    if (document.form1.select_medicamento.length > 0) {

      medicamentos = '&sMedicamentos=';
      vir = '';
      
      for (x=0; x < document.form1.select_medicamento.length; x++) {

        medicamentos += vir + document.form1.select_medicamento.options[x].value;
        vir = ',';

      }

    } else {
      medicamentos = '';
    
    }
    
    
    if(document.form1.idade_inicio.value != ''){
      idade = '&iIdadeIni='+document.form1.idade_inicio.value+'&iIdadeFim='+idade_fim.value;
    } else {
      idade = ''
    }

    iPadrao = '&iPadrao='+document.form1.aRetirada.value;//Define o padrão do relatorio, 1-normal, 2-Não Padronizado
    jan = window.open('far2_medicamentosatend002.php?'+datas+unidades+medicamentos+idade+iPadrao,
                      '',
                      'width='+(screen.availWidth-5)+
                      ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

}

function js_escondeMedicamentos(lExibe) {

  if($('tabMedicamentos').style.display == '' && lExibe == true) {
   
    $('toggleMedicamentos').style.display = '';
    $('toggleMedicamentos').src           = 'imagens/setabaixo.gif';
    
    } else if ($('tabMedicamentos').style.display == '') {
    
      $('tabMedicamentos').style.display = 'none';
      $('toggleMedicamentos').src        = 'imagens/seta.gif';

    } else if ($('tabMedicamentos').style.display == 'none') {

      $('tabMedicamentos').style.display = '';
      $('toggleMedicamentos').src = 'imagens/setabaixo.gif';
    
    }

  }

function js_escondeAlmoxarifado(lExibe){

  if ($('tabAlmoxarifado').style.display == '' && lExibe == true) {
        
    $('tabAlmoxarifado').style.display = '';
    $('toggleAlmoxarifado').src        = 'imagens/setabaixo.gif';
      
  }  else if ($('tabAlmoxarifado').style.display == '') {
      
        $('tabAlmoxarifado').style.display = 'none';
        $('toggleAlmoxarifado').src        = 'imagens/seta.gif';
        
  } else if ($('tabAlmoxarifado').style.display == 'none') {
        
        $('tabAlmoxarifado').style.display = '';
        $('toggleAlmoxarifado').src        = 'imagens/setabaixo.gif';
        
  }

  function js_validaCamposMedicamentos() {

    alert("Dentro da function valida medicamentos");
    
    if ($('fa01_i_codigo').value.trim() == '') {
      
      alert("Por favor preencha todos os campos!");
      $('fa01_i_codigo').focus();    
      return false;

    }
    
    if ($('m60_descr').value.trim() == '') {
      
      alert("Por favor preencha todos os campos!");
      js_escondeMedicamentos(true);
      $('m60_descr').focus();
      return false;

    }

    return true;
    
  }
   
}

</script>