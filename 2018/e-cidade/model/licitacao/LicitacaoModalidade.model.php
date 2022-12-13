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

/**
 * Class LicitacaoModalidade
 * @todo implementado somente o básico para o funcionamento
 */
class LicitacaoModalidade 
{
    /**
     * Sequencial
     * @var integer
     */
    private $iCodigo;

    /**
     * Descrição
     * @var string
     */
    private $sDescricao;

    /**
     * @type string
     */
    private $sSigla;

    /**
     * @type int
     */
    private $iCodigoTipoCompraTribunal;


    /**
     * @var string
     */
    private $sSiglaTipoCompraTribunal = null;

    /**
      * Tipo Chamamento Público / Credenciamento
      *
      * @var string
      */
    const CHAMAMENTO_PUBLICO_CREDENCIAMENTO = 'CPC';

    /**
     * Caminho das mensagens utilizadas pelo model
     * @var string
     */
    const URL_MENSAGENS = 'patrimonial.licitacao.LicitacaoModalidade.';

    /**
     * @param null $iCodigo
     * @throws BusinessException
     */
    public function __construct($iCodigo = null) 
    {
        $this->iCodigo = $iCodigo;

        if (empty($iCodigo)) {
            return;
        }

        $oDaoModalidade = new cl_cflicita;
        $sSqlBuscaModalidade = $oDaoModalidade->sql_query_file($this->iCodigo);
        $rsBuscaModalidade = $oDaoModalidade->sql_record($sSqlBuscaModalidade);

        if ($oDaoModalidade->erro_status == '0') {
            throw new BusinessException(_M(self::URL_MENSAGENS . 'modalidade_nao_encontrada'));
        }

        $oStdModalidade = db_utils::fieldsMemory($rsBuscaModalidade, 0);

        $this->sSigla = $oStdModalidade->l03_tipo;
        $this->sDescricao = $oStdModalidade->l03_descr;
        $this->iCodigoTipoCompraTribunal = $oStdModalidade->l03_pctipocompratribunal;

        unset($oDaoModalidade);
    }

    /**
     * @return int
     */
    public function getCodigo() 
    {
        return $this->iCodigo;
    }

    /**
     * @return string
     */
    public function getDescricao() 
    {
        return $this->sDescricao;
    }

    /**
     * @return string
     */
    public function getSigla() 
    {
        return $this->sSigla;
    }

    /**
     * @return int
     */
    public function getCodigoTipoCompraTribunal() 
    {
        return $this->iCodigoTipoCompraTribunal;
    }

    /**
     * Verifica se já possui a modalidade cadastrada com a STRING informada no parâmetro
     *
     * @param $sTipo
     * @return bool
     */
    public static function possuiTipoCadastrado($sTipo) 
    {
        $sTipo = strtoupper($sTipo);
        $oDaoCfLicita = new cl_cflicita;
        $sSqlBusca = $oDaoCfLicita->sql_query_file(null, "*", null, "l03_tipo = '{$sTipo}'");
        $rsBuscaModalidade = db_query($sSqlBusca);

        return pg_num_rows($rsBuscaModalidade) > 0;
    }

    /**
      * @return string
      * @throws BusinessException
      */
    public function getSiglaTipoCompraTribunal() 
    {
        if ($this->sSiglaTipoCompraTribunal === null) {
            $oDaoTipoCompra = new cl_pctipocompratribunal;
            $sSqlTipoCompra = $oDaoTipoCompra->sql_query_file($this->getCodigoTipoCompraTribunal(),
                                                            'l44_sigla');
            $rsTipoCompra = $oDaoTipoCompra->sql_record($sSqlTipoCompra);
          
            if ($oDaoTipoCompra->erro_status == '0' && $oDaoTipoCompra->numrows == 0) {
                throw new BusinessException('Não foi encontrado o tipo de compra do tribunal.');
            }

            $this->sSiglaTipoCompraTribunal = db_utils::fieldsMemory($rsTipoCompra, 0)->l44_sigla;
        }

        return $this->sSiglaTipoCompraTribunal;
    }
}