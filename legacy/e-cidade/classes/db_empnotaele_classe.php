<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: empenho
//CLASSE DA ENTIDADE empnotaele
class cl_empnotaele { 
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
   var $e70_codnota = 0; 
   var $e70_codele = 0; 
   var $e70_valor = 0; 
   var $e70_vlranu = 0; 
   var $e70_vlrliq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e70_codnota = int4 = Nota 
                 e70_codele = int4 = Código Elemento 
                 e70_valor = float8 = Valor 
                 e70_vlranu = float8 = Valor anulado 
                 e70_vlrliq = float8 = Valor liquidado 
                 ";
   //funcao construtor da classe 
   function cl_empnotaele() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empnotaele"); 
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
       $this->e70_codnota = ($this->e70_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_codnota"]:$this->e70_codnota);
       $this->e70_codele = ($this->e70_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_codele"]:$this->e70_codele);
       $this->e70_valor = ($this->e70_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_valor"]:$this->e70_valor);
       $this->e70_vlranu = ($this->e70_vlranu == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_vlranu"]:$this->e70_vlranu);
       $this->e70_vlrliq = ($this->e70_vlrliq == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_vlrliq"]:$this->e70_vlrliq);
     }else{
       $this->e70_codnota = ($this->e70_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_codnota"]:$this->e70_codnota);
       $this->e70_codele = ($this->e70_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e70_codele"]:$this->e70_codele);
     }
   }
   // funcao para inclusao
   function incluir ($e70_codnota,$e70_codele){ 
      $this->atualizacampos();
     if($this->e70_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "e70_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e70_vlranu == null ){ 
       $this->erro_sql = " Campo Valor anulado nao Informado.";
       $this->erro_campo = "e70_vlranu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e70_vlrliq == null ){ 
       $this->erro_sql = " Campo Valor liquidado nao Informado.";
       $this->erro_campo = "e70_vlrliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e70_codnota = $e70_codnota; 
       $this->e70_codele = $e70_codele; 
     if(($this->e70_codnota == null) || ($this->e70_codnota == "") ){ 
       $this->erro_sql = " Campo e70_codnota nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e70_codele == null) || ($this->e70_codele == "") ){ 
       $this->erro_sql = " Campo e70_codele nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empnotaele(
                                       e70_codnota 
                                      ,e70_codele 
                                      ,e70_valor 
                                      ,e70_vlranu 
                                      ,e70_vlrliq 
                       )
                values (
                                $this->e70_codnota 
                               ,$this->e70_codele 
                               ,$this->e70_valor 
                               ,$this->e70_vlranu 
                               ,$this->e70_vlrliq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Elementos com notas do empenho ($this->e70_codnota."-".$this->e70_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Elementos com notas do empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Elementos com notas do empenho ($this->e70_codnota."-".$this->e70_codele) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e70_codnota."-".$this->e70_codele;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e70_codnota,$this->e70_codele));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6050,'$this->e70_codnota','I')");
       $resac = db_query("insert into db_acountkey values($acount,6051,'$this->e70_codele','I')");
       $resac = db_query("insert into db_acount values($acount,972,6050,'','".AddSlashes(pg_result($resaco,0,'e70_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,972,6051,'','".AddSlashes(pg_result($resaco,0,'e70_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,972,6052,'','".AddSlashes(pg_result($resaco,0,'e70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,972,6053,'','".AddSlashes(pg_result($resaco,0,'e70_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,972,6054,'','".AddSlashes(pg_result($resaco,0,'e70_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e70_codnota=null,$e70_codele=null) { 
      $this->atualizacampos();
     $sql = " update empnotaele set ";
     $virgula = "";
     if(trim($this->e70_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e70_codnota"])){ 
       $sql  .= $virgula." e70_codnota = $this->e70_codnota ";
       $virgula = ",";
       if(trim($this->e70_codnota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "e70_codnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e70_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e70_codele"])){ 
       $sql  .= $virgula." e70_codele = $this->e70_codele ";
       $virgula = ",";
       if(trim($this->e70_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "e70_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e70_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e70_valor"])){ 
       $sql  .= $virgula." e70_valor = $this->e70_valor ";
       $virgula = ",";
       if(trim($this->e70_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "e70_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e70_vlranu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e70_vlranu"])){ 
       $sql  .= $virgula." e70_vlranu = $this->e70_vlranu ";
       $virgula = ",";
       if(trim($this->e70_vlranu) == null ){ 
         $this->erro_sql = " Campo Valor anulado nao Informado.";
         $this->erro_campo = "e70_vlranu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e70_vlrliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e70_vlrliq"])){ 
       $sql  .= $virgula." e70_vlrliq = $this->e70_vlrliq ";
       $virgula = ",";
       if(trim($this->e70_vlrliq) == null ){ 
         $this->erro_sql = " Campo Valor liquidado nao Informado.";
         $this->erro_campo = "e70_vlrliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e70_codnota!=null){
       $sql .= " e70_codnota = $this->e70_codnota";
     }
     if($e70_codele!=null){
       $sql .= " and  e70_codele = $this->e70_codele";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e70_codnota,$this->e70_codele));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6050,'$this->e70_codnota','A')");
         $resac = db_query("insert into db_acountkey values($acount,6051,'$this->e70_codele','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e70_codnota"]))
           $resac = db_query("insert into db_acount values($acount,972,6050,'".AddSlashes(pg_result($resaco,$conresaco,'e70_codnota'))."','$this->e70_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e70_codele"]))
           $resac = db_query("insert into db_acount values($acount,972,6051,'".AddSlashes(pg_result($resaco,$conresaco,'e70_codele'))."','$this->e70_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e70_valor"]))
           $resac = db_query("insert into db_acount values($acount,972,6052,'".AddSlashes(pg_result($resaco,$conresaco,'e70_valor'))."','$this->e70_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e70_vlranu"]))
           $resac = db_query("insert into db_acount values($acount,972,6053,'".AddSlashes(pg_result($resaco,$conresaco,'e70_vlranu'))."','$this->e70_vlranu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e70_vlrliq"]))
           $resac = db_query("insert into db_acount values($acount,972,6054,'".AddSlashes(pg_result($resaco,$conresaco,'e70_vlrliq'))."','$this->e70_vlrliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elementos com notas do empenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e70_codnota."-".$this->e70_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elementos com notas do empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e70_codnota."-".$this->e70_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e70_codnota."-".$this->e70_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e70_codnota=null,$e70_codele=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e70_codnota,$e70_codele));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6050,'$e70_codnota','E')");
         $resac = db_query("insert into db_acountkey values($acount,6051,'$e70_codele','E')");
         $resac = db_query("insert into db_acount values($acount,972,6050,'','".AddSlashes(pg_result($resaco,$iresaco,'e70_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,972,6051,'','".AddSlashes(pg_result($resaco,$iresaco,'e70_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,972,6052,'','".AddSlashes(pg_result($resaco,$iresaco,'e70_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,972,6053,'','".AddSlashes(pg_result($resaco,$iresaco,'e70_vlranu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,972,6054,'','".AddSlashes(pg_result($resaco,$iresaco,'e70_vlrliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empnotaele
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e70_codnota != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e70_codnota = $e70_codnota ";
        }
        if($e70_codele != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e70_codele = $e70_codele ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Elementos com notas do empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e70_codnota."-".$e70_codele;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Elementos com notas do empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e70_codnota."-".$e70_codele;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e70_codnota."-".$e70_codele;
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
        $this->erro_sql   = "Record Vazio na Tabela:empnotaele";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e70_codnota=null,$e70_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaele ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaele.e70_codnota";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empnotaele.e70_codele and orcelemento.o56_anousu = empempenho.e60_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($e70_codnota!=null ){
         $sql2 .= " where empnotaele.e70_codnota = $e70_codnota "; 
       } 
       if($e70_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaele.e70_codele = $e70_codele "; 
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
   function sql_query_file ( $e70_codnota=null,$e70_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaele ";
     $sql2 = "";
     if($dbwhere==""){
       if($e70_codnota!=null ){
         $sql2 .= " where empnotaele.e70_codnota = $e70_codnota "; 
       } 
       if($e70_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaele.e70_codele = $e70_codele "; 
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
   function sql_query_notaliq ( $e70_codnota=null,$e70_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaele ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaele.e70_codnota";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empnotaele.e70_codele and orcelemento.o56_anousu = empempenho.e60_anousu";
     $sql .= "      inner join pagordemnota  on  empnotaele.e70_codnota = pagordemnota.e71_codnota ";
     $sql .= "      inner join pagordem   on  pagordem.e50_codord =  pagordemnota.e71_codord ";
     $sql .= "      inner join pagordemele   on  pagordemele.e53_codord =  pagordem.e50_codord ";
     $sql2 = "";
     if($dbwhere==""){
       if($e70_codnota!=null ){
         $sql2 .= " where empnotaele.e70_codnota = $e70_codnota ";
       }
       if($e70_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empnotaele.e70_codele = $e70_codele ";
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
   function sql_query_ordem ( $e70_codnota=null,$e70_codele=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empnotaele ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = empnotaele.e70_codnota";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empnotaele.e70_codele and orcelemento.o56_anousu = empempenho.e60_anousu";
     $sql .= "      left outer join pagordemnota  on  empnotaele.e70_codnota = pagordemnota.e71_codnota and e71_anulado='f'";
     $sql2 = "";
     if($dbwhere==""){
       if($e70_codnota!=null ){
         $sql2 .= " where empnotaele.e70_codnota = $e70_codnota ";
       }
       if($e70_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " empnotaele.e70_codele = $e70_codele ";
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
function sql_query_nf ( $e70_codnota=null,$e70_codele=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empnotaele ";
     $sql .= "      inner join empnota  on      empnota.e69_codnota = empnotaele.e70_codnota";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho   on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empnotaele.e70_codele ";
     $sql .= "                              and orcelemento.o56_anousu = empempenho.e60_anousu";
     $sql .= "      left  join empnotaord    on m72_codnota = e69_codnota ";
     $sql .= "      left  join matordem      on m51_codordem = m72_codordem ";
     $sql .= "      left  join empnotaprocesso on empnota.e69_codnota =  empnotaprocesso.e04_empnota";
     $sql .= "      left  join pagordemnota on empnota.e69_codnota = pagordemnota.e71_codnota";
     $sql .= "      left  join pagordemprocesso on pagordemnota.e71_codord = pagordemprocesso.e03_pagordem";
     
     $sql2 = "";
     if($dbwhere==""){
       if($e70_codnota!=null ){
         $sql2 .= " where empnotaele.e70_codnota = $e70_codnota "; 
       } 
       if($e70_codele!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " empnotaele.e70_codele = $e70_codele "; 
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