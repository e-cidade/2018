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

/**
  * Classe básica para geração do arquivo de retorno para o banco 
  *
  * @author Renan Pigato Silva   renan.silva@dbseller.com.br	
  * @package Consignados
  * @revision $Author: dbrenan.silva $
  * @version $Revision: 1.4 $
*/
class ExportacaoArquivoConsignado {

	const EXPORTACAO_CONSIGNADO_CAIXA = '104';

	/**
	 * Define o Banco para processar o arquivo de retorno para a instituição
	 */
	protected $oBanco;

	/**
	 * Define a Competência para processar o arquivo de retorno para a instituição
	 */
	protected $oCompetencia;

	/**
	 * Define a Instituição para processar o arquivo de retorno para a instituição
	 */
	protected $oInstituicao;

	/**
	 * Define a Configuração para processar o arquivo de retorno para a instituição
	 */
	protected $oConfiguracao;

	/**
	 * Define o caminho temporário do arquivo de importação recebido da instituição bancária
	 */
	protected $sCaminhoArquivo;

	/**
	 * Define o caminho do arquivo gerado para retorno à instituição bancária
	 */
	protected $sCaminhoArquivoRetorno;

	
	function __construct(\Banco $oBanco, \DBCompetencia $oCompetencia, \Instituicao $oInstituicao = null) {

		$this->oBanco        = $oBanco;
		$this->oCompetencia  = $oCompetencia;
		$this->oInstituicao  = $oInstituicao;

		if(empty($oInstituicao)) {
			$this->oInstituicao = InstituicaoRepository::getInstituicaoSessao();
		}

		$this->oConfiguracao = ConfiguracaoConsignadoRepository::getConfiguracaoDoBancoNaInstituicao($this->oBanco, $this->oInstituicao);

		$this->sCaminhoArquivo         = 'tmp/consignado_' . $this->oBanco->getCodigo() . '_'. $this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_AAAAMM, false) .'.txt';
		$this->sCaminhoArquivoRetorno  = 'tmp/consignado_retorno_' . $this->oBanco->getCodigo() . '_' . $this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_AAAAMM, false) .'.txt';
	}

	public function processar() {
		throw new BusinessException("Não há implementação de exportação de arquivo consignado para o banco informado.");
	}
}