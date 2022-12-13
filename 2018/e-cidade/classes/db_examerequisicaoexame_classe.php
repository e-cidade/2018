<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE examerequisicaoexame
class cl_examerequisicaoexame { 
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
   var $sd104_codigo = 0; 
   var $sd104_requisicaoexameprontuario = 0; 
   var $sd104_lab_exame = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd104_codigo = int4 = Codigo 
                 sd104_requisicaoexameprontuario = int4 = Requisição 
                 sd104_lab_exame = int4 = Exame 
                 ";
   //funcao construtor da classe 
   function cl_examerequisicaoexame() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("examerequisicaoexame"); 
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
       $this->sd104_codigo = ($this->sd104_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd104_codigo"]:$this->sd104_codigo);
       $this->sd104_requisicaoexameprontuario = ($this->sd104_requisicaoexameprontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd104_requisicaoexameprontuario"]:$this->sd104_requisicaoexameprontuario);
       $this->sd104_lab_exame = ($this->sd104_lab_exame == ""?@$GLOBALS["HTTP_POST_VARS"]["sd104_lab_exame"]:$this->sd104_lab_exame);
     }else{
       $this->sd104_codigo = ($this->sd104_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd104_codigo"]:$this->sd104_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd104_codigo){ 
      $this->atualizacampos();
     if($this->sd104_requisicaoexameprontuario == null ){ 
       $this->erro_sql = " Campo Requisição não informado.";
       $this->erro_campo = "sd104_requisicaoexameprontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd104_lab_exame == null ){ 
       $this->erro_sql = " Campo Exame não informado.";
       $this->erro_campo = "sd104_lab_exame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd104_codigo == "" || $sd104_codigo == null ){
       $result = db_query("select nextval('examerequisicaoexame_sd104_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: examerequisicaoexame_sd104_codigo_seq do campo: sd104_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd104_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from examerequisicaoexame_sd104_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd104_codigo)){
         $this->erro_sql = " Campo sd104_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd104_codigo = $sd104_codigo; 
       }
     }
     if(($this->sd104_codigo == null) || ($this->sd104_codigo == "") ){ 
       $this->erro_sql = " Campo sd104_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into examerequisicaoexame(
                                       sd104_codigo 
                                      ,sd104_requisicaoexameprontuario 
                                      ,sd104_lab_exame 
                       )
                values (
                                $this->sd104_codigo 
                               ,$this->sd104_requisicaoexameprontuario 
                               ,$this->sd104_lab_exame 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exame da requisição de exames ($this->sd104_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exame da requisição de exames já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exame da requisição de exames ($this->sd104_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd104_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd104_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20966,'$this->sd104_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3776,20966,'','".AddSlashes(pg_result($resaco,0,'sd104_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3776,20967,'','".AddSlashes(pg_result($resaco,0,'sd104_requisicaoexameprontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3776,20968,'','".AddSlashes(pg_result($resaco,0,'sd104_lab_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd104_codigo=null) { 
      $this->atualizacampos();
     $sql = " update examerequisicaoexame set ";
     $virgula = "";
     if(trim($this->sd104_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd104_codigo"])){ 
       $sql  .= $virgula." sd104_codigo = $this->sd104_codigo ";
       $virgula = ",";
       if(trim($this->sd104_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "sd104_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd104_requisicaoexameprontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd104_requisicaoexameprontuario"])){ 
       $sql  .= $virgula." sd104_requisicaoexameprontuario = $this->sd104_requisicaoexameprontuario ";
       $virgula = ",";
       if(trim($this->sd104_requisicaoexameprontuario) == null ){ 
         $this->erro_sql = " Campo Requisição não informado.";
         $this->erro_campo = "sd104_requisicaoexameprontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd104_lab_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd104_lab_exame"])){ 
       $sql  .= $virgula." sd104_lab_exame = $this->sd104_lab_exame ";
       $virgula = ",";
       if(trim($this->sd104_lab_exame) == null ){ 
         $this->erro_sql = " Campo Exame não informado.";
         $this->erro_campo = "sd104_lab_exame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd104_codigo!=null){
       $sql .= " sd104_codigo = $this->sd104_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd104_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20966,'$this->sd104_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd104_codigo"]) || $this->sd104_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3776,20966,'".AddSlashes(pg_result($resaco,$conresaco,'sd104_codigo'))."','$this->sd104_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd104_requisicaoexameprontuario"]) || $this->sd104_requisicaoexameprontuario != "")
             $resac = db_query("insert into db_acount values($acount,3776,20967,'".AddSlashes(pg_result($resaco,$conresaco,'sd104_requisicaoexameprontuario'))."','$this->sd104_requisicaoexameprontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd104_lab_exame"]) || $this->sd104_lab_exame != "")
             $resac = db_query("insert into db_acount values($acount,3776,20968,'".AddSlashes(pg_result($resaco,$conresaco,'sd104_lab_exame'))."','$this->sd104_lab_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exame da requisição de exames nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd104_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exame da requisição de exames nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd104_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd104_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20966,'$sd104_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3776,20966,'','".AddSlashes(pg_result($resaco,$iresaco,'sd104_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3776,20967,'','".AddSlashes(pg_result($resaco,$iresaco,'sd104_requisicaoexameprontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3776,20968,'','".AddSlashes(pg_result($resaco,$iresaco,'sd104_lab_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from examerequisicaoexame
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd104_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd104_codigo = $sd104_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exame da requisição de exames nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd104_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exame da requisição de exames nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd104_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:examerequisicaoexame";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd104_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from examerequisicaoexame ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = examerequisicaoexame.sd104_lab_exame";
     $sql .= "      inner join requisicaoexameprontuario  on  requisicaoexameprontuario.sd103_codigo = examerequisicaoexame.sd104_requisicaoexameprontuario";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = requisicaoexameprontuario.sd103_medicos";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = requisicaoexameprontuario.sd103_prontuarios";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd104_codigo)) {
         $sql2 .= " where examerequisicaoexame.sd104_codigo = $sd104_codigo "; 
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
   public function sql_query_file ($sd104_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from examerequisicaoexame ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd104_codigo)){
         $sql2 .= " where examerequisicaoexame.sd104_codigo = $sd104_codigo "; 
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
