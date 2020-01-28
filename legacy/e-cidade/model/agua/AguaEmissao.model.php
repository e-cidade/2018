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
 * Consultas de Emissão de Carnês
 */
class AguaEmissao {

  /**
   * Código da instituição
   *
   * @var int
   */
  private $iCodigoInstituicao;

  /**
   * @return int
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param int $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @param string $sWhere
   * @param string $sOrderBy
   * @param string $sGroupBy
   *
   * @return string
   */
  public function queryInformacoesContratos($sWhere = null, $sOrderBy = null, $sGroupBy = null) {

    /**
     * @todo Refatorar usando um alias mais significativo para as tabelas e campos.
     */
    $sCampos = implode(', ', array(
      'x01_matric          as codigo_matricula',
      'x01_quadra          as quadra',
      'x01_entrega         as entrega_zona',
      'x01_zona            as zona',

      // Código do logradouro de entrega
      '(case when x32_codcorresp is not null then
        x02_codrua
      else
        x01_codrua
      end) as entrega_codigo_logradouro',

      // ### Dados de entrega
      // Tipo logradouro
      '(case when x32_codcorresp is not null then
        ruastipo2.j88_sigla
      else
        ruastipo.j88_sigla
      end) as entrega_tipo_logradouro',

      // Logradouro
      '(case when x32_codcorresp is not null then
        trim(ruas2.j14_nome)
      else
        trim(ruas.j14_nome)
      end) as entrega_nome_logradouro',

      // Número
      '(case when x32_codcorresp is not null then
        x02_numero
      else
        x01_numero
      end) as entrega_numero',

      // Orientação
      "(case when x32_codcorresp is not null then
        x02_orientacao
      else
        x01_orientacao
      end) as entrega_orientacao",

      // Bairro
      '(case when x32_codcorresp is not null then
        bairro2.j13_descr
      else
        bairro.j13_descr
      end) as entrega_bairro',

      // Complemento
      '(case when (x54_condominio is true and x54_responsavelpagamento = 1) then
        x38_complemento
      when x32_codcorresp is not null then
        x02_complemento
      else
        x11_complemento
      end) as entrega_complemento',

      // Área construída
      "to_char(fc_agua_areaconstr(x01_matric), '999990.00') as area_construida",

      // Código logradouro
      '(case when x32_codcorresp is not null then
        x02_codrua
      else
        x01_codrua
      end) as codigo_logradouro',

      // Orientação
      "aguabase.x01_orientacao as orientacao",
      // ### Fim Dados de entrega

      // ### Dados da matrícula
      // Número
      'aguabase.x01_numero        as numero',

      // Logradouro
      'trim(ruas.j14_nome)        as nome_logradouro',

      // Tipo logradouro
      'ruastipo.j88_sigla         as tipo_logradouro',

      // Complemento
      'x11_complemento            as complemento',

      // Bairro
      'bairro.j13_descr           as bairro',

      // Código endereço de entrega
      'x32_codcorresp             as entrega_codigo',

      // Denominação endereço entrega
      'entrega.j85_descr          as denominacao',

      // Localização do endereço de entrega
      'entrega.j85_ender          as localizacao',

      // ### Dados do contrato
      'x54_sequencial             as codigo_contrato',
      'x54_condominio             as is_condominio',
      'x54_sequencial             as codigo_contrato',
      'x54_datainicial            as data_inicial_contrato',
      'x38_sequencial             as codigo_economia',
      'x54_responsavelpagamento   as responsavel_pagamento',
      'x54_emitiroutrosdebitos    as contrato_emitir_outros',
      'x38_emitiroutrosdebitos    as economia_emitir_outros',

      // ### Categoria de consumo
      '(case when x54_condominio is true and x54_responsavelpagamento = 1 then
        trim(categoria_economia.x13_descricao)
      else
        trim(aguacategoriaconsumo.x13_descricao)
      end) as categoria_consumo',

      // Nome/Razão Social
      '(case when x54_condominio is true and x54_responsavelpagamento = 1 then
        trim(responsavel_economia.z01_nome)
      else
        trim(responsavel_contrato.z01_nome)
      end) as nome_responsavel',

      // CGM
      '(case when x54_condominio is true and x54_responsavelpagamento = 1 then
        responsavel_economia.z01_numcgm
      else
        responsavel_contrato.z01_numcgm
      end) as codigo_responsavel',

      // CPF/CNPJ
      '(case when x54_condominio is true and x54_responsavelpagamento = 1 then
        responsavel_economia.z01_cgccpf
      else
        responsavel_contrato.z01_cgccpf
      end) as documento_responsavel',

      // Identidade
      '(case when x54_condominio is true and x54_responsavelpagamento = 1 then
        responsavel_economia.z01_ident
      else
        responsavel_contrato.z01_ident
      end) as numero_identidade',

      // Quantidade de economias
      '(case when x54_condominio then
        (select count(x38_sequencial) from aguacontratoeconomia where x38_aguacontrato = x54_sequencial)
      else
        1
      end) as economias',
    ));

    $sJoins = implode(' ', array(
      'inner join aguacontrato                              on x54_aguabase                      = x01_matric',
      'inner join cgm as responsavel_contrato               on x54_cgm                           = z01_numcgm',
      'left join aguacontratoeconomia                       on x54_sequencial                    = x38_aguacontrato and x54_condominio is true and x54_responsavelpagamento = 1',
      'left join cgm as responsavel_economia                on x38_cgm                           = responsavel_economia.z01_numcgm',
      "left join aguaconstr                                 on x11_matric                        = x01_matric and x11_tipo = 'P'",
      'left join aguabasecorresp                            on x32_matric                        = x01_matric',
      'left join aguacorresp                                on x02_codcorresp                    = x32_codcorresp',
      'left join ruas as ruas2                              on ruas2.j14_codigo                  = x02_codrua',
      'left join ruastipo as ruastipo2                      on ruastipo2.j88_codigo              = ruas2.j14_tipo',
      'left join bairro as bairro2                          on bairro2.j13_codi                  = x02_codbairro',
      'left join ruas                                       on ruas.j14_codigo                   = x01_codrua',
      'left join ruastipo                                   on ruastipo.j88_codigo               = ruas.j14_tipo',
      'left join bairro                                     on bairro.j13_codi                   = x01_codbairro',
      'left join iptucadzonaentrega as entrega              on entrega.j85_codigo                = x01_entrega',
      'left join aguacategoriaconsumo                       on x54_aguacategoriaconsumo          = aguacategoriaconsumo.x13_sequencial',
      'left join aguacategoriaconsumo as categoria_economia on categoria_economia.x13_sequencial = x38_aguacategoriaconsumo',
    ));

    $sQuery = "select distinct {$sCampos} from aguabase {$sJoins}";
    if ($sWhere) {
      $sQuery .= " where {$sWhere} ";
    }

    if ($sGroupBy) {
      $sQuery .= " group by {$sGroupBy} ";
    }

    if ($sOrderBy) {
      $sQuery .= " order by {$sOrderBy} ";
    }

    return $sQuery;
  }

  /**
   * @param integer|null $iCodigoContrato
   * @throws DBException
   * @return resource
   */
  public function getInformacoesEmissao($iCodigoContrato = null) {

    $sWhere = 'fc_agua_existecaract(x01_matric, 5101) is not null';
    if ($iCodigoContrato) {
      $sWhere .= " and x54_sequencial = {$iCodigoContrato} ";
    }

    $sOrderBy = implode(', ', array(
      'entrega_zona',
      'entrega_codigo_logradouro',
      'entrega_orientacao',
      'entrega_numero',
      'entrega_complemento',
      'codigo_matricula',
    ));
    $sQuery = $this->queryInformacoesContratos($sWhere, $sOrderBy);

    $rsContratos = db_query($sQuery);

    if (!$rsContratos || pg_num_rows($rsContratos) === 0) {
      throw new DBException('Não foi possível encontrar as informações necessárias para efetuar a emissão.');
    }

    return $rsContratos;
  }

  /**
   * @deprecated
   * @param int|null $iCodigoContrato
   * @return mixed
   */
  public function getInformacoesContratos($iCodigoContrato = null) {
    return $this->getInformacoesEmissao($iCodigoContrato);
  }

  /**
   * @return bool
   * @throws DBException
   */
  public function removerTabelaTemporaria() {

    $rsDropTable = db_query('drop table if exists tmp_arrecad_emissao');
    if (!$rsDropTable) {
      throw new DBException('Não foi possível excluir tabela temporária.');
    }

    return true;
  }

  /**
   * @return bool
   * @throws DBException
   */
  public function criarTabelaTemporaria() {

    $rsCreateTable = db_query('create temporary table tmp_arrecad_emissao as select * from arrecad limit 0');
    if (!$rsCreateTable) {
      throw new DBException('Não foi possível criar tabela temporária de débitos.');
    }

    $rsCreateIndex = db_query('create index tmp_arrecad_emissao_in on tmp_arrecad_emissao(k00_numpre, k00_numpar)');
    if (!$rsCreateIndex) {
      throw new DBException('Não foi possível criar os indeces da tabela temporária');
    }

    return true;
  }

  /**
   * @param $iCodigoResponsavel
   * @param $iCodigoInstituicao
   * @return bool
   * @throws DBException
   */
  public function preencherTabelaTemporaria($iCodigoResponsavel, $iCodigoInstituicao) {

    $rsTruncateTable = db_query('truncate table tmp_arrecad_emissao');
    if (!$rsTruncateTable) {
      throw new DBException('Não foi possível excluir os registros da tabela temporária.');
    }

    $sSqlInsert = "
      insert into tmp_arrecad_emissao
      select arrecad.* from arrecad
        inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre and arrenumcgm.k00_numcgm = {$iCodigoResponsavel}
        inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre and arreinstit.k00_instit = {$iCodigoInstituicao}
    ";
    $rsInsert = db_query($sSqlInsert);
    if (!$rsInsert) {
      throw new DBException('Não foi possível inserir os registros na tabela temporária.');
    }

    $rsAnalyze = db_query('analyze tmp_arrecad_emissao');
    if (!$rsAnalyze) {
      throw new DBException('Não foi possível fazer analise da tabela temporária.');
    }

    return true;
  }

  /**
   * @param int $oContrato
   * @param int $iMes
   * @param int $iAno
   * @param int $iCodigoTipo
   *
   * @return array
   * @throws DBException
   * @throws ParameterException
   */
  public function getDebitos($oContrato, $iMes, $iAno, $iCodigoTipo, $sDataVencimento) {

    if (!$this->iCodigoInstituicao) {
      throw new ParameterException('Código da instituição não foi informado.');
    }

    $oDataVencimento = new DateTime($sDataVencimento);

    $sTemporaryTable = 'tmp_arrecad_emissao';

    /**
     * Filtra os débitos por economia, caso seja a emissão de condomínio com responsável pagamento Economia
     */
    $sFiltroEconomia = null;
    if ($oContrato->is_condominio == 't' && $oContrato->codigo_economia) {

      $sFiltroEconomia = 'and ' . implode(' and ', array(
        "aguacalc.x22_manual = '1'",
        "aguacalc.x22_aguacontratoeconomia = {$oContrato->codigo_economia}",
      ));
    }

    $aSql = array();

    /**
     * Tarifas de água
     */
    $aSql[] = "
      select
        arrecad.k00_receit as codigo_receita,
        arrecad.k00_numpre as codigo_cobranca,
        arrecad.k00_numpar as parcela,
        arrecad.k00_numtot as total_parcelas,
        arrecad.k00_tipo   as tipo,
        arrecad.k00_dtvenc as data_vencimento,
        tabrec.k02_descr   as descricao,
        round(sum(arrecad.k00_valor), 2) as valor
      from {$sTemporaryTable} as arrecad
        inner join tabrec       on tabrec.k02_codigo     = arrecad.k00_receit
      where
        arrecad.k00_tipo = {$iCodigoTipo}
        and exists (
          select
            1
          from
            aguacalc
          where
            aguacalc.x22_tipo = 2
            and aguacalc.x22_aguacontrato = {$oContrato->codigo_contrato}
            and aguacalc.x22_exerc = {$iAno}
            and aguacalc.x22_mes = {$iMes}
            and aguacalc.x22_numpre = arrecad.k00_numpre
            {$sFiltroEconomia}
        )
      group by
        codigo_receita,
        codigo_cobranca,
        parcela,
        total_parcelas,
        tipo,
        data_vencimento,
        descricao";

    /**
     * Outros débitos (diversos, parcelamentos de foro e parcelamentos de dívida)
     */
    $lContratoEmitirOutros = false;
    $lEconomiaEmitirOutros = false;
    if ($oContrato->is_condominio === 't' && (int) $oContrato->responsavel_pagamento == AguaContrato::RESPONSAVEL_PAGAMENTO_ECONOMIA) {
      $lEconomiaEmitirOutros = $oContrato->economia_emitir_outros === 't';
    } else {
      $lContratoEmitirOutros = $oContrato->contrato_emitir_outros === 't';
    }

    if ($lEconomiaEmitirOutros || $lContratoEmitirOutros) {

      /**
       * Parcelamento Dívida
       */
      $aSql[] = "
        select
          min(arrecad.k00_receit)          as codigo_receita,
          arrecad.k00_numpre               as codigo_cobranca,
          arrecad.k00_numpar               as parcela,
          arrecad.k00_numtot               as total_parcelas,
          arrecad.k00_tipo                 as tipo,
          arrecad.k00_dtvenc               as data_vencimento,
          'PARCELAM DIV'                   as descricao,
          round(sum(arrecad.k00_valor), 2) as valor
        from {$sTemporaryTable} as arrecad
          inner join arrenumcgm   on arrenumcgm.k00_numpre = arrecad.k00_numpre
          inner join tabrec       on tabrec.k02_codigo     = arrecad.k00_receit
          inner join arretipo     on arretipo.k00_tipo     = arrecad.k00_tipo
        where
          arretipo.k03_tipo = 6
          and arretipo.k00_instit = {$this->iCodigoInstituicao}
          and extract (year from arrecad.k00_dtvenc) = {$oDataVencimento->format('Y')}
          and extract (month from arrecad.k00_dtvenc) = {$oDataVencimento->format('m')}
          and arrenumcgm.k00_numcgm = {$oContrato->codigo_responsavel}
        group by
          codigo_cobranca,
          parcela,
          total_parcelas,
          tipo,
          data_vencimento,
          descricao";

      /**
       * Parcelamento Foro
       */
      $aSql[] = "
        select
          min(arrecad.k00_receit)          as codigo_receita,
          arrecad.k00_numpre               as codigo_cobranca,
          arrecad.k00_numpar               as parcela,
          arrecad.k00_numtot               as total_parcelas,
          arrecad.k00_tipo                 as tipo,
          arrecad.k00_dtvenc               as data_vencimento,
          'PARCELAM FORO'                  as descricao,
          round(sum(arrecad.k00_valor), 2) as valor
        from {$sTemporaryTable} as arrecad
          inner join arrenumcgm   on arrenumcgm.k00_numpre = arrecad.k00_numpre
          inner join tabrec       on tabrec.k02_codigo     = arrecad.k00_receit
          inner join arretipo     on arretipo.k00_tipo     = arrecad.k00_tipo
        where
          arretipo.k03_tipo = 13
          and arretipo.k00_instit = {$this->iCodigoInstituicao}
          and extract (year from arrecad.k00_dtvenc) = {$oDataVencimento->format('Y')}
          and extract (month from arrecad.k00_dtvenc) = {$oDataVencimento->format('m')}
          and arrenumcgm.k00_numcgm = {$oContrato->codigo_responsavel}
        group by
          codigo_cobranca,
          parcela,
          total_parcelas,
          tipo,
          data_vencimento,
          descricao";

      /**
       * Outros
       */
      $aSql[] = "
        select
          arrecad.k00_receit               as codigo_receita,
          arrecad.k00_numpre               as codigo_cobranca,
          arrecad.k00_numpar               as parcela,
          arrecad.k00_numtot               as total_parcelas,
          arrecad.k00_tipo                 as tipo,
          arrecad.k00_dtvenc               as data_vencimento,
          tabrec.k02_descr                 as descricao,
          round(sum(arrecad.k00_valor), 2) as valor
        from {$sTemporaryTable} as arrecad
          inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre
          inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit
          inner join arretipo   on arretipo.k00_tipo     = arrecad.k00_tipo
        where
          arretipo.k00_instit = {$this->iCodigoInstituicao}
          and arretipo.k03_tipo not in(6, 13)
          and arrecad.k00_tipo not in({$iCodigoTipo})
          and arrenumcgm.k00_numcgm = {$oContrato->codigo_responsavel}
          and extract (year from arrecad.k00_dtvenc) = {$oDataVencimento->format('Y')}
          and extract (month from arrecad.k00_dtvenc) = {$oDataVencimento->format('m')}
        group by
          codigo_receita,
          codigo_cobranca,
          parcela,
          total_parcelas,
          tipo,
          data_vencimento,
          descricao";
    }

    $sSql = implode(' union ', $aSql) . " order by codigo_receita desc";

    $rsDebitos = db_query($sSql);
    if (!$rsDebitos) {
      throw new DBException('Não foi possível encontrar as informações de débito.');
    }

    if (pg_num_rows($rsDebitos) == 0) {
      return array();
    }

    return pg_fetch_all($rsDebitos);
  }

  /**
   * @param $iCodigoContrato
   * @param DateTime $oDataInicial
   * @param int $iTotalLeituras
   * @return array
   * @throws DBException
   */
  public function getUltimasLeituras($iCodigoContrato, DateTime $oDataInicial, $iTotalLeituras = 6) {

    $sCampos = implode(', ', array(
      'x21_exerc      as exercicio',
      'x21_mes        as mes',
      'x21_leitura    as leitura',
      'x17_descr      as descricao',
      '30 :: integer  as dias',
      'x21_dtleitura  as data',
      'case when x21_excesso >= 0 then
        x21_consumo + x21_excesso
       else
        x21_consumo
       end            as consumo',
    ));

    $sJoin = implode(' ', array(
      'inner join aguahidromatric     on x04_codhidrometro = x21_codhidrometro',
      'inner join aguacontratoligacao on x04_codhidrometro = x55_aguahidromatric',
      'inner join aguacontrato        on x54_sequencial    = x55_aguacontrato',
      'inner join aguasitleitura      on x17_codigo        = x21_situacao'
    ));

    $oDataInicial->modify('+1 month');
    $sMeses = implode(', ', array_map(function () use ($oDataInicial) {
      $oDataInicial->modify('-1 month');
      return $oDataInicial->format('(Y, n)');
    }, range(1, $iTotalLeituras)));

    $sWhere = implode(' and ', array(
      'agualeitura.x21_status = ' . AguaLeitura::STATUS_ATIVA,
      "aguacontrato.x54_sequencial = {$iCodigoContrato}",
      "(agualeitura.x21_exerc, agualeitura.x21_mes) in ({$sMeses})"
    ));

    $sOrder = "x21_exerc desc, x21_mes desc";
    $sSql = "select {$sCampos} from agualeitura {$sJoin} where {$sWhere} order by {$sOrder}";
    $rsLeituras = db_query($sSql);

    if (!$rsLeituras) {
      throw new DBException('Não foi possível encontrar informações sobre as leituras do contrato.');
    }

    if (pg_num_rows($rsLeituras) == 0) {
      return array();
    }

    return pg_fetch_all($rsLeituras);
  }

  /**
   * @param $iCodigoTipo
   * @param $iCodigoInstituicao
   * @return object
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function getTipoArrecadacao($iCodigoTipo, $iCodigoInstituicao) {

    if (!$iCodigoTipo) {
      throw new ParameterException('Código do Tipo de Arrecadação não informado.');
    }

    if (!$iCodigoInstituicao) {
      throw new ParameterException('Código da Instituiação não informado.');
    }

    $sSqlArrecadacao = "
      select k00_codbco,
             k00_codage,
             k00_descr,
             k00_hist1,
             k00_hist2,
             k00_hist3,
             k00_hist4,
             k00_hist5,
             k00_hist6,
             k00_hist7,
             k00_hist8,
             k03_tipo,
             k00_tipoagrup,
             k00_tercdigrecnormal
      from   arretipo
      where  k00_tipo   = {$iCodigoTipo}
        and  k00_instit = {$iCodigoInstituicao}";

    $rsTipoArrecadacao = db_query($sSqlArrecadacao);
    if (!$rsTipoArrecadacao) {
      throw new DBException('Não foi possível encontrar as informações do Tipo de Arrecadação.');
    }

    if (pg_num_rows($rsTipoArrecadacao) == 0) {
      throw new BusinessException('Código do Banco não está Cadastrado no arquivo de Tipo de Arrecadação.');
    }

    $oTipoArrecadacao = pg_fetch_object($rsTipoArrecadacao);
    if (!$oTipoArrecadacao->k00_tercdigrecnormal) {
      throw new BusinessException('Terceiro dígito do Código de Barras não está configurado.');
    }

    return $oTipoArrecadacao;
  }
}
