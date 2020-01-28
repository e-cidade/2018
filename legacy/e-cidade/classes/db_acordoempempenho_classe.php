<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoempempenho
class cl_acordoempempenho { 
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
   var $ac54_sequencial = 0; 
   var $ac54_acordo = 0; 
   var $ac54_empempenho = 0; 
   var $ac54_numerolicitacao = null; 
   var $ac54_ano = null;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac54_sequencial = int4 = Código 
                 ac54_acordo = int4 = Contrato 
                 ac54_empempenho = int4 = Empenho 
                 ac54_numerolicitacao = varchar(50) = Número Licitação 
                 ac54_ano = int4 = Exercício 
                 ";
   //funcao construtor da classe 
   function cl_acordoempempenho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoempempenho"); 
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
       $this->ac54_sequencial = ($this->ac54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_sequencial"]:$this->ac54_sequencial);
       $this->ac54_acordo = ($this->ac54_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_acordo"]:$this->ac54_acordo);
       $this->ac54_empempenho = ($this->ac54_empempenho == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_empempenho"]:$this->ac54_empempenho);
       $this->ac54_numerolicitacao = ($this->ac54_numerolicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_numerolicitacao"]:$this->ac54_numerolicitacao);
       $this->ac54_ano = ($this->ac54_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_ano"]:$this->ac54_ano);
     }else{
       $this->ac54_sequencial = ($this->ac54_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac54_sequencial"]:$this->ac54_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ac54_sequencial){ 
      $this->atualizacampos();
     if($this->ac54_acordo == null ){ 
       $this->erro_sql = " Campo Contrato não informado.";
       $this->erro_campo = "ac54_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac54_empempenho == null ){ 
       $this->erro_sql = " Campo Empenho não informado.";
       $this->erro_campo = "ac54_empempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac54_sequencial == "" || $ac54_sequencial == null ){
       $result = db_query("select nextval('acordoempempenho_ac54_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoempempenho_ac54_sequencial_seq do campo: ac54_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac54_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoempempenho_ac54_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac54_sequencial)){
         $this->erro_sql = " Campo ac54_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac54_sequencial = $ac54_sequencial; 
       }
     }
     if(($this->ac54_sequencial == null) || ($this->ac54_sequencial == "") ){ 
       $this->erro_sql = " Campo ac54_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if ($this->ac54_ano == null) {
       $this->ac54_ano = "null";
     }

     $sql = "insert into acordoempempenho(
                                       ac54_sequencial 
                                      ,ac54_acordo 
                                      ,ac54_empempenho 
                                      ,ac54_numerolicitacao 
                                      ,ac54_ano 
                       )
                values (
                                $this->ac54_sequencial 
                               ,$this->ac54_acordo 
                               ,$this->ac54_empempenho 
                               ,'$this->ac54_numerolicitacao' 
                               ,$this->ac54_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenhos do contrato ($this->ac54_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenhos do contrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenhos do contrato ($this->ac54_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac54_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac54_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21797,'$this->ac54_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3926,21797,'','".AddSlashes(pg_result($resaco,0,'ac54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3926,21798,'','".AddSlashes(pg_result($resaco,0,'ac54_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3926,21799,'','".AddSlashes(pg_result($resaco,0,'ac54_empempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3926,21800,'','".AddSlashes(pg_result($resaco,0,'ac54_numerolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3926,21848,'','".AddSlashes(pg_result($resaco,0,'ac54_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ac54_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoempempenho set ";
     $virgula = "";
     if(trim($this->ac54_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac54_sequencial"])){ 
       $sql  .= $virgula." ac54_sequencial = $this->ac54_sequencial ";
       $virgula = ",";
       if(trim($this->ac54_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ac54_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac54_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac54_acordo"])){ 
       $sql  .= $virgula." ac54_acordo = $this->ac54_acordo ";
       $virgula = ",";
       if(trim($this->ac54_acordo) == null ){ 
         $this->erro_sql = " Campo Contrato não informado.";
         $this->erro_campo = "ac54_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac54_empempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac54_empempenho"])){ 
       $sql  .= $virgula." ac54_empempenho = $this->ac54_empempenho ";
       $virgula = ",";
       if(trim($this->ac54_empempenho) == null ){ 
         $this->erro_sql = " Campo Empenho não informado.";
         $this->erro_campo = "ac54_empempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac54_numerolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac54_numerolicitacao"])){ 
       $sql  .= $virgula." ac54_numerolicitacao = '$this->ac54_numerolicitacao' ";
       $virgula = ",";
     }
     if(trim($this->ac54_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac54_ano"])){ 
        if(trim($this->ac54_ano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac54_ano"])){ 
           $this->ac54_ano = "0" ; 
        } 
       $sql  .= $virgula." ac54_ano = $this->ac54_ano ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac54_sequencial!=null){
       $sql .= " ac54_sequencial = $this->ac54_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac54_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21797,'$this->ac54_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac54_sequencial"]) || $this->ac54_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3926,21797,'".AddSlashes(pg_result($resaco,$conresaco,'ac54_sequencial'))."','$this->ac54_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac54_acordo"]) || $this->ac54_acordo != "")
             $resac = db_query("insert into db_acount values($acount,3926,21798,'".AddSlashes(pg_result($resaco,$conresaco,'ac54_acordo'))."','$this->ac54_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac54_empempenho"]) || $this->ac54_empempenho != "")
             $resac = db_query("insert into db_acount values($acount,3926,21799,'".AddSlashes(pg_result($resaco,$conresaco,'ac54_empempenho'))."','$this->ac54_empempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac54_numerolicitacao"]) || $this->ac54_numerolicitacao != "")
             $resac = db_query("insert into db_acount values($acount,3926,21800,'".AddSlashes(pg_result($resaco,$conresaco,'ac54_numerolicitacao'))."','$this->ac54_numerolicitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac54_ano"]) || $this->ac54_ano != "")
             $resac = db_query("insert into db_acount values($acount,3926,21848,'".AddSlashes(pg_result($resaco,$conresaco,'ac54_ano'))."','$this->ac54_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenhos do contrato não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos do contrato não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ac54_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac54_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21797,'$ac54_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3926,21797,'','".AddSlashes(pg_result($resaco,$iresaco,'ac54_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3926,21798,'','".AddSlashes(pg_result($resaco,$iresaco,'ac54_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3926,21799,'','".AddSlashes(pg_result($resaco,$iresaco,'ac54_empempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3926,21800,'','".AddSlashes(pg_result($resaco,$iresaco,'ac54_numerolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3926,21848,'','".AddSlashes(pg_result($resaco,$iresaco,'ac54_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoempempenho
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac54_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac54_sequencial = $ac54_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenhos do contrato não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac54_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Empenhos do contrato não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac54_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac54_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoempempenho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ac54_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from acordoempempenho ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = acordoempempenho.ac54_empempenho";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoempempenho.ac54_acordo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac54_sequencial)) {
         $sql2 .= " where acordoempempenho.ac54_sequencial = $ac54_sequencial "; 
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
   public function sql_query_file ($ac54_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordoempempenho ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac54_sequencial)){
         $sql2 .= " where acordoempempenho.ac54_sequencial = $ac54_sequencial "; 
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
