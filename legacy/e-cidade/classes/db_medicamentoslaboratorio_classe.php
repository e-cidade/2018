<?
//MODULO: laboratorio
//CLASSE DA ENTIDADE medicamentoslaboratorio
class cl_medicamentoslaboratorio { 
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
   var $la43_sequencial = 0; 
   var $la43_nome = null; 
   var $la43_abreviatura = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la43_sequencial = int4 = Código 
                 la43_nome = varchar(50) = Nome 
                 la43_abreviatura = varchar(3) = Abreviatura 
                 ";
   //funcao construtor da classe 
   function cl_medicamentoslaboratorio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicamentoslaboratorio"); 
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
       $this->la43_sequencial = ($this->la43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la43_sequencial"]:$this->la43_sequencial);
       $this->la43_nome = ($this->la43_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["la43_nome"]:$this->la43_nome);
       $this->la43_abreviatura = ($this->la43_abreviatura == ""?@$GLOBALS["HTTP_POST_VARS"]["la43_abreviatura"]:$this->la43_abreviatura);
     }else{
       $this->la43_sequencial = ($this->la43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la43_sequencial"]:$this->la43_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($la43_sequencial){ 
      $this->atualizacampos();
     if($this->la43_nome == null ){ 
       $this->erro_sql = " Campo Nome não informado.";
       $this->erro_campo = "la43_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la43_abreviatura == null ){ 
       $this->erro_sql = " Campo Abreviatura não informado.";
       $this->erro_campo = "la43_abreviatura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la43_sequencial == "" || $la43_sequencial == null ){
       $result = db_query("select nextval('medicamentoslaboratorio_la43_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: medicamentoslaboratorio_la43_sequencial_seq do campo: la43_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la43_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from medicamentoslaboratorio_la43_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $la43_sequencial)){
         $this->erro_sql = " Campo la43_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la43_sequencial = $la43_sequencial; 
       }
     }
     if(($this->la43_sequencial == null) || ($this->la43_sequencial == "") ){ 
       $this->erro_sql = " Campo la43_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicamentoslaboratorio(
                                       la43_sequencial 
                                      ,la43_nome 
                                      ,la43_abreviatura 
                       )
                values (
                                $this->la43_sequencial 
                               ,'$this->la43_nome' 
                               ,'$this->la43_abreviatura' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Medicamentos do Laboratorio ($this->la43_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Medicamentos do Laboratorio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Medicamentos do Laboratorio ($this->la43_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la43_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la43_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21628,'$this->la43_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3885,21628,'','".AddSlashes(pg_result($resaco,0,'la43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3885,21629,'','".AddSlashes(pg_result($resaco,0,'la43_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3885,21630,'','".AddSlashes(pg_result($resaco,0,'la43_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($la43_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update medicamentoslaboratorio set ";
     $virgula = "";
     if(trim($this->la43_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la43_sequencial"])){ 
       $sql  .= $virgula." la43_sequencial = $this->la43_sequencial ";
       $virgula = ",";
       if(trim($this->la43_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la43_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la43_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la43_nome"])){ 
       $sql  .= $virgula." la43_nome = '$this->la43_nome' ";
       $virgula = ",";
       if(trim($this->la43_nome) == null ){ 
         $this->erro_sql = " Campo Nome não informado.";
         $this->erro_campo = "la43_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la43_abreviatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la43_abreviatura"])){ 
       $sql  .= $virgula." la43_abreviatura = '$this->la43_abreviatura' ";
       $virgula = ",";
       if(trim($this->la43_abreviatura) == null ){ 
         $this->erro_sql = " Campo Abreviatura não informado.";
         $this->erro_campo = "la43_abreviatura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la43_sequencial!=null){
       $sql .= " la43_sequencial = $this->la43_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la43_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21628,'$this->la43_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la43_sequencial"]) || $this->la43_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3885,21628,'".AddSlashes(pg_result($resaco,$conresaco,'la43_sequencial'))."','$this->la43_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la43_nome"]) || $this->la43_nome != "")
             $resac = db_query("insert into db_acount values($acount,3885,21629,'".AddSlashes(pg_result($resaco,$conresaco,'la43_nome'))."','$this->la43_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la43_abreviatura"]) || $this->la43_abreviatura != "")
             $resac = db_query("insert into db_acount values($acount,3885,21630,'".AddSlashes(pg_result($resaco,$conresaco,'la43_abreviatura'))."','$this->la43_abreviatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamentos do Laboratorio não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamentos do Laboratorio não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($la43_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la43_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21628,'$la43_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3885,21628,'','".AddSlashes(pg_result($resaco,$iresaco,'la43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3885,21629,'','".AddSlashes(pg_result($resaco,$iresaco,'la43_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3885,21630,'','".AddSlashes(pg_result($resaco,$iresaco,'la43_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from medicamentoslaboratorio
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la43_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la43_sequencial = $la43_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamentos do Laboratorio não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamentos do Laboratorio não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la43_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:medicamentoslaboratorio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($la43_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from medicamentoslaboratorio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la43_sequencial)) {
         $sql2 .= " where medicamentoslaboratorio.la43_sequencial = $la43_sequencial "; 
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
   public function sql_query_file ($la43_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from medicamentoslaboratorio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la43_sequencial)){
         $sql2 .= " where medicamentoslaboratorio.la43_sequencial = $la43_sequencial "; 
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
