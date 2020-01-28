<?
//MODULO: protocolo
//CLASSE DA ENTIDADE cgmestrangeiro
class cl_cgmestrangeiro {
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
  var $z09_sequencial = 0;
  var $z09_numcgm = 0;
  var $z09_documento = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 z09_sequencial = int4 = Código 
                 z09_numcgm = int4 = Código do CGM 
                 z09_documento = varchar(30) = Documento 
                 ";
  //funcao construtor da classe
  public function cl_cgmestrangeiro() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("cgmestrangeiro");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro
  public function erro($mostra,$retorna) {
    if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if($retorna==true){
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }
  // funcao para atualizar campos
  public function atualizacampos($exclusao=false) {
    if($exclusao==false){
      $this->z09_sequencial = ($this->z09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z09_sequencial"]:$this->z09_sequencial);
      $this->z09_numcgm = ($this->z09_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z09_numcgm"]:$this->z09_numcgm);
      $this->z09_documento = ($this->z09_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["z09_documento"]:$this->z09_documento);
    }else{
      $this->z09_sequencial = ($this->z09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z09_sequencial"]:$this->z09_sequencial);
    }
  }
  // funcao para Inclusão
  public function incluir ($z09_sequencial){
    $this->atualizacampos();
    if($this->z09_numcgm == null ){
      $this->erro_sql = " Campo Código do CGM não informado.";
      $this->erro_campo = "z09_numcgm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->z09_documento == null ){
      $this->erro_sql = " Campo Documento não informado.";
      $this->erro_campo = "z09_documento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($z09_sequencial == "" || $z09_sequencial == null ){
      $result = db_query("select nextval('cgmestrangeiro_z09_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: cgmestrangeiro_z09_sequencial_seq do campo: z09_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->z09_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from cgmestrangeiro_z09_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $z09_sequencial)){
        $this->erro_sql = " Campo z09_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->z09_sequencial = $z09_sequencial;
      }
    }
    if(($this->z09_sequencial == null) || ($this->z09_sequencial == "") ){
      $this->erro_sql = " Campo z09_sequencial não declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into cgmestrangeiro(
                                       z09_sequencial 
                                      ,z09_numcgm 
                                      ,z09_documento 
                       )
                values (
                                $this->z09_sequencial 
                               ,$this->z09_numcgm 
                               ,'$this->z09_documento' 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "CGM Estrangeiro ($this->z09_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "CGM Estrangeiro já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "CGM Estrangeiro ($this->z09_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->z09_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->z09_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,21907,'$this->z09_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,3944,21907,'','".AddSlashes(pg_result($resaco,0,'z09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3944,21908,'','".AddSlashes(pg_result($resaco,0,'z09_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3944,21909,'','".AddSlashes(pg_result($resaco,0,'z09_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  public function alterar ($z09_sequencial=null) {
    $this->atualizacampos();
    $sql = " update cgmestrangeiro set ";
    $virgula = "";
    if(trim($this->z09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z09_sequencial"])){
      $sql  .= $virgula." z09_sequencial = $this->z09_sequencial ";
      $virgula = ",";
      if(trim($this->z09_sequencial) == null ){
        $this->erro_sql = " Campo Código não informado.";
        $this->erro_campo = "z09_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->z09_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z09_numcgm"])){
      $sql  .= $virgula." z09_numcgm = $this->z09_numcgm ";
      $virgula = ",";
      if(trim($this->z09_numcgm) == null ){
        $this->erro_sql = " Campo Código do CGM não informado.";
        $this->erro_campo = "z09_numcgm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->z09_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z09_documento"])){
      $sql  .= $virgula." z09_documento = '$this->z09_documento' ";
      $virgula = ",";
      if(trim($this->z09_documento) == null ){
        $this->erro_sql = " Campo Documento não informado.";
        $this->erro_campo = "z09_documento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($z09_sequencial!=null){
      $sql .= " z09_sequencial = $this->z09_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->z09_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,21907,'$this->z09_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["z09_sequencial"]) || $this->z09_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,3944,21907,'".AddSlashes(pg_result($resaco,$conresaco,'z09_sequencial'))."','$this->z09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["z09_numcgm"]) || $this->z09_numcgm != "")
            $resac = db_query("insert into db_acount values($acount,3944,21908,'".AddSlashes(pg_result($resaco,$conresaco,'z09_numcgm'))."','$this->z09_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["z09_documento"]) || $this->z09_documento != "")
            $resac = db_query("insert into db_acount values($acount,3944,21909,'".AddSlashes(pg_result($resaco,$conresaco,'z09_documento'))."','$this->z09_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "CGM Estrangeiro não Alterado. Alteração Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->z09_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "CGM Estrangeiro não foi Alterado. Alteração Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->z09_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->z09_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  public function excluir ($z09_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($z09_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,21907,'$z09_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,3944,21907,'','".AddSlashes(pg_result($resaco,$iresaco,'z09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3944,21908,'','".AddSlashes(pg_result($resaco,$iresaco,'z09_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3944,21909,'','".AddSlashes(pg_result($resaco,$iresaco,'z09_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from cgmestrangeiro
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($z09_sequencial)){
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " z09_sequencial = $z09_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "CGM Estrangeiro não Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$z09_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "CGM Estrangeiro não Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$z09_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$z09_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:cgmestrangeiro";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  public function sql_query ($z09_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from cgmestrangeiro ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = cgmestrangeiro.z09_numcgm";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($z09_sequencial)) {
        $sql2 .= " where cgmestrangeiro.z09_sequencial = $z09_sequencial ";
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
  public function sql_query_file ($z09_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from cgmestrangeiro ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($z09_sequencial)){
        $sql2 .= " where cgmestrangeiro.z09_sequencial = $z09_sequencial ";
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

}
