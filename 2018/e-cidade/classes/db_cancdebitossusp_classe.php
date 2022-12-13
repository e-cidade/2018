<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE cancdebitossusp
class cl_cancdebitossusp { 
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
   var $ar21_sequencial = 0; 
   var $ar21_suspensaofinaliza = 0; 
   var $ar21_cancdebitos = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar21_sequencial = int4 = Sequencial 
                 ar21_suspensaofinaliza = int4 = Suspensão Finalizada 
                 ar21_cancdebitos = int4 = Débitos Cancelados 
                 ";
   //funcao construtor da classe 
   function cl_cancdebitossusp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitossusp"); 
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
       $this->ar21_sequencial = ($this->ar21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar21_sequencial"]:$this->ar21_sequencial);
       $this->ar21_suspensaofinaliza = ($this->ar21_suspensaofinaliza == ""?@$GLOBALS["HTTP_POST_VARS"]["ar21_suspensaofinaliza"]:$this->ar21_suspensaofinaliza);
       $this->ar21_cancdebitos = ($this->ar21_cancdebitos == ""?@$GLOBALS["HTTP_POST_VARS"]["ar21_cancdebitos"]:$this->ar21_cancdebitos);
     }else{
       $this->ar21_sequencial = ($this->ar21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar21_sequencial"]:$this->ar21_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar21_sequencial){ 
      $this->atualizacampos();
     if($this->ar21_suspensaofinaliza == null ){ 
       $this->erro_sql = " Campo Suspensão Finalizada nao Informado.";
       $this->erro_campo = "ar21_suspensaofinaliza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar21_cancdebitos == null ){ 
       $this->erro_sql = " Campo Débitos Cancelados nao Informado.";
       $this->erro_campo = "ar21_cancdebitos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar21_sequencial == "" || $ar21_sequencial == null ){
       $result = db_query("select nextval('cancdebitossusp_ar21_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitossusp_ar21_sequencial_seq do campo: ar21_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar21_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdebitossusp_ar21_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar21_sequencial)){
         $this->erro_sql = " Campo ar21_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar21_sequencial = $ar21_sequencial; 
       }
     }
     if(($this->ar21_sequencial == null) || ($this->ar21_sequencial == "") ){ 
       $this->erro_sql = " Campo ar21_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitossusp(
                                       ar21_sequencial 
                                      ,ar21_suspensaofinaliza 
                                      ,ar21_cancdebitos 
                       )
                values (
                                $this->ar21_sequencial 
                               ,$this->ar21_suspensaofinaliza 
                               ,$this->ar21_cancdebitos 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento de Débitos por Suspensão ($this->ar21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento de Débitos por Suspensão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento de Débitos por Suspensão ($this->ar21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar21_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar21_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13285,'$this->ar21_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2331,13285,'','".AddSlashes(pg_result($resaco,0,'ar21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2331,13286,'','".AddSlashes(pg_result($resaco,0,'ar21_suspensaofinaliza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2331,13287,'','".AddSlashes(pg_result($resaco,0,'ar21_cancdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar21_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cancdebitossusp set ";
     $virgula = "";
     if(trim($this->ar21_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar21_sequencial"])){ 
       $sql  .= $virgula." ar21_sequencial = $this->ar21_sequencial ";
       $virgula = ",";
       if(trim($this->ar21_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ar21_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar21_suspensaofinaliza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar21_suspensaofinaliza"])){ 
       $sql  .= $virgula." ar21_suspensaofinaliza = $this->ar21_suspensaofinaliza ";
       $virgula = ",";
       if(trim($this->ar21_suspensaofinaliza) == null ){ 
         $this->erro_sql = " Campo Suspensão Finalizada nao Informado.";
         $this->erro_campo = "ar21_suspensaofinaliza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar21_cancdebitos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar21_cancdebitos"])){ 
       $sql  .= $virgula." ar21_cancdebitos = $this->ar21_cancdebitos ";
       $virgula = ",";
       if(trim($this->ar21_cancdebitos) == null ){ 
         $this->erro_sql = " Campo Débitos Cancelados nao Informado.";
         $this->erro_campo = "ar21_cancdebitos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar21_sequencial!=null){
       $sql .= " ar21_sequencial = $this->ar21_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar21_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13285,'$this->ar21_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar21_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2331,13285,'".AddSlashes(pg_result($resaco,$conresaco,'ar21_sequencial'))."','$this->ar21_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar21_suspensaofinaliza"]))
           $resac = db_query("insert into db_acount values($acount,2331,13286,'".AddSlashes(pg_result($resaco,$conresaco,'ar21_suspensaofinaliza'))."','$this->ar21_suspensaofinaliza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar21_cancdebitos"]))
           $resac = db_query("insert into db_acount values($acount,2331,13287,'".AddSlashes(pg_result($resaco,$conresaco,'ar21_cancdebitos'))."','$this->ar21_cancdebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento de Débitos por Suspensão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento de Débitos por Suspensão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar21_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar21_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13285,'$ar21_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2331,13285,'','".AddSlashes(pg_result($resaco,$iresaco,'ar21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2331,13286,'','".AddSlashes(pg_result($resaco,$iresaco,'ar21_suspensaofinaliza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2331,13287,'','".AddSlashes(pg_result($resaco,$iresaco,'ar21_cancdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitossusp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar21_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar21_sequencial = $ar21_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento de Débitos por Suspensão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento de Débitos por Suspensão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar21_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitossusp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitossusp ";
     $sql .= "      inner join cancdebitos  on  cancdebitos.k20_codigo = cancdebitossusp.ar21_cancdebitos";
     $sql .= "      inner join suspensaofinaliza  on  suspensaofinaliza.ar19_sequencial = cancdebitossusp.ar21_suspensaofinaliza";
     $sql .= "      inner join db_config  on  db_config.codigo = cancdebitos.k20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cancdebitos.k20_usuario";
     $sql .= "      inner join cancdebitostipo  on  cancdebitostipo.k73_sequencial = cancdebitos.k20_cancdebitostipo";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = suspensaofinaliza.ar19_id_usuario";
     $sql .= "      inner join suspensao  as b on   b.ar18_sequencial = suspensaofinaliza.ar19_suspensao";
     $sql2 = "";
     if($dbwhere==""){
       if($ar21_sequencial!=null ){
         $sql2 .= " where cancdebitossusp.ar21_sequencial = $ar21_sequencial "; 
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
   function sql_query_file ( $ar21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitossusp ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar21_sequencial!=null ){
         $sql2 .= " where cancdebitossusp.ar21_sequencial = $ar21_sequencial "; 
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