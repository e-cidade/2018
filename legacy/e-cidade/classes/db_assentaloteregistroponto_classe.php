<?
//MODULO: pessoal
//CLASSE DA ENTIDADE assentaloteregistroponto
class cl_assentaloteregistroponto { 
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
   var $rh160_sequencial = 0; 
   var $rh160_loteregistroponto = 0; 
   var $rh160_assentamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh160_sequencial = int4 = Sequencial da tabela 
                 rh160_loteregistroponto = int4 = Lote 
                 rh160_assentamento = int4 = Assentamento 
                 ";
   //funcao construtor da classe 
   function cl_assentaloteregistroponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assentaloteregistroponto"); 
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
       $this->rh160_sequencial = ($this->rh160_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh160_sequencial"]:$this->rh160_sequencial);
       $this->rh160_loteregistroponto = ($this->rh160_loteregistroponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh160_loteregistroponto"]:$this->rh160_loteregistroponto);
       $this->rh160_assentamento = ($this->rh160_assentamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh160_assentamento"]:$this->rh160_assentamento);
     }else{
       $this->rh160_sequencial = ($this->rh160_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh160_sequencial"]:$this->rh160_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh160_sequencial){ 
      $this->atualizacampos();
     if($this->rh160_loteregistroponto == null ){ 
       $this->erro_sql = " Campo Lote não informado.";
       $this->erro_campo = "rh160_loteregistroponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh160_assentamento == null ){ 
       $this->erro_sql = " Campo Assentamento não informado.";
       $this->erro_campo = "rh160_assentamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh160_sequencial == "" || $rh160_sequencial == null ){
       $result = db_query("select nextval('assentaloteregistroponto_rh160_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assentaloteregistroponto_rh160_sequencial_seq do campo: rh160_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh160_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from assentaloteregistroponto_rh160_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh160_sequencial)){
         $this->erro_sql = " Campo rh160_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh160_sequencial = $rh160_sequencial; 
       }
     }
     if(($this->rh160_sequencial == null) || ($this->rh160_sequencial == "") ){ 
       $this->erro_sql = " Campo rh160_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assentaloteregistroponto(
                                       rh160_sequencial 
                                      ,rh160_loteregistroponto 
                                      ,rh160_assentamento 
                       )
                values (
                                $this->rh160_sequencial 
                               ,$this->rh160_loteregistroponto 
                               ,$this->rh160_assentamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh160_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh160_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh160_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh160_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21162,'$this->rh160_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3811,21162,'','".AddSlashes(pg_result($resaco,0,'rh160_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3811,21163,'','".AddSlashes(pg_result($resaco,0,'rh160_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3811,21164,'','".AddSlashes(pg_result($resaco,0,'rh160_assentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh160_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update assentaloteregistroponto set ";
     $virgula = "";
     if(trim($this->rh160_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh160_sequencial"])){ 
       $sql  .= $virgula." rh160_sequencial = $this->rh160_sequencial ";
       $virgula = ",";
       if(trim($this->rh160_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh160_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh160_loteregistroponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh160_loteregistroponto"])){ 
       $sql  .= $virgula." rh160_loteregistroponto = $this->rh160_loteregistroponto ";
       $virgula = ",";
       if(trim($this->rh160_loteregistroponto) == null ){ 
         $this->erro_sql = " Campo Lote não informado.";
         $this->erro_campo = "rh160_loteregistroponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh160_assentamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh160_assentamento"])){ 
       $sql  .= $virgula." rh160_assentamento = $this->rh160_assentamento ";
       $virgula = ",";
       if(trim($this->rh160_assentamento) == null ){ 
         $this->erro_sql = " Campo Assentamento não informado.";
         $this->erro_campo = "rh160_assentamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh160_sequencial!=null){
       $sql .= " rh160_sequencial = $this->rh160_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh160_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21162,'$this->rh160_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh160_sequencial"]) || $this->rh160_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3811,21162,'".AddSlashes(pg_result($resaco,$conresaco,'rh160_sequencial'))."','$this->rh160_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh160_loteregistroponto"]) || $this->rh160_loteregistroponto != "")
             $resac = db_query("insert into db_acount values($acount,3811,21163,'".AddSlashes(pg_result($resaco,$conresaco,'rh160_loteregistroponto'))."','$this->rh160_loteregistroponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh160_assentamento"]) || $this->rh160_assentamento != "")
             $resac = db_query("insert into db_acount values($acount,3811,21164,'".AddSlashes(pg_result($resaco,$conresaco,'rh160_assentamento'))."','$this->rh160_assentamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh160_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh160_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh160_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21162,'$rh160_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3811,21162,'','".AddSlashes(pg_result($resaco,$iresaco,'rh160_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3811,21163,'','".AddSlashes(pg_result($resaco,$iresaco,'rh160_loteregistroponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3811,21164,'','".AddSlashes(pg_result($resaco,$iresaco,'rh160_assentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from assentaloteregistroponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh160_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh160_sequencial = $rh160_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh160_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh160_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:assentaloteregistroponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh160_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from assentaloteregistroponto ";
     $sql .= "      inner join assenta  on  assenta.h16_codigo = assentaloteregistroponto.rh160_assentamento";
     $sql .= "      inner join loteregistroponto  on  loteregistroponto.rh155_sequencial = assentaloteregistroponto.rh160_loteregistroponto";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
     $sql .= "      inner join db_config  on  db_config.codigo = loteregistroponto.rh155_instit";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = loteregistroponto.rh155_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh160_sequencial)) {
         $sql2 .= " where assentaloteregistroponto.rh160_sequencial = $rh160_sequencial "; 
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
   public function sql_query_file ($rh160_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from assentaloteregistroponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh160_sequencial)){
         $sql2 .= " where assentaloteregistroponto.rh160_sequencial = $rh160_sequencial "; 
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
