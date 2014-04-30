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

//MODULO: orcamento
//CLASSE DA ENTIDADE ppadotacao
class cl_ppadotacao {
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
  var $o08_sequencial = 0;
  var $o08_ano = 0;
  var $o08_orgao = 0;
  var $o08_unidade = 0;
  var $o08_funcao = 0;
  var $o08_subfuncao = 0;
  var $o08_programa = 0;
  var $o08_projativ = 0;
  var $o08_elemento = 0;
  var $o08_recurso = 0;
  var $o08_instit = 0;
  var $o08_localizadorgastos = 0;
  var $o08_ppaversao = 0;
  var $o08_concarpeculiar = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 o08_sequencial = int4 = Código Sequencial
                 o08_ano = int4 = Ano da Dotação
                 o08_orgao = int4 = Orgão
                 o08_unidade = int4 = Unidade
                 o08_funcao = int4 = Função
                 o08_subfuncao = int4 = SubFunção
                 o08_programa = int4 = Programa
                 o08_projativ = int4 = Projeto/Atividade
                 o08_elemento = int4 = Elemento
                 o08_recurso = int4 = Recurso
                 o08_instit = int4 = Instituição
                 o08_localizadorgastos = int4 = Localizador dos gastos
                 o08_ppaversao = int4 = Versão do PPA
                 o08_concarpeculiar = varchar(100) = C.Peculiar/ C. Aplicação
                 ";
  //funcao construtor da classe
  function cl_ppadotacao() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ppadotacao");
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
      $this->o08_sequencial = ($this->o08_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_sequencial"]
          : $this->o08_sequencial);
      $this->o08_ano = ($this->o08_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_ano"] : $this->o08_ano);
      $this->o08_orgao = ($this->o08_orgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_orgao"] : $this->o08_orgao);
      $this->o08_unidade = ($this->o08_unidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_unidade"] : $this->o08_unidade);
      $this->o08_funcao = ($this->o08_funcao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_funcao"] : $this->o08_funcao);
      $this->o08_subfuncao = ($this->o08_subfuncao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_subfuncao"]
          : $this->o08_subfuncao);
      $this->o08_programa = ($this->o08_programa == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_programa"]
          : $this->o08_programa);
      $this->o08_projativ = ($this->o08_projativ == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_projativ"]
          : $this->o08_projativ);
      $this->o08_elemento = ($this->o08_elemento == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_elemento"]
          : $this->o08_elemento);
      $this->o08_recurso = ($this->o08_recurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_recurso"] : $this->o08_recurso);
      $this->o08_instit = ($this->o08_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_instit"] : $this->o08_instit);
      $this->o08_localizadorgastos = ($this->o08_localizadorgastos == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_localizadorgastos"]
          : $this->o08_localizadorgastos);
      $this->o08_ppaversao = ($this->o08_ppaversao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_ppaversao"]
          : $this->o08_ppaversao);
      $this->o08_concarpeculiar = ($this->o08_concarpeculiar == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_concarpeculiar"]
          : $this->o08_concarpeculiar);
    } else {
      $this->o08_sequencial = ($this->o08_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o08_sequencial"]
          : $this->o08_sequencial);
    }
  }
  // funcao para inclusao
  function incluir($o08_sequencial) {
    $this->atualizacampos();
    if ($this->o08_ano == null) {
      $this->erro_sql = " Campo Ano da Dotação nao Informado.";
      $this->erro_campo = "o08_ano";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_orgao == null) {
      $this->erro_sql = " Campo Orgão nao Informado.";
      $this->erro_campo = "o08_orgao";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_unidade == null) {
      $this->erro_sql = " Campo Unidade nao Informado.";
      $this->erro_campo = "o08_unidade";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_funcao == null) {
      $this->erro_sql = " Campo Função nao Informado.";
      $this->erro_campo = "o08_funcao";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_subfuncao == null) {
      $this->erro_sql = " Campo SubFunção nao Informado.";
      $this->erro_campo = "o08_subfuncao";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_programa == null) {
      $this->erro_sql = " Campo Programa nao Informado.";
      $this->erro_campo = "o08_programa";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_projativ == null) {
      $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
      $this->erro_campo = "o08_projativ";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_elemento == null) {
      $this->erro_sql = " Campo Elemento nao Informado.";
      $this->erro_campo = "o08_elemento";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_recurso == null) {
      $this->erro_sql = " Campo Recurso nao Informado.";
      $this->erro_campo = "o08_recurso";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_instit == null) {
      $this->erro_sql = " Campo Instituição nao Informado.";
      $this->erro_campo = "o08_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_localizadorgastos == null) {
      $this->o08_localizadorgastos = "0";
    }
    if ($this->o08_ppaversao == null) {
      $this->erro_sql = " Campo Versão do PPA nao Informado.";
      $this->erro_campo = "o08_ppaversao";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->o08_concarpeculiar == null) {
      $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
      $this->erro_campo = "o08_concarpeculiar";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($o08_sequencial == "" || $o08_sequencial == null) {
      $result = db_query("select nextval('ppadotacao_o08_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ppadotacao_o08_sequencial_seq do campo: o08_sequencial";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->o08_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ppadotacao_o08_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $o08_sequencial)) {
        $this->erro_sql = " Campo o08_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->o08_sequencial = $o08_sequencial;
      }
    }
    if (($this->o08_sequencial == null) || ($this->o08_sequencial == "")) {
      $this->erro_sql = " Campo o08_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into ppadotacao(
                                       o08_sequencial
                                      ,o08_ano
                                      ,o08_orgao
                                      ,o08_unidade
                                      ,o08_funcao
                                      ,o08_subfuncao
                                      ,o08_programa
                                      ,o08_projativ
                                      ,o08_elemento
                                      ,o08_recurso
                                      ,o08_instit
                                      ,o08_localizadorgastos
                                      ,o08_ppaversao
                                      ,o08_concarpeculiar
                       )
                values (
                                $this->o08_sequencial
                               ,$this->o08_ano
                               ,$this->o08_orgao
                               ,$this->o08_unidade
                               ,$this->o08_funcao
                               ,$this->o08_subfuncao
                               ,$this->o08_programa
                               ,$this->o08_projativ
                               ,$this->o08_elemento
                               ,$this->o08_recurso
                               ,$this->o08_instit
                               ,$this->o08_localizadorgastos
                               ,$this->o08_ppaversao
                               ,'$this->o08_concarpeculiar'
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "Dotações do ppa ($this->o08_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Dotações do ppa já Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
      } else {
        $this->erro_sql = "Dotações do ppa ($this->o08_sequencial) nao Incluído. Inclusao Abortada.";
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
    $this->erro_sql .= "Valores : " . $this->o08_sequencial;
    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->o08_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,13702,'$this->o08_sequencial','I')");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13702,'','" . AddSlashes(pg_result($resaco, 0, 'o08_sequencial'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13704,'','" . AddSlashes(pg_result($resaco, 0, 'o08_ano')) . "',"
              . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13705,'','" . AddSlashes(pg_result($resaco, 0, 'o08_orgao'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13706,'','" . AddSlashes(pg_result($resaco, 0, 'o08_unidade'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13707,'','" . AddSlashes(pg_result($resaco, 0, 'o08_funcao'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13708,'','" . AddSlashes(pg_result($resaco, 0, 'o08_subfuncao'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13709,'','" . AddSlashes(pg_result($resaco, 0, 'o08_programa'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13710,'','" . AddSlashes(pg_result($resaco, 0, 'o08_projativ'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13711,'','" . AddSlashes(pg_result($resaco, 0, 'o08_elemento'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13712,'','" . AddSlashes(pg_result($resaco, 0, 'o08_recurso'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13713,'','" . AddSlashes(pg_result($resaco, 0, 'o08_instit'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,13718,'','"
              . AddSlashes(pg_result($resaco, 0, 'o08_localizadorgastos')) . "'," . db_getsession('DB_datausu') . ","
              . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,14317,'','" . AddSlashes(pg_result($resaco, 0, 'o08_ppaversao'))
              . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query(
          "insert into db_acount values($acount,2396,18158,'','"
              . AddSlashes(pg_result($resaco, 0, 'o08_concarpeculiar')) . "'," . db_getsession('DB_datausu') . ","
              . db_getsession('DB_id_usuario') . ")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar($o08_sequencial = null) {
    $this->atualizacampos();
    $sql = " update ppadotacao set ";
    $virgula = "";
    if (trim($this->o08_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_sequencial"])) {
      $sql .= $virgula . " o08_sequencial = $this->o08_sequencial ";
      $virgula = ",";
      if (trim($this->o08_sequencial) == null) {
        $this->erro_sql = " Campo Código Sequencial nao Informado.";
        $this->erro_campo = "o08_sequencial";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_ano) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_ano"])) {
      $sql .= $virgula . " o08_ano = $this->o08_ano ";
      $virgula = ",";
      if (trim($this->o08_ano) == null) {
        $this->erro_sql = " Campo Ano da Dotação nao Informado.";
        $this->erro_campo = "o08_ano";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_orgao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_orgao"])) {
      $sql .= $virgula . " o08_orgao = $this->o08_orgao ";
      $virgula = ",";
      if (trim($this->o08_orgao) == null) {
        $this->erro_sql = " Campo Orgão nao Informado.";
        $this->erro_campo = "o08_orgao";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_unidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_unidade"])) {
      $sql .= $virgula . " o08_unidade = $this->o08_unidade ";
      $virgula = ",";
      if (trim($this->o08_unidade) == null) {
        $this->erro_sql = " Campo Unidade nao Informado.";
        $this->erro_campo = "o08_unidade";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_funcao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_funcao"])) {
      $sql .= $virgula . " o08_funcao = $this->o08_funcao ";
      $virgula = ",";
      if (trim($this->o08_funcao) == null) {
        $this->erro_sql = " Campo Função nao Informado.";
        $this->erro_campo = "o08_funcao";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_subfuncao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_subfuncao"])) {
      $sql .= $virgula . " o08_subfuncao = $this->o08_subfuncao ";
      $virgula = ",";
      if (trim($this->o08_subfuncao) == null) {
        $this->erro_sql = " Campo SubFunção nao Informado.";
        $this->erro_campo = "o08_subfuncao";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_programa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_programa"])) {
      $sql .= $virgula . " o08_programa = $this->o08_programa ";
      $virgula = ",";
      if (trim($this->o08_programa) == null) {
        $this->erro_sql = " Campo Programa nao Informado.";
        $this->erro_campo = "o08_programa";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_projativ) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_projativ"])) {
      $sql .= $virgula . " o08_projativ = $this->o08_projativ ";
      $virgula = ",";
      if (trim($this->o08_projativ) == null) {
        $this->erro_sql = " Campo Projeto/Atividade nao Informado.";
        $this->erro_campo = "o08_projativ";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_elemento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_elemento"])) {
      $sql .= $virgula . " o08_elemento = $this->o08_elemento ";
      $virgula = ",";
      if (trim($this->o08_elemento) == null) {
        $this->erro_sql = " Campo Elemento nao Informado.";
        $this->erro_campo = "o08_elemento";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_recurso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_recurso"])) {
      $sql .= $virgula . " o08_recurso = $this->o08_recurso ";
      $virgula = ",";
      if (trim($this->o08_recurso) == null) {
        $this->erro_sql = " Campo Recurso nao Informado.";
        $this->erro_campo = "o08_recurso";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_instit"])) {
      $sql .= $virgula . " o08_instit = $this->o08_instit ";
      $virgula = ",";
      if (trim($this->o08_instit) == null) {
        $this->erro_sql = " Campo Instituição nao Informado.";
        $this->erro_campo = "o08_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_localizadorgastos) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_localizadorgastos"])) {
      if (trim($this->o08_localizadorgastos) == "" && isset($GLOBALS["HTTP_POST_VARS"]["o08_localizadorgastos"])) {
        $this->o08_localizadorgastos = "0";
      }
      $sql .= $virgula . " o08_localizadorgastos = $this->o08_localizadorgastos ";
      $virgula = ",";
    }
    if (trim($this->o08_ppaversao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_ppaversao"])) {
      $sql .= $virgula . " o08_ppaversao = $this->o08_ppaversao ";
      $virgula = ",";
      if (trim($this->o08_ppaversao) == null) {
        $this->erro_sql = " Campo Versão do PPA nao Informado.";
        $this->erro_campo = "o08_ppaversao";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->o08_concarpeculiar) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o08_concarpeculiar"])) {
      $sql .= $virgula . " o08_concarpeculiar = '$this->o08_concarpeculiar' ";
      $virgula = ",";
      if (trim($this->o08_concarpeculiar) == null) {
        $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
        $this->erro_campo = "o08_concarpeculiar";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($o08_sequencial != null) {
      $sql .= " o08_sequencial = $this->o08_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->o08_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,13702,'$this->o08_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_sequencial"]) || $this->o08_sequencial != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13702,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_sequencial')) . "','$this->o08_sequencial',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_ano"]) || $this->o08_ano != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13704,'" . AddSlashes(pg_result($resaco, $conresaco, 'o08_ano'))
                  . "','$this->o08_ano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_orgao"]) || $this->o08_orgao != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13705,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_orgao')) . "','$this->o08_orgao',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_unidade"]) || $this->o08_unidade != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13706,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_unidade')) . "','$this->o08_unidade',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_funcao"]) || $this->o08_funcao != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13707,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_funcao')) . "','$this->o08_funcao',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_subfuncao"]) || $this->o08_subfuncao != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13708,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_subfuncao')) . "','$this->o08_subfuncao',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_programa"]) || $this->o08_programa != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13709,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_programa')) . "','$this->o08_programa',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_projativ"]) || $this->o08_projativ != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13710,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_projativ')) . "','$this->o08_projativ',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_elemento"]) || $this->o08_elemento != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13711,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_elemento')) . "','$this->o08_elemento',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_recurso"]) || $this->o08_recurso != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13712,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_recurso')) . "','$this->o08_recurso',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_instit"]) || $this->o08_instit != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13713,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_instit')) . "','$this->o08_instit',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_localizadorgastos"]) || $this->o08_localizadorgastos != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,13718,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_localizadorgastos'))
                  . "','$this->o08_localizadorgastos'," . db_getsession('DB_datausu') . ","
                  . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_ppaversao"]) || $this->o08_ppaversao != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,14317,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_ppaversao')) . "','$this->o08_ppaversao',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["o08_concarpeculiar"]) || $this->o08_concarpeculiar != "")
          $resac = db_query(
              "insert into db_acount values($acount,2396,18158,'"
                  . AddSlashes(pg_result($resaco, $conresaco, 'o08_concarpeculiar')) . "','$this->o08_concarpeculiar',"
                  . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Dotações do ppa nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->o08_sequencial;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Dotações do ppa nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->o08_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->o08_sequencial;
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
  function excluir($o08_sequencial = null, $dbwhere = null) {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($o08_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,13702,'$o08_sequencial','E')");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13702,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_sequencial')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13704,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o08_ano'))
                . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13705,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_orgao')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13706,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_unidade')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13707,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_funcao')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13708,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_subfuncao')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13709,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_programa')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13710,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_projativ')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13711,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_elemento')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13712,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_recurso')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13713,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_instit')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,13718,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_localizadorgastos')) . "',"
                . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,14317,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_ppaversao')) . "'," . db_getsession('DB_datausu') . ","
                . db_getsession('DB_id_usuario') . ")");
        $resac = db_query(
            "insert into db_acount values($acount,2396,18158,'','"
                . AddSlashes(pg_result($resaco, $iresaco, 'o08_concarpeculiar')) . "'," . db_getsession('DB_datausu')
                . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from ppadotacao
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($o08_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " o08_sequencial = $o08_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Dotações do ppa nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $o08_sequencial;
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Dotações do ppa nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $o08_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
            . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $o08_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:ppadotacao";
      $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco
          . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
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
    $sql .= " from ppadotacao ";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = ppadotacao.o08_elemento and  orcelemento.o56_anousu = ppadotacao.o08_ano";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
    $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = ppadotacao.o08_concarpeculiar";
    $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
    $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppadotacao.o08_ppaversao";
    $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
    $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
    $sql .= "      inner join db_config  as a on   a.codigo = orcorgao.o40_instit";
    $sql .= "      inner join db_config  as b on   b.codigo = orcunidade.o41_instit";
    $sql .= "      inner join orcorgao  as c on   c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaversao.o119_idusuario";
    $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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
  function sql_query_file($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
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
    $sql .= " from ppadotacao ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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
   * retornar as dotações com vonculo ao orcamento
   *
   * @param unknown_type $o08_sequencial
   * @param unknown_type $campos
   * @param unknown_type $ordem
   * @param unknown_type $dbwhere
   * @return unknown
   */
  public function sql_query_dotacao_integrada($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " From ppaestimativadespesa  ";
    $sql .= "      inner join ppadotacao           on o07_coddot     = o08_sequencial  ";
    $sql .= "      inner join ppaestimativa        on o07_ppaestimativa = o05_sequencial ";
    $sql .= "      inner join ppaversao            on o08_ppaversao  = o119_sequencial  ";
    $sql .= "      inner join ppaintegracao        on o123_ppaversao = o119_sequencial  ";
    $sql .= "      left  join ppaintegracaodespesa on o121_ppaestimativadespesa = o07_sequencial ";
    $sql .= "                                     and o121_ppaintegracao = o123_sequencial ";
    $sql .= "      left join orcdotacao            on o58_anousu    = o08_ano ";
    $sql .= "                                     and o58_orgao     = o08_orgao  ";
    $sql .= "                                     and o58_unidade   = o08_unidade  ";
    $sql .= "                                     and o58_funcao    = o08_funcao  ";
    $sql .= "                                     and o58_subfuncao = o08_subfuncao  ";
    $sql .= "                                     and o58_programa  = o08_programa  ";
    $sql .= "                                     and o58_projativ  = o08_projativ ";
    $sql .= "                                     and o58_codele    = o08_elemento  ";
    $sql .= "                                     and o58_codigo    = o08_recurso ";
    $sql .= "                                     and o58_localizadorgastos    = o08_localizadorgastos ";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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
  function sql_query_estimativa($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
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
    $sql .= " from ppadotacao ";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = ppadotacao.o08_elemento and  orcelemento.o56_anousu = ppadotacao.o08_ano";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
    $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
    $sql .= "      inner join db_config  as a on   a.codigo = orcprojativ.o55_instit";
    $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
    $sql .= "      inner join db_config  as b on   b.codigo = orcorgao.o40_instit";
    $sql .= "      inner join db_config  as c on   c.codigo = orcunidade.o41_instit";
    $sql .= "      inner join orctiporec  on   o15_codigo = o08_recurso";
    $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
    $sql .= "      inner join ppaestimativadespesa  on o08_sequencial = o07_coddot";
    $sql .= "      inner join ppaestimativa   on o07_ppaestimativa = o05_sequencial";
    $sql .= "      inner join ppaversao on o05_ppaversao = o119_sequencial";
    $sql .= "      inner join ppalei   on o01_sequencial = o119_ppalei";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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

  function sql_query_despesa_ppa($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
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
    $sql .= " from ppadotacao ";
    $sql .= "        inner join ppaestimativadespesa on ppadotacao.o08_sequencial              = ppaestimativadespesa.o07_coddot";
    $sql .= "        inner join ppaestimativa        on ppaestimativadespesa.o07_ppaestimativa = ppaestimativa.o05_sequencial";
    $sql .= "        inner join ppadotacaoorcdotacao on ppadotacaoorcdotacao.o19_ppadotacao    = ppadotacao.o08_sequencial";

    $sql2 = "";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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

  function sql_query_despesa_programa($o08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
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
    $sql .= " from ppadotacao ";
    $sql .= "      inner join ppaestimativadespesa on ppaestimativadespesa.o07_coddot = ppadotacao.o08_sequencial              ";
    $sql .= "                                     and ppaestimativadespesa.o07_anousu = ppadotacao.o08_ano                     ";
    $sql .= "      inner join ppaestimativa        on ppaestimativa.o05_sequencial    = ppaestimativadespesa.o07_ppaestimativa ";
    $sql .= "      inner join orcprograma          on orcprograma.o54_programa        = ppadotacao.o08_programa                ";
    $sql .= "                                     and orcprograma.o54_anousu          = ppadotacao.o08_ano                     ";

    $sql2 = "";
    if ($dbwhere == "") {
      if ($o08_sequencial != null) {
        $sql2 .= " where ppadotacao.o08_sequencial = $o08_sequencial ";
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