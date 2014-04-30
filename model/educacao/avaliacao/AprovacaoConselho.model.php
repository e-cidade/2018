<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

define("URL_APROVCONSELHO", "educacao.escola.AprovacaoConselho.");

/**
 * Controla as informacoes sobre uma aprovacao pelo conselho
 * @author Fabio Esteves - <fabio.esteves@dbseller.com.br>
 * @package educacao
 * @subpackage avaliacao
 */
class AprovacaoConselho {
	
	/**
   * Codigo da aprovacao pelo conselho
   * @var integer
   */
	private $iCodigo;
	
	/**
	 * Justificativa para aprovacao do conselho
	 * @var string
	 */
	private $sJustificativa = '';
	
	/**
	 * Código do RecHumano
	 * @todo Refatorar para aceitar somente docentes 
	 * @var integer
	 */
	private $iRecHumano = null;
	
	/**
	 * Forma de aprovacao
	 * 1 - APROVADO_CONSELHO
   * 2 - RECLASSIFICACAO_BAIXA_FREQUENCIA
	 * @var integer
	 */
	private $iFormaAprovacao;
	
	/**
	 * Instancia de UsuarioSistema
	 * @var UsuarioSistema
	 */
	private $oUsuario;
	
	/**
	 * Timestamp da data
	 * @var DBDate
	 */
	private $oData;
	
	/**
	 * Hora da Aprovação do conselho
	 * @var string
	 */
	private $sHora;
	
	/**
	 * Instancia de AvaliacaoResultadoFinal
	 * @var AvaliacaoResultadoFinal
	 */
	private $oAvaliacaoResultadoFinal;
	
	CONST APROVADO_CONSELHO = 1;
	
	CONST RECLASSIFICACAO_BAIXA_FREQUENCIA = 2;
	
	CONST APROVADO_CONFORME_REGIMENTO_ESCOLAR = 3;

	/**
	 * Array com as descrições dos tipos de aprovação utilizado pelo conselho
	 * @var array
	 */
	private static $aTiposAprovacao = array();
	
	/**
	 * Construtor da classe. Recebe uma instancia de AvaliacaoResultadoFinal
	 * @param AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal
	 */
	public function __construct(AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal) {
		$this->oAvaliacaoResultadoFinal = $oAvaliacaoResultadoFinal;
	}
	
	/**
	 * Retorna o codigo de AprovacaoConselho
	 * @return integer
	 */
	public function getCodigo() {
		return $this->iCodigo;
	}
	
	/**
	 * Seta o codigo de AprovacaoConselho
	 * @param integer $iCodigo
	 */
	public function setCodigo($iCodigo) {
		$this->iCodigo = $iCodigo;
	}
	
	/**
	 * Retorna uma instancia de Justificativa
	 * @return Justificativa
	 */
	public function getJustificativa() {
	  return $this->sJustificativa;
	}

	/**
	 * Seta a justificativa para aprovacao
	 * @param $sJustificativa
	 */
	public function setJustificativa($sJustificativa) {
	  $this->sJustificativa = $sJustificativa;
	}

	/**
	 * Retorna código do rechumno
	 * @return integer 
	 */
	public function getRecursoHumano() {
	  return $this->iRecHumano;
	}

	/**
	 * Seta código do rechumno
	 * @param integer $iRecHumano
	 */
	public function setRecursoHumano($iRecHumano) {
	  $this->iRecHumano = $iRecHumano;
	}

	/**
	 * Retorna a forma de aprovacao
	 * @return integer
	 */
	public function getFormaAprovacao() {
		
	  switch ($this->iFormaAprovacao) {
	  	
	    case '2':
  			$iFormaAprovacao = AprovacaoConselho::RECLASSIFICACAO_BAIXA_FREQUENCIA;
	      break;
      case '3':
        $iFormaAprovacao = AprovacaoConselho::APROVADO_CONFORME_REGIMENTO_ESCOLAR;
        break;
      default:
        $iFormaAprovacao = AprovacaoConselho::APROVADO_CONSELHO;        
	  }
		
	  return $iFormaAprovacao;
	}

	/**
	 * Seta a forma de aprovacao
	 * @param integer $iFormaAprovacao
	 */
	public function setFormaAprovacao($iFormaAprovacao) {
	  $this->iFormaAprovacao = $iFormaAprovacao;
	}
	
	/**
	 * Retorna uma instancia de UsuarioSistema
	 * @return UsuarioSistema
	 */
	public function getUsuario() {
		return $this->oUsuario;
	}
	
	/**
	 * Seta uma instancia de UsuarioSistema
	 * @param UsuarioSistema $oUsuario
	 */
	public function setUsuario(UsuarioSistema $oUsuario) {
		$this->oUsuario = $oUsuario;
	}
	
	/**
	 * Retorna o timestamp da data
	 * @return DBDate
	 */
	public function getData() {
		return $this->oData;
	}
	
	/**
	 * Seta a data que foi realizado a aprovação pelo conselho
	 * @param DBDate
	 */
	public function setData(DBDate $oData) {
		$this->oData = $oData;
	}
	
	/**
	 * retorna a hora que foi realizado a aprovação pelo conselho
	 * @return string H:i
	 */
	public function getHora() {
	  
	  return $this->sHora;
	}
	
	/**
	 * Define a hora em que os Dados foram salvos
	 * @param string $sHora hora que foi salvo a aprovação pelo conselho formado hh:mm
	 */
	public function setHora($sHora) {
	  $this->sHora = $sHora;
	}
	/**
	 * Retorna uma instancia de AvaliacaoResultadoFinal
	 * @return AvaliacaoResultadoFinal
	 */
	public function getAvaliacaoResultadoFinal() {
		return $this->oAvaliacaoResultadoFinal;
	}
	
	/**
	 * Seta uma instancia de AvaliacaoResultadoFinal
	 * @param AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal
	 */
	public function setAvaliacaoResultadoFinal(AvaliacaoResultadoFinal $oAvaliacaoResultadoFinal) {
		$this->oAvaliacaoResultadoFinal = $oAvaliacaoResultadoFinal;
	}
	
	/**
	 * Salvamos os dados da aprovacao pelo conselho. Método chamado em $this->salvar() onde sao persistidas todas as
	 * informacoes referentes ao resultado final
	 * @throws DBException
	 */
	public function salvar() {
		
		$oDaoAprovConselho   = new cl_aprovconselho();
		$sWhereAprovConselho = "ed253_i_diario = {$this->oAvaliacaoResultadoFinal->getCodigoDiario()}";
		$sSqlAprovConselho   = $oDaoAprovConselho->sql_query_file(null, "ed253_i_codigo", null, $sWhereAprovConselho);
		$rsAprovConselho     = $oDaoAprovConselho->sql_record($sSqlAprovConselho);
		 
		$oDaoAprovConselho->ed253_i_diario = $this->oAvaliacaoResultadoFinal->getCodigoDiario();
		
		$oDaoAprovConselho->ed253_i_rechumano = '';
		if ($this->iRecHumano != null) {
			$oDaoAprovConselho->ed253_i_rechumano = $this->iRecHumano;
		}
		
		$aHora = explode(":", $this->sHora);
		
		$oDaoAprovConselho->ed253_i_usuario         = $this->oUsuario->getIdUsuario();
		$oDaoAprovConselho->ed253_t_obs             = $this->sJustificativa;
		$oDaoAprovConselho->ed253_i_data            = mktime($aHora[0], $aHora[1], 0, $this->oData->getMes(), 
		                                                     $this->oData->getDia(), $this->oData->getAno());
		$oDaoAprovConselho->ed253_aprovconselhotipo = $this->iFormaAprovacao;
		 
		if ($oDaoAprovConselho->numrows > 0) {
	
			$iCodigoAprovConselho              = db_utils::fieldsMemory($rsAprovConselho, 0)->ed253_i_codigo;
			$oDaoAprovConselho->ed253_i_codigo = $iCodigoAprovConselho;
			$oDaoAprovConselho->alterar($oDaoAprovConselho);
		} else {
			$oDaoAprovConselho->incluir(null);
		}
		 
		if ( $oDaoAprovConselho->erro_status == "0" ) {
			throw new DBException($oDaoAprovConselho->erro_msg);
		}
		 
		$this->oAvaliacaoResultadoFinal->setResultadoFinal('A');
		$this->oAvaliacaoResultadoFinal->salvar();
	}
	
	/**
	 * Remove a aprovacao pelo conselho
	 * @throws DBException
	 */
	public function remover() {
		 
		$oDaoAprovConselho   = new cl_aprovconselho();
		$sWhereAprovConselho = "ed253_i_diario = {$this->oAvaliacaoResultadoFinal->getCodigoDiario()}";
		$oDaoAprovConselho->excluir(null, $sWhereAprovConselho);
		 
		if ( $oDaoAprovConselho->erro_status == "0" ) {
			throw new DBException($oDaoAprovConselho->erro_msg);
		}
		 
		$sResultadoFinal = 'R';
		if ($this->oAvaliacaoResultadoFinal->getResultadoFrequencia() == 'A' && 
				$this->oAvaliacaoResultadoFinal->getResultadoAprovacao() == 'A') {
			$sResultadoFinal = 'A';
		}
		
		$this->oAvaliacaoResultadoFinal->setResultadoFinal($sResultadoFinal);
		$this->oAvaliacaoResultadoFinal->salvar();
	}
	
	/**
	 * Retorna a descrição do tipo de aprovação realizado pelo conselho
	 * @param integer $iCodigoTipoAprovacao
	 * @return string
	 */
	public static function getDescricaoTipoAprovacao($iCodigoTipoAprovacao) {
	
	  if (count(self::$aTiposAprovacao) == 0) {
	
	    $oDaoAprovConselho = new cl_aprovconselhotipo();
	    $rsAprovConselho   = $oDaoAprovConselho->sql_record($oDaoAprovConselho->sql_query_file());
	    $iLinhas           = $oDaoAprovConselho->numrows;
	     
	    if ($iLinhas == 0) {
	      throw new DBException(URL_APROVCONSELHO."base_desconfigurada_sem_tipo");
	    }
	    for ($i = 0; $i < $iLinhas; $i++) {
	
	      $oDados = db_utils::fieldsMemory($rsAprovConselho, $i);
	      self::$aTiposAprovacao[$oDados->ed122_sequencial] = $oDados->ed122_descricao;
	    }
	  }
	  return self::$aTiposAprovacao[$iCodigoTipoAprovacao];
	}
}