<?
//MODULO: matriculaonline
//CLASSE DA ENTIDADE criteriosdesignacao
class cl_criteriosdesignacao {
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
   var $mo16_sequencial = 0;
   var $mo16_descricao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 mo16_sequencial = int4 = Código
                 mo16_descricao = varchar(30) = Descrição
                 ";
   //funcao construtor da classe
   function cl_criteriosdesignacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("criteriosdesignacao");
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
       $this->mo16_sequencial = ($this->mo16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo16_sequencial"]:$this->mo16_sequencial);
       $this->mo16_descricao = ($this->mo16_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["mo16_descricao"]:$this->mo16_descricao);
     }else{
       $this->mo16_sequencial = ($this->mo16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo16_sequencial"]:$this->mo16_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($mo16_sequencial){
      $this->atualizacampos();
     if($this->mo16_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "mo16_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($mo16_sequencial == "" || $mo16_sequencial == null ){
       $result = db_query("select nextval('criteriosdesignacao_mo16_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: criteriosdesignacao_mo16_sequencial_seq do campo: mo16_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->mo16_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from criteriosdesignacao_mo16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $mo16_sequencial)){
         $this->erro_sql = " Campo mo16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->mo16_sequencial = $mo16_sequencial;
       }
     }
     if(($this->mo16_sequencial == null) || ($this->mo16_sequencial == "") ){
       $this->erro_sql = " Campo mo16_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into criteriosdesignacao(
                                       mo16_sequencial
                                      ,mo16_descricao
                       )
                values (
                                $this->mo16_sequencial
                               ,'$this->mo16_descricao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Critérios de Designação ($this->mo16_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Critérios de Designação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Critérios de Designação ($this->mo16_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo16_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21502,'$this->mo16_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3860,21502,'','".AddSlashes(pg_result($resaco,0,'mo16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3860,21503,'','".AddSlashes(pg_result($resaco,0,'mo16_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($mo16_sequencial=null) {
      $this->atualizacampos();
     $sql = " update criteriosdesignacao set ";
     $virgula = "";
     if(trim($this->mo16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo16_sequencial"])){
       $sql  .= $virgula." mo16_sequencial = $this->mo16_sequencial ";
       $virgula = ",";
       if(trim($this->mo16_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "mo16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo16_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo16_descricao"])){
       $sql  .= $virgula." mo16_descricao = '$this->mo16_descricao' ";
       $virgula = ",";
       if(trim($this->mo16_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "mo16_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($mo16_sequencial!=null){
       $sql .= " mo16_sequencial = $this->mo16_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo16_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21502,'$this->mo16_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo16_sequencial"]) || $this->mo16_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3860,21502,'".AddSlashes(pg_result($resaco,$conresaco,'mo16_sequencial'))."','$this->mo16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo16_descricao"]) || $this->mo16_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3860,21503,'".AddSlashes(pg_result($resaco,$conresaco,'mo16_descricao'))."','$this->mo16_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Critérios de Designação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Critérios de Designação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($mo16_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($mo16_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21502,'$mo16_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3860,21502,'','".AddSlashes(pg_result($resaco,$iresaco,'mo16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3860,21503,'','".AddSlashes(pg_result($resaco,$iresaco,'mo16_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from criteriosdesignacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($mo16_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " mo16_sequencial = $mo16_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Critérios de Designação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$mo16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Critérios de Designação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$mo16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$mo16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:criteriosdesignacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($mo16_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from criteriosdesignacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo16_sequencial)) {
         $sql2 .= " where criteriosdesignacao.mo16_sequencial = $mo16_sequencial ";
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
  public function sql_query_file ($mo16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from criteriosdesignacao ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($mo16_sequencial)){
        $sql2 .= " where criteriosdesignacao.mo16_sequencial = $mo16_sequencial ";
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


  public function sql_query_ensino ($mo16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos} ";
    $sql .= "   from criteriosdesignacao ";
    $sql .= "   left join criteriosdesignacaoensino on mo17_criteriosdesignacao = mo16_sequencial ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($mo16_sequencial)){
        $sql2 .= " where criteriosdesignacao.mo16_sequencial = $mo16_sequencial ";
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