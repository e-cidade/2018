<?
//MODULO: recursoshumanos
//CLASSE DA ENTIDADE agendaassentamento
class cl_agendaassentamento { 
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
   var $h82_sequencial = 0; 
   var $h82_tipoassentamento = 0; 
   var $h82_formulainicio = 0; 
   var $h82_formulacondicao = 0; 
   var $h82_selecao = 0; 
   var $h82_instit = 0; 
   var $h82_formulafim = 0; 
   var $h82_formulafaltasperiodo = 0; 
   var $h82_formulaprorrogafim = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h82_sequencial = int4 = Sequencial da tabela 
                 h82_tipoassentamento = int4 = Tipo de Assentamento 
                 h82_formulainicio = int4 = Formula de Início 
                 h82_formulacondicao = int4 = Fórmula de Condição 
                 h82_selecao = int4 = Seleção 
                 h82_instit = int4 = Instituição 
                 h82_formulafim = int4 = Fórmula de Fim 
                 h82_formulafaltasperiodo = int4 = Fórmula de Faltas por Período 
                 h82_formulaprorrogafim = int4 = Fórmula de Prorrogação do Fim 
                 ";
   //funcao construtor da classe 
   function cl_agendaassentamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendaassentamento"); 
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
       $this->h82_sequencial = ($this->h82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_sequencial"]:$this->h82_sequencial);
       $this->h82_tipoassentamento = ($this->h82_tipoassentamento == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_tipoassentamento"]:$this->h82_tipoassentamento);
       $this->h82_formulainicio = ($this->h82_formulainicio == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_formulainicio"]:$this->h82_formulainicio);
       $this->h82_formulacondicao = ($this->h82_formulacondicao == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_formulacondicao"]:$this->h82_formulacondicao);
       $this->h82_selecao = ($this->h82_selecao == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_selecao"]:$this->h82_selecao);
       $this->h82_instit = ($this->h82_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_instit"]:$this->h82_instit);
       $this->h82_formulafim = ($this->h82_formulafim == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_formulafim"]:$this->h82_formulafim);
       $this->h82_formulafaltasperiodo = ($this->h82_formulafaltasperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_formulafaltasperiodo"]:$this->h82_formulafaltasperiodo);
       $this->h82_formulaprorrogafim = ($this->h82_formulaprorrogafim == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_formulaprorrogafim"]:$this->h82_formulaprorrogafim);
     }else{
       $this->h82_sequencial = ($this->h82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h82_sequencial"]:$this->h82_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($h82_sequencial){ 
      $this->atualizacampos();
     if($this->h82_tipoassentamento == null ){ 
       $this->erro_sql = " Campo Tipo de Assentamento não informado.";
       $this->erro_campo = "h82_tipoassentamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h82_formulainicio == null ){ 
       $this->erro_sql = " Campo Formula de Início não informado.";
       $this->erro_campo = "h82_formulainicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h82_formulacondicao == null ){ 
       $this->erro_sql = " Campo Fórmula de Condição não informado.";
       $this->erro_campo = "h82_formulacondicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h82_selecao == null ){ 
       $this->erro_sql = " Campo Seleção não informado.";
       $this->erro_campo = "h82_selecao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h82_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "h82_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h82_formulafim == null ){ 
       $this->h82_formulafim = "null";
     }
     if($this->h82_formulafaltasperiodo == null ){ 
       $this->h82_formulafaltasperiodo = "null";
     }
     if($this->h82_formulaprorrogafim == null ){ 
       $this->h82_formulaprorrogafim = "null";
     }
     if($h82_sequencial == "" || $h82_sequencial == null ){
       $result = db_query("select nextval('agendaassentamento_h82_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendaassentamento_h82_sequencial_seq do campo: h82_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h82_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendaassentamento_h82_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h82_sequencial)){
         $this->erro_sql = " Campo h82_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h82_sequencial = $h82_sequencial; 
       }
     }
     if(($this->h82_sequencial == null) || ($this->h82_sequencial == "") ){ 
       $this->erro_sql = " Campo h82_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendaassentamento(
                                       h82_sequencial 
                                      ,h82_tipoassentamento 
                                      ,h82_formulainicio 
                                      ,h82_formulacondicao 
                                      ,h82_selecao 
                                      ,h82_instit 
                                      ,h82_formulafim 
                                      ,h82_formulafaltasperiodo 
                                      ,h82_formulaprorrogafim 
                       )
                values (
                                $this->h82_sequencial 
                               ,$this->h82_tipoassentamento 
                               ,$this->h82_formulainicio 
                               ,$this->h82_formulacondicao 
                               ,$this->h82_selecao 
                               ,$this->h82_instit 
                               ,$this->h82_formulafim 
                               ,$this->h82_formulafaltasperiodo 
                               ,$this->h82_formulaprorrogafim 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda de Assentamentos ($this->h82_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda de Assentamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda de Assentamentos ($this->h82_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h82_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h82_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21279,'$this->h82_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3835,21279,'','".AddSlashes(pg_result($resaco,0,'h82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21280,'','".AddSlashes(pg_result($resaco,0,'h82_tipoassentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21281,'','".AddSlashes(pg_result($resaco,0,'h82_formulainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21282,'','".AddSlashes(pg_result($resaco,0,'h82_formulacondicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21283,'','".AddSlashes(pg_result($resaco,0,'h82_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21284,'','".AddSlashes(pg_result($resaco,0,'h82_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21801,'','".AddSlashes(pg_result($resaco,0,'h82_formulafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21802,'','".AddSlashes(pg_result($resaco,0,'h82_formulafaltasperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3835,21954,'','".AddSlashes(pg_result($resaco,0,'h82_formulaprorrogafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h82_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update agendaassentamento set ";
     $virgula = "";
     if(trim($this->h82_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_sequencial"])){ 
       $sql  .= $virgula." h82_sequencial = $this->h82_sequencial ";
       $virgula = ",";
       if(trim($this->h82_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "h82_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_tipoassentamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_tipoassentamento"])){ 
       $sql  .= $virgula." h82_tipoassentamento = $this->h82_tipoassentamento ";
       $virgula = ",";
       if(trim($this->h82_tipoassentamento) == null ){ 
         $this->erro_sql = " Campo Tipo de Assentamento não informado.";
         $this->erro_campo = "h82_tipoassentamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_formulainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_formulainicio"])){ 
       $sql  .= $virgula." h82_formulainicio = $this->h82_formulainicio ";
       $virgula = ",";
       if(trim($this->h82_formulainicio) == null ){ 
         $this->erro_sql = " Campo Formula de Início não informado.";
         $this->erro_campo = "h82_formulainicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_formulacondicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_formulacondicao"])){ 
       $sql  .= $virgula." h82_formulacondicao = $this->h82_formulacondicao ";
       $virgula = ",";
       if(trim($this->h82_formulacondicao) == null ){ 
         $this->erro_sql = " Campo Fórmula de Condição não informado.";
         $this->erro_campo = "h82_formulacondicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_selecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_selecao"])){ 
       $sql  .= $virgula." h82_selecao = $this->h82_selecao ";
       $virgula = ",";
       if(trim($this->h82_selecao) == null ){ 
         $this->erro_sql = " Campo Seleção não informado.";
         $this->erro_campo = "h82_selecao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_instit"])){ 
       $sql  .= $virgula." h82_instit = $this->h82_instit ";
       $virgula = ",";
       if(trim($this->h82_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "h82_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h82_formulafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafim"])){ 
        if(trim($this->h82_formulafim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafim"])){ 
           $this->h82_formulafim = "null" ;
        } 
       $sql  .= $virgula." h82_formulafim = $this->h82_formulafim ";
       $virgula = ",";
     }
     if(trim($this->h82_formulafaltasperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafaltasperiodo"])){ 
        if(trim($this->h82_formulafaltasperiodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafaltasperiodo"])){ 
           $this->h82_formulafaltasperiodo = "null" ;
        } 
       $sql  .= $virgula." h82_formulafaltasperiodo = $this->h82_formulafaltasperiodo ";
       $virgula = ",";
     }
     if(trim($this->h82_formulaprorrogafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h82_formulaprorrogafim"])){ 
        if(trim($this->h82_formulaprorrogafim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h82_formulaprorrogafim"])){ 
           $this->h82_formulaprorrogafim = "null" ;
        } 
       $sql  .= $virgula." h82_formulaprorrogafim = $this->h82_formulaprorrogafim ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h82_sequencial!=null){
       $sql .= " h82_sequencial = $this->h82_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h82_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21279,'$this->h82_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_sequencial"]) || $this->h82_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3835,21279,'".AddSlashes(pg_result($resaco,$conresaco,'h82_sequencial'))."','$this->h82_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_tipoassentamento"]) || $this->h82_tipoassentamento != "")
             $resac = db_query("insert into db_acount values($acount,3835,21280,'".AddSlashes(pg_result($resaco,$conresaco,'h82_tipoassentamento'))."','$this->h82_tipoassentamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_formulainicio"]) || $this->h82_formulainicio != "")
             $resac = db_query("insert into db_acount values($acount,3835,21281,'".AddSlashes(pg_result($resaco,$conresaco,'h82_formulainicio'))."','$this->h82_formulainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_formulacondicao"]) || $this->h82_formulacondicao != "")
             $resac = db_query("insert into db_acount values($acount,3835,21282,'".AddSlashes(pg_result($resaco,$conresaco,'h82_formulacondicao'))."','$this->h82_formulacondicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_selecao"]) || $this->h82_selecao != "")
             $resac = db_query("insert into db_acount values($acount,3835,21283,'".AddSlashes(pg_result($resaco,$conresaco,'h82_selecao'))."','$this->h82_selecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_instit"]) || $this->h82_instit != "")
             $resac = db_query("insert into db_acount values($acount,3835,21284,'".AddSlashes(pg_result($resaco,$conresaco,'h82_instit'))."','$this->h82_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafim"]) || $this->h82_formulafim != "")
             $resac = db_query("insert into db_acount values($acount,3835,21801,'".AddSlashes(pg_result($resaco,$conresaco,'h82_formulafim'))."','$this->h82_formulafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_formulafaltasperiodo"]) || $this->h82_formulafaltasperiodo != "")
             $resac = db_query("insert into db_acount values($acount,3835,21802,'".AddSlashes(pg_result($resaco,$conresaco,'h82_formulafaltasperiodo'))."','$this->h82_formulafaltasperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h82_formulaprorrogafim"]) || $this->h82_formulaprorrogafim != "")
             $resac = db_query("insert into db_acount values($acount,3835,21954,'".AddSlashes(pg_result($resaco,$conresaco,'h82_formulaprorrogafim'))."','$this->h82_formulaprorrogafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda de Assentamentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agenda de Assentamentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h82_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h82_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21279,'$h82_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3835,21279,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21280,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_tipoassentamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21281,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_formulainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21282,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_formulacondicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21283,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21284,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21801,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_formulafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21802,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_formulafaltasperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3835,21954,'','".AddSlashes(pg_result($resaco,$iresaco,'h82_formulaprorrogafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from agendaassentamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h82_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h82_sequencial = $h82_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda de Assentamentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Agenda de Assentamentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso\\n";
         $this->erro_sql .= "Valores : ".$h82_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendaassentamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($h82_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from agendaassentamento ";
     $sql .= "      inner join selecao                             on  selecao.r44_selec = agendaassentamento.h82_selecao and  selecao.r44_instit = agendaassentamento.h82_instit";
     $sql .= "      inner join tipoasse                            on  tipoasse.h12_codigo = agendaassentamento.h82_tipoassentamento";
     $sql .= "      inner join db_formulas as formulainicio        on formulainicio.db148_sequencial = agendaassentamento.h82_formulainicio";
     $sql .= "      left  join db_formulas as formulafim           on formulafim.db148_sequencial = agendaassentamento.h82_formulafim";
     $sql .= "      left  join db_formulas as formulafaltasperiodo on formulafaltasperiodo.db148_sequencial = agendaassentamento.h82_formulafaltasperiodo";
     $sql .= "      inner join db_formulas as formulacondicao      on formulacondicao.db148_sequencial = agendaassentamento.h82_formulacondicao";
     $sql .= "      left  join db_formulas as formulaprorroga      on formulaprorroga.db148_sequencial = agendaassentamento.h82_formulaprorrogafim";
     $sql .= "      inner join db_config                           on  db_config.codigo = selecao.r44_instit";
     $sql .= "      inner join gruposelecao                        on  gruposelecao.rh122_sequencial = selecao.r44_gruposelecao";
     $sql .= "      inner join naturezatipoassentamento            on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h82_sequencial)) {
         $sql2 .= " where agendaassentamento.h82_sequencial = $h82_sequencial "; 
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
   public function sql_query_file ($h82_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from agendaassentamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h82_sequencial)){
         $sql2 .= " where agendaassentamento.h82_sequencial = $h82_sequencial "; 
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
