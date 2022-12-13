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
 * dac para webservices de aluno
 * Atua como um facade para a classe de Aluno.
 * as informações Retornadas são dos dados de Documentação, matriculas e notas dos alunos
 * @author dbseller
 *
 */
class AlunoWebservice {

  private $iCodigoAluno = null;
  private $oAluno       = null;
  private $oDadosAluno  = null;
  public function __construct($iCodigoAluno) {

    $this->oAluno = new Aluno($iCodigoAluno);
    $this->oDadosAluno = new stdClass();
  }

  /**
   * Retorna todos os Dados do aluno como um StdClass
   */
  public function getDados() {

    $oDadosAluno                               = new stdClass();
    $oDadosAluno->codigo_aluno                 = $this->oAluno->getCodigoAluno();
    $oDadosAluno->codigo_inep                  = $this->oAluno->getCodigoInep();
    $oDadosAluno->nome_aluno                   = utf8_encode($this->oAluno->getNome());
    $oDadosAluno->data_nascimento_aluno        = $this->oAluno->getDataNascimento();

    $oNaturalidade = $this->oAluno->getNaturalidade();
    $oDadosAluno->uf_naturalidade_aluno        = null;
    $oDadosAluno->municipio_naturalidade_aluno = null;

    if ( !is_null($oNaturalidade->getCodigo()) ) {
      $oDadosAluno->municipio_naturalidade_aluno = utf8_encode($this->oAluno->getNaturalidade()->getNome());
      $oDadosAluno->uf_naturalidade_aluno        = $this->oAluno->getNaturalidade()->getUF()->getUF();
    }

    $oDadosAluno->nacionalidade_aluno          = '';
    $oDadosAluno->pais_aluno                   = utf8_encode($this->oAluno->getPaisNaturalidade()->getDescricao());
    $oDadosAluno->endereco_residencia_aluno    = utf8_encode($this->oAluno->getEnderecoResidencia());
    $oDadosAluno->numero_residencia_aluno      = utf8_encode($this->oAluno->getNumeroResidencia());
    $oDadosAluno->bairro_residencia_aluno      = utf8_encode($this->oAluno->getBairroResidencia());
    $oDadosAluno->complemento_residencia_aluno = utf8_encode($this->oAluno->getComplementoResidencia());
    $oDadosAluno->zona_residencia_aluno        = utf8_encode($this->oAluno->getZonaResidencia());

    $oMunicipio = $this->oAluno->getMunicipioResidencia();
    $oDadosAluno->municipio_residencia_aluno   = null;
    $oDadosAluno->uf_residencia_aluno          = null;
    if ( !is_null($oMunicipio) ) {

      $oDadosAluno->municipio_residencia_aluno = utf8_encode($oMunicipio->getNome());
      $oDadosAluno->uf_residencia_aluno        = utf8_encode($oMunicipio->getUF()->getUF());
    }

    $oDadosAluno->cep_residencia_aluno         = $this->oAluno->getCepResidencia();
    $oDadosAluno->sexo_aluno                   = utf8_encode($this->oAluno->getSexo() == "M"?"MASCULINO":"FEMININO");
    $oDadosAluno->raca_aluno                   = utf8_encode($this->oAluno->getRaca());
    $sEstadoCivil                              = '';
    switch ($this->oAluno->getEstadoCivil()) {

      case 1:

        $sEstadoCivil = 'SOLTEIRO';
        break;

      case 2:

        $sEstadoCivil = 'CASADO';
        break;

      case 3:

        $sEstadoCivil = 'VIUVO';
        break;

      case 3:

        $sEstadoCivil = 'DIVORCIADO';
        break;
    }

    $sNacionalidade = 'NÃO INFORMADA';
    switch ($this->oAluno->getNacionalidade()) {

      case Aluno::NACIONALIDADE_BRASILEIRA:

        $sNacionalidade = 'BRASILEIRA';
        break;

      case Aluno::NACIONALIDADE_ESTRANGEIRA:

        $sNacionalidade = 'ESTRANGEIRA';
        break;

      case Aluno::NACIONALIDADE_NATURALIZADA:

        $sNacionalidade = 'BRASILEIRA NO EXTERIOR OU NATURALIZADO';
        break;

    }
    $oDadosAluno->nacionalidade_aluno    = utf8_encode($sNacionalidade);
    $oDadosAluno->estado_civil_aluno     = $sEstadoCivil;
    $oDadosAluno->telefone_aluno         = utf8_encode($this->oAluno->getNumeroTelefone());
    $oDadosAluno->telefone_celular_aluno = utf8_encode($this->oAluno->getNumeroCelular());
    $oDadosAluno->foto_aluno             = '';
    $oDadosAluno->none_foto_aluno        = '';
    $oDadosAluno->idade_aluno            = $this->oAluno->getIdadeNaData(date('Y-m-d'));

    db_inicio_transacao();
    $sCaminhoFoto = $this->oAluno->getFoto();
    db_fim_transacao();


    if ($sCaminhoFoto && file_exists($sCaminhoFoto)) {

      $oDadosAluno->none_foto_aluno  = $sCaminhoFoto;
      $oDadosAluno->foto_aluno       = base64_encode(file_get_contents($sCaminhoFoto));
    }

    $oDadosAluno->matriculas   = $this->getMatriculas();
    $oDadosAluno->outros_dados = $this->getOutrosDados();
    $oDadosAluno->documentos   = $this->getDocumentos();

    $oDadosEscola = new stdClass();

    /**
     * Busca os dados da escola do aluno referente a sua ultima matrícula
     */
    $oMatricula = MatriculaRepository::getUltimaMatriculaAluno( $this->oAluno );

    if ( !empty( $oMatricula ) ) {

      $oEscola                            = $oMatricula->getTurma()->getEscola();
      $oDadosEscola->sNome                = utf8_encode($oEscola->getNome());

      $aDiretores = $oEscola->getDiretor();

      for( $iContador = 0; $iContador < count($aDiretores); $iContador++ )  {

        $aDiretores[$iContador]->sNome     = utf8_encode($aDiretores[$iContador]->sNome);
        $aDiretores[$iContador]->sAtoLegal = utf8_encode($aDiretores[$iContador]->sAtoLegal);
        $aDiretores[$iContador]->sTurno    = utf8_encode($aDiretores[$iContador]->sTurno);
      }

      $oDadosEscola->aDiretores           = $aDiretores;
      $oDadosEscola->sUrl                 = utf8_encode($oEscola->getHomePage());
      $oDadosEscola->sEndereco            = utf8_encode($oEscola->getEndereco());
      $oDadosEscola->iNumeroEndereco      = $oEscola->getNumeroEndereco();
      $oDadosEscola->sComplementoEndereco = utf8_encode($oEscola->getComplementoEndereco());
      $oDadosEscola->sBairro              = utf8_encode($oEscola->getBairro());
      $oDadosEscola->sMunicipio           = utf8_encode($oEscola->getMunicipio());
      $oDadosEscola->sUf                  = utf8_encode($oEscola->getUf());
      $oDadosEscola->sEstado              = utf8_encode($oEscola->getEstado());
      $oDadosEscola->sCep                 = utf8_encode($oEscola->getCep());
      $oDadosEscola->sEmail               = utf8_encode($oEscola->getEmail());

      $aTelefones = $oEscola->getTelefones();

      for( $iContador = 0; $iContador < count($aTelefones); $iContador++ )  {

        $aTelefones[$iContador]->sObservacao   = utf8_encode($aTelefones[$iContador]->sObservacao);
        $aTelefones[$iContador]->sTipoTelefone = utf8_encode($aTelefones[$iContador]->sTipoTelefone);
      }

      $oDadosEscola->aTelefones = $aTelefones;
    }

    $oDadosAluno->oEscola = $oDadosEscola;

    return $oDadosAluno;
  }


  /**
   * Retorna todas as Matriculas de um Aluno
   */
  public function getMatriculas() {

    $aMatriculas = array();
    foreach ($this->oAluno->getMatriculas() as $oMatriculaAluno) {

      $oMatricula                   = new stdClass();
      $oMatricula->etapa_matricula  = utf8_encode($oMatriculaAluno->getEtapaDeOrigem()->getNome());
      $oMatricula->codigo_matricula = $oMatriculaAluno->getCodigo();
      $oMatricula->ano_matricula    = $oMatriculaAluno->getTurma()->getCalendario()->getAnoExecucao();
      $aMatriculas[]                = $oMatricula;
    }

    uasort($aMatriculas, 'ordernarMatriculas');
    return $aMatriculas;
  }

  /**
   * REtorna outros dados do aluno
   */
  protected function getOutrosDados() {

    $oOutrosDados                                   = new stdClass();
    $oOutrosDados->filiacao_aluno                   = '';
    $oOutrosDados->pai_aluno                        = '';
    $oOutrosDados->mae_aluno                        = '';
    $oOutrosDados->responsavel_aluno                = '';
    $oOutrosDados->email_responsavel_aluno          = '';
    $oOutrosDados->celular_responsavel_aluno        = '';
    $oOutrosDados->bolsa_familia_aluno              = '';
    $oOutrosDados->numero_nis_aluno                 = '';
    $oOutrosDados->transporte_publico_aluno         = '';
    $oOutrosDados->poder_publico_transporte         = '';
    $oOutrosDados->email_aluno                      = '';
    $oOutrosDados->profissao_aluno                  = '';
    $oOutrosDados->escolarizacao_outro_espaco_aluno = '';
    $oOutrosDados->data_cadastramento_aluno         = '';
    $oOutrosDados->ultima_alteracao_aluno           = '';
    $oOutrosDados->observacao_aluno                 = '';
    $oOutrosDados->contato_aluno                    = '';
    $oOutrosDados->local_procedencia                = '';
    $oOutrosDados->data_procedencia                 = '';
    $oDaoAluno       = new cl_aluno;
    $sSqlOutrosDados = $oDaoAluno->sql_query_file($this->oAluno->getCodigoAluno());
    $rsOutrosDados   = $oDaoAluno->sql_record($sSqlOutrosDados);
    if ($rsOutrosDados && $oDaoAluno->numrows > 0) {

      $oDadosAluno = db_utils::fieldsMemory($rsOutrosDados, 0);

      $sEscolarizacaoOutroEspaco = '';
      switch ($oDadosAluno->ed47_c_atenddifer) {

        case "1":

          $sEscolarizacaoOutroEspaco = "EM HOSPITAL";
          break;
        case '2':

          $sEscolarizacaoOutroEspaco = "EM DOMICÍLIO";
          break;

        default:

          $sEscolarizacaoOutroEspaco = "NÃO RECEBE";
          break;
      }
      $sTipoTransportePublico = 'NÃO INFORMADO';
      switch ($oDadosAluno->ed47_c_transporte) {

        case '1':

          $sTipoTransportePublico = 'ESTADUAL';
          break;

        case '2':

          $sTipoTransportePublico = "MUNICIPAL";
          break;
      }
      $oOutrosDados->filiacao_aluno            = utf8_encode($oDadosAluno->ed47_i_filiacao == "0"
                                                                          ? "NÃO DECLARADO/IGNORADO" : "PAI E/OU MÃE"
                                                            );
      $oOutrosDados->pai_aluno                 = utf8_encode($oDadosAluno->ed47_v_pai);
      $oOutrosDados->mae_aluno                 = utf8_encode($oDadosAluno->ed47_v_mae);
      $oOutrosDados->responsavel_aluno         = utf8_encode($oDadosAluno->ed47_c_nomeresp);
      $oOutrosDados->email_responsavel_aluno   = utf8_encode($oDadosAluno->ed47_c_emailresp);
      $oOutrosDados->celular_responsavel_aluno = utf8_encode($oDadosAluno->ed47_celularresponsavel);
      $oOutrosDados->bolsa_familia_aluno       = utf8_encode($oDadosAluno->ed47_c_bolsafamilia == 'S' ? 'SIM' : 'NÃO');
      $oOutrosDados->numero_nis_aluno          = $oDadosAluno->ed47_c_nis;

      $oOutrosDados->transporte_publico_aluno  = utf8_encode($oDadosAluno->ed47_i_transpublico == "0" ? "NÃO UTILIZA"
                                                                                                      : "UTILIZA"
                                                            );

      $oOutrosDados->poder_publico_transporte         = utf8_encode($sTipoTransportePublico);
      $oOutrosDados->email_aluno                      = utf8_encode($oDadosAluno->ed47_v_email);
      $oOutrosDados->profissao_aluno                  = utf8_encode($oDadosAluno->ed47_v_profis);
      $oOutrosDados->escolarizacao_outro_espaco_aluno = utf8_encode($sEscolarizacaoOutroEspaco);
      $oOutrosDados->data_cadastramento_aluno         = $oDadosAluno->ed47_d_cadast;
      $oOutrosDados->ultima_alteracao_aluno           = $oDadosAluno->ed47_d_ultalt;
      $oOutrosDados->observacao_aluno                 = utf8_encode($oDadosAluno->ed47_t_obs);
      $oOutrosDados->contato_aluno                    = utf8_encode($oDadosAluno->ed47_v_contato);


      $oOutrosDados->transportes_utilizados = array();
      $oDaoAlunoTransportes                 = new cl_alunocensotipotransporte();
      $sWhereTransportes                    = "ed311_aluno = {$this->oAluno->getCodigoAluno()}";
      $sSqlTransportes                      = $oDaoAlunoTransportes->sql_query_tipo_transporte(null,
                                                                                               "ed312_descricao",
                                                                                               "ed312_descricao",
                                                                                               $sWhereTransportes
                                                                                              );
      $rsTransportes = $oDaoAlunoTransportes->sql_record($sSqlTransportes);
      if ($rsTransportes && $oDaoAlunoTransportes->numrows > 0) {

        for ($iTransporte = 0; $iTransporte < $oDaoAlunoTransportes->numrows; $iTransporte++) {

          $sDescricaoTransporte = db_utils::fieldsMemory($rsTransportes, $iTransporte)->ed312_descricao;

          $oOutrosDados->transportes_utilizados[] = utf8_decode($sDescricaoTransporte);
        }
      }
    }

    $oDaoPrimeiraMatricula = new cl_alunoprimat();
    $sWhereProcedencia     = "ed76_i_aluno = {$this->oAluno->getCodigoAluno()}";
    $sCamposProcendencia   = "case when ed76_c_tipo = 'M' then escola.ed18_c_nome else ed82_c_nome end as nome_escola,";
    $sCamposProcendencia  .= "ed76_d_data";
    $sSqlProcedencia       = $oDaoPrimeiraMatricula->sql_query(null, $sCamposProcendencia, null, $sWhereProcedencia);
    $rsProcedencia        = $oDaoPrimeiraMatricula->sql_record($sSqlProcedencia);
    if ($rsProcedencia && $oDaoPrimeiraMatricula->numrows > 0) {

      $oDadosProcedencia               = db_utils::fieldsMemory($rsProcedencia, 0);
      $oOutrosDados->local_procedencia = utf8_encode($oDadosProcedencia->nome_escola);
      $oOutrosDados->data_procedencia  = utf8_encode($oDadosProcedencia->ed76_d_data);
    }
    return $oOutrosDados;
  }

  /**
   * retorna os documentos do cidadao
   */
  protected function getDocumentos() {

    $oDocumentos                                     = new stdClass();
    $oDocumentos->certidao_nascimento                = new stdClass();
    $oDocumentos->certidao_nascimento->tipo_certidao = '';
    $oDocumentos->certidao_nascimento->numero_termo  = '';
    $oDocumentos->certidao_nascimento->livro         = '';
    $oDocumentos->certidao_nascimento->folha         = '';
    $oDocumentos->certidao_nascimento->data_emissao  = '';
    $oDocumentos->certidao_nascimento->cartorio      = '';
    $oDocumentos->certidao_nascimento->municipio     = '';
    $oDocumentos->certidao_nascimento->uf            = '';
    $oDocumentos->certidao_nascimento->matricula     = '';


    $oDocumentos->identidade                 = new stdClass();
    $oDocumentos->identidade->numero         = '';
    $oDocumentos->identidade->complemento    = '';
    $oDocumentos->identidade->uf_emissao     = '';
    $oDocumentos->identidade->orgao_emissor  = '';
    $oDocumentos->identidade->data_expedicao = '';

    $oDocumentos->cnh                  = new stdClass();
    $oDocumentos->cnh->numero          = '';
    $oDocumentos->cnh->categoria       = '';
    $oDocumentos->cnh->data_emissao    = '';
    $oDocumentos->cnh->primeira_cnh    = '';
    $oDocumentos->cnh->data_vencimento = '';

    $oDocumentos->cpf        = '';
    $oDocumentos->passaporte = '';
    $oDaoAluno      = new cl_aluno;

    $sSqlDocumentos = $oDaoAluno->sql_query_file($this->oAluno->getCodigoAluno());
    $rsDocumentos   = $oDaoAluno->sql_record($sSqlDocumentos);
    if ($rsDocumentos && $oDaoAluno->numrows > 0) {

      $oDadosDocumento = db_utils::fieldsMemory($rsDocumentos, 0);
      $sTipoCertidao = "Não Informado";
      switch ($oDadosDocumento->ed47_c_certidaotipo) {

        case 'C':

          $sTipoCertidao = "CASAMENTO";
          break;
        case 'N':

          $sTipoCertidao = "NASCIMENTO";
          break;
      }
      $sCartorio          = '';
      $sMatricula         = "Não Informado ";
      if ($oDadosDocumento->ed47_i_censocartorio != "") {

        $oCartorio = CensoCartorioRepository::getCensoCartorioByCodigo($oDadosDocumento->ed47_i_censocartorio);
        $sCartorio = $oCartorio->getNome();

      }
      if ($oDadosDocumento->ed47_i_censomuniccert != "") {

        $oMunicipioCartorio = CensoMunicipioRepository::getMunicipioByCodigo($oDadosDocumento->ed47_i_censomuniccert);
        $oDocumentos->certidao_nascimento->municipio     = utf8_encode($oMunicipioCartorio->getNome());
        $oDocumentos->certidao_nascimento->uf            = utf8_encode($oMunicipioCartorio->getUF()->getUF());
      }

      $oDocumentos->certidao_nascimento->tipo_certidao = utf8_encode($sTipoCertidao);
      $oDocumentos->certidao_nascimento->numero_termo  = utf8_encode($oDadosDocumento->ed47_c_certidaonum);
      $oDocumentos->certidao_nascimento->livro         = utf8_encode($oDadosDocumento->ed47_c_certidaolivro);
      $oDocumentos->certidao_nascimento->folha         = utf8_encode($oDadosDocumento->ed47_c_certidaofolha);
      $oDocumentos->certidao_nascimento->data_emissao  = utf8_encode($oDadosDocumento->ed47_c_certidaodata);
      $oDocumentos->certidao_nascimento->cartorio      = utf8_encode($sCartorio);
      if (!empty($oDadosDocumento->ed47_certidaomatricula)) {

        $sMatricula  = substr($oDadosDocumento->ed47_certidaomatricula, 0, 6)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 6, 2)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 8, 2)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 10, 4)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 14, 1)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 15, 5)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 20, 3)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 23, 7)." ";
        $sMatricula .= substr($oDadosDocumento->ed47_certidaomatricula, 30, 2);
      }
      $oDocumentos->certidao_nascimento->matricula = utf8_encode($sMatricula);

      /**
       * Dados da carteira de identidade
       */
      $sUfIdentidade = '';
      if (!empty($oDadosDocumento->ed47_i_censoufident)) {
        $sUfIdentidade = CensoUFRepository::getEstadoPorCodigo($oDadosDocumento->ed47_i_censoufident)->getUF();
      }
      $oDocumentos->identidade->numero         = utf8_encode($oDadosDocumento->ed47_v_ident);
      $oDocumentos->identidade->complemento    = utf8_encode($oDadosDocumento->ed47_v_identcompl);
      $oDocumentos->identidade->uf_emissao     = utf8_encode($sUfIdentidade);
      $oDocumentos->identidade->data_expedicao = utf8_encode($oDadosDocumento->ed47_d_identdtexp);
      if (!empty($oDadosDocumento->ed47_i_censoorgemissrg)) {

        $oEmissor = new CensoOrgaoEmissorRG($oDadosDocumento->ed47_i_censoorgemissrg);
        $oDocumentos->identidade->orgao_emissor  = utf8_encode($oEmissor->getNome());
      }


      /**
       * Dados da CNH
       */
      $oDocumentos->cnh->numero          = utf8_encode($oDadosDocumento->ed47_v_cnh);
      $oDocumentos->cnh->categoria       = utf8_encode($oDadosDocumento->ed47_v_categoria);
      $oDocumentos->cnh->data_emissao    = utf8_encode($oDadosDocumento->ed47_d_dtemissao);
      $oDocumentos->cnh->primeira_cnh    = utf8_encode($oDadosDocumento->ed47_d_dthabilitacao);
      $oDocumentos->cnh->data_vencimento = utf8_encode($oDadosDocumento->ed47_d_dtvencimento);

      /**
       * cpf e passaporte
       */
      $oDocumentos->cpf        = utf8_encode($oDadosDocumento->ed47_v_cpf);
      $oDocumentos->passaporte = utf8_encode($oDadosDocumento->ed47_c_passaporte);
    }
    return $oDocumentos;
  }
}

/**
 * Ordena as matriculas pelo ano decrescente
 * @param unknown $oMatriculaAtual
 * @param unknown $oProximaMatricula
 * @return number
 */
function ordernarMatriculas($oMatriculaAtual, $oProximaMatricula) {

  if ($oMatriculaAtual->ano_matricula == $oProximaMatricula->ano_matricula) {
    return 0;
  }
  return ($oMatriculaAtual->ano_matricula > $oProximaMatricula->ano_matricula) ? -1 : 1;
}