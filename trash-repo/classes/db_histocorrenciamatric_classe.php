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
//CLASSE DA ENTIDADE histocorrenciamatric
class cl_histocorrenciamatric { 
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
   var $ar25_sequencial = 0; 
   var $ar25_matric = 0; 
   var $ar25_histocorrencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar25_sequencial = int4 = Código Histórico 
                 ar25_matric = int4 = Matrícula do Imóvel 
                 ar25_histocorrencia = int4 = Código Histórico 
                 ";
   //funcao construtor da classe 
   function cl_histocorrenciamatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histocorrenciamatric"); 
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
       $this->ar25_sequencial = ($this->ar25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar25_sequencial"]:$this->ar25_sequencial);
       $this->ar25_matric = ($this->ar25_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["ar25_matric"]:$this->ar25_matric);
       $this->ar25_histocorrencia = ($this->ar25_histocorrencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar25_histocorrencia"]:$this->ar25_histocorrencia);
     }else{
       $this->ar25_sequencial = ($this->ar25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar25_sequencial"]:$this->ar25_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar25_sequencial){ 
      $this->atualizacampos();
     if($this->ar25_matric == null ){ 
       $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
       $this->erro_campo = "ar25_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar25_histocorrencia == null ){ 
       $this->erro_sql = " Campo Código Histórico nao Informado.";
       $this->erro_campo = "ar25_histocorrencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar25_sequencial == "" || $ar25_sequencial == null ){
       $result = db_query("select nextval('histocorrenciamatric_ar25_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histocorrenciamatric_ar25_sequencial_seq do campo: ar25_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar25_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histocorrenciamatric_ar25_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar25_sequencial)){
         $this->erro_sql = " Campo ar25_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar25_sequencial = $ar25_sequencial; 
       }
     }
     if(($this->ar25_sequencial == null) || ($this->ar25_sequencial == "") ){ 
       $this->erro_sql = " Campo ar25_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histocorrenciamatric(
                                       ar25_sequencial 
                                      ,ar25_matric 
                                      ,ar25_histocorrencia 
                       )
                values (
                                $this->ar25_sequencial 
                               ,$this->ar25_matric 
                               ,$this->ar25_histocorrencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "histocorrenciamatric ($this->ar25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "histocorrenciamatric já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "histocorrenciamatric ($this->ar25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar25_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar25_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15103,'$this->ar25_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2656,15103,'','".AddSlashes(pg_result($resaco,0,'ar25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2656,15104,'','".AddSlashes(pg_result($resaco,0,'ar25_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2656,15105,'','".AddSlashes(pg_result($resaco,0,'ar25_histocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar25_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update histocorrenciamatric set ";
     $virgula = "";
     if(trim($this->ar25_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar25_sequencial"])){ 
       $sql  .= $virgula." ar25_sequencial = $this->ar25_sequencial ";
       $virgula = ",";
       if(trim($this->ar25_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Histórico nao Informado.";
         $this->erro_campo = "ar25_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar25_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar25_matric"])){ 
       $sql  .= $virgula." ar25_matric = $this->ar25_matric ";
       $virgula = ",";
       if(trim($this->ar25_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "ar25_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar25_histocorrencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar25_histocorrencia"])){ 
       $sql  .= $virgula." ar25_histocorrencia = $this->ar25_histocorrencia ";
       $virgula = ",";
       if(trim($this->ar25_histocorrencia) == null ){ 
         $this->erro_sql = " Campo Código Histórico nao Informado.";
         $this->erro_campo = "ar25_histocorrencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar25_sequencial!=null){
       $sql .= " ar25_sequencial = $this->ar25_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar25_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15103,'$this->ar25_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar25_sequencial"]) || $this->ar25_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2656,15103,'".AddSlashes(pg_result($resaco,$conresaco,'ar25_sequencial'))."','$this->ar25_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar25_matric"]) || $this->ar25_matric != "")
           $resac = db_query("insert into db_acount values($acount,2656,15104,'".AddSlashes(pg_result($resaco,$conresaco,'ar25_matric'))."','$this->ar25_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar25_histocorrencia"]) || $this->ar25_histocorrencia != "")
           $resac = db_query("insert into db_acount values($acount,2656,15105,'".AddSlashes(pg_result($resaco,$conresaco,'ar25_histocorrencia'))."','$this->ar25_histocorrencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "histocorrenciamatric nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "histocorrenciamatric nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar25_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar25_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15103,'$ar25_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2656,15103,'','".AddSlashes(pg_result($resaco,$iresaco,'ar25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2656,15104,'','".AddSlashes(pg_result($resaco,$iresaco,'ar25_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2656,15105,'','".AddSlashes(pg_result($resaco,$iresaco,'ar25_histocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histocorrenciamatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar25_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar25_sequencial = $ar25_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "histocorrenciamatric nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "histocorrenciamatric nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar25_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:histocorrenciamatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histocorrenciamatric ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = histocorrenciamatric.ar25_matric";
     $sql .= "      inner join histocorrencia  on  histocorrencia.ar23_sequencial = histocorrenciamatric.ar25_histocorrencia";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = histocorrencia.ar23_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = histocorrencia.ar23_id_usuario";
     $sql .= "      inner join db_itensmenu  on  db_itensmenu.id_item = histocorrencia.ar23_id_itensmenu";
     $sql .= "      inner join db_modulos  on  db_modulos.id_item = histocorrencia.ar23_modulo";
     $sql2 = "";
     if($dbwhere==""){
       if($ar25_sequencial!=null ){
         $sql2 .= " where histocorrenciamatric.ar25_sequencial = $ar25_sequencial "; 
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
   function sql_query_file ( $ar25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histocorrenciamatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar25_sequencial!=null ){
         $sql2 .= " where histocorrenciamatric.ar25_sequencial = $ar25_sequencial "; 
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