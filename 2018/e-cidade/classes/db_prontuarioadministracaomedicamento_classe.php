<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE prontuarioadministracaomedicamento
class cl_prontuarioadministracaomedicamento { 
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
   var $sd106_codigo = 0; 
   var $sd106_prontuario = 0; 
   var $sd106_administracaomedicamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd106_codigo = int4 = Código 
                 sd106_prontuario = int4 = Prontuario 
                 sd106_administracaomedicamento = int4 = Administração de Medicamento 
                 ";
   //funcao construtor da classe 
   function cl_prontuarioadministracaomedicamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontuarioadministracaomedicamento"); 
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
       $this->sd106_codigo = ($this->sd106_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd106_codigo"]:$this->sd106_codigo);
       $this->sd106_prontuario = ($this->sd106_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd106_prontuario"]:$this->sd106_prontuario);
       $this->sd106_administracaomedicamento = ($this->sd106_administracaomedicamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd106_administracaomedicamento"]:$this->sd106_administracaomedicamento);
     }else{
       $this->sd106_codigo = ($this->sd106_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd106_codigo"]:$this->sd106_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($sd106_codigo){ 
      $this->atualizacampos();
     if($this->sd106_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuario não informado.";
       $this->erro_campo = "sd106_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd106_administracaomedicamento == null ){ 
       $this->erro_sql = " Campo Administração de Medicamento não informado.";
       $this->erro_campo = "sd106_administracaomedicamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd106_codigo == "" || $sd106_codigo == null ){
       $result = db_query("select nextval('prontuarioadministracaomedicamento_sd106_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontuarioadministracaomedicamento_sd106_codigo_seq do campo: sd106_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd106_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontuarioadministracaomedicamento_sd106_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd106_codigo)){
         $this->erro_sql = " Campo sd106_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd106_codigo = $sd106_codigo; 
       }
     }
     if(($this->sd106_codigo == null) || ($this->sd106_codigo == "") ){ 
       $this->erro_sql = " Campo sd106_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontuarioadministracaomedicamento(
                                       sd106_codigo 
                                      ,sd106_prontuario 
                                      ,sd106_administracaomedicamento 
                       )
                values (
                                $this->sd106_codigo 
                               ,$this->sd106_prontuario 
                               ,$this->sd106_administracaomedicamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Medicamento administrado ao Paciente  ($this->sd106_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Medicamento administrado ao Paciente  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Medicamento administrado ao Paciente  ($this->sd106_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd106_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd106_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21335,'$this->sd106_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3842,21335,'','".AddSlashes(pg_result($resaco,0,'sd106_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3842,21336,'','".AddSlashes(pg_result($resaco,0,'sd106_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3842,21337,'','".AddSlashes(pg_result($resaco,0,'sd106_administracaomedicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($sd106_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontuarioadministracaomedicamento set ";
     $virgula = "";
     if(trim($this->sd106_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd106_codigo"])){ 
       $sql  .= $virgula." sd106_codigo = $this->sd106_codigo ";
       $virgula = ",";
       if(trim($this->sd106_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "sd106_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd106_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd106_prontuario"])){ 
       $sql  .= $virgula." sd106_prontuario = $this->sd106_prontuario ";
       $virgula = ",";
       if(trim($this->sd106_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuario não informado.";
         $this->erro_campo = "sd106_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd106_administracaomedicamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd106_administracaomedicamento"])){ 
       $sql  .= $virgula." sd106_administracaomedicamento = $this->sd106_administracaomedicamento ";
       $virgula = ",";
       if(trim($this->sd106_administracaomedicamento) == null ){ 
         $this->erro_sql = " Campo Administração de Medicamento não informado.";
         $this->erro_campo = "sd106_administracaomedicamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd106_codigo!=null){
       $sql .= " sd106_codigo = $this->sd106_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd106_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21335,'$this->sd106_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd106_codigo"]) || $this->sd106_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3842,21335,'".AddSlashes(pg_result($resaco,$conresaco,'sd106_codigo'))."','$this->sd106_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd106_prontuario"]) || $this->sd106_prontuario != "")
             $resac = db_query("insert into db_acount values($acount,3842,21336,'".AddSlashes(pg_result($resaco,$conresaco,'sd106_prontuario'))."','$this->sd106_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd106_administracaomedicamento"]) || $this->sd106_administracaomedicamento != "")
             $resac = db_query("insert into db_acount values($acount,3842,21337,'".AddSlashes(pg_result($resaco,$conresaco,'sd106_administracaomedicamento'))."','$this->sd106_administracaomedicamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamento administrado ao Paciente  não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd106_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamento administrado ao Paciente  não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd106_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd106_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($sd106_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd106_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21335,'$sd106_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3842,21335,'','".AddSlashes(pg_result($resaco,$iresaco,'sd106_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3842,21336,'','".AddSlashes(pg_result($resaco,$iresaco,'sd106_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3842,21337,'','".AddSlashes(pg_result($resaco,$iresaco,'sd106_administracaomedicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from prontuarioadministracaomedicamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd106_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd106_codigo = $sd106_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamento administrado ao Paciente  não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd106_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamento administrado ao Paciente  não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd106_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd106_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontuarioadministracaomedicamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($sd106_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from prontuarioadministracaomedicamento ";
     $sql .= "      inner join administracaomedicamento  on  administracaomedicamento.sd105_codigo = prontuarioadministracaomedicamento.sd106_administracaomedicamento";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontuarioadministracaomedicamento.sd106_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = administracaomedicamento.sd105_usuario";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = administracaomedicamento.sd105_unidadesaida";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join setorambulatorial  on  setorambulatorial.sd91_codigo = prontuarios.sd24_setorambulatorial";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd106_codigo)) {
         $sql2 .= " where prontuarioadministracaomedicamento.sd106_codigo = $sd106_codigo "; 
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
   public function sql_query_file ($sd106_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from prontuarioadministracaomedicamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd106_codigo)){
         $sql2 .= " where prontuarioadministracaomedicamento.sd106_codigo = $sd106_codigo "; 
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
  public function sql_query_administracao ($sd106_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from prontuarioadministracaomedicamento ";
    $sql .= "      inner join administracaomedicamento  on  administracaomedicamento.sd105_codigo = prontuarioadministracaomedicamento.sd106_administracaomedicamento";
    $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo       = prontuarioadministracaomedicamento.sd106_prontuario";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario          = administracaomedicamento.sd105_usuario";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid              = administracaomedicamento.sd105_unidadesaida";
    $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = administracaomedicamento.sd105_medicamento";
    $sql .= "      inner join matmater        on  matmater.m60_codmater        = far_matersaude.fa01_i_codmater";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($sd106_codigo)){
        $sql2 .= " where prontuarioadministracaomedicamento.sd106_codigo = $sd106_codigo ";
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
