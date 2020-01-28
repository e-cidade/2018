<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: issqn
//CLASSE DA ENTIDADE ativid
class cl_ativid {

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
  var $q03_ativ = 0;
  var $q03_descr = null;
  var $q03_atmemo = null;
  var $q03_limite_dia = null;
  var $q03_limite_mes = null;
  var $q03_limite_ano = null;
  var $q03_limite = null;
  var $q03_horaini = null;
  var $q03_horafim = null;
  var $q03_deducao = 'f';
  var $q03_tributacao_municipio = 'f';
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 q03_ativ = int4 = Codigo da atividade 
                 q03_descr = varchar(200) = Descricao 
                 q03_atmemo = text = observacoes 
                 q03_limite = date = Data Limite 
                 q03_horaini = char(5) = Hora Inicial 
                 q03_horafim = char(5) = Hora Final 
                 q03_deducao = bool = Deduz Valor Nota
                 q03_tributacao_municipio = bool = Retenção p/ Prestação Fora do Município
                 ";

  //funcao construtor da classe
  function cl_ativid() {

    //classes dos rotulos dos campos
    $this->rotulo         = new rotulo("ativid");
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
      $this->q03_ativ   = ($this->q03_ativ == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_ativ"] : $this->q03_ativ);
      $this->q03_descr  = ($this->q03_descr == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_descr"] : $this->q03_descr);
      $this->q03_atmemo = ($this->q03_atmemo == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_atmemo"] : $this->q03_atmemo);
      if ($this->q03_limite == "") {
        $this->q03_limite_dia = ($this->q03_limite_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_limite_dia"] : $this->q03_limite_dia);
        $this->q03_limite_mes = ($this->q03_limite_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_limite_mes"] : $this->q03_limite_mes);
        $this->q03_limite_ano = ($this->q03_limite_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_limite_ano"] : $this->q03_limite_ano);
        if ($this->q03_limite_dia != "") {
          $this->q03_limite = $this->q03_limite_ano . "-" . $this->q03_limite_mes . "-" . $this->q03_limite_dia;
        }
      }
      $this->q03_horaini = ($this->q03_horaini == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_horaini"] : $this->q03_horaini);
      $this->q03_horafim = ($this->q03_horafim == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_horafim"] : $this->q03_horafim);
      $this->q03_deducao = ($this->q03_deducao == "f" ? @$GLOBALS["HTTP_POST_VARS"]["q03_deducao"] : $this->q03_deducao);
      $this->q03_tributacao_municipio = ($this->q03_tributacao_municipio == "f" ? @$GLOBALS["HTTP_POST_VARS"]["q03_tributacao_municipio"] : $this->q03_tributacao_municipio);
    } else {
      $this->q03_ativ = ($this->q03_ativ == "" ? @$GLOBALS["HTTP_POST_VARS"]["q03_ativ"] : $this->q03_ativ);
    }
  }

  // funcao para inclusao
  function incluir($q03_ativ) {

    $this->atualizacampos();
    if ($this->q03_descr == null) {
      $this->erro_sql   = " Campo Descricao não informado.";
      $this->erro_campo = "q03_descr";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->q03_deducao == null) {
      $this->q03_deducao = "false";
    }
    if ($this->q03_tributacao_municipio == null) {
      $this->q03_tributacao_municipio = "false";
    }
    $this->q03_ativ = $q03_ativ;
    if (($this->q03_ativ == null) || ($this->q03_ativ == "")) {
      $this->erro_sql   = " Campo q03_ativ nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";

      return false;
    }
    $sql    = "insert into ativid(
                                       q03_ativ 
                                      ,q03_descr 
                                      ,q03_atmemo 
                                      ,q03_limite 
                                      ,q03_horaini 
                                      ,q03_horafim 
                                      ,q03_deducao
                                      ,q03_tributacao_municipio
                       )
                values (
                                $this->q03_ativ 
                               ,'$this->q03_descr' 
                               ,'$this->q03_atmemo' 
                               ," . ($this->q03_limite == "null" || $this->q03_limite == "" ? "null" : "'" . $this->q03_limite . "'") . "
                               ,'$this->q03_horaini' 
                               ,'$this->q03_horafim' 
                               ,'$this->q03_deducao'
                               ,'$this->q03_tributacao_municipio'
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = " ($this->q03_ativ) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = " já Cadastrado";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql = " ($this->q03_ativ) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status     = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql   = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->q03_ativ;
    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "",
                                   str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status       = "1";
    $this->numrows_incluir   = pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))
    ) {

      $resaco = $this->sql_record($this->sql_query_file($this->q03_ativ));
      if (($resaco != false) || ($this->numrows != 0)) {

        $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac  = db_query("insert into db_acountkey values($acount,243,'$this->q03_ativ','I')");
        $resac  = db_query("insert into db_acount values($acount,48,243,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                     'q03_ativ')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,244,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                     'q03_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,245,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                     'q03_atmemo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,6853,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                      'q03_limite')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,12649,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                       'q03_horaini')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,12650,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                       'q03_horafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,20501,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                       'q03_deducao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac  = db_query("insert into db_acount values($acount,48,20585,'','" . AddSlashes(pg_result($resaco, 0,
                                                                                                       'q03_tributacao_municipio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }

    return true;
  }

  // funcao para alteracao
  function alterar($q03_ativ = null) {

    $this->atualizacampos();
    $sql     = " update ativid set ";
    $virgula = "";
    if (trim($this->q03_ativ) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_ativ"])) {
      $sql .= $virgula . " q03_ativ = $this->q03_ativ ";
      $virgula = ",";
      if (trim($this->q03_ativ) == null) {
        $this->erro_sql   = " Campo Codigo da atividade não informado.";
        $this->erro_campo = "q03_ativ";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->q03_descr) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_descr"])) {
      $sql .= $virgula . " q03_descr = '$this->q03_descr' ";
      $virgula = ",";
      if (trim($this->q03_descr) == null) {
        $this->erro_sql   = " Campo Descricao não informado.";
        $this->erro_campo = "q03_descr";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->q03_atmemo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_atmemo"])) {
      $sql .= $virgula . " q03_atmemo = '$this->q03_atmemo' ";
      $virgula = ",";
    }
    if (trim($this->q03_limite) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_limite_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["q03_limite_dia"] != "")) {
      $sql .= $virgula . " q03_limite = '$this->q03_limite' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["q03_limite_dia"])) {
        $sql .= $virgula . " q03_limite = null ";
        $virgula = ",";
      }
    }
    if (trim($this->q03_horaini) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_horaini"])) {
      $sql .= $virgula . " q03_horaini = '$this->q03_horaini' ";
      $virgula = ",";
    }
    if (trim($this->q03_horafim) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_horafim"])) {
      $sql .= $virgula . " q03_horafim = '$this->q03_horafim' ";
      $virgula = ",";
    }
    if (trim($this->q03_deducao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_deducao"])) {
      $sql .= $virgula . " q03_deducao = '$this->q03_deducao' ";
      $virgula = ",";
    }
    if (trim($this->q03_tributacao_municipio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["q03_tributacao_municipio"])) {
      $sql .= $virgula . " q03_tributacao_municipio = '$this->q03_tributacao_municipio' ";
      $virgula = ",";
    }
    $sql .= " where ";
    if ($q03_ativ != null) {
      $sql .= " q03_ativ = $this->q03_ativ";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))
    ) {

      $resaco = $this->sql_record($this->sql_query_file($this->q03_ativ));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac  = db_query("insert into db_acountkey values($acount,243,'$this->q03_ativ','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_ativ"]) || $this->q03_ativ != "") {
            $resac = db_query("insert into db_acount values($acount,48,243,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_ativ')) . "','$this->q03_ativ'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_descr"]) || $this->q03_descr != "") {
            $resac = db_query("insert into db_acount values($acount,48,244,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_descr')) . "','$this->q03_descr'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_atmemo"]) || $this->q03_atmemo != "") {
            $resac = db_query("insert into db_acount values($acount,48,245,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_atmemo')) . "','$this->q03_atmemo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_limite"]) || $this->q03_limite != "") {
            $resac = db_query("insert into db_acount values($acount,48,6853,'" . AddSlashes(pg_result($resaco,
                                                                                                      $conresaco,
                                                                                                      'q03_limite')) . "','$this->q03_limite'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_horaini"]) || $this->q03_horaini != "") {
            $resac = db_query("insert into db_acount values($acount,48,12649,'" . AddSlashes(pg_result($resaco,
                                                                                                       $conresaco,
                                                                                                       'q03_horaini')) . "','$this->q03_horaini'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_horafim"]) || $this->q03_horafim != "") {
            $resac = db_query("insert into db_acount values($acount,48,12650,'" . AddSlashes(pg_result($resaco,
                                                                                                       $conresaco,
                                                                                                       'q03_horafim')) . "','$this->q03_horafim'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_deducao"]) || $this->q03_deducao != "") {
            $resac = db_query("insert into db_acount values($acount,48,20501,'" . AddSlashes(pg_result($resaco,
                                                                                                       $conresaco,
                                                                                                       'q03_deducao')) . "','$this->q03_deducao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
          if (isset($GLOBALS["HTTP_POST_VARS"]["q03_tributacao_municipio"]) || $this->q03_tributacao_municipio != "") {
            $resac = db_query("insert into db_acount values($acount,48,20585,'" . AddSlashes(pg_result($resaco,
                                                                                                       $conresaco,
                                                                                                       'q03_tributacao_municipio')) . "','$this->q03_tributacao_municipio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          }
        }
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->q03_ativ;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status     = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = " nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->q03_ativ;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status     = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql   = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->q03_ativ;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status     = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($q03_ativ = null, $dbwhere = null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))
    ) {

      if ($dbwhere == null || $dbwhere == "") {

        $resaco = $this->sql_record($this->sql_query_file($q03_ativ));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
      }
      if (($resaco != false) || ($this->numrows != 0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac  = db_query("insert into db_acountkey values($acount,243,'$q03_ativ','E')");
          $resac  = db_query("insert into db_acount values($acount,48,243,'','" . AddSlashes(pg_result($resaco,
                                                                                                       $iresaco,
                                                                                                       'q03_ativ')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,244,'','" . AddSlashes(pg_result($resaco,
                                                                                                       $iresaco,
                                                                                                       'q03_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,245,'','" . AddSlashes(pg_result($resaco,
                                                                                                       $iresaco,
                                                                                                       'q03_atmemo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,6853,'','" . AddSlashes(pg_result($resaco,
                                                                                                        $iresaco,
                                                                                                        'q03_limite')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,12649,'','" . AddSlashes(pg_result($resaco,
                                                                                                         $iresaco,
                                                                                                         'q03_horaini')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,12650,'','" . AddSlashes(pg_result($resaco,
                                                                                                         $iresaco,
                                                                                                         'q03_horafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,20501,'','" . AddSlashes(pg_result($resaco,
                                                                                                         $iresaco,
                                                                                                         'q03_deducao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,48,20585,'','" . AddSlashes(pg_result($resaco,
                                                                                                         $iresaco,
                                                                                                         'q03_tributacao_municipio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $sql  = " delete from ativid
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($q03_ativ != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " q03_ativ = $q03_ativ ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $q03_ativ;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status     = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = " nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $q03_ativ;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status     = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql   = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $q03_ativ;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
                                       str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status     = "1";
        $this->numrows_excluir = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql) {

    $result = db_query($sql);
    if ($result == false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:ativid";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "",
                                     str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  // funcao do sql
  function sql_query_file($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  // funcao do sql modificado para acrecentar campos
  function sql_query_dados($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql .= "      left join clasativ      on clasativ.q82_ativ             = ativid.q03_ativ                       ";
    $sql .= "      left join classe        on classe.q12_classe             = clasativ.q82_classe                   ";
    $sql .= "      left join atividcbo     on atividcbo.q75_ativid          = ativid.q03_ativ                       ";
    $sql .= "      left join rhcbo         on rhcbo.rh70_sequencial         = atividcbo.q75_rhcbo                   ";
    $sql .= "      left join atividcnae    on atividcnae.q74_ativid         = ativid.q03_ativ                       ";
    $sql .= "      left join cnaeanalitica on cnaeanalitica.q72_sequencial  = atividcnae.q74_cnaeanalitica          ";
    $sql .= "      left join cnae          on cnae.q71_sequencial           = cnaeanalitica.q72_cnae                ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  function sql_query_cbo($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql .= "      left join atividcbo on q75_ativid = q03_ativ ";
    $sql .= "      left join rhcbo on rh70_sequencial = q75_rhcbo ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  function sql_query_clas($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql .= "      left join clasativ on clasativ.q82_ativ = ativid.q03_ativ";
    $sql .= "      left join classe on classe.q12_classe = clasativ.q82_classe ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  function sql_query_cnae($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ativid ";
    $sql .= "      left join atividcnae on q74_ativid = q03_ativ ";
    $sql .= "      left join cnaeanalitica on q72_sequencial = q74_cnaeanalitica ";
    $sql .= "      left join cnae on q71_sequencial = q72_cnae ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($q03_ativ != null) {
        $sql2 .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  /**
   * CPF - CBO
   * Enter description here ...
   *
   * @param int    $q03_ativ
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_cboSimulacao($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sSql = "select ";

    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $campos;
    }

    $sSql .= " from ativid                                                                ";
    $sSql .= "   inner join clasativ      on clasativ.q82_ativ = ativid.q03_ativ          ";
    $sSql .= "   inner join classe        on classe.q12_classe = clasativ.q82_classe      ";
    $sSql .= "   inner join atividcbo     on atividcbo.q75_ativid = ativid.q03_ativ       ";
    $sSql .= "   inner join rhcbo         on rhcbo.rh70_sequencial = atividcbo.q75_rhcbo  ";

    if ($dbwhere == "") {

      if ($q03_ativ != null) {
        $sSql .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sSql .= " where $dbwhere";
    }

    if ($ordem != null) {

      $sSql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sSql;
  }

  /**
   * Foi preciso alterar o modo como o método alterava a data e horaini e horafim
   */
  function alterar_alterado($q03_ativ = null) {

    $this->atualizacampos();
    $sql     = ' update ativid set ';
    $virgula = null;

    if (trim($this->q03_ativ) != '' || isset($GLOBALS['HTTP_POST_VARS']['q03_ativ'])) {

      $sql .= "{$virgula} q03_ativ = {$this->q03_ativ} ";
      $virgula = ',';

      if (trim($this->q03_ativ) == null) {

        $this->erro_sql   = ' Campo Codigo da atividade nao Informado.';
        $this->erro_campo = 'q03_ativ';
        $this->erro_banco = '';
        $this->erro_msg   = "Usuário: \\n\\n {$this->erro_sql} \\n\\n";
        $this->erro_msg .= str_replace('', '', str_replace("'", '', "Administrador: \\n\\n {$this->erro_banco} \\n"));
        $this->erro_status = '0';

        return false;
      }
    }

    if (trim($this->q03_descr) != '' || isset($GLOBALS['HTTP_POST_VARS']['q03_descr'])) {

      $sql .= "{$virgula} q03_descr = '{$this->q03_descr}' ";
      $virgula = ',';

      if (trim($this->q03_descr) == null) {

        $this->erro_sql   = ' Campo Descricao nao Informado.';
        $this->erro_campo = 'q03_descr';
        $this->erro_banco = '';
        $this->erro_msg   = "Usuário: \\n\\n {$this->erro_sql} \\n\\n";
        $this->erro_msg .= str_replace('"', '', str_replace("'", '', "Administrador: \\n\\n {$this->erro_banco} \\n"));
        $this->erro_status = '0';

        return false;
      }
    }

    if (trim($this->q03_atmemo) != '' || isset($GLOBALS['HTTP_POST_VARS']['q03_atmemo'])) {

      $sql .= "{$virgula} q03_atmemo = '{$this->q03_atmemo}' ";
      $virgula = ',';
    } else {

      $sql .= "{$virgula} q03_atmemo = null ";
      $virgula = ',';
    }

    if ($this->q03_limite != '') {

      $this->q03_limite = implode("-", array_reverse(explode("/", $this->q03_limite)));
      $sql .= "{$virgula} q03_limite = '{$this->q03_limite}'";
    } else {
      $sql .= "{$virgula} q03_limite = null";
    }

    $virgula = ',';
    $sql .= "{$virgula} q03_horaini = '{$this->q03_horaini}' ";
    $sql .= "{$virgula} q03_horafim = '{$this->q03_horafim}' ";

    if ($this->q03_deducao == 't' || isset($GLOBALS["HTTP_POST_VARS"]["q03_deducao"])) {
      $sql .= "{$virgula} q03_deducao = true ";
    } else {
      $sql .= "{$virgula} q03_deducao = false ";
    }

    if ($this->q03_tributacao_municipio == 't' || isset($GLOBALS["HTTP_POST_VARS"]["q03_tributacao_municipio"])) {
      $sql .= "{$virgula} q03_tributacao_municipio = true ";
    } else {
      $sql .= "{$virgula} q03_tributacao_municipio = false ";
    }

    $sql .= ' where ';

    if ($q03_ativ != null) {
      $sql .= " q03_ativ = {$this->q03_ativ} ";
    }

    $resaco = $this->sql_record($this->sql_query_file($this->q03_ativ));

    if ($this->numrows > 0) {

      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

        $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac  = db_query("insert into db_acountkey values($acount,243,'$this->q03_ativ','A')");

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_ativ"]) || $this->q03_ativ != "") {
          $resac = db_query("insert into db_acount values($acount,48,243,'" . AddSlashes(pg_result($resaco, $conresaco,
                                                                                                   'q03_ativ')) . "','$this->q03_ativ'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_descr"]) || $this->q03_descr != "") {
          $resac = db_query("insert into db_acount values($acount,48,244,'" . AddSlashes(pg_result($resaco, $conresaco,
                                                                                                   'q03_descr')) . "','$this->q03_descr'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_atmemo"]) || $this->q03_atmemo != "") {
          $resac = db_query("insert into db_acount values($acount,48,245,'" . AddSlashes(pg_result($resaco, $conresaco,
                                                                                                   'q03_atmemo')) . "','$this->q03_atmemo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_limite"]) || $this->q03_limite != "") {
          $resac = db_query("insert into db_acount values($acount,48,6853,'" . AddSlashes(pg_result($resaco, $conresaco,
                                                                                                    'q03_limite')) . "','$this->q03_limite'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_horaini"]) || $this->q03_horaini != "") {
          $resac = db_query("insert into db_acount values($acount,48,12649,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_horaini')) . "','$this->q03_horaini'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_horafim"]) || $this->q03_horafim != "") {
          $resac = db_query("insert into db_acount values($acount,48,12650,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_horafim')) . "','$this->q03_horafim'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_deducao"]) || is_bool($this->q03_deducao)) {
          $resac = db_query("insert into db_acount values($acount,48,20501,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_deducao')) . "',$this->q03_deducao," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }

        if (isset($GLOBALS["HTTP_POST_VARS"]["q03_tributacao_municipio"]) || is_bool($this->q03_tributacao_municipio)) {
          $resac = db_query("insert into db_acount values($acount,48,20585,'" . AddSlashes(pg_result($resaco,
                                                                                                     $conresaco,
                                                                                                     'q03_tributacao_municipio')) . "',$this->q03_tributacao_municipio," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }

    $result = db_query($sql);

    if ($result == false) {

      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores: {$this->q03_ativ}";
      $this->erro_msg = "Usuário: \\n\\n {$this->erro_sql} \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", '', "Administrador: \\n\\n {$this->erro_banco} \\n"));
      $this->erro_status     = '0';
      $this->numrows_alterar = 0;

      return false;
    } else {

      if (pg_affected_rows($result) == 0) {

        $this->erro_banco = '';
        $this->erro_sql   = " nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : {$this->q03_ativ}";
        $this->erro_msg = "Usuário: \\n\\n {$this->erro_sql} \\n\\n";
        $this->erro_msg .= str_replace('"', '', str_replace("'", '', "Administrador: \\n\\n {$this->erro_banco} \\n"));
        $this->erro_status     = '1';
        $this->numrows_alterar = 0;

        return true;
      } else {

        $this->erro_banco = '';
        $this->erro_sql   = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->q03_ativ;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', '', str_replace("'", '', "Administrador: \\n\\n {$this->erro_banco} \\n"));
        $this->erro_status     = '1';
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  /**
   * CNPJ - CNAE
   *
   * @param int    $q03_ativ
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   */
  function sql_query_cnaeSimulacao($q03_ativ = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sSql = "select ";

    if ($campos != "*") {

      $campos_sql = split("#", $campos);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $campos;
    }

    $sSql .= " from ativid                                                                                ";
    $sSql .= "   inner join clasativ      on clasativ.q82_ativ = ativid.q03_ativ                          ";
    $sSql .= "   inner join classe        on classe.q12_classe = clasativ.q82_classe                      ";
    $sSql .= "   inner join atividcnae    on atividcnae.q74_ativid = ativid.q03_ativ                      ";
    $sSql .= "   inner join cnaeanalitica on cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica  ";
    $sSql .= "   inner join cnae          on cnae.q71_sequencial = cnaeanalitica.q72_cnae                 ";

    if ($dbwhere == "") {

      if ($q03_ativ != null) {
        $sSql .= " where ativid.q03_ativ = $q03_ativ ";
      }
    } else if ($dbwhere != "") {
      $sSql .= " where $dbwhere";
    }

    if ($ordem != null) {

      $sSql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula    = "";

      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sSql;
  }
}

?>