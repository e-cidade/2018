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

//MODULO: caixa
//CLASSE DA ENTIDADE conciliapendcorrente
class cl_conciliapendcorrente {
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
   var $k89_sequencial = 0;
   var $k89_concilia = 0;
   var $k89_id = 0;
   var $k89_data_dia = null;
   var $k89_data_mes = null;
   var $k89_data_ano = null;
   var $k89_data = null;
   var $k89_autent = 0;
   var $k89_conciliaorigem = 0;
   var $k89_justificativa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k89_sequencial = int4 = Codigo sequencial
                 k89_concilia = int4 = Codigo da conciliação
                 k89_id = int4 = Autenticação
                 k89_data = date = Data Autenticação
                 k89_autent = int4 = Código Autenticação
                 k89_conciliaorigem = int4 = Código
                 k89_justificativa = text = Justificativa
                 ";
   //funcao construtor da classe
   function cl_conciliapendcorrente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conciliapendcorrente");
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
       $this->k89_sequencial = ($this->k89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_sequencial"]:$this->k89_sequencial);
       $this->k89_concilia = ($this->k89_concilia == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_concilia"]:$this->k89_concilia);
       $this->k89_id = ($this->k89_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_id"]:$this->k89_id);
       if($this->k89_data == ""){
         $this->k89_data_dia = ($this->k89_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_data_dia"]:$this->k89_data_dia);
         $this->k89_data_mes = ($this->k89_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_data_mes"]:$this->k89_data_mes);
         $this->k89_data_ano = ($this->k89_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_data_ano"]:$this->k89_data_ano);
         if($this->k89_data_dia != ""){
            $this->k89_data = $this->k89_data_ano."-".$this->k89_data_mes."-".$this->k89_data_dia;
         }
       }
       $this->k89_autent = ($this->k89_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_autent"]:$this->k89_autent);
       $this->k89_conciliaorigem = ($this->k89_conciliaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_conciliaorigem"]:$this->k89_conciliaorigem);
       $this->k89_justificativa = ($this->k89_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_justificativa"]:$this->k89_justificativa);
     }else{
       $this->k89_sequencial = ($this->k89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k89_sequencial"]:$this->k89_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k89_sequencial){
      $this->atualizacampos();
     if($this->k89_concilia == null ){
       $this->erro_sql = " Campo Codigo da conciliação nao Informado.";
       $this->erro_campo = "k89_concilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k89_id == null ){
       $this->erro_sql = " Campo Autenticação nao Informado.";
       $this->erro_campo = "k89_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k89_data == null ){
       $this->erro_sql = " Campo Data Autenticação nao Informado.";
       $this->erro_campo = "k89_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k89_autent == null ){
       $this->erro_sql = " Campo Código Autenticação nao Informado.";
       $this->erro_campo = "k89_autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k89_conciliaorigem == null ){
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "k89_conciliaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k89_sequencial == "" || $k89_sequencial == null ){
       $result = db_query("select nextval('conciliapendcorrente_k89_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conciliapendcorrente_k89_sequencial_seq do campo: k89_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k89_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conciliapendcorrente_k89_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k89_sequencial)){
         $this->erro_sql = " Campo k89_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k89_sequencial = $k89_sequencial;
       }
     }
     if(($this->k89_sequencial == null) || ($this->k89_sequencial == "") ){
       $this->erro_sql = " Campo k89_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conciliapendcorrente(
                                       k89_sequencial
                                      ,k89_concilia
                                      ,k89_id
                                      ,k89_data
                                      ,k89_autent
                                      ,k89_conciliaorigem
                                      ,k89_justificativa
                       )
                values (
                                $this->k89_sequencial
                               ,$this->k89_concilia
                               ,$this->k89_id
                               ,".($this->k89_data == "null" || $this->k89_data == ""?"null":"'".$this->k89_data."'")."
                               ,$this->k89_autent
                               ,$this->k89_conciliaorigem
                               ,'$this->k89_justificativa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pendencias do corrente ($this->k89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pendencias do corrente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pendencias do corrente ($this->k89_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k89_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k89_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10087,'$this->k89_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1733,10087,'','".AddSlashes(pg_result($resaco,0,'k89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,10086,'','".AddSlashes(pg_result($resaco,0,'k89_concilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,10083,'','".AddSlashes(pg_result($resaco,0,'k89_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,10084,'','".AddSlashes(pg_result($resaco,0,'k89_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,10085,'','".AddSlashes(pg_result($resaco,0,'k89_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,10171,'','".AddSlashes(pg_result($resaco,0,'k89_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1733,19286,'','".AddSlashes(pg_result($resaco,0,'k89_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k89_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conciliapendcorrente set ";
     $virgula = "";
     if(trim($this->k89_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_sequencial"])){
       $sql  .= $virgula." k89_sequencial = $this->k89_sequencial ";
       $virgula = ",";
       if(trim($this->k89_sequencial) == null ){
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k89_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k89_concilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_concilia"])){
       $sql  .= $virgula." k89_concilia = $this->k89_concilia ";
       $virgula = ",";
       if(trim($this->k89_concilia) == null ){
         $this->erro_sql = " Campo Codigo da conciliação nao Informado.";
         $this->erro_campo = "k89_concilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k89_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_id"])){
       $sql  .= $virgula." k89_id = $this->k89_id ";
       $virgula = ",";
       if(trim($this->k89_id) == null ){
         $this->erro_sql = " Campo Autenticação nao Informado.";
         $this->erro_campo = "k89_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k89_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k89_data_dia"] !="") ){
       $sql  .= $virgula." k89_data = '$this->k89_data' ";
       $virgula = ",";
       if(trim($this->k89_data) == null ){
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "k89_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k89_data_dia"])){
         $sql  .= $virgula." k89_data = null ";
         $virgula = ",";
         if(trim($this->k89_data) == null ){
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "k89_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k89_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_autent"])){
       $sql  .= $virgula." k89_autent = $this->k89_autent ";
       $virgula = ",";
       if(trim($this->k89_autent) == null ){
         $this->erro_sql = " Campo Código Autenticação nao Informado.";
         $this->erro_campo = "k89_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k89_conciliaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_conciliaorigem"])){
       $sql  .= $virgula." k89_conciliaorigem = $this->k89_conciliaorigem ";
       $virgula = ",";
       if(trim($this->k89_conciliaorigem) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k89_conciliaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k89_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k89_justificativa"])){
       $sql  .= $virgula." k89_justificativa = '$this->k89_justificativa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k89_sequencial!=null){
       $sql .= " k89_sequencial = $this->k89_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k89_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10087,'$this->k89_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_sequencial"]) || $this->k89_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1733,10087,'".AddSlashes(pg_result($resaco,$conresaco,'k89_sequencial'))."','$this->k89_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_concilia"]) || $this->k89_concilia != "")
           $resac = db_query("insert into db_acount values($acount,1733,10086,'".AddSlashes(pg_result($resaco,$conresaco,'k89_concilia'))."','$this->k89_concilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_id"]) || $this->k89_id != "")
           $resac = db_query("insert into db_acount values($acount,1733,10083,'".AddSlashes(pg_result($resaco,$conresaco,'k89_id'))."','$this->k89_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_data"]) || $this->k89_data != "")
           $resac = db_query("insert into db_acount values($acount,1733,10084,'".AddSlashes(pg_result($resaco,$conresaco,'k89_data'))."','$this->k89_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_autent"]) || $this->k89_autent != "")
           $resac = db_query("insert into db_acount values($acount,1733,10085,'".AddSlashes(pg_result($resaco,$conresaco,'k89_autent'))."','$this->k89_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_conciliaorigem"]) || $this->k89_conciliaorigem != "")
           $resac = db_query("insert into db_acount values($acount,1733,10171,'".AddSlashes(pg_result($resaco,$conresaco,'k89_conciliaorigem'))."','$this->k89_conciliaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k89_justificativa"]) || $this->k89_justificativa != "")
           $resac = db_query("insert into db_acount values($acount,1733,19286,'".AddSlashes(pg_result($resaco,$conresaco,'k89_justificativa'))."','$this->k89_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias do corrente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias do corrente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k89_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k89_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10087,'$k89_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1733,10087,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,10086,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_concilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,10083,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,10084,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,10085,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,10171,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1733,19286,'','".AddSlashes(pg_result($resaco,$iresaco,'k89_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conciliapendcorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k89_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k89_sequencial = $k89_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias do corrente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k89_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias do corrente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k89_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k89_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conciliapendcorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $k89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conciliapendcorrente ";
     $sql .= "      inner join corrente  on  corrente.k12_id = conciliapendcorrente.k89_id and  corrente.k12_data = conciliapendcorrente.k89_data and  corrente.k12_autent = conciliapendcorrente.k89_autent";
     $sql .= "      inner join concilia  on  concilia.k68_sequencial = conciliapendcorrente.k89_concilia";
     $sql .= "      inner join conciliaorigem  on  conciliaorigem.k96_sequencial = conciliapendcorrente.k89_conciliaorigem";
     $sql .= "      inner join db_config  on  db_config.codigo = corrente.k12_instit";
     $sql .= "      inner join cfautent  on  cfautent.k11_id = corrente.k12_id";
     $sql .= "      inner join conciliastatus  on  conciliastatus.k95_sequencial = concilia.k68_conciliastatus";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = concilia.k68_contabancaria";
     $sql2 = "";
     if($dbwhere==""){
       if($k89_sequencial!=null ){
         $sql2 .= " where conciliapendcorrente.k89_sequencial = $k89_sequencial ";
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
   function sql_query_file ( $k89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conciliapendcorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($k89_sequencial!=null ){
         $sql2 .= " where conciliapendcorrente.k89_sequencial = $k89_sequencial ";
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

  function sql_query_pendencias_sigfis ( $iInstit, $iContaBancaria, $iSequencialConciliacao ) {

    $sqlPendenciascaixa  = "  select k89_id, ";
    $sqlPendenciascaixa .= "         k89_autent, ";
    $sqlPendenciascaixa .= "         k89_data, ";
    $sqlPendenciascaixa .= "         sum(case                                         ";
    $sqlPendenciascaixa .= "           when coalesce(rnvalordebito,0) <> 0                    ";
    $sqlPendenciascaixa .= "           then coalesce(rnvalordebito,0)                         ";
    $sqlPendenciascaixa .= "           else coalesce(rivalorcredito,0)                        ";
    $sqlPendenciascaixa .= "         end)  as vl_movconciliado ,                     ";
    $sqlPendenciascaixa .= "         max(k68_data) as data_conciliacao,                ";
    $sqlPendenciascaixa .= "         case                                         ";
    $sqlPendenciascaixa .= "           when coalesce(rnvalordebito,0) <> 0                    ";
    $sqlPendenciascaixa .= "           then 'D'                                   ";
    $sqlPendenciascaixa .= "           else 'C'                                   ";
    $sqlPendenciascaixa .= "         end as tipomovimentacao                      ";
    $sqlPendenciascaixa .= "    from conciliapendcorrente                         ";
    $sqlPendenciascaixa .= "         inner join concilia on concilia.k68_sequencial = conciliapendcorrente.k89_concilia ";
    $sqlPendenciascaixa .= "         inner join fc_extratocaixa({$iInstit},{$iContaBancaria},null,null,false ) on ricaixa  = k89_id ";
    $sqlPendenciascaixa .= "                                                                                  and riautent = k89_autent ";
    $sqlPendenciascaixa .= "                                                                                  and ridata   = k89_data ";
    $sqlPendenciascaixa .= "   where k89_concilia = {$iSequencialConciliacao} ";
    $sqlPendenciascaixa .= " group by k89_id,k89_autent,k89_data,tipomovimentacao ";
    $sqlPendenciascaixa .= " order by tipomovimentacao,k89_data,k89_id,k89_autent ";

    return $sqlPendenciascaixa;
  }


  public function sql_query_pendencia_tesouraria($iCodigoConta, $sCampos, $sWhere, $iInstituicao = null) {

    if (empty($iInstituicao)) {
      $iInstituicao = db_getsession('DB_instit');
    }

    $sSqlPendenciasDebito  = "  select $sCampos";
    $sSqlPendenciasDebito .= "    from conciliapendcorrente";
    $sSqlPendenciasDebito .= "         inner join fc_extratocaixa({$iInstituicao}, {$iCodigoConta}, null, null, false) on ricaixa  = k89_id ";
    $sSqlPendenciasDebito .= "                                                                                       and riautent = k89_autent ";
    $sSqlPendenciasDebito .= "                                                                                       and ridata   = k89_data ";
    $sSqlPendenciasDebito .= "   where {$sWhere} ";

    return $sSqlPendenciasDebito;
  }
}