<?
//MODULO: laboratorio
//CLASSE DA ENTIDADE medicamentoslaboratoriorequiitem
class cl_medicamentoslaboratoriorequiitem { 
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
   var $la44_sequencial = 0; 
   var $la44_medicamentoslaboratorio = 0; 
   var $la44_requiitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la44_sequencial = int4 = Código 
                 la44_medicamentoslaboratorio = int4 = Medicamento 
                 la44_requiitem = int4 = Exame 
                 ";
   //funcao construtor da classe 
   function cl_medicamentoslaboratoriorequiitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("medicamentoslaboratoriorequiitem"); 
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
       $this->la44_sequencial = ($this->la44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la44_sequencial"]:$this->la44_sequencial);
       $this->la44_medicamentoslaboratorio = ($this->la44_medicamentoslaboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la44_medicamentoslaboratorio"]:$this->la44_medicamentoslaboratorio);
       $this->la44_requiitem = ($this->la44_requiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["la44_requiitem"]:$this->la44_requiitem);
     }else{
       $this->la44_sequencial = ($this->la44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la44_sequencial"]:$this->la44_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($la44_sequencial){ 
      $this->atualizacampos();
     if($this->la44_medicamentoslaboratorio == null ){ 
       $this->erro_sql = " Campo Medicamento não informado.";
       $this->erro_campo = "la44_medicamentoslaboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la44_requiitem == null ){ 
       $this->erro_sql = " Campo Exame não informado.";
       $this->erro_campo = "la44_requiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la44_sequencial == "" || $la44_sequencial == null ){
       $result = db_query("select nextval('medicamentoslaboratoriorequiitem_la44_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: medicamentoslaboratoriorequiitem_la44_sequencial_seq do campo: la44_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la44_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from medicamentoslaboratoriorequiitem_la44_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $la44_sequencial)){
         $this->erro_sql = " Campo la44_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la44_sequencial = $la44_sequencial; 
       }
     }
     if(($this->la44_sequencial == null) || ($this->la44_sequencial == "") ){ 
       $this->erro_sql = " Campo la44_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into medicamentoslaboratoriorequiitem(
                                       la44_sequencial 
                                      ,la44_medicamentoslaboratorio 
                                      ,la44_requiitem 
                       )
                values (
                                $this->la44_sequencial 
                               ,$this->la44_medicamentoslaboratorio 
                               ,$this->la44_requiitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Medicamentos utilizados no exame ($this->la44_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Medicamentos utilizados no exame já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Medicamentos utilizados no exame ($this->la44_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la44_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la44_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21631,'$this->la44_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3886,21631,'','".AddSlashes(pg_result($resaco,0,'la44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3886,21632,'','".AddSlashes(pg_result($resaco,0,'la44_medicamentoslaboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3886,21633,'','".AddSlashes(pg_result($resaco,0,'la44_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($la44_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update medicamentoslaboratoriorequiitem set ";
     $virgula = "";
     if(trim($this->la44_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la44_sequencial"])){ 
       $sql  .= $virgula." la44_sequencial = $this->la44_sequencial ";
       $virgula = ",";
       if(trim($this->la44_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la44_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la44_medicamentoslaboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la44_medicamentoslaboratorio"])){ 
       $sql  .= $virgula." la44_medicamentoslaboratorio = $this->la44_medicamentoslaboratorio ";
       $virgula = ",";
       if(trim($this->la44_medicamentoslaboratorio) == null ){ 
         $this->erro_sql = " Campo Medicamento não informado.";
         $this->erro_campo = "la44_medicamentoslaboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la44_requiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la44_requiitem"])){ 
       $sql  .= $virgula." la44_requiitem = $this->la44_requiitem ";
       $virgula = ",";
       if(trim($this->la44_requiitem) == null ){ 
         $this->erro_sql = " Campo Exame não informado.";
         $this->erro_campo = "la44_requiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la44_sequencial!=null){
       $sql .= " la44_sequencial = $this->la44_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la44_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21631,'$this->la44_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la44_sequencial"]) || $this->la44_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3886,21631,'".AddSlashes(pg_result($resaco,$conresaco,'la44_sequencial'))."','$this->la44_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la44_medicamentoslaboratorio"]) || $this->la44_medicamentoslaboratorio != "")
             $resac = db_query("insert into db_acount values($acount,3886,21632,'".AddSlashes(pg_result($resaco,$conresaco,'la44_medicamentoslaboratorio'))."','$this->la44_medicamentoslaboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la44_requiitem"]) || $this->la44_requiitem != "")
             $resac = db_query("insert into db_acount values($acount,3886,21633,'".AddSlashes(pg_result($resaco,$conresaco,'la44_requiitem'))."','$this->la44_requiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamentos utilizados no exame não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamentos utilizados no exame não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($la44_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la44_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21631,'$la44_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3886,21631,'','".AddSlashes(pg_result($resaco,$iresaco,'la44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3886,21632,'','".AddSlashes(pg_result($resaco,$iresaco,'la44_medicamentoslaboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3886,21633,'','".AddSlashes(pg_result($resaco,$iresaco,'la44_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from medicamentoslaboratoriorequiitem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la44_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la44_sequencial = $la44_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamentos utilizados no exame não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamentos utilizados no exame não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la44_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:medicamentoslaboratoriorequiitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($la44_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from medicamentoslaboratoriorequiitem ";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = medicamentoslaboratoriorequiitem.la44_requiitem";
     $sql .= "      inner join medicamentoslaboratorio  on  medicamentoslaboratorio.la43_sequencial = medicamentoslaboratoriorequiitem.la44_medicamentoslaboratorio";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la44_sequencial)) {
         $sql2 .= " where medicamentoslaboratoriorequiitem.la44_sequencial = $la44_sequencial "; 
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
   public function sql_query_file ($la44_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from medicamentoslaboratoriorequiitem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la44_sequencial)){
         $sql2 .= " where medicamentoslaboratoriorequiitem.la44_sequencial = $la44_sequencial "; 
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
