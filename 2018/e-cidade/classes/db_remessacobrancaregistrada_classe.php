<?
//MODULO: caixa
//CLASSE DA ENTIDADE remessacobrancaregistrada
class cl_remessacobrancaregistrada { 
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
   var $k147_sequencial = 0; 
   var $k147_instit = 0; 
   var $k147_convenio = 0; 
   var $k147_sequencialremessa = 0; 
   var $k147_dataemissao_dia = null; 
   var $k147_dataemissao_mes = null; 
   var $k147_dataemissao_ano = null; 
   var $k147_dataemissao = null; 
   var $k147_horaemissao = null; 
   var $k147_arquivoremessa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k147_sequencial = int4 = Sequencial 
                 k147_instit = int4 = Intituição 
                 k147_convenio = int4 = Convênio 
                 k147_sequencialremessa = int4 = Sequencial Remessa 
                 k147_dataemissao = date = Data de Emissão 
                 k147_horaemissao = char(5) = Hora da Emissão 
                 k147_arquivoremessa = oid = Arquivo da Remessa 
                 ";
   //funcao construtor da classe 
   function cl_remessacobrancaregistrada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("remessacobrancaregistrada"); 
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
       $this->k147_sequencial = ($this->k147_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_sequencial"]:$this->k147_sequencial);
       $this->k147_instit = ($this->k147_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_instit"]:$this->k147_instit);
       $this->k147_convenio = ($this->k147_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_convenio"]:$this->k147_convenio);
       $this->k147_sequencialremessa = ($this->k147_sequencialremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_sequencialremessa"]:$this->k147_sequencialremessa);
       if($this->k147_dataemissao == ""){
         $this->k147_dataemissao_dia = ($this->k147_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_dia"]:$this->k147_dataemissao_dia);
         $this->k147_dataemissao_mes = ($this->k147_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_mes"]:$this->k147_dataemissao_mes);
         $this->k147_dataemissao_ano = ($this->k147_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_ano"]:$this->k147_dataemissao_ano);
         if($this->k147_dataemissao_dia != ""){
            $this->k147_dataemissao = $this->k147_dataemissao_ano."-".$this->k147_dataemissao_mes."-".$this->k147_dataemissao_dia;
         }
       }
       $this->k147_horaemissao = ($this->k147_horaemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_horaemissao"]:$this->k147_horaemissao);
       $this->k147_arquivoremessa = ($this->k147_arquivoremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_arquivoremessa"]:$this->k147_arquivoremessa);
     }else{
       $this->k147_sequencial = ($this->k147_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k147_sequencial"]:$this->k147_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($k147_sequencial){ 
      $this->atualizacampos();
     if($this->k147_instit == null ){ 
       $this->erro_sql = " Campo Intituição não informado.";
       $this->erro_campo = "k147_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k147_convenio == null ){ 
       $this->erro_sql = " Campo Convênio não informado.";
       $this->erro_campo = "k147_convenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k147_sequencialremessa == null ){ 
       $this->erro_sql = " Campo Sequencial Remessa não informado.";
       $this->erro_campo = "k147_sequencialremessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k147_dataemissao == null ){ 
       $this->erro_sql = " Campo Data de Emissão não informado.";
       $this->erro_campo = "k147_dataemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k147_horaemissao == null ){ 
       $this->erro_sql = " Campo Hora da Emissão não informado.";
       $this->erro_campo = "k147_horaemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k147_sequencial == "" || $k147_sequencial == null ){
       $result = db_query("select nextval('remessacobrancaregistrada_k147_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: remessacobrancaregistrada_k147_sequencial_seq do campo: k147_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k147_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from remessacobrancaregistrada_k147_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k147_sequencial)){
         $this->erro_sql = " Campo k147_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k147_sequencial = $k147_sequencial; 
       }
     }
     if(($this->k147_sequencial == null) || ($this->k147_sequencial == "") ){ 
       $this->erro_sql = " Campo k147_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into remessacobrancaregistrada(
                                       k147_sequencial 
                                      ,k147_instit 
                                      ,k147_convenio 
                                      ,k147_sequencialremessa 
                                      ,k147_dataemissao 
                                      ,k147_horaemissao 
                                      ,k147_arquivoremessa 
                       )
                values (
                                $this->k147_sequencial 
                               ,$this->k147_instit 
                               ,$this->k147_convenio 
                               ,$this->k147_sequencialremessa 
                               ,".($this->k147_dataemissao == "null" || $this->k147_dataemissao == ""?"null":"'".$this->k147_dataemissao."'")." 
                               ,'$this->k147_horaemissao' 
                               ,$this->k147_arquivoremessa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "RemessaCobrancaRegistrada ($this->k147_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "RemessaCobrancaRegistrada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "RemessaCobrancaRegistrada ($this->k147_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k147_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k147_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22100,'$this->k147_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3981,22100,'','".AddSlashes(pg_result($resaco,0,'k147_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22101,'','".AddSlashes(pg_result($resaco,0,'k147_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22102,'','".AddSlashes(pg_result($resaco,0,'k147_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22103,'','".AddSlashes(pg_result($resaco,0,'k147_sequencialremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22104,'','".AddSlashes(pg_result($resaco,0,'k147_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22105,'','".AddSlashes(pg_result($resaco,0,'k147_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3981,22306,'','".AddSlashes(pg_result($resaco,0,'k147_arquivoremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($k147_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update remessacobrancaregistrada set ";
     $virgula = "";
     if(trim($this->k147_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_sequencial"])){ 
       $sql  .= $virgula." k147_sequencial = $this->k147_sequencial ";
       $virgula = ",";
       if(trim($this->k147_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k147_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k147_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_instit"])){ 
       $sql  .= $virgula." k147_instit = $this->k147_instit ";
       $virgula = ",";
       if(trim($this->k147_instit) == null ){ 
         $this->erro_sql = " Campo Intituição não informado.";
         $this->erro_campo = "k147_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k147_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_convenio"])){ 
       $sql  .= $virgula." k147_convenio = $this->k147_convenio ";
       $virgula = ",";
       if(trim($this->k147_convenio) == null ){ 
         $this->erro_sql = " Campo Convênio não informado.";
         $this->erro_campo = "k147_convenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k147_sequencialremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_sequencialremessa"])){ 
       $sql  .= $virgula." k147_sequencialremessa = $this->k147_sequencialremessa ";
       $virgula = ",";
       if(trim($this->k147_sequencialremessa) == null ){ 
         $this->erro_sql = " Campo Sequencial Remessa não informado.";
         $this->erro_campo = "k147_sequencialremessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k147_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_dia"] !="") ){ 
       $sql  .= $virgula." k147_dataemissao = '$this->k147_dataemissao' ";
       $virgula = ",";
       if(trim($this->k147_dataemissao) == null ){ 
         $this->erro_sql = " Campo Data de Emissão não informado.";
         $this->erro_campo = "k147_dataemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k147_dataemissao_dia"])){ 
         $sql  .= $virgula." k147_dataemissao = null ";
         $virgula = ",";
         if(trim($this->k147_dataemissao) == null ){ 
           $this->erro_sql = " Campo Data de Emissão não informado.";
           $this->erro_campo = "k147_dataemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k147_horaemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_horaemissao"])){ 
       $sql  .= $virgula." k147_horaemissao = '$this->k147_horaemissao' ";
       $virgula = ",";
       if(trim($this->k147_horaemissao) == null ){ 
         $this->erro_sql = " Campo Hora da Emissão não informado.";
         $this->erro_campo = "k147_horaemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k147_arquivoremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k147_arquivoremessa"])){ 
       $sql  .= $virgula." k147_arquivoremessa = $this->k147_arquivoremessa ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k147_sequencial!=null){
       $sql .= " k147_sequencial = $this->k147_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k147_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22100,'$this->k147_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_sequencial"]) || $this->k147_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3981,22100,'".AddSlashes(pg_result($resaco,$conresaco,'k147_sequencial'))."','$this->k147_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_instit"]) || $this->k147_instit != "")
             $resac = db_query("insert into db_acount values($acount,3981,22101,'".AddSlashes(pg_result($resaco,$conresaco,'k147_instit'))."','$this->k147_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_convenio"]) || $this->k147_convenio != "")
             $resac = db_query("insert into db_acount values($acount,3981,22102,'".AddSlashes(pg_result($resaco,$conresaco,'k147_convenio'))."','$this->k147_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_sequencialremessa"]) || $this->k147_sequencialremessa != "")
             $resac = db_query("insert into db_acount values($acount,3981,22103,'".AddSlashes(pg_result($resaco,$conresaco,'k147_sequencialremessa'))."','$this->k147_sequencialremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_dataemissao"]) || $this->k147_dataemissao != "")
             $resac = db_query("insert into db_acount values($acount,3981,22104,'".AddSlashes(pg_result($resaco,$conresaco,'k147_dataemissao'))."','$this->k147_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_horaemissao"]) || $this->k147_horaemissao != "")
             $resac = db_query("insert into db_acount values($acount,3981,22105,'".AddSlashes(pg_result($resaco,$conresaco,'k147_horaemissao'))."','$this->k147_horaemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k147_arquivoremessa"]) || $this->k147_arquivoremessa != "")
             $resac = db_query("insert into db_acount values($acount,3981,22306,'".AddSlashes(pg_result($resaco,$conresaco,'k147_arquivoremessa'))."','$this->k147_arquivoremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "RemessaCobrancaRegistrada não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k147_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "RemessaCobrancaRegistrada não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k147_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k147_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($k147_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k147_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22100,'$k147_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3981,22100,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22101,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22102,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22103,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_sequencialremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22104,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22105,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3981,22306,'','".AddSlashes(pg_result($resaco,$iresaco,'k147_arquivoremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from remessacobrancaregistrada
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k147_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k147_sequencial = $k147_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "RemessaCobrancaRegistrada não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k147_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "RemessaCobrancaRegistrada não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k147_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k147_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:remessacobrancaregistrada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($k147_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from remessacobrancaregistrada ";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = remessacobrancaregistrada.k147_convenio";
     $sql .= "      inner join db_config  on  db_config.codigo = cadconvenio.ar11_instit";
     $sql .= "      inner join cadtipoconvenio  on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k147_sequencial)) {
         $sql2 .= " where remessacobrancaregistrada.k147_sequencial = $k147_sequencial "; 
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
   public function sql_query_file ($k147_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from remessacobrancaregistrada ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k147_sequencial)){
         $sql2 .= " where remessacobrancaregistrada.k147_sequencial = $k147_sequencial "; 
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
