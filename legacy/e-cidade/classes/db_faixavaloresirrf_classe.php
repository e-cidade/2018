<?
//MODULO: pessoal
//CLASSE DA ENTIDADE faixavaloresirrf
class cl_faixavaloresirrf { 
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
   var $rh175_sequencial = 0; 
   var $rh175_db_faixavalores = 0; 
   var $rh175_percentual = 0; 
   var $rh175_deducao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh175_sequencial = int4 = Código do Identificador 
                 rh175_db_faixavalores = int4 = Código Identificador 
                 rh175_percentual = float8 = Percentual 
                 rh175_deducao = float8 = Valor da Dedução 
                 ";
   //funcao construtor da classe 
   function cl_faixavaloresirrf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("faixavaloresirrf"); 
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
       $this->rh175_sequencial = ($this->rh175_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh175_sequencial"]:$this->rh175_sequencial);
       $this->rh175_db_faixavalores = ($this->rh175_db_faixavalores == ""?@$GLOBALS["HTTP_POST_VARS"]["rh175_db_faixavalores"]:$this->rh175_db_faixavalores);
       $this->rh175_percentual = ($this->rh175_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["rh175_percentual"]:$this->rh175_percentual);
       $this->rh175_deducao = ($this->rh175_deducao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh175_deducao"]:$this->rh175_deducao);
     }else{
       $this->rh175_sequencial = ($this->rh175_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh175_sequencial"]:$this->rh175_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh175_sequencial){ 
      $this->atualizacampos();
     if($this->rh175_db_faixavalores == null ){ 
       $this->erro_sql = " Campo Código Identificador não informado.";
       $this->erro_campo = "rh175_db_faixavalores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh175_percentual == null ){ 
       $this->erro_sql = " Campo Percentual não informado.";
       $this->erro_campo = "rh175_percentual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh175_deducao == null ){ 
       $this->erro_sql = " Campo Valor da Dedução não informado.";
       $this->erro_campo = "rh175_deducao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh175_sequencial == "" || $rh175_sequencial == null ){
       $result = db_query("select nextval('faixavaloresirrf_rh175_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: faixavaloresirrf_rh175_sequencial_seq do campo: rh175_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh175_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from faixavaloresirrf_rh175_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh175_sequencial)){
         $this->erro_sql = " Campo rh175_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh175_sequencial = $rh175_sequencial; 
       }
     }
     if(($this->rh175_sequencial == null) || ($this->rh175_sequencial == "") ){ 
       $this->erro_sql = " Campo rh175_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into faixavaloresirrf(
                                       rh175_sequencial 
                                      ,rh175_db_faixavalores 
                                      ,rh175_percentual 
                                      ,rh175_deducao 
                       )
                values (
                                $this->rh175_sequencial 
                               ,$this->rh175_db_faixavalores 
                               ,$this->rh175_percentual 
                               ,$this->rh175_deducao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faixa de Valores do IRRF ($this->rh175_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faixa de Valores do IRRF já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faixa de Valores do IRRF ($this->rh175_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh175_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh175_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21677,'$this->rh175_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3895,21677,'','".AddSlashes(pg_result($resaco,0,'rh175_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3895,21678,'','".AddSlashes(pg_result($resaco,0,'rh175_db_faixavalores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3895,21679,'','".AddSlashes(pg_result($resaco,0,'rh175_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3895,21680,'','".AddSlashes(pg_result($resaco,0,'rh175_deducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh175_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update faixavaloresirrf set ";
     $virgula = "";
     if(trim($this->rh175_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh175_sequencial"])){ 
       $sql  .= $virgula." rh175_sequencial = $this->rh175_sequencial ";
       $virgula = ",";
       if(trim($this->rh175_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Identificador não informado.";
         $this->erro_campo = "rh175_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh175_db_faixavalores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh175_db_faixavalores"])){ 
       $sql  .= $virgula." rh175_db_faixavalores = $this->rh175_db_faixavalores ";
       $virgula = ",";
       if(trim($this->rh175_db_faixavalores) == null ){ 
         $this->erro_sql = " Campo Código Identificador não informado.";
         $this->erro_campo = "rh175_db_faixavalores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh175_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh175_percentual"])){ 
       $sql  .= $virgula." rh175_percentual = $this->rh175_percentual ";
       $virgula = ",";
       if(trim($this->rh175_percentual) == null ){ 
         $this->erro_sql = " Campo Percentual não informado.";
         $this->erro_campo = "rh175_percentual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh175_deducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh175_deducao"])){ 
       $sql  .= $virgula." rh175_deducao = $this->rh175_deducao ";
       $virgula = ",";
       if(trim($this->rh175_deducao) == null ){ 
         $this->erro_sql = " Campo Valor da Dedução não informado.";
         $this->erro_campo = "rh175_deducao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh175_sequencial!=null){
       $sql .= " rh175_sequencial = $this->rh175_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh175_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21677,'$this->rh175_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh175_sequencial"]) || $this->rh175_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3895,21677,'".AddSlashes(pg_result($resaco,$conresaco,'rh175_sequencial'))."','$this->rh175_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh175_db_faixavalores"]) || $this->rh175_db_faixavalores != "")
             $resac = db_query("insert into db_acount values($acount,3895,21678,'".AddSlashes(pg_result($resaco,$conresaco,'rh175_db_faixavalores'))."','$this->rh175_db_faixavalores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh175_percentual"]) || $this->rh175_percentual != "")
             $resac = db_query("insert into db_acount values($acount,3895,21679,'".AddSlashes(pg_result($resaco,$conresaco,'rh175_percentual'))."','$this->rh175_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh175_deducao"]) || $this->rh175_deducao != "")
             $resac = db_query("insert into db_acount values($acount,3895,21680,'".AddSlashes(pg_result($resaco,$conresaco,'rh175_deducao'))."','$this->rh175_deducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores do IRRF não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh175_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores do IRRF não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh175_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh175_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21677,'$rh175_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3895,21677,'','".AddSlashes(pg_result($resaco,$iresaco,'rh175_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3895,21678,'','".AddSlashes(pg_result($resaco,$iresaco,'rh175_db_faixavalores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3895,21679,'','".AddSlashes(pg_result($resaco,$iresaco,'rh175_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3895,21680,'','".AddSlashes(pg_result($resaco,$iresaco,'rh175_deducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from faixavaloresirrf
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh175_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh175_sequencial = $rh175_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores do IRRF não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh175_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores do IRRF não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh175_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh175_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:faixavaloresirrf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh175_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from faixavaloresirrf ";
     $sql .= "      inner join db_faixavalores  on  db_faixavalores.db150_sequencial = faixavaloresirrf.rh175_db_faixavalores";
     $sql .= "      inner join db_tabelavalores  on  db_tabelavalores.db149_sequencial = db_faixavalores.db150_db_tabelavalores";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh175_sequencial)) {
         $sql2 .= " where faixavaloresirrf.rh175_sequencial = $rh175_sequencial "; 
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
   public function sql_query_file ($rh175_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from faixavaloresirrf ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh175_sequencial)){
         $sql2 .= " where faixavaloresirrf.rh175_sequencial = $rh175_sequencial "; 
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
