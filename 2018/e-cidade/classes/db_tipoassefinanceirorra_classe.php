<?
//MODULO: pessoal
//CLASSE DA ENTIDADE tipoassefinanceirorra
class cl_tipoassefinanceirorra { 
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
   var $rh172_sequencial = 0; 
   var $rh172_tipoasse = 0; 
   var $rh172_rubricaprevidencia = null; 
   var $rh172_rubricaprovento = null; 
   var $rh172_rubricapensao = null; 
   var $rh172_rubricairrf = null; 
   var $rh172_rubricaparceladeducao = null; 
   var $rh172_instit = 0; 
   var $rh172_rubricamolestia = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh172_sequencial = int4 = Código 
                 rh172_tipoasse = int4 = Tipo de Assentamento 
                 rh172_rubricaprevidencia = char(4) = Previdência 
                 rh172_rubricaprovento = char(4) = Provento 
                 rh172_rubricapensao = char(4) = Pensão Judicial 
                 rh172_rubricairrf = char(4) = IRRF 
                 rh172_rubricaparceladeducao = char(4) = Parcela de Dedução 
                 rh172_instit = int4 = Instituição 
                 rh172_rubricamolestia = char(4) = Isenção por Moléstia 
                 ";
   //funcao construtor da classe 
   function cl_tipoassefinanceirorra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoassefinanceirorra"); 
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
       $this->rh172_sequencial = ($this->rh172_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_sequencial"]:$this->rh172_sequencial);
       $this->rh172_tipoasse = ($this->rh172_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_tipoasse"]:$this->rh172_tipoasse);
       $this->rh172_rubricaprevidencia = ($this->rh172_rubricaprevidencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprevidencia"]:$this->rh172_rubricaprevidencia);
       $this->rh172_rubricaprovento = ($this->rh172_rubricaprovento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprovento"]:$this->rh172_rubricaprovento);
       $this->rh172_rubricapensao = ($this->rh172_rubricapensao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricapensao"]:$this->rh172_rubricapensao);
       $this->rh172_rubricairrf = ($this->rh172_rubricairrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricairrf"]:$this->rh172_rubricairrf);
       $this->rh172_rubricaparceladeducao = ($this->rh172_rubricaparceladeducao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricaparceladeducao"]:$this->rh172_rubricaparceladeducao);
       $this->rh172_instit = ($this->rh172_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_instit"]:$this->rh172_instit);
       $this->rh172_rubricamolestia = ($this->rh172_rubricamolestia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_rubricamolestia"]:$this->rh172_rubricamolestia);
     }else{
       $this->rh172_sequencial = ($this->rh172_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh172_sequencial"]:$this->rh172_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh172_sequencial){ 
      $this->atualizacampos();
     if($this->rh172_tipoasse == null ){ 
       $this->erro_sql = " Campo Tipo de Assentamento não informado.";
       $this->erro_campo = "rh172_tipoasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricaprevidencia == null ){ 
       $this->erro_sql = " Campo Previdência não informado.";
       $this->erro_campo = "rh172_rubricaprevidencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricaprovento == null ){ 
       $this->erro_sql = " Campo Provento não informado.";
       $this->erro_campo = "rh172_rubricaprovento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricapensao == null ){ 
       $this->erro_sql = " Campo Pensão Judicial não informado.";
       $this->erro_campo = "rh172_rubricapensao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricairrf == null ){ 
       $this->erro_sql = " Campo IRRF não informado.";
       $this->erro_campo = "rh172_rubricairrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricaparceladeducao == null ){ 
       $this->erro_sql = " Campo Parcela de Dedução não informado.";
       $this->erro_campo = "rh172_rubricaparceladeducao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh172_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh172_rubricamolestia == null ){ 
       $this->erro_sql = " Campo Isenção por Moléstia não informado.";
       $this->erro_campo = "rh172_rubricamolestia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh172_sequencial == "" || $rh172_sequencial == null ){
       $result = db_query("select nextval('tipoassefinanceirorra_rh172_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoassefinanceirorra_rh172_sequencial_seq do campo: rh172_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh172_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoassefinanceirorra_rh172_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh172_sequencial)){
         $this->erro_sql = " Campo rh172_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh172_sequencial = $rh172_sequencial; 
       }
     }
     if(($this->rh172_sequencial == null) || ($this->rh172_sequencial == "") ){ 
       $this->erro_sql = " Campo rh172_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoassefinanceirorra(
                                       rh172_sequencial 
                                      ,rh172_tipoasse 
                                      ,rh172_rubricaprevidencia 
                                      ,rh172_rubricaprovento 
                                      ,rh172_rubricapensao 
                                      ,rh172_rubricairrf 
                                      ,rh172_rubricaparceladeducao 
                                      ,rh172_instit 
                                      ,rh172_rubricamolestia 
                       )
                values (
                                $this->rh172_sequencial 
                               ,$this->rh172_tipoasse 
                               ,'$this->rh172_rubricaprevidencia' 
                               ,'$this->rh172_rubricaprovento' 
                               ,'$this->rh172_rubricapensao' 
                               ,'$this->rh172_rubricairrf' 
                               ,'$this->rh172_rubricaparceladeducao' 
                               ,$this->rh172_instit 
                               ,'$this->rh172_rubricamolestia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configurações do RRA para Assentamento ($this->rh172_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configurações do RRA para Assentamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configurações do RRA para Assentamento ($this->rh172_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh172_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh172_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21647,'$this->rh172_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3889,21647,'','".AddSlashes(pg_result($resaco,0,'rh172_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21648,'','".AddSlashes(pg_result($resaco,0,'rh172_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21649,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricaprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21650,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricaprovento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21651,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricapensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21652,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricairrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21664,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricaparceladeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21653,'','".AddSlashes(pg_result($resaco,0,'rh172_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3889,21703,'','".AddSlashes(pg_result($resaco,0,'rh172_rubricamolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh172_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tipoassefinanceirorra set ";
     $virgula = "";
     if(trim($this->rh172_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_sequencial"])){ 
       $sql  .= $virgula." rh172_sequencial = $this->rh172_sequencial ";
       $virgula = ",";
       if(trim($this->rh172_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh172_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_tipoasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_tipoasse"])){ 
       $sql  .= $virgula." rh172_tipoasse = $this->rh172_tipoasse ";
       $virgula = ",";
       if(trim($this->rh172_tipoasse) == null ){ 
         $this->erro_sql = " Campo Tipo de Assentamento não informado.";
         $this->erro_campo = "rh172_tipoasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricaprevidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprevidencia"])){ 
       $sql  .= $virgula." rh172_rubricaprevidencia = '$this->rh172_rubricaprevidencia' ";
       $virgula = ",";
       if(trim($this->rh172_rubricaprevidencia) == null ){ 
         $this->erro_sql = " Campo Previdência não informado.";
         $this->erro_campo = "rh172_rubricaprevidencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricaprovento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprovento"])){ 
       $sql  .= $virgula." rh172_rubricaprovento = '$this->rh172_rubricaprovento' ";
       $virgula = ",";
       if(trim($this->rh172_rubricaprovento) == null ){ 
         $this->erro_sql = " Campo Provento não informado.";
         $this->erro_campo = "rh172_rubricaprovento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricapensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricapensao"])){ 
       $sql  .= $virgula." rh172_rubricapensao = '$this->rh172_rubricapensao' ";
       $virgula = ",";
       if(trim($this->rh172_rubricapensao) == null ){ 
         $this->erro_sql = " Campo Pensão Judicial não informado.";
         $this->erro_campo = "rh172_rubricapensao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricairrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricairrf"])){ 
       $sql  .= $virgula." rh172_rubricairrf = '$this->rh172_rubricairrf' ";
       $virgula = ",";
       if(trim($this->rh172_rubricairrf) == null ){ 
         $this->erro_sql = " Campo IRRF não informado.";
         $this->erro_campo = "rh172_rubricairrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricaparceladeducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaparceladeducao"])){ 
       $sql  .= $virgula." rh172_rubricaparceladeducao = '$this->rh172_rubricaparceladeducao' ";
       $virgula = ",";
       if(trim($this->rh172_rubricaparceladeducao) == null ){ 
         $this->erro_sql = " Campo Parcela de Dedução não informado.";
         $this->erro_campo = "rh172_rubricaparceladeducao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_instit"])){ 
       $sql  .= $virgula." rh172_instit = $this->rh172_instit ";
       $virgula = ",";
       if(trim($this->rh172_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh172_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh172_rubricamolestia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricamolestia"])){ 
       $sql  .= $virgula." rh172_rubricamolestia = '$this->rh172_rubricamolestia' ";
       $virgula = ",";
       if(trim($this->rh172_rubricamolestia) == null ){ 
         $this->erro_sql = " Campo Isenção por Moléstia não informado.";
         $this->erro_campo = "rh172_rubricamolestia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh172_sequencial!=null){
       $sql .= " rh172_sequencial = $this->rh172_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh172_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21647,'$this->rh172_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_sequencial"]) || $this->rh172_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3889,21647,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_sequencial'))."','$this->rh172_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_tipoasse"]) || $this->rh172_tipoasse != "")
             $resac = db_query("insert into db_acount values($acount,3889,21648,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_tipoasse'))."','$this->rh172_tipoasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprevidencia"]) || $this->rh172_rubricaprevidencia != "")
             $resac = db_query("insert into db_acount values($acount,3889,21649,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricaprevidencia'))."','$this->rh172_rubricaprevidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaprovento"]) || $this->rh172_rubricaprovento != "")
             $resac = db_query("insert into db_acount values($acount,3889,21650,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricaprovento'))."','$this->rh172_rubricaprovento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricapensao"]) || $this->rh172_rubricapensao != "")
             $resac = db_query("insert into db_acount values($acount,3889,21651,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricapensao'))."','$this->rh172_rubricapensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricairrf"]) || $this->rh172_rubricairrf != "")
             $resac = db_query("insert into db_acount values($acount,3889,21652,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricairrf'))."','$this->rh172_rubricairrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricaparceladeducao"]) || $this->rh172_rubricaparceladeducao != "")
             $resac = db_query("insert into db_acount values($acount,3889,21664,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricaparceladeducao'))."','$this->rh172_rubricaparceladeducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_instit"]) || $this->rh172_instit != "")
             $resac = db_query("insert into db_acount values($acount,3889,21653,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_instit'))."','$this->rh172_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh172_rubricamolestia"]) || $this->rh172_rubricamolestia != "")
             $resac = db_query("insert into db_acount values($acount,3889,21703,'".AddSlashes(pg_result($resaco,$conresaco,'rh172_rubricamolestia'))."','$this->rh172_rubricamolestia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações do RRA para Assentamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh172_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configurações do RRA para Assentamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh172_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh172_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh172_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh172_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21647,'$rh172_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3889,21647,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21648,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21649,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricaprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21650,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricaprovento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21651,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricapensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21652,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricairrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21664,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricaparceladeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21653,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3889,21703,'','".AddSlashes(pg_result($resaco,$iresaco,'rh172_rubricamolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipoassefinanceirorra
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh172_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh172_sequencial = $rh172_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configurações do RRA para Assentamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh172_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configurações do RRA para Assentamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh172_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh172_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoassefinanceirorra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh172_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from tipoassefinanceirorra ";
     $sql .= "      inner join db_config  on  db_config.codigo = tipoassefinanceirorra.rh172_instit";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = tipoassefinanceirorra.rh172_tipoasse";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";

     $aTipos = array("previdencia", "provento", "pensao", "irrf", "parceladeducao", "molestia");

     foreach ($aTipos as $sTipoRubrica) {
       $sql .= "      inner join rhrubricas as rubrica{$sTipoRubrica} on rubrica{$sTipoRubrica}.rh27_rubric = tipoassefinanceirorra.rh172_rubrica{$sTipoRubrica} ";
       $sql .= "                                                     and rubrica{$sTipoRubrica}.rh27_instit = tipoassefinanceirorra.rh172_instit";
     }

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh172_sequencial)) {
         $sql2 .= " where tipoassefinanceirorra.rh172_sequencial = $rh172_sequencial "; 
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
   public function sql_query_file ($rh172_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tipoassefinanceirorra ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh172_sequencial)){
         $sql2 .= " where tipoassefinanceirorra.rh172_sequencial = $rh172_sequencial "; 
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
