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
 * Caso
 *
 */
class DadosCensoDocente extends DadosCenso {
  
  protected $iCodigoDocente;
  
  protected $iCodigoInep;
  
  protected $oDadosGerais;
  
  protected $iCodigoEscola;
  
  protected $iAnoCenso;
  
  /**
   * Codigo do cgm do docente
   */
  protected $iCodigoCgm;
  /**
   * 
   */
  function __construct($iCodigoDocente) {

    $this->iCodigoDocente = $iCodigoDocente;
    
  }
  
  /**
   * Retorna os dados básicos do Recurso humano
   * @return Object
   */
  protected function getDados() {
    
    $oDaoRecursoHumano = db_utils::getDao("rechumano");
    $sCampos           = "ed20_i_codigoinep as identificacao_unica_docente_inep, ";
    $sCampos           .= "ed20_i_codigo as codigo_docente_entidade_escola, ";
    $sCampos           .= "case when cgmrh.z01_nome is not null then cgmrh.z01_nome else cgmcgm.z01_nome end as nome_completo, ";
    $sCampos           .= "case when cgmrh.z01_numcgm is not null then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end as numcgm, ";
    $sCampos           .= "case when cgmrh.z01_email is not null then upper(cgmrh.z01_email) else upper(cgmcgm.z01_email) end as email, ";
    $sCampos           .= "case when cgmrh.z01_nasc is not null then cgmrh.z01_nasc else cgmcgm.z01_nasc end as data_nascimento, ";
    $sCampos           .= "case when cgmrh.z01_sexo is not null then cgmrh.z01_sexo else cgmcgm.z01_sexo end as sexo, ";
    $sCampos           .= "case when cgmrh.z01_mae is not null then cgmrh.z01_mae else cgmcgm.z01_mae end as nome_completo_mae, ";
    $sCampos           .= "ed20_i_nacionalidade as nacionalidade_docente,";
    $sCampos           .= "ed228_i_paisonu as pais_origem,";
    $sCampos           .= "ed20_i_censoufnat as uf_nascimento,";
    $sCampos           .= "ed20_i_censomunicnat as municipio_nascimento,";
    $sCampos           .= "ed20_i_raca as cor_raca,";
    $sCampos           .= "ed20_c_nis as numero_identificacao_social_inss, ";
    $sCampos           .= "0 as docente_deficiencia, ";
    $sCampos           .= "case when regimerh.rh30_naturezaregime is not null then  regimerh.rh30_naturezaregime ";
    $sCampos           .= "     else regimecgm.rh30_naturezaregime end as regime_trabalho,";
    $sCampos           .= 'ed20_i_escolaridade as escolaridade';
    
    $sWhereCgm  = " ed20_i_codigo = {$this->getCodigoDocente()} ";
    $sWhereCgm .= " AND ed01_c_regencia = 'S'";

    $sSqlDadosRecurso  = $oDaoRecursoHumano->sql_query_censo(null, $sCampos, null, $sWhereCgm);
    $rsDadoCenso       = $oDaoRecursoHumano->sql_record($sSqlDadosRecurso);
    if ($oDaoRecursoHumano->numrows == 0) {
      throw new Exception("Não foram encontrados recursos humanos.");     
    }
     
    $oDadosRecursoHumano       = db_utils::fieldsMemory($rsDadoCenso, 0);
    
    $this->iCodigoCgm          = $oDadosRecursoHumano->numcgm;
    $oDadosRecursoHumano->sexo = $oDadosRecursoHumano->sexo == 'F'?2:1;
    $this->iCodigoInep         = $oDadosRecursoHumano->identificacao_unica_docente_inep;
    $aNecessidadesEspeciais    = $this->getNecessidadesEspeciais();
    if (count($aNecessidadesEspeciais) > 0) {
      $oDadosRecursoHumano->docente_deficiencia = 1;
    }
    $iDocenteNecessidade = '';
    if (count($aNecessidadesEspeciais) > 0) {
      $iDocenteNecessidade  = '0';
    }
    
    $oDadosRecursoHumano->data_nascimento                  = db_formatar($oDadosRecursoHumano->data_nascimento, "d");
    $oDadosRecursoHumano->nome_completo_mae                = $this->removeCaracteres($oDadosRecursoHumano->nome_completo_mae, 1);
    $oDadosRecursoHumano->numero_identificacao_social_inss = '';
    $oDadosRecursoHumano->tipos_deficiencia_cegueira       = isset($aNecessidadesEspeciais[101])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_baixa_visao    = isset($aNecessidadesEspeciais[102])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdez         = isset($aNecessidadesEspeciais[103])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_auditiva       = isset($aNecessidadesEspeciais[104])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_surdocegueira  = isset($aNecessidadesEspeciais[105])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_fisica         = isset($aNecessidadesEspeciais[106])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_intelectual    = isset($aNecessidadesEspeciais[107])?1:$iDocenteNecessidade;
    $oDadosRecursoHumano->tipos_deficiencia_multipla       = isset($aNecessidadesEspeciais[108])?1:$iDocenteNecessidade;
    $iRegimeTrabalho                                       = 1;
    switch ($oDadosRecursoHumano->regime_trabalho) {
      
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
    $this->oDadosGerais = $oDadosRecursoHumano;
    return $this->oDadosGerais;
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
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }
  
  /**
   * Retorna as necessidades Especiais do Recurso Humano
   */
  public function getNecessidadesEspeciais() {
    
    $oDaoRecHumanoNecessidadeEspecial = db_utils::getDao("rechumanonecessidade");
    $sCampos  = "ed48_i_codigo  as codigo,";
    $sCampos .= " ed48_c_descr  as necessidade,";
    $sCampos .= " ed310_sequencial  as possui";
    
    $sWhere = "ed48_i_codigo <> 108"; //não lista registro de Necessidade Multipla
    
    $sSqlNecessidades        = $oDaoRecHumanoNecessidadeEspecial->sql_query_necessidade($this->getCodigoDocente(),
                                                                                        $sCampos,
                                                                                        $sWhere);
    $rsNecessidades    = $oDaoRecHumanoNecessidadeEspecial->sql_record($sSqlNecessidades);
    $aNecessidades     = array();
    $iQuantidadeNecessidades = 0;
    
    for ($iNecessidade = 0; $iNecessidade < $oDaoRecHumanoNecessidadeEspecial->numrows; $iNecessidade++) {
      
      $oNecessidade = db_utils::fieldsMemory($rsNecessidades, $iNecessidade);
      if ($oNecessidade->possui != "") {
         
        unset($oNecessidade->ed310_sequencial);
        $aNecessidades[$oNecessidade->codigo] = $oNecessidade;
        $iQuantidadeNecessidades++;
      }
    }
    
    if ($iQuantidadeNecessidades > 1) {
      
      /**
       * identifica que existe necessidade multipla
       */
      $aNecessidades[108] = 't';
    }
    
    return $aNecessidades;
  }

  /**
   * Retorna o codigo inep do Docente
   */
  public function getCodigoInep() {
    return $this->iCodigoInep;
  }
  /**
   * Retorna os dados de endereço e documentos do Docente;.
   * Cria os dados necessários para a geracao do registro 30 do censo.
   * @return Objeto 
   */
  public function getEnderecoDocumento() {
    
    $oDaoRecursoHumano  = db_utils::getDao("rechumano");
    $sCampos            = "case when cgmrh.z01_cgccpf is not null then cgmrh.z01_cgccpf "; 
    $sCampos           .= "     else cgmcgm.z01_cgccpf end as numero_cpf, ";
    $sCampos           .= "case when cgmrh.z01_cep is not null then cgmrh.z01_cep else cgmcgm.z01_cep end as cep, ";
    $sCampos           .= "case when cgmrh.z01_ender is not null then cgmrh.z01_ender else cgmcgm.z01_ender end as endereco, ";
    $sCampos           .= "case when cgmrh.z01_numero is not null ";
    $sCampos           .= "     then cgmrh.z01_numero else cgmcgm.z01_numero end as numero_endereco, ";
    $sCampos           .= "case when cgmrh.z01_compl is not null ";
    $sCampos           .= "     then cgmrh.z01_compl else cgmcgm.z01_compl end as complemento, ";
    $sCampos           .= "case when cgmrh.z01_bairro is not null ";
    $sCampos           .= "     then cgmrh.z01_bairro else cgmcgm.z01_bairro end as bairro, ";
    $sCampos           .= "ed20_i_zonaresidencia as localizacao_zona_residencia, ";
    $sCampos           .= "ed20_i_censoufender as uf, ";
    $sCampos           .= " ed20_i_censomunicender as municipio";
    
    $sWhereCgm  = " ed20_i_codigo = {$this->getCodigoDocente()} ";
    $sWhereCgm .= " AND ed01_c_regencia = 'S'";
    
    $sSqlDadosRecurso  = $oDaoRecursoHumano->sql_query_censo(null, $sCampos, null, $sWhereCgm);
    $rsRecurso         = $oDaoRecursoHumano->sql_record($sSqlDadosRecurso);
    if ($oDaoRecursoHumano->numrows == 0) {
      throw new Exception('Docente sem documentos lancados');
    }
    $oDadosDocumentoDocente = db_utils::fieldsMemory($rsRecurso, 0);
    $oDadosDocumentoDocente->endereco 			 									= $this->removeCaracteres($oDadosDocumentoDocente->endereco, 				3); 
    $oDadosDocumentoDocente->numero_endereco                  = $this->removeCaracteres($oDadosDocumentoDocente->numero_endereco, 3);
    $oDadosDocumentoDocente->complemento                  		= $this->removeCaracteres($oDadosDocumentoDocente->complemento, 		3);
    $oDadosDocumentoDocente->bairro                  					= $this->removeCaracteres($oDadosDocumentoDocente->bairro, 					3);
    $oDadosDocumentoDocente->identificacao_unica_docente_inep = $this->iCodigoInep;
    $oDadosDocumentoDocente->codigo_docente_entidade_escola   = $this->getCodigoDocente();
    
    return $oDadosDocumentoDocente;
  }
  
  /**
   * Retorna os dados da Formação do Recurso Humano
   */
  public function getDadosFormacao() {
    
    $oDadosIdentificacao = $this->getDadosIdentificacao();     
    $oDaoFormacao        = db_utils::getDao("formacao");
    $sWhereFormacao      = "ed27_i_rechumano = {$this->getCodigoDocente()}";
    $sWhereFormacao     .= " and ed27_c_situacao <> 'INT' ";
    $sCampos             = "coalesce(ed27_i_formacaopedag, '0') as formacao_pedagogica,";
    $sCampos            .= "case when ed27_c_situacao = 'CON' THEN  1 else 2 end as situacao_curso, ";
    $sCampos            .= "ed27_i_censoinstsuperior as codigo_instituicao,";
    $sCampos            .= "ed94_c_codigocenso as codigo_curso,";
    $sCampos            .= "ed94_i_grauacademico as grau_formacao,";
    $sCampos            .= "ed27_i_anoinicio as ano_inicio,";
    $sCampos            .= "ed27_i_anoconclusao as ano_conclusao,";
    $sCampos            .= "ed257_i_tipo as tipo_instituicao";
    $sSqlDadosFormacao   = $oDaoFormacao->sql_query(null, 
                                                    $sCampos,
                                                    "ed27_i_codigo limit 3", 
                                                    $sWhereFormacao
                                                   );
    $rsDadosFormacao  = $oDaoFormacao->sql_record($sSqlDadosFormacao);
    $aFormacoes       = db_utils::getCollectionByRecord($rsDadosFormacao);                                                
    $oDadosFormacao   = new stdClass();
    $oDadosFormacao->escolaridade = $oDadosIdentificacao->escolaridade;
    
    $iTotalCursos     = 3;
    $iTotalSuperior   = 0;
    $lEstaEmAndamento = false;
    
    for ($iFormacao = 0; $iFormacao < $iTotalCursos; $iFormacao++) {
      
      $iSequencialCurso    = $iFormacao + 1;
      $iSituacaoCurso      = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->situacao_curso : "";
      $iFormacaoPedagogica = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->formacao_pedagogica :"";
      $iCodigoCurso        = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->codigo_curso : "";
      $iAnoInicio          = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->ano_inicio : "";
      $iAnoConclusao       = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->ano_conclusao : "";
      $iTipoInstituicao    = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->tipo_instituicao : "";
      $iCodigoInstituicao  = isset($aFormacoes[$iFormacao]) ? $aFormacoes[$iFormacao]->codigo_instituicao : "";
      
      if (isset($aFormacoes[$iFormacao]) && $aFormacoes[$iFormacao]->grau_formacao == 3) {
        $iFormacaoPedagogica = '';
      }
      
      /**
       * Validamos se existe o curso superior, verificando o codigo. Caso existe, incrementamos a variaval iTotalSuperior,
       * e em seguida verificamos se a situacao do curso eh 2 - Em Andamento
       */
      if (!empty($iSituacaoCurso)) {
        
        $iTotalSuperior++;
        if ($iSituacaoCurso == 2) {
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
      
      if($lEstaEmAndamento){
      	$oDadosFormacao->{"formacao_complementacao_pedagogica_{$iSequencialCurso}"} = '';	
      }
    }
    
    foreach ($this->getDadosOutrosCursos() as $oRespostas) {
      $oDadosFormacao->{$oRespostas->campo} = $oRespostas->resposta;
    }
    
    /**
     * Caso a escolaridade seja diferente de 6 - Superior, ou seja Superior, esteja em Andamento e seja o unico superior,
     * os campos de Pos-Graduacao ficam vazio
     */
    if ($oDadosFormacao->escolaridade != 6 || ($oDadosFormacao->escolaridade == 6 && $iTotalSuperior == 1 && $lEstaEmAndamento)) {

      $oDadosFormacao->pos_graduacao                = "";
      $oDadosFormacao->pos_graduacao_doutorado      = "";
      $oDadosFormacao->pos_graduacao_mestrado       = "";
      $oDadosFormacao->pos_graduacao_especializacao = "";
    }
    
    $oDadosFormacao->codigo_docente_entidade_escola = $oDadosIdentificacao->codigo_docente_entidade_escola;
    $oDadosFormacao->identificacao_unica_docente    = $oDadosIdentificacao->identificacao_unica_docente_inep;
    
    return $oDadosFormacao;
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
   * Retorna os outros cursos do docente.
   */
  protected function getDadosOutrosCursos() {

    /**
     * Procuramos o codigo da avaliacao da escola.
     */
    $oDaoRecHumanoDadosCenso = db_utils::getDao("rechumanodadoscenso");
    $sSqlCodigoAvaliacao     = $oDaoRecHumanoDadosCenso->sql_query_file(null, 
                                                                  "ed309_avaliacaogruporesposta",
                                                                  null, 
                                                                  "ed309_rechumano = {$this->getCodigoDocente()}"
                                                                 );
                                                                 
    $rsCodigoAvaliacao  = $oDaoRecHumanoDadosCenso->sql_record($sSqlCodigoAvaliacao);
    if ($oDaoRecHumanoDadosCenso->numrows == 0) {
      
       $sSqlResposta = " '' "; 
    } else {
      
      $iCodigoAvaliacao  = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed309_avaliacaogruporesposta;
      $sSqlResposta      = "(select case when trim(db106_resposta) != '' then db106_resposta else '1' end ";
      $sSqlResposta     .= "   from avaliacaogrupoperguntaresposta ";
      $sSqlResposta     .= "        inner join avaliacaoresposta on db108_avaliacaoresposta = db106_sequencial ";
      $sSqlResposta     .= "  where db106_avaliacaoperguntaopcao = db104_sequencial ";
      $sSqlResposta     .= "   and db108_avaliacaogruporesposta  = {$iCodigoAvaliacao})";  
    }
    
    $oDaoPerguntas = db_utils::getDao("avaliacaoperguntaopcaolayoutcampo");
    $sSqlPerguntas = $oDaoPerguntas->sql_query_avaliacao(null, 
                                                     "db52_nome as campo, coalesce({$sSqlResposta}, '0') as resposta", 
                                                     null,
                                                     "db102_avaliacao = 3000002"
                                                     );
    $rsPerguntas = $oDaoPerguntas->sql_record($sSqlPerguntas);
    return db_utils::getCollectionByRecord($rsPerguntas);                            
  }

  /**
   * Retorna os dados de docencia do Recurso Humano.
   * Os dados retornados se referem ao Registro 51 do censo;
   */
  public function getDadosDocencia() {
    
    $oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
    $aDadosDocencia      = array();
    $oDadosDocente       = $this->getDadosIdentificacao();
    
    $sWhere   = " (cgmcgm.z01_numcgm = {$this->getCodigoCgm()} or cgmrh.z01_numcgm = {$this->getCodigoCgm()})";
    $sWhere  .= " and ed52_i_ano    = {$this->getAnoCenso()}";                
    $sWhere  .= " and ed57_i_escola = {$this->getCodigoEscola()}";
    $sCampos  = "distinct  on(ed57_i_codigo) ed57_i_codigo, ";
    $sCampos .= "                            ed57_i_codigoinep, ";
    $sCampos .= "                            1 as tipoturma, ";
    $sCampos .= "                            ed57_i_tipoturma,";
    $sCampos .= "                            ed57_i_censoetapa";
    
    $sSqlTurmasRecursoHumano = $oDaoRegenciaHorario->sql_query(null, $sCampos, "ed57_i_codigo", $sWhere);
    $rsDadosDocente          = $oDaoRegenciaHorario->sql_record($sSqlTurmasRecursoHumano);
    $iTotalLinhas            = $oDaoRegenciaHorario->numrows; 
    for ($iDocente = 0; $iDocente < $iTotalLinhas; $iDocente++) {
      
      $oDocente                                               = db_utils::fieldsMemory($rsDadosDocente, $iDocente);
      $oDadosDocencia                                         = new stdClass();
      $oDadosDocencia->tipo_registro                          = 51;  
      $oDadosDocencia->identificacao_unica_inep               = $oDadosDocente->identificacao_unica_docente_inep;
      $oDadosDocencia->codigo_docente_entidade_escola         = $this->getCodigoDocente();
      $oDadosDocencia->codigo_turma_inep                      = $oDocente->ed57_i_codigoinep;
      $oDadosDocencia->codigo_turma_entidade_escola           = $oDocente->ed57_i_codigo;
      $oDadosDocencia->funcao_exerce_escola_turma             = 1;
      $oDadosDocencia->situacao_funcional_contratacao_vinculo = $oDadosDocente->regime_trabalho;
      $iTotalLinhasDisciplinas = 0;
      $aDisciplinasDocente     = $this->getDisciplinasNaTurma($oDocente->ed57_i_codigo);
      for ($iDisciplina = 0; $iDisciplina < 13; $iDisciplina++) {
        
        $iSequencialDiscplina = $iDisciplina + 1;
        $iCodigoDisciplina    = "";
        
        $aEtapasCensoTurma = array(1, 2, 3, 65, 66);
        if ( !in_array($oDocente->ed57_i_censoetapa, $aEtapasCensoTurma) && ($oDocente->ed57_i_tipoturma != 4 && $oDocente->ed57_i_tipoturma != 5) ) {
        	        
	        if (isset($aDisciplinasDocente[$iDisciplina])) {
	        	
	          $iCodigoDisciplina = $aDisciplinasDocente[$iDisciplina]->getDisciplina();
	        }
        }
        $oDadosDocencia->{"codigo_disciplina_{$iSequencialDiscplina}"} = $iCodigoDisciplina;
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
   * Define o codigo da escola 
   * @param integer $iCodigoEscola Código da escola.
   */
  public function setCodigoEscola($iCodigoEscola) {
    $this->iCodigoEscola = $iCodigoEscola;
  }
  
  /**
   * Retorna o codigo da escola
   * @return $iCodigoEscola;
   */
  public function getCodigoEscola() {
    return $this->iCodigoEscola;
  }
  
  /**
   * Define o ano do censo
   */
  public function setAnoCenso($iAnoCenso) {
    $this->iAnoCenso = $iAnoCenso;
  }
  
  /**
   * Retorna o Ano do censo
   */
  public function getAnoCenso() {
    return $this->iAnoCenso; 
  }
  
  /**
   * Retorna os dados das docencias em turmas de Atendimento especializado
   */
  public function getDadosDocenciaTurmaAEE() {
    
    $oDadosDocente      = $this->getDadosIdentificacao();
    $aHorarios          = array();
    $oDaoTurmaACHorario = db_utils::getDao("turmaachorario");
    $sWhere             = " ed270_i_rechumano   = {$this->getCodigoDocente()}";
    $sWhere            .= " and ed52_i_ano     = {$this->getAnoCenso()}";
    $sWhere            .= " and ed268_i_escola = {$this->getCodigoEscola()}";
    $sCampos            = " DISTINCT on(ed268_i_codigo)ed268_i_codigo, ed268_i_codigoinep ";
    $sSqlHorario        = $oDaoTurmaACHorario->sql_query_rechumano(null, $sCampos, null, $sWhere);
    $rsHorario          = $oDaoTurmaACHorario->sql_record($sSqlHorario);
    $iTotalHorarios     = $oDaoTurmaACHorario->numrows;
    
    for ($iDocente = 0; $iDocente < $iTotalHorarios; $iDocente++) {
      
      $oDocente                                               = db_utils::fieldsMemory($rsHorario, $iDocente);
      $oDadosDocencia                                         = new stdClass();
      $oDadosDocencia->tipo_registro                          = 51;  
      $oDadosDocencia->identificacao_unica_inep               = $oDadosDocente->identificacao_unica_docente_inep;
      $oDadosDocencia->codigo_docente_entidade_escola         = $this->getCodigoDocente();
      $oDadosDocencia->codigo_turma_inep                      = $oDocente->ed268_i_codigoinep;
      $oDadosDocencia->codigo_turma_entidade_escola           = $oDocente->ed268_i_codigo;
      $oDadosDocencia->funcao_exerce_escola_turma             = 1;
      $oDadosDocencia->situacao_funcional_contratacao_vinculo = $oDadosDocente->regime_trabalho;
      $iTotalLinhasDisciplinas = 0;
      for ($iDisciplina = 0; $iDisciplina < 13; $iDisciplina++) {
        
        $iSequencialDiscplina = $iDisciplina + 1;
        $oDadosDocencia->{"codigo_disciplina_{$iSequencialDiscplina}"} = '';
      }
      $aHorarios[] = $oDadosDocencia;
    }
    return $aHorarios;
  }
  /**
   * Retorna as Disciplinas que o Docente leciona na turma .
   * @return array com DisciplinaCenso
   */
  public function getDisciplinasNaTurma($iTurma) {
    
    $oDaoDisciplinas   = db_utils::getDao("regenciahorario");
    $sWhere            = " ed57_i_Codigo    = {$iTurma} ";
    $sWhere           .= " and  (cgmcgm.z01_numcgm = {$this->getCodigoCgm()} or cgmrh.z01_numcgm = {$this->getCodigoCgm()})";
    $sWhere           .= " and ed58_ativo is true ";
    $sCampos           = "distinct ed265_i_codigo as codigo,"; 
    $sCampos          .= "         ed265_c_descr   as descricao";
    $sSqlDisciplinas   = $oDaoDisciplinas->sql_query_disciplina_regencia_censo(null, $sCampos, null, $sWhere);
    $rsDisciplinas     = $oDaoDisciplinas->sql_record($sSqlDisciplinas);
    //echo $sSqlDisciplinas."\n\n";
    
    $iTotalDisciplinas = $oDaoDisciplinas->numrows;
    $aDisciplinas      = array();
    for ($iDisciplina = 0; $iDisciplina < $iTotalDisciplinas; $iDisciplina++) {

      $oDisciplina      = db_utils::fieldsMemory($rsDisciplinas, $iDisciplina);
      $oDisciplinaCenso = new DisciplinaCenso();
      $oDisciplinaCenso->setNome($oDisciplina->descricao);
      $oDisciplinaCenso->setDisciplina($oDisciplina->codigo);
      $aDisciplinas[]  = $oDisciplinaCenso; 
    }
    return $aDisciplinas;
  }
  
  /**
   * Valida os dados do arquivo
   * @param $oExportacaoCenso instancia da Importacao do censo
   * @return boolean 
   */
  public function validarDados(ExportacaoCenso2013 $oExportacaoCenso) {
  
    $lDadosValidos = true;
    $oDadosDocente = $oExportacaoCenso->getDadosProcessadosDocente();
    
    foreach ($oDadosDocente as $oDadosCensoDocente) {

    	if (strpos($oDadosCensoDocente->registro30->nome_completo, '  ')) {
    	
    		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
    		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
    		$sMsgErro     .= "O nome deve conter apenas espaços simples.";
    		$oExportacaoCenso->logErro($sMsgErro);
    		$lDadosValidos = false;
    	}
    	
      if ($oDadosCensoDocente->registro30->data_nascimento == '') {

     	    $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Deve ser informado a data de nascimento do Docente";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
      }
        
      /**
       * Validações do registro 30 do Layout do Censo
       */
      if ($oDadosCensoDocente->registro30->tipos_deficiencia_intelectual == 1) {

        if ($oDadosCensoDocente->registro30->tipos_deficiencia_multipla == 1) {

          $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Deve ser selecionada apenas uma das opções: Deficiência Intelectual ou Deficiência Múltipla.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if (!empty($oDadosCensoDocente->registro30->nome_completo_mae)) {
      
      	if(!DBString::isSomenteLetras($oDadosCensoDocente->registro30->nome_completo_mae)){
      
      		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
      		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro 		.= "O nome da mãe possui caracteres inválidos, deve ser informado apenas letras.".$oDadosCensoDocente->registro30->nome_completo_mae;
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      	
      	if(strpos($oDadosCensoDocente->registro30->nome_completo_mae, '  ')){
      	
      		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
      		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro 		.= "O nome da mãe deve conter apenas espaços simples.";
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      }
      
      if (!empty($oDadosCensoDocente->registro30->email)) {
      
      	if(!DBString::isEmail(($oDadosCensoDocente->registro30->email))){
      
      		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
      		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
      		$sMsgErro 		.= "O e-mail informado não é válido.";
      		$oExportacaoCenso->logErro($sMsgErro);
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

        $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Deve ser selecionada apenas uma dentre os seguintes tipos de deficiência: Cegueira, Baixa Visão,";
        $sMsgErro     .= " Surdez ou Surdocegueira.";
        $oExportacaoCenso->logErro($sMsgErro);
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

        $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Deve ser selecionada apenas uma dentre os seguintes tipos de deficiência: Cegueira, Surdez,";
        $sMsgErro     .= " Auditiva ou Surdocegueira.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }

      if ($oDadosCensoDocente->registro30->tipos_deficiencia_surdocegueira == 1 ) {

        if ($oDadosCensoDocente->registro30->tipos_deficiencia_cegueira    == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_baixa_visao == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_surdez      == 1 ||
            $oDadosCensoDocente->registro30->tipos_deficiencia_auditiva    == 1) {

          $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Ao selecionadar a opção Deficiência Surdocegueira, ";
          $sMsgErro     .= "os outros tipos de deficiência devem ser desmarcados.";
          $oExportacaoCenso->logErro($sMsgErro);
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

          $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Tipo de Deficiência Múltipla é obrigatória.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      /**
       * Validações do registro 40 do Layout do Censo
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

        $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Os campos CEP, Endereço, UF e Município devem ser preenchidos.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }

      if ($oDadosCensoDocente->registro40->localizacao_zona_residencia == 0 ||
          $oDadosCensoDocente->registro40->localizacao_zona_residencia == '') {
        
        $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Informe a zona de Residencia do Docente.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }
      
      /**
       * Validações do registro 50 do Layout do Censo
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
            
              $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
              $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
              $sMsgErro     .= "Docente possui apenas uma graduação, a qual está em andamento. Logo, não deve ser";
              $sMsgErro     .= " selecionada nenhuma opção referente a Pós-Graduação.";
              $oExportacaoCenso->logErro($sMsgErro);
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
          		
          		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          		$sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos referentes a Pós-Graduação";
          		$sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          		$oExportacaoCenso->logErro($sMsgErro);
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
          	
          		$sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          		$sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          		$sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos";
          		$sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          		$oExportacaoCenso->logErro($sMsgErro);
          		$lDadosValidos = false;
          	}
          	
          }
          
        }
        
        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1         == "" ||
            $oDadosCensoDocente->registro50->codigo_curso_superior_1           == "" ||
            $oDadosCensoDocente->registro50->tipo_instituicao_curso_superior_1 == "" ||
            $oDadosCensoDocente->registro50->instituicao_curso_superior_1      == "") {

          $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "Os seguintes campos devem ser preenchidos: Situação do Curso Superior 1, Código do Curso Superior 1,";
          $sMsgErro     .= " Tipo de Instituição do Curso Superior 1 e Instituição do Curso Superior 1.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }

        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 1) {

          if ($oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 != "") {
            
            $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Formação marcada como concluída. Não deve ser indicada a data de início.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oDadosCensoDocente->registro50->ano_conclusao_curso_superior_1 == "") {
            
            $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Ano de conclusao do Curso Superior 1 deve ser informado.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }
        }

        if ($oDadosCensoDocente->registro50->situacao_curso_superior_1 == 2) {

          if ($oDadosCensoDocente->registro50->ano_conclusao_curso_superior_1 != "") {
            
            $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "Formação em andamento. Ano de Conclusão do Curso não pode ser informada.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 == "") {
            
            $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "formação em andamento. Deve ser informado o ano de início do curso superior.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if (($this->getAnoCenso()-$oDadosCensoDocente->registro50->ano_inicio_curso_superior_1) > 11 &&
          		$oDadosCensoDocente->registro50->ano_inicio_curso_superior_1 != "") {
            
            $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
            $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
            $sMsgErro     .= "O intervalor entre o ano de início do curso superior e o Ano Base do Censo é superior a 11 anos.";
            $oExportacaoCenso->logErro($sMsgErro);
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
          
          $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
          $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
          $sMsgErro     .= "É necessário selecionar ao menos uma opção entre as existentes para Outros Cursos";
          $sMsgErro     .= " (Recursos Humanos -> Formação -> Outros Dados -> Outros Cursos)";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
        
      }
      
      if (count($oDadosCensoDocente->registro51) == 0) {
        
        $sDadosDocente = $oDadosCensoDocente->registro30->numcgm." - ".$oDadosCensoDocente->registro30->nome_completo." - Data de Nascimento: ".$oDadosCensoDocente->registro30->data_nascimento;
        $sMsgErro      = "Docente CGM {$sDadosDocente}: \n";
        $sMsgErro     .= "Docente não possui vínculo com turmas.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
        
      }
    }
    
    return $lDadosValidos;
  }
  
  public function atualizarDados ($oLinha, $oDados) {
    
                      
    $oDaoRechumano              = db_utils::getdao('rechumano');      
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

    $oDaoRechumano              = db_utils::getdao('rechumano');        
    
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

   $oDaoRechumano = db_utils::getDao("rechumano"); 
   if (isset($oLinha->escolaridade) && !empty($oLinha->escolaridade)) { 
      $oDaoRechumano->ed20_i_escolaridade = $oLinha->escolaridade;         
    }
    
    $oDaoRechumano->ed20_i_codigo = $this->iCodigoDocente; 
    $oDaoRechumano->alterar($oDaoRechumano->ed20_i_codigo);
    if ($oDaoRechumano->erro_status == '0') {          
      throw new Exception("Erro na alteração dos dados de endereço do Recurso Humano. Erro da classe: ".$oDaoRechumano->erro_msg);        
    }  
    $oDaoCursoFormacao   = db_utils::getdao('cursoformacao');          
    $oDaoFormacao        = db_utils::getdao('formacao'); 
    $oDaoFormacao->excluir(null,"ed27_i_rechumano = {$this->iCodigoDocente}");
    $aFormacao = array();
    for ($i = 1; $i <= 3; $i++) {   
       
      if (isset($oLinha->{"situacao_curso_superior_$i"}) && isset($oLinha->{"formacao_complementacao_pedagogica_$i"}) 
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
    
    foreach ($aFormacao as $iFormacao => $aFormacaoDecente)  {
      
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
   * @return Avaliacao
   */
  public function getAvaliacacaoEscolaridade() {
    
    $oDaoAvaliacaoRecursoHumano = db_utils::getDao("rechumanodadoscenso");
    $sSqlCodigoAvaliacao     = $oDaoAvaliacaoRecursoHumano->sql_query_file(null, 
                                                                  "ed309_avaliacaogruporesposta",
                                                                  null, 
                                                                  "ed309_rechumano = {$this->getCodigoDocente()}"
                                                                 ); 
    $rsCodigoAvaliacao = $oDaoAvaliacaoRecursoHumano->sql_record($sSqlCodigoAvaliacao);
    if ($oDaoAvaliacaoRecursoHumano->numrows == 0) {
                                                                      
      $iCodigoAvaliacao = db_utils::fieldsMemory($rsCodigoAvaliacao, 0)->ed309_avaliacaogruporesposta;
    } else {
      
      $oDaoAvaliacaoGrupoPergunta = db_utils::getDao("avaliacaogruporesposta");
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
    
    $oDaoAlunoNecessidade = db_utils::getdao('rechumanonecessidade');
    $oDaoAlunoNecessidade->excluir(null, "ed310_rechumano = {$this->iCodigoDocente}");
    if (isset($oLinha->docente_deficiencia) && 
        $oLinha->docente_deficiencia == 1) { 

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
}
?>