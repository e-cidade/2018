<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE escriturainventario
class cl_escriturainventario {
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
  var $c88_sequencial = 0;
  var $c88_inventario = 0;
  var $c88_data_dia = null;
  var $c88_data_mes = null;
  var $c88_data_ano = null;
  var $c88_data = null;
  var $c88_usuario = 0;
  var $c88_estornado = 'f';
  // cria propriedade com as variaveis do arquivo
  var $campos = "
  c88_sequencial = int4 = Sequencial
  c88_inventario = int4 = Inventario
  c88_data = date = Data
  c88_usuario = int4 = Usuario
  c88_estornado = bool = Estornado
  ";
  //funcao construtor da classe
  function cl_escriturainventario() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("escriturainventario");
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
      $this->c88_sequencial = ($this->c88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_sequencial"]:$this->c88_sequencial);
      $this->c88_inventario = ($this->c88_inventario == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_inventario"]:$this->c88_inventario);
      if($this->c88_data == ""){
        $this->c88_data_dia = ($this->c88_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_data_dia"]:$this->c88_data_dia);
        $this->c88_data_mes = ($this->c88_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_data_mes"]:$this->c88_data_mes);
        $this->c88_data_ano = ($this->c88_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_data_ano"]:$this->c88_data_ano);
        if($this->c88_data_dia != ""){
          $this->c88_data = $this->c88_data_ano."-".$this->c88_data_mes."-".$this->c88_data_dia;
        }
      }
      $this->c88_usuario = ($this->c88_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_usuario"]:$this->c88_usuario);
      $this->c88_estornado = ($this->c88_estornado == "f"?@$GLOBALS["HTTP_POST_VARS"]["c88_estornado"]:$this->c88_estornado);
    }else{
      $this->c88_sequencial = ($this->c88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c88_sequencial"]:$this->c88_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($c88_sequencial){
    $this->atualizacampos();
    if($this->c88_inventario == null ){
      $this->erro_sql = " Campo Inventario nao Informado.";
      $this->erro_campo = "c88_inventario";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c88_data == null ){
      $this->erro_sql = " Campo Data nao Informado.";
      $this->erro_campo = "c88_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c88_usuario == null ){
      $this->erro_sql = " Campo Usuario nao Informado.";
      $this->erro_campo = "c88_usuario";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c88_estornado == null ){
      $this->erro_sql = " Campo Estornado nao Informado.";
      $this->erro_campo = "c88_estornado";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($c88_sequencial == "" || $c88_sequencial == null ){
      $result = db_query("select nextval('escriturainventario_c88_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: escriturainventario_c88_sequencial_seq do campo: c88_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c88_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from escriturainventario_c88_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $c88_sequencial)){
        $this->erro_sql = " Campo c88_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->c88_sequencial = $c88_sequencial;
      }
    }
    if(($this->c88_sequencial == null) || ($this->c88_sequencial == "") ){
      $this->erro_sql = " Campo c88_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into escriturainventario(
    c88_sequencial
    ,c88_inventario
    ,c88_data
    ,c88_usuario
    ,c88_estornado
    )
    values (
    $this->c88_sequencial
    ,$this->c88_inventario
    ,".($this->c88_data == "null" || $this->c88_data == ""?"null":"'".$this->c88_data."'")."
    ,$this->c88_usuario
    ,'$this->c88_estornado'
    )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Escritura de lancamentro ($this->c88_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Escritura de lancamentro já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Escritura de lancamentro ($this->c88_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->c88_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->c88_sequencial));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,19468,'$this->c88_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,3456,19468,'','".AddSlashes(pg_result($resaco,0,'c88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3456,19469,'','".AddSlashes(pg_result($resaco,0,'c88_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3456,19470,'','".AddSlashes(pg_result($resaco,0,'c88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3456,19471,'','".AddSlashes(pg_result($resaco,0,'c88_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3456,19472,'','".AddSlashes(pg_result($resaco,0,'c88_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($c88_sequencial=null) {
    $this->atualizacampos();
    $sql = " update escriturainventario set ";
    $virgula = "";
    if(trim($this->c88_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c88_sequencial"])){
      $sql  .= $virgula." c88_sequencial = $this->c88_sequencial ";
      $virgula = ",";
      if(trim($this->c88_sequencial) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "c88_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c88_inventario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c88_inventario"])){
      $sql  .= $virgula." c88_inventario = $this->c88_inventario ";
      $virgula = ",";
      if(trim($this->c88_inventario) == null ){
        $this->erro_sql = " Campo Inventario nao Informado.";
        $this->erro_campo = "c88_inventario";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c88_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c88_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c88_data_dia"] !="") ){
      $sql  .= $virgula." c88_data = '$this->c88_data' ";
      $virgula = ",";
      if(trim($this->c88_data) == null ){
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "c88_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["c88_data_dia"])){
        $sql  .= $virgula." c88_data = null ";
        $virgula = ",";
        if(trim($this->c88_data) == null ){
          $this->erro_sql = " Campo Data nao Informado.";
          $this->erro_campo = "c88_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->c88_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c88_usuario"])){
      $sql  .= $virgula." c88_usuario = $this->c88_usuario ";
      $virgula = ",";
      if(trim($this->c88_usuario) == null ){
        $this->erro_sql = " Campo Usuario nao Informado.";
        $this->erro_campo = "c88_usuario";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c88_estornado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c88_estornado"])){
      $sql  .= $virgula." c88_estornado = '$this->c88_estornado' ";
      $virgula = ",";
      if(trim($this->c88_estornado) == null ){
        $this->erro_sql = " Campo Estornado nao Informado.";
        $this->erro_campo = "c88_estornado";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($c88_sequencial!=null){
      $sql .= " c88_sequencial = $this->c88_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->c88_sequencial));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19468,'$this->c88_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c88_sequencial"]) || $this->c88_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,3456,19468,'".AddSlashes(pg_result($resaco,$conresaco,'c88_sequencial'))."','$this->c88_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c88_inventario"]) || $this->c88_inventario != "")
          $resac = db_query("insert into db_acount values($acount,3456,19469,'".AddSlashes(pg_result($resaco,$conresaco,'c88_inventario'))."','$this->c88_inventario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c88_data"]) || $this->c88_data != "")
          $resac = db_query("insert into db_acount values($acount,3456,19470,'".AddSlashes(pg_result($resaco,$conresaco,'c88_data'))."','$this->c88_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c88_usuario"]) || $this->c88_usuario != "")
          $resac = db_query("insert into db_acount values($acount,3456,19471,'".AddSlashes(pg_result($resaco,$conresaco,'c88_usuario'))."','$this->c88_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c88_estornado"]) || $this->c88_estornado != "")
          $resac = db_query("insert into db_acount values($acount,3456,19472,'".AddSlashes(pg_result($resaco,$conresaco,'c88_estornado'))."','$this->c88_estornado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Escritura de lancamentro nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->c88_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Escritura de lancamentro nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->c88_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c88_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($c88_sequencial=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($c88_sequencial));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19468,'$c88_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,3456,19468,'','".AddSlashes(pg_result($resaco,$iresaco,'c88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3456,19469,'','".AddSlashes(pg_result($resaco,$iresaco,'c88_inventario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3456,19470,'','".AddSlashes(pg_result($resaco,$iresaco,'c88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3456,19471,'','".AddSlashes(pg_result($resaco,$iresaco,'c88_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3456,19472,'','".AddSlashes(pg_result($resaco,$iresaco,'c88_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from escriturainventario
    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($c88_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " c88_sequencial = $c88_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Escritura de lancamentro nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$c88_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Escritura de lancamentro nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$c88_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$c88_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:escriturainventario";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $c88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from escriturainventario ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = escriturainventario.c88_usuario";
    $sql .= "      inner join inventario  on  inventario.t75_sequencial = escriturainventario.c88_inventario";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
    $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
    $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
    $sql2 = "";
    if($dbwhere==""){
      if($c88_sequencial!=null ){
        $sql2 .= " where escriturainventario.c88_sequencial = $c88_sequencial ";
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
  function sql_query_file ( $c88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from escriturainventario ";
    $sql2 = "";
    if($dbwhere==""){
      if($c88_sequencial!=null ){
        $sql2 .= " where escriturainventario.c88_sequencial = $c88_sequencial ";
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


  function sql_queryLancamentoAnterior ( $c88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

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
    $sql .= " from escriturainventario ";
    $sql .= "      inner join conlancaminventario on c85_escriturainventario = c88_sequencial ";
    $sql .= "      inner join conlancam           on c70_codlan              = c85_codlan     ";
    $sql .= "      inner join conlancamdoc        on c71_codlan              = c85_codlan     ";

    $sql2 = "";
    if($dbwhere==""){
      if($c88_sequencial!=null ){
        $sql2 .= " where escriturainventario.c88_sequencial = $c88_sequencial ";
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







}
?>