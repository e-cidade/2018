<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracaopessoalvalorprevidencia
class cl_rhdirfgeracaopessoalvalorprevidencia { 
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
   var $rh204_sequencial = 0; 
   var $rh204_rhdirfgeracaodadospessoalvalor = 0; 
   var $rh204_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh204_sequencial = int4 = Código 
                 rh204_rhdirfgeracaodadospessoalvalor = int4 = Valor da Dirf 
                 rh204_numcgm = int4 = Previdência 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaopessoalvalorprevidencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaopessoalvalorprevidencia"); 
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
       $this->rh204_sequencial = ($this->rh204_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh204_sequencial"]:$this->rh204_sequencial);
       $this->rh204_rhdirfgeracaodadospessoalvalor = ($this->rh204_rhdirfgeracaodadospessoalvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh204_rhdirfgeracaodadospessoalvalor"]:$this->rh204_rhdirfgeracaodadospessoalvalor);
       $this->rh204_numcgm = ($this->rh204_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh204_numcgm"]:$this->rh204_numcgm);
     }else{
       $this->rh204_sequencial = ($this->rh204_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh204_sequencial"]:$this->rh204_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh204_sequencial){ 
      $this->atualizacampos();
     if($this->rh204_rhdirfgeracaodadospessoalvalor == null ){ 
       $this->erro_sql = " Campo Valor da Dirf não informado.";
       $this->erro_campo = "rh204_rhdirfgeracaodadospessoalvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh204_numcgm == null ){ 
       $this->erro_sql = " Campo Previdência não informado.";
       $this->erro_campo = "rh204_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh204_sequencial == "" || $rh204_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq do campo: rh204_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh204_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaopessoalvalorprevidencia_rh204_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh204_sequencial)){
         $this->erro_sql = " Campo rh204_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh204_sequencial = $rh204_sequencial; 
       }
     }
     if(($this->rh204_sequencial == null) || ($this->rh204_sequencial == "") ){ 
       $this->erro_sql = " Campo rh204_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaopessoalvalorprevidencia(
                                       rh204_sequencial 
                                      ,rh204_rhdirfgeracaodadospessoalvalor 
                                      ,rh204_numcgm 
                       )
                values (
                                $this->rh204_sequencial 
                               ,$this->rh204_rhdirfgeracaodadospessoalvalor 
                               ,$this->rh204_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhdirfgeracaopessoalvalorprevidencia ($this->rh204_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhdirfgeracaopessoalvalorprevidencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhdirfgeracaopessoalvalorprevidencia ($this->rh204_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh204_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh204_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22319,'$this->rh204_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4022,22319,'','".AddSlashes(pg_result($resaco,0,'rh204_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4022,22320,'','".AddSlashes(pg_result($resaco,0,'rh204_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4022,22321,'','".AddSlashes(pg_result($resaco,0,'rh204_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh204_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaopessoalvalorprevidencia set ";
     $virgula = "";
     if(trim($this->rh204_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh204_sequencial"])){ 
       $sql  .= $virgula." rh204_sequencial = $this->rh204_sequencial ";
       $virgula = ",";
       if(trim($this->rh204_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh204_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh204_rhdirfgeracaodadospessoalvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh204_rhdirfgeracaodadospessoalvalor"])){ 
       $sql  .= $virgula." rh204_rhdirfgeracaodadospessoalvalor = $this->rh204_rhdirfgeracaodadospessoalvalor ";
       $virgula = ",";
       if(trim($this->rh204_rhdirfgeracaodadospessoalvalor) == null ){ 
         $this->erro_sql = " Campo Valor da Dirf não informado.";
         $this->erro_campo = "rh204_rhdirfgeracaodadospessoalvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh204_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh204_numcgm"])){ 
       $sql  .= $virgula." rh204_numcgm = $this->rh204_numcgm ";
       $virgula = ",";
       if(trim($this->rh204_numcgm) == null ){ 
         $this->erro_sql = " Campo Previdência não informado.";
         $this->erro_campo = "rh204_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh204_sequencial!=null){
       $sql .= " rh204_sequencial = $this->rh204_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh204_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22319,'$this->rh204_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh204_sequencial"]) || $this->rh204_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4022,22319,'".AddSlashes(pg_result($resaco,$conresaco,'rh204_sequencial'))."','$this->rh204_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh204_rhdirfgeracaodadospessoalvalor"]) || $this->rh204_rhdirfgeracaodadospessoalvalor != "")
             $resac = db_query("insert into db_acount values($acount,4022,22320,'".AddSlashes(pg_result($resaco,$conresaco,'rh204_rhdirfgeracaodadospessoalvalor'))."','$this->rh204_rhdirfgeracaodadospessoalvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh204_numcgm"]) || $this->rh204_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,4022,22321,'".AddSlashes(pg_result($resaco,$conresaco,'rh204_numcgm'))."','$this->rh204_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaopessoalvalorprevidencia não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh204_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaopessoalvalorprevidencia não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh204_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh204_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh204_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh204_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22319,'$rh204_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4022,22319,'','".AddSlashes(pg_result($resaco,$iresaco,'rh204_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4022,22320,'','".AddSlashes(pg_result($resaco,$iresaco,'rh204_rhdirfgeracaodadospessoalvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4022,22321,'','".AddSlashes(pg_result($resaco,$iresaco,'rh204_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaopessoalvalorprevidencia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh204_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh204_sequencial = $rh204_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaopessoalvalorprevidencia não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh204_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaopessoalvalorprevidencia não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh204_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh204_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaopessoalvalorprevidencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh204_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhdirfgeracaopessoalvalorprevidencia ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaopessoalvalorprevidencia.rh204_numcgm";
     $sql .= "      inner join rhdirfgeracaodadospessoalvalor  on  rhdirfgeracaodadospessoalvalor.rh98_sequencial = rhdirfgeracaopessoalvalorprevidencia.rh204_rhdirfgeracaodadospessoalvalor";
     $sql .= "      inner join rhdirfgeracaodadospessoal  as a on   a.rh96_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal";
     $sql .= "      inner join rhdirfgeracao on a.rh96_rhdirfgeracao = rh95_sequencial";
     $sql .= "      inner join rhdirftipovalor  on  rhdirftipovalor.rh97_sequencial = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh204_sequencial)) {
         $sql2 .= " where rhdirfgeracaopessoalvalorprevidencia.rh204_sequencial = $rh204_sequencial "; 
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
   public function sql_query_file ($rh204_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhdirfgeracaopessoalvalorprevidencia ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh204_sequencial)){
         $sql2 .= " where rhdirfgeracaopessoalvalorprevidencia.rh204_sequencial = $rh204_sequencial "; 
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
