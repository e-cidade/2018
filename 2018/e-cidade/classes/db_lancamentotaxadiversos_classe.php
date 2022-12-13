<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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
//MODULO: fiscal
//CLASSE DA ENTIDADE lancamentotaxadiversos
class cl_lancamentotaxadiversos {
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
  var $y120_sequencial = 0;
  var $y120_cgm = 0;
  var $y120_taxadiversos = 0;
  var $y120_unidade = 0;
  var $y120_periodo = 0;
  var $y120_datainicio_dia = null;
  var $y120_datainicio_mes = null;
  var $y120_datainicio_ano = null;
  var $y120_datainicio = null;
  var $y120_datafim_dia = null;
  var $y120_datafim_mes = null;
  var $y120_datafim_ano = null;
  var $y120_datafim = null;
  var $y120_issbase = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 y120_sequencial = int4 = Sequencial 
                 y120_cgm = int4 = CGM 
                 y120_taxadiversos = int4 = Taxa 
                 y120_unidade = float8 = Unidade 
                 y120_periodo = float8 = Período 
                 y120_datainicio = date = Data de Início 
                 y120_datafim = date = Data de fim 
                 y120_issbase = int4 = Inscrição Municipal 
                 ";
  //funcao construtor da classe
  function cl_lancamentotaxadiversos() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("lancamentotaxadiversos");
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
      $this->y120_sequencial = ($this->y120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_sequencial"]:$this->y120_sequencial);
      $this->y120_cgm = ($this->y120_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_cgm"]:$this->y120_cgm);
      $this->y120_taxadiversos = ($this->y120_taxadiversos == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_taxadiversos"]:$this->y120_taxadiversos);
      $this->y120_unidade = ($this->y120_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_unidade"]:$this->y120_unidade);
      $this->y120_periodo = ($this->y120_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_periodo"]:$this->y120_periodo);
      if($this->y120_datainicio == ""){
        $this->y120_datainicio_dia = ($this->y120_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datainicio_dia"]:$this->y120_datainicio_dia);
        $this->y120_datainicio_mes = ($this->y120_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datainicio_mes"]:$this->y120_datainicio_mes);
        $this->y120_datainicio_ano = ($this->y120_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datainicio_ano"]:$this->y120_datainicio_ano);
        if($this->y120_datainicio_dia != ""){
          $this->y120_datainicio = $this->y120_datainicio_ano."-".$this->y120_datainicio_mes."-".$this->y120_datainicio_dia;
        }
      }
      if($this->y120_datafim == ""){
        $this->y120_datafim_dia = ($this->y120_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datafim_dia"]:$this->y120_datafim_dia);
        $this->y120_datafim_mes = ($this->y120_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datafim_mes"]:$this->y120_datafim_mes);
        $this->y120_datafim_ano = ($this->y120_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_datafim_ano"]:$this->y120_datafim_ano);
        if($this->y120_datafim_dia != ""){
          $this->y120_datafim = $this->y120_datafim_ano."-".$this->y120_datafim_mes."-".$this->y120_datafim_dia;
        }
      }
      $this->y120_issbase = ($this->y120_issbase == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_issbase"]:$this->y120_issbase);
    }else{
      $this->y120_sequencial = ($this->y120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["y120_sequencial"]:$this->y120_sequencial);
    }
  }
  // funcao para Inclusão
  function incluir ($y120_sequencial){
    $this->atualizacampos();
    if($this->y120_cgm == null ){
      $this->y120_cgm = "0";
    }
    if($this->y120_taxadiversos == null ){
      $this->erro_sql = " Campo Taxa não informado.";
      $this->erro_campo = "y120_taxadiversos";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->y120_unidade == null ){
      $this->erro_sql = " Campo Unidade não informado.";
      $this->erro_campo = "y120_unidade";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->y120_periodo == null ){
      $this->y120_periodo = "0";
    }
    if($this->y120_datainicio == null ){
      $this->y120_datainicio = "null";
    }
    if($this->y120_datafim == null ){
      $this->y120_datafim = "null";
    }
    if($this->y120_issbase == null ){
      $this->y120_issbase = 'null';
    }
    if($y120_sequencial == "" || $y120_sequencial == null ){
      $result = db_query("select nextval('lancamentotaxadiversos_y120_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: lancamentotaxadiversos_y120_sequencial_seq do campo: y120_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->y120_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from lancamentotaxadiversos_y120_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $y120_sequencial)){
        $this->erro_sql = " Campo y120_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->y120_sequencial = $y120_sequencial;
      }
    }
    if(($this->y120_sequencial == null) || ($this->y120_sequencial == "") ){
      $this->erro_sql = " Campo y120_sequencial não declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into lancamentotaxadiversos(
                                       y120_sequencial 
                                      ,y120_cgm 
                                      ,y120_taxadiversos 
                                      ,y120_unidade 
                                      ,y120_periodo 
                                      ,y120_datainicio 
                                      ,y120_datafim 
                                      ,y120_issbase 
                       )
                values (
                                $this->y120_sequencial 
                               ,$this->y120_cgm 
                               ,$this->y120_taxadiversos 
                               ,$this->y120_unidade 
                               ,$this->y120_periodo 
                               ,".($this->y120_datainicio == "null" || $this->y120_datainicio == ""?"null":"'".$this->y120_datainicio."'")." 
                               ,".($this->y120_datafim == "null" || $this->y120_datafim == ""?"null":"'".$this->y120_datafim."'")." 
                               ,$this->y120_issbase 
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Lançamento de Taxas diversas ($this->y120_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Lançamento de Taxas diversas já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Lançamento de Taxas diversas ($this->y120_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->y120_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->y120_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,22057,'$this->y120_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,3974,22057,'','".AddSlashes(pg_result($resaco,0,'y120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22058,'','".AddSlashes(pg_result($resaco,0,'y120_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22059,'','".AddSlashes(pg_result($resaco,0,'y120_taxadiversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22060,'','".AddSlashes(pg_result($resaco,0,'y120_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22079,'','".AddSlashes(pg_result($resaco,0,'y120_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22061,'','".AddSlashes(pg_result($resaco,0,'y120_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22062,'','".AddSlashes(pg_result($resaco,0,'y120_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3974,22124,'','".AddSlashes(pg_result($resaco,0,'y120_issbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  public function alterar ($y120_sequencial=null) {
    $this->atualizacampos();
    $sql = " update lancamentotaxadiversos set ";
    $virgula = "";
    if(trim($this->y120_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_sequencial"])){
      $sql  .= $virgula." y120_sequencial = $this->y120_sequencial ";
      $virgula = ",";
      if(trim($this->y120_sequencial) == null ){
        $this->erro_sql = " Campo Sequencial não informado.";
        $this->erro_campo = "y120_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->y120_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_cgm"])){
      if(trim($this->y120_cgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y120_cgm"])){
        $this->y120_cgm = "0" ;
      }
      $sql  .= $virgula." y120_cgm = $this->y120_cgm ";
      $virgula = ",";
    }
    if(trim($this->y120_taxadiversos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_taxadiversos"])){
      $sql  .= $virgula." y120_taxadiversos = $this->y120_taxadiversos ";
      $virgula = ",";
      if(trim($this->y120_taxadiversos) == null ){
        $this->erro_sql = " Campo Taxa não informado.";
        $this->erro_campo = "y120_taxadiversos";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->y120_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_unidade"])){
      $sql  .= $virgula." y120_unidade = $this->y120_unidade ";
      $virgula = ",";
      if(trim($this->y120_unidade) == null ){
        $this->erro_sql = " Campo Unidade não informado.";
        $this->erro_campo = "y120_unidade";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->y120_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_periodo"])){
      if(trim($this->y120_periodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y120_periodo"])){
        $this->y120_periodo = "0" ;
      }
      $sql  .= $virgula." y120_periodo = $this->y120_periodo ";
      $virgula = ",";
    }
    if(trim($this->y120_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y120_datainicio_dia"] !="") ){

      if(trim($this->y120_datainicio) == 'null') {
        $sql  .= $virgula." y120_datainicio = null ";
      } else {
        $sql  .= $virgula." y120_datainicio = '$this->y120_datainicio' ";
      }

      $virgula = ",";

    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["y120_datainicio_dia"])){
        $sql  .= $virgula." y120_datainicio = null ";
        $virgula = ",";
      }
    }
    if(trim($this->y120_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y120_datafim_dia"] !="") ){

      if(trim($this->y120_datafim) == 'null') {
        $sql  .= $virgula." y120_datafim = null ";
      } else {
        $sql  .= $virgula." y120_datafim = '$this->y120_datafim' ";
      }

      $virgula = ",";
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["y120_datafim_dia"])){
        $sql  .= $virgula." y120_datafim = null ";
        $virgula = ",";
      }
    }
    if(trim($this->y120_issbase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y120_issbase"])){
      if(trim($this->y120_issbase)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y120_issbase"])){
        $this->y120_issbase = 'null' ;
      }
      $sql  .= $virgula." y120_issbase = $this->y120_issbase ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($y120_sequencial!=null){
      $sql .= " y120_sequencial = $this->y120_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->y120_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,22057,'$this->y120_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_sequencial"]) || $this->y120_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,3974,22057,'".AddSlashes(pg_result($resaco,$conresaco,'y120_sequencial'))."','$this->y120_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_cgm"]) || $this->y120_cgm != "")
            $resac = db_query("insert into db_acount values($acount,3974,22058,'".AddSlashes(pg_result($resaco,$conresaco,'y120_cgm'))."','$this->y120_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_taxadiversos"]) || $this->y120_taxadiversos != "")
            $resac = db_query("insert into db_acount values($acount,3974,22059,'".AddSlashes(pg_result($resaco,$conresaco,'y120_taxadiversos'))."','$this->y120_taxadiversos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_unidade"]) || $this->y120_unidade != "")
            $resac = db_query("insert into db_acount values($acount,3974,22060,'".AddSlashes(pg_result($resaco,$conresaco,'y120_unidade'))."','$this->y120_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_periodo"]) || $this->y120_periodo != "")
            $resac = db_query("insert into db_acount values($acount,3974,22079,'".AddSlashes(pg_result($resaco,$conresaco,'y120_periodo'))."','$this->y120_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_datainicio"]) || $this->y120_datainicio != "")
            $resac = db_query("insert into db_acount values($acount,3974,22061,'".AddSlashes(pg_result($resaco,$conresaco,'y120_datainicio'))."','$this->y120_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_datafim"]) || $this->y120_datafim != "")
            $resac = db_query("insert into db_acount values($acount,3974,22062,'".AddSlashes(pg_result($resaco,$conresaco,'y120_datafim'))."','$this->y120_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["y120_issbase"]) || $this->y120_issbase != "")
            $resac = db_query("insert into db_acount values($acount,3974,22124,'".AddSlashes(pg_result($resaco,$conresaco,'y120_issbase'))."','$this->y120_issbase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Lançamento de Taxas diversas não Alterado. Alteração Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->y120_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Lançamento de Taxas diversas não foi Alterado. Alteração Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->y120_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->y120_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  public function excluir ($y120_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($y120_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,22057,'$y120_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,3974,22057,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22058,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22059,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_taxadiversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22060,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22079,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22061,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22062,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3974,22124,'','".AddSlashes(pg_result($resaco,$iresaco,'y120_issbase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from lancamentotaxadiversos
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($y120_sequencial)){
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " y120_sequencial = $y120_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Lançamento de Taxas diversas não Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$y120_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Lançamento de Taxas diversas não Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$y120_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$y120_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:lancamentotaxadiversos";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  public function sql_query ($y120_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from lancamentotaxadiversos ";
    $sql .= "      left  join issbase  on  issbase.q02_inscr = lancamentotaxadiversos.y120_issbase";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = lancamentotaxadiversos.y120_cgm";
    $sql .= "      inner join taxadiversos  on  taxadiversos.y119_sequencial = lancamentotaxadiversos.y120_taxadiversos";
    $sql .= "      left  join cgm  as a on   a.z01_numcgm = issbase.q02_numcgm";
    $sql .= "      inner join db_formulas  on  db_formulas.db148_sequencial = taxadiversos.y119_formula";
    $sql .= "      inner join grupotaxadiversos  on  grupotaxadiversos.y118_sequencial = taxadiversos.y119_grupotaxadiversos";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($y120_sequencial)) {
        $sql2 .= " where lancamentotaxadiversos.y120_sequencial = $y120_sequencial ";
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
  public function sql_query_join_diversos ($y120_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from lancamentotaxadiversos ";
    $sql .= "      left  join issbase  on  issbase.q02_inscr = lancamentotaxadiversos.y120_issbase";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = lancamentotaxadiversos.y120_cgm";
    $sql .= "      inner join taxadiversos  on  taxadiversos.y119_sequencial = lancamentotaxadiversos.y120_taxadiversos";
    $sql .= "      left  join cgm  as a on   a.z01_numcgm = issbase.q02_numcgm";
    $sql .= "      inner join db_formulas  on  db_formulas.db148_sequencial = taxadiversos.y119_formula";
    $sql .= "      inner join grupotaxadiversos  on  grupotaxadiversos.y118_sequencial = taxadiversos.y119_grupotaxadiversos";
    $sql .= "      left  join diversoslancamentotaxa on dv14_lancamentotaxadiversos = lancamentotaxadiversos.y120_sequencial";
    $sql .= "      left  join diversos on dv05_coddiver = diversoslancamentotaxa.dv14_diversos";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($y120_sequencial)) {
        $sql2 .= " where lancamentotaxadiversos.y120_sequencial = $y120_sequencial ";
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
  public function sql_query_file ($y120_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from lancamentotaxadiversos ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($y120_sequencial)){
        $sql2 .= " where lancamentotaxadiversos.y120_sequencial = $y120_sequencial ";
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
