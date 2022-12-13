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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacao
class cl_declaracaoquitacao {
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
  var $ar30_sequencial = 0;
  var $ar30_exercicio = 0;
  var $ar30_situacao = 0;
  var $ar30_data_dia = null;
  var $ar30_data_mes = null;
  var $ar30_data_ano = null;
  var $ar30_data = null;
  var $ar30_id_usuario = 0;
  var $ar30_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 ar30_sequencial = int8 = Código Declaração 
                 ar30_exercicio = int4 = Ano 
                 ar30_situacao = int4 = Situação Quitação 
                 ar30_data = date = Data Quitação 
                 ar30_id_usuario = int4 = Cod. Usuário 
                 ar30_instit = int4 = Cod. Instituição 
                 ";
  //funcao construtor da classe
  function cl_declaracaoquitacao() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("declaracaoquitacao");
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
      $this->ar30_sequencial = ($this->ar30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_sequencial"]:$this->ar30_sequencial);
      $this->ar30_exercicio = ($this->ar30_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_exercicio"]:$this->ar30_exercicio);
      $this->ar30_situacao = ($this->ar30_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_situacao"]:$this->ar30_situacao);
      if($this->ar30_data == ""){
        $this->ar30_data_dia = ($this->ar30_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_data_dia"]:$this->ar30_data_dia);
        $this->ar30_data_mes = ($this->ar30_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_data_mes"]:$this->ar30_data_mes);
        $this->ar30_data_ano = ($this->ar30_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_data_ano"]:$this->ar30_data_ano);
        if($this->ar30_data_dia != ""){
          $this->ar30_data = $this->ar30_data_ano."-".$this->ar30_data_mes."-".$this->ar30_data_dia;
        }
      }
      $this->ar30_id_usuario = ($this->ar30_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_id_usuario"]:$this->ar30_id_usuario);
      $this->ar30_instit = ($this->ar30_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_instit"]:$this->ar30_instit);
    }else{
      $this->ar30_sequencial = ($this->ar30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar30_sequencial"]:$this->ar30_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($ar30_sequencial){
    $this->atualizacampos();
    if($this->ar30_exercicio == null ){
      $this->erro_sql = " Campo Ano nao Informado.";
      $this->erro_campo = "ar30_exercicio";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ar30_situacao == null ){
      $this->erro_sql = " Campo Situação Quitação nao Informado.";
      $this->erro_campo = "ar30_situacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ar30_data == null ){
      $this->erro_sql = " Campo Data Quitação nao Informado.";
      $this->erro_campo = "ar30_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ar30_id_usuario == null ){
      $this->erro_sql = " Campo Cod. Usuário nao Informado.";
      $this->erro_campo = "ar30_id_usuario";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ar30_instit == null ){
      $this->erro_sql = " Campo Cod. Instituição nao Informado.";
      $this->erro_campo = "ar30_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($ar30_sequencial == "" || $ar30_sequencial == null ){
      $result = db_query("select nextval('declaracaoquitacao_ar30_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacao_ar30_sequencial_seq do campo: ar30_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->ar30_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from declaracaoquitacao_ar30_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $ar30_sequencial)){
        $this->erro_sql = " Campo ar30_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->ar30_sequencial = $ar30_sequencial;
      }
    }
    if(($this->ar30_sequencial == null) || ($this->ar30_sequencial == "") ){
      $this->erro_sql = " Campo ar30_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into declaracaoquitacao(
                                       ar30_sequencial 
                                      ,ar30_exercicio 
                                      ,ar30_situacao 
                                      ,ar30_data 
                                      ,ar30_id_usuario 
                                      ,ar30_instit 
                       )
                values (
                $this->ar30_sequencial
                               ,$this->ar30_exercicio 
                               ,$this->ar30_situacao 
                               ,".($this->ar30_data == "null" || $this->ar30_data == ""?"null":"'".$this->ar30_data."'")." 
                               ,$this->ar30_id_usuario 
                               ,$this->ar30_instit 
                      )";
                $result = db_query($sql);
                if($result==false){
                  $this->erro_banco = str_replace("\n","",@pg_last_error());
                  if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                    $this->erro_sql   = "Declaração de Quitação ($this->ar30_sequencial) nao Incluído. Inclusao Abortada.";
                    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                    $this->erro_banco = "Declaração de Quitação já Cadastrado";
                    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                  }else{
                    $this->erro_sql   = "Declaração de Quitação ($this->ar30_sequencial) nao Incluído. Inclusao Abortada.";
                    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                  }
                  $this->erro_status = "0";
                  $this->numrows_incluir= 0;
                  return false;
                }
                $this->erro_banco = "";
                $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$this->ar30_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_incluir= pg_affected_rows($result);
                $resaco = $this->sql_record($this->sql_query_file($this->ar30_sequencial));
                if(($resaco!=false)||($this->numrows!=0)){
                  $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                  $acount = pg_result($resac,0,0);
                  $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                  $resac = db_query("insert into db_acountkey values($acount,17153,'$this->ar30_sequencial','I')");
                  $resac = db_query("insert into db_acount values($acount,3031,17153,'','".AddSlashes(pg_result($resaco,0,'ar30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                  $resac = db_query("insert into db_acount values($acount,3031,17154,'','".AddSlashes(pg_result($resaco,0,'ar30_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                  $resac = db_query("insert into db_acount values($acount,3031,17155,'','".AddSlashes(pg_result($resaco,0,'ar30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                  $resac = db_query("insert into db_acount values($acount,3031,17156,'','".AddSlashes(pg_result($resaco,0,'ar30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                  $resac = db_query("insert into db_acount values($acount,3031,17157,'','".AddSlashes(pg_result($resaco,0,'ar30_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                  $resac = db_query("insert into db_acount values($acount,3031,17158,'','".AddSlashes(pg_result($resaco,0,'ar30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
                return true;
  }
  // funcao para alteracao
  function alterar ($ar30_sequencial=null) {
    $this->atualizacampos();
    $sql = " update declaracaoquitacao set ";
    $virgula = "";
    if(trim($this->ar30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_sequencial"])){
      $sql  .= $virgula." ar30_sequencial = $this->ar30_sequencial ";
      $virgula = ",";
      if(trim($this->ar30_sequencial) == null ){
        $this->erro_sql = " Campo Código Declaração nao Informado.";
        $this->erro_campo = "ar30_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ar30_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_exercicio"])){
      $sql  .= $virgula." ar30_exercicio = $this->ar30_exercicio ";
      $virgula = ",";
      if(trim($this->ar30_exercicio) == null ){
        $this->erro_sql = " Campo Ano nao Informado.";
        $this->erro_campo = "ar30_exercicio";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ar30_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_situacao"])){
      $sql  .= $virgula." ar30_situacao = $this->ar30_situacao ";
      $virgula = ",";
      if(trim($this->ar30_situacao) == null ){
        $this->erro_sql = " Campo Situação Quitação nao Informado.";
        $this->erro_campo = "ar30_situacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ar30_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar30_data_dia"] !="") ){
      $sql  .= $virgula." ar30_data = '$this->ar30_data' ";
      $virgula = ",";
      if(trim($this->ar30_data) == null ){
        $this->erro_sql = " Campo Data Quitação nao Informado.";
        $this->erro_campo = "ar30_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_data_dia"])){
        $sql  .= $virgula." ar30_data = null ";
        $virgula = ",";
        if(trim($this->ar30_data) == null ){
          $this->erro_sql = " Campo Data Quitação nao Informado.";
          $this->erro_campo = "ar30_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->ar30_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_id_usuario"])){
      $sql  .= $virgula." ar30_id_usuario = $this->ar30_id_usuario ";
      $virgula = ",";
      if(trim($this->ar30_id_usuario) == null ){
        $this->erro_sql = " Campo Cod. Usuário nao Informado.";
        $this->erro_campo = "ar30_id_usuario";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ar30_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar30_instit"])){
      $sql  .= $virgula." ar30_instit = $this->ar30_instit ";
      $virgula = ",";
      if(trim($this->ar30_instit) == null ){
        $this->erro_sql = " Campo Cod. Instituição nao Informado.";
        $this->erro_campo = "ar30_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($ar30_sequencial!=null){
      $sql .= " ar30_sequencial = $this->ar30_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->ar30_sequencial));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,17153,'$this->ar30_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_sequencial"]) || $this->ar30_sequencial != "")
        $resac = db_query("insert into db_acount values($acount,3031,17153,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_sequencial'))."','$this->ar30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_exercicio"]) || $this->ar30_exercicio != "")
        $resac = db_query("insert into db_acount values($acount,3031,17154,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_exercicio'))."','$this->ar30_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_situacao"]) || $this->ar30_situacao != "")
        $resac = db_query("insert into db_acount values($acount,3031,17155,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_situacao'))."','$this->ar30_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_data"]) || $this->ar30_data != "")
        $resac = db_query("insert into db_acount values($acount,3031,17156,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_data'))."','$this->ar30_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_id_usuario"]) || $this->ar30_id_usuario != "")
        $resac = db_query("insert into db_acount values($acount,3031,17157,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_id_usuario'))."','$this->ar30_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ar30_instit"]) || $this->ar30_instit != "")
        $resac = db_query("insert into db_acount values($acount,3031,17158,'".AddSlashes(pg_result($resaco,$conresaco,'ar30_instit'))."','$this->ar30_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Declaração de Quitação nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ar30_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Declaração de Quitação nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ar30_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ar30_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($ar30_sequencial=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($ar30_sequencial));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,17153,'$ar30_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,3031,17153,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3031,17154,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3031,17155,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3031,17156,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3031,17157,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3031,17158,'','".AddSlashes(pg_result($resaco,$iresaco,'ar30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from declaracaoquitacao
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($ar30_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " ar30_sequencial = $ar30_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Declaração de Quitação nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$ar30_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Declaração de Quitação nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$ar30_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$ar30_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacao";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $ar30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from declaracaoquitacao ";
    $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql2 = "";
    if($dbwhere==""){
      if($ar30_sequencial!=null ){
        $sql2 .= " where declaracaoquitacao.ar30_sequencial = $ar30_sequencial ";
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
  function sql_query_file ( $ar30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from declaracaoquitacao ";
    $sql2 = "";
    if($dbwhere==""){
      if($ar30_sequencial!=null ){
        $sql2 .= " where declaracaoquitacao.ar30_sequencial = $ar30_sequencial ";
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
   * Consulta registros do exercicio e origem informados
   * @param integer $iExercicio Exercicio informado para a declaração de quitação
   * @param integer $iOrigem 1 Cgm, 2 Matricula, 3 Inscrição
   * @param boolean $bSomenteCGM True para somente cgm e false cgm geral
   */
  public function sql_query_debitos_arrecad($iExercicio, $iOrigem, $iCodOrigem, $bSomenteCGM = false) {
    
    $sTabela = "";
    $sCampo  = "";
    
    $sLeftJoinArrecant   = "";
    $sLeftJoinArrepaga   = "";
    $sLeftJoinArreprescr = "";
    $sWhereLeftJoin      = "";

    if($iOrigem == 1) {
      //cgm
      $sTabela = 'arrenumcgm';
      $sCampo  = 'k00_numcgm';

    }elseif($iOrigem == 2) {
      //matric
      $sTabela = 'arrematric';
      $sCampo  = 'k00_matric';
    }elseif($iOrigem == 3) {
      //inscr
      $sTabela = 'arreinscr';
      $sCampo  = 'k00_inscr';
    }

    if($bSomenteCGM == true) {
      $sLeftJoinArrepaga = "
        left join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre 
        left join arreinscr  on arreinscr.k00_numpre  = arrepaga.k00_numpre ";

      $sLeftJoinArrecant = "
        left join arrematric on arrematric.k00_numpre = arrecant.k00_numpre
        left join arreinscr  on arreinscr.k00_numpre  = arrecant.k00_numpre ";

      $sLeftJoinArreprescr = "
        left join arrematric on arrematric.k00_numpre = arreprescr.k30_numpre 
        left join arreinscr  on arreinscr.k00_numpre  = arreprescr.k30_numpre ";

      $sWhereLeftJoin = "
        and arrematric.k00_matric is null
        and arreinscr.k00_inscr is null ";
    }


    $sSql =
    "
    select distinct exerc, numpre, numpar, receita
     from ( 
             select extract( year from arrepaga.k00_dtvenc ) as exerc, arrepaga.k00_numpre as numpre, arrepaga.k00_numpar as numpar, arrepaga.k00_receit as receita
              from arrepaga 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arrepaga.k00_numpre
                   {$sLeftJoinArrepaga}
                   inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} and extract( year from arrepaga.k00_dtvenc ) = {$iExercicio}
             {$sWhereLeftJoin}

             union all
  
            select extract( year from arrecant.k00_dtvenc ) as exerc, cancdebitosreg.k21_numpre as numpre, cancdebitosreg.k21_numpar as numpar, cancdebitosreg.k21_receit as receita
              from cancdebitosprocreg
                   inner join cancdebitosreg  on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg
                   inner join arrecant        on arrecant.k00_numpre   = cancdebitosreg.k21_numpre
                                             and arrecant.k00_numpar   = cancdebitosreg.k21_numpar
                   inner join {$sTabela}      on {$sTabela}.k00_numpre = arrecant.k00_numpre
                   {$sLeftJoinArrecant}
                   inner join arreinstit      on arreinstit.k00_numpre = arrecant.k00_numpre
                                             and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} and extract( year from arrecant.k00_dtvenc ) = {$iExercicio}
             {$sWhereLeftJoin}
               and not exists ( select 1 
                                  from arrepaga 
                                 where arrepaga.k00_numpre  = arrecant.k00_numpre
                                   and arrepaga.k00_numpar  = arrecant.k00_numpar )
   
           union all
   
            select extract( year from arreprescr.k30_dtvenc ) as exerc, arreprescr.k30_numpre as numpre, arreprescr.k30_numpar as numpar, arreprescr.k30_receit as receita
              from arreprescr 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreprescr.k30_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit on arreinstit.k00_numpre = arreprescr.k30_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} and extract( year from arreprescr.k30_dtvenc ) = {$iExercicio}
             {$sWhereLeftJoin}

           union all
            
            select extract( year from arreforo.k00_dtvenc ) as exerc,
                   arreforo.k00_numpre as numpre,
                   arreforo.k00_numpar as numpar,
                   arreforo.k00_receit as receita
              from arreforo 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreforo.k00_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit on arreinstit.k00_numpre = arreforo.k00_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} and extract( year from arreforo.k00_dtvenc ) = {$iExercicio}
             {$sWhereLeftJoin}
            
           union all
           
            select extract( year from arreold.k00_dtvenc ) as exerc,
                   arreold.k00_numpre as numpre,
                   arreold.k00_numpar as numpar,
                   arreold.k00_receit as receita
              from arreold 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreold.k00_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit  on arreinstit.k00_numpre = arreold.k00_numpre
                                         and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                   inner join termodiv    on termodiv.numpreant    = arreold.k00_numpre
                   inner join termo       on termo.v07_parcel      = termodiv.parcel
             where {$sTabela}.{$sCampo} = {$iCodOrigem} and extract( year from arreold.k00_dtvenc ) = {$iExercicio}
                   {$sWhereLeftJoin}  
             
           ) as x
     where exerc < extract( year from current_date )
     
       and not exists ( select 1 
                          from {$sTabela}
                               inner join arrecad    on arrecad.k00_numpre = {$sTabela}.k00_numpre
                                                    and extract( year from arrecad.k00_dtvenc ) = x.exerc
                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                                                    and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                         where {$sTabela}.{$sCampo} = {$iCodOrigem} 
  
                           and not exists ( select 1
                                              from arresusp  
                                                   inner join suspensaofinaliza on suspensaofinaliza.ar19_suspensao = arresusp.k00_suspensao
                                             where arresusp.k00_numpre = arrecad.k00_numpre
                                               and arresusp.k00_numpar = arrecad.k00_numpar
                                               and suspensaofinaliza.ar19_tipo = 2 
                                           limit 1 )
                         limit 1);
      ";
      return $sSql;
  }

  /**
   * retorna os exercicios que podera ser gerada a declaracao de quitacao
   * @param string $sTabela
   * @param string $sCampo
   * @param integer $iCodOrigem
   * @param integer $iInstituicao
   * @param boolean $bSomenteCGM
   */
  public function sql_query_exercicios($iOrigem, $iCodOrigem, $bSomenteCGM = false) {

    $sLeftJoinArrepaga   = "";
    $sLeftJoinArrecant   = "";
    $sLeftJoinArreprescr = "";
    $sWhereLeftJoin      = "";

    if($iOrigem == 1) {
      $sTabela = 'arrenumcgm';
      $sCampo  = 'k00_numcgm';
    } else if($iOrigem == 2) {
      $sTabela = 'arrematric';
      $sCampo  = 'k00_matric';
    } else if($iOrigem == 3) {
      $sTabela = 'arreinscr';
      $sCampo  = 'k00_inscr';
    }

    if($bSomenteCGM == true) {
      $sLeftJoinArrepaga = "
        left join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre 
        left join arreinscr  on arreinscr.k00_numpre  = arrepaga.k00_numpre ";

      $sLeftJoinArrecant = "
        left join arrematric on arrematric.k00_numpre = arrecant.k00_numpre
        left join arreinscr  on arreinscr.k00_numpre  = arrecant.k00_numpre ";

      $sLeftJoinArreprescr = "
      left join arrematric on arrematric.k00_numpre = arreprescr.k30_numpre 
      left join arreinscr  on arreinscr.k00_numpre  = arreprescr.k30_numpre ";

      $sWhereLeftJoin = "
        and arrematric.k00_matric is null
        and arreinscr.k00_inscr is null ";
    }

    $sSql =
    "
    select distinct exerc
     from ( 
             select extract( year from arrepaga.k00_dtvenc ) as exerc
              from arrepaga 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arrepaga.k00_numpre
                   {$sLeftJoinArrepaga}
                   inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} 
             {$sWhereLeftJoin}
   
           union all
  
            select extract( year from arrecant.k00_dtvenc ) as exerc
              from cancdebitosprocreg
                   inner join cancdebitosreg  on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg
                   inner join arrecant        on arrecant.k00_numpre   = k21_numpre
                                             and arrecant.k00_numpar   = k21_numpar
                   inner join {$sTabela}      on {$sTabela}.k00_numpre = arrecant.k00_numpre
                   {$sLeftJoinArrecant}
                   inner join arreinstit      on arreinstit.k00_numpre = arrecant.k00_numpre
                                             and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem}
             {$sWhereLeftJoin}
               and not exists ( select 1 
                                  from arrepaga 
                                 where arrepaga.k00_numpre  = arrecant.k00_numpre
                                   and arrepaga.k00_numpar  = arrecant.k00_numpar )
   
           union all
   
            select extract( year from arreprescr.k30_dtvenc ) as exerc
              from arreprescr 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreprescr.k30_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit on arreinstit.k00_numpre = arreprescr.k30_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} 
             {$sWhereLeftJoin}

           union all
   
            select extract( year from arreforo.k00_dtvenc ) as exerc
              from arreforo 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreforo.k00_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit on arreinstit.k00_numpre = arreforo.k00_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where {$sTabela}.{$sCampo} = {$iCodOrigem} 
             {$sWhereLeftJoin}
             
           union all
   
            select extract( year from arreold.k00_dtvenc ) as exerc
              from arreold 
                   inner join {$sTabela} on {$sTabela}.k00_numpre = arreold.k00_numpre
                   {$sLeftJoinArreprescr}
                   inner join arreinstit on arreinstit.k00_numpre = arreold.k00_numpre
                                        and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                   inner join termodiv   on termodiv.numpreant    = arreold.k00_numpre
                   inner join termo      on termo.v07_parcel      = termodiv.parcel
             where {$sTabela}.{$sCampo} = {$iCodOrigem} 
             {$sWhereLeftJoin}             
             
             
           ) as x
     where exerc < extract( year from current_date )
     
       and not exists ( select 1 
                          from {$sTabela}
                               inner join arrecad    on arrecad.k00_numpre = {$sTabela}.k00_numpre
                                                    and extract( year from arrecad.k00_dtvenc ) = x.exerc
                         inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                                                    and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                         where {$sTabela}.{$sCampo} = {$iCodOrigem} 
  
                           and not exists ( select 1
                                              from arresusp  
                                                   inner join suspensaofinaliza on suspensaofinaliza.ar19_suspensao = arresusp.k00_suspensao
                                             where arresusp.k00_numpre = arrecad.k00_numpre
                                               and arresusp.k00_numpar = arrecad.k00_numpar
                                               and suspensaofinaliza.ar19_tipo = 2 
                                           limit 1 )
                         limit 1);
  
  
  
    ";
    return $sSql;
  }

  /**
   * query que retorna os dados para geração de arquivo txt por cgm
   * @param integer $iExercicio
   * @param logico $bSomenteCGM
   */
  public function sql_query_txt_cgm($iExercicio, $bSomenteCGM = false) {

    if($bSomenteCGM) {
      $sSomenteCGM = 'declaracaoquitacaocgm.ar34_somentecgm is true';
    }else {
      $sSomenteCGM = 'declaracaoquitacaocgm.ar34_somentecgm is false';
    }

    $iInstit = db_getsession('DB_instit');
    $iAnoUsu = db_getsession('DB_anousu');
    
    $sSql = "
    select db_config.nomeinst                       as instituicao, 
           declaracaoquitacao.ar30_exercicio        as ano,  
           cast('CGM Geral' as varchar)             as origem,
           cgm.z01_ender||', '||cgm.z01_numero      as endereco,
           cgm.z01_numcgm                           as cod_origem,
           cgm.z01_nome                             as nome_origem,
           cgm.z01_cgccpf                           as cod_cpf_cnpj,
           cgm2.z01_nome                            as nome_assinatura,
           rhfuncao.rh37_descr                      as cargo,
           declaracaoquitacao.ar30_sequencial       as declaracao,
           cgm.z01_ender                            as logradouro,
           cgm.z01_numero                           as numero,
           cgm.z01_compl                            as complemento, 
           cgm.z01_bairro                           as bairro,
           ''                                       as rota,
           ''                                       as orientacao
           
      from declaracaoquitacao 
     inner join db_config                on db_config.codigo = declaracaoquitacao.ar30_instit
     inner join declaracaoquitacaocgm    on declaracaoquitacaocgm.ar34_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
                                        and {$sSomenteCGM}
     inner join cgm                      on cgm.z01_numcgm = declaracaoquitacaocgm.ar34_numcgm
     inner join numpref                  on numpref.k03_anousu   = {$iAnoUsu}
                                        and numpref.k03_instit   = declaracaoquitacao.ar30_instit
      left join cgm as cgm2              on cgm2.z01_numcgm      = numpref.k03_respcgm
      left join rhfuncao                 on rhfuncao.rh37_instit = declaracaoquitacao.ar30_instit
                                        and rhfuncao.rh37_funcao = numpref.k03_respcargo
                                        
     where ar30_exercicio = {$iExercicio}
       and ar30_instit    = {$iInstit}
       and ar30_situacao  = 1";

    return $sSql;

  }

  /**
   * query que retorna os dados para geração de arquivo txt por matricula
   * @param integer $iExercicio
   */
  public function sql_query_txt_matric($iExercicio, $dData = null) {
    
    $iInstit = db_getsession('DB_instit');
    $iAnoUsu = db_getsession('DB_anousu');
    
    $sWhere = '';
    if($dData != '') {
      $sWhere = " and declaracaoquitacao.ar30_data >= '$dData' ";
    }

    $sSql = " select x.instituicao,
           x.ano ,
                     x.cod_origem,
                     x.nome_origem,
                     x.cod_cpf_cnpj,
                     x.nome_assinatura,
                     x.cargo,
                     x.declaracao,
                     x.agua_matric,
                     x.rota,
                     x.orientacao,
                     cast('Matrícula' as varchar) as origem,
                     case when x.agua_matric is not null then x.endereco_agua        else substr(fc_iptuender,001,40)||', '||substr(fc_iptuender,042,10) end as endereco,
                     case when x.agua_matric is not null then x.agua_logradouro      else substr(fc_iptuender,001,40)                                    end as logradouro,
                     case when x.agua_matric is not null then x.agua_numero::varchar else substr(fc_iptuender,042,10)                                    end as numero, 
                     case when x.agua_matric is not null then x.agua_complemento     else substr(fc_iptuender,053,20)                                    end as complemento, 
                     case when x.agua_matric is not null then x.agua_bairro          else substr(fc_iptuender,074,40)                                    end as bairro,
                     x.agua_entrega,
                     agua_codrua
                from ( 
                      select db_config.nomeinst                       as instituicao, 
                             declaracaoquitacao.ar30_exercicio        as ano,
                             ruas.j14_nome||', '||aguabase.x01_numero as endereco_agua,
                             declaracaoquitacaomatric.ar33_matric     as cod_origem,
                             proprietario.z01_nome                    as nome_origem,
                             proprietario.z01_cgccpf                  as cod_cpf_cnpj,
                             cgm.z01_nome                             as nome_assinatura,
                             rhfuncao.rh37_descr                      as cargo,
                             declaracaoquitacao.ar30_sequencial       as declaracao,
                             aguabase.x01_entrega                     as agua_entrega,
                             fc_iptuender(declaracaoquitacaomatric.ar33_matric),
                             case when aguabasecorresp.x32_matric  is not null then aguabasecorresp.x32_matric  else aguabase.x01_matric        end as agua_matric,
                             case when ruas_agua.j14_nome          is not null then ruas_agua.j14_nome          else ruas.j14_nome              end as agua_logradouro,
                             case when aguacorresp.x02_numero      is not null then aguacorresp.x02_numero      else aguabase.x01_numero        end as agua_numero,
                             case when bairro_corresp.j13_descr    is not null then bairro_corresp.j13_descr    else bairro_aguabase.j13_descr  end as agua_bairro,
                             case when aguacorresp.x02_rota        is not null then aguacorresp.x02_rota        else aguabase.x01_rota          end as rota,
                             case when aguacorresp.x02_orientacao  is not null then aguacorresp.x02_orientacao  else aguabase.x01_orientacao    end as orientacao,
                             case when aguacorresp.x02_complemento is not null then aguacorresp.x02_complemento else aguaconstr.x11_complemento end as agua_complemento,
                             case when aguacorresp.x02_codrua      is not null then aguacorresp.x02_codrua      else aguabase.x01_codrua        end as agua_codrua
                        from declaracaoquitacao 
                             inner join declaracaoquitacaomatric   on declaracaoquitacaomatric.ar33_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
                             inner join db_config                  on db_config.codigo                                 = declaracaoquitacao.ar30_instit
                             inner join numpref                    on numpref.k03_instit                               = declaracaoquitacao.ar30_instit    
                                                                  and numpref.k03_anousu                               = {$iAnoUsu}
                             left join cgm                         on cgm.z01_numcgm                                   = numpref.k03_respcgm
                             left join rhfuncao                    on rhfuncao.rh37_instit                             = declaracaoquitacao.ar30_instit
                                                                  and rhfuncao.rh37_funcao                             = numpref.k03_respcargo
                             left join aguabase                    on aguabase.x01_matric                              = declaracaoquitacaomatric.ar33_matric
                             left join proprietario                on proprietario.j01_matric                          = aguabase.x01_matric
                             left join ruas                        on ruas.j14_codigo                                  = aguabase.x01_codrua
                             left join aguabasecorresp             on aguabasecorresp.x32_matric                       = aguabase.x01_matric
                             left join aguacorresp                 on aguacorresp.x02_codcorresp                       = aguabasecorresp.x32_codcorresp
                             left join ruas    as ruas_agua        on ruas_agua.j14_codigo                             = aguacorresp.x02_codrua
                             left join bairro  as bairro_corresp   on bairro_corresp.j13_codi                          = aguacorresp.x02_codbairro
                             left join bairro  as bairro_aguabase  on bairro_aguabase.j13_codi                         = aguabase.x01_codbairro
                             left join aguaconstr                  on aguaconstr.x11_matric                            = aguabase.x01_matric 
                                                                  and aguaconstr.x11_tipo                              = 'P'
                       where declaracaoquitacao.ar30_exercicio = {$iExercicio}
                         and declaracaoquitacao.ar30_instit    = {$iInstit}
                         and declaracaoquitacao.ar30_situacao  = 1
                             {$sWhere}) as x 
                       order by x.agua_entrega, agua_codrua, x.orientacao, numero, complemento, x.agua_matric";
    return $sSql;

  }

  /**
   * query que retorna os dados para geração de arquivo txt por inscricao
   * @param integer $iExercicio
   */
  public function sql_query_txt_inscr($iExercicio) {
    
    $iInstit = db_getsession('DB_instit');
    $iAnoUsu = db_getsession('DB_anousu');

    $sSql = "
    select db_config.nomeinst                       as instituicao, 
           declaracaoquitacao.ar30_exercicio        as ano,  
           cast('Inscrição' as varchar)             as origem, 
           cgm.z01_ender||', '||cgm.z01_numero      as endereco,
           issbase.q02_inscr                        as cod_origem,
           cgm.z01_nome                             as nome_origem,
           cgm.z01_cgccpf                           as cod_cpf_cnpj,
           cgm2.z01_nome                            as nome_assinatura,
           rhfuncao.rh37_descr                      as cargo,
           declaracaoquitacao.ar30_sequencial       as declaracao, 
           cgm.z01_ender                            as logradouro,
           cgm.z01_numero                           as numero,
           cgm.z01_compl                            as complemento, 
           cgm.z01_bairro                           as bairro,
           ''                                       as rota,
           ''                                       as orientacao
           
      from declaracaoquitacao  
     inner join db_config                on db_config.codigo     = declaracaoquitacao.ar30_instit
     inner join declaracaoquitacaoinscr  on declaracaoquitacaoinscr.ar35_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
     inner join issbase                  on issbase.q02_inscr    = declaracaoquitacaoinscr.ar35_inscr
     inner join cgm                      on cgm.z01_numcgm       = issbase.q02_numcgm
     inner join numpref                  on numpref.k03_anousu   = {$iAnoUsu}
                                        and numpref.k03_instit   = declaracaoquitacao.ar30_instit
      left join cgm as cgm2              on cgm2.z01_numcgm      = numpref.k03_respcgm
      left join rhfuncao                 on rhfuncao.rh37_instit = declaracaoquitacao.ar30_instit
                                        and rhfuncao.rh37_funcao = numpref.k03_respcargo
     where ar30_exercicio = {$iExercicio} 
       and ar30_instit    = {$iInstit}
       and ar30_situacao  = 1";

    return $sSql;

  }

  /**
   * retorna os debitos para inclusao no arquivo txt
   * @param integer $iCodDeclaracao
   */
  public function sql_query_txt_debitos($iCodDeclaracao) {

    $sSql = "
    select distinct arretipo.k00_tipo as codigo_tipo_debito, arretipo.k00_descr as tipo_debito
      from declaracaoquitacaoreg
      left join arrecant    on arrecant.k00_numpre   = declaracaoquitacaoreg.ar31_numpre
                            and arrecant.k00_numpar   = declaracaoquitacaoreg.ar31_numpar
      left join arreprescr  on arreprescr.k30_numpre = declaracaoquitacaoreg.ar31_numpre
                            and arreprescr.k30_numpar = declaracaoquitacaoreg.ar31_numpar
      left join arreforo    on arreforo.k00_numpre   = declaracaoquitacaoreg.ar31_numpre
                            and arreforo.k00_numpar   = declaracaoquitacaoreg.ar31_numpar
      left join arreold     on arreold.k00_numpre    = declaracaoquitacaoreg.ar31_numpre
                            and arreold.k00_numpar    = declaracaoquitacaoreg.ar31_numpar                   
      left join arretipo    on arretipo.k00_tipo     = arrecant.k00_tipo
     where declaracaoquitacaoreg.ar31_declaracaoquitacao = {$iCodDeclaracao} ";

    return $sSql;
    
  }

  /**
   * lista as declaracações canceladas em determinado periodo
   * @param unknown_type $sOrigem
   * @param unknown_type $dDataInicial
   * @param unknown_type $dDataFinal
   * @param unknown_type $sOrdem
   */  
  public function sql_query_declaracoes_canceladas($sOrigem, $dDataInicial, $dDataFinal, $sOrdem) {

    if($sOrigem == 'matric') {
      $sCampos = 'cast(\'Matrícula\' as varchar) as origem, ar33_matric as cod_origem';
    } elseif($sOrigem == 'inscr') {
      $sCampos = 'cast(\'Inscrição\' as varchar) as origem, ar35_inscr as cod_origem';
    } elseif($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {
      if($sOrigem == 'somentecgm') {
        $sCampos = 'cast(\'Somente CGM\' as varchar) as origem, ar34_numcgm as cod_origem';
      } else {
        $sCampos = 'cast(\'CGM Geral\' as varchar) as origem, ar34_numcgm as cod_origem';
      }
    }

    $sSql = "
            select ar30_sequencial, ar30_exercicio, ar32_datacancelamento, z01_nome, ar32_obs, {$sCampos}
              from declaracaoquitacaocancelamento
             inner join declaracaoquitacao on ar30_sequencial = ar32_declaracaoquitacao ";

    if($sOrigem == 'matric') {

      $sSql .= "
              inner join declaracaoquitacaomatric on ar33_declaracaoquitacao = ar30_sequencial
              inner join aguabase                 on x01_matric              = ar33_matric
              inner join cgm                      on z01_numcgm              = x01_numcgm ";

    } elseif($sOrigem == 'inscr') {

      $sSql .= "
              inner join declaracaoquitacaoinscr on ar35_declaracaoquitacao = ar30_sequencial
              inner join issbase                 on q02_inscr               = ar35_inscr
              inner join cgm                     on z01_numcgm              = q02_numcgm ";

    } elseif($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {

      if($sOrigem == 'somentecgm') {
        $sAnd  = " and ar34_somentecgm is true ";
      } else {
        $sAnd  = " and ar34_somentecgm is false ";
      }
      $sSql .= "
              inner join declaracaoquitacaocgm on ar34_declaracaoquitacao = ar30_sequencial
              inner join cgm                   on z01_numcgm              = ar34_numcgm $sAnd";

    }

    $sSql .= "
            where ar32_datacancelamento between '{$dDataInicial}' and '{$dDataFinal}' 
              and ar30_instit    = ".db_getsession('DB_instit')." ";

    if($sOrdem == 'datacancelamento') {

      $sSql .= 'order by ar32_datacancelamento ';

    } else {

      $sSql .= 'order by ar30_sequencial ';

    }

    return $sSql;

  }

  /**
   * lista as declarações de quitacao
   * @param integer $iOrigem
   * @param integer $iCodOrigem
   * @param logico $bSomenteCGM
   */
  public function sql_query_lista_declaracoes($iOrigem, $iCodOrigem, $lSomenteCGM = false) {

    $sCampos  = "ar30_sequencial, ";
    $sCampos .= "ar30_exercicio,";
    $sCampos .= "case ";
    $sCampos .= " when ar30_situacao = 1 then 'Ativa' "; 
    $sCampos .= " when ar30_situacao = 2 then 'Anulada' "; 
    $sCampos .= " else 'Anulada Automaticamente' ";
    $sCampos .= "end as ar30_situacao, ";

    if($iOrigem == 1) {

      $sTabela = " declaracaoquitacaocgm ";
      
      if($lSomenteCGM) {
        $sCampos .= "cast('Somente CGM' as varchar) as ar30_origem ";
        $sWhere   = "ar34_somentecgm is true ";
      } else {
        $sCampos .= "cast('CGM Geral' as varchar) as ar30_origem ";
        $sWhere   = "ar34_somentecgm is false ";
      }
      
      $sWhere         .= "and ar34_numcgm = {$iCodOrigem} ";
      $sCampoInnerJoin = "ar34_declaracaoquitacao ";  
      
    } else if($iOrigem == 2) {
      
      $sTabela         = " declaracaoquitacaomatric ";
      $sCampos        .= "cast('Matr&iacute;cula' as varchar) as ar30_origem ";
      $sWhere          = "ar33_matric = {$iCodOrigem} ";
      $sCampoInnerJoin = "ar33_declaracaoquitacao ";
        
    } else if($iOrigem == 3) {
      
      $sTabela     = " declaracaoquitacaoinscr ";
      $sCampos    .= "cast('Inscri&ccedil;&atilde;o' as varchar) as ar30_origem ";
      $sWhere      = "ar35_inscr = {$iCodOrigem} ";
      $sCampoInnerJoin  = "ar35_declaracaoquitacao "; 
      
    }

    $sSql  = "select {$sCampos} ";
    $sSql .= "  from {$sTabela} ";
    $sSql .= " inner join declaracaoquitacao on ar30_sequencial = {$sCampoInnerJoin} ";
    $sSql .= " where {$sWhere}  ";
    $sSql .= "   and ar30_instit    = ".db_getsession('DB_instit')." ";
    $sSql .= " order by ar30_exercicio ";
    return $sSql;
  }
  
  /**
   * lista as declarações de quitação pelo codigo da declaração informado
   * @param $iCodDeclaracao
   */
  public function sql_query_detalhes_declaracao($iCodDeclaracao) {
    
    $sSql = "
    select codigodeclaracao, exercicio, nomecgm, origem, codigoorigem, numeroorigem, data, usuario, situacao, anomesimpressao

      from (
            select ar30_sequencial as codigodeclaracao, 
                   ar30_exercicio  as exercicio, 
                   z01_nome        as nomecgm, 
                   'Somente CGM'::varchar as origem, 
                   1::integer      as numeroorigem,
                   ar34_numcgm     as codigoorigem, 
                   ar30_situacao   as situacao, 
                   (ar41_mesemissao || '/' || ar41_anoemissao) as anomesimpressao,
                   ar30_data       as data, 
                   nome            as usuario
    
              from declaracaoquitacaocgm
             inner join declaracaoquitacao          on ar30_sequencial         = ar34_declaracaoquitacao
             inner join cgm                         on z01_numcgm              = ar34_numcgm
             inner join db_usuarios                 on id_usuario              = ar30_id_usuario
             inner join db_config                   on codigo                  = ar30_instit 
                                                   and codigo                  = ".db_getsession('DB_instit')."
             left  join declaracaoquitacaocarneagua on ar41_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
             where ar30_sequencial = {$iCodDeclaracao} 
               and ar34_somentecgm is true
    
             union 
    
            select ar30_sequencial as codigodeclaracao, 
                   ar30_exercicio  as exercicio, 
                   z01_nome        as nomecgm, 
                   'CGM Geral'::varchar as origem, 
                   1::integer      as numeroorigem,
                   ar34_numcgm     as codigoorigem, 
                   ar30_situacao   as situacao, 
                   (ar41_mesemissao || '/' || ar41_anoemissao) as anomesimpressao,
                   ar30_data       as data, 
                   nome            as usuario
    
              from declaracaoquitacaocgm
             inner join declaracaoquitacao on ar30_sequencial  = ar34_declaracaoquitacao
             inner join cgm                on z01_numcgm       = ar34_numcgm
             inner join db_usuarios        on id_usuario       = ar30_id_usuario
             inner join db_config          on codigo           = ar30_instit 
                                          and codigo           = ".db_getsession('DB_instit')."
             left  join declaracaoquitacaocarneagua on ar41_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
             where ar30_sequencial = {$iCodDeclaracao} 
               and ar34_somentecgm is false
               and not exists (select 1 from declaracaoquitacaomatric where ar33_declaracaoquitacao = {$iCodDeclaracao})
               and not exists (select 1 from declaracaoquitacaoinscr  where ar35_declaracaoquitacao = {$iCodDeclaracao})
            
             union
    
            select ar30_sequencial as codigodeclaracao, 
                   ar30_exercicio  as exercicio, 
                   z01_nome        as nomecgm, 
                   'Matr&iacute;cula'::varchar as origem, 
                   2::integer      as numeroorigem,
                   ar33_matric     as codigoorigem, 
                   ar30_situacao   as situacao, 
                   (ar41_mesemissao || '/' || ar41_anoemissao) as anomesimpressao,
                   ar30_data       as data, 
                   nome            as usuario
    
              from declaracaoquitacaomatric
             inner join declaracaoquitacao    on ar30_sequencial         = ar33_declaracaoquitacao
             inner join declaracaoquitacaocgm on ar34_declaracaoquitacao = ar33_declaracaoquitacao
             inner join cgm                   on z01_numcgm              = ar34_numcgm
             inner join db_usuarios           on id_usuario              = ar30_id_usuario
             inner join db_config             on codigo                  = ar30_instit 
                                             and codigo                  = ".db_getsession('DB_instit')."
             left  join declaracaoquitacaocarneagua on ar41_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
             where ar30_sequencial = {$iCodDeclaracao}
             
             union
    
            select ar30_sequencial as codigodeclaracao, 
                   ar30_exercicio  as exercicio, 
                   z01_nome        as nomecgm, 
                   'Inscri&ccedil;&atilde;o'::varchar as origem,
                   3::integer      as numeroorigem, 
                   ar35_inscr      as codigoorigem, 
                   ar30_situacao   as situacao, 
                   (ar41_mesemissao || '/' || ar41_anoemissao) as anomesimpressao,
                   ar30_data       as data, 
                   nome            as usuario
    
              from declaracaoquitacaoinscr
             inner join declaracaoquitacao    on ar30_sequencial         = ar35_declaracaoquitacao
             inner join declaracaoquitacaocgm on ar34_declaracaoquitacao = ar35_declaracaoquitacao 
             inner join cgm                   on z01_numcgm              = ar34_numcgm
             inner join db_usuarios           on id_usuario              = ar30_id_usuario
             inner join db_config             on codigo                  = ar30_instit 
                                             and codigo                  = ".db_getsession('DB_instit')."
             left  join declaracaoquitacaocarneagua on ar41_declaracaoquitacao = declaracaoquitacao.ar30_sequencial
             where ar30_sequencial = {$iCodDeclaracao}
     
    ) as declaracaoquitacao";
    
    return $sSql;
    
  }
  
  /**
   * Lista os debitos registrados para a declaração de quitação
   * @param integer $iCodDeclaracao
   */
  public function sql_query_declaracao_debitos($iCodDeclaracao) {
    
    $sSql = "
    select distinct numpre, parcela, receita, tipo, valor, situacao
      from
      (
        select arrepaga.k00_numpre as numpre, arrepaga.k00_numpar as parcela, tabrec.k02_descr as receita, arretipo.k00_descr as tipo, arrepaga.k00_valor as valor, cast('Pago' as varchar) as situacao
          from arrepaga
         inner join tabrec                on tabrec.k02_codigo                 = arrepaga.k00_receit
         inner join arrecant              on arrecant.k00_numpre               = arrepaga.k00_numpre
                                         and arrecant.k00_numpar               = arrepaga.k00_numpar
         inner join arretipo              on arretipo.k00_tipo                 = arrecant.k00_tipo
         inner join declaracaoquitacaoreg on declaracaoquitacaoreg.ar31_numpre = arrepaga.k00_numpre
                                         and declaracaoquitacaoreg.ar31_numpar = arrepaga.k00_numpar
         inner join arreinstit            on arreinstit.k00_numpre             = arrepaga.k00_numpre
                                         and arreinstit.k00_instit             = ".db_getsession('DB_instit')."
         where declaracaoquitacaoreg.ar31_declaracaoquitacao  = {$iCodDeclaracao}

         union all

        select arrecant.k00_numpre as numpre, arrecant.k00_numpar as parcela, tabrec.k02_descr as receita, arretipo.k00_descr as tipo, arrecant.k00_valor as valor, cast('Cancelado' as varchar) as situacao
          from cancdebitosprocreg
         inner join cancdebitosreg        on cancdebitosreg.k21_sequencia      = cancdebitosprocreg.k24_cancdebitosreg
         inner join arrecant              on arrecant.k00_numpre               = cancdebitosreg.k21_numpre
                                         and arrecant.k00_numpar               = cancdebitosreg.k21_numpar
         inner join arretipo              on arretipo.k00_tipo                 = arrecant.k00_tipo
         inner join tabrec                on tabrec.k02_codigo                 = arrecant.k00_receit
         inner join declaracaoquitacaoreg on declaracaoquitacaoreg.ar31_numpre = arrecant.k00_numpre
                                         and declaracaoquitacaoreg.ar31_numpar = arrecant.k00_numpar
         inner join arreinstit            on arreinstit.k00_numpre             = arrecant.k00_numpre
                                         and arreinstit.k00_instit             = ".db_getsession('DB_instit')."
         where declaracaoquitacaoreg.ar31_declaracaoquitacao  = {$iCodDeclaracao}
         
         union all

        select arreprescr.k30_numpre as numpre, arreprescr.k30_numpar as parcela, tabrec.k02_descr as receita, arretipo.k00_descr as tipo,arreprescr.k30_valor as valor, cast('Prescrito' as varchar) as situacao
          from arreprescr
         inner join tabrec                on tabrec.k02_codigo                 = arreprescr.k30_receit
         inner join declaracaoquitacaoreg on declaracaoquitacaoreg.ar31_numpre = arreprescr.k30_numpre
                                         and declaracaoquitacaoreg.ar31_numpar = arreprescr.k30_numpar              
         inner join arretipo              on arretipo.k00_tipo                 = arreprescr.k30_tipo
         inner join arreinstit            on arreinstit.k00_numpre             = arreprescr.k30_numpre
                                         and arreinstit.k00_instit             = ".db_getsession('DB_instit')."
         where declaracaoquitacaoreg.ar31_declaracaoquitacao = {$iCodDeclaracao}

        union all

        select arreforo.k00_numpre as numpre, arreforo.k00_numpar as parcela,
               tabrec.k02_descr as receita, arretipo.k00_descr as tipo, 
               arreforo.k00_valor as valor, cast('Parcelado Foro' as varchar) as situacao
          from arreforo
         inner join tabrec                on tabrec.k02_codigo                 = arreforo.k00_receit
         inner join declaracaoquitacaoreg on declaracaoquitacaoreg.ar31_numpre = arreforo.k00_numpre
                                         and declaracaoquitacaoreg.ar31_numpar = arreforo.k00_numpar              
         inner join arretipo              on arretipo.k00_tipo                 = arreforo.k00_tipo
         inner join arreinstit            on arreinstit.k00_numpre             = arreforo.k00_numpre
                                         and arreinstit.k00_instit             = ".db_getsession('DB_instit')."
         where declaracaoquitacaoreg.ar31_declaracaoquitacao = {$iCodDeclaracao}

        union all

        select arreold.k00_numpre as numpre, arreold.k00_numpar as parcela,
               tabrec.k02_descr as receita, arretipo.k00_descr as tipo, 
               arreold.k00_valor as valor, cast('Parcelado Divida' as varchar) as situacao
          from arreold
         inner join tabrec                on tabrec.k02_codigo                 = arreold.k00_receit
         inner join declaracaoquitacaoreg on declaracaoquitacaoreg.ar31_numpre = arreold.k00_numpre
                                         and declaracaoquitacaoreg.ar31_numpar = arreold.k00_numpar              
         inner join arretipo              on arretipo.k00_tipo                 = arreold.k00_tipo
         inner join arreinstit            on arreinstit.k00_numpre             = arreold.k00_numpre
                                         and arreinstit.k00_instit             = ".db_getsession('DB_instit')."
         inner join termodiv              on termodiv.numpreant                = arreold.k00_numpre
         inner join termo                 on termo.v07_parcel                  = termodiv.parcel
         where declaracaoquitacaoreg.ar31_declaracaoquitacao = {$iCodDeclaracao}
        
       ) as x 
       order by numpre, parcela, receita";
    
    return $sSql;
    
  }
  
  public function sql_query_lista_origens($iOrigem, $iExercicio, $lSomenteCgm = false) {
    
    $sLeftJoinArrecant   = "";
    $sLeftJoinArrepaga   = "";
    $sLeftJoinArreprescr = "";
    $sWhereLeftJoin      = "";
    
    if ($iOrigem == 1) {
      
      $sTabela = 'arrenumcgm';
      $sCampo  = 'k00_numcgm';
      
      if($lSomenteCgm) {
        
        $sLeftJoinArrepaga = "
        left join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre 
        left join arreinscr  on arreinscr.k00_numpre  = arrepaga.k00_numpre ";

        $sLeftJoinArrecant = "
        left join arrematric on arrematric.k00_numpre = arrecant.k00_numpre
        left join arreinscr  on arreinscr.k00_numpre  = arrecant.k00_numpre ";

        $sLeftJoinArreprescr = "
        left join arrematric on arrematric.k00_numpre = arreprescr.k30_numpre 
        left join arreinscr  on arreinscr.k00_numpre  = arreprescr.k30_numpre ";

        $sWhereLeftJoin = "
        and arrematric.k00_matric is null
        and arreinscr.k00_inscr is null ";
        
      }
      
    } elseif ($iOrigem == 2) {
      
      $sTabela = 'arrematric';
      $sCampo  = 'k00_matric';
      
    } elseif ($iOrigem == 3) {
      
      $sTabela = 'arreinscr';
      $sCampo  = 'k00_inscr';
      
    }
    
    $sSql = "
    select codigo_origem
    from (
    select distinct codigo_origem
         from (
                 select {$sTabela}.{$sCampo} as codigo_origem
                  from arrepaga
                 inner join {$sTabela} on {$sTabela}.k00_numpre = arrepaga.k00_numpre
                 {$sLeftJoinArrepaga}
                 inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre
                                      and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                 where extract( year from arrepaga.k00_dtvenc ) = {$iExercicio}
                 {$sWhereLeftJoin}
      
               union all
     
                select {$sTabela}.{$sCampo} as codigo_origem
                  from cancdebitosprocreg
                 inner join cancdebitosreg  on cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg
                 inner join arrecant        on arrecant.k00_numpre   = k21_numpre
                                           and arrecant.k00_numpar   = k21_numpar
                 inner join {$sTabela}      on {$sTabela}.k00_numpre = arrecant.k00_numpre
                 {$sLeftJoinArrecant}
                 inner join arreinstit      on arreinstit.k00_numpre = arrecant.k00_numpre
                                           and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                 where extract( year from arrecant.k00_dtvenc ) = {$iExercicio}
                 {$sWhereLeftJoin}
                   and not exists ( select 1
                                      from arrepaga
                                     where arrepaga.k00_numpre  = arrecant.k00_numpre
                                       and arrepaga.k00_numpar  = arrecant.k00_numpar limit 1 )
      
               union all
      
                select {$sTabela}.{$sCampo} as codigo_origem
                  from arreprescr
                 inner join {$sTabela} on {$sTabela}.k00_numpre = arreprescr.k30_numpre
                 {$sLeftJoinArreprescr}
                 inner join arreinstit on arreinstit.k00_numpre = arreprescr.k30_numpre
                                          and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                 where extract( year from arreprescr.k30_dtvenc ) = {$iExercicio}
                 {$sWhereLeftJoin}
                
               union all
      
                select {$sTabela}.{$sCampo} as codigo_origem
                  from arreforo
                 inner join {$sTabela} on {$sTabela}.k00_numpre = arreforo.k00_numpre
                 {$sLeftJoinArreprescr}
                 inner join arreinstit on arreinstit.k00_numpre = arreforo.k00_numpre
                                          and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                 where extract( year from arreforo.k00_dtvenc ) = {$iExercicio}
                 {$sWhereLeftJoin}
                 
               union all
      
                select {$sTabela}.{$sCampo} as codigo_origem
                  from arreold
                       inner join {$sTabela} on {$sTabela}.k00_numpre = arreold.k00_numpre
                       {$sLeftJoinArreprescr}
                       inner join arreinstit on arreinstit.k00_numpre = arreold.k00_numpre
                                            and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                       inner join termodiv   on termodiv.numpreant    = arreold.k00_numpre
                       inner join termo      on termo.v07_parcel      = termodiv.parcel
                 where extract( year from arreold.k00_dtvenc ) = {$iExercicio}
                       {$sWhereLeftJoin}
                 
              ) as y  
            ) as x
         where not exists ( select 1
                              from {$sTabela}
                             inner join arrecad    on arrecad.k00_numpre = {$sTabela}.k00_numpre
                             inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                                                  and arreinstit.k00_instit = " . db_getsession('DB_instit') . "
                             where {$sTabela}.{$sCampo} = x.codigo_origem
                               and extract( year from arrecad.k00_dtvenc ) = {$iExercicio}
                               and not exists ( select 1
                                                  from arresusp 
                                                 inner join suspensaofinaliza on suspensaofinaliza.ar19_suspensao = arresusp.k00_suspensao
                                                 where arresusp.k00_numpre = arrecad.k00_numpre
                                                   and arresusp.k00_numpar = arrecad.k00_numpar
                                                   and suspensaofinaliza.ar19_tipo = 2
                                               limit 1 )
                             limit 1)";
    
    return $sSql;
    
  }
  
}
?>