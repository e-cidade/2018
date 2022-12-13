<?
//MODULO: pessoal
//CLASSE DA ENTIDADE loteregistropontorhfolhapagamento
class cl_loteregistropontorhfolhapagamento { 
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
   var $rh162_sequencial = 0; 
   var $rh162_loteregistroponto = 0; 
   var $rh162_rhfolhapagamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh162_sequencial = int4 = Sequencial da tabela 
                 rh162_loteregistroponto = int4 = Lote 
                 rh162_rhfolhapagamento = int4 = Folha de Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_loteregistropontorhfolhapagamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("loteregistropontorhfolhapagamento"); 
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
       $this->rh162_sequencial = ($this->rh162_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh162_sequencial"]:$this->rh162_sequencial);
       $this->rh162_loteregistroponto = ($this->rh162_loteregistroponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh162_loteregistroponto"]:$this->rh162_loteregistroponto);
       $this->rh162_rhfolhapagamento = ($this->rh162_rhfolhapagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh162_rhfolhapagamento"]:$this->rh162_rhfolhapagamento);
     }else{
       $this->rh162_sequencial = ($this->rh162_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh162_sequencial"]:$this->rh162_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh162_sequencial){ 
      $this->atualizacampos();
     if($this->rh162_loteregistroponto == null ){ 
       $this->erro_sql = " Campo Lote não informado.";
       $this->erro_campo = "rh162_loteregistroponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh162_rhfolhapagamento == null ){ 
       $this->erro_sql = " Campo Folha de Pagamento não informado.";
       $this->erro_campo = "rh162_rhfolhapagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh162_sequencial == "" || $rh162_sequencial == null ){
       $result = db_query("select nextval('loteregistropontorhfolhapagamento_rh162_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: loteregistropontorhfolhapagamento_rh162_sequencial_seq do campo: rh162_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh162_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from loteregistropontorhfolhapagamento_rh162_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh162_sequencial)){
         $this->erro_sql = " Campo rh162_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh162_sequencial = $rh162_sequencial; 
       }
     }
     if(($this->rh162_sequencial == null) || ($this->rh162_sequencial == "") ){ 
       $this->erro_sql = " Campo rh162_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into loteregistropontorhfolhapagamento(
                                       rh162_sequencial 
                                      ,rh162_loteregistroponto 
                                      ,rh162_rhfolhapagamento 
                       )
                values (
                                $this->rh162_sequencial 
                               ,$this->rh162_loteregistroponto 
                               ,$this->rh162_rhfolhapagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo entre lote e folha de pagamento ($this->rh162_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo entre lote e folha de pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo entre lote e folha de pagamento ($this->rh162_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh162_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh162_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21181,'$this->rh162_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3815,21181,'','".AddSlashes(pg_result($resaco,0,'rh162_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3815,21182,'','".AddSlashes(pg_result($resaco,0,'rh162_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3815,21183,'','".AddSlashes(pg_result($resaco,0,'rh162_rhfolhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh162_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update loteregistropontorhfolhapagamento set ";
     $virgula = "";
     if(trim($this->rh162_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh162_sequencial"])){ 
       $sql  .= $virgula." rh162_sequencial = $this->rh162_sequencial ";
       $virgula = ",";
       if(trim($this->rh162_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh162_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh162_loteregistroponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh162_loteregistroponto"])){ 
       $sql  .= $virgula." rh162_loteregistroponto = $this->rh162_loteregistroponto ";
       $virgula = ",";
       if(trim($this->rh162_loteregistroponto) == null ){ 
         $this->erro_sql = " Campo Lote não informado.";
         $this->erro_campo = "rh162_loteregistroponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh162_rhfolhapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh162_rhfolhapagamento"])){ 
       $sql  .= $virgula." rh162_rhfolhapagamento = $this->rh162_rhfolhapagamento ";
       $virgula = ",";
       if(trim($this->rh162_rhfolhapagamento) == null ){ 
         $this->erro_sql = " Campo Folha de Pagamento não informado.";
         $this->erro_campo = "rh162_rhfolhapagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh162_sequencial!=null){
       $sql .= " rh162_sequencial = $this->rh162_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh162_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21181,'$this->rh162_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh162_sequencial"]) || $this->rh162_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3815,21181,'".AddSlashes(pg_result($resaco,$conresaco,'rh162_sequencial'))."','$this->rh162_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh162_loteregistroponto"]) || $this->rh162_loteregistroponto != "")
             $resac = db_query("insert into db_acount values($acount,3815,21182,'".AddSlashes(pg_result($resaco,$conresaco,'rh162_loteregistroponto'))."','$this->rh162_loteregistroponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh162_rhfolhapagamento"]) || $this->rh162_rhfolhapagamento != "")
             $resac = db_query("insert into db_acount values($acount,3815,21183,'".AddSlashes(pg_result($resaco,$conresaco,'rh162_rhfolhapagamento'))."','$this->rh162_rhfolhapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre lote e folha de pagamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh162_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre lote e folha de pagamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh162_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh162_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21181,'$rh162_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3815,21181,'','".AddSlashes(pg_result($resaco,$iresaco,'rh162_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3815,21182,'','".AddSlashes(pg_result($resaco,$iresaco,'rh162_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3815,21183,'','".AddSlashes(pg_result($resaco,$iresaco,'rh162_rhfolhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from loteregistropontorhfolhapagamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh162_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh162_sequencial = $rh162_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre lote e folha de pagamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh162_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre lote e folha de pagamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh162_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh162_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:loteregistropontorhfolhapagamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh162_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from loteregistropontorhfolhapagamento ";
     $sql .= "      inner join rhfolhapagamento  on  rhfolhapagamento.rh141_sequencial = loteregistropontorhfolhapagamento.rh162_rhfolhapagamento";
     $sql .= "      inner join loteregistroponto  on  loteregistroponto.rh155_sequencial = loteregistropontorhfolhapagamento.rh162_loteregistroponto";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhfolhapagamento.rh141_tipofolha";
     $sql .= "      inner join db_config  on  db_config.codigo = loteregistroponto.rh155_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = loteregistroponto.rh155_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh162_sequencial)) {
         $sql2 .= " where loteregistropontorhfolhapagamento.rh162_sequencial = $rh162_sequencial "; 
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
   public function sql_query_file ($rh162_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from loteregistropontorhfolhapagamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh162_sequencial)){
         $sql2 .= " where loteregistropontorhfolhapagamento.rh162_sequencial = $rh162_sequencial "; 
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
  public function sql_query_join_folha_pagamento($rh162_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from loteregistropontorhfolhapagamento ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($rh162_sequencial)){
        $sql2 .= " where loteregistropontorhfolhapagamento.rh162_sequencial = $rh162_sequencial "; 
      } 
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= " inner join rhfolhapagamento on rh162_rhfolhapagamento = rh141_sequencial";
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

}
