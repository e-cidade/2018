<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhhistoricocalculo
class cl_rhhistoricocalculo { 
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
   var $rh143_sequencial = 0; 
   var $rh143_regist = 0; 
   var $rh143_folhapagamento = 0; 
   var $rh143_rubrica = null; 
   var $rh143_quantidade = 0; 
   var $rh143_valor = 0; 
   var $rh143_tipoevento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh143_sequencial = int4 = Sequencial 
                 rh143_regist = int4 = Matrícula do Servidor 
                 rh143_folhapagamento = int4 = Folha de Pagamento 
                 rh143_rubrica = char(4) = Rubrica 
                 rh143_quantidade = float8 = Quantidade 
                 rh143_valor = float8 = Valor da Rubrica 
                 rh143_tipoevento = int4 = Tipo de Evento 
                 ";
   //funcao construtor da classe 
   function cl_rhhistoricocalculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhhistoricocalculo"); 
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
       $this->rh143_sequencial = ($this->rh143_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_sequencial"]:$this->rh143_sequencial);
       $this->rh143_regist = ($this->rh143_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_regist"]:$this->rh143_regist);
       $this->rh143_folhapagamento = ($this->rh143_folhapagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_folhapagamento"]:$this->rh143_folhapagamento);
       $this->rh143_rubrica = ($this->rh143_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_rubrica"]:$this->rh143_rubrica);
       $this->rh143_quantidade = ($this->rh143_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_quantidade"]:$this->rh143_quantidade);
       $this->rh143_valor = ($this->rh143_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_valor"]:$this->rh143_valor);
       $this->rh143_tipoevento = ($this->rh143_tipoevento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_tipoevento"]:$this->rh143_tipoevento);
     }else{
       $this->rh143_sequencial = ($this->rh143_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh143_sequencial"]:$this->rh143_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh143_sequencial){ 
      $this->atualizacampos();
     if($this->rh143_regist == null ){ 
       $this->erro_sql = " Campo Matrícula do Servidor não informado.";
       $this->erro_campo = "rh143_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh143_folhapagamento == null ){ 
       $this->erro_sql = " Campo Folha de Pagamento não informado.";
       $this->erro_campo = "rh143_folhapagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh143_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh143_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh143_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade da Rubrica não informado.";
       $this->erro_campo = "rh143_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh143_valor == null ){ 
       $this->erro_sql = " Campo Valor da Rubrica não informado.";
       $this->erro_campo = "rh143_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh143_tipoevento == null ){ 
       $this->erro_sql = " Campo Tipo de Evento não informado.";
       $this->erro_campo = "rh143_tipoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh143_sequencial == "" || $rh143_sequencial == null ){
       $result = db_query("select nextval('rhhistoricocalculo_rh143_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhhistoricocalculo_rh143_sequencial_seq do campo: rh143_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh143_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhhistoricocalculo_rh143_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh143_sequencial)){
         $this->erro_sql = " Campo rh143_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh143_sequencial = $rh143_sequencial; 
       }
     }
     if(($this->rh143_sequencial == null) || ($this->rh143_sequencial == "") ){ 
       $this->erro_sql = " Campo rh143_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhhistoricocalculo(
                                       rh143_sequencial 
                                      ,rh143_regist 
                                      ,rh143_folhapagamento 
                                      ,rh143_rubrica 
                                      ,rh143_quantidade 
                                      ,rh143_valor 
                                      ,rh143_tipoevento 
                       )
                values (
                                $this->rh143_sequencial 
                               ,$this->rh143_regist 
                               ,$this->rh143_folhapagamento 
                               ,'$this->rh143_rubrica' 
                               ,$this->rh143_quantidade 
                               ,$this->rh143_valor 
                               ,$this->rh143_tipoevento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico do Cálculo ($this->rh143_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico do Cálculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico do Cálculo ($this->rh143_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh143_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh143_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20718,'$this->rh143_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3729,20718,'','".AddSlashes(pg_result($resaco,0,'rh143_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20730,'','".AddSlashes(pg_result($resaco,0,'rh143_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20719,'','".AddSlashes(pg_result($resaco,0,'rh143_folhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20720,'','".AddSlashes(pg_result($resaco,0,'rh143_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20721,'','".AddSlashes(pg_result($resaco,0,'rh143_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20722,'','".AddSlashes(pg_result($resaco,0,'rh143_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3729,20735,'','".AddSlashes(pg_result($resaco,0,'rh143_tipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh143_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhhistoricocalculo set ";
     $virgula = "";
     if(trim($this->rh143_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_sequencial"])){ 
       $sql  .= $virgula." rh143_sequencial = $this->rh143_sequencial ";
       $virgula = ",";
       if(trim($this->rh143_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh143_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_regist"])){ 
       $sql  .= $virgula." rh143_regist = $this->rh143_regist ";
       $virgula = ",";
       if(trim($this->rh143_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor não informado.";
         $this->erro_campo = "rh143_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_folhapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_folhapagamento"])){ 
       $sql  .= $virgula." rh143_folhapagamento = $this->rh143_folhapagamento ";
       $virgula = ",";
       if(trim($this->rh143_folhapagamento) == null ){ 
         $this->erro_sql = " Campo Folha de Pagamento não informado.";
         $this->erro_campo = "rh143_folhapagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_rubrica"])){ 
       $sql  .= $virgula." rh143_rubrica = '$this->rh143_rubrica' ";
       $virgula = ",";
       if(trim($this->rh143_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh143_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_quantidade"])){ 
       $sql  .= $virgula." rh143_quantidade = $this->rh143_quantidade ";
       $virgula = ",";
       if(trim($this->rh143_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade da Rubrica não informado.";
         $this->erro_campo = "rh143_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_valor"])){ 
       $sql  .= $virgula." rh143_valor = $this->rh143_valor ";
       $virgula = ",";
       if(trim($this->rh143_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Rubrica não informado.";
         $this->erro_campo = "rh143_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh143_tipoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh143_tipoevento"])){ 
       $sql  .= $virgula." rh143_tipoevento = $this->rh143_tipoevento ";
       $virgula = ",";
       if(trim($this->rh143_tipoevento) == null ){ 
         $this->erro_sql = " Campo tipo de Evento não informado.";
         $this->erro_campo = "rh143_tipoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh143_sequencial!=null){
       $sql .= " rh143_sequencial = $this->rh143_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh143_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20718,'$this->rh143_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_sequencial"]) || $this->rh143_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3729,20718,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_sequencial'))."','$this->rh143_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_regist"]) || $this->rh143_regist != "")
             $resac = db_query("insert into db_acount values($acount,3729,20730,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_regist'))."','$this->rh143_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_folhapagamento"]) || $this->rh143_folhapagamento != "")
             $resac = db_query("insert into db_acount values($acount,3729,20719,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_folhapagamento'))."','$this->rh143_folhapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_rubrica"]) || $this->rh143_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3729,20720,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_rubrica'))."','$this->rh143_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_quantidade"]) || $this->rh143_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3729,20721,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_quantidade'))."','$this->rh143_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_valor"]) || $this->rh143_valor != "")
             $resac = db_query("insert into db_acount values($acount,3729,20722,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_valor'))."','$this->rh143_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh143_tipoevento"]) || $this->rh143_tipoevento != "")
             $resac = db_query("insert into db_acount values($acount,3729,20735,'".AddSlashes(pg_result($resaco,$conresaco,'rh143_tipoevento'))."','$this->rh143_tipoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Cálculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh143_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Cálculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh143_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh143_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20718,'$rh143_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3729,20718,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20730,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20719,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_folhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20720,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20721,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20722,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3729,20735,'','".AddSlashes(pg_result($resaco,$iresaco,'rh143_tipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhhistoricocalculo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh143_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh143_sequencial = $rh143_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Cálculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh143_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Cálculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh143_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhhistoricocalculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh143_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhhistoricocalculo ";
     $sql .= "      inner join rhfolhapagamento  on  rhfolhapagamento.rh141_sequencial = rhhistoricocalculo.rh143_folhapagamento \n";
     $sql .= "      inner join rhtipofolha       on  rhtipofolha.rh142_sequencial      = rhfolhapagamento.rh141_tipofolha        \n";
     $sql .= "      inner join rhrubricas        on  rhrubricas.rh27_rubric            = rhhistoricocalculo.rh143_rubrica        \n";
     $sql .= "                                  and  rhrubricas.rh27_instit            = rhfolhapagamento.rh141_instit           \n";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh143_sequencial)) {
         $sql2 .= " where rhhistoricocalculo.rh143_sequencial = $rh143_sequencial "; 
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
   public function sql_query_file ($rh143_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhhistoricocalculo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh143_sequencial)){
         $sql2 .= " where rhhistoricocalculo.rh143_sequencial = $rh143_sequencial "; 
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


   public function sql_query_registros_consulta_complementar( $iSequencial, $sBase, $iMatricula ) {

     $sSql = " select case                                                                   \n";
     $sSql.= "          when rh27_pd != 3                                                    \n";
     $sSql.= "          then 1                                                               \n";
     $sSql.= "          else 3                                                               \n";
     $sSql.= "        end                as ordem,                                           \n";
     $sSql.= "        rh143_rubrica      as rubrica,                                         \n";
     $sSql.= "        case                                                                   \n";
     $sSql.= "          when rh143_tipoevento in ( 1, 3 )                                    \n";
     $sSql.= "          then rh143_valor                                                     \n";
     $sSql.= "          else 0                                                               \n";
     $sSql.= "        end                as provento,                                        \n";
     $sSql.= "        case                                                                   \n";
     $sSql.= "           when rh143_tipoevento in ( 1, 3 )                                   \n";
     $sSql.= "           then 0                                                              \n";
     $sSql.= "           else rh143_valor                                                    \n";
     $sSql.= "        end                as desconto,                                        \n";
     $sSql.= "        rh143_quantidade   as quant,                                           \n";
     $sSql.= "        rh27_descr         as rh27_descr,                                      \n";
     $sSql.= "        'x'                as tipo,                                            \n";
     $sSql.= "        case                                                                   \n";
     $sSql.= "          when rh143_tipoevento = 1                                            \n";
     $sSql.= "            then 'Provento'                                                    \n";
     $sSql.= "          when rh143_tipoevento = 2                                            \n";
     $sSql.= "            then 'Desconto'                                                    \n";
     $sSql.= "          when rh143_tipoevento = 3                                            \n";
     $sSql.= "            then 'Base'                                                        \n";
     $sSql.= "        end                 as provdesc,                                       \n";
     $sSql.= "        exists(select 1                                                        \n";
     $sSql.= "                 from rhbasesreg                                               \n";
     $sSql.= "                where rh54_base   = '$sBase'                                   \n";
     $sSql.= "                  and rh54_regist = '$iMatricula'                              \n";
     $sSql.= "                  and rh54_rubric = rh143_rubrica                              \n";
     $sSql.= "                union                                                          \n";
     $sSql.= "               select 1                                                        \n";
     $sSql.= "                 from basesr                                                   \n";
     $sSql.= "                where r09_anousu = rh141_anousu                                \n";
     $sSql.= "                  and r09_mesusu = rh141_mesusu                                \n";
     $sSql.= "                  and r09_instit = rh141_instit                                \n";
     $sSql.= "                  and r09_rubric = rh143_rubrica                               \n";
     $sSql.= "                  and r09_base   = '{$sBase}'                                  \n";
     $sSql.= "        ) as compoe_base,                                                      \n";
     $sSql.= "        exists( select 1                                                       \n";
     $sSql.= "                  from rhrubricas                                              \n";
     $sSql.= "                 where rh27_instit = rh141_instit                              \n";
     $sSql.= "                   and rh27_rubric = rh143_rubrica                             \n";
     $sSql.= "                   and (rh27_form  like '%{$sBase}%' or                        \n";
     $sSql.= "                        rh27_form2 like '%{$sBase}%' or                        \n";
     $sSql.= "                        rh27_form3 like '%{$sBase}%')                          \n";
     $sSql.= "        ) as tem_base_formula                                                  \n";
     $sSql.= "   from rhhistoricocalculo                                                     \n";
     $sSql.= "        inner join rhfolhapagamento on rh141_sequencial = rh143_folhapagamento \n";
     $sSql.= "                                   and rh141_sequencial = $iSequencial         \n";
     $sSql.= "                                   and rh143_regist     = $iMatricula          \n";
     $sSql.= "        inner join rhrubricas       on rh27_rubric      = rh143_rubrica        \n";
     $sSql.= "                                   and rh27_instit      = rh141_instit         \n";
     $sSql.= "   order by 1,2";
     return $sSql;
   }

   /**
    * Retorna o total de proventos da folha informada como parâmetro
    * @param  integer $iCodigoFolha
    * @return string  $sSql
    */
   public function sql_query_proventos_folha($iCodigoFolha, $iMatricula) {

     $iEventoProvento = EventoFinanceiroFolha::PROVENTO;

     $sSql  = "select sum(rh143_valor) as totalproventos";
     $sSql .= "  from rhhistoricocalculo";
     $sSql .= " where rh143_folhapagamento = {$iCodigoFolha}";
     $sSql .= "   and rh143_tipoevento     = {$iEventoProvento}";
     $sSql .= "   and rh143_regist         = {$iMatricula}";
     
     return $sSql;
   }

   /**
    * Retorna o total de descontos da folha informada como parâmetro
    * @param  integer $iCodigoFolha
    * @return string  $sSql
    */
   public function sql_query_descontos_folha($iCodigoFolha, $iMatricula) {

     $iEventoDesconto = EventoFinanceiroFolha::DESCONTO;

     $sSql  = "select sum(rh143_valor) as totaldescontos ";
     $sSql .= "  from rhhistoricocalculo                       ";
     $sSql .= " where rh143_folhapagamento = {$iCodigoFolha}   ";
     $sSql .= "   and rh143_tipoevento     = {$iEventoDesconto}";
     $sSql .= "   and rh143_regist         = {$iMatricula}";
     
     return $sSql;
   }

  /**
   * Retorna os dados necessarios para a geração em disco a partir dos dados informados como parâmetros.
   * @param  String         $iFolhaPagamento, pode ser informado apenas uma folha ou n folhas
   * @param  String         $sLabel          
   * @param  String         $sTipoFolha      
   * @return String $sSql                          
   */
  public function sql_query_geracao( $sFolhaPagamento, $sLabel, $sTipoFolha = null) {

    $sSql  = "select rh143_regist                                                     as regist,           \n";
    $sSql .= "       rh02_lota::varchar                                                as lotac,            \n";
    $sSql .= "       sum(case when rh143_tipoevento = 1 then rh143_valor else 0 end)  as proven,           \n";
    $sSql .= "       sum(case when rh143_tipoevento = 2 then rh143_valor else 0 end)  as descon,           \n";
    $sSql .= "       rh141_anousu                                                     as anousu,           \n";
    $sSql .= "       rh141_mesusu                                                     as mesusu            \n";

    if ( !is_null($sTipoFolha) ) {

      $sSql .= ",      '$sLabel'::varchar                                               as label_tipo_folha, \n";
      $sSql .= "       '$sTipoFolha'::varchar                                           as tipo_folha        \n";
    }

    $sSql .= "  from rhhistoricocalculo                                                                    \n";
    $sSql .= "       inner join rhfolhapagamento on rh143_folhapagamento = rh141_sequencial                \n";
    $sSql .= "       inner join rhpessoalmov     on rh02_regist          = rh143_regist                    \n";
    $sSql .= "                                  and rh02_anousu          = rh141_anousu                    \n";
    $sSql .= "                                  and rh02_mesusu          = rh141_mesusu                    \n";
    $sSql .= "                                  and rh02_instit          = rh141_instit                    \n";
    $sSql .= "  where rh143_folhapagamento  in ({$sFolhaPagamento})                                            \n";
    $sSql .= "  group by regist, lotac, anousu, mesusu                                                     \n";

    if ( !is_null($sTipoFolha) ) {
      $sSql .= ", label_tipo_folha, tipo_folha";  
    }

    return $sSql;
  }

  /**
   * Metodo para retornar todos os eventos financeitos da folha Salário e Suplementar, 
   * quando existir mais de um evento o mesmo é somado.
   * @param  DBCompetencia $oCompetencia
   * @param  DBCompetencia $oCompetencia
   * @param  DBCompetencia $oCompetencia
   * @return String
   */
  public function sql_query_eventosfinanceiros_fechados( DBCompetencia $oCompetencia, $sTipoCalculo = CalculoFolha::CALCULO_SALARIO, Servidor $oServidor = null, Rubrica $oRubrica = null ) {

    /**
     * Validações do tipo de Cálculo
     */
    switch ( $sTipoCalculo ) {

      case CalculoFolha::CALCULO_COMPLEMENTAR: 

        $sTiposFolha = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
        break;
      case CalculoFolha::CALCULO_SALARIO: 
      default:

        $sTiposFolha = FolhaPagamento::TIPO_FOLHA_SALARIO . ", " .
                       FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
      break;
    }

    /**
     * Matricula Informada
     */
    $iMatricula = null;

    if ( !empty($oServidor) ) {
      $iMatricula = $oServidor->getMatricula();
    }

    /**
     * Rubrica informada
     */
    $sRubrica   = null;

    if (!empty($oRubrica) ) {
      $sRubrica = $oRubrica->getCodigo();
    }


    $sSql  = "select rh143_regist,                                                            ".PHP_EOL;
    $sSql .= "       rh143_rubrica,                                                           ".PHP_EOL;
    $sSql .= "       sum(rh143_quantidade) as rh143_quantidade,                               ".PHP_EOL;
    $sSql .= "       sum(rh143_valor) as rh143_valor,                                         ".PHP_EOL;
    $sSql .= "       rh143_tipoevento                                                         ".PHP_EOL;
    $sSql .= "  from rhfolhapagamento                                                         ".PHP_EOL;
    $sSql .= "       inner join rhhistoricocalculo on rh143_folhapagamento = rh141_sequencial ".PHP_EOL;
    $sSql .= "where rh141_anousu = {$oCompetencia->getAno()}                                  ".PHP_EOL;
    $sSql .= "  and rh141_mesusu = {$oCompetencia->getMes()}                                  ".PHP_EOL;
    $sSql .= "  and rh141_aberto = false                                                      ".PHP_EOL;
    $sSql .= "  and rh141_tipofolha in ({$sTiposFolha})                                       ".PHP_EOL;

    if (!empty($iMatricula) ) {
      $sSql .= "  and rh143_regist = {$iMatricula}                                            ".PHP_EOL;
    }

    if (!empty($sRubrica) ) {
      $sSql .= "  and rh143_rubrica = '{$sRubrica}'                                           ".PHP_EOL;
    }

    $sSql .= "group by rh143_regist, rh143_rubrica, rh143_tipoevento order by rh143_tipoevento".PHP_EOL;

    return $sSql;
  }
  /**
   * 
   * 
   * @param  Array          $aMatricula      
   * @param  FolhaPagamento $oFolhaPagamento
   * @return String
   */
  public function sql_query_dados_consolidados($aMatricula = null, FolhaPagamento $oFolhaPagamento) {

    $iAno           =  $oFolhaPagamento->getCompetencia()->getAno();
    $iMes           =  $oFolhaPagamento->getCompetencia()->getMes();
    $iInstituicao   =  $oFolhaPagamento->getInstituicao()->getCodigo();
    $aFolhasSalario =  array(FolhaPagamento::TIPO_FOLHA_SALARIO, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR);

    if ( in_array($oFolhaPagamento->getTipoFolha(), $aFolhasSalario) ) {    
      $iTipoFolha = '1, 6';
    } elseif ($oFolhaPagamento->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR ) {
      $iTipoFolha = '3';
    }

    if ( !empty($aMatricula) && count($aMatricula) > 0 ) {
      $sWhereMatriculas = implode(",", $aMatricula);
    }
     
    $sSql  = " select anousu,                                                                         \n";
    $sSql .= "       mesusu,                                                                          \n";
    $sSql .= "       regist,                                                                          \n";
    $sSql .= "       rubric,                                                                          \n";
    $sSql .= "       sum(valor)  as valor,                                                            \n";
    $sSql .= "       pd,                                                                              \n";
    $sSql .= "       max(quant)  as quant,                                                            \n";
    $sSql .= "       rh02_lota::text as lotac,                                                        \n";
    $sSql .= "       semest,                                                                          \n";
    $sSql .= "       instit                                                                           \n";
    $sSql .= "  from (SELECT rh141_anousu     AS anousu,                                              \n";
    $sSql .= "               rh141_mesusu     AS mesusu,                                              \n";
    $sSql .= "               rh143_regist     AS regist,                                              \n";
    $sSql .= "               rh143_rubrica    AS rubric,                                              \n";
    $sSql .= "               rh143_valor      AS valor,                                               \n";
    $sSql .= "               rh143_tipoevento AS pd,                                                  \n";
    $sSql .= "               rh143_quantidade AS quant,                                               \n";
    $sSql .= "               1                AS semest,                                              \n";
    $sSql .= "               rh141_instit     AS instit                                               \n";
    $sSql .= "          FROM rhfolhapagamento                                                         \n";
    $sSql .= "               INNER JOIN rhhistoricocalculo ON rh141_sequencial = rh143_folhapagamento \n";
    $sSql .= "         WHERE rh141_tipofolha in ( {$iTipoFolha} )                                     \n";
    $sSql .= "           AND rh141_anousu    = {$iAno}                                                \n";
    $sSql .= "           AND rh141_mesusu    = {$iMes}                                                \n";
    $sSql .= "           AND rh141_instit    = {$iInstituicao}                                        \n";
    $sSql .= "       ) AS gerfsal                                                                     \n";
    $sSql .= "       inner join rhpessoalmov  on rh02_anousu = anousu                                 \n";
    $sSql .= "                               and rh02_mesusu = mesusu                                 \n";
    $sSql .= "                               and rh02_instit = instit                                 \n";
    $sSql .= "                               and rh02_regist = regist                                 \n";
    if ( !is_null($aMatricula) ) {
      $sSql .= " where regist in ({$sWhereMatriculas})                                                \n";
    }
    $sSql .= " group by anousu,                                                                       \n";
    $sSql .= "          mesusu,                                                                       \n";
    $sSql .= "          regist,                                                                       \n";
    $sSql .= "          rubric,                                                                       \n";
    $sSql .= "          pd,                                                                           \n";
    $sSql .= "          lotac,                                                                        \n";
    $sSql .= "          semest,                                                                       \n";
    $sSql .= "          instit                                                                        \n";
    $sSql .= " order by rubric;                                                                       \n";
    return $sSql;
  }
}
