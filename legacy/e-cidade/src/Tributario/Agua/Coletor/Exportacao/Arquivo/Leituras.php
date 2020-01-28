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

namespace ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo;

class Leituras extends Arquivo {

  /**
   * @var int
   */
  private $iCodigoExportacao;

  const CODIGO_LAYOUT = 272;

  /**
   * Leituras constructor.
   */
  public function __construct() {

    $this->sNomeArquivo = 'leituras';
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @param int $iCodigoExportacao
   */
  public function setCodigoExportacao($iCodigoExportacao) {
    $this->iCodigoExportacao = $iCodigoExportacao;
  }

  /**
   * @return int
   */
  public function getCodigoExportacao() {
    return $this->iCodigoExportacao;
  }

  /**
   * @return array
   * @throws \DBException
   * @throws \ParameterException
   */
  public function getDados() {

    if (!$this->getNomeArquivo()) {
      throw new \ParameterException('Nome do arquivo não foi informado.');
    }

    if (!$this->getCodigoLayout()) {
      throw new \ParameterException('Código do Layout não foi informado.');
    }

    if (!$this->getCodigoExportacao()) {
      throw new \ParameterException('Código da Exportação não foi informado.');
    }

    $rsLeituras = $this->getLeituras();

    if (!$rsLeituras) {
      throw new \DBException("Não foi possível obter informações das Leituras.");
    }

    if (pg_num_rows($rsLeituras) == 0) {
      return array();
    }

    $aAvisos = $this->getAvisos();

    $aRegistros = array();
    while ($oLeitura = pg_fetch_object($rsLeituras)) {

      $aRegistro = array(
        'codigo'                      => $oLeitura->codigo,
        'codigo_leiturista'           => $oLeitura->codigo_leiturista,
        'codigo_rota'                 => $oLeitura->codigo_rota,
        'ano'                         => $oLeitura->ano,
        'mes'                         => $oLeitura->mes,
        'codigo_contrato'             => $oLeitura->codigo_contrato,
        'codigo_cobranca'             => $oLeitura->codigo_cobranca,
        'codigo_matricula'            => $oLeitura->codigo_matricula,
        'nome_responsavel'            => $oLeitura->nome_responsavel,
        'documento_responsavel'       => $oLeitura->documento_responsavel,
        'codigo_logradouro'           => $oLeitura->codigo_logradouro,
        'codigo_tipo_isencao'         => '',
        'tipo_logradouro'             => $oLeitura->tipo_logradouro,
        'nome_logradouro'             => $oLeitura->nome_logradouro,
        'numero'                      => $oLeitura->numero,
        'letra'                       => $oLeitura->letra,
        'complemento'                 => $oLeitura->complemento,
        'bairro'                      => $oLeitura->bairro,
        'cidade'                      => $oLeitura->cidade,
        'estado'                      => $oLeitura->estado,
        'zona'                        => $oLeitura->zona,
        'quadra'                      => $oLeitura->quadra,
        'economias'                   => $oLeitura->economias,
        'codigo_categoria_consumo'    => $oLeitura->codigo_categoria_consumo,
        'descricao_categoria_consumo' => $oLeitura->descricao_categoria_consumo,
        'codigo_hidrometro'           => $oLeitura->codigo_hidrometro,
        'dt_leitura_atual'            => $oLeitura->dt_leitura_atual,
        'dt_leitura_anterior'         => $oLeitura->dt_leitura_anterior,
        'consumo'                     => $oLeitura->consumo,
        'dias_leitura'                => $oLeitura->dias_leitura,
        'dt_vencimento'               => $oLeitura->dt_vencimento,
        'valor_acrescimo'             => $oLeitura->valor_acrescimo,
        'valor_desconto'              => $oLeitura->valor_desconto,
        'valor_total'                 => $oLeitura->valor_total,
        'aviso1'                      => $aAvisos['aviso1'],
        'aviso2'                      => $aAvisos['aviso2'],
        'aviso3'                      => $aAvisos['aviso3'],
        'aviso4'                      => $aAvisos['aviso4'],
        'aviso5'                      => $aAvisos['aviso5'],
        'aviso6'                      => $aAvisos['aviso6'],
        'msg1'                        => $aAvisos['msg8'],
        'msg2'                        => $aAvisos['msg9'],
        'msg3'                        => $aAvisos['msg10'],
        'msg4'                        => $aAvisos['msg11'],
        'msg5'                        => $aAvisos['msg12'],
        'msg6'                        => $aAvisos['msg13'],
        'imprime_conta'               => $oLeitura->imprime_conta,
        'codigo_coletor'              => $oLeitura->codigo_coletor,
        'aviso_leiturista'            => $oLeitura->aviso_leiturista,
        'leitura_1_ano'               => '',
        'leitura_1_mes'               => '',
        'leitura_1_situacao'          => '',
        'leitura_1_leitura'           => '',
        'leitura_1_consumo'           => '',
        'leitura_1_dias'              => '',
        'leitura_2_ano'               => '',
        'leitura_2_mes'               => '',
        'leitura_2_situacao'          => '',
        'leitura_2_leitura'           => '',
        'leitura_2_consumo'           => '',
        'leitura_2_dias'              => '',
        'leitura_3_ano'               => '',
        'leitura_3_mes'               => '',
        'leitura_3_situacao'          => '',
        'leitura_3_leitura'           => '',
        'leitura_3_consumo'           => '',
        'leitura_3_dias'              => '',
        'leitura_4_ano'               => '',
        'leitura_4_mes'               => '',
        'leitura_4_situacao'          => '',
        'leitura_4_leitura'           => '',
        'leitura_4_consumo'           => '',
        'leitura_4_dias'              => '',
        'leitura_5_ano'               => '',
        'leitura_5_mes'               => '',
        'leitura_5_situacao'          => '',
        'leitura_5_leitura'           => '',
        'leitura_5_consumo'           => '',
        'leitura_5_dias'              => '',
        'leitura_6_ano'               => '',
        'leitura_6_mes'               => '',
        'leitura_6_situacao'          => '',
        'leitura_6_leitura'           => '',
        'leitura_6_consumo'           => '',
        'leitura_6_dias'              => '',
        'titulo_receita_1'            => $this->getTituloReceita(),
        'receita_1_codigo'            => '',
        'receita_1_descricao'         => '',
        'receita_1_parcela'           => '',
        'receita_1_valor'             => '',
        'receita_1_numpre'            => '',
        'receita_2_codigo'            => '',
        'receita_2_descricao'         => '',
        'receita_2_parcela'           => '',
        'receita_2_valor'             => '',
        'receita_2_numpre'            => '',
        'receita_3_codigo'            => '',
        'receita_3_descricao'         => '',
        'receita_3_parcela'           => '',
        'receita_3_valor'             => '',
        'receita_3_numpre'            => '',
        'receita_4_codigo'            => '',
        'receita_4_descricao'         => '',
        'receita_4_parcela'           => '',
        'receita_4_valor'             => '',
        'receita_4_numpre'            => '',
        'titulo_receita_2'            => $this->getTituloReceita(),
        'receita_5_codigo'            => '',
        'receita_5_descricao'         => '',
        'receita_5_parcela'           => '',
        'receita_5_valor'             => '',
        'receita_5_numpre'            => '',
        'receita_6_codigo'            => '',
        'receita_6_descricao'         => '',
        'receita_6_parcela'           => '',
        'receita_6_valor'             => '',
        'receita_6_numpre'            => '',
        'receita_7_codigo'            => '',
        'receita_7_descricao'         => '',
        'receita_7_parcela'           => '',
        'receita_7_valor'             => '',
        'receita_7_numpre'            => '',
        'receita_8_codigo'            => '',
        'receita_8_descricao'         => '',
        'receita_8_parcela'           => '',
        'receita_8_valor'             => '',
        'receita_8_numpre'            => '',
      );

      // Busca Leituras Anteriores
      $aLeiturasAnteriores = $this->getLeiturasAnteriores(
        $oLeitura->codigo_matricula,
        $oLeitura->ano,
        $oLeitura->mes
      );

      // Preenche Campos de Leituras Anteriores
      if ($aLeiturasAnteriores) {
        foreach ($aLeiturasAnteriores as $iIndice => $aLeituraAnterior) {

          $iLeitura = $iIndice + 1;
          $aRegistro["leitura_{$iLeitura}_ano"]      = $aLeituraAnterior['x21_exerc'];
          $aRegistro["leitura_{$iLeitura}_mes"]      = $aLeituraAnterior['x21_mes'];
          $aRegistro["leitura_{$iLeitura}_situacao"] = $aLeituraAnterior['x21_situacao'];
          $aRegistro["leitura_{$iLeitura}_leitura"]  = $aLeituraAnterior['x21_leitura'];
          $aRegistro["leitura_{$iLeitura}_consumo"]  = $aLeituraAnterior['x21_consumo'];
          $aRegistro["leitura_{$iLeitura}_dias"]     = $aLeituraAnterior['x21_dias'];
        }
      }

      // Busca Receitas do Débito
      $aReceitas = $this->getReceitas($oLeitura->codigo);

      // Preenche Campos de Receita
      if ($aReceitas) {
        foreach ($aReceitas as $iIndice => $aReceita) {

          $iReceita = $iIndice + 1;
          $aRegistro["receita_{$iReceita}_codigo"]    = $aReceita['x52_receita'];
          $aRegistro["receita_{$iReceita}_descricao"] = $aReceita['x52_descricao'];
          $aRegistro["receita_{$iReceita}_parcela"]   = $aReceita['x52_numpar'] . '/' . $aReceita['x52_numtot'];
          $aRegistro["receita_{$iReceita}_valor"]     = $aReceita['x52_valor'];
          $aRegistro["receita_{$iReceita}_numpre"]    = $aReceita['x52_numpre'];
        }
      }

      // Busca Número de Economias
      if ($oLeitura->is_condominio == 't') {
        $aRegistro['economias'] = $this->getNumeroEconomias($aRegistro['codigo_contrato']);
      }

      $aRegistros[] = (object) $aRegistro;
    }

    return $aRegistros;
  }

  /**
   * Retorna o número de economias vinculadas ao contrato.
   *
   * @param $iCodigoContrato
   * @return integer|string
   * @throws \DBException
   */
  private function getNumeroEconomias($iCodigoContrato) {

    $oDaoAguaContratoEconomia = new \cl_aguacontratoeconomia();
    $sSqlAguaContratoEconomia = $oDaoAguaContratoEconomia->sql_query_file(
      null, 'count(*) as total', null, 'x38_aguacontrato = ' . $iCodigoContrato
    );

    $rsAguaContratoEconomia = db_query($sSqlAguaContratoEconomia);
    if (!$rsAguaContratoEconomia) {
      throw new \DBException("Não foi possível encontrar informações do Número de Economias.");
    }

    $oEconomias = pg_fetch_object($rsAguaContratoEconomia);

    return $oEconomias->total;
  }

  /**
   * @return bool|resource
   */
  private function getLeituras() {

    $sCampos = implode(', ', array(
      'x50_sequencial        as codigo',
      'x50_rota              as codigo_rota',
      'x49_anousu            as ano',
      'x49_mesusu            as mes',
      'x54_sequencial        as codigo_contrato',
      'x50_numpre            as codigo_cobranca',
      'x50_matric            as codigo_matricula',
      'z01_nome              as nome_responsavel',
      'z01_cgccpf            as documento_responsavel',
      'x50_codlogradouro     as codigo_logradouro',
      'x50_nomelogradouro    as nome_logradouro',
      'x50_tipo              as tipo_logradouro',
      'x50_numero            as numero',
      'x50_letra             as letra',
      'x50_complemento       as complemento',
      'x50_nomebairro        as bairro',
      'x50_cidade            as cidade',
      'x50_estado            as estado',
      'x50_zona              as zona',
      'x50_quadra            as quadra',
      '1                     as economias',
      'x54_condominio        as is_condominio',
      'x13_sequencial        as codigo_categoria_consumo',
      'x13_descricao         as descricao_categoria_consumo',
      'x50_nrohidro          as codigo_hidrometro',
      'x49_aguacoletor       as codigo_coletor',
      'x50_avisoleiturista   as aviso_leiturista',
      'x50_diasleitura       as dias_leitura',
      'x50_dtleituraanterior as dt_leitura_anterior',
      'x50_dtleituraatual    as dt_leitura_atual',
      'x50_valortotal        as valor_total',
      'x50_imprimeconta      as imprime_conta',
      'x50_consumo           as consumo',
      'x50_valoracrescimo    as valor_acrescimo',
      'x50_valordesconto     as valor_desconto',
      'x51_numcgm            as codigo_leiturista',
      'x50_vencimento        as dt_vencimento',
    ));

    $sJoin = implode(' ', array(
      'INNER JOIN aguacoletorexportadados         ON x50_aguacoletorexporta = aguacoletorexporta.x49_sequencial',
      'INNER JOIN aguacoletorexportadadoscontrato ON x50_sequencial = x57_aguacoletorexportadados',
      'INNER JOIN aguacoletorexportadadosleitura  ON x50_sequencial = x51_aguacoletorexportadados',
      'INNER JOIN aguacontrato                    ON x54_sequencial = x57_aguacontrato',
      'INNER JOIN cgm                             ON aguacontrato.x54_cgm = cgm.z01_numcgm',
      'LEFT  JOIN aguacategoriaconsumo            ON x54_aguacategoriaconsumo = aguacategoriaconsumo.x13_sequencial'
    ));

    $sWhere = implode(' and ', array(
      "x49_sequencial = {$this->getCodigoExportacao()}",
    ));

    $sSqlAguaDadosExportacao = "select {$sCampos} from aguacoletorexporta {$sJoin} where {$sWhere} order by x50_ordem asc";

    return db_query($sSqlAguaDadosExportacao);
  }

  /**
   * @return array
   */
  private function getAvisos() {

    /**
     * @todo Criar constante para o código do documento
     */
    $oLibDocumento = new \libdocumento(32);

    $aAvisos = array();
    foreach ($oLibDocumento->getDocParagrafos() as $oParagrafos) {

      $sDescricao = strtolower($oParagrafos->oParag->db02_descr);
      $aAvisos[$sDescricao] = $oParagrafos->oParag->db02_texto;
    }

    return $aAvisos;
  }

  /**
   * @return string
   */
  private function getTituloReceita() {
    return 'Rec   Descricao        Parcela       Valor  Numpre';
  }

  /**
   * @param $iCodigoMatricula
   * @param $iAno
   * @param $iMes
   * @return array
   * @throws \DBException
   */
  private function getLeiturasAnteriores($iCodigoMatricula, $iAno, $iMes) {

    $oDaoExportaDados = new \cl_aguacoletorexportadados();
    $rsLeiturasAnteriores = db_query($oDaoExportaDados->sql_query_leituras_anteriores(
      $iCodigoMatricula, $iAno, $iMes
    ));

    if (!$rsLeiturasAnteriores) {
      throw new \DBException("Não foi possível obter as informações de Leituras Anteriores.");
    }

    return pg_fetch_all($rsLeiturasAnteriores);
  }

  /**
   * @param $iCodigoLeitura
   * @return array
   * @throws \DBException
   */
  private function getReceitas($iCodigoLeitura) {

    $oDaoExportaDadosReceitas = new \cl_aguacoletorexportadadosreceita();
    $sCampos = implode(', ', array(
      'x52_receita',
      'x52_descricao',
      'x52_numpar',
      'x52_numtot',
      'x52_valor',
      'x52_numpre'
    ));
    $sWhere = "x52_aguacoletorexportadados = {$iCodigoLeitura} limit 8";
    $sSqlExportaDadosReceitas = $oDaoExportaDadosReceitas->sql_query_file(
      null, $sCampos, null, $sWhere
    );
    $rsReceitas = db_query($sSqlExportaDadosReceitas);

    if (!$rsReceitas) {
      throw new \DBException("Não foi possível obter informações das Receitas do Débito.");
    }

    return pg_fetch_all($rsReceitas);
  }
}
