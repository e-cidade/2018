<?
//MODULO: meioambiente
//CLASSE DA ENTIDADE mensagerialicencaprocessado
class cl_mensagerialicencaprocessado { 
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
   var $am15_sequencial = 0; 
   var $am15_mensagerialicencadb_usuarios = 0; 
   var $am15_licencaempreendimento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 am15_sequencial = int4 = Licen�as Notificadas 
                 am15_mensagerialicencadb_usuarios = int4 = Usu�rio 
                 am15_licencaempreendimento = int4 = Licen�a 
                 ";
   //funcao construtor da classe 
   function cl_mensagerialicencaprocessado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mensagerialicencaprocessado"); 
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
       $this->am15_sequencial = ($this->am15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am15_sequencial"]:$this->am15_sequencial);
       $this->am15_mensagerialicencadb_usuarios = ($this->am15_mensagerialicencadb_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["am15_mensagerialicencadb_usuarios"]:$this->am15_mensagerialicencadb_usuarios);
       $this->am15_licencaempreendimento = ($this->am15_licencaempreendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["am15_licencaempreendimento"]:$this->am15_licencaempreendimento);
     }else{
       $this->am15_sequencial = ($this->am15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am15_sequencial"]:$this->am15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am15_sequencial){ 
      $this->atualizacampos();
     if($this->am15_mensagerialicencadb_usuarios == null ){ 
       $this->erro_sql = " Campo Usu�rio n�o informado.";
       $this->erro_campo = "am15_mensagerialicencadb_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am15_licencaempreendimento == null ){ 
       $this->erro_sql = " Campo Licen�a n�o informado.";
       $this->erro_campo = "am15_licencaempreendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am15_sequencial == "" || $am15_sequencial == null ){
       $result = db_query("select nextval('mensagerialicencaprocessado_am15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mensagerialicencaprocessado_am15_sequencial_seq do campo: am15_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->am15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mensagerialicencaprocessado_am15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am15_sequencial)){
         $this->erro_sql = " Campo am15_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am15_sequencial = $am15_sequencial; 
       }
     }
     if(($this->am15_sequencial == null) || ($this->am15_sequencial == "") ){ 
       $this->erro_sql = " Campo am15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mensagerialicencaprocessado(
                                       am15_sequencial 
                                      ,am15_mensagerialicencadb_usuarios 
                                      ,am15_licencaempreendimento 
                       )
                values (
                                $this->am15_sequencial 
                               ,$this->am15_mensagerialicencadb_usuarios 
                               ,$this->am15_licencaempreendimento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Licen�as Notificadas ($this->am15_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Licen�as Notificadas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Licen�as Notificadas ($this->am15_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am15_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am15_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20977,'$this->am15_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3779,20977,'','".AddSlashes(pg_result($resaco,0,'am15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3779,20978,'','".AddSlashes(pg_result($resaco,0,'am15_mensagerialicencadb_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3779,20979,'','".AddSlashes(pg_result($resaco,0,'am15_licencaempreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($am15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update mensagerialicencaprocessado set ";
     $virgula = "";
     if(trim($this->am15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am15_sequencial"])){ 
       $sql  .= $virgula." am15_sequencial = $this->am15_sequencial ";
       $virgula = ",";
       if(trim($this->am15_sequencial) == null ){ 
         $this->erro_sql = " Campo Licen�as Notificadas n�o informado.";
         $this->erro_campo = "am15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am15_mensagerialicencadb_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am15_mensagerialicencadb_usuarios"])){ 
       $sql  .= $virgula." am15_mensagerialicencadb_usuarios = $this->am15_mensagerialicencadb_usuarios ";
       $virgula = ",";
       if(trim($this->am15_mensagerialicencadb_usuarios) == null ){ 
         $this->erro_sql = " Campo Usu�rio n�o informado.";
         $this->erro_campo = "am15_mensagerialicencadb_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am15_licencaempreendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am15_licencaempreendimento"])){ 
       $sql  .= $virgula." am15_licencaempreendimento = $this->am15_licencaempreendimento ";
       $virgula = ",";
       if(trim($this->am15_licencaempreendimento) == null ){ 
         $this->erro_sql = " Campo Licen�a n�o informado.";
         $this->erro_campo = "am15_licencaempreendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am15_sequencial!=null){
       $sql .= " am15_sequencial = $this->am15_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am15_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20977,'$this->am15_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am15_sequencial"]) || $this->am15_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3779,20977,'".AddSlashes(pg_result($resaco,$conresaco,'am15_sequencial'))."','$this->am15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am15_mensagerialicencadb_usuarios"]) || $this->am15_mensagerialicencadb_usuarios != "")
             $resac = db_query("insert into db_acount values($acount,3779,20978,'".AddSlashes(pg_result($resaco,$conresaco,'am15_mensagerialicencadb_usuarios'))."','$this->am15_mensagerialicencadb_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am15_licencaempreendimento"]) || $this->am15_licencaempreendimento != "")
             $resac = db_query("insert into db_acount values($acount,3779,20979,'".AddSlashes(pg_result($resaco,$conresaco,'am15_licencaempreendimento'))."','$this->am15_licencaempreendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Licen�as Notificadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am15_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Licen�as Notificadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am15_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am15_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($am15_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am15_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20977,'$am15_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3779,20977,'','".AddSlashes(pg_result($resaco,$iresaco,'am15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3779,20978,'','".AddSlashes(pg_result($resaco,$iresaco,'am15_mensagerialicencadb_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3779,20979,'','".AddSlashes(pg_result($resaco,$iresaco,'am15_licencaempreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from mensagerialicencaprocessado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am15_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am15_sequencial = $am15_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Licen�as Notificadas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am15_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Licen�as Notificadas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am15_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:mensagerialicencaprocessado";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($am15_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from mensagerialicencaprocessado ";
     $sql .= "      inner join licencaempreendimento  on  licencaempreendimento.am13_sequencial = mensagerialicencaprocessado.am15_licencaempreendimento";
     $sql .= "      inner join mensagerialicenca_db_usuarios  on  mensagerialicenca_db_usuarios.am16_sequencial = mensagerialicencaprocessado.am15_mensagerialicencadb_usuarios";
     $sql .= "      inner join parecertecnico  on  parecertecnico.am08_sequencial = licencaempreendimento.am13_parecertecnico";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mensagerialicenca_db_usuarios.am16_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am15_sequencial)) {
         $sql2 .= " where mensagerialicencaprocessado.am15_sequencial = $am15_sequencial "; 
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
   public function sql_query_file ($am15_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from mensagerialicencaprocessado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am15_sequencial)){
         $sql2 .= " where mensagerialicencaprocessado.am15_sequencial = $am15_sequencial "; 
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
