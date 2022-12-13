<?
//MODULO: matriculaonline
//CLASSE DA ENTIDADE ciclosensino
class cl_ciclosensino { 
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
   var $mo14_sequencial = 0; 
   var $mo14_ciclo = 0; 
   var $mo14_ensino = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 mo14_sequencial = int4 = Código 
                 mo14_ciclo = int4 = Ciclo 
                 mo14_ensino = int4 = Ensino 
                 ";
   //funcao construtor da classe 
   function cl_ciclosensino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ciclosensino"); 
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
       $this->mo14_sequencial = ($this->mo14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo14_sequencial"]:$this->mo14_sequencial);
       $this->mo14_ciclo = ($this->mo14_ciclo == ""?@$GLOBALS["HTTP_POST_VARS"]["mo14_ciclo"]:$this->mo14_ciclo);
       $this->mo14_ensino = ($this->mo14_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["mo14_ensino"]:$this->mo14_ensino);
     }else{
       $this->mo14_sequencial = ($this->mo14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo14_sequencial"]:$this->mo14_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($mo14_sequencial){ 
      $this->atualizacampos();
     if($this->mo14_ciclo == null ){ 
       $this->erro_sql = " Campo Ciclo não informado.";
       $this->erro_campo = "mo14_ciclo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mo14_ensino == null ){ 
       $this->erro_sql = " Campo Ensino não informado.";
       $this->erro_campo = "mo14_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($mo14_sequencial == "" || $mo14_sequencial == null ){
       $result = db_query("select nextval('ciclosensino_mo14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ciclosensino_mo14_sequencial_seq do campo: mo14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->mo14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ciclosensino_mo14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $mo14_sequencial)){
         $this->erro_sql = " Campo mo14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->mo14_sequencial = $mo14_sequencial; 
       }
     }
     if(($this->mo14_sequencial == null) || ($this->mo14_sequencial == "") ){ 
       $this->erro_sql = " Campo mo14_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ciclosensino(
                                       mo14_sequencial 
                                      ,mo14_ciclo 
                                      ,mo14_ensino 
                       )
                values (
                                $this->mo14_sequencial 
                               ,$this->mo14_ciclo 
                               ,$this->mo14_ensino 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ensinos de um Ciclo ($this->mo14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ensinos de um Ciclo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ensinos de um Ciclo ($this->mo14_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo14_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21246,'$this->mo14_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3829,21246,'','".AddSlashes(pg_result($resaco,0,'mo14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3829,21247,'','".AddSlashes(pg_result($resaco,0,'mo14_ciclo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3829,21249,'','".AddSlashes(pg_result($resaco,0,'mo14_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($mo14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ciclosensino set ";
     $virgula = "";
     if(trim($this->mo14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo14_sequencial"])){ 
       $sql  .= $virgula." mo14_sequencial = $this->mo14_sequencial ";
       $virgula = ",";
       if(trim($this->mo14_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "mo14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo14_ciclo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo14_ciclo"])){ 
       $sql  .= $virgula." mo14_ciclo = $this->mo14_ciclo ";
       $virgula = ",";
       if(trim($this->mo14_ciclo) == null ){ 
         $this->erro_sql = " Campo Ciclo não informado.";
         $this->erro_campo = "mo14_ciclo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo14_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo14_ensino"])){ 
       $sql  .= $virgula." mo14_ensino = $this->mo14_ensino ";
       $virgula = ",";
       if(trim($this->mo14_ensino) == null ){ 
         $this->erro_sql = " Campo Ensino não informado.";
         $this->erro_campo = "mo14_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($mo14_sequencial!=null){
       $sql .= " mo14_sequencial = $this->mo14_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo14_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21246,'$this->mo14_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo14_sequencial"]) || $this->mo14_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3829,21246,'".AddSlashes(pg_result($resaco,$conresaco,'mo14_sequencial'))."','$this->mo14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo14_ciclo"]) || $this->mo14_ciclo != "")
             $resac = db_query("insert into db_acount values($acount,3829,21247,'".AddSlashes(pg_result($resaco,$conresaco,'mo14_ciclo'))."','$this->mo14_ciclo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo14_ensino"]) || $this->mo14_ensino != "")
             $resac = db_query("insert into db_acount values($acount,3829,21249,'".AddSlashes(pg_result($resaco,$conresaco,'mo14_ensino'))."','$this->mo14_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ensinos de um Ciclo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ensinos de um Ciclo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($mo14_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($mo14_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21246,'$mo14_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3829,21246,'','".AddSlashes(pg_result($resaco,$iresaco,'mo14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3829,21247,'','".AddSlashes(pg_result($resaco,$iresaco,'mo14_ciclo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3829,21249,'','".AddSlashes(pg_result($resaco,$iresaco,'mo14_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from ciclosensino
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($mo14_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " mo14_sequencial = $mo14_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ensinos de um Ciclo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$mo14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ensinos de um Ciclo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$mo14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$mo14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ciclosensino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($mo14_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from ciclosensino ";
     $sql .= "      inner join ciclos  on  ciclos.mo09_codigo = ciclosensino.mo14_ciclo";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = ciclosensino.mo14_ensino";
     $sql .= "      inner join mediacaodidaticopedagogica  on  mediacaodidaticopedagogica.ed130_codigo = ensino.ed10_mediacaodidaticopedagogica";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo14_sequencial)) {
         $sql2 .= " where ciclosensino.mo14_sequencial = $mo14_sequencial "; 
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
   public function sql_query_file ($mo14_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from ciclosensino ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo14_sequencial)){
         $sql2 .= " where ciclosensino.mo14_sequencial = $mo14_sequencial "; 
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
