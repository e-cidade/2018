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
//CLASSE DA ENTIDADE emppresta
class cl_emppresta {
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
   var $e45_numemp = 0;
   var $e45_data_dia = null;
   var $e45_data_mes = null;
   var $e45_data_ano = null;
   var $e45_data = null;
   var $e45_obs = null;
   var $e45_tipo = 0;
   var $e45_acerta_dia = null;
   var $e45_acerta_mes = null;
   var $e45_acerta_ano = null;
   var $e45_acerta = null;
   var $e45_conferido_dia = null;
   var $e45_conferido_mes = null;
   var $e45_conferido_ano = null;
   var $e45_conferido     = null;
   var $e45_codmov        = null;
   var $e45_sequencial    = 0;
   var $e45_processoadministrativo = null;
   var $e45_datalimiteaplicacao_dia = null;
   var $e45_datalimiteaplicacao_mes = null;
   var $e45_datalimiteaplicacao_ano = null;
   var $e45_datalimiteaplicacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e45_numemp = int4 = Número do Empenho
                 e45_data = date = Data
                 e45_obs = text = Observação
                 e45_tipo = int4 = Tipo
                 e45_acerta = date = Acerto da Prestação de Contas
                 e45_conferido = date = Conferido
                 e45_codmov = int4 = Código do Movimento
                 e45_sequencial = int4 = Código Sequencial
                 e45_processoadministrativo = varchar(20) = Processo Administrativo
                 e45_datalimiteaplicacao = date = Data Limite para Aplicação
                 ";
   //funcao construtor da classe
   function cl_emppresta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("emppresta");
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
       $this->e45_numemp = ($this->e45_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_numemp"]:$this->e45_numemp);
       if($this->e45_data == ""){
         $this->e45_data_dia = ($this->e45_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_data_dia"]:$this->e45_data_dia);
         $this->e45_data_mes = ($this->e45_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_data_mes"]:$this->e45_data_mes);
         $this->e45_data_ano = ($this->e45_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_data_ano"]:$this->e45_data_ano);
         if($this->e45_data_dia != ""){
            $this->e45_data = $this->e45_data_ano."-".$this->e45_data_mes."-".$this->e45_data_dia;
         }
       }
       $this->e45_obs = ($this->e45_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_obs"]:$this->e45_obs);
       $this->e45_tipo = ($this->e45_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_tipo"]:$this->e45_tipo);
       if($this->e45_acerta == ""){
         $this->e45_acerta_dia = ($this->e45_acerta_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_acerta_dia"]:$this->e45_acerta_dia);
         $this->e45_acerta_mes = ($this->e45_acerta_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_acerta_mes"]:$this->e45_acerta_mes);
         $this->e45_acerta_ano = ($this->e45_acerta_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_acerta_ano"]:$this->e45_acerta_ano);
         if($this->e45_acerta_dia != ""){
            $this->e45_acerta = $this->e45_acerta_ano."-".$this->e45_acerta_mes."-".$this->e45_acerta_dia;
         }
       }
       if($this->e45_conferido == ""){
         $this->e45_conferido_dia = ($this->e45_conferido_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_conferido_dia"]:$this->e45_conferido_dia);
         $this->e45_conferido_mes = ($this->e45_conferido_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_conferido_mes"]:$this->e45_conferido_mes);
         $this->e45_conferido_ano = ($this->e45_conferido_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_conferido_ano"]:$this->e45_conferido_ano);
         if($this->e45_conferido_dia != ""){
            $this->e45_conferido = $this->e45_conferido_ano."-".$this->e45_conferido_mes."-".$this->e45_conferido_dia;
         }
       }
       $this->e45_codmov = ($this->e45_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_codmov"]:$this->e45_codmov);
       $this->e45_sequencial = ($this->e45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_sequencial"]:$this->e45_sequencial);
       $this->e45_processoadministrativo = ($this->e45_processoadministrativo == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_processoadministrativo"]:$this->e45_processoadministrativo);
       if($this->e45_datalimiteaplicacao == ""){
         $this->e45_datalimiteaplicacao_dia = ($this->e45_datalimiteaplicacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_dia"]:$this->e45_datalimiteaplicacao_dia);
         $this->e45_datalimiteaplicacao_mes = ($this->e45_datalimiteaplicacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_mes"]:$this->e45_datalimiteaplicacao_mes);
         $this->e45_datalimiteaplicacao_ano = ($this->e45_datalimiteaplicacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_ano"]:$this->e45_datalimiteaplicacao_ano);
         if($this->e45_datalimiteaplicacao_dia != ""){
            $this->e45_datalimiteaplicacao = $this->e45_datalimiteaplicacao_ano."-".$this->e45_datalimiteaplicacao_mes."-".$this->e45_datalimiteaplicacao_dia;
         }
       }
     }else{
       $this->e45_sequencial = ($this->e45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e45_sequencial"]:$this->e45_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e45_sequencial){
      $this->atualizacampos();
     if($this->e45_numemp == null ){
       $this->erro_sql = " Campo Número do Empenho não informado.";
       $this->erro_campo = "e45_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e45_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "e45_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e45_tipo == null ){
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "e45_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e45_acerta == null ){
       $this->e45_acerta = "null";
     }
     if($this->e45_conferido == null ){
       $this->e45_conferido = "null";
     }
     if($this->e45_codmov == null ){
       $this->e45_codmov = "null";
     }
     if($e45_sequencial == "" || $e45_sequencial == null ){
       $result = db_query("select nextval('emppresta_e45_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: emppresta_e45_sequencial_seq do campo: e45_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e45_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from emppresta_e45_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e45_sequencial)){
         $this->erro_sql = " Campo e45_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e45_sequencial = $e45_sequencial;
       }
     }
     if(($this->e45_sequencial == null) || ($this->e45_sequencial == "") ){
       $this->erro_sql = " Campo e45_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into emppresta(
                                       e45_numemp
                                      ,e45_data
                                      ,e45_obs
                                      ,e45_tipo
                                      ,e45_acerta
                                      ,e45_conferido
                                      ,e45_codmov
                                      ,e45_sequencial
                                      ,e45_processoadministrativo
                                      ,e45_datalimiteaplicacao
                       )
                values (
                                $this->e45_numemp
                               ,".($this->e45_data == "null" || $this->e45_data == ""?"null":"'".$this->e45_data."'")."
                               ,'$this->e45_obs'
                               ,$this->e45_tipo
                               ,".($this->e45_acerta == "null" || $this->e45_acerta == ""?"null":"'".$this->e45_acerta."'")."
                               ,".($this->e45_conferido == "null" || $this->e45_conferido == ""?"null":"'".$this->e45_conferido."'")."
                               ,$this->e45_codmov
                               ,$this->e45_sequencial
                               ,'$this->e45_processoadministrativo'
                               ,".($this->e45_datalimiteaplicacao == "null" || $this->e45_datalimiteaplicacao == ""?"null":"'".$this->e45_datalimiteaplicacao."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prestação ($this->e45_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prestação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prestação ($this->e45_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e45_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e45_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20271,'$this->e45_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1036,6340,'','".AddSlashes(pg_result($resaco,0,'e45_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,6341,'','".AddSlashes(pg_result($resaco,0,'e45_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,6342,'','".AddSlashes(pg_result($resaco,0,'e45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,6343,'','".AddSlashes(pg_result($resaco,0,'e45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,6344,'','".AddSlashes(pg_result($resaco,0,'e45_acerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,6345,'','".AddSlashes(pg_result($resaco,0,'e45_conferido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,20269,'','".AddSlashes(pg_result($resaco,0,'e45_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,20271,'','".AddSlashes(pg_result($resaco,0,'e45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,20884,'','".AddSlashes(pg_result($resaco,0,'e45_processoadministrativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1036,20889,'','".AddSlashes(pg_result($resaco,0,'e45_datalimiteaplicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($e45_sequencial=null) {
      $this->atualizacampos();
     $sql = " update emppresta set ";
     $virgula = "";
     if(trim($this->e45_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_numemp"])){
       $sql  .= $virgula." e45_numemp = $this->e45_numemp ";
       $virgula = ",";
       if(trim($this->e45_numemp) == null ){
         $this->erro_sql = " Campo Número do Empenho não informado.";
         $this->erro_campo = "e45_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e45_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e45_data_dia"] !="") ){
       $sql  .= $virgula." e45_data = '$this->e45_data' ";
       $virgula = ",";
       if(trim($this->e45_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "e45_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e45_data_dia"])){
         $sql  .= $virgula." e45_data = null ";
         $virgula = ",";
         if(trim($this->e45_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "e45_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e45_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_obs"])){
       $sql  .= $virgula." e45_obs = '$this->e45_obs' ";
       $virgula = ",";
     }
     if(trim($this->e45_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_tipo"])){
       $sql  .= $virgula." e45_tipo = $this->e45_tipo ";
       $virgula = ",";
       if(trim($this->e45_tipo) == null ){
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "e45_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e45_acerta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_acerta_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e45_acerta_dia"] !="") ){
       $sql  .= $virgula." e45_acerta = '$this->e45_acerta' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e45_acerta_dia"])){
         $sql  .= $virgula." e45_acerta = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e45_conferido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_conferido_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e45_conferido_dia"] !="") ){
       $sql  .= $virgula." e45_conferido = '$this->e45_conferido' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e45_conferido_dia"])){
         $sql  .= $virgula." e45_conferido = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e45_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_codmov"])){
        if(trim($this->e45_codmov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e45_codmov"])){
           $this->e45_codmov = "null" ;
        }
       $sql  .= $virgula." e45_codmov = $this->e45_codmov ";
       $virgula = ",";
     }
     if(trim($this->e45_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_sequencial"])){
       $sql  .= $virgula." e45_sequencial = $this->e45_sequencial ";
       $virgula = ",";
       if(trim($this->e45_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "e45_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e45_processoadministrativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_processoadministrativo"])){
       $sql  .= $virgula." e45_processoadministrativo = '$this->e45_processoadministrativo' ";
       $virgula = ",";
     }
     if(trim($this->e45_datalimiteaplicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_dia"] !="") ){
       $sql  .= $virgula." e45_datalimiteaplicacao = '$this->e45_datalimiteaplicacao' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao_dia"])){
         $sql  .= $virgula." e45_datalimiteaplicacao = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($e45_sequencial!=null){
       $sql .= " e45_sequencial = $this->e45_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e45_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20271,'$this->e45_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_numemp"]) || $this->e45_numemp != "")
             $resac = db_query("insert into db_acount values($acount,1036,6340,'".AddSlashes(pg_result($resaco,$conresaco,'e45_numemp'))."','$this->e45_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_data"]) || $this->e45_data != "")
             $resac = db_query("insert into db_acount values($acount,1036,6341,'".AddSlashes(pg_result($resaco,$conresaco,'e45_data'))."','$this->e45_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_obs"]) || $this->e45_obs != "")
             $resac = db_query("insert into db_acount values($acount,1036,6342,'".AddSlashes(pg_result($resaco,$conresaco,'e45_obs'))."','$this->e45_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_tipo"]) || $this->e45_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1036,6343,'".AddSlashes(pg_result($resaco,$conresaco,'e45_tipo'))."','$this->e45_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_acerta"]) || $this->e45_acerta != "")
             $resac = db_query("insert into db_acount values($acount,1036,6344,'".AddSlashes(pg_result($resaco,$conresaco,'e45_acerta'))."','$this->e45_acerta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_conferido"]) || $this->e45_conferido != "")
             $resac = db_query("insert into db_acount values($acount,1036,6345,'".AddSlashes(pg_result($resaco,$conresaco,'e45_conferido'))."','$this->e45_conferido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_codmov"]) || $this->e45_codmov != "")
             $resac = db_query("insert into db_acount values($acount,1036,20269,'".AddSlashes(pg_result($resaco,$conresaco,'e45_codmov'))."','$this->e45_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_sequencial"]) || $this->e45_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1036,20271,'".AddSlashes(pg_result($resaco,$conresaco,'e45_sequencial'))."','$this->e45_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_processoadministrativo"]) || $this->e45_processoadministrativo != "")
             $resac = db_query("insert into db_acount values($acount,1036,20884,'".AddSlashes(pg_result($resaco,$conresaco,'e45_processoadministrativo'))."','$this->e45_processoadministrativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e45_datalimiteaplicacao"]) || $this->e45_datalimiteaplicacao != "")
             $resac = db_query("insert into db_acount values($acount,1036,20889,'".AddSlashes(pg_result($resaco,$conresaco,'e45_datalimiteaplicacao'))."','$this->e45_datalimiteaplicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e45_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($e45_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e45_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20271,'$e45_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1036,6340,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,6341,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,6342,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,6343,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,6344,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_acerta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,6345,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_conferido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,20269,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,20271,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,20884,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_processoadministrativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1036,20889,'','".AddSlashes(pg_result($resaco,$iresaco,'e45_datalimiteaplicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from emppresta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e45_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e45_sequencial = $e45_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e45_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e45_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:emppresta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($e45_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from emppresta ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = emppresta.e45_numemp";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e45_sequencial)) {
         $sql2 .= " where emppresta.e45_sequencial = $e45_sequencial ";
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
   public function sql_query_file ($e45_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from emppresta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e45_sequencial)){
         $sql2 .= " where emppresta.e45_sequencial = $e45_sequencial ";
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

   function sql_query_depto ( $e45_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from emppresta ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = emppresta.e45_numemp";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = empempenho.e60_instit";
//     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
//     $sql .= "      inner join db_departorg on db01_orgao = o58_orgao and db01_unidade = o58_unidade and db01_anousu=".db_getsession("DB_anousu");
     $sql2 = "";
     if($dbwhere==""){
       if($e45_numemp!=null ){
         $sql2 .= " where emppresta.e45_numemp = $e45_numemp ";
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
  function sql_query_depto_movimento ( $e45_numemp=null, $campos="*", $ordem=null, $group = null, $dbwhere=""){
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
     $sql .= " from emppresta ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = emppresta.e45_numemp";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join empagemov on e81_numemp = e45_numemp";

     $sql2 = "";
     if($dbwhere==""){
       if($e45_numemp!=null ){
         $sql2 .= " where emppresta.e45_numemp = $e45_numemp ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;

     if ($group != null) {
      $sql .= " group by {$group}";
     }

     if ($ordem != null) {
       $sql .= " order by {$ordem}";
     }

     return $sql;
  }

   function sql_query_emp ( $e45_numemp=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from emppresta ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = emppresta.e45_numemp";
     $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($e45_numemp!=null ){
         $sql2 .= " where emppresta.e45_numemp = $e45_numemp ";
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

  function sql_query_nota_empenho ( $e45_numemp=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select {$campos} ";



    $sql .= " from emppresta ";

    $sql .= "      inner join empagemov on e45_codmov = e81_codmov ";
    $sql .= "      inner join empord on e81_codmov    = e82_codmov ";
    $sql .= "      inner join pagordem on e82_codord  = e50_codord ";
    $sql .= "      inner join pagordemnota on pagordemnota.e71_codord = e50_codord ";
    $sql .= "      inner join pagordemele on pagordemele.e53_codord = e50_codord ";
    $sql .= "      inner join empnota     on empnota.e69_codnota = e71_codnota";
    $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp ";
    $sql .= "      inner join empempaut   on  empempaut.e61_numemp = empempenho.e60_numemp ";
    $sql .= "      left join empautorizaprocesso  on  empautorizaprocesso.e150_empautoriza = empempaut.e61_autori ";
    $sql .= "      inner join empprestatip  on  empprestatip.e44_tipo = emppresta.e45_tipo ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm ";
    $sql .= "      inner join conlancamord  on  conlancamord.c80_codord = pagordem.e50_codord ";
    $sql .= "      inner join conlancamdoc  on  conlancamdoc.c71_codlan  = conlancamord.c80_codlan ";
    $sql .= "      inner join conhistdoc   on  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc ";

    $sql2 = "";
    if($dbwhere==""){
      if($e45_numemp!=null ){
        $sql2 .= " where emppresta.e45_numemp = $e45_numemp ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if ( !empty($ordem) ) {
      $sql .= " order by {$ordem}";
     }
     return $sql;
  }
}
