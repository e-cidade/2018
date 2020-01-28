<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 *
 * @author I
 * @revision $Author: dbmatheus.felini $
 * @version $Revision: 1.23 $
 */
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/JSON.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("model/linhaRelatorioContabil.model.php"));
include(modification("model/relatorioContabil.model.php"));
$oGet            = db_utils::postMemory($_GET);
$oDaoOrcparamSeq = db_utils::getDao("orcparamseq");
$sSqlDadosLinha  = $oDaoOrcparamSeq->sql_query_file($oGet->iCodRel,$oGet->iLinha);
$rsDadosLinha    = $oDaoOrcparamSeq->sql_record($sSqlDadosLinha);
$oRelatorio      = new relatorioContabil($oGet->iCodRel);
$aPeriodos       = $oRelatorio->getPeriodos();
$sDescricaoPeriodo = "";
foreach ($aPeriodos as $oPeriodo) {

  if ($oPeriodo->o114_sequencial == $oGet->iPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}
$oLinhaRelatorio = db_utils::fieldsMemory($rsDadosLinha, 0);
if ($oLinhaRelatorio->o69_observacao == '') {
  $oLinhaRelatorio->o69_observacao = 'Informe os valores para a linha.';
}
$oLinhaRelatorio->o69_observacao = str_replace("\n", "<br>", $oLinhaRelatorio->o69_observacao);
$oLinhaRelatorio->o69_observacao = str_replace("\r", "", $oLinhaRelatorio->o69_observacao);
$oLinha          = new linhaRelatorioContabil($oGet->iCodRel,$oGet->iLinha);
$aColunasLinhas  = $oLinha->getCols($oGet->iPeriodo);
$oJson           = new Services_JSON();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="getValoresColunas()" style='margin:0'>
<center>
<form name='frmReprocessa' id='frmReprocessa'>
  <table width="100%" cellspacing="0" cellpadding="0">
    <tr style="height:auto" >
      <td valign="top">
         <fieldset >
           <table cellspacing="0" width='100%' style='border:2px inset white;'>
             <tr>
               <td  class="table_header">&nbsp;</td>
            <?

            $i=0;
              foreach ($aColunasLinhas as $oColuna) {

                   echo "<td class='table_header'>{$oColuna->o115_descricao}</td>\n";
                   $i++;
               }
               echo "<td class='table_header'>Período</td>\n";
               $i++;
               //if ($oLinhaRelatorio->o69_manual == "t") {
                   echo "<td class='table_header'>Ação</td>\n";
                //}
                ?>
                <td class='table_header'>&nbsp;</td>
             </tr>
             <tbody id='grid1' style='z-Index:0;height:350px;overflow:scroll;overflow-x:hidden;background-color:white'>
             </tbody>
           </table>
         </fieldset>
      </td>
    </tr>
  </table>
  </form>
</center>
</body>
</html>
<script>
iTotalColunas = <?=count($aColunasLinhas);?>;
aColunas      = new Array();
<?
foreach ($aColunasLinhas as $oColuna) {

  echo "oColunaNova           = new Object()\n";
  echo "oColunaNova.id        = {$oColuna->o116_sequencial};\n";
  echo "oColunaNova.descricao = '{$oColuna->o115_descricao}';\n";
  echo "oColunaNova.tipo      = '{$oColuna->o115_tipo}';\n";
  echo "aColunas.push(oColunaNova)\n";

}
echo "aPeriodos = eval('(".$oJson->encode($aPeriodos).")');\n";
?>
function js_createCombo(iLinha, iPeriodoLinha) {

  var sSelect  = "<select style='width:100%' id='periodo"+iLinha+"'>";

  for (var i = 0; i < aPeriodos.length; i++) {

    sSelected = "";
   if (iPeriodoLinha == aPeriodos[i].o114_sequencial) {
      sSelected = " selected ";
    }

    sSelect  += "<option value='"+aPeriodos[i].o114_sequencial+"' "+sSelected+">"+aPeriodos[i].o114_descricao+"</option>";
  }
  sSelect  += "</select>";

  return sSelect;
}
sUrlRPC = "con4_variaveisrelatorioRPC.php";

function js_linhaInicial(iLinha) {
   var iTotalLinhas = iLinha
   if (iTotalLinhas == 0) {
     iTotalLinhas  = 1;
   }
  <?
  if ($oLinhaRelatorio->o69_manual == "t" || 1==1) {

    $i  = 0;
    echo "var aLinha = '<tr id=\"newLine\" style=\"height: 1em;\">';\n";
    echo "aLinha    += '<td class=\"linhagrid\" style=\"background-color:#DED5CB\"><b>'+iTotalLinhas+'</b></td>';\n";
    foreach ($aColunasLinhas as $oColuna) {

     $sAling  = $oColuna->o115_tipo == 2?"left":"right";
     echo "aLinha += \"<td class='linhagrid'>\"\n";
     if (trim($oColuna->o115_valoresdefault) != "") {

       echo "aLinha += \"<select id='linha\"+iTotalLinhas+\"coluna{$i}'\";\n";
       echo "aLinha += \"style='width:100%;height:99%;border:1px solid white'>\";\n";
       $aValores = explode(",",$oColuna->o115_valoresdefault);
       for ($iDefault = 0; $iDefault < count($aValores); $iDefault++) {
          echo "aLinha += \"<option value='{$aValores[$iDefault]}'>{$aValores[$iDefault]}</option>\";\n";
       }

       echo "aLinha += \"</select>\"\n";
     } else {

       echo "aLinha += \"<input type='text' id='linha\"+iTotalLinhas+\"coluna{$i}' value='' \"\n";
       echo "aLinha += \"onfocus='js_liberaDigitacao(this,{$oColuna->o115_tipo})' onblur='js_bloqueiaDigitacao(this,{$oColuna->o115_tipo});'\"\n";
       echo "aLinha += \"onkeyDown='js_verifica(this,event,{$oColuna->o115_tipo})' \"\n";
       if ($oColuna->o115_tipo == 1) {
         echo "aLinha += \"onKeyPress=\\\"return js_mask(event,'0-9|.|-')\\\"\"\n";
       }
       echo "aLinha += \"style='width:100%;height:100%;text-align:{$sAling};border:1px solid white'>\";\n";
     }
     echo "aLinha += \"</td>\";\n";
     $i++;

    }

    //echo "aLinha  += \"<td class='linhagrid'>\"+js_createCombo(iTotalLinhas,0)+\"</td>\";\n";
    echo "aLinha  += \"<td class='linhagrid'>{$sDescricaoPeriodo}</td>\";\n";
    $i++;
    echo "aLinha += \"<td class='linhagrid'><input style='width:100%' type='button' value='Salvar' onclick='js_save(\"+iTotalLinhas+\")'>\";\n";
    echo "aLinha += \"</td></tr>\";\n";

  }
  ?>
  aLinha +="<tr id='linhaaux' style='height:auto; width: 1em;' ><td colspan='<?=($i+2)?>'>&nbsp;</td></tr>";
  $('grid1').innerHTML += aLinha;

}

function js_liberaDigitacao(object, tipo) {

  nValorObjeto        = object.value;
  //alert(object);
  object.style.borderColor = 'black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  if (tipo == 1) {
    object.value  = js_strToFloat(object.value);
  }
  object.select();

}
function js_bloqueiaDigitacao(object, tipo) {

  object.style.borderColor ='white ';
  object.style.fontWeight = "normal";
  if (tipo == 1) {
    object.value  = js_formatar(object.value,"f");
  }
  object.readOnly  = true;


}

function js_verifica(object,event, tipo) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value = nValorObjeto;
     js_bloqueiaDigitacao(object,tipo);
  }
}


function js_save(iLinha) {

  var oGet = js_urlToObject (window.location.search);

  oRequisicao        = new Object();
  oRequisicao.exec   = "save";
  oRequisicao.cols   = new Array();
  oRequisicao.iLinha = iLinha;

  if (oGet.iAnoPesquisa) {
    oRequisicao.iAnoUsu = oGet.iAnoPesquisa;
  }

  for ( var i = 0; i < iTotalColunas  ; i++) {

    var oColuna       = new Object();
    oColuna.iCodigo   = aColunas[i].id;
    oColuna.iPeriodo  = <?=$oGet->iPeriodo?>;
    if (aColunas[i].tipo == 1) {
      oColuna.nValor    = js_strToFloat($('linha'+iLinha+'coluna'+i).value).valueOf();
    } else {
      oColuna.nValor    = encodeURIComponent($('linha'+iLinha+'coluna'+i).value);
    }
    oColuna.iSeq      = null;
    if ($('linha'+iLinha+'coluna'+i).getAttribute("sequencial") != "") {
      oColuna.iSeq      = $('linha'+iLinha+'coluna'+i).getAttribute("sequencial");
    }
    oRequisicao.cols.push(oColuna);

  }

  //return false;
  js_divCarregando("Aguarde, pesquisando","msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oRequisicao),
                          onComplete: js_retornoSalvar
                          }
                        );


}

function js_retornoSalvar(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {
    getValoresColunas();
  }
}

function getValoresColunas() {

  var oGet = js_urlToObject (window.location.search);

  $('grid1').innerHTML      = "";
  oRequisicao               = new Object();
  oRequisicao.exec          = "getValoresColunas";
  oRequisicao.iLinhaRel     = oGet.iLinha;
  oRequisicao.iCodRel       = oGet.iCodRel;
  oRequisicao.iPeriodo      = oGet.iPeriodo;

  if (oGet.iAnoPesquisa) {
    oRequisicao.iAnoPesquisa  = oGet.iAnoPesquisa;
  }
  js_divCarregando("Aguarde, pesquisando","msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oRequisicao),
                          onComplete: js_retornoColunas
                          }
                        );


}

function js_retornoColunas(oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  var iLinha   = 0;
  var iPeriodo = 0;
  var iCodigoLinha = 0;
  if (oRetorno.status == 2) {

  } else {

    // var oLinhaAdd = $('linhaaux');
     //$('grid1').removeChild($('linhaaux'));

     for (var iLinhas = 0 ; iLinhas < oRetorno.itens.length; iLinhas++) {

       var oLinha = document.createElement("TR");
        var oColuna =   document.createElement("TD");
            oColuna.className = "linhagrid";
            oColuna.style.backgroundColor ='#DED5CB';
            oColuna.style.fontWeight      ='bold';
            oColuna.style.height    = '1em';
            oColuna.innerHTML = iLinhas+1;
       oLinha.appendChild(oColuna);

       with (oRetorno.itens[iLinhas]) {

         for (var iCol = 0 ; iCol < colunas.length; iCol++) {

           with (colunas[iCol]) {

              var sAlign  = o115_tipo == 2?"left":"right";
              var oColuna =   document.createElement("TD");
              oColuna.className = "linhagrid";
              var aLinha = "<input type='text' id='linha"+o117_linha+"coluna"+iCol+"' ";
              aLinha += "onfocus='js_liberaDigitacao(this,"+o115_tipo+")' onblur='js_bloqueiaDigitacao(this,"+o115_tipo+");'";
              aLinha += "onkeyDown='js_verifica(this,event,"+o115_tipo+")'";
              if (o115_tipo == 1) {
                aLinha += "onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
              }
              if (o115_tipo == 1) {
                aLinha += "value='"+js_formatar(o117_valor,"f")+"' sequencial='"+o117_sequencial+"' ";
              } else {
               aLinha += "value='"+o117_valor.urlDecode()+"' sequencial='"+o117_sequencial+"' ";
              }
              aLinha += "style='width:100%;height:99%;text-align:"+sAlign+";border:1px solid white'>";
              o115_valoresdefault = o115_valoresdefault.urlDecode();
              if (o115_valoresdefault.trim() != "") {

                var aValoresPadrao = o115_valoresdefault.split(',');
                if (aValoresPadrao.length > 0) {


                  aLinha = "<select  id='linha"+o117_linha+"coluna"+iCol+"' sequencial='"+o117_sequencial+"'";
                  aLinha += "style='width:100%;height:99%;text-align:"+sAlign+";border:1px solid white'>";
                  for (var iDefault = 0; iDefault < aValoresPadrao.length; iDefault++) {

                     var sSelected = "";
                     if (o117_valor.urlDecode().trim() == aValoresPadrao[iDefault].trim()) {
                       var sSelected = " selected ";
                     }
                     aLinha += "<option "+sSelected+" value='"+aValoresPadrao[iDefault]+"'>";
                     aLinha += aValoresPadrao[iDefault]+"</option>";

                  }
                  aLinha += "</select>";
                }
              }
              oColuna.innerHTML = aLinha;
              oLinha.appendChild(oColuna);
              iCodigoLinha = o117_linha;
              iPeriodo     = o117_periodo;
           }
         }
         var oColunaPeriodo       = document.createElement("TD");
         oColunaPeriodo.className = "linhagrid";
         oColunaPeriodo.noWrap    = "true";
         oColunaPeriodo.style.whiteSpace = "nowrap";
         oColunaPeriodo.innerHTML = "<?=$sDescricaoPeriodo?>";
         //oColunaPeriodo.innerHTML = js_createCombo(iCodigoLinha, iPeriodo);
         oLinha.appendChild(oColunaPeriodo);

         var oColunaAcao       = document.createElement("TD");
         oColunaAcao.className = "linhagrid";
         oColunaAcao.noWrap    = "true";
         oColunaAcao.style.whiteSpace = "nowrap";
         aLinha  = "<input type='button' style='width:50%' value='Salvar'  onclick='js_save("+iCodigoLinha+")'>";
         aLinha += "<input type='button' style='width:50%' value='Excluir' onclick='js_excluirLinha("+iCodigoLinha+")'>";

         oColunaAcao.innerHTML = aLinha;
         oLinha.appendChild(oColunaAcao);
         $('grid1').appendChild(oLinha) ;
       }
     }
  }
  js_linhaInicial(new Number(iCodigoLinha)+1);
}

function js_excluirLinha(iLinha) {

  if (!confirm('Confirma Exclusão?')) {
    return false;
  }
  oRequisicao           = new Object();
  oRequisicao.exec      = "excluirLinha";

  var oGet = js_urlToObject (window.location.search);

  oRequisicao.iLinhaRel = oGet.iLinha;
  oRequisicao.iCodRel   = oGet.iCodRel;
  oRequisicao.iPeriodo  = oGet.iPeriodo;
  oRequisicao.iLinha    = iLinha;
  //js_divCarregando("Aguarde, excluindo linha","msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oRequisicao),
                          onComplete: getValoresColunas
                          }
                        );
}
//oBtnFechar            = parent.$('fechardb_iframe_colunas');
//oBtnFechar.onclick    = js_sair;

function js_sair() {

  parent.location.href=parent.location.href;
}
var oMessageBoard = new messageBoard('msg1',
                                     'Valores para a linha  - <?=$oLinhaRelatorio->o69_descr?>.',
                                     '<?=@$oLinhaRelatorio->o69_observacao?>',
                                     $('frmReprocessa')
                                    );
oMessageBoard.show();

document.observe('keydown', function(event) {


  if (event.ctrlKey) {

    if (event.which == 39) {

     var sUrl = 'con4_orcrelatorioscolunas.php?';
      sUrl += "iLinha=<?=($oGet->iLinha+1)?>";
      sUrl += "&iCodRel=<?=($oGet->iCodRel)?>";
      sUrl += "&iPeriodo=<?=($oGet->iPeriodo)?>";
      document.location.href = sUrl;
      event.preventDefault();
      event.stopPropagation();

    } else if (event.which == 37) {

     var sUrl = 'con4_orcrelatorioscolunas.php?';
      sUrl += "iLinha=<?=($oGet->iLinha-1)?>";
      sUrl += "&iCodRel=<?=($oGet->iCodRel)?>";
      sUrl += "&iPeriodo=<?=($oGet->iPeriodo)?>";
      document.location.href = sUrl;
      event.preventDefault();
      event.stopPropagation();

    }
  }
});

</script>