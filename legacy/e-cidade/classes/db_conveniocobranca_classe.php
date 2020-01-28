<?
//MODULO: arrecadacao
//CLASSE DA ENTIDADE conveniocobranca
class cl_conveniocobranca {
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
   var $ar13_sequencial = 0;
   var $ar13_bancoagencia = 0;
   var $ar13_cadconvenio = 0;
   var $ar13_carteira = null;
   var $ar13_convenio = null;
   var $ar13_cedente = null;
   var $ar13_especie = null;
   var $ar13_variacao = 0;
   var $ar13_operacao = null;
   var $ar13_digcedente = null;
   var $ar13_contabancaria = null;
   var $ar13_responsavelnossonumero = 't';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ar13_sequencial = int4 = Sequêncial
                 ar13_bancoagencia = int4 = Agência do banco
                 ar13_cadconvenio = int4 = Convênio
                 ar13_carteira = varchar(6) = Carteira
                 ar13_convenio = varchar(7) = Convênio
                 ar13_cedente = varchar(6) = Cedente
                 ar13_especie = varchar(6) = Espécie
                 ar13_variacao = int4 = Variação
                 ar13_operacao = char(3) = Operação
                 ar13_digcedente = char(1) = Digito Cedente
                 ar13_contabancaria = int4 = Conta Bancária
                 ar13_responsavelnossonumero = bool = Responsável pelo Nosso Número
                 ";
   //funcao construtor da classe
   function cl_conveniocobranca() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conveniocobranca");
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
       $this->ar13_sequencial = ($this->ar13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_sequencial"]:$this->ar13_sequencial);
       $this->ar13_bancoagencia = ($this->ar13_bancoagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_bancoagencia"]:$this->ar13_bancoagencia);
       $this->ar13_cadconvenio = ($this->ar13_cadconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_cadconvenio"]:$this->ar13_cadconvenio);
       $this->ar13_carteira = ($this->ar13_carteira == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_carteira"]:$this->ar13_carteira);
       $this->ar13_convenio = ($this->ar13_convenio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_convenio"]:$this->ar13_convenio);
       $this->ar13_cedente = ($this->ar13_cedente == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_cedente"]:$this->ar13_cedente);
       $this->ar13_especie = ($this->ar13_especie == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_especie"]:$this->ar13_especie);
       $this->ar13_variacao = ($this->ar13_variacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_variacao"]:$this->ar13_variacao);
       $this->ar13_operacao = ($this->ar13_operacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_operacao"]:$this->ar13_operacao);
       $this->ar13_digcedente = ($this->ar13_digcedente == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_digcedente"]:$this->ar13_digcedente);
       $this->ar13_contabancaria = ($this->ar13_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_contabancaria"]:$this->ar13_contabancaria);
       if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_responsavelnossonumero"])) {
        $this->ar13_responsavelnossonumero = $GLOBALS["HTTP_POST_VARS"]["ar13_responsavelnossonumero"];
       }
     }else{
       $this->ar13_sequencial = ($this->ar13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar13_sequencial"]:$this->ar13_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ar13_sequencial){
      $this->atualizacampos();
     if($this->ar13_bancoagencia == null ){
       $this->erro_sql = " Campo Agência do banco não informado.";
       $this->erro_campo = "ar13_bancoagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_cadconvenio == null ){
       $this->erro_sql = " Campo Convênio não informado.";
       $this->erro_campo = "ar13_cadconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_carteira == null ){
       $this->erro_sql = " Campo Carteira não informado.";
       $this->erro_campo = "ar13_carteira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_cedente == null ){
       $this->erro_sql = " Campo Cedente não informado.";
       $this->erro_campo = "ar13_cedente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_especie == null ){
       $this->erro_sql = " Campo Espécie não informado.";
       $this->erro_campo = "ar13_especie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_variacao == null ){
       $this->erro_sql = " Campo Variação não informado.";
       $this->erro_campo = "ar13_variacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar13_contabancaria == null ){
       $this->ar13_contabancaria = "null";
     }
	     if($this->ar13_responsavelnossonumero == null ){
       $this->erro_sql = " Campo Responsável pelo Nosso Número não informado.";
       $this->erro_campo = "ar13_responsavelnossonumero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar13_sequencial == "" || $ar13_sequencial == null ){
       $result = db_query("select nextval('conveniocobranca_ar13_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conveniocobranca_ar13_sequencial_seq do campo: ar13_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ar13_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conveniocobranca_ar13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar13_sequencial)){
         $this->erro_sql = " Campo ar13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar13_sequencial = $ar13_sequencial;
       }
     }
     if(($this->ar13_sequencial == null) || ($this->ar13_sequencial == "") ){
       $this->erro_sql = " Campo ar13_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conveniocobranca(
                                       ar13_sequencial
                                      ,ar13_bancoagencia
                                      ,ar13_cadconvenio
                                      ,ar13_carteira
                                      ,ar13_convenio
                                      ,ar13_cedente
                                      ,ar13_especie
                                      ,ar13_variacao
                                      ,ar13_operacao
                                      ,ar13_digcedente
                                      ,ar13_contabancaria
                                      ,ar13_responsavelnossonumero
                       )
                values (
                                $this->ar13_sequencial
                               ,$this->ar13_bancoagencia
                               ,$this->ar13_cadconvenio
                               ,'$this->ar13_carteira'
                               ,'$this->ar13_convenio'
                               ,'$this->ar13_cedente'
                               ,'$this->ar13_especie'
                               ,$this->ar13_variacao
                               ,'$this->ar13_operacao'
                               ,'$this->ar13_digcedente'
                               ,$this->ar13_contabancaria
                               ,'$this->ar13_responsavelnossonumero'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cobrança do convênio ($this->ar13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cobrança do convênio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cobrança do convênio ($this->ar13_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ar13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ar13_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12527,'$this->ar13_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2186,12527,'','".AddSlashes(pg_result($resaco,0,'ar13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12528,'','".AddSlashes(pg_result($resaco,0,'ar13_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12529,'','".AddSlashes(pg_result($resaco,0,'ar13_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12530,'','".AddSlashes(pg_result($resaco,0,'ar13_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12531,'','".AddSlashes(pg_result($resaco,0,'ar13_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12532,'','".AddSlashes(pg_result($resaco,0,'ar13_cedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12533,'','".AddSlashes(pg_result($resaco,0,'ar13_especie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,12701,'','".AddSlashes(pg_result($resaco,0,'ar13_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,15146,'','".AddSlashes(pg_result($resaco,0,'ar13_operacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,15182,'','".AddSlashes(pg_result($resaco,0,'ar13_digcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2186,22121,'','".AddSlashes(pg_result($resaco,0,'ar13_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2186,22251,'','".AddSlashes(pg_result($resaco,0,'ar13_responsavelnossonumero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ar13_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conveniocobranca set ";
     $virgula = "";
     if(trim($this->ar13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_sequencial"])){
       $sql  .= $virgula." ar13_sequencial = $this->ar13_sequencial ";
       $virgula = ",";
       if(trim($this->ar13_sequencial) == null ){
         $this->erro_sql = " Campo Sequêncial não informado.";
         $this->erro_campo = "ar13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_bancoagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_bancoagencia"])){
       $sql  .= $virgula." ar13_bancoagencia = $this->ar13_bancoagencia ";
       $virgula = ",";
       if(trim($this->ar13_bancoagencia) == null ){
         $this->erro_sql = " Campo Agência do banco não informado.";
         $this->erro_campo = "ar13_bancoagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_cadconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_cadconvenio"])){
       $sql  .= $virgula." ar13_cadconvenio = $this->ar13_cadconvenio ";
       $virgula = ",";
       if(trim($this->ar13_cadconvenio) == null ){
         $this->erro_sql = " Campo Convênio não informado.";
         $this->erro_campo = "ar13_cadconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_carteira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_carteira"])){
       $sql  .= $virgula." ar13_carteira = '$this->ar13_carteira' ";
       $virgula = ",";
       if(trim($this->ar13_carteira) == null ){
         $this->erro_sql = " Campo Carteira não informado.";
         $this->erro_campo = "ar13_carteira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_convenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_convenio"])){
       $sql  .= $virgula." ar13_convenio = '$this->ar13_convenio' ";
       $virgula = ",";
     }
     if(trim($this->ar13_cedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_cedente"])){
       $sql  .= $virgula." ar13_cedente = '$this->ar13_cedente' ";
       $virgula = ",";
       if(trim($this->ar13_cedente) == null ){
         $this->erro_sql = " Campo Cedente não informado.";
         $this->erro_campo = "ar13_cedente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_especie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_especie"])){
       $sql  .= $virgula." ar13_especie = '$this->ar13_especie' ";
       $virgula = ",";
       if(trim($this->ar13_especie) == null ){
         $this->erro_sql = " Campo Espécie não informado.";
         $this->erro_campo = "ar13_especie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_variacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_variacao"])){
       $sql  .= $virgula." ar13_variacao = $this->ar13_variacao ";
       $virgula = ",";
       if(trim($this->ar13_variacao) == null ){
         $this->erro_sql = " Campo Variação não informado.";
         $this->erro_campo = "ar13_variacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar13_operacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_operacao"])){
       $sql  .= $virgula." ar13_operacao = '$this->ar13_operacao' ";
       $virgula = ",";
     }
     if(trim($this->ar13_digcedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_digcedente"])){
       $sql  .= $virgula." ar13_digcedente = '$this->ar13_digcedente' ";
       $virgula = ",";
     }
     if(trim($this->ar13_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_contabancaria"])){
        if(trim($this->ar13_contabancaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ar13_contabancaria"])){
           $this->ar13_contabancaria = "0" ;
        }
       $sql  .= $virgula." ar13_contabancaria = $this->ar13_contabancaria ";
       $virgula = ",";
     }
     if(trim($this->ar13_responsavelnossonumero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar13_responsavelnossonumero"])){
       $sql  .= $virgula." ar13_responsavelnossonumero = '$this->ar13_responsavelnossonumero' ";
       $virgula = ",";
       if(trim($this->ar13_responsavelnossonumero) == null ){
         $this->erro_sql = " Campo Responsável pelo Nosso Número não informado.";
         $this->erro_campo = "ar13_responsavelnossonumero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar13_sequencial!=null){
       $sql .= " ar13_sequencial = $this->ar13_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ar13_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12527,'$this->ar13_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_sequencial"]) || $this->ar13_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2186,12527,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_sequencial'))."','$this->ar13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_bancoagencia"]) || $this->ar13_bancoagencia != "")
             $resac = db_query("insert into db_acount values($acount,2186,12528,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_bancoagencia'))."','$this->ar13_bancoagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_cadconvenio"]) || $this->ar13_cadconvenio != "")
             $resac = db_query("insert into db_acount values($acount,2186,12529,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_cadconvenio'))."','$this->ar13_cadconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_carteira"]) || $this->ar13_carteira != "")
             $resac = db_query("insert into db_acount values($acount,2186,12530,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_carteira'))."','$this->ar13_carteira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_convenio"]) || $this->ar13_convenio != "")
             $resac = db_query("insert into db_acount values($acount,2186,12531,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_convenio'))."','$this->ar13_convenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_cedente"]) || $this->ar13_cedente != "")
             $resac = db_query("insert into db_acount values($acount,2186,12532,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_cedente'))."','$this->ar13_cedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_especie"]) || $this->ar13_especie != "")
             $resac = db_query("insert into db_acount values($acount,2186,12533,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_especie'))."','$this->ar13_especie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_variacao"]) || $this->ar13_variacao != "")
             $resac = db_query("insert into db_acount values($acount,2186,12701,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_variacao'))."','$this->ar13_variacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_operacao"]) || $this->ar13_operacao != "")
             $resac = db_query("insert into db_acount values($acount,2186,15146,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_operacao'))."','$this->ar13_operacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_digcedente"]) || $this->ar13_digcedente != "")
             $resac = db_query("insert into db_acount values($acount,2186,15182,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_digcedente'))."','$this->ar13_digcedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_contabancaria"]) || $this->ar13_contabancaria != "")
             $resac = db_query("insert into db_acount values($acount,2186,22121,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_contabancaria'))."','$this->ar13_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ar13_responsavelnossonumero"]) || $this->ar13_responsavelnossonumero != "")
             $resac = db_query("insert into db_acount values($acount,2186,22251,'".AddSlashes(pg_result($resaco,$conresaco,'ar13_responsavelnossonumero'))."','$this->ar13_responsavelnossonumero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cobrança do convênio não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cobrança do convênio não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ar13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ar13_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ar13_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12527,'$ar13_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2186,12527,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12528,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12529,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12530,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12531,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_convenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12532,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_cedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12533,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_especie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,12701,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,15146,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_operacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,15182,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_digcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,22121,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2186,22251,'','".AddSlashes(pg_result($resaco,$iresaco,'ar13_responsavelnossonumero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conveniocobranca
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ar13_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ar13_sequencial = $ar13_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cobrança do convênio não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cobrança do convênio não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ar13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conveniocobranca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ar13_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from conveniocobranca ";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = conveniocobranca.ar13_cadconvenio";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = conveniocobranca.ar13_bancoagencia";
     $sql .= "      left  join contabancaria  on  contabancaria.db83_sequencial = conveniocobranca.ar13_contabancaria";
     $sql .= "      inner join db_config  on  db_config.codigo = cadconvenio.ar11_instit";
     $sql .= "      inner join cadtipoconvenio  on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ar13_sequencial)) {
         $sql2 .= " where conveniocobranca.ar13_sequencial = $ar13_sequencial ";
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
   public function sql_query_file ($ar13_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from conveniocobranca ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ar13_sequencial)){
         $sql2 .= " where conveniocobranca.ar13_sequencial = $ar13_sequencial ";
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
