<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 *
 * @author I
 * @revision $Author: dbtales.baz $
 * @version $Revision: 1.13 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oRotulo = new rotulocampo();
$oRotulo->label("rh72_projativ");
$oRotulo->label("rh72_programa");
$oRotulo->label("rh72_funcao");
$oRotulo->label("rh72_subfuncao");
$oRotulo->label("rh72_codele");
$oRotulo->label("rh72_recurso");
$oRotulo->label("rh72_concarpeculiar");
$oRotulo->label("rh72_coddot");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");
$oRotulo->label("o52_descr");
$oRotulo->label("o53_descr");
$oRotulo->label("o54_descr");
$oRotulo->label("o56_codele");

$oJson = new Services_JSON();
$oParam  = $oJson->decode(str_replace("\\","",$_GET["json"]));
/**
 * Verificamos se a rotina foi liberada pela folha
 */
$sWhereConfirma   = " rh83_anousu           = {$oParam->iAnoFolha}";
$sWhereConfirma  .= " and rh83_mesusu       = {$oParam->iMesFolha}";
$sWhereConfirma  .= " and rh83_siglaarq     = '{$oParam->sSigla}'";
if ($oParam->sSigla == 'r20') {
  $sWhereConfirma  .= " and rh83_complementar in (".implode(",", $oParam->aRescisoes).")";
} else {
  $sWhereConfirma  .= " and rh83_complementar = {$oParam->sSemestre}";
}
$sWhereConfirma  .= " and rh83_tipoempenho  = {$oParam->iTipo}";
$sWhereConfirma  .= " and rh83_instit       =  ".db_getsession("DB_instit");
$oDaoConfirma     = db_utils::getDao("rhempenhofolhaconfirma");
$sSqlConfirma     = $oDaoConfirma->sql_query(null, "*", null, $sWhereConfirma);
$rsConfirma       = $oDaoConfirma->sql_record($sSqlConfirma);
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
<style>
.empenho {color: black}
.border  {border-right: 1px solid threedshadow;
          text-align: right
         }
.BordasCgm {
           border-top:2px groove white;
           border-bottom:2px groove white
           }
</style>
</head>
<body bgcolor="#cccccc" onload="js_init()" style='margin: 0px'>
  <div id='messageBoard' style="border-bottom: 2px groove white; padding:5px;
                                background-color: white; vertical-align:bottom; font-weight:bold;
             width: 99%;height: 50px;tex-align:left;display: none">

   </div>
  <form name='form1'>

   <table cellspacing="0" >
     <tr>
       <td>
         <fieldset>
           <legend>
            <strong>Dados da Folha</strong>
          </legend>
          <table cellspacing="0"  >
             <tr>
                <td>
                   <strong>Mês:</strong>
                </td>
                <td style='border:1px solid #999999;background-color: white;width: 50px' id='mesfolha'>
                   &nbsp;
                </td>
                <td>
                   <strong>Ano:</strong>
                </td>
                <td style='border:1px solid #999999;background-color: white;width: 50px' id='anofolha'>
                   &nbsp;
                </td>
             </tr>
             <tr>
               <td>
                <strong>Tipo de Folha:</strong>
               </td>
               <td style='border:1px solid #999999;background-color: white;width: 120px' id='tipofolha' colspan="3">
                  &nbsp;
                </td>
             </tr>
          </table>
          </fieldset>
       </td>
     </tr>
   </table>

    <center>

   <table cellspacing="0" style='-moz-user-select: none'>
     <tr>
       <td colspan='8'>

          <fieldset style="width: 1714px;">
            <legend>
               <strong>Empenhos</strong>
            </legend>
            <table style='border:2px inset white;width: 100%' cellpadding="0" cellspacing="0">
      <!--        <tr>
               <td class='table_header' width="1%">
               <img src="imagens/espaco.gif">
               </td>
                <td class='table_header' width="5%">
                   Matricula
                </td>
                <td class='table_header' colspan='6'>
                   Nome
                </td>
                <td class='table_header' colspan='2'>
                   Rescisão
                </td>
                <td class='table_header' colspan='3'>
                   &nbsp;
                </td>
             </tr>  -->
              <tr>
                <td class='table_header' width="35">
                   <img src="imagens/espaco.gif">
                </td>
                <td class='table_header' width="60">
                   Orgão
                </td>
                <td class='table_header' width="340">
                   Unidade
                </td>
                <td class='table_header' width="70">
                   Atividade
                </td>
                <td class='table_header' width="70">
                   Recurso
                </td>
                <td class='table_header' width="535">
                   Desdobramento
                </td>
                <td class='table_header' width="50">
                   CP
                </td>
                <td class='table_header' width="70">
                   Dotação
                </td>
                <td class='table_header' width="130">
                   Valor
                </td>
                <td class='table_header' width="130">
                   Saldo Dotação
                </td>
                <td class='table_header' width="70">
                   Programa
                </td>
                <td class='table_header' width="70">
                   Função
                </td>
                <td class='table_header' width="70">
                   Sub-Função
                </td>
                 <td class='table_header' width="14px">
                   <img src='imagens/identacao.gif' border='0'>
                   <img src="imagens/espaco.gif">
                </td>
              </tr>
              <tbody id='listaEmpenhos' style='height: 300px;overflow: scroll; overflow-x:hidden; background-color: white'>
              </tbody>
            </table>

          </fieldset>

         </td>
       </tr>
       <tr>
        <td>

           <fieldset>

           <table width="100%" height="100%" cellspacing="0">
            <tr>
              <td style='border-right: 2px groove white'
                  nowrap title="<?=@$Trh01_numcgm?>" width="50%">
                &nbsp;
                <input type="checkbox" id='opporrecurso' checked="checked">
                <label for="opporrecurso">Gerar OP Auxiliar Por recurso</label>
              </td>
              <td class='BordasCgm' style="text-align: left">
                 <strong>Total Bruto:</strong>
              </td>
              <td class='BordasCgm' style="text-align: right;border-right: 2px groove white">
                <strong><span id='valorbruto' style='color:blue'>0,00</span></strong>
              </td>
              <td class='BordasCgm' style="text-align: left">
                <strong>Total Descontos:</strong>
              </td>
              <td class='BordasCgm' style="text-align: right;border-right: 2px groove white">
                <strong><span id='valordescontos' style='color:red'>0,00</span></strong>
               </td>
              <td class='BordasCgm' style="text-align: left">
                <strong>Total Líquido:</strong>
              </td>
              <td class='BordasCgm' style="text-align: right;border-right: 2px groove white">
                <strong><span id='valorliquido' style='color:blue'>0,00</span></strong>
              </td>
            </tr>
          </table>

          </fieldset>

        </td>
       </tr>
       <tr>
        <td colspan="7" style="text-align: center;">
          <span id='buttonReservas'>
          </span>
          <input type='button' onclick="js_gerarEmpenhos()"     id='empenhar'     value='Gerar Empenhos' disabled>
        </td>
       </tr>
   </table>

  </center>

  <div style="position:absolute; top:0px; display:none;" id='modal'>
  </div>

  <div style='position:fixed;
              border:2px outset black;
              background-color: #CCCCCC;
              display: none' id='wndAlterarDotacao'>

     <div style='padding:0px;text-align:right;border-bottom: 2px outset white;background-color: #2C7AFE;color:white'>
        <span style='float:left'><strong>Alterar Dotação</strong></span>
        <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('fecharDotacao').click();">
     </div>

     <div style="border-bottom: 2px groove white;vertical-align:bottom;font-weight:bold;
                 background-color: white;width: 100%;height: 50px;tex-align:left">
        <strong>Alterar dados dos empenhos a Gerar</strong>
     </div>

     <div style='padding:3px'>
       <center>
         <fieldset>
           <table>
             <tr>
              <td>
                <strong>Orgão:</strong>
              </td>
              <td>
              <?
              $clorcorgao = db_utils::getDao("orcorgao");
              $result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"o40_orgao,o40_descr",
                                                                         "o40_orgao",
                                                                         "o40_anousu=".db_getsession("DB_anousu")."
                                                                         and o40_instit=".db_getsession("DB_instit")));
              db_selectrecord("rh72_orgao",$result,true,2,"","","","0","js_getUnidades();");
             ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Unidade:</strong>
              </td>
              <td>
                <?
                  db_select("rh72_unidade",array(),true,1,"onchange='js_getDotacoes();'");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh72_projativ?>">
               <?
               db_ancora("<strong>Projeto:</strong>","js_pesquisarh72_projativ(true);", 1);
               ?>
              </td>
              <td>
               <?
               db_input('rh72_projativ',10,$Irh72_projativ,true,'text',1," onchange='js_pesquisarh72_projativ(false);'");
               db_input('o55_descr',35,"",true,'text',3,'')
               ?>
             </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh72_programa; ?>">
                <?php db_ancora($Lrh72_programa, 'js_pesquisarh72_programa(true)', 1); ?>
              </td>
              <td>
                <?php db_input('rh72_programa', 10, $Irh72_programa, true, 'text', 1, "onchange='js_pesquisarh72_programa(false)'"); ?>
                <?php db_input('o54_descr', 35, $Io54_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh72_funcao; ?>">
                <?php db_ancora($Lrh72_funcao, 'js_pesquisarh72_funcao(true)', 1); ?>
              </td>
              <td>
                <?php db_input('rh72_funcao', 10, $Irh72_funcao, true, 'text', 1, "onchange='js_pesquisarh72_funcao(false)'"); ?>
                <?php db_input('o52_descr', 35, $Io52_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh72_subfuncao; ?>">
                <?php db_ancora($Lrh72_subfuncao, 'js_pesquisarh72_subfuncao(true)', 1); ?>
              </td>
              <td>
                <?php db_input('rh72_subfuncao', 10, $Irh72_subfuncao, true, 'text', 1, "onchange='js_pesquisarh72_subfuncao(false)'"); ?>
                <?php db_input('o53_descr', 35, $Io53_descr, true, 'text', 3); ?>
              </td>
            </tr>
            <tr>
               <td nowrap title="<?=@$Trh72_codele?>">
                 <strong>
	   	            <?
	   	              db_ancora("Desdobramento","js_pesquisarh72_elemento(true);", 1);
	   	            ?>
                 </strong>
               </td>
               <td>
                <?
                 db_input('rh72_elemento',10,$Irh72_codele,true,'text', 1," onchange='js_pesquisarh72_elemento(false);'");
                 db_input('o56_elemento',35,"",true,'text',3,'')
                 ?>
               </td>
             </tr>
             <tr>
              <td nowrap title="<?=@$Trh72_recurso?>">
               <?
                 db_ancora(@$Lrh72_recurso,"js_pesquisac62_codrec(true);", 1);
               ?>
              </td>
              <td>
              <?
              db_input('rh72_recurso',10,$Irh72_recurso,true,'text', 1,"onchange='js_pesquisac62_codrec(false);'");
              db_input('o15_descr',35,"",true,'text',3,"");
              ?>
              </td>
            </tr>
	          <tr>
	            <td nowrap title="<?=@$Trh72_concarpeculiar?>">
	              <strong>
	              <?
	                db_ancora("CP","js_pesquisarh72_concarpeculiar(true);",1);
	              ?>
	              </strong>
	            </td>
	            <td>
	              <?
	                db_input("rh72_concarpeculiar",10,$Irh72_concarpeculiar,true,"text",1,"onChange='js_pesquisarh72_concarpeculiar(false);'");
	                db_input("c58_descr",35,0,true,"text",3);
	              ?>
	            </td>
	          </tr>
            <tr>
              <td nowrap title="<?=@$Trh72_coddot?>">
               <?
                 db_ancora(@$Lrh72_coddot,"js_pesquisarh72_coddot(true);", 1);
               ?>
              </td>
              <td>
              <?
                db_select("rh72_coddot",array(),true,1);
               ?>
              </td>
            </tr>
            <tr>
               <td>
                 <strong>Valor:</strong>
               </td>
               <td>
               <?
                db_input('rh73_valor',10,"",true,'text', 3);
               ?>
            </tr>
           </table>
         </fieldset>
         <input value='Confirma' type='button' id='atualizarDotacao' onclick='js_atualizaDotacao'>
         <input value='Fechar'   type='button' id='fecharDotacao' onclick=''>
       </center>

     </div>

  </div>

  </form>

</body>
</html>
<script>

iDotacaoOriginal = '';
oParametros = eval("("+parent.js_getQueryTela('consultarEmpenhos')+")");
lBotoes     = true;
sNomeFolha  = "";
switch (oParametros.sSigla) {

    case 'r14' :

      sNomeFolha = 'Salário';
      break;

    case 'r48' :

      sNomeFolha = 'Complementar';
      break;

    case 'r35' :

      sNomeFolha = '13º Salário';
      break;

    case 'r20' :

      sNomeFolha = 'Rescisão';
      break;

    case 'r22' :

      sNomeFolha = 'Adiantamento';
      break;
  }
<?
 if ($oDaoConfirma->numrows == 0) {
?>
   //lBotoes = false;
   $("atualizarDotacao").disabled  = true;
   $('messageBoard').innerHTML     = "Folha  de "+sNomeFolha+" para o período "+oParametros.iMesFolha+"/"+oParametros.iAnoFolha;
   $('messageBoard').innerHTML    += " Não liberada para gerar Empenhos.";
   $('messageBoard').style.display = "";
<?
}
?>
sUrl = "pes4_gerarEmpenhoFolhaRPC.php";

function js_init() {
  /**
   * consultamos os empenhos que deve ser gerados
   */
 $('listaEmpenhos').innerHTML = "";
 $('mesfolha').innerHTML      = oParametros.iMesFolha;
 $('anofolha').innerHTML      = oParametros.iAnoFolha;
 $('tipofolha').innerHTML     = sNomeFolha;
 oParametros.exec = "getMatriculasComEmpenhoRescisao";
 js_divCarregando('Aguarde, pesquisando', 'msgbox');
 var oAjax  = new Ajax.Request(
                                sUrl,
                                {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoConsultaEmpenhos
                                }
                               );

 }
function js_retornoConsultaEmpenhos(oResponse) {

  js_removeObj('msgbox');

  var oRetorno =  eval("("+oResponse.responseText+")");
  var lReservadoSaldo = false;
  var nValorBruto    = new Number(0);
  var nValorDesconto = new Number(0);
  if (oRetorno.itens.length > 0) {
    nValorDesconto = oRetorno.nTotalDescontos;
  } else {
    lBotoes = false;
  }
  var iDotacaoAnterior       = 0;
  var nValorAcumuladoDotacao = new Number(0);
  nSaldoDotacaoAnterior      = new Number(0);
  var lErro                  = false;
  var iQtdeErro               = 0;
  var iToTalEmpEmpenhosReservados = 0;
  if (oRetorno.status == 1) {

    for (var i = 0; i < oRetorno.itens.length; i++) {

      with (oRetorno.itens[i]) {


        var oRow              = document.createElement("TR");
        oRow.style.height     = "1em";
        oRow.style.fontWeight = "bold";
        oRow.style.cursor     = "default";
        oRow.className        = "empenho";
        oRow.id               = 'no'+rh02_seqpes;
        oRow.setAttribute('estado', 1);
        oRow.setAttribute('rh02_seqpes', rh02_seqpes);
        var sImagemTree = "plusbottom.gif";
        var iProximoId  = '';
        if (oRetorno.itens[i+1]) {
         var iProximoId = oRetorno.itens[i+1].rh02_seqpes;
        }
        if (i+1 == oRetorno.itens.length) {
          sImagemTree   = "plus.gif";

        }
        var oCellTree = document.createElement("TD");
        oCellTree.innerHTML  = "<img id='open"+rh02_seqpes+"' onclick='getEmpenhosFilhos("+rh02_seqpes+", \""+iProximoId+"\")' src='imagens/treeplus.gif' border='0'>";
        oCellTree.align      = "center";

         var oCellMatricula  = document.createElement("TD");
         oCellMatricula.innerHTML         = "<span id='matric"+rh02_seqpes+"'>M-"+rh01_regist+"</span>";
         oCellMatricula.align      = "right";
         oCellMatricula.style.padding     = "1px";
         oCellMatricula.style.borderRight = "1px solid #999999";
         var oCellNome  = document.createElement("TD");
         oCellNome.innerHTML         = "<span id='nome"+rh02_seqpes+"'>"+z01_nome.urlDecode()+"</span>";
         oCellNome.colSpan          = 6;
         oCellNome.style.padding     = "1px";

         var oCellValor  = document.createElement("TD");
         oCellValor.innerHTML        = "<span id='valor"+rh02_seqpes+"'>"+js_formatar(rh73_valor, "f")+"</span>";
         oCellValor.colSpan          = 1;
         oCellValor.align            = "right";

         var oCellVazia   = document.createElement("TD");
         oCellVazia.className        = "border";
         oCellVazia.innerHTML        = "<img src='imagens/espaco.gif' border='0'>";
         oCellVazia.style.textAlign  = "left";

         oRow.appendChild(oCellTree);
         oRow.appendChild(oCellMatricula);
         oRow.appendChild(oCellNome);
         oRow.appendChild(oCellValor);
         $('listaEmpenhos').appendChild(oRow);
         nValorBruto           += new Number(rh73_valor);
         /**
          * Verifica se todos os Empenhos da matricula já foram Reservados.
          */
         if (totalempenhos == totalempenhosreserva) {
           iToTalEmpEmpenhosReservados++;
         }
       }
     }
     if (iToTalEmpEmpenhosReservados == oRetorno.itens.length) {
       lReservadoSaldo = true;
     }
     var oLinhaFinal = document.createElement("TR");
     oLinhaFinal.style.height='auto';
     oLinhaFinal.id          ='fixFF';
     oLinhaFinal.innerHTML   ='<TD>&nbsp;</td>';
     $('listaEmpenhos').appendChild(oLinhaFinal);


     if (lReservadoSaldo && lBotoes) {

       $('empenhar').disabled     = false;
       $('buttonReservas').innerHTML = "<input type='button' onclick='js_cancelarReservas()' id='cancelarreserva'    value='Cancelar reservas de Saldos' >";

     } else if (!lReservadoSaldo && lBotoes) {

       $('empenhar').disabled     = true;
       if (oRetorno.itens.length > 0) {
         $('buttonReservas').innerHTML = "<input type='button' onclick='js_reservarSaldos()'  id='reservasaldo'    value='Reservar Saldos' >";
       } else {
         $('buttonReservas').innerHTML = "<input type='button' onclick='js_reservarSaldos()'  id='reservasaldo'    value='Reservar Saldos' disabled>";
       }

     }
     if (!lBotoes) {

       $('empenhar').disabled     = true;
       $('buttonReservas').innerHTML = "<input type='button' onclick='js_reservarSaldos()'  id='reservasaldo'    value='Reservar Saldos' disabled>";

     }

     if (lErro) {

        if (!$('erro')) {

          var sErroMsg  =  "<div id='erro' style='color:red'>Há Empenhos com valor retido maior que o valor do empenho.<br/>";
              sErroMsg +=  "Contate suporte.</div>";
          $('messageBoard').innerHTML   += sErroMsg;
          $('messageBoard').style.display = "";
          $('empenhar').disabled     = true;

        }
     } else {

        if ($('erro')) {
          $('erro').innerHTML = '';
        }
     }
   }
   $('valorbruto').innerHTML     = "&nbsp;&nbsp;"+js_formatar(nValorBruto, "f");
   $('valordescontos').innerHTML = "&nbsp;&nbsp;"+js_formatar(nValorDesconto, "f");
   $('valorliquido').innerHTML   = "&nbsp;&nbsp;"+js_formatar(nValorBruto - nValorDesconto, "f");
}

function getEmpenhosFilhos(iSequencial, iProximoEmpenho) {

  var lConsulta = false;
  if ($('no'+iSequencial).getAttribute("estado") == 1) {

   $('no'+iSequencial).setAttribute("estado",2);
   $('open'+iSequencial).src='imagens/treeminus.gif';
   lConsulta = true;

  } else {

    $('no'+iSequencial).setAttribute("estado",1);
    $('open'+iSequencial).src='imagens/treeplus.gif';
    lConsulta = false;

  }
  /**
   * Buscamos a informacao dos dados por funcionario
   */
  if (lConsulta) {

   oParametros.exec            = "getDadosEmpenho";
   oParametros.iSeqPes         = iSequencial;
   oParametros.iProximoEmpenho = iProximoEmpenho;
   var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoGetDadosEmpenhoFilho
                                }
                               );
   } else {

     var aListaNos = getElementsByClass("empenhos"+iSequencial);
     for (var i = 0; i < aListaNos.length; i++) {

       var aListaRubricas = getElementsByClass("rubricas"+iSequencial+"empenho"+aListaNos[i].getAttribute('sequencial'));
       for (j = 0; j < aListaRubricas.length; j++) {
        $('listaEmpenhos').removeChild(aListaRubricas[j]);
       }
       $('listaEmpenhos').removeChild(aListaNos[i]);
     }
   }

}

function js_retornoGetDadosEmpenhoFilho(oResponse) {


  var iDotacaoAnterior       = 0;
  var nSaldoDotacaoAnterior  = 0;
  oRetorno      = eval("("+oResponse.responseText+")");
  var iQtdeErro = 0;

  if (oRetorno.status == 1) {

    var nValorAcumuladoDotacao = new Number(0);
    for (var j = 0; j < oRetorno.itens.length; j++) {

      with (oRetorno.itens[j]) {

        var sId = new String(rh72_sequencial);

        var oRow    = document.createElement("TR");
        oRow.style.height  = "1em";
        oRow.style.display = "";
        oRow.style.cursor  = "default";
        oRow.setAttribute("sequencial", rh72_sequencial);
        oRow.setAttribute("estado", 1);
        oRow.id            = "noempenho"+sId;
        oRow.className     ='empenhos'+rh73_seqpes;
        oRow.ondblclick    = function () {
           js_alterarDotacao(rh72_sequencial, 1, rh73_seqpes);
        }

        var oCellTree = document.createElement("TD");
        oCellTree.innerHTML      = "<img src='imagens/identacao.gif' border='0'>";
        oCellTree.align          = "right";
        oCellTree.style.padding  = "0px";
        oCellTree.innerHTML     += "<img id='openempenho"+sId+"' onclick='js_showRubricas("+rh72_sequencial+","+rh73_seqpes+")' src='imagens/treeplus.gif' border='0'>";

         var oCellOrgao  = document.createElement("TD");
         oCellOrgao.innerHTML         = "<span id='orgao"+rh72_sequencial+"'>"+rh72_orgao+"</span>";
         oCellOrgao.style.textAlign   = "center";
         oCellOrgao.className         = "border";

         var oCellUnidade             = document.createElement("TD");
         oCellUnidade.innerHTML       = "<span style='display:none' id='unidade"+rh72_sequencial+"'>"+rh72_unidade+"</span>&nbsp;"+rh72_unidade.urlDecode()+' - '+o41_descr.urlDecode().substr(0,38);
         oCellUnidade.style.textAlign = 'left';
         oCellUnidade.className       = "border";
//         oCellUnidade.id              = "unidade"+rh72_sequencial;

         var oCellAtividade           = document.createElement("TD");
         oCellAtividade.innerHTML     = rh72_projativ.urlDecode();
         oCellAtividade.className     = "border";
         oCellAtividade.id            = 'projativ'+rh72_sequencial;

         var oCellRecurso             = document.createElement("TD");
         oCellRecurso.style.textAlign = 'right';
         oCellRecurso.innerHTML       = rh72_recurso;
         oCellRecurso.className       = "border";
         oCellRecurso.id              = 'recurso'+rh72_sequencial;

         var oCellElemento             = document.createElement("TD");
         oCellElemento.innerHTML       = "<span style='display:none' id='elemento"+rh72_sequencial+"'>"+rh72_codele+"</span>&nbsp;"+o56_elemento.urlDecode()+' - '+o56_descr.urlDecode().substr(0,37);
         oCellElemento.style.textAlign = 'left';
         oCellElemento.className       = "border";
//         oCellElemento.id              = 'elemento'+rh72_sequencial;

         var oCellCaracteristica             = document.createElement("TD");
         oCellCaracteristica.innerHTML       = rh72_concarpeculiar.urlDecode();
         oCellCaracteristica.style.textAlign = 'right';
         oCellCaracteristica.className       = "border";
         oCellCaracteristica.id              = 'caracteristica'+rh72_sequencial;

        var oCellDotacao               = document.createElement("TD");
         oCellDotacao.innerHTML        = "<span style='display:none' id='dotacao"+rh72_sequencial+"'>"+rh72_coddot+"</span>";

         if(rh72_coddot != 0){
          oCellDotacao.innerHTML      += "<a href='#' onclick='js_mostraDotacao("+rh72_coddot+");return false'>"+rh72_coddot.urlDecode()+"</a>";
         }else{
          oCellDotacao.innerHTML      += rh72_coddot.urlDecode();
         }
         oCellDotacao.style.textAlign  = 'right';
         oCellDotacao.className        = "border";

         var oCellValor             = document.createElement("TD");
         oCellValor.style.textAlign ='right';
         oCellValor.innerHTML       = js_formatar(rh73_valor, 'f');
         oCellValor.style.textAlign = 'right';
         oCellValor.className       = "border";
         oCellValor.id              = "valor"+rh72_sequencial;
         if (o120_orcreserva == "") {

           oCellValor.style.color = "red";
           lReservadoSaldo      = false;
         }
         nSaldoDotacao          = new Number(saldodotacao);
         if (o120_orcreserva == "") {

           if (rh72_coddot != iDotacaoAnterior) {

              nValorAcumuladoDotacao = new Number(rh73_valor);
              nSaldoDotacaoAnterior  = new Number(saldodotacao);
              var nSaldoDotacao      = new Number(saldodotacao);
              nSaldoDotacao          -= nValorAcumuladoDotacao;

           } else {

             nSaldoDotacaoAnterior   = (nSaldoDotacao);
             nValorAcumuladoDotacao += new Number(rh73_valor).toFixed(2);
             nSaldoDotacao          -= nValorAcumuladoDotacao;

           }
         }
         var oCellSaldo             = document.createElement("TD");
         oCellSaldo.style.textAlign ='right';
         oCellSaldo.innerHTML       = js_formatar(nSaldoDotacao, 'f');
         oCellSaldo.className       = "border";
         oCellSaldo.style.color     = 'black';
         if ((nSaldoDotacao < 0 || nSaldoDotacaoAnterior < rh73_valor) && o120_orcreserva == "") {

          oCellSaldo.style.color = 'red';

         }

         var oCellPrograma  = document.createElement("TD");
         oCellPrograma.innerHTML         = "<span id='programa"+rh72_sequencial+"'>"+rh72_programa+"</span>";
         oCellPrograma.style.textAlign   = "center";
         oCellPrograma.className         = "border";

         var oCellFuncao  = document.createElement("TD");
         oCellFuncao.innerHTML         = "<span id='funcao"+rh72_sequencial+"'>"+rh72_funcao+"</span>";
         oCellFuncao.style.textAlign   = "center";
         oCellFuncao.className         = "border";

         var oCellSubFuncao  = document.createElement("TD");
         oCellSubFuncao.innerHTML         = "<span id='subfuncao"+rh72_sequencial+"'>"+rh72_subfuncao+"</span>";
         oCellSubFuncao.style.textAlign   = "center";
         oCellSubFuncao.className         = "border";

         var oCellVazia   = document.createElement("TD");

         oCellVazia.className        = "border";
         oCellVazia.innerHTML        = "<img src='imagens/espaco.gif' border='0'>";
         oCellVazia.style.textAlign  = "left";

         oRow.appendChild(oCellTree);
         oRow.appendChild(oCellOrgao);
         oRow.appendChild(oCellUnidade);
         oRow.appendChild(oCellAtividade);
         oRow.appendChild(oCellRecurso);
         oRow.appendChild(oCellElemento);
         oRow.appendChild(oCellCaracteristica);
         oRow.appendChild(oCellDotacao);
         oRow.appendChild(oCellValor);
         oRow.appendChild(oCellSaldo);
         oRow.appendChild(oCellPrograma);
         oRow.appendChild(oCellFuncao);
         oRow.appendChild(oCellSubFuncao);
         oRow.appendChild(oCellVazia);
         if (!$('no'+oRetorno.iProximoEmpenho)) {
          $('listaEmpenhos').insertBefore(oRow, $('fixFF'));
        } else {
         $('listaEmpenhos').insertBefore(oRow, $('no'+oRetorno.iProximoEmpenho));
        }
//         /**
//          * percorremos os nos filhos para adicionar o Empenho
//          */
         iDotacaoAnterior = rh72_coddot;

         if (new Number(diferencaretencao) < 0 ) {

           lErro = true;
           iQtdeErro++;
           oRow.style.backgroundColor='#DEB887';

         }

         for (var i = 0; i < rubricas[0].rubricas.length; i++) {

          with (rubricas[0].rubricas[i]) {

            var oRowRubricas    = document.createElement("TR");
            oRowRubricas.style.height  = "1em";
            oRowRubricas.style.display = "none";
            oRowRubricas.className     ='rubricas'+rh73_seqpes+'empenho'+rh72_sequencial;

            var oCellTree        = document.createElement("TD");
            oCellTree.innerHTML  = "<img src='imagens/espaco.gif' border='0'>";

            var oCellRubrica        = document.createElement("TD");
            oCellRubrica.innerHTML  = "<img src='imagens/identacao.gif' border='0'>";
            oCellRubrica.innerHTML += "<img src='imagens/identacao.gif' border='0'>";
            oCellRubrica.innerHTML += rh27_rubric+"-"+rh27_descr.urlDecode();
            oCellRubrica.colSpan   = '7';
            oCellRubrica.style.textAlign  = "left";
            oCellRubrica.style.borderBottom  = "1px dotted threedshadow";
            oCellRubrica.className           = "border";

            oCellValor              = document.createElement("TD");
            oCellValor.innerHTML  = js_formatar(rh73_valor, 'f');
            oCellValor.style.textAlign  = "right";
            oCellValor.className        = "border";
            if (rh73_pd == 2) {
              oCellValor.style.color   = "red";
            }

            var oCellPrograma            = document.createElement("TD");
            oCellPrograma.innerHTML      = "<img src='imagens/espaco.gif' border='0'>";
            oCellPrograma.className      = "border";

            var oCellFuncao              = document.createElement("TD");
            oCellFuncao.innerHTML        = "<img src='imagens/espaco.gif' border='0'>";
            oCellFuncao.className        = "border";

            var oCellSubFuncao           = document.createElement("TD");
            oCellSubFuncao.innerHTML     = "<img src='imagens/espaco.gif' border='0'>";
            oCellSubFuncao.className     = "border";

            var oCellVazia             = document.createElement("TD");
            oCellVazia.innerHTML       = "<img src='imagens/espaco.gif' border='0'>";
            oCellVazia.className       = "border";

            oRowRubricas.appendChild(oCellTree);
            oRowRubricas.appendChild(oCellRubrica);
            oRowRubricas.appendChild(oCellValor);

            oRowRubricas.appendChild(oCellPrograma);
            oRowRubricas.appendChild(oCellFuncao);
            oRowRubricas.appendChild(oCellSubFuncao);
            oRowRubricas.appendChild(oCellVazia);

            if (!$('no'+oRetorno.iProximoEmpenho)) {
              $('listaEmpenhos').insertBefore(oRowRubricas, $('fixFF'));
            } else {
              $('listaEmpenhos').insertBefore(oRowRubricas, $('no'+oRetorno.iProximoEmpenho));
            }
          }
        }
      }
    }
  }
}


function js_showRubricas(iSequencial, iEmpenho) {

  var lConsulta = false;
  if ($('noempenho'+iSequencial).getAttribute("estado") == 1) {

   $('noempenho'+iSequencial).setAttribute("estado",2);
  // $('openempenho'+iSequencial).src='imagens/setabrancabaixo.png';
   $('openempenho'+iSequencial).src='imagens/treeminus.gif';
   lConsulta = true;

  } else {

    $('noempenho'+iSequencial).setAttribute("estado",1);
//    $('openempenho'+iSequencial).src ='imagens/setabranca.png';
    $('openempenho'+iSequencial).src ='imagens/treeplus.gif';
    lConsulta = false;

  }
 var aListaRubricas = getElementsByClass("rubricas"+iEmpenho+"empenho"+iSequencial)
 aListaRubricas.each(function(oRubrica, id) {

   if (lConsulta) {
    oRubrica.style.display = "";
   } else {
     oRubrica.style.display = "none";
   }

 });

}
function getElementsByClass ( searchClass, domNode, tagName) {

    if (domNode == null) {
      domNode = document;
    }

    if (tagName == null) {
      tagName = '*';
    }

    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
    for (i=0,j=0; i<tags.length; i++) {

      var test = " " + tags[i].className + " ";
      if (test.indexOf(tcl) != -1) {
         el[j++] = tags[i];
       }
    }
    return el;
  }
/**
 * busca os dados do empenho e passamos para a tela permitindo o usuário alterar as informacoes
 */
function js_alterarDotacao(iSequencial, iTipoEmpenho, iSeqPes) {

  var el =  document;
  var x  = el.body.getWidth()/3;



  if (iSeqPes == null){
    iSeqPes = "";
  }
  $('noempenho'+iSequencial).style.backgroundColor="#EFEFEF";
  $('rh72_orgao').value = $('orgao'+iSequencial).innerHTML;
  js_ProcCod_rh72_orgao('rh72_orgao','rh72_orgaodescr');

  iUnidadeOriginal    = $('unidade'+iSequencial).innerHTML;
  js_getUnidades();

  $('rh72_projativ').value  = $('projativ'+iSequencial).innerHTML;
  js_pesquisarh72_projativ(false);

  $('rh72_programa').value  = $('programa'+iSequencial).innerHTML;
  js_pesquisarh72_projativ(false);

  $('rh72_funcao').value  = $('funcao'+iSequencial).innerHTML;
  js_pesquisarh72_projativ(false);

  $('rh72_subfuncao').value  = $('subfuncao'+iSequencial).innerHTML;
  js_pesquisarh72_subfuncao(false);

  $('rh72_coddot').value    = $('dotacao'+iSequencial).innerHTML;
  iDotacaoOriginal          = $('dotacao'+iSequencial).innerHTML;

  $('rh72_elemento').value  = $('elemento'+iSequencial).innerHTML;
  js_pesquisarh72_elemento(false);

  $('rh72_concarpeculiar').value  = $('caracteristica'+iSequencial).innerHTML;
  js_pesquisarh72_concarpeculiar(false);


  $('rh72_recurso').value   = $('recurso'+iSequencial).innerHTML;
  js_pesquisac62_codrec(false);
  $('rh73_valor').value     = $('valor'+iSequencial).innerHTML;

  $('modal').style.width                  = document.body.getWidth()+"px";
  $('modal').style.height                 = (document.body.getHeight() - 180)+"px";
  $('modal').style.display                = '';
  $('wndAlterarDotacao').style.top     = 80+"px";
  $('wndAlterarDotacao').style.left    = (x)+"px";
  $('wndAlterarDotacao').style.display = '';
  $('wndAlterarDotacao').style.zIndex     = '100000';
  $('fecharDotacao').onclick = function() {

    $('wndAlterarDotacao').style.display    ='none';
    $('wndAlterarDotacao').style.zIndex     = '0';
    $('modal').style.display                = 'none';
    $('noempenho'+iSequencial).style.backgroundColor="white";
  }
  $('atualizarDotacao').onclick = function() {

    if (confirm('Confirma a alteração dos Dados?')) {
      js_alterarDadosEmpenho(iSequencial, iTipoEmpenho, iSeqPes);
    }
  }
}

function js_pesquisarh72_projativ(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('','db_iframe_orcprojativ',
                       'func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr',
                       'Projetos/Atividades',
                        true,0,0,
                        document.body.clientWidth-40,
                       document.body.scrollHeight-180);


    $('Jandb_iframe_orcprojativ').style.zIndex = 100000;

  }else{
     if($('rh72_projativ').value != ''){
        js_OpenJanelaIframe('','db_iframe_orcprojativ',
                            'func_orcprojativ.php?pesquisa_chave='+$F('rh72_projativ')+'&funcao_js=parent.js_mostraorcprojativ',
                            'Projetos/atividades',
                            false,
                            0,
                            0,
                            document.body.clientWidth-40,
                            document.body.scrollHeight-180);
     }else{
       $('o55_descr').value = '';
     }
  }
}
function js_mostraorcprojativ(chave,erro) {

  $('o55_descr').value = chave;
  if(erro==true){
    $('rh72_projativ').focus();
    $('rh72_projativ').value = '';
  } else {
    js_getDotacoes();
  }
}
function js_mostraorcprojativ1(chave1,chave2){
  $('rh72_projativ').value = chave1;
  $('o55_descr').value = chave2;
  db_iframe_orcprojativ.hide();
  js_getDotacoes();
}


function js_pesquisarh72_programa(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('','db_iframe_orcprograma',
                       'func_orcprograma.php?funcao_js=parent.js_mostraprograma1|o54_programa|o54_descr',
                       'Programa',
                        true,0,0,
                        document.body.clientWidth-40,
                       document.body.scrollHeight-180);


    $('Jandb_iframe_orcprograma').style.zIndex = 100000;

  }else{
     if($('rh72_programa').value != ''){
        js_OpenJanelaIframe('','db_iframe_orcprograma',
                            'func_orcprojativ.php?pesquisa_chave='+$F('rh72_programa')+'&funcao_js=parent.js_mostraprograma',
                            'Programa',
                            false,
                            0,
                            0,
                            document.body.clientWidth-40,
                            document.body.scrollHeight-180);
     }else{
       $('o54_descr').value = '';
     }
  }
}
function js_mostraprograma(chave,erro) {

  $('o54_descr').value = chave;
  if(erro==true){
    $('rh72_programa').focus();
    $('rh72_programa').value = '';
  } else {
    js_getDotacoes();
  }
}
function js_mostraprograma1(chave1,chave2){
  $('rh72_programa').value = chave1;
  $('o54_descr').value = chave2;
  db_iframe_orcprograma.hide();
  js_getDotacoes();
}

function js_pesquisarh72_funcao(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('','db_iframe_orcfuncao',
                       'func_orcfuncao.php?funcao_js=parent.js_mostrafuncao1|o52_funcao|o52_descr',
                       'Funções',
                        true,0,0,
                        document.body.clientWidth-40,
                       document.body.scrollHeight-180);


    $('Jandb_iframe_orcfuncao').style.zIndex = 100000;

  }else{
     if($('rh72_funcao').value != ''){
        js_OpenJanelaIframe('','db_iframe_orcfuncao',
                            'func_orcfuncao.php?pesquisa_chave='+$F('rh72_funcao')+'&funcao_js=parent.js_mostrafuncao',
                            'Funções',
                            false,
                            0,
                            0,
                            document.body.clientWidth-40,
                            document.body.scrollHeight-180);
     }else{
       $('o52_descr').value = '';
     }
  }
}
function js_mostrafuncao(chave,erro) {

  $('o52_descr').value = chave;
  if(erro==true){
    $('rh72_funcao').focus();
    $('rh72_funcao').value = '';
  } else {
    js_getDotacoes();
  }
}
function js_mostrafuncao1(chave1,chave2){
  $('rh72_funcao').value = chave1;
  $('o52_descr').value = chave2;
  db_iframe_orcfuncao.hide();
  js_getDotacoes();
}

function js_pesquisarh72_subfuncao(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('','db_iframe_orcsubfuncao',
                       'func_orcsubfuncao.php?funcao_js=parent.js_mostrasubfuncao1|o53_subfuncao|o53_descr',
                       'Sub Função',
                        true,0,0,
                        document.body.clientWidth-40,
                       document.body.scrollHeight-180);


    $('Jandb_iframe_orcsubfuncao').style.zIndex = 100000;

  }else{
     if($('rh72_subfuncao').value != ''){
        js_OpenJanelaIframe('','db_iframe_orcsubfuncao',
                            'func_orcsubfuncao.php?pesquisa_chave='+$F('rh72_subfuncao')+'&funcao_js=parent.js_mostrasubfuncao',
                            'Sub Função',
                            false,
                            0,
                            0,
                            document.body.clientWidth-40,
                            document.body.scrollHeight-180);
     }else{
       $('o53_descr').value = '';
     }
  }
}
function js_mostrasubfuncao(chave,erro) {

  $('o53_descr').value = chave;
  if(erro==true){
    $('rh72_subfuncao').focus();
    $('rh72_subfuncao').value = '';
  } else {
    js_getDotacoes();
  }
}
function js_mostrasubfuncao1(chave1,chave2){
  $('rh72_subfuncao').value = chave1;
  $('o53_descr').value = chave2;
  db_iframe_orcsubfuncao.hide();
  js_getDotacoes();
}


function js_pesquisarh72_elemento(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_orcelemento',
                        'func_orcelementodesdobramento.php?dotacao='+iDotacaoOriginal+'&funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_descr',
                        'Elementos da Despesa',
                        true,0,0,document.body.clientWidth-40,
                        document.body.scrollHeight-180
                        );
    $('Jandb_iframe_orcelemento').style.zIndex = 100000;
  }else{
     if($('rh72_elemento').value != ''){
        js_OpenJanelaIframe('',
                            'db_iframe_orcelemento',
                            'func_orcelementodesdobramento.php?dotacao='+iDotacaoOriginal+'&pesquisa_chave='+$('rh72_elemento').value+
                            '&funcao_js=parent.js_mostraorcelemento&tipo_pesquisa=1',
                            'Elementos da Despesa',
                             false,
                             0,
                             0,
                             document.body.clientWidth-40,
                             document.body.scrollHeight-180);
     }else{
       $('o56_elemento').value = '';
     }
  }
}
function js_mostraorcelemento(chave,erro){

  $('o56_elemento').value = chave;

  if(erro==true){
    $('rh72_elemento').focus();
    $('rh72_elemento').value = '';
  } else {
   js_getDotacoes();
  }
}

function js_mostraorcelemento1(chave1,chave2){
  $('rh72_elemento').value = chave1;
  $('o56_elemento').value = chave2;
  db_iframe_orcelemento.hide();
  js_getDotacoes();
}



function js_pesquisarh72_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_orcdotacao',
                        'func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot',
                        'Pesquisar Dotações',true,0,0,
                        document.body.clientWidth-40,
                        document.body.scrollHeight - 180);
    $('Jandb_iframe_orcdotacao').style.zIndex = 100000;
  }else{
    js_OpenJanelaIframe('',
                        'db_iframe_orcdotacao',
                        'func_orcdotacao.php?pesquisa_chave='+document.form1.rh72_coddot.value+'&funcao_js=parent.js_mostraorcdotacao',
                        'Pesquisa de Dotacoes',
                        false,
                        0,
                        0,
                        document.body.clientWidth-40,
                        document.body.scrollHeight-180);
  }
}
function js_mostraorcdotacao(chave,erro){
  if(erro==true){
    document.form1.rh72_coddot.focus();
    document.form1.rh72_coddot.value = '';
  } else {
   js_getDotacoes();
  }
}
function js_mostraorcdotacao1(chave1){
  document.form1.rh72_coddot.value = chave1;
  db_iframe_orcdotacao.hide();
  js_getOrigemDotacao(chave1);
}

function js_pesquisarh72_concarpeculiar(mostra){
  if ( mostra ) {
    js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr','Pesquisa',true, 0, 0, document.body.clientWidth-40);
    $('Jandb_iframe_concarpeculiar').style.zIndex = 100000;
  } else {
    if( document.form1.rh72_concarpeculiar.value != '' ){
      js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.rh72_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false, 0, 0, document.body.clientWidth-40);
    } else {
      document.form1.c58_descr.value = '';
    }
  }
}

function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave;
  if(erro){
    document.form1.rh72_concarpeculiar.focus();
    document.form1.rh72_concarpeculiar.value = '';
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.rh72_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}



function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('',
                           'db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Recursos',true,0,0,
                           document.body.clientWidth-40,
                           document.body.scrollHeight - 180);
       $('Jandb_iframe_orctiporec').style.zIndex = 100000;

   }else{
       if(document.form1.rh72_recurso.value != ''){
           js_OpenJanelaIframe('',
                               'db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='
                                +document.form1.rh72_recurso.value+'&funcao_js=parent.js_mostraorctiporec',
                                'Recursos',
                                false,
                                0,
                                0,
                                document.body.clientWidth-40,
                                document.body.scrollHeight-180);
       }else{
           document.form1.o15_descr.value = '';
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave;
   if(erro==true){
      document.form1.rh72_recurso.focus();
      document.form1.rh72_recurso.value = '';
   } else {
     js_getDotacoes();
   }
}

function js_mostraorctiporec1(chave1,chave2){
    document.form1.rh72_recurso.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
    js_getDotacoes();
}

function js_getUnidades() {

   oParam          = new Object();
   oParam.exec     = "getUnidades";
   oParam.orgao    = $F('rh72_orgao');
   var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoGetUnidades
                                }
                               );
}

function js_retornoGetUnidades(oRequest) {

  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {
   $('rh72_unidade').options.length = 0;
   oRetorno.itens.each(function (oUnidade, iIndice) {

      var oOption  = new Option(oUnidade.o41_descr.urlDecode(), oUnidade.o41_unidade);
      $('rh72_unidade').add(oOption, null);
      if (iUnidadeOriginal == oUnidade.o41_unidade && oUnidade.o41_orgao == $F('rh72_orgao')) {
        $('rh72_unidade').options[iIndice].selected = true;
      }
    });
    js_getDotacoes();
  }
}
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true) {

    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_nome.php?campos='+
                        'cgm.z01_numcgm\, z01_nome\,trim\(z01_cgccpf\) as z01_cgccpf\,'+
                        'trim\(z01_ender\) as z01_ender\, z01_munic\, z01_uf\, z01_cep\, z01_email\,z01_sexo\,z01_nasc'+
                        '&testanome=false&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisa CGM',
                        true,
                        0,
                        0,
                        document.body.clientWidth-40,
                        document.body.scrollHeight-180
                        );
  }else{
    if(document.form1.z01_numcgm.value != ''){
      js_OpenJanelaIframe('',
                          'func_nome',
                          'func_nome.php?novosvalores=z01_nome|&pesquisa_chave='+
                           document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm',
                           'Pesquisa CGM',
                           false,0,
                           0,
                           document.body.clientWidth-40,
                           document.body.scrollHeight-180);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostracgm(erro,chave1,chave2,chave3,chave4){

  document.form1.z01_nome.value   = chave1;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
//  document.form1.submit();
}

function js_mostracgm1(chave1,chave2) {
//  alert(chave1+' -- '+chave2+' -- '+chave3+' -- '+chave4);
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}


function js_getOrigemDotacao(iDotacao){

  var oParam            = new Object();
  oParam.iDotacao       = iDotacao;
  oParam.iDesdobramento = $('rh72_elemento').value;
  oParam.exec           = "getOrigemDotacao";

  var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                   method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoGetOrigemDotacao
                                 }
                               );

}

function js_retornoGetOrigemDotacao(oRequest) {

  var oRetorno = eval("("+oRequest.responseText+")");

  if ( oRetorno.itens.length > 0 ) {
    with( oRetorno.itens[0] ){
		  var iNroOrgao = $('rh72_orgao').options.length;

		  for ( var iInd=0; iInd < iNroOrgao; iInd++ ) {
		    if ( $('rh72_orgao').options[iInd].value == o58_orgao ) {
		      $('rh72_orgao').options[iInd].selected      = true;
		      $('rh72_orgaodescr').options[iInd].selected = true;
		    }
		  }

		  iUnidadeOriginal       = o58_unidade;
		  iDotacaoOriginal       = o58_coddot;

		  $('rh72_projativ').value = o58_projativ;
		  $('o55_descr').value     = o55_descr.urlDecode();
		  $('rh72_recurso').value  = o58_codigo;
		  $('o15_descr').value     = o15_descr.urlDecode();


	 	  js_getUnidades();

		  if ( oRetorno.lDesdobramento ) {
			  $('rh72_elemento').value = oRetorno.iCodDesdobramento;
			  $('o56_elemento').value  = oRetorno.iDescrDesdobramento.urlDecode();
		  } else {
		    $('rh72_elemento').value = '';
        $('o56_elemento').value  = '';
        js_pesquisarh72_elemento(true);
		  }

    }
  }
}



/**
 * Consulta as dotacoes para os dados informados na tela de alteração
 */
function js_getDotacoes() {

 var iOrgao    = $F('rh72_orgao');
 var iUnidade  = $F('rh72_unidade');
 if (iUnidade == null) {
   iUnidade = iUnidadeOriginal;
 }
 var iProjAtiv  = $F('rh72_projativ');
 var iPrograma  = $F('rh72_programa');
 var iFuncao    = $F('rh72_funcao');
 var iSubFuncao = $F('rh72_subfuncao');
 var iElemento  = $F('rh72_elemento');
 var iRecurso   = $F('rh72_recurso');

 if (iOrgao == "" || iUnidade == "" || iProjAtiv == "" || iElemento == "" || iRecurso == "") {
   return false;
 }

 var oParam           = new Object();
 oParam.iOrgao        = iOrgao;
 oParam.exec          = "getDotacoes";
 oParam.iUnidade      = iUnidade;
 oParam.iProjAtiv     = iProjAtiv;
 oParam.iPrograma     = iPrograma;
 oParam.iFuncao       = iFuncao;
 oParam.iSubFuncao    = iSubFuncao;
 oParam.iElemento     = iElemento;
 oParam.iRecurso      = iRecurso;

 var oAjax  = new Ajax.Request(
                                sUrl,
                                {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoGetDotacoes
                                }
                               );

}

function js_retornoGetDotacoes(oRequest) {

  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {

    $('rh72_coddot').options.length = 0;
    oRetorno.itens.each(function (oDotacao, iIndice) {

      var oOption  = new Option(oDotacao.o58_coddot, oDotacao.o58_coddot);
      $('rh72_coddot').add(oOption, null);
      if (iDotacaoOriginal == oDotacao.o58_coddot) {
        $('rh72_coddot').options[iIndice].selected = true;
      }
    });
  }
}

/**
 * @param int iCodigo odigo do empenho - Pode ser o codigo da tabela rhempenhofolha, ou o codigo da tabela rhempenhoFolhaRubrica,
 * dependendo do parametro iTipo
 * @param int iTipo Tipo do empenho 1 -rhempenhofolha, 2 rhempenhofolharubrica
 * @param int iSeqPes Tipo do empenho 1 -rhempenhofolha, 2 rhempenhofolharubrica
 */
function js_alterarDadosEmpenho(iCodigoEmpenho, iTipo, iSeqPes) {

  /**
   * Validamos os dados, para confirmar que o usuario realmente modificou os Dados.
   */
   if ($F('rh72_coddot') == null) {

     alert('Informe uma Dotação!');
     return false;
   }

   if ($('orgao'+iCodigoEmpenho).innerHTML        == $F('rh72_orgao')
       && $('unidade'+iCodigoEmpenho).innerHTML   == $F('rh72_unidade')
       && $('projativ'+iCodigoEmpenho).innerHTML  == $F('rh72_projativ')
       && $('programa'+iCodigoEmpenho).innerHTML  == $F('rh72_programa')
       && $('funcao'+iCodigoEmpenho).innerHTML    == $F('rh72_funcao')
       && $('subfuncao'+iCodigoEmpenho).innerHTML == $F('rh72_subfuncao')
       && $('recurso'+iCodigoEmpenho).innerHTML   == $F('rh72_recurso')
       && $('elemento'+iCodigoEmpenho).innerHTML  == $F('rh72_elemento')
       && $('dotacao'+iCodigoEmpenho).innerHTML   == $F('rh72_coddot') ) {

       $('fecharDotacao').click();

    } else {

      var oParam        = new Object();
      oParam.exec       = "alterarDadosEmpenho";
      oParam.iEmpenho   = iCodigoEmpenho;
      oParam.iTipo      = iTipo;
      oParam.sSigla     = 'r20';
      oParam.iOrgao     = $F('rh72_orgao');
      oParam.iUnidade   = $F('rh72_unidade');
      oParam.iProjAtiv  = $F('rh72_projativ');
      oParam.iPrograma  = $F('rh72_programa');
      oParam.iFuncao    = $F('rh72_funcao');
      oParam.iSubFuncao = $F('rh72_subfuncao');
      oParam.iRecurso   = $F('rh72_recurso');
      oParam.iElemento  = $F('rh72_elemento');
      oParam.iDotacao   = $F('rh72_coddot');
      oParam.iCaract    = $F('rh72_concarpeculiar');
      oParam.iSeqPes    = iSeqPes;

      if (iSeqPes != null) {
        oParam.iSeqPes   = iSeqPes;
      }
      var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoAlterarEmpenho
                                }
                               );
    }
}

function js_retornoAlterarEmpenho(oRequest) {

  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {

    $('fecharDotacao').click();
    js_init();

  } else {
    alert(oRetorno.message.urlDecode());
  }
}

/**
 * Cria a Reserva de saldos para os Empenhos
 */
function js_reservarSaldos() {

  var sMsgConfirma = "Confirma a reserva de saldo dos Empenhos?";
  if (!confirm(sMsgConfirma)) {
    return false;
  }

  aItens = getElementsByClass("empenho");

  oParametros.exec      = "reservarSaldo";
  oParametros.rescisao   = true;
  oParametros.aEmpenhos = new Array();
  aItens.each(function(oEmpenho, id) {

    oParametros.aEmpenhos.push(oEmpenho.getAttribute('rh02_seqpes'));

  });

  js_divCarregando('Aguarde, Reservando saldo dos empenhos','msgbox');
  $('modal').style.display="";
  var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoReservarSaldo
                                }
                               );
}

function js_retornoReservarSaldo(oRequest) {

  js_removeObj('msgbox');
  $('modal').style.display = "none";
  $('modal').style.width   = document.width+"px";
  $('modal').style.height  = (document.body.scrollHeight - 180)+"px";
  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {

    alert('Saldos reservados com Sucesso');
    js_init();

  } else {
    alert(oRetorno.message.urlDecode());
  }

}


function js_cancelarReservas() {

  var sMsgConfirma = "Confirma o cancelamento da geração das reservas de saldo dos Empenhos?";
  if (!confirm(sMsgConfirma)) {
    return false;
  }

  aItens = getElementsByClass("empenho");

  oParametros.exec      = "cancelarReservas";
  oParametros.rescisao  = true;
  oParametros.aEmpenhos = new Array();
  aItens.each(function(oEmpenho, id) {

    oParametros.aEmpenhos.push(oEmpenho.getAttribute('rh02_seqpes'));

  });

  js_divCarregando('Aguarde, Cancelando reservas de saldo dos empenhos','msgbox');
  $('modal').style.display="";
  var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoCancelaReservas
                                }
                               );

}

function js_retornoCancelaReservas(oRequest) {

  js_removeObj('msgbox');
  $('modal').style.display = "none";
  $('modal').style.width   = document.width+"px";
  $('modal').style.height  = (document.body.scrollHeight - 180)+"px";
  var oRetorno = eval("("+oRequest.responseText+")");
  if (oRetorno.status == 1) {

    alert('Reservas canceladas com Sucesso');
    js_init();

  } else {
    alert(oRetorno.message.urlDecode());
  }

}

/**
 * Gera os Empenhos da folha
 */
function js_gerarEmpenhos() {

  var sMsgConfirma = "Confirma a Emissão dos Empenhos?";
  if ($('opporrecurso').checked) {
    sMsgConfirma += "\nSerá gerada uma OP Auxiliar por recurso.";
  }
  if (!confirm(sMsgConfirma)) {
    return false;
  }
  aItens = getElementsByClass("empenho");

  //var oParam       = new Object();
  oParametros.exec      = "gerarEmpenhos";
  oParametros.lOPporRecurso = false;
  oParametros.rescisao   = true;
  oParametros.aEmpenhos = new Array();
  aItens.each(function(oEmpenho, id) {

    var oEmpenhoAdicionar = new Object();
    oEmpenhoAdicionar.rh72_sequencial = oEmpenho.getAttribute('rh02_seqpes');
    oParametros.aEmpenhos.push(oEmpenho.getAttribute('rh02_seqpes'));

  });

  js_divCarregando('Aguarde, Emitindo empenhos','msgbox');
  $('modal').style.display = "";
  $('modal').style.width   = document.width+"px";
  $('modal').style.height  = (document.body.scrollHeight - 180)+"px";
  var oAjax  = new Ajax.Request(
                                 sUrl,
                                 {
                                 method: 'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoGerarEmpenhos
                                }
                               );
}

function js_retornoGerarEmpenhos(oRequest) {


  js_removeObj('msgbox');
  $('modal').style.display = "none";
  $('modal').style.width   = document.width+"px";
  $('modal').style.height  = (document.body.scrollHeight - 180)+"px";
  var oRetorno = eval("("+oRequest.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');

  if (oRetorno.status == 1) {

    alert('Empenhos Gerados com sucesso\nOrdem Auxiliar Nº'+oRetorno.e42_sequencial);
    js_init();

  } else {
    alert(oRetorno.message.urlDecode().replace(sExpReg,'\n'));
  }

}
/**
 * Consulta das dotações
 */
function js_mostraDotacao(chave){

  arq = 'func_saldoorcdotacao.php?o58_coddot='+chave
  js_OpenJanelaIframe('',
                      'db_iframe_saldos',arq,'Saldo da dotação',true, 0, 0,
                       (document.body.clientWidth)-40,
                       document.body.scrollHeight-180);
}
$('rh72_orgao').style.width="95px";
$('rh72_orgaodescr').style.width="300px";
$('rh72_unidade').style.width="395px";
$('rh72_coddot').style.width="95px";
</script>