<?
//MODULO: escola
//CLASSE DA ENTIDADE rechumanoausente
class cl_rechumanoausente {
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
   var $ed348_sequencial = 0;
   var $ed348_rechumano = 0;
   var $ed348_tipoausencia = 0;
   var $ed348_usuario = 0;
   var $ed348_inicio_dia = null;
   var $ed348_inicio_mes = null;
   var $ed348_inicio_ano = null;
   var $ed348_inicio = null;
   var $ed348_final_dia = null;
   var $ed348_final_mes = null;
   var $ed348_final_ano = null;
   var $ed348_final = null;
   var $ed348_observacao = null;
   var $ed348_escola = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed348_sequencial = int4 = Sequencial
                 ed348_rechumano = int4 = Recursos Humanos
                 ed348_tipoausencia = int4 = Tipo de Ausência
                 ed348_usuario = int4 = Usuário
                 ed348_inicio = date = Data de Início
                 ed348_final = date = Data Final
                 ed348_observacao = text = Observação
                 ed348_escola = int4 = Escola
                 ";
   //funcao construtor da classe
   function cl_rechumanoausente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanoausente");
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
       $this->ed348_sequencial = ($this->ed348_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_sequencial"]:$this->ed348_sequencial);
       $this->ed348_rechumano = ($this->ed348_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_rechumano"]:$this->ed348_rechumano);
       $this->ed348_tipoausencia = ($this->ed348_tipoausencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_tipoausencia"]:$this->ed348_tipoausencia);
       $this->ed348_usuario = ($this->ed348_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_usuario"]:$this->ed348_usuario);
       if($this->ed348_inicio == ""){
         $this->ed348_inicio_dia = ($this->ed348_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_inicio_dia"]:$this->ed348_inicio_dia);
         $this->ed348_inicio_mes = ($this->ed348_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_inicio_mes"]:$this->ed348_inicio_mes);
         $this->ed348_inicio_ano = ($this->ed348_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_inicio_ano"]:$this->ed348_inicio_ano);
         if($this->ed348_inicio_dia != ""){
            $this->ed348_inicio = $this->ed348_inicio_ano."-".$this->ed348_inicio_mes."-".$this->ed348_inicio_dia;
         }
       }
       if($this->ed348_final == ""){
         $this->ed348_final_dia = ($this->ed348_final_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_final_dia"]:$this->ed348_final_dia);
         $this->ed348_final_mes = ($this->ed348_final_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_final_mes"]:$this->ed348_final_mes);
         $this->ed348_final_ano = ($this->ed348_final_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_final_ano"]:$this->ed348_final_ano);
         if($this->ed348_final_dia != ""){
            $this->ed348_final = $this->ed348_final_ano."-".$this->ed348_final_mes."-".$this->ed348_final_dia;
         }
       }
       $this->ed348_observacao = ($this->ed348_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_observacao"]:$this->ed348_observacao);
       $this->ed348_escola = ($this->ed348_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_escola"]:$this->ed348_escola);
     }else{
       $this->ed348_sequencial = ($this->ed348_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed348_sequencial"]:$this->ed348_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed348_sequencial){
      $this->atualizacampos();
     if($this->ed348_rechumano == null ){
       $this->erro_sql = " Campo Recursos Humanos não informado.";
       $this->erro_campo = "ed348_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed348_tipoausencia == null ){
       $this->erro_sql = " Campo Tipo de Ausência não informado.";
       $this->erro_campo = "ed348_tipoausencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed348_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ed348_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed348_inicio == null ){
       $this->erro_sql = " Campo Data de Início não informado.";
       $this->erro_campo = "ed348_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed348_final == null ){
       $this->ed348_final = "null";
     }
     if($this->ed348_escola == null ){
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed348_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed348_sequencial == "" || $ed348_sequencial == null ){
       $result = db_query("select nextval('rechumanoausente_ed348_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanoausente_ed348_sequencial_seq do campo: ed348_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed348_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rechumanoausente_ed348_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed348_sequencial)){
         $this->erro_sql = " Campo ed348_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed348_sequencial = $ed348_sequencial;
       }
     }
     if(($this->ed348_sequencial == null) || ($this->ed348_sequencial == "") ){
       $this->erro_sql = " Campo ed348_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanoausente(
                                       ed348_sequencial
                                      ,ed348_rechumano
                                      ,ed348_tipoausencia
                                      ,ed348_usuario
                                      ,ed348_inicio
                                      ,ed348_final
                                      ,ed348_observacao
                                      ,ed348_escola
                       )
                values (
                                $this->ed348_sequencial
                               ,$this->ed348_rechumano
                               ,$this->ed348_tipoausencia
                               ,$this->ed348_usuario
                               ,".($this->ed348_inicio == "null" || $this->ed348_inicio == ""?"null":"'".$this->ed348_inicio."'")."
                               ,".($this->ed348_final == "null" || $this->ed348_final == ""?"null":"'".$this->ed348_final."'")."
                               ,'$this->ed348_observacao'
                               ,$this->ed348_escola
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de Ausência Recursos Humanos ($this->ed348_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de Ausência Recursos Humanos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de Ausência Recursos Humanos ($this->ed348_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed348_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed348_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21619,'$this->ed348_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3884,21619,'','".AddSlashes(pg_result($resaco,0,'ed348_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21620,'','".AddSlashes(pg_result($resaco,0,'ed348_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21621,'','".AddSlashes(pg_result($resaco,0,'ed348_tipoausencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21622,'','".AddSlashes(pg_result($resaco,0,'ed348_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21623,'','".AddSlashes(pg_result($resaco,0,'ed348_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21624,'','".AddSlashes(pg_result($resaco,0,'ed348_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21625,'','".AddSlashes(pg_result($resaco,0,'ed348_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3884,21626,'','".AddSlashes(pg_result($resaco,0,'ed348_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed348_sequencial=null) {
      $this->atualizacampos();
     $sql = " update rechumanoausente set ";
     $virgula = "";
     if(trim($this->ed348_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_sequencial"])){
       $sql  .= $virgula." ed348_sequencial = $this->ed348_sequencial ";
       $virgula = ",";
       if(trim($this->ed348_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ed348_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed348_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_rechumano"])){
       $sql  .= $virgula." ed348_rechumano = $this->ed348_rechumano ";
       $virgula = ",";
       if(trim($this->ed348_rechumano) == null ){
         $this->erro_sql = " Campo Recursos Humanos não informado.";
         $this->erro_campo = "ed348_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed348_tipoausencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_tipoausencia"])){
       $sql  .= $virgula." ed348_tipoausencia = $this->ed348_tipoausencia ";
       $virgula = ",";
       if(trim($this->ed348_tipoausencia) == null ){
         $this->erro_sql = " Campo Tipo de Ausência não informado.";
         $this->erro_campo = "ed348_tipoausencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed348_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_usuario"])){
       $sql  .= $virgula." ed348_usuario = $this->ed348_usuario ";
       $virgula = ",";
       if(trim($this->ed348_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ed348_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed348_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed348_inicio_dia"] !="") ){
       $sql  .= $virgula." ed348_inicio = '$this->ed348_inicio' ";
       $virgula = ",";
       if(trim($this->ed348_inicio) == null ){
         $this->erro_sql = " Campo Data de Início não informado.";
         $this->erro_campo = "ed348_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed348_inicio_dia"])){
         $sql  .= $virgula." ed348_inicio = null ";
         $virgula = ",";
         if(trim($this->ed348_inicio) == null ){
           $this->erro_sql = " Campo Data de Início não informado.";
           $this->erro_campo = "ed348_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if ( trim($this->ed348_final) != ""  ) {

       $sql  .= $virgula." ed348_final = '$this->ed348_final' ";
       $virgula = ",";
     } else {

        $sql     .= $virgula." ed348_final = null ";
        $virgula  = ",";
     }
     if(trim($this->ed348_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_observacao"])){
       $sql  .= $virgula." ed348_observacao = '$this->ed348_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ed348_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed348_escola"])){
       $sql  .= $virgula." ed348_escola = $this->ed348_escola ";
       $virgula = ",";
       if(trim($this->ed348_escola) == null ){
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed348_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed348_sequencial!=null){
       $sql .= " ed348_sequencial = $this->ed348_sequencial";
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed348_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21619,'$this->ed348_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_sequencial"]) || $this->ed348_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3884,21619,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_sequencial'))."','$this->ed348_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_rechumano"]) || $this->ed348_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,3884,21620,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_rechumano'))."','$this->ed348_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_tipoausencia"]) || $this->ed348_tipoausencia != "")
             $resac = db_query("insert into db_acount values($acount,3884,21621,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_tipoausencia'))."','$this->ed348_tipoausencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_usuario"]) || $this->ed348_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3884,21622,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_usuario'))."','$this->ed348_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_inicio"]) || $this->ed348_inicio != "")
             $resac = db_query("insert into db_acount values($acount,3884,21623,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_inicio'))."','$this->ed348_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_final"]) || $this->ed348_final != "")
             $resac = db_query("insert into db_acount values($acount,3884,21624,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_final'))."','$this->ed348_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_observacao"]) || $this->ed348_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3884,21625,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_observacao'))."','$this->ed348_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed348_escola"]) || $this->ed348_escola != "")
             $resac = db_query("insert into db_acount values($acount,3884,21626,'".AddSlashes(pg_result($resaco,$conresaco,'ed348_escola'))."','$this->ed348_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Ausência Recursos Humanos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed348_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Ausência Recursos Humanos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed348_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed348_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed348_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed348_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21619,'$ed348_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3884,21619,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21620,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21621,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_tipoausencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21622,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21623,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21624,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21625,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3884,21626,'','".AddSlashes(pg_result($resaco,$iresaco,'ed348_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rechumanoausente
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed348_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed348_sequencial = $ed348_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de Ausência Recursos Humanos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed348_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de Ausência Recursos Humanos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed348_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed348_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rechumanoausente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed348_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rechumanoausente ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rechumanoausente.ed348_usuario";
     $sql .= "      inner join tipoausencia  on  tipoausencia.ed320_sequencial = rechumanoausente.ed348_tipoausencia";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoausente.ed348_escola";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoausente.ed348_rechumano";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join   on  .ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  as a on   a.ed260_i_codigo = rechumano.ed20_i_censoufcert and   a.ed260_i_codigo = rechumano.ed20_i_censoufender and   a.ed260_i_codigo = rechumano.ed20_i_censoufnat and   a.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  as b on   b.ed261_i_codigo = rechumano.ed20_i_censomunicnat and   b. = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql .= "      left  join rechumano  as c on   c.ed20_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed348_sequencial)) {
         $sql2 .= " where rechumanoausente.ed348_sequencial = $ed348_sequencial ";
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
  public function sql_query_file ($ed348_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from rechumanoausente ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed348_sequencial)){
        $sql2 .= " where rechumanoausente.ed348_sequencial = $ed348_sequencial ";
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
  public function sql_query_tipo_ausencia ($ed348_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rechumanoausente ";
     $sql .= "       inner join tipoausencia on tipoausencia.ed320_sequencial = rechumanoausente.ed348_tipoausencia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed348_sequencial)){
         $sql2 .= " where rechumanoausente.ed348_sequencial = $ed348_sequencial ";
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

  public function sql_query_profissional_cgm ($ed348_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from rechumanoausente ";
    $sql .= " inner join escola on ed18_i_codigo = ed348_escola ";

    /**
     * CGM da rhpessoal
     */
    $sql .= "  left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumanoausente.ed348_rechumano ";
    $sql .= "  left join rhpessoal        on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal            ";
    $sql .= "  left join cgm as cgmrh     on cgmrh.z01_numcgm = rhpessoal.rh01_numcgm                              ";
    /**
     * CGM do RH
     */
    $sql .= "  left join rechumanocgm  on rechumanocgm.ed285_i_rechumano = rechumanoausente.ed348_rechumano ";
    $sql .= "  left join cgm as cgmcgm on cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm                      ";
    $sql2 = "";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed348_sequencial)){
        $sql2 .= " where rechumanoausente.ed348_sequencial = $ed348_sequencial ";
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
