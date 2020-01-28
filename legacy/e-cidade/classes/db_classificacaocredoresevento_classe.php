<?
//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredoresevento
class cl_classificacaocredoresevento { 
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
   var $cc35_sequencial = 0; 
   var $cc35_classificacaocredores = 0; 
   var $cc35_empprestatip = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc35_sequencial = int4 = Código 
                 cc35_classificacaocredores = int4 = Classificação de Credores 
                 cc35_empprestatip = int4 = Tipo de Evento 
                 ";
   //funcao construtor da classe 
   function cl_classificacaocredoresevento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredoresevento"); 
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
       $this->cc35_sequencial = ($this->cc35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc35_sequencial"]:$this->cc35_sequencial);
       $this->cc35_classificacaocredores = ($this->cc35_classificacaocredores == ""?@$GLOBALS["HTTP_POST_VARS"]["cc35_classificacaocredores"]:$this->cc35_classificacaocredores);
       $this->cc35_empprestatip = ($this->cc35_empprestatip == ""?@$GLOBALS["HTTP_POST_VARS"]["cc35_empprestatip"]:$this->cc35_empprestatip);
     }else{
       $this->cc35_sequencial = ($this->cc35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc35_sequencial"]:$this->cc35_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($cc35_sequencial){ 
      $this->atualizacampos();
     if($this->cc35_classificacaocredores == null ){ 
       $this->erro_sql = " Campo Classificação de Credores não informado.";
       $this->erro_campo = "cc35_classificacaocredores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc35_empprestatip == null ){ 
       $this->erro_sql = " Campo Tipo de Evento não informado.";
       $this->erro_campo = "cc35_empprestatip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc35_sequencial == "" || $cc35_sequencial == null ){
       $result = db_query("select nextval('classificacaocredoresevento_cc35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredoresevento_cc35_sequencial_seq do campo: cc35_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaocredoresevento_cc35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc35_sequencial)){
         $this->erro_sql = " Campo cc35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc35_sequencial = $cc35_sequencial; 
       }
     }
     if(($this->cc35_sequencial == null) || ($this->cc35_sequencial == "") ){ 
       $this->erro_sql = " Campo cc35_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredoresevento(
                                       cc35_sequencial 
                                      ,cc35_classificacaocredores 
                                      ,cc35_empprestatip 
                       )
                values (
                                $this->cc35_sequencial 
                               ,$this->cc35_classificacaocredores 
                               ,$this->cc35_empprestatip 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "classificacaocredoresevento ($this->cc35_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "classificacaocredoresevento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "classificacaocredoresevento ($this->cc35_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc35_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21892,'$this->cc35_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3942,21892,'','".AddSlashes(pg_result($resaco,0,'cc35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3942,21893,'','".AddSlashes(pg_result($resaco,0,'cc35_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3942,21894,'','".AddSlashes(pg_result($resaco,0,'cc35_empprestatip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($cc35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update classificacaocredoresevento set ";
     $virgula = "";
     if(trim($this->cc35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc35_sequencial"])){ 
       $sql  .= $virgula." cc35_sequencial = $this->cc35_sequencial ";
       $virgula = ",";
       if(trim($this->cc35_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "cc35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc35_classificacaocredores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc35_classificacaocredores"])){ 
       $sql  .= $virgula." cc35_classificacaocredores = $this->cc35_classificacaocredores ";
       $virgula = ",";
       if(trim($this->cc35_classificacaocredores) == null ){ 
         $this->erro_sql = " Campo Classificação de Credores não informado.";
         $this->erro_campo = "cc35_classificacaocredores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc35_empprestatip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc35_empprestatip"])){ 
       $sql  .= $virgula." cc35_empprestatip = $this->cc35_empprestatip ";
       $virgula = ",";
       if(trim($this->cc35_empprestatip) == null ){ 
         $this->erro_sql = " Campo Tipo de Evento não informado.";
         $this->erro_campo = "cc35_empprestatip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc35_sequencial!=null){
       $sql .= " cc35_sequencial = $this->cc35_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc35_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21892,'$this->cc35_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc35_sequencial"]) || $this->cc35_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3942,21892,'".AddSlashes(pg_result($resaco,$conresaco,'cc35_sequencial'))."','$this->cc35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc35_classificacaocredores"]) || $this->cc35_classificacaocredores != "")
             $resac = db_query("insert into db_acount values($acount,3942,21893,'".AddSlashes(pg_result($resaco,$conresaco,'cc35_classificacaocredores'))."','$this->cc35_classificacaocredores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc35_empprestatip"]) || $this->cc35_empprestatip != "")
             $resac = db_query("insert into db_acount values($acount,3942,21894,'".AddSlashes(pg_result($resaco,$conresaco,'cc35_empprestatip'))."','$this->cc35_empprestatip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoresevento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoresevento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($cc35_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc35_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21892,'$cc35_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3942,21892,'','".AddSlashes(pg_result($resaco,$iresaco,'cc35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3942,21893,'','".AddSlashes(pg_result($resaco,$iresaco,'cc35_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3942,21894,'','".AddSlashes(pg_result($resaco,$iresaco,'cc35_empprestatip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredoresevento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc35_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc35_sequencial = $cc35_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoresevento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoresevento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredoresevento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($cc35_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredoresevento ";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = classificacaocredoresevento.cc35_empprestatip";
     $sql .= "      inner join classificacaocredores  on  classificacaocredores.cc30_codigo = classificacaocredoresevento.cc35_classificacaocredores";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc35_sequencial)) {
         $sql2 .= " where classificacaocredoresevento.cc35_sequencial = $cc35_sequencial "; 
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
   public function sql_query_file ($cc35_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredoresevento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc35_sequencial)){
         $sql2 .= " where classificacaocredoresevento.cc35_sequencial = $cc35_sequencial "; 
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
