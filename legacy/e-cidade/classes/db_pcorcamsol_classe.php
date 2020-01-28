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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamsol
class cl_pcorcamsol { 
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
   var $pc27_codorc = 0; 
   var $pc27_solic = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc27_codorc = int4 = Código do orçamento 
                 pc27_solic = int4 = numero da solicitacao 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamsol() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamsol"); 
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
       $this->pc27_codorc = ($this->pc27_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc27_codorc"]:$this->pc27_codorc);
       $this->pc27_solic = ($this->pc27_solic == ""?@$GLOBALS["HTTP_POST_VARS"]["pc27_solic"]:$this->pc27_solic);
     }else{
       $this->pc27_codorc = ($this->pc27_codorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc27_codorc"]:$this->pc27_codorc);
       $this->pc27_solic = ($this->pc27_solic == ""?@$GLOBALS["HTTP_POST_VARS"]["pc27_solic"]:$this->pc27_solic);
     }
   }
   // funcao para inclusao
   function incluir ($pc27_codorc,$pc27_solic){ 
      $this->atualizacampos();
       $this->pc27_codorc = $pc27_codorc; 
       $this->pc27_solic = $pc27_solic; 
     if(($this->pc27_codorc == null) || ($this->pc27_codorc == "") ){ 
       $this->erro_sql = " Campo pc27_codorc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc27_solic == null) || ($this->pc27_solic == "") ){ 
       $this->erro_sql = " Campo pc27_solic nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamsol(
                                       pc27_codorc 
                                      ,pc27_solic 
                       )
                values (
                                $this->pc27_codorc 
                               ,$this->pc27_solic 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitação do orcamento ($this->pc27_codorc."-".$this->pc27_solic) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitação do orcamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitação do orcamento ($this->pc27_codorc."-".$this->pc27_solic) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc27_codorc."-".$this->pc27_solic;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc27_codorc,$this->pc27_solic));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5524,'$this->pc27_codorc','I')");
       $resac = db_query("insert into db_acountkey values($acount,5525,'$this->pc27_solic','I')");
       $resac = db_query("insert into db_acount values($acount,861,5524,'','".AddSlashes(pg_result($resaco,0,'pc27_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,861,5525,'','".AddSlashes(pg_result($resaco,0,'pc27_solic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc27_codorc=null,$pc27_solic=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamsol set ";
     $virgula = "";
     if(trim($this->pc27_codorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc27_codorc"])){ 
       $sql  .= $virgula." pc27_codorc = $this->pc27_codorc ";
       $virgula = ",";
       if(trim($this->pc27_codorc) == null ){ 
         $this->erro_sql = " Campo Código do orçamento nao Informado.";
         $this->erro_campo = "pc27_codorc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc27_solic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc27_solic"])){ 
       $sql  .= $virgula." pc27_solic = $this->pc27_solic ";
       $virgula = ",";
       if(trim($this->pc27_solic) == null ){ 
         $this->erro_sql = " Campo numero da solicitacao nao Informado.";
         $this->erro_campo = "pc27_solic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc27_codorc!=null){
       $sql .= " pc27_codorc = $this->pc27_codorc";
     }
     if($pc27_solic!=null){
       $sql .= " and  pc27_solic = $this->pc27_solic";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc27_codorc,$this->pc27_solic));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5524,'$this->pc27_codorc','A')");
         $resac = db_query("insert into db_acountkey values($acount,5525,'$this->pc27_solic','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc27_codorc"]))
           $resac = db_query("insert into db_acount values($acount,861,5524,'".AddSlashes(pg_result($resaco,$conresaco,'pc27_codorc'))."','$this->pc27_codorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc27_solic"]))
           $resac = db_query("insert into db_acount values($acount,861,5525,'".AddSlashes(pg_result($resaco,$conresaco,'pc27_solic'))."','$this->pc27_solic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação do orcamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc27_codorc."-".$this->pc27_solic;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação do orcamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc27_codorc."-".$this->pc27_solic;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc27_codorc."-".$this->pc27_solic;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc27_codorc=null,$pc27_solic=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc27_codorc,$pc27_solic));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5524,'$pc27_codorc','E')");
         $resac = db_query("insert into db_acountkey values($acount,5525,'$pc27_solic','E')");
         $resac = db_query("insert into db_acount values($acount,861,5524,'','".AddSlashes(pg_result($resaco,$iresaco,'pc27_codorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,861,5525,'','".AddSlashes(pg_result($resaco,$iresaco,'pc27_solic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamsol
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc27_codorc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc27_codorc = $pc27_codorc ";
        }
        if($pc27_solic != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc27_solic = $pc27_solic ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação do orcamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc27_codorc."-".$pc27_solic;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação do orcamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc27_codorc."-".$pc27_solic;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc27_codorc."-".$pc27_solic;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamsol";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>