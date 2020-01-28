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

//MODULO: Empenho
//CLASSE DA ENTIDADE empageconfche
class cl_empageconfche {
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
  var $e91_codcheque = 0;
  var $e91_codmov = 0;
  var $e91_cheque = null;
  var $e91_valor = 0;
  var $e91_ativo = 'f';
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 e91_codcheque = int8 = Código 
                 e91_codmov = int4 = Movimento 
                 e91_cheque = varchar(40) = Cheque 
                 e91_valor = float8 = Valor 
                 e91_ativo = bool = Ativo 
                 ";
  //funcao construtor da classe
  function cl_empageconfche() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empageconfche");
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
      $this->e91_codcheque = ($this->e91_codcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_codcheque"]:$this->e91_codcheque);
      $this->e91_codmov = ($this->e91_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_codmov"]:$this->e91_codmov);
      $this->e91_cheque = ($this->e91_cheque == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_cheque"]:$this->e91_cheque);
      $this->e91_valor = ($this->e91_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_valor"]:$this->e91_valor);
      $this->e91_ativo = ($this->e91_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["e91_ativo"]:$this->e91_ativo);
    }else{
      $this->e91_codcheque = ($this->e91_codcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_codcheque"]:$this->e91_codcheque);
    }
  }
  // funcao para inclusao
  function incluir ($e91_codcheque){
    $this->atualizacampos();
    if($this->e91_codmov == null ){
      $this->erro_sql = " Campo Movimento nao Informado.";
      $this->erro_campo = "e91_codmov";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_cheque == null ){
      $this->erro_sql = " Campo Cheque nao Informado.";
      $this->erro_campo = "e91_cheque";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_valor == null ){
      $this->erro_sql = " Campo Valor nao Informado.";
      $this->erro_campo = "e91_valor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_ativo == null ){
      $this->erro_sql = " Campo Ativo nao Informado.";
      $this->erro_campo = "e91_ativo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($e91_codcheque == "" || $e91_codcheque == null ){
      $result = db_query("select nextval('empageconfche_e91_codcheque_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: empageconfche_e91_codcheque_seq do campo: e91_codcheque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->e91_codcheque = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from empageconfche_e91_codcheque_seq");
      if(($result != false) && (pg_result($result,0,0) < $e91_codcheque)){
        $this->erro_sql = " Campo e91_codcheque maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->e91_codcheque = $e91_codcheque;
      }
    }
    if(($this->e91_codcheque == null) || ($this->e91_codcheque == "") ){
      $this->erro_sql = " Campo e91_codcheque nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empageconfche(
                                       e91_codcheque 
                                      ,e91_codmov 
                                      ,e91_cheque 
                                      ,e91_valor 
                                      ,e91_ativo 
                       )
                values (
                                $this->e91_codcheque 
                               ,$this->e91_codmov 
                               ,'$this->e91_cheque' 
                               ,$this->e91_valor 
                               ,'$this->e91_ativo' 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Cheques e seus valores ($this->e91_codcheque) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Cheques e seus valores já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Cheques e seus valores ($this->e91_codcheque) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->e91_codcheque;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->e91_codcheque));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,6307,'$this->e91_codcheque','I')");
      $resac = db_query("insert into db_acount values($acount,1027,6307,'','".AddSlashes(pg_result($resaco,0,'e91_codcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1027,6304,'','".AddSlashes(pg_result($resaco,0,'e91_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1027,6305,'','".AddSlashes(pg_result($resaco,0,'e91_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1027,6306,'','".AddSlashes(pg_result($resaco,0,'e91_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1027,15191,'','".AddSlashes(pg_result($resaco,0,'e91_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($e91_codcheque=null) {
    $this->atualizacampos();
    $sql = " update empageconfche set ";
    $virgula = "";
    if(trim($this->e91_codcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_codcheque"])){
      $sql  .= $virgula." e91_codcheque = $this->e91_codcheque ";
      $virgula = ",";
      if(trim($this->e91_codcheque) == null ){
        $this->erro_sql = " Campo Código nao Informado.";
        $this->erro_campo = "e91_codcheque";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_codmov"])){
      $sql  .= $virgula." e91_codmov = $this->e91_codmov ";
      $virgula = ",";
      if(trim($this->e91_codmov) == null ){
        $this->erro_sql = " Campo Movimento nao Informado.";
        $this->erro_campo = "e91_codmov";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_cheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_cheque"])){
      $sql  .= $virgula." e91_cheque = '$this->e91_cheque' ";
      $virgula = ",";
      if(trim($this->e91_cheque) == null ){
        $this->erro_sql = " Campo Cheque nao Informado.";
        $this->erro_campo = "e91_cheque";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_valor"])){
      $sql  .= $virgula." e91_valor = $this->e91_valor ";
      $virgula = ",";
      if(trim($this->e91_valor) == null ){
        $this->erro_sql = " Campo Valor nao Informado.";
        $this->erro_campo = "e91_valor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_ativo"])){
      $sql  .= $virgula." e91_ativo = '$this->e91_ativo' ";
      $virgula = ",";
      if(trim($this->e91_ativo) == null ){
        $this->erro_sql = " Campo Ativo nao Informado.";
        $this->erro_campo = "e91_ativo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($e91_codcheque!=null){
      $sql .= " e91_codcheque = $this->e91_codcheque";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->e91_codcheque));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6307,'$this->e91_codcheque','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_codcheque"]) || $this->e91_codcheque != "")
          $resac = db_query("insert into db_acount values($acount,1027,6307,'".AddSlashes(pg_result($resaco,$conresaco,'e91_codcheque'))."','$this->e91_codcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_codmov"]) || $this->e91_codmov != "")
          $resac = db_query("insert into db_acount values($acount,1027,6304,'".AddSlashes(pg_result($resaco,$conresaco,'e91_codmov'))."','$this->e91_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_cheque"]) || $this->e91_cheque != "")
          $resac = db_query("insert into db_acount values($acount,1027,6305,'".AddSlashes(pg_result($resaco,$conresaco,'e91_cheque'))."','$this->e91_cheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_valor"]) || $this->e91_valor != "")
          $resac = db_query("insert into db_acount values($acount,1027,6306,'".AddSlashes(pg_result($resaco,$conresaco,'e91_valor'))."','$this->e91_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_ativo"]) || $this->e91_ativo != "")
          $resac = db_query("insert into db_acount values($acount,1027,15191,'".AddSlashes(pg_result($resaco,$conresaco,'e91_ativo'))."','$this->e91_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Cheques e seus valores nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->e91_codcheque;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Cheques e seus valores nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->e91_codcheque;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->e91_codcheque;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($e91_codcheque=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($e91_codcheque));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6307,'$e91_codcheque','E')");
        $resac = db_query("insert into db_acount values($acount,1027,6307,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_codcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1027,6304,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1027,6305,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_cheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1027,6306,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1027,15191,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from empageconfche
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($e91_codcheque != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e91_codcheque = $e91_codcheque ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Cheques e seus valores nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$e91_codcheque;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Cheques e seus valores nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$e91_codcheque;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$e91_codcheque;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset
  function sql_record($sql) {
    $result = db_query($sql);
    if($result==false){
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if($this->numrows==0){
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:empageconfche";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $e91_codcheque=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfche ";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfche.e91_codmov";
    $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_codcheque!=null ){
        $sql2 .= " where empageconfche.e91_codcheque = $e91_codcheque ";
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
  // funcao do sql
  function sql_query_file ( $e91_codcheque=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfche ";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_codcheque!=null ){
        $sql2 .= " where empageconfche.e91_codcheque = $e91_codcheque ";
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
  function sql_query_cheques ( $e91_codcheque=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfche ";
    $sql .= "      inner join empageconf  on  empageconf.e86_codmov = empageconfche.e91_codmov";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfche.e91_codmov";
    $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
    $sql .= "      left  join empord  on  empord.e82_codmov = empagemov.e81_codmov";
    $sql .= "      left  join pagordemele on pagordemele.e53_codord = empord.e82_codord ";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm ";
    $sql .= "      inner join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov ";
    $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo ";
    $sql .= "      left  join corconf on corconf.k12_codmov = empageconfche.e91_codcheque ";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_codcheque!=null ){
        $sql2 .= " where empageconfche.e91_codcheque = $e91_codcheque ";
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
  function sql_query_cheques_cancelados ( $e91_codcheque=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfche ";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfche.e91_codmov ";
    $sql .= "      inner join empageconf on  empageconf.e86_codmov = empagemov.e81_codmov ";
    $sql .= "      inner join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov ";
    $sql .= "      left  join empageconfchecanc  on  empageconfchecanc.e93_codcheque = empageconfche.e91_codcheque ";
    $sql .= "      left  join corconf    on  corconf.k12_codmov = empageconfche.e91_codcheque ";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_codcheque!=null ){
        $sql2 .= " where empageconfche.e91_codcheque = $e91_codcheque ";
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
  function sql_query_pagamento ( $e91_codcheque=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empageconfche ";
    $sql .= "      inner join empageconf  on  empageconf.e86_codmov = empageconfche.e91_codmov";
    $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empageconfche.e91_codmov";
    $sql .= "      left  join empord  on  empord.e82_codmov = empagemov.e81_codmov";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_codcheque!=null ){
        $sql2 .= " where empageconfche.e91_codcheque = $e91_codcheque ";
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

  public function sql_query_cheque_slip($sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from empageconfche ";
    $sSql .= "        inner join empageslip on empageslip.e89_codmov = empageconfche.e91_codmov ";
    $sSql .= "        inner join slip       on slip.k17_codigo = empageslip.e89_codigo ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }
    return $sSql;
  }
}
?>