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

require_once 'model/pessoal/calculoatuarial/cnm/ArquivoCalculoAtuarialCNM.model.php';
require_once 'model/pessoal/calculoatuarial/cnm/InformacaoCalculoAtuarialAtivos.model.php';
require_once 'model/pessoal/calculoatuarial/cnm/InformacaoCalculoAtuarialInativos.model.php';
require_once 'model/pessoal/calculoatuarial/cnm/InformacaoCalculoAtuarialPensionistas.model.php';

/**
 * Calculo Atuarial
 * 
 * @author Alberto <alberto@dbseller.com.br>
 */
class CalculoAtuarialCNM {
	
	/**
	 * Mes da folha
	 * @var integer
	 */
	private $iMesFolha;
	
	/**
	 * iAnoFolha
	 */
	private $iAnoFolha;	

  /**
   * Assentamentos escolhidos na tela
   * 
   * @var array
   * @access private
   */
	private $aAssentamentos = array();
	
  /**
   * Codigos dos cargos dos professores
   * 
   * @var array
   * @access private
   */
	private $aCargosProfessores = array();
	
  /**
   * TIpos de arquivos
   * 
   * @var array
   * @access private
   */
	private $aTiposArquivos = array();
	
  /**
   * Instituicoes com servidores inativos
   * 
   * @var array
   * @access private
   */
	private $aInstituicoesInativos = array();
	
	/**
	 * Instituicoes com servidores ativos
	 *
	 * @var array
	 * @access private
	 */
	private $aInstituicoesAtivos = array();
	
  /**
   * Tipos de arquivos a serem gerados, um para cada tipo de vinculo do servidor
   * 
   * @var array
   * @access private
   */
	private $aArquivosGeracao = array();

  private $aTiposDependentes = array(Dependente::IRF_FILHOS_ATE_21,
                                     Dependente::IRF_FILHO_ENTEADO_ATE_24_ENSINO_SUPERIOR,
                                     Dependente::IRF_ABSOLUTAMENTE_INCAPAZ); 

  public function processar() {

    $oDataAtual = new DBDate(date('Y-m-d'), db_getsession('DB_datausu'));

    foreach ( $this->aTiposArquivos as $iTipoArquivo ) {

      switch ( $iTipoArquivo ) {

        /**
         * ATIVOS 
         */
        case VinculoServidor::ATIVO :

          $oArquivo          = new ArquivoCalculoAtuarialCNM(ArquivoCalculoAtuarialCNM::ATIVOS);          
          $sInstituicoes     = implode(',', $this->aInstituicoesAtivos);          
          $aServidoresAtivos = ServidorRepository::getServidoresPorVinculo($this->iAnoFolha, $this->iMesFolha, VinculoServidor::ATIVO, $sInstituicoes);
          sort($aServidoresAtivos);
          
          for ( $iIndice = 0; $iIndice < count($aServidoresAtivos); $iIndice++ ) {

            $oServidor = $aServidoresAtivos[$iIndice];

            if ( $oServidor->getTipoRegime() != "1" ) { // != Estatutario
              continue;
            }
 
            if ( $oServidor->getVinculo()->getTipo() != 'A' ) { //Ativo
              continue;
            }
 
						$oInformacoes = new InformacaoCalculoAtuarialAtivos();						

            if ( $oServidor->getTipoExposicaoAgentesNocivos() == '' ) {
              $oInformacoes->setPericulosidadeInsalubridade(3);
            } else {
              $oInformacoes->setPericulosidadeInsalubridade(1);
            }

            $iTempoServico  = DBDate::calculaIntervaloEntreDatas(new DBDate(date('Y-m-d'), db_getsession('DB_datausu')), $oServidor->getDataAdmissao(), 'y');
						$iTempoAverbado = self::getTempoAverbado($oServidor, $this->aAssentamentos);
            
            $oInformacoes->setTempoServicoEnteEstatal($iTempoServico);
            $oInformacoes->setTempocontribuicaoFundo ($iTempoServico + $iTempoAverbado);
            $oInformacoes->setTempoServicoAnterior   ($iTempoAverbado);

            $iCodigoProfessor = 0;

            if ( in_array($oServidor->getCodigoCargo(), $this->aCargosProfessores) ) {
              $iCodigoProfessor = 1;
            }

            $oInformacoes->setCodigoProfessor($iCodigoProfessor);

            /**
             * Verifica se existe registros na rhpessoalmov para a competencia de admissão, 
             * se não existir seta a primeira data disponível.
             */
            $oDaoRhPessoalMov = new cl_rhpessoalmov();
            $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_query(null, $oServidor->getCodigoInstituicao(), '*', 'rh02_anousu, rh02_mesusu', "rh02_regist = {$oServidor->getMatricula()}");
            $rsRhPessoalMov   = db_query($sSqlRhPessoalMov);
            
            $oRhPessoalMovNaAdmissao = db_utils::fieldsMemory($rsRhPessoalMov, 0);
            
            $oServidorNaAdmissao = ServidorRepository::getInstanciaByCodigo($oServidor->getMatricula(), 
            																														    $oRhPessoalMovNaAdmissao->rh02_anousu, 			
            																																$oRhPessoalMovNaAdmissao->rh02_mesusu);

            $oCalculoSalario     = new CalculoFolhaSalario($oServidorNaAdmissao);
            $aRemuneracao        = $oCalculoSalario->getEventosFinanceiros(null, 'R992');
            
            $nRemuneracao      = 0;
            
            if (count($aRemuneracao) > 0) {
	            foreach ($aRemuneracao as $oRemuneracao) {
	              $nRemuneracao += $oRemuneracao->getValor();
	            }
            } else {
            	
            	$nRemuneracao = $oServidor->getValorVariaveisCalculo($this->iAnoFolha, 
            																											 $this->iMesFolha, 
            			                                                 $oServidor->getMatricula(), 	
            																										   db_getsession('DB_instit'), 
            																											 Servidor::VARIAVEL_SALARIO_BASE_PROGRESSAO);
            }
            
            $aRemuneracaoFinal = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, 'R992');
            $nRemuneracaoFinal = 0;
            foreach ($aRemuneracaoFinal as $oRemuneracaoFinal) {
              $nRemuneracaoFinal += $oRemuneracaoFinal->getValor();
            }

            $oInformacoes->setRemuneracao( $nRemuneracao ) ;
            $oInformacoes->setRemuneracaoFinal( $nRemuneracaoFinal ) ; 

            $aIdadeFilhos           = array();
            $iQuantidadeDependentes = 0;
            
            foreach ( $oServidor->getDependentes() as $oDependente ) {

              if ( in_array( $oDependente->getTipo(), $this->aTiposDependentes ) ) {

                $iIdadeDependente = null;
                if ($oDependente->getDataNascimento() instanceof DBDate) {
                  $iIdadeDependente = DBDate::calculaIntervaloEntreDatas($oDataAtual, $oDependente->getDataNascimento(), 'y');
              	}
                
                if ( $oDependente->getTipo() == Dependente::IRF_FILHOS_ATE_21 && $iIdadeDependente > 21) {
                  continue;
                }
                
                if ( $oDependente->getTipo() == Dependente::IRF_FILHO_ENTEADO_ATE_24_ENSINO_SUPERIOR && $iIdadeDependente > 24) {
                  continue;
                }

                if (is_null($iIdadeDependente) ) {
                  continue;
                }
              	$aIdadeFilhos[] = $iIdadeDependente;
                $iQuantidadeDependentes++;
              }

              if ( $oDependente->getTipo() == Dependente::IRF_CONJUGE_COMPANHEIRO ) {

                $oInformacoes->setIdadeConjuge( DBDate::calculaIntervaloEntreDatas($oDataAtual, $oDependente->getDataNascimento(), 'y') ) ;
                $iQuantidadeDependentes++;
                
              }
              
            }

            /**
             * Percorre os Filhos Setando as Idades
             */
            foreach ( $aIdadeFilhos as $iIndiceFilho => $iIdade ) {

              
              $oInformacoes->setIdadeFilho( $iIndiceFilho,  $iIdade ) ;
            }

            $oInformacoes->setMatricula            ( $oServidor->getMatricula() ) ;
            $oInformacoes->setIdade                ( $oServidor->getIdade() ) ;
            $oInformacoes->setSexo                 ( $oServidor->getSexo() == 'M' ? 1 : 2) ;
            $oInformacoes->setTempoServicoAnterior ( self::getTempoAverbado($oServidor, $this->aAssentamentos) );
            $oInformacoes->setQuantidadeDependentes( $iQuantidadeDependentes ) ;
            $oInformacoes->setTipoVinculacaoEstatal( $oServidor->getInstituicao()->getTipo() ) ;
            $oInformacoes->setTipoServidor($oServidor->getTipoRegime()) ;

            $oArquivo->lancarRegistro( $oInformacoes );


          } // endforeach
            
          $this->aArquivosGeracao[] = $oArquivo;

        break;		

        case VinculoServidor::TEMPO_CONTRIBUICAO :
	      case VinculoServidor::IDADE              :
	      case VinculoServidor::INVALIDEZ          :
	      case VinculoServidor::COMPULSORIA        :
        case VinculoServidor::PENSIONISTA        :

          $oArquivo            = new ArquivoCalculoAtuarialCNM($iTipoArquivo);
          $sInstituicoes       = implode(',', $this->aInstituicoesInativos);

          $aServidoresInativos = ServidorRepository::getServidoresPorVinculo($this->iAnoFolha, $this->iMesFolha, $iTipoArquivo, $sInstituicoes);
          
          foreach ( $aServidoresInativos as $oServidor ) {

            if ( $oServidor->getTipoRegime() != "1" ) {
              continue;
            }

            if ( $iTipoArquivo == VinculoServidor::PENSIONISTA ) {

              if ( $oServidor->getVinculo()->getTipo() != 'P' ) {
                continue;
              }
              $oInformacoes = new InformacaoCalculoAtuarialPensionistas();						
              
              $oInformacoes->setDataNascimentoRecebedor($oServidor->getDataNascimento());
              if ($oServidorOrigem = $oServidor->getServidorOrigem()) {
              	$oInformacoes->setDataNascimentoInstituidor($oServidor->getServidorOrigem()->getDataNascimento());
              }

            } else {

              if ( $oServidor->getVinculo()->getTipo() != 'I' ) {
                continue;
              }
              $oInformacoes = new InformacaoCalculoAtuarialInativos();						
              $oInformacoes->setTipoInativo         ($iTipoArquivo);
              $oInformacoes->setDataNascimento      ($oServidor->getDataNascimento());
            }

            $oInformacoes->setMatricula           ($oServidor->getMatricula());
            if ( $oServidor->getCgm() instanceof CgmJuridico ) {
              $oInformacoes->setCpf                 ( $oServidor->getCgm()->getCnpj() );
            }  else {
              $oInformacoes->setCpf                 ( $oServidor->getCgm()->getCpf() );
            }
            $oInformacoes->setSexo                ($oServidor->getSexo() == 'M' ? 1 : 2);
            $oInformacoes->setDataInicioBeneficio ($oServidor->getDataAdmissao());
            
            $nRemuneracao      = 0;

            $aRemuneracao      = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, 'R981');
            foreach ($aRemuneracao as $oRemuneracao) {
            	$nRemuneracao += $oRemuneracao->getValor();
            }

            /**                                
             * Caso não possua nenhum valor da R981, pesquisar na R975
             */
            if ($nRemuneracao == 0) {
	            $aRemuneracao      = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, 'R975');
	            foreach ($aRemuneracao as $oRemuneracao) {
	            	$nRemuneracao += $oRemuneracao->getValor();
	            }
            }
            
            /**
             * Caso não possua nenhum valor da R975, pesquisar na R997
             */
            if ($nRemuneracao == 0) {
            	$aRemuneracao      = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, 'R997');
            	foreach ($aRemuneracao as $oRemuneracao) {
            		$nRemuneracao += $oRemuneracao->getValor();
            	}
            }

            $oInformacoes->setRemuneracao ($nRemuneracao);

            $iQuantidadeDependentes = 0;
            $aIdadeFilhos           = array();

            foreach ( $oServidor->getDependentes() as $oDependente ) {

              if ( in_array( $oDependente->getTipo(), $this->aTiposDependentes ) ) {

                $aIdadeFilhos[] = DBDate::calculaIntervaloEntreDatas($oDataAtual, $oDependente->getDataNascimento(), 'y');
                $iQuantidadeDependentes++;
              }

              if ( $oDependente->getTipo() == Dependente::IRF_CONJUGE_COMPANHEIRO ) {

                $oInformacoes->setDataNascimentoConjuge ($oDependente->getDataNascimento());
                $iQuantidadeDependentes++;
              }
            }

            /**
             * Percorre os Filhos Setando as Idades
             */
            foreach ( $aIdadeFilhos as $iIndiceFilho => $iIdade ) {
              $oInformacoes->setIdadeFilho( $iIndiceFilho + 1,  $iIdade ) ;
            }

            $oInformacoes->setNumeroDependentes ($iQuantidadeDependentes);

            $oArquivo->lancarRegistro( $oInformacoes );

          } 

					$this->aArquivosGeracao[] = $oArquivo;
				
        break;

      } // endswitch	

    } // endforeach
    
    return $this->gerarArquivos();
  }
	
	public function gerarArquivos() {
		
		$aCaminhosArquivosGerados = array();
		
		foreach ( $this->aArquivosGeracao as $oArquivo ) {
			
			$sArquivoGerado = $oArquivo->processar();
			
			if ( empty($sArquivoGerado) ) {
				continue;
			}
			
			$aCaminhosArquivosGerados[] = $sArquivoGerado;
		}
		
		if ( empty($aCaminhosArquivosGerados) ) {
			throw new Exception('Não foi encontrado nennhum registro para os filtros informados.');
		}
		
		return $aCaminhosArquivosGerados;
	}
	
	public function setAnoFolha ($iAnoFolha) {
		$this->iAnoFolha = $iAnoFolha;
	}
	
	public function setMesFolha ($iMesFolha) {
		$this->iMesFolha = $iMesFolha;
	}

	public function setInstituicoesAtivos (array $aCodigoInstituicao) {
		$this->aInstituicoesAtivos = $aCodigoInstituicao;
	}
	
	public function setInstituicoesInativos (array $aCodigoInstituicao) {
		$this->aInstituicoesInativos = $aCodigoInstituicao;
	}
	
	public function setCargosProfessores (array $aCargosProfessores) {
		$this->aCargosProfessores = $aCargosProfessores;
	}
	
	public function setTiposArquivos (array $aTiposArquivos) {
		$this->aTiposArquivos = $aTiposArquivos;
	}
	
	public function setAssentamentos($aAssentamentos) {
		$this->aAssentamentos = $aAssentamentos;
	}
	
	public function getAssentamentos() {
		return $this->aAssentamentos;
	}
	
  public static function getTempoAverbado(Servidor $oServidor, $aAssentamentos) {
    
    if ( empty($aAssentamentos) ) {
      return 0;
    }  

    $sAssentamentos   = "('" . implode("','", $aAssentamentos) . "')";
    $oDaoAssentamento = db_utils::getDao('assenta');
    $sWhere           = "h16_regist = {$oServidor->getMatricula()} and h16_assent in {$sAssentamentos}";
   
    $sSqlAssentamento = $oDaoAssentamento->sql_query_file(null, 'coalesce(sum( extract(year from age(h16_dtterm, h16_dtconc)) ), 0) as termo_averbado', null, $sWhere);
    $rsAssentamento   = $oDaoAssentamento->sql_record($sSqlAssentamento);

    if ($oDaoAssentamento->numrows == 0) {
      return 0;
    }

    return db_utils::fieldsMemory($rsAssentamento, 0)->termo_averbado;

  }
}