<?php

namespace ECidade\Tributario\Agua\EmissaoCarnes;

require_once(modification("libs/db_libdocumento.php"));

class Processamento {

  const LAYOUT_EMISSAO = 280;

  const LAYOUT_LINHA = 918;

  const DOCUMENTO_CARNE_AGUA = 32;

  /**
   * @var string
   */
  private $sArquivo;

  /**
   * @var string
   */
  private $sArquivoLayout;

  /**
   * @var \db_layouttxt
   */
  private $oArquivo;

  /**
   * @param string $sArquivo
   * @param string $sArquivoLayout
   */
  public function __construct($sArquivo, $sArquivoLayout) {

    $this->sArquivo = $sArquivo;
    $this->sArquivoLayout = $sArquivoLayout;
    $this->oArquivo = new \db_layouttxt(self::LAYOUT_EMISSAO, $sArquivo);
  }

  /**
   * @param  array  $aLeituras
   * @return array
   */
  private function formatarLeituras(array $aLeituras) {

    $aCampos = array();
    foreach ($aLeituras as $iMes => $oLeitura) {

      $aLeitura = $aLeituras[$iMes];
      $aLeituraAnterior = isset($aLeituras[$iMes + 1]) ? $aLeituras[$iMes + 1] : null;

      $oDataAtual = null;
      $oDataAnterior = null;
      if (!empty($aLeitura['data'])) {
        $oDataAtual = new \DateTime($aLeitura['data']);
      }
      if ($aLeituraAnterior && !empty($aLeituraAnterior['data'])) {
        $oDataAnterior = new \DateTime($aLeituraAnterior['data']);
      }
      $sLinha  = " " . substr(db_mes($aLeitura['mes']), 0, 3);
      $sLinha .= "  " . str_pad(substr($aLeitura['descricao'], 0, 10), 10, " ", STR_PAD_RIGHT);
      $sLinha .= "  " . str_pad($aLeitura['leitura'], 6, " ", STR_PAD_LEFT);
      $sLinha .= "  " . str_pad($aLeitura['consumo'], 4, " ", STR_PAD_LEFT);

      if (($iMes + 1) < count($aLeituras)) {

        if(empty($oDataAtual) || empty($oDataAnterior)) {
          $iDias = 0;
        } else {
          $iDias = $oDataAtual->diff($oDataAnterior)->format('%a');
        }

        $sLinha .= "  ".str_pad($iDias, 3, " ", STR_PAD_LEFT);
      } else {
        $sLinha .= "  ".str_pad(30, 3, " ", STR_PAD_LEFT);
      }

      $aCampos[] = $sLinha;
    }

    return $aCampos;
  }

  /**
   * @param  array  $aDebitos
   * @return array
   */
  private function formatarDebitos(array $aDebitos) {

    $aCampos = array();
    foreach ($aDebitos as $aDebito) {

      $sParcela = str_pad($aDebito['parcela'], 3, "0", STR_PAD_LEFT);
      $sTotalParcelas = str_pad($aDebito['total_parcelas'], 3, '0', STR_PAD_LEFT);
      $sParcelas = "{$sParcela} / {$sTotalParcelas}";
      $sValor = str_pad(trim(db_formatar($aDebito['valor'], 'f')), 10, '*', STR_PAD_LEFT);
      $sNumpre = str_pad($aDebito['codigo_cobranca'], 8, '0', STR_PAD_LEFT);

      $sLinha  = $aDebito['codigo_receita'];
      $sLinha .= "  " . str_pad($aDebito['descricao'], 14, " ", STR_PAD_RIGHT);
      $sLinha .= "  " . $sParcelas;
      $sLinha .= "  " . $sValor;
      $sLinha .= "  " . $sNumpre;

      $aCampos[] = $sLinha;
    }

    return $aCampos;
  }

  /**
   * @param  \stdClass $oDados
   * @param  \stdClass $oMes
   * @return array
   */
  private function getMensagens(\stdClass $oDados, \stdClass $oMes) {

    $oDocumento = new \libdocumento(self::DOCUMENTO_CARNE_AGUA);

    /**
     * Mensagem carne
     */
    $oDocumento->msg_debitos = $oMes->mensagem_debitos;

    /**
     * Débito em conta
     */
    $oDocumento->msg_debconta01 = '';
    $oDocumento->msg_debconta02 = '';
    if ($oDados->has_debito_conta) {

      $oDocumento->msg_debconta01 = $oMes->mensagem_debito_conta_1;
      $oDocumento->msg_debconta02 = $oMes->mensagem_debito_conta_2;
    }

    /**
     * Declaração de quitação de débitos
     */
    $oDocumento->coddeclaracao = '';
    $oDocumento->data_inicial = '';
    $oDocumento->data_final = '';
    if ($oMes->codigo_declaracao_quitacao) {

      $oDocumento->coddeclaracao = $oMes->codigo_declaracao_quitacao;
      $oDocumento->data_inicial = $oMes->data_inicial_declaracao;
      $oDocumento->data_final = $oMes->data_final_declaracao;
    }

    /**
     * Validação de CPF/CNPJ para informar sobre a necessidade de atualização cadastral,
     * devido a obrigatoriedade de CPF/CNPJ na cobrança registrada.
     */
    $lDocumentoValido = false;
    if (\DBString::isCPF($oDados->documento_responsavel) || \DBString::isCNPJ($oDados->documento_responsavel)) {
      $lDocumentoValido = true;
    }

    /**
     * Transforma o retorno da libdocumento em um array associativo
     */
    $aParagrafos = $oDocumento->getDocParagrafos();
    $aMensagens = array();
    foreach ($aParagrafos as $oParagrafo) {
      $aMensagens[strtolower($oParagrafo->oParag->db02_descr)] = $oParagrafo->oParag->db02_texto;
    }

    /**
     * Se não tem declaração de quitação de débitos oculta a mensagem.
     */
    if (!$oMes->codigo_declaracao_quitacao) {
      $aMensagens['msg21'] = '';
    }

    /**
     * Se o documento do responsável é valido oculta a mensagem.
     */
    if ($lDocumentoValido) {

      $aMensagens['msg15'] = '';
      $aMensagens['msg16'] = '';
    }

    return $aMensagens;
  }

  /**
   * @param  \stdClass $oDados
   * @return array
   */
  private function formatar(\stdClass $oDados) {

    $aLinhas = array();
    foreach ($oDados->meses as $oMes) {

      $oLinha = new \stdClass;
      $oLinha->vencimento             = $oMes->vencimento;
      $oLinha->referencia             = $oMes->referencia;
      $oLinha->proprietario           = $oDados->nome_responsavel;
      $oLinha->endereco_entrega       = $oDados->endereco_entrega;
      $oLinha->matricula              = $oDados->codigo_matricula;
      $oLinha->logradouro             = $oDados->codigo_logradouro;
      $oLinha->categoria              = $oDados->categoria_consumo;
      $oLinha->zona                   = $oDados->zona;
      $oLinha->quadra                 = $oDados->quadra;
      $oLinha->economias              = $oDados->economias;
      $oLinha->bairro                 = $oDados->bairro;
      $oLinha->dados_usuario_1        = $oDados->nome_responsavel;
      $oLinha->dados_usuario_2        = $oDados->endereco_entrega;
      $oLinha->dados_usuario_3        = '';
      $oLinha->processamento          = $oMes->codigo_cobranca;
      $oLinha->natureza               = $oDados->natureza;
      $oLinha->area_construida        = trim($oDados->area_construida);
      $oLinha->hidrometro             = $oDados->codigo_hidrometro;
      $oLinha->dt_leitura_atual       = $oMes->data_leitura_atual;
      $oLinha->dt_leitura_anterior    = $oMes->data_leitura_anterior;
      $oLinha->consumo                = $oMes->consumo;
      $oLinha->dias_leitura           = $oMes->dias_entre_leituras;
      $oLinha->media_diaria           = trim(db_formatar($oMes->media_consumo_diario, 'f'));
      $oLinha->valor_acrescimo        = trim(db_formatar($oMes->valor_acrescimo, 'f'));
      $oLinha->valor_desconto         = trim(db_formatar($oMes->valor_desconto, 'f'));
      $oLinha->valor_total            = trim(db_formatar($oMes->valor, 'f'));
      $oLinha->data_emissao           = '';
      $oLinha->contador               = $oMes->contador;
      $oLinha->zona_entrega           = $oDados->zona_entrega;
      $oLinha->cpfcnpj_proprietario   = $oDados->documento_responsavel;
      $oLinha->nosso_numero           = $oMes->nosso_numero;
      $oLinha->codigo_contrato        = $oDados->codigo_contrato;
      $oLinha->agencia_codigo_cedente = $oMes->agencia_codigo_cedente;
      $oLinha->carteira               = $oMes->carteira;
      $oLinha->linha_digitavel        = $oMes->linha_digitavel;
      $oLinha->codigo_barras          = $oMes->codigo_barras;

      $aLeituras = $this->formatarLeituras($oMes->leituras);
      foreach ($aLeituras as $iChave => $sLeitura) {
        $oLinha->{'leitura_' . ($iChave + 1)} = $sLeitura;
      }

      $oLinha->titulo_receita_1 = 'Rec   Descricao        Parcela       Valor  Numpre';
      $oLinha->titulo_receita_2 = 'Rec   Descricao        Parcela       Valor  Numpre';

      $aDebitos = $this->formatarDebitos($oMes->debitos);
      foreach ($aDebitos as $iChave => $sDebito) {
        $oLinha->{'linha_receita_' . ($iChave + 1)} = $sDebito;
      }

      $aMensagens = $this->getMensagens($oDados, $oMes);
      foreach ($aMensagens as $sCampoMensagem => $sMensagem) {
        $oLinha->{$sCampoMensagem} = $sMensagem;
      }

      $aLinhas[] = $oLinha;
    }

    return $aLinhas;
  }

  /**
   * Escreve linhas no arquivo
   *
   * @param  \stdClass $oDados
   */
  public function escrever(\stdClass $oDados) {

    $aLinhas = $this->formatar($oDados);

    foreach ($aLinhas as $oLinha) {
      $this->oArquivo->addLine($oLinha, \db_layouttxt::TIPO_LINHA_REGISTRO);
    }
  }

  /**
   * Finaliza a geração dos arquivos
   */
  public function finalizar() {

    $this->oArquivo->fechaArquivo();
    $this->oArquivo->gerarArquivoLeiaute($this->sArquivoLayout, self::LAYOUT_LINHA);
  }
}
