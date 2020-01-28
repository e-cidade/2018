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
//CLASSE DA ENTIDADE tarefa_arquivos
class cl_tarefa_arquivos { 
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
   var $at80_seqarquivo = 0; 
   var $at80_tarefa = 0; 
   var $at80_arquivos = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at80_seqarquivo = int4 = Sequencial 
                 at80_tarefa = int4 = Codigo da Tarefa 
                 at80_arquivos = varchar(100) = Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_arquivos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_arquivos"); 
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
       $this->at80_seqarquivo = ($this->at80_seqarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_seqarquivo"]:$this->at80_seqarquivo);
       $this->at80_tarefa = ($this->at80_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_tarefa"]:$this->at80_tarefa);
       $this->at80_arquivos = ($this->at80_arquivos == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_arquivos"]:$this->at80_arquivos);
     }else{
       $this->at80_seqarquivo = ($this->at80_seqarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["at80_seqarquivo"]:$this->at80_seqarquivo);
     }
   }
   // funcao para inclusao
   function incluir ($at80_seqarquivo){ 
      $this->atualizacampos();
     if($this->at80_tarefa == null ){ 
       $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
       $this->erro_campo = "at80_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at80_arquivos == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "at80_arquivos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at80_seqarquivo == "" || $at80_seqarquivo == null ){
       $result = db_query("select nextval('tarefa_arquivos_at80_seqarquivo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_arquivos_at80_seqarquivo_seq do campo: at80_seqarquivo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at80_seqarquivo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_arquivos_at80_seqarquivo_seq");
       if(($result != false) && (pg_result($result,0,0) < $at80_seqarquivo)){
         $this->erro_sql = " Campo at80_seqarquivo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at80_seqarquivo = $at80_seqarquivo; 
       }
     }
     if(($this->at80_seqarquivo == null) || ($this->at80_seqarquivo == "") ){ 
       $this->erro_sql = " Campo at80_seqarquivo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_arquivos(
                                       at80_seqarquivo 
                                      ,at80_tarefa 
                                      ,at80_arquivos 
                       )
                values (
                                $this->at80_seqarquivo 
                               ,$this->at80_tarefa 
                               ,'$this->at80_arquivos' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tarefas e arquivos ($this->at80_seqarquivo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tarefas e arquivos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tarefas e arquivos ($this->at80_seqarquivo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at80_seqarquivo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at80_seqarquivo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10001,'$this->at80_seqarquivo','I')");
       $resac = db_query("insert into db_acount values($acount,1716,10001,'','".AddSlashes(pg_result($resaco,0,'at80_seqarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1716,9999,'','".AddSlashes(pg_result($resaco,0,'at80_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1716,10000,'','".AddSlashes(pg_result($resaco,0,'at80_arquivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at80_seqarquivo=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_arquivos set ";
     $virgula = "";
     if(trim($this->at80_seqarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_seqarquivo"])){ 
       $sql  .= $virgula." at80_seqarquivo = $this->at80_seqarquivo ";
       $virgula = ",";
       if(trim($this->at80_seqarquivo) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at80_seqarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_tarefa"])){ 
       $sql  .= $virgula." at80_tarefa = $this->at80_tarefa ";
       $virgula = ",";
       if(trim($this->at80_tarefa) == null ){ 
         $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
         $this->erro_campo = "at80_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at80_arquivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at80_arquivos"])){ 
       $sql  .= $virgula." at80_arquivos = '$this->at80_arquivos' ";
       $virgula = ",";
       if(trim($this->at80_arquivos) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "at80_arquivos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at80_seqarquivo!=null){
       $sql .= " at80_seqarquivo = $this->at80_seqarquivo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at80_seqarquivo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10001,'$this->at80_seqarquivo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_seqarquivo"]))
           $resac = db_query("insert into db_acount values($acount,1716,10001,'".AddSlashes(pg_result($resaco,$conresaco,'at80_seqarquivo'))."','$this->at80_seqarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1716,9999,'".AddSlashes(pg_result($resaco,$conresaco,'at80_tarefa'))."','$this->at80_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at80_arquivos"]))
           $resac = db_query("insert into db_acount values($acount,1716,10000,'".AddSlashes(pg_result($resaco,$conresaco,'at80_arquivos'))."','$this->at80_arquivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tarefas e arquivos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at80_seqarquivo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tarefas e arquivos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at80_seqarquivo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at80_seqarquivo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at80_seqarquivo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at80_seqarquivo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10001,'$at80_seqarquivo','E')");
         $resac = db_query("insert into db_acount values($acount,1716,10001,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_seqarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1716,9999,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1716,10000,'','".AddSlashes(pg_result($resaco,$iresaco,'at80_arquivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_arquivos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at80_seqarquivo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at80_seqarquivo = $at80_seqarquivo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tarefas e arquivos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at80_seqarquivo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tarefas e arquivos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at80_seqarquivo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at80_seqarquivo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_arquivos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>