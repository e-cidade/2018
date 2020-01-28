<?
//MODULO: empenho
//CLASSE DA ENTIDADE empagemovjustificativa
class cl_empagemovjustificativa {
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
   var $e09_sequencial = 0;
   var $e09_codnota = 0;
   var $e09_codmov = 0;
   var $e09_justificativa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e09_sequencial = int4 = Código
                 e09_codnota = int4 = Código da Nota
                 e09_codmov = int4 = Código do Movimento
                 e09_justificativa = text = Justificativa
                 ";
   //funcao construtor da classe
   function cl_empagemovjustificativa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagemovjustificativa");
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
       $this->e09_sequencial = ($this->e09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e09_sequencial"]:$this->e09_sequencial);
       $this->e09_codnota = ($this->e09_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e09_codnota"]:$this->e09_codnota);
       $this->e09_codmov = ($this->e09_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e09_codmov"]:$this->e09_codmov);
       $this->e09_justificativa = ($this->e09_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["e09_justificativa"]:$this->e09_justificativa);
     }else{
       $this->e09_codmov = ($this->e09_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e09_codmov"]:$this->e09_codmov);
     }
   }
   // funcao para Inclusão
   function incluir ($e09_sequencial = null){
      $this->atualizacampos();
     if($this->e09_codnota == null ){
       $this->erro_sql = " Campo Código da Nota não informado.";
       $this->erro_campo = "e09_codnota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e09_justificativa == null ){
       $this->erro_sql = " Campo Justificativa não informado.";
       $this->erro_campo = "e09_justificativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e09_sequencial == "" || $e09_sequencial == null ){
       $result = db_query("select nextval('empagemovjustificativa_e09_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagemovjustificativa_e09_sequencial_seq do campo: e09_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e09_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empagemovjustificativa_e09_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e09_sequencial)){
         $this->erro_sql = " Campo e09_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e09_sequencial = $e09_sequencial;
       }
     }
     if(($this->e09_codmov == null) || ($this->e09_codmov == "") ){
       $this->erro_sql = " Campo e09_codmov não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagemovjustificativa(
                                       e09_sequencial
                                      ,e09_codnota
                                      ,e09_codmov
                                      ,e09_justificativa
                       )
                values (
                                $this->e09_sequencial
                               ,$this->e09_codnota
                               ,$this->e09_codmov
                               ,'$this->e09_justificativa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empagemovjustificativa ($this->e09_codmov) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empagemovjustificativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empagemovjustificativa ($this->e09_codmov) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e09_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e09_codmov  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21615,'$this->e09_codmov','I')");
         $resac = db_query("insert into db_acount values($acount,3883,21613,'','".AddSlashes(pg_result($resaco,0,'e09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3883,21614,'','".AddSlashes(pg_result($resaco,0,'e09_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3883,21615,'','".AddSlashes(pg_result($resaco,0,'e09_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3883,21616,'','".AddSlashes(pg_result($resaco,0,'e09_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e09_codmov=null) {
      $this->atualizacampos();
     $sql = " update empagemovjustificativa set ";
     $virgula = "";
     if(trim($this->e09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e09_sequencial"])){
       $sql  .= $virgula." e09_sequencial = $this->e09_sequencial ";
       $virgula = ",";
       if(trim($this->e09_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "e09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e09_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e09_codnota"])){
       $sql  .= $virgula." e09_codnota = $this->e09_codnota ";
       $virgula = ",";
       if(trim($this->e09_codnota) == null ){
         $this->erro_sql = " Campo Código da Nota não informado.";
         $this->erro_campo = "e09_codnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e09_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e09_codmov"])){
       $sql  .= $virgula." e09_codmov = $this->e09_codmov ";
       $virgula = ",";
       if(trim($this->e09_codmov) == null ){
         $this->erro_sql = " Campo Código do Movimento não informado.";
         $this->erro_campo = "e09_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e09_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e09_justificativa"])){
       $sql  .= $virgula." e09_justificativa = '$this->e09_justificativa' ";
       $virgula = ",";
       if(trim($this->e09_justificativa) == null ){
         $this->erro_sql = " Campo Justificativa não informado.";
         $this->erro_campo = "e09_justificativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e09_codmov!=null){
       $sql .= " e09_codmov = $this->e09_codmov";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e09_codmov));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21615,'$this->e09_codmov','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e09_sequencial"]) || $this->e09_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3883,21613,'".AddSlashes(pg_result($resaco,$conresaco,'e09_sequencial'))."','$this->e09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e09_codnota"]) || $this->e09_codnota != "")
             $resac = db_query("insert into db_acount values($acount,3883,21614,'".AddSlashes(pg_result($resaco,$conresaco,'e09_codnota'))."','$this->e09_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e09_codmov"]) || $this->e09_codmov != "")
             $resac = db_query("insert into db_acount values($acount,3883,21615,'".AddSlashes(pg_result($resaco,$conresaco,'e09_codmov'))."','$this->e09_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e09_justificativa"]) || $this->e09_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,3883,21616,'".AddSlashes(pg_result($resaco,$conresaco,'e09_justificativa'))."','$this->e09_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagemovjustificativa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e09_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "empagemovjustificativa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e09_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e09_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e09_codmov=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e09_codmov));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21615,'$e09_codmov','E')");
           $resac  = db_query("insert into db_acount values($acount,3883,21613,'','".AddSlashes(pg_result($resaco,$iresaco,'e09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3883,21614,'','".AddSlashes(pg_result($resaco,$iresaco,'e09_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3883,21615,'','".AddSlashes(pg_result($resaco,$iresaco,'e09_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3883,21616,'','".AddSlashes(pg_result($resaco,$iresaco,'e09_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empagemovjustificativa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e09_codmov)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e09_codmov = $e09_codmov ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagemovjustificativa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e09_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "empagemovjustificativa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e09_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e09_codmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagemovjustificativa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e09_codmov = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empagemovjustificativa ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empagemovjustificativa.e09_codnota";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagemovjustificativa.e09_codmov";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join empage  as a on   a.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e09_codmov)) {
         $sql2 .= " where empagemovjustificativa.e09_codmov = $e09_codmov ";
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
   public function sql_query_file ($e09_codmov = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empagemovjustificativa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e09_codmov)){
         $sql2 .= " where empagemovjustificativa.e09_codmov = $e09_codmov ";
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
