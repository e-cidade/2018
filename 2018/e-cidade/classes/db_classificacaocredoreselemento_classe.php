<?
//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredoreselemento
class cl_classificacaocredoreselemento { 
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
   var $cc32_sequencial = 0; 
   var $cc32_classificacaocredores = 0; 
   var $cc32_codcon = 0; 
   var $cc32_anousu = 0; 
   var $cc32_exclusao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc32_sequencial = int4 = C?digo 
                 cc32_classificacaocredores = int4 = Lista 
                 cc32_codcon = int4 = Conta 
                 cc32_anousu = int4 = Conta 
                 cc32_exclusao = bool = Exclus?o 
                 ";
   //funcao construtor da classe 
   function cl_classificacaocredoreselemento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredoreselemento"); 
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
       $this->cc32_sequencial = ($this->cc32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc32_sequencial"]:$this->cc32_sequencial);
       $this->cc32_classificacaocredores = ($this->cc32_classificacaocredores == ""?@$GLOBALS["HTTP_POST_VARS"]["cc32_classificacaocredores"]:$this->cc32_classificacaocredores);
       $this->cc32_codcon = ($this->cc32_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["cc32_codcon"]:$this->cc32_codcon);
       $this->cc32_anousu = ($this->cc32_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["cc32_anousu"]:$this->cc32_anousu);
       $this->cc32_exclusao = ($this->cc32_exclusao == "f"?@$GLOBALS["HTTP_POST_VARS"]["cc32_exclusao"]:$this->cc32_exclusao);
     }else{
       $this->cc32_sequencial = ($this->cc32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc32_sequencial"]:$this->cc32_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($cc32_sequencial){ 
      $this->atualizacampos();
     if($this->cc32_classificacaocredores == null ){ 
       $this->erro_sql = " Campo Lista não informado.";
       $this->erro_campo = "cc32_classificacaocredores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc32_codcon == null ){ 
       $this->erro_sql = " Campo Conta não informado.";
       $this->erro_campo = "cc32_codcon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc32_anousu == null ){ 
       $this->erro_sql = " Campo Conta não informado.";
       $this->erro_campo = "cc32_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc32_exclusao == null ){ 
       $this->erro_sql = " Campo Exclus?o não informado.";
       $this->erro_campo = "cc32_exclusao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc32_sequencial == "" || $cc32_sequencial == null ){
       $result = db_query("select nextval('classificacaocredoreselemento_cc32_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredoreselemento_cc32_sequencial_seq do campo: cc32_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc32_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from classificacaocredoreselemento_cc32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc32_sequencial)){
         $this->erro_sql = " Campo cc32_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc32_sequencial = $cc32_sequencial; 
       }
     }
     if(($this->cc32_sequencial == null) || ($this->cc32_sequencial == "") ){ 
       $this->erro_sql = " Campo cc32_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredoreselemento(
                                       cc32_sequencial 
                                      ,cc32_classificacaocredores 
                                      ,cc32_codcon 
                                      ,cc32_anousu 
                                      ,cc32_exclusao 
                       )
                values (
                                $this->cc32_sequencial 
                               ,$this->cc32_classificacaocredores 
                               ,$this->cc32_codcon 
                               ,$this->cc32_anousu 
                               ,'$this->cc32_exclusao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "classificacaocredoreselemento ($this->cc32_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "classificacaocredoreselemento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "classificacaocredoreselemento ($this->cc32_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc32_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc32_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21877,'$this->cc32_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3939,21877,'','".AddSlashes(pg_result($resaco,0,'cc32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3939,21878,'','".AddSlashes(pg_result($resaco,0,'cc32_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3939,21879,'','".AddSlashes(pg_result($resaco,0,'cc32_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3939,21883,'','".AddSlashes(pg_result($resaco,0,'cc32_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3939,21885,'','".AddSlashes(pg_result($resaco,0,'cc32_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($cc32_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update classificacaocredoreselemento set ";
     $virgula = "";
     if(trim($this->cc32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc32_sequencial"])){ 
       $sql  .= $virgula." cc32_sequencial = $this->cc32_sequencial ";
       $virgula = ",";
       if(trim($this->cc32_sequencial) == null ){ 
         $this->erro_sql = " Campo C?digo não informado.";
         $this->erro_campo = "cc32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc32_classificacaocredores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc32_classificacaocredores"])){ 
       $sql  .= $virgula." cc32_classificacaocredores = $this->cc32_classificacaocredores ";
       $virgula = ",";
       if(trim($this->cc32_classificacaocredores) == null ){ 
         $this->erro_sql = " Campo Lista não informado.";
         $this->erro_campo = "cc32_classificacaocredores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc32_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc32_codcon"])){ 
       $sql  .= $virgula." cc32_codcon = $this->cc32_codcon ";
       $virgula = ",";
       if(trim($this->cc32_codcon) == null ){ 
         $this->erro_sql = " Campo Conta não informado.";
         $this->erro_campo = "cc32_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc32_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc32_anousu"])){ 
       $sql  .= $virgula." cc32_anousu = $this->cc32_anousu ";
       $virgula = ",";
       if(trim($this->cc32_anousu) == null ){ 
         $this->erro_sql = " Campo Conta não informado.";
         $this->erro_campo = "cc32_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc32_exclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc32_exclusao"])){ 
       $sql  .= $virgula." cc32_exclusao = '$this->cc32_exclusao' ";
       $virgula = ",";
       if(trim($this->cc32_exclusao) == null ){ 
         $this->erro_sql = " Campo Exclus?o não informado.";
         $this->erro_campo = "cc32_exclusao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc32_sequencial!=null){
       $sql .= " cc32_sequencial = $this->cc32_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc32_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21877,'$this->cc32_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc32_sequencial"]) || $this->cc32_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3939,21877,'".AddSlashes(pg_result($resaco,$conresaco,'cc32_sequencial'))."','$this->cc32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc32_classificacaocredores"]) || $this->cc32_classificacaocredores != "")
             $resac = db_query("insert into db_acount values($acount,3939,21878,'".AddSlashes(pg_result($resaco,$conresaco,'cc32_classificacaocredores'))."','$this->cc32_classificacaocredores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc32_codcon"]) || $this->cc32_codcon != "")
             $resac = db_query("insert into db_acount values($acount,3939,21879,'".AddSlashes(pg_result($resaco,$conresaco,'cc32_codcon'))."','$this->cc32_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc32_anousu"]) || $this->cc32_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3939,21883,'".AddSlashes(pg_result($resaco,$conresaco,'cc32_anousu'))."','$this->cc32_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc32_exclusao"]) || $this->cc32_exclusao != "")
             $resac = db_query("insert into db_acount values($acount,3939,21885,'".AddSlashes(pg_result($resaco,$conresaco,'cc32_exclusao'))."','$this->cc32_exclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoreselemento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoreselemento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($cc32_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc32_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21877,'$cc32_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3939,21877,'','".AddSlashes(pg_result($resaco,$iresaco,'cc32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3939,21878,'','".AddSlashes(pg_result($resaco,$iresaco,'cc32_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3939,21879,'','".AddSlashes(pg_result($resaco,$iresaco,'cc32_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3939,21883,'','".AddSlashes(pg_result($resaco,$iresaco,'cc32_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3939,21885,'','".AddSlashes(pg_result($resaco,$iresaco,'cc32_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredoreselemento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc32_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc32_sequencial = $cc32_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "classificacaocredoreselemento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "classificacaocredoreselemento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredoreselemento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($cc32_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredoreselemento ";
     $sql .= "      inner join classificacaocredores  on  classificacaocredores.cc30_codigo = classificacaocredoreselemento.cc32_classificacaocredores";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc32_sequencial)) {
         $sql2 .= " where classificacaocredoreselemento.cc32_sequencial = $cc32_sequencial "; 
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
   public function sql_query_file ($cc32_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredoreselemento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc32_sequencial)){
         $sql2 .= " where classificacaocredoreselemento.cc32_sequencial = $cc32_sequencial "; 
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
