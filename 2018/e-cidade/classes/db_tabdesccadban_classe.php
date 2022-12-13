<?
//MODULO: arrecadacao
//CLASSE DA ENTIDADE tabdesccadban
class cl_tabdesccadban { 
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
   var $k114_sequencial = 0; 
   var $k114_tabdesc = 0; 
   var $k114_codban = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k114_sequencial = int8 = Código sequencial 
                 k114_tabdesc = int8 = Código de Desconto 
                 k114_codban = int8 = Código da conta que será vinculada 
                 ";
   //funcao construtor da classe 
   function cl_tabdesccadban() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabdesccadban"); 
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
       $this->k114_sequencial = ($this->k114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k114_sequencial"]:$this->k114_sequencial);
       $this->k114_tabdesc = ($this->k114_tabdesc == ""?@$GLOBALS["HTTP_POST_VARS"]["k114_tabdesc"]:$this->k114_tabdesc);
       $this->k114_codban = ($this->k114_codban == ""?@$GLOBALS["HTTP_POST_VARS"]["k114_codban"]:$this->k114_codban);
     }else{
       $this->k114_sequencial = ($this->k114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k114_sequencial"]:$this->k114_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k114_sequencial){ 
      $this->atualizacampos();
     if($this->k114_tabdesc == null ){ 
       $this->erro_sql = " Campo Código de Desconto não informado.";
       $this->erro_campo = "k114_tabdesc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k114_codban == null ){ 
       $this->erro_sql = " Campo Código da conta que será vinculada não informado.";
       $this->erro_campo = "k114_codban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k114_sequencial == "" || $k114_sequencial == null ){
       $result = db_query("select nextval('tabdesccadban_k114_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabdesccadban_k114_sequencial_seq do campo: k114_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k114_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabdesccadban_k114_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k114_sequencial)){
         $this->erro_sql = " Campo k114_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k114_sequencial = $k114_sequencial; 
       }
     }
     if(($this->k114_sequencial == null) || ($this->k114_sequencial == "") ){ 
       $this->erro_sql = " Campo k114_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabdesccadban(
                                       k114_sequencial 
                                      ,k114_tabdesc 
                                      ,k114_codban 
                       )
                values (
                                $this->k114_sequencial 
                               ,$this->k114_tabdesc 
                               ,$this->k114_codban 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Desconto Conta Bancária ($this->k114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Desconto Conta Bancária já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Desconto Conta Bancária ($this->k114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k114_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k114_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20630,'$this->k114_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3716,20630,'','".AddSlashes(pg_result($resaco,0,'k114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3716,20631,'','".AddSlashes(pg_result($resaco,0,'k114_tabdesc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3716,20632,'','".AddSlashes(pg_result($resaco,0,'k114_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k114_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabdesccadban set ";
     $virgula = "";
     if(trim($this->k114_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k114_sequencial"])){ 
       $sql  .= $virgula." k114_sequencial = $this->k114_sequencial ";
       $virgula = ",";
       if(trim($this->k114_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial não informado.";
         $this->erro_campo = "k114_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k114_tabdesc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k114_tabdesc"])){ 
       $sql  .= $virgula." k114_tabdesc = $this->k114_tabdesc ";
       $virgula = ",";
       if(trim($this->k114_tabdesc) == null ){ 
         $this->erro_sql = " Campo Código de Desconto não informado.";
         $this->erro_campo = "k114_tabdesc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k114_codban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k114_codban"])){ 
       $sql  .= $virgula." k114_codban = $this->k114_codban ";
       $virgula = ",";
       if(trim($this->k114_codban) == null ){ 
         $this->erro_sql = " Campo Código da conta que será vinculada não informado.";
         $this->erro_campo = "k114_codban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k114_sequencial!=null){
       $sql .= " k114_sequencial = $this->k114_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k114_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20630,'$this->k114_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k114_sequencial"]) || $this->k114_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3716,20630,'".AddSlashes(pg_result($resaco,$conresaco,'k114_sequencial'))."','$this->k114_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k114_tabdesc"]) || $this->k114_tabdesc != "")
             $resac = db_query("insert into db_acount values($acount,3716,20631,'".AddSlashes(pg_result($resaco,$conresaco,'k114_tabdesc'))."','$this->k114_tabdesc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k114_codban"]) || $this->k114_codban != "")
             $resac = db_query("insert into db_acount values($acount,3716,20632,'".AddSlashes(pg_result($resaco,$conresaco,'k114_codban'))."','$this->k114_codban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Desconto Conta Bancária nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Desconto Conta Bancária nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k114_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($k114_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20630,'$k114_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3716,20630,'','".AddSlashes(pg_result($resaco,$iresaco,'k114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3716,20631,'','".AddSlashes(pg_result($resaco,$iresaco,'k114_tabdesc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3716,20632,'','".AddSlashes(pg_result($resaco,$iresaco,'k114_codban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tabdesccadban
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k114_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k114_sequencial = $k114_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Desconto Conta Bancária nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Desconto Conta Bancária nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k114_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabdesccadban";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabdesccadban ";
     $sql .= "      inner join tabdesc  on  tabdesc.codsubrec = tabdesccadban.k114_tabdesc";
     $sql .= "      inner join cadban  on  cadban.k15_codigo = tabdesccadban.k114_codban";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = tabdesc.k07_codigo";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabdesc.k07_codinf";
     $sql .= "      inner join db_config  on  db_config.codigo = tabdesc.k07_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cadban.k15_numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = cadban.k15_instit";
     $sql .= "      inner join bancos  on  bancos.codbco = cadban.k15_codbco";
     $sql2 = "";
     if($dbwhere==""){
       if($k114_sequencial!=null ){
         $sql2 .= " where tabdesccadban.k114_sequencial = $k114_sequencial "; 
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
   function sql_query_file ( $k114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabdesccadban ";
     $sql2 = "";
     if($dbwhere==""){
       if($k114_sequencial!=null ){
         $sql2 .= " where tabdesccadban.k114_sequencial = $k114_sequencial "; 
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
