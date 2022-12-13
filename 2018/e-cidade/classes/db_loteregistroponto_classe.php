<?
//MODULO: pessoal
//CLASSE DA ENTIDADE loteregistroponto
class cl_loteregistroponto { 
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
   var $rh155_sequencial = 0; 
   var $rh155_descricao = null; 
   var $rh155_ano = 0; 
   var $rh155_mes = 0; 
   var $rh155_situacao = null; 
   var $rh155_instit = 0; 
   var $rh155_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh155_sequencial = int4 =  
                 rh155_descricao = varchar(255) = Descrição 
                 rh155_ano = int4 = Competência 
                 rh155_mes = int4 = Mês da Competência 
                 rh155_situacao = char(1) = Situação 
                 rh155_instit = int4 = Instituição 
                 rh155_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_loteregistroponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("loteregistroponto"); 
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
       $this->rh155_sequencial = ($this->rh155_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_sequencial"]:$this->rh155_sequencial);
       $this->rh155_descricao = ($this->rh155_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_descricao"]:$this->rh155_descricao);
       $this->rh155_ano = ($this->rh155_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_ano"]:$this->rh155_ano);
       $this->rh155_mes = ($this->rh155_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_mes"]:$this->rh155_mes);
       $this->rh155_situacao = ($this->rh155_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_situacao"]:$this->rh155_situacao);
       $this->rh155_instit = ($this->rh155_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_instit"]:$this->rh155_instit);
       $this->rh155_usuario = ($this->rh155_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_usuario"]:$this->rh155_usuario);
     }else{
       $this->rh155_sequencial = ($this->rh155_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh155_sequencial"]:$this->rh155_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh155_sequencial){ 
      $this->atualizacampos();
     if($this->rh155_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "rh155_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh155_ano == null ){ 
       $this->erro_sql = " Campo Competência não informado.";
       $this->erro_campo = "rh155_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh155_mes == null ){ 
       $this->erro_sql = " Campo Mês da Competência não informado.";
       $this->erro_campo = "rh155_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh155_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "rh155_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh155_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh155_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh155_usuario == null ){ 
       $this->erro_sql = " Campo Usuario não informado.";
       $this->erro_campo = "rh155_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh155_sequencial == "" || $rh155_sequencial == null ){
       $result = db_query("select nextval('loteregistroponto_rh155_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: loteregistroponto_rh155_sequencial_seq do campo: rh155_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh155_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from loteregistroponto_rh155_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh155_sequencial)){
         $this->erro_sql = " Campo rh155_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh155_sequencial = $rh155_sequencial; 
       }
     }
     if(($this->rh155_sequencial == null) || ($this->rh155_sequencial == "") ){ 
       $this->erro_sql = " Campo rh155_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into loteregistroponto(
                                       rh155_sequencial 
                                      ,rh155_descricao 
                                      ,rh155_ano 
                                      ,rh155_mes 
                                      ,rh155_situacao 
                                      ,rh155_instit 
                                      ,rh155_usuario 
                       )
                values (
                                $this->rh155_sequencial 
                               ,'$this->rh155_descricao' 
                               ,$this->rh155_ano 
                               ,$this->rh155_mes 
                               ,'$this->rh155_situacao' 
                               ,$this->rh155_instit 
                               ,$this->rh155_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh155_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh155_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh155_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh155_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21089,'$this->rh155_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3798,21089,'','".AddSlashes(pg_result($resaco,0,'rh155_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21091,'','".AddSlashes(pg_result($resaco,0,'rh155_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21095,'','".AddSlashes(pg_result($resaco,0,'rh155_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21097,'','".AddSlashes(pg_result($resaco,0,'rh155_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21100,'','".AddSlashes(pg_result($resaco,0,'rh155_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21125,'','".AddSlashes(pg_result($resaco,0,'rh155_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3798,21126,'','".AddSlashes(pg_result($resaco,0,'rh155_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh155_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update loteregistroponto set ";
     $virgula = "";
     if(trim($this->rh155_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_sequencial"])){ 
       $sql  .= $virgula." rh155_sequencial = $this->rh155_sequencial ";
       $virgula = ",";
       if(trim($this->rh155_sequencial) == null ){ 
         $this->erro_sql = " Campo  não informado.";
         $this->erro_campo = "rh155_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_descricao"])){ 
       $sql  .= $virgula." rh155_descricao = '$this->rh155_descricao' ";
       $virgula = ",";
       if(trim($this->rh155_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh155_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_ano"])){ 
       $sql  .= $virgula." rh155_ano = $this->rh155_ano ";
       $virgula = ",";
       if(trim($this->rh155_ano) == null ){ 
         $this->erro_sql = " Campo Competência não informado.";
         $this->erro_campo = "rh155_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_mes"])){ 
       $sql  .= $virgula." rh155_mes = $this->rh155_mes ";
       $virgula = ",";
       if(trim($this->rh155_mes) == null ){ 
         $this->erro_sql = " Campo Mês da Competência não informado.";
         $this->erro_campo = "rh155_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_situacao"])){ 
       $sql  .= $virgula." rh155_situacao = '$this->rh155_situacao' ";
       $virgula = ",";
       if(trim($this->rh155_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "rh155_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_instit"])){ 
       $sql  .= $virgula." rh155_instit = $this->rh155_instit ";
       $virgula = ",";
       if(trim($this->rh155_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh155_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh155_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh155_usuario"])){ 
       $sql  .= $virgula." rh155_usuario = $this->rh155_usuario ";
       $virgula = ",";
       if(trim($this->rh155_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario não informado.";
         $this->erro_campo = "rh155_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh155_sequencial!=null){
       $sql .= " rh155_sequencial = $this->rh155_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh155_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21089,'$this->rh155_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_sequencial"]) || $this->rh155_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3798,21089,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_sequencial'))."','$this->rh155_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_descricao"]) || $this->rh155_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3798,21091,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_descricao'))."','$this->rh155_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_ano"]) || $this->rh155_ano != "")
             $resac = db_query("insert into db_acount values($acount,3798,21095,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_ano'))."','$this->rh155_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_mes"]) || $this->rh155_mes != "")
             $resac = db_query("insert into db_acount values($acount,3798,21097,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_mes'))."','$this->rh155_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_situacao"]) || $this->rh155_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3798,21100,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_situacao'))."','$this->rh155_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_instit"]) || $this->rh155_instit != "")
             $resac = db_query("insert into db_acount values($acount,3798,21125,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_instit'))."','$this->rh155_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh155_usuario"]) || $this->rh155_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3798,21126,'".AddSlashes(pg_result($resaco,$conresaco,'rh155_usuario'))."','$this->rh155_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh155_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh155_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh155_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21089,'$rh155_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3798,21089,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21091,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21095,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21097,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21100,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21125,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3798,21126,'','".AddSlashes(pg_result($resaco,$iresaco,'rh155_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from loteregistroponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh155_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh155_sequencial = $rh155_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh155_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh155_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:loteregistroponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh155_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from loteregistroponto ";
     $sql .= "      inner join db_config  on  db_config.codigo = loteregistroponto.rh155_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = loteregistroponto.rh155_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh155_sequencial)) {
         $sql2 .= " where loteregistroponto.rh155_sequencial = $rh155_sequencial "; 
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
   public function sql_query_file ($rh155_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from loteregistroponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh155_sequencial)){
         $sql2 .= " where loteregistroponto.rh155_sequencial = $rh155_sequencial "; 
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
