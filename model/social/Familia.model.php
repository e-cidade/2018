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


/**
 * Classe com os dados de uma Familia
 * @author Andrio Costa
 * @package social
 * @version $Revision: 1.22 $
 */
class Familia {
  
  /**
   * codigo sequencial de cidadaofamilia
   * @var integer
   */
  private $iCodigoSequencial;
  
  /**
   * Composicao familiar
   * @var array
   */
  private $aFamiliares = array();
  
  /**
   * Avaliacao da Familia
   * @var Avaliacao
   */
  private $oAvaliacao;
  
  /**
   * Array de Visitas
   * @var array
   */
  private $aVisitas = array();  
  
  /**
   * codifo da familia do cadastro unico
   * @var integer
   */
  private $iCodigoFamiliarCadastroUnico;
  
  /**
   * Codigo das repostas da avalicao respindidas pela Familia
   */
  protected $iCodigoGrupoRespostas;
  
  /**
   * data da entrevista
   * @var string
   */
  private $dtEntrevista;
  
  /**
   * Codigo de lancamento da avaliacao familiar;
   * @var integer
   */
  protected $iCodigoLancamentoAvaliacao;

  /**
   * Array dos benefícios da família
   * @var array
   */
  protected $aBeneficiosFamilia = array();
  
  /*
   * valor da Renda Familiar
   */
  protected $nRendaFamiliar;
  
  /**
   * Data de Atualizacao dos dados da familia
   * @var DBDate
   */
  protected $oDataAtualizacao;
  
  /**
   * Identifica se algum membro da familia utiliza aparelho ligado a rede eletrica continuamente
   * @var boolean
   */
  protected $lAparelhoRedeEletricaContinuo = false;
  
  /**
   * Código da avalidao da Familia
   * @var integer
   */
  const CODIGO_AVALICAO = 3000003;
  
  /**
   * Instancia de LocalAtendimentoFamilia
   * @var LocalAtendimentoFamilia
   */
  protected $oLocalAtendimentoFamilia;
  
  public function __construct($iCodigoSequencial = null) {
    
    if (!empty($iCodigoSequencial)) {
      
      $oDaoFamilia = new cl_cidadaofamilia();
      $sSqlFamilia = $oDaoFamilia->sql_query_familiarcadastrounico($iCodigoSequencial);
      $rsFamilia   = $oDaoFamilia->sql_record($sSqlFamilia);
      
      if ($oDaoFamilia->numrows > 0) {
        
        $oFamilia = db_utils::fieldsMemory($rsFamilia, 0);
        
        $this->iCodigoSequencial             = $iCodigoSequencial;
        $this->iCodigoFamiliarCadastroUnico  = $oFamilia->as15_codigofamiliarcadastrounico;
        $this->dtEntrevista                  = db_formatar($oFamilia->as04_dataentrevista, 'd');
        $this->nRendaFamiliar                = $oFamilia->as04_rendafamiliar;
        $this->lAparelhoRedeEletricaContinuo = $oFamilia->as04_aparelhoeletricocontinuo;
      }
    }
  }
  
  /**
   * Retorna os familiares da familia
   * @return Cidadao
   */
  public function getComposicaoFamiliar() {

    if (count($this->aFamiliares) == 0 && $this->getCodigoSequencial() != "") {
      
      $oDaoComposicaoFamiliar = db_utils::getDao('cidadaocomposicaofamiliar');
      $sWhere                 = "as03_cidadaofamilia = {$this->iCodigoSequencial}"; 
      $sSqlComposicaoFamiliar = $oDaoComposicaoFamiliar->sql_query_tipo_cidadao(null, "as03_cidadao, 
                                                                                       as02_sequencial,
                                                                                       as03_tipofamiliar, 
      																																								 z14_descricao", 
                                                                                       null, $sWhere);
      $rsComposicaoFamiliar   = $oDaoComposicaoFamiliar->sql_record($sSqlComposicaoFamiliar);
      $iTotalLinhas           = $oDaoComposicaoFamiliar->numrows;
      
      if ($iTotalLinhas > 0) {
        
        for ($i = 0; $i < $iTotalLinhas; $i++) {
          
          $oDadosComposicaoFamiliar = db_utils::fieldsMemory($rsComposicaoFamiliar, $i);
          if ($oDadosComposicaoFamiliar->as02_sequencial != "") {
            $oCidadao = new CadastroUnico($oDadosComposicaoFamiliar->as02_sequencial);
          } else {
            $oCidadao = new Cidadao($oDadosComposicaoFamiliar->as03_cidadao);
          }
          $oCidadao->setCodigoTipoFamilia($oDadosComposicaoFamiliar->as03_tipofamiliar);
          $oCidadao->setTipoFamilia($oDadosComposicaoFamiliar->z14_descricao);
          
          $this->aFamiliares[] = $oCidadao;
          
        }
      }
    }
    return $this->aFamiliares;
  }
  
  /**
   * Verifica se é uma familia do cadastro unico
   * @return boolean
   */
  public function isCadastroUnico() {
    
    if (!empty($this->iCodigoFamiliarCadastroUnico)) {
      return true;
    }
    return false;
  }
  
  /**
   * Retorna a avaliacao realizada pela Familia
   * @return Avaliacao
   */
  public function getAvaliacao() {
    
    if (empty($this->oAvaliacao)) {

      $this->buildAvaliacao(); 
      $this->atualizarAvaliacaoFamilia();
    }
    return $this->oAvaliacao;
  }
  /**
   * Retorna a avaliacao realizada pela Familia
   * @return Avaliacao
   */
  protected function buildAvaliacao() {
  
    $oDaoAvaliacao     = db_utils::getDao('cidadaofamiliaavaliacao');
    if (empty($this->oAvaliacao) && !empty($this->iCodigoSequencial)) {
      
      $sWhere            = "as06_cidadaofamilia = {$this->iCodigoSequencial}";
      $sSqlDadosAvalicao = $oDaoAvaliacao->sql_query_file(null, "*", null, $sWhere);
      $rsAvaliacao       = $oDaoAvaliacao->sql_record($sSqlDadosAvalicao);
      $this->oAvaliacao  = new Avaliacao(Familia::CODIGO_AVALICAO);
      if ($oDaoAvaliacao->numrows > 0) {

        $oDadosLancamentoAvalicao         = db_utils::fieldsMemory($rsAvaliacao, 0);
        $this->iCodigoGrupoRespostas      = $oDadosLancamentoAvalicao->as06_avaliacaogruporesposta;
        $this->iCodigoLancamentoAvaliacao = $oDadosLancamentoAvalicao->as06_sequencial;
        $this->oAvaliacao->setAvaliacaoGrupo($this->iCodigoGrupoRespostas);
        
      }
    }
     
    if (empty($this->iCodigoGrupoRespostas)) {
      
      $this->oAvaliacao            = new Avaliacao(Familia::CODIGO_AVALICAO);
      $this->iCodigoGrupoRespostas = $this->oAvaliacao->setAvaliacaoGrupo()->getAvaliacaoGrupo();
      $this->oAvaliacao->setAvaliacaoGrupo($this->iCodigoGrupoRespostas);
      
      $oDaoAvalicaoFamilia                              = db_utils::getDao("cidadaofamiliaavaliacao");
      $oDaoAvalicaoFamilia->as06_avaliacaogruporesposta = $this->getCodigoGrupoResposta();
      $oDaoAvalicaoFamilia->as06_cidadaofamilia         = $this->getCodigoSequencial();
      $oDaoAvalicaoFamilia->incluir(null);
      if ($oDaoAvalicaoFamilia->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados da avaliacao da familia.\n{$oDaoAvalicaoFamilia->erro_msg}");
      }
      $this->iCodigoLancamentoAvaliacao = $oDaoAvalicaoFamilia->as06_sequencial; 
    }
    return $this->oAvaliacao;
  }
  
  /**
   * Persiste os dados da Familia
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação Ativa.');
    }
    
    $oDaoFamilia = db_utils::getDao('cidadaofamilia');
    $oDaoFamilia->as04_dataentrevista              = "";
    if (!empty($this->oDataAtualizacao)) {
      $oDaoFamilia->as04_dataatualizacao = $this->oDataAtualizacao->convertTo(DBDate::DATA_EN);
    }
    $oDaoFamilia->as04_rendafamiliar = "{$this->getRendaPerCapita()}";
    if (!empty($this->dtEntrevista)) {
      $oDaoFamilia->as04_dataentrevista = implode("-", array_reverse(explode("/", $this->dtEntrevista))) ;
    }

    $oDaoFamilia->as04_aparelhoeletricocontinuo = "false";
    if (!empty($this->lAparelhoRedeEletricaContinuo)) {
      $oDaoFamilia->as04_aparelhoeletricocontinuo = $this->lAparelhoRedeEletricaContinuo;
    }
    
    if (!empty($this->iCodigoSequencial)) {
      
      $oDaoFamilia->as04_sequencial = $this->iCodigoSequencial;
      $oDaoFamilia->alterar($this->iCodigoSequencial);
    } else {
      
      $oDaoFamilia->incluir(null);
      $this->iCodigoSequencial = $oDaoFamilia->as04_sequencial;
    }
      
    /**
     * Lançamos exessao caso de erro na inclusao de: cidadaofamilia
     */
    if ($oDaoFamilia->erro_status == 0) {
    
      $sMsgErro  = "Erro ao salvar dados da Família.";
      $sMsgErro .= "\n\nErro técnico: {$oDaoFamilia->erro_msg}";
      throw new BusinessException($sMsgErro);
    }
    
    /**
     * Caso a família tenha setado o código do cadastro único da família, e 
     * a familia ainda não esta presente na tabela cidadaofamiliacadastrounico, devemos incluir/vincular a familia
     */
    if (!empty($this->iCodigoFamiliarCadastroUnico)) {
      
      $oDaoFamiliaCadUnico = new cl_cidadaofamiliacadastrounico();
      $sWhere              = "as15_cidadaofamilia = {$oDaoFamilia->as04_sequencial}";
      $sSqlFamiliaCadUnico = $oDaoFamiliaCadUnico->sql_query_file(null, "1", null, $sWhere);
      $rsFamiliaCadUnico   = $oDaoFamiliaCadUnico->sql_record($sSqlFamiliaCadUnico);
      
      if ($oDaoFamiliaCadUnico->numrows == 0) {
        
        $oDaoFamiliaCadUnico->as15_cidadaofamilia              = $oDaoFamilia->as04_sequencial;
        $oDaoFamiliaCadUnico->as15_codigofamiliarcadastrounico = $this->iCodigoFamiliarCadastroUnico;
        $oDaoFamiliaCadUnico->as15_sequencial                  = null;
        $oDaoFamiliaCadUnico->incluir(null);
        
        if ($oDaoFamiliaCadUnico->erro_status == 0) {
          
          $sMsgErro  = "Erro ao excluir vincular o código da família do cadastro único.";
          $sMsgErro .= "\n\nErro técnico: {$oDaoFamiliaCadUnico->erro_msg}";
          throw new BusinessException($sMsgErro);
        }
      }
    }


    /**
     * Excluimos os membros da Familia caso exista
     */
    $oDaoComposicaoFamiliar = db_utils::getDao('cidadaocomposicaofamiliar');
    $sWhereExcluiMembros    = " as03_cidadaofamilia = {$this->iCodigoSequencial}";
    $oDaoComposicaoFamiliar->excluir(null, $sWhereExcluiMembros);
    if ($oDaoComposicaoFamiliar->erro_status == 0) {
      
      $sMsgErro  = "Erro ao excluir membros da Família.";
      $sMsgErro .= "\n\nErro técnico: {$oDaoComposicaoFamiliar->erro_msg}";
      throw new BusinessException($sMsgErro);
    }
    /**
     * Incluimos membros na familia
     */
    foreach ($this->getComposicaoFamiliar() as $oCidadao) {
      
      $oDaoComposicaoFamiliar->as03_sequencial     = null;
      $oDaoComposicaoFamiliar->as03_cidadao        = $oCidadao->getCodigo();
      $oDaoComposicaoFamiliar->as03_tipofamiliar   = "{$oCidadao->getCodigoTipoFamilia()}";
      $oDaoComposicaoFamiliar->as03_cidadao_seq    = $oCidadao->getSequencialInterno();
      $oDaoComposicaoFamiliar->as03_cidadaofamilia = $this->iCodigoSequencial;
      $oDaoComposicaoFamiliar->incluir(null);
      
      /**
       * Lançamos exessao caso de erro na inclusao dos membros familiares de: cidadaocomposicaofamiliar
       */
      if ($oDaoComposicaoFamiliar->erro_status == 0) {
      
        $sMsgErro  = "Erro ao salvar dados da Familia.";
        $sMsgErro .= "\n\nErro técnico: {$oDaoComposicaoFamiliar->erro_msg}";
        throw new BusinessException($sMsgErro);
      }
    }
    
    /**
     * Salvamos os dados das visitas 
     */
    foreach ($this->aVisitas as $oVisita) {
      $oVisita->salvar($this->iCodigoSequencial);
    }
    
    /**
     * Salvamos os dados da avaliacao
     */
    
    return true;
  }
  
  /**
   * Retorna o cidadao responsavel pela familia
   * @return CadastroUnico || Cidadao
   */
  public function getResponsavel () {
    
    
    foreach ($this->getComposicaoFamiliar() as $oCidadao) {
      
      if ($oCidadao->getCodigoTipoFamilia() == 0) {
        return $oCidadao;
      }      
    }
  }
  
  /**
   * retorna o código sequencial de cidadaofamilia
   */
  public function getCodigoSequencial() {
    
    return $this->iCodigoSequencial;
  }  
  
  /**
   * Adiciona uma visita ao array de visitas
   * @param FamiliaVisita $oVisitaiar
   */
  public function adicionarVisita (FamiliaVisita $oVisita) {
    $this->aVisitas[] = $oVisita; 
  }
  
  /**
   * Adiciona um Cidadao a Familia
   * @param Cidadao $oCidadao
   */
  public function adicionarCidadao(Cidadao $oCidadao) {
    
    $this->getComposicaoFamiliar();
    $lCidadaoJaEstaNaFamilia = false;
    foreach ($this->aFamiliares as $iIndice => $oCidadaoLancado) {
      
      if ($oCidadaoLancado->getCodigo() == $oCidadao->getCodigo()) {
        
        $lCidadaoJaEstaNaFamilia     = true;
        if ($oCidadao->getCodigo() != "") {
          $this->aFamiliares[$iIndice] = $oCidadao;
        }
        break;
      }
    }
    if (!$lCidadaoJaEstaNaFamilia || $oCidadao->getCodigo() == "") {
      $this->aFamiliares[] = $oCidadao;
    }
  }
  
  /**
   * retorna uma colecao de FamiliaVisita
   * @return array
   */
  public function getVisitas() {
    
  	if (count($this->aVisitas) == 0 && $this->getCodigoSequencial() != "") {

  		$oDaoFamiliaVisita = db_utils::getDao('cidadaofamiliavisita');
  		$sWhere            = " as05_cidadaofamilia = ".$this->getCodigoSequencial();
  		$sSqlFamiliaVisita = $oDaoFamiliaVisita->sql_query_file(null, "as05_sequencial", null, $sWhere);
  		$rsFamiliaVisita   = $oDaoFamiliaVisita->sql_record($sSqlFamiliaVisita);
  		$iTotalLinhas      = $oDaoFamiliaVisita->numrows;  
  		
  		if ($iTotalLinhas > 0) {
  			
  			for ($i = 0; $i < $iTotalLinhas; $i++) {
  				
  				$this->aVisitas[] = new FamiliaVisita(db_utils::fieldsMemory($rsFamiliaVisita, $i)->as05_sequencial);
  			}
  		}
  	}
    return $this->aVisitas;
  }
  
  /**
   * seta o codigo da Familia
   * @param integer $iCodigoFamiliarCadastroUnico
   */
  public function setCodigoFamiliarCadastroUnico ($iCodigoFamiliarCadastroUnico) {
    
    $this->iCodigoFamiliarCadastroUnico = $iCodigoFamiliarCadastroUnico;
  }
  /**
   * Retorna o codigo da Familia
   * @return integer
   */
  public function getCodigoFamiliarCadastroUnico() {
    
    return $this->iCodigoFamiliarCadastroUnico;
  }
  
  /**
   * seta a data da emtrevista
   * formato d-m-Y
   * @param string $dtEntrevista
   */
  public function setDataEntrevista($dtEntrevista) {
    
    $this->dtEntrevista = $dtEntrevista;
  }
  
  /**
   * Retorna a data de entrevista
   * formato d-m-Y 
   * @return string
   */
  public function getDataEntrevista() {
    return $this->dtEntrevista;
  }
  
  /**
   * seta a data da ultima atualizacao dos dados
   * formato d-m-Y
   * @param DBDate $dtEntrevista;
   */
  public function setDataAtualizacao(DBDate $dtAtualizacao) {
    $this->oDataAtualizacao = $dtAtualizacao;
  }
  
  /**
   * Retorna a data da ultima atualizacao
   * formato d-m-Y 
   * @return DBDate
   */
  public function getDataAtualizacao() {
    return $this->oDataAtualizacao;
  }
  /**
   * Adiciona uma avalicao para a familia
   * @return Avalicao
   */
  public function adicionarAvaliacao () {
    
    $oAvaliacao = $this->getAvaliacao();
    if ($oAvaliacao->getAvaliacaoGrupo() == "") {
      
      $oAvaliacao                  = new Avaliacao(Familia::CODIGO_AVALICAO);
      $this->iCodigoGrupoRespostas = $oAvaliacao->setAvaliacaoGrupo()->getAvaliacaoGrupo();
      $this->oAvaliacao            = $oAvaliacao;
    }
    return $this->oAvaliacao;
  }
  
  /**
   * Retorna a renda per capita 
   * @return float
   */
  public function getRendaPerCapita() {
  	return $this->nRendaFamiliar;
  }
  
  /**
   * Retorna a renda per capita 
   * @return float
   */
  public function setRendaPerCapita($nRendaPerCapita) {
    $this->nRendaFamiliar = $nRendaPerCapita;
  }
  
  /**
   * Retorna uma lista dos benefícios da família
   * @return array
   */
  public function getListaBeneficios() {
    
    foreach ($this->getComposicaoFamiliar() as $oFamiliares) {

      if (count($oFamiliares->getBeneficios()) > 0) {
      
        foreach ($oFamiliares->getBeneficios() as $oBeneficios) {
        
          $oDadosBeneficio = new stdClass();
          $sBeneficio      = str_replace(" ", "", $oBeneficios->getTipoBeneficio());
          
          if (array_key_exists($sBeneficio, $this->aBeneficiosFamilia)) {
            $this->aBeneficiosFamilia[$sBeneficio]->quantidade++;
          } else {
            
            $oDadosBeneficio->beneficio            = $oBeneficios->getTipoBeneficio();
            $oDadosBeneficio->situacao             = $oBeneficios->getSituacao();
            $oDadosBeneficio->quantidade           = 1;
            $this->aBeneficiosFamilia[$sBeneficio] = $oDadosBeneficio;
          }
        }
      }
    }
    return $this->aBeneficiosFamilia;
  }
  
  /**
   * Atualiza os dados da Familia
   */
  protected function atualizarAvaliacaoFamilia() {
    
    $oImportacaoCadastroUnico = new ImportacaoCadastroUnico(null);
    $oImportacaoCadastroUnico->atualizarFamilia($this);
  }
  
  /**
   * Retorna o código do grupo de respostas
   * @return integer
   */
  public function getCodigoGrupoResposta() {
    return $this->iCodigoGrupoRespostas;
  }
  
  /**
   * Retorna o codigo de lancamento da avaliacao
   * @return integer
   */
  public function getCodigoLancamentoAvaliacao() {
    return $this->iCodigoLancamentoAvaliacao;
  }
  
  /**
   * Retorna se algum membro da familia utiliza aparelho ligado a rede eletrica continuamente
   * @return boolean 
   */
  public function getAparelhoRedeEletricaContinuo() {
    return $this->lAparelhoRedeEletricaContinuo;
  }
  
  /**
   * Setamos se algum membro da familia utiliza aparelho ligado a rede eletrica continuamente
   */
  public function setAparelhoRedeEletricaContinuo($lAparelhoRedeEletricaContinuo) {
    $this->lAparelhoRedeEletricaContinuo = $lAparelhoRedeEletricaContinuo;
  }
  
  /**
   * Verifica se a família está inscrita no cadastro único e se possui renda mensal
   * de até $iQuantidadeSalario salaríos mínimos
   * @param numeric $iQuantidadeSalario
   * @throws ParameterException
   * @return booleam
   */
  public function validaRendaMensalAte($iQuantidadeSalario) {
  
    if ($this->getRendaPerCapita() <= 0) {
      return true;
    }
  
    /**
     * Validamos se o parametro esta setado
     */
    if ($iQuantidadeSalario <= 0) {
      throw new ParameterException("Número de salários <= a 0.");
    }
  
    /**
     * Buscamos o valor do salario minimo
     */
    $oDaoPesDiver = db_utils::getDao('pesdiver');
  
    $sWhere       = "     pesdiver.r07_codigo = 'D912' ";
    $sWhere      .= " and pesdiver.r07_instit =  " . db_getsession("DB_instit");
    $sOrder       = " r07_anousu desc, r07_mesusu desc limit 1";
  
    $sSqlSalarioMinimo = $oDaoPesDiver->sql_query_file(null, null, null, null, "r07_valor", $sOrder, $sWhere);
    $rsSalarioMinimo   = $oDaoPesDiver->sql_record($sSqlSalarioMinimo);
  
    if ($oDaoPesDiver->numrows == 0) {
      throw new BusinessException("Não foi possível localizar o valor do salário minimo.");
    }
  
    /**
     * Verificamos se o salario da familia e menor do que a quantidade estipulada pelo parametro
     */
    $nSalarioMinimo = db_utils::fieldsMemory($rsSalarioMinimo, 0)->r07_valor;
    if ($this->getRendaPerCapita() > ($nSalarioMinimo * $iQuantidadeSalario)) {
      return false;
    }
    return true;
  }
  
  /**
   * Verificamos se um membro da familia recebe o:
   * Benefício de Prestacao Continua - BCP Deficiente
   * @throws BusinessException
   * @return booleam
   */
  public function recebePrestacaoContinuadaDeficiente() {
  
    /**
     * validamos se a familia pertence ao cadastro unico
     */
    if (!$this->isCadastroUnico()) {
      throw new BusinessException("Família não pertence ao Cadastro Unico.");
    }
  
    /**
     * Verifica se a pergunta 2.05 do Formulário F1.01 esta com a opcao de resposta:
     * 3000469 - 1 - Benefício de Prestacao Continua - BCP Deficiente esta selecionada
     */
    return $this->getAvaliacao()->verificaSeRespostaEstaMarcada('RecebeAssistenciaProgramaSocial', 3000469);

  }
  
  /**
   * Verificamos se um membro da familia recebe o:
   * Benefício de Prestacao Continua - BCP Idoso
   * @throws BusinessException
   * @return booleam
   */
  public function recebePrestacaoContinuadaIdoso() {
  
    /**
     * validamos se a familia pertence ao cadastro unico
     */
    if (!$this->isCadastroUnico()) {
      throw new BusinessException("Família não pertence ao Cadastro Unico.");
    }
  
    /**
     * Verifica se a pergunta 2.05 do Formulário F1.01 esta com a opcao de resposta:
     * 3000470 - 2 - Benefício de Prestacao Continua - BCP Idoso esta selecionada
     */
    return $this->getAvaliacao()->verificaSeRespostaEstaMarcada('RecebeAssistenciaProgramaSocial', 3000470);
    
  }
  
  /**
   * Verificamos se a familia é de descendencia quilombola e possui renda de ate 1/2 salario minimo
   * Pergunta: 3000064 - 3.05) - A família é quilombola?
   * Formulario F1 - Familia
   * @throws BusinessException
   * @return boolean
   */
  public function familiaQuilombola() {
  
    /**
     * validamos se a familia pertence ao cadastro unico
     */
    if (!$this->isCadastroUnico()) {
      throw new BusinessException("Família não pertence ao Cadastro Unico.");
    }
  
    /**
     * Validamos a renda mensal
     */
    if (!$this->validaRendaMensalAte(0.5)) {
      return false;
    }
  
    /**
     * Verifica se a pergunta 3.05 do Formulário F1 - Familia esta com a opcao de resposta:
     * 3000234 - 1) Sim            selecionada
     */
    return $this->getAvaliacao()->verificaSeRespostaEstaMarcada('FamiliaQuilombola', 3000234);
    
  }
  
  /**
   * Verificamos se a familia é de descendencia indigena e possui renda de ate 1/2 salario minimo
   * Pergunta: 3000060 - 3.01) - A família é indígena?
   * Formulario F1 - Familia
   * @throws BusinessException
   * @return boolean
   */
  public function familiaIndigena() {
  
    /**
     * validamos se a familia pertence ao cadastro unico
     */
    if (!$this->isCadastroUnico()) {
      throw new BusinessException("Família não pertence ao Cadastro Unico.");
    }
  
    /**
     * Validamos a renda mensal
     */
    if (!$this->validaRendaMensalAte(0.5)) {
      return false;
    }
  
    
    /**
     * Verifica se a pergunta 3.05 do Formulário F1 - Familia esta com a opcao de resposta:
     * 3000226 - 1) Sim   selecionada
     */
    return $this->getAvaliacao()->verificaSeRespostaEstaMarcada('FamiliaIndigena', 3000226);

  }
  
  /**
   * Retorna uma instancia de LocalAtendimentoFamilia, caso exista um vinculo ativo
   * @return LocalAtendimentoFamilia
   */
  public function getLocalAtendimentoAtual() {
    
    if ($this->getCodigoSequencial() != null) {
       
      $oDaoLocalAtendimentoFamilia    = new cl_localatendimentofamilia();
      $sWhereLocalAtendimentoFamilia  = "as23_cidadaofamilia = {$this->getCodigoSequencial()}";
      $sWhereLocalAtendimentoFamilia .= " and as23_ativo is true";
      $sSqlLocalAtendimentoFamilia    = $oDaoLocalAtendimentoFamilia->sql_query(
                                                                                 null,
                                                                                 "as23_sequencial",
                                                                                 null,
                                                                                 $sWhereLocalAtendimentoFamilia
                                                                               );
      $rsLocalAtendimentoFamilia = $oDaoLocalAtendimentoFamilia->sql_record($sSqlLocalAtendimentoFamilia);
      
      if ($oDaoLocalAtendimentoFamilia->numrows > 0) {
        
        $iLocalAtendimentoFamilia       = db_utils::fieldsMemory($rsLocalAtendimentoFamilia, 0)->as23_sequencial;
        $this->oLocalAtendimentoFamilia = new LocalAtendimentoFamilia($iLocalAtendimentoFamilia);
      }
    }
    
    return $this->oLocalAtendimentoFamilia;
  }
  
  /**
   * Retorna uma colecao com o historico de locais de atendimentos que a familia ja foi vinculada
   * @return array
   */
  public function getHistoricoAtendimentos() {

    $aAtendimentos = array();
    if ($this->getCodigoSequencial() != null) {
       
      $oDaoLocalAtendimentoFamilia    = new cl_localatendimentofamilia();
      $sWhereLocalAtendimentoFamilia  = "as23_cidadaofamilia = {$this->getCodigoSequencial()}";
      $sSqlLocalAtendimentoFamilia    = $oDaoLocalAtendimentoFamilia->sql_query(
                                                                                 null,
                                                                                 "as23_sequencial",
                                                                                 "as23_sequencial",
                                                                                 $sWhereLocalAtendimentoFamilia
                                                                               );
      $rsLocalAtendimentoFamilia     = $oDaoLocalAtendimentoFamilia->sql_record($sSqlLocalAtendimentoFamilia);
      $iTotalLocalAtendimentoFamilia = $oDaoLocalAtendimentoFamilia->numrows;
      
      if ($iTotalLocalAtendimentoFamilia > 0) {
        
        for ($iContador = 0; $iContador < $iTotalLocalAtendimentoFamilia; $iContador++) {
          
          $iLocalAtendimentoFamilia = db_utils::fieldsMemory($rsLocalAtendimentoFamilia, $iContador)->as23_sequencial;
          $oLocalAtendimentoFamilia = new LocalAtendimentoFamilia($iLocalAtendimentoFamilia);
          $aAtendimentos[]          = $oLocalAtendimentoFamilia;
        }
      }
    }
    
    return $aAtendimentos;
  }
}