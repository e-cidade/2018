<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */
set_time_limit(0);
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");


$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->erro      = false;
$oRetorno->mensagem  = '';

try {


  switch ($oParametros->exec) {

    case 'getItens':

      $oRetorno->itens = getMateriaisParaAjuste();
      break;

    case 'processar':

      if ($_SESSION["DB_id_usuario"] != 1) {
        throw new Exception("Sem permissão de acesso");
      }
      db_inicio_transacao();
      db_query("insert into matestoquetipo select 999, 'AJUSTE ESTOQUE - ENTRADA', TRUE, 1 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 999)");
      db_query("insert into matestoquetipo select 998, 'AJUSTE ESTOQUE - SAIDA', false, 2 as x where not exists (select 1 from matestoquetipo where m81_codtipo = 998)");
      db_query("alter table matestoqueinimei disable trigger all");
      $aItens = getMateriaisParaAjuste($oParametros->itens);

      /**
       * Correção das transferencias inconsistentes
       */
      foreach ($aItens as $oDadosItem) {

        $sSqlTransferenciasInconsistentes = " select * from (select distinct m80_codigo,
               m80_codtipo,
               m80_data,
               m80_login,
               m80_data,
               m80_Hora,
               (select array_to_string(array_accum(distinct m70_codmatmater), ',')
                  from matestoqueinimei
                       inner join matestoqueitem on m82_matestoqueitem = m71_codlanc
                       inner join matestoque     on m71_codmatestoque  = m70_codigo
                  where m82_matestoqueini =  m80_codigo
               )as itens_envolvidos,
               m80_coddepto,
               ts.m87_matestoqueini as codigo_saida,
               tell.m87_matestoqueini as codigo_entrada
          from  matestoqueini
               inner join matestoqueinil temt   on m80_codigo           = temt.m86_matestoqueini
               inner join matestoqueinill ts    on temt.m86_codigo      = ts.m87_matestoqueinil
               inner join matestoqueinil tel    on ts.m87_matestoqueini = tel.m86_matestoqueini
               inner join matestoqueinill tell  on tel.m86_codigo       = tell.m87_matestoqueinil
               inner join matestoqueinimei      on m80_codigo           = m82_matestoqueini
               inner join matestoqueitem        on m71_codlanc          = m82_matestoqueitem
               inner join matestoque            on m71_codmatestoque    = m70_codigo
         where m80_codtipo = 7
           and ts.m87_matestoqueini = m80_codigo
           and m70_codmatmater = {$oDadosItem->m70_codmatmater}
         order by 3) as x
         where   itens_envolvidos <> ''";

        $rsItens = db_query($sSqlTransferenciasInconsistentes);
        if (!$rsItens) {
          throw new Exception("Erro na consulta das Incosistencias");
        }
        $aTransferencias = db_utils::getCollectionByRecord($rsItens);
        foreach ($aTransferencias as $oTransferencia) {
          corrigirTransferencia($oTransferencia);
        }
      }

      /**
       * Ajuste da Movimentacao
       */
      $aItens           = getMateriaisParaAjuste($oParametros->itens);
      $aItensCorrigidos = array();
      foreach ($aItens as $oDadosItem) {

        $sSqlEntradas   = " select distinct m71_codlanc, m71_quant, m71_quantatend, m71_servico";
        $sSqlEntradas  .= "   from matestoqueini ";
        $sSqlEntradas  .= "        inner join matestoqueinimei on m80_codigo        = m82_matestoqueini ";
        $sSqlEntradas  .= "        inner join matestoqueitem   on m71_codlanc       = m82_matestoqueitem";
        $sSqlEntradas  .= "        inner join matestoque       on m71_codmatestoque = m70_codigo ";
        $sSqlEntradas  .= "  where m70_codigo = {$oDadosItem->m70_codigo}";
        $rsEntradas     = db_query($sSqlEntradas);
        $iTotalEntradas = pg_num_rows($rsEntradas);
        $lPoissuiEntradaInconsistente = false;
        for ($iEntrada = 0; $iEntrada < $iTotalEntradas; $iEntrada++) {

          $oDadosEntrada = db_utils::fieldsMemory($rsEntradas, $iEntrada);
          if ($oDadosEntrada->m71_servico == 't') {
            continue;
          }
          db_query("select fc_putsession('db_instit', '{$oDadosItem->instit}')");
          $nSaldoNaEntrada = getSaldoNaEntrada($oDadosEntrada);
          if (($oDadosEntrada->m71_quant - $oDadosEntrada->m71_quantatend) != $nSaldoNaEntrada) {

            corrigirMovimentacoes($oDadosEntrada->m71_codlanc, $oDadosEntrada->m71_quant, $oDadosEntrada->m71_quantatend);
            $lPoissuiEntradaInconsistente = true;
          }
        }

        if ($lPoissuiEntradaInconsistente) {

          $aItensCorrigidos[$oDadosItem->m70_codmatmater][] = (object)array("depto" => $oDadosItem->m70_coddepto, "instit" => $oDadosItem->instit);
          $aMensagemUsuario[] = "Item {$oDadosItem->m70_codmatmater} - {$oDadosItem->m60_descr} do estoque {$oDadosItem->m70_coddepto} Corrigido\n";
        }
      }
      /**
       * Realizar ajuste no preço médio:
       */
      db_query("alter table matestoqueinimei enable trigger all");
      foreach ($aItensCorrigidos as $iCodigoItem => $aDeptos) {

        foreach ($aDeptos as $oDepto) {

          db_query("select fc_putsession('db_instit', '{$oDepto->instit}')");
          corrigirPrecoMedio($iCodigoItem, $oDepto->depto);
        }
      }

      db_query("alter table matestoqueinimei enable trigger all");
      db_fim_transacao(false);
      $oRetorno->mensagem = "Itens Processados com sucesso!";
      break;
  }
} catch (Exception $oErro) {

  db_query("alter table matestoqueinimei enable trigger all");
  $oRetorno           = new stdClass();
  $oRetorno->erro     = true;
  $oRetorno->mensagem = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);



function getMateriaisParaAjuste(array $aItens = null) {

  $sWhereMater    = "where m60_ativo is true and instit =".db_getsession("DB_instit");
  if (count($aItens) > 0) {
    $sWhereMater .= " and m60_codmater in(".implode(",", $aItens).")";
  }
  $sSqlTransferencias  = "select * ";
  $sSqlTransferencias .= "  from ( select m70_codmatmater, ";
  $sSqlTransferencias .= "                m60_descr, ";
  $sSqlTransferencias .= "                m70_coddepto, ";
  $sSqlTransferencias .= "                m70_codigo, ";
  $sSqlTransferencias .= "               round ( fc_saldo_item_estoque ( m70_coddepto, m70_codmatmater::integer), 3) as saldo_movimentacao, ";
  $sSqlTransferencias .= "               coalesce((select sum(m82_quant) ";
  $sSqlTransferencias .= "                 from matestoqueini ";
  $sSqlTransferencias .= "                     inner join matestoqueinimei on m80_codigo        = m82_matestoqueini ";
  $sSqlTransferencias .= "                     inner join matestoqueitem   on m71_codlanc       = m82_matestoqueitem ";
  $sSqlTransferencias .= "                     inner join matestoque as t  on m71_codmatestoque = t.m70_codigo ";
  $sSqlTransferencias .= "                     left  join matestoqueinil    on m80_codigo       = m86_matestoqueini ";
  $sSqlTransferencias .= "               where t.m70_codigo = matestoque.m70_codigo ";
  $sSqlTransferencias .= "                 and m80_codtipo = 7 ";
  $sSqlTransferencias .= "                 and m86_codigo is null ";
  $sSqlTransferencias .= "             ), 0) as total_transferencias, ";
  $sSqlTransferencias .= "             round(m70_quant, 3) as m70_quant, ";
  $sSqlTransferencias .= "             round(m70_valor, 3) as m70_valor, ";
  $sSqlTransferencias .= "             instit, ";
  $sSqlTransferencias .= "             exists(select 1 from matestoqueitem where m71_codmatestoque = m70_codigo and m71_servico is true ) as tem_mov_servico";
  $sSqlTransferencias .= "        from matestoque ";
  $sSqlTransferencias .= "             inner join matmater on m60_codmater  = m70_codmatmater ";
  $sSqlTransferencias .= "             inner join db_depart on matestoque.m70_coddepto = db_depart.coddepto ";
  $sSqlTransferencias .= "             $sWhereMater ";
  $sSqlTransferencias .= "   ) as x ";
  $sSqlTransferencias .= " where (saldo_movimentacao - total_transferencias)  <> m70_quant ";

  $rsItensEnvolvidos = db_query($sSqlTransferencias);
  return db_utils::getCollectionByRecord($rsItensEnvolvidos, false, false, true);
}

function getSaldoNaEntrada($oEntrada) {

  $sSqlEntradas   = " select round(coalesce(sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant * -1 end), 0), 4) as valor";
  $sSqlEntradas  .= "   from matestoqueini ";
  $sSqlEntradas  .= "        inner join matestoqueinimei on m80_codigo        = m82_matestoqueini ";
  $sSqlEntradas  .= "        inner join matestoqueitem   on m71_codlanc       = m82_matestoqueitem";
  $sSqlEntradas  .= "        inner join matestoque       on m71_codmatestoque = m70_codigo ";
  $sSqlEntradas  .= "        inner join matestoquetipo   on m81_codtipo       = m80_codtipo";
  $sSqlEntradas  .= "  where m71_codlanc = {$oEntrada->m71_codlanc}";
  $rsItens = db_query($sSqlEntradas);
  if (!$rsItens) {
    throw new Exception(pg_last_error());
  }
  return db_utils::fieldsMemory($rsItens, 0)->valor;
}

function corrigirTransferencia($oDadosTransferencia) {

  $sSqlItensTransferencia  = "select m80_codigo, ";
  $sSqlItensTransferencia .= "       m70_Coddepto, ";
  $sSqlItensTransferencia .= "       m70_codmatmater, ";
  $sSqlItensTransferencia .= "       m71_codlanc, ";
  $sSqlItensTransferencia .= "       m80_login, ";
  $sSqlItensTransferencia .= "       m82_codigo, m82_quant, m80_coddepto, m80_codtipo, m80_data, m80_Hora  ";
  $sSqlItensTransferencia .= "  from matestoqueini ";
  $sSqlItensTransferencia .= "       inner join matestoqueinimei on m80_codigo  = m82_matestoqueini ";
  $sSqlItensTransferencia .= "       inner join matestoqueitem   on m71_codlanc = m82_matestoqueitem ";
  $sSqlItensTransferencia .= "       inner join matestoque       on m70_codigo  = m71_codmatestoque  ";
  $sSqlItensTransferencia .= " where m80_codigo  = {$oDadosTransferencia->m80_codigo} ";
  $sSqlItensTransferencia .= " order by m82_codigo, m70_codmatmater, m70_coddepto";

  $rsItensTransferencia     = db_query($sSqlItensTransferencia);
  $iTotalItens              = pg_num_rows($rsItensTransferencia);
  $aItensAgrupadosPorDepto  = array();

  for ($i = 0; $i < $iTotalItens; $i++) {

    $oItem = db_utils::fieldsMemory($rsItensTransferencia, $i);
    $aItensAgrupadosPorDepto[$oItem->m70_coddepto][] = $oItem;
  }

  /**
   * separar por tipo:
   *  - no departamento de saida, devemos ter: um 7 - Em transferencia, e 21 - Transferencia saida
   *  - No departamento destino, devemos ter apenas um 8 - Transferencia entrada
   * O registro das tabelas matestoqueinil deve ser: matestoqueinil = codigo da matestoqueini do tipo 7
   *                                                 matestoqueinill = codigo da matestoqueini do tipo 8, ligando com o inil da 7
   */

  $aItensAgrupadosPorTipo = array();
  foreach ($aItensAgrupadosPorDepto as $oItemAGrupado) {

    $aItensJaAgrupados = array();
    foreach ($oItemAGrupado as $iCodigoDepto => $oItem) {

      if ($oItem->m70_coddepto != $oDadosTransferencia->m80_coddepto) {

        $aItensAgrupadosPorTipo[8][] = $oItem;
        continue;
      }

      if (!in_array($oItem->m70_codmatmater, $aItensJaAgrupados)) {

        $aItensAgrupadosPorTipo[7][] = $oItem;
        $aItensJaAgrupados[]         = $oItem->m70_codmatmater;
      } else {
        $aItensAgrupadosPorTipo[21][] = $oItem;
      }
    }
  }

  /**
   * validamos a quantidade de itens em cada Grupo. Deve ser a mesma em todos eles.
   */
  $iTotalItens7  = 0;
  $iTotalItens8  = 0;
  $iTotalItens21 = 0;
  foreach ($aItensAgrupadosPorTipo as $iTipo => $aItens) {

    $iTotalTipo               = count($aItens);
    ${"iTotalItens{$iTipo}"} += $iTotalTipo;
  }

  if ($iTotalItens7 + $iTotalItens8 + $iTotalItens21 != $iTotalItens) {
    return false;
  }

  /**
   * criamos os dos movimentos;
   */
  $aComandos   = array();
  $aComandos[] = "delete from matestoqueinill where m87_matestoqueini = {$oDadosTransferencia->m80_codigo}";
  $aComandos[] = "delete from matestoqueinil where m86_matestoqueini = {$oDadosTransferencia->m80_codigo}";

  $iCodigoIniSaida   = db_utils::fieldsMemory(db_query("select nextval('matestoqueini_m80_codigo_seq') as m"), 0)->m;
  $iCodigoIniEntrada = db_utils::fieldsMemory(db_query("select nextval('matestoqueini_m80_codigo_seq') as m"), 0)->m;
  $lUpdate21 = false;
  $lUpdate8  = false;
  foreach ($aItensAgrupadosPorTipo as $iTipo => $aItens) {

    switch ($iTipo) {

      case 7:
        continue;
        break;

      case 8:

        $sInsert  = "insert ";
        $sInsert .= "  into matestoqueini ";
        $sInsert .= "   values ({$iCodigoIniEntrada},";
        $sInsert .= "           {$oDadosTransferencia->m80_login},";
        $sInsert .= "           '{$oDadosTransferencia->m80_data}',";
        $sInsert .= "           '',";
        $sInsert .= "          8,";
        $sInsert .= "          {$aItens[0]->m70_coddepto},";
        $sInsert .= "         '{$oDadosTransferencia->m80_hora}'::interval +' 2 second'";
        $sInsert .= ")";
        $aComandos[] = $sInsert;

        $aListaMatestoqueIniMei = array();
        foreach ($aItens as $oItem) {
          $aListaMatestoqueIniMei[] = $oItem->m82_codigo;
        }

        $sLista      = implode(",", $aListaMatestoqueIniMei);
        $aComandos[] = "update matestoqueinimei set m82_matestoqueini = {$iCodigoIniEntrada} where m82_codigo in({$sLista})";
        $lUpdate8    = true;
        break;

      case 21:

        $sInsert  = "insert ";
        $sInsert .= "  into matestoqueini ";
        $sInsert .= "   values ({$iCodigoIniSaida},";
        $sInsert .= "           {$oDadosTransferencia->m80_login},";
        $sInsert .= "           '{$oDadosTransferencia->m80_data}',";
        $sInsert .= "           '',";
        $sInsert .= "          21,";
        $sInsert .= "          {$aItens[0]->m70_coddepto},";
        $sInsert .= "         '{$oDadosTransferencia->m80_hora}'::interval +' 1 seconds'";
        $sInsert .= ")";
        $aComandos[] = $sInsert;
        $aListaMatestoqueIniMei = array();
        foreach ($aItens as $oItem) {
          $aListaMatestoqueIniMei[] = $oItem->m82_codigo;
        }

        $sLista      = implode(",", $aListaMatestoqueIniMei);
        $aComandos[] = "update matestoqueinimei set m82_matestoqueini = {$iCodigoIniSaida} where m82_codigo in({$sLista})";
        $lUpdate21   = true;
        break;
    }
  }

  $iIniL       = db_utils::fieldsMemory(db_query("select nextval('matestoqueinil_m86_codigo_seq') as m"), 0)->m;
  $aComandos[] = "insert into matestoqueinil values ({$iIniL}, $oDadosTransferencia->m80_codigo)";

  $aComandos[] = "insert into matestoqueinill values ({$iIniL}, $iCodigoIniSaida)";

  if ($lUpdate21) {

    $iIniLSaida  = db_utils::fieldsMemory(db_query("select nextval('matestoqueinil_m86_codigo_seq') as m"), 0)->m;
    $aComandos[] = "insert into matestoqueinil values ({$iIniLSaida}, $iCodigoIniSaida)";
  }

  if ($lUpdate8 && !empty($iIniLSaida)) {
    $aComandos[] = "insert into matestoqueinill values ({$iIniLSaida}, $iCodigoIniEntrada)";
  }

  foreach ($aComandos as $sInsert) {

    $rsInsert = db_query($sInsert);
    if (!$rsInsert) {
      throw new Exception("Erro no comando: {$sInsert}");
    }
  }
}
function corrigirMovimentacoes($iCodigoLancamento, $nQuantidade, $nQuantidadeAtendida) {

  $sSqlEntradas   = " select m70_codmatmater, m82_quant, m82_codigo, m89_sequencial, m71_codlanc, m81_tipo, m81_codtipo, m80_data, m80_hora, m80_login, m80_coddepto";
  $sSqlEntradas  .= "   from matestoqueini ";
  $sSqlEntradas  .= "        inner join matestoqueinimei   on m80_codigo        = m82_matestoqueini ";
  $sSqlEntradas  .= "        left  join matestoqueinimeipm on m82_codigo        = m89_matestoqueinimei ";
  $sSqlEntradas  .= "        inner join matestoqueitem     on m71_codlanc       = m82_matestoqueitem";
  $sSqlEntradas  .= "        inner join matestoque         on m71_codmatestoque = m70_codigo ";
  $sSqlEntradas  .= "        inner join matestoquetipo     on m80_Codtipo       = m81_codtipo ";
  $sSqlEntradas  .= "  where m71_codlanc = {$iCodigoLancamento}";
  $sSqlEntradas  .= "    and m71_servico is false";
  $sSqlEntradas  .= "   order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') ";

  $rsMovimentacoes     = db_query($sSqlEntradas);
  $iTotalMovimentacoes = pg_num_rows($rsMovimentacoes);
  $nValorMovimentado   = 0;
  $oPrimeiraMovimentacaoNegativa = null;
  for ($iMov = 0; $iMov < $iTotalMovimentacoes; $iMov++) {

    $oDadosMovimentacao = db_utils::fieldsMemory($rsMovimentacoes, $iMov);
    switch ($oDadosMovimentacao->m81_tipo) {

      case 1:

        $nValorMovimentado += $oDadosMovimentacao->m82_quant;
        break;

      case 2:

        if (empty($oPrimeiraMovimentacaoNegativa)) {
          $oPrimeiraMovimentacaoNegativa = $oDadosMovimentacao;
        }
        $nValorMovimentado -= $oDadosMovimentacao->m82_quant;
        break;
    }
  }

  if ($nValorMovimentado  > ($nQuantidade - $nQuantidadeAtendida)) {
    criarMovimentacaoEstoque($iCodigoLancamento, $oDadosMovimentacao, 998, round($nValorMovimentado  - ($nQuantidade - $nQuantidadeAtendida), 3));
  } else if ($nValorMovimentado < 0) {
    criarMovimentacaoEstoque($iCodigoLancamento, $oPrimeiraMovimentacaoNegativa, 999, round(abs($nValorMovimentado), 3), '-');
  } else if ($nValorMovimentado == 0 ) {
    criarMovimentacaoEstoque($iCodigoLancamento, $oDadosMovimentacao, 999, round(($nQuantidade - $nQuantidadeAtendida), 3));
  }
  return 1;
}

function criarMovimentacaoEstoque($iCodigoEntrada, $oMovimentacao, $iTipoMov, $nQuantidade, $iDiferenca = '+') {

  $iCodigoIniEntrada = db_utils::fieldsMemory(db_query("select nextval('matestoqueini_m80_codigo_seq') as m"), 0)->m;
  $sInsert  = "insert ";
  $sInsert .= "  into matestoqueini ";
  $sInsert .= "   values ({$iCodigoIniEntrada},";
  $sInsert .= "           {$oMovimentacao->m80_login},";
  $sInsert .= "           '{$oMovimentacao->m80_data}',";
  $sInsert .= "           'Ajuste Estoque',";
  $sInsert .= "          {$iTipoMov},";
  $sInsert .= "          {$oMovimentacao->m80_coddepto},";
  $sInsert .= "         '{$oMovimentacao->m80_hora}'::interval {$iDiferenca}' 1 seconds'";
  $sInsert .= ")";
  $aComandos[] = $sInsert;

  $iCodigoIniMei = db_utils::fieldsMemory(db_query("select nextval('matestoqueinimei_m82_codigo_seq') as m"), 0)->m;

  $sInsert  = "insert ";
  $sInsert .= "  into matestoqueinimei ";
  $sInsert .= "   values ({$iCodigoIniMei},";
  $sInsert .= "    {$iCodigoIniEntrada},";
  $sInsert .= "           {$iCodigoEntrada},";
  $sInsert .= "          $nQuantidade";
  $sInsert .= ")";
  $aComandos[] = $sInsert;

  $nPrecoMedio =  getUltimoPrecoMedioNaMovimentacao($oMovimentacao);
  $nValorTotal = $nPrecoMedio * $nQuantidade;
  $sInsert  = "insert ";
  $sInsert .= "  into matestoqueinimeipm ";
  $sInsert .= "   values (nextval('matestoqueinimeipm_m89_sequencial_seq'),";
  $sInsert .= "           {$iCodigoIniMei},";
  $sInsert .= "          $nPrecoMedio,";
  $sInsert .= "          $nPrecoMedio,";
  $sInsert .= "          $nValorTotal";
  $sInsert .= ")";
  $aComandos[] = $sInsert;
  foreach ($aComandos as $sInsert) {

    $rsInsert = db_query($sInsert);
    if (!$rsInsert) {
      throw new Exception("Erro no comando: {$sInsert}");
    }
  }
}


function getUltimoPrecoMedioNaMovimentacao($oMovimentacao) {

  $sSqlPrecoMedio  = "select m89_precomedio ";
  $sSqlPrecoMedio .= " from matestoqueini   ";
  $sSqlPrecoMedio .= "      inner join matestoqueinimei   on m80_codigo        = m82_matestoqueini";
  $sSqlPrecoMedio .= "      left  join matestoqueinimeipm on m82_codigo        = m89_matestoqueinimei";
  $sSqlPrecoMedio .= "      inner join matestoqueitem     on m71_codlanc       = m82_matestoqueitem ";
  $sSqlPrecoMedio .= "      inner join matestoque         on m71_codmatestoque = m70_codigo  ";
  $sSqlPrecoMedio .= "      inner join matestoquetipo     on m80_Codtipo       = m81_codtipo ";
  $sSqlPrecoMedio .= "where m71_codlanc = {$oMovimentacao->m71_codlanc}  ";
  $sSqlPrecoMedio .= "      and  to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <=
                                 to_timestamp('{$oMovimentacao->m80_data}' || ' ' || '{$oMovimentacao->m80_hora}', 'YYYY-MM-DD HH24:MI:SS')" ;
  $sSqlPrecoMedio .= " order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1";
  $rsPrecoMedio   = db_query($sSqlPrecoMedio);
  if (!$rsPrecoMedio || pg_num_rows($rsPrecoMedio) == 0) {
    return 0;
  }
  return db_utils::fieldsMemory($rsPrecoMedio, 0)->m89_precomedio;
}

function corrigirPrecoMedio ($iCodigoItem, $iCodigoDepto) {

  $sSql  = "select min(m82_codigo) as primeira_entrada ";
  $sSql .= " from  matestoque ";
  $sSql .= "       inner join matestoqueitem   on m70_codigo  = m71_codmatestoque";
  $sSql .= "       inner join matestoqueinimei on m71_codlanc = m82_matestoqueitem";
  $sSql .= " where m70_codmatmater = {$iCodigoItem}";
  $sSql .= "   and m70_coddepto    = {$iCodigoDepto}";

  $rsMateriais = db_query($sSql);
  if ($rsMateriais && pg_num_rows($rsMateriais) > 0) {

    $iCodigo = db_utils::fieldsMemory($rsMateriais, 0)->primeira_entrada;
    db_query("update matestoqueinimei set m82_quant = m82_quant where m82_codigo = {$iCodigo}");
  }
}