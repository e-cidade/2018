<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE cgs_unddocumento
class cl_cgs_unddocumento { 
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
   var $sd108_sequencial = 0; 
   var $sd108_cgs_und = 0; 
   var $sd108_documento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd108_sequencial = int4 = Sequencial 
                 sd108_cgs_und = int4 = CGS 
                 sd108_documento = int4 = Código Documento 
                 ";
   //funcao construtor da classe 
   function cl_cgs_unddocumento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgs_unddocumento"); 
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
       $this->sd108_sequencial = ($this->sd108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd108_sequencial"]:$this->sd108_sequencial);
       $this->sd108_cgs_und = ($this->sd108_cgs_und == ""?@$GLOBALS["HTTP_POST_VARS"]["sd108_cgs_und"]:$this->sd108_cgs_und);
       $this->sd108_documento = ($this->sd108_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd108_documento"]:$this->sd108_documento);
     }else{
       $this->sd108_sequencial = ($this->sd108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd108_sequencial"]:$this->sd108_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($sd108_sequencial){ 
      $this->atualizacampos();
     if($this->sd108_cgs_und == null ){ 
       $this->erro_sql = " Campo CGS não informado.";
       $this->erro_campo = "sd108_cgs_und";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd108_documento == null ){ 
       $this->erro_sql = " Campo Código Documento não informado.";
       $this->erro_campo = "sd108_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd108_sequencial == "" || $sd108_sequencial == null ){
       $result = db_query("select nextval('cgs_unddocumento_sd108_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgs_unddocumento_sd108_sequencial_seq do campo: sd108_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd108_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgs_unddocumento_sd108_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd108_sequencial)){
         $this->erro_sql = " Campo sd108_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd108_sequencial = $sd108_sequencial; 
       }
     }
     if(($this->sd108_sequencial == null) || ($this->sd108_sequencial == "") ){ 
       $this->erro_sql = " Campo sd108_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgs_unddocumento(
                                       sd108_sequencial 
                                      ,sd108_cgs_und 
                                      ,sd108_documento 
                       )
                values (
                                $this->sd108_sequencial 
                               ,$this->sd108_cgs_und 
                               ,$this->sd108_documento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos ($this->sd108_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos ($this->sd108_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd108_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd108_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21874,'$this->sd108_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3937,21874,'','".AddSlashes(pg_result($resaco,0,'sd108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3937,21875,'','".AddSlashes(pg_result($resaco,0,'sd108_cgs_und'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3937,21876,'','".AddSlashes(pg_result($resaco,0,'sd108_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd108_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cgs_unddocumento set ";
     $virgula = "";
     if(trim($this->sd108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd108_sequencial"])){ 
       $sql  .= $virgula." sd108_sequencial = $this->sd108_sequencial ";
       $virgula = ",";
       if(trim($this->sd108_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "sd108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd108_cgs_und)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd108_cgs_und"])){ 
       $sql  .= $virgula." sd108_cgs_und = $this->sd108_cgs_und ";
       $virgula = ",";
       if(trim($this->sd108_cgs_und) == null ){ 
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "sd108_cgs_und";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd108_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd108_documento"])){ 
       $sql  .= $virgula." sd108_documento = $this->sd108_documento ";
       $virgula = ",";
       if(trim($this->sd108_documento) == null ){ 
         $this->erro_sql = " Campo Código Documento não informado.";
         $this->erro_campo = "sd108_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd108_sequencial!=null){
       $sql .= " sd108_sequencial = $this->sd108_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd108_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21874,'$this->sd108_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd108_sequencial"]) || $this->sd108_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3937,21874,'".AddSlashes(pg_result($resaco,$conresaco,'sd108_sequencial'))."','$this->sd108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd108_cgs_und"]) || $this->sd108_cgs_und != "")
             $resac = db_query("insert into db_acount values($acount,3937,21875,'".AddSlashes(pg_result($resaco,$conresaco,'sd108_cgs_und'))."','$this->sd108_cgs_und',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd108_documento"]) || $this->sd108_documento != "")
             $resac = db_query("insert into db_acount values($acount,3937,21876,'".AddSlashes(pg_result($resaco,$conresaco,'sd108_documento'))."','$this->sd108_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd108_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd108_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21874,'$sd108_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3937,21874,'','".AddSlashes(pg_result($resaco,$iresaco,'sd108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3937,21875,'','".AddSlashes(pg_result($resaco,$iresaco,'sd108_cgs_und'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3937,21876,'','".AddSlashes(pg_result($resaco,$iresaco,'sd108_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cgs_unddocumento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd108_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd108_sequencial = $sd108_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd108_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd108_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd108_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgs_unddocumento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd108_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cgs_unddocumento ";
     $sql .= "      inner join documento  on  documento.db58_sequencial = cgs_unddocumento.sd108_documento";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = cgs_unddocumento.sd108_cgs_und";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd108_sequencial)) {
         $sql2 .= " where cgs_unddocumento.sd108_sequencial = $sd108_sequencial "; 
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
   public function sql_query_file ($sd108_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cgs_unddocumento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd108_sequencial)){
         $sql2 .= " where cgs_unddocumento.sd108_sequencial = $sd108_sequencial "; 
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
