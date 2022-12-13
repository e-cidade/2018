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

use \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Geral as Regra;

class ComissaoLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 233;
  const NOME_ARQUIVO  = "COMISSAO";

  /**
   * Tipos de Comissão
   *
   * Código E-Cidade -> Código LicitaCon
   */
  public static $aTipos = array(
    1 => 'P',
    2 => 'E',
    3 => 'G',
    4 => 'S',
  );

  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * Retorna comissões vinculadas a alguma licitação da instituição
   * @return resource
   * @throws DBException
   */
  private function getComissoes() {

    $aWhere   = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao());
    $aWhere[] = "l44_sigla not in ('PRD','PRI')";
    $sSqlComissoes  = "select distinct liccomissao.* from liclicita ";
    $sSqlComissoes .= "inner join cflicita             on l20_codtipocom           = l03_codigo ";
    $sSqlComissoes .= "inner join pctipocompratribunal on l03_pctipocompratribunal = l44_sequencial ";
    $sSqlComissoes .= "inner join liccomissao          on l20_liccomissao          = l30_codigo ";
    $sSqlComissoes .= "left  join liclicitaencerramentolicitacon on l18_liclicita  = l20_codigo ";
    $sSqlComissoes .= "where ".implode(' and ', $aWhere);
    $rsComissoes    = db_query($sSqlComissoes);

    if (!$rsComissoes) {
      throw new DBException('Não foi possível consultar as comissões.');
    }
    return $rsComissoes;
  }

  /**
   * @return array
   */
  public function getDados() {

    $aComissoes  = array();
    $rsComissoes = $this->getComissoes();
    for ($iComissao = 0; $iComissao < pg_num_rows($rsComissoes); $iComissao++) {

      $oDadosComissao = db_utils::fieldsMemory($rsComissoes, $iComissao);

      $iNumero             = preg_replace('/[^0-9]/', '', $oDadosComissao->l30_portaria);
      $oDataDesignacao     = new DBDate($oDadosComissao->l30_data);
      $oDataInicioVigencia = new DBDate($oDadosComissao->l30_data);
      $sTipo               = str_replace(array_keys(self::$aTipos), array_values(self::$aTipos), $oDadosComissao->l30_tipo);

      $oComissao = new stdClass;
      $oComissao->NR_COMISSAO            = $iNumero;
      $oComissao->ANO_COMISSAO           = $oDataDesignacao->getAno();
      $oComissao->TP_COMISSAO            = $sTipo;
      $oComissao->DT_DESIGNACAO          = $oDataDesignacao->getDate(DBDate::DATA_PTBR);
      $oComissao->DT_INICIO_VIGENCIA     = $oDataInicioVigencia->getDate(DBDate::DATA_PTBR);
      $oComissao->DT_FINAL_VIGENCIA      = null;
      $oComissao->NOME_ARQUIVO_DOCUMENTO = null;


      if ($oDadosComissao->l30_arquivo) {

        $sNomeArquivo = File::cutName($this->sNomeArquivo . "\\" . $oDadosComissao->l30_nomearquivo, $this->oRegra->getTamanhoNomeArquivo());
        $oComissao->NOME_ARQUIVO_DOCUMENTO           = $sNomeArquivo;
        $this->aAnexos[$oDadosComissao->l30_arquivo] = $sNomeArquivo;
      }

      $aComissoes[] = $oComissao;
    }
    return $aComissoes;
  }
}
