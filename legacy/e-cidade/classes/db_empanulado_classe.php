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
//CLASSE DA ENTIDADE empanulado
class cl_empanulado {
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
  var $e94_codanu = 0;
  var $e94_numemp = 0;
  var $e94_valor = 0;
  var $e94_saldoant = 0;
  var $e94_data_dia = null;
  var $e94_data_mes = null;
  var $e94_data_ano = null;
  var $e94_data = null;
  var $e94_motivo = null;
  var $e94_empanuladotipo = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 e94_codanu = int4 = Código anulação
                 e94_numemp = int4 = Número
                 e94_valor = float8 = Valor anulado
                 e94_saldoant = float8 = Saldo anterior
                 e94_data = date = Data anulação
                 e94_motivo = text = Motivo
                 e94_empanuladotipo = int4 = Tipo da Anulacao
                 ";
  //funcao construtor da classe
  function cl_empanulado() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empanulado");
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
      $this->e94_codanu = ($this->e94_codanu == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_codanu"]:$this->e94_codanu);
      $this->e94_numemp = ($this->e94_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_numemp"]:$this->e94_numemp);
      $this->e94_valor = ($this->e94_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_valor"]:$this->e94_valor);
      $this->e94_saldoant = ($this->e94_saldoant == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_saldoant"]:$this->e94_saldoant);
      if($this->e94_data == ""){
        $this->e94_data_dia = ($this->e94_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_data_dia"]:$this->e94_data_dia);
        $this->e94_data_mes = ($this->e94_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_data_mes"]:$this->e94_data_mes);
        $this->e94_data_ano = ($this->e94_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_data_ano"]:$this->e94_data_ano);
        if($this->e94_data_dia != ""){
          $this->e94_data = $this->e94_data_ano."-".$this->e94_data_mes."-".$this->e94_data_dia;
        }
      }
      $this->e94_motivo = ($this->e94_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_motivo"]:$this->e94_motivo);
      $this->e94_empanuladotipo = ($this->e94_empanuladotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_empanuladotipo"]:$this->e94_empanuladotipo);
    }else{
      $this->e94_codanu = ($this->e94_codanu == ""?@$GLOBALS["HTTP_POST_VARS"]["e94_codanu"]:$this->e94_codanu);
    }
  }
  // funcao para inclusao
  function incluir ($e94_codanu){
    $this->atualizacampos();
    if($this->e94_numemp == null ){
      $this->erro_sql = " Campo Número nao Informado.";
      $this->erro_campo = "e94_numemp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e94_valor == null ){
      $this->erro_sql = " Campo Valor anulado nao Informado.";
      $this->erro_campo = "e94_valor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e94_saldoant == null ){
      $this->erro_sql = " Campo Saldo anterior nao Informado.";
      $this->erro_campo = "e94_saldoant";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e94_data == null ){
      $this->erro_sql = " Campo Data anulação nao Informado.";
      $this->erro_campo = "e94_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e94_motivo == null ){
      $this->erro_sql = " Campo Motivo nao Informado.";
      $this->erro_campo = "e94_motivo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e94_empanuladotipo == null ){
      $this->e94_empanuladotipo = "0";
    }
    if($e94_codanu == "" || $e94_codanu == null ){
      $result = db_query("select nextval('empanulado_e94_codanu_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: empanulado_e94_codanu_seq do campo: e94_codanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->e94_codanu = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from empanulado_e94_codanu_seq");
      if(($result != false) && (pg_result($result,0,0) < $e94_codanu)){
        $this->erro_sql = " Campo e94_codanu maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->e94_codanu = $e94_codanu;
      }
    }
    if(($this->e94_codanu == null) || ($this->e94_codanu == "") ){
      $this->erro_sql = " Campo e94_codanu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empanulado(
                                       e94_codanu
                                      ,e94_numemp
                                      ,e94_valor
                                      ,e94_saldoant
                                      ,e94_data
                                      ,e94_motivo
                                      ,e94_empanuladotipo
                       )
                values (
                                $this->e94_codanu
                               ,$this->e94_numemp
                               ,$this->e94_valor
                               ,$this->e94_saldoant
                               ,".($this->e94_data == "null" || $this->e94_data == ""?"null":"'".$this->e94_data."'")."
                               ,'$this->e94_motivo'
                               ,$this->e94_empanuladotipo
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Empenhos anulados ($this->e94_codanu) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Empenhos anulados já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Empenhos anulados ($this->e94_codanu) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->e94_codanu;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->e94_codanu));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,6325,'$this->e94_codanu','I')");
      $resac = db_query("insert into db_acount values($acount,1030,6325,'','".AddSlashes(pg_result($resaco,0,'e94_codanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,6321,'','".AddSlashes(pg_result($resaco,0,'e94_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,6324,'','".AddSlashes(pg_result($resaco,0,'e94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,6323,'','".AddSlashes(pg_result($resaco,0,'e94_saldoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,6322,'','".AddSlashes(pg_result($resaco,0,'e94_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,6329,'','".AddSlashes(pg_result($resaco,0,'e94_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1030,11318,'','".AddSlashes(pg_result($resaco,0,'e94_empanuladotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($e94_codanu=null) {
    $this->atualizacampos();
    $sql = " update empanulado set ";
    $virgula = "";
    if(trim($this->e94_codanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_codanu"])){
      $sql  .= $virgula." e94_codanu = $this->e94_codanu ";
      $virgula = ",";
      if(trim($this->e94_codanu) == null ){
        $this->erro_sql = " Campo Código anulação nao Informado.";
        $this->erro_campo = "e94_codanu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e94_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_numemp"])){
      $sql  .= $virgula." e94_numemp = $this->e94_numemp ";
      $virgula = ",";
      if(trim($this->e94_numemp) == null ){
        $this->erro_sql = " Campo Número nao Informado.";
        $this->erro_campo = "e94_numemp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e94_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_valor"])){
      $sql  .= $virgula." e94_valor = $this->e94_valor ";
      $virgula = ",";
      if(trim($this->e94_valor) == null ){
        $this->erro_sql = " Campo Valor anulado nao Informado.";
        $this->erro_campo = "e94_valor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e94_saldoant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_saldoant"])){
      $sql  .= $virgula." e94_saldoant = $this->e94_saldoant ";
      $virgula = ",";
      if(trim($this->e94_saldoant) == null ){
        $this->erro_sql = " Campo Saldo anterior nao Informado.";
        $this->erro_campo = "e94_saldoant";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e94_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e94_data_dia"] !="") ){
      $sql  .= $virgula." e94_data = '$this->e94_data' ";
      $virgula = ",";
      if(trim($this->e94_data) == null ){
        $this->erro_sql = " Campo Data anulação nao Informado.";
        $this->erro_campo = "e94_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["e94_data_dia"])){
        $sql  .= $virgula." e94_data = null ";
        $virgula = ",";
        if(trim($this->e94_data) == null ){
          $this->erro_sql = " Campo Data anulação nao Informado.";
          $this->erro_campo = "e94_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->e94_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_motivo"])){
      $sql  .= $virgula." e94_motivo = '$this->e94_motivo' ";
      $virgula = ",";
      if(trim($this->e94_motivo) == null ){
        $this->erro_sql = " Campo Motivo nao Informado.";
        $this->erro_campo = "e94_motivo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e94_empanuladotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e94_empanuladotipo"])){
      if(trim($this->e94_empanuladotipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e94_empanuladotipo"])){
        $this->e94_empanuladotipo = "0" ;
      }
      $sql  .= $virgula." e94_empanuladotipo = $this->e94_empanuladotipo ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($e94_codanu!=null){
      $sql .= " e94_codanu = $this->e94_codanu";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->e94_codanu));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6325,'$this->e94_codanu','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_codanu"]))
          $resac = db_query("insert into db_acount values($acount,1030,6325,'".AddSlashes(pg_result($resaco,$conresaco,'e94_codanu'))."','$this->e94_codanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_numemp"]))
          $resac = db_query("insert into db_acount values($acount,1030,6321,'".AddSlashes(pg_result($resaco,$conresaco,'e94_numemp'))."','$this->e94_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_valor"]))
          $resac = db_query("insert into db_acount values($acount,1030,6324,'".AddSlashes(pg_result($resaco,$conresaco,'e94_valor'))."','$this->e94_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_saldoant"]))
          $resac = db_query("insert into db_acount values($acount,1030,6323,'".AddSlashes(pg_result($resaco,$conresaco,'e94_saldoant'))."','$this->e94_saldoant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_data"]))
          $resac = db_query("insert into db_acount values($acount,1030,6322,'".AddSlashes(pg_result($resaco,$conresaco,'e94_data'))."','$this->e94_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_motivo"]))
          $resac = db_query("insert into db_acount values($acount,1030,6329,'".AddSlashes(pg_result($resaco,$conresaco,'e94_motivo'))."','$this->e94_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e94_empanuladotipo"]))
          $resac = db_query("insert into db_acount values($acount,1030,11318,'".AddSlashes(pg_result($resaco,$conresaco,'e94_empanuladotipo'))."','$this->e94_empanuladotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Empenhos anulados nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->e94_codanu;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Empenhos anulados nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->e94_codanu;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->e94_codanu;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($e94_codanu=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($e94_codanu));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6325,'$e94_codanu','E')");
        $resac = db_query("insert into db_acount values($acount,1030,6325,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_codanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,6321,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,6324,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,6323,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_saldoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,6322,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,6329,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1030,11318,'','".AddSlashes(pg_result($resaco,$iresaco,'e94_empanuladotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from empanulado
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($e94_codanu != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e94_codanu = $e94_codanu ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Empenhos anulados nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$e94_codanu;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Empenhos anulados nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$e94_codanu;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$e94_codanu;
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
      $this->erro_sql   = "Record Vazio na Tabela:empanulado";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  function sql_query ( $e94_codanu=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empanulado ";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empanulado.e94_numemp";
    $sql .= "      inner join empanuladotipo  on  empanuladotipo.e38_sequencial = empanulado.e94_empanuladotipo";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
    $sql2 = "";
    if($dbwhere==""){
      if($e94_codanu!=null ){
        $sql2 .= " where empanulado.e94_codanu = $e94_codanu ";
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
  function sql_query_file ( $e94_codanu=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empanulado ";
    $sql2 = "";
    if($dbwhere==""){
      if($e94_codanu!=null ){
        $sql2 .= " where empanulado.e94_codanu = $e94_codanu ";
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

  public function sql_query_empenho ($campos = "*", $dbwhere = null){

    $sql  = " select {$campos} ";
    $sql .= "   from empanulado ";
    $sql .= "        inner join empempenho  on  empempenho.e60_numemp = empanulado.e94_numemp ";
    $sql .= "        inner join empanuladotipo  on  empanuladotipo.e38_sequencial = empanulado.e94_empanuladotipo ";
    $sql .= "        inner join db_config  on  db_config.codigo = empempenho.e60_instit ";
    $sql .= "        inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot ";
    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    }
    return $sql;
  }
}
?>