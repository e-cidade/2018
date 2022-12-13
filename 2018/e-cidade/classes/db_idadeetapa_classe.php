<?
//MODULO: matriculaonline
//CLASSE DA ENTIDADE idadeetapa
class cl_idadeetapa { 
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
   var $mo15_sequencial = 0; 
   var $mo15_etapa = 0; 
   var $mo15_idadeinicial = null; 
   var $mo15_idadefinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 mo15_sequencial = int4 = Código Sequencial 
                 mo15_etapa = int4 = Etapa 
                 mo15_idadeinicial = varchar(100) = Idade Inicial 
                 mo15_idadefinal = varchar(100) = Idade Final 
                 ";
   //funcao construtor da classe 
   function cl_idadeetapa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("idadeetapa"); 
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
       $this->mo15_sequencial = ($this->mo15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo15_sequencial"]:$this->mo15_sequencial);
       $this->mo15_etapa = ($this->mo15_etapa == ""?@$GLOBALS["HTTP_POST_VARS"]["mo15_etapa"]:$this->mo15_etapa);
       $this->mo15_idadeinicial = ($this->mo15_idadeinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo15_idadeinicial"]:$this->mo15_idadeinicial);
       $this->mo15_idadefinal = ($this->mo15_idadefinal == ""?@$GLOBALS["HTTP_POST_VARS"]["mo15_idadefinal"]:$this->mo15_idadefinal);
     }else{
       $this->mo15_sequencial = ($this->mo15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["mo15_sequencial"]:$this->mo15_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($mo15_sequencial){ 
      $this->atualizacampos();
     if($this->mo15_etapa == null ){ 
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "mo15_etapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($mo15_sequencial == "" || $mo15_sequencial == null ){
       $result = db_query("select nextval('idadeetapa_mo15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: idadeetapa_mo15_sequencial_seq do campo: mo15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->mo15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from idadeetapa_mo15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $mo15_sequencial)){
         $this->erro_sql = " Campo mo15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->mo15_sequencial = $mo15_sequencial; 
       }
     }
     if(($this->mo15_sequencial == null) || ($this->mo15_sequencial == "") ){ 
       $this->erro_sql = " Campo mo15_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into idadeetapa(
                                       mo15_sequencial 
                                      ,mo15_etapa 
                                      ,mo15_idadeinicial 
                                      ,mo15_idadefinal 
                       )
                values (
                                $this->mo15_sequencial 
                               ,$this->mo15_etapa 
                               ,'$this->mo15_idadeinicial' 
                               ,'$this->mo15_idadefinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Idade de Etapas ($this->mo15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Idade de Etapas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Idade de Etapas ($this->mo15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo15_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21428,'$this->mo15_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3851,21428,'','".AddSlashes(pg_result($resaco,0,'mo15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3851,21429,'','".AddSlashes(pg_result($resaco,0,'mo15_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3851,21430,'','".AddSlashes(pg_result($resaco,0,'mo15_idadeinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3851,21431,'','".AddSlashes(pg_result($resaco,0,'mo15_idadefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($mo15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update idadeetapa set ";
     $virgula = "";
     if(trim($this->mo15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo15_sequencial"])){ 
       $sql  .= $virgula." mo15_sequencial = $this->mo15_sequencial ";
       $virgula = ",";
       if(trim($this->mo15_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "mo15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo15_etapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo15_etapa"])){ 
       $sql  .= $virgula." mo15_etapa = $this->mo15_etapa ";
       $virgula = ",";
       if(trim($this->mo15_etapa) == null ){ 
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "mo15_etapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo15_idadeinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo15_idadeinicial"])){ 
       $sql  .= $virgula." mo15_idadeinicial = '$this->mo15_idadeinicial' ";
       $virgula = ",";
     }
     if(trim($this->mo15_idadefinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo15_idadefinal"])){ 
       $sql  .= $virgula." mo15_idadefinal = '$this->mo15_idadefinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($mo15_sequencial!=null){
       $sql .= " mo15_sequencial = $this->mo15_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo15_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21428,'$this->mo15_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo15_sequencial"]) || $this->mo15_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3851,21428,'".AddSlashes(pg_result($resaco,$conresaco,'mo15_sequencial'))."','$this->mo15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo15_etapa"]) || $this->mo15_etapa != "")
             $resac = db_query("insert into db_acount values($acount,3851,21429,'".AddSlashes(pg_result($resaco,$conresaco,'mo15_etapa'))."','$this->mo15_etapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo15_idadeinicial"]) || $this->mo15_idadeinicial != "")
             $resac = db_query("insert into db_acount values($acount,3851,21430,'".AddSlashes(pg_result($resaco,$conresaco,'mo15_idadeinicial'))."','$this->mo15_idadeinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo15_idadefinal"]) || $this->mo15_idadefinal != "")
             $resac = db_query("insert into db_acount values($acount,3851,21431,'".AddSlashes(pg_result($resaco,$conresaco,'mo15_idadefinal'))."','$this->mo15_idadefinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Idade de Etapas não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Idade de Etapas não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($mo15_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($mo15_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21428,'$mo15_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3851,21428,'','".AddSlashes(pg_result($resaco,$iresaco,'mo15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3851,21429,'','".AddSlashes(pg_result($resaco,$iresaco,'mo15_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3851,21430,'','".AddSlashes(pg_result($resaco,$iresaco,'mo15_idadeinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3851,21431,'','".AddSlashes(pg_result($resaco,$iresaco,'mo15_idadefinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from idadeetapa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($mo15_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " mo15_sequencial = $mo15_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Idade de Etapas não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$mo15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Idade de Etapas não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$mo15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$mo15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:idadeetapa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($mo15_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from idadeetapa ";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = idadeetapa.mo15_etapa";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo15_sequencial)) {
         $sql2 .= " where idadeetapa.mo15_sequencial = $mo15_sequencial "; 
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
   public function sql_query_file ($mo15_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from idadeetapa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo15_sequencial)){
         $sql2 .= " where idadeetapa.mo15_sequencial = $mo15_sequencial "; 
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
