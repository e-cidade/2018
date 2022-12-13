<?
//MODULO: escola
//CLASSE DA ENTIDADE criterioavaliacaodisciplina
class cl_criterioavaliacaodisciplina { 
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
   var $ed339_sequencial = 0; 
   var $ed339_criterioavaliacao = 0; 
   var $ed339_disciplina = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed339_sequencial = int4 = Código 
                 ed339_criterioavaliacao = int4 = Critéro de Avaliação 
                 ed339_disciplina = int4 = Disciplina 
                 ";
   //funcao construtor da classe 
   function cl_criterioavaliacaodisciplina() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("criterioavaliacaodisciplina"); 
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
       $this->ed339_sequencial = ($this->ed339_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed339_sequencial"]:$this->ed339_sequencial);
       $this->ed339_criterioavaliacao = ($this->ed339_criterioavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed339_criterioavaliacao"]:$this->ed339_criterioavaliacao);
       $this->ed339_disciplina = ($this->ed339_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed339_disciplina"]:$this->ed339_disciplina);
     }else{
       $this->ed339_sequencial = ($this->ed339_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed339_sequencial"]:$this->ed339_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed339_sequencial){ 
      $this->atualizacampos();
     if($this->ed339_criterioavaliacao == null ){ 
       $this->erro_sql = " Campo Critéro de Avaliação não informado.";
       $this->erro_campo = "ed339_criterioavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed339_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed339_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed339_sequencial == "" || $ed339_sequencial == null ){
       $result = db_query("select nextval('criterioavaliacaodisciplina_ed339_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: criterioavaliacaodisciplina_ed339_sequencial_seq do campo: ed339_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed339_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from criterioavaliacaodisciplina_ed339_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed339_sequencial)){
         $this->erro_sql = " Campo ed339_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed339_sequencial = $ed339_sequencial; 
       }
     }
     if(($this->ed339_sequencial == null) || ($this->ed339_sequencial == "") ){ 
       $this->erro_sql = " Campo ed339_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into criterioavaliacaodisciplina(
                                       ed339_sequencial 
                                      ,ed339_criterioavaliacao 
                                      ,ed339_disciplina 
                       )
                values (
                                $this->ed339_sequencial 
                               ,$this->ed339_criterioavaliacao 
                               ,$this->ed339_disciplina 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "criterioavaliacaodisciplina ($this->ed339_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "criterioavaliacaodisciplina já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "criterioavaliacaodisciplina ($this->ed339_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed339_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed339_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20509,'$this->ed339_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3689,20509,'','".AddSlashes(pg_result($resaco,0,'ed339_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3689,20510,'','".AddSlashes(pg_result($resaco,0,'ed339_criterioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3689,20511,'','".AddSlashes(pg_result($resaco,0,'ed339_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed339_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update criterioavaliacaodisciplina set ";
     $virgula = "";
     if(trim($this->ed339_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed339_sequencial"])){ 
       $sql  .= $virgula." ed339_sequencial = $this->ed339_sequencial ";
       $virgula = ",";
       if(trim($this->ed339_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed339_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed339_criterioavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed339_criterioavaliacao"])){ 
       $sql  .= $virgula." ed339_criterioavaliacao = $this->ed339_criterioavaliacao ";
       $virgula = ",";
       if(trim($this->ed339_criterioavaliacao) == null ){ 
         $this->erro_sql = " Campo Critéro de Avaliação não informado.";
         $this->erro_campo = "ed339_criterioavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed339_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed339_disciplina"])){ 
       $sql  .= $virgula." ed339_disciplina = $this->ed339_disciplina ";
       $virgula = ",";
       if(trim($this->ed339_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed339_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed339_sequencial!=null){
       $sql .= " ed339_sequencial = $this->ed339_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed339_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20509,'$this->ed339_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed339_sequencial"]) || $this->ed339_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3689,20509,'".AddSlashes(pg_result($resaco,$conresaco,'ed339_sequencial'))."','$this->ed339_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed339_criterioavaliacao"]) || $this->ed339_criterioavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,3689,20510,'".AddSlashes(pg_result($resaco,$conresaco,'ed339_criterioavaliacao'))."','$this->ed339_criterioavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed339_disciplina"]) || $this->ed339_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,3689,20511,'".AddSlashes(pg_result($resaco,$conresaco,'ed339_disciplina'))."','$this->ed339_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "criterioavaliacaodisciplina nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed339_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "criterioavaliacaodisciplina nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed339_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed339_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed339_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed339_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20509,'$ed339_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3689,20509,'','".AddSlashes(pg_result($resaco,$iresaco,'ed339_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3689,20510,'','".AddSlashes(pg_result($resaco,$iresaco,'ed339_criterioavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3689,20511,'','".AddSlashes(pg_result($resaco,$iresaco,'ed339_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from criterioavaliacaodisciplina
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed339_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed339_sequencial = $ed339_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "criterioavaliacaodisciplina nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed339_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "criterioavaliacaodisciplina nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed339_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed339_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:criterioavaliacaodisciplina";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed339_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from criterioavaliacaodisciplina ";
     $sql .= "      inner join criterioavaliacao  on  criterioavaliacao.ed338_sequencial = criterioavaliacaodisciplina.ed339_criterioavaliacao";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = criterioavaliacaodisciplina.ed339_disciplina";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed339_sequencial!=null ){
         $sql2 .= " where criterioavaliacaodisciplina.ed339_sequencial = $ed339_sequencial "; 
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
   function sql_query_file ( $ed339_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from criterioavaliacaodisciplina ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed339_sequencial!=null ){
         $sql2 .= " where criterioavaliacaodisciplina.ed339_sequencial = $ed339_sequencial "; 
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
