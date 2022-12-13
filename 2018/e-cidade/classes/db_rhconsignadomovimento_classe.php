<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhconsignadomovimento
class cl_rhconsignadomovimento { 
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
   var $rh151_sequencial = 0; 
   var $rh151_ano = 0; 
   var $rh151_mes = 0; 
   var $rh151_nomearquivo = null; 
   var $rh151_instit = 0; 
   var $rh151_relatorio = 0; 
   var $rh151_processado = 'f'; 
   var $rh151_arquivo = 0; 
   var $rh151_banco = null; 
   var $rh151_tipoconsignado = null; 
   var $rh151_consignadoorigem = 0; 
   var $rh151_situacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh151_sequencial = int4 =  
                 rh151_ano = int4 = Ano 
                 rh151_mes = int4 = Mês 
                 rh151_nomearquivo = varchar(100) = Nome do arquivo 
                 rh151_instit = int4 = Instituição 
                 rh151_relatorio = oid = Relatório 
                 rh151_processado = bool = Processado 
                 rh151_arquivo = oid = Conteudo do Arquivo 
                 rh151_banco = varchar(10) = Banco 
                 rh151_tipoconsignado = char(1) = Tipo do Consignado 
                 rh151_consignadoorigem = int4 = Código do consignado de origem 
                 rh151_situacao = char(1) = Situação do consignado 
                 ";
   //funcao construtor da classe 
   function cl_rhconsignadomovimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhconsignadomovimento"); 
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
       $this->rh151_sequencial = ($this->rh151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_sequencial"]:$this->rh151_sequencial);
       $this->rh151_ano = ($this->rh151_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_ano"]:$this->rh151_ano);
       $this->rh151_mes = ($this->rh151_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_mes"]:$this->rh151_mes);
       $this->rh151_nomearquivo = ($this->rh151_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_nomearquivo"]:$this->rh151_nomearquivo);
       $this->rh151_instit = ($this->rh151_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_instit"]:$this->rh151_instit);
       $this->rh151_relatorio = ($this->rh151_relatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_relatorio"]:$this->rh151_relatorio);
       $this->rh151_processado = ($this->rh151_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh151_processado"]:$this->rh151_processado);
       $this->rh151_arquivo = ($this->rh151_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_arquivo"]:$this->rh151_arquivo);
       $this->rh151_banco = ($this->rh151_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_banco"]:$this->rh151_banco);
       $this->rh151_tipoconsignado = ($this->rh151_tipoconsignado == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_tipoconsignado"]:$this->rh151_tipoconsignado);
       $this->rh151_consignadoorigem = ($this->rh151_consignadoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_consignadoorigem"]:$this->rh151_consignadoorigem);
       $this->rh151_situacao = ($this->rh151_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_situacao"]:$this->rh151_situacao);
     }else{
       $this->rh151_sequencial = ($this->rh151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh151_sequencial"]:$this->rh151_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh151_sequencial){ 
      $this->atualizacampos();
     if($this->rh151_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh151_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh151_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "rh151_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh151_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do arquivo não informado.";
       $this->erro_campo = "rh151_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh151_instit == null ){ 
       $this->rh151_instit = "0";
     }
     if($this->rh151_processado == null ){ 
       $this->erro_sql = " Campo Processado não informado.";
       $this->erro_campo = "rh151_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh151_consignadoorigem == null ){ 
       $this->rh151_consignadoorigem = "0";
     }
     if($rh151_sequencial == "" || $rh151_sequencial == null ){
       $result = db_query("select nextval('rhconsignadomovimento_rh151_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhconsignadomovimento_rh151_sequencial_seq do campo: rh151_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh151_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhconsignadomovimento_rh151_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh151_sequencial)){
         $this->erro_sql = " Campo rh151_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh151_sequencial = $rh151_sequencial; 
       }
     }
     if(($this->rh151_sequencial == null) || ($this->rh151_sequencial == "") ){ 
       $this->erro_sql = " Campo rh151_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhconsignadomovimento(
                                       rh151_sequencial 
                                      ,rh151_ano 
                                      ,rh151_mes 
                                      ,rh151_nomearquivo 
                                      ,rh151_instit 
                                      ,rh151_relatorio 
                                      ,rh151_processado 
                                      ,rh151_arquivo 
                                      ,rh151_banco 
                                      ,rh151_tipoconsignado 
                                      ,rh151_consignadoorigem 
                                      ,rh151_situacao 
                       )
                values (
                                $this->rh151_sequencial 
                               ,$this->rh151_ano 
                               ,$this->rh151_mes 
                               ,'$this->rh151_nomearquivo' 
                               ,$this->rh151_instit 
                               ,$this->rh151_relatorio 
                               ,'$this->rh151_processado' 
                               ,$this->rh151_arquivo 
                               ,'$this->rh151_banco' 
                               ,'$this->rh151_tipoconsignado' 
                               ,$this->rh151_consignadoorigem 
                               ,'$this->rh151_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->rh151_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->rh151_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh151_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh151_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21005,'$this->rh151_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3785,21005,'','".AddSlashes(pg_result($resaco,0,'rh151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21006,'','".AddSlashes(pg_result($resaco,0,'rh151_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21007,'','".AddSlashes(pg_result($resaco,0,'rh151_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21008,'','".AddSlashes(pg_result($resaco,0,'rh151_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21009,'','".AddSlashes(pg_result($resaco,0,'rh151_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21010,'','".AddSlashes(pg_result($resaco,0,'rh151_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21011,'','".AddSlashes(pg_result($resaco,0,'rh151_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21869,'','".AddSlashes(pg_result($resaco,0,'rh151_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21870,'','".AddSlashes(pg_result($resaco,0,'rh151_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21984,'','".AddSlashes(pg_result($resaco,0,'rh151_tipoconsignado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21985,'','".AddSlashes(pg_result($resaco,0,'rh151_consignadoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3785,21986,'','".AddSlashes(pg_result($resaco,0,'rh151_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh151_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhconsignadomovimento set ";
     $virgula = "";
     if(trim($this->rh151_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_sequencial"])){ 
       $sql  .= $virgula." rh151_sequencial = $this->rh151_sequencial ";
       $virgula = ",";
       if(trim($this->rh151_sequencial) == null ){ 
         $this->erro_sql = " Campo  não informado.";
         $this->erro_campo = "rh151_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh151_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_ano"])){ 
       $sql  .= $virgula." rh151_ano = $this->rh151_ano ";
       $virgula = ",";
       if(trim($this->rh151_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh151_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh151_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_mes"])){ 
       $sql  .= $virgula." rh151_mes = $this->rh151_mes ";
       $virgula = ",";
       if(trim($this->rh151_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "rh151_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh151_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_nomearquivo"])){ 
       $sql  .= $virgula." rh151_nomearquivo = '$this->rh151_nomearquivo' ";
       $virgula = ",";
       if(trim($this->rh151_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo não informado.";
         $this->erro_campo = "rh151_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh151_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_instit"])){ 
        if(trim($this->rh151_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh151_instit"])){ 
           $this->rh151_instit = "0" ; 
        } 
       $sql  .= $virgula." rh151_instit = $this->rh151_instit ";
       $virgula = ",";
     }
     if(trim($this->rh151_relatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_relatorio"])){ 
       $sql  .= $virgula." rh151_relatorio = $this->rh151_relatorio ";
       $virgula = ",";
     }
     if(trim($this->rh151_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_processado"])){ 
       $sql  .= $virgula." rh151_processado = '$this->rh151_processado' ";
       $virgula = ",";
       if(trim($this->rh151_processado) == null ){ 
         $this->erro_sql = " Campo Processado não informado.";
         $this->erro_campo = "rh151_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh151_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_arquivo"])){ 
       $sql  .= $virgula." rh151_arquivo = $this->rh151_arquivo ";
       $virgula = ",";
     }
     if(trim($this->rh151_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_banco"])){ 
       $sql  .= $virgula." rh151_banco = '$this->rh151_banco' ";
       $virgula = ",";
     }
     if(trim($this->rh151_tipoconsignado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_tipoconsignado"])){ 
       $sql  .= $virgula." rh151_tipoconsignado = '$this->rh151_tipoconsignado' ";
       $virgula = ",";
     }
     if(trim($this->rh151_consignadoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_consignadoorigem"])){ 
        if(trim($this->rh151_consignadoorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh151_consignadoorigem"])){ 
           $this->rh151_consignadoorigem = "0" ; 
        } 
       $sql  .= $virgula." rh151_consignadoorigem = $this->rh151_consignadoorigem ";
       $virgula = ",";
     }
     if(trim($this->rh151_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh151_situacao"])){ 
       $sql  .= $virgula." rh151_situacao = '$this->rh151_situacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh151_sequencial!=null){
       $sql .= " rh151_sequencial = $this->rh151_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh151_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21005,'$this->rh151_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_sequencial"]) || $this->rh151_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3785,21005,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_sequencial'))."','$this->rh151_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_ano"]) || $this->rh151_ano != "")
             $resac = db_query("insert into db_acount values($acount,3785,21006,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_ano'))."','$this->rh151_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_mes"]) || $this->rh151_mes != "")
             $resac = db_query("insert into db_acount values($acount,3785,21007,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_mes'))."','$this->rh151_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_nomearquivo"]) || $this->rh151_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3785,21008,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_nomearquivo'))."','$this->rh151_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_instit"]) || $this->rh151_instit != "")
             $resac = db_query("insert into db_acount values($acount,3785,21009,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_instit'))."','$this->rh151_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_relatorio"]) || $this->rh151_relatorio != "")
             $resac = db_query("insert into db_acount values($acount,3785,21010,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_relatorio'))."','$this->rh151_relatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_processado"]) || $this->rh151_processado != "")
             $resac = db_query("insert into db_acount values($acount,3785,21011,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_processado'))."','$this->rh151_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_arquivo"]) || $this->rh151_arquivo != "")
             $resac = db_query("insert into db_acount values($acount,3785,21869,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_arquivo'))."','$this->rh151_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_banco"]) || $this->rh151_banco != "")
             $resac = db_query("insert into db_acount values($acount,3785,21870,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_banco'))."','$this->rh151_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_tipoconsignado"]) || $this->rh151_tipoconsignado != "")
             $resac = db_query("insert into db_acount values($acount,3785,21984,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_tipoconsignado'))."','$this->rh151_tipoconsignado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_consignadoorigem"]) || $this->rh151_consignadoorigem != "")
             $resac = db_query("insert into db_acount values($acount,3785,21985,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_consignadoorigem'))."','$this->rh151_consignadoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh151_situacao"]) || $this->rh151_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3785,21986,'".AddSlashes(pg_result($resaco,$conresaco,'rh151_situacao'))."','$this->rh151_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh151_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh151_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21005,'$rh151_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3785,21005,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21006,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21007,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21008,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21009,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21010,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21011,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21869,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21870,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21984,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_tipoconsignado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21985,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_consignadoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3785,21986,'','".AddSlashes(pg_result($resaco,$iresaco,'rh151_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhconsignadomovimento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh151_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh151_sequencial = $rh151_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh151_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhconsignadomovimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh151_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimento ";
     $sql .= "      left  join db_config  on  db_config.codigo = rhconsignadomovimento.rh151_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh151_sequencial)) {
         $sql2 .= " where rhconsignadomovimento.rh151_sequencial = $rh151_sequencial "; 
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
   public function sql_query_file ($rh151_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhconsignadomovimento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh151_sequencial)){
         $sql2 .= " where rhconsignadomovimento.rh151_sequencial = $rh151_sequencial "; 
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

   public function sql_query_com_registros ($rh151_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
     $sql .= "  from rhconsignadomovimento rhcm";
     $sql .= "      left join rhconsignadomovimentoservidor rhcms on  rhcm.rh151_sequencial = rhcms.rh152_consignadomovimento";
     $sql .= "      left join rhconsignadomovimentoservidorrubrica rhcmsr on  rhcms.rh152_sequencial = rhcmsr.rh153_consignadomovimentoservidor";
     $sql .= "      left join rhconsignadomotivo rhcmot on  rhcms.rh152_consignadomotivo = rhcmot.rh154_sequencial";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh151_sequencial)) {
         $sql2 .= " where rhcm.rh151_sequencial = $rh151_sequencial ";
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
