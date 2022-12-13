<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE motivoalta
class cl_motivoalta { 
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
   var $sd01_codigo = 0; 
   var $sd01_codigosus = 0; 
   var $sd01_descricao = null; 
   var $sd01_finalizaatendimento = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd01_codigo = int4 = Código 
                 sd01_codigosus = int4 = Código SUS 
                 sd01_descricao = varchar(80) = Descrição 
                 sd01_finalizaatendimento = bool = Finaliza Atendimento 
                 ";
   //funcao construtor da classe 
   function cl_motivoalta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("motivoalta"); 
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
       $this->sd01_codigo = ($this->sd01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd01_codigo"]:$this->sd01_codigo);
       $this->sd01_codigosus = ($this->sd01_codigosus == ""?@$GLOBALS["HTTP_POST_VARS"]["sd01_codigosus"]:$this->sd01_codigosus);
       $this->sd01_descricao = ($this->sd01_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd01_descricao"]:$this->sd01_descricao);
       $this->sd01_finalizaatendimento = ($this->sd01_finalizaatendimento == "f"?@$GLOBALS["HTTP_POST_VARS"]["sd01_finalizaatendimento"]:$this->sd01_finalizaatendimento);
     }else{
       $this->sd01_codigo = ($this->sd01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd01_codigo"]:$this->sd01_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd01_codigo){ 
      $this->atualizacampos();
     if($this->sd01_codigosus == null ){ 
       $this->erro_sql = " Campo Código SUS não informado.";
       $this->erro_campo = "sd01_codigosus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd01_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "sd01_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd01_finalizaatendimento == null ){ 
       $this->erro_sql = " Campo Finaliza Atendimento não informado.";
       $this->erro_campo = "sd01_finalizaatendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd01_codigo == "" || $sd01_codigo == null ){
       $result = db_query("select nextval('motivoalta_sd01_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: motivoalta_sd01_codigo_seq do campo: sd01_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd01_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from motivoalta_sd01_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd01_codigo)){
         $this->erro_sql = " Campo sd01_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd01_codigo = $sd01_codigo; 
       }
     }
     if(($this->sd01_codigo == null) || ($this->sd01_codigo == "") ){ 
       $this->erro_sql = " Campo sd01_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into motivoalta(
                                       sd01_codigo 
                                      ,sd01_codigosus 
                                      ,sd01_descricao 
                                      ,sd01_finalizaatendimento 
                       )
                values (
                                $this->sd01_codigo 
                               ,$this->sd01_codigosus 
                               ,'$this->sd01_descricao' 
                               ,'$this->sd01_finalizaatendimento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Motivos de Alta ($this->sd01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Motivos de Alta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Motivos de Alta ($this->sd01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd01_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd01_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20903,'$this->sd01_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3761,20903,'','".AddSlashes(pg_result($resaco,0,'sd01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3761,20904,'','".AddSlashes(pg_result($resaco,0,'sd01_codigosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3761,20905,'','".AddSlashes(pg_result($resaco,0,'sd01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3761,20906,'','".AddSlashes(pg_result($resaco,0,'sd01_finalizaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd01_codigo=null) { 
      $this->atualizacampos();
     $sql = " update motivoalta set ";
     $virgula = "";
     if(trim($this->sd01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd01_codigo"])){ 
       $sql  .= $virgula." sd01_codigo = $this->sd01_codigo ";
       $virgula = ",";
       if(trim($this->sd01_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd01_codigosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd01_codigosus"])){ 
       $sql  .= $virgula." sd01_codigosus = $this->sd01_codigosus ";
       $virgula = ",";
       if(trim($this->sd01_codigosus) == null ){ 
         $this->erro_sql = " Campo Código SUS não informado.";
         $this->erro_campo = "sd01_codigosus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd01_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd01_descricao"])){ 
       $sql  .= $virgula." sd01_descricao = '$this->sd01_descricao' ";
       $virgula = ",";
       if(trim($this->sd01_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "sd01_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd01_finalizaatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd01_finalizaatendimento"])){ 
       $sql  .= $virgula." sd01_finalizaatendimento = '$this->sd01_finalizaatendimento' ";
       $virgula = ",";
       if(trim($this->sd01_finalizaatendimento) == null ){ 
         $this->erro_sql = " Campo Finaliza Atendimento não informado.";
         $this->erro_campo = "sd01_finalizaatendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd01_codigo!=null){
       $sql .= " sd01_codigo = $this->sd01_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd01_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20903,'$this->sd01_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd01_codigo"]) || $this->sd01_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3761,20903,'".AddSlashes(pg_result($resaco,$conresaco,'sd01_codigo'))."','$this->sd01_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd01_codigosus"]) || $this->sd01_codigosus != "")
             $resac = db_query("insert into db_acount values($acount,3761,20904,'".AddSlashes(pg_result($resaco,$conresaco,'sd01_codigosus'))."','$this->sd01_codigosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd01_descricao"]) || $this->sd01_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3761,20905,'".AddSlashes(pg_result($resaco,$conresaco,'sd01_descricao'))."','$this->sd01_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd01_finalizaatendimento"]) || $this->sd01_finalizaatendimento != "")
             $resac = db_query("insert into db_acount values($acount,3761,20906,'".AddSlashes(pg_result($resaco,$conresaco,'sd01_finalizaatendimento'))."','$this->sd01_finalizaatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Motivos de Alta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Motivos de Alta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd01_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd01_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20903,'$sd01_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3761,20903,'','".AddSlashes(pg_result($resaco,$iresaco,'sd01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3761,20904,'','".AddSlashes(pg_result($resaco,$iresaco,'sd01_codigosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3761,20905,'','".AddSlashes(pg_result($resaco,$iresaco,'sd01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3761,20906,'','".AddSlashes(pg_result($resaco,$iresaco,'sd01_finalizaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from motivoalta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd01_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd01_codigo = $sd01_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Motivos de Alta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Motivos de Alta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd01_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:motivoalta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd01_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from motivoalta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd01_codigo)) {
         $sql2 .= " where motivoalta.sd01_codigo = $sd01_codigo "; 
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
   public function sql_query_file ($sd01_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from motivoalta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd01_codigo)){
         $sql2 .= " where motivoalta.sd01_codigo = $sd01_codigo "; 
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
