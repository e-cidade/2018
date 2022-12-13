<?
//MODULO: secretariadeeducacao
//CLASSE DA ENTIDADE cursoequivalencia
class cl_cursoequivalencia {
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
   var $ed140_sequencial = 0;
   var $ed140_cursoedu = 0;
   var $ed140_cursoequivalente = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed140_sequencial = int4 = Código
                 ed140_cursoedu = int4 = Curso
                 ed140_cursoequivalente = int4 = Curso equivalente
                 ";
   //funcao construtor da classe
   function cl_cursoequivalencia() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursoequivalencia");
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
       $this->ed140_sequencial = ($this->ed140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed140_sequencial"]:$this->ed140_sequencial);
       $this->ed140_cursoedu = ($this->ed140_cursoedu == ""?@$GLOBALS["HTTP_POST_VARS"]["ed140_cursoedu"]:$this->ed140_cursoedu);
       $this->ed140_cursoequivalente = ($this->ed140_cursoequivalente == ""?@$GLOBALS["HTTP_POST_VARS"]["ed140_cursoequivalente"]:$this->ed140_cursoequivalente);
     }else{
       $this->ed140_sequencial = ($this->ed140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed140_sequencial"]:$this->ed140_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed140_sequencial){
      $this->atualizacampos();
     if($this->ed140_cursoedu == null ){
       $this->erro_sql = " Campo Curso não informado.";
       $this->erro_campo = "ed140_cursoedu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed140_cursoequivalente == null ){
       $this->erro_sql = " Campo Curso equivalente não informado.";
       $this->erro_campo = "ed140_cursoequivalente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed140_sequencial == "" || $ed140_sequencial == null ){
       $result = db_query("select nextval('cursoequivalencia_ed140_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursoequivalencia_ed140_sequencial_seq do campo: ed140_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed140_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cursoequivalencia_ed140_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed140_sequencial)){
         $this->erro_sql = " Campo ed140_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed140_sequencial = $ed140_sequencial;
       }
     }
     if(($this->ed140_sequencial == null) || ($this->ed140_sequencial == "") ){
       $this->erro_sql = " Campo ed140_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursoequivalencia(
                                       ed140_sequencial
                                      ,ed140_cursoedu
                                      ,ed140_cursoequivalente
                       )
                values (
                                $this->ed140_sequencial
                               ,$this->ed140_cursoedu
                               ,$this->ed140_cursoequivalente
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cursoequivalencia ($this->ed140_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cursoequivalencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cursoequivalencia ($this->ed140_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed140_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed140_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22323,'$this->ed140_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,4023,22323,'','".AddSlashes(pg_result($resaco,0,'ed140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4023,22324,'','".AddSlashes(pg_result($resaco,0,'ed140_cursoedu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,4023,22325,'','".AddSlashes(pg_result($resaco,0,'ed140_cursoequivalente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed140_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cursoequivalencia set ";
     $virgula = "";
     if(trim($this->ed140_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed140_sequencial"])){
       $sql  .= $virgula." ed140_sequencial = $this->ed140_sequencial ";
       $virgula = ",";
       if(trim($this->ed140_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed140_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed140_cursoedu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed140_cursoedu"])){
       $sql  .= $virgula." ed140_cursoedu = $this->ed140_cursoedu ";
       $virgula = ",";
       if(trim($this->ed140_cursoedu) == null ){
         $this->erro_sql = " Campo Curso não informado.";
         $this->erro_campo = "ed140_cursoedu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed140_cursoequivalente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed140_cursoequivalente"])){
       $sql  .= $virgula." ed140_cursoequivalente = $this->ed140_cursoequivalente ";
       $virgula = ",";
       if(trim($this->ed140_cursoequivalente) == null ){
         $this->erro_sql = " Campo Curso equivalente não informado.";
         $this->erro_campo = "ed140_cursoequivalente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed140_sequencial!=null){
       $sql .= " ed140_sequencial = $this->ed140_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed140_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22323,'$this->ed140_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed140_sequencial"]) || $this->ed140_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,4023,22323,'".AddSlashes(pg_result($resaco,$conresaco,'ed140_sequencial'))."','$this->ed140_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed140_cursoedu"]) || $this->ed140_cursoedu != "")
             $resac = db_query("insert into db_acount values($acount,4023,22324,'".AddSlashes(pg_result($resaco,$conresaco,'ed140_cursoedu'))."','$this->ed140_cursoedu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed140_cursoequivalente"]) || $this->ed140_cursoequivalente != "")
             $resac = db_query("insert into db_acount values($acount,4023,22325,'".AddSlashes(pg_result($resaco,$conresaco,'ed140_cursoequivalente'))."','$this->ed140_cursoequivalente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cursoequivalencia não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cursoequivalencia não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed140_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed140_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22323,'$ed140_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,4023,22323,'','".AddSlashes(pg_result($resaco,$iresaco,'ed140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4023,22324,'','".AddSlashes(pg_result($resaco,$iresaco,'ed140_cursoedu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,4023,22325,'','".AddSlashes(pg_result($resaco,$iresaco,'ed140_cursoequivalente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cursoequivalencia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed140_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed140_sequencial = $ed140_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cursoequivalencia não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cursoequivalencia não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed140_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursoequivalencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed140_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cursoequivalencia ";
     $sql .= "      inner join cursoedu as cursobase        on cursobase.ed29_i_codigo = cursoequivalencia.ed140_cursoedu";
     $sql .= "      inner join cursoedu as cursoequivalente on cursoequivalente.ed29_i_codigo = cursoequivalencia.ed140_cursoequivalente";
     $sql .= "      inner join ensino as ensinobase         on ensinobase.ed10_i_codigo = cursobase.ed29_i_ensino";
     $sql .= "      inner join ensino as ensinoequivalente  on ensinoequivalente.ed10_i_codigo = cursoequivalente.ed29_i_ensino";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed140_sequencial)) {
         $sql2 .= " where cursoequivalencia.ed140_sequencial = $ed140_sequencial ";
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
   public function sql_query_file ($ed140_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cursoequivalencia ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed140_sequencial)){
         $sql2 .= " where cursoequivalencia.ed140_sequencial = $ed140_sequencial ";
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
