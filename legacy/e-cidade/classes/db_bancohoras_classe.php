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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE bancohoras
class cl_bancohoras {
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
   var $rh126_sequencial = 0;
   var $rh126_regist = 0;
   var $rh126_soma = 'f';
   var $rh126_data_dia = null;
   var $rh126_data_mes = null;
   var $rh126_data_ano = null;
   var $rh126_data = null;
   var $rh126_horas = 0;
   var $rh126_minutos = 0;
   var $rh126_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh126_sequencial = int8 = Sequencial
                 rh126_regist = int4 = Servidor
                 rh126_soma = bool = Tipo Lançamento
                 rh126_data = date = Data
                 rh126_horas = int4 = Horas
                 rh126_minutos = int4 = Minutos
                 rh126_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_bancohoras() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bancohoras");
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
       $this->rh126_sequencial = ($this->rh126_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_sequencial"]:$this->rh126_sequencial);
       $this->rh126_regist = ($this->rh126_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_regist"]:$this->rh126_regist);
       $this->rh126_soma = ($this->rh126_soma == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh126_soma"]:$this->rh126_soma);
       if($this->rh126_data == ""){
         $this->rh126_data_dia = ($this->rh126_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_data_dia"]:$this->rh126_data_dia);
         $this->rh126_data_mes = ($this->rh126_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_data_mes"]:$this->rh126_data_mes);
         $this->rh126_data_ano = ($this->rh126_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_data_ano"]:$this->rh126_data_ano);
         if($this->rh126_data_dia != ""){
            $this->rh126_data = $this->rh126_data_ano."-".$this->rh126_data_mes."-".$this->rh126_data_dia;
         }
       }
       $this->rh126_horas = ($this->rh126_horas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_horas"]:$this->rh126_horas);
       $this->rh126_minutos = ($this->rh126_minutos == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_minutos"]:$this->rh126_minutos);
       $this->rh126_observacao = ($this->rh126_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_observacao"]:$this->rh126_observacao);
     }else{
       $this->rh126_sequencial = ($this->rh126_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh126_sequencial"]:$this->rh126_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh126_sequencial){
      $this->atualizacampos();
     if($this->rh126_regist == null ){
       $this->erro_sql = " Campo Servidor não informado.";
       $this->erro_campo = "rh126_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh126_soma == null ){
       $this->erro_sql = " Campo Tipo Lançamento não informado.";
       $this->erro_campo = "rh126_soma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh126_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "rh126_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh126_horas == null ){
       $this->erro_sql = " Campo Horas não informado.";
       $this->erro_campo = "rh126_horas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh126_minutos == null ){
       $this->erro_sql = " Campo Minutos não informado.";
       $this->erro_campo = "rh126_minutos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh126_sequencial == "" || $rh126_sequencial == null ){
       $result = db_query("select nextval('bancohoras_rh126_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bancohoras_rh126_sequencial_seq do campo: rh126_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh126_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from bancohoras_rh126_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh126_sequencial)){
         $this->erro_sql = " Campo rh126_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh126_sequencial = $rh126_sequencial;
       }
     }
     if(($this->rh126_sequencial == null) || ($this->rh126_sequencial == "") ){
       $this->erro_sql = " Campo rh126_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bancohoras(
                                       rh126_sequencial
                                      ,rh126_regist
                                      ,rh126_soma
                                      ,rh126_data
                                      ,rh126_horas
                                      ,rh126_minutos
                                      ,rh126_observacao
                       )
                values (
                                $this->rh126_sequencial
                               ,$this->rh126_regist
                               ,'$this->rh126_soma'
                               ,".($this->rh126_data == "null" || $this->rh126_data == ""?"null":"'".$this->rh126_data."'")."
                               ,$this->rh126_horas
                               ,$this->rh126_minutos
                               ,'$this->rh126_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bancohoras ($this->rh126_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "bancohoras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "bancohoras ($this->rh126_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh126_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh126_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20350,'$this->rh126_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3656,20350,'','".AddSlashes(pg_result($resaco,0,'rh126_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20345,'','".AddSlashes(pg_result($resaco,0,'rh126_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20354,'','".AddSlashes(pg_result($resaco,0,'rh126_soma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20346,'','".AddSlashes(pg_result($resaco,0,'rh126_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20347,'','".AddSlashes(pg_result($resaco,0,'rh126_horas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20349,'','".AddSlashes(pg_result($resaco,0,'rh126_minutos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3656,20348,'','".AddSlashes(pg_result($resaco,0,'rh126_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($rh126_sequencial=null) {
      $this->atualizacampos();
     $sql = " update bancohoras set ";
     $virgula = "";
     if(trim($this->rh126_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_sequencial"])){
       $sql  .= $virgula." rh126_sequencial = $this->rh126_sequencial ";
       $virgula = ",";
       if(trim($this->rh126_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh126_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh126_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_regist"])){
       $sql  .= $virgula." rh126_regist = $this->rh126_regist ";
       $virgula = ",";
       if(trim($this->rh126_regist) == null ){
         $this->erro_sql = " Campo Servidor não informado.";
         $this->erro_campo = "rh126_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh126_soma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_soma"])){
       $sql  .= $virgula." rh126_soma = '$this->rh126_soma' ";
       $virgula = ",";
       if(trim($this->rh126_soma) == null ){
         $this->erro_sql = " Campo Tipo Lançamento não informado.";
         $this->erro_campo = "rh126_soma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh126_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh126_data_dia"] !="") ){
       $sql  .= $virgula." rh126_data = '$this->rh126_data' ";
       $virgula = ",";
       if(trim($this->rh126_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "rh126_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_data_dia"])){
         $sql  .= $virgula." rh126_data = null ";
         $virgula = ",";
         if(trim($this->rh126_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "rh126_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh126_horas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_horas"])){
       $sql  .= $virgula." rh126_horas = $this->rh126_horas ";
       $virgula = ",";
       if(trim($this->rh126_horas) == null ){
         $this->erro_sql = " Campo Horas não informado.";
         $this->erro_campo = "rh126_horas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh126_minutos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh126_minutos"])){
       $sql  .= $virgula." rh126_minutos = $this->rh126_minutos ";
       $virgula = ",";
       if(trim($this->rh126_minutos) == null ){
         $this->erro_sql = " Campo Minutos não informado.";
         $this->erro_campo = "rh126_minutos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if( $this->rh126_minutos || isset($GLOBALS["HTTP_POST_VARS"]["rh126_observacao"])){
       $sql  .= $virgula." rh126_observacao = '$this->rh126_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh126_sequencial!=null){
       $sql .= " rh126_sequencial = $this->rh126_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh126_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20350,'$this->rh126_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_sequencial"]) || $this->rh126_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3656,20350,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_sequencial'))."','$this->rh126_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_regist"]) || $this->rh126_regist != "")
             $resac = db_query("insert into db_acount values($acount,3656,20345,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_regist'))."','$this->rh126_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_soma"]) || $this->rh126_soma != "")
             $resac = db_query("insert into db_acount values($acount,3656,20354,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_soma'))."','$this->rh126_soma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_data"]) || $this->rh126_data != "")
             $resac = db_query("insert into db_acount values($acount,3656,20346,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_data'))."','$this->rh126_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_horas"]) || $this->rh126_horas != "")
             $resac = db_query("insert into db_acount values($acount,3656,20347,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_horas'))."','$this->rh126_horas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_minutos"]) || $this->rh126_minutos != "")
             $resac = db_query("insert into db_acount values($acount,3656,20349,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_minutos'))."','$this->rh126_minutos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh126_observacao"]) || $this->rh126_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3656,20348,'".AddSlashes(pg_result($resaco,$conresaco,'rh126_observacao'))."','$this->rh126_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bancohoras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh126_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bancohoras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh126_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh126_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($rh126_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh126_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20350,'$rh126_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3656,20350,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20345,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20354,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_soma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20346,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20347,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_horas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20349,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_minutos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3656,20348,'','".AddSlashes(pg_result($resaco,$iresaco,'rh126_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bancohoras
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh126_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh126_sequencial = $rh126_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bancohoras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh126_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bancohoras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh126_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh126_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bancohoras";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $rh126_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bancohoras ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = bancohoras.rh126_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql2 = "";
     if($dbwhere==""){
       if($rh126_sequencial!=null ){
         $sql2 .= " where bancohoras.rh126_sequencial = $rh126_sequencial ";
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
   function sql_query_file ( $rh126_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from bancohoras ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh126_sequencial!=null ){
         $sql2 .= " where bancohoras.rh126_sequencial = $rh126_sequencial ";
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