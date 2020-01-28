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
class AnexoIIIConciliacaoBancaria implements IAnexoConciliacaoBancaria {

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
  private $sNome = "ANEXO III";

  /**
   * @type string
   */
  private $sTitulo = "CRÉDITOS VÁRIOS NÃO CONTABILIZADOS";

  /**
   * Caminho das mensagens de aviso ao usuário
   * @type string
   */
  const CAMINHO_MENSAGEM = 'financeiro.caixa.AnexoIIIConciliacaoBancaria.';

  const ANEXO = 3;

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
    $rsBuscaPendencias  = AnexoIIConciliacaoBancaria::getRecord($this);

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