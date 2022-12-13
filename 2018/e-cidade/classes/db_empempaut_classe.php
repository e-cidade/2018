<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: empenho
//CLASSE DA ENTIDADE empempaut
class cl_empempaut {
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
  var $e61_numemp = 0;
  var $e61_autori = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 e61_numemp = int4 = Número 
                 e61_autori = int4 = Autorização 
                 ";
  //funcao construtor da classe
  function cl_empempaut() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empempaut");
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
      $this->e61_numemp = ($this->e61_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e61_numemp"]:$this->e61_numemp);
      $this->e61_autori = ($this->e61_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e61_autori"]:$this->e61_autori);
    }else{
      $this->e61_numemp = ($this->e61_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e61_numemp"]:$this->e61_numemp);
    }
  }
  // funcao para inclusao
  function incluir ($e61_numemp){
    $this->atualizacampos();
    if($this->e61_autori == null ){
      $this->erro_sql = " Campo Autorização nao Informado.";
      $this->erro_campo = "e61_autori";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->e61_numemp = $e61_numemp;
    if(($this->e61_numemp == null) || ($this->e61_numemp == "") ){
      $this->erro_sql = " Campo e61_numemp nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empempaut(
                                       e61_numemp 
                                      ,e61_autori 
                       )
                values (
                                $this->e61_numemp 
                               ,$this->e61_autori 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Autorizações empenhadas ($this->e61_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Autorizações empenhadas já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Autorizações empenhadas ($this->e61_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->e61_numemp;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->e61_numemp));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,5664,'$this->e61_numemp','I')");
      $resac = db_query("insert into db_acount values($acount,890,5664,'','".AddSlashes(pg_result($resaco,0,'e61_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,890,5665,'','".AddSlashes(pg_result($resaco,0,'e61_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($e61_numemp=null) {
    $this->atualizacampos();
    $sql = " update empempaut set ";
    $virgula = "";
    if(trim($this->e61_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e61_numemp"])){
      $sql  .= $virgula." e61_numemp = $this->e61_numemp ";
      $virgula = ",";
      if(trim($this->e61_numemp) == null ){
        $this->erro_sql = " Campo Número nao Informado.";
        $this->erro_campo = "e61_numemp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e61_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e61_autori"])){
      $sql  .= $virgula." e61_autori = $this->e61_autori ";
      $virgula = ",";
      if(trim($this->e61_autori) == null ){
        $this->erro_sql = " Campo Autorização nao Informado.";
        $this->erro_campo = "e61_autori";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($e61_numemp!=null){
      $sql .= " e61_numemp = $this->e61_numemp";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->e61_numemp));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5664,'$this->e61_numemp','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e61_numemp"]))
          $resac = db_query("insert into db_acount values($acount,890,5664,'".AddSlashes(pg_result($resaco,$conresaco,'e61_numemp'))."','$this->e61_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e61_autori"]))
          $resac = db_query("insert into db_acount values($acount,890,5665,'".AddSlashes(pg_result($resaco,$conresaco,'e61_autori'))."','$this->e61_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Autorizações empenhadas nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->e61_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Autorizações empenhadas nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->e61_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->e61_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($e61_numemp=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($e61_numemp));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5664,'$e61_numemp','E')");
        $resac = db_query("insert into db_acount values($acount,890,5664,'','".AddSlashes(pg_result($resaco,$iresaco,'e61_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,890,5665,'','".AddSlashes(pg_result($resaco,$iresaco,'e61_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from empempaut
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($e61_numemp != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e61_numemp = $e61_numemp ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Autorizações empenhadas nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$e61_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Autorizações empenhadas nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$e61_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$e61_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset
  function sql_record($sql) {
    $result = db_query($sql);
    if($result==false){
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if($this->numrows==0){
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:empempaut";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_query ( $e61_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempaut ";
    $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empempaut.e61_autori";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempaut.e61_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
    $sql .= "      inner join cgm  as a on   a.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  as b on   b.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql2 = "";
    if($dbwhere==""){
      if($e61_numemp!=null ){
        $sql2 .= " where empempaut.e61_numemp = $e61_numemp ";
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
  function sql_query_empenho ( $e61_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempaut ";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempaut.e61_numemp";
    $sql2 = "";
    if($dbwhere==""){
      if($e61_numemp!=null ){
        $sql2 .= " where empempaut.e61_numemp = $e61_numemp ";
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
  function sql_query_file ( $e61_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempaut ";
    $sql2 = "";
    if($dbwhere==""){
      if($e61_numemp!=null ){
        $sql2 .= " where empempaut.e61_numemp = $e61_numemp ";
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

  function sql_query_empenho_inscricaopassivo ($e61_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempaut ";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempaut.e61_numemp";
    $sql .= "      inner join empautorizainscricaopassivo on  empautorizainscricaopassivo.e16_empautoriza = empempaut.e61_autori";
    $sql .= "      inner join empautoriza on  empautorizainscricaopassivo.e16_empautoriza = empautoriza.e54_autori";
    $sql2 = "";
    if($dbwhere==""){
      if($e61_numemp!=null ){
        $sql2 .= " where empempaut.e61_numemp = $e61_numemp ";
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


  public function sql_query_empenho_autorizacao ($e61_numemp=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = " select {$campos} ";
    $sql .= "   from empempaut ";
    $sql .= "        inner join empempenho   on  empempenho.e60_numemp  = empempaut.e61_numemp ";
    $sql .= "        inner join empautoriza  on  empautoriza.e54_autori = empempaut.e61_autori ";
    $sql .= "        inner join empautitem   on  empautitem.e55_autori  = empautoriza.e54_autori ";
    $sql .= "        inner join empempitem   on  empempitem.e62_numemp  = empempenho.e60_numemp ";
    $sql .= "        left  join matunid      on  matunid.m61_codmatunid  = empautitem.e55_matunid ";
    $sql2 = "";

    if (empty($dbwhere)) {

      if (!empty($e61_numemp)) {
        $sql2 .= " where empempaut.e61_numemp = {$e61_numemp} ";
      }
    }else if(!empty($dbwhere)){
      $sql2 = " where {$dbwhere} ";
    }

    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }
    return $sql;
  }
}
?>