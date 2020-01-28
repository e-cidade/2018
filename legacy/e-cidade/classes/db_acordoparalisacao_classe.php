<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoparalisacao
class cl_acordoparalisacao { 
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
   var $ac47_sequencial = 0; 
   var $ac47_acordo = 0; 
   var $ac47_datainicio_dia = null; 
   var $ac47_datainicio_mes = null; 
   var $ac47_datainicio_ano = null; 
   var $ac47_datainicio = null; 
   var $ac47_datafim_dia = null; 
   var $ac47_datafim_mes = null; 
   var $ac47_datafim_ano = null; 
   var $ac47_datafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac47_sequencial = int4 = Sequencial da Paralisação 
                 ac47_acordo = int4 = Acordo 
                 ac47_datainicio = date = Data Inicial 
                 ac47_datafim = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_acordoparalisacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoparalisacao"); 
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
       $this->ac47_sequencial = ($this->ac47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_sequencial"]:$this->ac47_sequencial);
       $this->ac47_acordo = ($this->ac47_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_acordo"]:$this->ac47_acordo);
       if($this->ac47_datainicio == ""){
         $this->ac47_datainicio_dia = ($this->ac47_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_dia"]:$this->ac47_datainicio_dia);
         $this->ac47_datainicio_mes = ($this->ac47_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_mes"]:$this->ac47_datainicio_mes);
         $this->ac47_datainicio_ano = ($this->ac47_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_ano"]:$this->ac47_datainicio_ano);
         if($this->ac47_datainicio_dia != ""){
            $this->ac47_datainicio = $this->ac47_datainicio_ano."-".$this->ac47_datainicio_mes."-".$this->ac47_datainicio_dia;
         }
       }
       if($this->ac47_datafim == ""){
         $this->ac47_datafim_dia = ($this->ac47_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datafim_dia"]:$this->ac47_datafim_dia);
         $this->ac47_datafim_mes = ($this->ac47_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datafim_mes"]:$this->ac47_datafim_mes);
         $this->ac47_datafim_ano = ($this->ac47_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_datafim_ano"]:$this->ac47_datafim_ano);
         if($this->ac47_datafim_dia != ""){
            $this->ac47_datafim = $this->ac47_datafim_ano."-".$this->ac47_datafim_mes."-".$this->ac47_datafim_dia;
         }
       }
     }else{
       $this->ac47_sequencial = ($this->ac47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac47_sequencial"]:$this->ac47_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac47_sequencial){ 
      $this->atualizacampos();
     if($this->ac47_acordo == null ){ 
       $this->erro_sql = " Campo Acordo não informado.";
       $this->erro_campo = "ac47_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac47_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicial não informado.";
       $this->erro_campo = "ac47_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac47_datafim == null ){ 
       $this->ac47_datafim = "null";
     }
     if($ac47_sequencial == "" || $ac47_sequencial == null ){
       $result = db_query("select nextval('acordoparalisacao_ac47_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoparalisacao_ac47_sequencial_seq do campo: ac47_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac47_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoparalisacao_ac47_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac47_sequencial)){
         $this->erro_sql = " Campo ac47_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac47_sequencial = $ac47_sequencial; 
       }
     }
     if(($this->ac47_sequencial == null) || ($this->ac47_sequencial == "") ){ 
       $this->erro_sql = " Campo ac47_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoparalisacao(
                                       ac47_sequencial 
                                      ,ac47_acordo 
                                      ,ac47_datainicio 
                                      ,ac47_datafim 
                       )
                values (
                                $this->ac47_sequencial 
                               ,$this->ac47_acordo 
                               ,".($this->ac47_datainicio == "null" || $this->ac47_datainicio == ""?"null":"'".$this->ac47_datainicio."'")." 
                               ,".($this->ac47_datafim == "null" || $this->ac47_datafim == ""?"null":"'".$this->ac47_datafim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Paralisação de contratos ($this->ac47_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Paralisação de contratos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Paralisação de contratos ($this->ac47_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac47_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac47_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20518,'$this->ac47_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3692,20518,'','".AddSlashes(pg_result($resaco,0,'ac47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3692,20519,'','".AddSlashes(pg_result($resaco,0,'ac47_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3692,20520,'','".AddSlashes(pg_result($resaco,0,'ac47_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3692,20521,'','".AddSlashes(pg_result($resaco,0,'ac47_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac47_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoparalisacao set ";
     $virgula = "";
     if(trim($this->ac47_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac47_sequencial"])){ 
       $sql  .= $virgula." ac47_sequencial = $this->ac47_sequencial ";
       $virgula = ",";
       if(trim($this->ac47_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da Paralisação não informado.";
         $this->erro_campo = "ac47_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac47_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac47_acordo"])){ 
       $sql  .= $virgula." ac47_acordo = $this->ac47_acordo ";
       $virgula = ",";
       if(trim($this->ac47_acordo) == null ){ 
         $this->erro_sql = " Campo Acordo não informado.";
         $this->erro_campo = "ac47_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac47_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." ac47_datainicio = '$this->ac47_datainicio' ";
       $virgula = ",";
       if(trim($this->ac47_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicial não informado.";
         $this->erro_campo = "ac47_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac47_datainicio_dia"])){ 
         $sql  .= $virgula." ac47_datainicio = null ";
         $virgula = ",";
         if(trim($this->ac47_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicial não informado.";
           $this->erro_campo = "ac47_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if( $this->ac47_datafim != 'null' &&  trim($this->ac47_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac47_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac47_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ac47_datafim = '$this->ac47_datafim' ";
       $virgula = ",";
     }     else{

         $sql  .= $virgula." ac47_datafim = null ";
         $virgula = ",";
     }
     $sql .= " where ";
     if($ac47_sequencial!=null){
       $sql .= " ac47_sequencial = $this->ac47_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac47_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20518,'$this->ac47_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac47_sequencial"]) || $this->ac47_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3692,20518,'".AddSlashes(pg_result($resaco,$conresaco,'ac47_sequencial'))."','$this->ac47_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac47_acordo"]) || $this->ac47_acordo != "")
             $resac = db_query("insert into db_acount values($acount,3692,20519,'".AddSlashes(pg_result($resaco,$conresaco,'ac47_acordo'))."','$this->ac47_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac47_datainicio"]) || $this->ac47_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3692,20520,'".AddSlashes(pg_result($resaco,$conresaco,'ac47_datainicio'))."','$this->ac47_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac47_datafim"]) || $this->ac47_datafim != "")
             $resac = db_query("insert into db_acount values($acount,3692,20521,'".AddSlashes(pg_result($resaco,$conresaco,'ac47_datafim'))."','$this->ac47_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paralisação de contratos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paralisação de contratos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac47_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ac47_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20518,'$ac47_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3692,20518,'','".AddSlashes(pg_result($resaco,$iresaco,'ac47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3692,20519,'','".AddSlashes(pg_result($resaco,$iresaco,'ac47_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3692,20520,'','".AddSlashes(pg_result($resaco,$iresaco,'ac47_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3692,20521,'','".AddSlashes(pg_result($resaco,$iresaco,'ac47_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoparalisacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac47_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac47_sequencial = $ac47_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Paralisação de contratos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac47_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Paralisação de contratos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac47_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac47_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoparalisacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoparalisacao ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoparalisacao.ac47_acordo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
     $sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac47_sequencial!=null ){
         $sql2 .= " where acordoparalisacao.ac47_sequencial = $ac47_sequencial "; 
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
   function sql_query_file ( $ac47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoparalisacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac47_sequencial!=null ){
         $sql2 .= " where acordoparalisacao.ac47_sequencial = $ac47_sequencial "; 
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
