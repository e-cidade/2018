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
//CLASSE DA ENTIDADE contacorrentesaldo
class cl_contacorrentesaldo {
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
  var $c29_sequencial = 0;
  var $c29_contacorrentedetalhe = 0;
  var $c29_anousu = 0;
  var $c29_mesusu = 0;
  var $c29_debito = 0;
  var $c29_credito = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c29_sequencial = int4 = Sequencial
                 c29_contacorrentedetalhe = int4 = Sequencial
                 c29_anousu = int4 = Ano
                 c29_mesusu = int4 = Mês
                 c29_debito = float4 = Débito
                 c29_credito = float4 = Crédito
                 ";
  //funcao construtor da classe
  function cl_contacorrentesaldo() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("contacorrentesaldo");
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
      $this->c29_sequencial = ($this->c29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_sequencial"]:$this->c29_sequencial);
      $this->c29_contacorrentedetalhe = ($this->c29_contacorrentedetalhe == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_contacorrentedetalhe"]:$this->c29_contacorrentedetalhe);
      $this->c29_anousu = ($this->c29_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_anousu"]:$this->c29_anousu);
      $this->c29_mesusu = ($this->c29_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_mesusu"]:$this->c29_mesusu);
      $this->c29_debito = ($this->c29_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_debito"]:$this->c29_debito);
      $this->c29_credito = ($this->c29_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_credito"]:$this->c29_credito);
    }else{
      $this->c29_sequencial = ($this->c29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c29_sequencial"]:$this->c29_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($c29_sequencial){
    $this->atualizacampos();
    if($this->c29_contacorrentedetalhe == null ){
      $this->erro_sql = " Campo Sequencial nao Informado.";
      $this->erro_campo = "c29_contacorrentedetalhe";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c29_anousu == null ){
      $this->erro_sql = " Campo Ano nao Informado.";
      $this->erro_campo = "c29_anousu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c29_mesusu == null ){
      $this->erro_sql = " Campo Mês nao Informado.";
      $this->erro_campo = "c29_mesusu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c29_debito == null ){
      $this->erro_sql = " Campo Débito nao Informado.";
      $this->erro_campo = "c29_debito";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->c29_credito == null ){
      $this->erro_sql = " Campo Crédito nao Informado.";
      $this->erro_campo = "c29_credito";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($c29_sequencial == "" || $c29_sequencial == null ){
      $result = db_query("select nextval('contacorrentesaldo_c29_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: contacorrentesaldo_c29_sequencial_seq do campo: c29_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c29_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from contacorrentesaldo_c29_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $c29_sequencial)){
        $this->erro_sql = " Campo c29_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->c29_sequencial = $c29_sequencial;
      }
    }
    if(($this->c29_sequencial == null) || ($this->c29_sequencial == "") ){
      $this->erro_sql = " Campo c29_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into contacorrentesaldo(
                                       c29_sequencial
                                      ,c29_contacorrentedetalhe
                                      ,c29_anousu
                                      ,c29_mesusu
                                      ,c29_debito
                                      ,c29_credito
                       )
                values (
                                $this->c29_sequencial
                               ,$this->c29_contacorrentedetalhe
                               ,$this->c29_anousu
                               ,$this->c29_mesusu
                               ,$this->c29_debito
                               ,$this->c29_credito
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Conta Corrente Saldo ($this->c29_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Conta Corrente Saldo já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Conta Corrente Saldo ($this->c29_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->c29_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      $resaco = $this->sql_record($this->sql_query_file($this->c29_sequencial));
      if (($resaco!=false)||($this->numrows!=0)) {

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19669,'$this->c29_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,3495,19669,'','".AddSlashes(pg_result($resaco,0,'c29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3495,19670,'','".AddSlashes(pg_result($resaco,0,'c29_contacorrentedetalhe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3495,19671,'','".AddSlashes(pg_result($resaco,0,'c29_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3495,19672,'','".AddSlashes(pg_result($resaco,0,'c29_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3495,19673,'','".AddSlashes(pg_result($resaco,0,'c29_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3495,19674,'','".AddSlashes(pg_result($resaco,0,'c29_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($c29_sequencial=null) {
    $this->atualizacampos();
    $sql = " update contacorrentesaldo set ";
    $virgula = "";
    if(trim($this->c29_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_sequencial"])){
      $sql  .= $virgula." c29_sequencial = $this->c29_sequencial ";
      $virgula = ",";
      if(trim($this->c29_sequencial) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "c29_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c29_contacorrentedetalhe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_contacorrentedetalhe"])){
      $sql  .= $virgula." c29_contacorrentedetalhe = $this->c29_contacorrentedetalhe ";
      $virgula = ",";
      if(trim($this->c29_contacorrentedetalhe) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "c29_contacorrentedetalhe";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c29_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_anousu"])){
      $sql  .= $virgula." c29_anousu = $this->c29_anousu ";
      $virgula = ",";
      if(trim($this->c29_anousu) == null ){
        $this->erro_sql = " Campo Ano nao Informado.";
        $this->erro_campo = "c29_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c29_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_mesusu"])){
      $sql  .= $virgula." c29_mesusu = $this->c29_mesusu ";
      $virgula = ",";
      if(trim($this->c29_mesusu) == null ){
        $this->erro_sql = " Campo Mês nao Informado.";
        $this->erro_campo = "c29_mesusu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c29_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_debito"])){
      $sql  .= $virgula." c29_debito = $this->c29_debito ";
      $virgula = ",";
      if(trim($this->c29_debito) == null ){
        $this->erro_sql = " Campo Débito nao Informado.";
        $this->erro_campo = "c29_debito";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c29_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c29_credito"])){
      $sql  .= $virgula." c29_credito = $this->c29_credito ";
      $virgula = ",";
      if(trim($this->c29_credito) == null ){
        $this->erro_sql = " Campo Crédito nao Informado.";
        $this->erro_campo = "c29_credito";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($c29_sequencial!=null){
      $sql .= " c29_sequencial = $this->c29_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      $resaco = $this->sql_record($this->sql_query_file($this->c29_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco=0;$conresaco<$this->numrows;$conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,19669,'$this->c29_sequencial','A')");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_sequencial"]) || $this->c29_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,3495,19669,'".AddSlashes(pg_result($resaco,$conresaco,'c29_sequencial'))."','$this->c29_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_contacorrentedetalhe"]) || $this->c29_contacorrentedetalhe != "")
            $resac = db_query("insert into db_acount values($acount,3495,19670,'".AddSlashes(pg_result($resaco,$conresaco,'c29_contacorrentedetalhe'))."','$this->c29_contacorrentedetalhe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_anousu"]) || $this->c29_anousu != "")
            $resac = db_query("insert into db_acount values($acount,3495,19671,'".AddSlashes(pg_result($resaco,$conresaco,'c29_anousu'))."','$this->c29_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_mesusu"]) || $this->c29_mesusu != "")
            $resac = db_query("insert into db_acount values($acount,3495,19672,'".AddSlashes(pg_result($resaco,$conresaco,'c29_mesusu'))."','$this->c29_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_debito"]) || $this->c29_debito != "")
            $resac = db_query("insert into db_acount values($acount,3495,19673,'".AddSlashes(pg_result($resaco,$conresaco,'c29_debito'))."','$this->c29_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["c29_credito"]) || $this->c29_credito != "")
            $resac = db_query("insert into db_acount values($acount,3495,19674,'".AddSlashes(pg_result($resaco,$conresaco,'c29_credito'))."','$this->c29_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Conta Corrente Saldo nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->c29_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Conta Corrente Saldo nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->c29_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c29_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($c29_sequencial=null,$dbwhere=null) {
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

      if($dbwhere==null || $dbwhere==""){
        $resaco = $this->sql_record($this->sql_query_file($c29_sequencial));
      }else{
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if(($resaco!=false)||($this->numrows!=0)){
        for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,19669,'$c29_sequencial','E')");
          $resac = db_query("insert into db_acount values($acount,3495,19669,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,3495,19670,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_contacorrentedetalhe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,3495,19671,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,3495,19672,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,3495,19673,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,3495,19674,'','".AddSlashes(pg_result($resaco,$iresaco,'c29_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from contacorrentesaldo
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($c29_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " c29_sequencial = $c29_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Conta Corrente Saldo nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$c29_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Conta Corrente Saldo nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$c29_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$c29_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:contacorrentesaldo";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $c29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from contacorrentesaldo ";
    $sql .= "      inner join contacorrentedetalhe  on  contacorrentedetalhe.c19_sequencial = contacorrentesaldo.c29_contacorrentedetalhe";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
    $sql .= "      left  join db_config  on  db_config.codigo = contacorrentedetalhe.c19_instit";
    $sql .= "      left  join orctiporec  on  orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
    $sql .= "      left  join orcorgao  on  orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
    $sql .= "      left  join orcunidade  on  orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
    $sql .= "      left  join conplanoreduz  on  conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
    $sql .= "      left  join empempenho  on  empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
    $sql .= "      left  join contabancaria  on  contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
    $sql .= "      left  join conlancamconcarpeculiar  on  conlancamconcarpeculiar.c08_sequencial = contacorrentedetalhe.c19_conlancamconcarpeculiar";
    $sql .= "      left  join contacorrente  on  contacorrente. = contacorrentedetalhe.c19_contacorrente";
    $sql2 = "";
    if($dbwhere==""){
      if($c29_sequencial!=null ){
        $sql2 .= " where contacorrentesaldo.c29_sequencial = $c29_sequencial ";
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

  function sql_query_buscasaldo($c29_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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

    $sql .= " from conlancamval ";
    $sql .= " inner join contacorrentedetalheconlancamval on c69_sequen               = c28_conlancamval ";
    $sql .= " inner join contacorrentedetalhe             on c28_contacorrentedetalhe = c19_sequencial ";
    $sql .= " inner join contacorrentesaldo               on c29_contacorrentedetalhe = c19_sequencial ";

    $sql2 = "";
    if($dbwhere==""){
      if(!empty($c29_sequencial)){
        $sql2 .= " where contacorrentesaldo.c29_sequencial = $c29_sequencial ";
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
  function sql_query_file ( $c29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from contacorrentesaldo ";
    $sql2 = "";
    if($dbwhere==""){
      if($c29_sequencial!=null ){
        $sql2 .= " where contacorrentesaldo.c29_sequencial = $c29_sequencial ";
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

  public function sql_query_busca_saldo ($campos = "*", $ordem = null, $dbwhere = null){

    $sql  = " select {$campos} ";
    $sql .= "   from contacorrentesaldo ";
    $sql .= "        inner join contacorrentedetalhe on contacorrentedetalhe.c19_sequencial = contacorrentesaldo.c29_contacorrentedetalhe";
    $sql .= "        inner join conplanoreduz        on conplanoreduz.c61_reduz = contacorrentedetalhe.c19_reduz ";
    $sql .= "                                       and conplanoreduz.c61_anousu = contacorrentedetalhe.c19_conplanoreduzanousu ";
    $sql .= "        inner join conplano   on conplano.c60_codcon = conplanoreduz.c61_codcon ";
    $sql .= "                             and conplano.c60_anousu = conplanoreduz.c61_anousu ";

    if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere} ";
    }

    if (!empty($ordem)) {
      $sql .= " order by {$ordem} ";
    }
    return $sql;
  }

  public function sql_query_busca_saldo_implantacao($sCampos, $sWhere, $iAnoUsu) {

    if (empty($sCampos)) {
      $sCampos = "*";
    }

    if (!empty($sWhere)) {
      $sWhere = " where {$sWhere} ";
    }

    $sSql  = " select {$sCampos} ";
    $sSql .= " from contacorrente ";
    $sSql .= "      inner join  contacorrentedetalhe on c17_sequencial           = c19_contacorrente ";
    $sSql .= "      inner join  contacorrentesaldo   on c29_contacorrentedetalhe = c19_sequencial ";
    $sSql .= "                                          and c29_mesusu = 0 and c29_anousu = {$iAnoUsu} ";
    $sSql .= " {$sWhere} ";

    return $sSql;
  }
}