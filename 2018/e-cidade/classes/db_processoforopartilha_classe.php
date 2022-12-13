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

//MODULO: juridico
//CLASSE DA ENTIDADE processoforopartilha
class cl_processoforopartilha {
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
   var $v76_sequencial = 0;
   var $v76_processoforo = 0;
   var $v76_tipolancamento = 0;
   var $v76_dtpagamento_dia = null;
   var $v76_dtpagamento_mes = null;
   var $v76_dtpagamento_ano = null;
   var $v76_dtpagamento = null;
   var $v76_obs = null;
   var $v76_valorpartilha = 0;
   var $v76_datapartilha_dia = null;
   var $v76_datapartilha_mes = null;
   var $v76_datapartilha_ano = null;
   var $v76_datapartilha = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v76_sequencial = int4 = Sequencial
                 v76_processoforo = int4 = Processo Foro
                 v76_tipolancamento = int4 = Forma de Lançamento
                 v76_dtpagamento = date = Data de Pagamento
                 v76_obs = text = Observação
                 v76_valorpartilha = numeric(15,2) = Valor da Partilha
                 v76_datapartilha = date = Data da Partilha
                 ";
   //funcao construtor da classe
   function cl_processoforopartilha() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforopartilha");
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
       $this->v76_sequencial = ($this->v76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_sequencial"]:$this->v76_sequencial);
       $this->v76_processoforo = ($this->v76_processoforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_processoforo"]:$this->v76_processoforo);
       $this->v76_tipolancamento = ($this->v76_tipolancamento == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_tipolancamento"]:$this->v76_tipolancamento);
       if($this->v76_dtpagamento == ""){
         $this->v76_dtpagamento_dia = ($this->v76_dtpagamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_dia"]:$this->v76_dtpagamento_dia);
         $this->v76_dtpagamento_mes = ($this->v76_dtpagamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_mes"]:$this->v76_dtpagamento_mes);
         $this->v76_dtpagamento_ano = ($this->v76_dtpagamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_ano"]:$this->v76_dtpagamento_ano);
         if($this->v76_dtpagamento_dia != ""){
            $this->v76_dtpagamento = $this->v76_dtpagamento_ano."-".$this->v76_dtpagamento_mes."-".$this->v76_dtpagamento_dia;
         }
       }
       $this->v76_obs = ($this->v76_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_obs"]:$this->v76_obs);
       $this->v76_valorpartilha = ($this->v76_valorpartilha == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_valorpartilha"]:$this->v76_valorpartilha);
       if($this->v76_datapartilha == ""){
         $this->v76_datapartilha_dia = ($this->v76_datapartilha_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_dia"]:$this->v76_datapartilha_dia);
         $this->v76_datapartilha_mes = ($this->v76_datapartilha_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_mes"]:$this->v76_datapartilha_mes);
         $this->v76_datapartilha_ano = ($this->v76_datapartilha_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_ano"]:$this->v76_datapartilha_ano);
         if($this->v76_datapartilha_dia != ""){
            $this->v76_datapartilha = $this->v76_datapartilha_ano."-".$this->v76_datapartilha_mes."-".$this->v76_datapartilha_dia;
         }
       }
     }else{
       $this->v76_sequencial = ($this->v76_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v76_sequencial"]:$this->v76_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v76_sequencial){
      $this->atualizacampos();
     if($this->v76_processoforo == null ){
       $this->erro_sql = " Campo Processo Foro nao Informado.";
       $this->erro_campo = "v76_processoforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v76_tipolancamento == null ){
       $this->erro_sql = " Campo Forma de Lançamento nao Informado.";
       $this->erro_campo = "v76_tipolancamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v76_dtpagamento == null ){
       $this->v76_dtpagamento = "null";
     }
     if($this->v76_valorpartilha == null ){
       $this->erro_sql = " Campo Valor da Partilha nao Informado.";
       $this->erro_campo = "v76_valorpartilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v76_datapartilha == null ){
       $this->erro_sql = " Campo Data da Partilha nao Informado.";
       $this->erro_campo = "v76_datapartilha_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v76_sequencial == "" || $v76_sequencial == null ){
       $result = db_query("select nextval('processoforopartilha_v76_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforopartilha_v76_sequencial_seq do campo: v76_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v76_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from processoforopartilha_v76_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v76_sequencial)){
         $this->erro_sql = " Campo v76_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v76_sequencial = $v76_sequencial;
       }
     }
     if(($this->v76_sequencial == null) || ($this->v76_sequencial == "") ){
       $this->erro_sql = " Campo v76_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforopartilha(
                                       v76_sequencial
                                      ,v76_processoforo
                                      ,v76_tipolancamento
                                      ,v76_dtpagamento
                                      ,v76_obs
                                      ,v76_valorpartilha
                                      ,v76_datapartilha
                       )
                values (
                                $this->v76_sequencial
                               ,$this->v76_processoforo
                               ,$this->v76_tipolancamento
                               ,".($this->v76_dtpagamento == "null" || $this->v76_dtpagamento == ""?"null":"'".$this->v76_dtpagamento."'")."
                               ,'$this->v76_obs'
                               ,$this->v76_valorpartilha
                               ,".($this->v76_datapartilha == "null" || $this->v76_datapartilha == ""?"null":"'".$this->v76_datapartilha."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Partilhas do Processo ($this->v76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Partilhas do Processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Partilhas do Processo ($this->v76_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v76_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v76_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18255,'$this->v76_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3229,18255,'','".AddSlashes(pg_result($resaco,0,'v76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,18259,'','".AddSlashes(pg_result($resaco,0,'v76_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,18260,'','".AddSlashes(pg_result($resaco,0,'v76_tipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,18261,'','".AddSlashes(pg_result($resaco,0,'v76_dtpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,18262,'','".AddSlashes(pg_result($resaco,0,'v76_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,19195,'','".AddSlashes(pg_result($resaco,0,'v76_valorpartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3229,19196,'','".AddSlashes(pg_result($resaco,0,'v76_datapartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($v76_sequencial=null) {
      $this->atualizacampos();
     $sql = " update processoforopartilha set ";
     $virgula = "";
     if(trim($this->v76_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_sequencial"])){
       $sql  .= $virgula." v76_sequencial = $this->v76_sequencial ";
       $virgula = ",";
       if(trim($this->v76_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v76_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v76_processoforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_processoforo"])){
       $sql  .= $virgula." v76_processoforo = $this->v76_processoforo ";
       $virgula = ",";
       if(trim($this->v76_processoforo) == null ){
         $this->erro_sql = " Campo Processo Foro nao Informado.";
         $this->erro_campo = "v76_processoforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v76_tipolancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_tipolancamento"])){
       $sql  .= $virgula." v76_tipolancamento = $this->v76_tipolancamento ";
       $virgula = ",";
       if(trim($this->v76_tipolancamento) == null ){
         $this->erro_sql = " Campo Forma de Lançamento nao Informado.";
         $this->erro_campo = "v76_tipolancamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v76_dtpagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_dia"] !="") ){
       $sql  .= $virgula." v76_dtpagamento = '$this->v76_dtpagamento' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento_dia"])){
         $sql  .= $virgula." v76_dtpagamento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->v76_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_obs"])){
       $sql  .= $virgula." v76_obs = '$this->v76_obs' ";
       $virgula = ",";
     }
     if(trim($this->v76_valorpartilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_valorpartilha"])){
       $sql  .= $virgula." v76_valorpartilha = $this->v76_valorpartilha ";
       $virgula = ",";
       if(trim($this->v76_valorpartilha) == null ){
         $this->erro_sql = " Campo Valor da Partilha nao Informado.";
         $this->erro_campo = "v76_valorpartilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v76_datapartilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_dia"] !="") ){
       $sql  .= $virgula." v76_datapartilha = '$this->v76_datapartilha' ";
       $virgula = ",";
       if(trim($this->v76_datapartilha) == null ){
         $this->erro_sql = " Campo Data da Partilha nao Informado.";
         $this->erro_campo = "v76_datapartilha_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v76_datapartilha_dia"])){
         $sql  .= $virgula." v76_datapartilha = null ";
         $virgula = ",";
         if(trim($this->v76_datapartilha) == null ){
           $this->erro_sql = " Campo Data da Partilha nao Informado.";
           $this->erro_campo = "v76_datapartilha_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($v76_sequencial!=null){
       $sql .= " v76_sequencial = $this->v76_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v76_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18255,'$this->v76_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_sequencial"]) || $this->v76_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3229,18255,'".AddSlashes(pg_result($resaco,$conresaco,'v76_sequencial'))."','$this->v76_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_processoforo"]) || $this->v76_processoforo != "")
           $resac = db_query("insert into db_acount values($acount,3229,18259,'".AddSlashes(pg_result($resaco,$conresaco,'v76_processoforo'))."','$this->v76_processoforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_tipolancamento"]) || $this->v76_tipolancamento != "")
           $resac = db_query("insert into db_acount values($acount,3229,18260,'".AddSlashes(pg_result($resaco,$conresaco,'v76_tipolancamento'))."','$this->v76_tipolancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_dtpagamento"]) || $this->v76_dtpagamento != "")
           $resac = db_query("insert into db_acount values($acount,3229,18261,'".AddSlashes(pg_result($resaco,$conresaco,'v76_dtpagamento'))."','$this->v76_dtpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_obs"]) || $this->v76_obs != "")
           $resac = db_query("insert into db_acount values($acount,3229,18262,'".AddSlashes(pg_result($resaco,$conresaco,'v76_obs'))."','$this->v76_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_valorpartilha"]) || $this->v76_valorpartilha != "")
           $resac = db_query("insert into db_acount values($acount,3229,19195,'".AddSlashes(pg_result($resaco,$conresaco,'v76_valorpartilha'))."','$this->v76_valorpartilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v76_datapartilha"]) || $this->v76_datapartilha != "")
           $resac = db_query("insert into db_acount values($acount,3229,19196,'".AddSlashes(pg_result($resaco,$conresaco,'v76_datapartilha'))."','$this->v76_datapartilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Partilhas do Processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Partilhas do Processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($v76_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v76_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18255,'$v76_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3229,18255,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,18259,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,18260,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_tipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,18261,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_dtpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,18262,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,19195,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_valorpartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3229,19196,'','".AddSlashes(pg_result($resaco,$iresaco,'v76_datapartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforopartilha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v76_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v76_sequencial = $v76_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Partilhas do Processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v76_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Partilhas do Processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v76_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v76_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforopartilha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $v76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforopartilha ";
     $sql .= "      inner join processoforo  on  processoforo.v70_sequencial = processoforopartilha.v76_processoforo";
     $sql .= "      inner join db_config  on  db_config.codigo = processoforo.v70_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = processoforo.v70_id_usuario";
     $sql .= "      inner join vara  on  vara.v53_codvara = processoforo.v70_vara";
     $sql .= "      left  join processoforomov  on  processoforomov.v73_sequencial = processoforo.v70_processoforomov";
     $sql .= "      inner join cartorio  on  cartorio.v82_sequencial = processoforo.v70_cartorio";
     $sql .= "                          and  cartorio.v82_extrajudicial = false";
     $sql2 = "";
     if($dbwhere==""){
       if($v76_sequencial!=null ){
         $sql2 .= " where processoforopartilha.v76_sequencial = $v76_sequencial ";
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
   function sql_query_file ( $v76_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from processoforopartilha ";
     $sql2 = "";
     if($dbwhere==""){
       if($v76_sequencial!=null ){
         $sql2 .= " where processoforopartilha.v76_sequencial = $v76_sequencial ";
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