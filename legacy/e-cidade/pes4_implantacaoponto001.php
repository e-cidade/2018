<?php
/**
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
try {

  $oRequest     = db_utils::postMemory($_REQUEST);
  $lSubmit      = isset($oRequest->ano_folha) && isset($oRequest->mes_folha) ? true : false;
  $ano_folha    = $oRequest->ano_folha = isset($oRequest->ano_folha) ? $oRequest->ano_folha : DBPessoal::getAnoFolha();
  $mes_folha    = $oRequest->mes_folha = isset($oRequest->mes_folha) ? $oRequest->mes_folha : DBPessoal::getMesFolha();

  $oRequest->rh143_valor      = empty($oRequest->rh143_valor) ? "0" : $oRequest->rh143_valor;
  $oRequest->rh143_quantidade = empty($oRequest->rh143_quantidade) ? "0" : $oRequest->rh143_quantidade;

  $oCompetencia = new DBCompetencia($ano_folha,$mes_folha);
  $aFolhas      = null;
  $db_opcao     = 1;

  switch ( $gerf ) {
    case "com":
      $aFolhas     = FolhaPagamentoComplementar::getFolhasFechadasCompetencia($oCompetencia);
      $oDaoCalculo = new cl_gerfcom();
      break;
    case "fs":
      $aFolhas     = FolhaPagamentoSalario::getFolhasFechadasCompetencia($oCompetencia);
      $oDaoCalculo = new cl_gerfsal();
      break;
    case "supl":
      $aFolhas     = FolhaPagamentoSuplementar::getFolhasFechadasCompetencia($oCompetencia);
      $oDaoCalculo = new cl_gerfsal();
      break;
    default:
      exit;
  }

  /**
   * Não pode exibir esta mensagem ao acessar a rotina.
   */
  if ( count($aFolhas) == "0" && $lSubmit) {
    db_msgbox("Não existem folhas fechadas para a competência informada.");
  }

  $aCodigosFolha = array();

  foreach ( $aFolhas as $oFolha ) {
    $aCodigosFolha[$oFolha->getSequencial()] = $oFolha->getNumero();
  }

  /**
   * validar se os campos do lançamento estrão corretos
   */
  if ( isset($oRequest->processar) ) {

    db_inicio_transacao();
    $oFolha = FolhaPagamentoFactory::construirPeloCodigo($oRequest->rh143_folhapagamento);

    switch ($oRequest->processar) {
      case "Alterar";

        $oRegistroHistorico = new RegistroHistoricoCalculo( $oRequest->rh143_sequencial );
        $oRegistroHistorico->setNatureza( $oRequest->rh143_tipoevento );
        $oRegistroHistorico->setQuantidade( $oRequest->rh143_quantidade );
        $oRegistroHistorico->setValor( $oRequest->rh143_valor );
        $oRegistroHistorico->setServidor( ServidorRepository::getInstanciaByCodigo($oRequest->rh01_regist, $ano_folha, $mes_folha) );
        $oRegistroHistorico->setRubrica( RubricaRepository::getInstanciaByCodigo($oRequest->rh143_rubrica) );
        $oRegistroHistorico->setFolhaPagamento($oFolha);
        $oRegistroHistorico->salvar();
        break;
      case "Incluir";

        $oRegistroHistorico = new RegistroHistoricoCalculo();
        $oRegistroHistorico->setNatureza( $oRequest->rh143_tipoevento );
        $oRegistroHistorico->setQuantidade( $oRequest->rh143_quantidade );
        $oRegistroHistorico->setValor( $oRequest->rh143_valor );
        $oRegistroHistorico->setServidor( ServidorRepository::getInstanciaByCodigo($oRequest->rh01_regist, $ano_folha, $mes_folha) );
        $oRegistroHistorico->setRubrica( RubricaRepository::getInstanciaByCodigo($oRequest->rh143_rubrica) );
        $oRegistroHistorico->setFolhaPagamento($oFolha);
        $oRegistroHistorico->salvar();
        break;
      case "Excluir";

        $oRegistroHistorico = new RegistroHistoricoCalculo( $oRequest->rh143_sequencial );
        $oRegistroHistorico->excluir();
        break;
    }

    /**
     * @todo Colocar partes desta implementação em seus respectivos model's
     */
    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
    $oDaoCalculo->excluir($oRequest->ano_folha,$oRequest->mes_folha, $oRequest->rh01_regist);

    if ( $oDaoCalculo->erro_status == "0" ) {
      throw new DBException("Erro ao excluir dados totalizados do cálculo.");
    }

    $sSqlDadosConsolidados = $oDaoRhHistoricoCalculo->sql_query_dados_consolidados(array($oRequest->rh01_regist), $oFolha);
    $rsInsert              = db_query("insert into {$oFolha->getTabelaCalculo()}". $sSqlDadosConsolidados);
    if ( !$rsInsert ) {
      throw new DBException("Erro consolidar dados do cálculo.");
    }
  }
  db_fim_transacao(false);

} catch ( Exception $eErro ) {

  db_msgbox($eErro->getMessage());
  db_fim_transacao(true);
}


if ( !isset($oRequest->opcao) ) {

  $rh143_sequencial = $oRequest->rh143_sequencial = null;
  $rh143_rubrica    = $oRequest->rh143_rubrica    = null;
  $rh143_quantidade = $oRequest->rh143_quantidade = null;
  $rh143_valor      = $oRequest->rh143_valor      = null;
  $rh143_tipoevento = $oRequest->rh143_tipoevento = null;
  $rh27_descr       = $oRequest->rh27_descr       = null;
}

include ("forms/db_frmimplantacaoponto.php");
db_menu();
