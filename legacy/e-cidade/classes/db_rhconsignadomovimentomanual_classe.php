<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhconsignadomovimentomanual
class cl_rhconsignadomovimentomanual { 
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
   var $rh182_sequencial = 0; 
   var $rh182_rhconsignadomovimento = 0; 
   var $rh182_rhconsignadomovimentoservidor = 0; 
   var $rh182_processado = 'f'; 
   var $rh182_ano = 0; 
   var $rh182_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh182_sequencial = int4 = Sequencial 
                 rh182_rhconsignadomovimento = int4 = Sequencial Contrato 
                 rh182_rhconsignadomovimentoservidor = int4 = Sequencial da Parcela 
                 rh182_processado = bool = Flag de processamento 
                 rh182_ano = int4 = Ano da competência 
                 rh182_mes = int4 = Mês da competência 
                 ";
   //funcao construtor da classe 
   function cl_rhconsignadomovimentomanual() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhconsignadomovimentomanual"); 
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
       $this->rh182_sequencial = ($this->rh182_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_sequencial"]:$this->rh182_sequencial);
       $this->rh182_rhconsignadomovimento = ($this->rh182_rhconsignadomovimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimento"]:$this->rh182_rhconsignadomovimento);
       $this->rh182_rhconsignadomovimentoservidor = ($this->rh182_rhconsignadomovimentoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimentoservidor"]:$this->rh182_rhconsignadomovimentoservidor);
       $this->rh182_processado = ($this->rh182_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh182_processado"]:$this->rh182_processado);
       $this->rh182_ano = ($this->rh182_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_ano"]:$this->rh182_ano);
       $this->rh182_mes = ($this->rh182_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_mes"]:$this->rh182_mes);
     }else{
       $this->rh182_sequencial = ($this->rh182_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh182_sequencial"]:$this->rh182_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh182_sequencial){ 
      $this->atualizacampos();
     if($this->rh182_rhconsignadomovimento == null ){ 
       $this->erro_sql = " Campo Sequencial Contrato não informado.";
       $this->erro_campo = "rh182_rhconsignadomovimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh182_rhconsignadomovimentoservidor == null ){ 
       $this->erro_sql = " Campo Sequencial da Parcela não informado.";
       $this->erro_campo = "rh182_rhconsignadomovimentoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh182_processado == null ){ 
       $this->erro_sql = " Campo Flag de processamento não informado.";
       $this->erro_campo = "rh182_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh182_ano == null ){ 
       $this->erro_sql = " Campo Ano da competência não informado.";
       $this->erro_campo = "rh182_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh182_mes == null ){ 
       $this->erro_sql = " Campo Mês da competência não informado.";
       $this->erro_campo = "rh182_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh182_sequencial == "" || $rh182_sequencial == null ){
       $result = db_query("select nextval('rhconsignadomovimentomanual_rh182_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhconsignadomovimentomanual_rh182_sequencial_seq do campo: rh182_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh182_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhconsignadomovimentomanual_rh182_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh182_sequencial)){
         $this->erro_sql = " Campo rh182_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh182_sequencial = $rh182_sequencial; 
       }
     }
     if(($this->rh182_sequencial == null) || ($this->rh182_sequencial == "") ){ 
       $this->erro_sql = " Campo rh182_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhconsignadomovimentomanual(
                                       rh182_sequencial 
                                      ,rh182_rhconsignadomovimento 
                                      ,rh182_rhconsignadomovimentoservidor 
                                      ,rh182_processado 
                                      ,rh182_ano 
                                      ,rh182_mes 
                       )
                values (
                                $this->rh182_sequencial 
                               ,$this->rh182_rhconsignadomovimento 
                               ,$this->rh182_rhconsignadomovimentoservidor 
                               ,'$this->rh182_processado' 
                               ,$this->rh182_ano 
                               ,$this->rh182_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhconsignadomovimentomanual ($this->rh182_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhconsignadomovimentomanual já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhconsignadomovimentomanual ($this->rh182_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh182_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh182_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21983,'$this->rh182_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3956,21983,'','".AddSlashes(pg_result($resaco,0,'rh182_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3956,21978,'','".AddSlashes(pg_result($resaco,0,'rh182_rhconsignadomovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3956,21979,'','".AddSlashes(pg_result($resaco,0,'rh182_rhconsignadomovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3956,21980,'','".AddSlashes(pg_result($resaco,0,'rh182_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3956,21981,'','".AddSlashes(pg_result($resaco,0,'rh182_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3956,21982,'','".AddSlashes(pg_result($resaco,0,'rh182_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh182_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhconsignadomovimentomanual set ";
     $virgula = "";
     if(trim($this->rh182_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_sequencial"])){ 
       $sql  .= $virgula." rh182_sequencial = $this->rh182_sequencial ";
       $virgula = ",";
       if(trim($this->rh182_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh182_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh182_rhconsignadomovimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimento"])){ 
       $sql  .= $virgula." rh182_rhconsignadomovimento = $this->rh182_rhconsignadomovimento ";
       $virgula = ",";
       if(trim($this->rh182_rhconsignadomovimento) == null ){ 
         $this->erro_sql = " Campo Sequencial Contrato não informado.";
         $this->erro_campo = "rh182_rhconsignadomovimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh182_rhconsignadomovimentoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimentoservidor"])){ 
       $sql  .= $virgula." rh182_rhconsignadomovimentoservidor = $this->rh182_rhconsignadomovimentoservidor ";
       $virgula = ",";
       if(trim($this->rh182_rhconsignadomovimentoservidor) == null ){ 
         $this->erro_sql = " Campo Sequencial da Parcela não informado.";
         $this->erro_campo = "rh182_rhconsignadomovimentoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh182_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_processado"])){ 
       $sql  .= $virgula." rh182_processado = '$this->rh182_processado' ";
       $virgula = ",";
       if(trim($this->rh182_processado) == null ){ 
         $this->erro_sql = " Campo Flag de processamento não informado.";
         $this->erro_campo = "rh182_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh182_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_ano"])){ 
       $sql  .= $virgula." rh182_ano = $this->rh182_ano ";
       $virgula = ",";
       if(trim($this->rh182_ano) == null ){ 
         $this->erro_sql = " Campo Ano da competência não informado.";
         $this->erro_campo = "rh182_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh182_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh182_mes"])){ 
       $sql  .= $virgula." rh182_mes = $this->rh182_mes ";
       $virgula = ",";
       if(trim($this->rh182_mes) == null ){ 
         $this->erro_sql = " Campo Mês da competência não informado.";
         $this->erro_campo = "rh182_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh182_sequencial!=null){
       $sql .= " rh182_sequencial = $this->rh182_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh182_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21983,'$this->rh182_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_sequencial"]) || $this->rh182_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3956,21983,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_sequencial'))."','$this->rh182_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimento"]) || $this->rh182_rhconsignadomovimento != "")
             $resac = db_query("insert into db_acount values($acount,3956,21978,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_rhconsignadomovimento'))."','$this->rh182_rhconsignadomovimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_rhconsignadomovimentoservidor"]) || $this->rh182_rhconsignadomovimentoservidor != "")
             $resac = db_query("insert into db_acount values($acount,3956,21979,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_rhconsignadomovimentoservidor'))."','$this->rh182_rhconsignadomovimentoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_processado"]) || $this->rh182_processado != "")
             $resac = db_query("insert into db_acount values($acount,3956,21980,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_processado'))."','$this->rh182_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_ano"]) || $this->rh182_ano != "")
             $resac = db_query("insert into db_acount values($acount,3956,21981,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_ano'))."','$this->rh182_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh182_mes"]) || $this->rh182_mes != "")
             $resac = db_query("insert into db_acount values($acount,3956,21982,'".AddSlashes(pg_result($resaco,$conresaco,'rh182_mes'))."','$this->rh182_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentomanual não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh182_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentomanual não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh182_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh182_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21983,'$rh182_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3956,21983,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3956,21978,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_rhconsignadomovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3956,21979,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_rhconsignadomovimentoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3956,21980,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3956,21981,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3956,21982,'','".AddSlashes(pg_result($resaco,$iresaco,'rh182_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhconsignadomovimentomanual
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh182_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh182_sequencial = $rh182_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhconsignadomovimentomanual não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh182_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhconsignadomovimentomanual não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh182_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhconsignadomovimentomanual";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh182_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimentomanual ";
     $sql .= "      inner join rhconsignadomovimento  on  rhconsignadomovimento.rh151_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimento";
     $sql .= "      inner join rhconsignadomovimentoservidor  on  rhconsignadomovimentoservidor.rh152_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimentoservidor";
     $sql .= "      left  join db_config  on  db_config.codigo = rhconsignadomovimento.rh151_instit";
     $sql .= "      inner join rhconsignadomovimento  on  rhconsignadomovimento.rh151_sequencial = rhconsignadomovimentoservidor.rh152_consignadomovimento";
     $sql .= "      left  join rhconsignadomotivo  on  rhconsignadomotivo.rh154_sequencial = rhconsignadomovimentoservidor.rh152_consignadomotivo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh182_sequencial)) {
         $sql2 .= " where rhconsignadomovimentomanual.rh182_sequencial = $rh182_sequencial "; 
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
   public function sql_query_file ($rh182_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhconsignadomovimentomanual ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh182_sequencial)){
         $sql2 .= " where rhconsignadomovimentomanual.rh182_sequencial = $rh182_sequencial "; 
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

   public function sql_query_dados_financiamento ($rh182_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimentomanual ";
     $sql .= "      inner join rhconsignadomovimento  on  rhconsignadomovimento.rh151_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimento";
     $sql .= "      inner join rhconsignadomovimentoservidor  on  rhconsignadomovimentoservidor.rh152_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimentoservidor";
     $sql .= "      inner join rhconsignadomovimentoservidorrubrica on  rhconsignadomovimentoservidor.rh152_sequencial = rhconsignadomovimentoservidorrubrica.rh153_consignadomovimentoservidor";
     $sql .= "      left  join db_config  on  db_config.codigo = rhconsignadomovimento.rh151_instit";
     $sql .= "      left  join rhconsignadomotivo  on  rhconsignadomotivo.rh154_sequencial = rhconsignadomovimentoservidor.rh152_consignadomotivo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh182_sequencial)) {
         $sql2 .= " where rhconsignadomovimentomanual.rh182_sequencial = $rh182_sequencial ";
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

  public function sql_query_dados_financiamento_banco ($rh182_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from rhconsignadomovimentomanual ";
    $sql .= "      inner join rhconsignadomovimento  on  rhconsignadomovimento.rh151_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimento";
    $sql .= "      inner join db_bancos  on  rhconsignadomovimento.rh151_banco = db_bancos.db90_codban";
    $sql .= "      inner join rhconsignadomovimentoservidor  on  rhconsignadomovimentoservidor.rh152_sequencial = rhconsignadomovimentomanual.rh182_rhconsignadomovimentoservidor";
    $sql .= "      inner join rhconsignadomovimentoservidorrubrica on  rhconsignadomovimentoservidor.rh152_sequencial = rhconsignadomovimentoservidorrubrica.rh153_consignadomovimentoservidor";
    $sql .= "      left  join db_config  on  db_config.codigo = rhconsignadomovimento.rh151_instit";
    $sql .= "      left  join rhconsignadomotivo  on  rhconsignadomotivo.rh154_sequencial = rhconsignadomovimentoservidor.rh152_consignadomotivo";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($rh182_sequencial)) {
        $sql2 .= " where rhconsignadomovimentomanual.rh182_sequencial = $rh182_sequencial ";
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
