<?php

/**
 *  Classe de geração do arquivo da DIRF para utilização entre 2012 e 2015
 *
 *  @package Pessoal
 *  @subpackage Arquivos/Dirf
 *  @author Rafael Nery <rafael.nery@dbseller.com.br>
 *  @version $Id: ArquivoDirf2012.model.php,v 1.13 2017/02/10 16:32:04 dbiuri Exp $
 */
class ArquivoDirf2012 {

  protected $aBaseValoresMensais = array(
     1 => "",
     2 => "",
     3 => "",
     4 => "",
     5 => "",
     6 => "",
     7 => "",
     8 => "",
     9 => "",
    10 => "",
    11 => "",
    12 => "",
    13 => ""
  );

  protected $aMeses = array(
    1 => "janeiro",
    2 => "fevereiro",
    3 => "marco",
    4 => "abril",
    5 => "maio",
    6 => "junho",
    7 => "julho",
    8 => "agosto",
    9 => "setembro",
    10 => "outubro",
    11 => "novembro",
    12 => "dezembro",
    13 => "decimo_terceiro",
  );
  protected $oGerador;

  public function __construct(db_layouttxt $oLayout) {
    $this->oLayout = $oLayout;
  }

  public function setGeracaoArquivo(GeracaoArquivoTXTDirf $oGerador) {
    $this->oGerador = $oGerador;
  }
  /**
   *  Escreve linha "Dirf"
   *  @return
   */
  public function escreverLinhaHeaderArquivo() {

    /**
     * escrevemos o header do txt
     */
    $this->oLayout->setCampoTipoLinha(1);
    $this->oLayout->setCampoIdentLinha("Dirf");
    $this->oLayout->setCampo("identificador_registro", 'Dirf');
    $this->oLayout->setCampo("ano_referencia", $this->oGerador->getAno() + 1);
    $this->oLayout->setCampo("ano_calendario", $this->oGerador->getAno());

    $sRetificadora = 'N';

    if ($this->oGerador->getTipoDeclaracao() == "R") {
      $sRetificadora = 'S';
    }

    $this->oLayout->setCampo("idetificador_retificadora", $sRetificadora);
    $this->oLayout->setCampo("numero_recibo", $this->oGerador->getNumeroRecibo());
    $this->oLayout->setCampo("identificador_estrutura_layout", $this->oGerador->getDirf()->getCodigoArquivo());
    return $this->oLayout->geraDadosLinha();
  }

  /**
   *  Escreve linha "RESPO"
   */
  public function escreverLinhaResponsavel() {

    $this->oLayout->setCampoTipoLinha(3);
    $this->oLayout->setCampoIdentLinha("RESPO");
    $this->oLayout->setCampo("identificador_registro", 'RESPO');
    $this->oLayout->setCampo("cpf", $this->oGerador->getCpfResponsavel());
    $this->oLayout->setCampo("nome",
      urldecode(
        db_stdClass::db_stripTagsJson(
          $this->oGerador->getNomeResponsavel()
        )
      )
    );
    $this->oLayout->setCampo("ddd", $this->oGerador->getDDDResponsavel());
    $this->oLayout->setCampo("telefone", $this->oGerador->getFoneResponsavel());
    $this->oLayout->geraDadosLinha();
  }

  /**
   *  Escreve a secção DECPJ com suas respctivas subseções de pessoas fisica e juridica e seus IDREC
   *
   *  @param  String $sNomeInstituicao
   *  @return void
   */
  public function escreverSecaoDeclarantePessoaJuridica($sNomeInstituicao) {

    $this->oLayout->setCampoTipoLinha(3);
    $this->oLayout->setCampoIdentLinha("DECPJ");
    $this->oLayout->setCampo("identificador_registro", 'DECPJ');
    $this->oLayout->setCampo("responsavel_perante_cnpj", $this->oGerador->getCpfResponsavelCNPJ());
    $this->oLayout->setCampo("cnpj", $this->oGerador->getCnpj());
    $this->oLayout->setCampo("nome_empresarial", $sNomeInstituicao);

    if ($this->oGerador->getNumeroANS() > 0) {
      $this->oLayout->setCampo("plano_privado_assistencia", "S");
    }    
    $this->oLayout->geraDadosLinha();

    $aLinhasDirf = $this->oGerador->getRegistros();

    foreach ($aLinhasDirf as $oLinhaDirf) {

      if ($oLinhaDirf->receita == "1889") { //Receita do RRA
        continue;
      }

      $this->escreverLinhaReceita($oLinhaDirf->receita);
      $this->escreverLinhasPessoaFisica($oLinhaDirf->fisica);
      $this->escreverLinhasPessoaJuridica($oLinhaDirf->juridica);
    }
  }

  /**
   *  Escreve seção "PSE" Plano de Assistencia a Saude empresarial e seus "OPSE" Operadora Plano de Saude e "TPSE" - Titular do Plano de Saude
   * @throws \BusinessException
   * @throws \DBException
   */
  public function escreverSecaoANS() {

    /**
     * geramos as linhas do plano de saude
     */
    if (trim($this->oGerador->getNumeroANS()) != "" || trim($this->oGerador->getNumeroANS2()) != "") {

      $this->oLayout->setCampoTipoLinha(3);
      $this->oLayout->setCampoIdentLinha("PSE");
      $this->oLayout->setCampo("identificador_registro", 'PSE');
      $this->oLayout->geraDadosLinha();

      if (trim($this->oGerador->getNumeroANS()) != "") {

        $oDaoCgm   = db_utils::getDao("cgm");
        $sSqlNome  = $oDaoCgm->sql_query_file($this->oGerador->getCcgmSaude(), "z01_nome, z01_cgccpf");
        $rsNome    = db_query($sSqlNome);

        if (!$rsNome) {
          throw new DBException("Erro ao buscar os dados da Operadora de Plano de Saude({$this->oGerador->getCcgmSaude()}).");
        }

        if (pg_num_rows($rsNome) == 0) {
          throw new BusinessException("Nenhuma informação sobre Operadora de Plano de Saude foi encontrada.");
        }

        $oOperador = db_utils::fieldsMemory($rsNome, 0);

        $this->oLayout->setCampoTipoLinha(3);
        $this->oLayout->setCampoIdentLinha("OPSE");
        $this->oLayout->setCampo("identificador_registro", 'OPSE');
        $this->oLayout->setCampo("cnpj", str_pad($oOperador->z01_cgccpf, 14, "0", STR_PAD_LEFT));
        $this->oLayout->setCampo("nome", $oOperador->z01_nome);
        $this->oLayout->setCampo("registro_ans", str_pad($this->oGerador->getNumeroANS(), 6, "0", STR_PAD_LEFT));
        $this->oLayout->geraDadosLinha();

        /**
         * geramos todas as pessoas que possuem valor do plano de saude maior que zero.
         */
        $aLinhasDirf = $this->oGerador->getRegistros();

        foreach ($aLinhasDirf as $oLinhaDirf) {

          foreach ($oLinhaDirf->fisica as $oPessoaFisica) {

            if ($oPessoaFisica->totalsaude1 > 0) {

              $nValorAno = db_formatar(str_replace(',','',str_replace('.','',
                trim(db_formatar($oPessoaFisica->totalsaude1,'f')))),'s','0',13,'e',2);
              $this->oLayout->setCampoTipoLinha(3);
              $this->oLayout->setCampoIdentLinha("TPSE");
              $this->oLayout->setCampo("identificador_registro", 'TPSE');
              $this->oLayout->setCampo("cnpj", str_pad($oPessoaFisica->cpf, 11, "0", STR_PAD_LEFT));
              $this->oLayout->setCampo("nome", $oPessoaFisica->nome);
              $this->oLayout->setCampo("valor_ano", $nValorAno);
              $this->oLayout->geraDadosLinha();
            }
          }
        }
      }

      if (trim($this->oGerador->getNumeroANS2()) != "") {

        $oDaoCgm   = db_utils::getDao("cgm");
        $sSqlNome  = $oDaoCgm->sql_query_file($this->oGerador->getCcgmSaude2(), "z01_nome, z01_cgccpf");
        $rsNome    = db_query($sSqlNome);

        if (!$rsNome) {
          throw new DBException("Erro ao buscar os dados da Operadora de Plano de Saude({$this->oGerador->getCcgmSaude2()}).");
        }

        if (pg_num_rows($rsNome) == 0) {
          throw new BusinessException("Nenhuma informação sobre Operadora de Plano de Saude foi encontrada.");
        }

        $oOperador = db_utils::fieldsMemory($rsNome, 0);

        $this->oLayout->setCampoTipoLinha(3);
        $this->oLayout->setCampoIdentLinha("OPSE");
        $this->oLayout->setCampo("identificador_registro", 'OPSE');
        $this->oLayout->setCampo("cnpj", str_pad($oOperador->z01_cgccpf, 14, "0", STR_PAD_LEFT));
        $this->oLayout->setCampo("nome", $oOperador->z01_nome);
        $this->oLayout->setCampo("registro_ans", str_pad($this->oGerador->getNumeroANS2(), 6, "0", STR_PAD_LEFT));
        $this->oLayout->geraDadosLinha();

        /**
         * geramos todas as pessoas que possuem valor do plano de saude maior que zero.
         */
        foreach ($aLinhasDirf as $oLinhaDirf) {

          foreach ($oLinhaDirf->fisica as $oPessoaFisica) {

            if ($oPessoaFisica->totalsaude2 > 0) {

              $nValorAno = db_formatar(str_replace(',','',str_replace('.','',
                trim(db_formatar($oPessoaFisica->totalsaude2,'f')))),'s','0',13,'e',2);
              $this->oLayout->setCampoTipoLinha(3);
              $this->oLayout->setCampoIdentLinha("TPSE");
              $this->oLayout->setCampo("identificador_registro", 'TPSE');
              $this->oLayout->setCampo("cnpj", str_pad($oPessoaFisica->cpf, 11, "0", STR_PAD_LEFT));
              $this->oLayout->setCampo("nome", $oPessoaFisica->nome);
              $this->oLayout->setCampo("valor_ano", $nValorAno);
              $this->oLayout->geraDadosLinha();
            }
          }
        }
      }
    }

  }

  /**
   *  Escreve linha "INF" informações complementares para o comprovante de rendimentos
   *  @return void
   */
  public function escreverInformacoesComplementares() {

    $aLinhasDirf = $this->oGerador->getRegistros();

    $aInformacoesComplementares = array();

    foreach ($aLinhasDirf as $oLinhaDirf) {

      foreach ($oLinhaDirf->fisica as $oPessoaFisica) {
        if ($oPessoaFisica->informacao_complementar != '') {
          $aInformacoesComplementares[$oPessoaFisica->cpf] = substr($oPessoaFisica->informacao_complementar, 0, 200);
        }
      }
    }
    ksort($aInformacoesComplementares);
    $aInformacoesJaImpressas = array();

    foreach ($aInformacoesComplementares as $cpf  => $informacao ) {

      $oPessoaFisica->identificador_registro  = 'INF';
      $oPessoaFisica->cpf                     = $cpf;
      $oPessoaFisica->Pipe                    = '';
      $oPessoaFisica->informacao_complementar = substr($informacao, 0, 200);
      $this->oLayout->setByLineOfDBUtils($oPessoaFisica, 3, 'INF');
    }

  }

  /**
   *  Escreve linha "FIMDirf"
   *
   *  @return void
   */
  public function escreverFooterArquivo() {

    $this->oLayout->setCampoTipoLinha(4);
    $this->oLayout->setCampoIdentLinha("FIMDirf");
    $this->oLayout->setCampo("identificador_registro", 'FIMDirf');
    $this->oLayout->geraDadosLinha();
  }

  /**
   *  Escreve a subsecao "BPFDEC"(Beneficiario Pessoa Fisica do Declarante) e seus "RTRT"(rendimentos tributaveis)
   *  @param  array $aDadosPessoaFisica
   *  @return void
   */
  public function escreverLinhasPessoaFisica(array $aDadosPessoaFisica) {

    foreach ($aDadosPessoaFisica as $oPessoaFisica) {
      
      $this->oLayout->setCampoTipoLinha(3);
      $this->oLayout->setCampoIdentLinha("BPFDEC");
      $this->oLayout->setCampo("identificador_registro", 'BPFDEC');
      $this->oLayout->setCampo("nome", $oPessoaFisica->nome);
      $this->oLayout->setCampo("cpf",  $oPessoaFisica->cpf);
      $this->oLayout->setCampo("data_laudo",  $oPessoaFisica->datalaudo);
      $this->oLayout->geraDadosLinha();

      /**
       * carregamos as informações dos pagamentos
       */
      foreach ($oPessoaFisica->pagamentos as $iTipo => $oPagamento) {

        /**
         *  Se for algum dos tipos para RRA passa para o próximo
         */
        if (in_array($iTipo, array(17,18,19,20,21,22,23))) {
          continue;
        }

        $this->oLayout->setCampoTipoLinha(3);
        $this->oLayout->setCampoIdentLinha("RTRT");
        $aSiglas        = $this->oGerador->getSiglas();
        $iSiglaRegistro = $aSiglas[$iTipo];

        $this->oLayout->setCampo("idetificador_registro", $iSiglaRegistro);

        /**
         * escreve os meses com cada valor
         */
        for ($iMes = 1; $iMes <= 13; $iMes++) {

          $aMes[$iMes] = '';

          foreach ($oPagamento as $oMes) {

            if ($oMes->rh98_mes == $iMes) {

              $nValorDeducao65 = 0;

              if ($oMes->rh98_rhdirftipovalor == 1) {
                $nValorDeducao65 = $this->oGerador->getDirf()->getValorDeducaoRIP65($iMes,$oPessoaFisica->pagamentos);
              }

              $nValorLancar = $oMes->valor > 0 ? $oMes->valor : 0;
              $aMes[$iMes] = db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($nValorLancar,'f')))),'s','0',8,'e',2);
            }
          }
          $this->oLayout->setCampo($this->aMeses[$iMes], $aMes[$iMes]);
        }
        $this->oLayout->geraDadosLinha();
      }

      if ($oPessoaFisica->previdencia_privada) {

        foreach ($oPessoaFisica->previdencia_privada as $iCgm => $aMeses) {

          $oEmpresa = CgmRepository::getByCodigo($iCgm);
          $this->oLayout->setCampoTipoLinha(3);
          $this->oLayout->setCampoIdentLinha("INFPC");
          $this->oLayout->setCampo("identificador_registro", "INFPC");
          $this->oLayout->setCampo("cnpj", $oEmpresa->getCnpj());
          $this->oLayout->setCampo("nome_empresarial",  $oEmpresa->getNome());
          $this->oLayout->geraDadosLinha();

          $this->oLayout->setCampoTipoLinha(3);
          $this->oLayout->setCampoIdentLinha("RTRT");
          $this->oLayout->setCampo("idetificador_registro", "RTPP");
          for ($iMes = 1; $iMes <= 13; $iMes++) {

            $mes = '';
            if (isset($aMeses[$iMes])) {
              $mes = $aMeses[$iMes];
            }            
            $this->oLayout->setCampo($this->aMeses[$iMes], self::converterValor($mes));
          }
          $this->oLayout->geraDadosLinha();
        }

      }

      /**
       * escrevemos os dados dos pensionistas
       */
      uasort($oPessoaFisica->pensionistas, function($primeiro, $proximo) {
         return strcasecmp($primeiro->cpf, $proximo->cpf);
      });
      foreach ($oPessoaFisica->pensionistas as $oPensionista) {

        $this->oLayout->setCampoTipoLinha(3);
        $relacao_depedencia = str_pad($oPensionista->relacao_dependencia, 2, "0", STR_PAD_LEFT);
        
        if ($oPensionista->relacao_dependencia == 0) {
          $relacao_depedencia = '';
        }
        $sDataPensionista = implode("", (explode("-", $oPensionista->data_nascimento)));
        $this->oLayout->setCampoIdentLinha("INFPA");
        $this->oLayout->setCampo('cpf', $oPensionista->cpf);
        $this->oLayout->setCampo('nome', $oPensionista->nome);
        $this->oLayout->setCampo('data_nascimento', $sDataPensionista);
        $this->oLayout->setCampo('relacao_dependencia', $relacao_depedencia);
        $this->oLayout->setCampo("idetificador_registro", 'INFPA');
        $this->oLayout->geraDadosLinha();
        $this->oLayout->setCampoTipoLinha(3);
        $this->oLayout->setCampoIdentLinha("RTRT");
        $this->oLayout->setCampo("idetificador_registro", "RTPA");
        for ($iMes = 1; $iMes <= 13; $iMes++) {

          $mes = '';
          if (isset($oPensionista->valores[$iMes])) {
            $mes = $oPensionista->valores[$iMes];
          }
          $this->oLayout->setCampo($this->aMeses[$iMes], self::converterValor($mes));
        }
        $this->oLayout->geraDadosLinha();
      }

      /**
       * Outros dados.
       */
      if($oPessoaFisica->totaloutros > 0){
        $this->escreverOutrosDados(self::converterValor($oPessoaFisica->totaloutros, 13));
      }
    }
  }

  /**
   *  Escreve subseção "BPJDEC" Beneficiario pessoa juridica e seus "RTRT"(Rendimentos Tributáveis)
   *
   *  @param  array $aDadosPessoaJuridica [description]
   *  @return bool                        [description]
   */
  public function escreverLinhasPessoaJuridica(array $aDadosPessoaJuridica) {

    foreach ($aDadosPessoaJuridica as $oPessoaFisica) {

      $this->oLayout->setCampoTipoLinha(3);
      $this->oLayout->setCampoIdentLinha("BPJDEC");
      $this->oLayout->setCampo("identificador_registro", 'BPJDEC');
      $this->oLayout->setCampo("nome", $oPessoaFisica->nome);
      $this->oLayout->setCampo("cnpj", $oPessoaFisica->cnpj);
      $this->oLayout->geraDadosLinha();

      /**
       * carregamos as informações dos pagamentos
       */
      foreach ($oPessoaFisica->pagamentos as $iTipo => $oPagamento) {

        $aSiglas        = $this->oGerador->getSiglas();
        $iSiglaRegistro = $aSiglas[$iTipo];

        $this->oLayout->setCampoTipoLinha(3);
        $this->oLayout->setCampoIdentLinha("RTRT");
        $this->oLayout->limpaCampos();
        $this->oLayout->setCampo("idetificador_registro", $iSiglaRegistro);

        /**
         * escreve os meses com cada valor
         */
        foreach ($oPagamento as $oMes) {
          $this->oLayout->setCampo($this->aMeses[$oMes->rh98_mes],db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($oMes->valor,'f')))),'s','0',8,'e',2));
        }
        $this->oLayout->geraDadosLinha();
      }
    }
  }

  /**
   *  Escreve linha "RIO"(Rendimentos Isentos - Outros)
   *  @param  number $nValorAno Valor acumulado de outras informações no ano.
   *  @return void
   */
  public function escreverOutrosDados($nValorAno) {

    $this->oLayout->setCampoTipoLinha(3);
    $this->oLayout->setCampoIdentLinha("RIO");
    $this->oLayout->setCampo("identificador_registro", "RIO");
    $this->oLayout->setCampo("valor_anual", $nValorAno);
    $this->oLayout->setCampo("descricao_rend_isentos", "");
    $this->oLayout->geraDadosLinha();
  }

  /**
   *  Escreve seção do RRA
   *
   *  return void
   */
  public function escreverSecaoRRA() {

    /**
     * Após geração a estrutura ficará dessa forma
     *
     * RRA           - Rendimentos recebidos acumuladamente
     *   IDREC       - Identificação do código de receita
     *     BPFRRA    - Beneficiário pessoa física do rendimento recebido acumuladamente
     *       RTRT    - Rendimentos Tributáveis - Rendimento Tributável
     *       RTPO    - Rendimentos Tributáveis - Dedução - Previdência Oficial
     *       RTPA    - Rendimentos Tributáveis - Dedução - Pensão Alimentícia
     *       RTIRF   - Rendimentos Tributáveis - Imposto sobre a Renda Retido na Fonte
     *       RIMOG   - Rendimentos Isentos - Pensão, Aposentadoria ou Reforma por Moléstia Grave
     *       DAJUD   - Despesa com ação judicial
     *       QTMESES - Quantidade de meses
     */
    $aReceitas = $this->oGerador->getBeneficiariosRRAPorReceita();

    /**
     *  Caso não existam registros para por aqui
     */
    if (empty($aReceitas)) {
      return;
    }

    $this->escreverLinhaRRA();

    /**
     *  Percorre as receitas encontradas na geração da DIRF
     */
    foreach ($aReceitas as $iCodigoReceita => $aBeneficiarios) {

      $this->escreverLinhaReceita($iCodigoReceita);

      /**
       *  Percorre os beneficiarios da Receita
       */
      foreach ($aBeneficiarios as $oBeneficiario) {

        $this->escreverLinhaBeneficiarioRRA($oBeneficiario->nome, $oBeneficiario->cpf);
        /**
         *  Remove nome e cpf para que possa percorrer as propriedades
         */
        unset($oBeneficiario->nome, $oBeneficiario->cpf);

        /**
         *  Percorre as propriedades do objeto com as registros mensais
         */
        foreach ($oBeneficiario as $sTipoRegistro => $aCompetencias) {

          if (empty($aCompetencias)) {
            continue;
          }
          if ($sTipoRegistro == 'RTPA') {

            foreach ($aCompetencias as $oPensionista) {

              $relacao_depedencia = $oPensionista->relacao_dependencia;

              if ($oPensionista->relacao_dependencia == 0) {
                $relacao_depedencia = '';
              }
              $sDataPensionista = implode("", (explode("-", $oPensionista->data_nascimento)));
              $this->oLayout->setCampoTipoLinha(3);
              $this->oLayout->setCampoIdentLinha("INFPA");
              $this->oLayout->setCampo('cpf', $oPensionista->cpf);
              $this->oLayout->setCampo('nome', $oPensionista->nome);
              $this->oLayout->setCampo('data_nascimento', $sDataPensionista);
              $this->oLayout->setCampo('relacao_dependencia', $relacao_depedencia);
              $this->oLayout->setCampo("idetificador_registro", 'INFPA');
              $this->oLayout->geraDadosLinha();
              $this->oLayout->setCampoTipoLinha(3);
              $this->oLayout->setCampoIdentLinha("RTRT");
              $this->oLayout->setCampo("idetificador_registro", "RTPA");
              for ($iMes = 1;$iMes <= 13;$iMes++) {

                $mes = '';
                if (isset($oPensionista->valores[$iMes])) {
                  $mes = $oPensionista->valores[$iMes];
                }
                $this->oLayout->setCampo($this->aMeses[$iMes], self::converterValor($mes));
              }
              $this->oLayout->geraDadosLinha();
            }
            continue;
          }
          $aValores = array_replace($this->aBaseValoresMensais, $aCompetencias);
          $this->escreverLinhaValoresMensais($sTipoRegistro, $aValores);
        }
      }
    }

  }

  /**
   *	Escreve linha  RRA
   *
   *  @return void
   */
  public function escreverLinhaRRA() {

    $oPessoaFisica = new stdClass();
    $oPessoaFisica->identificador                     = 'RRA';
    $oPessoaFisica->identificador_rendimento_recebido = "1";
    $oPessoaFisica->numero_processo                   = '';
    $oPessoaFisica->tipo_advogado                     = '';
    $oPessoaFisica->documento_advogado                = '';
    $oPessoaFisica->nome_advogado                     = '';
    $oPessoaFisica->pipe                              = '';

    $this->oLayout->setByLineOfDBUtils($oPessoaFisica, 3, 'RRA');

  }

  /**
   *  Escreve a linha com o codigo da Receita "IDREC"
   *
   *  @param  $sCodigoReceita
   *  @return void
   */
  public function escreverLinhaReceita($sCodigoReceita) {

    $aReceita["identificador_registro"] = "IDREC";
    $aReceita["codigo_receita"]         = $sCodigoReceita;
    $aReceita["Pipe"]                   = "";
    $this->oLayout->setByLineOfDBUtils((object)$aReceita, 3, "IDREC");
  }

  /**
   *  Escreve a linha do beneficiario do RRA ("BPFRRA")
   *
   *  @return void
   */
  public function escreverLinhaBeneficiarioRRA($sNome, $sCpf) {

    $aBeneficiario['identificador']  = "BPFRRA";
    $aBeneficiario['cpf']            = $sCpf;
    $aBeneficiario['nome']           = $sNome;
    $aBeneficiario['natureza']       = "";
    $aBeneficiario['data_molestia']  = "";
    $aBeneficiario['pipe']           = "";
    $this->oLayout->setByLineOfDBUtils((object)$aBeneficiario, 3, 'BPFRRA');
  }

  /**
   *  Escreve linha de valores mensais "RTRT"
   *
   *  @param  String $sIdentificador [description]
   *  @param  [type] $aValores       [description]
   *  @return bool                   [description]
   */
  public function escreverLinhaValoresMensais($sIdentificador, $aValores){

    $iTamanho = 8;
    if ( $sIdentificador == "QTMESES") {
      $iTamanho = 4;
    }

    $aDados['idetificador_registro'] = $sIdentificador;
    $aDados['janeiro']               = self::converterValor($aValores[1],  $iTamanho);
    $aDados['fevereiro']             = self::converterValor($aValores[2],  $iTamanho);
    $aDados['marco']                 = self::converterValor($aValores[3],  $iTamanho);
    $aDados['abril']                 = self::converterValor($aValores[4],  $iTamanho);
    $aDados['maio']                  = self::converterValor($aValores[5],  $iTamanho);
    $aDados['junho']                 = self::converterValor($aValores[6],  $iTamanho);
    $aDados['julho']                 = self::converterValor($aValores[7],  $iTamanho);
    $aDados['agosto']                = self::converterValor($aValores[8],  $iTamanho);
    $aDados['setembro']              = self::converterValor($aValores[9],  $iTamanho);
    $aDados['outubro']               = self::converterValor($aValores[10], $iTamanho);
    $aDados['novembro']              = self::converterValor($aValores[11], $iTamanho);
    $aDados['dezembro']              = self::converterValor($aValores[12], $iTamanho);
    if($sIdentificador != "QTMESES") {
      $aDados['decimo_terceiro']       = self::converterValor($aValores[13]);
    }
    $aDados['Pipe']                  = "";

    $this->oLayout->setByLineOfDBUtils((object)$aDados, 3, "RTRT");
  }

  /**
   *  Transforma o valor mensal
   *
   *  @param  Number $nValor
   *  @return String
   */
  protected static function converterValor($nValor, $iTamanho = 8) {

    if ($nValor == 0) {
      return '';
    }

    $nValor = db_formatar($nValor,'f');
    $nValor = trim($nValor);
    $nValor = str_replace('.','', $nValor);
    $nValor = str_replace(',','', $nValor);
    $nValor = db_formatar($nValor, 's', '0', $iTamanho, 'e', 2);
    return $nValor;
  }

}
