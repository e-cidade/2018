<?
//MODULO: farmacia
//CLASSE DA ENTIDADE dadoscompetenciadispensacao
class cl_dadoscompetenciadispensacao {
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
   var $fa61_sequencial = 0;
   var $fa61_far_retiradaitens = 0;
   var $fa61_integracaohorus = 0;
   var $fa61_enviar = 'f';
   var $fa61_validadohorus = 'f';
   var $fa61_unidade = 0;
   var $fa61_cnes = null;
   var $fa61_catmat = null;
   var $fa61_tipo = null;
   var $fa61_valor = 0;
   var $fa61_validade_dia = null;
   var $fa61_validade_mes = null;
   var $fa61_validade_ano = null;
   var $fa61_validade = null;
   var $fa61_lote = null;
   var $fa61_quantidade = 0;
   var $fa61_dispensacao_dia = null;
   var $fa61_dispensacao_mes = null;
   var $fa61_dispensacao_ano = null;
   var $fa61_dispensacao = null;
   var $fa61_cns = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 fa61_sequencial = int4 = Código
                 fa61_far_retiradaitens = int4 = Código Retirada
                 fa61_integracaohorus = int4 = Integração Hórus
                 fa61_enviar = bool = Enviar
                 fa61_validadohorus = bool = Validado Hórus
                 fa61_unidade = int4 = UPS
                 fa61_cnes = varchar(10) = CNES
                 fa61_catmat = varchar(20) = CATMAT
                 fa61_tipo = char(1) = Tipo
                 fa61_valor = float4 = Valor
                 fa61_validade = date = Validade
                 fa61_lote = varchar(50) = Lote
                 fa61_quantidade = int4 = Quantidade
                 fa61_dispensacao = date = Dispensação
                 fa61_cns = varchar(15) = CNS
                 ";
   //funcao construtor da classe
   function cl_dadoscompetenciadispensacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dadoscompetenciadispensacao");
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
       $this->fa61_sequencial = ($this->fa61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_sequencial"]:$this->fa61_sequencial);
       $this->fa61_far_retiradaitens = ($this->fa61_far_retiradaitens == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_far_retiradaitens"]:$this->fa61_far_retiradaitens);
       $this->fa61_integracaohorus = ($this->fa61_integracaohorus == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_integracaohorus"]:$this->fa61_integracaohorus);
       $this->fa61_enviar = ($this->fa61_enviar == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa61_enviar"]:$this->fa61_enviar);
       $this->fa61_validadohorus = ($this->fa61_validadohorus == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa61_validadohorus"]:$this->fa61_validadohorus);
       $this->fa61_unidade = ($this->fa61_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_unidade"]:$this->fa61_unidade);
       $this->fa61_cnes = ($this->fa61_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_cnes"]:$this->fa61_cnes);
       $this->fa61_catmat = ($this->fa61_catmat == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_catmat"]:$this->fa61_catmat);
       $this->fa61_tipo = ($this->fa61_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_tipo"]:$this->fa61_tipo);
       $this->fa61_valor = ($this->fa61_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_valor"]:$this->fa61_valor);
       if($this->fa61_validade == ""){
         $this->fa61_validade_dia = ($this->fa61_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_validade_dia"]:$this->fa61_validade_dia);
         $this->fa61_validade_mes = ($this->fa61_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_validade_mes"]:$this->fa61_validade_mes);
         $this->fa61_validade_ano = ($this->fa61_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_validade_ano"]:$this->fa61_validade_ano);
         if($this->fa61_validade_dia != ""){
            $this->fa61_validade = $this->fa61_validade_ano."-".$this->fa61_validade_mes."-".$this->fa61_validade_dia;
         }
       }
       $this->fa61_lote = ($this->fa61_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_lote"]:$this->fa61_lote);
       $this->fa61_quantidade = ($this->fa61_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_quantidade"]:$this->fa61_quantidade);
       if($this->fa61_dispensacao == ""){
         $this->fa61_dispensacao_dia = ($this->fa61_dispensacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_dia"]:$this->fa61_dispensacao_dia);
         $this->fa61_dispensacao_mes = ($this->fa61_dispensacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_mes"]:$this->fa61_dispensacao_mes);
         $this->fa61_dispensacao_ano = ($this->fa61_dispensacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_ano"]:$this->fa61_dispensacao_ano);
         if($this->fa61_dispensacao_dia != ""){
            $this->fa61_dispensacao = $this->fa61_dispensacao_ano."-".$this->fa61_dispensacao_mes."-".$this->fa61_dispensacao_dia;
         }
       }
       $this->fa61_cns = ($this->fa61_cns == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_cns"]:$this->fa61_cns);
     }else{
       $this->fa61_sequencial = ($this->fa61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["fa61_sequencial"]:$this->fa61_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($fa61_sequencial){
      $this->atualizacampos();
     if($this->fa61_far_retiradaitens == null ){
       $this->erro_sql = " Campo Código Retirada não informado.";
       $this->erro_campo = "fa61_far_retiradaitens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_integracaohorus == null ){
       $this->erro_sql = " Campo Integração Hórus não informado.";
       $this->erro_campo = "fa61_integracaohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_enviar == null ){
       $this->erro_sql = " Campo Enviar não informado.";
       $this->erro_campo = "fa61_enviar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_validadohorus == null ){
       $this->erro_sql = " Campo Validado Hórus não informado.";
       $this->erro_campo = "fa61_validadohorus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_unidade == null ){
       $this->erro_sql = " Campo UPS não informado.";
       $this->erro_campo = "fa61_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_cnes == null ){
       $this->erro_sql = " Campo CNES não informado.";
       $this->erro_campo = "fa61_cnes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_catmat == null ){
       $this->erro_sql = " Campo CATMAT não informado.";
       $this->erro_campo = "fa61_catmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_tipo == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "fa61_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "fa61_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_validade == null ){
       $this->fa61_validade = "null";
     }
     if($this->fa61_quantidade == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "fa61_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa61_dispensacao == null ){
       $this->erro_sql = " Campo Dispensação não informado.";
       $this->erro_campo = "fa61_dispensacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa61_sequencial == "" || $fa61_sequencial == null ){
       $result = db_query("select nextval('dadoscompetenciadispensacao_fa61_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dadoscompetenciadispensacao_fa61_sequencial_seq do campo: fa61_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->fa61_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dadoscompetenciadispensacao_fa61_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa61_sequencial)){
         $this->erro_sql = " Campo fa61_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa61_sequencial = $fa61_sequencial;
       }
     }
     if(($this->fa61_sequencial == null) || ($this->fa61_sequencial == "") ){
       $this->erro_sql = " Campo fa61_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dadoscompetenciadispensacao(
                                       fa61_sequencial
                                      ,fa61_far_retiradaitens
                                      ,fa61_integracaohorus
                                      ,fa61_enviar
                                      ,fa61_validadohorus
                                      ,fa61_unidade
                                      ,fa61_cnes
                                      ,fa61_catmat
                                      ,fa61_tipo
                                      ,fa61_valor
                                      ,fa61_validade
                                      ,fa61_lote
                                      ,fa61_quantidade
                                      ,fa61_dispensacao
                                      ,fa61_cns
                       )
                values (
                                $this->fa61_sequencial
                               ,$this->fa61_far_retiradaitens
                               ,$this->fa61_integracaohorus
                               ,'$this->fa61_enviar'
                               ,'$this->fa61_validadohorus'
                               ,$this->fa61_unidade
                               ,'$this->fa61_cnes'
                               ,'$this->fa61_catmat'
                               ,'$this->fa61_tipo'
                               ,$this->fa61_valor
                               ,".($this->fa61_validade == "null" || $this->fa61_validade == ""?"null":"'".$this->fa61_validade."'")."
                               ,'$this->fa61_lote'
                               ,$this->fa61_quantidade
                               ,".($this->fa61_dispensacao == "null" || $this->fa61_dispensacao == ""?"null":"'".$this->fa61_dispensacao."'")."
                               ,'$this->fa61_cns'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Dispensação ($this->fa61_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Dispensação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Dispensação ($this->fa61_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa61_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa61_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21520,'$this->fa61_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3867,21520,'','".AddSlashes(pg_result($resaco,0,'fa61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21521,'','".AddSlashes(pg_result($resaco,0,'fa61_far_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21522,'','".AddSlashes(pg_result($resaco,0,'fa61_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21523,'','".AddSlashes(pg_result($resaco,0,'fa61_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21524,'','".AddSlashes(pg_result($resaco,0,'fa61_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21525,'','".AddSlashes(pg_result($resaco,0,'fa61_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21526,'','".AddSlashes(pg_result($resaco,0,'fa61_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21527,'','".AddSlashes(pg_result($resaco,0,'fa61_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21528,'','".AddSlashes(pg_result($resaco,0,'fa61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21529,'','".AddSlashes(pg_result($resaco,0,'fa61_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21530,'','".AddSlashes(pg_result($resaco,0,'fa61_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21531,'','".AddSlashes(pg_result($resaco,0,'fa61_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21532,'','".AddSlashes(pg_result($resaco,0,'fa61_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21533,'','".AddSlashes(pg_result($resaco,0,'fa61_dispensacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3867,21534,'','".AddSlashes(pg_result($resaco,0,'fa61_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($fa61_sequencial=null) {
      $this->atualizacampos();
     $sql = " update dadoscompetenciadispensacao set ";
     $virgula = "";
     if(trim($this->fa61_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_sequencial"])){
       $sql  .= $virgula." fa61_sequencial = $this->fa61_sequencial ";
       $virgula = ",";
       if(trim($this->fa61_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa61_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_far_retiradaitens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_far_retiradaitens"])){
       $sql  .= $virgula." fa61_far_retiradaitens = $this->fa61_far_retiradaitens ";
       $virgula = ",";
       if(trim($this->fa61_far_retiradaitens) == null ){
         $this->erro_sql = " Campo Código Retirada não informado.";
         $this->erro_campo = "fa61_far_retiradaitens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_integracaohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_integracaohorus"])){
       $sql  .= $virgula." fa61_integracaohorus = $this->fa61_integracaohorus ";
       $virgula = ",";
       if(trim($this->fa61_integracaohorus) == null ){
         $this->erro_sql = " Campo Integração Hórus não informado.";
         $this->erro_campo = "fa61_integracaohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_enviar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_enviar"])){
       $sql  .= $virgula." fa61_enviar = '$this->fa61_enviar' ";
       $virgula = ",";
       if(trim($this->fa61_enviar) == null ){
         $this->erro_sql = " Campo Enviar não informado.";
         $this->erro_campo = "fa61_enviar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_validadohorus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_validadohorus"])){
       $sql  .= $virgula." fa61_validadohorus = '$this->fa61_validadohorus' ";
       $virgula = ",";
       if(trim($this->fa61_validadohorus) == null ){
         $this->erro_sql = " Campo Validado Hórus não informado.";
         $this->erro_campo = "fa61_validadohorus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_unidade"])){
       $sql  .= $virgula." fa61_unidade = $this->fa61_unidade ";
       $virgula = ",";
       if(trim($this->fa61_unidade) == null ){
         $this->erro_sql = " Campo UPS não informado.";
         $this->erro_campo = "fa61_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_cnes"])){
       $sql  .= $virgula." fa61_cnes = '$this->fa61_cnes' ";
       $virgula = ",";
       if(trim($this->fa61_cnes) == null ){
         $this->erro_sql = " Campo CNES não informado.";
         $this->erro_campo = "fa61_cnes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_catmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_catmat"])){
       $sql  .= $virgula." fa61_catmat = '$this->fa61_catmat' ";
       $virgula = ",";
       if(trim($this->fa61_catmat) == null ){
         $this->erro_sql = " Campo CATMAT não informado.";
         $this->erro_campo = "fa61_catmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_tipo"])){
       $sql  .= $virgula." fa61_tipo = '$this->fa61_tipo' ";
       $virgula = ",";
       if(trim($this->fa61_tipo) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "fa61_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_valor"])){
       $sql  .= $virgula." fa61_valor = $this->fa61_valor ";
       $virgula = ",";
       if(trim($this->fa61_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "fa61_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa61_validade_dia"] !="") ){
       $sql  .= $virgula." fa61_validade = '$this->fa61_validade' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa61_validade_dia"])){
         $sql  .= $virgula." fa61_validade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fa61_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_lote"])){
       $sql  .= $virgula." fa61_lote = '$this->fa61_lote' ";
       $virgula = ",";
     }
     if(trim($this->fa61_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_quantidade"])){
       $sql  .= $virgula." fa61_quantidade = $this->fa61_quantidade ";
       $virgula = ",";
       if(trim($this->fa61_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "fa61_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa61_dispensacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_dia"] !="") ){
       $sql  .= $virgula." fa61_dispensacao = '$this->fa61_dispensacao' ";
       $virgula = ",";
       if(trim($this->fa61_dispensacao) == null ){
         $this->erro_sql = " Campo Dispensação não informado.";
         $this->erro_campo = "fa61_dispensacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao_dia"])){
         $sql  .= $virgula." fa61_dispensacao = null ";
         $virgula = ",";
         if(trim($this->fa61_dispensacao) == null ){
           $this->erro_sql = " Campo Dispensação não informado.";
           $this->erro_campo = "fa61_dispensacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa61_cns)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa61_cns"])){
       $sql  .= $virgula." fa61_cns = '$this->fa61_cns' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($fa61_sequencial!=null){
       $sql .= " fa61_sequencial = $this->fa61_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa61_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21520,'$this->fa61_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_sequencial"]) || $this->fa61_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3867,21520,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_sequencial'))."','$this->fa61_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_far_retiradaitens"]) || $this->fa61_far_retiradaitens != "")
             $resac = db_query("insert into db_acount values($acount,3867,21521,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_far_retiradaitens'))."','$this->fa61_far_retiradaitens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_integracaohorus"]) || $this->fa61_integracaohorus != "")
             $resac = db_query("insert into db_acount values($acount,3867,21522,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_integracaohorus'))."','$this->fa61_integracaohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_enviar"]) || $this->fa61_enviar != "")
             $resac = db_query("insert into db_acount values($acount,3867,21523,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_enviar'))."','$this->fa61_enviar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_validadohorus"]) || $this->fa61_validadohorus != "")
             $resac = db_query("insert into db_acount values($acount,3867,21524,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_validadohorus'))."','$this->fa61_validadohorus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_unidade"]) || $this->fa61_unidade != "")
             $resac = db_query("insert into db_acount values($acount,3867,21525,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_unidade'))."','$this->fa61_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_cnes"]) || $this->fa61_cnes != "")
             $resac = db_query("insert into db_acount values($acount,3867,21526,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_cnes'))."','$this->fa61_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_catmat"]) || $this->fa61_catmat != "")
             $resac = db_query("insert into db_acount values($acount,3867,21527,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_catmat'))."','$this->fa61_catmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_tipo"]) || $this->fa61_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3867,21528,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_tipo'))."','$this->fa61_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_valor"]) || $this->fa61_valor != "")
             $resac = db_query("insert into db_acount values($acount,3867,21529,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_valor'))."','$this->fa61_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_validade"]) || $this->fa61_validade != "")
             $resac = db_query("insert into db_acount values($acount,3867,21530,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_validade'))."','$this->fa61_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_lote"]) || $this->fa61_lote != "")
             $resac = db_query("insert into db_acount values($acount,3867,21531,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_lote'))."','$this->fa61_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_quantidade"]) || $this->fa61_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3867,21532,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_quantidade'))."','$this->fa61_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_dispensacao"]) || $this->fa61_dispensacao != "")
             $resac = db_query("insert into db_acount values($acount,3867,21533,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_dispensacao'))."','$this->fa61_dispensacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa61_cns"]) || $this->fa61_cns != "")
             $resac = db_query("insert into db_acount values($acount,3867,21534,'".AddSlashes(pg_result($resaco,$conresaco,'fa61_cns'))."','$this->fa61_cns',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Dispensação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Dispensação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($fa61_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa61_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21520,'$fa61_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3867,21520,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21521,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_far_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21522,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_integracaohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21523,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_enviar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21524,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_validadohorus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21525,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21526,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21527,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_catmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21528,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21529,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21530,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21531,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21532,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21533,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_dispensacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3867,21534,'','".AddSlashes(pg_result($resaco,$iresaco,'fa61_cns'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from dadoscompetenciadispensacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa61_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa61_sequencial = $fa61_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Dispensação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados Dispensação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa61_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dadoscompetenciadispensacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($fa61_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from dadoscompetenciadispensacao ";
     $sql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_codigo = dadoscompetenciadispensacao.fa61_far_retiradaitens";
     $sql .= "      inner join integracaohorus  on  integracaohorus.fa59_codigo = dadoscompetenciadispensacao.fa61_integracaohorus";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = dadoscompetenciadispensacao.fa61_unidade";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada";
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
       if (!empty($fa61_sequencial)) {
         $sql2 .= " where dadoscompetenciadispensacao.fa61_sequencial = $fa61_sequencial ";
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
  public function sql_query_file ($fa61_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from dadoscompetenciadispensacao ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($fa61_sequencial)){
        $sql2 .= " where dadoscompetenciadispensacao.fa61_sequencial = $fa61_sequencial ";
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

  public function sqlMedicamentosCompetenciaHorus($fa61_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = " select {$campos} ";

     $sql .= "   from dadoscompetenciadispensacao ";
     $sql .= "  inner join far_retiradaitens on far_retiradaitens.fa06_i_codigo = dadoscompetenciadispensacao.fa61_far_retiradaitens ";
     $sql .= "  inner join integracaohorus   on integracaohorus.fa59_codigo     = dadoscompetenciadispensacao.fa61_integracaohorus ";
     $sql .= "  inner join situacaohorus     on situacaohorus.fa60_sequencial   = integracaohorus.fa59_situacaohorus";
     $sql .= "  inner join unidades          on unidades.sd02_i_codigo          = dadoscompetenciadispensacao.fa61_unidade ";
     $sql .= "  inner join far_matersaude    on far_matersaude.fa01_i_codigo    = far_retiradaitens.fa06_i_matersaude ";
     $sql .= "  inner join matmater          on matmater.m60_codmater           = far_matersaude.fa01_i_codmater ";
     $sql .= "  inner join far_retirada      on far_retirada.fa04_i_codigo      = far_retiradaitens.fa06_i_retirada ";
     $sql .= "  inner join cgs_und           on cgs_und.z01_i_cgsund            = far_retirada.fa04_i_cgsund ";

     $sql2 = "";
     if (empty($dbwhere)) {

       if (!empty($fa61_sequencial)) {
         $sql2 .= " where dadoscompetenciadispensacao.fa61_sequencial = $fa61_sequencial ";
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
