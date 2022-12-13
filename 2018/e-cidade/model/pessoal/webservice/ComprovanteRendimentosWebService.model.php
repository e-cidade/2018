<?php

/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 09/11/16
 * Time: 13:19
 */
class ComprovanteRendimentosWebService {


  private $ano;

  public function __construct($ano) {

    $this->ano = $ano;
  }

  public function getComprovanteDoServidor($matricula) {

    $oServidor    = ServidorRepository::getInstanciaByCodigo($matricula);
    $oComprovante = ComprovanteRendimentoRepository::getPorMatriculaNoAno($oServidor, $this->ano);

    $oRendimento = new stdClass();
    /**
     * Dados Pessoais
     */
    $oRendimento->cpf                 = db_formatar($oComprovante->getCgm()->getCpf(), 'cpf');
    $oRendimento->nome                = db_translate($oServidor->getCgm()->getNome());
    $oRendimento->resp                = '';
    $oRendimento->cnpj_fonte_pagadora = db_formatar($oComprovante->getFontePagadora(), 'cnpj');
    $oRendimento->nome_fonte_pagadora = ($oComprovante->getNomeFontePagadora());
    $oRendimento->pensionistas        = '';
    $oRendimento->ano                 = $this->ano;
    $oRendimento->matricula           = str_replace('}','',str_replace('{','',$matricula));
    $oRendimento->lotacao             = $oComprovante->getLotacao();
    $oRendimento->num_comprovante     = 1;

    /**
     * Rendimentos
     */
    $oRendimento->rendimento             = $oComprovante->getValorTotalRendimentos();
    $oRendimento->prev_oficial           = $oComprovante->getValorPrevidenciaOficial();
    $oRendimento->prev_privada           = $oComprovante->getValorPrevidenciaPrivada();
    $oRendimento->pensao                 = $oComprovante->getValorPagoEmPensao();
    $oRendimento->irrf                   = $oComprovante->getValorPagoIRRF();
    $oRendimento->desconto_aposentadoria = $oComprovante->getValorDescontoAposentado() + $oComprovante->getValorDescontoAposentadoDecimoTerceiro();
    $oRendimento->diarias                = $oComprovante->getValorDiarias();
    $oRendimento->valor_molestia         = $oComprovante->getValorTotalMolestiaGrave();
    $oRendimento->ind_rescisao           = $oComprovante->getValorIndenizacaoRescisao();
    $oRendimento->abono                  = $oComprovante->getValorAbono();
    $oRendimento->outros5                = $oComprovante->getValorOutrosRendimentos();


    /**
     * Decimeto 13 e plano de saude
     */
    $oRendimento->decimo_terceiro           = $oComprovante->getValorDecimoTerceiroParaComprovante();
    $oRendimento->irrf_decimo_terceiro      = $oComprovante->getValorPagoIRRFDecimoTerceiro();
    $oRendimento->outros_redimentos_decimo  = 0;
    $oRendimento->gasto_plano_saude         = $oComprovante->getValorPlanoSaude();
    $oRendimento->pensionistas              = $oComprovante->getOutrasInformacoes();

    /**
     * RRAs
     */
    $oRendimento->rra_rendimentos_tributaveis = $oComprovante->getValorRendimentosTributaveisSobreRRA();
    $oRendimento->rra_previdencia             = $oComprovante->getValorPrevidenciaSobreRRA();
    $oRendimento->rra_pensao                  = $oComprovante->getValorPensaoSobreRRA();
    $oRendimento->rra_irrf                    = $oComprovante->getValorIRRFSobreRRA();
    $oRendimento->rra_despesa_acao            = $oComprovante->getValorDespesaDaAcao();
    $oRendimento->rra_quantidade_meses        = $oComprovante->getQuantidadeDeMeses()  ;
    $oRendimento->rra_isentos                 = $oComprovante->getValorIsencaoSobreRRA();
    return utf8_encode_all($oRendimento);
  }
}