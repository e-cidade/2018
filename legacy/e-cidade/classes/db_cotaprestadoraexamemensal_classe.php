<?php

class cl_cotaprestadoraexamemensal extends DAOBasica
{
  public function __construct()
  {
    parent::__construct("agendamento.cotaprestadoraexamemensal");
  }

  /**
   * Query para buscar todas as competências e seus dados
   *
   * @param  string $sCampos
   *
   * @return string
   */
  public function getAll($sCampos)
  {
    $sQuery  = " select $sCampos ";
    $sQuery .= "   from cotaprestadoraexamemensal ";
    $sQuery .= "        inner join grupoexame on age02_cotaprestadoraexamemensal = age01_sequencial ";
    $sQuery .= "        inner join grupoexameprestador on age03_grupoexame = age02_sequencial ";
    $sQuery .= "        inner join sau_prestadorvinculos on s111_i_codigo = age03_prestadorvinculos ";
    $sQuery .= "        inner join sau_procedimento on sd63_i_codigo = s111_procedimento ";

    return $sQuery;
  }

  /**
   * Query para buscar todas as competências e seus dados
   *
   * @param  string $sCampos
   *
   * @return string
   */
  public function getAllMunicipio($sCampos)
  {
    $sQuery  = " select $sCampos ";
    $sQuery .= "   from cotaprestadoraexamemensal ";
    $sQuery .= "        inner join grupoexame on age02_cotaprestadoraexamemensal = age01_sequencial ";
    $sQuery .= "        inner join grupomunicipio on age04_grupoexame = age02_sequencial ";
    $sQuery .= "        inner join sau_procedimento on sd63_i_codigo = age04_procedimento ";

    return $sQuery;
  }

  /**
   * Criamos o SQL que busca todas as cotas do prestador definido
   *
   * @param  integer $iPrestador
   * @param  string  $sCampos
   *
   * @return string
   */
  public function getQueryByPrestador( $iPrestador, $sCampos = "*", $sOrderBy = null, $sGroupBy = null )
  {
    $sQuery  = $this->getAll($sCampos);
    $sQuery .= "  where s111_i_prestador = $iPrestador ";

    if (!empty($sGroupBy)) {
      $sQuery .= " group by {$sGroupBy}";
    }

    if (!empty($sOrderBy)) {
      $sQuery .= " order by {$sOrderBy}";
    }

    return $sQuery;
  }

  /**
   * Função que criar a query para buscar os dados das cotas e grupos por prestador e precedimento
   *
   * @param  integer $iPrestadorVinculo
   * @param  integer $iMes
   * @param  integer $iAno
   * @param  string  $sCampos
   *
   * @return string           Query pronta
   */
  public function sql_query_grupo( $iPrestadorVinculo, $iMes = null, $iAno = null, $sCampos = "*" )
  {
    $sSql  = $this->getAll($sCampos);
    $sSql .= "  where age03_prestadorvinculos = $iPrestadorVinculo ";

    if ( $iMes ) {
      $sSql .= "    and age01_mes = $iMes ";
    }

    if ( $iAno ) {
      $sSql .= "    and age01_ano = $iAno ";
    }

    return $sSql;
  }

  /**
   * Criamos o SQL que busca a cota do grupo
   *
   * @param  integer $iGrupo
   * @param  string  $sCampos
   *
   * @return string
   */
  public function getQueryByGrupo( $iGrupo, $sCampos = "*", $sOrderBy = null, $sGroupBy = null )
  {
    $sQuery  = $this->getAll($sCampos);
    $sQuery .= "  where age02_sequencial = $iGrupo ";

    if (!empty($sGroupBy)) {
      $sQuery .= " group by {$sGroupBy}";
    }

    if (!empty($sOrderBy)) {
      $sQuery .= " order by {$sOrderBy}";
    }

    return $sQuery;
  }

  public function getQuery( $sCampos = "*", $sWhere = null )
  {
    $sQuery  = $this->getAll($sCampos);

    if (!empty($sWhere)) {
      $sQuery .= " where {$sWhere}";
    }

    return $sQuery;
  }

  public function getQueryMunicipio( $sCampos = "*", $sWhere = null )
  {
    $sQuery  = $this->getAllMunicipio($sCampos);

    if (!empty($sWhere)) {
      $sQuery .= " where {$sWhere}";
    }

    return $sQuery;
  }

  public function getAllGrupoMunicipio($sCampos, $sOrderBy = null, $sGroupBy = null)
  {
    $sQuery  = $this->getAllMunicipio($sCampos);

    if (!empty($sGroupBy)) {
      $sQuery .= " group by {$sGroupBy}";
    }

    if (!empty($sOrderBy)) {
      $sQuery .= " order by {$sOrderBy}";
    }

    return $sQuery;
  }

  /**
   * Criamos o SQL que busca a cota do grupo
   *
   * @param  integer $iGrupo
   * @param  string  $sCampos
   *
   * @return string
   */
  public function getQueryByGrupoMunicipio( $iGrupo, $sCampos = "*", $sOrderBy = null, $sGroupBy = null )
  {
    $sQuery  = $this->getAllMunicipio($sCampos);
    $sQuery .= "  where age02_sequencial = $iGrupo ";

    if (!empty($sGroupBy)) {
      $sQuery .= " group by {$sGroupBy}";
    }

    if (!empty($sOrderBy)) {
      $sQuery .= " order by {$sOrderBy}";
    }

    return $sQuery;
  }
}
