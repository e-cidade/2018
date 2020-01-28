<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhconsignacaobancolayout
class cl_rhconsignacaobancolayout { 
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
   var $rh178_sequencial = 0; 
   var $rh178_db_banco = null; 
   var $rh178_layout = 0; 
   var $rh178_rubrica = null; 
   var $rh178_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh178_sequencial = int4 = Codigo 
                 rh178_db_banco = varchar(10) = Banco 
                 rh178_layout = int4 = Layout 
                 rh178_rubrica = char(4) = Rubrica 
                 rh178_instit = int4 = Instituiçao 
                 ";
   //funcao construtor da classe 
   function cl_rhconsignacaobancolayout() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhconsignacaobancolayout"); 
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
       $this->rh178_sequencial = ($this->rh178_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_sequencial"]:$this->rh178_sequencial);
       $this->rh178_db_banco = ($this->rh178_db_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_db_banco"]:$this->rh178_db_banco);
       $this->rh178_layout = ($this->rh178_layout == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_layout"]:$this->rh178_layout);
       $this->rh178_rubrica = ($this->rh178_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_rubrica"]:$this->rh178_rubrica);
       $this->rh178_instit = ($this->rh178_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_instit"]:$this->rh178_instit);
     }else{
       $this->rh178_sequencial = ($this->rh178_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh178_sequencial"]:$this->rh178_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh178_sequencial){ 
      $this->atualizacampos();
     if($this->rh178_db_banco == null ){ 
       $this->erro_sql = " Campo Banco não informado.";
       $this->erro_campo = "rh178_db_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh178_layout == null ){ 
       $this->erro_sql = " Campo Layout não informado.";
       $this->erro_campo = "rh178_layout";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh178_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh178_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh178_instit == null ){ 
       $this->erro_sql = " Campo Instituiçao não informado.";
       $this->erro_campo = "rh178_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh178_sequencial == "" || $rh178_sequencial == null ){
       $result = db_query("select nextval('rhconsignacaobancolayout_rh178_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhconsignacaobancolayout_rh178_sequencial_seq do campo: rh178_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh178_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhconsignacaobancolayout_rh178_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh178_sequencial)){
         $this->erro_sql = " Campo rh178_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh178_sequencial = $rh178_sequencial; 
       }
     }
     if(($this->rh178_sequencial == null) || ($this->rh178_sequencial == "") ){ 
       $this->erro_sql = " Campo rh178_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhconsignacaobancolayout(
                                       rh178_sequencial 
                                      ,rh178_db_banco 
                                      ,rh178_layout 
                                      ,rh178_rubrica 
                                      ,rh178_instit 
                       )
                values (
                                $this->rh178_sequencial 
                               ,'$this->rh178_db_banco' 
                               ,$this->rh178_layout 
                               ,'$this->rh178_rubrica' 
                               ,$this->rh178_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo dos layouts com banco ($this->rh178_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo dos layouts com banco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo dos layouts com banco ($this->rh178_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh178_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh178_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21859,'$this->rh178_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3936,21859,'','".AddSlashes(pg_result($resaco,0,'rh178_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3936,21860,'','".AddSlashes(pg_result($resaco,0,'rh178_db_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3936,21861,'','".AddSlashes(pg_result($resaco,0,'rh178_layout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3936,21862,'','".AddSlashes(pg_result($resaco,0,'rh178_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3936,21863,'','".AddSlashes(pg_result($resaco,0,'rh178_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh178_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhconsignacaobancolayout set ";
     $virgula = "";
     if(trim($this->rh178_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh178_sequencial"])){ 
       $sql  .= $virgula." rh178_sequencial = $this->rh178_sequencial ";
       $virgula = ",";
       if(trim($this->rh178_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "rh178_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh178_db_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh178_db_banco"])){ 
       $sql  .= $virgula." rh178_db_banco = '$this->rh178_db_banco' ";
       $virgula = ",";
       if(trim($this->rh178_db_banco) == null ){ 
         $this->erro_sql = " Campo Banco não informado.";
         $this->erro_campo = "rh178_db_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh178_layout)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh178_layout"])){ 
       $sql  .= $virgula." rh178_layout = $this->rh178_layout ";
       $virgula = ",";
       if(trim($this->rh178_layout) == null ){ 
         $this->erro_sql = " Campo Layout não informado.";
         $this->erro_campo = "rh178_layout";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh178_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh178_rubrica"])){ 
       $sql  .= $virgula." rh178_rubrica = '$this->rh178_rubrica' ";
       $virgula = ",";
       if(trim($this->rh178_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh178_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh178_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh178_instit"])){ 
       $sql  .= $virgula." rh178_instit = $this->rh178_instit ";
       $virgula = ",";
       if(trim($this->rh178_instit) == null ){ 
         $this->erro_sql = " Campo Instituiçao não informado.";
         $this->erro_campo = "rh178_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh178_sequencial!=null){
       $sql .= " rh178_sequencial = $this->rh178_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh178_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21859,'$this->rh178_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh178_sequencial"]) || $this->rh178_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3936,21859,'".AddSlashes(pg_result($resaco,$conresaco,'rh178_sequencial'))."','$this->rh178_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh178_db_banco"]) || $this->rh178_db_banco != "")
             $resac = db_query("insert into db_acount values($acount,3936,21860,'".AddSlashes(pg_result($resaco,$conresaco,'rh178_db_banco'))."','$this->rh178_db_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh178_layout"]) || $this->rh178_layout != "")
             $resac = db_query("insert into db_acount values($acount,3936,21861,'".AddSlashes(pg_result($resaco,$conresaco,'rh178_layout'))."','$this->rh178_layout',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh178_rubrica"]) || $this->rh178_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3936,21862,'".AddSlashes(pg_result($resaco,$conresaco,'rh178_rubrica'))."','$this->rh178_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh178_instit"]) || $this->rh178_instit != "")
             $resac = db_query("insert into db_acount values($acount,3936,21863,'".AddSlashes(pg_result($resaco,$conresaco,'rh178_instit'))."','$this->rh178_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo dos layouts com banco não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh178_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo dos layouts com banco não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh178_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh178_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh178_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh178_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21859,'$rh178_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3936,21859,'','".AddSlashes(pg_result($resaco,$iresaco,'rh178_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3936,21860,'','".AddSlashes(pg_result($resaco,$iresaco,'rh178_db_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3936,21861,'','".AddSlashes(pg_result($resaco,$iresaco,'rh178_layout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3936,21862,'','".AddSlashes(pg_result($resaco,$iresaco,'rh178_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3936,21863,'','".AddSlashes(pg_result($resaco,$iresaco,'rh178_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhconsignacaobancolayout
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh178_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh178_sequencial = $rh178_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo dos layouts com banco não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh178_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo dos layouts com banco não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh178_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh178_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhconsignacaobancolayout";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh178_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignacaobancolayout ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhconsignacaobancolayout.rh178_rubrica and  rhrubricas.rh27_instit = rhconsignacaobancolayout.rh178_instit";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rhconsignacaobancolayout.rh178_db_banco";
     $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = rhconsignacaobancolayout.rh178_layout";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
     $sql .= "      inner join db_layouttxtgrupo  on  db_layouttxtgrupo.db56_sequencial = db_layouttxt.db50_layouttxtgrupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh178_sequencial)) {
         $sql2 .= " where rhconsignacaobancolayout.rh178_sequencial = $rh178_sequencial "; 
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
   public function sql_query_file ($rh178_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhconsignacaobancolayout ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh178_sequencial)){
         $sql2 .= " where rhconsignacaobancolayout.rh178_sequencial = $rh178_sequencial "; 
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
