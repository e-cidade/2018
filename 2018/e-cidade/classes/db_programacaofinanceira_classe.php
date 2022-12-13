<?
//MODULO: caixa
//CLASSE DA ENTIDADE programacaofinanceira
class cl_programacaofinanceira {
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
   var $k117_sequencial = 0;
   var $k117_id_usuario = 0;
   var $k117_data_dia = null;
   var $k117_data_mes = null;
   var $k117_data_ano = null;
   var $k117_data = null;
   var $k117_despesaantecipada = 'f';
   var $k117_conta = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k117_sequencial = int4 = Código Sequencial 
                 k117_id_usuario = int4 = Código do Usuáro 
                 k117_data = date = Data Inclusão 
                 k117_despesaantecipada = bool = Despesa Antecipada 
                 k117_conta = int4 = Conta 
                 ";
   //funcao construtor da classe
   function cl_programacaofinanceira() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("programacaofinanceira");
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
       $this->k117_sequencial = ($this->k117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]:$this->k117_sequencial);
       $this->k117_id_usuario = ($this->k117_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"]:$this->k117_id_usuario);
       if($this->k117_data == ""){
         $this->k117_data_dia = ($this->k117_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_dia"]:$this->k117_data_dia);
         $this->k117_data_mes = ($this->k117_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_mes"]:$this->k117_data_mes);
         $this->k117_data_ano = ($this->k117_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_data_ano"]:$this->k117_data_ano);
         if($this->k117_data_dia != ""){
            $this->k117_data = $this->k117_data_ano."-".$this->k117_data_mes."-".$this->k117_data_dia;
         }
       }
       $this->k117_despesaantecipada = ($this->k117_despesaantecipada == "f"?@$GLOBALS["HTTP_POST_VARS"]["k117_despesaantecipada"]:$this->k117_despesaantecipada);
       $this->k117_conta = ($this->k117_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_conta"]:$this->k117_conta);
     }else{
       $this->k117_sequencial = ($this->k117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]:$this->k117_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($k117_sequencial){
      $this->atualizacampos();
     if($this->k117_id_usuario == null ){
       $this->erro_sql = " Campo Código do Usuáro não informado.";
       $this->erro_campo = "k117_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_data == null ){
       $this->erro_sql = " Campo Data Inclusão não informado.";
       $this->erro_campo = "k117_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k117_despesaantecipada == null ){
       $this->erro_sql = " Campo Despesa Antecipada não informado.";
       $this->erro_campo = "k117_despesaantecipada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->k117_conta == null ) {
        $this->k117_conta = 'null';
     }
     if($k117_sequencial == "" || $k117_sequencial == null ){
       $result = db_query("select nextval('programacaofinanceira_k117_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: programacaofinanceira_k117_sequencial_seq do campo: k117_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k117_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from programacaofinanceira_k117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k117_sequencial)){
         $this->erro_sql = " Campo k117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k117_sequencial = $k117_sequencial;
       }
     }
     if(($this->k117_sequencial == null) || ($this->k117_sequencial == "") ){
       $this->erro_sql = " Campo k117_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into programacaofinanceira(
                                       k117_sequencial 
                                      ,k117_id_usuario 
                                      ,k117_data 
                                      ,k117_despesaantecipada 
                                      ,k117_conta 
                       )
                values (
                                $this->k117_sequencial 
                               ,$this->k117_id_usuario 
                               ,".($this->k117_data == "null" || $this->k117_data == ""?"null":"'".$this->k117_data."'")." 
                               ,'$this->k117_despesaantecipada' 
                               ,$this->k117_conta 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programação Financeira ($this->k117_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programação Financeira já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programação Financeira ($this->k117_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k117_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17125,'$this->k117_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3025,17125,'','".AddSlashes(pg_result($resaco,0,'k117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17126,'','".AddSlashes(pg_result($resaco,0,'k117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,17127,'','".AddSlashes(pg_result($resaco,0,'k117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,22410,'','".AddSlashes(pg_result($resaco,0,'k117_despesaantecipada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3025,22411,'','".AddSlashes(pg_result($resaco,0,'k117_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($k117_sequencial=null) {
      $this->atualizacampos();
     $sql = " update programacaofinanceira set ";
     $virgula = "";
     if(trim($this->k117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_sequencial"])){
       $sql  .= $virgula." k117_sequencial = $this->k117_sequencial ";
       $virgula = ",";
       if(trim($this->k117_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "k117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"])){
       $sql  .= $virgula." k117_id_usuario = $this->k117_id_usuario ";
       $virgula = ",";
       if(trim($this->k117_id_usuario) == null ){
         $this->erro_sql = " Campo Código do Usuáro não informado.";
         $this->erro_campo = "k117_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"] !="") ){
       $sql  .= $virgula." k117_data = '$this->k117_data' ";
       $virgula = ",";
       if(trim($this->k117_data) == null ){
         $this->erro_sql = " Campo Data Inclusão não informado.";
         $this->erro_campo = "k117_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k117_data_dia"])){
         $sql  .= $virgula." k117_data = null ";
         $virgula = ",";
         if(trim($this->k117_data) == null ){
           $this->erro_sql = " Campo Data Inclusão não informado.";
           $this->erro_campo = "k117_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k117_despesaantecipada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_despesaantecipada"])){
       $sql  .= $virgula." k117_despesaantecipada = '$this->k117_despesaantecipada' ";
       $virgula = ",";
       if(trim($this->k117_despesaantecipada) == null ){
         $this->erro_sql = " Campo Despesa Antecipada não informado.";
         $this->erro_campo = "k117_despesaantecipada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k117_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k117_conta"])){
       $sql  .= $virgula." k117_conta = $this->k117_conta ";
       $virgula = ",";
       if(trim($this->k117_conta) == null ){
         $this->erro_sql = " Campo Conta não informado.";
         $this->erro_campo = "k117_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k117_sequencial!=null){
       $sql .= " k117_sequencial = $this->k117_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k117_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17125,'$this->k117_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k117_sequencial"]) || $this->k117_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3025,17125,'".AddSlashes(pg_result($resaco,$conresaco,'k117_sequencial'))."','$this->k117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k117_id_usuario"]) || $this->k117_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3025,17126,'".AddSlashes(pg_result($resaco,$conresaco,'k117_id_usuario'))."','$this->k117_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k117_data"]) || $this->k117_data != "")
             $resac = db_query("insert into db_acount values($acount,3025,17127,'".AddSlashes(pg_result($resaco,$conresaco,'k117_data'))."','$this->k117_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k117_despesaantecipada"]) || $this->k117_despesaantecipada != "")
             $resac = db_query("insert into db_acount values($acount,3025,22410,'".AddSlashes(pg_result($resaco,$conresaco,'k117_despesaantecipada'))."','$this->k117_despesaantecipada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k117_conta"]) || $this->k117_conta != "")
             $resac = db_query("insert into db_acount values($acount,3025,22411,'".AddSlashes(pg_result($resaco,$conresaco,'k117_conta'))."','$this->k117_conta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($k117_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k117_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17125,'$k117_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3025,17125,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3025,17126,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3025,17127,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3025,22410,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_despesaantecipada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3025,22411,'','".AddSlashes(pg_result($resaco,$iresaco,'k117_conta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from programacaofinanceira
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k117_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k117_sequencial = $k117_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Financeira não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Programação Financeira não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:programacaofinanceira";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($k117_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from programacaofinanceira ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = programacaofinanceira.k117_id_usuario";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = programacaofinanceira.k117_conta";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k117_sequencial)) {
         $sql2 .= " where programacaofinanceira.k117_sequencial = $k117_sequencial ";
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
   public function sql_query_file ($k117_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from programacaofinanceira ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k117_sequencial)){
         $sql2 .= " where programacaofinanceira.k117_sequencial = $k117_sequencial ";
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

  public function sql_query_liquidacao(DBCompetencia $oCompetencia = null, $iCredor, $iContrato, $lBuscaAnosAnteriores = false) {

    $aWhere = array();

    if (!empty($oCompetencia)) {
      $aWhere[] = "k118_mes = {$oCompetencia->getMes()} and k118_ano = {$oCompetencia->getAno()}";
    } else {

      $oCompetencia = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
      $oCompetencia = $oCompetencia->getCompetencia();

      if ($lBuscaAnosAnteriores) {
          $aWhere[]     = "( k118_ano < {$oCompetencia->getAno()} OR (k118_mes <= {$oCompetencia->getMes()} and k118_ano <= {$oCompetencia->getAno()}))";
      } else {
          $aWhere[]     = "(k118_mes <= {$oCompetencia->getMes()} and k118_ano <= {$oCompetencia->getAno()})";
      }

    }

    if (!empty($iCredor)) {
      $aWhere[] = "ac16_contratado = {$iCredor}";
    }

    if (!empty($iContrato)) {
      $aWhere[] = "ac34_acordo = {$iContrato}";
    }

    $sWhere = implode(' and ', $aWhere);

    $sSql  = ' select ac34_acordo as acordo,                                                                           ';
    $sSql .= '        k118_mes,                                                                                        ';
    $sSql .= '        k117_despesaantecipada,                                                                                        ';
    $sSql .= '        k118_ano,                                                                                        ';
    $sSql .= '        k118_valor as valortotal,                                                                        ';
    $sSql .= '        sum(case                                                                                         ';
    $sSql .= '                when (c53_coddoc in(4000, 4002)) then c70_valor                                          ';
    $sSql .= '                else 0                                                                                   ';
    $sSql .= '            end) as valorreconhecido,                                                                    ';
    $sSql .= '        sum(case                                                                                         ';
    $sSql .= '                when (c53_tipo = 20) then c70_valor                                                      ';
    $sSql .= '                when (c53_tipo = 21) then c70_valor*-1                                                   ';
    $sSql .= '            end) as valorrealizado,                                                                      ';
    $sSql .= ' coalesce((select sum(case when c53_tipo = 20 then c70_valor else c70_valor *-1 end)  ';
    $sSql .= '             from empenho.empempenhocontrato ';
    $sSql .= '                  inner join conlancamemp on c75_numemp = e100_numemp ';
    $sSql .= '                  inner join conlancam    on c70_codlan = c75_codlan ';
    $sSql .= '                  inner join conlancamdoc on c70_codlan = c71_codlan ';
    $sSql .= '                  inner join conhistdoc   on c53_coddoc = c71_coddoc ';
    $sSql .= '           where e100_acordo =  ac34_acordo and c53_tipo in (20,21) and k117_despesaantecipada is true and k118_parcela = 1),0) as valor_pago_antecipado ';
    $sSql .= ' from programacaofinanceira                                                                              ';
    $sSql .= ' inner join acordoprogramacaofinanceira           on ac34_programacaofinanceira        = k117_sequencial ';
    $sSql .= ' inner join acordo                                on ac16_sequencial                   = ac34_acordo     ';
    $sSql .= ' inner join programacaofinanceiraparcela          on k118_programacaofinanceira        = k117_sequencial ';
    $sSql .= ' left  join conlancamprogramacaofinanceiraparcela on c118_programacaofinanceiraparcela = k118_sequencial ';
    $sSql .= ' left  join conlancam                             on c70_codlan                        = c118_conlancam  ';
    $sSql .= ' left  join conlancamdoc                          on c70_codlan                        = c71_codlan      ';
    $sSql .= ' left  join conhistdoc                            on c53_coddoc                        = c71_coddoc      ';
    $sSql .= ' and c53_tipo in (20,                                                                                    ';
    $sSql .= '                  21,                                                                                    ';
    $sSql .= '                  4000, 4002)                                                                             ';

    if (!empty($sWhere)) {
      $sSql .= "where $sWhere ";
    }

    $sSql .= ' group by k118_mes,                                                                                      ';
    $sSql .= '          k118_ano,                                                                                      ';
    $sSql .= '          k118_valor,                                                                                    ';
    $sSql .= '          ac34_acordo,                                                                                   ';
    $sSql .= '          ac16_resumoobjeto,                                                                             ';
    $sSql .= '          k117_despesaantecipada,                                                                        ';
    $sSql .= '          k118_parcela                                                                                   ';
    $sSql .= ' order by ac34_acordo,                                                                                   ';
    $sSql .= '          k118_ano,                                                                                      ';
    $sSql .= '          k118_mes                                                                                       ';

    return $sSql;
  }
}
