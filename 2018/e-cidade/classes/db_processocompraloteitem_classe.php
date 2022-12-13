<?
//MODULO: compras
//CLASSE DA ENTIDADE processocompraloteitem
class cl_processocompraloteitem { 
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
   var $pc69_sequencial = 0; 
   var $pc69_processocompralote = 0; 
   var $pc69_pcprocitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc69_sequencial = int4 = Sequencial 
                 pc69_processocompralote = int4 = Lote do Processo de Compra 
                 pc69_pcprocitem = int8 = Item do Processo de Compra 
                 ";
   //funcao construtor da classe 
   function cl_processocompraloteitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processocompraloteitem"); 
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
       $this->pc69_sequencial = ($this->pc69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc69_sequencial"]:$this->pc69_sequencial);
       $this->pc69_processocompralote = ($this->pc69_processocompralote == ""?@$GLOBALS["HTTP_POST_VARS"]["pc69_processocompralote"]:$this->pc69_processocompralote);
       $this->pc69_pcprocitem = ($this->pc69_pcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc69_pcprocitem"]:$this->pc69_pcprocitem);
     }else{
       $this->pc69_sequencial = ($this->pc69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc69_sequencial"]:$this->pc69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc69_sequencial){ 
      $this->atualizacampos();
     if($this->pc69_processocompralote == null ){ 
       $this->erro_sql = " Campo Lote do Processo de Compra não informado.";
       $this->erro_campo = "pc69_processocompralote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc69_pcprocitem == null ){ 
       $this->erro_sql = " Campo Item do Processo de Compra não informado.";
       $this->erro_campo = "pc69_pcprocitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc69_sequencial == "" || $pc69_sequencial == null ){
       $result = db_query("select nextval('processocompraloteitem_pc69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processocompraloteitem_pc69_sequencial_seq do campo: pc69_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processocompraloteitem_pc69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc69_sequencial)){
         $this->erro_sql = " Campo pc69_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc69_sequencial = $pc69_sequencial; 
       }
     }
     if(($this->pc69_sequencial == null) || ($this->pc69_sequencial == "") ){ 
       $this->erro_sql = " Campo pc69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processocompraloteitem(
                                       pc69_sequencial 
                                      ,pc69_processocompralote 
                                      ,pc69_pcprocitem 
                       )
                values (
                                $this->pc69_sequencial 
                               ,$this->pc69_processocompralote 
                               ,$this->pc69_pcprocitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote e Item ($this->pc69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote e Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote e Item ($this->pc69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc69_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc69_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20768,'$this->pc69_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3738,20768,'','".AddSlashes(pg_result($resaco,0,'pc69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3738,20771,'','".AddSlashes(pg_result($resaco,0,'pc69_processocompralote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3738,20772,'','".AddSlashes(pg_result($resaco,0,'pc69_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($pc69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processocompraloteitem set ";
     $virgula = "";
     if(trim($this->pc69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc69_sequencial"])){ 
       $sql  .= $virgula." pc69_sequencial = $this->pc69_sequencial ";
       $virgula = ",";
       if(trim($this->pc69_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "pc69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc69_processocompralote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc69_processocompralote"])){ 
       $sql  .= $virgula." pc69_processocompralote = $this->pc69_processocompralote ";
       $virgula = ",";
       if(trim($this->pc69_processocompralote) == null ){ 
         $this->erro_sql = " Campo Lote do Processo de Compra não informado.";
         $this->erro_campo = "pc69_processocompralote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc69_pcprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc69_pcprocitem"])){ 
       $sql  .= $virgula." pc69_pcprocitem = $this->pc69_pcprocitem ";
       $virgula = ",";
       if(trim($this->pc69_pcprocitem) == null ){ 
         $this->erro_sql = " Campo Item do Processo de Compra não informado.";
         $this->erro_campo = "pc69_pcprocitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc69_sequencial!=null){
       $sql .= " pc69_sequencial = $this->pc69_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc69_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20768,'$this->pc69_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc69_sequencial"]) || $this->pc69_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3738,20768,'".AddSlashes(pg_result($resaco,$conresaco,'pc69_sequencial'))."','$this->pc69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc69_processocompralote"]) || $this->pc69_processocompralote != "")
             $resac = db_query("insert into db_acount values($acount,3738,20771,'".AddSlashes(pg_result($resaco,$conresaco,'pc69_processocompralote'))."','$this->pc69_processocompralote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc69_pcprocitem"]) || $this->pc69_pcprocitem != "")
             $resac = db_query("insert into db_acount values($acount,3738,20772,'".AddSlashes(pg_result($resaco,$conresaco,'pc69_pcprocitem'))."','$this->pc69_pcprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote e Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lote e Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($pc69_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc69_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20768,'$pc69_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3738,20768,'','".AddSlashes(pg_result($resaco,$iresaco,'pc69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3738,20771,'','".AddSlashes(pg_result($resaco,$iresaco,'pc69_processocompralote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3738,20772,'','".AddSlashes(pg_result($resaco,$iresaco,'pc69_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from processocompraloteitem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc69_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc69_sequencial = $pc69_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote e Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lote e Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc69_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processocompraloteitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($pc69_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from processocompraloteitem ";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = processocompraloteitem.pc69_pcprocitem";
     $sql .= "      inner join processocompralote  on  processocompralote.pc68_sequencial = processocompraloteitem.pc69_processocompralote";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = processocompralote.pc68_pcproc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc69_sequencial)) {
         $sql2 .= " where processocompraloteitem.pc69_sequencial = $pc69_sequencial "; 
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
   public function sql_query_file ($pc69_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from processocompraloteitem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc69_sequencial)){
         $sql2 .= " where processocompraloteitem.pc69_sequencial = $pc69_sequencial "; 
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
