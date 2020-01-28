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
//CLASSE DA ENTIDADE empresto
class cl_empresto {
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
  var $e91_anousu = 0;
  var $e91_numemp = 0;
  var $e91_vlremp = 0;
  var $e91_vlranu = 0;
  var $e91_vlrliq = 0;
  var $e91_vlrpag = 0;
  var $e91_elemento = null;
  var $e91_recurso = 0;
  var $e91_codtipo = 0;
  var $e91_rpcorreto = 'f';
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 e91_anousu = int4 = anousu
                 e91_numemp = int4 = numero empenho
                 e91_vlremp = float8 = valor do empenho
                 e91_vlranu = float8 = valor anulado
                 e91_vlrliq = float8 = valor liquidado
                 e91_vlrpag = float8 = valor pago
                 e91_elemento = varchar(20) = elemento antigo
                 e91_recurso = int4 = Recurso
                 e91_codtipo = int4 = codigo do tipo
                 e91_rpcorreto = bool = RP Correto
                 ";
  //funcao construtor da classe
  function cl_empresto() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empresto");
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
      $this->e91_anousu = ($this->e91_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_anousu"]:$this->e91_anousu);
      $this->e91_numemp = ($this->e91_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_numemp"]:$this->e91_numemp);
      $this->e91_vlremp = ($this->e91_vlremp == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_vlremp"]:$this->e91_vlremp);
      $this->e91_vlranu = ($this->e91_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_vlranu"]:$this->e91_vlranu);
      $this->e91_vlrliq = ($this->e91_vlrliq == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_vlrliq"]:$this->e91_vlrliq);
      $this->e91_vlrpag = ($this->e91_vlrpag == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_vlrpag"]:$this->e91_vlrpag);
      $this->e91_elemento = ($this->e91_elemento == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_elemento"]:$this->e91_elemento);
      $this->e91_recurso = ($this->e91_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_recurso"]:$this->e91_recurso);
      $this->e91_codtipo = ($this->e91_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_codtipo"]:$this->e91_codtipo);
      $this->e91_rpcorreto = ($this->e91_rpcorreto == "f"?@$GLOBALS["HTTP_POST_VARS"]["e91_rpcorreto"]:$this->e91_rpcorreto);
    }else{
      $this->e91_anousu = ($this->e91_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_anousu"]:$this->e91_anousu);
      $this->e91_numemp = ($this->e91_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e91_numemp"]:$this->e91_numemp);
    }
  }
  // funcao para inclusao
  function incluir ($e91_anousu,$e91_numemp){
    $this->atualizacampos();
    if($this->e91_vlremp == null ){
      $this->erro_sql = " Campo valor do empenho nao Informado.";
      $this->erro_campo = "e91_vlremp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_vlranu == null ){
      $this->erro_sql = " Campo valor anulado nao Informado.";
      $this->erro_campo = "e91_vlranu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_vlrliq == null ){
      $this->erro_sql = " Campo valor liquidado nao Informado.";
      $this->erro_campo = "e91_vlrliq";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_vlrpag == null ){
      $this->erro_sql = " Campo valor pago nao Informado.";
      $this->erro_campo = "e91_vlrpag";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_recurso == null ){
      $this->erro_sql = " Campo Recurso nao Informado.";
      $this->erro_campo = "e91_recurso";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_codtipo == null ){
      $this->erro_sql = " Campo codigo do tipo nao Informado.";
      $this->erro_campo = "e91_codtipo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e91_rpcorreto == null ){
      $this->erro_sql = " Campo RP Correto nao Informado.";
      $this->erro_campo = "e91_rpcorreto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->e91_anousu = $e91_anousu;
    $this->e91_numemp = $e91_numemp;
    if(($this->e91_anousu == null) || ($this->e91_anousu == "") ){
      $this->erro_sql = " Campo e91_anousu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if(($this->e91_numemp == null) || ($this->e91_numemp == "") ){
      $this->erro_sql = " Campo e91_numemp nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empresto(
                                       e91_anousu
                                      ,e91_numemp
                                      ,e91_vlremp
                                      ,e91_vlranu
                                      ,e91_vlrliq
                                      ,e91_vlrpag
                                      ,e91_elemento
                                      ,e91_recurso
                                      ,e91_codtipo
                                      ,e91_rpcorreto
                       )
                values (
                                $this->e91_anousu
                               ,$this->e91_numemp
                               ,$this->e91_vlremp
                               ,$this->e91_vlranu
                               ,$this->e91_vlrliq
                               ,$this->e91_vlrpag
                               ,'$this->e91_elemento'
                               ,$this->e91_recurso
                               ,$this->e91_codtipo
                               ,'$this->e91_rpcorreto'
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = " ($this->e91_anousu."-".$this->e91_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = " já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = " ($this->e91_anousu."-".$this->e91_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->e91_anousu."-".$this->e91_numemp;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->e91_anousu,$this->e91_numemp));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,6232,'$this->e91_anousu','I')");
      $resac = db_query("insert into db_acountkey values($acount,6233,'$this->e91_numemp','I')");
      $resac = db_query("insert into db_acount values($acount,1011,6232,'','".AddSlashes(pg_result($resaco,0,'e91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6233,'','".AddSlashes(pg_result($resaco,0,'e91_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6234,'','".AddSlashes(pg_result($resaco,0,'e91_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6237,'','".AddSlashes(pg_result($resaco,0,'e91_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6235,'','".AddSlashes(pg_result($resaco,0,'e91_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6236,'','".AddSlashes(pg_result($resaco,0,'e91_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6238,'','".AddSlashes(pg_result($resaco,0,'e91_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6240,'','".AddSlashes(pg_result($resaco,0,'e91_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,6239,'','".AddSlashes(pg_result($resaco,0,'e91_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,1011,12561,'','".AddSlashes(pg_result($resaco,0,'e91_rpcorreto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($e91_anousu=null,$e91_numemp=null) {
    $this->atualizacampos();
    $sql = " update empresto set ";
    $virgula = "";
    if(trim($this->e91_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_anousu"])){
      $sql  .= $virgula." e91_anousu = $this->e91_anousu ";
      $virgula = ",";
      if(trim($this->e91_anousu) == null ){
        $this->erro_sql = " Campo anousu nao Informado.";
        $this->erro_campo = "e91_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_numemp"])){
      $sql  .= $virgula." e91_numemp = $this->e91_numemp ";
      $virgula = ",";
      if(trim($this->e91_numemp) == null ){
        $this->erro_sql = " Campo numero empenho nao Informado.";
        $this->erro_campo = "e91_numemp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_vlremp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_vlremp"])){
      $sql  .= $virgula." e91_vlremp = $this->e91_vlremp ";
      $virgula = ",";
      if(trim($this->e91_vlremp) == null ){
        $this->erro_sql = " Campo valor do empenho nao Informado.";
        $this->erro_campo = "e91_vlremp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_vlranu"])){
      $sql  .= $virgula." e91_vlranu = $this->e91_vlranu ";
      $virgula = ",";
      if(trim($this->e91_vlranu) == null ){
        $this->erro_sql = " Campo valor anulado nao Informado.";
        $this->erro_campo = "e91_vlranu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_vlrliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_vlrliq"])){
      $sql  .= $virgula." e91_vlrliq = $this->e91_vlrliq ";
      $virgula = ",";
      if(trim($this->e91_vlrliq) == null ){
        $this->erro_sql = " Campo valor liquidado nao Informado.";
        $this->erro_campo = "e91_vlrliq";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_vlrpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_vlrpag"])){
      $sql  .= $virgula." e91_vlrpag = $this->e91_vlrpag ";
      $virgula = ",";
      if(trim($this->e91_vlrpag) == null ){
        $this->erro_sql = " Campo valor pago nao Informado.";
        $this->erro_campo = "e91_vlrpag";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_elemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_elemento"])){
      $sql  .= $virgula." e91_elemento = '$this->e91_elemento' ";
      $virgula = ",";
    }
    if(trim($this->e91_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_recurso"])){
      $sql  .= $virgula." e91_recurso = $this->e91_recurso ";
      $virgula = ",";
      if(trim($this->e91_recurso) == null ){
        $this->erro_sql = " Campo Recurso nao Informado.";
        $this->erro_campo = "e91_recurso";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_codtipo"])){
      $sql  .= $virgula." e91_codtipo = $this->e91_codtipo ";
      $virgula = ",";
      if(trim($this->e91_codtipo) == null ){
        $this->erro_sql = " Campo codigo do tipo nao Informado.";
        $this->erro_campo = "e91_codtipo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e91_rpcorreto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e91_rpcorreto"])){
      $sql  .= $virgula." e91_rpcorreto = '$this->e91_rpcorreto' ";
      $virgula = ",";
      if(trim($this->e91_rpcorreto) == null ){
        $this->erro_sql = " Campo RP Correto nao Informado.";
        $this->erro_campo = "e91_rpcorreto";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($e91_anousu!=null){
      $sql .= " e91_anousu = $this->e91_anousu";
    }
    if($e91_numemp!=null){
      $sql .= " and  e91_numemp = $this->e91_numemp";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->e91_anousu,$this->e91_numemp));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6232,'$this->e91_anousu','A')");
        $resac = db_query("insert into db_acountkey values($acount,6233,'$this->e91_numemp','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_anousu"]))
          $resac = db_query("insert into db_acount values($acount,1011,6232,'".AddSlashes(pg_result($resaco,$conresaco,'e91_anousu'))."','$this->e91_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_numemp"]))
          $resac = db_query("insert into db_acount values($acount,1011,6233,'".AddSlashes(pg_result($resaco,$conresaco,'e91_numemp'))."','$this->e91_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_vlremp"]))
          $resac = db_query("insert into db_acount values($acount,1011,6234,'".AddSlashes(pg_result($resaco,$conresaco,'e91_vlremp'))."','$this->e91_vlremp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_vlranu"]))
          $resac = db_query("insert into db_acount values($acount,1011,6237,'".AddSlashes(pg_result($resaco,$conresaco,'e91_vlranu'))."','$this->e91_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_vlrliq"]))
          $resac = db_query("insert into db_acount values($acount,1011,6235,'".AddSlashes(pg_result($resaco,$conresaco,'e91_vlrliq'))."','$this->e91_vlrliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_vlrpag"]))
          $resac = db_query("insert into db_acount values($acount,1011,6236,'".AddSlashes(pg_result($resaco,$conresaco,'e91_vlrpag'))."','$this->e91_vlrpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_elemento"]))
          $resac = db_query("insert into db_acount values($acount,1011,6238,'".AddSlashes(pg_result($resaco,$conresaco,'e91_elemento'))."','$this->e91_elemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_recurso"]))
          $resac = db_query("insert into db_acount values($acount,1011,6240,'".AddSlashes(pg_result($resaco,$conresaco,'e91_recurso'))."','$this->e91_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_codtipo"]))
          $resac = db_query("insert into db_acount values($acount,1011,6239,'".AddSlashes(pg_result($resaco,$conresaco,'e91_codtipo'))."','$this->e91_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e91_rpcorreto"]))
          $resac = db_query("insert into db_acount values($acount,1011,12561,'".AddSlashes(pg_result($resaco,$conresaco,'e91_rpcorreto'))."','$this->e91_rpcorreto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->e91_anousu."-".$this->e91_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->e91_anousu."-".$this->e91_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->e91_anousu."-".$this->e91_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($e91_anousu=null,$e91_numemp=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($e91_anousu,$e91_numemp));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,6232,'$e91_anousu','E')");
        $resac = db_query("insert into db_acountkey values($acount,6233,'$e91_numemp','E')");
        $resac = db_query("insert into db_acount values($acount,1011,6232,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6233,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6234,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6237,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6235,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6236,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6238,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6240,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,6239,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1011,12561,'','".AddSlashes(pg_result($resaco,$iresaco,'e91_rpcorreto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from empresto
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($e91_anousu != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e91_anousu = $e91_anousu ";
      }
      if($e91_numemp != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e91_numemp = $e91_numemp ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$e91_anousu."-".$e91_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$e91_anousu."-".$e91_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$e91_anousu."-".$e91_numemp;
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
      $this->erro_sql   = "Record Vazio na Tabela:empresto";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $e91_anousu=null,$e91_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empresto ";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = empresto.e91_recurso";
    $sql .= "      inner join emprestotipo  on  emprestotipo.e90_codigo = empresto.e91_codtipo";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_anousu!=null ){
        $sql2 .= " where empresto.e91_anousu = $e91_anousu ";
      }
      if($e91_numemp!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " empresto.e91_numemp = $e91_numemp ";
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
  // funcao do sql
  function sql_query_file ( $e91_anousu=null,$e91_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empresto ";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_anousu!=null ){
        $sql2 .= " where empresto.e91_anousu = $e91_anousu ";
      }
      if($e91_numemp!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " empresto.e91_numemp = $e91_numemp ";
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
  function sql_query_empenho ( $e91_anousu=null,$e91_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empresto ";
    $sql .= "      inner join orctiporec    on  orctiporec.o15_codigo = empresto.e91_recurso";
    $sql .= "      inner join emprestotipo  on  emprestotipo.e90_codigo = empresto.e91_codtipo";
    $sql .= "      inner join empempenho    on  empempenho.e60_numemp = empresto.e91_numemp";
    $sql2 = "";
    if($dbwhere==""){
      if($e91_anousu!=null ){
        $sql2 .= " where empresto.e91_anousu = $e91_anousu ";
      }
      if($e91_numemp!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " empresto.e91_numemp = $e91_numemp ";
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
  function sql_rp($anousu="",$instit="1",$dtini="",$dtfim="",$sql_where="",$sql_where_externo="",$order="") {
    //##// função usada no relatorio de rp, não esta cadastrada como classe externa
    //##// $sql_where : caso queira filtrar por recurso, por exemplo
    //##//
    //##//
    $where_datas = "";
    if ($dtini ==""){
      $where_datas = "  <  '$dtfim'  ";
    }	else {
      $where_datas = " between '$dtini' and '$dtfim' ";
    }
    $sqlperiodo = "
	select
	    e91_numemp,
	    e91_vlremp,
	    e91_vlranu,
	    e91_vlrliq,
	    e91_vlrpag,
	    e91_recurso,
	    o15_descr,
	    vlranu,
	    vlrliq,
	    vlrpag,
	    e91_codtipo,
	    e90_descr,
	    z01_nome,
	    e60_numemp,
	    e60_instit,
	    e60_codemp,
	    e60_emiss,
	    e60_anousu,
        o58_orgao,
        o58_unidade,
	    o58_subfuncao,
        o56_elemento,
        o58_codigo,
	    o56_descr,
	    o40_descr,
        o41_descr,
	    o53_descr, /* descrição da subfunçao  */
	    vlranuliq,
	    vlranunliq,
	    vlrpagliq
	from (
	  select
	       e91_numemp,
	       e91_codtipo,
	       e90_descr,
	       o15_descr,
               coalesce(e91_vlremp,0) as e91_vlremp,
               coalesce(e91_vlranu,0) as e91_vlranu,
               coalesce(e91_vlrliq,0)  as e91_vlrliq,
               coalesce(e91_vlrpag,0) as e91_vlrpag,
               e91_recurso,
 	       coalesce(vlranu,0) as vlranu,
 	       coalesce(vlranuliq,0) as vlranuliq,
 	       coalesce(vlranunliq,0) as vlranunliq,
               coalesce(vlrliq,0) as  vlrliq,
               coalesce(vlrpag,0) as vlrpag,
               coalesce(vlrpagliq,0) as vlrpagliq
	  from empresto
	       inner join emprestotipo on e91_codtipo = e90_codigo
	       inner join orctiporec on e91_recurso = o15_codigo
	       left outer join (
		     select c75_numemp,
		         sum( case when c53_tipo = 11 then c70_valor else 0 end) as vlranu,
		         sum( case when c71_coddoc = 31 then c70_valor else 0 end) as vlranuliq,
		         sum( case when c53_coddoc = 32 then c70_valor else 0 end) as vlranunliq,
		         sum( case when c53_tipo = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end) as vlrliq,
		         sum( case when c53_tipo = 30 then c70_valor else ( case when c53_tipo = 31 then c70_valor*-1 else  0 end) end) as vlrpag,
		         sum( case when c71_coddoc = 37 then c70_valor else ( case when c71_coddoc = 38 then c70_valor*-1 else  0 end) end) as vlrpagliq
		         from conlancamemp
		         inner join conlancamdoc on c71_codlan = c75_codlan
		         inner join conhistdoc on c53_coddoc = c71_coddoc
		         inner join conlancam on c70_codlan = c75_codlan
		         inner join empempenho on e60_numemp = c75_numemp
		     where e60_anousu < ".$anousu." and c75_data $where_datas
		          and $instit
		     group by c75_numemp
	       ) as x on x.c75_numemp = e91_numemp
	       where e91_anousu = ".$anousu." $sql_where
	   ) as x
	      inner join empempenho on e60_numemp = e91_numemp
              inner join orcdotacao on o58_coddot = e60_coddot and o58_anousu=e60_anousu and o58_instit =e60_instit
              inner join orcelemento on o58_codele = o56_codele and o58_anousu=o56_anousu
	      	  inner join orcorgao on o40_orgao = o58_orgao  and o40_anousu = o58_anousu
	                     /*
			      a instituição, em sapiranga não pode estar ligada ao orção

			      and o40_instit=o58_instit
			      */

              inner join orcunidade on o41_anousu=o58_anousu and o41_orgao=o58_orgao and o41_unidade=o58_unidade
	      inner join orcsubfuncao on o53_subfuncao = orcdotacao.o58_subfuncao
	      inner join cgm on z01_numcgm = e60_numcgm
       $sql_where_externo
       $order
	";
    return $sqlperiodo;
  }
  function sql_rp2($anousu="",$instit="1",$dtini="",$dtfim="",$sql_where="",$order="") {
    //##// função usada no relatorio de rp, não esta cadastrada como classe externa
    //##// $sql_where : caso queira filtrar por recurso, por exemplo
    //##//
    //##//
    $where_datas = "";
    if ($dtini ==""){
      $where_datas = "  <  '$dtfim'  ";
    }	else {
      $where_datas = " between '$dtini' and '$dtfim' ";
    }
    $sqlperiodo = "
	  select *
	  from empresto
				 inner join empempenho on e60_numemp = e91_numemp
				 inner join db_config on codigo = e60_instit
				 inner join orcdotacao on o58_coddot = e60_coddot and o58_anousu=e60_anousu
				 inner join orcelemento on o58_codele = o56_codele and o58_anousu=o56_anousu
				 inner join orcorgao on o40_orgao = o58_orgao and o40_anousu=e91_anousu /* and o40_instit=o58_instit */
				 inner join orcunidade on o41_anousu=o58_anousu and o41_orgao=o58_orgao and o41_unidade=o58_unidade
         inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao
         inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
				 inner join cgm on z01_numcgm = e60_numcgm
	       inner join emprestotipo on e91_codtipo = e90_codigo
	       inner join orctiporec on e91_recurso = o15_codigo
	       left outer join (
		     select c75_numemp,
		         sum( case when c53_tipo = 11 then c70_valor else 0 end) as vlranu,
		         sum( case when c53_coddoc = 31 then c70_valor else 0 end) as canc_proc,
		         sum( case when c53_coddoc = 32 then c70_valor else 0 end) as canc_nproc,
		         sum( case when c53_tipo = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end) as vlrliq,
		         sum( case when c71_coddoc = 35 then c70_valor else ( case when c71_coddoc = 36 then c70_valor*-1 else  0 end) end) as vlrpag,
		         sum( case when c71_coddoc = 37 then c70_valor else ( case when c71_coddoc = 38 then c70_valor*-1 else  0 end) end) as vlrpagnproc
		     from conlancamemp
		         inner join conlancamdoc on c71_codlan = c75_codlan
		         inner join conhistdoc on c53_coddoc = c71_coddoc
		         inner join conlancam on c70_codlan = c75_codlan
		         inner join empempenho on e60_numemp = c75_numemp
		     where e60_anousu < ".$anousu." and c75_data $where_datas
		          and $instit
		     group by c75_numemp
	       ) as x on x.c75_numemp = e91_numemp
	  where e91_anousu = ".$anousu."   and $instit

	  $sql_where

          $order
	";

    return $sqlperiodo;
  }
  function sql_rp_novo($anousu="",$instit="1",$dtini="",$dtfim="",$sql_where="",$sql_where_externo="",$order="") {
    //##// função usada no relatorio de rp, não esta cadastrada como classe externa
    //##// $sql_where : caso queira filtrar por recurso, por exemplo
    //##//
    //##//
    $where_datas = "";
    if ($dtini ==""){
      $where_datas = "  <  '$dtfim'  ";
    }	else {
      $where_datas = " between '$dtini' and '$dtfim' ";
    }
    $sqlperiodo = "
	select
	    e91_numemp,
	    e91_vlremp,
	    e91_vlranu,
	    e91_vlrliq,
	    e91_vlrpag,
	    e91_recurso,
	    e91_anousu,
	    o15_descr,
	    vlranu,
	    vlrliq,
	    vlrpag,
	    vlrpagnproc,
	    e91_codtipo,
	    e90_descr,
	    z01_numcgm,
      z01_nome,
      z01_cgccpf,
	    e60_numemp,
	    e60_codemp,
	    e60_emiss,
	    e60_anousu,
      e64_codele,
      o58_orgao,
      o58_unidade,
      o58_codigo,
      o58_funcao,
	    o58_subfuncao,
      o56_elemento,
      o58_programa,
      o58_projativ,
      o52_descr,
      o54_descr,
      o55_descr,
	    o56_descr,
	    o40_descr,
      o41_descr,
	    o53_descr, /* descrição da subfunçao  */
	    vlranuliq,
      vlranuliqnaoproc,
      c70_anousu,
      e64_codele,
      db21_tipoinstit,
      e60_instit,
      nomeinst
	from (
	  select
	       e91_numemp,
	       e91_anousu,
	       e91_codtipo,
	       e90_descr,
	       o15_descr,
         c70_anousu,

         coalesce(e91_vlremp,0) as e91_vlremp,
         coalesce(e91_vlranu,0) as e91_vlranu,
         coalesce(e91_vlrliq,0)  as e91_vlrliq,
         coalesce(e91_vlrpag,0) as e91_vlrpag,
         e91_recurso,
 	       coalesce(vlranu,0) as vlranu,
 	       coalesce(vlranuliq,0) as vlranuliq,
         coalesce(vlranuliqnaoproc,0) as vlranuliqnaoproc,
         coalesce(vlrliq,0) as  vlrliq,
         coalesce(vlrpag,0) as vlrpag,
         coalesce(vlrpagnproc,0) as vlrpagnproc
	  from empresto
	       inner join emprestotipo on e91_codtipo = e90_codigo
	       inner join orctiporec on e91_recurso = o15_codigo
	       left outer join (
		     select c75_numemp,c70_anousu,
		         sum( round( case when c53_tipo   = 11 then c70_valor else 0 end,2) ) as vlranu,
		         sum( round(case when c71_coddoc = 31 then c70_valor else 0 end,2) ) as vlranuliq,
             sum( round(case when c71_coddoc = 32 then c70_valor else 0 end,2) ) as vlranuliqnaoproc,
		         sum( round(case when c53_tipo   = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end,2) ) as vlrliq,
		         sum( round(case when c71_coddoc = 35 then c70_valor else ( case when c71_coddoc = 36 then c70_valor*-1 else  0 end) end,2) ) as vlrpag,
		         sum( round( case when c71_coddoc = 37 then c70_valor else ( case when c71_coddoc = 38 then c70_valor*-1 else  0 end) end ,2) ) as vlrpagnproc
		     from conlancamemp
		         inner join conlancamdoc on c71_codlan = c75_codlan
		         inner join conhistdoc   on c53_coddoc = c71_coddoc
		         inner join conlancam    on c70_codlan = c75_codlan
		         inner join empempenho   on e60_numemp = c75_numemp
		     where e60_anousu < ".$anousu." and c75_data $where_datas
		          and $instit
		     group by c75_numemp,c70_anousu
	       ) as x on x.c75_numemp = e91_numemp
	     where e91_anousu = ".$anousu." $sql_where
	) as x
inner join empempenho    on e60_numemp    = e91_numemp and $instit
inner join db_config     on db_config.codigo = empempenho.e60_instit
inner join db_tipoinstit on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit
inner join empelemento   on e64_numemp    = e60_numemp
inner join cgm           on z01_numcgm    = e60_numcgm
inner join orcdotacao    on o58_coddot    = e60_coddot and o58_anousu  = e60_anousu and o58_instit = e60_instit
inner join orcorgao      on o40_orgao     = o58_orgao  and o40_anousu  = o58_anousu
inner join orcunidade    on o41_anousu    = o58_anousu and o41_orgao   = o58_orgao and o41_unidade = o58_unidade
inner join orcfuncao     on o52_funcao    = orcdotacao.o58_funcao
inner join orcsubfuncao  on o53_subfuncao = orcdotacao.o58_subfuncao
inner join orcprograma   on o54_programa  = o58_programa and o54_anousu = orcdotacao.o58_anousu
inner join orcprojativ   on o55_projativ  = o58_projativ and o55_anousu = orcdotacao.o58_anousu
inner join orcelemento   on o58_codele    = o56_codele and o58_anousu   = o56_anousu
$sql_where_externo
$order
	";
    return $sqlperiodo;
  }

  /**
   *
   * Calcula os Restos a pagar para o arquivo restosap.txt Sigfis (TCE/RJ)
   * @return string
   */
  function sql_query_restosPag ( $e91_anousu=null,$e91_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from empresto 		                                                                   ";
    $sql .= "      inner join empempenho   on empempenho.e60_numemp   = empresto.e91_numemp       ";
    $sql .= "      inner join orcdotacao   on orcdotacao.o58_coddot   = empempenho.e60_coddot     ";
    $sql .= "                             and orcdotacao.o58_anousu   = empempenho.e60_anousu     ";
    $sql .= "      inner join orctiporec   on orctiporec.o15_codigo   = orcdotacao.o58_codigo     ";
    $sql .= "      inner join emprestotipo on emprestotipo.e90_codigo = empresto.e91_codtipo 		 ";
    $sql .= "      inner join conlancamemp on conlancamemp.c75_numemp = empempenho.e60_numemp     ";
    $sql .= "      inner join conlancam    on conlancam.c70_codlan    = conlancamemp.c75_codlan   ";
    $sql .= "      inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan      ";
    $sql2 = "";
    if ($dbwhere=="") {

      if($e91_anousu!=null ) {
        $sql2 .= " where empresto.e91_anousu = $e91_anousu ";
      }
      if ($e91_numemp!=null ) {
        if($sql2!=""){
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " empresto.e91_numemp = $e91_numemp ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {

      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  /**
   * @param  string $sCampos
   * @param  string $sOrder
   * @param  string $sWhere
   * @return string
   */
  function sql_query_restos( $sCampos = "*", $sOrder = null, $sWhere = null) {

    $sSql  = "select {$sCampos}                                                                 \n";
    $sSql .= "  from empresto                                                                   \n";
    $sSql .= "       inner join empempenho   on empempenho.e60_numemp   = empresto.e91_numemp   \n";
    $sSql .= "       inner join cgm          on z01_numcgm = e60_numcgm                         \n";
    $sSql .= "       inner join orcdotacao   on orcdotacao.o58_coddot   = empempenho.e60_coddot \n";
    $sSql .= "                              and orcdotacao.o58_anousu   = empempenho.e60_anousu \n";
    $sSql .= "       inner join orcelemento  on o58_codele = o56_codele                         \n";
    $sSql .= "                              and o58_anousu = o56_anousu                         \n";
    $sSql .= "       inner join orctiporec   on orctiporec.o15_codigo   = orcdotacao.o58_codigo \n";
    $sSql .= "       inner join emprestotipo on emprestotipo.e90_codigo = empresto.e91_codtipo  \n";
    $sSql .= "       inner join empempaut    on e61_numemp = e60_numemp                         \n";
    $sSql .= "       left  join empautorizaprocesso on e150_empautoriza = e61_autori            \n";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }

  /**
   * Busca restos a pagar por periodo
   *
   * @param integer $iAnoUsu
   * @param string $sDataInicial
   * @param string $sDataFinal
   * @param string $sInstituicoes
   * @param string $sCampos
   * @param string $sWhere
   * @param string $sOrder
   * @return string
   */
  public function sql_query_restosPagarPorPeriodo($iAnoUsu, $sDataInicial, $sDataFinal, $sInstituicoes, $sCampos = '*', $sWhere = '', $sOrder = '') {

    $sSql  = "select $sCampos                                                                                                                                           ";
    $sSql .= " from (                                                                                                                                                   ";
    $sSql .= "   select                                                                                                                                                 ";
    $sSql .= "        e91_numemp,                                                                                                                                       ";
    $sSql .= "        e91_anousu,                                                                                                                                       ";
    $sSql .= "        e91_codtipo,                                                                                                                                      ";
    $sSql .= "        e90_descr,                                                                                                                                        ";
    $sSql .= "        o15_descr,                                                                                                                                        ";
    $sSql .= "        c70_anousu,                                                                                                                                       ";
    $sSql .= "        coalesce(e91_vlremp,0) as e91_vlremp,                                                                                                             ";
    $sSql .= "        coalesce(e91_vlranu,0) as e91_vlranu,                                                                                                             ";
    $sSql .= "        coalesce(e91_vlrliq,0) as e91_vlrliq,                                                                                                             ";
    $sSql .= "        coalesce(e91_vlrpag,0) as e91_vlrpag,                                                                                                             ";
    $sSql .= "        e91_recurso,                                                                                                                                      ";
    $sSql .= "        coalesce(vlranu,0) as vlranu,                                                                                                                     ";
    $sSql .= "        coalesce(vlranuliq,0) as vlranuliq,                                                                                                               ";
    $sSql .= "        coalesce(vlranuliqnaoproc,0) as vlranuliqnaoproc,                                                                                                 ";
    $sSql .= "        coalesce(vlrliq,0) as  vlrliq,                                                                                                                    ";
    $sSql .= "        coalesce(vlrpag,0) as vlrpag,                                                                                                                     ";
    $sSql .= "        coalesce(vlrpagnproc,0) as vlrpagnproc                                                                                                            ";
    $sSql .= "   from empresto                                                                                                                                          ";
    $sSql .= "        inner join emprestotipo on e91_codtipo = e90_codigo                                                                                               ";
    $sSql .= "        inner join orctiporec on e91_recurso = o15_codigo                                                                                                 ";
    $sSql .= "        left outer join (                                                                                                                                 ";
    $sSql .= "        select c75_numemp,c70_anousu,                                                                                                                     ";
    $sSql .= "            sum( round( case when c53_tipo   = 11 then c70_valor else 0 end,2) ) as vlranu,                                                               ";
    $sSql .= "            sum( round(case when c71_coddoc = 31 then c70_valor else 0 end,2) ) as vlranuliq,                                                             ";
    $sSql .= "            sum( round(case when c71_coddoc = 32 then c70_valor else 0 end,2) ) as vlranuliqnaoproc,                                                      ";
    $sSql .= "            sum( round(case when c53_tipo   = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end,2) ) as vlrliq,         ";
    $sSql .= "            sum( round(case when c71_coddoc = 35 then c70_valor else ( case when c71_coddoc = 36 then c70_valor*-1 else  0 end) end,2) ) as vlrpag,       ";
    $sSql .= "            sum( round( case when c71_coddoc = 37 then c70_valor else ( case when c71_coddoc = 38 then c70_valor*-1 else  0 end) end ,2) ) as vlrpagnproc ";
    $sSql .= "        from conlancamemp                                                                                                                                 ";
    $sSql .= "            inner join conlancamdoc on c71_codlan = c75_codlan                                                                                            ";
    $sSql .= "            inner join conhistdoc   on c53_coddoc = c71_coddoc                                                                                            ";
    $sSql .= "            inner join conlancam    on c70_codlan = c75_codlan                                                                                            ";
    $sSql .= "            inner join empempenho   on e60_numemp = c75_numemp                                                                                            ";
    $sSql .= "        where e60_anousu < $iAnoUsu and c75_data between '$sDataInicial' and '$sDataFinal'                                                                ";
    $sSql .= "             and  e60_instit in ($sInstituicoes)                                                                                                          ";
    $sSql .= "        group by c75_numemp,c70_anousu                                                                                                                    ";
    $sSql .= "        ) as x on x.c75_numemp = e91_numemp                                                                                                               ";
    $sSql .= "      where e91_anousu = $iAnoUsu                                                                                                                         ";
    $sSql .= " ) as x                                                                                                                                                   ";
    $sSql .= "      inner join empempenho   on e60_numemp    = e91_numemp and  e60_instit in ($sInstituicoes)                                                           ";
    $sSql .= "      inner join empelemento  on e64_numemp    = e60_numemp                                                                                               ";
    $sSql .= "      inner join cgm          on z01_numcgm    = e60_numcgm                                                                                               ";
    $sSql .= "      inner join orcdotacao   on o58_coddot    = e60_coddot and o58_anousu  = e60_anousu and o58_instit = e60_instit                                      ";
    $sSql .= "      inner join orcorgao     on o40_orgao     = o58_orgao  and o40_anousu  = o58_anousu                                                                  ";
    $sSql .= "      inner join orcunidade   on o41_anousu    = o58_anousu and o41_orgao   = o58_orgao and o41_unidade = o58_unidade                                     ";
    $sSql .= "      inner join orcfuncao    on o52_funcao    = orcdotacao.o58_funcao                                                                                    ";
    $sSql .= "      inner join orcsubfuncao on o53_subfuncao = orcdotacao.o58_subfuncao                                                                                 ";
    $sSql .= "      inner join orcprograma  on o54_programa  = o58_programa and o54_anousu = orcdotacao.o58_anousu                                                      ";
    $sSql .= "      inner join orcprojativ  on o55_projativ  = o58_projativ and o55_anousu = orcdotacao.o58_anousu                                                      ";
    $sSql .= "      inner join orcelemento  on o58_codele    = o56_codele and o58_anousu   = o56_anousu                                                                 ";
    $sSql .= "      inner join empempaut    on e61_numemp    = e60_numemp                                                                                               ";
    $sSql .= "      left  join empautorizaprocesso on e150_empautoriza = e61_autori                                                                                     ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sSql .= " order by {$sOrder} ";
    }

    return $sSql;
  }
}
