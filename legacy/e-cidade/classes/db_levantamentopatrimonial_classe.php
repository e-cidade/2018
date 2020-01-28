<?
//MODULO: patrimonio
//CLASSE DA ENTIDADE levantamentopatrimonial
class cl_levantamentopatrimonial { 
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
   var $p13_sequencial = 0; 
   var $p13_departamento = 0; 
   var $p13_data_dia = null; 
   var $p13_data_mes = null; 
   var $p13_data_ano = null; 
   var $p13_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p13_sequencial = int4 = Código 
                 p13_departamento = int4 = Departamento 
                 p13_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_levantamentopatrimonial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("levantamentopatrimonial"); 
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
       $this->p13_sequencial = ($this->p13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_sequencial"]:$this->p13_sequencial);
       $this->p13_departamento = ($this->p13_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_departamento"]:$this->p13_departamento);
       if($this->p13_data == ""){
         $this->p13_data_dia = ($this->p13_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_data_dia"]:$this->p13_data_dia);
         $this->p13_data_mes = ($this->p13_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_data_mes"]:$this->p13_data_mes);
         $this->p13_data_ano = ($this->p13_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_data_ano"]:$this->p13_data_ano);
         if($this->p13_data_dia != ""){
            $this->p13_data = $this->p13_data_ano."-".$this->p13_data_mes."-".$this->p13_data_dia;
         }
       }
     }else{
       $this->p13_sequencial = ($this->p13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p13_sequencial"]:$this->p13_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($p13_sequencial){ 
      $this->atualizacampos();
     if($this->p13_departamento == null ){ 
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "p13_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p13_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "p13_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p13_sequencial == "" || $p13_sequencial == null ){
       $result = db_query("select nextval('levantamentopatrimonial_p13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: levantamentopatrimonial_p13_sequencial_seq do campo: p13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from levantamentopatrimonial_p13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p13_sequencial)){
         $this->erro_sql = " Campo p13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p13_sequencial = $p13_sequencial; 
       }
     }
     if(($this->p13_sequencial == null) || ($this->p13_sequencial == "") ){ 
       $this->erro_sql = " Campo p13_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into levantamentopatrimonial(
                                       p13_sequencial 
                                      ,p13_departamento 
                                      ,p13_data 
                       )
                values (
                                $this->p13_sequencial 
                               ,$this->p13_departamento 
                               ,".($this->p13_data == "null" || $this->p13_data == ""?"null":"'".$this->p13_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Levantamento Patrimonial ($this->p13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Levantamento Patrimonial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Levantamento Patrimonial ($this->p13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p13_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21508,'$this->p13_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3862,21508,'','".AddSlashes(pg_result($resaco,0,'p13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3862,21509,'','".AddSlashes(pg_result($resaco,0,'p13_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3862,21510,'','".AddSlashes(pg_result($resaco,0,'p13_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($p13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update levantamentopatrimonial set ";
     $virgula = "";
     if(trim($this->p13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p13_sequencial"])){ 
       $sql  .= $virgula." p13_sequencial = $this->p13_sequencial ";
       $virgula = ",";
       if(trim($this->p13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "p13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p13_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p13_departamento"])){ 
       $sql  .= $virgula." p13_departamento = $this->p13_departamento ";
       $virgula = ",";
       if(trim($this->p13_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "p13_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p13_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p13_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p13_data_dia"] !="") ){ 
       $sql  .= $virgula." p13_data = '$this->p13_data' ";
       $virgula = ",";
       if(trim($this->p13_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "p13_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p13_data_dia"])){ 
         $sql  .= $virgula." p13_data = null ";
         $virgula = ",";
         if(trim($this->p13_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "p13_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($p13_sequencial!=null){
       $sql .= " p13_sequencial = $this->p13_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p13_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21508,'$this->p13_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p13_sequencial"]) || $this->p13_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3862,21508,'".AddSlashes(pg_result($resaco,$conresaco,'p13_sequencial'))."','$this->p13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p13_departamento"]) || $this->p13_departamento != "")
             $resac = db_query("insert into db_acount values($acount,3862,21509,'".AddSlashes(pg_result($resaco,$conresaco,'p13_departamento'))."','$this->p13_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p13_data"]) || $this->p13_data != "")
             $resac = db_query("insert into db_acount values($acount,3862,21510,'".AddSlashes(pg_result($resaco,$conresaco,'p13_data'))."','$this->p13_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento Patrimonial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento Patrimonial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($p13_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p13_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21508,'$p13_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3862,21508,'','".AddSlashes(pg_result($resaco,$iresaco,'p13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3862,21509,'','".AddSlashes(pg_result($resaco,$iresaco,'p13_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3862,21510,'','".AddSlashes(pg_result($resaco,$iresaco,'p13_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from levantamentopatrimonial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p13_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p13_sequencial = $p13_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento Patrimonial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento Patrimonial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:levantamentopatrimonial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($p13_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from levantamentopatrimonial ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = levantamentopatrimonial.p13_departamento";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p13_sequencial)) {
         $sql2 .= " where levantamentopatrimonial.p13_sequencial = $p13_sequencial "; 
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
   public function sql_query_file ($p13_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from levantamentopatrimonial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p13_sequencial)){
         $sql2 .= " where levantamentopatrimonial.p13_sequencial = $p13_sequencial "; 
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
