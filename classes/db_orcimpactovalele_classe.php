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
//CLASSE DA ENTIDADE orcimpactovalele
class cl_orcimpactovalele { 
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
   var $o94_codseqimp = 0; 
   var $o94_codele = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o94_codseqimp = int8 = Sequencial 
                 o94_codele = int4 = Elemento 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactovalele() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactovalele"); 
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
       $this->o94_codseqimp = ($this->o94_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o94_codseqimp"]:$this->o94_codseqimp);
       $this->o94_codele = ($this->o94_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["o94_codele"]:$this->o94_codele);
     }else{
       $this->o94_codseqimp = ($this->o94_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o94_codseqimp"]:$this->o94_codseqimp);
       $this->o94_codele = ($this->o94_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["o94_codele"]:$this->o94_codele);
     }
   }
   // funcao para inclusao
   function incluir ($o94_codseqimp,$o94_codele){ 
      $this->atualizacampos();
       $this->o94_codseqimp = $o94_codseqimp; 
       $this->o94_codele = $o94_codele; 
     if(($this->o94_codseqimp == null) || ($this->o94_codseqimp == "") ){ 
       $this->erro_sql = " Campo o94_codseqimp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o94_codele == null) || ($this->o94_codele == "") ){ 
       $this->erro_sql = " Campo o94_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactovalele(
                                       o94_codseqimp 
                                      ,o94_codele 
                       )
                values (
                                $this->o94_codseqimp 
                               ,$this->o94_codele 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores dos elementos ($this->o94_codseqimp."-".$this->o94_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores dos elementos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores dos elementos ($this->o94_codseqimp."-".$this->o94_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o94_codseqimp."-".$this->o94_codele;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o94_codseqimp,$this->o94_codele));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6652,'$this->o94_codseqimp','I')");
       $resac = db_query("insert into db_acountkey values($acount,6653,'$this->o94_codele','I')");
       $resac = db_query("insert into db_acount values($acount,1091,6652,'','".AddSlashes(pg_result($resaco,0,'o94_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1091,6653,'','".AddSlashes(pg_result($resaco,0,'o94_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o94_codseqimp=null,$o94_codele=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactovalele set ";
     $virgula = "";
     if(trim($this->o94_codseqimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o94_codseqimp"])){ 
       $sql  .= $virgula." o94_codseqimp = $this->o94_codseqimp ";
       $virgula = ",";
       if(trim($this->o94_codseqimp) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o94_codseqimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o94_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o94_codele"])){ 
       $sql  .= $virgula." o94_codele = $this->o94_codele ";
       $virgula = ",";
       if(trim($this->o94_codele) == null ){ 
         $this->erro_sql = " Campo Elemento nao Informado.";
         $this->erro_campo = "o94_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o94_codseqimp!=null){
       $sql .= " o94_codseqimp = $this->o94_codseqimp";
     }
     if($o94_codele!=null){
       $sql .= " and  o94_codele = $this->o94_codele";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o94_codseqimp,$this->o94_codele));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6652,'$this->o94_codseqimp','A')");
         $resac = db_query("insert into db_acountkey values($acount,6653,'$this->o94_codele','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o94_codseqimp"]))
           $resac = db_query("insert into db_acount values($acount,1091,6652,'".AddSlashes(pg_result($resaco,$conresaco,'o94_codseqimp'))."','$this->o94_codseqimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o94_codele"]))
           $resac = db_query("insert into db_acount values($acount,1091,6653,'".AddSlashes(pg_result($resaco,$conresaco,'o94_codele'))."','$this->o94_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos elementos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o94_codseqimp."-".$this->o94_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos elementos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o94_codseqimp."-".$this->o94_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o94_codseqimp."-".$this->o94_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o94_codseqimp=null,$o94_codele=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o94_codseqimp,$o94_codele));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6652,'$o94_codseqimp','E')");
         $resac = db_query("insert into db_acountkey values($acount,6653,'$o94_codele','E')");
         $resac = db_query("insert into db_acount values($acount,1091,6652,'','".AddSlashes(pg_result($resaco,$iresaco,'o94_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1091,6653,'','".AddSlashes(pg_result($resaco,$iresaco,'o94_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactovalele
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o94_codseqimp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o94_codseqimp = $o94_codseqimp ";
        }
        if($o94_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o94_codele = $o94_codele ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos elementos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o94_codseqimp."-".$o94_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos elementos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o94_codseqimp."-".$o94_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o94_codseqimp."-".$o94_codele;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactovalele";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o94_codseqimp=null,$o94_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalele ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcimpactovalele.o94_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcimpactoval  on  orcimpactoval.o91_codseqimp = orcimpactovalele.o94_codseqimp";
     $sql .= "      inner join orcimpacto  as a on   a.o90_codimp = orcimpactoval.o91_codimp";
     $sql2 = "";
     if($dbwhere==""){
       if($o94_codseqimp!=null ){
         $sql2 .= " where orcimpactovalele.o94_codseqimp = $o94_codseqimp "; 
       } 
       if($o94_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalele.o94_codele = $o94_codele "; 
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
   function sql_query_file ( $o94_codseqimp=null,$o94_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalele ";
     $sql2 = "";
     if($dbwhere==""){
       if($o94_codseqimp!=null ){
         $sql2 .= " where orcimpactovalele.o94_codseqimp = $o94_codseqimp "; 
       } 
       if($o94_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcimpactovalele.o94_codele = $o94_codele "; 
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