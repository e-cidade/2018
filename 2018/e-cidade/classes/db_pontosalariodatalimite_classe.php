<?
//MODULO: pessoal
//CLASSE DA ENTIDADE pontosalariodatalimite
class cl_pontosalariodatalimite { 
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
   var $rh183_sequencial = 0; 
   var $rh183_rubrica = null; 
   var $rh183_datainicio_dia = null; 
   var $rh183_datainicio_mes = null; 
   var $rh183_datainicio_ano = null; 
   var $rh183_datainicio = null; 
   var $rh183_datafim_dia = null; 
   var $rh183_datafim_mes = null; 
   var $rh183_datafim_ano = null; 
   var $rh183_datafim = null; 
   var $rh183_matricula = 0; 
   var $rh183_quantidade = 0; 
   var $rh183_instituicao = 0; 
   var $rh183_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh183_sequencial = int4 = Código 
                 rh183_rubrica = varchar(5) = Rubrica 
                 rh183_datainicio = date = Data Início 
                 rh183_datafim = date = Data Final 
                 rh183_matricula = int4 = Matrícula 
                 rh183_quantidade = int4 = Quantidade 
                 rh183_instituicao = int4 = Instituição 
                 rh183_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_pontosalariodatalimite() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontosalariodatalimite"); 
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
       $this->rh183_sequencial = ($this->rh183_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_sequencial"]:$this->rh183_sequencial);
       $this->rh183_rubrica = ($this->rh183_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_rubrica"]:$this->rh183_rubrica);
       if($this->rh183_datainicio == ""){
         $this->rh183_datainicio_dia = ($this->rh183_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_dia"]:$this->rh183_datainicio_dia);
         $this->rh183_datainicio_mes = ($this->rh183_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_mes"]:$this->rh183_datainicio_mes);
         $this->rh183_datainicio_ano = ($this->rh183_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_ano"]:$this->rh183_datainicio_ano);
         if($this->rh183_datainicio_dia != ""){
            $this->rh183_datainicio = $this->rh183_datainicio_ano."-".$this->rh183_datainicio_mes."-".$this->rh183_datainicio_dia;
         }
       }
       if($this->rh183_datafim == ""){
         $this->rh183_datafim_dia = ($this->rh183_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datafim_dia"]:$this->rh183_datafim_dia);
         $this->rh183_datafim_mes = ($this->rh183_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datafim_mes"]:$this->rh183_datafim_mes);
         $this->rh183_datafim_ano = ($this->rh183_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_datafim_ano"]:$this->rh183_datafim_ano);
         if($this->rh183_datafim_dia != ""){
            $this->rh183_datafim = $this->rh183_datafim_ano."-".$this->rh183_datafim_mes."-".$this->rh183_datafim_dia;
         }
       }
       $this->rh183_matricula = ($this->rh183_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_matricula"]:$this->rh183_matricula);
       $this->rh183_quantidade = ($this->rh183_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_quantidade"]:$this->rh183_quantidade);
       $this->rh183_instituicao = ($this->rh183_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_instituicao"]:$this->rh183_instituicao);
       $this->rh183_valor = ($this->rh183_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_valor"]:$this->rh183_valor);
     }else{
       $this->rh183_sequencial = ($this->rh183_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh183_sequencial"]:$this->rh183_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh183_sequencial){ 
      $this->atualizacampos();
     if($this->rh183_datainicio == null ){ 
       $this->erro_sql = " Campo Data Início não informado.";
       $this->erro_campo = "rh183_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh183_datafim == null ){ 
       $this->erro_sql = " Campo Data Final não informado.";
       $this->erro_campo = "rh183_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh183_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "rh183_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh183_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "rh183_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh183_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh183_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh183_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "rh183_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh183_sequencial == "" || $rh183_sequencial == null ){
       $result = db_query("select nextval('pontosalariodatalimite_rh183_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pontosalariodatalimite_rh183_sequencial_seq do campo: rh183_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh183_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pontosalariodatalimite_rh183_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh183_sequencial)){
         $this->erro_sql = " Campo rh183_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh183_sequencial = $rh183_sequencial; 
       }
     }
     if(($this->rh183_sequencial == null) || ($this->rh183_sequencial == "") ){ 
       $this->erro_sql = " Campo rh183_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontosalariodatalimite(
                                       rh183_sequencial 
                                      ,rh183_rubrica 
                                      ,rh183_datainicio 
                                      ,rh183_datafim 
                                      ,rh183_matricula 
                                      ,rh183_quantidade 
                                      ,rh183_instituicao 
                                      ,rh183_valor 
                       )
                values (
                                $this->rh183_sequencial 
                               ,'$this->rh183_rubrica' 
                               ,".($this->rh183_datainicio == "null" || $this->rh183_datainicio == ""?"null":"'".$this->rh183_datainicio."'")." 
                               ,".($this->rh183_datafim == "null" || $this->rh183_datafim == ""?"null":"'".$this->rh183_datafim."'")." 
                               ,$this->rh183_matricula 
                               ,$this->rh183_quantidade 
                               ,$this->rh183_instituicao 
                               ,$this->rh183_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pontosalariodatalimite ($this->rh183_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pontosalariodatalimite já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pontosalariodatalimite ($this->rh183_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh183_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh183_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22112,'$this->rh183_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3980,22112,'','".AddSlashes(pg_result($resaco,0,'rh183_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22095,'','".AddSlashes(pg_result($resaco,0,'rh183_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22096,'','".AddSlashes(pg_result($resaco,0,'rh183_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22097,'','".AddSlashes(pg_result($resaco,0,'rh183_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22098,'','".AddSlashes(pg_result($resaco,0,'rh183_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22110,'','".AddSlashes(pg_result($resaco,0,'rh183_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22099,'','".AddSlashes(pg_result($resaco,0,'rh183_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3980,22111,'','".AddSlashes(pg_result($resaco,0,'rh183_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh183_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pontosalariodatalimite set ";
     $virgula = "";
     if(trim($this->rh183_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_sequencial"])){ 
       $sql  .= $virgula." rh183_sequencial = $this->rh183_sequencial ";
       $virgula = ",";
       if(trim($this->rh183_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh183_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh183_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_rubrica"])){ 
       $sql  .= $virgula." rh183_rubrica = '$this->rh183_rubrica' ";
       $virgula = ",";
     }
     if(trim($this->rh183_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." rh183_datainicio = '$this->rh183_datainicio' ";
       $virgula = ",";
       if(trim($this->rh183_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Início não informado.";
         $this->erro_campo = "rh183_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh183_datainicio_dia"])){ 
         $sql  .= $virgula." rh183_datainicio = null ";
         $virgula = ",";
         if(trim($this->rh183_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Início não informado.";
           $this->erro_campo = "rh183_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh183_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh183_datafim_dia"] !="") ){ 
       $sql  .= $virgula." rh183_datafim = '$this->rh183_datafim' ";
       $virgula = ",";
       if(trim($this->rh183_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final não informado.";
         $this->erro_campo = "rh183_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh183_datafim_dia"])){ 
         $sql  .= $virgula." rh183_datafim = null ";
         $virgula = ",";
         if(trim($this->rh183_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final não informado.";
           $this->erro_campo = "rh183_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh183_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_matricula"])){ 
       $sql  .= $virgula." rh183_matricula = $this->rh183_matricula ";
       $virgula = ",";
       if(trim($this->rh183_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "rh183_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh183_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_quantidade"])){ 
       $sql  .= $virgula." rh183_quantidade = $this->rh183_quantidade ";
       $virgula = ",";
       if(trim($this->rh183_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "rh183_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh183_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_instituicao"])){ 
       $sql  .= $virgula." rh183_instituicao = $this->rh183_instituicao ";
       $virgula = ",";
       if(trim($this->rh183_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh183_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh183_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh183_valor"])){ 
       $sql  .= $virgula." rh183_valor = $this->rh183_valor ";
       $virgula = ",";
       if(trim($this->rh183_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "rh183_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh183_sequencial!=null){
       $sql .= " rh183_sequencial = $this->rh183_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh183_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22112,'$this->rh183_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_sequencial"]) || $this->rh183_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3980,22112,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_sequencial'))."','$this->rh183_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_rubrica"]) || $this->rh183_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3980,22095,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_rubrica'))."','$this->rh183_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_datainicio"]) || $this->rh183_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3980,22096,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_datainicio'))."','$this->rh183_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_datafim"]) || $this->rh183_datafim != "")
             $resac = db_query("insert into db_acount values($acount,3980,22097,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_datafim'))."','$this->rh183_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_matricula"]) || $this->rh183_matricula != "")
             $resac = db_query("insert into db_acount values($acount,3980,22098,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_matricula'))."','$this->rh183_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_quantidade"]) || $this->rh183_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3980,22110,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_quantidade'))."','$this->rh183_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_instituicao"]) || $this->rh183_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3980,22099,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_instituicao'))."','$this->rh183_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh183_valor"]) || $this->rh183_valor != "")
             $resac = db_query("insert into db_acount values($acount,3980,22111,'".AddSlashes(pg_result($resaco,$conresaco,'rh183_valor'))."','$this->rh183_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pontosalariodatalimite não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh183_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pontosalariodatalimite não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh183_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh183_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh183_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh183_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22112,'$rh183_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3980,22112,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22095,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22096,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22097,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22098,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22110,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22099,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3980,22111,'','".AddSlashes(pg_result($resaco,$iresaco,'rh183_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pontosalariodatalimite
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh183_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh183_sequencial = $rh183_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pontosalariodatalimite não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh183_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pontosalariodatalimite não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh183_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh183_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontosalariodatalimite";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh183_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pontosalariodatalimite ";
     $sql .= "      left  join rhrubricas  on  rhrubricas.rh27_rubric = pontosalariodatalimite.rh183_rubrica and  rhrubricas.rh27_instit = pontosalariodatalimite.rh183_instituicao";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh183_sequencial)) {
         $sql2 .= " where pontosalariodatalimite.rh183_sequencial = $rh183_sequencial "; 
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
   public function sql_query_file ($rh183_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pontosalariodatalimite ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh183_sequencial)){
         $sql2 .= " where pontosalariodatalimite.rh183_sequencial = $rh183_sequencial "; 
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
