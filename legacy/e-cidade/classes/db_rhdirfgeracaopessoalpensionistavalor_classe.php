<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracaopessoalpensionistavalor
class cl_rhdirfgeracaopessoalpensionistavalor { 
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
   var $rh203_sequencial = 0; 
   var $rh203_rhdirfgeracaopessoalpensionista = 0; 
   var $rh203_rhdirfgeracaodadospessoalvalor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh203_sequencial = int4 = Código Sequencial 
                 rh203_rhdirfgeracaopessoalpensionista = int4 = Pensionista 
                 rh203_rhdirfgeracaodadospessoalvalor = int4 = Valor processado 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaopessoalpensionistavalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaopessoalpensionistavalor"); 
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
       $this->rh203_sequencial = ($this->rh203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh203_sequencial"]:$this->rh203_sequencial);
       $this->rh203_rhdirfgeracaopessoalpensionista = ($this->rh203_rhdirfgeracaopessoalpensionista == ""?@$GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaopessoalpensionista"]:$this->rh203_rhdirfgeracaopessoalpensionista);
       $this->rh203_rhdirfgeracaodadospessoalvalor = ($this->rh203_rhdirfgeracaodadospessoalvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaodadospessoalvalor"]:$this->rh203_rhdirfgeracaodadospessoalvalor);
     }else{
       $this->rh203_sequencial = ($this->rh203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh203_sequencial"]:$this->rh203_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh203_sequencial){ 
      $this->atualizacampos();
     if($this->rh203_rhdirfgeracaopessoalpensionista == null ){ 
       $this->erro_sql = " Campo Pensionista não informado.";
       $this->erro_campo = "rh203_rhdirfgeracaopessoalpensionista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh203_rhdirfgeracaodadospessoalvalor == null ){ 
       $this->erro_sql = " Campo Valor processado não informado.";
       $this->erro_campo = "rh203_rhdirfgeracaodadospessoalvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh203_sequencial == "" || $rh203_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq do campo: rh203_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh203_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaopessoalpensionistavalor_rh203_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh203_sequencial)){
         $this->erro_sql = " Campo rh203_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh203_sequencial = $rh203_sequencial; 
       }
     }
     if(($this->rh203_sequencial == null) || ($this->rh203_sequencial == "") ){ 
       $this->erro_sql = " Campo rh203_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaopessoalpensionistavalor(
                                       rh203_sequencial 
                                      ,rh203_rhdirfgeracaopessoalpensionista 
                                      ,rh203_rhdirfgeracaodadospessoalvalor 
                       )
                values (
                                $this->rh203_sequencial 
                               ,$this->rh203_rhdirfgeracaopessoalpensionista 
                               ,$this->rh203_rhdirfgeracaodadospessoalvalor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhdirfgeracaopessoalpensionistavalor ($this->rh203_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhdirfgeracaopessoalpensionistavalor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhdirfgeracaopessoalpensionistavalor ($this->rh203_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh203_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh203_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22316,'$this->rh203_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4020,22316,'','".AddSlashes(pg_result($resaco,0,'rh203_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4020,22317,'','".AddSlashes(pg_result($resaco,0,'rh203_rhdirfgeracaopessoalpensionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4020,22318,'','".AddSlashes(pg_result($resaco,0,'rh203_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh203_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaopessoalpensionistavalor set ";
     $virgula = "";
     if(trim($this->rh203_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh203_sequencial"])){ 
       $sql  .= $virgula." rh203_sequencial = $this->rh203_sequencial ";
       $virgula = ",";
       if(trim($this->rh203_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh203_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh203_rhdirfgeracaopessoalpensionista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaopessoalpensionista"])){ 
       $sql  .= $virgula." rh203_rhdirfgeracaopessoalpensionista = $this->rh203_rhdirfgeracaopessoalpensionista ";
       $virgula = ",";
       if(trim($this->rh203_rhdirfgeracaopessoalpensionista) == null ){ 
         $this->erro_sql = " Campo Pensionista não informado.";
         $this->erro_campo = "rh203_rhdirfgeracaopessoalpensionista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh203_rhdirfgeracaodadospessoalvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaodadospessoalvalor"])){ 
       $sql  .= $virgula." rh203_rhdirfgeracaodadospessoalvalor = $this->rh203_rhdirfgeracaodadospessoalvalor ";
       $virgula = ",";
       if(trim($this->rh203_rhdirfgeracaodadospessoalvalor) == null ){ 
         $this->erro_sql = " Campo Valor processado não informado.";
         $this->erro_campo = "rh203_rhdirfgeracaodadospessoalvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh203_sequencial!=null){
       $sql .= " rh203_sequencial = $this->rh203_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh203_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22316,'$this->rh203_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh203_sequencial"]) || $this->rh203_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4020,22316,'".AddSlashes(pg_result($resaco,$conresaco,'rh203_sequencial'))."','$this->rh203_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaopessoalpensionista"]) || $this->rh203_rhdirfgeracaopessoalpensionista != "")
             $resac = db_query("insert into db_acount values($acount,4020,22317,'".AddSlashes(pg_result($resaco,$conresaco,'rh203_rhdirfgeracaopessoalpensionista'))."','$this->rh203_rhdirfgeracaopessoalpensionista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh203_rhdirfgeracaodadospessoalvalor"]) || $this->rh203_rhdirfgeracaodadospessoalvalor != "")
             $resac = db_query("insert into db_acount values($acount,4020,22318,'".AddSlashes(pg_result($resaco,$conresaco,'rh203_rhdirfgeracaodadospessoalvalor'))."','$this->rh203_rhdirfgeracaodadospessoalvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaopessoalpensionistavalor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh203_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaopessoalpensionistavalor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh203_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh203_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh203_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh203_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22316,'$rh203_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4020,22316,'','".AddSlashes(pg_result($resaco,$iresaco,'rh203_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4020,22317,'','".AddSlashes(pg_result($resaco,$iresaco,'rh203_rhdirfgeracaopessoalpensionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4020,22318,'','".AddSlashes(pg_result($resaco,$iresaco,'rh203_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaopessoalpensionistavalor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh203_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh203_sequencial = $rh203_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaopessoalpensionistavalor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh203_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaopessoalpensionistavalor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh203_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh203_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaopessoalpensionistavalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh203_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhdirfgeracaopessoalpensionistavalor ";
     $sql .= "      inner join rhdirfgeracaodadospessoalvalor  on  rhdirfgeracaodadospessoalvalor.rh98_sequencial = rhdirfgeracaopessoalpensionistavalor.rh203_rhdirfgeracaodadospessoalvalor";
     $sql .= "      inner join rhdirfgeracaopessoalpensionista  on  rhdirfgeracaopessoalpensionista.rh202_sequencial = rhdirfgeracaopessoalpensionistavalor.rh203_rhdirfgeracaopessoalpensionista";
     $sql .= "      inner join rhdirfgeracaodadospessoal  on  rhdirfgeracaodadospessoal.rh96_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal";
     $sql .= "      inner join rhdirftipovalor  on  rhdirftipovalor.rh97_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaopessoalpensionista.rh202_numcgm";
     $sql .= "      inner join rhdirfgeracaodadospessoal  on  rhdirfgeracaodadospessoal.rh96_sequencial = rhdirfgeracaopessoalpensionista.rh202_rhdirfgeracaopessoal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh203_sequencial)) {
         $sql2 .= " where rhdirfgeracaopessoalpensionistavalor.rh203_sequencial = $rh203_sequencial "; 
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
   public function sql_query_file ($rh203_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhdirfgeracaopessoalpensionistavalor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh203_sequencial)){
         $sql2 .= " where rhdirfgeracaopessoalpensionistavalor.rh203_sequencial = $rh203_sequencial "; 
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
  public function sql_query_valores ($rh203_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from rhdirfgeracaopessoalpensionistavalor ";
    $sql .= "      inner join rhdirfgeracaodadospessoalvalor  on  rhdirfgeracaodadospessoalvalor.rh98_sequencial = rhdirfgeracaopessoalpensionistavalor.rh203_rhdirfgeracaodadospessoalvalor";
    $sql .= "      inner join rhdirfgeracaopessoalpensionista  on  rhdirfgeracaopessoalpensionista.rh202_sequencial = rhdirfgeracaopessoalpensionistavalor.rh203_rhdirfgeracaopessoalpensionista";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($rh203_sequencial)) {
        $sql2 .= " where rhdirfgeracaopessoalpensionistavalor.rh203_sequencial = $rh203_sequencial ";
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
