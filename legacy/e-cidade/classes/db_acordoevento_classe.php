<?
//MODULO: acordos
//CLASSE DA ENTIDADE acordoevento
class cl_acordoevento {
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
   var $ac55_sequencial = 0;
   var $ac55_tipoevento = 0;
   var $ac55_acordo = 0;
   var $ac55_data_dia = null;
   var $ac55_data_mes = null;
   var $ac55_data_ano = null;
   var $ac55_data = null;
   var $ac55_veiculocomunicacao = 0;
   var $ac55_numeroprocesso = null;
   var $ac55_anoprocesso = 0;
   var $ac55_descricaopublicacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac55_sequencial = int4 = Sequencial
                 ac55_tipoevento = int4 = Evento
                 ac55_acordo = int4 = Acordo
                 ac55_data = date = Data do Evento
                 ac55_veiculocomunicacao = int4 = Veículo de Comunicação
                 ac55_numeroprocesso = varchar(50) = Número do Processo
                 ac55_anoprocesso = int4 = Ano do Processo
                 ac55_descricaopublicacao = varchar(100) = Descrição da Publicação
                 ";
   //funcao construtor da classe
   function cl_acordoevento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoevento");
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

     if ($exclusao == false) {

       $this->ac55_sequencial = (empty($this->ac55_sequencial) ? 'null' : $this->ac55_sequencial);
       $this->ac55_tipoevento = (empty($this->ac55_tipoevento) ? 'null' : $this->ac55_tipoevento);
       $this->ac55_acordo = (empty($this->ac55_acordo) ? 'null' : $this->ac55_acordo);
       if (empty($this->ac55_data)) {

         $this->ac55_data_dia = (empty($this->ac55_data_dia) ? '' : $this->ac55_data_dia);
         $this->ac55_data_mes = (empty($this->ac55_data_mes) ? '' : $this->ac55_data_mes);
         $this->ac55_data_ano = (empty($this->ac55_data_ano) ? '' : $this->ac55_data_ano);
         if (!empty($this->ac55_data_dia)){
            $this->ac55_data = $this->ac55_data_ano . "-" . $this->ac55_data_mes . "-" . $this->ac55_data_dia;
         }
       }
       $this->ac55_veiculocomunicacao = (empty($this->ac55_veiculocomunicacao) ? 'null' : $this->ac55_veiculocomunicacao);
       $this->ac55_numeroprocesso = (empty($this->ac55_numeroprocesso) ? '' : $this->ac55_numeroprocesso);
       $this->ac55_anoprocesso = (empty($this->ac55_anoprocesso) ? 'null' : $this->ac55_anoprocesso);
       $this->ac55_descricaopublicacao = (empty($this->ac55_descricaopublicacao) ? '' : $this->ac55_descricaopublicacao);
     } else {
       $this->ac55_sequencial = ($this->ac55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac55_sequencial"]:$this->ac55_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ac55_sequencial){
      $this->atualizacampos();
     if($this->ac55_tipoevento == null ){
       $this->erro_sql = " Campo Evento não informado.";
       $this->erro_campo = "ac55_tipoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac55_acordo == null ){
       $this->erro_sql = " Campo Acordo não informado.";
       $this->erro_campo = "ac55_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac55_data == null ){
       $this->erro_sql = " Campo Data do Evento não informado.";
       $this->erro_campo = "ac55_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac55_sequencial == "" || $ac55_sequencial == null ){
       $result = db_query("select nextval('acordoevento_ac55_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoevento_ac55_sequencial_seq do campo: ac55_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac55_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordoevento_ac55_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac55_sequencial)){
         $this->erro_sql = " Campo ac55_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac55_sequencial = $ac55_sequencial;
       }
     }
     if(($this->ac55_sequencial == null) || ($this->ac55_sequencial == "") ){
       $this->erro_sql = " Campo ac55_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoevento(
                                       ac55_sequencial
                                      ,ac55_tipoevento
                                      ,ac55_acordo
                                      ,ac55_data
                                      ,ac55_veiculocomunicacao
                                      ,ac55_numeroprocesso
                                      ,ac55_anoprocesso
                                      ,ac55_descricaopublicacao
                       )
                values (
                                $this->ac55_sequencial
                               ,$this->ac55_tipoevento
                               ,$this->ac55_acordo
                               ,".($this->ac55_data == "null" || $this->ac55_data == ""?"null":"'".$this->ac55_data."'")."
                               ,$this->ac55_veiculocomunicacao
                               ,'$this->ac55_numeroprocesso'
                               ,$this->ac55_anoprocesso
                               ,'$this->ac55_descricaopublicacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Eventos do Acordo ($this->ac55_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Eventos do Acordo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Eventos do Acordo ($this->ac55_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac55_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac55_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21822,'$this->ac55_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3927,21822,'','".AddSlashes(pg_result($resaco,0,'ac55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21821,'','".AddSlashes(pg_result($resaco,0,'ac55_tipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21823,'','".AddSlashes(pg_result($resaco,0,'ac55_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21824,'','".AddSlashes(pg_result($resaco,0,'ac55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21825,'','".AddSlashes(pg_result($resaco,0,'ac55_veiculocomunicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21826,'','".AddSlashes(pg_result($resaco,0,'ac55_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21827,'','".AddSlashes(pg_result($resaco,0,'ac55_anoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3927,21828,'','".AddSlashes(pg_result($resaco,0,'ac55_descricaopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ac55_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordoevento set ";
     $virgula = "";
     if(trim($this->ac55_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_sequencial"])){
       $sql  .= $virgula." ac55_sequencial = $this->ac55_sequencial ";
       $virgula = ",";
       if(trim($this->ac55_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ac55_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac55_tipoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_tipoevento"])){
       $sql  .= $virgula." ac55_tipoevento = $this->ac55_tipoevento ";
       $virgula = ",";
       if(trim($this->ac55_tipoevento) == null ){
         $this->erro_sql = " Campo Evento não informado.";
         $this->erro_campo = "ac55_tipoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac55_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_acordo"])){
       $sql  .= $virgula." ac55_acordo = $this->ac55_acordo ";
       $virgula = ",";
       if(trim($this->ac55_acordo) == null ){
         $this->erro_sql = " Campo Acordo não informado.";
         $this->erro_campo = "ac55_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac55_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac55_data_dia"] !="") ){
       $sql  .= $virgula." ac55_data = '$this->ac55_data' ";
       $virgula = ",";
       if(trim($this->ac55_data) == null ){
         $this->erro_sql = " Campo Data do Evento não informado.";
         $this->erro_campo = "ac55_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac55_data_dia"])){
         $sql  .= $virgula." ac55_data = null ";
         $virgula = ",";
         if(trim($this->ac55_data) == null ){
           $this->erro_sql = " Campo Data do Evento não informado.";
           $this->erro_campo = "ac55_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac55_veiculocomunicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_veiculocomunicacao"])){
        if(trim($this->ac55_veiculocomunicacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac55_veiculocomunicacao"])){
           $this->ac55_veiculocomunicacao = "0" ;
        }
       $sql  .= $virgula." ac55_veiculocomunicacao = $this->ac55_veiculocomunicacao ";
       $virgula = ",";
     }
     if(trim($this->ac55_numeroprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_numeroprocesso"])){
       $sql  .= $virgula." ac55_numeroprocesso = '$this->ac55_numeroprocesso' ";
       $virgula = ",";
     }
     if(trim($this->ac55_anoprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_anoprocesso"])){
        if(trim($this->ac55_anoprocesso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac55_anoprocesso"])){
           $this->ac55_anoprocesso = "0" ;
        }
       $sql  .= $virgula." ac55_anoprocesso = $this->ac55_anoprocesso ";
       $virgula = ",";
     }
     if(trim($this->ac55_descricaopublicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac55_descricaopublicacao"])){
       $sql  .= $virgula." ac55_descricaopublicacao = '$this->ac55_descricaopublicacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac55_sequencial!=null){
       $sql .= " ac55_sequencial = $this->ac55_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac55_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21822,'$this->ac55_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_sequencial"]) || $this->ac55_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3927,21822,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_sequencial'))."','$this->ac55_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_tipoevento"]) || $this->ac55_tipoevento != "")
             $resac = db_query("insert into db_acount values($acount,3927,21821,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_tipoevento'))."','$this->ac55_tipoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_acordo"]) || $this->ac55_acordo != "")
             $resac = db_query("insert into db_acount values($acount,3927,21823,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_acordo'))."','$this->ac55_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_data"]) || $this->ac55_data != "")
             $resac = db_query("insert into db_acount values($acount,3927,21824,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_data'))."','$this->ac55_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_veiculocomunicacao"]) || $this->ac55_veiculocomunicacao != "")
             $resac = db_query("insert into db_acount values($acount,3927,21825,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_veiculocomunicacao'))."','$this->ac55_veiculocomunicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_numeroprocesso"]) || $this->ac55_numeroprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3927,21826,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_numeroprocesso'))."','$this->ac55_numeroprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_anoprocesso"]) || $this->ac55_anoprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3927,21827,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_anoprocesso'))."','$this->ac55_anoprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac55_descricaopublicacao"]) || $this->ac55_descricaopublicacao != "")
             $resac = db_query("insert into db_acount values($acount,3927,21828,'".AddSlashes(pg_result($resaco,$conresaco,'ac55_descricaopublicacao'))."','$this->ac55_descricaopublicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos do Acordo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Eventos do Acordo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ac55_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac55_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21822,'$ac55_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3927,21822,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21821,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_tipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21823,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21824,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21825,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_veiculocomunicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21826,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21827,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_anoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3927,21828,'','".AddSlashes(pg_result($resaco,$iresaco,'ac55_descricaopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoevento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac55_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac55_sequencial = $ac55_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos do Acordo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Eventos do Acordo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac55_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoevento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ac55_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from acordoevento ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoevento.ac55_acordo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
     $sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
     $sql .= "      left join acordoposicaoevento  on  acordoposicaoevento.ac56_acordoevento = acordoevento.ac55_sequencial";
     $sql .= "      left join acordoposicao  on  acordoposicao.ac26_sequencial = acordoposicaoevento.ac56_acordoposicao";
     $sql .= "      left  join acordoencerramentolicitacon on ac16_sequencial = ac58_acordo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac55_sequencial)) {
         $sql2 .= " where acordoevento.ac55_sequencial = $ac55_sequencial ";
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
   public function sql_query_file ($ac55_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordoevento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac55_sequencial)){
         $sql2 .= " where acordoevento.ac55_sequencial = $ac55_sequencial ";
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
