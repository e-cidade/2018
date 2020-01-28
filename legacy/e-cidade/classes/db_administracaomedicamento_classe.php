<?
//MODULO: ambulatorial
//CLASSE DA ENTIDADE administracaomedicamento
class cl_administracaomedicamento {
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
   var $sd105_codigo = 0;
   var $sd105_usuario = 0;
   var $sd105_unidadesaida = 0;
   var $sd105_quantidade = 0;
   var $sd105_quantidadetotal = 0;
   var $sd105_data_dia = null;
   var $sd105_data_mes = null;
   var $sd105_data_ano = null;
   var $sd105_data = null;
   var $sd105_hora = null;
   var $sd105_medicamento = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sd105_codigo = int4 = Codigo
                 sd105_usuario = int4 = Usuário
                 sd105_unidadesaida = int4 = Unidade de Saída
                 sd105_quantidade = float8 = Quantidade
                 sd105_quantidadetotal = float8 = Quantidade do Medicamento
                 sd105_data = date = Data
                 sd105_hora = varchar(5) = Hora
                 sd105_medicamento = int4 = Medicamento
                 ";
   //funcao construtor da classe
   function cl_administracaomedicamento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("administracaomedicamento");
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
       $this->sd105_codigo = ($this->sd105_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_codigo"]:$this->sd105_codigo);
       $this->sd105_usuario = ($this->sd105_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_usuario"]:$this->sd105_usuario);
       $this->sd105_unidadesaida = ($this->sd105_unidadesaida == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_unidadesaida"]:$this->sd105_unidadesaida);
       $this->sd105_quantidade = ($this->sd105_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_quantidade"]:$this->sd105_quantidade);
       $this->sd105_quantidadetotal = ($this->sd105_quantidadetotal == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_quantidadetotal"]:$this->sd105_quantidadetotal);
       if($this->sd105_data == ""){
         $this->sd105_data_dia = ($this->sd105_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_data_dia"]:$this->sd105_data_dia);
         $this->sd105_data_mes = ($this->sd105_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_data_mes"]:$this->sd105_data_mes);
         $this->sd105_data_ano = ($this->sd105_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_data_ano"]:$this->sd105_data_ano);
         if($this->sd105_data_dia != ""){
            $this->sd105_data = $this->sd105_data_ano."-".$this->sd105_data_mes."-".$this->sd105_data_dia;
         }
       }
       $this->sd105_hora = ($this->sd105_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_hora"]:$this->sd105_hora);
       $this->sd105_medicamento = ($this->sd105_medicamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_medicamento"]:$this->sd105_medicamento);
     }else{
       $this->sd105_codigo = ($this->sd105_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd105_codigo"]:$this->sd105_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($sd105_codigo){
      $this->atualizacampos();
     if($this->sd105_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "sd105_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_unidadesaida == null ){
       $this->erro_sql = " Campo Unidade de Saída não informado.";
       $this->erro_campo = "sd105_unidadesaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_quantidade == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "sd105_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_quantidadetotal == null ){
       $this->erro_sql = " Campo Quantidade do Medicamento não informado.";
       $this->erro_campo = "sd105_quantidadetotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "sd105_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_hora == null ){
       $this->erro_sql = " Campo Hora não informado.";
       $this->erro_campo = "sd105_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd105_medicamento == null ){
       $this->erro_sql = " Campo Medicamento não informado.";
       $this->erro_campo = "sd105_medicamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd105_codigo == "" || $sd105_codigo == null ){
       $result = db_query("select nextval('administracaomedicamento_sd105_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: administracaomedicamento_sd105_codigo_seq do campo: sd105_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sd105_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from administracaomedicamento_sd105_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd105_codigo)){
         $this->erro_sql = " Campo sd105_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd105_codigo = $sd105_codigo;
       }
     }
     if(($this->sd105_codigo == null) || ($this->sd105_codigo == "") ){
       $this->erro_sql = " Campo sd105_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into administracaomedicamento(
                                       sd105_codigo
                                      ,sd105_usuario
                                      ,sd105_unidadesaida
                                      ,sd105_quantidade
                                      ,sd105_quantidadetotal
                                      ,sd105_data
                                      ,sd105_hora
                                      ,sd105_medicamento
                       )
                values (
                                $this->sd105_codigo
                               ,$this->sd105_usuario
                               ,$this->sd105_unidadesaida
                               ,$this->sd105_quantidade
                               ,$this->sd105_quantidadetotal
                               ,".($this->sd105_data == "null" || $this->sd105_data == ""?"null":"'".$this->sd105_data."'")."
                               ,'$this->sd105_hora'
                               ,$this->sd105_medicamento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Medicamento administrado ($this->sd105_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Medicamento administrado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Medicamento administrado ($this->sd105_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd105_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd105_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21328,'$this->sd105_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3841,21328,'','".AddSlashes(pg_result($resaco,0,'sd105_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21329,'','".AddSlashes(pg_result($resaco,0,'sd105_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21330,'','".AddSlashes(pg_result($resaco,0,'sd105_unidadesaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21331,'','".AddSlashes(pg_result($resaco,0,'sd105_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21332,'','".AddSlashes(pg_result($resaco,0,'sd105_quantidadetotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21333,'','".AddSlashes(pg_result($resaco,0,'sd105_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21334,'','".AddSlashes(pg_result($resaco,0,'sd105_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3841,21338,'','".AddSlashes(pg_result($resaco,0,'sd105_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($sd105_codigo=null) {
      $this->atualizacampos();
     $sql = " update administracaomedicamento set ";
     $virgula = "";
     if(trim($this->sd105_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_codigo"])){
       $sql  .= $virgula." sd105_codigo = $this->sd105_codigo ";
       $virgula = ",";
       if(trim($this->sd105_codigo) == null ){
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "sd105_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_usuario"])){
       $sql  .= $virgula." sd105_usuario = $this->sd105_usuario ";
       $virgula = ",";
       if(trim($this->sd105_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "sd105_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_unidadesaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_unidadesaida"])){
       $sql  .= $virgula." sd105_unidadesaida = $this->sd105_unidadesaida ";
       $virgula = ",";
       if(trim($this->sd105_unidadesaida) == null ){
         $this->erro_sql = " Campo Unidade de Saída não informado.";
         $this->erro_campo = "sd105_unidadesaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_quantidade"])){
       $sql  .= $virgula." sd105_quantidade = $this->sd105_quantidade ";
       $virgula = ",";
       if(trim($this->sd105_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "sd105_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_quantidadetotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_quantidadetotal"])){
       $sql  .= $virgula." sd105_quantidadetotal = $this->sd105_quantidadetotal ";
       $virgula = ",";
       if(trim($this->sd105_quantidadetotal) == null ){
         $this->erro_sql = " Campo Quantidade do Medicamento não informado.";
         $this->erro_campo = "sd105_quantidadetotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd105_data_dia"] !="") ){
       $sql  .= $virgula." sd105_data = '$this->sd105_data' ";
       $virgula = ",";
       if(trim($this->sd105_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "sd105_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd105_data_dia"])){
         $sql  .= $virgula." sd105_data = null ";
         $virgula = ",";
         if(trim($this->sd105_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "sd105_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd105_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_hora"])){
       $sql  .= $virgula." sd105_hora = '$this->sd105_hora' ";
       $virgula = ",";
       if(trim($this->sd105_hora) == null ){
         $this->erro_sql = " Campo Hora não informado.";
         $this->erro_campo = "sd105_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd105_medicamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd105_medicamento"])){
       $sql  .= $virgula." sd105_medicamento = $this->sd105_medicamento ";
       $virgula = ",";
       if(trim($this->sd105_medicamento) == null ){
         $this->erro_sql = " Campo Medicamento não informado.";
         $this->erro_campo = "sd105_medicamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd105_codigo!=null){
       $sql .= " sd105_codigo = $this->sd105_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->sd105_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21328,'$this->sd105_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_codigo"]) || $this->sd105_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3841,21328,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_codigo'))."','$this->sd105_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_usuario"]) || $this->sd105_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3841,21329,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_usuario'))."','$this->sd105_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_unidadesaida"]) || $this->sd105_unidadesaida != "")
             $resac = db_query("insert into db_acount values($acount,3841,21330,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_unidadesaida'))."','$this->sd105_unidadesaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_quantidade"]) || $this->sd105_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3841,21331,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_quantidade'))."','$this->sd105_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_quantidadetotal"]) || $this->sd105_quantidadetotal != "")
             $resac = db_query("insert into db_acount values($acount,3841,21332,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_quantidadetotal'))."','$this->sd105_quantidadetotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_data"]) || $this->sd105_data != "")
             $resac = db_query("insert into db_acount values($acount,3841,21333,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_data'))."','$this->sd105_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_hora"]) || $this->sd105_hora != "")
             $resac = db_query("insert into db_acount values($acount,3841,21334,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_hora'))."','$this->sd105_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["sd105_medicamento"]) || $this->sd105_medicamento != "")
             $resac = db_query("insert into db_acount values($acount,3841,21338,'".AddSlashes(pg_result($resaco,$conresaco,'sd105_medicamento'))."','$this->sd105_medicamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamento administrado não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd105_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamento administrado não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd105_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd105_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($sd105_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($sd105_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21328,'$sd105_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3841,21328,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21329,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21330,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_unidadesaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21331,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21332,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_quantidadetotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21333,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21334,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3841,21338,'','".AddSlashes(pg_result($resaco,$iresaco,'sd105_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from administracaomedicamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sd105_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sd105_codigo = $sd105_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Medicamento administrado não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd105_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Medicamento administrado não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd105_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd105_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:administracaomedicamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($sd105_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from administracaomedicamento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = administracaomedicamento.sd105_usuario";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = administracaomedicamento.sd105_unidadesaida";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = administracaomedicamento.sd105_medicamento";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left  join far_medanvisa  on  far_medanvisa.fa14_i_codigo = far_matersaude.fa01_i_medanvisa";
     $sql .= "      left  join far_prescricaomed  on  far_prescricaomed.fa31_i_codigo = far_matersaude.fa01_i_prescricaomed";
     $sql .= "      left  join far_laboratoriomed  on  far_laboratoriomed.fa32_i_codigo = far_matersaude.fa01_i_laboratoriomed";
     $sql .= "      left  join far_formafarmaceuticamed  on  far_formafarmaceuticamed.fa33_i_codigo = far_matersaude.fa01_i_formafarmaceuticamed";
     $sql .= "      left  join far_medreferenciamed  on  far_medreferenciamed.fa34_i_codigo = far_matersaude.fa01_i_medrefemed";
     $sql .= "      left  join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
     $sql .= "      left  join far_classeterapeuticamed  on  far_classeterapeuticamed.fa36_i_codigo = far_matersaude.fa01_i_classemed";
     $sql .= "      left  join far_concentracaomed  on  far_concentracaomed.fa37_i_codigo = far_matersaude.fa01_i_concentracaomed";
     $sql .= "      inner join far_medicamentohiperdia  on  far_medicamentohiperdia.fa43_i_codigo = far_matersaude.fa01_i_medhiperdia";
     $sql .= "      left  join medicamentos  on  medicamentos.fa58_codigo = far_matersaude.fa01_medicamentos";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd105_codigo)) {
         $sql2 .= " where administracaomedicamento.sd105_codigo = $sd105_codigo ";
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
   public function sql_query_file ($sd105_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from administracaomedicamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sd105_codigo)){
         $sql2 .= " where administracaomedicamento.sd105_codigo = $sd105_codigo ";
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

  public function administracaoMedicamento($sCampos, $sWhere = null, $sGroup = null, $sOrder = null) {


    $sSql  = " select {$sCampos} ";
    $sSql .= "   from administracaomedicamento ";
    $sSql .= "  inner join prontuarioadministracaomedicamento on sd106_administracaomedicamento = sd105_codigo ";
    $sSql .= "  inner join prontuarios on sd24_i_codigo = sd106_prontuario ";

    if ( !empty($sWhere) ) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sGroup) ) {
      $sSql .= " group by {$sGroup} ";
    }
    if ( !empty($sOrder) ) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;


  }

  /**
   * @todo  validar nome do metodo com Iuri ... (medicamentosSolicitados)
   */
  public function medicamentosSolicitados($sCampos, $sWhere = null, $sGroup = null, $sOrder = null) {

    $sSql  = " select {$sCampos}  ";
    $sSql .= '   from matestoqueini ';
    $sSql .= '        inner join matestoqueinimei    on matestoqueinimei.m82_matestoqueini          = matestoqueini.m80_codigo ';
    $sSql .= '        inner join matestoqueitem      on matestoqueitem.m71_codlanc                  = matestoqueinimei.m82_matestoqueitem ';
    $sSql .= '        inner join matestoque          on matestoque.m70_codigo                       = matestoqueitem.m71_codmatestoque ';
    $sSql .= '        inner join matmater            on matmater.m60_codmater                       = matestoque.m70_codmatmater ';
    $sSql .= '        inner join matunid             on matunid.m61_codmatunid                      = matmater.m60_codmatunid ';
    $sSql .= '        inner join far_matersaude      on far_matersaude.fa01_i_codmater              = matmater.m60_codmater ';
    $sSql .= '        inner join matestoquetipo      on matestoquetipo.m81_codtipo                  = matestoqueini.m80_codtipo ';
    $sSql .= '         left join matestoqueitemlote  on matestoqueitemlote.m77_matestoqueitem       = matestoqueitem.m71_codlanc ';
    $sSql .= '        inner join matestoqueinimeipm  on matestoqueinimeipm.m89_matestoqueinimei     = matestoqueinimei.m82_codigo ';
    $sSql .= '         left join matestoqueinimeiari on matestoqueinimeiari.m49_codmatestoqueinimei = matestoqueinimei.m82_codigo';
    $sSql .= '         left join atendrequiitem      on atendrequiitem.m43_codigo                   = matestoqueinimeiari.m49_codatendrequiitem ';
    $sSql .= '         left join matrequiitem        on matrequiitem.m41_codigo                     = atendrequiitem.m43_codmatrequiitem';
    $sSql .= '         left join matrequi            on matrequi.m40_codigo                         = matrequiitem.m41_codmatrequi';
    $sSql .= '         left join far_retiradarequi   on far_retiradarequi.fa07_i_matrequi           = matrequi.m40_codigo';
    $sSql .= '         left join far_retirada        on far_retirada.fa04_i_codigo                  = far_retiradarequi.fa07_i_retirada';

    if ( !empty($sWhere) ) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sGroup) ) {
      $sSql .= " group by {$sGroup} ";
    }
    if ( !empty($sOrder) ) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

}
