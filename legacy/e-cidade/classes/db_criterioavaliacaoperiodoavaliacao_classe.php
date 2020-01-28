<?
//MODULO: escola
//CLASSE DA ENTIDADE criterioavaliacaoperiodoavaliacao
class cl_criterioavaliacaoperiodoavaliacao { 
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
   var $ed340_sequencial = 0; 
   var $ed340_criterioavaliacao = 0; 
   var $ed340_periodoavaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed340_sequencial = int4 = Código 
                 ed340_criterioavaliacao = int4 = Critéro de Avaliação 
                 ed340_periodoavaliacao = int4 = Período de avaliação 
                 ";
   //funcao construtor da classe 
   function cl_criterioavaliacaoperiodoavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("criterioavaliacaoperiodoavaliacao"); 
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
       $this->ed340_sequencial = ($this->ed340_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed340_sequencial"]:$this->ed340_sequencial);
       $this->ed340_criterioavaliacao = ($this->ed340_criterioavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed340_criterioavaliacao"]:$this->ed340_criterioavaliacao);
       $this->ed340_periodoavaliacao = ($this->ed340_periodoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed340_periodoavaliacao"]:$this->ed340_periodoavaliacao);
     }else{
       $this->ed340_sequencial = ($this->ed340_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed340_sequencial"]:$this->ed340_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed340_sequencial){ 
      $this->atualizacampos();
     if($this->ed340_criterioavaliacao == null ){ 
       $this->erro_sql = " Campo Critéro de Avaliação não informado.";
       $this->erro_campo = "ed340_criterioavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed340_periodoavaliacao == null ){ 
       $this->erro_sql = " Campo Período de avaliação não informado.";
       $this->erro_campo = "ed340_periodoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed340_sequencial == "" || $ed340_sequencial == null ){
       $result = db_query("select nextval('criterioavaliacaoperiodoavaliacao_ed340_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: criterioavaliacaoperiodoavaliacao_ed340_sequencial_seq do campo: ed340_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed340_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from criterioavaliacaoperiodoavaliacao_ed340_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed340_sequencial)){
         $this->erro_sql = " Campo ed340_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed340_sequencial = $ed340_sequencial; 
       }
     }
     if(($this->ed340_sequencial == null) || ($this->ed340_sequencial == "") ){ 
       $this->erro_sql = " Campo ed340_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into criterioavaliacaoperiodoavaliacao(
                                       ed340_sequencial 
                                      ,ed340_criterioavaliacao 
                                      ,ed340_periodoavaliacao 
                       )
                values (
                                $this->ed340_sequencial 
                               ,$this->ed340_criterioavaliacao 
                               ,$this->ed340_periodoavaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Período de avaliação do critério de avaliação ($this->ed340_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Período de avaliação do critério de avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Período de avaliação do critério de avaliação ($this->ed340_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed340_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed340_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20512,'$this->ed340_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3690,20512,'','".AddSlashes(pg_result($resaco,0,'ed340_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3690,20513,'','".AddSlashes(pg_result($resaco,0,'ed340_criterioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3690,20514,'','".AddSlashes(pg_result($resaco,0,'ed340_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed340_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update criterioavaliacaoperiodoavaliacao set ";
     $virgula = "";
     if(trim($this->ed340_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed340_sequencial"])){ 
       $sql  .= $virgula." ed340_sequencial = $this->ed340_sequencial ";
       $virgula = ",";
       if(trim($this->ed340_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed340_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed340_criterioavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed340_criterioavaliacao"])){ 
       $sql  .= $virgula." ed340_criterioavaliacao = $this->ed340_criterioavaliacao ";
       $virgula = ",";
       if(trim($this->ed340_criterioavaliacao) == null ){ 
         $this->erro_sql = " Campo Critéro de Avaliação não informado.";
         $this->erro_campo = "ed340_criterioavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed340_periodoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed340_periodoavaliacao"])){ 
       $sql  .= $virgula." ed340_periodoavaliacao = $this->ed340_periodoavaliacao ";
       $virgula = ",";
       if(trim($this->ed340_periodoavaliacao) == null ){ 
         $this->erro_sql = " Campo Período de avaliação não informado.";
         $this->erro_campo = "ed340_periodoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed340_sequencial!=null){
       $sql .= " ed340_sequencial = $this->ed340_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed340_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20512,'$this->ed340_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed340_sequencial"]) || $this->ed340_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3690,20512,'".AddSlashes(pg_result($resaco,$conresaco,'ed340_sequencial'))."','$this->ed340_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed340_criterioavaliacao"]) || $this->ed340_criterioavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,3690,20513,'".AddSlashes(pg_result($resaco,$conresaco,'ed340_criterioavaliacao'))."','$this->ed340_criterioavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed340_periodoavaliacao"]) || $this->ed340_periodoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,3690,20514,'".AddSlashes(pg_result($resaco,$conresaco,'ed340_periodoavaliacao'))."','$this->ed340_periodoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Período de avaliação do critério de avaliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed340_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Período de avaliação do critério de avaliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed340_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed340_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed340_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed340_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20512,'$ed340_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3690,20512,'','".AddSlashes(pg_result($resaco,$iresaco,'ed340_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3690,20513,'','".AddSlashes(pg_result($resaco,$iresaco,'ed340_criterioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3690,20514,'','".AddSlashes(pg_result($resaco,$iresaco,'ed340_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from criterioavaliacaoperiodoavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed340_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed340_sequencial = $ed340_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Período de avaliação do critério de avaliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed340_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Período de avaliação do critério de avaliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed340_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed340_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:criterioavaliacaoperiodoavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed340_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from criterioavaliacaoperiodoavaliacao ";
     $sql .= "      inner join criterioavaliacao  on  criterioavaliacao.ed338_sequencial = criterioavaliacaoperiodoavaliacao.ed340_criterioavaliacao";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = criterioavaliacaoperiodoavaliacao.ed340_periodoavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ed340_sequencial!=null ){
         $sql2 .= " where criterioavaliacaoperiodoavaliacao.ed340_sequencial = $ed340_sequencial "; 
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
   function sql_query_file ( $ed340_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from criterioavaliacaoperiodoavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed340_sequencial!=null ){
         $sql2 .= " where criterioavaliacaoperiodoavaliacao.ed340_sequencial = $ed340_sequencial "; 
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
