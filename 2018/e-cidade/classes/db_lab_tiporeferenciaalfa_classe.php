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

//MODULO: Laborat�rio
//CLASSE DA ENTIDADE lab_tiporeferenciaalfa
class cl_lab_tiporeferenciaalfa { 
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
   var $la29_i_codigo = 0; 
   var $la29_i_valorref = 0; 
   var $la29_i_fixo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la29_i_codigo = int4 = C�digo 
                 la29_i_valorref = int4 = Valor Referencial 
                 la29_i_fixo = int4 = Valor Referencial Fixo 
                 ";
   //funcao construtor da classe 
   function cl_lab_tiporeferenciaalfa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_tiporeferenciaalfa"); 
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
       $this->la29_i_codigo = ($this->la29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la29_i_codigo"]:$this->la29_i_codigo);
       $this->la29_i_valorref = ($this->la29_i_valorref == ""?@$GLOBALS["HTTP_POST_VARS"]["la29_i_valorref"]:$this->la29_i_valorref);
       $this->la29_i_fixo = ($this->la29_i_fixo == ""?@$GLOBALS["HTTP_POST_VARS"]["la29_i_fixo"]:$this->la29_i_fixo);
     }else{
       $this->la29_i_codigo = ($this->la29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la29_i_codigo"]:$this->la29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la29_i_codigo){ 
      $this->atualizacampos();
     if($this->la29_i_valorref == null ){ 
       $this->erro_sql = " Campo Valor Referencial nao Informado.";
       $this->erro_campo = "la29_i_valorref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la29_i_fixo == null ){ 
       $this->la29_i_fixo = "0";
     }
     if($la29_i_codigo == "" || $la29_i_codigo == null ){
       $result = db_query("select nextval('lab_tiporeferenciaalfa_la29_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_tiporeferenciaalfa_la29_i_codigo_seq do campo: la29_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_tiporeferenciaalfa_la29_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la29_i_codigo)){
         $this->erro_sql = " Campo la29_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la29_i_codigo = $la29_i_codigo; 
       }
     }
     if(($this->la29_i_codigo == null) || ($this->la29_i_codigo == "") ){ 
       $this->erro_sql = " Campo la29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_tiporeferenciaalfa(
                                       la29_i_codigo 
                                      ,la29_i_valorref 
                                      ,la29_i_fixo 
                       )
                values (
                                $this->la29_i_codigo 
                               ,$this->la29_i_valorref 
                               ,$this->la29_i_fixo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de refer�ncia alfanum�rico ($this->la29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de refer�ncia alfanum�rico j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de refer�ncia alfanum�rico ($this->la29_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la29_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16500,'$this->la29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2902,16500,'','".AddSlashes(pg_result($resaco,0,'la29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2902,16501,'','".AddSlashes(pg_result($resaco,0,'la29_i_valorref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2902,16502,'','".AddSlashes(pg_result($resaco,0,'la29_i_fixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_tiporeferenciaalfa set ";
     $virgula = "";
     if(trim($this->la29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la29_i_codigo"])){ 
       $sql  .= $virgula." la29_i_codigo = $this->la29_i_codigo ";
       $virgula = ",";
       if(trim($this->la29_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la29_i_valorref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la29_i_valorref"])){ 
       $sql  .= $virgula." la29_i_valorref = $this->la29_i_valorref ";
       $virgula = ",";
       if(trim($this->la29_i_valorref) == null ){ 
         $this->erro_sql = " Campo Valor Referencial nao Informado.";
         $this->erro_campo = "la29_i_valorref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la29_i_fixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la29_i_fixo"])){ 
        if(trim($this->la29_i_fixo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la29_i_fixo"])){ 
           $this->la29_i_fixo = "0" ; 
        } 
       $sql  .= $virgula." la29_i_fixo = $this->la29_i_fixo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la29_i_codigo!=null){
       $sql .= " la29_i_codigo = $this->la29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16500,'$this->la29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la29_i_codigo"]) || $this->la29_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2902,16500,'".AddSlashes(pg_result($resaco,$conresaco,'la29_i_codigo'))."','$this->la29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la29_i_valorref"]) || $this->la29_i_valorref != "")
           $resac = db_query("insert into db_acount values($acount,2902,16501,'".AddSlashes(pg_result($resaco,$conresaco,'la29_i_valorref'))."','$this->la29_i_valorref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la29_i_fixo"]) || $this->la29_i_fixo != "")
           $resac = db_query("insert into db_acount values($acount,2902,16502,'".AddSlashes(pg_result($resaco,$conresaco,'la29_i_fixo'))."','$this->la29_i_fixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de refer�ncia alfanum�rico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de refer�ncia alfanum�rico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16500,'$la29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2902,16500,'','".AddSlashes(pg_result($resaco,$iresaco,'la29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2902,16501,'','".AddSlashes(pg_result($resaco,$iresaco,'la29_i_valorref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2902,16502,'','".AddSlashes(pg_result($resaco,$iresaco,'la29_i_fixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_tiporeferenciaalfa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la29_i_codigo = $la29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de refer�ncia alfanum�rico nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la29_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de refer�ncia alfanum�rico nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la29_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_tiporeferenciaalfa";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_tiporeferenciaalfa ";
     $sql .= "      inner join lab_valorreferencia  on  lab_valorreferencia.la27_i_codigo = lab_tiporeferenciaalfa.la29_i_valorref";
     $sql .= "      left  join lab_undmedida  on  lab_undmedida.la13_i_codigo = lab_valorreferencia.la27_i_unidade";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_valorreferencia.la27_i_atributo";
     $sql2 = "";
     if($dbwhere==""){
       if($la29_i_codigo!=null ){
         $sql2 .= " where lab_tiporeferenciaalfa.la29_i_codigo = $la29_i_codigo "; 
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
   function sql_query_file ( $la29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_tiporeferenciaalfa ";
     $sql2 = "";
     if($dbwhere==""){
       if($la29_i_codigo!=null ){
         $sql2 .= " where lab_tiporeferenciaalfa.la29_i_codigo = $la29_i_codigo "; 
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