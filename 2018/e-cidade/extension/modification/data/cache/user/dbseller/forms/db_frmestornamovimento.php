<?
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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("k13_conta");
$clrotulo->label("k12_cheque");
$clrotulo->label("k13_descr");
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("e60_coddot");
$clrotulo->label("e50_codord");
$clrotulo->label("c71_complem");
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();

if (!isset($reimpressao)) {  // quando reimpessão não precisa validar
  if (isset ($pag_emp)) {
    $result02 = $clpagordemele->sql_record($clpagordemele->sql_query(null, null, "sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu", "", "e60_numemp=$e60_numemp"));
    $numrows02 = $clpagordemele->numrows;

    if ($numrows02 > 0) {
      db_fieldsmemory($result02, 0);
      $vlrdis = $e60_vlrpag - $tot_vlrpag;
    } else {
      $vlrdis = $e60_vlrpag;
    }

    $vlrpag_estornar = $vlrdis;

    if ($vlrdis == 0 || $vlrdis == '') {
      $db_opcao = 33;

      if ($numrows02 > 0) {
        $erro_m = "Não existe valor pago sem ordem para ser estornado!";
      } else {
        $erro_m = "Não existe valor pago para ser estornado!";
      }
    }

  } else if (isset($pag_ord) && isset($origem_devolucao)) {

    $sSql = $clpagordemele->sql_query_file( $e50_codord,
      null,
      "sum(e53_valor) as total_valor, sum(e53_vlrpag) as total_vlrpag,"
      . " sum(e53_vlranu) as total_vlranu " );
    $result = $clpagordemele->sql_record( $sSql );

    if ($clpagordemele->numrows > 0) {

      db_fieldsmemory($result, 0);

      $clempprestaitem = new cl_empprestaitem();
      $sSql            = $clempprestaitem->sql_query( null,
        "sum(e46_valor) as total_prestado, e45_conferido",
        null,
        "e45_numemp = {$e50_numemp} and e45_codmov = {$e81_codmov} group by e45_conferido" );
      $result          = $clempprestaitem->sql_record( $sSql );

      $sql             = $clempagemov->sql_query_file( null,
        "e81_valor as valor_total_movimento",
        null,
        "e81_codmov = {$e81_codmov} and e81_numemp = {$e50_numemp}");
      $result02        = $clempagemov->sql_record( $sql );

      if ($clempprestaitem->numrows <= 0 || $clempagemov->numrows <= 0) {
        die('Prestação de contas não encontrada');
      }

      db_fieldsmemory($result, 0);
      db_fieldsmemory($result02, 0);

      $vlrdis = $valor_total_movimento;

      /**
       * Prestação não estornada
       */
      if ( !empty($e45_conferido) ) {
        $vlrdis = (float) $valor_total_movimento - (float) $total_prestado;
      }

      if ( $vlrdis <= 0 || $vlrdis == '' ) {

        $vlrdis   = 0;
        $db_opcao = 33;
        $erro_m   = "Não existe valor pago para ser estornado com ordem!";
      }

    } else {
      die('Elemento não encontrado na tabela pagordemele !');
    }

  } else if (isset ($pag_ord)) {

    $result02 = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord, null, "sum(e53_valor) as total_valor, sum(e53_vlrpag) as total_vlrpag, sum(e53_vlranu) as total_vlranu  "));
    $numrows = $clpagordemele->numrows;

    if ($numrows > 0) {
      db_fieldsmemory($result02, 0);
    } else {
      die('Elemento não encontrado na tabela pagordemele !');
    }

    $vlrdis = $total_vlrpag;

    if ($vlrdis == 0 || $vlrdis == '') {
      $db_opcao = 33;
      $erro_m = "Não existe valor pago para ser estornado com ordem!";
    }

  } else {
    $db_opcao = 33;
  }
}

$oDaoPagOrdemNota = db_utils::getDao("pagordemnota");
$rsNota           = $oDaoPagOrdemNota->sql_record($oDaoPagOrdemNota->sql_query_file($e50_codord));

if ($oDaoPagOrdemNota->numrows > 0) {
  $e71_codnota = db_utils::fieldsMemory($rsNota, 0)->e71_codnota;
}

$oDaoPagOrdemConta = db_utils::getDao("pagordemconta");
$rsCgmOrdem        = $oDaoPagOrdemConta->sql_record($oDaoPagOrdemConta->sql_query($e50_codord));

if ($oDaoPagOrdemConta->numrows > 0) {

  $oCgmOrdem   = db_utils::fieldsMemory($rsCgmOrdem, 0);
  $e60_numcgm  = $oCgmOrdem->z01_numcgm;
  $z01_nome    = $oCgmOrdem->z01_nome;
}

?>
<style>
  <?$cor="#999999"?>
  .bordas02{
    border: 1px solid #cccccc;
    border-top-color: <?=$cor?>;
    border-right-color: <?=$cor?>;
    border-left-color: <?=$cor?>;
    border-bottom-color: <?=$cor?>;
    background-color: #999999;
  }
  .bordas{
    border: 1px solid #cccccc;
    border-top-color: <?=$cor?>;
    border-right-color: <?=$cor?>;
    border-left-color: <?=$cor?>;
    border-bottom-color: <?=$cor?>;
    background-color: #cccccc;
  }
</style>
<form name="form1" method="post" action="">
  <center>
    <table border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td colspan='2' align='center' valign='top'>
          <?


          if (empty ($modo)) {
            if (isset ($pag_ord)) {
              $modo = "pag_ord";
            } else {
              $modo = "pag_emp";
            }
          }
          db_input($modo, 6, 0, true, 'hidden', 3);
          db_input('dados', 6, 0, true, 'hidden', 3);
          ?>
          <fieldset><legend><b>Dados da Ordem</legend>
            <table border="0" cellspacing='0' cellpadding='0'>
              <tr>
                <td nowrap title="<?=@$Te60_numemp?>" colspan='3' align='right'>
                  <?if(isset($pag_ord)){?>
                    <?=@$Le50_codord?>
                    <?


                    db_input('e50_codord', 8, $Ie50_codord, true, 'text', 3)
                    ?>
                  <?}?>
                  <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e60_numemp."')",1)?>
                  <?

                  db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tz01_nome?>">
                  <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','".@$e60_numcgm."')",1)?>
                </td>
                <td>
                  <?  db_input('e60_numcgm', 10, $Ie60_numcgm, true, 'text', 3) ?>
                  <? db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '') ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Te60_coddot?>">
                  <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."','".db_getsession("DB_anousu")."')",1)?>
                </td>
                <td>
                  <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
                </td>
              </tr>
              <?
              /* busca dados da dotação  */
              if ((isset ($e60_coddot))) {
                $instit = db_getsession("DB_instit");
                $clorcdotacao->sql_record($clorcdotacao->sql_query_file("", "", "*", "", "o58_coddot=$e60_coddot and o58_instit=$instit"));
                if ($clorcdotacao->numrows > 0) {
                  $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e60_coddot", $e60_anousu);
                  db_fieldsmemory($result, 0);
                  $atual = number_format($atual, 2, ",", ".");
                  $reservado = number_format($reservado, 2, ",", ".");
                  $atudo = number_format($atual_menos_reservado, 2, ",", ".");
                } else {
                  $nops = " Dotação $e60_coddot  não encontrada ";
                }

              }
              ?>
              <tr>
                <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
                <td nowrap >
                  <? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?>
                  <? db_input('o40_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
                <td nowrap >
                  <? db_input('o58_unidade',8,"",true,'text',3,"");  ?>
                  <? db_input('o41_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
                <td nowrap >
                  <? db_input('o58_funcao',8,"",true,'text',3,"");  ?>
                  <? db_input('o52_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
                <td nowrap >
                  <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>
                  <? db_input('o53_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
                <td nowrap >
                  <? db_input('o58_programa',8,"",true,'text',3,"");  ?>
                  <? db_input('o54_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
                <td nowrap >
                  <? db_input('o58_projativ',8,"",true,'text',3,"");  ?>
                  <? db_input('o55_descr',40,"",true,'text',3,"");  ?>
                </td>
              </tr>
              <tr>
                <td colspan=2> &nbsp </td>
              </tr>



              <tr>
                <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
                <td nowrap >
                  <? db_input('o58_codigo',8,"",true,'text',3,"");  ?>
                  <? db_input('o15_descr',40,"",true,'text',3,"");  ?>

                </td>
              </tr>

              <? if (isset($e50_codord) && ($e50_codord!="")){   ?>
                <tr>
                  <td nowrap >
                    <? db_ancora("Consulta Pagamentos","js_pesquisa_pagamentos($e60_numemp);",1);  ?>
                  </td>
                  <td nowrap >
                  </td>
                </tr>
              <?  } ?>


              <tr>
                <td nowrap title="<?=@$Tk13_conta?>">
                  <? db_ancora(@ $Lk13_conta, "js_pesquisak13_conta(true);", 1); ?>
                </td>
                <td nowrap >
                  <? db_input('k13_conta', 8, $Ik13_conta, true, 'text', $db_opcao, " onchange='js_pesquisak13_conta(false);'") ?>
                  <? db_input('k13_descr', 40, $Ik13_descr, true, 'text', 3); ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="Motivo de estorno">
                  <b>Motivo de estorno</b>
                </td>
                <td>
                  <? db_textarea('c72_complem', 2, 50, 0, true, 'text', $db_opcao, "") ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
        <td valign='top'>
          <fieldset><legend><b>Saldos</b></legend>
            <table cellspacing='0' cellpadding='0' width="100%">
              <?  if (isset ($e60_anousu) && $e60_anousu < db_getsession("DB_anousu")) {    ?>
                <tr class='bordas'>
                  <td  colspan='2' align='center'>
                    <b style='color:red'>RESTO À PAGAR</b>
                  </td>
                </tr>
              <?
              }
              ?>
              <tr>
                <td class='table_header' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
                  <b><small>EMPENHO</small></b>
                </td>
              </tr>
              <tr class=''>
                <td class='' nowrap title="<?=@$Te60_vlremp?>">
                  <?=@$Le60_vlremp?>
                </td>
                <td class=''>
                  <? db_input('e60_vlremp', 15, $Ie60_vlremp, true, 'text', 3, '') ?>
                </td>
              </tr>
              <tr class=''>
                <td class='' nowrap title="<?=@$Te60_vlranu?>"><?=@$Le60_vlranu?></td>
                <td class=''>
                  <? db_input('e60_vlranu', 15, $Ie60_vlranu, true, 'text', 3, '') ?>
                </td>
              </tr>
              <tr>
                <td class='' nowrap title="<?=@$Te60_vlrliq?>">
                  <?=@$Le60_vlrliq?>
                </td>
                <td class=''>
                  <? db_input('e60_vlrliq', 15, $Ie60_vlrliq, true, 'text', 3, '') ?>
                </td>
              </tr>
              <tr>
                <td class='' nowrap title="<?=@$Te60_vlrpag?>"><?=@$Le60_vlrpag?></td>
                <td class=''>
                  <? db_input('e60_vlrpag', 15, $Ie60_vlrpag, true, 'text', 3, '') ?>
                </td>
              </tr>
              <?
              if (isset ($e60_numemp)) {
                if (isset ($e50_codord) && $e50_codord != '') {
                  $result = $clpagordemele->sql_record($clpagordemele->sql_query(null, null, "sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu", "", "e60_numemp=$e60_numemp and e50_codord=$e50_codord "));
                } else {
                  $result = $clpagordemele->sql_record($clpagordemele->sql_query(null, null, "sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu", "", "e60_numemp=$e60_numemp"));
                }
                db_fieldsmemory($result, 0, true);
                if ($tot_valor != '0') {
                  ?>
                  <tr class=''>
                    <td class='table_header' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
                      <b><small>ORDEM</small></b>
                    </td>
                  </tr>
                  <tr>
                    <td class='' nowrap title="<?=@$Te60_vlranu?>">
                      <?=@$Le53_valor?>
                    </td>
                    <td class=''>
                      <? db_input('tot_valor', 15, $Ie60_vlranu, true, 'text', 3, '') ?>
                    </td>
                  </tr>
                  <tr>
                    <td class='' nowrap title="<?=@$Te53_vlrpag?>">
                      <?=@$Le53_vlrpag?>
                    </td>
                    <td class=''>
                      <? db_input('tot_vlrpag', 15, $Ie53_vlrpag, true, 'text', 3, '') ?>
                    </td>
                  </tr>
                  <tr>
                    <td class='' nowrap title="<?=@$Te53_vlranu?>"><?=@$Le53_vlranu?></td>
                    <td class=''><? db_input('tot_vlranu', 15, $Ie53_vlranu, true, 'text', 3, '') ?></td>
                  </tr>
                <?
                }
              }
              ?>
              <tr class=''>
                <td class='table_header' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
                  <b><small>SALDO</small></b>
                </td>
              </tr>
              <tr>

              <tr>
                <td class='' nowrap title="Valor que deseja anular">
                  <b>Valor disponível:</b>
                </td>
                <td class=''>
                  <?
                  $vlrdis = number_format($vlrdis, "2", ".", "");
                  db_input('vlrdis', 15, 0, true, 'text', 3);
                  if ($vlrdis == 0) {
                    $db_botao = false;
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="Valor que deseja pagar">
                  <b>Valor a estornar:</b>
                </td>
                <td>
                  <?php

                  $vlrpag_estornar = $tot_vlrpag;
                  if (isset($pag_ord) && isset($origem_devolucao)) {
                    $vlrpag_estornar = $vlrdis;
                    db_input('vlrpag_estornar', 15, $Ie53_vlrpag, true, 'text', 3, "onchange='js_verificar(\"campo\");'");
                  } else {
                    db_input('vlrpag_estornar', 15, $Ie53_vlrpag, true, 'text', 1, "onchange='js_verificar(\"campo\");'");
                  }

                  ?>
                </td>
              </tr>
              <tr>
                <td class='' nowrap>
                  <?

                  $dbwhere = "e80_instit = " . db_getsession("DB_instit") . " and e60_numemp = $e60_numemp";

                  if (isset ($e50_codord) && $e50_codord != '') {
                    $dbwhere .= " and  e82_codord = $e50_codord ";
                  }

                  $oDaoMovimentoCheque = new cl_empagemov();
                  $sql = $oDaoMovimentoCheque->sql_query_conf(null, "k13_descr,e81_codmov,z01_nome,e83_conta,e83_descr,e83_sequencia,e81_valor", "", "$dbwhere ");
                  $result = $oDaoMovimentoCheque->sql_record($sql);


                  if ($oDaoMovimentoCheque->numrows > 0 && !isset($origem_devolucao)) {
                    db_ancora(@ $Lk12_cheque, "js_cheque(true);", $db_opcao);
                  } else {
                    echo $Lk12_cheque;
                  }
                  ?>
                </td>
                <td >
                  <?


                  db_input('k12_cheque', 15, 4, true, 'text', 3);
                  ?>
                  <?=db_input('e91_codcheque',7,'',true,'hidden',1);?>
                  <?=db_input('e81_codmov',7,'',true,'hidden',1);?>
                  <?=db_input('e71_codnota',7,'',true,'hidden',1);?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Retencão:</b>
                </td>
                <td>
                  <?=db_input('valorretencao',15,'',true,'text',3);?>
                </td>
              </tr>
              <tr>
                <td colspan='2'>
                  <input type="checkbox" id='estornarpagamento'><label for="estornarpagamento"><b>Estornar Pagamento</b></label>
                </td>
              </tr>
              <tr id='mostracheque'>
                <td colspan='2'>
                  <input type="checkbox" id='estornarcheque'><label for="estornarcheque"><b>Estornar Cheque</b></label>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan='3' align='left'>
          <fieldset><legend><b>Retenções</b></legend>
            <div id='gridRetencoes1'></div>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align='center' colspan='3'>
          <br>
          <? if( (isset($retorno) && $k11_tipautent == 1) ||  (isset($retorno_imp))  && !isset($confirmar_primeira_vez) ){  ?>

            <input name="pesquisar" type="button" id="imprimir_novamente" value="Autenticar Novamente" onclick="aut();" >

          <? }  ?>
          <input name="confirmar" type="button" id="db_opcao" value="Confirmar" onclick="return js_estornarPagamento()" <?=($db_botao==false?"disabled":"")?> >
          <input name="pesquisar" type="button" id="pesquisar" value="Voltar" onclick="js_volta();" >
        </td>
      </tr>
    </table>
  </center>
</form>
<script>

  <?php
  if (isset ($e60_numemp)) {
    if ($vlrdis == 0 || $vlrdis == '') {
      echo " document.form1.confirmar.disabled=true;\n
            ";
      if (empty ($confirmar)) {
        echo "alert(\"$erro_m\");\n";
      }
    }
  }
  ?>

  <?php if (isset($pag_ord) && isset($origem_devolucao)): ?>

  function js_volta(){
    location.href="cai4_devolucaoadiantamento001.php";
  }

  var lOrigemDevolucao = true;

  <?php else: ?>

  function js_volta(){
    location.href="emp1_emppagamentoestorna001.php";
  }

  var lOrigemDevolucao = false;

  <?php endif; ?>

  function js_pesquisak13_conta(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
    }
  }
  function js_mostrasaltes(chave,erro){
    document.form1.k13_descr.value = chave;
    if(erro==true){
      document.form1.k13_conta.focus();
      document.form1.k13_conta.value = '';
    }
  }
  function js_mostrasaltes1(chave1,chave2){
    document.form1.k13_conta.value = chave1;
    document.form1.k13_descr.value = chave2;
    db_iframe_saltes.hide();
  }
  function js_pesquisa_pagamentos(empenho){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem002.php?e60_numemp='+empenho,'Pesquisa',true);
  }

  <?


  if (isset ($e60_numemp)) {
  ?>
  function js_verificar(tipo){
    erro=false;

    if(tipo=="botao" && document.form1.k13_conta.value==''){
      alert('Preencha o campo com a conta da tesouraria!');
      return false;
    }

    vlrpag_estornar= new Number(document.form1.vlrpag_estornar.value);
    if(tipo=='botao'){
      if(vlrpag_estornar == '' || vlrpag_estornar == 0 ){
        alert('Informe o valor à ser estornado!');
        return false;
      }
    }

    if(isNaN(vlrpag_estornar)){
      erro=true;
    }
    if(vlrpag_estornar > $F('vlrdis')){
      erro= true;
    }

    if(erro==false){
      val = vlrpag_estornar.toFixed(2);
      document.form1.vlrpag_estornar.value=val
      return true;
    }else{
      document.form1.vlrpag_estornar.focus();
      document.form1.vlrpag_estornar.value= $F('vlrdis');
      return false;
    }

  }

  <?

  }
  ?>

  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_empempenho.hide();
    <?


  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?e60_numemp='+chave";
  ?>
  }

  //----------------------cheque

  function js_tranca(campos){
    arr = campos.split("#");
    for(i=0; i<arr.length; i++ ){
      campo = arr[i];
      eval("document.form1."+campo+".readOnly=true;");
      eval("document.form1."+campo+".style.backgroundColor = '#DEB887'");
    }
  }
  function js_libera(campos){
    arr = campos.split("#");
    for(i=0; i<arr.length; i++ ){
      campo = arr[i];
      eval("document.form1."+campo+".readOnly=false;");
      eval("document.form1."+campo+".style.backgroundColor = 'white'");
    }
  }


  function js_cheque(abrejanela, fFuncRetorno){
    js_OpenJanelaIframe('top.corpo','db_iframe_cheque',
      'emp4_movimentosgenda.php?js_funcao=' + 'parent.' + fFuncRetorno
      + '|e91_codcheque|e83_conta|e91_cheque|k13_descr|k12_valor|e81_codmov|k105_corgrupotipo'
      +'&e50_codord=<?=@$e50_codord?>&e60_numemp=<?=@$e60_numemp?>'
      + '<?php echo (isset($e81_codmov) ? "&e81_codmov={$e81_codmov}" : ""); ?>',
      'Pesquisar Movimentos',abrejanela);
  }

  function js_vai(codcheque,conta,sequencia,descr,valor,iCodMov,iTipo){
    obj = document.form1;
    valor = new Number(valor);
    obj.vlrdis.value     = valor.toFixed(2);
    disponivel = new Number(obj.vlrdis.value);
    if(valor>disponivel){
      alert("O valor do cheque é maior do que o disponivel!");
      return false;
    }

    if (iTipo == 2) {

      $('estornarpagamento').disabled = true;
      $('estornarpagamento').checked  = false;

    } else {

      $('estornarpagamento').disabled = false;
      $('estornarpagamento').checked  = false;

    }
    $('mostracheque').style.display = "none";
    $('estornarcheque').checked     = false;
    obj.e91_codcheque.value = codcheque;
    obj.k13_conta.value  = conta;
    obj.k13_descr.value  = descr;
    obj.k12_cheque.value = sequencia;
    obj.e81_codmov.value = iCodMov;
    valor = new Number(valor);
    obj.vlrpag_estornar.value     = valor.toFixed(2);
    js_tranca('k12_cheque#k13_conta');
    db_iframe_cheque.hide();
    js_pesquisak13_conta(false);
    if ( codcheque > 0) {
      $('mostracheque').style.display="";
    }
    js_getRetencoesMovimento(iCodMov);
  }

  function js_origem_devolucao(codcheque,conta,sequencia,descr,valor,iCodMov,iTipo) {
    obj = document.form1;
    valor = new Number(valor);
    disponivel = new Number(obj.vlrdis.value);

    $('estornarpagamento').disabled = true;
    $('estornarpagamento').checked  = true;

    $('mostracheque').style.display = "none";
    $('estornarcheque').checked     = false;

    obj.k13_conta.value     = conta;
    obj.k13_descr.value     = descr;
    obj.e81_codmov.value    = iCodMov;

    js_tranca('k12_cheque#k13_conta');

    db_iframe_cheque.hide();

    js_pesquisak13_conta(false);

    js_getRetencoesMovimento(iCodMov);
  }

  document.form1.e91_codcheque.value ='';
  document.form1.k12_cheque.value ='';

  function js_init() {

    gridRetencoes              = new DBGrid("RetencoesGrid");
    gridRetencoes.nameInstance = "gridRetencoes";
    gridRetencoes.setCheckbox(0);
    gridRetencoes.setCellAlign(new Array("Right", "left"    , "right"  , "right"         ,"right"    , "right"       ,"right"));
    gridRetencoes.setHeader   (new Array("Mov"  , "Retenção", 'Dedução',"Base de Calculo","Aliquota" , "Valor Retido","Tipo"));
    gridRetencoes.show(document.getElementById('gridRetencoes1'));

  }
  js_init();

  /**
   * Retorna as retencoes do movimento
   */
  function js_getRetencoesMovimento(iCodMov) {
    js_divCarregando("Aguarde, Consultando Retenções.","msgBox");
    var oRequisicao      = new Object();
    oRequisicao.exec     = "getRetencoesMovimento";
    oRequisicao.iCodMov  = iCodMov;
    oRequisicao.iCodNota = $F('e71_codnota');
    var sJson            = js_objectToJson(oRequisicao);
    var url              = 'emp4_retencaonotaRPC.php';
    var oAjax            = new Ajax.Request(
      url,
      {
        method    : 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoGetRetencoesMovimento
      }
    );
  }

  function js_objectToJson(oObject) { return JSON.stringify(oObject); 

    var sJson = oObject.toSource();
    sJson     = sJson.replace("(","");
    sJson     = sJson.replace(")","");
    return sJson;

  }

  sUrl  = "emp4_pagarpagamentoRPC.php";
  function js_retornoGetRetencoesMovimento(oAjax) {

    js_removeObj("msgBox");
    var oRetencoes = JSON.parse(oAjax.responseText);

    $('vlrpag_estornar').readOnly              = true;
    $('vlrpag_estornar').style.backgroundColor = "rgb(222, 184, 135);";

    //preenchemos a grid com as retencoes;
    if (oRetencoes.aRetencoes.length > 0) {

      gridRetencoes.clearAll(true);

      var lDisabled  = false;
      var iSlip      = '';
      var iSlipSaldo = '';

      for (var iRet = 0; iRet < oRetencoes.aRetencoes.length; iRet++) {
//      with (oRetencoes.aRetencoes[iRet]) {

        var oDadosRetencao = oRetencoes.aRetencoes[iRet];

        if (oDadosRetencao.k108_slip != "" || oDadosRetencao.k17_slip != "") {

          lDisabled  = true;
          iSlip      = oDadosRetencao.k108_slip == "" ? oDadosRetencao.k17_slip:oDadosRetencao.k108_slip;

        }
        var aLinha = new Array();
        aLinha[0]  = oDadosRetencao.e23_sequencial;
        aLinha[1]  = oDadosRetencao.e21_descricao.urlDecode();
        aLinha[2]  = js_formatar(oDadosRetencao.e23_deducao,'f');
        aLinha[3]  = js_formatar(oDadosRetencao.e23_valorbase,'f');
        aLinha[4]  = js_formatar(oDadosRetencao.e23_aliquota,'f');
        aLinha[5]  = js_formatar(oDadosRetencao.e23_valorretencao,'f');
        aLinha[6]  = oDadosRetencao.e21_retencaotipocalc;

        gridRetencoes.addRow(aLinha,false, lDisabled);
        gridRetencoes.aRows[iRet].sValue  = oDadosRetencao.e23_sequencial;
      }
      //  }
      gridRetencoes.renderRows();
    } else {

      $('vlrpag_estornar').disabled              = false;

      if (!lOrigemDevolucao) {

        $('vlrpag_estornar').readOnly              = false;
        $('vlrpag_estornar').style.backgroundColor = "white";
      }

    }
    $('valorretencao').value = js_round(gridRetencoes.sum(6,false),2);
    if (lDisabled) {

      // $('estornarpagamento').checked  = false;
      // $('estornarpagamento').disabled = true;
      alert('As retenções da OP já foram transferidas via slip('+iSlip+').\nNão podera ser estornada as retenções.\n');

    }
  }

  function js_estornarPagamento() {

    var lEstornarPagamento  = false;
    if ($('estornarpagamento').checked) {
      lEstornarPagamento = true;
    }
    var aRetencoes             = gridRetencoes.getSelection();

    if (!lEstornarPagamento && aRetencoes.length == 0) {

      alert('Não há definição sobre o que deve ser estornado.');
      return false;

    } else if (lEstornarPagamento && aRetencoes.length == 0) {
      var sMsg = "Confirma o estorno do pagamento";
      if ($('estornarcheque').checked) {
        sMsg += " e seu cheque ";
      }
      if (!confirm(sMsg+'?')) {
        return false;
      }
    } else if (!lEstornarPagamento && aRetencoes.length > 0) {
      if (!confirm('Confirma o estorno das retencoes da nota '+$F('e50_codord')+'?')) {
        return false;
      }
    } else if (lEstornarPagamento && aRetencoes.length > 0) {

      var sMsg = 'Confirma o estorno das retencoes da nota '+$F('e50_codord')+' e o seu pagamento';
      if ($('estornarcheque').checked) {
        sMsg += " e seu cheque ";
      }
      if (!confirm(sMsg+'?')) {
        return false;
      }
    }
    var oRequisicao            = new Object();
    oRequisicao.exec           = "estornarPagamento";
    oRequisicao.iCodMov        = $F('e81_codmov');
    oRequisicao.nValorEstornar = $F('vlrpag_estornar');
    oRequisicao.iCodCheque     = $F('e91_codcheque');
    oRequisicao.iCheque        = $F('k12_cheque');
    oRequisicao.iNota          = $F('e50_codord');
    oRequisicao.iConta         = $F('k13_conta');
    oRequisicao.lEstornaCheque = $('estornarcheque').checked;
    oRequisicao.lEstornarPgto  = lEstornarPagamento;
    oRequisicao.sHistorico     = encodeURIComponent($F('c72_complem').replace(/\"/g, "<aspa>"));
    oRequisicao.aRetencoes     = new Array();
    oRequisicao.lDevolucao     = lOrigemDevolucao;

    if (!oRequisicao.lEstornaCheque) {

      oRequisicao.iCodCheque     = "";
      oRequisicao.iCheque        = "";

    }
    var aRetencoes             = gridRetencoes.getSelection();
    for (var i = 0; i < aRetencoes.length; i++) {

      var oRetencao  = new Object();
      oRetencao.iRetencao = aRetencoes[i][1];
      oRetencao.nValor    = js_strToFloat(aRetencoes[i][6]).valueOf();
      oRetencao.iTipoCalc = aRetencoes[i][7];
      oRequisicao.aRetencoes.push(oRetencao);

    }
    js_divCarregando("Aguarde, Estornando pagamento.","msgBox");
    js_liberaBotoes(false);
    var sJson            = Object.toJSON(oRequisicao);
    var oAjax = new Ajax.Request(
      sUrl,
      {
        method    : 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoEstornarPagamento
      }
    );

  }

  function js_retornoEstornarPagamento(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");

    js_removeObj("msgBox");

    if (oRetorno.status == 1) {

      iCodLanc = oRetorno.sCodLanc;
      if (oRetorno.iTipoAutentica != 3) {
        if (confirm('Estorno efetuado com sucesso.\nDeseja autenticar?')) {
          js_autentica(oRetorno.sAutenticacao.urlDecode());
        } else {
          js_liberaBotoes(true);
          js_showRelatorio(iCodLanc);
        }
      } else {

        alert("Estorno de Pagamento Efetuado com sucesso");
        js_showRelatorio(iCodLanc);

      }

    } else {
      alert(oRetorno.message.urlDecode());
      js_liberaBotoes(true);
    }
  }
  function js_showRelatorio(sCodlans) {
    window.open('emp2_emiteestornoemp002.php?codord='+$F(e50_codord)+'&codlan='+sCodlans,'',
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    js_volta();
  }

  function js_autentica(sString) {

    sStringAutentica = sString
    var oRequisicao      = new Object();
    oRequisicao.exec     = "Autenticar";
    oRequisicao.sString  = sString;
    var sJson            = js_objectToJson(oRequisicao);
    var oAjax = new Ajax.Request(
      sUrl,
      {
        method    : 'post',
        parameters: 'json='+sJson,
        onComplete: js_retornoAutenticacao
      }
    );
  }

  function js_retornoAutenticacao(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      if (confirm('Autenticar Novamente?')) {
        js_autentica(oRetorno.sAutenticacao.urlDecode());
      } else {

        js_liberaBotoes(true);
        js_showRelatorio(iCodLanc);

      }
    } else {

      alert('Não foi possivel Conectar a Impressora');
      js_volta();
      $('confirmar').disabled = true;


    }

  }

  function js_liberaBotoes(lLiberar) {

    if (lLiberar) {

      $('pesquisar').disabled = false;
      $('db_opcao').disabled   = false;

    } else {

      $('pesquisar').disabled = true;
      $('db_opcao').disabled   = true;

    }
  }

  $('vlrpag_estornar').readOnly              = true;
  $('vlrpag_estornar').style.backgroundColor = "rgb(222, 184, 135);";
</script>
<?
// Para abrir a func dos cheques na abertura da tela de estorno de pagamento de empenho
if ($oDaoMovimentoCheque->numrows > 0) {

  if (isset($pag_ord) && isset($origem_devolucao)) {
    echo "<script>js_cheque(true, 'js_origem_devolucao');</script>";
  } else {
    echo "<script>js_cheque(true, 'js_vai');</script>";
  }
  echo "<script>js_libera('vlrpag_estornar')</script>";
} else {
  echo "<script>js_libera('vlrpag_estornar')</script>";
}
?>
