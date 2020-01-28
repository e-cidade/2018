<?
//MODULO: farmacia
//CLASSE DA ENTIDADE dadoscompetenciasaida
class cl_dadoscompetenciasaida {
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
   var $fa63_sequencial = 0;
   var $fa63_integracaohorus = 0;
   var $fa63_matestoqueinimei = 0;
   var $fa63_unidade = 0;
   var $fa63_enviar = 'f';
   var $fa63_validadohorus = 'f';
   var $fa63_catmat = null;
   var $fa63_cnes = null;
   var $fa63_tipo = null;
   var $fa63_valor = 0;
   var $fa63_lote = null;
   var $fa63_validade_dia = null;
   var $fa63_validade_mes = null;
   var $fa63_validade_ano = null;
   var $fa63_validade = null;
   var $fa63_quantidade = 0;
   var $fa63_data_dia = null;
   var $fa63_data_mes = null;
   var $fa63_data_ano = null;
   var $fa63_data = null;
   var $fa63_movimentacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 fa63_sequencial = int4 = Código
                 fa63_integracaohorus = int4 = Integração Hórus
                 fa63_matestoqueinimei = int4 = Movimentação Estoque
                 fa63_unidade = int4 = UPS
                 fa63_enviar = bool = Enviar
                 fa63_validadohorus = bool = Validado Hórus
                 fa63_catmat = varchar(20) = CATMAT
                 fa63_cnes = varchar(10) = CNES
                 fa63_tipo = char(1) = Tipo do produto
                 fa63_valor = float4 = Valor
                 fa63_lote = varchar(50) = Lote
                 fa63_validade = date = Validade
                 fa63_quantidade = int4 = Quantidade
                 fa63_data = date = Data Saída
                 fa63_movimentacao = varchar(15) = Tipos de movimentação
                 ";
   //funcao construtor da classe
   function cl_dadoscompetenciasaida() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dadoscompetenciasaida");
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
       $this->fa63_sequencial = ($this->fa63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_sequencial"]:$this->fa63_sequencial);
       $this->fa63_integracaohorus = ($this->fa63_integracaohorus == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_integracaohorus"]:$this->fa63_integracaohorus);
       $this->fa63_matestoqueinimei = ($this->fa63_matestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_matestoqueinimei"]:$this->fa63_matestoqueinimei);
       $this->fa63_unidade = ($this->fa63_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_unidade"]:$this->fa63_unidade);
       $this->fa63_enviar = ($this->fa63_enviar == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa63_enviar"]:$this->fa63_enviar);
       $this->fa63_validadohorus = ($this->fa63_validadohorus == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa63_validadohorus"]:$this->fa63_validadohorus);
       $this->fa63_catmat = ($this->fa63_catmat == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_catmat"]:$this->fa63_catmat);
       $this->fa63_cnes = ($this->fa63_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_cnes"]:$this->fa63_cnes);
       $this->fa63_tipo = ($this->fa63_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_tipo"]:$this->fa63_tipo);
       $this->fa63_valor = ($this->fa63_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_valor"]:$this->fa63_valor);
       $this->fa63_lote = ($this->fa63_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_lote"]:$this->fa63_lote);
       if($this->fa63_validade == ""){
         $this->fa63_validade_dia = ($this->fa63_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_validade_dia"]:$this->fa63_validade_dia);
         $this->fa63_validade_mes = ($this->fa63_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_validade_mes"]:$this->fa63_validade_mes);
         $this->fa63_validade_ano = ($this->fa63_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_validade_ano"]:$this->fa63_validade_ano);
         if($this->fa63_validade_dia != ""){
            $this->fa63_validade = $this->fa63_validade_ano."-".$this->fa63_validade_mes."-".$this->fa63_validade_dia;
         }
       }
       $this->fa63_quantidade = ($this->fa63_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_quantidade"]:$this->fa63_quantidade);
       if($this->fa63_data == ""){
         $this->fa63_data_dia = ($this->fa63_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_data_dia"]:$this->fa63_data_dia);
         $this->fa63_data_mes = ($this->fa63_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_data_mes"]:$this->fa63_data_mes);
         $this->fa63_data_ano = ($this->fa63_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_data_ano"]:$this->fa63_data_ano);
         if($this->fa63_data_dia != ""){
            $this->fa63_data = $this->fa63_data_ano."-".$this->fa63_data_mes."-".$this->fa63_data_dia;
         }
       }
       $this->fa63_movimentacao = ($this->fa63_movimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_movimentacao"]:$this->fa63_movimentacao);
     }else{
       $this->fa63_sequencial = ($this->fa63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa63_sequencial"]:$this->fa63_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($fa63_sequencial){
      $this->atualizacampos();
     if($this->fa63_integracaohorus == null ){
       $this->erro_sql = " Campo Integração Hórus não informado.";
       $this->erro_campo = "fa63_integracaohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_matestoqueinimei == null ){
       $this->erro_sql = " Campo Movimentação Estoque não informado.";
       $this->erro_campo = "fa63_matestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_unidade == null ){
       $this->erro_sql = " Campo UPS não informado.";
       $this->erro_campo = "fa63_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_enviar == null ){
       $this->erro_sql = " Campo Enviar não informado.";
       $this->erro_campo = "fa63_enviar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_validadohorus == null ){
       $this->erro_sql = " Campo Validado Hórus não informado.";
       $this->erro_campo = "fa63_validadohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_catmat == null ){
       $this->erro_sql = " Campo CATMAT não informado.";
       $this->erro_campo = "fa63_catmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_cnes == null ){
       $this->erro_sql = " Campo CNES não informado.";
       $this->erro_campo = "fa63_cnes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_tipo == null ){
       $this->erro_sql = " Campo Tipo do produto não informado.";
       $this->erro_campo = "fa63_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "fa63_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_validade == null ){
       $this->fa63_validade = "null";
     }
     if($this->fa63_quantidade == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "fa63_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_data == null ){
       $this->erro_sql = " Campo Data Saída não informado.";
       $this->erro_campo = "fa63_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa63_movimentacao == null ){
       $this->erro_sql = " Campo Tipos de movimentação não informado.";
       $this->erro_campo = "fa63_movimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa63_sequencial == "" || $fa63_sequencial == null ){
       $result = db_query("select nextval('dadoscompetenciasaida_fa63_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dadoscompetenciasaida_fa63_sequencial_seq do campo: fa63_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->fa63_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dadoscompetenciasaida_fa63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa63_sequencial)){
         $this->erro_sql = " Campo fa63_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa63_sequencial = $fa63_sequencial;
       }
     }
     if(($this->fa63_sequencial == null) || ($this->fa63_sequencial == "") ){
       $this->erro_sql = " Campo fa63_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dadoscompetenciasaida(
                                       fa63_sequencial
                                      ,fa63_integracaohorus
                                      ,fa63_matestoqueinimei
                                      ,fa63_unidade
                                      ,fa63_enviar
                                      ,fa63_validadohorus
                                      ,fa63_catmat
                                      ,fa63_cnes
                                      ,fa63_tipo
                                      ,fa63_valor
                                      ,fa63_lote
                                      ,fa63_validade
                                      ,fa63_quantidade
                                      ,fa63_data
                                      ,fa63_movimentacao
                       )
                values (
                                $this->fa63_sequencial
                               ,$this->fa63_integracaohorus
                               ,$this->fa63_matestoqueinimei
                               ,$this->fa63_unidade
                               ,'$this->fa63_enviar'
                               ,'$this->fa63_validadohorus'
                               ,'$this->fa63_catmat'
                               ,'$this->fa63_cnes'
                               ,'$this->fa63_tipo'
                               ,$this->fa63_valor
                               ,'$this->fa63_lote'
                               ,".($this->fa63_validade == "null" || $this->fa63_validade == ""?"null":"'".$this->fa63_validade."'")."
                               ,$this->fa63_quantidade
                               ,".($this->fa63_data == "null" || $this->fa63_data == ""?"null":"'".$this->fa63_data."'")."
                               ,'$this->fa63_movimentacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Saída ($this->fa63_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Saída já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Saída ($this->fa63_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa63_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa63_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21550,'$this->fa63_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3869,21550,'','".AddSlashes(pg_result($resaco,0,'fa63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21551,'','".AddSlashes(pg_result($resaco,0,'fa63_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21552,'','".AddSlashes(pg_result($resaco,0,'fa63_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21553,'','".AddSlashes(pg_result($resaco,0,'fa63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21554,'','".AddSlashes(pg_result($resaco,0,'fa63_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21555,'','".AddSlashes(pg_result($resaco,0,'fa63_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21557,'','".AddSlashes(pg_result($resaco,0,'fa63_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21556,'','".AddSlashes(pg_result($resaco,0,'fa63_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21558,'','".AddSlashes(pg_result($resaco,0,'fa63_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21559,'','".AddSlashes(pg_result($resaco,0,'fa63_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21561,'','".AddSlashes(pg_result($resaco,0,'fa63_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21560,'','".AddSlashes(pg_result($resaco,0,'fa63_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21562,'','".AddSlashes(pg_result($resaco,0,'fa63_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21563,'','".AddSlashes(pg_result($resaco,0,'fa63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3869,21564,'','".AddSlashes(pg_result($resaco,0,'fa63_movimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($fa63_sequencial=null) {
      $this->atualizacampos();
     $sql = " update dadoscompetenciasaida set ";
     $virgula = "";
     if(trim($this->fa63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_sequencial"])){
       $sql  .= $virgula." fa63_sequencial = $this->fa63_sequencial ";
       $virgula = ",";
       if(trim($this->fa63_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa63_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_integracaohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_integracaohorus"])){
       $sql  .= $virgula." fa63_integracaohorus = $this->fa63_integracaohorus ";
       $virgula = ",";
       if(trim($this->fa63_integracaohorus) == null ){
         $this->erro_sql = " Campo Integração Hórus não informado.";
         $this->erro_campo = "fa63_integracaohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_matestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_matestoqueinimei"])){
       $sql  .= $virgula." fa63_matestoqueinimei = $this->fa63_matestoqueinimei ";
       $virgula = ",";
       if(trim($this->fa63_matestoqueinimei) == null ){
         $this->erro_sql = " Campo Movimentação Estoque não informado.";
         $this->erro_campo = "fa63_matestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_unidade"])){
       $sql  .= $virgula." fa63_unidade = $this->fa63_unidade ";
       $virgula = ",";
       if(trim($this->fa63_unidade) == null ){
         $this->erro_sql = " Campo UPS não informado.";
         $this->erro_campo = "fa63_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_enviar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_enviar"])){
       $sql  .= $virgula." fa63_enviar = '$this->fa63_enviar' ";
       $virgula = ",";
       if(trim($this->fa63_enviar) == null ){
         $this->erro_sql = " Campo Enviar não informado.";
         $this->erro_campo = "fa63_enviar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_validadohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_validadohorus"])){
       $sql  .= $virgula." fa63_validadohorus = '$this->fa63_validadohorus' ";
       $virgula = ",";
       if(trim($this->fa63_validadohorus) == null ){
         $this->erro_sql = " Campo Validado Hórus não informado.";
         $this->erro_campo = "fa63_validadohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_catmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_catmat"])){
       $sql  .= $virgula." fa63_catmat = '$this->fa63_catmat' ";
       $virgula = ",";
       if(trim($this->fa63_catmat) == null ){
         $this->erro_sql = " Campo CATMAT não informado.";
         $this->erro_campo = "fa63_catmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_cnes"])){
       $sql  .= $virgula." fa63_cnes = '$this->fa63_cnes' ";
       $virgula = ",";
       if(trim($this->fa63_cnes) == null ){
         $this->erro_sql = " Campo CNES não informado.";
         $this->erro_campo = "fa63_cnes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_tipo"])){
       $sql  .= $virgula." fa63_tipo = '$this->fa63_tipo' ";
       $virgula = ",";
       if(trim($this->fa63_tipo) == null ){
         $this->erro_sql = " Campo Tipo do produto não informado.";
         $this->erro_campo = "fa63_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_valor"])){
       $sql  .= $virgula." fa63_valor = $this->fa63_valor ";
       $virgula = ",";
       if(trim($this->fa63_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "fa63_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_lote"])){
       $sql  .= $virgula." fa63_lote = '$this->fa63_lote' ";
       $virgula = ",";
     }
     if(trim($this->fa63_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa63_validade_dia"] !="") ){
       $sql  .= $virgula." fa63_validade = '$this->fa63_validade' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa63_validade_dia"])){
         $sql  .= $virgula." fa63_validade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fa63_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_quantidade"])){
       $sql  .= $virgula." fa63_quantidade = $this->fa63_quantidade ";
       $virgula = ",";
       if(trim($this->fa63_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "fa63_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa63_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa63_data_dia"] !="") ){
       $sql  .= $virgula." fa63_data = '$this->fa63_data' ";
       $virgula = ",";
       if(trim($this->fa63_data) == null ){
         $this->erro_sql = " Campo Data Saída não informado.";
         $this->erro_campo = "fa63_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa63_data_dia"])){
         $sql  .= $virgula." fa63_data = null ";
         $virgula = ",";
         if(trim($this->fa63_data) == null ){
           $this->erro_sql = " Campo Data Saída não informado.";
           $this->erro_campo = "fa63_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa63_movimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa63_movimentacao"])){
       $sql  .= $virgula." fa63_movimentacao = '$this->fa63_movimentacao' ";
       $virgula = ",";
       if(trim($this->fa63_movimentacao) == null ){
         $this->erro_sql = " Campo Tipos de movimentação não informado.";
         $this->erro_campo = "fa63_movimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa63_sequencial!=null){
       $sql .= " fa63_sequencial = $this->fa63_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa63_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21550,'$this->fa63_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_sequencial"]) || $this->fa63_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3869,21550,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_sequencial'))."','$this->fa63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_integracaohorus"]) || $this->fa63_integracaohorus != "")
             $resac = db_query("insert into db_acount values($acount,3869,21551,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_integracaohorus'))."','$this->fa63_integracaohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_matestoqueinimei"]) || $this->fa63_matestoqueinimei != "")
             $resac = db_query("insert into db_acount values($acount,3869,21552,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_matestoqueinimei'))."','$this->fa63_matestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_unidade"]) || $this->fa63_unidade != "")
             $resac = db_query("insert into db_acount values($acount,3869,21553,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_unidade'))."','$this->fa63_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_enviar"]) || $this->fa63_enviar != "")
             $resac = db_query("insert into db_acount values($acount,3869,21554,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_enviar'))."','$this->fa63_enviar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_validadohorus"]) || $this->fa63_validadohorus != "")
             $resac = db_query("insert into db_acount values($acount,3869,21555,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_validadohorus'))."','$this->fa63_validadohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_catmat"]) || $this->fa63_catmat != "")
             $resac = db_query("insert into db_acount values($acount,3869,21557,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_catmat'))."','$this->fa63_catmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_cnes"]) || $this->fa63_cnes != "")
             $resac = db_query("insert into db_acount values($acount,3869,21556,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_cnes'))."','$this->fa63_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_tipo"]) || $this->fa63_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3869,21558,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_tipo'))."','$this->fa63_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_valor"]) || $this->fa63_valor != "")
             $resac = db_query("insert into db_acount values($acount,3869,21559,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_valor'))."','$this->fa63_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_lote"]) || $this->fa63_lote != "")
             $resac = db_query("insert into db_acount values($acount,3869,21561,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_lote'))."','$this->fa63_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_validade"]) || $this->fa63_validade != "")
             $resac = db_query("insert into db_acount values($acount,3869,21560,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_validade'))."','$this->fa63_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_quantidade"]) || $this->fa63_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3869,21562,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_quantidade'))."','$this->fa63_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_data"]) || $this->fa63_data != "")
             $resac = db_query("insert into db_acount values($acount,3869,21563,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_data'))."','$this->fa63_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa63_movimentacao"]) || $this->fa63_movimentacao != "")
             $resac = db_query("insert into db_acount values($acount,3869,21564,'".AddSlashes(pg_result($resaco,$conresaco,'fa63_movimentacao'))."','$this->fa63_movimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Saída não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Saída não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($fa63_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa63_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21550,'$fa63_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3869,21550,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21551,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21552,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21553,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21554,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21555,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21557,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21556,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21558,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21559,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21561,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21560,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21562,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21563,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3869,21564,'','".AddSlashes(pg_result($resaco,$iresaco,'fa63_movimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from dadoscompetenciasaida
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa63_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa63_sequencial = $fa63_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Saída não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Saída não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dadoscompetenciasaida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($fa63_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from dadoscompetenciasaida ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = dadoscompetenciasaida.fa63_matestoqueinimei";
     $sql .= "      inner join integracaohorus  on  integracaohorus.fa59_codigo = dadoscompetenciasaida.fa63_integracaohorus";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = dadoscompetenciasaida.fa63_unidade";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = integracaohorus.fa59_usuario";
     $sql .= "      inner join situacaohorus  on  situacaohorus.fa60_sequencial = integracaohorus.fa59_situacaohorus";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = unidades.sd02_i_diretor and  cgm.z01_numcgm = unidades.sd02_i_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left  join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left  join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left  join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left  join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left  join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left  join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left  join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left  join sau_distritosanitario  on  sau_distritosanitario.s153_i_codigo = unidades.sd02_i_distrito";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa63_sequencial)) {
         $sql2 .= " where dadoscompetenciasaida.fa63_sequencial = $fa63_sequencial ";
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
  public function sql_query_file ($fa63_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from dadoscompetenciasaida ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa63_sequencial)){
        $sql2 .= " where dadoscompetenciasaida.fa63_sequencial = $fa63_sequencial ";
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

  public function sqlMedicamentosCompetenciaHorus($fa63_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos} ";
    $sql .= "   from dadoscompetenciasaida ";
    $sql .= "  inner join matestoqueinimei on matestoqueinimei.m82_codigo   = dadoscompetenciasaida.fa63_matestoqueinimei ";
    $sql .= "  inner join integracaohorus  on integracaohorus.fa59_codigo   = dadoscompetenciasaida.fa63_integracaohorus ";
    $sql .= "  inner join situacaohorus    on situacaohorus.fa60_sequencial = integracaohorus.fa59_situacaohorus ";
    $sql .= "  inner join unidades         on unidades.sd02_i_codigo        = dadoscompetenciasaida.fa63_unidade ";
    $sql .= "  inner join matestoqueitem   on matestoqueitem.m71_codlanc    = matestoqueinimei.m82_matestoqueitem ";
    $sql .= "  inner join matestoque       on matestoque.m70_codigo         = matestoqueitem.m71_codmatestoque ";
    $sql .= "  inner join matmater         on matmater.m60_codmater         = matestoque.m70_codmatmater ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa63_sequencial)){
        $sql2 .= " where dadoscompetenciasaida.fa63_sequencial = $fa63_sequencial ";
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
