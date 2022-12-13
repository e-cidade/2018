<?
//MODULO: pessoal
//CLASSE DA ENTIDADE db_usuariosrhlota
class cl_db_usuariosrhlota { 
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
   var $rh157_sequencial = 0; 
   var $rh157_usuario = 0; 
   var $rh157_lotacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh157_sequencial = int4 = Chave Primária 
                 rh157_usuario = int4 = Usuário 
                 rh157_lotacao = int4 = Lotação do Usuário 
                 ";
   //funcao construtor da classe 
   function cl_db_usuariosrhlota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_usuariosrhlota"); 
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
       $this->rh157_sequencial = ($this->rh157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh157_sequencial"]:$this->rh157_sequencial);
       $this->rh157_usuario = ($this->rh157_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh157_usuario"]:$this->rh157_usuario);
       $this->rh157_lotacao = ($this->rh157_lotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh157_lotacao"]:$this->rh157_lotacao);
     }else{
       $this->rh157_sequencial = ($this->rh157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh157_sequencial"]:$this->rh157_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh157_sequencial){ 
      $this->atualizacampos();
     if($this->rh157_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "rh157_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh157_lotacao == null ){ 
       $this->erro_sql = " Campo Lotação do Usuário não informado.";
       $this->erro_campo = "rh157_lotacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh157_sequencial == "" || $rh157_sequencial == null ){
       $result = db_query("select nextval('db_usuariosrhlota_rh157_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_usuariosrhlota_rh157_sequencial_seq do campo: rh157_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh157_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_usuariosrhlota_rh157_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh157_sequencial)){
         $this->erro_sql = " Campo rh157_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh157_sequencial = $rh157_sequencial; 
       }
     }
     if(($this->rh157_sequencial == null) || ($this->rh157_sequencial == "") ){ 
       $this->erro_sql = " Campo rh157_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_usuariosrhlota(
                                       rh157_sequencial 
                                      ,rh157_usuario 
                                      ,rh157_lotacao 
                       )
                values (
                                $this->rh157_sequencial 
                               ,$this->rh157_usuario 
                               ,$this->rh157_lotacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relaciona Usuarios a lotação ($this->rh157_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relaciona Usuarios a lotação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relaciona Usuarios a lotação ($this->rh157_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh157_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh157_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21131,'$this->rh157_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3804,21131,'','".AddSlashes(pg_result($resaco,0,'rh157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3804,21129,'','".AddSlashes(pg_result($resaco,0,'rh157_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3804,21130,'','".AddSlashes(pg_result($resaco,0,'rh157_lotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh157_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_usuariosrhlota set ";
     $virgula = "";
     if(trim($this->rh157_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh157_sequencial"])){ 
       $sql  .= $virgula." rh157_sequencial = $this->rh157_sequencial ";
       $virgula = ",";
       if(trim($this->rh157_sequencial) == null ){ 
         $this->erro_sql = " Campo Chave Primária não informado.";
         $this->erro_campo = "rh157_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh157_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh157_usuario"])){ 
       $sql  .= $virgula." rh157_usuario = $this->rh157_usuario ";
       $virgula = ",";
       if(trim($this->rh157_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "rh157_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh157_lotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh157_lotacao"])){ 
       $sql  .= $virgula." rh157_lotacao = $this->rh157_lotacao ";
       $virgula = ",";
       if(trim($this->rh157_lotacao) == null ){ 
         $this->erro_sql = " Campo Lotação do Usuário não informado.";
         $this->erro_campo = "rh157_lotacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh157_sequencial!=null){
       $sql .= " rh157_sequencial = $this->rh157_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh157_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21131,'$this->rh157_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh157_sequencial"]) || $this->rh157_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3804,21131,'".AddSlashes(pg_result($resaco,$conresaco,'rh157_sequencial'))."','$this->rh157_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh157_usuario"]) || $this->rh157_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3804,21129,'".AddSlashes(pg_result($resaco,$conresaco,'rh157_usuario'))."','$this->rh157_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh157_lotacao"]) || $this->rh157_lotacao != "")
             $resac = db_query("insert into db_acount values($acount,3804,21130,'".AddSlashes(pg_result($resaco,$conresaco,'rh157_lotacao'))."','$this->rh157_lotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relaciona Usuarios a lotação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh157_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relaciona Usuarios a lotação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh157_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh157_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21131,'$rh157_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3804,21131,'','".AddSlashes(pg_result($resaco,$iresaco,'rh157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3804,21129,'','".AddSlashes(pg_result($resaco,$iresaco,'rh157_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3804,21130,'','".AddSlashes(pg_result($resaco,$iresaco,'rh157_lotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_usuariosrhlota
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh157_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh157_sequencial = $rh157_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relaciona Usuarios a lotação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh157_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Relaciona Usuarios a lotação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh157_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_usuariosrhlota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh157_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_usuariosrhlota ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_usuariosrhlota.rh157_usuario";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = db_usuariosrhlota.rh157_lotacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhlota.r70_numcgm";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = rhlota.r70_codestrut";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhlota.r70_concarpeculiar";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh157_sequencial)) {
         $sql2 .= " where db_usuariosrhlota.rh157_sequencial = $rh157_sequencial "; 
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
   public function sql_query_file ($rh157_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from db_usuariosrhlota ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh157_sequencial)){
         $sql2 .= " where db_usuariosrhlota.rh157_sequencial = $rh157_sequencial "; 
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
