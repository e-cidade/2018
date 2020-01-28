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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conhistdoc
class cl_conhistdoc {
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
  var $c53_coddoc = 0;
  var $c53_descr = null;
  var $c53_tipo = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c53_coddoc = int4 = Código do documento 
                 c53_descr = varchar(50) = Descrição 
                 c53_tipo = int4 = Tipo de Lançamento 
                 ";
  //funcao construtor da classe
  function cl_conhistdoc() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("conhistdoc");
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
      $this->c53_coddoc = ($this->c53_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c53_coddoc"]:$this->c53_coddoc);
      $this->c53_descr = ($this->c53_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c53_descr"]:$this->c53_descr);
      $this->c53_tipo = ($this->c53_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c53_tipo"]:$this->c53_tipo);
    }else{
      $this->c53_coddoc = ($this->c53_coddoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c53_coddoc"]:$this->c53_coddoc);
    }
  }
  // funcao para inclusao
  function incluir ($c53_coddoc){
    $this->atualizacampos();
    if($this->c53_descr == null ){
      $this->erro_sql = " Campo Descrição nao Informado.";
      $this->erro_campo = "c53_descr";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c53_tipo == null ){
      $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
      $this->erro_campo = "c53_tipo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->c53_coddoc = $c53_coddoc;
    if(($this->c53_coddoc == null) || ($this->c53_coddoc == "") ){
      $this->erro_sql = " Campo c53_coddoc nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into conhistdoc(
                                       c53_coddoc 
                                      ,c53_descr 
                                      ,c53_tipo 
                       )
                values (
                                $this->c53_coddoc 
                               ,'$this->c53_descr' 
                               ,$this->c53_tipo 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Históricos de Lançamentos ($this->c53_coddoc) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Históricos de Lançamentos já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Históricos de Lançamentos ($this->c53_coddoc) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->c53_coddoc;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->c53_coddoc));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,5434,'$this->c53_coddoc','I')");
      $resac = db_query("insert into db_acount values($acount,807,5434,'','".AddSlashes(pg_result($resaco,0,'c53_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,807,5435,'','".AddSlashes(pg_result($resaco,0,'c53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,807,5436,'','".AddSlashes(pg_result($resaco,0,'c53_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($c53_coddoc=null) {
    $this->atualizacampos();
    $sql = " update conhistdoc set ";
    $virgula = "";
    if(trim($this->c53_coddoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c53_coddoc"])){
      $sql  .= $virgula." c53_coddoc = $this->c53_coddoc ";
      $virgula = ",";
      if(trim($this->c53_coddoc) == null ){
        $this->erro_sql = " Campo Código do documento nao Informado.";
        $this->erro_campo = "c53_coddoc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c53_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c53_descr"])){
      $sql  .= $virgula." c53_descr = '$this->c53_descr' ";
      $virgula = ",";
      if(trim($this->c53_descr) == null ){
        $this->erro_sql = " Campo Descrição nao Informado.";
        $this->erro_campo = "c53_descr";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c53_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c53_tipo"])){
      $sql  .= $virgula." c53_tipo = $this->c53_tipo ";
      $virgula = ",";
      if(trim($this->c53_tipo) == null ){
        $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
        $this->erro_campo = "c53_tipo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($c53_coddoc!=null){
      $sql .= " c53_coddoc = $this->c53_coddoc";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->c53_coddoc));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5434,'$this->c53_coddoc','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c53_coddoc"]))
          $resac = db_query("insert into db_acount values($acount,807,5434,'".AddSlashes(pg_result($resaco,$conresaco,'c53_coddoc'))."','$this->c53_coddoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c53_descr"]))
          $resac = db_query("insert into db_acount values($acount,807,5435,'".AddSlashes(pg_result($resaco,$conresaco,'c53_descr'))."','$this->c53_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c53_tipo"]))
          $resac = db_query("insert into db_acount values($acount,807,5436,'".AddSlashes(pg_result($resaco,$conresaco,'c53_tipo'))."','$this->c53_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Históricos de Lançamentos nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->c53_coddoc;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Históricos de Lançamentos nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->c53_coddoc;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c53_coddoc;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($c53_coddoc=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($c53_coddoc));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5434,'$c53_coddoc','E')");
        $resac = db_query("insert into db_acount values($acount,807,5434,'','".AddSlashes(pg_result($resaco,$iresaco,'c53_coddoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,807,5435,'','".AddSlashes(pg_result($resaco,$iresaco,'c53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,807,5436,'','".AddSlashes(pg_result($resaco,$iresaco,'c53_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from conhistdoc
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($c53_coddoc != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " c53_coddoc = $c53_coddoc ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Históricos de Lançamentos nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$c53_coddoc;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Históricos de Lançamentos nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$c53_coddoc;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$c53_coddoc;
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
      $this->erro_sql   = "Record Vazio na Tabela:conhistdoc";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_query ( $c53_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conhistdoc ";
    $sql2 = "";
    if($dbwhere==""){
      if($c53_coddoc!=null ){
        $sql2 .= " where conhistdoc.c53_coddoc = $c53_coddoc ";
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
  function sql_query_file ( $c53_coddoc=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conhistdoc ";
    $sql2 = "";
    if($dbwhere==""){
      if($c53_coddoc!=null ){
        $sql2 .= " where conhistdoc.c53_coddoc = $c53_coddoc ";
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

  function sql_query_documentos ( $c45_seqtrans=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $iAnoSessao         = db_getsession("DB_anousu");
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sql .= " from conhistdoc ";
    $sql .= "      left join contrans  on contrans.c45_coddoc = conhistdoc.c53_coddoc";
    $sql .= "                         and contrans.c45_anousu   = {$iAnoSessao}";
    $sql .= "                         and contrans.c45_instit   = {$iInstituicaoSessao}";


    $sql2 = "";
    if($dbwhere==""){
      if($c45_seqtrans!=null ){
        $sql2 .= " where contrans.c45_seqtrans = $c45_seqtrans ";
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

  public function sql_query_documento_evento_contabil($sCampos = "*", $sWhere = null, $sOrder = null) {

    $sql  = "select {$sCampos} ";
    $sql .= " from conhistdoc ";
    $sql .= "      inner join conlancamdoc on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc";

    if (!empty($sWhere)) {
      $sql .= " where {$sWhere} ";
    }
    if (!empty($sOrder)) {
      $sql .= " order by {$sOrder} ";
    }


    return $sql;
  }

    /**
     * @param $c53_coddoc
     * @param $c75_numemp
     * @return string
     */
  public function sql_query_empenhos($sWhere = null) {
      $sql = "select
          c53_coddoc,
          c70_codlan,
          c70_data,
          c53_descr,
          c70_valor
        from
          conlancamemp
          inner join conlancam on c70_codlan = c75_codlan
          inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan
          left outer join conlancampag on c82_codlan = c70_codlan
          inner join conlancamdoc on c71_codlan = c70_codlan
          inner join conhistdoc on c53_coddoc = c71_coddoc";

      if (!empty($sWhere)) {
          $sql .= " where {$sWhere} ";
      }

      return $sql;
  }

}
?>
