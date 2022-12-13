<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhfolhapagamento
class cl_rhfolhapagamento { 
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
   var $rh141_sequencial = 0; 
   var $rh141_codigo = 0; 
   var $rh141_anoref = 0; 
   var $rh141_mesref = 0; 
   var $rh141_anousu = 0; 
   var $rh141_mesusu = 0; 
   var $rh141_instit = 0; 
   var $rh141_tipofolha = 0; 
   var $rh141_aberto = 'f'; 
   var $rh141_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh141_sequencial = int4 = Sequencial 
                 rh141_codigo = int4 = Código da Folha 
                 rh141_anoref = int4 = Ano de Referência 
                 rh141_mesref = int4 = Mês de Referência 
                 rh141_anousu = int4 = Ano 
                 rh141_mesusu = int4 = Mês 
                 rh141_instit = int4 = Instituição 
                 rh141_tipofolha = int4 = Tipo da Folha 
                 rh141_aberto = bool = Aberto 
                 rh141_descricao = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_rhfolhapagamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhfolhapagamento"); 
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

       $this->rh141_sequencial = ($this->rh141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_sequencial"]:$this->rh141_sequencial);
       $this->rh141_codigo = ($this->rh141_codigo === ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_codigo"]:$this->rh141_codigo);
       $this->rh141_anoref = ($this->rh141_anoref == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_anoref"]:$this->rh141_anoref);
       $this->rh141_mesref = ($this->rh141_mesref == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_mesref"]:$this->rh141_mesref);
       $this->rh141_anousu = ($this->rh141_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_anousu"]:$this->rh141_anousu);
       $this->rh141_mesusu = ($this->rh141_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_mesusu"]:$this->rh141_mesusu);
       $this->rh141_instit = ($this->rh141_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_instit"]:$this->rh141_instit);
       $this->rh141_tipofolha = ($this->rh141_tipofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_tipofolha"]:$this->rh141_tipofolha);
       $this->rh141_aberto = ($this->rh141_aberto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_aberto"]:$this->rh141_aberto);
       $this->rh141_descricao = ($this->rh141_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_descricao"]:$this->rh141_descricao);
     }else{
       $this->rh141_sequencial = ($this->rh141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh141_sequencial"]:$this->rh141_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh141_sequencial){ 

      $this->atualizacampos();

     if($this->rh141_codigo === null ){ 
       $this->erro_sql = " Campo Código da Folha não informado.";
       $this->erro_campo = "rh141_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_anoref == null ){ 
       $this->erro_sql = " Campo Ano de Referência não informado.";
       $this->erro_campo = "rh141_anoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_mesref == null ){ 
       $this->erro_sql = " Campo Mês de Referência não informado.";
       $this->erro_campo = "rh141_mesref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh141_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_mesusu == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh141_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh141_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_tipofolha == null ){ 
       $this->erro_sql = " Campo Tipo da Folha não informado.";
       $this->erro_campo = "rh141_tipofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_aberto == null ){ 
       $this->erro_sql = " Campo Aberto não informado.";
       $this->erro_campo = "rh141_aberto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh141_descricao == null ){ 
       $this->erro_sql = "Descrição não informada.";
       $this->erro_campo = "rh141_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh141_sequencial == "" || $rh141_sequencial == null ){
       $result = db_query("select nextval('rhfolhapagamento_rh141_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhfolhapagamento_rh141_sequencial_seq do campo: rh141_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh141_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhfolhapagamento_rh141_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh141_sequencial)){
         $this->erro_sql = " Campo rh141_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh141_sequencial = $rh141_sequencial; 
       }
     }
     if(($this->rh141_sequencial == null) || ($this->rh141_sequencial == "") ){ 
       $this->erro_sql = " Campo rh141_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfolhapagamento(
                                       rh141_sequencial 
                                      ,rh141_codigo 
                                      ,rh141_anoref 
                                      ,rh141_mesref 
                                      ,rh141_anousu 
                                      ,rh141_mesusu 
                                      ,rh141_instit 
                                      ,rh141_tipofolha 
                                      ,rh141_aberto 
                                      ,rh141_descricao 
                       )
                values (
                                $this->rh141_sequencial 
                               ,$this->rh141_codigo 
                               ,$this->rh141_anoref 
                               ,$this->rh141_mesref 
                               ,$this->rh141_anousu 
                               ,$this->rh141_mesusu 
                               ,$this->rh141_instit 
                               ,$this->rh141_tipofolha 
                               ,'$this->rh141_aberto' 
                               ,'$this->rh141_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Folha de Pagamento ($this->rh141_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Folha de Pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Folha de Pagamento ($this->rh141_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh141_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh141_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20706,'$this->rh141_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3727,20706,'','".AddSlashes(pg_result($resaco,0,'rh141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20707,'','".AddSlashes(pg_result($resaco,0,'rh141_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20708,'','".AddSlashes(pg_result($resaco,0,'rh141_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20709,'','".AddSlashes(pg_result($resaco,0,'rh141_mesref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20710,'','".AddSlashes(pg_result($resaco,0,'rh141_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20711,'','".AddSlashes(pg_result($resaco,0,'rh141_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20712,'','".AddSlashes(pg_result($resaco,0,'rh141_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20713,'','".AddSlashes(pg_result($resaco,0,'rh141_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20714,'','".AddSlashes(pg_result($resaco,0,'rh141_aberto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3727,20717,'','".AddSlashes(pg_result($resaco,0,'rh141_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh141_sequencial=null) { 

     $this->atualizacampos();

     $sql = " update rhfolhapagamento set ";
     $virgula = "";
     if(trim($this->rh141_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_sequencial"])){ 
       $sql  .= $virgula." rh141_sequencial = $this->rh141_sequencial ";
       $virgula = ",";
       if(trim($this->rh141_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh141_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_codigo"])){ 
       $sql  .= $virgula." rh141_codigo = $this->rh141_codigo ";
       $virgula = ",";
       if(trim($this->rh141_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Folha não informado.";
         $this->erro_campo = "rh141_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_anoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_anoref"])){ 
       $sql  .= $virgula." rh141_anoref = $this->rh141_anoref ";
       $virgula = ",";
       if(trim($this->rh141_anoref) == null ){ 
         $this->erro_sql = " Campo Ano de Referência não informado.";
         $this->erro_campo = "rh141_anoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_mesref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_mesref"])){ 
       $sql  .= $virgula." rh141_mesref = $this->rh141_mesref ";
       $virgula = ",";
       if(trim($this->rh141_mesref) == null ){ 
         $this->erro_sql = " Campo Mês de Referência não informado.";
         $this->erro_campo = "rh141_mesref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_anousu"])){ 
       $sql  .= $virgula." rh141_anousu = $this->rh141_anousu ";
       $virgula = ",";
       if(trim($this->rh141_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh141_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_mesusu"])){ 
       $sql  .= $virgula." rh141_mesusu = $this->rh141_mesusu ";
       $virgula = ",";
       if(trim($this->rh141_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh141_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_instit"])){ 
       $sql  .= $virgula." rh141_instit = $this->rh141_instit ";
       $virgula = ",";
       if(trim($this->rh141_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh141_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_tipofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_tipofolha"])){ 
       $sql  .= $virgula." rh141_tipofolha = $this->rh141_tipofolha ";
       $virgula = ",";
       if(trim($this->rh141_tipofolha) == null ){ 
         $this->erro_sql = " Campo Tipo da Folha não informado.";
         $this->erro_campo = "rh141_tipofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->rh141_aberto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_aberto"])){ 
       $sql  .= $virgula." rh141_aberto = '$this->rh141_aberto' ";
       $virgula = ",";
       if(trim($this->rh141_aberto) == null ){ 
         $this->erro_sql = " Campo Aberto não informado.";
         $this->erro_campo = "rh141_aberto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh141_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh141_descricao"])){ 
       $sql  .= $virgula." rh141_descricao = '$this->rh141_descricao' ";
       $virgula = ",";
       if(trim($this->rh141_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh141_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh141_sequencial!=null){
       $sql .= " rh141_sequencial = $this->rh141_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh141_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20706,'$this->rh141_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_sequencial"]) || $this->rh141_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3727,20706,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_sequencial'))."','$this->rh141_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_codigo"]) || $this->rh141_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3727,20707,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_codigo'))."','$this->rh141_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_anoref"]) || $this->rh141_anoref != "")
             $resac = db_query("insert into db_acount values($acount,3727,20708,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_anoref'))."','$this->rh141_anoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_mesref"]) || $this->rh141_mesref != "")
             $resac = db_query("insert into db_acount values($acount,3727,20709,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_mesref'))."','$this->rh141_mesref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_anousu"]) || $this->rh141_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3727,20710,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_anousu'))."','$this->rh141_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_mesusu"]) || $this->rh141_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3727,20711,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_mesusu'))."','$this->rh141_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_instit"]) || $this->rh141_instit != "")
             $resac = db_query("insert into db_acount values($acount,3727,20712,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_instit'))."','$this->rh141_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_tipofolha"]) || $this->rh141_tipofolha != "")
             $resac = db_query("insert into db_acount values($acount,3727,20713,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_tipofolha'))."','$this->rh141_tipofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_aberto"]) || $this->rh141_aberto != "")
             $resac = db_query("insert into db_acount values($acount,3727,20714,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_aberto'))."','$this->rh141_aberto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh141_descricao"]) || $this->rh141_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3727,20717,'".AddSlashes(pg_result($resaco,$conresaco,'rh141_descricao'))."','$this->rh141_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }

     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Folha de Pagamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Folha de Pagamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh141_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh141_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20706,'$rh141_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3727,20706,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20707,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20708,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20709,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_mesref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20710,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20711,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20712,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20713,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20714,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_aberto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3727,20717,'','".AddSlashes(pg_result($resaco,$iresaco,'rh141_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhfolhapagamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh141_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh141_sequencial = $rh141_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Folha de Pagamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Folha de Pagamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh141_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfolhapagamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh141_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfolhapagamento ";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhfolhapagamento.rh141_tipofolha";
     $sql2 = "";
     if($dbwhere==""){
       if($rh141_sequencial!=null ){
         $sql2 .= " where rhfolhapagamento.rh141_sequencial = $rh141_sequencial "; 
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
   function sql_query_file ( $rh141_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfolhapagamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh141_sequencial!=null ){
         $sql2 .= " where rhfolhapagamento.rh141_sequencial = $rh141_sequencial "; 
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

  /**
   * Retorna uma query que verifica se existe calculo efetuado na competência atual
   * 
   * @access public
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @return String
   */
  
  public function sql_query_gerfs(Instituicao $oInstituicao, DBCompetencia $oCompetencia) {
    
    $iAno         = $oCompetencia->getAno();
    $iMes         = $oCompetencia->getMes();
    $iInstituicao = $oInstituicao->getCodigo();

    $sSql  = "select 1 from gerfsal where r14_anousu = {$iAno} and r14_mesusu = {$iMes}          ";
    $sSql .= "	                                               and r14_instit = {$iInstituicao}  "; 
    $sSql .= "UNION ALL                                                                          ";
    $sSql .= "select 1 from gerfcom where r48_anousu = {$iAno} and r48_mesusu = {$iMes}          ";
    $sSql .= "                                                 and r48_instit = {$iInstituicao}  "; 
    $sSql .= "UNION ALL                                                                          ";
    $sSql .= "select 1 from gerffer where r31_anousu = {$iAno} and r31_mesusu = {$iMes}          ";
    $sSql .= "                                                 and r31_instit = {$iInstituicao}  "; 
    $sSql .= "UNION ALL                                                                          ";
    $sSql .= "select 1 from gerfs13 where r35_anousu = {$iAno} and r35_mesusu = {$iMes}          ";
    $sSql .= "                                                 and r35_instit = {$iInstituicao}  "; 
    $sSql .= "UNION ALL                                                                          ";
    $sSql .= "select 1 from gerfadi where r22_anousu = {$iAno} and r22_mesusu = {$iMes}          ";
    $sSql .= "                                                 and r22_instit = {$iInstituicao}  ";
    $sSql .= "limit 1                                                                            ";



    return $sSql;
  }

}