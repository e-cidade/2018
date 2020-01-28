<?
//MODULO: pessoal
//CLASSE DA ENTIDADE tipoassefinanceiro
class cl_tipoassefinanceiro { 
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
   var $rh165_sequencial = 0; 
   var $rh165_tipoasse = 0; 
   var $rh165_rubric = null; 
   var $rh165_instit = 0; 
   var $rh165_db_formulas = 0; 
   var $rh165_tipolancamento = 0; 
   var $rh165_mesusu = 0; 
   var $rh165_anousu = 0; 
   var $rh165_datainicio_dia = null; 
   var $rh165_datainicio_mes = null; 
   var $rh165_datainicio_ano = null; 
   var $rh165_datainicio = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh165_sequencial = int4 = Sequencial 
                 rh165_tipoasse = int4 = Tipo de Assentamento 
                 rh165_rubric = char(4) = Rubrica 
                 rh165_instit = int4 = Instituição 
                 rh165_db_formulas = int4 = Fórmula 
                 rh165_tipolancamento = int4 = Tipo de Lançamento 
                 rh165_mesusu = int4 = Mês 
                 rh165_anousu = int4 = Ano 
                 rh165_datainicio = date = Data de Início 
                 ";
   //funcao construtor da classe 
   function cl_tipoassefinanceiro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoassefinanceiro"); 
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
       $this->rh165_sequencial = ($this->rh165_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_sequencial"]:$this->rh165_sequencial);
       $this->rh165_tipoasse = ($this->rh165_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_tipoasse"]:$this->rh165_tipoasse);
       $this->rh165_rubric = ($this->rh165_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_rubric"]:$this->rh165_rubric);
       $this->rh165_instit = ($this->rh165_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_instit"]:$this->rh165_instit);
       $this->rh165_db_formulas = ($this->rh165_db_formulas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_db_formulas"]:$this->rh165_db_formulas);
       $this->rh165_tipolancamento = ($this->rh165_tipolancamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_tipolancamento"]:$this->rh165_tipolancamento);
       $this->rh165_mesusu = ($this->rh165_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_mesusu"]:$this->rh165_mesusu);
       $this->rh165_anousu = ($this->rh165_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_anousu"]:$this->rh165_anousu);
       if($this->rh165_datainicio == ""){
         $this->rh165_datainicio_dia = ($this->rh165_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_dia"]:$this->rh165_datainicio_dia);
         $this->rh165_datainicio_mes = ($this->rh165_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_mes"]:$this->rh165_datainicio_mes);
         $this->rh165_datainicio_ano = ($this->rh165_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_ano"]:$this->rh165_datainicio_ano);
         if($this->rh165_datainicio_dia != ""){
            $this->rh165_datainicio = $this->rh165_datainicio_ano."-".$this->rh165_datainicio_mes."-".$this->rh165_datainicio_dia;
         }
       }
     }else{
       $this->rh165_sequencial = ($this->rh165_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh165_sequencial"]:$this->rh165_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh165_sequencial){ 
      $this->atualizacampos();
     if($this->rh165_tipoasse == null ){ 
       $this->erro_sql = " Campo Tipo de Assentamento não informado.";
       $this->erro_campo = "rh165_tipoasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh165_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh165_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_db_formulas == null ){ 
       $this->erro_sql = " Campo Fórmula não informado.";
       $this->erro_campo = "rh165_db_formulas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_tipolancamento == null ){ 
       $this->erro_sql = " Campo Tipo de Lançamento não informado.";
       $this->erro_campo = "rh165_tipolancamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_mesusu == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh165_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh165_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh165_datainicio == null ){ 
       $this->rh165_datainicio = "null";
     }
     if($rh165_sequencial == "" || $rh165_sequencial == null ){
       $result = db_query("select nextval('tipoassefinanceiro_rh165_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoassefinanceiro_rh165_sequencial_seq do campo: rh165_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh165_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoassefinanceiro_rh165_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh165_sequencial)){
         $this->erro_sql = " Campo rh165_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh165_sequencial = $rh165_sequencial; 
       }
     }
     if(($this->rh165_sequencial == null) || ($this->rh165_sequencial == "") ){ 
       $this->erro_sql = " Campo rh165_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoassefinanceiro(
                                       rh165_sequencial 
                                      ,rh165_tipoasse 
                                      ,rh165_rubric 
                                      ,rh165_instit 
                                      ,rh165_db_formulas 
                                      ,rh165_tipolancamento 
                                      ,rh165_mesusu 
                                      ,rh165_anousu 
                                      ,rh165_datainicio 
                       )
                values (
                                $this->rh165_sequencial 
                               ,$this->rh165_tipoasse 
                               ,'$this->rh165_rubric' 
                               ,$this->rh165_instit 
                               ,$this->rh165_db_formulas 
                               ,$this->rh165_tipolancamento 
                               ,$this->rh165_mesusu 
                               ,$this->rh165_anousu 
                               ,".($this->rh165_datainicio == "null" || $this->rh165_datainicio == ""?"null":"'".$this->rh165_datainicio."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cofigurações de Pagamento por Tipo de Assentamento ($this->rh165_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cofigurações de Pagamento por Tipo de Assentamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cofigurações de Pagamento por Tipo de Assentamento ($this->rh165_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh165_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh165_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21222,'$this->rh165_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3821,21222,'','".AddSlashes(pg_result($resaco,0,'rh165_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21217,'','".AddSlashes(pg_result($resaco,0,'rh165_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21218,'','".AddSlashes(pg_result($resaco,0,'rh165_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21219,'','".AddSlashes(pg_result($resaco,0,'rh165_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21220,'','".AddSlashes(pg_result($resaco,0,'rh165_db_formulas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21221,'','".AddSlashes(pg_result($resaco,0,'rh165_tipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21259,'','".AddSlashes(pg_result($resaco,0,'rh165_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21258,'','".AddSlashes(pg_result($resaco,0,'rh165_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3821,21323,'','".AddSlashes(pg_result($resaco,0,'rh165_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh165_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tipoassefinanceiro set ";
     $virgula = "";
     if(trim($this->rh165_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_sequencial"])){ 
       $sql  .= $virgula." rh165_sequencial = $this->rh165_sequencial ";
       $virgula = ",";
       if(trim($this->rh165_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh165_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_tipoasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_tipoasse"])){ 
       $sql  .= $virgula." rh165_tipoasse = $this->rh165_tipoasse ";
       $virgula = ",";
       if(trim($this->rh165_tipoasse) == null ){ 
         $this->erro_sql = " Campo Tipo de Assentamento não informado.";
         $this->erro_campo = "rh165_tipoasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_rubric"])){ 
       $sql  .= $virgula." rh165_rubric = '$this->rh165_rubric' ";
       $virgula = ",";
       if(trim($this->rh165_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh165_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_instit"])){ 
       $sql  .= $virgula." rh165_instit = $this->rh165_instit ";
       $virgula = ",";
       if(trim($this->rh165_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh165_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_db_formulas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_db_formulas"])){ 
       $sql  .= $virgula." rh165_db_formulas = $this->rh165_db_formulas ";
       $virgula = ",";
       if(trim($this->rh165_db_formulas) == null ){ 
         $this->erro_sql = " Campo Fórmula não informado.";
         $this->erro_campo = "rh165_db_formulas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_tipolancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_tipolancamento"])){ 
       $sql  .= $virgula." rh165_tipolancamento = $this->rh165_tipolancamento ";
       $virgula = ",";
       if(trim($this->rh165_tipolancamento) == null ){ 
         $this->erro_sql = " Campo Tipo de Lançamento não informado.";
         $this->erro_campo = "rh165_tipolancamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_mesusu"])){ 
       $sql  .= $virgula." rh165_mesusu = $this->rh165_mesusu ";
       $virgula = ",";
       if(trim($this->rh165_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh165_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_anousu"])){ 
       $sql  .= $virgula." rh165_anousu = $this->rh165_anousu ";
       $virgula = ",";
       if(trim($this->rh165_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh165_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh165_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." rh165_datainicio = '$this->rh165_datainicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh165_datainicio_dia"])){ 
         $sql  .= $virgula." rh165_datainicio = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($rh165_sequencial!=null){
       $sql .= " rh165_sequencial = $this->rh165_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh165_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21222,'$this->rh165_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_sequencial"]) || $this->rh165_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3821,21222,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_sequencial'))."','$this->rh165_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_tipoasse"]) || $this->rh165_tipoasse != "")
             $resac = db_query("insert into db_acount values($acount,3821,21217,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_tipoasse'))."','$this->rh165_tipoasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_rubric"]) || $this->rh165_rubric != "")
             $resac = db_query("insert into db_acount values($acount,3821,21218,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_rubric'))."','$this->rh165_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_instit"]) || $this->rh165_instit != "")
             $resac = db_query("insert into db_acount values($acount,3821,21219,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_instit'))."','$this->rh165_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_db_formulas"]) || $this->rh165_db_formulas != "")
             $resac = db_query("insert into db_acount values($acount,3821,21220,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_db_formulas'))."','$this->rh165_db_formulas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_tipolancamento"]) || $this->rh165_tipolancamento != "")
             $resac = db_query("insert into db_acount values($acount,3821,21221,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_tipolancamento'))."','$this->rh165_tipolancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_mesusu"]) || $this->rh165_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3821,21259,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_mesusu'))."','$this->rh165_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_anousu"]) || $this->rh165_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3821,21258,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_anousu'))."','$this->rh165_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh165_datainicio"]) || $this->rh165_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3821,21323,'".AddSlashes(pg_result($resaco,$conresaco,'rh165_datainicio'))."','$this->rh165_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cofigurações de Pagamento por Tipo de Assentamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh165_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cofigurações de Pagamento por Tipo de Assentamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh165_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh165_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh165_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh165_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21222,'$rh165_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3821,21222,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21217,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21218,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21219,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21220,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_db_formulas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21221,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_tipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21259,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21258,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3821,21323,'','".AddSlashes(pg_result($resaco,$iresaco,'rh165_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tipoassefinanceiro
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh165_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh165_sequencial = $rh165_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cofigurações de Pagamento por Tipo de Assentamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh165_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cofigurações de Pagamento por Tipo de Assentamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh165_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh165_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoassefinanceiro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh165_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from tipoassefinanceiro ";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = tipoassefinanceiro.rh165_tipoasse";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = tipoassefinanceiro.rh165_rubric and  rhrubricas.rh27_instit = tipoassefinanceiro.rh165_instit";
     $sql .= "      inner join db_formulas  on  db_formulas.db148_sequencial = tipoassefinanceiro.rh165_db_formulas";
     $sql .= "      inner join naturezatipoassentamento  on  naturezatipoassentamento.rh159_sequencial = tipoasse.h12_natureza";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh165_sequencial)) {
         $sql2 .= " where tipoassefinanceiro.rh165_sequencial = $rh165_sequencial "; 
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
   public function sql_query_file ($rh165_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from tipoassefinanceiro ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh165_sequencial)){
         $sql2 .= " where tipoassefinanceiro.rh165_sequencial = $rh165_sequencial "; 
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
