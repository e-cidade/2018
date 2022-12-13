<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE inventario
class cl_inventario {
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
  var $t75_sequencial = 0;
  var $t75_dataabertura_dia = null;
  var $t75_dataabertura_mes = null;
  var $t75_dataabertura_ano = null;
  var $t75_dataabertura = null;
  var $t75_periodoinicial_dia = null;
  var $t75_periodoinicial_mes = null;
  var $t75_periodoinicial_ano = null;
  var $t75_periodoinicial = null;
  var $t75_periodofinal_dia = null;
  var $t75_periodofinal_mes = null;
  var $t75_periodofinal_ano = null;
  var $t75_periodofinal = null;
  var $t75_exercicio = 0;
  var $t75_processo = 0;
  var $t75_acordocomissao = 0;
  var $t75_observacao = null;
  var $t75_situacao = 0;
  var $t75_db_depart = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
  t75_sequencial = int4 = Sequencia do Inventário
  t75_dataabertura = date = Data de Abertura
  t75_periodoinicial = date = Periodo Inicial
  t75_periodofinal = date = Período Final
  t75_exercicio = int4 = Exercício
  t75_processo = int4 = Processo
  t75_acordocomissao = int4 = Comissão
  t75_observacao = text = Observação
  t75_situacao = int4 = Situação
  t75_db_depart = int4 = Departamento
  ";
  //funcao construtor da classe
  function cl_inventario() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("inventario");
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
      $this->t75_sequencial = ($this->t75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_sequencial"]:$this->t75_sequencial);
      if($this->t75_dataabertura == ""){
        $this->t75_dataabertura_dia = ($this->t75_dataabertura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_dia"]:$this->t75_dataabertura_dia);
        $this->t75_dataabertura_mes = ($this->t75_dataabertura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_mes"]:$this->t75_dataabertura_mes);
        $this->t75_dataabertura_ano = ($this->t75_dataabertura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_ano"]:$this->t75_dataabertura_ano);
        if($this->t75_dataabertura_dia != ""){
          $this->t75_dataabertura = $this->t75_dataabertura_ano."-".$this->t75_dataabertura_mes."-".$this->t75_dataabertura_dia;
        }
      }
      if($this->t75_periodoinicial == ""){
        $this->t75_periodoinicial_dia = ($this->t75_periodoinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_dia"]:$this->t75_periodoinicial_dia);
        $this->t75_periodoinicial_mes = ($this->t75_periodoinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_mes"]:$this->t75_periodoinicial_mes);
        $this->t75_periodoinicial_ano = ($this->t75_periodoinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_ano"]:$this->t75_periodoinicial_ano);
        if($this->t75_periodoinicial_dia != ""){
          $this->t75_periodoinicial = $this->t75_periodoinicial_ano."-".$this->t75_periodoinicial_mes."-".$this->t75_periodoinicial_dia;
        }
      }
      if($this->t75_periodofinal == ""){
        $this->t75_periodofinal_dia = ($this->t75_periodofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_dia"]:$this->t75_periodofinal_dia);
        $this->t75_periodofinal_mes = ($this->t75_periodofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_mes"]:$this->t75_periodofinal_mes);
        $this->t75_periodofinal_ano = ($this->t75_periodofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_ano"]:$this->t75_periodofinal_ano);
        if($this->t75_periodofinal_dia != ""){
          $this->t75_periodofinal = $this->t75_periodofinal_ano."-".$this->t75_periodofinal_mes."-".$this->t75_periodofinal_dia;
        }
      }
      $this->t75_exercicio = ($this->t75_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_exercicio"]:$this->t75_exercicio);
      $this->t75_processo = ($this->t75_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_processo"]:$this->t75_processo);
      $this->t75_acordocomissao = ($this->t75_acordocomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_acordocomissao"]:$this->t75_acordocomissao);
      $this->t75_observacao = ($this->t75_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_observacao"]:$this->t75_observacao);
      $this->t75_situacao = ($this->t75_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_situacao"]:$this->t75_situacao);
      $this->t75_db_depart = ($this->t75_db_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_db_depart"]:$this->t75_db_depart);
    }else{
      $this->t75_sequencial = ($this->t75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t75_sequencial"]:$this->t75_sequencial);
    }
  }
  // funcao para inclusao
  function incluir ($t75_sequencial){
    $this->atualizacampos();
    if($this->t75_dataabertura == null ){
      $this->erro_sql = " Campo Data de Abertura nao Informado.";
      $this->erro_campo = "t75_dataabertura_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t75_periodoinicial == null ){
      $this->erro_sql = " Campo Periodo Inicial nao Informado.";
      $this->erro_campo = "t75_periodoinicial_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t75_periodofinal == null ){
      $this->erro_sql = " Campo Período Final nao Informado.";
      $this->erro_campo = "t75_periodofinal_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t75_exercicio == null ){
      $this->erro_sql = " Campo Exercício nao Informado.";
      $this->erro_campo = "t75_exercicio";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t75_processo == null ){
      $this->t75_processo = "null";
    }
    if($this->t75_acordocomissao == null ){
      $this->erro_sql = " Campo Comissão nao Informado.";
      $this->erro_campo = "t75_acordocomissao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->t75_situacao == null ){
      $this->t75_situacao = "0";
    }
    if($this->t75_db_depart == null ){
      $this->erro_sql = " Campo Departamento nao Informado.";
      $this->erro_campo = "t75_db_depart";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($t75_sequencial == "" || $t75_sequencial == null ){
      $result = db_query("select nextval('inventario_t75_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: inventario_t75_sequencial_seq do campo: t75_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->t75_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from inventario_t75_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $t75_sequencial)){
        $this->erro_sql = " Campo t75_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->t75_sequencial = $t75_sequencial;
      }
    }
    if(($this->t75_sequencial == null) || ($this->t75_sequencial == "") ){
      $this->erro_sql = " Campo t75_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into inventario(
    t75_sequencial
    ,t75_dataabertura
    ,t75_periodoinicial
    ,t75_periodofinal
    ,t75_exercicio
    ,t75_processo
    ,t75_acordocomissao
    ,t75_observacao
    ,t75_situacao
    ,t75_db_depart
    )
    values (
    $this->t75_sequencial
    ,".($this->t75_dataabertura == "null" || $this->t75_dataabertura == ""?"null":"'".$this->t75_dataabertura."'")."
    ,".($this->t75_periodoinicial == "null" || $this->t75_periodoinicial == ""?"null":"'".$this->t75_periodoinicial."'")."
    ,".($this->t75_periodofinal == "null" || $this->t75_periodofinal == ""?"null":"'".$this->t75_periodofinal."'")."
    ,$this->t75_exercicio
    ,$this->t75_processo
    ,$this->t75_acordocomissao
    ,'$this->t75_observacao'
    ,$this->t75_situacao
    ,$this->t75_db_depart
    )";
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Inventario ($this->t75_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Inventario já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Inventario ($this->t75_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : ".$this->t75_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->t75_sequencial));
    if(($resaco!=false)||($this->numrows!=0)){
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac,0,0);
      $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
      $resac = db_query("insert into db_acountkey values($acount,19316,'$this->t75_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,3435,19316,'','".AddSlashes(pg_result($resaco,0,'t75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19317,'','".AddSlashes(pg_result($resaco,0,'t75_dataabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19318,'','".AddSlashes(pg_result($resaco,0,'t75_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19319,'','".AddSlashes(pg_result($resaco,0,'t75_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19320,'','".AddSlashes(pg_result($resaco,0,'t75_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19321,'','".AddSlashes(pg_result($resaco,0,'t75_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19322,'','".AddSlashes(pg_result($resaco,0,'t75_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19323,'','".AddSlashes(pg_result($resaco,0,'t75_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19324,'','".AddSlashes(pg_result($resaco,0,'t75_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      $resac = db_query("insert into db_acount values($acount,3435,19325,'','".AddSlashes(pg_result($resaco,0,'t75_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($t75_sequencial=null) {
    $this->atualizacampos();
    $sql = " update inventario set ";
    $virgula = "";
    if(trim($this->t75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_sequencial"])){
      $sql  .= $virgula." t75_sequencial = $this->t75_sequencial ";
      $virgula = ",";
      if(trim($this->t75_sequencial) == null ){
        $this->erro_sql = " Campo Sequencia do Inventário nao Informado.";
        $this->erro_campo = "t75_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->t75_dataabertura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_dia"] !="") ){
      $sql  .= $virgula." t75_dataabertura = '$this->t75_dataabertura' ";
      $virgula = ",";
      if(trim($this->t75_dataabertura) == null ){
        $this->erro_sql = " Campo Data de Abertura nao Informado.";
        $this->erro_campo = "t75_dataabertura_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["t75_dataabertura_dia"])){
        $sql  .= $virgula." t75_dataabertura = null ";
        $virgula = ",";
        if(trim($this->t75_dataabertura) == null ){
          $this->erro_sql = " Campo Data de Abertura nao Informado.";
          $this->erro_campo = "t75_dataabertura_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->t75_periodoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_dia"] !="") ){
      $sql  .= $virgula." t75_periodoinicial = '$this->t75_periodoinicial' ";
      $virgula = ",";
      if(trim($this->t75_periodoinicial) == null ){
        $this->erro_sql = " Campo Periodo Inicial nao Informado.";
        $this->erro_campo = "t75_periodoinicial_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial_dia"])){
        $sql  .= $virgula." t75_periodoinicial = null ";
        $virgula = ",";
        if(trim($this->t75_periodoinicial) == null ){
          $this->erro_sql = " Campo Periodo Inicial nao Informado.";
          $this->erro_campo = "t75_periodoinicial_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->t75_periodofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_dia"] !="") ){
      $sql  .= $virgula." t75_periodofinal = '$this->t75_periodofinal' ";
      $virgula = ",";
      if(trim($this->t75_periodofinal) == null ){
        $this->erro_sql = " Campo Período Final nao Informado.";
        $this->erro_campo = "t75_periodofinal_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if(isset($GLOBALS["HTTP_POST_VARS"]["t75_periodofinal_dia"])){
        $sql  .= $virgula." t75_periodofinal = null ";
        $virgula = ",";
        if(trim($this->t75_periodofinal) == null ){
          $this->erro_sql = " Campo Período Final nao Informado.";
          $this->erro_campo = "t75_periodofinal_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if(trim($this->t75_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_exercicio"])){
      $sql  .= $virgula." t75_exercicio = $this->t75_exercicio ";
      $virgula = ",";
      if(trim($this->t75_exercicio) == null ){
        $this->erro_sql = " Campo Exercício nao Informado.";
        $this->erro_campo = "t75_exercicio";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->t75_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_processo"])){
      if(trim($this->t75_processo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t75_processo"])){
        $this->t75_processo = "0" ;
      }
      $sql  .= $virgula." t75_processo = $this->t75_processo ";
      $virgula = ",";
    }
    if(trim($this->t75_acordocomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_acordocomissao"])){
      $sql  .= $virgula." t75_acordocomissao = $this->t75_acordocomissao ";
      $virgula = ",";
      if(trim($this->t75_acordocomissao) == null ){
        $this->erro_sql = " Campo Comissão nao Informado.";
        $this->erro_campo = "t75_acordocomissao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->t75_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_observacao"])){
      $sql  .= $virgula." t75_observacao = '$this->t75_observacao' ";
      $virgula = ",";
    }
    if(trim($this->t75_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_situacao"])){
      if(trim($this->t75_situacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t75_situacao"])){
        $this->t75_situacao = "0" ;
      }
      $sql  .= $virgula." t75_situacao = $this->t75_situacao ";
      $virgula = ",";
    }
    if(trim($this->t75_db_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t75_db_depart"])){
      $sql  .= $virgula." t75_db_depart = $this->t75_db_depart ";
      $virgula = ",";
      if(trim($this->t75_db_depart) == null ){
        $this->erro_sql = " Campo Departamento nao Informado.";
        $this->erro_campo = "t75_db_depart";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($t75_sequencial!=null){
      $sql .= " t75_sequencial = $this->t75_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->t75_sequencial));
    if($this->numrows>0){
      for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19316,'$this->t75_sequencial','A')");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_sequencial"]) || $this->t75_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,3435,19316,'".AddSlashes(pg_result($resaco,$conresaco,'t75_sequencial'))."','$this->t75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_dataabertura"]) || $this->t75_dataabertura != "")
          $resac = db_query("insert into db_acount values($acount,3435,19317,'".AddSlashes(pg_result($resaco,$conresaco,'t75_dataabertura'))."','$this->t75_dataabertura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_periodoinicial"]) || $this->t75_periodoinicial != "")
          $resac = db_query("insert into db_acount values($acount,3435,19318,'".AddSlashes(pg_result($resaco,$conresaco,'t75_periodoinicial'))."','$this->t75_periodoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_periodofinal"]) || $this->t75_periodofinal != "")
          $resac = db_query("insert into db_acount values($acount,3435,19319,'".AddSlashes(pg_result($resaco,$conresaco,'t75_periodofinal'))."','$this->t75_periodofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_exercicio"]) || $this->t75_exercicio != "")
          $resac = db_query("insert into db_acount values($acount,3435,19320,'".AddSlashes(pg_result($resaco,$conresaco,'t75_exercicio'))."','$this->t75_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_processo"]) || $this->t75_processo != "")
          $resac = db_query("insert into db_acount values($acount,3435,19321,'".AddSlashes(pg_result($resaco,$conresaco,'t75_processo'))."','$this->t75_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_acordocomissao"]) || $this->t75_acordocomissao != "")
          $resac = db_query("insert into db_acount values($acount,3435,19322,'".AddSlashes(pg_result($resaco,$conresaco,'t75_acordocomissao'))."','$this->t75_acordocomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_observacao"]) || $this->t75_observacao != "")
          $resac = db_query("insert into db_acount values($acount,3435,19323,'".AddSlashes(pg_result($resaco,$conresaco,'t75_observacao'))."','$this->t75_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_situacao"]) || $this->t75_situacao != "")
          $resac = db_query("insert into db_acount values($acount,3435,19324,'".AddSlashes(pg_result($resaco,$conresaco,'t75_situacao'))."','$this->t75_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        if(isset($GLOBALS["HTTP_POST_VARS"]["t75_db_depart"]) || $this->t75_db_depart != "")
          $resac = db_query("insert into db_acount values($acount,3435,19325,'".AddSlashes(pg_result($resaco,$conresaco,'t75_db_depart'))."','$this->t75_db_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Inventario nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : ".$this->t75_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Inventario nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : ".$this->t75_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->t75_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($t75_sequencial=null,$dbwhere=null) {
    if($dbwhere==null || $dbwhere==""){
      $resaco = $this->sql_record($this->sql_query_file($t75_sequencial));
    }else{
      $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
    }
    if(($resaco!=false)||($this->numrows!=0)){
      for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,19316,'$t75_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,3435,19316,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19317,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_dataabertura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19318,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19319,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19320,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19321,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19322,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19323,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19324,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,3435,19325,'','".AddSlashes(pg_result($resaco,$iresaco,'t75_db_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
    }
    $sql = " delete from inventario
    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($t75_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " t75_sequencial = $t75_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Inventario nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : ".$t75_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "Inventario nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : ".$t75_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$t75_sequencial;
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
      $this->erro_sql   = "Record Vazio na Tabela:inventario";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $t75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from inventario ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
    $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
    $sql .= "      left  join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
    $sql .= "      left join db_config  on  db_config.codigo = db_depart.instit";
    $sql .= "      left join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
    $sql .= "      left join db_config  as a on   a.codigo = protprocesso.p58_instit";
    $sql .= "      left join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
    $sql .= "      left join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
    $sql .= "      inner join db_config  as b on   b.codigo = acordocomissao.ac08_instit";
    $sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
    $sql2 = "";
    if($dbwhere==""){
      if($t75_sequencial!=null ){
        $sql2 .= " where inventario.t75_sequencial = $t75_sequencial ";
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
  function sql_query_file ( $t75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from inventario ";
    $sql2 = "";
    if($dbwhere==""){
      if($t75_sequencial!=null ){
        $sql2 .= " where inventario.t75_sequencial = $t75_sequencial ";
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



  /*
   * metodo para trazer os inventarios que possuem bens vinculados
  */
  function sql_querybensvinculados ( $t75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select distinct ";
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
    $sql .= " from inventario ";
    $sql .= "       inner join inventariobem on inventario.t75_sequencial = inventariobem.t77_inventario";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
    $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
    $sql .= "      left  join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
    $sql .= "      left join db_config  on  db_config.codigo = db_depart.instit";
    $sql .= "      left join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
    $sql .= "      left join db_config  as a on   a.codigo = protprocesso.p58_instit";
    $sql .= "      left join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
    $sql .= "      left join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
    $sql .= "      left join db_config  as b on   b.codigo = acordocomissao.ac08_instit";
    $sql .= "      left join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";

    $sql2 = "";
    if($dbwhere==""){
      if($t75_sequencial!=null ){
        $sql2 .= " where inventario.t75_sequencial = $t75_sequencial ";
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


  function sql_query_left_processo ( $t75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from inventario ";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = inventario.t75_db_depart";
    $sql .= "      left  join protprocesso  on  protprocesso.p58_codproc = inventario.t75_processo";
    $sql .= "      left  join acordocomissao  on  acordocomissao.ac08_sequencial = inventario.t75_acordocomissao";
    $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
    $sql .= "      inner join db_config  as a on   a.codigo = protprocesso.p58_instit";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
    $sql .= "      left  join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
    $sql .= "      inner join db_config  as b on   b.codigo = acordocomissao.ac08_instit";
    $sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
    $sql2 = "";
    if($dbwhere==""){
      if($t75_sequencial!=null ){
        $sql2 .= " where inventario.t75_sequencial = $t75_sequencial ";
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
}
?>