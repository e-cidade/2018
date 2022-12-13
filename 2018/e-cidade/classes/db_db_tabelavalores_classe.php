<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE db_tabelavalores
class cl_db_tabelavalores { 
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
   var $db149_sequencial = 0; 
   var $db149_descricao = null; 
   var $db149_db_tabelavalorestipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db149_sequencial = int4 = Código do Identificador 
                 db149_descricao = varchar(100) = Descrição 
                 db149_db_tabelavalorestipo = int4 = Identificador do TIpo 
                 ";
   //funcao construtor da classe 
   function cl_db_tabelavalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_tabelavalores"); 
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
       $this->db149_sequencial = ($this->db149_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db149_sequencial"]:$this->db149_sequencial);
       $this->db149_descricao = ($this->db149_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db149_descricao"]:$this->db149_descricao);
       $this->db149_db_tabelavalorestipo = ($this->db149_db_tabelavalorestipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db149_db_tabelavalorestipo"]:$this->db149_db_tabelavalorestipo);
     }else{
       $this->db149_sequencial = ($this->db149_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db149_sequencial"]:$this->db149_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db149_sequencial){ 
      $this->atualizacampos();
     if($this->db149_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "db149_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db149_db_tabelavalorestipo == null ){ 
       $this->erro_sql = " Campo Identificador do TIpo não informado.";
       $this->erro_campo = "db149_db_tabelavalorestipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db149_sequencial == "" || $db149_sequencial == null ){
       $result = db_query("select nextval('db_tabelavalores_db149_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_tabelavalores_db149_sequencial_seq do campo: db149_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db149_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_tabelavalores_db149_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db149_sequencial)){
         $this->erro_sql = " Campo db149_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db149_sequencial = $db149_sequencial; 
       }
     }
     if(($this->db149_sequencial == null) || ($this->db149_sequencial == "") ){ 
       $this->erro_sql = " Campo db149_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_tabelavalores(
                                       db149_sequencial 
                                      ,db149_descricao 
                                      ,db149_db_tabelavalorestipo 
                       )
                values (
                                $this->db149_sequencial 
                               ,'$this->db149_descricao' 
                               ,$this->db149_db_tabelavalorestipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de Valores ($this->db149_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de Valores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de Valores ($this->db149_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db149_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db149_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21670,'$this->db149_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3893,21670,'','".AddSlashes(pg_result($resaco,0,'db149_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3893,21671,'','".AddSlashes(pg_result($resaco,0,'db149_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3893,21683,'','".AddSlashes(pg_result($resaco,0,'db149_db_tabelavalorestipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db149_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_tabelavalores set ";
     $virgula = "";
     if(trim($this->db149_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db149_sequencial"])){ 
       $sql  .= $virgula." db149_sequencial = $this->db149_sequencial ";
       $virgula = ",";
       if(trim($this->db149_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Identificador não informado.";
         $this->erro_campo = "db149_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db149_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db149_descricao"])){ 
       $sql  .= $virgula." db149_descricao = '$this->db149_descricao' ";
       $virgula = ",";
       if(trim($this->db149_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "db149_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db149_db_tabelavalorestipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db149_db_tabelavalorestipo"])){ 
       $sql  .= $virgula." db149_db_tabelavalorestipo = $this->db149_db_tabelavalorestipo ";
       $virgula = ",";
       if(trim($this->db149_db_tabelavalorestipo) == null ){ 
         $this->erro_sql = " Campo Identificador do TIpo não informado.";
         $this->erro_campo = "db149_db_tabelavalorestipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db149_sequencial!=null){
       $sql .= " db149_sequencial = $this->db149_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db149_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21670,'$this->db149_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db149_sequencial"]) || $this->db149_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3893,21670,'".AddSlashes(pg_result($resaco,$conresaco,'db149_sequencial'))."','$this->db149_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db149_descricao"]) || $this->db149_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3893,21671,'".AddSlashes(pg_result($resaco,$conresaco,'db149_descricao'))."','$this->db149_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db149_db_tabelavalorestipo"]) || $this->db149_db_tabelavalorestipo != "")
             $resac = db_query("insert into db_acount values($acount,3893,21683,'".AddSlashes(pg_result($resaco,$conresaco,'db149_db_tabelavalorestipo'))."','$this->db149_db_tabelavalorestipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Valores não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db149_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Valores não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db149_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db149_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21670,'$db149_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3893,21670,'','".AddSlashes(pg_result($resaco,$iresaco,'db149_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3893,21671,'','".AddSlashes(pg_result($resaco,$iresaco,'db149_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3893,21683,'','".AddSlashes(pg_result($resaco,$iresaco,'db149_db_tabelavalorestipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_tabelavalores
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db149_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db149_sequencial = $db149_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Valores não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db149_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Valores não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db149_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db149_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_tabelavalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db149_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_tabelavalores ";
     $sql .= "      inner join db_tabelavalorestipo  on  db_tabelavalorestipo.db151_sequencial = db_tabelavalores.db149_db_tabelavalorestipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db149_sequencial)) {
         $sql2 .= " where db_tabelavalores.db149_sequencial = $db149_sequencial "; 
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
   public function sql_query_file ($db149_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_tabelavalores ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db149_sequencial)){
         $sql2 .= " where db_tabelavalores.db149_sequencial = $db149_sequencial "; 
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
