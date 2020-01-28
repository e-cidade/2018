<?
//MODULO: escola
//CLASSE DA ENTIDADE horarioescola
class cl_horarioescola { 
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
   var $ed123_sequencial = 0; 
   var $ed123_turnoreferencia = 0; 
   var $ed123_escola = 0; 
   var $ed123_horainicio = null; 
   var $ed123_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed123_sequencial = int4 = Código do Horário da Escola 
                 ed123_turnoreferencia = int4 = Turno de Referência 
                 ed123_escola = int8 = Código da Escola 
                 ed123_horainicio = varchar(5) = Horário Inicial 
                 ed123_horafim = varchar(5) = Horário Final 
                 ";
   //funcao construtor da classe 
   function cl_horarioescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("horarioescola"); 
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
       $this->ed123_sequencial = ($this->ed123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_sequencial"]:$this->ed123_sequencial);
       $this->ed123_turnoreferencia = ($this->ed123_turnoreferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_turnoreferencia"]:$this->ed123_turnoreferencia);
       $this->ed123_escola = ($this->ed123_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_escola"]:$this->ed123_escola);
       $this->ed123_horainicio = ($this->ed123_horainicio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_horainicio"]:$this->ed123_horainicio);
       $this->ed123_horafim = ($this->ed123_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_horafim"]:$this->ed123_horafim);
     }else{
       $this->ed123_sequencial = ($this->ed123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed123_sequencial"]:$this->ed123_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed123_sequencial){ 
      $this->atualizacampos();
     if($this->ed123_turnoreferencia == null ){ 
       $this->erro_sql = " Campo Turno de Referência não informado.";
       $this->erro_campo = "ed123_turnoreferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed123_escola == null ){ 
       $this->erro_sql = " Campo Código da Escola não informado.";
       $this->erro_campo = "ed123_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed123_horainicio == null ){ 
       $this->erro_sql = " Campo Horário Inicial não informado.";
       $this->erro_campo = "ed123_horainicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed123_horafim == null ){ 
       $this->erro_sql = " Campo Horário Final não informado.";
       $this->erro_campo = "ed123_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed123_sequencial == "" || $ed123_sequencial == null ){
       $result = db_query("select nextval('horarioescola_ed123_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: horarioescola_ed123_sequencial_seq do campo: ed123_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed123_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from horarioescola_ed123_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed123_sequencial)){
         $this->erro_sql = " Campo ed123_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed123_sequencial = $ed123_sequencial; 
       }
     }
     if(($this->ed123_sequencial == null) || ($this->ed123_sequencial == "") ){ 
       $this->erro_sql = " Campo ed123_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into horarioescola(
                                       ed123_sequencial 
                                      ,ed123_turnoreferencia 
                                      ,ed123_escola 
                                      ,ed123_horainicio 
                                      ,ed123_horafim 
                       )
                values (
                                $this->ed123_sequencial 
                               ,$this->ed123_turnoreferencia 
                               ,$this->ed123_escola 
                               ,'$this->ed123_horainicio' 
                               ,'$this->ed123_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Horário da Escola ($this->ed123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Horário da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Horário da Escola ($this->ed123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed123_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed123_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20759,'$this->ed123_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3735,20759,'','".AddSlashes(pg_result($resaco,0,'ed123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3735,20760,'','".AddSlashes(pg_result($resaco,0,'ed123_turnoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3735,20761,'','".AddSlashes(pg_result($resaco,0,'ed123_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3735,20762,'','".AddSlashes(pg_result($resaco,0,'ed123_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3735,20763,'','".AddSlashes(pg_result($resaco,0,'ed123_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed123_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update horarioescola set ";
     $virgula = "";
     if(trim($this->ed123_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed123_sequencial"])){ 
       $sql  .= $virgula." ed123_sequencial = $this->ed123_sequencial ";
       $virgula = ",";
       if(trim($this->ed123_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Horário da Escola não informado.";
         $this->erro_campo = "ed123_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed123_turnoreferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed123_turnoreferencia"])){ 
       $sql  .= $virgula." ed123_turnoreferencia = $this->ed123_turnoreferencia ";
       $virgula = ",";
       if(trim($this->ed123_turnoreferencia) == null ){ 
         $this->erro_sql = " Campo Turno de Referência não informado.";
         $this->erro_campo = "ed123_turnoreferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed123_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed123_escola"])){ 
       $sql  .= $virgula." ed123_escola = $this->ed123_escola ";
       $virgula = ",";
       if(trim($this->ed123_escola) == null ){ 
         $this->erro_sql = " Campo Código da Escola não informado.";
         $this->erro_campo = "ed123_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed123_horainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed123_horainicio"])){ 
       $sql  .= $virgula." ed123_horainicio = '$this->ed123_horainicio' ";
       $virgula = ",";
       if(trim($this->ed123_horainicio) == null ){ 
         $this->erro_sql = " Campo Horário Inicial não informado.";
         $this->erro_campo = "ed123_horainicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed123_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed123_horafim"])){ 
       $sql  .= $virgula." ed123_horafim = '$this->ed123_horafim' ";
       $virgula = ",";
       if(trim($this->ed123_horafim) == null ){ 
         $this->erro_sql = " Campo Horário Final não informado.";
         $this->erro_campo = "ed123_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed123_sequencial!=null){
       $sql .= " ed123_sequencial = $this->ed123_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed123_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20759,'$this->ed123_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed123_sequencial"]) || $this->ed123_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3735,20759,'".AddSlashes(pg_result($resaco,$conresaco,'ed123_sequencial'))."','$this->ed123_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed123_turnoreferencia"]) || $this->ed123_turnoreferencia != "")
             $resac = db_query("insert into db_acount values($acount,3735,20760,'".AddSlashes(pg_result($resaco,$conresaco,'ed123_turnoreferencia'))."','$this->ed123_turnoreferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed123_escola"]) || $this->ed123_escola != "")
             $resac = db_query("insert into db_acount values($acount,3735,20761,'".AddSlashes(pg_result($resaco,$conresaco,'ed123_escola'))."','$this->ed123_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed123_horainicio"]) || $this->ed123_horainicio != "")
             $resac = db_query("insert into db_acount values($acount,3735,20762,'".AddSlashes(pg_result($resaco,$conresaco,'ed123_horainicio'))."','$this->ed123_horainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed123_horafim"]) || $this->ed123_horafim != "")
             $resac = db_query("insert into db_acount values($acount,3735,20763,'".AddSlashes(pg_result($resaco,$conresaco,'ed123_horafim'))."','$this->ed123_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horário da Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Horário da Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed123_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed123_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20759,'$ed123_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3735,20759,'','".AddSlashes(pg_result($resaco,$iresaco,'ed123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3735,20760,'','".AddSlashes(pg_result($resaco,$iresaco,'ed123_turnoreferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3735,20761,'','".AddSlashes(pg_result($resaco,$iresaco,'ed123_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3735,20762,'','".AddSlashes(pg_result($resaco,$iresaco,'ed123_horainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3735,20763,'','".AddSlashes(pg_result($resaco,$iresaco,'ed123_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from horarioescola
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed123_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed123_sequencial = $ed123_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horário da Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Horário da Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:horarioescola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed123_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from horarioescola ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = horarioescola.ed123_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed123_sequencial)) {
         $sql2 .= " where horarioescola.ed123_sequencial = $ed123_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($ed123_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from horarioescola ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed123_sequencial)){
         $sql2 .= " where horarioescola.ed123_sequencial = $ed123_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
