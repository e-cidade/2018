<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoparalisacaoacordomovimentacao
class cl_acordoparalisacaoacordomovimentacao { 
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
   var $ac48_sequencial = 0; 
   var $ac48_acordoparalisacao = 0; 
   var $ac48_acordomovimentacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac48_sequencial = int4 = Sequencial Periodo da Paralisacao 
                 ac48_acordoparalisacao = int4 = Sequencial da Paralisação 
                 ac48_acordomovimentacao = int4 = Sequencial 
                 ";
   //funcao construtor da classe 
   function cl_acordoparalisacaoacordomovimentacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoparalisacaoacordomovimentacao"); 
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
       $this->ac48_sequencial = ($this->ac48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac48_sequencial"]:$this->ac48_sequencial);
       $this->ac48_acordoparalisacao = ($this->ac48_acordoparalisacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac48_acordoparalisacao"]:$this->ac48_acordoparalisacao);
       $this->ac48_acordomovimentacao = ($this->ac48_acordomovimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac48_acordomovimentacao"]:$this->ac48_acordomovimentacao);
     }else{
       $this->ac48_sequencial = ($this->ac48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac48_sequencial"]:$this->ac48_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac48_sequencial){ 
      $this->atualizacampos();
     if($this->ac48_acordoparalisacao == null ){ 
       $this->erro_sql = " Campo Sequencial da Paralisação não informado.";
       $this->erro_campo = "ac48_acordoparalisacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac48_acordomovimentacao == null ){ 
       $this->erro_sql = " Campo Sequencial não informado.";
       $this->erro_campo = "ac48_acordomovimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac48_sequencial == "" || $ac48_sequencial == null ){
       $result = db_query("select nextval('acordoparalisacaoacordomovimentacao_ac48_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoparalisacaoacordomovimentacao_ac48_sequencial_seq do campo: ac48_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac48_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoparalisacaoacordomovimentacao_ac48_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac48_sequencial)){
         $this->erro_sql = " Campo ac48_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac48_sequencial = $ac48_sequencial; 
       }
     }
     if(($this->ac48_sequencial == null) || ($this->ac48_sequencial == "") ){ 
       $this->erro_sql = " Campo ac48_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoparalisacaoacordomovimentacao(
                                       ac48_sequencial 
                                      ,ac48_acordoparalisacao 
                                      ,ac48_acordomovimentacao 
                       )
                values (
                                $this->ac48_sequencial 
                               ,$this->ac48_acordoparalisacao 
                               ,$this->ac48_acordomovimentacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimento da Paralisacao ($this->ac48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimento da Paralisacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimento da Paralisacao ($this->ac48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac48_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac48_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20522,'$this->ac48_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3693,20522,'','".AddSlashes(pg_result($resaco,0,'ac48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3693,20523,'','".AddSlashes(pg_result($resaco,0,'ac48_acordoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3693,20524,'','".AddSlashes(pg_result($resaco,0,'ac48_acordomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac48_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoparalisacaoacordomovimentacao set ";
     $virgula = "";
     if(trim($this->ac48_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac48_sequencial"])){ 
       $sql  .= $virgula." ac48_sequencial = $this->ac48_sequencial ";
       $virgula = ",";
       if(trim($this->ac48_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial Periodo da Paralisacao não informado.";
         $this->erro_campo = "ac48_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac48_acordoparalisacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac48_acordoparalisacao"])){ 
       $sql  .= $virgula." ac48_acordoparalisacao = $this->ac48_acordoparalisacao ";
       $virgula = ",";
       if(trim($this->ac48_acordoparalisacao) == null ){ 
         $this->erro_sql = " Campo Sequencial da Paralisação não informado.";
         $this->erro_campo = "ac48_acordoparalisacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac48_acordomovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac48_acordomovimentacao"])){ 
       $sql  .= $virgula." ac48_acordomovimentacao = $this->ac48_acordomovimentacao ";
       $virgula = ",";
       if(trim($this->ac48_acordomovimentacao) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ac48_acordomovimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac48_sequencial!=null){
       $sql .= " ac48_sequencial = $this->ac48_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac48_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20522,'$this->ac48_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac48_sequencial"]) || $this->ac48_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3693,20522,'".AddSlashes(pg_result($resaco,$conresaco,'ac48_sequencial'))."','$this->ac48_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac48_acordoparalisacao"]) || $this->ac48_acordoparalisacao != "")
             $resac = db_query("insert into db_acount values($acount,3693,20523,'".AddSlashes(pg_result($resaco,$conresaco,'ac48_acordoparalisacao'))."','$this->ac48_acordoparalisacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac48_acordomovimentacao"]) || $this->ac48_acordomovimentacao != "")
             $resac = db_query("insert into db_acount values($acount,3693,20524,'".AddSlashes(pg_result($resaco,$conresaco,'ac48_acordomovimentacao'))."','$this->ac48_acordomovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da Paralisacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da Paralisacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac48_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ac48_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20522,'$ac48_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3693,20522,'','".AddSlashes(pg_result($resaco,$iresaco,'ac48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3693,20523,'','".AddSlashes(pg_result($resaco,$iresaco,'ac48_acordoparalisacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3693,20524,'','".AddSlashes(pg_result($resaco,$iresaco,'ac48_acordomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoparalisacaoacordomovimentacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac48_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac48_sequencial = $ac48_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da Paralisacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da Paralisacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac48_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoparalisacaoacordomovimentacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoparalisacaoacordomovimentacao ";
     $sql .= "      inner join acordomovimentacao  on  acordomovimentacao.ac10_sequencial = acordoparalisacaoacordomovimentacao.ac48_acordomovimentacao";
     $sql .= "      inner join acordoparalisacao  on  acordoparalisacao.ac47_sequencial = acordoparalisacaoacordomovimentacao.ac48_acordoparalisacao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = acordomovimentacao.ac10_id_usuario";
     $sql .= "      inner join acordomovimentacaotipo  on  acordomovimentacaotipo.ac09_sequencial = acordomovimentacao.ac10_acordomovimentacaotipo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordomovimentacao.ac10_acordo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoparalisacao.ac47_acordo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac48_sequencial!=null ){
         $sql2 .= " where acordoparalisacaoacordomovimentacao.ac48_sequencial = $ac48_sequencial "; 
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
   function sql_query_file ( $ac48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoparalisacaoacordomovimentacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac48_sequencial!=null ){
         $sql2 .= " where acordoparalisacaoacordomovimentacao.ac48_sequencial = $ac48_sequencial "; 
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

  /**
   * query criada para movimentaçoes das paralisações do acordo
   * @param null   $ac48_sequencial
   * @param string $campos
   * @param null   $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_queryMovimentacao ( $ac48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from acordoparalisacaoacordomovimentacao ";
    $sql .= "      inner join acordomovimentacao  on  acordomovimentacao.ac10_sequencial = acordoparalisacaoacordomovimentacao.ac48_acordomovimentacao";
    $sql .= "      inner join acordoparalisacao  on  acordoparalisacao.ac47_sequencial = acordoparalisacaoacordomovimentacao.ac48_acordoparalisacao";
    $sql2 = "";
    if($dbwhere==""){
      if($ac48_sequencial!=null ){
        $sql2 .= " where acordoparalisacaoacordomovimentacao.ac48_sequencial = $ac48_sequencial ";
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
