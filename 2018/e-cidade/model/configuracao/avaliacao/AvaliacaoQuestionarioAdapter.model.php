<?php
/**
 *         E-cidade Software Publico para Gestao Municipal
 *      Copyright (C) 2016  DBSeller Servicos de Informatica
 *                       www.dbseller.com.br
 *                    e-cidade@dbseller.com.br
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
class AvaliacaoQuestionarioAdapter Extends AvaliacaoAdapter {

	/**
	 * Servidor
	 * @var Servidor
	 */
	private $oServidor;

	/**
	 * @var CgmFisico|CgmJuridico
	 */
	private $oCgm;

	/**
	 * Rotina deve trazer os dados Sugeridos.
	 * @var bool
	 */
	private $lTrazerSugestoes = false;


	/**
	 * Define o codigo do Servidor
	 * @param integer
	 */
	public function setServidor ($oServidor) {
	  $this->oServidor = $oServidor;
	}

	public function trazerSugestoes($lTrazeSugestoes = false) {
		$this->lTrazerSugestoes = $lTrazeSugestoes;
	}

	/**
	 * Define o Cgm
	 * @param \CgmBase $oCgm
	 */
	public function setCgm (CgmBase $oCgm) {
		$this->oCgm = $oCgm;
	}
	/**
	 * Retorna o codigo do Servidor
	 * @return Servidor
	 */
	public function getServidor () {
	  return $this->oServidor; 
	}
	

	protected function getPerguntas(\AvaliacaoGrupo $avaliacaoGrupo) {

		$aPerguntas = array();

    foreach ($avaliacaoGrupo->getPerguntas() as $pergunta) {

      $pergunta->getRespostas();
      
      $aRespostas = $this->consultaRespostasPergunta($pergunta);
      $pergunta->setResposta($aRespostas);

      $oPergunta = new \StdClass();          
      $oPergunta->codigo        = $pergunta->getCodigo();
      $oPergunta->id            = $pergunta->getIdentificador();
      $oPergunta->label         = $pergunta->getDescricao();
      $oPergunta->tipo_resposta = $pergunta->getTipo();
      $oPergunta->tipo          = $pergunta->getTipoComponente();//$pergunta->getCodigo();
      $oPergunta->ordem         = 1;
      $oPergunta->obrigatoria   = $pergunta->isObrigatoria();
      $oPergunta->ativo         = $pergunta->isAtivo();
      $oPergunta->formato       = $pergunta->getCodigoFormula();
      $oPergunta->mascara       = $pergunta->getMascara();
      $oPergunta->respostas     = $this->getRespostas($pergunta);
      $aPerguntas[]             = $oPergunta;
    }    

    return $aPerguntas;
	}

	/**
	 * Consulta na base de dados as respostas 
	 * para o questionário por servidor
	 */
	protected function consultaRespostasPergunta(AvaliacaoPergunta $oPergunta) {

		$sSqlRespostas = $this->getConsultaParaAsRespostasDaPergunta($oPergunta);
	  $rsRespostas   = db_query($sSqlRespostas);

		$aResposta = array();
	  if(!$rsRespostas) {
	  	throw new DBException("Ocorreu um erro ao consultar as respostas da pergunta: \n".$oPergunta->getDescricao());
	  }

	  if (pg_num_rows($rsRespostas) > 0) {

	  	$aResposta = db_utils::makeCollectionFromRecord($rsRespostas , function ($oResposta) { 

	  		$oStdResposta = new StdClass();
	  		$oStdResposta->codigoresposta = $oResposta->db106_avaliacaoperguntaopcao;					
	  		$oStdResposta->textoresposta = $oResposta->db106_resposta;					
	  		return $oStdResposta;
	  	});
	  }
    if (count($aResposta) == 0 && $this->lTrazerSugestoes) {
			$aResposta  = $this->getSugestaoRespostaDaPergunta($oPergunta);
		}
		return $aResposta;
	}

	/**
	 * Retorna as sugestoes das perguntas
	 * @param \AvaliacaoPergunta $oPergunta
	 * @return string
	 * @throws \BusinessException
	 * @throws \DBException
	 */
	private function getSugestaoRespostaDaPergunta(AvaliacaoPergunta $oPergunta) {

		$aRespostas  = array();

    $sSqlFormulaPergunta = $oPergunta->getFormulaVinculada();
		if (empty($sSqlFormulaPergunta)) {
      return $aRespostas;
		}
		$oFormulaEsocialSugestaoResposta = $this->getContextoFormula();
		if (empty($oFormulaEsocialSugestaoResposta)) {
			return $aRespostas;
		}

		$sSqlFormulaPergunta = 'SELECT substring(ROW(sugestoes.*)::varchar, \'^\\\("*(.*?)"*\\\)$\') AS sugestao FROM ['. $sSqlFormulaPergunta .'] AS sugestoes';
		$sSqlRespostas =  $oFormulaEsocialSugestaoResposta->parse($sSqlFormulaPergunta);

		$rsRespostas   = db_query("{$sSqlRespostas}");
		if (!$rsRespostas) {
			throw new DBException("Erro ao excutar fórmula {$sSqlRespostas}, para a pergunta {$oPergunta->getDescricao()}");
		}

		$aRespostas = db_utils::makeCollectionFromRecord($rsRespostas , function ($oResposta) use ($oPergunta) {

			$iCodigoResposta = '';
			$sTextoResposta  = '';
			switch ($oPergunta->getTipo()) {

				case AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA:

					$iCodigoResposta = $oResposta->sugestao;
					$sTextoResposta  = 1;
					break;
				case AvaliacaoPergunta::TIPO_RESPOSTA_DISSERTATIVA:
					
					$aRespostaPergunta = $oPergunta->getRespostas();
					$iCodigoResposta = $aRespostaPergunta[0]->codigoresposta;
					$sTextoResposta  = $oResposta->sugestao;
					break;
			  case AvaliacaoPergunta::TIPO_RESPOSTA_MULTIPLA:
					$sTextoResposta  = 1;
  				$iCodigoResposta = $oResposta->sugestao;
			 	  break;

			}

			$oStdResposta                 = new \stdClass();
			$oStdResposta->codigoresposta = $iCodigoResposta;
			$oStdResposta->textoresposta  = $sTextoResposta;
			return $oStdResposta;
		});
    return $aRespostas;
	}

	/**
	 * @return \DBFormulaCGM|\DBFormulaMatricula|null
	 */
	private function getContextoFormula() {

		$oContextoMatricula = $this->getContextoMatricula();
		if (!empty($oContextoMatricula)) {
			return $oContextoMatricula;
		}
		$oContextoCgm = $this->getContextoCgm();
		if (!empty($oContextoCgm)) {
			return $oContextoCgm;
		}
		return null;
	}

	/**
	 * @return \DBFormulaMatricula|null
	 */
	private function getContextoMatricula() {

		if (!empty($this->oServidor)) {

			$oFormulaEsocialSugestaoResposta  = new DBFormulaMatricula($this->getServidor());
			$oFormulaEsocialSugestaoResposta->adicionarVariavelServidor('ESOCIAL_MATRICULA_SERVIDOR');
			$oFormulaEsocialSugestaoResposta->adicionar('ESOCIAL_INSTITUICAO', InstituicaoRepository::getInstituicaoSessao()->getCodigo());
			$oFormulaEsocialSugestaoResposta->adicionar('CODIGO_CGM', $this->getServidor()->getCgm()->getCodigo());
      return $oFormulaEsocialSugestaoResposta;
		}
		return null;
	}

	/**
	 * @return \DBFormulaCGM|null
	 */
	private function getContextoCgm() {

		if (!empty($this->oCgm)) {

			$oFormulaEsocialSugestaoResposta = new DBFormulaCGM($this->oCgm);
			$oFormulaEsocialSugestaoResposta->adicionar('CODIGO_CGM', $this->oCgm->getCodigo());
			return $oFormulaEsocialSugestaoResposta;
		}
		return null;
	}

	private function getConsultaParaAsRespostasDaPergunta(AvaliacaoPergunta $oPergunta) {

		if (!empty($this->oServidor)) {
			return $this->getConsultaParaRespostasDaMatricula($oPergunta);
		}
		if (!empty($this->oCgm)) {
			return $this->getConsultaParaRespostasDoCgm($oPergunta);
		}
	}
	private function getConsultaParaRespostasDaMatricula(AvaliacaoPergunta $oPergunta) {

		$oDaoAvaliacaoGrupoRespostaServidor = new cl_avaliacaogruporespostarhpessoal();
		$sCamposRespostas  = "db106_avaliacaoperguntaopcao,";
		$sCamposRespostas .= "  case when db106_resposta = ''and db103_avaliacaotiporesposta <> 2 ";
		$sCamposRespostas .= "       then ";
		$sCamposRespostas .= "          case when db103_avaliacaotiporesposta = 1 and db104_sequencial in (select db104_sequencial from avaliacaopergunta inner join avaliacaoperguntaopcao on db104_avaliacaopergunta = db103_sequencial inner join avaliacaoresposta on db106_avaliacaoperguntaopcao = db104_sequencial where db103_sequencial = {$oPergunta->getCodigo()} order by db106_sequencial desc limit 1)";
		$sCamposRespostas .= "               then '1'";
		$sCamposRespostas .= "               else '1'";
		$sCamposRespostas .= "          end";
		$sCamposRespostas .= "       else db106_resposta ";
		$sCamposRespostas .= "   end as db106_resposta";
		$sSqlRespostas     = $oDaoAvaliacaoGrupoRespostaServidor->buscaRespostasPorPerguntaMatricula($oPergunta->getCodigo(), $this->getServidor()->getMatricula(), $sCamposRespostas, null);
    return $sSqlRespostas;
	}

	private function getConsultaParaRespostasDoCgm(AvaliacaoPergunta $oPergunta) {

		$oDaoAvaliacaoGrupoRespostaServidor = new cl_avaliacaogruporespostacgm();
		$sCamposRespostas  = "db106_avaliacaoperguntaopcao,";
		$sCamposRespostas .= "  case when db106_resposta = ''and db103_avaliacaotiporesposta <> 2 ";
		$sCamposRespostas .= "       then ";
		$sCamposRespostas .= "          case when db103_avaliacaotiporesposta = 1 and db104_sequencial in (select db104_sequencial from avaliacaopergunta inner join avaliacaoperguntaopcao on db104_avaliacaopergunta = db103_sequencial inner join avaliacaoresposta on db106_avaliacaoperguntaopcao = db104_sequencial where db103_sequencial = {$oPergunta->getCodigo()} order by db106_sequencial desc limit 1)";
		$sCamposRespostas .= "               then '1'";
		$sCamposRespostas .= "               else '1'";
		$sCamposRespostas .= "          end";
		$sCamposRespostas .= "       else db106_resposta ";
		$sCamposRespostas .= "   end as db106_resposta";
		$sSqlRespostas     = $oDaoAvaliacaoGrupoRespostaServidor->buscaRespostasPorPergunta($oPergunta->getCodigo(), $this->oCgm->getCodigo(), $sCamposRespostas, null);
    return $sSqlRespostas;
	}
}