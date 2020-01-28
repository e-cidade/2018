<?
//MODULO: escola
//CLASSE DA ENTIDADE turmamedicaodidaticopedagogica
class cl_turmamedicaodidaticopedagogica { 
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
   var $ed345_sequencial = 0; 
   var $ed345_turma = 0; 
   var $ed345_medicaodidaticopedagogica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed345_sequencial = int4 = Código 
                 ed345_turma = int4 = Turma 
                 ed345_medicaodidaticopedagogica = int4 = Medição Didático-Pedagógica 
                 ";
   //funcao construtor da classe 
   function cl_turmamedicaodidaticopedagogica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmamedicaodidaticopedagogica"); 
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
       $this->ed345_sequencial = ($this->ed345_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed345_sequencial"]:$this->ed345_sequencial);
       $this->ed345_turma = ($this->ed345_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed345_turma"]:$this->ed345_turma);
       $this->ed345_medicaodidaticopedagogica = ($this->ed345_medicaodidaticopedagogica == ""?@$GLOBALS["HTTP_POST_VARS"]["ed345_medicaodidaticopedagogica"]:$this->ed345_medicaodidaticopedagogica);
     }else{
       $this->ed345_sequencial = ($this->ed345_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed345_sequencial"]:$this->ed345_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed345_sequencial){ 
      $this->atualizacampos();
     if($this->ed345_turma == null ){ 
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed345_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed345_medicaodidaticopedagogica == null ){ 
       $this->erro_sql = " Campo Medição Didático-Pedagógica não informado.";
       $this->erro_campo = "ed345_medicaodidaticopedagogica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed345_sequencial == "" || $ed345_sequencial == null ){
       $result = db_query("select nextval('turmamedicaodidaticopedagogica_ed345_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmamedicaodidaticopedagogica_ed345_sequencial_seq do campo: ed345_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed345_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmamedicaodidaticopedagogica_ed345_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed345_sequencial)){
         $this->erro_sql = " Campo ed345_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed345_sequencial = $ed345_sequencial; 
       }
     }
     if(($this->ed345_sequencial == null) || ($this->ed345_sequencial == "") ){ 
       $this->erro_sql = " Campo ed345_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmamedicaodidaticopedagogica(
                                       ed345_sequencial 
                                      ,ed345_turma 
                                      ,ed345_medicaodidaticopedagogica 
                       )
                values (
                                $this->ed345_sequencial 
                               ,$this->ed345_turma 
                               ,$this->ed345_medicaodidaticopedagogica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmamedicaodidaticopedagogica ($this->ed345_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmamedicaodidaticopedagogica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmamedicaodidaticopedagogica ($this->ed345_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed345_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed345_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20600,'$this->ed345_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3708,20600,'','".AddSlashes(pg_result($resaco,0,'ed345_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3708,20601,'','".AddSlashes(pg_result($resaco,0,'ed345_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3708,20602,'','".AddSlashes(pg_result($resaco,0,'ed345_medicaodidaticopedagogica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed345_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update turmamedicaodidaticopedagogica set ";
     $virgula = "";
     if(trim($this->ed345_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed345_sequencial"])){ 
       $sql  .= $virgula." ed345_sequencial = $this->ed345_sequencial ";
       $virgula = ",";
       if(trim($this->ed345_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed345_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed345_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed345_turma"])){ 
       $sql  .= $virgula." ed345_turma = $this->ed345_turma ";
       $virgula = ",";
       if(trim($this->ed345_turma) == null ){ 
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed345_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed345_medicaodidaticopedagogica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed345_medicaodidaticopedagogica"])){ 
       $sql  .= $virgula." ed345_medicaodidaticopedagogica = $this->ed345_medicaodidaticopedagogica ";
       $virgula = ",";
       if(trim($this->ed345_medicaodidaticopedagogica) == null ){ 
         $this->erro_sql = " Campo Medição Didático-Pedagógica não informado.";
         $this->erro_campo = "ed345_medicaodidaticopedagogica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed345_sequencial!=null){
       $sql .= " ed345_sequencial = $this->ed345_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed345_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20600,'$this->ed345_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed345_sequencial"]) || $this->ed345_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3708,20600,'".AddSlashes(pg_result($resaco,$conresaco,'ed345_sequencial'))."','$this->ed345_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed345_turma"]) || $this->ed345_turma != "")
             $resac = db_query("insert into db_acount values($acount,3708,20601,'".AddSlashes(pg_result($resaco,$conresaco,'ed345_turma'))."','$this->ed345_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed345_medicaodidaticopedagogica"]) || $this->ed345_medicaodidaticopedagogica != "")
             $resac = db_query("insert into db_acount values($acount,3708,20602,'".AddSlashes(pg_result($resaco,$conresaco,'ed345_medicaodidaticopedagogica'))."','$this->ed345_medicaodidaticopedagogica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmamedicaodidaticopedagogica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed345_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmamedicaodidaticopedagogica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed345_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed345_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed345_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed345_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20600,'$ed345_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3708,20600,'','".AddSlashes(pg_result($resaco,$iresaco,'ed345_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3708,20601,'','".AddSlashes(pg_result($resaco,$iresaco,'ed345_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3708,20602,'','".AddSlashes(pg_result($resaco,$iresaco,'ed345_medicaodidaticopedagogica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmamedicaodidaticopedagogica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed345_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed345_sequencial = $ed345_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmamedicaodidaticopedagogica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed345_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmamedicaodidaticopedagogica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed345_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed345_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmamedicaodidaticopedagogica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed345_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmamedicaodidaticopedagogica ";
     $sql .= "      inner join medicaoditaticopedagocica  on  medicaoditaticopedagocica.ed344_sequencial = turmamedicaodidaticopedagogica.ed345_medicaodidaticopedagogica";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmamedicaodidaticopedagogica.ed345_turma";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed345_sequencial!=null ){
         $sql2 .= " where turmamedicaodidaticopedagogica.ed345_sequencial = $ed345_sequencial "; 
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
   function sql_query_file ( $ed345_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmamedicaodidaticopedagogica ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed345_sequencial!=null ){
         $sql2 .= " where turmamedicaodidaticopedagogica.ed345_sequencial = $ed345_sequencial "; 
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
