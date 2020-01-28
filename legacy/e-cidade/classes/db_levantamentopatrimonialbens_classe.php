<?
//MODULO: patrimonio
//CLASSE DA ENTIDADE levantamentopatrimonialbens
class cl_levantamentopatrimonialbens { 
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
   var $p14_sequencial = 0; 
   var $p14_levantamentopatrimonial = 0; 
   var $p14_placa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p14_sequencial = int4 = Código 
                 p14_levantamentopatrimonial = int4 = Levantamento Patrimonial 
                 p14_placa = varchar(50) = Placa 
                 ";
   //funcao construtor da classe 
   function cl_levantamentopatrimonialbens() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("levantamentopatrimonialbens"); 
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
       $this->p14_sequencial = ($this->p14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p14_sequencial"]:$this->p14_sequencial);
       $this->p14_levantamentopatrimonial = ($this->p14_levantamentopatrimonial == ""?@$GLOBALS["HTTP_POST_VARS"]["p14_levantamentopatrimonial"]:$this->p14_levantamentopatrimonial);
       $this->p14_placa = ($this->p14_placa == ""?@$GLOBALS["HTTP_POST_VARS"]["p14_placa"]:$this->p14_placa);
     }else{
       $this->p14_sequencial = ($this->p14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p14_sequencial"]:$this->p14_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($p14_sequencial){ 
      $this->atualizacampos();
     if($this->p14_levantamentopatrimonial == null ){ 
       $this->erro_sql = " Campo Levantamento Patrimonial não informado.";
       $this->erro_campo = "p14_levantamentopatrimonial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p14_placa == null ){ 
       $this->erro_sql = " Campo Placa não informado.";
       $this->erro_campo = "p14_placa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p14_sequencial == "" || $p14_sequencial == null ){
       $result = db_query("select nextval('levantamentopatrimonialbens_p14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: levantamentopatrimonialbens_p14_sequencial_seq do campo: p14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from levantamentopatrimonialbens_p14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p14_sequencial)){
         $this->erro_sql = " Campo p14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p14_sequencial = $p14_sequencial; 
       }
     }
     if(($this->p14_sequencial == null) || ($this->p14_sequencial == "") ){ 
       $this->erro_sql = " Campo p14_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into levantamentopatrimonialbens(
                                       p14_sequencial 
                                      ,p14_levantamentopatrimonial 
                                      ,p14_placa 
                       )
                values (
                                $this->p14_sequencial 
                               ,$this->p14_levantamentopatrimonial 
                               ,'$this->p14_placa' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Levantamento Patrimonial Bens ($this->p14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Levantamento Patrimonial Bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Levantamento Patrimonial Bens ($this->p14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p14_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21511,'$this->p14_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3864,21511,'','".AddSlashes(pg_result($resaco,0,'p14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3864,21512,'','".AddSlashes(pg_result($resaco,0,'p14_levantamentopatrimonial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3864,21513,'','".AddSlashes(pg_result($resaco,0,'p14_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($p14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update levantamentopatrimonialbens set ";
     $virgula = "";
     if(trim($this->p14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p14_sequencial"])){ 
       $sql  .= $virgula." p14_sequencial = $this->p14_sequencial ";
       $virgula = ",";
       if(trim($this->p14_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "p14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p14_levantamentopatrimonial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p14_levantamentopatrimonial"])){ 
       $sql  .= $virgula." p14_levantamentopatrimonial = $this->p14_levantamentopatrimonial ";
       $virgula = ",";
       if(trim($this->p14_levantamentopatrimonial) == null ){ 
         $this->erro_sql = " Campo Levantamento Patrimonial não informado.";
         $this->erro_campo = "p14_levantamentopatrimonial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p14_placa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p14_placa"])){ 
       $sql  .= $virgula." p14_placa = '$this->p14_placa' ";
       $virgula = ",";
       if(trim($this->p14_placa) == null ){ 
         $this->erro_sql = " Campo Placa não informado.";
         $this->erro_campo = "p14_placa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p14_sequencial!=null){
       $sql .= " p14_sequencial = $this->p14_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p14_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21511,'$this->p14_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p14_sequencial"]) || $this->p14_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3864,21511,'".AddSlashes(pg_result($resaco,$conresaco,'p14_sequencial'))."','$this->p14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p14_levantamentopatrimonial"]) || $this->p14_levantamentopatrimonial != "")
             $resac = db_query("insert into db_acount values($acount,3864,21512,'".AddSlashes(pg_result($resaco,$conresaco,'p14_levantamentopatrimonial'))."','$this->p14_levantamentopatrimonial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p14_placa"]) || $this->p14_placa != "")
             $resac = db_query("insert into db_acount values($acount,3864,21513,'".AddSlashes(pg_result($resaco,$conresaco,'p14_placa'))."','$this->p14_placa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento Patrimonial Bens não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento Patrimonial Bens não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($p14_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p14_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21511,'$p14_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3864,21511,'','".AddSlashes(pg_result($resaco,$iresaco,'p14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3864,21512,'','".AddSlashes(pg_result($resaco,$iresaco,'p14_levantamentopatrimonial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3864,21513,'','".AddSlashes(pg_result($resaco,$iresaco,'p14_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from levantamentopatrimonialbens
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p14_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p14_sequencial = $p14_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento Patrimonial Bens não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento Patrimonial Bens não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:levantamentopatrimonialbens";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($p14_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from levantamentopatrimonialbens ";
     $sql .= "      inner join levantamentopatrimonial  on  levantamentopatrimonial.p13_sequencial = levantamentopatrimonialbens.p14_levantamentopatrimonial";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = levantamentopatrimonial.p13_departamento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p14_sequencial)) {
         $sql2 .= " where levantamentopatrimonialbens.p14_sequencial = $p14_sequencial "; 
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
   public function sql_query_file ($p14_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from levantamentopatrimonialbens ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p14_sequencial)){
         $sql2 .= " where levantamentopatrimonialbens.p14_sequencial = $p14_sequencial "; 
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
