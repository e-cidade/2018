<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE arquivosimplesimportacao
class cl_arquivosimplesimportacao {
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
   var $q64_sequencial = 0;
   var $q64_nomearquivo = null;
   var $q64_data_dia = null;
   var $q64_data_mes = null;
   var $q64_data_ano = null;
   var $q64_data = null;
   var $q64_processado = 'f';
   var $q64_datalimitevencimentos_dia = null;
   var $q64_datalimitevencimentos_mes = null;
   var $q64_datalimitevencimentos_ano = null;
   var $q64_datalimitevencimentos = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q64_sequencial = int4 = Código Sequencial
                 q64_nomearquivo = varchar(60) = Nome do arquivo
                 q64_data = date = Data de importação
                 q64_processado = bool = Processado
                 q64_datalimitevencimentos = date = Data limite de vencimentos dos débitos
                 ";
   //funcao construtor da classe
   function cl_arquivosimplesimportacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arquivosimplesimportacao");
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
       $this->q64_sequencial = ($this->q64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_sequencial"]:$this->q64_sequencial);
       $this->q64_nomearquivo = ($this->q64_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_nomearquivo"]:$this->q64_nomearquivo);
       if($this->q64_data == ""){
         $this->q64_data_dia = ($this->q64_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_data_dia"]:$this->q64_data_dia);
         $this->q64_data_mes = ($this->q64_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_data_mes"]:$this->q64_data_mes);
         $this->q64_data_ano = ($this->q64_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_data_ano"]:$this->q64_data_ano);
         if($this->q64_data_dia != ""){
            $this->q64_data = $this->q64_data_ano."-".$this->q64_data_mes."-".$this->q64_data_dia;
         }
       }
       $this->q64_processado = ($this->q64_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["q64_processado"]:$this->q64_processado);
       if($this->q64_datalimitevencimentos == ""){
         $this->q64_datalimitevencimentos_dia = ($this->q64_datalimitevencimentos_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_dia"]:$this->q64_datalimitevencimentos_dia);
         $this->q64_datalimitevencimentos_mes = ($this->q64_datalimitevencimentos_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_mes"]:$this->q64_datalimitevencimentos_mes);
         $this->q64_datalimitevencimentos_ano = ($this->q64_datalimitevencimentos_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_ano"]:$this->q64_datalimitevencimentos_ano);
         if($this->q64_datalimitevencimentos_dia != ""){
            $this->q64_datalimitevencimentos = $this->q64_datalimitevencimentos_ano."-".$this->q64_datalimitevencimentos_mes."-".$this->q64_datalimitevencimentos_dia;
         }
       }
     }else{
       $this->q64_sequencial = ($this->q64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q64_sequencial"]:$this->q64_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q64_sequencial){
      $this->atualizacampos();
     if($this->q64_nomearquivo == null ){
       $this->erro_sql = " Campo Nome do arquivo não informado.";
       $this->erro_campo = "q64_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q64_data == null ){
       $this->erro_sql = " Campo Data de importação não informado.";
       $this->erro_campo = "q64_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q64_processado == null ){
       $this->erro_sql = " Campo Processado não informado.";
       $this->erro_campo = "q64_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q64_datalimitevencimentos == null ){
       $this->q64_datalimitevencimentos = "null";
     }
     if($q64_sequencial == "" || $q64_sequencial == null ){
       $result = db_query("select nextval('arquivosimplesimportacao_q64_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arquivosimplesimportacao_q64_sequencial_seq do campo: q64_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q64_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from arquivosimplesimportacao_q64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q64_sequencial)){
         $this->erro_sql = " Campo q64_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q64_sequencial = $q64_sequencial;
       }
     }
     if(($this->q64_sequencial == null) || ($this->q64_sequencial == "") ){
       $this->erro_sql = " Campo q64_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arquivosimplesimportacao(
                                       q64_sequencial
                                      ,q64_nomearquivo
                                      ,q64_data
                                      ,q64_processado
                                      ,q64_datalimitevencimentos
                       )
                values (
                                $this->q64_sequencial
                               ,'$this->q64_nomearquivo'
                               ,".($this->q64_data == "null" || $this->q64_data == ""?"null":"'".$this->q64_data."'")."
                               ,'$this->q64_processado'
                               ,".($this->q64_datalimitevencimentos == "null" || $this->q64_datalimitevencimentos == ""?"null":"'".$this->q64_datalimitevencimentos."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arquivosimplesimportacao ($this->q64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arquivosimplesimportacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arquivosimplesimportacao ($this->q64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q64_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q64_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20326,'$this->q64_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3653,20326,'','".AddSlashes(pg_result($resaco,0,'q64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3653,20328,'','".AddSlashes(pg_result($resaco,0,'q64_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3653,20327,'','".AddSlashes(pg_result($resaco,0,'q64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3653,20329,'','".AddSlashes(pg_result($resaco,0,'q64_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3653,20336,'','".AddSlashes(pg_result($resaco,0,'q64_datalimitevencimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($q64_sequencial=null) {
      $this->atualizacampos();
     $sql = " update arquivosimplesimportacao set ";
     $virgula = "";
     if(trim($this->q64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q64_sequencial"])){
       $sql  .= $virgula." q64_sequencial = $this->q64_sequencial ";
       $virgula = ",";
       if(trim($this->q64_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "q64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q64_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q64_nomearquivo"])){
       $sql  .= $virgula." q64_nomearquivo = '$this->q64_nomearquivo' ";
       $virgula = ",";
       if(trim($this->q64_nomearquivo) == null ){
         $this->erro_sql = " Campo Nome do arquivo não informado.";
         $this->erro_campo = "q64_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q64_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q64_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q64_data_dia"] !="") ){
       $sql  .= $virgula." q64_data = '$this->q64_data' ";
       $virgula = ",";
       if(trim($this->q64_data) == null ){
         $this->erro_sql = " Campo Data de importação não informado.";
         $this->erro_campo = "q64_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q64_data_dia"])){
         $sql  .= $virgula." q64_data = null ";
         $virgula = ",";
         if(trim($this->q64_data) == null ){
           $this->erro_sql = " Campo Data de importação não informado.";
           $this->erro_campo = "q64_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q64_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q64_processado"])){
       $sql  .= $virgula." q64_processado = '$this->q64_processado' ";
       $virgula = ",";
       if(trim($this->q64_processado) == null ){
         $this->erro_sql = " Campo Processado não informado.";
         $this->erro_campo = "q64_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q64_datalimitevencimentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_dia"] !="") ){
       $sql  .= $virgula." q64_datalimitevencimentos = '$this->q64_datalimitevencimentos' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos_dia"])){
         $sql  .= $virgula." q64_datalimitevencimentos = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($q64_sequencial!=null){
       $sql .= " q64_sequencial = $this->q64_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q64_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20326,'$this->q64_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q64_sequencial"]) || $this->q64_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3653,20326,'".AddSlashes(pg_result($resaco,$conresaco,'q64_sequencial'))."','$this->q64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q64_nomearquivo"]) || $this->q64_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3653,20328,'".AddSlashes(pg_result($resaco,$conresaco,'q64_nomearquivo'))."','$this->q64_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q64_data"]) || $this->q64_data != "")
             $resac = db_query("insert into db_acount values($acount,3653,20327,'".AddSlashes(pg_result($resaco,$conresaco,'q64_data'))."','$this->q64_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q64_processado"]) || $this->q64_processado != "")
             $resac = db_query("insert into db_acount values($acount,3653,20329,'".AddSlashes(pg_result($resaco,$conresaco,'q64_processado'))."','$this->q64_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q64_datalimitevencimentos"]) || $this->q64_datalimitevencimentos != "")
             $resac = db_query("insert into db_acount values($acount,3653,20336,'".AddSlashes(pg_result($resaco,$conresaco,'q64_datalimitevencimentos'))."','$this->q64_datalimitevencimentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($q64_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($q64_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20326,'$q64_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3653,20326,'','".AddSlashes(pg_result($resaco,$iresaco,'q64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3653,20328,'','".AddSlashes(pg_result($resaco,$iresaco,'q64_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3653,20327,'','".AddSlashes(pg_result($resaco,$iresaco,'q64_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3653,20329,'','".AddSlashes(pg_result($resaco,$iresaco,'q64_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3653,20336,'','".AddSlashes(pg_result($resaco,$iresaco,'q64_datalimitevencimentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from arquivosimplesimportacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q64_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q64_sequencial = $q64_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arquivosimplesimportacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $q64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arquivosimplesimportacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($q64_sequencial!=null ){
         $sql2 .= " where arquivosimplesimportacao.q64_sequencial = $q64_sequencial ";
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
   function sql_query_file ( $q64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from arquivosimplesimportacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($q64_sequencial!=null ){
         $sql2 .= " where arquivosimplesimportacao.q64_sequencial = $q64_sequencial ";
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

  function getCnaes ( $iArquivoSimplesImportacao = '', $lValidos = true ) {

    $sSql  = "select distinct q71_estrutural, q71_descr                                                                                            ";
    $sSql .= "           from cnae                                                                                                                 ";
    $sSql .= "          where substr ( trim(q71_estrutural ), 1, 1 )                                                                               ";
    $sSql .= "                       IN ( select distinct on ( substr( trim(q71_estrutural ), 1, 1 ) ) substr( trim(q71_estrutural ), 1, 1 )       ";
    $sSql .= "                                       from cnae                                                                                     ";
    $sSql .= "                                      where exists ( select 1                                                                        ";
    $sSql .= "                                                       from arquivosimplesimportacaodetalhe                                          ";
    $sSql .= "                                                      where q142_cnae = substr( trim(q71_estrutural ), 2, length( q71_estrutural ) ) ";
    $sSql .= "                                                        and q142_arquivosimplesimportacao = $iArquivoSimplesImportacao ) )           ";
    $sSql .= "           and length(q71_estrutural) = 1                                                                                            ";
    $sSql .= "      order by q71_descr                                                                                                             ";

    if( !$lValidos ) {

      $sSql  = "select *                                                                                    ";
      $sSql .= "  from arquivosimplesimportacaodetalhe                                                      ";
      $sSql .= " where q142_arquivosimplesimportacao = $iArquivoSimplesImportacao                           ";
      $sSql .= "   and q142_cnae not in ( select substr( trim(q71_estrutural ), 2, length( q71_estrutural ))";
      $sSql .= "                            from cnae )                                                     ";
    }

    return $sSql;
  }

  function getEmpresabyCnaes ( $sCodigoSecao, $iArquivoSimplesImportacao ){

    $sSql  = "select *                                                                                      ";
    $sSql .= "  from arquivosimplesimportacaodetalhe                                                        ";
    $sSql .= " where q142_cnae IN ( select substr( trim( q71_estrutural ), 2, length( q71_estrutural ) )    ";
    $sSql .= "                        from cnae                                                             ";
    $sSql .= "                       where substr( trim( q71_estrutural ), 1, 1 )  IN ( '$sCodigoSecao' ) ) ";
    $sSql .= "   and q142_arquivosimplesimportacao = $iArquivoSimplesImportacao                             ";

    return $sSql;
  }
}
?>