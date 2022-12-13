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
//CLASSE DA ENTIDADE empempenho
class cl_empempenho {
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
  var $e60_numemp = 0;
  var $e60_codemp = null;
  var $e60_anousu = 0;
  var $e60_coddot = 0;
  var $e60_numcgm = 0;
  var $e60_emiss_dia = null;
  var $e60_emiss_mes = null;
  var $e60_emiss_ano = null;
  var $e60_emiss = null;
  var $e60_vencim_dia = null;
  var $e60_vencim_mes = null;
  var $e60_vencim_ano = null;
  var $e60_vencim = null;
  var $e60_vlrorc = 0;
  var $e60_vlremp = 0;
  var $e60_vlrliq = 0;
  var $e60_vlrpag = 0;
  var $e60_vlranu = 0;
  var $e60_codtipo = 0;
  var $e60_resumo = null;
  var $e60_destin = null;
  var $e60_salant = 0;
  var $e60_instit = 0;
  var $e60_codcom = 0;
  var $e60_tipol = null;
  var $e60_numerol = null;
  var $migra_elemento = null;
  var $e60_concarpeculiar = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 e60_numemp = int4 = Número
                 e60_codemp = varchar(15) = Empenho
                 e60_anousu = int4 = Exercício
                 e60_coddot = int4 = Dotação
                 e60_numcgm = int4 = Numcgm
                 e60_emiss = date = Data Emissão
                 e60_vencim = date = Vencimento
                 e60_vlrorc = float8 = Valor Orçado
                 e60_vlremp = float8 = Valor Empenho
                 e60_vlrliq = float8 = Valor Liquidado
                 e60_vlrpag = float8 = Valor Pago
                 e60_vlranu = float8 = Valor Anulado
                 e60_codtipo = int4 = Tipo Empenho
                 e60_resumo = text = Observação
                 e60_destin = varchar(40) = Destino
                 e60_salant = float8 = Saldo anterior
                 e60_instit = int4 = codigo da instituicao
                 e60_codcom = int4 = Tipo de compra
                 e60_tipol = char(1) = Tipo da Licitacao
                 e60_numerol = char(8) = Numero Licitação
                 migra_elemento = varchar(12) = Migra Elemento
                 e60_concarpeculiar = varchar(100) = Caracteristica Peculiar
                 ";
  //funcao construtor da classe
  function cl_empempenho() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("empempenho");
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
      $this->e60_numemp = ($this->e60_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_numemp"]:$this->e60_numemp);
      $this->e60_codemp = ($this->e60_codemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_codemp"]:$this->e60_codemp);
      $this->e60_anousu = ($this->e60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_anousu"]:$this->e60_anousu);
      $this->e60_coddot = ($this->e60_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_coddot"]:$this->e60_coddot);
      $this->e60_numcgm = ($this->e60_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_numcgm"]:$this->e60_numcgm);
      if($this->e60_emiss == ""){
        $this->e60_emiss_dia = ($this->e60_emiss_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"]:$this->e60_emiss_dia);
        $this->e60_emiss_mes = ($this->e60_emiss_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_emiss_mes"]:$this->e60_emiss_mes);
        $this->e60_emiss_ano = ($this->e60_emiss_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_emiss_ano"]:$this->e60_emiss_ano);
        if($this->e60_emiss_dia != ""){
          $this->e60_emiss = $this->e60_emiss_ano."-".$this->e60_emiss_mes."-".$this->e60_emiss_dia;
        }
      }
      if($this->e60_vencim == ""){
        $this->e60_vencim_dia = ($this->e60_vencim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"]:$this->e60_vencim_dia);
        $this->e60_vencim_mes = ($this->e60_vencim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vencim_mes"]:$this->e60_vencim_mes);
        $this->e60_vencim_ano = ($this->e60_vencim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vencim_ano"]:$this->e60_vencim_ano);
        if($this->e60_vencim_dia != ""){
          $this->e60_vencim = $this->e60_vencim_ano."-".$this->e60_vencim_mes."-".$this->e60_vencim_dia;
        }
      }
      $this->e60_vlrorc = ($this->e60_vlrorc == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"]:$this->e60_vlrorc);
      $this->e60_vlremp = ($this->e60_vlremp == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vlremp"]:$this->e60_vlremp);
      $this->e60_vlrliq = ($this->e60_vlrliq == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"]:$this->e60_vlrliq);
      $this->e60_vlrpag = ($this->e60_vlrpag == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"]:$this->e60_vlrpag);
      $this->e60_vlranu = ($this->e60_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_vlranu"]:$this->e60_vlranu);
      $this->e60_codtipo = ($this->e60_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_codtipo"]:$this->e60_codtipo);
      $this->e60_resumo = ($this->e60_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_resumo"]:$this->e60_resumo);
      $this->e60_destin = ($this->e60_destin == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_destin"]:$this->e60_destin);
      $this->e60_salant = ($this->e60_salant == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_salant"]:$this->e60_salant);
      $this->e60_instit = ($this->e60_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_instit"]:$this->e60_instit);
      $this->e60_codcom = ($this->e60_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_codcom"]:$this->e60_codcom);
      $this->e60_tipol = ($this->e60_tipol == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_tipol"]:$this->e60_tipol);
      $this->e60_numerol = ($this->e60_numerol == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_numerol"]:$this->e60_numerol);
      $this->migra_elemento = ($this->migra_elemento == ""?@$GLOBALS["HTTP_POST_VARS"]["migra_elemento"]:$this->migra_elemento);
      $this->e60_concarpeculiar = ($this->e60_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"]:$this->e60_concarpeculiar);
    }else{
      $this->e60_numemp = ($this->e60_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e60_numemp"]:$this->e60_numemp);
    }
  }
  // funcao para inclusao
  function incluir ($e60_numemp){
    $this->atualizacampos();
    if($this->e60_codemp == null ){
      $this->erro_sql = " Campo Empenho nao Informado.";
      $this->erro_campo = "e60_codemp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_anousu == null ){
      $this->erro_sql = " Campo Exercício nao Informado.";
      $this->erro_campo = "e60_anousu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_coddot == null ){
      $this->erro_sql = " Campo Dotação nao Informado.";
      $this->erro_campo = "e60_coddot";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_numcgm == null ){
      $this->erro_sql = " Campo Numcgm nao Informado.";
      $this->erro_campo = "e60_numcgm";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_emiss == null ){
      $this->erro_sql = " Campo Data Emissão nao Informado.";
      $this->erro_campo = "e60_emiss_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vencim == null ){
      $this->erro_sql = " Campo Vencimento nao Informado.";
      $this->erro_campo = "e60_vencim_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vlrorc == null ){
      $this->erro_sql = " Campo Valor Orçado nao Informado.";
      $this->erro_campo = "e60_vlrorc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vlremp == null ){
      $this->erro_sql = " Campo Valor Empenho nao Informado.";
      $this->erro_campo = "e60_vlremp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vlrliq == null ){
      $this->erro_sql = " Campo Valor Liquidado nao Informado.";
      $this->erro_campo = "e60_vlrliq";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vlrpag == null ){
      $this->erro_sql = " Campo Valor Pago nao Informado.";
      $this->erro_campo = "e60_vlrpag";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_vlranu == null ){
      $this->erro_sql = " Campo Valor Anulado nao Informado.";
      $this->erro_campo = "e60_vlranu";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_codtipo == null ){
      $this->erro_sql = " Campo Tipo Empenho nao Informado.";
      $this->erro_campo = "e60_codtipo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_salant == null ){
      $this->e60_salant = "0";
    }
    if($this->e60_instit == null ){
      $this->erro_sql = " Campo codigo da instituicao nao Informado.";
      $this->erro_campo = "e60_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_codcom == null ){
      $this->erro_sql = " Campo Tipo de compra nao Informado.";
      $this->erro_campo = "e60_codcom";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->e60_concarpeculiar == null ){
      $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
      $this->erro_campo = "e60_concarpeculiar";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($e60_numemp == "" || $e60_numemp == null ){
      $result = db_query("select nextval('empempenho_e60_numemp_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: empempenho_e60_numemp_seq do campo: e60_numemp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->e60_numemp = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from empempenho_e60_numemp_seq");
      if(($result != false) && (pg_result($result,0,0) < $e60_numemp)){
        $this->erro_sql = " Campo e60_numemp maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->e60_numemp = $e60_numemp;
      }
    }
    if(($this->e60_numemp == null) || ($this->e60_numemp == "") ){
      $this->erro_sql = " Campo e60_numemp nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into empempenho(
                                       e60_numemp
                                      ,e60_codemp
                                      ,e60_anousu
                                      ,e60_coddot
                                      ,e60_numcgm
                                      ,e60_emiss
                                      ,e60_vencim
                                      ,e60_vlrorc
                                      ,e60_vlremp
                                      ,e60_vlrliq
                                      ,e60_vlrpag
                                      ,e60_vlranu
                                      ,e60_codtipo
                                      ,e60_resumo
                                      ,e60_destin
                                      ,e60_salant
                                      ,e60_instit
                                      ,e60_codcom
                                      ,e60_tipol
                                      ,e60_numerol
                                      ,migra_elemento
                                      ,e60_concarpeculiar
                       )
                values (
                                $this->e60_numemp
                               ,'$this->e60_codemp'
                               ,$this->e60_anousu
                               ,$this->e60_coddot
                               ,$this->e60_numcgm
                               ,".($this->e60_emiss == "null" || $this->e60_emiss == ""?"null":"'".$this->e60_emiss."'")."
                               ,".($this->e60_vencim == "null" || $this->e60_vencim == ""?"null":"'".$this->e60_vencim."'")."
                               ,$this->e60_vlrorc
                               ,$this->e60_vlremp
                               ,$this->e60_vlrliq
                               ,$this->e60_vlrpag
                               ,$this->e60_vlranu
                               ,$this->e60_codtipo
                               ,'$this->e60_resumo'
                               ,'$this->e60_destin'
                               ,$this->e60_salant
                               ,$this->e60_instit
                               ,$this->e60_codcom
                               ,'$this->e60_tipol'
                               ,'$this->e60_numerol'
                               ,'$this->migra_elemento'
                               ,'$this->e60_concarpeculiar'
                      )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Empenhos na prefeitura ($this->e60_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Empenhos na prefeitura já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Empenhos na prefeitura ($this->e60_numemp) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->e60_numemp;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->e60_numemp));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,5594,'$this->e60_numemp','I')");
      $resac = db_query("insert into db_acount values($acount,889,5594,'','".AddSlashes(pg_result($resaco,0,'e60_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5595,'','".AddSlashes(pg_result($resaco,0,'e60_codemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5596,'','".AddSlashes(pg_result($resaco,0,'e60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5597,'','".AddSlashes(pg_result($resaco,0,'e60_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5598,'','".AddSlashes(pg_result($resaco,0,'e60_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5599,'','".AddSlashes(pg_result($resaco,0,'e60_emiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5600,'','".AddSlashes(pg_result($resaco,0,'e60_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5656,'','".AddSlashes(pg_result($resaco,0,'e60_vlrorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5657,'','".AddSlashes(pg_result($resaco,0,'e60_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5658,'','".AddSlashes(pg_result($resaco,0,'e60_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5659,'','".AddSlashes(pg_result($resaco,0,'e60_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5660,'','".AddSlashes(pg_result($resaco,0,'e60_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5661,'','".AddSlashes(pg_result($resaco,0,'e60_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5662,'','".AddSlashes(pg_result($resaco,0,'e60_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5679,'','".AddSlashes(pg_result($resaco,0,'e60_destin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5684,'','".AddSlashes(pg_result($resaco,0,'e60_salant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5663,'','".AddSlashes(pg_result($resaco,0,'e60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5889,'','".AddSlashes(pg_result($resaco,0,'e60_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5890,'','".AddSlashes(pg_result($resaco,0,'e60_tipol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,5891,'','".AddSlashes(pg_result($resaco,0,'e60_numerol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,7383,'','".AddSlashes(pg_result($resaco,0,'migra_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,889,10817,'','".AddSlashes(pg_result($resaco,0,'e60_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($e60_numemp=null) {
    $this->atualizacampos();
    $sql = " update empempenho set ";
    $virgula = "";
    if(trim($this->e60_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numemp"])){
      $sql  .= $virgula." e60_numemp = $this->e60_numemp ";
      $virgula = ",";
      if(trim($this->e60_numemp) == null ){
        $this->erro_sql = " Campo Número nao Informado.";
        $this->erro_campo = "e60_numemp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_codemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codemp"])){
      $sql  .= $virgula." e60_codemp = '$this->e60_codemp' ";
      $virgula = ",";
      if(trim($this->e60_codemp) == null ){
        $this->erro_sql = " Campo Empenho nao Informado.";
        $this->erro_campo = "e60_codemp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_anousu"])){
      $sql  .= $virgula." e60_anousu = $this->e60_anousu ";
      $virgula = ",";
      if(trim($this->e60_anousu) == null ){
        $this->erro_sql = " Campo Exercício nao Informado.";
        $this->erro_campo = "e60_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_coddot"])){
      $sql  .= $virgula." e60_coddot = $this->e60_coddot ";
      $virgula = ",";
      if(trim($this->e60_coddot) == null ){
        $this->erro_sql = " Campo Dotação nao Informado.";
        $this->erro_campo = "e60_coddot";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numcgm"])){
      $sql  .= $virgula." e60_numcgm = $this->e60_numcgm ";
      $virgula = ",";
      if(trim($this->e60_numcgm) == null ){
        $this->erro_sql = " Campo Numcgm nao Informado.";
        $this->erro_campo = "e60_numcgm";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_emiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"] !="") ){
      $sql  .= $virgula." e60_emiss = '$this->e60_emiss' ";
      $virgula = ",";
      if(trim($this->e60_emiss) == null ){
        $this->erro_sql = " Campo Data Emissão nao Informado.";
        $this->erro_campo = "e60_emiss_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"])){
        $sql  .= $virgula." e60_emiss = null ";
        $virgula = ",";
        if(trim($this->e60_emiss) == null ){
          $this->erro_sql = " Campo Data Emissão nao Informado.";
          $this->erro_campo = "e60_emiss_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->e60_vencim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"] !="") ){
      $sql  .= $virgula." e60_vencim = '$this->e60_vencim' ";
      $virgula = ",";
      if(trim($this->e60_vencim) == null ){
        $this->erro_sql = " Campo Vencimento nao Informado.";
        $this->erro_campo = "e60_vencim_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"])){
        $sql  .= $virgula." e60_vencim = null ";
        $virgula = ",";
        if(trim($this->e60_vencim) == null ){
          $this->erro_sql = " Campo Vencimento nao Informado.";
          $this->erro_campo = "e60_vencim_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->e60_vlrorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"])){
      $sql  .= $virgula." e60_vlrorc = $this->e60_vlrorc ";
      $virgula = ",";
      if(trim($this->e60_vlrorc) == null ){
        $this->erro_sql = " Campo Valor Orçado nao Informado.";
        $this->erro_campo = "e60_vlrorc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_vlremp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlremp"])){
      $sql  .= $virgula." e60_vlremp = $this->e60_vlremp ";
      $virgula = ",";
      if(trim($this->e60_vlremp) == null ){
        $this->erro_sql = " Campo Valor Empenho nao Informado.";
        $this->erro_campo = "e60_vlremp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_vlrliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"])){
      $sql  .= $virgula." e60_vlrliq = $this->e60_vlrliq ";
      $virgula = ",";
      if(trim($this->e60_vlrliq) == null ){
        $this->erro_sql = " Campo Valor Liquidado nao Informado.";
        $this->erro_campo = "e60_vlrliq";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_vlrpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"])){
      $sql  .= $virgula." e60_vlrpag = $this->e60_vlrpag ";
      $virgula = ",";
      if(trim($this->e60_vlrpag) == null ){
        $this->erro_sql = " Campo Valor Pago nao Informado.";
        $this->erro_campo = "e60_vlrpag";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlranu"])){
      $sql  .= $virgula." e60_vlranu = $this->e60_vlranu ";
      $virgula = ",";
      if(trim($this->e60_vlranu) == null ){
        $this->erro_sql = " Campo Valor Anulado nao Informado.";
        $this->erro_campo = "e60_vlranu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codtipo"])){
      $sql  .= $virgula." e60_codtipo = $this->e60_codtipo ";
      $virgula = ",";
      if(trim($this->e60_codtipo) == null ){
        $this->erro_sql = " Campo Tipo Empenho nao Informado.";
        $this->erro_campo = "e60_codtipo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_resumo"])){
      $sql  .= $virgula." e60_resumo = '$this->e60_resumo' ";
      $virgula = ",";
    }
    if(trim($this->e60_destin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_destin"])){
      $sql  .= $virgula." e60_destin = '$this->e60_destin' ";
      $virgula = ",";
    }
    if(trim($this->e60_salant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"])){
      if(trim($this->e60_salant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"])){
        $this->e60_salant = "0" ;
      }
      $sql  .= $virgula." e60_salant = $this->e60_salant ";
      $virgula = ",";
    }
    if(trim($this->e60_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_instit"])){
      $sql  .= $virgula." e60_instit = $this->e60_instit ";
      $virgula = ",";
      if(trim($this->e60_instit) == null ){
        $this->erro_sql = " Campo codigo da instituicao nao Informado.";
        $this->erro_campo = "e60_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_codcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codcom"])){
      $sql  .= $virgula." e60_codcom = $this->e60_codcom ";
      $virgula = ",";
      if(trim($this->e60_codcom) == null ){
        $this->erro_sql = " Campo Tipo de compra nao Informado.";
        $this->erro_campo = "e60_codcom";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->e60_tipol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_tipol"])){
      $sql  .= $virgula." e60_tipol = '$this->e60_tipol' ";
      $virgula = ",";
    }
    if(trim($this->e60_numerol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numerol"])){
      $sql  .= $virgula." e60_numerol = '$this->e60_numerol' ";
      $virgula = ",";
    }
    if(trim($this->migra_elemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["migra_elemento"])){
      $sql  .= $virgula." migra_elemento = '$this->migra_elemento' ";
      $virgula = ",";
    }
    if(trim($this->e60_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"])){
      $sql  .= $virgula." e60_concarpeculiar = '$this->e60_concarpeculiar' ";
      $virgula = ",";
      if(trim($this->e60_concarpeculiar) == null ){
        $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
        $this->erro_campo = "e60_concarpeculiar";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($e60_numemp!=null){
      $sql .= " e60_numemp = $this->e60_numemp";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->e60_numemp));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5594,'$this->e60_numemp','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_numemp"]) || $this->e60_numemp != "")
          $resac = db_query("insert into db_acount values($acount,889,5594,'".AddSlashes(pg_result($resaco,$conresaco,'e60_numemp'))."','$this->e60_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_codemp"]) || $this->e60_codemp != "")
          $resac = db_query("insert into db_acount values($acount,889,5595,'".AddSlashes(pg_result($resaco,$conresaco,'e60_codemp'))."','$this->e60_codemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_anousu"]) || $this->e60_anousu != "")
          $resac = db_query("insert into db_acount values($acount,889,5596,'".AddSlashes(pg_result($resaco,$conresaco,'e60_anousu'))."','$this->e60_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_coddot"]) || $this->e60_coddot != "")
          $resac = db_query("insert into db_acount values($acount,889,5597,'".AddSlashes(pg_result($resaco,$conresaco,'e60_coddot'))."','$this->e60_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_numcgm"]) || $this->e60_numcgm != "")
          $resac = db_query("insert into db_acount values($acount,889,5598,'".AddSlashes(pg_result($resaco,$conresaco,'e60_numcgm'))."','$this->e60_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss"]) || $this->e60_emiss != "")
          $resac = db_query("insert into db_acount values($acount,889,5599,'".AddSlashes(pg_result($resaco,$conresaco,'e60_emiss'))."','$this->e60_emiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim"]) || $this->e60_vencim != "")
          $resac = db_query("insert into db_acount values($acount,889,5600,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vencim'))."','$this->e60_vencim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"]) || $this->e60_vlrorc != "")
          $resac = db_query("insert into db_acount values($acount,889,5656,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vlrorc'))."','$this->e60_vlrorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vlremp"]) || $this->e60_vlremp != "")
          $resac = db_query("insert into db_acount values($acount,889,5657,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vlremp'))."','$this->e60_vlremp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"]) || $this->e60_vlrliq != "")
          $resac = db_query("insert into db_acount values($acount,889,5658,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vlrliq'))."','$this->e60_vlrliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"]) || $this->e60_vlrpag != "")
          $resac = db_query("insert into db_acount values($acount,889,5659,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vlrpag'))."','$this->e60_vlrpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_vlranu"]) || $this->e60_vlranu != "")
          $resac = db_query("insert into db_acount values($acount,889,5660,'".AddSlashes(pg_result($resaco,$conresaco,'e60_vlranu'))."','$this->e60_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_codtipo"]) || $this->e60_codtipo != "")
          $resac = db_query("insert into db_acount values($acount,889,5661,'".AddSlashes(pg_result($resaco,$conresaco,'e60_codtipo'))."','$this->e60_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_resumo"]) || $this->e60_resumo != "")
          $resac = db_query("insert into db_acount values($acount,889,5662,'".AddSlashes(pg_result($resaco,$conresaco,'e60_resumo'))."','$this->e60_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_destin"]) || $this->e60_destin != "")
          $resac = db_query("insert into db_acount values($acount,889,5679,'".AddSlashes(pg_result($resaco,$conresaco,'e60_destin'))."','$this->e60_destin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"]) || $this->e60_salant != "")
          $resac = db_query("insert into db_acount values($acount,889,5684,'".AddSlashes(pg_result($resaco,$conresaco,'e60_salant'))."','$this->e60_salant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_instit"]) || $this->e60_instit != "")
          $resac = db_query("insert into db_acount values($acount,889,5663,'".AddSlashes(pg_result($resaco,$conresaco,'e60_instit'))."','$this->e60_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_codcom"]) || $this->e60_codcom != "")
          $resac = db_query("insert into db_acount values($acount,889,5889,'".AddSlashes(pg_result($resaco,$conresaco,'e60_codcom'))."','$this->e60_codcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_tipol"]) || $this->e60_tipol != "")
          $resac = db_query("insert into db_acount values($acount,889,5890,'".AddSlashes(pg_result($resaco,$conresaco,'e60_tipol'))."','$this->e60_tipol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_numerol"]) || $this->e60_numerol != "")
          $resac = db_query("insert into db_acount values($acount,889,5891,'".AddSlashes(pg_result($resaco,$conresaco,'e60_numerol'))."','$this->e60_numerol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["migra_elemento"]) || $this->migra_elemento != "")
          $resac = db_query("insert into db_acount values($acount,889,7383,'".AddSlashes(pg_result($resaco,$conresaco,'migra_elemento'))."','$this->migra_elemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"]) || $this->e60_concarpeculiar != "")
          $resac = db_query("insert into db_acount values($acount,889,10817,'".AddSlashes(pg_result($resaco,$conresaco,'e60_concarpeculiar'))."','$this->e60_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Empenhos na prefeitura nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->e60_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Empenhos na prefeitura nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->e60_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->e60_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($e60_numemp=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($e60_numemp));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,5594,'$e60_numemp','E')");
        $resac = db_query("insert into db_acount values($acount,889,5594,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5595,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_codemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5596,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5597,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5598,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5599,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_emiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5600,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vencim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5656,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vlrorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5657,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vlremp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5658,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5659,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vlrpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5660,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5661,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5662,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5679,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_destin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5684,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_salant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5663,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5889,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5890,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_tipol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,5891,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_numerol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,7383,'','".AddSlashes(pg_result($resaco,$iresaco,'migra_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,889,10817,'','".AddSlashes(pg_result($resaco,$iresaco,'e60_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from empempenho
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($e60_numemp != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " e60_numemp = $e60_numemp ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Empenhos na prefeitura nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$e60_numemp;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Empenhos na prefeitura nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$e60_numemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$e60_numemp;
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
      $this->erro_sql   = "Record Vazio na Tabela:empempenho";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_file ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_codord ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join matordemitem on m52_numemp          =  e60_numemp";
    $sql .= "      inner join matordem     on m51_codordem        = m52_codordem";
    $sql .= "      inner join cgm          on matordem.m51_numcgm = cgm.z01_numcgm ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_consulta ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      inner join db_permemp on 	db20_anousu  	= o58_anousu 	and";
    $sql .= "    				db20_orgao   	= o58_orgao 	and";
    $sql .= "    				db20_unidade 	= o58_unidade	and";
    $sql .= "    				db20_funcao  	= o58_funcao	and";
    $sql .= "    				db20_subfuncao 	= o58_subfuncao and";
    $sql .= "    				db20_programa	= o58_programa	and";
    $sql .= "    				db20_projativ	= o58_projativ	and";
    $sql .= "    				db20_codele	= o58_codele    and";
    $sql .= "    				db20_codigo	= o58_codigo";
    $sql .= "      inner join db_usupermemp on db21_codperm = db20_codperm and db21_id_usuario = " . db_getsession("DB_id_usuario");
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_doc ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= " from empempenho ";
    $sql .= "      left outer join conlancamemp on c75_numemp = e60_numemp  ";
    $sql .= "      left outer join conlancam on c70_codlan = conlancamemp.c75_codlan  ";
    $sql .= "      left outer join conlancamdoc on c71_codlan = conlancam.c70_codlan  ";
    $sql .= "      left outer join conhistdoc  on c53_coddoc = conlancamdoc.c71_coddoc  ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu
                                          and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      left join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      left join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      left join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      left join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      left join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      left join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu
                                         and  orcprograma.o54_programa = orcdotacao.o58_programa";
    $sql .= "      left join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele";
    $sql .= "      left join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu
                                         and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
    $sql .= "      left join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu
                                         and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      left join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu
                                           and  orcunidade.o41_orgao = orcdotacao.o58_orgao
                                         and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
    $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
    $sql2 = "";

    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_empnome($e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join empelemento  on  e64_numemp = e60_numemp";
    $sql .= "      left join empempenholiberado  on  e22_numemp = e60_numemp";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
   * @param  string $sCampos
   * @param  string $sOrdem
   * @param  string $sWhere
   * @return string
   */
  function sql_query_empresto($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = " select {$sCampos}                                    ";
    $sSql .= "  from empempenho                                     ";
    $sSql .= "       inner join empresto on e60_numemp = e91_numemp ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }
    return $sSql;
  }

  function sql_query_encerramento_empresto($sCampos = "*", $sOrdem = null, $sWhere = "") {

    $sSql  = " select {$sCampos}                                    ";
    $sSql .= "  from empempenho                                     ";
    $sSql .= "       left join empresto on e60_numemp = e91_numemp ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }
    return $sSql;
  }

  function sql_query_hist ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot
     = empempenho.e60_coddot";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa
     = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ
     = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao
     = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao
     = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
    $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
    $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_impconsulta ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join empempitem  on  empempitem.e62_numemp = empempenho.e60_numemp";
    $sql .= "      inner join pcmater     on  pcmater.pc01_codmater = empempitem.e62_item";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_itemmaterial($pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join empempitem on e62_numemp = e60_numemp   ";
    $sql .= "      inner join pcmater on pcmater.pc01_codmater = empempitem.e62_item ";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      left join pagordem  on  pagordem.e50_numemp = empempenho.e60_numemp";

    $sql2 = "";
    if($dbwhere==""){
      if($pc01_codmater!=null ){
        $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
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
  function sql_query_itensadquiridos($pc01_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join empempitem on e62_numemp = e60_numemp   ";
    $sql .= "      inner join pcmater on pcmater.pc01_codmater = empempitem.e62_item ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      inner join pagordem  on  pagordem.e50_numemp = empempenho.e60_numemp";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";

    $sql2 = "";
    if($dbwhere==""){
      if($pc01_codmater!=null ){
        $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
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
  function sql_query_liberarempenho( $e60_numemp=null,$campos="*",$ordem="",$dbwhere="" ){

    if (isset($ordem) && $ordem != "") {
      $ordem = " order by {$ordem}";
    }

    $sSql  = "   select {$campos}                                                                                    ";
    $sSql .= "     from empempenho                                                                                   ";
    $sSql .= "          left  join empempenholiberado on e22_numemp = e60_numemp                                     ";
    $sSql .= "          inner join cgm                on z01_numcgm = e60_numcgm                                     ";
    $sSql .= "    where {$dbwhere} {$ordem}                                                                          ";

    return $sSql;
  }
  function sql_query_relatorio ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot
     = empempenho.e60_coddot";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu
                             and  orcprograma.o54_programa     = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu ";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ
     = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao
     = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao
     = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
    $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
    $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
    $sql .= "   /* pesquisa no relatorio de empenhos por ítem */

                 inner join empempitem on e62_numemp = e60_numemp

                  ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_resto ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "      inner join empempenho on e91_numemp = e60_numemp";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot
     = empempenho.e60_coddot";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa
     = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ
     = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao
     = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao
     = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
    $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
    $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
    $sql2 = "";

    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empresto.e91_anousu = ".db_getsession("DB_anousu")." and empresto.e91_numemp = $e60_numemp ";
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
  function sql_query_saldo($e60_numemp,$e64_codele=0){
    $sql = "select
                    		substr(saldo,16,12)::float8 as e60_vlremp,
                    		substr(saldo,29,12)::float8 as e60_vlranu,
                    		substr(saldo,42,12)::float8 as e60_vlrliq,
                    		substr(saldo,55,12)::float8 as e60_vlrpag,
                	    	substr(saldo,68,12)::float8 as vlr_proc,
              	        	substr(saldo,81,12)::float8 as vlr_nproc
                	  from ( select fc_empsaldo(".$e60_numemp.",".$e64_codele.") as saldo ) as x  ";

    return $sql;
  }
  function sql_query_buscaempenhos ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " FROM empempenho                                                                ";
    $sql .= "   INNER JOIN db_config   ON db_config.codigo         = empempenho.e60_instit   ";
    $sql .= "   INNER JOIN orcdotacao  ON orcdotacao.o58_anousu    = empempenho.e60_anousu   ";
    $sql .= "                         AND orcdotacao.o58_coddot    = empempenho.e60_coddot   ";
    $sql .= "   INNER JOIN cgm         ON cgm.z01_numcgm           = empempenho.e60_numcgm   ";
    $sql .= "   INNER JOIN orcprojativ ON orcprojativ.o55_anousu   = orcdotacao.o58_anousu   ";
    $sql .= "                         AND orcprojativ.o55_projativ = orcdotacao.o58_projativ ";
    $sql .= "   INNER JOIN orcelemento ON orcelemento.o56_codele   = orcdotacao.o58_codele   ";
    $sql .= "                         AND orcelemento.o56_anousu   = orcdotacao.o58_anousu   ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_buscaestornos ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " FROM empempenho                                                           ";
    $sql .= "   INNER JOIN db_config   ON db_config.codigo      = empempenho.e60_instit ";
    $sql .= "   INNER JOIN empanulado  ON empanulado.e94_numemp = empempenho.e60_numemp ";
    $sql .= "   INNER JOIN orcdotacao  ON orcdotacao.o58_anousu = empempenho.e60_anousu ";
    $sql .= "                         AND orcdotacao.o58_coddot = empempenho.e60_coddot ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
  function sql_query_buscaliquidacoes ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " FROM empempenho                                                                ";
    $sql .= "   INNER JOIN conlancamemp ON conlancamemp.c75_numemp = empempenho.e60_numemp   ";
    $sql .= "   INNER JOIN conlancam    ON conlancam.c70_codlan    = conlancamemp.c75_codlan ";
    $sql .= "   INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamemp.c75_codlan ";
    $sql .= "   INNER JOIN conhistdoc   ON conhistdoc.c53_coddoc   = conlancamdoc.c71_coddoc ";
    $sql .= "   INNER JOIN orcdotacao   ON orcdotacao.o58_anousu   = empempenho.e60_anousu   ";
    $sql .= "                          AND orcdotacao.o58_coddot   = empempenho.e60_coddot   ";
    $sql .= "   INNER JOIN db_config    ON db_config.codigo        = empempenho.e60_instit   ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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
   * metodo para consultar empenhos vinculados a contrados
   */

  function sql_query_empenhocontrato($e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " FROM empempenho                                                                            ";
    $sql .= " left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp            ";
    $sql .= " left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori          ";
    $sql .= " left join empempenhocontrato on  empempenho.e60_numemp = empempenhocontrato.e100_numemp    ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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


  function sql_query_itens_consulta_empenho($e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "  from fc_saldoitensempenho({$e60_numemp}) ";
    $sql .= "       inner join empempitem                         on ricoditem                          = e62_sequencial ";
    $sql .= "       inner join empempenho                         on e62_numemp                         = e60_numemp ";
    $sql .= "       inner join orcelemento                        on e62_codele                         = o56_codele ";
    $sql .= "                                                    and e60_anousu                         = o56_anousu ";
    $sql .= "       left join empempaut                           on empempaut.e61_numemp               = empempenho.e60_numemp ";
    $sql .= "       left join empautitem                          on empautitem.e55_autori              = empempaut.e61_autori ";
    $sql .= "                                                    and empautitem.e55_sequen              = empempitem.e62_sequen";
    $sql .= "       left join empautitempcprocitem                on empautitempcprocitem.e73_autori    = empautitem.e55_autori ";
    $sql .= "                                                    and empautitempcprocitem.e73_sequen    = empautitem.e55_sequen ";
    $sql .= "       left join pcprocitem                          on pcprocitem.pc81_codprocitem        = empautitempcprocitem.e73_pcprocitem ";
    $sql .= "       left join solicitem                           on solicitem.pc11_codigo              = pcprocitem.pc81_solicitem ";
    $sql .= "       left join liclicitem                          on liclicitem.l21_codpcprocitem       = empautitempcprocitem.e73_pcprocitem ";
    $sql .= "       left join pcorcamitemlic                      on pcorcamitemlic.pc26_liclicitem     = liclicitem.l21_codigo ";
    $sql .= "       left join pcorcamjulg                         on pcorcamjulg.pc24_orcamitem         = pcorcamitemlic.pc26_orcamitem ";
    $sql .= "                                                    and pcorcamjulg.pc24_pontuacao         = 1 ";
    $sql .= "       left join pcorcamval                          on pcorcamval.pc23_orcamitem          = pcorcamjulg.pc24_orcamitem ";
    $sql .= "                                                    and pcorcamval.pc23_orcamforne         = pcorcamjulg.pc24_orcamforne ";
    $sql .= "       left join solicitemvinculo                    on solicitemvinculo.pc55_solicitemfilho = solicitem.pc11_codigo ";
    $sql .= "       left join solicitem       as solicitempai     on solicitempai.pc11_codigo             = solicitemvinculo.pc55_solicitempai ";
    $sql .= "       left join pcprocitem      as pcprocitempai    on pcprocitempai.pc81_solicitem         = solicitempai.pc11_codigo ";
    $sql .= "       left join liclicitem      as liclicitempai    on liclicitempai.l21_codpcprocitem      = pcprocitempai.pc81_codprocitem ";
    $sql .= "       left join pcorcamitemlic as pcorcamitemlicpai on pcorcamitemlicpai.pc26_liclicitem    = liclicitempai.l21_codigo ";
    $sql .= "       left join pcorcamjulg    as pcorcamjulgpai    on pcorcamjulgpai.pc24_orcamitem      = pcorcamitemlicpai.pc26_orcamitem ";
    $sql .= "                                                    and pcorcamjulgpai.pc24_pontuacao      = 1 ";
    $sql .= "       left join pcorcamval     as pcorcamvalpai     on pcorcamvalpai.pc23_orcamitem       = pcorcamjulgpai.pc24_orcamitem ";
    $sql .= "                                                    and pcorcamvalpai.pc23_orcamforne      = pcorcamjulgpai.pc24_orcamforne ";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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

  function sql_queryMovimentacaoPatrimonial ( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
    $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
    $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
    $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
    $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
    $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
    $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
    $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
    $sql .= "      inner join conlancamemp on empempenho.e60_numemp = conlancamemp.c75_numemp";
    $sql .= "      inner join conlancam on conlancamemp.c75_codlan = conlancam.c70_codlan";
    $sql .= "      inner join conlancamdoc on conlancam.c70_codlan = conlancamdoc.c71_codlan";
    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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


  function sql_queryProcessoAdministrativo( $e60_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from empempenho ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
    $sql .= "        inner join empempaut   on e61_numemp = e60_numemp ";
    $sql .= "        inner join empautorizaprocesso on e61_autori = e150_empautoriza ";

    $sql2 = "";
    if($dbwhere==""){
      if($e60_numemp!=null ){
        $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
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


  public function sql_query_cota_mensal($e60_numemp = null,$campos="*",$ordem=null,$dbwhere=""){

    $sql  = "select {$campos} ";
    $sql .= " from empempenho ";
    $sql .= "     inner join empenhocotamensal on empenhocotamensal.e05_numemp = empempenho.e60_numemp ";
    $sql .= "     inner join orcdotacao        on orcdotacao.o58_coddot = empempenho.e60_coddot ";
    $sql .= "                                 and orcdotacao.o58_anousu = empempenho.e60_anousu ";

    if (empty($dbwhere) && !empty($e60_numemp)) {
      $sql .= " where empempenho.e60_numemp = {$e60_numemp} ";
    } else if (!empty($dbwhere)) {
      $sql .= " where {$dbwhere}";
    }

    if(!empty($ordem)){
      $sql .= " order by {$ordem} ";
    }
    return $sql;
  }

  public function sql_query_classificacao_credor($sCampos = "*", $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from empempenho ";
    $sSql .= "        inner join cgm on z01_numcgm = e60_numcgm";
    $sSql .= "        left join classificacaocredoresempenho on cc31_empempenho = e60_numemp";
    $sSql .= "        left join classificacaocredores on cc31_classificacaocredores = cc30_codigo";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere}";
    }
    return $sSql;
  }


  public function sql_query_empenho_classificacao_nota($sCampos = "*", $sWhere = null) {

    $sSql  = " select {$sCampos} ";
    $sSql .= "   from empempenho ";
    $sSql .= "        inner join orcdotacao   on (e60_anousu, e60_coddot) = (o58_anousu, o58_coddot) ";
    $sSql .= "        inner join empnota      on empnota.e69_numemp = empempenho.e60_numemp ";
    $sSql .= "        inner join pagordemnota on pagordemnota.e71_codnota = empnota.e69_codnota ";
    $sSql .= "        inner join pagordemele  on pagordemele.e53_codord = pagordemnota.e71_codord ";
    $sSql .= "        inner join pagordem     on pagordem.e50_codord    = pagordemele.e53_codord ";
    $sSql .= "        inner join cgm          on cgm.z01_numcgm     = empempenho.e60_numcgm ";
    $sSql .= "        inner join classificacaocredoresempenho on classificacaocredoresempenho.cc31_empempenho = empempenho.e60_numemp ";
    $sSql .= "        inner join classificacaocredores        on cc31_classificacaocredores = cc30_codigo ";
    $sSql .= "        inner join empord on empord.e82_codord = pagordem.e50_codord ";
    $sSql .= "        inner join empagemov on empagemov.e81_codmov = empord.e82_codmov ";
    $sSql .= "        left  join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov ";
    $sSql .= "        left  join corrente on corempagemov.k12_id = corrente.k12_id and corempagemov.k12_data = corrente.k12_data and corempagemov.k12_autent = corrente.k12_autent";
    $sSql .= "        left  join empagemovjustificativa on empagemovjustificativa.e09_codmov = empagemov.e81_codmov ";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere}";
    }
    return $sSql;
  }
}