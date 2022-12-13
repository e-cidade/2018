<?
//MODULO: configuracoes
//CLASSE DA ENTIDADE bancoagenciaendereco
class cl_bancoagenciaendereco { 
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
   var $db92_sequencial = 0; 
   var $db92_bancoagencia = 0; 
   var $db92_endereco = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db92_sequencial = int4 = Código Sequencial 
                 db92_bancoagencia = int4 = Agência 
                 db92_endereco = int4 = Endereço da Agencia 
                 ";
   //funcao construtor da classe 
   function cl_bancoagenciaendereco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bancoagenciaendereco"); 
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
       $this->db92_sequencial = ($this->db92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db92_sequencial"]:$this->db92_sequencial);
       $this->db92_bancoagencia = ($this->db92_bancoagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["db92_bancoagencia"]:$this->db92_bancoagencia);
       $this->db92_endereco = ($this->db92_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["db92_endereco"]:$this->db92_endereco);
     }else{
       $this->db92_sequencial = ($this->db92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db92_sequencial"]:$this->db92_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db92_sequencial){ 
      $this->atualizacampos();
     if($this->db92_bancoagencia == null ){ 
       $this->erro_sql = " Campo Agência não informado.";
       $this->erro_campo = "db92_bancoagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db92_endereco == null ){ 
       $this->erro_sql = " Campo Endereço da Agencia não informado.";
       $this->erro_campo = "db92_endereco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db92_sequencial == "" || $db92_sequencial == null ){
       $result = db_query("select nextval('bancoagenciaendereco_db92_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bancoagenciaendereco_db92_sequencial_seq do campo: db92_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db92_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bancoagenciaendereco_db92_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db92_sequencial)){
         $this->erro_sql = " Campo db92_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db92_sequencial = $db92_sequencial; 
       }
     }
     if(($this->db92_sequencial == null) || ($this->db92_sequencial == "") ){ 
       $this->erro_sql = " Campo db92_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bancoagenciaendereco(
                                       db92_sequencial 
                                      ,db92_bancoagencia 
                                      ,db92_endereco 
                       )
                values (
                                $this->db92_sequencial 
                               ,$this->db92_bancoagencia 
                               ,$this->db92_endereco 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Endereço da Agencia ($this->db92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Endereço da Agencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Endereço da Agencia ($this->db92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db92_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db92_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20686,'$this->db92_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3723,20686,'','".AddSlashes(pg_result($resaco,0,'db92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3723,20687,'','".AddSlashes(pg_result($resaco,0,'db92_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3723,20688,'','".AddSlashes(pg_result($resaco,0,'db92_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db92_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bancoagenciaendereco set ";
     $virgula = "";
     if(trim($this->db92_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db92_sequencial"])){ 
       $sql  .= $virgula." db92_sequencial = $this->db92_sequencial ";
       $virgula = ",";
       if(trim($this->db92_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "db92_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db92_bancoagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db92_bancoagencia"])){ 
       $sql  .= $virgula." db92_bancoagencia = $this->db92_bancoagencia ";
       $virgula = ",";
       if(trim($this->db92_bancoagencia) == null ){ 
         $this->erro_sql = " Campo Agência não informado.";
         $this->erro_campo = "db92_bancoagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db92_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db92_endereco"])){ 
       $sql  .= $virgula." db92_endereco = $this->db92_endereco ";
       $virgula = ",";
       if(trim($this->db92_endereco) == null ){ 
         $this->erro_sql = " Campo Endereço da Agencia não informado.";
         $this->erro_campo = "db92_endereco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db92_sequencial!=null){
       $sql .= " db92_sequencial = $this->db92_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db92_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20686,'$this->db92_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db92_sequencial"]) || $this->db92_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3723,20686,'".AddSlashes(pg_result($resaco,$conresaco,'db92_sequencial'))."','$this->db92_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db92_bancoagencia"]) || $this->db92_bancoagencia != "")
             $resac = db_query("insert into db_acount values($acount,3723,20687,'".AddSlashes(pg_result($resaco,$conresaco,'db92_bancoagencia'))."','$this->db92_bancoagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["db92_endereco"]) || $this->db92_endereco != "")
             $resac = db_query("insert into db_acount values($acount,3723,20688,'".AddSlashes(pg_result($resaco,$conresaco,'db92_endereco'))."','$this->db92_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço da Agencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereço da Agencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db92_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($db92_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20686,'$db92_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3723,20686,'','".AddSlashes(pg_result($resaco,$iresaco,'db92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3723,20687,'','".AddSlashes(pg_result($resaco,$iresaco,'db92_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3723,20688,'','".AddSlashes(pg_result($resaco,$iresaco,'db92_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bancoagenciaendereco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db92_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db92_sequencial = $db92_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço da Agencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereço da Agencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:bancoagenciaendereco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from bancoagenciaendereco ";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = bancoagenciaendereco.db92_bancoagencia";
     $sql .= "      inner join endereco  on  endereco.db76_sequencial = bancoagenciaendereco.db92_endereco";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
     $sql .= "      inner join cadenderlocal  on  cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($db92_sequencial!=null ){
         $sql2 .= " where bancoagenciaendereco.db92_sequencial = $db92_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $db92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from bancoagenciaendereco ";
     $sql2 = "";
     if($dbwhere==""){
       if($db92_sequencial!=null ){
         $sql2 .= " where bancoagenciaendereco.db92_sequencial = $db92_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>
