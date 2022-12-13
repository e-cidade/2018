<?
//MODULO: laboratorio
//CLASSE DA ENTIDADE tiporeferenciacalculo
class cl_tiporeferenciacalculo { 
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
   var $la61_sequencial = 0; 
   var $la61_tiporeferencialnumerico = 0; 
   var $la61_atributobase = 0; 
   var $la61_tipocalculo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la61_sequencial = int4 = Sequencial 
                 la61_tiporeferencialnumerico = int4 = Código da Referência 
                 la61_atributobase = int4 = Atributo Referência 
                 la61_tipocalculo = int4 = Tipo do Calculo 
                 ";
   //funcao construtor da classe 
   function cl_tiporeferenciacalculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiporeferenciacalculo"); 
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
       $this->la61_sequencial = ($this->la61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la61_sequencial"]:$this->la61_sequencial);
       $this->la61_tiporeferencialnumerico = ($this->la61_tiporeferencialnumerico == ""?@$GLOBALS["HTTP_POST_VARS"]["la61_tiporeferencialnumerico"]:$this->la61_tiporeferencialnumerico);
       $this->la61_atributobase = ($this->la61_atributobase == ""?@$GLOBALS["HTTP_POST_VARS"]["la61_atributobase"]:$this->la61_atributobase);
       $this->la61_tipocalculo = ($this->la61_tipocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["la61_tipocalculo"]:$this->la61_tipocalculo);
     }else{
       $this->la61_sequencial = ($this->la61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["la61_sequencial"]:$this->la61_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($la61_sequencial){ 
      $this->atualizacampos();
     if($this->la61_tiporeferencialnumerico == null ){ 
       $this->erro_sql = " Campo Código da Referência não informado.";
       $this->erro_campo = "la61_tiporeferencialnumerico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la61_tipocalculo == 2 && $this->la61_atributobase == null){
       $this->erro_sql = " Campo Atributo Referência não informado.";
       $this->erro_campo = "la61_atributobase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la61_tipocalculo == null ){ 
       $this->erro_sql = " Campo Tipo do Calculo não informado.";
       $this->erro_campo = "la61_tipocalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la61_sequencial == "" || $la61_sequencial == null ){
       $result = db_query("select nextval('tiporeferenciacalculo_la61_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tiporeferenciacalculo_la61_sequencial_seq do campo: la61_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la61_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tiporeferenciacalculo_la61_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $la61_sequencial)){
         $this->erro_sql = " Campo la61_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la61_sequencial = $la61_sequencial; 
       }
     }
     if(($this->la61_sequencial == null) || ($this->la61_sequencial == "") ){ 
       $this->erro_sql = " Campo la61_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if(empty($this->la61_atributobase)) {
       $this->la61_atributobase = 'NULL';
     }

     $sql = "insert into tiporeferenciacalculo(
                                       la61_sequencial 
                                      ,la61_tiporeferencialnumerico 
                                      ,la61_atributobase 
                                      ,la61_tipocalculo 
                       )
                values (
                                $this->la61_sequencial 
                               ,$this->la61_tiporeferencialnumerico 
                               ,$this->la61_atributobase
                               ,$this->la61_tipocalculo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calculos da Referencia ($this->la61_sequencial) já cadastrada. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calculos da Referencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calculos da Referencia ($this->la61_sequencial) nao Incluído. Inclusao Abortada: " . $this->erro_banco;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la61_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la61_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20492,'$this->la61_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3686,20492,'','".AddSlashes(pg_result($resaco,0,'la61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3686,20493,'','".AddSlashes(pg_result($resaco,0,'la61_tiporeferencialnumerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3686,20494,'','".AddSlashes(pg_result($resaco,0,'la61_atributobase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3686,20495,'','".AddSlashes(pg_result($resaco,0,'la61_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la61_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tiporeferenciacalculo set ";
     $virgula = "";
     if(trim($this->la61_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la61_sequencial"])){ 
       $sql  .= $virgula." la61_sequencial = $this->la61_sequencial ";
       $virgula = ",";
       if(trim($this->la61_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "la61_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la61_tiporeferencialnumerico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la61_tiporeferencialnumerico"])){ 
       $sql  .= $virgula." la61_tiporeferencialnumerico = $this->la61_tiporeferencialnumerico ";
       $virgula = ",";
       if(trim($this->la61_tiporeferencialnumerico) == null ){ 
         $this->erro_sql = " Campo Código da Referência não informado.";
         $this->erro_campo = "la61_tiporeferencialnumerico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la61_atributobase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la61_atributobase"])){ 
       $sql  .= $virgula." la61_atributobase = $this->la61_atributobase ";
       $virgula = ",";
       if(trim($this->la61_atributobase) == null ){ 
         $this->erro_sql = " Campo Atributo Referência não informado.";
         $this->erro_campo = "la61_atributobase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la61_tipocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la61_tipocalculo"])){ 
       $sql  .= $virgula." la61_tipocalculo = $this->la61_tipocalculo ";
       $virgula = ",";
       if(trim($this->la61_tipocalculo) == null ){ 
         $this->erro_sql = " Campo Tipo do Calculo não informado.";
         $this->erro_campo = "la61_tipocalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la61_sequencial!=null){
       $sql .= " la61_sequencial = $this->la61_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la61_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20492,'$this->la61_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la61_sequencial"]) || $this->la61_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3686,20492,'".AddSlashes(pg_result($resaco,$conresaco,'la61_sequencial'))."','$this->la61_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la61_tiporeferencialnumerico"]) || $this->la61_tiporeferencialnumerico != "")
             $resac = db_query("insert into db_acount values($acount,3686,20493,'".AddSlashes(pg_result($resaco,$conresaco,'la61_tiporeferencialnumerico'))."','$this->la61_tiporeferencialnumerico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la61_atributobase"]) || $this->la61_atributobase != "")
             $resac = db_query("insert into db_acount values($acount,3686,20494,'".AddSlashes(pg_result($resaco,$conresaco,'la61_atributobase'))."','$this->la61_atributobase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la61_tipocalculo"]) || $this->la61_tipocalculo != "")
             $resac = db_query("insert into db_acount values($acount,3686,20495,'".AddSlashes(pg_result($resaco,$conresaco,'la61_tipocalculo'))."','$this->la61_tipocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculos da Referencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculos da Referencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la61_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($la61_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20492,'$la61_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3686,20492,'','".AddSlashes(pg_result($resaco,$iresaco,'la61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3686,20493,'','".AddSlashes(pg_result($resaco,$iresaco,'la61_tiporeferencialnumerico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3686,20494,'','".AddSlashes(pg_result($resaco,$iresaco,'la61_atributobase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3686,20495,'','".AddSlashes(pg_result($resaco,$iresaco,'la61_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tiporeferenciacalculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la61_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la61_sequencial = $la61_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculos da Referencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculos da Referencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la61_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tiporeferenciacalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la61_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiporeferenciacalculo ";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = tiporeferenciacalculo.la61_atributobase";
     $sql .= "      inner join lab_tiporeferenciaalnumerico  on  lab_tiporeferenciaalnumerico.la30_i_codigo = tiporeferenciacalculo.la61_tiporeferencialnumerico";
     $sql .= "      left  join lab_valorreferencia  on  lab_valorreferencia.la27_i_codigo = lab_tiporeferenciaalnumerico.la30_i_valorref";
     $sql2 = "";
     if($dbwhere==""){
       if($la61_sequencial!=null ){
         $sql2 .= " where tiporeferenciacalculo.la61_sequencial = $la61_sequencial "; 
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
   function sql_query_file ( $la61_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiporeferenciacalculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($la61_sequencial!=null ){
         $sql2 .= " where tiporeferenciacalculo.la61_sequencial = $la61_sequencial "; 
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
