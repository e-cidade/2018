<?
//MODULO: matriculaonline
//CLASSE DA ENTIDADE alocados
class cl_alocados {
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
   var $mo13_codigo = 0;
   var $mo13_base = 0;
   var $mo13_fase = 0;
   var $mo13_baseescturno = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 mo13_codigo = int4 = Código
                 mo13_base = int4 = Base
                 mo13_fase = int4 = Fase
                 mo13_baseescturno = int4 = Base Escola Turno
                 ";
   //funcao construtor da classe
   function cl_alocados() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alocados");
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
       $this->mo13_codigo = ($this->mo13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["mo13_codigo"]:$this->mo13_codigo);
       $this->mo13_base = ($this->mo13_base == ""?@$GLOBALS["HTTP_POST_VARS"]["mo13_base"]:$this->mo13_base);
       $this->mo13_fase = ($this->mo13_fase == ""?@$GLOBALS["HTTP_POST_VARS"]["mo13_fase"]:$this->mo13_fase);
       $this->mo13_baseescturno = ($this->mo13_baseescturno == ""?@$GLOBALS["HTTP_POST_VARS"]["mo13_baseescturno"]:$this->mo13_baseescturno);
     }else{
       $this->mo13_codigo = ($this->mo13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["mo13_codigo"]:$this->mo13_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($mo13_codigo){
      $this->atualizacampos();
     if($this->mo13_base == null ){
       $this->erro_sql = " Campo Base não informado.";
       $this->erro_campo = "mo13_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mo13_fase == null ){
       $this->erro_sql = " Campo Fase não informado.";
       $this->erro_campo = "mo13_fase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->mo13_baseescturno == null ){
       $this->erro_sql = " Campo Base Escola Turno não informado.";
       $this->erro_campo = "mo13_baseescturno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($mo13_codigo == "" || $mo13_codigo == null ){
       $result = db_query("select nextval('alocados_mo13_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alocados_mo13_codigo_seq do campo: mo13_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->mo13_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from alocados_mo13_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $mo13_codigo)){
         $this->erro_sql = " Campo mo13_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->mo13_codigo = $mo13_codigo;
       }
     }
     if(($this->mo13_codigo == null) || ($this->mo13_codigo == "") ){
       $this->erro_sql = " Campo mo13_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alocados(
                                       mo13_codigo
                                      ,mo13_base
                                      ,mo13_fase
                                      ,mo13_baseescturno
                       )
                values (
                                $this->mo13_codigo
                               ,$this->mo13_base
                               ,$this->mo13_fase
                               ,$this->mo13_baseescturno
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alocados ($this->mo13_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alocados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alocados ($this->mo13_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo13_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo13_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21458,'$this->mo13_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,3858,21458,'','".AddSlashes(pg_result($resaco,0,'mo13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3858,21459,'','".AddSlashes(pg_result($resaco,0,'mo13_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3858,21460,'','".AddSlashes(pg_result($resaco,0,'mo13_fase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3858,21461,'','".AddSlashes(pg_result($resaco,0,'mo13_baseescturno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($mo13_codigo=null) {
      $this->atualizacampos();
     $sql = " update alocados set ";
     $virgula = "";
     if(trim($this->mo13_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo13_codigo"])){
       $sql  .= $virgula." mo13_codigo = $this->mo13_codigo ";
       $virgula = ",";
       if(trim($this->mo13_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "mo13_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo13_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo13_base"])){
       $sql  .= $virgula." mo13_base = $this->mo13_base ";
       $virgula = ",";
       if(trim($this->mo13_base) == null ){
         $this->erro_sql = " Campo Base não informado.";
         $this->erro_campo = "mo13_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo13_fase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo13_fase"])){
       $sql  .= $virgula." mo13_fase = $this->mo13_fase ";
       $virgula = ",";
       if(trim($this->mo13_fase) == null ){
         $this->erro_sql = " Campo Fase não informado.";
         $this->erro_campo = "mo13_fase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->mo13_baseescturno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["mo13_baseescturno"])){
       $sql  .= $virgula." mo13_baseescturno = $this->mo13_baseescturno ";
       $virgula = ",";
       if(trim($this->mo13_baseescturno) == null ){
         $this->erro_sql = " Campo Base Escola Turno não informado.";
         $this->erro_campo = "mo13_baseescturno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($mo13_codigo!=null){
       $sql .= " mo13_codigo = $this->mo13_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->mo13_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21458,'$this->mo13_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo13_codigo"]) || $this->mo13_codigo != "")
             $resac = db_query("insert into db_acount values($acount,3858,21458,'".AddSlashes(pg_result($resaco,$conresaco,'mo13_codigo'))."','$this->mo13_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo13_base"]) || $this->mo13_base != "")
             $resac = db_query("insert into db_acount values($acount,3858,21459,'".AddSlashes(pg_result($resaco,$conresaco,'mo13_base'))."','$this->mo13_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo13_fase"]) || $this->mo13_fase != "")
             $resac = db_query("insert into db_acount values($acount,3858,21460,'".AddSlashes(pg_result($resaco,$conresaco,'mo13_fase'))."','$this->mo13_fase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["mo13_baseescturno"]) || $this->mo13_baseescturno != "")
             $resac = db_query("insert into db_acount values($acount,3858,21461,'".AddSlashes(pg_result($resaco,$conresaco,'mo13_baseescturno'))."','$this->mo13_baseescturno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alocados não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo13_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alocados não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->mo13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->mo13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($mo13_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($mo13_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21458,'$mo13_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,3858,21458,'','".AddSlashes(pg_result($resaco,$iresaco,'mo13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3858,21459,'','".AddSlashes(pg_result($resaco,$iresaco,'mo13_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3858,21460,'','".AddSlashes(pg_result($resaco,$iresaco,'mo13_fase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3858,21461,'','".AddSlashes(pg_result($resaco,$iresaco,'mo13_baseescturno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from alocados
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($mo13_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " mo13_codigo = $mo13_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alocados não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$mo13_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alocados não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$mo13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$mo13_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alocados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($mo13_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from alocados ";
     $sql .= "      inner join fase  on  fase.mo04_codigo = alocados.mo13_fase";
     $sql .= "      inner join mobase  on  mobase.mo01_codigo = alocados.mo13_base";
     $sql .= "      inner join baseescturno  on  baseescturno.mo03_codigo = alocados.mo13_baseescturno";
     $sql .= "      inner join ciclos  on  ciclos.mo09_codigo = fase.mo04_ciclo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = mobase.mo01_bairro";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = mobase.mo01_serie";
     $sql .= "      inner join baseescola  on  baseescola.mo02_codigo = baseescturno.mo03_baseescola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = baseescturno.mo03_turno";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo13_codigo)) {
         $sql2 .= " where alocados.mo13_codigo = $mo13_codigo ";
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
   public function sql_query_file ($mo13_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from alocados ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($mo13_codigo)){
         $sql2 .= " where alocados.mo13_codigo = $mo13_codigo ";
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
 public function sql_query_dadosaluno ($mo13_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = " select {$campos} ";
    $sql .= "   from alocados ";
    $sql .= "  inner join fase         on fase.mo04_codigo         = alocados.mo13_fase ";
    $sql .= "  inner join mobase       on mobase.mo01_codigo       = alocados.mo13_base ";
    $sql .= "  inner join redeorigem   on redeorigem.mo05_codigo   = mobase.mo01_redeorigem ";
    $sql .= "  inner join baseescturno on baseescturno.mo03_codigo = alocados.mo13_baseescturno ";
    $sql .= "  inner join ciclos       on ciclos.mo09_codigo       = fase.mo04_ciclo ";
    $sql .= "  inner join bairro       on bairro.j13_codi          = mobase.mo01_bairro ";
    $sql .= "  inner join serie        on serie.ed11_i_codigo      = mobase.mo01_serie ";
    $sql .= "  inner join baseescola   on baseescola.mo02_codigo   = baseescturno.mo03_baseescola ";
    $sql .= "  inner join escola       on escola.ed18_i_codigo     = baseescola.mo02_escola ";
    $sql .= "  inner join turno        on turno.ed15_i_codigo      = baseescturno.mo03_turno ";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($mo13_codigo)){
        $sql2 .= " where alocados.mo13_codigo = $mo13_codigo ";
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
