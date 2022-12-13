<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhfundamentacaolegal
class cl_rhfundamentacaolegal { 
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
   var $rh137_sequencial = 0; 
   var $rh137_tipodocumentacao = 0; 
   var $rh137_numero = 0; 
   var $rh137_datainicio_dia = null; 
   var $rh137_datainicio_mes = null; 
   var $rh137_datainicio_ano = null; 
   var $rh137_datainicio = null; 
   var $rh137_datafim_dia = null; 
   var $rh137_datafim_mes = null; 
   var $rh137_datafim_ano = null; 
   var $rh137_datafim = null; 
   var $rh137_descricao = null; 
   var $rh137_instituicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh137_sequencial = int4 = Código Fundamentação Legal 
                 rh137_tipodocumentacao = int4 = Tipo de documentação 
                 rh137_numero = int4 = Número 
                 rh137_datainicio = date = Data ínicio fundamentação legal 
                 rh137_datafim = date = Data fim fundamentação legal 
                 rh137_descricao = text = Descrição fundamentação legal
                 rh137_instituicao = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhfundamentacaolegal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhfundamentacaolegal"); 
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
       $this->rh137_sequencial = ($this->rh137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_sequencial"]:$this->rh137_sequencial);
       $this->rh137_tipodocumentacao = ($this->rh137_tipodocumentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_tipodocumentacao"]:$this->rh137_tipodocumentacao);
       $this->rh137_numero = ($this->rh137_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_numero"]:$this->rh137_numero);
       if($this->rh137_datainicio == ""){
         $this->rh137_datainicio_dia = ($this->rh137_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_dia"]:$this->rh137_datainicio_dia);
         $this->rh137_datainicio_mes = ($this->rh137_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_mes"]:$this->rh137_datainicio_mes);
         $this->rh137_datainicio_ano = ($this->rh137_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_ano"]:$this->rh137_datainicio_ano);
         if($this->rh137_datainicio_dia != ""){
            $this->rh137_datainicio = $this->rh137_datainicio_ano."-".$this->rh137_datainicio_mes."-".$this->rh137_datainicio_dia;
         }
       }
       if($this->rh137_datafim == ""){
         $this->rh137_datafim_dia = ($this->rh137_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datafim_dia"]:$this->rh137_datafim_dia);
         $this->rh137_datafim_mes = ($this->rh137_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datafim_mes"]:$this->rh137_datafim_mes);
         $this->rh137_datafim_ano = ($this->rh137_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_datafim_ano"]:$this->rh137_datafim_ano);
         if($this->rh137_datafim_dia != ""){
            $this->rh137_datafim = $this->rh137_datafim_ano."-".$this->rh137_datafim_mes."-".$this->rh137_datafim_dia;
         }
       }
       $this->rh137_descricao = ($this->rh137_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_descricao"]:$this->rh137_descricao);
       $this->rh137_instituicao = ($this->rh137_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_instituicao"]:$this->rh137_instituicao);
     }else{
       $this->rh137_sequencial = ($this->rh137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh137_sequencial"]:$this->rh137_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh137_sequencial){ 
      $this->atualizacampos();
     if($this->rh137_tipodocumentacao == null ){ 
       $this->erro_sql = " Campo Tipo de documentação não informado.";
       $this->erro_campo = "rh137_tipodocumentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh137_numero == null ){ 
       $this->erro_sql = " Campo Número não informado.";
       $this->erro_campo = "rh137_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh137_datainicio == null ){ 
       $this->rh137_datainicio = "null";
     }
     if($this->rh137_datafim == null ){ 
       $this->rh137_datafim = "null";
     }
     if($this->rh137_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh137_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh137_sequencial == "" || $rh137_sequencial == null ){
       $result = db_query("select nextval('rhfundamentacaolegal_rh137_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhfundamentacaolegal_rh137_sequencial_seq do campo: rh137_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh137_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhfundamentacaolegal_rh137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh137_sequencial)){
         $this->erro_sql = " Campo rh137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh137_sequencial = $rh137_sequencial; 
       }
     }
     if(($this->rh137_sequencial == null) || ($this->rh137_sequencial == "") ){ 
       $this->erro_sql = " Campo rh137_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfundamentacaolegal(
                                       rh137_sequencial 
                                      ,rh137_tipodocumentacao 
                                      ,rh137_numero 
                                      ,rh137_datainicio 
                                      ,rh137_datafim 
                                      ,rh137_descricao 
                                      ,rh137_instituicao 
                       )
                values (
                                $this->rh137_sequencial 
                               ,$this->rh137_tipodocumentacao 
                               ,$this->rh137_numero 
                               ,".($this->rh137_datainicio == "null" || $this->rh137_datainicio == ""?"null":"'".$this->rh137_datainicio."'")." 
                               ,".($this->rh137_datafim == "null" || $this->rh137_datafim == ""?"null":"'".$this->rh137_datafim."'")." 
                               ,'$this->rh137_descricao' 
                               ,$this->rh137_instituicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fundamentação Legal ($this->rh137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fundamentação Legal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fundamentação Legal ($this->rh137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         //$this->erro_sql .= "Valores : ".$this->rh137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh137_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20533,'$this->rh137_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3697,20533,'','".AddSlashes(pg_result($resaco,0,'rh137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20534,'','".AddSlashes(pg_result($resaco,0,'rh137_tipodocumentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20535,'','".AddSlashes(pg_result($resaco,0,'rh137_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20536,'','".AddSlashes(pg_result($resaco,0,'rh137_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20537,'','".AddSlashes(pg_result($resaco,0,'rh137_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20538,'','".AddSlashes(pg_result($resaco,0,'rh137_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3697,20902,'','".AddSlashes(pg_result($resaco,0,'rh137_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh137_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhfundamentacaolegal set ";
     $virgula = "";
     if(trim($this->rh137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_sequencial"])){ 
       $sql  .= $virgula." rh137_sequencial = $this->rh137_sequencial ";
       $virgula = ",";
       if(trim($this->rh137_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Fundamentação Legal não informado.";
         $this->erro_campo = "rh137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh137_tipodocumentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_tipodocumentacao"])){ 
       $sql  .= $virgula." rh137_tipodocumentacao = $this->rh137_tipodocumentacao ";
       $virgula = ",";
       if(trim($this->rh137_tipodocumentacao) == null ){ 
         $this->erro_sql = " Campo Tipo de documentação não informado.";
         $this->erro_campo = "rh137_tipodocumentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh137_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_numero"])){ 
       $sql  .= $virgula." rh137_numero = $this->rh137_numero ";
       $virgula = ",";
       if(trim($this->rh137_numero) == null ){ 
         $this->erro_sql = " Campo Número não informado.";
         $this->erro_campo = "rh137_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh137_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." rh137_datainicio = '$this->rh137_datainicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh137_datainicio_dia"])){ 
         $sql  .= $virgula." rh137_datainicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh137_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh137_datafim_dia"] !="") ){ 
       $sql  .= $virgula." rh137_datafim = '$this->rh137_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh137_datafim_dia"])){ 
         $sql  .= $virgula." rh137_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh137_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_descricao"])){ 
       $sql  .= $virgula." rh137_descricao = '$this->rh137_descricao' ";
       $virgula = ",";
     }
     if(trim($this->rh137_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh137_instituicao"])){ 
       $sql  .= $virgula." rh137_instituicao = $this->rh137_instituicao ";
       $virgula = ",";
       if(trim($this->rh137_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh137_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh137_sequencial!=null){
       $sql .= " rh137_sequencial = $this->rh137_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh137_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20533,'$this->rh137_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_sequencial"]) || $this->rh137_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3697,20533,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_sequencial'))."','$this->rh137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_tipodocumentacao"]) || $this->rh137_tipodocumentacao != "")
             $resac = db_query("insert into db_acount values($acount,3697,20534,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_tipodocumentacao'))."','$this->rh137_tipodocumentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_numero"]) || $this->rh137_numero != "")
             $resac = db_query("insert into db_acount values($acount,3697,20535,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_numero'))."','$this->rh137_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_datainicio"]) || $this->rh137_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,3697,20536,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_datainicio'))."','$this->rh137_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_datafim"]) || $this->rh137_datafim != "")
             $resac = db_query("insert into db_acount values($acount,3697,20537,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_datafim'))."','$this->rh137_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_descricao"]) || $this->rh137_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3697,20538,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_descricao'))."','$this->rh137_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh137_instituicao"]) || $this->rh137_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3697,20902,'".AddSlashes(pg_result($resaco,$conresaco,'rh137_instituicao'))."','$this->rh137_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fundamentação Legal nao Alterado. Alteracao Abortada.\\n";
         //$this->erro_sql .= "Valores : ".$this->rh137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fundamentação Legal nao foi Alterado. Alteracao Executada.\\n";
         //$this->erro_sql .= "Valores : ".$this->rh137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
      //   $this->erro_sql .= "Valores : ".$this->rh137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh137_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh137_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20533,'$rh137_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3697,20533,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20534,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_tipodocumentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20535,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20536,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20537,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20538,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3697,20902,'','".AddSlashes(pg_result($resaco,$iresaco,'rh137_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhfundamentacaolegal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh137_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh137_sequencial = $rh137_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fundamentação Legal não Excluído. Exclusão Abortada.\\n";
       //$this->erro_sql .= "Valores : ".$rh137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fundamentação Legal nao Encontrado. Exclusão não Efetuada.\\n";
         //$this->erro_sql .= "Valores : ".$rh137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         //$this->erro_sql .= "Valores : ".$rh137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfundamentacaolegal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh137_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhfundamentacaolegal ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhfundamentacaolegal.rh137_instituicao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh137_sequencial)) {
         $sql2 .= " where rhfundamentacaolegal.rh137_sequencial = $rh137_sequencial "; 
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
   public function sql_query_file ($rh137_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhfundamentacaolegal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh137_sequencial)){
         $sql2 .= " where rhfundamentacaolegal.rh137_sequencial = $rh137_sequencial "; 
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
  
  /**
   * Retorna a query que faz o vínculo entre as tabelas: rhfundamentacaolegal e rhrubricas
   * 
   * @param Integer $rh137_sequencial
   * @param String $sCampos
   * @param String $sOrdem
   * @param String $sWhere
   * @return String
   */
  public function sql_query_fundamentacao_rubrica ($rh137_sequencial = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {
    
    $sSql  = "select {$sCampos} ";
    $sSql .= "  from rhfundamentacaolegal ";
    $sSql .= "    inner join rhrubricas ";
    $sSql .= "  on rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal ";
    $sSql2 = "";
    
    if (empty($sWhere)) { 
      if (!empty($rh137_sequencial)){
        $sSql2 .= " where rhfundamentacaolegal.rh137_sequencial = $rh137_sequencial "; 
      } 
    } else if (!empty($sWhere)) {
      $sSql2 = " where $sWhere";
    }
    
    $sSql .= $sSql2;
    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem}";
    }
    
    return $sSql;
  }
  
}
