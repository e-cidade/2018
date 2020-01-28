<?
//MODULO: escola
//CLASSE DA ENTIDADE atividaderh
class cl_atividaderh {
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
   var $ed01_i_codigo = 0;
   var $ed01_c_descr = null;
   var $ed01_c_regencia = null;
   var $ed01_c_atualiz = null;
   var $ed01_c_docencia = null;
   var $ed01_c_exigeato = null;
   var $ed01_c_efetividade = null;
   var $ed01_i_funcaoadmin = 0;
   var $ed01_funcaoatividade = 0;
   var $ed01_atividadeescolar = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed01_i_codigo = int8 = C�digo
                 ed01_c_descr = char(25) = Descri��o
                 ed01_c_regencia = char(1) = Reg�ncia
                 ed01_c_atualiz = char(1) = Atualizar
                 ed01_c_docencia = char(1) = Doc�ncia
                 ed01_c_exigeato = char(1) = Exige Ato Legal
                 ed01_c_efetividade = char(4) = Efetividade
                 ed01_i_funcaoadmin = int4 = Fun��o Administrativa
                 ed01_funcaoatividade = int4 = Fun��o
                 ed01_atividadeescolar = bool = Atividade Escolar sem Reg�ncia
                 ";
   //funcao construtor da classe
   function cl_atividaderh() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividaderh");
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
       $this->ed01_i_codigo = ($this->ed01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]:$this->ed01_i_codigo);
       $this->ed01_c_descr = ($this->ed01_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"]:$this->ed01_c_descr);
       $this->ed01_c_regencia = ($this->ed01_c_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"]:$this->ed01_c_regencia);
       $this->ed01_c_atualiz = ($this->ed01_c_atualiz == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"]:$this->ed01_c_atualiz);
       $this->ed01_c_docencia = ($this->ed01_c_docencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"]:$this->ed01_c_docencia);
       $this->ed01_c_exigeato = ($this->ed01_c_exigeato == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"]:$this->ed01_c_exigeato);
       $this->ed01_c_efetividade = ($this->ed01_c_efetividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"]:$this->ed01_c_efetividade);
       $this->ed01_i_funcaoadmin = ($this->ed01_i_funcaoadmin == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"]:$this->ed01_i_funcaoadmin);
       $this->ed01_funcaoatividade = ($this->ed01_funcaoatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_funcaoatividade"]:$this->ed01_funcaoatividade);
       $this->ed01_atividadeescolar = ($this->ed01_atividadeescolar == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_atividadeescolar"]:$this->ed01_atividadeescolar);
     }else{
       $this->ed01_i_codigo = ($this->ed01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]:$this->ed01_i_codigo);
     }
   }
   // funcao para Inclus�o
   function incluir ($ed01_i_codigo){
      $this->atualizacampos();
     if($this->ed01_c_descr == null ){
       $this->erro_sql = " Campo Descri��o n�o informado.";
       $this->erro_campo = "ed01_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_regencia == null ){
       $this->erro_sql = " Campo Reg�ncia n�o informado.";
       $this->erro_campo = "ed01_c_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_atualiz == null ){
       $this->erro_sql = " Campo Atualizar n�o informado.";
       $this->erro_campo = "ed01_c_atualiz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_docencia == null ){
       $this->erro_sql = " Campo Doc�ncia n�o informado.";
       $this->erro_campo = "ed01_c_docencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_exigeato == null ){
       $this->erro_sql = " Campo Exige Ato Legal n�o informado.";
       $this->erro_campo = "ed01_c_exigeato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_c_efetividade == null ){
       $this->erro_sql = " Campo Efetividade n�o informado.";
       $this->erro_campo = "ed01_c_efetividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_i_funcaoadmin == null ){
       $this->erro_sql = " Campo Fun��o Administrativa n�o informado.";
       $this->erro_campo = "ed01_i_funcaoadmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_funcaoatividade == null ){
       $this->erro_sql = " Campo Fun��o n�o informado.";
       $this->erro_campo = "ed01_funcaoatividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed01_atividadeescolar == null ){
       $this->erro_sql = " Campo Atividade Escolar sem Reg�ncia n�o informado.";
       $this->erro_campo = "ed01_atividadeescolar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed01_i_codigo == "" || $ed01_i_codigo == null ){
       $result = db_query("select nextval('atividaderh_ed01_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atividaderh_ed01_i_codigo_seq do campo: ed01_i_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed01_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from atividaderh_ed01_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed01_i_codigo)){
         $this->erro_sql = " Campo ed01_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed01_i_codigo = $ed01_i_codigo;
       }
     }
     if(($this->ed01_i_codigo == null) || ($this->ed01_i_codigo == "") ){
       $this->erro_sql = " Campo ed01_i_codigo n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividaderh(
                                       ed01_i_codigo
                                      ,ed01_c_descr
                                      ,ed01_c_regencia
                                      ,ed01_c_atualiz
                                      ,ed01_c_docencia
                                      ,ed01_c_exigeato
                                      ,ed01_c_efetividade
                                      ,ed01_i_funcaoadmin
                                      ,ed01_funcaoatividade
                                      ,ed01_atividadeescolar
                       )
                values (
                                $this->ed01_i_codigo
                               ,'$this->ed01_c_descr'
                               ,'$this->ed01_c_regencia'
                               ,'$this->ed01_c_atualiz'
                               ,'$this->ed01_c_docencia'
                               ,'$this->ed01_c_exigeato'
                               ,'$this->ed01_c_efetividade'
                               ,$this->ed01_i_funcaoadmin
                               ,$this->ed01_funcaoatividade
                               ,'$this->ed01_atividadeescolar'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Atividades ($this->ed01_i_codigo) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Atividades j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Atividades ($this->ed01_i_codigo) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed01_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008539,'$this->ed01_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010095,1008539,'','".AddSlashes(pg_result($resaco,0,'ed01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008540,'','".AddSlashes(pg_result($resaco,0,'ed01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008541,'','".AddSlashes(pg_result($resaco,0,'ed01_c_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,1008542,'','".AddSlashes(pg_result($resaco,0,'ed01_c_atualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,19584,'','".AddSlashes(pg_result($resaco,0,'ed01_c_docencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,14572,'','".AddSlashes(pg_result($resaco,0,'ed01_c_exigeato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,14582,'','".AddSlashes(pg_result($resaco,0,'ed01_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,18535,'','".AddSlashes(pg_result($resaco,0,'ed01_i_funcaoadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,20597,'','".AddSlashes(pg_result($resaco,0,'ed01_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010095,22090,'','".AddSlashes(pg_result($resaco,0,'ed01_atividadeescolar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed01_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update atividaderh set ";
     $virgula = "";
     if(trim($this->ed01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"])){
       $sql  .= $virgula." ed01_i_codigo = $this->ed01_i_codigo ";
       $virgula = ",";
       if(trim($this->ed01_i_codigo) == null ){
         $this->erro_sql = " Campo C�digo n�o informado.";
         $this->erro_campo = "ed01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"])){
       $sql  .= $virgula." ed01_c_descr = '$this->ed01_c_descr' ";
       $virgula = ",";
       if(trim($this->ed01_c_descr) == null ){
         $this->erro_sql = " Campo Descri��o n�o informado.";
         $this->erro_campo = "ed01_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"])){
       $sql  .= $virgula." ed01_c_regencia = '$this->ed01_c_regencia' ";
       $virgula = ",";
       if(trim($this->ed01_c_regencia) == null ){
         $this->erro_sql = " Campo Reg�ncia n�o informado.";
         $this->erro_campo = "ed01_c_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_atualiz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"])){
       $sql  .= $virgula." ed01_c_atualiz = '$this->ed01_c_atualiz' ";
       $virgula = ",";
       if(trim($this->ed01_c_atualiz) == null ){
         $this->erro_sql = " Campo Atualizar n�o informado.";
         $this->erro_campo = "ed01_c_atualiz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_docencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"])){
       $sql  .= $virgula." ed01_c_docencia = '$this->ed01_c_docencia' ";
       $virgula = ",";
       if(trim($this->ed01_c_docencia) == null ){
         $this->erro_sql = " Campo Doc�ncia n�o informado.";
         $this->erro_campo = "ed01_c_docencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_exigeato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"])){
       $sql  .= $virgula." ed01_c_exigeato = '$this->ed01_c_exigeato' ";
       $virgula = ",";
       if(trim($this->ed01_c_exigeato) == null ){
         $this->erro_sql = " Campo Exige Ato Legal n�o informado.";
         $this->erro_campo = "ed01_c_exigeato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_c_efetividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"])){
       $sql  .= $virgula." ed01_c_efetividade = '$this->ed01_c_efetividade' ";
       $virgula = ",";
       if(trim($this->ed01_c_efetividade) == null ){
         $this->erro_sql = " Campo Efetividade n�o informado.";
         $this->erro_campo = "ed01_c_efetividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_i_funcaoadmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"])){
       $sql  .= $virgula." ed01_i_funcaoadmin = $this->ed01_i_funcaoadmin ";
       $virgula = ",";
       if(trim($this->ed01_i_funcaoadmin) == null ){
         $this->erro_sql = " Campo Fun��o Administrativa n�o informado.";
         $this->erro_campo = "ed01_i_funcaoadmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed01_funcaoatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_funcaoatividade"])){
       $sql  .= $virgula." ed01_funcaoatividade = $this->ed01_funcaoatividade ";
       $virgula = ",";
       if(trim($this->ed01_funcaoatividade) == null ){
         $this->erro_sql = " Campo Fun��o n�o informado.";
         $this->erro_campo = "ed01_funcaoatividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->ed01_atividadeescolar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed01_atividadeescolar"])){
       $sql  .= $virgula." ed01_atividadeescolar = '$this->ed01_atividadeescolar' ";
       $virgula = ",";
       if(trim($this->ed01_atividadeescolar) == null ){
         $this->erro_sql = " Campo Atividade Escolar sem Reg�ncia n�o informado.";
         $this->erro_campo = "ed01_atividadeescolar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed01_i_codigo!=null){
       $sql .= " ed01_i_codigo = $this->ed01_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed01_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008539,'$this->ed01_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_codigo"]) || $this->ed01_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010095,1008539,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_i_codigo'))."','$this->ed01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_descr"]) || $this->ed01_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010095,1008540,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_descr'))."','$this->ed01_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_regencia"]) || $this->ed01_c_regencia != "")
             $resac = db_query("insert into db_acount values($acount,1010095,1008541,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_regencia'))."','$this->ed01_c_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_atualiz"]) || $this->ed01_c_atualiz != "")
             $resac = db_query("insert into db_acount values($acount,1010095,1008542,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_atualiz'))."','$this->ed01_c_atualiz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_docencia"]) || $this->ed01_c_docencia != "")
             $resac = db_query("insert into db_acount values($acount,1010095,19584,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_docencia'))."','$this->ed01_c_docencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_exigeato"]) || $this->ed01_c_exigeato != "")
             $resac = db_query("insert into db_acount values($acount,1010095,14572,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_exigeato'))."','$this->ed01_c_exigeato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_c_efetividade"]) || $this->ed01_c_efetividade != "")
             $resac = db_query("insert into db_acount values($acount,1010095,14582,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_c_efetividade'))."','$this->ed01_c_efetividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_i_funcaoadmin"]) || $this->ed01_i_funcaoadmin != "")
             $resac = db_query("insert into db_acount values($acount,1010095,18535,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_i_funcaoadmin'))."','$this->ed01_i_funcaoadmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_funcaoatividade"]) || $this->ed01_funcaoatividade != "")
             $resac = db_query("insert into db_acount values($acount,1010095,20597,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_funcaoatividade'))."','$this->ed01_funcaoatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed01_atividadeescolar"]) || $this->ed01_atividadeescolar != "")
             $resac = db_query("insert into db_acount values($acount,1010095,22090,'".AddSlashes(pg_result($resaco,$conresaco,'ed01_atividadeescolar'))."','$this->ed01_atividadeescolar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Atividades n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Atividades n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed01_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed01_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008539,'$ed01_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010095,1008539,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,1008540,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,1008541,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,1008542,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_atualiz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,19584,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_docencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,14572,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_exigeato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,14582,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,18535,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_i_funcaoadmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,20597,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010095,22090,'','".AddSlashes(pg_result($resaco,$iresaco,'ed01_atividadeescolar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from atividaderh
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed01_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed01_i_codigo = $ed01_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Atividades n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Atividades n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:atividaderh";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed01_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from atividaderh ";
     $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = atividaderh.ed01_funcaoatividade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed01_i_codigo)) {
         $sql2 .= " where atividaderh.ed01_i_codigo = $ed01_i_codigo ";
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
   public function sql_query_file ($ed01_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from atividaderh ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed01_i_codigo)){
         $sql2 .= " where atividaderh.ed01_i_codigo = $ed01_i_codigo ";
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
