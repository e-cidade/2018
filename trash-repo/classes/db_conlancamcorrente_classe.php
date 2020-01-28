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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamcorrente
class cl_conlancamcorrente {
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
   var $c86_sequencial = 0;
   var $c86_id = 0;
   var $c86_data_dia = null;
   var $c86_data_mes = null;
   var $c86_data_ano = null;
   var $c86_data = null;
   var $c86_autent = 0;
   var $c86_conlancam = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c86_sequencial = int4 = Sequencial conlancamcorrente
                 c86_id = int4 = Sequencial ID
                 c86_data = date = Data
                 c86_autent = int4 = autent
                 c86_conlancam = int4 = conlancam
                 ";
   //funcao construtor da classe
   function cl_conlancamcorrente() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamcorrente");
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
       $this->c86_sequencial = ($this->c86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_sequencial"]:$this->c86_sequencial);
       $this->c86_id = ($this->c86_id == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_id"]:$this->c86_id);
       if($this->c86_data == ""){
         $this->c86_data_dia = ($this->c86_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_data_dia"]:$this->c86_data_dia);
         $this->c86_data_mes = ($this->c86_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_data_mes"]:$this->c86_data_mes);
         $this->c86_data_ano = ($this->c86_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_data_ano"]:$this->c86_data_ano);
         if($this->c86_data_dia != ""){
            $this->c86_data = $this->c86_data_ano."-".$this->c86_data_mes."-".$this->c86_data_dia;
         }
       }
       $this->c86_autent = ($this->c86_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_autent"]:$this->c86_autent);
       $this->c86_conlancam = ($this->c86_conlancam == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_conlancam"]:$this->c86_conlancam);
     }else{
       $this->c86_sequencial = ($this->c86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c86_sequencial"]:$this->c86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c86_sequencial){
      $this->atualizacampos();
     if($this->c86_id == null ){
       $this->erro_sql = " Campo Sequencial ID nao Informado.";
       $this->erro_campo = "c86_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c86_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c86_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c86_autent == null ){
       $this->erro_sql = " Campo autent nao Informado.";
       $this->erro_campo = "c86_autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c86_conlancam == null ){
       $this->erro_sql = " Campo conlancam nao Informado.";
       $this->erro_campo = "c86_conlancam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c86_sequencial == "" || $c86_sequencial == null ){
       $result = db_query("select nextval('conlancamcorrente_c86_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamcorrente_c86_sequencial_seq do campo: c86_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c86_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conlancamcorrente_c86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c86_sequencial)){
         $this->erro_sql = " Campo c86_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c86_sequencial = $c86_sequencial;
       }
     }
     if(($this->c86_sequencial == null) || ($this->c86_sequencial == "") ){
       $this->erro_sql = " Campo c86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamcorrente(
                                       c86_sequencial
                                      ,c86_id
                                      ,c86_data
                                      ,c86_autent
                                      ,c86_conlancam
                       )
                values (
                                $this->c86_sequencial
                               ,$this->c86_id
                               ,".($this->c86_data == "null" || $this->c86_data == ""?"null":"'".$this->c86_data."'")."
                               ,$this->c86_autent
                               ,$this->c86_conlancam
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "conlancamcorrente ($this->c86_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "conlancamcorrente j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "conlancamcorrente ($this->c86_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c86_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c86_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19804,'$this->c86_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3551,19804,'','".AddSlashes(pg_result($resaco,0,'c86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3551,19805,'','".AddSlashes(pg_result($resaco,0,'c86_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3551,19806,'','".AddSlashes(pg_result($resaco,0,'c86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3551,19807,'','".AddSlashes(pg_result($resaco,0,'c86_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3551,19808,'','".AddSlashes(pg_result($resaco,0,'c86_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($c86_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conlancamcorrente set ";
     $virgula = "";
     if(trim($this->c86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c86_sequencial"])){
       $sql  .= $virgula." c86_sequencial = $this->c86_sequencial ";
       $virgula = ",";
       if(trim($this->c86_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial conlancamcorrente nao Informado.";
         $this->erro_campo = "c86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c86_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c86_id"])){
       $sql  .= $virgula." c86_id = $this->c86_id ";
       $virgula = ",";
       if(trim($this->c86_id) == null ){
         $this->erro_sql = " Campo Sequencial ID nao Informado.";
         $this->erro_campo = "c86_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c86_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c86_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c86_data_dia"] !="") ){
       $sql  .= $virgula." c86_data = '$this->c86_data' ";
       $virgula = ",";
       if(trim($this->c86_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c86_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["c86_data_dia"])){
         $sql  .= $virgula." c86_data = null ";
         $virgula = ",";
         if(trim($this->c86_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c86_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c86_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c86_autent"])){
       $sql  .= $virgula." c86_autent = $this->c86_autent ";
       $virgula = ",";
       if(trim($this->c86_autent) == null ){
         $this->erro_sql = " Campo autent nao Informado.";
         $this->erro_campo = "c86_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c86_conlancam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c86_conlancam"])){
       $sql  .= $virgula." c86_conlancam = $this->c86_conlancam ";
       $virgula = ",";
       if(trim($this->c86_conlancam) == null ){
         $this->erro_sql = " Campo conlancam nao Informado.";
         $this->erro_campo = "c86_conlancam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c86_sequencial!=null){
       $sql .= " c86_sequencial = $this->c86_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->c86_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19804,'$this->c86_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c86_sequencial"]) || $this->c86_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3551,19804,'".AddSlashes(pg_result($resaco,$conresaco,'c86_sequencial'))."','$this->c86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c86_id"]) || $this->c86_id != "")
             $resac = db_query("insert into db_acount values($acount,3551,19805,'".AddSlashes(pg_result($resaco,$conresaco,'c86_id'))."','$this->c86_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c86_data"]) || $this->c86_data != "")
             $resac = db_query("insert into db_acount values($acount,3551,19806,'".AddSlashes(pg_result($resaco,$conresaco,'c86_data'))."','$this->c86_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c86_autent"]) || $this->c86_autent != "")
             $resac = db_query("insert into db_acount values($acount,3551,19807,'".AddSlashes(pg_result($resaco,$conresaco,'c86_autent'))."','$this->c86_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c86_conlancam"]) || $this->c86_conlancam != "")
             $resac = db_query("insert into db_acount values($acount,3551,19808,'".AddSlashes(pg_result($resaco,$conresaco,'c86_conlancam'))."','$this->c86_conlancam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conlancamcorrente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c86_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conlancamcorrente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c86_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c86_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($c86_sequencial=null,$dbwhere=null) {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($c86_sequencial));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19804,'$c86_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3551,19804,'','".AddSlashes(pg_result($resaco,$iresaco,'c86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3551,19805,'','".AddSlashes(pg_result($resaco,$iresaco,'c86_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3551,19806,'','".AddSlashes(pg_result($resaco,$iresaco,'c86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3551,19807,'','".AddSlashes(pg_result($resaco,$iresaco,'c86_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3551,19808,'','".AddSlashes(pg_result($resaco,$iresaco,'c86_conlancam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamcorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c86_sequencial = $c86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conlancamcorrente nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c86_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conlancamcorrente nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c86_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c86_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conlancamcorrente";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamcorrente ";
     $sql .= "      inner join corrente  on  corrente.k12_id = conlancamcorrente.c86_id and  corrente.k12_data = conlancamcorrente.c86_data and  corrente.k12_autent = conlancamcorrente.c86_autent";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamcorrente.c86_conlancam";
     $sql .= "      inner join db_config  on  db_config.codigo = corrente.k12_instit";
     $sql .= "      inner join cfautent  on  cfautent.k11_id = corrente.k12_id";
     $sql2 = "";
     if($dbwhere==""){
       if($c86_sequencial!=null ){
         $sql2 .= " where conlancamcorrente.c86_sequencial = $c86_sequencial ";
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
   function sql_query_file ( $c86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conlancamcorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($c86_sequencial!=null ){
         $sql2 .= " where conlancamcorrente.c86_sequencial = $c86_sequencial ";
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