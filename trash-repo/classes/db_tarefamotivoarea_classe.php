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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefamotivoarea
class cl_tarefamotivoarea { 
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
   var $at33_sequencial = 0; 
   var $at33_tarefacadmotivo = 0; 
   var $at33_atendcadarea = 0; 
   var $at33_proced = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at33_sequencial = int4 = C�digo sequencial 
                 at33_tarefacadmotivo = int4 = Motivo 
                 at33_atendcadarea = int4 = �rea 
                 at33_proced = int4 = Procedimento 
                 ";
   //funcao construtor da classe 
   function cl_tarefamotivoarea() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefamotivoarea"); 
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
       $this->at33_sequencial = ($this->at33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at33_sequencial"]:$this->at33_sequencial);
       $this->at33_tarefacadmotivo = ($this->at33_tarefacadmotivo == ""?@$GLOBALS["HTTP_POST_VARS"]["at33_tarefacadmotivo"]:$this->at33_tarefacadmotivo);
       $this->at33_atendcadarea = ($this->at33_atendcadarea == ""?@$GLOBALS["HTTP_POST_VARS"]["at33_atendcadarea"]:$this->at33_atendcadarea);
       $this->at33_proced = ($this->at33_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["at33_proced"]:$this->at33_proced);
     }else{
       $this->at33_sequencial = ($this->at33_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at33_sequencial"]:$this->at33_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at33_sequencial){ 
      $this->atualizacampos();
     if($this->at33_tarefacadmotivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "at33_tarefacadmotivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at33_atendcadarea == null ){ 
       $this->erro_sql = " Campo �rea nao Informado.";
       $this->erro_campo = "at33_atendcadarea";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at33_proced == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "at33_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at33_sequencial == "" || $at33_sequencial == null ){
       $result = db_query("select nextval('tarefamotivoarea_at33_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefamotivoarea_at33_sequencial_seq do campo: at33_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at33_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefamotivoarea_at33_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at33_sequencial)){
         $this->erro_sql = " Campo at33_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at33_sequencial = $at33_sequencial; 
       }
     }
     if(($this->at33_sequencial == null) || ($this->at33_sequencial == "") ){ 
       $this->erro_sql = " Campo at33_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefamotivoarea(
                                       at33_sequencial 
                                      ,at33_tarefacadmotivo 
                                      ,at33_atendcadarea 
                                      ,at33_proced 
                       )
                values (
                                $this->at33_sequencial 
                               ,$this->at33_tarefacadmotivo 
                               ,$this->at33_atendcadarea 
                               ,$this->at33_proced 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tarefamotivoarea ($this->at33_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tarefamotivoarea j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tarefamotivoarea ($this->at33_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at33_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at33_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9882,'$this->at33_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1696,9882,'','".AddSlashes(pg_result($resaco,0,'at33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1696,9883,'','".AddSlashes(pg_result($resaco,0,'at33_tarefacadmotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1696,9884,'','".AddSlashes(pg_result($resaco,0,'at33_atendcadarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1696,9885,'','".AddSlashes(pg_result($resaco,0,'at33_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at33_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefamotivoarea set ";
     $virgula = "";
     if(trim($this->at33_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at33_sequencial"])){ 
       $sql  .= $virgula." at33_sequencial = $this->at33_sequencial ";
       $virgula = ",";
       if(trim($this->at33_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo sequencial nao Informado.";
         $this->erro_campo = "at33_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at33_tarefacadmotivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at33_tarefacadmotivo"])){ 
       $sql  .= $virgula." at33_tarefacadmotivo = $this->at33_tarefacadmotivo ";
       $virgula = ",";
       if(trim($this->at33_tarefacadmotivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "at33_tarefacadmotivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at33_atendcadarea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at33_atendcadarea"])){ 
       $sql  .= $virgula." at33_atendcadarea = $this->at33_atendcadarea ";
       $virgula = ",";
       if(trim($this->at33_atendcadarea) == null ){ 
         $this->erro_sql = " Campo �rea nao Informado.";
         $this->erro_campo = "at33_atendcadarea";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at33_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at33_proced"])){ 
       $sql  .= $virgula." at33_proced = $this->at33_proced ";
       $virgula = ",";
       if(trim($this->at33_proced) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "at33_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at33_sequencial!=null){
       $sql .= " at33_sequencial = $this->at33_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at33_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9882,'$this->at33_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at33_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1696,9882,'".AddSlashes(pg_result($resaco,$conresaco,'at33_sequencial'))."','$this->at33_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at33_tarefacadmotivo"]))
           $resac = db_query("insert into db_acount values($acount,1696,9883,'".AddSlashes(pg_result($resaco,$conresaco,'at33_tarefacadmotivo'))."','$this->at33_tarefacadmotivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at33_atendcadarea"]))
           $resac = db_query("insert into db_acount values($acount,1696,9884,'".AddSlashes(pg_result($resaco,$conresaco,'at33_atendcadarea'))."','$this->at33_atendcadarea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at33_proced"]))
           $resac = db_query("insert into db_acount values($acount,1696,9885,'".AddSlashes(pg_result($resaco,$conresaco,'at33_proced'))."','$this->at33_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tarefamotivoarea nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at33_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tarefamotivoarea nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at33_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at33_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at33_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at33_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9882,'$at33_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1696,9882,'','".AddSlashes(pg_result($resaco,$iresaco,'at33_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1696,9883,'','".AddSlashes(pg_result($resaco,$iresaco,'at33_tarefacadmotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1696,9884,'','".AddSlashes(pg_result($resaco,$iresaco,'at33_atendcadarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1696,9885,'','".AddSlashes(pg_result($resaco,$iresaco,'at33_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefamotivoarea
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at33_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at33_sequencial = $at33_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tarefamotivoarea nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at33_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tarefamotivoarea nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at33_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at33_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefamotivoarea";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>