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
//CLASSE DA ENTIDADE conplanoorcamentoanalitica

class cl_conplanoorcamentoanalitica {
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
  var $c61_reduz = 0;
  var $c61_codcon = 0;
  var $c61_anousu = 0;
  var $c61_instit = 0;
  var $c61_codigo = 0;
  var $c61_contrapartida = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c61_reduz = int4 = Reduzido
                 c61_codcon = int4 = Código da Conta
                 c61_anousu = int4 = Exercício
                 c61_instit = int4 = Instituição
                 c61_codigo = int4 = Codigo do Recurso
                 c61_contrapartida = int4 = Contra Partida
                 ";
  //funcao construtor da classe

  function cl_conplanoorcamentoanalitica() {
    //classes dos rotulos dos campos

    $this->rotulo = new rotulo("conplanoorcamentoanalitica");
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
      $this->c61_reduz = ($this->c61_reduz == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_reduz"] : $this->c61_reduz);
      $this->c61_codcon = ($this->c61_codcon == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_codcon"] : $this->c61_codcon);
      $this->c61_anousu = ($this->c61_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_anousu"] : $this->c61_anousu);
      $this->c61_instit = ($this->c61_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_instit"] : $this->c61_instit);
      $this->c61_codigo = ($this->c61_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_codigo"] : $this->c61_codigo);
      $this->c61_contrapartida = ($this->c61_contrapartida == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"]
          : $this->c61_contrapartida);
    } else {
      $this->c61_reduz = ($this->c61_reduz == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_reduz"] : $this->c61_reduz);
      $this->c61_anousu = ($this->c61_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["c61_anousu"] : $this->c61_anousu);
    }
  }
  // funcao para inclusao

  function incluir($c61_reduz, $c61_anousu) {

    $this->atualizacampos();
    if ($this->c61_codcon == null) {
      $this->erro_sql = " Campo Código da Conta nao Informado.";
      $this->erro_campo = "c61_codcon";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c61_instit == null) {
      $this->erro_sql = " Campo Instituição nao Informado.";
      $this->erro_campo = "c61_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c61_codigo == null) {
      $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
      $this->erro_campo = "c61_codigo";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c61_contrapartida == null) {
      $this->c61_contrapartida = "0";
    }
    if ($c61_reduz == "" || $c61_reduz == null) {
      $result = db_query("select nextval('conplanoorcamentoanalitica_c61_reduz_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: conplanoorcamentoanalitica_c61_reduz_seq do campo: c61_reduz";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c61_reduz = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from conplanoorcamentoanalitica_c61_reduz_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $c61_reduz)) {
        $this->erro_sql = " Campo c61_reduz maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->c61_reduz = $c61_reduz;
      }
    }
    if (($this->c61_reduz == null) || ($this->c61_reduz == "")) {
      $this->erro_sql = " Campo c61_reduz nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if (($this->c61_anousu == null) || ($this->c61_anousu == "")) {
      $this->erro_sql = " Campo c61_anousu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into conplanoorcamentoanalitica(
                                       c61_reduz
                                      ,c61_codcon
                                      ,c61_anousu
                                      ,c61_instit
                                      ,c61_codigo
                                      ,c61_contrapartida
                       )
                values (
                                $this->c61_reduz
                               ,$this->c61_codcon
                               ,$this->c61_anousu
                               ,$this->c61_instit
                               ,$this->c61_codigo
                               ,$this->c61_contrapartida
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "conplanoorcamentoanalitica ($this->c61_reduz."
            - ".$this->c61_anousu) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "conplanoorcamentoanalitica já Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      } else {
        $this->erro_sql = "conplanoorcamentoanalitica ($this->c61_reduz."
            - ".$this->c61_anousu) nao Incluído. Inclusao Abortada.";
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
    $this->erro_sql .= "Valores : " . $this->c61_reduz . "-" . $this->c61_anousu;
    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->c61_reduz, $this->c61_anousu));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,5247,'$this->c61_reduz','I')");
      $resac = db_query("insert into db_acountkey values($acount,8060,'$this->c61_anousu','I')");
      $resac = db_query(
          "insert into db_acount values($acount,3272,5247,'','" . AddSlashes(pg_result($resaco, 0, 'c61_reduz')) . "',"
              . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,3272,5246,'','" . AddSlashes(pg_result($resaco, 0, 'c61_codcon'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,3272,8060,'','" . AddSlashes(pg_result($resaco, 0, 'c61_anousu'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,3272,5248,'','" . AddSlashes(pg_result($resaco, 0, 'c61_instit'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,3272,5249,'','" . AddSlashes(pg_result($resaco, 0, 'c61_codigo'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,3272,7372,'','" . AddSlashes(pg_result($resaco, 0, 'c61_contrapartida'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }
    return true;
  }
  // funcao para alteracao

  function alterar($c61_reduz = null, $c61_anousu = null) {

    $this->atualizacampos();
    $sql = " update conplanoorcamentoanalitica set ";
    $virgula = "";
    if (trim($this->c61_reduz) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_reduz"])) {
      $sql .= $virgula . " c61_reduz = $this->c61_reduz ";
      $virgula = ",";
      if (trim($this->c61_reduz) == null) {
        $this->erro_sql = " Campo Reduzido nao Informado.";
        $this->erro_campo = "c61_reduz";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c61_codcon) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_codcon"])) {
      $sql .= $virgula . " c61_codcon = $this->c61_codcon ";
      $virgula = ",";
      if (trim($this->c61_codcon) == null) {
        $this->erro_sql = " Campo Código da Conta nao Informado.";
        $this->erro_campo = "c61_codcon";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c61_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_anousu"])) {
      $sql .= $virgula . " c61_anousu = $this->c61_anousu ";
      $virgula = ",";
      if (trim($this->c61_anousu) == null) {
        $this->erro_sql = " Campo Exercício nao Informado.";
        $this->erro_campo = "c61_anousu";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c61_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_instit"])) {
      $sql .= $virgula . " c61_instit = $this->c61_instit ";
      $virgula = ",";
      if (trim($this->c61_instit) == null) {
        $this->erro_sql = " Campo Instituição nao Informado.";
        $this->erro_campo = "c61_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c61_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_codigo"])) {
      $sql .= $virgula . " c61_codigo = $this->c61_codigo ";
      $virgula = ",";
      if (trim($this->c61_codigo) == null) {
        $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
        $this->erro_campo = "c61_codigo";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c61_contrapartida) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"])) {
      if (trim($this->c61_contrapartida) == "" && isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"])) {
        $this->c61_contrapartida = "0";
      }
      $sql .= $virgula . " c61_contrapartida = $this->c61_contrapartida ";
      $virgula = ",";
    }
    $sql .= " where ";
    if ($c61_reduz != null) {
      $sql .= " c61_reduz = $this->c61_reduz";
    }
    if ($c61_anousu != null) {
      $sql .= " and  c61_anousu = $this->c61_anousu";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->c61_reduz, $this->c61_anousu));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,5247,'$this->c61_reduz','A')");
        $resac = db_query("insert into db_acountkey values($acount,8060,'$this->c61_anousu','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_reduz"]) || $this->c61_reduz != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,5247,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_reduz')) . "','$this->c61_reduz',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_codcon"]) || $this->c61_codcon != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,5246,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_codcon')) . "','$this->c61_codcon',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_anousu"]) || $this->c61_anousu != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,8060,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_anousu')) . "','$this->c61_anousu',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_instit"]) || $this->c61_instit != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,5248,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_instit')) . "','$this->c61_instit',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_codigo"]) || $this->c61_codigo != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,5249,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_codigo')) . "','$this->c61_codigo',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c61_contrapartida"]) || $this->c61_contrapartida != "")
          $resac = db_query(
              "insert into db_acount values($acount,3272,7372,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'c61_contrapartida')) . "','$this->c61_contrapartida',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "conplanoorcamentoanalitica nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->c61_reduz . "-" . $this->c61_anousu;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "conplanoorcamentoanalitica nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->c61_reduz . "-" . $this->c61_anousu;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->c61_reduz . "-" . $this->c61_anousu;
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

  function excluir($c61_reduz = null, $c61_anousu = null, $dbwhere = null) {

    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($c61_reduz, $c61_anousu));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,5247,'$c61_reduz','E')");
        $resac = db_query("insert into db_acountkey values($acount,8060,'$c61_anousu','E')");
        $resac = db_query(
            "insert into db_acount values($acount,3272,5247,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c61_reduz'))
                . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,3272,5246,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'c61_codcon')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,3272,8060,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'c61_anousu')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,3272,5248,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'c61_instit')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,3272,5249,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'c61_codigo')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,3272,7372,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'c61_contrapartida')) . "'," . db_getsession('DB_datausu')
                . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from conplanoorcamentoanalitica
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($c61_reduz != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c61_reduz = $c61_reduz ";
      }
      if ($c61_anousu != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c61_anousu = $c61_anousu ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "conplanoorcamentoanalitica nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $c61_reduz . "-" . $c61_anousu;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "conplanoorcamentoanalitica nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $c61_reduz . "-" . $c61_anousu;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $c61_reduz . "-" . $c61_anousu;
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
      $this->erro_sql = "Record Vazio na Tabela:conplanoorcamentoanalitica";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql

  function sql_query($c61_reduz = null, $c61_anousu = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conplanoorcamentoanalitica ";
    $sql .= "      inner join db_config  on  db_config.codigo = conplanoorcamentoanalitica.c61_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoorcamentoanalitica.c61_codigo";
    $sql .= "      inner join conplanoorcamento  on  conplanoorcamento.c60_codcon = conplanoorcamentoanalitica.c61_codcon and  conplanoorcamento.c60_anousu = conplanoorcamentoanalitica.c61_anousu";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
    $sql .= "      inner join conclass  on  conclass.c51_codcla = conplanoorcamento.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis = conplanoorcamento.c60_codsis";
    $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplanoorcamento.c60_consistemaconta";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c61_reduz != null) {
        $sql2 .= " where conplanoorcamentoanalitica.c61_reduz = $c61_reduz ";
      }
      if ($c61_anousu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoorcamentoanalitica.c61_anousu = $c61_anousu ";
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
  // funcao do sql

  function sql_query_file($c61_reduz = null, $c61_anousu = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conplanoorcamentoanalitica ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c61_reduz != null) {
        $sql2 .= " where conplanoorcamentoanalitica.c61_reduz = $c61_reduz ";
      }
      if ($c61_anousu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoorcamentoanalitica.c61_anousu = $c61_anousu ";
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
   * Busca todos os Reduzidos que não possuem registros no ano de destino
   * @param integer $iAnoAtual
   * @param integer $iAnoDestino
   * @param integer $iIntinst
   * @return string
   */
  function sql_query_analiticaProximoExercicio($iAnoAtual, $iAnoDestino, $iIntinst) {

    $sSQl = "select conplanoorcamentoanalitica.* 																										";
    $sSQl .= "  from conplanoorcamentoanalitica																												";
    $sSQl .= "       left join conplanoorcamentoanalitica  proximoexercicio   												";
    $sSQl .= "                    on proximoexercicio.c61_reduz  = conplanoorcamentoanalitica.c61_reduz         		";
    $sSQl .= "                   and proximoexercicio.c61_instit = conplanoorcamentoanalitica.c61_instit           ";
    $sSQl .= "                   and proximoexercicio.c61_anousu = {$iAnoDestino} 										";
    $sSQl .= " where conplanoorcamentoanalitica.c61_anousu = {$iAnoAtual}                                          ";
    $sSQl .= "   and conplanoorcamentoanalitica.c61_instit = {$iIntinst}                                           ";
    $sSQl .= "   and proximoexercicio.c61_anousu is null                                              ";

    return $sSQl;
  }
  
  function sql_query_reduzVinculoAnalitica($c61_reduz = null, $c61_anousu = null, $campos = "*", $ordem = null, $dbwhere = "") {
  
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
    $sql .= " from conplanoorcamentoanalitica ";
    $sql .= "      inner join conplanoorcamento         on conplanoorcamento.c60_codcon                    = conplanoorcamentoanalitica.c61_codcon ";
    $sql .= "                                          and conplanoorcamento.c60_anousu                    = conplanoorcamentoanalitica.c61_anousu";
    $sql .= "      inner join conplanoconplanoorcamento on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon ";
    $sql .= "                                          and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu";
    $sql .= "      inner join conplano                  on conplanoconplanoorcamento.c72_conplano          = conplano.c60_codcon";
    $sql .= "                                          and conplanoconplanoorcamento.c72_anousu            = conplano.c60_anousu";
    $sql .= "      inner join conplanoreduz             on conplano.c60_codcon                             = conplanoreduz.c61_codcon";
    $sql .= "                                          and conplano.c60_anousu                             = conplanoreduz.c61_anousu";
    $sql2 = "";
    
    if ($dbwhere == "") {
      if ($c61_reduz != null) {
        $sql2 .= " where conplanoorcamentoanalitica.c61_reduz = $c61_reduz ";
      }
      if ($c61_anousu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoorcamentoanalitica.c61_anousu = $c61_anousu ";
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

  function sql_query_simples($c61_reduz = null, $c61_anousu = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from conplanoorcamentoanalitica ";
    $sql .= "      inner join conplanoorcamento  on  conplanoorcamento.c60_codcon = conplanoorcamentoanalitica.c61_codcon";
    $sql .= "                                   and  conplanoorcamento.c60_anousu = conplanoorcamentoanalitica.c61_anousu";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c61_reduz != null) {
        $sql2 .= " where conplanoorcamentoanalitica.c61_reduz = $c61_reduz ";
      }
      if ($c61_anousu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoorcamentoanalitica.c61_anousu = $c61_anousu ";
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