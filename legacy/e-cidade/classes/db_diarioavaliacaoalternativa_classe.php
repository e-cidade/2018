<?
//MODULO: escola
//CLASSE DA ENTIDADE diarioavaliacaoalternativa
class cl_diarioavaliacaoalternativa { 
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
   var $ed136_sequencial = 0; 
   var $ed136_diario = 0; 
   var $ed136_procavalalternativa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed136_sequencial = int4 = Código 
                 ed136_diario = int4 = Diário 
                 ed136_procavalalternativa = int4 = Avaliação Alternativa 
                 ";
   //funcao construtor da classe 
   function cl_diarioavaliacaoalternativa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioavaliacaoalternativa"); 
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
       $this->ed136_sequencial = ($this->ed136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed136_sequencial"]:$this->ed136_sequencial);
       $this->ed136_diario = ($this->ed136_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed136_diario"]:$this->ed136_diario);
       $this->ed136_procavalalternativa = ($this->ed136_procavalalternativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed136_procavalalternativa"]:$this->ed136_procavalalternativa);
     }else{
       $this->ed136_sequencial = ($this->ed136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed136_sequencial"]:$this->ed136_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed136_sequencial){ 
      $this->atualizacampos();
     if($this->ed136_diario == null ){ 
       $this->erro_sql = " Campo Diário não informado.";
       $this->erro_campo = "ed136_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed136_procavalalternativa == null ){ 
       $this->erro_sql = " Campo Avaliação Alternativa não informado.";
       $this->erro_campo = "ed136_procavalalternativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed136_sequencial == "" || $ed136_sequencial == null ){
       $result = db_query("select nextval('diarioavaliacaoalternativa_ed136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioavaliacaoalternativa_ed136_sequencial_seq do campo: ed136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diarioavaliacaoalternativa_ed136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed136_sequencial)){
         $this->erro_sql = " Campo ed136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed136_sequencial = $ed136_sequencial; 
       }
     }
     if(($this->ed136_sequencial == null) || ($this->ed136_sequencial == "") ){ 
       $this->erro_sql = " Campo ed136_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioavaliacaoalternativa(
                                       ed136_sequencial 
                                      ,ed136_diario 
                                      ,ed136_procavalalternativa 
                       )
                values (
                                $this->ed136_sequencial 
                               ,$this->ed136_diario 
                               ,$this->ed136_procavalalternativa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diario Avaliação Alternativa ($this->ed136_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diario Avaliação Alternativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diario Avaliação Alternativa ($this->ed136_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed136_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21607,'$this->ed136_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3881,21607,'','".AddSlashes(pg_result($resaco,0,'ed136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3881,21608,'','".AddSlashes(pg_result($resaco,0,'ed136_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3881,21609,'','".AddSlashes(pg_result($resaco,0,'ed136_procavalalternativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diarioavaliacaoalternativa set ";
     $virgula = "";
     if(trim($this->ed136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed136_sequencial"])){ 
       $sql  .= $virgula." ed136_sequencial = $this->ed136_sequencial ";
       $virgula = ",";
       if(trim($this->ed136_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed136_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed136_diario"])){ 
       $sql  .= $virgula." ed136_diario = $this->ed136_diario ";
       $virgula = ",";
       if(trim($this->ed136_diario) == null ){ 
         $this->erro_sql = " Campo Diário não informado.";
         $this->erro_campo = "ed136_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed136_procavalalternativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed136_procavalalternativa"])){ 
       $sql  .= $virgula." ed136_procavalalternativa = $this->ed136_procavalalternativa ";
       $virgula = ",";
       if(trim($this->ed136_procavalalternativa) == null ){ 
         $this->erro_sql = " Campo Avaliação Alternativa não informado.";
         $this->erro_campo = "ed136_procavalalternativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed136_sequencial!=null){
       $sql .= " ed136_sequencial = $this->ed136_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed136_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21607,'$this->ed136_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed136_sequencial"]) || $this->ed136_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3881,21607,'".AddSlashes(pg_result($resaco,$conresaco,'ed136_sequencial'))."','$this->ed136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed136_diario"]) || $this->ed136_diario != "")
             $resac = db_query("insert into db_acount values($acount,3881,21608,'".AddSlashes(pg_result($resaco,$conresaco,'ed136_diario'))."','$this->ed136_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed136_procavalalternativa"]) || $this->ed136_procavalalternativa != "")
             $resac = db_query("insert into db_acount values($acount,3881,21609,'".AddSlashes(pg_result($resaco,$conresaco,'ed136_procavalalternativa'))."','$this->ed136_procavalalternativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario Avaliação Alternativa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diario Avaliação Alternativa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed136_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed136_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21607,'$ed136_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3881,21607,'','".AddSlashes(pg_result($resaco,$iresaco,'ed136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3881,21608,'','".AddSlashes(pg_result($resaco,$iresaco,'ed136_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3881,21609,'','".AddSlashes(pg_result($resaco,$iresaco,'ed136_procavalalternativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diarioavaliacaoalternativa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed136_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed136_sequencial = $ed136_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario Avaliação Alternativa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diario Avaliação Alternativa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioavaliacaoalternativa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed136_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from diarioavaliacaoalternativa ";
     $sql .= "      inner join procavalalternativa  on  procavalalternativa.ed281_i_codigo = diarioavaliacaoalternativa.ed136_procavalalternativa";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diarioavaliacaoalternativa.ed136_diario";
     $sql .= "      inner join procresultado  on  procresultado.ed43_i_codigo = procavalalternativa.ed281_i_procresultado";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed136_sequencial)) {
         $sql2 .= " where diarioavaliacaoalternativa.ed136_sequencial = $ed136_sequencial "; 
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
   public function sql_query_file ($ed136_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diarioavaliacaoalternativa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed136_sequencial)){
         $sql2 .= " where diarioavaliacaoalternativa.ed136_sequencial = $ed136_sequencial "; 
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
