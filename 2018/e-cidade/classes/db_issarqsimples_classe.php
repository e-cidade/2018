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

//MODULO: issqn
//CLASSE DA ENTIDADE issarqsimples
class cl_issarqsimples {
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
  var $q17_sequencial = 0;
  var $q17_instit = 0;
  var $q17_data_dia = null;
  var $q17_data_mes = null;
  var $q17_data_ano = null;
  var $q17_data = null;
  var $q17_nroremessa = 0;
  var $q17_versao = 0;
  var $q17_qtdreg = 0;
  var $q17_vlrtot = 0;
  var $q17_codbco = null;
  var $q17_oidarq = 0;
  var $q17_nomearq = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
  q17_sequencial = int4 = Sequencial
  q17_instit = int4 = Cod. Instituição
  q17_data = date = Data
  q17_nroremessa = int4 = Numero da Remessa
  q17_versao = int4 = Versao
  q17_qtdreg = int4 = Quantidade
  q17_vlrtot = float8 = Valor Total
  q17_codbco = char(3) = Código Banco
  q17_oidarq = oid = Arquivo
  q17_nomearq = varchar(100) = Nome Arquivo
  ";
  //funcao construtor da classe
  function cl_issarqsimples() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("issarqsimples");
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
      $this->q17_sequencial = ($this->q17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_sequencial"]:$this->q17_sequencial);
      $this->q17_instit = ($this->q17_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_instit"]:$this->q17_instit);
      if($this->q17_data == ""){
        $this->q17_data_dia = ($this->q17_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_data_dia"]:$this->q17_data_dia);
        $this->q17_data_mes = ($this->q17_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_data_mes"]:$this->q17_data_mes);
        $this->q17_data_ano = ($this->q17_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_data_ano"]:$this->q17_data_ano);
        if($this->q17_data_dia != ""){
          $this->q17_data = $this->q17_data_ano."-".$this->q17_data_mes."-".$this->q17_data_dia;
        }
      }
      $this->q17_nroremessa = ($this->q17_nroremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_nroremessa"]:$this->q17_nroremessa);
      $this->q17_versao = ($this->q17_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_versao"]:$this->q17_versao);
      $this->q17_qtdreg = ($this->q17_qtdreg == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_qtdreg"]:$this->q17_qtdreg);
      $this->q17_vlrtot = ($this->q17_vlrtot == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_vlrtot"]:$this->q17_vlrtot);
      $this->q17_codbco = ($this->q17_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_codbco"]:$this->q17_codbco);
      $this->q17_oidarq = ($this->q17_oidarq == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_oidarq"]:$this->q17_oidarq);
      $this->q17_nomearq = ($this->q17_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_nomearq"]:$this->q17_nomearq);
    }else{
      $this->q17_sequencial = ($this->q17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q17_sequencial"]:$this->q17_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($q17_sequencial){
    $this->atualizacampos();
    if($this->q17_instit == null ){
      $this->erro_sql = " Campo Cod. Instituição nao Informado.";
      $this->erro_campo = "q17_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->q17_data == null ){
      $this->q17_data = "null";
    }
    if($this->q17_nroremessa == null ){
      $this->q17_nroremessa = "0";
    }
    if($this->q17_versao == null ){
      $this->q17_versao = "0";
    }
    if($this->q17_qtdreg == null ){
      $this->q17_qtdreg = "0";
    }
    if($this->q17_vlrtot == null ){
      $this->q17_vlrtot = "0";
    }
    if($this->q17_oidarq == null ){
      $this->erro_sql = " Campo Arquivo nao Informado.";
      $this->erro_campo = "q17_oidarq";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($q17_sequencial == "" || $q17_sequencial == null ){
      $result = db_query("select nextval('issarqsimples_q17_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: issarqsimples_q17_sequencial_seq do campo: q17_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->q17_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from issarqsimples_q17_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $q17_sequencial)){
        $this->erro_sql = " Campo q17_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->q17_sequencial = $q17_sequencial;
      }
    }
    if(($this->q17_sequencial == null) || ($this->q17_sequencial == "") ){
      $this->erro_sql = " Campo q17_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into issarqsimples(
    q17_sequencial
    ,q17_instit
    ,q17_data
    ,q17_nroremessa
    ,q17_versao
    ,q17_qtdreg
    ,q17_vlrtot
    ,q17_codbco
    ,q17_oidarq
    ,q17_nomearq
    )
    values (
    $this->q17_sequencial
    ,$this->q17_instit
    ,".($this->q17_data == "null" || $this->q17_data == ""?"null":"'".$this->q17_data."'")."
    ,$this->q17_nroremessa
    ,$this->q17_versao
    ,$this->q17_qtdreg
    ,$this->q17_vlrtot
    ,'$this->q17_codbco'
    ,$this->q17_oidarq
    ,'$this->q17_nomearq'
    )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "issarqsimples ($this->q17_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "issarqsimples já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "issarqsimples ($this->q17_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->q17_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->q17_sequencial));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,10683,'$this->q17_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,1844,10683,'','".AddSlashes(pg_result($resaco,0,'q17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10717,'','".AddSlashes(pg_result($resaco,0,'q17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10684,'','".AddSlashes(pg_result($resaco,0,'q17_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10685,'','".AddSlashes(pg_result($resaco,0,'q17_nroremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10686,'','".AddSlashes(pg_result($resaco,0,'q17_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10687,'','".AddSlashes(pg_result($resaco,0,'q17_qtdreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10688,'','".AddSlashes(pg_result($resaco,0,'q17_vlrtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10689,'','".AddSlashes(pg_result($resaco,0,'q17_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10719,'','".AddSlashes(pg_result($resaco,0,'q17_oidarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1844,10720,'','".AddSlashes(pg_result($resaco,0,'q17_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($q17_sequencial=null) {
    $this->atualizacampos();
    $sql = " update issarqsimples set ";
    $virgula = "";
    if(trim($this->q17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_sequencial"])){
      $sql  .= $virgula." q17_sequencial = $this->q17_sequencial ";
      $virgula = ",";
      if(trim($this->q17_sequencial) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "q17_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->q17_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_instit"])){
      $sql  .= $virgula." q17_instit = $this->q17_instit ";
      $virgula = ",";
      if(trim($this->q17_instit) == null ){
        $this->erro_sql = " Campo Cod. Instituição nao Informado.";
        $this->erro_campo = "q17_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->q17_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q17_data_dia"] !="") ){
      $sql  .= $virgula." q17_data = '$this->q17_data' ";
      $virgula = ",";
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["q17_data_dia"])){
        $sql  .= $virgula." q17_data = null ";
        $virgula = ",";
      }
    }
    if(trim($this->q17_nroremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_nroremessa"])){
      if(trim($this->q17_nroremessa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q17_nroremessa"])){
        $this->q17_nroremessa = "0" ;
      }
      $sql  .= $virgula." q17_nroremessa = $this->q17_nroremessa ";
      $virgula = ",";
    }
    if(trim($this->q17_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_versao"])){
      if(trim($this->q17_versao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q17_versao"])){
        $this->q17_versao = "0" ;
      }
      $sql  .= $virgula." q17_versao = $this->q17_versao ";
      $virgula = ",";
    }
    if(trim($this->q17_qtdreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_qtdreg"])){
      if(trim($this->q17_qtdreg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q17_qtdreg"])){
        $this->q17_qtdreg = "0" ;
      }
      $sql  .= $virgula." q17_qtdreg = $this->q17_qtdreg ";
      $virgula = ",";
    }
    if(trim($this->q17_vlrtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_vlrtot"])){
      if(trim($this->q17_vlrtot)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q17_vlrtot"])){
        $this->q17_vlrtot = "0" ;
      }
      $sql  .= $virgula." q17_vlrtot = $this->q17_vlrtot ";
      $virgula = ",";
    }
    if(trim($this->q17_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_codbco"])){
      $sql  .= $virgula." q17_codbco = '$this->q17_codbco' ";
      $virgula = ",";
    }
    if(trim($this->q17_oidarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_oidarq"])){
      $sql  .= $virgula." q17_oidarq = $this->q17_oidarq ";
      $virgula = ",";
      if(trim($this->q17_oidarq) == null ){
        $this->erro_sql = " Campo Arquivo nao Informado.";
        $this->erro_campo = "q17_oidarq";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->q17_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q17_nomearq"])){
      $sql  .= $virgula." q17_nomearq = '$this->q17_nomearq' ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($q17_sequencial!=null){
      $sql .= " q17_sequencial = $this->q17_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->q17_sequencial));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,10683,'$this->q17_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_sequencial"]))
          $resac = db_query("insert into db_acount values($acount,1844,10683,'".AddSlashes(pg_result($resaco,$conresaco,'q17_sequencial'))."','$this->q17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_instit"]))
          $resac = db_query("insert into db_acount values($acount,1844,10717,'".AddSlashes(pg_result($resaco,$conresaco,'q17_instit'))."','$this->q17_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_data"]))
          $resac = db_query("insert into db_acount values($acount,1844,10684,'".AddSlashes(pg_result($resaco,$conresaco,'q17_data'))."','$this->q17_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_nroremessa"]))
          $resac = db_query("insert into db_acount values($acount,1844,10685,'".AddSlashes(pg_result($resaco,$conresaco,'q17_nroremessa'))."','$this->q17_nroremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_versao"]))
          $resac = db_query("insert into db_acount values($acount,1844,10686,'".AddSlashes(pg_result($resaco,$conresaco,'q17_versao'))."','$this->q17_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_qtdreg"]))
          $resac = db_query("insert into db_acount values($acount,1844,10687,'".AddSlashes(pg_result($resaco,$conresaco,'q17_qtdreg'))."','$this->q17_qtdreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_vlrtot"]))
          $resac = db_query("insert into db_acount values($acount,1844,10688,'".AddSlashes(pg_result($resaco,$conresaco,'q17_vlrtot'))."','$this->q17_vlrtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_codbco"]))
          $resac = db_query("insert into db_acount values($acount,1844,10689,'".AddSlashes(pg_result($resaco,$conresaco,'q17_codbco'))."','$this->q17_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_oidarq"]))
          $resac = db_query("insert into db_acount values($acount,1844,10719,'".AddSlashes(pg_result($resaco,$conresaco,'q17_oidarq'))."','$this->q17_oidarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["q17_nomearq"]))
          $resac = db_query("insert into db_acount values($acount,1844,10720,'".AddSlashes(pg_result($resaco,$conresaco,'q17_nomearq'))."','$this->q17_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "issarqsimples nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->q17_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "issarqsimples nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->q17_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->q17_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($q17_sequencial=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($q17_sequencial));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,10683,'$q17_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,1844,10683,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10717,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10684,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10685,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_nroremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10686,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10687,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_qtdreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10688,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_vlrtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10689,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10719,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_oidarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1844,10720,'','".AddSlashes(pg_result($resaco,$iresaco,'q17_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from issarqsimples
    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($q17_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " q17_sequencial = $q17_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "issarqsimples nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$q17_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "issarqsimples nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$q17_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$q17_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:issarqsimples";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_query ( $q17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issarqsimples ";
    $sql .= "      inner join db_config  on  db_config.codigo = issarqsimples.q17_instit";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sql2 = "";
    if($dbwhere==""){
      if($q17_sequencial!=null ){
        $sql2 .= " where issarqsimples.q17_sequencial = $q17_sequencial ";
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
  function sql_query_file ( $q17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issarqsimples ";
    $sql2 = "";
    if($dbwhere==""){
      if($q17_sequencial!=null ){
        $sql2 .= " where issarqsimples.q17_sequencial = $q17_sequencial ";
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
  function sql_query_processados( $q17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issarqsimples ";
    $sql .= "      inner join db_config            on  db_config.codigo  = issarqsimples.q17_instit";
    $sql .= "      inner join cgm                  on  z01_numcgm        = db_config.numcgm";
    $sql .= "      left  join issarqsimplesdisarq  on  q43_issarqsimples = q17_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($q17_sequencial!=null ){
        $sql2 .= " where issarqsimples.q17_sequencial = $q17_sequencial ";
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
   * Pré-processamento
   * verificamos as inconsistencias no arquivo antes do processamento
   * @param  integer $iCodigoArquivo
   * @return array   $aRetorno       array com inconsistencias
   */
  function validaArquivo($iCodigoArquivo) {

    $aErros                    = array();
    $oDaoIssArqSimplesReg      = new cl_issarqsimplesreg();
    $oDaoIssArqSimplesRegErro  = new cl_issarqsimplesregerro();
    $oDaoParISSQN              = new cl_parissqn();
    $oParametrosISSQN          = $oDaoParISSQN->getParametrosIssqn();

    if (!db_utils::inTransaction()) {
      throw new Exception("Não Existe Transação Ativa");
    }
    /**
     * Limpando Erros Existentes...
     */
    $sWhereExclusaoErros       = "q49_sequencial in (select q23_sequencial                               ";
    $sWhereExclusaoErros      .= "                     from issarqsimplesreg                             ";
    $sWhereExclusaoErros      .= "                    where q23_issarqsimples = {$iCodigoArquivo})";


    $oDaoIssArqSimplesRegErro->excluir(null, $sWhereExclusaoErros);

    if ( $oDaoIssArqSimplesRegErro->erro_status == "0" ) {
      throw new Exception("Erro ao Deletar inconsistências. Processamento cancelado.");
    }

    $iNumRowsReg               = $oDaoIssArqSimplesReg->numrows;

    /**
     * Colocado limit pois somente sera usado quando o registro for valido, ou seja quando houver apenas 1 cgm vinculado ao cnpj
     */
    $sCamposSqlRegistros       = "(select z01_numcgm                                                  ";
    $sCamposSqlRegistros      .= "   from cgm                                                         ";
    $sCamposSqlRegistros      .= "  where z01_cgccpf = issarqsimplesreg.q23_cnpj                      ";
    $sCamposSqlRegistros      .= "  limit 1)                                      as cgm_valido,      ";
    $sCamposSqlRegistros      .= "(select array_to_string(array_accum(distinct z01_numcgm), ', ')     ";
    $sCamposSqlRegistros      .= "   from cgm                                                         ";
    $sCamposSqlRegistros      .= "  where z01_cgccpf = issarqsimplesreg.q23_cnpj) as cgms_vinculados, ";
    $sCamposSqlRegistros      .= "*                                                                   ";
    $sSqlRegistros             = $oDaoIssArqSimplesReg->sql_query_registrosProcessamentoArquivo($sCamposSqlRegistros,"q23_issarqsimples = {$iCodigoArquivo}");

    $rsRegistros               = $oDaoIssArqSimplesReg->sql_record($sSqlRegistros);
    $aRegistros                = db_utils::getCollectionByRecord($rsRegistros);

    foreach ($aRegistros as $iIndiceRegistros => $oRegSimples) {

      $dVlrReg     = ($oRegSimples->q23_vlrprinc + $oRegSimples->q23_vlrjur + $oRegSimples->q23_vlrmul);
      $iNumpre     = null;
      $iNumpar     = null;

      if ( $oRegSimples->quantidade_cgm == 0 ) {

        $oErros            = new stdClass();
        $oErros->sTipo     = "ERRO";
        $oErros->iRegistro = $oRegSimples->q23_sequencial;
        $oErros->sCnpj     = $oRegSimples->q23_cnpj;
        $oErros->sDetalhe  = urlencode("CNPJ - ".$oRegSimples->q23_cnpj." não existe no cadastro do CGM");
        $oErros->sErro     = "semCgm";
        $aErros[$oRegSimples->q23_sequencial] = $oErros;

      } elseif ( $oRegSimples->quantidade_cgm > 1 ) {

        $oErros            = new stdClass();
        $oErros->sTipo     = "ERRO";
        $oErros->iRegistro = $oRegSimples->q23_sequencial;
        $oErros->sCnpj     = $oRegSimples->q23_cnpj;
        $oErros->sDetalhe  = urlencode("CNPJ - ".$oRegSimples->q23_cnpj."  cadastro nos cgms ($oRegSimples->cgms_vinculados)");
        $oErros->sErro     = "cnpjDuplicado";
        $aErros[$oRegSimples->q23_sequencial] = $oErros;

      }

      if ($oRegSimples->q23_acao == 1 && $oRegSimples->quantidade_cgm == 1) {

        $oErros                 = new stdClass();
        $oErros->sTipo          = "AVISO";
        $oErros->iRegistro      = $oRegSimples->q23_sequencial;
        $oErros->sCnpj          = $oRegSimples->q23_cnpj;
        $oErros->sDetalhe       = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}: ");
        $oErros->sDetalhe      .= urlencode("Será Lançado ISSQN Complementar para {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}");
        $oErros->sErro          = "avisoComplementar";
        $aErros[$oRegSimples->q23_sequencial] = $oErros;

      } else if ($oRegSimples->q23_acao == 0 && $oRegSimples->quantidade_cgm == 1) {

        $oDaoIssBase    = new cl_issbase();
        $sCamposIssBase = " distinct z01_numcgm,                                                                     \n";
        $sCamposIssBase.= " z01_cgccpf,                                                                              \n";
        $sCamposIssBase.= " q02_inscr,                                                                               \n";
        $sCamposIssBase.= " (select count(*)                                                                         \n";
        $sCamposIssBase.= "    from issarqsimplesregissbase                                                          \n";
        $sCamposIssBase.= "     where issarqsimplesregissbase.q134_issarqsimplesreg = {$oRegSimples->q23_sequencial} \n";
        $sCamposIssBase.= " ) as quantidade_vinculo,                                                                 \n";
        $sCamposIssBase.= " (select array_to_string(array_accum(q02_inscr), ', ')                                    \n";
        $sCamposIssBase.= "    from issarqsimplesregissbase                                                          \n";
        $sCamposIssBase.= "         inner join issarqsimplesreg on issarqsimplesregissbase.q134_issarqsimplesreg = issarqsimplesreg.q23_sequencial \n";
        $sCamposIssBase.= "         inner join issbase on q134_inscr = issbase.q02_inscr                             \n";
        $sCamposIssBase.= "   where q02_numcgm = cgm.z01_numcgm                                                      \n";
        $sCamposIssBase.= "     and q134_issarqsimplesreg = {$oRegSimples->q23_sequencial}                           \n";
        $sCamposIssBase.= "     and (q02_dtbaix is null or q02_dtbaix >= (q23_anousu||'-'||q23_mesusu||'-01')::date) \n";
        $sCamposIssBase.= " ) as inscricoes,                                                                         \n";
        $sCamposIssBase.= " (select coalesce(count( distinct q02_inscr ), 0)                                         \n";
        $sCamposIssBase.= "    from issarqsimplesregissbase                                                          \n";
        $sCamposIssBase.= "    inner join issarqsimplesreg on issarqsimplesregissbase.q134_issarqsimplesreg = issarqsimplesreg.q23_sequencial \n";
        $sCamposIssBase.= "    inner join issbase on q134_inscr = issbase.q02_inscr                                  \n";
        $sCamposIssBase.= "  where q02_numcgm = cgm.z01_numcgm                                                       \n";
        $sCamposIssBase.= "    and q134_issarqsimplesreg = {$oRegSimples->q23_sequencial}                            \n";
        $sCamposIssBase.= "    and (q02_dtbaix is null or q02_dtbaix >= (q23_anousu||'-'||q23_mesusu||'-01')::date)  \n";
        $sCamposIssBase.= "  ) as quantidade_inscricao                                                               \n";
        $sWhereIssBase  = "      z01_numcgm = {$oRegSimples->cgm_valido}                                             \n";

        $sSqlIssBase    = "select z01_numcgm,
                                  z01_cgccpf,
                                  case when quantidade_vinculo   = 0 then 1                  else quantidade_vinculo   end as quantidade_vinculo,
                                  case when quantidade_vinculo   = 0 then q02_inscr::varchar else inscricoes           end as inscricoes,
                                  case when quantidade_inscricao = 0 then 0                  else quantidade_inscricao end as quantidade_inscricao
                             from (";
        $sSqlIssBase   .= $oDaoIssBase->sql_query(null, $sCamposIssBase, null, $sWhereIssBase);
        $sSqlIssBase   .= ") as x";

        $rsBase         = $oDaoIssBase->sql_record($sSqlIssBase);
        $oIssBase = null;
        if ($oDaoIssBase->numrows > 0) {
          $oIssBase       = db_utils::fieldsMemory($rsBase, 0);
        }

        if (empty($oIssBase->quantidade_inscricao)) {

          $oErros            = new stdClass();
          $oErros->sTipo     = "ERRO";
          $oErros->iRegistro = $oRegSimples->q23_sequencial;
          $oErros->sCnpj     = $oRegSimples->q23_cnpj;
          $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}: Sem Inscrição Ativa.");
          $oErros->sErro     = "semInscricaoAtiva";
          $aErros[$oRegSimples->q23_sequencial] = $oErros;

        } else if ( $oIssBase->quantidade_inscricao > 1 ) {

          $oErros            = new stdClass();
          $oErros->sTipo     = "ERRO";
          $oErros->iRegistro = $oRegSimples->q23_sequencial;
          $oErros->sCnpj     = $oRegSimples->q23_cnpj;
          $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM -{$oRegSimples->cgm_valido}: Cadastro com Mais de um alvará ({$oIssBase->inscricoes}) ou sem vínculo.");
          $oErros->sErro     = "variasinscricoes";
          $aErros[$oRegSimples->q23_sequencial] = $oErros;

        } else if ($oIssBase->quantidade_inscricao == 1 ) {

          $oDaoIssCalc = new cl_isscalc();
          $oDaoIssvar  = new cl_issvar();

          $sWhereIssCalc  = "     q01_anousu = $oRegSimples->q23_anousu ";
          $sWhereIssCalc .= " and q01_inscr  in ($oIssBase->inscricoes) ";
          $sWhereIssCalc .= " and q01_cadcal = 3 ";

          //testando se existe um calculo para o ano da competencia do registro.
          $sSqlIssCalc = $oDaoIssCalc->sql_query(null, null, null, null, null, "*", null, $sWhereIssCalc);
          $rsIssCalc   = db_query($sSqlIssCalc);

          if ( pg_num_rows($rsIssCalc) == 0 ) {

            $oErros            = new stdClass();
            $oErros->iRegistro = $oRegSimples->q23_sequencial;
            $oErros->sTipo     = "AVISO";
            $oErros->sCnpj     = $oRegSimples->q23_cnpj;
            $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}, Alvará - {$oIssBase->inscricoes}: ");
            $oErros->sDetalhe .= urlencode(" será lançado ISS Complementar para  {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}");
            $oErros->sErro     = "avisoComplementar";
            $aErros[$oRegSimples->q23_sequencial] = $oErros;
          }

          //testando se existe issvar para o mes/competencia
          $sSqlIssvar = $oDaoIssvar->sql_issvar_isscalc_inscr_comp($oIssBase->inscricoes,
                                                                   $oRegSimples->q23_anousu,
                                                                   $oRegSimples->q23_mesusu);

          $rsVar = db_query($sSqlIssvar);

          if (pg_num_rows($rsVar) < 1) {

            $oErros            = new stdClass();
            $oErros->iRegistro = $oRegSimples->q23_sequencial;
            $oErros->sTipo     = "ERRO";
            $oErros->sCnpj     = $oRegSimples->q23_cnpj;
            $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}, Alvará - {$oIssBase->inscricoes}: ");
            $oErros->sDetalhe .= urlencode("Sem lançamento ISS variavel para {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}");
            $oErros->sErro     = "semInscricaoAtiva";
            $aErros[$oRegSimples->q23_sequencial] = $oErros;

          } else {

            $oDaoArrecad      = new cl_arrecad();
            $oCalc            = db_utils::fieldsMemory( $rsIssCalc, 0 );
            $oIssVar          = db_utils::fieldsMemory( $rsVar    , 0 );
            $q05_codigo       = $oIssVar->q05_codigo;
            $sCamposArrecad   = "k00_numpre, k00_numpar, k00_hist, k00_dtoper";
            $sWhereArrecad    = "       k00_numpre = ".$oIssVar->q05_numpre;
            $sWhereArrecad   .= "   and k00_numpar = ".$oIssVar->q05_numpar;

            $sSqlArrecad      = $oDaoArrecad->sql_query_file(null, $sCamposArrecad, null, $sWhereArrecad);
            $rsArrecad        = db_query($sSqlArrecad);

            if ( !$rsArrecad ) {
              throw new Exception ("Erro ao buscar dados do Arrecad:".pg_last_error());
            }

            if (pg_num_rows($rsArrecad) != 1) {

              $oErros            = new stdClass();
              $oErros->sTipo     = "AVISO";
              $oErros->iRegistro = $oRegSimples->q23_sequencial;
              $oErros->sCnpj     = $oRegSimples->q23_cnpj;
              $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}, Alvará - {$oIssBase->inscricoes}: ");
              $oErros->sDetalhe .= urlencode(" será lançado ISS Complementar para  {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}");
              $oErros->sErro     = "avisoComplementar";
              $aErros[$oRegSimples->q23_sequencial] = $oErros;
            }
          }

          /* Verifica se o contribuinte tem caracteristica de emissor de NFS-e */
          $oDaoIssbasecaracteristica = new cl_issbasecaracteristica();
          $sSqlVerificaValidadeCaracteristica = $oDaoIssbasecaracteristica->sql_query_valida_caracteristica($oIssBase->inscricoes, 10);
          $rsIssbasecaracteristica = db_query($sSqlVerificaValidadeCaracteristica);

          if(!$rsIssbasecaracteristica){
            throw new Exception ("Erro ao buscar caracteristica de emissor de NFS-e do Alvará.");
          }

          /**
           * Possui caracteristica de emissor de NFS-e
           */
          if(pg_num_rows($rsIssbasecaracteristica) > 0) {

            $oDaoIsscadsimples           = new cl_isscadsimples();
            $sCompetencia                = "{$oRegSimples->q23_anousu}-{$oRegSimples->q23_mesusu}";
            $sSqlVerificaValidadeSimples = $oDaoIsscadsimples->sql_query_valida_competencia($oIssBase->inscricoes, $sCompetencia);
            $rsIsscadsimples             = db_query($sSqlVerificaValidadeSimples);


            if(!$rsIsscadsimples){
              throw new Exception ("Erro ao buscar dados do Simples.");
            }

            /**
             * Possui cadastro no simples
             */
            if(pg_num_rows($rsIsscadsimples) > 0){

              $aRegistrosNoSimples = db_utils::getCollectionByRecord($rsIsscadsimples);
              $lCompetenciaValida  = false;

              /**
               * Verificamos se está ativo no simples na competencia de pagamento
               */
              foreach ($aRegistrosNoSimples as $oRegistroNoSimples) {

                /**
                 * Cadastro do simples sem data de baixa
                 */
                if ($oRegistroNoSimples->validade == 't') {
                  $lCompetenciaValida = true;
                }
              }

              /**
               * Quando não encontrar nenhuma competencia valida para o pagamento
               */
              if (!$lCompetenciaValida) {

                $oErros            = new stdClass();
                $oErros->sTipo     = "ERRO";
                $oErros->iRegistro = $oRegSimples->q23_sequencial;
                $oErros->sCnpj     = $oRegSimples->q23_cnpj;
                $oErros->sDetalhe  = urlencode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}, Alvará - {$oIssBase->inscricoes}: ");
                $oErros->sDetalhe .= urlencode(" Alvará com data de baixa informada no cadastro do Simples para competência {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}.");
                $oErros->sErro     = "semInscricaoAtiva";
                $aErros[$oRegSimples->q23_sequencial] = $oErros;
              }

            }
          }
        }
      }
    }

    /**
     * Percorre  as inconsistencias encontradas e as salva no banco
     */
    $aErroRetorno = array();
    foreach ( $aErros as $oInconsistencia ) {

      $oDaoIssArqSimplesRegErro                 = new cl_issarqsimplesregerro();
      $oDaoIssArqSimplesRegErro->q49_sequencial = $oInconsistencia->iRegistro;
      $oDaoIssArqSimplesRegErro->q49_erro       = $oInconsistencia->sDetalhe;
      $oDaoIssArqSimplesRegErro->q49_tipo       = $oInconsistencia->sTipo == "AVISO" ? 2 : 1; //1 - Erro , 2 - Aviso
      $oDaoIssArqSimplesRegErro->incluir($oDaoIssArqSimplesRegErro->q49_sequencial);

      if ($oDaoIssArqSimplesRegErro->erro_status == "0"){
        throw new Exception ($oDaoIssArqSimplesRegErro->erro_msg."");
      }
      $aErroRetorno[] = $oInconsistencia;
    }

    return $aErroRetorno;
  }

  /**
   * funcao para retornar a data do vencimento a partir do parametro
   * @param date $q23_dtvenc
   * @return string <unknown, string>
   */
  function getDatavenc($iAnoUsu, $iMesUsu, $lManterCompetencia = false) {

    $dVencimento = null;
    $sSqlParametroVencimento  = "select distinct                                                                              \n";
    $sSqlParametroVencimento .= "       q82_venc                                                                              \n";
    $sSqlParametroVencimento .= "  from tipcalcexe                                                                            \n";
    $sSqlParametroVencimento .= "       inner join cadvencdesc on cadvencdesc.q92_codigo = tipcalcexe .q83_cadvencdescsimples \n";
    $sSqlParametroVencimento .= "       inner join cadvenc     on cadvenc    .q82_codigo = cadvencdesc.q92_codigo             \n";
    $sSqlParametroVencimento .= "       inner join tipcalc     on tipcalc    .q81_codigo = tipcalcexe .q83_tipcalc            \n";
    $sSqlParametroVencimento .= " where q81_cadcalc = 3                                                                       \n";
    $sSqlParametroVencimento .= "   and q83_cadvencdescsimples is not null                                                    \n";
    $sSqlParametroVencimento .= "   and q83_anousu = {$iAnoUsu}                                                               \n";
    $sSqlParametroVencimento .= "   and q82_parc   = {$iMesUsu} ;                                                             \n";

    $rsVencimentoParametro    =  db_query($sSqlParametroVencimento);

    if ($rsVencimentoParametro && pg_num_rows($rsVencimentoParametro) > 0) {

      $iVariacaoMes           = 1;

      if ($lManterCompetencia) {
        $iVariacaoMes         = 0;
      }
      $dVencimento            = db_utils::fieldsMemory($rsVencimentoParametro, 0)->q82_venc;
      $aVencimento            = explode("-", $dVencimento);
      $iDiaVencimento         = $aVencimento[2];
      $dNovoVencimento        = mktime(null, null, null, $iMesUsu + $iVariacaoMes,  $iDiaVencimento, $iAnoUsu);
      $dVencimento            = date("Y-m-d", $dNovoVencimento);
    }

    if ( $dVencimento == null ) {
      throw new Exception( "Sem Vencimento configurado para o exercicio de " . $iAnoUsu);
    }
    return $dVencimento;
  }

  function processaArquivo($iRegistro, $sArquivo, $iBanco, $iAgencia, $iConta) {

    $oDaoParISSQN     = db_utils::getDao('parissqn');
    $oParametrosISSQN = $oDaoParISSQN->getParametrosIssqn();

    if ( !db_utils::inTransaction()) {
      throw new Exception ("Sem Transação Ativa");
    }

    /**
     * Limpando Erros Existentes...
     */
    $oDaoIssArqSimplesRegErro  = db_utils::getDao('issarqsimplesregerro');
    $sWhereExclusaoErros       = "q49_sequencial in (select q23_sequencial                    ";
    $sWhereExclusaoErros      .= "                     from issarqsimplesreg                  ";
    $sWhereExclusaoErros      .= "                    where q23_issarqsimples = {$iRegistro}) ";
    $oDaoIssArqSimplesRegErro->excluir(null, $sWhereExclusaoErros);

    if ( $oDaoIssArqSimplesRegErro->erro_status == "0" ) {
      throw new Exception("Erro ao Deletar inconsistências. Processamento cancelado.");
    }

    $oDaoDisArq                = db_utils::getDao('disarq');
    $oDaoDisArq->id_usuario    = db_getsession("DB_id_usuario");
    $oDaoDisArq->k15_codbco    = $iBanco;
    $oDaoDisArq->k15_codage    = $iAgencia;
    $oDaoDisArq->arqret        = $sArquivo;
    $oDaoDisArq->textoret      = "Object oid";
    $oDaoDisArq->dtretorno     = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoDisArq->dtarquivo     = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoDisArq->k00_conta     = $iConta;
    $oDaoDisArq->autent        = "false";
    $oDaoDisArq->instit        = db_getsession('DB_instit');
    $oDaoDisArq->md5           = 'null';
    $oDaoDisArq->incluir(null);

    if ( $oDaoDisArq->erro_status == 0 ) {
      throw new Exception("Erro Disarq: " . $oDaoDisArq->erro_msg);
    }

    $oDaoIssArqSimplesDisArq                    = db_utils::getDao('issarqsimplesdisarq');
    $oDaoIssArqSimplesDisArq->q43_issarqsimples = $iRegistro;
    $oDaoIssArqSimplesDisArq->q43_disarq        = $oDaoDisArq->codret;
    $oDaoIssArqSimplesDisArq->incluir(null);

    if ($oDaoIssArqSimplesDisArq->erro_status == 0){
      throw new Exception("Erro IssArqSimplesDisArq: " . $oDaoIssArqSimplesDisArq->erro_msg);
    }

    /**
     * Colocaldo limit pois somente sera usado quando o regiustro for valido, ou seja quando houver apenas 1 cgm vinculado ao cnpj
     */
    $oDaoIssArqSimplesReg      = db_utils::getDao('issarqsimplesreg');
    $sCamposSqlRegistros       = "(select z01_numcgm                                                  ";
    $sCamposSqlRegistros      .= "   from cgm                                                         ";
    $sCamposSqlRegistros      .= "  where z01_cgccpf = issarqsimplesreg.q23_cnpj                      ";
    $sCamposSqlRegistros      .= "  limit 1)                                      as cgm_valido,      ";
    $sCamposSqlRegistros      .= "(select array_to_string(array_accum(distinct z01_numcgm), ', ')     ";
    $sCamposSqlRegistros      .= "   from cgm                                                         ";
    $sCamposSqlRegistros      .= "  where z01_cgccpf = issarqsimplesreg.q23_cnpj) as cgms_vinculados, ";
    $sCamposSqlRegistros      .= "*                                                                   ";
    $sSqlRegistros             = $oDaoIssArqSimplesReg->sql_query_registrosProcessamentoArquivo($sCamposSqlRegistros,"q23_issarqsimples=".$iRegistro);

    $rsRegistros               = $oDaoIssArqSimplesReg->sql_record($sSqlRegistros);
    $aRegistros                = db_utils::getCollectionByRecord($rsRegistros,false, false, true);

    foreach ($aRegistros as $iIndiceRegistros => $oRegSimples) {

      $dVlrReg     = ($oRegSimples->q23_vlrprinc + $oRegSimples->q23_vlrjur + $oRegSimples->q23_vlrmul);
      $iNumpre     = null;
      $iNumpar     = null;

      if ( $oRegSimples->quantidade_cgm == 0 ) {

        throw new Exception(urldecode("CNPJ - ".$oRegSimples->q23_cnpj." não existe no cadastro do CGM , Registro: {$oRegSimples->q23_sequencial}"));

      } elseif ( $oRegSimples->quantidade_cgm > 1 ) {

        throw new Exception( urldecode("CNPJ - $oRegSimples->q23_cnpj   cadastro nos cgms ($oRegSimples->cgms_vinculados , Registro: {$oRegSimples->q23_sequencial}"));
      }

      if ($oRegSimples->q23_acao == 1 && $oRegSimples->quantidade_cgm == 1) {

        $rsNumpre               = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
        $oNumpre                = db_utils::fieldsmemory($rsNumpre, 0);//quantidade_cgm
        $iNumpre                = $oNumpre->k03_numpre;
        $iNumpar                = $oRegSimples->q23_mesusu;

        /**
         * validamos o campo
         * q83_cadvencdescsimples
         * da tabela tipcalcexe
         */
        require_once(modification("classes/db_tipcalcexe_classe.php"));
        $oDaoTipCalcExe         = new cl_tipcalcexe;

        $oDaoIssVar             = db_utils::getDao("issvar");
        $oDaoIssVar->q05_numpre = $oNumpre->k03_numpre;
        $oDaoIssVar->q05_numpar = $iNumpar;
        $oDaoIssVar->q05_ano    = $oRegSimples->q23_anousu;
        $oDaoIssVar->q05_mes    = $oRegSimples->q23_mesusu;
        $oDaoIssVar->q05_histor = "Referente a Baixa Simples nacional competencia ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
        $oDaoIssVar->q05_aliq   = $oParametrosISSQN->q60_aliq;
        $oDaoIssVar->q05_valor  = "0";
        $oDaoIssVar->q05_bruto  = "0";
        $oDaoIssVar->q05_vlrinf = $dVlrReg;
        $oDaoIssVar->incluir(null);

        if ($oDaoIssVar->erro_status == "0"){
          throw new Exception("Iss Complementar".$oDaoIssVar->erro_msg);
        }

        $q05_codigo            = $oDaoIssVar->q05_codigo;
        $oDaoArrecad           = db_utils::getDao('arrecad');
        $oDaoArrecad->k00_numpre = $oNumpre->k03_numpre;
        $oDaoArrecad->k00_numpar = $iNumpar;
        $oDaoArrecad->k00_numcgm = $oRegSimples->cgm_valido;
        $oDaoArrecad->k00_dtoper = $oRegSimples->q23_dtvenc;
        $oDaoArrecad->k00_receit = $oParametrosISSQN->q60_receit;
        $oDaoArrecad->k00_hist   = $oParametrosISSQN->q60_histsemmov;
        $oDaoArrecad->k00_valor  = $dVlrReg;
        $oDaoArrecad->k00_dtvenc = $this->getDatavenc($oRegSimples->q23_anousu, $oRegSimples->q23_mesusu);//$oRegSimples->q23_dtvenc;
        $oDaoArrecad->k00_numtot = 1;
        $oDaoArrecad->k00_numdig = "0";
        $oDaoArrecad->k00_tipo   = $oParametrosISSQN->q60_tipo;
        $oDaoArrecad->k00_tipojm = "0";
        $oDaoArrecad->incluir(null);

        if ($oDaoArrecad->erro_status == 0){
          throw new Exception("ARRECAD :  ". $oDaoArrecad->erro_msg);
        }

          /**
           * verificamos o vinculo de inscrição para gerar arreinscr
           */
        $oDaoIssarqSimplesRegIssbase = db_utils::getDao("issarqsimplesregissbase");
        $sSqlIssarqSimplesRegIssbase = $oDaoIssarqSimplesRegIssbase->sql_query_file(null,
                                                                                    "q134_inscr",
                                                                                    null,
                                                                                    "q134_issarqsimplesreg = {$oRegSimples->q23_sequencial}" );
        $rsIncricaoVinculada         = db_query($sSqlIssarqSimplesRegIssbase);

        if (pg_num_rows($rsIncricaoVinculada) > 0 ) {

          $iInscricaoVinculada       = db_utils::fieldsMemory($rsIncricaoVinculada, 0)->q134_inscr;


          $oDaoArreinscr             = db_utils::getDao("arreinscr");
          $oDaoArreinscr->k00_inscr  = $iInscricaoVinculada;
          $oDaoArreinscr->k00_numpre = $iNumpre;
          $oDaoArreinscr->k00_perc   = 100;
          $oDaoArreinscr->incluir($iNumpre,$iInscricaoVinculada);

          if ($oDaoArreinscr->erro_status == 0){
            throw new Exception("Arreinscr : ". $oDaoArreinscr->erro_msg);
          }
        }
        /**
         * Aqui validar se vai ter incrição
         * se houver lançar arreinscr
         */
      } else if ($oRegSimples->q23_acao == 0 and $oRegSimples->quantidade_cgm == 1) {

        $oDaoIssBase    = db_utils::getDao("issbase");
        $sSqlIssBase    = $oDaoIssBase->sql_query_get_cgm_issarqsimplesreg($oRegSimples->cgm_valido, $oRegSimples->q23_sequencial);
        $rsBase         = $oDaoIssBase->sql_record($sSqlIssBase);
        $aIssBase       = db_utils::getCollectionByRecord($rsBase);

        if ($oDaoIssBase->numrows == 0) {

          throw new Exception(urldecode("CNPJ - {$oRegSimples->q23_cnpj}, CGM - {$oRegSimples->cgm_valido}: Sem Inscrição Ativa,  Registro: {$oRegSimples->q23_sequencial}"));

        } else if ($oDaoIssBase->numrows > 1) {

          $sVi      = '';
          $sInscr   = '';

          foreach ( $aIssBase as $oInscr ) {

            $sInscr .= $sVi.$oInscr->q02_inscr;
            $sVi     = ", ";
          }

          throw new Exception(urldecode("CNPJ - {$oRegSimples->q23_cnpj}, CGM -{$oRegSimples->cgm_valido}:  Cadastro com Mais de um alvará ({$sInscr}), Registro: {$oRegSimples->q23_sequencial} "));

        } else {

          $oInscr      = $aIssBase[0];
          $oDaoIssCalc = db_utils::getDao("isscalc");
          $oDaoIssvar  = db_utils::getDao("issvar");

          $rsIssCalc = db_query($oDaoIssCalc->sql_query($oRegSimples->q23_anousu, $oInscr->q02_inscr, 3));

          $rsVar = db_query($oDaoIssvar->sql_issvar_isscalc_inscr_comp($oInscr->q02_inscr,
                                                                       $oRegSimples->q23_anousu,
                                                                       $oRegSimples->q23_mesusu));

          if ( pg_num_rows($rsVar) < 1) {
            throw new Exception(urldecode("Sem lançamento ISS variavel para {$oRegSimples->q23_mesusu}/{$oRegSimples->q23_anousu}, Registro: {$oRegSimples->q23_sequencial} "));
          } else {

            $oDaoArrecad     = db_utils::getDao("arrecad");
            $oCalc           = db_utils::fieldsMemory($rsIssCalc, 0);
            $oIssVar         = db_utils::fieldsMemory($rsVar,     0);
            $q05_codigo      = $oIssVar->q05_codigo;
            $sCamposArrecad  = " k00_numpre, k00_numpar, k00_hist, k00_dtoper                     ";
            $sWhereArrecad   = "       k00_numpre = {$oIssVar->q05_numpre}                        ";
            $sWhereArrecad  .= "   and k00_numpar = {$oIssVar->q05_numpar}                        ";
            $sWhereArrecad  .= "   and exists (select 1                                           ";
            $sWhereArrecad  .= "                 from isscalc                                     ";
            $sWhereArrecad  .= "                where q01_numpre = {$oIssVar->q05_numpre}         ";
            $sWhereArrecad  .= "                  and q01_inscr  = {$oInscr->q02_inscr})          ";

            $sSqlArrecad = $oDaoArrecad->sql_query_file(null, $sCamposArrecad, null, $sWhereArrecad);
            $rsArrecad   = db_query($sSqlArrecad);

            if(!$rsArrecad){
              throw new Exception ("Erro ao buscar dados do Arrecad:".pg_last_error());
            }

            if(pg_num_rows($rsArrecad) != 1){

              $rsNumpre               = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
              $iNumpre                = db_utils::fieldsmemory($rsNumpre, 0)->k03_numpre;
              $iNumpar                = $oRegSimples->q23_mesusu;

              $oDaoIssVar             = db_utils::getDao("issvar");
              $oDaoIssVar->q05_numpre = $iNumpre;
              $oDaoIssVar->q05_numpar = $iNumpar;
              $oDaoIssVar->q05_ano    = $oRegSimples->q23_anousu;
              $oDaoIssVar->q05_mes    = $oRegSimples->q23_mesusu;
              $oDaoIssVar->q05_histor = "Referente a Baixa Simples nacional competencia ".$oRegSimples->q23_mesusu."/".$oRegSimples->q23_anousu;
              $oDaoIssVar->q05_aliq   = $oParametrosISSQN->q60_aliq;
              $oDaoIssVar->q05_valor  = "0";
              $oDaoIssVar->q05_bruto  = "0";
              $oDaoIssVar->q05_vlrinf = $dVlrReg;
              $oDaoIssVar->incluir(null);

              if ($oDaoIssVar->erro_status == 0) {
                throw new Exception("Iss Complementar : ".$oDaoIssVar->erro_msg);
              }

              $q05_codigo              = $oDaoIssVar->q05_codigo;
              $oDaoArrecad             = db_utils::getDao("arrecad");
              $oDaoArrecad->k00_numpre = $iNumpre;
              $oDaoArrecad->k00_numpar = $iNumpar;
              $oDaoArrecad->k00_numcgm = $oRegSimples->cgm_valido;
              $oDaoArrecad->k00_dtoper = $oRegSimples->q23_dtvenc;
              $oDaoArrecad->k00_receit = $oParametrosISSQN->q60_receit;
              $oDaoArrecad->k00_hist   = $oParametrosISSQN->q60_histsemmov;
              $oDaoArrecad->k00_valor  = $dVlrReg;
              $oDaoArrecad->k00_dtvenc = $this->getDatavenc($oRegSimples->q23_anousu, $oRegSimples->q23_mesusu );//$oRegSimples->q23_dtvenc;
              $oDaoArrecad->k00_numtot = 1;
              $oDaoArrecad->k00_numdig = "0";
              $oDaoArrecad->k00_tipo   = $oParametrosISSQN->q60_tipo;
              $oDaoArrecad->k00_tipojm = "0";
              $oDaoArrecad->incluir(null);

              if ($oDaoArrecad->erro_status == 0){
                throw new Exception("ARRECAD : ". $oDaoArrecad->erro_msg);
              }

              $oDaoArreinscr             = db_utils::getDao("arreinscr");
              $oDaoArreinscr->k00_inscr  = $oInscr->q02_inscr;
              $oDaoArreinscr->k00_numpre = $iNumpre;
              $oDaoArreinscr->k00_perc   = 100;
              $oDaoArreinscr->incluir($iNumpre,$oInscr->q02_inscr);

              if ($oDaoArreinscr->erro_status == 0){
                throw new Exception("Arreinscr : ". $oDaoArreinscr->erro_msg);
              }

            }

            if ($iNumpre == null){
              $iNumpre = $oIssVar->q05_numpre;
              $iNumpar = $oIssVar->q05_numpar;
            }
          }

        }
      }

      /**
       * Valida se existe inconsistencia
       * Trocar por count do array de inconsistencia
       */

      //Cadastramos na disbanco e issarqsimplesregdisbanco
      $oDaoDisbanco             = db_utils::getDao('disbanco');
      $oDaoDisbanco->codret     = $oDaoDisArq->codret;
      $oDaoDisbanco->k15_codbco = $iBanco;
      $oDaoDisbanco->k15_codage = $iAgencia;
      $oDaoDisbanco->dtarq      = $oRegSimples->q17_data;
      $oDaoDisbanco->dtpago     = $oRegSimples->q23_dtarrec;
      $oDaoDisbanco->vlrpago    = $dVlrReg;
      $oDaoDisbanco->vlrjuros   = "0";
      $oDaoDisbanco->vlrmulta   = "0";
      $oDaoDisbanco->vlrdesco   = "0";
      $oDaoDisbanco->cedente    = null;
      $oDaoDisbanco->vlrtot     = $dVlrReg;
      $oDaoDisbanco->vlrcalc    = "0";
      $oDaoDisbanco->classi     = 'false';
      $oDaoDisbanco->k00_numpre = $iNumpre;
      $oDaoDisbanco->k00_numpar = $iNumpar;
      $oDaoDisbanco->instit     = db_getsession('DB_instit');
      $oDaoDisbanco->convenio   = null;
      $oDaoDisbanco->dtcredito  = $oRegSimples->q23_dtarrec;
      $oDaoDisbanco->incluir(null);

      if ($oDaoDisbanco->erro_status == 0){

        $sErroMsg    = "erro disbanco ".$oDaoDisbanco->erro_msg;
        throw new Exception ($sErroMsg);
      }

      $oDaoIssArqSimplesRegDisbanco                       = db_utils::getDao("issarqsimplesregdisbanco");
      $oDaoIssArqSimplesRegDisbanco->q44_issarqsimplesreg = $oRegSimples->q23_sequencial;
      $oDaoIssArqSimplesRegDisbanco->q44_disbanco         = $oDaoDisbanco->idret;
      $oDaoIssArqSimplesRegDisbanco->incluir(null);
      if ($oDaoIssArqSimplesRegDisbanco->erro_status == 0){

        $sErroMsg    = " ".$oDaoIssArqSimplesRegDisbanco->erro_msg;
        throw new Exeption($sErroMsg);

      }

      $oDaoIssArqSimplesRegIssVar                       = db_utils::getDao("issarqsimplesregissvar");
      $oDaoIssArqSimplesRegIssVar->q68_issvar           = $q05_codigo;
      $oDaoIssArqSimplesRegIssVar->q68_issarqsimplesreg = $oRegSimples->q23_sequencial;
      $oDaoIssArqSimplesRegIssVar->incluir(null);
      if ($oDaoIssArqSimplesRegIssVar->erro_status == 0){

        $sErroMsg     = " Erro ao incluir issarqsimplesregissvar. Contate o suporte ";
        $sErroMsg    .= $oDaoIssArqSimplesRegIssVar->erro_msg;
        throw new Exeption($sErroMsg);

      }else{

        $oDaoIssvar = db_utils::getDao('issvar');
        $sSqlIssvar = $oDaoIssvar->sql_update_vlrinf_if_null($q05_codigo, $dVlrReg);
        $rsIssvar = db_query($sSqlIssvar);

        if(!$rsIssvar){
          throw new Exception ("Erro ao atualizar Issvar:".pg_last_error());
        }

        $oDaoArrecad             = db_utils::getDao('arrecad');
        $oDaoArrecad->k00_dtvenc = $this->getDatavenc($oRegSimples->q23_anousu, $oRegSimples->q23_mesusu);
        $oDaoArrecad->alterar(null, "k00_numpre = {$iNumpre} and k00_numpar = {$iNumpar}");

        if ($oDaoArrecad->erro_status == 0){
          throw new Exception( $oDaoArrecad->erro_msg );
        }

        $oDaoArrehist                 = db_utils::getDao("arrehist");
        $oDaoArrehist->k00_numpre     = $iNumpre;
        $oDaoArrehist->k00_numpar     = $iNumpar;
        $oDaoArrehist->k00_hist       = $oParametrosISSQN->q60_histsemmov;
        $oDaoArrehist->k00_dtoper     = $oRegSimples->q23_dtvenc;
        $oDaoArrehist->k00_hora       = db_hora();
        $oDaoArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
        $oDaoArrehist->k00_histtxt    = "BAIXA SIMPLES NACIONAL COMPETÊNCIA ".str_pad($oRegSimples->q23_mesusu,2,"0",STR_PAD_LEFT)."/".$oRegSimples->q23_anousu;
        $oDaoArrehist->k00_limithist  = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoArrehist->incluir(null);

        if ($oDaoArrehist->erro_status == 0){
          throw new Exeption("Arrehist:".$oDaoArrehist->erro_msg);
        }
      }
    }

  }

}