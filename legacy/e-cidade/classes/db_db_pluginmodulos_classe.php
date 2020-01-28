<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE db_pluginmodulos
class cl_db_pluginmodulos { 
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
   var $db152_sequencial = 0; 
   var $db152_db_plugin = 0; 
   var $db152_db_modulo = 0; 
   var $db152_uid = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db152_sequencial = int4 = C�digo 
                 db152_db_plugin = int4 = C�digo do Plugin 
                 db152_db_modulo = int4 = C�digo do M�dulo 
                 db152_uid = varchar(255) = C�digo �nico 
                 ";
   //funcao construtor da classe 
   function cl_db_pluginmodulos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_pluginmodulos"); 
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
       $this->db152_sequencial = ($this->db152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db152_sequencial"]:$this->db152_sequencial);
       $this->db152_db_plugin = ($this->db152_db_plugin == ""?@$GLOBALS["HTTP_POST_VARS"]["db152_db_plugin"]:$this->db152_db_plugin);
       $this->db152_db_modulo = ($this->db152_db_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["db152_db_modulo"]:$this->db152_db_modulo);
       $this->db152_uid = ($this->db152_uid == ""?@$GLOBALS["HTTP_POST_VARS"]["db152_uid"]:$this->db152_uid);
     }else{
       $this->db152_sequencial = ($this->db152_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db152_sequencial"]:$this->db152_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($db152_sequencial){ 
      $this->atualizacampos();
     if($this->db152_db_plugin == null ){ 
       $this->erro_sql = " Campo C�digo do Plugin n�o informado.";
       $this->erro_campo = "db152_db_plugin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db152_db_modulo == null ){ 
       $this->erro_sql = " Campo C�digo do M�dulo n�o informado.";
       $this->erro_campo = "db152_db_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db152_sequencial == "" || $db152_sequencial == null ){
       $result = db_query("select nextval('db_pluginmodulos_db152_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_pluginmodulos_db152_sequencial_seq do campo: db152_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db152_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_pluginmodulos_db152_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db152_sequencial)){
         $this->erro_sql = " Campo db152_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db152_sequencial = $db152_sequencial; 
       }
     }
     if(($this->db152_sequencial == null) || ($this->db152_sequencial == "") ){ 
       $this->erro_sql = " Campo db152_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_pluginmodulos(
                                       db152_sequencial 
                                      ,db152_db_plugin 
                                      ,db152_db_modulo 
                                      ,db152_uid 
                       )
                values (
                                $this->db152_sequencial 
                               ,$this->db152_db_plugin 
                               ,$this->db152_db_modulo 
                               ,'$this->db152_uid' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Plugin M�dulos ($this->db152_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Plugin M�dulos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Plugin M�dulos ($this->db152_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db152_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db152_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21686,'$this->db152_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3898,21686,'','".AddSlashes(pg_result($resaco,0,'db152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3898,21687,'','".AddSlashes(pg_result($resaco,0,'db152_db_plugin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3898,21688,'','".AddSlashes(pg_result($resaco,0,'db152_db_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3898,21697,'','".AddSlashes(pg_result($resaco,0,'db152_uid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db152_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_pluginmodulos set ";
     $virgula = "";
     if(trim($this->db152_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db152_sequencial"])){ 
       $sql  .= $virgula." db152_sequencial = $this->db152_sequencial ";
       $virgula = ",";
       if(trim($this->db152_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "db152_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db152_db_plugin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db152_db_plugin"])){ 
       $sql  .= $virgula." db152_db_plugin = $this->db152_db_plugin ";
       $virgula = ",";
       if(trim($this->db152_db_plugin) == null ){ 
         $this->erro_sql = " Campo C�digo do Plugin n�o informado.";
         $this->erro_campo = "db152_db_plugin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db152_db_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db152_db_modulo"])){ 
       $sql  .= $virgula." db152_db_modulo = $this->db152_db_modulo ";
       $virgula = ",";
       if(trim($this->db152_db_modulo) == null ){ 
         $this->erro_sql = " Campo C�digo do M�dulo n�o informado.";
         $this->erro_campo = "db152_db_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db152_uid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db152_uid"])){ 
       $sql  .= $virgula." db152_uid = '$this->db152_uid' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db152_sequencial!=null){
       $sql .= " db152_sequencial = $this->db152_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db152_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21686,'$this->db152_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db152_sequencial"]) || $this->db152_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3898,21686,'".AddSlashes(pg_result($resaco,$conresaco,'db152_sequencial'))."','$this->db152_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db152_db_plugin"]) || $this->db152_db_plugin != "")
             $resac = db_query("insert into db_acount values($acount,3898,21687,'".AddSlashes(pg_result($resaco,$conresaco,'db152_db_plugin'))."','$this->db152_db_plugin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db152_db_modulo"]) || $this->db152_db_modulo != "")
             $resac = db_query("insert into db_acount values($acount,3898,21688,'".AddSlashes(pg_result($resaco,$conresaco,'db152_db_modulo'))."','$this->db152_db_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db152_uid"]) || $this->db152_uid != "")
             $resac = db_query("insert into db_acount values($acount,3898,21697,'".AddSlashes(pg_result($resaco,$conresaco,'db152_uid'))."','$this->db152_uid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plugin M�dulos n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db152_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Plugin M�dulos n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db152_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db152_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db152_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db152_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21686,'$db152_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3898,21686,'','".AddSlashes(pg_result($resaco,$iresaco,'db152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3898,21687,'','".AddSlashes(pg_result($resaco,$iresaco,'db152_db_plugin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3898,21688,'','".AddSlashes(pg_result($resaco,$iresaco,'db152_db_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3898,21697,'','".AddSlashes(pg_result($resaco,$iresaco,'db152_uid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_pluginmodulos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db152_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db152_sequencial = $db152_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Plugin M�dulos n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db152_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Plugin M�dulos n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db152_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db152_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_pluginmodulos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($db152_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_pluginmodulos ";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = db_pluginmodulos.db152_db_modulo";
     $sql .= "      inner join db_plugin  on  db_plugin.db145_sequencial = db_pluginmodulos.db152_db_plugin";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db152_sequencial)) {
         $sql2 .= " where db_pluginmodulos.db152_sequencial = $db152_sequencial "; 
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
   public function sql_query_file ($db152_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_pluginmodulos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($db152_sequencial)){
         $sql2 .= " where db_pluginmodulos.db152_sequencial = $db152_sequencial "; 
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
