<?
//MODULO: meioambiente
//CLASSE DA ENTIDADE mensagerialicenca_db_usuarios
class cl_mensagerialicenca_db_usuarios {
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
   var $am16_sequencial = 0;
   var $am16_usuario = 0;
   var $am16_dias = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am16_sequencial = int4 = Sequencial
                 am16_usuario = int4 = Usuário
                 am16_dias = int4 = Dias
                 ";
   //funcao construtor da classe
   function cl_mensagerialicenca_db_usuarios() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mensagerialicenca_db_usuarios");
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
       $this->am16_sequencial = ($this->am16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am16_sequencial"]:$this->am16_sequencial);
       $this->am16_usuario = ($this->am16_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["am16_usuario"]:$this->am16_usuario);
       $this->am16_dias = ($this->am16_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["am16_dias"]:$this->am16_dias);
     }else{
       $this->am16_sequencial = ($this->am16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am16_sequencial"]:$this->am16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am16_sequencial){
      $this->atualizacampos();
     if($this->am16_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "am16_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am16_dias == null ){
       $this->erro_sql = " Campo Dias não informado.";
       $this->erro_campo = "am16_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am16_sequencial == "" || $am16_sequencial == null ){
       $result = db_query("select nextval('mensagerialicenca_db_usuarios_am16_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mensagerialicenca_db_usuarios_am16_sequencial_seq do campo: am16_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am16_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from mensagerialicenca_db_usuarios_am16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am16_sequencial)){
         $this->erro_sql = " Campo am16_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am16_sequencial = $am16_sequencial;
       }
     }
     if(($this->am16_sequencial == null) || ($this->am16_sequencial == "") ){
       $this->erro_sql = " Campo am16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mensagerialicenca_db_usuarios(
                                       am16_sequencial
                                      ,am16_usuario
                                      ,am16_dias
                       )
                values (
                                $this->am16_sequencial
                               ,$this->am16_usuario
                               ,$this->am16_dias
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usuário ($this->am16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usuário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usuário ($this->am16_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am16_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am16_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20980,'$this->am16_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3780,20980,'','".AddSlashes(pg_result($resaco,0,'am16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3780,20981,'','".AddSlashes(pg_result($resaco,0,'am16_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3780,20982,'','".AddSlashes(pg_result($resaco,0,'am16_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am16_sequencial=null) {
      $this->atualizacampos();
     $sql = " update mensagerialicenca_db_usuarios set ";
     $virgula = "";
     if(trim($this->am16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am16_sequencial"])){
       $sql  .= $virgula." am16_sequencial = $this->am16_sequencial ";
       $virgula = ",";
       if(trim($this->am16_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "am16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am16_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am16_usuario"])){
       $sql  .= $virgula." am16_usuario = $this->am16_usuario ";
       $virgula = ",";
       if(trim($this->am16_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "am16_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am16_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am16_dias"])){
       $sql  .= $virgula." am16_dias = $this->am16_dias ";
       $virgula = ",";
       if(trim($this->am16_dias) == null ){
         $this->erro_sql = " Campo Dias não informado.";
         $this->erro_campo = "am16_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am16_sequencial!=null){
       $sql .= " am16_sequencial = $this->am16_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am16_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20980,'$this->am16_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am16_sequencial"]) || $this->am16_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3780,20980,'".AddSlashes(pg_result($resaco,$conresaco,'am16_sequencial'))."','$this->am16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am16_usuario"]) || $this->am16_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3780,20981,'".AddSlashes(pg_result($resaco,$conresaco,'am16_usuario'))."','$this->am16_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am16_dias"]) || $this->am16_dias != "")
             $resac = db_query("insert into db_acount values($acount,3780,20982,'".AddSlashes(pg_result($resaco,$conresaco,'am16_dias'))."','$this->am16_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Usuário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am16_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am16_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20980,'$am16_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3780,20980,'','".AddSlashes(pg_result($resaco,$iresaco,'am16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3780,20981,'','".AddSlashes(pg_result($resaco,$iresaco,'am16_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3780,20982,'','".AddSlashes(pg_result($resaco,$iresaco,'am16_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from mensagerialicenca_db_usuarios
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am16_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am16_sequencial = $am16_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am16_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Usuário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am16_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:mensagerialicenca_db_usuarios";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am16_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from mensagerialicenca_db_usuarios ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mensagerialicenca_db_usuarios.am16_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am16_sequencial)) {
         $sql2 .= " where mensagerialicenca_db_usuarios.am16_sequencial = $am16_sequencial ";
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
   public function sql_query_file ($am16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from mensagerialicenca_db_usuarios ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am16_sequencial)){
         $sql2 .= " where mensagerialicenca_db_usuarios.am16_sequencial = $am16_sequencial ";
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


  /**
   * Busca usuarios que nao foram notificados
   *
   * @param string $sCampos
   * @param string $sOrdenacao
   * @param string $sWhere
   * @return string
   */
  public function sql_query_usuariosNotificar($sCampos = '*', $sOrdenacao = '', $sWhere = null) {

    $sSql  = "select {$sCampos}                                                                                    ";
    $sSql .= "   from mensagerialicenca_db_usuarios                                                                ";
    $sSql .= "        left join mensagerialicencaprocessado on am15_mensagerialicencadb_usuarios = am16_sequencial ";
    $sSql .= "    where am15_sequencial is null                                                                    ";

    if (!empty($sWhere)) {
      $sSql .= " and $sWhere ";
    }

    if (!empty($sOrdenacao)) {
      $sSql .= " order by $sOrdenacao";
    }

    return $sSql;
  }

}
