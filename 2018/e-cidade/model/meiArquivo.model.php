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

/**
 * Model referente a rotina do MEI ( Micro Empreendedor Individual )
 *
 * @package issqn
 * @author Felipe Nunes Ribeiro
 * @revision $Author: dblucas.dumer $
 * @version $Revision: 1.33 $
 */
class MeiArquivo {

  /**
   * Constante do código da categoria MEI no cadastro do simples nacional
   */
  const SIMPLES_NACIONAL_MEI = 3;

  /**
   * Constante do código do motivo de baixa: OFICIO
   */
  const MOTIVO_BAIXA_OFICIO  = 1;

	/**
	 * Array contendo os dados do MEI agrupados da seguite forma :
	 *
	 * $aDadosArquivo['CNPJ_MEI']['sRecibo']                                 = $sRecibo;      -- Recibo de Solicitação
   * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['aAtividades'][] = $oAtividade;   -- Array com todas Atividades
   * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oEmpresa']      = $oEmpresa;     -- Dados da Empresa
   * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oResponsavel']  = $oResponsavel; -- Dados do Responsável
   * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oContador']     = $oContador;    -- Dados do Contador ou Escritório Contábil
	 *
	 * @var array
	 */
	private $aDadosMEI     = array();

	/**
	 * Cláusula where com filtro dos dados a serem exibidos
	 *
	 * @var string
	 */
	private $sWhereImporta = '';

	/**
	 * Caso seja passado por parâmetro a cláusula where no método construtor a propriedade $aDadosMEI
	 * receberá somente o valor dos dados de importação do MEI apartir do filtro
	 *
	 * @param string $sWhereImporta
	 */
  function __construct( $sWhereImporta='' ) {

  	$sMsgErro = '';

  	if ( trim($sWhereImporta) != '' ) {

	    $oMeiImporta                  = db_utils::getDao('meiimporta');
	    $oMeiImportaMeiRegEmpresa     = db_utils::getDao('meiimportameiregempresa');
	    $oMeiImportaMeiRegContador    = db_utils::getDao('meiimportameiregcontador');
	    $oMeiImportaMeiRegAtividade   = db_utils::getDao('meiimportameiregatividade');
	    $oMeiImportaMeiRegResponsavel = db_utils::getDao('meiimportameiregresponsavel');

	    /**
	     * Consulta todos os dados apartir da cláusula where informada
	     */
		  $sSqlImporta    = $oMeiImporta->sql_query_reg(null,"*",'q111_data',$sWhereImporta);
		  $rsDadosImporta = $oMeiImporta->sql_record($sSqlImporta);

		  if ( !$rsDadosImporta ) {
		  	throw new Exception("{$sMsgErro}\n".pg_last_error());
		  }

		  /**
		   *  Atribui a cláusula ao parâmetro $sWhereImporta para ser utilizada por outros métodos
		   */
		  $this->sWhereImporta = $sWhereImporta;

		  $aDadosImporta = db_utils::getCollectionByRecord($rsDadosImporta);

		  foreach ( $aDadosImporta as $oDadosImporta ) {

		    try {

          $this->setDadosEvento($oDadosImporta->q105_cnpj,
                                $oDadosImporta->q101_codigo,
                                $oDadosImporta->q111_sequencial,
                                $oDadosImporta->q111_data);

          $this->addRecibo($oDadosImporta->q105_cnpj,$oDadosImporta->q105_recibosolicitacao);

        } catch (Exception $eException){
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
        }

		  	/**
		  	 *  Caso tenha registro na tabela meiimportareg referente a empresa será adicionado uma empresa
		  	 *  com o método addEmpresa
		  	 */
        if ( trim($oDadosImporta->q111_meiimportameiregempresa) != '' ) {

		      $sSqlEmpresa     = $oMeiImportaMeiRegEmpresa->sql_query_file($oDadosImporta->q111_meiimportameiregempresa);
		      $rsDadosEmpresa  = $oMeiImportaMeiRegEmpresa->sql_record($sSqlEmpresa);
		      $oDadosEmpresa   = db_utils::fieldsMemory($rsDadosEmpresa,0);

				  try {
				    $this->addEmpresa($oDadosImporta->q105_cnpj,$oDadosImporta->q101_codigo,$oDadosEmpresa);
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
          }

        /**
         *  Caso tenha registro na tabela meiimportareg referente ao responsável será adicionado um responsável
         *  com o método addResponsavel
         */
        } else if (trim($oDadosImporta->q111_meiimportameiregresponsavel) != '') {

          $sSqlResponsavel     = $oMeiImportaMeiRegResponsavel->sql_query_file($oDadosImporta->q111_meiimportameiregresponsavel);
          $rsDadosResponsavel  = $oMeiImportaMeiRegResponsavel->sql_record($sSqlResponsavel);
          $oDadosResponsavel   = db_utils::fieldsMemory($rsDadosResponsavel,0);

          try {
            $this->addResponsavel($oDadosImporta->q105_cnpj,$oDadosImporta->q101_codigo,$oDadosResponsavel);
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
          }


        /**
         *  Caso tenha registro na tabela meiimportareg referente ao contador será adicionado um contador
         *  com o método addContador
         */
       	} else if (trim($oDadosImporta->q111_meiimportameiregcontador) != '') {

          $sSqlContador     = $oMeiImportaMeiRegContador->sql_query_file($oDadosImporta->q111_meiimportameiregcontador);
          $rsDadosContador  = $oMeiImportaMeiRegContador->sql_record($sSqlContador);
          $oDadosContador   = db_utils::fieldsMemory($rsDadosContador,0);

          try {
            $this->addContador($oDadosImporta->q105_cnpj,$oDadosImporta->q101_codigo,$oDadosContador);
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
          }


        /**
         *  Caso tenha registro na tabela meiimportareg referente a atividade será adicionado uma atividade
         *  com o método addAtividade
         */
     		} else if (trim($oDadosImporta->q111_meiimportameiregatividade) != '') {

          $sSqlAtividade     = $oMeiImportaMeiRegAtividade->sql_query_file($oDadosImporta->q111_meiimportameiregatividade);
          $rsDadosAtividade  = $oMeiImportaMeiRegAtividade->sql_record($sSqlAtividade);
          $oDadosAtividade   = db_utils::fieldsMemory($rsDadosAtividade,0);

          try {
            $this->addAtividade($oDadosImporta->q105_cnpj,$oDadosImporta->q101_codigo,$oDadosAtividade);
          } catch (Exception $eException){
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
          }

        }

		  }

  	}

  }

  /**
   * Método de importação do Aquivo txt MEI
   *
   * @param string $sNomeArquivo
   * @param string $sCaminhoArquivo
   */
  function importaArquivo( $sNomeArquivo='', $sCaminhoArquivo='' ) {

  	$sMsgErro = "Importação de Arquivo MEI abortada!\n";

  	$aCodigoValidos = MeiArquivo::getEventosPermitidos();

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( trim($sNomeArquivo) == '' ) {
  	  throw new Exception("{$sMsgErro}Nome do arquivo não informado!");
    }

    if ( trim($sCaminhoArquivo) == '' ) {
      throw new Exception("{$sMsgErro}Caminho do arquivo não informado!");
    }

    $oDaoCnae                     = db_utils::getDao('cnae');
    $oDBConfig                    = db_utils::getDao('db_config');
    $oMeiEvento                   = db_utils::getDao('meievento');
    $oMeiImporta                  = db_utils::getDao('meiimporta');
    $oMeiImportaMei               = db_utils::getDao('meiimportamei');
    $oMunicipioSIAFI              = db_utils::getDao('municipiosiafi');
    $oMeiImportaMeiReg            = db_utils::getDao('meiimportameireg');
    $oMeiImportaMeiRegEmpresa     = db_utils::getDao('meiimportameiregempresa');
    $oMeiImportaMeiRegContador    = db_utils::getDao('meiimportameiregcontador');
    $oMeiImportaMeiRegAtividade   = db_utils::getDao('meiimportameiregatividade');
    $oMeiImportaMeiRegResponsavel = db_utils::getDao('meiimportameiregresponsavel');


    /**
     *  Consulta do código SIAFI do município
     */
    $rsSIAFI = $oDBConfig->sql_record($oDBConfig->sql_query_siafi(db_getsession('DB_instit'),'q110_codigo'));

    if ( $oDBConfig->numrows > 0 ) {
      $sCodSIAFI = db_utils::fieldsMemory($rsSIAFI,0)->q110_codigo;
    } else {
      throw new Exception("Erro: Código SIAFI não encontrado!");
    }

    /**
     *  Classe que transforma o arquivo em um array de objeto apartir do cadastro de layout
     */
    try {

	    $oDBLayoutReader = new DBLayoutReader(84,$sCaminhoArquivo);
	    $aLinhasArquivo  = $oDBLayoutReader->getLines();
    } catch ( Exception $eException ) {
      throw new Exception("{$sMsgErro}{$eException->getMessage()}");
    }


    /**
     *
     * Estrutura de dados do Array, que agrupa as informações do arquivo por CNPJ do MEI e Eventos
     *
     * $aDadosArquivo['CNPJ_MEI']['sRecibo']                                 = $sRecibo;      -- Recibo de Solicitação
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['aAtividades'][] = $oAtividade;   -- Array com todas Atividades
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oEmpresa']      = $oEmpresa;     -- Dados da Empresa
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oResponsavel']  = $oResponsavel; -- Dados do Responsável
     * $aDadosArquivo['CNPJ_MEI']['aEventos']['sCodEvento']['oContador']     = $oContador;    -- Dados do Contador ou Escritório Contábil
     *
     */

    $aDadosArquivo    = array();
    $aCodSIAFI        = array();
    $lValidaData      = true;
    $iNumeroRegistros = 0;
    foreach ( $aLinhasArquivo as $iIndLinha => $oLinha ) {

      if ( $oLinha->co_convenio != $sCodSIAFI ) {
      	$sMsgErro .= "Código do município do arquivo TXT inválido ( Linha: ".($iIndLinha+1)." ). Contate suporte!";
        throw new Exception($sMsgErro);
      }

      if ( $lValidaData ) {

		    $dtDataUsu    = date('Y-m-d', db_getsession('DB_datausu'));
		    $sDataArquivo = $oLinha->dt_evento1;

		    if ( strlen(trim($sDataArquivo)) != 8 ) {
		      $sMsgErro .= "Data inválida do arquivo!";
		      throw new Exception($sMsgErro);
		    }

		    $iAnoArquivo = substr($sDataArquivo,0,4);
		    $iMesArquivo = substr($sDataArquivo,4,2);

		    $sWhereImporta  = " q104_nomearq = '{$sNomeArquivo}'";
		    $sSqlImporta    = $oMeiImporta->sql_query_file(null,"*",null,$sWhereImporta);
		    $rsImporta      = $oMeiImporta->sql_record($sSqlImporta);

		    if ( $oMeiImporta->numrows > 0 ) {
		      throw new Exception("{$sMsgErro} Arquivo já importado!");
		    }

		    /**
		     *  Verifica se o período informado é maior que a data de implantação do MEI
		     */

		    try {
			    $dtDataImpMei = $this->getDataImpMEI();
		    } catch (Exception $eException) {
		    	throw new Exception($eException->getMessage());
		    }

	      list($iAnoDataImpMei,$iMesDataImpMei,$iDiaDataImpMei) = explode("-",$dtDataImpMei);

	      if ( $iAnoArquivo < $iAnoDataImpMei || ( $iMesArquivo < $iMesDataImpMei && $iAnoArquivo == $iAnoDataImpMei) ) {

	        $sMsgErro .= "Competência do arquivo menor que da implantação do MEI!";
	        throw new Exception($sMsgErro);
	      }

		    /**
		     *  Verifica se já existe lançamento sem movimento para a competência informada
		     */
        $sWhereImportaSemMov  = "     q104_anousu = {$iAnoArquivo} ";
        $sWhereImportaSemMov .= " and q104_mesusu = {$iMesArquivo} ";
        $sWhereImportaSemMov .= " and q104_tipoimporta = 2         ";
        $sSqlImportaSemMov    = $oMeiImporta->sql_query_file(null,"*",null,$sWhereImportaSemMov);
        $rsImportaSemMov      = $oMeiImporta->sql_record($sSqlImportaSemMov);

        if ( $oMeiImporta->numrows > 0 ) {
          throw new Exception("{$sMsgErro}Competência sem movimento já lançado!");
        }

		    $lValidaData = false;
      }


      /**
       *  Percorre os 8 eventos de cada linha
       */
      for ( $iIndEvent=1; $iIndEvent <= 8; $iIndEvent++ ) {


        $sCodEvento = $oLinha->{"co_evento".$iIndEvent};

      	/**
      	 *  Caso o convênio esteja em branco ou não esteja no array de códigos válidos,
      	 *  deve pular para o próximo registro,
      	 */
        if (trim($sCodEvento) == '' || !in_array($sCodEvento, $aCodigoValidos) ) {
          continue;
        } else {

        	/**
        	 *  Consulta o código do evento na tabela meievento
        	 */
        	$sWhereEvento     = "     q101_codigo = '{$sCodEvento}'                              ";
        	$sWhereEvento    .= " and case                                                       ";
          $sWhereEvento    .= "       when q101_dataini is not null then                       ";
          $sWhereEvento    .= "          case                                                  ";
          $sWhereEvento    .= "            when '{$dtDataUsu}'::date >= q101_dataini then true ";
          $sWhereEvento    .= "            else false                                          ";
          $sWhereEvento    .= "          end                                                   ";
          $sWhereEvento    .= "       else true                                                ";
          $sWhereEvento    .= "     end                                                        ";
          $sWhereEvento    .= " and case                                                       ";
          $sWhereEvento    .= "       when q101_datafin is not null then                       ";
          $sWhereEvento    .= "          case                                                  ";
          $sWhereEvento    .= "            when '{$dtDataUsu}'::date <= q101_datafin then true ";
          $sWhereEvento    .= "            else false                                          ";
          $sWhereEvento    .= "          end                                                   ";
          $sWhereEvento    .= "       else true                                                ";
          $sWhereEvento    .= "     end                                                        ";

        	$sSqlEvento       = $oMeiEvento->sql_query_file(null,"q101_sequencial",null,$sWhereEvento);

        	$rsConsultaEvento = $oMeiEvento->sql_record($sSqlEvento);

        	/**
        	 * Se não existir o código do evento, pula para o proximo evento.
        	 */
        	if ( !$rsConsultaEvento || pg_num_rows($rsConsultaEvento) == 0 ) {
        	  continue;
        	} else {
        		$iSeqEvento = db_utils::fieldsMemory($rsConsultaEvento,0)->q101_sequencial;
        	}

        	try {


        	  /**
             * Contabiliza se o item foi importado.
             */
        	  $iNumeroRegistros++;

	          $this->setDadosEvento($oLinha->nu_cnpj,
	                                $sCodEvento,
	                                $iSeqEvento,
	                                $oLinha->{"dt_evento".$iIndEvent},
	                                $oLinha->{"tp_evento".$iIndEvent});

	          $this->addRecibo($oLinha->nu_cnpj,$oLinha->nu_recibo_solicitacao);

          } catch (Exception $eException){
          	throw new Exception("{$sMsgErro}{$eException->getMessage()}");
          }

          /**
           *  Linha do arquivo referente aos dados cadastrais da Empresa
           */
          if ( $oLinha->co_tipo_registro == '01' ) {

          	/**
          	 *  Dados referente a empresa do MEI
          	 */
          	if ( trim($oLinha->nm_empresarial_01) != '' ||
          	     trim($oLinha->nm_logradouro) != '' ||
          	     ((int)$sCodEvento >= 500 && (int)$sCodEvento <= 599) ||
          	      in_array($sCodEvento,array('101','221'))
          	   ) {


          	  if ( $this->validaNumeroEndereco($oLinha->nu_logradouro)) {
		            $sNumeroEnd = $oLinha->nu_logradouro;
		            $sComplEnd  = $oLinha->nm_complemento_logradouro;
		          } else {
		            $sNumeroEnd = "0";
		            $sComplEnd  = $oLinha->nu_logradouro." ".$oLinha->nm_complemento_logradouro;
		          }

          	  if ( trim($oLinha->nu_capital_social) != '' ) {
                $sCapitalSocial = substr($oLinha->nu_capital_social,0,12).".".substr($oLinha->nu_capital_social,12,2);
              } else {
                $sCapitalSocial = '';
              }

          		$oMeiEmpresa = new stdClass();
	          	$oMeiEmpresa->q107_nome              = addslashes(substr($oLinha->nm_empresarial_01,0,40));
	          	$oMeiEmpresa->q107_cnpj              = addslashes($oLinha->nu_cnpj);
	          	$oMeiEmpresa->q107_cnpjmatriz        = addslashes($oLinha->nu_cnpj_estabelecimento_matriz);
	          	$oMeiEmpresa->q107_capitalsocial     = addslashes($sCapitalSocial);
	          	$oMeiEmpresa->q107_nomefantasia      = addslashes($oLinha->nm_fantasia);
	          	$oMeiEmpresa->q107_tipologradouro    = addslashes($oLinha->co_tipo_logradouro);
	          	$oMeiEmpresa->q107_logradouro        = addslashes(strtoupper($oLinha->nm_logradouro));
	          	$oMeiEmpresa->q107_numero            = addslashes($sNumeroEnd);
	          	$oMeiEmpresa->q107_complemento       = addslashes(substr($sComplEnd,0,20));
	          	$oMeiEmpresa->q107_bairro            = addslashes(substr(strtoupper($oLinha->nm_bairro),0,40));
	          	$oMeiEmpresa->q107_municipio         = addslashes((trim(strtoupper($oLinha->co_municipio)) != ''?$oLinha->co_municipio:0));
	          	$oMeiEmpresa->q107_uf                = addslashes($oLinha->nm_uf);
	          	$oMeiEmpresa->q107_cep               = addslashes($oLinha->nu_cep);
	          	$oMeiEmpresa->q107_referencia        = addslashes($oLinha->nm_referencia);
	          	$oMeiEmpresa->q107_telefone          = addslashes($oLinha->nu_ddd_telefone_1." ".$oLinha->nu_telefone_1);
	          	$oMeiEmpresa->q107_telefonecomercial = addslashes($oLinha->nu_ddd_telefone_2." ".$oLinha->nu_telefone_2);
	          	$oMeiEmpresa->q107_fax               = addslashes($oLinha->nu_ddd_fax." ".$oLinha->nu_fax);
	          	$oMeiEmpresa->q107_email             = addslashes(substr($oLinha->nm_correio_eletronico,0,100));
	          	$oMeiEmpresa->q107_caixapostal       = addslashes($oLinha->nu_caixa_postal);
	          	$oMeiEmpresa->q107_inscrmei          = addslashes($oLinha->in_inscricao_mei);

	          	try {
	          	  $this->addEmpresa($oLinha->nu_cnpj,$sCodEvento,$oMeiEmpresa);
          	  } catch (Exception $eException){
		            throw new Exception("{$sMsgErro}{$eException->getMessage()}");
		          }

	            $aCodSIAFI[] = $oLinha->co_municipio;

          	}

          	/**
          	 *  Dados referente ao responsável pela empresa do MEI
          	 */
            if ( trim($oLinha->nm_responsavel) != '' ) {

              if ( $this->validaNumeroEndereco($oLinha->nu_logradouro_responsavel)) {
                $sNumeroEnd = $oLinha->nu_logradouro_responsavel;
                $sComplEnd  = $oLinha->nm_complemento_logradouro_responsavel;
              } else {
                $sNumeroEnd = "0";
                $sComplEnd  = $oLinha->nu_logradouro_responsavel." ".$oLinha->nm_complemento_logradouro_responsavel;
              }

            	$oMeiResponsavel = new stdClass();
	          	$oMeiResponsavel->q108_nome           = addslashes(substr($oLinha->nm_responsavel,0,40));
	          	$oMeiResponsavel->q108_cpf            = addslashes($oLinha->nu_cpf_responsavel);
	            $oMeiResponsavel->q108_tipologradouro = addslashes($oLinha->co_tipo_logradouro_responsavel);
	            $oMeiResponsavel->q108_logradouro     = addslashes(strtoupper($oLinha->nm_logradouro_responsavel));
	            $oMeiResponsavel->q108_numero         = addslashes($sNumeroEnd);
	            $oMeiResponsavel->q108_complemento    = addslashes(substr($sComplEnd,0,20));
	            $oMeiResponsavel->q108_bairro         = addslashes(substr(strtoupper($oLinha->nm_bairro_responsavel),0,40));
	            $oMeiResponsavel->q108_municipio      = addslashes(strtoupper($oLinha->co_municipio_responsavel));
	            $oMeiResponsavel->q108_uf             = addslashes($oLinha->co_uf_responsavel);
	            $oMeiResponsavel->q108_cep            = addslashes($oLinha->nu_cep_responsavel);
	            $oMeiResponsavel->q108_telefone       = addslashes(substr($oLinha->nu_ddd_telefone_responsavel,2,2)." ".$oLinha->nu_telefone_responsavel);
	            $oMeiResponsavel->q108_fax            = addslashes($oLinha->nu_ddd_fax_responsavel." ".$oLinha->nu_fax_responsavel);
	            $oMeiResponsavel->q108_email          = addslashes(substr($oLinha->nm_correio_eletronico_responsavel,0,100));

	            try {
	              $this->addResponsavel($oLinha->nu_cnpj,$sCodEvento,$oMeiResponsavel);
              } catch (Exception $eException){
		            throw new Exception("{$sMsgErro}{$eException->getMessage()}");
		          }

	          	$aCodSIAFI[] = $oLinha->co_municipio_responsavel;

            }

          	/**
          	 *  Caso tenha no arquivo empresa contábil e contador deve ser gravado
          	 *  na tabela escrito apenas o cadastro do contador
          	 */
          	if ( trim($oLinha->nu_seq_contador_pf) != '' ) {

              if ( $this->validaNumeroEndereco($oLinha->nu_logradouro_contador_pf)) {
                $sNumeroEnd = $oLinha->nu_logradouro_contador_pf;
                $sComplEnd  = $oLinha->nm_complemento_logradouro_contador_pf;
              } else {
                $sNumeroEnd = "0";
                $sComplEnd  = $oLinha->nu_logradouro_contador_pf." ".$oLinha->nm_complemento_logradouro_contador_pf;
              }

          		$oMeiContador = new stdClass();
	            $oMeiContador->q109_ufcrc          = addslashes($oLinha->nm_uf_contador_pf);
	            $oMeiContador->q109_codcrc         = addslashes($oLinha->nu_seq_contador_pf);
	            $oMeiContador->q109_datacrc        = addslashes($oLinha->dt_registro_crc_contador_pf);
	            $oMeiContador->q109_cnpjcpf        = addslashes($oLinha->nu_cpf_contador_pf);
	            $oMeiContador->q109_nome           = addslashes(substr($oLinha->nm_contador_pf,0,40));
	            $oMeiContador->q109_tipologradouro = addslashes($oLinha->co_tipo_logradouro_contador_pf);
	            $oMeiContador->q109_logradouro     = addslashes(strtoupper($oLinha->nm_logradouro_contador_pf));
	            $oMeiContador->q109_numero         = addslashes($sNumeroEnd);
	            $oMeiContador->q109_complemento    = addslashes(substr($sComplEnd,0,20));
	            $oMeiContador->q109_bairro         = addslashes(substr(strtoupper($oLinha->nm_bairro_contador_pf),0,40));
	            $oMeiContador->q109_municipio      = addslashes(strtoupper($oLinha->co_municipio_contador_pf));
	            $oMeiContador->q109_uf             = addslashes($oLinha->nm_uf_contador_pf);
	            $oMeiContador->q109_cep            = addslashes($oLinha->nu_cep_contador_pf);
	            $oMeiContador->q109_telefone       = addslashes(substr($oLinha->nu_ddd_telefone_contador_pf,2,2)." ".$oLinha->nu_telefone_contador_pf);
	            $oMeiContador->q109_fax            = addslashes($oLinha->nu_ddd_fax_contador_pf." ".$oLinha->nu_fax_contador_pf);
	            $oMeiContador->q109_email          = addslashes(substr($oLinha->nm_correio_eletronico_contador_pf,0,100));

	            try {
	              $this->addContador($oLinha->nu_cnpj,$sCodEvento,$oMeiContador);
          	  } catch (Exception $eException){
		            throw new Exception("{$sMsgErro}{$eException->getMessage()}");
		          }

	            $aCodSIAFI[] = $oLinha->co_municipio_contador_pf;

          	} else if ( trim($oLinha->nu_seq_crc_empresa_contabil) != '' ) {

              if ( $this->validaNumeroEndereco($oLinha->nu_logradouro_contador_pf)) {
                $sNumeroEnd = $oLinha->nu_logradouro_contador_pf;
                $sComplEnd  = $oLinha->nm_complemento_logradouro_contador_pf;
              } else {
                $sNumeroEnd = "0";
                $sComplEnd  = $oLinha->nu_logradouro_contador_pf." ".$oLinha->nm_complemento_logradouro_contador_pf;
              }

          		$oMeiContador = new stdClass();
              $oMeiContador->q109_ufcrc          = addslashes($oLinha->nm_uf_crc_empresa_contabil);
              $oMeiContador->q109_codcrc         = addslashes($oLinha->nu_seq_crc_empresa_contabil);
              $oMeiContador->q109_datacrc        = addslashes($oLinha->dt_registro_crc_empresa_contabil);
              $oMeiContador->q109_cnpjcpf        = addslashes($oLinha->nu_cnpj_empresa_contabil);
              $oMeiContador->q109_nome           = addslashes(substr($oLinha->nm_empresa_contabil,0,40));
              $oMeiContador->q109_tipologradouro = addslashes($oLinha->co_tipo_logradouro_empresa_contabil_complementar);
              $oMeiContador->q109_logradouro     = addslashes(strtoupper($oLinha->nm_logradouro_empresa_contabil_complementar));
              $oMeiContador->q109_numero         = addslashes($sNumeroEnd);
              $oMeiContador->q109_complemento    = addslashes(substr($sComplEnd,0,20));
              $oMeiContador->q109_bairro         = addslashes(substr(strtoupper($oLinha->nm_bairro_empresa_contabil_complementar),0,40));
              $oMeiContador->q109_municipio      = addslashes(strtoupper($oLinha->co_municipio_empresa_contabil_complementar));
              $oMeiContador->q109_uf             = addslashes($oLinha->co_uf_empresa_contabil_complementar);
              $oMeiContador->q109_cep            = addslashes($oLinha->nu_cep_empresa_contabil_complementar);
              $oMeiContador->q109_telefone       = addslashes($oLinha->nu_ddd_telefone_empresa_contabil_complementar." ".$oLinha->nu_telefone_empresa_contabil_complementar);

              try {
                $this->addContador($oLinha->nu_cnpj,$sCodEvento,$oMeiContador);
   	          } catch (Exception $eException){
		            throw new Exception("{$sMsgErro}{$eException->getMessage()}");
		          }

              $aCodSIAFI[] = $oLinha->co_municipio_empresa_contabil_complementar;

          	}


          /**
           *  Linha do arquivo referente as Atividades da Empresa
           */
		      } else if ( $oLinha->co_tipo_registro == '04' ) {

		        for ( $iIndAtiv=1; $iIndAtiv <= 99; $iIndAtiv++ ) {

		          /*
		           *  No arquivo a atividade primária fica em local diferente das atividades secundárias
		           */

		          if ( $iIndAtiv == 1 ) {
		            $sCnae      = $oLinha->co_cnae_fiscal;
		            $lPrincipal = 'true';
		          } else {
		            $sCnae      = $oLinha->{"co_cnae_fiscal_secundaria".($iIndAtiv-1)};
		            $lPrincipal = 'false';
		          }

		          if ( trim($sCnae) == '' ) {
		            continue;
		          }

              $sWhereDescrCnae = " q71_estrutural like '%{$sCnae}'";
              $rsDescrCnae     = $oDaoCnae->sql_record($oDaoCnae->sql_query_file(null,"q71_descr",null,$sWhereDescrCnae));

              if ( $oDaoCnae->numrows > 0 ) {
                $oCnae           = db_utils::fieldsMemory($rsDescrCnae,0);
                $sDescrAtividade = substr($oCnae->q71_descr,0,70);
              } else {
                throw new Exception("{$sMsgErro}CNAE:{$sCnae} não cadastrado!");
              }

		          $oMeiAtividade = new stdClass();
		          $oMeiAtividade->q106_cnae      = addslashes($sCnae);
		          $oMeiAtividade->q106_descricao = addslashes($sDescrAtividade);
		          $oMeiAtividade->q106_principal = $lPrincipal;

		          try {
			          $this->addAtividade($oLinha->nu_cnpj,$sCodEvento,$oMeiAtividade);
		          } catch (Exception $eException){
		            throw new Exception("{$sMsgErro}{$eException->getMessage()}");
		          }

		        }

		      } else {
	          $sMsgErro .= "Tipo de linha do arquivo difere do padrão, contate o suporte!( Linha: ".($iIndLinha+1)." )";
	          throw new Exception($sMsgErro);
		      }
        }
      }

    }


    /**
     *  Cria um array contendo todas as descrições dos municipios SIAFI
     *  utilizado no arquivo txt, pois nele é apenas informado o código
     */
    $aDescrSIAFI = array();

    if ( count($aCodSIAFI) > 0 ) {

    	$sListaSIAFI = implode("','",array_unique($aCodSIAFI));

    	$sCamposSIAFI     = " q110_codigo,   ";
    	$sCamposSIAFI    .= " q110_descricao ";
    	$sWhereSIAFI      = " q110_codigo in ('{$sListaSIAFI}')";

    	$sSqlSIAFI        = $oMunicipioSIAFI->sql_query_file(null,$sCamposSIAFI,"q110_codigo",$sWhereSIAFI);
    	$rsMunicipioSIAFI = $oMunicipioSIAFI->sql_record($sSqlSIAFI);
    	$iLinhasSIAFI     = pg_num_rows($rsMunicipioSIAFI);

    	$aDescrSIAFI['0'] = '';

    	for ( $iIndSIAFI=0; $iIndSIAFI < $iLinhasSIAFI; $iIndSIAFI++ ) {

    		$oSIAFI = db_utils::fieldsMemory($rsMunicipioSIAFI,$iIndSIAFI);
    		$aDescrSIAFI[$oSIAFI->q110_codigo] = $oSIAFI->q110_descricao;

    	}

    }

    /**
     *  Gera OID para gravação do arquivo na base
     */
    $oidGrava       = pg_lo_create();
    $sStringArquivo = file_get_contents($sCaminhoArquivo);

    if ( !$sStringArquivo ) {
      throw new Exception("{$sMsgErro}Falha ao abrir o arquivo [{$sCaminhoArquivo}].") ;
    }

    $oLargeObject = pg_lo_open($oidGrava, "w");

    if (!$oLargeObject) {
      throw new Exception("{$sMsgErro}Falha ao buscar objeto do banco de dados") ;
    }

    $lObjetoEscrito = pg_lo_write($oLargeObject,$sStringArquivo);

    if (!$lObjetoEscrito) {
   	  throw new Exception("{$sMsgErro}Falha na escrita do objedo no banco de dados") ;
    }

    pg_lo_close($oLargeObject);


    $oMeiImporta->q104_anousu      = $iAnoArquivo;
    $oMeiImporta->q104_mesusu      = $iMesArquivo;
    $oMeiImporta->q104_id_usuario  = db_getsession('DB_id_usuario');
    $oMeiImporta->q104_arquivo     = $oidGrava;
    $oMeiImporta->q104_nomearq     = $sNomeArquivo;
    $oMeiImporta->q104_xml         = '';
    $oMeiImporta->q104_cancelado   = 'false';
    $oMeiImporta->q104_tipoimporta = 1;

    $oMeiImporta->incluir(null);

    if ($oMeiImporta->erro_status == 0){
      throw new Exception("{$sMsgErro}{$oMeiImporta->erro_msg}") ;
    }

    /**
     *  Insere nas tabelas filhas apartir do array $aDadosArquivo
     */

    foreach ( $this->aDadosMEI as $iCnpj => $oDadosEmpresa ) {

    	$oMeiImportaMei->q105_meiimporta        = $oMeiImporta->q104_sequencial;
    	$oMeiImportaMei->q105_cnpj              = $iCnpj;
    	$oMeiImportaMei->q105_recibosolicitacao = $oDadosEmpresa['sRecibo'];

      $oMeiImportaMei->incluir(null);

	    if ($oMeiImportaMei->erro_status == 0){
	      throw new Exception("{$sMsgErro}{$oMeiImportaMei->erro_msg}") ;
	    }

    	foreach ( $oDadosEmpresa['aEventos'] as $sCodEvento => $aDadosEvento ) {


    		if ( isset($aDadosEvento['oEmpresa']) ){

    			$oEmpresa = $aDadosEvento['oEmpresa'];

				  $oMeiImportaMeiRegEmpresa->q107_municipio         = $aDescrSIAFI[$oEmpresa->q107_municipio];
				  $oMeiImportaMeiRegEmpresa->q107_cnpj              = $oEmpresa->q107_cnpj;
				  $oMeiImportaMeiRegEmpresa->q107_cnpjmatriz        = $oEmpresa->q107_cnpjmatriz;
				  $oMeiImportaMeiRegEmpresa->q107_nome              = $oEmpresa->q107_nome;
				  $oMeiImportaMeiRegEmpresa->q107_capitalsocial     = $oEmpresa->q107_capitalsocial;
				  $oMeiImportaMeiRegEmpresa->q107_nomefantasia      = $oEmpresa->q107_nomefantasia;
				  $oMeiImportaMeiRegEmpresa->q107_tipologradouro    = $oEmpresa->q107_tipologradouro;
				  $oMeiImportaMeiRegEmpresa->q107_logradouro        = $oEmpresa->q107_logradouro;
				  $oMeiImportaMeiRegEmpresa->q107_numero            = $oEmpresa->q107_numero;
				  $oMeiImportaMeiRegEmpresa->q107_complemento       = $oEmpresa->q107_complemento;
				  $oMeiImportaMeiRegEmpresa->q107_bairro            = $oEmpresa->q107_bairro;
				  $oMeiImportaMeiRegEmpresa->q107_uf                = $oEmpresa->q107_uf;
				  $oMeiImportaMeiRegEmpresa->q107_cep               = $oEmpresa->q107_cep;
				  $oMeiImportaMeiRegEmpresa->q107_referencia        = $oEmpresa->q107_referencia;
				  $oMeiImportaMeiRegEmpresa->q107_telefone          = $oEmpresa->q107_telefone;
				  $oMeiImportaMeiRegEmpresa->q107_telefonecomercial = $oEmpresa->q107_telefonecomercial;
				  $oMeiImportaMeiRegEmpresa->q107_fax               = $oEmpresa->q107_fax;
				  $oMeiImportaMeiRegEmpresa->q107_email             = $oEmpresa->q107_email;
				  $oMeiImportaMeiRegEmpresa->q107_caixapostal       = $oEmpresa->q107_caixapostal;

				  if ( trim($oEmpresa->q107_inscrmei) == 'S' ) {
				    $oMeiImportaMeiRegEmpresa->q107_inscrmei = 'true';
				  } else {
				  	$oMeiImportaMeiRegEmpresa->q107_inscrmei = 'false';
				  }


 		      $oMeiImportaMeiRegEmpresa->incluir(null);

		      if ($oMeiImportaMeiRegEmpresa->erro_status == 0){
		        throw new Exception("{$sMsgErro}{$oMeiImportaMeiRegEmpresa->erro_msg}") ;
		      }

		      $oMeiImportaMeiReg->q111_meiimportameiregempresa     = $oMeiImportaMeiRegEmpresa->q107_sequencial;
		      $oMeiImportaMeiReg->q111_meiimportameiregresponsavel = '';
		      $oMeiImportaMeiReg->q111_meiimportameiregatividade   = '';
		      $oMeiImportaMeiReg->q111_meiimportameiregcontador    = '';
		      $oMeiImportaMeiReg->q111_meiimportamei               = $oMeiImportaMei->q105_sequencial;
		      $oMeiImportaMeiReg->q111_meievento                   = $aDadosEvento['oEvento']->iSeqEvento;
		      $oMeiImportaMeiReg->q111_data                        = $aDadosEvento['oEvento']->dtData;
		      $oMeiImportaMeiReg->incluir(null);

    		  if ($oMeiImportaMeiReg->erro_status == 0){
            throw new Exception("{$sMsgErro}{$oMeiImportaMeiReg->erro_msg}") ;
          }

    		}

        if ( isset($aDadosEvento['oResponsavel']) ){

        	$oResponsavel = $aDadosEvento['oResponsavel'];

          $oMeiImportaMeiRegResponsavel->q108_municipio       = $aDescrSIAFI[$oResponsavel->q108_municipio];
          $oMeiImportaMeiRegResponsavel->q108_cpf             = $oResponsavel->q108_cpf;
          $oMeiImportaMeiRegResponsavel->q108_nome            = $oResponsavel->q108_nome;
          $oMeiImportaMeiRegResponsavel->q108_tipologradouro  = $oResponsavel->q108_tipologradouro;
          $oMeiImportaMeiRegResponsavel->q108_logradouro      = $oResponsavel->q108_logradouro;
          $oMeiImportaMeiRegResponsavel->q108_numero          = $oResponsavel->q108_numero;
          $oMeiImportaMeiRegResponsavel->q108_complemento     = $oResponsavel->q108_complemento;
          $oMeiImportaMeiRegResponsavel->q108_bairro          = $oResponsavel->q108_bairro;
          $oMeiImportaMeiRegResponsavel->q108_uf              = $oResponsavel->q108_uf;
          $oMeiImportaMeiRegResponsavel->q108_cep             = $oResponsavel->q108_cep;
          $oMeiImportaMeiRegResponsavel->q108_telefone        = $oResponsavel->q108_telefone;
          $oMeiImportaMeiRegResponsavel->q108_fax             = $oResponsavel->q108_fax;
          $oMeiImportaMeiRegResponsavel->q108_email           = $oResponsavel->q108_email;

          $oMeiImportaMeiRegResponsavel->incluir(null);

          if ($oMeiImportaMeiRegResponsavel->erro_status == 0){
            throw new Exception("{$sMsgErro}{$oMeiImportaMeiRegResponsavel->erro_msg}") ;
          }

          $oMeiImportaMeiReg->q111_meiimportameiregresponsavel = $oMeiImportaMeiRegResponsavel->q108_sequencial;
          $oMeiImportaMeiReg->q111_meiimportameiregempresa     = '';
          $oMeiImportaMeiReg->q111_meiimportameiregatividade   = '';
          $oMeiImportaMeiReg->q111_meiimportameiregcontador    = '';
          $oMeiImportaMeiReg->q111_meiimportamei               = $oMeiImportaMei->q105_sequencial;
          $oMeiImportaMeiReg->q111_meievento                   = $aDadosEvento['oEvento']->iSeqEvento;
          $oMeiImportaMeiReg->q111_data                        = $aDadosEvento['oEvento']->dtData;
          $oMeiImportaMeiReg->incluir(null);

          if ($oMeiImportaMeiReg->erro_status == 0){
            throw new Exception("{$sMsgErro}{$oMeiImportaMeiReg->erro_msg}") ;
          }

        }

        if ( isset($aDadosEvento['oContador']) ){

        	$oContador = $aDadosEvento['oContador'];

          $oMeiImportaMeiRegContador->q109_municipio       = $aDescrSIAFI[$oContador->q109_municipio];
          $oMeiImportaMeiRegContador->q109_ufcrc           = $oContador->q109_ufcrc;
          $oMeiImportaMeiRegContador->q109_codigocrc       = $oContador->q109_codcrc;
          $oMeiImportaMeiRegContador->q109_datacrc         = $oContador->q109_datacrc;
          $oMeiImportaMeiRegContador->q109_cnpjcpf         = $oContador->q109_cnpjcpf;
          $oMeiImportaMeiRegContador->q109_nome            = $oContador->q109_nome;
          $oMeiImportaMeiRegContador->q109_tipologradouro  = $oContador->q109_tipologradouro;
          $oMeiImportaMeiRegContador->q109_logradouro      = $oContador->q109_logradouro;
          $oMeiImportaMeiRegContador->q109_numero          = $oContador->q109_numero;
          $oMeiImportaMeiRegContador->q109_complemento     = $oContador->q109_complemento;
          $oMeiImportaMeiRegContador->q109_bairro          = $oContador->q109_bairro;
          $oMeiImportaMeiRegContador->q109_uf              = $oContador->q109_uf;
          $oMeiImportaMeiRegContador->q109_cep             = $oContador->q109_cep;
          $oMeiImportaMeiRegContador->q109_telefone        = $oContador->q109_telefone;
          $oMeiImportaMeiRegContador->q109_fax             = $oContador->q109_fax;
          $oMeiImportaMeiRegContador->q109_email           = $oContador->q109_email;

          $oMeiImportaMeiRegContador->incluir(null);

          if ($oMeiImportaMeiRegContador->erro_status == 0){
            throw new Exception("{$sMsgErro}{$oMeiImportaMeiRegContador->erro_msg}") ;
          }

          $oMeiImportaMeiReg->q111_meiimportameiregcontador    = $oMeiImportaMeiRegContador->q109_sequencial;
          $oMeiImportaMeiReg->q111_meiimportameiregresponsavel = '';
          $oMeiImportaMeiReg->q111_meiimportameiregatividade   = '';
          $oMeiImportaMeiReg->q111_meiimportameiregempresa     = '';
          $oMeiImportaMeiReg->q111_meiimportamei               = $oMeiImportaMei->q105_sequencial;
          $oMeiImportaMeiReg->q111_meievento                   = $aDadosEvento['oEvento']->iSeqEvento;
          $oMeiImportaMeiReg->q111_data                        = $aDadosEvento['oEvento']->dtData;
          $oMeiImportaMeiReg->incluir(null);

          if ($oMeiImportaMeiReg->erro_status == 0){
            throw new Exception("{$sMsgErro}{$oMeiImportaMeiReg->erro_msg}") ;
          }

        }

        if ( isset($aDadosEvento['aAtividades']) ){

        	foreach ( $aDadosEvento['aAtividades'] as $oAtividade ) {

	          $oMeiImportaMeiRegAtividade->q106_cnae      = $oAtividade->q106_cnae;
	          $oMeiImportaMeiRegAtividade->q106_descricao = $oAtividade->q106_descricao;
	          $oMeiImportaMeiRegAtividade->q106_principal = $oAtividade->q106_principal;

	          $oMeiImportaMeiRegAtividade->incluir(null);

	          if ($oMeiImportaMeiRegAtividade->erro_status == 0){
	            throw new Exception("{$sMsgErro}{$oMeiImportaMeiRegAtividade->erro_msg}") ;
	          }

	        	$oMeiImportaMeiReg->q111_meiimportameiregatividade   = $oMeiImportaMeiRegAtividade->q106_sequencial;
	          $oMeiImportaMeiReg->q111_meiimportameiregcontador    = '';
	          $oMeiImportaMeiReg->q111_meiimportameiregresponsavel = '';
	          $oMeiImportaMeiReg->q111_meiimportameiregempresa     = '';
	          $oMeiImportaMeiReg->q111_meiimportamei               = $oMeiImportaMei->q105_sequencial;
	          $oMeiImportaMeiReg->q111_meievento                   = $aDadosEvento['oEvento']->iSeqEvento;
	          $oMeiImportaMeiReg->q111_data                        = $aDadosEvento['oEvento']->dtData;
	          $oMeiImportaMeiReg->incluir(null);

	          if ($oMeiImportaMeiReg->erro_status == 0){
	            throw new Exception("{$sMsgErro}{$oMeiImportaMeiReg->erro_msg}") ;
	          }
        	}
        }
    	}
    }

    /**
     * Verifica o número de registros importados, caso ele não tenho
     * conseguido importar nenhum retorna um erro.
     */
    if ($iNumeroRegistros == 0) {
      throw new Exception("Nenhum registro importado!");
    }
  }

  /**
   * Adiciona dados referente a empresa para a propriedade $aDadosMEI
   *
   * @param integer $iCnpj
   * @param string  $sEvento
   * @param object  $oMeiEmpresa
   */
  function addEmpresa( $iCnpj='', $sEvento='', $oMeiEmpresa ){

    $sMsgErro = 'Falha ao adicionar empresa';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

    if ( trim($sEvento) == '' ){
      throw new Exception("{$sMsgErro}. Evento não informado!") ;
    }

    if ( empty($oMeiEmpresa) ){
      throw new Exception("{$sMsgErro}. Dados da empresa não informado!") ;
    }

    $oMeiEmpresa->iCodRua                = '';
    $oMeiEmpresa->iCodBairro             = '';
    $oMeiEmpresa->lEmpresaCadastrada     = false;

  	$this->aDadosMEI["$iCnpj"]['aEventos'][$sEvento]['oEmpresa'] = $oMeiEmpresa;

  }

  /**
   * Adiciona dados referente a empresa contábil ( contador ) para a propriedade $aDadosMEI
   *
   * @param integer $iCnpj
   * @param string  $sEvento
   * @param string  $oMeiContador
   */
  function addContador( $iCnpj='', $sEvento='', $oMeiContador){

    $sMsgErro = 'Falha ao adicionar empresa contábil';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

    if ( trim($sEvento) == '' ){
      throw new Exception("{$sMsgErro}. Evento não informado!") ;
    }

    if ( empty($oMeiContador) ){
      throw new Exception("{$sMsgErro}. Dados da empresa contábil não informada!") ;
    }

    $oMeiContador->iCodRua    = '';
    $oMeiContador->iCodBairro = '';
    $this->aDadosMEI["$iCnpj"]['aEventos'][$sEvento]['oContador'] = $oMeiContador;

  }

  /**
   * Adiciona dados referente ao responsável para a propriedade $aDadosMEI
   *
   * @param string $iCnpj
   * @param string $sEvento
   * @param object $oMeiResponsavel
   */
  function addResponsavel( $iCnpj='', $sEvento='', $oMeiResponsavel){

  	$sMsgErro = 'Falha ao adicionar responsável';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

    if ( trim($sEvento) == '' ){
      throw new Exception("{$sMsgErro}. Evento não informado!") ;
    }

    if ( empty($oMeiResponsavel) ){
      throw new Exception("{$sMsgErro}. Dados do responsável não informado!") ;
    }


    $oMeiResponsavel->iCodRua                = '';
    $oMeiResponsavel->iCodBairro             = '';
    $oMeiResponsavel->lResponsavelCadastrado = false;

    $this->aDadosMEI["$iCnpj"]['aEventos'][$sEvento]['oResponsavel'] = $oMeiResponsavel;

  }

  /**
   * Adiciona dados referente a atividade para a propriedade $aDadosMEI
   *
   * @param integer $iCnpj
   * @param string  $sEvento
   * @param string  $oMeiAtividade
   */
  function addAtividade( $iCnpj='', $sEvento='', $oMeiAtividade){

  	$sMsgErro = 'Falha ao adicionar atividade';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

    if ( trim($sEvento) == '' ){
      throw new Exception("{$sMsgErro}. Evento não informado!") ;
    }

    if ( empty($oMeiAtividade) ){
      throw new Exception("{$sMsgErro}. Dados da atividade não informada!");
    }

    $oMeiAtividade->iCodAtividade = '';
    $this->aDadosMEI["$iCnpj"]['aEventos'][$sEvento]['aAtividades'][$oMeiAtividade->q106_cnae] = $oMeiAtividade;

  }

  /**
   * Adiciona dados referente ao evento para a propriedade $aDadosMEI
   *
   * @param integer $iCnpj
   * @param string  $sEvento
   * @param integer $iSeqEvento
   * @param date    $dtData
   * @param string  $sTipo
   */
  function setDadosEvento( $iCnpj='', $sEvento='', $iSeqEvento='', $dtData='', $sTipo='' ){

  	$sMsgErro = 'Falha ao definir dados do evento';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

    if ( trim($sEvento) == '' ){
      throw new Exception("{$sMsgErro}. Evento não informado!") ;
    }

    $oEvento = new stdClass();
    $oEvento->iSeqEvento = $iSeqEvento;
    $oEvento->dtData     = $dtData;
    $oEvento->sTipo      = $sTipo;

    $this->aDadosMEI["$iCnpj"]['aEventos'][$sEvento]['oEvento'] = $oEvento;

  }

  /**
   * Adiciona dados referente ao recibo para a propriedade $aDadosMEI
   *
   * @param integer $iCnpj
   * @param string  $sNroRecibo
   */
  function addRecibo( $iCnpj='', $sNroRecibo='' ){

  	$sMsgErro = 'Falha ao adicionar número recibo';

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}. CNPJ do MEI não informado!") ;
    }

  	$this->aDadosMEI["$iCnpj"]['sRecibo'] = $sNroRecibo;

  }

  /**
   * Verifica se existe alguma inconsistência para o evento do cnpj informado, caso extista
   * então será retornado uma mensagem contendo a descrição da inconsistência
   *
   * @param  string  $sCodEvento
   * @param  integer $iCnpj
   * @return string  Mensagem de retorno
   */
  function validaEventoMEI( $sCodEvento='', $iCnpj='' ){

    $sMsgErro = "Validação do Evento sobre o MEI abortada!";

    if ( trim($sCodEvento) == '' ) {
      throw new Exception("{$sMsgErro}, código do evento não informado!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}, CNPJ do MEI não informado!");
    }

    /**
     *  Consulta os dados do CNPJ informado
     */
    try {
	    $aDadosMEI = $this->getDadosMEI($iCnpj);
    } catch (Exception $eException){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
    }

    $aMsg = array();

    if ( $sCodEvento == '101' || $sCodEvento == '209' ) {

   		$oDaoIssBase = db_utils::getDao('issbase');
   		$oDaoRuas    = db_utils::getDao('ruas');
   		$oDaoBairro  = db_utils::getDao('bairro');
   		$oDaoAtivid  = db_utils::getDao('ativid');

   		$oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];

   		$iCodCgmEmpresa = $this->getCgmByCpfCnpj($oDadosEmpresa->q107_cnpj);

   		if ( trim($iCodCgmEmpresa) != '' ) {

     		$sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa} ";
        $sWhereIssBase .= " and q02_dtbaix is null             ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows > 0 ) {
          $aMsg[]['10'] = "Alvará já cadastrado para o CGM : {$iCodCgmEmpresa}";
          return $aMsg;
        }
   		}


   		if ( !$oDadosEmpresa->lEmpresaCadastrada ) {

        if ( trim($iCodCgmEmpresa) != '' ) {
          $aMsg[]['1'] = "Empresa já cadatrada no sistema! CGM : {$iCodCgmEmpresa}";
        }

    		if ( trim($oDadosEmpresa->iCodRua) != '' ) {
    			$sWhereRuas    = " j14_codigo = {$oDadosEmpresa->iCodRua}";
    		} else {
          $sWhereRuas    = " trim(j14_nome) = '".trim($oDadosEmpresa->q107_logradouro)."'";
    		}

        $sSqlConsultaRua = $oDaoRuas->sql_query_file(null,"j14_codigo",null,$sWhereRuas);
        $rsConsultaRuas  = $oDaoRuas->sql_record($sSqlConsultaRua);

        if ( $oDaoRuas->numrows == 0 ) {
          $aMsg[]['2']   = "Logradouro ({$oDadosEmpresa->q107_logradouro}) não cadastrado!";
        }

        if ( trim($oDadosEmpresa->iCodBairro) != '' ) {
        	$sWhereBairro  = " j13_codi = {$oDadosEmpresa->iCodBairro}";
        } else {
  	      $sWhereBairro  = " trim(j13_descr) = '".trim(pg_escape_string($oDadosEmpresa->q107_bairro))."'";
        }

        $sSqlConsultaBairro = $oDaoBairro->sql_query_file(null,"j13_codi",null,$sWhereBairro);
        $rsConsultaBairro   = $oDaoBairro->sql_record($sSqlConsultaBairro);

        if ( $oDaoBairro->numrows == 0 ) {
        	$aMsg[]['3'] = "Bairro ({$oDadosEmpresa->q107_bairro}) não cadastrado!";
        }
   		}

   		$oDadosResponsavel = $aDadosMEI['aEventos'][$sCodEvento]['oResponsavel'];

      if ( !$oDadosResponsavel->lResponsavelCadastrado ) {

        $iCodCgmResponsavel = $this->getCgmByCpfCnpj($oDadosResponsavel->q108_cpf);

        if ( trim($iCodCgmResponsavel) != '' ) {
          $aMsg[]['12'] = "Responsável já cadatrado no sistema! CGM : {$iCodCgmResponsavel}";
        }

      }

      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['aAtividades']) ) {

        $aDadosAtividade = $aDadosMEI['aEventos'][$sCodEvento]['aAtividades'];

        foreach ( $aDadosAtividade as $iInd => $oDadosAtividade ) {

        	if ( trim($oDadosAtividade->iCodAtividade) != '' ) {

            $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae($oDadosAtividade->iCodAtividade);
            $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);

            if ( $oDaoAtivid->numrows ==  0 ) {
              $aMsg[]['4'] = "Sem atividades cadastradas para o CNAE : {$oDadosAtividade->q106_cnae}";
            }

        	} else {

		        $sWhereAtividade       = " q71_estrutural like '%{$oDadosAtividade->q106_cnae}'";
		        $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae(null,"q03_ativ",null,$sWhereAtividade);
		        $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);

		        if ( $oDaoAtivid->numrows ==  0 ) {
		          $aMsg[]['4'] = "Sem atividades cadastradas para o CNAE : {$oDadosAtividade->q106_cnae}";
		        } else if ($oDaoAtivid->numrows > 1 ) {
		        	$aMsg[]['8'] = "Mais e uma atividade cadastradas para o CNAE : {$oDadosAtividade->q106_cnae}";
		        }
        	}
        }
      }

    } else if (  $sCodEvento == '570' || $sCodEvento == '517' ) {


    	$oDaoMeiCgm = db_utils::getDao('meicgm');

      $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

      if ( trim($iCodCgmEmpresa) == '' ) {
      	$aMsg[]['5'] = "Empresa não cadastrada!";
      } else {

	      $rsMeiCgm = $oDaoMeiCgm->sql_record($oDaoMeiCgm->sql_query_file(null,"*",null,"q115_numcgm = {$iCodCgmEmpresa}"));

	      if ( $oDaoMeiCgm->numrows == 0 ) {
	        $aMsg[]['6'] = "Mei não cadastrado! CGM : {$iCodCgmEmpresa}";
	      }
      }


    } else if ( $sCodEvento == '247' ) {

      $oDaoIssBase    = db_utils::getDao('issbase');
      $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

      if ( trim($iCodCgmEmpresa) == '' ) {

        $aMsg[]['7'] = "Empresa não cadastrada!";

      } else {

        $sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa} ";
        $sWhereIssBase .= " and q02_dtbaix is null             ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows == 0 ) {
          $aMsg[]['10'] = "Alvará não cadastrado para o CGM : {$iCodCgmEmpresa}";
        }

      }

    } else if ( $sCodEvento == '912' )  {

    	$aMsg[]['11'] = "Evento sem processamento favor descartar!";

    /**
     *  Verifica se o evento contém algum tipo de validação
     *
     *  OBS: O array informado abaixo contém todos evento que existentes, porém sem nenhum tipo de validação,
     *  se não encontrar nenhum registro então o evento informado não está configurado no sistema.
     */
    } else if ( !in_array($sCodEvento,array('211','220','221','232','244','203')) ) {

  	  $aMsg[]['9']  = "Evento {$sCodEvento} não configurado para o sistema!";

    }

    return $aMsg;

  }


  function getTelaInconsistenciaMEI( $sCodEvento='', $iCnpj='' ){

  	$sMsgErro = 'Consulta de detalhes das inconsistências abortada!\n';

    if ( trim($sCodEvento) == '' ) {
      throw new Exception("{$sMsgErro}Código do evento não informado!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}CNPJ do MEI não informado!");
    }

    $oDaoAtivid  = db_utils::getDao('ativid');

    try {
      $aDadosMEI = $this->getDadosMEI($iCnpj);
    } catch (Exception $eException){
      throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
    }

  	try {
  		$aDadosInconsistencias = $this->validaEventoMEI($sCodEvento,$iCnpj);
  	} catch (Exception $eException) {
      throw new Exception("{$sMsgErro}{$eException->getMessage()}");
  	}

  	$sTela = "";
  	$aCnaeProcessado = array();


  	foreach ( $aDadosInconsistencias as $iIndInconsistencia => $aListaInconsistencias ) {
	    foreach ( $aListaInconsistencias as $iCodInconsistencia => $sDescricaoInconsistencia ) {

	    	$sTela .= "<tr>  ";
	    	$sTela .= "  <td>";

	    	if (  in_array($iCodInconsistencia,array(2,3)) ) {

	    		if ( $iCodInconsistencia == 2 ) {
		    		$sCampo = "logradouro";
	    		} else {
	          $sCampo = "bairro";
	    		}

	    		$sTela .= "<fieldset>     ";
	    		$sTela .= "  <legend>     ";
	        $sTela .= "    <b>{$sDescricaoInconsistencia}</b>";
	    		$sTela .= "  </legend>    ";
	        $sTela .= "  <table>      ";
	        $sTela .= "    <tr>       ";
	        $sTela .= "      <td width='80px'>";
	        $sTela .= "        <b><a href='#' onClick='js_pesquisa".ucwords($sCampo)."(true);'>".ucwords($sCampo)." :</a></b>";
	        $sTela .= "      </td>    ";
	        $sTela .= "      <td>     ";
	        $sTela .= "        <input id='cod{$sCampo}'   type='text' size='10px' onChange='js_pesquisa".ucwords($sCampo)."(false);'/>";
	        $sTela .= "        <input id='descr{$sCampo}' type='text' size='50px' style='background:#DEB887' readonly/>";
	        $sTela .= "      </td>    ";
	        $sTela .= "    </tr>      ";
	        $sTela .= "  </table>     ";
	        $sTela .= "</fieldset>    ";

	      } else if ( $iCodInconsistencia == 4 ) {

	        if ( isset($aDadosMEI['aEventos'][$sCodEvento]['aAtividades']) ) {

	          $aDadosAtividade = $aDadosMEI['aEventos'][$sCodEvento]['aAtividades'];

	          foreach ( $aDadosAtividade as $iInd => $oDadosAtividade ) {

	            $sCnae = $oDadosAtividade->q106_cnae;

	            if ( in_array($sCnae,$aCnaeProcessado) || $oDadosAtividade->iCodAtividade != '') {
                continue;
              }

	            $sWhereAtividade       = " q71_estrutural like '%{$oDadosAtividade->q106_cnae}'";
	            $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae(null,"*",null,$sWhereAtividade);
	            $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);

	            if ( $oDaoAtivid->numrows > 0  ) {
	            	continue;
	            }


	            $sTela .= "<fieldset>     ";
	            $sTela .= "  <legend>     ";
	            $sTela .= "    <b>{$sDescricaoInconsistencia}</b>";
	            $sTela .= "  </legend>    ";
	            $sTela .= "  <table>      ";
	            $sTela .= "    <tr>       ";
	            $sTela .= "      <td width='80px'>";
	            $sTela .= "        <b><a href='#' onClick='js_pesquisaAtividade(true,\"{$sCnae}\");'>Atividade :</a></b>";
	            $sTela .= "      </td>    ";
	            $sTela .= "      <td>     ";
	            $sTela .= "        <input id='codatividade{$sCnae}'   class='inputAtiv' type='text' size='10px' onChange='js_pesquisaAtividade(false,\"{$sCnae}\");'/>";
	            $sTela .= "        <input id='descratividade{$sCnae}' type='text' size='50px' style='background:#DEB887' readonly/>";
	            $sTela .= "      </td>    ";
	            $sTela .= "    </tr>      ";
	            $sTela .= "  </table>     ";
	            $sTela .= "</fieldset>    ";

              $aCnaeProcessado[] = $sCnae;
              break;

	          }
	        }

	    	} else if ( $iCodInconsistencia == 8 ) {

			    $sTelaAtividade = "";

	 	      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['aAtividades']) ) {

	          $aDadosAtividade = $aDadosMEI['aEventos'][$sCodEvento]['aAtividades'];

		        foreach ( $aDadosAtividade as $iInd => $oDadosAtividade ) {

	            $sCnae = $oDadosAtividade->q106_cnae;

		        	if ( in_array($sCnae,$aCnaeProcessado)) {
		        		continue;
		        	}

		          $sWhereAtividade       = " q71_estrutural like '%{$oDadosAtividade->q106_cnae}'";
		          $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae(null,"*",null,$sWhereAtividade);
		          $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);
	            $iLinhasAtividade      = $oDaoAtivid->numrows;


		          if ( $iLinhasAtividade > 1 ) {
		          	for ( $iIndAtiv=0; $iIndAtiv < $iLinhasAtividade; $iIndAtiv++ ) {

		          		$oAtividade = db_utils::fieldsMemory($rsAtividade,$iIndAtiv);

		          		$sTelaAtividade .= "<tr>";
		          		$sTelaAtividade .= "  <td class='linhagrid'><input type='radio' class='radioAtiv' name='radioatividade{$sCnae}' value='{$oAtividade->q03_ativ}'></td>";
		          		$sTelaAtividade .= "  <td class='linhagrid' textAlign='center'>{$oAtividade->q03_ativ}</td>";
		          		$sTelaAtividade .= "  <td class='linhagrid' textAlign='left'>{$oAtividade->q03_descr}</td>";
		          		$sTelaAtividade .= "</tr>";

		          	}
		          	$aCnaeProcessado[] = $sCnae;
		          	break;
		          }
		        }
	        }

	        $sTela .= "<fieldset>     ";
	        $sTela .= "  <legend>     ";
	        $sTela .= "    <b>{$sDescricaoInconsistencia}</b>";
	        $sTela .= "  </legend>    ";
	        $sTela .= "  <table style='border: 2px inset white; width:100%' cellspacing='0'>      ";
	        $sTela .= "    <tr>       ";
	        $sTela .= "      <td class='table_header'>&nbsp;</td>";
	        $sTela .= "      <td class='table_header'>Código</td>";
	        $sTela .= "      <td class='table_header'>Descrição Atividade</td>";
	        $sTela .= "    </tr>      ";
	        $sTela .= "    <tbody style=' overflow: scroll; overflow-x: hidden; background-color: white'>      ";
	        $sTela .=        $sTelaAtividade;
	        $sTela .= "    </tbody>      ";
	        $sTela .= "  </table>     ";
	        $sTela .= "</fieldset>    ";

	    	} else if ( $iCodInconsistencia == 1 ) {

          $sTela .= "<fieldset>      ";
          $sTela .= "  <legend>      ";
          $sTela .= "    <b>{$sDescricaoInconsistencia}<b> ";
          $sTela .= "  </legend>     ";
          $sTela .= "  <table>       ";
          $sTela .= "    <tr>        ";
          $sTela .= "      <td>      ";
          $sTela .= "        <input type='checkbox' id='empresacadastrada'/><b>Utilizar CGM já cadastrado</b> ";
          $sTela .= "      </td>     ";
          $sTela .= "    </tr>       ";
          $sTela .= "  </table>      ";
          $sTela .= "</fieldset>     ";

        } else if ( $iCodInconsistencia == 12 ) {

          $sTela .= "<fieldset>      ";
          $sTela .= "  <legend>      ";
          $sTela .= "    <b>{$sDescricaoInconsistencia}<b> ";
          $sTela .= "  </legend>     ";
          $sTela .= "  <table>       ";
          $sTela .= "    <tr>        ";
          $sTela .= "      <td>      ";
          $sTela .= "        <input type='checkbox' id='responsavelcadastrado'/><b>Utilizar CGM já cadastrado</b> ";
          $sTela .= "      </td>     ";
          $sTela .= "    </tr>       ";
          $sTela .= "  </table>      ";
          $sTela .= "</fieldset>     ";
	    	} else {

          $sTela .= "<fieldset>      ";
          $sTela .= "  <table>       ";
          $sTela .= "    <tr>        ";
          $sTela .= "      <td>      ";
          $sTela .= "        <b> - {$sDescricaoInconsistencia}</b>";
          $sTela .= "      </td>     ";
          $sTela .= "    </tr>       ";
          $sTela .= "  </table>      ";
          $sTela .= "</fieldset>     ";
	    	}

	      $sTela .= "  </td>";
	    	$sTela .= "</tr>  ";

	    }
  	}
    $sTelaInconsistencia = "<table width='100%'>$sTela</table> ";

  	return $sTelaInconsistencia;

  }


  function getTelaDetalhesEvento( $sCodEvento='', $iCnpj='' ){

  	$sMsgErro = 'Consulta dos detalhes do Evento abortado';

    if ( trim($sCodEvento) == '' ) {
      throw new Exception("{$sMsgErro}, código do evento não informado!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}, CNPJ do MEI não informado!");
    }

    $sTelaDetalhe = "";

    if ( $sCodEvento == '101' ||  $sCodEvento == '209' ) {

      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDadosEmpresa         = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];
      $aPropriedadesEmpresa  = get_object_vars($oDadosEmpresa);

      $oRotulo = new rotulolov();

      $sTelaDetalhe .= "<table width='100%'>";
      $sTelaDetalhe .= "  <tr>              ";
      $sTelaDetalhe .= "    <td>            ";
      $sTelaDetalhe .= "      <fieldset>    ";
      $sTelaDetalhe .= "        <legend>    ";
      $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosEmpresa\",\"tglEmpresa\");'>";
      $sTelaDetalhe .= "          <b>Dados Empresa</b>    ";
      $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglEmpresa' border='0'>";
      $sTelaDetalhe .= "          </a>                    ";
      $sTelaDetalhe .= "        </legend>                 ";
      $sTelaDetalhe .= "        <table id='dadosEmpresa' style='display:none'> ";

      foreach ( $aPropriedadesEmpresa as $sCampoEmpresa => $sValorEmpresa ) {

        $oRotulo->label($sCampoEmpresa);

        if ( $sCampoEmpresa == 'q107_inscrmei') {
        	if ( $sValorEmpresa == 't') {
        		$sValorEmpresa = 'Sim';
        	} else {
        		$sValorEmpresa = 'Não';
        	}
        }

        $sTelaDetalhe .= "<tr>                                                              ";
        $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                   </td> ";
        $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorEmpresa}</td> ";
        $sTelaDetalhe .= "</tr>                                                             ";

      }

      $sTelaDetalhe .= "        </table>  ";
      $sTelaDetalhe .= "      </fieldset> ";
      $sTelaDetalhe .= "    </td> ";
      $sTelaDetalhe .= "  </tr> ";

      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['oResponsavel']) ) {

        $oDadosResponsavel         = $aDadosMEI['aEventos'][$sCodEvento]['oResponsavel'];
        $aPropriedadesResponsavel  = get_object_vars($oDadosResponsavel);


        $sTelaDetalhe .= "  <tr> ";
        $sTelaDetalhe .= "    <td> ";
        $sTelaDetalhe .= "      <fieldset> ";
        $sTelaDetalhe .= "        <legend> ";
        $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosResponsavel\",\"tglResponsavel\");'>";
        $sTelaDetalhe .= "          <b>Dados Responsável</b>    ";
        $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglResponsavel' border='0'>";
        $sTelaDetalhe .= "          </a>";
        $sTelaDetalhe .= "        </legend> ";
        $sTelaDetalhe .= "        <table id='dadosResponsavel' style='display:none'>   ";

        foreach ( $aPropriedadesResponsavel as $sCampoResponsavel => $sValorResponsavel ) {

          $oRotulo->label($sCampoResponsavel);

          $sTelaDetalhe .= "<tr>                                                                   ";
          $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                        </td> ";
          $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorResponsavel} </td> ";
          $sTelaDetalhe .= "</tr>                                                                  ";

        }

        $sTelaDetalhe .= "        </table>  ";
        $sTelaDetalhe .= "      </fieldset> ";
        $sTelaDetalhe .= "    </td>         ";
        $sTelaDetalhe .= "  </tr>           ";

      }

      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['oContador']) ) {

        $oDadosContador         = $aDadosMEI['aEventos'][$sCodEvento]['oContador'];
        $aPropriedadesContador  = get_object_vars($oDadosContador);


        $sTelaDetalhe .= "  <tr> ";
        $sTelaDetalhe .= "    <td> ";
        $sTelaDetalhe .= "      <fieldset> ";
        $sTelaDetalhe .= "        <legend> ";
        $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosContador\",\"tglContador\");'>";
        $sTelaDetalhe .= "          <b>Dados Contador</b>    ";
        $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglContador' border='0'>";
        $sTelaDetalhe .= "          </a>";
        $sTelaDetalhe .= "        </legend> ";
        $sTelaDetalhe .= "        <table  id='dadosContador' style='display:none'>   ";

        foreach ( $aPropriedadesContador as $sCampoContador => $sValorContador ) {

          $oRotulo->label($sCampoContador);

          $sTelaDetalhe .= "<tr>                                                                ";
          $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                     </td> ";
          $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorContador} </td> ";
          $sTelaDetalhe .= "</tr>                                                               ";

        }

        $sTelaDetalhe .= "        </table>  ";
        $sTelaDetalhe .= "      </fieldset> ";
        $sTelaDetalhe .= "    </td>         ";
        $sTelaDetalhe .= "  </tr>           ";

      }

      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['aAtividades']) ) {

        $aDadosAtividade = $aDadosMEI['aEventos'][$sCodEvento]['aAtividades'];

        foreach ( $aDadosAtividade as $iInd => $oDadosAtividade ) {

          $aPropriedadesAtividade = get_object_vars($oDadosAtividade);

          $sCnae = $oDadosAtividade->q106_cnae;

          $sTelaDetalhe .= "  <tr> ";
          $sTelaDetalhe .= "    <td> ";
          $sTelaDetalhe .= "      <fieldset> ";
          $sTelaDetalhe .= "        <legend> ";
          $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosAtividade{$sCnae}\",\"tglAtividade{$sCnae}\");'>";
          $sTelaDetalhe .= "          <b>Dados Atividade - {$sCnae}</b>    ";
          $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglAtividade{$sCnae}' border='0'>";
          $sTelaDetalhe .= "          </a>";
          $sTelaDetalhe .= "        </legend> ";
          $sTelaDetalhe .= "        <table  id='dadosAtividade{$sCnae}' style='display:none'>   ";

          foreach ( $aPropriedadesAtividade as $sCampoAtividade => $sValorAtividade ) {

            $oRotulo->label($sCampoAtividade);

	          if ( $sCampoAtividade == 'q106_principal') {
	            if ( $sValorAtividade == 't') {
	              $sValorAtividade = 'Sim';
	            } else {
	              $sValorAtividade = 'Não';
	            }
	          }

            $sTelaDetalhe .= "<tr>                                                                 ";
            $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                      </td> ";
            $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorAtividade} </td> ";
            $sTelaDetalhe .= "</tr>                                                                ";

          }

          $sTelaDetalhe .= "        </table>  ";
          $sTelaDetalhe .= "      </fieldset> ";
          $sTelaDetalhe .= "    </td>         ";
          $sTelaDetalhe .= "  </tr>           ";

        }
      }

      $sTelaDetalhe .= "</table>";


    } else if ( $sCodEvento == '211' ) {


      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDadosEmpresa         = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];
      $aPropriedadesEmpresa  = get_object_vars($oDadosEmpresa);

      $oRotulo = new rotulolov();

      $sTelaDetalhe .= "<table width='100%'>                                              ";
      $sTelaDetalhe .= "  <tr>                                                            ";
      $sTelaDetalhe .= "    <td>                                                          ";
      $sTelaDetalhe .= "      <fieldset>                                                  ";
      $sTelaDetalhe .= "        <legend>                                                  ";
      $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosEmpresa\",\"tglEmpresa\");'>";
      $sTelaDetalhe .= "          <b>Endereço Empresa</b>                                 ";
      $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglEmpresa' border='0'> ";
      $sTelaDetalhe .= "          </a>                                                    ";
      $sTelaDetalhe .= "        </legend>                                                 ";
      $sTelaDetalhe .= "        <table id='dadosEmpresa' style='display:none'>            ";

      foreach ( $aPropriedadesEmpresa as $sCampoEmpresa => $sValorEmpresa ) {

        $oRotulo->label($sCampoEmpresa);

        if ( $sCampoEmpresa == 'q107_inscrmei') {
          if ( $sValorEmpresa == 't') {
            $sValorEmpresa = 'Sim';
          } else {
            $sValorEmpresa = 'Não';
          }
        }

        $sTelaDetalhe .= "<tr>                                                              ";
        $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                   </td> ";
        $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorEmpresa}</td> ";
        $sTelaDetalhe .= "</tr>                                                             ";

      }

      $sTelaDetalhe .= "        </table>  ";
      $sTelaDetalhe .= "      </fieldset> ";
      $sTelaDetalhe .= "    </td>         ";
      $sTelaDetalhe .= "  </tr>           ";
      $sTelaDetalhe .= "</table>          ";


    } else if ( $sCodEvento == '221' ) {


      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDaoCgm = db_utils::getDao('cgm');
      $rsCgm   = $oDaoCgm->sql_record($oDaoCgm->sql_query_file(null,"z01_nomefanta",null," z01_cgccpf = '{$iCnpj}'"));

      if ( $oDaoCgm->numrows > 0  ) {
      	$sNomeFantasiaCgm = db_utils::fieldsMemory($rsCgm,0)->z01_nomefanta;
      } else {
      	$sNomeFantasiaCgm = '';
      }

      $oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];

      $sTelaDetalhe .= "<table width='100%'>                                                        ";
      $sTelaDetalhe .= "  <tr>                                                                      ";
      $sTelaDetalhe .= "    <td>                                                                    ";
      $sTelaDetalhe .= "      <fieldset>                                                            ";
      $sTelaDetalhe .= "        <legend>                                                            ";
      $sTelaDetalhe .= "          <b>Alteração Nome Fantasia</b>                                    ";
      $sTelaDetalhe .= "        </legend>                                                           ";
      $sTelaDetalhe .= "        <table>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Nome Fantasia Atual:</b></td>                     ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$sNomeFantasiaCgm}</td>";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Nome Fantasia Novo:</b></td>                      ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$oDadosEmpresa->q107_nomefantasia}</td> ";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "        </table>                                                            ";
      $sTelaDetalhe .= "      </fieldset>                                                           ";
      $sTelaDetalhe .= "    </td>                                                                   ";
      $sTelaDetalhe .= "  </tr>                                                                     ";
      $sTelaDetalhe .= "</table>                                                                    ";


    } else if ( $sCodEvento == '220' ) {


      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDaoCgm = db_utils::getDao('cgm');
      $rsCgm   = $oDaoCgm->sql_record($oDaoCgm->sql_query_file(null,"z01_nome",null," z01_cgccpf = '{$iCnpj}'"));

      if ( $oDaoCgm->numrows > 0  ) {
        $sNomeCgm = db_utils::fieldsMemory($rsCgm,0)->z01_nome;
      } else {
        $sNomeCgm = '';
      }

      $oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];

      $sTelaDetalhe .= "<table width='100%'>                                                        ";
      $sTelaDetalhe .= "  <tr>                                                                      ";
      $sTelaDetalhe .= "    <td>                                                                    ";
      $sTelaDetalhe .= "      <fieldset>                                                            ";
      $sTelaDetalhe .= "        <legend>                                                            ";
      $sTelaDetalhe .= "          <b>Alteração Nome Empresa</b>                                     ";
      $sTelaDetalhe .= "        </legend>                                                           ";
      $sTelaDetalhe .= "        <table>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Nome Empresa Atual:</b></td>                      ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$sNomeCgm}</td>      ";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Nome Empresa Novo:</b></td>                       ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$oDadosEmpresa->q107_nome}</td> ";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "        </table>                                                            ";
      $sTelaDetalhe .= "      </fieldset>                                                           ";
      $sTelaDetalhe .= "    </td>                                                                   ";
      $sTelaDetalhe .= "  </tr>                                                                     ";
      $sTelaDetalhe .= "</table>                                                                    ";

    } else if ( $sCodEvento == '232' ) {

      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oRotulo                = new rotulolov();
      $oDadosContador         = $aDadosMEI['aEventos'][$sCodEvento]['oContador'];
      $aPropriedadesContador  = get_object_vars($oDadosContador);

      $sTelaDetalhe .= "<table width='100%'>";
      $sTelaDetalhe .= "  <tr> ";
      $sTelaDetalhe .= "    <td> ";
      $sTelaDetalhe .= "      <fieldset> ";
      $sTelaDetalhe .= "        <legend> ";
      $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosContador\",\"tglContador\");'>";
      $sTelaDetalhe .= "          <b>Dados Novo Contador</b> ";
      $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglContador' border='0'>";
      $sTelaDetalhe .= "          </a>";
      $sTelaDetalhe .= "        </legend> ";
      $sTelaDetalhe .= "        <table  id='dadosContador' style='display:none'>   ";

      foreach ( $aPropriedadesContador as $sCampoContador => $sValorContador ) {

        $oRotulo->label($sCampoContador);

        $sTelaDetalhe .= "<tr>                                                                ";
        $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                     </td> ";
        $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorContador} </td> ";
        $sTelaDetalhe .= "</tr>                                                               ";

      }

      $sTelaDetalhe .= "        </table>  ";
      $sTelaDetalhe .= "      </fieldset> ";
      $sTelaDetalhe .= "    </td>         ";
      $sTelaDetalhe .= "  </tr>           ";
      $sTelaDetalhe .= "</table>          ";


    } else if ( $sCodEvento == '244' ) {


      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oRotulo         = new rotulolov();
    	$aDadosAtividade = $aDadosMEI['aEventos'][$sCodEvento]['aAtividades'];

      foreach ( $aDadosAtividade as $iInd => $oDadosAtividade ) {

        $aPropriedadesAtividade = get_object_vars($oDadosAtividade);
        $sCnae = $oDadosAtividade->q106_cnae;

        $sTelaDetalhe .= "<table width='100%'> ";
        $sTelaDetalhe .= "  <tr> ";
        $sTelaDetalhe .= "    <td> ";
        $sTelaDetalhe .= "      <fieldset> ";
        $sTelaDetalhe .= "        <legend> ";
        $sTelaDetalhe .= "          <a   style='-moz-user-select: none;cursor: pointer' onClick='js_toggle(\"dadosAtividade{$sCnae}\",\"tglAtividade{$sCnae}\");'>";
        $sTelaDetalhe .= "          <b>Dados Nova Atividade - {$sCnae}</b>    ";
        $sTelaDetalhe .= "          <img src='imagens/seta.gif' id='tglAtividade{$sCnae}' border='0'>";
        $sTelaDetalhe .= "          </a>";
        $sTelaDetalhe .= "        </legend> ";
        $sTelaDetalhe .= "        <table  id='dadosAtividade{$sCnae}' style='display:none'>   ";

        foreach ( $aPropriedadesAtividade as $sCampoAtividade => $sValorAtividade ) {

          $oRotulo->label($sCampoAtividade);

          if ( $sCampoAtividade == 'q106_principal') {
	          if ( $sValorAtividade == 't') {
	            $sValorAtividade = 'Sim';
	          } else {
	            $sValorAtividade = 'Não';
	          }
	        }

          $sTelaDetalhe .= "<tr>                                                                 ";
          $sTelaDetalhe .= "  <td nowrap ><b>{$oRotulo->titulo} :</b>                      </td> ";
          $sTelaDetalhe .= "  <td width='100%' class='valDetalhe'>&nbsp;{$sValorAtividade} </td> ";
          $sTelaDetalhe .= "</tr>                                                                ";

        }

        $sTelaDetalhe .= "        </table>  ";
        $sTelaDetalhe .= "      </fieldset> ";
        $sTelaDetalhe .= "    </td>         ";
        $sTelaDetalhe .= "  </tr>           ";
        $sTelaDetalhe .= "</table>          ";

      }

    } else if ( $sCodEvento == '247' ) {


      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDaoIssBase    = db_utils::getDao('issbase');
      $sWhereIssBase  = "     z01_cgccpf = '{$iCnpj}'";
      $sWhereIssBase .= " and q02_dtbaix is null     ";
      $rsIssBase      = $oDaoIssBase->sql_record($oDaoIssBase->sql_query(null,"q02_capit",null,$sWhereIssBase));

      if ( $oDaoIssBase->numrows > 0  ) {
        $sCapitalSocial = db_utils::fieldsMemory($rsIssBase,0)->q02_capit;
      } else {
        $sCapitalSocial = '';
      }

      $oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];

      $sTelaDetalhe .= "<table width='100%'>                                                        ";
      $sTelaDetalhe .= "  <tr>                                                                      ";
      $sTelaDetalhe .= "    <td>                                                                    ";
      $sTelaDetalhe .= "      <fieldset>                                                            ";
      $sTelaDetalhe .= "        <legend>                                                            ";
      $sTelaDetalhe .= "          <b>Alteração Capital Social</b>                                   ";
      $sTelaDetalhe .= "        </legend>                                                           ";
      $sTelaDetalhe .= "        <table>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Capital Social Atual:</b></td>                    ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$sCapitalSocial}</td>";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>Capital Social Novo:</b></td>                      ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$oDadosEmpresa->q107_capitalsocial}</td> ";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "        </table>                                                            ";
      $sTelaDetalhe .= "      </fieldset>                                                           ";
      $sTelaDetalhe .= "    </td>                                                                   ";
      $sTelaDetalhe .= "  </tr>                                                                     ";
      $sTelaDetalhe .= "</table>                                                                    ";


    } else if ( $sCodEvento == '570' || $sCodEvento == '517' ) {

      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];

      $sTelaDetalhe .= "<table width='100%'>                                                        ";
      $sTelaDetalhe .= "  <tr>                                                                      ";
      $sTelaDetalhe .= "    <td>                                                                    ";
      $sTelaDetalhe .= "      <fieldset>                                                            ";
      $sTelaDetalhe .= "        <legend>                                                            ";
      $sTelaDetalhe .= "          <b>Dados Empresa</b>                                              ";
      $sTelaDetalhe .= "        </legend>                                                           ";
      $sTelaDetalhe .= "        <table>                                                             ";
      $sTelaDetalhe .= "          <tr>                                                              ";
      $sTelaDetalhe .= "            <td nowrap><b>CNPJ Empresa:</b></td>                            ";
      $sTelaDetalhe .= "            <td width='100%' class='valDetalhe'>&nbsp;{$oDadosEmpresa->q107_cnpj}</td>";
      $sTelaDetalhe .= "          </tr>                                                             ";
      $sTelaDetalhe .= "        </table>                                                            ";
      $sTelaDetalhe .= "      </fieldset>                                                           ";
      $sTelaDetalhe .= "    </td>                                                                   ";
      $sTelaDetalhe .= "  </tr>                                                                     ";
      $sTelaDetalhe .= "</table>                                                                    ";

    }

    return $sTelaDetalhe;

  }


  function getDadosMEI( $iCnpj='' ){

  	if ( trim($iCnpj) != '' ) {
      return $this->aDadosMEI[$iCnpj];
  	} else {
	  	return $this->aDadosMEI;
  	}

  }


  function processaMeiArquivo( $iCnpj='', $sCodEvento='', $iCodProcessa='' ){

    $sMsgErro = 'Processamento do arquivo do MEI abortado,\n';

    if ( !db_utils::inTransaction() ) {
      throw new Exception("{$sMsgErro}, nenhuma transação encontrada!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}, CNPJ do MEI não informado!");
    }

    $oDaoMeiImporta     = db_utils::getDao('meiimporta');
    $oDaoMeiProcessa    = db_utils::getDao('meiprocessa');
    $oDaoMeiProcessaReg = db_utils::getDao('meiprocessareg');

    if ( trim($iCodProcessa) == '' ) {

      $oDaoMeiProcessa->q113_id_usuario  = db_getsession('DB_id_usuario');
      $oDaoMeiProcessa->q113_data        = date('Y-m-d',db_getsession('DB_datausu'));
      $oDaoMeiProcessa->q113_hora        = db_hora();
      $oDaoMeiProcessa->incluir(null);

      if ( $oDaoMeiProcessa->erro_status == 0  ) {
        throw new Exception("{$sMsgErro}, {$oDaoMeiProcessa->erro_msg}");
      }

      $iCodProcessa = $oDaoMeiProcessa->q113_sequencial;
    }

    $sWhereImporta  = $this->sWhereImporta;
    $sWhereImporta .= " and q105_cnpj = '{$iCnpj}' ";

    if ( trim($sCodEvento) != '' ) {
    	$sWhereImporta .= " and q101_codigo = '{$sCodEvento}' ";
    }

    $sSqlImporta    = $oDaoMeiImporta->sql_query_reg(null,"*",null,$sWhereImporta);
    $rsMeiImporta   = $oDaoMeiImporta->sql_record($sSqlImporta);
    $iRowsImporta   = pg_num_rows($rsMeiImporta);

    $aRegProcessa = array();

    if ( $iRowsImporta > 0 ) {

      for ( $iInd=0; $iInd < $iRowsImporta; $iInd++ ) {

        $oDadosImporta = db_utils::fieldsMemory($rsMeiImporta,$iInd);

        $oDaoMeiProcessaReg->q112_meiprocessa      = $iCodProcessa;
        $oDaoMeiProcessaReg->q112_meiimportameireg = $oDadosImporta->q111_sequencial;
        $oDaoMeiProcessaReg->q112_tipoprocessa     = 1;
        $oDaoMeiProcessaReg->incluir(null);

        if ( $oDaoMeiProcessaReg->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}, {$oDaoMeiProcessaReg->erro_msg}");
        }

        $aRegProcessa[] = $oDaoMeiProcessaReg->q112_sequencial;

      }

    }

    $sDataEvento = "";
    $aDadosMEI   = $this->getDadosMEI($iCnpj);

   	foreach ( $aDadosMEI['aEventos'] as $sEvento => $oDadosEvento ) {

   		if ( trim($sCodEvento) != '' && $sEvento != $sCodEvento ) {
   			continue;
   		}

      if ( isset($oDadosEvento['oEvento']->dtData) ) {
        $sDataEvento = $oDadosEvento['oEvento']->dtData;
      }

   		if ( $sCodEvento == '101' || $sCodEvento == '209' ) {

   			require_once modification("model/CgmFactory.model.php");

   			$oDaoRuas                 = db_utils::getDao('ruas');
   			$oDaoBairro               = db_utils::getDao('bairro');
		    $oDaoMeiCgm               = db_utils::getDao('meicgm');
   			$oDaoIssBase              = db_utils::getDao('issbase');
        $oDaoIssRuas              = db_utils::getDao('issruas');
        $oDaoIssBairro            = db_utils::getDao('issbairro');
        $oDaoEscrito              = db_utils::getDao('escrito');
        $oDaoCadEscrito           = db_utils::getDao('cadescrito');
        $oDaoCgm                  = db_utils::getDao('cgm');
        $oDaoCnae                 = db_utils::getDao('cnae');
        $oDaoAtivid               = db_utils::getDao('ativid');
        $oDaoAtivPrinc            = db_utils::getDao('ativprinc');
        $oDaoAtividCnae           = db_utils::getDao('atividcnae');
        $oDaoCnaeAnalitica        = db_utils::getDao('cnaeanalitica');
        $oDaoTabAtiv              = db_utils::getDao('tabativ');
        $oDaoSocios               = db_utils::getDao('socios');
   			$oDaoMeiProcessaRegMeiCgm = db_utils::getDao('meiprocessaregmeicgm');

   			$oDadosEmpresa = $oDadosEvento['oEmpresa'];


   			if ( $oDadosEmpresa->lEmpresaCadastrada ) {

   			  $oCgmEmpresa = CgmFactory::getInstanceByCnpjCpf($oDadosEmpresa->q107_cnpj);

   			} else {

          try {

            $oCgmEmpresa = CgmFactory::getInstanceByType(2);

            if ( trim($oDadosEmpresa->iCodRua) != '' ) {
              $rsLogradouro = $oDaoRuas->sql_record($oDaoRuas->sql_query_file($oDadosEmpresa->iCodRua));

              if ( $oDaoRuas->numrows > 0 ) {
                $sLogradouro = db_utils::fieldsMemory($rsLogradouro,0)->j14_nome;
              } else {
                throw new Exception("{$sMsgErro}Logradouro não encontrado!");
              }
            } else {
              $sLogradouro = $oDadosEmpresa->q107_logradouro;
            }

            if ( trim($oDadosEmpresa->iCodBairro) != '' ) {
              $rsBairro = $oDaoBairro->sql_record($oDaoBairro->sql_query_file($oDadosEmpresa->iCodBairro));

              if ( $oDaoBairro->numrows > 0 ) {
                $sBairro = db_utils::fieldsMemory($rsBairro,0)->j13_descr;
              } else {
                throw new Exception("{$sMsgErro}Bairro não encontrado!");
              }
            } else {
              $sBairro = $oDadosEmpresa->q107_bairro;
            }

            $oCgmEmpresa->setMunicipio         ($oDadosEmpresa->q107_municipio);
            $oCgmEmpresa->setCnpj              ($oDadosEmpresa->q107_cnpj);
            $oCgmEmpresa->setNome              ($oDadosEmpresa->q107_nome);
            $oCgmEmpresa->setNomeFantasia      ($oDadosEmpresa->q107_nomefantasia);
            $oCgmEmpresa->setLogradouro        ($sLogradouro);
            $oCgmEmpresa->setNumero            ($oDadosEmpresa->q107_numero);
            $oCgmEmpresa->setComplemento       ($oDadosEmpresa->q107_complemento);
            $oCgmEmpresa->setBairro            ($sBairro);
            $oCgmEmpresa->setUf                ($oDadosEmpresa->q107_uf);
            $oCgmEmpresa->setCep               ($oDadosEmpresa->q107_cep);
            $oCgmEmpresa->setTelefone          ($oDadosEmpresa->q107_telefone);
            $oCgmEmpresa->setTelefoneComercial ($oDadosEmpresa->q107_telefonecomercial);
            $oCgmEmpresa->setFax               ($oDadosEmpresa->q107_fax);
            $oCgmEmpresa->setEmail             ($oDadosEmpresa->q107_email);
            $oCgmEmpresa->setCaixaPostal       ($oDadosEmpresa->q107_caixapostal);

            $oCgmEmpresa->save();

          } catch (Exception $eException) {
            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
          }

   			}


   			$sWhereIssBase = " q02_numcgm = {$oCgmEmpresa->getCodigo()}";
   			$rsIssBase     = $oDaoIssBase->sql_record($oDaoIssBase->sql_query_file(null,"*",null,$sWhereIssBase));

   			if ( $oDaoIssBase->numrows > 0 ) {

   			  $iCodInscricao = $oIssbase = db_utils::fieldsMemory($rsIssBase,0)->q02_inscr;

   			} else {

          $oDaoIssBase->q02_numcgm   = $oCgmEmpresa->getCodigo();
          $oDaoIssBase->q02_tiplic   = '0';
          $oDaoIssBase->q02_inscmu   = '0';
          $oDaoIssBase->q02_dtinic   = $oDadosEvento['oEvento']->dtData;
          $oDaoIssBase->q02_dtcada   = date('Y-m-d',db_getsession('DB_datausu'));
          $oDaoIssBase->q02_ultalt   = date("Y-m-d",db_getsession('DB_datausu'));
          $oDaoIssBase->q02_dtalt    = date("Y-m-d",db_getsession('DB_datausu'));
          $oDaoIssBase->q02_capit    = $oDadosEmpresa->q107_capitalsocial;
          $oDaoIssBase->q02_cep      = $oDadosEmpresa->q107_cep;

          $oDaoIssBase->incluirNumeracaoContinua(null);

          if ( $oDaoIssBase->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$oDaoIssBase->erro_msg}");
          }

          $iCodInscricao = $oDaoIssBase->q02_inscr;

          $sSqlRuaBairroCgm = $oDaoCgm->sql_query_ender($oCgmEmpresa->getCodigo(),"ruas.j14_codigo,bairro.j13_codi");
          $rsRuaBairroCgm   = $oDaoCgm->sql_record($sSqlRuaBairroCgm);


          if ( $oDaoCgm->numrows > 0 ) {
            $iCodRua    = db_utils::fieldsMemory($rsRuaBairroCgm,0)->j14_codigo;
            $iCodBairro = db_utils::fieldsMemory($rsRuaBairroCgm,0)->j13_codi;
          } else {
            $iCodRua    = '';
            $iCodBairro = '';
          }


          if ( trim($iCodBairro) != '' ) {

            $oDaoIssBairro->q13_inscr  = $iCodInscricao;
            $oDaoIssBairro->q13_bairro = $iCodBairro;
            $oDaoIssBairro->incluir($iCodInscricao);

            if ( $oDaoIssBairro->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoIssBairro->erro_msg}");
            }

          }

          if ( trim($iCodRua) != '' ) {

            $oDaoIssRuas->q02_inscr  = $iCodInscricao;
            $oDaoIssRuas->j14_codigo = $iCodRua;
            $oDaoIssRuas->q02_compl  = $oDadosEmpresa->q107_complemento;
            $oDaoIssRuas->q02_cxpost = $oDadosEmpresa->q107_caixapostal;
            $oDaoIssRuas->q02_numero = $oDadosEmpresa->q107_numero;

            $oDaoIssRuas->incluir($iCodInscricao);

            if ( $oDaoIssRuas->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoIssRuas->erro_msg}");
            }
          }
   			}


  			if ( isset($oDadosEvento['oResponsavel']) ) {

    			$oDadosResponsavel  = $oDadosEvento['oResponsavel'];
    			$iCodCgmResponsavel = '';

		      if ( trim($oDadosResponsavel->q108_cpf) != '' ) {
		      	$iCodCgmResponsavel = $this->getCgmByCpfCnpj($oDadosResponsavel->q108_cpf);
		      }

		      if ( trim($iCodCgmResponsavel) == '' ) {

	    			try {

		    			$oCgmResponsavel = CgmFactory::getInstanceByType(1);

		          $oCgmResponsavel->setCpf         ($oDadosResponsavel->q108_cpf);
		          $oCgmResponsavel->setNome        ($oDadosResponsavel->q108_nome);
		          $oCgmResponsavel->setMunicipio   ($oDadosResponsavel->q108_municipio);
		          $oCgmResponsavel->setLogradouro  ($oDadosResponsavel->q108_logradouro);
		          $oCgmResponsavel->setNumero      ($oDadosResponsavel->q108_numero);
		          $oCgmResponsavel->setComplemento ($oDadosResponsavel->q108_complemento);
		          $oCgmResponsavel->setBairro      ($oDadosResponsavel->q108_bairro);
		          $oCgmResponsavel->setUf          ($oDadosResponsavel->q108_uf);
		          $oCgmResponsavel->setCep         ($oDadosResponsavel->q108_cep);
		          $oCgmResponsavel->setTelefone    ($oDadosResponsavel->q108_telefone);
		          $oCgmResponsavel->setFax         ($oDadosResponsavel->q108_fax);
		          $oCgmResponsavel->setEmail       ($oDadosResponsavel->q108_email);

	            $oCgmResponsavel->save();

	    			} catch (Exception $eException) {
	            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	          }

	          $iCodCgmResponsavel = $oCgmResponsavel->getCodigo();

		      }

          $oDaoSocios->q95_cgmpri = $oCgmEmpresa->getCodigo();
          $oDaoSocios->q95_numcgm = $iCodCgmResponsavel;
          $oDaoSocios->q95_perc   = $oDadosEmpresa->q107_capitalsocial;
          $oDaoSocios->q95_tipo   = 2;
          $oDaoSocios->incluir($oCgmEmpresa->getCodigo(),$iCodCgmResponsavel);

          if ( $oDaoSocios->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$oDaoSocios->erro_msg}");
          }

   			}

   			if ( isset($oDadosEvento['oContador']) ) {

   				$oDadosContador  = $oDadosEvento['oContador'];
          $iCodCgmContador = '';

   			  if ( trim($oDadosContador->q109_cnpjcpf) != ''  ) {
            $iCodCgmContador = $this->getCgmByCpfCnpj($oDadosContador->q109_cnpjcpf);
          }

          if ( trim($iCodCgmContador) == '' ) {

	   				try {

		   				if ( strlen(trim($oDadosContador->q109_cnpjcpf)) == '14' ) {
		    				$oCgmContador = CgmFactory::getInstanceByType(2);
		            $oCgmContador->setCnpj($oDadosContador->q109_cnpjcpf);
		   				} else {
		   					$oCgmContador = CgmFactory::getInstanceByType(1);
		   					$oCgmContador->setCpf($oDadosContador->q109_cnpjcpf);
		   				}

		          $oCgmContador->setNome        ($oDadosContador->q109_nome);
		          $oCgmContador->setMunicipio   ($oDadosContador->q109_municipio);
		          $oCgmContador->setLogradouro  ($oDadosContador->q109_logradouro);
		          $oCgmContador->setNumero      ($oDadosContador->q109_numero);
		          $oCgmContador->setComplemento ($oDadosContador->q109_complemento);
		          $oCgmContador->setBairro      ($oDadosContador->q109_bairro);
		          $oCgmContador->setUf          ($oDadosContador->q109_uf);
		          $oCgmContador->setCep         ($oDadosContador->q109_cep);
		          $oCgmContador->setTelefone    ($oDadosContador->q109_telefone);
		          $oCgmContador->setFax         ($oDadosContador->q109_fax);
		          $oCgmContador->setEmail       ($oDadosContador->q109_email);

		   				$oCgmContador->save();

	          } catch (Exception $eException) {
	            throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	          }

	          $iCodCgmContador = $oCgmContador->getCodigo();

          }

   			  $rsCadEscrito = $oDaoCadEscrito->sql_record($oDaoCadEscrito->sql_query_file($iCodCgmContador));

	        if ( $oDaoCadEscrito->numrows == 0 ) {

	          $oDaoCadEscrito->q86_numcgm = $iCodCgmContador;
	          $oDaoCadEscrito->incluir($iCodCgmContador);

	          if ( $oDaoCadEscrito->erro_status == 0 ) {
	            throw new Exception("{$sMsgErro}\n{$oDaoCadEscrito->erro_msg}");
	          }

	        }

          $oDaoEscrito->q10_inscr  = $iCodInscricao;
          $oDaoEscrito->q10_numcgm = $iCodCgmContador;

          $oDaoEscrito->incluir(null);

          if ( $oDaoEscrito->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$oDaoEscrito->erro_msg}");
          }

   			}

   			$iSeqAtiv = 0;

   			foreach ( $oDadosEvento['aAtividades'] as $iIndAtiv => $oAtividade ) {


   				if ( trim($oAtividade->iCodAtividade) != '' ) {

   					$iCodAtividade = $oAtividade->iCodAtividade;

            $sWhereCnaeAnalitica = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
            $sSqlCnaeAnalitica   = $oDaoCnaeAnalitica->sql_query(null,"q72_sequencial",null,$sWhereCnaeAnalitica);
            $rsCnaeAnalitica     = $oDaoCnaeAnalitica->sql_record($sSqlCnaeAnalitica);

            if ( $oDaoCnaeAnalitica->numrows > 0 ) {
              $iCodCnaeAnalitica = db_utils::fieldsMemory($rsCnaeAnalitica,0)->q72_sequencial;
            } else {
              throw new Exception("{$sMsgErro}, CNAE não cadastrado!");
            }

            $rsAtividCnae = $oDaoAtividCnae->sql_record($oDaoAtividCnae->sql_query_file($iCodCnaeAnalitica,$iCodAtividade));

            if ( $oDaoAtividCnae->numrows ==  0 ) {
	            $oDaoAtividCnae->q74_ativid        = $iCodAtividade;
	            $oDaoAtividCnae->q74_cnaeanalitica = $iCodCnaeAnalitica;
	            $oDaoAtividCnae->incluir($iCodCnaeAnalitica,$iCodAtividade);

	            if ( $oDaoAtividCnae->erro_status == 0 ) {
	              throw new Exception("{$sMsgErro}\n{$oDaoAtividCnae->erro_msg}");
	            }
            }

   				} else {

	          $sWhereAtividade       = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
	          $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae(null,"q03_ativ",null,$sWhereAtividade);
	          $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);

	          if ( $oDaoAtivid->numrows > 0 ) {

	            $iCodAtividade = db_utils::fieldsMemory($rsAtividade,0)->q03_ativ;

	          } else {

	            $rsUltimoAtiv  = $oDaoAtivid->sql_record($oDaoAtivid->sql_query_file(null,"max(q03_ativ) as maxativ"));
	            $iCodAtividade = ( db_utils::fieldsMemory($rsUltimoAtiv,0)->maxativ + 1 );

	            if ( trim($oAtividade->q106_descricao) == '' ) {

	              $sWhereDescrCnae = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
	              $rsDescrCnae     = $oDaoCnae->sql_record($oDaoCnae->sql_query_file(null,"q71_descr",null,$sWhereDescrCnae));

	              if ( $oDaoCnae->numrows > 0 ) {
	                $oCnae = db_utils::fieldsMemory($rsDescrCnae,0);
	                $sDescrAtividade = substr($oCnae->q71_descr,0,40);
	                $sObsAtiv        = $oCnae->q71_descr;
	              } else {
	                throw new Exception("{$sMsgErro}\nCNAE:{$oAtividade->q106_cnae} não cadastrado!");
	              }

	            } else {
	              $sDescrAtividade = substr($oAtividade->q106_descricao,0,40);
	              $sObsAtiv        = '';
	            }

	            $oDaoAtivid->q03_ativ   = $iCodAtividade;
	            $oDaoAtivid->q03_descr  = $sDescrAtividade;
	            $oDaoAtivid->q03_atmemo = $sObsAtiv;

	            $oDaoAtivid->incluir($iCodAtividade);

	            if ( $oDaoAtivid->erro_status == 0 ) {
	              throw new Exception("{$sMsgErro}\n{$oDaoAtivid->erro_msg}");
	            }

	            $sWhereCnaeAnalitica = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
	            $sSqlCnaeAnalitica   = $oDaoCnaeAnalitica->sql_query(null,"q72_sequencial",null,$sWhereCnaeAnalitica);
	            $rsCnaeAnalitica     = $oDaoCnaeAnalitica->sql_record($sSqlCnaeAnalitica);

	            if ( $oDaoCnaeAnalitica->numrows > 0 ) {
	              $iCodCnaeAnalitica = db_utils::fieldsMemory($rsCnaeAnalitica,0)->q72_sequencial;
	            } else {
	              throw new Exception("{$sMsgErro}, CNAE não cadastrado!");
	            }

	            $oDaoAtividCnae->q74_ativid        = $iCodAtividade;
	            $oDaoAtividCnae->q74_cnaeanalitica = $iCodCnaeAnalitica;
	            $oDaoAtividCnae->incluir($iCodCnaeAnalitica,$iCodAtividade);

	            if ( $oDaoAtividCnae->erro_status == 0 ) {
	              throw new Exception("{$sMsgErro}\n{$oDaoAtividCnae->erro_msg}");
	            }
	          }
   				}

  			  $oDaoTabAtiv->q07_inscr  = $iCodInscricao;
  			  $oDaoTabAtiv->q07_ativ   = $iCodAtividade;
  			  $oDaoTabAtiv->q07_seq    = ++$iSeqAtiv;
  			  $oDaoTabAtiv->q07_tipbx  = '0';
  			  $oDaoTabAtiv->q07_perman = 't';
  			  $oDaoTabAtiv->q07_quant  = 1;
  			  $oDaoTabAtiv->q07_datain = $oDadosEvento['oEvento']->dtData;

  			  $oDaoTabAtiv->incluir($iCodInscricao,$iSeqAtiv);

          if ( $oDaoTabAtiv->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$oDaoTabAtiv->erro_msg}");
          }


          if ( $oAtividade->q106_principal == 't') {

          	$oDaoAtivPrinc->q88_inscr = $iCodInscricao;
          	$oDaoAtivPrinc->q88_seq   = $oDaoTabAtiv->q07_seq;
          	$oDaoAtivPrinc->incluir($iCodInscricao);

            if ( $oDaoAtivPrinc->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoAtivPrinc->erro_msg}");
            }

          }

  			}

        $oDaoMeiCgm->q115_numcgm     = $oCgmEmpresa->getCodigo();
        $oDaoMeiCgm->q115_meisitucao = 1;
        $oDaoMeiCgm->incluir(null);

   		  if ( $oDaoMeiCgm->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}, {$oDaoMeiCgm->erro_msg}");
        }


        foreach ( $aRegProcessa as $iSeqReg ) {

	        $oDaoMeiProcessaRegMeiCgm->q118_meicgm         = $oDaoMeiCgm->q115_sequencial;
	        $oDaoMeiProcessaRegMeiCgm->q118_meiprocessareg = $iSeqReg;
	        $oDaoMeiProcessaRegMeiCgm->incluir(null);

	   		  if ( $oDaoMeiProcessaRegMeiCgm->erro_status == 0 ) {
	          throw new Exception("{$sMsgErro}, {$oDaoMeiProcessaRegMeiCgm->erro_msg}");
	        }

        }

      } else if (  $sCodEvento == '203' ) {

        require_once modification("model/CgmFactory.model.php");

        $iCodCgm = $this->getCgmByCpfCnpj($iCnpj);

        if ( trim($iCodCgm) == ''   ) {
          throw new Exception("{$sMsgErro}\nCGM não encontrado para o CNPJ :{$iCnpj}");
        }

        try {

          $oCgmEmpresa = CgmFactory::getInstanceByCgm($iCodCgm);
          $GLOBALS["HTTP_POST_VARS"]["z01_nomefanta"] = '';
          $oCgmEmpresa->setNomeFantasia('');
          $oCgmEmpresa->save();

        } catch (Exception $eException) {
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
        }


   		} else if (  $sCodEvento == '211' ) {


        require_once modification("model/CgmFactory.model.php");
        $oDadosEmpresa = $oDadosEvento['oEmpresa'];

        $iCodCgm = $this->getCgmByCpfCnpj($iCnpj);

        if ( trim($iCodCgm) == ''   ) {
          throw new Exception("{$sMsgErro}\nCGM não encontrado para o CNPJ :{$iCnpj}");
        }

        try {

          $oCgmEmpresa = CgmFactory::getInstanceByCgm($iCodCgm);

          $oCgmEmpresa->setMunicipio         ($oDadosEmpresa->q107_municipio);
          $oCgmEmpresa->setLogradouro        ($oDadosEmpresa->q107_logradouro);
          $oCgmEmpresa->setNumero            ($oDadosEmpresa->q107_numero);
          $oCgmEmpresa->setComplemento       ($oDadosEmpresa->q107_complemento);
          $oCgmEmpresa->setBairro            ($oDadosEmpresa->q107_bairro);
          $oCgmEmpresa->setUf                ($oDadosEmpresa->q107_uf);
          $oCgmEmpresa->setCep               ($oDadosEmpresa->q107_cep);
          $oCgmEmpresa->setTelefone          ($oDadosEmpresa->q107_telefone);
          $oCgmEmpresa->setTelefoneComercial ($oDadosEmpresa->q107_telefonecomercial);
          $oCgmEmpresa->setFax               ($oDadosEmpresa->q107_fax);
          $oCgmEmpresa->setEmail             ($oDadosEmpresa->q107_email);
          $oCgmEmpresa->setCaixaPostal       ($oDadosEmpresa->q107_caixapostal);

          $oCgmEmpresa->save();

        } catch (Exception $eException) {
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
        }


      } else if (  $sCodEvento == '220' ) {


        require_once modification("model/CgmFactory.model.php");
        $oDadosEmpresa = $oDadosEvento['oEmpresa'];

        $iCodCgm = $this->getCgmByCpfCnpj($iCnpj);

        if ( trim($iCodCgm) == ''   ) {
          throw new Exception("{$sMsgErro}\nCGM não encontrado!");
        }

        try {

          $oCgmEmpresa = CgmFactory::getInstanceByCgm($iCodCgm);
          $oCgmEmpresa->setNome($oDadosEmpresa->q107_nome);
          $oCgmEmpresa->save();

        } catch (Exception $eException) {
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
        }
      } else if (  $sCodEvento == '221' ) {


        require_once modification("model/CgmFactory.model.php");
        $oDadosEmpresa = $oDadosEvento['oEmpresa'];

      	$iCodCgm = $this->getCgmByCpfCnpj($iCnpj);

        if ( trim($iCodCgm) == ''   ) {
          throw new Exception("{$sMsgErro}\nCGM não encontrado!");
        }

        try {

          $oCgmEmpresa = CgmFactory::getInstanceByCgm($iCodCgm);
          $oCgmEmpresa->setNomeFantasia($oDadosEmpresa->q107_nomefantasia);
          $oCgmEmpresa->save();

        } catch (Exception $eException) {
          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
        }



      } else if (  $sCodEvento == '232' ) {


        require_once modification("model/CgmFactory.model.php");
        $oDadosContador = $oDadosEvento['oContador'];

        $oDaoCadEscrito = db_utils::getDao('cadescrito');
        $oDaoEscrito    = db_utils::getDao('escrito');

        $iCodCgmContador = $this->getCgmByCpfCnpj($oDadosContador->q109_cnpjcpf);

        if ( trim($iCodCgmContador) == '' ) {

	        try {

	          if ( strlen(trim($oDadosContador->q109_cnpjcpf)) == '14' ) {
	            $oCgmContador = CgmFactory::getInstanceByType(2);
	            $oCgmContador->setCnpj($oDadosContador->q109_cnpjcpf);
	          } else {
	            $oCgmContador = CgmFactory::getInstanceByType(1);
	            $oCgmContador->setCpf($oDadosContador->q109_cnpjcpf);
	          }

	          $oCgmContador->setNome        ($oDadosContador->q109_nome);
	          $oCgmContador->setMunicipio   ($oDadosContador->q109_municipio);
	          $oCgmContador->setLogradouro  ($oDadosContador->q109_logradouro);
	          $oCgmContador->setNumero      ($oDadosContador->q109_numero);
	          $oCgmContador->setComplemento ($oDadosContador->q109_complemento);
	          $oCgmContador->setBairro      ($oDadosContador->q109_bairro);
	          $oCgmContador->setUf          ($oDadosContador->q109_uf);
	          $oCgmContador->setCep         ($oDadosContador->q109_cep);
	          $oCgmContador->setTelefone    ($oDadosContador->q109_telefone);
	          $oCgmContador->setFax         ($oDadosContador->q109_fax);
	          $oCgmContador->setEmail       ($oDadosContador->q109_email);

	          $oCgmContador->save();

	        } catch (Exception $eException) {
	          throw new Exception("{$sMsgErro}\n{$eException->getMessage()}");
	        }

	        $iCodCgmContador = $oCgmContador->getCodigo();

        }

        $rsCadEscrito = $oDaoCadEscrito->sql_record($oDaoCadEscrito->sql_query_file($iCodCgmContador));

        if ( $oDaoCadEscrito->numrows == 0 ) {

	        $oDaoCadEscrito->q86_numcgm = $iCodCgmContador;
	        $oDaoCadEscrito->incluir($iCodCgmContador);

	        if ( $oDaoCadEscrito->erro_status == 0 ) {
	          throw new Exception("{$sMsgErro}\n{$oDaoCadEscrito->erro_msg}");
	        }

        }


        $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

        $sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa}";
        $sWhereIssBase .= " and q02_dtbaix is null            ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows == 0 ) {
        	throw new Exception("{$sMsgErro},Inscrição não encontrada!");
        } else {
        	$iCodInscricao = db_utils::fieldsMemory($rsIssBase,0)->q02_inscr;
        }

        $rsEscrito    = $oDaoEscrito->sql_record($oDaoEscrito->sql_query(null,"*",null,"q10_inscr = {$iCodInscricao} "));
        $iRowsEscrito = $oDaoEscrito->numrows;

        if ( $iRowsEscrito > 0  ) {

        	for ( $iIndEscrito=0; $iIndEscrito < $iRowsEscrito; $iIndEscrito++ ) {

        		$oEscrito = db_utils::fieldsMemory($rsEscrito,$iIndEscrito);
        		$oDaoEscrito->q10_dtfim = date('Y-m-d',db_getsession('DB_datausu'));
        		$oDaoEscrito->alterar($oEscrito->q10_sequencial);

        		if ( $oDaoEscrito->erro_status == 0 ) {
        			throw new Exception("{$sMsgErro}\n{$oDaoEscrito->erro_msg}");
        		}

        	}

        }

        $oDaoEscrito->q10_inscr  = $iCodInscricao;
        $oDaoEscrito->q10_numcgm = $iCodCgmContador;
        $oDaoEscrito->incluir(null);

        if ( $oDaoEscrito->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$oDaoEscrito->erro_msg}");
        }


      } else if (  $sCodEvento == '244' ) {


        $oDaoAtivid        = db_utils::getDao('ativid');
        $oDaoAtividCnae    = db_utils::getDao('atividcnae');
        $oDaoCnaeAnalitica = db_utils::getDao('cnaeanalitica');
        $oDaoTabAtiv       = db_utils::getDao('tabativ');
        $oDaoIssBase       = db_utils::getDao('issbase');

        $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

        $sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa}";
        $sWhereIssBase .= " and q02_dtbaix is null            ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows == 0 ) {
          throw new Exception("{$sMsgErro},Inscrição não encontrada!");
        } else {
          $iCodInscricao = db_utils::fieldsMemory($rsIssBase,0)->q02_inscr;
        }

        $rsTabAtiv    = $oDaoTabAtiv->sql_record($oDaoTabAtiv->sql_query_file($iCodInscricao));
        $iRowsTabAtiv = $oDaoTabAtiv->numrows;

        if ( $iRowsTabAtiv > 0 ) {

        	for ( $iIndTabAtiv=0; $iIndTabAtiv < $iRowsTabAtiv; $iIndTabAtiv++ ) {

        		$oTabAtiv = db_utils::fieldsMemory($rsTabAtiv,$iIndTabAtiv);

        		$oDaoTabAtiv->q07_inscr  = $iCodInscricao;
        		$oDaoTabAtiv->q07_seq    = $oTabAtiv->q07_seq;
        		$oDaoTabAtiv->q07_databx = date('Y-m-d',db_getsession('DB_datausu'));
        		$oDaoTabAtiv->alterar($iCodInscricao,$oTabAtiv->q07_seq);

        	  if ( $oDaoTabAtiv->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoTabAtiv->erro_msg}");
            }

        	}

        }

        $iSeqAtiv = $iRowsTabAtiv;

        foreach ( $oDadosEvento['aAtividades'] as $iIndAtiv => $oAtividade ) {


          $sWhereAtividade       = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
          $sSqlConsultaAtividade = $oDaoAtivid->sql_query_cnae(null,"q03_ativ",null,$sWhereAtividade);
          $rsAtividade           = $oDaoAtivid->sql_record($sSqlConsultaAtividade);

          if ( $oDaoAtivid->numrows > 0 ) {

            $iCodAtividade = db_utils::fieldsMemory($rsAtividade,0)->q03_ativ;

          } else {

            $rsUltimoAtiv  = $oDaoAtivid->sql_record($oDaoAtivid->sql_query_file(null,"max(q03_ativ) as maxativ"));
            $iCodAtividade = ( db_utils::fieldsMemory($rsUltimoAtiv,0)->maxativ + 1 );

            $oDaoAtivid->q03_ativ  = $iCodAtividade;
            $oDaoAtivid->q03_descr = substr($oAtividade->q106_descricao,0,40);
            $oDaoAtivid->incluir($iCodAtividade);

            if ( $oDaoAtivid->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoAtivid->erro_msg}");
            }

            $sWhereCnaeAnalitica = " q71_estrutural like '%{$oAtividade->q106_cnae}'";
            $sSqlCnaeAnalitica   = $oDaoCnaeAnalitica->sql_query(null,"q72_sequencial",null,$sWhereCnaeAnalitica);
            $rsCnaeAnalitica     = $oDaoCnaeAnalitica->sql_record($sSqlCnaeAnalitica);

            if ( $oDaoCnaeAnalitica->numrows > 0 ) {
              $iCodCnaeAnalitica = db_utils::fieldsMemory($rsCnaeAnalitica,0)->q72_sequencial;
            } else {
              throw new Exception("{$sMsgErro}, CNAE não cadastrado!");
            }

            $oDaoAtividCnae->q74_ativid        = $iCodAtividade;
            $oDaoAtividCnae->q74_cnaeanalitica = $iCodCnaeAnalitica;
            $oDaoAtividCnae->incluir($iCodCnaeAnalitica,$iCodAtividade);

            if ( $oDaoAtividCnae->erro_status == 0 ) {
              throw new Exception("{$sMsgErro}\n{$oDaoAtividCnae->erro_msg}");
            }

          }

          $oDaoTabAtiv->q07_inscr  = $iCodInscricao;
          $oDaoTabAtiv->q07_ativ   = $iCodAtividade;
          $oDaoTabAtiv->q07_seq    = ++$iSeqAtiv;
          $oDaoTabAtiv->q07_tipbx  = '0';
          $oDaoTabAtiv->q07_perman = 't';
          $oDaoTabAtiv->q07_quant  = 1;
          $oDaoTabAtiv->q07_databx = "";

          $oDaoTabAtiv->incluir($iCodInscricao,$iSeqAtiv);

          if ( $oDaoTabAtiv->erro_status == 0 ) {
            throw new Exception("{$sMsgErro}\n{$oDaoTabAtiv->erro_msg}");
          }

        }



      } else if (  $sCodEvento == '247' ) {


      	$oDadosEmpresa = $oDadosEvento['oEmpresa'];
        $oDaoIssBase    = db_utils::getDao('issbase');

        $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

        $sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa}";
        $sWhereIssBase .= " and q02_dtbaix is null            ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows == 0 ) {
          throw new Exception("{$sMsgErro},Inscrição não encontrada!");
        } else {
          $iCodInscricao = db_utils::fieldsMemory($rsIssBase,0)->q02_inscr;
        }

        $oDaoIssBase->q02_inscr = $iCodInscricao;
        $oDaoIssBase->q02_capit = $oDadosEmpresa->q107_capitalsocial;
        $oDaoIssBase->alterar($iCodInscricao);
        if ( $oDaoIssBase->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}\n{$oDaoIssBase->erro_msg}");
        }


      } else if ( $sCodEvento == '517' || $sCodEvento == '570' ) {

      	require_once(modification("model/logBaixaAlvara.model.php"));
        $oDaoLogBaixaAlvara = new logbaixaalvara();

        $oDaoTabAtiv              = db_utils::getDao('tabativ');
        $oDaoIssBase              = db_utils::getDao('issbase');
        $oDaoArreInscr            = db_utils::getDao('arreinscr');
        $oDaoCertBaixaNumero      = db_utils::getDao('certbaixanumero');
        $oDaoTabAtivBaixa         = db_utils::getDao('tabativbaixa');
        $oDaoAtivPrinc            = db_utils::getDao('ativprinc');
        $oDaoMeiCgm               = db_utils::getDao('meicgm');
        $oDaoMeiProcessaRegMeiCgm = db_utils::getDao('meiprocessaregmeicgm');

        $iCodCgmEmpresa = $this->getCgmByCpfCnpj($iCnpj);

        $sWhereIssBase  = "     q02_numcgm = {$iCodCgmEmpresa}";
        $sWhereIssBase .= " and q02_dtbaix is null            ";
        $sSqlIssBase    = $oDaoIssBase->sql_query_file(null,"q02_inscr",null,$sWhereIssBase);
        $rsIssBase      = $oDaoIssBase->sql_record($sSqlIssBase);

        if ( $oDaoIssBase->numrows == 0 ) {
          throw new Exception("{$sMsgErro},Inscrição não encontrada!");
        } else {
          $iCodInscricao = db_utils::fieldsMemory($rsIssBase,0)->q02_inscr;
        }

        $iAnoUsu   = db_getsession('DB_anousu');
        $dtDataUsu = date('Y-m-d',db_getsession('DB_datausu'));

		    $sWhereArreinscr  = "     k00_inscr = {$iCodInscricao}  ";
        $sWhereArreinscr .= " and k00_dtvenc < '$dtDataUsu'     ";
        $sWhereArreinscr .= " and k03_tipo not in (2,9,19)      ";
		    $sSqlArreinscr    = $oDaoArreInscr->sql_query_arrecad("","","arrecad.k00_numpre","",$sWhereArreinscr);
		    $rsArreInscr      = $oDaoArreInscr->sql_record($sSqlArreinscr);

		    if ( $oDaoArreInscr->numrows > 0 ) {
	        throw new Exception("{$sMsgErro}, Existem débitos para este contribuinte!");
		    } else {

		      // verificar se tem debitos do tipo 3 vencidos
		      $sSqlDebitos = " select arrecad.k00_numpre
										         from arreinscr
													        inner join issbase on issbase.q02_inscr = arreinscr.k00_inscr
													        inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm
													        inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
													        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
										        where k00_inscr= {$iCodInscricao}
													    and k00_dtvenc < current_date
													    and k03_tipo = 3";

		      $rsDebitos    = db_query($sSqlDebitos);
		      $iRowsDebitos = pg_num_rows($rsDebitos);

		      if( $iRowsDebitos > 0 ){
		      	throw new Exception("{$sMsgErro}, Existem débitos para este contribuinte!");
		      }

		    }

		    $sWhereCertBaixaNumero = " q79_anousu = {$iAnoUsu}";
		    $sSqlCertBaixaNumero   = $oDaoCertBaixaNumero->sql_query_file(null,"*",null,$sWhereCertBaixaNumero);
        $rsCertBaixaNumero     = $oDaoCertBaixaNumero->sql_record($sSqlCertBaixaNumero);

        if ( $oDaoCertBaixaNumero->numrows > 0 ){
          $iSeqCertBaixa    = (db_utils::fieldsMemory($rsCertBaixaNumero,0)->q79_sequencial);
          $iNumeroCertBaixa = (db_utils::fieldsMemory($rsCertBaixaNumero,0)->q79_ultcodcertbaixa+1);
        } else {
          throw new Exception("{$sMsgErro},Código de certidão de baixa não encontrado!");
        }

        $rsTabAtiv    = $oDaoTabAtiv->sql_record($oDaoTabAtiv->sql_query_atividade_inscr($iCodInscricao));
        $iRowsTabAtiv = $oDaoTabAtiv->numrows;

        if ( $iRowsTabAtiv > 0 ) {

          for ( $iIndTabAtiv=0; $iIndTabAtiv < $iRowsTabAtiv; $iIndTabAtiv++ ) {

            $oTabAtiv = db_utils::fieldsMemory($rsTabAtiv,$iIndTabAtiv);

	          if ( $sCodEvento == '570') {
		          $oDaoTabAtivBaixa->q11_oficio = 'true';
	          } else {
	          	$oDaoTabAtivBaixa->q11_oficio = 'false';
	          }

	          $oDaoTabAtivBaixa->q11_login    = db_getsession("DB_id_usuario");
	          $oDaoTabAtivBaixa->q11_hora     = db_hora();
	          $oDaoTabAtivBaixa->q11_data     = $oDadosEvento['oEvento']->dtData;
	          $oDaoTabAtivBaixa->q11_obs      = 'Exclusão do MEI por Arquivo';
	          $oDaoTabAtivBaixa->q11_processo = 'null';
	          $oDaoTabAtivBaixa->q11_seq      = $oTabAtiv->q07_seq;
	          $oDaoTabAtivBaixa->q11_inscr    = $iCodInscricao;
	          $oDaoTabAtivBaixa->q11_numero   = $iNumeroCertBaixa;
	          $oDaoTabAtivBaixa->incluir( $iCodInscricao, $oTabAtiv->q07_seq );

	          if ( $oDaoTabAtivBaixa->erro_status == 0 ) {
	            throw new Exception("{$sMsgErro},{$oDaoTabAtivBaixa->erro_msg}");
	          }

            $oDaoCertBaixaNumero->q79_sequencial      = $iSeqCertBaixa;
	          $oDaoCertBaixaNumero->q79_ultcodcertbaixa = $iNumeroCertBaixa;
	          $oDaoCertBaixaNumero->alterar($iSeqCertBaixa);

	          if ( $oDaoCertBaixaNumero->erro_status == 0 ) {
              throw new Exception("{$sMsgErro},{$oDaoCertBaixaNumero->erro_msg}");
	          }

	          $oDaoTabAtiv->q07_seq    = $oTabAtiv->q07_seq;
	          $oDaoTabAtiv->q07_inscr  = $iCodInscricao;
	          $oDaoTabAtiv->q07_datafi = $oDadosEvento['oEvento']->dtData;
	          $oDaoTabAtiv->q07_databx = $oDadosEvento['oEvento']->dtData;
	          $oDaoTabAtiv->alterar( $iCodInscricao, $oTabAtiv->q07_seq );

	          if ( $oDaoTabAtiv->erro_status == 0 ) {
	            throw new Exception("{$sMsgErro},{$oDaoTabAtiv->erro_msg}");
	          }

	          if ( trim($oTabAtiv->q88_inscr) != '' ) {

	            $oDaoAtivPrinc->q88_inscr = $iCodInscricao;
	            $oDaoAtivPrinc->q88_seq   = $oTabAtiv->q07_seq;
	            $oDaoAtivPrinc->excluir($iCodInscricao);

	            if( $oDaoAtivPrinc->erro_status == 0 ){
	              throw new Exception("{$sMsgErro},{$oDaoAtivPrinc->erro_msg}");
	            }
	          }

            try {
              $oDaoLogBaixaAlvara->identificaAlteracao($iCodInscricao,1,7,$oTabAtiv->q07_ativ);
            } catch ( Exception $eException ){
              throw new Exception("{$sMsgErro},{$eException->getMessage()}");
            }

	        }

	        try {
	           $oDaoLogBaixaAlvara->gravarLog();
	        } catch ( Exception $eException ){
            throw new Exception("{$sMsgErro},{$eException->getMessage()}");
	        }

        }


	      $oDaoIssBase->q02_dtbaix = $oDadosEvento['oEvento']->dtData;
	      $oDaoIssBase->q02_inscr  = $iCodInscricao;
        $oDaoIssBase->alterar($iCodInscricao);

        if ( $oDaoIssBase->erro_status == 0 ) {
          throw new Exception("{$sMsgErro},{$oDaoIssBase->erro_msg}");
        }


        $rsMeiCgm = $oDaoMeiCgm->sql_record($oDaoMeiCgm->sql_query_file(null,"*",null,"q115_numcgm = {$iCodCgmEmpresa}"));

        if ( $oDaoMeiCgm->numrows > 0 ) {
        	$iSeqMeiCgm = db_utils::fieldsMemory($rsMeiCgm,0)->q115_sequencial;
        } else {
        	throw new Exception("{$sMsgErro}MEI do CGM : {$iCodCgmEmpresa} CNPJ: {$iCnpj} não encontrado!");
        }

        $sWhereProcessaRegMeiCgm = " q118_meicgm = {$iSeqMeiCgm} ";
        $oDaoMeiProcessaRegMeiCgm->excluir( null, $sWhereProcessaRegMeiCgm);

        if ( $oDaoMeiProcessaRegMeiCgm->erro_status == 0 ) {
          throw new Exception("{$sMsgErro},{$oDaoMeiProcessaRegMeiCgm->erro_msg}");
        }

        $sWhereMeiCgm = " q115_numcgm = {$iCodCgmEmpresa} ";
        $oDaoMeiCgm->excluir( null, $sWhereMeiCgm);

        if ( $oDaoMeiCgm->erro_status == 0 ) {
          throw new Exception("{$sMsgErro},{$oDaoMeiCgm->erro_msg}");
        }
   		} else {



        // if nao encontrou na lista {
			  //   throw new Exception("{$sMsgErro}Evento $sCodEvento não cadastrado!");
        // }
   		}
   	}


   	/*
   	 *
   	 * Verificamos se a inscrição possui ou não alvará lançado
   	 * Caso não exista alvará, este será gerado como automático
   	 *
   	 */
 	  if ( !empty($iCodInscricao) ) {

 	    $clIssAlvara    = db_utils::getDao("issalvara");
 	    $clIssMovAlvara = db_utils::getDao("issmovalvara");

 	    $sSqlExisteAlvara = $clIssAlvara->sql_query(null, "q123_sequencial", null, "q123_inscr = {$iCodInscricao} and q123_situacao in (1,2)");
      $rsExisteAlvara   = $clIssAlvara->sql_record($sSqlExisteAlvara);
      if ( $clIssAlvara->numrows == 0 ) {

        $rsTipoAlvara  = db_query("select q60_isstipoalvaraprov from parissqn");
        $iTipoAlvara   = db_utils::fieldsMemory($rsTipoAlvara,0)->q60_isstipoalvaraprov;

      	$clIssAlvara->q123_isstipoalvara = $iTipoAlvara;  // valor a partir da parissqn
      	$clIssAlvara->q123_inscr         = $iCodInscricao;
      	$clIssAlvara->q123_dtinclusao    = date('Y-m-d', db_getsession("DB_datausu"));
      	$clIssAlvara->q123_situacao      = 1;
      	$clIssAlvara->q123_usuario       = db_getsession("DB_id_usuario");
      	$clIssAlvara->q123_geradoautomatico = "true";
      	$clIssAlvara->incluir(null);
      	if($clIssAlvara->erro_status == '0'){
      		throw new Exception($clIssAlvara->erro_msg);
      	}

      	$clIssMovAlvara->q120_codproc          = "";
      	$clIssMovAlvara->q120_issalvara        = $clIssAlvara->q123_sequencial;
      	$clIssMovAlvara->q120_isstipomovalvara = 1 ;// liberação
      	$clIssMovAlvara->q120_dtmov            = date('Y-m-d', db_getsession("DB_datausu"));
      	$clIssMovAlvara->q120_validadealvara   = 180;
      	$clIssMovAlvara->q120_usuario          = db_getsession("DB_id_usuario");
      	$clIssMovAlvara->q120_obs              = "GERACAO AUTOMATICA";
      	$clIssMovAlvara->incluir(null);
      	if($clIssMovAlvara->erro_status == '0'){
      		throw new Exception($clIssMovAlvara->erro_msg);
      	}
      }

      /**
       * Cadastramos o contribuinte como MEI na estrutura do cadastro do simples nacional
       */
      $oDaoIssCadSimples      = new cl_isscadsimples;
      $oDaoIssCadSimplesBaixa = new cl_isscadsimplesbaixa;

      if ( empty($sDataEvento) ) {
        $sDataEvento = date('Y-m-d', db_getsession("DB_datausu"));
      }

      $lInserirMei   = false;
      $lInserirBaixa = false;

      /**
       * Verificamos se o contribuinte é simples nacional
       */
      $sWhereSimples     = "     q38_inscr     = {$iCodInscricao}                       ";
      $sWhereSimples    .= " and (q39_dtbaixa is null or q39_dtbaixa > '{$sDataEvento}') ";
      $sSqlSimples       = $oDaoIssCadSimples->sql_query_baixa(null, "*", "q38_sequencial desc", $sWhereSimples);
      $rsSqlSimples      = db_query($sSqlSimples);
      $iRegistrosSimples = pg_num_rows($rsSqlSimples);

      if (empty($rsSqlSimples)) {
        throw new DBException("Erro ao consultar contribuinte no cadastro do simples nacional.");
      }

      /**
       * Caso não seja, devemos inclui-lo como MEI
       */
      if (empty($iRegistrosSimples)) {
        $lInserirMei = true;
      }

      if ( $sCodEvento == 517 || $sCodEvento == 570 ) {

        $lInserirBaixa = true;
        $lInserirMei   = false;
      }

      if ( !$lInserirMei && !$lInserirBaixa ){

        $oSimples = db_utils::fieldsMemory($rsSqlSimples, 0);

        /**
         * Se o contribuinte está numa categoria diferente de MEI, devemos realizar a baixa
         */
        if ($oSimples->q38_categoria != self::SIMPLES_NACIONAL_MEI) {

          /**
           * Após a baixa, devemos inseri-lo novamente no simples, porém como MEI desta vez
           */
          $lInserirMei = true;

          /**
           * Excluir alguma baixa já existente, para não haver conflitos
           */
          $sWhereBaixa = "q39_isscadsimples = {$oSimples->q38_sequencial}";
          $lBaixa      = $oDaoIssCadSimplesBaixa->excluir(null, $sWhereBaixa);

          if (!$lBaixa) {
            throw new DBException("Erro ao excluir baixa do simples nacional.");
          }

          /**
           * Inserimos a nova baxa
           */
          $sObservacao = "Baixa realizada pelo processamento do arquivo do MEI.";

          $oDaoIssCadSimplesBaixa->q39_isscadsimples  = $oSimples->q38_sequencial;
          $oDaoIssCadSimplesBaixa->q39_dtbaixa        = $sDataEvento;
          $oDaoIssCadSimplesBaixa->q39_issmotivobaixa = self::MOTIVO_BAIXA_OFICIO;
          $oDaoIssCadSimplesBaixa->q39_obs            = $sObservacao;
          $lInseriu = $oDaoIssCadSimplesBaixa->incluir(null);

          if (!$lInseriu) {
            throw new DBException("Erro ao inserir baixa no simples nacional.");
          }
        }
      }

      if ( $lInserirMei ) {

        /**
         * Inserimos o contribuinte no simples nacional como mei
         */
        $oDaoIssCadSimples->q38_inscr     = $iCodInscricao;
        $oDaoIssCadSimples->q38_dtinicial = $sDataEvento;
        $oDaoIssCadSimples->q38_categoria = self::SIMPLES_NACIONAL_MEI;
        $lInseriu = $oDaoIssCadSimples->incluir(null);

        if ( !$lInseriu ) {
          throw new DBException("Erro ao inserir contribuinte no simples nacional como MEI.");
        }
      }

      if ($lInserirBaixa && !empty($iRegistrosSimples) ) {

        $oSimples = db_utils::fieldsMemory($rsSqlSimples, 0);

        /**
         * Inserimos a nova baxa
         */
        $sObservacao = "Baixa realizada pelo processamento do arquivo do MEI.";

        $oDaoIssCadSimplesBaixa->q39_isscadsimples  = $oSimples->q38_sequencial;
        $oDaoIssCadSimplesBaixa->q39_dtbaixa        = $sDataEvento;
        $oDaoIssCadSimplesBaixa->q39_issmotivobaixa = self::MOTIVO_BAIXA_OFICIO;
        $oDaoIssCadSimplesBaixa->q39_obs            = $sObservacao;
        $lInseriu = $oDaoIssCadSimplesBaixa->incluir(null);

        if (!$lInseriu) {
          throw new DBException("Erro ao inserir baixa no simples nacional.");
        }
      }
 	  }
  }


  function descartaMeiArquivo( $iCnpj='', $sCodEvento='', $sMotivo='', $iCodProcessa='' ){

    $sMsgErro = 'Processamento do arquivo do MEI abortado,\n';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}CNPJ do MEI não informado!");
    }

    $oDaoMeiImporta     = db_utils::getDao('meiimporta');
    $oDaoMeiProcessa    = db_utils::getDao('meiprocessa');
    $oDaoMeiProcessaReg = db_utils::getDao('meiprocessareg');

    if ( trim($iCodProcessa) == '' ) {

      $oDaoMeiProcessa->q113_id_usuario  = db_getsession('DB_id_usuario');
      $oDaoMeiProcessa->q113_data        = date('Y-m-d',db_getsession('DB_datausu'));
      $oDaoMeiProcessa->q113_hora        = db_hora();
      $oDaoMeiProcessa->incluir(null);

      if ( $oDaoMeiProcessa->erro_status == 0  ) {
      	throw new Exception("{$sMsgErro}{$oDaoMeiProcessa->erro_msg}");
      }

    	$iCodProcessa = $oDaoMeiProcessa->q113_sequencial;

    }

    $sWhereImporta  = $this->sWhereImporta;
    $sWhereImporta .= " and q105_cnpj = '{$iCnpj}' ";

    if ( trim($sCodEvento) != '' ) {
      $sWhereImporta .= " and q101_codigo = '{$sCodEvento}' ";
    }

    $sSqlImporta    = $oDaoMeiImporta->sql_query_reg(null,"*",null,$sWhereImporta);
    $rsMeiImporta   = $oDaoMeiImporta->sql_record($sSqlImporta);
    $iRowsImporta   = pg_num_rows($rsMeiImporta);

    if ( $iRowsImporta > 0 ) {

    	for ( $iInd=0; $iInd < $iRowsImporta; $iInd++ ) {

    		$oDadosImporta = db_utils::fieldsMemory($rsMeiImporta,$iInd);

    		$oDaoMeiProcessaReg->q112_meiprocessa      = $iCodProcessa;
    		$oDaoMeiProcessaReg->q112_meiimportameireg = $oDadosImporta->q111_sequencial;
    		$oDaoMeiProcessaReg->q112_tipoprocessa     = 2;
    		$oDaoMeiProcessaReg->q112_motivo           = $sMotivo;
    		$oDaoMeiProcessaReg->incluir(null);

    		if ( $oDaoMeiProcessaReg->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}{$oDaoMeiProcessaReg->erro_msg}");
    		}
    	}
    }
  }


  function processaMeiArquivoLote( $aListaProcessa=array() ){

    $sMsgErro = 'Processamento em lote do arquivo do MEI abortado,\n';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( empty($aListaProcessa) ) {
      throw new Exception("{$sMsgErro}Nenhum registro informado!");
    }

    $oDaoMeiProcessa = db_utils::getDao('meiprocessa');

    $oDaoMeiProcessa->q113_id_usuario  = db_getsession('DB_id_usuario');
    $oDaoMeiProcessa->q113_data        = date('Y-m-d',db_getsession('DB_datausu'));
    $oDaoMeiProcessa->q113_hora        = db_hora();
    $oDaoMeiProcessa->incluir(null);

    if ( $oDaoMeiProcessa->erro_status == 0  ) {
      throw new Exception("{$sMsgErro}{$oDaoMeiProcessa->erro_msg}");
    }

    $iCodProcessa = $oDaoMeiProcessa->q113_sequencial;

    try {

      foreach ( $aListaProcessa as $oMeiDados ) {

        if ( $oMeiDados->lDescarta ) {

          $this->descartaMeiArquivo( $oMeiDados->iCnpj,
                                     $oMeiDados->sCodEvento,
                                     $oMeiDados->sMotivo,
                                     $iCodProcessa );

        } else {

          $this->processaMeiArquivo( $oMeiDados->iCnpj,
                                     $oMeiDados->sCodEvento,
                                     $iCodProcessa );
        }

      }

    } catch (Exception $eException) {
      throw new Exception($eException->getMessage());
    }

  }


  function getCgmByCpfCnpj($iCnpj){

    $oDaoCgm = db_utils::getDao('cgm');

    $sWhereCgm = " z01_cgccpf = '{$iCnpj}'";
    $sSqlCgm   = $oDaoCgm->sql_query_file(null,"z01_numcgm",null,$sWhereCgm);
    $rsCgm     = $oDaoCgm->sql_record($sSqlCgm);

    if ( $oDaoCgm->numrows > 0 ) {
    	$iNumCgm = db_utils::fieldsMemory($rsCgm,0)->z01_numcgm;
    } else {
    	$iNumCgm = null;
    }

    return $iNumCgm;

  }


  function vinculaMeiCgm( $iCnpj='', $sCodEvento='' ){

    $sMsgErro = 'Vinculação do MEI abortada,\n';

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( trim($iCnpj) == '' ) {
      throw new Exception("{$sMsgErro}CNPJ do MEI não informado!");
    }

    if ( trim($sCodEvento) == '' ) {
      throw new Exception("{$sMsgErro}Evento não informado!");
    }

    $oDaoMeiCgm               = db_utils::getDao('meicgm');
    $oDaoMeiImporta           = db_utils::getDao('meiimporta');
    $oDaoMeiProcessa          = db_utils::getDao('meiprocessa');
    $oDaoMeiProcessaReg       = db_utils::getDao('meiprocessareg');
    $oDaoMeiProcessaRegMeiCgm = db_utils::getDao('meiprocessaregmeicgm');

    $oDaoMeiProcessa->q113_id_usuario  = db_getsession('DB_id_usuario');
    $oDaoMeiProcessa->q113_data        = date('Y-m-d',db_getsession('DB_datausu'));
    $oDaoMeiProcessa->q113_hora        = db_hora();
    $oDaoMeiProcessa->incluir(null);

    if ( $oDaoMeiProcessa->erro_status == 0  ) {
      throw new Exception("{$sMsgErro}{$oDaoMeiProcessa->erro_msg}");
    }

    $iCodProcessa = $oDaoMeiProcessa->q113_sequencial;

    $sWhereImporta  = $this->sWhereImporta;
    $sWhereImporta .= " and q105_cnpj   = '{$iCnpj}' ";
    $sWhereImporta .= " and q101_codigo = '{$sCodEvento}' ";

    $sSqlImporta    = $oDaoMeiImporta->sql_query_reg(null,"*",null,$sWhereImporta);
    $rsMeiImporta   = $oDaoMeiImporta->sql_record($sSqlImporta);
    $iRowsImporta   = pg_num_rows($rsMeiImporta);

    if ( $iRowsImporta > 0 ) {

      for ( $iInd=0; $iInd < $iRowsImporta; $iInd++ ) {

        $oDadosImporta = db_utils::fieldsMemory($rsMeiImporta,$iInd);

        $oDaoMeiProcessaReg->q112_meiprocessa      = $iCodProcessa;
        $oDaoMeiProcessaReg->q112_meiimportameireg = $oDadosImporta->q111_sequencial;
        $oDaoMeiProcessaReg->q112_tipoprocessa     = 3;
        $oDaoMeiProcessaReg->q112_motivo           = '';
        $oDaoMeiProcessaReg->incluir(null);

        if ( $oDaoMeiProcessaReg->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}{$oDaoMeiProcessaReg->erro_msg}");
        }

        $aRegProcessa[] = $oDaoMeiProcessaReg->q112_sequencial;

      }

      try {
        $aDadosMEI = $this->getDadosMEI($iCnpj);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      if ( isset($aDadosMEI['aEventos'][$sCodEvento]['oEmpresa']) ) {
	      $oDadosEmpresa  = $aDadosMEI['aEventos'][$sCodEvento]['oEmpresa'];
	      $iCodCgmEmpresa= $this->getCgmByCpfCnpj($oDadosEmpresa->q107_cnpj);
      } else {
     	  throw new Exception("{$sMsgErro}Dados da empresa não encontrado!");
      }

      $oDaoMeiCgm->q115_numcgm     = $iCodCgmEmpresa;
      $oDaoMeiCgm->q115_meisitucao = 1;
      $oDaoMeiCgm->incluir(null);

      if ( $oDaoMeiCgm->erro_status == 0 ) {
        throw new Exception("{$sMsgErro}, {$oDaoMeiCgm->erro_msg}");
      }

      foreach ( $aRegProcessa as $iSeqReg ) {

        $oDaoMeiProcessaRegMeiCgm->q118_meicgm         = $oDaoMeiCgm->q115_sequencial;
        $oDaoMeiProcessaRegMeiCgm->q118_meiprocessareg = $iSeqReg;
        $oDaoMeiProcessaRegMeiCgm->incluir(null);

        if ( $oDaoMeiProcessaRegMeiCgm->erro_status == 0 ) {
          throw new Exception("{$sMsgErro}, {$oDaoMeiProcessaRegMeiCgm->erro_msg}");
        }

      }

    } else {
    	throw new Exception("{$sMsgErro}Nenhum registro de importação encontrado!");
    }

  }


  function setCodRuaMEI($iCnpj='',$sCodEvento='',$iCodRua=''){

  	if ( trim($iCnpj) == '') {
      throw new Exception('CNPJ não informado!');
  	}

    if ( trim($sCodEvento) == '') {
      throw new Exception('Evento não informado!');
    }

  	$this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['oEmpresa']->iCodRua = $iCodRua;

  }


  function setCodBairroMEI($iCnpj='',$sCodEvento='',$iCodBairro=''){

    if ( trim($iCnpj) == '') {
      throw new Exception('CNPJ não informado!');
    }

    if ( trim($sCodEvento) == '') {
      throw new Exception('Evento não informado!');
    }

    $this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['oEmpresa']->iCodBairro = $iCodBairro;

  }


  function setEmpresaCadastrada($iCnpj='',$sCodEvento=''){

    if ( trim($iCnpj) == '') {
      throw new Exception('CNPJ não informado!');
    }

    if ( trim($sCodEvento) == '') {
      throw new Exception('Evento não informado!');
    }

    $this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['oEmpresa']->lEmpresaCadastrada = true;
  }

  function setResponsavelCadastrado($iCnpj='',$sCodEvento=''){

    if ( trim($iCnpj) == '') {
      throw new Exception('CNPJ não informado!');
    }

    if ( trim($sCodEvento) == '') {
      throw new Exception('Evento não informado!');
    }

    $this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['oResponsavel']->lResponsavelCadastrado = true;
  }


  function setCodAtividade($iCnpj='',$sCodEvento='',$sCnae='',$iCodAtividade=''){

    if ( trim($iCnpj) == '') {
      throw new Exception('CNPJ não informado!');
    }

    if ( trim($sCodEvento) == '') {
      throw new Exception('Evento não informado!');
    }

    if ( trim($sCnae) == '') {
      throw new Exception('Cnae não informado!');
    }

    foreach ( $this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['aAtividades'] as $iInd => $oAtividade ) {

    	if ( $oAtividade->q106_cnae == $sCnae ) {
    		$this->aDadosMEI[$iCnpj]['aEventos'][$sCodEvento]['aAtividades'][$iInd]->iCodAtividade = $iCodAtividade;
    	}

    }

  }


  function validaNumeroEndereco($sNumero=''){

  	$aNumero = split("[^0-9]",trim($sNumero));
  	if ( count($aNumero) > 1 ){
  	  return false;
  	} else {
  		return true;
  	}

  }


  function getCompetencias($dtDataini='',$dtDataFim=''){

  	$clMeiImporta = db_utils::getDao('meiimporta');

  	if ( trim($dtDataini) == '' ) {

      try {
        $dtDataImpMei = $this->getDataImpMEI();
      } catch (Exception $eException) {
        throw new Exception($eException->getMessage());
      }
  	}


    $sCamposImportaReg  = " distinct extract( month from q111_data ) as q104_mesusu, ";
    $sCamposImportaReg .= "          extract( year  from q111_data ) as q104_anousu  ";

    $sWhereImportaReg   = "  q111_data between cast('{$dtDataini}'as date) and cast('{$dtDataFim}' as date) ";
    $sSqlImportaReg     = $clMeiImporta->sql_query_reg(null,$sCamposImportaReg,null,$sWhereImportaReg);


    $sCamposImportaArq  = " distinct q104_mesusu,q104_anousu  ";
    $sWhereImportaArq   = "     q104_tipoimporta = 2          ";
    $sWhereImportaArq  .= " and q104_cancelado is false       ";
    $sWhereImportaArq  .= " and cast( q104_anousu||'-'||q104_mesusu||'-01' as date) between  ";
    $sWhereImportaArq  .= "         cast('{$dtDataini}' as date) and cast('{$dtDataFim}' as date)   ";

    $sSqlImportaArq     = $clMeiImporta->sql_query_file( null,
                                                         $sCamposImportaArq,
                                                         null,
                                                         $sWhereImportaArq);
    $sSqlImporta        = " select distinct
                                   q104_mesusu,
                                   q104_anousu
                              from ( {$sSqlImportaReg}
                                      union all
                                     {$sSqlImportaArq} ) as x
                          order by q104_anousu,q104_mesusu  ";

    $rsDadosImporta = db_query($sSqlImporta);

    if (!$rsDadosImporta) {
    	throw new Exception('Erro ao consultar competências!');
    }

    $aDadosImporta  = db_utils::getCollectionByRecord($rsDadosImporta);

    return $aDadosImporta;

  }


	function getDataImpMEI() {

		$oParIssqn = db_utils::getDao('parissqn');

	  $sSqlParIssqn = $oParIssqn->sql_query_file(null,"q60_dataimpmei",null,"q60_dataimpmei is not null");
	  $rsParIssqn   = $oParIssqn->sql_record($sSqlParIssqn);

	  if ( $oParIssqn->numrows > 0 ) {
	    return db_utils::fieldsMemory($rsParIssqn,0)->q60_dataimpmei;
	  } else {
      throw new Exception("{$sMsgErro},\nParâmentros de ISSQN não configurados!");
	  }
	}

	/**
	 * Retorna um Array com os códigos tratados pelo sistema,
	 * apenas estes códigos devem ser importados
	 * @return array
	 */
	public static function getEventosPermitidos() {

	  $aCodigoValidos[] = "247"; // Alteração de capital social
	  $aCodigoValidos[] = "570"; // Exclusão de Ofício
	  $aCodigoValidos[] = "517"; // Pedido de baixa
	  $aCodigoValidos[] = "101"; // Inscrição de primeiro estabelecimento
	  $aCodigoValidos[] = "209"; // Alteração de endereço entre municípios dentro do mesmo estado
	  $aCodigoValidos[] = "203"; // Exclusão do título do estabelecimento (nome de fantasia)
	  $aCodigoValidos[] = "211"; // Alteração de endereço dentro do mesmo município
	  $aCodigoValidos[] = "220"; // Alteração do nome empresarial (firma ou denominação)
	  $aCodigoValidos[] = "221"; // Alteração do título do estabelecimento (nome de fantasia)
	  $aCodigoValidos[] = "232"; // Alteração do contabilista ou da empresa de contabilidade
	  $aCodigoValidos[] = "244"; // Alteração de atividades econômicas (principal e secundárias)
	  return $aCodigoValidos;
	}

}