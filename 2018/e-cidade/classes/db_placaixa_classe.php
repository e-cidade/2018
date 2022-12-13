<?
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

//MODULO: caixa
//CLASSE DA ENTIDADE placaixa

class cl_placaixa {
  // cria variaveis de erro

  var $rotulo = null;
  var $query_sql = null;
  var $numrows = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql = null;
  var $erro_banco = null;
  var $erro_msg = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $k80_codpla = 0;
  var $k80_data_dia = null;
  var $k80_data_mes = null;
  var $k80_data_ano = null;
  var $k80_data = null;
  var $k80_instit = 0;
  var $k80_dtaut_dia = null;
  var $k80_dtaut_mes = null;
  var $k80_dtaut_ano = null;
  var $k80_dtaut = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 k80_codpla = int4 = PLanilha
                 k80_data = date = Data
                 k80_instit = int4 = codigo da instituicao
                 k80_dtaut = date = Data da Autenticacao
                 ";
  //funcao construtor da classe

  function cl_placaixa() {
    //classes dos rotulos dos campos

    $this->rotulo = new rotulo("placaixa");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro

  function erro($mostra, $retorna) {

    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }
  // funcao para atualizar campos

  function atualizacampos($exclusao = false) {

    if ($exclusao == false) {
      $this->k80_codpla = ($this->k80_codpla == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_codpla"] : $this->k80_codpla);
      if ($this->k80_data == "") {
        $this->k80_data_dia = ($this->k80_data_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_data_dia"]
            : $this->k80_data_dia);
        $this->k80_data_mes = ($this->k80_data_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_data_mes"]
            : $this->k80_data_mes);
        $this->k80_data_ano = ($this->k80_data_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_data_ano"]
            : $this->k80_data_ano);
        if ($this->k80_data_dia != "") {
          $this->k80_data = $this->k80_data_ano . "-" . $this->k80_data_mes . "-" . $this->k80_data_dia;
        }
      }
      $this->k80_instit = ($this->k80_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_instit"] : $this->k80_instit);
      if ($this->k80_dtaut == "") {
        $this->k80_dtaut_dia = ($this->k80_dtaut_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_dtaut_dia"]
            : $this->k80_dtaut_dia);
        $this->k80_dtaut_mes = ($this->k80_dtaut_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_dtaut_mes"]
            : $this->k80_dtaut_mes);
        $this->k80_dtaut_ano = ($this->k80_dtaut_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_dtaut_ano"]
            : $this->k80_dtaut_ano);
        if ($this->k80_dtaut_dia != "") {
          $this->k80_dtaut = $this->k80_dtaut_ano . "-" . $this->k80_dtaut_mes . "-" . $this->k80_dtaut_dia;
        }
      }
    } else {
      $this->k80_codpla = ($this->k80_codpla == "" ? @$GLOBALS["HTTP_POST_VARS"]["k80_codpla"] : $this->k80_codpla);
    }
  }
  // funcao para inclusao

  function incluir($k80_codpla) {

    $this->atualizacampos();
    if ($this->k80_data == null) {
      $this->erro_sql = " Campo Data nao Informado.";
      $this->erro_campo = "k80_data_dia";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->k80_instit == null) {
      $this->erro_sql = " Campo codigo da instituicao nao Informado.";
      $this->erro_campo = "k80_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->k80_dtaut == null) {
      $this->k80_dtaut = "null";
    }
    if ($k80_codpla == "" || $k80_codpla == null) {
      $result = db_query("select nextval('placaixa_k80_codpla_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: placaixa_k80_codpla_seq do campo: k80_codpla";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->k80_codpla = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from placaixa_k80_codpla_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $k80_codpla)) {
        $this->erro_sql = " Campo k80_codpla maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->k80_codpla = $k80_codpla;
      }
    }
    if (($this->k80_codpla == null) || ($this->k80_codpla == "")) {
      $this->erro_sql = " Campo k80_codpla nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into placaixa(
                                       k80_codpla
                                      ,k80_data
                                      ,k80_instit
                                      ,k80_dtaut
                       )
                values (
                                $this->k80_codpla
                               ," . ($this->k80_data == "null" || $this->k80_data == "" ? "null"
            : "'" . $this->k80_data . "'")
        . "
                               ,$this->k80_instit
                               ,"
        . ($this->k80_dtaut == "null" || $this->k80_dtaut == "" ? "null" : "'" . $this->k80_dtaut . "'")
        . "
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "Planilha de lançamento ($this->k80_codpla) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Planilha de lançamento já Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      } else {
        $this->erro_sql = "Planilha de lançamento ($this->k80_codpla) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->k80_codpla;
    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->k80_codpla));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,6288,'$this->k80_codpla','I')");
      $resac = db_query(
          "insert into db_acount values($acount,1023,6288,'','" . AddSlashes(pg_result($resaco, 0, 'k80_codpla'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,1023,6289,'','" . AddSlashes(pg_result($resaco, 0, 'k80_data')) . "',"
              . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,1023,6290,'','" . AddSlashes(pg_result($resaco, 0, 'k80_instit'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,1023,6291,'','" . AddSlashes(pg_result($resaco, 0, 'k80_dtaut')) . "',"
              . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }
    return true;
  }
  // funcao para alteracao

  function alterar($k80_codpla = null) {

    $this->atualizacampos();
    $sql = " update placaixa set ";
    $virgula = "";
    if (trim($this->k80_codpla) != "" || isset($GLOBALS["HTTP_POST_VARS"]["k80_codpla"])) {
      $sql .= $virgula . " k80_codpla = $this->k80_codpla ";
      $virgula = ",";
      if (trim($this->k80_codpla) == null) {
        $this->erro_sql = " Campo PLanilha nao Informado.";
        $this->erro_campo = "k80_codpla";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->k80_data) != ""
        || isset($GLOBALS["HTTP_POST_VARS"]["k80_data_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["k80_data_dia"] != "")) {
      $sql .= $virgula . " k80_data = '$this->k80_data' ";
      $virgula = ",";
      if (trim($this->k80_data) == null) {
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "k80_data_dia";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["k80_data_dia"])) {
        $sql .= $virgula . " k80_data = null ";
        $virgula = ",";
        if (trim($this->k80_data) == null) {
          $this->erro_sql = " Campo Data nao Informado.";
          $this->erro_campo = "k80_data_dia";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg .= str_replace('"', "",
              str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->k80_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["k80_instit"])) {
      $sql .= $virgula . " k80_instit = $this->k80_instit ";
      $virgula = ",";
      if (trim($this->k80_instit) == null) {
        $this->erro_sql = " Campo codigo da instituicao nao Informado.";
        $this->erro_campo = "k80_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->k80_dtaut) != ""
        || isset($GLOBALS["HTTP_POST_VARS"]["k80_dtaut_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["k80_dtaut_dia"] != "")) {
      $sql .= $virgula . " k80_dtaut = '$this->k80_dtaut' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["k80_dtaut_dia"])) {
        $sql .= $virgula . " k80_dtaut = null ";
        $virgula = ",";
      }
    }
    $sql .= " where ";
    if ($k80_codpla != null) {
      $sql .= " k80_codpla = $this->k80_codpla";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->k80_codpla));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,6288,'$this->k80_codpla','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["k80_codpla"]))
          $resac = db_query(
              "insert into db_acount values($acount,1023,6288,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'k80_codpla')) . "','$this->k80_codpla',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["k80_data"]))
          $resac = db_query(
              "insert into db_acount values($acount,1023,6289,'" . AddSlashes(pg_result($resaco, $conresaco, 'k80_data'))
                  . "','$this->k80_data'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["k80_instit"]))
          $resac = db_query(
              "insert into db_acount values($acount,1023,6290,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'k80_instit')) . "','$this->k80_instit',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["k80_dtaut"]))
          $resac = db_query(
              "insert into db_acount values($acount,1023,6291,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'k80_dtaut')) . "','$this->k80_dtaut',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Planilha de lançamento nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->k80_codpla;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Planilha de lançamento nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->k80_codpla;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->k80_codpla;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao

  function excluir($k80_codpla = null, $dbwhere = null) {

    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($k80_codpla));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,6288,'$k80_codpla','E')");
        $resac = db_query(
            "insert into db_acount values($acount,1023,6288,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'k80_codpla')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,1023,6289,'','" . AddSlashes(pg_result($resaco, $iresaco, 'k80_data'))
                . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,1023,6290,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'k80_instit')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,1023,6291,'','" . AddSlashes(pg_result($resaco, $iresaco, 'k80_dtaut'))
                . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from placaixa
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($k80_codpla != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " k80_codpla = $k80_codpla ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Planilha de lançamento nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $k80_codpla;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Planilha de lançamento nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $k80_codpla;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $k80_codpla;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset

  function sql_record($sql) {

    $result = db_query($sql);
    if ($result == false) {
      $this->numrows = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:placaixa";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  function sql_query($k80_codpla = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from placaixa ";
    $sql .= "      inner join db_config  on  db_config.codigo = placaixa.k80_instit";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($k80_codpla != null) {
        $sql2 .= " where placaixa.k80_codpla = $k80_codpla ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_processo($k80_codpla = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos}";
    $sql .= "   from placaixa ";
    $sql .= "        left join placaixaprocesso  on placaixaprocesso.k144_placaixa = placaixa.k80_codpla ";

    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    } else if (!empty($k80_codpla)) {
      $sql .= " where placaixa.k80_codpla = $k80_codpla ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

  function sql_query_file($k80_codpla = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from placaixa ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($k80_codpla != null) {
        $sql2 .= " where placaixa.k80_codpla = $k80_codpla ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_receita($k80_codpla = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from placaixa ";
    $sql .= "      inner join placaixarec on k81_codpla = k80_codpla                      ";
    $sql .= "      inner join tabrec      on tabrec.k02_codigo = k81_receita              ";
    $sql .= "      inner join taborc      on taborc.k02_codigo = tabrec.k02_codigo        ";
    $sql .= "      inner join orcreceita  on o70_codrec = taborc.k02_codrec               ";
    $sql .= "                            and o70_anousu = ".db_getsession('DB_anousu');
    $sql2 = "";
    if ($dbwhere == "") {
      if ($k80_codpla != null) {
        $sql2 .= " where placaixa.k80_codpla = $k80_codpla ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_rec($k80_codpla = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = " select k80_codpla, k80_data, k80_dtaut, sum(k81_valor) as k81_valor, k144_numeroprocesso ";
    $sql .= " from placaixa ";
    $sql .= "      inner join placaixarec on k80_codpla = k81_codpla";
    $sql .= "      inner join db_config  on  db_config.codigo = placaixa.k80_instit";
    $sql .= "      left join placaixaprocesso on k80_codpla = k144_placaixa";
    $sql2 = " ";
    if ($dbwhere == "") {
      if ($k80_codpla != null) {
        $sql2 .= " where placaixa.k80_codpla = $k80_codpla ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    $sql .= " group by k80_codpla, k80_data, k80_dtaut, k80_instit, k144_numeroprocesso";
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>