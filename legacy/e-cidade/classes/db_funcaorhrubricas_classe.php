<?
//MODULO: pessoal
//CLASSE DA ENTIDADE funcaorhrubricas
class cl_funcaorhrubricas { 
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
   var $rh177_sequencial = 0; 
   var $rh177_funcao = 0; 
   var $rh177_rubrica = null; 
   var $rh177_instit = 0; 
   var $rh177_quantidade = 0; 
   var $rh177_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh177_sequencial = int4 = Sequencial 
                 rh177_funcao = int4 = Função 
                 rh177_rubrica = char(4) = Rubrica 
                 rh177_instit = int4 = Instituição 
                 rh177_quantidade = float8 = Quantidade 
                 rh177_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_funcaorhrubricas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("funcaorhrubricas"); 
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
       $this->rh177_sequencial = ($this->rh177_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_sequencial"]:$this->rh177_sequencial);
       $this->rh177_funcao = ($this->rh177_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_funcao"]:$this->rh177_funcao);
       $this->rh177_rubrica = ($this->rh177_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_rubrica"]:$this->rh177_rubrica);
       $this->rh177_instit = ($this->rh177_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_instit"]:$this->rh177_instit);
       $this->rh177_quantidade = ($this->rh177_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_quantidade"]:$this->rh177_quantidade);
       $this->rh177_valor = ($this->rh177_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_valor"]:$this->rh177_valor);
     }else{
       $this->rh177_sequencial = ($this->rh177_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh177_sequencial"]:$this->rh177_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh177_sequencial){ 
      $this->atualizacampos();
     if($this->rh177_funcao == null ){ 
       $this->erro_sql = " Campo Função não informado.";
       $this->erro_campo = "rh177_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh177_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh177_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh177_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh177_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh177_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "rh177_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh177_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "rh177_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh177_sequencial == "" || $rh177_sequencial == null ){
       $result = db_query("select nextval('funcaorhrubricas_rh177_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: funcaorhrubricas_rh177_sequencial_seq do campo: rh177_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh177_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from funcaorhrubricas_rh177_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh177_sequencial)){
         $this->erro_sql = " Campo rh177_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh177_sequencial = $rh177_sequencial; 
       }
     }
     if(($this->rh177_sequencial == null) || ($this->rh177_sequencial == "") ){ 
       $this->erro_sql = " Campo rh177_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into funcaorhrubricas(
                                       rh177_sequencial 
                                      ,rh177_funcao 
                                      ,rh177_rubrica 
                                      ,rh177_instit 
                                      ,rh177_quantidade 
                                      ,rh177_valor 
                       )
                values (
                                $this->rh177_sequencial 
                               ,$this->rh177_funcao 
                               ,'$this->rh177_rubrica' 
                               ,$this->rh177_instit 
                               ,$this->rh177_quantidade 
                               ,$this->rh177_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubricas por função ($this->rh177_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubricas por função já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubricas por função ($this->rh177_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh177_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh177_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21733,'$this->rh177_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3915,21733,'','".AddSlashes(pg_result($resaco,0,'rh177_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3915,21734,'','".AddSlashes(pg_result($resaco,0,'rh177_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3915,21735,'','".AddSlashes(pg_result($resaco,0,'rh177_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3915,21736,'','".AddSlashes(pg_result($resaco,0,'rh177_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3915,21756,'','".AddSlashes(pg_result($resaco,0,'rh177_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3915,21757,'','".AddSlashes(pg_result($resaco,0,'rh177_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh177_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update funcaorhrubricas set ";
     $virgula = "";
     if(trim($this->rh177_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_sequencial"])){ 
       $sql  .= $virgula." rh177_sequencial = $this->rh177_sequencial ";
       $virgula = ",";
       if(trim($this->rh177_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh177_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh177_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_funcao"])){ 
       $sql  .= $virgula." rh177_funcao = $this->rh177_funcao ";
       $virgula = ",";
       if(trim($this->rh177_funcao) == null ){ 
         $this->erro_sql = " Campo Função não informado.";
         $this->erro_campo = "rh177_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh177_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_rubrica"])){ 
       $sql  .= $virgula." rh177_rubrica = '$this->rh177_rubrica' ";
       $virgula = ",";
       if(trim($this->rh177_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh177_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh177_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_instit"])){ 
       $sql  .= $virgula." rh177_instit = $this->rh177_instit ";
       $virgula = ",";
       if(trim($this->rh177_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh177_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh177_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_quantidade"])){ 
       $sql  .= $virgula." rh177_quantidade = $this->rh177_quantidade ";
       $virgula = ",";
       if(trim($this->rh177_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "rh177_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh177_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh177_valor"])){ 
       $sql  .= $virgula." rh177_valor = $this->rh177_valor ";
       $virgula = ",";
       if(trim($this->rh177_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "rh177_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh177_sequencial!=null){
       $sql .= " rh177_sequencial = $this->rh177_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh177_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21733,'$this->rh177_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_sequencial"]) || $this->rh177_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3915,21733,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_sequencial'))."','$this->rh177_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_funcao"]) || $this->rh177_funcao != "")
             $resac = db_query("insert into db_acount values($acount,3915,21734,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_funcao'))."','$this->rh177_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_rubrica"]) || $this->rh177_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3915,21735,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_rubrica'))."','$this->rh177_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_instit"]) || $this->rh177_instit != "")
             $resac = db_query("insert into db_acount values($acount,3915,21736,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_instit'))."','$this->rh177_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_quantidade"]) || $this->rh177_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3915,21756,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_quantidade'))."','$this->rh177_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh177_valor"]) || $this->rh177_valor != "")
             $resac = db_query("insert into db_acount values($acount,3915,21757,'".AddSlashes(pg_result($resaco,$conresaco,'rh177_valor'))."','$this->rh177_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas por função não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh177_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas por função não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh177_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh177_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh177_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh177_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21733,'$rh177_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3915,21733,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3915,21734,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3915,21735,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3915,21736,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3915,21756,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3915,21757,'','".AddSlashes(pg_result($resaco,$iresaco,'rh177_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from funcaorhrubricas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh177_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh177_sequencial = $rh177_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubricas por função não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh177_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubricas por função não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh177_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh177_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:funcaorhrubricas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh177_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from funcaorhrubricas ";
     $sql .= "      inner join db_config  on  db_config.codigo = funcaorhrubricas.rh177_instit";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = funcaorhrubricas.rh177_funcao and  rhfuncao.rh37_instit = funcaorhrubricas.rh177_instit";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = funcaorhrubricas.rh177_rubrica and  rhrubricas.rh27_instit = funcaorhrubricas.rh177_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join rhfuncaogrupo  on  rhfuncaogrupo.rh100_sequencial = rhfuncao.rh37_funcaogrupo";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh177_sequencial)) {
         $sql2 .= " where funcaorhrubricas.rh177_sequencial = $rh177_sequencial "; 
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
   public function sql_query_file ($rh177_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from funcaorhrubricas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh177_sequencial)){
         $sql2 .= " where funcaorhrubricas.rh177_sequencial = $rh177_sequencial "; 
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
