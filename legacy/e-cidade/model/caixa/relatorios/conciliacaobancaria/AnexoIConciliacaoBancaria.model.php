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
 * Class AnexoIConciliacaoBancaria
 */
class AnexoIConciliacaoBancaria implements IAnexoConciliacaoBancaria {

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
  private $sNome = "ANEXO I";

  /**
   * @type string
   */
  private $sTitulo = "DEPÓSITOS AINDA NÃO CREDITADOS NO EXTRATO";

  /**
   * Caminho das mensagens de aviso ao usuário
   * @type string
   */
  const CAMINHO_MENSAGEM = 'financeiro.caixa.AnexoIConciliacaoBancaria.';

  /**
   * @var int
   */
  const ANEXO = 1;

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
    $iCodigoConta       = $this->oContabancaria->getSequencialContaBancaria();
    $sWhereConciliacao  = "     k89_concilia = (select max(k68_sequencial) ";
    $sWhereConciliacao .= "                       from concilia ";
    $sWhereConciliacao .= "                      where extract(month from k68_data) = '{$this->oCompetencia->getMes()}'";
    $sWhereConciliacao .= "                        and extract(year from k68_data)  =  '{$this->oCompetencia->getAno()}'";
    $sWhereConciliacao .= "                        and k68_contabancaria            = {$iCodigoConta})";
    $sWhereConciliacao .= "     and ((rnvalordebito <> 0";
    $sWhereConciliacao .= "          and rnvalordebito is not null)";
    $sWhereConciliacao .= "          or ";
    $sWhereConciliacao .= "        (rivalorcredito is not null ";
    $sWhereConciliacao .= "        and rivalorcredito <> 0 )";
    $sWhereConciliacao .= "                                  )";
    $sWhereConciliacao .= "     and not exists (select 1";
    $sWhereConciliacao .= "                       from corgrupocorrente";
    $sWhereConciliacao .= "                      where k105_autent = k89_autent";
    $sWhereConciliacao .= "                        and k105_id     = k89_id";
    $sWhereConciliacao .= "                        and k105_data   = k89_data";
    $sWhereConciliacao .= "                        and k105_corgrupotipo in (2,3,5,6)";
    $sWhereConciliacao .= "                        and extract(year from k105_data) <= 2012 )";
    $sWhereConciliacao .= "   group by ricaixa, riautent, ridata ";
    $sWhereConciliacao .= "   order by data, ricaixa, riautent ";

    $sCamposPendencia  = " ricaixa, riautent, ridata as data,";
    $sCamposPendencia .= " sum(case";
    $sCamposPendencia .= "   when rnvalordebito  is not null ";
    $sCamposPendencia .= "    and rnvalordebito <> 0 ";
    $sCamposPendencia .= "   then rnvalordebito";
    $sCamposPendencia .= "   else rivalorcredito * -1";
    $sCamposPendencia .= " end) as valor";

    $oDaoConciliacaoPendente = new cl_conciliapendcorrente();
    $sSqlBuscaPendencias     = $oDaoConciliacaoPendente->sql_query_pendencia_tesouraria($iCodigoConta, $sCamposPendencia, $sWhereConciliacao);
    $rsBuscaPendencias       = db_query($sSqlBuscaPendencias);

    if (!$rsBuscaPendencias) {
      throw new BusinessException(_M(self::CAMINHO_MENSAGEM."erro_busca_dados"));
    }

    $iTotalPendencias = pg_num_rows($rsBuscaPendencias);
    for ($iPendencia  = 0; $iPendencia < $iTotalPendencias; $iPendencia++) {

      $oStdPendencia = db_utils::fieldsMemory($rsBuscaPendencias, $iPendencia);
      $oRegistro = new RegistroAnexoConciliacaoBancaria();
      $oRegistro->setData(new DBDate($oStdPendencia->data));
      $oRegistro->setValor($oStdPendencia->valor);
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