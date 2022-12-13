<?
//MODULO: caixa
//CLASSE DA ENTIDADE infracaotransito
class cl_infracaotransito { 
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
   var $i05_sequencial = 0; 
   var $i05_codigo = null; 
   var $i05_nivel = 0; 
   var $i05_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 i05_sequencial = int4 = Cadastro sequencial 
                 i05_codigo = varchar(10) = Código da Infração 
                 i05_nivel = int4 = Código do Nível 
                 i05_descricao = varchar(100) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_infracaotransito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("infracaotransito"); 
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
       $this->i05_sequencial = ($this->i05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["i05_sequencial"]:$this->i05_sequencial);
       $this->i05_codigo = ($this->i05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i05_codigo"]:$this->i05_codigo);
       $this->i05_nivel = ($this->i05_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["i05_nivel"]:$this->i05_nivel);
       $this->i05_descricao = ($this->i05_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["i05_descricao"]:$this->i05_descricao);
     }else{
       $this->i05_sequencial = ($this->i05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["i05_sequencial"]:$this->i05_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($i05_sequencial){ 
      $this->atualizacampos();
     if($this->i05_codigo == null ){ 
       $this->erro_sql = " Campo Código da Infração não informado.";
       $this->erro_campo = "i05_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i05_nivel == null ){ 
       $this->erro_sql = " Campo Código do Nível não informado.";
       $this->erro_campo = "i05_nivel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i05_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "i05_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($i05_sequencial == "" || $i05_sequencial == null ){
       $result = db_query("select nextval('infracaotransito_i05_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: infracaotransito_i05_sequencial_seq do campo: i05_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->i05_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from infracaotransito_i05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $i05_sequencial)){
         $this->erro_sql = " Campo i05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->i05_sequencial = $i05_sequencial; 
       }
     }
     if(($this->i05_sequencial == null) || ($this->i05_sequencial == "") ){ 
       $this->erro_sql = " Campo i05_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into infracaotransito(
                                       i05_sequencial 
                                      ,i05_codigo 
                                      ,i05_nivel 
                                      ,i05_descricao 
                       )
                values (
                                $this->i05_sequencial 
                               ,'$this->i05_codigo' 
                               ,$this->i05_nivel 
                               ,'$this->i05_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "infracaotransito ($this->i05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "infracaotransito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "infracaotransito ($this->i05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->i05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->i05_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009422,'$this->i05_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010224,1009422,'','".AddSlashes(pg_result($resaco,0,'i05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010224,1009425,'','".AddSlashes(pg_result($resaco,0,'i05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010224,1009423,'','".AddSlashes(pg_result($resaco,0,'i05_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010224,1009424,'','".AddSlashes(pg_result($resaco,0,'i05_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($i05_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update infracaotransito set ";
     $virgula = "";
     if(trim($this->i05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i05_sequencial"])){ 
       $sql  .= $virgula." i05_sequencial = $this->i05_sequencial ";
       $virgula = ",";
       if(trim($this->i05_sequencial) == null ){ 
         $this->erro_sql = " Campo Cadastro sequencial não informado.";
         $this->erro_campo = "i05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i05_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i05_codigo"])){ 
       $sql  .= $virgula." i05_codigo = '$this->i05_codigo' ";
       $virgula = ",";
       if(trim($this->i05_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Infração não informado.";
         $this->erro_campo = "i05_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i05_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i05_nivel"])){ 
       $sql  .= $virgula." i05_nivel = $this->i05_nivel ";
       $virgula = ",";
       if(trim($this->i05_nivel) == null ){ 
         $this->erro_sql = " Campo Código do Nível não informado.";
         $this->erro_campo = "i05_nivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i05_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i05_descricao"])){ 
       $sql  .= $virgula." i05_descricao = '$this->i05_descricao' ";
       $virgula = ",";
       if(trim($this->i05_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "i05_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($i05_sequencial!=null){
       $sql .= " i05_sequencial = $this->i05_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->i05_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009422,'$this->i05_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["i05_sequencial"]) || $this->i05_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010224,1009422,'".AddSlashes(pg_result($resaco,$conresaco,'i05_sequencial'))."','$this->i05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["i05_codigo"]) || $this->i05_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010224,1009425,'".AddSlashes(pg_result($resaco,$conresaco,'i05_codigo'))."','$this->i05_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["i05_nivel"]) || $this->i05_nivel != "")
             $resac = db_query("insert into db_acount values($acount,1010224,1009423,'".AddSlashes(pg_result($resaco,$conresaco,'i05_nivel'))."','$this->i05_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["i05_descricao"]) || $this->i05_descricao != "")
             $resac = db_query("insert into db_acount values($acount,1010224,1009424,'".AddSlashes(pg_result($resaco,$conresaco,'i05_descricao'))."','$this->i05_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "infracaotransito não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->i05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "infracaotransito não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->i05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->i05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($i05_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($i05_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009422,'$i05_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010224,1009422,'','".AddSlashes(pg_result($resaco,$iresaco,'i05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010224,1009425,'','".AddSlashes(pg_result($resaco,$iresaco,'i05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010224,1009423,'','".AddSlashes(pg_result($resaco,$iresaco,'i05_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010224,1009424,'','".AddSlashes(pg_result($resaco,$iresaco,'i05_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from infracaotransito
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($i05_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " i05_sequencial = $i05_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "infracaotransito não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$i05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "infracaotransito não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$i05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$i05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:infracaotransito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($i05_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from infracaotransito ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($i05_sequencial)) {
         $sql2 .= " where infracaotransito.i05_sequencial = $i05_sequencial "; 
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
   public function sql_query_file ($i05_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from infracaotransito ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($i05_sequencial)){
         $sql2 .= " where infracaotransito.i05_sequencial = $i05_sequencial "; 
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
