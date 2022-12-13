<?
//MODULO: secretariadeeducacao
//CLASSE DA ENTIDADE controlematriculainicial
class cl_controlematriculainicial { 
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
   var $ed135_sequencial = 0; 
   var $ed135_anoinicial = 0; 
   var $ed135_anofinal = 0; 
   var $ed135_quantidadedias = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed135_sequencial = int4 = Código 
                 ed135_anoinicial = int4 = Ano Inicial 
                 ed135_anofinal = int4 = Ano Final 
                 ed135_quantidadedias = int4 = Quantidade de Dias 
                 ";
   //funcao construtor da classe 
   function cl_controlematriculainicial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("controlematriculainicial"); 
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
       $this->ed135_sequencial = ($this->ed135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed135_sequencial"]:$this->ed135_sequencial);
       $this->ed135_anoinicial = ($this->ed135_anoinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed135_anoinicial"]:$this->ed135_anoinicial);
       $this->ed135_anofinal = ($this->ed135_anofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed135_anofinal"]:$this->ed135_anofinal);
       $this->ed135_quantidadedias = ($this->ed135_quantidadedias == ""?@$GLOBALS["HTTP_POST_VARS"]["ed135_quantidadedias"]:$this->ed135_quantidadedias);
     }else{
       $this->ed135_sequencial = ($this->ed135_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed135_sequencial"]:$this->ed135_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed135_sequencial){ 
      $this->atualizacampos();
     if($this->ed135_anoinicial == null ){ 
       $this->erro_sql = " Campo Ano Inicial não informado.";
       $this->erro_campo = "ed135_anoinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed135_anofinal == null ){ 
       $this->ed135_anofinal = "null";
     }
     if($this->ed135_quantidadedias == null ){ 
       $this->erro_sql = " Campo Quantidade de Dias não informado.";
       $this->erro_campo = "ed135_quantidadedias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed135_sequencial == "" || $ed135_sequencial == null ){
       $result = db_query("select nextval('controlematriculainicial_ed135_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: controlematriculainicial_ed135_sequencial_seq do campo: ed135_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed135_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from controlematriculainicial_ed135_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed135_sequencial)){
         $this->erro_sql = " Campo ed135_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed135_sequencial = $ed135_sequencial; 
       }
     }
     if(($this->ed135_sequencial == null) || ($this->ed135_sequencial == "") ){ 
       $this->erro_sql = " Campo ed135_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into controlematriculainicial(
                                       ed135_sequencial 
                                      ,ed135_anoinicial 
                                      ,ed135_anofinal 
                                      ,ed135_quantidadedias 
                       )
                values (
                                $this->ed135_sequencial 
                               ,$this->ed135_anoinicial 
                               ,$this->ed135_anofinal 
                               ,$this->ed135_quantidadedias 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Controle de Matrícula Inicial ($this->ed135_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Controle de Matrícula Inicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Controle de Matrícula Inicial ($this->ed135_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed135_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed135_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21591,'$this->ed135_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3877,21591,'','".AddSlashes(pg_result($resaco,0,'ed135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3877,21593,'','".AddSlashes(pg_result($resaco,0,'ed135_anoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3877,21594,'','".AddSlashes(pg_result($resaco,0,'ed135_anofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3877,21592,'','".AddSlashes(pg_result($resaco,0,'ed135_quantidadedias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed135_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update controlematriculainicial set ";
     $virgula = "";
     if(trim($this->ed135_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed135_sequencial"])){ 
       $sql  .= $virgula." ed135_sequencial = $this->ed135_sequencial ";
       $virgula = ",";
       if(trim($this->ed135_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed135_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed135_anoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed135_anoinicial"])){ 
       $sql  .= $virgula." ed135_anoinicial = $this->ed135_anoinicial ";
       $virgula = ",";
       if(trim($this->ed135_anoinicial) == null ){ 
         $this->erro_sql = " Campo Ano Inicial não informado.";
         $this->erro_campo = "ed135_anoinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed135_anofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed135_anofinal"])){ 
        if(trim($this->ed135_anofinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed135_anofinal"])){ 
           $this->ed135_anofinal = "0" ; 
        } 
       $sql  .= $virgula." ed135_anofinal = $this->ed135_anofinal ";
       $virgula = ",";
     }
     if(trim($this->ed135_quantidadedias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed135_quantidadedias"])){ 
       $sql  .= $virgula." ed135_quantidadedias = $this->ed135_quantidadedias ";
       $virgula = ",";
       if(trim($this->ed135_quantidadedias) == null ){ 
         $this->erro_sql = " Campo Quantidade de Dias não informado.";
         $this->erro_campo = "ed135_quantidadedias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed135_sequencial!=null){
       $sql .= " ed135_sequencial = $this->ed135_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed135_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21591,'$this->ed135_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed135_sequencial"]) || $this->ed135_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3877,21591,'".AddSlashes(pg_result($resaco,$conresaco,'ed135_sequencial'))."','$this->ed135_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed135_anoinicial"]) || $this->ed135_anoinicial != "")
             $resac = db_query("insert into db_acount values($acount,3877,21593,'".AddSlashes(pg_result($resaco,$conresaco,'ed135_anoinicial'))."','$this->ed135_anoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed135_anofinal"]) || $this->ed135_anofinal != "")
             $resac = db_query("insert into db_acount values($acount,3877,21594,'".AddSlashes(pg_result($resaco,$conresaco,'ed135_anofinal'))."','$this->ed135_anofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed135_quantidadedias"]) || $this->ed135_quantidadedias != "")
             $resac = db_query("insert into db_acount values($acount,3877,21592,'".AddSlashes(pg_result($resaco,$conresaco,'ed135_quantidadedias'))."','$this->ed135_quantidadedias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de Matrícula Inicial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Controle de Matrícula Inicial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed135_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed135_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21591,'$ed135_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3877,21591,'','".AddSlashes(pg_result($resaco,$iresaco,'ed135_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3877,21593,'','".AddSlashes(pg_result($resaco,$iresaco,'ed135_anoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3877,21594,'','".AddSlashes(pg_result($resaco,$iresaco,'ed135_anofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3877,21592,'','".AddSlashes(pg_result($resaco,$iresaco,'ed135_quantidadedias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from controlematriculainicial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed135_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed135_sequencial = $ed135_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de Matrícula Inicial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed135_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Controle de Matrícula Inicial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed135_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed135_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:controlematriculainicial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed135_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from controlematriculainicial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed135_sequencial)) {
         $sql2 .= " where controlematriculainicial.ed135_sequencial = $ed135_sequencial "; 
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
   public function sql_query_file ($ed135_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from controlematriculainicial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed135_sequencial)){
         $sql2 .= " where controlematriculainicial.ed135_sequencial = $ed135_sequencial "; 
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
