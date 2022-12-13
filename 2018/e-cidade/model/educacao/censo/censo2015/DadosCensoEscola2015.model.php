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

class DadosCensoEscola2015 extends DadosCensoEscola {

  /**
   * Dados do registro 00
   * @var stdClass
   */
  private $oRegistro00;


  /**
   * Dados do registro 10
   * @var stdClass
   */
  private $oRegistro10;

  /**
   * Método Construtor
   */
  function __construct($iCodigoEscola, $iAnoCenso, $dtBaseCenso) {

    $this->iAnoCenso     = $iAnoCenso;
    $this->iCodigoEscola = $iCodigoEscola;
    $this->dtBaseCenso   = $dtBaseCenso;

  }

  public function getDadosRegistro00() {

    if (empty($this->oRegistro00)) {

      $oDadosEscola = $this->getDadosIdentificacao();
      $oDadosGestor = $this->getDadosGestorEscola();

      $this->oRegistro00 = (object) array_merge((array) $oDadosEscola, (array) $oDadosGestor);

      $this->oRegistro00->tipo_registro                            = "00";
      $this->oRegistro00->unidade_vinculada_escola_educacao_basica = 0;
      $this->oRegistro00->codigo_escola_sede                       = "";
      $this->oRegistro00->codigo_ies                               = "";

    }

    return $this->oRegistro00;

  }

  public function getDadosRegistro10() {

    $this->getDadosRegistro00();
    $this->oRegistro10       = $this->getDadosInfraEstrutura();
    return $this->oRegistro10;
  }

  /**
   * Retorna os dados do gestor
   * @return stdClass dados do gestor da escola
   */
  private function getDadosGestorEscola() {

    $oDaoEscolaGestor     = new cl_escolagestorcenso();
    $sCamposEscolaGestor  = " ed254_i_codigo, ed20_i_tiposervidor, ";
    $sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
    $sCamposEscolaGestor .= "      then case when cgmrh.z01_nomecomple <> '' ";
    $sCamposEscolaGestor .= "                     then cgmrh.z01_nomecomple ";
    $sCamposEscolaGestor .= "                else cgmrh.z01_nome ";
    $sCamposEscolaGestor .= "            end ";
    $sCamposEscolaGestor .= "      else case when cgmcgm.z01_nomecomple <> '' ";
    $sCamposEscolaGestor .= "                     then cgmcgm.z01_nomecomple ";
    $sCamposEscolaGestor .= "                else cgmcgm.z01_nome ";
    $sCamposEscolaGestor .= "            end ";
    $sCamposEscolaGestor .= "  end as z01_nome, ";
    $sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
    $sCamposEscolaGestor .= "      then trim(cgmrh.z01_cgccpf) ";
    $sCamposEscolaGestor .= " else trim(cgmcgm.z01_cgccpf) end as z01_cgccpf, ";
    $sCamposEscolaGestor .= " case when ed20_i_tiposervidor = 1 ";
    $sCamposEscolaGestor .= "      then trim(rhfuncao.rh37_descr) ";
    $sCamposEscolaGestor .= " else 'DIRETOR' end as rh37_descr, trim(ed325_email) as ed325_email";
    $sWhereEscolaGestor   = " ed325_escola = {$this->iCodigoEscola} LIMIT 1";
    $sSqlEscolaGestor     = $oDaoEscolaGestor->sql_query_dados_gestor("", $sCamposEscolaGestor, "", $sWhereEscolaGestor);
    $rsEscolaGestor       = $oDaoEscolaGestor->sql_record($sSqlEscolaGestor);

    $oDadosGestorEscola                                     = new stdClass();
    $oDadosGestorEscola->numero_cpf_gestor_escolar          = '';
    $oDadosGestorEscola->nome_gestor_escolar                = '';
    $oDadosGestorEscola->cargo_gestor_escolar               = '';
    $oDadosGestorEscola->endereco_eletronico_gestor_escolar = '';
    if ($oDaoEscolaGestor->numrows > 0) {

      $oDadosGestor = db_utils::fieldsMemory($rsEscolaGestor, 0);
      $oDadosGestorEscola->numero_cpf_gestor_escolar = $oDadosGestor->z01_cgccpf;
      $oDadosGestorEscola->nome_gestor_escolar       = $this->removeCaracteres(trim($oDadosGestor->z01_nome), 1);
      $oDadosGestorEscola->cargo_gestor_escolar      = 1;

      if (empty($oDadosGestor->ed254_i_codigo)) {
        $oDadosGestorEscola->cargo_gestor_escolar = 2;
      }

      $oDadosGestorEscola->endereco_eletronico_gestor_escolar = strtoupper(trim($oDadosGestor->ed325_email));
    }
    return $oDadosGestorEscola;
  }

  public function criarObjetoInfraestrutura(){

    $oDadosInfraEstrutura                = new stdClass();
    $oDadosInfraEstrutura->tipo_registro = "10";
    $oDadosInfraEstrutura->equipamentos_existentes_escola_televisao           = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_videocassete        = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_dvd                 = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_antena_parabolica   = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_copiadora           = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_retroprojetor       = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_impressora          = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_aparelho_som        = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_projetor_datashow   = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_fax                 = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_maquina_fotografica = '';
    $oDadosInfraEstrutura->equipamentos_existentes_escola_computador          = '';
    $oDadosInfraEstrutura->equipamentos_impressora_multifuncional             = '';
    $oDadosInfraEstrutura->quantidade_computadores_uso_administrativo         = '';
    $oDadosInfraEstrutura->quantidade_computadores_uso_alunos                 = '';
    $oDadosInfraEstrutura->acesso_internet                                    = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_nenhuma_relacionada = '';
    $oDadosInfraEstrutura->numero_salas_aula_existentes_escola                = '';
    $oDadosInfraEstrutura->numero_salas_usadas_como_salas_aula                = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_predio_escolar          = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_templo_igreja           = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_salas_empresas          = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_casa_professor          = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_salas_outras_escolas    = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_galpao_rancho_paiol_bar = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_un_internacao_socio     = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_unidade_prisional       = '';
    $oDadosInfraEstrutura->local_funcionamento_escola_outros                  = '';
    $oDadosInfraEstrutura->abastecimento_agua_rede_publica                    = '';
    $oDadosInfraEstrutura->abastecimento_agua_poco_artesiano                  = '';
    $oDadosInfraEstrutura->abastecimento_agua_cacimba_cisterna_poco           = '';
    $oDadosInfraEstrutura->abastecimento_agua_fonte_rio_igarape_riacho_correg = '';
    $oDadosInfraEstrutura->abastecimento_agua_inexistente                     = '';
    $oDadosInfraEstrutura->abastecimento_energia_eletrica_inexistente         = '';
    $oDadosInfraEstrutura->destinacao_lixo_coleta_periodica                   = '';
    $oDadosInfraEstrutura->destinacao_lixo_queima                             = '';
    $oDadosInfraEstrutura->destinacao_lixo_joga_outra_area                    = '';
    $oDadosInfraEstrutura->destinacao_lixo_recicla                            = '';
    $oDadosInfraEstrutura->destinacao_lixo_enterra                            = '';
    $oDadosInfraEstrutura->destinacao_lixo_outros                             = '';
    $oDadosInfraEstrutura->esgoto_sanitario_inexistente                       = '';
    $oDadosInfraEstrutura->alimentacao_escolar_aluno                          = '';
    $oDadosInfraEstrutura->materiais_didaticos_especificos_nao_utiliza        = '';
    $oDadosInfraEstrutura->materiais_didaticos_especificos_quilombola         = '';
    $oDadosInfraEstrutura->materiais_didaticos_especificos_indigena           = '';
    $oDadosInfraEstrutura->atendimento_educacional_especializado              = '';
    $oDadosInfraEstrutura->atividade_complementar                             = '';
    $oDadosInfraEstrutura->modalidade_ensino_regular                          = 0;
    $oDadosInfraEstrutura->modalidade_educacao_especial_modalidade_substutiva = 0;
    $oDadosInfraEstrutura->modalidade_educacao_jovens_adultos                 = 0;
    $oDadosInfraEstrutura->modalidade_educacao_profissional                   = 0;
    $oDadosInfraEstrutura->codigo_escola_compartilha_1                        = '';
    $oDadosInfraEstrutura->codigo_escola_compartilha_2                        = '';
    $oDadosInfraEstrutura->codigo_escola_compartilha_3                        = '';
    $oDadosInfraEstrutura->codigo_escola_compartilha_4                        = '';
    $oDadosInfraEstrutura->codigo_escola_compartilha_5                        = '';
    $oDadosInfraEstrutura->codigo_escola_compartilha_6                        = '';
    $oDadosInfraEstrutura->ensino_fundamental_organizado_ciclos               = '0';
    $oDadosInfraEstrutura->forma_ocupacao_predio                              = '';
    $oDadosInfraEstrutura->predio_compartilhado_outra_escola                  = '';
    $oDadosInfraEstrutura->agua_consumida_alunos                              = '';
    $oDadosInfraEstrutura->abastecimento_energia_eletrica_rede_publica        = '';
    $oDadosInfraEstrutura->abastecimento_energia_eletrica_gerador             = '';
    $oDadosInfraEstrutura->abastecimento_energia_eletrica_outros_alternativa  = '';
    $oDadosInfraEstrutura->esgoto_sanitario_rede_publica                      = '';
    $oDadosInfraEstrutura->esgoto_sanitario_fossa                             = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_sala_diretoria      = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_sala_professores    = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_sala_secretaria     = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_laboratorio_informa = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_laboratorio_ciencia = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_sala_recursos_multi = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_quadra_esporte_cobe = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_quadra_esporte_desc = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_cozinha             = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_biblioteca          = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_sala_leitura        = '';
    $oDadosInfraEstrutura->dependencias_existentes_escola_parque_infantil     = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_bercario            = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_banheiro_fora_predi = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_banheiro_dentro_pre = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_banheiro_educ_infan = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_banheiro_alunos_def = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_dep_vias_alunos_def = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_banheiro_chuveiro   = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_refeitorio          = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_despensa            = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_almoxarifado        = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_auditorio           = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_patio_coberto       = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_patio_descoberto    = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_alojamento_aluno    = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_alojamento_professo = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_area_verde          = "";
    $oDadosInfraEstrutura->dependencias_existentes_escola_lavanderia          = "";
    $oDadosInfraEstrutura->escola_cede_espaco_turma_brasil_alfabetizado       = "";
    $oDadosInfraEstrutura->escola_abre_finais_semanas_comunidade              = "";
    $oDadosInfraEstrutura->escola_formacao_alternancia                        = "";

    return $oDadosInfraEstrutura;
  }

  public function getDadosInfraEstrutura() {

    $oDadosInfraEstrutura = $this->criarObjetoInfraestrutura();

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
        case 4:

          $oDadosInfraEstrutura->modalidade_educacao_profissional = 1;
          break;
      }
    }

    $oDadosInfraEstrutura->localizacao_diferenciada_escola = $this->oRegistro00->localizacao_diferenciada;
    $oDadosInfraEstrutura->educacao_indigena               = empty($this->oRegistro00->educacao_indigena) ? 0 : 1;
    $oDadosInfraEstrutura->codigo_escola_inep              = $this->oRegistro00->codigo_escola_inep;

    $oDadosInfraEstrutura->lingua_ensino_ministrado_lingua_indigena   = $this->oRegistro00->educacao_indigena_lingua_indigena;
    $oDadosInfraEstrutura->lingua_ensino_ministrada_lingua_portuguesa = $this->oRegistro00->educacao_indigena_lingua_portugues;
    $oDadosInfraEstrutura->codigo_lingua_indigena                     = $this->oRegistro00->codigo_censo_lingua_indigena;
    /**
     * Percorremos os dados das avaliacoes da escola, onde montamos o restante dos dados da infraEstrutra
     */
    foreach ($this->getDadosAvaliacao($oDadosInfraEstrutura) as $oRespostas) {
      $oDadosInfraEstrutura->{$oRespostas->campo} = $oRespostas->respostas;
    }

    if ( $oDadosInfraEstrutura->equipamentos_existentes_escola_computador === '' ) {
      $oDadosInfraEstrutura->acesso_internet = '';
    }

    if ( $oDadosInfraEstrutura->acesso_internet != 1 ) {
      $oDadosInfraEstrutura->banda_larga = '';
    }

    $lTemAtividadeExclusiva = $oDadosInfraEstrutura->atendimento_educacional_especializado == 2;
    if ( !$lTemAtividadeExclusiva && $oDadosInfraEstrutura->atividade_complementar == 2) {
      $lTemAtividadeExclusiva = true;
    }

    /**
     * Campos 92, 93, 94 e 95 implementado validação da regra 2 e 3
     */
    if ( $lTemAtividadeExclusiva ) {

      $oDadosInfraEstrutura->modalidade_ensino_regular                          = '';
      $oDadosInfraEstrutura->modalidade_educacao_especial_modalidade_substutiva = '';
      $oDadosInfraEstrutura->modalidade_educacao_jovens_adultos                 = '';
      $oDadosInfraEstrutura->modalidade_educacao_profissional                   = '';
    }

    return $oDadosInfraEstrutura;
  }

  /**
   * Retorna os dados da avaliação da escola
   * @param  stdClass $oDadosInfra dados da infraestrutura
   * @return array
   */
  public function getDadosAvaliacao ($oDadosInfra) {

    /**
     * Procuramos o codigo da avaliacao da escola.
     */
    $aRespostasObjetivas          = array();

    /**
     * 3000004 - Acesso à Internet
     * acesso_internet
     */
    $aRespostasObjetivas[3000035] = 1;   // SIM
    $aRespostasObjetivas[3000036] = 0; // NÃO

    /**
     * 3000017 - Água consumida pelos Alunos
     * agua_consumida_alunos
     */
    $aRespostasObjetivas[3000099] = 1; // NÃO FILTRADA
    $aRespostasObjetivas[3000100] = 2; // FILTRADA

    /**
     * 3000018 - Alimentação Escolar para os Alunos
     * alimentacao_escolar_aluno
     */
    $aRespostasObjetivas[3000101] = 1; // OFERECE
    $aRespostasObjetivas[3000102] = 0; // NÃO OFERECE

    /**
     * 3000022 - Atendimento Educ. Especializado AEE
     * atendimento_educacional_especializado
     */
    $aRespostasObjetivas[3000108] = 1;   // NÃO EXCLUSIVAMENTE
    $aRespostasObjetivas[3000109] = 2;   // EXCLUSIVAMENTE
    $aRespostasObjetivas[3000110] = 0; // NÃO OFERECE

    /**
     * 3000021 - Atividade Complementar
     *  atividade_complementar
     */
    $aRespostasObjetivas[3000105] = 1;    // NÃO EXCLUSIVAMENTE
    $aRespostasObjetivas[3000106] = 0;  // NÃO OFERECE
    $aRespostasObjetivas[3000107] = 2;  // EXCLUSIVAMENTE

    /**
     *  3000005 - Banda Larga
     *  banda_larga
     */
    $aRespostasObjetivas[3000037] = 1;   // Possui
    $aRespostasObjetivas[3000038] = 0; // Não Possui

    /**
     * 3000023 - Ensino Fundamental em ciclos
     * ensino_fundamental_organizado_ciclos
     */
    $aRespostasObjetivas[3000111] = 0; // NÃO
    $aRespostasObjetivas[3000112] = 1;   // SIM

    /**
     * 3000025 - Escola cede espaço para turmas do Brasil Alfabetizado
     *  escola_cede_espaco_turma_brasil_alfabetizado
     */
    $aRespostasObjetivas[3000122] = 1;   // SIM
    $aRespostasObjetivas[3000123] = 0; // NÃO

    /**
     * 3000007 - Forma de Ocupação do Prédio
     *  forma_ocupacao do prédio
     */
    $aRespostasObjetivas[3000047] = 1; // Próprio
    $aRespostasObjetivas[3000048] = 2; // Alugado
    $aRespostasObjetivas[3000049] = 3; // Cedido

    /**
     * 3000016 - Predio Compartilhad
     * predio_compartilhado_outra_escola
     */
    $aRespostasObjetivas[3000097] = 1; // SIM
    $aRespostasObjetivas[3000098] = 0; // NÃO

    /**
     * 3000026 - Escola abre aos finais de semana para a comunidade
     * escola_abre_finais_semanas_comunidade
     */
    $aRespostasObjetivas[3000124] = 1;   // SIM
    $aRespostasObjetivas[3000125] = 0; // NÃO


    /**
     * 3000153 - Escola com proposta pedagogica de formação por alternância
     * escola_formacao_alternancia
     */
    $aRespostasObjetivas[3000561] = 1;   // Sim
    $aRespostasObjetivas[3000562] = 0; // Não


    /**
     * Perguntas que nao podem ter respostas com valor 0.
     * esses respostas devem ficar com o valor vazio;
     */
    $aPerguntasLimparValorZero = array(3000010, 3000003, 3000024);

    $aPerguntasRespostaObrigatoria = array(3000000, 3000006, 3000014, 3000015, 3000008, 3000011,3000009);

    $sWhereAvaliacao      = "ed308_escola = {$this->iCodigoEscola}";
    $aPerguntas           = array();
    $oDaoEscolaDadosCenso = new cl_escoladadoscenso();
    $sSqlCodigoAvaliacao  = $oDaoEscolaDadosCenso->sql_query_file(null, "ed308_avaliacaogruporesposta", null, $sWhereAvaliacao);
    $rsCodigoAvaliacao    = $oDaoEscolaDadosCenso->sql_record($sSqlCodigoAvaliacao);

    if ($oDaoEscolaDadosCenso->numrows == 0) {
      $sSqlResposta = " '' ";
    } else {

      $aPerguntasDevemUsarTexto = array(3000003, 3000010, 3000019, 3000020, 3000024, 3000154, 3000155, 3000156, 3000157, 3000158, 3000159);

      $iCodigoAvaliacao  = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed308_avaliacaogruporesposta;
      $sSqlResposta      = "(select case when db103_avaliacaotiporesposta = 1        then cast(db104_sequencial as varchar) ";
      $sSqlResposta     .= "             when db103_sequencial IN (". implode(", ", $aPerguntasDevemUsarTexto) .") then db106_resposta else";
      $sSqlResposta     .= "  case when trim(db106_resposta) != '' then db106_resposta else '1' end  end ";
      $sSqlResposta     .= "   from avaliacaogrupoperguntaresposta ";
      $sSqlResposta     .= "        inner join avaliacaoresposta      on db108_avaliacaoresposta      = db106_sequencial ";
      $sSqlResposta     .= "  where db106_avaliacaoperguntaopcao = db104_sequencial ";
      $sSqlResposta     .= "   and db108_avaliacaogruporesposta  = {$iCodigoAvaliacao} limit 1)";
    }

    $sCamposPerguntas  = "distinct db52_nome as campo, db103_avaliacaotiporesposta as tipo_resposta, db103_sequencial, ";
    $sCamposPerguntas .= " {$sSqlResposta} as respostas";
    $sWherePerguntas   = " db102_avaliacao = 3000000 and ed313_ano = {$this->iAnoCenso} ";
    $oDaoPerguntas     = new cl_avaliacaoperguntaopcaolayoutcampo();
    $sSqlPerguntas     = $oDaoPerguntas->sql_query_avaliacao( null, $sCamposPerguntas, "db103_sequencial", $sWherePerguntas );
    $rsPerguntas       = $oDaoPerguntas->sql_record($sSqlPerguntas);
    $iTotalPerguntas   = $oDaoPerguntas->numrows;
    for ($iPergunta = 0; $iPergunta < $iTotalPerguntas; $iPergunta++) {

      $oPergunta         = db_utils::fieldsMemory($rsPerguntas, $iPergunta);
      $iRespostaPergunta = $oPergunta->respostas;

      /**
       * Trata as perguntas objetivas
       */
      if ($oPergunta->tipo_resposta == 1) {

        if ( !isset($aPerguntas[$oPergunta->campo]) ) {
          $aPerguntas[$oPergunta->campo] = $oPergunta;
        }

        if (trim($iRespostaPergunta) != "" ) {
          $aPerguntas[$oPergunta->campo]->respostas = $aRespostasObjetivas[$iRespostaPergunta];
        }

      } else {

        /**
         * Caso a pergunta seja 3000010 (equipamentos existentes) e a resposta esteja como zero, setamos a resposta
         * como vazio
         */
        $mResposta = trim($oPergunta->respostas);
        if ( in_array($oPergunta->db103_sequencial, $aPerguntasLimparValorZero) && empty($mResposta) ) {
          $oPergunta->respostas = '';
        }
        $aPerguntas[$oPergunta->campo] = $oPergunta;

        if ( in_array($oPergunta->db103_sequencial, $aPerguntasRespostaObrigatoria) && trim($oPergunta->respostas) == '') {
          $aPerguntas[$oPergunta->campo]->respostas = 0;
        }
      }
    }

    return $aPerguntas;
  }


  /**
   * Valida os dados do arquivo
   * @param IExportacaoCenso instancia da Importacao do censo
   * @return boolean
   */
  public function validarDados(IExportacaoCenso $oExportacaoCenso) {

    $lTodosDadosValidos = true;
    $sMensagem          = "";
    $oDadosEscola       = $oExportacaoCenso->getDadosProcessadosEscola();

    /**
     * Início da validação dos campos obrigatórios
    */
    if( trim( $oDadosEscola->registro00->codigo_escola_inep ) == '' ) {

      $sMensagem          = "É necessário informar o código INEP da escola.";
      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }
    // Validações do código INEP
    if( trim( $oDadosEscola->registro00->codigo_escola_inep ) != '' ) {

      if( !DBNumber::isInteger( $oDadosEscola->registro00->codigo_escola_inep ) ) {

        $sMensagem          = "Código INEP da escola inválido. O código INEP deve conter apenas números.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if( strlen( trim( $oDadosEscola->registro00->codigo_escola_inep ) ) != 8 ) {

        $sMensagem          = "Código INEP da escola inválido. O código INEP deve conter 8 dígitos.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }
    // valida os dados do gestor
    $lValidaAutenticacao = DadosCensoEscola2015::validaAutenticacao($oExportacaoCenso, $oDadosEscola->registro00);
     // se lTodosDadosValidos já esta false, mantem false
    if ( !$lValidaAutenticacao ) {
      $lTodosDadosValidos = $lValidaAutenticacao;
    }

    /**
     * Valida dados identificação
     */
    if (trim($oDadosEscola->registro00->nome_escola) == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome da Escola não pode ser vazio", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ( trim( $oDadosEscola->registro00->nome_escola ) != '' && strlen($oDadosEscola->registro00->nome_escola) < 4 ) {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Nome da Escola deve conter no mínimo 4 dígitos", ExportacaoCenso2015::LOG_ESCOLA);
    }

    $lvalidaLatitudeLongitude = DadosCensoEscola2015::validaLatitudeLongitude($oExportacaoCenso, $oDadosEscola->registro00);

    if ( !$lvalidaLatitudeLongitude ) {
      $lTodosDadosValidos = $lvalidaLatitudeLongitude;
    }

    if ($oDadosEscola->registro00->cep == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo CEP é obrigatório", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if( !empty( $oDadosEscola->registro00->cep ) ) {

      if( !DBNumber::isInteger( $oDadosEscola->registro00->cep ) ) {

        $sMensagem          = "CEP inválido. Deve conter somente números.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if (strlen($oDadosEscola->registro00->cep) < 8) {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("CEP  da escola deve conter 8 dígitos.", ExportacaoCenso2015::LOG_ESCOLA);
      }

      if (preg_match ('/1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}/', $oDadosEscola->registro00->cep)) {

        $lTodosDadosValidos = false;
        $sMensagem          = "O Campo CEP foi preenchido com um valor inválido. CEP: {$oDadosEscola->registro00->cep}";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    if ($oDadosEscola->registro00->endereco == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Endereço da escola é obrigatório.", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->uf == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("UF da escolas é obrigatório.", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->municipio == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo Munícipio da escola é obrigatório.", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ($oDadosEscola->registro00->distrito == '') {

      $lTodosDadosValidos = false;
      $oExportacaoCenso->logErro("Campo Distrito é obrigatório.", ExportacaoCenso2015::LOG_ESCOLA);
    }

    /**
     * Situação de funcionamento diferente de 1
     * coluna 7
     */
    if ($oDadosEscola->registro00->situacao_funcionamento != 1) {

      $sMsgPadrao = 'quando o campo situação de funcionamento da escola for diferente de 1 (em atividade)';
      if ( !empty($oDadosEscola->registro00->data_inicio_ano_letivo) ) {

        $lTodosDadosValidos = false;
        $sMensagem          = "O campo data de início letivo não pode ser preenchido {$sMsgPadrao}";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ( !empty($oDadosEscola->registro00->data_termino_ano_letivo) ) {

        $lTodosDadosValidos = false;
        $sMensagem          = "O campo data de término do ano letivo não pode ser preenchido {$sMsgPadrao}";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ( !is_null($oDadosEscola->registro00->unidade_vinculada_escola_educacao_basica) ) {
        $oDadosEscola->registro00->unidade_vinculada_escola_educacao_basica = '';
      }
    }

    /**
     * Validações que serão executadas caso a situação de funcionamento seja igual a 1
     * 1 - em atividade
     * coluna 7
     */
    if ($oDadosEscola->registro00->situacao_funcionamento == 1) {

      if (trim($oDadosEscola->registro00->codigo_orgao_regional_ensino) == '' && $oDadosEscola->registro00->lOrgaoEnsinoObrigatorio) {

        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro("Orgão Regional de Ensino obrigatório.", ExportacaoCenso2015::LOG_ESCOLA);
      }

      $dtInicioAnoLetivo = $oDadosEscola->registro00->data_inicio_ano_letivo;
      $dtFimAnoLetivo    = $oDadosEscola->registro00->data_termino_ano_letivo;

      if( !empty( $dtInicioAnoLetivo ) && !empty( $dtFimAnoLetivo ) ) {

        $oDataInicio  = null;
        $oDataTermino = null;
        try {
          $oDataInicio  = new DBDate( $dtInicioAnoLetivo );
        } catch( Exception $o) {

          $sMensagem  = "Data de início do ano letivo não é válida. Data informada: {$dtInicioAnoLetivo}. ";
          $sMensagem .= "Formato válido: dd/mm/aaaa.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro($sMensagem , ExportacaoCenso2015::LOG_ESCOLA );
        }

        try {
          $oDataTermino = new DBDate( $dtFimAnoLetivo );
        } catch( Exception $o) {

          $sMensagem  = "Data de início do ano letivo não é válida. Data informada: {$dtFimAnoLetivo}. ";
          $sMensagem .= "Formato válido: dd/mm/aaaa.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro($sMensagem , ExportacaoCenso2015::LOG_ESCOLA );
        }

        if( DBDate::calculaIntervaloEntreDatas( $oDataInicio, $oDataTermino, 'd' ) > 0 ) {

          $sMensagem          = "A data de início do ano letivo não pode ser maior que a data de término.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }
        if ($oDataInicio->getAno() < $oExportacaoCenso->getAnoCenso() && $oDataInicio->getAno() > $oExportacaoCenso->getAnoCenso()) {

          $sMensagem          = "Ano da data de início não pode ser inferior/superior a " . $oExportacaoCenso->getAnoCenso();
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }

        $oDataCenso = new DBDate( $oExportacaoCenso->getDataCenso() );
        if ($oDataTermino < $oDataCenso || $oDataTermino->getAno() > $oExportacaoCenso->getAnoCenso() ) {

          $sMensagem  = "Data de término (".$oDataTermino->getDate(DBDate::DATA_PTBR).") não pode ser inferior a data do censo (";
          $sMensagem .= $oExportacaoCenso->getDataCenso() .")e o ano de referência não pode ser superior a " . $oExportacaoCenso->getAnoCenso();

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }
      }

      $sTelefone         = $oDadosEscola->registro00->telefone;
      $sTelefonePublico1 = $oDadosEscola->registro00->telefone_publico_1;
      $sTelefonePublico2 = $oDadosEscola->registro00->telefone_publico_2;
      $sFax              = $oDadosEscola->registro00->fax;

      if (    !empty( $oDadosEscola->registro00->ddd )
           && empty( $sTelefone )
           && empty( $sTelefonePublico1 )
           && empty( $sTelefonePublico2 )
           && empty( $sFax )
         ) {

        $sMensagem          = "Se informado DDD, deve ser informado um telefone para a escola.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
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
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
          }

          if (    substr($oDadosEscola->registro00->{$sPropriedadeTelefone}, 0, 1) != 9
               && strlen($oDadosEscola->registro00->{$sPropriedadeTelefone}) == 9
             ) {

            $sMensagem          = "Campo {$sMensagemTelefone}, ao conter 9 dígitos, o primeiro algarismo deve ser 9.";
            $lTodosDadosValidos = false;
            $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
          }
        }
      }

      if ($oDadosEscola->registro00->fax != '') {

        if (strlen($oDadosEscola->registro00->fax) < 8) {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("Campo Fax deve conter 8 dígitos", ExportacaoCenso2015::LOG_ESCOLA);
        }
      }

      if(    !empty( $oDadosEscola->registro00->endereco_eletronico )
          && !DBString::isEmail( $oDadosEscola->registro00->endereco_eletronico ) ) {

        $lTodosDadosValidos = false;
        $sMensagem          = "E-mail da escola inválido: {$oDadosEscola->registro00->endereco_eletronico}";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( !empty( $oDadosEscola->registro00->categoria_escola_privada ) && $oDadosEscola->registro00->dependencia_administrativa != 4 ) {

        $lTodosDadosValidos  = false;
        $sMensagem = "Escola de Categoria Privada a Dependência administrativa deve ser igual a 4 - privada.";
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( !empty( $oDadosEscola->registro00->conveniada_poder_publico ) && $oDadosEscola->registro00->dependencia_administrativa != 4 ) {

        $lTodosDadosValidos  = false;
        $sMensagem = "Se informado \"Conveniada com o poder publico\" a Dependência administrativa deve ser igual a 4 - privada.";
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
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
          $oExportacaoCenso->logErro($sErroMsg, ExportacaoCenso2015::LOG_ESCOLA);
        }

        if ($oDadosEscola->registro00->conveniada_poder_publico == '') {

          $sMensagem          = "Deve ser selecionada uma opção no campo Conveniada Com o Poder Público";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }

        if (    $oDadosEscola->registro00->mant_esc_privada_empresa_grupo_empresarial_pes_fis  == 0
             && $oDadosEscola->registro00->mant_esc_privada_sidicatos_associacoes_cooperativa  == 0
             && $oDadosEscola->registro00->mant_esc_privada_ong_internacional_nacional_oscip   == 0
             && $oDadosEscola->registro00->mant_esc_privada_instituicoes_sem_fins_lucrativos   == 0
             && $oDadosEscola->registro00->sistema_s_sesi_senai_sesc_outros                    == 0
           ) {

          $sMensagem          = "Deve ser selecionado pelo menos um campo de Mantenedora da Escola Privada";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }

        if ($oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada == '') {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("O CNPJ da mantenedora principal da escola deve ser informado", ExportacaoCenso2015::LOG_ESCOLA);
        }

        if(    $oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada != ''
            && !DBString::isCNPJ( $oDadosEscola->registro00->cnpj_mantenedora_principal_escola_privada )
          ) {

          $sMensagem          = "O CNPJ da mantenedora principal não é válido.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }

        if ($oDadosEscola->registro00->cnpj_escola_privada == '') {

          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro("O CNPJ da escola deve ser informado quando escola for privada.", ExportacaoCenso2015::LOG_ESCOLA);
        }

        if(    trim( $oDadosEscola->registro00->cnpj_escola_privada ) != ''
            && !DBString::isCNPJ( $oDadosEscola->registro00->cnpj_escola_privada )
          ) {

          $sMensagem          = "O CNPJ da escola não é válido.";
          $lTodosDadosValidos = false;
          $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        }
      }

      if( $oDadosEscola->registro00->regulamentacao_autorizacao_conselho_orgao == '' ) {

        $sMensagem          = "Deve ser informada uma opção referente ao Credenciamento da escola.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    if ( empty($oDadosEscola->registro00->localizacao_zona_escola) ){

      $sMensagem = "Localização/Zona da escola deve ser informado.";
      $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      $lTodosDadosValidos  = false;
    }

    $lDadosInfraEstrutura = DadosCensoEscola2015::validarDadosInfraEstrutura($oExportacaoCenso);
    if (!$lDadosInfraEstrutura) {
      $lTodosDadosValidos = $lDadosInfraEstrutura;
    }

    return $lTodosDadosValidos;
  }


  /**
   * Valida dos dados da Autenticação... campos:
   * coluna 3 - CPF gestor escola
   * coluna 4 - Nome do Gestor Escolar
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @param  stdClass         $oDados
   * @return boolean
   */
  static protected function validaAutenticacao(IExportacaoCenso $oExportacaoCenso, $oDados ) {

    $lValidou = true;

    if ($oDados->numero_cpf_gestor_escolar == '') {

      $lValidou = false;
      $oExportacaoCenso->logErro("Número do CPF do gestor é obrigatório", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ( in_array( trim($oDados->numero_cpf_gestor_escolar), array("00000000191", "00000000000") ) ) {

      $lValidou  = false;
      $sMensagem           = "Número do CPF ({$oDados->numero_cpf_gestor_escolar}) do";
      $sMensagem          .= " gestor informado é inválido.";
      $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ($oDados->nome_gestor_escolar == '') {

      $lValidou = false;
      $oExportacaoCenso->logErro("Nome do gestor é obrigatório", ExportacaoCenso2015::LOG_ESCOLA);
    }

    $sExpressao          = '/[^a-zA-Z\s]+/';
    $lValidacaoExpressao = preg_match($sExpressao, $oDados->nome_gestor_escolar) ? true : false;

    if ($lValidacaoExpressao) {

      $lValidou = false;
      $oExportacaoCenso->logErro("Nome do gestor inválido. Não é possível ter caractéres acentuados.", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ($oDados->cargo_gestor_escolar == '') {

      $lValidou = false;
      $oExportacaoCenso->logErro("Cargo do gestor é obrigatório", ExportacaoCenso2015::LOG_ESCOLA);
    }

    if ( !DBString::isEmail($oDados->endereco_eletronico_gestor_escolar) ) {

      $lValidou = false;
      $oExportacaoCenso->logErro("E-mail do gestor inválido. {$oDados->endereco_eletronico_gestor_escolar}", ExportacaoCenso2015::LOG_ESCOLA);
    }
    return $lValidou;
  }


  /**
   * Valida as colunas 11 - Latitude e 12 - Longitude do layout
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @param  StdClass         $oDados
   * @return boolean
   */
  static protected function validaLatitudeLongitude(IExportacaoCenso $oExportacaoCenso, $oDados) {

    $lTodosDadosValidos = true;

    $sRegexCoordenadas = '/[^\-0-9.]+/i';
    if ( !empty($oDados->latitude) ) {

      if ( strlen($oDados->latitude) > 20 ) {

        $sMensagem  = "Latitude deve conter no máximo 20 caractéres.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( preg_match ('/[^\-0-9.]+/i',  $oDados->latitude) ) {

        $sMensagem  = "Latitude aceita deve conter somente os seguintes caractéres entre parêntesis(0123456789.-).";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( strpos($oDados->latitude, "-") > 0 ) {

        $sMensagem  = "O sinal de subtração(-) só pode vir na posição inicial.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( $oDados->latitude < -33.75208 || $oDados->latitude > 5.271841) {

        $sMensagem  = "Latitude informada deve ser maior ou igual a -33.75208 e menor ou igual a 5.271841 .";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }
    }

    if ( !empty($oDados->longitude) ) {

      if ( strlen($oDados->longitude) > 20 ) {

        $sMensagem  = "Longitude deve conter no máximo 20 caractéres.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( preg_match ('/[^\-0-9.]+/i',  $oDados->longitude) ) {

        $sMensagem  = "Latitude aceita deve conter somente os seguintes caractéres entre parêntesis(0123456789.-).";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( strpos($oDados->longitude, "-") > 0 ) {

        $sMensagem  = "O sinal de subtração(-) só pode vir na posição inicial.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }

      if ( $oDados->longitude < -73.99045 || $oDados->longitude > -32.39091) {

        $sMensagem  = "Latitude informada deve ser maior ou igual a -73.99045 e menor ou igual a -32.39091.";
        $lTodosDadosValidos = false;
        $oExportacaoCenso->logErro($sMensagem, ExportacaoCenso2015::LOG_ESCOLA);
      }
    }

    return $lTodosDadosValidos;
  }

  /**
   * Realizada a validacao dos dados da InfraEstrutura da escola
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  protected function validarDadosInfraEstrutura(IExportacaoCenso $oExportacaoCenso) {

    $aValidou = array();

    $aValidou[] = self::validarEquipamentos($oExportacaoCenso);
    $aValidou[] = self::validarDependencias($oExportacaoCenso);
    $aValidou[] = self::validarLocalFuncionamento($oExportacaoCenso);
    $aValidou[] = self::validarAbastecimentoAgua( $oExportacaoCenso );
    $aValidou[] = self::validarAbastecimentoEnergia( $oExportacaoCenso );
    $aValidou[] = self::validarDestinacaoLixo( $oExportacaoCenso );
    $aValidou[] = self::validarEsgotoSanitario( $oExportacaoCenso );
    $aValidou[] = self::validarInformacoesGeraisInfraestrutura( $oExportacaoCenso );
    $aValidou[] = self::validarAtendimentoModalidade( $oExportacaoCenso );
    $aValidou[] = self::validarLinguaEnsinoMinistrado( $oExportacaoCenso );
    $aValidou[] = self::validarRegistro10Colunas14a19( $oExportacaoCenso );
    $aValidou[] = self::validarRegistro10Colunas20( $oExportacaoCenso );
    $aValidou[] = self::validarRegistro10Colunas21a25( $oExportacaoCenso );
    $aValidou[] = self::validarRegistro10Colunas105a107( $oExportacaoCenso );

    $lValidou = array_reduce( $aValidou, 'validaVerdadeiro');

    return $lValidou;
  }

  /**
   * Valida as Dependencias da escola - Registro 10
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  static protected function validarLocalFuncionamento(IExportacaoCenso $oExportacaoCenso) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $aErros       = array();

    $lValidouLocalFuncionamento = true;

    $aLocalFuncionamento = array( "local_funcionamento_escola_predio_escolar",
                                  "local_funcionamento_escola_templo_igreja",
                                  "local_funcionamento_escola_salas_empresas",
                                  "local_funcionamento_escola_casa_professor",
                                  "local_funcionamento_escola_salas_outras_escolas",
                                  "local_funcionamento_escola_galpao_rancho_paiol_bar",
                                  "local_funcionamento_escola_un_internacao_socio",
                                  "local_funcionamento_escola_unidade_prisional",
                                  "local_funcionamento_escola_outros"
                                );

    $lMarcou = false;
    foreach ($aLocalFuncionamento as $sLocal) {

      if ( $oRegistro10->$sLocal == 1 ) {
        $lMarcou = true;
      }
    }

    if ( $oRegistro10->local_funcionamento_escola_predio_escolar == 1 && $oRegistro10->forma_ocupacao_predio == '') {
      $aErros[] = "Forma de ocupaçao do predio é obrigatório quando informado que o local de funcionamento é um Predio escolar.";
    }

    if (  $oRegistro10->local_funcionamento_escola_predio_escolar != 1          &&
          $oRegistro10->local_funcionamento_escola_galpao_rancho_paiol_bar != 1 &&
          !empty($oRegistro10->forma_ocupacao_predio) ) {
      $aErros[] = "Forma de ocupaçao deve ser nulo se o local de funcionamento não for Prédio escolar ou Galpão/Rancho/Paiol/Barracão.";
    }

    if ( !$lMarcou ) {
      $aErros[] = "Deve ser informado ao menos um local de funcionamento para a escola.";
    }

    if ($oRegistro10->local_funcionamento_escola_predio_escolar === 0 ) {
      $oDadosEscola->registro10->predio_compartilhado_outra_escola = '';
    }

    foreach( $aErros as $sMensagem ) {

      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      $lValidouLocalFuncionamento = false;
    }

    return $lValidouLocalFuncionamento;
  }

  /**
   * Valida as Dependencias da escola - Registro 10
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  static protected function validarDependencias (IExportacaoCenso $oExportacaoCenso) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $aErros       = array();

    $lValidouDependencias = true;
    $aDependenciasEscola  = array(
      isset( $oRegistro10->dependencias_existentes_escola_sala_diretoria )      ? $oRegistro10->dependencias_existentes_escola_sala_diretoria      : null,
      isset( $oRegistro10->dependencias_existentes_escola_sala_professores )    ? $oRegistro10->dependencias_existentes_escola_sala_professores    : null,
      isset( $oRegistro10->dependencias_existentes_escola_sala_secretaria )     ? $oRegistro10->dependencias_existentes_escola_sala_secretaria     : null,
      isset( $oRegistro10->dependencias_existentes_escola_laboratorio_informa ) ? $oRegistro10->dependencias_existentes_escola_laboratorio_informa : null,
      isset( $oRegistro10->dependencias_existentes_escola_laboratorio_ciencia ) ? $oRegistro10->dependencias_existentes_escola_laboratorio_ciencia : null,
      isset( $oRegistro10->dependencias_existentes_escola_sala_recursos_multi ) ? $oRegistro10->dependencias_existentes_escola_sala_recursos_multi : null,
      isset( $oRegistro10->dependencias_existentes_escola_quadra_esporte_cobe ) ? $oRegistro10->dependencias_existentes_escola_quadra_esporte_cobe : null,
      isset( $oRegistro10->dependencias_existentes_escola_quadra_esporte_desc ) ? $oRegistro10->dependencias_existentes_escola_quadra_esporte_desc : null,
      isset( $oRegistro10->dependencias_existentes_escola_cozinha )             ? $oRegistro10->dependencias_existentes_escola_cozinha             : null,
      isset( $oRegistro10->dependencias_existentes_escola_biblioteca )          ? $oRegistro10->dependencias_existentes_escola_biblioteca          : null,
      isset( $oRegistro10->dependencias_existentes_escola_sala_leitura )        ? $oRegistro10->dependencias_existentes_escola_sala_leitura        : null,
      isset( $oRegistro10->dependencias_existentes_escola_parque_infantil )     ? $oRegistro10->dependencias_existentes_escola_parque_infantil     : null,
      isset( $oRegistro10->dependencias_existentes_escola_bercario )            ? $oRegistro10->dependencias_existentes_escola_bercario            : null,
      isset( $oRegistro10->dependencias_existentes_escola_banheiro_fora_predi ) ? $oRegistro10->dependencias_existentes_escola_banheiro_fora_predi : null,
      isset( $oRegistro10->dependencias_existentes_escola_banheiro_dentro_pre ) ? $oRegistro10->dependencias_existentes_escola_banheiro_dentro_pre : null,
      isset( $oRegistro10->dependencias_existentes_escola_banheiro_educ_infan ) ? $oRegistro10->dependencias_existentes_escola_banheiro_educ_infan : null,
      isset( $oRegistro10->dependencias_existentes_escola_banheiro_alunos_def ) ? $oRegistro10->dependencias_existentes_escola_banheiro_alunos_def : null,
      isset( $oRegistro10->dependencias_existentes_escola_dep_vias_alunos_def ) ? $oRegistro10->dependencias_existentes_escola_dep_vias_alunos_def : null,
      isset( $oRegistro10->dependencias_existentes_escola_banheiro_chuveiro )   ? $oRegistro10->dependencias_existentes_escola_banheiro_chuveiro   : null,
      isset( $oRegistro10->dependencias_existentes_escola_refeitorio )          ? $oRegistro10->dependencias_existentes_escola_refeitorio          : null,
      isset( $oRegistro10->dependencias_existentes_escola_despensa )            ? $oRegistro10->dependencias_existentes_escola_despensa            : null,
      isset( $oRegistro10->dependencias_existentes_escola_almoxarifado )        ? $oRegistro10->dependencias_existentes_escola_almoxarifado        : null,
      isset( $oRegistro10->dependencias_existentes_escola_auditorio )           ? $oRegistro10->dependencias_existentes_escola_auditorio           : null,
      isset( $oRegistro10->dependencias_existentes_escola_patio_coberto )       ? $oRegistro10->dependencias_existentes_escola_patio_coberto       : null,
      isset( $oRegistro10->dependencias_existentes_escola_patio_descoberto )    ? $oRegistro10->dependencias_existentes_escola_patio_descoberto    : null,
      isset( $oRegistro10->dependencias_existentes_escola_alojamento_aluno )    ? $oRegistro10->dependencias_existentes_escola_alojamento_aluno    : null,
      isset( $oRegistro10->dependencias_existentes_escola_alojamento_professo ) ? $oRegistro10->dependencias_existentes_escola_alojamento_professo : null,
      isset( $oRegistro10->dependencias_existentes_escola_area_verde )          ? $oRegistro10->dependencias_existentes_escola_area_verde          : null,
      isset( $oRegistro10->dependencias_existentes_escola_lavanderia )          ? $oRegistro10->dependencias_existentes_escola_lavanderia          : null
    );

    if ( !in_array(1, $aDependenciasEscola) && $oRegistro10->dependencias_existentes_escola_nenhuma_relacionada == 0 ) {

        $sMensagem  = 'Dependências existentes na escola inválido. Não podem ser informadas todas as opções com valor';
        $sMensagem .= ' igual a 0 (Não).';
        $aErros[]   = $sMensagem;
    }

    if ( in_array(1, $aDependenciasEscola) && $oRegistro10->dependencias_existentes_escola_nenhuma_relacionada == 1 ) {

        $sMensagem  = "Informação das dependências da escola inválida. Ao selecionar 'Nenhuma das dependências";
        $sMensagem .= " relacionadas', nenhuma das outras dependências pode ser selecionada.";
        $aErros[]   = $sMensagem;
    }

    if ( $oRegistro10->local_funcionamento_escola_predio_escolar == 1 && $oRegistro10->numero_salas_aula_existentes_escola == '' ) {

      $sMensagem  = "Numero de salas de aula existentes na escola é obrigatório para escolas que informaram" ;
      $sMensagem .= " \"Prédio Escolar\" como local de funcionamento da escola.";
      $aErros[]   = $sMensagem ;
    }

    $lNumeroSalasExistentesValido = true;

    if( $oDadosEscola->registro10->numero_salas_aula_existentes_escola != ''
      && !DBNumber::isInteger( trim( $oDadosEscola->registro10->numero_salas_aula_existentes_escola ) ) ) {

      $sMensagem                    = "Valor do 'N° de Salas de Aula Existentes na Escola' inválido. Deve ser";
      $sMensagem                   .= " informado somente números.";
      $aErros[]                     = $sMensagem;
      $lNumeroSalasExistentesValido = false;
    }

    if (    $lNumeroSalasExistentesValido
         && trim( $oDadosEscola->registro10->numero_salas_aula_existentes_escola == '0' )
       ) {
      $aErros[] = "O valor do campo 'N° de Salas de Aula Existentes na Escola' deve ser maior que 0.";
    }

    if ( strlen($oDadosEscola->registro10->numero_salas_aula_existentes_escola) > 4 ) {
      $aErros[] = 'O campo "Número de salas de aula existentes na escola" está maior que o especificado.';
    }

    if (  $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula === '' ) {
      $aErros[] = 'O campo "Número de salas utilizadas como sala de aula - Dentro e fora do prédio" deve ser preenchido.';
    }

    $lNumeroSalasUsadasValido = true;
    if( $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula !== ''
      && !DBNumber::isInteger( trim( $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula ) ) ) {

      $lNumeroSalasUsadasValido = false;
      $sMensagem                = "Valor do 'N° de Salas Utilizadas como Sala de Aula' inválido. Deve ser";
      $sMensagem               .= " informado somente números.";
      $aErros[]                 = $sMensagem;
    }

    if ( $lNumeroSalasUsadasValido && trim( $oDadosEscola->registro10->numero_salas_usadas_como_salas_aula == '0' ) ) {
      $aErros[] = "O valor do campo 'N° de Salas Utilizadas como Sala de Aula' deve ser maior de 0.";
    }

    if ( strlen($oDadosEscola->registro10->numero_salas_usadas_como_salas_aula) > 4 ) {
      $aErros[] = 'O campo "Número de salas utilizadas como sala de aula - Dentro e fora do prédio" está maior que o especificado.';
    }

    foreach ($aErros as $sMsg) {

      $oExportacaoCenso->logErro($sMsg, ExportacaoCenso2015::LOG_ESCOLA);
      $lValidouDependencias = false;
    }

    return $lValidouDependencias;
  }

  /**
   * Valida os Equipamentos da escola - Registro 10
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  static protected function validarEquipamentos(IExportacaoCenso $oExportacaoCenso) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;

    $aErros = array();

    $lValidouEquipamento    = true;
    $aEquipamentosValidacao = array(
      "Aparelho de Televisão"           => "equipamentos_existentes_escola_televisao",
      "Videocassete"                    => "equipamentos_existentes_escola_videocassete",
      "Aparelho de DVD"                 => "equipamentos_existentes_escola_dvd",
      "Antena parabólica"               => "equipamentos_existentes_escola_antena_parabolica",
      "Copiadora"                       => "equipamentos_existentes_escola_copiadora",
      "Retroprojetor"                   => "equipamentos_existentes_escola_retroprojetor",
      "Impressora"                      => "equipamentos_existentes_escola_impressora",
      "Aparelho de som"                 => "equipamentos_existentes_escola_aparelho_som",
      "Projetor Multimídia (Data show)" => "equipamentos_existentes_escola_projetor_datashow",
      "Fax"                             => "equipamentos_existentes_escola_fax",
      "Máquina Fotográfica/Filmadora"   => "equipamentos_existentes_escola_maquina_fotografica",
      "Computadores"                    => "equipamentos_existentes_escola_computador",
      "Impressora Multifuncional"       => "equipamentos_impressora_multifuncional"
    );

    foreach ($aEquipamentosValidacao as $sNome => $sEquipamentoValidacao) {

      if (!DadosCensoEscola::validarEquipamentosExistentes($oExportacaoCenso, $oRegistro10->$sEquipamentoValidacao, $sNome)) {
        $lValidouEquipamento = false;
      }
    }

    if ( $oRegistro10->quantidade_computadores_uso_administrativo === 0) {
      $aErros[] = "Quantidade de computadores de uso administrativo não pode ser zero (0).";
    }

    if ( strlen($oRegistro10->quantidade_computadores_uso_administrativo) > 4 ) {
      $aErros[] = 'O campo "Quantidade de computadores de uso administrativo" está maior que o especificado.';
    }

    if ( $oRegistro10->quantidade_computadores_uso_administrativo > $oRegistro10->equipamentos_existentes_escola_computador) {
      $aErros[] = "Quantidade de computadores de uso administrativo não pode maior que os computadores existentes na escola.";
    }

    if ( $oRegistro10->quantidade_computadores_uso_alunos === 0) {
      $aErros[] = "Quantidade de computadores de uso dos alunos não pode ser zero (0).";
    }

    if ( $oRegistro10->quantidade_computadores_uso_alunos > $oRegistro10->equipamentos_existentes_escola_computador) {
      $aErros[] = "Quantidade de computadores de uso dos alunos não pode maior que os computadores existentes na escola.";
    }

    if ( strlen($oRegistro10->quantidade_computadores_uso_alunos) > 4 ) {
      $aErros[] = 'O campo "Quantidade de computadores de uso dos alunos" está maior que o especificado.';
    }

    if ( $oRegistro10->equipamentos_existentes_escola_computador !== 0 &&
         empty($oRegistro10->quantidade_computadores_uso_alunos) && empty($oRegistro10->quantidade_computadores_uso_administrativo) ) {
      $aErros[] = 'Pelo menos um dos campos "Quantidade de computadores de uso administrativo" ou "Quantidade de computadores de uso dos alunos" deve ser preenchido quando o campo "Quantidade de equipamentos existentes na escola - Computadores" for preenchido.';
    }

    if ( !empty($oRegistro10->equipamentos_existentes_escola_computador) && $oRegistro10->acesso_internet === '') {
     $aErros[] = "Obrigatório para escolas que informaram possuir computador. ";
    }

    if ( $oRegistro10->acesso_internet == 1 && $oRegistro10->banda_larga === '') {
     $aErros[] = "Obrigatório para escolas que informaram possuir acesso à internet.";
    }

    foreach ($aErros as $sMsg) {

      $oExportacaoCenso->logErro($sMsg, ExportacaoCenso2015::LOG_ESCOLA);
      $lValidouEquipamento = false;
    }

    return $lValidouEquipamento;
  }

  /**
   * Valida as informações referentes ao abastecimento de água
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarAbastecimentoAgua( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola          = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10           = $oDadosEscola->registro10;
    $lValidouAbastecimento = true;

    if(    empty( $oRegistro10->abastecimento_agua_rede_publica )
        && empty( $oRegistro10->abastecimento_agua_poco_artesiano )
        && empty( $oRegistro10->abastecimento_agua_cacimba_cisterna_poco )
        && empty( $oRegistro10->abastecimento_agua_fonte_rio_igarape_riacho_correg )
        && empty( $oRegistro10->abastecimento_agua_inexistente )
      ) {

      $sMensagem = "Obrigatório selecionar ao menos um tipo de abastecimento de água.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      $lValidouAbastecimento = false;
    }

    if( $oRegistro10->abastecimento_agua_inexistente == 1 ) {

      if(    $oRegistro10->abastecimento_agua_rede_publica                    == 1
          || $oRegistro10->abastecimento_agua_poco_artesiano                  == 1
          || $oRegistro10->abastecimento_agua_cacimba_cisterna_poco           == 1
          || $oRegistro10->abastecimento_agua_fonte_rio_igarape_riacho_correg == 1
        ) {

        $sMensagem  = "Ao selecionar Abastecimento de Água como \"Inexistente\", nenhuma das outras opções deve ser";
        $sMensagem .= " selecionada.";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        $lValidouAbastecimento = false;
      }
    }

    return $lValidouAbastecimento;
  }

  /**
   * Valida as informações referentes ao abastecimento de energia
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarAbastecimentoEnergia( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola    = $oExportacaoCenso->getDadosProcessadosEscola();
    $lValidouEnergia = true;

    if(    empty( $oDadosEscola->registro10->abastecimento_energia_eletrica_rede_publica )
        && empty( $oDadosEscola->registro10->abastecimento_energia_eletrica_gerador )
        && empty( $oDadosEscola->registro10->abastecimento_energia_eletrica_outros_alternativa )
        && empty( $oDadosEscola->registro10->abastecimento_energia_eletrica_inexistente )
      ) {

      $sMensagem = "Obrigatório selecionar ao menos um tipo de abastecimento de energia.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      $lValidouEnergia = false;
    }

    if( $oDadosEscola->registro10->abastecimento_energia_eletrica_inexistente == 1 ) {

      if(    $oDadosEscola->registro10->abastecimento_energia_eletrica_rede_publica       == 1
          || $oDadosEscola->registro10->abastecimento_energia_eletrica_gerador            == 1
          || $oDadosEscola->registro10->abastecimento_energia_eletrica_outros_alternativa == 1
        ) {

        $sMensagem  = "Ao selecionar Abastecimento de Energia como \"Inexistente\", nenhuma das outras opções deve ser";
        $sMensagem .= " selecionada.";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        $lValidouEnergia = false;
      }
    }

    return $lValidouEnergia;
  }

  /**
   * Valida as informações referentes esgoto sanitário
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarEsgotoSanitario( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola   = $oExportacaoCenso->getDadosProcessadosEscola();
    $lValidouEsgoto = true;

    if(    empty( $oDadosEscola->registro10->esgoto_sanitario_rede_publica )
        && empty( $oDadosEscola->registro10->esgoto_sanitario_fossa )
        && empty( $oDadosEscola->registro10->esgoto_sanitario_inexistente )
      ) {

      $sMensagem = "Obrigatório selecionar ao menos uma opção referente a Esgoto Sanitário.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      $lValidouEsgoto = false;
    }

    if( $oDadosEscola->registro10->esgoto_sanitario_inexistente == 1 ) {

      if(    $oDadosEscola->registro10->esgoto_sanitario_rede_publica == 1
          || $oDadosEscola->registro10->esgoto_sanitario_fossa        == 1
        ) {

        $sMensagem  = "Ao selecionar Esgoto Sanitário como \"Inexistente\", nenhuma das outras opções deve ser";
        $sMensagem .= " selecionada.";
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        $lValidouEsgoto = false;
      }
    }

    return $lValidouEsgoto;
  }

  /**
   * Valida as informações referentes a destinação do lixo
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarDestinacaoLixo( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $lValidouLixo = true;

    if (    $oDadosEscola->registro10->destinacao_lixo_coleta_periodica == 0
         && $oDadosEscola->registro10->destinacao_lixo_queima           == 0
         && $oDadosEscola->registro10->destinacao_lixo_joga_outra_area  == 0
         && $oDadosEscola->registro10->destinacao_lixo_recicla          == 0
         && $oDadosEscola->registro10->destinacao_lixo_enterra          == 0
         && $oDadosEscola->registro10->destinacao_lixo_outros           == 0
       ) {

        $lValidouLixo = false;
        $oExportacaoCenso->logErro( "Destinação do lixo da escola não informado.", ExportacaoCenso2015::LOG_ESCOLA );
      }

    return $lValidouLixo;
  }

  /**
   * Valida informações gerais referentes a infraestrutura da escola
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarInformacoesGeraisInfraestrutura( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $aDadosTurma  = $oExportacaoCenso->getDadosProcessadosTurma();
    $lValidouInformacoes = true;

    // regra removida em 2017
    // if(    $oDadosEscola->registro00->dependencia_administrativa != 4
    //     && $oDadosEscola->registro10->alimentacao_escolar_aluno  == 0
    //   ) {

    //   $lValidouInformacoes = false;
    //   $sMensagem           = "Escola informada como pública( Federal / Estadual / Municipal ). A mesma deve estar com";
    //   $sMensagem          .= " a opção \"Alimentação Escolar para os Alunos\" selecionada como Oferece.";
    //   $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    // }


    /* Coluna 89 do Registro 10 - INICIO */
    if ( $oDadosEscola->registro10->alimentacao_escolar_aluno == 1 ) {

      /**
       * regra 4 deve haver pelo menos um registro 20 com o campo 6 (Tipo de mediação didático-pedagógica) igual a
       * 1 (Presencial) ou 2 (Semipresencial).
       **/
      $lEscolaPossuiTurmaPresencialOuSemi = true;
      foreach ($aDadosTurma as $oRegistro20) {

        if ( !in_array($oRegistro20->mediacao_didatico_pedagogica, array(1,2)) ) {
          $lEscolaPossuiTurmaPresencialOuSemi = false;
        }
      }

      if ( !$lEscolaPossuiTurmaPresencialOuSemi ) {

        $sMensagem  = 'O campo "Alimentação escolar para os alunos" não pode ser igual a 1 (Oferece) quando o';
        $sMensagem .= ' campo "Tipo de mediação didático-pedagógica" de todas as turmas da escola for';
        $sMensagem .= ' igual a 3 (Educação a Distância).';
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
        $lValidouInformacoes = false;
      }
    }
    /* Coluna 89 do Registro 10 - FIM */


    if(    $oDadosEscola->registro10->localizacao_diferenciada_escola == 1
        && $oDadosEscola->registro00->localizacao_zona_escola         != 2
      ) {

      $lValidouInformacoes = false;
      $sMensagem           = "Localização diferenciada da escola informada como Área de assentamento. Localização/Zona";
      $sMensagem          .= " deve ser do tipo Rural.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    if(    $oDadosEscola->registro10->materiais_didaticos_especificos_nao_utiliza == 1
        && (    $oDadosEscola->registro10->materiais_didaticos_especificos_quilombola == 1
             || $oDadosEscola->registro10->materiais_didaticos_especificos_indigena   == 1 )
      ) {

      $lValidouInformacoes = false;
      $sMensagem           = "Materais Didáticos Específicos: Ao selecionar a opção \"Não utiliza\", as demais não";
      $sMensagem          .= " não estar selecionadas.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    if(    $oDadosEscola->registro10->materiais_didaticos_especificos_nao_utiliza == 0
        && $oDadosEscola->registro10->materiais_didaticos_especificos_quilombola  == 0
        && $oDadosEscola->registro10->materiais_didaticos_especificos_indigena    == 0
      ) {


      $lValidouInformacoes = false;
      $sMensagem           = "Materais Didáticos Específicos: Ao menos uma das opções devem ser selecionadas.";
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    return $lValidouInformacoes;
  }

  /**
   * Valida informações referentes ao atendimento AC e AEE
   * @param IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarAtendimentoModalidade( IExportacaoCenso $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oDadosTurma  = $oExportacaoCenso->getDadosProcessadosTurma();
    $oDadosAluno  = $oExportacaoCenso->getDadosProcessadosAluno();
    $lValidou     = true;

    $lTurmaEscolarizacao = true;
    if (    $oDadosEscola->registro10->atendimento_educacional_especializado == 2
         || $oDadosEscola->registro10->atividade_complementar == 2 ) {

      foreach ($oDadosTurma as $oRegistro20) {

        if ( $oRegistro20->modalidade_turma == 1 || $oRegistro20->modalidade_turma == 3) {
          $lTurmaEscolarizacao = false;
        }
      }
    }

    if ( !$lTurmaEscolarizacao ) {

      $sAtividade = "Atividade Complementar";

      if ( $oDadosEscola->registro10->atendimento_educacional_especializado == 2 ) {
        $sAtividade = "AEE";
      }

      $sMensagem  = "Campo 'Escola oferece {$sAtividade}' deve ser informamdo como 'Não Exclusivamente' pois escola";
      $sMensagem .= " possui turmas de escolarização.";
      $lValidou   = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    // coluna 90 regra 3
    if ( $oDadosEscola->registro10->atendimento_educacional_especializado != 0 &&
         $oDadosEscola->registro10->atividade_complementar == 2) {

      $sMensagem  = 'O campo "Atendimento educacional especializado - AEE" não pode ser preenchido diferente de não ';
      $sMensagem .= 'oferece (0), quando o campo "Atividade complementar" for preenchido com exclusivamente (2).';
      $lValidou   = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    // coluna 91 regra 3
    if ( $oDadosEscola->registro10->atividade_complementar != 0 &&
         $oDadosEscola->registro10->atendimento_educacional_especializado == 2) {

      $sMensagem  = 'O campo "Atividade complementar" não pode ser preenchido diferente de não oferece (0), quando o ';
      $sMensagem .= 'campo "Atendimento educacional especializado - AEE" for preenchido com exclusivamente (2).';
      $lValidou   = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    if( $oDadosEscola->registro10->atendimento_educacional_especializado === '' &&
        $oDadosEscola->registro10->atividade_complementar                === ''
      ) {

      $sMensagem  = "É necessário marcar uma das opções referentes a Atendimento Educacional Especializado";
      $sMensagem .= " e Atividade Complementar.";
      $lValidou   = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }

    // coluna 90 regra 5
    if ( in_array($oDadosEscola->registro10->atendimento_educacional_especializado, array(1, 2)) ) {

      $lSemTurmaAEE = false;
      foreach ($oDadosTurma as $oRegistro20) {

        if ( $oRegistro20->tipo_atendimento == 5 ) {
          $lSemTurmaAEE = true;
        }
      }

      if ( !$lSemTurmaAEE ) {

        $sMensagem  = 'O campo "Atendimento educacional especializado - AEE" foi preenchido com 1 (Não exclusivamente) ';
        $sMensagem .= 'ou 2 (Exclusivamente), mas não há nenhuma turma com esse tipo de atendimento.';
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }
    // coluna 91 regra 5
    if ( in_array($oDadosEscola->registro10->atividade_complementar, array(1, 2)) ) {

      $lTemTurmaComplementar = false;
      foreach ($oDadosTurma as $oRegistro20) {

        if (    $oRegistro20->tipo_atendimento == 4
             || $oRegistro20->turma_participante_mais_educacao_ensino_medio_inov == 1
           ) {
          $lTemTurmaComplementar = true;
        }
      }

      if ( !$lTemTurmaComplementar ) {

        $sMensagem  = "Escola oferece turma de Atividade Complementar mas não possui turma com este tipo de atendimento.";
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    /* Coluna 96 do Registro 10 - INICIO */
    $aEtapasCiclos = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 41, 56);
    $lPossuiEtapasCompativelComCiclos = false;
    foreach ($oDadosTurma as $oRegistro20) {

      if ( in_array($oRegistro20->etapa_ensino_turma, $aEtapasCiclos) ) {
        $lPossuiEtapasCompativelComCiclos = true;
      }
    }

    if ( !$lPossuiEtapasCompativelComCiclos ) {
      $oDadosEscola->registro10->ensino_fundamental_organizado_ciclos = '';
    }


    if ( $lPossuiEtapasCompativelComCiclos && $oDadosEscola->registro10->ensino_fundamental_organizado_ciclos === '' ) {

      $sMensagem  = 'O campo "Ensino fundamental organizado em ciclos" deve ser preenchido quando a escola possuir ';
      $sMensagem .= 'turma(s) do ensino fundamental.';
      $lValidou   = false;
      $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
    }
    /* Coluna 96 do Registro 10 - FIM */

    return $lValidou;
  }

  /**
   * Validações refenrentes as linhas: 101, 102, 103 e 104
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return bool
   */
  static protected function validarLinguaEnsinoMinistrado( $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $lValidou     = true;
    if ( $oDadosEscola->registro10->educacao_indigena == 1 ) {

      if ( $oDadosEscola->registro10->lingua_ensino_ministrado_lingua_indigena   === 0 &&
           $oDadosEscola->registro10->lingua_ensino_ministrada_lingua_portuguesa === 0 ) {

        $sMensagem  = 'Ao informar que a escola oferece educação indígena, é obrigatório informar em qual língua o';
        $sMensagem .= ' ensino é ministrado.';
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
      if ( !empty($oDadosEscola->registro10->codigo_lingua_indigena) &&
           $oDadosEscola->registro10->lingua_ensino_ministrado_lingua_indigena === 0 ) {

        $sMensagem  = 'Ao informar o código da língua indigena, escola deve ter informado que oferece ensino ';
        $sMensagem .= 'ministrado em língua indigena.';
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ( $oDadosEscola->registro10->lingua_ensino_ministrado_lingua_indigena == 1 &&
           empty($oDadosEscola->registro10->codigo_lingua_indigena) ) {

        $sMensagem  = 'Ao informar que a escola oferece ensino ministrado em língua indigena, ';
        $sMensagem .= 'é obrigatório informar qual a língua indígena.';
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMensagem, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    return $lValidou;
  }

  /**
   * [atualizarDados description]
   * @param  DBLayoutLinha $oLinha
   * @throws DBException
   * @return boolean
   */
  public function atualizarDados(DBLayoutLinha $oLinha)  {

    $oDaoEscola                        = new cl_escola();
    $oDaoEscola->ed18_latitude         = $oLinha->latitude;
    $oDaoEscola->ed18_longitude        = $oLinha->longitude;
    $oDaoEscola->ed18_c_cep            = $oLinha->cep;
    $oDaoEscola->ed18_i_censouf        = $oLinha->uf;
    $oDaoEscola->ed18_i_censomunic     = $oLinha->municipio;
    $oDaoEscola->ed18_i_censodistrito  = $this->getDistrito($oLinha->municipio, $oLinha->distrito);
    $oDaoEscola->ed18_i_censoorgreg    = $this->getCensoOrgaoRegional($oLinha->uf, $oLinha->codigo_orgao_regional_ensino);
    $oDaoEscola->ed18_c_mantenedora    = $oLinha->dependencia_administrativa;
    $oDaoEscola->ed18_c_local          = $oLinha->localizacao_zona_escola;
    $oDaoEscola->ed18_i_credenciamento = $oLinha->regulamentacao_autorizacao_conselho_orgao;
    $oDaoEscola->ed18_i_codigo         = $this->iCodigoEscola;
    $oDaoEscola->alterar($this->iCodigoEscola);

    if ( $oDaoEscola->erro_status == 0 ) {
      throw new DBException($oDaoEscola->erro_msg);
    }
    return true;
  }

  /**
   * Validações do registro 10 colunas  14 a 19
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  protected static function validarRegistro10Colunas14a19( $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $lValidou     = true;

    $aCompartilha   = array();
    $lRepitiuEscola = false;
    for ($i = 1; $i <= 6; $i++) {

      $sKey    = "Escola compartilhada {$i}";
      $sPropry = "codigo_escola_compartilha_{$i}";
      if ( !empty($oRegistro10->$sPropry) && in_array($oRegistro10->$sPropry, $aCompartilha) ) {
        $lRepitiuEscola = true;
      }
      $aCompartilha[$sKey] = $oRegistro10->$sPropry;
    }

    // 14 a 19 regra - Não pode haver dois dos campos de 14 a 19 igualmente preenchidos.
    if ( $lRepitiuEscola ) {

      $sMsgErro  = "Código da escola com a qual compartilha inválido. Não pode haver dois códigos da escola com a ";
      $sMsgErro .= "qual compartilha iguais.";
      $lValidou  = false;
      $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
    }

    foreach ($aCompartilha as $sCompartilha => $sValue) {

      if ( !empty($sValue) && $oRegistro10->predio_compartilhado_outra_escola != 1 ) {

        $sMsgErro  = "O campo \"{$sCompartilha}\" não pode ser preenchido quando o campo \"Prédio compartilhado com ";
        $sMsgErro .= "outra escola\" for diferente de 1 (Sim).";
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ( !empty($sValue) && strlen($sValue) != 8 ) {

        $sMsgErro  = "O campo \"{$sCompartilha}\" deve ter 8 caractéres.";
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ( preg_match("/[^1-9]/", $sValue) ) {

        $sMsgErro  = "O campo \"{$sCompartilha}\", quando preenchido, apenas números podem ser informados.";
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

      if ($oDadosEscola->registro00->codigo_escola_inep == $sValue ) {

        $sMsgErro  = "O campo \"{$sCompartilha}\", não pode ser igual ao da escola atual.";
        $lValidou   = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    return $lValidou;
      }

  /**
   * Validações da Água consumida pelos alunos
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  protected static function validarRegistro10Colunas20( $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $lValidou     = true;

    if ( empty($oRegistro10->agua_consumida_alunos) ) {

      $sMsgErro = 'O campo "Água consumida pelos alunos" é uma informação obrigatória.';
      $lValidou = false;
      $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
    }

    if ( isset($oRegistro10->agua_consumida_alunos) && !in_array($oRegistro10->agua_consumida_alunos, array(1, 2)) ) {

      $sMsgErro = 'O campo "Água consumida pelos alunos" foi preenchido com valor inválido.';
      $lValidou = false;
      $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
    }

    return $lValidou;
  }

  /**
   * Validações do Abastecimento de água
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  protected static function validarRegistro10Colunas21a25 ( $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $lValidou     = true;

    $aAbastecimentoAgua = array(
      "Rede pública"                            => $oRegistro10->abastecimento_agua_rede_publica,
      "Poço artesiano"                          => $oRegistro10->abastecimento_agua_poco_artesiano,
      "Cacimba / cisterna / poço"               => $oRegistro10->abastecimento_agua_cacimba_cisterna_poco,
      "Fonte / rio / igarapé/ riacho/ córrego." => $oRegistro10->abastecimento_agua_fonte_rio_igarape_riacho_correg,
      "Inexistente"                             => $oRegistro10->abastecimento_agua_inexistente,
    );

    // colunas 21 a 25 regra Pelo menos um dos campos de 21 a 25 deve ser preenchido com 1 (Sim)
    if ( !in_array(1, $aAbastecimentoAgua) ) {

      $sMsgErro = 'Abastecimento de água inválido. Não podem ser informadas todas as opções com valor igual a 0 (Não).';
      $lValidou = false;
      $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
    }

    foreach ($aAbastecimentoAgua as $sAbastecimento => $iValue) {

      // colunas 21-25 regra 2
      if ( !in_array($iValue, array(0, 1)) ) {

        $sMsgErro = "O campo \"Abastecimento de água - {$sAbastecimento}\" foi preenchido com valor inválido.";
        $lValidou = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

      // colunas 21-24 regra 3
      if ( $sAbastecimento != "Inexistente" && $iValue !== 0 && $oRegistro10->abastecimento_agua_inexistente == 1) {

        $sMsgErro  = "O campo \"Abastecimento de água - {$sAbastecimento}\" não pode ser preenchido com 1 (Sim) ";
        $sMsgErro .= "quando o campo \"Abastecimento de água - Inexistente\" for preenchido com 1 (Sim).";
        $lValidou  = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

    }

    return $lValidou;
  }

  /**
   * Validações dos campos:
   * Escola cede espaço para turmas do Brasil Alfabetizado
   * Escola abre aos finais de semana para a comunidade
   * Escola com proposta pedagógica de formação por alternância
   *
   * @param  IExportacaoCenso $oExportacaoCenso
   * @return boolean
   */
  protected static function validarRegistro10Colunas105a107( $oExportacaoCenso ) {

    $oDadosEscola = $oExportacaoCenso->getDadosProcessadosEscola();
    $oRegistro10  = $oDadosEscola->registro10;
    $lValidou     = true;

    $iCedeEspaco          = isset($oRegistro10->escola_cede_espaco_turma_brasil_alfabetizado) ? $oRegistro10->escola_cede_espaco_turma_brasil_alfabetizado : null;
    $iAbreFinaisSemana    = isset($oRegistro10->escola_abre_finais_semanas_comunidade) ? $oRegistro10->escola_abre_finais_semanas_comunidade : null;
    $iFormacaoAlternancia = isset($oRegistro10->escola_formacao_alternancia) ? $oRegistro10->escola_formacao_alternancia : null;

    $aCampos = array(
      "Escola cede espaço para turmas do Brasil Alfabetizado"      => $iCedeEspaco,
      "Escola abre aos finais de semana para a comunidade"         => $iAbreFinaisSemana,
      "Escola com proposta pedagógica de formação por alternância" => $iFormacaoAlternancia,
    );

    foreach ($aCampos as $sColuna => $mValor) {

      // regra 1
      if ( $mValor === '' ) {
        $sMsgErro = "O campo \"{$sColuna}\" é uma informação obrigatória.";
        $lValidou = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }

      // regra 2
      if ( $mValor !== '' && !in_array($mValor, array(0,1)) ) {

        $sMsgErro = "O campo \"{$sColuna}\" foi preenchido com valor inválido.";
        $lValidou = false;
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
      }
    }

    // coluna 107 regra 3
    if ( isset($oRegistro10->escola_formacao_alternancia) && $oRegistro10->escola_formacao_alternancia != 0 ) {

      // etapas de educação infantil e de anos iniciais do ensino fundamental
      $aEtapas       = array(1, 2, 3, 4, 5, 6, 7, 14, 15, 16, 17, 18);
      $oDadosTurma   = $oExportacaoCenso->getDadosProcessadosTurma();
      $lPossuiEtapas = false;
      foreach ($oDadosTurma as $oRegistro20) {

        if ( in_array($oRegistro20->etapa_ensino_turma, $aEtapas) ) {
          $lPossuiEtapas = true;
        }
      }

      if ( in_array($oRegistro10->atendimento_educacional_especializado, array(1,2)) ||
           in_array($oRegistro10->atividade_complementar, array(1,2) ) ||
           $lPossuiEtapas ) {

        $sMsgErro  = 'Escolas que possuem apenas turmas de atividade complementar, de atendimento educacional ';
        $sMsgErro .= 'especializando, de educação infantil e de anos iniciais do ensino fundamental devem informar o ';
        $sMsgErro .= 'campo "Escola com proposta pedagógica de formação por alternância" com 0 (Não).';
        $oExportacaoCenso->logErro( $sMsgErro, ExportacaoCenso2015::LOG_ESCOLA );
        $lValidou = false;
      }
    }

    return $lValidou;
  }

}
