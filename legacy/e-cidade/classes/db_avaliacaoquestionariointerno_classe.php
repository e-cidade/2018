<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE avaliacaoquestionariointerno
class cl_avaliacaoquestionariointerno { 
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
   var $db170_sequencial = 0; 
   var $db170_avaliacao = 0; 
   var $db170_transmitido = 'f'; 
   var $db170_ativo = 'f'; 
   var $db170_codigo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db170_sequencial = int4 = Cadastro sequencial 
                 db170_avaliacao = int4 = Código da Avaliação 
                 db170_transmitido = bool = Transmitido 
                 db170_ativo = bool = Ativo 
                 db170_codigo = int4 = Codigo do Questionario Externo 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoquestionariointerno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoquestionariointerno"); 
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
       $this->db170_sequencial = ($this->db170_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db170_sequencial"]:$this->db170_sequencial);
       $this->db170_avaliacao = ($this->db170_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["db170_avaliacao"]:$this->db170_avaliacao);
       $this->db170_transmitido = ($this->db170_transmitido == "f"?@$GLOBALS["HTTP_POST_VARS"]["db170_transmitido"]:$this->db170_transmitido);
       $this->db170_ativo = ($this->db170_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["db170_ativo"]:$this->db170_ativo);
       $this->db170_codigo = ($this->db170_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db170_codigo"]:$this->db170_codigo);
     }else{
       $this->db170_sequencial = ($this->db170_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db170_sequencial"]:$this->db170_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db170_sequencial){ 
      $this->atualizacampos();
     if($this->db170_avaliacao == null ){ 
       $this->erro_sql = " Campo Código da Avaliação não informado.";
       $this->erro_campo = "db170_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db170_transmitido == null ){ 
       $this->db170_transmitido = "f";
     }
     if($this->db170_ativo == null ){ 
       $this->db170_ativo = "1";
     }
     if($this->db170_codigo == null ){ 
       $this->db170_codigo = "0";
     }
     if($db170_sequencial == "" || $db170_sequencial == null ){
       $result = db_query("select nextval('avaliacaoquestionariointerno_db170_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoquestionariointerno_db170_sequencial_seq do campo: db170_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db170_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoquestionariointerno_db170_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db170_sequencial)){
         $this->erro_sql = " Campo db170_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db170_sequencial = $db170_sequencial; 
       }
     }
     if(($this->db170_sequencial == null) || ($this->db170_sequencial == "") ){ 
       $this->erro_sql = " Campo db170_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoquestionariointerno(
                                       db170_sequencial 
                                      ,db170_avaliacao 
                                      ,db170_transmitido 
                                      ,db170_ativo 
                                      ,db170_codigo 
                       )
                values (
                                $this->db170_sequencial 
                               ,$this->db170_avaliacao 
                               ,'$this->db170_transmitido' 
                               ,'$this->db170_ativo' 
                               ,$this->db170_codigo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "avaliacaoquestionariointerno ($this->db170_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "avaliacaoquestionariointerno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "avaliacaoquestionariointerno ($this->db170_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db170_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db170_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22024,'$this->db170_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3964,22024,'','".AddSlashes(pg_result($resaco,0,'db170_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3964,22025,'','".AddSlashes(pg_result($resaco,0,'db170_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3964,22023,'','".AddSlashes(pg_result($resaco,0,'db170_transmitido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3964,22022,'','".AddSlashes(pg_result($resaco,0,'db170_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3964,22045,'','".AddSlashes(pg_result($resaco,0,'db170_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db170_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoquestionariointerno set ";
     $virgula = "";
     if(trim($this->db170_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db170_sequencial"])){ 
       $sql  .= $virgula." db170_sequencial = $this->db170_sequencial ";
       $virgula = ",";
       if(trim($this->db170_sequencial) == null ){ 
         $this->erro_sql = " Campo Cadastro sequencial não informado.";
         $this->erro_campo = "db170_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db170_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db170_avaliacao"])){ 
       $sql  .= $virgula." db170_avaliacao = $this->db170_avaliacao ";
       $virgula = ",";
       if(trim($this->db170_avaliacao) == null ){ 
         $this->erro_sql = " Campo Código da Avaliação não informado.";
         $this->erro_campo = "db170_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db170_transmitido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db170_transmitido"])){ 
       $sql  .= $virgula." db170_transmitido = '$this->db170_transmitido' ";
       $virgula = ",";
     }
     if(trim($this->db170_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db170_ativo"])){ 
       $sql  .= $virgula." db170_ativo = '$this->db170_ativo' ";
       $virgula = ",";
     }
     if(trim($this->db170_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db170_codigo"])){ 
        if(trim($this->db170_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db170_codigo"])){ 
           $this->db170_codigo = "0" ; 
        } 
       $sql  .= $virgula." db170_codigo = $this->db170_codigo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db170_sequencial!=null){
       $sql .= " db170_sequencial = $this->db170_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db170_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22024,'$this->db170_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db170_sequencial"]) || $this->db170_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3964,22024,'".AddSlashes(pg_result($resaco,$conresaco,'db170_sequencial'))."','$this->db170_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db170_avaliacao"]) || $this->db170_avaliacao != "")
             $resac = db_query("insert into db_acount values($acount,3964,22025,'".AddSlashes(pg_result($resaco,$conresaco,'db170_avaliacao'))."','$this->db170_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db170_transmitido"]) || $this->db170_transmitido != "")
             $resac = db_query("insert into db_acount values($acount,3964,22023,'".AddSlashes(pg_result($resaco,$conresaco,'db170_transmitido'))."','$this->db170_transmitido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db170_ativo"]) || $this->db170_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3964,22022,'".AddSlashes(pg_result($resaco,$conresaco,'db170_ativo'))."','$this->db170_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db170_codigo"]) || $this->db170_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3964,22045,'".AddSlashes(pg_result($resaco,$conresaco,'db170_codigo'))."','$this->db170_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "avaliacaoquestionariointerno não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db170_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "avaliacaoquestionariointerno não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db170_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db170_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22024,'$db170_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3964,22024,'','".AddSlashes(pg_result($resaco,$iresaco,'db170_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3964,22025,'','".AddSlashes(pg_result($resaco,$iresaco,'db170_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3964,22023,'','".AddSlashes(pg_result($resaco,$iresaco,'db170_transmitido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3964,22022,'','".AddSlashes(pg_result($resaco,$iresaco,'db170_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3964,22045,'','".AddSlashes(pg_result($resaco,$iresaco,'db170_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoquestionariointerno
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db170_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db170_sequencial = $db170_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "avaliacaoquestionariointerno não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db170_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "avaliacaoquestionariointerno não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db170_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoquestionariointerno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db170_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaoquestionariointerno ";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaoquestionariointerno.db170_avaliacao";
     $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db170_sequencial)) {
         $sql2 .= " where avaliacaoquestionariointerno.db170_sequencial = $db170_sequencial "; 
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
   public function sql_query_file ($db170_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaoquestionariointerno ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db170_sequencial)){
         $sql2 .= " where avaliacaoquestionariointerno.db170_sequencial = $db170_sequencial "; 
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
