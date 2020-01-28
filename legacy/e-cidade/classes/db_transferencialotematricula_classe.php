<?
//MODULO: escola
//CLASSE DA ENTIDADE transferencialotematricula
class cl_transferencialotematricula { 
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
   var $ed138_sequencial = 0; 
   var $ed138_transferencialote = 0; 
   var $ed138_matricula = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed138_sequencial = int4 = Código 
                 ed138_transferencialote = int4 = Transferência em Lote 
                 ed138_matricula = int4 = Matrícula 
                 ";
   //funcao construtor da classe 
   function cl_transferencialotematricula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transferencialotematricula"); 
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
       $this->ed138_sequencial = ($this->ed138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed138_sequencial"]:$this->ed138_sequencial);
       $this->ed138_transferencialote = ($this->ed138_transferencialote == ""?@$GLOBALS["HTTP_POST_VARS"]["ed138_transferencialote"]:$this->ed138_transferencialote);
       $this->ed138_matricula = ($this->ed138_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed138_matricula"]:$this->ed138_matricula);
     }else{
       $this->ed138_sequencial = ($this->ed138_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed138_sequencial"]:$this->ed138_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed138_sequencial){ 
      $this->atualizacampos();
     if($this->ed138_transferencialote == null ){ 
       $this->erro_sql = " Campo Transferência em Lote não informado.";
       $this->erro_campo = "ed138_transferencialote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed138_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "ed138_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed138_sequencial == "" || $ed138_sequencial == null ){
       $result = db_query("select nextval('transferencialotematricula_ed138_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transferencialotematricula_ed138_sequencial_seq do campo: ed138_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed138_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from transferencialotematricula_ed138_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed138_sequencial)){
         $this->erro_sql = " Campo ed138_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed138_sequencial = $ed138_sequencial; 
       }
     }
     if(($this->ed138_sequencial == null) || ($this->ed138_sequencial == "") ){ 
       $this->erro_sql = " Campo ed138_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transferencialotematricula(
                                       ed138_sequencial 
                                      ,ed138_transferencialote 
                                      ,ed138_matricula 
                       )
                values (
                                $this->ed138_sequencial 
                               ,$this->ed138_transferencialote 
                               ,$this->ed138_matricula 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matrículas do Lote de Transferência ($this->ed138_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matrículas do Lote de Transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matrículas do Lote de Transferência ($this->ed138_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed138_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed138_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22019,'$this->ed138_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3962,22019,'','".AddSlashes(pg_result($resaco,0,'ed138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3962,22020,'','".AddSlashes(pg_result($resaco,0,'ed138_transferencialote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3962,22021,'','".AddSlashes(pg_result($resaco,0,'ed138_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed138_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update transferencialotematricula set ";
     $virgula = "";
     if(trim($this->ed138_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed138_sequencial"])){ 
       $sql  .= $virgula." ed138_sequencial = $this->ed138_sequencial ";
       $virgula = ",";
       if(trim($this->ed138_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed138_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed138_transferencialote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed138_transferencialote"])){ 
       $sql  .= $virgula." ed138_transferencialote = $this->ed138_transferencialote ";
       $virgula = ",";
       if(trim($this->ed138_transferencialote) == null ){ 
         $this->erro_sql = " Campo Transferência em Lote não informado.";
         $this->erro_campo = "ed138_transferencialote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed138_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed138_matricula"])){ 
       $sql  .= $virgula." ed138_matricula = $this->ed138_matricula ";
       $virgula = ",";
       if(trim($this->ed138_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "ed138_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed138_sequencial!=null){
       $sql .= " ed138_sequencial = $this->ed138_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed138_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22019,'$this->ed138_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed138_sequencial"]) || $this->ed138_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3962,22019,'".AddSlashes(pg_result($resaco,$conresaco,'ed138_sequencial'))."','$this->ed138_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed138_transferencialote"]) || $this->ed138_transferencialote != "")
             $resac = db_query("insert into db_acount values($acount,3962,22020,'".AddSlashes(pg_result($resaco,$conresaco,'ed138_transferencialote'))."','$this->ed138_transferencialote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed138_matricula"]) || $this->ed138_matricula != "")
             $resac = db_query("insert into db_acount values($acount,3962,22021,'".AddSlashes(pg_result($resaco,$conresaco,'ed138_matricula'))."','$this->ed138_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas do Lote de Transferência não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas do Lote de Transferência não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed138_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed138_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22019,'$ed138_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3962,22019,'','".AddSlashes(pg_result($resaco,$iresaco,'ed138_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3962,22020,'','".AddSlashes(pg_result($resaco,$iresaco,'ed138_transferencialote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3962,22021,'','".AddSlashes(pg_result($resaco,$iresaco,'ed138_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from transferencialotematricula
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed138_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed138_sequencial = $ed138_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas do Lote de Transferência não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed138_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas do Lote de Transferência não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed138_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed138_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:transferencialotematricula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed138_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from transferencialotematricula ";
     $sql .= "      inner join transferencialote  on  transferencialote.ed137_sequencial = transferencialotematricula.ed138_transferencialote";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = transferencialotematricula.ed138_matricula";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transferencialote.ed137_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = transferencialote.ed137_escolaorigem";
     $sql .= "      inner join tipoingresso  on  tipoingresso.ed334_sequencial = matricula.ed60_tipoingresso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma and  turma.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed138_sequencial)) {
         $sql2 .= " where transferencialotematricula.ed138_sequencial = $ed138_sequencial "; 
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
   public function sql_query_file ($ed138_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from transferencialotematricula ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed138_sequencial)){
         $sql2 .= " where transferencialotematricula.ed138_sequencial = $ed138_sequencial "; 
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
