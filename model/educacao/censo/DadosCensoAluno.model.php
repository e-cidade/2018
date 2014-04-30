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


class DadosCensoAluno extends DadosCenso {

  protected $iCodigoAluno;

  protected $oDadosAluno;

  protected $oDadosDocumento;

  protected $oDadosEndereco;

  protected $aDadosMatricula;

  protected $sDataCenso;

  protected $iAnoCenso;

  protected $iEscola;
  /**
   *
   */
  function __construct($iAluno, $iEscola) {

    $this->iCodigoAluno = $iAluno;
    $this->iEscola      = $iEscola;
  }

  protected function getDados() {

    $oDaoMatricula    = new cl_matricula();
    $sCamposAluno     = " trim(aluno.ed47_c_codigoinep) as ed47_c_codigoinep,  ";
    $sCamposAluno    .= " aluno.ed47_i_codigo,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_nome) as ed47_v_nome,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_nis) as ed47_c_nis,  ";
    $sCamposAluno    .= " aluno.ed47_d_nasc,  ";
    $sCamposAluno    .= " aluno.ed47_v_sexo,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_raca) as ed47_c_raca,  ";
    $sCamposAluno    .= " aluno.ed47_i_filiacao,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_mae) as ed47_v_mae,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_pai) as ed47_v_pai,  ";
    $sCamposAluno    .= " aluno.ed47_i_nacion, ";
    $sCamposAluno    .= " pais.ed228_i_paisonu,  ";
    $sCamposAluno    .= " aluno.ed47_i_censoufnat,  ";
    $sCamposAluno    .= " aluno.ed47_i_censomunicnat,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_ident) as ed47_v_ident,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_identcompl) as ed47_v_identcompl,  ";
    $sCamposAluno    .= " aluno.ed47_i_censoorgemissrg,  ";
    $sCamposAluno    .= " aluno.ed47_i_censoufident, ";
    $sCamposAluno    .= " aluno.ed47_d_identdtexp,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_certidaotipo) as ed47_c_certidaotipo, ";
    $sCamposAluno    .= " trim(aluno.ed47_c_certidaonum) as ed47_c_certidaonum,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_certidaofolha) as ed47_c_certidaofolha,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_certidaolivro) as ed47_c_certidaolivro, ";
    $sCamposAluno    .= " trim(aluno.ed47_c_certidaodata::varchar) as ed47_c_certidaodata,  ";
    $sCamposAluno    .= " trim(censocartorio.ed291_i_codigocenso::varchar) as ed47_i_censocartorio,  ";
    $sCamposAluno    .= " trim(ed47_certidaomatricula) as ed47_certidaomatricula,";
    $sCamposAluno    .= " aluno.ed47_i_censoufcert,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_cpf) as ed47_v_cpf,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_passaporte) as ed47_c_passaporte,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_cep) as ed47_v_cep,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_ender) as ed47_v_ender,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_numero) as ed47_c_numero,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_compl) as ed47_v_compl,  ";
    $sCamposAluno    .= " trim(aluno.ed47_v_bairro) as ed47_v_bairro,  ";
    $sCamposAluno    .= " aluno.ed47_i_censoufend,  ";
    $sCamposAluno    .= " aluno.ed47_i_censomunicend,  ";
    $sCamposAluno    .= " ed47_i_censomuniccert,";
    $sCamposAluno    .= " trim(aluno.ed47_c_atenddifer) as ed47_c_atenddifer,  ";
    $sCamposAluno    .= " aluno.ed47_i_transpublico,  ";
    $sCamposAluno    .= " trim(aluno.ed47_c_transporte) as ed47_c_transporte,  ";
    $sCamposAluno    .= " case when trim(aluno.ed47_c_zona) = 'RURAL' then 2 else 1 end  as ed47_c_zona,  ";
    $sCamposAluno    .= " aluno.ed47_situacaodocumentacao as ed47_situacaodocumentacao,  ";
    $sCamposAluno    .= " matricula.ed60_i_turma,  ";
    $sCamposAluno    .= " turma.ed57_i_codigoinep,  ";
    $sCamposAluno    .= " turma.ed57_i_codigo,  ";
    $sCamposAluno    .= " serie.ed11_i_codcenso as codcensomatricula,  ";
    $sCamposAluno    .= " turma.ed57_i_censoetapa as ed11_i_codcenso,  ";
    $sCamposAluno    .= " ensino.ed10_i_tipoensino,  ";
    $sCamposAluno    .= " matricula.ed60_i_codigo,  ";
    $sCamposAluno    .= " turnoreferente.ed231_i_referencia as turnoreferente  ";
    $sWhereMatricula  = " turma.ed57_i_escola = {$this->iEscola} ";
    $sWhereMatricula .= "  AND calendario.ed52_i_ano = {$this->iAnoCenso} ";
    $sWhereMatricula .= "  AND aluno.ed47_i_codigo   = {$this->iCodigoAluno} ";
    $sWhereMatricula .= "  AND ed60_d_datamatricula <= '{$this->sDataCenso}' ";
    $sWhereMatricula .= "  AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
    $sWhereMatricula .= "       OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->sDataCenso}'))";
    
    $sSqlMatricula    = $oDaoMatricula->sql_query_censo("", $sCamposAluno, "ed60_i_codigo DESC LIMIT 1",$sWhereMatricula);
    $rsMatricula      = $oDaoMatricula->sql_record($sSqlMatricula);
    $this->setDadosIdenficacao($rsMatricula);
    $this->setDocumentoEndereco($rsMatricula);
    $this->setDadosMatricula($rsMatricula);
    unset($rsMatricula);
  }

  /**
   * Retorna os dados de identificacao do aluno;
   */
  public function getDadosIdentificacao() {

    if (empty($this->oDadosAluno)) {
      $this->getDados();
    }
    return $this->oDadosAluno;
  }

  /**
   * Retorna os dados do registro de tipo 70;
   * @return stdClass;
   */
  public function getDadosEnderecoDocumento() {

    if (empty($this->oDadosDocumento)) {
      $this->getDados();
    }
    return $this->oDadosDocumento;
  }

  /**
   * Retorna os dados do registro de tipo 80;
   * @return stdClass;
   */
  public function getDadosMatricula() {

    if (empty($this->aDadosMatricula)) {
      $this->getDados();
    }
    return $this->aDadosMatricula;
  }

  /**
   * Cria o objeto de Retorno com os dados do Aluno;
   * @return Object;
   */
  protected function setDadosIdenficacao ($rsDadosAluno) {

    $oDadosAluno = db_utils::fieldsMemory($rsDadosAluno, 0);
    $iRacaAluno  = 0;
    switch (trim($oDadosAluno->ed47_c_raca)) {

      case  "BRANCA":
        $iRacaAluno = 1;
         break;

       case "PRETA":

         $iRacaAluno = 2;
         break;

       case "PARDA":

         $iRacaAluno = 3;
         break;

       case 'AMARELA' :

         $iRacaAluno = 4;
         break;

       case 'INDÍGENA' :
         $iRacaAluno = 5;
         break;

       default:

         $iRacaAluno = 0;
         break;
    }
    $iTipoFiliacao = 0;
    if (trim($oDadosAluno->ed47_v_mae) != "" || trim($oDadosAluno->ed47_v_pai) != "") {
      $iTipoFiliacao = 1;
    }
    
    $this->oDadosAluno                               = new stdClass();
    $this->oDadosAluno->tipo_registro                = 60;
    $this->oDadosAluno->identificacao_unica_aluno    = $oDadosAluno->ed47_c_codigoinep;
    $this->oDadosAluno->codigo_aluno_entidade_escola = $oDadosAluno->ed47_i_codigo;
    $this->oDadosAluno->nome_completo                = $this->removeCaracteres($oDadosAluno->ed47_v_nome, 1);
    $this->oDadosAluno->numero_identificacao_social  = $oDadosAluno->ed47_c_nis;
    $this->oDadosAluno->data_nascimento              = db_formatar($oDadosAluno->ed47_d_nasc, "d");
    $this->oDadosAluno->sexo                         = $oDadosAluno->ed47_v_sexo=='M'?1:2;
    $this->oDadosAluno->cor_raca                     = $iRacaAluno;
    $this->oDadosAluno->filiacao                     = $iTipoFiliacao;
    $this->oDadosAluno->nome_mae                     = $this->removeCaracteres($oDadosAluno->ed47_v_mae, 1);
    $this->oDadosAluno->nome_pai                     = $this->removeCaracteres($oDadosAluno->ed47_v_pai, 1);
    $this->oDadosAluno->nacionalidade_aluno          = $oDadosAluno->ed47_i_nacion;
    $this->oDadosAluno->pais_origem                  = $oDadosAluno->ed228_i_paisonu;
    $this->oDadosAluno->uf_nascimento                = $oDadosAluno->ed47_i_censoufnat;
    $this->oDadosAluno->municipio_nascimento         = $oDadosAluno->ed47_i_censomunicnat;
    $this->oDadosAluno->alunos_deficiencia_transtorno_desenv_superdotacao = 0;
    
    $aNecessidades = $this->getDeficiencias();
    $sNecessidades = '';
    
    if (count($aNecessidades) > 0) {
      $this->oDadosAluno->alunos_deficiencia_transtorno_desenv_superdotacao = 1;
      $sNecessidades = '0';
    }
    
    $this->oDadosAluno->tipos_defic_transtorno_cegueira                = isset($aNecessidades[101]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_baixa_visao             = isset($aNecessidades[102]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_surdez                  = isset($aNecessidades[103]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_auditiva                = isset($aNecessidades[104]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_surdocegueira           = isset($aNecessidades[105]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_fisica              = isset($aNecessidades[106]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_intelectual         = isset($aNecessidades[107]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_autismo_infantil    = isset($aNecessidades[109]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_asperger            = isset($aNecessidades[110]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_sindrome_rett       = isset($aNecessidades[111]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_desintegrativo_infancia = isset($aNecessidades[112]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_altas_habilidades       = isset($aNecessidades[113]) ? 1 : $sNecessidades;
    $this->oDadosAluno->tipos_defic_transtorno_def_multipla            = $sNecessidades;
    
    if ( ($this->oDadosAluno->tipos_defic_transtorno_cegueira 		 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica 			== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_cegueira 		 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao   == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica 			== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao   == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_surdez 			 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica 			== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_surdez 			 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_auditiva 		 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica 			== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_auditiva 		 == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_surdocegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_fisica 			== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_surdocegueira == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_cegueira 		 == 1 && $this->oDadosAluno->tipos_defic_transtorno_auditiva 				== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao   == 1 && $this->oDadosAluno->tipos_defic_transtorno_surdez 					== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_baixa_visao   == 1 && $this->oDadosAluno->tipos_defic_transtorno_auditiva 				== 1) ||
         ($this->oDadosAluno->tipos_defic_transtorno_def_fisica    == 1 && $this->oDadosAluno->tipos_defic_transtorno_def_intelectual == 1)
       ) {
      $this->oDadosAluno->tipos_defic_transtorno_def_multipla = 1;
    }
    
    /**
     * Validamos os recursos especiais do aluno
     */
    $aRecursosEspeciais = $this->getRecursosAvaliacao();
    $sRecursosEspeciais = '';
    if (count($aRecursosEspeciais) > 0 || count($aNecessidades) > 0) {
      $sRecursosEspeciais = '0';
    }
    
    $this->oDadosAluno->recurso_auxilio_ledor             = isset($aRecursosEspeciais[101]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_transcricao       = isset($aRecursosEspeciais[102]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_interprete        = isset($aRecursosEspeciais[103]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_interprete_libras = isset($aRecursosEspeciais[104]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_leitura_labial    = isset($aRecursosEspeciais[105]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_prova_ampliada_16 = isset($aRecursosEspeciais[106]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_prova_ampliada_20 = isset($aRecursosEspeciais[107]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_prova_ampliada_24 = isset($aRecursosEspeciais[108]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_prova_braille     = isset($aRecursosEspeciais[109]) ? 1 : $sRecursosEspeciais;
    $this->oDadosAluno->recurso_auxilio_nenhum            = isset($aRecursosEspeciais[110]) ? 1 : $sRecursosEspeciais;
    
    if($this->oDadosAluno->tipos_defic_transtorno_altas_habilidades == 1){
    	
    	if($this->oDadosAluno->tipos_defic_transtorno_cegueira == 0 && $this->oDadosAluno->tipos_defic_transtorno_baixa_visao == 0){
    		
    		$this->oDadosAluno->recurso_auxilio_nenhum = 0;
    	}
    }
    
    return $this->oDadosAluno;
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
   * Retorna o codigo do aluno
   */
  public function getCodigoAluno () {
    return $this->iCodigoAluno;
  }

  /**
   * Define a data do censo
   */
  public function setDataCenso($dtCenso) {
    $this->sDataCenso = $dtCenso;
  }

  /**
   * Retorna as necessidades especiais do aluno
   */
  protected function getDeficiencias() {

    $aNecessidades         = array();
    $oDaoAlunoNecessidades = db_utils::getDao("alunonecessidade");
    $sWhere                = "ed214_i_aluno = {$this->getCodigoAluno()}";
    $sCampos               = "distinct ed48_i_codigo as codigo, ";
    $sCampos              .= "ed48_c_descr  as necessidade ";
    $sSqlNecessidades      = $oDaoAlunoNecessidades->sql_query(null, $sCampos, "ed48_i_codigo", $sWhere);
    $rsNecessidades        = $oDaoAlunoNecessidades->sql_record($sSqlNecessidades);
    $iTotalNecessidades    = $oDaoAlunoNecessidades->numrows;
    for ($iNecessidade = 0; $iNecessidade < $iTotalNecessidades; $iNecessidade++) {

      $oDadosNecessidade = db_utils::fieldsMemory($rsNecessidades, $iNecessidade);
      $aNecessidades[$oDadosNecessidade->codigo] = $oDadosNecessidade;
    }
    return $aNecessidades;
  }

  /**
   * Retorna os Documentos do aluno
   */
  protected function setDocumentoEndereco($rsAluno) {

    $iCertidaoNova   = 1;
    $oDadosDocumento = db_utils::fieldsMemory($rsAluno, 0);
    
    if ($oDadosDocumento->ed47_certidaomatricula != "") {
      $iCertidaoNova = 2;
    } else if ($oDadosDocumento->ed47_situacaodocumentacao != 0) {
      $iCertidaoNova = '';
    } else if ($oDadosDocumento->ed47_situacaodocumentacao == 0 && empty($oDadosDocumento->ed47_c_certidaotipo)) {
    	$iCertidaoNova = '';
    }
    
    $iTipoCertidao = '';
    switch ($oDadosDocumento->ed47_c_certidaotipo) {

      case 'C':

        $iTipoCertidao = 2;
        break;

      case 'N':

        $iTipoCertidao = 1;
        break;
        
      default:
        
        $iTipoCertidao = '';
        break;
    }
    
    /**
     * Caso a certidao for nova, não devemos informar os dados do cartorio;
     */
    if ($iCertidaoNova == 2) {

      $iTipoCertidao                          = '';
      $oDadosDocumento->ed47_c_certidaotipo   = '';
      $oDadosDocumento->ed47_c_certidaonum    = '';
      $oDadosDocumento->ed47_c_certidaofolha  = '';
      $oDadosDocumento->ed47_c_certidaolivro  = '';
      $oDadosDocumento->ed47_c_certidaodata   = '';
      $oDadosDocumento->ed47_i_censoufcert    = '';
      $oDadosDocumento->ed47_i_censomuniccert = '';
      $oDadosDocumento->ed47_i_censocartorio  = '';
    }
    
    if($oDadosDocumento->ed47_i_nacion == 3){
    	$iCertidaoNova													= '';
    	$iTipoCertidao													= '';
    	$oDadosDocumento->ed47_c_certidaonum    = '';
    	$oDadosDocumento->ed47_c_certidaofolha  = '';
    	$oDadosDocumento->ed47_c_certidaolivro  = '';
    	$oDadosDocumento->ed47_c_certidaodata   = '';
    	$oDadosDocumento->ed47_i_censoufcert    = '';
    	$oDadosDocumento->ed47_i_censomuniccert = '';
    	$oDadosDocumento->ed47_i_censocartorio  = '';
    }
    
    $this->oDadosDocumento = new stdClass();
    $this->oDadosDocumento->tipo_registro                    = 70;
    $this->oDadosDocumento->identificacao_unica_aluno        = $oDadosDocumento->ed47_c_codigoinep;
    $this->oDadosDocumento->codigo_aluno_entidade            = $oDadosDocumento->ed47_i_codigo;
    $this->oDadosDocumento->numero_identidade                = $oDadosDocumento->ed47_v_ident;
    $this->oDadosDocumento->complemento_identidade           = $oDadosDocumento->ed47_v_identcompl;
    $this->oDadosDocumento->orgao_emissor_identidade         = $oDadosDocumento->ed47_i_censoorgemissrg;
    $this->oDadosDocumento->uf_identidade                    = $oDadosDocumento->ed47_i_censoufident;
    $this->oDadosDocumento->data_expedicao_identidade        = db_formatar($oDadosDocumento->ed47_d_identdtexp, "d");
    $this->oDadosDocumento->certidao_civil                   = $iCertidaoNova;
    $this->oDadosDocumento->tipo_certidao_civil              = $iTipoCertidao;
    $this->oDadosDocumento->numero_termo                     = $oDadosDocumento->ed47_c_certidaonum;
    $this->oDadosDocumento->folha                            = strtoupper($oDadosDocumento->ed47_c_certidaofolha);
    $this->oDadosDocumento->livro                            = strtoupper($oDadosDocumento->ed47_c_certidaolivro);
    $this->oDadosDocumento->data_emissao_certidao            = db_formatar($oDadosDocumento->ed47_c_certidaodata, "d");
    $this->oDadosDocumento->uf_cartorio                      = $oDadosDocumento->ed47_i_censoufcert;
    $this->oDadosDocumento->municipio_cartorio               = $oDadosDocumento->ed47_i_censomuniccert;
    $this->oDadosDocumento->codigo_cartorio                  = $oDadosDocumento->ed47_i_censocartorio;
    $this->oDadosDocumento->numero_matricula                 = $oDadosDocumento->ed47_certidaomatricula;
    $this->oDadosDocumento->numero_cpf                       = $oDadosDocumento->ed47_v_cpf;
    $this->oDadosDocumento->documento_estrangeiro_passaporte = $oDadosDocumento->ed47_c_passaporte;
    $this->oDadosDocumento->localizacao_zona_residencia      = $oDadosDocumento->ed47_c_zona;
    $this->oDadosDocumento->numero_identificacao_social      = $this->oDadosAluno->numero_identificacao_social;
    $this->oDadosDocumento->justificativa_falta_documentacao = $oDadosDocumento->ed47_situacaodocumentacao;
        
    if ($oDadosDocumento->ed47_situacaodocumentacao == 0) {
      $this->oDadosDocumento->justificativa_falta_documentacao = '';
    }

    $this->oDadosDocumento->cep                              = $oDadosDocumento->ed47_v_cep;
    $this->oDadosDocumento->endereco                         = $this->removeCaracteres($oDadosDocumento->ed47_v_ender,  3);
    $this->oDadosDocumento->numero                           = $this->removeCaracteres($oDadosDocumento->ed47_c_numero, 3);
    $this->oDadosDocumento->complemento                      = $this->removeCaracteres($oDadosDocumento->ed47_v_compl,  3);
    $this->oDadosDocumento->bairro                           = $this->removeCaracteres($oDadosDocumento->ed47_v_bairro, 3);
    $this->oDadosDocumento->uf                               = $oDadosDocumento->ed47_i_censoufend;
    $this->oDadosDocumento->municipio                        = $oDadosDocumento->ed47_i_censomunicend;
    
    return $this->oDadosDocumento;
  }

  /**
   * Retorna os dados de Transporte publico do aluno
   */
  public function getDadosTransportePublico() {

    $oDaoAlunoTransporte    = db_utils::getDao("alunocensotipotransporte");
    $sWhere                 = "ed311_aluno = {$this->getCodigoAluno()}";
    $sCampos                = "ed312_sequencial as codigo,";
    $sCampos               .= "ed312_descricao as descricao";
    $sSqlTransportePublico  = $oDaoAlunoTransporte->sql_query_tipo_transporte(null,
                                                                             $sCampos,
                                                                             "ed311_censotipotransporte limit 3",
                                                                             $sWhere
                                                                            );
    $rsTransportePublico    = $oDaoAlunoTransporte->sql_record($sSqlTransportePublico);
    $iTotalLinhas           = $oDaoAlunoTransporte->numrows;
    $aMeiosTransporte       = array();
    for ($iTransporte = 0; $iTransporte < $iTotalLinhas; $iTransporte++) {

      $oDadosTransporte = db_utils::fieldsMemory($rsTransportePublico, $iTransporte);
      $aMeiosTransporte[$oDadosTransporte->codigo] = $oDadosTransporte;
    }
    return $aMeiosTransporte;
  }

  /**
   * Monta os dados do registro 80 - Dados de matricula do aluno
   */
  public function setDadosMatricula($rsMatricula) {

    $sTurmaMultiEtapa            = "";
    $oDadosMatricula             = db_utils::fieldsMemory($rsMatricula, 0);
    $aSeriesValidasMultiEtapaEja = array(12, 13, 22, 23, 51, 58);
    $aTransportes                = $this->getDadosTransportePublico();
    if (in_array($oDadosMatricula->ed11_i_codcenso, $aSeriesValidasMultiEtapaEja)) {
      $sTurmaMultiEtapa = $oDadosMatricula->codcensomatricula;
    }
    $sTurmaUnificada    = '';
    if ($oDadosMatricula->ed11_i_codcenso == 3) {
       $sTurmaUnificada = 2;
    }
    $this->aDadosMatricula = array();
    
    $oDadosMatricula->tipo_registro                       = 80;
    $oDadosMatricula->identificacao_unica_aluno           = $oDadosMatricula->ed47_c_codigoinep;
    $oDadosMatricula->codigo_aluno_entidade_escola        = $oDadosMatricula->ed47_i_codigo;
    $oDadosMatricula->codigo_turma_inep                   = $oDadosMatricula->ed57_i_codigoinep;
    $oDadosMatricula->codigo_turma_entidade_escola        = $oDadosMatricula->ed57_i_codigo;
    $oDadosMatricula->codigo_matricula_aluno              = '';
    $oDadosMatricula->turma_unificada                     = $sTurmaUnificada;
    $oDadosMatricula->codigo_etapa_multi_etapa            = $sTurmaMultiEtapa;
    $oDadosMatricula->recebe_escolarizacao_outro_espaco   = $oDadosMatricula->ed47_c_atenddifer;
    $oDadosMatricula->transporte_escolar_publico          = $oDadosMatricula->ed47_i_transpublico;
    $oDadosMatricula->poder_publico_transporte_escolar    = $oDadosMatricula->ed47_c_transporte;
    $oDadosMatricula->forma_ingresso_aluno_escola_federal = '';
    $oDadosMatricula->tipo_turma                          = "NORMAL";
    
    
    $this->aDadosMatricula[] = $oDadosMatricula;

    $aMatriculasAEE = $this->getMatriculasAtividadeEspecial();
    foreach ($aMatriculasAEE as $oMatriculaAEE) {
      $this->aDadosMatricula[] = $oMatriculaAEE;
    }

    foreach ($this->aDadosMatricula as $oMatricula) {
      $sValorDefaultTransporte = '';
      if ($oMatricula->transporte_escolar_publico  == 1) {
        $sValorDefaultTransporte = '0';
      }
      $oMatricula->rodoviario_vans_kombi                    = isset($aTransportes[1])?1:$sValorDefaultTransporte;
      $oMatricula->rodoviario_microonibus                   = isset($aTransportes[2])?1:$sValorDefaultTransporte;
      $oMatricula->rodoviario_onibus                        = isset($aTransportes[3])?1:$sValorDefaultTransporte;
      $oMatricula->rodoviario_bicicleta                     = isset($aTransportes[4])?1:$sValorDefaultTransporte;
      $oMatricula->rodoviario_tracao_animal                 = isset($aTransportes[5])?1:$sValorDefaultTransporte;
      $oMatricula->rodoviario_outro                         = isset($aTransportes[6])?1:$sValorDefaultTransporte;
      $oMatricula->aquaviario_embarcacao_5_pessoas          = isset($aTransportes[7])?1:$sValorDefaultTransporte;
      $oMatricula->aquaviario_embarcacao_5_a_15_pessoas     = isset($aTransportes[8])?1:$sValorDefaultTransporte;
      $oMatricula->aquaviario_embarcacao_15_a_35_pessoas    = isset($aTransportes[9])?1:$sValorDefaultTransporte;
      $oMatricula->aquaviario_embarcacao_mais_de_35_pessoas = isset($aTransportes[10])?1:$sValorDefaultTransporte;
      $oMatricula->ferroviario_trem_metro                   = isset($aTransportes[11])?1:$sValorDefaultTransporte;

    }
    unset($aTransportes);
    return $this->aDadosMatricula;
  }

  /**
   * Valida os dados do arquivo
   * @param $oExportacaoCenso instancia da Importacao do censo
   * @return boolean
   */
  public function validarDados( ExportacaoCenso2013 $oExportacaoCenso ) {

    $lDadosValidos   = true;
    $lValidaCertidao = true;
    $aNecessidades   = array();
    $oDadosAluno     = $oExportacaoCenso->getDadosProcessadosAluno();

    foreach ($oDadosAluno as $oAlunos) {
    	
      /**
       * Validações do registro 60 do Layout do Censo
       */
    	if (!empty($oAlunos->registro60->identificacao_unica_aluno)) {
    		
	    	if (strlen($oAlunos->registro60->identificacao_unica_aluno) < 12) {
	    	
	    		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
	    		$sMsgErro .= "Código INEP do Aluno possui tamanho inferior a 12 dígitos.";
	    		$oExportacaoCenso->logErro($sMsgErro);
	    		$lDadosValidos = false;
	    	}
    	}
    	
    	if (!empty($oAlunos->registro60->numero_identificacao_social)) {
    	
    		if (!parent::ValidaNIS($oAlunos->registro60->numero_identificacao_social)) {
    	
    			$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
    			$sMsgErro .= "Número NIS do Aluno é inválido.";
    			$oExportacaoCenso->logErro($sMsgErro);
    			$lDadosValidos = false;
    		}
    	}
    	
      if ($oAlunos->registro60->data_nascimento == "") {

        $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $sMsgErro .= "Campo Nascimento é obrigatório.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }

      if ($oAlunos->registro60->filiacao == 1) {

        if ($oAlunos->registro60->nome_mae == "" && $oAlunos->registro60->nome_pai == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "É necessário preencher o Nome da mãe e/ou o Nome do pai.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }

        if ($oAlunos->registro60->nome_mae == $oAlunos->registro60->nome_pai) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "O nome da mãe e do pai devem ser diferentes.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
        
        if (!empty($oAlunos->registro60->nome_mae)) {
        
        	if(!DBString::isSomenteLetras($oAlunos->registro60->nome_mae)){
        	
	        	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
	        	$sMsgErro .= "O nome da mãe possui caracteres inválidos, deve ser informado apenas letras.";
	        	$oExportacaoCenso->logErro($sMsgErro);
	        	$lDadosValidos = false;
        	}
        	
        	if(strpos($oAlunos->registro60->nome_mae, '  ')){
        		 
        		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        		$sMsgErro .= "O nome da mãe deve conter apenas espaços simples.";
        		$oExportacaoCenso->logErro($sMsgErro);
        		$lDadosValidos = false;
        	}
        }
        
        if (!empty($oAlunos->registro60->nome_pai)) {
        
        	if(!DBString::isSomenteLetras($oAlunos->registro60->nome_pai)){
        		 
	        	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
	        	$sMsgErro .= "O nome da pai possui caracteres inválidos, deve ser informado apenas letras.";
	        	$oExportacaoCenso->logErro($sMsgErro);
	        	$lDadosValidos = false;
        	}
        	
        	if(strpos($oAlunos->registro60->nome_pai, '  ')){
        		 
        		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        		$sMsgErro .= "O nome do pai deve conter apenas espaços simples.";
        		$oExportacaoCenso->logErro($sMsgErro);
        		$lDadosValidos = false;
        	}
        }
        
      }

      if ($oAlunos->registro60->pais_origem == 76) {

        if ($oAlunos->registro60->nacionalidade_aluno == 3) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "O país de origem deve estar de acordo com a nacionalidade.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if ($oAlunos->registro60->pais_origem != 76) {

        if ($oAlunos->registro60->nacionalidade_aluno != 3) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Deve ser selecionada a nacionalidade Estrangeira para este país.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if ($oAlunos->registro60->nacionalidade_aluno == 1) {

        if ($oAlunos->registro60->uf_nascimento == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "UF de nascimento deve ser informado.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }

        if ($oAlunos->registro60->municipio_nascimento == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Município de nascimento deve ser informado.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }
      
      $iControleDeficiencias = 0;
      
      if ( $oAlunos->registro60->tipos_defic_transtorno_def_autismo_infantil == 1 ) {
        $iControleDeficiencias++;
      }
      
      if ( $oAlunos->registro60->tipos_defic_transtorno_def_asperger == 1 ) {
        $iControleDeficiencias++;
      }
      
      if ( $oAlunos->registro60->tipos_defic_transtorno_def_sindrome_rett == 1 ) {
        $iControleDeficiencias++;
      }
      
      if ( $oAlunos->registro60->tipos_defic_transtorno_desintegrativo_infancia == 1 ) {
        $iControleDeficiencias++;
      }
      
      if ($iControleDeficiencias > 1) {
        
        $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $sMsgErro .= "Deve ser selecionado apenas um tipo de deficiência entre: Autismo Infantil, Síndrome de ";
        $sMsgErro .= "Asperger, Síndrome de Rett e Transtorno Desintegrativo da Infância.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }
      
      /* Criando vetores com necessidades e recursos do aluno */
      $aNecessidades['tipos_defic_transtorno_cegueira'] 		           = $oAlunos->registro60->tipos_defic_transtorno_cegueira; 		          
      $aNecessidades['tipos_defic_transtorno_baixa_visao'] 	           = $oAlunos->registro60->tipos_defic_transtorno_baixa_visao; 	          
      $aNecessidades['tipos_defic_transtorno_surdez'] 			           = $oAlunos->registro60->tipos_defic_transtorno_surdez; 			          
      $aNecessidades['tipos_defic_transtorno_auditiva'] 		           = $oAlunos->registro60->tipos_defic_transtorno_auditiva; 		          
      $aNecessidades['tipos_defic_transtorno_surdocegueira']           = $oAlunos->registro60->tipos_defic_transtorno_surdocegueira;          
      $aNecessidades['tipos_defic_transtorno_def_fisica']              = $oAlunos->registro60->tipos_defic_transtorno_def_fisica;             
      $aNecessidades['tipos_defic_transtorno_def_intelectual']         = $oAlunos->registro60->tipos_defic_transtorno_def_intelectual;        
      $aNecessidades['tipos_defic_transtorno_def_autismo_infantil']		 = $oAlunos->registro60->tipos_defic_transtorno_def_autismo_infantil;		
      $aNecessidades['tipos_defic_transtorno_def_asperger']						 = $oAlunos->registro60->tipos_defic_transtorno_def_asperger;						
      $aNecessidades['tipos_defic_transtorno_def_sindrome_rett']			 = $oAlunos->registro60->tipos_defic_transtorno_def_sindrome_rett;			
      $aNecessidades['tipos_defic_transtorno_desintegrativo_infancia'] = $oAlunos->registro60->tipos_defic_transtorno_desintegrativo_infancia;
      $aNecessidades['tipos_defic_transtorno_altas_habilidades']			 = $oAlunos->registro60->tipos_defic_transtorno_altas_habilidades;			
      $aNecessidades['tipos_defic_transtorno_def_multipla']						 = $oAlunos->registro60->tipos_defic_transtorno_def_multipla;
      						
      $aRecursos['recurso_auxilio_ledor']  						                 = $oAlunos->registro60->recurso_auxilio_ledor; 
      $aRecursos['recurso_auxilio_transcricao']  			                 = $oAlunos->registro60->recurso_auxilio_transcricao;
      $aRecursos['recurso_auxilio_interprete']  			                 = $oAlunos->registro60->recurso_auxilio_interprete;
      $aRecursos['recurso_auxilio_interprete_libras']                  = $oAlunos->registro60->recurso_auxilio_interprete_libras;
      $aRecursos['recurso_auxilio_leitura_labial']  	                 = $oAlunos->registro60->recurso_auxilio_leitura_labial;
      $aRecursos['recurso_auxilio_prova_ampliada_16']                  = $oAlunos->registro60->recurso_auxilio_prova_ampliada_16;
      $aRecursos['recurso_auxilio_prova_ampliada_20']                  = $oAlunos->registro60->recurso_auxilio_prova_ampliada_20;
      $aRecursos['recurso_auxilio_prova_ampliada_24']                  = $oAlunos->registro60->recurso_auxilio_prova_ampliada_24;
      $aRecursos['recurso_auxilio_prova_braille']  		                 = $oAlunos->registro60->recurso_auxilio_prova_braille;
      $aRecursos['recurso_auxilio_nenhum']  					                 = $oAlunos->registro60->recurso_auxilio_nenhum;
      
     	$avalidarNecessidades = DadosCensoAluno::validarNecessidades($aNecessidades, $aRecursos);
     	$avalidarRecursos			= DadosCensoAluno::validarRecursos($aNecessidades, $aRecursos);
      	
      if(count($avalidarNecessidades) > 0){
      		 
      	foreach($avalidarNecessidades as $sMsgErroValidarNecessidades){
      	
      		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      		$sMsgErro .= $sMsgErroValidarNecessidades;
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      }
      	
      if(count($avalidarRecursos) > 0){
      	
      	foreach($avalidarRecursos as $sMsgErroValidarRecursos){
      		
      		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      		$sMsgErro .= $sMsgErroValidarRecursos;
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      }
            
      if (($oAlunos->registro60->recurso_auxilio_prova_ampliada_16 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_20 == 1) ||
          ($oAlunos->registro60->recurso_auxilio_prova_ampliada_16 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_24 == 1) ||
          ($oAlunos->registro60->recurso_auxilio_prova_ampliada_20 == 1 && $oAlunos->registro60->recurso_auxilio_prova_ampliada_24 == 1)
         ) {
        
        $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $sMsgErro .= "Deve ser informado somente 1 tipo de de recurso para avaliação no INEP, entre Prova Ampliada";
        $sMsgErro .= " (Fonte Tamanho 16), Prova Ampliada (Fonte Tamanho 20) e Prova Ampliada (Fonte Tamanho 24).";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }
      
      /**
       * Validações do registro 70 do Layout do Censo
       */
      $oRetornoDocumentacao = DadosCensoAluno::registroDocumentacaoValido( $oAlunos );
      
      if ( !$oRetornoDocumentacao->lDadosValidos ) {
        
        $oExportacaoCenso->logErro( $oRetornoDocumentacao->sMsgErro );
        $lDadosValidos = $oRetornoDocumentacao->lDadosValidos;
      }
      
      if ($oAlunos->registro70->numero_cpf != "") {
      
        if (!DBString::isCPF($oAlunos->registro70->numero_cpf)) {
      
          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= $oAlunos->registro70->numero_cpf . " não é um CPF válido";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }
      
      if ($oAlunos->registro70->numero_identidade != "") {

        if ($oAlunos->registro60->nacionalidade_aluno == 3) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Número de identidade deve ser preenchido apenas por alunos com nacionalidade Brasileira ";
          $sMsgErro .= "ou Brasileira - nascido no exterior ou naturalizado";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }

        if ($oAlunos->registro70->orgao_emissor_identidade == "" && $oAlunos->registro70->uf_identidade == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
            	
      	$lValidaCertidao = false;
      }else{
      	
      	$lValidaCertidao = true;
      }
      
      if ($oAlunos->registro70->orgao_emissor_identidade != "" && $oAlunos->registro70->numero_identidade == "") {
      
      	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      	$sMsgErro .= "Órgão emissor da identidade preenchido sem a informação do campo Número da identidade.";
      	$oExportacaoCenso->logErro($sMsgErro);
      	$lDadosValidos = false;
      }
      
      if ($oAlunos->registro70->orgao_emissor_identidade != "" && $oAlunos->registro70->data_expedicao_identidade == "") {
      
      	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      	$sMsgErro .= "Órgão emissor da identidade preenchido sem a informação do campo Data Expedição Identidade.";
      	$oExportacaoCenso->logErro($sMsgErro);
      	$lDadosValidos = false;
      }

      if ($oAlunos->registro70->complemento_identidade != "") {

        if ($oAlunos->registro70->numero_identidade        == "" ||
            $oAlunos->registro70->orgao_emissor_identidade == "" ||
            $oAlunos->registro70->uf_identidade            == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Número da identidade, Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if ($oAlunos->registro70->data_expedicao_identidade != "") {

        if ($oAlunos->registro70->numero_identidade        == "" ||
            $oAlunos->registro70->orgao_emissor_identidade == "" ||
            $oAlunos->registro70->uf_identidade            == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Número da identidade, Órgão Emissor da Identidade e UF da Identidade devem ser preenchidos.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if (($oAlunos->registro60->nacionalidade_aluno == 1 || $oAlunos->registro60->nacionalidade_aluno == 2) && $lValidaCertidao) {
      	
        if ($oAlunos->registro70->certidao_civil == 1 && $oAlunos->registro70->justificativa_falta_documentacao == '') {

          if ($oAlunos->registro70->tipo_certidao_civil == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Tipo de Certidão Civil deve ser preenchido.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oAlunos->registro70->numero_termo == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Número do Termo deve ser preenchido.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oAlunos->registro70->uf_cartorio == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "UF do Cartório deve ser preenchido.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if (trim($oAlunos->registro70->codigo_cartorio) == '') {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Campo Cartório de emissão não informado. ";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }
          
          if ($oAlunos->registro70->municipio_cartorio == '') {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Campo município do cartório de emissão não informado. ";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }
        }

        if ($oAlunos->registro70->certidao_civil == 2) {

          if ($oAlunos->registro70->numero_matricula == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Número da Matrícula (Registro Civil - Certidão Nova) deve ser preenchido.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oAlunos->registro70->tipo_certidao_civil   != "" ||
              $oAlunos->registro70->numero_termo          != "" ||
              $oAlunos->registro70->folha                 != "" ||
              $oAlunos->registro70->livro                 != "" ||
              $oAlunos->registro70->data_emissao_certidao != "" ||
              $oAlunos->registro70->uf_cartorio           != "" ||
              $oAlunos->registro70->municipio_cartorio    != "" ) {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Os seguintes campos não devem ser preenchidos: Tipo de Certidão Civil, Número do Termo, Folha, ";
            $sMsgErro .= "Livro, Data de Emissão da Certidão, UF do Cartório e Município do Cartório.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oAlunos->registro70->numero_matricula == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Número da Matrícula (Registro Civil - Certidão nova) deve ser preenchido.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }
        }
      }
      $sDataEmissao    = implode("-", array_reverse(explode("/", $oAlunos->registro70->data_emissao_certidao)));
      $sDataNascimento = implode("-", array_reverse(explode("/", $oAlunos->registro60->data_nascimento)));
      if ($sDataEmissao != "" && db_strtotime($sDataEmissao) < db_strtotime($sDataNascimento)) {

        $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $sMsgErro .= "Data de Emissão da Certidão não deve ser menor que a data de nascimento.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }
      
      if ($oAlunos->registro70->folha                 != "" ||
          $oAlunos->registro70->livro                 != "" ||
          $oAlunos->registro70->data_emissao_certidao != "") {

        if ($oAlunos->registro60->nacionalidade_aluno == 3 && $oAlunos->registro70->certidao_civil != 1) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Deve ser selecionada a nacionalidade Brasileira ou Brasileira - nascido no exterior ou ";
          $sMsgErro .= "naturalizado, e a certidão de nascimento deve ser do modelo antigo.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if ($oAlunos->registro70->documento_estrangeiro_passaporte != "") {

        if ($oAlunos->registro60->nacionalidade_aluno != 3) {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Deve ser selecionada a nacionalidade Estrangeira para utilizar o documento estrangeiro.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }
      
      if ($oAlunos->registro70->numero != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "", $oAlunos->registro70->numero),true)) {
      	
      	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      	$sMsgErro .= "Caracteres inválidos no número do endereço do aluno.";
      	$oExportacaoCenso->logErro($sMsgErro);
      	$lDadosValidos = false;
      }
      
      if ($oAlunos->registro70->folha != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "", $oAlunos->registro70->folha),true,true)) {
         
        $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $sMsgErro .= "Caracteres inválidos no campo folha dos dados da certidão do aluno.";
        $oExportacaoCenso->logErro($sMsgErro);
        $lDadosValidos = false;
      }
      
      if ($oAlunos->registro70->livro != '' && !DBString::isSomenteAlfanumerico(str_replace(" ", "", $oAlunos->registro70->livro),true,true,true)) {
      	 
      	$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      	$sMsgErro .= "Caracteres inválidos no livro da certidão do aluno.";
      	$oExportacaoCenso->logErro($sMsgErro);
      	$lDadosValidos = false;
      }
      
      if ($oAlunos->registro70->cep != "") {

        if ($oAlunos->registro70->endereco  == "" ||
            $oAlunos->registro70->municipio == "" ||
            $oAlunos->registro70->uf        == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "Endereço, Município e UF devem ser preenchidos.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }

      if ($oAlunos->registro70->numero      != "" ||
          $oAlunos->registro70->complemento != "" ||
          $oAlunos->registro70->bairro      != "") {

        if ($oAlunos->registro70->cep       == "" ||
            $oAlunos->registro70->endereco  == "" ||
            $oAlunos->registro70->municipio == "" ||
            $oAlunos->registro70->uf        == "") {

          $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
          $sMsgErro .= "CEP, Endereço, Município e UF devem ser preenchidos.";
          $oExportacaoCenso->logErro($sMsgErro);
          $lDadosValidos = false;
        }
      }
      
      
      $aMatriculaTurno = array();
      /**
       * Validações do registro 80 do Layout do Censo
       */
      foreach ($oAlunos->registro80 as $oMatricula) {
      	
      	if ( !in_array( $oMatricula->tipo_turma, $aMatriculaTurno ) ) {
      		$aMatriculaTurno[$oMatricula->turnoreferente] = $oMatricula->tipo_turma;
      	} else {
      		
      	  if ( in_array( $oMatricula->tipo_turma, $aMatriculaTurno )                    && 
      	       $aMatriculaTurno[$oMatricula->turnoreferente] == $oMatricula->tipo_turma && 
      	       $oMatricula->tipo_turma == "AEE" 
             ) {
      	    continue;
      	  }
      	  
      		$sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
      		$sMsgErro .= "Aluno(a) matriculado em mais de uma turma, no mesmo turno (conflito de horários).";
      		$oExportacaoCenso->logErro($sMsgErro);
      		$lDadosValidos = false;
      	}
      	
        if ($oMatricula->transporte_escolar_publico == 1) {

          if ($oMatricula->poder_publico_transporte_escolar == "") {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Deve ser informado o poder público responsável.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }

          if ($oMatricula->rodoviario_vans_kombi                    == 0 &&
              $oMatricula->rodoviario_microonibus                   == 0 &&
              $oMatricula->rodoviario_onibus                        == 0 &&
              $oMatricula->rodoviario_bicicleta                     == 0 &&
              $oMatricula->rodoviario_tracao_animal                 == 0 &&
              $oMatricula->rodoviario_outro                         == 0 &&
              $oMatricula->aquaviario_embarcacao_5_pessoas          == 0 &&
              $oMatricula->aquaviario_embarcacao_5_a_15_pessoas     == 0 &&
              $oMatricula->aquaviario_embarcacao_15_a_35_pessoas    == 0 &&
              $oMatricula->aquaviario_embarcacao_mais_de_35_pessoas == 0 &&
              $oMatricula->ferroviario_trem_metro                   == 0) {

            $sMsgErro  = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo} - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
            $sMsgErro .= "Ao menos uma das opções de transporte público deve ser selecionada.";
            $oExportacaoCenso->logErro($sMsgErro);
            $lDadosValidos = false;
          }
        }
      }
    }
    return $lDadosValidos;
  }

  /**
   * Valida os Necessidades especiais do aluno
   * @param $oExportacaoCenso instancia da Importacao do censo
   * @return boolean
   */
  function validarNecessidades ($aNecessidades, $aRecursos){
  
  	$aErroMsg							= array();
  	$aNecessidadesDoAluno = array();
  	$iContadorErros				= 0;
  	
  	foreach( $aNecessidades as $sTipoDeficiencia => $iNecessidade ){
  		 
  		if($iNecessidade == 1){
  
  			$aNecessidadesDoAluno[$sTipoDeficiencia] = $iNecessidade;
  		}
  	}
  	
  	if(count($aNecessidadesDoAluno) == 0){
  		return $aErroMsg;
  	} else {
  		
  		foreach( $aNecessidadesDoAluno as $sTipoDeficiencia => $iNecessidade ) {
  			
  			switch ($sTipoDeficiencia) {
  
  				case 'tipos_defic_transtorno_cegueira':
  					 
  					if( $aRecursos['recurso_auxilio_transcricao']   == 0 &&
		  					$aRecursos['recurso_auxilio_ledor'] 				== 0 &&
		  					$aRecursos['recurso_auxilio_prova_braille'] == 0 &&
  	            $aRecursos['recurso_auxilio_nenhum']        == 0  ) {
  							
  							$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Cegueira.";
  					}
  						
  				break;
  				
  				case 'tipos_defic_transtorno_baixa_visao':
  						
  					if( $aRecursos['recurso_auxilio_transcricao']   		== 0 &&
		  					$aRecursos['recurso_auxilio_ledor'] 						== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_16']	== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_20']	== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_24']	== 0 &&
  	            $aRecursos['recurso_auxilio_nenhum']            == 0  ) {
  
  						$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Baixa Visão.";
  					}
  					
  				break;
  
  				case 'tipos_defic_transtorno_surdez':
  													
  					if( $aRecursos['recurso_auxilio_leitura_labial'] 		== 0 &&
  							$aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
  	            $aRecursos['recurso_auxilio_nenhum']            == 0   ) {
  						 
  						$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Surdez.";
  					}
  					 
  				break;
  					 
  				case 'tipos_defic_transtorno_auditiva':
  						
  					if( $aRecursos['recurso_auxilio_leitura_labial'] 		== 0 &&
  							$aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
  	            $aRecursos['recurso_auxilio_nenhum']            == 0   ) {
  
  						$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Deficiência Auditiva.";
  					}
  						
  				break;
  
  				case 'tipos_defic_transtorno_surdocegueira':
  						
  					if( $aRecursos['recurso_auxilio_ledor'] 						== 0 &&
		  					$aRecursos['recurso_auxilio_transcricao'] 			== 0 &&
		  					$aRecursos['recurso_auxilio_interprete'] 				== 0 &&
		  					$aRecursos['recurso_auxilio_interprete_libras'] == 0 &&
		  					$aRecursos['recurso_auxilio_leitura_labial'] 		== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_16']	== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_20']	== 0 &&
		  					$aRecursos['recurso_auxilio_prova_ampliada_24']	== 0 &&
		  					$aRecursos['recurso_auxilio_prova_braille'] 		== 0 &&
  	            $aRecursos['recurso_auxilio_nenhum']            == 0   ) {
  						 
  						$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Surdocegueira.";
  					}
  						
  				break;
  						
  				default :
  
						if( $aRecursos['recurso_auxilio_transcricao'] == 1 ||
								$aRecursos['recurso_auxilio_ledor'] 			== 1   ){
  	      
    					if( $sTipoDeficiencia != 'tipos_defic_transtorno_def_fisica' 							&&
  		  					$sTipoDeficiencia != 'tipos_defic_transtorno_def_intelectual' 				&&
  		  					$sTipoDeficiencia != 'tipos_defic_transtorno_def_autismo_infantil' 		&&
  		  					$sTipoDeficiencia != 'tipos_defic_transtorno_def_asperger'					  &&
  		  					$sTipoDeficiencia != 'tipos_defic_transtorno_def_sindrome_rett' 			&&
  		  					$sTipoDeficiencia != 'tipos_defic_transtorno_desintegrativo_infancia'		 ) {
  								
  								$aErroMsg[$iContadorErros++] = "Não foi selecionado recurso de avaliação para a Necessidade Especial Informada.";
  						}
  					}
  
  					if( $sTipoDeficiencia == 'tipos_defic_transtorno_altas_habilidades' ) {
  	      
  						if( $aNecessidades['tipos_defic_transtorno_cegueira'] == 0 && $aNecessidades['tipos_defic_transtorno_baixa_visao'] == 0){
  
  							if( $aRecursos['recurso_auxilio_transcricao'] != 0 &&	$aRecursos['recurso_auxilio_ledor'] != 0 ){
  	      		
  								$aErroMsg[$iContadorErros++] = "Foram selecionados recursos de avaliação inválidos para a Necessidade Especial Altas habilidades/Superdotação.";
  							}
  						}
  					}
  
  				break;
  			}
  		}
  	}
  
  	return $aErroMsg;
  }
    

  function validarRecursos ($aNecessidades, $aRecursos){
  
  	$aErroMsg							= array();
  	$aRecursosDoAluno			= array();
  	$iContadorErros				= 0;
  	 
  	foreach( $aRecursos as $sTipoRecurso => $iRecurso ){
  			
  		if($iRecurso == 1){
  
  			$aRecursosDoAluno[$sTipoRecurso] = $iRecurso;
  		}
  	}
  
  	if(count($aRecursosDoAluno) == 0){
  			
  		return $aErroMsg;
  	}else{
  
  		foreach( $aRecursosDoAluno as $sTipoRecurso => $iNecessidade ){
  
  			switch ($sTipoRecurso) {
  
  				case 'recurso_auxilio_ledor':
  
  					if( $aNecessidades['tipos_defic_transtorno_cegueira'] 								== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_baixa_visao'] 							== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_surdocegueira']						== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_def_fisica']								== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_def_intelectual']					== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_def_autismo_infantil']			== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_def_asperger']							== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_def_sindrome_rett']				== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_desintegrativo_infancia']	== 0   ){
		  
  						$aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Auxílio ledor.";
  					}
  
  				break;

  				case ($sTipoRecurso == 'recurso_auxilio_interprete' || $sTipoRecurso == 'recurso_auxilio_interprete_libras'):
  				
  					if(	$aNecessidades['tipos_defic_transtorno_surdocegueira']			== 0 ){
  				
  						$aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Guia-intérprete / Intérprete de Libras.";
  					}
  				
  				break;
  				
  				case 'recurso_auxilio_leitura_labial':
  				
  					if( $aNecessidades['tipos_defic_transtorno_surdez']							== 0 &&
  							$aNecessidades['tipos_defic_transtorno_auditiva']						== 0   ){
  				
  						$aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Leitura Labial.";
  					}
  				
  				break;
  					
  				case ($sTipoRecurso == 'recurso_auxilio_prova_ampliada_16' || $sTipoRecurso == 'recurso_auxilio_prova_ampliada_20' || $sTipoRecurso == 'recurso_auxilio_prova_ampliada_24'):
  				
  					if(	$aNecessidades['tipos_defic_transtorno_baixa_visao'] 				== 0 &&
  							$aNecessidades['tipos_defic_transtorno_surdocegueira']			== 0   ){
  				
  						$aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Prova Ampliada.";
  					}
  				
  				break;
  					
  				case 'recurso_auxilio_prova_braille':
  				
  					if( $aNecessidades['tipos_defic_transtorno_cegueira'] 					== 0 &&
		  					$aNecessidades['tipos_defic_transtorno_surdocegueira']			== 0   ){
  				
  						$aErroMsg[$iContadorErros++] = "O Aluno não possui Necessidade Especial compatível com o Recurso de avaliação selecionado, Prova Braille.";
  					}
  				
 					break;
  
  			}
  		}
  	}
  
  	return $aErroMsg;
  }
  
  public function getMatriculasAtividadeEspecial() {

    $aMatriculaAEE    = array();
    $oDaoTurmaAEE     = new cl_turmaacmatricula();
    $sWhereMatricula  = " turmaac.ed268_i_escola = {$this->iEscola} ";
    $sWhereMatricula .= "  AND calendario.ed52_i_ano = {$this->iAnoCenso} ";
    $sWhereMatricula .= "  AND aluno.ed47_i_codigo   = {$this->iCodigoAluno} ";
    $sWhereMatricula .= "  AND (ed60_d_datamatricula <= '{$this->sDataCenso}' AND ed60_c_concluida = 'N' AND ed60_c_ativa = 'S') ";
    $sWhereMatricula .= "  AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
    $sWhereMatricula .= "       OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '{$this->sDataCenso}'))";
    $sSqlMatriculas   = $oDaoTurmaAEE->sql_query_censo(null, "*", null, $sWhereMatricula);
    
    $rsMatriculaAEE   = $oDaoTurmaAEE->sql_record($sSqlMatriculas);
    
    if ($oDaoTurmaAEE->numrows > 0) {

      for ($i = 0; $i < $oDaoTurmaAEE->numrows; $i++) {

        $oMatricula      = db_utils::fieldsMemory($rsMatriculaAEE, $i);
        $oDadosMatricula = new stdClass();
        
        if($oMatricula->ed268_i_tipoatend == 5 && empty($oMatricula->ed214_i_codigo)){
        	continue;
        }
        
        $oDadosMatricula->tipo_registro                       = 80;
        $oDadosMatricula->identificacao_unica_aluno           = $oMatricula->ed47_c_codigoinep;
        $oDadosMatricula->codigo_aluno_entidade_escola        = $oMatricula->ed47_i_codigo;
        $oDadosMatricula->codigo_turma_inep                   = $oMatricula->ed268_i_codigoinep;
        $oDadosMatricula->codigo_turma_entidade_escola        = $oMatricula->ed268_i_codigo;
        $oDadosMatricula->codigo_matricula_aluno              = '';
        $oDadosMatricula->turma_unificada                     = '';
        $oDadosMatricula->codigo_etapa_multi_etapa            = '';
        $oDadosMatricula->recebe_escolarizacao_outro_espaco   = 3;
        $oDadosMatricula->transporte_escolar_publico          = $oMatricula->ed47_i_transpublico;
        $oDadosMatricula->poder_publico_transporte_escolar    = $oMatricula->ed47_c_transporte;
        $oDadosMatricula->forma_ingresso_aluno_escola_federal = '';
        $oDadosMatricula->turnoreferente 					            = $oMatricula->ed231_i_referencia;
        $oDadosMatricula->tipo_turma                          = "AEE";
        $aMatriculaAEE[] = $oDadosMatricula;
      }
      
    }
    
    return $aMatriculaAEE;
  }

  /**
   * Realiza a atualização dos dados do aluno conforme os dados do censo
   */
  public function atualizarDados(DBLayoutLinha $oLinha) {

    $oDaoAluno = $this->preencherDaoAluno($oLinha);
    $oDaoAluno->ed47_i_codigo        = $this->getCodigoAluno();
    $oDaoAluno->alterar($this->getCodigoAluno());
    if ($oDaoAluno->erro_status == '0') {
      throw new Exception("Erro na alteração dos dados do Aluno. Erro da classe: ".$oDaoAluno->erro_msg);
    }
    $this->atualizarNecessidadesEspeciais($oLinha);
  }

  /**
   * Adiciona um novo aluno
   * @param DBLayoutLinha $oLinha
   */
  public function adicionarNovoAluno(DBLayoutLinha $oLinha) {

    $oDaoAluno = $this->preencherDaoAluno($oLinha);
    $oDaoAluno->ed47_c_atenddifer         = '3';
    $oDaoAluno->ed47_v_ender              = 'NAO INFORMADO';
    $oDaoAluno->ed47_i_transpublico       = '0';
    if (!empty($oLinha->nome_completo)) {
      $oDaoAluno->ed47_v_nome = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo);
    }
    $oDaoAluno->incluir(null);
     if ($oDaoAluno->erro_status == '0') {
      throw new Exception("Erro na inclusão do novo Aluno. Erro da classe: ".$oDaoAluno->erro_msg);
    }

    $this->iCodigoAluno = $oDaoAluno->ed47_i_codigo;
    $this->atualizarNecessidadesEspeciais($oLinha);
  }

  /**
   * Converte a raça do censo para o formato do e-cidaade, retornando o nome da cor/raça.
   * @param integer $iCodigoRacaCenso Código da cor/raça do censo
   * @return string
   */
  public function getRaca($iCodigoRacaCenso) {

    $sRaca = '';
    switch (trim($iCodigoRacaCenso)) {

      case 1:
        $sRaca = 'BRANCA';
        break;

      case 2:

        $sRaca = 'PRETA';
        break;
      case 3:

        $sRaca = 'PARDA';
        break;

      case 4:

        $sRaca = 'AMARELA';
        break;

      case 5:

        $sRaca = 'INDÍGENA';
        break;

      default :

        $sRaca = 'NÃO DECLARADA';
        break;
    }
    return $sRaca;
  }

  /**
   * Preenche os dados da dao cl_aluno
   */
  protected function preencherDaoAluno(DBLayoutLinha $oLinha) {

    $oDaoAluno                         = db_utils::getdao('aluno');
    $oDaoAluno->ed47_i_censoorgemissrg = "";
    $oDaoAluno->ed47_i_censocartorio   = "";
    $oDaoAluno->ed47_i_pais            = "";
    $oDaoAluno->oid                    = "";

    if (!empty($oLinha->nome_mae)) {
      $oDaoAluno->ed47_v_mae =  str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_mae);
    }

    if (!empty($oLinha->nome_pai)) {
      $oDaoAluno->ed47_v_pai = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_pai);
    }

    if ($oLinha->data_nascimento  != "") {
      $oDaoAluno->ed47_d_nasc = importacaoCenso::formataData($oLinha->data_nascimento);
    }

    if ($oLinha->sexo != "") {
      $oDaoAluno->ed47_v_sexo = $oLinha->sexo == 1?"M":"F";
    }

    $oDaoAluno->ed47_c_raca = $this->getRaca(trim($oLinha->cor_raca));
    if ($oLinha-> pais_origem != "") {
      $oDaoAluno->ed47_i_pais = importacaoCenso::getPais($oLinha->pais_origem);
    }

    if ($oLinha->nacionalidade_aluno != "") {
      $oDaoAluno->ed47_i_nacion = $oLinha->nacionalidade_aluno;
    } else {
      $oDaoAluno->ed47_i_nacion = 1;
    }
    if ($oLinha->uf_nascimento != "") {
      $oDaoAluno->ed47_i_censoufnat = $oLinha->uf_nascimento;
    }

    if ($oLinha->municipio_nascimento != "") {
      $oDaoAluno->ed47_i_censomunicnat = $oLinha->municipio_nascimento;
    }
    $oDaoAluno->ed47_c_codigoinep         = $oLinha->identificacao_unica_aluno;
    $oDaoAluno->ed47_c_nis                = $oLinha->numero_identificacao_social;
    $oDaoAluno->ed47_i_filiacao           = $oLinha->filiacao;
    $oDaoAluno->ed47_c_atenddifer         = '3';
    $oDaoAluno->ed47_v_ender              = 'NAO INFORMADO';
  	$oDaoAluno->ed47_i_transpublico       = "";
  	$oDaoAluno->ed47_situacaodocumentacao = "0"; 
    return $oDaoAluno;
  }

  protected function atualizarNecessidadesEspeciais(DBLayoutLinha $oLinha) {

    if (isset($oLinha->alunos_deficiencia_transtorno_desenv_superdotacao)) {

      $oDaoAlunoNecessidade = db_utils::getdao('alunonecessidade');
      $oDaoAlunoNecessidade->excluir(null, "ed214_i_aluno = {$this->getCodigoAluno()}");

      $aNecessidade = array();
      
      trim($oLinha->tipos_defic_transtorno_cegueira)                == 1 ? $aNecessidade[] = 101 : '';
      trim($oLinha->tipos_defic_transtorno_baixa_visao)             == 1 ? $aNecessidade[] = 102 : '';
      trim($oLinha->tipos_defic_transtorno_surdez)                  == 1 ? $aNecessidade[] = 103 : '';
      trim($oLinha->tipos_defic_transtorno_auditiva)                == 1 ? $aNecessidade[] = 104 : '';
      trim($oLinha->tipos_defic_transtorno_surdocegueira)           == 1 ? $aNecessidade[] = 105 : '';
      trim($oLinha->tipos_defic_transtorno_def_fisica)              == 1 ? $aNecessidade[] = 106 : '';
      trim($oLinha->tipos_defic_transtorno_def_intelectual)         == 1 ? $aNecessidade[] = 107 : '';
      trim($oLinha->tipos_defic_transtorno_def_multipla)            == 1 ? $aNecessidade[] = 108 : '';
      trim($oLinha->tipos_defic_transtorno_def_autismo_infantil)    == 1 ? $aNecessidade[] = 109 : '';
      trim($oLinha->tipos_defic_transtorno_def_asperger)            == 1 ? $aNecessidade[] = 110 : '';
      trim($oLinha->tipos_defic_transtorno_def_sindrome_rett)       == 1 ? $aNecessidade[] = 111 : '';
      trim($oLinha->tipos_defic_transtorno_desintegrativo_infancia) == 1 ? $aNecessidade[] = 112 : '';
      trim($oLinha->tipos_defic_transtorno_altas_habilidades)       == 1 ? $aNecessidade[] = 113 : '';
      $iTam = count($aNecessidade);

      for ($iContNecessidade = 0; $iContNecessidade < $iTam; $iContNecessidade++) {

        if ($aNecessidade[$iContNecessidade] > 0) {

          $oDaoAlunoNecessidade->ed214_i_necessidade = $aNecessidade[$iContNecessidade];
          $oDaoAlunoNecessidade->ed214_c_principal   = 'NAO';
          $oDaoAlunoNecessidade->ed214_i_apoio       = 1;
          $oDaoAlunoNecessidade->ed214_d_data        = 'null';
          $oDaoAlunoNecessidade->ed214_i_tipo        = 1;
          $oDaoAlunoNecessidade->ed214_i_escola      = 'null';
          $oDaoAlunoNecessidade->ed214_i_aluno       = $this->getCodigoAluno();
          $oDaoAlunoNecessidade->incluir(null);

          if ($oDaoAlunoNecessidade->erro_status == '0') {

            throw new Exception("Erro na inclusão das necessidades do aluno. Erro da classe: ".
                                $oDaoAlunoNecessidade->erro_msg
                               );

          }

        }
      }
    }
  }

  /**
   * funcao que seleciona e atualiza os dados de endereco  e documentos(certidao, identidade.) do aluno, registro 70
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
   */
  function atualizarEnderecoDocumentos($oLinha) {

    $oDaoAluno = db_utils::getdao('aluno');

    $oDaoAluno->ed47_v_ident           = $oLinha->numero_identidade;
    $oDaoAluno->ed47_v_identcompl      = $oLinha->complemento_identidade;
    $oDaoAluno->ed47_i_censoorgemissrg = $oLinha->orgao_emissor_identidade;
    $oDaoAluno->ed47_i_censoufident    = $oLinha->uf_identidade;
    if (trim($oLinha->data_expedicao_identidade) != "") {
      $oDaoAluno->ed47_d_identdtexp      = importacaoCenso::formataData($oLinha->data_expedicao_identidade);
    }
    $oDaoAluno->ed47_c_certidaotipo    = '';
    if ($oLinha->tipo_certidao_civil == 1) {
      $oDaoAluno->ed47_c_certidaotipo = 'N';
    } else {
      $oDaoAluno->ed47_c_certidaotipo = 'C';
    }
    $oDaoAluno->ed47_c_certidaonum   = $oLinha->numero_termo;
    $oDaoAluno->ed47_c_certidaofolha = $oLinha->folha;
    $oDaoAluno->ed47_c_certidaolivro = $oLinha->livro;
    if (trim($oLinha->data_emissao_certidao) != "") {
      $oDaoAluno->ed47_c_certidaodata  = importacaoCenso::formataData($oLinha->data_emissao_certidao );
    }
    $oDaoAluno->ed47_i_censocartorio = importacaoCenso::getCartorio($oLinha->codigo_cartorio, null);
    $oDaoAluno->ed47_i_censoufcert   = $oLinha->uf_cartorio;
    $oDaoAluno->ed47_v_cpf           = $oLinha->numero_cpf;
    
    if ($oLinha->certidao_civil == 2) {
      $oDaoAluno->ed47_certidaomatricula = $oLinha->numero_matricula;
    }
    $oDaoAluno->ed47_c_passaporte = $oLinha->documento_estrangeiro_passaporte;
    if ($oLinha-> localizacao_zona_residencia == 1) {
      $oDaoAluno->ed47_c_zona = 'URBANA';
    } else {
      $oDaoAluno->ed47_c_zona = 'RURAL';
    }
    $oDaoAluno->ed47_v_cep            = $oLinha->cep;
    $oDaoAluno->ed47_v_ender          = $oLinha->endereco;
    $oDaoAluno->ed47_c_numero         = $oLinha->numero;
    $oDaoAluno->ed47_v_compl          = $oLinha->complemento;
    $oDaoAluno->ed47_v_bairro         = substr($oLinha->bairro, 0, 40);
    $oDaoAluno->ed47_i_censoufend     = $oLinha->uf;
    if ($oLinha->municipio != "") {
      $oDaoAluno->ed47_i_censomunicend  = importacaoCenso::getCensoMunicipioCertidao($oLinha->municipio);
    }
    $oDaoAluno->ed47_i_censomuniccert = $oLinha->municipio_cartorio;
    $oDaoAluno->ed47_i_codigo         = $this->getCodigoAluno();
    $oDaoAluno->alterar($this->getCodigoAluno());

    if ($oDaoAluno->erro_status == '0') {
      throw new Exception("Erro na alteração do endereço do aluno.Erro da classe: ".$oDaoAluno->erro_msg);
    }

    if (!empty($oDaoAluno->ed47_v_bairro)) {

      $oDaoBairro   = db_utils::getdao('bairro');
      $sWhereBairro = "to_ascii(j13_descr,'LATIN1') = '{$oDaoAluno->ed47_v_bairro}'";
      $sSqlBairro   = $oDaoBairro->sql_query_file("", "j13_codi", "", $sWhereBairro);
      $rsBairro     = $oDaoBairro->sql_record($sSqlBairro);

      if ($oDaoBairro->numrows > 0) {

        $oDaoBairroAluno = db_utils::getdao('alunobairro');
        $oDaoBairroAluno->excluir(null, "ed225_i_aluno = {$this->iCodigoAluno}");
        $oDaoBairroAluno->ed225_i_aluno  = $this->getCodigoAluno();
        $oDaoBairroAluno->ed225_i_bairro = db_utils::fieldsmemory($rsBairro, 0)->j13_codi;
        $oDaoBairroAluno->incluir(null);

        if ($oDaoBairroAluno->erro_status == '0') {
          throw new Exception("Erro na alteração do bairro do aluno. Erro da classe: ".$oDaoBairroAluno->erro_msg);
        }//fecha o if erro_status
      }
    }
  }

  /**
   * Atualiza os dados dos transportes utilizados pelo aluno
   */
  public function atualizarDadosTransporte($oLinha) {

    $oDaoAluno = db_utils::getdao('aluno');
    $oDaoAluno->ed47_c_atenddifer   = $oLinha->recebe_escolarizacao_outro_espaco;
    $oDaoAluno->ed47_i_transpublico = $oLinha->transporte_escolar_publico;
    if ($oLinha->transporte_escolar_publico == "") {
      $oLinha->poder_publico_transporte_escolar = "0";
    }
    $oDaoAluno->ed47_c_transporte = "{$oLinha->poder_publico_transporte_escolar}";
    $oDaoAluno->ed47_i_codigo     = $this->getCodigoAluno();
    $oDaoAluno->alterar($this->getCodigoAluno());
    if ($oDaoAluno->erro_status == '0') {
       throw new Exception("Erro na alteração dos dados adicionais do aluno. Erro da classe: ".$oDaoAluno->erro_msg);
    }

    $oDaoAlunoCensoTransporte = db_utils::getDao("alunocensotipotransporte");
    $oDaoAlunoCensoTransporte->excluir(null, "ed311_aluno={$this->getCodigoAluno()}");
    if ($oDaoAlunoCensoTransporte->erro_status == 0) {
      throw new Exception("Erro na alteração dos dados de transportes do aluno. Erro da classe: ".$oDaoAluno->erro_msg);
    }
    /**
     * Atualizamos os transportes utilizados pelos alunos para a locomoção até a escola.
     */
    $aTransportes = array();
    $oLinha->rodoviario_vans_kombi                    == 1 ? $aTransportes[] =  1 : '';
    $oLinha->rodoviario_microonibus                   == 1 ? $aTransportes[] =  2 : '';
    $oLinha->rodoviario_onibus                        == 1 ? $aTransportes[] =  3 : '';
    $oLinha->rodoviario_bicicleta                     == 1 ? $aTransportes[] =  4 : '';
    $oLinha->rodoviario_tracao_animal                 == 1 ? $aTransportes[] =  5 : '';
    $oLinha->rodoviario_outro                         == 1 ? $aTransportes[] =  6 : '';
    $oLinha->aquaviario_embarcacao_5_pessoas          == 1 ? $aTransportes[] =  7 : '';
    $oLinha->aquaviario_embarcacao_5_a_15_pessoas     == 1 ? $aTransportes[] =  8 : '';
    $oLinha->aquaviario_embarcacao_15_a_35_pessoas    == 1 ? $aTransportes[] =  9 : '';
    $oLinha->aquaviario_embarcacao_mais_de_35_pessoas == 1 ? $aTransportes[] = 10 : '';
    $oLinha->ferroviario_trem_metro                   == 1 ? $aTransportes[] = 11 : '';

    foreach ($aTransportes as $iTipoTransporte) {

      $oDaoAlunoCensoTransporte->ed311_aluno               = $this->getCodigoAluno();
      $oDaoAlunoCensoTransporte->ed311_censotipotransporte = $iTipoTransporte;
      $oDaoAlunoCensoTransporte->incluir(null);
      if ($oDaoAlunoCensoTransporte->erro_status == 0) {
        throw new Exception("Erro na alteração dos dados de transportes do aluno. Erro da classe: ".$oDaoAluno->erro_msg);
      }
    }
  }
  
  /**
   * Retorna os recursos para avaliacao vinculados ao aluno
   * @return array
   */
  public function getRecursosAvaliacao() {
    
    $oDaoAlunoRecursoAvaliacaoInep     = db_utils::getDao("alunorecursosavaliacaoinep");
    $sWhereAlunoRecursoAvaliacaoInep   = "ed327_aluno = {$this->iCodigoAluno}";
    $sCamposAlunoRecursoAvaliacaoInep  = "alunorecursosavaliacaoinep.ed327_sequencial as codigo_aluno_recurso";
    $sCamposAlunoRecursoAvaliacaoInep .= ", recursosavaliacaoinep.ed326_sequencial as codigo_recurso_avaliacao";
    $sCamposAlunoRecursoAvaliacaoInep .= ", recursosavaliacaoinep.ed326_descricao as descricao_recurso_avaliacao";
    $sCamposAlunoRecursoAvaliacaoInep .= ", aluno.ed47_i_codigo as codigo_aluno";
    $sSqlAlunoRecursoAvaliacaoInep     = $oDaoAlunoRecursoAvaliacaoInep->sql_query(null, 
                                                                                   $sCamposAlunoRecursoAvaliacaoInep, 
                                                                                   null, 
                                                                                   $sWhereAlunoRecursoAvaliacaoInep);
    $rsAlunoRecursoAvaliacaoInep      = $oDaoAlunoRecursoAvaliacaoInep->sql_record($sSqlAlunoRecursoAvaliacaoInep);
    $iTotalAlunoRecursoAvaliacaoInep  = $oDaoAlunoRecursoAvaliacaoInep->numrows;
    
    $aRecursosAvaliacaoInep = array();
    if ( $iTotalAlunoRecursoAvaliacaoInep > 0 ) {
      
      for ( $iContador = 0; $iContador < $iTotalAlunoRecursoAvaliacaoInep; $iContador++ ) {
        
        $oDadosAlunoRecursoAvaliacao = db_utils::fieldsMemory($rsAlunoRecursoAvaliacaoInep, $iContador);
        $aRecursosAvaliacaoInep[$oDadosAlunoRecursoAvaliacao->codigo_recurso_avaliacao] = $oDadosAlunoRecursoAvaliacao;
      }
    }
    
    return $aRecursosAvaliacaoInep;
  }
  
  public function registroDocumentacaoValido( $oAlunos ) {
    
    $oRetorno                = new stdClass();
    $oRetorno->lDadosValidos = true;
    $oRetorno->sMsgErro      = "";
    $iSituacaoDocumentacao   = 0;
    
    foreach ( $oAlunos->registro80 as $oRegistro80 ) {
      
      if ( isset( $oRegistro80->ed47_situacaodocumentacao ) ) {
        $iSituacaoDocumentacao = $oRegistro80->ed47_situacaodocumentacao;
      }
    }
    
    if ( $iSituacaoDocumentacao == 0 ) {
      
      if (
           empty( $oAlunos->registro70->numero_identidade )                &&
           empty( $oAlunos->registro70->complemento_identidade )           &&
           empty( $oAlunos->registro70->orgao_emissor_identidade )         &&
           empty( $oAlunos->registro70->uf_identidade )                    &&
           empty( $oAlunos->registro70->data_expedicao_identidade )        &&
           empty( $oAlunos->registro70->certidao_civil )                   &&
           empty( $oAlunos->registro70->tipo_certidao_civil )              &&
           empty( $oAlunos->registro70->numero_termo )                     &&
           empty( $oAlunos->registro70->folha )                            &&
           empty( $oAlunos->registro70->livro )                            &&
           empty( $oAlunos->registro70->data_emissao_certidao )            &&
           empty( $oAlunos->registro70->uf_cartorio )                      &&
           empty( $oAlunos->registro70->municipio_cartorio )               &&
           empty( $oAlunos->registro70->codigo_cartorio )                  &&
           empty( $oAlunos->registro70->numero_matricula )                 &&
           empty( $oAlunos->registro70->numero_cpf )                       &&
           empty( $oAlunos->registro70->documento_estrangeiro_passaporte )
         ) {
        
        $oRetorno->sMsgErro       = "Aluno(a) {$oAlunos->registro60->codigo_aluno_entidade_escola} - {$oAlunos->registro60->nome_completo}";
        $oRetorno->sMsgErro      .= " - Data de Nascimento: {$oAlunos->registro60->data_nascimento}: \n";
        $oRetorno->sMsgErro      .= "Foi selecionada a opção 'Possui Documentação', porém sem nenhum documento preenchido.";
        $oRetorno->lDadosValidos  = false;
      }
    }
    
    return $oRetorno;
  }
}
?>