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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoposicao
class cl_acordoposicao {
  // cria variaveis de erro
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status= null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $ac26_sequencial = 0;
  var $ac26_acordo = 0;
  var $ac26_acordoposicaotipo = 0;
  var $ac26_numero = 0;
  var $ac26_situacao = 0;
  var $ac26_data_dia = null;
  var $ac26_data_mes = null;
  var $ac26_data_ano = null;
  var $ac26_data = null;
  var $ac26_emergencial = 'f';
  var $ac26_observacao = null;
  var $ac26_numeroaditamento = null;
  var $ac26_tipooperacao = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 ac26_sequencial = int4 = Código Sequencial
                 ac26_acordo = int4 = Acordo
                 ac26_acordoposicaotipo = int4 = Tipo da Posição
                 ac26_numero = int4 = Número da Posição
                 ac26_situacao = int4 = Situacao
                 ac26_data = date = Data
                 ac26_emergencial = bool = Posição Emergencial
                 ac26_observacao = text = Observação
                 ac26_numeroaditamento = varchar(20) = Número do aditamento
                 ac26_tipooperacao = int4 = Tipo de Operação
                 ";
  //funcao construtor da classe
  function cl_acordoposicao() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("acordoposicao");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro
  function erro($mostra,$retorna) {
    if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if($retorna==true){
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }
  // funcao para atualizar campos
  function atualizacampos($exclusao=false) {
    if($exclusao==false){
      $this->ac26_sequencial = ($this->ac26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_sequencial"]:$this->ac26_sequencial);
      $this->ac26_acordo = ($this->ac26_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_acordo"]:$this->ac26_acordo);
      $this->ac26_acordoposicaotipo = ($this->ac26_acordoposicaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_acordoposicaotipo"]:$this->ac26_acordoposicaotipo);
      $this->ac26_numero = ($this->ac26_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_numero"]:$this->ac26_numero);
      $this->ac26_situacao = ($this->ac26_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_situacao"]:$this->ac26_situacao);
      if($this->ac26_data == ""){
        $this->ac26_data_dia = ($this->ac26_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_data_dia"]:$this->ac26_data_dia);
        $this->ac26_data_mes = ($this->ac26_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_data_mes"]:$this->ac26_data_mes);
        $this->ac26_data_ano = ($this->ac26_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_data_ano"]:$this->ac26_data_ano);
        if($this->ac26_data_dia != ""){
          $this->ac26_data = $this->ac26_data_ano."-".$this->ac26_data_mes."-".$this->ac26_data_dia;
        }
      }
      $this->ac26_emergencial = ($this->ac26_emergencial == "f"?@$GLOBALS["HTTP_POST_VARS"]["ac26_emergencial"]:$this->ac26_emergencial);
      $this->ac26_observacao = ($this->ac26_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_observacao"]:$this->ac26_observacao);
      $this->ac26_numeroaditamento = ($this->ac26_numeroaditamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_numeroaditamento"]:$this->ac26_numeroaditamento);
      $this->ac26_tipooperacao = ($this->ac26_tipooperacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_tipooperacao"]:$this->ac26_tipooperacao);
    }else{
      $this->ac26_sequencial = ($this->ac26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac26_sequencial"]:$this->ac26_sequencial);
    }
  }
  // funcao para Inclusão
  function incluir ($ac26_sequencial){
    $this->atualizacampos();
    if($this->ac26_acordo == null ){
      $this->erro_sql = " Campo Acordo não informado.";
      $this->erro_campo = "ac26_acordo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ac26_acordoposicaotipo == null ){
      $this->erro_sql = " Campo Tipo da Posição não informado.";
      $this->erro_campo = "ac26_acordoposicaotipo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ac26_numero == null ){
      $this->erro_sql = " Campo Número da Posição não informado.";
      $this->erro_campo = "ac26_numero";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ac26_situacao == null ){
      $this->erro_sql = " Campo Situacao não informado.";
      $this->erro_campo = "ac26_situacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ac26_data == null ){
      $this->erro_sql = " Campo Data não informado.";
      $this->erro_campo = "ac26_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ac26_emergencial == null ){
      $this->ac26_emergencial = "false";
    }
    if($ac26_sequencial == "" || $ac26_sequencial == null ){
      $result = db_query("select nextval('acordoposicao_ac26_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: acordoposicao_ac26_sequencial_seq do campo: ac26_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->ac26_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from acordoposicao_ac26_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $ac26_sequencial)){
        $this->erro_sql = " Campo ac26_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->ac26_sequencial = $ac26_sequencial;
      }
    }
    if(($this->ac26_sequencial == null) || ($this->ac26_sequencial == "") ){
      $this->erro_sql = " Campo ac26_sequencial não declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into acordoposicao(
                                       ac26_sequencial
                                      ,ac26_acordo
                                      ,ac26_acordoposicaotipo
                                      ,ac26_numero
                                      ,ac26_situacao
                                      ,ac26_data
                                      ,ac26_emergencial
                                      ,ac26_observacao
                                      ,ac26_numeroaditamento
                                      ,ac26_tipooperacao
                       )
                values (
                                $this->ac26_sequencial
                               ,$this->ac26_acordo
                               ,$this->ac26_acordoposicaotipo
                               ,$this->ac26_numero
                               ,$this->ac26_situacao
                               ,".($this->ac26_data == "null" || $this->ac26_data == ""?"null":"'".$this->ac26_data."'")."
                               ,'$this->ac26_emergencial'
                               ,'$this->ac26_observacao'
                               ,'$this->ac26_numeroaditamento'
                               ,$this->ac26_tipooperacao
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "posicoes do acordo ($this->ac26_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "posicoes do acordo já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "posicoes do acordo ($this->ac26_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->ac26_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->ac26_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,16665,'$this->ac26_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,2930,16665,'','".AddSlashes(pg_result($resaco,0,'ac26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16666,'','".AddSlashes(pg_result($resaco,0,'ac26_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16668,'','".AddSlashes(pg_result($resaco,0,'ac26_acordoposicaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16667,'','".AddSlashes(pg_result($resaco,0,'ac26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16669,'','".AddSlashes(pg_result($resaco,0,'ac26_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16730,'','".AddSlashes(pg_result($resaco,0,'ac26_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,16731,'','".AddSlashes(pg_result($resaco,0,'ac26_emergencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,18486,'','".AddSlashes(pg_result($resaco,0,'ac26_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,20232,'','".AddSlashes(pg_result($resaco,0,'ac26_numeroaditamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2930,21843,'','".AddSlashes(pg_result($resaco,0,'ac26_tipooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  public function alterar ($ac26_sequencial=null) {
    $this->atualizacampos();
    $sql = " update acordoposicao set ";
    $virgula = "";
    if(trim($this->ac26_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_sequencial"])){
      $sql  .= $virgula." ac26_sequencial = $this->ac26_sequencial ";
      $virgula = ",";
      if(trim($this->ac26_sequencial) == null ){
        $this->erro_sql = " Campo Código Sequencial não informado.";
        $this->erro_campo = "ac26_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ac26_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_acordo"])){
      $sql  .= $virgula." ac26_acordo = $this->ac26_acordo ";
      $virgula = ",";
      if(trim($this->ac26_acordo) == null ){
        $this->erro_sql = " Campo Acordo não informado.";
        $this->erro_campo = "ac26_acordo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ac26_acordoposicaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_acordoposicaotipo"])){
      $sql  .= $virgula." ac26_acordoposicaotipo = $this->ac26_acordoposicaotipo ";
      $virgula = ",";
      if(trim($this->ac26_acordoposicaotipo) == null ){
        $this->erro_sql = " Campo Tipo da Posição não informado.";
        $this->erro_campo = "ac26_acordoposicaotipo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ac26_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_numero"])){
      $sql  .= $virgula." ac26_numero = $this->ac26_numero ";
      $virgula = ",";
      if(trim($this->ac26_numero) == null ){
        $this->erro_sql = " Campo Número da Posição não informado.";
        $this->erro_campo = "ac26_numero";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ac26_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_situacao"])){
      $sql  .= $virgula." ac26_situacao = $this->ac26_situacao ";
      $virgula = ",";
      if(trim($this->ac26_situacao) == null ){
        $this->erro_sql = " Campo Situacao não informado.";
        $this->erro_campo = "ac26_situacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ac26_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac26_data_dia"] !="") ){
      $sql  .= $virgula." ac26_data = '$this->ac26_data' ";
      $virgula = ",";
      if(trim($this->ac26_data) == null ){
        $this->erro_sql = " Campo Data não informado.";
        $this->erro_campo = "ac26_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["ac26_data_dia"])){
        $sql  .= $virgula." ac26_data = null ";
        $virgula = ",";
        if(trim($this->ac26_data) == null ){
          $this->erro_sql = " Campo Data não informado.";
          $this->erro_campo = "ac26_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->ac26_emergencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_emergencial"])){
      $sql  .= $virgula." ac26_emergencial = '$this->ac26_emergencial' ";
      $virgula = ",";
    }
    if(trim($this->ac26_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_observacao"])){
      $sql  .= $virgula." ac26_observacao = '$this->ac26_observacao' ";
      $virgula = ",";
    }
    if(trim($this->ac26_numeroaditamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_numeroaditamento"])){
      $sql  .= $virgula." ac26_numeroaditamento = '$this->ac26_numeroaditamento' ";
      $virgula = ",";
    }
    if(trim($this->ac26_tipooperacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac26_tipooperacao"])){
      if(trim($this->ac26_tipooperacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac26_tipooperacao"])){
        $this->ac26_tipooperacao = "0" ;
      }
      $sql  .= $virgula." ac26_tipooperacao = $this->ac26_tipooperacao ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($ac26_sequencial!=null){
      $sql .= " ac26_sequencial = $this->ac26_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->ac26_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,16665,'$this->ac26_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_sequencial"]) || $this->ac26_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,2930,16665,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_sequencial'))."','$this->ac26_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_acordo"]) || $this->ac26_acordo != "")
            $resac = db_query("insert into db_acount values($acount,2930,16666,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_acordo'))."','$this->ac26_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_acordoposicaotipo"]) || $this->ac26_acordoposicaotipo != "")
            $resac = db_query("insert into db_acount values($acount,2930,16668,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_acordoposicaotipo'))."','$this->ac26_acordoposicaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_numero"]) || $this->ac26_numero != "")
            $resac = db_query("insert into db_acount values($acount,2930,16667,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_numero'))."','$this->ac26_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_situacao"]) || $this->ac26_situacao != "")
            $resac = db_query("insert into db_acount values($acount,2930,16669,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_situacao'))."','$this->ac26_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_data"]) || $this->ac26_data != "")
            $resac = db_query("insert into db_acount values($acount,2930,16730,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_data'))."','$this->ac26_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_emergencial"]) || $this->ac26_emergencial != "")
            $resac = db_query("insert into db_acount values($acount,2930,16731,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_emergencial'))."','$this->ac26_emergencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_observacao"]) || $this->ac26_observacao != "")
            $resac = db_query("insert into db_acount values($acount,2930,18486,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_observacao'))."','$this->ac26_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_numeroaditamento"]) || $this->ac26_numeroaditamento != "")
            $resac = db_query("insert into db_acount values($acount,2930,20232,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_numeroaditamento'))."','$this->ac26_numeroaditamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["ac26_tipooperacao"]) || $this->ac26_tipooperacao != "")
            $resac = db_query("insert into db_acount values($acount,2930,21843,'".AddSlashes(pg_result($resaco,$conresaco,'ac26_tipooperacao'))."','$this->ac26_tipooperacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "posicoes do acordo não Alterado. Alteração Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ac26_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "posicoes do acordo não foi Alterado. Alteração Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ac26_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ac26_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  public function excluir ($ac26_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($ac26_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,16665,'$ac26_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,2930,16665,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16666,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16668,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_acordoposicaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16667,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16669,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16730,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,16731,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_emergencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,18486,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,20232,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_numeroaditamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2930,21843,'','".AddSlashes(pg_result($resaco,$iresaco,'ac26_tipooperacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from acordoposicao
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ac26_sequencial)){
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " ac26_sequencial = $ac26_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "posicoes do acordo não Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$ac26_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "posicoes do acordo não Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$ac26_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$ac26_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:acordoposicao";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  public function sql_query ($ac26_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from acordoposicao ";
    $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
    $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
    $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
    $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
    $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    $sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
    $sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ac26_sequencial)) {
        $sql2 .= " where acordoposicao.ac26_sequencial = $ac26_sequencial ";
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
  public function sql_query_file ($ac26_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from acordoposicao ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ac26_sequencial)){
        $sql2 .= " where acordoposicao.ac26_sequencial = $ac26_sequencial ";
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

  function sql_query_vigencia ( $ac26_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from acordoposicao ";
    $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
    $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
    $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
    $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
    $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    $sql .= "      left  join acordovigencia  on  ac26_sequencial                = ac18_acordoposicao";
    $sql2 = "";
    if($dbwhere==""){
      if($ac26_sequencial!=null ){
        $sql2 .= " where acordoposicao.ac26_sequencial = $ac26_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * Metodo para buscar periodos com execucao
   */
  public function sql_query_periodo_execucao ( $ac26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select $campos ";
    $sql .= " from acordoposicao ";
    $sql .= "      inner join acordoitem             on acordoitem.ac20_acordoposicao = acordoposicao.ac26_sequencial ";
    $sql .= "      inner join acordoitemprevisao aip on aip.ac37_acordoitem           = acordoitem.ac20_sequencial ";

    $sql2 = "";

    if($dbwhere==""){

      if($ac26_sequencial!=null ){
        $sql2 .= " where acordoposicao.ac26_sequencial = $ac26_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * Retorna os dados dos aditamentos para o portal da transparencia
   *
   * @param  string $sCampos
   * @param  string $sOrdem
   * @param  string $sWhere
   * @return string
   */
  public function sql_query_transparencia($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = "select {$sCampos} \n";
    $sSql .= "  from acordoposicao                                                           \n";
    $sSql .= "       inner join acordo on ac16_sequencial = ac26_acordo                      \n";
    $sSql .= "       left join acordoposicaotipo on ac27_sequencial = ac26_acordoposicaotipo \n";
    $sSql .= "       left join acordosituacao on ac17_sequencial = ac26_situacao             \n";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} \n";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

  /**
   * retorna periodos que foram reativados
   * @param string $ac26_sequencial
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_queryReativados ( $ac26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from acordoposicao ";
    $sql .= "        inner join acordoposicaoperiodo     on acordoposicao.ac26_sequencial         = acordoposicaoperiodo.ac36_acordoposicao";
    $sql .= "        inner join acordoparalisacaoperiodo on acordoposicaoperiodo.ac36_sequencial  = acordoparalisacaoperiodo.ac49_acordoposicaoperiodo";

    $sql2 = "";

    if($dbwhere==""){
      if($ac26_sequencial!=null ){
        $sql2 .= " where acordoposicao.ac26_sequencial = $ac26_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  public function sql_query_posicoes_licitacon($sCampos = "*", $sWhere = null) {

    $sSql  = "  select {$sCampos} ";
    $sSql .= "    from acordoposicao ";
    $sSql .= "         left  join acordoitem          on acordoitem.ac20_acordoposicao = acordoposicao.ac26_sequencial ";
    $sSql .= "         left  join acordoposicaoevento on acordoposicaoevento.ac56_acordoposicao = acordoposicao.ac26_sequencial ";
    $sSql .= "         left  join acordoevento        on acordoevento.ac55_sequencial = acordoposicaoevento.ac56_acordoevento ";
    $sSql .= "";
    $sSql .= "";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }

}
