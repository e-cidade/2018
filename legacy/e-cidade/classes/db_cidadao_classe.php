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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE cidadao
class cl_cidadao {
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
  var $ov02_sequencial = 0;
  var $ov02_seq = 0;
  var $ov02_nome = null;
  var $ov02_ident = null;
  var $ov02_cnpjcpf = null;
  var $ov02_endereco = null;
  var $ov02_numero = 0;
  var $ov02_compl = null;
  var $ov02_bairro = null;
  var $ov02_munic = null;
  var $ov02_uf = null;
  var $ov02_cep = null;
  var $ov02_situacaocidadao = 0;
  var $ov02_ativo = 'f';
  var $ov02_data_dia = null;
  var $ov02_data_mes = null;
  var $ov02_data_ano = null;
  var $ov02_data = null;
  var $ov02_datanascimento_dia = null;
  var $ov02_datanascimento_mes = null;
  var $ov02_datanascimento_ano = null;
  var $ov02_datanascimento = null;
  var $ov02_sexo = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 ov02_sequencial = int4 = Cidadão
                 ov02_seq = int4 = Sequencial
                 ov02_nome = varchar(100) = Nome / Razão Social
                 ov02_ident = varchar(20) = Identidade
                 ov02_cnpjcpf = varchar(14) = CPF
                 ov02_endereco = varchar(100) = Endereço
                 ov02_numero = int4 = Número
                 ov02_compl = varchar(50) = Complemento
                 ov02_bairro = varchar(100) = Bairro
                 ov02_munic = varchar(100) = Município
                 ov02_uf = char(2) = UF
                 ov02_cep = varchar(8) = CEP
                 ov02_situacaocidadao = int4 = Situacão
                 ov02_ativo = bool = Ativo
                 ov02_data = date = Data
                 ov02_datanascimento = date = Data de Nascimento
                 ov02_sexo = varchar(1) = Sexo
                 ";
  //funcao construtor da classe
  function cl_cidadao() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("cidadao");
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
      $this->ov02_sequencial = ($this->ov02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"]:$this->ov02_sequencial);
      $this->ov02_seq = ($this->ov02_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_seq"]:$this->ov02_seq);
      $this->ov02_nome = ($this->ov02_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_nome"]:$this->ov02_nome);
      $this->ov02_ident = ($this->ov02_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_ident"]:$this->ov02_ident);
      $this->ov02_cnpjcpf = ($this->ov02_cnpjcpf == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_cnpjcpf"]:$this->ov02_cnpjcpf);
      $this->ov02_endereco = ($this->ov02_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_endereco"]:$this->ov02_endereco);
      $this->ov02_numero = ($this->ov02_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_numero"]:$this->ov02_numero);
      $this->ov02_compl = ($this->ov02_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_compl"]:$this->ov02_compl);
      $this->ov02_bairro = ($this->ov02_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_bairro"]:$this->ov02_bairro);
      $this->ov02_munic = ($this->ov02_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_munic"]:$this->ov02_munic);
      $this->ov02_uf = ($this->ov02_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_uf"]:$this->ov02_uf);
      $this->ov02_cep = ($this->ov02_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_cep"]:$this->ov02_cep);
      $this->ov02_situacaocidadao = ($this->ov02_situacaocidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_situacaocidadao"]:$this->ov02_situacaocidadao);
      $this->ov02_ativo = ($this->ov02_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ov02_ativo"]:$this->ov02_ativo);
      if($this->ov02_data == ""){
        $this->ov02_data_dia = ($this->ov02_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"]:$this->ov02_data_dia);
        $this->ov02_data_mes = ($this->ov02_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_data_mes"]:$this->ov02_data_mes);
        $this->ov02_data_ano = ($this->ov02_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_data_ano"]:$this->ov02_data_ano);
        if($this->ov02_data_dia != ""){
          $this->ov02_data = $this->ov02_data_ano."-".$this->ov02_data_mes."-".$this->ov02_data_dia;
        }
      }
      if($this->ov02_datanascimento == ""){
        $this->ov02_datanascimento_dia = ($this->ov02_datanascimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_datanascimento_dia"]:$this->ov02_datanascimento_dia);
        $this->ov02_datanascimento_mes = ($this->ov02_datanascimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_datanascimento_mes"]:$this->ov02_datanascimento_mes);
        $this->ov02_datanascimento_ano = ($this->ov02_datanascimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_datanascimento_ano"]:$this->ov02_datanascimento_ano);
        if($this->ov02_datanascimento_dia != ""){
          $this->ov02_datanascimento = $this->ov02_datanascimento_ano."-".$this->ov02_datanascimento_mes."-".$this->ov02_datanascimento_dia;
        }
      }
      $this->ov02_sexo = ($this->ov02_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_sexo"]:$this->ov02_sexo);
    }else{
      $this->ov02_sequencial = ($this->ov02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"]:$this->ov02_sequencial);
      $this->ov02_seq = ($this->ov02_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ov02_seq"]:$this->ov02_seq);
    }
  }
  // funcao para inclusao
  function incluir ($ov02_sequencial,$ov02_seq){
    $this->atualizacampos();
    if($this->ov02_nome == null ){
      $this->erro_sql = " Campo Nome / Razão Social nao Informado.";
      $this->erro_campo = "ov02_nome";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ov02_ident == null ){
      $this->erro_sql = " Campo Identidade nao Informado.";
      $this->erro_campo = "ov02_ident";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ov02_numero == null ){
      $this->ov02_numero = "0";
    }
    if($this->ov02_situacaocidadao == null ){
      $this->erro_sql = " Campo Situacão nao Informado.";
      $this->erro_campo = "ov02_situacaocidadao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ov02_ativo == null ){
      $this->erro_sql = " Campo Ativo nao Informado.";
      $this->erro_campo = "ov02_ativo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ov02_data == null ){
      $this->erro_sql = " Campo Data nao Informado.";
      $this->erro_campo = "ov02_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->ov02_datanascimento == null ){
      $this->ov02_datanascimento = "null";
    }
    if($ov02_sequencial == "" || $ov02_sequencial == null ){
      $result = db_query("select nextval('cidadao_ov02_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: cidadao_ov02_sequencial_seq do campo: ov02_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->ov02_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from cidadao_ov02_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $ov02_sequencial)){
        $this->erro_sql = " Campo ov02_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->ov02_sequencial = $ov02_sequencial;
      }
    }
    if(($this->ov02_sequencial == null) || ($this->ov02_sequencial == "") ){
      $this->erro_sql = " Campo ov02_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if(($this->ov02_seq == null) || ($this->ov02_seq == "") ){
      $this->erro_sql = " Campo ov02_seq nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into cidadao(
                                       ov02_sequencial
                                      ,ov02_seq
                                      ,ov02_nome
                                      ,ov02_ident
                                      ,ov02_cnpjcpf
                                      ,ov02_endereco
                                      ,ov02_numero
                                      ,ov02_compl
                                      ,ov02_bairro
                                      ,ov02_munic
                                      ,ov02_uf
                                      ,ov02_cep
                                      ,ov02_situacaocidadao
                                      ,ov02_ativo
                                      ,ov02_data
                                      ,ov02_datanascimento
                                      ,ov02_sexo
                       )
                values (
                                $this->ov02_sequencial
                               ,$this->ov02_seq
                               ,'$this->ov02_nome'
                               ,'$this->ov02_ident'
                               ,'$this->ov02_cnpjcpf'
                               ,'$this->ov02_endereco'
                               ,$this->ov02_numero
                               ,'$this->ov02_compl'
                               ,'$this->ov02_bairro'
                               ,'$this->ov02_munic'
                               ,'$this->ov02_uf'
                               ,'$this->ov02_cep'
                               ,$this->ov02_situacaocidadao
                               ,'$this->ov02_ativo'
                               ,".($this->ov02_data == "null" || $this->ov02_data == ""?"null":"'".$this->ov02_data."'")."
                               ,".($this->ov02_datanascimento == "null" || $this->ov02_datanascimento == ""?"null":"'".$this->ov02_datanascimento."'")."
                               ,'$this->ov02_sexo'
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Cadastro do Cidadão ($this->ov02_sequencial."-".$this->ov02_seq) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Cadastro do Cidadão já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Cadastro do Cidadão ($this->ov02_sequencial."-".$this->ov02_seq) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->ov02_sequencial,$this->ov02_seq  ));
      if(($resaco!=false)||($this->numrows!=0)){

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,14736,'$this->ov02_sequencial','I')");
        $resac = db_query("insert into db_acountkey values($acount,14748,'$this->ov02_seq','I')");
        $resac = db_query("insert into db_acount values($acount,2595,14736,'','".AddSlashes(pg_result($resaco,0,'ov02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14748,'','".AddSlashes(pg_result($resaco,0,'ov02_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14746,'','".AddSlashes(pg_result($resaco,0,'ov02_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14740,'','".AddSlashes(pg_result($resaco,0,'ov02_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14745,'','".AddSlashes(pg_result($resaco,0,'ov02_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14744,'','".AddSlashes(pg_result($resaco,0,'ov02_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14743,'','".AddSlashes(pg_result($resaco,0,'ov02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14742,'','".AddSlashes(pg_result($resaco,0,'ov02_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14739,'','".AddSlashes(pg_result($resaco,0,'ov02_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14741,'','".AddSlashes(pg_result($resaco,0,'ov02_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14738,'','".AddSlashes(pg_result($resaco,0,'ov02_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14825,'','".AddSlashes(pg_result($resaco,0,'ov02_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14747,'','".AddSlashes(pg_result($resaco,0,'ov02_situacaocidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14737,'','".AddSlashes(pg_result($resaco,0,'ov02_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,14860,'','".AddSlashes(pg_result($resaco,0,'ov02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,19938,'','".AddSlashes(pg_result($resaco,0,'ov02_datanascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,2595,19939,'','".AddSlashes(pg_result($resaco,0,'ov02_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($ov02_sequencial=null,$ov02_seq=null) {
    $this->atualizacampos();
    $sql = " update cidadao set ";
    $virgula = "";
    if(trim($this->ov02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"])){
      $sql  .= $virgula." ov02_sequencial = $this->ov02_sequencial ";
      $virgula = ",";
      if(trim($this->ov02_sequencial) == null ){
        $this->erro_sql = " Campo Cidadão nao Informado.";
        $this->erro_campo = "ov02_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_seq"])){
      $sql  .= $virgula." ov02_seq = $this->ov02_seq ";
      $virgula = ",";
      if(trim($this->ov02_seq) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "ov02_seq";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_nome"])){
      $sql  .= $virgula." ov02_nome = '$this->ov02_nome' ";
      $virgula = ",";
      if(trim($this->ov02_nome) == null ){
        $this->erro_sql = " Campo Nome / Razão Social nao Informado.";
        $this->erro_campo = "ov02_nome";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_ident"])){
      $sql  .= $virgula." ov02_ident = '$this->ov02_ident' ";
      $virgula = ",";
      if(trim($this->ov02_ident) == null ){
        $this->erro_sql = " Campo Identidade nao Informado.";
        $this->erro_campo = "ov02_ident";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_cnpjcpf"])){
      $sql  .= $virgula." ov02_cnpjcpf = '$this->ov02_cnpjcpf' ";
      $virgula = ",";
    }
    if(trim($this->ov02_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_endereco"])){
      $sql  .= $virgula." ov02_endereco = '$this->ov02_endereco' ";
      $virgula = ",";
    }
    if(trim($this->ov02_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"])){
      if(trim($this->ov02_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"])){
        $this->ov02_numero = "0" ;
      }
      $sql  .= $virgula." ov02_numero = $this->ov02_numero ";
      $virgula = ",";
    }
    if(trim($this->ov02_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_compl"])){
      $sql  .= $virgula." ov02_compl = '$this->ov02_compl' ";
      $virgula = ",";
    }
    if(trim($this->ov02_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_bairro"])){
      $sql  .= $virgula." ov02_bairro = '$this->ov02_bairro' ";
      $virgula = ",";
    }
    if(trim($this->ov02_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_munic"])){
      $sql  .= $virgula." ov02_munic = '$this->ov02_munic' ";
      $virgula = ",";
    }
    if(trim($this->ov02_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_uf"])){
      $sql  .= $virgula." ov02_uf = '$this->ov02_uf' ";
      $virgula = ",";
    }
    if(trim($this->ov02_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_cep"])){
      $sql  .= $virgula." ov02_cep = '$this->ov02_cep' ";
      $virgula = ",";
    }
    if(trim($this->ov02_situacaocidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_situacaocidadao"])){
      $sql  .= $virgula." ov02_situacaocidadao = $this->ov02_situacaocidadao ";
      $virgula = ",";
      if(trim($this->ov02_situacaocidadao) == null ){
        $this->erro_sql = " Campo Situacão nao Informado.";
        $this->erro_campo = "ov02_situacaocidadao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_ativo"])){
      $sql  .= $virgula." ov02_ativo = '$this->ov02_ativo' ";
      $virgula = ",";
      if(trim($this->ov02_ativo) == null ){
        $this->erro_sql = " Campo Ativo nao Informado.";
        $this->erro_campo = "ov02_ativo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"] !="") ){
      $sql  .= $virgula." ov02_data = '$this->ov02_data' ";
      $virgula = ",";
      if(trim($this->ov02_data) == null ){
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "ov02_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"])){
        $sql  .= $virgula." ov02_data = null ";
        $virgula = ",";
        if(trim($this->ov02_data) == null ){
          $this->erro_sql = " Campo Data nao Informado.";
          $this->erro_campo = "ov02_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if( !empty($this->ov02_datanascimento) &&  $this->ov02_datanascimento != 'null'){
      $sql  .= $virgula." ov02_datanascimento = '$this->ov02_datanascimento' ";
      $virgula = ",";
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_datanascimento_dia"])){
        $sql  .= $virgula." ov02_datanascimento = null ";
        $virgula = ",";
      }
    }
    if(trim($this->ov02_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_sexo"])){
      $sql  .= $virgula." ov02_sexo = '$this->ov02_sexo' ";
      $virgula = ",";
    }
    $sql .= " where ";
    if($ov02_sequencial!=null){
      $sql .= " ov02_sequencial = $this->ov02_sequencial";
    }
    if($ov02_seq!=null){
      $sql .= " and  ov02_seq = $this->ov02_seq";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      $sSqlFile = $this->sql_query_file($this->ov02_sequencial,$this->ov02_seq);
      $resaco = $this->sql_record($sSqlFile);
      if($this->numrows>0){

        for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,14736,'$this->ov02_sequencial','A')");
          $resac = db_query("insert into db_acountkey values($acount,14748,'$this->ov02_seq','A')");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"]) || $this->ov02_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,2595,14736,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_sequencial'))."','$this->ov02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_seq"]) || $this->ov02_seq != "")
            $resac = db_query("insert into db_acount values($acount,2595,14748,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_seq'))."','$this->ov02_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_nome"]) || $this->ov02_nome != "")
            $resac = db_query("insert into db_acount values($acount,2595,14746,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_nome'))."','$this->ov02_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_ident"]) || $this->ov02_ident != "")
            $resac = db_query("insert into db_acount values($acount,2595,14740,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_ident'))."','$this->ov02_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_cnpjcpf"]) || $this->ov02_cnpjcpf != "")
            $resac = db_query("insert into db_acount values($acount,2595,14745,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_cnpjcpf'))."','$this->ov02_cnpjcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_endereco"]) || $this->ov02_endereco != "")
            $resac = db_query("insert into db_acount values($acount,2595,14744,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_endereco'))."','$this->ov02_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"]) || $this->ov02_numero != "")
            $resac = db_query("insert into db_acount values($acount,2595,14743,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_numero'))."','$this->ov02_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_compl"]) || $this->ov02_compl != "")
            $resac = db_query("insert into db_acount values($acount,2595,14742,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_compl'))."','$this->ov02_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_bairro"]) || $this->ov02_bairro != "")
            $resac = db_query("insert into db_acount values($acount,2595,14739,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_bairro'))."','$this->ov02_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_munic"]) || $this->ov02_munic != "")
            $resac = db_query("insert into db_acount values($acount,2595,14741,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_munic'))."','$this->ov02_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_uf"]) || $this->ov02_uf != "")
            $resac = db_query("insert into db_acount values($acount,2595,14738,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_uf'))."','$this->ov02_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_cep"]) || $this->ov02_cep != "")
            $resac = db_query("insert into db_acount values($acount,2595,14825,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_cep'))."','$this->ov02_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_situacaocidadao"]) || $this->ov02_situacaocidadao != "")
            $resac = db_query("insert into db_acount values($acount,2595,14747,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_situacaocidadao'))."','$this->ov02_situacaocidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_ativo"]) || $this->ov02_ativo != "")
            $resac = db_query("insert into db_acount values($acount,2595,14737,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_ativo'))."','$this->ov02_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_data"]) || $this->ov02_data != "")
            $resac = db_query("insert into db_acount values($acount,2595,14860,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_data'))."','$this->ov02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_datanascimento"]) || $this->ov02_datanascimento != "")
            $resac = db_query("insert into db_acount values($acount,2595,19938,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_datanascimento'))."','$this->ov02_datanascimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_sexo"]) || $this->ov02_sexo != "")
            $resac = db_query("insert into db_acount values($acount,2595,19939,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_sexo'))."','$this->ov02_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }

    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Cadastro do Cidadão nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Cadastro do Cidadão nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($ov02_sequencial=null,$ov02_seq=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

      if ($dbwhere==null || $dbwhere=="") {

        $resaco = $this->sql_record($this->sql_query_file($ov02_sequencial,$ov02_seq));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,14736,'$ov02_sequencial','E')");
          $resac  = db_query("insert into db_acountkey values($acount,14748,'$ov02_seq','E')");
          $resac  = db_query("insert into db_acount values($acount,2595,14736,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14748,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14746,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14740,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14745,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_cnpjcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14744,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14743,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14742,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14739,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14741,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14738,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14825,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14747,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_situacaocidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14737,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,14860,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,19938,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_datanascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,2595,19939,'','".AddSlashes(pg_result($resaco,$iresaco,'ov02_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
    }
    $sql = " delete from cidadao
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($ov02_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " ov02_seq = $ov02_seq ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Cadastro do Cidadão nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$ov02_sequencial."-".$ov02_seq;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Cadastro do Cidadão nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$ov02_sequencial."-".$ov02_seq;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$ov02_sequencial."-".$ov02_seq;
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
      $this->erro_sql   = "Record Vazio na Tabela:cidadao";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query_1 ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao ";
    $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = '" . $ov02_sequencial . "' ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
  function sql_query_file ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao ";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
  function sql_query_cidadaovinculos ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao ";
    $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
    $sql .= "      left 	join cidadaocgm			  on  cidadaocgm.ov03_cidadao = cidadao.ov02_sequencial and cidadaocgm.ov03_seq = cidadao.ov02_seq";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
  function sql_query_cidadaotiporetornocgm ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao as c ";
    $sql .= " left join cidadaocgm as ccgm on c.ov02_sequencial = ccgm.ov03_cidadao and c.ov02_seq = ccgm.ov03_seq ";
    $sql .= " left join cidadaotiporetorno as ctr on c.ov02_sequencial = ctr.ov04_cidadao and c.ov02_seq = ctr.ov04_seq ";
    $sql .= " left join cgm on ccgm.ov03_numcgm = cgm.z01_numcgm ";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where c.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
  function alterar_where ($ov02_sequencial=null,$ov02_seq=null,$where="") {
    $this->atualizacampos();

    $sql = " update cidadao set ";
    $virgula = "";
    if(trim($this->ov02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"])){
      $sql  .= $virgula." ov02_sequencial = $this->ov02_sequencial ";
      $virgula = ",";
      if(trim($this->ov02_sequencial) == null ){
        $this->erro_sql = " Campo Cidadão nao Informado.";
        $this->erro_campo = "ov02_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_seq"])){
      $sql  .= $virgula." ov02_seq = $this->ov02_seq ";
      $virgula = ",";
      if(trim($this->ov02_seq) == null ){
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "ov02_seq";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_nome"])){
      $sql  .= $virgula." ov02_nome = '$this->ov02_nome' ";
      $virgula = ",";
      if(trim($this->ov02_nome) == null ){
        $this->erro_sql = " Campo Nome / Razão Social nao Informado.";
        $this->erro_campo = "ov02_nome";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_ident"])){
      $sql  .= $virgula." ov02_ident = '$this->ov02_ident' ";
      $virgula = ",";
    }
    if(trim($this->ov02_cnpjcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_cnpjcpf"])){
      $sql  .= $virgula." ov02_cnpjcpf = '$this->ov02_cnpjcpf' ";
      $virgula = ",";
      if(trim($this->ov02_cnpjcpf) == null ){
        $this->erro_sql = " Campo CPF nao Informado.";
        $this->erro_campo = "ov02_cnpjcpf";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_endereco"])){
      $sql  .= $virgula." ov02_endereco = '$this->ov02_endereco' ";
      $virgula = ",";
    }
    if(trim($this->ov02_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"])){
      if(trim($this->ov02_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"])){
        $this->ov02_numero = "0" ;
      }
      $sql  .= $virgula." ov02_numero = $this->ov02_numero ";
      $virgula = ",";
    }
    if(trim($this->ov02_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_compl"])){
      $sql  .= $virgula." ov02_compl = '$this->ov02_compl' ";
      $virgula = ",";
    }
    if(trim($this->ov02_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_bairro"])){
      $sql  .= $virgula." ov02_bairro = '$this->ov02_bairro' ";
      $virgula = ",";
    }
    if(trim($this->ov02_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_munic"])){
      $sql  .= $virgula." ov02_munic = '$this->ov02_munic' ";
      $virgula = ",";
    }
    if(trim($this->ov02_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_uf"])){
      $sql  .= $virgula." ov02_uf = '$this->ov02_uf' ";
      $virgula = ",";
    }
    if(trim($this->ov02_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_cep"])){
      $sql  .= $virgula." ov02_cep = '$this->ov02_cep' ";
      $virgula = ",";
    }
    if(trim($this->ov02_situacaocidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_situacaocidadao"])){
      $sql  .= $virgula." ov02_situacaocidadao = $this->ov02_situacaocidadao ";
      $virgula = ",";
      if(trim($this->ov02_situacaocidadao) == null ){
        $this->erro_sql = " Campo Situacão nao Informado.";
        $this->erro_campo = "ov02_situacaocidadao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_ativo"])){
      $sql  .= $virgula." ov02_ativo = '$this->ov02_ativo' ";
      $virgula = ",";
      if(trim($this->ov02_ativo) == null ){
        $this->erro_sql = " Campo Ativo nao Informado.";
        $this->erro_campo = "ov02_ativo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->ov02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"] !="") ){
      $sql  .= $virgula." ov02_data = '$this->ov02_data' ";
      $virgula = ",";
      if(trim($this->ov02_data) == null ){
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "ov02_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_data_dia"])){
        $sql  .= $virgula." ov02_data = null ";
        $virgula = ",";
        if(trim($this->ov02_data) == null ){
          $this->erro_sql = " Campo Data nao Informado.";
          $this->erro_campo = "ov02_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    $sql .= " where ";
    if ($where != "") {
      $sql .= $where;
    }else{
      if($ov02_sequencial!=null){
        $sql .= " ov02_sequencial = $this->ov02_sequencial";
      }
      if($ov02_seq!=null){
        $sql .= " and  ov02_seq = $this->ov02_seq";
      }
    }
    $resaco = $this->sql_record($this->sql_query_file($this->ov02_sequencial,$this->ov02_seq, "*", null, $where));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,14736,'$this->ov02_sequencial','A')");
        $resac = db_query("insert into db_acountkey values($acount,14748,'$this->ov02_seq','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_sequencial"]) || $this->ov02_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2595,14736,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_sequencial'))."','$this->ov02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_seq"]) || $this->ov02_seq != "")
          $resac = db_query("insert into db_acount values($acount,2595,14748,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_seq'))."','$this->ov02_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_nome"]) || $this->ov02_nome != "")
          $resac = db_query("insert into db_acount values($acount,2595,14746,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_nome'))."','$this->ov02_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_ident"]) || $this->ov02_ident != "")
          $resac = db_query("insert into db_acount values($acount,2595,14740,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_ident'))."','$this->ov02_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_cnpjcpf"]) || $this->ov02_cnpjcpf != "")
          $resac = db_query("insert into db_acount values($acount,2595,14745,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_cnpjcpf'))."','$this->ov02_cnpjcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_endereco"]) || $this->ov02_endereco != "")
          $resac = db_query("insert into db_acount values($acount,2595,14744,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_endereco'))."','$this->ov02_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_numero"]) || $this->ov02_numero != "")
          $resac = db_query("insert into db_acount values($acount,2595,14743,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_numero'))."','$this->ov02_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_compl"]) || $this->ov02_compl != "")
          $resac = db_query("insert into db_acount values($acount,2595,14742,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_compl'))."','$this->ov02_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_bairro"]) || $this->ov02_bairro != "")
          $resac = db_query("insert into db_acount values($acount,2595,14739,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_bairro'))."','$this->ov02_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_munic"]) || $this->ov02_munic != "")
          $resac = db_query("insert into db_acount values($acount,2595,14741,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_munic'))."','$this->ov02_munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_uf"]) || $this->ov02_uf != "")
          $resac = db_query("insert into db_acount values($acount,2595,14738,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_uf'))."','$this->ov02_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_cep"]) || $this->ov02_cep != "")
          $resac = db_query("insert into db_acount values($acount,2595,14825,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_cep'))."','$this->ov02_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_situacaocidadao"]) || $this->ov02_situacaocidadao != "")
          $resac = db_query("insert into db_acount values($acount,2595,14747,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_situacaocidadao'))."','$this->ov02_situacaocidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_ativo"]) || $this->ov02_ativo != "")
          $resac = db_query("insert into db_acount values($acount,2595,14737,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_ativo'))."','$this->ov02_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["ov02_data"]) || $this->ov02_data != "")
          $resac = db_query("insert into db_acount values($acount,2595,14860,'".AddSlashes(pg_result($resaco,$conresaco,'ov02_data'))."','$this->ov02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Cadastro do Cidadão nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Cadastro do Cidadão nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->ov02_sequencial."-".$this->ov02_seq;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  function sql_query ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao ";
    $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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

  function sql_query_enderecoCidadao( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= " from cidadao as c ";
    $sql .= "      left join  cidadaotelefone as ct on c.ov02_sequencial = ct.ov07_cidadao                           ";
    $sql .= "                                      and c.ov02_seq = ct.ov07_seq  and ov07_principal is true          ";
    $sql .= "      left join  cidadaoemail as cm    on c.ov02_sequencial = cm.ov08_cidadao                           ";
    $sql .= "                                      and c.ov02_seq = cm.ov08_seq                                      ";
    $sql .= "                                      and ov08_principal is true                                        ";
    $sql .= "      left join cadenderestado on cadenderestado.db71_sigla = c.ov02_uf                                 ";
    $sql .= "      left join cadendermunicipio on cadendermunicipio.db72_cadenderestado = cadenderestado.db71_sequencial";
    $sql .= "                              and trim(cadendermunicipio.db72_descricao) = trim(c.ov02_munic)           ";
    $sql .= "      left join cadenderbairro on cadenderbairro.db73_cadendermunicipio = cadendermunicipio.db72_sequencial";
    $sql .= "                              and trim(cadenderbairro.db73_descricao) = trim(c.ov02_bairro)            ";
    $sql .= "      left join cadenderrua    on cadenderrua.db74_cadendermunicipio = cadendermunicipio.db72_sequencial";
    $sql .= "                              and trim(cadenderrua.db74_descricao) = trim(c.ov02_endereco)              ";
    $sql .= "      left join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_cadenderrua    = cadenderrua.db74_sequencial ";
    $sql .= "                                         and cadenderbairrocadenderrua.db87_cadenderbairro = cadenderbairro.db73_sequencial";

    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
  function sql_query_importaCidadao ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao as c
                    left join  cidadaotelefone as ct on c.ov02_sequencial = ct.ov07_cidadao
                                                    and c.ov02_seq = ct.ov07_seq  and ov07_principal is true
                    left join  cidadaoemail as cm    on c.ov02_sequencial = cm.ov08_cidadao
                                                    and c.ov02_seq = cm.ov08_seq
                                                    and ov08_principal is true  ";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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

  function sql_query_cadastrounico ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cidadao ";
    $sql .= "      left join cidadaocadastrounico on cidadao.ov02_sequencial =  cidadaocadastrounico.as02_cidadao";
    $sql .= "                                    and cidadao.ov02_seq        =  cidadaocadastrounico.as02_cidadao_seq";
    $sql2 = "";
    if ($dbwhere=="") {
      if ($ov02_sequencial != null ) {
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if ($ov02_seq!=null ) {
        if ($sql2!="") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

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

  function sql_query_cidadaotelefone ( $ov02_sequencial=null,$ov02_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadao ";
    $sql .= "left join cidadaotelefone on cidadao.ov02_sequencial = cidadaotelefone.ov07_cidadao ";
    $sql .= "                         and cidadao.ov02_seq        = cidadaotelefone.ov07_seq    ";
    $sql2 = "";
    if($dbwhere==""){
      if($ov02_sequencial!=null ){
        $sql2 .= " where cidadao.ov02_sequencial = $ov02_sequencial ";
      }
      if($ov02_seq!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " cidadao.ov02_seq = $ov02_seq ";
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
   * Retorna uma query com joins para a filiação cidadão
   *
   * @param string $ov02_sequencial
   * @param string $ov02_seq
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  public function sql_query_filiacao($ov02_sequencial = null , $ov02_seq = null, $campos = "*",
                                     $ordem = null, $dbwhere = "") {


    $sSqlFiliacao  = "select {$campos}";
    $sSqlFiliacao .=  " from cidadao filho";
    $sSqlFiliacao .=  "      left join cidadaofiliacao on ov29_cidadao         = filho.ov02_sequencial ";
    $sSqlFiliacao .=  "                               and ov29_cidadao_seq     = filho.ov02_seq";
    $sSqlFiliacao .=  "      left join cidadao pais    on pais.ov02_sequencial = ov29_cidadaovinculo";
    $sSqlFiliacao .=  "                               and pais.ov02_seq        = ov29_cidadaovinculo_seq ";

    $aWhere = array();
    if ($dbwhere == "") {

      if ($ov02_sequencial != null) {
        array_push($aWhere, "filho.ov02_sequencial = {$ov02_sequencial}");
      }
      if ($ov02_seq != null) {
        array_push($aWhere, " cidadao.ov02_seq = {$ov02_seq}");
      }
    } else if( $dbwhere != "" ) {
      array_push($aWhere, $dbwhere);
    }

    if (count($aWhere) > 0) {
      $sSqlFiliacao .= " where ".implode(" and ", $aWhere);
    }

    if (!empty($ordem)) {
      $sSqlFiliacao .= " order by {$ordem}";
    }

    return $sSqlFiliacao;
  }
}
?>