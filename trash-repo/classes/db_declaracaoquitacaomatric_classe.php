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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacaomatric
class cl_declaracaoquitacaomatric { 
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
   var $ar33_sequencial = 0; 
   var $ar33_matric = 0; 
   var $ar33_declaracaoquitacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar33_sequencial = int8 = Código 
                 ar33_matric = int4 = Matrícula do Imóvel 
                 ar33_declaracaoquitacao = int8 = Código Declaração 
                 ";
   //funcao construtor da classe 
   function cl_declaracaoquitacaomatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("declaracaoquitacaomatric"); 
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
       $this->ar33_sequencial = ($this->ar33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar33_sequencial"]:$this->ar33_sequencial);
       $this->ar33_matric = ($this->ar33_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["ar33_matric"]:$this->ar33_matric);
       $this->ar33_declaracaoquitacao = ($this->ar33_declaracaoquitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar33_declaracaoquitacao"]:$this->ar33_declaracaoquitacao);
     }else{
       $this->ar33_sequencial = ($this->ar33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar33_sequencial"]:$this->ar33_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar33_sequencial){ 
      $this->atualizacampos();
     if($this->ar33_matric == null ){ 
       $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
       $this->erro_campo = "ar33_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar33_declaracaoquitacao == null ){ 
       $this->erro_sql = " Campo Código Declaração nao Informado.";
       $this->erro_campo = "ar33_declaracaoquitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar33_sequencial == "" || $ar33_sequencial == null ){
       $result = db_query("select nextval('declaracaoquitacaomatric_ar33_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacaomatric_ar33_sequencial_seq do campo: ar33_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar33_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from declaracaoquitacaomatric_ar33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar33_sequencial)){
         $this->erro_sql = " Campo ar33_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar33_sequencial = $ar33_sequencial; 
       }
     }
     if(($this->ar33_sequencial == null) || ($this->ar33_sequencial == "") ){ 
       $this->erro_sql = " Campo ar33_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into declaracaoquitacaomatric(
                                       ar33_sequencial 
                                      ,ar33_matric 
                                      ,ar33_declaracaoquitacao 
                       )
                values (
                                $this->ar33_sequencial 
                               ,$this->ar33_matric 
                               ,$this->ar33_declaracaoquitacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matricula da Declaração de Quitação ($this->ar33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matricula da Declaração de Quitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matricula da Declaração de Quitação ($this->ar33_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar33_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar33_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17170,'$this->ar33_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3035,17170,'','".AddSlashes(pg_result($resaco,0,'ar33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3035,17171,'','".AddSlashes(pg_result($resaco,0,'ar33_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3035,17172,'','".AddSlashes(pg_result($resaco,0,'ar33_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar33_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update declaracaoquitacaomatric set ";
     $virgula = "";
     if(trim($this->ar33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar33_sequencial"])){ 
       $sql  .= $virgula." ar33_sequencial = $this->ar33_sequencial ";
       $virgula = ",";
       if(trim($this->ar33_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ar33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar33_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar33_matric"])){ 
       $sql  .= $virgula." ar33_matric = $this->ar33_matric ";
       $virgula = ",";
       if(trim($this->ar33_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "ar33_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar33_declaracaoquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar33_declaracaoquitacao"])){ 
       $sql  .= $virgula." ar33_declaracaoquitacao = $this->ar33_declaracaoquitacao ";
       $virgula = ",";
       if(trim($this->ar33_declaracaoquitacao) == null ){ 
         $this->erro_sql = " Campo Código Declaração nao Informado.";
         $this->erro_campo = "ar33_declaracaoquitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar33_sequencial!=null){
       $sql .= " ar33_sequencial = $this->ar33_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar33_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17170,'$this->ar33_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar33_sequencial"]) || $this->ar33_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3035,17170,'".AddSlashes(pg_result($resaco,$conresaco,'ar33_sequencial'))."','$this->ar33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar33_matric"]) || $this->ar33_matric != "")
           $resac = db_query("insert into db_acount values($acount,3035,17171,'".AddSlashes(pg_result($resaco,$conresaco,'ar33_matric'))."','$this->ar33_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar33_declaracaoquitacao"]) || $this->ar33_declaracaoquitacao != "")
           $resac = db_query("insert into db_acount values($acount,3035,17172,'".AddSlashes(pg_result($resaco,$conresaco,'ar33_declaracaoquitacao'))."','$this->ar33_declaracaoquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matricula da Declaração de Quitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matricula da Declaração de Quitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar33_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar33_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17170,'$ar33_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3035,17170,'','".AddSlashes(pg_result($resaco,$iresaco,'ar33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3035,17171,'','".AddSlashes(pg_result($resaco,$iresaco,'ar33_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3035,17172,'','".AddSlashes(pg_result($resaco,$iresaco,'ar33_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from declaracaoquitacaomatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar33_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar33_sequencial = $ar33_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matricula da Declaração de Quitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar33_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matricula da Declaração de Quitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar33_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacaomatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaomatric ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = declaracaoquitacaomatric.ar33_matric";
     $sql .= "      inner join declaracaoquitacao  on  declaracaoquitacao.ar30_sequencial = declaracaoquitacaomatric.ar33_declaracaoquitacao";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ar33_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaomatric.ar33_sequencial = $ar33_sequencial "; 
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
   function sql_query_file ( $ar33_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaomatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar33_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaomatric.ar33_sequencial = $ar33_sequencial "; 
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