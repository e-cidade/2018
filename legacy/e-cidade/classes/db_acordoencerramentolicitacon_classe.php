<?php
//MODULO: acordos
//CLASSE DA ENTIDADE acordoencerramentolicitacon
class cl_acordoencerramentolicitacon {
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
   var $ac58_sequencial = 0;
   var $ac58_acordo = 0;
   var $ac58_data_dia = null;
   var $ac58_data_mes = null;
   var $ac58_data_ano = null;
   var $ac58_data = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac58_sequencial = int4 = Código
                 ac58_acordo = int4 = Acordo
                 ac58_data = date = Data
                 ";
   //funcao construtor da classe
   function cl_acordoencerramentolicitacon() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoencerramentolicitacon");
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
       $this->ac58_sequencial = ($this->ac58_sequencial == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"] : null):$this->ac58_sequencial);
       $this->ac58_acordo = ($this->ac58_acordo == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_acordo"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_acordo"] : null):$this->ac58_acordo);
       if($this->ac58_data == ""){
         $this->ac58_data_dia = ($this->ac58_data_dia == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_data_dia"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_data_dia"] : null):$this->ac58_data_dia);
         $this->ac58_data_mes = ($this->ac58_data_mes == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_data_mes"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_data_mes"] : null):$this->ac58_data_mes);
         $this->ac58_data_ano = ($this->ac58_data_ano == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_data_ano"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_data_ano"] : null):$this->ac58_data_ano);
         if($this->ac58_data_dia != ""){
            $this->ac58_data = $this->ac58_data_ano."-".$this->ac58_data_mes."-".$this->ac58_data_dia;
         }
       }
     }else{
       $this->ac58_sequencial = ($this->ac58_sequencial == ""?(isset($GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"]) ? $GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"] : null):$this->ac58_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ac58_sequencial){
      $this->atualizacampos();
     if($this->ac58_acordo == null ){
       $this->erro_sql = " Campo Acordo não informado.";
       $this->erro_campo = "ac58_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac58_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ac58_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac58_sequencial == "" || $ac58_sequencial == null ){
       $result = db_query("select nextval('acordoencerramentolicitacon_ac58_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoencerramentolicitacon_ac58_sequencial_seq do campo: ac58_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac58_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordoencerramentolicitacon_ac58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac58_sequencial)){
         $this->erro_sql = " Campo ac58_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac58_sequencial = $ac58_sequencial;
       }
     }
     if(($this->ac58_sequencial == null) || ($this->ac58_sequencial == "") ){
       $this->erro_sql = " Campo ac58_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoencerramentolicitacon(
                                       ac58_sequencial
                                      ,ac58_acordo
                                      ,ac58_data
                       )
                values (
                                $this->ac58_sequencial
                               ,$this->ac58_acordo
                               ,".($this->ac58_data == "null" || $this->ac58_data == ""?"null":"'".$this->ac58_data."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Encerramento de Contratos ($this->ac58_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Encerramento de Contratos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Encerramento de Contratos ($this->ac58_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac58_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21845,'$this->ac58_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3933,21845,'','".AddSlashes(pg_result($resaco,0,'ac58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3933,21846,'','".AddSlashes(pg_result($resaco,0,'ac58_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3933,21847,'','".AddSlashes(pg_result($resaco,0,'ac58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ac58_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordoencerramentolicitacon set ";
     $virgula = "";
     if(trim($this->ac58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"])){
       $sql  .= $virgula." ac58_sequencial = $this->ac58_sequencial ";
       $virgula = ",";
       if(trim($this->ac58_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ac58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac58_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac58_acordo"])){
       $sql  .= $virgula." ac58_acordo = $this->ac58_acordo ";
       $virgula = ",";
       if(trim($this->ac58_acordo) == null ){
         $this->erro_sql = " Campo Acordo não informado.";
         $this->erro_campo = "ac58_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac58_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac58_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac58_data_dia"] !="") ){
       $sql  .= $virgula." ac58_data = '$this->ac58_data' ";
       $virgula = ",";
       if(trim($this->ac58_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ac58_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac58_data_dia"])){
         $sql  .= $virgula." ac58_data = null ";
         $virgula = ",";
         if(trim($this->ac58_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ac58_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac58_sequencial!=null){
       $sql .= " ac58_sequencial = $this->ac58_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac58_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21845,'$this->ac58_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac58_sequencial"]) || $this->ac58_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3933,21845,'".AddSlashes(pg_result($resaco,$conresaco,'ac58_sequencial'))."','$this->ac58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac58_acordo"]) || $this->ac58_acordo != "")
             $resac = db_query("insert into db_acount values($acount,3933,21846,'".AddSlashes(pg_result($resaco,$conresaco,'ac58_acordo'))."','$this->ac58_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac58_data"]) || $this->ac58_data != "")
             $resac = db_query("insert into db_acount values($acount,3933,21847,'".AddSlashes(pg_result($resaco,$conresaco,'ac58_data'))."','$this->ac58_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Encerramento de Contratos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Encerramento de Contratos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ac58_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac58_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21845,'$ac58_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3933,21845,'','".AddSlashes(pg_result($resaco,$iresaco,'ac58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3933,21846,'','".AddSlashes(pg_result($resaco,$iresaco,'ac58_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3933,21847,'','".AddSlashes(pg_result($resaco,$iresaco,'ac58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoencerramentolicitacon
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac58_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac58_sequencial = $ac58_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Encerramento de Contratos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Encerramento de Contratos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoencerramentolicitacon";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ac58_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from acordoencerramentolicitacon ";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoencerramentolicitacon.ac58_acordo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
     $sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac58_sequencial)) {
         $sql2 .= " where acordoencerramentolicitacon.ac58_sequencial = $ac58_sequencial ";
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
   public function sql_query_file ($ac58_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordoencerramentolicitacon ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac58_sequencial)){
         $sql2 .= " where acordoencerramentolicitacon.ac58_sequencial = $ac58_sequencial ";
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
