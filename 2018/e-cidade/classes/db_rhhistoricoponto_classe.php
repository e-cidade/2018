<?
//MODULO: pessoal
//CLASSE DA ENTIDADE rhhistoricoponto
class cl_rhhistoricoponto { 
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
   var $rh144_sequencial = 0; 
   var $rh144_regist = 0; 
   var $rh144_folhapagamento = 0; 
   var $rh144_rubrica = null; 
   var $rh144_quantidade = 0; 
   var $rh144_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh144_sequencial = int4 = Sequencial 
                 rh144_regist = int4 = Matrícula do Servidor 
                 rh144_folhapagamento = int4 = Folha de Pagamento 
                 rh144_rubrica = char(4) = Rubrica 
                 rh144_quantidade = float8 = Quantidade da Rubrica 
                 rh144_valor = float8 = Valor da Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhhistoricoponto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhhistoricoponto"); 
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
       $this->rh144_sequencial = ($this->rh144_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_sequencial"]:$this->rh144_sequencial);
       $this->rh144_regist = ($this->rh144_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_regist"]:$this->rh144_regist);
       $this->rh144_folhapagamento = ($this->rh144_folhapagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_folhapagamento"]:$this->rh144_folhapagamento);
       $this->rh144_rubrica = ($this->rh144_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_rubrica"]:$this->rh144_rubrica);
       $this->rh144_quantidade = ($this->rh144_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_quantidade"]:$this->rh144_quantidade);
       $this->rh144_valor = ($this->rh144_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_valor"]:$this->rh144_valor);
     }else{
       $this->rh144_sequencial = ($this->rh144_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh144_sequencial"]:$this->rh144_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh144_sequencial){ 
      $this->atualizacampos();
     if($this->rh144_regist == null ){ 
       $this->erro_sql = " Campo Matrícula do Servidor não informado.";
       $this->erro_campo = "rh144_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh144_folhapagamento == null ){ 
       $this->erro_sql = " Campo Folha de Pagamento não informado.";
       $this->erro_campo = "rh144_folhapagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh144_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh144_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh144_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade da Rubrica não informado.";
       $this->erro_campo = "rh144_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh144_valor == null ){ 
       $this->erro_sql = " Campo Valor da Rubrica não informado.";
       $this->erro_campo = "rh144_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh144_sequencial == "" || $rh144_sequencial == null ){
       $result = db_query("select nextval('rhhistoricoponto_rh144_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhhistoricoponto_rh144_sequencial_seq do campo: rh144_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh144_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhhistoricoponto_rh144_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh144_sequencial)){
         $this->erro_sql = " Campo rh144_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh144_sequencial = $rh144_sequencial; 
       }
     }
     if(($this->rh144_sequencial == null) || ($this->rh144_sequencial == "") ){ 
       $this->erro_sql = " Campo rh144_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhhistoricoponto(
                                       rh144_sequencial 
                                      ,rh144_regist 
                                      ,rh144_folhapagamento 
                                      ,rh144_rubrica 
                                      ,rh144_quantidade 
                                      ,rh144_valor 
                       )
                values (
                                $this->rh144_sequencial 
                               ,$this->rh144_regist 
                               ,$this->rh144_folhapagamento 
                               ,'$this->rh144_rubrica' 
                               ,$this->rh144_quantidade 
                               ,$this->rh144_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico do Ponto ($this->rh144_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico do Ponto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico do Ponto ($this->rh144_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh144_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh144_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20723,'$this->rh144_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3730,20723,'','".AddSlashes(pg_result($resaco,0,'rh144_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3730,20731,'','".AddSlashes(pg_result($resaco,0,'rh144_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3730,20724,'','".AddSlashes(pg_result($resaco,0,'rh144_folhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3730,20725,'','".AddSlashes(pg_result($resaco,0,'rh144_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3730,20726,'','".AddSlashes(pg_result($resaco,0,'rh144_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3730,20727,'','".AddSlashes(pg_result($resaco,0,'rh144_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh144_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhhistoricoponto set ";
     $virgula = "";
     if(trim($this->rh144_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_sequencial"])){ 
       $sql  .= $virgula." rh144_sequencial = $this->rh144_sequencial ";
       $virgula = ",";
       if(trim($this->rh144_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh144_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh144_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_regist"])){ 
       $sql  .= $virgula." rh144_regist = $this->rh144_regist ";
       $virgula = ",";
       if(trim($this->rh144_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor não informado.";
         $this->erro_campo = "rh144_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh144_folhapagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_folhapagamento"])){ 
       $sql  .= $virgula." rh144_folhapagamento = $this->rh144_folhapagamento ";
       $virgula = ",";
       if(trim($this->rh144_folhapagamento) == null ){ 
         $this->erro_sql = " Campo Folha de Pagamento não informado.";
         $this->erro_campo = "rh144_folhapagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh144_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_rubrica"])){ 
       $sql  .= $virgula." rh144_rubrica = '$this->rh144_rubrica' ";
       $virgula = ",";
       if(trim($this->rh144_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh144_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh144_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_quantidade"])){ 
       $sql  .= $virgula." rh144_quantidade = $this->rh144_quantidade ";
       $virgula = ",";
       if(trim($this->rh144_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade da Rubrica não informado.";
         $this->erro_campo = "rh144_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh144_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh144_valor"])){ 
       $sql  .= $virgula." rh144_valor = $this->rh144_valor ";
       $virgula = ",";
       if(trim($this->rh144_valor) == null ){ 
         $this->erro_sql = " Campo Valor da Rubrica não informado.";
         $this->erro_campo = "rh144_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh144_sequencial!=null){
       $sql .= " rh144_sequencial = $this->rh144_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh144_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20723,'$this->rh144_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_sequencial"]) || $this->rh144_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3730,20723,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_sequencial'))."','$this->rh144_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_regist"]) || $this->rh144_regist != "")
             $resac = db_query("insert into db_acount values($acount,3730,20731,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_regist'))."','$this->rh144_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_folhapagamento"]) || $this->rh144_folhapagamento != "")
             $resac = db_query("insert into db_acount values($acount,3730,20724,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_folhapagamento'))."','$this->rh144_folhapagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_rubrica"]) || $this->rh144_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,3730,20725,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_rubrica'))."','$this->rh144_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_quantidade"]) || $this->rh144_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3730,20726,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_quantidade'))."','$this->rh144_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh144_valor"]) || $this->rh144_valor != "")
             $resac = db_query("insert into db_acount values($acount,3730,20727,'".AddSlashes(pg_result($resaco,$conresaco,'rh144_valor'))."','$this->rh144_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Ponto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh144_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Ponto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh144_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh144_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20723,'$rh144_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3730,20723,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3730,20731,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3730,20724,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_folhapagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3730,20725,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3730,20726,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3730,20727,'','".AddSlashes(pg_result($resaco,$iresaco,'rh144_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhhistoricoponto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh144_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh144_sequencial = $rh144_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico do Ponto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh144_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico do Ponto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh144_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh144_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhhistoricoponto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh144_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhhistoricoponto ";
     $sql .= "      inner join rhfolhapagamento  on  rhfolhapagamento.rh141_sequencial = rhhistoricoponto.rh144_folhapagamento";
     $sql .= "      inner join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhfolhapagamento.rh141_tipofolha";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh144_sequencial)) {
         $sql2 .= " where rhhistoricoponto.rh144_sequencial = $rh144_sequencial "; 
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
   public function sql_query_file ($rh144_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhhistoricoponto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh144_sequencial)){
         $sql2 .= " where rhhistoricoponto.rh144_sequencial = $rh144_sequencial "; 
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
