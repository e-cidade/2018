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
 * Classe para retorno dos dados do censo da escola.
 * @package Educacao
 * @subpackage Censo
 */
class DadosCensoEscola extends DadosCenso {

	/**
	 * codigo da escola
	 * @var integer
	 */
	protected $iCodigoEscola;

	protected $iAnoCenso;

	protected $iCodigoInep;

	protected $dtBaseCenso;

	/**
	 * Método Construtor
	 */
	function __construct($iCodigoEscola, $iAnoCenso, $dtBaseCenso) {

		$this->iAnoCenso     = $iAnoCenso;
		$this->iCodigoEscola = $iCodigoEscola;
		$this->dtBaseCenso   = $dtBaseCenso;

	}
	/**
	 * Gera os dados de Identificacao da escola
	 * são so dados do Registro 00
	 */
	public function getDadosIdentificacao() {

		$oDaoEscola      = new cl_escola();
		$sCamposEscola   = "  ed18_i_codigo, ";
		$sCamposEscola   .= " trim(ed18_c_codigoinep) as codigo_escola_inep, ";
		$sCamposEscola   .= " ed18_i_funcionamento as situacao_funcionamento, ";
		$sCamposEscola   .= " trim(ed18_c_nome) as nome_escola, ";
		$sCamposEscola   .= " trim(ed18_c_cep) as cep, ";
		$sCamposEscola   .= " trim(j14_nome) as endereco, ";
		$sCamposEscola   .= " ed18_i_numero as endereco_numero, ";
		$sCamposEscola   .= " trim(ed18_c_compl) as complemento_endereco, ";
		$sCamposEscola   .= " trim(j13_descr) as bairro, ";
		$sCamposEscola   .= " ed18_i_censouf  as uf, ";
		$sCamposEscola   .= " ed18_i_censomunic as municipio, ";
		$sCamposEscola   .= " ed262_i_coddistrito as distrito, ";
		$sCamposEscola   .= " trim(ed18_c_email) as endereco_eletronico, ";
		$sCamposEscola   .= " ed263_i_codigocenso as codigo_orgao_regional_ensino, ";
		$sCamposEscola   .= " trim(ed18_c_mantenedora::varchar) as dependencia_administrativa, ";
		$sCamposEscola   .= " trim(ed18_c_local) as localizacao_zona_escola, ";
		$sCamposEscola   .= " ed18_i_categprivada as categoria_escola_privada, ";
		$sCamposEscola   .= " ed18_i_conveniada as conveniada_poder_publico, ";
		$sCamposEscola   .= " trim(ed18_c_mantprivada) as lista_mantenedoras, ";
		$sCamposEscola   .= " ed18_i_cnpjprivada as cnpj_escola_privada, ";
		$sCamposEscola   .= " ed18_i_credenciamento as regulamentacao_autorizacao_conselho_orgao , ";
		$sCamposEscola   .= " ed18_i_cnpjmantprivada as cnpj_mantenedora_principal_escola_privada, ";
		$sCamposEscola   .= "(SELECT to_char(min(ed52_d_inicio), 'DD/MM/YYYY') ";
		$sCamposEscola   .= "  from calendario ";
		$sCamposEscola   .= "       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
		$sCamposEscola   .= " where ed38_i_escola = {$this->iCodigoEscola} ";
		$sCamposEscola   .= "   and ed52_i_ano     = {$this->iAnoCenso}) as data_inicio_ano_letivo,";
		$sCamposEscola   .= "(SELECT to_char(max(ed52_d_fim), 'DD/MM/YYYY') ";
		$sCamposEscola   .= "  from calendario ";
		$sCamposEscola   .= "       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
		$sCamposEscola   .= " where ed38_i_escola = {$this->iCodigoEscola} ";
		$sCamposEscola   .= "   and ed52_i_ano     = {$this->iAnoCenso}) as data_termino_ano_letivo,";
		$sCamposEscola   .= "ed18_latitude  as latitude,";
		$sCamposEscola   .= "ed18_longitude as longitude,";
		$sCamposEscola   .= "ed18_i_locdiferenciada as localizacao_diferenciada,";
		$sCamposEscola   .= "ed18_i_educindigena as educacao_indigena,";
		$sCamposEscola   .= "ed18_i_tipolinguapt as educacao_indigena_lingua_portugues,";
		$sCamposEscola   .= "ed18_i_tipolinguain as educacao_indigena_lingua_indigena,";
		$sCamposEscola   .= " ed18_i_linguaindigena as codigo_censo_lingua_indigena";
		$sSqlEscola       = $oDaoEscola->sql_query(
				                                       null,
																							 $sCamposEscola,
																							 null,
																							 " ed18_i_codigo = {$this->iCodigoEscola}"
																							);
		$rsDadosEscola = $oDaoEscola->sql_record($sSqlEscola);
		$oDadosEscola = db_utils::fieldsMemory($rsDadosEscola, 0);
		if ($oDadosEscola->dependencia_administrativa == 3) {
			$oDadosEscola->lista_mantenedoras = '     ';
		}
		if ($oDadosEscola->educacao_indigena == 0) {

			$oDadosEscola->educacao_indigena_lingua_portugues = '';
			$oDadosEscola->educacao_indigena_lingua_indigena  = '';
		}

		$oDadosEscola->lOrgaoEnsinoObrigatorio = false;
		$oDaoCensoOrgReg   = new cl_censoorgreg();
		$sWhereCensoOrgReg = "ed263_i_censouf = {$oDadosEscola->uf}";
		$sSqlCensoOrgReg   = $oDaoCensoOrgReg->sql_query(null, "ed263_i_codigo", null, $sWhereCensoOrgReg);
		$rsCensoOrgReg     = $oDaoCensoOrgReg->sql_record($sSqlCensoOrgReg);
		
		if ($oDaoCensoOrgReg->numrows > 0) {
			$oDadosEscola->lOrgaoEnsinoObrigatorio = true;
		}
		
		$this->iCodigoInep 									                              = $oDadosEscola->codigo_escola_inep;
		$oDadosEscola->nome_escola                                        = $this->removeCaracteres($oDadosEscola->nome_escola, 				 1);
		$oDadosEscola->endereco                                           = $this->removeCaracteres($oDadosEscola->endereco, 						 3);
		$oDadosEscola->bairro                                             = $this->removeCaracteres($oDadosEscola->bairro, 							 3);
		$oDadosEscola->complemento_endereco                               = $this->removeCaracteres($oDadosEscola->complemento_endereco, 3);
		$oDadosEscola->endereco_numero                               			= $this->removeCaracteres($oDadosEscola->endereco_numero, 		 3);
		$oDadosEscola->endereco_eletronico                                = $this->removeCaracteres($oDadosEscola->endereco_eletronico,  4);
		$oDadosEscola->mant_esc_privada_empresa_grupo_empresarial_pes_fis = substr($oDadosEscola->lista_mantenedoras, 0, 1);
		$oDadosEscola->mant_esc_privada_sidicatos_associacoes_cooperativa = substr($oDadosEscola->lista_mantenedoras, 1, 1);
		$oDadosEscola->mant_esc_privada_ong_internacional_nacional_oscip  = substr($oDadosEscola->lista_mantenedoras, 2, 1);
		$oDadosEscola->mant_esc_privada_instituicoes_sem_fins_lucrativos  = substr($oDadosEscola->lista_mantenedoras, 3, 1);
		$oDadosEscola->sistema_s_sesi_senai_sesc_outros                   = substr($oDadosEscola->lista_mantenedoras, 4, 1);
		$oDadosEscola->ddd                                                = '';
		$oDadosEscola->telefone                                           = '';
		$oDadosEscola->telefone_publico_1                                 = '';
		$oDadosEscola->telefone_publico_2                                 = '';
		$oDadosEscola->fax                                                = '';
		$aTelefones = $this->getTelefones();
		for ($iFone = 0; $iFone < count($aTelefones); $iFone++) {

			$iSequenciaTelefone  = $iFone + 1;
			$oDadosEscola->ddd = $aTelefones[$iFone]->ddd;
			if ($aTelefones[$iFone]->tipo_telefone == 2) {

				$oDadosEscola->fax = $aTelefones[$iFone]->numero;
				continue;
			}
			$oDadosEscola->{"telefone_publico_{$iSequenciaTelefone}"} = $aTelefones[$iFone]->numero;
		}
		unset($aTelefones);
		return $oDadosEscola;
	}

	public function getDadosInfraEstrutura () {

		$oDaoEscolaGestor     = new cl_escolagestorcenso();
		$sCamposEscolaGestor  = " ed254_i_codigo, ";
		$sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
		$sCamposEscolaGestor .= "      then trim(cgmrh.z01_nome) ";
		$sCamposEscolaGestor .= " else trim(cgmcgm.z01_nome) end as z01_nome, ";
		$sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
		$sCamposEscolaGestor .= "      then trim(cgmrh.z01_cgccpf) ";
		$sCamposEscolaGestor .= " else trim(cgmcgm.z01_cgccpf) end as z01_cgccpf, ";
		$sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
		$sCamposEscolaGestor .= "      then trim(rhfuncao.rh37_descr) ";
		$sCamposEscolaGestor .= " else 'DIRETOR' end as rh37_descr, trim(ed325_email) as ed325_email";
		$sWhereEscolaGestor   = " ed325_escola = {$this->iCodigoEscola} LIMIT 1";
		$sSqlEscolaGestor     = $oDaoEscolaGestor->sql_query_dados_gestor("", $sCamposEscolaGestor, "", $sWhereEscolaGestor);
		$rsEscolaGestor       = $oDaoEscolaGestor->sql_record($sSqlEscolaGestor);

		$oDadosInfraEstrutura = new stdClass();
		$oDadosInfraEstrutura->codigo_escola_inep                            = $this->iCodigoInep;
		$oDadosInfraEstrutura->numero_cpf_responsavel_gestor_responsavel     = '';
		$oDadosInfraEstrutura->nome_gestor                                   = '';
		$oDadosInfraEstrutura->cargo_gestor_responsavel                      = '';
		$oDadosInfraEstrutura->endereco_eletronico_email_gestor_responsavel  = '';
		if ($oDaoEscolaGestor->numrows > 0) {

			$oDadosGestor = db_utils::fieldsMemory($rsEscolaGestor, 0);
			$oDadosInfraEstrutura->numero_cpf_responsavel_gestor_responsavel = $oDadosGestor->z01_cgccpf;
			$oDadosInfraEstrutura->nome_gestor                               = trim($oDadosGestor->z01_nome);
			$oDadosInfraEstrutura->cargo_gestor_responsavel                  = 1;

			if (empty($oDadosGestor->ed254_i_codigo)) {
				$oDadosInfraEstrutura->cargo_gestor_responsavel = 2;
			}

			$oDadosInfraEstrutura->endereco_eletronico_email_gestor_responsavel = strtoupper(trim($oDadosGestor->ed325_email));
		}

		/**
		 * Total de Funcionarios na escola:
		 */
		$oDaoRecHumanoEscola     = new cl_rechumanoescola();
		$sCamposRecHumanoEscola  = "count(*) as total ";
		$sWhereRecHumanoEscola   = " ed75_i_escola = {$this->iCodigoEscola} ";

		$sSqlTotalRecursos   = $oDaoRecHumanoEscola->sql_query("", $sCamposRecHumanoEscola, "", $sWhereRecHumanoEscola);
		$rsTotalFuncionarios = $oDaoRecHumanoEscola->sql_record($sSqlTotalRecursos);

		$iTotalFuncionarios  = db_utils::fieldsMemory($rsTotalFuncionarios, 0)->total;
		$oDadosInfraEstrutura->total_funcionarios_prof_aux_assistentes_monitores = $iTotalFuncionarios;
		/**
		 * carregamos os tipos de ensino que escola disponibiliza
		 */
		$oDadosInfraEstrutura->modalidade_ensino_regular                          = '0';
		$oDadosInfraEstrutura->modalidade_educacao_especial_modalidade_substutiva = '0';
		$oDadosInfraEstrutura->modalidade_educacao_jovens_adultos                 = '0';
		foreach ($this->getTiposDeEnsinoNaEscola() as $iTipoEnsino) {

			switch ($iTipoEnsino) {

				case 1:

					$oDadosInfraEstrutura->modalidade_ensino_regular = 1;
					break;

				case 2:

					$oDadosInfraEstrutura->modalidade_educacao_especial_modalidade_substutiva = 1;
					break;

				case 3:

					$oDadosInfraEstrutura->modalidade_educacao_jovens_adultos = 1;
					break;
			}
		}

		/**
		 * criamos os dados das etapas da escola
		 */
		foreach ($this->getEtapasNaEscola() as $oEtapa) {

			switch ($oEtapa->codigo) {

				case 1 :

					$oDadosInfraEstrutura->etapas_ensino_regular_creche = $oEtapa->etapa_na_escola;
					break;

				case 2 :

					$oDadosInfraEstrutura->etapas_ensino_regular_pre_escola = $oEtapa->etapa_na_escola;
					break;

				case 3 :

					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_fundamental_8_anos = $oEtapa->etapa_na_escola;
					break;

				case 4 :

					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_fundamental_9_anos = $oEtapa->etapa_na_escola;
					break;

				case 5 :

					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_medio_medio = $oEtapa->etapa_na_escola;
					break;

				case 6 :
					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_medio_integrado = $oEtapa->etapa_na_escola;
					break;

				case 7 :
					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_medio_normal_magister = $oEtapa->etapa_na_escola;
					break;

				case 8 :
					$oDadosInfraEstrutura->etapas_ensino_regular_ensino_medio_profissional = $oEtapa->etapa_na_escola;
					break;

				case 9 :

					$oDadosInfraEstrutura->etapa_educacao_especial_creche = $oEtapa->etapa_na_escola;
					break;

				case 10:

					$oDadosInfraEstrutura->etapa_educacao_especial_pre_escola = $oEtapa->etapa_na_escola;
					break;

				case 11 :

					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_fundamental_8_anos = $oEtapa->etapa_na_escola;
					break;

				case 12 :

					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_fundamental_9_anos = $oEtapa->etapa_na_escola;
					break;

				case 13 :
					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_medio_medio = $oEtapa->etapa_na_escola;
					break;

				case 14 :
					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_medio_integrado = $oEtapa->etapa_na_escola;
					break;

				case 15 :

					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_medio_normal_magist = $oEtapa->etapa_na_escola;
					break;

				case 16 :

					$oDadosInfraEstrutura->etapa_educacao_especial_ensino_medio_educ_profis = $oEtapa->etapa_na_escola;
					break;

				case 17 :
					$oDadosInfraEstrutura->etapa_educacao_especial_eja_ensino_fundamental = $oEtapa->etapa_na_escola;
					break;

				case 18 :

					$oDadosInfraEstrutura->etapa_educacao_especial_eja_ensino_medio = $oEtapa->etapa_na_escola;
					break;

				case 19 :

					$oDadosInfraEstrutura->etapa_eja_ensino_fundamental = $oEtapa->etapa_na_escola;
					break;

				case 20 :

					$oDadosInfraEstrutura->etapa_eja_ensino_fundamental_projovem_urbano = $oEtapa->etapa_na_escola;
					break;

				case 21 :
					$oDadosInfraEstrutura->etapa_eja_ensino_medio = $oEtapa->etapa_na_escola;
					break;
			}
		}
		$oDadosEscola = $this->getDadosIdentificacao();
		$oDadosInfraEstrutura->localizacao_diferenciada_escola = $oDadosEscola->localizacao_diferenciada;
		$oDadosInfraEstrutura->educacao_indigena               = $oDadosEscola->educacao_indigena;

		$oDadosInfraEstrutura->lingua_ensino_ministrado_lingua_indigena   = $oDadosEscola->educacao_indigena_lingua_indigena;
		$oDadosInfraEstrutura->lingua_ensino_ministrada_lingua_portuguesa = $oDadosEscola->educacao_indigena_lingua_portugues;
		$oDadosInfraEstrutura->codigo_lingua_indigena                     = $oDadosEscola->codigo_censo_lingua_indigena;

		$oDadosInfraEstrutura->codigo_escola_compartilha_1 = '';
		$oDadosInfraEstrutura->codigo_escola_compartilha_2 = '';
		$oDadosInfraEstrutura->codigo_escola_compartilha_3 = '';
		$oDadosInfraEstrutura->codigo_escola_compartilha_4 = '';
		$oDadosInfraEstrutura->codigo_escola_compartilha_5 = '';
		$oDadosInfraEstrutura->codigo_escola_compartilha_6 = '';

		/**
		 * Percorremos os dados das avaliacoes da escola, onde montamos o restante dos dados da infraEstrutra
		 */
		foreach ($this->getDadosAvaliacao($oDadosInfraEstrutura) as $oRespostas) {
			$oDadosInfraEstrutura->{$oRespostas->campo} = $oRespostas->respostas;
		}
		
		if ($oDadosInfraEstrutura->acesso_internet == 0) {
			$oDadosInfraEstrutura->banda_larga 		 = '';
		}
		
		if($oDadosInfraEstrutura->equipamentos_existentes_escola_computador == 0 || $oDadosInfraEstrutura->equipamentos_existentes_escola_computador == ''){
			$oDadosInfraEstrutura->banda_larga 		 = '';
			$oDadosInfraEstrutura->acesso_internet = '';
		}
		 
		return $oDadosInfraEstrutura;
	}

	/**
	 * Retorna os tipos de ensino que a escola possui alunos matriculados
	 * no ano, até a data do censo.
	 * @return Array;
	 */
	public function getTiposDeEnsinoNaEscola() {

		$oDaoMatricula     = db_utils::getDao("matricula");
		$aTiposDeEnsino    = array();
		$sCamposModalidade = " distinct ed10_i_tipoensino ";
		$sWhereModalidade  = " turma.ed57_i_escola  = {$this->iCodigoEscola} ";
		$sWhereModalidade .= "  AND calendario.ed52_i_ano = {$this->iAnoCenso} ";
		$sWhereModalidade .= "  AND ed60_c_situacao = 'MATRICULADO' ";
		$sWhereModalidade .= "  AND ed60_d_datamatricula <= '{$this->dtBaseCenso}'";
		$sSqlModalidade    = $oDaoMatricula->sql_query("",
				$sCamposModalidade,
				"ed10_i_tipoensino",
				$sWhereModalidade
		);
		$sSqlTotalTiposDeEnsino = $oDaoMatricula->sql_query("", $sCamposModalidade, "", $sWhereModalidade);
		$rsTotalTiposDeEnsino   = $oDaoMatricula->sql_record($sSqlTotalTiposDeEnsino);
		$iTotalTiposDeEnsino    = $oDaoMatricula->numrows;

		for ($iContador     = 0; $iContador < $iTotalTiposDeEnsino; $iContador++) {
			$aTiposDeEnsino[] = db_utils::fieldsMemory($rsTotalTiposDeEnsino, $iContador)->ed10_i_tipoensino;
		}
		return $aTiposDeEnsino;
	}

	/**
	 * Retorna um array com o as etapas do censo que a escola disponibiliza.
	 * retornamos apenas as etapas que exista um aluno matriculo na mesma
	 * @return Array;
	 */
	public function getEtapasNaEscola() {

		$aEtapas               = array();
		$oDaoCensoEtapas        = db_utils::getDao("censoetapamodal");
		$sCamposEtapasNaEscola  = "ed273_i_sequencia  as codigo, ";
		$sCamposEtapasNaEscola .= "ed273_i_codigo  as codigo_Seq, ";
		$sCamposEtapasNaEscola .= "ed273_i_modalidade as modalidade, ";
		$sCamposEtapasNaEscola .= "ed273_c_etapa ";

		$sSqlEtapaModal  = $oDaoCensoEtapas->sql_query("", $sCamposEtapasNaEscola, "ed273_i_sequencia", "");
		$rsEtapaNaEscola = $oDaoCensoEtapas->sql_record($sSqlEtapaModal);
		$aEtapas         = db_utils::getCollectionByRecord($rsEtapaNaEscola);
		$oDaoMatricula   = db_utils::getDao("matricula");
		foreach ($aEtapas as $oEtapa) {

			$sCamposModalidade       = " serie.ed11_i_codcenso as codigoetapa";
			$oEtapa->etapa_na_escola = '0';
			$sWhereModalidade        = " turma.ed57_i_escola = {$this->iCodigoEscola} ";
			$sWhereModalidade       .= " AND calendario.ed52_i_ano = {$this->iAnoCenso}";
			$sWhereModalidade       .= " AND ed60_c_situacao = 'MATRICULADO' ";
			$sWhereModalidade       .= " AND serie.ed11_i_codcenso in ({$oEtapa->ed273_c_etapa}) ";
			$sWhereModalidade       .= " AND ensino.ed10_i_tipoensino = {$oEtapa->modalidade}";
			$sWhereModalidade       .= " AND ed60_d_datamatricula <= '{$this->dtBaseCenso}'";
			$sSqlModalidade          = $oDaoMatricula->sql_query("", $sCamposModalidade, "", $sWhereModalidade);

			$rsModalidades           = $oDaoMatricula->sql_record($sSqlModalidade);
			if ($oDaoMatricula->numrows > 0) {
				$oEtapa->etapa_na_escola = 1;
			}
		}
		return $aEtapas;
	}

	public function getDadosAvaliacao ($oDadosInfra) {
		
		/**
		 * Procuramos o codigo da avaliacao da escola.
		 */
		$aRespostasObjetivas          = array();

		/**
		 * acesso a internt
		*/
		$aRespostasObjetivas[3000035] = 1;
		$aRespostasObjetivas[3000036] = 0;

		/**
		 * equipamentos_existentes_escola_computador
		 */
		$aRespostasObjetivas[3000030] = 1;
		$aRespostasObjetivas[3000031] = 0;

		/**
		 * Cede espaco para a comunidade
		 */
		$aRespostasObjetivas[3000122] = 1;
		$aRespostasObjetivas[3000123] = 2;

		/**
		 * Agua consumida
		 */
		$aRespostasObjetivas[3000099] = 1;
		$aRespostasObjetivas[3000100] = 2;

		/**
		 * Alimentacao escolar
		 */
		$aRespostasObjetivas[3000101] = 1;
		$aRespostasObjetivas[3000102] = 0;

		/**
		 * atendimento_educacional_especializado
		 */
		$aRespostasObjetivas[3000108] = 1;
		$aRespostasObjetivas[3000109] = 2;
		$aRespostasObjetivas[3000110] = '0';

		/**
		 *  atividade_complementar
		 */
		$aRespostasObjetivas[3000105] = 1;
		$aRespostasObjetivas[3000106] = "0";
		$aRespostasObjetivas[3000007] = "2";

		/**
		 *  banda_larga
		 */
		$aRespostasObjetivas[3000037] = 1;
		$aRespostasObjetivas[3000038] = "0";

		/**
		 *   ensino_fundamental_organizado_ciclos
		 */
		$aRespostasObjetivas[3000111] = "0";
		$aRespostasObjetivas[3000112] = 1;

		/**
		 *  escola_cede_espaco_turma_brasil_alfabetizado
		 */
		$aRespostasObjetivas[3000122] = 1;
		$aRespostasObjetivas[3000123] = "0";

		/**
		 *  forma_ocupacao
		 */
		$aRespostasObjetivas[3000047] = 1;
		$aRespostasObjetivas[3000048] = 2;
		$aRespostasObjetivas[3000049] = 3;

		/**
		 * Predio compartilhado
		 */
		$aRespostasObjetivas[3000097] = 1;
		$aRespostasObjetivas[3000098] = "0";

		/**
		 * Funciona em finais de semana
		 */
		$aRespostasObjetivas[3000124] = 1;
		$aRespostasObjetivas[3000125] = "0";

		/**
		 * Escola com proposta pedagogica de formacao por alternancia
		 */
		$aRespostasObjetivas[3000561] = 1;
		$aRespostasObjetivas[3000562] = "0";

		/**
		 * Perguntas que nao podem ter respostas com valor 0.
		 * esses respostas devem ficar com o valor vazio;
		 */
		$aPerguntasLimparValorZero = array(3000010);

		$aPerguntas           = array();
		$oDaoEscolaDadosCenso = db_utils::getDao("escoladadoscenso");
		$sSqlCodigoAvaliacao  = $oDaoEscolaDadosCenso->sql_query_file(null,
				"ed308_avaliacaogruporesposta",
				null,
				"ed308_escola = {$this->iCodigoEscola}"
		);
		$rsCodigoAvaliacao  = $oDaoEscolaDadosCenso->sql_record($sSqlCodigoAvaliacao);
		if ($oDaoEscolaDadosCenso->numrows == 0) {
			$sSqlResposta = " '' ";

		} else {

			$iCodigoAvaliacao  = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed308_avaliacaogruporesposta;
			$sSqlResposta      = "(select case when db103_avaliacaotiporesposta = 1        then cast(db104_sequencial as varchar) ";
			$sSqlResposta     .= "             when db103_sequencial IN (3000003, 3000024) then db106_resposta else"; 
			$sSqlResposta     .= "  case when trim(db106_resposta) != '' then db106_resposta else '1' end  end ";
			$sSqlResposta     .= "   from avaliacaogrupoperguntaresposta ";
			$sSqlResposta     .= "        inner join avaliacaoresposta      on db108_avaliacaoresposta      = db106_sequencial ";
			$sSqlResposta     .= "  where db106_avaliacaoperguntaopcao = db104_sequencial ";
			$sSqlResposta     .= "   and db108_avaliacaogruporesposta  = {$iCodigoAvaliacao} limit 1)";
		}

		$oDaoPerguntas = db_utils::getDao("avaliacaoperguntaopcaolayoutcampo");
		$sSqlPerguntas = $oDaoPerguntas->sql_query_avaliacao(null,
				"distinct db52_nome as campo,
				db103_avaliacaotiporesposta as tipo_resposta,
				db103_sequencial,
				{$sSqlResposta} as respostas",
				null,
				"db102_avaliacao = 3000000 and ed313_ano = {$this->iAnoCenso}"
		);
		$rsPerguntas = $oDaoPerguntas->sql_record($sSqlPerguntas);
		$iTotalPerguntas = $oDaoPerguntas->numrows;
		for ($iPergunta = 0; $iPergunta < $iTotalPerguntas; $iPergunta++) {

			$oPergunta = db_utils::fieldsMemory($rsPerguntas, $iPergunta);
			if ($oPergunta->tipo_resposta == 1) {

				if (isset($aPerguntas[$oPergunta->campo])) {
					if (trim($oPergunta->respostas) != "") {
						$aPerguntas[$oPergunta->campo] = $oPergunta;
					}
				} else {
					$aPerguntas[$oPergunta->campo] = $oPergunta;
				}

				if ($oPergunta->respostas != "") {
					$aPerguntas[$oPergunta->campo]->respostas = $aRespostasObjetivas[$aPerguntas[$oPergunta->campo]->respostas];
				}
				
				if ($oPergunta->db103_sequencial == 3000023){
					
					if(($oDadosInfra->etapas_ensino_regular_creche <> 0 || $oDadosInfra->etapas_ensino_regular_pre_escola <> 0) &&
						 ($oDadosInfra->etapas_ensino_regular_ensino_fundamental_8_anos == 0 && $oDadosInfra->etapas_ensino_regular_ensino_fundamental_9_anos == 0)){
						
						 $aPerguntas[$oPergunta->campo]->respostas = '';
					}
				}
				
			} else {
				
				/**
				 * Caso a pergunta seja 3000010 (equipamentos existentes) e a resposta esteja como zero, setamos a resposta
				 * como vazio
				 */
				if ($oPergunta->db103_sequencial == 3000010 && (trim($oPergunta->respostas) == 0 || trim($oPergunta->respostas) == '') ) {
					$oPergunta->respostas = '';
				}
				$aPerguntas[$oPergunta->campo] = $oPergunta;
			}
		}
		
		return $aPerguntas;
	}

	/**
	 * Retorna os telefones que a escola tem.
	 * os tipos de telefone que a funcao retorna é 1 = Telefone Fixo/Celular ou 2 - Fax
	 * @return array
	 */
	public function getTelefones() {

		$aTelefones     = array();
		$oDaoTelefones  = db_utils::getDao("telefoneescola");
		$sCampos        = "ed26_i_ddd as ddd,";
		$sCampos       .= "ed26_i_numero as numero,";
		$sCampos       .= "ed13_c_descr as tipo_telefone";
		$sSqlTelefones  = $oDaoTelefones->sql_query(null,
				$sCampos,
				"ed26_i_numero",
				"ed26_i_escola = {$this->iCodigoEscola}"
		);
		$rsTelefones     = $oDaoTelefones->sql_record($sSqlTelefones);
		$iTotalTelefones = $oDaoTelefones->numrows;
		for ($iFone = 0; $iFone < $iTotalTelefones; $iFone++) {

			$oTelefone = db_utils::fieldsMemory($rsTelefones, $iFone);
			$iTipoFone = 1;
			if (strpos(strtoupper($oTelefone->tipo_telefone), "FAX") !== false) {
				$iTipoFone = 2;
			}
			$oTelefone->tipo_telefone = $iTipoFone;
			$aTelefones[]             = $oTelefone;
		}
		return $aTelefones;
	}

	/**
	 * Valida os dados do arquivo
	 * @param $oExportacaoCenso instancia da Importacao do censo
	 * @return boolean
	 */
	public function validarDados(ExportacaoCenso2013 $oExportacaoCenso) {

		$lDadosInvalidos = true;
		$oDadosEscola    = $oExportacaoCenso->getDadosProcessadosEscola();
		
		/**
		 * Início da validação dos campos obrigatórios
		*/
		if (trim($oDadosEscola->registro00->nome_escola) == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Nome da Escola não pode ser vazio");
		}

		if (strlen($oDadosEscola->registro00->nome_escola) < 4) {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Nome da Escola deve conter no mínimo 4 dígitos");
		}

		if ($oDadosEscola->registro00->cep == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Campo CEP é obrigatório");
		}
		if (strlen($oDadosEscola->registro00->cep) < 8) {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("CEP  da escola deve conter 8 dígitos.");
		}

		if ($oDadosEscola->registro00->endereco == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Endereço da escola é obrigatório.");
		}

		if ($oDadosEscola->registro00->uf == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("UF da escolas é obrigatório.");
		}

		if ($oDadosEscola->registro00->municipio == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Campo Munícipio da escola é obrigatório.");
		}

		if ($oDadosEscola->registro00->distrito == '') {

			$lDadosInvalidos = false;
			$oExportacaoCenso->logErro("Campo Distrito é obrigatório.");
		}
		
		/**
		 * Validações que serão executadas caso a situação de funcionamento seja igual a 1
		 * 1 - em atividade
		 */
		if ($oDadosEscola->registro00->situacao_funcionamento == 1) {

			
			if (trim($oDadosEscola->registro00->codigo_orgao_regional_ensino) == '' && $oDadosEscola->registro00->lOrgaoEnsinoObrigatorio) {
			
				$lDadosInvalidos = false;
				$oExportacaoCenso->logErro("Orgão Regional de Ensino obrigatório.");
			}
			
			if ($oDadosEscola->registro00->data_inicio_ano_letivo == '') {

				$lDadosInvalidos = false;
				$oExportacaoCenso->logErro("Campo Data de Início do Ano Letivo é obrigatório");
			}

			if ($oDadosEscola->registro00->data_termino_ano_letivo == '') {

				$lDadosInvalidos = false;
				$oExportacaoCenso->logErro("Campo Data de Término do Ano Letivo é obrigatório");
			}

			if ($oDadosEscola->registro00->telefone != '') {

				if (strlen($oDadosEscola->registro00->telefone) < 8) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone deve conter 8 dígitos");
				}

				if ( substr($oDadosEscola->registro00->telefone, 0, 1) != 9 && strlen($oDadosEscola->registro00->telefone) == 9 ) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone, ao conter 9 dígitos, o primeiro algarismo deve ser 9.");
				}
			}

			if ($oDadosEscola->registro00->telefone_publico_1 != '') {

				if (strlen($oDadosEscola->registro00->telefone_publico_1) < 8) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone Público 1 deve conter 8 dígitos");
				}

				if ( substr($oDadosEscola->registro00->telefone_publico_1, 0, 1) != 9 &&
						strlen($oDadosEscola->registro00->telefone_publico_1) == 9 ) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone Público 1, ao conter 9 dígitos, o primeiro algarismo deve ser 9.");
				}
			}

			if ($oDadosEscola->registro00->telefone_publico_2 != '') {

				if (strlen($oDadosEscola->registro00->telefone_publico_2) < 8) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone Público 2 deve conter 8 dígitos");
				}

				if ( substr($oDadosEscola->registro00->telefone_publico_2, 0, 1) != 9 &&
						strlen($oDadosEscola->registro00->telefone_publico_2) == 9 ) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Telefone Público 2, ao conter 9 dígitos, o primeiro algarismo deve ser 9.");
				}
			}

			if ($oDadosEscola->registro00->fax != '') {

				if (strlen($oDadosEscola->registro00->fax) < 8) {

					$lDadosInvalidos = false;
					$oExportacaoCenso->logErro("Campo Fax deve conter 8 dígitos");
				}
			}

			/**
			 * Validações que serão executadas caso a situação de funcionamento seja igual a 1
			 * 1 - em atividade
			 * e caso a opção Dependência Administrativa selecionada seja igual a 4
			 * 4 - privada
			 */
			if ($oDadosEscola->registro00->dependencia_administrativa == 4) {

				if ($oDadosEscola->registro00->categoria_escola_privada == '' ||
						$oDadosEscola->registro00->categoria_escola_privada == 0) {

					$lDadosInvalidos  = false;
					$sErroMsg         = "Escola de Categoria Privada.\n";
					$sErroMsg        .= "Deve ser selecionada uma opção no campo Categoria da Escola Privada";
					$oExportacaoCenso->logErro($sErroMsg);
				}

				if ($oDadosEscola->registro00->conveniada_poder_publico == '') {

					$lDadosInvalidos  = false;
					$oExportacaoCenso->logErro("Deve ser selecionada uma opção no campo Conveniada Com o Poder Público");
				}

				if (($oDadosEscola->registro00->mant_esc_privada_empresa_grupo_empresarial_pes_fis)  == 0 &&
						($oDadosEscola->registro00->mant_esc_privada_sindicatos_associacoes_cooperativa) == 0 &&
						($oDadosEscola->registro00->mant_esc_privada_ong_internacional_nacional_oscip)   == 0 &&
						($oDadosEscola->registro00->mant_esc_privada_instituicoes_sem_fins_lucrativos)   == 0 &&
						($oDadosEscola->registro00->sistema_s_sesi_senai_sesc_outros)                    == 0) {

					$lDadosInvalidos  = false;
					$oExportacaoCenso->logErro("Deve ser selecionado pelo menos um campo de Mantenedora da Escola Privada");
				}

				if ($oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada == '') {

					$lDadosInvalidos  = false;
					$oExportacaoCenso->logErro("O CNPJ da mantenedora principal da escola deve ser informado");
				}

				if ($oDadosEscola->registro00->cnpj_escola_privada == '') {

					$lDadosInvalidos  = false;
					$oExportacaoCenso->logErro("O CNPJ da escola deve ser informado quando escola for privada.");
				}
			}
		}
		
		$lDadosInfraEstrutura = DadosCensoEscola::validarDadosInfraEstrutura($oExportacaoCenso);
		if (!$lDadosInfraEstrutura) {
			$lDadosInvalidos = $lDadosInfraEstrutura;
		}
		
		return $lDadosInvalidos;
	}

	/**
	 * Realizada a validacao dos dados da InfraEstrutura da escola
	 * @param ExportacaoCenso2013 $oExportacaoCenso
	 */
	protected function validarDadosInfraEstrutura(ExportacaoCenso2013 $oExportacaoCenso) {

		$lDadosInvalidos        = true;
		$oDadosEscola           = $oExportacaoCenso->getDadosProcessadosEscola();
		$aEquipamentosValidacao = array(
				0 => "equipamentos_existentes_escola_televisao",
				1 => "equipamentos_existentes_escola_videocassete",
				2 => "equipamentos_existentes_escola_dvd",
				3 => "equipamentos_existentes_escola_antena_parabolica",
				4 => "equipamentos_existentes_escola_copiadora",
				5 => "equipamentos_existentes_escola_retroprojetor",
				6 => "equipamentos_existentes_escola_impressora",
				7 => "equipamentos_existentes_escola_aparelho_som",
				8 => "equipamentos_existentes_escola_projetor_datashow",
				9 => "equipamentos_existentes_escola_fax",
				10 => "equipamentos_existentes_escola_maquina_fotografica",
				11 => "equipamentos_existentes_escola_computador"
		);

		$aEstruturaValidacao    = array(
				3000032 => "equipamentos_existentes_escola_computador",
				3000003 => "quantidade_computadores_uso_administrativo",
				3000024 => "quantidade_computadores_uso_alunos",
				3000019 => "numero_salas_aula_existentes_escola",
				3000020 => "numero_salas_usadas_como_salas_aula"
		);

		foreach ($aEquipamentosValidacao as $sEquipamentoValidacao) {

			if (!DadosCensoEscola::validarEquipamentosExistentes($oExportacaoCenso, $oDadosEscola->registro10->$sEquipamentoValidacao)) {
				$lDadosInvalidos  = false;
			}
			break;
		}

		foreach ($aEstruturaValidacao as $iSequencial => $sEstruturaValidacao) {

			if (!DadosCensoEscola::validarEquipamentosGeral($oExportacaoCenso, $iSequencial, $oDadosEscola->registro10->$sEstruturaValidacao)) {
				$lDadosInvalidos  = false;
			}
		}

		if ($oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel == '') {

			$lDadosInvalidos  = false;
			$oExportacaoCenso->logErro("Número do CPF do gestor é obrigatório");
		}

		if (trim($oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel) == "00000000191") {

			$lDadosInvalidos  = false;
			$sMensagem        = "Número do CPF ({$oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel}) do";
			$sMensagem       .= " gestor informado é inválido.";
			$oExportacaoCenso->logErro($sMensagem);
		}

		if ($oDadosEscola->registro10->nome_gestor == '') {

			$lDadosInvalidos  = false;
			$oExportacaoCenso->logErro("Nome do gestor é obrigatório");
		}

		$sExpressao          = '/([a-zA-Z])\1{3}/';
		$lValidacaoExpressao = preg_match($sExpressao, $oDadosEscola->registro10->nome_gestor) ? true : false;

		if ($lValidacaoExpressao) {

			$lDadosInvalidos  = false;
			$oExportacaoCenso->logErro("Nome do gestor inválido. Não é possível informar mais de 4 letras repetidas em sequência.");
		}

		if ($oDadosEscola->registro10->cargo_gestor_responsavel == '') {

			$lDadosInvalidos  = false;
			$oExportacaoCenso->logErro("Cargo do gestor é obrigatório");
		}

		/**
		 * Validações que serão executadas caso a situação de funcionamento seja igual a 1
		 * 1 - em atividade
		 */
		if ($oDadosEscola->registro00->situacao_funcionamento == 1) {

			if ($oDadosEscola->registro10->local_funcionamento_escola_predio_escolar          == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_templo_igreja           == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_salas_empresas          == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_casa_professor          == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_salas_outras_escolas    == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_galpao_rancho_paiol_bar == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_un_internacao_prisional == 0 &&
					$oDadosEscola->registro10->local_funcionamento_escola_outros                  == 0) {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Deve ser selecionado pelo menos um local de funcionamento da escola.");
			}

			/**
			 * Validações que serão executadas caso a situação de funcionamento seja igual a 1
			 * 1 - em atividade
			 * e o local de funcionamento seja igual a 7
			 * 7 - Prédio escolar
			 */
			if ($oDadosEscola->registro10->local_funcionamento_escola_predio_escolar == 1) {

				if ($oDadosEscola->registro10->forma_ocupacao_predio == '' ||
						$oDadosEscola->registro10->forma_ocupacao_predio == 0) {

					$lDadosInvalidos  = false;
					$oExportacaoCenso->logErro("Escola Ativa. Deve ser selecionada uma forma de ocupação do prédio.");
				}
			}

			if ($oDadosEscola->registro10->agua_consumida_alunos == '') {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Deve ser selecionada um tipo de agua consumida pelos alunos.");
			}
			if ($oDadosEscola->registro10->destinacao_lixo_coleta_periodica == 0 &&
					$oDadosEscola->registro10->destinacao_lixo_queima           == 0 &&
					$oDadosEscola->registro10->destinacao_lixo_joga_outra_area  == 0 &&
					$oDadosEscola->registro10->destinacao_lixo_recicla          == 0 &&
					$oDadosEscola->registro10->destinacao_lixo_enterra          == 0 &&
					$oDadosEscola->registro10->destinacao_lixo_outros           == 0) {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Destinação do lixo da escola nao informado.");
			}

			if ($oDadosEscola->registro10->numero_salas_aula_existentes_escola == 0) {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("O valor do campo Números de salas de aula existentes na escola deve ser maior que 0");
			}

			if ($oDadosEscola->registro10->numero_salas_usadas_como_salas_aula == 0) {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("O valor do campo Números de salas utilizadas como sala de aula deve ser maior de 0");
			}

			if ($oDadosEscola->registro10->escola_cede_espaco_turma_brasil_alfabetizado == '') {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Informe se a escola cede espaço para turmas do Brasil Alfabetizado.");
			}

			if ($oDadosEscola->registro10->escola_abre_finais_semanas_comunidade == '') {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Informe se a escola abre aos finais de semana para a comunidade.");
			}

			if ($oDadosEscola->registro10->escola_formacao_alternancia == '') {

				$lDadosInvalidos  = false;
				$oExportacaoCenso->logErro("Informe se a escola possui proposta pedagógica de formação por alternância.");
			}
			
			if ($oDadosEscola->registro00->dependencia_administrativa == 3) {
				
				if (trim($oDadosEscola->registro10->alimentacao_escolar_aluno) != 1) {
				
					$lDadosInvalidos  = false;
					$sMensagem        = "Campo Alimentação na aba Infra estrutura do cadastro dados da escola deve ser ";
					$sMensagem       .= "informado como Oferece, pois dependência administrativa é Municipal.";
					$oExportacaoCenso->logErro($sMensagem);
				}
			}
		}
		
		return $lDadosInvalidos;
	}

	public function atualizarDados(DBLayoutLinha $oLinha)  {

		$oDaoEscola                       = db_utils::getDao('escola');
		$oDaoEscola->ed18_i_funcionamento = $oLinha->situacao_funcionamento;
		$oDaoEscola->ed18_c_cep           = $oLinha->cep;
		$oDaoEscola->ed18_i_numero        = $oLinha->endereco_numero;
		$oDaoEscola->ed18_c_complemento   = $oLinha->complemento_endereco;
		$oDaoEscola->ed18_c_email         = $oLinha->endereco_eletronico;
		$oDaoEscola->ed18_i_censouf       = $oLinha->uf;
		$oDaoEscola->ed18_i_censomunic    = $oLinha->municipio;
		/**
		 * Atualizamos os dados do Mnicipío
		 */
		$oDaoCensoDistrito = db_utils::getdao('censodistrito');
		$sWhere            = "ed262_i_censomunic = ".$oLinha->municipio;
		$sWhere           .= " and ed262_i_coddistrito = ".$oLinha->distrito;
		$sSqlCensoDistrito = $oDaoCensoDistrito->sql_query_file("", "ed262_i_codigo", "", $sWhere);
		$rsCensoDistrito   = $oDaoCensoDistrito->sql_record($sSqlCensoDistrito);

		if ($oDaoCensoDistrito->numrows > 0) {
			$oDaoEscola->ed18_i_censodistrito = db_utils::fieldsmemory($rsCensoDistrito, 0)->ed262_i_codigo;
		} else {
			$oDaoEscola->ed18_i_censodistrito = "null";
		}

		$oDaoCensoOrgReg  = db_utils::getdao('censoorgreg');
		$sWhereCensoOrg   = "ed263_i_censouf = {$oLinha->uf} ";
		$sWhereCensoOrg  .= " and ed263_i_codigocenso = '{$oLinha->codigo_orgao_regional_ensino}'";
		$sSqlOrgReg       = $oDaoCensoOrgReg->sql_query("", "ed263_i_codigo", "", $sWhereCensoOrg);
		$rsCensoOrgReg    = $oDaoCensoOrgReg->sql_record($sSqlOrgReg);

		if ($oDaoCensoOrgReg->numrows > 0) {
			$oDaoEscola->ed18_i_censoorgreg = db_utils::fieldsmemory($rsCensoOrgReg, 0)->ed263_i_codigo;
		} else {
			$oDaoEscola->ed18_i_censoorgreg = "null";
		}

		$oDaoEscola->ed18_c_local        = $oLinha->localizacao_zona_escola;
		$oDaoEscola->ed18_i_categprivada = $oLinha->categoria_escola_privada;
		$oDaoEscola->ed18_i_conveniada   = $oLinha->conveniada_poder_publico;
		$oDaoEscola->ed18_i_cnpjprivada  = $oLinha->cnpj_escola_privada;
		$oDaoBairro = db_utils::getdao('bairro');
		$sSqlBairro = $oDaoBairro->sql_query_file("", "j13_codi", "",
				"to_ascii(j13_descr,'LATIN1') = '".$oLinha->bairro."'"
		);
		$rsBairro   = $oDaoBairro->sql_record($sSqlBairro);

		if ($oDaoBairro->numrows > 0) {
			$oDaoEscola->ed18_i_bairro = db_utils::fieldsmemory($rsBairro, 0)->j13_codi;
		} else {
			$oDaoEscola->ed18_i_bairro = 0;
		}

		$oDaoRuas = db_utils::getdao('ruas');
		$sSqlRuas = $oDaoRuas->sql_query("", "j14_codigo", "", "translate(j14_nome,'()','') = '".$oLinha->endereco."'");
		$rsRuas   = $oDaoRuas->sql_record($sSqlRuas);

		if ($oDaoRuas->numrows > 0) {
			$oDadosRuas->j14_codigo = db_utils::fieldsmemory($rsRuas, 0)->j14_codigo;
		} else {
			$oDadosRuas->j14_codigo = 0;
		}

		$oDaoEscola->ed18_i_credenciamento = $oLinha->regulamentacao_autorizacao_conselho_orgao;
		$oDaoEscola->ed18_i_codigo         = $this->iCodigoEscola;
		$oDaoEscola->alterar($this->iCodigoEscola);

		if ($oDaoEscola->erro_status == '0') {
			throw new Exception("Erro na alteração dos dados da escola. Erro da classe: ".$oDaoEscola->erro_msg);
		}
	}

	/**
	 * Atualiza os dados da Estrutura da escola
	 * @param DBLayoutLinha $oLinha linha com os dados do censo
	 * @param  $oDadosEscola dados da escola
	 */
	public function atualizarDadosEstrutura(DBLayoutLinha $oLinha, $oDadosEscola) {

		$oDaoEscola          = db_utils::getDao('escola');
		$oDaoEscolaEstrutura = db_utils::getDao('escolaestrutura');

		if ($oLinha->educacao_indigena != ""
				&& $oLinha->educacao_indigena != trim($oDadosEscola->ed18_i_educindigena)) {
			$oDaoEscola->ed18_i_educindigena = $oLinha->educacao_indigena;
		}

		if ($oLinha->lingua_ensino_ministrado_lingua_indigena != ""
				&& $oLinha->lingua_ensino_ministrado_lingua_indigena != trim($oDadosEscola->ed18_i_tipolinguain)) {
			$oDaoEscola->ed18_i_tipolinguain = $oLinha->lingua_ensino_ministrado_lingua_indigena;
		}

		if ($oLinha->lingua_ensino_ministrada_lingua_portuguesa != ""
				&& $oLinha->lingua_ensino_ministrada_lingua_portuguesa != trim($oDadosEscola->ed18_i_tipolinguapt)) {
			$oDaoEscola->ed18_i_tipolinguapt = $oLinha->lingua_ensino_ministrada_lingua_portuguesa;
		}

		if ($oLinha->codigo_lingua_indigena != ""
				&& $oLinha->codigo_lingua_indigena != trim($oDadosEscola->ed18_i_linguaindigena)) {
			$oDaoEscola->ed18_i_linguaindigena = $oLinha->codigo_lingua_indigena;
		}

		if ($oLinha->localizacao_diferenciada_escola != ""
				&& $oLinha->localizacao_diferenciada_escola != trim($oDadosEscola->ed18_i_locdiferenciada)) {
			$oDaoEscola->ed18_i_locdiferenciada = $oLinha->localizacao_diferenciada_escola;
		}

		$oDaoEscola->ed18_i_codigo = $oDadosEscola->ed18_i_codigo;
		$oDaoEscola->alterar($oDadosEscola->ed18_i_codigo);

		if ($oDaoEscola->erro_status == '0') {
			throw new Exception("Erro na alteração dos dados da escola. Erro da classe: ".$oDaoEscola->erro_msg);
		}
	}

	public function getAvaliacao() {

		$oDaoAvaliacaoEscola = db_utils::getDao("escoladadoscenso");
		$sSqlCodigoAvaliacao = $oDaoAvaliacaoEscola->sql_query_file(null,
				"ed308_avaliacaogruporesposta",
				null,
				"ed308_escola = {$this->iCodigoEscola}"
		);
		$rsCodigoAvaliacao = $oDaoAvaliacaoEscola->sql_record($sSqlCodigoAvaliacao);
		if ($oDaoAvaliacaoEscola->numrows > 0) {

			$iCodigoAvaliacao = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed308_avaliacaogruporesposta;
		} else {

			$oDaoAvaliacaoGrupoPergunta = db_utils::getDao("avaliacaogruporesposta");
			$oDaoAvaliacaoGrupoPergunta->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
			$oDaoAvaliacaoGrupoPergunta->db107_hora           = db_hora();
			$oDaoAvaliacaoGrupoPergunta->db107_usuario        = db_getsession("DB_id_usuario");
			$oDaoAvaliacaoGrupoPergunta->incluir(null);
			if ($oDaoAvaliacaoGrupoPergunta->erro_status == 0) {
				throw new Exception($oDaoAvaliacaoGrupoPergunta->erro_msg);
			}
			$oDaoAvaliacaoEscola->ed308_avaliacaogruporesposta = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
			$oDaoAvaliacaoEscola->ed308_escola          = $this->iCodigoEscola;
			$oDaoAvaliacaoEscola->incluir(null);
			if ($oDaoAvaliacaoEscola->erro_status == 0) {
				throw new Exception($oDaoAvaliacaoEscola->erro_msg);
			}
			$iCodigoAvaliacao = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
		}

		$oAvaliacao = new Avaliacao(3000000);
		$oAvaliacao->setAvaliacaoGrupo($iCodigoAvaliacao);
		return $oAvaliacao;
	}

	/**
	 * Validamos os dados informados para cada equipamento existente na escola, e se estes são valores válidos
	 * @param ExportacaoCenso2013 $oExportacaoCenso
	 * @param string $sEquipamento
	 * @return boolean
	 */
	public function validarEquipamentosExistentes (ExportacaoCenso2013 $oExportacaoCenso, $sEquipamento) {

		$lDadosInvalidos = true;

		if (strlen($sEquipamento) > 4) {

			$lDadosInvalidos  = false;
			$sMensagem        = "Quantidade de dígitos informado inválido. Para os equipamentos existentes na escola, é ";
			$sMensagem       .= "permitido no máximo 4 dígitos.";
			$oExportacaoCenso->logErro($sMensagem);
		}

		if (!empty($sEquipamento) && !DBNumber::isInteger($sEquipamento)) {

			$lDadosInvalidos  = false;
			$sMensagem        = "A quantidade informada para um ou mais equipamentos existentes na escola é inválido. Informe ";
			$sMensagem       .= "apenas números para a quantidade em cada.";
			$oExportacaoCenso->logErro($sMensagem);
		}

		return $lDadosInvalidos;
	}

	/**
	 * Validamos campos que possuem input text e necessitam informar determinada quantidade, se estes sao validos
	 * @param ExportacaoCenso2013 $oExportacaoCenso
	 * @param integer $iSequencial
	 * @param string $sEquipamento
	 * @return boolean
	 */
	public function validarEquipamentosGeral (ExportacaoCenso2013 $oExportacaoCenso, $iSequencial, $sEquipamento) {

		switch ($iSequencial) {

			case 3000002:

				$sMensagem = "Dados de Infra Estrutura da escola -> Campo Quantidade de Computadores: ";
				break;

			case 3000003:

				$sMensagem = "Dados de Infra Estrutura da escola -> Campo Quantidade de Computadores de Uso Administrativo: ";
				break;

			case 3000024:

				$sMensagem = "Dados de Infra Estrutura da escola -> Campo Quantidade de Computadores de Uso dos Alunos: ";
				break;

			case 3000019:

				$sMensagem = "Dados de Infra Estrutura da escola -> Campo Número de Salas de Aula Existentes na Escola: ";
				break;

			case 3000020:

				$sMensagem  = "Dados de Infra Estrutura da escola -> Campo Número de Salas Utilizadas Como Sala de Aula - ";
				$sMensagem .= "Dentro e Fora do Prédio: ";
				break;
		}

		$lDadosInvalidos = true;

		if (strlen($sEquipamento) > 4) {

			$lDadosInvalidos   = false;
			$sMensagemTamanho  = "Quantidade de dígitos informado inválido. É permitido no máximo 4 dígitos.";
			$oExportacaoCenso->logErro($sMensagem.$sMensagemTamanho);
		}

		if($sEquipamento != null){
			if (!DBNumber::isInteger($sEquipamento)) {
	
				$lDadosInvalidos   = false;
				$sMensagemInteiro  = "Valor informado para quantidade inválido. Informe apenas números no campo de quantidade.";
				$oExportacaoCenso->logErro($sMensagem.$sMensagemInteiro);
			}
		}
		
		return $lDadosInvalidos;
	}
}
?>