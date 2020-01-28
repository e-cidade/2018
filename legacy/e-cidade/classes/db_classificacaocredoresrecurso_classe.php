<?
//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredoresrecurso
class cl_classificacaocredoresrecurso { 
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
   var $cc33_sequencial = 0; 
   var $cc33_classificacaocredores = 0; 
   var $cc33_orctiporec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc33_sequencial = int4 = Código 
                 cc33_classificacaocredores = int4 = Classificação de Credores 
                 cc33_orctiporec = int4 = Recurso 
                 ";
   //funcao construtor da classe 
   function cl_classificacaocredoresrecurso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredoresrecurso"); 
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
       $this->cc33_sequencial = ($this->cc33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc33_sequencial"]:$this->cc33_sequencial);
       $this->cc33_classificacaocredores = ($this->cc33_classificacaocredores == ""?@$GLOBALS["HTTP_POST_VARS"]["cc33_classificacaocredores"]:$this->cc33_classificacaocredores);
       $this->cc33_orctiporec = ($this->cc33_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["cc33_orctiporec"]:$this->cc33_orctiporec);
     }else{
       $this->cc33_sequencial = ($this->cc33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc33_sequencial"]:$this->cc33_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($cc33_sequencial){ 
      $this->atualizacampos();
     if($this->cc33_classificacaocredores == null ){ 
       $this->erro_sql = " Campo Classificação de Credores não informado.";
       $this->erro_campo = "cc33_classificacaocredores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc33_orctiporec == null ){ 
       $this->erro_sql = " Campo Recurso não informado.";
       $this->erro_campo = "cc33_orctiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc33_sequencial == "" || $cc33_sequencial == null ){
       $result = db_query("select nextval('classificacaocredoresrecurso_cc33_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredoresrecurso_cc33_sequencial_seq do campo: cc33_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc33_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaocredoresrecurso_cc33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc33_sequencial)){
         $this->erro_sql = " Campo cc33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc33_sequencial = $cc33_sequencial; 
       }
     }
     if(($this->cc33_sequencial == null) || ($this->cc33_sequencial == "") ){ 
       $this->erro_sql = " Campo cc33_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredoresrecurso(
                                       cc33_sequencial 
                                      ,cc33_classificacaocredores 
                                      ,cc33_orctiporec 
                       )
                values (
                                $this->cc33_sequencial 
                               ,$this->cc33_classificacaocredores 
                               ,$this->cc33_orctiporec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "classificacaocredoresrecurso ($this->cc33_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "classificacaocredoresrecurso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "classificacaocredoresrecurso ($this->cc33_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc33_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21886,'$this->cc33_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3940,21886,'','".AddSlashes(pg_result($resaco,0,'cc33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3940,21887,'','".AddSlashes(pg_result($resaco,0,'cc33_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3940,21888,'','".AddSlashes(pg_result($resaco,0,'cc33_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($cc33_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update classificacaocredoresrecurso set ";
     $virgula = "";
     if(trim($this->cc33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc33_sequencial"])){ 
       $sql  .= $virgula." cc33_sequencial = $this->cc33_sequencial ";
       $virgula = ",";
       if(trim($this->cc33_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "cc33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc33_classificacaocredores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc33_classificacaocredores"])){ 
       $sql  .= $virgula." cc33_classificacaocredores = $this->cc33_classificacaocredores ";
       $virgula = ",";
       if(trim($this->cc33_classificacaocredores) == null ){ 
         $this->erro_sql = " Campo Classificação de Credores não informado.";
         $this->erro_campo = "cc33_classificacaocredores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc33_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc33_orctiporec"])){ 
       $sql  .= $virgula." cc33_orctiporec = $this->cc33_orctiporec ";
       $virgula = ",";
       if(trim($this->cc33_orctiporec) == null ){ 
         $this->erro_sql = " Campo Recurso não informado.";
         $this->erro_campo = "cc33_orctiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc33_sequencial!=null){
       $sql .= " cc33_sequencial = $this->cc33_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc33_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21886,'$this->cc33_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc33_sequencial"]) || $this->cc33_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3940,21886,'".AddSlashes(pg_result($resaco,$conresaco,'cc33_sequencial'))."','$this->cc33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc33_classificacaocredores"]) || $this->cc33_classificacaocredores != "")
             $resac = db_query("insert into db_acount values($acount,3940,21887,'".AddSlashes(pg_result($resaco,$conresaco,'cc33_classificacaocredores'))."','$this->cc33_classificacaocredores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc33_orctiporec"]) || $this->cc33_orctiporec != "")
             $resac = db_query("insert into db_acount values($acount,3940,21888,'".AddSlashes(pg_result($resaco,$conresaco,'cc33_orctiporec'))."','$this->cc33_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoresrecurso não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoresrecurso não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($cc33_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc33_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21886,'$cc33_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3940,21886,'','".AddSlashes(pg_result($resaco,$iresaco,'cc33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3940,21887,'','".AddSlashes(pg_result($resaco,$iresaco,'cc33_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3940,21888,'','".AddSlashes(pg_result($resaco,$iresaco,'cc33_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredoresrecurso
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc33_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc33_sequencial = $cc33_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoresrecurso não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoresrecurso não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredoresrecurso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($cc33_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredoresrecurso ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = classificacaocredoresrecurso.cc33_orctiporec";
     $sql .= "      inner join classificacaocredores  on  classificacaocredores.cc30_codigo = classificacaocredoresrecurso.cc33_classificacaocredores";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc33_sequencial)) {
         $sql2 .= " where classificacaocredoresrecurso.cc33_sequencial = $cc33_sequencial "; 
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
   public function sql_query_file ($cc33_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredoresrecurso ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc33_sequencial)){
         $sql2 .= " where classificacaocredoresrecurso.cc33_sequencial = $cc33_sequencial "; 
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
