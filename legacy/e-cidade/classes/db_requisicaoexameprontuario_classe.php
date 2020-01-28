<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE requisicaoexameprontuario
class cl_requisicaoexameprontuario {
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
   var $sd103_codigo = 0;
   var $sd103_prontuarios = 0;
   var $sd103_medicos = 0;
   var $sd103_data_dia = null;
   var $sd103_data_mes = null;
   var $sd103_data_ano = null;
   var $sd103_data = null;
   var $sd103_hora = null;
   var $sd103_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd103_codigo = int4 = Requisição
                 sd103_prontuarios = int4 = Prontuarios
                 sd103_medicos = int4 = Médicos
                 sd103_data = date = Data
                 sd103_hora = varchar(5) = Hora
                 sd103_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_requisicaoexameprontuario() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("requisicaoexameprontuario");
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
       $this->sd103_codigo = ($this->sd103_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_codigo"]:$this->sd103_codigo);
       $this->sd103_prontuarios = ($this->sd103_prontuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_prontuarios"]:$this->sd103_prontuarios);
       $this->sd103_medicos = ($this->sd103_medicos == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_medicos"]:$this->sd103_medicos);
       if($this->sd103_data == ""){
         $this->sd103_data_dia = ($this->sd103_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_data_dia"]:$this->sd103_data_dia);
         $this->sd103_data_mes = ($this->sd103_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_data_mes"]:$this->sd103_data_mes);
         $this->sd103_data_ano = ($this->sd103_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_data_ano"]:$this->sd103_data_ano);
         if($this->sd103_data_dia != ""){
            $this->sd103_data = $this->sd103_data_ano."-".$this->sd103_data_mes."-".$this->sd103_data_dia;
         }
       }
       $this->sd103_hora = ($this->sd103_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_hora"]:$this->sd103_hora);
       $this->sd103_observacao = ($this->sd103_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_observacao"]:$this->sd103_observacao);
     }else{
       $this->sd103_codigo = ($this->sd103_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd103_codigo"]:$this->sd103_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd103_codigo){
      $this->atualizacampos();
     if($this->sd103_prontuarios == null ){
       $this->erro_sql = " Campo Prontuarios não informado.";
       $this->erro_campo = "sd103_prontuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd103_medicos == null ){
       $this->erro_sql = " Campo Médicos não informado.";
       $this->erro_campo = "sd103_medicos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd103_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "sd103_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd103_hora == null ){
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "sd103_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd103_codigo == "" || $sd103_codigo == null ){
       $result = db_query("select nextval('requisicaoexameprontuario_sd103_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: requisicaoexameprontuario_sd103_codigo_seq do campo: sd103_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd103_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from requisicaoexameprontuario_sd103_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd103_codigo)){
         $this->erro_sql = " Campo sd103_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd103_codigo = $sd103_codigo;
       }
     }
     if(($this->sd103_codigo == null) || ($this->sd103_codigo == "") ){
       $this->erro_sql = " Campo sd103_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into requisicaoexameprontuario(
                                       sd103_codigo
                                      ,sd103_prontuarios
                                      ,sd103_medicos
                                      ,sd103_data
                                      ,sd103_hora
                                      ,sd103_observacao
                       )
                values (
                                $this->sd103_codigo
                               ,$this->sd103_prontuarios
                               ,$this->sd103_medicos
                               ,".($this->sd103_data == "null" || $this->sd103_data == ""?"null":"'".$this->sd103_data."'")."
                               ,'$this->sd103_hora'
                               ,'$this->sd103_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Requisição de exame do prontuario ($this->sd103_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Requisição de exame do prontuario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Requisição de exame do prontuario ($this->sd103_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd103_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd103_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20960,'$this->sd103_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3775,20960,'','".AddSlashes(pg_result($resaco,0,'sd103_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3775,20961,'','".AddSlashes(pg_result($resaco,0,'sd103_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3775,20962,'','".AddSlashes(pg_result($resaco,0,'sd103_medicos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3775,20964,'','".AddSlashes(pg_result($resaco,0,'sd103_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3775,20965,'','".AddSlashes(pg_result($resaco,0,'sd103_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3775,20963,'','".AddSlashes(pg_result($resaco,0,'sd103_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($sd103_codigo=null) {
      $this->atualizacampos();
     $sql = " update requisicaoexameprontuario set ";
     $virgula = "";
     if(trim($this->sd103_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_codigo"])){
       $sql  .= $virgula." sd103_codigo = $this->sd103_codigo ";
       $virgula = ",";
       if(trim($this->sd103_codigo) == null ){
         $this->erro_sql = " Campo Requisição não informado.";
         $this->erro_campo = "sd103_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd103_prontuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_prontuarios"])){
       $sql  .= $virgula." sd103_prontuarios = $this->sd103_prontuarios ";
       $virgula = ",";
       if(trim($this->sd103_prontuarios) == null ){
         $this->erro_sql = " Campo Prontuarios não informado.";
         $this->erro_campo = "sd103_prontuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd103_medicos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_medicos"])){
       $sql  .= $virgula." sd103_medicos = $this->sd103_medicos ";
       $virgula = ",";
       if(trim($this->sd103_medicos) == null ){
         $this->erro_sql = " Campo Médicos não informado.";
         $this->erro_campo = "sd103_medicos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd103_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd103_data_dia"] !="") ){
       $sql  .= $virgula." sd103_data = '$this->sd103_data' ";
       $virgula = ",";
       if(trim($this->sd103_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "sd103_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd103_data_dia"])){
         $sql  .= $virgula." sd103_data = null ";
         $virgula = ",";
         if(trim($this->sd103_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "sd103_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd103_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_hora"])){
       $sql  .= $virgula." sd103_hora = '$this->sd103_hora' ";
       $virgula = ",";
       if(trim($this->sd103_hora) == null ){
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "sd103_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sObservacao = " sd103_observacao = null ";
     if(trim($this->sd103_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd103_observacao"])){
       $sObservacao = " sd103_observacao = '$this->sd103_observacao' ";

     }
     $sql  .= $virgula." {$sObservacao}";
     $virgula = ",";

     $sql .= " where ";
     if($sd103_codigo!=null){
       $sql .= " sd103_codigo = $this->sd103_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd103_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20960,'$this->sd103_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_codigo"]) || $this->sd103_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3775,20960,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_codigo'))."','$this->sd103_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_prontuarios"]) || $this->sd103_prontuarios != "")
             $resac = db_query("insert into db_acount values($acount,3775,20961,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_prontuarios'))."','$this->sd103_prontuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_medicos"]) || $this->sd103_medicos != "")
             $resac = db_query("insert into db_acount values($acount,3775,20962,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_medicos'))."','$this->sd103_medicos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_data"]) || $this->sd103_data != "")
             $resac = db_query("insert into db_acount values($acount,3775,20964,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_data'))."','$this->sd103_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_hora"]) || $this->sd103_hora != "")
             $resac = db_query("insert into db_acount values($acount,3775,20965,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_hora'))."','$this->sd103_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd103_observacao"]) || $this->sd103_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3775,20963,'".AddSlashes(pg_result($resaco,$conresaco,'sd103_observacao'))."','$this->sd103_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Requisição de exame do prontuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd103_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Requisição de exame do prontuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($sd103_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd103_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20960,'$sd103_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3775,20960,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3775,20961,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_prontuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3775,20962,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_medicos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3775,20964,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3775,20965,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3775,20963,'','".AddSlashes(pg_result($resaco,$iresaco,'sd103_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from requisicaoexameprontuario
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd103_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd103_codigo = $sd103_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Requisição de exame do prontuario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd103_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Requisição de exame do prontuario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd103_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:requisicaoexameprontuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($sd103_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from requisicaoexameprontuario ";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = requisicaoexameprontuario.sd103_medicos";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = requisicaoexameprontuario.sd103_prontuarios";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join setorambulatorial  on  setorambulatorial.sd91_codigo = prontuarios.sd24_setorambulatorial";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd103_codigo)) {
         $sql2 .= " where requisicaoexameprontuario.sd103_codigo = $sd103_codigo ";
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
   public function sql_query_file ($sd103_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from requisicaoexameprontuario ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd103_codigo)){
         $sql2 .= " where requisicaoexameprontuario.sd103_codigo = $sd103_codigo ";
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
