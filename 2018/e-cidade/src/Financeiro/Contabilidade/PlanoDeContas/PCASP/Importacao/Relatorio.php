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
namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao;

use \ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Importacao;
use \ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Conta;
use \ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo;
/**
 * Class Relatorio
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao
 */
class Relatorio {

  /**
   * @var Importacao
   */
  private $oImportacao;

  /**
   * @var \stdClass[]
   */
  private $aDadosImprimir;

  /**
   * @var Conta[]
   */
  private $aContasExclusao = array();

  /**
   * Relatorio constructor.
   * @param \ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Importacao $oImportacao
   */
  public function __construct(Importacao $oImportacao) {
    $this->oImportacao = $oImportacao;
  }

  /**
   * Retorna somente as contas que foram excluídas
   * @return Conta[]
   */
  private function getContasExclusao() {

    if (empty($this->aContasExclusao)) {

      foreach ($this->oImportacao->getModelo()->getContas() as $oConta) {

        if ($oConta->isExclusao()) {
          $this->aContasExclusao[] = $oConta;
        }
      }
    }
    return $this->aContasExclusao;
  }

  /**
   * Processando a informação para impressão do relatório
   * @throws \Exception
   */
  private function processar() {

    $iExercicio = ($this->oImportacao->getModelo()->getExercicio() - 1);
    foreach ($this->getContasExclusao() as $oConta) {

      $iNivelEstrutural    = \ContaPlano::getNivelEstrutura($oConta->getEstrutural());
      $sEstruturalAteNivel = \ContaPlano::getEstruturalAteNivel($oConta->getEstrutural(), $iNivelEstrutural);

      $aWhere = array(
        "conplano.c60_estrut ilike '".str_replace('.', '', $sEstruturalAteNivel)."%'",
        "conplano.c60_anousu = {$iExercicio}",
        "conlancamval.c69_anousu = {$iExercicio}"
      );

      $aCampos = array(
        'distinct conplano.c60_estrut as estrutural',
        'conplano.c60_descr as titulo',
      );

      $oDaoReduzidos      = new \cl_conplanoreduz();
      $sSqlBuscaReduzidos = $oDaoReduzidos->sql_query_razao(null, null, implode(',', $aCampos), "conplano.c60_estrut", implode(' and ', $aWhere));
      $rsBuscaReduzidos   = db_query($sSqlBuscaReduzidos);
      if (!$rsBuscaReduzidos) {
        throw new \Exception("Ocorreu um erro ao buscar os reduzidos.");
      }

      $iTotalRegistros = pg_num_rows($rsBuscaReduzidos);
      for ($iRowReduzido = 0; $iRowReduzido < $iTotalRegistros; $iRowReduzido++) {

        $oStdDadosImprimir      = \db_utils::fieldsMemory($rsBuscaReduzidos, $iRowReduzido);
        $this->aDadosImprimir[$oStdDadosImprimir->estrutural] = $oStdDadosImprimir;
      }
    }

    if (empty($this->aDadosImprimir)) {

      $iExercicioDestino = $this->oImportacao->getModelo()->getExercicio();
      throw new \Exception("Nenhuma conta com movimentação em ".($iExercicioDestino - 1)." excluída para o exercício de {$iExercicioDestino}.");
    }
  }

  /**
   * Emite o relatório
   */
  public function emitir() {

    $this->processar();

    $oPdf = new \PDFTable();

    $oPdf->setHeaders(array("Estrutural", "Descrição da Conta"));
    $oPdf->setPercentWidth(true);
    $oPdf->setColumnsWidth(array("20", "80"));
    $oPdf->setColumnsAlign(array('center', 'center'));

    foreach ($this->aDadosImprimir as $oDadoImprimir){

      $oPdf->addLineInformation(
        array(
          $oDadoImprimir->estrutural,
          $oDadoImprimir->titulo
        )
      );
    }

    $iAnoDestino = $this->oImportacao->getModelo()->getExercicio();
    $oPdfDocument = new \PDFDocument();
    $oPdfDocument->addHeaderDescription('Relatório de Atualização do Plano de Contas');
    $oPdfDocument->addHeaderDescription('');
    $oPdfDocument->addHeaderDescription("Contas com movimentação em ".($iAnoDestino-1).", excluídas em {$iAnoDestino}.");
    $oPdfDocument->setFillColor(235);
    $oPdfDocument->setFontSize(8);
    $oPdfDocument->open();
    $oPdf->printOut($oPdfDocument);
  }
}
