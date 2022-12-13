<?
//MODULO: meioambiente
//CLASSE DA ENTIDADE mensagerialicenca
class cl_mensagerialicenca { 
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
   var $am14_sequencial = 0; 
   var $am14_assunto = null; 
   var $am14_mensagem = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 am14_sequencial = int4 = Código Mensagem 
                 am14_assunto = varchar(100) = Assunto 
                 am14_mensagem = text = Mensagem 
                 ";
   //funcao construtor da classe 
   function cl_mensagerialicenca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mensagerialicenca"); 
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
       $this->am14_sequencial = ($this->am14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am14_sequencial"]:$this->am14_sequencial);
       $this->am14_assunto = ($this->am14_assunto == ""?@$GLOBALS["HTTP_POST_VARS"]["am14_assunto"]:$this->am14_assunto);
       $this->am14_mensagem = ($this->am14_mensagem == ""?@$GLOBALS["HTTP_POST_VARS"]["am14_mensagem"]:$this->am14_mensagem);
     }else{
       $this->am14_sequencial = ($this->am14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am14_sequencial"]:$this->am14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am14_sequencial){ 
      $this->atualizacampos();
     if($this->am14_assunto == null ){ 
       $this->erro_sql = " Campo Assunto não informado.";
       $this->erro_campo = "am14_assunto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am14_mensagem == null ){ 
       $this->erro_sql = " Campo Mensagem não informado.";
       $this->erro_campo = "am14_mensagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am14_sequencial == "" || $am14_sequencial == null ){
       $result = db_query("select nextval('mensagerialicenca_am14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mensagerialicenca_am14_sequencial_seq do campo: am14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->am14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mensagerialicenca_am14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am14_sequencial)){
         $this->erro_sql = " Campo am14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am14_sequencial = $am14_sequencial; 
       }
     }
     if(($this->am14_sequencial == null) || ($this->am14_sequencial == "") ){ 
       $this->erro_sql = " Campo am14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mensagerialicenca(
                                       am14_sequencial 
                                      ,am14_assunto 
                                      ,am14_mensagem 
                       )
                values (
                                $this->am14_sequencial 
                               ,'$this->am14_assunto' 
                               ,'$this->am14_mensagem' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Mensagem para notificação de Licenças ($this->am14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Mensagem para notificação de Licenças já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Mensagem para notificação de Licenças ($this->am14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am14_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20974,'$this->am14_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3778,20974,'','".AddSlashes(pg_result($resaco,0,'am14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3778,20975,'','".AddSlashes(pg_result($resaco,0,'am14_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3778,20976,'','".AddSlashes(pg_result($resaco,0,'am14_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($am14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update mensagerialicenca set ";
     $virgula = "";
     if(trim($this->am14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am14_sequencial"])){ 
       $sql  .= $virgula." am14_sequencial = $this->am14_sequencial ";
       $virgula = ",";
       if(trim($this->am14_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Mensagem não informado.";
         $this->erro_campo = "am14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am14_assunto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am14_assunto"])){ 
       $sql  .= $virgula." am14_assunto = '$this->am14_assunto' ";
       $virgula = ",";
       if(trim($this->am14_assunto) == null ){ 
         $this->erro_sql = " Campo Assunto não informado.";
         $this->erro_campo = "am14_assunto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am14_mensagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am14_mensagem"])){ 
       $sql  .= $virgula." am14_mensagem = '$this->am14_mensagem' ";
       $virgula = ",";
       if(trim($this->am14_mensagem) == null ){ 
         $this->erro_sql = " Campo Mensagem não informado.";
         $this->erro_campo = "am14_mensagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am14_sequencial!=null){
       $sql .= " am14_sequencial = $this->am14_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am14_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20974,'$this->am14_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am14_sequencial"]) || $this->am14_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3778,20974,'".AddSlashes(pg_result($resaco,$conresaco,'am14_sequencial'))."','$this->am14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am14_assunto"]) || $this->am14_assunto != "")
             $resac = db_query("insert into db_acount values($acount,3778,20975,'".AddSlashes(pg_result($resaco,$conresaco,'am14_assunto'))."','$this->am14_assunto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am14_mensagem"]) || $this->am14_mensagem != "")
             $resac = db_query("insert into db_acount values($acount,3778,20976,'".AddSlashes(pg_result($resaco,$conresaco,'am14_mensagem'))."','$this->am14_mensagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mensagem para notificação de Licenças nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Mensagem para notificação de Licenças nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($am14_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am14_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20974,'$am14_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3778,20974,'','".AddSlashes(pg_result($resaco,$iresaco,'am14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3778,20975,'','".AddSlashes(pg_result($resaco,$iresaco,'am14_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3778,20976,'','".AddSlashes(pg_result($resaco,$iresaco,'am14_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from mensagerialicenca
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am14_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am14_sequencial = $am14_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Mensagem para notificação de Licenças nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Mensagem para notificação de Licenças nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:mensagerialicenca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($am14_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from mensagerialicenca ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am14_sequencial)) {
         $sql2 .= " where mensagerialicenca.am14_sequencial = $am14_sequencial "; 
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
   public function sql_query_file ($am14_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from mensagerialicenca ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am14_sequencial)){
         $sql2 .= " where mensagerialicenca.am14_sequencial = $am14_sequencial "; 
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
