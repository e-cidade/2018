<?
//MODULO: protocolo
//CLASSE DA ENTIDADE procandamint
class cl_procandamint {
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
   var $p78_sequencial = 0;
   var $p78_codandam = 0;
   var $p78_data_dia = null;
   var $p78_data_mes = null;
   var $p78_data_ano = null;
   var $p78_data = null;
   var $p78_hora = null;
   var $p78_usuario = 0;
   var $p78_despacho = null;
   var $p78_publico = 'f';
   var $p78_transint = 'f';
   var $p78_tipodespacho = 1;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 p78_sequencial = int4 = Cod. sequencial
                 p78_codandam = int4 = Código andamento
                 p78_data = date = Data
                 p78_hora = varchar(5) = Hora
                 p78_usuario = int4 = Cod. Usuário
                 p78_despacho = text = Despacho Interno
                 p78_publico = bool = Despacho Publico
                 p78_transint = bool = Trans. Int.
                 p78_tipodespacho = int4 = Tipo de Despacho
                 ";
   //funcao construtor da classe
   function cl_procandamint() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procandamint");
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
       $this->p78_sequencial = ($this->p78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_sequencial"]:$this->p78_sequencial);
       $this->p78_codandam = ($this->p78_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_codandam"]:$this->p78_codandam);
       if($this->p78_data == ""){
         $this->p78_data_dia = ($this->p78_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_data_dia"]:$this->p78_data_dia);
         $this->p78_data_mes = ($this->p78_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_data_mes"]:$this->p78_data_mes);
         $this->p78_data_ano = ($this->p78_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_data_ano"]:$this->p78_data_ano);
         if($this->p78_data_dia != ""){
            $this->p78_data = $this->p78_data_ano."-".$this->p78_data_mes."-".$this->p78_data_dia;
         }
       }
       $this->p78_hora = ($this->p78_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_hora"]:$this->p78_hora);
       $this->p78_usuario = ($this->p78_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_usuario"]:$this->p78_usuario);
       $this->p78_despacho = ($this->p78_despacho == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_despacho"]:$this->p78_despacho);
       $this->p78_publico = ($this->p78_publico == "f"?@$GLOBALS["HTTP_POST_VARS"]["p78_publico"]:$this->p78_publico);
       $this->p78_transint = ($this->p78_transint == "f"?@$GLOBALS["HTTP_POST_VARS"]["p78_transint"]:$this->p78_transint);
       $this->p78_tipodespacho = ($this->p78_tipodespacho == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_tipodespacho"]:$this->p78_tipodespacho);
     }else{
       $this->p78_sequencial = ($this->p78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p78_sequencial"]:$this->p78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($p78_sequencial){
      $this->atualizacampos();
     if($this->p78_codandam == null ){
       $this->erro_sql = " Campo Código andamento não informado.";
       $this->erro_campo = "p78_codandam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p78_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "p78_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p78_hora == null ){
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "p78_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p78_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "p78_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p78_publico == null ){
       $this->erro_sql = " Campo Despacho Publico não informado.";
       $this->erro_campo = "p78_publico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p78_transint == null ){
       $this->erro_sql = " Campo Trans. Int. não informado.";
       $this->erro_campo = "p78_transint";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p78_sequencial == "" || $p78_sequencial == null ){
       $result = db_query("select nextval('procandamint_p78_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procandamint_p78_sequencial_seq do campo: p78_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->p78_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from procandamint_p78_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p78_sequencial)){
         $this->erro_sql = " Campo p78_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p78_sequencial = $p78_sequencial;
       }
     }
     if(($this->p78_sequencial == null) || ($this->p78_sequencial == "") ){
       $this->erro_sql = " Campo p78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->p78_tipodespacho == null ){
       $this->p78_tipodespacho = 1;
     }
     $sql = "insert into procandamint(
                                       p78_sequencial
                                      ,p78_codandam
                                      ,p78_data
                                      ,p78_hora
                                      ,p78_usuario
                                      ,p78_despacho
                                      ,p78_publico
                                      ,p78_transint
                                      ,p78_tipodespacho
                       )
                values (
                                $this->p78_sequencial
                               ,$this->p78_codandam
                               ,".($this->p78_data == "null" || $this->p78_data == ""?"null":"'".$this->p78_data."'")."
                               ,'$this->p78_hora'
                               ,$this->p78_usuario
                               ,'$this->p78_despacho'
                               ,'$this->p78_publico'
                               ,'$this->p78_transint'
                               ,$this->p78_tipodespacho
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento Interno ($this->p78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento Interno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento Interno ($this->p78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p78_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p78_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6446,'$this->p78_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1059,6446,'','".AddSlashes(pg_result($resaco,0,'p78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6447,'','".AddSlashes(pg_result($resaco,0,'p78_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6448,'','".AddSlashes(pg_result($resaco,0,'p78_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6449,'','".AddSlashes(pg_result($resaco,0,'p78_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6450,'','".AddSlashes(pg_result($resaco,0,'p78_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6451,'','".AddSlashes(pg_result($resaco,0,'p78_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6523,'','".AddSlashes(pg_result($resaco,0,'p78_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,6535,'','".AddSlashes(pg_result($resaco,0,'p78_transint'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1059,20751,'','".AddSlashes(pg_result($resaco,0,'p78_tipodespacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($p78_sequencial=null) {
      $this->atualizacampos();
     $sql = " update procandamint set ";
     $virgula = "";
     if(trim($this->p78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_sequencial"])){
       $sql  .= $virgula." p78_sequencial = $this->p78_sequencial ";
       $virgula = ",";
       if(trim($this->p78_sequencial) == null ){
         $this->erro_sql = " Campo Cod. sequencial não informado.";
         $this->erro_campo = "p78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_codandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_codandam"])){
       $sql  .= $virgula." p78_codandam = $this->p78_codandam ";
       $virgula = ",";
       if(trim($this->p78_codandam) == null ){
         $this->erro_sql = " Campo Código andamento não informado.";
         $this->erro_campo = "p78_codandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p78_data_dia"] !="") ){
       $sql  .= $virgula." p78_data = '$this->p78_data' ";
       $virgula = ",";
       if(trim($this->p78_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "p78_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["p78_data_dia"])){
         $sql  .= $virgula." p78_data = null ";
         $virgula = ",";
         if(trim($this->p78_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "p78_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p78_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_hora"])){
       $sql  .= $virgula." p78_hora = '$this->p78_hora' ";
       $virgula = ",";
       if(trim($this->p78_hora) == null ){
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "p78_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_usuario"])){
       $sql  .= $virgula." p78_usuario = $this->p78_usuario ";
       $virgula = ",";
       if(trim($this->p78_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "p78_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_despacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_despacho"])){
       $sql  .= $virgula." p78_despacho = '$this->p78_despacho' ";
       $virgula = ",";
     }
     if(trim($this->p78_publico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_publico"])){
       $sql  .= $virgula." p78_publico = '$this->p78_publico' ";
       $virgula = ",";
       if(trim($this->p78_publico) == null ){
         $this->erro_sql = " Campo Despacho Publico não informado.";
         $this->erro_campo = "p78_publico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_transint)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_transint"])){
       $sql  .= $virgula." p78_transint = '$this->p78_transint' ";
       $virgula = ",";
       if(trim($this->p78_transint) == null ){
         $this->erro_sql = " Campo Trans. Int. não informado.";
         $this->erro_campo = "p78_transint";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p78_tipodespacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p78_tipodespacho"])){
       $sql  .= $virgula." p78_tipodespacho = $this->p78_tipodespacho ";
       $virgula = ",";
       if(trim($this->p78_tipodespacho) == null ){
         $this->erro_sql = " Campo Tipo de Despacho não informado.";
         $this->erro_campo = "p78_tipodespacho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p78_sequencial!=null){
       $sql .= " p78_sequencial = $this->p78_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p78_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,6446,'$this->p78_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_sequencial"]) || $this->p78_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1059,6446,'".AddSlashes(pg_result($resaco,$conresaco,'p78_sequencial'))."','$this->p78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_codandam"]) || $this->p78_codandam != "")
             $resac = db_query("insert into db_acount values($acount,1059,6447,'".AddSlashes(pg_result($resaco,$conresaco,'p78_codandam'))."','$this->p78_codandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_data"]) || $this->p78_data != "")
             $resac = db_query("insert into db_acount values($acount,1059,6448,'".AddSlashes(pg_result($resaco,$conresaco,'p78_data'))."','$this->p78_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_hora"]) || $this->p78_hora != "")
             $resac = db_query("insert into db_acount values($acount,1059,6449,'".AddSlashes(pg_result($resaco,$conresaco,'p78_hora'))."','$this->p78_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_usuario"]) || $this->p78_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1059,6450,'".AddSlashes(pg_result($resaco,$conresaco,'p78_usuario'))."','$this->p78_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_despacho"]) || $this->p78_despacho != "")
             $resac = db_query("insert into db_acount values($acount,1059,6451,'".AddSlashes(pg_result($resaco,$conresaco,'p78_despacho'))."','$this->p78_despacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_publico"]) || $this->p78_publico != "")
             $resac = db_query("insert into db_acount values($acount,1059,6523,'".AddSlashes(pg_result($resaco,$conresaco,'p78_publico'))."','$this->p78_publico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_transint"]) || $this->p78_transint != "")
             $resac = db_query("insert into db_acount values($acount,1059,6535,'".AddSlashes(pg_result($resaco,$conresaco,'p78_transint'))."','$this->p78_transint',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p78_tipodespacho"]) || $this->p78_tipodespacho != "")
             $resac = db_query("insert into db_acount values($acount,1059,20751,'".AddSlashes(pg_result($resaco,$conresaco,'p78_tipodespacho'))."','$this->p78_tipodespacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Interno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Interno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($p78_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p78_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,6446,'$p78_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1059,6446,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6447,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6448,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6449,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6450,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6451,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6523,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,6535,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_transint'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1059,20751,'','".AddSlashes(pg_result($resaco,$iresaco,'p78_tipodespacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from procandamint
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p78_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p78_sequencial = $p78_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Interno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Interno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procandamint";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($p78_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from procandamint ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = procandamint.p78_usuario";
     $sql .= "      inner join procandam  on  procandam.p61_codandam = procandamint.p78_codandam";
     $sql .= "      inner join tipodespacho  on  tipodespacho.p100_sequencial = procandamint.p78_tipodespacho";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = procandam.p61_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = procandam.p61_coddepto";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = procandam.p61_codproc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p78_sequencial)) {
         $sql2 .= " where procandamint.p78_sequencial = $p78_sequencial ";
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
   public function sql_query_file ($p78_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from procandamint ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p78_sequencial)){
         $sql2 .= " where procandamint.p78_sequencial = $p78_sequencial ";
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

   function sql_query_sim ( $p78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from procandamint ";
     $sql .= "      inner join db_usuarios on  db_usuarios.id_usuario = procandamint.p78_usuario";
     $sql .= "      left  join tipodespacho on p100_sequencial = p78_tipodespacho";
     $sql2 = "";
     if($dbwhere==""){
       if($p78_sequencial!=null ){
         $sql2 .= " where procandamint.p78_sequencial = $p78_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
 $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_tran ( $p78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from procandamint ";
     $sql .= "      inner join db_usuarios a  on  a.id_usuario = procandamint.p78_usuario";
     $sql .= "      inner join procandam  on  procandam.p61_codandam = procandamint.p78_codandam";
     $sql .= "      inner join db_usuarios b on  b.id_usuario = procandam.p61_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = procandam.p61_coddepto";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = procandam.p61_codproc";
     $sql .= "      inner join procandamintand  on  procandamintand.p86_codandam = procandamint.p78_codandam";
     $sql2 = "";
     if($dbwhere==""){
       if($p78_sequencial!=null ){
         $sql2 .= " where procandamint.p78_sequencial = $p78_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
 $virgula = ",";
       }
     }
     return $sql;
  }
}
