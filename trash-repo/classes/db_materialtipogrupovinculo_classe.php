<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: material
//CLASSE DA ENTIDADE materialtipogrupovinculo
class cl_materialtipogrupovinculo { 
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
   var $m04_sequencial = 0; 
   var $m04_materialtipogrupo = 0; 
   var $m04_db_estruturavalor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m04_sequencial = int4 = Codigo Sequencial 
                 m04_materialtipogrupo = int4 = Codigo Material Tipo Grupo 
                 m04_db_estruturavalor = int4 = Codigo DB Estrutura Valor 
                 ";
   //funcao construtor da classe 
   function cl_materialtipogrupovinculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("materialtipogrupovinculo"); 
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
       $this->m04_sequencial = ($this->m04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m04_sequencial"]:$this->m04_sequencial);
       $this->m04_materialtipogrupo = ($this->m04_materialtipogrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["m04_materialtipogrupo"]:$this->m04_materialtipogrupo);
       $this->m04_db_estruturavalor = ($this->m04_db_estruturavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["m04_db_estruturavalor"]:$this->m04_db_estruturavalor);
     }else{
       $this->m04_sequencial = ($this->m04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m04_sequencial"]:$this->m04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m04_sequencial){ 
      $this->atualizacampos();
     if($this->m04_materialtipogrupo == null ){ 
       $this->erro_sql = " Campo Codigo Material Tipo Grupo nao Informado.";
       $this->erro_campo = "m04_materialtipogrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m04_db_estruturavalor == null ){ 
       $this->erro_sql = " Campo Codigo DB Estrutura Valor nao Informado.";
       $this->erro_campo = "m04_db_estruturavalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m04_sequencial == "" || $m04_sequencial == null ){
       $result = db_query("select nextval('materialtipogrupovinculo_m04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: materialtipogrupovinculo_m04_sequencial_seq do campo: m04_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from materialtipogrupovinculo_m04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m04_sequencial)){
         $this->erro_sql = " Campo m04_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m04_sequencial = $m04_sequencial; 
       }
     }
     if(($this->m04_sequencial == null) || ($this->m04_sequencial == "") ){ 
       $this->erro_sql = " Campo m04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into materialtipogrupovinculo(
                                       m04_sequencial 
                                      ,m04_materialtipogrupo 
                                      ,m04_db_estruturavalor 
                       )
                values (
                                $this->m04_sequencial 
                               ,$this->m04_materialtipogrupo 
                               ,$this->m04_db_estruturavalor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Grupo do Material Vinculo ($this->m04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Grupo do Material Vinculo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Grupo do Material Vinculo ($this->m04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m04_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m04_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19159,'$this->m04_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3404,19159,'','".AddSlashes(pg_result($resaco,0,'m04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3404,19160,'','".AddSlashes(pg_result($resaco,0,'m04_materialtipogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3404,19161,'','".AddSlashes(pg_result($resaco,0,'m04_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update materialtipogrupovinculo set ";
     $virgula = "";
     if(trim($this->m04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m04_sequencial"])){ 
       $sql  .= $virgula." m04_sequencial = $this->m04_sequencial ";
       $virgula = ",";
       if(trim($this->m04_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "m04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m04_materialtipogrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m04_materialtipogrupo"])){ 
       $sql  .= $virgula." m04_materialtipogrupo = $this->m04_materialtipogrupo ";
       $virgula = ",";
       if(trim($this->m04_materialtipogrupo) == null ){ 
         $this->erro_sql = " Campo Codigo Material Tipo Grupo nao Informado.";
         $this->erro_campo = "m04_materialtipogrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m04_db_estruturavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m04_db_estruturavalor"])){ 
       $sql  .= $virgula." m04_db_estruturavalor = $this->m04_db_estruturavalor ";
       $virgula = ",";
       if(trim($this->m04_db_estruturavalor) == null ){ 
         $this->erro_sql = " Campo Codigo DB Estrutura Valor nao Informado.";
         $this->erro_campo = "m04_db_estruturavalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m04_sequencial!=null){
       $sql .= " m04_sequencial = $this->m04_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m04_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19159,'$this->m04_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m04_sequencial"]) || $this->m04_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3404,19159,'".AddSlashes(pg_result($resaco,$conresaco,'m04_sequencial'))."','$this->m04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m04_materialtipogrupo"]) || $this->m04_materialtipogrupo != "")
           $resac = db_query("insert into db_acount values($acount,3404,19160,'".AddSlashes(pg_result($resaco,$conresaco,'m04_materialtipogrupo'))."','$this->m04_materialtipogrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m04_db_estruturavalor"]) || $this->m04_db_estruturavalor != "")
           $resac = db_query("insert into db_acount values($acount,3404,19161,'".AddSlashes(pg_result($resaco,$conresaco,'m04_db_estruturavalor'))."','$this->m04_db_estruturavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Grupo do Material Vinculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Grupo do Material Vinculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m04_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m04_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19159,'$m04_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3404,19159,'','".AddSlashes(pg_result($resaco,$iresaco,'m04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3404,19160,'','".AddSlashes(pg_result($resaco,$iresaco,'m04_materialtipogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3404,19161,'','".AddSlashes(pg_result($resaco,$iresaco,'m04_db_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from materialtipogrupovinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m04_sequencial = $m04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Grupo do Material Vinculo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Grupo do Material Vinculo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:materialtipogrupovinculo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from materialtipogrupovinculo ";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = materialtipogrupovinculo.m04_db_estruturavalor";
     $sql .= "      inner join materialtipogrupo  on  materialtipogrupo.m03_sequencial = materialtipogrupovinculo.m04_materialtipogrupo";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = db_estruturavalor.db121_db_estrutura";
     $sql2 = "";
     if($dbwhere==""){
       if($m04_sequencial!=null ){
         $sql2 .= " where materialtipogrupovinculo.m04_sequencial = $m04_sequencial "; 
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
   function sql_query_file ( $m04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from materialtipogrupovinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($m04_sequencial!=null ){
         $sql2 .= " where materialtipogrupovinculo.m04_sequencial = $m04_sequencial "; 
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