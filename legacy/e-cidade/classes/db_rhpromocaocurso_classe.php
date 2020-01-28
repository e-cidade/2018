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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhpromocaocurso
class cl_rhpromocaocurso { 
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
   var $h74_sequencial = 0; 
   var $h74_rhcurso = 0; 
   var $h74_rhpromocao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h74_sequencial = int4 = Sequencial 
                 h74_rhcurso = int4 = Curso 
                 h74_rhpromocao = int4 = Promo��o 
                 ";
   //funcao construtor da classe 
   function cl_rhpromocaocurso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpromocaocurso"); 
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
       $this->h74_sequencial = ($this->h74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h74_sequencial"]:$this->h74_sequencial);
       $this->h74_rhcurso = ($this->h74_rhcurso == ""?@$GLOBALS["HTTP_POST_VARS"]["h74_rhcurso"]:$this->h74_rhcurso);
       $this->h74_rhpromocao = ($this->h74_rhpromocao == ""?@$GLOBALS["HTTP_POST_VARS"]["h74_rhpromocao"]:$this->h74_rhpromocao);
     }else{
       $this->h74_sequencial = ($this->h74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h74_sequencial"]:$this->h74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h74_sequencial){ 
      $this->atualizacampos();
     if($this->h74_rhcurso == null ){ 
       $this->erro_sql = " Campo Curso nao Informado.";
       $this->erro_campo = "h74_rhcurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h74_rhpromocao == null ){ 
       $this->erro_sql = " Campo Promo��o nao Informado.";
       $this->erro_campo = "h74_rhpromocao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h74_sequencial == "" || $h74_sequencial == null ){
       $result = db_query("select nextval('rhpromocaocurso_h74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpromocaocurso_h74_sequencial_seq do campo: h74_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpromocaocurso_h74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h74_sequencial)){
         $this->erro_sql = " Campo h74_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h74_sequencial = $h74_sequencial; 
       }
     }
     if(($this->h74_sequencial == null) || ($this->h74_sequencial == "") ){ 
       $this->erro_sql = " Campo h74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpromocaocurso(
                                       h74_sequencial 
                                      ,h74_rhcurso 
                                      ,h74_rhpromocao 
                       )
                values (
                                $this->h74_sequencial 
                               ,$this->h74_rhcurso 
                               ,$this->h74_rhpromocao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Promo��o curso ($this->h74_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Promo��o curso j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Promo��o curso ($this->h74_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h74_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h74_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18721,'$this->h74_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3317,18721,'','".AddSlashes(pg_result($resaco,0,'h74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3317,18722,'','".AddSlashes(pg_result($resaco,0,'h74_rhcurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3317,18723,'','".AddSlashes(pg_result($resaco,0,'h74_rhpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhpromocaocurso set ";
     $virgula = "";
     if(trim($this->h74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h74_sequencial"])){ 
       $sql  .= $virgula." h74_sequencial = $this->h74_sequencial ";
       $virgula = ",";
       if(trim($this->h74_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "h74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h74_rhcurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h74_rhcurso"])){ 
       $sql  .= $virgula." h74_rhcurso = $this->h74_rhcurso ";
       $virgula = ",";
       if(trim($this->h74_rhcurso) == null ){ 
         $this->erro_sql = " Campo Curso nao Informado.";
         $this->erro_campo = "h74_rhcurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h74_rhpromocao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h74_rhpromocao"])){ 
       $sql  .= $virgula." h74_rhpromocao = $this->h74_rhpromocao ";
       $virgula = ",";
       if(trim($this->h74_rhpromocao) == null ){ 
         $this->erro_sql = " Campo Promo��o nao Informado.";
         $this->erro_campo = "h74_rhpromocao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h74_sequencial!=null){
       $sql .= " h74_sequencial = $this->h74_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h74_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18721,'$this->h74_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h74_sequencial"]) || $this->h74_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3317,18721,'".AddSlashes(pg_result($resaco,$conresaco,'h74_sequencial'))."','$this->h74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h74_rhcurso"]) || $this->h74_rhcurso != "")
           $resac = db_query("insert into db_acount values($acount,3317,18722,'".AddSlashes(pg_result($resaco,$conresaco,'h74_rhcurso'))."','$this->h74_rhcurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h74_rhpromocao"]) || $this->h74_rhpromocao != "")
           $resac = db_query("insert into db_acount values($acount,3317,18723,'".AddSlashes(pg_result($resaco,$conresaco,'h74_rhpromocao'))."','$this->h74_rhpromocao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Promo��o curso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Promo��o curso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h74_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h74_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18721,'$h74_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3317,18721,'','".AddSlashes(pg_result($resaco,$iresaco,'h74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3317,18722,'','".AddSlashes(pg_result($resaco,$iresaco,'h74_rhcurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3317,18723,'','".AddSlashes(pg_result($resaco,$iresaco,'h74_rhpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpromocaocurso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h74_sequencial = $h74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Promo��o curso nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Promo��o curso nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpromocaocurso";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaocurso ";
     $sql .= "      inner join curric  on  curric.h03_seq = rhpromocaocurso.h74_rhcurso";
     $sql .= "      inner join rhpromocao  on  rhpromocao.h72_sequencial = rhpromocaocurso.h74_rhpromocao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = curric.h03_numcgm";
     $sql .= "      inner join tabcurri  on  tabcurri.h01_codigo = curric.h03_codigo";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpromocao.h72_regist";
     $sql2 = "";
     if($dbwhere==""){
       if($h74_sequencial!=null ){
         $sql2 .= " where rhpromocaocurso.h74_sequencial = $h74_sequencial "; 
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
   function sql_query_file ( $h74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpromocaocurso ";
     $sql2 = "";
     if($dbwhere==""){
       if($h74_sequencial!=null ){
         $sql2 .= " where rhpromocaocurso.h74_sequencial = $h74_sequencial "; 
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