<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);
$c12_contcearquivo = $oGet->c12_contcearquivo;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <style>

  table.borda {
    background-color:white;
  }
  
  table th{
    height:22px;
  }

  table thead  th:nth-child(1),
  #arquivosPad td:nth-child(1),
  #arquivos    td:nth-child(1)   {
    width: 30px;
  }

  table thead  th:nth-child(2),
  #arquivosPad td:nth-child(2),
  #arquivos    td:nth-child(2)   {
    width: 70px;
  }

  table thead  th:nth-child(3),
  #arquivosPad td:nth-child(3),
  #arquivos    td:nth-child(3)   {
    width: 350px;
  }

  table thead  th:nth-child(4),
  #arquivosPad td:nth-child(4),
  #arquivos    td:nth-child(4)  {
    
    white-space: nowrap;
    width      : 250px;

  }
/*
  #arquivosPad td:nth-child(4),
  #arquivos    td:nth-child(4) {
    width      : 260px;
  }*/

   table thead  th:nth-child(5)  {
    width: 10px;
  }
  </style>
</head>
<br><br>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
  <table align="center" border='0' width='50%' cellspacing='0' style='padding:0px' style='empty-cells:show'>
      <tr>
        <td colspan='2'>
        <?db_input('c12_contcearquivo',10,null,true,'hidden',1);?>
          <fieldset>
            <legend>
              <b>Arquivos de Informações Digitais</b>
            </legend>
            <table class="borda" width='100%' cellspacing='0'>
              <thead>
                <tr>
                  <th class='table_header'>
                    <b><a onclick='js_marca("arquivos")' style='cursor:pointer'>M</a></b>
                  </th>
                  <th class='table_header' width="60px">Código       </th>
                  <th class='table_header'>Descrição    </th>
                  <th class='table_header' width="160px">Grupo        </th>
                  <th class='table_header' width='18px'>&nbsp;</th>
                </tr>    
               </thead>
           </table>
         
         <div style="overflow-x: hidden; overflow-y: visible; height: 200px;">
           <table class="borda" width='100%' cellspacing='0'>
             <tbody id='arquivos' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
               <tr><td colspan='4'>&nbsp;</td></tr>
             </tbody>
           </table>
         </div>

        </fieldset>
      </td>
   </tr>
 </table>
 
<table align="center" border='0' width='50%' cellspacing='0' style='empty-cells:show'>
  <tr>
    <td colspan='2'>
      
      <fieldset>
        <legend>Arquivos SIAPC/PAD</legend>

          <table width='100%' cellspacing='0' class="borda">
            <thead>
              <tr>
                <th class='table_header' >
                  <b><a onclick='js_marca("arquivosPad");' style='cursor:pointer'>M</a></b>
                </th>
                <th class='table_header' width="60px" >Código       </th>
                <th class='table_header'>Descrição    </th>
                <th class='table_header' width="160px" >Grupo        </th>
                <th class='table_header' width='18px'>&nbsp;</th>
              </tr>    
            </thead>
          </table>
          
          <div style="overflow-x: hidden; overflow-y: visible; height: 200px;">
            <table width='100%' cellspacing='0' class="borda">
              <tbody id='arquivosPad' style='background-color:white'>
                <tr><td colspan='4'>&nbsp;</td></tr>
              </tbody>
            </table>
          </div>

        </fieldset>
      </td>
   </tr>
   <tr>
     <td colspan="2" align = "center"> 
       <input  name="btnProcessar" id="btnProcessar" type="button" value="Processar" onClick="js_processar();" >
     </td>
   </tr>
 </table>
 
 
</form>
</body>
</html>
<script>

//
// Funcao para montar a grid com os arquivos.
//
js_consultaArquivos();

function js_processar() {

  var aArqs          = new Array();
  var aArqsPAD       = new Array();
  var iCodigoGeracao = document.form1.c12_contcearquivo.value;
  
  if (iCodigoGeracao == ''){
    alert('Codigo da geração dos dados do arquivo não encontrado.\n Verifique os dados cadastrados e tente novamente.');
  }
  
  var aChecks = document.getElementById('arquivos').getElementsByTagName("input");
  for (var i = 0; i < aChecks.length; i++) {
    if (aChecks[i].type == 'checkbox') {
      if (aChecks[i].checked == true){
        aArqs.push(aChecks[i].value);
      }
    }
  }
  
  var aChecksPAD = document.getElementById('arquivosPad').getElementsByTagName("input");
  for (var i = 0; i < aChecksPAD.length; i++) {
    if (aChecksPAD[i].type == 'checkbox') {
      if (aChecksPAD[i].checked == true){
        aArqsPAD.push(aChecksPAD[i].value);
      }
    }
  }

  if ( aArqs.length == 0 && aArqsPAD.length == 0 ) {
    alert("Selecione um arquivo !");
    return false;    
  }

  var sCodArqs    = aArqs.toSource();
  var sCodArqsPAD = aArqsPAD.toSource();
  var sQuery      = 'sArquivos='+sCodArqs+'&sArquivosPad='+sCodArqsPAD+'&codigogeracao='+iCodigoGeracao;
  var url         = 'con4_geratcearq002.php';
  
  js_OpenJanelaIframe('','db_iframe_processa',url+'?'+sQuery,'Gerando arquivos',true,0);
    
}

function js_consultaArquivos() {
   
   js_divCarregando("Aguarde, buscando registros","msgBox");
   
   strJson = '{"exec":"getDadosArquivos"}';
   
   var url     = 'con4_geratcearqRPC.php';
   var oAjax   = new Ajax.Request( url, {
                                          method: 'post', 
                                          parameters: 'json='+strJson, 
                                          onComplete: js_saida
                                        }
                                 );

}

function js_saida(oAjax) {  
  
  var obj = eval("(" + oAjax.responseText + ")");

  if (obj.status && obj.status == 2){
     js_removeObj("msgBox");
     alert(obj.sMensagem.urlDecode());
     return false ;
  }

  saida         = '';
  var iErros    = new Number(0);
  var lDisabled = false;

  if (obj) {
    
    var saida    = '';
    var saidaPAD = '';
    var saidaAID = '';
    for (var iInd = 0; iInd < obj.length; iInd++) {
      
      with (obj[iInd]) {
         
	      saida  = "<tr id='linhaItemchkmarca"+db50_codigo+"'>";
	      saida += "  <td class='linhagrid' >";
	      saida += "     <input type='checkbox' ";
	      saida += "            class='' name='chkItem"+db50_codigo+"'";
	      saida += "            id='chkArquivo"+db50_codigo+"'";
	      saida += "            value='"+db50_codigo+"' >";
	      saida += "  </td>";
	      saida += "  <td class='linhagrid' id='' >";
	      saida +=      db50_codigo;
	      saida += "  </td>";
	      saida += "  <td style='text-align:left;' class='linhagrid' >";
	      saida +=     db50_descr.urlDecode();
	      saida += "  </td>";
	      saida += "  <td class='linhagrid' id='' >";
	      saida +=      db56_descr.urlDecode();
	      saida += "  </td>";
	      saida += "</tr>";
        
        if (db50_layouttxtgrupo == '2'){
          saidaAID += saida;           
        } else if (db50_layouttxtgrupo == '3') {
          saidaPAD += saida;
        }
      }
      saida += "";      
    }
    $('arquivos').innerHTML    = saidaAID;
    $('arquivosPad').innerHTML = saidaPAD; 
  }
  js_removeObj("msgBox");
}

function js_marca(tBody){
  
  var aChecks = document.getElementById(tBody).getElementsByTagName("input");
  
  for (var i = 0; i < aChecks.length; i++) {
    if (aChecks[i].type == 'checkbox') {
      if (aChecks[i].checked == true){
        aChecks[i].checked = false;
      }else{
        aChecks[i].checked = true;
      }
    }
  }
}
</script>
