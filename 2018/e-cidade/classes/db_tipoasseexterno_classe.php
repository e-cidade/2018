<?
//MODULO: pessoal
//CLASSE DA ENTIDADE tipoasseexterno
class cl_tipoasseexterno { 
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
   var $rh167_sequencial = 0; 
   var $rh167_anousu = 0; 
   var $rh167_mesusu = 0; 
   var $rh167_codmovsefip = null; 
   var $rh167_tipoasse = 0; 
   var $rh167_situacaoafastamento = 0; 
   var $rh167_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh167_sequencial = int4 = Sequencial da tabela 
                 rh167_anousu = int4 = Ano 
                 rh167_mesusu = int4 = Mês 
                 rh167_codmovsefip = varchar(2) = Código sefip 
                 rh167_tipoasse = int4 = Tipo de assentamento 
                 rh167_situacaoafastamento = int4 = Situação do afastamento 
                 rh167_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_tipoasseexterno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoasseexterno"); 
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
       $this->rh167_sequencial = ($this->rh167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_sequencial"]:$this->rh167_sequencial);
       $this->rh167_anousu = ($this->rh167_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_anousu"]:$this->rh167_anousu);
       $this->rh167_mesusu = ($this->rh167_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_mesusu"]:$this->rh167_mesusu);
       $this->rh167_codmovsefip = ($this->rh167_codmovsefip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_codmovsefip"]:$this->rh167_codmovsefip);
       $this->rh167_tipoasse = ($this->rh167_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_tipoasse"]:$this->rh167_tipoasse);
       $this->rh167_situacaoafastamento = ($this->rh167_situacaoafastamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_situacaoafastamento"]:$this->rh167_situacaoafastamento);
       $this->rh167_instit = ($this->rh167_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_instit"]:$this->rh167_instit);
     }else{
       $this->rh167_sequencial = ($this->rh167_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh167_sequencial"]:$this->rh167_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh167_sequencial){ 
      $this->atualizacampos();
     if($this->rh167_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh167_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh167_mesusu == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh167_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh167_codmovsefip == null ){ 
       $this->erro_sql = " Campo Código sefip não informado.";
       $this->erro_campo = "rh167_codmovsefip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh167_tipoasse == null ){ 
       $this->erro_sql = " Campo Tipo de assentamento não informado.";
       $this->erro_campo = "rh167_tipoasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh167_situacaoafastamento == null ){ 
       $this->erro_sql = " Campo Situação do afastamento não informado.";
       $this->erro_campo = "rh167_situacaoafastamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh167_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh167_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh167_sequencial == "" || $rh167_sequencial == null ){
       $result = db_query("select nextval('tipoasseexterno_rh167_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoasseexterno_rh167_sequencial_seq do campo: rh167_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh167_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoasseexterno_rh167_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh167_sequencial)){
         $this->erro_sql = " Campo rh167_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh167_sequencial = $rh167_sequencial; 
       }
     }
     if(($this->rh167_sequencial == null) || ($this->rh167_sequencial == "") ){ 
       $this->erro_sql = " Campo rh167_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoasseexterno(
                                       rh167_sequencial 
                                      ,rh167_anousu 
                                      ,rh167_mesusu 
                                      ,rh167_codmovsefip 
                                      ,rh167_tipoasse 
                                      ,rh167_situacaoafastamento 
                                      ,rh167_instit 
                       )
                values (
                                $this->rh167_sequencial 
                               ,$this->rh167_anousu 
                               ,$this->rh167_mesusu 
                               ,'$this->rh167_codmovsefip' 
                               ,$this->rh167_tipoasse 
                               ,$this->rh167_situacaoafastamento 
                               ,$this->rh167_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculos entre tipos de assentamento e outras ($this->rh167_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculos entre tipos de assentamento e outras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculos entre tipos de assentamento e outras ($this->rh167_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh167_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh167_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21268,'$this->rh167_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3833,21268,'','".AddSlashes(pg_result($resaco,0,'rh167_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21269,'','".AddSlashes(pg_result($resaco,0,'rh167_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21270,'','".AddSlashes(pg_result($resaco,0,'rh167_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21271,'','".AddSlashes(pg_result($resaco,0,'rh167_codmovsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21272,'','".AddSlashes(pg_result($resaco,0,'rh167_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21273,'','".AddSlashes(pg_result($resaco,0,'rh167_situacaoafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3833,21274,'','".AddSlashes(pg_result($resaco,0,'rh167_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh167_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tipoasseexterno set ";
     $virgula = "";
     if(trim($this->rh167_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_sequencial"])){ 
       $sql  .= $virgula." rh167_sequencial = $this->rh167_sequencial ";
       $virgula = ",";
       if(trim($this->rh167_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh167_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_anousu"])){ 
       $sql  .= $virgula." rh167_anousu = $this->rh167_anousu ";
       $virgula = ",";
       if(trim($this->rh167_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh167_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_mesusu"])){ 
       $sql  .= $virgula." rh167_mesusu = $this->rh167_mesusu ";
       $virgula = ",";
       if(trim($this->rh167_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh167_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_codmovsefip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_codmovsefip"])){ 
       $sql  .= $virgula." rh167_codmovsefip = '$this->rh167_codmovsefip' ";
       $virgula = ",";
       if(trim($this->rh167_codmovsefip) == null ){ 
         $this->erro_sql = " Campo Código sefip não informado.";
         $this->erro_campo = "rh167_codmovsefip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_tipoasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_tipoasse"])){ 
       $sql  .= $virgula." rh167_tipoasse = $this->rh167_tipoasse ";
       $virgula = ",";
       if(trim($this->rh167_tipoasse) == null ){ 
         $this->erro_sql = " Campo Tipo de assentamento não informado.";
         $this->erro_campo = "rh167_tipoasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_situacaoafastamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_situacaoafastamento"])){ 
       $sql  .= $virgula." rh167_situacaoafastamento = $this->rh167_situacaoafastamento ";
       $virgula = ",";
       if(trim($this->rh167_situacaoafastamento) == null ){ 
         $this->erro_sql = " Campo Situação do afastamento não informado.";
         $this->erro_campo = "rh167_situacaoafastamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh167_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh167_instit"])){ 
       $sql  .= $virgula." rh167_instit = $this->rh167_instit ";
       $virgula = ",";
       if(trim($this->rh167_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh167_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh167_sequencial!=null){
       $sql .= " rh167_sequencial = $this->rh167_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh167_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21268,'$this->rh167_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_sequencial"]) || $this->rh167_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3833,21268,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_sequencial'))."','$this->rh167_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_anousu"]) || $this->rh167_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3833,21269,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_anousu'))."','$this->rh167_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_mesusu"]) || $this->rh167_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3833,21270,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_mesusu'))."','$this->rh167_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_codmovsefip"]) || $this->rh167_codmovsefip != "")
             $resac = db_query("insert into db_acount values($acount,3833,21271,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_codmovsefip'))."','$this->rh167_codmovsefip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_tipoasse"]) || $this->rh167_tipoasse != "")
             $resac = db_query("insert into db_acount values($acount,3833,21272,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_tipoasse'))."','$this->rh167_tipoasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_situacaoafastamento"]) || $this->rh167_situacaoafastamento != "")
             $resac = db_query("insert into db_acount values($acount,3833,21273,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_situacaoafastamento'))."','$this->rh167_situacaoafastamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh167_instit"]) || $this->rh167_instit != "")
             $resac = db_query("insert into db_acount values($acount,3833,21274,'".AddSlashes(pg_result($resaco,$conresaco,'rh167_instit'))."','$this->rh167_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculos entre tipos de assentamento e outras não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculos entre tipos de assentamento e outras não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh167_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh167_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21268,'$rh167_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3833,21268,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21269,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21270,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21271,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_codmovsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21272,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21273,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_situacaoafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3833,21274,'','".AddSlashes(pg_result($resaco,$iresaco,'rh167_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipoasseexterno
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh167_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh167_sequencial = $rh167_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculos entre tipos de assentamento e outras não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh167_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculos entre tipos de assentamento e outras não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh167_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh167_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoasseexterno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh167_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from tipoasseexterno ";
     $sql .= "      inner join db_config  on  db_config.codigo = tipoasseexterno.rh167_instit";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = tipoasseexterno.rh167_tipoasse";
     $sql .= "      inner join codmovsefip  on  codmovsefip.r66_anousu = tipoasseexterno.rh167_anousu and  codmovsefip.r66_mesusu = tipoasseexterno.rh167_mesusu and  codmovsefip.r66_codigo = tipoasseexterno.rh167_codmovsefip";
     $sql .= "      inner join situacaoafastamento  on  situacaoafastamento.rh166_sequencial = tipoasseexterno.rh167_situacaoafastamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql .= "       left join movcasadassefip  on  movcasadassefip.r67_afast = codmovsefip.r66_codigo and movcasadassefip.r67_anousu = codmovsefip.r66_anousu  and movcasadassefip.r67_mesusu = codmovsefip.r66_mesusu";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh167_sequencial)) {
         $sql2 .= " where tipoasseexterno.rh167_sequencial = $rh167_sequencial "; 
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
   public function sql_query_file ($rh167_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tipoasseexterno ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh167_sequencial)){
         $sql2 .= " where tipoasseexterno.rh167_sequencial = $rh167_sequencial "; 
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
   public function sql_query_com_join ($rh167_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "", $join = null) { 

     $sql  = "select {$campos}";
     $sql .= "  from tipoasseexterno ";
     $sql2 = "";
     if(!empty($join)){
       $sql .= $join;
     }
     if (empty($dbwhere)) {
       if (!empty($rh167_sequencial)) {
         $sql2 .= " where tipoasseexterno.rh167_sequencial = $rh167_sequencial "; 
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
