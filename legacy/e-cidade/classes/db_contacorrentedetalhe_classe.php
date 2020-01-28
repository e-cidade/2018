<?php
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
//CLASSE DA ENTIDADE contacorrentedetalhe
class cl_contacorrentedetalhe {
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
  var $c19_sequencial = 0;
  var $c19_contacorrente = 0;
  var $c19_orctiporec = 0;
  var $c19_instit = 0;
  var $c19_concarpeculiar = null;
  var $c19_contabancaria = 0;
  var $c19_reduz = 0;
  var $c19_numemp = 0;
  var $c19_numcgm = 0;
  var $c19_orcunidadeanousu = 0;
  var $c19_orcunidadeorgao = 0;
  var $c19_orcunidadeunidade = 0;
  var $c19_orcorgaoanousu = 0;
  var $c19_orcorgaoorgao = 0;
  var $c19_conplanoreduzanousu = 0;
  var $c19_acordo = 0;
  var $c19_estrutural = null;
  var $c19_orcdotacao = 0;
  var $c19_orcdotacaoanousu = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 c19_sequencial = int4 = Sequencial
                 c19_contacorrente = int4 = Código
                 c19_orctiporec = int4 = Recurso
                 c19_instit = int4 = Cod. Instituição
                 c19_concarpeculiar = varchar(100) = Característica Peculiar
                 c19_contabancaria = int4 = Codigo sequencial da conta bancaria
                 c19_reduz = int4 = Código da Conta
                 c19_numemp = int4 = Seq. Empenho
                 c19_numcgm = int4 = Número do Credor
                 c19_orcunidadeanousu = int4 = Exercício
                 c19_orcunidadeorgao = int4 = Órgão
                 c19_orcunidadeunidade = int4 = Unidade
                 c19_orcorgaoanousu = int4 = Exercício do Órgão
                 c19_orcorgaoorgao = int4 = Órgão
                 c19_conplanoreduzanousu = int4 = Exercício
                 c19_acordo = int4 = Acordo
                 c19_estrutural = varchar(15) = Estrutural
                 c19_orcdotacao = int4 = Orcdotação
                 c19_orcdotacaoanousu = int4 = Orcdotação Ano
                 ";
  //funcao construtor da classe
  function cl_contacorrentedetalhe() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("contacorrentedetalhe");
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
      $this->c19_sequencial = ($this->c19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]:$this->c19_sequencial);
      $this->c19_contacorrente = ($this->c19_contacorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"]:$this->c19_contacorrente);
      $this->c19_orctiporec = ($this->c19_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"]:$this->c19_orctiporec);
      $this->c19_instit = ($this->c19_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_instit"]:$this->c19_instit);
      $this->c19_concarpeculiar = ($this->c19_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"]:$this->c19_concarpeculiar);
      $this->c19_contabancaria = ($this->c19_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"]:$this->c19_contabancaria);
      $this->c19_reduz = ($this->c19_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_reduz"]:$this->c19_reduz);
      $this->c19_numemp = ($this->c19_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_numemp"]:$this->c19_numemp);
      $this->c19_numcgm = ($this->c19_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_numcgm"]:$this->c19_numcgm);
      $this->c19_orcunidadeanousu = ($this->c19_orcunidadeanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"]:$this->c19_orcunidadeanousu);
      $this->c19_orcunidadeorgao = ($this->c19_orcunidadeorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"]:$this->c19_orcunidadeorgao);
      $this->c19_orcunidadeunidade = ($this->c19_orcunidadeunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"]:$this->c19_orcunidadeunidade);
      $this->c19_orcorgaoanousu = ($this->c19_orcorgaoanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"]:$this->c19_orcorgaoanousu);
      $this->c19_orcorgaoorgao = ($this->c19_orcorgaoorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"]:$this->c19_orcorgaoorgao);
      $this->c19_conplanoreduzanousu = ($this->c19_conplanoreduzanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"]:$this->c19_conplanoreduzanousu);
      $this->c19_acordo = ($this->c19_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_acordo"]:$this->c19_acordo);
      $this->c19_estrutural = ($this->c19_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_estrutural"]:$this->c19_estrutural);
      $this->c19_orcdotacao = ($this->c19_orcdotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"]:$this->c19_orcdotacao);
      $this->c19_orcdotacaoanousu = ($this->c19_orcdotacaoanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"]:$this->c19_orcdotacaoanousu);
    }else{
      $this->c19_sequencial = ($this->c19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]:$this->c19_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($c19_sequencial){
    $this->atualizacampos();
    if($this->c19_contacorrente == null ){
      $this->c19_contacorrente = "null";
    }
    if($this->c19_orctiporec == null ){
      $this->c19_orctiporec = "null";
    }
    if($this->c19_instit == null ){
      $this->c19_instit = "null";
    }
    if($this->c19_concarpeculiar == null ){
      $this->c19_concarpeculiar = "null";
    }
    if($this->c19_contabancaria == null ){
      $this->c19_contabancaria = "null";
    }
    if($this->c19_reduz == null ){
      $this->c19_reduz = "null";
    }
    if($this->c19_numemp == null ){
      $this->c19_numemp = "null";
    }
    if($this->c19_numcgm == null ){
      $this->c19_numcgm = "null";
    }
    if($this->c19_orcunidadeanousu == null ){
      $this->c19_orcunidadeanousu = "null";
    }
    if($this->c19_orcunidadeorgao == null ){
      $this->c19_orcunidadeorgao = "null";
    }
    if($this->c19_orcunidadeunidade == null ){
      $this->c19_orcunidadeunidade = "null";
    }
    if($this->c19_orcorgaoanousu == null ){
      $this->c19_orcorgaoanousu = "null";
    }
    if($this->c19_orcorgaoorgao == null ){
      $this->c19_orcorgaoorgao = "null";
    }
    if($this->c19_conplanoreduzanousu == null ){
      $this->c19_conplanoreduzanousu = "null";
    }
    if($this->c19_acordo == null ){
      $this->c19_acordo = "null";
    }
    if($this->c19_estrutural == null ){
      $this->c19_estrutural = "null";
    }
    if($this->c19_orcdotacao == null ){
      $this->c19_orcdotacao = "null";
    }
    if($this->c19_orcdotacaoanousu == null ){
      $this->c19_orcdotacaoanousu = "null";
    }
    if($c19_sequencial == "" || $c19_sequencial == null ){
      $result = db_query("select nextval('contacorrentedetalhe_c19_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: contacorrentedetalhe_c19_sequencial_seq do campo: c19_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c19_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from contacorrentedetalhe_c19_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $c19_sequencial)){
        $this->erro_sql = " Campo c19_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->c19_sequencial = $c19_sequencial;
      }
    }
    if(($this->c19_sequencial == null) || ($this->c19_sequencial == "") ){
      $this->erro_sql = " Campo c19_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into contacorrentedetalhe(
                                       c19_sequencial
                                      ,c19_contacorrente
                                      ,c19_orctiporec
                                      ,c19_instit
                                      ,c19_concarpeculiar
                                      ,c19_contabancaria
                                      ,c19_reduz
                                      ,c19_numemp
                                      ,c19_numcgm
                                      ,c19_orcunidadeanousu
                                      ,c19_orcunidadeorgao
                                      ,c19_orcunidadeunidade
                                      ,c19_orcorgaoanousu
                                      ,c19_orcorgaoorgao
                                      ,c19_conplanoreduzanousu
                                      ,c19_acordo
                                      ,c19_estrutural
                                      ,c19_orcdotacao
                                      ,c19_orcdotacaoanousu
                       )
                values (
                                $this->c19_sequencial
                               ,$this->c19_contacorrente
                               ,$this->c19_orctiporec
                               ,$this->c19_instit
                               ,$this->c19_concarpeculiar
                               ,$this->c19_contabancaria
                               ,$this->c19_reduz
                               ,$this->c19_numemp
                               ,$this->c19_numcgm
                               ,$this->c19_orcunidadeanousu
                               ,$this->c19_orcunidadeorgao
                               ,$this->c19_orcunidadeunidade
                               ,$this->c19_orcorgaoanousu
                               ,$this->c19_orcorgaoorgao
                               ,$this->c19_conplanoreduzanousu
                               ,$this->c19_acordo
                               ,$this->c19_estrutural
                               ,$this->c19_orcdotacao
                               ,$this->c19_orcdotacaoanousu
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Conta Corrente Detalhe ($this->c19_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Conta Corrente Detalhe já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Conta Corrente Detalhe ($this->c19_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->c19_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->c19_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19648,'$this->c19_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,3492,19648,'','".AddSlashes(pg_result($resaco,0,'c19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19649,'','".AddSlashes(pg_result($resaco,0,'c19_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19650,'','".AddSlashes(pg_result($resaco,0,'c19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19651,'','".AddSlashes(pg_result($resaco,0,'c19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19652,'','".AddSlashes(pg_result($resaco,0,'c19_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19653,'','".AddSlashes(pg_result($resaco,0,'c19_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19654,'','".AddSlashes(pg_result($resaco,0,'c19_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19655,'','".AddSlashes(pg_result($resaco,0,'c19_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19656,'','".AddSlashes(pg_result($resaco,0,'c19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19657,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19658,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19659,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19660,'','".AddSlashes(pg_result($resaco,0,'c19_orcorgaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19661,'','".AddSlashes(pg_result($resaco,0,'c19_orcorgaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19665,'','".AddSlashes(pg_result($resaco,0,'c19_conplanoreduzanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,19704,'','".AddSlashes(pg_result($resaco,0,'c19_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,20732,'','".AddSlashes(pg_result($resaco,0,'c19_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,20733,'','".AddSlashes(pg_result($resaco,0,'c19_orcdotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3492,20734,'','".AddSlashes(pg_result($resaco,0,'c19_orcdotacaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  public function alterar ($c19_sequencial=null) {
    $this->atualizacampos();
    $sql = " update contacorrentedetalhe set ";
    $virgula = "";
    if(trim($this->c19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_sequencial"])){
      $sql  .= $virgula." c19_sequencial = $this->c19_sequencial ";
      $virgula = ",";
      if(trim($this->c19_sequencial) == null ){
        $this->erro_sql = " Campo Sequencial não informado.";
        $this->erro_campo = "c19_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->c19_contacorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"])){
      if(trim($this->c19_contacorrente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"])){
        $this->c19_contacorrente = "0" ;
      }
      $sql  .= $virgula." c19_contacorrente = $this->c19_contacorrente ";
      $virgula = ",";
    }
    if(trim($this->c19_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"])){
      if(trim($this->c19_orctiporec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"])){
        $this->c19_orctiporec = "0" ;
      }
      $sql  .= $virgula." c19_orctiporec = $this->c19_orctiporec ";
      $virgula = ",";
    }
    if(trim($this->c19_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"])){
      if(trim($this->c19_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"])){
        $this->c19_instit = "0" ;
      }
      $sql  .= $virgula." c19_instit = $this->c19_instit ";
      $virgula = ",";
    }
    if(trim($this->c19_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"])){
      $sql  .= $virgula." c19_concarpeculiar = '$this->c19_concarpeculiar' ";
      $virgula = ",";
    }
    if(trim($this->c19_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"])){
      if(trim($this->c19_contabancaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"])){
        $this->c19_contabancaria = "0" ;
      }
      $sql  .= $virgula." c19_contabancaria = $this->c19_contabancaria ";
      $virgula = ",";
    }
    if(trim($this->c19_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"])){
      if(trim($this->c19_reduz)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"])){
        $this->c19_reduz = "0" ;
      }
      $sql  .= $virgula." c19_reduz = $this->c19_reduz ";
      $virgula = ",";
    }
    if(trim($this->c19_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"])){
      if(trim($this->c19_numemp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"])){
        $this->c19_numemp = "0" ;
      }
      $sql  .= $virgula." c19_numemp = $this->c19_numemp ";
      $virgula = ",";
    }
    if(trim($this->c19_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"])){
      if(trim($this->c19_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"])){
        $this->c19_numcgm = "0" ;
      }
      $sql  .= $virgula." c19_numcgm = $this->c19_numcgm ";
      $virgula = ",";
    }
    if(trim($this->c19_orcunidadeanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"])){
      if(trim($this->c19_orcunidadeanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"])){
        $this->c19_orcunidadeanousu = "0" ;
      }
      $sql  .= $virgula." c19_orcunidadeanousu = $this->c19_orcunidadeanousu ";
      $virgula = ",";
    }
    if(trim($this->c19_orcunidadeorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"])){
      if(trim($this->c19_orcunidadeorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"])){
        $this->c19_orcunidadeorgao = "0" ;
      }
      $sql  .= $virgula." c19_orcunidadeorgao = $this->c19_orcunidadeorgao ";
      $virgula = ",";
    }
    if(trim($this->c19_orcunidadeunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"])){
      if(trim($this->c19_orcunidadeunidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"])){
        $this->c19_orcunidadeunidade = "0" ;
      }
      $sql  .= $virgula." c19_orcunidadeunidade = $this->c19_orcunidadeunidade ";
      $virgula = ",";
    }
    if(trim($this->c19_orcorgaoanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"])){
      if(trim($this->c19_orcorgaoanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"])){
        $this->c19_orcorgaoanousu = "0" ;
      }
      $sql  .= $virgula." c19_orcorgaoanousu = $this->c19_orcorgaoanousu ";
      $virgula = ",";
    }
    if(trim($this->c19_orcorgaoorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"])){
      if(trim($this->c19_orcorgaoorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"])){
        $this->c19_orcorgaoorgao = "0" ;
      }
      $sql  .= $virgula." c19_orcorgaoorgao = $this->c19_orcorgaoorgao ";
      $virgula = ",";
    }
    if(trim($this->c19_conplanoreduzanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"])){
      if(trim($this->c19_conplanoreduzanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"])){
        $this->c19_conplanoreduzanousu = "0" ;
      }
      $sql  .= $virgula." c19_conplanoreduzanousu = $this->c19_conplanoreduzanousu ";
      $virgula = ",";
    }
    if(trim($this->c19_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"])){
      if(trim($this->c19_acordo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"])){
        $this->c19_acordo = "0" ;
      }
      $sql  .= $virgula." c19_acordo = $this->c19_acordo ";
      $virgula = ",";
    }
    if(trim($this->c19_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_estrutural"])){
      $sql  .= $virgula." c19_estrutural = '$this->c19_estrutural' ";
      $virgula = ",";
    }
    if(trim($this->c19_orcdotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"])){
      if(trim($this->c19_orcdotacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"])){
        $this->c19_orcdotacao = "0" ;
      }
      $sql  .= $virgula." c19_orcdotacao = $this->c19_orcdotacao ";
      $virgula = ",";
    }
    if(trim($this->c19_orcdotacaoanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"])){
      if(trim($this->c19_orcdotacaoanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"])){
        $this->c19_orcdotacaoanousu = "0" ;
      }
      $sql  .= $virgula." c19_orcdotacaoanousu = $this->c19_orcdotacaoanousu ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($c19_sequencial!=null){
      $sql .= " c19_sequencial = $this->c19_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->c19_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,19648,'$this->c19_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]) || $this->c19_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,3492,19648,'".AddSlashes(pg_result($resaco,$conresaco,'c19_sequencial'))."','$this->c19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"]) || $this->c19_contacorrente != "")
            $resac = db_query("insert into db_acount values($acount,3492,19649,'".AddSlashes(pg_result($resaco,$conresaco,'c19_contacorrente'))."','$this->c19_contacorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"]) || $this->c19_orctiporec != "")
            $resac = db_query("insert into db_acount values($acount,3492,19650,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orctiporec'))."','$this->c19_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"]) || $this->c19_instit != "")
            $resac = db_query("insert into db_acount values($acount,3492,19651,'".AddSlashes(pg_result($resaco,$conresaco,'c19_instit'))."','$this->c19_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"]) || $this->c19_concarpeculiar != "")
            $resac = db_query("insert into db_acount values($acount,3492,19652,'".AddSlashes(pg_result($resaco,$conresaco,'c19_concarpeculiar'))."','$this->c19_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"]) || $this->c19_contabancaria != "")
            $resac = db_query("insert into db_acount values($acount,3492,19653,'".AddSlashes(pg_result($resaco,$conresaco,'c19_contabancaria'))."','$this->c19_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"]) || $this->c19_reduz != "")
            $resac = db_query("insert into db_acount values($acount,3492,19654,'".AddSlashes(pg_result($resaco,$conresaco,'c19_reduz'))."','$this->c19_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"]) || $this->c19_numemp != "")
            $resac = db_query("insert into db_acount values($acount,3492,19655,'".AddSlashes(pg_result($resaco,$conresaco,'c19_numemp'))."','$this->c19_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"]) || $this->c19_numcgm != "")
            $resac = db_query("insert into db_acount values($acount,3492,19656,'".AddSlashes(pg_result($resaco,$conresaco,'c19_numcgm'))."','$this->c19_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"]) || $this->c19_orcunidadeanousu != "")
            $resac = db_query("insert into db_acount values($acount,3492,19657,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeanousu'))."','$this->c19_orcunidadeanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"]) || $this->c19_orcunidadeorgao != "")
            $resac = db_query("insert into db_acount values($acount,3492,19658,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeorgao'))."','$this->c19_orcunidadeorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"]) || $this->c19_orcunidadeunidade != "")
            $resac = db_query("insert into db_acount values($acount,3492,19659,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeunidade'))."','$this->c19_orcunidadeunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"]) || $this->c19_orcorgaoanousu != "")
            $resac = db_query("insert into db_acount values($acount,3492,19660,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcorgaoanousu'))."','$this->c19_orcorgaoanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"]) || $this->c19_orcorgaoorgao != "")
            $resac = db_query("insert into db_acount values($acount,3492,19661,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcorgaoorgao'))."','$this->c19_orcorgaoorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"]) || $this->c19_conplanoreduzanousu != "")
            $resac = db_query("insert into db_acount values($acount,3492,19665,'".AddSlashes(pg_result($resaco,$conresaco,'c19_conplanoreduzanousu'))."','$this->c19_conplanoreduzanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"]) || $this->c19_acordo != "")
            $resac = db_query("insert into db_acount values($acount,3492,19704,'".AddSlashes(pg_result($resaco,$conresaco,'c19_acordo'))."','$this->c19_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_estrutural"]) || $this->c19_estrutural != "")
            $resac = db_query("insert into db_acount values($acount,3492,20732,'".AddSlashes(pg_result($resaco,$conresaco,'c19_estrutural'))."','$this->c19_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"]) || $this->c19_orcdotacao != "")
            $resac = db_query("insert into db_acount values($acount,3492,20733,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcdotacao'))."','$this->c19_orcdotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"]) || $this->c19_orcdotacaoanousu != "")
            $resac = db_query("insert into db_acount values($acount,3492,20734,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcdotacaoanousu'))."','$this->c19_orcdotacaoanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Conta Corrente Detalhe nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->c19_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Conta Corrente Detalhe nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->c19_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->c19_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  public function excluir ($c19_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($c19_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,19648,'$c19_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,3492,19648,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19649,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19650,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19651,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19652,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19653,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19654,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19655,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19656,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19657,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19658,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19659,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19660,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcorgaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19661,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcorgaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19665,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_conplanoreduzanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,19704,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,20732,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,20733,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcdotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,3492,20734,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcdotacaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from contacorrentedetalhe
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($c19_sequencial)){
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " c19_sequencial = $c19_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Conta Corrente Detalhe nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$c19_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Conta Corrente Detalhe nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$c19_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$c19_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:contacorrentedetalhe";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "  from contacorrentedetalhe ";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
    $sql .= "      left  join db_config  on  db_config.codigo = contacorrentedetalhe.c19_instit";
    $sql .= "      left  join orctiporec  on  orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
    $sql .= "      left  join orcorgao  on  orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
    $sql .= "      left  join orcunidade  on  orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
    $sql .= "      left  join orcdotacao  on  orcdotacao.o58_anousu = contacorrentedetalhe.c19_orcdotacaoanousu and  orcdotacao.o58_coddot = contacorrentedetalhe.c19_orcdotacao";
    $sql .= "      left  join conplanoreduz  on  conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
    $sql .= "      left  join empempenho  on  empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
    $sql .= "      left  join concarpeculiar  on  concarpeculiar.c58_sequencial = contacorrentedetalhe.c19_concarpeculiar";
    $sql .= "      left  join contabancaria  on  contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
    $sql .= "      left  join bancoagencia                 on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
    $sql .= "      left  join acordo  on  acordo.ac16_sequencial = contacorrentedetalhe.c19_acordo";
    $sql .= "      left  join contacorrente                on contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "      left  join db_tipoinstit                on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= "      left  join db_estruturavalor            on db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
    $sql .= "      left  join orcorgao  as c               on c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
    $sql .= "      left  join conplano  as d               on d.c60_codcon = conplanoreduz.c61_codcon and   d.c60_anousu = conplanoreduz.c61_anousu";
    $sql .= "      left  join orcdotacao                   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      left  join pctipocompra                 on pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      left  join emptipo                      on emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left  join concarpeculiarclassificacao  on concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
    $sql .= "      left  join db_depart                    on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
    $sql .= "      left  join acordogrupo                  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
    $sql .= "      left  join acordosituacao               on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
    $sql .= "      left  join acordocomissao               on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    // $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    // $sql .= "      inner join db_config  as a on   a.codigo = orcorgao.o40_instit";
    // $sql .= "      inner join db_config  as d on   d.codigo = conplanoreduz.c61_instit";
    // $sql .= "      inner join db_config  as b on   b.codigo = orcunidade.o41_instit";
    //$sql .= "      inner join orctiporec  as d on   d.o15_codigo = conplanoreduz.c61_codigo";
    // $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    // $sql .= "      inner join db_config  as d on   d.codigo = empempenho.e60_instit";
    //$sql .= "      inner join db_estruturavalor  as d on   d.db121_sequencial = concarpeculiar.c58_db_estruturavalor";
    // $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
    //$sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
    //$sql .= "      inner join concarpeculiar  as d on   d.c58_sequencial = empempenho.e60_concarpeculiar";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
  function sql_query_file ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from contacorrentedetalhe ";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
  function sql_query_saldo($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from contacorrentedetalhe ";
    $sql .= " inner join contacorrentesaldo on c19_sequencial = c29_contacorrentedetalhe";

    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
  function sql_query_lancamentos($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from contacorrentedetalhe ";
    $sql .= " left join contacorrentedetalheconlancamval on contacorrentedetalhe.c19_sequencial               = contacorrentedetalheconlancamval.c28_contacorrentedetalhe ";
    $sql .= " left join conlancamval                     on contacorrentedetalheconlancamval.c28_conlancamval = c69_sequen";

    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
  function sql_query_fileAtributos ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= "       from contacorrentedetalhe ";
    $sql .= "            left  join contacorrentedetalheconlancamval on contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe";
    $sql .= "            inner join contacorrente  on  contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "            left join conlancamval                     on contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen ";
    $sql .= "            inner join db_config                        on contacorrentedetalhe.c19_instit =  db_config.codigo ";
    $sql .= "            left join cgm                              on cgm.z01_numcgm                  = contacorrentedetalhe.c19_numcgm ";
    $sql .= "            left join concarpeculiar                   on concarpeculiar.c58_sequencial   = contacorrentedetalhe.c19_concarpeculiar";
    $sql .= "            left join contabancaria                    on contabancaria.db83_sequencial   = contacorrentedetalhe.c19_contabancaria";
    $sql .= "            left join bancoagencia                     on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
    $sql .= "            left join orctiporec                       on orctiporec.o15_codigo           = contacorrentedetalhe.c19_orctiporec";
    $sql .= "            left join empempenho                       on empempenho.e60_numemp           = contacorrentedetalhe.c19_numemp";
    $sql .= "            left join emppresta                        on emppresta.e45_numemp            = empempenho.e60_numemp";
    $sql .= "            left join orcorgao                         on orcorgao.o40_anousu             = contacorrentedetalhe.c19_orcorgaoanousu ";
    $sql .= "                                                      and orcorgao.o40_orgao              = contacorrentedetalhe.c19_orcorgaoorgao";
    $sql .= "            left join orcunidade                       on orcunidade.o41_anousu           = contacorrentedetalhe.c19_orcunidadeanousu ";
    $sql .= "                                                      and orcunidade.o41_orgao            = contacorrentedetalhe.c19_orcunidadeorgao ";
    $sql .= "                                                      and orcunidade.o41_unidade          = contacorrentedetalhe.c19_orcunidadeunidade";

    $sql .= "            inner join conplanoreduz                    on contacorrentedetalhe.c19_reduz = conplanoreduz.c61_reduz ";
    $sql .= "                                                       and conplanoreduz.c61_anousu       = contacorrentedetalhe.c19_conplanoreduzanousu ";
    $sql .= "            inner join conplano                         on conplano.c60_codcon            = conplanoreduz.c61_codcon ";
    $sql .= "                                                       and conplano.c60_anousu            = conplanoreduz.c61_anousu ";
    $sql .= "            left join acordo                           on acordo.ac16_sequencial          = contacorrentedetalhe.c19_acordo ";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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

  function sql_query_contacorrente_cgm ($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= "       from contacorrentedetalhe ";
    $sql .= "            inner join contacorrente  on  contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "            inner join cgm            on cgm.z01_numcgm                = contacorrentedetalhe.c19_numcgm ";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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

  function sql_query_viewDetalhes ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from contacorrentedetalhe ";
    $sql .= "      left  join cgm                          on cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
    $sql .= "      left  join db_config                    on db_config.codigo = contacorrentedetalhe.c19_instit";
    $sql .= "      left  join orctiporec                   on orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
    $sql .= "      left  join orcorgao                     on orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
    $sql .= "      left  join orcunidade                   on orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
    $sql .= "      left  join conplanoreduz                on conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
    $sql .= "      left  join empempenho                   on empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
    $sql .= "      left  join concarpeculiar               on concarpeculiar.c58_sequencial = contacorrentedetalhe.c19_concarpeculiar";
    $sql .= "      left  join contabancaria                on contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
    $sql .= "      left  join bancoagencia                 on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
    $sql .= "      left  join acordo                       on acordo.ac16_sequencial = contacorrentedetalhe.c19_acordo";
    $sql .= "      left  join contacorrente                on contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "      left  join db_tipoinstit                on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sql .= "      left  join db_estruturavalor            on db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
    $sql .= "      left  join orcorgao  as c               on c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
    $sql .= "      left  join conplano  as d               on d.c60_codcon = conplanoreduz.c61_codcon and   d.c60_anousu = conplanoreduz.c61_anousu";
    $sql .= "      left  join orcdotacao                   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      left  join pctipocompra                 on pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      left  join emptipo                      on emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left  join concarpeculiarclassificacao  on concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
    $sql .= "      left  join db_depart                    on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
    $sql .= "      left  join acordogrupo                  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
    $sql .= "      left  join acordosituacao               on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
    $sql .= "      left  join acordocomissao               on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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

  public function sql_query_disponibilidade_financeira($sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from contacorrentedetalhe ";
    $sSql .= "        left join contacorrentedetalheconlancamval on contacorrentedetalheconlancamval
    .c28_contacorrentedetalhe = contacorrentedetalhe.c19_sequencial ";
    $sSql .= "        left join conlancamval on conlancamval.c69_sequen = contacorrentedetalheconlancamval
    .c28_conlancamval";
    $sSql .= "        inner join orctiporec on orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec ";
    $sSql .= "        left  join contacorrentesaldo on contacorrentesaldo.c29_contacorrentedetalhe = contacorrentedetalhe.c19_sequencial ";
    $sSql .= "                                     and contacorrentesaldo.c29_mesusu = 0 ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }
    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;

  }

}
?>