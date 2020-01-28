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

namespace ECidade\Tributario\Agua\Coletor\Exportacao;

use ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo\Leituras;

class Exportacao {

  /**
   * Tipo de Leitura
   */
  const LEITURA_TIPO_MANUAL     = 1;
  const LEITURA_TIPO_EXPORTACAO = 2;
  const LEITURA_TIPO_IMPORTADA  = 3;

  const LEITURA_SITUACAO_NORMAL       = "0";
  const LEITURA_HIDROMETRO_NAO_VIRADO = "false";

  /**
   * Status de Leitura
   */
  const LEITURA_STATUS_ATIVO      = 1;
  const LEITURA_STATUS_INATIVO    = 2;
  const LEITURA_STATUS_CANCELADO  = 3;

  /**
   * Tipo de Coleta de Leitura
   */
  const TIPO_LEITURA_MANUAL     = 1;
  const TIPO_LEITURA_ELETRONICA = 2;

  /**
   * Situações de Exportação
   */
  const EXPORTACAO_SITUACAO_EXPORTADA = 1;
  const EXPORTACAO_SITUACAO_IMPORTADA = 2;
  const EXPORTACAO_SITUACAO_CANCELADA = 3;

  /**
   * @var array
   */
  private $aRotas = array();

  /**
   * @var array
   */
  private $aRuas = array();

  /**
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * @var integer
   */
  private $iCodigoColetor;

  /**
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * @var \DBDate
   */
  private $oDataAtual;

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sHoraAtual;

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var integer
   */
  private $iMes;

  /**
   * @var integer
   */
  private $iCodigoLeiturista;

  /**
   * @todo Remover essa dependência, após refatorar o Processamento.
   *
   * @var Processamento
   */
  private $oProcessamento;

	public function __construct() {
		$this->oProcessamento = new Processamento;
	}

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return int
   */
  public function getCodigoLeiturista() {
    return $this->iCodigoLeiturista;
  }

  /**
   * @param int $iCodigoLeiturista
   */
  public function setCodigoLeiturista($iCodigoLeiturista) {
    $this->iCodigoLeiturista = $iCodigoLeiturista;
  }

  /**
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param integer $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return integer
   */
  public function getCodigoColetor() {
    return $this->iCodigoColetor;
  }

  /**
   * @param integer $iCodigoColetor
   */
  public function setCodigoColetor($iCodigoColetor) {
    $this->iCodigoColetor = $iCodigoColetor;
  }

  /**
   * @return \DBDate
   */
  public function getDataAtual() {
    return $this->oDataAtual;
  }

  /**
   * @param \DBDate $oDataAtual
   */
  public function setDataAtual(\DBDate $oDataAtual) {
    $this->oDataAtual = $oDataAtual;
  }

  /**
   * @return integer
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param integer $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return integer
   */
  public function getCodigoExportacao() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigoExportacao($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string
   */
  public function getHoraAtual() {
    return $this->sHoraAtual;
  }

  /**
   * @param string $sHoraAtual
   */
  public function setHoraAtual($sHoraAtual) {
    $this->sHoraAtual = $sHoraAtual;
  }

  /**
   * @return array
   */
  public function getRotas() {
    return $this->aRotas;
  }

  /**
   * @param array $aRotas
   */
  public function setRotas($aRotas) {
    $this->aRotas = $aRotas;
  }

  /**
   * @return array
   */
  public function getRuas() {
    return $this->aRuas;
  }

  /**
   * @param array $aRuas
   */
  public function setRuas($aRuas) {
    $this->aRuas = $aRuas;
  }

  /**
   * @return integer
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * @param integer $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return bool|resource
   * @throws \BusinessException
   * @throws \DBException
   * @throws \ParameterException
   */
  private function getDados() {

    if (!$this->getRotas()) {
      throw new \ParameterException('Lista de Rotas não foi informada.');
    }

    if (!$this->getRuas()) {
      throw new \ParameterException('Lista de Ruas não foi informada.');
    }

    if (!$this->getAno()) {
      throw new \ParameterException('Ano de Exportação não foi informado.');
    }

    $iAno    = $this->getAno();
    $sRotas  = implode(', ', $this->getRotas());
    $sRuas   = implode(', ', $this->getRuas());
    $sCampos = implode(', ', array(
      'x54_sequencial                     as codigo_contrato',
      'x07_codrota                        as codigo_rota',
      'x01_matric                         as codigo_matricula',
      'z01_numcgm                         as codigo_responsavel',
      'z01_nome                           as nome_responsavel',
      'z01_cgccpf                         as documento_responsavel',
      'x01_codrua                         as codigo_logradouro',
      'j88_codigo                         as tipo_logradouro',
      'j14_nome                           as nome_logradouro',
      'x01_numero                         as numero',
      'x01_orientacao                     as orientacao',
      'x01_zona                           as zona',
      'x01_quadra                         as quadra',
      'x01_letra                          as letra',
      'x01_codbairro                      as codigo_bairro',
      'x54_condominio                     as is_condominio',
      "'RS'                               as estado",
      "'BAGE'                             as cidade",
      '1                                  as economias',
      '2                                  as imprime_conta',
      'x32_codcorresp                     as codigo_correspondencia',
      'x54_diavencimento                  as dia_vencimento',
      "nextval('numpref_k03_numpre_seq')  as codigo_cobranca",
      'x13_sequencial                     as codigo_categoria_consumo',
      'case when x32_codcorresp is not null then
          x02_complemento
       else
          x11_complemento
       end as complemento',
      'case when x32_codcorresp is not null then
          bairro2.j13_descr
       else
          bairro.j13_descr
       end as bairro',
       'x54_aguatipocontrato as tipo_contrato',
    ));

    $sJoin = implode(' ', array(
      'inner join aguacontrato                   on x54_aguabase         = x01_matric',
      'inner join cgm                            on z01_numcgm           = x54_cgm',
      'left  join aguarotarua                    on x07_codrua           = x01_codrua',
      'left  join aguarota                       on x06_codrota          = x07_codrota',
      'left  join aguabasecorresp                on x32_matric           = x01_matric',
      'left  join aguacorresp                    on x02_codcorresp       = x32_codcorresp',
      'left  join ruas                           on ruas.j14_codigo      = x01_codrua',
      'left  join aguaconstr                     on x11_matric           = x01_matric',
      'left  join bairro                         on bairro.j13_codi      = x01_codbairro',
      'left  join bairro   as bairro2            on bairro2.j13_codi     = x02_codbairro',
      'left  join ruastipo                       on ruastipo.j88_codigo  = ruas.j14_tipo',
      'left  join aguabasebaixa                  on x08_matric           = x01_matric',
      'left  join aguacategoriaconsumo           on x13_sequencial       = x54_aguacategoriaconsumo',
    ));

    $sWhere = implode(' and ', array(
      "x07_codrota IN ({$sRotas})",
      "x07_codrotarua IN ({$sRuas})",
      "x01_numero between x07_nroini and x07_nrofim",
      "x01_orientacao = x07_orientacao",
      "x08_matric is null",
      "x54_datainicial < now()",
      "(x54_datafinal > now() or x54_datafinal is null)",
      "x13_exercicio = {$iAno}",
    ));

    $sOrderBy = implode(', ', array(
      'x07_codrota',
      'x07_ordem',
      'x01_codrua',
      'x01_orientacao',
      'x01_numero',
    ));

    $sSqlContrato = "select {$sCampos} from aguabase {$sJoin} where {$sWhere} order by {$sOrderBy}";
    $rsContratos  = db_query($sSqlContrato);

    if (!$rsContratos) {
      throw new \DBException('Não foi possível obter informações dos Contratos.');
    }

    if (pg_num_rows($rsContratos) == 0) {
      throw new \BusinessException('Nenhuma matrícula foi encontrada.');
    }

    return $rsContratos;
  }

  /**
   * @return int
   * @throws \BusinessException
   * @throws \DBException
   * @throws \Exception
   */
  public function salvar() {

    if (!$this->iCodigoColetor) {
      throw new \BusinessException('Coletor não informado.');
    }

    if (!$this->iCodigoInstituicao) {
      throw new \BusinessException('Instituição não informada.');
    }

    if (!$this->iCodigoUsuario) {
      throw new \BusinessException('Usuário não informado.');
    }

    if (!$this->iAno) {
      throw new \BusinessException('Ano não informado.');

    }
    if (!$this->iMes) {
      throw new \BusinessException('Mês não informado.');
    }

    if (!$this->oDataAtual) {
      throw new \BusinessException('Data atual não informada.');
    }

    if (!$this->iCodigoLeiturista) {
      throw new \BusinessException('Código do Leiturista não informado.');
    }

    if (!\db_utils::inTransaction()) {
      throw new \Exception('Nenhuma transação encontrada.');
    }

    $oDaoExportacao = new \cl_aguacoletorexporta;
    $oDaoExportacao->x49_aguacoletor  = $this->iCodigoColetor;
    $oDaoExportacao->x49_instit       = $this->iCodigoInstituicao;
    $oDaoExportacao->x49_anousu       = $this->getAno();
    $oDaoExportacao->x49_mesusu       = $this->getMes();
    $oDaoExportacao->x49_db_layouttxt = Leituras::CODIGO_LAYOUT;
    $oDaoExportacao->x49_situacao     = self::EXPORTACAO_SITUACAO_EXPORTADA;
    $oDaoExportacao->incluir(null);

    if ($oDaoExportacao->erro_status == "0") {
      throw new \DBException('Não foi possível salvar a exportação.');
    }

    $oDaoSituacaoExportacao = new \cl_aguacoletorexportasituacao;
    $oDaoSituacaoExportacao->x48_aguacoletorexporta = $oDaoExportacao->x49_sequencial;
    $oDaoSituacaoExportacao->x48_usuario            = $this->iCodigoUsuario;
    $oDaoSituacaoExportacao->x48_data               = $this->oDataAtual->getDate();
    $oDaoSituacaoExportacao->x48_hora               = $this->sHoraAtual;
    $oDaoSituacaoExportacao->x48_situacao           = self::EXPORTACAO_SITUACAO_EXPORTADA;
    $oDaoSituacaoExportacao->x48_motivo             = 'Processamento de matrículas para o coletor.';
    $oDaoSituacaoExportacao->incluir(null);

    if ($oDaoSituacaoExportacao->erro_status == "0") {
      throw new \DBException('Não foi possível salvar a situação da exportação.');
    }

    $this->iCodigo = (integer) $oDaoExportacao->x49_sequencial;
    $this->salvarDetalhes();

    return $this->iCodigo;
  }

  /**
   * @throws \BusinessException
   * @throws \DBException
   */
  private function salvarDetalhes() {

    $iOrdem = 1;
    $rsDadosExportacao = $this->getDados();
    while ($oInformacoes = pg_fetch_object($rsDadosExportacao)) {

      if ($oInformacoes->tipo_contrato == \AguaContrato::TIPO_CONTRATO_SEM_HIDROMETRO) {
        continue;
      }

      $lContratoExportado = $this->oProcessamento->isContratoExportado($oInformacoes->codigo_contrato, $this->getAno(), $this->getMes());
      if ($lContratoExportado) {
        continue;
      }

      $oContrato = new \AguaContrato();
      $oContrato->setCodigo($oInformacoes->codigo_contrato);
      if (!$oContrato->getHidrometros()) {
        throw new \BusinessException('Nenhum Hidrômetro está ligado ao Contrato.');
      }
      $oHidrometro = current($oContrato->getHidrometros());

      if ($oInformacoes->is_condominio == 't') {
        $oInformacoes->economias = $oContrato->getQuantidadeEconomias();
      }

      if (!$oInformacoes->codigo_correspondencia) {
        $oInformacoes->imprime_conta = $this->oProcessamento->getImprimeConta($oInformacoes->codigo_matricula);
      }

      // Busca data de vencimento
      $oInformacoes->data_vencimento = $this->oProcessamento->getDataVencimento(
        $oInformacoes->codigo_matricula,
        $this->getAno(),
        $this->getMes()
      );

      $oUltimaLeitura = $oContrato->getUltimaLeituraAtiva();
      $sDataLeituraAnterior = null;

      if ($oUltimaLeitura) {
        $sDataLeituraAnterior = $oUltimaLeitura->getDataLeitura()->getDate();
      }

      $oDaoAguaColetorExportaDados = new \cl_aguacoletorexportadados;
      $oDaoAguaColetorExportaDados->x50_aguacoletorexporta      = $this->iCodigo;
      $oDaoAguaColetorExportaDados->x50_aguacoletorexportadados = 'null';
      $oDaoAguaColetorExportaDados->x50_ordem                   = $iOrdem;
      $oDaoAguaColetorExportaDados->x50_matric                  = $oInformacoes->codigo_matricula;
      $oDaoAguaColetorExportaDados->x50_rota                    = $oInformacoes->codigo_rota;
      $oDaoAguaColetorExportaDados->x50_tipo                    = $oInformacoes->tipo_logradouro;
      $oDaoAguaColetorExportaDados->x50_codlogradouro           = $oInformacoes->codigo_logradouro;
      $oDaoAguaColetorExportaDados->x50_codbairro               = $oInformacoes->codigo_bairro;
      $oDaoAguaColetorExportaDados->x50_zona                    = $oInformacoes->zona;
      $oDaoAguaColetorExportaDados->x50_responsavel             = pg_escape_string($oInformacoes->nome_responsavel);
      $oDaoAguaColetorExportaDados->x50_nomelogradouro          = pg_escape_string($oInformacoes->nome_logradouro);
      $oDaoAguaColetorExportaDados->x50_numero                  = $oInformacoes->numero;
      $oDaoAguaColetorExportaDados->x50_letra                   = $oInformacoes->orientacao;
      $oDaoAguaColetorExportaDados->x50_complemento             = pg_escape_string($oInformacoes->complemento);
      $oDaoAguaColetorExportaDados->x50_nomebairro              = $oInformacoes->bairro;
      $oDaoAguaColetorExportaDados->x50_cidade                  = $oInformacoes->cidade;
      $oDaoAguaColetorExportaDados->x50_estado                  = $oInformacoes->estado;
      $oDaoAguaColetorExportaDados->x50_quadra                  = $oInformacoes->quadra;
      $oDaoAguaColetorExportaDados->x50_economias               = $oInformacoes->economias;
      $oDaoAguaColetorExportaDados->x50_imprimeconta            = $oInformacoes->imprime_conta;
      $oDaoAguaColetorExportaDados->x50_vencimento              = $oInformacoes->data_vencimento;
      $oDaoAguaColetorExportaDados->x50_codhidrometro           = $oHidrometro->getCodigo();
      $oDaoAguaColetorExportaDados->x50_nrohidro                = $oHidrometro->getNumero();
      $oDaoAguaColetorExportaDados->x50_avisoleiturista         = $oHidrometro->getAvisoLeiturista();
      $oDaoAguaColetorExportaDados->x50_consumopadrao           = 0;
      $oDaoAguaColetorExportaDados->x50_consumomaximo           = 0;
      $oDaoAguaColetorExportaDados->x50_areaconstruida          = 0;
      $oDaoAguaColetorExportaDados->x50_numpre                  = $oInformacoes->codigo_cobranca;
      $oDaoAguaColetorExportaDados->x50_categorias              = 'LAYOUT_TARIFA';
      $oDaoAguaColetorExportaDados->x50_dtleituraanterior       = $sDataLeituraAnterior;
      $oDaoAguaColetorExportaDados->incluir(null);

      if ($oDaoAguaColetorExportaDados->erro_status == '0') {
        throw new \DBException('Não foi possível salvar os dados da exportação.');
      }

      $iCodigoLeitura = $this->criarLeitura($oInformacoes->codigo_contrato, $oHidrometro->getCodigo());

      $this->vincularLeituraExportacao(
        $iCodigoLeitura, $oDaoAguaColetorExportaDados->x50_sequencial
      );

      $aDebitos = $this->getReceitas($oInformacoes->codigo_matricula, $oInformacoes->codigo_responsavel);
      if ($aDebitos) {
        $this->vincularReceitas($oDaoAguaColetorExportaDados->x50_sequencial, $aDebitos);
      }

      $this->vincularContrato($oDaoAguaColetorExportaDados->x50_sequencial, $oInformacoes);

      $iOrdem++;
    }
  }

  /**
   * Cria Leitura de Exportação
   *
   * @param $iCodigoContrato
   * @param $iCodigoHidrometro
   * @throws \DBException
   * @throws \ParameterException
   * @return int
   */
  private function criarLeitura($iCodigoContrato, $iCodigoHidrometro) {

    if (!$iCodigoHidrometro) {
      throw new \ParameterException('Código do Hidrômetro é inválido.');
    }

    $oDaoAguaLeitura = new \cl_agualeitura();
    $sDataAtual      = $this->getDataAtual()->getDate();

    $oDaoAguaLeitura->x21_codhidrometro = $iCodigoHidrometro;
    $oDaoAguaLeitura->x21_exerc         = $this->getAno();
    $oDaoAguaLeitura->x21_mes           = $this->getMes();
    $oDaoAguaLeitura->x21_situacao      = self::LEITURA_SITUACAO_NORMAL;
    $oDaoAguaLeitura->x21_numcgm        = $this->getCodigoLeiturista();
    $oDaoAguaLeitura->x21_dtleitura     = $sDataAtual;
    $oDaoAguaLeitura->x21_usuario       = $this->getCodigoUsuario();
    $oDaoAguaLeitura->x21_dtinc         = $sDataAtual;
    $oDaoAguaLeitura->x21_leitura       = "0";
    $oDaoAguaLeitura->x21_virou         = self::LEITURA_HIDROMETRO_NAO_VIRADO;
    $oDaoAguaLeitura->x21_tipo          = self::LEITURA_TIPO_EXPORTACAO;
    $oDaoAguaLeitura->x21_status        = self::LEITURA_STATUS_INATIVO;
    $oDaoAguaLeitura->x21_aguacontrato  = $iCodigoContrato;
    $oDaoAguaLeitura->incluir(null);

    if ($oDaoAguaLeitura->erro_status == '0') {
      throw new \DBException('Não foi possível salvar as informações de Leitura.');
    }

    return $oDaoAguaLeitura->x21_codleitura;
  }

  /**
   * @param $iCodigoLeitura
   * @param $iCodigoExportacao
   * @return int
   * @throws \DBException
   * @throws \ParameterException
   */
  private function vincularLeituraExportacao($iCodigoLeitura, $iCodigoExportacao) {

    if (!$iCodigoLeitura) {
      throw new \ParameterException('Código de Leitura é inválido.');
    }

    if (!$iCodigoExportacao) {
      throw new \ParameterException('Código de Exportação é inválido.');
    }

    $oExportacaoLeitura = new \cl_aguacoletorexportadadosleitura();
    $oExportacaoLeitura->x51_aguacoletorexportadados = $iCodigoExportacao;
    $oExportacaoLeitura->x51_agualeitura             = $iCodigoLeitura;
    $oExportacaoLeitura->x51_diasultimaleitura       = "0";
    $oExportacaoLeitura->x51_mesesultimaleitura      = "0";
    $oExportacaoLeitura->x51_tipoleitura             = self::TIPO_LEITURA_ELETRONICA;
    $oExportacaoLeitura->x51_numcgm                  = $this->getCodigoLeiturista();
    $oExportacaoLeitura->incluir(null);

    if ($oExportacaoLeitura->erro_status == '0') {
      throw new \DBException('Não foi possível salvar vínculo entre Leitura e Exportação.');
    }

    return $oExportacaoLeitura->x51_sequencial;
  }

  /**
   * @param $iCodigoExportacao
   * @param $aReceitas
   * @return bool
   * @throws \DBException
   * @throws \ParameterException
   */
  private function vincularReceitas($iCodigoExportacao, $aReceitas) {

    if (!$aReceitas) {
      throw new \ParameterException('Lista de Receitas é inválida.');
    }

    foreach ($aReceitas as $aReceita) {

      $oExportacaoReceita = new \cl_aguacoletorexportadadosreceita();
      $oExportacaoReceita->x52_numpre                  = $aReceita['codigo_cobranca'];
      $oExportacaoReceita->x52_receita                 = $aReceita['codigo_receita'];
      $oExportacaoReceita->x52_descricao               = $aReceita['descricao'];
      $oExportacaoReceita->x52_aguacoletorexportadados = $iCodigoExportacao;
      $oExportacaoReceita->x52_valor                   = $aReceita['valor'];
      $oExportacaoReceita->x52_numpar                  = $aReceita['parcela'];
      $oExportacaoReceita->x52_numtot                  = $aReceita['total_parcelas'];
      $oExportacaoReceita->incluir(null);

      if ($oExportacaoReceita->erro_status == '0') {
        throw new \DBException('Não foi possível salvar vínculo entre Receita e Exportação.');
      }
    }

    return true;
  }

  /**
   * @param $iCodigoMatricula
   * @param $iCodigoResponsavel
   * @return array
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getReceitas($iCodigoMatricula, $iCodigoResponsavel) {

    $oDadosExportacao = new \clExpDadosColetores;

    $iTipoDebito = $oDadosExportacao->getArretipo($this->getAno());
    if (!$iTipoDebito) {
      throw new \BusinessException('Tipo de Débito não configurado.');
    }

    $sJoin = implode(' ', array(
      "inner join arreinstit ON arreinstit.k00_numpre = arrematric.k00_numpre AND arreinstit.k00_instit = {$this->getCodigoInstituicao()}",
      "inner join arrenumcgm ON arrenumcgm.k00_numpre = arrematric.k00_numpre AND arrenumcgm.k00_numcgm = {$iCodigoResponsavel}"
    ));

    $sSqlDebitosMatricula = "(
      select distinct arrematric.k00_numpre from arrematric {$sJoin} where arrematric.k00_matric = {$iCodigoMatricula}
    ) as arrematric";

    $rsDebitos = $oDadosExportacao->getSqlArreCad(
      $sSqlDebitosMatricula, $iTipoDebito, $this->getAno(), $this->getMes(), $this->getMes()
    );

    if (!$rsDebitos) {
      throw new \DBException('Não foi possível obter informações dos Débitos para a Exportação.');
    }

    $aDebitos = array();
    while($oDebito = pg_fetch_object($rsDebitos)) {
      $aDebitos[] = array(
        'codigo_cobranca' => $oDebito->k00_numpre,
        'codigo_receita'  => $oDebito->k00_receit,
        'descricao'       => $oDebito->k02_descr,
        'parcela'         => $oDebito->k00_numpar,
        'total_parcelas'  => $oDebito->k00_numtot,
        'valor'           => $oDebito->k00_valor,
      );
    }

    return $aDebitos;
  }

  /**
   * @param  stdClass
   * @param \stdClass $oInformacoes
   * @return int
   * @throws \DBException
   */
  private function vincularContrato($iCodigoLinha, \stdClass $oInformacoes) {

    $iCodigoIsencao = $this->oProcessamento->getCodigoIsencao($oInformacoes->codigo_responsavel);
    $oDaoAguaColetorExportaDadosContratos = new \cl_aguacoletorexportadadoscontrato;
    $oDaoAguaColetorExportaDadosContratos->x57_cgm                     = $oInformacoes->codigo_responsavel;
    $oDaoAguaColetorExportaDadosContratos->x57_aguacontrato            = $oInformacoes->codigo_contrato;
    $oDaoAguaColetorExportaDadosContratos->x57_aguacategoriaconsumo    = $oInformacoes->codigo_categoria_consumo;
    $oDaoAguaColetorExportaDadosContratos->x57_aguacoletorexportadados = $iCodigoLinha;
    if ($iCodigoIsencao) {
      $oDaoAguaColetorExportaDadosContratos->x57_aguaisencaocgm = $iCodigoIsencao;
    }

    $oDaoAguaColetorExportaDadosContratos->incluir();
    if ($oDaoAguaColetorExportaDadosContratos->erro_status == '0') {
      throw new \DBException('Não foi possível vincular o contrato.');
    }

    return $oDaoAguaColetorExportaDadosContratos->x57_sequencial;
  }

  /**
   * Gera um arquivo com as informações do layout informado.
   *
   * @param  integer $iCodigoLayout
   * @param  string  $sSufixoArquivo
   * @return string
   */
  public static function gerarArquivoLayout($iCodigoLayout, $sSufixoArquivo = null) {

    $iCodigoLayoutLayouts = 12;

    $sNomeArquivo  = "tmp/" . "layout_{$sSufixoArquivo}.txt";
    $oLayoutTxt    = new \db_layouttxt($iCodigoLayoutLayouts, $sNomeArquivo, "");
    $oLayoutCampos = new \cl_db_layoutcampos;

    $oLayoutTxt->setCampoTipoLinha(\db_layouttxt::TIPO_LINHA_REGISTRO);
    $oLayoutTxt->limpaCampos();

    $sCampos        = "db52_nome, db52_descr, db52_layoutformat, db52_posicao as db52_posicao_inicial, ";
    $sCampos       .= "db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end) as db52_posicao_final";
    $sWhere         = "db52_layoutlinha in(select db51_codigo from db_layoutlinha where db51_layouttxt = {$iCodigoLayout} and db51_tipolinha = 3)";
    $sOrdem         = "db52_posicao";
    $sSql           = $oLayoutCampos->sql_query(null, $sCampos, $sOrdem, $sWhere);
    $rsResultado    = db_query($sSql);
    $iQtdResultados = pg_num_rows($rsResultado);

    for ($iResultado = 0; $iResultado < $iQtdResultados; $iResultado++) {

      $oCampo = pg_fetch_object($rsResultado, $iResultado);

      $oLayoutTxt->setCampo('posicao_inicial', $oCampo->db52_posicao_inicial);
      $oLayoutTxt->setCampo('posicao_final',   $oCampo->db52_posicao_final);
      $oLayoutTxt->setCampo('nome_campo',      $oCampo->db52_nome);
      $oLayoutTxt->setCampo('descricao',       $oCampo->db52_descr);
      $oLayoutTxt->geraDadosLinha();
    }
    $oLayoutTxt->fechaArquivo();

    return new \File($sNomeArquivo);
  }
}

