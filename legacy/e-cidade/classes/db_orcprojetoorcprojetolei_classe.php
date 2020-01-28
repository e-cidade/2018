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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcprojetoorcprojetolei
class cl_orcprojetoorcprojetolei { 
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
   var $o139_sequencial = 0; 
   var $o139_orcprojetolei = 0; 
   var $o139_orcprojeto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o139_sequencial = int4 = Código Sequencial 
                 o139_orcprojetolei = int4 = Código do Projeto de lei 
                 o139_orcprojeto = int4 = Código do Decreto 
                 ";
   //funcao construtor da classe 
   function cl_orcprojetoorcprojetolei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprojetoorcprojetolei"); 
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
       $this->o139_sequencial = ($this->o139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o139_sequencial"]:$this->o139_sequencial);
       $this->o139_orcprojetolei = ($this->o139_orcprojetolei == ""?@$GLOBALS["HTTP_POST_VARS"]["o139_orcprojetolei"]:$this->o139_orcprojetolei);
       $this->o139_orcprojeto = ($this->o139_orcprojeto == ""?@$GLOBALS["HTTP_POST_VARS"]["o139_orcprojeto"]:$this->o139_orcprojeto);
     }else{
       $this->o139_sequencial = ($this->o139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o139_sequencial"]:$this->o139_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o139_sequencial){ 
      $this->atualizacampos();
     if($this->o139_orcprojetolei == null ){ 
       $this->erro_sql = " Campo Código do Projeto de lei nao Informado.";
       $this->erro_campo = "o139_orcprojetolei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o139_orcprojeto == null ){ 
       $this->erro_sql = " Campo Código do Decreto nao Informado.";
       $this->erro_campo = "o139_orcprojeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o139_sequencial == "" || $o139_sequencial == null ){
       $result = db_query("select nextval('orcprojetoorcprojetolei_o139_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprojetoorcprojetolei_o139_sequencial_seq do campo: o139_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o139_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprojetoorcprojetolei_o139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o139_sequencial)){
         $this->erro_sql = " Campo o139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o139_sequencial = $o139_sequencial; 
       }
     }
     if(($this->o139_sequencial == null) || ($this->o139_sequencial == "") ){ 
       $this->erro_sql = " Campo o139_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprojetoorcprojetolei(
                                       o139_sequencial 
                                      ,o139_orcprojetolei 
                                      ,o139_orcprojeto 
                       )
                values (
                                $this->o139_sequencial 
                               ,$this->o139_orcprojetolei 
                               ,$this->o139_orcprojeto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação do projeto de lei com o Decreto ($this->o139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação do projeto de lei com o Decreto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação do projeto de lei com o Decreto ($this->o139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o139_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17687,'$this->o139_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3124,17687,'','".AddSlashes(pg_result($resaco,0,'o139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3124,17688,'','".AddSlashes(pg_result($resaco,0,'o139_orcprojetolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3124,17689,'','".AddSlashes(pg_result($resaco,0,'o139_orcprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o139_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcprojetoorcprojetolei set ";
     $virgula = "";
     if(trim($this->o139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o139_sequencial"])){ 
       $sql  .= $virgula." o139_sequencial = $this->o139_sequencial ";
       $virgula = ",";
       if(trim($this->o139_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o139_orcprojetolei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o139_orcprojetolei"])){ 
       $sql  .= $virgula." o139_orcprojetolei = $this->o139_orcprojetolei ";
       $virgula = ",";
       if(trim($this->o139_orcprojetolei) == null ){ 
         $this->erro_sql = " Campo Código do Projeto de lei nao Informado.";
         $this->erro_campo = "o139_orcprojetolei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o139_orcprojeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o139_orcprojeto"])){ 
       $sql  .= $virgula." o139_orcprojeto = $this->o139_orcprojeto ";
       $virgula = ",";
       if(trim($this->o139_orcprojeto) == null ){ 
         $this->erro_sql = " Campo Código do Decreto nao Informado.";
         $this->erro_campo = "o139_orcprojeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o139_sequencial!=null){
       $sql .= " o139_sequencial = $this->o139_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o139_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17687,'$this->o139_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o139_sequencial"]) || $this->o139_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3124,17687,'".AddSlashes(pg_result($resaco,$conresaco,'o139_sequencial'))."','$this->o139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o139_orcprojetolei"]) || $this->o139_orcprojetolei != "")
           $resac = db_query("insert into db_acount values($acount,3124,17688,'".AddSlashes(pg_result($resaco,$conresaco,'o139_orcprojetolei'))."','$this->o139_orcprojetolei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o139_orcprojeto"]) || $this->o139_orcprojeto != "")
           $resac = db_query("insert into db_acount values($acount,3124,17689,'".AddSlashes(pg_result($resaco,$conresaco,'o139_orcprojeto'))."','$this->o139_orcprojeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do projeto de lei com o Decreto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do projeto de lei com o Decreto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o139_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o139_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17687,'$o139_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3124,17687,'','".AddSlashes(pg_result($resaco,$iresaco,'o139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3124,17688,'','".AddSlashes(pg_result($resaco,$iresaco,'o139_orcprojetolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3124,17689,'','".AddSlashes(pg_result($resaco,$iresaco,'o139_orcprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprojetoorcprojetolei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o139_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o139_sequencial = $o139_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do projeto de lei com o Decreto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do projeto de lei com o Decreto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprojetoorcprojetolei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojetoorcprojetolei ";
     $sql .= "      inner join orcprojeto  on  orcprojeto.o39_codproj = orcprojetoorcprojetolei.o139_orcprojeto";
     $sql .= "      inner join orcprojetolei  on  orcprojetolei.o138_sequencial = orcprojetoorcprojetolei.o139_orcprojetolei";
     $sql .= "      inner join orclei  on  orclei.o45_codlei = orcprojeto.o39_codlei";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojetolei.o138_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($o139_sequencial!=null ){
         $sql2 .= " where orcprojetoorcprojetolei.o139_sequencial = $o139_sequencial "; 
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
   function sql_query_file ( $o139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojetoorcprojetolei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o139_sequencial!=null ){
         $sql2 .= " where orcprojetoorcprojetolei.o139_sequencial = $o139_sequencial "; 
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