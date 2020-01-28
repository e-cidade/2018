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
//CLASSE DA ENTIDADE conplanoexe
class cl_conplanoexe {
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
  var $c62_anousu = 0;
  var $c62_reduz = 0;
  var $c62_codrec = 0;
  var $c62_vlrcre = 0;
  var $c62_vlrdeb = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c62_anousu = int4 = Exercício
                 c62_reduz = int4 = Reduzido
                 c62_codrec = int4 = Codigo do Recurso
                 c62_vlrcre = float8 = Saldo Abertura a Credito
                 c62_vlrdeb = float8 = Saldo Abertura a Débito
                 ";
  //funcao construtor da classe
  function cl_conplanoexe() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("conplanoexe");
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
      $this->c62_anousu = ($this->c62_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_anousu"]:$this->c62_anousu);
      $this->c62_reduz = ($this->c62_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_reduz"]:$this->c62_reduz);
      $this->c62_codrec = ($this->c62_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_codrec"]:$this->c62_codrec);
      $this->c62_vlrcre = ($this->c62_vlrcre == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_vlrcre"]:$this->c62_vlrcre);
      $this->c62_vlrdeb = ($this->c62_vlrdeb == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_vlrdeb"]:$this->c62_vlrdeb);
    }else{
      $this->c62_anousu = ($this->c62_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_anousu"]:$this->c62_anousu);
      $this->c62_reduz = ($this->c62_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c62_reduz"]:$this->c62_reduz);
    }
  }
  // funcao para inclusao
  function incluir ($c62_anousu,$c62_reduz){
    $this->atualizacampos();
    if($this->c62_codrec == null ){
      $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
      $this->erro_campo = "c62_codrec";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c62_vlrcre == null ){
      $this->erro_sql = " Campo Saldo Abertura a Credito nao Informado.";
      $this->erro_campo = "c62_vlrcre";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c62_vlrdeb == null ){
      $this->erro_sql = " Campo Saldo Abertura a Débito nao Informado.";
      $this->erro_campo = "c62_vlrdeb";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->c62_anousu = $c62_anousu;
    $this->c62_reduz = $c62_reduz;
    if(($this->c62_anousu == null) || ($this->c62_anousu == "") ){
      $this->erro_sql = " Campo c62_anousu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if(($this->c62_reduz == null) || ($this->c62_reduz == "") ){
      $this->erro_sql = " Campo c62_reduz nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into conplanoexe(
                                       c62_anousu
                                      ,c62_reduz
                                      ,c62_codrec
                                      ,c62_vlrcre
                                      ,c62_vlrdeb
                       )
                values (
                                $this->c62_anousu
                               ,$this->c62_reduz
                               ,$this->c62_codrec
                               ,$this->c62_vlrcre
                               ,$this->c62_vlrdeb
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Plano de Contas Exercício ($this->c62_anousu."-".$this->c62_reduz) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Plano de Contas Exercício já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Plano de Contas Exercício ($this->c62_anousu."-".$this->c62_reduz) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->c62_anousu."-".$this->c62_reduz;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file_log($this->c62_anousu,$this->c62_reduz));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,5226,'$this->c62_anousu','I')");
      $resac = db_query("insert into db_acountkey values($acount,5227,'$this->c62_reduz','I')");
      $resac = db_query("insert into db_acount values($acount,789,5226,'','".AddSlashes(pg_result($resaco,0,'c62_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,789,5227,'','".AddSlashes(pg_result($resaco,0,'c62_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,789,5228,'','".AddSlashes(pg_result($resaco,0,'c62_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,789,5229,'','".AddSlashes(pg_result($resaco,0,'c62_vlrcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,789,5230,'','".AddSlashes(pg_result($resaco,0,'c62_vlrdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($c62_anousu=null,$c62_reduz=null) {
    $this->atualizacampos();
    $sql = " update conplanoexe set ";
    $virgula = "";
    if(trim($this->c62_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c62_anousu"])){
      $sql  .= $virgula." c62_anousu = $this->c62_anousu ";
      $virgula = ",";
      if(trim($this->c62_anousu) == null ){
        $this->erro_sql = " Campo Exercício nao Informado.";
        $this->erro_campo = "c62_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c62_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c62_reduz"])){
      $sql  .= $virgula." c62_reduz = $this->c62_reduz ";
      $virgula = ",";
      if(trim($this->c62_reduz) == null ){
        $this->erro_sql = " Campo Reduzido nao Informado.";
        $this->erro_campo = "c62_reduz";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c62_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c62_codrec"])){
      $sql  .= $virgula." c62_codrec = $this->c62_codrec ";
      $virgula = ",";
      if(trim($this->c62_codrec) == null ){
        $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
        $this->erro_campo = "c62_codrec";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c62_vlrcre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c62_vlrcre"])){
      $sql  .= $virgula." c62_vlrcre = $this->c62_vlrcre ";
      $virgula = ",";
      if(trim($this->c62_vlrcre) == null ){
        $this->erro_sql = " Campo Saldo Abertura a Credito nao Informado.";
        $this->erro_campo = "c62_vlrcre";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c62_vlrdeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c62_vlrdeb"])){
      $sql  .= $virgula." c62_vlrdeb = $this->c62_vlrdeb ";
      $virgula = ",";
      if(trim($this->c62_vlrdeb) == null ){
        $this->erro_sql = " Campo Saldo Abertura a Débito nao Informado.";
        $this->erro_campo = "c62_vlrdeb";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($c62_anousu!=null){
      $sql .= " c62_anousu = $this->c62_anousu";
    }
    if($c62_reduz!=null){
      $sql .= " and  c62_reduz = $this->c62_reduz";
    }
    $resaco = $this->sql_record($this->sql_query_file_log($this->c62_anousu,$this->c62_reduz));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5226,'$this->c62_anousu','A')");
        $resac = db_query("insert into db_acountkey values($acount,5227,'$this->c62_reduz','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c62_anousu"]))
          $resac = db_query("insert into db_acount values($acount,789,5226,'".AddSlashes(pg_result($resaco,$conresaco,'c62_anousu'))."','$this->c62_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c62_reduz"]))
          $resac = db_query("insert into db_acount values($acount,789,5227,'".AddSlashes(pg_result($resaco,$conresaco,'c62_reduz'))."','$this->c62_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c62_codrec"]))
          $resac = db_query("insert into db_acount values($acount,789,5228,'".AddSlashes(pg_result($resaco,$conresaco,'c62_codrec'))."','$this->c62_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c62_vlrcre"]))
          $resac = db_query("insert into db_acount values($acount,789,5229,'".AddSlashes(pg_result($resaco,$conresaco,'c62_vlrcre'))."','$this->c62_vlrcre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["c62_vlrdeb"]))
          $resac = db_query("insert into db_acount values($acount,789,5230,'".AddSlashes(pg_result($resaco,$conresaco,'c62_vlrdeb'))."','$this->c62_vlrdeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Plano de Contas Exercício nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->c62_anousu."-".$this->c62_reduz;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Plano de Contas Exercício nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->c62_anousu."-".$this->c62_reduz;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c62_anousu."-".$this->c62_reduz;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($c62_anousu=null,$c62_reduz=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file_log($c62_anousu,$c62_reduz));
    }else{
      $resaco = $this->sql_record($this->sql_query_file_log(null,null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5226,'$c62_anousu','E')");
        $resac = db_query("insert into db_acountkey values($acount,5227,'$c62_reduz','E')");
        $resac = db_query("insert into db_acount values($acount,789,5226,'','".AddSlashes(pg_result($resaco,$iresaco,'c62_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,789,5227,'','".AddSlashes(pg_result($resaco,$iresaco,'c62_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,789,5228,'','".AddSlashes(pg_result($resaco,$iresaco,'c62_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,789,5229,'','".AddSlashes(pg_result($resaco,$iresaco,'c62_vlrcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,789,5230,'','".AddSlashes(pg_result($resaco,$iresaco,'c62_vlrdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from conplanoexe
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($c62_anousu != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " c62_anousu = $c62_anousu ";
      }
      if($c62_reduz != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " c62_reduz = $c62_reduz ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Plano de Contas Exercício nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$c62_anousu."-".$c62_reduz;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Plano de Contas Exercício nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$c62_anousu."-".$c62_reduz;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$c62_anousu."-".$c62_reduz;
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
      $this->erro_sql   = "Record Vazio na Tabela:conplanoexe";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_conta_debitar( $c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoexe ";
    $sql .= "     inner join conplanoreduz on conplanoreduz.c61_reduz=conplanoexe.c62_reduz and c61_anousu=c62_anousu";
    $sql .= "     inner join conplano      on conplano.c60_codcon=conplanoreduz.c61_codcon  and c61_anousu=c60_anousu";
    if (USE_PCASP) {

      $sql .= " and substring(c60_estrut, 1, 1) in ('1', '2')";
      $sql .= " inner join consistema   on consistema.c52_codsis =  conplano.c60_codsis";
      $sql .= "                        and c52_descrred = 'F'";
    } else {
      $sql .= "   and substring(c60_estrut, 1, 1) not in ('3', '4', '9')";
    }
    $sql .= "     left join orcreceita on orcreceita.o70_codfon     = conplanoreduz.c61_codcon  and
                                           orcreceita.o70_anousu     = conplanoreduz.c61_anousu";
    $sql .= "     left join orctiporec  on orctiporec.o15_codigo    = orcreceita.o70_codigo";
    $sql .= "     left  join taborc        on taborc.k02_codrec     = orcreceita.o70_codrec and";
    $sql .= "                                 taborc.k02_anousu     = orcreceita.o70_anousu";
    $sql .= "     left  join tabplan       on tabplan.k02_reduz     = conplanoexe.c62_reduz and";
    $sql .= "                                 tabplan.k02_anousu    = conplanoexe.c62_anousu";
    $sql .= "     left  join saltes        on saltes.k13_reduz      = conplanoexe.c62_reduz";
    $sql .= "     left  join tabrec t1     on t1.k02_codigo         = taborc.k02_codigo";
    $sql .= "     left  join tabrec t2     on t2.k02_codigo         = tabplan.k02_codigo";
    $sql2 = "";

    if($dbwhere==""){
      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
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
  function sql_descr( $c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoexe ";
    $sql .= "     inner join conplanoreduz on conplanoreduz.c61_reduz=conplanoexe.c62_reduz and c61_anousu=c62_anousu";
    $sql .= "     inner join conplano      on conplano.c60_codcon=conplanoreduz.c61_codcon  and c61_anousu=c60_anousu";
    $sql .= "     inner join orctiporec    on orctiporec.o15_codigo = conplanoexe.c62_codrec";
    $sql2 = "";

    if($dbwhere==""){
      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
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
  function sql_query ( $c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoexe ";
    $sql .= "      inner join orctiporec     on orctiporec.o15_codigo = conplanoexe.c62_codrec";
    $sql .= "      inner join conplanoreduz  on conplanoreduz.c61_reduz = conplanoexe.c62_reduz ";
    $sql .= "                               and conplanoreduz.c61_anousu = conplanoexe.c62_anousu";
    $sql .= "      inner join conplano       on conplano.c60_codcon = conplanoreduz.c61_codcon";
    $sql .= "                               and conplano.c60_anousu = conplanoreduz.c61_anousu";
    $sql2 = "";
    if($dbwhere==""){
      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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
  function sql_query_file ( $c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoexe ";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = conplanoexe.c62_reduz  and c61_anousu=c62_anousu";
    $sql2 = "";
    if($dbwhere==""){
      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
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

  public function sql_query_reduzido ($c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql  = " select {$campos}";
    $sql .= "   from conplanoexe ";
    $sql .= "        inner join conplanoreduz  on  conplanoreduz.c61_reduz = conplanoexe.c62_reduz and c61_anousu = c62_anousu";
    $sql2 = "";

    if(empty($dbwhere)){

      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;

    if(!empty($ordem)){
      $sql .= " order by {$ordem} ";
    }
    return $sql;
  }

  function sql_query_file_log ( $c62_anousu=null,$c62_reduz=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from conplanoexe ";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_reduz = conplanoexe.c62_reduz  and c61_anousu=c62_anousu";
    $sql2 = "";
    if($dbwhere==""){
      if($c62_anousu!=null ){
        $sql2 .= " where conplanoexe.c62_anousu = $c62_anousu ";
      }
      if($c62_reduz!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " conplanoexe.c62_reduz = $c62_reduz ";
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

  public function sql_query_implantacao_saldos($sDataInicio, $sDataFim, $iInstituicao, $iExercicio) {

    $iProximoExercicio = $iExercicio + 1;

    $sSql  = " select ";
		$sSql .= "        c60_anousu, ";
		$sSql .= " 		    c61_reduz, ";
		$sSql .= " 		    c61_codigo, ";
		$sSql .= "        round(substr(fc_planosaldonovo,3,14)::float8,2)::float8  as saldo_anterior, ";
		$sSql .= " 		    round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito, ";
		$sSql .= " 		    round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito, ";
		$sSql .= " 		    round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final, ";
		$sSql .= " 		    substr(fc_planosaldonovo,59,1)::varchar(1)               as sinal_anterior, ";
		$sSql .= " 		    substr(fc_planosaldonovo,60,1)::varchar(1)               as sinal_final ";
		$sSql .= " from ( ";
		$sSql .= " 	     select ";
		$sSql .= " 	            c60_anousu, ";
    $sSql .= "              c61_reduz, ";
    $sSql .= "              c61_codigo, ";
    $sSql .= "              fc_planosaldonovo(c60_anousu, c61_reduz, '{$sDataInicio}', '{$sDataFim}', true) ";
		$sSql .= " 	     from conplanoexe ";
		$sSql .= " 	          inner join conplanoreduz on c61_reduz  = c62_reduz  and c61_anousu = c62_anousu ";
		$sSql .= " 	          inner join conplano      on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
		$sSql .= " 	     where c61_anousu = {$iExercicio} and ";
		$sSql .= " 	           c61_instit = {$iInstituicao} ";
		$sSql .= " 	     group by c60_anousu, c61_reduz, c61_codigo ";
		$sSql .= " ) as x ";
		$sSql .= "      inner join conplanoexe exeant on exeant.c62_anousu = c60_anousu           and exeant.c62_reduz = c61_reduz ";
		$sSql .= "      inner join conplanoexe exe    on exe.c62_anousu    = {$iProximoExercicio} and exe.c62_reduz    = c61_reduz ";

    return $sSql;
  }
}