<?
//MODULO: licitacao
//CLASSE DA ENTIDADE liclicitaevento
class cl_liclicitaevento {
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
   var $l46_sequencial = 0;
   var $l46_liclicita = 0;
   var $l46_fase = 0;
   var $l46_liclicitatipoevento = 0;
   var $l46_dataevento_dia = null;
   var $l46_dataevento_mes = null;
   var $l46_dataevento_ano = null;
   var $l46_dataevento = null;
   var $l46_datajulgamento_dia = null;
   var $l46_datajulgamento_mes = null;
   var $l46_datajulgamento_ano = null;
   var $l46_datajulgamento = null;
   var $l46_cgm = null;
   var $l46_tipopublicacao = null;
   var $l46_descricaopublicacao = null;
   var $l46_tiporesultado = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 l46_sequencial = int4 = Código
                 l46_liclicita = int4 = Código da Licitação
                 l46_fase = int4 = Fase
                 l46_liclicitatipoevento = int4 = Tipo de Evento
                 l46_dataevento = date = Data do Evento
                 l46_datajulgamento = date = Data do Julgamento
                 l46_cgm = int4 = Autor
                 l46_tipopublicacao = int4 = Tipo de Publicação
                 l46_descricaopublicacao = text = Descrição da Publicação
                 l46_tiporesultado = int4 = Tipo de Resultado
                 ";
   //funcao construtor da classe
   function cl_liclicitaevento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaevento");
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
       $this->l46_sequencial = ($this->l46_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_sequencial"]:$this->l46_sequencial);
       $this->l46_liclicita = ($this->l46_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_liclicita"]:$this->l46_liclicita);
       $this->l46_fase = ($this->l46_fase == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_fase"]:$this->l46_fase);
       $this->l46_liclicitatipoevento = ($this->l46_liclicitatipoevento == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_liclicitatipoevento"]:$this->l46_liclicitatipoevento);
       if($this->l46_dataevento == ""){
         $this->l46_dataevento_dia = ($this->l46_dataevento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_dataevento_dia"]:$this->l46_dataevento_dia);
         $this->l46_dataevento_mes = ($this->l46_dataevento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_dataevento_mes"]:$this->l46_dataevento_mes);
         $this->l46_dataevento_ano = ($this->l46_dataevento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_dataevento_ano"]:$this->l46_dataevento_ano);
         if($this->l46_dataevento_dia != ""){
            $this->l46_dataevento = $this->l46_dataevento_ano."-".$this->l46_dataevento_mes."-".$this->l46_dataevento_dia;
         }
       }
       if($this->l46_datajulgamento == ""){
         $this->l46_datajulgamento_dia = ($this->l46_datajulgamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento_dia"]:$this->l46_datajulgamento_dia);
         $this->l46_datajulgamento_mes = ($this->l46_datajulgamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento_mes"]:$this->l46_datajulgamento_mes);
         $this->l46_datajulgamento_ano = ($this->l46_datajulgamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento_ano"]:$this->l46_datajulgamento_ano);
         if($this->l46_datajulgamento_dia != ""){
            $this->l46_datajulgamento = $this->l46_datajulgamento_ano."-".$this->l46_datajulgamento_mes."-".$this->l46_datajulgamento_dia;
         }
       }
       $this->l46_cgm = ($this->l46_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_cgm"]:$this->l46_cgm);
       $this->l46_tipopublicacao = ($this->l46_tipopublicacao == "" ? "null" : $this->l46_tipopublicacao);
       $this->l46_descricaopublicacao = ($this->l46_descricaopublicacao == "" ? "null" : $this->l46_descricaopublicacao);
       $this->l46_tiporesultado = ($this->l46_tiporesultado == "" ? "null" : $this->l46_tiporesultado);
     }else{
       $this->l46_sequencial = ($this->l46_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l46_sequencial"]:$this->l46_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l46_sequencial){
      $this->atualizacampos();
     if($this->l46_liclicita == null ){
       $this->erro_sql = " Campo Código da Licitação não informado.";
       $this->erro_campo = "l46_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l46_fase == null ){
       $this->erro_sql = " Campo Fase não informado.";
       $this->erro_campo = "l46_fase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l46_liclicitatipoevento == null ){
       $this->erro_sql = " Campo Tipo de Evento não informado.";
       $this->erro_campo = "l46_liclicitatipoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l46_dataevento == null ){
       $this->erro_sql = " Campo Data do Evento não informado.";
       $this->erro_campo = "l46_dataevento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l46_datajulgamento == null ){
       $this->l46_datajulgamento = "null";
     }
     if($this->l46_cgm == null ){
       $this->l46_cgm = "null";
     }
     if($this->l46_tipopublicacao == null ){
       $this->l46_tipopublicacao = "null";
     }
     if($this->l46_tiporesultado == null ){
       $this->l46_tiporesultado = "null";
     }
     if($l46_sequencial == "" || $l46_sequencial == null ){
       $result = db_query("select nextval('liclicitaevento_l46_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaevento_l46_sequencial_seq do campo: l46_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->l46_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from liclicitaevento_l46_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l46_sequencial)){
         $this->erro_sql = " Campo l46_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l46_sequencial = $l46_sequencial;
       }
     }
     if(($this->l46_sequencial == null) || ($this->l46_sequencial == "") ){
       $this->erro_sql = " Campo l46_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaevento(
                                       l46_sequencial
                                      ,l46_liclicita
                                      ,l46_fase
                                      ,l46_liclicitatipoevento
                                      ,l46_dataevento
                                      ,l46_datajulgamento
                                      ,l46_cgm
                                      ,l46_tipopublicacao
                                      ,l46_descricaopublicacao
                                      ,l46_tiporesultado
                       )
                values (
                                $this->l46_sequencial
                               ,$this->l46_liclicita
                               ,$this->l46_fase
                               ,$this->l46_liclicitatipoevento
                               ,".($this->l46_dataevento == "null" || $this->l46_dataevento == ""?"null":"'".$this->l46_dataevento."'")."
                               ,".($this->l46_datajulgamento == "null" || $this->l46_datajulgamento == ""?"null":"'".$this->l46_datajulgamento."'")."
                               ,$this->l46_cgm
                               ,$this->l46_tipopublicacao
                               ," . ($this->l46_descricaopublicacao == "null" ? $this->l46_descricaopublicacao : "'".$this->l46_descricaopublicacao."'") . "
                               ,$this->l46_tiporesultado
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Eventos da Licitação ($this->l46_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Eventos da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Eventos da Licitação ($this->l46_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l46_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l46_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21739,'$this->l46_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3917,21739,'','".AddSlashes(pg_result($resaco,0,'l46_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21740,'','".AddSlashes(pg_result($resaco,0,'l46_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21741,'','".AddSlashes(pg_result($resaco,0,'l46_fase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21742,'','".AddSlashes(pg_result($resaco,0,'l46_liclicitatipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21743,'','".AddSlashes(pg_result($resaco,0,'l46_dataevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21744,'','".AddSlashes(pg_result($resaco,0,'l46_datajulgamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21745,'','".AddSlashes(pg_result($resaco,0,'l46_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21746,'','".AddSlashes(pg_result($resaco,0,'l46_tipopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21747,'','".AddSlashes(pg_result($resaco,0,'l46_descricaopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3917,21748,'','".AddSlashes(pg_result($resaco,0,'l46_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($l46_sequencial=null) {
      $this->atualizacampos();
     $sql = " update liclicitaevento set ";
     $virgula = "";
     if(trim($this->l46_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_sequencial"])){
       $sql  .= $virgula." l46_sequencial = $this->l46_sequencial ";
       $virgula = ",";
       if(trim($this->l46_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "l46_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l46_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_liclicita"])){
       $sql  .= $virgula." l46_liclicita = $this->l46_liclicita ";
       $virgula = ",";
       if(trim($this->l46_liclicita) == null ){
         $this->erro_sql = " Campo Código da Licitação não informado.";
         $this->erro_campo = "l46_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l46_fase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_fase"])){
       $sql  .= $virgula." l46_fase = $this->l46_fase ";
       $virgula = ",";
       if(trim($this->l46_fase) == null ){
         $this->erro_sql = " Campo Fase não informado.";
         $this->erro_campo = "l46_fase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l46_liclicitatipoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_liclicitatipoevento"])){
       $sql  .= $virgula." l46_liclicitatipoevento = $this->l46_liclicitatipoevento ";
       $virgula = ",";
       if(trim($this->l46_liclicitatipoevento) == null ){
         $this->erro_sql = " Campo Tipo de Evento não informado.";
         $this->erro_campo = "l46_liclicitatipoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l46_dataevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_dataevento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l46_dataevento_dia"] !="") ){
       $sql  .= $virgula." l46_dataevento = '$this->l46_dataevento' ";
       $virgula = ",";
       if(trim($this->l46_dataevento) == null ){
         $this->erro_sql = " Campo Data do Evento não informado.";
         $this->erro_campo = "l46_dataevento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["l46_dataevento_dia"])){
         $sql  .= $virgula." l46_dataevento = null ";
         $virgula = ",";
         if(trim($this->l46_dataevento) == null ){
           $this->erro_sql = " Campo Data do Evento não informado.";
           $this->erro_campo = "l46_dataevento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l46_datajulgamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento_dia"] !="") ){
       $sql  .= $virgula." l46_datajulgamento = '$this->l46_datajulgamento' ";
       $virgula = ",";
     } else {
       $sql  .= $virgula." l46_datajulgamento = null ";
       $virgula = ",";
     }
     if(trim($this->l46_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_cgm"])){
        if(trim($this->l46_cgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["l46_cgm"])){
           $this->l46_cgm = "null" ;
        }
       $sql  .= $virgula." l46_cgm = $this->l46_cgm ";
       $virgula = ",";
     }
     if(trim($this->l46_tipopublicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_tipopublicacao"])){
        if(trim($this->l46_tipopublicacao)==""){
           $this->l46_tipopublicacao = "null" ;
        }
       $sql  .= $virgula." l46_tipopublicacao = $this->l46_tipopublicacao ";
       $virgula = ",";
     }
     if(trim($this->l46_descricaopublicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_descricaopublicacao"])){

       if ($this->l46_descricaopublicacao == "null") {
         $sql .= $virgula . " l46_descricaopublicacao = $this->l46_descricaopublicacao ";
       } else {
         $sql .= $virgula . " l46_descricaopublicacao = '$this->l46_descricaopublicacao' ";
       }
       $virgula = ",";
     }
     if(trim($this->l46_tiporesultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l46_tiporesultado"])){
       $sql  .= $virgula." l46_tiporesultado = $this->l46_tiporesultado ";
       $virgula = ",";
       if(trim($this->l46_tiporesultado) == null ){
         $this->erro_sql = " Campo Tipo de Resultado não informado.";
         $this->erro_campo = "l46_tiporesultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l46_sequencial!=null){
       $sql .= " l46_sequencial = $this->l46_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l46_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21739,'$this->l46_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_sequencial"]) || $this->l46_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3917,21739,'".AddSlashes(pg_result($resaco,$conresaco,'l46_sequencial'))."','$this->l46_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_liclicita"]) || $this->l46_liclicita != "")
             $resac = db_query("insert into db_acount values($acount,3917,21740,'".AddSlashes(pg_result($resaco,$conresaco,'l46_liclicita'))."','$this->l46_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_fase"]) || $this->l46_fase != "")
             $resac = db_query("insert into db_acount values($acount,3917,21741,'".AddSlashes(pg_result($resaco,$conresaco,'l46_fase'))."','$this->l46_fase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_liclicitatipoevento"]) || $this->l46_liclicitatipoevento != "")
             $resac = db_query("insert into db_acount values($acount,3917,21742,'".AddSlashes(pg_result($resaco,$conresaco,'l46_liclicitatipoevento'))."','$this->l46_liclicitatipoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_dataevento"]) || $this->l46_dataevento != "")
             $resac = db_query("insert into db_acount values($acount,3917,21743,'".AddSlashes(pg_result($resaco,$conresaco,'l46_dataevento'))."','$this->l46_dataevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_datajulgamento"]) || $this->l46_datajulgamento != "")
             $resac = db_query("insert into db_acount values($acount,3917,21744,'".AddSlashes(pg_result($resaco,$conresaco,'l46_datajulgamento'))."','$this->l46_datajulgamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_cgm"]) || $this->l46_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3917,21745,'".AddSlashes(pg_result($resaco,$conresaco,'l46_cgm'))."','$this->l46_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_tipopublicacao"]) || $this->l46_tipopublicacao != "")
             $resac = db_query("insert into db_acount values($acount,3917,21746,'".AddSlashes(pg_result($resaco,$conresaco,'l46_tipopublicacao'))."','$this->l46_tipopublicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_descricaopublicacao"]) || $this->l46_descricaopublicacao != "")
             $resac = db_query("insert into db_acount values($acount,3917,21747,'".AddSlashes(pg_result($resaco,$conresaco,'l46_descricaopublicacao'))."','$this->l46_descricaopublicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l46_tiporesultado"]) || $this->l46_tiporesultado != "")
             $resac = db_query("insert into db_acount values($acount,3917,21748,'".AddSlashes(pg_result($resaco,$conresaco,'l46_tiporesultado'))."','$this->l46_tiporesultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos da Licitação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l46_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Licitação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($l46_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l46_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21739,'$l46_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3917,21739,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21740,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21741,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_fase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21742,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_liclicitatipoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21743,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_dataevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21744,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_datajulgamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21745,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21746,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_tipopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21747,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_descricaopublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3917,21748,'','".AddSlashes(pg_result($resaco,$iresaco,'l46_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from liclicitaevento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l46_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l46_sequencial = $l46_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos da Licitação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l46_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Licitação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l46_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l46_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaevento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($l46_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from liclicitaevento ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = liclicitaevento.l46_cgm";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaevento.l46_liclicita";
     $sql .= "      inner join liclicitatipoevento  on  liclicitatipoevento.l45_sequencial = liclicitaevento.l46_liclicitatipoevento";
     $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l46_sequencial)) {
         $sql2 .= " where liclicitaevento.l46_sequencial = $l46_sequencial ";
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
   public function sql_query_file ($l46_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from liclicitaevento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l46_sequencial)){
         $sql2 .= " where liclicitaevento.l46_sequencial = $l46_sequencial ";
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

  public function sql_query_licitacao_encerramento($sCampos = "*", $sWhere = null) {

    $sSqlBusca  = " select {$sCampos} ";
    $sSqlBusca .= "   from liclicitaevento";
    $sSqlBusca .= "        inner join liclicita  on  liclicita.l20_codigo = liclicitaevento.l46_liclicita";
    $sSqlBusca .= "        inner join liclicitatipoevento  on  liclicitatipoevento.l45_sequencial = liclicitaevento.l46_liclicitatipoevento";
    $sSqlBusca .= "        left join liclicitaencerramentolicitacon on l18_liclicita = l20_codigo ";

    if (!empty($sWhere)) {
      $sSqlBusca .= " where {$sWhere} ";
    }

    return $sSqlBusca;
  }

  public function sql_query_eventos($sCampos, $sWhere = null) {

    $sCampos = empty($sCampos) ? "*" : $sCampos;

    $sSqlBusca  = " select {$sCampos} ";
    $sSqlBusca .= "   from liclicitaevento";
    $sSqlBusca .= "        left  join cgm  on  cgm.z01_numcgm = liclicitaevento.l46_cgm";
    $sSqlBusca .= "        inner join liclicita  on  liclicita.l20_codigo = liclicitaevento.l46_liclicita";
    $sSqlBusca .= "        inner join liclicitatipoevento  on  liclicitatipoevento.l45_sequencial = liclicitaevento.l46_liclicitatipoevento";
    $sSqlBusca .= "        inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
    $sSqlBusca .= "        inner join pctipocompratribunal  on  pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal ";
    $sSqlBusca .= "        inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
    $sSqlBusca .= "        inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
    $sSqlBusca .= "        inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
    $sSqlBusca .= "        left join liclicitaencerramentolicitacon on l18_liclicita = l20_codigo ";

    if (!empty($sWhere)) {
      $sSqlBusca .= " where {$sWhere} ";
    }

    return $sSqlBusca;
  }

}
