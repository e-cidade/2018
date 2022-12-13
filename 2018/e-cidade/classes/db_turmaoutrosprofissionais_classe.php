<?
//MODULO: escola
//CLASSE DA ENTIDADE turmaoutrosprofissionais
class cl_turmaoutrosprofissionais {
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
   var $ed347_sequencial = 0;
   var $ed347_turma = 0;
   var $ed347_rechumano = 0;
   var $ed347_funcaoatividade = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed347_sequencial = int4 = Código
                 ed347_turma = int4 = Turma
                 ed347_rechumano = int4 = Profissional
                 ed347_funcaoatividade = int4 = Função
                 ";
   //funcao construtor da classe
   function cl_turmaoutrosprofissionais() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaoutrosprofissionais");
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
       $this->ed347_sequencial = ($this->ed347_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed347_sequencial"]:$this->ed347_sequencial);
       $this->ed347_turma = ($this->ed347_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed347_turma"]:$this->ed347_turma);
       $this->ed347_rechumano = ($this->ed347_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed347_rechumano"]:$this->ed347_rechumano);
       $this->ed347_funcaoatividade = ($this->ed347_funcaoatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed347_funcaoatividade"]:$this->ed347_funcaoatividade);
     }else{
       $this->ed347_sequencial = ($this->ed347_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed347_sequencial"]:$this->ed347_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed347_sequencial){
      $this->atualizacampos();
     if($this->ed347_turma == null ){
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed347_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed347_rechumano == null ){
       $this->erro_sql = " Campo Profissional não informado.";
       $this->erro_campo = "ed347_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed347_funcaoatividade == null ){
       $this->erro_sql = " Campo Função não informado.";
       $this->erro_campo = "ed347_funcaoatividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed347_sequencial == "" || $ed347_sequencial == null ){
       $result = db_query("select nextval('turmaoutrosprofissionais_ed347_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaoutrosprofissionais_ed347_sequencial_seq do campo: ed347_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed347_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from turmaoutrosprofissionais_ed347_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed347_sequencial)){
         $this->erro_sql = " Campo ed347_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed347_sequencial = $ed347_sequencial;
       }
     }
     if(($this->ed347_sequencial == null) || ($this->ed347_sequencial == "") ){
       $this->erro_sql = " Campo ed347_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaoutrosprofissionais(
                                       ed347_sequencial
                                      ,ed347_turma
                                      ,ed347_rechumano
                                      ,ed347_funcaoatividade
                       )
                values (
                                $this->ed347_sequencial
                               ,$this->ed347_turma
                               ,$this->ed347_rechumano
                               ,$this->ed347_funcaoatividade
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmaoutrosprofissionais ($this->ed347_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmaoutrosprofissionais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmaoutrosprofissionais ($this->ed347_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed347_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed347_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20610,'$this->ed347_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3710,20610,'','".AddSlashes(pg_result($resaco,0,'ed347_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3710,20611,'','".AddSlashes(pg_result($resaco,0,'ed347_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3710,20612,'','".AddSlashes(pg_result($resaco,0,'ed347_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3710,20613,'','".AddSlashes(pg_result($resaco,0,'ed347_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed347_sequencial=null) {
      $this->atualizacampos();
     $sql = " update turmaoutrosprofissionais set ";
     $virgula = "";
     if(trim($this->ed347_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed347_sequencial"])){
       $sql  .= $virgula." ed347_sequencial = $this->ed347_sequencial ";
       $virgula = ",";
       if(trim($this->ed347_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed347_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed347_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed347_turma"])){
       $sql  .= $virgula." ed347_turma = $this->ed347_turma ";
       $virgula = ",";
       if(trim($this->ed347_turma) == null ){
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed347_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed347_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed347_rechumano"])){
       $sql  .= $virgula." ed347_rechumano = $this->ed347_rechumano ";
       $virgula = ",";
       if(trim($this->ed347_rechumano) == null ){
         $this->erro_sql = " Campo Profissional não informado.";
         $this->erro_campo = "ed347_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed347_funcaoatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed347_funcaoatividade"])){
       $sql  .= $virgula." ed347_funcaoatividade = $this->ed347_funcaoatividade ";
       $virgula = ",";
       if(trim($this->ed347_funcaoatividade) == null ){
         $this->erro_sql = " Campo Função não informado.";
         $this->erro_campo = "ed347_funcaoatividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed347_sequencial!=null){
       $sql .= " ed347_sequencial = $this->ed347_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed347_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20610,'$this->ed347_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed347_sequencial"]) || $this->ed347_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3710,20610,'".AddSlashes(pg_result($resaco,$conresaco,'ed347_sequencial'))."','$this->ed347_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed347_turma"]) || $this->ed347_turma != "")
             $resac = db_query("insert into db_acount values($acount,3710,20611,'".AddSlashes(pg_result($resaco,$conresaco,'ed347_turma'))."','$this->ed347_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed347_rechumano"]) || $this->ed347_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,3710,20612,'".AddSlashes(pg_result($resaco,$conresaco,'ed347_rechumano'))."','$this->ed347_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed347_funcaoatividade"]) || $this->ed347_funcaoatividade != "")
             $resac = db_query("insert into db_acount values($acount,3710,20613,'".AddSlashes(pg_result($resaco,$conresaco,'ed347_funcaoatividade'))."','$this->ed347_funcaoatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaoutrosprofissionais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed347_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaoutrosprofissionais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed347_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed347_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed347_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed347_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20610,'$ed347_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3710,20610,'','".AddSlashes(pg_result($resaco,$iresaco,'ed347_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3710,20611,'','".AddSlashes(pg_result($resaco,$iresaco,'ed347_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3710,20612,'','".AddSlashes(pg_result($resaco,$iresaco,'ed347_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3710,20613,'','".AddSlashes(pg_result($resaco,$iresaco,'ed347_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmaoutrosprofissionais
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed347_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed347_sequencial = $ed347_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaoutrosprofissionais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed347_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaoutrosprofissionais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed347_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed347_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaoutrosprofissionais";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed347_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from turmaoutrosprofissionais ";
     $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaoutrosprofissionais.ed347_funcaoatividade";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaoutrosprofissionais.ed347_turma";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaoutrosprofissionais.ed347_rechumano";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join turmacensoetapa on turmacensoetapa.ed132_turma = turma.ed57_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat and  censomunic. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as a on   a.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed347_sequencial!=null ){
         $sql2 .= " where turmaoutrosprofissionais.ed347_sequencial = $ed347_sequencial ";
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
   function sql_query_file ( $ed347_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from turmaoutrosprofissionais ";
     $sql2 = "";

     if($dbwhere==""){
       if($ed347_sequencial!=null ){
         $sql2 .= " where turmaoutrosprofissionais.ed347_sequencial = $ed347_sequencial ";
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

  function sql_query_profissional_atividade( $ed347_sequencial=null, $campos="*", $ordem=null, $dbwhere="" ) {

    $sql = "select ";

    if ( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for ( $i = 0; $i < sizeof( $campos_sql ); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from turmaoutrosprofissionais ";
    $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaoutrosprofissionais.ed347_funcaoatividade";
    $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaoutrosprofissionais.ed347_turma";
    $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaoutrosprofissionais.ed347_rechumano";
    $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sql2 = "";

    if ( $dbwhere == "" ) {

      if ( $ed347_sequencial != null ) {
        $sql2 .= " where turmaoutrosprofissionais.ed347_sequencial = $ed347_sequencial ";
      }
    } else if ( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if ( $ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split( "#", $ordem );
      $virgula    = "";

      for ( $i = 0; $i < sizeof( $campos_sql ); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  function sql_query_cgm ( $ed347_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

    $sql = "select ";
    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from turmaoutrosprofissionais ";
    $sql .= "      inner join funcaoatividade    on funcaoatividade.ed119_sequencial   = turmaoutrosprofissionais.ed347_funcaoatividade";
    $sql .= "      inner join turma              on turma.ed57_i_codigo                = turmaoutrosprofissionais.ed347_turma";
    $sql .= "      inner join rechumano          on rechumano.ed20_i_codigo            = turmaoutrosprofissionais.ed347_rechumano";
    $sql .= "      inner join rechumanoescola    on rechumano.ed20_i_codigo            = rechumanoescola.ed75_i_rechumano";
    $sql .= "      left  join censocursoprofiss  on censocursoprofiss.ed247_i_codigo   = turma.ed57_i_censocursoprofiss";
    $sql .= "      inner join escola             on escola.ed18_i_codigo               = turma.ed57_i_escola";
    $sql .= "      inner join turno              on turno.ed15_i_codigo                = turma.ed57_i_turno";
    $sql .= "      inner join sala               on sala.ed16_i_codigo                 = turma.ed57_i_sala";
    $sql .= "      inner join calendario         on calendario.ed52_i_codigo           = turma.ed57_i_calendario";
    $sql .= "      inner join base               on base.ed31_i_codigo                 = turma.ed57_i_base";
    $sql .= "      left  join rhregime           on rhregime.rh30_codreg               = rechumano.ed20_i_rhregime";
    $sql .= "      inner join pais               on pais.ed228_i_codigo                = rechumano.ed20_i_pais";
    $sql .= "      left  join censouf            on censouf.ed260_i_codigo             = rechumano.ed20_i_censoufcert";
    $sql .= "                                   and censouf.ed260_i_codigo             = rechumano.ed20_i_censoufender";
    $sql .= "                                   and censouf.ed260_i_codigo             = rechumano.ed20_i_censoufnat";
    $sql .= "                                   and censouf.ed260_i_codigo             = rechumano.ed20_i_censoufident";
    $sql .= "      left  join censoorgemissrg    on censoorgemissrg.ed132_i_codigo     = rechumano.ed20_i_censoorgemiss";
    $sql .= "      left  join censocartorio      on censocartorio.ed291_i_codigo       = rechumano.ed20_i_censocartorio";
    $sql .= "      left  join rechumano  as a    on a.ed20_i_codigo                    = rechumano.ed20_i_censocartorio";
    $sql .= "      left  join rechumanopessoal   on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      left  join rhpessoal          on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left  join cgm as cgmrh       on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm";
    $sql .= "      left  join rechumanocgm       on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo";
    $sql .= "      left  join cgm as cgmcgm      on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm";
    $sql .= "      left  join turmacensoturma    on turmacensoturma.ed343_turma        = turma.ed57_i_codigo";
    $sql .= "      left  join turmacenso         on turmacenso.ed342_sequencial        = turmacensoturma.ed343_turmacenso";
    $sql .= "      inner join turmacensoetapa    on turmacensoetapa.ed132_turma        = turma.ed57_i_codigo";
    $sql .= "      inner join censoetapa         on censoetapa.ed266_i_codigo          = turmacensoetapa.ed132_censoetapa";
    $sql .= "                                   and censoetapa.ed266_ano               = calendario.ed52_i_ano";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed347_sequencial != null ) {
        $sql2 .= " where turmaoutrosprofissionais.ed347_sequencial = $ed347_sequencial ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}