<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE avaliacaoquestionariointernomenu
class cl_avaliacaoquestionariointernomenu { 
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
   var $db171_sequencial = 0; 
   var $db171_questionario = 0; 
   var $db171_menu = 0; 
   var $db171_modulo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db171_sequencial = int4 = Cadastro sequencial 
                 db171_questionario = int4 = Questionário Interno 
                 db171_menu = int4 = Código do Menu 
                 db171_modulo = int4 = Código do Módulo 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoquestionariointernomenu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoquestionariointernomenu"); 
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
       $this->db171_sequencial = ($this->db171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db171_sequencial"]:$this->db171_sequencial);
       $this->db171_questionario = ($this->db171_questionario == ""?@$GLOBALS["HTTP_POST_VARS"]["db171_questionario"]:$this->db171_questionario);
       $this->db171_menu = ($this->db171_menu == ""?@$GLOBALS["HTTP_POST_VARS"]["db171_menu"]:$this->db171_menu);
       $this->db171_modulo = ($this->db171_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["db171_modulo"]:$this->db171_modulo);
     }else{
       $this->db171_sequencial = ($this->db171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db171_sequencial"]:$this->db171_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db171_sequencial){ 
      $this->atualizacampos();
     if($this->db171_questionario == null ){ 
       $this->erro_sql = " Campo Questionário Interno não informado.";
       $this->erro_campo = "db171_questionario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db171_menu == null ){ 
       $this->db171_menu = "0";
     }
     if($this->db171_modulo == null ){ 
       $this->erro_sql = " Campo Código do Módulo não informado.";
       $this->erro_campo = "db171_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db171_sequencial == "" || $db171_sequencial == null ){
       $result = db_query("select nextval('avaliacaoquestionariointernomenu_db171_sequencial_seq')"); 
 
       if($result==false){
         $this->erro_banco  = str_replace("\n","",@pg_last_error());
         $this->erro_sql    = "Verifique o cadastro da sequencia: avaliacaoquestionariointernomenu_db171_sequencial_seq do campo: db171_sequencial"; 
         $this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db171_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoquestionariointernomenu_db171_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db171_sequencial)){
         $this->erro_sql = " Campo db171_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db171_sequencial = $db171_sequencial; 
       }
     }
     if(($this->db171_sequencial == null) || ($this->db171_sequencial == "") ){ 
       $this->erro_sql = " Campo db171_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoquestionariointernomenu(
                                       db171_sequencial 
                                      ,db171_questionario 
                                      ,db171_menu 
                                      ,db171_modulo 
                       )
                values (
                                $this->db171_sequencial 
                               ,$this->db171_questionario 
                               ,$this->db171_menu 
                               ,$this->db171_modulo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "avaliacaoquestionariointernomenu ($this->db171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "avaliacaoquestionariointernomenu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "avaliacaoquestionariointernomenu ($this->db171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db171_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db171_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22027,'$this->db171_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3965,22027,'','".AddSlashes(pg_result($resaco,0,'db171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3965,22028,'','".AddSlashes(pg_result($resaco,0,'db171_questionario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3965,22026,'','".AddSlashes(pg_result($resaco,0,'db171_menu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3965,22029,'','".AddSlashes(pg_result($resaco,0,'db171_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db171_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoquestionariointernomenu set ";
     $virgula = "";
     if(trim($this->db171_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db171_sequencial"])){ 
       $sql  .= $virgula." db171_sequencial = $this->db171_sequencial ";
       $virgula = ",";
       if(trim($this->db171_sequencial) == null ){ 
         $this->erro_sql = " Campo Cadastro sequencial não informado.";
         $this->erro_campo = "db171_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db171_questionario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db171_questionario"])){ 
       $sql  .= $virgula." db171_questionario = $this->db171_questionario ";
       $virgula = ",";
       if(trim($this->db171_questionario) == null ){ 
         $this->erro_sql = " Campo Questionário Interno não informado.";
         $this->erro_campo = "db171_questionario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db171_menu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db171_menu"])){ 
        if(trim($this->db171_menu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db171_menu"])){ 
           $this->db171_menu = "0" ; 
        } 
       $sql  .= $virgula." db171_menu = $this->db171_menu ";
       $virgula = ",";
     }
     if(trim($this->db171_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db171_modulo"])){ 
       $sql  .= $virgula." db171_modulo = $this->db171_modulo ";
       $virgula = ",";
       if(trim($this->db171_modulo) == null ){ 
         $this->erro_sql = " Campo Código do Módulo não informado.";
         $this->erro_campo = "db171_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db171_sequencial!=null){
       $sql .= " db171_sequencial = $this->db171_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db171_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22027,'$this->db171_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db171_sequencial"]) || $this->db171_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3965,22027,'".AddSlashes(pg_result($resaco,$conresaco,'db171_sequencial'))."','$this->db171_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db171_questionario"]) || $this->db171_questionario != "")
             $resac = db_query("insert into db_acount values($acount,3965,22028,'".AddSlashes(pg_result($resaco,$conresaco,'db171_questionario'))."','$this->db171_questionario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db171_menu"]) || $this->db171_menu != "")
             $resac = db_query("insert into db_acount values($acount,3965,22026,'".AddSlashes(pg_result($resaco,$conresaco,'db171_menu'))."','$this->db171_menu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db171_modulo"]) || $this->db171_modulo != "")
             $resac = db_query("insert into db_acount values($acount,3965,22029,'".AddSlashes(pg_result($resaco,$conresaco,'db171_modulo'))."','$this->db171_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "avaliacaoquestionariointernomenu não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "avaliacaoquestionariointernomenu não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db171_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db171_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22027,'$db171_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3965,22027,'','".AddSlashes(pg_result($resaco,$iresaco,'db171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3965,22028,'','".AddSlashes(pg_result($resaco,$iresaco,'db171_questionario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3965,22026,'','".AddSlashes(pg_result($resaco,$iresaco,'db171_menu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3965,22029,'','".AddSlashes(pg_result($resaco,$iresaco,'db171_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoquestionariointernomenu
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db171_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db171_sequencial = $db171_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "avaliacaoquestionariointernomenu não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "avaliacaoquestionariointernomenu não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db171_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoquestionariointernomenu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db171_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaoquestionariointernomenu ";
     $sql .= "      inner join avaliacaoquestionariointerno  on  avaliacaoquestionariointerno.db170_sequencial = avaliacaoquestionariointernomenu.db171_questionario";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaoquestionariointerno.db170_avaliacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db171_sequencial)) {
         $sql2 .= " where avaliacaoquestionariointernomenu.db171_sequencial = $db171_sequencial "; 
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
   public function sql_query_file ($db171_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaoquestionariointernomenu ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db171_sequencial)){
         $sql2 .= " where avaliacaoquestionariointernomenu.db171_sequencial = $db171_sequencial "; 
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
