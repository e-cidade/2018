<?
//MODULO: farmacia
//CLASSE DA ENTIDADE dadoscompetenciaentrada
class cl_dadoscompetenciaentrada {
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
   var $fa62_sequencial = 0;
   var $fa62_integracaohorus = 0;
   var $fa62_matestoqueinimei = 0;
   var $fa62_unidade = 0;
   var $fa62_enviar = 'f';
   var $fa62_validadohorus = 'f';
   var $fa62_cnes = null;
   var $fa62_catmat = null;
   var $fa62_tipo = null;
   var $fa62_valor = 0;
   var $fa62_validade_dia = null;
   var $fa62_validade_mes = null;
   var $fa62_validade_ano = null;
   var $fa62_validade = null;
   var $fa62_lote = null;
   var $fa62_quantidade = 0;
   var $fa62_recebimento_dia = null;
   var $fa62_recebimento_mes = null;
   var $fa62_recebimento_ano = null;
   var $fa62_recebimento = null;
   var $fa62_movimentacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 fa62_sequencial = int4 = Código
                 fa62_integracaohorus = int4 = Integração Hórus
                 fa62_matestoqueinimei = int4 = Movimentação Estoque
                 fa62_unidade = int4 = UPS
                 fa62_enviar = bool = Enviar
                 fa62_validadohorus = bool = Validado Hórus
                 fa62_cnes = varchar(10) = CNES
                 fa62_catmat = varchar(20) = CATMAT
                 fa62_tipo = char(1) = Tipo do produto
                 fa62_valor = float4 = Valor
                 fa62_validade = date = Validade
                 fa62_lote = varchar(50) = Lote
                 fa62_quantidade = int4 = Quantidade
                 fa62_recebimento = date = Recebimento
                 fa62_movimentacao = varchar(15) = Tipo Movimentação
                 ";
   //funcao construtor da classe
   function cl_dadoscompetenciaentrada() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dadoscompetenciaentrada");
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
       $this->fa62_sequencial = ($this->fa62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_sequencial"]:$this->fa62_sequencial);
       $this->fa62_integracaohorus = ($this->fa62_integracaohorus == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_integracaohorus"]:$this->fa62_integracaohorus);
       $this->fa62_matestoqueinimei = ($this->fa62_matestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_matestoqueinimei"]:$this->fa62_matestoqueinimei);
       $this->fa62_unidade = ($this->fa62_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_unidade"]:$this->fa62_unidade);
       $this->fa62_enviar = ($this->fa62_enviar == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa62_enviar"]:$this->fa62_enviar);
       $this->fa62_validadohorus = ($this->fa62_validadohorus == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa62_validadohorus"]:$this->fa62_validadohorus);
       $this->fa62_cnes = ($this->fa62_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_cnes"]:$this->fa62_cnes);
       $this->fa62_catmat = ($this->fa62_catmat == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_catmat"]:$this->fa62_catmat);
       $this->fa62_tipo = ($this->fa62_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_tipo"]:$this->fa62_tipo);
       $this->fa62_valor = ($this->fa62_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_valor"]:$this->fa62_valor);
       if($this->fa62_validade == ""){
         $this->fa62_validade_dia = ($this->fa62_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_validade_dia"]:$this->fa62_validade_dia);
         $this->fa62_validade_mes = ($this->fa62_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_validade_mes"]:$this->fa62_validade_mes);
         $this->fa62_validade_ano = ($this->fa62_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_validade_ano"]:$this->fa62_validade_ano);
         if($this->fa62_validade_dia != ""){
            $this->fa62_validade = $this->fa62_validade_ano."-".$this->fa62_validade_mes."-".$this->fa62_validade_dia;
         }
       }
       $this->fa62_lote = ($this->fa62_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_lote"]:$this->fa62_lote);
       $this->fa62_quantidade = ($this->fa62_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_quantidade"]:$this->fa62_quantidade);
       if($this->fa62_recebimento == ""){
         $this->fa62_recebimento_dia = ($this->fa62_recebimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_dia"]:$this->fa62_recebimento_dia);
         $this->fa62_recebimento_mes = ($this->fa62_recebimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_mes"]:$this->fa62_recebimento_mes);
         $this->fa62_recebimento_ano = ($this->fa62_recebimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_ano"]:$this->fa62_recebimento_ano);
         if($this->fa62_recebimento_dia != ""){
            $this->fa62_recebimento = $this->fa62_recebimento_ano."-".$this->fa62_recebimento_mes."-".$this->fa62_recebimento_dia;
         }
       }
       $this->fa62_movimentacao = ($this->fa62_movimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_movimentacao"]:$this->fa62_movimentacao);
     }else{
       $this->fa62_sequencial = ($this->fa62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa62_sequencial"]:$this->fa62_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($fa62_sequencial){
      $this->atualizacampos();
     if($this->fa62_integracaohorus == null ){
       $this->erro_sql = " Campo Integração Hórus não informado.";
       $this->erro_campo = "fa62_integracaohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_matestoqueinimei == null ){
       $this->erro_sql = " Campo Movimentação Estoque não informado.";
       $this->erro_campo = "fa62_matestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_unidade == null ){
       $this->erro_sql = " Campo UPS não informado.";
       $this->erro_campo = "fa62_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_enviar == null ){
       $this->erro_sql = " Campo Enviar não informado.";
       $this->erro_campo = "fa62_enviar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_validadohorus == null ){
       $this->erro_sql = " Campo Validado Hórus não informado.";
       $this->erro_campo = "fa62_validadohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_cnes == null ){
       $this->erro_sql = " Campo CNES não informado.";
       $this->erro_campo = "fa62_cnes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_catmat == null ){
       $this->erro_sql = " Campo CATMAT não informado.";
       $this->erro_campo = "fa62_catmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_tipo == null ){
       $this->erro_sql = " Campo Tipo do produto não informado.";
       $this->erro_campo = "fa62_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "fa62_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_validade == null ){
       $this->fa62_validade = "null";
     }
     if($this->fa62_quantidade == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "fa62_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_recebimento == null ){
       $this->erro_sql = " Campo Recebimento não informado.";
       $this->erro_campo = "fa62_recebimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa62_movimentacao == null ){
       $this->erro_sql = " Campo Tipo Movimentação não informado.";
       $this->erro_campo = "fa62_movimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa62_sequencial == "" || $fa62_sequencial == null ){
       $result = db_query("select nextval('dadoscompetenciaentrada_fa62_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dadoscompetenciaentrada_fa62_sequencial_seq do campo: fa62_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->fa62_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dadoscompetenciaentrada_fa62_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa62_sequencial)){
         $this->erro_sql = " Campo fa62_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa62_sequencial = $fa62_sequencial;
       }
     }
     if(($this->fa62_sequencial == null) || ($this->fa62_sequencial == "") ){
       $this->erro_sql = " Campo fa62_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dadoscompetenciaentrada(
                                       fa62_sequencial
                                      ,fa62_integracaohorus
                                      ,fa62_matestoqueinimei
                                      ,fa62_unidade
                                      ,fa62_enviar
                                      ,fa62_validadohorus
                                      ,fa62_cnes
                                      ,fa62_catmat
                                      ,fa62_tipo
                                      ,fa62_valor
                                      ,fa62_validade
                                      ,fa62_lote
                                      ,fa62_quantidade
                                      ,fa62_recebimento
                                      ,fa62_movimentacao
                       )
                values (
                                $this->fa62_sequencial
                               ,$this->fa62_integracaohorus
                               ,$this->fa62_matestoqueinimei
                               ,$this->fa62_unidade
                               ,'$this->fa62_enviar'
                               ,'$this->fa62_validadohorus'
                               ,'$this->fa62_cnes'
                               ,'$this->fa62_catmat'
                               ,'$this->fa62_tipo'
                               ,$this->fa62_valor
                               ,".($this->fa62_validade == "null" || $this->fa62_validade == ""?"null":"'".$this->fa62_validade."'")."
                               ,'$this->fa62_lote'
                               ,$this->fa62_quantidade
                               ,".($this->fa62_recebimento == "null" || $this->fa62_recebimento == ""?"null":"'".$this->fa62_recebimento."'")."
                               ,'$this->fa62_movimentacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Entrada ($this->fa62_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Entrada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Entrada ($this->fa62_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa62_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa62_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21535,'$this->fa62_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3868,21535,'','".AddSlashes(pg_result($resaco,0,'fa62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21536,'','".AddSlashes(pg_result($resaco,0,'fa62_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21537,'','".AddSlashes(pg_result($resaco,0,'fa62_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21538,'','".AddSlashes(pg_result($resaco,0,'fa62_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21539,'','".AddSlashes(pg_result($resaco,0,'fa62_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21540,'','".AddSlashes(pg_result($resaco,0,'fa62_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21541,'','".AddSlashes(pg_result($resaco,0,'fa62_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21542,'','".AddSlashes(pg_result($resaco,0,'fa62_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21543,'','".AddSlashes(pg_result($resaco,0,'fa62_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21544,'','".AddSlashes(pg_result($resaco,0,'fa62_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21545,'','".AddSlashes(pg_result($resaco,0,'fa62_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21546,'','".AddSlashes(pg_result($resaco,0,'fa62_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21547,'','".AddSlashes(pg_result($resaco,0,'fa62_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21548,'','".AddSlashes(pg_result($resaco,0,'fa62_recebimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3868,21549,'','".AddSlashes(pg_result($resaco,0,'fa62_movimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($fa62_sequencial=null) {
      $this->atualizacampos();
     $sql = " update dadoscompetenciaentrada set ";
     $virgula = "";
     if(trim($this->fa62_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_sequencial"])){
       $sql  .= $virgula." fa62_sequencial = $this->fa62_sequencial ";
       $virgula = ",";
       if(trim($this->fa62_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa62_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_integracaohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_integracaohorus"])){
       $sql  .= $virgula." fa62_integracaohorus = $this->fa62_integracaohorus ";
       $virgula = ",";
       if(trim($this->fa62_integracaohorus) == null ){
         $this->erro_sql = " Campo Integração Hórus não informado.";
         $this->erro_campo = "fa62_integracaohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_matestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_matestoqueinimei"])){
       $sql  .= $virgula." fa62_matestoqueinimei = $this->fa62_matestoqueinimei ";
       $virgula = ",";
       if(trim($this->fa62_matestoqueinimei) == null ){
         $this->erro_sql = " Campo Movimentação Estoque não informado.";
         $this->erro_campo = "fa62_matestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_unidade"])){
       $sql  .= $virgula." fa62_unidade = $this->fa62_unidade ";
       $virgula = ",";
       if(trim($this->fa62_unidade) == null ){
         $this->erro_sql = " Campo UPS não informado.";
         $this->erro_campo = "fa62_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_enviar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_enviar"])){
       $sql  .= $virgula." fa62_enviar = '$this->fa62_enviar' ";
       $virgula = ",";
       if(trim($this->fa62_enviar) == null ){
         $this->erro_sql = " Campo Enviar não informado.";
         $this->erro_campo = "fa62_enviar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_validadohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_validadohorus"])){
       $sql  .= $virgula." fa62_validadohorus = '$this->fa62_validadohorus' ";
       $virgula = ",";
       if(trim($this->fa62_validadohorus) == null ){
         $this->erro_sql = " Campo Validado Hórus não informado.";
         $this->erro_campo = "fa62_validadohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_cnes"])){
       $sql  .= $virgula." fa62_cnes = '$this->fa62_cnes' ";
       $virgula = ",";
       if(trim($this->fa62_cnes) == null ){
         $this->erro_sql = " Campo CNES não informado.";
         $this->erro_campo = "fa62_cnes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_catmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_catmat"])){
       $sql  .= $virgula." fa62_catmat = '$this->fa62_catmat' ";
       $virgula = ",";
       if(trim($this->fa62_catmat) == null ){
         $this->erro_sql = " Campo CATMAT não informado.";
         $this->erro_campo = "fa62_catmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_tipo"])){
       $sql  .= $virgula." fa62_tipo = '$this->fa62_tipo' ";
       $virgula = ",";
       if(trim($this->fa62_tipo) == null ){
         $this->erro_sql = " Campo Tipo do produto não informado.";
         $this->erro_campo = "fa62_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_valor"])){
       $sql  .= $virgula." fa62_valor = $this->fa62_valor ";
       $virgula = ",";
       if(trim($this->fa62_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "fa62_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa62_validade_dia"] !="") ){
       $sql  .= $virgula." fa62_validade = '$this->fa62_validade' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa62_validade_dia"])){
         $sql  .= $virgula." fa62_validade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fa62_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_lote"])){
       $sql  .= $virgula." fa62_lote = '$this->fa62_lote' ";
       $virgula = ",";
     }
     if(trim($this->fa62_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_quantidade"])){
       $sql  .= $virgula." fa62_quantidade = $this->fa62_quantidade ";
       $virgula = ",";
       if(trim($this->fa62_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "fa62_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa62_recebimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_dia"] !="") ){
       $sql  .= $virgula." fa62_recebimento = '$this->fa62_recebimento' ";
       $virgula = ",";
       if(trim($this->fa62_recebimento) == null ){
         $this->erro_sql = " Campo Recebimento não informado.";
         $this->erro_campo = "fa62_recebimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa62_recebimento_dia"])){
         $sql  .= $virgula." fa62_recebimento = null ";
         $virgula = ",";
         if(trim($this->fa62_recebimento) == null ){
           $this->erro_sql = " Campo Recebimento não informado.";
           $this->erro_campo = "fa62_recebimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa62_movimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa62_movimentacao"])){
       $sql  .= $virgula." fa62_movimentacao = '$this->fa62_movimentacao' ";
       $virgula = ",";
       if(trim($this->fa62_movimentacao) == null ){
         $this->erro_sql = " Campo Tipo Movimentação não informado.";
         $this->erro_campo = "fa62_movimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa62_sequencial!=null){
       $sql .= " fa62_sequencial = $this->fa62_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa62_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21535,'$this->fa62_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_sequencial"]) || $this->fa62_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3868,21535,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_sequencial'))."','$this->fa62_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_integracaohorus"]) || $this->fa62_integracaohorus != "")
             $resac = db_query("insert into db_acount values($acount,3868,21536,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_integracaohorus'))."','$this->fa62_integracaohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_matestoqueinimei"]) || $this->fa62_matestoqueinimei != "")
             $resac = db_query("insert into db_acount values($acount,3868,21537,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_matestoqueinimei'))."','$this->fa62_matestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_unidade"]) || $this->fa62_unidade != "")
             $resac = db_query("insert into db_acount values($acount,3868,21538,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_unidade'))."','$this->fa62_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_enviar"]) || $this->fa62_enviar != "")
             $resac = db_query("insert into db_acount values($acount,3868,21539,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_enviar'))."','$this->fa62_enviar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_validadohorus"]) || $this->fa62_validadohorus != "")
             $resac = db_query("insert into db_acount values($acount,3868,21540,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_validadohorus'))."','$this->fa62_validadohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_cnes"]) || $this->fa62_cnes != "")
             $resac = db_query("insert into db_acount values($acount,3868,21541,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_cnes'))."','$this->fa62_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_catmat"]) || $this->fa62_catmat != "")
             $resac = db_query("insert into db_acount values($acount,3868,21542,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_catmat'))."','$this->fa62_catmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_tipo"]) || $this->fa62_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3868,21543,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_tipo'))."','$this->fa62_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_valor"]) || $this->fa62_valor != "")
             $resac = db_query("insert into db_acount values($acount,3868,21544,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_valor'))."','$this->fa62_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_validade"]) || $this->fa62_validade != "")
             $resac = db_query("insert into db_acount values($acount,3868,21545,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_validade'))."','$this->fa62_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_lote"]) || $this->fa62_lote != "")
             $resac = db_query("insert into db_acount values($acount,3868,21546,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_lote'))."','$this->fa62_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_quantidade"]) || $this->fa62_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3868,21547,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_quantidade'))."','$this->fa62_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_recebimento"]) || $this->fa62_recebimento != "")
             $resac = db_query("insert into db_acount values($acount,3868,21548,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_recebimento'))."','$this->fa62_recebimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa62_movimentacao"]) || $this->fa62_movimentacao != "")
             $resac = db_query("insert into db_acount values($acount,3868,21549,'".AddSlashes(pg_result($resaco,$conresaco,'fa62_movimentacao'))."','$this->fa62_movimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Entrada não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Entrada não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($fa62_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa62_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21535,'$fa62_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3868,21535,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21536,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21537,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21538,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21539,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21540,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21541,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21542,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21543,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21544,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21545,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21546,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21547,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21548,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_recebimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3868,21549,'','".AddSlashes(pg_result($resaco,$iresaco,'fa62_movimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from dadoscompetenciaentrada
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa62_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa62_sequencial = $fa62_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Entrada não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Entrada não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa62_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dadoscompetenciaentrada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($fa62_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from dadoscompetenciaentrada ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = dadoscompetenciaentrada.fa62_matestoqueinimei";
     $sql .= "      inner join integracaohorus  on  integracaohorus.fa59_codigo = dadoscompetenciaentrada.fa62_integracaohorus";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = dadoscompetenciaentrada.fa62_unidade";
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
       if (!empty($fa62_sequencial)) {
         $sql2 .= " where dadoscompetenciaentrada.fa62_sequencial = $fa62_sequencial ";
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
  public function sql_query_file ($fa62_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from dadoscompetenciaentrada ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa62_sequencial)){
        $sql2 .= " where dadoscompetenciaentrada.fa62_sequencial = $fa62_sequencial ";
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

  public function sqlMedicamentosCompetenciaHorus($fa62_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos} ";
    $sql .= "   from dadoscompetenciaentrada ";
    $sql .= "  inner join matestoqueinimei on matestoqueinimei.m82_codigo   = dadoscompetenciaentrada.fa62_matestoqueinimei ";
    $sql .= "  inner join integracaohorus  on integracaohorus.fa59_codigo   = dadoscompetenciaentrada.fa62_integracaohorus ";
    $sql .= "  inner join situacaohorus    on situacaohorus.fa60_sequencial = integracaohorus.fa59_situacaohorus ";
    $sql .= "  inner join unidades         on unidades.sd02_i_codigo        = dadoscompetenciaentrada.fa62_unidade ";
    $sql .= "  inner join matestoqueitem   on matestoqueitem.m71_codlanc    = matestoqueinimei.m82_matestoqueitem ";
    $sql .= "  inner join matestoque       on matestoque.m70_codigo         = matestoqueitem.m71_codmatestoque ";
    $sql .= "  inner join matmater         on matmater.m60_codmater         = matestoque.m70_codmatmater ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa62_sequencial)){
        $sql2 .= " where dadoscompetenciaentrada.fa62_sequencial = $fa62_sequencial ";
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
