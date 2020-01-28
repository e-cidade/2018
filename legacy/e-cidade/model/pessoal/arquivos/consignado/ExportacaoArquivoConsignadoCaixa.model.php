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
  * Gera o arquivo de retorno para o banco 
  * com a informa��o sobre os descontos
  *
  * @author Renan Pigato Silva   renan.silva@dbseller.com.br	
  * @package Consignados
  * @revision $Author: dbstephano.ramos $
  * @version $Revision: 1.10 $
*/
class ExportacaoArquivoConsignadoCaixa extends ExportacaoArquivoConsignado {

	const ACATADO                   = 1;
	const EXCLUIDO                  = 2;
	const EXCESSO_DEBITO            = 3;
	const MATRICULA_INVALIDA        = 4;
	const DIGITO_MATRICULA_INVALIDA = 5;
	const APOSENTADO                = 6;
	const RESCISAO                  = 7;
	const AFASTADO                  = 8;
	const OUTROS                    = 9;
	
	/**
	 * Define o ArquivoProcessado na importa��o
	 */
	private $oArquivoProcessado;
	
	/**
	 * Define os Registros do arquivo processado na importa��o
	 */
	private $aRegistros;
	
	/**
	 * Define o LayoutArquivo do arquivo processado na importa��o
	 * @var DBLayoutReader
	 */
	private $oLayoutArquivo;

	/**
	 * Construtor da classe
	 *
	 * @param Banco         $oBanco
	 * @param DBCompetencia $oCompetencia
	 * @param Instituicao   $oInstituicao
	 * @throws \BusinessException
	 * @throws \DBException
	 * @throws \Exception
	 */
	function __construct(\Banco $oBanco, \DBCompetencia $oCompetencia, \Instituicao $oInstituicao = null) {

		parent::__construct($oBanco, $oCompetencia, $oInstituicao);

		$this->oArquivoProcessado = ArquivoConsignadoRepository::getUltimoArquivoNaCompetenciaDoBanco($this->oInstituicao, $this->oCompetencia, $this->oBanco, true);

		if(empty($this->oArquivoProcessado)) {

			throw new Exception("N�o foi poss�vel buscar os registros para o arquivo informado.\nVerifique a compet�ncia informada.");
		}

		$this->aRegistros = RegistroConsignadoRepository::getRegistrosDoArquivo($this->oArquivoProcessado);


	}

	/**
	 * Processa o arquivo para retorno � institui��o
	 */
	public function processar() {

		DBLargeObject::leitura($this->oArquivoProcessado->getArquivo(), $this->sCaminhoArquivo);
		$this->oLayoutArquivo    = new DBLayoutReader($this->oConfiguracao->getLayout()->getCodigo(), $this->sCaminhoArquivo);

		$pCaminhoArquivoRetorno  = fopen($this->sCaminhoArquivoRetorno, 'w');

		$this->escreverHeaderArquivo($pCaminhoArquivoRetorno);

		$oValoresTotais = new stdClass;

		$oValoresTotais->acatado   = 0;
		$oValoresTotais->rejeitado = 0;

		foreach ($this->aRegistros as $iRegistro => $oRegistro) {
            
			$iMotivo = $oRegistro->getMotivo();

			switch ($iMotivo) {
				case '2': // SERVIDOR N�O IDENTIFICADO informa 4-Matr�cula inv�lida
					$iMotivo = self::MATRICULA_INVALIDA;
					break;

				case '4': // MARGEM CONSIGN�VEL EXCEDIDA informa 3-Excesso de d�bito
					$iMotivo = self::EXCESSO_DEBITO;
					break;

				case '6': // SERVIDOR DESLIGADO          informa 7-Rescis�o
					$iMotivo = self::RESCISAO;
					break;
					
				case '7': // SERVIDOR SERVIDOR AFASTADO EM LICEN�A SA�DE informa 8-Afastado
					$iMotivo = self::AFASTADO;
					break;
				
				case '1': // FALECIMENTO                             informa 9-Outros
				case '3': // TIPO DE CONTRATO N�O PERMITE EMPR�STIMO informa 9-Outros
				case '5': // N�O DESCONTADO - OUTROS MOTIVOS         informa 9-Outros
				case '9':
				  $iMotivo = self::OUTROS;
				  break;
			  case RegistroConsignado::MOTIVO_EXCLUIDO:
					
					$iMotivo = self::EXCLUIDO;
					break;

				default:  // informa 1-Acatado
				
					$iMotivo = self::ACATADO;
					$oValoresTotais->acatado += (float)$oRegistro->getValorDescontado();
					break;
			}

			if($iMotivo != self::ACATADO) {
				$oValoresTotais->rejeitado += (float)$oRegistro->getValorDescontar();
			}

      if ($oLinha = $this->oLayoutArquivo->getLineByInd($iRegistro + 1)) {

			  if($oLinha->getProperties('codigo_ocorrencia_processamento')) { 

      		$oLinha->substituirConteudoCampo(str_pad($iMotivo, 2, '0', STR_PAD_LEFT), 'codigo_ocorrencia_processamento');
			    fwrite($pCaminhoArquivoRetorno, $oLinha->getLinha());
	  	  }
		  }
		}
 
		$this->escreverFooterArquivo($pCaminhoArquivoRetorno, $iRegistro + 2, $oValoresTotais);

		fclose($pCaminhoArquivoRetorno);
		unlink($this->sCaminhoArquivo);

		return $this->sCaminhoArquivoRetorno;
	}

	/**
	 * Escreve o cabe�alho do arquivo de retorno
	 *
	 * @param resource $pCaminhoArquivoRetorno  do arquivo de retorno, para escrita
	 */
	public function escreverHeaderArquivo($pCaminhoArquivoRetorno) {

    $oLinhaHeader = $this->oLayoutArquivo->getLineByInd(0);

		$oLinhaHeader->substituirConteudoCampo(str_pad(2, 1, '0', STR_PAD_LEFT), 'codigo_remessa_retorno');
		$oLinhaHeader->substituirConteudoCampo(str_pad('RETORNO', 7, ' ', STR_PAD_LEFT), 'nome_remessa_retorno');

    fwrite($pCaminhoArquivoRetorno, $oLinhaHeader->getLinha());
	}

	/**
	 * Escreve o rodap� do arquivo de retorno
	 *
	 * @param         $pCaminhoArquivoRetorno
	 * @param Integer $iLinhaFooter Linha do Footer do arquivo de retorno
	 * @param         $oValoresTotais
	 */
	public function escreverFooterArquivo($pCaminhoArquivoRetorno, $iLinhaFooter, $oValoresTotais) {

		$oLinhaFooter = $this->oLayoutArquivo->getLineByInd($iLinhaFooter);

		$oLinhaFooter->substituirConteudoCampo(str_pad(number_format($oValoresTotais->rejeitado, 2, '', ''), 17, '0', STR_PAD_LEFT), 'valor_total_rejeicoes');
		$oLinhaFooter->substituirConteudoCampo(str_pad(number_format($oValoresTotais->acatado, 2, '', ''), 17, '0', STR_PAD_LEFT), 'valor_total_acatados');
    
    fwrite($pCaminhoArquivoRetorno, $oLinhaFooter->getLinha());
	}
}