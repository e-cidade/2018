<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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
namespace ECidade\Tributario\Arrecadacao\Repository;

use \Taxa as TaxaModel;
use \cl_taxa;
use \DBException;

/**
 * Class Taxa
 * @package ECidade\Tributario\Arrecadacao\Repository
 * @author Davi Busanello <davi@dbseller.com.br>
 */
class Taxa extends \BaseClassRepository
{
    protected static $itens = array();

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     *@var Taxa
     */
    protected static $oInstance;

    /**
     * @param TaxaModel $oTaxa
     * @return integer
     * @throws DBException
     */
    public function persist(TaxaModel $oTaxa)
    {
        $oDaoTaxa = new cl_taxa();
        $iSequencial = $oTaxa->getCodigoTaxa();

        $oDaoTaxa->ar36_grupotaxa          = $oTaxa->getGrupoTaxas();
        $oDaoTaxa->ar36_receita            = $oTaxa->getReceita();
        $oDaoTaxa->ar36_descricao          = $oTaxa->getDescricao();
        $oDaoTaxa->ar36_perc               = $oTaxa->getPercentual();
        $oDaoTaxa->ar36_valor              = $oTaxa->getValor();
        $oDaoTaxa->ar36_valormin           = $oTaxa->getValorMinimo();
        $oDaoTaxa->ar36_valormax           = $oTaxa->getValorMaximo();
        $oDaoTaxa->ar36_debitoscomprocesso = ($oTaxa->isDebitosComProcesso() ? 't' : 'f');
        $oDaoTaxa->ar36_debitossemprocesso = ($oTaxa->isDebitosSemProcesso() ? 't' : 'f');

        if (!empty($iSequencial)) {
            $oDaoTaxa->ar36_sequencial = $iSequencial;
            $lResult = $oDaoTaxa->alterar($iSequencial);
        } else {
            $lResult = $oDaoTaxa->incluir(null);
        }

        if (!$lResult) {
            $sMensagem  = 'Ocorreu um erro ao ';
            $sMensagem .= (empty($iSequencial) ? 'incluir' : 'alterar');
            $sMensagem .= ' a taxa. ' . $oDaoTaxa->erro_msg;
            throw new DBException($sMensagem);
        }

        return $oDaoTaxa->ar36_sequencial;
    }

    /**
     * @param \stdClass $dados
     * @return null|TaxaModel
     */
    protected function make($dados)
    {

        if (empty($dados)) {
            return NULL;
        }

        $oTaxa = new TaxaModel();
        $oTaxa->setTaxas($dados->ar36_sequencial);
        $oTaxa->setGrupoTaxas($dados->ar36_grupotaxa);
        $oTaxa->setReceita($dados->ar36_receita);
        $oTaxa->setDescricao($dados->ar36_descricao);
        $oTaxa->setPercentual($dados->ar36_perc);
        $oTaxa->setValor($dados->ar36_valor);
        $oTaxa->setValorMinimo($dados->ar36_valormin);
        $oTaxa->setValorMaximo($dados->ar36_valormax);
        $oTaxa->setDebitosComProcesso(($dados->ar36_debitoscomprocesso == 't' ? true : false));
        $oTaxa->setDebitosSemProcesso(($dados->ar36_debitossemprocesso == 't' ? true : false));

        return $oTaxa;
    }

    /**
     * Obtem a taxa por codigo
     * @param $iCodigo
     * @return null|TaxaModel
     * @throws DBException
     */
    public function getByCodigo($iCodigo)
    {
        if (!empty($iCodigo)) {

            $oDaoTaxa = new cl_taxa;

            $rsTaxa = $oDaoTaxa->sql_record($oDaoTaxa->sql_query_file($iCodigo));

            if (!$rsTaxa || $oDaoTaxa->numrows == 0) {
                throw new DBException("[1]Erro ao consultar a taxa {$iCodigo}. ERRO: {$oDaoTaxa->erro_msg}");
            }

            return $this->make((object) $oDaoTaxa);
        }

        return null;
    }

    /**
     * @param $rsResult
     * @return TaxaModel[]
     */
    private function makeCollection($rsResult)
    {
        $aCollection = array();
        $aResult = pg_fetch_all($rsResult);

        if (empty($aResult)) {
            return array();
        }

        foreach ($aResult as $oResult) {
            $aCollection[] = $this->make((object) $oResult);
        }

        return $aCollection;
    }

    /**
     * Obtem todas as taxas aplicadas a debitos com processo
     * @return null|TaxaModel[]
     * @throws DBException
     */
    public function getTodasComProcesso()
    {
        $oDaoTaxa = new cl_taxa();
        $sWhere = "ar36_debitoscomprocesso = 't'";
        $sSql = $oDaoTaxa->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException('Ocorreu um erro ao buscar as Taxas aplicadas a Débitos com Processo.');
        }

        return $this->makeCollection($rsResult);
    }

    /**
     * Obtem todas as taxas aplicadas a debitos com processo
     * @return null|TaxaModel[]
     * @throws DBException
     */
    public function getTodasSemProcesso()
    {
        $oDaoTaxa = new cl_taxa();
        $sWhere = "ar36_debitossemprocesso = 't'";
        $sSql = $oDaoTaxa->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException('Ocorreu um erro ao buscar as Taxas aplicadas a Débitos sem Processo.');
        }

        return $this->makeCollection($rsResult);
    }
}
