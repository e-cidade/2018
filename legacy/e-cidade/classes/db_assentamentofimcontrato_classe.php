<?
//MODULO: recursoshumanos
//CLASSE DA ENTIDADE assentamentofimcontrato
class cl_assentamentofimcontrato { 
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
   var $h15_sequencial = 0; 
   var $h15_instituicao = 0; 
   var $h15_assentamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h15_sequencial = int4 = Código 
                 h15_instituicao = int4 = Instituição 
                 h15_assentamento = int4 = Assentamento 
                 ";
   //funcao construtor da classe 
   function cl_assentamentofimcontrato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assentamentofimcontrato"); 
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
       $this->h15_sequencial = ($this->h15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h15_sequencial"]:$this->h15_sequencial);
       $this->h15_instituicao = ($this->h15_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["h15_instituicao"]:$this->h15_instituicao);
       $this->h15_assentamento = ($this->h15_assentamento == ""?@$GLOBALS["HTTP_POST_VARS"]["h15_assentamento"]:$this->h15_assentamento);
     }else{
       $this->h15_sequencial = ($this->h15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h15_sequencial"]:$this->h15_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($h15_sequencial){ 
      $this->atualizacampos();
     if($this->h15_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "h15_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h15_assentamento == null ){ 
       $this->erro_sql = " Campo Assentamento não informado.";
       $this->erro_campo = "h15_assentamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h15_sequencial == "" || $h15_sequencial == null ){
       $result = db_query("select nextval('assentamentofimcontrato_h15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assentamentofimcontrato_h15_sequencial_seq do campo: h15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from assentamentofimcontrato_h15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h15_sequencial)){
         $this->erro_sql = " Campo h15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h15_sequencial = $h15_sequencial; 
       }
     }
     if(($this->h15_sequencial == null) || ($this->h15_sequencial == "") ){ 
       $this->erro_sql = " Campo h15_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assentamentofimcontrato(
                                       h15_sequencial 
                                      ,h15_instituicao 
                                      ,h15_assentamento 
                       )
                values (
                                $this->h15_sequencial 
                               ,$this->h15_instituicao 
                               ,$this->h15_assentamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assentamentos de Fim de Contrato ($this->h15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assentamentos de Fim de Contrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assentamentos de Fim de Contrato ($this->h15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h15_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21960,'$this->h15_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3953,21960,'','".AddSlashes(pg_result($resaco,0,'h15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3953,21961,'','".AddSlashes(pg_result($resaco,0,'h15_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3953,21962,'','".AddSlashes(pg_result($resaco,0,'h15_assentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update assentamentofimcontrato set ";
     $virgula = "";
     if(trim($this->h15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h15_sequencial"])){ 
       $sql  .= $virgula." h15_sequencial = $this->h15_sequencial ";
       $virgula = ",";
       if(trim($this->h15_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "h15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h15_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h15_instituicao"])){ 
       $sql  .= $virgula." h15_instituicao = $this->h15_instituicao ";
       $virgula = ",";
       if(trim($this->h15_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "h15_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h15_assentamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h15_assentamento"])){ 
       $sql  .= $virgula." h15_assentamento = $this->h15_assentamento ";
       $virgula = ",";
       if(trim($this->h15_assentamento) == null ){ 
         $this->erro_sql = " Campo Assentamento não informado.";
         $this->erro_campo = "h15_assentamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h15_sequencial!=null){
       $sql .= " h15_sequencial = $this->h15_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h15_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21960,'$this->h15_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h15_sequencial"]) || $this->h15_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3953,21960,'".AddSlashes(pg_result($resaco,$conresaco,'h15_sequencial'))."','$this->h15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h15_instituicao"]) || $this->h15_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3953,21961,'".AddSlashes(pg_result($resaco,$conresaco,'h15_instituicao'))."','$this->h15_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h15_assentamento"]) || $this->h15_assentamento != "")
             $resac = db_query("insert into db_acount values($acount,3953,21962,'".AddSlashes(pg_result($resaco,$conresaco,'h15_assentamento'))."','$this->h15_assentamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos de Fim de Contrato não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos de Fim de Contrato não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h15_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h15_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21960,'$h15_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3953,21960,'','".AddSlashes(pg_result($resaco,$iresaco,'h15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3953,21961,'','".AddSlashes(pg_result($resaco,$iresaco,'h15_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3953,21962,'','".AddSlashes(pg_result($resaco,$iresaco,'h15_assentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from assentamentofimcontrato
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h15_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h15_sequencial = $h15_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos de Fim de Contrato não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos de Fim de Contrato não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:assentamentofimcontrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h15_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from assentamentofimcontrato ";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assentamentofimcontrato.h15_assentamento";
     $sql .= "      inner join rhparam  on  rhparam.h36_instit = assentamentofimcontrato.h15_instituicao";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql .= "      inner join db_config  on  db_config.codigo = rhparam.h36_instit";
     $sql .= "      left  join tipoasse  on  tipoasse.h12_codigo = rhparam.h36_temposemcontribuicao and  tipoasse.h12_codigo = rhparam.h36_tempocontribuicaorgps and  tipoasse.h12_codigo = rhparam.h36_tempocontribuicaorpps and  tipoasse.h12_codigo = rhparam.h36_temposficticios";
     $sql .= "      left  join db_relatorio  on  db_relatorio.db63_sequencial = rhparam.h36_modportariacoletiva and  db_relatorio.db63_sequencial = rhparam.h36_modportariaindividual and  db_relatorio.db63_sequencial = rhparam.h36_modtermoposse";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h15_sequencial)) {
         $sql2 .= " where assentamentofimcontrato.h15_sequencial = $h15_sequencial "; 
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
   public function sql_query_file ($h15_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from assentamentofimcontrato ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h15_sequencial)){
         $sql2 .= " where assentamentofimcontrato.h15_sequencial = $h15_sequencial "; 
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
