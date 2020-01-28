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

define( "MSG_DADOS_CENSO_DOCENTE", "educacao.escola.DadosCensoDocente." );

class DadosCensoDocente extends DadosCenso {

  protected $iCodigoDocente = null;

  protected $iCodigoInep = null;

  protected $oDadosGerais = null;

  protected $iCodigoEscola = null;

  protected $iAnoCenso = null;

  protected $oDataBaseCenso = null;

  protected $iCodigoCgm = null;

  protected $aEtapasCensoTurma = array(1, 2, 3, 65, 66);

  protected $aTurmasCenso = array();

  /**
   * Construtor da classe. Recebe como parâmetro, o código do docente
   * @param integer $iCodigoDocente
   */
  public function __construct( $iCodigoDocente ) {
    $this->iCodigoDocente = $iCodigoDocente;
  }

  /**
   * Retorna o codigo do docente
   * @return integer
   */
  public function getCodigoDocente() {
    return $this->iCodigoDocente;
  }

  /**
   * Retorna o codigo Cgm do proferssor;
   * @return integer
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * Retorna o codigo inep do Docente
   * @return integer
   */
  public function getCodigoInep() {
    return $this->iCodigoInep;
  }

  /**
   * Retorna o codigo da escola
   * @return $iCodigoEscola;
   */
  public function getCodigoEscola() {
    return $this->iCodigoEscola;
  }

  /**
   * Define o codigo da escola
   * @param integer $iCodigoEscola Código da escola.
   */
  public function setCodigoEscola($iCodigoEscola) {
    $this->iCodigoEscola = $iCodigoEscola;
  }

  /**
   * Retorna o Ano do censo
   * @return integer
   */
  public function getAnoCenso() {
    return $this->iAnoCenso;
  }

  /**
   * Define o ano do censo
   * @param integer
   */
  public function setAnoCenso( $iAnoCenso ) {
    $this->iAnoCenso = $iAnoCenso;
  }

  /**
   * Define a data base do censo
   * @param DBDate $oDataCenso
   */
  public function setDataCenso( DBDate $oDataCenso ) {
    $this->oDataBaseCenso = $oDataCenso;
  }

  public function turmasCenso( $aTurmasCenso ) {
    $this->aTurmasCenso = $aTurmasCenso;
  }

  /**
   * Retorna os dados básicos do Recurso humano
   * REGISTRO 30
   * @throws Exception
   * @return stdClass
   */
  protected function getDados() {

    $oDaoRecursoHumano     = new cl_rechumano();
    $sCamposRecursoHumano  = "ed20_i_codigoinep as identificacao_unica_docente_inep, ";
    $sCamposRecursoHumano .= "ed20_i_codigo as codigo_docente_entidade_escola ,";
    $sCamposRecursoHumano .= "case when cgmrh.z01_nome is not null then cgmrh.z01_nome else cgmcgm.z01_nome end as nome_completo, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_numcgm is not null then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as numcgm, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_email is not null then upper(cgmrh.z01_email) else upper(cgmcgm.z01_email) end as email, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_nasc is not null then cgmrh.z01_nasc else cgmcgm.z01_nasc end as data_nascimento, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_sexo is not null then cgmrh.z01_sexo else cgmcgm.z01_sexo end as sexo, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_mae is not null then cgmrh.z01_mae else cgmcgm.z01_mae end as nome_completo_mae, ";
    $sCamposRecursoHumano .= "ed20_i_nacionalidade as nacionalidade_docente,";
    $sCamposRecursoHumano .= "ed228_i_paisonu as pais_origem,";
    $sCamposRecursoHumano .= "ed20_i_censoufnat as uf_nascimento,";
    $sCamposRecursoHumano .= "ed20_i_censomunicnat as municipio_nascimento,";
    $sCamposRecursoHumano .= "ed20_i_raca as cor_raca,";
    $sCamposRecursoHumano .= "ed20_c_nis as numero_identificacao_social_inss, ";
    $sCamposRecursoHumano .= "0 as docente_deficiencia, ";
    $sCamposRecursoHumano .= "case when regimerh.rh30_naturezaregime is not null then  regimerh.rh30_naturezaregime ";
    $sCamposRecursoHumano .= "     else regimecgm.rh30_naturezaregime end as regime_trabalho,";
    $sCamposRecursoHumano .= 'ed20_i_escolaridade as escolaridade';

    $sWhereRecursoHumano  = "     ed20_i_codigo = {$this->iCodigoDocente} ";
    $sWhereRecursoHumano .= " and (ed01_funcaoatividade in (2,3,4) or (ed01_funcaoatividade = 1 and ed01_c_regencia = 'S'))";

    $sSqlRecursoHumano = $oDaoRecursoHumano->sql_query_censo( null, $sCamposRecursoHumano, null, $sWhereRecursoHumano );
    $rsRecursoHumano   = db_query( $sSqlRecursoHumano );

    if( !$rsRecursoHumano ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_dados_docente', $oMensagem ) );
    }

    if( pg_num_rows( $rsRecursoHumano ) == 0 ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'recurso_humano_nao_encontrado', $oMensagem ) );
    }

    $oDadosRecursoHumano       = db_utils::fieldsMemory( $rsRecursoHumano, 0 );
    $this->iCodigoCgm          = $oDadosRecursoHumano->numcgm;
    $oDadosRecursoHumano->sexo = $oDadosRecursoHumano->sexo == 'F' ? 2 : 1;
    $this->iCodigoInep         = $oDadosRecursoHumano->identificacao_unica_docente_inep;
    $aNecessidadesEspeciais    = $this->getNecessidadesEspeciais();
    $iDocenteNecessidade       = '';
    $iRegimeTrabalho           = 1;

    if( count( $aNecessidadesEspeciais ) > 0 ) {

      $oDadosRecursoHumano->docente_deficiencia = 1;
      $iDocenteNecessidade                      = '0';
    }

    $oDadosRecursoHumano->data_nascimento                 = db_formatar($oDadosRecursoHumano->data_nascimento, "d");
    $oDadosRecursoHumano->nome_completo_mae               = $this->removeCaracteres($oDadosRecursoHumano->nome_completo_mae, 1);
    $oDadosRecursoHumano->tipos_deficiencia_cegueira      = isset($aNecessidadesEspeciais[101]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_baixa_visao   = isset($aNecessidadesEspeciais[102]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdez        = isset($aNecessidadesEspeciais[103]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_auditiva      = isset($aNecessidadesEspeciais[104]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdocegueira = isset($aNecessidadesEspeciais[105]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_fisica        = isset($aNecessidadesEspeciais[106]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_intelectual   = isset($aNecessidadesEspeciais[107]) ? 1 : $iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_multipla      = isset($aNecessidadesEspeciais[108]) ? 1 : $iDocenteNecessidade;

    switch( $oDadosRecursoHumano->regime_trabalho ) {

      case 1 :

        $iRegimeTrabalho = 1;
        break;

      case 2 :

        $iRegimeTrabalho = 3;
        break;

      case 3:

        $iRegimeTrabalho = 2;
        break;

      case 4:

        $iRegimeTrabalho = 4;
        break;
    }

    $oDadosRecursoHumano->regime_trabalho = $iRegimeTrabalho;
    $this->oDadosGerais                   = $oDadosRecursoHumano;

    return $this->oDadosGerais;
  }

  /**
   * Retorna os dados de identificacao do docente.
   * os dados gerados são referentes ao registro 10.
   */
  public function getDadosIdentificacao() {

    if ($this->oDadosGerais == null) {
      $this->getDados();
    }
    return $this->oDadosGerais;
  }

  /**
   * Retorna as necessidades Especiais do Recurso Humano
   * REGISTRO 30
   */
  public function getNecessidadesEspeciais() {

    $oDaoRecHumanoNecessidadeEspecial     = new cl_rechumanonecessidade();
    $sCamposRecHumanoNecessidadeEspecial  = "ed48_i_codigo  as codigo, ";
    $sCamposRecHumanoNecessidadeEspecial .= "ed48_c_descr  as necessidade, ";
    $sCamposRecHumanoNecessidadeEspecial .= "ed310_sequencial  as possui";
    $sWhereRecHumanoNecessidadeEspecial   = "ed48_i_codigo <> 108"; //não lista registro de Necessidade Multipla

    $sSqlNecessidades  = $oDaoRecHumanoNecessidadeEspecial->sql_query_necessidade(
                                                                                   $this->iCodigoDocente,
                                                                                   $sCamposRecHumanoNecessidadeEspecial,
                                                                                   $sWhereRecHumanoNecessidadeEspecial
                                                                                 );
    $rsNecessidades    = db_query( $sSqlNecessidades );

    if( !$rsNecessidades ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_necessidades_docente', $oMensagem ) );
    }

    $aNecessidades           = array();
    $iQuantidadeNecessidades = 0;

    for( $iNecessidade = 0; $iNecessidade < pg_num_rows( $rsNecessidades ); $iNecessidade++ ) {

      $oNecessidade = db_utils::fieldsMemory($rsNecessidades, $iNecessidade);

      if( $oNecessidade->possui != "" ) {

        unset($oNecessidade->ed310_sequencial);
        $aNecessidades[$oNecessidade->codigo] = $oNecessidade;
        $iQuantidadeNecessidades++;
      }
    }

    if( $iQuantidadeNecessidades > 1 ) {

      /**
       * identifica que existe necessidade múltipla
       */
      $aNecessidades[108] = 't';
    }

    return $aNecessidades;
  }

  /**
   * Retorna os dados de endereço e documentos do Docente
   * REGISTRO 40
   * @throws Exception
   * @return stdClass
   */
  public function getEnderecoDocumento() {

    $oDaoRecursoHumano     = new cl_rechumano();
    $sCamposRecursoHumano  = "case when cgmrh.z01_cgccpf is not null then cgmrh.z01_cgccpf ";
    $sCamposRecursoHumano .= "     else cgmcgm.z01_cgccpf end as numero_cpf, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_cep is not null then cgmrh.z01_cep else cgmcgm.z01_cep end as cep, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_ender is not null then cgmrh.z01_ender else cgmcgm.z01_ender end as endereco, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_numero is not null ";
    $sCamposRecursoHumano .= "     then cgmrh.z01_numero else cgmcgm.z01_numero end as numero_endereco, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_compl is not null ";
    $sCamposRecursoHumano .= "     then cgmrh.z01_compl else cgmcgm.z01_compl end as complemento, ";
    $sCamposRecursoHumano .= "case when cgmrh.z01_bairro is not null ";
    $sCamposRecursoHumano .= "     then cgmrh.z01_bairro else cgmcgm.z01_bairro end as bairro, ";
    $sCamposRecursoHumano .= "ed20_i_zonaresidencia as localizacao_zona_residencia, ";
    $sCamposRecursoHumano .= "ed20_i_censoufender as uf, ";
    $sCamposRecursoHumano .= " ed20_i_censomunicender as municipio";

    $sWhereRecursoHumano  = "    ed20_i_codigo = {$this->iCodigoDocente} ";
    $sWhereRecursoHumano .= "and ed01_funcaoatividade in (1,2,3,4) ";

    $sSqlRecursoHumano = $oDaoRecursoHumano->sql_query_censo( null, $sCamposRecursoHumano, null, $sWhereRecursoHumano );
    $rsRecursoHumano   = db_query( $sSqlRecursoHumano );

    if( !$rsRecursoHumano ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_documentos_docente', $oMensagem ) );
    }

    if( pg_num_rows( $rsRecursoHumano ) == 0 ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'documentos_nao_encontrados', $oMensagem ) );
    }

    $oDadosDocumentoDocente                                   = db_utils::fieldsMemory( $rsRecursoHumano, 0 );
    $oDadosDocumentoDocente->endereco 			 									= $this->removeCaracteres($oDadosDocumentoDocente->endereco, 				7);
    $oDadosDocumentoDocente->numero_endereco                  = $this->removeCaracteres($oDadosDocumentoDocente->numero_endereco, 7);
    $oDadosDocumentoDocente->complemento                  		= $this->removeCaracteres($oDadosDocumentoDocente->complemento, 		7);
    $oDadosDocumentoDocente->complemento                      = substr($oDadosDocumentoDocente->complemento, 0, 20);
    $oDadosDocumentoDocente->bairro                  					= $this->removeCaracteres($oDadosDocumentoDocente->bairro, 					7);
    $oDadosDocumentoDocente->identificacao_unica_docente_inep = $this->iCodigoInep;
    $oDadosDocumentoDocente->codigo_docente_entidade_escola   = $this->iCodigoDocente;

    return $oDadosDocumentoDocente;
  }

  /**
   * Retorna os dados da Formação do Recurso Humano
   * REGISTRO 50
   */
  public function getDadosFormacao() {

    $oDadosIdentificacao = $this->getDados();

    $oDaoFormacao     = new cl_formacao();
    $sWhereFormacao   = "ed27_i_rechumano = {$this->iCodigoDocente}";
    $sWhereFormacao  .= " and ed27_c_situacao <> 'INT' ";
    $sCamposFormacao  = "coalesce(ed27_i_formacaopedag, '0') as formacao_pedagogica,";
    $sCamposFormacao .= "case when ed27_c_situacao = 'CON' THEN  1 else 2 end as situacao_curso, ";
    $sCamposFormacao .= "ed27_i_censoinstsuperior as codigo_instituicao,";
    $sCamposFormacao .= "ed94_c_codigocenso as codigo_curso,";
    $sCamposFormacao .= "ed94_i_grauacademico as grau_formacao,";
    $sCamposFormacao .= "ed27_i_anoinicio as ano_inicio,";
    $sCamposFormacao .= "ed27_i_anoconclusao as ano_conclusao,";
    $sCamposFormacao .= " case ";
    $sCamposFormacao .= "   when ed27_i_censoinstsuperior = 9999999 ";
    $sCamposFormacao .= "     then 2                                 ";
    $sCamposFormacao .= "   else ed257_i_tipo                       ";
    $sCamposFormacao .= " end as tipo_instituicao                   ";

    $sSqlDadosFormacao = $oDaoFormacao->sql_query( null, $sCamposFormacao, "ed27_i_codigo limit 3", $sWhereFormacao );
    $rsDadosFormacao   = db_query( $sSqlDadosFormacao );

    if( !$rsDadosFormacao ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_dados_formacao_docente', $oMensagem ) );
    }

    $aFormacoes     = db_utils::getCollectionByRecord( $rsDadosFormacao );
    $oDadosFormacao = new stdClass();

    $oDadosFormacao->especifico_educacao_especial                      = '';
    $oDadosFormacao->nenhum                                            = '';
    $oDadosFormacao->outros                                            = '';
    $oDadosFormacao->educ_relacoes_etnicorraciais_his_cult_afro_brasil = '';
    $oDadosFormacao->direitos_crianca_adolescente                      = '';
    $oDadosFormacao->genero_diversidade_sexual                         = '';
    $oDadosFormacao->especifico_educacao_direitos_humanos              = '';
    $oDadosFormacao->especifico_educacao_ambiental                     = '';
    $oDadosFormacao->especifico_educacao_campo                         = '';
    $oDadosFormacao->especifico_educacao_indigena                      = '';
    $oDadosFormacao->especifico_eja                                    = '';
    $oDadosFormacao->especifico_ensino_medio                           = '';
    $oDadosFormacao->especifico_anos_finais_ensino_fundamental         = '';
    $oDadosFormacao->especifico_anos_iniciais_ensino_fundamental       = '';
    $oDadosFormacao->especifico_pre_escola_4_5_anos                    = '';
    $oDadosFormacao->especifico_creche_0_3_anos                        = '';
    $oDadosFormacao->pos_graduacao                                     = '';
    $oDadosFormacao->pos_graduacao_doutorado                           = '';
    $oDadosFormacao->pos_graduacao_mestrado                            = '';
    $oDadosFormacao->pos_graduacao_especializacao                      = '';

    $oDadosFormacao->escolaridade = $oDadosIdentificacao->escolaridade;

    $iTotalCursos     = 3;
    $iTotalSuperior   = 0;
    $lEstaEmAndamento = false;

    for ($iFormacao = 0; $iFormacao < $iTotalCursos; $iFormacao++) {

      $iSequencialCurso    = $iFormacao + 1;
      $iSituacaoCurso      = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->situacao_curso      : "";
      $iFormacaoPedagogica = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->formacao_pedagogica : "";
      $iCodigoCurso        = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->codigo_curso        : "";
      $iAnoInicio          = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->ano_inicio          : "";
      $iAnoConclusao       = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->ano_conclusao       : "";
      $iTipoInstituicao    = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->tipo_instituicao    : "";
      $iCodigoInstituicao  = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->codigo_instituicao  : "";

      if( isset( $aFormacoes[$iFormacao] ) && $aFormacoes[$iFormacao]->grau_formacao == 3 ) {
        $iFormacaoPedagogica = '';
      }

      /**
       * Validamos se existe o curso superior, verificando o código. Caso exista, incrementamos a variável iTotalSuperior,
       * e em seguida verificamos se a situação do curso é 2 - Em Andamento
       */
      if( !empty( $iSituacaoCurso ) ) {

        $iTotalSuperior++;
        if( $iSituacaoCurso == 2 ) {
          $lEstaEmAndamento = true;
        }
      }

      $oDadosFormacao->{"situacao_curso_superior_{$iSequencialCurso}"}            = $iSituacaoCurso;
      $oDadosFormacao->{"formacao_complementacao_pedagogica_{$iSequencialCurso}"} = $iFormacaoPedagogica;
      $oDadosFormacao->{"codigo_curso_superior_{$iSequencialCurso}"}              = $iCodigoCurso;
      $oDadosFormacao->{"ano_inicio_curso_superior_{$iSequencialCurso}"}          = $iAnoInicio;
      $oDadosFormacao->{"ano_conclusao_curso_superior_{$iSequencialCurso}"}       = $iAnoConclusao;
      $oDadosFormacao->{"tipo_instituicao_curso_superior_{$iSequencialCurso}"}    = $iTipoInstituicao;
      $oDadosFormacao->{"instituicao_curso_superior_{$iSequencialCurso}"}         = $iCodigoInstituicao;

      /**
       * Quando curso EM ANDAMENTO, não pode ser informado ano de conclusão informado e
       * quando curso CONCLUÍDO, não pode ser informado ano de início do curso
       *
       */
      if ($lEstaEmAndamento) {

      	$oDadosFormacao->{"formacao_complementacao_pedagogica_{$iSequencialCurso}"} = '';
        $oDadosFormacao->{"ano_conclusao_curso_superior_{$iSequencialCurso}"}       = '';
      } else {
        $oDadosFormacao->{"ano_inicio_curso_superior_{$iSequencialCurso}"} = '';
      }
    }

    foreach( $this->getDadosOutrosCursos() as $oRespostas ) {
      $oDadosFormacao->{$oRespostas->campo} = $oRespostas->resposta;
    }

    /**
     * Caso a escolaridade seja diferente de 6 - Superior, ou seja Superior, esteja em Andamento e seja o unico superior,
     * os campos de Pos-Graduacao ficam vazio
     */
    if(    $oDadosFormacao->escolaridade != 6
        || ($oDadosFormacao->escolaridade == 6 && $iTotalSuperior == 1 && $lEstaEmAndamento) ) {

      $oDadosFormacao->pos_graduacao                = "";
      $oDadosFormacao->pos_graduacao_doutorado      = "";
      $oDadosFormacao->pos_graduacao_mestrado       = "";
      $oDadosFormacao->pos_graduacao_especializacao = "";
    }

    $oDadosFormacao->codigo_docente_entidade_escola   = $oDadosIdentificacao->codigo_docente_entidade_escola;
    $oDadosFormacao->identificacao_unica_docente      = $oDadosIdentificacao->identificacao_unica_docente_inep;
    // alterado nome do campo no layout em 2017
    $oDadosFormacao->identificacao_unica_docente_inep = $oDadosIdentificacao->identificacao_unica_docente_inep;

    return $oDadosFormacao;
  }

  /**
   * Retorna os outros cursos do docente
   * REGISTRO 50
   */
  protected function getDadosOutrosCursos() {

    /**
     * Procuramos o código da avaliação da escola.
     */
    $oDaoAvaliacaoRecHumano = new cl_rechumanodadoscenso();
    $sSqlAvaliacaoRecHumano = $oDaoAvaliacaoRecHumano->sql_query_file(
                                                                       null,
                                                                       "ed309_avaliacaogruporesposta",
                                                                       null,
                                                                       "ed309_rechumano = {$this->iCodigoDocente}"
                                                                     );


    $rsAvaliacaoRecHumano = db_query( $sSqlAvaliacaoRecHumano );

    if( !$rsAvaliacaoRecHumano ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_avaliacao_docente', $oMensagem ) );
    }

    if( pg_num_rows( $rsAvaliacaoRecHumano ) == 0 ) {
      $sSqlResposta = " '' ";
    } else {

      $iCodigoAvaliacao  = db_utils::fieldsMemory( $rsAvaliacaoRecHumano, 0 )->ed309_avaliacaogruporesposta;
      $sSqlResposta      = "(select case when trim(db106_resposta) != '' then db106_resposta else '1' end ";
      $sSqlResposta     .= "   from avaliacaogrupoperguntaresposta ";
      $sSqlResposta     .= "        inner join avaliacaoresposta on db108_avaliacaoresposta = db106_sequencial ";
      $sSqlResposta     .= "  where db106_avaliacaoperguntaopcao = db104_sequencial ";
      $sSqlResposta     .= "    and db108_avaliacaogruporesposta = {$iCodigoAvaliacao} limit 1)";
    }

    $oDaoPerguntas    = new cl_avaliacaoperguntaopcaolayoutcampo();
    $sCamposPerguntas = "db52_nome as campo, coalesce({$sSqlResposta}, '0') as resposta";
    $sWherePerguntas  = " db102_avaliacao = 3000002 and ed313_ano = {$this->iAnoCenso} ";
    $sSqlPerguntas    = $oDaoPerguntas->sql_query_avaliacao( null, $sCamposPerguntas, null, $sWherePerguntas );

    $rsPerguntas      = db_query( $sSqlPerguntas );

    if( !$rsPerguntas ) {

      $oMensagem           = new stdClass();
      $oMensagem->iDocente = $this->iCodigoDocente;
      $oMensagem->sErro    = pg_last_error();
      throw new DBException( _M( MSG_DADOS_CENSO_DOCENTE . 'erro_buscar_outros_cursos_docente', $oMensagem ) );
    }

    return db_utils::getCollectionByRecord($rsPerguntas);
  }

  /**
   * Retorna os dados de docencia do Recurso Humano.
   * REGISTRO 51
   */
  public function getDadosDocencia() {

    $oDaoRegenciaHorario = new cl_regenciahorario();
    $aDadosDocencia      = array();
    $oDadosDocente       = $this->getDadosIdentificacao();

    $sWhere   = "     (cgmcgm.z01_numcgm = {$this->iCodigoCgm} or cgmrh.z01_numcgm = {$this->iCodigoCgm})";
    $sWhere  .= " and ed52_i_ano         = {$this->iAnoCenso}";
    $sWhere  .= " and ed57_i_escola      = {$this->iCodigoEscola}";
    $sWhere  .= " and (ed75_i_saidaescola is null OR ed75_i_saidaescola  >= '{$this->oDataBaseCenso->getDate()}') ";
    $sWhere  .= " and ed75_i_escola = {$this->iCodigoEscola}";
    $sWhere  .= " and ed58_ativo is true";
    $sWhere  .= " and ed01_c_regencia = 'S'";
    $sWhere  .= " and ed01_c_docencia = 'S'";

    $sCampos  = "distinct  on(ed57_i_codigo) ed57_i_codigo, ed57_i_codigoinep, ed57_i_tipoturma,";
    $sCampos .= " ed132_censoetapa as censo_etapa,";
    $sCampos .= " case when {$this->iAnoCenso} = 2014 then 1 else ed01_funcaoatividade end as funcao, ";
    $sCampos .= " case when ed342_sequencial is not null then ed343_turmacenso else ed57_i_codigo end as codigo_turma";

    $sSqlTurmasRecursoHumano = $oDaoRegenciaHorario->sql_query_censo( null, $sCampos, "ed57_i_codigo", $sWhere, null, $this->iAnoCenso );

    // echo "busca dados registro 51: <br> $sSqlTurmasRecursoHumano <br><br><br>";
    $rsDadosDocente          = db_query($sSqlTurmasRecursoHumano);
    $iTotalLinhas            = pg_num_rows( $rsDadosDocente );
    $aDocencia               = array();

    for ($iDocente = 0; $iDocente < $iTotalLinhas; $iDocente++) {

      $oDocente                      = db_utils::fieldsMemory($rsDadosDocente, $iDocente);
      $oDocente->iCgmDocenteOriginal = $this->iCodigoCgm;
      $aDocencia[]                   = $oDocente;
    }

    /**
     * Caso o docente esteja substituindo um outro docente na escola COMO TEMPORARIO o docente subistituto não vai
     * ter vínculos real na turma (regencia horario).
     * Visto com Tiago que neste caso, os dados do vínculo na turma, deve ser do docente que esta ausente.
     */
    $sCamposCgmDocenteAusente  = " distinct ";
    $sCamposCgmDocenteAusente .= " (case when cgmrhausente.z01_numcgm is not null ";
    $sCamposCgmDocenteAusente .= "    then cgmrhausente.z01_numcgm              ";
    $sCamposCgmDocenteAusente .= " else cgmcgmausente.z01_numcgm end) as numcgm ";

    $sWhereDocenteAusente      = "     ed322_rechumano   = {$this->iCodigoDocente}  ";
    $sWhereDocenteAusente     .= " and ed322_tipovinculo = 1 "; // Docente subistituto deve ser temporário
    $sWhereDocenteAusente     .= " and ed57_i_escola     = {$this->iCodigoEscola} ";
    $sWhereDocenteAusente     .= " and ed52_i_ano        = {$this->iAnoCenso}     ";
    $sWhereDocenteAusente     .= " and (ed322_periodofinal is null OR ed322_periodofinal >= '{$this->oDataBaseCenso->getDate()}')";

    $oDaoDocenteSubistituto = new cl_docentesubstituto();
    // busca o cgm do docente ausente
    $sSqlDocenteAusente = $oDaoDocenteSubistituto->sql_query_dadosdocenteausente(null, $sCamposCgmDocenteAusente, null, $sWhereDocenteAusente);
    $rsDocenteAusente   = db_query($sSqlDocenteAusente);

    if ( $rsDocenteAusente && pg_num_rows($rsDocenteAusente) > 0) {

      $iLinhas = pg_num_rows($rsDocenteAusente);

      for ($i = 0; $i < $iLinhas; $i++) {

        $iCgmDocenteAusente = db_utils::fieldsMemory($rsDocenteAusente, $i)->numcgm;

        $sWhereAusente   = "     (cgmcgm.z01_numcgm = {$iCgmDocenteAusente} or cgmrh.z01_numcgm = {$iCgmDocenteAusente})";
        $sWhereAusente  .= " and ed52_i_ano         = {$this->iAnoCenso}";
        $sWhereAusente  .= " and ed57_i_escola      = {$this->iCodigoEscola}";
        $sWhereAusente  .= " and (ed75_i_saidaescola is null OR ed75_i_saidaescola  >= '{$this->oDataBaseCenso->getDate()}') ";
        $sWhereAusente  .= " and ed75_i_escola = {$this->iCodigoEscola}";
        $sWhereAusente  .= " and ed58_ativo is true";

        $sSqlTurmasDocenteAusente = $oDaoRegenciaHorario->sql_query_censo( null, $sCampos, "ed57_i_codigo", $sWhereAusente, null, $this->iAnoCenso );
        $rsDadosDocenteAusente    = db_query($sSqlTurmasDocenteAusente);
        $iTotalLinhas             = pg_num_rows( $rsDadosDocenteAusente );

        for ($iDocente = 0; $iDocente < $iTotalLinhas; $iDocente++) {

          $oDocente                      = db_utils::fieldsMemory($rsDadosDocenteAusente, $iDocente);
          $oDocente->iCgmDocenteOriginal = $iCgmDocenteAusente;
          $aDocencia[]                   = $oDocente;
        }
      }
    }// fim busca dados

    $aDocencia = array_merge($aDocencia, $this->getOutrasFuncoesExercidas());
    foreach ( $aDocencia as $oDocente) {

      $oDadosDocencia                                         = new stdClass();
      $oDadosDocencia->tipo_registro                          = 51;
      $oDadosDocencia->identificacao_unica_inep               = $oDadosDocente->identificacao_unica_docente_inep;
      $oDadosDocencia->codigo_docente_entidade_escola         = $this->getCodigoDocente();
      $oDadosDocencia->codigo_turma_inep                      = $oDocente->ed57_i_codigoinep;
      $oDadosDocencia->codigo_turma_entidade_escola           = $oDocente->codigo_turma;
      $oDadosDocencia->funcao_exerce_escola_turma             = $oDocente->funcao;
      $oDadosDocencia->situacao_funcional_contratacao_vinculo = $oDadosDocente->regime_trabalho;
      $oDadosDocencia->lTurmaRegular                          = true;

      $aDisciplinasDocente = $this->getDisciplinasNaTurma($oDocente->ed57_i_codigo, $oDocente->iCgmDocenteOriginal);
      if ($oDocente->funcao != 1) {
        $aDisciplinasDocente  = $this->getTodasDisciplinaTurma($oDocente->ed57_i_codigo);
      }

      for( $iDisciplina = 0; $iDisciplina < 13; $iDisciplina++ ) {

        $iSequencialDiscplina = $iDisciplina + 1;
        $iCodigoDisciplina    = "";

        if (    !in_array($oDocente->censo_etapa, $this->aEtapasCensoTurma)
             && ($oDocente->ed57_i_tipoturma != 4 && $oDocente->ed57_i_tipoturma != 5) ) {

	        if (isset($aDisciplinasDocente[$iDisciplina])) {
	          $iCodigoDisciplina = $aDisciplinasDocente[$iDisciplina]->getDisciplina();
	        }
        }

        if ( !in_array($oDadosDocencia->funcao_exerce_escola_turma, array( 1, 5 ) ) ) {
          $iCodigoDisciplina = "" ;
        }
        $oDadosDocencia->{"codigo_disciplina_{$iSequencialDiscplina}"} = $iCodigoDisciplina;
      }

      /**
       * campo 8 "situacao_funcional_contratacao_vinculo" deve ser null se o campo 7
       * "situacao_funcional_contratacao_vinculo" do registro 51 deve ser igual a 1, 5 ou 6
       */
      if (  !in_array($oDadosDocencia->funcao_exerce_escola_turma, array( 1, 5, 6 )) ) {
        $oDadosDocencia->situacao_funcional_contratacao_vinculo = '';
      }

      $aDadosDocencia[] = $oDadosDocencia;
    }

    $aDadosDocenciaAEE = $this->getDadosDocenciaTurmaAEE();
    foreach ($aDadosDocenciaAEE as $oDocenciaAEE) {
      $aDadosDocencia[] = $oDocenciaAEE;
    }

    return $aDadosDocencia;
  }

  /**
   * Retorna outras as outras funcoes do docente/profissional na escola.
   * @return array
   */
  protected function getOutrasFuncoesExercidas() {

    $oDaoOutrosProfissionais = new cl_turmaoutrosprofissionais();

    $sWhere   = "     (cgmcgm.z01_numcgm = {$this->iCodigoCgm} or cgmrh.z01_numcgm = {$this->iCodigoCgm})";
    $sWhere  .= " and ed52_i_ano    = {$this->iAnoCenso}";
    $sWhere  .= " and ed57_i_escola = {$this->iCodigoEscola}";
    $sWhere  .= " and (ed75_i_saidaescola is null OR ed75_i_saidaescola  >= '{$this->oDataBaseCenso->getDate()}') ";
    $sWhere  .= " and ed75_i_escola = {$this->iCodigoEscola}";

    $sCampos  = "distinct  on(ed57_i_codigo) ed57_i_codigo, ed57_i_codigoinep, ed57_i_tipoturma,";
    $sCampos .= " ed132_censoetapa as censo_etapa,";
    $sCampos .= " ed347_funcaoatividade as funcao,";
    $sCampos .= " case when ed342_sequencial is not null then ed343_turmacenso else ed57_i_codigo end as codigo_turma";

    $sSqlTurmasRecursoHumano = $oDaoOutrosProfissionais->sql_query_cgm(null, $sCampos, "ed57_i_codigo", $sWhere);
    $rsDadosDocente          = db_query( $sSqlTurmasRecursoHumano );

    if ( !$rsDadosDocente ) {
      throw new Exception("Erro ao buscar outras funções do profissional.\n".pg_last_error());
    }
    $iLinhas = pg_num_rows($rsDadosDocente);

    $aOutrasFuncoes = array();
    for ($i = 0; $i < $iLinhas; $i++ ) {

      $oDados                      = db_utils::fieldsMemory($rsDadosDocente, $i);
      $oDados->iCgmDocenteOriginal = $this->iCodigoCgm;
      $aOutrasFuncoes[] = $oDados;
    }

    return $aOutrasFuncoes;
  }

  /**
   * Retorna as Disciplinas que o Docente leciona na turma .
   * @param integer $iTurma
   * @param integer $iCgmDocente codigo do cgm vinculado na turma (informado por causa dos docentes subistitutos temporarios)
   * @return array
   */
  public function getDisciplinasNaTurma( $iTurma, $iCgmDocente ) {

    $oDaoDisciplinas   = new cl_regenciahorario();
    $sWhere            = "     ed57_i_Codigo      = {$iTurma} ";
    $sWhere           .= " and (cgmcgm.z01_numcgm = {$iCgmDocente} or cgmrh.z01_numcgm = {$iCgmDocente})";
    $sWhere           .= " and ed58_ativo is true ";
    $sCampos           = "distinct ed265_i_codigo as codigo, ed265_c_descr   as descricao";
    $sSqlDisciplinas   = $oDaoDisciplinas->sql_query_disciplina_regencia_censo(null, $sCampos, null, $sWhere);
    $rsDisciplinas     = db_query($sSqlDisciplinas);
    $iTotalDisciplinas = pg_num_rows( $rsDisciplinas );
    $aDisciplinas      = array();

    for( $iDisciplina = 0; $iDisciplina < $iTotalDisciplinas; $iDisciplina++ ) {

      $oDisciplina      = db_utils::fieldsMemory($rsDisciplinas, $iDisciplina);
      $oDisciplinaCenso = new DisciplinaCenso();
      $oDisciplinaCenso->setNome($oDisciplina->descricao);
      $oDisciplinaCenso->setDisciplina($oDisciplina->codigo);
      $aDisciplinas[]  = $oDisciplinaCenso;
    }

    return $aDisciplinas;
  }

  /**
   * Retorna as disciplinas obrigatórias da turma
   * @param  integer $iTurma
   * @return DisciplinaCenso[]
   * @throws DBException
   */
  public function getTodasDisciplinaTurma($iTurma) {

    $sWhere  = "     ed59_i_turma    = {$iTurma} ";
    $sWhere .= " and ed59_c_condicao = 'OB'   ";

    $sCampos = "distinct ed265_i_codigo as codigo, ed265_c_descr   as descricao";

    $oDaoRegencia = new cl_regencia();
    $sSqlRegencia = $oDaoRegencia->sql_query_censo(null, $sCampos, null, $sWhere);
    $rsRegencia   = db_query($sSqlRegencia);

    if ( !$rsRegencia ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException ( _M( MSG_DADOS_CENSO_DOCENTE . "erro_buscar_disciplinas", $oErro) );
    }

    $aDisciplinas = array();
    $iLinhas      = pg_num_rows( $rsRegencia );

    for( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

      $oDisciplina      = db_utils::fieldsMemory( $rsRegencia, $iContador );
      $oDisciplinaCenso = new DisciplinaCenso();
      $oDisciplinaCenso->setNome( $oDisciplina->descricao );
      $oDisciplinaCenso->setDisciplina( $oDisciplina->codigo );
      $aDisciplinas[] = $oDisciplinaCenso;
    }

    return $aDisciplinas;
  }

  /**
   * Retorna os dados das docencias em turmas de Atendimento especializado
   * @return array
   */
  public function getDadosDocenciaTurmaAEE() {

    $aDadosDocente = array();
    $aTurmasACAEE  = array();

    foreach( $this->aTurmasCenso as $oTurmasCenso ) {

      if( $oTurmasCenso->tipo_atendimento == 4 || $oTurmasCenso->tipo_atendimento == 5 ) {
        $aTurmasACAEE[] = $oTurmasCenso->codigo_turma_entidade_escola;
      }
    }

    if( count( $aTurmasACAEE ) == 0 ) {
      return $aDadosDocente;
    }

    $sTurmasACAEE       = implode( ",", $aTurmasACAEE );
    $oDadosDocente      = $this->getDadosIdentificacao();
    $oDaoTurmaACHorario = new cl_turmaachorarioprofissional();
    $sWhere             = "     ed346_rechumano = {$this->iCodigoDocente}";
    $sWhere            .= " and ed52_i_ano      = {$this->iAnoCenso}";
    $sWhere            .= " and ed268_i_escola  = {$this->iCodigoEscola}";
    $sWhere            .= " and ed346_turmaac in( {$sTurmasACAEE} )";
    $sCampos            = " DISTINCT on(ed268_i_codigo)ed268_i_codigo, ed268_i_codigoinep, ";
    $sCampos           .= " case when {$this->iAnoCenso} = 2014 then 1 else ed346_funcaoatividade end as funcao ";
    $sSqlHorario        = $oDaoTurmaACHorario->sql_query_vinculo_profissional(null, $sCampos, null, $sWhere);
    $rsHorario          = db_query($sSqlHorario);
    $iTotalHorarios     = pg_num_rows( $rsHorario );

    for ($iDocente = 0; $iDocente < $iTotalHorarios; $iDocente++) {

      $oDocente                                               = db_utils::fieldsMemory($rsHorario, $iDocente);
      $oDadosDocencia                                         = new stdClass();
      $oDadosDocencia->tipo_registro                          = 51;
      $oDadosDocencia->identificacao_unica_inep               = $oDadosDocente->identificacao_unica_docente_inep;
      $oDadosDocencia->codigo_docente_entidade_escola         = $this->iCodigoDocente;
      $oDadosDocencia->codigo_turma_inep                      = $oDocente->ed268_i_codigoinep;
      $oDadosDocencia->codigo_turma_entidade_escola           = $oDocente->ed268_i_codigo;
      $oDadosDocencia->funcao_exerce_escola_turma             = $oDocente->funcao;
      $oDadosDocencia->situacao_funcional_contratacao_vinculo = $oDadosDocente->regime_trabalho;
      $oDadosDocencia->lTurmaRegular                          = false;

      for ($iDisciplina = 0; $iDisciplina < 13; $iDisciplina++) {

        $iSequencialDiscplina    = $iDisciplina + 1;
        $oDadosDocencia->{"codigo_disciplina_{$iSequencialDiscplina}"} = '';
      }

      /*
       * campo 8 "situacao_funcional_contratacao_vinculo" deve ser null se o campo 7
       * "situacao_funcional_contratacao_vinculo" do registro 51 deve ser igual a 1, 5 ou 6
       */
      if (  !in_array($oDadosDocencia->funcao_exerce_escola_turma, array( 1, 5, 6 )) ) {
        $oDadosDocencia->situacao_funcional_contratacao_vinculo = '';
      }

      $aDadosDocente[] = $oDadosDocencia;
    }

    return $aDadosDocente;
  }

  /**
   * Valida os dados do arquivo
   * @param IExportacaoCenso $oExportacaoCenso  Importacao do censo
   * @return boolean
   */
  public static function validarDados(IExportacaoCenso $oExportacaoCenso) {

    $lDadosValidos = true;
    $oDadosDocente = $oExportacaoCenso->getDadosProcessadosDocente();

    foreach ($oDadosDocente as $oDadosCensoDocente) {

      $sDadosDocente  = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo;
      $sDadosDocente .= " - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;

      /**
       * ***************************************************************************************************************
       * ********************************** Validações do registro 30 **************************************************
       * ***************************************************************************************************************
       */
      if (strpos($oDadosCensoDocente->registro30->nome_completo, '  ')) {

    		$sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
    		$sMsgErro .= "O nome deve conter apenas espaços simples.";
    		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
    		$lDadosValidos = false;
    	}

      if (strpos($oDadosCensoDocente->registro30->nome_completo, '  ')) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
    	  $sMsgErro .= "O nome deve conter apenas espaços simples.";
    	  $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
    	  $lDadosValidos = false;
      }
      if( !DBString::isNomeValido($oDadosCensoDocente->registro30->nome_completo, 4)) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "O nome contém 4 ou mais caractéres iguais em sequência.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( !empty($oDadosCensoDocente->registro30->identificacao_unica_docente_inep) &&
           strlen($oDadosCensoDocente->registro30->identificacao_unica_docente_inep) < 12) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Identificação única do Profissional escolar em sala de Aula (INEP) com tamanho diferente do especificado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ($oDadosCensoDocente->registro30->data_nascimento == '') {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Deve ser informado a data de nascimento do Docente";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }


      if ( !empty($oDadosCensoDocente->registro30->data_nascimento)) {

        $oDataNascimento = new DBDate( $oDadosCensoDocente->registro30->data_nascimento );
        if( $oDataNascimento->getAno() < 1919 || $oDataNascimento->getAno() > 2000 ) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "O ano de nascimento do Docente deve estar entre os anos de 1919 e 2000.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }
        unset($oDataNascimento);
      }

      if (!empty($oDadosCensoDocente->registro30->nome_completo_mae)) {

      	if(!DBString::isSomenteLetras($oDadosCensoDocente->registro30->nome_completo_mae)){

      		$sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro .= "O nome da mãe possui caracteres inválidos, deve ser informado apenas letras.\n";
          $sMsgErro .= "Nome: {$oDadosCensoDocente->registro30->nome_completo_mae}";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      		$lDadosValidos = false;
      	}

      	if(strpos($oDadosCensoDocente->registro30->nome_completo_mae, '  ')){

      		$sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro .= "O nome da mãe deve conter apenas espaços simples.";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      		$lDadosValidos = false;
      	}

        if( !DBString::isNomeValido($oDadosCensoDocente->registro30->nome_completo_mae, 4)){

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "O nome da mãe contém 4 ou mais caractéres iguais em sequência.\n";
          $sMsgErro .= "Nome: {$oDadosCensoDocente->registro30->nome_completo_mae}";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }
      }

      if (!empty($oDadosCensoDocente->registro30->email)) {

      	if(!DBString::isEmail(($oDadosCensoDocente->registro30->email))){

      		$sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro .= "O e-mail informado não é válido.";
      		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
      		$lDadosValidos = false;
      	}
      }

      if ($oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) {

        if ($oDadosCensoDocente->registro30->tipos_deficiencia_multipla == 1) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Deve ser selecionada apenas uma das opções: Deficiência Intelectual ou Deficiência Múltipla.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }
      }
      /**
       * Verifica se foi selecionada mais de uma opção dentre as quatro abaixo
       */
      $aTiposDeficienciaVisao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_cegueira;
      $aTiposDeficienciaVisao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao;
      $aTiposDeficienciaVisao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_surdez;
      $aTiposDeficienciaVisao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira;
      $iTotalDeficienciaVisao = 0;

      foreach ($aTiposDeficienciaVisao as $iTiposDeficienciaVisao) {
        if ($iTiposDeficienciaVisao == 1) {
          $iTotalDeficienciaVisao++;
        }
      }

      if ($iTotalDeficienciaVisao > 1) {

        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Deve ser selecionada apenas uma dentre os seguintes tipos de deficiência: Cegueira, Baixa Visão,";
        $sMsgErro .= " Surdez ou Surdocegueira.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      /**
       * Verifica se foi selecionada mais de uma opção dentre as quatro abaixo
       */
      $aTiposDeficienciaAudicao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_cegueira;
      $aTiposDeficienciaAudicao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_surdez;
      $aTiposDeficienciaAudicao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_auditiva;
      $aTiposDeficienciaAudicao[] = $oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira;
      $iTotalDeficienciaAudicao = 0;

      foreach ($aTiposDeficienciaAudicao as $iTiposDeficienciaAudicao) {
        if ($iTiposDeficienciaAudicao == 1) {
          $iTotalDeficienciaAudicao++;
        }
      }

      if ($iTotalDeficienciaAudicao > 1) {

        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Deve ser selecionada apenas uma dentre os seguintes tipos de deficiência: Cegueira, Surdez,";
        $sMsgErro     .= " Auditiva ou Surdocegueira.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ($oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira == 1 ) {

        if ($oDadosCensoDocente->registro30->tipos_deficiencia_cegueira    == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_surdez      == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_auditiva    == 1) {

          $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro .= "Ao selecionadar a opção Deficiência Surdocegueira, ";
          $sMsgErro .= "os outros tipos de deficiência devem ser desmarcados.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }
      }

      /**
       * Validação para deficiências múltiplas
       */

      if (($oDadosCensoDocente->registro30->tipos_deficiencia_cegueira      == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_fisica      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_cegueira      == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao   == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_fisica      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao   == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_surdez        == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_fisica      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_surdez        == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_fisica      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_cegueira      == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_auditiva    == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao   == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_surdez      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao   == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_auditiva    == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_fisica        == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_auditiva      == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_fisica      == 1) ||
          ($oDadosCensoDocente->registro30->tipos_deficiencia_auditiva      == 1 && $oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1)) {

        if ($oDadosCensoDocente->registro30->tipos_deficiencia_multipla != 1) {

          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Tipo de Deficiência Múltipla é obrigatória.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }
      }

      if ( !empty($oDadosCensoDocente->registro30->numero_identificacao_social_inss) &&
           !DadosCenso::ValidaNIS($oDadosCensoDocente->registro30->numero_identificacao_social_inss)
         ) {
        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Código de NIS informado esta inválido.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( in_array($oDadosCensoDocente->registro30->nacionalidade_docente, array(1,2)) &&
           $oDadosCensoDocente->registro30->pais_origem != 76
         ) {
        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem deve ser BRASIL quando nacionalidade: \n";
        $sMsgErro .= " - Brasileira;\n - Brasileira nascido no Exterior.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( ($oDadosCensoDocente->registro30->nacionalidade_docente == 3) &&
           ($oDadosCensoDocente->registro30->pais_origem == 76)
         ) {
        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "País de origem deve diferente de BRASIL quando nacionalidade: \n";
        $sMsgErro .= " - Estrangeira.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oDadosCensoDocente->registro30->nacionalidade_docente == 1 &&
           empty($oDadosCensoDocente->registro30->uf_nascimento) ) {
        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "UF de nascimento não informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ( $oDadosCensoDocente->registro30->nacionalidade_docente == 1 &&
           empty($oDadosCensoDocente->registro30->municipio_nascimento) ) {
        $sMsgErro  = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro .= "Munícipio de nascimento não informado.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }


      /**
       * ***************************************************************************************************************
       * ********************************** Validações do registro 40 **************************************************
       * ***************************************************************************************************************
       */
      $aEnderecoCompleto   = array();
      $aEnderecoCompleto[] = $oDadosCensoDocente->registro40->cep;
      $aEnderecoCompleto[] = $oDadosCensoDocente->registro40->endereco;
      $aEnderecoCompleto[] = $oDadosCensoDocente->registro40->uf;
      $aEnderecoCompleto[] = $oDadosCensoDocente->registro40->municipio;
      $iTotalEnderecoCompleto = 0;

      foreach ($aEnderecoCompleto as $iEnderecoCompleto) {
        if (empty($iEnderecoCompleto) || $iEnderecoCompleto == "NAO INFORMADO") {
          $iTotalEnderecoCompleto++;
        }
      }

      if ($iTotalEnderecoCompleto > 0 && $iTotalEnderecoCompleto != 4) {

        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Os campos CEP, Endereço, UF e Município devem ser preenchidos.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      if ($oDadosCensoDocente->registro40->localizacao_zona_residencia == 0 ||
          $oDadosCensoDocente->registro40->localizacao_zona_residencia == '') {

        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Informe a zona de Residencia do Docente.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;
      }

      /**
       * ***************************************************************************************************************
       * ********************************** Validações do registro 40 **************************************************
       * ***************************************************************************************************************
       */
      if ($oDadosCensoDocente->registro50->escolaridade == 6) {

        /**
         * Validação que verifica se o Docente possui apenas 1 curso superior. Caso sim, verifica a situacao atual
         * deste curso superior. Caso esteja como 2 (Em andamento), verifica se algumas das opcoes referentes a
         * Pos-Graduacao foi selecionado. Caso se confirme, mostra o erro pois nao eh permitido um Docente com superior
         * em andamento, ter nenhum tipo de Pos-Graduacao
         * Obs.: a validacao eh feita inclusive se a opcao "Nenhum" na avaliacao de Pos-Graduacao do Docente, estiver
         * selecionada
         */
        if ($oDadosCensoDocente->registro50->codigo_curso_superior_1 != "" &&
            $oDadosCensoDocente->registro50->codigo_curso_superior_2 == "" &&
            $oDadosCensoDocente->registro50->codigo_curso_superior_3 == ""
           ) {

          if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 2) {

            if ($oDadosCensoDocente->registro50->pos_graduacao                != "" ||
                $oDadosCensoDocente->registro50->pos_graduacao_doutorado      != "" ||
                $oDadosCensoDocente->registro50->pos_graduacao_mestrado       != "" ||
                $oDadosCensoDocente->registro50->pos_graduacao_especializacao != ""
               ) {

              $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
              $sMsgErro     .= "Docente possui apenas uma graduação, a qual está em andamento. Logo, não deve ser";
              $sMsgErro     .= " selecionada nenhuma opção referente a Pós-Graduação.";
              $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
              $lDadosValidos = false;
            }
          }

          if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 1 ||
          		$oDadosCensoDocente->registro50->codigo_curso_superior_2   == 1 ||
          		$oDadosCensoDocente->registro50->codigo_curso_superior_3   == 1) {

          	if ($oDadosCensoDocente->registro50->pos_graduacao                == 0 &&
          			$oDadosCensoDocente->registro50->pos_graduacao_doutorado      == 0 &&
          			$oDadosCensoDocente->registro50->pos_graduacao_mestrado       == 0 &&
          			$oDadosCensoDocente->registro50->pos_graduacao_especializacao == 0
          	) {

          		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          		$sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos referentes a Pós-Graduação";
          		$sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          		$lDadosValidos = false;
          	}

            if (  $oDadosCensoDocente->registro50->pos_graduacao == 1 &&
              ($oDadosCensoDocente->registro50->pos_graduacao_doutorado      == 1 ||
                $oDadosCensoDocente->registro50->pos_graduacao_mestrado       == 1 ||
                $oDadosCensoDocente->registro50->pos_graduacao_especializacao == 1)
            ) {

              $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
              $sMsgErro     .= " Quando informado \"Nenhum\" a \"Pós-Graduação\" não pode-se marcar outra opção. ";
              $sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
              $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
              $lDadosValidos = false;
            }


          	if ($oDadosCensoDocente->registro50->especifico_educacao_especial                      == 0 &&
          			$oDadosCensoDocente->registro50->nenhum 											                     == 0 &&
          			$oDadosCensoDocente->registro50->outros 											                     == 0 &&
          			$oDadosCensoDocente->registro50->educ_relacoes_etnicorraciais_his_cult_afro_brasil == 0 &&
          			$oDadosCensoDocente->registro50->direitos_crianca_adolescente 				             == 0 &&
          			$oDadosCensoDocente->registro50->genero_diversidade_sexual 					               == 0 &&
          			$oDadosCensoDocente->registro50->especifico_educacao_direitos_humanos              == 0 &&
          			$oDadosCensoDocente->registro50->especifico_educacao_ambiental 			               == 0 &&
          			$oDadosCensoDocente->registro50->especifico_educacao_campo 						             == 0 &&
          			$oDadosCensoDocente->registro50->especifico_educacao_indigena 				             == 0 &&
          			$oDadosCensoDocente->registro50->especifico_eja 											             == 0 &&
          			$oDadosCensoDocente->registro50->especifico_ensino_medio 							             == 0 &&
          			$oDadosCensoDocente->registro50->especifico_anos_finais_ensino_fundamental         == 0 &&
          			$oDadosCensoDocente->registro50->especifico_anos_iniciais_ensino_fundamental       == 0 &&
          			$oDadosCensoDocente->registro50->especifico_pre_escola_4_5_anos                    == 0 &&
          			$oDadosCensoDocente->registro50->especifico_creche_0_3_anos 		                   == 0
          	) {

          		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          		$sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos";
          		$sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          		$oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          		$lDadosValidos = false;
          	}


            if (  $oDadosCensoDocente->registro50->nenhum == 1 &&
              ( $oDadosCensoDocente->registro50->especifico_educacao_especial                      == 1 ||
                $oDadosCensoDocente->registro50->outros                                            == 1 ||
                $oDadosCensoDocente->registro50->educ_relacoes_etnicorraciais_his_cult_afro_brasil == 1 ||
                $oDadosCensoDocente->registro50->direitos_crianca_adolescente                      == 1 ||
                $oDadosCensoDocente->registro50->genero_diversidade_sexual                         == 1 ||
                $oDadosCensoDocente->registro50->especifico_educacao_direitos_humanos              == 1 ||
                $oDadosCensoDocente->registro50->especifico_educacao_ambiental                     == 1 ||
                $oDadosCensoDocente->registro50->especifico_educacao_campo                         == 1 ||
                $oDadosCensoDocente->registro50->especifico_educacao_indigena                      == 1 ||
                $oDadosCensoDocente->registro50->especifico_eja                                    == 1 ||
                $oDadosCensoDocente->registro50->especifico_ensino_medio                           == 1 ||
                $oDadosCensoDocente->registro50->especifico_anos_finais_ensino_fundamental         == 1 ||
                $oDadosCensoDocente->registro50->especifico_anos_iniciais_ensino_fundamental       == 1 ||
                $oDadosCensoDocente->registro50->especifico_pre_escola_4_5_anos                    == 1 ||
                $oDadosCensoDocente->registro50->especifico_creche_0_3_anos                        == 1)
            ) {
              $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
              $sMsgErro     .= " Quando informado \"Nenhum\" a \"Outros Cursos\" não pode-se marcar outra opção. ";
              $sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
              $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
              $lDadosValidos = false;
            }
          }
        }

        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1         == "" ||
            $oDadosCensoDocente->registro50->codigo_curso_superior_1           == "" ||
            $oDadosCensoDocente->registro50->tipo_instituicao_curso_superior_1 == "" ||
            $oDadosCensoDocente->registro50->instituicao_curso_superior_1      == "") {

          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Os seguintes campos devem ser preenchidos: Situação do Curso Superior 1, Código do Curso Superior 1,";
          $sMsgErro     .= " Tipo de Instituição do Curso Superior 1 e Instituição do Curso Superior 1.";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 1) {

          if ($oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 != "") {

            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Formação marcada como concluída. Não deve ser indicada a data de início.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }

          if ($oDadosCensoDocente->registro50->ano_conclusao_curso_superior_1 == "") {

            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Ano de conclusao do Curso Superior 1 deve ser informado.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }
        }

        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 2) {

          if ($oDadosCensoDocente->registro50->ano_conclusao_curso_superior_1 != "") {

            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Formação em andamento. Ano de Conclusão do Curso não pode ser informada.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }

          if ($oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 == "") {

            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "formação em andamento. Deve ser informado o ano de início do curso superior.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }

          if (($this->getAnoCenso()-$oDadosCensoDocente->registro50->ano_inicio_curso_superior_1) > 11 &&
          		$oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 != "") {

            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "O intervalor entre o ano de início do curso superior e o Ano Base do Censo é superior a 11 anos.";
            $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
            $lDadosValidos = false;
          }
        }
      }

      if ($oDadosCensoDocente->registro50->escolaridade != 6) {

        if ($oDadosCensoDocente->registro50->especifico_educacao_especial == 0 &&
            $oDadosCensoDocente->registro50->nenhum == 0 &&
            $oDadosCensoDocente->registro50->outros == 0 &&
            $oDadosCensoDocente->registro50->educ_relacoes_etnicorraciais_his_cult_afro_brasil == 0 &&
            $oDadosCensoDocente->registro50->direitos_crianca_adolescente == 0 &&
            $oDadosCensoDocente->registro50->genero_diversidade_sexual == 0 &&
            $oDadosCensoDocente->registro50->especifico_educacao_direitos_humanos == 0 &&
            $oDadosCensoDocente->registro50->especifico_educacao_ambiental == 0 &&
            $oDadosCensoDocente->registro50->especifico_educacao_campo == 0 &&
            $oDadosCensoDocente->registro50->especifico_educacao_indigena == 0 &&
            $oDadosCensoDocente->registro50->especifico_eja == 0 &&
            $oDadosCensoDocente->registro50->especifico_ensino_medio == 0 &&
            $oDadosCensoDocente->registro50->especifico_anos_finais_ensino_fundamental == 0 &&
            $oDadosCensoDocente->registro50->especifico_anos_iniciais_ensino_fundamental == 0 &&
            $oDadosCensoDocente->registro50->especifico_pre_escola_4_5_anos == 0 &&
            $oDadosCensoDocente->registro50->especifico_creche_0_3_anos == 0
           ) {

          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos";
          $sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
          $lDadosValidos = false;
        }

      }

      if (count($oDadosCensoDocente->registro51) == 0) {

        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Docente não possui vínculo com turmas.";
        $oExportacaoCenso->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);
        $lDadosValidos = false;

      }
    }

    return $lDadosValidos;
  }
  public function atualizarDados ($oLinha, $oDados) {

    $oDaoRechumano              = new cl_rechumano();
    $oDaoRechumano->ed20_i_pais = "";

    if (isset($oLinha->identificacao_unica_docente_inep)
         && $oLinha->identificacao_unica_docente_inep != trim($oDados->ed20_i_codigoinep)) {
       $oDaoRechumano->ed20_i_codigoinep = $oLinha->identificacao_unica_docente_inep;
}
    if ($oLinha->numero_identificacao_social_inss != ""
        && $oLinha->numero_identificacao_social_inss != trim($oDados->ed20_c_nis)) {
     $oDaoRechumano->ed20_c_nis = $oLinha->numero_identificacao_social_inss;
    }

    if ($oLinha->cor_raca != ""
          && $oLinha->cor_raca != trim($oDados->ed20_i_raca)) {
       $oDaoRechumano->ed20_i_raca = $oLinha->cor_raca;
    }

    if ($oLinha->nacionalidade_docente != ""
        && $oLinha->nacionalidade_docente != trim($oDados->ed20_i_nacionalidade)) {
      $oDaoRechumano->ed20_i_nacionalidade = $oLinha->nacionalidade_docente;
    }

    if (!empty($oLinha->pais_origem)
         && $oLinha->pais_origem != trim($oDados->ed228_i_paisonu)) {
      $oDaoRechumano->ed20_i_pais = $this->getPais($oLinha->pais_origem);
    }

    if ($oLinha->uf_nascimento != ""
          && $oLinha->uf_nascimento != trim($oDados->ed20_i_censoufnat)) {
       $oDaoRechumano->ed20_i_censoufnat = $oLinha->uf_nascimento;
    }

    if ($oLinha->municipio_nascimento != ""
         && $oLinha->municipio_nascimento != trim($oDados->ed20_i_censomunicnat)) {
        $oDaoRechumano->ed20_i_censomunicnat = $oLinha->municipio_nascimento;
    }

    $oDaoRechumano->ed20_i_codigo = $oDados->ed20_i_codigo;
    $oDaoRechumano->alterar($oDados->ed20_i_codigo);

    if ($oDaoRechumano->erro_status == '0') {
       throw new Exception("Erro na alteração dos dados do Rechumano. Erro da classe: ".$oDaoRechumano->erro_msg);
     }

     $this->atualizarNecessidadesEspeciais($oLinha);
  }

  /**
   * Atualização dos dados de endereço do docente
   */
  function atualizarDadosEndereco($oLinha, $oDadosEndereco) {

    $oDaoRechumano = new cl_rechumano();

    if ($oLinha->uf != ""
        && $oLinha->uf != trim($oDadosEndereco->ed20_i_censoufender)) {
      $oDaoRechumano->ed20_i_censoufender = $oLinha->uf;
    }

    if ($oLinha->municipio != ""
        && $oLinha->municipio != trim($oDadosEndereco->ed20_i_censomunicender)) {
      $oDaoRechumano->ed20_i_censomunicender = $oLinha->municipio;
    }

    $oDaoRechumano->ed20_i_zonaresidencia = $oLinha->localizacao_zona_residencia  ;
    $oDaoRechumano->ed20_i_codigo         = $oDadosEndereco->ed20_i_codigo;
    $oDaoRechumano->alterar($oDadosEndereco->ed20_i_codigo);

    if ($oDaoRechumano->erro_status == '0') {
      throw new Exception("Erro na alteração dos dados de endereço do Recurso Humano. Erro da classe: ".$oDaoRechumano->erro_msg);
    }

  }

  /**
   * Atualiza os dados da escolaridade do docente
   */
  public function atualizarDadosEscolaridade($oLinha) {

   $oDaoRechumano = new cl_rechumano();
   if (isset($oLinha->escolaridade) && !empty($oLinha->escolaridade)) {
      $oDaoRechumano->ed20_i_escolaridade = $oLinha->escolaridade;
    }

    $oDaoRechumano->ed20_i_codigo = $this->iCodigoDocente;
    $oDaoRechumano->alterar($oDaoRechumano->ed20_i_codigo);

    if ($oDaoRechumano->erro_status == '0') {
      throw new Exception("Erro na alteração dos dados de endereço do Recurso Humano. Erro da classe: ".$oDaoRechumano->erro_msg);
    }
    $oDaoCursoFormacao   = new cl_cursoformacao();
    $oDaoFormacao        = new cl_formacao();
    $oDaoFormacao->excluir(null,"ed27_i_rechumano = {$this->iCodigoDocente}");
    $aFormacao = array();
    for ($i = 1; $i <= 3; $i++) {

      if (   isset($oLinha->{"situacao_curso_superior_$i"}) && isset($oLinha->{"formacao_complementacao_pedagogica_$i"})
          && isset($oLinha->{"codigo_curso_superior_$i"}) && isset($oLinha->{"ano_inicio_curso_superior_$i"})
          && isset($oLinha->{"ano_conclusao_curso_superior_$i"}) && isset($oLinha->{"tipo_instituicao_curso_superior_$i"})
          && isset($oLinha->{"instituicao_curso_superior_$i"})) {

          $aFormacao[] = array(trim($oLinha->{"situacao_curso_superior_$i"}),
                               trim($oLinha->{"formacao_complementacao_pedagogica_$i"}),
                               trim($oLinha->{"codigo_curso_superior_$i"}),
                               trim($oLinha->{"ano_inicio_curso_superior_$i"}),
                               trim($oLinha->{"ano_conclusao_curso_superior_$i"}),
                               trim($oLinha->{"tipo_instituicao_curso_superior_$i"}),
                               trim($oLinha->{"instituicao_curso_superior_$i"})
                             );
      }
    }

    /**
     * Inclui os cursos do Recurso Humano (Aba Formação)
     */
    foreach ($aFormacao as $iFormacao => $aFormacaoDecente)  {

      if ( empty($aFormacaoDecente[2]) ) {
        continue;
      }

      $sWhereCursoFormacao = "ed94_c_codigocenso = '".$aFormacaoDecente[2]."'";
      $sSqlCursoFormacao   = $oDaoCursoFormacao->sql_query("", "*", "", $sWhereCursoFormacao);
      $rsCursoFormacao     = $oDaoCursoFormacao->sql_record($sSqlCursoFormacao);

      if ($oDaoCursoFormacao->numrows > 0) {

        if ($aFormacaoDecente[0] != null) {

          $oDaoFormacao->ed27_i_rechumano         = $this->iCodigoDocente;
          $oDaoFormacao->ed27_i_cursoformacao     = db_utils::fieldsmemory($rsCursoFormacao, 0)->ed94_i_codigo;
          $oDaoFormacao->ed27_c_situacao          = 'CON';
          $oDaoFormacao->ed27_i_licenciatura      = (isset($aFormacaoDecente[0]) ? $aFormacaoDecente[0] : '');
          $oDaoFormacao->ed27_i_anoconclusao      = (isset($aFormacaoDecente[4]) ? $aFormacaoDecente[4] : '');
          $oDaoFormacao->ed27_i_censosuperior     = (isset($aFormacaoDecente[5]) ? $aFormacaoDecente[5] : '');
          $oDaoFormacao->ed27_i_censoinstsuperior = (isset($aFormacaoDecente[6]) ? $aFormacaoDecente[6] : '');
          $oDaoFormacao->incluir(null);
          if ($oDaoFormacao->erro_status == '0') {

            throw new Exception("Erro na alteração dos dados da Formação do professor. Erro da classe: ".
                                $oDaoFormacao->erro_msg
                               );

          }
        }
      }
    }
  }

  /**
   * Retorna a avaliacao do docente.
   *
   * @throws Exception
   * @return Avaliacao
   */
  public function getAvaliacacaoEscolaridade() {

    $oDaoAvaliacaoRecursoHumano = new cl_rechumanodadoscenso();
    $sSqlCodigoAvaliacao     = $oDaoAvaliacaoRecursoHumano->sql_query_file(null,
                                                                  "ed309_avaliacaogruporesposta",
                                                                  null,
                                                                  "ed309_rechumano = {$this->getCodigoDocente()}"
                                                                 );

    $rsCodigoAvaliacao = db_query($sSqlCodigoAvaliacao);

    if ($rsCodigoAvaliacao && pg_num_rows($rsCodigoAvaliacao) == 1) {
      $iCodigoAvaliacao = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed309_avaliacaogruporesposta;
    } else {

      $oDaoAvaliacaoGrupoPergunta = new cl_avaliacaogruporesposta();
      $oDaoAvaliacaoGrupoPergunta->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoAvaliacaoGrupoPergunta->db107_hora           = db_hora();
      $oDaoAvaliacaoGrupoPergunta->db107_usuario        = db_getsession("DB_id_usuario");
      $oDaoAvaliacaoGrupoPergunta->incluir(null);
      if ($oDaoAvaliacaoGrupoPergunta->erro_status == 0) {
        throw new Exception($oDaoAvaliacaoGrupoPergunta->erro_msg);
      }
      $oDaoAvaliacaoRecursoHumano->ed309_avaliacaogruporesposta = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
      $oDaoAvaliacaoRecursoHumano->ed309_rechumano              = $this->getCodigoDocente();
      $oDaoAvaliacaoRecursoHumano->incluir(null);
      if ($oDaoAvaliacaoRecursoHumano->erro_status == 0) {
        throw new Exception($oDaoAvaliacaoRecursoHumano->erro_msg);
      }
      $iCodigoAvaliacao = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
    }

    $oAvaliacao = new Avaliacao(3000002);
    $oAvaliacao->setAvaliacaoGrupo($iCodigoAvaliacao);
    return $oAvaliacao;
  }

  protected function atualizarNecessidadesEspeciais(DBLayoutLinha $oLinha) {

    $oDaoAlunoNecessidade = new cl_rechumanonecessidade();
    $oDaoAlunoNecessidade->excluir(null, "ed310_rechumano = {$this->iCodigoDocente}");
    if (isset($oLinha->docente_deficiencia) && $oLinha->docente_deficiencia == 1) {

      trim($oLinha->tipos_deficiencia_cegueira)      == 1 ? $aNecessidade[] = 101 : '';
      trim($oLinha->tipos_deficiencia_baixa_visao)   == 1 ? $aNecessidade[] = 102 : '';
      trim($oLinha->tipos_deficiencia_surdez)        == 1 ? $aNecessidade[] = 103 : '';
      trim($oLinha->tipos_deficiencia_auditiva)      == 1 ? $aNecessidade[] = 104 : '';
      trim($oLinha->tipos_deficiencia_surdocegueira) == 1 ? $aNecessidade[] = 105 : '';
      trim($oLinha->tipos_deficiencia_fisica)        == 1 ? $aNecessidade[] = 106 : '';
      trim($oLinha->tipos_deficiencia_intelectual)   == 1 ? $aNecessidade[] = 107 : '';
      trim($oLinha->tipos_deficiencia_multipla)      == 1 ? $aNecessidade[] = 108 : '';
      $iTam = count($aNecessidade);

      for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {

        if ($aNecessidade[$iContNecessidade] > 0) {

          $oDaoAlunoNecessidade->ed310_necessidade = $aNecessidade[$iContNecessidade];
          $oDaoAlunoNecessidade->ed310_rechumano   = $this->iCodigoDocente;
          $oDaoAlunoNecessidade->incluir(null);

          if ($oDaoAlunoNecessidade->erro_status == '0') {

            throw new Exception("Erro na inclusão das necessidades do recurso humano. Erro da classe: ".
                                $oDaoAlunoNecessidade->erro_msg
                               );

          }
        }
      }
    }
  }

  /**
   * Funcao que seleciona o codigo do pais para utilizarmos na inclusao do aluno e do docente (RecHumano)
   *
   * @param integer $iCodPaisCenso
   */
  protected function getPais($iCodPaisCenso) {

    $oDaoPais    = db_utils::getdao('pais');
    $sWhere      = "ed228_i_paisonu = {$iCodPaisCenso}";
    $sSqlPais    = $oDaoPais->sql_query_file("", "ed228_i_codigo", "", $sWhere);
    $rsPais      = $oDaoPais->sql_record($sSqlPais);
    $iCodigoPais = 10;

    if ($oDaoPais->numrows > 0) {
      $iCodigoPais = db_utils::fieldsmemory($rsPais, 0)->ed228_i_codigo;
    }

    return $iCodigoPais;
  }
}
