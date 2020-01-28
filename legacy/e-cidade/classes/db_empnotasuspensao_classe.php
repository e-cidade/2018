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

//MODULO: empenho
//CLASSE DA ENTIDADE empnotasuspensao
class cl_empnotasuspensao {

  // cria variaveis de erro
  var $rotulo          = null;
  var $query_sql       = null;
  var $numrows         = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status     = null;
  var $erro_sql        = null;
  var $erro_banco      = null;
  var $erro_msg        = null;
  var $erro_campo      = null;
  var $pagina_retorno  = null;
  // cria variaveis do arquivo
  var $cc36_sequencial             = 0;
  var $cc36_empnota                = 0;
  var $cc36_justificativasuspensao = null;
  var $cc36_datasuspensao_dia      = null;
  var $cc36_datasuspensao_mes      = null;
  var $cc36_datasuspensao_ano      = null;
  var $cc36_datasuspensao          = null;
  var $cc36_justificativaretorno   = null;
  var $cc36_dataretorno_dia        = null;
  var $cc36_dataretorno_mes        = null;
  var $cc36_dataretorno_ano        = null;
  var $cc36_dataretorno            = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 cc36_sequencial = int4 = Codigo
                 cc36_empnota = int4 = Nota de Liquidação
                 cc36_justificativasuspensao = text = Justificativa de Suspensão
                 cc36_datasuspensao = date = Data de Suspensão
                 cc36_justificativaretorno = text = Justificativa de Retorno
                 cc36_dataretorno = date = Data de Retorno
                 ";
  //funcao construtor da classe
  function cl_empnotasuspensao() {

    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empnotasuspensao");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  // funcao para Inclusão
  function incluir ($cc36_sequencial){

    if($this->cc36_empnota == null ){
      $this->erro_sql = " Campo Nota de Liquidação não informado.";
      $this->erro_campo = "cc36_empnota";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->cc36_justificativasuspensao == null ){
      $this->erro_sql = " Campo Justificativa de Suspensão não informado.";
      $this->erro_campo = "cc36_justificativasuspensao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->cc36_datasuspensao == null ){
      $this->erro_sql = " Campo Data de Suspensão não informado.";
      $this->erro_campo = "cc36_datasuspensao_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($cc36_sequencial == "" || $cc36_sequencial == null ){
      $result = db_query("select nextval('empnotasuspensao_cc36_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: empnotasuspensao_cc36_sequencial_seq do campo: cc36_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->cc36_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from empnotasuspensao_cc36_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $cc36_sequencial)){
        $this->erro_sql = " Campo cc36_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->cc36_sequencial = $cc36_sequencial;
      }
    }
    if(($this->cc36_sequencial == null) || ($this->cc36_sequencial == "") ){
      $this->erro_sql = " Campo cc36_sequencial não declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empnotasuspensao(
                                       cc36_sequencial
                                      ,cc36_empnota
                                      ,cc36_justificativasuspensao
                                      ,cc36_datasuspensao
                                      ,cc36_justificativaretorno
                                      ,cc36_dataretorno
                       )
                values (
                                $this->cc36_sequencial
                               ,$this->cc36_empnota
                               ,'$this->cc36_justificativasuspensao'
                               ,".($this->cc36_datasuspensao == "null" || $this->cc36_datasuspensao == ""?"null":"'".$this->cc36_datasuspensao."'")."
                               ,'$this->cc36_justificativaretorno'
                               ,".($this->cc36_dataretorno == "null" || $this->cc36_dataretorno == ""?"null":"'".$this->cc36_dataretorno."'")."
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Suspensão de Nota de Liquidação ($this->cc36_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Suspensão de Nota de Liquidação já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Suspensão de Nota de Liquidação ($this->cc36_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->cc36_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->cc36_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,21966,'$this->cc36_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,3954,21966,'','".AddSlashes(pg_result($resaco,0,'cc36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3954,21967,'','".AddSlashes(pg_result($resaco,0,'cc36_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3954,21968,'','".AddSlashes(pg_result($resaco,0,'cc36_justificativasuspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3954,21969,'','".AddSlashes(pg_result($resaco,0,'cc36_datasuspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3954,21970,'','".AddSlashes(pg_result($resaco,0,'cc36_justificativaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3954,21971,'','".AddSlashes(pg_result($resaco,0,'cc36_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }

  // funcao para alteracao
  public function alterar ($cc36_sequencial=null) {

    $sql = " update empnotasuspensao set ";
    $virgula = "";
    if(trim($this->cc36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_sequencial"])){
      $sql  .= $virgula." cc36_sequencial = $this->cc36_sequencial ";
      $virgula = ",";
      if(trim($this->cc36_sequencial) == null ){
        $this->erro_sql = " Campo Codigo não informado.";
        $this->erro_campo = "cc36_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->cc36_empnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_empnota"])){
      $sql  .= $virgula." cc36_empnota = $this->cc36_empnota ";
      $virgula = ",";
      if(trim($this->cc36_empnota) == null ){
        $this->erro_sql = " Campo Nota de Liquidação não informado.";
        $this->erro_campo = "cc36_empnota";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->cc36_justificativasuspensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_justificativasuspensao"])){
      $sql  .= $virgula." cc36_justificativasuspensao = '$this->cc36_justificativasuspensao' ";
      $virgula = ",";
      if(trim($this->cc36_justificativasuspensao) == null ){
        $this->erro_sql = " Campo Justificativa de Suspensão não informado.";
        $this->erro_campo = "cc36_justificativasuspensao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->cc36_datasuspensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_datasuspensao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cc36_datasuspensao_dia"] !="") ){
      $sql  .= $virgula." cc36_datasuspensao = '$this->cc36_datasuspensao' ";
      $virgula = ",";
      if(trim($this->cc36_datasuspensao) == null ){
        $this->erro_sql = " Campo Data de Suspensão não informado.";
        $this->erro_campo = "cc36_datasuspensao_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["cc36_datasuspensao_dia"])){
        $sql  .= $virgula." cc36_datasuspensao = null ";
        $virgula = ",";
        if(trim($this->cc36_datasuspensao) == null ){
          $this->erro_sql = " Campo Data de Suspensão não informado.";
          $this->erro_campo = "cc36_datasuspensao_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->cc36_justificativaretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_justificativaretorno"])){
      $sql  .= $virgula." cc36_justificativaretorno = '$this->cc36_justificativaretorno' ";
      $virgula = ",";
    }
    if(trim($this->cc36_dataretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc36_dataretorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cc36_dataretorno_dia"] !="") ){
      $sql  .= $virgula." cc36_dataretorno = '$this->cc36_dataretorno' ";
      $virgula = ",";
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["cc36_dataretorno_dia"])){
        $sql  .= $virgula." cc36_dataretorno = null ";
        $virgula = ",";
      }
    }
    $sql .= " where ";
    if($cc36_sequencial!=null){
      $sql .= " cc36_sequencial = $this->cc36_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->cc36_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,21966,'$this->cc36_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_sequencial"]) || $this->cc36_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,3954,21966,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_sequencial'))."','$this->cc36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_empnota"]) || $this->cc36_empnota != "")
            $resac = db_query("insert into db_acount values($acount,3954,21967,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_empnota'))."','$this->cc36_empnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_justificativasuspensao"]) || $this->cc36_justificativasuspensao != "")
            $resac = db_query("insert into db_acount values($acount,3954,21968,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_justificativasuspensao'))."','$this->cc36_justificativasuspensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_datasuspensao"]) || $this->cc36_datasuspensao != "")
            $resac = db_query("insert into db_acount values($acount,3954,21969,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_datasuspensao'))."','$this->cc36_datasuspensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_justificativaretorno"]) || $this->cc36_justificativaretorno != "")
            $resac = db_query("insert into db_acount values($acount,3954,21970,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_justificativaretorno'))."','$this->cc36_justificativaretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["cc36_dataretorno"]) || $this->cc36_dataretorno != "")
            $resac = db_query("insert into db_acount values($acount,3954,21971,'".AddSlashes(pg_result($resaco,$conresaco,'cc36_dataretorno'))."','$this->cc36_dataretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Suspensão de Nota de Liquidação não Alterado. Alteração Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->cc36_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Suspensão de Nota de Liquidação não foi Alterado. Alteração Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->cc36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->cc36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  public function excluir ($cc36_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($cc36_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,21966,'$cc36_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,3954,21966,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3954,21967,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_empnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3954,21968,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_justificativasuspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3954,21969,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_datasuspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3954,21970,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_justificativaretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3954,21971,'','".AddSlashes(pg_result($resaco,$iresaco,'cc36_dataretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from empnotasuspensao
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($cc36_sequencial)){
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " cc36_sequencial = $cc36_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Suspensão de Nota de Liquidação não Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$cc36_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Suspensão de Nota de Liquidação não Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$cc36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$cc36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao do recordset
  public function sql_record($sql) {

    $result = db_query($sql);
    if (!$result) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_num_rows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:empnotasuspensao";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql
  public function sql_query ($cc36_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from empnota ";
    $sql .= "      left  join empnotasuspensao on  empnota.e69_codnota    = empnotasuspensao.cc36_empnota";
    $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario = empnota.e69_id_usuario";
    $sql .= "      inner join empempenho       on  empempenho.e60_numemp  = empnota.e69_numemp";
    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($cc36_sequencial)) {
        $sql2 .= " where empnotasuspensao.cc36_sequencial = $cc36_sequencial ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

  // funcao do sql
  public function sql_query_file ($cc36_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from empnotasuspensao ";
    $sql2 = "";
    if (empty($dbwhere)) {

      if (!empty($cc36_sequencial)){
        $sql2 .= " where empnotasuspensao.cc36_sequencial = $cc36_sequencial ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }


  public function sql_query_relatorio_suspensao($sCampos = "*", $sWhere = null, $sOrder = null) {

    $sql  = " select {$sCampos} ";
    $sql .= "   from empnotasuspensao ";
    $sql .= "        inner join empnota    on empnota.e69_codnota    = empnotasuspensao.cc36_empnota ";
    $sql .= "        inner join empnotaele on empnotaele.e70_codnota = empnota.e69_codnota ";
    $sql .= "        inner join empempenho on empempenho.e60_numemp  = empnota.e69_numemp ";
    $sql .= "        inner join cgm        on cgm.z01_numcgm         = empempenho.e60_numcgm ";
    $sql .= "        inner join classificacaocredoresempenho on classificacaocredoresempenho.cc31_empempenho = empempenho.e60_numemp ";
    $sql .= "        inner join classificacaocredores on classificacaocredores.cc30_codigo = classificacaocredoresempenho.cc31_classificacaocredores";

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }
    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }
    return $sql;
  }

}
