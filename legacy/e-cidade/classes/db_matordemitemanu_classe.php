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

//MODULO: material
//CLASSE DA ENTIDADE matordemitemanu
class cl_matordemitemanu {
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
  var $m36_sequencial = 0;
  var $m36_matordemitem = 0;
  var $m36_matordemanul = 0;
  var $m36_vrlanu = 0;
  var $m36_qtd = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 m36_sequencial = int4 = Código Sequencial 
                 m36_matordemitem = int4 = Códido do Item 
                 m36_matordemanul = int4 = Código da Anulação 
                 m36_vrlanu = float4 = Valor Anulado 
                 m36_qtd = float4 = Quantidade Anulada 
                 ";
  //funcao construtor da classe
  function cl_matordemitemanu() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("matordemitemanu");
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
      $this->m36_sequencial = ($this->m36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_sequencial"]:$this->m36_sequencial);
      $this->m36_matordemitem = ($this->m36_matordemitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_matordemitem"]:$this->m36_matordemitem);
      $this->m36_matordemanul = ($this->m36_matordemanul == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_matordemanul"]:$this->m36_matordemanul);
      $this->m36_vrlanu = ($this->m36_vrlanu == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_vrlanu"]:$this->m36_vrlanu);
      $this->m36_qtd = ($this->m36_qtd == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_qtd"]:$this->m36_qtd);
    }else{
      $this->m36_sequencial = ($this->m36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m36_sequencial"]:$this->m36_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($m36_sequencial){
    $this->atualizacampos();
    if($this->m36_matordemitem == null ){
      $this->erro_sql = " Campo Códido do Item nao Informado.";
      $this->erro_campo = "m36_matordemitem";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->m36_matordemanul == null ){
      $this->erro_sql = " Campo Código da Anulação nao Informado.";
      $this->erro_campo = "m36_matordemanul";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->m36_vrlanu == null ){
      $this->erro_sql = " Campo Valor Anulado nao Informado.";
      $this->erro_campo = "m36_vrlanu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->m36_qtd == null ){
      $this->erro_sql = " Campo Quantidade Anulada nao Informado.";
      $this->erro_campo = "m36_qtd";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($m36_sequencial == "" || $m36_sequencial == null ){
      $result = db_query("select nextval('matordemitemanu_m36_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: matordemitemanu_m36_sequencial_seq do campo: m36_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->m36_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from matordemitemanu_m36_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $m36_sequencial)){
        $this->erro_sql = " Campo m36_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->m36_sequencial = $m36_sequencial;
      }
    }
    if(($this->m36_sequencial == null) || ($this->m36_sequencial == "") ){
      $this->erro_sql = " Campo m36_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into matordemitemanu(
                                       m36_sequencial 
                                      ,m36_matordemitem 
                                      ,m36_matordemanul 
                                      ,m36_vrlanu 
                                      ,m36_qtd 
                       )
                values (
                                $this->m36_sequencial 
                               ,$this->m36_matordemitem 
                               ,$this->m36_matordemanul 
                               ,$this->m36_vrlanu 
                               ,$this->m36_qtd 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Itens anulados da ordem de compra ($this->m36_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Itens anulados da ordem de compra já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Itens anulados da ordem de compra ($this->m36_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->m36_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->m36_sequencial));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,10923,'$this->m36_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,1886,10923,'','".AddSlashes(pg_result($resaco,0,'m36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1886,10924,'','".AddSlashes(pg_result($resaco,0,'m36_matordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1886,10985,'','".AddSlashes(pg_result($resaco,0,'m36_matordemanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1886,10925,'','".AddSlashes(pg_result($resaco,0,'m36_vrlanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1886,10926,'','".AddSlashes(pg_result($resaco,0,'m36_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($m36_sequencial=null) {
    $this->atualizacampos();
    $sql = " update matordemitemanu set ";
    $virgula = "";
    if(trim($this->m36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m36_sequencial"])){
      $sql  .= $virgula." m36_sequencial = $this->m36_sequencial ";
      $virgula = ",";
      if(trim($this->m36_sequencial) == null ){
        $this->erro_sql = " Campo Código Sequencial nao Informado.";
        $this->erro_campo = "m36_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->m36_matordemitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m36_matordemitem"])){
      $sql  .= $virgula." m36_matordemitem = $this->m36_matordemitem ";
      $virgula = ",";
      if(trim($this->m36_matordemitem) == null ){
        $this->erro_sql = " Campo Códido do Item nao Informado.";
        $this->erro_campo = "m36_matordemitem";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->m36_matordemanul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m36_matordemanul"])){
      $sql  .= $virgula." m36_matordemanul = $this->m36_matordemanul ";
      $virgula = ",";
      if(trim($this->m36_matordemanul) == null ){
        $this->erro_sql = " Campo Código da Anulação nao Informado.";
        $this->erro_campo = "m36_matordemanul";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->m36_vrlanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m36_vrlanu"])){
      $sql  .= $virgula." m36_vrlanu = $this->m36_vrlanu ";
      $virgula = ",";
      if(trim($this->m36_vrlanu) == null ){
        $this->erro_sql = " Campo Valor Anulado nao Informado.";
        $this->erro_campo = "m36_vrlanu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->m36_qtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m36_qtd"])){
      $sql  .= $virgula." m36_qtd = $this->m36_qtd ";
      $virgula = ",";
      if(trim($this->m36_qtd) == null ){
        $this->erro_sql = " Campo Quantidade Anulada nao Informado.";
        $this->erro_campo = "m36_qtd";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($m36_sequencial!=null){
      $sql .= " m36_sequencial = $this->m36_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->m36_sequencial));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,10923,'$this->m36_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["m36_sequencial"]))
          $resac = db_query("insert into db_acount values($acount,1886,10923,'".AddSlashes(pg_result($resaco,$conresaco,'m36_sequencial'))."','$this->m36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["m36_matordemitem"]))
          $resac = db_query("insert into db_acount values($acount,1886,10924,'".AddSlashes(pg_result($resaco,$conresaco,'m36_matordemitem'))."','$this->m36_matordemitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["m36_matordemanul"]))
          $resac = db_query("insert into db_acount values($acount,1886,10985,'".AddSlashes(pg_result($resaco,$conresaco,'m36_matordemanul'))."','$this->m36_matordemanul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["m36_vrlanu"]))
          $resac = db_query("insert into db_acount values($acount,1886,10925,'".AddSlashes(pg_result($resaco,$conresaco,'m36_vrlanu'))."','$this->m36_vrlanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["m36_qtd"]))
          $resac = db_query("insert into db_acount values($acount,1886,10926,'".AddSlashes(pg_result($resaco,$conresaco,'m36_qtd'))."','$this->m36_qtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Itens anulados da ordem de compra nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->m36_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Itens anulados da ordem de compra nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->m36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->m36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($m36_sequencial=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($m36_sequencial));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,10923,'$m36_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,1886,10923,'','".AddSlashes(pg_result($resaco,$iresaco,'m36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1886,10924,'','".AddSlashes(pg_result($resaco,$iresaco,'m36_matordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1886,10985,'','".AddSlashes(pg_result($resaco,$iresaco,'m36_matordemanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1886,10925,'','".AddSlashes(pg_result($resaco,$iresaco,'m36_vrlanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1886,10926,'','".AddSlashes(pg_result($resaco,$iresaco,'m36_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from matordemitemanu
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($m36_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " m36_sequencial = $m36_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Itens anulados da ordem de compra nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$m36_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Itens anulados da ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$m36_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$m36_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:matordemitemanu";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_query ( $m36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matordemitemanu ";
    $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matordemitemanu.m36_matordemitem";
    $sql .= "      inner join matordemanul  on  matordemanul.m37_sequencial = matordemitemanu.m36_matordemanul";
    $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
    $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matordemanul.m37_usuario";
    $sql2 = "";
    if($dbwhere==""){
      if($m36_sequencial!=null ){
        $sql2 .= " where matordemitemanu.m36_sequencial = $m36_sequencial ";
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
  function sql_query_file ( $m36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matordemitemanu ";
    $sql2 = "";
    if($dbwhere==""){
      if($m36_sequencial!=null ){
        $sql2 .= " where matordemitemanu.m36_sequencial = $m36_sequencial ";
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

  function sql_query_file_anulado ($sCampos = "*", $sWhere = null){

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from matordemitemanu ";
    $sSql .= "        inner join matordemitem on matordemitem.m52_codlanc = matordemitemanu.m36_matordemitem";
    $sSql .= "        inner join matordem on matordem.m51_codordem = matordemitem.m52_codordem ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    return $sSql;
  }
}