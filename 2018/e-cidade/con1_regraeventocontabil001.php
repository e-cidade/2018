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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_contranslr_classe.php"));
require_once(modification("classes/db_conplano_classe.php"));
require_once(modification("classes/db_contranslan_classe.php"));
require_once(modification("classes/db_pctipocompra_classe.php"));
require_once(modification("classes/db_emprestotipo_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("c46_codhist");
$clrotulo->label("c61_reduz");
$clrotulo->label("e54_codcom");


/*Criação dos DAO's */
$oDAODbconfig = new cl_db_config();
$oDAOEmprestotipo = new cl_emprestotipo();
$oDAOpctipocompra = new cl_pctipocompra();
$oDAOconplano = new cl_conplano();
$oDaoConCarPeculiar = new cl_concarpeculiar();

/*Criação dos campos de cada tabela em globals*/

//global contranslan | c47
$oRotuloContransLanLr = new rotulo("contranslr");
$oRotuloContransLanLr->label();

$oRotuloContransLan = new rotulo("contranslan");
$oRotuloContransLan->label();

//global contranslan | c62
$oRotuloConplanoexe = new rotulo("conplanoexe");
$oRotuloConplanoexe->label();

$db_opcao= 1;

$c47_anousu = date("Y", db_getsession("DB_datausu"));

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

#c47_tiporesto, #c47_ref, #c47_compara, #c58_concarpeculiar{
  width: 100px;
}

#c47_tiporestodescr, #c47_refdescr, #c58_concarpeculiardescr {
  width: 70%;
}

#c47_obs {
  width: 100%
}

#c47_compara , #t45_sequencial, #t51_motivo{

  width: 485px !important ;
}

#categoriaContrato {
  width: 485px !important ;
}

#e44_tipo {
  width: 485px !important ;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 30px;" >
<center>
  <form name="form1">
  <fieldset  style="position:relative;  width:725; ">
  <legend><b>Lançamento</b></legend>

    <table border="0" width="100%">
      <tr>
        <!-- Lançamento | tabela : contranslr | código: c47 -->

        <td nowrap="nowrap">
          <b><?=$Lc47_seqtranslan;?></b>
        </td>
        <td width="30">
          <? db_input('c47_seqtranslan', 8, $Ic46_seqtranslan, true, 'text', 3);?>
        </td>

        <!-- Seq.Partida | tabela : contranslr | código: c47-->

        <td nowrap="nowrap" width="80">
          <b><?=$Lc47_seqtranslr;?></b>
        </td>
        <td>
          <? db_input('c47_seqtranslr', 8, $Ic47_seqtranslr, true,'text',3);?>
        </td>
      </tr>

      <!-- Ano | tabela : contranslanlr | código: c47-->
      <tr>
        <td nowrap="nowrap">
          <b><?=$Lc47_anousu;?></b>
        </td>
        <td>
          <? db_input('c47_anousu', 8, $Ic47_anousu, true,'text',1);?>
        </td>
      </tr>

      <!-- Conta a Débito | tabela :contranslanlr | código: c62 -->
      <tr>
        <td nowrap title="<?=@$Tc47_debito?>">
          <?php
            db_ancora($Lc47_debito,"js_pesquisaDebito(true);",$db_opcao);
          ?>
        </td>
        <td colspan="3">
          <?php
            db_input('c47_debito',8,$Ic47_debito,true,'text',$db_opcao,"  onchange='js_pesquisaDebito(false, false);'");
            db_input('c60_estrut_debito', 15, null, true, 'text', $db_opcao, "  onchange='js_pesquisaDebito(false, true);'");
            db_input('c60_descr',50,$Ic61_reduz,true,'text',3,"c60_descr_debito");
          ?>
        </td>
      </tr>

      <!-- Conta a Crédito | tabela :contranslanlr  | código: c62 -->
      <tr>
        <td nowrap title="<?=@$Tc47_credito?>">
          <?php
            db_ancora($Lc47_credito,"js_pesquisaCredito(true);",$db_opcao);
          ?>
        </td>
        <td colspan="3">
          <?php
            db_input('c47_credito',8,$Ic47_credito,true,'text',$db_opcao,"  onchange='js_pesquisaCredito(false, false);'");
            db_input('c60_estrut_credito', 15, null, true, 'text', $db_opcao, "  onchange='js_pesquisaCredito(false, true);'");
            db_input('c60_descr_credito',50,$Ic61_reduz,true,'text',3,"c60_descr_credito");
          ?>
        </td>
      </tr>

      <!------    Toggle ------->

      <tr colspan="2">
        <td colspan="5">
          <fieldset id="fieldsetCamposReferencia" style="position:relative;  width:96%; ">
          <legend><b>Campos de Referência</b></legend>

          <table border="0" width="100%">

            <!-- Regra Comparação -->
            <tr>
              <td nowrap title="<?=@$Tc47_compara?>" width="120px">
                <?=@$Lc47_compara?>
              </td>
              <td>
              <?php
              $aOpcoesComparacao = array("0"  => "Não",
                                         "1"  => "Débito",
                                         "10" => "Débito / Elemento",
                                         "2"  => "Crédito",
                                         "11" => "Crédito / Elemento",
                                         "3"  => "Elemento",
                                         "4"  => "Conta do Plano Orçamentário",
                                         "5"  => "Categoria do Contrato",
                                         "6"  => "Prestação de Contas",
																				 "7"  => "Tipo de Aquisição",
																				 "8"  => "Tipo de Baixa",
																				 "9"  => "Tipo de Reconhecimento Contábil",
                                         "12" => "CP/CA igual a",
                                         "13" => "CP/CA diferente de"
                                       );
              db_select('c47_compara', $aOpcoesComparacao, true, $db_opcao, "onchange='js_validaTipoComparacao(this.value);'");
              ?>
              </td>
            </tr>


            <tr id='ctnTipoAquisicao' style="display: none;">
              <td nowrap title="Tipos de aquisições.">
                <strong>Tipo de Aquisição:</strong>
              </td>
              <td>
                <?php
                  $aTipoAquisicao = array();
                  db_select('t45_sequencial', $aTipoAquisicao, true, $db_opcao, "");
                ?>
              </td>
            </tr>

             <tr id='ctnTipoBaixa' style="display: none;">
              <td nowrap title="Tipos de baixas.">
                <strong>Tipo de Baixa:</strong>
              </td>
              <td>
                <?php
                  $aTipoBaixa = array();
                  db_select('t51_motivo', $aTipoBaixa, true, $db_opcao, "");
                ?>
              </td>
            </tr>

             <tr id='ctnTipoReconhecimentoContabil' style="display: none;">
              <td nowrap title="Tipos de Reconhecimento Contábil .">
                <strong>Tipo de Reconhecimento Contábil : </strong>
              </td>
              <td>
                <?php
                  db_select('c111_tiporeconhecimento', array(), true, $db_opcao, "");
                ?>
              </td>
            </tr>



            <!-- Tipo de Restos a Pagar -->
            <tr>
              <td nowrap title="<?=@$Tc47_tiporesto?>">
                <?=@$Lc47_tiporesto?>
              </td>
              <td>
              <?
              $rsResult=$oDAOEmprestotipo->sql_record($oDAOEmprestotipo->sql_query_file(null,"e90_codigo,e90_descr"));
              db_selectrecord("c47_tiporesto",$rsResult,true,$db_opcao,"","","","0");
              ?>
              </td>
            </tr>

					  <!--
               Tipo de Eventos
                 - será exibida  quando for selecionado "Prestação de contas"
                   no combo Regra de Comparação
            -->

            <tr id='tipoEvento' style="display: none;">
              <td nowrap title="Tipos de eventos.">
                <strong>Tipo de Evento:</strong>
              </td>
              <td>
                <?php
                  $aTipoEvento = array();
                  db_select('e44_tipo', $aTipoEvento, true, $db_opcao, "");
                ?>
              </td>
            </tr>


            <!-- Tipo de Compra -->
             <tr id="trComparaNao" style="display: visible;">
               <td>
                 <b>Tipo de Compra:</b>
               </td>
               <td>
                <?php
                $sSqlTipoDeCompra = $oDAOpctipocompra->sql_query_file(null, "pc50_codcom as e54_codcom, pc50_descr");
                $rsTipoDeCompra   = $oDAOpctipocompra->sql_record($sSqlTipoDeCompra);
                db_selectrecord("c47_ref", $rsTipoDeCompra, true, $db_opcao, "", "", "", "0");
                ?>
               </td>
             </tr>
             <tr id="trComparaDebitoCreditoElemento" style="display: none;">
               <td>
                 <b><span id='NomeCompara'>Elemento:</span></b>
               </td>
               <td>
                <?php
                db_input("sElemento", 20, true, 1, 'text', $db_opcao);
                ?>
               </td>
             </tr>
             <tr id='trCategoriaContrato' style='display:none'>
               <td class="bold">Categorias de Contrato:</td>
               <td>
                 <select id='categoriaContrato' style="width:100%">
                   <option selected="selected" value="">Selecione</option>
                 </select>
               </td>
             </tr>

            <!-- CP/CA  -->
            <tr id="trConcarPeculiar" style="display: none">
              <td class="bold">Característica Peculiar</td>
              <td>
                <?php

                $sCamposConcarPeculiar = "c58_sequencial, c58_descr";
                $sOrderConcarPeculiar  = "c58_sequencial";

                $sSqlConcarPeculiar = $oDaoConCarPeculiar->sql_query_file(null, $sCamposConcarPeculiar, $sOrderConcarPeculiar);
                $rsConcarPeculiar   = $oDaoConCarPeculiar->sql_record($sSqlConcarPeculiar);

                db_selectrecord('c58_concarpeculiar', $rsConcarPeculiar, true, $db_opcao);
                ?>
              </td>
            </tr>
          </table>
          </fieldset>
        </td>
      </tr>

      <!-- Observações //contranslan -->
      <tr colspan="2">
        <td colspan="5">
          <fieldset>
          <legend><b>Observações</b></legend>
          <? db_textarea('c47_obs', 3, 93, $Ic47_obs, true, 'text', $db_opcao)?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <br>
  <input type="button" name="btnSalvarRegra"      id="btnSalvarRegra"      value="Salvar" />
  <input type="button" name="btnLimparFormulario" id="btnLimparFormulario" value="Limpar" />
  </form>

  <fieldset>
    <legend><b>Partidas de Lançamentos Inclusas</b></legend>
    <div id="ctnGridContasLancamentos">
    </div>
  </fieldset>
  <input type="button" name="btnExcluirSelecionados" value="Excluir Selecionados" title="Excluir Selecionados" id="btnExcluirSelecionados" style="display: none;" onclick="js_excluirRegraLancamento();"/>
</center>
</body>
</html>
<script>

//========================busca tipo de aquisicao
function js_getTipoAquisicao(iAlteracao) {

	  var iRegraComparacao                = $F('c47_compara');
    var msgDiv                          = "Aguarde. <br>Buscando tipos de aquisição.";
	  var sUrlRPC                         = "con4_regraeventocontabil.RPC.php";
	  var oParametros                     = new Object();
	  oParametros.exec                    = 'getTipoAquisicao';
	  oParametros.iRegraComparacao        = iRegraComparacao;
	  oParametros.iAlteracao              = iAlteracao;

		js_divCarregando(msgDiv,'msgBox');

		new Ajax.Request(sUrlRPC,
		                        {method: "post",
		                         parameters:'json='+Object.toJSON(oParametros),
		                         onComplete: js_retornoTipoAquisicao
		                        });
}
function js_retornoTipoAquisicao(oAjax){

	  js_removeObj('msgBox');
	  var oRetorno      = eval("("+oAjax.responseText+")");
    $("t45_sequencial").options.length = 0;
	  oRetorno.aDados.each(function (oDado, iInd) {

	    var oOption = new Option(oDado.t45_descricao.urlDecode(), oDado.t45_sequencial);


	    $("t45_sequencial").appendChild(oOption);
	  });
}
//========================================

function js_getTipoReconhecimentoContabil(iAlteracao){

	  var iRegraComparacao            = $F('c47_compara');
	  var msgDiv                      = "Aguarde. <br>Buscando tipos de aquisição.";
		var sUrlRPC                     = "con4_regraeventocontabil.RPC.php";
		var oParametros                 = new Object();
		oParametros.exec                = 'getTipoReconhecimentoContabil';
		oParametros.iRegraComparacao    = iRegraComparacao;
		oParametros.iAlteracao          = iAlteracao;

		js_divCarregando(msgDiv,'msgBox');

		 new Ajax.Request(sUrlRPC,
		                         {method: "post",
		                          parameters:'json='+Object.toJSON(oParametros),
		                          onComplete: js_retornoTipoReconhecimentoContabil
		                         });

}

function js_retornoTipoReconhecimentoContabil(oAjax){

	  js_removeObj('msgBox');
	  var oRetorno      = eval("("+oAjax.responseText+")");
    $("c111_tiporeconhecimento").options.length = 0;

	  oRetorno.aDados.each(function (oDado, iInd) {
	    var oOption = new Option(oDado.c111_descricao.urlDecode(), oDado.c111_sequencial);
	    $("c111_tiporeconhecimento").appendChild(oOption);
	  });
	  if ( oRetorno.iChaveAlteracao != 'novo' ){
	    $("c111_tiporeconhecimento").value = oRetorno.iChaveAlteracao;
	  }

}


                //========================busca tipo de Baixa
function js_getTipoBaixa(iAlteracao){

	  var iRegraComparacao            = $F('c47_compara');
	  var msgDiv                      = "Aguarde. <br>Buscando tipos de aquisição.";
		var sUrlRPC                     = "con4_regraeventocontabil.RPC.php";
		var oParametros                 = new Object();
		oParametros.exec                = 'getTipoBaixa';
		oParametros.iRegraComparacao    = iRegraComparacao;
		oParametros.iAlteracao          = iAlteracao;

		js_divCarregando(msgDiv,'msgBox');

		 new Ajax.Request(sUrlRPC,
		                         {method: "post",
		                          parameters:'json='+Object.toJSON(oParametros),
		                          onComplete: js_retornoTipoBaixa
		                         });

}
function js_retornoTipoBaixa(oAjax){

	  js_removeObj('msgBox');
	  var oRetorno      = eval("("+oAjax.responseText+")");
    $("t51_motivo").options.length = 0;

	  oRetorno.aDados.each(function (oDado, iInd) {

	    var oOption = new Option(oDado.t51_descr.urlDecode(), oDado.t51_motivo);
	    $("t51_motivo").appendChild(oOption);
	  });
}
//========================================

var oGet = js_urlToObject(window.location.search);
$('c47_seqtranslan').value = oGet.iCodigoLancamento;
$('c47_compara').observe("change", function() {

	switch ($F('c47_compara')) {

	  case '6' :

		  js_getTipoEvento('novo');
      $('tipoEvento').style.display       = "table-row";
		  $('ctnTipoAquisicao').style.display = "none";
		  $('ctnTipoBaixa').style.display     = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";

		break;

	  case "7" :

		  js_getTipoAquisicao('novo');
	    $('tipoEvento').style.display       = "none";
		  $('ctnTipoAquisicao').style.display = "table-row";
		  $('ctnTipoBaixa').style.display     = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		break;

	  case "8" :

		  js_getTipoBaixa('novo');
	    $('tipoEvento').style.display       = "none";
		  $('ctnTipoAquisicao').style.display = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		  $('ctnTipoBaixa').style.display     = "table-row";
		break;

	  case "9" :

		  js_getTipoReconhecimentoContabil('novo');

	    $('tipoEvento').style.display       = "none";
		  $('ctnTipoAquisicao').style.display = "none";
		  $('ctnTipoBaixa').style.display     = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "table-row";

		break;

		default :

      $('tipoEvento').style.display       = "none";
		  $('ctnTipoAquisicao').style.display = "none";
		  $('ctnTipoBaixa').style.display     = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		break;
	}
});

function js_getTipoEvento(iAlteracao){

	var iRegraComparacao           = $F('c47_compara');
  var msgDiv                     = "Aguarde. <br>Buscando tipos de evento.";
  var sUrlRPC                    = "con4_regraeventocontabil.RPC.php";
  var oParametros                = new Object();
  oParametros.exec               = 'getTiposEventoEmpenho';
  oParametros.iRegraComparacao   = iRegraComparacao;
  oParametros.iAlteracao         = iAlteracao;

	js_divCarregando(msgDiv,'msgBox');

	 new Ajax.Request(sUrlRPC,
	                  {method: "post",
	                   parameters:'json='+Object.toJSON(oParametros),
	                   onComplete: js_retornoTipoEvento
	                  });

}

function js_retornoTipoEvento(oAjax) {

  js_removeObj('msgBox');
  var oRetorno      = eval("("+oAjax.responseText+")");

  $("e44_tipo").options.length = 0;
  oRetorno.aDados.each(function (oDado, iInd) {

    var oOption = new Option(oDado.e44_descr.urlDecode(), oDado.e44_tipo);
    $("e44_tipo").appendChild(oOption);
  });

}


/* Botão para chamar RPC */
$('btnSalvarRegra').observe("click", function() {

	if ( Number($F('c47_compara')) != 0) {

	  if ( ((Number($F('c47_compara')) <= 4 ||
          Number($F('c47_compara')) == 10 ||
          Number($F('c47_compara')) == 11) &&
          $F('sElemento') == "" ) &&
          ( $F('c47_debito') == ''  ||
          $F('c47_credito') == '' ) ) {

	    alert("Informe o elemento.");
	    return false;
	  }
	}

  if ($F('c47_compara') == 5 && $F('categoriaContrato') == "") {

   alert('Informe a Categoria de contrato.');
   return false;
  }
  var iCodigoContaDebito  = $F('c47_debito');
  var iCodigoContaCredito = $F('c47_credito');
  if ($F('c47_debito') == "" || $F('c47_credito') == "") {

    if ($F('c47_debito') == "") {
      iCodigoContaDebito  = "0";
    }
    if ($F('c47_credito') == "") {
      iCodigoContaCredito = "0";
    }
    if (!confirm("Não foram informadas as contas débito e/ou crédito. Deseja continuar com esta operação?")) {
      return false;
    }
  }
  if ((iCodigoContaDebito == iCodigoContaCredito) && iCodigoContaCredito != '0' ) {

    alert("Conta crédito e débito não podem ser iguais.");
    return false;
  }

  if (parseFloat($F('c47_compara')) == 6 && $F('e44_tipo') == '') {

	  alert("Selecione um tipo de evento, para a ragra de comparação 'Prestação de Contas'. ");
	  return false;
	}
  if (parseFloat($F('c47_compara')) == 7 && $F('t45_sequencial') == '') {

	  alert("Selecione um tipo de aquisição, para a ragra de comparação 'Tipo de Aquisição'. ");
	  return false;
	}
  if (parseFloat($F('c47_compara')) == 8 && $F('t51_motivo') == '') {

	  alert("Selecione um tipo de baixa, para a ragra de comparação 'Tipo de Baixa'. ");
	  return false;
	}
  if (parseFloat($F('c47_compara')) == 9 && $F('c111_tiporeconhecimento') == '') {

	  alert("Selecione um tipo de reconhecimento contábil, para a regra de comparação. ");
	  return false;
	}

  if (parseFloat($F('c47_compara')) == 10 && $F('c47_credito') == "") {
    return alert("Campo Conta a Crédito é de preenchimento obrigatório.");
  }

  if (parseFloat($F('c47_compara')) == 11 && $F('c47_debito') == "") {
    return alert("Campo Conta a Débito é de preenchimento obrigatório.");
  }

  var oParam                = new Object();
  oParam.exec               = 'salvarLancamento';
  oParam.c47_seqtranslr     = $F('c47_seqtranslr');
  oParam.c47_seqtranslan    = $F('c47_seqtranslan');
  oParam.c47_debito         = iCodigoContaDebito;
  oParam.c47_credito 		    = iCodigoContaCredito;
  oParam.c47_obs 		        = $F('c47_obs');
  oParam.c47_ref            = $F('c47_ref');
  oParam.c47_anousu         = $F('c47_anousu');
  oParam.c47_compara        = $F('c47_compara');
  oParam.c47_tiporesto      = $F('c47_tiporesto');
  oParam.sElemento          = $F('sElemento');

  switch ($F('c47_compara')) {

    case "5":
      oParam.c47_ref = $F('categoriaContrato');
    break;

    case "6":
    	oParam.c47_ref = $F('e44_tipo');
    break;

    case "7" :
    	oParam.c47_ref = $F('t45_sequencial');
    break;

    case "8" :
    	oParam.c47_ref = $F('t51_motivo');
    break;
    case "9" :
    	oParam.c47_ref = $F('c111_tiporeconhecimento');
    break;
    case "12" :
    case "13" :
    	oParam.c47_ref = $F('c58_concarpeculiar');
    break;

  }

  js_divCarregando("Aguarde, salvando dados...", "msgBox");
  var oAjax = new Ajax.Request("con4_regraeventocontabil.RPC.php",
  {method:'post',
  parameters:'json='+Object.toJSON(oParam),
                               onComplete: js_finalizaSalvarLancamento});
});

function js_finalizaSalvarLancamento(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {

    $('c47_seqtranslr').value     = '';
    $('c47_debito').value         = '';
    $('c47_credito').value        = '';
    $('c47_obs').value            = '';
    $('c47_ref').value            = '0';
    $('c47_compara').value        = '';
    $('c47_tiporesto').value      = '0';
    $('sElemento').value          = '';
    $('categoriaContrato').value  = '';
    $('c60_estrut_credito').value = '';
    $('c60_estrut_debito').value  = '';
    $('c60_descr_credito').value  = '';
    $('c60_descr').value   = '';
  }

  js_loadRegrasEventoContabil();
}


function js_loadRegrasEventoContabil() {

  var iCodigoLancamento    = oGet.iCodigoLancamento;
  var oParam               = new Object();
  oParam.exec              = "getRegrasLancamentoContabil";
  oParam.iCodigoLancamento = iCodigoLancamento;
  if (iCodigoLancamento == "" || iCodigoLancamento == undefined) {
    return false;
  }

  js_divCarregando("Aguarde, carregando regras...", "msgBox");
  new Ajax.Request("con4_regraeventocontabil.RPC.php",
                  {method:'post',
                  parameters:'json='+Object.toJSON(oParam),
                  onComplete: js_preencheGridRegrasEventoContabil});

}

/**
 * Criamos a grid que irá armazenas os dados
 */
var oDBGridContas   = new DBGrid('ctnGridContasLancamentos');
var aHeadersGrid    = ["Partida", "Conta Débito", "Conta Crédito", "Ano", "Referência", "Compara", "Tipo Resto", "Elemento", "Ação"];
var aCellWidthGrid  = ["5%", "25%", "25%", "5%", "5%", "5%", "10%", "10%", "5%"];
var aCellAlign      = ["center", "left", "left", "center", "center", "center", "center", "left", "center"];

oDBGridContas.nameInstance = 'oDBGridContas';
oDBGridContas.setCheckbox(0);
oDBGridContas.setCellWidth(aCellWidthGrid);
oDBGridContas.setCellAlign(aCellAlign);
oDBGridContas.setHeader(aHeadersGrid);
oDBGridContas.setHeight(130);
oDBGridContas.allowSelectColumns(true);
oDBGridContas.show($('ctnGridContasLancamentos'));

$("col1").style.width = "2%";

function js_preencheGridRegrasEventoContabil(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  /**
   * Erro no RPC
   */
  if ( oRetorno.status == 2 ) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  oDBGridContas.clearAll(true);
  $("btnExcluirSelecionados").style.display = "none";

  if (oRetorno.aRegrasLancamento.length > 0) {

    $("btnExcluirSelecionados").style.display = "";

    oRetorno.aRegrasLancamento.each(function (oRegra, iLinha) {

      var sStringDebito = oRegra.c47_debito;
      if (sStringDebito != '0') {
        sStringDebito += ' - ' + oRegra.estrutural_debito + ' - ' + oRegra.descricao_debito.urlDecode();
      }

      var sStringCredito = oRegra.c47_credito;
      if (sStringCredito != '0') {
        sStringCredito += ' - ' + oRegra.estrutural_credito + ' - ' + oRegra.descricao_credito.urlDecode();
      }

      var aLinha = new Array();
      aLinha[0]  = oRegra.c47_seqtranslr;
      aLinha[1]  = sStringDebito;
      aLinha[2]  = sStringCredito;
      aLinha[3]  = oRegra.c47_anousu;
      aLinha[4]  = oRegra.c47_ref;
      aLinha[5]  = oRegra.c47_compara;
      aLinha[6]  = oRegra.c47_tiporesto;
      aLinha[7]  = oRegra.sElemento;
      aLinha[8]  = "<input type='button' name='btnAlterar"+oRegra.c47_seqtranslr+"' onclick='js_alterarRegraLancamento("+oRegra.c47_seqtranslr+");' value='A' title='Alterar'/>";
      oDBGridContas.addRow(aLinha);
    });

    oDBGridContas.renderRows();

    oRetorno.aRegrasLancamento.each(function (oRegra, iLinha) {

      var oCelulaDebito  = $(oDBGridContas.aRows[iLinha].aCells[1].sId);
      var oCelulaCredito = $(oDBGridContas.aRows[iLinha].aCells[2].sId);

      var sStringDebito = oRegra.c47_debito;
      if (sStringDebito != 0) {
        sStringDebito += ' - ' + oRegra.estrutural_debito + ' - ' + oRegra.descricao_debito.urlDecode();
      }

      var sStringCredito = oRegra.c47_credito;
      if (sStringCredito != 0) {
        sStringCredito += ' - ' + oRegra.estrutural_credito + ' - ' + oRegra.descricao_credito.urlDecode();
      }

      var oDBHintDebito	 = eval("oDBHint_"+iLinha+"_1 = new DBHint('oDBHint_"+iLinha+"_1')");
      var oDBHintCredito = eval("oDBHint_"+iLinha+"_2 = new DBHint('oDBHint_"+iLinha+"_2')");

      oDBHintDebito.setWidth(350);
      oDBHintDebito.setText(sStringDebito);
      oDBHintDebito.setShowEvents(["onmouseover"]);
      oDBHintDebito.setHideEvents(["onmouseout"]);
      oDBHintDebito.setPosition('B', 'L');
      oDBHintDebito.make(oCelulaDebito);

      oDBHintCredito.setWidth(350);
      oDBHintCredito.setText(sStringCredito);
      oDBHintCredito.setShowEvents(["onmouseover"]);
      oDBHintCredito.setHideEvents(["onmouseout"]);
      oDBHintCredito.setPosition('B', 'L');
      oDBHintCredito.make(oCelulaCredito);

    });
  }
}

function js_alterarRegraLancamento(iCodigoRegra) {

  js_divCarregando("Aguarde, carregando informações da regra...", "msgBox");
  var oParam          = new Object();
  oParam.exec         = "getRegraEventoContabil";
  oParam.iCodigoRegra = iCodigoRegra;

  new Ajax.Request("con4_regraeventocontabil.RPC.php",
                   {method:'post',
                   parameters:'json='+Object.toJSON(oParam),
                   onComplete: js_carregaFormularioAlteracao});
}

function js_carregaFormularioAlteracao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  js_validaTipoComparacao(oRetorno.c47_compara);

  $('c47_seqtranslr').value     = oRetorno.c47_seqtranslr;
  $('c47_debito').value         = oRetorno.c47_debito;
  $('c47_credito').value        = oRetorno.c47_credito;
  $('c47_obs').value            = oRetorno.c47_obs.urlDecode();
  $('c47_ref').value            = oRetorno.c47_ref;
  $('c47_anousu').value         = oRetorno.c47_anousu;
  $('c47_compara').value        = oRetorno.c47_compara;
  $('c47_tiporesto').value      = oRetorno.c47_tiporesto;
  js_pesquisaDebito(false);
  js_pesquisaCredito(false);
  js_ProcCod_c47_ref('c47_ref','c47_refdescr');
  js_ProcCod_c47_tiporesto('c47_tiporesto','c47_tiporestodescr');


  switch (oRetorno.c47_compara) {

    case "5" :

      $('categoriaContrato').value = oRetorno.c47_ref;
      oRetorno.c47_ref = '';
      js_validaTipoComparacao('5');
    break;

	  case "6" :

      js_getTipoEvento(oRetorno.c47_ref);
		  $("e44_tipo").value                 = oRetorno.c47_ref;
		  $('tipoEvento').style.display       = 'table-row';
		  $('ctnTipoAquisicao').style.display = 'none';
		  $('ctnTipoBaixa').style.display     = 'none';
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		break;

	  case "7" :

      js_getTipoAquisicao(oRetorno.c47_ref);
		  $("t45_sequencial").value           = oRetorno.c47_ref;
		  $('tipoEvento').style.display       = 'none';
		  $('ctnTipoAquisicao').style.display = 'table-row';
		  $('ctnTipoBaixa').style.display     = 'none';
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		break;

	  case "8" :

      js_getTipoBaixa(oRetorno.c47_ref);
		  $("t51_motivo").value               = oRetorno.c47_ref;
		  $('tipoEvento').style.display       = 'none';
		  $('ctnTipoAquisicao').style.display = 'none';
		  $('ctnTipoBaixa').style.display     = 'table-row';
		  $('ctnTipoReconhecimentoContabil').style.display     = "none";
		break;

	  case "9" :
      js_getTipoReconhecimentoContabil(oRetorno.c47_ref);
	    $('tipoEvento').style.display       = "none";
		  $('ctnTipoAquisicao').style.display = "none";
		  $('ctnTipoBaixa').style.display     = "none";
		  $('ctnTipoReconhecimentoContabil').style.display     = "table-row";

    case "12":
    case "13":

      $("c58_concarpeculiar").value = oRetorno.c47_ref;
      break;
    break;

  }
}

/*
 *	Exclusão de Regra de lançamento contabil
 */
function  js_excluirRegraLancamento() {

  if ( !confirm( "Deseja excluir as regras selecionadas?" ) ) {
    return false;
  }

  /**
    * "Pegamos" as linhas selecionadas da grid
    */
  var oLinhasSelecionadas = oDBGridContas.getSelection( "object" );

  /**
    * Criamos o array que irá guardar os códigos sequenciais das regras á excluir
    */
  var aLinhasSelecionadas = new Array();

  /**
    * Adicionando ao array os sequenciais das regras selecionadas
    */
  for(var iContadorLinha = 0; iContadorLinha < oLinhasSelecionadas.length; iContadorLinha++){
    aLinhasSelecionadas.push( oLinhasSelecionadas[iContadorLinha].aCells[0].getValue() );
  }

  var oParam  				= new Object();
  oParam.aRegras      = aLinhasSelecionadas; //Adicionando ao objeto que faz a requisição, o array de regras á excluir
  oParam.exec 				= "excluirRegraLancamentoContabil";

  js_divCarregando("Aguarde, removendo regra...", "msgBox");

  new Ajax.Request("con4_regraeventocontabil.RPC.php",
                    {
                      method:'post',
                      parameters:'json='+Object.toJSON(oParam),
                      onComplete: js_concluiExclusaoRegra
                    }
                  );
}

function js_concluiExclusaoRegra(oAjax){

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")")
    alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {
    js_loadRegrasEventoContabil();
  }
}



/* Pesquisa Débito */
function js_pesquisaDebito(lMostra, lPesquisaElemento){

  var sUrlHistorico = "";
  if(lMostra){
    sUrlHistorico  = "func_conplanoexe.php?funcao_js=parent.js_preencheDebito|c62_reduz|c60_descr|c60_estrut";
  }else{

    var sChavePesquisa = $F('c47_debito');
    if (lPesquisaElemento) {
      sChavePesquisa = $F('c60_estrut_debito');
    }

    if (sChavePesquisa == "") {
      $('c47_debito').value  = '';
      $('c60_descr').value   = '';
      $('c60_estrut_debito').value  = '';
      return false;
    }
    sUrlHistorico  = "func_conplanoexe.php?pesquisa_chave="+sChavePesquisa+"&lEstrutural="+lPesquisaElemento+"&funcao_js=parent.js_completaDebito";
  }
  js_OpenJanelaIframe("CurrentWindow.corpo.iframe_contranslr", "db_iframe_conplanoexe", sUrlHistorico, "Pesquisa Debito", lMostra);
}

function js_completaDebito(sDescricaoDebito,erro, sEstrutural, lEstrutural){

  $('c60_descr').value         = sDescricaoDebito;

  if (lEstrutural) {
    $('c47_debito').value = sEstrutural;
  } else {
    $('c60_estrut_debito').value = sEstrutural;
  }
  if(erro==true){
    $('c47_debito').value        = '';
    $('c60_estrut_debito').value = '';
  }
}

function js_preencheDebito(iCodigoDebito,sDescricaoDebito, sEstrutural){
  $('c47_debito').value  = iCodigoDebito;
  $('c60_descr').value   = sDescricaoDebito;
  $('c60_estrut_debito').value  = sEstrutural;
  db_iframe_conplanoexe.hide();
}

/*  Pesquisa Crédito */
function js_pesquisaCredito(lMostra, lPesquisaElemento){

  var sUrlHistorico = "";
  if(lMostra){
    sUrlHistorico  = "func_conplanoexe.php?funcao_js=parent.js_preencheCredito|c62_reduz|c60_descr|c60_estrut";
  }else{

    var sChavePesquisa = $F('c47_credito');
    if (lPesquisaElemento) {
      sChavePesquisa = $F('c60_estrut_credito');
    }

    if (sChavePesquisa == "") {
      $('c47_credito').value  = '';
      $('c60_descr_credito').value   = '';
      $('c60_estrut_credito').value  = '';
      return false;
    }
    sUrlHistorico  = "func_conplanoexe.php?pesquisa_chave="+sChavePesquisa+"&lEstrutural="+lPesquisaElemento+"&funcao_js=parent.js_completaCredito";
  }
  js_OpenJanelaIframe("CurrentWindow.corpo.iframe_contranslr", "db_iframe_conplanoexe_credito", sUrlHistorico, "Pesquisa Credito", lMostra);
}

function js_completaCredito(sDescricaoCredito,erro, sEstrutural, lEstrutural){

  $('c60_descr_credito').value = sDescricaoCredito;
  if (lEstrutural) {
    $('c47_credito').value = sEstrutural;
  } else {
    $('c60_estrut_credito').value = sEstrutural;
  }

  if(erro==true){
    $('c47_credito').value = '';
    $('c60_estrut_credito').value = '';
  }
}

function js_preencheCredito(iCodigoCredito,sDescricaoCredito, sEstrutural){

  $('c47_credito').value        = iCodigoCredito;
  $('c60_descr_credito').value  = sDescricaoCredito;
  $('c60_estrut_credito').value = sEstrutural;
  db_iframe_conplanoexe_credito.hide();
}

/**
 * Verifica o tipo de comparação e mostra ou não o campo do elemento
 */
function js_validaTipoComparacao(iValor) {

  resetaRegraComparacao();
  switch (iValor) {

    case '0':

      $("trComparaNao").style.display                   = '';
      $("trComparaDebitoCreditoElemento").style.display = 'none';
      break;

    case '4':

      resetaRegraComparacao();
      $('NomeCompara').innerHTML = "Conta do Plano Orçamentário:";
      break;

    case '5':

       $("trComparaDebitoCreditoElemento").style.display = 'none';
       $('trCategoriaContrato').style.display            = '';
      break;

    case '6':

      resetaRegraComparacao();
      break;

    case '12':
    case '13':

      resetaRegraComparacao();
      $('trConcarPeculiar').style.display               = '';
      $("trComparaDebitoCreditoElemento").style.display = 'none';
      break;

    default:

      resetaRegraComparacao();
      break;
  }
}

/**
 * Reseta os campos a serem mostrados conforme padrão da tela.
 */
function resetaRegraComparacao() {

  $("trComparaNao").style.display                   = 'none';
  $("trComparaDebitoCreditoElemento").style.display = '';
  $('NomeCompara').innerHTML                        = "Elemento:";
  $('trCategoriaContrato').style.display            = 'none';
  $('trConcarPeculiar').style.display               = 'none';
}

var oToggle = new DBToogle("fieldsetCamposReferencia", false);
js_loadRegrasEventoContabil();


$('btnLimparFormulario').observe('click', function () {

  $('c47_seqtranslr').value     = '';
  $('c47_debito').value         = '';
  $('c47_credito').value        = '';
  $('c47_obs').value            = '';
  $('c47_ref').value            = '';
  $('c47_anousu').value         = '';
  $('c47_compara').value        = '';
  $('c47_tiporesto').value      = '';
  $('c60_estrut_debito').value  = '';
  $('c60_descr').value          = '';
  $('c60_estrut_credito').value = '';
  $('c60_descr_credito').value  = '';
  $('c47_compara').value        = 0;
  $('c47_tiporesto').value      = 0;
  $('c47_ref').value            = 0;
  $('sElemento').value          = '';
  $('c47_obs').value            = '';
});

function js_buscaCategoriaContrato () {

  var oParam  				= new Object();
  oParam.exec 				= "categoriaContrato";
  js_divCarregando("Aguarde, buscando dados...", "msgBox");
  new Ajax.Request("con4_regraeventocontabil.RPC.php",
                    { method:'post',
                      parameters:'json='+Object.toJSON(oParam),
                      onComplete: js_retornoCategoriaContrato
                    }
                  );
}

function js_retornoCategoriaContrato(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")")
  js_removeObj("msgBox");
  oRetorno.aCategoriaContrato.each(function(oCategoria) {

	  var oOption       = document.createElement('option');
	  oOption.value     = oCategoria.ac50_sequencial;
	  oOption.innerHTML = oCategoria.ac50_descricao.urlDecode();
	  $('categoriaContrato').appendChild(oOption);
	});
}

js_buscaCategoriaContrato();
</script>