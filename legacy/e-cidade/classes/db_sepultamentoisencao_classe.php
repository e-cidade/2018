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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE sepultamentoisencao
class cl_sepultamentoisencao {
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
   var $cm33_sequencial = 0;
   var $cm33_processo = 0;
   var $cm33_sepultamento = 0;
   var $cm33_isencao = 0;
   var $cm33_datalanc_dia = null;
   var $cm33_datalanc_mes = null;
   var $cm33_datalanc_ano = null;
   var $cm33_datalanc = null;
   var $cm33_datainicio_dia = null;
   var $cm33_datainicio_mes = null;
   var $cm33_datainicio_ano = null;
   var $cm33_datainicio = null;
   var $cm33_datafim_dia = null;
   var $cm33_datafim_mes = null;
   var $cm33_datafim_ano = null;
   var $cm33_datafim = null;
   var $cm33_percentual = 0;
   var $cm33_obs = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm33_sequencial = int4 = Sequencial
                 cm33_processo = int4 = Processo
                 cm33_sepultamento = int4 = Sepultamento
                 cm33_isencao = int4 = Isenção
                 cm33_datalanc = date = Data Lançamento
                 cm33_datainicio = date = Data Início
                 cm33_datafim = date = Data Final
                 cm33_percentual = float8 = Percentual
                 cm33_obs = text = Observação
                 ";
   //funcao construtor da classe
   function cl_sepultamentoisencao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sepultamentoisencao");
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
       $this->cm33_sequencial = ($this->cm33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_sequencial"]:$this->cm33_sequencial);
       $this->cm33_processo = ($this->cm33_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_processo"]:$this->cm33_processo);
       $this->cm33_sepultamento = ($this->cm33_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_sepultamento"]:$this->cm33_sepultamento);
       $this->cm33_isencao = ($this->cm33_isencao == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_isencao"]:$this->cm33_isencao);
       if($this->cm33_datalanc == ""){
         $this->cm33_datalanc_dia = ($this->cm33_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_dia"]:$this->cm33_datalanc_dia);
         $this->cm33_datalanc_mes = ($this->cm33_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_mes"]:$this->cm33_datalanc_mes);
         $this->cm33_datalanc_ano = ($this->cm33_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_ano"]:$this->cm33_datalanc_ano);
         if($this->cm33_datalanc_dia != ""){
            $this->cm33_datalanc = $this->cm33_datalanc_ano."-".$this->cm33_datalanc_mes."-".$this->cm33_datalanc_dia;
         }
       }
       if($this->cm33_datainicio == ""){
         $this->cm33_datainicio_dia = ($this->cm33_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_dia"]:$this->cm33_datainicio_dia);
         $this->cm33_datainicio_mes = ($this->cm33_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_mes"]:$this->cm33_datainicio_mes);
         $this->cm33_datainicio_ano = ($this->cm33_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_ano"]:$this->cm33_datainicio_ano);
         if($this->cm33_datainicio_dia != ""){
            $this->cm33_datainicio = $this->cm33_datainicio_ano."-".$this->cm33_datainicio_mes."-".$this->cm33_datainicio_dia;
         }
       }
       if($this->cm33_datafim == ""){
         $this->cm33_datafim_dia = ($this->cm33_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datafim_dia"]:$this->cm33_datafim_dia);
         $this->cm33_datafim_mes = ($this->cm33_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datafim_mes"]:$this->cm33_datafim_mes);
         $this->cm33_datafim_ano = ($this->cm33_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_datafim_ano"]:$this->cm33_datafim_ano);
         if($this->cm33_datafim_dia != ""){
            $this->cm33_datafim = $this->cm33_datafim_ano."-".$this->cm33_datafim_mes."-".$this->cm33_datafim_dia;
         }
       }
       $this->cm33_percentual = ($this->cm33_percentual == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_percentual"]:$this->cm33_percentual);
       $this->cm33_obs = ($this->cm33_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_obs"]:$this->cm33_obs);
     }else{
       $this->cm33_sequencial = ($this->cm33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cm33_sequencial"]:$this->cm33_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cm33_sequencial){
      $this->atualizacampos();
     if($this->cm33_processo == null ){
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "cm33_processo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm33_sepultamento == null ){
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm33_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm33_isencao == null ){
       $this->erro_sql = " Campo Isenção nao Informado.";
       $this->erro_campo = "cm33_isencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm33_datalanc == null ){
       $this->erro_sql = " Campo Data Lançamento nao Informado.";
       $this->erro_campo = "cm33_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm33_datainicio == null ){
       $this->cm33_datainicio = "null";
     }
     if($this->cm33_datafim == null ){
       $this->cm33_datafim = "null";
     }
     if($this->cm33_percentual == null ){
       $this->cm33_percentual = "0";
     }
     if($cm33_sequencial == "" || $cm33_sequencial == null ){
       $result = db_query("select nextval('sepultamentoisencao_cm33_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sepultamentoisencao_cm33_sequencial_seq do campo: cm33_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm33_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from sepultamentoisencao_cm33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm33_sequencial)){
         $this->erro_sql = " Campo cm33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm33_sequencial = $cm33_sequencial;
       }
     }
     if(($this->cm33_sequencial == null) || ($this->cm33_sequencial == "") ){
       $this->erro_sql = " Campo cm33_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sepultamentoisencao(
                                       cm33_sequencial
                                      ,cm33_processo
                                      ,cm33_sepultamento
                                      ,cm33_isencao
                                      ,cm33_datalanc
                                      ,cm33_datainicio
                                      ,cm33_datafim
                                      ,cm33_percentual
                                      ,cm33_obs
                       )
                values (
                                $this->cm33_sequencial
                               ,$this->cm33_processo
                               ,$this->cm33_sepultamento
                               ,$this->cm33_isencao
                               ,".($this->cm33_datalanc == "null" || $this->cm33_datalanc == ""?"null":"'".$this->cm33_datalanc."'")."
                               ,".($this->cm33_datainicio == "null" || $this->cm33_datainicio == ""?"null":"'".$this->cm33_datainicio."'")."
                               ,".($this->cm33_datafim == "null" || $this->cm33_datafim == ""?"null":"'".$this->cm33_datafim."'")."
                               ,$this->cm33_percentual
                               ,'$this->cm33_obs'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sepultamentoisencao ($this->cm33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sepultamentoisencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sepultamentoisencao ($this->cm33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm33_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14602,'$this->cm33_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2567,14602,'','".AddSlashes(pg_result($resaco,0,'cm33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14603,'','".AddSlashes(pg_result($resaco,0,'cm33_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14604,'','".AddSlashes(pg_result($resaco,0,'cm33_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14605,'','".AddSlashes(pg_result($resaco,0,'cm33_isencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14606,'','".AddSlashes(pg_result($resaco,0,'cm33_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14607,'','".AddSlashes(pg_result($resaco,0,'cm33_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14608,'','".AddSlashes(pg_result($resaco,0,'cm33_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14609,'','".AddSlashes(pg_result($resaco,0,'cm33_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2567,14610,'','".AddSlashes(pg_result($resaco,0,'cm33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm33_sequencial=null) {
      $this->atualizacampos();
     $sql = " update sepultamentoisencao set ";
     $virgula = "";
     if(trim($this->cm33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_sequencial"])){
       $sql  .= $virgula." cm33_sequencial = $this->cm33_sequencial ";
       $virgula = ",";
       if(trim($this->cm33_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cm33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm33_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_processo"])){
       $sql  .= $virgula." cm33_processo = $this->cm33_processo ";
       $virgula = ",";
       if(trim($this->cm33_processo) == null ){
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "cm33_processo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm33_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_sepultamento"])){
       $sql  .= $virgula." cm33_sepultamento = $this->cm33_sepultamento ";
       $virgula = ",";
       if(trim($this->cm33_sepultamento) == null ){
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm33_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm33_isencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_isencao"])){
       $sql  .= $virgula." cm33_isencao = $this->cm33_isencao ";
       $virgula = ",";
       if(trim($this->cm33_isencao) == null ){
         $this->erro_sql = " Campo Isenção nao Informado.";
         $this->erro_campo = "cm33_isencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm33_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_dia"] !="") ){
       $sql  .= $virgula." cm33_datalanc = '$this->cm33_datalanc' ";
       $virgula = ",";
       if(trim($this->cm33_datalanc) == null ){
         $this->erro_sql = " Campo Data Lançamento nao Informado.";
         $this->erro_campo = "cm33_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datalanc_dia"])){
         $sql  .= $virgula." cm33_datalanc = null ";
         $virgula = ",";
         if(trim($this->cm33_datalanc) == null ){
           $this->erro_sql = " Campo Data Lançamento nao Informado.";
           $this->erro_campo = "cm33_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm33_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_dia"] !="") ){
       $sql  .= $virgula." cm33_datainicio = '$this->cm33_datainicio' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datainicio_dia"])){
         $sql  .= $virgula." cm33_datainicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm33_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm33_datafim_dia"] !="") ){
       $sql  .= $virgula." cm33_datafim = '$this->cm33_datafim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datafim_dia"])){
         $sql  .= $virgula." cm33_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->cm33_percentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_percentual"])){
        if(trim($this->cm33_percentual)=="" && isset($GLOBALS["HTTP_POST_VARS"]["cm33_percentual"])){
           $this->cm33_percentual = "0" ;
        }
       $sql  .= $virgula." cm33_percentual = $this->cm33_percentual ";
       $virgula = ",";
     }
     if(trim($this->cm33_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm33_obs"])){
       $sql  .= $virgula." cm33_obs = '$this->cm33_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cm33_sequencial!=null){
       $sql .= " cm33_sequencial = $this->cm33_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm33_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14602,'$this->cm33_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_sequencial"]) || $this->cm33_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2567,14602,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_sequencial'))."','$this->cm33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_processo"]) || $this->cm33_processo != "")
           $resac = db_query("insert into db_acount values($acount,2567,14603,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_processo'))."','$this->cm33_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_sepultamento"]) || $this->cm33_sepultamento != "")
           $resac = db_query("insert into db_acount values($acount,2567,14604,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_sepultamento'))."','$this->cm33_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_isencao"]) || $this->cm33_isencao != "")
           $resac = db_query("insert into db_acount values($acount,2567,14605,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_isencao'))."','$this->cm33_isencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datalanc"]) || $this->cm33_datalanc != "")
           $resac = db_query("insert into db_acount values($acount,2567,14606,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_datalanc'))."','$this->cm33_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datainicio"]) || $this->cm33_datainicio != "")
           $resac = db_query("insert into db_acount values($acount,2567,14607,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_datainicio'))."','$this->cm33_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_datafim"]) || $this->cm33_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2567,14608,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_datafim'))."','$this->cm33_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_percentual"]) || $this->cm33_percentual != "")
           $resac = db_query("insert into db_acount values($acount,2567,14609,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_percentual'))."','$this->cm33_percentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm33_obs"]) || $this->cm33_obs != "")
           $resac = db_query("insert into db_acount values($acount,2567,14610,'".AddSlashes(pg_result($resaco,$conresaco,'cm33_obs'))."','$this->cm33_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sepultamentoisencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sepultamentoisencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm33_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm33_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14602,'$cm33_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2567,14602,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14603,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14604,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14605,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_isencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14606,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14607,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14608,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14609,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_percentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2567,14610,'','".AddSlashes(pg_result($resaco,$iresaco,'cm33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sepultamentoisencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm33_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm33_sequencial = $cm33_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sepultamentoisencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sepultamentoisencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sepultamentoisencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $cm33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepultamentoisencao ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = sepultamentoisencao.cm33_processo";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = sepultamentoisencao.cm33_sepultamento";
     $sql .= "      inner join cemiterioisencao  on  cemiterioisencao.cm34_sequencial = sepultamentoisencao.cm33_isencao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  as b on   b.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  as c on   c.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      left join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql .= "      left join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql .= "      left join legista  on  legista.cm32_i_codigo = sepultamentos.cm01_i_medico";
     $sql2 = "";
     if($dbwhere==""){
       if($cm33_sequencial!=null ){
         $sql2 .= " where sepultamentoisencao.cm33_sequencial = $cm33_sequencial ";
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
   function sql_query_file ( $cm33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sepultamentoisencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm33_sequencial!=null ){
         $sql2 .= " where sepultamentoisencao.cm33_sequencial = $cm33_sequencial ";
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