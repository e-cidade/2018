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
//CLASSE DA ENTIDADE histbemtrans
class cl_histbemtrans { 
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
   var $t97_histbem = 0; 
   var $t97_codtran = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t97_histbem = int8 = Sequencial do lançamento de histórico 
                 t97_codtran = int8 = Sequencial da lançamento das transferências 
                 ";
   //funcao construtor da classe 
   function cl_histbemtrans() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histbemtrans"); 
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
       $this->t97_histbem = ($this->t97_histbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t97_histbem"]:$this->t97_histbem);
       $this->t97_codtran = ($this->t97_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t97_codtran"]:$this->t97_codtran);
     }else{
       $this->t97_histbem = ($this->t97_histbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t97_histbem"]:$this->t97_histbem);
       $this->t97_codtran = ($this->t97_codtran == ""?@$GLOBALS["HTTP_POST_VARS"]["t97_codtran"]:$this->t97_codtran);
     }
   }
   // funcao para inclusao
   function incluir ($t97_histbem,$t97_codtran){ 
      $this->atualizacampos();
       $this->t97_histbem = $t97_histbem; 
       $this->t97_codtran = $t97_codtran; 
     if(($this->t97_histbem == null) || ($this->t97_histbem == "") ){ 
       $this->erro_sql = " Campo t97_histbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t97_codtran == null) || ($this->t97_codtran == "") ){ 
       $this->erro_sql = " Campo t97_codtran nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histbemtrans(
                                       t97_histbem 
                                      ,t97_codtran 
                       )
                values (
                                $this->t97_histbem 
                               ,$this->t97_codtran 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento da confirmação da transferência ($this->t97_histbem."-".$this->t97_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento da confirmação da transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento da confirmação da transferência ($this->t97_histbem."-".$this->t97_codtran) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t97_histbem."-".$this->t97_codtran;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t97_histbem,$this->t97_codtran));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5835,'$this->t97_histbem','I')");
       $resac = db_query("insert into db_acountkey values($acount,5836,'$this->t97_codtran','I')");
       $resac = db_query("insert into db_acount values($acount,933,5835,'','".AddSlashes(pg_result($resaco,0,'t97_histbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,933,5836,'','".AddSlashes(pg_result($resaco,0,'t97_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t97_histbem=null,$t97_codtran=null) { 
      $this->atualizacampos();
     $sql = " update histbemtrans set ";
     $virgula = "";
     if(trim($this->t97_histbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t97_histbem"])){ 
       $sql  .= $virgula." t97_histbem = $this->t97_histbem ";
       $virgula = ",";
       if(trim($this->t97_histbem) == null ){ 
         $this->erro_sql = " Campo Sequencial do lançamento de histórico nao Informado.";
         $this->erro_campo = "t97_histbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t97_codtran)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t97_codtran"])){ 
       $sql  .= $virgula." t97_codtran = $this->t97_codtran ";
       $virgula = ",";
       if(trim($this->t97_codtran) == null ){ 
         $this->erro_sql = " Campo Sequencial da lançamento das transferências nao Informado.";
         $this->erro_campo = "t97_codtran";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t97_histbem!=null){
       $sql .= " t97_histbem = $this->t97_histbem";
     }
     if($t97_codtran!=null){
       $sql .= " and  t97_codtran = $this->t97_codtran";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t97_histbem,$this->t97_codtran));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5835,'$this->t97_histbem','A')");
         $resac = db_query("insert into db_acountkey values($acount,5836,'$this->t97_codtran','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t97_histbem"]))
           $resac = db_query("insert into db_acount values($acount,933,5835,'".AddSlashes(pg_result($resaco,$conresaco,'t97_histbem'))."','$this->t97_histbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t97_codtran"]))
           $resac = db_query("insert into db_acount values($acount,933,5836,'".AddSlashes(pg_result($resaco,$conresaco,'t97_codtran'))."','$this->t97_codtran',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento da confirmação da transferência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t97_histbem."-".$this->t97_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento da confirmação da transferência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t97_histbem."-".$this->t97_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t97_histbem."-".$this->t97_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t97_histbem=null,$t97_codtran=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t97_histbem,$t97_codtran));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5835,'$t97_histbem','E')");
         $resac = db_query("insert into db_acountkey values($acount,5836,'$t97_codtran','E')");
         $resac = db_query("insert into db_acount values($acount,933,5835,'','".AddSlashes(pg_result($resaco,$iresaco,'t97_histbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,933,5836,'','".AddSlashes(pg_result($resaco,$iresaco,'t97_codtran'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histbemtrans
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t97_histbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t97_histbem = $t97_histbem ";
        }
        if($t97_codtran != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t97_codtran = $t97_codtran ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento da confirmação da transferência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t97_histbem."-".$t97_codtran;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento da confirmação da transferência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t97_histbem."-".$t97_codtran;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t97_histbem."-".$t97_codtran;
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
        $this->erro_sql   = "Record Vazio na Tabela:histbemtrans";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t97_histbem=null,$t97_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbemtrans ";
     $sql .= "      inner join histbem  on  histbem.t56_histbem = histbemtrans.t97_histbem";
     $sql .= "      inner join benstransf  on  benstransf.t93_codtran = histbemtrans.t97_codtran";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = histbem.t56_depart";
     $sql .= "      inner join bens  as a on   a.t52_bem = histbem.t56_codbem";
     $sql .= "      inner join situabens  on  situabens.t70_situac = histbem.t56_situac";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benstransf.t93_id_usuario";
     $sql .= "      inner join db_depart  as b on   b.coddepto = benstransf.t93_depart";
     $sql2 = "";
     if($dbwhere==""){
       if($t97_histbem!=null ){
         $sql2 .= " where histbemtrans.t97_histbem = $t97_histbem "; 
       } 
       if($t97_codtran!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " histbemtrans.t97_codtran = $t97_codtran "; 
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
   function sql_query_file ( $t97_histbem=null,$t97_codtran=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histbemtrans ";
     $sql2 = "";
     if($dbwhere==""){
       if($t97_histbem!=null ){
         $sql2 .= " where histbemtrans.t97_histbem = $t97_histbem "; 
       } 
       if($t97_codtran!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " histbemtrans.t97_codtran = $t97_codtran "; 
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