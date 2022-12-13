<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordodocumentoevento
class cl_acordodocumentoevento { 
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
   var $ac57_sequencial = 0; 
   var $ac57_acordoevento = 0; 
   var $ac57_acordodocumento = 0; 
   var $ac57_tipodocumento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac57_sequencial = int4 = Sequencial 
                 ac57_acordoevento = int4 = Evento 
                 ac57_acordodocumento = int4 = Documento 
                 ac57_tipodocumento = int4 = Tipo do Documento 
                 ";
   //funcao construtor da classe 
   function cl_acordodocumentoevento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordodocumentoevento"); 
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
       $this->ac57_sequencial = ($this->ac57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac57_sequencial"]:$this->ac57_sequencial);
       $this->ac57_acordoevento = ($this->ac57_acordoevento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac57_acordoevento"]:$this->ac57_acordoevento);
       $this->ac57_acordodocumento = ($this->ac57_acordodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac57_acordodocumento"]:$this->ac57_acordodocumento);
       $this->ac57_tipodocumento = ($this->ac57_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["ac57_tipodocumento"]:$this->ac57_tipodocumento);
     }else{
       $this->ac57_sequencial = ($this->ac57_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac57_sequencial"]:$this->ac57_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ac57_sequencial){ 
      $this->atualizacampos();
     if($this->ac57_acordoevento == null ){ 
       $this->erro_sql = " Campo Evento não informado.";
       $this->erro_campo = "ac57_acordoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac57_acordodocumento == null ){ 
       $this->erro_sql = " Campo Documento não informado.";
       $this->erro_campo = "ac57_acordodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac57_tipodocumento == null ){ 
       $this->erro_sql = " Campo Tipo do Documento não informado.";
       $this->erro_campo = "ac57_tipodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac57_sequencial == "" || $ac57_sequencial == null ){
       $result = db_query("select nextval('acordodocumentoevento_ac57_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordodocumentoevento_ac57_sequencial_seq do campo: ac57_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac57_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordodocumentoevento_ac57_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac57_sequencial)){
         $this->erro_sql = " Campo ac57_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac57_sequencial = $ac57_sequencial; 
       }
     }
     if(($this->ac57_sequencial == null) || ($this->ac57_sequencial == "") ){ 
       $this->erro_sql = " Campo ac57_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordodocumentoevento(
                                       ac57_sequencial 
                                      ,ac57_acordoevento 
                                      ,ac57_acordodocumento 
                                      ,ac57_tipodocumento 
                       )
                values (
                                $this->ac57_sequencial 
                               ,$this->ac57_acordoevento 
                               ,$this->ac57_acordodocumento 
                               ,$this->ac57_tipodocumento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos dos acordos ($this->ac57_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos dos acordos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos dos acordos ($this->ac57_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac57_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac57_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21832,'$this->ac57_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3929,21832,'','".AddSlashes(pg_result($resaco,0,'ac57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3929,21833,'','".AddSlashes(pg_result($resaco,0,'ac57_acordoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3929,21834,'','".AddSlashes(pg_result($resaco,0,'ac57_acordodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3929,21835,'','".AddSlashes(pg_result($resaco,0,'ac57_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ac57_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordodocumentoevento set ";
     $virgula = "";
     if(trim($this->ac57_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac57_sequencial"])){ 
       $sql  .= $virgula." ac57_sequencial = $this->ac57_sequencial ";
       $virgula = ",";
       if(trim($this->ac57_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ac57_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac57_acordoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac57_acordoevento"])){ 
       $sql  .= $virgula." ac57_acordoevento = $this->ac57_acordoevento ";
       $virgula = ",";
       if(trim($this->ac57_acordoevento) == null ){ 
         $this->erro_sql = " Campo Evento não informado.";
         $this->erro_campo = "ac57_acordoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac57_acordodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac57_acordodocumento"])){ 
       $sql  .= $virgula." ac57_acordodocumento = $this->ac57_acordodocumento ";
       $virgula = ",";
       if(trim($this->ac57_acordodocumento) == null ){ 
         $this->erro_sql = " Campo Documento não informado.";
         $this->erro_campo = "ac57_acordodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac57_tipodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac57_tipodocumento"])){ 
       $sql  .= $virgula." ac57_tipodocumento = $this->ac57_tipodocumento ";
       $virgula = ",";
       if(trim($this->ac57_tipodocumento) == null ){ 
         $this->erro_sql = " Campo Tipo do Documento não informado.";
         $this->erro_campo = "ac57_tipodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac57_sequencial!=null){
       $sql .= " ac57_sequencial = $this->ac57_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac57_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21832,'$this->ac57_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac57_sequencial"]) || $this->ac57_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3929,21832,'".AddSlashes(pg_result($resaco,$conresaco,'ac57_sequencial'))."','$this->ac57_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac57_acordoevento"]) || $this->ac57_acordoevento != "")
             $resac = db_query("insert into db_acount values($acount,3929,21833,'".AddSlashes(pg_result($resaco,$conresaco,'ac57_acordoevento'))."','$this->ac57_acordoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac57_acordodocumento"]) || $this->ac57_acordodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3929,21834,'".AddSlashes(pg_result($resaco,$conresaco,'ac57_acordodocumento'))."','$this->ac57_acordodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac57_tipodocumento"]) || $this->ac57_tipodocumento != "")
             $resac = db_query("insert into db_acount values($acount,3929,21835,'".AddSlashes(pg_result($resaco,$conresaco,'ac57_tipodocumento'))."','$this->ac57_tipodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos dos acordos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos dos acordos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ac57_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac57_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21832,'$ac57_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3929,21832,'','".AddSlashes(pg_result($resaco,$iresaco,'ac57_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3929,21833,'','".AddSlashes(pg_result($resaco,$iresaco,'ac57_acordoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3929,21834,'','".AddSlashes(pg_result($resaco,$iresaco,'ac57_acordodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3929,21835,'','".AddSlashes(pg_result($resaco,$iresaco,'ac57_tipodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordodocumentoevento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac57_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac57_sequencial = $ac57_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos dos acordos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac57_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos dos acordos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac57_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac57_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordodocumentoevento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ac57_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from acordodocumentoevento ";
     $sql .= "      inner join acordodocumento  on  acordodocumento.ac40_sequencial = acordodocumentoevento.ac57_acordodocumento";
     $sql .= "      inner join acordoevento  on  acordoevento.ac55_sequencial = acordodocumentoevento.ac57_acordoevento";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordodocumento.ac40_acordo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoevento.ac55_acordo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac57_sequencial)) {
         $sql2 .= " where acordodocumentoevento.ac57_sequencial = $ac57_sequencial "; 
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
   public function sql_query_file ($ac57_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordodocumentoevento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac57_sequencial)){
         $sql2 .= " where acordodocumentoevento.ac57_sequencial = $ac57_sequencial "; 
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
