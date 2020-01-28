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

//MODULO: patrim
//CLASSE DA ENTIDADE apolitem
class cl_apolitem { 
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
   var $t82_codapo = 0; 
   var $t82_codbem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t82_codapo = int8 = C�digo ap�lice 
                 t82_codbem = int8 = C�digo do bem 
                 ";
   //funcao construtor da classe 
   function cl_apolitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("apolitem"); 
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
       $this->t82_codapo = ($this->t82_codapo == ""?@$GLOBALS["HTTP_POST_VARS"]["t82_codapo"]:$this->t82_codapo);
       $this->t82_codbem = ($this->t82_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t82_codbem"]:$this->t82_codbem);
     }else{
       $this->t82_codapo = ($this->t82_codapo == ""?@$GLOBALS["HTTP_POST_VARS"]["t82_codapo"]:$this->t82_codapo);
       $this->t82_codbem = ($this->t82_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t82_codbem"]:$this->t82_codbem);
     }
   }
   // funcao para inclusao
   function incluir ($t82_codapo,$t82_codbem){ 
      $this->atualizacampos();
       $this->t82_codapo = $t82_codapo; 
       $this->t82_codbem = $t82_codbem; 
     if(($this->t82_codapo == null) || ($this->t82_codapo == "") ){ 
       $this->erro_sql = " Campo t82_codapo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t82_codbem == null) || ($this->t82_codbem == "") ){ 
       $this->erro_sql = " Campo t82_codbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into apolitem(
                                       t82_codapo 
                                      ,t82_codbem 
                       )
                values (
                                $this->t82_codapo 
                               ,$this->t82_codbem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da ap�lice ($this->t82_codapo."-".$this->t82_codbem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da ap�lice j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da ap�lice ($this->t82_codapo."-".$this->t82_codbem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t82_codapo."-".$this->t82_codbem;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t82_codapo,$this->t82_codbem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5820,'$this->t82_codapo','I')");
       $resac = db_query("insert into db_acountkey values($acount,5821,'$this->t82_codbem','I')");
       $resac = db_query("insert into db_acount values($acount,929,5820,'','".AddSlashes(pg_result($resaco,0,'t82_codapo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,929,5821,'','".AddSlashes(pg_result($resaco,0,'t82_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t82_codapo=null,$t82_codbem=null) { 
      $this->atualizacampos();
     $sql = " update apolitem set ";
     $virgula = "";
     if(trim($this->t82_codapo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t82_codapo"])){ 
       $sql  .= $virgula." t82_codapo = $this->t82_codapo ";
       $virgula = ",";
       if(trim($this->t82_codapo) == null ){ 
         $this->erro_sql = " Campo C�digo ap�lice nao Informado.";
         $this->erro_campo = "t82_codapo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t82_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t82_codbem"])){ 
       $sql  .= $virgula." t82_codbem = $this->t82_codbem ";
       $virgula = ",";
       if(trim($this->t82_codbem) == null ){ 
         $this->erro_sql = " Campo C�digo do bem nao Informado.";
         $this->erro_campo = "t82_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t82_codapo!=null){
       $sql .= " t82_codapo = $this->t82_codapo";
     }
     if($t82_codbem!=null){
       $sql .= " and  t82_codbem = $this->t82_codbem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t82_codapo,$this->t82_codbem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5820,'$this->t82_codapo','A')");
         $resac = db_query("insert into db_acountkey values($acount,5821,'$this->t82_codbem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t82_codapo"]))
           $resac = db_query("insert into db_acount values($acount,929,5820,'".AddSlashes(pg_result($resaco,$conresaco,'t82_codapo'))."','$this->t82_codapo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t82_codbem"]))
           $resac = db_query("insert into db_acount values($acount,929,5821,'".AddSlashes(pg_result($resaco,$conresaco,'t82_codbem'))."','$this->t82_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ap�lice nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t82_codapo."-".$this->t82_codbem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ap�lice nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t82_codapo."-".$this->t82_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t82_codapo."-".$this->t82_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t82_codapo=null,$t82_codbem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t82_codapo,$t82_codbem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5820,'$t82_codapo','E')");
         $resac = db_query("insert into db_acountkey values($acount,5821,'$t82_codbem','E')");
         $resac = db_query("insert into db_acount values($acount,929,5820,'','".AddSlashes(pg_result($resaco,$iresaco,'t82_codapo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,929,5821,'','".AddSlashes(pg_result($resaco,$iresaco,'t82_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from apolitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t82_codapo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t82_codapo = $t82_codapo ";
        }
        if($t82_codbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t82_codbem = $t82_codbem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ap�lice nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t82_codapo."-".$t82_codbem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ap�lice nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t82_codapo."-".$t82_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t82_codapo."-".$t82_codbem;
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
        $this->erro_sql   = "Record Vazio na Tabela:apolitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t82_codapo=null,$t82_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from apolitem ";
     $sql .= "      inner join bens  on  bens.t52_bem = apolitem.t82_codbem";
     $sql .= "      inner join apolice  on  apolice.t81_codapo = apolitem.t82_codapo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join seguradoras  on  seguradoras.t80_segura = apolice.t81_codseg";
     $sql2 = "";
     if($dbwhere==""){
       if($t82_codapo!=null ){
         $sql2 .= " where apolitem.t82_codapo = $t82_codapo "; 
       } 
       if($t82_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " apolitem.t82_codbem = $t82_codbem "; 
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
   function sql_query_apolice ( $t82_codapo=null,$t82_codbem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from apolitem ";
     $sql .= "      inner join bens  on  bens.t52_bem = apolitem.t82_codbem";
     $sql .= "      inner join apolice  on  apolice.t81_codapo = apolitem.t82_codapo";
     $sql2 = "";
     if($dbwhere==""){
       if($t82_codapo!=null ){
         $sql2 .= " where apolitem.t82_codapo = $t82_codapo ";
       }
       if($t82_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " apolitem.t82_codbem = $t82_codbem ";
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
   function sql_query_file ( $t82_codapo=null,$t82_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from apolitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t82_codapo!=null ){
         $sql2 .= " where apolitem.t82_codapo = $t82_codapo "; 
       } 
       if($t82_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " apolitem.t82_codbem = $t82_codbem "; 
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