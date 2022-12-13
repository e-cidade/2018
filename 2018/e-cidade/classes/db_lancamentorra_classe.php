<?
//MODULO: pessoal
//CLASSE DA ENTIDADE lancamentorra
class cl_lancamentorra { 
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
   var $rh173_sequencial = 0; 
   var $rh173_assentamentorra = 0; 
   var $rh173_valorlancado = 0; 
   var $rh173_encargos = 0; 
   var $rh173_pensao = 0; 
   var $rh173_baseprevidencia = 0; 
   var $rh173_baseirrf = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh173_sequencial = int4 = Código 
                 rh173_assentamentorra = int4 = Código do Assentamento do RRA 
                 rh173_valorlancado = float8 = Valor da Parcela 
                 rh173_encargos = float8 = Valor dos Encargos 
                 rh173_pensao = float8 = Valor da Pensão 
                 rh173_baseprevidencia = float8 = Base Previdencia 
                 rh173_baseirrf = float8 = Base IRRF 
                 ";
   //funcao construtor da classe 
   function cl_lancamentorra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lancamentorra"); 
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
       $this->rh173_sequencial = ($this->rh173_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_sequencial"]:$this->rh173_sequencial);
       $this->rh173_assentamentorra = ($this->rh173_assentamentorra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_assentamentorra"]:$this->rh173_assentamentorra);
       $this->rh173_valorlancado = ($this->rh173_valorlancado == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_valorlancado"]:$this->rh173_valorlancado);
       $this->rh173_encargos = ($this->rh173_encargos == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_encargos"]:$this->rh173_encargos);
       $this->rh173_pensao = ($this->rh173_pensao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_pensao"]:$this->rh173_pensao);
       $this->rh173_baseprevidencia = ($this->rh173_baseprevidencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_baseprevidencia"]:$this->rh173_baseprevidencia);
       $this->rh173_baseirrf = ($this->rh173_baseirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_baseirrf"]:$this->rh173_baseirrf);
     }else{
       $this->rh173_sequencial = ($this->rh173_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh173_sequencial"]:$this->rh173_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh173_sequencial){ 
      $this->atualizacampos();
     if($this->rh173_assentamentorra == null ){ 
       $this->erro_sql = " Campo Código do Assentamento do RRA não informado.";
       $this->erro_campo = "rh173_assentamentorra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh173_valorlancado == null ){ 
       $this->erro_sql = " Campo Valor da Parcela não informado.";
       $this->erro_campo = "rh173_valorlancado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh173_encargos == null ){ 
       $this->erro_sql = " Campo Valor dos Encargos não informado.";
       $this->erro_campo = "rh173_encargos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh173_pensao == null ){ 
       $this->erro_sql = " Campo Valor da Pensão não informado.";
       $this->erro_campo = "rh173_pensao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh173_baseprevidencia == null ){ 
       $this->erro_sql = " Campo Base Previdencia não informado.";
       $this->erro_campo = "rh173_baseprevidencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh173_baseirrf == null ){ 
       $this->erro_sql = " Campo Base IRRF não informado.";
       $this->erro_campo = "rh173_baseirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh173_sequencial == "" || $rh173_sequencial == null ){
       $result = db_query("select nextval('lancamentorra_rh173_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lancamentorra_rh173_sequencial_seq do campo: rh173_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh173_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lancamentorra_rh173_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh173_sequencial)){
         $this->erro_sql = " Campo rh173_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh173_sequencial = $rh173_sequencial; 
       }
     }
     if(($this->rh173_sequencial == null) || ($this->rh173_sequencial == "") ){ 
       $this->erro_sql = " Campo rh173_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lancamentorra(
                                       rh173_sequencial 
                                      ,rh173_assentamentorra 
                                      ,rh173_valorlancado 
                                      ,rh173_encargos 
                                      ,rh173_pensao 
                                      ,rh173_baseprevidencia 
                                      ,rh173_baseirrf 
                       )
                values (
                                $this->rh173_sequencial 
                               ,$this->rh173_assentamentorra 
                               ,$this->rh173_valorlancado 
                               ,$this->rh173_encargos 
                               ,$this->rh173_pensao 
                               ,$this->rh173_baseprevidencia 
                               ,$this->rh173_baseirrf 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos do RRA ($this->rh173_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos do RRA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos do RRA ($this->rh173_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh173_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh173_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21658,'$this->rh173_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3891,21658,'','".AddSlashes(pg_result($resaco,0,'rh173_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21659,'','".AddSlashes(pg_result($resaco,0,'rh173_assentamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21660,'','".AddSlashes(pg_result($resaco,0,'rh173_valorlancado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21667,'','".AddSlashes(pg_result($resaco,0,'rh173_encargos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21668,'','".AddSlashes(pg_result($resaco,0,'rh173_pensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21669,'','".AddSlashes(pg_result($resaco,0,'rh173_baseprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3891,21685,'','".AddSlashes(pg_result($resaco,0,'rh173_baseirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh173_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update lancamentorra set ";
     $virgula = "";
     if(trim($this->rh173_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_sequencial"])){ 
       $sql  .= $virgula." rh173_sequencial = $this->rh173_sequencial ";
       $virgula = ",";
       if(trim($this->rh173_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh173_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_assentamentorra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_assentamentorra"])){ 
       $sql  .= $virgula." rh173_assentamentorra = $this->rh173_assentamentorra ";
       $virgula = ",";
       if(trim($this->rh173_assentamentorra) == null ){ 
         $this->erro_sql = " Campo Código do Assentamento do RRA não informado.";
         $this->erro_campo = "rh173_assentamentorra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_valorlancado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_valorlancado"])){ 
       $sql  .= $virgula." rh173_valorlancado = $this->rh173_valorlancado ";
       $virgula = ",";
       if(trim($this->rh173_valorlancado) == null ){ 
         $this->erro_sql = " Campo Valor da Parcela não informado.";
         $this->erro_campo = "rh173_valorlancado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_encargos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_encargos"])){ 
       $sql  .= $virgula." rh173_encargos = $this->rh173_encargos ";
       $virgula = ",";
       if(trim($this->rh173_encargos) == null ){ 
         $this->erro_sql = " Campo Valor dos Encargos não informado.";
         $this->erro_campo = "rh173_encargos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_pensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_pensao"])){ 
       $sql  .= $virgula." rh173_pensao = $this->rh173_pensao ";
       $virgula = ",";
       if(trim($this->rh173_pensao) == null ){ 
         $this->erro_sql = " Campo Valor da Pensão não informado.";
         $this->erro_campo = "rh173_pensao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_baseprevidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_baseprevidencia"])){ 
       $sql  .= $virgula." rh173_baseprevidencia = $this->rh173_baseprevidencia ";
       $virgula = ",";
       if(trim($this->rh173_baseprevidencia) == null ){ 
         $this->erro_sql = " Campo Base Previdencia não informado.";
         $this->erro_campo = "rh173_baseprevidencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh173_baseirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh173_baseirrf"])){ 
       $sql  .= $virgula." rh173_baseirrf = $this->rh173_baseirrf ";
       $virgula = ",";
       if(trim($this->rh173_baseirrf) == null ){ 
         $this->erro_sql = " Campo Base IRRF não informado.";
         $this->erro_campo = "rh173_baseirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh173_sequencial!=null){
       $sql .= " rh173_sequencial = $this->rh173_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh173_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21658,'$this->rh173_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_sequencial"]) || $this->rh173_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3891,21658,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_sequencial'))."','$this->rh173_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_assentamentorra"]) || $this->rh173_assentamentorra != "")
             $resac = db_query("insert into db_acount values($acount,3891,21659,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_assentamentorra'))."','$this->rh173_assentamentorra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_valorlancado"]) || $this->rh173_valorlancado != "")
             $resac = db_query("insert into db_acount values($acount,3891,21660,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_valorlancado'))."','$this->rh173_valorlancado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_encargos"]) || $this->rh173_encargos != "")
             $resac = db_query("insert into db_acount values($acount,3891,21667,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_encargos'))."','$this->rh173_encargos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_pensao"]) || $this->rh173_pensao != "")
             $resac = db_query("insert into db_acount values($acount,3891,21668,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_pensao'))."','$this->rh173_pensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_baseprevidencia"]) || $this->rh173_baseprevidencia != "")
             $resac = db_query("insert into db_acount values($acount,3891,21669,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_baseprevidencia'))."','$this->rh173_baseprevidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh173_baseirrf"]) || $this->rh173_baseirrf != "")
             $resac = db_query("insert into db_acount values($acount,3891,21685,'".AddSlashes(pg_result($resaco,$conresaco,'rh173_baseirrf'))."','$this->rh173_baseirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos do RRA não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh173_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos do RRA não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh173_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh173_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh173_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh173_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21658,'$rh173_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3891,21658,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21659,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_assentamentorra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21660,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_valorlancado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21667,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_encargos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21668,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_pensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21669,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_baseprevidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3891,21685,'','".AddSlashes(pg_result($resaco,$iresaco,'rh173_baseirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lancamentorra
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh173_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh173_sequencial = $rh173_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos do RRA não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh173_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos do RRA não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh173_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh173_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:lancamentorra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh173_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from lancamentorra ";
     $sql .= "      inner join assentamentorra  on  assentamentorra.h83_assenta = lancamentorra.rh173_assentamentorra";
     $sql .= "      inner join assenta  on  assenta.h16_codigo = assentamentorra.h83_assenta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh173_sequencial)) {
         $sql2 .= " where lancamentorra.rh173_sequencial = $rh173_sequencial "; 
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
   public function sql_query_file ($rh173_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lancamentorra ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh173_sequencial)){
         $sql2 .= " where lancamentorra.rh173_sequencial = $rh173_sequencial "; 
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
