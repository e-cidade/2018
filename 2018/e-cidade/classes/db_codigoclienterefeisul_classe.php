<?
//MODULO: pessoal
//CLASSE DA ENTIDADE codigoclienterefeisul
class cl_codigoclienterefeisul { 
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
   var $rh171_sequencial = 0; 
   var $rh171_instit = 0; 
   var $rh171_codigocliente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh171_sequencial = int4 = Sequencial 
                 rh171_instit = int4 = Instituiçao 
                 rh171_codigocliente = int4 = Código Refeisul 
                 ";
   //funcao construtor da classe 
   function cl_codigoclienterefeisul() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("codigoclienterefeisul"); 
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
       $this->rh171_sequencial = ($this->rh171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh171_sequencial"]:$this->rh171_sequencial);
       $this->rh171_instit = ($this->rh171_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh171_instit"]:$this->rh171_instit);
       $this->rh171_codigocliente = ($this->rh171_codigocliente == ""?@$GLOBALS["HTTP_POST_VARS"]["rh171_codigocliente"]:$this->rh171_codigocliente);
     }else{
       $this->rh171_sequencial = ($this->rh171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh171_sequencial"]:$this->rh171_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh171_sequencial){ 
      $this->atualizacampos();
     if($this->rh171_instit == null ){ 
       $this->erro_sql = " Campo Instituiçao não informado.";
       $this->erro_campo = "rh171_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh171_codigocliente == null ){ 
       $this->erro_sql = " Campo Código Refeisul não informado.";
       $this->erro_campo = "rh171_codigocliente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh171_sequencial == "" || $rh171_sequencial == null ){
       $result = db_query("select nextval('codigoclienterefeisul_rh171_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: codigoclienterefeisul_rh171_sequencial_seq do campo: rh171_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh171_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from codigoclienterefeisul_rh171_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh171_sequencial)){
         $this->erro_sql = " Campo rh171_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh171_sequencial = $rh171_sequencial; 
       }
     }
     if(($this->rh171_sequencial == null) || ($this->rh171_sequencial == "") ){ 
       $this->erro_sql = " Campo rh171_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into codigoclienterefeisul(
                                       rh171_sequencial 
                                      ,rh171_instit 
                                      ,rh171_codigocliente 
                       )
                values (
                                $this->rh171_sequencial 
                               ,$this->rh171_instit 
                               ,$this->rh171_codigocliente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração do refeisul ($this->rh171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração do refeisul já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração do refeisul ($this->rh171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh171_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh171_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21635,'$this->rh171_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3887,21635,'','".AddSlashes(pg_result($resaco,0,'rh171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3887,21636,'','".AddSlashes(pg_result($resaco,0,'rh171_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3887,21637,'','".AddSlashes(pg_result($resaco,0,'rh171_codigocliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh171_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update codigoclienterefeisul set ";
     $virgula = "";
     if(trim($this->rh171_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh171_sequencial"])){ 
       $sql  .= $virgula." rh171_sequencial = $this->rh171_sequencial ";
       $virgula = ",";
       if(trim($this->rh171_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh171_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh171_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh171_instit"])){ 
       $sql  .= $virgula." rh171_instit = $this->rh171_instit ";
       $virgula = ",";
       if(trim($this->rh171_instit) == null ){ 
         $this->erro_sql = " Campo Instituiçao não informado.";
         $this->erro_campo = "rh171_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh171_codigocliente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh171_codigocliente"])){ 
       $sql  .= $virgula." rh171_codigocliente = $this->rh171_codigocliente ";
       $virgula = ",";
       if(trim($this->rh171_codigocliente) == null ){ 
         $this->erro_sql = " Campo Código Refeisul não informado.";
         $this->erro_campo = "rh171_codigocliente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh171_sequencial!=null){
       $sql .= " rh171_sequencial = $this->rh171_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh171_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21635,'$this->rh171_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh171_sequencial"]) || $this->rh171_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3887,21635,'".AddSlashes(pg_result($resaco,$conresaco,'rh171_sequencial'))."','$this->rh171_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh171_instit"]) || $this->rh171_instit != "")
             $resac = db_query("insert into db_acount values($acount,3887,21636,'".AddSlashes(pg_result($resaco,$conresaco,'rh171_instit'))."','$this->rh171_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh171_codigocliente"]) || $this->rh171_codigocliente != "")
             $resac = db_query("insert into db_acount values($acount,3887,21637,'".AddSlashes(pg_result($resaco,$conresaco,'rh171_codigocliente'))."','$this->rh171_codigocliente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração do refeisul não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração do refeisul não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh171_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh171_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21635,'$rh171_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3887,21635,'','".AddSlashes(pg_result($resaco,$iresaco,'rh171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3887,21636,'','".AddSlashes(pg_result($resaco,$iresaco,'rh171_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3887,21637,'','".AddSlashes(pg_result($resaco,$iresaco,'rh171_codigocliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from codigoclienterefeisul
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh171_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh171_sequencial = $rh171_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração do refeisul não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração do refeisul não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh171_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:codigoclienterefeisul";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh171_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from codigoclienterefeisul ";
     $sql .= "      inner join db_config  on  db_config.codigo = codigoclienterefeisul.rh171_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh171_sequencial)) {
         $sql2 .= " where codigoclienterefeisul.rh171_sequencial = $rh171_sequencial "; 
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
   public function sql_query_file ($rh171_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from codigoclienterefeisul ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh171_sequencial)){
         $sql2 .= " where codigoclienterefeisul.rh171_sequencial = $rh171_sequencial "; 
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
