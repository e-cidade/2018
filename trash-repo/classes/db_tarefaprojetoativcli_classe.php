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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefaprojetoativcli
class cl_tarefaprojetoativcli { 
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
   var $at69_sequencial = 0; 
   var $at69_seqprojeto = 0; 
   var $at69_seqtarefa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at69_sequencial = int4 = Sequencial 
                 at69_seqprojeto = int4 = Atividade Projeto 
                 at69_seqtarefa = int4 = Tarefa 
                 ";
   //funcao construtor da classe 
   function cl_tarefaprojetoativcli() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefaprojetoativcli"); 
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
       $this->at69_sequencial = ($this->at69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at69_sequencial"]:$this->at69_sequencial);
       $this->at69_seqprojeto = ($this->at69_seqprojeto == ""?@$GLOBALS["HTTP_POST_VARS"]["at69_seqprojeto"]:$this->at69_seqprojeto);
       $this->at69_seqtarefa = ($this->at69_seqtarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at69_seqtarefa"]:$this->at69_seqtarefa);
     }else{
       $this->at69_sequencial = ($this->at69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at69_sequencial"]:$this->at69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at69_sequencial){ 
      $this->atualizacampos();
     if($this->at69_seqprojeto == null ){ 
       $this->erro_sql = " Campo Atividade Projeto nao Informado.";
       $this->erro_campo = "at69_seqprojeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at69_seqtarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at69_seqtarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at69_sequencial == "" || $at69_sequencial == null ){
       $result = db_query("select nextval('tarefalogprojetoativcli_at69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefalogprojetoativcli_at69_sequencial_seq do campo: at69_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefalogprojetoativcli_at69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at69_sequencial)){
         $this->erro_sql = " Campo at69_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at69_sequencial = $at69_sequencial; 
       }
     }
     if(($this->at69_sequencial == null) || ($this->at69_sequencial == "") ){ 
       $this->erro_sql = " Campo at69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefaprojetoativcli(
                                       at69_sequencial 
                                      ,at69_seqprojeto 
                                      ,at69_seqtarefa 
                       )
                values (
                                $this->at69_sequencial 
                               ,$this->at69_seqprojeto 
                               ,$this->at69_seqtarefa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimento da Tarefa ligado aos projetos ($this->at69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimento da Tarefa ligado aos projetos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimento da Tarefa ligado aos projetos ($this->at69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at69_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at69_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11856,'$this->at69_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2048,11856,'','".AddSlashes(pg_result($resaco,0,'at69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2048,11858,'','".AddSlashes(pg_result($resaco,0,'at69_seqprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2048,11857,'','".AddSlashes(pg_result($resaco,0,'at69_seqtarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefaprojetoativcli set ";
     $virgula = "";
     if(trim($this->at69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at69_sequencial"])){ 
       $sql  .= $virgula." at69_sequencial = $this->at69_sequencial ";
       $virgula = ",";
       if(trim($this->at69_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at69_seqprojeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at69_seqprojeto"])){ 
       $sql  .= $virgula." at69_seqprojeto = $this->at69_seqprojeto ";
       $virgula = ",";
       if(trim($this->at69_seqprojeto) == null ){ 
         $this->erro_sql = " Campo Atividade Projeto nao Informado.";
         $this->erro_campo = "at69_seqprojeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at69_seqtarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at69_seqtarefa"])){ 
       $sql  .= $virgula." at69_seqtarefa = $this->at69_seqtarefa ";
       $virgula = ",";
       if(trim($this->at69_seqtarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at69_seqtarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at69_sequencial!=null){
       $sql .= " at69_sequencial = $this->at69_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at69_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11856,'$this->at69_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at69_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2048,11856,'".AddSlashes(pg_result($resaco,$conresaco,'at69_sequencial'))."','$this->at69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at69_seqprojeto"]))
           $resac = db_query("insert into db_acount values($acount,2048,11858,'".AddSlashes(pg_result($resaco,$conresaco,'at69_seqprojeto'))."','$this->at69_seqprojeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at69_seqtarefa"]))
           $resac = db_query("insert into db_acount values($acount,2048,11857,'".AddSlashes(pg_result($resaco,$conresaco,'at69_seqtarefa'))."','$this->at69_seqtarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da Tarefa ligado aos projetos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da Tarefa ligado aos projetos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at69_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at69_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11856,'$at69_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2048,11856,'','".AddSlashes(pg_result($resaco,$iresaco,'at69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2048,11858,'','".AddSlashes(pg_result($resaco,$iresaco,'at69_seqprojeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2048,11857,'','".AddSlashes(pg_result($resaco,$iresaco,'at69_seqtarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefaprojetoativcli
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at69_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at69_sequencial = $at69_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da Tarefa ligado aos projetos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da Tarefa ligado aos projetos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at69_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefaprojetoativcli";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaprojetoativcli ";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefaprojetoativcli.at69_seqtarefa";
     $sql .= "      inner join db_projetosativcli  on  db_projetosativcli.at64_sequencial = tarefaprojetoativcli.at69_seqprojeto";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa.at40_responsavel";
     $sql .= "      inner join db_projetoscliente  on  db_projetoscliente.at60_codproj = db_projetosativcli.at64_codproj";
     $sql .= "      inner join db_projetosituacao  on  db_projetosituacao.at61_codigo = db_projetosativcli.at64_situacao";
     $sql .= "      inner join db_projetosativid  on  db_projetosativid.at62_codigo = db_projetosativcli.at64_codativ";
     $sql2 = "";
     if($dbwhere==""){
       if($at69_sequencial!=null ){
         $sql2 .= " where tarefaprojetoativcli.at69_sequencial = $at69_sequencial "; 
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
   function sql_query_file ( $at69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefaprojetoativcli ";
     $sql2 = "";
     if($dbwhere==""){
       if($at69_sequencial!=null ){
         $sql2 .= " where tarefaprojetoativcli.at69_sequencial = $at69_sequencial "; 
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