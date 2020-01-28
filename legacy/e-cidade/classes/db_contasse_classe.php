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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE contasse
class cl_contasse { 
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
   var $h14_regime = 0; 
   var $h14_tpcont = null; 
   var $h14_assent = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h14_regime = int4 = Codigo do Regime do Func. 
                 h14_tpcont = varchar(2) = Tipo 
                 h14_assent = varchar(5) = Assentamento 
                 ";
   //funcao construtor da classe 
   function cl_contasse() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contasse"); 
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
       $this->h14_regime = ($this->h14_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_regime"]:$this->h14_regime);
       $this->h14_tpcont = ($this->h14_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_tpcont"]:$this->h14_tpcont);
       $this->h14_assent = ($this->h14_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_assent"]:$this->h14_assent);
     }else{
       $this->h14_regime = ($this->h14_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_regime"]:$this->h14_regime);
       $this->h14_tpcont = ($this->h14_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_tpcont"]:$this->h14_tpcont);
       $this->h14_assent = ($this->h14_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h14_assent"]:$this->h14_assent);
     }
   }
   // funcao para inclusao
   function incluir ($h14_regime,$h14_tpcont,$h14_assent){ 
      $this->atualizacampos();
       $this->h14_regime = $h14_regime; 
       $this->h14_tpcont = $h14_tpcont; 
       $this->h14_assent = $h14_assent; 
     if(($this->h14_regime == null) || ($this->h14_regime == "") ){ 
       $this->erro_sql = " Campo h14_regime nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->h14_tpcont == null) || ($this->h14_tpcont == "") ){ 
       $this->erro_sql = " Campo h14_tpcont nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->h14_assent == null) || ($this->h14_assent == "") ){ 
       $this->erro_sql = " Campo h14_assent nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contasse(
                                       h14_regime 
                                      ,h14_tpcont 
                                      ,h14_assent 
                       )
                values (
                                $this->h14_regime 
                               ,'$this->h14_tpcont' 
                               ,'$this->h14_assent' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assentamentos para cada tipo de contrato           ($this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assentamentos para cada tipo de contrato           já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assentamentos para cada tipo de contrato           ($this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h14_regime,$this->h14_tpcont,$this->h14_assent));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3844,'$this->h14_regime','I')");
       $resac = db_query("insert into db_acountkey values($acount,3845,'$this->h14_tpcont','I')");
       $resac = db_query("insert into db_acountkey values($acount,3846,'$this->h14_assent','I')");
       $resac = db_query("insert into db_acount values($acount,541,3844,'','".AddSlashes(pg_result($resaco,0,'h14_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,541,3845,'','".AddSlashes(pg_result($resaco,0,'h14_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,541,3846,'','".AddSlashes(pg_result($resaco,0,'h14_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h14_regime=null,$h14_tpcont=null,$h14_assent=null) { 
      $this->atualizacampos();
     $sql = " update contasse set ";
     $virgula = "";
     if(trim($this->h14_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h14_regime"])){ 
       $sql  .= $virgula." h14_regime = $this->h14_regime ";
       $virgula = ",";
       if(trim($this->h14_regime) == null ){ 
         $this->erro_sql = " Campo Codigo do Regime do Func. nao Informado.";
         $this->erro_campo = "h14_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h14_tpcont)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h14_tpcont"])){ 
       $sql  .= $virgula." h14_tpcont = '$this->h14_tpcont' ";
       $virgula = ",";
       if(trim($this->h14_tpcont) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "h14_tpcont";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h14_assent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h14_assent"])){ 
       $sql  .= $virgula." h14_assent = '$this->h14_assent' ";
       $virgula = ",";
       if(trim($this->h14_assent) == null ){ 
         $this->erro_sql = " Campo Assentamento nao Informado.";
         $this->erro_campo = "h14_assent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h14_regime!=null){
       $sql .= " h14_regime = $this->h14_regime";
     }
     if($h14_tpcont!=null){
       $sql .= " and  h14_tpcont = '$this->h14_tpcont'";
     }
     if($h14_assent!=null){
       $sql .= " and  h14_assent = '$this->h14_assent'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h14_regime,$this->h14_tpcont,$this->h14_assent));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3844,'$this->h14_regime','A')");
         $resac = db_query("insert into db_acountkey values($acount,3845,'$this->h14_tpcont','A')");
         $resac = db_query("insert into db_acountkey values($acount,3846,'$this->h14_assent','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h14_regime"]))
           $resac = db_query("insert into db_acount values($acount,541,3844,'".AddSlashes(pg_result($resaco,$conresaco,'h14_regime'))."','$this->h14_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h14_tpcont"]))
           $resac = db_query("insert into db_acount values($acount,541,3845,'".AddSlashes(pg_result($resaco,$conresaco,'h14_tpcont'))."','$this->h14_tpcont',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h14_assent"]))
           $resac = db_query("insert into db_acount values($acount,541,3846,'".AddSlashes(pg_result($resaco,$conresaco,'h14_assent'))."','$this->h14_assent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos para cada tipo de contrato           nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos para cada tipo de contrato           nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h14_regime."-".$this->h14_tpcont."-".$this->h14_assent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h14_regime=null,$h14_tpcont=null,$h14_assent=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h14_regime,$h14_tpcont,$h14_assent));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3844,'$h14_regime','E')");
         $resac = db_query("insert into db_acountkey values($acount,3845,'$h14_tpcont','E')");
         $resac = db_query("insert into db_acountkey values($acount,3846,'$h14_assent','E')");
         $resac = db_query("insert into db_acount values($acount,541,3844,'','".AddSlashes(pg_result($resaco,$iresaco,'h14_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,541,3845,'','".AddSlashes(pg_result($resaco,$iresaco,'h14_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,541,3846,'','".AddSlashes(pg_result($resaco,$iresaco,'h14_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contasse
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h14_regime != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h14_regime = $h14_regime ";
        }
        if($h14_tpcont != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h14_tpcont = '$h14_tpcont' ";
        }
        if($h14_assent != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h14_assent = '$h14_assent' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos para cada tipo de contrato           nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h14_regime."-".$h14_tpcont."-".$h14_assent;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos para cada tipo de contrato           nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h14_regime."-".$h14_tpcont."-".$h14_assent;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h14_regime."-".$h14_tpcont."-".$h14_assent;
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
        $this->erro_sql   = "Record Vazio na Tabela:contasse";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>