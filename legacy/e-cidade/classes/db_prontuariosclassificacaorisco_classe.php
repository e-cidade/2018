<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE prontuariosclassificacaorisco
class cl_prontuariosclassificacaorisco { 
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
   var $sd101_codigo = 0; 
   var $sd101_prontuarios = 0; 
   var $sd101_classificacaorisco = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd101_codigo = int4 = Código 
                 sd101_prontuarios = int4 = Prontuário 
                 sd101_classificacaorisco = int4 = Classificação de Risco 
                 ";
   //funcao construtor da classe 
   function cl_prontuariosclassificacaorisco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontuariosclassificacaorisco"); 
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
       $this->sd101_codigo = ($this->sd101_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd101_codigo"]:$this->sd101_codigo);
       $this->sd101_prontuarios = ($this->sd101_prontuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd101_prontuarios"]:$this->sd101_prontuarios);
       $this->sd101_classificacaorisco = ($this->sd101_classificacaorisco == ""?@$GLOBALS["HTTP_POST_VARS"]["sd101_classificacaorisco"]:$this->sd101_classificacaorisco);
     }else{
       $this->sd101_codigo = ($this->sd101_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd101_codigo"]:$this->sd101_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd101_codigo){ 
      $this->atualizacampos();
     if($this->sd101_prontuarios == null ){ 
       $this->erro_sql = " Campo Prontuário não informado.";
       $this->erro_campo = "sd101_prontuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd101_classificacaorisco == null ){ 
       $this->erro_sql = " Campo Classificação de Risco não informado.";
       $this->erro_campo = "sd101_classificacaorisco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd101_codigo == "" || $sd101_codigo == null ){
       $result = db_query("select nextval('prontuariosclassificacaorisco_sd101_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontuariosclassificacaorisco_sd101_codigo_seq do campo: sd101_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd101_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontuariosclassificacaorisco_sd101_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd101_codigo)){
         $this->erro_sql = " Campo sd101_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd101_codigo = $sd101_codigo; 
       }
     }
     if(($this->sd101_codigo == null) || ($this->sd101_codigo == "") ){ 
       $this->erro_sql = " Campo sd101_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontuariosclassificacaorisco(
                                       sd101_codigo 
                                      ,sd101_prontuarios 
                                      ,sd101_classificacaorisco 
                       )
                values (
                                $this->sd101_codigo 
                               ,$this->sd101_prontuarios 
                               ,$this->sd101_classificacaorisco 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuarios com classificação de risco ($this->sd101_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuarios com classificação de risco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuarios com classificação de risco ($this->sd101_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd101_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd101_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20919,'$this->sd101_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3764,20919,'','".AddSlashes(pg_result($resaco,0,'sd101_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3764,20920,'','".AddSlashes(pg_result($resaco,0,'sd101_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3764,20921,'','".AddSlashes(pg_result($resaco,0,'sd101_classificacaorisco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd101_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontuariosclassificacaorisco set ";
     $virgula = "";
     if(trim($this->sd101_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd101_codigo"])){ 
       $sql  .= $virgula." sd101_codigo = $this->sd101_codigo ";
       $virgula = ",";
       if(trim($this->sd101_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd101_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd101_prontuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd101_prontuarios"])){ 
       $sql  .= $virgula." sd101_prontuarios = $this->sd101_prontuarios ";
       $virgula = ",";
       if(trim($this->sd101_prontuarios) == null ){ 
         $this->erro_sql = " Campo Prontuário não informado.";
         $this->erro_campo = "sd101_prontuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd101_classificacaorisco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd101_classificacaorisco"])){ 
       $sql  .= $virgula." sd101_classificacaorisco = $this->sd101_classificacaorisco ";
       $virgula = ",";
       if(trim($this->sd101_classificacaorisco) == null ){ 
         $this->erro_sql = " Campo Classificação de Risco não informado.";
         $this->erro_campo = "sd101_classificacaorisco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd101_codigo!=null){
       $sql .= " sd101_codigo = $this->sd101_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd101_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20919,'$this->sd101_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd101_codigo"]) || $this->sd101_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3764,20919,'".AddSlashes(pg_result($resaco,$conresaco,'sd101_codigo'))."','$this->sd101_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd101_prontuarios"]) || $this->sd101_prontuarios != "")
             $resac = db_query("insert into db_acount values($acount,3764,20920,'".AddSlashes(pg_result($resaco,$conresaco,'sd101_prontuarios'))."','$this->sd101_prontuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd101_classificacaorisco"]) || $this->sd101_classificacaorisco != "")
             $resac = db_query("insert into db_acount values($acount,3764,20921,'".AddSlashes(pg_result($resaco,$conresaco,'sd101_classificacaorisco'))."','$this->sd101_classificacaorisco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuarios com classificação de risco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd101_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prontuarios com classificação de risco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd101_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd101_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd101_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd101_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20919,'$sd101_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3764,20919,'','".AddSlashes(pg_result($resaco,$iresaco,'sd101_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3764,20920,'','".AddSlashes(pg_result($resaco,$iresaco,'sd101_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3764,20921,'','".AddSlashes(pg_result($resaco,$iresaco,'sd101_classificacaorisco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from prontuariosclassificacaorisco
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd101_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd101_codigo = $sd101_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuarios com classificação de risco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd101_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prontuarios com classificação de risco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd101_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd101_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontuariosclassificacaorisco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd101_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from prontuariosclassificacaorisco ";
     $sql .= "      inner join classificacaorisco  on  classificacaorisco.sd78_codigo = prontuariosclassificacaorisco.sd101_classificacaorisco";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontuariosclassificacaorisco.sd101_prontuarios";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd101_codigo)) {
         $sql2 .= " where prontuariosclassificacaorisco.sd101_codigo = $sd101_codigo "; 
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
   public function sql_query_file ($sd101_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from prontuariosclassificacaorisco ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd101_codigo)){
         $sql2 .= " where prontuariosclassificacaorisco.sd101_codigo = $sd101_codigo "; 
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
