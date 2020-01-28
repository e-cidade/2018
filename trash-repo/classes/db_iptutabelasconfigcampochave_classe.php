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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptutabelasconfigcampochave
class cl_iptutabelasconfigcampochave { 
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
   var $j124_sequencial = 0; 
   var $j124_codcam = 0; 
   var $j124_iptutabelasconfig = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j124_sequencial = int4 = C�digo Sequencial 
                 j124_codcam = int4 = C�digo do Campo 
                 j124_iptutabelasconfig = int4 = C�digo da Tabela de Configura��o 
                 ";
   //funcao construtor da classe 
   function cl_iptutabelasconfigcampochave() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptutabelasconfigcampochave"); 
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
       $this->j124_sequencial = ($this->j124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j124_sequencial"]:$this->j124_sequencial);
       $this->j124_codcam = ($this->j124_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["j124_codcam"]:$this->j124_codcam);
       $this->j124_iptutabelasconfig = ($this->j124_iptutabelasconfig == ""?@$GLOBALS["HTTP_POST_VARS"]["j124_iptutabelasconfig"]:$this->j124_iptutabelasconfig);
     }else{
       $this->j124_sequencial = ($this->j124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j124_sequencial"]:$this->j124_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j124_sequencial){ 
      $this->atualizacampos();
     if($this->j124_codcam == null ){ 
       $this->erro_sql = " Campo C�digo do Campo nao Informado.";
       $this->erro_campo = "j124_codcam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j124_iptutabelasconfig == null ){ 
       $this->erro_sql = " Campo C�digo da Tabela de Configura��o nao Informado.";
       $this->erro_campo = "j124_iptutabelasconfig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j124_sequencial == "" || $j124_sequencial == null ){
       $result = db_query("select nextval('iptutabelasconfigcampochave_j124_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptutabelasconfigcampochave_j124_sequencial_seq do campo: j124_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j124_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptutabelasconfigcampochave_j124_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j124_sequencial)){
         $this->erro_sql = " Campo j124_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j124_sequencial = $j124_sequencial; 
       }
     }
     if(($this->j124_sequencial == null) || ($this->j124_sequencial == "") ){ 
       $this->erro_sql = " Campo j124_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptutabelasconfigcampochave(
                                       j124_sequencial 
                                      ,j124_codcam 
                                      ,j124_iptutabelasconfig 
                       )
                values (
                                $this->j124_sequencial 
                               ,$this->j124_codcam 
                               ,$this->j124_iptutabelasconfig 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "iptutabelasconfigcampochave ($this->j124_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "iptutabelasconfigcampochave j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "iptutabelasconfigcampochave ($this->j124_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j124_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j124_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17401,'$this->j124_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3080,17401,'','".AddSlashes(pg_result($resaco,0,'j124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3080,17402,'','".AddSlashes(pg_result($resaco,0,'j124_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3080,17403,'','".AddSlashes(pg_result($resaco,0,'j124_iptutabelasconfig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j124_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptutabelasconfigcampochave set ";
     $virgula = "";
     if(trim($this->j124_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j124_sequencial"])){ 
       $sql  .= $virgula." j124_sequencial = $this->j124_sequencial ";
       $virgula = ",";
       if(trim($this->j124_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "j124_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j124_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j124_codcam"])){ 
       $sql  .= $virgula." j124_codcam = $this->j124_codcam ";
       $virgula = ",";
       if(trim($this->j124_codcam) == null ){ 
         $this->erro_sql = " Campo C�digo do Campo nao Informado.";
         $this->erro_campo = "j124_codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j124_iptutabelasconfig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j124_iptutabelasconfig"])){ 
       $sql  .= $virgula." j124_iptutabelasconfig = $this->j124_iptutabelasconfig ";
       $virgula = ",";
       if(trim($this->j124_iptutabelasconfig) == null ){ 
         $this->erro_sql = " Campo C�digo da Tabela de Configura��o nao Informado.";
         $this->erro_campo = "j124_iptutabelasconfig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j124_sequencial!=null){
       $sql .= " j124_sequencial = $this->j124_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j124_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17401,'$this->j124_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j124_sequencial"]) || $this->j124_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3080,17401,'".AddSlashes(pg_result($resaco,$conresaco,'j124_sequencial'))."','$this->j124_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j124_codcam"]) || $this->j124_codcam != "")
           $resac = db_query("insert into db_acount values($acount,3080,17402,'".AddSlashes(pg_result($resaco,$conresaco,'j124_codcam'))."','$this->j124_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j124_iptutabelasconfig"]) || $this->j124_iptutabelasconfig != "")
           $resac = db_query("insert into db_acount values($acount,3080,17403,'".AddSlashes(pg_result($resaco,$conresaco,'j124_iptutabelasconfig'))."','$this->j124_iptutabelasconfig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutabelasconfigcampochave nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j124_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutabelasconfigcampochave nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j124_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j124_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j124_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j124_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17401,'$j124_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3080,17401,'','".AddSlashes(pg_result($resaco,$iresaco,'j124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3080,17402,'','".AddSlashes(pg_result($resaco,$iresaco,'j124_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3080,17403,'','".AddSlashes(pg_result($resaco,$iresaco,'j124_iptutabelasconfig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptutabelasconfigcampochave
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j124_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j124_sequencial = $j124_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutabelasconfigcampochave nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j124_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutabelasconfigcampochave nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j124_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j124_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptutabelasconfigcampochave";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutabelasconfigcampochave                                                                                              ";
     $sql .= "      inner join db_syscampo        on db_syscampo.codcam                = iptutabelasconfigcampochave.j124_codcam             ";
     $sql .= "      inner join iptutabelasconfig  on iptutabelasconfig.j122_sequencial = iptutabelasconfigcampochave.j124_iptutabelasconfig  ";
     $sql .= "      inner join iptutabelas  as a  on a.j121_sequencial                 = iptutabelasconfig.j122_iptutabelas                  ";
     $sql .= "      inner join db_sysarquivo      on db_sysarquivo.codarq              = a.j121_codarq                                       ";
     $sql2 = "";
     if($dbwhere==""){
       if($j124_sequencial!=null ){
         $sql2 .= " where iptutabelasconfigcampochave.j124_sequencial = $j124_sequencial "; 
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
   function sql_query_file ( $j124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutabelasconfigcampochave ";
     $sql2 = "";
     if($dbwhere==""){
       if($j124_sequencial!=null ){
         $sql2 .= " where iptutabelasconfigcampochave.j124_sequencial = $j124_sequencial "; 
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