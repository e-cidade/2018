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
    $sCamposEscola   .= " trim(ed18_c_codigoinep)            as codigo_escola_inep, ";
    $sCamposEscola   .= " ed18_i_funcionamento               as situacao_funcionamento, ";
    $sCamposEscola   .= " trim(ed18_c_nome)                  as nome_escola, ";
    $sCamposEscola   .= " trim(ed18_c_cep)                   as cep, ";
    $sCamposEscola   .= " trim(j14_nome)                     as endereco, ";
    $sCamposEscola   .= " ed18_i_numero                      as endereco_numero, ";
    $sCamposEscola   .= " trim(ed18_c_compl)                 as complemento_endereco, ";
    $sCamposEscola   .= " trim(j13_descr)                    as bairro, ";
    $sCamposEscola   .= " ed18_i_censouf                     as uf, ";
    $sCamposEscola   .= " ed18_i_censomunic                  as municipio, ";
    $sCamposEscola   .= " ed262_i_coddistrito                as distrito, ";
    $sCamposEscola   .= " trim(ed18_c_email)                 as endereco_eletronico, ";
    $sCamposEscola   .= " ed263_i_codigocenso                as codigo_orgao_regional_ensino, ";
    $sCamposEscola   .= " trim(ed18_c_mantenedora::varchar)  as dependencia_administrativa, ";
    $sCamposEscola   .= " trim(ed18_c_local)                 as localizacao_zona_escola, ";
    $sCamposEscola   .= " ed18_i_categprivada                as categoria_escola_privada, ";
    $sCamposEscola   .= " ed18_i_conveniada                  as conveniada_poder_publico, ";
    $sCamposEscola   .= " trim(ed18_c_mantprivada)           as lista_mantenedoras, ";
    $sCamposEscola   .= " ed18_i_cnpjprivada                 as cnpj_escola_privada, ";
    $sCamposEscola   .= " ed18_i_credenciamento              as regulamentacao_autorizacao_conselho_orgao , ";
    $sCamposEscola   .= " ed18_i_cnpjmantprivada             as cnpj_mantenedora_principal_escola_privada, ";
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
      $oDadosEscola->lista_mantenedoras  = '     ';
    }
    if ($oDadosEscola->dependencia_administrativa != 4) {

      $oDadosEscola->cnpj_mantenedora_principal_escola_privada = '';
      $oDadosEscola->cnpj_escola_privada                       = '';
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

    $this->iCodigoInep                                                = $oDadosEscola->codigo_escola_inep;
    $oDadosEscola->nome_escola                                        = $this->removeCaracteres($oDadosEscola->nome_escola,          6);
    $oDadosEscola->endereco                                           = $this->removeCaracteres($oDadosEscola->endereco,             7);
    $oDadosEscola->bairro                                             = $this->removeCaracteres($oDadosEscola->bairro,               7);
    $oDadosEscola->complemento_endereco                               = $this->removeCaracteres($oDadosEscola->complemento_endereco, 7);
    $oDadosEscola->endereco_numero                                    = $this->removeCaracteres($oDadosEscola->endereco_numero,      5);
    $oDadosEscola->endereco_eletronico                                = $this->removeCaracteres($oDadosEscola->endereco_eletronico,  4);
    $oDadosEscola->mant_esc_privada_empresa_grupo_empresarial_pes_fis = trim(substr($oDadosEscola->lista_mantenedoras, 0, 1));
    $oDadosEscola->mant_esc_privada_sidicatos_associacoes_cooperativa = trim(substr($oDadosEscola->lista_mantenedoras, 1, 1));
    $oDadosEscola->mant_esc_privada_ong_internacional_nacional_oscip  = trim(substr($oDadosEscola->lista_mantenedoras, 2, 1));
    $oDadosEscola->mant_esc_privada_instituicoes_sem_fins_lucrativos  = trim(substr($oDadosEscola->lista_mantenedoras, 3, 1));
    $oDadosEscola->sistema_s_sesi_senai_sesc_outros                   = trim(substr($oDadosEscola->lista_mantenedoras, 4, 1));

    $oTelefone                        = $this->getTelefones();
    $oDadosEscola->ddd                = $oTelefone->ddd;
    $oDadosEscola->telefone           = $oTelefone->telefone;
    $oDadosEscola->telefone_publico_1 = $oTelefone->telefone_publico;
    $oDadosEscola->telefone_publico_2 = $oTelefone->telefone_outro;
    $oDadosEscola->fax                = $oTelefone->fax;

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

  /**
   * @deprecated    só usado até 2014 ver DadosCensoEscola2015.model.php -> getDadosAvaliacao
   */
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
    $aRespostasObjetivas[3000107] = "2";

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

      $sListaRespostasUsarTexto = "3000003, 3000024,3000154, 3000155, 3000156, 3000157,3000158,  3000159";

      $iCodigoAvaliacao  = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed308_avaliacaogruporesposta;
      $sSqlResposta      = "(select case when db103_avaliacaotiporesposta = 1        then cast(db104_sequencial as varchar) ";
      $sSqlResposta     .= "             when db103_sequencial IN ({$sListaRespostasUsarTexto}) then db106_resposta else";
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
   * @return stdClass
   */
  public function getTelefones() {

    $aTelefones    = array();
    $oDaoTelefones = new cl_telefoneescola();
    $sCampos       = "ed26_i_ddd as ddd, ed26_i_numero as numero, ed13_c_descr as tipo_telefone";
    $sSqlTelefones = $oDaoTelefones->sql_query(null, $sCampos, "ed26_i_numero", "ed26_i_escola = {$this->iCodigoEscola}" );
    $rsTelefones   = db_query($sSqlTelefones);

    if ( !$rsTelefones ) {
      throw new DBException("Erro ao buscar os telefones.\n" . pg_last_error());
    }
    $iTotalTelefones = pg_num_rows($rsTelefones);

    $oDadosTelefone                   = new stdClass();
    $oDadosTelefone->ddd              = '';
    $oDadosTelefone->fax              = '';
    $oDadosTelefone->telefone         = '';
    $oDadosTelefone->telefone_publico = '';
    $oDadosTelefone->telefone_outro   = '';

    for ($i = 0; $i < $iTotalTelefones; $i++) {

      $oTelefone = db_utils::fieldsMemory($rsTelefones, $i);

      $oDadosTelefone->ddd = $oTelefone->ddd;

      if (strpos(strtoupper($oTelefone->tipo_telefone), "FAX") !== false) {
        $oDadosTelefone->fax = $oTelefone->numero;
        continue;
      }

      if ( empty($oDadosTelefone->telefone_publico) && strlen($oTelefone->numero) == 8 ) {
        $oDadosTelefone->telefone_publico = $oTelefone->numero;
        continue;
      }

      if ( empty($oDadosTelefone->telefone) ) {
        $oDadosTelefone->telefone = $oTelefone->numero;
        continue;
      }


      if ( empty($oDadosTelefone->telefone_outro) ) {
        $oDadosTelefone->telefone_outro = $oTelefone->numero;
        continue;
      }
    }

    return $oDadosTelefone;
  }

  /**
   * Valida os dados do arquivo
   * @param IExportacaoCenso instancia da Importacao do censo
   * @return boolean
   */
  public function validarDados(IExportacaoCenso $oExportacaoCenso) {

    $lTodosDadosValidos = true;
    $oDadosEscola       = $oExportacaoCenso->getDadosProcessadosEscola();
    $sMensagem          = "";

    /**
     * Início da validação dos campos obrigatórios
    */
    if( trim( $oDadosEscola->registro00->codigo_escola_inep ) == '' ) {

      $sMensagem          = "É necessário informar o código INEP da escola.";
      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
    }

    if( trim( $oDadosEscola->registro00->codigo_escola_inep ) != '' ) {

      if( !DBNumber::isInteger( $oDadosEscola->registro00->codigo_escola_inep ) ) {

        $sMensagem          = "Código INEP da escola inválido. O código INEP deve conter apenas números.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( strlen( trim( $oDadosEscola->registro00->codigo_escola_inep ) ) != 8 ) {

        $sMensagem          = "Código INEP da escola inválido. O código INEP deve conter 8 dígitos.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }
    }

    if (trim($oDadosEscola->registro00->nome_escola) == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome da Escola não pode ser vazio", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if (    trim( $oDadosEscola->registro00->nome_escola ) != ''
         && strlen( $oDadosEscola->registro00->nome_escola ) < 4
       ) {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome da Escola deve conter no mínimo 4 dígitos", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->cep == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo CEP é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if( !empty( $oDadosEscola->registro00->cep ) ) {

      if( !DBNumber::isInteger( $oDadosEscola->registro00->cep ) ) {

        $sMensagem          = "CEP inválido. Deve conter somente números.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if (strlen($oDadosEscola->registro00->cep) < 8) {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("CEP  da escola deve conter 8 dígitos.", ExportacaoCensoBase::LOG_ESCOLA);
      }
    }

    if ($oDadosEscola->registro00->endereco == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Endereço da escola é obrigatório.", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->uf == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("UF da escolas é obrigatório.", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->municipio == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo Munícipio da escola é obrigatório.", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->distrito == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo Distrito é obrigatório.", ExportacaoCensoBase::LOG_ESCOLA);
    }

    /**
     * Validações que serão executadas caso a situação de funcionamento seja igual a 1
     * 1 - em atividade
     */
    if ($oDadosEscola->registro00->situacao_funcionamento == 1) {


      if (trim($oDadosEscola->registro00->codigo_orgao_regional_ensino) == '' && $oDadosEscola->registro00->lOrgaoEnsinoObrigatorio) {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("Orgão Regional de Ensino obrigatório.", ExportacaoCensoBase::LOG_ESCOLA);
      }

      if ($oDadosEscola->registro00->data_inicio_ano_letivo == '') {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("Campo Data de Início do Ano Letivo é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
      }

      if ($oDadosEscola->registro00->data_termino_ano_letivo == '') {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("Campo Data de Término do Ano Letivo é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
      }

      if(    !empty( $oDadosEscola->registro00->data_inicio_ano_letivo )
          && !empty( $oDadosEscola->registro00->data_termino_ano_letivo ) ) {

        $oDataInicio  = new DBDate( $oDadosEscola->registro00->data_inicio_ano_letivo );
        $oDataTermino = new DBDate( $oDadosEscola->registro00->data_termino_ano_letivo );

        if( DBDate::calculaIntervaloEntreDatas( $oDataInicio, $oDataTermino, 'd' ) > 0 ) {

          $sMensagem          = "A data de início do ano letivo não pode ser maior que a data de término.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if( $oExportacaoCenso->getDataCenso() != '' ) {

          $oDataCenso   = new DBDate( $oExportacaoCenso->getDataCenso() );
          $iAnoAnterior = $oDataCenso->getAno() - 1;
          if(    $oDataInicio->getAno() != $oDataCenso->getAno()
              && $oDataInicio->getAno() != $iAnoAnterior
            ) {

            $sMensagem           = "Data de início do ano letivo inválido. Ano informado: {$oDataInicio->getAno()}";
            $sMensagem          .= " - Anos válidos: {$iAnoAnterior} ou {$oDataCenso->getAno()}";
            $lTodosDadosValidos  = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }

          if(    DBDate::calculaIntervaloEntreDatas( $oDataCenso, $oDataTermino, 'd' ) > 0
              || $oDataTermino->getAno() != $oDataCenso->getAno()
            ) {

            $sMensagem           = "Data de término do ano letivo inválido. O ano deve ser o mesmo do censo";
            $sMensagem          .= " ( {$oDataCenso->getAno()} ), e a data não pode ser inferior a data de referência";
            $sMensagem          .= " ( {$oDataCenso->getDate( DBDate::DATA_PTBR )} ).";
            $lTodosDadosValidos  = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }
        }
      }

      /**
       * Validações referentes aos telefones
       */
      for( $iContador = 0; $iContador <= 2; $iContador++ ) {

        $sPropriedadeTelefone = "telefone";
        $sMensagemTelefone    = "Telefone";

        if( $iContador > 0 ) {

          $sPropriedadeTelefone = "telefone_publico_{$iContador}";
          $sMensagemTelefone    = "Telefone Público {$iContador}";
        }

        if( $oDadosEscola->registro00->{$sPropriedadeTelefone} != '' ) {

          if( strlen( $oDadosEscola->registro00->{$sPropriedadeTelefone} ) < 8 ) {

            $sMensagem          = "Campo {$sMensagemTelefone} deve conter 8 dígitos";
            $lTodosDadosValidos = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }

          if (    substr($oDadosEscola->registro00->{$sPropriedadeTelefone}, 0, 1) != 9
               && strlen($oDadosEscola->registro00->{$sPropriedadeTelefone}) == 9
             ) {

            $sMensagem          = "Campo {$sMensagemTelefone}, ao conter 9 dígitos, o primeiro algarismo deve ser 9.";
            $lTodosDadosValidos = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }
        }
      }

      if ($oDadosEscola->registro00->fax != '') {

        if (strlen($oDadosEscola->registro00->fax) < 8) {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("Campo Fax deve conter 8 dígitos", ExportacaoCensoBase::LOG_ESCOLA);
        }
      }

      /**
       * Validações que serão executadas caso a situação de funcionamento seja igual a 1
       * 1 - em atividade
       * e caso a opção Dependência Administrativa selecionada seja igual a 4
       * 4 - privada
       */
      if ($oDadosEscola->registro00->dependencia_administrativa == 4) {

        if ( empty( $oDadosEscola->registro00->categoria_escola_privada ) ) {

          $lTodosDadosValidos  = false;
          $sErroMsg            = "Escola de Categoria Privada.\n";
          $sErroMsg           .= "Deve ser selecionada uma opção no campo Categoria da Escola Privada";
          $oExportacaoCenso->logErro($sErroMsg, ExportacaoCensoBase::LOG_ESCOLA);
        }

        if ($oDadosEscola->registro00->conveniada_poder_publico == '') {

          $sMensagem          = "Deve ser selecionada uma opção no campo Conveniada Com o Poder Público";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if (    $oDadosEscola->registro00->mant_esc_privada_empresa_grupo_empresarial_pes_fis  == 0
             && $oDadosEscola->registro00->mant_esc_privada_sidicatos_associacoes_cooperativa  == 0
             && $oDadosEscola->registro00->mant_esc_privada_ong_internacional_nacional_oscip   == 0
             && $oDadosEscola->registro00->mant_esc_privada_instituicoes_sem_fins_lucrativos   == 0
             && $oDadosEscola->registro00->sistema_s_sesi_senai_sesc_outros                    == 0
           ) {

          $sMensagem          = "Deve ser selecionado pelo menos um campo de Mantenedora da Escola Privada";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if ($oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada == '') {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("O CNPJ da mantenedora principal da escola deve ser informado", ExportacaoCensoBase::LOG_ESCOLA);
        }

        if(    $oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada != ''
            && !DBNumber::isInteger( $oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada )
          ) {

          $sMensagem          = "O CNPJ da mantenedora principal deve conter somente números.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if ($oDadosEscola->registro00->cnpj_escola_privada == '') {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("O CNPJ da escola deve ser informado quando escola for privada.", ExportacaoCensoBase::LOG_ESCOLA);
        }
      }

      if( $oDadosEscola->registro00->regulamentacao_autorizacao_conselho_orgao == '' ) {

        $sMensagem          = "Deve ser informada uma opção referente ao Credenciamento da escola.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }
    }

    $lDadosInfraEstrutura = DadosCensoEscola::validarDadosInfraEstrutura($oExportacaoCenso);
    if (!$lDadosInfraEstrutura) {
      $lTodosDadosValidos = $lDadosInfraEstrutura;
    }

    return $lTodosDadosValidos;
  }

  /**
   * Realizada a validacao dos dados da InfraEstrutura da escola
   * @param IExportacaoCenso $oExportacaoCenso
   */
  protected function validarDadosInfraEstrutura(IExportacaoCenso $oExportacaoCenso) {

    $lTodosDadosValidos     = true;
    $oDadosEscola           = $oExportacaoCenso->getDadosProcessadosEscola();
    $aDadosTurma            = $oExportacaoCenso->getDadosProcessadosTurma();

    $aEquipamentosValidacao = array(
                                      0  => "equipamentos_existentes_escola_televisao",
                                      1  => "equipamentos_existentes_escola_videocassete",
                                      2  => "equipamentos_existentes_escola_dvd",
                                      3  => "equipamentos_existentes_escola_antena_parabolica",
                                      4  => "equipamentos_existentes_escola_copiadora",
                                      5  => "equipamentos_existentes_escola_retroprojetor",
                                      6  => "equipamentos_existentes_escola_impressora",
                                      7  => "equipamentos_existentes_escola_aparelho_som",
                                      8  => "equipamentos_existentes_escola_projetor_datashow",
                                      9  => "equipamentos_existentes_escola_fax",
                                      10 => "equipamentos_existentes_escola_maquina_fotografica"
                                   );

    $aEstruturaValidacao    = array(
                                      3000032 => "equipamentos_existentes_escola_computador",
                                      3000003 => "quantidade_computadores_uso_administrativo",
                                      3000024 => "quantidade_computadores_uso_alunos",
                                      3000019 => "numero_salas_aula_existentes_escola",
                                      3000020 => "numero_salas_usadas_como_salas_aula"
                                   );

    $aDependenciasEscola = array(
                                  "dependencias_existentes_escola_sala_professores",
                                  "dependencias_existentes_escola_bercario",
                                  "dependencias_existentes_escola_despensa",
                                  "dependencias_existentes_escola_area_verde",
                                  "dependencias_existentes_escola_almoxarifado",
                                  "dependencias_existentes_escola_auditorio",
                                  "dependencias_existentes_escola_banheiro_alunos_def",
                                  "dependencias_existentes_escola_banheiro_chuveiro",
                                  "dependencias_existentes_escola_sala_leitura",
                                  "dependencias_existentes_escola_sala_recursos_multi",
                                  "dependencias_existentes_escola_banheiro_educ_infan",
                                  "dependencias_existentes_escola_cozinha",
                                  "dependencias_existentes_escola_alojamento_professo",
                                  "dependencias_existentes_escola_laboratorio_ciencia",
                                  "dependencias_existentes_escola_patio_coberto",
                                  "dependencias_existentes_escola_alojamento_aluno",
                                  "dependencias_existentes_escola_laboratorio_informa",
                                  "dependencias_existentes_escola_refeitorio",
                                  "dependencias_existentes_escola_parque_infantil",
                                  "dependencias_existentes_escola_banheiro_fora_predi",
                                  "dependencias_existentes_escola_quadra_esporte_desc",
                                  "dependencias_existentes_escola_sala_secretaria",
                                  "dependencias_existentes_escola_dep_vias_alunos_def",
                                  "dependencias_existentes_escola_banheiro_dentro_pre",
                                  "dependencias_existentes_escola_lavanderia",
                                  "dependencias_existentes_escola_quadra_esporte_cobe",
                                  "dependencias_existentes_escola_biblioteca",
                                  "dependencias_existentes_escola_sala_diretoria",
                                  "dependencias_existentes_escola_patio_descoberto",
                                  "dependencias_existentes_escola_nenhuma_relacionada"
                                );

    $aModalidadesEtapas = array(
                                 "modalidade_ensino_regular",
                                 "modalidade_educacao_especial_modalidade_substutiva",
                                 "modalidade_educacao_jovens_adultos",
                                 "etapas_ensino_regular_creche",
                                 "etapas_ensino_regular_pre_escola",
                                 "etapas_ensino_regular_ensino_fundamental_8_anos",
                                 "etapas_ensino_regular_ensino_fundamental_9_anos",
                                 "etapas_ensino_regular_ensino_medio_medio",
                                 "etapas_ensino_regular_ensino_medio_integrado",
                                 "etapas_ensino_regular_ensino_medio_normal_magister",
                                 "etapas_ensino_regular_ensino_medio_profissional",
                                 "etapa_educacao_especial_creche",
                                 "etapa_educacao_especial_pre_escola",
                                 "etapa_educacao_especial_ensino_fundamental_8_anos",
                                 "etapa_educacao_especial_ensino_fundamental_9_anos",
                                 "etapa_educacao_especial_ensino_medio_medio",
                                 "etapa_educacao_especial_ensino_medio_integrado",
                                 "etapa_educacao_especial_ensino_medio_normal_magist",
                                 "etapa_educacao_especial_ensino_medio_educ_profis",
                                 "etapa_educacao_especial_eja_ensino_fundamental",
                                 "etapa_educacao_especial_eja_ensino_medio",
                                 "etapa_eja_ensino_fundamental",
                                 "etapa_eja_ensino_fundamental_projovem_urbano",
                                 "etapa_eja_ensino_medio"
                               );

    foreach ($aEquipamentosValidacao as $sEquipamentoValidacao) {

      if (!DadosCensoEscola::validarEquipamentosExistentes($oExportacaoCenso, $oDadosEscola->registro10->$sEquipamentoValidacao)) {
        $lTodosDadosValidos = false;
      }
      break;
    }

    $iComputadorExistentes = 0;
    foreach ($aEstruturaValidacao as $iSequencial => $sEstruturaValidacao) {

      if( $iSequencial == 3000032 ) {

        if( !empty( $oDadosEscola->registro10->$sEstruturaValidacao ) ) {
          $iComputadorExistentes = $oDadosEscola->registro10->$sEstruturaValidacao;
        }
      }

      if(    $iSequencial == 3000003
          && $oDadosEscola->registro10->$sEstruturaValidacao != ''
          && ( $iComputadorExistentes == 0 || $iComputadorExistentes < $oDadosEscola->registro10->$sEstruturaValidacao )
        ) {

        $lTodosDadosValidos  = false;
        $sMensagem           = "Quantidade de computadores informados para uso administrativo";
        $sMensagem          .= " ({$oDadosEscola->registro10->$sEstruturaValidacao}), é maior que a quantidade de";
        $sMensagem          .= " computadores existentes na escola ({$iComputadorExistentes}).";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if(    $iSequencial == 3000024
          && $oDadosEscola->registro10->$sEstruturaValidacao != ''
          && ( $iComputadorExistentes == 0 || $iComputadorExistentes < $oDadosEscola->registro10->$sEstruturaValidacao )
        ) {

        $lTodosDadosValidos  = false;
        $sMensagem           = "Quantidade de computadores informados para uso dos alunos";
        $sMensagem          .= " ({$oDadosEscola->registro10->$sEstruturaValidacao}), é maior que a quantidade de";
        $sMensagem          .= " computadores existentes na escola ({$iComputadorExistentes}).";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if (!DadosCensoEscola::validarEquipamentosGeral($oExportacaoCenso, $iSequencial, $oDadosEscola->registro10->$sEstruturaValidacao)) {
        $lTodosDadosValidos = false;
      }
    }

    if( $iComputadorExistentes > 0 ) {

      if( $oDadosEscola->registro10->acesso_internet === '' ) {

        $sMensagem           = "Deve ser informado se a escola possui acesso a internet ou não, pois foi informada a";
        $sMensagem          .= " quantidade de computadores que a mesma possui.";
        $lTodosDadosValidos  = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->acesso_internet == 1 && $oDadosEscola->registro10->banda_larga == '' ) {

        $sMensagem           = "Deve ser informado se a escola possui banda larga, pois foi informado que a mesma";
        $sMensagem          .= " possui acesso a internet.";
        $lTodosDadosValidos  = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }
    }

    if ($oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Número do CPF do gestor é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if (trim($oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel) == "00000000191") {

      $lTodosDadosValidos  = false;
      $sMensagem           = "Número do CPF ({$oDadosEscola->registro10->numero_cpf_responsavel_gestor_responsavel}) do";
      $sMensagem          .= " gestor informado é inválido.";
      $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro10->nome_gestor == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome do gestor é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
    }

    $sExpressao          = '/([a-zA-Z])\1{3}/';
    $lValidacaoExpressao = preg_match($sExpressao, $oDadosEscola->registro10->nome_gestor) ? true : false;

    if ($lValidacaoExpressao) {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome do gestor inválido. Não é possível informar mais de 4 letras repetidas em sequência.", ExportacaoCensoBase::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro10->cargo_gestor_responsavel == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Cargo do gestor é obrigatório", ExportacaoCensoBase::LOG_ESCOLA);
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

        $sMensagem          = "Deve ser selecionado pelo menos um local de funcionamento da escola.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      /**
       * Validações que serão executadas caso a situação de funcionamento seja igual a 1
       * 1 - em atividade
       * e o local de funcionamento seja igual a 7
       * 7 - Prédio escolar
       */
      if ($oDadosEscola->registro10->local_funcionamento_escola_predio_escolar == 1) {

        if ( empty( $oDadosEscola->registro10->forma_ocupacao_predio ) ) {

          $sMensagem          = "Escola Ativa. Deve ser selecionada uma forma de ocupação do prédio.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if ($oDadosEscola->registro10->predio_compartilhado_outra_escola == '') {

          $sMensagem          = "Escola Ativa. Deve ser informado se o prédio é compartilhado ou não com outra escola.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if( $oDadosEscola->registro10->predio_compartilhado_outra_escola == 1 ) {

          $iTotalCodigoVazio   = 0;
          $lErroCodigoVazio    = false;
          $lErroCodigoInvalido = false;

          for( $iContador = 1; $iContador <= 6; $iContador++ ) {

            $sCodigoEscolaCompartilhada = "codigo_escola_compartilha_" . $iContador;
            if( trim( $oDadosEscola->registro10->{$sCodigoEscolaCompartilhada} ) == '' ) {
              $iTotalCodigoVazio++;
            }

            if(    trim( $oDadosEscola->registro10->{$sCodigoEscolaCompartilhada} ) != ''
                && strlen( trim( $oDadosEscola->registro10->{$sCodigoEscolaCompartilhada} ) ) != 8 ) {
              $lErroCodigoInvalido = true;
            }
          }

          if( $iTotalCodigoVazio == 6 ) {
            $lErroCodigoVazio = true;
          }

          if( $lErroCodigoVazio ) {

            $sMensagem           = "Escola com prédio compartilhado. É necessário informar ao menos o código INEP de uma";
            $sMensagem          .= " escola que compartilhe o prédio.";
            $lTodosDadosValidos  = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }

          if( $lErroCodigoInvalido ) {

            $sMensagem           = "Código INEP de uma das escolas que compartilham o prédio, com tamanho inválido.";
            $sMensagem          .= " Tamanho válido: 8 dígitos.";
            $lTodosDadosValidos  = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
          }
        }
      }

      if ($oDadosEscola->registro10->agua_consumida_alunos == '') {

        $sMensagem          = "Deve ser selecionada um tipo de água consumida pelos alunos.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if ($oDadosEscola->registro10->destinacao_lixo_coleta_periodica == 0 &&
          $oDadosEscola->registro10->destinacao_lixo_queima           == 0 &&
          $oDadosEscola->registro10->destinacao_lixo_joga_outra_area  == 0 &&
          $oDadosEscola->registro10->destinacao_lixo_recicla          == 0 &&
          $oDadosEscola->registro10->destinacao_lixo_enterra          == 0 &&
          $oDadosEscola->registro10->destinacao_lixo_outros           == 0) {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("Destinação do lixo da escola não informado.", ExportacaoCensoBase::LOG_ESCOLA);
      }

      $lNumeroSalasExistentesValido = true;
      if( !DBNumber::isInteger( trim( $oDadosEscola->registro10->numero_salas_aula_existentes_escola ) ) ) {

        $lNumeroSalasExistentesValido = false;
        $sMensagem                    = "Valor do 'N° de Salas de Aula Existentes na Escola' inválido. Deve ser";
        $sMensagem                   .= " informado somente números.";
        $lTodosDadosValidos           = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if (    $lNumeroSalasExistentesValido
           && trim( $oDadosEscola->registro10->numero_salas_aula_existentes_escola == 0 )
         ) {

        $sMensagem          = "O valor do campo 'N° de Salas de Aula Existentes na Escola' deve ser maior que 0.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      $lNumeroSalasUsadasValido = true;
      if( !DBNumber::isInteger( trim( $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula ) ) ) {

        $lNumeroSalasUsadasValido = false;
        $sMensagem                = "Valor do 'N° de Salas Utilizadas como Sala de Aula' inválido. Deve ser";
        $sMensagem               .= " informado somente números.";
        $lTodosDadosValidos       = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if ( $lNumeroSalasUsadasValido && trim( $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula == 0 ) ) {

        $sMensagem          = "O valor do campo 'N° de Salas Utilizadas como Sala de Aula' deve ser maior de 0.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if ($oDadosEscola->registro10->escola_cede_espaco_turma_brasil_alfabetizado == '') {

        $sMensagem          = "Informe se a escola cede espaço para turmas do Brasil Alfabetizado.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if ($oDadosEscola->registro10->escola_abre_finais_semanas_comunidade == '') {

        $sMensagem          = "Informe se a escola abre aos finais de semana para a comunidade.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if ($oDadosEscola->registro10->escola_formacao_alternancia == '') {

        $sMensagem          = "Informe se a escola possui proposta pedagógica de formação por alternância.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if (    $oDadosEscola->registro00->dependencia_administrativa != 3
           && trim( $oDadosEscola->registro10->alimentacao_escolar_aluno ) != 1
         ) {

        $lTodosDadosValidos  = false;
        $sMensagem           = "Campo Alimentação na aba Infra estrutura do cadastro dados da escola deve ser ";
        $sMensagem          .= "informado como Oferece, pois dependência administrativa é Municipal.";
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
      }

      if( $oDadosEscola->registro10->abastecimento_agua_inexistente == 1 ) {

        if(    $oDadosEscola->registro10->abastecimento_agua_fonte_rio_igarape_riacho_correg == 1
            || $oDadosEscola->registro10->abastecimento_agua_poco_artesiano                  == 1
            || $oDadosEscola->registro10->abastecimento_agua_cacimba_cisterna_poco           == 1
            || $oDadosEscola->registro10->abastecimento_agua_rede_publica                    == 1
          ) {

          $lTodosDadosValidos  = false;
          $sMensagem           = "Informação de abastecimento de água inválida. Ao selecionar a opção 'Inexistente'";
          $sMensagem          .= ", nenhum dos tipos de abastecimento deve ser selecionado.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
        }
      }

      if( $oDadosEscola->registro10->abastecimento_energia_eletrica_inexistente == 1 ) {

        if(    $oDadosEscola->registro10->abastecimento_energia_eletrica_gerador             == 1
            || $oDadosEscola->registro10->abastecimento_energia_eletrica_outros_alternativa  == 1
            || $oDadosEscola->registro10->abastecimento_energia_eletrica_rede_publica        == 1
        ) {

          $lTodosDadosValidos   = false;
          $sMensagem            = "Informação de abastecimento de energia inválida. Ao selecionar a opção 'Inexistente'";
          $sMensagem           .= ", nenhum dos tipos de abastecimento deve ser selecionado.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
        }
      }

      if( $oDadosEscola->registro10->esgoto_sanitario_inexistente == 1 ) {

        if(    $oDadosEscola->registro10->esgoto_sanitario_rede_publica == 1
            || $oDadosEscola->registro10->esgoto_sanitario_fossa        == 1
        ) {

          $lTodosDadosValidos   = false;
          $sMensagem            = "Informação de esgoto sanitário inválida. Ao selecionar a opção 'Inexistente'";
          $sMensagem           .= ", nenhum dos tipos de abastecimento deve ser selecionado.";
          $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
        }
      }

      if( $oDadosEscola->registro10->dependencias_existentes_escola_nenhuma_relacionada == 1 ) {

        $lSelecionouDependencia = false;
        foreach( $aDependenciasEscola as $sDependencia ) {

          if(    $oDadosEscola->registro10->{$sDependencia} != "dependencias_existentes_escola_nenhuma_relacionada"
              && $oDadosEscola->registro10->{$sDependencia} == 1
            ) {

            $lSelecionouDependencia = true;
            break;
          }
        }

        if( $lSelecionouDependencia ) {

          $sMensagem            = "Informação das dependências da escola inválida. Ao selecionar 'Nenhuma das dependências";
          $sMensagem           .= " relacionadas', nenhuma das outras dependências pode ser selecionada.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }
      }


      /**
       * Variáveis para controlar o total de turmas existentes
       * 0 - Normal
       * 4 - AEE
       * 5 - AC
       */
      $iTotalTurmaNormal = 0;
      $iTotalTurmaAC     = 0;
      $iTotalTurmaAEE    = 0;

      foreach( $aDadosTurma as $oDadosTurma ) {

        switch( $oDadosTurma->tipo_atendimento ) {

          case 0:

            $iTotalTurmaNormal++;
            break;

          case 4:

            $iTotalTurmaAC++;
            break;

          case 5:

            $iTotalTurmaAEE++;
            break;
        }
      }

      /**
       * Validações referentes as opções selecionadas em relação a Atividade Complementar( AC ) e Atendimento
       * Educacional Especializado( AEE )
       * - atendimento_educacional_especializado
       * - atividade_complementar
       */
      if(    $oDadosEscola->registro10->atendimento_educacional_especializado == ''
          || $oDadosEscola->registro10->atividade_complementar == ''
        ) {

        $sMensagem            = "É necessário marcar uma das opções referentes a Atendimento Educacional Especializado";
        $sMensagem           .= " e Atividade Complementar( Cadastros -> Dados da Escola -> Aba Infra Estrutura ).";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->atendimento_educacional_especializado == 2 ) {

        if( $oDadosEscola->registro10->atividade_complementar == 2 ) {

          $sMensagem            = "Atendimento Educacional Especializado e Atividade Complementar foram marcados como";
          $sMensagem           .= " 'Exclusivamente'. Ao marcar uma das opções como 'Exclusivamente', a outra deve";
          $sMensagem           .= " ser marcada como 'Não Oferece'.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if( $iTotalTurmaNormal > 0 ) {

          $sMensagem            = "Atendimento Educacional Especializado marcado como 'Exclusivamente'. Ao marcar esta";
          $sMensagem           .= " opção, a escola não deve turmas de Ensino Regular, EJA e/ou Educação Especial, ou";
          $sMensagem           .= " deve ser alterada a opção marcada.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if( $iTotalTurmaAEE == 0 ) {

          $sMensagem            = "Ao selecionar Atendimento Educacional Especializado como 'Exclusivamente', deve";
          $sMensagem           .= " existir na escola uma turma deste tipo criada e com aluno matriculado.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }
      }

      if( $oDadosEscola->registro10->atividade_complementar == 2 ) {

        if( $iTotalTurmaNormal > 0 ) {

          $sMensagem            = "Atividade Complementar marcada como 'Exclusivamente'. Ao marcar esta opção, a escola";
          $sMensagem           .= " não deve turmas de Ensino Regular, EJA e/ou Educação Especial, ou deve ser alterada";
          $sMensagem           .= " a opção marcada.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }

        if( $iTotalTurmaAC == 0 ) {

          $sMensagem            = "Ao selecionar Atividade Complementar como 'Exclusivamente', deve existir na escola";
          $sMensagem           .= " uma turma deste tipo criada e com aluno matriculado.";
          $lTodosDadosValidos   = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
        }
      }

      if(    (    $oDadosEscola->registro10->atendimento_educacional_especializado == 1
               && $oDadosEscola->registro10->atividade_complementar == 2 )
          || (    $oDadosEscola->registro10->atividade_complementar == 1
               && $oDadosEscola->registro10->atendimento_educacional_especializado == 2 )
        ) {

        $sMensagem            = "Ao selecionar, ou Atendimento Educacional Especializado ou Atividade Complementar, como";
        $sMensagem           .= " 'Não Exclusivamente', obrigatoriamente a outra opção não poderá ser marcada como";
        $sMensagem           .= " 'Exclusivamente'.";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->atendimento_educacional_especializado == 1 && $iTotalTurmaAEE == 0 ) {

        $sMensagem            = "Ao selecionar Atendimento Educacional Especializado como 'Não Exclusivamente', deve";
        $sMensagem           .= " existir na escola uma turma deste tipo criada e com aluno matriculado.";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->atividade_complementar == 1 && $iTotalTurmaAC == 0 ) {

        $sMensagem            = "Ao selecionar Atividade Complementar como 'Não Exclusivamente', deve";
        $sMensagem           .= " existir na escola uma turma deste tipo criada e com aluno matriculado.";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->atendimento_educacional_especializado == 0 && $iTotalTurmaAEE > 0 ) {

        $sMensagem            = "Atendimento Educacional Especializado marcado como 'Não Oferece', porém a escola possui";
        $sMensagem           .= " turmas cadastradas para este tipo de atendimento.";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }

      if( $oDadosEscola->registro10->atividade_complementar == 0 && $iTotalTurmaAC > 0 ) {

        $sMensagem            = "Atividade Complementa marcado como 'Não Oferece', porém a escola possui turmas";
        $sMensagem           .= " cadastradas para este tipo de atendimento.";
        $lTodosDadosValidos   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      }
    }

    return $lTodosDadosValidos;
  }

  protected function getCensoOrgaoRegional($iCodigoCensoUf, $sCodigoOrgaoRegionalEnsino) {

    $oDaoCensoOrgReg  = new cl_censoorgreg();
    $sWhereCensoOrg   = "ed263_i_censouf = {$iCodigoCensoUf} ";
    $sWhereCensoOrg  .= " and ed263_i_codigocenso = '{$sCodigoOrgaoRegionalEnsino}'";
    $sSqlOrgReg       = $oDaoCensoOrgReg->sql_query("", "ed263_i_codigo", "", $sWhereCensoOrg);
    $rsCensoOrgReg    = $oDaoCensoOrgReg->sql_record($sSqlOrgReg);

    if ($oDaoCensoOrgReg->numrows > 0) {
      return db_utils::fieldsmemory($rsCensoOrgReg, 0)->ed263_i_codigo;
    }

    return "null";
  }

  public function getDistrito($iMunicipioCenso, $iDistritoCenso) {

    $oDaoCensoDistrito = new cl_censodistrito();
    $sWhere            = "ed262_i_censomunic = {$iMunicipioCenso}";
    $sWhere           .= " and ed262_i_coddistrito = {$iDistritoCenso}";
    $sSqlCensoDistrito = $oDaoCensoDistrito->sql_query("", "ed262_i_codigo", "", $sWhere);
    $rsCensoDistrito   = $oDaoCensoDistrito->sql_record($sSqlCensoDistrito);

    if ($oDaoCensoDistrito->numrows > 0) {
      return db_utils::fieldsmemory($rsCensoDistrito, 0)->ed262_i_codigo;
    }
    return "null";
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

    $oDaoEscola->ed18_i_censodistrito = $this->getDistrito($oLinha->municipio, $oLinha->distrito);
    $oDaoEscola->ed18_i_censoorgreg  = $this->getCensoOrgaoRegional($oLinha->uf, $oLinha->codigo_orgao_regional_ensino);

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
   * Validações do registro 10 colunas: 71 a 83
   * @param IExportacaoCenso $oExportacaoCenso
   * @param string           $sEquipamento
   * @return boolean
   */
  public static function validarEquipamentosExistentes (IExportacaoCenso $oExportacaoCenso, $sEquipamento, $sNomeEquipamento = null) {

    $lTodosDadosValidos = true;

    // regra 1
    if (strlen($sEquipamento) > 4) {

      $sMensagem  = "Quantidade de equipamentos existentes na escola - {$sNomeEquipamento} ";
      $sMensagem .= "está maior que o especificado.. Não pode ter mais de 4 caracteres.";
      $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
      $lTodosDadosValidos  = false;
    }
    // regra 2
    if (!empty($sEquipamento) && !DBNumber::isInteger($sEquipamento)) {

      $sMensagem  = "Quantidade de equipamentos existentes na escola - {$sNomeEquipamento} ";
      $sMensagem .= "foi preenchido com valor inválido.";
      $oExportacaoCenso->logErro($sMensagem, ExportacaoCensoBase::LOG_ESCOLA);
      $lTodosDadosValidos  = false;
    }
    // regra 3
    if( DBNumber::isInteger($sEquipamento) && $sEquipamento == 0 ) {

      $sMensagem  = "Quantidade de equipamentos existentes na escola - {$sNomeEquipamento} ";
      $sMensagem .= "não pode ser preenchido apenas com zeros.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCensoBase::LOG_ESCOLA );
      $lTodosDadosValidos  = false;
    }

    return $lTodosDadosValidos;
  }

  /**
   * Validamos campos que possuem input text e necessitam informar determinada quantidade, se estes sao validos
   * @param IExportacaoCenso $oExportacaoCenso
   * @param integer $iSequencial
   * @param string $sEquipamento
   * @return boolean
   */
  public function validarEquipamentosGeral (IExportacaoCenso $oExportacaoCenso, $iSequencial, $sEquipamento) {

    $sMensagem = "";
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

    $lTodosDadosValidos = true;

    if (strlen($sEquipamento) > 4) {

      $lTodosDadosValidos = false;
      $sMensagemTamanho   = "Quantidade de dígitos informado inválido. É permitido no máximo 4 dígitos.";
      $oExportacaoCenso->logErro( $sMensagem.$sMensagemTamanho, ExportacaoCensoBase::LOG_ESCOLA );
    }

    if( $sEquipamento != null ) {

      if( !DBNumber::isInteger( $sEquipamento ) ) {

        $lTodosDadosValidos = false;
        $sMensagemInteiro   = "Valor informado para quantidade inválido. Informe apenas números no campo de quantidade.";
        $oExportacaoCenso->logErro( $sMensagem.$sMensagemInteiro, ExportacaoCensoBase::LOG_ESCOLA );
      }
    }

    if( DBNumber::isInteger($sEquipamento) && $sEquipamento == 0 ) {

      $lTodosDadosValidos = false;
      $sMensagemInvalido  = "Valor informado para quantidade inválido. Deixar o campo vazio, ao invés de informar 0.";
      $oExportacaoCenso->logErro( $sMensagem.$sMensagemInvalido, ExportacaoCensoBase::LOG_ESCOLA );
    }

    return $lTodosDadosValidos;
  }
}
?>