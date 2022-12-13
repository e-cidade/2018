<?
//MODULO: farmacia
//CLASSE DA ENTIDADE integracaohorusenviodadoscompetencia
class cl_integracaohorusenviodadoscompetencia { 
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
   var $fa65_sequencial = 0; 
   var $fa65_integracaohorusenvio = 0; 
   var $fa65_dadoscompetencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa65_sequencial = int4 = C�digo 
                 fa65_integracaohorusenvio = int4 = Envio Arquivo 
                 fa65_dadoscompetencia = int4 = Dados enviados 
                 ";
   //funcao construtor da classe 
   function cl_integracaohorusenviodadoscompetencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("integracaohorusenviodadoscompetencia"); 
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
       $this->fa65_sequencial = ($this->fa65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa65_sequencial"]:$this->fa65_sequencial);
       $this->fa65_integracaohorusenvio = ($this->fa65_integracaohorusenvio == ""?@$GLOBALS["HTTP_POST_VARS"]["fa65_integracaohorusenvio"]:$this->fa65_integracaohorusenvio);
       $this->fa65_dadoscompetencia = ($this->fa65_dadoscompetencia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa65_dadoscompetencia"]:$this->fa65_dadoscompetencia);
     }else{
       $this->fa65_sequencial = ($this->fa65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa65_sequencial"]:$this->fa65_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($fa65_sequencial){ 
      $this->atualizacampos();
     if($this->fa65_integracaohorusenvio == null ){ 
       $this->erro_sql = " Campo Envio Arquivo n�o informado.";
       $this->erro_campo = "fa65_integracaohorusenvio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa65_dadoscompetencia == null ){ 
       $this->erro_sql = " Campo Dados enviados n�o informado.";
       $this->erro_campo = "fa65_dadoscompetencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa65_sequencial == "" || $fa65_sequencial == null ){
       $result = db_query("select nextval('integracaohorusenviodadoscompetencia_fa65_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: integracaohorusenviodadoscompetencia_fa65_sequencial_seq do campo: fa65_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa65_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from integracaohorusenviodadoscompetencia_fa65_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa65_sequencial)){
         $this->erro_sql = " Campo fa65_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa65_sequencial = $fa65_sequencial; 
       }
     }
     if(($this->fa65_sequencial == null) || ($this->fa65_sequencial == "") ){ 
       $this->erro_sql = " Campo fa65_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into integracaohorusenviodadoscompetencia(
                                       fa65_sequencial 
                                      ,fa65_integracaohorusenvio 
                                      ,fa65_dadoscompetencia 
                       )
                values (
                                $this->fa65_sequencial 
                               ,$this->fa65_integracaohorusenvio 
                               ,$this->fa65_dadoscompetencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados enviados por protocolo ($this->fa65_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados enviados por protocolo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados enviados por protocolo ($this->fa65_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa65_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa65_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21570,'$this->fa65_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3871,21570,'','".AddSlashes(pg_result($resaco,0,'fa65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3871,21571,'','".AddSlashes(pg_result($resaco,0,'fa65_integracaohorusenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3871,21572,'','".AddSlashes(pg_result($resaco,0,'fa65_dadoscompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($fa65_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update integracaohorusenviodadoscompetencia set ";
     $virgula = "";
     if(trim($this->fa65_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa65_sequencial"])){ 
       $sql  .= $virgula." fa65_sequencial = $this->fa65_sequencial ";
       $virgula = ",";
       if(trim($this->fa65_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "fa65_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa65_integracaohorusenvio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa65_integracaohorusenvio"])){ 
       $sql  .= $virgula." fa65_integracaohorusenvio = $this->fa65_integracaohorusenvio ";
       $virgula = ",";
       if(trim($this->fa65_integracaohorusenvio) == null ){ 
         $this->erro_sql = " Campo Envio Arquivo n�o informado.";
         $this->erro_campo = "fa65_integracaohorusenvio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa65_dadoscompetencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa65_dadoscompetencia"])){ 
       $sql  .= $virgula." fa65_dadoscompetencia = $this->fa65_dadoscompetencia ";
       $virgula = ",";
       if(trim($this->fa65_dadoscompetencia) == null ){ 
         $this->erro_sql = " Campo Dados enviados n�o informado.";
         $this->erro_campo = "fa65_dadoscompetencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa65_sequencial!=null){
       $sql .= " fa65_sequencial = $this->fa65_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa65_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21570,'$this->fa65_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa65_sequencial"]) || $this->fa65_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3871,21570,'".AddSlashes(pg_result($resaco,$conresaco,'fa65_sequencial'))."','$this->fa65_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa65_integracaohorusenvio"]) || $this->fa65_integracaohorusenvio != "")
             $resac = db_query("insert into db_acount values($acount,3871,21571,'".AddSlashes(pg_result($resaco,$conresaco,'fa65_integracaohorusenvio'))."','$this->fa65_integracaohorusenvio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa65_dadoscompetencia"]) || $this->fa65_dadoscompetencia != "")
             $resac = db_query("insert into db_acount values($acount,3871,21572,'".AddSlashes(pg_result($resaco,$conresaco,'fa65_dadoscompetencia'))."','$this->fa65_dadoscompetencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados enviados por protocolo n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa65_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados enviados por protocolo n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa65_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa65_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($fa65_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa65_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21570,'$fa65_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3871,21570,'','".AddSlashes(pg_result($resaco,$iresaco,'fa65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3871,21571,'','".AddSlashes(pg_result($resaco,$iresaco,'fa65_integracaohorusenvio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3871,21572,'','".AddSlashes(pg_result($resaco,$iresaco,'fa65_dadoscompetencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from integracaohorusenviodadoscompetencia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa65_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa65_sequencial = $fa65_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados enviados por protocolo n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa65_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados enviados por protocolo n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa65_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa65_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:integracaohorusenviodadoscompetencia";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($fa65_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from integracaohorusenviodadoscompetencia ";
     $sql .= "      inner join integracaohorusenvio  on  integracaohorusenvio.fa64_sequencial = integracaohorusenviodadoscompetencia.fa65_integracaohorusenvio";
     $sql .= "      inner join integracaohorus  on  integracaohorus.fa59_codigo = integracaohorusenvio.fa64_integracaohorus";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa65_sequencial)) {
         $sql2 .= " where integracaohorusenviodadoscompetencia.fa65_sequencial = $fa65_sequencial "; 
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
   public function sql_query_file ($fa65_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from integracaohorusenviodadoscompetencia ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa65_sequencial)){
         $sql2 .= " where integracaohorusenviodadoscompetencia.fa65_sequencial = $fa65_sequencial "; 
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
