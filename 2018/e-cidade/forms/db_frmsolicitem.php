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

include(modification("dbforms/db_classesgenericas.php"));
require_once(modification("model/compilacaoRegistroPreco.model.php"));
$cliframe_alterar_excluir      = new cl_iframe_alterar_excluir();
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html_novo();
//MODULO: compras
$clsolicitem->rotulo->label();
$clveiculos->rotulo->label();
$clsolicitemveic->rotulo->label();
$clpcdotac->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("o47_coddot");
$clrotulo->label("pc16_codmater");
$clrotulo->label("pc01_servico");
$clrotulo->label("pc17_unid");
$clrotulo->label("pc17_quant");
$clrotulo->label("pc14_veiculos");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_codele");
$clrotulo->label("o103_pactovalor");
$clrotulo->label("o109_descricao");
$pcmateranterior = "";
if (isset($pc16_codmater)) {
  $pcmateranterior = $pc16_codmater;
}

/**
 * Busca parametros da insitutuicao atual
 */
$sSqlParametros        = $clpcparam->sql_query_file(db_getsession("DB_instit"));
$rsParametros          = $clpcparam->sql_record($sSqlParametros);
$oDadosParametros      = db_utils::fieldsMemory($rsParametros, 0);
$lValidarValorUnitario = $oDadosParametros->pc30_gerareserva == 't';

if (isset($pc11_numero) && (!isset ($opcao) || (isset($opcao) && $opcao != "alterar" && $opcao != "excluir"))
    && isset($verificado)
) {

  //Verifica se está sendo acessada a rotina pela acessa itens com Licitação ou processo de compras
  $sql = " select max(pc11_seq) as pc11_seq
	           from solicitem
			   left join pcprocitem on pcprocitem.pc81_solicitem     = pc11_codigo
			   left join liclicitem on liclicitem.l21_codpcprocitem  = pc81_codprocitem
			  where ";
  if (isset($ccodproc) && $ccodproc != "") {
    $sql .= " pc81_codproc = $ccodproc ";
  }
  if (isset($codliclicita) && $codliclicita != "") {
    $sql .= " l21_codliclicita = $codliclicita";
  } else {
    $sql .= " pc11_numero = " . $pc11_numero;
  }

  $result_pc11_seq = $clsolicitem->sql_record($sql);
  if ($clsolicitem->numrows > 0) {
    db_fieldsmemory($result_pc11_seq, 0);
    $pc11_seq += 1;
  } else {
    $pc11_seq = 1;
  }

}

if (isset ($param) && trim($param) != "") {
  $parametro = "&param=" . $param;
  if (isset ($codproc) && trim($codproc) != "") {
    $parametro .= "&codproc=" . $codproc;
    $codproc2 = $codproc;
  }

  if (isset ($codliclicita) && trim($codliclicita) != "") {
    $parametro .= "&codliclicita=" . $codliclicita;
    $codliclicita2 = $codliclicita;
  }
} else {
  $parametro = "";
}

echo "
     <script>
       function js_passaparam(opcao){
				 qry  = 'verificado=ok';
				 val  = CurrentWindow.corpo.iframe_solicita.document.form1.opselec.value;
				 qry += '" . $parametro . "';

				 opc = '';

				 if(opcao == 'alterar' || opcao == 'excluir'){
				   opc += '&pc11_codigo=" . @$pc11_codigo . "';
				   opc += '&opcao='+opcao;
				 }
				 if(val == '1'){
				   qry += '&selecao=1';
				 }else if(val == '2'){
				   qry += '&selecao=2';
				 }else if(val == '3'){
				   qry += '&selecao=3';
				 }
				 qry += '&pc11_numero=$pc11_numero';
				 qry += opc;
				 location.href = 'com1_solicitem001.php?'+qry;
       }
     ";
if (!isset ($verificado) && !isset ($codigomaterial)) {
  echo "js_passaparam('" . @$opcao . "');";
}
echo "
     </script>
     ";
/**
 * Verificamos se a solicitação é de um registro de preco(pc10_solicitacaotipo = 5);
 * devemos trazer na lookup dos itens somente os itens que fazem parte do registro de preco
 */
$oDaoSolicitaVInculo         = db_utils::getDao("solicitavinculo");
$iRegistroPreco              = '';
$iFormaControleRegistroPreco = 1;
$sWhere                      = "pc53_solicitafilho = {$pc11_numero}";
$sSqlRegistroPreco           = $oDaoSolicitaVInculo->sql_query(null, "pc53_solicitapai", null, $sWhere);
$rsRegistroPreco             = $oDaoSolicitaVInculo->sql_record($sSqlRegistroPreco);

if ($oDaoSolicitaVInculo->numrows > 0) {
  $iRegistroPreco = db_utils::fieldsMemory($rsRegistroPreco, 0)->pc53_solicitapai;
}
$trancaCodEle = 1;
if (isset($pc11_codigo) && $pc11_codigo != '') {

  // faremos uma query na pcdotac para ver se o item, possui dotação
  // caso nao possua, podemos liberar a seleção de Sub elelemnto
  $oDaoPcDotac = db_utils::getDao("pcdotac");
  $sSqlPcDotac = $oDaoPcDotac->sql_query_file(null, null, null, "*", null, "pc13_codigo = {$pc11_codigo}");
  $rsPcDotac   = $oDaoPcDotac->sql_record($sSqlPcDotac);
  if ($oDaoPcDotac->numrows > 0) {
    // possui dotacao e bloqueamos o campo
    $trancaCodEle = 3;
  }
}
?>

<form name="form1" method="post" action="" onsubmit=" return js_validarFormulario(); ">
  <center>
    <table border="0" height="100%">
      <tr>
        <td nowrap>
          <?= @$Lpc11_numero ?>
        </td>
        <td nowrap>
          <?php
          db_input('pc11_numero', 8, $Ipc11_numero, true, 'text', 3)
          ?>
        </td>
        <td nowrap>
          <?= @$Lpc11_codigo ?>
        </td>
        <td nowrap>
          <?php
          db_input('pc11_codigo', 8, $Ipc11_codigo, true, 'text', 3)
          ?>
        </td>
        <td nowrap>
          <?= @$Lpc11_seq ?>
        </td>
        <td nowrap>
          <?php
          db_input('pc11_seq', 8, $Ipc11_seq, true, 'text', 3);
          ?>
        </td>
        <td>
          <input name="info" type="button" id="info" value="Outras informações"
                 onclick="js_abrejan();" disabled>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc16_codmater ?>">
          <?php
          // $tranca --> variável q torna o campo pc16_codmater readOnly
          $tranca = 1;
          // $tquant --> variável q passa para o iframe se valor ou quant é readOnly
          $tquant = false;

          if (isset($pc11_codigo) && trim($pc11_codigo) != "") {
            $result_dotacaoitem = $clpcdotac->sql_record($clpcdotac->sql_query_file(@$pc11_codigo,
                                                                                    db_getsession("DB_anousu"),
                                                                                    null,
                                                                                    "pc13_coddot"));
            if ($clpcdotac->numrows > 0) {
              $tranca = 3;
            }
          }
          if ($iRegistroPreco != "" && (isset($pc16_codmater) && trim($pc16_codmater) != "")) {

            $oRegistroPreco              = new compilacaoRegistroPreco($iRegistroPreco);
            $iFormaControleRegistroPreco = $oRegistroPreco->getFormaDeControle();

            if ($pc11_codigo != "") {

              $oDaoSolicitemVinculo = new cl_solicitemvinculo;
              $sSqlVerificaVinculo  = $oDaoSolicitemVinculo->sql_query_file(null,
                                                                            "*",
                                                                            null,
                                                                            "pc55_solicitemfilho={$pc11_codigo}");
              $rsVerificaVinculo    = $oDaoSolicitemVinculo->sql_record($sSqlVerificaVinculo);
              if ($oDaoSolicitemVinculo->numrows > 0) {
                $codigoitemregistropreco = db_utils::fieldsMemory($rsVerificaVinculo, 0)->pc55_solicitempai;
              }
            }
            if (!isset($codigoitemregistropreco)) {
              $codigoitemregistropreco = $registroprecoorigem;
            }
            $oFornecedor = $oRegistroPreco->getFornecedorItem($pc16_codmater, $codigoitemregistropreco);
            $pc11_vlrun  = ($iFormaControleRegistroPreco
                            == aberturaRegistroPreco::CONTROLA_QUANTIDADE ? $oFornecedor->valorunitario : '');
            $pc17_unid   = $oFornecedor->unidade;
            $pc17_quant  = $oFornecedor->quantidadeunidade;

            /**
             * devemos criar um input com o codigo do item original da Solicitacao:
             *
             */
            $registroprecoorigem = @$codigoitemregistropreco;
            db_input("registroprecoorigem", 10, $Ipc11_codigo, true, "hidden");
            if ($pc11_codigo == "") {

              $oItemCompilacao = new itemCompilacao($registroprecoorigem);
              $pc11_resum      = urldecode($oItemCompilacao->getResumo());
              $pc11_just       = urldecode($oItemCompilacao->getJustificativa());
              $pc11_prazo      = urldecode($oItemCompilacao->getPrazos());
              $pc11_pgto       = urldecode($oItemCompilacao->getPagamento());

            }
          } else {

            if ($pc30_valoraproximadoautomatico == "t") {
              if ($pc11_vlrun == "" && !empty($pc16_codmater)) {
                $pc11_vlrun = itemSolicitacao::calculaMediaPrecoOrcamentos(itemSolicitacao::getUltimosOrcamentos($pc16_codmater,
                                                                                                                 array($pc17_unid)));
              }
            }
          }
          if (isset($pc16_codmater) && trim($pc16_codmater) != "" && isset($verificado)
              && (!isset($sqlerro)
                  || (isset($sqlerro)
                      && $sqlerro != true))
          ) {
            if ((isset ($alterar) || isset ($excluir) || isset ($incluir)
                 || (isset ($opcao)
                     && ($opcao == "alterar"
                         || $opcao == "excluir")))
                && isset ($sqlerro)
                && $sqlerro == false
            ) {
              $tranca = 3;
            }
            $result_servico = $clpcmater->sql_record($clpcmater->sql_query($pc16_codmater,
                                                                           "pc01_servico, pc01_descrmater",
                                                                           "pc01_codmater"));
            if ($clpcmater->numrows > 0) {
              db_fieldsmemory($result_servico, 0);
              if ($pc01_servico == "t") {
                $tquant = true;
              }
            }


          }
          if (!isset ($pc01_servico) || (isset ($pc01_servico) && $pc01_servico == "")) {
            $pc01_servico = 'f';
          }

          db_ancora(@$Lpc16_codmater, "js_pesquisapc16_codmater(true);", $tranca);
          ?>
        </td>
        <td nowrap>
          <?php
          db_input('pc16_codmater',
                   8,
                   $Ipc16_codmater,
                   true,
                   'text',
                   $tranca,
                   " onchange='js_pesquisapc16_codmater(false);'");
          db_input("iCodigoRegistro", 8, "iCodigoRegistro", true, 'hidden', $db_opcao);
          db_input("pc01_veiculo", 8, "", true, 'hidden', $db_opcao);
          db_input("codigoitemregistropreco", 8, "", true, 'hidden', $db_opcao);
          ?>
        </td>
        <td colspan="7">
          <?php

          if (!isset ($pc11_quant) || (isset ($pc11_quant) && $pc11_quant == "")) {
            $pc11_quant = 1;
          }
          db_input('pc01_descrmater', 65, $Ipc01_descrmater, true, 'text', 3, '');
          $result_unidade = array();
          $desabilita_qtd = array();
          if (isset ($verificado)) {
            $result_sql_unid = $clmatunid->sql_record($clmatunid->sql_query_file(null,
                                                                                 "m61_codmatunid,substr(m61_descr,1,20) as m61_descr,m61_usaquant,m61_usadec",
                                                                                 "m61_descr"));
            $numrows_unid    = $clmatunid->numrows;
            echo "<script>
                    			          arr_desabilitaqtd = new Array();\n;
                    			          arr_ksadecimalqtd = new Array();\n";
            for ($i = 0; $i < $numrows_unid; $i++) {
              db_fieldsmemory($result_sql_unid, $i);
              $result_unidade[$m61_codmatunid] = $m61_descr;
              echo "
                                    arr_desabilitaqtd[$m61_codmatunid] = '$m61_usaquant';\n
                                    arr_ksadecimalqtd[$m61_codmatunid] = '$m61_usadec';\n
                                   ";
            }
            echo "
                                    function js_desabilidaqtd(valor){
                    				          if(arr_desabilitaqtd[valor]=='f'){
                    				            document.form1.pc17_quant.type = 'hidden';
                    							      document.form1.pc17_quant.value = '1';
                    							      document.form1.pc01_descrmater.size='65';
                    							    }else if(arr_desabilitaqtd[valor]=='t'){
                    							      document.form1.pc17_quant.type = 'text';
                    							      document.form1.pc01_descrmater.size='65';
                    							    }
                    							  }
                                    function js_verificainteger(){
                                      x = document.form1;
                                      valor = new Number(x.pc11_quant.value);
                                      valor = valor.toString();
                                      if(arr_ksadecimalqtd[x.pc17_unid.value]=='f' && valor.indexOf('.')!=-1){
                                        alert('Unidade selecionada não permite quantidade com valor decimal. Verifique.');
                                        x.pc11_quant.value = '" . $pc11_quant . "';
                                        x.pc11_quant.focus();
                                        x.pc11_quant.select();
                                 ";
            if ($db_opcao == 1) {
              echo "    js_preencheqtd('" . $pc11_quant . "')";
            }
            echo "
                                      }
                    							  }
                    			        </script>
                                 ";
            if (isset ($pc11_codigo) && (!isset ($pc17_unid) || (isset ($pc17_unid) && $pc17_unid == ""))) {
              $result_solicitemunid = $clsolicitemunid->sql_record($clsolicitemunid->sql_query_file($pc11_codigo));
              if ($clsolicitemunid->numrows > 0) {
                db_fieldsmemory($result_solicitemunid, 0);
              } else {
                unset ($pc17_unid);
              }
            }
            if (!isset ($pc17_unid) || (isset ($pc17_unid) && $pc17_unid == "")) {
              $result_uniddefault = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),
                                                                                      "pc30_unid as pc17_unid,pc30_digval"));
              if ($clpcparam->numrows > 0) {
                db_fieldsmemory($result_uniddefault, 0);
              }
            }
          }
          if (!isset ($pc17_quant)) {
            $pc17_quant = 1;
          }
          $db_opcaounidade = $db_opcao;
          if ($iRegistroPreco != "") {
            $db_opcaounidade = 33;
          }
          db_select("pc17_unid",
                    $result_unidade,
                    true,
                    $db_opcaounidade,
                    "onchange='js_calculaMedia();js_desabilidaqtd(this.value);js_verificainteger();'");
          db_input("pc17_quant",
                   5,
                   $Ipc17_quant,
                   true,
                   'hidden',
                   $db_opcaounidade,
                   "onblur='if (this.value <= 0) alert(\"valor deve ser maior que zero,\");'");
          ?>
        </td>
      </tr>
      <?php
      if (isset ($opcao) && isset ($pc11_codigo) && $opcao != "incluir") {
        $rsVeiculo = $clsolicitemveic->sql_record($clsolicitemveic->sql_query(null,
                                                                              "pc14_sequencial,pc14_veiculos,ve01_placa",
                                                                              "",
                                                                              " pc14_solicitem = $pc11_codigo"));
        if ($clsolicitemveic->numrows > 0) {
          db_fieldsmemory($rsVeiculo, 0);
          $pc01_veiculo = "t";
        }
      }
      if (isset ($pc01_veiculo) && $pc01_veiculo != "") {
        ?>
        <tr id="MostraVeiculos">
          <td nowrap title="<?= @$Tve01_codigo ?>">
            <?php
            db_ancora($Lve01_codigo, "js_pesquisave01_veiculo(true);", $tranca);
            db_input("pc14_sequencial", 8, "", true, 'hidden', $db_opcao);
            ?>
          </td>
          <td>
            <?php
            db_input("pc14_veiculos",
                     8,
                     $Ipc14_veiculos,
                     true,
                     'text',
                     $tranca,
                     "onchange='js_pesquisave01_veiculo(false)';");
            ?>
          </td>
          <td>
            <?php
            db_input("ve01_placa", 10, $Ive01_placa, true, 'text', 3);
            ?>
          </td>
        </tr>
        <?php
      }
      ?>
      <tr>
        <td nowrap title="<?= @$Tpc11_quant ?>">
          <?= @$Lpc11_quant ?>
        </td>
        <td nowrap>
          <?php
          db_input('pc11_quant',
                   8,
                   $Ipc11_quant,
                   true,
                   'text',
                   $db_opcao,
            ($db_opcao == 1 ? "onchange='js_verificainteger();'" : "onchange='js_verificainteger();'"))
          ?>
        </td>
        <td nowrap title="Quantidade restante a ser lançada">
          <strong><?php echo($iFormaControleRegistroPreco
                             == aberturaRegistroPreco::CONTROLA_VALOR ? "Saldo" : "Quantidade restante"); ?>:</strong>
        </td>
        <td nowrap>
          <?php
          db_input('quant_rest', 8, $Ipc11_quant, true, 'text', 3, "")
          ?>
        </td>
        <?php
        $hidval = "text";
        if ($pc30_digval == 't') {
          echo "<td nowrap>";
          echo $Lpc11_vlrun;
          echo "</td>";
          echo "<td nowrap>";
        } else {
          $hidval         = "hidden";
          $pc11_vlrun_ant = $pc11_vlrun;
          $pc11_vlrun     = 0;
        }

        if (isset ($pc11_vlrun) && $pc11_vlrun != "") {
          $pc11_vlrun = str_replace(",", ".", $pc11_vlrun);
          if (strpos($pc11_vlrun, ".") == "") {
            $pc11_vlrun .= ".";
            $tam        = strlen($pc11_vlrun) + 2;
            $pc11_vlrun = str_pad($pc11_vlrun, $tam, '0', STR_PAD_RIGHT);
          }
          if ($hidval != "hidden") {
            $pc11_vlrun_ant = $pc11_vlrun;
          }
        }
        $db_opcaovunit = $iRegistroPreco == "" ? $db_opcao : 3;
        db_input('pc11_vlrun', 8, $Ipc11_vlrun, true, $hidval, $db_opcaovunit);
        if ($pc30_digval == 't') {
          echo "</td>";
        }

        // Alteração feita para processo de compra e licitacao
        if (isset ($param) && trim($param) != "") {
          db_input("param", 10, "", false, "hidden", 3);
          db_input("codproc", 10, "", false, "hidden", 3);
          db_input("codliclicita", 10, "", false, "hidden", 3);
        }
        ?>
        <td colspan="2">
          <?php if ($pc30_maximodiasorcamento > 0) { ?>
            <input type='button' value='Últimos Orçamentos' id='ultimosorcamentos'>
          <?php } ?>
          <input
            name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
            type="submit" id="db_opcao"
            value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>"
            onclick='return js_testaQuant(<?= $db_opcao ?>)'>
          <?php
          if ($db_opcao == 2 || ($db_opcao == 3 && $db_botao == true)) {
            echo "<input name='novo' type='button' id='novo' value='Novo' onclick= 'document.location.href=\"com1_solicitem001.php?pc11_numero=$pc11_numero$parametro\"'>";
          }
          ?>
        </td>
      </tr>
      <tr>
        <?php

        if (isset ($o56_codele) && $o56_codele != "") {
          $o56_elemento_ant = $o56_codele;
        }
        if (isset ($pc16_codmater) && trim($pc16_codmater) != "") {
          $sql_record = $clpcmaterele->sql_record($clpcmaterele->sql_query($pc16_codmater,
                                                                           null,
                                                                           "o56_codele,o56_descr,o56_elemento",
                                                                           "o56_descr"));
          $dad_select = array();
          for ($i = 0; $i < $clpcmaterele->numrows; $i++) {
            db_fieldsmemory($sql_record, $i);
            $dad_select[$o56_codele] = $o56_codele . " - " . $o56_elemento . " - " . $o56_descr;
          }
        }
        if (isset ($o56_codele_ant)) {
          $o56_codele = $o56_elemento_ant;
        }
        $numrows_materele = $clpcmaterele->numrows;
        if ($numrows_materele > 0) {
          ?>
          <td nowrap title="<?= @$To56_descr ?>">
            <strong>Sub. ele:</strong>
          </td>
          <td nowrap colspan="6">
            <?php
            $result_pc18ele = $clsolicitemele->sql_record($clsolicitemele->sql_query_file($pc11_codigo,
                                                                                          null,
                                                                                          "pc18_codele as o56_codele"));
            if ($clsolicitemele->numrows > 0) {
              db_fieldsmemory($result_pc18ele, 0);
            }

            db_select("o56_codele", $dad_select, true, $trancaCodEle, "");
            if (isset ($o56_codelefunc) && $o56_codelefunc != "") {
              echo "<script>document.form1.o56_codele.value=$o56_codelefunc;</script>";
            }
            ?>
          </td>
          <?php
        }
        db_input("o56_codelefunc", 5, $Io56_codele, true, 'hidden', $db_opcao);
        ?>
      </tr>

      <tr id='ctnServicoQuantidade' style="display:none;">
        <td><strong>Serviço Controlado por Quantidades: </strong></td>
        <td>
          <?php
          $aOpcoes = array("false" => "NÃO", "true" => "SIM");
          db_select('pc11_servicoquantidade',
                    $aOpcoes,
                    true,
                    $db_opcao,
                    "onchange='js_habilitaCamposServico(this.value);'");
          ?>
        </td>
      </tr>
      <?php
      if ($lMostraItensPacto) {

        echo "<tr>";
        echo "  <td>";
        db_ancora("<b>Item do Plano</b>", "js_pesquisao103_pactovalor(true);", $tranca);
        echo "  </td>";
        echo "  <td>";
        db_input('o103_pactovalor',
                 8,
                 $Io103_pactovalor,
                 true,
                 'text',
                 $tranca,
                 " onchange='js_pesquisao103_pactovalor(false);'");
        echo "  </td>";
        echo "  <td colspan=7>";
        db_input('o109_descricao', 40, $Io109_descricao, true, 'text', 3, '');
        echo "  </td>";
        echo "</tr>";

      }
      ?>
    </table>
    <hr>
  </center>
  <center>
    <?php
    $des = "false";
    if (isset ($quant_rest) && $quant_rest == 0) {
      $des = "true";
    }
    $qry = "pc11_numero=" . @$pc11_numero;
    $qry .= "&pc13_codigo=" . @$pc11_codigo;
    $qry .= "&db_opcion=" . @$opcao;
    $qry .= "&iframe=" . @$iframe;
    $qry .= "&des=" . $des;
    $qry .= "&pactoplano=" . $iPactoPlano;
    if ($tquant == true) {
      $qry .= "&tquant=true";
    } else {
      $qry .= "&tquant=false";
    }
    if (isset ($sqlerro) && $sqlerro == true) {
      $qry .= "&errado=true";
    } else if (isset ($sqlerro) && $sqlerro == false) {
      $qry .= "&errado=false";
    }

    $qry .= $parametro;

    ?>
    <center>
      <table width="100%" height="40%" border="0">
        <tr>
          <td width="100%" height="100%">
            <iframe name="lanc_dotac" id="lanc_dotac"
                    marginwidth="0" marginheight="0"
                    frameborder="0" src="com1_solicitemiframe001.php?<?= $qry ?>" width="100%" height="250">
            </iframe>
          </td>
        </tr>
      </table>
    </center>
    <hr>
    <table border="0" width="100%" height="50%">
      <tr>
        <td valign="top" align="center" width="100%">
          <?php
          $codigos = "";
          if ($pc30_ultdotac == "t") {
            $res_itens = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,
                                                                                  "pc11_codigo as codigo",
                                                                                  "pc11_codigo",
                                                                                  "pc11_numero= " . @$pc11_numero));
            if ($clsolicitem->numrows > 0) {
              $virgula = "";
              $codigos = "pc11_codigo in (";
              for ($i = 0; $i < $clsolicitem->numrows; $i++) {

                db_fieldsmemory($res_itens, $i);
                $codigos .= $virgula . $codigo;
                $virgula = ", ";
              }
              $codigos .= ") and";
            }
          }

          $sql_dot = $clsolicitem->sql_query_dot(null,
                                                 "pc11_codigo,
                                             					      pc11_quant,
                    						                            sum(pc13_quant)",
                                                 "",
                                                 "",
                                                 "group by pc10_numero,
                    						    	                               pc10_depto,
                    							                                   pc11_codigo,
                    							                                   pc11_quant,
                    							                                   pc11_numero,
                    							                                   pc13_codigo
                    						                              having (pc11_quant > sum(pc13_quant) or pc13_codigo is null)
                    						                                 and pc11_numero = " . $pc11_numero);

          $sSqlVerificaAut = " select solicitem.* ";
          $sSqlVerificaAut .= "		from solicitem ";
          $sSqlVerificaAut .= "        inner join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo";
          $sSqlVerificaAut .= "        inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
          $sSqlVerificaAut .= "        inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
          $sSqlVerificaAut .= "                                       and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
          $sSqlVerificaAut .= "				 inner join empautoriza          on e54_autori			                    = e55_autori";
          $sSqlVerificaAut .= "	 where pc11_numero = {$pc11_numero}";
          $sSqlVerificaAut .= "	   and e54_anulad is null";

          $sql_servico      = $clsolicitem->sql_query_serv(null,
                                                           "pc11_codigo as codsol,pc11_vlrun",
                                                           "pc11_codigo",
                                                           "pc11_numero = $pc11_numero ");
          $sql_reservasaldo = $clorcreservasol->sql_query_saldo(null,
                                                                null,
                                                                "round(o80_valor,2)  as valorreserva,
                                                                                       round(pc13_valor,2) as valordotacao,
                                                                                       pc11_numero,
                                                                                       pc11_codigo as pc11_codigo_reserva,
                                                                                       pc11_seq",
                                                                "pc11_numero,pc11_codigo",
                                                                "pc11_numero = $pc11_numero");

          $chavepri                                   = array(
            "pc11_numero" => $pc11_numero, "pc11_codigo" => @$pc11_codigo
          );
          $cliframe_alterar_excluir->chavepri         = $chavepri;
          $sCampos                                    = "pc11_seq,
                                pc11_codigo,
                                pc11_numero,
                                pc13_coddot,
                                pc19_orctiporec,
                                pc01_codmater,
                                case when pc16_codmater is null then substr(pc11_resum,1,40)
                                     else substr(pc01_descrmater,1,40)
                                end as pc01_descrmater,
                                pc13_quant,
                                pc13_valor";
          $cliframe_alterar_excluir->sql              = $clsolicitem->sql_query_pcmater(null,
                                                                                        $sCampos,
                                                                                        "pc11_seq, pc11_codigo",
                                                                                        "$codigos pc11_numero= "
                                                                                        . @$pc11_numero);
          $cliframe_alterar_excluir->campos           = "pc11_seq,pc11_codigo,pc11_numero,pc13_coddot,pc19_orctiporec,pc01_codmater,pc01_descrmater,pc13_quant,pc13_valor";
          $cliframe_alterar_excluir->legenda          = "ITENS CADASTRADOS";
          $cliframe_alterar_excluir->iframe_height    = "150";
          $cliframe_alterar_excluir->iframe_width     = "100%";
          $cliframe_alterar_excluir->textocabec       = "black";
          $cliframe_alterar_excluir->textocorpo       = "black";
          $cliframe_alterar_excluir->fundocabec       = "#999999";
          $cliframe_alterar_excluir->fundocorpo       = "#cccccc";
          $cliframe_alterar_excluir->strFormatar      = '1';
          $cliframe_alterar_excluir->sql_comparar     = $sql_dot;
          $cliframe_alterar_excluir->sql_servico      = $sql_servico;
          $cliframe_alterar_excluir->sql_reservasaldo = $sql_reservasaldo;
          $cliframe_alterar_excluir->sql_disabled     = $sSqlVerificaAut; // Desabilita Itens que tem Autorização de Empenho
          $cliframe_alterar_excluir->campos_comparar  = "pc11_codigo";

          $val     = 1;
          $verific = "N";
          if ($db_botao == false && $db_opcao == 3) {
            $val     = 3;
            $verific = "S";
          }
          $cliframe_alterar_excluir->opcoes   = $val;
          $cliframe_alterar_excluir->fieldset = true;
          if (isset ($pc11_codigo) && trim($pc11_codigo) != "") {
            $cliframe_alterar_excluir->msg_vazio = ($db_opcao == 1 ? "Inclusão" : ($db_opcao == 2
                                                                                   || $db_opcao
                                                                                      == 22 ? "Alteração" : "Exclusão"))
                                                   . " do item $pc11_codigo";
          } else {
            $cliframe_alterar_excluir->msg_vazio = "Cadastre o item";
          }
          ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"));
          if ($pc30_ultdotac == "t") {
            $cliframe_alterar_excluir->iframe_alterar_excluir(1);
          } else {
            $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          }
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <center>
            <b>Se após cadastrar um item esse permanecer em vermelho, o cadastro esta incorreto.</b>
          </center>
        </td>
      </tr>
    </table>
  </center>
  <?php
  $pc11_liberado = 'f';
  if (!isset ($pc11_resum) || (isset ($pc11_resum) && $pc11_resum == "")) {
    $digitouresumo = "false";
  }
  @$pc11_pgto = stripslashes($pc11_pgto);
  @$pc11_just = stripslashes($pc11_just);
  @$pc11_resum = stripslashes($pc11_resum);
  @$pc11_prazo = stripslashes($pc11_prazo);
  $pc11_resum = addslashes($pc11_resum);
  db_input('pc11_liberado', 1, $Ipc11_liberado, true, 'hidden', 3);
  db_input('pc11_pgto', 40, $Ipc11_pgto, true, 'hidden', 3);
  db_textarea('pc11_resum', 10, 40, $Ipc11_resum, true, 'text', 3, "style='display:none'");
  db_textarea('pc11_just', 10, 40, $Ipc11_just, true, 'hidden', 3, "style='display:none'");
  db_textarea('pc11_prazo', 10, 40, $Ipc11_prazo, true, 'hidden', 3, "style='display:none'");
  db_input('digitouresumo', 5, 0, true, 'hidden', 3);

  db_input('pc11_vlrun_ant', 40, $Ipc11_vlrun, true, 'hidden', 3);
  db_input('pc11_quant_ant', 40, $Ipc11_vlrun, true, 'hidden', 3);
  db_input('pc16_codmater_ant', 40, $Ipc16_codmater, true, 'hidden', 3);
  db_input('pc01_servico', 40, $Ipc01_servico, true, 'hidden', 3);

  db_input('db_opcao', 5, 0, true, 'hidden', 3);
  db_input('db_botao', 5, 0, true, 'hidden', 3);
  if ($pc30_digval == "t") {
    echo "<script>document.form1.pc11_vlrun_ant.value = document.form1.pc11_vlrun.value;</script>";
  }
  echo "<script>document.form1.pc11_quant_ant.value = document.form1.pc11_quant.value;</script>";
  echo "<script>document.form1.pc16_codmater_ant.value = document.form1.pc16_codmater.value;</script>";
  ?>
</form>
<script>

  /**
   * Codigo do material informado, pesquisa quantidade restante do item da estimativa
   */
  if (!empty($('pc16_codmater').value)) {
    js_buscarQuantidadeRestanteItemEstimativa();
  }

  function js_abrejan() {
    qry = "pc11_codigo=" + document.form1.pc11_codigo.value;
    qry += "&pc11_numero=" + document.form1.pc11_numero.value;
    qry += "&pc16_codmater=" + document.form1.pc16_codmater.value;
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                        'db_iframe',
                        'func_itenssol.php?' + qry,
                        'Pesquisa',
                        true,
                        '0'
    );

  }
  function js_pesquisave01_veiculo(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                          'db_iframe_veiculos',
                          'func_veiculos.php?funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_mostraveiculo1|ve01_codigo|ve01_placa<?=$db_opcao
                                                                                                                                              == 1 ? "&opcao_bloq=3&opcao=f" : "&opcao_bloq=1&opcao=i"?>',
                          'Pesquisa',
                          true,
                          '0'
      );
    } else if (document.form1.pc14_veiculos.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                          'db_iframe_veiculos',
                          'func_veiculos.php?pesquisa_chave=' + document.form1.pc14_veiculos.value
                          + '&funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_mostraveiculo<?=$db_opcao
                                                                                                       == 1 ? "&opcao_bloq=3&opcao=f" : "&opcao_bloq=1&opcao=i"?>',
                          'Pesquisa',
                          false,
                          '0'
      );
    }
  }

  function js_mostraveiculo(iCodVeic, erro) {
    if (erro) {
      document.form1.pc14_veiculos.value = "";
      document.form1.ve01_placa.value = "";
    } else {
      document.form1.pc14_veiculos.value = iCodVeic;
      document.form1.ve01_placa.value = "";
    }
  }

  function js_mostraveiculo1(iCodVeic, sPlacaVeic) {
    document.form1.pc14_veiculos.value = iCodVeic;
    document.form1.ve01_placa.value = sPlacaVeic;
    db_iframe_veiculos.hide();
  }

  function js_pesquisapc16_codmater(mostra) {

    var iRegistroPrecoFuncao = false;
    <?php
    $sUrlLookup = "func_pcmatersolicita.php";
    $sFiltro = "";
    if ($iRegistroPreco != "") {

      $sUrlLookup = 'func_pcmaterregistropreco.php';
      echo "iRegistroPrecoFuncao = true;\n";
      $sFiltro = "|pc11_codigo";
    }
    ?>
    if (mostra == true || iRegistroPrecoFuncao) {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                          'db_iframe_pcmater',
                          '<?=$sUrlLookup?>?funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_mostrapcmater1'
                          + '|pc01_codmater|pc01_descrmater|o56_codele|pc01_veiculo<?=$sFiltro?><?=$db_opcao
                                                                                                   == 1 ? "&opcao_bloq=3&opcao=f" : "&opcao_bloq=1&opcao=i"?>'
                          + '&iRegistroPreco=<?=$iRegistroPreco;?>',
                          'Pesquisa de Materiais',
                          true,
                          '0'
      );
    } else {
      if (document.form1.pc16_codmater.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                            'db_iframe_pcmater',
                            '<?=$sUrlLookup?>?pesquisa_chave=' + document.form1.pc16_codmater.value
                            + '&iRegistroPreco=<?=$iRegistroPreco;?>'
                            + '&funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_mostrapcmater<?=$db_opcao
                                                                                                         == 1 ? "&opcao_bloq=3&opcao=f" : "&opcao_bloq=1&opcao=i"?>',
                            'Pesquisa',
                            false,
                            '0'
        );
      } else {
        document.form1.pc01_descrmater.value = '';
        document.form1.pc01_servico.value = "f";
        js_mostraservico('f');
        document.form1.submit();
      }
    }
  }


  function js_esconteVeic(mostra) {

    if (!mostra) {
      mostra = "f";
    }

    if (mostra == "t") {
      document.form1.pc01_veiculo.value = "t";
    } else {
      if (document.form1.pc01_veiculo.value == "t") {
        document.getElementById("MostraVeiculos").style.display = "none";
        document.form1.ve01_placa = "";
        document.form1.pc14_veiculos.value = "";
      }
      document.form1.pc01_veiculo.value = "";
    }

  }

  function js_buscaDadosComplementaresMaterial(iCodigoMaterial) {

    var oRequest = {
      exec: "getDadosMaterial", iCodigoMaterial: iCodigoMaterial
    };

    new AjaxRequest("com4_materialsolicitacao.RPC.php", oRequest, function (oResponse, lError) {

        if (lError) {
          return alert(oResponse.message.urlDecode());
        }

        if (empty(document.form1.pc11_codigo.value)) {
          document.form1.pc11_resum.value = oResponse.dados.descricaocomplemento.urlDecode();
        }

      }
    ).setMessage("Carregando dados do item.").execute();
  }


  function js_mostrapcmater(chave, erro, lVeic) {
    document.form1.pc01_descrmater.value = chave;
    document.form1.o56_codelefunc.value = '';
    if (erro == true) {
      document.form1.pc16_codmater.focus();
      document.form1.pc16_codmater.value = '';
      document.form1.info.disabled = true;
      js_esconteVeic();
    } else {
      js_esconteVeic(lVeic);
      obj = document.createElement('input');
      obj.setAttribute('name', 'codigomaterial');
      obj.setAttribute('type', 'hidden');
      obj.setAttribute('value', document.form1.pc16_codmater.value);
      document.form1.appendChild(obj);
      document.form1.info.disabled = false;
      <?php
      if (isset($opcao)) {
        echo "
            obj=document.createElement('input');
            obj.setAttribute('name','opcao');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','$opcao');
            document.form1.appendChild(obj);
          ";
      }
      ?>
      js_buscaDadosComplementaresMaterial(document.form1.pc16_codmater.value);
      js_materanterior();
    }
  }
  function js_mostrapcmater1(chave1, chave2, codele, lVeic, iRegistro) {

    js_esconteVeic(lVeic);

    if (iRegistro != null) {
      document.getElementById('codigoitemregistropreco').value = iRegistro;
    }
    document.form1.iCodigoRegistro.value = iRegistro;
    document.form1.pc16_codmater.value = chave1;
    document.form1.pc01_descrmater.value = chave2;
    document.form1.o56_codelefunc.value = codele;
    db_iframe_pcmater.hide();
    obj = document.createElement('input');
    obj.setAttribute('name', 'codigomaterial');
    obj.setAttribute('type', 'hidden');
    obj.setAttribute('value', chave1);
    document.form1.appendChild(obj);
    <?php
    if (isset($opcao)) {
      echo "
          obj=document.createElement('input');
          obj.setAttribute('name','opcao');
          obj.setAttribute('type','hidden');
          obj.setAttribute('value','$opcao');
          document.form1.appendChild(obj);
        ";
    }
    ?>
    document.form1.info.disabled = false;
    js_buscaDadosComplementaresMaterial(chave1);
    js_materanterior();
  }

  /**
   * Busca quantidade restante do item da estimativa para o departamento logado
   *
   * @access public
   * @return void
   */
  function js_buscarQuantidadeRestanteItemEstimativa() {

    var oParametros = new Object();
    oParametros.exec = "getQuantidadeRestanteItemEstimativa";
    oParametros.iItemOrigem = $('codigoitemregistropreco').value;
    oParametros.iCodigoMaterial = $('pc16_codmater').value;

    if (empty(oParametros.iItemOrigem)) {
      return false;
    }

    var oAjax = new Ajax.Request("com4_solicitacaoComprasRegistroPreco.RPC.php", {
        method: 'post', parameters: 'json=' + js_objectToJson(oParametros), onComplete: function (oAjax) {

          var oRetorno = eval("(" + oAjax.responseText + ")");
          $('quant_rest').value = oRetorno.iQuantidadeRestante;
        }
      }
    );

  }


  function js_pesquisapc01_servico(codigo) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                        'db_iframe_pcmater',
                        'func_pcmater.php?funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_mostraservico|pc01_servico&chave_pc01_codmater='
                        + codigo,
                        'Pesquisa',
                        false,
                        '0',
                        '1',
                        '790',
                        '405'
    );
  }
  function js_pesquisa() {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_solicitem',
                        'func_solicitem.php?funcao_js=parent.CurrentWindow.corpo.iframe_solicitem.js_preenchepesquisa|pc11_codigo',
                        'Pesquisa',
                        true,
                        '0',
                        '1',
                        '790',
                        '405'
    );
  }
  function js_preenchepesquisa(chave) {
    db_iframe_solicitem.hide();
    <?php
    if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
    }
    ?>
  }
  function js_preencheqtd(valor) {
    console.log('js_prenencheqtd : ' + valor);
    document.form1.quant_rest.value = valor;
  }
  if (document.form1.pc11_vlrun.value != "") {
    x = document.form1.pc11_vlrun.value;
    if (x.indexOf(".") == -1 && x.indexOf(",") == -1) {
      document.form1.pc11_vlrun.value += ".00";
    }
  }
  function js_ver(verific) {
    campo = "";
    x = document.form1;
    for (i = 0; i < x.length; i++) {
      if (x.elements[i].type == "submit") {
        if (x.elements[i].name == "incluir") {
          campo = "incluir";
        } else if (x.elements[i].name == "excluir") {
          campo = "excluir";
        }
      }
    }
    if (campo == 'excluir') {
      if (verific == "S") {
        eval("document.form1." + campo + ".disabled=true;");
      }
    }
  }
  js_ver("<?=($verific)?>");
  if (document.form1.pc01_servico.value != "") {
    js_mostraservico(document.form1.pc01_servico.value);
  }
  document.form1.pc11_quant.style.color = "black";
  document.form1.pc16_codmater.focus();
  js_desabilidaqtd(document.form1.pc17_unid.value);

  if (document.form1.pc16_codmater.value != "") {
    <?php if ($iRegistroPreco == "") {?>
    js_pesquisapc16_codmater(false);
    <?} ?>
  }

  function js_materanterior() {

    <?php
    echo "materanterior = '$pcmateranterior';\n";
    if ($iRegistroPreco != "") {
      echo "document.form1.submit()";
    } else {
      echo "
        if(materanterior!=document.form1.pc16_codmater.value){
          document.form1.submit();
        }
      ";
    }
    ?>
  }
  function js_testaQuant(iOpcao) {

    js_validaSaldoPacto();
    if (iOpcao == 1) {
      if (lItemPacto && $F('o103_pactovalor') == "") {

        alert('Item do pacto nao informado!');
        return false;

      }
    }
    var nQuant = new Number(document.getElementById('pc17_quant').value);
    if (nQuant <= 0) {

      alert('Quantidade da unidade deve ser maior que zero!');
      return false;

    } else {
      return true;
    }
  }
  function js_pesquisao103_pactovalor(mostra) {

    var sFiltro = "&programa=1&projeto=1&item=1";
    if (mostra == true) {

      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                          'db_iframe_pactovalor',
                          'func_pactovalor.php?funcao_js=parent.js_mostrapactovalor1|o87_sequencial|dl_item' + sFiltro,
                          'Pesquisa',
                          true
      );
    } else {
      if (document.form1.o103_pactovalor.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_solicitem',
                            'db_iframe_pactovalor',
                            'func_pactovalor.php?pesquisa_chave=' + document.form1.o103_pactovalor.value
                            + '&funcao_js=parent.js_mostrapactovalor' + sFiltro,
                            'Pesquisa',
                            false
        );
      } else {
        document.form1.o109_descricao.value = '';
      }
    }
  }
  function js_mostrapactovalor(chave, erro) {

    document.form1.o109_descricao.value = chave;
    if (erro == true) {
      document.form1.o103_pactovalor.focus();
      document.form1.o103_pactovalor.value = '';
    } else {

    }

  }

  function js_validaSaldoPacto() {

    if ($('o103_pactovalor')) {
      var sUrl = "com4_solicitacaocompraRPC.php";

      var oRequest = new Object();
      oRequest.iItemPacto = new Number($F('o103_pactovalor')).valueOf();
      oRequest.iQuantidade = $F('pc11_quant').valueOf();
      oRequest.exec = "getSaldos";
      oRequest.iValorTotal = oRequest.iQuantidade * new Number($F('pc11_vlrun')).valueOf();
      var oAjax = new Ajax.Request(sUrl, {
          method: 'post', parameters: 'json=' + js_objectToJson(oRequest), onComplete: js_retornogetSaldo
        }
      );
    }
  }

  function js_retornogetSaldo(oAjax) {

    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.status == 1) {
      alert(oRetorno.itens.toSource());
    }
    return true;
  }
  function js_mostrapactovalor1(chave1, chave2) {

    document.form1.o103_pactovalor.value = chave1;
    document.form1.o109_descricao.value = chave2;
    db_iframe_pactovalor.hide();

  }
  function js_mostraservico(chave) {

    document.form1.pc01_servico.value = chave;
    
    if (chave == "t" ) {
        
      document.form1.pc11_quant.readOnly = true;
      document.form1.pc11_quant.style.backgroundColor = "#DEB887";

      if (document.form1.pc11_servicoquantidade.value == 'true') {

        document.form1.pc11_quant.readOnly = false;
        document.form1.pc11_quant.style.backgroundColor = "#FFFFFF";
      }
      if ($F('pc11_servicoquantidade') == "false") {
        console.log('[1] quant_rest');
        document.form1.quant_rest.value = "1";
      }


      document.form1.pc11_vlrun.readOnly = false;
      document.form1.pc11_vlrun.style.backgroundColor = "";
      document.form1.pc11_vlrun.focus();
      document.form1.pc17_quant.style.visibility = "hidden";
      document.form1.pc17_unid.style.visibility = 'hidden';
      document.form1.pc01_descrmater.size = '65';
    } else {

      document.form1.pc11_quant.readOnly = false;
      document.form1.pc11_quant.style.backgroundColor = "";
      document.form1.pc11_quant.focus();
      document.form1.pc17_unid.style.visibility = 'visible';
      js_desabilidaqtd(document.form1.pc17_unid.value);
    }
    <?php
    if ($db_opcao == 3) {
      echo "
        document.form1.pc11_quant.readOnly=true;
        document.form1.pc11_vlrun.readOnly=true;
        document.form1.pc11_quant.style.backgroundColor='#DEB887';
        document.form1.pc11_vlrun.style.backgroundColor='#DEB887';
      ";
    }
    ?>
  }

  function js_verificaServico() {

    var lServico = $F('pc01_servico');
    var sServicoQuantidade = $F('pc11_servicoquantidade');

    if (lServico == 't') {

      $('ctnServicoQuantidade').style.display = 'table-row';

      if (sServicoQuantidade == 'false') {
        $('quant_rest').value = "1";
      }

    } else {

      $('ctnServicoQuantidade').style.display = 'none';
    }

  }

  function js_habilitaCamposServico(sServicoQuantidade) {

    if (sServicoQuantidade == 'true') {

      $('pc17_unid').style.visibility = 'visible';
      $('pc11_quant').readOnly = false;
      $('pc11_quant').style.backgroundColor = 'white';
    } else {

      $('pc17_unid').style.visibility = 'hidden';
      $('pc11_quant').readOnly = true;
      $('pc11_quant').style.backgroundColor = '#DEB887';
      $('pc11_quant').value = "1";
      $('quant_rest').value = "1";
    }

  }

  function js_verificaServicoQuantidade() {

    var sServicoQuantidade = $F('pc11_servicoquantidade');
    var lServico = $F('pc01_servico');

    if (sServicoQuantidade == '' && lServico == 't') {
      alert('Selecione a forma de controle da quantidade do serviço');
      $('pc11_servicoquantidade').focus;
      $('pc11_servicoquantidade').style.backgroundColor = '#99A9AE';
      return false;
    }
    return true;
  }

  function js_validarFormulario() {

    if (!js_verificaServicoQuantidade()) {
      return false;
    }

    if (!js_validarValorUnitario()) {
      return false;
    }

    return true;
  }

  function js_validarValorUnitario() {

    var lValidarValorUnitario = <?php echo $lValidarValorUnitario ? 'true' : 'false'; ?>;

    if (!lValidarValorUnitario) {
      return true;
    }

    var nValorUnitario = $('pc11_vlrun').value;
    if (nValorUnitario <= 0) {

      alert(_M('patrimonial.material.db_frmsolicitem.erro_valor_unitario'));
      return false;
    }

    return true;
  }

  js_verificaServico();
</script>

<?php

if (isset($pc11_codigo) && $pc11_codigo != '') {

  $oDaoSolicitem           = new cl_solicitem();
  $sWhereServicoQuantidade = "pc11_numero = {$pc11_numero} and pc11_codigo = {$pc11_codigo} ";
  $sSqlServicoQuantidade   = $oDaoSolicitem->sql_query_file(null,
                                                            "pc11_servicoquantidade",
                                                            null,
                                                            $sWhereServicoQuantidade);
  $rsServicoQuantidade     = $oDaoSolicitem->sql_record($sSqlServicoQuantidade);
  if ($oDaoSolicitem->numrows > 0) {

    $pc11_servicoquantidade = db_utils::fieldsMemory($rsServicoQuantidade, 0)->pc11_servicoquantidade;

    if ($pc11_servicoquantidade == 't') {

      echo "<script>                                                                   ";
      echo "  $('pc11_servicoquantidade').options.length = 0;                          ";
      echo "  $('pc11_servicoquantidade').options[0]     = new Option('SIM', 'true');  ";
      echo "  $('pc11_servicoquantidade').options[1]     = new Option('NÃO', 'false'); ";
      echo "  $('pc17_unid') .style.visibility           = 'visible';                  ";

      echo "  if (document.form1.pc11_servicoquantidade.value == 'true') {  ";
      echo "     document.form1.pc11_quant.readOnly=false;                  ";
      echo "     document.form1.pc11_quant.style.backgroundColor='#FFFFFF'; ";
      echo "  }                                                             ";

      echo "</script>                                                                  ";

    } else {

      echo "<script>                                                                   ";
      echo "  $('pc11_servicoquantidade').options.length = 0;                          ";
      echo "  $('pc11_servicoquantidade').options[0]     = new Option('NÃO', 'false'); ";
      echo "  $('pc11_servicoquantidade').options[1]     = new Option('SIM', 'true');  ";
      echo "</script>                                                                  ";
    }

  }
}
