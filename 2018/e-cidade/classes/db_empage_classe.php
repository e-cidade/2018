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
//CLASSE DA ENTIDADE empage
class cl_empage {
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
   var $e80_codage = 0;
   var $e80_data_dia = null;
   var $e80_data_mes = null;
   var $e80_data_ano = null;
   var $e80_data = null;
   var $e80_cancelado_dia = null;
   var $e80_cancelado_mes = null;
   var $e80_cancelado_ano = null;
   var $e80_cancelado = null;
   var $e80_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e80_codage = int4 = Agenda
                 e80_data = date = Data
                 e80_cancelado = date = Data cancelamento
                 e80_instit = int4 = Cod. Instituição
                 ";
   //funcao construtor da classe
   function cl_empage() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empage");
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
       $this->e80_codage = ($this->e80_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_codage"]:$this->e80_codage);
       if($this->e80_data == ""){
         $this->e80_data_dia = ($this->e80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_data_dia"]:$this->e80_data_dia);
         $this->e80_data_mes = ($this->e80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_data_mes"]:$this->e80_data_mes);
         $this->e80_data_ano = ($this->e80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_data_ano"]:$this->e80_data_ano);
         if($this->e80_data_dia != ""){
            $this->e80_data = $this->e80_data_ano."-".$this->e80_data_mes."-".$this->e80_data_dia;
         }
       }
       if($this->e80_cancelado == ""){
         $this->e80_cancelado_dia = ($this->e80_cancelado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_cancelado_dia"]:$this->e80_cancelado_dia);
         $this->e80_cancelado_mes = ($this->e80_cancelado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_cancelado_mes"]:$this->e80_cancelado_mes);
         $this->e80_cancelado_ano = ($this->e80_cancelado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_cancelado_ano"]:$this->e80_cancelado_ano);
         if($this->e80_cancelado_dia != ""){
            $this->e80_cancelado = $this->e80_cancelado_ano."-".$this->e80_cancelado_mes."-".$this->e80_cancelado_dia;
         }
       }
       $this->e80_instit = ($this->e80_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_instit"]:$this->e80_instit);
     }else{
       $this->e80_codage = ($this->e80_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["e80_codage"]:$this->e80_codage);
     }
   }
   // funcao para inclusao
   function incluir ($e80_codage){
      $this->atualizacampos();
     if($this->e80_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e80_cancelado == null ){
       $this->e80_cancelado = "null";
     }
     if($this->e80_instit == null ){
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "e80_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e80_codage == "" || $e80_codage == null ){
       $result = db_query("select nextval('empage_e80_codage_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empage_e80_codage_seq do campo: e80_codage";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e80_codage = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empage_e80_codage_seq");
       if(($result != false) && (pg_result($result,0,0) < $e80_codage)){
         $this->erro_sql = " Campo e80_codage maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e80_codage = $e80_codage;
       }
     }
     if(($this->e80_codage == null) || ($this->e80_codage == "") ){
       $this->erro_sql = " Campo e80_codage nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empage(
                                       e80_codage
                                      ,e80_data
                                      ,e80_cancelado
                                      ,e80_instit
                       )
                values (
                                $this->e80_codage
                               ,".($this->e80_data == "null" || $this->e80_data == ""?"null":"'".$this->e80_data."'")."
                               ,".($this->e80_cancelado == "null" || $this->e80_cancelado == ""?"null":"'".$this->e80_cancelado."'")."
                               ,$this->e80_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda ($this->e80_codage) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda ($this->e80_codage) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e80_codage;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e80_codage));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6169,'$this->e80_codage','I')");
       $resac = db_query("insert into db_acount values($acount,994,6169,'','".AddSlashes(pg_result($resaco,0,'e80_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,994,6170,'','".AddSlashes(pg_result($resaco,0,'e80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,994,6171,'','".AddSlashes(pg_result($resaco,0,'e80_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,994,10139,'','".AddSlashes(pg_result($resaco,0,'e80_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e80_codage=null) {
      $this->atualizacampos();
     $sql = " update empage set ";
     $virgula = "";
     if(trim($this->e80_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e80_codage"])){
       $sql  .= $virgula." e80_codage = $this->e80_codage ";
       $virgula = ",";
       if(trim($this->e80_codage) == null ){
         $this->erro_sql = " Campo Agenda nao Informado.";
         $this->erro_campo = "e80_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e80_data_dia"] !="") ){
       $sql  .= $virgula." e80_data = '$this->e80_data' ";
       $virgula = ",";
       if(trim($this->e80_data) == null ){
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e80_data_dia"])){
         $sql  .= $virgula." e80_data = null ";
         $virgula = ",";
         if(trim($this->e80_data) == null ){
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e80_cancelado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e80_cancelado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e80_cancelado_dia"] !="") ){
       $sql  .= $virgula." e80_cancelado = '$this->e80_cancelado' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["e80_cancelado_dia"])){
         $sql  .= $virgula." e80_cancelado = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e80_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e80_instit"])){
       $sql  .= $virgula." e80_instit = $this->e80_instit ";
       $virgula = ",";
       if(trim($this->e80_instit) == null ){
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "e80_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e80_codage!=null){
       $sql .= " e80_codage = $this->e80_codage";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e80_codage));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6169,'$this->e80_codage','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e80_codage"]))
           $resac = db_query("insert into db_acount values($acount,994,6169,'".AddSlashes(pg_result($resaco,$conresaco,'e80_codage'))."','$this->e80_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e80_data"]))
           $resac = db_query("insert into db_acount values($acount,994,6170,'".AddSlashes(pg_result($resaco,$conresaco,'e80_data'))."','$this->e80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e80_cancelado"]))
           $resac = db_query("insert into db_acount values($acount,994,6171,'".AddSlashes(pg_result($resaco,$conresaco,'e80_cancelado'))."','$this->e80_cancelado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e80_instit"]))
           $resac = db_query("insert into db_acount values($acount,994,10139,'".AddSlashes(pg_result($resaco,$conresaco,'e80_instit'))."','$this->e80_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e80_codage;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e80_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e80_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e80_codage=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e80_codage));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6169,'$e80_codage','E')");
         $resac = db_query("insert into db_acount values($acount,994,6169,'','".AddSlashes(pg_result($resaco,$iresaco,'e80_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,994,6170,'','".AddSlashes(pg_result($resaco,$iresaco,'e80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,994,6171,'','".AddSlashes(pg_result($resaco,$iresaco,'e80_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,994,10139,'','".AddSlashes(pg_result($resaco,$iresaco,'e80_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empage
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e80_codage != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e80_codage = $e80_codage ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e80_codage;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e80_codage;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e80_codage;
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
        $this->erro_sql   = "Record Vazio na Tabela:empage";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empage ";
     $sql .= "      inner join db_config  on  db_config.codigo = empage.e80_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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
   function sql_query_cons ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empagemov ";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov";
     $sql .= "      left join empageconf  on empageconf.e86_codmov = empagemov.e81_codmov";
     $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      left join empagegera on empagegera.e87_codgera= empageconfgera.e90_codgera ";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp ";
     $sql .= "      left join cgm           on  cgm.z01_numcgm = empempenho.e60_numcgm ";
     $sql .= "      left join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov ";
     $sql .= "      left join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo ";
     $sql .= "      left join saltes  on  saltes.k13_conta = empagetipo.e83_conta ";
     $sql .= "      left join empageconfche  on  e91_codmov = empagemov.e81_codmov and e91_ativo is true ";
     $sql .= "      left join empord  on  empord.e82_codmov = empagemov.e81_codmov ";
     $sql .= "      left join empageslip  on  e89_codmov = empagemov.e81_codmov ";
     $sql .= "      left join slip      on  k17_codigo = empageslip.e89_codigo ";
     $sql .= "      left join slipnum   on  slipnum.k17_codigo = slip.k17_codigo ";
     $sql .= "      left join cgm cgmslip  on  slipnum.k17_numcgm = cgmslip.z01_numcgm ";
     $sql .= "      left join pagordemconta on e49_codord = e82_codord ";
     $sql .= "      left join pagordemele on e53_codord = e82_codord ";
     $sql .= "      left join cgm a on a.z01_numcgm = e49_numcgm ";
     $sql .= "      left join empagemovconta on empagemovconta.e98_codmov = empagemov.e81_codmov ";
     $sql .= "      left join pcfornecon on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco ";
     $sql .= "      left join empagedadosretmov on empagedadosretmov.e76_codmov = empagemov.e81_codmov ";
     $sql .= "      left join empagedadosret on empagedadosret.e75_codret = empagedadosretmov.e76_codret ";
     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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
   function sql_query_file ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empage ";
     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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
   function sql_query_pagam ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empagemov ";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageconf  on empageconf.e86_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      left  join empagegera on empagegera.e87_codgera= empageconfgera.e90_codgera ";
     $sql .= "      left  join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      left  join saltes  on  saltes.k13_conta = empagetipo.e83_conta";
     $sql .= "      left  join empageconfche  on  e91_codmov = empagemov.e81_codmov and e91_ativo is true ";
     $sql .= "      left  join empord  on  empord.e82_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageslip  on  e89_codmov = empagemov.e81_codmov";
     $sql .= "      left  join pagordemconta on e49_codord = e82_codord ";
     $sql .= "      left  join pagordemele on e53_codord = e82_codord ";
     $sql .= "      left  join cgm a on a.z01_numcgm = e49_numcgm ";
     $sql .= "      left  join slipnum  on slipnum.k17_codigo = e89_codigo";
     $sql .= "      left  join slip     on slipnum.k17_codigo = slip.k17_codigo";
     $sql .= "      left  join cgm cgmslip on cgmslip.z01_numcgm = k17_numcgm ";
     $sql .= "      left  join empagemovconta on empagemovconta.e98_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join pcfornecon on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco ";
     $sql .= "      left  join empagedadosretmov on empagedadosretmov.e76_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join empagedadosret on empagedadosret.e75_codret = empagedadosretmov.e76_codret ";
     $sql .= "      left  join empagedadosretmovocorrencia  on empagedadosretmovocorrencia.e02_empagedadosret    = empagedadosretmov.e76_codret";
     $sql .= "                                             and empagedadosretmovocorrencia.e02_empagedadosretmov = empagedadosretmov.e76_codmov";
     $sql .= "      left  join errobanco on errobanco.e92_sequencia = empagedadosretmovocorrencia.e02_errobanco ";

     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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
   function sql_query_rel_arqretorno ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empagemov ";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join empageconf  on empageconf.e86_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      left  join empagegera on empagegera.e87_codgera= empageconfgera.e90_codgera ";
     $sql .= "      left  join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left  join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      left  join saltes  on  saltes.k13_conta = empagetipo.e83_conta";
     $sql .= "      left  join empageconfche  on  e91_codmov = empagemov.e81_codmov and e91_ativo is true ";
     $sql .= "      left  join empord  on  empord.e82_codmov = empagemov.e81_codmov";
     $sql .= "      left  join empageslip  on  e89_codmov = empagemov.e81_codmov";
     $sql .= "      left  join pagordemconta on e49_codord = e82_codord ";
     $sql .= "      left  join pagordemele on e53_codord = e82_codord ";
     $sql .= "      left  join cgm a on a.z01_numcgm = e49_numcgm ";
     $sql .= "      left  join slipnum  on k17_codigo = e89_codigo";
     $sql .= "      left  join cgm cgmslip on cgmslip.z01_numcgm = k17_numcgm ";
     $sql .= "      left  join empagemovconta on empagemovconta.e98_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join pcfornecon on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco ";
     $sql .= "      left  join empagedadosretmov on empagedadosretmov.e76_codmov = empagemov.e81_codmov ";
     $sql .= "      left  join empagedadosret on empagedadosret.e75_codret = empagedadosretmov.e76_codret ";
     $sql .= "      left  join empagedadosretmovocorrencia  on empagedadosretmovocorrencia.e02_empagedadosret = empagedadosretmov.e76_codret ";
     $sql .= "                                             and empagedadosretmovocorrencia.e02_empagedadosretmov = empagedadosretmov.e76_codmov";
     $sql .= "      left  join errobanco on e02_errobanco = e92_sequencia";

     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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


   function sql_query_rel ( $e80_codage=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empagemov ";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left join empageconf  on empageconf.e86_codmov = empagemov.e81_codmov";
     $sql .= "      left join empageconfgera on empageconfgera.e90_codmov = empageconf.e86_codmov ";
     $sql .= "      left join empagegera on empagegera.e87_codgera= empageconfgera.e90_codgera ";
     $sql .= "      left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      left join empagepag  on  empagepag.e85_codmov = empagemov.e81_codmov";
     $sql .= "      left join empagetipo  on  empagetipo.e83_codtipo = empagepag.e85_codtipo";
     $sql .= "      left join saltes  on  saltes.k13_conta = empagetipo.e83_conta";
     $sql .= "      left join empageconfche  on  e91_codmov = empagemov.e81_codmov and e91_ativo is true ";
     $sql .= "      left join empord  on  empord.e82_codmov = empagemov.e81_codmov";
     $sql .= "      left join pagordem  on  empord.e82_codord = pagordem.e50_codord";
     $sql .= "      left join pagordemele  on  pagordemele.e53_codord = pagordem.e50_codord";
     $sql .= "      left join empageslip  on  e89_codmov = empagemov.e81_codmov";
     $sql .= "      left join pagordemconta on e49_codord = e82_codord ";
     $sql .= "      left join cgm a on a.z01_numcgm = e49_numcgm ";
     $sql .= "      left join empagemovconta on empagemovconta.e98_codmov = empagemov.e81_codmov ";
     $sql .= "      left join pcfornecon on pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco ";
     $sql .= "      left join empagedadosretmov on empagedadosretmov.e76_codmov = empagemov.e81_codmov ";
     $sql .= "      left join empagedadosret on empagedadosret.e75_codret = empagedadosretmov.e76_codret ";
     $sql2 = "";
     if($dbwhere==""){
       if($e80_codage!=null ){
         $sql2 .= " where empage.e80_codage = $e80_codage ";
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