<?
//MODULO: farmacia
//CLASSE DA ENTIDADE integracaohorusenvio
class cl_integracaohorusenvio { 
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
   var $fa64_sequencial = 0; 
   var $fa64_protocolo = null; 
   var $fa64_hora = null; 
   var $fa64_data_dia = null; 
   var $fa64_data_mes = null; 
   var $fa64_data_ano = null; 
   var $fa64_data = null; 
   var $fa64_integracaohorus = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa64_sequencial = int4 = Código 
                 fa64_protocolo = text = Protocolo 
                 fa64_hora = varchar(5) = Hora 
                 fa64_data = date = Data 
                 fa64_integracaohorus = int4 = Integração Hórus 
                 ";
   //funcao construtor da classe 
   function cl_integracaohorusenvio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("integracaohorusenvio"); 
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
       $this->fa64_sequencial = ($this->fa64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_sequencial"]:$this->fa64_sequencial);
       $this->fa64_protocolo = ($this->fa64_protocolo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_protocolo"]:$this->fa64_protocolo);
       $this->fa64_hora = ($this->fa64_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_hora"]:$this->fa64_hora);
       if($this->fa64_data == ""){
         $this->fa64_data_dia = ($this->fa64_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_data_dia"]:$this->fa64_data_dia);
         $this->fa64_data_mes = ($this->fa64_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_data_mes"]:$this->fa64_data_mes);
         $this->fa64_data_ano = ($this->fa64_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_data_ano"]:$this->fa64_data_ano);
         if($this->fa64_data_dia != ""){
            $this->fa64_data = $this->fa64_data_ano."-".$this->fa64_data_mes."-".$this->fa64_data_dia;
         }
       }
       $this->fa64_integracaohorus = ($this->fa64_integracaohorus == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_integracaohorus"]:$this->fa64_integracaohorus);
     }else{
       $this->fa64_sequencial = ($this->fa64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa64_sequencial"]:$this->fa64_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($fa64_sequencial){ 
      $this->atualizacampos();
     if($this->fa64_protocolo == null ){ 
       $this->erro_sql = " Campo Protocolo não informado.";
       $this->erro_campo = "fa64_protocolo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa64_hora == null ){ 
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "fa64_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa64_data == null ){ 
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "fa64_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa64_integracaohorus == null ){ 
       $this->erro_sql = " Campo Integração Hórus não informado.";
       $this->erro_campo = "fa64_integracaohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa64_sequencial == "" || $fa64_sequencial == null ){
       $result = db_query("select nextval('integracaohorusenvio_fa64_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: integracaohorusenvio_fa64_sequencial_seq do campo: fa64_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa64_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from integracaohorusenvio_fa64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa64_sequencial)){
         $this->erro_sql = " Campo fa64_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa64_sequencial = $fa64_sequencial; 
       }
     }
     if(($this->fa64_sequencial == null) || ($this->fa64_sequencial == "") ){ 
       $this->erro_sql = " Campo fa64_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into integracaohorusenvio(
                                       fa64_sequencial 
                                      ,fa64_protocolo 
                                      ,fa64_hora 
                                      ,fa64_data 
                                      ,fa64_integracaohorus 
                       )
                values (
                                $this->fa64_sequencial 
                               ,'$this->fa64_protocolo' 
                               ,'$this->fa64_hora' 
                               ,".($this->fa64_data == "null" || $this->fa64_data == ""?"null":"'".$this->fa64_data."'")." 
                               ,$this->fa64_integracaohorus 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Integração Horus Envio ($this->fa64_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Integração Horus Envio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Integração Horus Envio ($this->fa64_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa64_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa64_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21565,'$this->fa64_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3870,21565,'','".AddSlashes(pg_result($resaco,0,'fa64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3870,21569,'','".AddSlashes(pg_result($resaco,0,'fa64_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3870,21568,'','".AddSlashes(pg_result($resaco,0,'fa64_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3870,21567,'','".AddSlashes(pg_result($resaco,0,'fa64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3870,21566,'','".AddSlashes(pg_result($resaco,0,'fa64_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($fa64_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update integracaohorusenvio set ";
     $virgula = "";
     if(trim($this->fa64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa64_sequencial"])){ 
       $sql  .= $virgula." fa64_sequencial = $this->fa64_sequencial ";
       $virgula = ",";
       if(trim($this->fa64_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa64_protocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa64_protocolo"])){ 
       $sql  .= $virgula." fa64_protocolo = '$this->fa64_protocolo' ";
       $virgula = ",";
       if(trim($this->fa64_protocolo) == null ){ 
         $this->erro_sql = " Campo Protocolo não informado.";
         $this->erro_campo = "fa64_protocolo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa64_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa64_hora"])){ 
       $sql  .= $virgula." fa64_hora = '$this->fa64_hora' ";
       $virgula = ",";
       if(trim($this->fa64_hora) == null ){ 
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "fa64_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa64_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa64_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa64_data_dia"] !="") ){ 
       $sql  .= $virgula." fa64_data = '$this->fa64_data' ";
       $virgula = ",";
       if(trim($this->fa64_data) == null ){ 
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "fa64_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa64_data_dia"])){ 
         $sql  .= $virgula." fa64_data = null ";
         $virgula = ",";
         if(trim($this->fa64_data) == null ){ 
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "fa64_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa64_integracaohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa64_integracaohorus"])){ 
       $sql  .= $virgula." fa64_integracaohorus = $this->fa64_integracaohorus ";
       $virgula = ",";
       if(trim($this->fa64_integracaohorus) == null ){ 
         $this->erro_sql = " Campo Integração Hórus não informado.";
         $this->erro_campo = "fa64_integracaohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa64_sequencial!=null){
       $sql .= " fa64_sequencial = $this->fa64_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa64_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21565,'$this->fa64_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa64_sequencial"]) || $this->fa64_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3870,21565,'".AddSlashes(pg_result($resaco,$conresaco,'fa64_sequencial'))."','$this->fa64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa64_protocolo"]) || $this->fa64_protocolo != "")
             $resac = db_query("insert into db_acount values($acount,3870,21569,'".AddSlashes(pg_result($resaco,$conresaco,'fa64_protocolo'))."','$this->fa64_protocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa64_hora"]) || $this->fa64_hora != "")
             $resac = db_query("insert into db_acount values($acount,3870,21568,'".AddSlashes(pg_result($resaco,$conresaco,'fa64_hora'))."','$this->fa64_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa64_data"]) || $this->fa64_data != "")
             $resac = db_query("insert into db_acount values($acount,3870,21567,'".AddSlashes(pg_result($resaco,$conresaco,'fa64_data'))."','$this->fa64_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa64_integracaohorus"]) || $this->fa64_integracaohorus != "")
             $resac = db_query("insert into db_acount values($acount,3870,21566,'".AddSlashes(pg_result($resaco,$conresaco,'fa64_integracaohorus'))."','$this->fa64_integracaohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integração Horus Envio não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Integração Horus Envio não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($fa64_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa64_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21565,'$fa64_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3870,21565,'','".AddSlashes(pg_result($resaco,$iresaco,'fa64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3870,21569,'','".AddSlashes(pg_result($resaco,$iresaco,'fa64_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3870,21568,'','".AddSlashes(pg_result($resaco,$iresaco,'fa64_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3870,21567,'','".AddSlashes(pg_result($resaco,$iresaco,'fa64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3870,21566,'','".AddSlashes(pg_result($resaco,$iresaco,'fa64_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from integracaohorusenvio
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa64_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa64_sequencial = $fa64_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integração Horus Envio não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Integração Horus Envio não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:integracaohorusenvio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($fa64_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from integracaohorusenvio ";
     $sql .= "      inner join integracaohorus  on  integracaohorus.fa59_codigo = integracaohorusenvio.fa64_integracaohorus";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = integracaohorus.fa59_usuario";
     $sql .= "      inner join situacaohorus  on  situacaohorus.fa60_sequencial = integracaohorus.fa59_situacaohorus";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa64_sequencial)) {
         $sql2 .= " where integracaohorusenvio.fa64_sequencial = $fa64_sequencial "; 
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
   public function sql_query_file ($fa64_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from integracaohorusenvio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa64_sequencial)){
         $sql2 .= " where integracaohorusenvio.fa64_sequencial = $fa64_sequencial "; 
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
