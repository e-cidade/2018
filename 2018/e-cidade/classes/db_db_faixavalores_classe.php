<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE db_faixavalores
class cl_db_faixavalores { 
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
   var $db150_sequencial = 0; 
   var $db150_db_tabelavalores = 0; 
   var $db150_inicio = 0; 
   var $db150_final = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db150_sequencial = int4 = Código Identificador 
                 db150_db_tabelavalores = int4 = Código do Identificador 
                 db150_inicio = float8 = Inicio da Faixa 
                 db150_final = float8 = Fim da Faixa 
                 ";
   //funcao construtor da classe 
   function cl_db_faixavalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_faixavalores"); 
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
       $this->db150_sequencial = ($this->db150_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db150_sequencial"]:$this->db150_sequencial);
       $this->db150_db_tabelavalores = ($this->db150_db_tabelavalores == ""?@$GLOBALS["HTTP_POST_VARS"]["db150_db_tabelavalores"]:$this->db150_db_tabelavalores);
       $this->db150_inicio = ($this->db150_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["db150_inicio"]:$this->db150_inicio);
       $this->db150_final = ($this->db150_final == ""?@$GLOBALS["HTTP_POST_VARS"]["db150_final"]:$this->db150_final);
     }else{
       $this->db150_sequencial = ($this->db150_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db150_sequencial"]:$this->db150_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db150_sequencial){ 
      $this->atualizacampos();
     if($this->db150_db_tabelavalores == null ){ 
       $this->erro_sql = " Campo Código do Identificador não informado.";
       $this->erro_campo = "db150_db_tabelavalores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db150_inicio == null ){ 
       $this->erro_sql = " Campo Inicio da Faixa não informado.";
       $this->erro_campo = "db150_inicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db150_final == null ){ 
       $this->erro_sql = " Campo Fim da Faixa não informado.";
       $this->erro_campo = "db150_final";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db150_sequencial == "" || $db150_sequencial == null ){
       $result = db_query("select nextval('db_faixavalores_db150_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_faixavalores_db150_sequencial_seq do campo: db150_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db150_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_faixavalores_db150_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db150_sequencial)){
         $this->erro_sql = " Campo db150_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db150_sequencial = $db150_sequencial; 
       }
     }
     if(($this->db150_sequencial == null) || ($this->db150_sequencial == "") ){ 
       $this->erro_sql = " Campo db150_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_faixavalores(
                                       db150_sequencial 
                                      ,db150_db_tabelavalores 
                                      ,db150_inicio 
                                      ,db150_final 
                       )
                values (
                                $this->db150_sequencial 
                               ,$this->db150_db_tabelavalores 
                               ,$this->db150_inicio 
                               ,$this->db150_final 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faixa de Valor ($this->db150_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faixa de Valor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faixa de Valor ($this->db150_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db150_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db150_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21673,'$this->db150_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3894,21673,'','".AddSlashes(pg_result($resaco,0,'db150_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3894,21674,'','".AddSlashes(pg_result($resaco,0,'db150_db_tabelavalores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3894,21675,'','".AddSlashes(pg_result($resaco,0,'db150_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3894,21676,'','".AddSlashes(pg_result($resaco,0,'db150_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db150_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_faixavalores set ";
     $virgula = "";
     if(trim($this->db150_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db150_sequencial"])){ 
       $sql  .= $virgula." db150_sequencial = $this->db150_sequencial ";
       $virgula = ",";
       if(trim($this->db150_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Identificador não informado.";
         $this->erro_campo = "db150_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db150_db_tabelavalores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db150_db_tabelavalores"])){ 
       $sql  .= $virgula." db150_db_tabelavalores = $this->db150_db_tabelavalores ";
       $virgula = ",";
       if(trim($this->db150_db_tabelavalores) == null ){ 
         $this->erro_sql = " Campo Código do Identificador não informado.";
         $this->erro_campo = "db150_db_tabelavalores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db150_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db150_inicio"])){ 
       $sql  .= $virgula." db150_inicio = $this->db150_inicio ";
       $virgula = ",";
       if(trim($this->db150_inicio) == null ){ 
         $this->erro_sql = " Campo Inicio da Faixa não informado.";
         $this->erro_campo = "db150_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db150_final)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db150_final"])){ 
       $sql  .= $virgula." db150_final = $this->db150_final ";
       $virgula = ",";
       if(trim($this->db150_final) == null ){ 
         $this->erro_sql = " Campo Fim da Faixa não informado.";
         $this->erro_campo = "db150_final";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db150_sequencial!=null){
       $sql .= " db150_sequencial = $this->db150_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db150_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21673,'$this->db150_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db150_sequencial"]) || $this->db150_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3894,21673,'".AddSlashes(pg_result($resaco,$conresaco,'db150_sequencial'))."','$this->db150_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db150_db_tabelavalores"]) || $this->db150_db_tabelavalores != "")
             $resac = db_query("insert into db_acount values($acount,3894,21674,'".AddSlashes(pg_result($resaco,$conresaco,'db150_db_tabelavalores'))."','$this->db150_db_tabelavalores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db150_inicio"]) || $this->db150_inicio != "")
             $resac = db_query("insert into db_acount values($acount,3894,21675,'".AddSlashes(pg_result($resaco,$conresaco,'db150_inicio'))."','$this->db150_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db150_final"]) || $this->db150_final != "")
             $resac = db_query("insert into db_acount values($acount,3894,21676,'".AddSlashes(pg_result($resaco,$conresaco,'db150_final'))."','$this->db150_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db150_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db150_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db150_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db150_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db150_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21673,'$db150_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3894,21673,'','".AddSlashes(pg_result($resaco,$iresaco,'db150_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3894,21674,'','".AddSlashes(pg_result($resaco,$iresaco,'db150_db_tabelavalores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3894,21675,'','".AddSlashes(pg_result($resaco,$iresaco,'db150_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3894,21676,'','".AddSlashes(pg_result($resaco,$iresaco,'db150_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_faixavalores
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db150_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db150_sequencial = $db150_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db150_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db150_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db150_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_faixavalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db150_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_faixavalores ";
     $sql .= "      inner join db_tabelavalores  on  db_tabelavalores.db149_sequencial = db_faixavalores.db150_db_tabelavalores";
     $sql .= "      inner join db_tabelavalorestipo  on  db_tabelavalorestipo.db151_sequencial = db_tabelavalores.db149_db_tabelavalorestipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db150_sequencial)) {
         $sql2 .= " where db_faixavalores.db150_sequencial = $db150_sequencial "; 
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
   public function sql_query_file ($db150_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_faixavalores ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db150_sequencial)){
         $sql2 .= " where db_faixavalores.db150_sequencial = $db150_sequencial "; 
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
