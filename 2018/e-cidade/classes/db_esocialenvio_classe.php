<?
//MODULO: esocial
//CLASSE DA ENTIDADE esocialenvio
class cl_esocialenvio { 
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
   var $rh213_sequencial = 0; 
   var $rh213_evento = 0; 
   var $rh213_empregador = 0; 
   var $rh213_responsavelpreenchimento = null; 
   var $rh213_dados = null; 
   var $rh213_md5 = null; 
   var $rh213_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh213_sequencial = int4 = Código 
                 rh213_evento = int4 = Código do Evento 
                 rh213_empregador = int4 = empregador 
                 rh213_responsavelpreenchimento = varchar(255) = Responsável Preenchimento 
                 rh213_dados = text = Dados 
                 rh213_md5 = varchar(32) = MD5 
                 rh213_situacao = int4 = Situacao 
                 ";
   //funcao construtor da classe 
   function cl_esocialenvio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("esocialenvio"); 
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
       $this->rh213_sequencial = ($this->rh213_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"]:$this->rh213_sequencial);
       $this->rh213_evento = ($this->rh213_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_evento"]:$this->rh213_evento);
       $this->rh213_empregador = ($this->rh213_empregador == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_empregador"]:$this->rh213_empregador);
       $this->rh213_responsavelpreenchimento = ($this->rh213_responsavelpreenchimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"]:$this->rh213_responsavelpreenchimento);
       $this->rh213_dados = ($this->rh213_dados == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_dados"]:$this->rh213_dados);
       $this->rh213_md5 = ($this->rh213_md5 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_md5"]:$this->rh213_md5);
       $this->rh213_situacao = ($this->rh213_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_situacao"]:$this->rh213_situacao);
     }else{
       $this->rh213_sequencial = ($this->rh213_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"]:$this->rh213_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh213_sequencial){ 
      $this->atualizacampos();
     if($this->rh213_evento == null ){ 
       $this->erro_sql = " Campo Código do Evento não informado.";
       $this->erro_campo = "rh213_evento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh213_empregador == null ){ 
       $this->erro_sql = " Campo empregador não informado.";
       $this->erro_campo = "rh213_empregador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh213_responsavelpreenchimento == null ){ 
       $this->erro_sql = " Campo Responsável Preenchimento não informado.";
       $this->erro_campo = "rh213_responsavelpreenchimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh213_dados == null ){ 
       $this->erro_sql = " Campo Dados não informado.";
       $this->erro_campo = "rh213_dados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh213_md5 == null ){ 
       $this->erro_sql = " Campo MD5 não informado.";
       $this->erro_campo = "rh213_md5";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh213_situacao == null ){ 
       $this->erro_sql = " Campo Situacao não informado.";
       $this->erro_campo = "rh213_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh213_sequencial == "" || $rh213_sequencial == null ){
       $result = db_query("select nextval('esocialenvio_rh213_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: esocialenvio_rh213_sequencial_seq do campo: rh213_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh213_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from esocialenvio_rh213_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh213_sequencial)){
         $this->erro_sql = " Campo rh213_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh213_sequencial = $rh213_sequencial; 
       }
     }
     if(($this->rh213_sequencial == null) || ($this->rh213_sequencial == "") ){ 
       $this->erro_sql = " Campo rh213_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into esocialenvio(
                                       rh213_sequencial 
                                      ,rh213_evento 
                                      ,rh213_empregador 
                                      ,rh213_responsavelpreenchimento 
                                      ,rh213_dados 
                                      ,rh213_md5 
                                      ,rh213_situacao 
                       )
                values (
                                $this->rh213_sequencial 
                               ,$this->rh213_evento 
                               ,$this->rh213_empregador 
                               ,'$this->rh213_responsavelpreenchimento' 
                               ,'$this->rh213_dados' 
                               ,'$this->rh213_md5' 
                               ,$this->rh213_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fila de envio para o eSocial ($this->rh213_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fila de envio para o eSocial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fila de envio para o eSocial ($this->rh213_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh213_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh213_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009543,'$this->rh213_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010244,1009543,'','".AddSlashes(pg_result($resaco,0,'rh213_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009544,'','".AddSlashes(pg_result($resaco,0,'rh213_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009545,'','".AddSlashes(pg_result($resaco,0,'rh213_empregador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009546,'','".AddSlashes(pg_result($resaco,0,'rh213_responsavelpreenchimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009547,'','".AddSlashes(pg_result($resaco,0,'rh213_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009548,'','".AddSlashes(pg_result($resaco,0,'rh213_md5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010244,1009549,'','".AddSlashes(pg_result($resaco,0,'rh213_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh213_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update esocialenvio set ";
     $virgula = "";
     if(trim($this->rh213_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"])){ 
       $sql  .= $virgula." rh213_sequencial = $this->rh213_sequencial ";
       $virgula = ",";
       if(trim($this->rh213_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh213_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_evento"])){ 
       $sql  .= $virgula." rh213_evento = $this->rh213_evento ";
       $virgula = ",";
       if(trim($this->rh213_evento) == null ){ 
         $this->erro_sql = " Campo Código do Evento não informado.";
         $this->erro_campo = "rh213_evento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_empregador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_empregador"])){ 
       $sql  .= $virgula." rh213_empregador = $this->rh213_empregador ";
       $virgula = ",";
       if(trim($this->rh213_empregador) == null ){ 
         $this->erro_sql = " Campo empregador não informado.";
         $this->erro_campo = "rh213_empregador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_responsavelpreenchimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"])){ 
       $sql  .= $virgula." rh213_responsavelpreenchimento = '$this->rh213_responsavelpreenchimento' ";
       $virgula = ",";
       if(trim($this->rh213_responsavelpreenchimento) == null ){ 
         $this->erro_sql = " Campo Responsável Preenchimento não informado.";
         $this->erro_campo = "rh213_responsavelpreenchimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_dados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_dados"])){ 
       $sql  .= $virgula." rh213_dados = '$this->rh213_dados' ";
       $virgula = ",";
       if(trim($this->rh213_dados) == null ){ 
         $this->erro_sql = " Campo Dados não informado.";
         $this->erro_campo = "rh213_dados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_md5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_md5"])){ 
       $sql  .= $virgula." rh213_md5 = '$this->rh213_md5' ";
       $virgula = ",";
       if(trim($this->rh213_md5) == null ){ 
         $this->erro_sql = " Campo MD5 não informado.";
         $this->erro_campo = "rh213_md5";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh213_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_situacao"])){ 
       $sql  .= $virgula." rh213_situacao = $this->rh213_situacao ";
       $virgula = ",";
       if(trim($this->rh213_situacao) == null ){ 
         $this->erro_sql = " Campo Situacao não informado.";
         $this->erro_campo = "rh213_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh213_sequencial!=null){
       $sql .= " rh213_sequencial = $this->rh213_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh213_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009543,'$this->rh213_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"]) || $this->rh213_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009543,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_sequencial'))."','$this->rh213_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_evento"]) || $this->rh213_evento != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009544,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_evento'))."','$this->rh213_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_empregador"]) || $this->rh213_empregador != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009545,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_empregador'))."','$this->rh213_empregador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"]) || $this->rh213_responsavelpreenchimento != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009546,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_responsavelpreenchimento'))."','$this->rh213_responsavelpreenchimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_dados"]) || $this->rh213_dados != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009547,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_dados'))."','$this->rh213_dados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_md5"]) || $this->rh213_md5 != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009548,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_md5'))."','$this->rh213_md5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_situacao"]) || $this->rh213_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010244,1009549,'".AddSlashes(pg_result($resaco,$conresaco,'rh213_situacao'))."','$this->rh213_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fila de envio para o eSocial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh213_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fila de envio para o eSocial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh213_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh213_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh213_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh213_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009543,'$rh213_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009543,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009544,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009545,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_empregador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009546,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_responsavelpreenchimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009547,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009548,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_md5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010244,1009549,'','".AddSlashes(pg_result($resaco,$iresaco,'rh213_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from esocialenvio
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh213_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh213_sequencial = $rh213_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fila de envio para o eSocial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh213_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Fila de envio para o eSocial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh213_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh213_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:esocialenvio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh213_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from esocialenvio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh213_sequencial)) {
         $sql2 .= " where esocialenvio.rh213_sequencial = $rh213_sequencial "; 
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
   public function sql_query_file ($rh213_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from esocialenvio ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh213_sequencial)){
         $sql2 .= " where esocialenvio.rh213_sequencial = $rh213_sequencial "; 
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
