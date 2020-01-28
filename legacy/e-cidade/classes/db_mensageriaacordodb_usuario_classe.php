<?
//MODULO: acordos
//CLASSE DA ENTIDADE mensageriaacordodb_usuario
class cl_mensageriaacordodb_usuario { 
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
   var $ac52_sequencial = 0; 
   var $ac52_db_usuarios = 0; 
   var $ac52_dias = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac52_sequencial = int4 = Sequencial 
                 ac52_db_usuarios = int4 = db_usuarios 
                 ac52_dias = int4 = Dias 
                 ";
   //funcao construtor da classe 
   function cl_mensageriaacordodb_usuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mensageriaacordodb_usuario"); 
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
       $this->ac52_sequencial = ($this->ac52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac52_sequencial"]:$this->ac52_sequencial);
       $this->ac52_db_usuarios = ($this->ac52_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["ac52_db_usuarios"]:$this->ac52_db_usuarios);
       $this->ac52_dias = ($this->ac52_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["ac52_dias"]:$this->ac52_dias);
     }else{
       $this->ac52_sequencial = ($this->ac52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac52_sequencial"]:$this->ac52_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac52_sequencial){ 
      $this->atualizacampos();
     if($this->ac52_db_usuarios == null ){ 
       $this->erro_sql = " Campo db_usuarios não informado.";
       $this->erro_campo = "ac52_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac52_dias == null ){ 
       $this->erro_sql = " Campo Dias não informado.";
       $this->erro_campo = "ac52_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac52_sequencial == "" || $ac52_sequencial == null ){
       $result = db_query("select nextval('mensageriaacordodb_usuario_ac52_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mensageriaacordodb_usuario_ac52_sequencial_seq do campo: ac52_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac52_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mensageriaacordodb_usuario_ac52_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac52_sequencial)){
         $this->erro_sql = " Campo ac52_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac52_sequencial = $ac52_sequencial; 
       }
     }
     if(($this->ac52_sequencial == null) || ($this->ac52_sequencial == "") ){ 
       $this->erro_sql = " Campo ac52_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mensageriaacordodb_usuario(
                                       ac52_sequencial 
                                      ,ac52_db_usuarios 
                                      ,ac52_dias 
                       )
                values (
                                $this->ac52_sequencial 
                               ,$this->ac52_db_usuarios 
                               ,$this->ac52_dias 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mensageriaacordodb_usuario ($this->ac52_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mensageriaacordodb_usuario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mensageriaacordodb_usuario ($this->ac52_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac52_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac52_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20577,'$this->ac52_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3702,20577,'','".AddSlashes(pg_result($resaco,0,'ac52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3702,20579,'','".AddSlashes(pg_result($resaco,0,'ac52_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3702,20580,'','".AddSlashes(pg_result($resaco,0,'ac52_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac52_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update mensageriaacordodb_usuario set ";
     $virgula = "";
     if(trim($this->ac52_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac52_sequencial"])){ 
       $sql  .= $virgula." ac52_sequencial = $this->ac52_sequencial ";
       $virgula = ",";
       if(trim($this->ac52_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ac52_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac52_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac52_db_usuarios"])){ 
       $sql  .= $virgula." ac52_db_usuarios = $this->ac52_db_usuarios ";
       $virgula = ",";
       if(trim($this->ac52_db_usuarios) == null ){ 
         $this->erro_sql = " Campo db_usuarios não informado.";
         $this->erro_campo = "ac52_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac52_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac52_dias"])){ 
       $sql  .= $virgula." ac52_dias = $this->ac52_dias ";
       $virgula = ",";
       if(trim($this->ac52_dias) == null ){ 
         $this->erro_sql = " Campo Dias não informado.";
         $this->erro_campo = "ac52_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac52_sequencial!=null){
       $sql .= " ac52_sequencial = $this->ac52_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac52_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20577,'$this->ac52_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac52_sequencial"]) || $this->ac52_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3702,20577,'".AddSlashes(pg_result($resaco,$conresaco,'ac52_sequencial'))."','$this->ac52_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac52_db_usuarios"]) || $this->ac52_db_usuarios != "")
             $resac = db_query("insert into db_acount values($acount,3702,20579,'".AddSlashes(pg_result($resaco,$conresaco,'ac52_db_usuarios'))."','$this->ac52_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac52_dias"]) || $this->ac52_dias != "")
             $resac = db_query("insert into db_acount values($acount,3702,20580,'".AddSlashes(pg_result($resaco,$conresaco,'ac52_dias'))."','$this->ac52_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mensageriaacordodb_usuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mensageriaacordodb_usuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac52_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ac52_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20577,'$ac52_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3702,20577,'','".AddSlashes(pg_result($resaco,$iresaco,'ac52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3702,20579,'','".AddSlashes(pg_result($resaco,$iresaco,'ac52_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3702,20580,'','".AddSlashes(pg_result($resaco,$iresaco,'ac52_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from mensageriaacordodb_usuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac52_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac52_sequencial = $ac52_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mensageriaacordodb_usuario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mensageriaacordodb_usuario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac52_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:mensageriaacordodb_usuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac52_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mensageriaacordodb_usuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mensageriaacordodb_usuario.ac52_db_usuarios";
     $sql2 = "";
     if($dbwhere==""){
       if($ac52_sequencial!=null ){
         $sql2 .= " where mensageriaacordodb_usuario.ac52_sequencial = $ac52_sequencial "; 
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
   function sql_query_file ( $ac52_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mensageriaacordodb_usuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac52_sequencial!=null ){
         $sql2 .= " where mensageriaacordodb_usuario.ac52_sequencial = $ac52_sequencial "; 
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
    * Busca usuarios que nao foram notificados
    *
    * @param string $sCampos
    * @param string $sOrdenacao
    * @param string $sWhere
    * @return string
    */
   public function sql_query_usuariosNotificar($sCampos = '*', $sOrdenacao = '', $sWhere = null) {
   
     $sSql  = "select {$sCampos}                                                                                   ";
     $sSql .= "   from mensageriaacordodb_usuario                                                                  ";
     $sSql .= "        left join mensageriaacordoprocessados on ac53_mensageriaacordodb_usuarios = ac52_sequencial ";
     $sSql .= "    where ac53_sequencial is null                                                                   ";

     if (!empty($sWhere)) {
       $sSql .= " and $sWhere ";
     }

     if (!empty($sOrdenacao)) {
       $sSql .= " order by $sOrdenacao";
     }

     return $sSql;
   }

}
