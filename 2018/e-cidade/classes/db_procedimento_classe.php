<?
//MODULO: escola
//CLASSE DA ENTIDADE procedimento
class cl_procedimento {
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
   var $ed40_i_codigo = 0;
   var $ed40_i_formaavaliacao = 0;
   var $ed40_c_descr = null;
   var $ed40_i_percfreq = 0;
   var $ed40_c_contrfreqmpd = null;
   var $ed40_i_calcfreq = 0;
   var $ed40_desativado = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed40_i_codigo = int8 = Código
                 ed40_i_formaavaliacao = int8 = Forma de Avaliação
                 ed40_c_descr = char(40) = Descrição
                 ed40_i_percfreq = int4 = Frequencia Mínima para Aprovação
                 ed40_c_contrfreqmpd = char(1) = Controle de Frequência
                 ed40_i_calcfreq = int4 = Cálculo da Frequência
                 ed40_desativado = bool = Desativado
                 ";
   //funcao construtor da classe
   function cl_procedimento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procedimento");
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
       $this->ed40_i_codigo = ($this->ed40_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_i_codigo"]:$this->ed40_i_codigo);
       $this->ed40_i_formaavaliacao = ($this->ed40_i_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_i_formaavaliacao"]:$this->ed40_i_formaavaliacao);
       $this->ed40_c_descr = ($this->ed40_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_c_descr"]:$this->ed40_c_descr);
       $this->ed40_i_percfreq = ($this->ed40_i_percfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_i_percfreq"]:$this->ed40_i_percfreq);
       $this->ed40_c_contrfreqmpd = ($this->ed40_c_contrfreqmpd == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_c_contrfreqmpd"]:$this->ed40_c_contrfreqmpd);
       $this->ed40_i_calcfreq = ($this->ed40_i_calcfreq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_i_calcfreq"]:$this->ed40_i_calcfreq);
       $this->ed40_desativado = ($this->ed40_desativado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_desativado"]:$this->ed40_desativado);
     }else{
       $this->ed40_i_codigo = ($this->ed40_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed40_i_codigo"]:$this->ed40_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed40_i_codigo){
      $this->atualizacampos();
     if($this->ed40_i_formaavaliacao == null ){
       $this->erro_sql = " Campo Forma de Avaliação não informado.";
       $this->erro_campo = "ed40_i_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed40_c_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "ed40_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed40_i_percfreq == null ){
       $this->erro_sql = " Campo Frequencia Mínima para Aprovação não informado.";
       $this->erro_campo = "ed40_i_percfreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed40_c_contrfreqmpd == null ){
       $this->erro_sql = " Campo Controle de Frequência não informado.";
       $this->erro_campo = "ed40_c_contrfreqmpd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed40_i_calcfreq == null ){
       $this->erro_sql = " Campo Cálculo da Frequência não informado.";
       $this->erro_campo = "ed40_i_calcfreq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed40_desativado == null ){
       $this->erro_sql = " Campo Desativado não informado.";
       $this->erro_campo = "ed40_desativado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed40_i_codigo == "" || $ed40_i_codigo == null ){
       $result = db_query("select nextval('procedimento_ed40_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procedimento_ed40_i_codigo_seq do campo: ed40_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed40_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from procedimento_ed40_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed40_i_codigo)){
         $this->erro_sql = " Campo ed40_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed40_i_codigo = $ed40_i_codigo;
       }
     }
     if(($this->ed40_i_codigo == null) || ($this->ed40_i_codigo == "") ){
       $this->erro_sql = " Campo ed40_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procedimento(
                                       ed40_i_codigo
                                      ,ed40_i_formaavaliacao
                                      ,ed40_c_descr
                                      ,ed40_i_percfreq
                                      ,ed40_c_contrfreqmpd
                                      ,ed40_i_calcfreq
                                      ,ed40_desativado
                       )
                values (
                                $this->ed40_i_codigo
                               ,$this->ed40_i_formaavaliacao
                               ,'$this->ed40_c_descr'
                               ,$this->ed40_i_percfreq
                               ,'$this->ed40_c_contrfreqmpd'
                               ,$this->ed40_i_calcfreq
                               ,'$this->ed40_desativado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimento de Avaliação ($this->ed40_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimento de Avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimento de Avaliação ($this->ed40_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed40_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed40_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008436,'$this->ed40_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010074,1008436,'','".AddSlashes(pg_result($resaco,0,'ed40_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,1008437,'','".AddSlashes(pg_result($resaco,0,'ed40_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,1008438,'','".AddSlashes(pg_result($resaco,0,'ed40_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,1008439,'','".AddSlashes(pg_result($resaco,0,'ed40_i_percfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,1008440,'','".AddSlashes(pg_result($resaco,0,'ed40_c_contrfreqmpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,14662,'','".AddSlashes(pg_result($resaco,0,'ed40_i_calcfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010074,22147,'','".AddSlashes(pg_result($resaco,0,'ed40_desativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed40_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update procedimento set ";
     $virgula = "";
     if(trim($this->ed40_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_codigo"])){
       $sql  .= $virgula." ed40_i_codigo = $this->ed40_i_codigo ";
       $virgula = ",";
       if(trim($this->ed40_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed40_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_i_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_formaavaliacao"])){
       $sql  .= $virgula." ed40_i_formaavaliacao = $this->ed40_i_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed40_i_formaavaliacao) == null ){
         $this->erro_sql = " Campo Forma de Avaliação não informado.";
         $this->erro_campo = "ed40_i_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_c_descr"])){
       $sql  .= $virgula." ed40_c_descr = '$this->ed40_c_descr' ";
       $virgula = ",";
       if(trim($this->ed40_c_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "ed40_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_i_percfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_percfreq"])){
       $sql  .= $virgula." ed40_i_percfreq = $this->ed40_i_percfreq ";
       $virgula = ",";
       if(trim($this->ed40_i_percfreq) == null ){
         $this->erro_sql = " Campo Frequencia Mínima para Aprovação não informado.";
         $this->erro_campo = "ed40_i_percfreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_c_contrfreqmpd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_c_contrfreqmpd"])){
       $sql  .= $virgula." ed40_c_contrfreqmpd = '$this->ed40_c_contrfreqmpd' ";
       $virgula = ",";
       if(trim($this->ed40_c_contrfreqmpd) == null ){
         $this->erro_sql = " Campo Controle de Frequência não informado.";
         $this->erro_campo = "ed40_c_contrfreqmpd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_i_calcfreq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_calcfreq"])){
       $sql  .= $virgula." ed40_i_calcfreq = $this->ed40_i_calcfreq ";
       $virgula = ",";
       if(trim($this->ed40_i_calcfreq) == null ){
         $this->erro_sql = " Campo Cálculo da Frequência não informado.";
         $this->erro_campo = "ed40_i_calcfreq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed40_desativado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed40_desativado"])){
       $sql  .= $virgula." ed40_desativado = '$this->ed40_desativado' ";
       $virgula = ",";
       if(trim($this->ed40_desativado) == null ){
         $this->erro_sql = " Campo Desativado não informado.";
         $this->erro_campo = "ed40_desativado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed40_i_codigo!=null){
       $sql .= " ed40_i_codigo = $this->ed40_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed40_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008436,'$this->ed40_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_codigo"]) || $this->ed40_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010074,1008436,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_i_codigo'))."','$this->ed40_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_formaavaliacao"]) || $this->ed40_i_formaavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010074,1008437,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_i_formaavaliacao'))."','$this->ed40_i_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_c_descr"]) || $this->ed40_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010074,1008438,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_c_descr'))."','$this->ed40_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_percfreq"]) || $this->ed40_i_percfreq != "")
             $resac = db_query("insert into db_acount values($acount,1010074,1008439,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_i_percfreq'))."','$this->ed40_i_percfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_c_contrfreqmpd"]) || $this->ed40_c_contrfreqmpd != "")
             $resac = db_query("insert into db_acount values($acount,1010074,1008440,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_c_contrfreqmpd'))."','$this->ed40_c_contrfreqmpd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_i_calcfreq"]) || $this->ed40_i_calcfreq != "")
             $resac = db_query("insert into db_acount values($acount,1010074,14662,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_i_calcfreq'))."','$this->ed40_i_calcfreq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed40_desativado"]) || $this->ed40_desativado != "")
             $resac = db_query("insert into db_acount values($acount,1010074,22147,'".AddSlashes(pg_result($resaco,$conresaco,'ed40_desativado'))."','$this->ed40_desativado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento de Avaliação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed40_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento de Avaliação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed40_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed40_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008436,'$ed40_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010074,1008436,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,1008437,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_i_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,1008438,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,1008439,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_i_percfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,1008440,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_c_contrfreqmpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,14662,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_i_calcfreq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010074,22147,'','".AddSlashes(pg_result($resaco,$iresaco,'ed40_desativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from procedimento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed40_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed40_i_codigo = $ed40_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento de Avaliação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed40_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento de Avaliação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed40_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:procedimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed40_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from procedimento ";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
     $sql .= "      inner join procescola  on  procescola.ed86_i_procedimento = procedimento.ed40_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed40_i_codigo)) {
         $sql2 .= " where procedimento.ed40_i_codigo = $ed40_i_codigo ";
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
  function sql_query_file ( $ed40_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procedimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed40_i_codigo!=null ){
         $sql2 .= " where procedimento.ed40_i_codigo = $ed40_i_codigo ";
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
   function sql_query_procturma ( $ed40_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procedimento ";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
     $sql .= "      inner join procescola  on  procescola.ed86_i_procedimento = procedimento.ed40_i_codigo";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_procedimento = procedimento.ed40_i_codigo";
     //$sql .= "      inner join procresultado  on  procresultado.ed43_i_procedimento = procedimento.ed40_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed40_i_codigo!=null ){
         $sql2 .= " where procedimento.ed40_i_codigo = $ed40_i_codigo ";
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
   function sql_query_formaavaliacao ( $ed40_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from procedimento ";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
     $sql2 = "";
     if($dbwhere==""){
       if($ed40_i_codigo!=null ){
         $sql2 .= " where procedimento.ed40_i_codigo = $ed40_i_codigo ";
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

  /**
   * Busca os procedimentos de avaliacao do procedimento de avaliacao
   * @param integer $sWhereProcAval
   * @param integer $sWhereProcRes
   * @return string
   */
  function sql_query_procedimentoavaliacao($sWhereProcAval = '', $sWhereProcRes = '') {

    $sSql  = " select ed41_i_codigo as codigo_elemento, ed41_i_sequencia as sequencia, 'A' as tipo ";
    $sSql .= '   from procavaliacao ';
    if (!empty($sWhereProcAval)) {
      $sSql .= " where ed41_i_procedimento = {$sWhereProcAval} ";
    }
    $sSql .= '  union ';
    $sSql .= " select ed43_i_codigo as codigo_elemento, ed43_i_sequencia as sequencia, 'R' as tipo ";
    $sSql .= '   from procresultado ';
    if (!empty($sWhereProcRes)) {
      $sSql .= " where ed43_i_procedimento = {$sWhereProcRes} ";
    }
    $sSql .= " order by sequencia";

    return $sSql;
  }

   // funcao do sql
  public function sql_query_origem_procedimento ($ed40_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from procedimento ";
    $sql .= " inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao";
    $sql .= "  left join procescola  on  procescola.ed86_i_procedimento = procedimento.ed40_i_codigo";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed40_i_codigo)) {
        $sql2 .= " where procedimento.ed40_i_codigo = $ed40_i_codigo ";
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

  public function sql_formasAvaliacaoProcedimento($iProcedimento) {

    $sSql  = " select ed40_i_formaavaliacao as codigo";
    $sSql .= "   from procedimento ";
    $sSql .= "  where ed40_i_codigo = {$iProcedimento} ";
    $sSql .= " union ";
    $sSql .= " select ed41_i_formaavaliacao ";
    $sSql .= "   from procavaliacao ";
    $sSql .= "  where ed41_i_procedimento = {$iProcedimento} ";
    $sSql .= " union ";
    $sSql .= " select ed43_i_formaavaliacao ";
    $sSql .= "   from procresultado ";
    $sSql .= "  where ed43_i_procedimento = {$iProcedimento} ";
    $sSql .= " union ";
    $sSql .= " select ed282_i_formaavaliacao ";
    $sSql .= "   from procavalalternativa ";
    $sSql .= "   join procresultado             on ed43_i_codigo               = ed281_i_procresultado ";
    $sSql .= "   join procavalalternativaregra  on ed282_i_procavalalternativa = ed281_i_codigo ";
    $sSql .= "  where ed43_i_procedimento = {$iProcedimento} ";
    $sSql .= "    and ed282_i_formaavaliacao is not null ";

    return $sSql;
  }
}
?>
