<?
//MODULO: esocial
//CLASSE DA ENTIDADE avaliacaopergunta_db_formulas
class cl_avaliacaopergunta_db_formulas { 
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
   var $eso01_sequencial = 0; 
   var $eso01_db_formulas = 0; 
   var $eso01_avaliacaopergunta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 eso01_sequencial = int4 = C�digo 
                 eso01_db_formulas = int4 = F�rmula 
                 eso01_avaliacaopergunta = int4 = Pergunta 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaopergunta_db_formulas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaopergunta_db_formulas"); 
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
       $this->eso01_sequencial = ($this->eso01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso01_sequencial"]:$this->eso01_sequencial);
       $this->eso01_db_formulas = ($this->eso01_db_formulas == ""?@$GLOBALS["HTTP_POST_VARS"]["eso01_db_formulas"]:$this->eso01_db_formulas);
       $this->eso01_avaliacaopergunta = ($this->eso01_avaliacaopergunta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso01_avaliacaopergunta"]:$this->eso01_avaliacaopergunta);
     }else{
       $this->eso01_sequencial = ($this->eso01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso01_sequencial"]:$this->eso01_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($eso01_sequencial){ 
      $this->atualizacampos();
     if($this->eso01_db_formulas == null ){ 
       $this->erro_sql = " Campo F�rmula n�o informado.";
       $this->erro_campo = "eso01_db_formulas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso01_avaliacaopergunta == null ){ 
       $this->erro_sql = " Campo Pergunta n�o informado.";
       $this->erro_campo = "eso01_avaliacaopergunta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso01_sequencial == "" || $eso01_sequencial == null ){
       $result = db_query("select nextval('avaliacaopergunta_db_formulas_eso01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaopergunta_db_formulas_eso01_sequencial_seq do campo: eso01_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaopergunta_db_formulas_eso01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso01_sequencial)){
         $this->erro_sql = " Campo eso01_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso01_sequencial = $eso01_sequencial; 
       }
     }
     if(($this->eso01_sequencial == null) || ($this->eso01_sequencial == "") ){ 
       $this->erro_sql = " Campo eso01_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaopergunta_db_formulas(
                                       eso01_sequencial 
                                      ,eso01_db_formulas 
                                      ,eso01_avaliacaopergunta 
                       )
                values (
                                $this->eso01_sequencial 
                               ,$this->eso01_db_formulas 
                               ,$this->eso01_avaliacaopergunta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de Sugest�o de Respostas ($this->eso01_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de Sugest�o de Respostas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de Sugest�o de Respostas ($this->eso01_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso01_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso01_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21789,'$this->eso01_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3923,21789,'','".AddSlashes(pg_result($resaco,0,'eso01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3923,21790,'','".AddSlashes(pg_result($resaco,0,'eso01_db_formulas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3923,21791,'','".AddSlashes(pg_result($resaco,0,'eso01_avaliacaopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($eso01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaopergunta_db_formulas set ";
     $virgula = "";
     if(trim($this->eso01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso01_sequencial"])){ 
       $sql  .= $virgula." eso01_sequencial = $this->eso01_sequencial ";
       $virgula = ",";
       if(trim($this->eso01_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "eso01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso01_db_formulas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso01_db_formulas"])){ 
       $sql  .= $virgula." eso01_db_formulas = $this->eso01_db_formulas ";
       $virgula = ",";
       if(trim($this->eso01_db_formulas) == null ){ 
         $this->erro_sql = " Campo F�rmula n�o informado.";
         $this->erro_campo = "eso01_db_formulas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso01_avaliacaopergunta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso01_avaliacaopergunta"])){ 
       $sql  .= $virgula." eso01_avaliacaopergunta = $this->eso01_avaliacaopergunta ";
       $virgula = ",";
       if(trim($this->eso01_avaliacaopergunta) == null ){ 
         $this->erro_sql = " Campo Pergunta n�o informado.";
         $this->erro_campo = "eso01_avaliacaopergunta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso01_sequencial!=null){
       $sql .= " eso01_sequencial = $this->eso01_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso01_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21789,'$this->eso01_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso01_sequencial"]) || $this->eso01_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3923,21789,'".AddSlashes(pg_result($resaco,$conresaco,'eso01_sequencial'))."','$this->eso01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso01_db_formulas"]) || $this->eso01_db_formulas != "")
             $resac = db_query("insert into db_acount values($acount,3923,21790,'".AddSlashes(pg_result($resaco,$conresaco,'eso01_db_formulas'))."','$this->eso01_db_formulas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso01_avaliacaopergunta"]) || $this->eso01_avaliacaopergunta != "")
             $resac = db_query("insert into db_acount values($acount,3923,21791,'".AddSlashes(pg_result($resaco,$conresaco,'eso01_avaliacaopergunta'))."','$this->eso01_avaliacaopergunta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Sugest�o de Respostas n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Sugest�o de Respostas n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->eso01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($eso01_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso01_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21789,'$eso01_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3923,21789,'','".AddSlashes(pg_result($resaco,$iresaco,'eso01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3923,21790,'','".AddSlashes(pg_result($resaco,$iresaco,'eso01_db_formulas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3923,21791,'','".AddSlashes(pg_result($resaco,$iresaco,'eso01_avaliacaopergunta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaopergunta_db_formulas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso01_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso01_sequencial = $eso01_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Sugest�o de Respostas n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Sugest�o de Respostas n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$eso01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaopergunta_db_formulas";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($eso01_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaopergunta_db_formulas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso01_sequencial)) {
         $sql2 .= " where avaliacaopergunta_db_formulas.eso01_sequencial = $eso01_sequencial "; 
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
   public function sql_query_file ($eso01_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaopergunta_db_formulas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso01_sequencial)){
         $sql2 .= " where avaliacaopergunta_db_formulas.eso01_sequencial = $eso01_sequencial "; 
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
