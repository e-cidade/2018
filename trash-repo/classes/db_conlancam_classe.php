<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancam

class cl_conlancam {
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
  var $c70_codlan = 0;
  var $c70_anousu = 0;
  var $c70_data_dia = null;
  var $c70_data_mes = null;
  var $c70_data_ano = null;
  var $c70_data = null;
  var $c70_valor = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c70_codlan = int4 = C�digo Lan�amento
                 c70_anousu = int4 = Exerc�cio
                 c70_data = date = Data
                 c70_valor = float8 = Valor do Lan�amento
                 ";
  //funcao construtor da classe

  function cl_conlancam() {
    //classes dos rotulos dos campos

    $this->rotulo = new rotulo("conlancam");
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
      $this->c70_codlan = ($this->c70_codlan == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_codlan"] : $this->c70_codlan);
      $this->c70_anousu = ($this->c70_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_anousu"] : $this->c70_anousu);
      if ($this->c70_data == "") {
        $this->c70_data_dia = ($this->c70_data_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_data_dia"]
            : $this->c70_data_dia);
        $this->c70_data_mes = ($this->c70_data_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_data_mes"]
            : $this->c70_data_mes);
        $this->c70_data_ano = ($this->c70_data_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_data_ano"]
            : $this->c70_data_ano);
        if ($this->c70_data_dia != "") {
          $this->c70_data = $this->c70_data_ano . "-" . $this->c70_data_mes . "-" . $this->c70_data_dia;
        }
      }
      $this->c70_valor = ($this->c70_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_valor"] : $this->c70_valor);
    } else {
      $this->c70_codlan = ($this->c70_codlan == "" ? @$GLOBALS["HTTP_POST_VARS"]["c70_codlan"] : $this->c70_codlan);
    }
  }
  // funcao para inclusao

  function incluir($c70_codlan) {

    $this->atualizacampos();
    if ($this->c70_anousu == null) {
      $this->erro_sql = " Campo Exerc�cio nao Informado.";
      $this->erro_campo = "c70_anousu";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c70_data == null) {
      $this->erro_sql = " Campo Data nao Informado.";
      $this->erro_campo = "c70_data_dia";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c70_valor == null) {
      $this->erro_sql = " Campo Valor do Lan�amento nao Informado.";
      $this->erro_campo = "c70_valor";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($c70_codlan == "" || $c70_codlan == null) {
      $result = db_query("select nextval('conlancam_c70_codlan_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: conlancam_c70_codlan_seq do campo: c70_codlan";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c70_codlan = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from conlancam_c70_codlan_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $c70_codlan)) {
        $this->erro_sql = " Campo c70_codlan maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->c70_codlan = $c70_codlan;
      }
    }
    if (($this->c70_codlan == null) || ($this->c70_codlan == "")) {
      $this->erro_sql = " Campo c70_codlan nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into conlancam(
                                       c70_codlan
                                      ,c70_anousu
                                      ,c70_data
                                      ,c70_valor
                       )
                values (
                                $this->c70_codlan
                               ,$this->c70_anousu
                               ," . ($this->c70_data == "null" || $this->c70_data == "" ? "null"
            : "'" . $this->c70_data . "'")
        . "
                               ,$this->c70_valor
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "Lan�amentos Cont�beis ($this->c70_codlan) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Lan�amentos Cont�beis j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      } else {
        $this->erro_sql = "Lan�amentos Cont�beis ($this->c70_codlan) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->c70_codlan;
    $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      $resaco = $this->sql_record($this->sql_query_file($this->c70_codlan));
      if (($resaco != false) || ($this->numrows != 0)) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,5217,'$this->c70_codlan','I')");
        $resac = db_query(
            "insert into db_acount values($acount,760,5217,'','" . AddSlashes(pg_result($resaco, 0, 'c70_codlan')) . "',"
                . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,760,5218,'','" . AddSlashes(pg_result($resaco, 0, 'c70_anousu')) . "',"
                . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,760,5219,'','" . AddSlashes(pg_result($resaco, 0, 'c70_data')) . "',"
                . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,760,5839,'','" . AddSlashes(pg_result($resaco, 0, 'c70_valor')) . "',"
                . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    return true;
  }
  // funcao para alteracao

  function alterar($c70_codlan = null) {

    $this->atualizacampos();
    $sql = " update conlancam set ";
    $virgula = "";
    if (trim($this->c70_codlan) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c70_codlan"])) {
      $sql .= $virgula . " c70_codlan = $this->c70_codlan ";
      $virgula = ",";
      if (trim($this->c70_codlan) == null) {
        $this->erro_sql = " Campo C�digo Lan�amento nao Informado.";
        $this->erro_campo = "c70_codlan";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c70_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c70_anousu"])) {
      $sql .= $virgula . " c70_anousu = $this->c70_anousu ";
      $virgula = ",";
      if (trim($this->c70_anousu) == null) {
        $this->erro_sql = " Campo Exerc�cio nao Informado.";
        $this->erro_campo = "c70_anousu";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c70_data) != ""
        || isset($GLOBALS["HTTP_POST_VARS"]["c70_data_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["c70_data_dia"] != "")) {
      $sql .= $virgula . " c70_data = '$this->c70_data' ";
      $virgula = ",";
      if (trim($this->c70_data) == null) {
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "c70_data_dia";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["c70_data_dia"])) {
        $sql .= $virgula . " c70_data = null ";
        $virgula = ",";
        if (trim($this->c70_data) == null) {
          $this->erro_sql = " Campo Data nao Informado.";
          $this->erro_campo = "c70_data_dia";
          $this->erro_banco = "";
          $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg .= str_replace('"', "",
              str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->c70_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c70_valor"])) {
      $sql .= $virgula . " c70_valor = $this->c70_valor ";
      $virgula = ",";
      if (trim($this->c70_valor) == null) {
        $this->erro_sql = " Campo Valor do Lan�amento nao Informado.";
        $this->erro_campo = "c70_valor";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($c70_codlan != null) {
      $sql .= " c70_codlan = $this->c70_codlan";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      $resaco = $this->sql_record($this->sql_query_file($this->c70_codlan));
      if ($this->numrows > 0) {
        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac = db_query("insert into db_acountkey values($acount,5217,'$this->c70_codlan','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c70_codlan"]))
            $resac = db_query(
                "insert into db_acount values($acount,760,5217,'"
                    . AddSlashes(pg_result($resaco, $conresaco, 'c70_codlan')) . "','$this->c70_codlan',"
                    . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c70_anousu"]))
            $resac = db_query(
                "insert into db_acount values($acount,760,5218,'"
                    . AddSlashes(pg_result($resaco, $conresaco, 'c70_anousu')) . "','$this->c70_anousu',"
                    . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c70_data"]))
            $resac = db_query(
                "insert into db_acount values($acount,760,5219,'" . AddSlashes(pg_result($resaco, $conresaco, 'c70_data'))
                    . "','$this->c70_data'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c70_valor"]))
            $resac = db_query(
                "insert into db_acount values($acount,760,5839,'" . AddSlashes(pg_result($resaco, $conresaco, 'c70_valor'))
                    . "','$this->c70_valor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Lan�amentos Cont�beis nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->c70_codlan;
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Lan�amentos Cont�beis nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->c70_codlan;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->c70_codlan;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao

  function excluir($c70_codlan = null, $dbwhere = null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      if ($dbwhere == null || $dbwhere == "") {
        $resaco = $this->sql_record($this->sql_query_file($c70_codlan));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
      }
      if (($resaco != false) || ($this->numrows != 0)) {
        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac = db_query("insert into db_acountkey values($acount,5217,'$c70_codlan','E')");
          $resac = db_query(
              "insert into db_acount values($acount,760,5217,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c70_codlan'))
                  . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac = db_query(
              "insert into db_acount values($acount,760,5218,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c70_anousu'))
                  . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac = db_query(
              "insert into db_acount values($acount,760,5219,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c70_data'))
                  . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac = db_query(
              "insert into db_acount values($acount,760,5839,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c70_valor'))
                  . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $sql = " delete from conlancam
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($c70_codlan != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c70_codlan = $c70_codlan ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Lan�amentos Cont�beis nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_sql .= "Valores : " . $c70_codlan;
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Lan�amentos Cont�beis nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $c70_codlan;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $c70_codlan;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
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
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:conlancam";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  function sql_query($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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

  function sql_query_file($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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

  function sql_query_trans($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql .= " 	inner join conlancamdoc on c71_codlan = c70_codlan  ";
    $sql .= " 	inner join conhistdoc   on c71_coddoc = c53_coddoc  ";
    $sql .= " 	left join conlancamemp  on c75_codlan = c70_codlan  ";
    $sql .= " 	left join empempenho    on c75_numemp = e60_numemp  ";
    $sql .= " 	left join empelemento   on e64_numemp =  e60_numemp ";
    $sql .= " 	left join conlancamrec  on c70_codlan = c74_codlan ";
    $sql .= " 	left join orcreceita    on c74_anousu = o70_anousu and c74_codrec = o70_codrec";
    $sql .= " 	left join conlancampag  on c82_codlan = c70_codlan";
    $sql .= " 	left join conlancamele  on c67_codlan = c70_codlan";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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

  /**
   *
   * Retorna os movimentos comt�bil para o Arquivo TXT fo Sigfis
   * @return string
   */
  function sql_movimentoContabilSigfis($iAnoUsu, $iInstit, $dtDataInicial, $dtDataFinal) {

    $sSql = "select competencia,                                                                                       ";
    $sSql .= "       sum(case when tipo = 'C' then c69_valor else 0 end) as valor_credito,                              ";
    $sSql .= "       sum(case when tipo = 'D' then c69_valor else 0 end) as valor_debito,                               ";
    $sSql .= "       conta,                                                                                             ";
    $sSql .= "       tipo_movimento,                                                                                    ";
    $sSql .= "       estrutural                                                                                         ";
    $sSql .= "  from (SELECT to_char(c70_data,'YYYYmm') as competencia,                                                 ";
    $sSql .= "               (case c71_coddoc when 1000 then 2                                                          ";
    $sSql .= "               when 2000 then 1                                                                           ";
    $sSql .= "               else 3 end ) as tipo_movimento,                                                            ";
    $sSql .= "               planocredito.c60_codcon as conta,                                                          ";
    $sSql .= "               c69_valor,                                                                                 ";
    $sSql .= "               'C' as tipo, 																																							";
    $sSql .= "               planocredito.c60_estrut as estrutural                                                      ";
    $sSql .= "          from conlancamval                                                                               ";
    $sSql .= "               inner join conlancam                  on c69_codlan = c70_codlan                           ";
    $sSql .= "               inner join conlancamdoc               on c71_codlan = c70_codlan                           ";
    $sSql .= "               inner join conplanoreduz reduzcredito on reduzcredito.c61_reduz  = c69_credito             ";
    $sSql .= "                                                    and reduzcredito.c61_anousu = c69_anousu              ";
    $sSql .= "                                                    and reduzcredito.c61_instit = $iInstit                ";
    $sSql .= "               inner join conplano planocredito      on planocredito.c60_codcon = reduzcredito.c61_codcon ";
    $sSql .= "               																			and planocredito.c60_anousu = reduzcredito.c61_anousu ";
    $sSql .= "         where c70_data between cast('{$dtDataInicial}' as date) and  cast('{$dtDataFinal}' as date)      ";
    $sSql .= "           and c70_anousu = {$iAnoUsu}                                                                    ";
    $sSql .= "         union                                                                                            ";
    $sSql .= "        SELECT to_char(c70_data,'YYYYmm') as competencia,                                                 ";
    $sSql .= "               (case c71_coddoc when 1000 then 2                                                          ";
    $sSql .= "               when 2000 then 1                                                                           ";
    $sSql .= "               else 3 end ) as tipo_movimento,                                                            ";
    $sSql .= "               planodebito.c60_codcon as conta,                                                           ";
    $sSql .= "               c69_valor,                                                                                 ";
    $sSql .= "               'D' as tipo,                                                                    					  ";
    $sSql .= "               planodebito.c60_estrut as estrutural                                                       ";
    $sSql .= "          from conlancamval                                                                               ";
    $sSql .= "               inner join conlancam                  on c69_codlan = c70_codlan                           ";
    $sSql .= "               inner join conlancamdoc               on c71_codlan = c70_codlan                           ";
    $sSql .= "               inner join conplanoreduz reduzdebito  on reduzdebito.c61_reduz   = c69_debito              ";
    $sSql .= "               																		  and reduzdebito.c61_anousu  = c69_anousu              ";
    $sSql .= "                                                    and reduzdebito.c61_instit = $iInstit                 ";
    $sSql .= "               inner join conplano planodebito       on planodebito.c60_codcon  = reduzdebito.c61_codcon  ";
    $sSql .= "               																			and planodebito.c60_anousu  = reduzdebito.c61_anousu  ";
    $sSql .= "         where c70_data between cast('{$dtDataInicial}' as date) and  cast('{$dtDataFinal}' as date)      ";
    $sSql .= "           and c70_anousu = {$iAnoUsu}) lanc                                                              ";
    $sSql .= "   group by conta,                                                                                				";
    $sSql .= "            competencia,                                                                                  ";
    $sSql .= "            tipo_movimento,                                                                               ";
    $sSql .= "            estrutural                                                                                    ";
    $sSql .= "   order by estrutural 																																										";

    return $sSql;

  }

  function sql_query_empenho($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql .= " 	inner join conlancamval            on c69_codlan = c70_codlan";
    $sql .= " 	inner join conlancamdoc            on c71_codlan = c70_codlan  ";
    $sql .= " 	inner join conhistdoc              on c71_coddoc = c53_coddoc  ";
    $sql .= " 	inner join conlancamemp            on c75_codlan = c70_codlan  ";
    $sql .= " 	inner join empempenho              on c75_numemp = e60_numemp  ";
    $sql .= " 	inner join empelemento             on e64_numemp =  e60_numemp ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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


  function sql_query_empenho_cgm($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
  	$sql .= " from conlancam ";
  	$sql .= " 	left join conlancamemp            on c75_codlan = conlancam.c70_codlan  ";
  	$sql .= " 	left join empempenho 							on e60_numemp = conlancamemp.c75_numemp ";
  	$sql .= " 	left join conlancamcgm            on c76_codlan = conlancam.c70_codlan  ";
  	$sql .= " 	left join conlancamconcarpeculiar on c08_codlan = conlancam.c70_codlan ";
  	$sql2 = "";
  	if ($dbwhere == "") {
  		if ($c70_codlan != null) {
  			$sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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


  function sql_query_lancamento_requisicao_material($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql .= "      inner join conlancammatestoqueinimei on conlancammatestoqueinimei.c103_conlancam    = conlancam.c70_codlan";
    $sql .= "      inner join conlancamval              on conlancamval.c69_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conlancamdoc              on conlancamdoc.c71_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conhistdoc                on conhistdoc.c53_coddoc                       = conlancamdoc.c71_coddoc";
    $sql .= "      inner join matestoqueinimei          on matestoqueinimei.m82_codigo                 = conlancammatestoqueinimei.c103_matestoqueinimei";
    $sql .= "      inner join matestoqueini             on matestoqueini.m80_codigo                    = matestoqueinimei.m82_matestoqueini";
    $sql .= "      inner join matestoqueinimeiari       on matestoqueinimeiari.m49_codmatestoqueinimei = matestoqueinimei.m82_codigo";
    $sql .= "      inner join atendrequiitem            on atendrequiitem.m43_codigo                   = matestoqueinimeiari.m49_codatendrequiitem";
    $sql .= "      inner join matrequiitem              on matrequiitem.m41_codigo                     = atendrequiitem.m43_codmatrequiitem";
    $sql .= "      inner join matrequi                  on matrequi.m40_codigo                         = matrequiitem.m41_codmatrequi";
    $sql .= "      inner join db_depart                 on db_depart.coddepto                          = matrequi.m40_depto";
    $sql .= "      inner join matmater                  on matmater.m60_codmater                       = matrequiitem.m41_codmatmater";
    $sql2 = "";
    if ($dbwhere == "") {
    		if ($c70_codlan != null) {
    		  $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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

  function sql_query_lancamento_saida_manual($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql .= "      inner join conlancamval              on conlancamval.c69_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conlancamdoc              on conlancamdoc.c71_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conlancammatestoqueinimei on conlancammatestoqueinimei.c103_conlancam    = conlancam.c70_codlan";
    $sql .= "      inner join matestoqueinimei          on matestoqueinimei.m82_codigo                 = conlancammatestoqueinimei.c103_matestoqueinimei";
    $sql .= "      inner join matestoqueini             on matestoqueini.m80_codigo                    = matestoqueinimei.m82_matestoqueini";
    $sql .= "      inner join matestoqueitem            on matestoqueitem.m71_codlanc                  = matestoqueinimei.m82_matestoqueitem";
    $sql .= "      inner join matestoque                on matestoque.m70_codigo                       = matestoqueitem.m71_codmatestoque";
    $sql .= "      inner join matmater                  on matmater.m60_codmater                       = matestoque.m70_codmatmater";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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

  function sql_query_lancamento_estorno_saida_manual($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conlancam ";
    $sql .= "      inner join conlancamval              on conlancamval.c69_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conlancamdoc              on conlancamdoc.c71_codlan                     = conlancam.c70_codlan";
    $sql .= "      inner join conlancammatestoqueinimei on conlancammatestoqueinimei.c103_conlancam    = conlancam.c70_codlan";
    $sql .= "      inner join matestoqueinimeimdi       on matestoqueinimeimdi.m50_codmatestoqueinimei = conlancammatestoqueinimei.c103_matestoqueinimei";
    $sql .= "      inner join matestoquedevitem         on matestoquedevitem.m46_codigo                = matestoqueinimeimdi.m50_codmatestoquedevitem";
    $sql .= "      inner join matrequiitem              on matrequiitem.m41_codigo                     = matestoquedevitem.m46_codmatrequiitem";
    $sql .= "      inner join matestoqueinimei          on matestoqueinimei.m82_codigo                 = matestoqueinimeimdi.m50_codmatestoqueinimei";
    $sql .= "      inner join matestoqueini             on matestoqueini.m80_codigo                    = matestoqueinimei.m82_matestoqueini";
    $sql .= "      inner join matestoqueitem            on matestoqueitem.m71_codlanc                  = matestoqueinimei.m82_matestoqueitem";
    $sql .= "      inner join matestoque                on matestoque.m70_codigo                       = matestoqueitem.m71_codmatestoque";
    $sql .= "      inner join matmater                  on matmater.m60_codmater                       = matestoque.m70_codmatmater";

    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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
  
  function sql_query_reprocessamento($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
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
  	$sql .= "        from conlancam                                               ";
  	$sql .= "  inner join conlancamdoc              on c71_codlan = c70_codlan    ";
  	$sql .= " 	left join conlancamemp              on c75_codlan = c70_codlan    ";
  	$sql .= " 	left join conlancamacordo           on c70_codlan = c87_codlan    ";
  	$sql .= "   left join conlancaminscricaopassivo on c70_codlan = c37_conlancam ";
  	//$sql .= " 	left join empempenho                on c75_numemp = e60_numemp    ";
  	
  	$sql2 = "";
  	if ($dbwhere == "") {
  		if ($c70_codlan != null) {
  			$sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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
  
  
  
  function sql_query_reprocessaMovimentacaoPatrimonial($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
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
    $sql .= "        from conlancamdoc                                               ";
    $sql .= "  inner join conlancamnota                on conlancamnota.c66_codlan                           = conlancamdoc.c71_codlan                                ";
    $sql .= "  inner join conlancamemp                 on conlancamemp.c75_codlan                            = conlancamnota.c66_codlan                               ";
    $sql .= "  inner join empnota                      on empnota.e69_codnota                                = conlancamnota.c66_codnota                              ";
    $sql .= "  inner join empnotaord                   on empnotaord.m72_codnota                             = empnota.e69_codnota                                    ";
    $sql .= "  inner join matordemitem                 on matordemitem.m52_codordem                          = empnotaord.m72_codordem                                ";
    $sql .= "  inner join matestoqueitemoc             on matestoqueitemoc.m73_codmatordemitem               = matordemitem.m52_codlanc                               ";
    $sql .= "  inner join matestoqueitem               on matestoqueitem.m71_codlanc                         = matestoqueitemoc.m73_codmatestoqueitem                 ";
    $sql .= "  inner join matestoque                   on matestoque.m70_codigo                              = matestoqueitem.m71_codmatestoque                       ";
    $sql .= "  inner join matmater                     on matmater.m60_codmater                              = matestoque.m70_codmatmater                             ";
    $sql .= "  inner join matmatermaterialestoquegrupo on matmatermaterialestoquegrupo.m68_matmater          = matmater.m60_codmater                                  ";
    $sql .= "  inner join materialestoquegrupo         on materialestoquegrupo.m65_sequencial                = matmatermaterialestoquegrupo.m68_materialestoquegrupo  ";
    $sql .= "  inner join materialestoquegrupoconta    on materialestoquegrupoconta.m66_materialestoquegrupo = materialestoquegrupo.m65_sequencial                    ";
    $sql .= "  inner join conlancam                    on conlancam.c70_codlan                               = conlancamdoc.c71_codlan                                ";
    $sql .= "  inner join conlancamcompl               on conlancam.c70_codlan                               = conlancamcompl.c72_codlan                              ";
    $sql2 = "";
    if ($dbwhere == "") {
    		if ($c70_codlan != null) {
    		  $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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
  
  
  function sql_query_reprocessaExtraOrcamentario($c70_codlan = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
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
    $sql .= "        from conlancamdoc                                               ";
    $sql .= "  inner join conlancam          on conlancam.c70_codlan       = conlancamdoc.c71_codlan                                ";
    $sql .= "  inner join conlancamcompl     on conlancam.c70_codlan       = conlancamcompl.c72_codlan                              ";
    $sql .= "  inner join conlancamslip      on conlancam.c70_codlan       = conlancamslip.c84_conlancam                            ";
    
    
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c70_codlan != null) {
        $sql2 .= " where conlancam.c70_codlan = $c70_codlan ";
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
  
  
}
?>