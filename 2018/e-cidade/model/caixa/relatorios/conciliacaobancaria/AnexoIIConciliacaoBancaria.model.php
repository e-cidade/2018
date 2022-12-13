<?php
/**
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

require_once ('model/caixa/relatorios/conciliacaobancaria/IAnexoConciliacaoBancaria.interface.php');
/**
 * Class AnexoIIConciliacaoBancaria
 */
class AnexoIIConciliacaoBancaria implements IAnexoConciliacaoBancaria {

  /**
   * @type ContaBancaria
   */
  private $oContabancaria;

  /**
   * @type DBCompetencia
   */
  private $oCompetencia;

  /**
   * @type string
   */
  private $sNome = "ANEXO II";

  /**
   * @type string
   */
  private $sTitulo = "DÉBITOS VÁRIOS NÃO CONTABILIZADOS";

  /**
   * Caminho das mensagens de aviso ao usuário
   * @type string
   */
  const CAMINHO_MENSAGEM = 'financeiro.caixa.AnexoIIConciliacaoBancaria.';

  const ANEXO = 2;

  /**
   * @param ContaBancaria $oContaBancaria
   * @param DBCompetencia $oCompetencia
   */
  public function __construct(ContaBancaria $oContaBancaria, DBCompetencia $oCompetencia) {

    $this->oContabancaria = $oContaBancaria;
    $this->oCompetencia   = $oCompetencia;
  }

  /**
   * @throws BusinessException
   * @return RegistroAnexoConciliacaoBancaria[]
   */
  public function getDados() {

    $aRegistros         = array();
    $rsBuscaPendencias  = self::getRecord($this);
    if (!$rsBuscaPendencias) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM."erro_busca_dados"));
    }

    $iTotalPendencias = pg_num_rows($rsBuscaPendencias);
    for ($iPendencia  = 0; $iPendencia < $iTotalPendencias; $iPendencia++) {

      $oStdPendencia = db_utils::fieldsMemory($rsBuscaPendencias, $iPendencia);
      $oRegistro = new RegistroAnexoConciliacaoBancaria();
      $oRegistro->setData(new DBDate($oStdPendencia->k86_data));
      $oRegistro->setNatureza($oStdPendencia->k86_historico);
      $oRegistro->setValor($oStdPendencia->k86_valor);
      $aRegistros[] = $oRegistro;
    }
    return $aRegistros;
  }


  /**
   * @param IAnexoConciliacaoBancaria $oAnexo
   *
   * @return resource
   * @throws BusinessException
   */
  public static function getRecord(IAnexoConciliacaoBancaria $oAnexo) {

    $sSqlConciliacao  = " (select max(k68_sequencial) ";
    $sSqlConciliacao .= "    from concilia ";
    $sSqlConciliacao .= "   where extract(month from k68_data) = {$oAnexo->getCompetencia()->getMes()}";
    $sSqlConciliacao .= "     and extract(year from k68_data)  = {$oAnexo->getCompetencia()->getAno()}";
    $sSqlConciliacao .= "     and k68_contabancaria            = {$oAnexo->getContaBancaria()->getSequencialContaBancaria()})";
    $sCampos = "k86_data, k86_documento, k86_historico, k86_valor, k86_tipo";

    $aWhere  = array(
      "k88_concilia = {$sSqlConciliacao}"
    );
    switch ($oAnexo->getAnexo()) {

      case AnexoIIConciliacaoBancaria::ANEXO:
        $aWhere[] = "k86_tipo = 'D'";
        break;

      case AnexoIIIConciliacaoBancaria::ANEXO:
        $aWhere[] = "k86_tipo = 'C'";
        break;

      default:
        throw new BusinessException(_M(self::CAMINHO_MENSAGEM.'anexo_nao_encontrado'));

    }
    $oDaoPentenciaExtrato = new cl_conciliapendextrato();
    $sSqlBuscaPendencia   = $oDaoPentenciaExtrato->sql_query_extrato_sigfis(null, $sCampos, "k86_data", implode(' and ', $aWhere));
    return db_query($sSqlBuscaPendencia);
  }

  /**
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * @return string
   */
  public function getTitulo() {
    return $this->sTitulo;
  }

  /**
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * @return ContaBancaria
   */
  public function getContaBancaria() {
    return $this->oContabancaria;
  }

  /**
   * @return int
   */
  public function getAnexo() {
    return self::ANEXO;
  }
}