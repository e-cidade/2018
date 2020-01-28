<?
//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredorestipocompra
class cl_classificacaocredorestipocompra { 
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
   var $cc34_sequencial = 0; 
   var $cc34_classificacaocredores = 0; 
   var $cc34_pctipocompra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc34_sequencial = int4 = Código 
                 cc34_classificacaocredores = int4 = Classificação de Credores 
                 cc34_pctipocompra = int4 = Tipo de Compra 
                 ";
   //funcao construtor da classe 
   function cl_classificacaocredorestipocompra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredorestipocompra"); 
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
       $this->cc34_sequencial = ($this->cc34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc34_sequencial"]:$this->cc34_sequencial);
       $this->cc34_classificacaocredores = ($this->cc34_classificacaocredores == ""?@$GLOBALS["HTTP_POST_VARS"]["cc34_classificacaocredores"]:$this->cc34_classificacaocredores);
       $this->cc34_pctipocompra = ($this->cc34_pctipocompra == ""?@$GLOBALS["HTTP_POST_VARS"]["cc34_pctipocompra"]:$this->cc34_pctipocompra);
     }else{
       $this->cc34_sequencial = ($this->cc34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc34_sequencial"]:$this->cc34_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($cc34_sequencial){ 
      $this->atualizacampos();
     if($this->cc34_classificacaocredores == null ){ 
       $this->erro_sql = " Campo Classificação de Credores não informado.";
       $this->erro_campo = "cc34_classificacaocredores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc34_pctipocompra == null ){ 
       $this->erro_sql = " Campo Tipo de Compra não informado.";
       $this->erro_campo = "cc34_pctipocompra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc34_sequencial == "" || $cc34_sequencial == null ){
       $result = db_query("select nextval('classificacaocredorestipocompra_cc34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredorestipocompra_cc34_sequencial_seq do campo: cc34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaocredorestipocompra_cc34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc34_sequencial)){
         $this->erro_sql = " Campo cc34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc34_sequencial = $cc34_sequencial; 
       }
     }
     if(($this->cc34_sequencial == null) || ($this->cc34_sequencial == "") ){ 
       $this->erro_sql = " Campo cc34_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredorestipocompra(
                                       cc34_sequencial 
                                      ,cc34_classificacaocredores 
                                      ,cc34_pctipocompra 
                       )
                values (
                                $this->cc34_sequencial 
                               ,$this->cc34_classificacaocredores 
                               ,$this->cc34_pctipocompra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "classificacaocredorestipocompra ($this->cc34_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "classificacaocredorestipocompra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "classificacaocredorestipocompra ($this->cc34_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc34_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21889,'$this->cc34_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3941,21889,'','".AddSlashes(pg_result($resaco,0,'cc34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3941,21890,'','".AddSlashes(pg_result($resaco,0,'cc34_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3941,21891,'','".AddSlashes(pg_result($resaco,0,'cc34_pctipocompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($cc34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update classificacaocredorestipocompra set ";
     $virgula = "";
     if(trim($this->cc34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc34_sequencial"])){ 
       $sql  .= $virgula." cc34_sequencial = $this->cc34_sequencial ";
       $virgula = ",";
       if(trim($this->cc34_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "cc34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc34_classificacaocredores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc34_classificacaocredores"])){ 
       $sql  .= $virgula." cc34_classificacaocredores = $this->cc34_classificacaocredores ";
       $virgula = ",";
       if(trim($this->cc34_classificacaocredores) == null ){ 
         $this->erro_sql = " Campo Classificação de Credores não informado.";
         $this->erro_campo = "cc34_classificacaocredores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc34_pctipocompra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc34_pctipocompra"])){ 
       $sql  .= $virgula." cc34_pctipocompra = $this->cc34_pctipocompra ";
       $virgula = ",";
       if(trim($this->cc34_pctipocompra) == null ){ 
         $this->erro_sql = " Campo Tipo de Compra não informado.";
         $this->erro_campo = "cc34_pctipocompra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc34_sequencial!=null){
       $sql .= " cc34_sequencial = $this->cc34_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc34_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21889,'$this->cc34_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc34_sequencial"]) || $this->cc34_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3941,21889,'".AddSlashes(pg_result($resaco,$conresaco,'cc34_sequencial'))."','$this->cc34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc34_classificacaocredores"]) || $this->cc34_classificacaocredores != "")
             $resac = db_query("insert into db_acount values($acount,3941,21890,'".AddSlashes(pg_result($resaco,$conresaco,'cc34_classificacaocredores'))."','$this->cc34_classificacaocredores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc34_pctipocompra"]) || $this->cc34_pctipocompra != "")
             $resac = db_query("insert into db_acount values($acount,3941,21891,'".AddSlashes(pg_result($resaco,$conresaco,'cc34_pctipocompra'))."','$this->cc34_pctipocompra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredorestipocompra não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredorestipocompra não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($cc34_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc34_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21889,'$cc34_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3941,21889,'','".AddSlashes(pg_result($resaco,$iresaco,'cc34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3941,21890,'','".AddSlashes(pg_result($resaco,$iresaco,'cc34_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3941,21891,'','".AddSlashes(pg_result($resaco,$iresaco,'cc34_pctipocompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredorestipocompra
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc34_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc34_sequencial = $cc34_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredorestipocompra não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredorestipocompra não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredorestipocompra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($cc34_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredorestipocompra ";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = classificacaocredorestipocompra.cc34_pctipocompra";
     $sql .= "      inner join classificacaocredores  on  classificacaocredores.cc30_codigo = classificacaocredorestipocompra.cc34_classificacaocredores";
     $sql .= "      inner join pctipocompratribunal  on  pctipocompratribunal.l44_sequencial = pctipocompra.pc50_pctipocompratribunal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc34_sequencial)) {
         $sql2 .= " where classificacaocredorestipocompra.cc34_sequencial = $cc34_sequencial "; 
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
   public function sql_query_file ($cc34_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredorestipocompra ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc34_sequencial)){
         $sql2 .= " where classificacaocredorestipocompra.cc34_sequencial = $cc34_sequencial "; 
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
