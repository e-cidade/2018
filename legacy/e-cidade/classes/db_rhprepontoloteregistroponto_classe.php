<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhprepontoloteregistroponto
class cl_rhprepontoloteregistroponto { 
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
   var $rh156_sequencial = 0; 
   var $rh156_rhpreponto = 0; 
   var $rh156_loteregistroponto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh156_sequencial = int4 = Sequencial da tabela 
                 rh156_rhpreponto = int4 = rhpreponto 
                 rh156_loteregistroponto = int4 = loteregistroponto 
                 ";
   //funcao construtor da classe 
   function cl_rhprepontoloteregistroponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhprepontoloteregistroponto"); 
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
       $this->rh156_sequencial = ($this->rh156_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh156_sequencial"]:$this->rh156_sequencial);
       $this->rh156_rhpreponto = ($this->rh156_rhpreponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh156_rhpreponto"]:$this->rh156_rhpreponto);
       $this->rh156_loteregistroponto = ($this->rh156_loteregistroponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh156_loteregistroponto"]:$this->rh156_loteregistroponto);
     }else{
       $this->rh156_sequencial = ($this->rh156_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh156_sequencial"]:$this->rh156_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh156_sequencial){ 
      $this->atualizacampos();
     if($this->rh156_rhpreponto == null ){ 
       $this->erro_sql = " Campo rhpreponto não informado.";
       $this->erro_campo = "rh156_rhpreponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh156_loteregistroponto == null ){ 
       $this->erro_sql = " Campo loteregistroponto não informado.";
       $this->erro_campo = "rh156_loteregistroponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh156_sequencial == "" || $rh156_sequencial == null ){
       $result = db_query("select nextval('rhprepontoloteregistroponto_rh156_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprepontoloteregistroponto_rh156_sequencial_seq do campo: rh156_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh156_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprepontoloteregistroponto_rh156_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh156_sequencial)){
         $this->erro_sql = " Campo rh156_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh156_sequencial = $rh156_sequencial; 
       }
     }
     if(($this->rh156_sequencial == null) || ($this->rh156_sequencial == "") ){ 
       $this->erro_sql = " Campo rh156_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprepontoloteregistroponto(
                                       rh156_sequencial 
                                      ,rh156_rhpreponto 
                                      ,rh156_loteregistroponto 
                       )
                values (
                                $this->rh156_sequencial 
                               ,$this->rh156_rhpreponto 
                               ,$this->rh156_loteregistroponto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh156_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh156_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh156_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh156_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21114,'$this->rh156_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3801,21114,'','".AddSlashes(pg_result($resaco,0,'rh156_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3801,21116,'','".AddSlashes(pg_result($resaco,0,'rh156_rhpreponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3801,21117,'','".AddSlashes(pg_result($resaco,0,'rh156_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh156_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhprepontoloteregistroponto set ";
     $virgula = "";
     if(trim($this->rh156_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh156_sequencial"])){ 
       $sql  .= $virgula." rh156_sequencial = $this->rh156_sequencial ";
       $virgula = ",";
       if(trim($this->rh156_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh156_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh156_rhpreponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh156_rhpreponto"])){ 
       $sql  .= $virgula." rh156_rhpreponto = $this->rh156_rhpreponto ";
       $virgula = ",";
       if(trim($this->rh156_rhpreponto) == null ){ 
         $this->erro_sql = " Campo rhpreponto não informado.";
         $this->erro_campo = "rh156_rhpreponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh156_loteregistroponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh156_loteregistroponto"])){ 
       $sql  .= $virgula." rh156_loteregistroponto = $this->rh156_loteregistroponto ";
       $virgula = ",";
       if(trim($this->rh156_loteregistroponto) == null ){ 
         $this->erro_sql = " Campo loteregistroponto não informado.";
         $this->erro_campo = "rh156_loteregistroponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh156_sequencial!=null){
       $sql .= " rh156_sequencial = $this->rh156_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh156_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21114,'$this->rh156_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh156_sequencial"]) || $this->rh156_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3801,21114,'".AddSlashes(pg_result($resaco,$conresaco,'rh156_sequencial'))."','$this->rh156_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh156_rhpreponto"]) || $this->rh156_rhpreponto != "")
             $resac = db_query("insert into db_acount values($acount,3801,21116,'".AddSlashes(pg_result($resaco,$conresaco,'rh156_rhpreponto'))."','$this->rh156_rhpreponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh156_loteregistroponto"]) || $this->rh156_loteregistroponto != "")
             $resac = db_query("insert into db_acount values($acount,3801,21117,'".AddSlashes(pg_result($resaco,$conresaco,'rh156_loteregistroponto'))."','$this->rh156_loteregistroponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh156_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh156_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh156_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh156_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh156_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21114,'$rh156_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3801,21114,'','".AddSlashes(pg_result($resaco,$iresaco,'rh156_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3801,21116,'','".AddSlashes(pg_result($resaco,$iresaco,'rh156_rhpreponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3801,21117,'','".AddSlashes(pg_result($resaco,$iresaco,'rh156_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhprepontoloteregistroponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh156_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh156_sequencial = $rh156_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh156_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh156_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh156_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprepontoloteregistroponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh156_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprepontoloteregistroponto ";
     $sql .= "      inner join rhpreponto  on  rhpreponto.rh149_sequencial = rhprepontoloteregistroponto.rh156_rhpreponto";
     $sql .= "      inner join loteregistroponto  on  loteregistroponto.rh155_sequencial = rhprepontoloteregistroponto.rh156_loteregistroponto";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpreponto.rh149_instit";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpreponto.rh149_regist";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhpreponto.rh149_tipofolha";
     $sql .= "       left join assentaloteregistroponto on loteregistroponto.rh155_sequencial = assentaloteregistroponto.rh160_loteregistroponto";
     $sql .= "       left join assenta on assenta.h16_codigo = assentaloteregistroponto.rh160_assentamento";
     $sql .= "       left join tipoasse on tipoasse.h12_codigo = assenta.h16_assent";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh156_sequencial)) {
         $sql2 .= " where rhprepontoloteregistroponto.rh156_sequencial = $rh156_sequencial "; 
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
   public function sql_query_file ($rh156_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprepontoloteregistroponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh156_sequencial)){
         $sql2 .= " where rhprepontoloteregistroponto.rh156_sequencial = $rh156_sequencial "; 
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
