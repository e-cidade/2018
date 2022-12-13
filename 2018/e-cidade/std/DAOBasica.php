<?php
/**
 * Motivos para este refactor
 *
 * 1 - Maior escalabilidade, facilidade para novas mudanças
 * 2 - Possibilidade de implementacao de biblioteca de traducao msgs do banco
 * 3 - Possibilidade de implementacao de i18n (necessario mais cedo ou mais tarde teremos que fazer)
 * 4 - Evolucao dos metodos de programacao, se aproximando mais de POO
 * 5 - Codigo centralizado
 * 6 - A caminho de um ORM
 *
 */
abstract class DAOBasica {

  public $rotulo = null;
  public $query_sql = null;
  public $numrows = 0;
  public $numrows_incluir = 0;
  public $numrows_alterar = 0;
  public $numrows_excluir = 0;
  public $erro_status = null;
  public $erro_sql = null;
  public $erro_banco = null;
  public $erro_msg = null;
  public $erro_campo = null;
  public $pagina_retorno = null;

  private static $tables;

  private $aDados   = array ();

  protected $primaryKeyField = null;
  /**
   * @var DDTabelaXML
   */
  private $DDTabela = null;

  private $lSalvarAccount = true;

  const INSERT    = 1;
  const UPDATE    = 2;
  const DELETE    = 3;
  const QUERY     = 4;
  const QUERYFULL = 3;

  const VALOR_PADRAO = "padrao";
  const VALOR_INSERT = "insert";
  const VALOR_TIPO   = "tipo";

  const TIPO_ACCOUNT_INCLUSAO  = "I";
  const TIPO_ACCOUNT_ALTERACAO = "A";
  const TIPO_ACCOUNT_EXCLUSAO  = "E";

  /**
   * Valores para comparação com base no tipo de um campo
   * @var array
   */
  static private $aTipos = array(
    'insert' => array( 'boolean' => null,
                       'bool'    => null,
                       'char'    => "",
                       'varchar' => "",
                       'text'    => "",
                       'date'    => "",
                       'float4'  => "",
                       'float8'  => "",
                       'numeric' => "",
                       'int4'    => null,
                       'int8'    => null,
                       'oid'     => null
    ),
    'padrao' => array( 'boolean' => 'f',
                       'bool'    => 'f',
                       'char'    => null,
                       'varchar' => null,
                       'text'    => null,
                       'date'    => null,
                       'float4'  => 0,
                       'float8'  => 0,
                       'numeric' => 0,
                       'int4'    => 0,
                       'int8'    => 0,
                       'oid'     => 0
    ),
    'tipo' => array( 'boolean' => "f",
                     'bool'    => "f",
                     'char'    => "",
                     'varchar' => "",
                     'text'    => "",
                     'date'    => "",
                     'float4'  => "",
                     'float8'  => "",
                     'numeric' => "",
                     'int4'    => "",
                     'int8'    => "",
                     'oid'     => ""
    )
  );

  public function __construct($sTableName) {

    $this->rotulo   = new rotulo ( $sTableName );
    $this->DDTabela = DDXMLFactory::getInstance ( $sTableName );

    $aTabela = explode('.', $sTableName);
    if (trim(strtolower($aTabela[0])) == 'plugins' || count($aTabela) == 1) {
      $this->lSalvarAccount = false;
    }

    // @todo verificar possibilidade de implementar classe static para manipular
    // GPC e $_SERVER

    /**
     * Define os valores iniciais das propriedades da classe assim como nas DAOs geradas pelo sistema
     */
    foreach ( $this->DDTabela->aCampos as $oCampo ) {

      if ($oCampo->ispk) {
        $this->primaryKeyField = $oCampo;
      }
      if ($oCampo->ispk  != 't'){
        $this->{$oCampo->name} = $oCampo->inivalue;
      }
    }
    $this->pagina_retorno = basename ( $_SERVER ["PHP_SELF"] );
  }

  public function __isset($sName) {
    return isset($this->aDados[$sName]);
  }

  public function __set($sName, $sValue) {
    // @todo implementar maquina de estado (nao sei se eh aqui)
    $this->aDados [$sName] = $sValue;
  }

  public function __get($sName) {
    if (isset ( $this->aDados [$sName] )) {
      return $this->aDados [$sName];
    }
    // @todo verificar qual o melhor retorno
    return null;
  }

  public function setSalvarAccount($lSalvarAccount) {
    $this->lSalvarAccount = (bool) $lSalvarAccount;
  }

  /**
   * Carrega os dados de $_POST vindos de um formulário, caso houver
   * @param array $aCamposVerificar
   */
  public function loadPost($aCamposVerificar = Array()) {

    foreach ( $this->DDTabela->aCampos as $oCampo ) {

      if (count ( $aCamposVerificar ) > 0 && ! in_array ( $oCampo->name, $aCamposVerificar )) {
        continue;
      }

      /**
       * Verifica se o valor inicial da propriedade foi alterado
       */
      if ( $this->{$oCampo->name} != $this->getValorComparacao ( $oCampo->conteudo, self::VALOR_TIPO ) ) {
        continue;
      }

      /**
       * Se o valor da propriedade não foi alterado, verifica se foi postado a propriedade e altera o valor
       */
      if ( isset($_POST[$oCampo->name]) ) {
        $this->{$oCampo->name} = $_POST[$oCampo->name];
      }
    }
  }


  private function getValorComparacao($sTipo, $sOperacao) {

    $sChave = strtolower ( trim ( substr ( $sTipo, 0, (strpos ( $sTipo, "(" ) ? strpos ( $sTipo, "(" ) : strlen ( $sTipo )) ) ) );
    if ( !array_key_exists($sOperacao, self::$aTipos) ) {
      throw new ParameterException("Operação \"{$sOperacao}\" não informada.");
    }

    if ( !array_key_exists($sChave, self::$aTipos[$sOperacao]) ) {
      throw new ParameterException("Tipo de dado \"{$sChave}\" não existe no dicionário de dados.");
    }

    return self::$aTipos[$sOperacao][$sChave];
  }


  // @todo comentar metodos antigos com @deprecated
  public function atualizacampos($exclusao = false) {
    if ($exclusao == false) {
      $this->loadPost ();
    } else {
      // $this->ht09_sequencial = ($this->ht09_sequencial == "" ? @$GLOBALS ["HTTP_POST_VARS"] ["ht09_sequencial"] : $this->ht09_sequencial);
      // @todo retirar campo fixo e ler do dicionario chave primaria

      $aCampos = array ();
      foreach ( $this->DDTabela->getFieldsPk () as $oCampoChave ) {
        $aCampos [] = $oCampoChave->name;
      }
      $this->loadPost ( $aCampos );
    }
  }

  // @todo comentar metodos antigos com @deprecated
  public function erro($mostra, $retorna) {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {

      $sMensagem = str_replace("\n", '\n', $this->erro_msg);

      echo "<script>alert(\"" . $sMensagem . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }

  public function getParametros($aParametros = array(), $iOperacao) {

    $aRetorno = array ();
    $iIndice  = 0;

    foreach ( $this->DDTabela->getFieldsPk() as $oCampo ) {
      if (!isset($aParametros [$iIndice])){
        $iIndice ++;
        continue;
      }

      $aRetorno [$oCampo->name] = $aParametros [$iIndice];
      $this->{$oCampo->name}    = $aParametros [$iIndice];
      $iIndice ++;
    }

    switch ($iOperacao) {

      case self::INSERT :

        break;

      case self::UPDATE :

        //@todo testar melhor este codigo
        $aRetorno ["sWhere"] = (! empty ( $aParametros [$iIndice] ) ? $aParametros [$iIndice] : null);
        break;

      case self::DELETE :

        // @todo testar melhor este codigo
        $aRetorno ["sWhere"] = (! empty ( $aParametros [$iIndice] ) ? $aParametros [$iIndice] : null);
        break;

      case self::QUERY :

        // @todo testar melhor este codigo
        $aRetorno ["sCampos"]  = (! empty ( $aParametros [$iIndice] ) ? $aParametros [$iIndice] : null);
        $aRetorno ["sOrderBy"] = (! empty ( $aParametros [++ $iIndice] ) ? $aParametros [$iIndice] : null);
        $aRetorno ["sWhere"]   = (! empty ( $aParametros [++ $iIndice] ) ? $aParametros [$iIndice] : null);
        $aRetorno ["sGroupBy"] = (! empty ( $aParametros [++ $iIndice] ) ? $aParametros [$iIndice] : null);
        break;

      default :
        break;

    }

    return $aRetorno;

  }

  public function getStringCamposChave() {

    $sIfem   = "";
    $sCampos = "";
    foreach ( $this->DDTabela->getFieldsPk() as $oCampoChavePrimaria ) {

      $sCampos .= $this->{$oCampoChavePrimaria->name} . $sIfem;
      $sIfem    = "-";
    }
    if (! empty ( $sCampos )) {
      return $sCampos;
    }
    return false;
  }

  /**
   * Formata um campo de acordo com o tipo para utilização dentro de um INSERT ou UPDATE
   *
   * @param  string $sNomeCampo Nome do campo na tabela.
   * @param  string $sValor     Valor a ser formatado.
   * @return string
   */
  protected function formatarAtributo($sNomeCampo, $sValor) {

    $aValoresFormatados ['boolean'] = ( $sValor == "t" || $sValor == "true" || $sValor === true ? "true" : "false");
    $aValoresFormatados ['bool']    = ( $sValor == "t" || $sValor == "true" || $sValor === true ? "true" : "false");
    $aValoresFormatados ['char']    = "'" . $sValor . "'";
    $aValoresFormatados ['varchar'] = "'" . $sValor . "'";
    $aValoresFormatados ['text']    = "'" . $sValor . "'";
    $aValoresFormatados ['oid']     = (empty($sValor) ? "null" : $sValor);
    $aValoresFormatados ['date']    = (empty($sValor) ? "null" : "'" . implode("-", array_reverse(explode("/",$sValor))) . "'");
    $aValoresFormatados ['float4']  = (is_null($sValor) || $sValor === "" ? "null" : $sValor);
    $aValoresFormatados ['float8']  = (is_null($sValor) || $sValor === "" ? "null" : $sValor);
    $aValoresFormatados ['int4']    = (is_null($sValor) || $sValor === "" ? "null" : $sValor);
    $aValoresFormatados ['int8']    = (is_null($sValor) || $sValor === "" ? "null" : $sValor);
    $aValoresFormatados ['numeric'] = (is_null($sValor) || $sValor === "" ? "null" : $sValor);

    foreach ( $this->DDTabela->aCampos as $oCampo ) {

      if ($oCampo->name == $sNomeCampo) {

        $sChave = strtolower ( trim ( substr ( $oCampo->conteudo, 0, (strpos ( $oCampo->conteudo, "(" ) ? strpos ( $oCampo->conteudo, "(" ) : strlen ( $oCampo->conteudo )) ) ) );
        return $aValoresFormatados [$sChave];
      }
    }

    return $sValor;
  }

  /**
   * Retorna todos os campos para utilização dentro do INSERT.
   *
   * @return string
   */
  public function getValoresInsert() {

    $aDados         = array ();
    foreach ( $this->aDados as $sKey => $sValue ) {
      $aDados [$sKey] = $this->formatarAtributo ( $sKey, $sValue );
    }
    return implode ( ',', $aDados );
  }

  public function incluir() {

    $this->loadPost();
    $sCampos     = $this->getStringCamposChave();
    $aParametros = $this->getParametros( func_get_args (), self::INSERT );
    foreach ( $this->DDTabela->getCampos() as $oCampo ) {

      /**
       * Se o campo não aceita valor nulo
       */
      if ( ($oCampo->null == 'f' && !$oCampo->getSequence()) && !$this->validarCampoNulo($oCampo)) {
        return false;
      }

      /**
       * Se tem sequencia para o campo
       */
      if ($oCampo->getSequence()) {

        if (empty($this->{$oCampo->name})) {

          $rsNextval = db_query("select nextval('{$oCampo->getSequence()->name}') as sequencial");
          if ( !$rsNextval ) {

            $this->erro_banco  = str_replace ( "\n", "", @pg_last_error () );
            $this->erro_sql    = "Verifique o cadastro da sequencia: {$oCampo->getSequence()->name} do campo: {$oCampo->name}";
            $this->erro_msg    = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
            $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
            $this->erro_status = "0";
            return false;
          }
          $this->{$oCampo->name} = db_utils::fieldsMemory($rsNextval, 0)->sequencial;
        } else {

          $rsLastValue = db_query("select last_value from {$oCampo->getSequence()->name}");
          $this->{$oCampo->name} = $aParametros[$oCampo->name];
          $iLastValue  = db_utils::fieldsMemory($rsLastValue, 0)->last_value;

          if (($rsLastValue != false) && ($iLastValue < $this->{$oCampo->name})) {

            $this->erro_sql    = " Campo {$this->{$oCampo->name}} maior que último número da sequencia.";
            $this->erro_banco  = "Sequencia menor que este número.";
            $this->erro_msg    = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
            $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
            $this->erro_status = "0";
            return false;
          }
        }
      }

      if(!$this->validarChavePrimaria($oCampo)) {
        return false;
      }
    }

    $sCamposInsert  = implode ( ',', array_keys ( $this->aDados ) );
    $sValoresInsert = $this->getValoresInsert();


    $sSql     = "INSERT INTO {$this->DDTabela->name} ({$sCamposInsert}) VALUES ($sValoresInsert) ";
     
    $rsInsert = db_query( $sSql );

    if (! $rsInsert) {

      $this->erro_banco = str_replace ( "\n", "", @pg_last_error () );
      $this->erro_sql  = "{$this->description} ({$sCampos}) nao Incluído. Inclusao Abortada.";
      $this->erro_msg  = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
      $this->erro_msg .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );

      if (strpos(strtolower($this->erro_banco), "duplicate key" ) != 0) {

        $this->erro_sql   = "{$this->description} ({$sCampos}) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
        $this->erro_banco = "{$this->description} já Cadastrado";
        $this->erro_msg  .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      }
      $this->erro_status     = "0";
      $this->numrows_incluir = 0;
      return false;
    }

    $this->erro_banco  = "";
    $this->erro_sql    = "Inclusao efetuada com sucesso\n";
    $this->erro_sql   .= "Valores : " . $this->getStringCamposChave ();
    $this->erro_msg    = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
    $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
    $this->erro_status = "1";

    if (!$this->salvarAccount(self::TIPO_ACCOUNT_INCLUSAO)) {

      $this->erro_status = "0";
      $this->erro_msg = "Não foi possível salvar os dados da auditoria.";
    }
    return true;
  }

  public function alterar() {

    $this->loadPost();
    $aParametros = $this->getParametros( func_get_args (), self::UPDATE );

    foreach ( $this->DDTabela->getCampos() as $oCampo ) {

      if (($oCampo->null == 'f' && !$this->validarCampoNulo($oCampo)) || !$this->validarChavePrimaria($oCampo)) {
        return false;
      }
    }

    $sCamposUpdate = "";
    $sVirgula      = "";
    foreach ( $this->aDados as $sChave => $sValor ) {

      $sCamposUpdate .= "{$sVirgula} {$sChave} = " . $this->formatarAtributo ( $sChave, $sValor );
      $sVirgula       = ",";
    }

    /**
     * Montando where para campos chave primaria
     */
    $sWhere = "";
    $sAnd   = "";
    foreach ($this->DDTabela->getFieldsPk() as $oChave ) {

      if ($this->{$oChave->name} != null) {

        $sWhere .= " {$sAnd} {$oChave->name} = " . $this->formatarAtributo ( $oChave->name, $this->{$oChave->name} );
        $sAnd    = "and";
      }
    }

    if (!empty($sWhere)) {
      $sWhere = " where {$sWhere} ";
    }

    $sSql     = "UPDATE {$this->DDTabela->name} SET {$sCamposUpdate} {$sWhere}";
    $rsUpdate = db_query ( $sSql );

    if ($rsUpdate == false) {

      $this->erro_banco      = str_replace ( "\n", "", @pg_last_error () );
      $this->erro_sql        = "{$this->description} nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
      $this->erro_msg        = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
      $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      $this->erro_status     = "0";
      $this->numrows_alterar = 0;
      return false;
    }

    if (pg_affected_rows($rsUpdate) == 0) {

      $this->erro_banco      = "";
      $this->erro_sql        = "{$this->descricao} nao foi Alterado. Alteracao Executada.\n";
      $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
      $this->erro_msg        = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
      $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      $this->erro_status     = "1";
      $this->numrows_alterar = 0;
      return true;
    }

    $this->erro_banco      = "";
    $this->erro_sql        = "Alteração efetuada com Sucesso\n";
    $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
    $this->erro_msg        = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
    $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
    $this->erro_status     = "1";
    $this->numrows_alterar = pg_affected_rows ( $rsUpdate );

    if (!$this->salvarAccount(self::TIPO_ACCOUNT_ALTERACAO)) {

      $this->erro_status = "0";
      $this->erro_msg = "Não foi possível salvar os dados da auditoria.";
    }
    return true;
  }

  // funcao para exclusao
  public function excluir() {

    $aParametros = $this->getParametros ( func_get_args (), self::DELETE );
    $sSql        = " DELETE FROM {$this->DDTabela->name} WHERE ";
    $sWhere      = "";
    $sAnd        = "";

    if (empty($aParametros['sWhere'])) {

      foreach ( $aParametros as $sNomeParametro => $sValorParametro ) {

        if ($sNomeParametro == 'sWhere') {
          continue;
        }

        if (!empty($sValorParametro)) {
          $sWhere .= " {$sAnd} {$sNomeParametro} = ".$this->formatarAtributo($sNomeParametro, $sValorParametro);
          $sAnd    = "and";
        }

      }
    } else {
      $sWhere = $aParametros['sWhere'];
    }

    $sSql  .= $sWhere;
    $result = db_query ( $sSql );
    if ($result == false) {

      $this->erro_banco      = str_replace ( "\n", "", @pg_last_error () );
      $this->erro_sql        = "Documentos do Tipo de Grupo de Programa nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
      $this->erro_msg        = "Usuário: \\n\\n " . DBString::utf8_decode_all($this->erro_sql) . " \\n\\n";
      $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n" ) );
      $this->erro_status     = "0";
      $this->numrows_excluir = 0;
      return false;
    }

    if (pg_affected_rows($result) == 0) {

      $this->erro_banco      = "";
      $this->erro_sql        = "Documentos do Tipo de Grupo de Programa nao Encontrado. Exclusão não Efetuada.\\n";
      $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
      $this->erro_msg        = "Usuário: \\n\\n " . DBString::utf8_decode_all($this->erro_sql) . " \\n\\n";
      $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n" ) );
      $this->erro_status     = "1";
      $this->numrows_excluir = 0;
      return true;
    }

    $this->erro_banco      = "";
    $this->erro_sql        = "Exclusão efetuada com Sucesso\\n";
    $this->erro_sql       .= "Valores : " . $this->getStringCamposChave ();
    $this->erro_msg        = "Usuário: \\n\\n " . DBString::utf8_decode_all($this->erro_sql) . " \\n\\n";
    $this->erro_msg       .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n" ) );
    $this->erro_status     = "1";
    $this->numrows_excluir = pg_affected_rows ( $result );

    if (!$this->salvarAccount(self::TIPO_ACCOUNT_EXCLUSAO)) {

      $this->erro_status = "0";
      $this->erro_msg = "Não foi possível salvar os dados da auditoria.";
    }
    return true;
  }

  // funcao do recordset
  public function sql_record($sSql) {

    $rsQuery = db_query ( $sSql );
    if ($rsQuery == false) {

      $this->numrows     = 0;
      $this->erro_banco  = str_replace ( "\n", "", @pg_last_error () );
      $this->erro_sql    =  "Erro ao selecionar os registros." ;
      $this->erro_msg    = "Usuário: \\n\\n " . DBString::utf8_decode_all($this->erro_sql) . " \\n\\n";
      $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n" ) );
      $this->erro_status = "0";
      return false;
    }

    $this->numrows = pg_num_rows ( $rsQuery );
    if ($this->numrows == 0) {

      $this->erro_banco  = "";
      $this->erro_sql    = "Record Vazio na Tabela:conciliatipo";
      $this->erro_msg    = "Usuário: \\n\\n " . DBString::utf8_decode_all($this->erro_sql) . " \\n\\n";
      $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n" ) );
      $this->erro_status = "0";
      return false;
    }

    return $rsQuery;

  }

  // @todo criar classe para fazer a geracao de sql
  function sql_query_file() {

    $aParametros = $this->getParametros ( func_get_args (), self::QUERY );
    $sCampos       = "*";
    $sWhere        = "";
    $sOrderBy      = "";
    $sGroupBy      = "";
    $sWhereStr     = "WHERE";
    $aParametrosPk = array();

    if ($aParametros ['sWhere'] == null || $aParametros ['sWhere'] == "") {

      foreach ( $aParametros as $sNomeParametro => $sValorParametro ) {

        if ($sNomeParametro == 'sCampos' || $sNomeParametro == 'sWhere' || $sNomeParametro == 'sOrderBy' || $sNomeParametro == 'sGroupBy') {
          continue;
        }
        $aParametrosPk[$sNomeParametro] = $sValorParametro;
      }
    }

    return $this->query( $aParametrosPk,
                         $aParametros['sCampos'],
                         $aParametros['sWhere'],
                         $aParametros['sGroupBy'],
                         $aParametros['sOrderBy'],
                         0 );
  }

  // @todo criar classe para fazer a geracao de sql
  function sql_query() {

    $aParametros   = $this->getParametros ( func_get_args (), self::QUERY );
    $sCampos       = "*";
    $sWhere        = "";
    $sOrderBy      = "";
    $sGroupBy      = "";
    $sWhereStr     = "WHERE";
    $aParametrosPk = array();

    if ($aParametros ['sWhere'] == null || $aParametros ['sWhere'] == "") {

      foreach ( $aParametros as $sNomeParametro => $sValorParametro ) {

        if ($sNomeParametro == 'sCampos' || $sNomeParametro == 'sWhere' || $sNomeParametro == 'sOrderBy' || $sNomeParametro == 'sGroupBy') {
          continue;
        }
        $aParametrosPk[$sNomeParametro] = $sValorParametro;
      }
    }

    return $this->query( $aParametrosPk,
                         $aParametros['sCampos'],
                         $aParametros['sWhere'],
                         $aParametros['sGroupBy'],
                         $aParametros['sOrderBy'],
                         1 );
  }



  // @todo - verificar possibilidade de uso da pdo
  // @todo - implementar cache das strings de processamento
  //
  //   controlar por instancia
  //   verificar insert, update, delete e select
  //
  function query(array $aValuesPk, $sCamposP=null, $sWhereP=null, $sGroupByP=null, $sOrderByP=null, $iNivel=0) {

    $sAnd      = "";
    $sCampos   = "*";
    $sWhere    = "";
    $sOrderBy  = "";
    $sJoins    = "";
    $sGroupBy  = "";
    $sWhereStr = "WHERE";

    if (empty($sWhereP)) {

      foreach ( $aValuesPk as $sNomeParametro => $sValorParametro ) {

        if (! empty ( $sValorParametro )) {

          $sNomeParametro = "{$this->DDTabela->getTableName()}.{$sNomeParametro}";

          $sWhere .= "{$sWhereStr} {$sAnd} {$sNomeParametro} = {$sValorParametro} ";
          $sAnd = "and";
          $sWhereStr = "";
        }

      }
    } else {
      $sWhere = "{$sWhereStr} {$sWhereP}";
    }

    if ( $iNivel == 1){

      foreach ($this->DDTabela->getFks() as $oFk) {

        $inner   = "INNER";
        if ($oFk->inner == false || $oFk->inner == 'false') {
          $inner   = "LEFT";
        }

        $sAnd    = "";
        $sJoins .= " {$inner} JOIN {$oFk->reference} ON ";

        foreach ($oFk->getFields() as $oFieldFk) {
          $sJoins .= " $sAnd {$oFk->reference}.{$oFieldFk->reference} =  {$this->DDTabela->name}.{$oFieldFk->name} \n";
          $sAnd    = "AND";
        }
      }
    }

    if (! empty ( $sCamposP )) {
      $sCampos = $sCamposP;
    }

    if (! empty ( $sOrderByP)) {
      $sOrderBy = "ORDER BY {$sOrderByP}";
    }

    if (! empty ( $sGroupByP )) {
      $sOrderBy = "GROUP BY {$sGroupByP}";
    }

    $sSql = " SELECT {$sCampos} ";
    $sSql .= "   FROM {$this->DDTabela->name} ";
    $sSql .= "        {$sJoins}   ";
    $sSql .= " {$sWhere}   ";
    $sSql .= " {$sGroupBy} ";
    $sSql .= " {$sOrderBy} ";

    return $sSql;
  }

  /**
   * Retorna as variáveis da classe
   * @return array
   */
  protected function getDados() {
    return $this->aDados;
  }

  /**
   * @return DDTabelaXML|null
   */
  public function getTabela() {
    return $this->DDTabela;
  }


  /**
   * @param string $sTipoAccount
   * @return bool
   */
  private function salvarAccount($sTipoAccount) {

    if (!$this->lSalvarAccount) {
      return true;
    }

    $sDataSessao    = db_getsession('DB_datausu');
    $iUsuarioSessao = db_getsession('DB_id_usuario');

    $iSequenceAcount = db_utils::fieldsMemory(db_query("select nextval('db_acount_id_acount_seq') as seq"),0)->seq;

    $aAccount           = array();
    $aAccountPrimaryKey = array();
    $aAccountCampos     = array();

    $aAccount[] = "insert into db_acountacesso values({$iSequenceAcount}, ".db_getsession("DB_acessado").")";
    foreach ( $this->DDTabela->getFieldsPk() as $oChave ) {
      $aAccountPrimaryKey[] = "({$iSequenceAcount}, $oChave->codigo,'{$oChave->name}','{$sTipoAccount}')";
    }
    $aAccount[] = "insert into db_acountkey values ".implode(',', $aAccountPrimaryKey);

    foreach ($this->DDTabela->getCampos() as $oCampo) {
      $aAccountCampos[] = "({$iSequenceAcount}, {$this->DDTabela->codigo}, {$oCampo->codigo}, '', '{$this->{$oCampo->name}}', {$sDataSessao}, {$iUsuarioSessao})";
    }
    $aAccount[] = "insert into db_acount values ".implode(',', $aAccountCampos);
    $sInsertAccount = implode(";", $aAccount);
    return db_query($sInsertAccount);
  }

  /**
   * @param DDCampoXML $oCampo
   * @return bool
   */
  private function validarCampoNulo(DDCampoXML $oCampo) {

    $this->limparPropriedadesErro();
    $aTiposDeDadosIgnorar = array('float4','float8','numeric','int4','int8');
    if ( $this->{$oCampo->name} === $this->getValorComparacao($oCampo->conteudo, self::VALOR_INSERT)
      || ( !in_array($oCampo->conteudo, $aTiposDeDadosIgnorar)
        && $this->{$oCampo->name} == $this->getValorComparacao($oCampo->conteudo, self::VALOR_INSERT)) )
    {

      $this->erro_sql    = " Campo {$oCampo->description} nao Informado.";
      $this->erro_campo  = $oCampo->name;
      $this->erro_banco  = "";
      $this->erro_msg    = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
      $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      $this->erro_status = "0";
      return false;
    }
    return true;
  }

  /**
   * Método que verifica se existe chave primária e se a mesma encontra-se vazia
   * @param DDCampoXML $oCampo
   * @return bool
   */
  private function validarChavePrimaria(DDCampoXML $oCampo) {

    $this->limparPropriedadesErro();
    if ($oCampo->isPk() && $this->{$oCampo->name} == "") {

      $this->erro_sql    = " Campo {$oCampo->name} nao declarado.";
      $this->erro_banco  = "Chave Primaria zerada.";
      $this->erro_msg    = "Usuário: \n\n " . DBString::utf8_decode_all($this->erro_sql) . " \n\n";
      $this->erro_msg   .= str_replace ( '"', "", str_replace ( "'", "", "Administrador: \n\n " . $this->erro_banco . " \n" ) );
      $this->erro_status = "0";
      return false;
    }
    return true;
  }

  public function getPk() {
    return $this->primaryKeyField;
  }

  /**
   * Limpa as propriedades de aviso ao usuário antes de cada validação
   */
  private function limparPropriedadesErro() {

    $this->erro_sql    = "";
    $this->erro_banco  = "";
    $this->erro_msg    = "";
    $this->erro_status = "";
  }

  /**
   * Retorna um stdClass com os valores da PK
   * @param $value
   * @return null|object
   * @throws \DBException
   */
  public function findBydId($value) {

    $sSqlQuery = $this->sql_query_file($value);
    $rsDados   = db_query($sSqlQuery);
    if (!$rsDados) {
      throw  new \DBException('Erro ao pesquisar dados de '.$this->DDTabela->name);
    }
    if (pg_num_rows($rsDados) > 0) {
      return pg_fetch_object($rsDados, 0);
    }
    return null;
  }


    /**
     * Retorna uma lista de resultados do banco de dados
     *
     * @param $array  
     * @return array
    */

    public function find($params)
    {
        return  $this->doFind($params);
    }   
    

   /**
     * Retorna um array com um registro
     *
     * @param $params array  
     * @return array|null
    */

    public function findFirst($params)
    {
        $params['limit'] = 1; 
        return  reset($this->doFind($params));
    }

    /**
     * Retorna um resource
     *
     * @param $array  
     * @return resource
    */

    public function findResult($params)
    {
        $params['sql'] = true; 
        return  $this->query_exec($this->doFind($params));
    }   


    /**
     * Retorna uma lista de resultados do banco de dados
     *
     * @param $array  
     * @return array
    */ 
    public function doFind($param)
    {

        $fields = '';
       
        self::$tables = array();
  
        if ((isset($param['campos']) && is_array($param['campos'])) ||
         (isset($param['fields']) && is_array($param['fields'])) ) {
           $fields =  implode(',', $param['campos']);
           if (empty($fields)) {
                $fields = '*';
           }
        } else if (is_string($param['fields']) || is_string($param['fields'])) {

            $fields = ($param['fields'] ? $param['fields'] : $param['campos']);
        } else {
            
            $fields = '*';
        }

        $tabela = isset($param['tabela']) ? $param['tabela'] : $this->DDTabela->name;
        $alias  = isset($param['alias'])  ? $param['alias'] : '';
        $where  = '';
         
        self::$tables[] = $tabela ? $tabela : $alias ; 

        $sql = sprintf("SELECT %s FROM  %s ", $fields, $tabela);

        if (isset($param['filtro']) && is_array($param['filtro'])) {
            foreach ($param['filtro'] as $key => $value) {
         
               if (is_numeric($key)) {
                 $where .= ' '.$value;
               }elseif($this->has_next($param['filtro']) ){
                 $where .=   ' AND ' . $key .' = '. "'".$value."'" ;   
               } else {
                 $where .= $key .' = '."'".$value."'";
               }
            }
        } else {

             if (!empty($param['filtro']) && is_string($param['filtro'])) {
                 $where  = $param['filtro'];
             }
        }
        
        $joinSql = '';
        
        if (isset($param['join'])) {

            $joinSql = $this->genJoin($param['join']); 
        }

        if (!empty($joinSql)) {

            $sql = $sql . $joinSql;
        }

        $wheresql = ' WHERE  '. $where;

        if (!empty($where)) {
            $sql = $sql . $wheresql;
        }

        if (isset($param['ordem']) || isset($param['order'])) {
            $order = ($param['ordem'] ? $param['ordem'] : $param['order']);
            $sql = $sql . ' ORDER BY '. $order;
        }            
       
        if (isset($param['limite']) || isset($param['limit'])) {
            $limit = ($param['limite'] ? $param['limite'] : $param['limit']);
            $sql  .= ' LIMIT '. $limit;    
        }

        if ((isset($param['linhas']) || isset($param['offset'])) && isset($param['limite'])) {
            $sql .= ' OFFSET '. $param['linhas'];    
        }

        if (isset($param['sql'])) {
            return $sql;
        }
    
        $resource = $this->query_exec($sql);
        
        if ($param['aliased'] === false) {
        
            $result  = pg_fetch_all($resource);
        
        } else {
            
            while ($row = pg_fetch_assoc($resource)) {
               $tmp = array();
               $tmp2 = array();
               foreach ($row as $key => $value) {
                   $tmp =   explode('_', $key);
                 
                   $tmp2[$tmp[0]][$key] = $value;     
               }

               $ret = array();
               $i = 0; 
               foreach ($tmp2 as $key => $value) {
                  $ret[self::$tables[$i]] = $value;
                  $i++;
               }

               $result[]  = $ret;
             }
        }    

        return $result; 
    }

    /**
      * Retorna o proximo 
      *
      * @param $array array
      * @return booolean
    **/  
    private function has_next($array) 
    {
        if (is_array($array)) {
            if (next($array) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Executa Sql
     *
     * @param $sql string
     * @return resource
    **/  
    public function query_exec($sql) 
    {
      return  db_query($sql);
    }

 
    /**
     * Generate SQL Query
     */
    private function genJoin($join)
    {
        $sqlJoin = '';
        if ($join) {
            if (is_array($join)) {
                foreach ($join as $key => $value) {
                    if (is_array($value)) {
                        if (!isset($value['on']) && !isset($value['type']) && !isset($value['tabela']) 
                          && !isset($value['sql']) && $value) {
                            $value = array('on' => $value);
                        }
                        $joinType = (isset($value['type'])) ? strtoupper($value['type']) : 'LEFT';
                        self::$tables[] = $key;  
                        $joinAlias = $key;
                        $joinTable = (isset($value['tabela'])) ? $value['tabela'] : $key;

                        if (isset($value['on'])) {
                            $joinOn = ' ON ';
                            if (is_array($value['on'])) {
                                foreach ($value['on'] as $vkey => $vvalue) {
                                    $joinOn .= is_int($vkey) ? "{$vvalue} AND " : "{$vkey} = {$vvalue} AND ";
                                }
                                $joinOn = substr($joinOn, 0, -5);
                            } elseif (strtolower(substr($value['on'], 0, 5)) == 'using') {
                                $joinOn = $value['on'];
                            } else {
                                $joinOn .= $value['on'];
                            }
                        } else {
                            $joinOn = "USING (". $this->primaryKeyField.")";
                        }

                        if (!empty($value['sql'])) {
                            $joinTable = $value['sql'];
                        } else {
                            $joinTable = ($joinTable);
                        }

                        $sqlJoin .= "\n$joinType JOIN $joinTable AS $joinAlias $joinOn";
                    } elseif ($value !== false) {
                        $joinAlias = ucfirst($value);
                        $sqlJoin .= "\nLEFT JOIN " . $joinTable . " AS " . $joinAlias . " USING (".$this->primaryKeyField.")";
                    }
                }
            } else {
                if (strtolower(substr($join, 0, 4)) == 'left' || strtolower(substr($join, 0, 5)) == 'right' || strtolower(substr($join, 0, 5)) == 'inner') {
                    $sqlJoin = $join;
                } else {
                    $sqlJoin = "\nLEFT JOIN $join";
                }
            }
        }

        return $sqlJoin;
    }
 
}
